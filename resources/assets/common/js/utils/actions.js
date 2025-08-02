import {
    MSG_SUCCESS,
    MSG_ERROR_GENERAL,
    MSG_ERROR_ATEMPION_FIELDS,
    MSG_ERROR_DELETING,
    MSG_SUCCESS_USER_REGISTERED_VERIFY,
} from "#common/js/utils/messages";
import { DataTablesCustom } from "#common/js/utils/datatables.js";
import { msgDefault, msgError } from "#common/js/utils/messages";
import { effectsTransitions } from "#common/js/utils/effects.js";

import { loadingBodyStart, loadingBodyStop, loadingBtnStart, loadingBtnStop } from "#common/js/utils/loading";

import { removeErrorsAll, removeMasksFields, defineCityByName, resetFieldsAddress } from "#common/js/utils/functions";

/**
 *
 * @param {Object - formulário} form
 * @param {StringClass - Botão de click} btn
 * @param {Boolean - Retornar a página de listagem} backToList
 * @param {StringClass - Arquivos } arrayFilesName
 * @param {Array - Campos para remover maskara. ['0|campo1|campo2|campo3','1|campo4|campo5', '2|campo6' ]  } removeMaskList
 * @param {StringClass - Título do botão de Click} btnTitle
 * consulte a numeração para remoção de maskara no arquivo de enum_type_mask
 */

