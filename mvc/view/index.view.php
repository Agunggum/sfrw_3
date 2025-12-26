<?php if ( ! defined('APPPATH')) exit('No direct script access allowed'); ?>
<?php require_once view('header'); ?>
    <div class="container col-12 col-xl-12 col-lg-12">
        <div class="row">
            <div class="col-xl-12 col-lg-12 pt-2">
                <span class="float-right">
                    <div class="form-check form-switch">
                        <label class="form-check-label mr-5" for="darkModeToggle"><i class="fas fa-sun"></i></label>
                        <input class="form-check-input" type="checkbox" role="switch" id="darkModeToggle">
                        <label class="form-check-label" for="darkModeToggle"><i class="fas fa-moon"></i></label>
                    </div>
                </span>
            </div>
            <div class="col-xl-8 col-lg-8 pt-5 mt-5">
                <p><font class="h2 font-weight-bold text-danger">sfrw</font> <small><?php echo VERSIONFRMAEWORK; ?></small></p>
                <div class="row">
                    <div class="col-lg-6 pb-2">
                        <div class="card">
                            <div class="card-body">
                                <p class="card-text">sfrw adalah framework <font class="text-danger">indonesia</font> yang dikembangkan oleh indonesia untuk programer atau calon programer <font class="text-danger">indonesia</font></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 pb-2">
                        <div class="card">
                            <div class="card-body">
                                <p class="card-text">Pelajari lebih lanjut.</p>
                                <a href="https://documentation.agunggum.id/" target="_blank" class="btn btn-outline-info">dokumentasi</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php require_once view('footer'); ?>
