import { enableInteractions } from "../app.js";

import { one } from "./dom.js";
import { initializeNavigation } from "./navigation.js";
import { initializeDialogs } from "./feedback.js";
import { initializeValidation } from "./validation.js";
import { initializeRequest } from "./request-form.js";
import { initializeTracking } from "./tracking.js";
import { initializeContact } from "./contact.js";

if (document.body.dataset.frontend === "website") {
    enableInteractions();

    initializeValidation();

    initializeNavigation();

    initializeDialogs();

    if (one("#request-form")) {
        initializeRequest();
    }

    if (one("#lookup-form")) {
        initializeTracking();
    }

    if (one("#contact-form")) {
        initializeContact();
    }
}
