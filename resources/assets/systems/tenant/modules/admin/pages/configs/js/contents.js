import { Save } from "#common/js/utils/actions.js";

$(window).on("load", function () {
    // POS_LOAD scripts.

    $("#btnSave").on("click", function () {
        Save($("#formPage"), $(this), true);
        // $('.selectpicker').selectpicker('refresh');
    });
});
