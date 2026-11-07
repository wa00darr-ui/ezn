import { isPhone } from "./validation.js";

export const REQUEST_KEY = "afaq-parent-mock-request";

export function changeRequestStatus(record, status, now) {
  if (!["pending", "approved", "rejected", "completed"].includes(status))
    return;
  record.status = status;
  if (status === "pending") {
    record.decisionAt = null;
    record.completedAt = null;
  }
  if (status === "approved" || status === "rejected") {
    record.decisionAt = now;
    record.completedAt = null;
  }
  if (status === "completed") {
    record.decisionAt = record.decisionAt || now;
    record.completedAt = now;
  }
}

export function validRecord(record) {
  return Boolean(
    record &&
    record.requestNumber === "AF-2026-0482" &&
    typeof record.studentName === "string" &&
    record.studentName.length <= 100 &&
    typeof record.permissionReason === "string" &&
    record.permissionReason.length <= 500 &&
    isPhone(record.phone) &&
    Number.isFinite(record.submittedAt) &&
    ["pending", "approved", "rejected", "completed"].includes(record.status) &&
    (record.decisionAt === null || Number.isFinite(record.decisionAt)) &&
    (record.completedAt === null || Number.isFinite(record.completedAt)),
  );
}
