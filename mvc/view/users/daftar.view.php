<?php if ( ! defined('APPPATH')) exit('No direct script access allowed'); ?>
<?php require_once view('dashboard/header.dashboard'); $key = encrypt(date('YmdHi')); ?>
<?php require_once view('dashboard/top.dashboard'); ?>
                <section>
                    <table class="datatable-help table table-striped">
                        <thead>
                            <tr>
                                <th>Fullname</th>
                                <th>Email</th>
                                <th>Role</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Fullname</th>
                                <th>Email</th>
                                <th>Role</th>
                            </tr>
                        </tfoot>
                    </table>
                </section>
                <?php require_once view('dashboard/bottom.dashboard'); ?>
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
            url: '<?php echo BASEURL.'userslist/'.$key; ?>',
            dataSrc: ''
        },
        columns: [
            {data: 'fullname'},
            {data: 'email'},
            {data: 'role'},
        ]
    });
});
</script>
<?php require_once view('dashboard/footer.dashboard'); ?>
