<?php include 'header.php'; ?>

<div class="content-body">
    <div class="container pd-x-0">
        <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1 mg-b-10 dbg-none">
                        <li class="breadcrumb-item"><a href="#"><?= constant("HOME") ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Answer Ticket</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="col-md-12 mg-t-30 mg-b-30">
                        <div class="panel">
                            <div class="panel-heading edit-theme-title">
                                <div class="alert alert-outline alert-primary d-flex align-items-center" role="alert">
                                    <i data-feather="alert-circle" class="mg-r-5"></i> <?=$ticketMessage[0]["subject"]?>
                                </div>
                                <?php if( $ticketMessage[0]["canmessage"] == 1 ): ?>
                                    <div class="alert alert-warning d-flex align-items-center" role="alert">
                                        <span class="fa fa-lock"></span> &nbsp; Ticket is locked, so user cannot answer.
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="ticket-message__container">
                                                    <?php foreach($ticketMessage as $message): if( $message["support"] == 1 ): ?>
                                                    <div class="ticket-message__block ticket-message__client">
                                                        <div class="ticket-message">
                                                            <div class="ticket-message__title">
                                                                <div class="row">
                                                                    <div class="col-sm-6">
                                                                        <strong><?=$message["username"]?></strong>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <div class="ticket-message__title-date">
                                                                            <?=$message["time"]?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="ticket-message__text"><?=$message["message"]?></div>
                                                        </div>
                                                    </div>
                                                    <?php else: ?>
                                                    <div class="ticket-message__block ticket-message__support">
                                                        <div class="ticket-message">
                                                            <div class="ticket-message__title">
                                                                <div class="row">
                                                                    <div class="col-sm-6">
                                                                        <strong>You</strong>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <div class="ticket-message__title-date">
                                                                            <?=$message["time"]?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="ticket-message__text"><?=$message["message"]?></div>
                                                        </div>
                                                    </div>
                                                    <?php endif; endforeach; ?>
                                                </div>
                                                <form action="<?php echo site_url("admin/tickets/read/".$ticketMessage[0]["ticket_id"]) ?>" method="post">
                                                    <div class="col-md-12">
                                                        <div class="ticket-message-submit">
                                                            <textarea name="message" id="" cols="30" rows="5" class="form-control ticket-edit__textarea"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <button class="btn btn-primary click" type="submit">Send</button>
                                                        <div class="btn-group">
                                                            <?php if( $ticketMessage[0]["client_new"] == 1 ): ?>
                                                            <a href="<?php echo site_url("admin/tickets/unread/".$ticketMessage[0]["ticket_id"]) ?>" class="btn btn-outline-warning mg-r-5" data-toggle="tooltip" data-placement="top" title="" data-original-title="Set as Unread">Set as Unread</a>
                                                            <?php endif; if( $ticketMessage[0]["canmessage"] == 2 ): ?>
                                                            <a href="<?php echo site_url("admin/tickets/lock/".$ticketMessage[0]["ticket_id"]) ?>" class="btn btn-outline-warning mg-r-5" data-toggle="tooltip" data-placement="top" title="" data-original-title="Set as Locked">Set as Locked</a>
                                                            <?php else: ?>
                                                            <a href="<?php echo site_url("admin/tickets/unlock/".$ticketMessage[0]["ticket_id"]) ?>" class="btn btn-outline-warning mg-r-5" data-toggle="tooltip" data-placement="top" title="" data-original-title="Set as Unlocked">Set as Unlocked</a>
                                                            <?php endif; if( $ticketMessage[0]["status"] != "closed" ): ?>
                                                            <a href="<?php echo site_url("admin/tickets/close/".$ticketMessage[0]["ticket_id"]) ?>" class="btn btn-outline-warning" data-toggle="tooltip" data-placement="top" title="" data-original-title="Set as Closed">Set as Closed</a>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </form>
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
</div>
<?php include 'footer.php'; ?>