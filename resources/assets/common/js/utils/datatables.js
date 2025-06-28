import "../../plugins/js/common_datatables";
import { Delete, Filter } from "@assetsCommon/js/utils/actions.js";
import { CallComponents } from "./calls";

/**
 *  Classe Columns para configurar os colunas do Datatables
 *  Exemplo coluna tipo Data: { type: 'date-br', targets: $dateBr }
 */
var ColumnsFormatNumber = [];
var ColumnsFormatSelectOptions = [];
var ColumnsFormatStatus = [];
class Columns {
    constructor() {
        this.Defs = [];
        this.OrderInitials = [];
        this.ToRender = null;
    }
}

/**
 *  Classe ExcelButton para configurar o botão do excel do Datatables
 */
class ExcelButton {
    /**
     * footerRows = é um array com os indices das linhas do FOOTER que devem ser exportadas para o excel
     */
    constructor(enable, title, columns, footerRows) {
        this.Enable = enable == null ? "false" : enable;
        this.Title = title;
        this.Columns = columns == null ? ":visible" : columns;
        this.FooterRows = footerRows == null ? [0] : footerRows;
    }
}
class FixedColumns {
    constructor(columns = 0) {
        this.left = columns;
    }
}

/**
 *  Classe DataTablesCustom para reunir todas as configurações do Datatables
 */
