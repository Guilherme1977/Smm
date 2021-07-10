<?php
if (route(2) && is_numeric(route(2))):
    $page = route(2);
else:
    $page = 1;
endif;

require admin_view('plugins');