export async function Save(
    form,
    btn,
    backToList,
    removeMaskList = [],
    arrayFilesName = [],
    includeData = null,
    btnTitle = "Salvar",
    btnIcon = "fas fa-check",
    msgCustomSuccess = MSG_SUCCESS,
    msgCustomError = MSG_ERROR_GENERAL,
) {
    // Resgatando todos os dados do form
    let data = form.serialize();

    // Remover Maskara dos dados especificados
    if (removeMaskList.length > 0) {
        data = removeMasksFields(removeMaskList, data + "&");
    }

    // Convertendo a string 'data' em um array associativo, além disso, a função decodeURIComponent foi usada para converter strings
    // do tipo regExpo para string normal, pois o backend n entende dados do tipo formData com regExpo.
    let aux = data.split("&");
    let dataFormated = {};

    aux.forEach((element) => {
        const [key, value] = element.split("=");

        if (key != null) {
            // Identificando se é multiselect (quando a chave original tem '%5B%5D' ou '%5B\d*\%5D')
            // \%5B\d*\%5D  (o d* represente qualquer numero inteiro)
            const isMultiselect = key.includes("%5B%5D") || /\%5B\d*\%5D/.test(key);

            // Limpa a chave removendo os caracteres codificados
            const cleanKey = key.replace(/\%5B\d*\%5D/g, "").replace("%5B%5D", ""); // Removendo '[]' para campos multiselect

            // Verifica se a chave já existe no objeto
            if (dataFormated.hasOwnProperty(cleanKey)) {
                // Se já existir e for um array, adiciona o novo valor
                if (Array.isArray(dataFormated[cleanKey])) {
                    dataFormated[cleanKey].push(decodeURIComponent(value));
                } else {
                    // Se não for um array, transforma o valor atual em um array
                    dataFormated[cleanKey] = [dataFormated[cleanKey], decodeURIComponent(value)];
                }
            } else {
                // Se for multiselect, sempre inicia o campo como array
                if (isMultiselect) {
                    dataFormated[cleanKey] = [decodeURIComponent(value)];
                } else {
                    // Caso contrário, apenas adiciona o valor normalmente
                    dataFormated[cleanKey] = decodeURIComponent(value);
                }
            }
        }
    });

    // Inicializando o objeto FormData
    let formData = new FormData();

    // Adicionar todos os campos formatados no formData
    for (const [key, value] of Object.entries(dataFormated)) {
        if (Array.isArray(value)) {
            // Para arrays, adiciona cada valor separadamente com o mesmo nome de chave
            value.forEach((val) => {
                formData.append(key + "[]", val);
            });
        } else {
            formData.append(key, value);
        }
    }

    // informações adicionais para o back-end
    if (includeData != null) {
        // Supondo que includeData também possa ser um array de objetos
        if (Array.isArray(includeData) && includeData.every((item) => typeof item === "object" && item !== null)) {
            formData.append("includeData", JSON.stringify(includeData));
        } else {
            formData.append("includeData", includeData);
        }
    }

    // Adicionar arquivos do tipo file no formData
    if (arrayFilesName.length > 0) {
        arrayFilesName.forEach((fileName) => {
            formData.append(fileName, $("#" + fileName).get(0).files[0]);
        });
    }

    // Realizando a requisição AJAX
    let response = $.ajax({
        url: form.attr("attr-save"),
        type: "POST",
        dataType: "json",
        enctype: "multipart/form-data",
        cache: false,
        contentType: false,
        processData: false,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: formData,
        beforeSend: loadingBtnStart(btn),
        success: async function (data) {
            if (data.status == 1) {
                loadingBtnStop(btn, btnIcon, btnTitle);

                if (backToList) {
                    await msgDefault("fa fa-check", "Sucesso!", msgCustomSuccess, form.attr("attr-redirect"));
                } else {
                    await msgDefault("fa fa-check", "Sucesso!", msgCustomSuccess, "reload");
                }
                removeErrorsAll();
            } else if (data.status >= 2) {
                if (data.status == 100) {
                    await msgDefault(
                        "fa fa-check",
                        "Sucesso!",
                        MSG_SUCCESS_USER_REGISTERED_VERIFY,
                        form.attr("attr-redirect"),
                    );
                } else {
                    loadingBtnStop(btn, btnIcon, btnTitle);
                    await msgError(
                        "Desculpe",
                        data.hasOwnProperty("msgerro") && data.msgerro.length > 0 ? data.msgerro : MSG_ERROR_GENERAL,
                    );

                    removeErrorsAll();
                }
            }

            return true;
        },
        error: async function (data) {
            loadingBtnStop(btn, btnIcon, btnTitle);

            if (data.status == 500) {
                await msgError("Erro!", msgCustomError);
            } else {
                var errors = data.responseJSON.errors;
                removeErrorsAll();

                $.each(errors, function (index, value) {
                    let element = form.find("#" + index.replace(".", "\\."));

                    if (element.length == 0) {
                        element = form.find('[name="' + index.replace(".", "\\.") + '"]');
                    }

                    if (element.attr("type") == "radio") {
                        element
                            .addClass("error")
                            .parent()
                            .parent()
                            .append(
                                '<small id="' +
                                    index +
                                    '-error" class="error text-start" for="' +
                                    index +
                                    '">' +
                                    value[value.length - 1] +
                                    "</small>",
                            );
                    } else {
                        element
                            .addClass("error")
                            .parent()
                            .append(
                                '<small id="' +
                                    index +
                                    '-error" class="error text-start" for="' +
                                    index +
                                    '">' +
                                    value[value.length - 1] +
                                    "</small>",
                            );
                    }

                    element.parent().find("span.select2-selection.select2-selection--single").addClass("error");
                });

                await msgError("Erro!", MSG_ERROR_ATEMPION_FIELDS);
            }
            return false;
        },
    });

    return response;
}

