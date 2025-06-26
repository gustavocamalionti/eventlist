import { DataTablesCustom } from "#common/js/utils/datatables.js";

$(document).ready(function () {
    //LIST

    var vDataTable = new DataTablesCustom("#zero_config", ".divElementGridFather", true, "/panel/log-errors-filter");
    vDataTable.DivFatherTableClass = "#divGrid";
    vDataTable.Info = true;
    vDataTable.Columns.Defs = [{ orderable: false, targets: [0, 4] }];
    vDataTable.BtnFilter = $("#btnFilter");
    vDataTable.FormFilter = $(".form-filters");
    vDataTable.ServerSide = true;
    vDataTable.processing = true;
    vDataTable.Columns.ToRender = [
        {
            data: "id",
            name: "id",
            class: "text-center align-middle",
            width: "37px",
        },
        {
            data: "action",
            name: "action",
            class: "text-center align-middle",
        },
        {
            data: "route",
            name: "route",
            class: "text-center align-middle",
        },
        {
            data: "title",
            name: "title",
            class: "text-center align-middle",
        },
        {
            data: "method",
            name: "method",
            class: "text-center align-middle",
        },
        {
            data: "users_name",
            name: "users_name",
            class: "text-center align-middle",
        },
        {
            data: "users_email",
            name: "users_email",
            class: "text-center align-middle",
        },
        {
            data: "status",
            name: "status",
            class: "text-center align-middle",
        },
        {
            data: "message",
            name: "message",
            class: "text-start align-middle",
        },
        {
            data: "ip",
            name: "ip",
            class: "text-center align-middle",
        },
        {
            data: "created_at",
            name: "created_at",
            class: "text-center align-middle",
        },
    ];
    vDataTable.Init();
});
