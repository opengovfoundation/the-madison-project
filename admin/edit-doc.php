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
			<div>
				<span>
					<?php
						$content = $parent['content'];
						if(strlen($content) > 50){
							$content = substr($content, 0, 50) . '...';
							?>
							<img style="cursor:pointer;" src="/assets/i/arrow-down.png" alt="Dropdown Arrow" class="dropdown_arrow" />
							<?php
						}
					?>
					<?php echo $content; ?>
				</span>
				<input type="hidden" value="<?php echo $parent['id']; ?>"/>
				<p class="delete_doc_item">delete</p>
				<p class="doc_item_content"><textarea><?php echo $parent['content']; ?></textarea></p>
			</div>
		</li>
		<?php
		if(!isset($parent['children'])){
			return;
		}
		?>
		<ol>
			<?php 
				foreach($parent['children'] as $child){
					buildSection($child);
				}
			?>
		</ol>
		<?php
	}
?>
<a href="/admin/docs">&lt;&lt; Back</a>
<h1><?php echo $b->title; ?></h1>
<div class="edit_doc_info">
	<h2>Document Info:</h2>
	<form action="" method="post" name="doc_info">
		<label for="title">Title:</label>
		<input type="text" name="title" value="<?php echo $b->title; ?>"/>
		<label for="shortname">Shortname:</label>
		<input type="text" name="shortname" value="<?php echo $b->shortname; ?>" />
		<label for="twitter_text">Twitter Text:</label>
		<input type="text" name="twitter_text" value="<?php echo $b->twitter_text; ?>"/>
		<label for="twitter_hash">Twitter Hashtags:</label>
		<input type="text" name="twitter_hash" value="<?php echo $b->twitter_hash; ?>"/>
		<label for="doc_location">Document Location:</label>
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
				<ol id="doc_list_<?php echo $c; ?>" class="sortable">
					<?php buildSection($section_parts); ?>
				</ol>
			</div>
			<?php
			endforeach;
			
			
			
				
			//Add ability to add new sections
				
			//Save the document
		
		?>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		try{
			$('.sortable').nestedSortable({
				handle: 'div',
				items: 'li',
				toleranceElement: 'div',
				maxLevels: 0
			});
		}
		catch(err){
			console.log(err);
		}
	});
	
	$('.doc_item .dropdown_arrow').click(function(){
		var sibling_content = $(this).parent('span').siblings('.doc_item_content');
		
		if(sibling_content.hasClass('expanded')){
			sibling_content.removeClass('expanded');
		}
		else{
			sibling_content.addClass('expanded');
		}
	});
	
	$('.delete_doc_item').click(function(){
		$(this).parent('div').parent('.doc_item').remove();
	});
</script>
