import { one, all } from "./dom.js";

export function normalizeDigits(value) {
  return String(value)
    .replace(/[٠-٩]/g, function (digit) {
      return String("٠١٢٣٤٥٦٧٨٩".indexOf(digit));
    })
    .replace(/[۰-۹]/g, function (digit) {
      return String("۰۱۲۳۴۵۶۷۸۹".indexOf(digit));
    })
    .trim();
}

export function isPhone(value) {
  return /^05\d{8}$/.test(normalizeDigits(value));
}

export function isIdentity(value) {
  return /^[12]\d{9}$/.test(normalizeDigits(value));
}

export function clearError(id) {
  all('[data-error-for="' + id + '"]').forEach(function (message) {
    message.hidden = true;
  });
  const input = document.getElementById(id);
  if (input) {
    input.classList.remove("invalid");
    input.removeAttribute("aria-invalid");
    input.removeAttribute("aria-describedby");
  }
}

export function error(id, kind) {
  clearError(id);
  const message = one(
    '[data-error-for="' + id + '"][data-error-kind="' + kind + '"]',
  );
  if (!message) return;
  message.hidden = false;
  message.id = "error-" + id;
  const input = document.getElementById(id);
  if (input) {
    input.classList.add("invalid");
    input.setAttribute("aria-invalid", "true");
    input.setAttribute("aria-describedby", message.id);
  }
}

export function initializeValidation() {
  all("input, select, textarea").forEach(function (input) {
    input.addEventListener("input", function () {
      clearError(input.id);
    });
  });
}
