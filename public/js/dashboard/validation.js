import { one, all } from "../shared/dom.js";
import { digits } from "./format.js";
export function clearField(input) {
  input.removeAttribute("aria-invalid");
  const error = one(
    ".field-error",
    input.closest(".field") || input.parentElement,
  );
  if (error) error.hidden = true;
}
export function invalidField(input) {
  input.setAttribute("aria-invalid", "true");
  const error = one(
    ".field-error",
    input.closest(".field") || input.parentElement,
  );
  if (error) error.hidden = false;
}
export function validateForm(form) {
  let valid = true;
  all("input,textarea,select", form).forEach((input) => {
    if (input.disabled || input.type === "file" || input.type === "checkbox")
      return;
    clearField(input);
    if (input.type === "tel" || input.name === "nationalId")
      input.value = digits(input.value);
    let fieldValid = input.checkValidity();
    if (input.minLength > 0 && input.value.length < input.minLength)
      fieldValid = false;
    if (input.required && !input.value.trim()) fieldValid = false;
    if (input.name === "nationalId" && !/^[12]\d{9}$/.test(input.value))
      fieldValid = false;
    if (
      input.type === "tel" &&
      input.required &&
      !/^05\d{8}$/.test(input.value)
    )
      fieldValid = false;
    if (input.type === "number" && Number(input.value) < 1) fieldValid = false;
    if (!fieldValid) {
      invalidField(input);
      valid = false;
    }
  });
  if (!valid) one('[aria-invalid="true"]', form)?.focus();
  return valid;
}
export function initializeValidation() {
  all("input,textarea,select").forEach((input) =>
    input.addEventListener("input", () => clearField(input)),
  );
}
export function resetEntityForm(form) {
  form.reset();
  all("[aria-invalid]", form).forEach(clearField);
}
