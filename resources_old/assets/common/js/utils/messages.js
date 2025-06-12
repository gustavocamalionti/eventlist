/**
 *  CONSTANTES
 */
export const MSG_SUCCESS = "Operação realizada com sucesso!";
export const MSG_SUCCESS_TO_RESCUE_STORE = "Resgate suas recompensas em qualquer uma de nossas lojas participantes.";
export const MSG_SUCCESS_SAVED_REGISTER = "Registro salvo com sucesso !";
export const MSG_SUCCESS_DELETED = "Registro excluído com sucesso !";
export const MSG_SUCCESS_SEND_QUESTION = "Sua dúvida foi enviada com com sucesso! <BR>Em breve entraremos em contato.";
export const MSG_SUCCESS_USER_REGISTERED = "O seu cadastro foi realizado com sucesso !";

export const MSG_SUCCESS_USER_REGISTERED_VERIFY =
    "Cadastro concluído com sucesso! <BR> Verifique seu e-mail para confirmar.";
export const MSG_SUCCESS_PASSWORD_CHANGED = "A sua senha foi alterada com sucesso !";
export const MSG_SUCCESS_PASSWORD_CHANGED_UPDATE_DATA =
    "A sua senha foi alterada com sucesso ! <BR>Confira seus dados e atualize-os se for necessário.";
export const MSG_SUCCESS_TICKET_REGISTERED = "Código cadastrado com sucesso !";
export const MSG_SEND_LINK_RESET_PASSWORD =
    "Foi enviado um e-mail com o link para que seja efetuado o reset da sua senha, para o endereço: ";
export const MSG_ERROR_GENERAL = "Ocorreu uma falha inesperada, por favor tente mais tarde!";
export const MSG_ERROR_DELETING = "O registro não pôde ser apagado!<br>Por favor tente mais tarde!";
export const MSG_ERROR_SWAPS = "A Recompensa não pôde ser resgatada!<br> Por favor tente mais tarde!";
export const MSG_ERROR_ADD_VOUCHER =
    "Não é possível prosseguir para o pagamento se não foi adicionado nenhum participante.";
export const MSG_ERROR_ATEMPION_FIELDS =
    "Um ou mais campo(s) necessitam de sua atenção. <BR> Por favor revise os dados.";
export const MSG_ERROR_USER_INVALID = "Login e/ou senha inválido(s)!";
export const MSG_ERROR_USER_AUTHENTICATION = "Occorreu uma falha na autenticação do usuário !";
export const MSG_ERROR_PASSWORD_TOKEN = "Esse código de redefinição de senha expirou ou é inválido.";
export const MSG_ERROR_EMAIL_TOKEN =
    "O email digitado não foi encontrado ou é incopativel com o código de redefinição.";
export const MSG_ERROR_PASSWORD_USER = "Esse e-mail não consta em nossos cadastros.";
export const MSG_ERROR_RASPADINHA_NOVA_LOGADO = "Para cadastrar novos cupons, você precisa estar logado no sistema!";
export const MSG_ERROR_RASPADINHA_INEXISTENTE = "Código Promocional e/ou Código de Seguraça inválido(s)!";
export const MSG_ERROR_COUPON_NOT_FOUND = "Código Promocional Inválido!";
export const MSG_ERROR_RASPADINHA_JA_CADASTRADA = "Este cupom já foi utilizado por outro participante!";
export const MSG_ERROR_RASPADINHA_LIMITE_ATINGIDO = "Você já cadastrou o limite de cupons!<BR>Consulte o regulamento.";
export const MSG_ERROR_PASSWORD_NOT_CHANGED = "Não foi possível alterar a sua senha!";
export const MSG_ERROR_USER_NOT_REGISTERED = "Não foi possível realizar seu cadastro !";
export const MSG_ERROR_REGS_NOT_FOUND = "Nenhum registro foi encontrado !";
export const MSG_ERROR_RASPADINHA_BLOQUEADA = "Este cupom foi bloqueado pela empresa organizadora da promoção!";
export const MSG_ERROR_SEND_QUESTION = "Houve alguma falha ao enviar sua mensagem! <BR>Tente novamente mais tarde.";
export const MSG_ERROR_REWARDS_OUT_POINTS =
    "Infelizmente esse cliente não tem saldo o suficiente para concluir esse resgate!";
export const MSG_ERROR_BLACK_LIST =
    "Esse cliente está bloqueado para efetuar o resgate. <br>Para mais detalhes consulte a franqueadora.";
export const MSG_ERROR_TYPE_FILE_IMG = "Tipo de arquivo não suportado! Tente arquivos jpg ou png.";

/*****************************************************************************
 * MENSAGENS DIVERSAS
 *****************************************************************************/

/**
 * sweetalert - Modal geral e personalizavel
 */
export async function msgDefault(icon, title, msg, url, style) {
    let classBox = "popup_custom_success";
    let colorButton = "#2ecc71";
    if (style == 1) {
        classBox = "popup_custom_error";
        colorButton = "#c0392b";
    }
    swal.fire({
        title: '<span class="' + icon + '"></span> &nbsp' + title,
        html: msg,
        allowOutsideClick: false,
        confirmButtonColor: colorButton,
        customClass: {
            popup: classBox,
        },
    }).then(function () {
        if (url == "reload") {
            location.reload();
        } else if (url != "" && url != null) {
            //verify if is trigger or redirect location route.
            if (url.includes(";")) {
                var object = url.split(";")[0];
                var event = url.split(";")[1];
                $(object).trigger(event, true);
            } else {
                window.location.href = url;
            }
        }
    });
}

/**
 * sweetalert - Abrir o Modal de Loading
 */
export async function showMsgLoading() {
    let timerInterval;
    Swal.fire({
        title: "Estamos buscando suas informações!",
        html: "O alerta será fechado em alguns segundos.",
        customClass: "sweet-box",
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading();
        },
    });
}

/**
 * sweetalert - Fechar o Modal de Loading
 */
export async function closeMsgLoading() {
    swal.close();
}

/**
 * sweetalert - Modal de Sucesso
 */
export async function msgSuccess(title, msg) {
    swal.fire({
        title: title,
        html: msg,
        confirmButtonColor: "#c0392b",
        customClass: {
            popup: "popup_custom",
        },
    });
}

/**
 * sweetalert - Modal de Erro
 */
export async function msgError(title, msg) {
    swal.fire({
        title: title,
        html: msg,
        confirmButtonColor: "#e74c3c",
        customClass: {
            popup: "popup_custom_error",
        },
    });
}
