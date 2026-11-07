import { one } from "./dom.js";

export function initializeNavigation() {
  const menu = one(".menu");
  menu.addEventListener("click", function () {
    const expanded = one("#nav").classList.toggle("open");
    menu.setAttribute("aria-expanded", String(expanded));
  });
  document.addEventListener("keydown", function (event) {
    if (event.key === "Escape") {
      one("#nav").classList.remove("open");
      menu.setAttribute("aria-expanded", "false");
    }
  });
}
