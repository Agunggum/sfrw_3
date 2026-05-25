<?php if ( ! defined('APPPATH')) exit('No direct script access allowed'); ?>
<?php require_once view('header'); ?>
    <div class="col-md-12 col-lg-12 col-xl-12">
        <div class="row">
            
            <div class="col-md-12 col-lg-6 col-xl-6 shadow rounded-5" style="height: 100vh; overflow-y: auto;">
                
                <div class="text-center">
                    <div class="pt-2">
                    </div>

                    <div class="d-flex justify-content-center">
                        <div class="col-md-12 col-lg-12 col-xl-12 mt-5 mb-4 text-left">
                            <div class="h3"><span><?php echo WEBTITLE; ?></span></div>
                            <small data-toggle="modal" data-target="#versionmodal" style="vertical-align: super; font-size: small; cursor: pointer;"><i class="fa fa-copyright"></i> v<?php echo VERSION; ?></small>
                        </div>
                    </div>
                
                    <p class="mt-5"><?php 
                    if(isset($s) and $s != ""){
                        $action = BASEURL.'authforgot/'.$s;
                    } else {
                        $action = BASEURL.'authforgotpassword';
                    }
                    if(isset($_SESSION['alert'])){ echo $_SESSION['alert']; } ?></p>
                    
                    <div class="d-flex justify-content-center">
                        
                        <div class="col-md-8 col-lg-8 col-xl-8">
                                <?php if(isset($s) and $s != ""){ 
                                if($data['s']['end_time'] > date('Y-m-d H:i:s')){
                                ?>
                                    <p class="h3 text-left font-weight-bold">Reset password.</p>
                                    
                                    <form class="m-t" role="form" method="post" action="<?php echo $action; ?>">
                                        <!-- Password 1 input -->
                                        <?php echo forminput(['password', 'password1', 'password1', 'new password', 'off', 'required minlength="8"']); ?>

                                        <!-- Password 2 input -->
                                        <?php echo forminput(['password', 'password2', 'password-field', 'confirm new password', 'off', 'required minlength="8"'], ['group', 'right', '<button id="toggle-password" class="btn btn-outline-secondary" type="button"><i class="bi bi-eye-slash"></i></button>']); ?>

                                        <!-- Submit button -->
                                        <button type="submit" data-mdb-button-init data-mdb-ripple-init data-bs-theme="light" class="btn btn-danger btn-block mb-4 rounded-4">Submit New Password</button>
                                    </form>
                                <?php }else{ alert('warning', 'Attention..!', '<i class="fa fa-lock"></i> Link expired!', BASEURL.'forgot-password'); } } else { ?>
                                    <p class="h3 text-left font-weight-bold">Forgot password.</p>
                                    
                                    <form class="m-t" role="form" method="post" action="<?php echo $action; ?>">
                                        <!-- Email input -->
                                        <?php echo forminput(['email', 'email', 'email', 'email', 'off', 'required']); ?>

                                        <!-- Submit button -->
                                        <button type="submit" data-mdb-button-init data-mdb-ripple-init data-bs-theme="light" class="btn btn-danger btn-block mb-4 rounded-4">Confirm</button>
                                    </form>
                                <?php } ?>

                                <!-- Register buttons -->
                                <div class="text-center">
                                    <p><a href="<?php echo BASEURL.'login'; ?>" class="text-danger animated-link">Back to login</a></p>
                                </div>
                        </div>
                    </div>
                
                </div>
            </div>
        
        </div>
    </div>
<?php require_once view('footer'); ?>
