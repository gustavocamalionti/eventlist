import { DataTablesCustom } from "#common/js/utils/datatables.js";
import { history } from "#common/js/utils/actions.js";

$(document).ready(function () {
    //LIST

    var vDataTable = new DataTablesCustom("#zero_config", ".divElementGridFather", true, "/panel/form-configs-filter");
    vDataTable.DivFatherTableClass = "#divGrid";
    vDataTable.Info = true;
    vDataTable.Columns.Defs = [{ orderable: false, targets: [2, 5, 6] }];
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
            data: "form",
            name: "form",
            class: "text-left align-middle",
        },
        {
            data: "form_subject",
            name: "form_subject",
            class: "text-left align-middle",
        },
        {
            data: "name",
            name: "name",
            class: "text-start align-middle",
        },
        {
            data: "email",
            name: "email",
            class: "text-start align-middle",
        },
        {
            data: "active",
            name: "active",
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
                history("panel/form-configs-history", id);
            },
        },
    ];

    vDataTable.Init();
});
