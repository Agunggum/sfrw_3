<?php if ( ! defined('MODPATH')) exit('No direct script access allowed'); ?>
<?php require_once view('header'); ?>
<body>
    <div class="container col-12 col-md-12 col-xl-12 col-lg-12">
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
<script>
$(document).ready(function() {
    $('.datatable-logs').DataTable({
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