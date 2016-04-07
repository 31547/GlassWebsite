<?php
session_start();
require_once(realpath(dirname(__DIR__) . "/../private/class/AddonManager.php"));
var_dump($_POST);
$userObject = UserManager::getCurrent();
if(isset($_POST['action']) && is_object($userObject)) {
  if($_POST['action'] == "Approve") {
    // approve
    AddonManager::approveAddon($_POST['aid'], $_POST['board'], $userObject->getBLID());
    header('Location: /addons/list.php');
  } else if($_POST['action'] == "Reject") {
    // reject
  }
}
?>
