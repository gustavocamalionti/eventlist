import { findCep, filterCascade, filterStoresCard, Save } from "#common/js/utils/actions.js";

import { initInputMask, initInputMaskPhoneCell, initInputRulesFields } from "#common/js/utils/functions";

import { EnumFormSubjects } from "#common/js/utils/enums/enum_form_subjects";
import { EnumTypeMask } from "#common/js/utils/enums/enum_type_mask";

initInputMask();
initInputMaskPhoneCell();
initInputRulesFields();

$(window).on("load", function () {
    $("#btnSave").on("click", function (e) {
        e.preventDefault();
        let removeMaskList = [EnumTypeMask.Common + "|phone_cell|cpf"];
        Save($("#contactForm"), $(this), true, removeMaskList, [], null, "Enviar", "");
    });
});

/**
 *  Carrega combo de cities de acordo com a mudança do combo uf
 */
$("#states_id").on("change", async function () {
    await filterCascade($("#states_id").val(), null, $("#cities_id"), "cities", false, "Selecione...");
});

/**
 *  Carrega combo de stores de acordo com a mudança do combo de cities
 */
$("#cities_id").on("change", async function () {
    await filterCascade($("#cities_id").val(), null, $("#stores_id"), "stores", false, "Selecione...");
});

$("#stores_id").on("change", async function () {
    await filterStoresCard("filter-stores-card", $("#states_id").val(), $("#cities_id").val(), $("#stores_id").val());
});

$("#form_subjects_id").on("change", async function () {
    let divFormSubject = $("#divFormSubject");
    let divCpf = $("#divCpf");
    let cpf = $("#cpf");
    $("#form_subjects_id").select2("destroy");

    if ($(this).val() == EnumFormSubjects.Loyalty_program) {
        divFormSubject.addClass("col-md-6");
        divCpf.removeClass("d-none");
        cpf.attr("disabled", false);
    } else {
        cpf.attr("disabled", true);
        divFormSubject.removeClass("col-md-6");
        divCpf.addClass("d-none");
    }

    $("#form_subjects_id").select2({
        theme: "bootstrap-5",
    });
});
