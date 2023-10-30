<?php
$config = array (
  "urls" => array (
    "baseUrl" => "your_base_url"
  ),
  "paths" => array (
    "resources" => "/resources",
    "images" => array (
      "content" => $_SERVER["DOCUMENT_ROOT"] . "/public/images/content",
      "layout" => $_SERVER["DOCUMENT_ROOT"] . "/public/images/layout"
    )
  )
);

define('SITE_ROOT', $config['urls']['baseUrl']);

defined("LIBRARY_PATH") or 
  define("LIBRARY_PATH", realpath(dirname(__FILE__) . '/library'));

defined("TEMPLATE_PATH") or 
  define("TEMPLATE_PATH", realpath(dirname(__FILE__) . '/templates'));

# Truncate to the level of base folder
defined("STYLE_PATH") or 
  define("STYLE_PATH", realpath(dirname(__FILE__, 2) . '/public/css'));

/* For error reporting -- dev only */
ini_set("display_errors", true);
ini_set("display_startup_errors", true);
ini_set("error_reporting", true);
error_reporting(E_ALL|E_STRCT);
?>
