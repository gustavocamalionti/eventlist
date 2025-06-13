/**
 * Importações de plugins
 */
import "inputmask/dist/jquery.inputmask.min.js";
import "jquery-ui/dist/jquery-ui.js";
import "bootstrap5-toggle/js/bootstrap5-toggle.jquery.js";

import "select2/dist/css/select2.css";
import select2 from "select2";
import "select2-bootstrap-5-theme/dist/select2-bootstrap-5-theme.css";

import "sweetalert2/dist/sweetalert2.css";
import "sweetalert2/dist/sweetalert2.all.js";

import "bootstrap-datepicker/dist/css/bootstrap-datepicker.css";
import "bootstrap-datepicker/dist/js/bootstrap-datepicker.js";
import "bootstrap-datepicker/js/locales/bootstrap-datepicker.pt-BR.js";

import "jquery-datetimepicker/build/jquery.datetimepicker.min.css";
import "jquery-datetimepicker/build/jquery.datetimepicker.full.js";

/**
 * Importa e inicializa o plugin debug dimension
 */
import { init as debugDimension } from "./plugins/debug_dimension/debug_dimension.js";

/**
 * Inicialização de plugins
 */

const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
const tooltipList = [...tooltipTriggerList].map((tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl));

select2();
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

$("[datetime]").datetimepicker({
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

//debugDimension('main');
