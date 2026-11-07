import { one, all } from "../shared/dom.js";
import { validateForm, invalidField } from "./validation.js";
export function initializeAuth() {
  function authPanel(name) {
    all("[data-auth-panel]").forEach((panel) => {
      panel.hidden = panel.dataset.authPanel !== name;
    });
  }
  if (one("[data-login-form]")) {
    authPanel("login");
    all("[data-auth-target]").forEach((button) =>
      button.addEventListener("click", () =>
        authPanel(button.dataset.authTarget),
      ),
    );
    one("[data-password-toggle]").addEventListener("click", (event) => {
      const input = one('[name="password"]');
      const visible = input.type === "password";
      input.type = visible ? "text" : "password";
      event.currentTarget.setAttribute("aria-pressed", String(visible));
      one("[data-password-show]").hidden = visible;
      one("[data-password-hide]").hidden = !visible;
    });
    one("[data-login-form]").addEventListener("submit", (event) => {
      event.preventDefault();
      const form = event.currentTarget;
      if (!validateForm(form)) return;
      one('button[type="submit"]', form).disabled = true;
      one("[data-login-idle]").hidden = true;
      one("[data-login-busy]").hidden = false;
      setTimeout(() => {
        window.location.href = document.body.dataset.dashboardUrl;
      }, 650);
    });
    one("[data-forgot-form]").addEventListener("submit", (event) => {
      event.preventDefault();
      if (validateForm(event.currentTarget)) authPanel("sent");
    });
    one("[data-reset-form]").addEventListener("submit", (event) => {
      event.preventDefault();
      const form = event.currentTarget;
      if (!validateForm(form)) return;
      if (
        form.elements.newPassword.value !== form.elements.confirmPassword.value
      ) {
        invalidField(form.elements.confirmPassword);
        return;
      }
      form.reset();
      authPanel("reset-success");
    });
  }
}
