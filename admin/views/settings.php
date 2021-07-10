<?php include 'header.php'; ?>

<div class="content-body">
    <div class="pd-x-0">
        <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1 mg-b-10 dbg-none">
                        <li class="breadcrumb-item"><a href="#"><?= constant("HOME") ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Settings</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row row-xs">

            <div class="col">
                <div class="card dwd-100">
                    <div class="card-body pd-20 table-responsive dof-inherit">

                        <div class="container">
                            <div class="row">
                                <div class="col-md-2 col-md-offset-1">
                                    <ul class="nav nav-pills nav-stacked p-b">
                                        <?php foreach($menuList as $menuName => $menuLink): ?>
                                            <li class="settings_menus">
                                                <a class="btn btn-primary mg-b-10 dwd-140 <?php if( $route["2"] == $menuLink ): echo "dif"; endif; ?>" href="<?=site_url("admin/settings/".$menuLink)?>">
                                                    <?=$menuName?>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>

                                <?php if( $access ):
                                    include admin_view('settings/'.route(2));
                                else:
                                    include admin_view('settings/access');
                                endif; ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>