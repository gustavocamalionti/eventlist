import { Save } from "#common/js/utils/actions.js";

$(document).ready(function () {
    // POS_LOAD scripts.

    $("#btnSave").on("click", function () {
        Save($("#formPage"), $(this), true);
        // $('.selectpicker').selectpicker('refresh');
    });
});
