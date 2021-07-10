<?php
if ($user["access"]["orders"] != 1):
    header("Location:" . site_url("admin"));
    exit();
endif;
$smmapi = new SMMApi();
if (route(2) == "counter"):
    $count = $conn->prepare("SELECT * FROM orders WHERE dripfeed=:dripfeed && subscriptions_type=:sub $search_add ");
    $count->execute(array("dripfeed" => 1, "sub" => 1));
    $count = $count->rowCount();
    $services = $conn->prepare("SELECT * FROM services");
    $services->execute(array());
    $services = $services->fetchAll(PDO::FETCH_ASSOC);
    $active = $_POST["active"];
    echo '<li';
    if (!$active):
        echo ' class="active"';
    endif;
    echo '>
            <a href="/admin/orders/all">All orders (' . $count . ')</a>
          </li>';
    foreach ($services as $service):
        echo '<li';
        if ($service["service_id"] == $active):
            echo ' class="active"';
        endif;
        echo '>
                <a ';
        if ($service["service_type"] == 1):
            echo ' style="color: #c1c1c1;"';
        endif;
        echo ' href="admin/orders/all?service_id=' . $service["service_id"] . '"><span class="label-id">' . $service["service_id"] . '</span> ' . $service["service_name"] . ' (' . countRow(["table" => "orders", "where" => ["service_id" => $service["service_id"]]]) . ')</a>
              </li>';
    endforeach;
    exit();
endif;
if ($_SESSION["client"]["data"]):
    $data = $_SESSION["client"]["data"];
    foreach ($data as $key => $value) {
        $$key = $value;
    }
    unset($_SESSION["client"]);
endif;
if (route(2) && is_numeric(route(2))):
    $page = route(2);
else:
    $page = 1;
endif;
$statusList = ["all", "pending", "inprogress", "completed", "partial", "canceled", "processing", "fail", "cronpending"];
if (route(3) && in_array(route(3), $statusList)):
    $status = route(3);
elseif (!route(3) || !in_array(route(3), $statusList)):
    $status = "all";
endif;
if ($_GET["search_type"] == "username" && $_GET["search"]):
    $search_where = $_GET["search_type"];
    $search_word = $_GET["search"];
    $clients = $conn->prepare("SELECT client_id FROM clients WHERE username LIKE '%" . $search_word . "%' ");
    $clients->execute(array());
    $clients = $clients->fetchAll(PDO::FETCH_ASSOC);
    $id = "(";
    foreach ($clients as $client) {
        $id.= $client["client_id"] . ",";
    }
    if (substr($id, -1) == ","):
        $id = substr($id, 0, -1);
    endif;
    $id.= ")";
    $search = " orders.client_id IN " . $id;
    $count = $conn->prepare("SELECT * FROM orders INNER JOIN clients ON clients.client_id = orders.client_id WHERE {$search} && orders.dripfeed='1' && orders.subscriptions_type='1' ");
    $count->execute(array());
    $count = $count->rowCount();
    $search = "WHERE {$search} && orders.dripfeed='1' && orders.subscriptions_type='1' ";
    $search_link = "?search=" . $search_word . "&search_type=" . $search_where;
elseif ($_GET["search_type"] == "order_id" && $_GET["search"]):
    $search_where = $_GET["search_type"];
    $search_word = $_GET["search"];
    $count = $conn->prepare("SELECT * FROM orders INNER JOIN clients ON clients.client_id = orders.client_id WHERE orders.order_id LIKE '%" . $search_word . "%' && orders.dripfeed='1' && orders.subscriptions_type='1' ");
    $count->execute(array());
    $count = $count->rowCount();
    $search = "WHERE orders.order_id LIKE '%" . $search_word . "%'  && orders.dripfeed='1' && orders.subscriptions_type='1' ";
    $search_link = "?search=" . $search_word . "&search_type=" . $search_where;
