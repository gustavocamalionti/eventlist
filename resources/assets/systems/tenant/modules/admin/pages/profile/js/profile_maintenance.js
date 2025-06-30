import { findCep, filterCascade, Save, Delete } from "#common/js/utils/actions.js";
import { initInputMask, initInputMaskPhoneCell, initInputRulesFields } from "#common/js/utils/functions";
import { effectsTransitions } from "@assetsCommon/js/utils/effects.js";
import { MSG_SUCCESS_PASSWORD_CHANGED } from "#common/js/utils/messages";
import { EnumTypeMask } from "#common/js/utils/enums/enum_type_mask";

jQuery(function ($) {
    effectsTransitions();
    initInputMask();
    initInputMaskPhoneCell();
    initInputRulesFields();
    $(window).on("load", function () {
        $("#btnSave").on("click", function () {
            let removeMaskList = [EnumTypeMask.Common + "|cpf|phone_cell|zipcode"];
            Save($("#editForm"), $(this), true, removeMaskList, [], null, "Atualizar Dados");
        });
        $("#btnDeleteUser").on("click", function () {
            let id = $("#id").val();
            Delete(null, "/admin/profile", function () {}, id);
        });

        $("#btnAlterPassword").on("click", function () {
            Save(
                $("#editAlterPassword"),
                $(this),
                true,
                [],
                [],
                null,
                "Alterar Senha",
                "",
                MSG_SUCCESS_PASSWORD_CHANGED,
            );
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
