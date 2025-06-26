import { DataTablesCustom } from "#common/js/utils/datatables.js";
import { loadingBodyStart, loadingBodyStop } from "#common/js/utils/loading.js";

import { msgDefault, msgError, MSG_ERROR_GENERAL } from "#common/js/utils/messages.js";

$(document).ready(function () {
    //LIST

    var vDataTable = new DataTablesCustom("#zero_config", ".divElementGridFather", true, "/panel/log-audits-filter");
    vDataTable.DivFatherTableClass = "#divGrid";
    vDataTable.Info = true;
    vDataTable.ScrollX = true;
    vDataTable.Columns.Defs = [{ orderable: false, targets: [0, 11] }];
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
            data: "route",
            name: "route",
            class: "text-center align-middle",
        },

        {
            data: "action",
            name: "action",
            class: "text-center align-middle",
        },
        {
            data: "description",
            name: "description",
            class: "text-center align-middle",
        },
        {
            data: "title",
            name: "title",
            class: "text-center align-middle",
        },
        {
            data: "table_name",
            name: "table_name",
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
            data: "ip",
            name: "ip",
            class: "text-center align-middle",
        },
        {
            data: "created_at",
            name: "created_at",
            class: "text-center align-middle",
        },
        {
            data: "details",
            name: "details",
            class: "text-start align-middle",
            width: "350px",
        },
    ];

    vDataTable.BtnEventClickArray = [
        {
            objNameClass: ".btn-history",
            function: function (id) {
                history(id);
            },
        },
    ];

    vDataTable.Init();

    function history(id) {
        $.ajax({
            url: "/panel/log-audits-history/" + id,
            type: "GET",
            dataType: "json",
            beforeSender: loadingBodyStart(),
        })
            .done(function (data) {
                if (data.status == 1) {
                    $("#tbodyHistoryModal" + id).html(data.grid);
                }
                loadingBodyStop();
            })
            .fail(function (e) {
                msgError("Erro!", MSG_ERROR_GENERAL);
            });
    }
});
