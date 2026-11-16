<?php
require_once __DIR__ . '/config.php';

// Susun produk berdasarkan kategori secara otomatis.
$categories = [];
foreach ($PRODUCTS as $product) {
    $key = $product['category'];
    if (!isset($categories[$key])) {
        $categories[$key] = [
            'label' => $product['category_label'],
            'products' => []
        ];
    }
    $categories[$key]['products'][] = $product;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KDKMP Bakulan | Etalase Produk</title>
    <meta name="description" content="Etalase produk KDKMP Bakulan. Belanja kebutuhan harian dengan mudah.">
    <link rel="stylesheet" href="assets/style.css?v=10">
</head>
<body>

<div class="topbar">KDKMP BAKULAN • Belanja dekat, tumbuh bersama</div>

<header class="site-header">
    <a class="brand" href="index.php">
        <img
            src="assets/logo-kdkmp.png"
            alt="Logo KDKMP Bakulan"
            class="brand-logo"
        >

        <div class="brand-text">
            <strong>KDKMP <span>BAKULAN</span></strong>
            <small>Koperasi Desa/Kelurahan Merah Putih</small>
        </div>
    </a>

    <a class="header-cta" href="#etalase">
        Lihat Produk <span>→</span>
    </a>
</header>

<main>
    <section class="hero">
        <div class="slides">
            <article class="slide active slide-red">
                <div class="hero-copy">
                    <span class="eyebrow">SELAMAT DATANG DI KDKMP BAKULAN</span>
                    <h1>Kebutuhan harian<br><em>lebih mudah</em> didapatkan.</h1>
                    <p>Temukan produk pilihan dengan harga bersahabat untuk keluarga dan lingkungan sekitar.</p>
                    <a href="#etalase" class="hero-btn">Jelajahi Produk <b>→</b></a>
                </div>
                <div class="hero-visual"><span>🛍️</span><b>Belanja<br>Hemat!</b></div>
            </article>
            <article class="slide slide-orange">
                <div class="hero-copy">
                    <span class="eyebrow">DUKUNG PRODUK LOKAL</span>
                    <h1>Produk UMKM<br><em>naik kelas</em> bersama.</h1>
                    <p>Mari tumbuh bersama dengan mengenal dan memasarkan produk unggulan masyarakat.</p>
                    <a href="#kemitraan" class="hero-btn">Gabung Kemitraan <b>→</b></a>
                </div>
                <div class="hero-visual"><span>🌾</span><b>Produk<br>Lokal</b></div>
            </article>
            <article class="slide slide-dark">
                <div class="hero-copy">
                    <span class="eyebrow">KDKMP BAKULAN</span>
                    <h1>Dari warga,<br><em>untuk warga.</em></h1>
                    <p>Mendukung distribusi kebutuhan pokok dan menggerakkan ekonomi desa.</p>
                    <a href="#etalase" class="hero-btn">Lihat Etalase <b>→</b></a>
                </div>
                <div class="hero-visual"><span>🤝</span><b>Tumbuh<br>Bersama</b></div>
            </article>
        </div>
        <button class="slider-arrow prev" type="button" aria-label="Banner sebelumnya">‹</button>
        <button class="slider-arrow next" type="button" aria-label="Banner berikutnya">›</button>
        <div class="slider-dots">
            <button class="dot active" type="button" aria-label="Banner 1"></button>
            <button class="dot" type="button" aria-label="Banner 2"></button>
            <button class="dot" type="button" aria-label="Banner 3"></button>
        </div>
    </section>

    <section class="catalog" id="etalase">
        <div class="section-intro">
            <div>
                <span class="section-kicker">ETALASE KAMI</span>
                <h2>Temukan produk kebutuhanmu</h2>
                <p>Pilih kategori atau ketik nama produk pada kolom pencarian.</p>
            </div>
            <div class="total-products"><strong><?= count($PRODUCTS) ?></strong><span>produk tersedia</span></div>
        </div>

        <div class="catalog-tools">
            <label class="search-box">
                <span>⌕</span>
                <input type="search" id="productSearch" placeholder="Cari nama produk..." autocomplete="off">
            </label>
            <div class="category-filter" id="categoryFilter">
                <button class="category-chip active" data-category="all" type="button">Semua <b><?= count($PRODUCTS) ?></b></button>
                <?php foreach ($categories as $key => $category): ?>
                    <button class="category-chip" data-category="<?= htmlspecialchars($key) ?>" type="button">
                        <?= htmlspecialchars($category['label']) ?>
                        <b><?= count($category['products']) ?></b>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>

        <div id="productSections">
            <?php foreach ($categories as $key => $category): ?>
                <section class="category-section" data-section="<?= htmlspecialchars($key) ?>">
                    <div class="category-heading">
                        <h3><?= htmlspecialchars($category['label']) ?></h3>
                        <span><?= count($category['products']) ?> produk</span>
                    </div>
                    <div class="product-grid">
                        <?php foreach ($category['products'] as $product): ?>
                            <?php
                                $imagePath = __DIR__ . '/' . $product['image'];
                                $imageExists = is_file($imagePath);
                                $message = 'Halo KDKMP Bakulan, saya ingin bertanya tentang produk ' . $product['name'] . '.';
                            ?>
                            <article class="product-card"
                                data-name="<?= htmlspecialchars(mb_strtolower($product['name'], 'UTF-8')) ?>"
                                data-category="<?= htmlspecialchars($key) ?>">
                                <div class="product-image">
                                    <?php if ($imageExists): ?>
                                        <?php
                                            $imageFile = __DIR__ . '/' . $product['image'];
                                            $imageVersion = is_file($imageFile) ? filemtime($imageFile) : time();
                                        ?>

                                        <img src="<?= htmlspecialchars($product['image']) ?>?v=<?= $imageVersion ?>"
                                             alt="<?= htmlspecialchars($product['name']) ?>"
                                             loading="lazy">
                                    <?php else: ?>
                                        <span class="product-placeholder"><?= htmlspecialchars($product['emoji']) ?></span>
                                        <small class="image-hint">Foto menyusul</small>
                                    <?php endif; ?>
                                </div>
                                <div class="product-info">
                                    <h4><?= htmlspecialchars($product['name']) ?></h4>
                                    <?php if (!empty($product['terms']) && is_array($product['terms'])): ?>
                                        <div class="product-terms">
                                            <strong>Syarat &amp; Ketentuan:</strong>
                                            <ul>
                                                <?php foreach ($product['terms'] as $term): ?>
                                                    <li><?= htmlspecialchars($term) ?></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    <?php endif; ?>
                                    <div class="product-bottom">
                                        <strong>
                                             Rp<?= number_format($product['price'], 0, ',', '.') ?>
                                        </strong>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endforeach; ?>
        </div>
        <p class="no-results" id="noResults" hidden>Produk tidak ditemukan. Coba kata kunci atau kategori lain.</p>
    </section>

    <section class="partner-banner" id="kemitraan">
        <div class="partner-icon">🤝</div>
        <div class="partner-copy">
            <span class="section-kicker">TERBUKA UNTUK KEMITRAAN</span>
            <h2>Punya produk UMKM atau ingin bermitra?</h2>
            <p>Mari bekerja sama dan kembangkan usaha bersama KDKMP Bakulan.</p>
        </div>
        <a href="<?= htmlspecialchars(whatsappLink($WHATSAPP_NUMBER, 'Halo KDKMP Bakulan, saya ingin bertanya mengenai kemitraan atau menawarkan produk UMKM.')) ?>"
           target="_blank" rel="noopener">Hubungi kami ↗</a>
    </section>
</main>

<footer>
    <strong>KDKMP <span>BAKULAN</span></strong>
    <p>Belanja dekat, tumbuh bersama.</p>
    <small>© <?= date('Y') ?> KDKMP Bakulan</small>
</footer>

<a class="wa-float"
   href="<?= htmlspecialchars(whatsappLink($WHATSAPP_NUMBER, 'Halo KDKMP Bakulan, saya ingin bertanya tentang produk atau kemitraan.')) ?>"
   target="_blank" rel="noopener">
    <span class="wa-symbol">
    <img src="assets/whatsapp-logo.png"
         alt="WhatsApp"
         class="whatsapp-custom-logo">
</span>
    <span>Ingin bermitra atau menawarkan produk UMKM?<b>Klik WhatsApp di sini</b></span>
</a>

<script src="assets/script.js"></script>
</body>
</html>
