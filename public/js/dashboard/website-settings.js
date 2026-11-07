import { one, all } from "../shared/dom.js";
import { validateForm, invalidField } from "./validation.js";
import { toast } from "./feedback.js";
export function initializeWebsiteSettings() {
  all("[data-image-input]").forEach((input) =>
    input.addEventListener("change", () => {
      const file = input.files[0];
      if (!file) return;
      if (
        !/^image\/(png|jpeg|webp|gif)$/.test(file.type) ||
        file.size > 5 * 1024 * 1024
      ) {
        input.value = "";
        invalidField(input);
        return;
      }
      const reader = new FileReader();
      reader.onload = () => {
        const preview = one("#" + input.dataset.imageInput);
        preview.src = String(reader.result);
        preview.hidden = false;
      };
      reader.readAsDataURL(file);
    }),
  );
  one("[data-add-instruction]")?.addEventListener("click", () => {
    const field = one("[data-extra-instruction][hidden]");
    if (!field) {
      toast("limit");
      return;
    }
    field.hidden = false;
    one("input", field).focus();
  });
  one("[data-cms-form]")?.addEventListener("submit", (event) => {
    event.preventDefault();
    if (validateForm(event.currentTarget)) toast("saved");
  });
  one("[data-settings-form]")?.addEventListener("submit", (event) => {
    event.preventDefault();
    if (validateForm(event.currentTarget)) toast("saved");
  });
  one("[data-preview-website]")?.addEventListener("click", () => {
    const form = one("[data-cms-form]");
    const bindings = {
      schoolName: "[data-preview-school]",
      heroTitle: "[data-preview-title]",
      heroDescription: "[data-preview-description]",
      cta: "[data-preview-cta]",
      aboutTitle: "[data-preview-about-title]",
      aboutDescription: "[data-preview-about-description]",
      phone: "[data-preview-phone]",
      whatsapp: "[data-preview-whatsapp]",
      address: "[data-preview-address]",
      hours: "[data-preview-hours]",
    };
    for (const name in bindings)
      one(bindings[name]).textContent = form.elements[name].value;
    one("[data-preview-image]").src = one("#hero-image").src;
    const logo = one("#school-logo");
    one("[data-preview-logo]").hidden = logo.hidden;
    if (!logo.hidden) one("[data-preview-logo]").src = logo.src;
    all("[data-preview-social]").forEach((output) => {
      output.textContent = form.elements[output.dataset.previewSocial].value;
      output.hidden = !output.textContent.trim();
    });
    all("[data-section-toggle]").forEach((input) => {
      one(
        '[data-preview-section="' + input.dataset.sectionToggle + '"]',
      ).hidden = !input.checked;
    });
    all(".instruction-fields input").forEach((input, index) => {
      const output = one('[data-preview-instruction="' + index + '"]');
      output.textContent = input.value;
      output.hidden = input.closest(".field").hidden || !input.value.trim();
    });
    one("#website-preview").showModal();
  });
}
