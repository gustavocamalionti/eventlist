import { EnumTypeMask } from "#common/js/utils/enums/enum_type_mask";

/**
 * Remove Mask String
 */

export function replaceMasks(data) {
    var arrayFind = [".", "-", "/", "(", ")", "_", "%2F", "%20"];
    var arrayReplace = ["", "", "", "", "", "", "", ""];

    var replaceString = data.toString();
    var regex;
    for (var i = 0; i < arrayFind.length; i++) {
        regex = new RegExp("\\" + arrayFind[i], "g");
        replaceString = replaceString.replace(regex, arrayReplace[i]);
    }
    return replaceString;
}

/**
 * Remove Mask String (Currency)
 */
export function replaceMasksCurrency(data) {
    var arrayFind = ["%2C", "."];
    var arrayReplace = ["%2E", ""];

    var replaceString = data.toString();
    var regex;
    for (var i = 0; i < arrayFind.length; i++) {
        regex = new RegExp("\\" + arrayFind[i], "g");
        replaceString = replaceString.replace(regex, arrayReplace[i]);
    }
    return replaceString;
}

export function convertDateBrToEn(data) {
    if (data != null && data != "") {
        var parts = data.split("%2F");
        data = parts[2] + "%96" + parts[0] + "%96" + parts[1];
    }

    return data;
}

/**
 * Remove Mask dos Campos do Form Após ser Serializado
 */
export function removeMasksFields_old(pArrayField, pData, pType) {
    var text = pData + "&";
    var textWithMask = "";
    var textWithOutMask = "";
    for (var i = 0; i < pArrayField.length; i++) {
        var regex = new RegExp(pArrayField[i] + "=(.*?)&", "i");
        if (text.match(regex) != null) {
            textWithMask = text.match(regex)[1];
            //var vElement = textWithMask = $('#' + pArrayField[i]);
            //if(vElement != null){
            //textWithMask = vElement.val();
            switch (pType) {
                case 1: //Comum
                    textWithOutMask = replaceMasks(textWithMask);
                    break;
                case 2: //Currency
                    textWithOutMask = replaceMasksCurrency(textWithMask);
                    break;
                case 3: //Data
                    textWithOutMask = convertDateBrToEn(textWithMask);
                    break;
            }

            text = text.replace(textWithMask, textWithOutMask);
        }
    }

    return text.slice(0, -1);
}
/**
 * Remove Mask dos Campos do Form Após ser Serializado
 * @param {Array - Campos para remover maskara. ['0|campo1|campo2|campo3','1|campo4|campo5', '2|campo6' ]  } pArrayField
 */
export function removeMasksFields(pArrayField, pData) {
    var text = pData + "&";
    var textWithMask = "";
    var textWithOutMask = "";

    if (typeof pArrayField !== "undefined" && pArrayField.length > 0) {
        pArrayField.forEach((element) => {
            let data = element.split("|");
            for (var i = 1; i < data.length; i++) {
                var regex = new RegExp(data[i] + "=(.*?)&", "i");
                if (text.match(regex) != null) {
                    textWithMask = text.match(regex)[1];
                    switch (data[0]) {
                        case EnumTypeMask.Common:
                            textWithOutMask = replaceMasks(textWithMask);
                            break;
                        case EnumTypeMask.Currency:
                            textWithOutMask = replaceMasksCurrency(textWithMask);
                            break;
                        case EnumTypeMask.Date:
                            textWithOutMask = convertDateBrToEn(textWithMask);
                            break;
                    }
                    text = text.replace(textWithMask, textWithOutMask);
                }
            }
        });
        return text.slice(0, -1);
    }
}

/**
 * Input Mask
 */
