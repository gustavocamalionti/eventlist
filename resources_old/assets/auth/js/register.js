import { msgDefault, msgError, MSG_ERROR_ATEMPION_FIELDS } from "#common/js/utils/messages";

import { loadingBtnStart, loadingBtnStop, loadingBodyStart, loadingBodyStop } from "#common/js/utils/loading";

import {
    removeErrorsAll,
    initInputMask,
    initInputMaskPhoneCell,
    initInputRulesFields,
} from "#common/js/utils/functions";

import { findCep, filterCascade, Save } from "#common/js/utils/actions.js";
import { EnumTypeMask } from "#common/js/utils/enums/enum_type_mask";

initInputMask();
initInputMaskPhoneCell();
initInputRulesFields();

/**
 * Ajax do botão "btnLogin"
 */
$("#btnRegister").on("click", async function (e) {
    let removeMaskList = [EnumTypeMask.Common + "|cpf|phone_cell|zipcode"];

    Save($("#registerForm"), $(this), true, removeMaskList, [], null, "Registrar", "");
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
