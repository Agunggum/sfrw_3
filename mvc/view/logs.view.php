<?php if ( ! defined('MODPATH')) exit('No direct script access allowed'); ?>
<div id="topbreadcrumb" class="row wrapper border-bottom bg-light page-heading">
    <div class="col-lg-12">
        <div class="h3"><?php echo $data['title']; ?></div>
        <ol id="linkbreadcrumb" class="breadcrumb bg-light">
            <li class="breadcrumb-item active h6">
                <i class="<?php echo $data['icon']; ?> fa-lg"></i> <em><?php echo $data['breadcrumb']; ?></em>
            </li>
        </ol>
    </div>
</div>

<div class="fh-breadcrumb">
    <div class="full-height">
        <div class="full-height-scroll">

            <div class="wrapper wrapper-content animated fadeInRight">
                <div id="contentform" class="row bg-light b-r-lg">
                    <div class="col-lg-12">
                        <div class="ibox">
                            
                            <div id="box-title" class="ibox-title border-0 bg-light">
                            </div>
                            
                            <div id="box-content" class="ibox-content border-0 p-0 bg-light">
                                
                                <div class="col-12 b-r-lg px-0 pt-3 h6">
                                    <table id='table-theme' class='table table-striped table-bordered table-hover display' cellspacing='0' style="width:100%">
                                        <thead>
                                            <tr>
                                                <th class="bg-light">Date</th>
                                                <th class="bg-light">IP Browser</th>
                                                <th class="bg-light">Information</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                        <?php
                                        $filelogs = (!isset($_GET['file'])) ?  "logrequest.log":$_GET['file'].".log";
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
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- END PAGE CONTENT -->