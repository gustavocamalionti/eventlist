import { DataTablesCustom } from "#common/js/utils/datatables.js";

$(document).ready(function () {
    //LIST

    var vDataTable = new DataTablesCustom(
        "#zero_config",
        ".divElementGridFather",
        true,
        "/panel/event-vouchers-filter",
    );
    vDataTable.DivFatherTableClass = "#divGrid";
    vDataTable.Info = true;
    vDataTable.Columns.Defs = [{ orderable: false, targets: [0, 2, 4] }];
    vDataTable.BtnFilter = $("#btnFilter");
    vDataTable.FormFilter = $(".form-filters");
    vDataTable.ExcelButton.Enable = true;
    vDataTable.ExcelButton.Title = `convencao2025-ingressos-${new Date().toLocaleString("sv-SE").replace(/[-: ]/g, "")}`;
    vDataTable.ExcelButton.Columns = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
    vDataTable.ColumnsFormatNumber = [7];
    vDataTable.ColumnsFormatStatus = [8];
    vDataTable.ServerSide = true;
    vDataTable.processing = true;
    vDataTable.Searching = false;
    vDataTable.ScrollX = true;
    vDataTable.Columns.ToRender = [
        {
            data: "brands_id",
            name: "brands_id",
            class: "text-left align-middle",
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
            width: "170px",
        },
        {
            data: "email",
            name: "email",
            class: "text-start align-middle",
            width: "170px",
        },

        {
            data: "positions_id",
            name: "positions_id",
            class: "text-center align-middle",
            width: "70px",
        },
        {
            data: "tshirt",
            name: "tshirt",
            class: "text-center align-middle",
            width: "60px",
        },
        {
            data: "value",
            name: "value",
            class: "text-center align-middle",
            width: "70px",
        },
        {
            data: "active",
            name: "active",
            class: "text-center align-middle",
            width: "70px",
        },

        {
            data: "updated_at",
            name: "updated_at",
            class: "text-center align-middle",
            width: "90px",
        },
    ];

    vDataTable.BtnEventClickArray = [
        // {
        //     objNameClass: ".btn-history",
        //     function: function (id) {
        //         history(id);
        //     },
        // },
    ];

    vDataTable.Init();

    // function history(id) {
    //     let userId = id;
    //     $.ajax({
    //         url: "/panel/form-contacts-history/" + userId,
    //         type: "GET",
    //         dataType: "json",
    //         beforeSender: loadingBodyStart(),
    //     })
    //         .done(function (data) {
    //             if (data.status == 1) {
    //                 $("#tbodyHistoryModal" + userId).html(data.grid);
    //             }
    //             loadingBodyStop();
    //         })
    //         .fail(function (e) {
    //             msgError("Erro!", MSG_ERROR_GENERAL);
    //         });
    // }
});
