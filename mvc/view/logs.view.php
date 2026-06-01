<?php if ( ! defined('MODPATH')) exit('No direct script access allowed'); ?>
<?php require_once view('header'); ?>
    <div class="container col-12 col-md-12 col-xl-12 col-lg-12 mb-5">
        <div class="row">
            <div class="col-xl-12 col-lg-12 pt-2">
                <nav class="d-flex justify-content-between align-items-center">
                    <div>
                        <span id="id-pemicu" class="title-class" data-lang-id="id-pemicu"></span>
                        <?php if(isset($_SESSION['username'])){ ?>
                            <span class="h5"><span id="id-selamat-datang" class="title-class" data-lang-id="id-selamat-datang">Selamat datang</span>, <strong><?php echo $_SESSION['fullname']; ?></strong></span>
                            <a href="<?php echo BASEURL; ?>signout" class="btn btn-sm btn-outline-danger ms-2 rounded-3"><span id="id-keluar" class="title-class" data-lang-id="id-keluar">Keluar</span></a>
                            <a href="<?php echo BASEURL; ?>dashboard" class="btn btn-sm btn-info ms-2 rounded-3"><span id="id-dasbor" class="title-class" data-lang-id="id-dasbor">Dasbor</span></a>
                        <?php } else { ?>
                            <a href="<?php echo BASEURL; ?>login" class="btn btn-sm btn-danger rounded-3"><span id="id-masuk" class="title-class" data-lang-id="id-masuk">Masuk</span></a>
                        <?php } ?>
                        <?php if (defined('ENVIRONMENT') && ENVIRONMENT === 'local'): ?>
                            <button class="btn btn-sm btn-outline-success ms-2 rounded-3" onclick="simulasiPerubahan()">(<span id="pemicu-terjemahan" class="title-class" data-lang-id="pemicu-terjemahan">Pemicu Terjemahan</span>)</button>
                        <?php endif; ?>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        
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

                        <div class="dropdown" id="theme-dropdown-container">
                            <button class="btn btn-link nav-link dropdown-toggle d-flex align-items-center" id="bd-theme" type="button" aria-expanded="false" data-bs-toggle="dropdown" data-bs-display="static" aria-label="Toggle theme (auto)">
                                <i class="bi bi-circle-half theme-icon-active me-2"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="bd-theme-text">
                                <li>
                                    <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="light" aria-pressed="false">
                                        <i class="bi bi-sun-fill me-2 opacity-50 theme-icon"></i>
                                        <span id="id-terang" class="title-class" data-lang-id="id-terang">Terang</span>
                                        <i class="bi bi-check2 ms-auto d-none"></i>
                                    </button>
                                </li>
                                <li>
                                    <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark" aria-pressed="false">
                                        <i class="bi bi-moon-stars-fill me-2 opacity-50 theme-icon"></i>
                                        <span id="id-gelap" class="title-class" data-lang-id="id-gelap">Gelap</span>
                                        <i class="bi bi-check2 ms-auto d-none"></i>
                                    </button>
                                </li>
                                <li>
                                    <button type="button" class="dropdown-item d-flex align-items-center active" data-bs-theme-value="auto" aria-pressed="true">
                                        <i class="bi bi-circle-half me-2 opacity-50 theme-icon"></i>
                                        <span id="id-sistem" class="title-class" data-lang-id="id-sistem">Sistem</span>
                                        <i class="bi bi-check2 ms-auto d-none"></i>
                                    </button>
                                </li>
                            </ul>
                        </div>

                    </div>
                </nav>
            </div>

            <main class="col-12 col-md-12 col-xl-12 col-lg-12">
                <section>
                    <table class="datatable-logs table table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>IP Browser</th>
                                <th>Information</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><span class="placeholder-glow placeholder rounded-3 col-12"></span></td>
                                <td><span class="placeholder-glow placeholder rounded-3 col-12"></span></td>
                                <td><span class="placeholder-glow placeholder rounded-3 col-12"></span></td>
                            </tr>
                        </tbody>
                    </table>
                </section>
            </main>
        </div>
    </div>
    <footer class="fixed-bottom mt-auto m-1 p-2 rounded" data-bs-theme="light">
        <div class="container">
            <span><i class="bi bi-c-circle"></i><?php echo COPYR; ?></span>
        </div>
    </footer>
<script>
$(document).ready(function() {
    $('.datatable-logs').DataTable({
        layout: {
            top2Start: 'pageLength',
            top2End: 'search',
            topStart: 'info',
            topEnd: 'paging',
        },
        "lengthMenu": [
            [<?php echo PAGINATION; ?>, 50, 100, -1],
            [<?php echo PAGINATION; ?>, 50, 100, "All"] // change per page values here
        ],
        scrollX: true,
        fixedHeader: true,
        "ordering": false,
        // set the initial value
        pageLength: <?php echo PAGINATION; ?>,
        ajax: {
            url: '<?php echo BASEURL.'logsfiles/'.$data['file']; ?>',
            dataSrc: ''
        },
        "language": {
            "loadingRecords": '<div class="placeholder-glow p-2"><span class="placeholder-glow placeholder rounded-3 col-12"></span></div>'
        },
        columns: [
            {data: 'waktu'},
            {data: 'level'},
            {data: 'pesan'}
        ]
    });
});
</script>
<?php require_once view('footer'); ?>