<?php
  require("../CentralIndex.php");
  $ci = new CentralIndex("<api key goes here>");
  print_r($ci->getEntitySearchWhoBylocation( "starbucks", "dublin", 10, 1, "ie","en"));
?>