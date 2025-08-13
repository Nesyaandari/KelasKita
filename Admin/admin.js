// ===== Load Data Materi =====
function loadMateri() {
  fetch("admin.php?action=load_materi")
    .then(res => res.json())
    .then(data => {
      const materiSection = document.getElementById('materi');
      materiSection.innerHTML = '';

      if (data.status === "success") {
        data.materi.forEach(m => {
          const card = document.createElement('div');
          card.className = 'card';
          card.dataset.status = m.status;

          // Preview file jika ada file_url
          let previewHTML = `<span style="color:red;">File tidak ditemukan</span>`;
          if (m.file_url) {
            const ext = m.file.split('.').pop().toLowerCase();
            if (['pdf'].includes(ext)) {
              previewHTML = `<iframe src="${m.file_url}" width="100%" height="300"></iframe>`;
            } else if (['jpg','jpeg','png','gif','webp'].includes(ext)) {
              previewHTML = `<img src="${m.file_url}" alt="${m.title}" style="max-width:100%; height:auto;">`;
            } else {
              previewHTML = `<a href="${m.file_url}" target="_blank">Lihat / Download File</a>`;
            }
          }

          card.innerHTML = `
            <div class="card-content">
              <h3>${m.title}</h3>
              <p>Uploader: ${m.uploader}</p>
              ${previewHTML}
              <div class="status ${m.status}">${m.status}</div>
            </div>
            <div class="card-actions">
              <button class="approve" data-id="${m.id}"><i class="fas fa-check"></i> Setujui</button>
              <button class="reject" data-id="${m.id}"><i class="fas fa-times"></i> Tolak</button>
            </div>
          `;
          materiSection.appendChild(card);
        });
        initApproveRejectMateri();
      }
    });
}



// ===== Event Listener Approve/Reject Materi =====
function initApproveRejectMateri() {
  document.querySelectorAll('#materi .approve').forEach(btn => {
    btn.addEventListener('click', () => updateStatusMateri(btn.dataset.id, 'approved'));
  });
  document.querySelectorAll('#materi .reject').forEach(btn => {
    btn.addEventListener('click', () => updateStatusMateri(btn.dataset.id, 'rejected'));
  });
}

// ===== Update Status Materi =====
function updateStatusMateri(id, status) {
  const formData = new FormData();
  formData.append('action', 'update_materi');
  formData.append('id', id);
  formData.append('status', status);

  fetch('admin.php', {
    method: 'POST',
    body: formData
  })
    .then(res => res.json())
    .then(data => {
      if (data.status === "success") {
        alert("Status materi diperbarui!");
        loadMateri();
      }
    });
}


// ===== Load Data Branding =====
function loadBranding() {
  fetch("admin.php?action=load_branding")
    .then(res => res.json())
    .then(data => {
      const brandingSection = document.getElementById('branding');
      brandingSection.innerHTML = '';
      if (data.status === "success") {
        data.branding.forEach(b => {
          const card = document.createElement('div');
          card.className = 'card';
          card.dataset.status = b.status;
          card.innerHTML = `
            <img src="${b.image_path}" alt="Gambar Branding">
            <div class="card-content">
              <h3>${b.title}</h3>
              <p>${b.description}</p>
              <div class="status ${b.status}">${b.status}</div>
            </div>
            <div class="card-actions">
              <button class="approve-branding" data-id="${b.id}"><i class="fas fa-check"></i> Setujui</button>
              <button class="reject-branding" data-id="${b.id}"><i class="fas fa-times"></i> Tolak</button>
            </div>
          `;
          brandingSection.appendChild(card);
        });
        initApproveRejectBranding();
      }
    });
}

// ===== Update Status Branding =====
function updateStatusBranding(id, status) {
  const formData = new FormData();
  formData.append('action', 'update_branding');
  formData.append('id', id);
  formData.append('status', status);

  fetch('admin.php', {
    method: 'POST',
    body: formData
  })
    .then(res => res.json())
    .then(data => {
      if (data.status === "success") {
        alert("Status branding diperbarui!");
        loadBranding();
      }
    });
}

// ===== Event Listener Approve/Reject Branding =====
function initApproveRejectBranding() {
  document.querySelectorAll('.approve-branding').forEach(btn => {
    btn.addEventListener('click', () => updateStatusBranding(btn.dataset.id, 'approved'));
  });
  document.querySelectorAll('.reject-branding').forEach(btn => {
    btn.addEventListener('click', () => updateStatusBranding(btn.dataset.id, 'rejected'));
  });
}



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
    } else if (sectionId === 'branding') {
      loadBranding();
    }
    else if (sectionId === 'materi') {
      loadMateri();
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
