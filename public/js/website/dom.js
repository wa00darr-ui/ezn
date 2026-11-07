import { all } from "../shared/dom.js";
export { one, all } from "../shared/dom.js";

export function bindValue(key, value) {
  all('[data-bind="' + key + '"]').forEach(function (element) {
    element.textContent = value;
  });
}

export function formatTime(timestamp) {
  return new Intl.DateTimeFormat("ar-SA", {
    calendar: "gregory",
    timeZone: "Asia/Riyadh",
    year: "numeric",
    month: "long",
    day: "numeric",
    hour: "numeric",
    minute: "2-digit",
  }).format(new Date(timestamp));
}
