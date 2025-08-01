import { Save } from "#common/js/utils/actions.js";
import { generateSlug, addPrefix } from "#common/js/utils/functions.js";

$(window).on("load", function () {
    // O nosso maintenance é usado tanto para salvar/atualizar, como também usado em modal de inserção em outros lugares da aplicação.
    // Dessa forma, é feito uma tratativa nos names e ids que indica qual é a origem da página maintenance. Caso ela tenha prefixo
    // indica obviamente que estamos em uma inserção do maintenance em modal.
    // Caso não tenha prefixo, significa que estamos na página de inserção/atualização do maintenance normal.
    // adicione o nome de todos os campos ou até de classes específicas abaixo, fazendo que haja a concatenação
    let prefixName = $("#prefixNameLinks").val();

    let [fieldFile, fieldName, fieldStoresId, fieldSlug, fieldIsFile, fieldFileClick, fieldLink, fieldIsLink] =
        addPrefix(prefixName, ["file", "name", "stores_id", "slug", "is_file", "file-click", "link", "is_link"]);

    ClearFile();
    SearchFile();
    DefineSlug();
    AlterFileAndLink();

    if ($("#name").val() != null) {
        $("#btnLinksSave").on("click", async function () {
            let response = await Save($("#linkForm"), $(this), true, [], [fieldFile]);

            // Se positivo e estamos usando o maintemance como modal, fecharemos ele
            if (response.status == 1 && prefixName == "modal-link-") {
                $("#modalNewItemLinks .btn-close").trigger("click");
            }
        });
    }
});

function ClearFile() {
    $("#btnClearFile").on("click", function (e) {
        if ($("#" + fieldFile).val() != null) {
            $("#" + fieldFile).val(null);
            $("#" + fieldName).val(null);
        }
    });
}

function SearchFile() {
    $("." + fieldFileClick).on("click", function (e) {
        $("#" + fieldFile).trigger("click");
    });
}

function DefineSlug() {
    $("#" + fieldStoresId).on("change", function () {
        var textToConvertSlug = "cardapio digital " + $("#" + fieldStoresId + " option:selected").text();
        // alert(textToConvertSlug);
        var result = generateSlug(textToConvertSlug);
        $("#" + fieldSlug).val(result);
    });
}

function AlterFileAndLink() {
    $("#" + fieldIsFile).on("change", function (e) {
        $("#divFile").show();
        $("#divLink").hide();
        $("#" + fieldLink).val(null);
    });

    $("#" + fieldIsLink).on("change", function (e) {
        $("#divLink").show();
        $("#divFile").hide();
        // $('#name').val(null);
        $("#" + fieldFile).val(null);
    });

    if ($("#id").val() == "") {
        $("#divFile").show();
        $("#divLink").hide();
    }

    $('input[type="file"][name="' + fieldFile + '"]').on("change", function () {
        var fileName = $('input[type="file"][name="' + fieldFile + '"]')[0].files[0].name;

        $("#" + fieldName).val(fileName);
    });
}
