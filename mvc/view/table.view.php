<?php if ( ! defined('APPPATH')) exit('No direct script access allowed'); ?>
<?php require_once view('header'); ?>
    <div class="container col-12 col-md-12 col-xl-12 col-lg-12 mb-5">
        <div class="row">
            <div class="col-xl-12 col-lg-12 pt-2">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <?php if(isset($_SESSION['username'])){ ?>
                            <span class="h5">Selamat datang, <strong><?php echo $_SESSION['fullname']; ?></strong></span>
                            <a href="<?php echo BASEURL; ?>signout" class="btn btn-sm btn-outline-danger ms-2">Logout</a><?php } ?>
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

            <div class="col-12 col-md-12 col-xl-12 col-lg-12">
                <section>
                    <table class="datatable-help table table-striped">
                        <thead>
                            <tr>
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
            top2Start: 'pageLength',
            top2End: 'search',
            topStart: 'info',
            topEnd: 'paging',
        },
        "lengthMenu": [
            [<?php echo PAGINATION; ?>, 50, 100, -1],
            [<?php echo PAGINATION; ?>, 50, 100, "All"] // change per page values here
        ],
        pageLength: <?php echo PAGINATION; ?>,
        scrollX: true,
        fixedHeader: true,
        ajax: {
            url: 'https://jsonplaceholder.typicode.com/posts',
            dataSrc: ''
        },
        columns: [
            {data: 'userId'},
            {data: 'id'},
            {data: 'title'},
            {data: 'body'}
        ]
    });
});
</script>
<?php require_once view('footer'); ?>