export function initInputMask() {
    $("[data-mask-date]").inputmask({
        mask: "99/99/9999",
        placeholder: "__/__/____",
        clearIncomplete: true,
    });
    $("[data-mask-time]").inputmask({
        mask: "99:99",
        placeholder: "__:__",
        clearIncomplete: true,
    });
    $("[data-mask-year]").inputmask({
        mask: "9999",
        placeholder: "____",
        clearIncomplete: true,
    });
    $("[data-mask-date_time]").inputmask({
        mask: "99/99/9999 99:99:99",
        placeholder: "__/__/__ :__:__",
        clearIncomplete: true,
    });
    $("[data-mask-phone]").inputmask({
        mask: "(99) 9999-9999",
        clearIncomplete: true,
    });
    $("[data-mask-cep]").inputmask({
        mask: "99.999-999",
        placeholder: "__.___-___",
        clearIncomplete: true,
    });
    $("[data-mask-cpf]").inputmask({
        mask: "999.999.999-99",
        placeholder: "___.___.___-__",
        clearIncomplete: true,
    });
    $("[data-mask-cnpj]").inputmask({
        mask: "99.999.999/9999-99",
        placeholder: "__.___.___/____-__",
        clearIncomplete: true,
    });
    $("[data-mask-ie]").inputmask({
        mask: "999.999.999.999",
        placeholder: "___.___.___.___",
        clearIncomplete: true,
    });
    //$("[data-mask-integer]").inputmask('integer');
    // $("[data-mask-percent]").maskMoney({ thousands: '.', decimal: ',', allowZero: true, suffix: '', precision: 2 });
    // $("[data-mask-money]").maskMoney({ thousands: '.', decimal: ',', allowZero: true, suffix: '', precision: 2 });
    // $("[data-mask-integer]").maskMoney({ thousands: '.', decimal: ',', allowZero: true, suffix: '', precision: 0 });
    // $("[data-mask-money-negative]").maskMoney({ thousands: '.', decimal: ',', allowZero: true, suffix: '', precision: 2, allowNegative: true });
    // $("[data-mask-integer-negative]").maskMoney({ thousands: '.', decimal: ',', allowZero: true, suffix: '', precision: 0, allowNegative: true });
}

// Using jquery.inputmask: https://github.com/RobinHerbots/jquery.inputmask
// For 8 digit phone fields, the mask is like this: (99) 9999-9999
// For 9 digit phone fields the mask is like this: (99) 99999-9999
export function initInputMaskPhoneCell() {
    $("[data-mask-phone-cell]").each(function () {
        $(this).on("keyup", function () {
            setCorrectPhoneMask($(this));
        });
    });
}

/**
 * Input Rules Fields
 */
jQuery.fn.removeNot = function (settings) {
    var $this = jQuery(this);
    var defaults = {
        pattern: /[^0-9]/,
        replacement: "",
    };
    settings = jQuery.extend(defaults, settings);

    $this.keyup(function () {
        var new_value = $this.val().replace(settings.pattern, settings.replacement);
        $this.val(new_value);
    });
    return $this;
};

export function initInputRulesFields() {
    $("[data-only-word-upper]").removeNot({ pattern: /[^A-Z]+/gi });
    $("[data-only-numbers-word-upper]").removeNot({ pattern: /[^A-Z0-9]+/gi });
    $("[data-only-numbers]").removeNot({ pattern: /[^0-9]+/gi });
}

export function setCorrectPhoneMask(element) {
    let value = element.inputmask("unmaskedvalue");
    if (value.length > 10) {
        element.inputmask("remove");
        element.inputmask({ mask: "(99) 9999[9]-9999" });
    } else {
        element.inputmask("remove");
        element.inputmask({ mask: "(99) 9999-9999[9]", greedy: false });
    }
    element.val(value);
}

/**
 *  Remove todas as classes de erro dos campos
 */
export function removeErrorsAll() {
    $("[id$='-error']").remove();
    $("small.error").remove();
    $(".error").removeClass("error");
}

//formata de forma generica os campos
export function formataCampo(campo, Mascara, evento) {
    var boleanoMascara;

    var Digitato = evento.keyCode;
    exp = /\-|\.|\/|\(|\)| /g;
    campoSoNumeros = campo.toString().replace(exp, "");

    var posicaoCampo = 0;
    var NovoValorCampo = "";
    var TamanhoMascara = campoSoNumeros.length;

    if (Digitato != 8) {
        // backspace
        for (i = 0; i <= TamanhoMascara; i++) {
            boleanoMascara = Mascara.charAt(i) == "-" || Mascara.charAt(i) == "." || Mascara.charAt(i) == "/";
            boleanoMascara =
                boleanoMascara || Mascara.charAt(i) == "(" || Mascara.charAt(i) == ")" || Mascara.charAt(i) == " ";
            if (boleanoMascara) {
                NovoValorCampo += Mascara.charAt(i);
                TamanhoMascara++;
            } else {
                NovoValorCampo += campoSoNumeros.charAt(posicaoCampo);
                posicaoCampo++;
            }
        }
        campo.value = NovoValorCampo;
        return true;
    } else {
        return true;
    }
}

