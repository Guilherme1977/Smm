<!DOCTYPE html>
<html lang="en">

<head>
    <base href="<?=site_url()?>">

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <?php if($settings['favicon']): ?>
        <link rel="shortcut icon" type="image/ico" href="<?= $settings['favicon'] ?>" />
    <?php endif ?>

    <title><?php echo $title; ?></title>

    <link href="theme/lib/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="theme/lib/ionicons/css/ionicons.min.css" rel="stylesheet">
    <link href="theme/lib/jqvmap/jqvmap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="theme/assets/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="theme/assets/css/developerity.css">
    <link rel="stylesheet" href="theme/assets/css/developerity.custom.css">
    <link rel="stylesheet" href="theme/assets/css/developerity.modal.css">
    <link rel="stylesheet" href="theme/assets/css/developerity.otherstyles.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito+Sans:300,400%7cOpen+Sans:300,400,600%7cPT+Serif:400i">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="theme/admin/admin/html5shiv.min.js"></script>
    <script src="theme/admin/admin/respond.min.js"></script>
    <![endif]-->

    <link rel="stylesheet" type="text/css" href="theme/admin/admin/style.css">
    <link rel="stylesheet" type="text/css" href="theme/admin/admin/toastDemo.css">
    <link rel="stylesheet" type="text/css" href="theme/admin/datepicker/css/bootstrap-datepicker3.min.css">
    <link rel="stylesheet" type="text/css" href="theme/admin/admin/summernote.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="theme/admin/admin/tinytoggle.min.css" rel="stylesheet">
    <link rel="stylesheet" href="theme/admin/dist/assets/styles.css">
    <script>
        window.Promise ||
            document.write(
            '<script src="theme/admin/admin/polyfill.min.js"><\/script>'
        )
        window.Promise ||
            document.write(
            '<script src="theme/admin/admin/classList.min.js"><\/script>'
        )
        window.Promise ||
            document.write(
            '<script src="theme/admin/admin/findindex_polyfill_mdn.js"><\/script>'
        )
    </script>
    
    <script src="theme/assets/js/apexcharts.js"></script>

    <style media="screen">
      .grey {
        color: #c1c1c1;
      }
    </style>
</head>

<body class="dfont">
    <aside class="aside aside-fixed">
        <div class="aside-header">
            <a class="aside-logo dlogo" href="<?= $site['url'] ?>">
                SmmAdmin.
            </a>
            <a href="" class="aside-menu-link admin-menu-link">
                <i class="fas fa-bars"></i>
            </a>
        </div>
        <div class="aside-body dpt-0">
            <div class="aside-loggedin">
                <div class="aside-loggedin-user">
                    <h4 class="tx-semibold mg-b-0 dc-001737"><b>Administrator</b></h4>
                </div>
                <div class="collapse show" id="loggedinMenu">
                    <ul class="nav nav-aside mg-b-0">
                        <li class="nav-item"><a href="<?= site_url('account') ?>" class="nav-link"><i data-feather="settings"></i> <span><?= constant("ACCOUNT") ?></span></a></li>
                        <li class="nav-item"><a href="<?= site_url('logout') ?>" class="nav-link"><i data-feather="log-out"></i> <span><?= constant("LOGOUT") ?></span></a></li>
                    </ul>
                </div>
            </div>
            <ul class="nav nav-aside">
                <li class="nav-label">General</li>
                <li class="nav-item <?php if( route(1) == "home" ): echo 'active'; endif; ?>"><a class="nav-link" href="<?php echo site_url("admin/home") ?>">Home</a></li>
                <li class="nav-item <?php if( route(1) == "services" ): echo 'active'; endif; ?>"><a class="nav-link" href="<?php echo site_url("admin/services") ?>">Services</a></li>
                <li class="nav-item <?php if( route(1) == "tickets" ): echo 'active'; endif; ?>"><a class="nav-link" href="<?php echo site_url("admin/tickets") ?>">Tickets</a></li>
                
                <li class="nav-label mg-t-25">Payment</li>
                <li class="nav-item <?php if( route(1) == "payments" && route(2) == "bank" ): echo 'active'; endif; ?>"><a class="nav-link" href="<?php echo site_url("admin/payments/bank") ?>">Payment Notifications</a></li>
                <li class="nav-item <?php if( route(1) == "payments" && route(2) == "online" ): echo 'active'; endif; ?>"><a class="nav-link" href="<?php echo site_url("admin/payments/online") ?>">Online Payments</a></li>

                <li class="nav-label mg-t-25">Order</li>
                <li class="nav-item <?php if( route(1) == "orders" ): echo 'active'; endif; ?>"><a class="nav-link" href="<?php echo site_url("admin/orders") ?>">Orders</a></li>
                <li class="nav-item <?php if( route(1) == "subscriptions" ): echo 'active'; endif; ?>"><a class="nav-link" href="<?php echo site_url("admin/subscriptions") ?>">Subscriptions</a></li>
                <li class="nav-item <?php if( route(1) == "dripfeeds" ): echo 'active'; endif; ?>"><a class="nav-link" href="<?php echo site_url("admin/dripfeeds") ?>">Drip-Feeds</a></li>

                <li class="nav-label mg-t-25">User</li>
                <li class="nav-item <?php if( route(1) == "clients" ): echo 'active'; endif; ?>"><a class="nav-link" href="<?php echo site_url("admin/clients") ?>">Clients</a></li>
                <li class="nav-item"><a href="#" class="nav-link" data-toggle="modal" data-target="#modalDiv" data-action="new_user">New User</a></li>
                <li class="nav-item"><a href="#" class="nav-link" data-toggle="modal" data-target="#modalDiv" data-action="export_user">Export Users</a></li>
                <li class="nav-item"><a href="#" class="nav-link" data-toggle="modal" data-target="#modalDiv" data-action="alert_user">Alert User</a></li>
                <li class="nav-item"><a href="#" class="nav-link" data-toggle="modal" data-target="#modalDiv" data-action="all_numbers">Get Numbers & E-mails</a></li>

                <li class="nav-label mg-t-25">System</li>
                <li class="nav-item <?php if( route(1) == "plugins" ): echo 'active'; endif; ?>"><a class="nav-link" href="<?php echo site_url("admin/plugins") ?>">Plugins</a></li>
                <li class="nav-item <?php if( route(1) == "reports" ): echo 'active'; endif; ?>"><a class="nav-link" href="<?php echo site_url("admin/reports") ?>">Reports</a></li>
                <li class="nav-item <?php if( route(1) == "logs" ): echo 'active'; endif; ?>"><a class="nav-link" href="<?php echo site_url("admin/logs") ?>">Logs</a></li>
                <li class="nav-item <?php if( route(1) == "update" ): echo 'active'; endif; ?>"><a class="nav-link" href="<?php echo site_url("admin/update") ?>">Auto Update</a></li>
                <li class="nav-item <?php if( route(1) == "settings" ): echo 'active'; endif; ?>"><a class="nav-link" href="<?php echo site_url("admin/settings") ?>">Settings</a></li>
            </ul>
        </div>
    </aside>

    <div class="content ht-100v pd-0">
        <div class="content-header">
            <div class="content-search">
            </div>
            <nav class="nav">
                <a href="<?= site_url("logout") ?>" class="nav-link">Logout</a>
            </nav>
        </div>