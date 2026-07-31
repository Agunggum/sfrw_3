<?php if ( ! defined('APPPATH')) exit('No direct script access allowed'); ?>
<?php require_once view('header'); ?>
    <div class="col-md-12 col-lg-12 col-xl-12">
        <div class="row">

        <section class="col-md-12 col-lg-6 col-xl-6 vh-100 overflow-y-auto d-none d-lg-block d-xl-block" data-bs-theme="light">
                <div class="container">
                    <div class="pt-2">
                        <span id="id-pemicu" class="title-class" data-lang-id="id-pemicu"></span>
                        <a href="<?php echo site_url('/'); ?>" class="text-danger animated-link"><i class="bi bi-arrow-left"></i> <span id="id-kembali" class="title-class" data-lang-id="id-kembali">Kembali</span></a>
                    </div>
                        
                    <div class="d-flex justify-content-center">
                        <div class="col-md-12 col-lg-12 col-xl-12 mt-3 mb-1 text-left">
                            <div class="h3"><span><?php echo WEBTITLE; ?></span><small data-toggle="modal" data-target="#versionmodal" style="vertical-align: super; font-size: small; cursor: pointer;"><i class="bi bi-copyright"></i> v<?php echo VERSION; ?></small></div>
                        </div>
                    </div>
                    <img src="<?php echo asset('bootstrap/theme/abefc969-a907-4243-a25d-7372a4997a21.jpg'); ?>" alt="Gambar Login" class="img-fluid rounded-5">
                </div>
            </section>
            
            <section class="col-md-12 col-lg-6 col-xl-6 shadow rounded-5 vh-100 overflow-y-auto">

                <div class="d-flex justify-content-center d-block d-lg-none d-xl-none">
                    <div class="pt-2">
                        <span id="id-pemicu" class="title-class" data-lang-id="id-pemicu"></span>
                        <a href="<?php echo site_url('/'); ?>" class="text-accent animated-link"><i class="bi bi-arrow-left"></i> <span id="id-kembali" class="title-class" data-lang-id="id-kembali">Kembali</span></a>
                    </div>
                    <div class="mt-5 mb-2">
                        <div class="h3 text-center"><?php echo WEBTITLE; ?></div>
                    </div>
                </div>
                
                <p class="mt-sm-1 mt-md-1 mt-lg-5 mt-xl-5 pt-lg-5 pt-xl-5"><?php 
                if(isset($_SESSION['username'])){
                    alihkan(site_url('/'));
                }
                if(isset($_SESSION['alert'])){ echo $_SESSION['alert']; } ?></p>
                    
                <div class="d-flex justify-content-center">
                        
                    <div class="col-md-10 col-lg-10 col-xl-8">
                        <?php if(empty($_SESSION['error']) or $_SESSION['error']=="true"){ ?>
                            <p class="h3 text-left font-weight-bold mb-5"><span id="id-buat-akun" class="title-class" data-lang-id="id-buat-akun">Membuat akun.</span></p>
                                
                            <form class="m-t" role="form" method="post" action="<?php echo site_url('authregister'); ?>">
                                <input type="hidden" name="login" value="MASUK">
                                <!-- Email input -->
                                <?php echo forminput(['email', 'email', 'email', 'email', 'off', 'required']); ?>

                                <!-- Email input -->
                                <?php echo forminput(['text', 'username', 'username', 'username', 'off', 'required pattern="^[a-zA-Z0-9]+$" 
    title="Hanya diperbolehkan huruf (a-z, A-Z) dan angka (0-9)"']); ?>

                                <!-- Password input -->
                                <?php echo forminput(['password', 'password', 'password-field', 'password', 'off', 'required'], ['group', 'right', '<button id="toggle-password" class="btn btn-outline-secondary" type="button"><i class="bi bi-eye-slash"></i></button>']); ?>

                                <!-- 2 column grid layout for inline styling -->
                                <div class="row mb-4">
                                    <div class="col">
                                        <!-- Checkbox -->
                                        <?php echo formcheck(['Dengan ini saya menerima syarat dan ketentuan mengenai pendaftaran akun'], ['terms', 'required']); ?>
                                    </div>
                                </div>

                                <!-- Submit button -->
                                <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-danger btn-block mb-4 rounded-4" data-bs-theme="auto"><span id="id-mendaftar" class="title-class" data-lang-id="id-mendaftar">Mendaftar</span></button>

                                <!-- Register buttons -->
                                <div class="text-center">
                                    <a href="<?php echo site_url('login'); ?>" data-bs-theme="light" class="text-danger animated-link"><span id="id-sudah-akun" class="title-class" data-lang-id="id-sudah-akun">Anda sudah punya akun?</span></a>
                                    <p><span id="id-or-sign-up" class="title-class" data-lang-id="id-or-sign-up">Atau daftar dengan:</span></p>
                                    <button  type="button" data-mdb-button-init data-mdb-ripple-init data-bs-theme="light" class="btn btn-link btn-floating mx-1">
                                    <i class="bi bi-google text-danger"></i>
                                    </button>
                                </div>
                            </form>
                        <?php } ?>
                        <?php if (defined('ENVIRONMENT') && ENVIRONMENT === 'local'): ?>
                            <div class="text-center">
                            <button class="btn btn-sm btn-outline-success ms-2 rounded-3" onclick="simulasiPerubahan()">(<span id="pemicu-terjemahan" class="title-class" data-lang-id="pemicu-terjemahan">Pemicu Terjemahan</span>)</button>
                            </div>
                        <?php endif; ?>
                    </div>
                
                </div>
            </section>
        
        </div>
    </div>
<?php require_once view('footer'); ?>
