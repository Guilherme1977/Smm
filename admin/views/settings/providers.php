<div class="col-md-8">
    <div class="settings-header__table">
        <button type="button" class="btn btn-primary mg-b-20 pull-right" data-toggle="modal" data-target="#modalDiv" data-action="new_provider">Add New Provider</button>
    </div>
    <hr class="mg-t-50">
    <div class="panel panel-default">
        <?php foreach($providersList as $provider): ?>
            <div class="panel-body">
                <div class="form-group">
                    <label class="control-label">
                        <?php echo $provider["api_name"]; ?>
                            <button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#modalDiv" data-action="edit_provider" data-id="<?=$provider["id"]?>">Edit</button>
                            <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#confirmDelete<?=$provider["id"]?>">Delete</button>
                    </label>
                    <input type="text" class="form-control" readonly value="<?php echo $provider["api_key"]; ?>">
                </div>
            </div>
            <div class="modal modal-center fade" id="confirmDelete<?=$provider["id"]?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static">
                <div class="modal-dialog modal-dialog-center" role="document">
                    <div class="modal-content">
                        <div class="modal-body text-center">
                            <h4>Are you sure to delete?</h4>
                            <div align="center">
                            <a class="btn btn-primary" href="<?php echo site_url('admin/settings/providers/delete/'.$provider["id"]); ?>" id="confirmYes">Yes</a>
                                <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>