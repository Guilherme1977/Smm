<div class="col-md-8">
    <div class="panel panel-default">
        <div class="panel-body">

            <form action="" method="post" enctype="multipart/form-data">

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-10">
                            <label for="preferenceLogo" class="control-label">Site Logo</label>
                            <input type="file" name="logo" id="preferenceLogo">
                        </div>
                        <div class="col-md-2">
                            <?php if( $settings["site_logo"] ):  ?>
                                <div class="setting-block__image">
                                    <img class="img-thumbnail" src="<?=$settings["site_logo"]?>">
                                    <div class="setting-block__image-remove">
                                        <a href="" data-toggle="modal" data-target="#confirmChange" data-href="<?=site_url("admin/settings/general/delete-logo")?>"><i class="far fa-trash-alt"></i></a>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-11">
                            <label for="preferenceFavicon" class="control-label">Site Favicon</label>
                            <input type="file" name="favicon" id="preferenceFavicon">
                        </div>
                        <div class="col-md-1">
                            <?php if( $settings["favicon"] ):  ?>
                                <div class="setting-block__image">
                                    <img class="img-thumbnail" src="<?=$settings["favicon"]?>">
                                    <div class="setting-block__image-remove">
                                        <a href="" data-toggle="modal" data-target="#confirmChange" data-href="<?=site_url("admin/settings/general/delete-favicon")?>"><i class="far fa-trash-alt"></i></a>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="form-group">
                    <label class="control-label">Site Name</label>
                    <input type="text" class="form-control" name="name" value="<?=$settings["site_name"]?>">
                </div>
                <div class="form-group">
                    <label for="" class="control-label">Site Title</label>
                    <input type="text" class="form-control" name="title" value="<?=$settings["site_title"]?>">
                </div>
                <div class="form-group">
                    <label for="" class="control-label">Site Keywords</label>
                    <input type="text" class="form-control" name="keywords" value="<?=$settings["site_keywords"]?>">
                </div>
                <div class="form-group">
                    <label class="control-label">Site Description</label>
                    <textarea class="form-control" rows="3" name="description" placeholder=''><?=$settings["site_description"]?></textarea>
                </div>
                <hr />
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="" class="control-label">Currency</label>
                        <input type="text" class="form-control" name="currency" value="<?=$settings["currency"]?>">
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="" class="control-label">Currency Symbol</label>
                        <input type="text" class="form-control" name="csymbol" value="<?=$settings["csymbol"]?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">Maintenance Mode</label>
                    <select class="form-control" name="site_maintenance">
                        <option value="2" <?=$settings["site_maintenance"]==2 ? "selected" : null; ?>>Inactive</option>
                        <option value="1" <?=$settings["site_maintenance"]==1 ? "selected" : null; ?>>Active</option>
                    </select>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="" class="control-label">E-Mail</label>
                        <input type="text" class="form-control" name="mail" value="<?=$settings["mail"]?>">
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="" class="control-label">Whatsapp Number</label>
                        <input type="text" class="form-control" name="whatsapp" value="<?=$settings["whatsapp"]?>">
                    </div>
                </div>
                <hr>
                <div class="form-group">
                    <label class="control-label">Recaptcha</label>
                    <select class="form-control" name="recaptcha">
                        <option value="2" <?=$settings["recaptcha"]==2 ? "selected" : null; ?>>Active</option>
                        <option value="1" <?=$settings["recaptcha"]==1 ? "selected" : null; ?>>Inactive</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="" class="control-label">Recaptcha site key</label>
                    <input type="text" class="form-control" name="recaptcha_key" value="<?=$settings["recaptcha_key"]?>">
                </div>
                <div class="form-group">
                    <label for="" class="control-label">Recaptcha secret key</label>
                    <input type="text" class="form-control" name="recaptcha_secret" value="<?=$settings["recaptcha_secret"]?>">
                </div>
                <hr>
                <div class="row">
                    <div class="form-group col-md-4">
                        <label class="control-label">Password Reset Page</label>
                        <select class="form-control" name="resetpass">
                            <option value="2" <?=$settings["resetpass_page"]==2 ? "selected" : null; ?> >Active</option>
                            <option value="1" <?=$settings["resetpass_page"]==1 ? "selected" : null; ?>>Inactive</option>
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label class="control-label">Password Reset with SMS</label>
                        <select class="form-control" name="resetsms">
                            <option value="2" <?=$settings["resetpass_sms"]==2 ? "selected" : null; ?> >Active</option>
                            <option value="1" <?=$settings["resetpass_sms"]==1 ? "selected" : null; ?>>Inactive</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label class="control-label">Password Reset with E-mail</label>
                        <select class="form-control" name="resetmail">
                            <option value="2" <?=$settings["resetpass_email"]==2 ? "selected" : null; ?> >Active</option>
                            <option value="1" <?=$settings["resetpass_email"]==1 ? "selected" : null; ?>>Inactive</option>
                        </select>
                    </div>
                </div>
                <hr>
                <div class="form-group">
                    <label class="control-label">Ticket System</label>
                    <select class="form-control" name="ticket_system">
                        <option value="2" <?=$settings["ticket_system"]==2 ? "selected" : null; ?> >Active</option>
                        <option value="1" <?=$settings["ticket_system"]==1 ? "selected" : null; ?>>Inactive</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="control-label">Registration Page</label>
                    <select class="form-control" name="registration_page">
                        <option value="2" <?=$settings["registration_page"]==2 ? "selected" : null; ?>>Active</option>
                        <option value="1" <?=$settings["registration_page"]==1 ? "selected" : null; ?>>Inactive</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="control-label">Service List</label>
                    <select class="form-control" name="service_list">
                        <option value="2" <?=$settings["service_list"]==2 ? "selected" : null; ?>>Active for All</option>
                        <option value="1" <?=$settings["service_list"]==1 ? "selected" : null; ?>>Active for registered users</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="control-label">Service Speed Indicator</label>
                    <select class="form-control" name="service_speed">
                        <option value="2" <?=$settings["service_speed"]==2 ? "selected" : null; ?>>Active</option>
                        <option value="1" <?=$settings["service_speed"]==1 ? "selected" : null; ?>>Inactive</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
            </form>
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