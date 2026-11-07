import { one, all, bindValue, formatTime } from "./dom.js";
import { normalizeDigits } from "./validation.js";
import { toast } from "./feedback.js";
import {
  REQUEST_KEY,
  validRecord,
  changeRequestStatus,
} from "./mock-request.js";

export function initializeTracking() {
  let record = null,
    loadingTimer,
    refreshTimer;
  const lookup = one("#lookup-panel"),
    panel = one("#tracking-panel");
  const lookupForm = one("#lookup-form");
  const refresh = one('[data-action="refresh"]');
  try {
    const stored = JSON.parse(sessionStorage.getItem(REQUEST_KEY));
    if (validRecord(stored)) record = stored;
  } catch {
    /* A new demo lookup remains available. */
  }
  function save() {
    try {
      sessionStorage.setItem(REQUEST_KEY, JSON.stringify(record));
    } catch {
      /* Tracking remains available in this page. */
    }
  }
  function displayStatus() {
    all("[data-request-state]").forEach(function (article) {
      article.hidden = article.dataset.requestState !== record.status;
    });
    one("#tracking-loading").hidden = true;
    all("[data-status]").forEach(function (button) {
      button.classList.toggle(
        "selected",
        button.dataset.status === record.status,
      );
      button.setAttribute(
        "aria-pressed",
        String(button.dataset.status === record.status),
      );
    });
    bindValue("studentName", record.studentName);
    bindValue("requestNumber", record.requestNumber);
    bindValue("phone", record.phone);
    bindValue("permissionReason", record.permissionReason);
    bindValue("submittedAt", formatTime(record.submittedAt));
    if (record.decisionAt)
      bindValue("decisionAt", formatTime(record.decisionAt));
    if (record.completedAt)
      bindValue("completedAt", formatTime(record.completedAt));
  }
  function showTracking() {
    lookup.hidden = true;
    panel.hidden = false;
    displayStatus();
  }
  function refreshBusy(busy) {
    refresh.disabled = busy;
    one("[data-refresh-idle]").hidden = busy;
    one("[data-refresh-busy]").hidden = !busy;
  }
  lookupForm.querySelector('button[type="submit"]').disabled = false;
  lookupForm.addEventListener("submit", function (event) {
    event.preventDefault();
    const number = normalizeDigits(one("#request-number").value).toUpperCase();
    const phone = normalizeDigits(one("#lookup-phone").value);
    const matchesSaved =
      record && number === record.requestNumber && phone === record.phone;
    const matchesDemo =
      !record && number === "AF-2026-0482" && phone === "0500000000";
    one("#lookup-error").hidden = Boolean(matchesSaved || matchesDemo);
    if (!matchesSaved && !matchesDemo) return;
    if (!record)
      record = {
        requestNumber: "AF-2026-0482",
        studentName: "عبدالله محمد العتيبي",
        phone: "0500000000",
        permissionReason: "موعد طبي",
        submittedAt: Date.now(),
        status: "pending",
        decisionAt: null,
        completedAt: null,
      };
    save();
    showTracking();
    panel.scrollIntoView({ behavior: "smooth" });
  });
  all("[data-status]").forEach(function (button) {
    button.addEventListener("click", function () {
      clearTimeout(loadingTimer);
      clearTimeout(refreshTimer);
      refreshBusy(false);
      changeRequestStatus(record, button.dataset.status, Date.now());
      save();
      displayStatus();
      toast("status");
    });
  });
  one('[data-action="connection"]').addEventListener("click", function () {
    one("#connection-error").hidden = false;
  });
  one('[data-action="retry"]').addEventListener("click", function () {
    one("#connection-error").hidden = true;
    toast("retry");
  });
  one('[data-action="loading"]').addEventListener("click", function () {
    clearTimeout(loadingTimer);
    all("[data-request-state]").forEach(function (article) {
      article.hidden = true;
    });
    one("#tracking-loading").hidden = false;
    loadingTimer = setTimeout(displayStatus, 1300);
  });
  refresh.addEventListener("click", function () {
    refreshBusy(true);
    clearTimeout(refreshTimer);
    refreshTimer = setTimeout(function () {
      refreshBusy(false);
      one("#connection-error").hidden = true;
      toast("refresh");
    }, 700);
  });
  one('[data-action="new-request"]').addEventListener("click", function () {
    one("#reset-dialog").showModal();
  });
  one('[data-action="reset-request"]').addEventListener("click", function () {
    try {
      sessionStorage.removeItem(REQUEST_KEY);
    } catch {
      toast("storage");
      return;
    }
    window.location.href = document.body.dataset.requestUrl;
  });
  if (record) showTracking();
  else {
    lookup.hidden = false;
    panel.hidden = true;
  }
}
