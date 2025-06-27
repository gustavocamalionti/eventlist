import { DataTablesCustom } from "#common/js/utils/datatables.js";
import { history } from "#common/js/utils/actions.js";

$(document).ready(function () {
    //LIST

    var vDataTable = new DataTablesCustom("#zero_config", ".divElementGridFather", true, "/panel/links-filter");
    vDataTable.DivFatherTableClass = "#divGrid";
    vDataTable.Info = true;
    vDataTable.Columns.Defs = [{ orderable: false, targets: [4, 8] }];
    vDataTable.BtnFilter = $("#btnFilter");
    vDataTable.FormFilter = $(".form-filters");
    // vDataTable.ExcelButton.Enable = true;
    // vDataTable.ExcelButton.Columns = [0,1,2,3,4,5,6];
    // vDataTable.ColumnsFormatNumber = [];
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
            data: "stores",
            name: "stores",
            class: "text-left align-middle",
        },
        {
            data: "title",
            name: "title",
            class: "text-center align-middle",
        },
        {
            data: "slug",
            name: "slug",
            class: "text-center align-middle",
        },
        {
            data: "name",
            name: "name",
            class: "text-center align-middle",
        },
        {
            data: "link_type",
            name: "link_type",
            class: "text-center align-middle",
        },
        {
            data: "created_at",
            name: "created_at",
            class: "text-center align-middle",
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
                history("admin/links-history", id);
            },
        },
    ];

    vDataTable.Init();
});
