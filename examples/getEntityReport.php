<?php
  require("../CentralIndex.php");
  $ci = new CentralIndex("<insert api key here>");
  print_r($ci->getEntityReport("379236608286720","379236608299008","en"));
?>