<?php
	$slug = $_GET['page'];

	$bill_id = get_bill_by_slug($slug);
	$b = new Bill($bill_id, $db);
	
	if(!empty($_POST) && $u->loggedin && $u->user_level == 1){
		if(isset($_POST['form_submission']) && $_POST['form_submission'] == "bill_info"){
			$updated = false;
			foreach($_POST as $k=>$v){
				if( $k != 'form_submission' && $b->$k != $v ){
					$b->$k = $v;
					$updated = true;
				}
			}
			if($updated){
				$b->update_info();
			}
			unset($_POST);
		}
	}
?>
<a href="/admin/docs"><< Back</a>
<h1><?php echo $b->title; ?></h1>
<h2>Bill Info:</h2>
<form action="" method="post" name="bill_info">
	<label for="title">Title:</label>
	<input type="text" name="title" value="<?php echo $b->title; ?>"/>
	<label for="twitter_text">Twitter Text:</label>
	<input type="text" name="twitter_text" value="<?php echo $b->twitter_text; ?>"/>
	<label for="twitter_hash">Twitter Hashtags:</label>
	<input type="text" name="twitter_hash" value="<?php echo $b->twitter_hash; ?>"/>
	<label for="description">Description:</label>
	<textarea name="description" id="description" cols="50" rows="20"><?php echo $b->description; ?></textarea>
	<input type="hidden" value="bill_info" name="form_submission"/>
	<input type="submit" value="Update"/>
</form>