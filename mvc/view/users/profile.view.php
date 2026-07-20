<?php if (! defined('APPPATH')) exit('No direct script access allowed'); ?>
<?php require_once view('dashboard/header.dashboard'); ?>
<?php require_once view('dashboard/top.dashboard'); ?>

<section class="container-fluid">
    <div class="row">
        <div class="col-12 col-lg-11 col-xl-10">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="bi bi-person-badge me-2"></i>Profil Pengguna
                    </h5>
                    <a href="<?php echo BASEURL; ?>users" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                </div>
                <div class="card-body p-4">
                    <!-- Header Foto & Info Singkat -->
                    <div class="text-center mb-4">
                        <div class="avatar-placeholder rounded-circle bg-danger text-white d-inline-flex align-items-center justify-content-center shadow-sm mb-3" style="width: 80px; height: 80px; font-size: 32px; font-weight: bold;">
                            <?php echo strtoupper(substr($user['fullname'] ?? $user['username'] ?? 'U', 0, 1)); ?>
                        </div>
                        <h5 class="fw-bold mb-1" id="header-fullname"><?php echo htmlspecialchars($user['fullname'] ?? '-'); ?></h5>
                        <span class="badge bg-danger text-uppercase px-3 py-2"><?php echo htmlspecialchars($user['role'] ?? 'user'); ?></span>
                    </div>

                    <hr class="my-4 text-muted opacity-25">

                    <input type="hidden" id="user-id" value="<?php echo $user['id'] ?? $user['id_encrypted'] ?? ''; ?>">

                    <!-- Grid Profil Inline Edit -->
                    <div class="row g-4">
                        
                        <!-- Kolom Fullname -->
                        <div class="col-12 col-sm-6">
                            <label class="form-label text-muted small mb-1">Nama Lengkap</label>
                            <div class="inline-field-wrapper" data-field="fullname">
                                <!-- Mode Display -->
                                <div class="view-mode d-flex justify-content-between align-items-center p-2 rounded-3 border">
                                    <span class="field-value fw-semibold"><?php echo htmlspecialchars($user['fullname'] ?? '-'); ?></span>
                                    <button type="button" class="btn btn-link btn-sm p-0 text-primary btn-start-edit" title="Edit Nama">
                                        <i class="bi bi-pencil-square fs-6"></i>
                                    </button>
                                </div>
                                <!-- Mode Edit -->
                                <div class="edit-mode d-none">
                                    <div class="input-group">
                                        <input type="text" class="form-control focus-ring focus-ring-danger rounded-start-3 field-input" value="<?php echo htmlspecialchars($user['fullname'] ?? ''); ?>">
                                        <button type="button" class="btn btn-success btn-save-inline" title="Simpan">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary btn-cancel-inline" title="Batal">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Kolom Username -->
                        <div class="col-12 col-sm-6">
                            <label class="form-label text-muted small mb-1">Username</label>
                            <div class="inline-field-wrapper" data-field="username">
                                <div class="view-mode d-flex justify-content-between align-items-center p-2 rounded-3 border">
                                    <span class="field-value fw-semibold"><?php echo htmlspecialchars($user['username'] ?? '-'); ?></span>
                                    <button type="button" class="btn btn-link btn-sm p-0 text-primary btn-start-edit" title="Edit Username">
                                        <i class="bi bi-pencil-square fs-6"></i>
                                    </button>
                                </div>
                                <div class="edit-mode d-none">
                                    <div class="input-group">
                                        <input type="text" class="form-control focus-ring focus-ring-danger rounded-start-3 field-input" value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>">
                                        <button type="button" class="btn btn-success btn-save-inline" title="Simpan">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary btn-cancel-inline" title="Batal">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Kolom Email -->
                        <div class="col-12 col-sm-6">
                            <label class="form-label text-muted small mb-1">Email</label>
                            <div class="inline-field-wrapper" data-field="email">
                                <div class="view-mode d-flex justify-content-between align-items-center p-2 rounded-3 border">
                                    <span class="field-value fw-semibold"><?php echo htmlspecialchars($user['email'] ?? '-'); ?></span>
                                    <button type="button" class="btn btn-link btn-sm p-0 text-primary btn-start-edit" title="Edit Email">
                                        <i class="bi bi-pencil-square fs-6"></i>
                                    </button>
                                </div>
                                <div class="edit-mode d-none">
                                    <div class="input-group">
                                        <input type="email" class="form-control focus-ring focus-ring-danger rounded-start-3 field-input" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>">
                                        <button type="button" class="btn btn-success btn-save-inline" title="Simpan">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary btn-cancel-inline" title="Batal">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Kolom Role -->
                        <div class="col-12 col-sm-6">
                            <label class="form-label text-muted small mb-1">Role / Peran</label>
                            <div class="inline-field-wrapper" data-field="role">
                                <div class="view-mode d-flex justify-content-between align-items-center p-2 rounded-3 border">
                                    <span class="field-value fw-semibold text-capitalize"><?php echo htmlspecialchars($user['role'] ?? '-'); ?></span>
                                    <button type="button" class="btn btn-link btn-sm p-0 text-primary btn-start-edit" title="Edit Role">
                                        <i class="bi bi-pencil-square fs-6"></i>
                                    </button>
                                </div>
                                <div class="edit-mode d-none">
                                    <div class="input-group">
                                        <select class="form-select focus-ring focus-ring-danger rounded-start-3 field-input">
                                            <option value="admin" <?php echo ($user['role'] ?? '') === 'admin' ? 'selected' : ''; ?>>Admin</option>
                                            <option value="user" <?php echo ($user['role'] ?? '') === 'user' ? 'selected' : ''; ?>>User</option>
                                        </select>
                                        <button type="button" class="btn btn-success btn-save-inline" title="Simpan">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary btn-cancel-inline" title="Batal">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Toast Component untuk Notifikasi -->
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
        var userId = $('#user-id').val();

        // 1. Masuk ke Mode Edit saat tombol Pensil diklik
        $(document).on('click', '.btn-start-edit', function() {
            var $wrapper = $(this).closest('.inline-field-wrapper');
            $wrapper.find('.view-mode').addClass('d-none');
            $wrapper.find('.edit-mode').removeClass('d-none');
            $wrapper.find('.field-input').focus();
        });

        // 2. Batal (Close Times) - Kembalikan ke Mode Display tanpa ubah data
        $(document).on('click', '.btn-cancel-inline', function() {
            var $wrapper = $(this).closest('.inline-field-wrapper');
            var oldValue = $wrapper.find('.field-value').text().trim();
            
            // Revert nilai input ke nilai semula
            if ($wrapper.find('.field-input').is('select')) {
                $wrapper.find('.field-input').val(oldValue.toLowerCase());
            } else {
                $wrapper.find('.field-input').val(oldValue);
            }

            $wrapper.find('.edit-mode').addClass('d-none');
            $wrapper.find('.view-mode').removeClass('d-none');
        });

        // 3. Simpan Perubahan per Kolom via AJAX
        $(document).on('click', '.btn-save-inline', function() {
            var $btn = $(this);
            var $wrapper = $btn.closest('.inline-field-wrapper');
            var fieldName = $wrapper.data('field');
            var newValue = $wrapper.find('.field-input').val();
            var $input = $wrapper.find('.field-input');

            // Mencegah double click
            $btn.prop('disabled', true);

            var postData = {
                id: userId
            };
            postData[fieldName] = newValue; // Misal: { id: "123", fullname: "Budi" }

            $.ajax({
                url: '<?php echo BASEURL; ?>users/' + userId + '/perbarui', // Endpoint backend untuk update single field
                type: 'POST',
                data: postData,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        // Update teks di tampilan
                        $wrapper.find('.field-value').text(newValue);

                        // Khusus jika fullname diubah, update juga judul di header profil
                        if (fieldName === 'fullname') {
                            $('#header-fullname').text(newValue);
                        }

                        // Sembunyikan mode edit
                        $wrapper.find('.edit-mode').addClass('d-none');
                        $wrapper.find('.view-mode').removeClass('d-none');

                        showToast("Berhasil memperbarui " + fieldName, 'success');
                    } else {
                        showToast("Gagal: " + response.message, 'danger');
                    }
                },
                error: function(xhr, status, error) {
                    try {
                        var errObj = JSON.parse(xhr.responseText);
                        showToast("Gagal: " + (errObj.message || "Terjadi kesalahan."), 'danger');
                    } catch(e) {
                        showToast("Gagal: Terjadi kesalahan koneksi.", 'danger');
                    }
                },
                complete: function() {
                    $btn.prop('disabled', false);
                }
            });
        });

        // Helper Toast Notification
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