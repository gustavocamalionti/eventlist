import.meta.glob(["../images/**", "../fonts/**", "../videos/**"]);

$(".navbar-toggler").on("click", function () {
    const icon = $(this).find("i");
    icon.addClass("icon-rotate");
    setTimeout(() => {
        icon.removeClass("icon-rotate");
    }, 400);
});

$("#navbarSupportedContent").on("shown.bs.collapse", function () {
    const icon = $(".navbar-toggler i");
    if (!icon.hasClass("fa-times")) {
        icon.removeClass("fa-bars").addClass("fa-times");
    }
});

$("#navbarSupportedContent").on("hidden.bs.collapse", function () {
    const icon = $(".navbar-toggler i");
    if (!icon.hasClass("fa-bars")) {
        icon.removeClass("fa-times").addClass("fa-bars");
    }
});

// Fecha o menu ao clicar em um link
$(".navbar-nav a").on("click", function () {
    $("#navbarSupportedContent").removeClass("show");
    const icon = $(".navbar-toggler").find("i");
    icon.removeClass("fa-times").addClass("fa-bars");

    icon.addClass("icon-rotate");
    setTimeout(() => {
        icon.removeClass("icon-rotate");
    }, 400);
});
