<?php
namespace Glass;

class DiscordKeyManager {
  public static $verified_db = false;

  public static function newKey($blid) {
    $database = new DatabaseManager();
    DiscordKeyManager::verifyTable($database);

    $key = bin2hex(openssl_random_pseudo_bytes(4));
    $key = strtolower($key);

    $blid = $database->sanitize($blid);
    $key = $database->sanitize($key);

    $database->query("DELETE FROM `user_discord_key` WHERE `blid`='$blid';");
    $database->query("INSERT INTO `user_discord_key` (`blid`, `key`) VALUES ('$blid', UNHEX('$key'));");

    return $key;
  }

  public static function verifyKey($key) {
    $database = new DatabaseManager();
    DiscordKeyManager::verifyTable($database);

    $key = strtolower($key);
    $key = $database->sanitize($key);

    $res = $database->query("SELECT `blid` FROM `user_discord_key` WHERE `key`=UNHEX('$key') AND `generated` > NOW() - INTERVAL 5 MINUTE");

    if($res->num_rows == 1) {
      $row = $res->fetch_row();
      return $row[0];
    } else {
      return false;
    }
  }

  public static function linkDiscordBlid($blid, $discord) {
    $database = new DatabaseManager();
    DiscordKeyManager::verifyTable($database);

    $blid    = $database->sanitize($blid);
    $discord = $database->sanitize($discord);

    $res = $database->query("INSERT INTO `user_discord_map` (`blid`, `discord`) VALUES ('$blid', '$discord')");

    if($res === false) {
      $result = $database->query("SELECT `discord` FROM `user_discord_map` WHERE `blid`='$blid'");
      if($result && $result->num_rows > 0) {
        $row = $result->fetch_row();
        return $row[0];
      }
    } else {
      return true;
    }

    return false;
  }

  public static function getDiscord($blid) {
    $database = new DatabaseManager();

    $blid = $database->sanitize($blid);
    $res = $database->query("SELECT `discord` FROM `user_discord_map` WHERE `blid`='$blid'");

    if($res && $res->num_rows > 0) {
      $row = $res->fetch_row();
      return $row[0];
    }

    return false;
  }

  public static function getBlid($discord) {
    $database = new DatabaseManager();

    $discord = $database->sanitize($discord);
    $res = $database->query("SELECT `blid` FROM `user_discord_map` WHERE `discord`='$discord'");

    if($res && $res->num_rows > 0) {
      $row = $res->fetch_row();
      return $row[0];
    }

    return false;
  }

  public static function getBlids($discords) {
    if(sizeof($discords) == 0)
      return [];

    $str = "";
		foreach($discords as $discord) {
			if(!is_numeric($discord)) {
				continue;
			}

			if($str === "")
				$str = $discord;
			else
				$str .= "," . $discord;
		}

    $db = new DatabaseManager();

    $resource = $db->query("SELECT `blid`,`discord` FROM `user_discord_map` WHERE `discord` IN (" . $db->sanitize($str) . ")");

		$ret = [];
    while(($obj = $resource->fetch_object()) != null) {
			$ret[$obj->discord] = $obj->blid;
		}

		return $ret;
  }

  public static function getDiscords($blids) {
    if(sizeof($blids) == 0)
      return [];

    $str = "";
		foreach($blids as $blid) {
			if(!is_numeric($blid)) {
				continue;
			}

			if($str === "")
				$str = $blid;
			else
				$str .= "," . $blid;
		}

    $db = new DatabaseManager();

    $resource = $db->query("SELECT `blid`,`discord` FROM `user_discord_map` WHERE `blid` IN (" . $db->sanitize($str) . ")");

		$ret = [];
    while(($obj = $resource->fetch_object()) != null) {
			$ret[$obj->blid] = $obj->discord;
		}

		return $ret;
  }

  public static function checkSecret($secret) {
    $keyData = json_decode(file_get_contents(dirname(__DIR__) . "/config.json"));
    return hash_equals($keyData->discord_secret, $secret);
  }

  public static function verifyTable($database) {
		if(DiscordKeyManager::$verified_db)
			return;

		DiscordKeyManager::$verified_db = true;

		if(!$database->query("CREATE TABLE IF NOT EXISTS `user_discord_key` (
			`blid` INT NOT NULL,
			`key` BINARY(4) NOT NULL,
			`generated` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
			UNIQUE KEY (`blid`))")) {
			throw new \Exception("Error creating user_discord_key table: " . $database->error());
		}

    if(!$database->query("CREATE TABLE IF NOT EXISTS `user_discord_map` (
			`blid` INT NOT NULL,
			`discord` BIGINT NOT NULL,
			`linked` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
			UNIQUE KEY (`blid`))")) {
			throw new \Exception("Error creating user_discord_key table: " . $database->error());
		}
	}
}

?>
