import { one, all } from "../shared/dom.js";
import { arabicNumber } from "./format.js";
export function initializeReports() {
  all("[data-period]").forEach((button) =>
    button.addEventListener("click", () => {
      all("[data-period]").forEach((item) =>
        item.classList.toggle("selected", item === button),
      );
      const form = one("[data-report-filters]");
      if (button.dataset.period !== "custom") {
        form.elements.to.value = "2026-10-04";
        form.elements.from.value =
          button.dataset.period === "today"
            ? "2026-10-04"
            : button.dataset.period === "week"
              ? "2026-09-28"
              : "2026-10-01";
      }
    }),
  );
  one("[data-report-filters]")?.addEventListener("submit", (event) => {
    event.preventDefault();
    const form = event.currentTarget;
    const invalidRange = form.elements.from.value > form.elements.to.value;
    one("[data-report-range-error]").hidden = !invalidRange;
    one("[data-report-note]").hidden = invalidRange;
    if (!invalidRange) applyReportFilters();
  });

  function applyReportFilters() {
    const form = one("[data-report-filters]");
    const student = form.elements.student.selectedOptions[0];
    const grade = form.elements.grade.selectedOptions[0];
    const period = one("[data-period].selected");
    const source = student.value ? student : grade.value ? grade : period;
    let total = Number(source.dataset.total),
      approved = Number(source.dataset.approved),
      rejected = Number(source.dataset.rejected),
      checkout = Number(source.dataset.checkout);
    const average = Number(source.dataset.average);
    const status = form.elements.status.value;
    if (status === "approved") {
      total = approved;
      rejected = 0;
    }
    if (status === "rejected") {
      total = rejected;
      approved = 0;
      checkout = 0;
    }
    if (status === "completed") {
      total = checkout;
      approved = checkout;
      rejected = 0;
    }
    if (status === "pending" || status === "new") {
      total = Number(
        source.dataset[status] || Math.max(0, total - approved - rejected),
      );
      approved = 0;
      rejected = 0;
      checkout = 0;
    }
    if (
      (student.value && grade.value && student.dataset.grade !== grade.value) ||
      (form.elements.from.value && form.elements.from.value > "2026-10-04") ||
      (form.elements.to.value && form.elements.to.value < "2026-09-28")
    ) {
      total = 0;
      approved = 0;
      rejected = 0;
      checkout = 0;
    }
    const values = {
      total,
      approved,
      rejected,
      checkout,
      average: total ? average : 0,
    };
    all("[data-report-value]").forEach((element) => {
      element.textContent = arabicNumber(values[element.dataset.reportValue]);
    });
  }
}
