<?php include 'header.php'; ?>

<div class="content-body">
    <div class="pd-x-0">
        <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1 mg-b-10 dbg-none">
                        <li class="breadcrumb-item"><a href="#"><?= constant("HOME") ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Orders</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row row-xs">

            <div class="col">
                <div class="card dwd-100">
                    <div class="card-body pd-20 table-responsive dof-inherit">

                        <div class="container-fluid">
                            <form id="changebulkForm" action="<?php echo site_url("admin/orders/multi-action") ?>" method="post">
                                <ul class="nav nav-tabs mg-b-20 dborder-0">
                                    <li class="<?php if ($status == "all"): echo "active"; endif; ?>"><a href="<?=site_url("admin/orders")?>" class="btn btn-outline-light mg-r-5">All</a></li>
                                    <li class="<?php if ($status == "cronpending"): echo "active"; endif; ?>"><a href="<?=site_url("admin/orders/1/cronpending")?>" class="btn btn-outline-light mg-r-5">Cron Pending</a></li>
                                    <li class="<?php if ($status == "pending"): echo "active"; endif; ?>"><a href="<?=site_url("admin/orders/1/pending")?>" class="btn btn-outline-light mg-r-5">Pending</a></li>
                                    <li class="<?php if ($status == "inprogress"): echo "active"; endif; ?>"><a href="<?=site_url("admin/orders/1/inprogress")?>" class="btn btn-outline-light mg-r-5">Inprogress</a></li>
                                    <li class="<?php if ($status == "completed"): echo "active"; endif; ?>"><a href="<?=site_url("admin/orders/1/completed")?>" class="btn btn-outline-light mg-r-5">Completed</a></li>
                                    <li class="<?php if ($status == "partial"): echo "active"; endif; ?>"><a href="<?=site_url("admin/orders/1/partial")?>" class="btn btn-outline-light mg-r-5">Partial (Remaining has been refund)</a></li>
                                    <li class="<?php if ($status == "canceled"): echo "active"; endif; ?>"><a href="<?=site_url("admin/orders/1/canceled")?>" class="btn btn-outline-light mg-r-5">Canceled</a></li>
                                    <li class="<?php if ($status == "processing"): echo "active"; endif; ?>"><a href="<?=site_url("admin/orders/1/processing")?>" class="btn btn-outline-light mg-r-5">Processing</a></li>
                                    <li class="<?php if ($status == "fail"): echo "active"; endif; ?>"><a href="<?=site_url("admin/orders/1/fail")?>" class="btn btn-outline-light mg-r-5">Fail <span class="badge dfail"><?=$failCount?></span></a></li>
                                </ul>
                                <table class="table" id="dt">
                                    <thead>
                                        <tr>
                                            <th class="checkAll-th">
                                                <div class="checkAll-holder">
                                                    <input type="checkbox" id="checkAll">
                                                    <input type="hidden" id="checkAllText" value="order">
                                                </div>
                                                <div class="action-block">
                                                    <ul class="action-list">
                                                        <li><span class="countOrders"></span> orders selected</li>
                                                        <li>
                                                            <div class="dropdown">
                                                                <button type="button" class="btn btn-primary btn-xs dropdown-toggle btn-xs-caret" data-toggle="dropdown">Bulk actions</button>
                                                                <ul class="dropdown-menu">
                                                                    <li>
                                                                        <a class="bulkorder" data-type="completed">Completed</a>
                                                                        <a class="bulkorder" data-type="pending">Pending</a>
                                                                        <a class="bulkorder" data-type="inprogress">Inprogress</a>
                                                                        <a class="bulkorder" data-type="canceled">Canceled</a>
                                                                        <a class="bulkorder" data-type="fail">Re-send</a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </th>
                                            <th>#</th>
                                            <th>User</th>
                                            <th>Amount</th>
                                            <th>Link</th>
                                            <th>Start</th>
                                            <th>Quantity</th>
                                            <th class="dropdown-th">
                                                <div class="dropdown">
                                                    <button class="btn btn-th btn-primary dropdown-toggle" data-active="<?=$_GET["service_id"]?>" type="button" id="serviceList" data-href="admin/orders/counter" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Service
                                                
                                                    </button>
                                                    <ul class="dropdown-menu ddrop" aria-labelledby="dropdownMenu1" id="serviceListContent">
                                                    </ul>
                                                </div>
                                            </th>
                                            <th>Status</th>
                                            <th>Remaining</th>
                                            <th>Date</th>
                                            <th width="10%" class="dropdown-th">
                                                <div class="dropdown">
                                                    <button class="btn btn-th btn-primary dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Mode
                                                
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                                        <li class="<?php if (!$_GET["mode"]): echo "active"; endif; ?>"><a href="<?=site_url("admin/orders/1/".$status)?>">Tümü</a></li>
                                                        <li class="<?php if ($_GET["mode"] == "manuel"): echo "active"; endif; ?>"><a href="<?=site_url("admin/orders/1/".$status)?>?mode=manuel">Manuel</a></li>
                                                        <li class="<?php if ($_GET["mode"] == "auto"): echo "active"; endif; ?>"><a href="<?=site_url("admin/orders/1/".$status)?>?mode=auto">Auto</a></li>
                                                    </ul>
                                                </div>
                                            </th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($orders as $order): ?>
                                        <tr>
                                            <td><input type="checkbox" <?php if ($status == "all" || $status == "canceled"): echo "class=\"selectOrder dborder-1-solid\" "; else: echo 'class="selectOrder dborder-1-solid"'; endif; ?> name="order[<?php echo $order["order_id"] ?>]" value="1"></td>
                                            <td class="p-l">
                                                <?php echo $order["order_id"] ?>
                                                <?php if ($order["api_orderid"] != 0): echo '<div class="service-block__provider-value">'.$order["api_orderid"].'</div>'; endif; ?>
                                            </td>
                                            <td><?php echo $order["username"]; if ($order["order_where"] == "api"): echo ' <span class="label label-api">API</span>'; endif; ?> </td>
                                            <td class="service-block__minorder">
                                                <div>
                                                    <?php echo $order["order_charge"]; ?>
                                                </div>
                                                <?php if ($order["service_api"] != 0): echo '<div class="service-block__provider-value">'.$order["order_profit"].'</div>'; endif; ?>
                                            </td>
                                            <td><?php echo $order["order_url"]; ?></td>
                                            <td><?php echo $order["order_start"]; ?></td>
                                            <td><?php echo $order["order_quantity"]; ?></td>
                                            <td><?php echo $order["service_name"]; ?></td>
                                            <td><?php echo  orderStatu($order["order_status"], $order["order_error"], $order["order_detail"]); ?></td>
                                            <td><?php if ($order["order_status"] == "completed" && substr($order["order_remains"], 0, 1) == "-"): echo "+".substr($order["order_remains"], 1);  else: echo $order["order_remains"]; endif; ?></td>
                                            <td><?php echo $order["order_create"]; ?></td>
                                            <td><?php if ($order["api_service"] == 0): echo "Manuel"; else: echo "Auto"; endif; ?></td>
                                            <td class="service-block__action">
                                                <div class="dropdown pull-right">
                                                    <button type="button" class="btn btn-primary btn-xs dropdown-toggle btn-xs-caret" data-toggle="dropdown">Action</button>
                                                    <ul class="dropdown-menu">
                                                        <?php if ($order["order_error"] != "-" && $order["service_api"] != 0): ?>
                                                        <li><a href="#"  data-toggle="modal" data-target="#modalDiv" data-action="order_errors" data-id="<?php echo $order["order_id"] ?>">Error details</a></li>
                                                        <li><a href="<?=site_url("admin/orders/order_resend/".$order["order_id"])?>">Re-send</a></li>
                                                        <?php endif; ?>
                                                        <?php if ($order["order_error"] == "-" && $order["service_api"] != 0): ?>
                                                        <li><a href="#"  data-toggle="modal" data-target="#modalDiv" data-action="order_details" data-id="<?php echo $order["order_id"] ?>">Order details</a></li>
                                                        <?php endif; ?>
                                                        <?php if ($order["service_api"] == 0 || $order["order_error"] != "-"): ?>
                                                        <li><a href="#"  data-toggle="modal" data-target="#modalDiv" data-action="order_orderurl" data-id="<?php echo $order["order_id"] ?>">Set order url</a></li>
                                                        <?php endif; ?>
                                                        <?php if ($order["service_api"] == 0): ?>
                                                        <li><a href="#"  data-toggle="modal" data-target="#modalDiv" data-action="order_startcount" data-id="<?php echo $order["order_id"] ?>">Set start count</a></li>
                                                        <?php endif; ?>
                                                        <?php if ($order["order_status"] != "partial"): ?>
                                                        <li><a href="#"  data-toggle="modal" data-target="#modalDiv" data-action="order_partial" data-id="<?php echo $order["order_id"] ?>">Set remaining count</a></li>
                                                        <?php endif; ?>
                                                        <?php if (($order["order_status"]  ==  "pending" || $order["order_status"]  ==  "completed"  || $order["order_status"]  ==  "processing" || $order["order_status"]  ==  "partial" || $order["order_status"]  ==  "fail") or ($order["order_status"]  ==  "pending" || $order["order_status"]  ==  "inprogress"  || $order["order_status"]  ==  "processing")): ?>
                                                        <hr>
                                                        <h6 class="tx-uppercase tx-bold mg-l-20 tx-inverse">Edit order status</h6>
                                                        <?php endif; ?>
                                                        <?php if ($order["order_status"]  ==  "pending" || $order["order_status"]  ==  "completed"  || $order["order_status"]  ==  "processing" || $order["order_status"]  ==  "partial" || $order["order_status"]  ==  "fail"): ?>
                                                        <li><a href="#" data-toggle="modal" data-target="#confirmChange" data-href="<?=site_url("admin/orders/order_cancel/".$order["order_id"])?>">Cancel</a></li>
                                                        <?php endif; ?>
                                                        <?php if ($order["order_status"]  ==  "pending" || $order["order_status"]  ==  "inprogress"  || $order["order_status"]  ==  "processing"): ?>
                                                        <li><a href="#" data-toggle="modal" data-target="#confirmChange" data-href="<?=site_url("admin/orders/order_complete/".$order["order_id"])?>">Completed</a></li>
                                                        <?php endif; ?>
                                                        <?php if ($order["order_status"]  ==  "pending"  || $order["order_status"]  ==  "processing"): ?>
                                                        <li><a href="#" data-toggle="modal" data-target="#confirmChange" data-href="<?=site_url("admin/orders/order_inprogress/".$order["order_id"])?>">Inprogress</a></li>
                                                        <?php endif; ?>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <input type="hidden" name="bulkStatus" id="bulkStatus" value="0">
                            </form>
                        </div>
                        <div class="modal modal-center fade" id="confirmChange" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static">
                            <div class="modal-dialog modal-dialog-center" role="document">
                                <div class="modal-content">
                                    <div class="modal-body text-center">
                                        <h4>Are you sure to update?</h4>
                                        <div align="center">
                                            <a class="btn btn-primary" href="" id="confirmYes">Yes</a>
                                            <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
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

<script src="theme/assets/js/datatable/orders.js"></script>