<?php
/**
 * 	Madison Admin Page
 * 
 * 	@copyright Copyright &copy; 2012 by The OpenGov Foundation
 *	@license http://www.gnu.org/licenses/ GNU GPL v.3
 */
	$docs = mysql_query('select bill, slug from bills', $db->mySQLconnRW);
?>
<a href="/admin"><< Back</a>
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