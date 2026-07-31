<?php if (! defined('APPPATH')) exit('No direct script access allowed'); 
$id_id = isset($_COOKIE['user_language']) && $_COOKIE['user_language'] == 'id';
?>
<?php require_once view('dashboard/header.dashboard'); ?>
<?php require_once view('dashboard/top.dashboard'); ?>
<?php if (isset($_SESSION['alert'])) {
    echo $_SESSION['alert'];
} ?>

<style>
/* 1. Atur z-index dropdown agar berada di layer paling atas dari seluruh komponen DataTables */
.dropdown-table-menu {
    z-index: 170 !important;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.175);
    border: 1px solid rgba(0, 0, 0, 0.15);
}
/* 2. Turunkan z-index milik FixedHeader */
table.dataTable.fixedHeader-floating,
table.dataTable.fixedHeader-locked {
    z-index: 130 !important;
}
/* 3. Atur z-index untuk FixedColumns DataTables agar tidak menimpa menu dropdown */
div.DTFC_LeftWrapper,
div.DTFC_RightWrapper,
.dtfc-fixed-left,
.dtfc-fixed-right {
    z-index: 120 !important;
}
/* 4. Pastikan cell/kontainer dropdown saat aktif di kolom fixed naik ke atas */
td.dtfc-fixed-right,
td.dtfc-fixed-left {
    position: relative;
}
td.dtfc-fixed-right:has(.dropdown-toggle.show),
td.dtfc-fixed-left:has(.dropdown-toggle.show) {
    z-index: 160 !important;
}
.dt-search {
    display: inline-flex !important;
    align-items: center;
    margin-top: 0 !important;
    margin-left: 0 !important;
    margin-right: 0 !important;
}

.dt-search input[type="search"],
#custom-search-input {
    padding-left: 2.75rem !important; /* Ruang untuk ikon di sebelah kiri */
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%236c757d' class='bi bi-search' viewBox='0 0 16 16'%3E%3Cpath d='M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: 0.75rem center; /* Sumbu X: 0.75rem dari kiri, Sumbu Y: Tengah */
    background-size: 1rem 1rem;
}

.dt-search .form-control,
.dt-search input[type="search"] {
    border-top-left-radius: 1rem !important;
    border-bottom-left-radius: 1rem !important;
    border-top-right-radius: 1rem !important;
    border-bottom-right-radius: 1rem !important;
    padding: 0.5em;
    margin-top: 0 !important;
    margin-left: 0 !important;
    margin-right: 0 !important;
}

.dt-search .input-group-text {
    border-top-left-radius: 1rem !important;
    border-bottom-left-radius: 1rem !important;
    margin-top: 0.5em !important;
    margin-left: 0 !important;
    margin-right: 0 !important;
}
</style>

<section>
    <table id="datatable-users" class="table table-striped table-hover table-rounded">
        <thead>
            <tr>
                <th>#</th>
                <th><span id="id-namalengkap" class="title-class" data-lang-id="id-namalengkap">Nama lengkap</span></th>
                <th><span id="id-username" class="title-class" data-lang-id="id-username">Nama pengguna</span></th>
                <th><span id="id-email" class="title-class" data-lang-id="id-email">Surel</span></th>
                <th><span id="id-role" class="title-class" data-lang-id="id-role">Peran</span></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><span class="placeholder-glow placeholder rounded-3 col-12"></span></td>
                <td><span class="placeholder-glow placeholder rounded-3 col-12"></span></td>
                <td><span class="placeholder-glow placeholder rounded-3 col-12"></span></td>
                <td><span class="placeholder-glow placeholder rounded-3 col-12"></span></td>
                <td><span class="placeholder-glow placeholder rounded-3 col-12"></span></td>
                <td><span class="placeholder-glow placeholder rounded-3 col-12"></span></td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <th>#</th>
                <th><span id="id-namalengkap" class="title-class" data-lang-id="id-namalengkap">Nama lengkap</span></th>
                <th><span id="id-username" class="title-class" data-lang-id="id-username">Nama pengguna</span></th>
                <th><span id="id-email" class="title-class" data-lang-id="id-email">Surel</span></th>
                <th><span id="id-role" class="title-class" data-lang-id="id-role">Peran</span></th>
                <th></th>
            </tr>
        </tfoot>
    </table>
