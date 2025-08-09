 
        document.addEventListener('DOMContentLoaded', function() {
            const slides = document.getElementById('slides');
            const dotsContainer = document.getElementById('dots');
            const nextBtn = document.getElementById('nextBtn');
            const prevBtn = document.getElementById('prevBtn');
            const totalSlides = document.querySelectorAll('.slide').length;

            let currentIndex = 0;

            // Buat titik indikator sesuai jumlah slide
            for (let i = 0; i < totalSlides; i++) {
                const dot = document.createElement('span');
                dot.classList.add('dot');
                if (i === 0) dot.classList.add('active');
                dot.addEventListener('click', () => goToSlide(i));
                dotsContainer.appendChild(dot);
            }

            const dots = document.querySelectorAll('.dot');

            function showSlide() {
                // Geser slides berdasarkan index saat ini
                slides.style.transform = `translateX(-${currentIndex * 33.333}%)`;
                
                // Update dot aktif
                dots.forEach(dot => dot.classList.remove('active'));
                dots[currentIndex].classList.add('active');
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

            // Keyboard navigation (opsional)
            document.addEventListener('keydown', function(e) {
                if (e.key === 'ArrowRight') nextSlide();
                if (e.key === 'ArrowLeft') prevSlide();
            });

            // Inisialisasi tampilan pertama
            showSlide();
        });