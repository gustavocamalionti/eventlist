import { effectsTransitions } from "#common/js/utils/effects.js";

export function CallComponents(dataTable = null) {
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    const tooltipList = [...tooltipTriggerList].map((tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl));

    const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]');
    const popoverList = [...popoverTriggerList].map((popoverTriggerEl) => {
        const popover = new bootstrap.Popover(popoverTriggerEl, {
            trigger: "focus", // ou "click", se preferir
        });

        // Adiciona o comportamento de fechar os outros popovers ao clicar
        popoverTriggerEl.addEventListener("click", function () {
            popoverTriggerList.forEach((otherEl) => {
                if (otherEl !== popoverTriggerEl) {
                    bootstrap.Popover.getInstance(otherEl)?.hide();
                }
            });
        });

        return popover;
    });

    $(".select2").select2({
        theme: "bootstrap-5",
    });

    $("[date]").datepicker({
        language: "pt-BR",
        format: "dd/mm/yyyy",
        autoclose: true,
        todayHighlight: true,
    });

    $("[time]").datetimepicker({
        datepicker: false,
        format: "H:i",
        step: 5,
    });

    jQuery("[datetime]").datetimepicker({
        locale: "pt-br",
        format: "d/m/Y H:i:s",
    });

    $(".input-daterange").datepicker({
        format: "mm/yyyy",
        startView: 1,
        minViewMode: 1,
        language: "pt-BR",
        autoclose: true,
    });

    effectsTransitions();
}
