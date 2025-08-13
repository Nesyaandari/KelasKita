// seputarevent.js
document.addEventListener("DOMContentLoaded", function () {
    const fileInput = document.querySelector("input[name='poster']");
    const previewContainer = document.getElementById("eventGallery");

    if (fileInput) {
        fileInput.addEventListener("change", function () {
            const file = fileInput.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    // Hapus preview lama
                    const oldPreview = document.querySelector(".preview-card");
                    if (oldPreview) oldPreview.remove();

                    // Buat preview baru
                    const previewCard = document.createElement("div");
                    previewCard.classList.add("event-card", "preview-card");
                    previewCard.innerHTML = `
                        <img src="${e.target.result}" alt="Preview Poster" style="width:100%; border-radius:8px;">
                        <p>Status: pending</p>
                        <small>Menunggu upload...</small>
                    `;
                    previewContainer.prepend(previewCard);
                };
                reader.readAsDataURL(file);
            }
        });
    }
});
