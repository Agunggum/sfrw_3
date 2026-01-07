<?php if ( ! defined('APPPATH')) exit('No direct script access allowed'); ?>
<?php require_once view('header'); ?>
    <div class="container col-12 col-md-12 col-xl-12 col-lg-12 mb-5">
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
