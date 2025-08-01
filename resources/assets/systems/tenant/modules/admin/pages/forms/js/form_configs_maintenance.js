import { findCep, filterCascade, Save } from "#common/js/utils/actions.js";
import { initInputMask, initInputMaskPhoneCell, initInputRulesFields } from "#common/js/utils/functions";

import { EnumTypeMask } from "#common/js/utils/enums/enum_type_mask";

$(window).on("load", function () {
    initInputMask();
    initInputMaskPhoneCell();
    initInputRulesFields();

    $("#btnSave").on("click", function () {
        Save($("#formConfigForm"), $(this), true, [], [], null, "Salvar", "");
    });

    /**
     *  Carrega combo de cidades de acordo com a mudança do combo uf
     */
    $("#forms_id").on("change", function () {
        filterCascade($("#forms_id").val(), null, $("#form_subjects_id"), "cascade-form-subjects", false);
    });
});
