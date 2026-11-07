import { one, all } from "../shared/dom.js";
export function initializeActivity() {
  all("[data-activity-details]").forEach((button) =>
    button.addEventListener("click", () => {
      const cells = button.closest("tr").cells;
      all("[data-activity-value]").forEach((value) => {
        value.textContent =
          cells[Number(value.dataset.activityValue)].textContent;
      });
      one("#activity-dialog").showModal();
    }),
  );
}
