<?php
  require("../CentralIndex.php");
  $ci = new CentralIndex("<insert api key here>");
  print_r($ci->getEntityAdvertisers( "restaurant","cork", 3, "ie","en"));
?>