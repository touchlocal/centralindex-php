<?php
  require_once("../CentralIndex.php");
  
  $ci = new CentralIndex("<insert api key here>");
  print_r($ci->getEntity("379236608286720"));
?>