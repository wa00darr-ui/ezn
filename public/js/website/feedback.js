import { one, all } from "./dom.js";

let toastTimer;
export function toast(key) {
  const box = one("#toast");
  all("[data-toast]", box).forEach(function (message) {
    message.hidden = message.dataset.toast !== key;
  });
  box.classList.add("show");
  clearTimeout(toastTimer);
  toastTimer = setTimeout(function () {
    box.classList.remove("show");
  }, 3200);
}

export function initializeDialogs() {
  all('[data-action="privacy"]').forEach(function (button) {
    button.addEventListener("click", function () {
      one("#privacy-dialog").showModal();
    });
  });
  all("[data-close-dialog]").forEach(function (button) {
    button.addEventListener("click", function () {
      button.closest("dialog").close();
    });
  });
  all("dialog").forEach(function (dialog) {
    dialog.addEventListener("click", function (event) {
      if (event.target !== dialog) return;
      const rect = dialog.getBoundingClientRect();
      if (
        event.clientX < rect.left ||
        event.clientX > rect.right ||
        event.clientY < rect.top ||
        event.clientY > rect.bottom
      )
        dialog.close();
    });
  });
}
