import { DataTablesCustom } from "#common/js/utils/datatables.js";

$(document).ready(function () {
    //LIST

    var vDataTable = new DataTablesCustom("#zero_config", ".divElementGridFather", true, "/panel/log-emails-filter");
    vDataTable.DivFatherTableClass = "#divGrid";
    vDataTable.Info = true;
    vDataTable.Columns.Defs = [{ orderable: false, targets: [0, 4] }];
    vDataTable.BtnFilter = $("#btnFilter");
    vDataTable.FormFilter = $(".form-filters");
    vDataTable.ServerSide = true;
    vDataTable.processing = true;
    vDataTable.Columns.ToRender = [
        {
            data: "uuid",
            name: "uuid",
            class: "text-center align-middle",
        },
        {
            data: "job_title",
            name: "job_title",
            class: "text-center align-middle",
        },
        {
            data: "email",
            name: "email",
            class: "text-center align-middle",
        },
        {
            data: "users_id",
            name: "users_id",
            class: "text-center align-middle",
        },
        {
            data: "status",
            name: "status",
            class: "text-center align-middle",
        },
        {
            data: "details",
            name: "details",
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
