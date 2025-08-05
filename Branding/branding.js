// Toggle aktif tombol filter
const buttons = document.querySelectorAll('.category-btn');
const cards = document.querySelectorAll('.project-card');

buttons.forEach(button => {
  button.addEventListener('click', () => {
    document.querySelector('.category-btn.active').classList.remove('active');
    button.classList.add('active');

    const category = button.getAttribute('data-category');

    cards.forEach(card => {
      const cardCategory = card.getAttribute('data-category');
      if (category === 'all' || cardCategory === category) {
        card.style.display = 'block';
      } else {
        card.style.display = 'none';
      }
    });
  });
});


// Modal Logic
const modal = document.getElementById('uploadModal');
const btn = document.getElementById('uploadBtn');
const span = document.querySelector('.close');

btn.onclick = () => modal.style.display = 'block';
span.onclick = () => modal.style.display = 'none';
window.onclick = (e) => {
  if (e.target === modal) modal.style.display = 'none';
};

// Submit Form
document.getElementById('projectForm').addEventListener('submit', function (e) {
  e.preventDefault();

  const imageInput = document.getElementById('projectImage');
  const title = document.getElementById('projectTitle').value;
  const desc = document.getElementById('projectDesc').value;
  const category = document.getElementById('projectCategory').value;

  const reader = new FileReader();
  reader.onload = function (e) {
    const imageSrc = e.target.result;

    const newCard = document.createElement('div');
    newCard.className = 'project-card';
    newCard.setAttribute('data-category', category);
    newCard.innerHTML = `
      <img src="${imageSrc}" alt="${title}" />
      <h3>${title}</h3>
      <p>${desc}</p>
    `;

    document.querySelector('.project-grid').appendChild(newCard);
    modal.style.display = 'none';
    document.getElementById('projectForm').reset();
  };

  if (imageInput.files[0]) {
    reader.readAsDataURL(imageInput.files[0]);
  }
});
