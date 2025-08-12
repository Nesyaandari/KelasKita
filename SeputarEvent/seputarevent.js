const input = document.getElementById('posterInput');
const gallery = document.getElementById('eventGallery');

// Fungsi untuk menambahkan poster ke galeri
function addPoster(filename) {
  const card = document.createElement('div');
  card.className = 'event-card';

  const img = document.createElement('img');
  img.src = "uploads/" + filename; // Pastikan path sesuai
  img.alt = "Poster Event";

  card.appendChild(img);
  gallery.appendChild(card);
}

// 📌 1. Saat halaman dibuka, load semua event dari database
fetch("load_event.php")
  .then(res => res.json())
  .then(data => {
    if (data.status === "success") {
      data.events.forEach(filename => addPoster(filename));
    }
  })
  .catch(err => console.error(err));

// 📌 2. Saat upload file baru
input.addEventListener('change', function () {
  const file = this.files[0];
  if (!file) return;

  // Preview di browser
  const reader = new FileReader();
  reader.onload = function (e) {
    const card = document.createElement('div');
    card.className = 'event-card';

    const img = document.createElement('img');
    img.src = e.target.result;
    img.alt = "Poster Event";

    card.appendChild(img);
    gallery.appendChild(card);
  };
  reader.readAsDataURL(file);

  // Kirim ke server PHP
  const formData = new FormData();
  formData.append("poster", file);

  fetch("upload_event.php", {
    method: "POST",
    body: formData
  })
    .then(res => res.json())
    .then(data => {
      if (data.status === "success") {
        addPoster(data.filename);
      }
    })
    .catch(err => console.error(err));
});
