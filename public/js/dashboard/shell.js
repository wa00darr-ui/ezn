import { one, all } from "../shared/dom.js";
import { arabicNumber } from "./format.js";
import { toast } from "./feedback.js";
export function initializeShell() {
  let stateTimer;
  const drawerMedia = window.matchMedia("(max-width: 1000px)");
  function syncSidebar() {
    const sidebar = one(".sidebar");
    if (!sidebar) return;
    const open = document.body.classList.contains("nav-open");
    sidebar.inert = drawerMedia.matches && !open;
    one(".workspace").inert = drawerMedia.matches && open;
  }
  function closeSidebar() {
    const wasOpen = document.body.classList.contains("nav-open");
    document.body.classList.remove("nav-open");
    one(".sidebar-backdrop")?.setAttribute("hidden", "");
    one("[data-sidebar-toggle]")?.setAttribute("aria-expanded", "false");
    syncSidebar();
    if (wasOpen) one("[data-sidebar-toggle]")?.focus();
  }
  one("[data-sidebar-toggle]")?.addEventListener("click", () => {
    const open = document.body.classList.toggle("nav-open");
    one(".sidebar-backdrop").hidden = !open;
    one("[data-sidebar-toggle]").setAttribute("aria-expanded", String(open));
    syncSidebar();
    if (open) one(".sidebar nav a.active")?.focus();
  });
  all("[data-sidebar-close]").forEach((button) =>
    button.addEventListener("click", closeSidebar),
  );
  drawerMedia.addEventListener("change", () => {
    closeSidebar();
    syncSidebar();
  });
  syncSidebar();
  document.addEventListener("keydown", (event) => {
    if (event.key === "Escape") closeSidebar();
  });
  all("[data-open]").forEach((button) =>
    button.addEventListener("click", () =>
      one("#" + button.dataset.open).showModal(),
    ),
  );
  all("[data-close]").forEach((button) =>
    button.addEventListener("click", () => button.closest("dialog").close()),
  );
  all("dialog").forEach((dialog) =>
    dialog.addEventListener("click", (event) => {
      if (event.target !== dialog) return;
      const rect = dialog.getBoundingClientRect();
      if (
        event.clientX < rect.left ||
        event.clientX > rect.right ||
        event.clientY < rect.top ||
        event.clientY > rect.bottom
      )
        dialog.close();
    }),
  );
  document.addEventListener("click", (event) => {
    all(".dropdown[open]").forEach((dropdown) => {
      if (!dropdown.contains(event.target)) dropdown.open = false;
    });
  });
  function showPageState(state) {
    clearTimeout(stateTimer);
    const content = one("[data-page-content]");
    if (!content) return;
    content.hidden = state !== "ready";
    all("[data-page-state]").forEach((panel) => {
      panel.hidden = panel.dataset.pageState !== state;
    });
    if (state === "loading")
      stateTimer = setTimeout(() => showPageState("ready"), 1200);
  }
  all("[data-demo-state]").forEach((button) =>
    button.addEventListener("click", () =>
      showPageState(button.dataset.demoState),
    ),
  );
  all("[data-export]").forEach((button) =>
    button.addEventListener("click", () => toast("export")),
  );
  all("[data-save]").forEach((button) =>
    button.addEventListener("click", () => toast("saved")),
  );
  one("[data-new-request]")?.addEventListener("click", () => {
    one("[data-new-notice]").hidden = false;
    one(".nav-count").textContent = arabicNumber(9);
    one("tbody tr")?.classList.add("highlight-row");
    toast("new");
  });
  one("[data-dismiss-notice]")?.addEventListener("click", () => {
    one("[data-new-notice]").hidden = true;
  });
}
