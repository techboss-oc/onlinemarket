<?php
require_once 'init.php';

$user = new User();
$user->logout();

redirect('../login_page_-_onlinemarket.ng/');
