export const digits = (value) =>
  String(value)
    .replace(/[٠-٩]/g, (digit) => String("٠١٢٣٤٥٦٧٨٩".indexOf(digit)))
    .trim();
export const arabicNumber = (value) =>
  new Intl.NumberFormat("ar-SA").format(value);
const demoStartedAt = Date.now();
export const timeNow = () =>
  new Intl.DateTimeFormat("ar-SA", {
    calendar: "gregory",
    timeZone: "Asia/Riyadh",
    dateStyle: "medium",
    timeStyle: "short",
  }).format(
    new Date(
      Date.parse(document.body.dataset.demoClock) + Date.now() - demoStartedAt,
    ),
  );
