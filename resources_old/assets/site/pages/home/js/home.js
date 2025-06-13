import { filterCascade, Save } from "#common/js/utils/actions.js";
import {
    initInputMask,
    initPopovers,
    initInputMaskPhoneCell,
    initInputRulesFields,
    removeErrorsAll,
    removeMasksFields,
} from "#common/js/utils/functions";
import { EnumTypeMask } from "#common/js/utils/enums/enum_type_mask";
import { loadingBtnStart, loadingBtnStop } from "#common/js/utils/loading";
import { msgError } from "#common/js/utils/messages";
import { MSG_ERROR_GENERAL, MSG_ERROR_ATEMPION_FIELDS, MSG_ERROR_ADD_VOUCHER } from "#common/js/utils/messages";
import { effectsTransitions } from "#common/js/utils/effects.js";

$(window).on("load", function () {
    initPopovers();
    initInputMask();
    initInputRulesFields();
    execSelect2();
    const myModalEl = document.getElementById("exampleModal");
    myModalEl.addEventListener("hidden.bs.modal", (e) => {
        $.ajax({
            method: "GET",
            url: "/site/digite-cnpj",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: async function (data) {
                if (data.status == 1) {
                    const scrollTopBefore = $(window).scrollTop(); // salva scroll da página
                    $("#divContentModal").html(data.content); // atualiza o conteúdo
                    $(window).scrollTop(scrollTopBefore); // restaura scroll original
                    initInputMask();
                    execSelect2();
                    effectsTransitions();
                    eventClickmodal();
                } else if (data.status >= 2) {
                    if (data.status != 100) {
                        await msgError(
                            "Desculpe",
                            data.hasOwnProperty("msgerro") && data.msgerro.length > 0
                                ? data.msgerro
                                : MSG_ERROR_GENERAL,
                        );
                    }
                }

                return true;
            },
            error: async function (data) {
                await msgError("Erro!", MSG_ERROR_GENERAL);

                return false;
            },
        });
    });
    eventClickmodal();
});

function execSelect2() {
    $(".select2DropdownParent").select2({
        theme: "bootstrap-5",
        dropdownParent: $("#exampleModal .modal-body"),
    });
}

function eventClickPayment() {
    $("#btnPayment").on("click", async function (e) {
        let itemPending = $(".itemPending");
        let originStoresId = $("#origin_stores_id").val();
        if (itemPending.length == 0) {
            msgError("Atenção", MSG_ERROR_ADD_VOUCHER);
        } else {
            let buysId = $("#vouchers_buys_id_1").val();

            $.ajax({
                method: "GET",
                url: "/site/payment/" + originStoresId + "/" + buysId,
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                success: async function (data) {
                    if (data.status == 1) {
                        const scrollTopBefore = $(window).scrollTop(); // salva scroll da página
                        $("#divContentModal").html(data.content); // atualiza o conteúdo
                        $(window).scrollTop(scrollTopBefore); // restaura scroll original
                        initPopovers();
                        initInputMask();
                        initInputMaskPhoneCell();
                        execSelect2();
                        eventClickmodal();
                        eventClickConfirmPayment();
                        effectsTransitions();
                    } else if (data.status >= 2) {
                        if (data.status != 100) {
                            await msgError(
                                "Desculpe",
                                data.hasOwnProperty("msgerro") && data.msgerro.length > 0
                                    ? data.msgerro
                                    : MSG_ERROR_GENERAL,
                            );
                        }
                    }

                    return true;
                },
                error: async function (data) {
                    await msgError("Erro!", MSG_ERROR_GENERAL);

                    return false;
                },
            });
        }
    });
}

function eventClickConfirmPayment() {
    $("#btnConfirmBuys").on("click", async function (e) {
        e.preventDefault();
        let form = $("#formBuys");
        let btn = $(this);
        let removeMaskList = [EnumTypeMask.Common + "|phone_cell"];
        let data = removeMasksFields(removeMaskList, form.serialize() + "&");

        $.ajax({
            method: "post",
            url: form.attr("attr-save"),
            data: data,
            // beforeSender: loadingBodyStart(),
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            beforeSend: loadingBtnStart(btn),
            success: async function (data) {
                if (data.status == 1) {
                    const scrollTopBefore = $(window).scrollTop(); // salva scroll da página
                    $("#divContentModal").html(data.content); // atualiza o conteúdo
                    $(window).scrollTop(scrollTopBefore); // restaura scroll original
                    effectsTransitions();
                } else {
                    loadingBtnStop(btn, "", "Finalizar");
                    await msgError(
                        "Erro",
                        data.hasOwnProperty("msgerro") && data.msgerro.length > 0 ? data.msgerro : MSG_ERROR_GENERAL,
                    );
                }
            },
            error: async function (data) {
                loadingBtnStop(btn, "", "Finalizar");

                if (data.status == 500) {
                    await msgError("Erro!", MSG_ERROR_GENERAL);
                } else {
                    var errors = data.responseJSON.errors;
                    removeErrorsAll();

                    $.each(errors, function (index, value) {
                        form.find("#" + index.replace(".", "\\."))
                            .addClass("error")
                            .parent()
                            .append(
                                '<small id="' +
                                    index +
                                    '-error" class="error" for="' +
                                    index +
                                    '">' +
                                    value[value.length - 1] +
                                    "</small>",
                            );

                        form.find("#" + index.replace(".", "\\."))
                            .parent()
                            .find("span.select2-selection.select2-selection--single")
                            .addClass("error");
                    });

                    await msgError("Erro!", MSG_ERROR_ATEMPION_FIELDS);
                }

                return false;
            },
        });
    });
}

