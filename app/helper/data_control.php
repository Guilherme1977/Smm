<?php
function CreateApiKey($data) {
    global $conn;
    $data = md5($data['email'] . $data['username'] . rand(9999, 2324332));
    $row = $conn->prepare('SELECT * FROM clients WHERE apikey=:key ');
    $row->execute(['key' => $data]);
    if ($row->rowCount()) {
        CreateApiKey();
    } else {
        return $data;
    }
}
function createPaymentCode() {
    global $conn;
    $row = $conn->prepare('SELECT * FROM payments WHERE payment_method!=:method ORDER BY payment_privatecode DESC LIMIT 1 ');
    $row->execute(['method' => 7]);
    $row = $row->fetch(PDO::FETCH_ASSOC);
    return $row['payment_privatecode'] + 1;
}
function generate_shopier_form($data) {
    $api_key = $data->apikey;
    $secret = $data->apisecret;
    $user_registered = date('Y.m.d');
    $time_elapsed = time() - strtotime($user_registered);
    $buyer_account_age = (int)$time_elapsed / 86400;
    $currency = 0;
    $dataArray = $data;
    $productinfo = $data->item_name;
    $producttype = 1;
    $productinfo = str_replace('"', '', $productinfo);
    $productinfo = str_replace('"', '', $productinfo);
    $current_language = 0;
    $current_lan = 0;
    $modul_version = '1.0.4';
    srand(time(NULL));
    $random_number = rand(1000000, 9999999);
    $args = ['API_key' => $api_key, 'website_index' => $data->website_index, 'platform_order_id' => $data->order_id, 'product_name' => $productinfo, 'product_type' => $producttype, 'buyer_name' => $data->buyer_name, 'buyer_surname' => $data->buyer_surname, 'buyer_email' => $data->buyer_email, 'buyer_account_age' => $buyer_account_age, 'buyer_id_nr' => 0, 'buyer_phone' => $data->buyer_phone, 'billing_address' => $data->billing_address, 'billing_city' => $data->city, 'billing_country' => 'TR', 'billing_postcode' => '', 'shipping_address' => $data->billing_address, 'shipping_city' => $data->city, 'shipping_country' => 'TR', 'shipping_postcode' => '', 'total_order_value' => $data->ucret, 'currency' => $currency, 'platform' => 0, 'is_in_frame' => 1, 'current_language' => $current_lan, 'modul_version' => $modul_version, 'random_nr' => $random_number];
    $data = $args['random_nr'] . $args['platform_order_id'] . $args['total_order_value'] . $args['currency'];
    $signature = hash_hmac('SHA256', $data, $secret, true);
    $signature = base64_encode($signature);
    $args['signature'] = $signature;
    $args_array = [];
    foreach ($args as $key => $value) {
        $args_array[] = '<input type=\'hidden\' name=\'' . $key . '\' value=\'' . $value . '\'/>';
    }
    if (!empty($dataArray->apikey) && !empty($dataArray->apisecret) && !empty($dataArray->website_index)) {
        $_SESSION['data']['payment_shopier'] = true;
        return '<html> <!doctype html><head> <meta charset="UTF-8"> <meta content="True" name="HandheldFriendly"> <meta http-equiv="X-UA-Compatible" content="IE=edge">' . "\r\n" . '      <meta name="robots" content="noindex, nofollow, noarchive" />' . "\r\n" . '      <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0" /> <title lang="tr">Secured Payment Page</title><body><head>' . "\r\n" . '      <form action="https://www.shopier.com/ShowProduct/api_pay4.php" method="post" id="shopier_payment_form" class="ddnone">' . implode('', $args_array) . '<script>setInterval(function(){document.getElementById("shopier_payment_form").submit();},2000)</script></form></body></html>';
    }
}
function username_check($username) {
    if (preg_match('/^[a-z\\d_]{4,32}$/i', $username)) {
        $validate = true;
    } else {
        $validate = false;
    }
    return $validate;
}
function email_check($email) {
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $validate = true;
    } else {
        $validate = false;
    }
    return $validate;
}
function userdata_check($where, $data) {
    global $conn;
    $row = $conn->prepare('SELECT * FROM clients WHERE ' . $where . '=:data ');
    $row->execute(['data' => $data]);
    if ($row->rowCount()) {
        $validate = true;
    } else {
        $validate = false;
    }
    return $validate;
}
function userlogin_check($username, $pass) {
    global $conn;
    $row = $conn->prepare('SELECT * FROM clients WHERE username=:username && password=:password ');
    $row->execute(['username' => $username, 'password' => md5(sha1(md5($pass))) ]);
    if ($row->rowCount()) {
        $validate = true;
    } else {
        $validate = false;
    }
    return $validate;
}
function serviceSpeed($speed, $price) {
    switch ($speed) {
        case '1':
            return '<span class="dspeed11">' . priceFormat($price) . ' <span class="fa fa-arrow-down dspeed2"> </span></span>';
        break;
        case '2':
            return '<span class="dspeed12">' . priceFormat($price) . ' <span class="fa fa-arrow-down dspeed2"> </span></span>';
        break;
        case '3':
            return '<span class="dspeed13">' . priceFormat($price) . ' <span class="fa fa-compress dspeed2"> </span></span>';
        break;
        case '4':
            return '<span class="dspeed14">' . priceFormat($price) . ' <span class="fa fa-arrow-up dspeed2"> </span></span>';
        break;
    }
}
function service_price($service) {
    global $conn;
    global $user;
    $row = $conn->prepare('SELECT * FROM clients_price WHERE service_id=:s_id && client_id=:c_id ');
    $row->execute(['s_id' => $service, 'c_id' => $user['client_id']]);
    if ($row->rowCount()) {
        $row = $row->fetch(PDO::FETCH_ASSOC);
        $price = $row[__FUNCTION__];
    } else {
        $row = $conn->prepare('SELECT * FROM services WHERE service_id=:id');
        $row->execute(['id' => $service]);
        $row = $row->fetch(PDO::FETCH_ASSOC);
        $price = $row[__FUNCTION__];
    }
    return $price;
}
function client_price($service, $userid) {
    global $conn;
    global $user;
    $row = $conn->prepare('SELECT * FROM clients_price WHERE service_id=:s_id && client_id=:c_id ');
    $row->execute(['s_id' => $service, 'c_id' => $userid]);
    if ($row->rowCount()) {
        $row = $row->fetch(PDO::FETCH_ASSOC);
        $price = $row['service_price'];
    } else {
        $row = $conn->prepare('SELECT * FROM services WHERE service_id=:id');
        $row->execute(['id' => $service]);
        $row = $row->fetch(PDO::FETCH_ASSOC);
        $price = $row['service_price'];
    }
    return $price;
}
function open_bankpayment($user) {
    global $conn;
    $row = $conn->prepare('SELECT * FROM payments WHERE client_id=:client && payment_status=:status && payment_method=:method ');
    $row->execute(['client' => $user, 'status' => 1, 'method' => 6]);
    $validate = $row->rowCount();
    return $validate;
}
function open_ticket($user) {
    global $conn;
    $row = $conn->prepare('SELECT * FROM tickets WHERE client_id=:client && status=:status ');
    $row->execute(['client' => $user, 'status' => 'pending']);
    $validate = $row->rowCount();
    return $validate;
}
function new_ticket($user) {
    global $conn;
    $row = $conn->prepare('SELECT * FROM tickets WHERE client_id=:client && support_new=:new ');
    $row->execute(['client' => $user, 'new' => 2]);
    $validate = $row->rowCount();
    return $validate;
}
function countRow($data) {
    global $conn;
    $where = '';
    if ($data['where']) {
        $where = 'WHERE ';
        foreach ($data['where'] as $key => $value) {
            $where.= ' ' . $key . '=:' . $key . ' && ';
            $execute[$key] = $value;
        }
        $where = substr($where, 0, -3);
    } else {
        $execute[] = '';
    }
    $row = $conn->prepare('SELECT * FROM ' . $data['table'] . ' ' . $where . ' ');
    $row->execute($execute);
    $validate = $row->rowCount();
    return $validate;
}
function getRows($data) {
    global $conn;
    $where = '';
    $order = '';
    $order = '';
    $limit = '';
    $execute[] = '';
    if ($data['where']) {
        $where = 'WHERE ';
        foreach ($data['where'] as $key => $value) {
            $where.= ' ' . $key . '=:' . $key . ' && ';
            $execute[$key] = $value;
        }
        $where = substr($where, 0, -3);
    }
    if ($data['order']) {
        $order = 'ORDER BY ' . $data['order'] . ' ' . $data['order_type'];
    }
    if ($data['limit']) {
        $limit = 'LIMIT ' . $data['limit'];
    }
    $row = $conn->prepare('SELECT * FROM ' . $data['table'] . ' ' . $where . ' ' . $order . ' ' . $limit . ' ');
    $row->execute($execute);
    if ($row->rowCount()) {
        $rows = $row->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $rows = [];
    }
    return $rows;
}
function getRow($data) {
    global $conn;
    $where = 'WHERE ';
    foreach ($data['where'] as $key => $value) {
        $where.= ' ' . $key . '=:' . $key . ' && ';
        $execute[$key] = $value;
    }
    $where = substr($where, 0, -3);
    $row = $conn->prepare('SELECT * FROM ' . $data['table'] . ' ' . $where . ' ');
    $row->execute($execute);
    if ($row->rowCount()) {
        $row = $row->fetch(PDO::FETCH_ASSOC);
    } else {
        $row = [];
    }
    return $row;
}
function statutoTR($status) {
    switch ($status) {
        case 'pending':
            $statu = 'Pending';
        break;
        case 'inprogress':
            $statu = 'Inprogress';
        break;
        case 'completed':
            $statu = 'Completed';
        break;
        case 'partial':
            $statu = 'Partial';
        break;
        case 'processing':
            $statu = 'Processing';
        break;
        case 'canceled':
            $statu = 'Canceled';
        break;
    }
    return $statu;
}
function dripfeedstatutoTR($status) {
    switch ($status) {
        case 'active':
            $statu = 'Active';
        break;
        case 'canceled':
            $statu = 'Canceled';
        break;
        case 'completed':
            $statu = 'Completed';
        break;
    }
    return $statu;
}
function ticketStatu($status) {
    switch ($status) {
        case 'closed':
            $statu = 'Closed';
        break;
        case 'answered':
            $statu = 'Answered';
        break;
        case 'pending':
            $statu = 'Pending';
        break;
    }
    return $statu;
}
function subscriptionstatutoTR($status) {
    switch ($status) {
        case 'active':
            $statu = 'Active';
        break;
        case 'canceled':
            $statu = 'Canceled';
        break;
        case 'completed':
            $statu = 'Completed';
        break;
        case 'paused':
            $statu = 'Paused';
        break;
        case 'expired':
            $statu = 'Expired';
        break;
        case 'limit':
            $statu = 'Processing';
        break;
    }
    return $statu;
}
function serviceTypeGetList($type) {
    switch ($type) {
        case 'Default':
            $service_type = 1;
        break;
        case 'Package':
            $service_type = 2;
        break;
        case 'Custom Comments':
            $service_type = 3;
        break;
        case 'Custom Comments Package':
            $service_type = 4;
        break;
        case 'Mentions':
            $service_type = 5;
        break;
        case 'Mentions with hashtags':
            $service_type = 6;
        break;
        case 'Mentions custom list':
            $service_type = 7;
        break;
        case 'Mentions custom list':
            $service_type = '8';
        break;
        case 'Mentions user followers':
            $service_type = 9;
        break;
        case 'Mentions media likers':
            $service_type = 10;
        break;
        case 'Subscriptions':
            $service_type = 11;
        break;
        default:
        break;
    }
    return $service_type;
}
function array_group_by(array $arr, $key):
    array {
        if (!is_string($key) && !is_int($key) && !is_float($key) && !is_callable($key)) {
            trigger_error('array_group_by(): The key should be a string, an integer, a float, or a function', 256);
        }
        $isFunction = !is_string($key) && is_callable($key);
        $grouped = [];
        foreach ($arr as $value) {
            $groupKey = NULL;
            if ($isFunction) {
                $groupKey = $key($value);
            } else if (is_object($value)) {
                $groupKey = $value->{$key};
            } else {
                $groupKey = $value[$key];
            }
            $grouped[$groupKey][] = $value;
        }
        if (2 < func_num_args()) {
            $args = func_get_args();
            foreach ($grouped as $groupKey => $value) {
                $params = array_merge([$value], array_slice($args, 2, func_num_args()));
                $grouped[$groupKey] = call_user_func_array(__FUNCTION__, $params);
            }
        }
        return $grouped;
    }
    function instagramProfilecheck($array) {
        $type = $array['type'];
        if ($type == 'username') {
            $profile = 'https://www.instagram.com/' . $array['url'];
            $search_type = 'profile';
        } else {
            $profile = $array['url'];
            $check = explode('instagram.com/', $profile);
            if (substr($check[1], 0, 2) == 'p/') {
                $search_type = 'photo';
            } else {
                $search_type = 'profile';
            }
        }
        $html = file_get_contents($profile);
        $arr = explode('window._sharedData = ', $html);
        $arr = explode(';</script>', $arr[1]);
        $obj = json_decode($arr[0], true);
        if ($search_type == 'profile') {
            $user = $obj['entry_data']['ProfilePage'][0]['graphql']['user'];
            $private = $obj['entry_data']['ProfilePage'][0]['graphql']['user']['is_private'];
        } else {
            $user = $obj['entry_data']['PostPage'][0]['graphql']['shortcode_media']['owner'];
            $private = $obj['entry_data']['PostPage'][0]['graphql']['shortcode_media']['owner']['is_private'];
            if (!$user) {
                $user = $obj['entry_data']['ProfilePage'][0]['graphql']['user'];
                $private = $obj['entry_data']['ProfilePage'][0]['graphql']['user']['is_private'];
            }
        }
        if ($array['return'] == 'private') {
            return $private;
        }
    }
    function instagramCount($array) {
        $type = $array['type'];
        if ($type == 'username') {
            $profile = 'https://www.instagram.com/' . $array['url'];
            $search_type = 'profile';
        } else {
            $profile = $array['url'];
            $check = explode('instagram.com/', $profile);
            if (substr($check[1], 0, 2) == 'p/') {
                $search_type = 'photo';
            } else {
                $search_type = 'profile';
            }
        }
        $html = file_get_contents($profile);
        $arr = explode('window._sharedData = ', $html);
        $arr = explode(';</script>', $arr[1]);
        $obj = json_decode($arr[0], true);
        if ($array['search'] == 'instagram_follower') {
            $user = $obj['entry_data']['ProfilePage'][0]['graphql']['user'];
            $count = $obj['entry_data']['ProfilePage'][0]['graphql']['user']['edge_followed_by']['count'];
        } else {
            $user = $obj['entry_data']['PostPage'][0]['graphql']['shortcode_media']['edge_media_preview_like']['count'];
            $count = $obj['entry_data']['PostPage'][0]['graphql']['shortcode_media']['edge_media_preview_like']['count'];
        }
        if (!$count) {
            return 0;
        } else {
            return $count;
        }
    }
    function force_download($file) {
        if (isset($file) && file_exists($file)) {
            header('Content-length: ' . filesize($file));
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $file . '"');
            readfile((string)$file);
        } else {
            echo 'No file selected';
        }
    }
    function dayPayments($day, $ay, $year) {
        global $conn;
        $first = $year . '-' . $ay . '-' . $day . ' 00:00:00';
        $last = $year . '-' . $ay . '-' . $day . ' 23:59:59';
        $row = $conn->query('SELECT SUM(payment_amount) FROM payments WHERE payment_delivery=\'2\' && payment_status=\'3\' && payment_create_date<=\'' . $last . '\' && payment_create_date>=\'' . $first . '\'  ')->fetch(PDO::FETCH_ASSOC);
        $charge = $row['SUM(payment_amount)'];
        return number_format($charge, 2, '.', ',');
    }
    function monthPayments($ay, $year) {
        global $conn;
        $first = $year . '-' . $ay . '-1 00:00:00';
        $last = $year . '-' . $ay . '-31 23:59:59';
        $row = $conn->query('SELECT SUM(payment_amount) FROM payments WHERE payment_delivery=\'2\' && payment_status=\'3\' && payment_create_date<=\'' . $last . '\' && payment_create_date>=\'' . $first . '\'  ')->fetch(PDO::FETCH_ASSOC);
        $charge = $row['SUM(payment_amount)'];
        return number_format($charge, 2, '.', ',');
    }
    function totalPayments() {
        global $conn;
        $row = $conn->query('SELECT SUM(payment_amount) FROM payments WHERE payment_delivery=\'2\' && payment_status=\'3\'  ')->fetch(PDO::FETCH_ASSOC);
        $charge = $row['SUM(payment_amount)'];
        return number_format($charge, 2, '.', ',');
    }
    function manualOrders() {
        global $conn;
        $row = $conn->query('SELECT * FROM orders WHERE order_api=\'0\'');
        $row->execute(); 
        $orders = $row->rowCount();
        return $orders;
    }
    function dayCharge($day, $ay, $year) {
        global $conn;
        $first = $year . '-' . $ay . '-' . $day . ' 00:00:00';
        $last = $year . '-' . $ay . '-' . $day . ' 23:59:59';
        $row = $conn->query('SELECT SUM(order_charge) FROM orders WHERE order_create<=\'' . $last . '\' && order_create>=\'' . $first . '\' && dripfeed=\'1\' && subscriptions_type=\'1\'      ')->fetch(PDO::FETCH_ASSOC);
        $charge = $row['SUM(order_charge)'];
        return number_format($charge, 2, '.', ',');
    }
    function monthCharge($month, $year) {
        global $conn;
        $first = $year . '-' . $month . '-1 00:00:00';
        $last = $year . '-' . $month . '-31 23:59:59';
        $row = $conn->query('SELECT SUM(order_charge) FROM orders WHERE order_create<=\'' . $last . '\' && order_create>=\'' . $first . '\'  && dripfeed=\'1\' && subscriptions_type=\'1\'    ')->fetch(PDO::FETCH_ASSOC);
        $charge = $row['SUM(order_charge)'];
        return number_format($charge, 2, '.', ',');
    }
    function monthChargeNet($month, $year) {
        global $conn;
        $first = $year . '-' . $month . '-1 00:00:00';
        $last = $year . '-' . $month . '-31 23:59:59';
        $row = $conn->query('SELECT SUM(order_profit) FROM orders WHERE order_create<=\'' . $last . '\' && order_create>=\'' . $first . '\' && dripfeed=\'1\' && subscriptions_type=\'1\' && order_api!=\'0\'    ')->fetch(PDO::FETCH_ASSOC);
        $row2 = $conn->query('SELECT SUM(order_charge) FROM orders WHERE order_create<=\'' . $last . '\' && order_create>=\'' . $first . '\' && dripfeed=\'1\' && subscriptions_type=\'1\'    ')->fetch(PDO::FETCH_ASSOC);
        $charge = $row2['SUM(order_charge)'] - $row['SUM(order_profit)'];
        return number_format($charge, 2, '.', ',');
    }
    function dayOrders($day, $month, $year) {
        global $conn;
        $first = $year . '-' . $month . '-' . $day . ' 00:00:00';
        $last = $year . '-' . $month . '-' . $day . ' 23:59:59';
        return $row = $conn->query('SELECT order_id FROM orders WHERE order_create<=\'' . $last . '\' && order_create>=\'' . $first . '\' ')->rowCount();
    }
    function monthOrders($month, $year) {
        global $conn;
        $first = $year . '-' . $month . '-1 00:00:00';
        $last = $year . '-' . $month . '-31 23:59:59';
        return $row = $conn->query('SELECT order_id FROM orders WHERE order_create<=\'' . $last . '\' && order_create>=\'' . $first . '\' ')->rowCount();
    }
    function priceFormat($price) {
        $priceExplode = explode('.', $price);
        if ($priceExplode[1]) {
            if (strlen($priceExplode[1]) == 1) {
                return $price . '0';
            } else {
                return $price;
            }
        } else {
            return $price . '.00';
        }
    }
?>