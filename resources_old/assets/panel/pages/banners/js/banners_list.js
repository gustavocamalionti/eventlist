import { DataTablesCustom } from "#common/js/utils/datatables.js";
import { MSG_ERROR_GENERAL } from "#common/js/utils/messages";
import { msgError } from "#common/js/utils/messages";
import { loadingBodyStart, loadingBodyStop } from "#common/js/utils/loading";
import { Archive, Filter, Delete, saveOrderAndDraggable } from "#common/js/utils/actions";

jQuery(function ($) {
    $(window).on("load", function () {
        applyAll();
        applyFilter();

        $("#btnUnlock").on("click", function (e) {
            saveOrderAndDraggable(e, $(this), "/panel/banners-store-order");
        });
    });
});

async function applyButtonsLoadHeader() {
    if ($("#selFilterStatus").val() == 0) {
        $("#btnUnlock").hide();
        $("#btnInterrogate").hide();
        $("#draggablePanelList").sortable("disable");
        // $('.ui-sortable-handle').removeClass('hp_cursor_move').addClass('hp_cursor_default');
    } else {
        $("#btnNew").show();
        $("#btnUnlock").show();
        $("#btnInterrogate").show();
        $("#draggablePanelList").sortable("disable");
    }
}

async function applyAll() {
    applyDragDrop();
    applyDelete();
    applyArchive();
    applyHistory();
    applyButtonsLoadHeader();

    if ($("#empty").length) {
        $("#btnUnlock").hide();
        $("#btnInterrogate").hide();
    }
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    const tooltipList = [...tooltipTriggerList].map((tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl));
}

function applyDragDrop() {
    var panelList = $("#draggablePanelList");

    panelList.sortable({
        // Only make the .panel-heading child elements support dragging.
        // Omit this to make then entire <li>...</li> draggable.
        handle: ".card-header",
        update: function () {
            $(".card", panelList).each(function (index, elem) {
                var $listItem = $(elem),
                    newIndex = $listItem.index();

                // Persist the new indices.
            });
        },
    });

    $("#draggablePanelList").sortable("disable");
}

function applyDelete() {
    $(".lnk-delete").on("click", function (e) {
        Delete(null, $(this).attr("attr-url"), applyAll);
    });
}

function applyArchive() {
    $(".lnk-archive").on("click", function (e) {
        Archive(null, $(this).attr("attr-url"), $(this).attr("attr-title"), applyAll);
    });
}

function applyFilter() {
    $("#btnFilter").on("click", async function () {
        await Filter(null, applyAll, "banners");
    });
}

function applyHistory() {
    $(".btn-history").on("click", function (e) {
        e.preventDefault();
        let id = $(this).data("id");
        $.ajax({
            url: "/panel/banners-history/" + id,
            type: "GET",
            dataType: "json",
            beforeSender: loadingBodyStart(),
        })
            .done(function (data) {
                if ($.fn.DataTable.isDataTable("#zero_config_history" + id)) {
                    $("#zero_config_history" + id)
                        .DataTable()
                        .destroy();
                }

                if (data.status == 1) {
                    $("#tbodyHistoryModal" + id).html(data.grid);
                }

                var vDataTableHistory = new DataTablesCustom(
                    "#zero_config_history" + id,
                    ".divElementGridFatherHistory" + id,
                    false,
                );
                vDataTableHistory.DivFatherTableClass = "#divGrid";
                vDataTableHistory.Info = true;
                vDataTableHistory.Columns.Defs = [{ orderable: false, targets: [] }];
                vDataTableHistory.Init();
                loadingBodyStop();
            })
            .fail(function (e) {
                msgError("Erro!", MSG_ERROR_GENERAL);
            });
    });
}
