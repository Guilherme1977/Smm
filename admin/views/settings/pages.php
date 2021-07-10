<?php if(!route(4)): ?>
    <div class="col-md-8">
        <table class="table report-table dborder-1-solid-ddd">
            <thead>
                <tr>
                    <th>Page</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($pageList as $page): ?>
                    <tr>
                        <td class="col-md-6">
                            <?php echo $page["page_name"]; ?>
                        </td>
                        <td class="text-right col-md-1">
                            <div class="dropdown">
                                <a href="<?php echo site_url('admin/settings/pages/edit/'.$page["page_get"]) ?>" class="btn btn-primary btn-xs">Edit</a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php elseif( route(3) == "edit" ): ?>
        <style>
            .msg {
                background: #fefefe;
                color: #666666;
                font-weight: bold;
                font-size: small;
                padding: 12px;
                padding-left: 16px;
                border-top: solid 3px #CCCCCC;
                border-radius: 5px;
                margin-bottom: 10px;
                -webkit-box-shadow: 0 10px 10px -5px rgba(0, 0, 0, .08);
                -moz-box-shadow: 0 10px 10px -5px rgba(0, 0, 0, .08);
                box-shadow: 0 10px 10px -5px rgba(0, 0, 0, .08);
            }
            
            .msg-clear {
                border-color: #fefefe;
                -webkit-box-shadow: 0 7px 10px -5px rgba(0, 0, 0, .15);
                -moz-box-shadow: 0 7px 10px -5px rgba(0, 0, 0, .15);
                box-shadow: 0 7px 10px -5px rgba(0, 0, 0, .15);
            }
            
            .msg-info {
                border-color: #b8dbf2;
            }
            
            .msg-success {
                border-color: #cef2b8;
            }
            
            .msg-warning {
                border-color: rgba(255, 165, 0, .5);
            }
            
            .msg-danger {
                border-color: #ec8282;
            }
            
            .msg-primary {
                border-color: #9ca6f1;
            }
            
            .msg-magick {
                border-color: #e0b8f2;
            }
            
            .msg-info-text {
                color: #39b3d7;
            }
            
            .msg-success-text {
                color: #80d651;
            }
            
            .msg-warning-text {
                color: #db9e34;
            }
            
            .msg-danger-text {
                color: #c9302c;
            }
            
            .msg-primary-text {
                color: rgba(47, 106, 215, .9);
            }
            
            .msg-magick-text {
                color: #bb39d7;
            }
        </style>

        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form action="<?php echo site_url('admin/settings/pages/edit/'.route(4)) ?>" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label class="control-label">Page name</label>
                            <input type="text" class="form-control" readonly value="<?=$page["page_name"];?>">
                        </div>
                        <div class="form-group">
                            <label class="control-label">Content</label>
                            <textarea class="form-control" id="summernoteExample" rows="5" name="content" placeholder="">
                                <?php echo $page["page_content"]; ?>
                            </textarea>
                        </div>
                        <hr>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
        <?php endif; ?>