export function Filter(dataTable, callMethods, routeName = null) {
    removeErrorsAll();

    if (dataTable == null) {
        jQuery.ajax({
            method: "post",
            url: routeName + "-filter",
            data: $("#haliparForm").serialize(),
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            beforeSend: loadingBtnStart($("#btnFilter")),
            success: function (data) {
                $("#divGrid").html(data.grid);
                callMethods();
                loadingBtnStop($("#btnFilter"), "fa fa-filter", "Aplicar");
            },
            error: function (data) {
                msgError("Erro", MSG_ERROR_GENERAL);
                loadingBtnStop($("#btnFilter"), "fa fa-filter", "Aplicar");
            },
        });
    } else {
        var btn = $(dataTable.BtnFilter);
        var form = $(dataTable.FormFilter);
        if (dataTable.ServerSide != true) {
            jQuery.ajax({
                method: "post",
                url: dataTable.UrlFilter,
                data: form.serialize(),
                beforeSender: loadingBodyStart(),
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                success: function (data) {
                    dataTable.ApplyUpdate(data.grid);

                    // Se existir a função BuldCharts, é executado. Cria Gráficos
                    if (typeof BuildCharts == "function") {
                        BuildCharts(data);
                    }

                    // Se existir a função GetColumnsInvisible, é executado. Identifica as columas invisíveis
                    if (typeof GetColumnsInvisible == "function") {
                        dataTable.Columns.Defs = [{ visible: false, targets: GetColumnsInvisible() }];
                        dataTable.ApplyUpdate(data.grid);
                    }

                    let report = data.consulta;
                    if (report !== undefined) {
                        if (dataTable.ExcelButton.Enable && report.length > 0) {
                            dataTable.MontaBotaoExcel();
                        }
                    }

                    //Se for verdadeiro, significa que estamos trabalhando com dados selecionados na própria grid.
                    if (dataTable.MultiSelect) {
                        dataTable.Table.rows().deselect();
                        HiddenOrShowButtons(
                            dataTable,
                            dataTable.MultiSelect_ButtonClass,
                            dataTable.MultiSelect_ButtonMinQtdSelectedToShow,
                        );
                    }
                    swal.close();
                    loadingBodyStop();
                },
                error: function (data) {
                    removeErrorsAll();
                    var errors = data.responseJSON.errors;
                    $.each(errors, function (index, value) {
                        $element = form.find("#" + index);

                        //bootstrap-select
                        if ($element.hasClass("selectpicker")) {
                            $element.prev().prev().addClass("error");
                        } else {
                            //Select2
                            //Outros Inpust
                            $element.addClass("error");
                        }

                        var attr = form.find("#" + index).attr("date");
                        if (typeof attr !== typeof undefined && attr !== false) {
                            form.find("#" + index)
                                .addClass("error")
                                .parent()
                                .after(
                                    '<label id="' +
                                        index +
                                        '-error" class="error col-6" for="' +
                                        index +
                                        '">' +
                                        value +
                                        "</label>",
                                );
                        } else {
                            form.find("#" + index)
                                .addClass("error")
                                .parent()
                                .append(
                                    '<label id="' +
                                        index +
                                        '-error" class="error col-6 " for="' +
                                        index +
                                        '">' +
                                        value +
                                        "</label>",
                                );
                        }
                    });
                    loadingBodyStop();
                },
            });
        } else {
            dataTable.ApplyUpdate();
        }
    }
}

/**
 * Deletar Dado
 * @param {dataTable} dataTable
 * @param {String} urlDelete
 */
export function Delete(dataTable = null, urlDelete, callMethods, id = null) {
    Swal.fire({
        title: '<i class="fas fa-trash"></i> Exclusão de registros',
        text: "Deseja realmente excluir esse registro?",
        showCancelButton: true,
        focusCancel: true,
        cancelButtonText: '<strong><i class="fas fa-times"></i> NÃO</strong>',
        cancelButtonColor: "#2980b9",
        confirmButtonText: '<strong><i class="fas fa-check"></i> SIM</strong>',
        customClass: {
            popup: "popup_custom",
        },

        preConfirm: () => {
            return $.ajax({
                url: urlDelete,
                dataType: "json",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                method: "delete",
                data: JSON.stringify({ id: id }),
            }).fail(function (data) {
                msgError("Erro", MSG_ERROR_DELETING);
            });
        },
    }).then((data) => {
        if (data.isDismissed != true) {
            if (data.value.status == 1) {
                if (dataTable !== null) {
                    // dataTable.ApplyUpdate(data.value.grid);
                    $("#btnFilter").trigger("click");
                } else {
                    $("#divGrid").html(data.value.grid);
                    callMethods();
                }
                msgDefault("fa fa-check", "Sucesso!", MSG_SUCCESS, "reload");
            } else {
                msgError("Desculpe", data.value.msgerro);
            }
        }
    });
}
/**
 * Salvar ordenação dos itens
 */
