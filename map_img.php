<?php  

/** 
 * This page retreives the map image and returns raw JPEG data
 *
 * Duplicate and modification of graph_img.php.  
 * 2011-10-20
 *
 * ?sysmapid={NUMBER}
 *   NUMBER is the zabbix sysmapid of the map
 */

  require_once("config.inc.php");
  require_once("functions.php");
  require_once("class_zabbix.php");
  require_once("cookies.php");

  $arrSettings["zabbixApiUrl"] = str_replace("api_jsonrpc.php", "", $zabbixApi);
  $zabbix = new Zabbix($arrSettings);

  // Populate our class
  $zabbix->setUsername($zabbixUser);
  $zabbix->setPassword($zabbixPass);
  $zabbix->setZabbixApiUrl($zabbixApi);

  // Login
  if (isset($zabbixAuthHash) && strlen($zabbixAuthHash) > 0) {
    // Try it with the authentication hash we have
    $zabbix->setAuthToken($zabbixAuthHash);
  } elseif (strlen($zabbix->getUsername()) > 0 && strlen($zabbix->getPassword()) > 0 && strlen($zabbix->getZabbixApiUrl()) > 0) {
    $zabbix->login();
  }

  if (!$zabbix->isLoggedIn()) {
    header("Location: index.php");
    exit();
  }

  $mapid = (int) $_GET['sysmapid'];
  
  // Set correct header
  header("Content-Type: image/jpg");
  
  // Read the file & output
  print $zabbix->getMapImageById($mapid);
?>