import { one, all } from "../shared/dom.js";
import { arabicNumber, timeNow } from "./format.js";
import { validateForm } from "./validation.js";
import { toast } from "./feedback.js";
export function initializeRecords() {
  const recordPicker = one("[data-record-picker]");
  let decision = "pending";
  const rejectionDefault = one("[data-rejection-output]")?.textContent;
  function selectRecord() {
    if (!recordPicker) return;
    const option = recordPicker.selectedOptions[0];
    all("[data-record]").forEach((element) => {
      const value = option.dataset[element.dataset.record];
      if (value !== undefined) element.textContent = value;
    });
    // all("[data-request-number]").forEach((element) => {
    //   element.textContent = option.value;
    // });
    if (recordPicker.dataset.recordPicker === "student") {
      all("[data-profile-history]").forEach((row) => {
        row.hidden = row.dataset.profileHistory !== option.value;
      });
      all("[data-profile-stat]").forEach((value) => {
        value.textContent = arabicNumber(
          Number(option.dataset[value.dataset.profileStat]),
        );
      });
      one("[data-profile-active]").hidden =
        option.dataset.studentStatus !== "active";
      one("[data-profile-inactive]").hidden =
        option.dataset.studentStatus === "active";
    }
    if (recordPicker.dataset.recordPicker === "request") {
      one("[data-student-link]").href = option.dataset.studentUrl;
      decision = option.dataset.status;
      if (decision === "rejected")
        one("[data-rejection-output]").textContent =
          option.dataset.rejection || rejectionDefault;
      updateDecision(false);
    }
  }
  function updateDecision(updated) {
    all("[data-decision-badge]").forEach((badge) => {
      badge.hidden = badge.dataset.decisionBadge !== decision;
    });
    all("[data-decision-result]").forEach((panel) => {
      panel.hidden = panel.dataset.decisionResult !== decision;
    });
    if (one("[data-decision-actions]"))
      one("[data-decision-actions]").hidden = decision !== "pending";
    one("[data-decision-timeline]")?.classList.toggle(
      "done",
      decision !== "pending",
    );
    if (one("[data-timeline-wait]")) {
      one("[data-timeline-wait]").hidden = decision !== "pending";
      one("[data-timeline-approved]").hidden = ![
        "approved",
        "completed",
      ].includes(decision);
      one("[data-timeline-rejected]").hidden = decision !== "rejected";
      one("[data-checkout-timeline]").classList.toggle(
        "done",
        decision === "completed",
      );
      one("[data-checkout-wait]").hidden = ["completed", "rejected"].includes(
        decision,
      );
      one("[data-checkout-rejected]").hidden = decision !== "rejected";
      one("[data-checkout-done]").hidden = decision !== "completed";
      const time = one("[data-decision-time]");
      time.hidden = decision === "pending";
      if (decision !== "pending")
        time.textContent = updated
          ? timeNow()
          : recordPicker.selectedOptions[0].dataset.decisionAt;
    }
  }
  if (recordPicker) {
    const params = new URLSearchParams(window.location.search);
    const value = params.get(recordPicker.dataset.recordPicker);
    if (
      value &&
      Array.from(recordPicker.options).some((option) => option.value === value)
    )
      recordPicker.value = value;
    recordPicker.addEventListener("change", selectRecord);
    selectRecord();
  }
  one("[data-approve]")?.addEventListener("click", () => {
    decision = "approved";
    recordPicker.selectedOptions[0].dataset.status = decision;
    recordPicker.selectedOptions[0].dataset.decisionAt = timeNow();
    updateDecision(true);
    one("#approve-dialog").close();
    toast("approved");
  });
  one("[data-reject-form]")?.addEventListener("submit", (event) => {
    event.preventDefault();
    if (!validateForm(event.currentTarget)) return;
    one("[data-rejection-output]").textContent =
      event.currentTarget.elements.rejection.value.trim();
    decision = "rejected";
    recordPicker.selectedOptions[0].dataset.status = decision;
    recordPicker.selectedOptions[0].dataset.decisionAt = timeNow();
    recordPicker.selectedOptions[0].dataset.rejection =
      event.currentTarget.elements.rejection.value.trim();
    updateDecision(true);
    one("#reject-dialog").close();
    toast("rejected");
  });

  return recordPicker;
}
