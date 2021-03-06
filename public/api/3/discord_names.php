<?php
require dirname(__DIR__) . '/../../private/autoload.php';

use Glass\DiscordKeyManager;
use Glass\UserLog;

header('Content-Type: text/json');

$post_data = file_get_contents('php://input');

$ret  = new stdClass();
$secret = $_GET['secret'] ?? false;
$data = json_decode($post_data);

if(!$data && !is_array($data->blids ?? false) && !is_array($data->discords ?? false)) {
  $ret->status = "bad-request";
  die(json_encode($ret));
}

if($secret === false || !DiscordKeyManager::checkSecret($secret)) {
  $ret->status = "bad-secret";
  die(json_encode($ret));
  return;
}

$blids = $data->blids ?? [];
$discords = $data->discords ?? [];

$blid_map = DiscordKeyManager::getBlids($discords);
$discord_map = DiscordKeyManager::getDiscords($blids);

$users = $blid_map + array_flip($discord_map);

$usernames = UserLog::getUsernames($users);

$ret->users = [];

foreach($users as $discord=>$blid) {
  $user = $ret->users[$discord] ?? new stdClass();

  $user->name = utf8_encode($usernames[$blid] ?? false);
  $user->blid = $blid;

  $ret->users[$discord] = $user;
}

$ret->status = "success";

die(json_encode($ret, JSON_PRETTY_PRINT | JSON_FORCE_OBJECT) . "\n");
