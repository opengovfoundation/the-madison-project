<?php
	/* ADMIN FUNCTION - ARCHIVE COMPANIES
	=====================================================================*/
	function archive_companies($ac)
	{
	  global $db;
	  
	  if(empty($ac))
	    return false;
	    
	  $db->update(DB_TBL_USER_META, array('meta_value'=>-1), "user IN (0,".implode(',', $ac).") AND meta_key='company_approved'");
	  
	  return true;
	}
	
	
	
	/* ADMIN FUNCTION - APPROVE COMPAINES
	=====================================================================*/
	function approve_companies($ac)
	{
		global $db;
		
		if(empty($ac))
			return false;
		
		$db->update(DB_TBL_USER_META, array('meta_value'=>1), "user IN (0,".implode(',', $ac).") AND meta_key='company_approved'");
					
		$r = mysql_query("SELECT email FROM ".DB_TBL_USERS." WHERE id IN (0,".implode(',',$ac).")", $db->mySQLconnR);
		while($user = mysql_fetch_assoc($r))
			email($user['email'], 'Your KeeptheWebOpen Account Has Been Approved', '<h1>Your Company Account has been Approved!</h1><a href="'.SERVER_URL.'/login">Login Here</a>');
		
		return true;
	}

	/* FIND A BILL BY SLUG - PROBABLY NOT NEEDED
	=====================================================================*/
	function get_bill_by_slug($slug)
	{
		global $db;
		$r = mysql_query("SELECT id FROM ".DB_TBL_BILLS." WHERE slug LIKE '".$slug."'", $db->mySQLconnR);
		return mysql_num_rows($r) == 0 ? 0 : mysql_result($r, 0);
	}
	
	/* HELPER FUNCTION TO REDIRECT AND EXIT
	=====================================================================*/
	function redirect($url)
	{
		header("location:".$url); exit();	
	}
	
	/* HOOK INTO POSTMAKR APP AND SEND TRANSACTIONAL EMAILS
	=====================================================================*/
	function email($em, $subject, $message = '') 
	{
		require_once("postmark.class.php");

		$email = new Mail_Postmark();
		
		try 
		{
			$email-> addTo($em)
				  -> from(POSTMARK_EMAIL, POSTMARK_NAME)
				  -> subject($subject)
				  -> messageHTML($message)
				  -> send();
		} 
		catch (Exception $e) 
		{
			error_log($e);
			return false;	
		}

		return true;
	}
	
	/* SERVER DOES NOT ALLOW FILE GET CONTENTS - CREATE ALTERNATIVE
	=====================================================================*/
	function get_url_contents($url) 
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$output = curl_exec($ch);
		curl_close($ch);  
		return $output;
	}
	
	/* DISPLAY HTML HEADER AND META INFORMATION AS WELL AS NAVIGATION, ETC...
	=====================================================================*/
	function get_header() { 
		global $u, $facebook;
		
		//URIs that will display the live stream
		$live_uris = array(); ?>
		
        <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml">
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="description" content="">
        <meta name="keywords" content="">
        <meta property="og:title" content="KeepTheWebOpen.com" />
        <meta property="og:description" content="<?= $_SERVER['REQUEST_URI'] == '/sopa-vs-open' ? 'OPEN vs SOPA & PIPA: Check out this handy infographic comparing intellectual property protection legislation in the US House of Representatives and Senate. Which works best for you? http://bit.ly/sdbzsQ' : '' ?>" />
        <meta property="og:type" content="website"/>
        <meta property="og:url" content="<?=urlencode(SERVER_URL)?>"/>
        <meta property="og:image" content="<?=SERVER_URL?>/assets/i/fb_meta.jpg"/>
		    <meta property="og:site_name" content=""/>
		    <meta name="google-site-verification" content="m2BQRC2aAif8EyVGSm5dKUAD3ypCwyMpL0OvxR9U5GA" />
		    <meta name="msvalidate.01" content="42C8F58561C76B4196A0B2613F169BFF" />
        <title>KeepTheWebOpen.com</title>
        <link href="<?=SERVER_URL?>/assets/css/styles.css" rel="stylesheet" type="text/css" />
        <?php if($_SERVER['REQUEST_URI'] == '/open') : ?>
          <link href="<?=SERVER_URL?>/assets/css/open.css" rel="stylesheet" type="text/css" />
        <?php elseif($_SERVER['REQUEST_URI'] == '/data') : ?>
          <link href="<?=SERVER_URL?>/assets/css/data.css" rel="stylesheet" type="text/css" />
        <?php elseif(in_array($_SERVER['REQUEST_URI'], array('/cispa'))) : ?>
          <link rel="stylesheet" href="<?= SERVER_URL ?>/assets/css/cispa.css" type="text/css" />
        <?php endif; ?>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
        <script type="text/javascript">

		  var _gaq = _gaq || [];
		  _gaq.push(['_setAccount', 'UA-27551361-1']);
		  _gaq.push(['_trackPageview']);
		
		  (function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		  })();
		
		</script>
        </head>
        <body>
          <div id="fb-root"></div>
          <script>(function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) {return;}
              js = d.createElement(s); js.id = id;
              js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=<?= YOUR_APP_ID ?>";
              fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
          </script>
		      <script>
            window.fbAsyncInit = function() {
              FB.init({
                appId      : '<?= YOUR_APP_ID ?>',
                status     : true, 
                cookie     : true,
                xfbml      : true,
                oauth      : true,
              });
            };
            (function(d){
               var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
               js = d.createElement('script'); js.id = id; js.async = true;
               js.src = "//connect.facebook.net/en_US/all.js";
               d.getElementsByTagName('head')[0].appendChild(js);
             }(document));
          </script>
        <?php if(in_array($_SERVER['REQUEST_URI'], $live_uris)){ include("views/live-stream.php"); }?>
        <div id="header-wrapper">
        	<div id="header"><a href="<?=SERVER_URL?>"><div id="logo"></div></a></div>
        </div>
        <div id="nav">
        	<div id="subnav-items">
            	<!--<a href="<?=SERVER_URL?>/get-involved"><div class="subnav-item" style="padding-left:0px;">Get Involved</div></a>-->
           		<a href="<?=SERVER_URL?>/about"><div class="subnav-item">About</div></a>
           		<div class="subnav_div"></div>
           		<!-- <a href="<?=SERVER_URL?>/digital-bill-of-rights"><div class="subnav-item">Digital Bill of Rights</div></a> -->
				<div class="subnav_dropdown">Internet Freedom
					<ul>
						<li><a href="<?= SERVER_URL ?>/digital-bill-of-rights" style="width:125px;">Digital Bill of Rights</a></li>
						<li><a class="multiline-dropdown" href="<?= SERVER_URL ?>/declaration-of-internet-freedom" style="width:125px;">Declaration of Internet Defense</a></li>
					</ul>
				</div>	
           		<div class="subnav_div"></div>
           		<a href="<?=SERVER_URL?>/data"><div class="subnav-item">DATA</div></a>
           		<div class="subnav_div"></div>
              	<a href="<?=SERVER_URL?>/fisma"><div class="subnav-item">FISMA</div></a>
           		<div class="subnav_div"></div>
           		<div class="subnav_dropdown">OPEN
                  <ul>
                    <li><a href="<?=SERVER_URL?>/open">Introduced</a></li>
                    <li><a href="<?=SERVER_URL?>/open-archive">Draft</a></li>
                    <li><a href="<?=SERVER_URL?>/assets/pdfs/faqs.pdf">FAQs</a></li>
                    <li><a href="<?=SERVER_URL?>/supporters">Supporters</a></li>
                  </ul>
              </div>
              <div class="subnav_div"></div>
              <!--<a href="<?=SERVER_URL?>/acta"><div class="subnav-item">ACTA</div></a>
              <div class="subnav_div"></div>-->
              <a href="<?=SERVER_URL?>/pipa"><div class="subnav-item">PIPA</div></a>
              <div class="subnav_div"></div>
              <div class="subnav_dropdown">SOPA
                <ul>
                  <li><a href="<?=SERVER_URL?>/sopa">Bill</a></li>
                  <li><a href="<?=SERVER_URL?>/sopa-markup">Markup</a></li>
                </ul>
              </div>
              <div class="subnav_div"></div>
              <a href="<?=SERVER_URL?>/acta"><div class="subnav-item">ACTA</div></a>
              <div class="subnav_div"></div>
              <a href="<?=SERVER_URL?>/tpp"><div class="subnav-item">TPP</div></a>
            </div>
        </div>
        <div id="nav-items">
			    <?php if($u->loggedin) : ?>
            <a href="<?=SERVER_URL?>/edit/user"><div class="nav-item">update account</div></a>
            <a href="?logout"><div class="nav-item">logout</div></a>
            <div class="right" style="padding:0px 15px; text-shadow: #999 2px 2px 2px; margin-top:10px;">Welcome <?=$u->display_name?></div>
          <?php else : ?>
            <?php
              $loginUrl = $facebook->getLoginUrl(array(
                'scope' => 'email',
                'redirect_uri' => SERVER_URL . '/login?via=fb&redirect=' . $_SERVER['REQUEST_URI']
              ));
            
            ?>
            <a href="<?=$loginUrl?>"><div class="nav-item login-button"></div></a>
            <a href="<?=SERVER_URL?>/signup"><div class="nav-item">Create an Account</div></a>
            <a href="<?=SERVER_URL?>/login?redirect=<?=$_SERVER['REQUEST_URI']?>"><div class="nav-item">Login</div></a>
          <?php endif; ?>
        </div>
        <div id="wrap">
        	<div id="content-wrapper">
            	<div id="content">
