<?php
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