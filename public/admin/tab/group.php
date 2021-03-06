<h1>Group Management</h1>

<?php
  use Glass\UserManager;
  use Glass\GroupManager;

	if(!$user->inGroup("Administrator")) {
    die('You do not have permission to access this area.');
  }

  if(!isset($_GET['id'])) {
    die('No group ID specified.');
  }

  $gid = $_GET['id'];
  $group = GroupManager::getFromID($gid);

  if(!$group) {
    die('Invalid group ID.');
  }

  $dirty = false;

  // if(isset($_POST['name'])) {
    // GroupManager::editGroupByGroupID($gid, "name", $_POST['name']);
  // }

  if(isset($_POST['icon']) && $group->icon != $_POST['icon']) {
    GroupManager::editGroupByGroupID($gid, "icon", $_POST['icon']);
    $dirty = true;
  }

  if(isset($_POST['color']) && $group->color != $_POST['color']) {
    GroupManager::editGroupByGroupID($gid, "color", $_POST['color']);
    $dirty = true;
  }

  if(isset($_POST['desc']) && $group->description != $_POST['desc']) {
    GroupManager::editGroupByGroupID($gid, "desc", $_POST['desc']);
    $dirty = true;
  }

  if($dirty) {
    $group = GroupManager::getFromID($gid);
  }
?>

<h2><?php echo $group->name . (substr($group->name, strlen($group->name) - 1, 1) == "s" ? "" : "s"); ?></h2>

<ul>
  <?php
    $users = GroupManager::getUsersFromGroupID($gid);

    foreach($users as $blid) {
      $user = UserManager::getFromBlid($blid);
      $blid = $user->getBLID();
      echo "<li><a href=\"/user/view.php?blid=" . $blid . "\">" . $user->getUsername() . "</a> (" . $blid . ")</li>";
    }
  ?>
</ul>

<hr>

<form method="post">
  <table class="formtable">
    <tbody>
      <tr><td class="center" colspan="2"><h3>Edit Group</h3></td></tr>
      <tr><td>Name:</td><td><input type="text" name="name" id="name" value="<?php echo $group->name; ?>" disabled></td></tr>
      <tr><td>Icon:</td><td><input type="text" name="icon" id="icon" value="<?php echo $group->icon; ?>"></td></tr>
      <tr><td>Color:</td><td><input style="background-color: #<?php echo $group->color; ?>" type="text" name="color" id="color" value="<?php echo $group->color; ?>"></td></tr>
      <tr><td>Description:</td><td><textarea name="desc" id="desc"><?php echo $group->description; ?></textarea></tr>
      <tr><td class="center" colspan="2"><input class="yellow" type="submit"></td></tr>
    </tbody>
  </table>
  <input type="hidden" name="csrftoken" value="<?php echo($_SESSION['csrftoken']); ?>">
  <?php
    if(isset($_POST['redirect'])) {
      echo("<input type=\"hidden\" name=\"redirect\" value=\"" . htmlspecialchars($_POST['redirect']) . "\">");
    }
  ?>
</form>