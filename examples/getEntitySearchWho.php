<?php
  require("../CentralIndex.php");
  $ci = new CentralIndex("<insert api key here>");
  print_r($ci->getEntitySearchWho( "starbucks", 10, 1, "ie","en"));
?>