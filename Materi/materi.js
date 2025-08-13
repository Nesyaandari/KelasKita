// Buka popup form upload
function openForm() {
    document.getElementById("formOverlay").style.display = "flex";
}

// Tutup popup form upload
function closeForm() {
    document.getElementById("formOverlay").style.display = "none";
}

// Search filter (optional, kalau mau hidupkan pencarian)
document.addEventListener("DOMContentLoaded", () => {
    const searchInput = document.querySelector(".search-input");
    const cards = document.querySelectorAll(".materi-grid .card");

    if (searchInput) {
        searchInput.addEventListener("input", () => {
            const keyword = searchInput.value.toLowerCase();
            cards.forEach(card => {
                const title = card.querySelector("h3").textContent.toLowerCase();
                card.style.display = title.includes(keyword) ? "block" : "none";
            });
        });
    }
});
