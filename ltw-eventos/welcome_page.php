<?
session_start();
include ('templates/header.php');
if (!isset($_SESSION['userId']))
  include ('templates/create_account.php');
include('templates/search.php');
include ('templates/footer.php');
?>
