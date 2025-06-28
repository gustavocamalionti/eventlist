import $ from "jquery";

$(document).ready(function () {
    $("#divFiltersContent").on("shown.bs.collapse", function () {
        $("#iconFilter").removeClass("fa-plus-square").addClass("fa-minus-square");
    });

    $("#divFiltersContent").on("hidden.bs.collapse", function () {
        $("#iconFilter").removeClass("fa-minus-square").addClass("fa-plus-square");
    });
});

function MontaBotaoExcel() {
    if (typeof vBtnExcelShow != "undefined" && vBtnExcelShow) {
        $(".dt-buttons").remove();
        new $.fn.dataTable.Buttons($(".datatables-custom"), {
            buttons: [
                {
                    text: '<span class="fa fa-file-excel-o"></span> Excel',
                    extend: "excelHtml5",
                    className: "btn btn-sm btn-primary m_left_3",
                    title: vTitleExcel,
                    footer: true,
                    exportOptions: {
                        columns: ":visible",
                    },
                },
            ],
        })
            .container()
            .appendTo($("#bar_buttons"));
    }
}
