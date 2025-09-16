@extends('landing.layouts.app')

@section('title', 'HobiTracker - Kelola Hobi, Raih Potensi Maksimal Anda')

@section('content')
    <main class="main">

        <!-- Hero Section -->
        <section id="home" class="hero section light-background">

            <div class="container">
                <div class="row gy-4">
                    <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center" data-aos="zoom-out">
                        <h1 id="hero-title">Kelola Hobimu dengan HobiTracker</h1>
                        <p id="hero-subtitle">Dari menambah hobi hingga memantau progres target. Catat aktivitas,
                            visualisasikan data, dan raih pencapaian maksimal dengan platform lengkap ini.</p>
                        <div class="d-flex">
                            <a href="/login" class="btn-get-started" id="cta-button">Get Started</a>
                        </div>
                    </div>
                </div>
            </div>

        </section><!-- /Hero Section -->

        <!-- About Section -->
        <section id="about" class="about section light-background">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>Mengapa HobiTracker?</h2>
                <p><span>Lebih Dari Sekadar Pelacakan</span> <span class="description-title">Ini Mesin Pertumbuhan Pribadi
                        Anda</span></p>
            </div><!-- End Section Title -->

            <div class="container">

                <div class="row gy-3">

                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                        <img src="landing/img/about.jpg" alt="" class="img-fluid interactive-image">
                    </div>

                    <div class="col-lg-6 d-flex flex-column justify-content-center" data-aos="fade-up" data-aos-delay="200">
                        <div class="about-content ps-0 ps-lg-3">
                            <h3>Dari Hobi Sederhana hingga Pencapaian Maksimal</h3>
                            <p class="fst-italic">
                                HobiTracker adalah platform lengkap untuk mengelola hobi Anda dari registrasi hingga laporan
                                ekspor. Mulai tambah hobi, buat aktivitas, catat log, tetapkan target, dan visualisasikan
                                progres.
                            </p>
                            <ul class="interactive-list">
                                <li>
                                    <i class="bi bi-clock-history"></i>
                                    <div>
                                        <h4>Manajemen Waktu</h4>
                                        <p>Temukan waktu optimal untuk aktivitas berdasarkan analisis data log harian Anda.
                                        </p>
                                    </div>
                                </li>
                                <li>
                                    <i class="bi bi-graph-up"></i>
                                    <div>
                                        <h4>Visualisasi Progres</h4>
                                        <p>Saksikan evolusi hobi melalui grafik dan tabel progres target serta aktivitas.
                                        </p>
                                    </div>
                                </li>
                                <li>
                                    <i class="bi bi-people"></i>
                                    <div>
                                        <h4>Wawasan Komunitas</h4>
                                        <p>Bandingkan progres anonim, dapat inspirasi dari cerita pengguna lain, temukan
                                            partner akuntabilitas.</p>
                                    </div>
                                </li>
                            </ul>
                            <p>
                                Optimalkan setiap aktivitas untuk peningkatan terukur. Sistem memproses data menjadi
                                visualisasi dan laporan ekspor untuk evaluasi pribadi Anda.
                            </p>
                        </div>

                    </div>
                </div>

            </div>

        </section><!-- /About Section -->

        <!-- Featured Services Section -->
        <section id="featured-services" class="featured-services section">

            <div class="container">

                <div class="row gy-4">

                    <div class="col-xl-3 col-md-6 d-flex" data-aos="fade-up" data-aos-delay="100">
                        <div class="service-item position-relative interactive-card">
                            <div class="icon"><i class="bi bi-journal-text icon"></i></div>
                            <h4><a href="" class="stretched-link">Kelola Hobi & Aktivitas</a></h4>
                            <p>Tambahkan hobi, buat aktivitas spesifik, dan catat log harian dengan bukti untuk riwayat
                                lengkap.</p>
                        </div>
                    </div><!-- End Service Item -->

                    <div class="col-xl-3 col-md-6 d-flex" data-aos="fade-up" data-aos-delay="200">
                        <div class="service-item position-relative interactive-card">
                            <div class="icon"><i class="bi bi-graph-up-arrow icon"></i></div>
                            <h4><a href="" class="stretched-link">Dasbor Analitik & Visualisasi</a></h4>
                            <p>Grafik interaktif aktivitas, target, dan progres hobi Anda. Filter harian, mingguan, atau
                                bulanan untuk analisis mendalam.</p>
                        </div>
                    </div><!-- End Service Item -->

                    <div class="col-xl-3 col-md-6 d-flex" data-aos="fade-up" data-aos-delay="300">
                        <div class="service-item position-relative interactive-card">
                            <div class="icon"><i class="bi bi-trophy icon"></i></div>
                            <h4><a href="" class="stretched-link">Sistem Target & Pencapaian</a></h4>
                            <p>Tetapkan target hobi dengan deadline, lacak progres, dan raih milestone serta lencana untuk
                                setiap pencapaian.</p>
                        </div>
                    </div><!-- End Service Item -->

                    <div class="col-xl-3 col-md-6 d-flex" data-aos="fade-up" data-aos-delay="400">
                        <div class="service-item position-relative interactive-card">
                            <div class="icon"><i class="bi bi-lightbulb icon"></i></div>
                            <h4><a href="" class="stretched-link">Rekomendasi Pintar</a></h4>
                            <p>Sistem memberikan saran waktu optimal dan rekomendasi hobi baru berdasarkan pola aktivitas
                                Anda.</p>
                        </div>
                    </div><!-- End Service Item -->

                </div>

            </div>

        </section><!-- /Featured Services Section -->

        <section id="stats" class="stats section">
            <div class="decorative-element top-left"></div>
            <div class="decorative-element bottom-right"></div>

            <div class="container">
                <div class="section-title text-center mb-5">
                    <h2>Statistik Mengagumkan Kami</h2>
                    <p>Temukan angka-angka mengesankan yang menunjukkan passion dan dedikasi komunitas kami terhadap hobi
                    </p>
                </div>

                <div class="row gy-4">
                    <div class="col-lg-3 col-md-6">
                        <div
                            class="stats-item d-flex flex-column justify-content-center align-items-center text-center p-4 bg-light rounded shadow-sm h-100">
                            <div class="icon-circle d-flex justify-content-center align-items-center mb-3 rounded-circle"
                                style="width: 60px; height: 60px; min-width: 60px; min-height: 60px; background-color: #000; flex-shrink: 0; aspect-ratio: 1 / 1;">
                                <i class="fas fa-clock stats-icon" style="font-size: 1.5rem; color: #fff;"></i>
                            </div>
                            <span class="display-5 fw-bold">15000</span>
                            <p class="mb-0">Jam Aktivitas yang Dicatat</p>
                        </div>
                    </div><!-- End Stats Item -->

                    <div class="col-lg-3 col-md-6">
                        <div
                            class="stats-item d-flex flex-column justify-content-center align-items-center text-center p-4 bg-light rounded shadow-sm h-100">
                            <div class="icon-circle d-flex justify-content-center align-items-center mb-3 rounded-circle"
                                style="width: 60px; height: 60px; min-width: 60px; min-height: 60px; background-color: #000; flex-shrink: 0; aspect-ratio: 1 / 1;">
                                <i class="fas fa-users stats-icon" style="font-size: 1.5rem; color: #fff;"></i>
                            </div>
                            <span class="display-5 fw-bold">850</span>
                            <p class="mb-0">Hobiis Aktif</p>
                        </div>
                    </div><!-- End Stats Item -->

                    <div class="col-lg-3 col-md-6">
                        <div
                            class="stats-item d-flex flex-column justify-content-center align-items-center text-center p-4 bg-light rounded shadow-sm h-100">
                            <div class="icon-circle d-flex justify-content-center align-items-center mb-3 rounded-circle"
                                style="width: 60px; height: 60px; min-width: 60px; min-height: 60px; background-color: #000; flex-shrink: 0; aspect-ratio: 1 / 1;">
                                <i class="fas fa-th-large stats-icon" style="font-size: 1.5rem; color: #fff;"></i>
                            </div>
                            <span class="display-5 fw-bold">47</span>
                            <p class="mb-0">Kategori Hobi</p>
                        </div>
                    </div><!-- End Stats Item -->

                    <div class="col-lg-3 col-md-6">
                        <div
                            class="stats-item d-flex flex-column justify-content-center align-items-center text-center p-4 bg-light rounded shadow-sm h-100">
                            <div class="icon-circle d-flex justify-content-center align-items-center mb-3 rounded-circle"
                                style="width: 60px; height: 60px; min-width: 60px; min-height: 60px; background-color: #000; flex-shrink: 0; aspect-ratio: 1 / 1;">
                                <i class="fas fa-heart stats-icon" style="font-size: 1.5rem; color: #fff;"></i>
                            </div>
                            <span class="display-5 fw-bold">98%</span>
                            <p class="mb-0">Kepuasan Hobiis</p>
                        </div>
                    </div><!-- End Stats Item -->
                </div>
            </div>
        </section>

        <!-- Testimonials Section -->
        <section id="testimonials" class="testimonials section light-background">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>Cerita Sukses</h2>
                <p><span>Apa Kata</span> <span class="description-title">Master Hobi Kami</span></p>
            </div><!-- End Section Title -->

            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <div class="swiper init-swiper">
                    <script type="application/json" class="swiper-config">
            {
              "loop": true,
              "speed": 600,
              "autoplay": {
                "delay": 5000
              },
              "slidesPerView": "auto",
              "pagination": {
                "el": ".swiper-pagination",
                "type": "bullets",
                "clickable": true
              }
            }
          </script>
                    <div class="swiper-wrapper">

                        <div class="swiper-slide">
                            <div class="testimonial-item interactive-testimonial">
                                <img src="landing/img/testimonials/testimonials-1.jpg" class="testimonial-img"
                                    alt="">
                                <h3>Ridwan Pratama</h3>
                                <h4>Book Lover & Writer</h4>
                                <div class="stars interactive-stars">
                                    <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                        class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                        class="bi bi-star-fill"></i>
                                </div>
                                <p>
                                    <i class="bi bi-quote quote-icon-left"></i>
                                    <span>Dengan HobiTracker, saya berhasil membaca 73 buku tahun ini - 3x lipat dari tahun
                                        lalu! Analytics menunjukkan waktu optimal membaca saya adalah jam 6-8 pagi. Game
                                        changer!</span>
                                    <i class="bi bi-quote quote-icon-right"></i>
                                </p>
                            </div>
                        </div><!-- End testimonial item -->

                        <div class="swiper-slide">
                            <div class="testimonial-item interactive-testimonial">
                                <img src="landing/img/testimonials/testimonials-2.jpg" class="testimonial-img"
                                    alt="">
                                <h3>Sarah Chen</h3>
                                <h4>Gaming Enthusiast</h4>
                                <div class="stars interactive-stars">
                                    <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                        class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                        class="bi bi-star-fill"></i>
                                </div>
                                <p>
                                    <i class="bi bi-quote quote-icon-left"></i>
                                    <span>Data gaming saya menunjukkan pola menarik - performa terbaik di competitive games
                                        adalah hari Selasa-Kamis. Sekarang saya schedule ranked matches pada waktu tersebut
                                        dan rank naik drastis!</span>
                                    <i class="bi bi-quote quote-icon-right"></i>
                                </p>
                            </div>
                        </div><!-- End testimonial item -->

                        <div class="swiper-slide">
                            <div class="testimonial-item interactive-testimonial">
                                <img src="landing/img/testimonials/testimonials-3.jpg" class="testimonial-img"
                                    alt="">
                                <h3>Maya Putri</h3>
                                <h4>Cycling & Fitness</h4>
                                <div class="stars interactive-stars">
                                    <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                        class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                        class="bi bi-star-fill"></i>
                                </div>
                                <p>
                                    <i class="bi bi-quote quote-icon-left"></i>
                                    <span>Sistem kategori hobi (fisik, kreatif, intelektual) membantu saya menyeimbangkan
                                        aktivitas. Log cycling dan target mingguan membuat konsistensi saya meningkat
                                        drastis!</span>
                                    <i class="bi bi-quote quote-icon-right"></i>
                                </p>
                            </div>
                        </div><!-- End testimonial item -->

                    </div>
                    <div class="swiper-pagination"></div>
                </div>

            </div>

        </section><!-- /Testimonials Section -->

        <!-- Contact Section -->
        <section id="contact" class="contact section">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>Mari Berkolaborasi</h2>
                <p><span>Punya Ide?</span> <span class="description-title">Kami Ingin Mendengarnya</span></p>
            </div><!-- End Section Title -->

            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <div class="row gy-4">

                    <div class="col-lg-5">

                        <div class="info-wrap">
                            <div class="info-item d-flex interactive-info" data-aos="fade-up" data-aos-delay="200">
                                <i class="bi bi-geo-alt flex-shrink-0"></i>
                                <div>
                                    <h3>Pusat Inovasi</h3>
                                    <p>Jl. Rokan Kiri, Bowongan, Ds.Arjowinangun, Pacitan, Jawa Timur</p>
                                </div>
                            </div><!-- End Info Item -->

                            <div class="info-item d-flex interactive-info" data-aos="fade-up" data-aos-delay="300">
                                <i class="bi bi-whatsapp flex-shrink-0"></i>
                                <div>
                                    <h3>Dukungan WhatsApp</h3>
                                    <p>+62 831 8983 1452</p>
                                </div>
                            </div><!-- End Info Item -->

                            <div class="info-item d-flex interactive-info" data-aos="fade-up" data-aos-delay="400">
                                <i class="bi bi-envelope flex-shrink-0"></i>
                                <div>
                                    <h3>Email</h3>
                                    <p>hello@hobitracker.id</p>
                                </div>
                            </div><!-- End Info Item -->

                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3949.0290416250837!2d111.11473407432916!3d-8.199828582245933!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e79610455fd896b%3A0x1c2840d84ff48092!2sSentana%20Teknologi!5e0!3m2!1sid!2sid!4v1757907294724!5m2!1sid!2sid"
                                style="border:0; width: 100%; height: 270px;" allowfullscreen="" loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade">
                            </iframe>

                        </div>
                    </div>

                    <div class="col-lg-7">
                        <form action="forms/contact.php" method="post" class="php-email-form interactive-form"
                            data-aos="fade-up" data-aos-delay="200">
                            <div class="row gy-4">

                                <div class="col-md-6">
                                    <label for="name-field" class="pb-2">Nama Lengkap</label>
                                    <input type="text" name="name" id="name-field"
                                        class="form-control interactive-input" required=""
                                        placeholder="Masukkan nama Anda">
                                </div>

                                <div class="col-md-6">
                                    <label for="email-field" class="pb-2">Alamat Email</label>
                                    <input type="email" class="form-control interactive-input" name="email"
                                        id="email-field" required="" placeholder="email@anda.com">
                                </div>

                                <div class="col-md-12">
                                    <label for="subject-field" class="pb-2">Topik Pesan</label>
                                    <select class="form-control interactive-input" name="subject" id="subject-field"
                                        required="">
                                        <option value="">Pilih topik...</option>
                                        <option value="feature-request">Permintaan Fitur Baru</option>
                                        <option value="bug-report">Laporan Bug</option>
                                        <option value="partnership">Kolaborasi & Kemitraan</option>
                                        <option value="general">Pertanyaan Umum</option>
                                    </select>
                                </div>

                                <div class="col-md-12">
                                    <label for="message-field" class="pb-2">Detail Pesan</label>
                                    <textarea class="form-control interactive-input" name="message" rows="6" id="message-field" required=""
                                        placeholder="Bagikan ide, saran, atau pertanyaan Anda secara detail..."></textarea>
                                </div>

                                <div class="col-md-12 text-center">
                                    <div class="loading">Mengirim pesan...</div>
                                    <div class="error-message"></div>
                                    <div class="sent-message">Pesan berhasil dikirim! Tim kami akan merespons dalam 24 jam.
                                    </div>

                                    <button type="submit" class="interactive-submit">Kirim Pesan</button>
                                </div>

                            </div>
                        </form>
                    </div><!-- End Contact Form -->

                </div>

            </div>

        </section><!-- /Contact Section -->

    </main>

@endsection
