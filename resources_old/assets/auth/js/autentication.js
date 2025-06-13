import {
    msgDefault,
    msgError,
    MSG_ERROR_ATEMPION_FIELDS,
    MSG_ERROR_PASSWORD_USER,
    MSG_SEND_LINK_RESET_PASSWORD,
} from "#common/js/utils/messages";
import { loadingBtnStart, loadingBtnStop } from "#common/js/utils/loading";
import { removeErrorsAll } from "#common/js/utils/functions";

$("#password").on("keypress", function (e) {
    if (e.key == "Enter") {
        $("#btnSendLogin").trigger("click");
    }
});

/**
 * Ajax do botão "btnLogin"
 */
$("#loginForm").on("submit", function (e) {
    e.preventDefault();
    removeErrorsAll();
    $.ajax({
        url: "/login",
        type: "POST",
        data: $("#loginForm").serialize(),
        beforeSend: loadingBtnStart($("#btnLogin")),
        success: function (data) {
            window.location.href = "/";
        },
        error: function (data) {
            removeErrorsAll();
            if (data.status == "403") {
                window.location.href = "/";
            }

            var errors = data.responseJSON.errors;

            $.each(errors, function (index, value) {
                let element = $("#loginForm").find("#" + index);
                //Select2
                //Outros Inpust
                element
                    .addClass("error")
                    .after('<label id="' + index + '-error" class="error" for="' + index + '">' + value + "</label>");
            });
            loadingBtnStop($("#btnLogin"), "", "Entrar");
        },
    });
});

/*****************************************************************************
 * ESQUECI MINHA SENHA
 *****************************************************************************/
/**
 * Ajax do botão "btnLogin"
 */
$("#passwordLoginForm").on("submit", function (e) {
    e.preventDefault();
    removeErrorsAll();
    $.ajax({
        url: "/password/email",
        type: "POST",
        data: $("#passwordLoginForm").serialize(),
        beforeSend: loadingBtnStart($("#btnPasswordLogin")),
        success: function (data) {
            removeErrorsAll();
            if (data.status == 1) {
                loadingBtnStop($("#btnPasswordLogin"), "", "Enviar Link de Redefinição de Senha");
                msgDefault(
                    "fas fa-envelope",
                    "Email enviado!",
                    MSG_SEND_LINK_RESET_PASSWORD + "<span>" + data.email + "</span>",
                    "/login",
                );
            } else {
                let element = $("#email");
                //Select2
                //Outros Inpust
                element
                    .addClass("error")
                    .after(
                        '<label id="1-error" class="error col-12" for="email">' + MSG_ERROR_PASSWORD_USER + "</label>",
                    );

                loadingBtnStop($("#btnPasswordLogin"), "", "Enviar Link de Redefinição de Senha");
            }
        },
        error: function (data) {
            removeErrorsAll();
            var errors = data.responseJSON.errors;

            $.each(errors, function (index, value) {
                let element = $("#passwordLoginForm").find("#" + index);
                //Select2
                //Outros Inpust
                element
                    .addClass("error")
                    .after(
                        '<label id="' +
                            index +
                            '-error" class="error col-12" for="' +
                            index +
                            '">' +
                            value +
                            "</label>",
                    );
                loadingBtnStop($("#btnPasswordLogin"), "", "Enviar Link de Redefinição de Senha");
            });
        },
    });
});
