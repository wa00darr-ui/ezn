/* Dashboard-only behavior; primary markup stays in Blade. */
import { enableInteractions } from "../app.js";
import { initializeShell } from "./shell.js";
import { initializeValidation } from "./validation.js";
import { initializeTables } from "./tables.js";
import { initializeAuth } from "./auth.js";
import { initializeTabs } from "./tabs.js";
import { initializeRecords } from "./records.js";
import { initializeCheckout } from "./checkout.js";
import { initializeEntities } from "./entities.js";
import { initializeActivity } from "./activity.js";
import { initializeReports } from "./reports.js";
import { initializeWebsiteSettings } from "./website-settings.js";

if (document.body.dataset.frontend === "dashboard") {
  enableInteractions();
  initializeShell();
  const filterTable = initializeTables();
  initializeValidation();
  initializeAuth();
  initializeTabs();
  const recordPicker = initializeRecords();
  initializeCheckout();
  initializeEntities(filterTable, recordPicker);
  initializeActivity();
  initializeReports();
  initializeWebsiteSettings();
}
