import { findCep, filterCascade, filterStoresCard, Save } from "#common/js/utils/actions.js";

/**
 *  Carrega combo de cities de acordo com a mudança do combo uf
 */
$("#states_id").on("change", async function () {
    await filterCascade($("#states_id").val(), null, $("#cities_id"), "cities", false, "Selecione...");

    await filterCascade(
        $("#states_id").val(),
        null,
        $("#stores_id"),
        "states-exist-stores",
        false,
        "Selecione uma loja",
    );

    await filterStoresCard("filter-stores-card", $("#states_id").val(), $("#cities_id").val(), $("#stores_id").val());
});

/**
 *  Carrega combo de stores de acordo com a mudança do combo de cities
 */
$("#cities_id").on("change", async function () {
    await filterCascade($("#cities_id").val(), null, $("#stores_id"), "stores", false, "Selecione...");

    if ($("#cities_id").val() == "") {
        await filterCascade($("#states_id").val(), null, $("#stores_id"), "states-exist-stores", false, "Selecione...");
    }

    await filterStoresCard("filter-stores-card", $("#states_id").val(), $("#cities_id").val(), $("#stores_id").val());
});

$("#stores_id").on("change", async function () {
    await filterStoresCard("filter-stores-card", $("#states_id").val(), $("#cities_id").val(), $("#stores_id").val());
});
