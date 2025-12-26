<?php if ( ! defined('APPPATH')) exit('No direct script access allowed'); ?>
<?php require_once view('header'); ?>
    <div class="container col-12 col-md-12 col-xl-12 col-lg-12 mb-2">
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
<script>
$(document).ready(function() {
    $('.datatable-help').DataTable({
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
