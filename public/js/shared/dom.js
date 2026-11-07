export const one = (selector, root = document) => root.querySelector(selector);
export const all = (selector, root = document) =>
  root.querySelectorAll(selector);
