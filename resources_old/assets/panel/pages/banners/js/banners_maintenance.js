import { Save, filterCascade } from "#common/js/utils/actions.js";
import { MSG_ERROR_TYPE_FILE_IMG, msgError } from "#common/js/utils/messages.js";
import { removeErrorsAll } from "#common/js/utils/functions.js";

jQuery(function ($) {
    $(window).on("load", function () {
        CarregarFocoInput();
        CarregarDataInicialFinal();

        $("#btnSave").on("click", function () {
            Save($("#formPage"), $(this), true, [], ["file_desktop", "file_mobile"], null, "Salvar", "");
        });

        $(".file-desktop-click").on("click", function (e) {
            $("#file_desktop").trigger("click");
        });

        $(".file-mobile-click").on("click", function (e) {
            $("#file_mobile").trigger("click");
        });

        //Imagem preview file_mobile
        $('input[type="file"][name="file_desktop"]').on("change", function () {
            var img_path = $(this)[0].value;
            var img_default_desktop = $(".img-default-desktop");
            var extension = img_path.substring(img_path.lastIndexOf(".") + 1).toLowerCase();

            // alert(extension);

            if (extension == "jpg" || extension == "png") {
                if (typeof FileReader != "undefined") {
                    img_default_desktop.empty();
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        img_default_desktop.attr("src", e.target.result);
                    };
                    reader.readAsDataURL($(this)[0].files[0]);
                } else {
                    alert("Navegador não suporta o arquivo selecionado.");
                }
            } else {
                msgError("Error", MSG_ERROR_TYPE_FILE_IMG);
                $(img_default_desktop).empty();
            }
        });

        //Imagem preview file_desktop
        $('input[type="file"][name="file_mobile"]').on("change", function () {
            var img_path = $(this)[0].value;
            var img_default_mobile = $(".img-default-mobile");
            var extension = img_path.substring(img_path.lastIndexOf(".") + 1).toLowerCase();

            // alert(extension);

            if (extension == "jpg" || extension == "png") {
                if (typeof FileReader != "undefined") {
                    img_default_mobile.empty();
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        img_default_mobile.attr("src", e.target.result);
                    };
                    reader.readAsDataURL($(this)[0].files[0]);
                } else {
                    alert("Navegador não suporta o arquivo selecionado.");
                }
            } else {
                msgError("Error", MSG_ERROR_TYPE_FILE_IMG);
                $(img_default_mobile).empty();
            }
        });

        // Evento criado com o objetivo de limpar os campos do modal quando fechado (onde adicionamos novos itens ao select específicado)
        // Também atualizamos o select e selecionamos o novo item, caso ele tenha sido inserido
        const myModalEl = document.getElementById("modalNewItemLinks");
        myModalEl.addEventListener("hidden.bs.modal", async (event) => {
            $("#modal-link-stores_id").val("");
            $("#modal-link-stores_id").trigger("change");
            $("#modal-link-title").val(null);
            $("#modal-link-slug").val(null);
            $("#modal-link-name").val(null);
            $("#modal-link-file").val(null);
            $("#modal-link-link").val(null);

            $("#modal-link-is_file").prop("checked", true);
            $("#modal-link-is_link").prop("checked", false);
            $("#modal-link-is_file").trigger("change");
            removeErrorsAll();

            try {
                await filterCascade(0, true, $("#links_id"), "update-field-links", false, "Selecione...", "title");
            } catch (error) {
                console.log(error);
            }
        });
    });
});

function CarregarFocoInput() {
    $("#icon_date_start").on("click", function () {
        $("#date_start").trigger("focus");
    });

    $("#icon_time_start").on("click", function () {
        $("#time_start").trigger("focus");
    });
    $("#icon_date_end").on("click", function () {
        $("#date_end").trigger("focus");
    });

    $("#icon_time_end").on("click", function () {
        $("#time_end").trigger("focus");
    });
}

async function LimparDataHora() {
    $("#date_start").val("");
    $("#time_start").val("");
    $("#date_end").val("");
    $("#time_end").val("");
}

function HabilitarDataHora() {
    $(".divSchedule").removeClass("d-none");
    $("#date_start").prop("disabled", false);
    $("#time_start").prop("disabled", false);
    $("#date_end").prop("disabled", false);
    $("#time_end").prop("disabled", false);
}

function DesabilitarDataHora() {
    $(".divSchedule").addClass("d-none");
    $("#date_start").prop("disabled", true);
    $("#time_start").prop("disabled", true);
    $("#date_end").prop("disabled", true);
    $("#time_end").prop("disabled", true);
}

function CarregarDataInicialFinal() {
    //Agendamentos
    if ($("#is_schedule").is(":checked")) {
        HabilitarDataHora();
    } else {
        LimparDataHora();
        DesabilitarDataHora();
    }

    $("#is_schedule").on("change", function () {
        if (this.checked) {
            HabilitarDataHora();
        } else {
            LimparDataHora();
            DesabilitarDataHora();
        }
    });
}
