<?php
// Home controller
$page_title = "EasyCart - Home";
$page_css = "index.css";

// Any data preparation can happen here
// $products is already available from index.php (which requires data.php)

loadView('home', [
    'page_title' => $page_title,
    'page_css' => $page_css,
    'products' => $products
]);
