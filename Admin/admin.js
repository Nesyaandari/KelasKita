const filterSelect = document.getElementById('statusFilter');
const menuItems = document.querySelectorAll('.menu-item');
const sections = document.querySelectorAll('.upload-section');
const sectionTitle = document.getElementById('sectionTitle');

// Filter status
filterSelect.addEventListener('change', () => {
  const value = filterSelect.value;
  document.querySelectorAll('.card').forEach(card => {
    card.style.display = (value === 'all' || card.dataset.status === value) ? 'flex' : 'none';
  });
});

// Navigasi menu
menuItems.forEach(item => {
  item.addEventListener('click', () => {
    menuItems.forEach(i => i.classList.remove('active'));
    item.classList.add('active');
    const sectionId = item.getAttribute('data-section');

    sections.forEach(sec => {
      sec.style.display = 'none';
      sec.classList.remove('fadeIn');
    });

    const targetSection = document.getElementById(sectionId);
    targetSection.style.display = 'block';
    targetSection.classList.add('fadeIn');

    sectionTitle.textContent = item.textContent.trim();
  });
});

// Approve / Reject
document.querySelectorAll('.approve').forEach(btn => {
  btn.addEventListener('click', () => {
    const card = btn.closest('.card');
    card.dataset.status = 'approved';
    card.querySelector('.status').textContent = 'Disetujui';
    card.querySelector('.status').className = 'status approved';
  });
});
document.querySelectorAll('.reject').forEach(btn => {
  btn.addEventListener('click', () => {
    const card = btn.closest('.card');
    card.dataset.status = 'rejected';
    card.querySelector('.status').textContent = 'Ditolak';
    card.querySelector('.status').className = 'status rejected';
  });
});
