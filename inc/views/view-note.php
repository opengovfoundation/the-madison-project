<?php if($u->loggedin) : ?>
<script type="text/javascript">
	$(function() {
		$('.note-tool').click(function(){ ldf_note($(this)); });
	});
	
	function ldf_note(el)
	{
		note_id = $(el).parent().parent().attr('id');
		type	= $(el).attr('class');
		type    = type.replace('note-tool ', '');
		type	= type.replace('-large-btn', '');
		type	= type.replace('-btn', '');
	
		$.post('../../inc/jquery.php', {'action':'add-ldf-note', 'bill_id':<?=$b->id?>, 'note':note_id, 'type':type}, function(res) 
		{
			$('#'+note_id+'_'+type+'s').html(parseInt($('#'+note_id+'_'+type+'s').html()) + 1);
			
			type = type == 'like' ? 'Liked' : (type == 'dislike' ? 'Disliked' : 'Flagged');
			if(note_id == <?=$note['id']?>)
				$(el).parent().html('<strong><?=ucwords($_GET['note_type'])?> '+type+'!</strong>').css('margin-top', '4px');
			else
				$(el).parent().html('<strong>Comment '+type+'!</strong>');
				
		}, 'json');	
	}

	function add_note_comment()
	{
		$.post('../../inc/jquery.php', {'action':'add-note-comment', 'note':$('#note-comment').val(), 'bill_id':<?=$b->id?>, 'part_id':<?=$note['part_id']?>, 'type':'comment', 'parent':<?=$note['id']?>}, function(res) 
		{
			document.location.reload();
		});
	}
	
</script>
<?php endif; ?>
<h1><a href="<?=SERVER_URL?>/<?=$b->slug?>"><?=$b->bill?></a></h1>
<div id="generic-content">
	<?php if(!$note) : ?>
    <h2 style="margin:40px 0;">This <?=ucwords($_GET['note_type'])?> does not exist or has been removed due to inappropriate content.</h2>
    <?php else : ?>
	<div class="fb-like" style="float:left; width:150px;" data-send="true" data-layout="button_count" data-width="150" data-show-faces="false" data-font="arial"></div>
    <div style="float:left;">
        <a href="https://twitter.com/share" class="twitter-share-button" data-count="none">Tweet</a><script type="text/javascript" src="//platform.twitter.com/widgets.js"></script>
    </div>
	<h2 class="clear" style="padding-top:20px;"><?=$_GET['note_type'] == 'suggestion' ? 'Original' : 'Bill'?> Passage</h2>
    <?=$orig?>
    <h2 style="margin-top:30px;"><?=$_GET['note_type'] == 'suggestion' ? 'Suggested Edits' : 'Comment'?></h2>
	<?=$note['note']?>
    <div class="note-meta" id="<?=$note['id']?>">
		<div class="left"><strong><?=$note['user']?></strong> &nbsp;&nbsp; <span style="font-style:italic; font-size:.8em"><?=$note['time_stamp']?></span></div>
        <?php if($u->loggedin) : ?>
        <div class="note-tools-large">
       		<div class="note-tool flag-large-btn" title="Flag as Inappropriate"></div>
        	<div class="note-tool dislike-large-btn" title="Dislike"></div>
        	<div class="note-tool like-large-btn" title="Like"></div>
        </div>
        <?php endif; ?>
        <div class="right" style="margin:10px 15px 0 0">
			<span id="<?=$note['id']?>_likes"><?=$note['likes']?></span> Likes, <span id="<?=$note['id']?>_dislikes"><?=$note['dislikes']?></span> Dislikes
            <?php if(!$u->loggedin) : ?>
            <div style="margin-top:10px;"><a href="<?=SERVER_URL?>/login?redirect=<?=$_SERVER['REQUEST_URI']?>">Login to comment</a></div>
            <?php endif; ?>
        </div>
    </div>
    <?php if($u->loggedin) : ?>
    <div id="note-comment-container">
    	<textarea id="note-comment"></textarea>
        <input name="" type="button" value="Comment" onclick="add_note_comment();" />
    </div>
    <?php endif; ?>
    <ul id="note-comments">
    	<?php foreach($b->comments as $n) : 
				if($n['parent'] == $note['id']) :?>
        		<li id="<?=$n['id']?>">
                	<div class="comment-content"><?=$n['note']?></div>
                    <div class="comment-info" style="float:left; width:200px;"><strong><?=$n['user']?></strong> &nbsp;&nbsp; <span style="font-style:italic"><?=$note['time_stamp']?></span></div>
                    <div class="comment-tools">
                    	<?php if($u->loggedin) : ?>
                    	<div title="Flag as Inappropriate" class="note-tool flag-btn"></div>
                    	<div title="Dislike" class="note-tool dislike-btn"></div>
                        <div title="Like!" class="note-tool like-btn"></div>
                        <?php endif; ?>
                    </div>
                    <div style="text-align:right; width:150px; margin:10px 15px 0 0;" class="right">
						<span id="<?=$n['id']?>_likes"><?=$n['likes']?></span> Likes, <span id="<?=$n['id']?>_dislikes"><?=$n['dislikes']?></span> Dislikes</div>
                </li>
        <?php endif; endforeach; ?>
    </ul>
    <?php endif; ?>
</div>