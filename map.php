<?php  

/** 
 * This page builds the html to display a map image.
 *
 * Duplicate and modification of graph.php.  
 * 2011-10-26
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
   $zabbixMapId = (int) $_GET['sysmapid'];
 
   $map_object = $zabbix->getMaps($zabbixMapId);
    
   if (isset($map_object) && count($map_object) > 0) {
      
      if ($map = $map_object) {
        ?>
  <div id="maps_general<?php echo $map->sysmapid?>">
    <div class="toolbar">
      <h1><?php echo $map->name?></h1>
      <a class="back" href="#">Back</a>
    </div>
        <img src="map_img.php?sysmapid=<?=$map->sysmapid?>" width="100%" />
  </div>
<?php
      }
  
  } else {
    echo "<p>sysmapid=" . $zabbixMapId . " is an invalid map.</p>";
  }
?>
