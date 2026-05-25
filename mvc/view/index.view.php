<?php if ( ! defined('APPPATH')) exit('No direct script access allowed'); ?>
<?php require_once view('header'); ?>
    <div class="container col-12 col-xl-12 col-lg-12 mb-5">
        <div class="row">
            <div class="col-xl-12 col-lg-12 pt-2">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <?php if(isset($_SESSION['username'])){ ?>
                            <span class="h5">Selamat datang, <strong><?php echo $_SESSION['fullname']; ?></strong></span>
                            <a href="<?php echo BASEURL; ?>signout" class="btn btn-sm btn-outline-danger ms-2">Logout</a>
                        <?php } else { ?>
                            <a href="<?php echo BASEURL; ?>login" class="btn btn-sm btn-danger rounded-3">Login</a>
                        <?php } ?>
                    </div>
                    <div class="dropdown" id="theme-dropdown-container">
                        <button class="btn btn-link nav-link dropdown-toggle d-flex align-items-center" id="bd-theme" type="button" aria-expanded="false" data-bs-toggle="dropdown" data-bs-display="static" aria-label="Toggle theme (auto)">
                            <i class="bi bi-circle-half theme-icon-active me-2"></i>
                            <span class="d-lg-none ms-2" id="bd-theme-text">Toggle theme</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="bd-theme-text">
                            <li>
                                <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="light" aria-pressed="false">
                                    <i class="bi bi-sun-fill me-2 opacity-50 theme-icon"></i>
                                    Light
                                    <i class="bi bi-check2 ms-auto d-none"></i>
                                </button>
                            </li>
                            <li>
                                <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark" aria-pressed="false">
                                    <i class="bi bi-moon-stars-fill me-2 opacity-50 theme-icon"></i>
                                    Dark
                                    <i class="bi bi-check2 ms-auto d-none"></i>
                                </button>
                            </li>
                            <li>
                                <button type="button" class="dropdown-item d-flex align-items-center active" data-bs-theme-value="auto" aria-pressed="true">
                                    <i class="bi bi-circle-half me-2 opacity-50 theme-icon"></i>
                                    System
                                    <i class="bi bi-check2 ms-auto d-none"></i>
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-xl-8 col-lg-8 pt-5 mt-5">
                <p><font class="h2 font-weight-bold text-danger">sfrw</font> <small><?php echo VERSIONFRMAEWORK; ?></small></p>
                <!-- Lit-HTML Example Component -->
                <?php 
                    // Meneruskan data dari PHP ke JavaScript secara otomatis
                    echo pass_to_js([
                        'title' => 'S-FRW Reactive',
                        'user' => $_SESSION['fullname'] ?? 'Developer',
                        'version' => VERSIONFRMAEWORK,
                        'server_time' => DATEWMIN
                    ]); 
                ?>
                <div id="lit-app" class="mb-4"></div>
            </div>
        </div>
    </div>
    <footer class="fixed-bottom mt-auto m-1 p-2 rounded" data-bs-theme="light">
        <div class="container">
            <span><i class="bi bi-c-circle"></i> <?php echo COPYR; ?></span>
        </div>
    </footer>
<script type="module" src="<?php echo asset('bootstrap/theme/js/lit-html/welcome-lit.js'); ?>"></script>
<?php require_once view('footer'); ?>