export class DataTablesCustom {
    constructor(tableName, divFatherTableClass, isServerSide = false, urlServerSide = false) {
        //RECEBE O DATATABLE
        this.Table = null;

        //NOME DA TABELA (geralmente #zero_config)
        this.TableName = tableName;

        //ADICIONAR PAGINAÇÃO
        this.Paging = true;

        //ADICIONAR CAIXA DE PESQUISA
        this.Searching = true;

        //ADICIONAR INFORMAÇÕES DE REGISTROS (QUANTIDADES POR PÁGINA ETC ETC)
        this.Info = true;

        //DIV PAI DA #ZERO_CONFIG, USADO PARA RECRIAR DATATABLE
        this.DivFatherTableClass = divFatherTableClass;

        //DEFINIR ALGUNS COMPORTAMENTOS PADRÕES, COMO POR EXEMPLO: TORNAR ALGUMAS COLUNAS NÃO ORDENADA
        this.Columns = new Columns();

        //ADICIONAR INFORMAÇÕES GERAIS NO FOOTER (RODAPÉ) DO DATATABLE... COMO POR EXEMPLO, A SOMA DE ALGUMA COLUNA
        this.FooterCallback = false;

        //A OPÇÃO createdRow NO DATATABLES É UMA FUNÇÃO DE CALLBACK QUE É CHAMADA TODA VEZ QUE UMA NOVA LINHA (<tr>) É
        // CRIADA E INSERIDA NA TABELA. ELA PERMITE QUE VOCÊ PERSONALIZE OU MODIFIQUE O CONTEÚDO DA LINHA LOGO APÓS SUA
        // CRIAÇÃO, ANTES QUE ELA SEJA RENDERIZADA NO DOM.
        this.CreatedRow = false;

        // ROWCALLBACK NO DATATABLES É UMA FUNÇÃO DE CALLBACK QUE É EXECUTADA CADA VEZ QUE UMA LINHA É RE-RENDERIZADA NA TABELA,
        // PERMITINDO MODIFICAR SEU CONTEÚDO OU ESTILO COM BASE NOS DADOS EXIBIDOS.
        this.RowCallback = false;

        //CABEÇALHO OU RODAPÉ NA PARTE SUPERIOR OU INFERIRO DA JANELA DE ROLAGEM
        this.FixedHeader;

        this.ExcelFooterRows = [0];

        //ADICIONAR EVENTO DE CLICK NOS BOTÕES NA GRID JUNTO COM A FUNÇÃO ESPECIFICADA
        this.BtnEventClickArray = [];

        //CRIAR BOTÃO DE EXPORTAÇÃO DA GRID(EXCEL)
        this.ExcelButton = new ExcelButton(false, null);

        //CRIAR BARRA DE ROLAGEM NA DIREÇÃO HORIZONTAL
        this.ScrollX = false;

        // //SE FOR TRABALHAR COM REGISTROS EM CARD, É NECESSÁRIO ATIVAR A RESPONSIVIDADE
        this.Responsive = false;

        //EM ALGUMAS SITUAÇÕES É NECESSARIO FAZER A CHAMADA DE BUILD DOS COMPONENTES DO DATATABLE, CONTROLAR EM QUE MOMENTO ISSO IRÁ OCORRER.
        this.buildComponents = true;

        //USADO PARA SALVAR INFORMAÇÕES DE OUTRAS PÁGINAS NO DATATABLE.
        //EXEMPLO: FOI SELECIONADO 3 REGISTROS NA PRIMEIRA PÁGINA , 5 NA SEGUNDA PÁGINA E 2 NA TERCEIRA PÁGINA
        //SÓ É POSSÍVEL RECUPERAR ESSAS INFORMAÇÕES, SEM RESETAR, SE STATESAVE ESTIVER COMO VERDADEIRO.
        this.StateSave = false;

        //FARÁ COM QUE DATATABLES RECOLHA A JANELA DE VISUALIZAÇÃO DA TABELA QUANDO O CONJUNTO DE RESULTADOS
        //SE AJUSTAR À ALTURA Y FORNECIDA.
        this.ScrollCollapse = false;

        //AO USAR O RECURSO DE ROLAGEM DO EIXO X DO DATATABLES ( SCROLLX), VOCÊ PODE QUERER
        //FIXAR AS COLUNAS MAIS À ESQUERDA OU À DIREITA NO LUGAR.
        this.FixedColumns = new FixedColumns();

        //FORNECE AO USUÁRIO FINAL A CAPACIDADE DE CLICAR E ARRASTAR (OU TOCAR E ARRASTAR EM DISPOSITIVOS MÓVEIS) UMA LINHA NA TABELA E ALTERAR SUA POSIÇÃO.
        this.RowReorder = false;

        //AUXILIAR DO ROWREORDER
        this.RowReorder_Selector = { selector: "tr" };

        //BOTÃO DE FILTRAR (GERALMENTE  $('#btnFiltrar'))
        this.BtnFilter = null;

        //IDENTIFICADOR DO BOTÃO STATUS NA GRID EM CADA UMA DAS LINHAS
        this.btnStatus = ".btn-status";

        //IDENTIFICADORES PARA OS SELETORES DO TIPO UNITÁRIO (NA LINHA) OU TODOS(NO HEADER NA GRID).
        this.CheckBoxSelect = {
            all: ".select-all",
            unitary: ".select-checkbox",
        };

        //ADICIONAR MULTISELECT, OU SEJA, CHECKBOX NA GRID
        this.MultiSelect = false;

        //AUXILIAR DO MULTISELECT
        this.MultiSelect_Selector = {
            style: "multi",
            selector: "td:first-child input",
        };

        //PASSAR TODOS OS BOTÕES QUE TERÃO ALGUMA AÇÃO EMPREGADA MASSIVAMENTE.
        //TODOS REGISTROS OS SELECIONADOS SERÃO AFETADOS.
        //EXEMPLO:  ['#btnMassiveAlterStatus', '#btnMassiveDownload', '#btnMassiveSendPdfEmail'];
        this.MultiSelect_ButtonClass = [];

        //QUANDO SE TRATA DE MULTISELECT NA GRID, É IMPORTANTE ESPECIFICAR QUAL A
        //QUANTIDADE MÍNIMA DE REGISTROS SELECIONADOS QUE TORNA UM BOTÃO HABILITADO E VISÍVEL
        this.MultiSelect_ButtonMinQtdSelectedToShow = 2;

        //OBJETO FORM DO FILTRO. GERALMENTE  $('.form-filtros')
        this.FormFilter = null;

        //ROTA PARA APLICAR O FILTRO, EXEMPLO: /gerenciar-boletos-filter
        this.UrlFilter = urlServerSide ? urlServerSide : null;

        //IDENTIFICADOR DO BOTÃO DELETE, QUANDO EXISTIR EM ALGUMA CÉLULA NA GRID
        this.LnkDelete = ".lnk-delete";

        //SE HOUVER UMA CELULA NA GRID COM INPUTS DO TIPO NUMERO FINANCEIRO
        //É NECESSÁRIO PASSAR O INDEX QUANDO ELA FOR EXPORTADA COMO EXCEL
        //CASO NÃO EXPECIFIQUE O INDEX, NO EXCEL POSSIVELMENTE VIRÁ COM VALORES ERRADOS
        this.ColumnsFormatNumber = [];

        //SE HOUVER UMA CELULA NA GRID COM INPUTS DO TIPO SELECT
        //É NECESSÁRIO PASSAR O INDEX QUANDO ELA FOR EXPORTADA COMO EXCEL
        //CASO NÃO EXPECIFIQUE O INDEX, NO EXCEL VIRÁ COM HTML PURO E NÃO O TEXTO DO SELECT
        this.ColumnsFormatSelectOptions = [];

        //SE HOUVER UMA CELULA NA GRID COM HTML PURO DE STATUS
        //É NECESSÁRIO PASSAR O INDEX QUANDO ELA FOR EXPORTADA COMO EXCEL
        //CASO NÃO EXPECIFIQUE O INDEX, NO EXCEL VIRÁ COM HTML PURO E NÃO O TEXTO DO SELECT
        this.ColumnsFormatStatus = [];

        //TRADUTOR PARA PORTUGUÊS
        this.Language = {
            sEmptyTable: "Nenhum registro encontrado",
            sInfo: "Mostrando de _START_ até _END_ de _TOTAL_ registros",
            sInfoEmpty: "Mostrando 0 até 0 de 0 registros",
            sInfoFiltered: "(Filtrados de _MAX_ registros)",
            sInfoPostFix: "",
            sInfoThousands: ".",
            sLengthMenu: "_MENU_ resultados por página",
            sLoadingRecords: "Carregando...",
            sProcessing: "",
            processing: "",
            sZeroRecords: "Nenhum registro encontrado",
            sSearch: "Pesquisar",
            oPaginate: {
                sNext: "Próximo",
                sPrevious: "Anterior",
                sFirst: "Primeiro",
                sLast: "Último",
            },
            oAria: {
                sSortAscending: ": Ordenar colunas de forma ascendente",
                sSortDescending: ": Ordenar colunas de forma descendente",
            },
            decimal: "-",
            thousands: ".",
            select: {
                rows: {
                    _: " Você selecionou %d linhas",
                    0: " Clique em uma linha para selecioná-la",
                    1: " Apenas 1 linha selecionada",
                },
            },
            rowId: "id",
        };

        /**
         * PROPRIEDADES REFERENTE AO PROCESSAMENTO SOB DEMANDA
         * TORNAR O PROCESSAMENTO DO LADO DO SERVIDOR (TRABALHAR COM MUITOS DADOS)
         */
        this.ServerSide = isServerSide;
        this.Ajax = isServerSide ? this.FuncAjax() : false;
    }

