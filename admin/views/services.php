<?php include 'header.php'; ?>

<div class="content-body">
    <div class="pd-x-0">
        <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1 mg-b-10 dbg-none">
                        <li class="breadcrumb-item"><a href="#"><?= constant("HOME") ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Services</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row row-xs">

            <div class="col">
                <div class="card dwd-100">

                    <div class="container-fluid pd-t-20">
                        <ul class="nav nav-tabs nav-tabs__service pull-right mg-b-20 dborder-0">
                            <li class="p-b mg-r-10"><button class="btn btn-primary" data-toggle="modal" data-target="#modalDiv" data-action="new_service" >New Service</button></li>
                            <li class="p-b mg-r-10"><button class="btn btn-primary m-l" data-toggle="modal" data-target="#modalDiv" data-action="new_subscriptions">New Subscription</button></li>
                            <li class="p-b mg-r-10"><button class="btn btn-primary m-l" data-toggle="modal" data-target="#modalDiv" data-action="new_category">New Category</button></li>
                            <li class="mg-r-10">
                                <button class="btn btn-primary m-l" data-toggle="modal" data-target="#modalDiv" data-action="import_services">Get Services</button>
                            </li>
                            <li>
                                <div class="form-inline"><label for="service-search-input" class="service-search__icon"></label>
                                <input class="form-control" placeholder="Search Service" id="priceService" type="text" value="">
                                </div>
                            </li>
                        </ul>
                        <ul></ul>
                        <div class="sticker-head">
                            <table class="service-block__header" id="sticker">
                                <thead>
                                    <th class="checkAll-th service-block__checker null">
                                        <div class="checkAll-holder">
                                            <input type="checkbox" id="checkAll">
                                            <input type="hidden" id="checkAllText" value="order">
                                        </div>
                                        <div class="action-block">
                                            <ul class="action-list">
                                            <li><span class="countOrders"></span> services selected</li>
                                            <li>
                                                <div class="dropdown">
                                                    <button type="button" class="btn btn-primary btn-xs dropdown-toggle btn-xs-caret" data-toggle="dropdown"> Bulk actions</button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="bulkorder" data-type="active">Activate All</a>
                                                            <a class="bulkorder" data-type="deactive">Deactivate All</a>
                                                            <a class="bulkorder" data-type="secret">Make All Services Secret</a>
                                                            <a class="bulkorder" data-type="desecret">Make All Services Desecret</a>
                                                            <a class="bulkorder" data-type="del_price">Delete All Special Pricing</a>
                                                            <a class="bulkorder" data-type="del_services">Delete All Services</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </li>
                                            </ul>
                                        </div>
                                    </th>
                                    <th class="service-block__id">#</th>
                                    <th class="service-block__service">Service Name</th>
                                    <th class="service-block__type">
                                        Service Type
                                    </th>
                                    <th class="service-block__provider">Provider</th>
                                    <th class="service-block__rate">Pricing</th>
                                    <th class="service-block__minorder">Min</th>
                                    <th class="service-block__minorder">Max</th>
                                    <th class="service-block__visibility">Status</th>
                                    <th class="service-block__action text-right"><span id="allServices" class="service-block__hide-all fa fa-compress"></span></th>
                                    </tr>
                                </thead>
                            </table>
                        </div>

                        <div class="service-block__body">
                            <div class="service-block__body-scroll">
                                    <div class="dwh"></div>
                                    <form action="<?php echo site_url("admin/services/multi-action") ?>" method="post" id="changebulkForm">
                                    <div class="category-sortable">
                                    <?php $c=0;foreach($serviceList as $category => $services ): $c++; ?>
                                        <div class="categories" data-id="<?=$services[0]["category_id"]?>">
                                        <div class="<?php if( $services[0]["category_type"] == 1 ): echo 'grey'; endif;?>  service-block__category ">
                                            <div class="service-block__category-title"  class="categorySortable" data-category="<?=$category?>" id="category-<?=$c?>">
                                                <div class="service-block__drag handle">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                    <title>Drag-Handle</title>
                                                    <path d="M7 2c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm0 6c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm0 6c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm6-8c1.104 0 2-.896 2-2s-.896-2-2-2-2 .896-2 2 .896 2 2 2zm0 2c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm0 6c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2z"></path>
                                                </svg>
                                                </div>
                                                <?php if( $services[0]["category_secret"] == 1 ): echo '<small data-toggle="tooltip" data-placement="top" title="" data-original-title="secret category"><i class="fa fa-lock"></i></small> '; endif; echo $category; ?>
                                                <div class="dropdown-inline">
                                                <div class="dropdown">
                                                    <button type="button" class="btn btn-primary btn-xs dropdown-toggle btn-xs-caret" data-toggle="dropdown">Action</button>
                                                    <ul class="dropdown-menu">
                                                    <li><a class="dcs-pointer"  data-toggle="modal" data-target="#modalDiv" data-action="edit_category" data-id="<?=$services[0]["category_id"]?>">Edit Category</a></li>
                                                        <?php if( $services[0]["category_type"] == 1 ): $type = "category-active"; else: $type = "category-deactive"; endif; ?>
                                                    <li><a href="<?php echo site_url("admin/services/".$type."/".$services[0]["category_id"]) ?>"><?php if( $services[0]["category_type"] == 1 ): echo "Activate"; else: echo "Deactivate"; endif; ?> Category</a></li>
                                                    <li><a href="<?php echo site_url("admin/services/del_category/".$services[0]["category_id"]) ?>">Delete Category</a></li>
                                                    </ul>
                                                </div>
                                                </div>
                                                <?php if( !empty($services[0]["service_id"]) ): ?>

                                                <div class="service-block__collapse-block"><div id="collapedAdd-<?=$c?>" class="service-block__collapse-button" data-category="category-<?=$c?>"></div></div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="collapse in">
                                                <div class="service-block__packages">
                                                <table id="servicesTableList" class="Servicecategory-<?=$c?>">
                                                    <tbody class="service-sortable">
                                                        <div class="serviceSortable" id="Servicecategory-<?=$c?>" data-id="category-<?=$c?>">
                                                            <?php for($i=0;$i<count($services);$i++): $api_detail = json_decode($services[$i]["api_detail"],true); ?>
                                                            <tr id="serviceshowcategory-<?=$c?>" class="ui-state-default <?php if( $services[$i]["service_type"] == 1 ): echo "grey"; endif; ?>"  data-category="category-<?=$c?>" data-id="service-<?php echo $services[$i]["service_id"] ?>" data-service="<?php echo $services[$i]["service_name"] ?>">
                                                                <?php if( !empty($services[0]["service_id"])  ):?>
                                                                    <td class="service-block__checker">
                                                                    <?php if($services[$i]["api_servicetype"]==1): echo '<div class="service-block__danger"></div>'; endif;?>
                                                                    <span></span>
                                                                    <div class="service-block__checkbox">
                                                                        <div class="service-block__drag handle">
                                                                            <svg>
                                                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                                                <title>Drag-Handle</title>
                                                                                <path d="M7 2c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm0 6c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm0 6c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm6-8c1.104 0 2-.896 2-2s-.896-2-2-2-2 .896-2 2 .896 2 2 2zm0 2c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm0 6c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2z"></path>
                                                                                </svg>
                                                                            </svg>
                                                                        </div>
                                                                        <input type="checkbox" class="selectOrder dborder-1-solid" name="service[<?php echo $services[$i]["service_id"] ?>]" value="1">
                                                                    </div>
                                                                    </td>
                                                                    <td class="service-block__id"><?php echo $services[$i]["service_id"] ?></td>
                                                                    <td class="service-block__service"><?php if( $services[$i]["service_secret"] == 1 ): echo '<small data-toggle="tooltip" data-placement="top" title="" data-original-title="secret service"><i class="fa fa-lock"></i></small> '; endif; echo $services[$i]["service_name"]; ?></td>
                                                                    <td class="service-block__type" nowrap=""><?php echo servicePackageType($services[$i]["service_package"]); ?></td>
                                                                    <td class="service-block__provider"><?php if( $services[$i]["service_api"] != 0 ): echo $services[$i]["api_name"]; else: echo "Manuel"; endif; ?></td>
                                                                    <td class="service-block__rate">
                                                                    <?php
                                                                        $api_price  = $api_detail["rate"];
                                                                    ?>
                                                                    <div class="<?php if( $services[$i]["service_api"] != 0 && $services[$i]["service_price"] > $api_price ): echo "dgreen"; elseif( $services[$i]["service_api"] != 0 && $services[$i]["service_price"] < $api_price ): echo "dred"; endif ?>"><?php echo $services[$i]["service_price"] ?></div>

                                                                    <?php if( $services[$i]["service_api"] != 0 ): echo '<div class="service-block__provider-value"><i class="fa fa-'.strtolower($api_detail["currency"]).'"></i> '.priceFormat($api_detail["rate"]).'</div>'; endif; ?>
                                                                    </td>
                                                                    <td class="service-block__minorder">
                                                                    <div><?php echo $services[$i]["service_min"] ?></div>
                                                                    <?php if( $services[$i]["service_api"] != 0 ): echo '<div class="service-block__provider-value">'.$api_detail["min"].'</div>'; endif; ?>
                                                                    </td>
                                                                    <td class="service-block__minorder">
                                                                    <div><?php echo $services[$i]["service_max"] ?></div>
                                                                    <?php if( $services[$i]["service_api"] != 0 ): echo '<div class="service-block__provider-value">'.$api_detail["max"].'</div>'; endif; ?>
                                                                    </td>
                                                                    <td class="service-block__visibility"><?php if( $services[$i]["service_type"] == 1 ): echo "Inactive"; else: echo "Active"; endif; ?> <?php if($services[$i]["api_servicetype"]==1): echo '<span class="text-danger" title="Service provider removed service"><span class="fa fa-exclamation-circle"></span></span>'; endif;?> </td>
                                                                    <td class="service-block__action">
                                                                    <div class="dropdown pull-right">
                                                                        <button type="button" class="btn btn-primary btn-xs dropdown-toggle btn-xs-caret" data-toggle="dropdown">Action</button>
                                                                        <ul class="dropdown-menu">
                                                                        <li><a class="dcs-pointer"  data-toggle="modal" data-target="#modalDiv" data-action="edit_service" data-id="<?=$services[$i]["service_id"]?>">Edit Service</a></li>
                                                                            <?php if( $services[$i]["service_type"] == 1 ): $type = "service-active"; else: $type = "service-deactive"; endif; ?>
                                                                        <li><a href="<?php echo site_url("admin/services/".$type."/".$services[$i]["service_id"]) ?>"><?php if( $services[$i]["service_type"] == 1 ): echo "Activate"; else: echo "Deactivate"; endif; ?> Service</a></li>
                                                                        <li><a href="<?php echo site_url("admin/services/del_price/".$services[$i]["service_id"]) ?>">Delete Pricing</a></li>
                                                                        <li><a href="<?php echo site_url("admin/services/del_service/".$services[$i]["service_id"]) ?>">Delete Service</a></li>
                                                                        </ul>
                                                                    </div>
                                                                    </td>
                                                                <?php endif; ?>
                                                            </tr>
                                                            <?php endfor; ?>
                                                        </div>
                                                    </tbody>
                                                </table>
                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                    <?php endforeach; ?>

                                    <?php
                                        $services = $conn->prepare("SELECT * FROM services LEFT JOIN service_api ON service_api.id = services.service_api WHERE services.category_id=:c_id ORDER BY services.service_line ASC ");
                                        $services-> execute(array("c_id"=>0));
                                        $services = $services->fetchAll(PDO::FETCH_ASSOC);
                                        if( $services ):
                                    ?>
                                        <div class="service-block__category ">
                                            <div class="service-block__category-title"  class="categorySortable" data-category="notcategory" id="category-0">
                                                Uncategorized
                                                <div class="service-block__collapse-block"><div id="collapedAdd-0" class="service-block__collapse-button" data-category="category-0"></div>
                                            </div>
                                        </div>
                                        <div class="collapse in">
                                            <div class="service-block__packages">
                                                <table id="servicesTableList" class="Servicecategory-0">
                                                    <tbody class="service-sortable">
                                                        <div class="serviceSortable" id="Servicecategory-0" data-id="category-0">
                                                        <?php foreach($services as $service): $api_detail = json_decode($service["api_detail"],true); ?>
                                                            <tr id="serviceshowcategory-0" class="ui-state-default <?php if( $service["service_type"] == 1 ): echo "grey"; endif; ?>"  data-category="category-0" data-id="service-<?php echo $service["service_id"] ?>" data-service="<?php echo $service["service_name"] ?>">
                                                                <td class="service-block__checker">
                                                                <!-- <div class="service-block__danger"></div> //Activate here if the service is passive on the other site-->
                                                                <span></span>
                                                                <div class="service-block__checkbox">
                                                                    <div class="service-block__drag handle">
                                                                        <svg>
                                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                                            <title>Drag-Handle</title>
                                                                            <path d="M7 2c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm0 6c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm0 6c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm6-8c1.104 0 2-.896 2-2s-.896-2-2-2-2 .896-2 2 .896 2 2 2zm0 2c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm0 6c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2z"></path>
                                                                            </svg>
                                                                        </svg>
                                                                    </div>
                                                                    <input type="checkbox" class="selectOrder dborder-1-solid" name="service[<?php echo $service["service_id"] ?>]" value="1">
                                                                </div>
                                                                </td>
                                                                <td class="service-block__id"><?php echo $service["service_id"] ?></td>
                                                                <td class="service-block__service"><?php if( $service["service_secret"] == 1 ): echo '<small data-toggle="tooltip" data-placement="top" title="" data-original-title="secret service"><i class="fa fa-lock"></i></small> '; endif; echo $service["service_name"]; ?></td>
                                                                <td class="service-block__type" nowrap="">Service type will be set</td>
                                                                <td class="service-block__provider"><?php if( $service["service_api"] != 0 ): echo $service["api_name"]; else: echo "Manuel"; endif; ?></td>
                                                                <td class="service-block__rate">
                                                                <?php
                                                                    $api_price  = $api_detail["rate"];
                                                                ?>
                                                                <div class="<?php if( $service["service_api"] != 0 && $service["service_price"] > $api_price ): echo "dgreen"; elseif( $service["service_api"] != 0 && $service["service_price"] < $api_price ): echo "dred"; endif ?>"><?php echo $service["service_price"] ?></div>
                                                                <?php if( $service["service_api"] != 0 ): echo '<div class="service-block__provider-value"><i class="fa fa-'.strtolower($api_detail["currency"]).'"></i> '.priceFormat($api_detail["rate"]).'</div>'; endif; ?>
                                                                </td>
                                                                <td class="service-block__minorder">
                                                                <div><?php echo $service["service_min"] ?></div>
                                                                <?php if( $service["service_api"] != 0 ): echo '<div class="service-block__provider-value">'.$api_detail["min"].'</div>'; endif; ?>
                                                                </td>
                                                                <td class="service-block__minorder">
                                                                <div><?php echo $service["service_max"] ?></div>
                                                                <?php if( $service["service_api"] != 0 ): echo '<div class="service-block__provider-value">'.$api_detail["max"].'</div>'; endif; ?>
                                                                </td>
                                                                <td class="service-block__visibility"><?php if( $service["service_type"] == 1 ): echo "Inactive"; else: echo "Active"; endif; ?> </td>
                                                                <td class="service-block__action">
                                                                    <div class="dropdown pull-right">
                                                                        <button type="button" class="btn btn-primary btn-xs dropdown-toggle btn-xs-caret" data-toggle="dropdown">Action</button>
                                                                        <ul class="dropdown-menu">
                                                                        <li><a class="dcs-pointer"  data-toggle="modal" data-target="#modalDiv" data-action="edit_service" data-id="<?=$service["service_id"]?>">Edit Service</a></li>
                                                                            <?php if( $service["service_type"] == 1 ): $type = "service-active"; else: $type = "service-deactive"; endif; ?>
                                                                        <li><a href="<?php echo site_url("admin/services/".$type."/".$service["service_id"]) ?>"><?php if( $service["service_type"] == 1 ): echo "Activate"; else: echo "Deactivate"; endif; ?> Service</a></li>
                                                                        <li><a href="<?php echo site_url("admin/services/del_price/".$service["service_id"]) ?>">Delete Pricing</a></li>
                                                                        <li><a href="<?php echo site_url("admin/services/del_service/".$service["service_id"]) ?>">Delete Service</a></li>
                                                                        </ul>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                        </div>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        </div>
                                    <?php endif; ?>
                                    <input type="hidden" name="bulkStatus" id="bulkStatus" value="-1">
                                    </form>
                                </div>
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

<?php include 'footer.php'; ?>