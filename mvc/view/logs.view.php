<?php if ( ! defined('MODPATH')) exit('No direct script access allowed'); ?>
<?php require_once view('header'); ?>
<body>
    <div class="container col-12 col-md-12 col-xl-12 col-lg-12">
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

            <div class="col-12 col-md-12 col-xl-12 col-lg-12">
                <section>
                    <table class="datatable table table-striped">
                        <thead>
                            <tr>
                                <th class="bg-light">Date</th>
                                <th class="bg-light">IP Browser</th>
                                <th class="bg-light">Information</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $filelogs = (!isset($_GET['file'])) ?  "error.log":$_GET['file'].".log";
                            $nom= 1;
                            $getfile = fopen('logs/'.$filelogs, 'r');
                            while($data = fgets($getfile))
                            {
                            $dataarr=explode(' ~ ',$data);
                            ?>
                            <tr>
                                <td><?php echo tgl_eng_to_format_timesec($dataarr[0]); ?></td>
                                <td><?php echo $dataarr[1]; ?> :: <?php echo $dataarr[2]; ?></td>
                                <td><?php echo $dataarr[3]; ?></td>
                            </tr>
                            <?php $nom++; } fclose($getfile); ?>

                        </tbody>
                    </table>
                </section>
            </div>
        </div>
    </div>
<?php require_once view('footer'); ?>