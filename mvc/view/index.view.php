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
                            <a href="<?php echo BASEURL; ?>login" class="btn btn-sm btn-primary">Login</a>
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
                <script type="module">
                    const { html, render } = window.lit;
                    
                    // Lit-HTML template secara otomatis membaca data dari PHP (window.pageData)
                    const welcomeTemplate = (data) => html`
                        <div class="card border-0 shadow-sm bg-info bg-opacity-10 text-info p-3 mb-4">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-lightning-charge-fill fs-3 me-3"></i>
                                <div>
                                    <h5 class="mb-1">Lit-HTML Engine Active</h5>
                                    <p class="mb-0 small">
                                        Halo <strong>${data.user}</strong>, Anda menggunakan <strong>${data.title}</strong> versi ${data.version}.
                                        <br/>Server Time: <span class="badge bg-info">${data.server_time}</span>
                                    </p>
                                </div>
                            </div>
                        </div>

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
                    `;

                    // Render ke elemen
                    render(welcomeTemplate(window.pageData), document.getElementById('lit-app'));
                </script>

            </div>
        </div>
    </div>
    <footer class="fixed-bottom mt-auto m-1 p-2 rounded" data-bs-theme="light">
        <div class="container">
            <span><i class="bi bi-c-circle"></i><?php echo COPYR; ?></span>
        </div>
    </footer>
<?php require_once view('footer'); ?>
