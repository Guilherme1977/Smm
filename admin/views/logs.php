<?php include 'header.php'; ?>

<div class="content-body">
    <div class="pd-x-0">
        <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1 mg-b-10 dbg-none">
                        <li class="breadcrumb-item"><a href="#"><?= constant("HOME") ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Logs</li>
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
                                            <div class="table-responsive">
                                                <table class="table table-striped" id="dt">
                                                    <thead>
                                                        <tr>
                                                            <th class="checkAll-th dwd-1">
                                                                <div class="checkAll-holder">
                                                                    <input type="checkbox" id="checkAll">
                                                                    <input type="hidden" id="checkAllText" value="order">
                                                                </div>
                                                                <div class="action-block dwd-1">
                                                                    <ul class="action-list dmg-5">
                                                                        <li><span class="countlogs"></span> logs selected</li>
                                                                        <li>
                                                                            <div class="dropdown">
                                                                                <button type="button" class="btn btn-default btn-xs dropdown-toggle btn-xs-caret" data-toggle="dropdown"> Bulk actions</button>
                                                                                <ul class="dropdown-menu">
                                                                                    <li>
                                                                                        <a class="bulkorder" data-type="delete">Delete All</a>
                                                                                    </li>
                                                                                </ul>
                                                                            </div>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </th>
                                                            <th>User</th>
                                                            <th>Detail</th>
                                                            <th>IP Address</th>
                                                            <th>Date</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <form id="changebulkForm" action="<?php echo site_url("admin/logs/multi-action") ?>" method="post">
                                                        <tbody>
                                                            <?php foreach ($logs as $log): ?>
                                                            <tr>
                                                                <td><input type="checkbox" class="selectOrder dborder-1-solid" name="log[<?php echo $log["id"] ?>]" value="1"></td>
                                                                <td><?php echo $log["username"] ?></td>
                                                                <td><?php echo $log["action"] ?></td>
                                                                <td><?php echo $log["report_ip"] ?></td>
                                                                <td><?php echo $log["report_date"] ?></td>
                                                                <td> <a href="<?php echo site_url("admin/logs/delete/".$log["id"]) ?>" class="df-12">Delete</a> </td>
                                                            </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                        <input type="hidden" name="bulkStatus" id="bulkStatus" value="0">
                                                    </form>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal modal-center fade" id="confirmChange" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static">
                            <div class="modal-dialog modal-dialog-center" role="document">
                                <div class="modal-content">
                                    <div class="modal-body text-center">
                                        <h4>Are you sure to update?</h4>
                                        <div align="center">
                                            <a class="btn btn-primary" href="" id="confirmYes">Yes</a>
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

<script src="theme/assets/js/datatable/logs.js"></script>