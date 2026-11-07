import { one } from "./dom.js";
import { clearError, error } from "./validation.js";

export function createIdentityUpload() {
  const field = (id) => document.getElementById(id);
  let idImage = "",
    uploading = false,
    uploadVersion = 0,
    uploadTimer;
  function uploadDisplay() {
    one("#upload-prompt").hidden = uploading || Boolean(idImage);
    one("#upload-loading").hidden = !uploading;
    one("#identity-preview").hidden = uploading || !idImage;
    if (idImage) one("#identity-image").src = idImage;
  }
  one('[data-action="remove-image"]').addEventListener("click", function () {
    uploadVersion++;
    clearTimeout(uploadTimer);
    uploading = false;
    idImage = "";
    field("identity").value = "";
    uploadDisplay();
    clearError("identity");
  });
  field("identity").addEventListener("change", function () {
    const file = field("identity").files[0];
    if (!file) return;
    const version = ++uploadVersion;
    clearTimeout(uploadTimer);
    clearError("identity");
    uploading = false;
    if (
      !["image/jpeg", "image/png", "image/webp"].includes(file.type) ||
      file.size > 5 * 1024 * 1024
    ) {
      error("identity", "file");
      field("identity").value = "";
      uploadDisplay();
      return;
    }
    uploading = true;
    uploadDisplay();
    function failure() {
      if (version !== uploadVersion) return;
      uploading = false;
      field("identity").value = "";
      uploadDisplay();
      error("identity", "read");
    }
    const reader = new FileReader();
    reader.onerror = failure;
    reader.onload = function () {
      if (version !== uploadVersion) return;
      const image = new Image();
      image.onerror = failure;
      image.onload = function () {
        uploadTimer = setTimeout(function () {
          if (version !== uploadVersion) return;
          idImage = String(reader.result);
          uploading = false;
          uploadDisplay();
          clearError("identity");
        }, 650);
      };
      image.src = String(reader.result);
    };
    reader.readAsDataURL(file);
  });

  return {
    get image() {
      return idImage;
    },
    get uploading() {
      return uploading;
    },
    useMock() {
      uploadVersion++;
      clearTimeout(uploadTimer);
      uploading = false;
      idImage = document.body.dataset.mockIdentity;
      uploadDisplay();
      clearError("identity");
    },
  };
}
