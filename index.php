<?php

ini_set('session.gc_maxlifetime', 86400);
ini_set('session.cookie_lifetime', 86400);
session_start();

require_once 'lib/functions.php';

// get page
if (!empty($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = '1';
}

//important part (function -> getPage)
$info = getPage($page);
$fileName = $info['File'];
$title = $info['Title'];

ob_start();
include 'styles.php';
$styles = ob_get_clean();
ob_start();
include 'blocks/header.php';
$header = ob_get_clean();
ob_start();
include "pages/$fileName";
$content = ob_get_clean();
ob_start();
include 'blocks/footer.php';
$footer = ob_get_clean();


$html = file_get_contents('main.html');
$html = str_replace('{{STYLES}}', $styles, $html);
$html = str_replace('{{TITLE}}', $title, $html);
$html = str_replace('{{HEADER}}', $header, $html);
$html = str_replace('{{CONTENT}}', $content, $html);
$html = str_replace('{{FOOTER}}', $footer, $html);

echo $html;

