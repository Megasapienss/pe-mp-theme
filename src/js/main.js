// Main JavaScript file
document.addEventListener('DOMContentLoaded', function () {

    // Off-canvas menu toggle
    const offCanvasToggleButtons = document.querySelectorAll('.toggle--off-canvas--menu');
    const offCanvas = document.querySelector('.off-canvas--menu');

    if (offCanvasToggleButtons.length > 0 && offCanvas) {
        offCanvasToggleButtons.forEach(button => {
            button.addEventListener('click', () => {
                offCanvas.classList.toggle('off-canvas--visible');
            });
        });
    }
});