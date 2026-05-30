<?php if (! defined('APPPATH')) exit('No direct script access allowed'); ?>
<?php require_once view('header'); ?>
<div class="container col-12 col-md-12 col-xl-12 col-lg-12 mb-5">
    <div class="row">
        <div class="col-xl-12 col-lg-12 pt-2">
            <div class="d-flex justify-content-between align-items-center">
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
            </div>
        </div>
        <div class="col-12 col-md-12 col-xl-12 col-lg-12">
            <section>
                <table class="datatable-help table table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>userId</th>
                            <th>id</th>
                            <th>Title</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>No</th>
                            <th>userId</th>
                            <th>id</th>
                            <th>Title</th>
                            <th>Description</th>
                        </tr>
                    </tfoot>
                </table>
            </section>
        </div>
    </div>
</div>
<footer class="fixed-bottom mt-auto m-1 p-2 rounded" data-bs-theme="light">
    <div class="container">
        <span><i class="bi bi-c-circle"></i><?php echo COPYR; ?></span>
    </div>
</footer>
<script>
    $(document).ready(function() {
        $('.datatable-help').DataTable({
            layout: {
                /*top2Start: 'pageLength',
                top2End: 'search',
                topStart: 'info',
                topEnd: 'paging',*/
            },
            "lengthMenu": [
                [<?php echo PAGINATION; ?>, 50, 100, -1],
                [<?php echo PAGINATION; ?>, 50, 100, "All"] // change per page values here
            ],
            pageLength: <?php echo PAGINATION; ?>,
            scrollX: true,
            fixedHeader: true,
            fixedColumns: {
                rightColumns: 1
            },
            ajax: {
                url: 'https://jsonplaceholder.typicode.com/posts',
                dataSrc: ''
            },
            columns: [
                {
                    data: null, // Tidak butuh data dari database
                    sortable: false, // Matikan fitur sorting untuk kolom nomor
                    searchable: false, // Matikan fitur pencarian untuk kolom nomor
                    render: function(data, type, row, meta) {
                        // Perhitungan penomoran otomatis + mendukung perpindahan halaman (pagination)
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    data: 'userId'
                },
                {
                    data: 'id'
                },
                {
                    data: 'title'
                },
                {
                    data: 'body'
                },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    // 2. Use render to inject the HTML button string
                    render: function(data, type, row, meta) {
                        return `<div class="dropdown text-right">
                            <button class="btn btn-outline-default btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" data-bs-boundary="body" aria-expanded="false">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-table-menu" data-bs-popper="static">
                                <li><a class="dropdown-item edit-btn" href="#" data-id="${row[0]}">Details</a></li>
                            </ul>
                        </div>`;
                    }
                },
            ]
        });
    });
    // Saat dropdown Bootstrap akan ditampilkan
    $(document).on('show.bs.dropdown', '.table .dropdown', function() {
        // Salin dan pindahkan menu dropdown ke elemen body luar
        var $dropdownMenu = $(this).find('.dropdown-table-menu');
        $('body').append($dropdownMenu.css({
            position: 'absolute',
            left: $(this).offset().left,
            top: $(this).offset().top + $(this).outerHeight()
        }).detach());
    });

    // Saat dropdown Bootstrap ditutup
    $(document).on('hide.bs.dropdown', '.table .dropdown', function() {
        // Sembunyikan menu kembali ke tempat asalnya jika diperlukan, 
        // atau biarkan Bootstrap menghapusnya dari layar
        var $dropdownMenu = $('body').find('.dropdown-table-menu');
        // Mengembalikan struktur asli agar tidak merusak siklus hidup DOM
        $('.table .dropdown').append($dropdownMenu.detach());
    });
</script>
<?php require_once view('footer'); ?>