 <?php
require '../vendor/autoload.php';
require '../app/init.php';
$smmapi = new SMMApi();
$orders = $conn->prepare("SELECT *,services.service_id as service_id,services.service_api as api_id FROM orders
INNER JOIN clients ON clients.client_id=orders.client_id
INNER JOIN services ON services.service_id=orders.service_id
LEFT JOIN categories ON categories.category_id=services.category_id
INNER JOIN service_api ON service_api.id=services.service_api
WHERE orders.dripfeed=:dripfeed && orders.subscriptions_type=:subs && orders.order_status=:statu && orders.order_error=:error && orders.order_detail=:detail LIMIT 10 ");
$orders->execute(array("dripfeed" => 1, "subs" => 1, "statu" => "pending", "detail" => "cronpending", "error" => "-"));
$orders = $orders->fetchAll(PDO::FETCH_ASSOC);
foreach ($orders as $order) {
    $user = $conn->prepare("SELECT * FROM clients WHERE client_id=:id");
    $user->execute(array("id" => $order["client_id"]));
    $user = $user->fetch(PDO::FETCH_ASSOC);
    $price = $order["order_charge"];
    $clientBalance = $user["balance"];
    $clientSpent = $user["spent"];
    $balance_type = $order["balance_type"];
    $balance_limit = $order["debit_limit"];
    $link = $order["order_url"];
    if ((($price > $clientBalance) && $balance_type == 2) || (($clientBalance - $price < "-" . $balance_limit) && $balance_type == 1)):
        $conn->beginTransaction();
        $update_order = $conn->prepare("UPDATE orders SET order_detail=:detail, order_start=:start, order_finish=:finish, order_remains=:remains, order_status=:status, order_charge=:charge WHERE order_id=:id ");
        $update_order = $update_order->execute(array("id" => $order["order_id"], "start" => 0, "finish" => 0, "detail" => "", "remains" => $order["order_quantity"], "status" => "canceled", "charge" => 0));
        $insert2 = $conn->prepare("INSERT INTO client_report SET client_id=:c_id, action=:action, report_ip=:ip, report_date=:date ");
        $insert2 = $insert2->execute(array("c_id" => $order["client_id"], "action" => "Order #" . $order["order_id"] . " has been canceled because the user does not have sufficient balance.", "ip" => GetIP(), "date" => date("Y-m-d H:i:s")));
        if ($insert2 && $update_order) {
            $conn->commit();
        } else {
            $conn->rollBack();
        } else:
            if ($order["want_username"] == 2):
                $private_type = "username";
                $countRow = $conn->prepare("SELECT * FROM orders WHERE order_url=:url && ( order_status=:statu || order_status=:statu2 || order_status=:statu3 ) && dripfeed=:dripfeed && subscriptions_type=:subscriptions_type ");
                $countRow->execute(array("url" => $link, "statu" => "pending", "statu2" => "inprogress", "statu3" => "processing", "dripfeed" => 1, "subscriptions_type" => 1));
                $countRow = $countRow->rowCount();
            else:
                $private_type = "url";
                if (substr($link, 0, 7) == "http://"):
                    $linkSearch = substr($link, 7);
                endif;
                if (substr($linkSearch, 0, 8) == "https://"):
                    $linkSearch = substr($linkSearch, 8);
                endif;
                if (substr($linkSearch, 0, 4) == "www."):
                    $linkSearch = substr($link, 4);
                endif;
                $countRow = $conn->prepare("SELECT * FROM orders WHERE order_url LIKE :url && ( order_status=:statu || order_status=:statu2 || order_status=:statu3 ) && dripfeed=:dripfeed && subscriptions_type=:subscriptions_type ");
                $countRow->execute(array("url" => '%' . $linkSearch . '%', "statu" => "pending", "statu2" => "inprogress", "statu3" => "processing", "dripfeed" => 1, "subscriptions_type" => 1));
                $countRow = $countRow->rowCount();
            endif;
            if ($order["start_count"] == "none"):
                $start_count = "0";
            else:
                $start_count = instagramCount(["type" => $private_type, "url" => $link, "search" => $order["start_count"]]);
            endif;
            $conn->beginTransaction();
            if ($order["api_type"] == 1):
                
                if ($order["service_package"] == 1 || $order["service_package"] == 2):
                    
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
                        $update_order = $conn->prepare("UPDATE orders SET order_start=:start, order_error=:error, api_orderid=:orderid, order_detail=:detail, api_charge=:api_charge, api_currencycharge=:api_currencycharge, order_profit=:profit  WHERE order_id=:id ");
                        $update_order = $update_order->execute(array("start" => $start_count, "error" => $error, "orderid" => $order_id, "detail" => json_encode($get_order), "id" => $order["order_id"], "profit" => $api_charge * $currencycharge, "api_charge" => $api_charge, "api_currencycharge" => $currencycharge));
                        $update_client = $conn->prepare("UPDATE clients SET balance=:balance, spent=:spent WHERE client_id=:id");
                        $update_client = $update_client->execute(array("balance" => $clientBalance - $price, "spent" => $clientSpent + $price, "id" => $order["client_id"]));
                        $client = $conn->prepare("SELECT * FROM clients WHERE client_id=:id");
                        $client->execute(array("id" => $order["client_id"]));
                        $client = $client->fetch(PDO::FETCH_ASSOC);
                        $insert2 = $conn->prepare("INSERT INTO client_report SET client_id=:c_id, action=:action, report_ip=:ip, report_date=:date ");
                        $insert2 = $insert2->execute(array("c_id" => $order["client_id"], "action" => "A new order of " . $price . " " . $settings['currency'] . " was created through the API. order ID: #" . $order["order_id"] . " Old Balance: " . $clientBalance . " / New Balance:" . $client["balance"], "ip" => GetIP(), "date" => date("Y-m-d H:i:s")));
                        if ($update_order && $update_client) {
                            $conn->commit();
                        } else {
                            $conn->rollBack();
                        }
                    endif;
                    echo "<br>";
                }
                