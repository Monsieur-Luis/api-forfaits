<?php
header('Content-Type: application/json');

require_once 'controleurs/options.php';

?>

  <?php
  $controllerOptions=new ControlleurOption;
  $controllerOptions->afficherJSON();
  ?>