<?php } 

  /* DISPLAY HOMEPAGE VIDEOS
  =====================================================================*/
  function get_vids() { ?>
    <div id="sponsored-notes-title">What OPEN means to...</div>
    <div id="sponsored-notes">
    	<div class="open_video">
    	  <a href="http://youtu.be/TadQc3Vc5LQ" target="_blank">
    	    <img src="http://img.youtube.com/vi/TadQc3Vc5LQ/0.jpg" width="150" border="0" />
    	  </a>
    	  <br />
    	  <br />
    	  <a class="open_vid_title" href="#" target="_blank">Congressman Darrell Issa</a>
    	</div>
        <div class="open_video"><a href="http://youtu.be/1mVLx8AtEsk" target="_blank"><img src="http://img.youtube.com/vi/1mVLx8AtEsk/0.jpg" width="150" border="0" /></a><br /><br /><a class="open_vid_title" href="http://youtu.be/1mVLx8AtEsk" target="_blank">Senator Ron Wyden</a></div>
        <div class="open_video"><a href="http://youtu.be/4t0Pl83_Apo" target="_blank"><img src="http://img.youtube.com/vi/4t0Pl83_Apo/0.jpg" width="150" border="0" /></a><br /><br /><a class="open_vid_title" href="http://youtu.be/4t0Pl83_Apo" target="_blank">Congressman Jason Chaffetz</a></div>
        <div class="open_video"><a href="http://youtu.be/HHdDABORv_o" target="_blank"><img src="http://img.youtube.com/vi/HHdDABORv_o/0.jpg" width="150" border="0" /></a><br /><br /><a class="open_vid_title" href="http://youtu.be/HHdDABORv_o" target="_blank">Congresswoman Zoe Lofgren</a></div>
        <div class="open_video">
          <a href="http://youtu.be/Xe2Hvjl7hAA" target="_blank">
            <img src="http://img.youtube.com/vi/Xe2Hvjl7hAA/0.jpg" width="150" border="0" alt="" />
          </a>
          <br/>
          <br/>
          <a href="#" class="open_vid_title" target="_blank">Congressman Jared Polis</a>
        </div>
        <div class="open_video">
          <a href="http://youtu.be/tWQ3ubvLZ14" target="_blank">
            <img src="http://img.youtube.com/vi/tWQ3ubvLZ14/0.jpg" width="150" border="0" alt="" />
          </a>
          <br/>
          <br/>
          <a href="#" class="open_vid_title" target="_blank">Congressman Blake Farenthold</a>
        </div>
        <div class="open_video">
          <a href="http://youtu.be/7NHQdtxxREI" target="_blank">
            <img src="http://img.youtube.com/vi/7NHQdtxxREI/0.jpg" width="150" border="0" alt="" />
          </a>
          <br/>
          <br/>
          <a href="#" class="open_vid_title" target="_blank">Senator Jerry Moran</a>
        </div>
        <div class="open_video">
          <a href="http://youtu.be/bn7GGcuwrb4" target="_blank">
            <img src="http://img.youtube.com/vi/bn7GGcuwrb4/0.jpg" width="150" border="0" alt="" />
          </a>
          <br/>
          <br/>
          <a href="#" class="open_vid_title" target="_blank">Congressman John Campbell</a>
        </div>
        <div class="open_video">
          <a href="http://youtu.be/3LHLEtv908I" target="_blank">
            <img src="http://img.youtube.com/vi/3LHLEtv908I/0.jpg" width="150" border="0" alt="" />
          </a>
          <br/>
          <br/>
          <a href="#" class="open_vid_title" target="_blank">Congressman Jim Sensenbrenner</a>
        </div>
        <div class="open_video">
          <a href="http://youtu.be/8hvC43IFPPg" target="_blank">
            <img src="http://img.youtube.com/vi/8hvC43IFPPg/0.jpg" width="150" border="0" alt="" />
          </a>
          <br/>
          <br/>
          <a href="#" class="open_vid_title" target="_blank">Congressman Mike Thompson</a>
        </div>
    </div>
    <?php
  }


	/* DISPLAY FOOTER HTML
	=====================================================================*/
	function get_footer() { ?>
        	</div><!-- END CONTENT -->
        </div><!-- END CONTENT WRAPPER -->
      </div><!-- END WRAP -->
      <div id="footer">
          <div style="margin-bottom:10px;">
            <a href="<?=SERVER_URL?>/terms-conditions">Terms & Conditions</a> | &nbsp;
            <a href="<?=SERVER_URL?>/contact-us?f=b">Report a bug</a> | &nbsp; 
            From the office of Congressman <a href="http://issa.house.gov">Darrell Issa</a>
          </div>
          
          <?php /*?>&copy; Copyright 2011. keepthewebopen.com All rights reserved.<?php */?>
      </div>
<script src="//static.getclicky.com/js" type="text/javascript"></script>
<script type="text/javascript">try{ clicky.init(66512792); }catch(e){}</script>
<noscript><p><img alt="Clicky" width="1" height="1" src="//in.getclicky.com/66512792ns.gif" /></p></noscript>
    </body>
    </html>
<?php }  