</section>

<?php require_once view('dashboard/bottom.dashboard'); ?>

<script>
    $(document).ready(function() {
        var table = $('#datatable-users').DataTable({
            layout: {
                topEnd: {
                    buttons: [
                        {
                            text: '<i class="bi bi-plus"></i> <span id="id-tambah" class="title-class" data-lang-id="id-tambah">Tambah</span>',
                            className: 'btn btn-dark shadow-sm rounded-4 ms-2',
                            init: function() {
                                this.node().removeClass('dt-button');
                            },
                            action: function(e, dt, node, config) {
                                SPANavigator.navigateTo('<?php echo site_url('users/create'); ?>');
                            }
                        }
                    ]
                },
                topStart: {
                    className: 'w-auto ms-3 p-0 d-inline-block',
                    features: {
                        search: {
                            placeholder: 'Search',
                            text: ''
                        }
                    }
                },
            },
            "lengthMenu": [
                [<?php echo PAGINATION; ?>, 50, 100, -1],
                [<?php echo PAGINATION; ?>, 50, 100, "All"]
            ],
            pageLength: <?php echo PAGINATION; ?>,
            scrollX: true,
            fixedHeader: true,
            fixedColumns: {
                rightColumns: 1
            },
            ajax: {
                url: '<?php echo site_url('userslist'); ?>',
                dataSrc: ''
            },
            // Otomatis tutup dropdown yang terbuka jika tabel di-render ulang (misal: paged/search)
            drawCallback: function() {
                $('.dropdown-toggle.show').each(function() {
                    var instance = bootstrap.Dropdown.getInstance(this);
                    if (instance) instance.hide();
                });
            },
            "language": {
                "loadingRecords": '<div class="placeholder-glow p-2"><span class="placeholder-glow placeholder rounded-3 col-12"></span></div> <a href="<?php echo site_url('users'); ?>"><span id="en-dont-see-data" class="title-class" data-lang-id="en-dont-see-data">Don`t see data?</span> <i class="bi bi-repeat"></i></a>'
            },
            columns: [
                {
                    data: null,
                    sortable: false,
                    searchable: false,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                { data: 'fullname' },
                { data: 'username' },
                { data: 'email' },
                { data: 'role' },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row, meta) {
                        var idData = row.id || data.id_encrypted;

                        /* 
                          PERBAIKAN UTAMA:
                          Gunakan data-bs-boundary="body" dan strategy:"fixed" dari Popper.js.
                          Sistem ini akan menghitung posisi melayang secara native tanpa merusak DOM HTML.
                        */
                        return `<div class="dropdown float-end">
                        <button class="btn btn-outline-default btn-sm dropdown-toggle" 
                                type="button" 
                                data-bs-toggle="dropdown" 
                                data-bs-boundary="body" 
                                data-bs-popper-config='{"strategy":"fixed"}' 
                                aria-expanded="false">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-table-menu" style="width: 70px !important;">
                            <li><a class="dropdown-item text-center icon-link icon-link-hover" style="--bs-icon-link-transform: translate3d(0, -.125rem, 0);" href="<?php echo site_url('users/${data.id_encrypted}/see'); ?>" data-id="${idData}">-- <i class="bi bi-search"></i> --</a></li>
                            <li><a class="dropdown-item text-center icon-link icon-link-hover delete-btn" style="cursor:pointer; --bs-icon-link-transform: translate3d(0, -.125rem, 0);" data-id="${idData}" data-username="${data.username} - ${data.fullname}">-- <i class="bi bi-trash text-danger"></i> --</a></li>
                        </ul>
                    </div>`;
                    }
                },
            ]
        });

        var currentTableRow = null;
        var idYangAkanDihapus = null;
        var userYangAkanDihapus = null;

        // 1. Event listener ketika tombol delete diklik
        $(document).on('click', '.delete-btn', function(e) {
            e.preventDefault();

            idYangAkanDihapus = $(this).data('id');
            userYangAkanDihapus = $(this).data('username');

            // Tangkap elemen <tr> langsung dari tombol delete
            currentTableRow = $(this).closest('tr');

            $('#delete-id-display').text(userYangAkanDihapus);

            var modalElement = document.getElementById('staticBackdrop');
            var myModal = bootstrap.Modal.getOrCreateInstance(modalElement);
            myModal.show();
        });

        // 2. Event listener konfirmasi hapus modal
        $('#confirm-delete-action').on('click', function() {
            var $btn = $(this);
            $btn.prop('disabled', true).text('Processing...');

            $.ajax({
                url: '<?php echo site_url('users-hapus/'); ?>' + idYangAkanDihapus,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        if (currentTableRow && currentTableRow.length) {
                            table.row(currentTableRow).remove().draw(false);
                        }

                        var modalElement = document.getElementById('staticBackdrop');
                        var myModal = bootstrap.Modal.getOrCreateInstance(modalElement);
                        myModal.hide();

                        showToast("<?php echo $id_id ? 'Sukses: ' : 'Success: '; ?> " + response.message, 'success');
                    } else {
                        showToast("<?php echo $id_id ? 'Gagal: ' : 'Failed: '; ?>  " + response.message, 'danger');
                    }
                },
                error: function(xhr, status, error) {
                    try {
                        var errObj = JSON.parse(xhr.responseText);
                        showToast("<?php echo $id_id ? 'Gagal: ' : 'Failed: '; ?> " + errObj.message, 'danger');
                    } catch(e) {
                        showToast("<?php echo $id_id ? 'Gagal: ' : 'Failed: '; ?> Terjadi kesalahan sistem.", 'danger');
                    }
                },
                complete: function() {
                    $btn.prop('disabled', false).text("<?php echo $id_id ? 'Ya, Hapus!' : 'Yes, Deleted!'; ?> ");
                }
            });
        });

        function showToast(message, type) {
            var toastElement = document.getElementById('liveToast');
            var toastMessage = document.getElementById('toastMessage');

            toastMessage.textContent = message;

            toastElement.classList.remove('bg-success', 'bg-danger');
            if (type === 'success') {
                toastElement.classList.add('bg-success');
            } else {
                toastElement.classList.add('bg-danger');
            }

            var toast = new bootstrap.Toast(toastElement, { delay: 8000 });
            toast.show();
        }

        // Otomatis tutup dropdown jika kontainer tabel di-scroll
        $(document).on('scroll', '.dataTables_scrollBody', function() {
            $('.dropdown-toggle.show').each(function() {
                var instance = bootstrap.Dropdown.getInstance(this);
                if (instance) instance.hide();
            });
        });
    });
</script>

<!-- Modal Static Backdrop -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel"><sKonfirmasi Hapus</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <span id="id-apakah-yakin-hapus-record-ini" class="title-class" data-lang-id="id-apakah-yakin-hapus-record-ini">Apakah Anda yakin ingin menghapus record ini?</span> (Username: <span id="delete-id-display" class="fw-bold"></span>)
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><span id="id-batal" class="title-class" data-lang-id="id-batal">Batal</span></button>
                <button type="button" class="btn btn-danger" id="confirm-delete-action"><span id="id-ya-hapus" class="title-class" data-lang-id="id-ya-hapus">Ya, Hapus!</span></button>
            </div>
        </div>
    </div>
</div>

<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1100;">
    <div id="liveToast" class="toast align-items-center text-white border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body" id="toastMessage"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<?php require_once view('dashboard/footer.dashboard'); ?>