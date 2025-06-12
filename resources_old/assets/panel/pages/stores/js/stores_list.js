import { DataTablesCustom } from "#common/js/utils/datatables.js";
import { history } from "#common/js/utils/actions.js";

$(document).ready(function () {
    //LIST
    var vDataTable = new DataTablesCustom("#zero_config", ".divElementGridFather", true, "/panel/stores-filter");
    vDataTable.DivFatherTableClass = "#divGrid";
    vDataTable.Info = true;
    vDataTable.Columns.Defs = [{ orderable: false, targets: [3, 4, 5, 6, 9, 10] }];
    vDataTable.BtnFilter = $("#btnFilter");
    vDataTable.FormFilter = $(".form-filters");
    vDataTable.ExcelButton.Title = `convencao2025-lojas-${new Date().toLocaleString("sv-SE").replace(/[-: ]/g, "")}`;
    vDataTable.ExcelButton.Enable = true;
    vDataTable.ExcelButton.Columns = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
    vDataTable.ColumnsFormatNumber = [8];
    vDataTable.ColumnsFormatStatus = [7];
    vDataTable.ScrollX = true;
    vDataTable.Columns.ToRender = [
        {
            data: "id",
            name: "id",
            class: "text-center align-middle",
            width: "37px",
        },
        {
            data: "brands_id",
            name: "brands_id",
            class: "text-left align-middle",
            width: "34px",
        },
        {
            data: "name",
            name: "name",
            class: "text-left align-middle",
            width: "180px",
        },
        {
            data: "states",
            name: "states",
            class: "text-left align-middle",
            width: "25px",
        },
        {
            data: "email",
            name: "email",
            class: "text-start align-middle",
            width: "250px",
        },
        {
            data: "cnpj",
            name: "cnpj",
            class: "text-start align-middle",
        },
        {
            data: "phone_cell",
            name: "phone_cell",
            class: "text-center align-middle",
            width: "85px",
        },
        {
            data: "is_subscribe",
            name: "is_subscribe",
            class: "text-center align-middle",
        },
        {
            data: "qtd_vouchers",
            name: "qtd_vouchers",
            class: "text-center align-middle",
        },
        {
            data: "tshirts",
            name: "tshirts",
            class: "text-center align-middle",
        },

        {
            data: "actions",
            name: "actions",
            class: "text-center align-middle",
            width: "100px",
        },
    ];

    vDataTable.BtnEventClickArray = [
        {
            objNameClass: ".btn-history",
            function: function (id) {
                history("panel/stores-history", id);
            },
        },
    ];

    vDataTable.Init();
});
