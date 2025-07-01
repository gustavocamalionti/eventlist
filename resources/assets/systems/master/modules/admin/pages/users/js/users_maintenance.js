import { findCep, filterCascade, Save } from "#common/js/utils/actions.js";
import { initInputMask, initInputMaskPhoneCell, initInputRulesFields } from "#common/js/utils/functions";

import { EnumTypeMask } from "#common/js/utils/enums/enum_type_mask";

jQuery(function ($) {
    initInputMask();
    initInputMaskPhoneCell();
    initInputRulesFields();
    $(window).on("load", function () {
        $("#btnSave").on("click", function () {
            let removeMaskList = [EnumTypeMask.Common + "|cpf|phone_cell|zipcode"];
            Save($("#userForm"), $(this), true, removeMaskList, [], null, "Salvar", "");
        });
    });
});

/**
 * Preenchimento do endereço através do CEP
 */

$("#zipcode").on("blur", function () {
    var apicep = $("#apicep").val();
    var cep = this.value;
    if (cep.length < 10) {
        return false;
    } else {
        findCep(cep, apicep);
    }
});

/**
 *  Carrega combo de cidades de acordo com a mudança do combo uf
 */
$("#states_id").on("change", function () {
    filterCascade($("#states_id").val(), null, $("#cities_id"), "cities", false);
});
