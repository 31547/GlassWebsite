<?php
	require dirname(__DIR__) . '/../private/autoload.php';
  $_PAGETITLE = "Blockland Glass | " . $addonObject->getName();

	include(realpath(dirname(__DIR__) . "/../private/header.php"));
?>
<div class="maincontainer">
    <?php
      include(realpath(dirname(__DIR__) . "/../private/navigationbar.php"));
    ?>
    <div class="reviewbody">
      <?php
        echo "<span style=\"font-size: 9pt;\"><a href=\"/addons/\">Add-Ons</a> >> ";
        echo "<a href=\"#\">" . htmlspecialchars($addonObject->getName()) . "</a></span>";
        echo "<h2>" . htmlspecialchars($addonObject->getName()) . "</h2>";
      ?>
      <div style="margin-bottom: 15px; display: inline-block; width: 100%; font-size: 0.8em; background-color: #ffcccc; text-align:center; padding: 10px; font-size: 1em">
        <span style="float: left"><img src="/img/icons32/tick_octagon.png"></span>
        <strong>This add-on has already been approved.</strong><br />
      </div>
    </div>
</div>
<?php
	include(realpath(dirname(__DIR__) . "/../private/footer.php"));
?>
