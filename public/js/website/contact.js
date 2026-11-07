import { one, all } from "./dom.js";
import { normalizeDigits, isPhone, clearError, error } from "./validation.js";
import { toast } from "./feedback.js";

export function initializeContact() {
  const form = one("#contact-form"),
    success = one("#contact-success");
  const button = one('button[type="submit"]', form);
  button.disabled = false;
  const controls = all("input, textarea", form);
  function contactBusy(busy) {
    form.setAttribute("aria-busy", String(busy));
    button.disabled = busy;
    controls.forEach(function (input) {
      input.disabled = busy;
    });
    one("[data-contact-idle]").hidden = busy;
    one("[data-contact-busy]").hidden = !busy;
  }
  form.addEventListener("submit", function (event) {
    event.preventDefault();
    if (button.disabled) return;
    let valid = true;
    controls.forEach(function (input) {
      clearError(input.id);
      if (!input.value.trim()) {
        error(input.id, "required");
        valid = false;
      } else if (input.maxLength > 0 && input.value.length > input.maxLength) {
        error(input.id, "length");
        valid = false;
      }
    });
    const phone = one("#contact-phone");
    phone.value = normalizeDigits(phone.value);
    if (phone.value && !isPhone(phone.value)) {
      error(phone.id, "phone");
      valid = false;
    }
    if (!valid) {
      one('[aria-invalid="true"]', form)?.focus();
      return;
    }
    contactBusy(true);
    setTimeout(function () {
      contactBusy(false);
      form.hidden = true;
      success.hidden = false;
      success.focus();
      toast("contact");
    }, 800);
  });
  one('[data-action="contact-new"]').addEventListener("click", function () {
    form.reset();
    controls.forEach(function (input) {
      clearError(input.id);
    });
    form.hidden = false;
    success.hidden = true;
    one("#contact-name").focus();
  });
}
