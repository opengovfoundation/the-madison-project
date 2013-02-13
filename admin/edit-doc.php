<?php
/**
 * 	Madison Admin Page
 * 
 * 	@copyright Copyright &copy; 2012 by The OpenGov Foundation
 *	@license http://www.gnu.org/licenses/ GNU GPL v.3
 */
	$slug = $_GET['page'];

	$bill_id = get_bill_by_slug($slug);
	$b = new Bill($bill_id, $db);
	
	if(!empty($_POST) && $u->loggedin && $u->user_level == 1){
		if(isset($_POST['form_submission']) && $_POST['form_submission'] == "doc_info"){
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
	
	//Build out each section recursively
	function buildSection($parent){
		?>
		<li class="doc_item">
			<div class="sort_handle">
				<span>
					<?php
						$content = $parent['content'];
						if(strlen($content) > 50){
							$content = substr($content, 0, 100) . '...';
						}
					?>
					<img style="cursor:pointer;" src="/assets/i/arrow-down.png" alt="Dropdown Arrow" class="dropdown_arrow" />
					<?php echo $content; ?>
				</span>
				<input name="content_id" type="hidden" value="<?php echo $parent['id']; ?>"/>
				<p class="add_doc_item">+</p>
				<p class="delete_doc_item">x</p>
				<div class="floatingCirclesG hidden">
					<div class="f_circleG frotateG_01"></div>
					<div class="f_circleG frotateG_02"></div>
					<div class="f_circleG frotateG_03"></div>
					<div class="f_circleG frotateG_04"></div>
					<div class="f_circleG frotateG_05"></div>
					<div class="f_circleG frotateG_06"></div>
					<div class="f_circleG frotateG_07"></div>
					<div class="f_circleG frotateG_08"></div>
				</div>
				<p class="doc_item_content"><textarea><?php echo $parent['content']; ?></textarea></p>
			</div>
		</li>
		<ol>
			<?php 
				if(isset($parent['children'])){
					foreach($parent['children'] as $child){
						buildSection($child);
					}
				}
			?>
		</ol>
		<?php
	}
?>
<a href="/admin/docs">&lt;&lt; Back</a>
<h1><?php echo $b->title; ?> </h1>
<div class="edit_info_link">Edit Document Info</div>
<div class="edit_doc_info hidden">
	<h2>Document Info:</h2>
	<form action="" method="post" name="doc_info">
		<label for="title">Title:</label>
		<input type="text" name="title" value="<?php echo $b->title; ?>"/>
		<label for="shortname">Shortname <img src="/assets/i/question_icon.png" alt="Info" title="Document short title used in various places in the document reader" class="admin_info_ico"/>:</label>
		<input type="text" name="shortname" value="<?php echo $b->shortname; ?>" />
		<label for="twitter_text">Twitter Text <img src="/assets/i/question_icon.png" alt="Info" title="The text that is pre-filled in the tweet button" class="admin_info_ico"/>:</label>
		<input type="text" name="twitter_text" value="<?php echo $b->twitter_text; ?>"/>
		<label for="twitter_hash">Twitter Hashtags <img src="/assets/i/question_icon.png" alt="Info" title="The hashes that are appended to the pre-filled tweet" class="admin_info_ico"/>:</label>
		<input type="text" name="twitter_hash" value="<?php echo $b->twitter_hash; ?>"/>
		<label for="doc_location">Document Location <img src="/assets/i/question_icon.png" alt="Info" title="The location on disk of the pdf for this document" class="admin_info_ico"/>:</label>
		<input type="text" name="doc_location" value="<?php echo $b->doc_location; ?>"/>
		<label for="description">Description:</label>
		<textarea name="description" id="description" cols="50" rows="20"><?php echo $b->description; ?></textarea>
		<input type="hidden" value="bill_info" name="form_submission"/>
		<input type="submit" value="Update"/>
	</form>
</div>
<div class="edit_doc_content list_wrapper">
	<h2>Document Content:</h2>
	<div class="doc_sections">
		<?php
			//Create tabs for each section and show the first
			$c = 0;
			foreach($b->sections as $id => $section):
				$section_parts = $b->get_section($section['id']);
			?>
			<div id="doc_tab_<?php echo $c; ?>" class="doc_section <?php echo $c == 0 ? '' : 'hidden'?>">
				<?php //Create nestedSortable lists in each tab ?>
				<ol id="doc_list_<?php echo $c; ?>" class="sortable doc_list">
					<?php buildSection($section_parts); ?>
				</ol>
			</div>
			<?php
			endforeach;
		?>
		<input id="doc_id" name="doc_id" type="hidden" value="<?php echo $bill_id; ?>"/>
		<input type="submit" value="Save Doc" id="save_doc"/>
		<div id="save_message" class="ajax_message hidden"></div>
	</div>
</div>
<script type="text/javascript" src="<?php echo SERVER_URL . "/assets/js/edit-doc.js"?>"></script>
