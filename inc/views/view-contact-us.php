<?php
/**
 * 	Madison View Template
 * 
 * 	@copyright Copyright &copy; 2012 by The OpenGov Foundation
 *	@license http://www.gnu.org/licenses/ GNU GPL v.3
 */
?>
<h1>How are we doing?</h1>
<div id="generic-content">
<?php if(isset($response) && $response['type'] == 'error') : ?>
<div class="error">Error: <?=$response['message']?></div>
<?php elseif(isset($response) && $response['type'] == 'success') : ?>
<div class="success"><?=$response['message']?></div>
<?php endif; ?>
<form method="post" action="">
  <table width="100%" border="0" cellspacing="0" cellpadding="5">
    <tr>
      <th width="20%"><label>Your Name:</label></th>
      <td><input name="name" type="text" value="" style="width:300px;" /></td>
    </tr>
    <tr>
      <th><label>Email:</label></th>
      <td><input name="email" type="text" value="" style="width:300px;" /></td>
    </tr>
    <tr>
      <th><label>Purpose:</label></th>
      <td>
        <select name="purpose">
          <option value="">Please Select</option>
          <option value="bug" <?= (isset($_GET['f']) && $_GET['f'] == "b") ? "selected='selected'" : "" ?>>Report a Bug</option>
          <option value="question" <?= (isset($_GET['f']) && $_GET['f'] == "q") ? "selected='selected'" : "" ?>>Ask a Question</option>
          <option value="suggestion" <?= (isset($_GET['f']) && $_GET['f'] == "s") ? "selected='selected'" : "" ?>>Offer a Suggestion</option>
        </select>
      </td>
    </tr>
    <tr>
      <th valign="top"><label>Feedback:</label></th>
      <td><textarea name="feedback" cols="" rows="" style="width:300px; height:150px;"></textarea></td>
    </tr>
  </table>
  <input type="submit" value="Submit Feedback" style="margin:20px 0 40px 188px;" />
  <input name="action" type="hidden" value="feedback-submit" />
</form>
</div>
