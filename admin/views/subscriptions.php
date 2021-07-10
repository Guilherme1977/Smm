<?php include 'header.php'; ?>

<div class="content-body">
    <div class="pd-x-0">
        <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1 mg-b-10 dbg-none">
                        <li class="breadcrumb-item"><a href="#"><?= constant("HOME") ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Subscriptions</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row row-xs">

            <div class="col">
                <div class="card dwd-100">
                    <div class="card-body pd-20 table-responsive dof-inherit">

                        <div class="container-fluid">
                            <ul class="nav nav-tabs mg-b-20 dborder-0">
                                <li class="<?php if( $status == "all"): echo "active"; endif; ?>"><a href="<?=site_url("admin/subscriptions")?>" class="btn btn-outline-light mg-r-5">All</a></li>
                                <li class="<?php if( $status == "active"): echo "active"; endif; ?>"><a href="<?=site_url("admin/subscriptions/1/active")?>" class="btn btn-outline-light mg-r-5">Active</a></li>
                                <li class="<?php if( $status == "paused"): echo "active"; endif; ?>"><a href="<?=site_url("admin/subscriptions/1/paused")?>" class="btn btn-outline-light mg-r-5">Paused</a></li>
                                <li class="<?php if( $status == "completed"): echo "active"; endif; ?>"><a href="<?=site_url("admin/subscriptions/1/completed")?>" class="btn btn-outline-light mg-r-5">Completed</a></li>
                                <li class="<?php if( $status == "canceled"): echo "active"; endif; ?>"><a href="<?=site_url("admin/subscriptions/1/canceled")?>" class="btn btn-outline-light mg-r-5">Canceled</a></li>
                                <li class="<?php if( $status == "expired"): echo "active"; endif; ?>"><a href="<?=site_url("admin/subscriptions/1/expired")?>" class="btn btn-outline-light mg-r-5">Expired</a></li>
                                <li class="<?php if( $status == "limit"): echo "active"; endif; ?>"><a href="<?=site_url("admin/subscriptions/1/limit")?>" class="btn btn-outline-light mg-r-5">Limit</a></li>
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
                                                            <button type="button" class="btn btn-primary btn-xs dropdown-toggle btn-xs-caret" data-toggle="dropdown"> Bulk actions</button>
                                                            <ul class="dropdown-menu">
                                                                <li>
                                                                    <?php if( $status  ==  "active" ): ?>
                                                                    <a class="bulkorder" data-type="paused">Paused All</a>
                                                                    <?php endif; ?>
                                                                    <?php if( $status  ==  "active" || $status  ==  "paused" ): ?>
                                                                    <a class="bulkorder" data-type="completed">Completed All</a>
                                                                    <?php endif; ?>
                                                                    <?php if( $status  ==  "active" || $status  ==  "paused" ): ?>
                                                                    <a class="bulkorder" data-type="canceled">Canceled All</a>
                                                                    <?php endif; ?>
                                                                    <?php if( $status  ==  "expired" || $status  ==  "paused" || $status  ==  "canceled" ): ?>
                                                                    <a class="bulkorder" data-type="active">Activate All</a>
                                                                    <?php endif; ?>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </th>
                                        <th>#</th>
                                        <th>User</th>
                                        <th>Username</th>
                                        <th>Amount</th>
                                        <th>Link</th>
                                        <th>Delay</th>
                                        <th class="dropdown-th">
                                            Service
                                        </th>
                                        <th>Status</th>
                                        <th>Creation Date</th>
                                        <th>Last Update</th>
                                        <th>End Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <form id="changebulkForm" action="<?php echo site_url("admin/subscriptions/multi-action") ?>" method="post">
                                    <tbody>
                                        <?php foreach( $orders as $order ): ?>
                                        <tr>
                                            <td><input type="checkbox" <?php if ($status == "all" || $status == "canceled"): echo "class=\"dborder-1-solid\" disabled"; else: echo 'class="selectOrder dborder-1-solid"'; endif; ?> name="order[<?php echo $order["order_id"] ?>]" value="1"></td>
                                            <td class="p-l"><?php echo $order["order_id"] ?></td>
                                            <td><?php echo $order["username"] ?></td>
                                            <td><?php echo $order["order_url"]; ?></td>
                                            <td><?php echo $order["subscriptions_min"]."-".$order["subscriptions_max"]; ?></td>
                                            <td><?php echo "<a href='".site_url("admin/orders?subscription=".$order["order_id"])."'>".$order["subscriptions_delivery"]."</a>/".$order["subscriptions_posts"]; ?></td>
                                            <td><?php if( $order["subscriptions_delay"] == 0 ): echo "Without delay"; else: echo $order["subscriptions_delay"]/60; echo " minutes"; endif; ?></td>
                                            <td><?php echo $order["service_name"]; ?></td>
                                            <td><?php echo orderStatu($order["subscriptions_status"]); ?></td>
                                            <td><?php echo date("d.m.Y H:i:s", strtotime($order["order_create"])); ?></td>
                                            <td><?php echo date("d.m.Y H:i:s", strtotime($order["last_check"])); ?></td>
                                            <td><?php if( $order["subscriptions_expiry"] != "1970-01-01" ): echo date("d.m.Y", strtotime($order["subscriptions_expiry"])); endif; ?></td>
                                            <td class="service-block__action">
                                                <div class="dropdown pull-right">
                                                    <button type="button" class="btn btn-primary btn-xs dropdown-toggle btn-xs-caret" data-toggle="dropdown">Action</button>
                                                    <ul class="dropdown-menu">
                                                        <?php if( $order["subscriptions_status"] == "active" || $order["subscriptions_status"] == "paused" ): ?>
                                                        <li><a href="#"  data-toggle="modal" data-target="#subsDiv" data-action="subscriptions_expiry" data-subs="1" data-id="<?php echo $order["order_id"] ?>">Set end date</a></li>
                                                        <?php endif; ?>
                                                        <?php if( $order["subscriptions_status"] == "active" ): ?>
                                                        <li><a href="#" data-toggle="modal" data-target="#confirmChange" data-href="<?=site_url("admin/subscriptions/subscriptions_pause/".$order["order_id"])?>">Stop subscription</a></li>
                                                        <?php endif; ?>
                                                        <?php if( $order["subscriptions_status"] == "paused" || $order["subscriptions_status"] == "active" ): ?>
                                                        <li><a href="#" data-toggle="modal" data-target="#confirmChange" data-href="<?=site_url("admin/subscriptions/subscriptions_complete/".$order["order_id"])?>">Complete subscription</a></li>
                                                        <?php endif; ?>
                                                        <?php if( $order["subscriptions_status"] == "paused" || $order["subscriptions_status"] == "expired" || $order["subscriptions_status"] == "canceled" ): ?>
                                                        <li><a href="#" data-toggle="modal" data-target="#confirmChange" data-href="<?=site_url("admin/subscriptions/subscriptions_active/".$order["order_id"])?>">Activate subscription</a></li>
                                                        <?php endif; ?>
                                                        <?php if( $order["subscriptions_status"] == "paused" || $order["subscriptions_status"] == "active" ): ?>
                                                        <li><a href="#" data-toggle="modal" data-target="#confirmChange" data-href="<?=site_url("admin/subscriptions/subscriptions_canceled/".$order["order_id"])?>">Cancel subscription</a></li>
                                                        <?php endif; ?>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <input type="hidden" name="bulkStatus" id="bulkStatus" value="0">
                                </form>
                            </table>
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

<script src="theme/assets/js/datatable/subs.js"></script>