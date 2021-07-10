<?php include 'header.php'; ?>

<div class="content-body">
    <div class="pd-x-0">
        <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1 mg-b-10 dbg-none">
                        <li class="breadcrumb-item"><a href="#"><?= constant("HOME") ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Tickets</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row row-xs">

            <div class="col">
                <div class="card dwd-100">
                    <div class="card-body pd-20 table-responsive dof-inherit">
                        <ul class="nav nav-tabs mg-l-10 pull-right dborder-0">
                            <li class="p-b mg-r-10"><button type="button" class="btn btn-primary dp-10" data-toggle="modal" data-target="#modalDiv" data-action="new_ticket">Create new</button></li>
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
                                                <li><span class="countOrders"></span> tickets selected</li>
                                                <li>
                                                    <div class="dropdown">
                                                        <button type="button" class="btn btn-primary btn-xs dropdown-toggle btn-xs-caret" data-toggle="dropdown"> Bulk actions</button>
                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                <a class="bulkorder" data-type="unread">Set all as Unread</a>
                                                                <a class="bulkorder" data-type="lock">Set all as Locked</a>
                                                                <a class="bulkorder" data-type="unlock">Set all as Unlocked</a>
                                                                <a class="bulkorder" data-type="close">Set all as Close</a>
                                                                <a class="bulkorder" data-type="pending">Set all as Pending</a>
                                                                <a class="bulkorder" data-type="answered">Set all as Answered</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </th>
                                    <th width="5%" class="p-l">#</th>
                                    <th width="15%">User</th>
                                    <th width="50%">Subject</th>
                                    <th width="10%" class="dropdown-th">
                                    <div class="dropdown">
                                        <button class="btn btn-th btn-warning dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                        Status
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                            <li class="active"><a href="<?=site_url("admin/tickets")?>">All (<?=countRow(["table"=>"tickets"]);?>)</a></li>
                                            <li><a href="<?=site_url("admin/tickets")?>?search=unread">Unreads</a></li>
                                            <li><a href="<?=site_url("admin/tickets")?>?status=pending">Pending (<?=countRow(["table"=>"tickets","where"=>["status"=>"pending"]]);?>)</a></li>
                                            <li><a href="<?=site_url("admin/tickets")?>?status=answered">Answered (<?=countRow(["table"=>"tickets","where"=>["status"=>"answered"]]);?>)</a></li>
                                            <li><a href="<?=site_url("admin/tickets")?>?status=closed">Closed (<?=countRow(["table"=>"tickets","where"=>["status"=>"closed"]]);?>)</a></li>
                                        </ul>
                                    </div>
                                    </th>
                                    <th width="10%">Creation Date</th>
                                    <th width="10%" nowrap="">Last Update</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <form id="changebulkForm" action="<?php echo site_url("admin/tickets/multi-action") ?>" method="post">
                                <tbody>
                                <?php foreach($tickets as $ticket ): ?>
                                    <tr>
                                        <td><input type="checkbox" class="selectOrder dborder-1-solid" name="ticket[<?php echo $ticket["ticket_id"] ?>]" value="1"></td>
                                        <td class="p-l"><?php echo $ticket["ticket_id"] ?></td>
                                        <td><?php echo $ticket["username"] ?></td>
                                        <td class="subject"><?php  if( $ticket["canmessage"] == 1 ): echo '<i class="fa fa-lock"></i> '; endif;  ?><a href="<?=site_url("admin/tickets/read/".$ticket["ticket_id"])?>"><?php if( $ticket["client_new"] == 2 ): echo "<b>".$ticket["subject"]."</b>"; else: echo $ticket["subject"]; endif; ?></a></td>
                                        <td><?php echo ticketStatu($ticket["status"]); ?></td>
                                        <td nowrap=""><?php echo $ticket["time"] ?></td>
                                        <td nowrap=""><?php echo $ticket["lastupdate_time"] ?></td>
                                        <td class="service-block__action">
                                        <div class="dropdown pull-right">
                                            <button type="button" class="btn btn-primary btn-xs dropdown-toggle btn-xs-caret" data-toggle="dropdown">Action</button>
                                            <ul class="dropdown-menu">
                                            <?php if( $ticket["client_new"] == 1 ): ?>
                                                <li><a href="<?php echo site_url("admin/tickets/unread/".$ticket["ticket_id"]) ?>">Set as Unread</a></li>
                                            <?php endif; if( $ticket["canmessage"] == 2 ): ?>
                                                <li><a href="<?php echo site_url("admin/tickets/lock/".$ticket["ticket_id"]) ?>">Set as Locked</a></li>
                                            <?php else: ?>
                                                <li><a href="<?php echo site_url("admin/tickets/unlock/".$ticket["ticket_id"]) ?>">Set as Unlocked</a></li>
                                            <?php endif; if( $ticket["status"] != "closed" ): ?>
                                                <li><a href="<?php echo site_url("admin/tickets/close/".$ticket["ticket_id"]) ?>">Set as Closed</a></li>
                                            <?php endif; ?>
                                                <li><a href="<?php echo site_url("admin/tickets/delete/".$ticket["ticket_id"]) ?>">Delete Ticket</a></li>
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

<script src="theme/assets/js/datatable/tickets.js"></script>