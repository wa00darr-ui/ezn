import { one, all } from "../shared/dom.js";
import { digits, arabicNumber } from "./format.js";
export function initializeTables() {
  const pagedTable = one("table[data-paged]");
  let tablePage = 1;
  function filterTable() {
    if (!pagedTable) return;
    const panel = pagedTable.closest(".panel");
    const search = digits(
      one("[data-search]", panel)?.value || "",
    ).toLowerCase();
    const matched = [];
    all("tbody [data-row]", pagedTable).forEach((row) => {
      let match =
        !row.hasAttribute("data-spare") &&
        digits(row.textContent).toLowerCase().includes(search);
      all("[data-filter]", panel).forEach((input) => {
        const value = input.value;
        if (!value) return;
        const key = input.dataset.filter;
        if (key === "from" && (!row.dataset.date || row.dataset.date < value))
          match = false;
        else if (
          key === "to" &&
          (!row.dataset.date || row.dataset.date > value)
        )
          match = false;
        else if (key !== "from" && key !== "to" && row.dataset[key] !== value)
          match = false;
      });
      row.hidden = true;
      if (match) matched.push(row);
    });
    const totalPages = Math.max(1, Math.ceil(matched.length / 6));
    tablePage = Math.min(tablePage, totalPages);
    const start = (tablePage - 1) * 6;
    matched.slice(start, start + 6).forEach((row) => {
      row.hidden = false;
    });
    one("[data-no-results]", panel).hidden = matched.length !== 0;
    one("[data-page-prev]", panel).disabled = tablePage === 1;
    one("[data-page-next]", panel).disabled = tablePage === totalPages;
    one("[data-page-number]", panel).textContent =
      arabicNumber(tablePage) + " / " + arabicNumber(totalPages);
    one("[data-page-summary]", panel).textContent =
      arabicNumber(matched.length ? start + 1 : 0) +
      "–" +
      arabicNumber(Math.min(start + 6, matched.length)) +
      " / " +
      arabicNumber(matched.length);
  }
  all("[data-filters]").forEach((form) => {
    form.addEventListener("submit", (event) => event.preventDefault());
    form.addEventListener("input", () => {
      tablePage = 1;
      filterTable();
    });
    form.addEventListener("change", () => {
      tablePage = 1;
      filterTable();
    });
  });
  all("[data-reset-filters]").forEach((button) =>
    button.addEventListener("click", () => {
      one("[data-filters]")?.reset();
      tablePage = 1;
      filterTable();
    }),
  );
  one("[data-page-prev]")?.addEventListener("click", () => {
    tablePage--;
    filterTable();
  });
  one("[data-page-next]")?.addEventListener("click", () => {
    tablePage++;
    filterTable();
  });
  filterTable();

  return filterTable;
}
