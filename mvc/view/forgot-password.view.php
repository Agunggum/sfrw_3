<?php if ( ! defined('APPPATH')) exit('No direct script access allowed'); ?>
<?php require_once view('header'); ?>
    <div class="col-md-12 col-lg-12 col-xl-12">
        <div class="row">
            
            <div class="col-md-12 col-lg-6 col-xl-6 shadow rounded-5" style="height: 100vh; overflow-y: auto;">
                
                <div class="text-center">
                    <div class="pt-2">
                        <span class="float-right">
                            <div class="form-check form-switch">
                                <label class="form-check-label mr-5" for="darkModeToggle"><i class="fas fa-sun"></i></label>
                                <input class="form-check-input" type="checkbox" role="switch" id="darkModeToggle">
                                <label class="form-check-label" for="darkModeToggle"><i class="fas fa-moon"></i></label>
                            </div>
                        </span>
                    </div>

                    <div class="d-flex justify-content-center">
                        <div class="col-md-12 col-lg-12 col-xl-12 mt-5 mb-4 text-left">
                            <div class="h3"><span><?php echo WEBTITLE; ?></span></div>
                            <small data-toggle="modal" data-target="#versionmodal" style="vertical-align: super; font-size: small; cursor: pointer;"><i class="fa fa-copyright"></i> v<?php echo VERSION; ?></small>
                        </div>
                    </div>
                
                    <p class="mt-5"><?php Logincontroller::forgotform($_SERVER['REQUEST_URI']); 
                    if(isset($_SESSION['alert'])){ echo $_SESSION['alert']; } ?></p>
                    
                    <div class="d-flex justify-content-center">
                        
                        <div class="col-md-8 col-lg-8 col-xl-8">
                            <?php if(empty($_SESSION['error']) or $_SESSION['error']=="true"){ ?>
                                <p class="h3 text-left font-weight-bold">Forgot password.</p>
                                
                                <form class="m-t" role="form" method="post" action="">
                                    <!-- Email input -->
                                     <?php echo forminput(['email', 'username', 'username', 'email', 'off', 'required']); ?>

                                    <!-- Submit button -->
                                    <button type="submit" value="MASUK" data-mdb-button-init data-mdb-ripple-init data-bs-theme="light" class="btn btn-primary btn-block mb-4">Confirm</button>

                                    <!-- Register buttons -->
                                    <div class="text-center">
                                        <p><a href="<?php echo BASEURL.'login'; ?>">Back to login</a></p>
                                    </div>
                                </form>
                            <?php } ?>
                        </div>
                    </div>
                
                </div>
            </div>
        
        </div>
    </div>
<?php require_once view('footer'); ?>
