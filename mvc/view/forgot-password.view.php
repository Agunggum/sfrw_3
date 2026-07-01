<?php if ( ! defined('APPPATH')) exit('No direct script access allowed'); ?>
<?php require_once view('header'); ?>
    <div class="col-md-12 col-lg-12 col-xl-12">
        <div class="row">

            <section class="col-md-12 col-lg-6 col-xl-6 vh-100 overflow-y-auto d-none d-lg-block d-xl-block" data-bs-theme="light">
                <div class="container">
                    <div class="pt-2">
                        <span id="id-pemicu" class="title-class" data-lang-id="id-pemicu"></span>
                        <a href="<?php echo BASEURL; ?>" class="text-accent animated-link"><i class="fa fa-angle-left fa-fw"></i> <span id="id-kembali" class="title-class" data-lang-id="id-kembali">Kembali</span></a>
                    </div>
                        
                    <div class="d-flex justify-content-center">
                        <div class="col-md-12 col-lg-12 col-xl-12 mt-3 mb-1 text-left">
                            <div class="h3"><span><?php echo WEBTITLE; ?></span></div>
                            <small data-toggle="modal" data-target="#versionmodal" style="vertical-align: super; font-size: small; cursor: pointer;"><i class="fa fa-copyright"></i> v<?php echo VERSION; ?></small>
                        </div>
                    </div>
                    <img src="<?php echo asset('bootstrap/theme/abefc969-a907-4243-a25d-7372a4997a21.jpg'); ?>" alt="Gambar Login" class="img-fluid rounded-5">
                </div>
            </section>
            
            <div class="col-md-12 col-lg-6 col-xl-6 shadow rounded-5" style="height: 100vh; overflow-y: auto;">
                
                <p class="mt-5 pt-2"><?php 
                if(isset($s) and $s != ""):
                    $action = BASEURL.'authforgot/'.$s;
                else:
                    $action = BASEURL.'authforgotpassword';
                endif;
                if(isset($_SESSION['alert'])){ echo $_SESSION['alert']; } ?></p>
                    
                <div class="d-flex justify-content-center">
                        
                    <div class="col-md-10 col-lg-10 col-xl-8">
                        <?php 
                        if(isset($s) and $s != ""):
                            if($data['s']['end_time'] > date('Y-m-d H:i:s')):
                        ?>
                                <p class="h3 text-left font-weight-bold mb-5"><span id="id-kata-sandi-baru" class="title-class" data-lang-id="id-kata-sandi-baru">Kata Sandi Baru.</span></p>
                                    
                                <form class="m-t" role="form" method="post" action="<?php echo $action; ?>">
                                    <!-- Password 1 input -->
                                    <?php echo forminput(['password', 'password1', 'password1', 'new password', 'off', 'required minlength="8"']); ?>

                                    <!-- Password 2 input -->
                                    <?php echo forminput(['password', 'password2', 'password-field', 'confirm new password', 'off', 'required minlength="8"'], ['group', 'right', '<button id="toggle-password" class="btn btn-outline-secondary" type="button"><i class="bi bi-eye-slash"></i></button>']); ?>

                                    <!-- Submit button -->
                                    <button type="submit" data-mdb-button-init data-mdb-ripple-init data-bs-theme="light" class="btn btn-danger btn-block mb-4 rounded-4"><span id="id-submit-new-password" class="title-class" data-lang-id="id-submit-new-password">Kirim Kata Sandi Baru</span></button>
                                </form>
                            <?php else: alert('warning', 'Attention..!', '<i class="fa fa-lock"></i> Link expired!', BASEURL.'forgot-password'); endif; 
                        else: ?>
                                <p class="h3 text-left font-weight-bold mb-5"><span id="id-lupa-kata-sandi" class="title-class" data-lang-id="id-lupa-kata-sandi">Lupa Kata Sandi.</span></p>
                                    
                                <form class="m-t" role="form" method="post" action="<?php echo $action; ?>">
                                    <!-- Email input -->
                                    <?php echo forminput(['email', 'email', 'email', 'email', 'off', 'required']); ?>

                                    <!-- Submit button -->
                                    <button type="submit" data-mdb-button-init data-mdb-ripple-init data-bs-theme="light" class="btn btn-danger btn-block mb-4 rounded-4"><span id="id-konfirmasi" class="title-class" data-lang-id="id-konfirmasi">Konfirmasi</span></button>
                                </form>
                        <?php endif; ?>

                            <!-- Register buttons -->
                            <div class="text-center">
                                <p><a href="<?php echo BASEURL.'login'; ?>" class="text-accent animated-link"><span id="id-kembali-masuk" class="title-class" data-lang-id="id-kembali-masuk">Kembali ke halaman Masuk</span></a></p>
                            </div>
                            <?php if (defined('ENVIRONMENT') && ENVIRONMENT === 'local'): ?>
                                <div class="text-center">
                                <button class="btn btn-sm btn-outline-success ms-2 rounded-3" onclick="simulasiPerubahan()">(<span id="pemicu-terjemahan" class="title-class" data-lang-id="pemicu-terjemahan">Pemicu Terjemahan</span>)</button>
                                </div>
                            <?php endif; ?>
                    </div>
                
                </div>
            </div>
        
        </div>
    </div>
<?php require_once view('footer'); ?>