    FuncAjax() {
        let InjectThis = this;

        return function (data, callback, settings) {
            // Coleta os dados do formulário
            var formData = [];
            if (InjectThis.FormFilter != null) {
                formData = InjectThis.FormFilter.serializeArray();
            }

            // Aplica a função de identificação de multiselects
            var filterFormData = identifyMultiSelect(formData);

            console.log(InjectThis.FormFilter);
            console.log(filterFormData);
            // Adiciona os dados do formulário ao objeto data do DataTables
            for (var key in filterFormData) {
                data[key] = filterFormData[key];
            }

            // Faz a requisição AJAX
            $.ajax({
                url: InjectThis.UrlFilter,
                method: "POST",
                data: data,
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                success: function (response) {
                    // Chama o callback do DataTables com a resposta do servidor
                    callback(response);

                    if (InjectThis.ExcelButton.Enable) {
                        InjectThis.MontaBotaoExcel();
                    }
                },
                error: function (xhr, error, thrown) {
                    // Trata erros da requisição AJAX
                    console.error("Erro na requisição AJAX:", error, thrown);
                },
            });
        };
    }

    /**
     * Instancia o datatable e aplica as configurações definidas no objeto instanciado
     */
    ApplySettings() {
        this.Table = $(this.TableName).DataTable({
            paging: this.Paging,
            searching: this.Searching,
            info: this.Info,
            rowReorder: this.RowReorder ? this.RowReorder_Selector : this.RowReorder,
            scrollX: this.ScrollX,
            scrollCollapse: this.ScrollCollapse,
            fixedColumns: this.FixedColumns.left,
            stateSave: this.StateSave,
            responsive: this.Responsive,
            columnDefs: this.Columns.Defs,
            order: this.Columns.OrderInitials,
            language: this.Language,
            footerCallback: this.FooterCallback,
            rowCallback: this.RowCallback,
            createdRow: this.CreatedRow,
            select: this.MultiSelect ? this.MultiSelect_Selector : false,

            /**
             * PROPRIEDADES REFERENTE AO PROCESSAMENTO SOB DEMANDA
             */
            processing: this.ServerSide,
            serverSide: this.ServerSide,
            columns: this.Columns.ToRender,
            ajax: this.Ajax,
        });

        /**
         * Filtra pela coluna de Data no formato BR (Type: date-br)
         */
        jQuery.extend(jQuery.fn.dataTableExt.oSort, {
            "date-br-pre": function (a) {
                if (a == null || a == "") {
                    return 0;
                }
                var brDatea = a.split("/");
                return (brDatea[2] + brDatea[1] + brDatea[0]) * 1;
            },

            "date-br-asc": function (a, b) {
                return a < b ? -1 : a > b ? 1 : 0;
            },

            "date-br-desc": function (a, b) {
                return a < b ? 1 : a > b ? -1 : 0;
            },
        });

        ColumnsFormatNumber = this.ColumnsFormatNumber;
        ColumnsFormatSelectOptions = this.ColumnsFormatSelectOptions;
        ColumnsFormatStatus = this.ColumnsFormatStatus;

        BuildEventClickDelete(this);
        BuildEventSelectAll(this);
        BuildEventSelectUnitary(this);
        BuildEventModifyStatusGrid(this);
        BuildEventClickButtons(this);

        // if (this.buildComponents == true) {
        BuildComponents(this);
        // }
    }

