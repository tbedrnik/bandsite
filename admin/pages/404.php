<?php
/**
 * Vykreslení 404
 *
 * @package Bandsite Manager
 */

$template = file_get_contents("templates/error.html");
$template = str_replace("%error%","Error 404",$template);
$template = str_replace("%msg%","It seems your requested page doesn't exist",$template);

include("index_header.php");
echo $template;
include("index_footer.php");
