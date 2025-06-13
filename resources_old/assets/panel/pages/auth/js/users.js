import { msgDefault, msgError } from "#common/js/utils/messages";
import { loadingBtnStart, loadingBtnStop } from "#common/js/utils/loading";
import { removeErrorsAll } from "#common/js/utils/functions";

$("#btnEsqueciSenha").on("click", function () {
    MsgEsqueciMinhaSenha();
});

/**
 * Ajax do botão "btnLogin"
 */
$("#btnSendLogin").on("click", function (e) {
    e.preventDefault();

    var form = $("#formLogin");
    $.ajax({
        url: "/login",
        type: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        dataType: "json",
        data: form.serialize(),
        beforeSend: loadingBtnStart($(this)),
        success: function (data) {
            loadingBtnStop($("#btnSendLogin"), "", "Entrar");
            removeErrorsAll();
            // console.log('entrei aqui - success (users.js)');
            switch (data.status) {
                case 1:
                    window.location = "/panel/dashboard";
                    break;
                case 2:
                    window.location = "/panel/usuarios-manut/" + data.p;
                    break;
            }
        },
        error: function (data) {
            removeErrorsAll();

            loadingBtnStop($("#btnSendLogin"), "", "Entrar");
            var errors = data.responseJSON.errors;
            if (errors == undefined) {
                errors = data.responseJSON;
            }

            $.each(errors, function (index, value) {
                let element = $("#formLogin").find("#" + index);

                //Select2
                //Outros Inpust
                element
                    .addClass("error")
                    .after(
                        '<small id="' +
                            index +
                            '-error" class="error col-12" for="' +
                            index +
                            '">' +
                            value +
                            "</small>",
                    );
            });
            msgDefault("fa fa-times", "Erro", MSG_ERROR_ATEMPION_FIELDS, null);
        },
    });
});

/*****************************************************************************
 * ESQUECI MINHA SENHA
 *****************************************************************************/

$("#btnRecoveryPassword").on("click", function (e) {
    removeErrorsAll();
    var dadosForm = $("#formRecuperarSenha").serialize();

    $.ajax({
        url: "/esqueci-minha-senha",
        type: "POST",
        dataType: "json",
        data: dadosForm,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        beforeSend: loadingBtnStart($(this)),
        success: function (data) {
            if (data.status == 1) {
                loadingBtnStop($("#btnRecoveryPassword"), "Enviar");
                msgDefault(
                    "fa fa-envelope-o",
                    "Email enviado!",
                    MSG_SEND_LINK_RESET_PASSWORD + '<span class="c_red f_size_15">' + data.email + "</span>",
                    "/login",
                );
            } else {
                $("#formRecuperarSenha input")
                    .addClass("error")
                    .after(
                        '<small id="email-error" class="error col-12" for="email">' +
                            MSG_ERROR_PASSWORD_USER +
                            "</small>",
                    );
                loadingBtnStop($("#btnRecoveryPassword"), "Enviar");
            }
        },
        error: function (data) {
            removeErrorsAll();
            var errors = data.responseJSON.errors;

            $.each(errors, function (index, value) {
                let element = $("#formRecuperarSenha").find("#" + index);
                //Select2
                //Outros Inpust
                element
                    .addClass("error")
                    .after(
                        '<small id="' +
                            index +
                            '-error" class="error col-12" for="' +
                            index +
                            '">' +
                            value +
                            "</small>",
                    );
                loadingBtnStop($("#btnRecoveryPassword"), "Enviar");
            });
        },
    });
});

$("#btnSendPassword").on("click", function () {
    removeErrorsAll();

    var form = $("#formAlterarSenha");
    form[0]["email"].value = form[0]["email"].value.trim();
    form[0]["password"].value = form[0]["password"].value.trim();
    form[0]["password_confirmation"].value = form[0]["password_confirmation"].value.trim();
    var dadosForm = form.serialize();

    $.ajax({
        url: "/reset-senha-save",
        type: "POST",
        dataType: "json",
        data: dadosForm,
        beforeSend: loadingBtnStart($(this)),
        success: function (data) {
            switch (data.status) {
                case 1: //Senha Alterada com sucesso
                    msgDefault("fa fa-key", "Parabéns!", MSG_SUCCESS_PASSWORD_CHANGED, "/panel/dashboard");
                    break;
                case 2: //Senha Alterada com sucesso, necessita atualização do cadastro.
                    msgDefault(
                        "fa fa-key",
                        "Parabéns!",
                        MSG_SUCCESS_PASSWORD_CHANGED_UPDATE_DATA,
                        "/usuarios-manut/" + data.p,
                    );
                    break;
                case 3: //Esse código de redefinição de senha é inválido.
                    msgDefault("fa fa-times", "Erro", MSG_ERROR_PASSWORD_TOKEN, null);
                    break;
                case 4: //Usuário inválido
                    msgDefault("fa fa-times", "Erro", MSG_ERROR_EMAIL_TOKEN, null);
                    break;
                default:
                    msgDefault("fa fa-times", "Erro", MSG_ERROR_PASSWORD_NOT_CHANGED, null);
            }
            loadingBtnStop($("#btnSendPassword"), "Enviar");
        },
        error: function (data) {
            if (data.status == 500) {
                msgDefault("fa fa-times", "Erro", MSG_ERROR_GENERAL, null);
            } else {
                var errors = data.responseJSON.errors;

                $.each(errors, function (index, value) {
                    form.find("#" + index)
                        .addClass("error")
                        .after(
                            '<small id="' +
                                index +
                                '-error" class="error col-12" for="' +
                                index +
                                '">' +
                                value +
                                "</small>",
                        );
                });
                msgDefault("fa fa-times", "Erro", MSG_ERROR_ATEMPION_FIELDS, null);
                loadingBtnStop($("#btnSendPassword"), "Enviar");
            }
        },
    });

    return false;
});
