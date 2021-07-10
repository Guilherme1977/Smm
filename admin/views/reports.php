<?php include 'header.php'; ?>

<div class="content-body">
    <div class="pd-x-0">
        <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1 mg-b-10 dbg-none">
                        <li class="breadcrumb-item"><a href="#"><?= constant("HOME") ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Reports</li>
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
                                <li class="<?php if( $action == "profit" ): echo "active"; endif; ?>"><a href="<?php echo site_url("admin/reports") ?>" class="btn btn-outline-light mg-r-5">Profit from Orders</a></li>
                                <li class="<?php if( $action == "payments" ): echo "active"; endif; ?>"><a href="<?php echo site_url("admin/reports/payments") ?>" class="btn btn-outline-light mg-r-5">Profit from Payments</a></li>
                                <li class="<?php if( $action == "orders" ): echo "active"; endif; ?>"><a href="<?php echo site_url("admin/reports/orders") ?>" class="btn btn-outline-light mg-r-5">Total Order</a></li>
                            </ul>
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table report-table dborder-1-solid-ddd">
                                        <thead>
                                            <tr>
                                                <th>
                                                </th>
                                                <th align="right" class="dta-center">January</th>
                                                <th align="right" class="dta-center">February</th>
                                                <th align="right" class="dta-center">March</th>
                                                <th align="right" class="dta-center">April</th>
                                                <th align="right" class="dta-center">May</th>
                                                <th align="right" class="dta-center">June</th>
                                                <th align="right" class="dta-center">July</th>
                                                <th align="right" class="dta-center">August</th>
                                                <th align="right" class="dta-center">September</th>
                                                <th align="right" class="dta-center">October</th>
                                                <th align="right" class="dta-center">November</th>
                                                <th align="right" class="dta-center">December</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if( $action == "profit" ): ?>
                                            <?php for ($day=1; $day <=31; $day++): ?>
                                            <tr>
                                                <td align="center"><?=$day?></td>
                                                <?php for( $month=1; $month<=12; $month++ ): ?>
                                                <td align="center">
                                                    <?php echo dayCharge($day,$month,$year); ?>
                                                </td>
                                                <?php endfor; ?>
                                            </tr>
                                            <?php endfor; ?>
                                            <tr>
                                                <td align="center"><b>Gross profit: </b></td>
                                                <?php for( $month=1; $month<=12; $month++ ): ?>
                                                <td align="center">
                                                    <b>  <?php echo monthCharge($month,$year); ?> </b>
                                                </td>
                                                <?php endfor; ?>
                                            </tr>
                                            <tr>
                                                <td align="center"><b>Net profit: </b></td>
                                                <?php for( $month=1; $month<=12; $month++ ): ?>
                                                <td align="center">
                                                    <b>  <?php echo monthChargeNet($month,$year); ?> </b>
                                                </td>
                                                <?php endfor; ?>
                                            </tr>
                                            <?php elseif( $action == "payments" ): ?>
                                            <?php for ($day=1; $day <=31; $day++): ?>
                                            <tr>
                                                <td align="center"><?=$day?></td>
                                                <?php for( $month=1; $month<=12; $month++ ): ?>
                                                <td align="center">
                                                    <?php echo dayPayments($day,$month,$year); ?>
                                                </td>
                                                <?php endfor; ?>
                                            </tr>
                                            <?php endfor; ?>
                                            <tr>
                                                <td align="center"><b>Total: </b></td>
                                                <?php for( $month=1; $month<=12; $month++ ): ?>
                                                <td align="center">
                                                    <b>  <?php echo monthPayments($month,$year); ?> </b>
                                                </td>
                                                <?php endfor; ?>
                                            </tr>
                                            <?php elseif( $action == "orders" ): ?>
                                            <?php for ($day=1; $day <=31; $day++): ?>
                                            <tr>
                                                <td align="center"><?=$day?></td>
                                                <?php for( $month=1; $month<=12; $month++ ): ?>
                                                <td align="center">
                                                    <?php echo dayOrders($day,$month,$year); ?>
                                                </td>
                                                <?php endfor; ?>
                                            </tr>
                                            <?php endfor; ?>
                                            <tr>
                                                <td align="center"><b>Total: </b></td>
                                                <?php for( $month=1; $month<=12; $month++ ): ?>
                                                <td align="center">
                                                    <b>  <?php echo monthOrders($month,$year); ?> </b>
                                                </td>
                                                <?php endfor; ?>
                                            </tr>
                                            <?php endif; ?>
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

<?php include 'footer.php'; ?>