export function saveOrderAndDraggable(e, btn, url) {
    e.preventDefault();
    $(".icon-draggable").hide();
    if (btn.text() == "Desbloquear") {
        btn.text("Salvar Ordem");
        $("#btnNew").hide();
        $(".icon-draggable").show();

        $(".div-action").hide();
        $(".divFilters").hide();
        $("#btnInterrogate").show();
        $("#draggablePanelList").sortable("enable");
        $(".ui-sortable-handle").removeClass("hp_cursor_default").addClass("hp_cursor_move");
    } else {
        // Capturar a ordem correta dos inputs antes de serializar
        let novaOrdem = [];
        $("#draggablePanelList li").each(function () {
            novaOrdem.push($(this).find("input[name='banner_id[]']").val());
        });

        // Criar um objeto de dados para enviar via AJAX
        var formData = $("#formDraggable").serialize() + "&banner_ids=" + novaOrdem.join(",");

        $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            data: formData,
            beforeSend: loadingBtnStart(btn),
            success: function (data) {
                if (data.status == 1) {
                    loadingBtnStop($("#btnUnlock"), "", "Desbloquear");
                    $(".icon-draggable").hide();
                    $("#draggablePanelList").sortable("disable");
                    $(".div-action").show();
                    $(".divFilters").show();

                    $("#btnNew").show();
                    $("#btnInterrogate").show();
                    $(".ui-sortable-handle").removeClass("hp_cursor_move").addClass("hp_cursor_default");
                    msgDefault("fa fa-check", "Sucesso!", MSG_SUCCESS, null);
                } else if (data.status >= 2) {
                    if (!($(".cards-exist").length > 0)) {
                        loadingBtnStop($("#btnUnlock"), "", "Desbloquear");
                        $("#btnNew").show();
                        $(".icon-draggable").hide();
                        $("#draggablePanelList").sortable("disable");
                        $(".div-action").show();
                        $(".divFilters").show();
                        $("#btnInterrogate").show();
                        $(".ui-sortable-handle").removeClass("hp_cursor_move").addClass("hp_cursor_default");
                    }
                    msgError("Desculpe", MSG_ERROR_GENERAL);
                }
            },
            error: function (data) {
                loadingBtnStop($("#btnUnlock"), "", "Desbloquear");
                msgError("Ooops!", MSG_ERROR_ATEMPION_FIELDS);
            },
        });
    }
}

/**
 * Arquivar ou Desarquivar
 * @param {dataTable} dataTable
 * @param {String} urlDelete
 */
export function Archive(dataTable = null, urlArchive, title, callMethods) {
    Swal.fire({
        title: '<i class="fas fa-file-archive"></i> ' + title + " registros",
        text: "Deseja realmente " + title.toLowerCase() + " esse registro?",
        showCancelButton: true,
        focusCancel: true,
        cancelButtonText: '<strong><i class="fas fa-times"></i> NÃO</strong>',
        cancelButtonColor: "#2980b9",
        // confirmButtonColor: '#c0392b',
        confirmButtonText: '<strong><i class="fas fa-check"></i> SIM</strong>',
        customClass: {
            popup: "popup_custom",
        },

        preConfirm: () => {
            return $.ajax({
                url: urlArchive,
                dataType: "json",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                method: "put",
            }).fail(function (data) {
                msgError("Erro", MSG_ERROR_GENERAL);
            });
        },
    }).then((data) => {
        if (data.isDismissed != true) {
            if (data.value.status == 1) {
                if (dataTable !== null) {
                    dataTable.ApplyUpdate(data.value.grid);
                } else {
                    $("#divGrid").html(data.value.grid);
                    callMethods();
                }
                msgDefault("fa fa-check", "Sucesso!", MSG_SUCCESS);
            } else {
                msgError("Desculpe", data.value.msgerro);
            }
        }
    });
}

