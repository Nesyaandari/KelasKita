// admin.js

// Load data event untuk admin
fetch("admin_load_event.php")
  .then(res => res.json())
  .then(data => {
    if (data.status === "success") {
      const eventSection = document.getElementById('event');
      eventSection.innerHTML = ''; // hapus placeholder
      data.events.forEach(ev => {
        const card = document.createElement('div');
        card.className = 'card';
        card.dataset.status = ev.status;
        card.innerHTML = `
          <img src="${ev.file_url}" alt="Poster"
          <div class="card-content">
            <h3>${ev.filename}</h3> <!-- tampilkan nama file -->
            <div class="status ${ev.status}">${ev.status}</div>
          </div>
          <div class="card-actions">
            <button class="approve" data-id="${ev.id}"><i class="fas fa-check"></i> Setujui</button>
            <button class="reject" data-id="${ev.id}"><i class="fas fa-times"></i> Tolak</button>
          </div>
        `;
        eventSection.appendChild(card);
      });

      // Event listener approve/reject
      document.querySelectorAll('.approve').forEach(btn => {
        btn.addEventListener('click', () => updateStatus(btn.dataset.id, 'approved'));
      });
      document.querySelectorAll('.reject').forEach(btn => {
        btn.addEventListener('click', () => updateStatus(btn.dataset.id, 'rejected'));
      });
    }
  });

function updateStatus(id, status) {
  const formData = new FormData();
  formData.append('id', id);
  formData.append('status', status);

  fetch('approve_event.php', {
    method: 'POST',
    body: formData
  })
    .then(res => res.json())
    .then(data => {
      if (data.status === "success") {
        alert("Status diperbarui!");
        location.reload();
      }
    });
}


// 3️⃣ Script filter dan navigasi menu
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

// 4️⃣ Fungsi untuk event approve/reject setelah load data
function initApproveReject() {
  document.querySelectorAll('.approve').forEach(btn => {
    btn.addEventListener('click', () => updateStatus(btn.dataset.id, 'approved'));
  });
  document.querySelectorAll('.reject').forEach(btn => {
    btn.addEventListener('click', () => updateStatus(btn.dataset.id, 'rejected'));
  });
}
