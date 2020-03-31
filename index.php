<?php
session_start();
require 'config/config.php';
if(isset($_GET['code'])){
  include 'elements/header.php';
  include 'settings.php';
  include 'elements/footer.php';
}elseif (isset($_GET['url'])) {
  include 'alert.php';
}elseif(isset($_GET['settings'])){
  include 'elements/header.php';
  include 'customize.php';
  include 'elements/footer.php';
}else{
  include 'elements/header.php';
  include 'authorize.php';
  include 'elements/footer.php';
}
?>
