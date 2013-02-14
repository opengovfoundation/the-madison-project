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
	
	//Recursively build child navigation
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
					<div class="sort_handle">
						<span><?php echo $child['label']; ?></span>
						<input type="hidden" value="<?php echo $child['link']; ?>"/>
						<p class="delete_nav_item">x</p>
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
				<div class="sort_handle">
					<span><?php echo $navItem['label']; ?></span>
					<input type="hidden" value="<?php echo $navItem['link']; ?>"/>
					<p class="delete_nav_item">x</p>
				</div>
				<?php 
					buildChildNav($navItem); 
				?>
			</li>
		<?php endforeach; ?>
	</ol>
	<input type="submit" value="Save Nav" id="save_nav"/>
	<div id="save_message" class="ajax_message hidden">
		
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
<script type="text/javascript" src="<?php echo SERVER_URL . "/assets/js/edit-nav.js"; ?>"></script>