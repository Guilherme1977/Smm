<?php include 'header.php'; ?>

<div class="content-body">
    <div class="pd-x-0">
        <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1 mg-b-10 dbg-none">
                        <li class="breadcrumb-item"><a href="#"><?= constant("HOME") ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page">update</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row row-xs">

            <div class="col">
                <div class="card dwd-100">
                    <div class="card-body pd-20 table-responsive dof-inherit">

                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="panel panel-default">
                                        <div class="panel-body">
                                            <div class="alert alert-warning">This page has been developed to update the script automatically with one click.</div>

                                            <div class="alert alert-warning">Make sure to take a full backup before proceeding. PHP is not a fully trusted programming language because it runs web-based. We explained that we do not accept responsibility.<p><br>

                                            Why is PHP not reliable ?:<br>
                                            * It is web based. It does not have the power to control everything.<br>
                                            * When there is an interruption on your server's internet connection, auto update functions cannot continue. In that case, files are transferred missing.<br>
                                            * Timeout times are very limited.<br>
                                            * And much more.<p><br>

                                            In summary, backup is always important.</div><p><br>

                                            <button type="button" class="btn btn-primary" id="letsbtn" data-toggle="modal" data-target="#confirmUpdate">Let's do it!</button>

                                            <div id="loadico" style="display:none"><button class="btn btn-primary" type="button" disabled><span class="spinner-grow" role="status" aria-hidden="true"></span><span class="sr-only">Loading...</span></button> <button class="btn btn-primary" type="button" disabled>Proceed...</button></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal modal-center fade" id="confirmUpdate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static">
                            <div class="modal-dialog modal-dialog-center" role="document">
                                <div class="modal-content">
                                    <div class="modal-body text-center">
                                        <h4>Are you sure to continue?</h4>
                                        <div align="center">
                                            <a class="btn btn-primary anyway" href="<?php echo site_url('admin/update/now'); ?>" id="confirmYes">Yes</a>
                                            <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
<script>$(document).on("click",".anyway",function(){ $("#loadico").toggle(); $("#letsbtn").hide(); $("#confirmUpdate").hide(); });</script>
<script src="theme/assets/js/datatable/update.js"></script>