export function history(
    url,
    id,
    arrayMultipleReference = [
        {
            datatable: "#zero_config_history",
            modal: "#tbodyHistoryModal",
            divElementGrid: ".divElementGridFatherHistory",
            nameReferenceGrid: "grid",
        },
    ],
) {
    $.ajax({
        url: "/" + url + "/" + id,
        type: "GET",
        dataType: "json",
        beforeSender: loadingBodyStart(),
    })
        .done(function (data) {
            arrayMultipleReference.forEach((element) => {
                if ($.fn.DataTable.isDataTable(element.datatable + id)) {
                    $(element.datatable + id)
                        .DataTable()
                        .destroy();
                }

                if (data.status == 1) {
                    $(element.modal + id).html(data[element.nameReferenceGrid]);
                }

                var vDataTable = new DataTablesCustom(element.datatable + id, element.divElementGrid + id, false);
                vDataTable.DivFatherTableClass = "#divGrid";
                vDataTable.Info = true;
                vDataTable.Columns.Defs = [{ orderable: false, targets: [] }];
                vDataTable.Init();
            });

            loadingBodyStop();
        })
        .fail(function (e) {
            msgError("Erro!", MSG_ERROR_GENERAL);
        });
}

/**
 * Preenchimento do endereço através do CEP
 * Parâmentros:
 * cep = preencher no formato 00000000
 * api = (0) - API via cep (default) / (1) - API apicep (alternativa)
 */
export function findCep(pCep, pApi = 0) {
    $("[id$='zip_code-warning']").remove();
    $("[id$='zip_code-error']").remove();
    $("[id$='zip_code']").removeClass("warning").removeClass("error");
    loadingBodyStart();
    var url = null;
    var cep = null;
    var numberCepCaracter = pCep.replace(".", "").replace("-", "").replace("_", "").length;
    if (numberCepCaracter == 8) {
        if (pApi == 1) {
            //API apicep (alternativa)
            cep = pCep.replace(".", "");
            url = "https://cdn.apicep.com/file/apicep/" + cep + ".json";
            $.get(url, function (endereco) {
                $("#address").val(endereco.address);
                $("#district").val(endereco.district);
                $("#states_id option:contains(" + endereco.state + ")")
                    .prop("selected", true)
                    .change();
                defineCityByName(endereco.city);
            }).fail(function (erro) {
                $("#zip_code")
                    .addClass("warning")
                    .after("<small id='zip_code-warning' class='warning' for='zip_code'>CEP não encontrado.</small>");
                resetFieldsAddress();
                loadingBodyStop();
            });
        } else {
            //API via cep (default)
            cep = pCep.replace(".", "").replace("-", "");
            url = "https://viacep.com.br/ws/" + cep + "/json/";
            $.get(url, function (endereco) {
                if (endereco.erro) {
                    $("#zip_code")
                        .addClass("warning")
                        .after(
                            "<small id='zip_code-warning' class='warning' for='zip_code'>CEP não encontrado.</small>",
                        );
                    resetFieldsAddress();
                    loadingBodyStop();
                } else {
                    $("#address").val(endereco.logradouro);
                    $("#district").val(endereco.bairro);
                    $("#states_id option:contains(" + endereco.uf + ")")
                        .prop("selected", true)
                        .change();
                    filterCascade($("#states_id").val(), endereco.ibge, $("#cities_id"), "cities", false);
                    loadingBodyStop();
                }

                $("#number").focus();
            });
        }
    } else {
        resetFieldsAddress();
        loadingBodyStop();
    }
}

