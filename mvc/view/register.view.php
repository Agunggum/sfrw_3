<?php if ( ! defined('APPPATH')) exit('No direct script access allowed'); ?>
<?php require_once view('header'); ?>
    <div class="col-md-12 col-lg-12 col-xl-12">
        <div class="row">
            
            <div class="col-md-12 col-lg-6 col-xl-6 shadow rounded-5 vh-100 overflow-y-auto">
                
                <div class="text-center">
                    <div class="pt-2">
                        <span id="id-pemicu" class="title-class" data-lang-id="id-pemicu"></span>
                    </div>

                    <div class="d-flex justify-content-center">
                        <div class="col-md-12 col-lg-12 col-xl-12 mt-5 mb-4 text-left">
                            <div class="h3"><span><?php echo WEBTITLE; ?></span></div>
                            <small data-toggle="modal" data-target="#versionmodal" style="vertical-align: super; font-size: small; cursor: pointer;"><i class="fa fa-copyright"></i> v<?php echo VERSION; ?></small>
                        </div>
                    </div>
                
                    <p class="mt-5"><?php 
                    if(isset($_SESSION['username'])){
                        alihkan(BASEURL);
                    }
                    if(isset($_SESSION['alert'])){ echo $_SESSION['alert']; } ?></p>
                    
                    <div class="d-flex justify-content-center">
                        
                        <div class="col-md-8 col-lg-8 col-xl-8">
                            <?php if(empty($_SESSION['error']) or $_SESSION['error']=="true"){ ?>
                                <p class="h3 text-left font-weight-bold"><span id="id-buat-akun" class="title-class" data-lang-id="id-buat-akun">Membuat akun.</span></p>
                                
                                <form class="m-t" role="form" method="post" action="<?php echo BASEURL.'authregister'; ?>">
                                    <input type="hidden" name="login" value="MASUK">
                                    <!-- Email input -->
                                    <?php echo forminput(['email', 'email', 'email', 'email', 'off', 'required']); ?>

                                    <!-- Email input -->
                                    <?php echo forminput(['text', 'username', 'username', 'username', 'off', 'required']); ?>

                                    <!-- Password input -->
                                    <?php echo forminput(['password', 'password', 'password-field', 'password', 'off', 'required'], ['group', 'right', '<button id="toggle-password" class="btn btn-outline-secondary" type="button"><i class="bi bi-eye-slash"></i></button>']); ?>

                                    <!-- 2 column grid layout for inline styling -->
                                    <div class="row mb-4">
                                        <div class="col">
                                        <!-- Simple link -->
                                        <a href="<?php echo BASEURL.'login'; ?>" data-bs-theme="light" class="text-danger animated-link"><span id="id-sudah-akun" class="title-class" data-lang-id="id-sudah-akun">Anda sudah punya akun?</span></a>
                                        </div>
                                    </div>

                                    <!-- Submit button -->
                                    <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-danger btn-block mb-4 rounded-4" data-bs-theme="auto"><span id="id-mendftar" class="title-class" data-lang-id="id-mendftar">Mendaftar</span></button>

                                    <!-- Register buttons -->
                                    <div class="text-center">
                                        <p><span id="id-or-sign-up" class="title-class" data-lang-id="id-or-sign-up">Atau daftar dengan:</span></p>
                                        <button  type="button" data-mdb-button-init data-mdb-ripple-init data-bs-theme="light" class="btn btn-link btn-floating mx-1">
                                        <i class="fab fa-google text-danger"></i>
                                        </button>
                                    </div>
                                </form>
                            <?php } ?>
                            <?php if (defined('ENVIRONMENT') && ENVIRONMENT === 'local'): ?>
                            <button class="btn btn-sm btn-outline-success ms-2 rounded-3" onclick="simulasiPerubahan()">(<span id="pemicu-terjemahan" class="title-class" data-lang-id="pemicu-terjemahan">Pemicu Terjemahan</span>)</button>
                            <?php endif; ?>
                        </div>
                    </div>
                
                </div>
            </div>
        
        </div>
    </div>
<?php require_once view('footer'); ?>
