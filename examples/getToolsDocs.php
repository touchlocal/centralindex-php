<?php
  require("../CentralIndex.php");
  $ci = new CentralIndex("<insert api key here>");
  print_r($ci->getToolsDocs("phone","html"));
?>