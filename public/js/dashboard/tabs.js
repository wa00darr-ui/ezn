import { one, all } from "../shared/dom.js";
export function initializeTabs() {
  function activateTab(id, focus = false) {
    all("[data-tab]").forEach((button) => {
      const active = button.dataset.tab === id;
      button.setAttribute("aria-selected", String(active));
      button.tabIndex = active ? 0 : -1;
      one("#" + button.dataset.tab).hidden = !active;
      if (active && focus) button.focus();
    });
  }
  all("[data-tab]").forEach((button) => {
    button.addEventListener("click", () => activateTab(button.dataset.tab));
    button.addEventListener("keydown", (event) => {
      if (!["ArrowLeft", "ArrowRight", "Home", "End"].includes(event.key))
        return;
      event.preventDefault();
      const tabs = Array.from(all("[data-tab]"));
      const index = tabs.indexOf(button);
      const next =
        event.key === "Home"
          ? 0
          : event.key === "End"
            ? tabs.length - 1
            : (index + (event.key === "ArrowLeft" ? 1 : -1) + tabs.length) %
              tabs.length;
      activateTab(tabs[next].dataset.tab, true);
    });
  });
  if (one("[data-tab]")) activateTab(one("[data-tab]").dataset.tab);
}
