<?php if (! defined('APPPATH')) exit('No direct script access allowed'); ?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<span id="id-pemicu" class="title-class" data-lang-id="id-pemicu"></span>

<div class="d-flex" id="wrapper">
    <div id="sidebar-wrapper" class="shadow-sm">
        <div class="sidebar-heading d-flex align-items-center gap-2 fw-bold">
            <i class="bi bi-speedometer2 text-danger"></i>
            <span>Admin<span class="text-danger">SFRW</span></span>
        </div>
        <ul class="nav flex-column flex-nowrap p-2 gap-1" id="sidebarMenu">

            <li class="nav-item">
                <a class="nav-link rounded <?php echo (ROUTE == 'dashboard') ? 'active' : ''; ?>" href="<?php echo BASEURL; ?>dashboard">
                    <i class="bi bi-house-door me-2"></i>
                    <span id="id-dasbor" class="title-class" data-lang-id="id-dasbor">Dasbor</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link rounded d-flex align-items-center justify-content-between"
                    data-bs-toggle="collapse" href="#submenuMaster" role="button" aria-expanded="false" aria-controls="submenuMaster">
                    <div>
                        <i class="bi bi-database me-2"></i>
                        <span id="id-master-data" class="title-class" data-lang-id="id-master-data">Master Data</span>
                    </div>
                    <i class="bi bi-chevron-down arrow-icon fs-7"></i>
                </a>
                <div class="collapse <?php echo (ROUTE == 'users') ? 'show' : ''; ?>" id="submenuMaster" data-bs-parent="#sidebarMenu">
                    <ul class="nav flex-column ps-3 pt-1 gap-1 nav-tree">
                        <li class="nav-item">
                            <a href="<?php echo BASEURL; ?>users" class="nav-link animated-link rounded <?php echo (ROUTE == 'users') ? 'active' : ''; ?> py-2 small">
                                <span id="id-data-pengguna" class="title-class" data-lang-id="id-data-pengguna">Data Pengguna</span>
                            </a>
                            <ul class="nav flex-column ps-3 pt-1 gap-1">
                                <li class="nav-item">
                                    <a href="<?php echo BASEURL; ?>users/tambah" class="nav-link animated-link rounded py-2 small">
                                        <span id="id-tambah-data-pengguna" class="title-class" data-lang-id="id-tambah-data-pengguna">Tambah Data Pengguna</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link animated-link rounded py-2 small">
                                <span id="id-data-produk" class="title-class" data-lang-id="id-data-produk">Data Produk</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link rounded d-flex align-items-center justify-content-between"
                    data-bs-toggle="collapse" href="#submenuLaporan" role="button" aria-expanded="false" aria-controls="submenuLaporan">
                    <div>
                        <i class="bi bi-file-earmark-bar-graph me-2"></i>
                        <span id="id-laporan" class="title-class" data-lang-id="id-laporan">Laporan</span>
                    </div>
                    <i class="bi bi-chevron-down arrow-icon fs-7"></i>
                </a>
                <div class="collapse" id="submenuLaporan" data-bs-parent="#sidebarMenu">
                    <ul class="nav flex-column ps-3 pt-1 gap-1 nav-tree">
                        <li class="nav-item">
                            <a href="#" class="nav-link animated-link rounded py-2 small">
                                <span id="id-laporan-penjualan" class="title-class" data-lang-id="id-laporan-penjualan">Laporan Penjualan</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link animated-link rounded py-2 small">
                                <span id="id-laporan-keuangan" class="title-class" data-lang-id="id-laporan-keuangan">Laporan Keuangan</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

        </ul>
        <?php if (defined('ENVIRONMENT') && ENVIRONMENT === 'local'): ?>
            <button class="btn btn-sm btn-outline-success ms-2 rounded-3" onclick="simulasiPerubahan()">(<span id="pemicu-terjemahan" class="title-class" data-lang-id="pemicu-terjemahan">Pemicu Terjemahan</span>)</button>
        <?php endif; ?>
    </div>

    <div id="page-content-wrapper">
        <nav class="navbar navbar-expand px-4 shadow-sm">
            <button class="btn btn-outline-secondary btn-sm rounded-circle me-3" id="sidebarToggle">
                <i class="bi bi-list fs-5"></i>
            </button>

            <div class="ms-auto d-flex align-items-center gap-2">
                <div class="dropdown" id="themeDropdown">
                    <button class="btn btn-link nav-link text-body dropdown-toggle border-0" id="bd-theme-text" type="button" aria-expanded="false" data-bs-toggle="dropdown" aria-label="Toggle theme">
                        <i class="bi bi-circle-half theme-icon-active" id="themeIconActive"></i>
                        <span class="d-none ms-2" id="themeTextActive">Theme</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="bd-theme-text">
                        <li>
                            <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="light" aria-pressed="false">
                                <i class="bi bi-sun-fill me-2 opacity-50"></i>
                                <span id="id-terang" class="title-class" data-lang-id="id-terang">Terang</span>
                                <i class="bi bi-check2 ms-auto d-none"></i>
                            </button>
                        </li>
                        <li>
                            <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark" aria-pressed="false">
                                <i class="bi bi-moon-stars-fill me-2 opacity-50"></i>
                                <span id="id-gelap" class="title-class" data-lang-id="id-gelap">Gelap</span>
                                <i class="bi bi-check2 ms-auto d-none"></i>
                            </button>
                        </li>
                        <li>
                            <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="auto" aria-pressed="false">
                                <i class="bi bi-circle-half me-2 opacity-50"></i>
                                <span id="id-sistem" class="title-class" data-lang-id="id-sistem">Sistem</span>
                                <i class="bi bi-check2 ms-auto d-none"></i>
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="dropdown ms-2">
                    <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle text-body" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="user-avatar me-2" id="userAvatar" data-name="<?php echo $_SESSION['fullname']; ?>"></div>
                        <span class="d-none d-sm-inline" id="userNameText"><?php echo $_SESSION['fullname']; ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="dropdownUser">
                        <li><a class="dropdown-menu-item dropdown-item" href="#"><i class="bi bi-person me-2"></i><span id="id-profil" class="title-class" data-lang-id="id-profil">Profil</span></a></li>
                        <li><a class="dropdown-menu-item dropdown-item" href="#"><i class="bi bi-gear me-2"></i><span id="id-pengaturan" class="title-class" data-lang-id="id-pengaturan">Pengaturan</span></a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-menu-item dropdown-item" href="<?php echo BASEURL; ?>signout"><i class="bi bi-box-arrow-right me-2"></i><span id="id-keluar" class="title-class" data-lang-id="id-keluar">Keluar</span></a></li>
                    </ul>
                </div>

                <div class="dropdown" id="language-dropdown-container">
                    <button class="btn btn-link nav-link dropdown-toggle d-flex align-items-center" id="bd-language" type="button" aria-expanded="false" data-bs-toggle="dropdown" data-bs-display="static" aria-label="Toggle language">
                        <i id="current-flag-icon" class="fi fi-id language-icon-active me-2"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="bd-language-text">
                        <li>
                            <button type="button" class="dropdown-item d-flex align-items-center lang-item" data-lang="id" data-bs-language-value="id-ID" aria-pressed="false" onclick="changeLanguage('id')">
                                <i class="fi fi-id me-2 opacity-50 language-icon"></i>
                                Indonesia
                                <i class="bi bi-check2 ms-auto d-none check-icon"></i>
                            </button>
                        </li>
                        <li>
                            <button type="button" class="dropdown-item d-flex align-items-center lang-item" data-lang="en" data-bs-language-value="en-US" aria-pressed="false" onclick="changeLanguage('en')">
                                <i class="fi fi-us me-2 opacity-50 language-icon"></i>
                                English
                                <i class="bi bi-check2 ms-auto d-none check-icon"></i>
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container-fluid p-2">
            <div class="container-page p-3">