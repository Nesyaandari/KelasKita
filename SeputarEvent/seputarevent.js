 const input = document.getElementById('posterInput');
    const gallery = document.getElementById('eventGallery');

    input.addEventListener('change', function () {
      const file = this.files[0];
      if (!file) return;

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
    });