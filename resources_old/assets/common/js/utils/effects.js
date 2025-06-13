effectsTransitions();

// Os efeitos to-bottom e to-top não funcionam. Rever e corrigir
export function effectsTransitions() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add("show");
                observer.unobserve(entry.target); // Evita reanimações desnecessárias
            }
        });
    });

    const elementsToObserve = document.querySelectorAll(
        ".effect-reveal-to-right, .effect-reveal-to-left, .effect-reveal-to-top, .effect-reveal-to-bottom, .effect-reveal-opacity",
    );

    elementsToObserve.forEach((el) => observer.observe(el));
}

// Calculo do Efeito Smooth
function getOffsetByScreenWidth() {
    // if (window.innerWidth < 768) {
    //     return 35;
    // } else if (window.innerWidth < 1200) {
    //     return 2;
    // } else {
    //     return 2;
    // }

    return 2;
}

// Efeito Smooth - Ao clicar em um item do menu, irá redirecionar para a secção respectiva de forma suave
$(".smooth").on("click", function (e) {
    e.preventDefault();
    const offset = getOffsetByScreenWidth();
    const target = $(this.getAttribute("href"));
    if (target.length) {
        $("html, body").animate(
            {
                scrollTop: target.offset().top - offset,
            },
            450,
        );
    }
});
