<?php include 'header.php'; ?>

<div class="content-body">
    <div class="pd-x-0">
        <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1 mg-b-10 dbg-none">
                        <li class="breadcrumb-item"><a href="#"><?= constant("HOME") ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Payment Notifications</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row row-xs">

            <div class="col">
                <div class="card dwd-100">
                    <div class="card-body pd-20 table-responsive dof-inherit">

                        <div class="container-fluid pd-t-20 pd-b-20">
                            <ul class="nav nav-tabs pd-b-20 dborder-0">
                                <li class="<?php if( $status == "all"): echo "active"; endif; ?>"><a href="<?=site_url("admin/payments/bank")?>" class="btn btn-outline-light mg-r-5">All Payments</a></li>
                                <li class="<?php if( $status == "pending"): echo "active"; endif; ?>"><a href="<?=site_url("admin/payments/bank/1/pending")?>" class="btn btn-outline-light mg-r-5">Pending Payments</a></li>
                                <li class="<?php if( $status == "completed"): echo "active"; endif; ?>"><a href="<?=site_url("admin/payments/bank/1/completed")?>" class="btn btn-outline-light mg-r-5">Completed Payments</a></li>
                                <li class="<?php if( $status == "canceled"): echo "active"; endif; ?>"><a href="<?=site_url("admin/payments/bank/1/canceled")?>" class="btn btn-outline-light mg-r-5">Canceled Payments</a></li>
                            </ul>
                            <ul class="nav nav-tabs pull-right dborder-0">
                                <li class="export-li">
                                    <button class="btn btn-primary dp-10" data-toggle="modal" data-target="#modalDiv" data-action="payment_banknew">
                                        <span class="export-title">New Payment</span>
                                    </button>
                                </li>
                            </ul>
                            <table class="table" id="dt">
                                <thead>
                                    <tr>
                                        <th class="p-l">#</th>
                                        <th>User</th>
                                        <th>Amount</th>
                                        <th>Bank</th>
                                        <th>Sender Name</th>
                                        <th>Status</th>
                                        <th>Note</th>
                                        <th>Creation Date</th>
                                        <th>Last Update</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <form id="changebulkForm" action="<?php echo site_url("admin/payments/bank/multi-action") ?>" method="post">
                                    <tbody>
                                        <?php foreach($payments as $payment ): ?>
                                        <tr>
                                            <td class="p-l"><?php echo $payment["payment_id"] ?></td>
                                            <td><?php echo $payment["username"] ?></td>
                                            <td><?php echo $payment["payment_amount"] ?></td>
                                            <td><?php echo $payment["bank_name"] ?></td>
                                            <td><?php $extra = json_decode($payment["payment_extra"]); echo $extra->payment_gonderen; ?></td>
                                            <td><?php echo paymentStatu($payment["payment_status"]); ?></td>
                                            <td><?php echo $payment["payment_note"] ?></td>
                                            <td nowrap=""><?php echo $payment["payment_create_date"] ?></td>
                                            <td nowrap=""><?php echo $payment["payment_update_date"] ?></td>
                                            <td class="service-block__action">
                                                <div class="dropdown pull-right">
                                                    <button type="button" class="btn btn-primary btn-xs dropdown-toggle btn-xs-caret" data-toggle="dropdown">Action</button>
                                                    <ul class="dropdown-menu">
                                                        <li><a href="#"  data-toggle="modal" data-target="#modalDiv" data-action="payment_bankedit" data-id="<?php echo $payment["payment_id"] ?>">Edit Payment</a></li>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

<script src="theme/assets/js/datatable/payments_bank.js"></script>