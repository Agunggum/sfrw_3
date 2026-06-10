<?php if (! defined('APPPATH')) exit('No direct script access allowed'); ?>
<?php require_once view('dashboard/header.dashboard');
$key = encrypt(date('YmdHi')); ?>
<?php require_once view('dashboard/top.dashboard'); ?>
<?php if (isset($_SESSION['alert'])) {
    echo $_SESSION['alert'];
} ?>
<style>
    /* CSS tambahan untuk merapikan dropdown yang dipindah ke body via JS */
    .dropdown-table-menu {
        margin: 0 !important;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.175);
        border: 1px solid rgba(0, 0, 0, 0.15);
    }
</style>
<section>
    <table id="datatable-users" class="table table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Fullname</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
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
                <th>No</th>
                <th>Fullname</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
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
                /*top2Start: 'pageLength', topEnd: 'search',*/
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
                url: '<?php echo BASEURL . 'userslist/' . $key; ?>',
                dataSrc: ''
            },
            drawCallback: function() {
                // Sembunyikan dropdown yang terbuka saat tabel di-draw ulang (search/paging)
                $('.dropdown-table-menu').each(function() {
                    var $menu = $(this);
                    if ($menu.is(':visible')) {
                        var $dropdown = $menu.data('origin');
                        if ($dropdown) bootstrap.Dropdown.getOrCreateInstance($dropdown[0]).hide();
                    }
                });
            },
            "language": {
                "loadingRecords": '<div class="placeholder-glow p-2"><span class="placeholder-glow placeholder rounded-3 col-12"></span></div>'
            },
            columns: [{
                    data: null,
                    sortable: false,
                    searchable: false,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    data: 'fullname'
                },
                {
                    data: 'username'
                },
                {
                    data: 'email'
                },
                {
                    data: 'role'
                },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row, meta) {
                        // PENTING: Gunakan properti objek seperti row.id atau data.id (sesuai API Anda), bukan row[0] jika dataSrc berbentuk objek.
                        var idData = row.id || data.id_encrypted;

                        return `<div class="dropdown float-end">
                        <button class="btn btn-outline-default btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-table-menu">
                            <li><a class="dropdown-item edit-btn" href="<?php echo BASEURL; ?>users/${data.id_encrypted}/see" data-id="${idData}">Lihat detil</a></li>
                            <li><button class="dropdown-item delete-btn" href="#" data-id="${idData}" data-username="${data.username}">Hapus</button></li>
                        </ul>
                    </div>`;
                    }
                },
            ]
        });
        // Variabel penampung sementara untuk data baris yang akan dihapus
        var currentTableRow = null;
        var idYangAkanDihapus = null;
        var userYangAkanDihapus = null;

        // 1. Event listener ketika tombol delete di dalam dropdown diklik
        $(document).on('click', '.delete-btn', function(e) {
            e.preventDefault();

            idYangAkanDihapus = $(this).data('id');
            userYangAkanDihapus = $(this).data('username');

            // Cari elemen <tr> terdekat dari tombol delete yang diklik di tabel asli
            // Kita gunakan .edit-btn yang masih tersisa di tabel asli sebagai acuan pencarian baris (TR)
            var originalDropdownContainer = $(`.edit-btn[data-id="${idYangAkanDihapus}"]`).closest('tr');
            currentTableRow = originalDropdownContainer;

            // Tampilkan ID di modal
            $('#delete-id-display').text(userYangAkanDihapus);

            // Munculkan modal
            var modalElement = document.getElementById('staticBackdrop');
            var myModal = bootstrap.Modal.getOrCreateInstance(modalElement);
            myModal.show();
        });

        // 2. Event listener ketika tombol "Understood" (Konfirmasi Hapus) di dalam modal diklik
        $('#confirm-delete-action').on('click', function() {
            var $btn = $(this);

            // Mencegah double click selama proses AJAX berjalan
            $btn.prop('disabled', true).text('Processing...');

            $.ajax({
                url: '<?php echo BASEURL; ?>users-hapus/' + idYangAkanDihapus,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    // Validasi response dari backend
                    if (response.status === 'success') {
                        
                        // Hapus baris dari DataTables secara visual
                        if (currentTableRow && currentTableRow.length) {
                            table.row(currentTableRow).remove().draw(false);
                        } else {
                            var fallbackRow = $(`.edit-btn[data-id="${idYangAkanDihapus}"]`).closest('tr');
                            if (fallbackRow.length) {
                                table.row(fallbackRow).remove().draw(false);
                            }
                        }

                        // Tutup modal
                        var modalElement = document.getElementById('staticBackdrop');
                        var myModal = bootstrap.Modal.getOrCreateInstance(modalElement);
                        myModal.hide();

                        // Notifikasi sukses
                        showToast("Sukses: " + response.message, 'success');
                        $('.datatable-help').DataTable().ajax.reload(null, false);

                    } else {
                        // Jika backend mengirim status "error" walau HTTP Request-nya 'Success (200)'
                        showToast("Gagal: " + response.message, 'danger');
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error Response: ", xhr.responseText);
                    
                    // Coba parsing jika error response berupa JSON string
                    try {
                        var errObj = JSON.parse(xhr.responseText);
                        showToast("Gagal: " + errObj.message, 'danger');
                    } catch(e) {
                        showToast("Gagal: Terjadi kesalahan sistem.", 'danger');
                    }
                },
                complete: function() {
                    // Kembalikan status tombol ke semula
                    $btn.prop('disabled', false).text('Ya, Hapus!');
                }
            });
        });

        function showToast(message, type) {
            var toastElement = document.getElementById('liveToast');
            var toastMessage = document.getElementById('toastMessage');

            // Set teks pesan dari respon PHP
            toastMessage.textContent = message;

            // Atur warna background berdasarkan tipe status (Bootstrap 5 classes)
            toastElement.classList.remove('bg-success', 'bg-danger');
            if (type === 'success') {
                toastElement.classList.add('bg-success');
            } else {
                toastElement.classList.add('bg-danger');
            }

            // Inisialisasi dan jalankan Bootstrap Toast
            var toast = new bootstrap.Toast(toastElement, {
                delay: 5000 // Toast otomatis menghilang setelah 4 detik
            });
            toast.show();
        }
    });
    // Saat dropdown Bootstrap akan ditampilkan
    $(document).on('show.bs.dropdown', '.dropdown', function() {
        var $dropdownContainer = $(this);
        var $dropdownMenu = $dropdownContainer.find('.dropdown-table-menu');

        if ($dropdownMenu.length) {
            // SIMPAN referensi container asal
            $dropdownMenu.data('origin', $dropdownContainer);

            // Pindahkan ke body
            $('body').append($dropdownMenu.detach());

            // Hitung posisi relatif terhadap viewport
            var rect = $dropdownContainer[0].getBoundingClientRect();
            
            $dropdownMenu.css({
                display: 'block',
                position: 'fixed',
                zIndex: '9999',
                top: rect.bottom + 'px',
                left: rect.left + 'px'
            });

            // Sesuaikan jika menu keluar dari viewport kanan
            var menuRect = $dropdownMenu[0].getBoundingClientRect();
            if (menuRect.right > window.innerWidth) {
                $dropdownMenu.css({
                    left: (rect.right - menuRect.width) + 'px'
                });
            }
        }
    });

    // Saat dropdown Bootstrap ditutup
    $(document).on('hide.bs.dropdown', '.dropdown', function() {
        var $dropdownMenu = $('body').find('.dropdown-table-menu');
        if ($dropdownMenu.length) {
            var $originalContainer = $dropdownMenu.data('origin');
            if ($originalContainer && $originalContainer.length) {
                $originalContainer.append($dropdownMenu.detach());
                $dropdownMenu.css({
                    display: '',
                    position: '',
                    zIndex: '',
                    top: '',
                    left: ''
                });
            }
        }
    });

    // Tutup dropdown saat container tabel di-scroll
    $(document).on('scroll', '.dataTables_scrollBody', function() {
        $('.dropdown-table-menu').each(function() {
            if ($(this).is(':visible')) {
                var $origin = $(this).data('origin');
                if ($origin) {
                    var instance = bootstrap.Dropdown.getInstance($origin[0]);
                    if (instance) instance.hide();
                }
            }
        });
    });
</script>
<!-- Modal Static Backdrop diletakkan secara mandiri di body -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Konfirmasi Hapus</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus record ini? (Username: <span id="delete-id-display"></span>)
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirm-delete-action">Ya, Hapus!</button>
            </div>
        </div>
    </div>
</div>
<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1100;">
    <div id="liveToast" class="toast align-items-center text-white border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body" id="toastMessage">
                <!-- Pesan dari PHP akan dimasukkan di sini via JavaScript -->
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>
<?php require_once view('dashboard/footer.dashboard'); ?>