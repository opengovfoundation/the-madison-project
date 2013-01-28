<?php
/**
 * 	Madison Admin Page
 * 
 * 	@copyright Copyright &copy; 2012 by The OpenGov Foundation
 *	@license http://www.gnu.org/licenses/ GNU GPL v.3
 */
	$docs = mysql_query('select bill, slug from bills', $db->mySQLconnRW);
?>
<a href="/admin" style="display:block; margin-bottom:10px;"><< Back</a>
<div class="list_wrapper">
	<h1>Documents</h1>
	<ul>
		<?php while($doc = mysql_fetch_array($docs)) : ?>
			<li>
				<a href="/admin/edit/<?php echo $doc['slug']; ?>">
					<?php echo $doc['bill']; ?>
				</a>
			</li>
		<?php endwhile; ?>
	</ul>
</div>
<div class="list_wrapper">
	<h1>Add Document</h1>
	<label for="title">Document Title:</label>
	<input id="title" name="title" type="text" placeholder="Title"/>
	<label for="slug">Document Slug:</label>
	<input id="slug" name="slug" type="text" placeholder="Slug"/>
	<input id="create_doc" type="submit" value="Create Document"/>
	<div id="create_message" class="ajax_message hidden"></div>
</div>
<script type="text/javascript">
	$('#create_doc').click(function(){
		console.log('Clicked.');
		var title = $('#title').val();
		var slug = $('#slug').val();
		
		$.post('admin-ajax.php', {'action':'create-doc', 'title':title, 'slug':slug}, function(data){
			data = JSON.parse(data);
			console.log(data);
			var message;
			
			//If the creation failed
			if(false === data.doc_id){
				message = 'There was an error creating the document.';
			}
			else{
				message = 'Document(id = ' + data.doc_id + ') created.';
			}
			
			$('#create_message').html(message);
			$('#create_message').removeClass('hidden');
		});
	});
	
</script>