/**
 *  Função que filtra as cidades pela UF
 * finFor (0) busca pela via vale, (1) busca pela api alternativa
 */
export function filterCities(uf, setCity, findFor = 0) {
    $("#cities_id").html("").append('<option value="">  Carregando...  </option>');

    $.ajax({
        url: "/cities/" + uf,
        type: "GET",
        dataType: "json",
    }).done(function (cities) {
        $("#cities_id").empty();

        $("#cities_id").append(
            '<option value="" selected>' + (Object.keys(cities).length > 0 ? "Selecione..." : "") + "</option>",
        );

        $.each(cities, function (key, value) {
            $("#cities_id").append("<option value=" + value.id + ">" + value.name + "</option>");
        });
        if (setCity != undefined && setCity != null) {
            if (findFor == 0) {
                $("#cities_id").val(setCity);
            } else {
                $("#cities_id option:contains(" + setCity + ")").prop("selected", true);
            }
        }
    });
}

/**
 *  Função que filtra os registro em dropdown cascade
 */
export async function filterCascade(
    id,
    setValue = null,
    element,
    url,
    isMulti,
    nameFirstItem = "Selecione...",
    displayField = "name",
    idInfo = null,
) {
    if (isMulti) {
        element.prev().prev().find("span.filter-option").html(" Carregando... ");
    } else {
        element.html("").append('<option value="">  Carregando...  </option>');
    }
    let resolveUrl = `/${url}`;

    if (id) {
        resolveUrl += `/${id}`;

        if (idInfo) {
            resolveUrl += `/${idInfo}`;
        }
    }

    $.ajax({
        url: resolveUrl,
        type: "GET",
        dataType: "json",
    }).done(async function (data) {
        element.empty();

        if (!isMulti) {
            element.append('<option value="" selected>' + nameFirstItem + "</option>");
        }

        $.each(data, function (key, value) {
            let displayText = value[displayField] || "Sem nome";
            element.append("<option value=" + value.id + ">" + displayText + "</option>");
        });

        if (setValue != undefined && setValue != null) {
            // Se for true, significa que iremos pegar a última inserção, traremos essa informação do back-end
            if (setValue == true) {
                // Encontrar o item com maior ID
                let maxItem = data.reduce((max, item) => (item.id > max.id ? item : max), data[0]);
                if (maxItem != null) {
                    // Verificar se foi criado há pelo menos 2 minutos
                    let createdAt = new Date(maxItem.created_at);
                    let now = new Date();
                    let diffMinutes = (now - createdAt) / (1000 * 60); // Converter para minutos

                    if (diffMinutes <= 2) {
                        element.val(maxItem.id); // Seleciona o item com maior ID
                    }
                } else {
                    element.val(""); // Seleciona a opção vazia
                }
            } else {
                if (element.find('option[value="' + setValue + '"]').length > 0) {
                    element.val(setValue);
                } else {
                    element.val("");
                }
            }
        }

        //Atualiza o Multiselect após a recarga
        if (isMulti) {
            element.selectpicker("refresh");
        }

        element.trigger("change");

        // element.find("option").first().html(nameFirstItem);
    });
}

/**
 *  Função que filtra os registro em dropdown cascade
 */
export async function filterStoresCard(url, statesId, citiesId, storesId) {
    if (statesId == "") {
        statesId = "false";
    }

    if (citiesId == "") {
        citiesId = "false";
    }

    if (storesId == "") {
        storesId = "false";
    }

    $.ajax({
        url: "/" + url + "/" + statesId + "/" + citiesId + "/" + storesId,
        type: "GET",
        dataType: "json",
    }).done(function (data) {
        $("#divInfoStores").html(data.grid);
        effectsTransitions();
    });
}
