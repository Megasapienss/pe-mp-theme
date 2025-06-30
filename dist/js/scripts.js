/******/ (() => { // webpackBootstrap
/*!************************!*\
  !*** ./src/js/main.js ***!
  \************************/
// Main JavaScript file
document.addEventListener('DOMContentLoaded', function () {
  // Off-canvas menu toggle
  var offCanvasToggleButtons = document.querySelectorAll('.toggle--off-canvas--menu');
  var offCanvas = document.querySelector('.off-canvas--menu');
  if (offCanvasToggleButtons.length > 0 && offCanvas) {
    offCanvasToggleButtons.forEach(function (button) {
      button.addEventListener('click', function () {
        offCanvas.classList.toggle('off-canvas--visible');
      });
    });
  }
});
/******/ })()
;
//# sourceMappingURL=scripts.js.map