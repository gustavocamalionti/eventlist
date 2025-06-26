import { DataTablesCustom } from "#common/js/utils/datatables.js";
import { history } from "#common/js/utils/actions.js";

$(document).ready(function () {
    //LIST
    var vDataTable = new DataTablesCustom("#zero_config", ".divElementGridFather", true, "/panel/event-buys-filter");
    vDataTable.DivFatherTableClass = "#divGrid";
    vDataTable.Info = true;
    vDataTable.Columns.Defs = [{ orderable: false, targets: [0, 1, 2, 3, 4, 5, 6] }];
    vDataTable.BtnFilter = $("#btnFilter");
    vDataTable.FormFilter = $(".form-filters");
    vDataTable.ExcelButton.Enable = true;
    vDataTable.ExcelButton.Title = `convencao2025-vendas-${new Date().toLocaleString("sv-SE").replace(/[-: ]/g, "")}`;
    vDataTable.ExcelButton.Columns = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
    vDataTable.ColumnsFormatNumber = [8, 9, 10];
    vDataTable.ColumnsFormatStatus = [10];
    vDataTable.ScrollX = true;
    vDataTable.Columns.ToRender = [
        {
            data: "actions",
            name: "actions",
            class: "text-center align-middle",
            width: "70px",
        },
        {
            data: "brands_id",
            name: "brands_id",
            class: "text-center align-middle",
            width: "50px",
        },
        {
            data: "stores_id",
            name: "stores_id",
            class: "text-start align-middle",
            width: "180px",
        },
        {
            data: "states",
            name: "states",
            class: "text-center align-middle",
            width: "40px",
        },
        {
            data: "name",
            name: "name",
            class: "text-start align-middle",
            width: "150px",
        },
        {
            data: "email",
            name: "email",
            class: "text-start align-middle",
            width: "150px",
        },
        {
            data: "phone_cell",
            name: "phone_cell",
            class: "text-start align-middle",
            width: "80px",
        },
        {
            data: "method_payments_id",
            name: "method_payments_id",
            class: "text-center align-middle",
            width: "70px",
        },
        {
            data: "qtd_vouchers",
            name: "qtd_vouchers",
            class: "text-center align-middle",
        },
        {
            data: "value",
            name: "value",
            class: "text-center align-middle",
        },
        {
            data: "net_value",
            name: "net_value",
            class: "text-center align-middle",
        },
        {
            data: "status",
            name: "status",
            class: "text-center align-middle",
        },
        {
            data: "updated_at",
            name: "updated_at",
            class: "text-center align-middle",
            width: "90px",
        },
    ];

    vDataTable.BtnEventClickArray = [
        {
            objNameClass: ".btn-history",
            function: function (id) {
                history("panel/event-buys-history", id, [
                    {
                        datatable: "#zero_config_voucher",
                        modal: "#tbodyVoucherModal",
                        divElementGrid: ".divElementGridFatherVoucher",
                        nameReferenceGrid: "gridEventVouchers",
                    },
                    {
                        datatable: "#zero_config_email",
                        modal: "#tbodyEmailModal",
                        divElementGrid: ".divElementGridFatherEmail",
                        nameReferenceGrid: "gridEventEmails",
                    },
                    {
                        datatable: "#zero_config_webhook",
                        modal: "#tbodyWebhookModal",
                        divElementGrid: ".divElementGridFatherWebhook",
                        nameReferenceGrid: "gridEventWebhooks",
                    },
                ]);
            },
        },
    ];

    vDataTable.Init();
});
