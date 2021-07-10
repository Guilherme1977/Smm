<?php
$title.= " FAQs";
if ($user["client_type"] == 1) {
    Header("Location:" . site_url('logout'));
}
