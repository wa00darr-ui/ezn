import { one } from "../shared/dom.js";
import { digits, timeNow } from "./format.js";
import { clearField, invalidField } from "./validation.js";
import { toast } from "./feedback.js";
export function initializeCheckout() {
  let pickupUsed = false;
  function verifyPickup() {
    if (pickupUsed) return;
    one("[data-checkout-placeholder]").hidden = true;
    one("[data-checkout-result]").hidden = false;
    one("[data-checkout-result]").scrollIntoView({
      behavior: "smooth",
      block: "start",
    });
  }
  one("[data-scan]")?.addEventListener("click", verifyPickup);
  one("[data-code-form]")?.addEventListener("submit", (event) => {
    event.preventDefault();
    const input = event.currentTarget.elements.pickupCode;
    input.value = digits(input.value);
    clearField(input);
    if (pickupUsed || input.value !== "482915") {
      invalidField(input);
      return;
    }
    verifyPickup();
  });
  one("[data-identity-match]")?.addEventListener("change", (event) => {
    one("[data-checkout-confirm]").disabled = !event.target.checked;
  });
  one("[data-complete-checkout]")?.addEventListener("click", () => {
    if (!one("[data-identity-match]").checked || pickupUsed) return;
    pickupUsed = true;
    one("#checkout-dialog").close();
    one(".checkout-layout").hidden = true;
    one("[data-checkout-success]").hidden = false;
    one("[data-checkout-time]").textContent = timeNow();
    toast("checkout");
  });
  one("[data-checkout-reset]")?.addEventListener("click", () => {
    pickupUsed = false;
    one(".checkout-layout").hidden = false;
    one("[data-checkout-success]").hidden = true;
    one("[data-checkout-placeholder]").hidden = false;
    one("[data-checkout-result]").hidden = true;
    one("[data-code-form]").reset();
    one("[data-identity-match]").checked = false;
    one("[data-checkout-confirm]").disabled = true;
  });
}
