<?php if ( ! defined('MODPATH')) exit('No direct script access allowed'); ?>
<?php require_once view('header'); ?>
    <div class="container col-12 col-md-12 col-xl-12 col-lg-12 mb-5">
        <div class="row">
            <div class="col-xl-12 col-lg-12 pt-2">
                <span class="float-right">
                    <div class="form-check form-switch">
                        <label class="form-check-label mr-5" for="darkModeToggle"><i class="fas fa-sun"></i></label>
                        <input class="form-check-input" type="checkbox" role="switch" id="darkModeToggle">
                        <label class="form-check-label" for="darkModeToggle"><i class="fas fa-moon"></i></label>
                    </div>
                </span>
            </div>

            <div class="col-12 col-md-12 col-xl-12 col-lg-12">
                <section>
                    <table class="datatable-logs table table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>IP Browser</th>
                                <th>Information</th>
                            </tr>
                        </thead>
                    </table>
                </section>
            </div>
        </div>
    </div>
    <footer class="fixed-bottom mt-auto border-top p-2 w-100" data-bs-theme="light">
        <div class="container">
            <span><?php echo COPYR; ?></span>
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
        // set the initial value
        pageLength: <?php echo PAGINATION; ?>,
        ajax: {
            url: '<?php echo BASEURL.'logsfiles'; ?>',
            dataSrc: ''
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