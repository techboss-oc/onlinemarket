<?php
require_once '../core/init.php';

// Redirect to search page with category filter if provided, or just search page
$cat = $_GET['cat'] ?? '';
$url = '../search_results_page_-_onlinemarket.ng/';
if ($cat) {
    $url .= '?category=' . urlencode($cat);
}

redirect($url);
