import { one, all } from "../shared/dom.js";
import { validateForm, resetEntityForm } from "./validation.js";
import { toast } from "./feedback.js";
export function initializeEntities(filterTable, recordPicker) {
  let editingRow = null,
    editingReason = null;
  function openEntity(kind, row = null) {
    const dialog = one("#" + kind + "-dialog");
    const form = one("form", dialog);
    resetEntityForm(form);
    editingRow = row;
    if (row)
      all("[name]", form).forEach((input) => {
        const cell = one('[data-cell="' + input.name + '"]', row);
        if (input.name === "status" || input.name === "role")
          input.value = row.dataset[input.name];
        else if (cell) input.value = cell.textContent.trim();
      });
    dialog.showModal();
  }
  one("[data-add-student]")?.addEventListener("click", () =>
    openEntity("student"),
  );
  one("[data-add-employee]")?.addEventListener("click", () =>
    openEntity("employee"),
  );
  all("[data-edit-student]").forEach((button) =>
    button.addEventListener("click", () =>
      openEntity("student", button.closest("tr")),
    ),
  );
  all("[data-edit-employee]").forEach((button) =>
    button.addEventListener("click", () =>
      openEntity("employee", button.closest("tr")),
    ),
  );
  one("[data-edit-profile]")?.addEventListener("click", () => {
    const form = one("#student-dialog form");
    resetEntityForm(form);
    all("[name]", form).forEach((input) => {
      const source = one('[data-record="' + input.name + '"]');
      if (source) input.value = source.textContent;
    });
    form.elements.status.value = one("[data-profile-active]").hidden
      ? "inactive"
      : "active";
    one("#student-dialog").showModal();
  });
  all("[data-entity-form]").forEach((form) =>
    form.addEventListener("submit", (event) => {
      event.preventDefault();
      if (!validateForm(form)) return;
      const kind = form.dataset.entityForm;
      if (kind === "reason") {
        const row = editingReason || one("[data-spare-reason]");
        if (!row) {
          toast("limit");
          return;
        }
        one("[data-reason-text]", row).textContent =
          form.elements.reasonName.value.trim();
        row.hidden = false;
        row.removeAttribute("data-spare-reason");
        updateReasonButtons();
      } else if (kind === "student-profile") {
        all("[name]", form).forEach((input) => {
          recordPicker.selectedOptions[0].dataset[
            input.name === "status" ? "studentStatus" : input.name
          ] = input.value;
        });
        all("[name]", form).forEach((input) =>
          all('[data-record="' + input.name + '"]').forEach((element) => {
            element.textContent = input.value;
          }),
        );
        one("[data-profile-active]").hidden =
          form.elements.status.value !== "active";
        one("[data-profile-inactive]").hidden =
          form.elements.status.value === "active";
      } else {
        const row = editingRow || one("tr[data-spare]");
        if (!row) {
          toast("limit");
          return;
        }
        all("[name]", form).forEach((input) => {
          const cell = one('[data-cell="' + input.name + '"]', row);
          if (cell)
            cell.textContent =
              input.tagName === "SELECT"
                ? input.selectedOptions[0].textContent
                : input.value.trim();
          if (["status", "grade", "classroom", "role"].includes(input.name))
            row.dataset[input.name] = input.value;
        });
        one("[data-active]", row).hidden = row.dataset.status !== "active";
        one("[data-inactive]", row).hidden = row.dataset.status === "active";
        row.removeAttribute("data-spare");
        row.hidden = false;
        filterTable();
      }
      form.closest("dialog").close();
      toast("saved");
    }),
  );
  one("[data-import-file]")?.addEventListener("change", (event) => {
    const file = event.target.files[0];
    const valid = Boolean(file && /\.(xlsx|xls)$/i.test(file.name));
    one("[data-import-preview]").hidden = !valid;
    one("[data-import-confirm]").disabled = !valid;
  });
  one("[data-import-confirm]")?.addEventListener("click", () => {
    one("#import-dialog").close();
    toast("imported");
  });

  function updateReasonButtons() {
    const rows = Array.from(all("[data-reason-row]")).filter(
      (row) => !row.hidden,
    );
    rows.forEach((row, index) => {
      one('[data-move-reason="up"]', row).disabled = index === 0;
      one('[data-move-reason="down"]', row).disabled =
        index === rows.length - 1;
    });
  }
  one("[data-add-reason]")?.addEventListener("click", () => {
    editingReason = null;
    const form = one("#reason-dialog form");
    resetEntityForm(form);
    one("#reason-dialog").showModal();
  });
  all("[data-edit-reason]").forEach((button) =>
    button.addEventListener("click", () => {
      editingReason = button.closest("[data-reason-row]");
      const form = one("#reason-dialog form");
      resetEntityForm(form);
      form.elements.reasonName.value = one(
        "[data-reason-text]",
        editingReason,
      ).textContent;
      one("#reason-dialog").showModal();
    }),
  );
  all("[data-toggle-reason]").forEach((button) =>
    button.addEventListener("click", () => {
      const row = button.closest("[data-reason-row]");
      const enabled = one("[data-reason-active]", row).hidden;
      one("[data-reason-active]", row).hidden = !enabled;
      one("[data-reason-inactive]", row).hidden = enabled;
      one("[data-disable-label]", row).hidden = !enabled;
      one("[data-enable-label]", row).hidden = enabled;
    }),
  );
  all("[data-move-reason]").forEach((button) =>
    button.addEventListener("click", () => {
      const row = button.closest("[data-reason-row]");
      const parent = row.parentElement;
      if (button.dataset.moveReason === "up") {
        const previous = row.previousElementSibling;
        if (previous && !previous.hidden) parent.insertBefore(row, previous);
      } else {
        const next = row.nextElementSibling;
        if (next && !next.hidden) parent.insertBefore(next, row);
      }
      updateReasonButtons();
    }),
  );
  updateReasonButtons();
}