function eventClickmodal() {
    $("#formVerifyExistUser").on("keypress", function (e) {
        if (e.key == "Enter") {
            e.preventDefault();
            $("#btnGetUser").trigger("click");
        }
    });

    $("#btnGetUser").on("click", async function (e) {
        fetchModalContent();
    });

    $("#btnReturnBuy").on("click", async function (e) {
        $("#shouldReturnBuy").val(1);
        $("#shouldNewBuy").val(0);
        fetchModalContent();
        eventDeleteVoucher();
        initPopovers();
        effectsTransitions();
        removeErrorsAll();
    });

    $("#btnNewBuy").on("click", async function (e) {
        $("#shouldReturnBuy").val(0);
        $("#shouldNewBuy").val(1);
        fetchModalContent();
    });
}

function fetchModalContent() {
    let form = $("#formVerifyExistUser");
    let btn = $(this);
    let removeMaskList = [EnumTypeMask.Common + "|cnpj"];
    let data = removeMasksFields(removeMaskList, form.serialize() + "&");
    $.ajax({
        method: "post",
        url: form.attr("attr-save"),
        data: data,
        // beforeSender: loadingBodyStart(),
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        beforeSend: loadingBtnStart(btn),
        success: async function (data) {
            if (data.status == 1) {
                loadingBtnStop(btn, "", "Próximo");
                // $("#divContentModal").removeClass("d-none");
                // $("#verifyEmail").addClass("d-none");
                const scrollTopBefore = $(window).scrollTop(); // salva scroll da página
                $("#divContentModal").html(data.content); // atualiza o conteúdo
                $(window).scrollTop(scrollTopBefore); // restaura scroll original
                execSelect2();
                initInputRulesFields();
                initPopovers();
                eventClickmodal();
                eventClickAddVoucher();
                eventDeleteVoucher();
                eventClickPayment();
                effectsTransitions();
                removeErrorsAll();

                /**
                 *  Carrega combo de stores de acordo com a mudança do combo de brands
                 */

                $("#brands_id").on("change", async function () {
                    await filterCascade(
                        $("#brands_id").val(),
                        $("#origin_stores_id").val(),
                        $("#stores_id"),
                        "brands-for-stores",
                        false,
                        "Selecione...",
                        "name",
                        $("#origin_stores_id").val(),
                    );
                });
            } else if (data.status >= 2) {
                if (data.status != 100) {
                    loadingBtnStop(btn, "", "Próximo");
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
            loadingBtnStop(btn, "", "Próximo");

            if (data.status == 500) {
                await msgError("Erro!", MSG_ERROR_GENERAL);
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
}
function eventDeleteVoucher() {
    $(".btnDeleteVoucher").on("click", async function (e) {
        Swal.fire({
            title: '<i class="fas fa-trash"></i> Exclusão',
            text: "Deseja realmente excluir esse participante?",
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
                    url: $(this).attr("attr-delete"),
                    dataType: "json",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                    method: "delete",
                }).fail(function (data) {
                    msgError("Erro", MSG_ERROR_DELETING);
                });
            },
        }).then((data) => {
            if (data.value.status == 1) {
                $("#tablePaymentPendent").html(data.value.content);
                $("#paymentPending").html(data.value.content_pending);
                eventDeleteVoucher();
                initPopovers();
                effectsTransitions();
                removeErrorsAll();
            }
        });
    });
}

function eventClickAddVoucher() {
    $("#btnAddVoucher").on("click", async function (e) {
        e.preventDefault();
        let form = $("#formAddVouchers");
        let btn = $(this);
        let removeMaskList = [EnumTypeMask.Common + "|"];
        let data = removeMasksFields(removeMaskList, form.serialize() + "&");

        $.ajax({
            method: "post",
            url: form.attr("attr-save"),
            data: data,
            // beforeSender: loadingBodyStart(),
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            beforeSend: loadingBtnStart(btn),
            success: async function (data) {
                if (data.status == 1) {
                    loadingBtnStop(btn, "", "Adicionar Convidado");

                    form[0].reset();
                    $("#positions_id").trigger("change");
                    $("#tshirt").trigger("change");
                    $("#brands_id").trigger("change");
                    $("#stores_id").trigger("change");
                    $("#stores_id option").not(":first").remove();

                    $("#tablePaymentPendent").html(data.content);
                    $("#paymentPending").html(data.content_pending);
                    eventDeleteVoucher();
                    initPopovers();
                    effectsTransitions();
                    removeErrorsAll();
                } else if (data.status >= 2) {
                    if (data.status != 100) {
                        loadingBtnStop(btn, "", "Adicionar Convidado");
                        await msgError(
                            "Desculpe",
                            data.hasOwnProperty("msgerro") && data.msgerro.length > 0
                                ? data.msgerro
                                : MSG_ERROR_GENERAL,
                        );

                        removeErrorsAll();
                    }
                }

                return true;
            },
            error: async function (data) {
                loadingBtnStop(btn, "", "Adicionar Convidado");

                if (data.status == 500) {
                    await msgError("Erro!", MSG_ERROR_GENERAL);
                } else {
                    var errors = data.responseJSON.errors;
                    removeErrorsAll();

                    $.each(errors, function (index, value) {
                        form.find("#" + index.replace(".", "\\."))
                            .addClass("error")
                            .parent()
                            .append(
                                '<small id="' +
                                    index +
                                    '-error" class="error" for="' +
                                    index +
                                    '">' +
                                    value[value.length - 1] +
                                    "</small>",
                            );

                        form.find("#" + index.replace(".", "\\."))
                            .parent()
                            .find("span.select2-selection.select2-selection--single")
                            .addClass("error");
                    });

                    await msgError("Erro!", MSG_ERROR_ATEMPION_FIELDS);
                }
                return false;
            },
        });
    });
}
