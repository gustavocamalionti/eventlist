/*****************************************************************************
 * PRÉ LOADING PARA OS BOTÕES
 *****************************************************************************/
export function loadingBtnStart(btn) {
    btn.attr("disabled", true);
    btn.html("<span class='fas fa-sync fa-spin'></span> Aguarde...");
}

export function loadingBtnStop(btn, classIcon = "fas fa-check", caption = "Salvar") {
    btn.html("<span class='" + classIcon + " me-1'></span>" + caption);
    btn.removeAttr("disabled", false);
}

/*****************************************************************************
 * PRÉ LOADING PARA O BODY
 *****************************************************************************/
export function loadingBodyStart() {
    Swal.fire({
        title: "Aguarde, estamos processando!",
        html: "O alerta será fechado em alguns segundos.",
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        },
    });
}

export function loadingBodyStop() {
    swal.close();
}

/*****************************************************************************
 * PRÉ LOADING PARA OS SWEET ALERTS DE CONFIRMAÇÃO
 *****************************************************************************/
export function loadingSweetAlertStart() {
    $(".swal2-confirm").attr("disabled", true);
    $(".swal2-cancel").attr("disabled", true);
    $(".swal2-confirm").html('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Aguarde...');
}

export function loadingSweetAlertStop($caption) {
    $(".swal2-confirm").html('<span class="fas fa-check me-1"></span> ' + $caption);
    $(".swal2-confirm").removeAttr("disabled", false);
    $(".swal2-cancel").removeAttr("disabled", false);
}