/**
 *  Coloca máscara em campos (Exibição ex. coluna DataTables)
 */
//formata de forma generica os campos
export function maskFieldsCampo(campo, Mascara) {
    var boleanoMascara;

    //var Digitato = evento.keyCode;
    exp = /\-|\.|\/|\(|\)| /g;
    campoSoNumeros = campo.toString().replace(exp, "");

    var posicaoCampo = 0;
    var NovoValorCampo = "";
    var TamanhoMascara = campoSoNumeros.length;

    for (vi = 0; vi <= TamanhoMascara; vi++) {
        boleanoMascara = Mascara.charAt(vi) == "-" || Mascara.charAt(vi) == "." || Mascara.charAt(vi) == "/";
        boleanoMascara =
            boleanoMascara || Mascara.charAt(vi) == "(" || Mascara.charAt(vi) == ")" || Mascara.charAt(vi) == " ";
        if (boleanoMascara) {
            NovoValorCampo += Mascara.charAt(vi);
            TamanhoMascara++;
        } else {
            NovoValorCampo += campoSoNumeros.charAt(posicaoCampo);
            posicaoCampo++;
        }
    }
    return NovoValorCampo;
}

//adiciona mascara de cnpj
export function MascaraCNPJ(cnpj) {
    return MaskFieldsCampo(cnpj, "00.000.000/0000-00");
}

export function addPrefix(prefix, fields) {
    return fields.map((field) => (prefix || "") + field);
}

/*****************************************************************************
 * PRÉ LOADING PARA OS SWEET ALERTS
 *****************************************************************************/
function StartPreloaderSweetAlert() {
    $(".swal2-confirm").attr("disabled", true);
    $(".swal2-cancel").attr("disabled", true);
    $(".swal2-confirm").html('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Aguarde...');
}
function FinishPreloaderSweetAlert(caption) {
    $(".swal2-confirm").html(caption);
    $(".swal2-confirm").removeAttr("disabled", false);
    $(".swal2-cancel").removeAttr("disabled", false);
}

/*****************************************************************************
 * PRÉ LOADING PARA BODY
 *****************************************************************************/
export function startPreloaderGrid() {
    $("body").addClass("loading");
    $("#bar_buttons .btn").prop("disabled", true);
    $("#divFiltersContent button").prop("disabled", true);
    $("#divFiltersContent select").prop("disabled", true);
}

export function finishPreloaderGrid() {
    $("body").removeClass("loading");
    $("#bar_buttons .btn").prop("disabled", false);
    $("#divFiltersContent button").prop("disabled", false);
    $("#divFiltersContent select").prop("disabled", false);
}

//SLUG - FORMATADOR DE TEXTO PADRÃO URL OU ARQUIVO
export function generateSlug(str) {
    str = str.replace(/^\s+|\s+$/g, ""); // trim
    str = str.toLowerCase();

    // remove accents, swap ñ for n, etc
    var from = "ãàáäâẽèéëêìíïîõòóöôùúüûñç·/_,:;";
    var to = "aaaaaeeeeeiiiiooooouuuunc------";
    for (var i = 0, l = from.length; i < l; i++) {
        str = str.replace(new RegExp(from.charAt(i), "g"), to.charAt(i));
    }

    str = str
        .replace(/[^a-z0-9 -]/g, "") // remove invalid chars
        .replace(/\s+/g, "-") // collapse whitespace and replace by -
        .replace(/-+/g, "-"); // collapse dashes

    return str;
}

/**
 * Função auxiliar para limpar formulário após erro de pesquisa do cep
 */
export function resetFieldsAddress() {
    $("#address").val("");
    $("#number").val("");
    $("#district").val("");
    $("#complement").val("");
    $("#states_id").val("").trigger("change");
    $("#cities_id").val("").trigger("change");
}

/**
 *
 * Função auxiliar para encontrar cidade pelo nome
 */

export function defineCityByName(name) {
    $.ajax({
        url: "/cities-name/" + name,
        type: "GET",
        dataType: "json",
    }).done(function (id) {
        filterCascade($("#states_id").val(), id, $("#cities_id"), "cities", false);
        loadingBodyStop();
    });
}

export function initPopovers() {
    const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]');

    const popoverList = [...popoverTriggerList].map((popoverTriggerEl) => {
        const popover = new bootstrap.Popover(popoverTriggerEl, {
            trigger: "focus", // ou "click", se preferir,
        });

        return popover;
    });
}
