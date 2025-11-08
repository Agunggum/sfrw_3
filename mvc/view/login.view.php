<?php if ( ! defined('APPPATH')) exit('No direct script access allowed'); ?>
<?php require_once view('header'); ?>
<body>

    <div class="col-md-12 col-lg-12">
        <div class="row">
            
            <div class="col-md-7 col-lg-5 shadow rounded-5" style="height: 100vh; overflow-y: auto;">
                
                <div class="text-center ibox-content border-0">
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
                        <div class="col-md-12 col-lg-9 mt-5 mb-4 text-left">
                            <div class="h3"><span><?php echo WEBTITLE; ?></span></div>
                            <small data-toggle="modal" data-target="#versionmodal" style="vertical-align: super; font-size: small; cursor: pointer;"><i class="fa fa-copyright"></i> v<?php echo VERSION; ?></small>
                        </div>
                    </div>
                
                    <p class="mt-5"><?php Logincontroller::loginform($_SERVER['REQUEST_URI']); 
                    if(isset($_SESSION['alert'])){ echo $_SESSION['alert']; } ?></p>
                    
                    <div class="d-flex justify-content-center">
                        
                        <div class="col-md-12 col-lg-9">
                            <?php if(empty($_SESSION['error']) or $_SESSION['error']=="true"){ ?>
                                <p class="h4 text-left font-weight-bold">Sign In.</p>
                                
                                <form class="m-t" role="form" method="post" action="">
                                    <!-- Email input -->
                                    <div data-mdb-input-init class="form-outline mb-4 text-left">
                                        <input type="username" name="username" id="username" class="form-control" placeholder="username or email" required />
                                    </div>

                                    <!-- Password input -->
                                    <div data-mdb-input-init class="form-outline mb-4 text-left">
                                        <input type="password" name="password" id="password" class="form-control" placeholder="password" required />
                                    </div>

                                    <!-- 2 column grid layout for inline styling -->
                                    <div class="row mb-4">
                                        <div class="col d-flex justify-content-center">
                                        <!-- Checkbox -->
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="rememberme" checked />
                                            <label class="form-check-label" for="rememberme"> Remember me </label>
                                        </div>
                                        </div>

                                        <div class="col">
                                        <!-- Simple link -->
                                        <a href="#!">Forgot password?</a>
                                        </div>
                                    </div>

                                    <!-- Submit button -->
                                    <button type="submit" value="MASUK" data-mdb-button-init data-mdb-ripple-init data-bs-theme="light" class="btn btn-primary btn-block mb-4">Sign in</button>

                                    <!-- Register buttons -->
                                    <div class="text-center">
                                        <p>Not a member? <a href="#!">Register</a></p>
                                        <p>or sign up with:</p>
                                        <button  type="button" data-mdb-button-init data-mdb-ripple-init class="btn btn-link btn-floating mx-1">
                                        <i class="fab fa-facebook-f"></i>
                                        </button>

                                        <button  type="button" data-mdb-button-init data-mdb-ripple-init class="btn btn-link btn-floating mx-1">
                                        <i class="fab fa-google"></i>
                                        </button>

                                        <button  type="button" data-mdb-button-init data-mdb-ripple-init class="btn btn-link btn-floating mx-1">
                                        <i class="fab fa-twitter"></i>
                                        </button>

                                        <button  type="button" data-mdb-button-init data-mdb-ripple-init class="btn btn-link btn-floating mx-1">
                                        <i class="fab fa-github"></i>
                                        </button>
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
