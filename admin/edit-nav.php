<?php
/**
 * 	Madison Admin Page
 * 
 * 	@copyright Copyright &copy; 2012 by The OpenGov Foundation
 *	@license http://www.gnu.org/licenses/ GNU GPL v.3
 */

	$docs = mysql_query('select bill, slug from bills', $db->mySQLconnRW);
?>
<style type="text/css">
	ol{list-style-type: none;}
	.sortable li div  {
				border: 1px solid #d4d4d4;
				-webkit-border-radius: 3px;
				-moz-border-radius: 3px;
				border-radius: 3px;
				border-color: #D4D4D4 #D4D4D4 #BCBCBC;
				padding: 6px;
				margin: 0;
				cursor: move;
				background: #f6f6f6;
				background: -moz-linear-gradient(top,  #ffffff 0%, #f6f6f6 47%, #ededed 100%);
				background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ffffff), color-stop(47%,#f6f6f6), color-stop(100%,#ededed));
				background: -webkit-linear-gradient(top,  #ffffff 0%,#f6f6f6 47%,#ededed 100%);
				background: -o-linear-gradient(top,  #ffffff 0%,#f6f6f6 47%,#ededed 100%);
				background: -ms-linear-gradient(top,  #ffffff 0%,#f6f6f6 47%,#ededed 100%);
				background: linear-gradient(top,  #ffffff 0%,#f6f6f6 47%,#ededed 100%);
				filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#ededed',GradientType=0 );

			}
</style>


<h1>Navigation</h1>
<ol id="nav_list" class="sortable">
<?php
	$c = 1;
	while($doc = mysql_fetch_array($docs)) : 
?>
	<li id="<?php echo $c++; ?>"><div><?php echo $doc['bill']; ?></div></li>
<?php endwhile; ?>
</ol>
<script type="text/javascript">
	$(document).ready(function(){
		try{
			$('ol#nav_list').nestedSortable({
				handle: 'div',
				items: 'li',
				toleranceElement: 'div'
			});
		}
		catch(err){
			console.log(err);
		}
	});
	
</script>