<?php include 'header.php'; ?>

<div class="content-body">
    <div class="pd-x-0">
        <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1 mg-b-10 dbg-none">
                        <li class="breadcrumb-item"><a href="#"><?= constant("HOME") ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Clients</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row row-xs">

            <div class="col">
                <div class="card dwd-100">
                    <div class="card-body pd-20 table-responsive dof-inherit">

                        <table class="table" id="dt">
                            <thead>
                                <tr>
                                    <th class="column-id">#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Username</th>
                                    <th>Balance</th>
                                    <th>Spent</th>
                                    <th nowrap="">Register Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($clients as $client ): ?>
                                    <tr class="<?php if( $client["client_type"] == 1 ): echo "grey "; endif; ?>">
                                        <td>
                                            <?php echo $client["client_id"] ?>
                                        </td>
                                        <td>
                                            <?php echo $client["name"] ?>
                                        </td>
                                        <td>
                                            <?php echo $client["email"] ?>
                                        </td>
                                        <td>
                                            <?php echo $client["username"] ?>
                                        </td>
                                        <td>
                                            <?php echo $settings["csymbol"] . $client["balance"] ?>
                                        </td>
                                        <td>
                                            <?php echo $settings["csymbol"] . $client["spent"] ?>
                                        </td>
                                        <td>
                                            <?php echo $client["register_date"] ?>
                                        </td>
                                        <td class="td-caret">
                                            <div class="dropdown pull-right">
                                                <button type="button" class="btn btn-primary btn-xs dropdown-toggle btn-xs-caret" data-toggle="dropdown">Action</button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dcs-pointer" data-toggle="modal" data-target="#modalDiv" data-action="edit_user" data-id="<?=$client["client_id"]?>">Edit User</a>
                                                    </li>
                                                    <li>
                                                        <a class="dcs-pointer" data-toggle="modal" data-target="#modalDiv" data-action="pass_user" data-id="<?=$client["client_id"]?>">Change Password</a>
                                                    </li>
                                                    <li>
                                                        <a class="dcs-pointer" data-toggle="modal" data-target="#modalDiv" data-action="secret_user" data-id="<?=$client["client_id"]?>">Edit Categories</a>
                                                    </li>
                                                    <li>
                                                        <a href="<?php echo site_url("admin/clients/change_apikey/".$client["client_id"]) ?>">Set New API Key</a>
                                                    </li>
                                                    <?php if( $client["client_type"] == 1 ): $type = "active"; else: $type = "deactive"; endif; ?>
                                                    <li>
                                                        <a href="<?php echo site_url("admin/clients/".$type."/".$client["client_id"]) ?>"><?php if( $client["client_type"] == 1 ): echo "Activate"; else: echo "Deactivate"; endif; ?> Account</a>
                                                    </li>
                                                    <li>
                                                        <a href="#" class="dcs-pointer <?php if( !countRow(["table"=>"clients_price","where"=>["client_id"=>$client["client_id"]] ]) ): echo "disabled"; endif; ?>" data-toggle="modal" data-target="#modalDiv" data-id="<?php echo $client["client_id"] ?>" data-action="price_user">Special Pricing (<?php echo countRow(["table"=>"clients_price","where"=>["client_id"=>$client["client_id"]] ]) ?>)</a>
                                                    </li>
                                                    <li>
                                                        <a href="<?php echo site_url("admin/clients/del_price/".$client["client_id"]) ?>">Reset Special Pricing</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>

<?php include 'footer.php'; ?>

<script src="theme/assets/js/datatable/clients.js"></script>