import { DataTablesCustom } from "#common/js/utils/datatables.js";

$(document).ready(function () {
    //LIST

    var vDataTable = new DataTablesCustom("#zero_config", ".divElementGridFather", true, "/admin/log-webhooks-filter");
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
            data: "buys_id",
            name: "buys_id",
            class: "text-center align-middle",
        },
        {
            data: "events_id",
            name: "events_id",
            class: "text-center align-middle",
        },
        {
            data: "event_type",
            name: "event_type",
            class: "text-center align-middle",
        },
        {
            data: "should_treat",
            name: "should_treat",
            class: "text-center align-middle",
            width: "65px",
        },
        {
            data: "status",
            name: "status",
            class: "text-center align-middle",
            width: "85px",
        },
        {
            data: "created_at",
            name: "created_at",
            class: "text-center align-middle",
        },
    ];
    vDataTable.Init();
});
