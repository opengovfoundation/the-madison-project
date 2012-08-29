<?php if($u->loggedin) : ?>
<script type="text/javascript">
	$(function() {
		$('.flag-btn, .like-btn, .dislike-btn').click(function(){ ldf_note($(this)); });
	});
	
	function ldf_note(el)
	{
		note_id = $(el).parent().parent().attr('id');
		type	= $(el).attr('class');
		type	= type.replace('-btn', '');
	
		$.post('../../inc/jquery.php', {'action':'add-ldf-note', 'note':note_id, 'type':type}, function(res) 
		{
			$('#'+note_id+'_'+type+'s').html(parseInt($('#'+note_id+'_'+type+'s').html()) + 1);
			
			type = type == 'like' ? 'Liked' : (type == 'dislike' ? 'Disliked' : 'Flagged');
			$(el).parent().html('<strong>Comment '+type+'!</strong>');
				
		}, 'json');	
	}
	
</script>
<?php endif; ?>
<div id="generic-content">
	<div class="fb-like" style="float:left; width:150px;" data-send="true" data-layout="button_count" data-width="150" data-show-faces="false" data-font="arial"></div>
    <div style="float:left;">
        <a href="https://twitter.com/share" class="twitter-share-button" data-count="none">Tweet</a><script type="text/javascript" src="//platform.twitter.com/widgets.js"></script>
    </div>
    <?php foreach($notes as $n_type=>$ns) : ?>
	<h2 class="clear" style="padding-top:20px;"><?=$n_type == 'suggestions' ? 'Suggested Bill Edits by: '.$user->display_name : 'Comments by: '.$user->display_name?></h2>
    <?php if(empty($ns)) : ?>
    <div style="margin-top:20px;"><?=$n_type == 'suggestions' ? $user->display_name.' has not suggested any bill edits.' : $user->display_name.' has not commented on any bill passages.'?></div>
    <?php else : ?>
    <ul id="note-comments">
    	<?php foreach($ns as $n) : ?>
        		<li id="<?=$n['id']?>">
                	<div class="comment-content"><?=$n['note']?></div>
                    <div class="comment-info" style="float:left; width:200px;"><strong><?=$n['user']?></strong> &nbsp;&nbsp; <span style="font-style:italic"><?=$n['time_stamp']?></span></div>
                    <div class="comment-tools">
                    	<?php if($u->loggedin) : ?>
                    	<div title="Flag as Inappropriate" class="flag-btn"></div>
                    	<div title="Dislike" class="dislike-btn"></div>
                        <div title="Like!" class="like-btn"></div>
                        <?php endif; ?>
                    </div>
                    <div style="text-align:right; width:150px; margin:10px 15px 0 0;" class="right">
						<span id="<?=$n['id']?>_likes"><?=isset($n['like']) ? $n['like'] : 0?></span> Likes, <span id="<?=$n['id']?>_dislikes"><?=isset($n['dislike']) ? $n['dislike'] : 0?></span> Dislikes</div>
                </li>
        <?php endforeach; ?>
    </ul>
    <?php endif; endforeach; ?>
</div>