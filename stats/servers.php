<?php
	require_once dirname(__DIR__) . "/private/class/GroupManager.php";
	require_once dirname(__DIR__) . "/private/class/UserManager.php";
	require_once dirname(__DIR__) . "/private/class/ServerTracker.php";

	$_PAGETITLE = "Blockland Glass | Current Servers";

	include(realpath(dirname(__DIR__) . "/private/header.php"));
	include(realpath(dirname(__DIR__) . "/private/navigationbar.php"));

	$servers = ServerTracker::getActiveServers();
?>
<style>
.list td {
  padding: 10px;
}

.list tr:nth-child(2n+1) td {
  background-color: #ddd;
}

.list tr:first-child td {
  background-color: #777;
  color: #fff;
  font-weight: bold;
}

.list tr td:first-child {
  border-radius: 10px 0 0 10px;
}

.list tr td:last-child {
  border-radius: 0 10px 10px 0;
}

.list {
  margin: 0 auto;
}

.maincontainer p {
  text-align: center;
}

form {
  text-align: center;
}

</style>
<div class="maincontainer">
	<table class="list">
		<tbody>
			<tr>
				<td>Host</td>
				<td>IP:Port</td>
				<td>Users</td>
			</tr>
			<?php foreach($servers as $s) {
		    echo "<tr><td style=\"vertical-align: top\"><b>" . $s->host . "</b></td><td style=\"vertical-align: top\">" . $s->ip . ":" . $s->port . "</td>";
				$clients = json_decode($s->clients);
				$str = "";
				if((sizeof($clients) - 1) > 0) {
					foreach($clients as $cl) {
						$name = htmlspecialchars($cl->name);
						$str = $str . $name . " <i>(" . $cl->blid . ")</i><br/>";
					}
					echo "<td>$str</td></tr>";
				} else {
					//echo "<td style=\"vertical-align: top\"><i>Empty</i></td></tr>";
					echo "<td></td></tr>";
				}
		  } ?>
		</tbody>
	</table>
</div>
