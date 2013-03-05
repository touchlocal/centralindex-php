<?php
  require("../CentralIndex.php");
  $ci = new CentralIndex("<insert api key here>");
  print_r($ci->getEntitySearchWhat( "hotel", 10, 1, "ie","en"));
?>