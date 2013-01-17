<?php
/**
 * 	Madison Admin Page
 * 
 * 	@copyright Copyright &copy; 2012 by The OpenGov Foundation
 *	@license http://www.gnu.org/licenses/ GNU GPL v.3
 */
	//Get documents from db
	$docs = mysql_query('select bill, slug from bills', $db->mySQLconnRW);
	
	//Get views from views directory
	if($viewHandle = opendir(SERVER_ABS . '/inc/views')){
		$predefined = array('404', 'contact-us', 'forgot-password', 'index', 'login', 'note', 'notes', 'reader', 'user-responses');
		$views = array();
		while(false !== ($file = readdir($viewHandle))){
			if($file != "." && $file != ".." && preg_match('/^view-(.*)\.php/', $file, $name)){
				if(!in_array($name[1], $predefined)){
					array_push($views, $name[1]);
				}
			}
		}
	}
	else{
		$views = "Unable to get views";
	}
	
	$nav = mysql_query('SELECT meta_value FROM site_info WHERE meta_key = "nav_menu"');
	$nav = mysql_result($nav, 0);
	$nav = unserialize($nav);
	//print_r($nav);
	
	//Recuresively build child navigation
	function buildChildNav($parent){
		if(!isset($parent['children'])){
			return;
		}
		?>
		<ol>
		<?php
			foreach($parent['children'] as $child){
				?>
				<li class="nav_item">
					<div>
						<span><?php echo $child['label']; ?></span>
						<input type="hidden" value="<?php echo $child['link']; ?>"/>
					</div>
				</li>
				<?php
				buildChildNav($child);
			}
		?>
		</ol>
		<?php
	}
?>
<div class="list_wrapper">
	<ol id="nav_list" class="sortable">
		<h2>Navigation</h2>
		<?php foreach($nav as $navItem) : ?>	
			<li class="nav_item">
				<div>
					<span><?php echo $navItem['label']; ?></span>
					<input type="hidden" value="<?php echo $navItem['link']; ?>"/>
				</div>
				<?php 
					buildChildNav($navItem); 
				?>
			</li>
		<?php endforeach; ?>
	</ol>
	<input type="submit" value="Save Nav" id="save_nav"/>
	<div id="save_message">
		
	</div>
</div>
<div id="menu_items" class="menu_items">
	<h2>Documents</h2>
	<ul>
		<?php $c = 0; while($doc = mysql_fetch_array($docs)) : ?>
			<li id="doc_<?php echo $c++;?>" class="menu_item"><input type="checkbox" value="<?php echo $doc['slug']?>"/><?php echo $doc['bill']; ?></li>
		<?php endwhile; ?>
	</ul>
	<h2>Views</h2>
	<ul>
		<?php $c = 0; foreach($views as $view) : ?>
			<li id="view_<?php echo $c++; ?>" class="menu_item"><input type="checkbox" value="<?php echo strtolower($view); ?>"/><span><?php echo ucwords(str_replace('-', ' ', $view)); ?></span></li>
		<?php endforeach; ?>
	</ul>
	<input id="add_nav_items" type="submit" value="Add To Nav"/>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		try{
			$('.sortable').nestedSortable({
				handle: 'div',
				items: 'li',
				toleranceElement: 'div'
			});
		}
		catch(err){
			console.log(err);
		}
	});
	
	//Adding items to Nav menu
	$('#add_nav_items').click(function(){
		
		//Add each checked item
		$('.menu_item').each(function(){
			
			//Retrieve the item's checkbox input
			var checkbox = $(this).find('input[type="checkbox"]');
			
			//Only add the checked items
			if(checkbox.is(':checked')){
				
				//Get item's data
				var label = $(this).find('span').text();
				var slug = checkbox.val();
				
				//Append item to nav list
				$('#nav_list').append('<li class="nav_item"><div><span>' + label + '</span><input type="hidden" value="/' + slug + '" /></div></li>');
				
				//Uncheck the item
				checkbox.prop('checked', false);
			}
		});
	});
	
	//Returns the count of a parent's children
	function hasChildren(parent){
		var children = parent.children('ol').children('.nav_item').length;
		
		return children;
	}
	
	function buildNavTree(parent){
		var label, link, ret = {};
		
		//get nav item label and link
		label = parent.children('div').children('span').text();
		link = parent.children('div').children('input[type="hidden"]').val();
		
		//Create json object for nav item
		ret = {'label' : label, 'link' : link};
		
		if(!hasChildren(parent)){
			return ret;
		}
		
		//Instantiate children array
		var childTrees = new Array();
		
		//Iterate through the children items and build a tree for each
		parent.children('ol').children('.nav_item').each(function(){
			var childTree = buildNavTree($(this));
			childTrees.push(childTree);
		});
		
		//Add children to nav item json object
		ret.children = childTrees;
		
		return ret;
	}
	
	//Saving Navigation
	$('#save_nav').click(function(){
		//Instantiate nav array
		var nav = new Array();
		
		//Iterate over each nav item
		$('#nav_list > .nav_item').each(function(){
			//Build the tree for this item
			var navTree = buildNavTree($(this));
			
			//Push this item's tree to the nav list
			nav.push(navTree);
		});
		
		//Post the nav menu for saving
		$.post('admin-ajax.php', {'action':'save-nav', 'nav-menu': nav}, function(data){
			//Parse json string
			data = JSON.parse(data);
			
			//Check rows updated
			if(data.rows > 0){//Save Successful
				$('#save_message').html('Nav menu updated.');
			}
			else if(data.rows == 0){//No changes
				$('#save_message').html('Nothing to update.');
			}
			else{//Error
				$('#save_message').html('There was an error updating the nav menu.');
			}
		});
	});
</script>