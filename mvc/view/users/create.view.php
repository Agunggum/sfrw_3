<?php if (! defined('APPPATH')) exit('No direct script access allowed'); ?>
<?php require_once view('dashboard/header.dashboard'); ?>
<?php require_once view('dashboard/top.dashboard'); ?>

<section class="container-fluid">
    <div class="row">
        <div class="col-12 col-lg-11 col-xl-10">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="bi bi-plus me-2"></i><span id="id-tambah-pengguna-baru" class="title-class" data-lang-id="id-tambah-pengguna-baru">Tambah Pengguna Baru</span>
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form id="form-add-user" onsubmit="return false;" autocomplete="off">
                        <div class="mb-3">
                            <label for="fullname" class="form-label"><span id="id-namalengkap" class="title-class" data-lang-id="id-namalengkap">Nama lengkap</span> <span class="text-danger">*</span></label>
                            <?php echo forminput(['text', 'fullname', 'fullname', 'Fullname', 'off', 'required']); ?>
                        </div>

                        <div class="mb-3">
                            <label for="username" class="form-label"><span id="id-username" class="title-class" data-lang-id="id-username">Nama pengguna</span> <span class="text-danger">*</span></label>
                            <?php echo forminput(['text', 'username', 'username', 'Username', 'off', 'required pattern="^[a-z0-9_]+$" title="Username harus unik dan hanya berisi huruf, angka, dan garis bawah (_)"']); ?>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label"><span id="id-email" class="title-class" data-lang-id="id-email">Surel</span> <span class="text-danger">*</span></label>
                            <?php echo forminput(['email', 'email', 'email', 'jhon.doe@email.com', 'off', 'required']); ?>
                        </div>

                        <div class="mb-3">
                            <label for="password-field" class="form-label"><span id="id-sandi" class="title-class" data-lang-id="id-sandi">Kata sandi</span> <span class="text-danger">*</span></label>
                            <?php echo forminput(['password', 'password', 'password-field', 'Password', 'off', 'required pattern=".{8,}" title="Password harus minimal 8 karakter"'], ['group', 'right', '<button id="toggle-password" class="btn btn-outline-secondary" type="button"><i class="bi bi-eye-slash"></i></button>', 'toggle-password']); ?>
                        </div>

                        <div class="mb-4">
                            <label for="role" class="form-label"><span id="id-role" class="title-class" data-lang-id="id-role">Peran</span> <span class="text-danger">*</span></label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="" selected disabled>--</option>
                                <option value="admin">Admin</option>
                                <option value="user">User</option>
                            </select>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" id="btn-submit" class="btn btn-success px-4">
                                <i class="bi bi-save me-1"></i> <span id="id-simpan-pengguna" class="title-class" data-lang-id="id-simpan-pengguna">Simpan Pengguna</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Component Toast untuk Notifikasi Response AJAX -->
<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1100;">
    <div id="liveToast" class="toast align-items-center text-white border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body" id="toastMessage"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<?php require_once view('dashboard/bottom.dashboard'); ?>

<script>
    $(document).ready(function() {

        // Submit form via AJAX
        $('#form-add-user').on('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();

            var $form = $(this);
            var $btnSubmit = $('#btn-submit');
            var originalBtnText = $btnSubmit.html();

            // Disable tombol submit & tampilkan indikator loading
            $btnSubmit.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Menyimpan...');

            $.ajax({
                url: '<?php echo BASEURL; ?>users/simpan', // Sesuaikan dengan route endpoint simpan di backend
                type: 'POST',
                data: $form.serialize(), // Mengirimkan seluruh field form
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        showToast("Sukses: " + response.message, 'success');

                        // Opsi: Redirect otomatis ke halaman list user setelah 1.5 detik
                        setTimeout(function() {
                            window.location.href = '<?php echo BASEURL; ?>users';
                        }, 1500);
                    } else {
                        showToast("Gagal: " + response.message, 'danger');
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error Response: ", xhr.responseText);
                    try {
                        var errObj = JSON.parse(xhr.responseText);
                        showToast("Gagal: " + (errObj.message || "Terjadi kesalahan sistem."), 'danger');
                    } catch(e) {
                        showToast("Gagal: Terjadi kesalahan koneksi/sistem.", 'danger');
                    }
                },
                complete: function() {
                    // Kembalikan status tombol ke kondisi semula
                    $btnSubmit.prop('disabled', false).html(originalBtnText);
                }
            });
        });

        // Helper fungsi Toast Bootstrap
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

            var toast = new bootstrap.Toast(toastElement, { delay: 10000 });
            toast.show();
        }
    });
</script>

<?php require_once view('dashboard/footer.dashboard'); ?>