document.addEventListener('DOMContentLoaded', function() {
            const slides = document.getElementById('slides');
            const dotsContainer = document.getElementById('dots');
            const nextBtn = document.getElementById('nextBtn');
            const prevBtn = document.getElementById('prevBtn');
            const heroContent = document.getElementById('heroContent');
            const totalSlides = document.querySelectorAll('.slide').length;

            let currentIndex = 0;

            // Data konten untuk setiap slide
            const slideContents = [
                {
                    title: '<span class="red-text">Dirgahayu</span> Indonesia<br>ke-80',
                    subtitle: '80 tahun Indonesia merdeka, 80 tahun perjuangan dan pembangunan untuk masa depan yang lebih gemilang.',
                    description: 'Jiwa semangat kemerdekaan diwujudkan dalam bentuk inovasi teknologi!',
                    buttonText: 'Lihat Event',
                    buttonClass: 'btn-primary',
                    buttonAction: () => window.location.href = 'SeputarEvent/seputarevent.html'
                },
                {
                    title: 'Belajar <span class="blue-text">UI/UX Design</span><br>Modern',
                    subtitle: 'Kuasai seni mendesain antarmuka yang menarik dan pengalaman pengguna yang luar biasa.',
                    description: 'Dari wireframe hingga prototype, wujudkan ide kreatifmu menjadi desain yang memukau!',
                    buttonText: 'Mulai Belajar',
                    buttonClass: 'btn-secondary',
                    buttonAction: () => window.location.href = 'Materi/materi.html'
                },
                {
                    title: 'Pamerkan <span class="green-text">Website</span><br>Impianmu',
                    subtitle: 'Unggah karya kamu untuk branding bahwa kamu itu keren! .',
                    description: 'Tugas kuliah? proyek kuliah? atau sekedar ingin pamer? Upload website kamu di sini!',
                    buttonText: 'Pamerkan Sekarang!',
                    buttonClass: 'btn-success',
                    buttonAction: () => window.location.href = 'Branding/branding.html'
                }
            ];

            // Buat titik indikator sesuai jumlah slide
            for (let i = 0; i < totalSlides; i++) {
                const dot = document.createElement('span');
                dot.classList.add('dot');
                if (i === 0) dot.classList.add('active');
                dot.addEventListener('click', () => goToSlide(i));
                dotsContainer.appendChild(dot);
            }

            const dots = document.querySelectorAll('.dot');

            function updateContent() {
                const content = slideContents[currentIndex];
                heroContent.innerHTML = `
                    <h1 class="hero-title">${content.title}</h1>
                    <p class="hero-subtitle">${content.subtitle}</p>
                    <p class="hero-description">${content.description}</p>
                    <button class="hero-btn ${content.buttonClass}" onclick="slideContents[${currentIndex}].buttonAction()">${content.buttonText}</button>
                `;
            }

            function showSlide() {
                // Geser slides berdasarkan index saat ini
                slides.style.transform = `translateX(-${currentIndex * 33.333}%)`;
                
                // Update dot aktif
                dots.forEach(dot => dot.classList.remove('active'));
                dots[currentIndex].classList.add('active');
                
                // Update konten hero
                updateContent();
            }

            function goToSlide(i) {
                currentIndex = i;
                showSlide();
            }

            function nextSlide() {
                currentIndex = (currentIndex + 1) % totalSlides;
                showSlide();
            }

            function prevSlide() {
                currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
                showSlide();
            }

            // Event listeners untuk tombol navigasi
            nextBtn.addEventListener('click', nextSlide);
            prevBtn.addEventListener('click', prevSlide);

            // Keyboard navigation
            document.addEventListener('keydown', function(e) {
                if (e.key === 'ArrowRight') nextSlide();
                if (e.key === 'ArrowLeft') prevSlide();
            });

            // Auto-slide setiap 5 detik (opsional)
            setInterval(nextSlide, 5000);

            // Inisialisasi tampilan pertama
            showSlide();

            // Make slideContents globally accessible for button clicks
            window.slideContents = slideContents;
        });