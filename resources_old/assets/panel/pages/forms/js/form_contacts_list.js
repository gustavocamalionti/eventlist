import { DataTablesCustom } from "#common/js/utils/datatables.js";

$(document).ready(function () {
    //LIST

    var vDataTable = new DataTablesCustom("#zero_config", ".divElementGridFather", true, "/panel/form-contacts-filter");
    vDataTable.DivFatherTableClass = "#divGrid";
    vDataTable.Info = true;
    vDataTable.Columns.Defs = [{ orderable: false, targets: [1, 5, 6] }];
    vDataTable.BtnFilter = $("#btnFilter");
    vDataTable.FormFilter = $(".form-filters");
    vDataTable.ExcelButton.Enable = true;
    vDataTable.ExcelButton.Columns = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
    vDataTable.ColumnsFormatNumber = [];
    vDataTable.ServerSide = true;
    vDataTable.processing = true;
    vDataTable.ScrollX = true;
    vDataTable.Columns.ToRender = [
        {
            data: "form_subjects_id",
            name: "form_subjects_id",
            class: "text-center align-middle",
            width: "200px",
        },
        {
            data: "cpf",
            name: "cpf",
            class: "text-center align-middle",
            width: "115px",
        },
        {
            data: "name",
            name: "name",
            class: "text-center align-middle",
            width: "150px",
        },
        {
            data: "email",
            name: "name",
            class: "text-center align-middle",
        },
        {
            data: "stores_id",
            name: "stores_id",
            class: "text-center align-middle",
            width: "150px",
        },

        {
            data: "message",
            name: "message",
            class: "text-center align-middle",
            width: "250px",
        },
        {
            data: "phone_cell",
            name: "phone_cell",
            class: "text-center align-middle",
            width: "135px",
        },
        {
            data: "cities_id",
            name: "cities_id",
            class: "text-center align-middle",
        },
        {
            data: "states_id",
            name: "states_id",
            class: "text-center align-middle",
        },

        {
            data: "privacy_policy",
            name: "privacy_policy",
            class: "text-center align-middle",
        },
        {
            data: "created_at",
            name: "created_at",
            class: "text-center align-middle",
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