elseif ($_GET["search_type"] == "order_url" && $_GET["search"]):
    $search_where = $_GET["search_type"];
    $search_word = $_GET["search"];
    $count = $conn->prepare("SELECT * FROM orders INNER JOIN clients ON clients.client_id = orders.client_id WHERE orders.order_url LIKE '%" . $search_word . "%' && orders.dripfeed='1' && orders.subscriptions_type='1' ");
    $count->execute(array());
    $count = $count->rowCount();
    $search = "WHERE orders.order_url LIKE '%" . $search_word . "%'  && orders.dripfeed='1' && orders.subscriptions_type='1' ";
    $search_link = "?search=" . $search_word . "&search_type=" . $search_where;
elseif ($_GET["subscription"]):
    $subs_id = $_GET["subscription"];
    $count = $conn->prepare("SELECT * FROM orders INNER JOIN clients ON clients.client_id = orders.client_id WHERE orders.order_id LIKE '%" . $search_word . "%' && orders.dripfeed='1' && orders.subscriptions_type='1' && orders.subscriptions_id='$subs_id' ");
    $count->execute(array());
    $count = $count->rowCount();
    $search = "WHERE orders.subscriptions_id='$subs_id'  && orders.dripfeed='1' && orders.subscriptions_type='1' ";
    $search_link = "?subscription=" . $_GET["subscription"];
elseif ($_GET["dripfeed"]):
    $drip_id = $_GET["dripfeed"];
    $count = $conn->prepare("SELECT * FROM orders INNER JOIN clients ON clients.client_id = orders.client_id WHERE orders.order_id LIKE '%" . $search_word . "%' && orders.dripfeed='1' && orders.subscriptions_type='1' && orders.dripfeed_id='$drip_id' ");
    $count->execute(array());
    $count = $count->rowCount();
    $search = "WHERE orders.dripfeed_id='$drip_id'  && orders.dripfeed='1' && orders.subscriptions_type='1' ";
    $search_link = "?dripfeed=" . $_GET["subscription"];
elseif ($status != "all"):
    if ($_GET["mode"] && $_GET["mode"] == "manuel"):
        $search_add = " && orders.order_api=0";
        $search_link = "?mode=" . $_GET["mode"];
    elseif ($_GET["mode"] && $_GET["mode"] == "auto"):
        $search_add = " && orders.order_api!=0";
        $search_link = "?mode=" . $_GET["mode"];
    elseif ($_GET["service_id"]):
        $search_add = " && orders.service_id=" . $_GET["service_id"];
        $search_link = "?service_id=" . $_GET["service_id"];
    else:
        $search_add = "";
    endif;
    if ($status == "fail"):
        $search_add.= ' && orders.order_error!="-" ';
        $count = $conn->prepare("SELECT * FROM orders WHERE dripfeed=:dripfeed && subscriptions_type=:sub $search_add ");
        $count->execute(array("dripfeed" => 1, "sub" => 1));
        $count = $count->rowCount();
        $search = "WHERE orders.dripfeed='1' && orders.subscriptions_type='1' $search_add ";
    elseif ($status == "cronpending"):
        $search_add.= ' && orders.order_error="-" ';
        $count = $conn->prepare("SELECT * FROM orders WHERE order_detail=:detail && dripfeed=:dripfeed && subscriptions_type=:sub $search_add ");
        $count->execute(array("dripfeed" => 1, "sub" => 1, "detail" => "cronpending"));
        $count = $count->rowCount();
        $search = "WHERE orders.dripfeed='1' && orders.subscriptions_type='1' && order_detail='cronpending' $search_add ";
    else:
        $search_add.= ' && orders.order_error="-" ';
        $count = $conn->prepare("SELECT * FROM orders WHERE order_detail!=:detail && order_status=:status && dripfeed=:dripfeed && subscriptions_type=:sub $search_add ");
        $count->execute(array("dripfeed" => 1, "sub" => 1, "status" => $status, "detail" => "cronpending"));
        $count = $count->rowCount();
        $search = "WHERE orders.order_status='" . $status . "' && orders.dripfeed='1' && orders.subscriptions_type='1' && order_detail!='cronpending'  $search_add ";
    endif;