    /**
     *
     * @param grid = Se não for NULL, atualiza a div com a View da Grid renderizada no retorno JSON
     *
     */
    ApplyUpdate(data = null) {
        if (data != null) {
            this.Table.destroy();
            $(this.DivFatherTableClass).html(data);
            this.ApplySettings();
        } else {
            this.Table.ajax.reload();
        }
    }

    /**
     * Monta o botão do excel dentro da barra de botões da tela
     */
    MontaBotaoExcel() {
        $(".dt-buttons").remove();

        let buttonProperties = {
            text: '<i class="fa-regular fa-file-excel"></i> Excel',
            extend: "excelHtml5",
            className: "btn-sm btn-primary me-3 btn-excel",
            title: this.ExcelButton.Title,
            footer: false,
            exportOptions: {
                columns: this.ExcelButton.Columns,
                edit: this.ColumnsFormatNumber,
                format: {
                    body: function (data, row, column, node) {
                        //HÁ COLUNAS DO TIPO SELECT OPTIONS PARA SEREM EDITADAS E A COLUNA EDITADA É A MESMA ANALISADA
                        if (ColumnsFormatSelectOptions.length > 0 && ColumnsFormatSelectOptions.includes(column)) {
                            //BUSCANDO O TEXTO DO ITEM SELECIONADO E RETORNANDO-O PARA O EXCEL
                            return $(node).find("option:selected").text().trim();
                        }

                        //HÁ COLUNAS COM HTML PURO ("STATUS") PARA SEREM EDITADAS E A COLUNA EDITADA É A MESMA ANALISADA
                        if (ColumnsFormatStatus.length > 0 && ColumnsFormatStatus.includes(column)) {
                            //BUSCANDO O TEXTO DO ITEM SELECIONADO E RETORNANDO-O PARA O EXCEL
                            return $(node).find("span").text().trim();
                        }

                        //HÁ COLUNAS NÚMÉRICAS PARA SEREM EDITADAS E A COLUNA NUMÉRICA EDITADA É A MESMA ANALIZADA
                        if (ColumnsFormatNumber.length > 0 && ColumnsFormatNumber.includes(column)) {
                            if (data.toString().includes("%")) {
                                //NUMERO DO TIPO PORCENTAGEM
                                return parseFloat(data.toString().replaceAll("%", "")) / 100;
                            } else {
                                //NUMERO DIFERENTE DE PORCENTAGEM
                                return parseFloat(data.toString().replaceAll(".", "").replaceAll(",", "."));
                            }
                        }

                        let valueToExcel = formatValueToExcel(data);
                        return valueToExcel;
                    },
                },
            },
            customize: (xlsx, config, dataTable) => {
                let sheet = xlsx.xl.worksheets["sheet1.xml"];
                let footerIndex = $("sheetData row", sheet).length;
                let $footerRows = $("tr", dataTable.footer());

                // If there are more than one footer rows
                if ($footerRows.length > 1) {
                    // First header row is already present, so we start from the second row (i = 1)
                    for (let i = 0; i < $footerRows.length; i++) {
                        if (this.ExcelFooterRows.includes(i)) {
                            // Get the current footer row
                            let $footerRow = $footerRows[i];

                            // Get footer row columns

                            let $footerRowCols = $("th", $footerRow);

                            // Increment the last row index
                            footerIndex++;
                        }
                    }
                }
            },
        };

        if (this.ServerSide == true) {
            buttonProperties.action = newexportaction;
        }

        new $.fn.dataTable.Buttons(this.Table, {
            buttons: [buttonProperties],
        })
            .container()
            .appendTo($("#bar_buttons"));
        $(".dt-buttons").addClass("d-inline");
        $(".buttons-excel").removeClass("btn-secondary");
    }

