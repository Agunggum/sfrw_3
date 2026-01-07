<?php if ( ! defined('APPPATH')) exit('No direct script access allowed'); ?>
<?php require_once view('header'); ?>
    <div class="container col-12 col-xl-12 col-lg-12 mb-5">
        <div class="row">
            <div class="col-xl-12 col-lg-12 pt-2">
                <span class="float-right">
                    <div class="d-flex align-items-center ms-lg-3 mt-3 mt-lg-0" aria-label="Berpindah dari terang ke gelap">
                        <!-- Ikon Matahari (Mode Terang) -->
                        <i class="bi bi-sun-fill text-warning me-1"></i>
                        
                        <!-- Switch Utama -->
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" role="switch" id="darkModeToggle" style="cursor: pointer; width: 2.5em; height: 1.25em;" aria-label="Berpindah dari terang ke gelap">
                            <label class="form-check-label" for="darkModeToggle" aria-label="Berpindah dari terang ke gelap"></label>
                        </div>
                        
                        <!-- Ikon Bulan (Mode Gelap) -->
                        <i class="bi bi-moon-stars-fill text-secondary ms-1"></i>
                    </div>
                </span>
            </div>
            <div class="col-xl-8 col-lg-8 pt-5 mt-5">
                <p><font class="h2 font-weight-bold text-danger">sfrw</font> <small><?php echo VERSIONFRMAEWORK; ?></small></p>
                <div class="row">
                    <div class="col-lg-6 pb-2">
                        <div class="card">
                            <div class="card-body">
                                <p class="card-text">sfrw adalah framework <font class="text-danger">indonesia</font> yang dikembangkan oleh indonesia untuk programer atau calon programer <font class="text-danger">indonesia</font></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 pb-2">
                        <div class="card">
                            <div class="card-body">
                                <p class="card-text">Pelajari lebih lanjut.</p>
                                <a href="https://documentation.agunggum.id/" target="_blank" class="btn btn-outline-info">dokumentasi</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="fixed-bottom mt-auto m-1 p-2 rounded" data-bs-theme="light">
        <div class="container">
            <span><i class="bi bi-c-circle"></i><?php echo COPYR; ?></span>
        </div>
    </footer>
<?php require_once view('footer'); ?>