elseif ($status == "all"):
    if ($_GET["mode"] && $_GET["mode"] == "manuel"):
        $search_add = " && orders.order_api=0";
        $search_link = "?mode=" . $_GET["mode"];
    elseif ($_GET["mode"] && $_GET["mode"] == "auto"):
        $search_add = " && orders.order_api!=0";
        $search_link = "?mode=" . $_GET["mode"];
    elseif ($_GET["service_id"]):
        $search_add = " && orders.service_id=" . $_GET["service_id"];
        $search_link = "?service_id=" . $_GET["service_id"];
    else:
        $search_add = "";
    endif;
    $count = $conn->prepare("SELECT * FROM orders WHERE dripfeed=:dripfeed && subscriptions_type=:sub $search_add ");
    $count->execute(array("dripfeed" => 1, "sub" => 1));
    $count = $count->rowCount();
    $search = "WHERE orders.dripfeed='1' && orders.subscriptions_type='1' $search_add ";
endif;
$to = 10000;
$pageCount = ceil($count / $to);
if ($page > $pageCount):
    $page = 1;
endif;
$where = ($page * $to) - $to;
$paginationArr = ["count" => $pageCount, "current" => $page, "next" => $page + 1, "previous" => $page - 1];
$orders = $conn->prepare("SELECT * FROM orders INNER JOIN clients ON clients.client_id=orders.client_id INNER JOIN services ON services.service_id=orders.service_id $search ORDER BY orders.order_id DESC LIMIT $where,$to ");
$orders->execute(array());
$orders = $orders->fetchAll(PDO::FETCH_ASSOC);
$failCount = $conn->prepare("SELECT * FROM orders WHERE orders.dripfeed='1' && orders.subscriptions_type='1' && order_error!=:error ");
$failCount->execute(array("error" => "-"));
$failCount = $failCount->rowCount();
function orderStatu($statu, $error, $cron)
{
    if ($cron == "cronpending"):
        $statu = "Cron pending"; elseif ($error == "-"):
        switch ($statu) {
            case 'pending':
                $statu = "Pending";
            break;
            case 'inprogress':
                $statu = "Inprogress";
            break;
            case 'completed':
                $statu = "Completed";
            break;
            case 'partial':
                $statu = "Partial (Remaining has been refund)";
            break;
            case 'canceled':
                $statu = "Canceled";
            break;
            case 'processing':
                $statu = "Processing";
            break;
        } else:
            $statu = "Fail";
    endif;
    return $statu;
}
    if ($_POST):
        if (route(2) == "set_orderurl"):
            $id = route(3);
            $url = $_POST["url"];
            $update = $conn->prepare("UPDATE orders SET order_url=:url WHERE order_id=:id ");
            $update->execute(array("id" => $id, "url" => $url));
            header("Location:" . site_url("admin/orders"));
        elseif (route(2) == "set_startcount"):
            $id = route(3);
            $start = $_POST["start"];
            $update = $conn->prepare("UPDATE orders SET order_start=:start WHERE order_id=:id ");
            $update->execute(array("id" => $id, "start" => $start));
            header("Location:" . site_url("admin/orders"));
        elseif (route(2) == "set_partial"):
            $id = route(3);
            $remains = $_POST["remains"];
            $order = $conn->prepare("SELECT * FROM orders INNER JOIN clients ON clients.client_id = orders.client_id WHERE orders.order_id=:id ");
            $order->execute(array("id" => $id));
            $order = $order->fetch(PDO::FETCH_ASSOC);
            if (empty($remains) || !is_numeric($remains)):
                $error = 1;
                $errorText = "Remains can not be empty";
                $icon = "error";
            elseif ($order["order_quantity"] < $remains):
                $error = 1;
                $errorText = "Remains cannot be much than order quantity.";
                $icon = "error";
            else:
                $price = $order["order_charge"] / $order["order_quantity"];
                $return = $price * $remains;
                $balance = $order["balance"] + $return;
                $order["order_quantity"] = $order["order_quantity"] - $remains;
                $charge = $order["order_charge"] - $return;
                $conn->beginTransaction();
                $update = $conn->prepare("UPDATE orders SET order_remains=:remains, order_status=:statu, order_charge=:charge, order_quantity=:quantity WHERE order_id=:id ");
                $update = $update->execute(array("id" => $id, "remains" => $remains, "statu" => "partial", "charge" => $charge, "quantity" => $order["order_quantity"]));
                $update2 = $conn->prepare("UPDATE clients SET balance=:balance, spent=:spent WHERE client_id=:id ");
                $update2 = $update2->execute(array("id" => $order["client_id"], "balance" => $balance, "spent" => $order["spent"] - $return));
                if ($update && $update2):
                    $conn->commit();
                    $error = 1;
                    $errorText = "Successful";
                    $icon = "success";
                    $referrer = site_url("admin/orders");
                else:
                    $conn->rollBack();
                    $error = 1;
                    $errorText = "Error";
                    $icon = "error";
                    $referrer = site_url("admin/orders");
                endif;
            endif;
            echo json_encode(["t" => "error", "m" => $errorText, "s" => $icon, "r" => $referrer]);
        elseif (route(2) == "multi-action"):
            $orders = $_POST["order"];
            $action = $_POST["bulkStatus"];
            if ($action == "pending"):
                foreach ($orders as $id => $value):
                    $update = $conn->prepare("UPDATE orders SET order_status=:status WHERE order_id=:id ");
                    $update->execute(array("status" => "pending", "id" => $id));
                endforeach;
            elseif ($action == "inprogress"):
                foreach ($orders as $id => $value):
                    $update = $conn->prepare("UPDATE orders SET order_status=:status WHERE order_id=:id ");
                    $update->execute(array("status" => "inprogress", "id" => $id));
                endforeach;
            elseif ($action == "completed"):
                foreach ($orders as $id => $value):
                    $update = $conn->prepare("UPDATE orders SET order_status=:status WHERE order_id=:id ");
                    $update->execute(array("status" => "completed", "id" => $id));
                endforeach;
            elseif ($action == "canceled"):
                foreach ($orders as $id => $value):
                    $order = $conn->prepare("SELECT * FROM orders INNER JOIN clients ON clients.client_id = orders.client_id WHERE orders.order_id=:id ");
                    $order->execute(array("id" => $id));
                    $order = $order->fetch(PDO::FETCH_ASSOC);
                    $balance = $order["balance"] + $order["order_charge"];
                    $spent = $order["spent"] - $order["order_charge"];
                    $order["order_quantity"] = $order["order_quantity"];
                    $conn->beginTransaction();
                    $update = $conn->prepare("UPDATE orders SET api_charge=:api_charge, order_profit=:order_profit, order_status=:status, order_error=:error, order_charge=:price, order_quantity=:quantity, order_remains=:remains WHERE order_id=:id ");
                    $update = $update->execute(array("api_charge" => 0, "order_profit" => 0, "status" => "canceled", "price" => 0, "quantity" => 0, "remains" => $order["order_quantity"], "error" => "-", "id" => $id));
                    $update2 = $conn->prepare("UPDATE clients SET balance=:balance, spent=:spent WHERE client_id=:id ");
                    $update2 = $update2->execute(array("id" => $order["client_id"], "balance" => $balance, "spent" => $spent));
                    if ($update && $update2):
                        $conn->commit();
                    else:
                        $conn->rollBack();
                    endif;
                endforeach;
            elseif ($action == "resend"):
                foreach ($orders as $id => $value):
                    $order = $conn->prepare("SELECT * FROM orders INNER JOIN services ON services.service_id = orders.service_id INNER JOIN service_api ON services.service_api = service_api.id WHERE orders.order_id=:id ");
                    $order->execute(array("id" => $id));
                    $order = $order->fetch(PDO::FETCH_ASSOC);
                    if ($order["api_type"] == 1):
                        if ($order["service_package"] == 1 || $order["service_package"] == 2 || $order["service_package"] == 11 || $order["service_package"] == 12):
                            $get_order = $smmapi->action(array('key' => $order["api_key"], 'action' => 'add', 'service' => $order["api_service"], 'link' => $order["order_url"], 'quantity' => $order["order_quantity"]), $order["api_url"]);
                            if (@!$get_order->order):
                                $error = json_encode($get_order);
                                $order_id = "";
                            else:
                                $error = "-";
                                $order_id = @$get_order->order;
                            endif;
                            elseif ($order["service_package"] == 3):
                                $get_order = $smmapi->action(array('key' => $order["api_key"], 'action' => 'add', 'service' => $order["api_service"], 'link' => $order["order_url"], 'comments' => $comments), $order["api_url"]);
                                if (@!$get_order->order):
                                    $error = json_encode($get_order);
                                    $order_id = "";
                                else:
                                    $error = "-";
                                    $order_id = @$get_order->order;
                                endif;
                                else:
                                endif;
                                $orderstatus = $smmapi->action(array('key' => $order["api_key"], 'action' => 'status', 'order' => $order_id), $order["api_url"]);
                                $balance = $smmapi->action(array('key' => $order["api_key"], 'action' => 'balance'), $order["api_url"]);
                                $api_charge = $orderstatus->charge;
                                if (!$api_charge):
                                    $api_charge = 0;
                                endif;
                                $currencycharge = 1;
                                elseif ($order["api_type"] == 2):
                                    elseif ($order["api_type"] == 3):
                                        if ($order["service_package"] == 1 || $order["service_package"] == 2):
                                            $get_order = $smmapi->standartAPI(array('api_token' => $order["api_key"], 'action' => 'add', 'package' => $order["api_service"], 'link' => $order["order_url"], 'quantity' => $order["order_quantity"]), $order["api_url"]);
                                            if (@!$get_order->order):
                                                $error = json_encode($get_order);
                                                $order_id = "";
                                            else:
                                                $error = "-";
                                                $order_id = @$get_order->order;
                                            endif;
                                            
                                        endif;
                                        $orderstatus = $smmapi->action(array('api_token' => $order["api_key"], 'status' => 'balance', 'order' => $order_id), $order["api_url"]);
                                        $balance = $smmapi->action(array('api_token' => $order["api_key"], 'action' => 'balance'), $order["api_url"]);
                                        $api_charge = $orderstatus->charge;
                                        $currencycharge = 1;
                                    else:
                                    endif;
                                    $update = $conn->prepare("UPDATE orders SET order_api=:api, api_serviceid=:serviceid, order_error=:error, api_orderid=:orderid, order_detail=:detail, api_charge=:api_charge, api_currencycharge=:api_currencycharge, order_profit=:profit  WHERE order_id=:id ");
                                    $update->execute(array("error" => $error, "api" => $order["id"], "serviceid" => $order["api_service"], "orderid" => $order_id, "detail" => json_encode($get_order), "id" => $order["order_id"], "profit" => $api_charge * $currencycharge, "api_charge" => $api_charge, "api_currencycharge" => $currencycharge));
                                endforeach;
                            endif;
                            header("Location:" . site_url("admin/orders"));
                        endif;
                        exit();
                    endif;
                    require admin_view('orders');
                    if (route(2) == "order_cancel"):
                        $id = route(3);
                        $order = $conn->prepare("SELECT * FROM orders INNER JOIN clients ON clients.client_id = orders.client_id WHERE orders.order_id=:id ");
                        $order->execute(array("id" => $id));
                        $order = $order->fetch(PDO::FETCH_ASSOC);
                        $balance = $order["balance"] + $order["order_charge"];
                        $spent = $order["spent"] - $order["order_charge"];
                        $order["order_quantity"] = $order["order_quantity"];
                        $conn->beginTransaction();
                        $update = $conn->prepare("UPDATE orders SET api_charge=:api_charge, order_profit=:order_profit, order_status=:status, order_error=:error, order_charge=:price, order_quantity=:quantity, order_remains=:remains WHERE order_id=:id ");
                        $update = $update->execute(array("api_charge" => 0, "order_profit" => 0, "status" => "canceled", "price" => 0, "error" => "-", "quantity" => 0, "remains" => $order["order_quantity"], "id" => $id));
                        $update2 = $conn->prepare("UPDATE clients SET balance=:balance, spent=:spent WHERE client_id=:id ");
                        $update2 = $update2->execute(array("id" => $order["client_id"], "balance" => $balance, "spent" => $spent));
                        if ($update && $update2):
                            $conn->commit();
                        else:
                            $conn->rollBack();
                        endif;
                        header("Location:" . site_url("admin/orders"));
                    elseif (route(2) == "order_complete"):
                        $id = route(3);
                        $update = $conn->prepare("UPDATE orders SET order_status=:status WHERE order_id=:id ");
                        $update->execute(array("status" => "completed", "id" => $id));
                        header("Location:" . site_url("admin/orders"));
                    elseif (route(2) == "order_inprogress"):
                        $id = route(3);
                        $update = $conn->prepare("UPDATE orders SET order_status=:status WHERE order_id=:id ");
                        $update->execute(array("status" => "inprogress", "id" => $id));
                        header("Location:" . site_url("admin/orders"));
                    elseif (route(2) == "order_resend"):
                        $id = route(3);
                        $order = $conn->prepare("SELECT * FROM orders INNER JOIN services ON services.service_id = orders.service_id INNER JOIN service_api ON services.service_api = service_api.id WHERE orders.order_id=:id ");
                        $order->execute(array("id" => $id));
                        $order = $order->fetch(PDO::FETCH_ASSOC);
                        if ($order["api_type"] == 1):
                            if ($order["service_package"] == 1 || $order["service_package"] == 2 || $order["service_package"] == 11 || $order["service_package"] == 12):
                                $get_order = $smmapi->action(array('key' => $order["api_key"], 'action' => 'add', 'service' => $order["api_service"], 'link' => $order["order_url"], 'quantity' => $order["order_quantity"]), $order["api_url"]);
                                if (@!$get_order->order):
                                    $error = json_encode($get_order);
                                    $order_id = "";
                                else:
                                    $error = "-";
                                    $order_id = @$get_order->order;
                                endif;
                                elseif ($order["service_package"] == 3):
                                    $get_order = $smmapi->action(array('key' => $order["api_key"], 'action' => 'add', 'service' => $order["api_service"], 'link' => $order["order_url"], 'comments' => $comments), $order["api_url"]);
                                    if (@!$get_order->order):
                                        $error = json_encode($get_order);
                                        $order_id = "";
                                    else:
                                        $error = "-";
                                        $order_id = @$get_order->order;
                                    endif;
                                    else:
                                    endif;
                                    $orderstatus = $smmapi->action(array('key' => $order["api_key"], 'action' => 'status', 'order' => $order_id), $order["api_url"]);
                                    $balance = $smmapi->action(array('key' => $order["api_key"], 'action' => 'balance'), $order["api_url"]);
                                    $api_charge = $orderstatus->charge;
                                    if (!$api_charge):
                                        $api_charge = 0;
                                    endif;
                                    $currencycharge = 1;
                                    elseif ($order["api_type"] == 2):
                                        elseif ($order["api_type"] == 3):
                                            if ($order["service_package"] == 1 || $order["service_package"] == 2):
                                                $get_order = $smmapi->standartAPI(array('api_token' => $order["api_key"], 'action' => 'add', 'package' => $order["api_service"], 'link' => $order["order_url"], 'quantity' => $order["order_quantity"]), $order["api_url"]);
                                                if (@!$get_order->order):
                                                    $error = json_encode($get_order);
                                                    $order_id = "";
                                                else:
                                                    $error = "-";
                                                    $order_id = @$get_order->order;
                                                endif;
                                                
                                            endif;
                                            $orderstatus = $smmapi->action(array('api_token' => $order["api_key"], 'status' => 'balance', 'order' => $order_id), $order["api_url"]);
                                            $balance = $smmapi->action(array('api_token' => $order["api_key"], 'action' => 'balance'), $order["api_url"]);
                                            $api_charge = $orderstatus->charge;
                                            $currencycharge = 1;
                                        else:
                                        endif;
                                        $update = $conn->prepare("UPDATE orders SET order_api=:api, api_serviceid=:serviceid, order_error=:error, api_orderid=:orderid, order_detail=:detail, api_charge=:api_charge, api_currencycharge=:api_currencycharge, order_profit=:profit WHERE order_id=:id ");
                                        $update->execute(array("error" => $error, "api" => $order["id"], "serviceid" => $order["api_service"], "orderid" => $order_id, "detail" => json_encode($get_order), "id" => $order["order_id"], "profit" => $api_charge * $currencycharge, "api_charge" => $api_charge, "api_currencycharge" => $currencycharge));
                                        header("Location:" . site_url("admin/orders"));
                                    endif;