    /**
     * Inicializa o DataTable na primeira carga
     */
    Init() {
        this.ApplySettings();
        BuildEventClickFilter(this);
    }
}

/**
 * Recarrega no DOM o evento de click dos links DELETE do datatable
 */
function BuildEventClickDelete(dataTable) {
    $(dataTable.TableName).on("click", dataTable.LnkDelete, function () {
        Delete(dataTable, $(this).attr("data-url"));
    });
}

/**
 * Recarrega no DOM o evento de click do Botão Filtrar
 */
function BuildEventClickFilter(dataTable) {
    $(dataTable.BtnFilter).on("click", function () {
        Filter(dataTable);
    });
}

/**
 * Recarrega no DOM o evento de click do Botão Selecionar Todos (checkbox)
 */
function BuildEventSelectAll(dataTable) {
    $(dataTable.CheckBoxSelect.all).on("change", function () {
        SelectBox(dataTable, $(this), "all", dataTable.MultiSelect_ButtonClass);
    });
}

/**
 * Recarrega no DOM o evento de click do Botão Selecionar Linha (checkbox)
 */
function BuildEventSelectUnitary(dataTable) {
    $(dataTable.TableName).on("change", dataTable.CheckBoxSelect.unitary, function () {
        SelectBox(dataTable, $(this), "unitary", dataTable.MultiSelect_ButtonClass);
    });
}

/**
 * Recarrega no DOM o evento de click de botões, com regras bem específicas.
 */
function BuildEventClickButtons(dataTable) {
    dataTable.BtnEventClickArray.forEach((element) => {
        $(dataTable.TableName).on("click", element.objNameClass, function () {
            element.function($(this).data("id"));
        });
    });
}

/**
 * Recarrega no DOM o evento de atualizar o status na tabela
 */
function BuildEventModifyStatusGrid(dataTable) {
    $(dataTable.TableName).on("change", dataTable.btnStatus, function () {
        // let statusValue = 1;
        let statusValue = $(this).val();

        // Verifica se existe a classe off no toggle components.
        // Se não existir, significa que o botão de status está on.
        // if (!$(this).parent().hasClass('off')) {
        //     statusValue = 0;
        // };

        // Busca em todas as tags, do filho $(this), onde ela seja tr e selecionar a linha.
        let row = $(this).closest("tr");

        // Extrair informações armazenadas no ID.
        // Formato atual: #ID-STATUS-INFO
        // É necessário fazer dessa forma pois o datatable não renderiza todas as linhas da paginação.
        // Dessa forma, é a unica forma encontrada de conseguir extrair informações de determinado registros em outras páginas.
        let result = extractInfoID([row.attr("id")]);

        // Atribuir novo valor para o #id-status-info
        $(row).attr(
            "id",
            String(result[0].id) +
                String("-") +
                String(statusValue) +
                String("*") +
                String(result[0].info.split("*")[1]),
        );

        //Atualizar do cache a linha selecionada para o novo valor de ID ser modificado
        dataTable.Table.row(row).invalidate().draw("page");

        //Modificar o status
        ModifyStatusGrid(dataTable, statusValue, $(this));

        //Se houver botões de ação massivos, aplicará a lógica de mostra-los ou não.
        if (dataTable.MultiSelect_ButtonClass != []) {
            HiddenOrShowButtons(
                dataTable,
                dataTable.MultiSelect_ButtonClass,
                dataTable.MultiSelect_ButtonMinQtdSelectedToShow,
            );
        }

        //Se houver botões específicos(com a função no js da página) na grid, aplicará a lógica de mostra-los ou não.
        if (typeof HiddenButtonsInGrid === "function") {
            HiddenButtonsInGrid(dataTable, $(this), statusValue);
            return;
        }
    });
}

