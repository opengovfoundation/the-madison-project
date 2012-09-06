<style>#content{overflow:visible; height:600px;}</style>
<script type="text/javascript">

	bill_id	 = <?=$b->id?>;
	section  = '<?=isset($_GET['sec']) ? $_GET['sec'] : $b->init_section?>';
	selected = 0;
	view     = '<?=$b->view_user_edits ? 'edit' : 'content'?>';
	
	loggedin = <?=$u->loggedin ? 'true' : 'false'?>;
	
	$(function() {
			   
		$('.reader-section-el').click(function(){ section = $(this).attr('id'); get_section(section,(selected == 0 ? null : selected), view); });
		$('#gbt_lightbox_close').click(function() {$('#gbt_lightbox, #gbt_lightbox_content').hide();});
		
		<?php if($u->loggedin) : ?>
		$('#view-edits').click(function() {
			view = $(this).html() == 'Hide My Edits' ? 'content' : 'edit';
			get_section(section, (selected == 0 ? null : selected), view);
			$(this).html((view == 'content' ? 'Show My Edits' : 'Hide My Edits'));
		});
		<?php endif; ?>
		
		get_section(section,<?=isset($_GET['sel'])? $_GET['sel'] : 'null'?>,view);
		load_notes();
		
	});
	
</script>
<script type="text/javascript" src="assets/js/bill-reader.js"></script>
<div class="left" style="width:550px;">
    <h1 style="font-size:2em; float:left;width:550px;"><?php echo $b->title; ?></h1>
    <span style="float:left;margin-bottom:20px; width:550px; font-size:1.2em;"><?php echo $b->description; ?></span>
	  
    <div id="reader">
        <div id="reader-tools"></div>
        <div id="reader-nav">
            <?php $i = 1; foreach($b->sections as $sec) : ?>
            <div class="reader-section-el" id="rsec-<?=$sec['id']?>"><?=$i?></div>
            <?php $i++; endforeach; ?>
        </div>
        <div id="reader-content">
          <ol id="section-content" style="list-style-type:none"></ol>
        </div>
        <?php if($u->loggedin) : ?>
        <div id="view-edits"><?=$b->view_user_edits ? 'Hide My Edits' : 'Show My Edits'?></div>
        <?php endif; ?>
        <div id="reader-legend">
        	<div id="selected-legend"></div><div class="left" style="margin-right:15px;">Selected Passage</div>
        	<div id="has-note-legend"></div><div class="left">Has User Edits/Comments</div>
        	<?php if(in_array($_SERVER['REQUEST_URI'], array('/data'))) : ?>
            <div style="margin-left:10px;" id="user-generated-legend"></div><div>Bill Changes Before House Vote</div>
          <?php endif; ?>
        </div>
    </div>
    <div style="clear:both; margin:5px;">
	<?php if(isset($b->doc_location) && $b->doc_location != '' && isset($b->shortname) && $b->shortname != '') : ?>
    	<div style="float:left;">
			<a href="/contact-us?f=q">Questions about <?php echo $b->shortname; ?>?</a> or <a href="/contact-us?f=s">Have a Suggestion?</a>
		</div>
    	<div style="float:right;"><a href="<?php echo $b->doc_location; ?>">Download <?php echo $b->shortname; ?></a></div>
		<?php endif; ?>
    </div>
    <div style="clear:both; margin:5px;"></div>
</div>
<div class="right" style="width:360px; margin-top:68px;">
	<div id="share-tools">
    	<input id="show-link-popup" value="Link" style="float:right;" onclick="show_popup('link')" />
    	<div style="float:right;">
        	<a href="https://twitter.com/share" class="twitter-share-button" data-lang="en" data-text="<?php echo $b->twitter_text; ?>" data-hashtags="<?php echo $b->twitter_hash; ?>">Tweet</a> 
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
        </div>
    	<div class="fb-like" data-href="<?=SERVER_URL?>/" style="float:right; width:150px;" data-send="true" data-layout="button_count" data-width="100" data-show-faces="false" data-font="arial"></div>
        <div id="link-popup" class="popup">
          <h3>Link to Selection</h3>
          <div id="short-url"></div>
        </div> 
    </div>
    <div id="interact">
        <div id="interact-header">Participate</div>
        <h3>Add A Bill Edit / Comment</h3>
        <div id="add-notes">
            <?php if(!$u->loggedin) : ?>
            <a href="<?=SERVER_URL?>/login?redirect=<?=$_SERVER['REQUEST_URI']?>">Login to Add Comments</a>.
            <?php else : ?>
            <div id="choose-note-type">
                <input type="button" value="Make Bill Edit" onclick="choose_note('suggestion');" /> 
                <input type="button" value="Make Comment" onclick="choose_note('comment');" />
                <input id="note-type" type="hidden" value="suggestion" />
            </div>
            <div id="suggestion-frm">
                <textarea id="suggestion"></textarea>
                <input type="button" value="Preview Bill Edit" onclick="preview_suggestion();" />
            </div>
            <div id="comment-frm">
                <textarea id="comment"></textarea>
                <input type="button" value="Submit Comment" onclick="add_note('comment');" />
            </div>
            <div id="note-submitted">
                Thank you for submitting your comments.
            </div>
            <div id="frm-select">
                Select part of the bill to make a suggestion or comment.
            </div>
            <?php endif; ?>
        </div>
        <h3>
            <span id="suggestion-loader">Grabbing Suggestions...</span>
            <span id="suggestion-stats"><span id="num-suggestions"></span> Community Suggestions</span>
        </h3>
        <div id="suggestions"></div>
        <h3>
            <span id="comment-loader">Grabbing Comments...</span>
            <span id="comment-stats"><span id="num-comments"></span> Community Comments</span>
        </h3>
        <div id="comments"></div>
    </div>
    <div id="keep_updated">
      <span>Keep me updated!</span>
      <form action="" method="post">
        <label for="email">Email:</label>
        <input type="text" name="email" />
        <input type="button" id="subscribe_updates" onclick="subscribe();" value="Subscribe"/>
        <input type="hidden" name="bill" value="<?php echo $b->shortname; ?>" /> 
      </form>
    </div>
    <div id="update_success" style="display:none;">
      Subscription Successful
    </div>
    <script type="text/javascript">
      function subscribe(){
        var bill = $("input[name=bill]").val();
        var email = $("input[name=email]").val();
        var action = "subscribe";
        
        $.post('inc/jquery.php', {action: action, email:email, bill:bill}, function(data){
          if(data != 0 && data != false)
          {
            $("#keep_updated").toggle();
            $("#update_success").toggle();
          }
          else
          {
            $("#update_success").css("background", "lightred");
            $("#update_success").html("There was an error.");
            
          }
        });
      }
    </script>


</div>

<div id="gbt_lightbox"></div>
<div id="gbt_lightbox_content">
	<div id="gbt_lightbox_close"></div> 
    <div class="lightbox_page">
    	<h2>Original Passage</h2>
        <div id="suggestion_preview_original"></div>
        <h2>My Edits</h2>
        <div id="suggestion_preview_edit"></div>
        <h3>Why?</h3>
        <div><textarea id="suggestion-comment"></textarea></div>
        <input type="button" value="Submit Bill Edit" onclick="add_note('suggestion');" />
    </div>
</div>