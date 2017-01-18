<?php
  use Glass\RepositoryManager;
  $repoInfo = RepositoryManager::getRepository($addon);
  if($repoInfo == false) {
    $repoExists = false;
    $url = "";
    $type = "JSON";
    $channel = "";
  } else {
    $repoExists = true;
    $url = $repoInfo->url;
    $type = $repoInfo->type;
    $channel = $repoInfo->channel;
  }
?>

<script type="text/javascript">
  $(document).ready(function () {
    $('#add').on('click', function(e) {
      $('#action').val('add');
      $('#repoForm').submit();
    })

    $('#delete').on('click', function(e) {
      $('#action').val('delete');
      $('#repoForm').submit();
    })

    $('#repoForm').on('submit', function(e) {
      e.preventDefault();
      $.ajax({
        url : $(this).attr('action') || window.location.pathname,
        type: "POST",
        data: $(this).serialize(),
        success: function (data) {
          try {
            var obj = JSON.parse(data);
            if(obj.status == "error") {
              $("#form_output").html(obj.error);
            } else {
              $("#form_output").html(obj.status);
              $("#delete").css('display', 'auto');
            }
          } catch(e) {
            $("#form_output").html(data);
          }
        },
        error: function (jXHR, textStatus, errorThrown) {
          alert(errorThrown);
        }
      });
    });
  });
</script>
<style>
a.btn:hover {
  text-decoration: none;
}
</style>
<div id="form_output" style="font-size: 1.8em; font-weight: bold; text-align:center; padding-bottom: 20px">
  Upstream Repository
</div>
<form method="post" action="/ajax/repository.php" id="repoForm">
  <input type="hidden" name="aid" value="<?php echo $addon->getId();?>">
  <input type="hidden" name="action" id="action" value="">
  <table class="formtable">
    <tbody>
      <tr>
        <td><b>Upstream Repository Url</b></td>
        <td><input type="text" name="url" value="<?php echo $url ?>"/></td>
      </tr>
      <tr>
        <td><b>Repository Type</b>
        <td>
          <select name="type">
            <?php
            $options = ["JSON", "TML"];
            foreach($options as $option) {
              if($option == $type) {
                echo "<option selected value=\"$option\">$option</option>";
              } else {
                echo "<option value=\"$option\">$option</option>";
              }
            }
            ?>
          </select>
        </td>
      </tr>
      <tr>
        <td><b>Channel</b></td>
        <td><input type="text" name="channel" value="<?php echo $channel ?>"/></td>
      </tr>
      <tr>
        <td colspan="2" style="text-align:center">
          <a class="btn green" style="font-size: 1em; padding: 10px 20px;" id="add" href="#">Add</a>
          <a class="btn red"   style="font-size: 1em; padding: 10px 20px; display: <?php echo ($repoExists ? "auto" : "none"); ?>" id="remove" href="#">Remove</a>
        </td>
      </tr>
    </tbody>
  </table>
  <input type="hidden" name="csrftoken" value="<?php echo($_SESSION['csrftoken']); ?>">
</form>