/**
 * Recarrega no DOM todos os componentes gerais dentro da datatable, como select2, datatime, toggle, etc etc...
 */
function BuildComponents(dataTable) {
    CallComponents(dataTable);
    dataTable.Table.on("draw.dt", function () {
        CallComponents(dataTable);
    });
}

function identifyMultiSelect(formData) {
    var filterFormData = {};
    for (var i in formData) {
        var field = formData[i];
        if (!filterFormData[field.name]) {
            filterFormData[field.name] = [];
        }
        filterFormData[field.name].push(field.value);
    }

    // Converte os arrays de múltiplos valores corretamente
    for (var key in filterFormData) {
        if (filterFormData[key].length === 1) {
            filterFormData[key] = filterFormData[key][0];
        }
    }
    return filterFormData;
}

/**
 * Função usada para trazer todos os dados de uma tabela que está usando processamento sob demanda ao querer exportar a planilha.
 */

function newexportaction(e, dt, button, config) {
    var self = this;
    var oldStart = dt.settings()[0]._iDisplayStart;
    dt.one("preXhr", function (e, s, data) {
        // Just this once, load all data from the server...
        data.start = 0;
        data.length = 2147483647;
        dt.one("preDraw", function (e, settings) {
            // Call the original action function
            if (button[0].className.indexOf("buttons-copy") >= 0) {
                $.fn.dataTable.ext.buttons.copyHtml5.action.call(self, e, dt, button, config);
            } else if (button[0].className.indexOf("buttons-excel") >= 0) {
                $.fn.dataTable.ext.buttons.excelHtml5.available(dt, config)
                    ? $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config)
                    : $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
            } else if (button[0].className.indexOf("buttons-csv") >= 0) {
                $.fn.dataTable.ext.buttons.csvHtml5.available(dt, config)
                    ? $.fn.dataTable.ext.buttons.csvHtml5.action.call(self, e, dt, button, config)
                    : $.fn.dataTable.ext.buttons.csvFlash.action.call(self, e, dt, button, config);
            } else if (button[0].className.indexOf("buttons-pdf") >= 0) {
                $.fn.dataTable.ext.buttons.pdfHtml5.available(dt, config)
                    ? $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, button, config)
                    : $.fn.dataTable.ext.buttons.pdfFlash.action.call(self, e, dt, button, config);
            } else if (button[0].className.indexOf("buttons-print") >= 0) {
                $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
            }
            dt.one("preXhr", function (e, s, data) {
                // DataTables thinks the first item displayed is index 0, but we're not drawing that.
                // Set the property to what it was before exporting.
                settings._iDisplayStart = oldStart;
                data.start = oldStart;
            });
            // Reload the grid with the original page. Otherwise, API functions like table.cell(this) don't work properly.
            setTimeout(dt.ajax.reload, 0);
            // Prevent rendering of the full data to the DOM
            return false;
        });
    });
    // Requery the server with the new one-time export settings
    dt.ajax.reload();
}

function formatValueToExcel(data) {
    if (data.toString().indexOf('title="Ativo') !== -1) {
        return "Ativo";
    }

    if (data.toString().indexOf('title="Inativo') !== -1) {
        return "Inativo";
    }

    if (data.toString().indexOf('title="Não') !== -1) {
        return "Não";
    }

    if (data.toString().indexOf('title="Sim') !== -1) {
        return "Sim";
    }

    // SE O 'data' FOR UM MODAL HTML, ENTÃO APLICAMOS A LOGICA ABAIXO PARA RESGATAR O SEU VALOR.
    if (data.toString().indexOf("data-bs-toggle") != -1) {
        let number = $(data).attr("data-value");
        return number.toLocaleString("pt-BR", {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0,
        });
    }

    return data;
}

// SOMA TODOS OS VALORES DE UMA COLUNA NO DATATABLE
async function getSumColumn(vDataTable, numberColumn) {
    return vDataTable.Table.column(numberColumn)
        .data()
        .reduce(function (a, b) {
            return (parseFloat(a.toString().replace(",", ".")) + parseFloat(b.toString().replace(",", "."))).toFixed(2);
        }, 0);
}
