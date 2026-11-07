import { one, all } from "../shared/dom.js";
let toastTimer;
export function toast(key) {
  const box = one(".toast");
  if (!box) return;
  all("[data-toast]", box).forEach((message) => {
    message.hidden = message.dataset.toast !== key;
  });
  box.classList.add("show");
  clearTimeout(toastTimer);
  toastTimer = setTimeout(() => box.classList.remove("show"), 3400);
}
