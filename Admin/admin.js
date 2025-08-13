// ===== Load Data Event =====
function loadEvents() {
  fetch("admin.php?action=load")
    .then(res => res.json())
    .then(data => {
      const eventSection = document.getElementById('event');
      eventSection.innerHTML = '';
      if (data.status === "success") {
        data.events.forEach(ev => {
          const card = document.createElement('div');
          card.className = 'card';
          card.dataset.status = ev.status;
          card.innerHTML = `
            <img src="${ev.file_url}" alt="Poster">
            <div class="card-content">
              <h3>${ev.filename}</h3>
              <div class="status ${ev.status}">${ev.status}</div>
            </div>
            <div class="card-actions">
              <button class="approve" data-id="${ev.id}"><i class="fas fa-check"></i> Setujui</button>
              <button class="reject" data-id="${ev.id}"><i class="fas fa-times"></i> Tolak</button>
            </div>
          `;
          eventSection.appendChild(card);
        });
        initApproveReject();
      }
    });
}

// ===== Update Status Event =====
function updateStatus(id, status) {
  const formData = new FormData();
  formData.append('action', 'update');
  formData.append('id', id);
  formData.append('status', status);

  fetch('admin.php', {
    method: 'POST',
    body: formData
  })
    .then(res => res.json())
    .then(data => {
      if (data.status === "success") {
        alert("Status diperbarui!");
        loadEvents();
      }
    });
}

// ===== Event Listener Approve/Reject =====
function initApproveReject() {
  document.querySelectorAll('.approve').forEach(btn => {
    btn.addEventListener('click', () => updateStatus(btn.dataset.id, 'approved'));
  });
  document.querySelectorAll('.reject').forEach(btn => {
    btn.addEventListener('click', () => updateStatus(btn.dataset.id, 'rejected'));
  });
}

// ===== Navigasi Menu =====
const menuItems = document.querySelectorAll('.menu-item');
const sections = document.querySelectorAll('.upload-section');
const sectionTitle = document.getElementById('sectionTitle');

menuItems.forEach(item => {
  item.addEventListener('click', () => {
    menuItems.forEach(i => i.classList.remove('active'));
    item.classList.add('active');
    const sectionId = item.getAttribute('data-section');
    sections.forEach(sec => sec.style.display = 'none');
    const targetSection = document.getElementById(sectionId);
    targetSection.style.display = 'block';
    sectionTitle.textContent = item.textContent.trim();
    if (sectionId === 'event') {
      loadEvents();
    }
  });
});

// ===== Filter Status =====
document.getElementById('statusFilter').addEventListener('change', (e) => {
  const value = e.target.value;
  document.querySelectorAll('.card').forEach(card => {
    card.style.display = (value === 'all' || card.dataset.status === value) ? 'flex' : 'none';
  });
});

// ===== Auto Load Event Saat Pertama Dibuka =====
loadEvents();
