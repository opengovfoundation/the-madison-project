<?php 
/**
 * 	Madison View Template
 * 
 * 	@copyright Copyright &copy; 2012 by The OpenGov Foundation
 *	@license http://www.gnu.org/licenses/ GNU GPL v.3
 */ 	
?>
<h1>Company Approval</h1>
<form action="" method="post">
<div id="generic-content" style="overflow:hidden; font-size:12px;">
	<h2 style="margin-bottom:10px;">Companies to Approve</h2>
	<?php $r = mysql_query("SELECT u.* FROM ".DB_TBL_USERS." AS u, ".DB_TBL_USER_META." AS um WHERE um.user=u.id AND um.meta_key='company_approved' AND um.meta_value='0'");
          while($user = mysql_fetch_assoc($r)) : ?>
          <div style="float:left; width:450px; margin-bottom:20px;">
          	<input name="companies[]" type="checkbox" value="<?=$user['id']?>" /><strong><?=$user['company']?></strong> 
          	<div style="margin-left:20px;">Name: <?=$user['fname']?> <?=$user['fname']?></div>
            <div style="margin-left:20px;">Email: <?=$user['email']?></div>
            <div style="margin-left:20px;">Phone: <?=$user['phone']?></div>
            <div style="margin-left:20px;">Position: <?=$user['position']?></div>
            <div style="margin-left:20px;">URL: <?=$user['url']?></div>
          </div>
    <?php endwhile; ?>
    <div class="clear"></div>
    <input type="submit" value="Approve Selected" style="margin:20px 20px 40px 0; float:left; " />
    <input type="button" value="Archive Selected" style="margin:20px 0 40px 0" onclick="submit_archives();"/>
</div>
<input name="action" type="hidden" value="company-approval" />
</form>
<script type="text/javascript">
  function submit_archives(){
   $("input[name=action]").val("company-archival");
   $("input[name=action]").closest("form").submit();
  }
</script>