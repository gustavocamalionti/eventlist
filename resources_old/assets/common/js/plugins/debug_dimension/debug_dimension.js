/**
 * Debug dimensions browser
 */
let divElement = null;

function updateScreenSize() {
    const screenWidth = window.innerWidth;
    const screenHeight = window.innerHeight;
    let screenSizeDiv = document.getElementById("screen-size");
    if (!screenSizeDiv) {
        $(divElement).append(
            '<div id="screen-size" style="text-align: center;position: fixed;position: -webkit-sticky;top: 0;left: 0;width: 150px;height: 20px;background-color: rgb(224, 224, 224);font-size: 12px;z-index: 9999;font-family: arial, sans-serif;"></div>',
        );
    }

    screenSizeDiv = document.getElementById("screen-size");
    if (screenSizeDiv) {
        screenSizeDiv.innerHTML = `W: ${screenWidth}px, H: ${screenHeight}px`;
    }
}

export function init(elemento) {
    divElement = elemento;
    window.addEventListener("resize", updateScreenSize);
    window.addEventListener("load", updateScreenSize);
}
