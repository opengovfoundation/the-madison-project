<?php
/**
 * 	Madison Functions File
 * 
 * 	@copyright Copyright &copy; 2012 by The OpenGov Foundation
 *	@license http://www.gnu.org/licenses/ GNU GPL v.3
 */

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
			email($user['email'], 'Your' . get_site_title() . 'Account Has Been Approved', '<h1>Your Company Account has been Approved!</h1><a href="'.SERVER_URL.'/login">Login Here</a>');
		
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
	
	/* HOOK INTO POSTMARK APP AND SEND TRANSACTIONAL EMAILS
	=====================================================================*/
	function email($em, $subject, $message = '') 
	{
		//require_once("postmark.class.php");

		//$email = new Mail_Postmark();
		$headers = 'MIME-Version: 1.0' . "\r\n";
		$headers = 'Content-type: text/html;' . "\r\n";
		$headers = 'From: The Madison Project' . "\r\n";
		
		try 
		{
			mail($em, $subject, $message, $headers);
			// $email-> addTo($em)
			// 				  -> from(POSTMARK_EMAIL, POSTMARK_NAME)
			// 				  -> subject($subject)
			// 				  -> messageHTML($message)
			// 				  -> send();
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
	
	/**
	*	Echo out the site title
	*/
	function site_title() {
		echo get_site_title();
	}
	
	/**
	*	Return the site title from the database
	*/
	function get_site_title() {
		//TODO: retrieve from the database 
		return "Madison";
	}
	
	/**
	*	Return the site description from the database
	*	TODO: retrieve value from the database
	*	TODO: allow descriptions for individual pages
	*/
	function get_description(){
		return "Generic site description";
	}
	
	
	/* DISPLAY HTML HEADER AND META INFORMATION AS WELL AS NAVIGATION, ETC...
	=====================================================================*/
	function get_header() { 
		global $u, $facebook; 
		
		$nav = mysql_query('SELECT meta_value FROM site_info WHERE meta_key = "nav_menu"');
		$nav = mysql_result($nav, 0);
		$nav = unserialize($nav);
	?>
		
    <!DOCTYPE html>
	<html>
        <head>
        	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        	<meta name="description" content="<?php echo get_description(); ?>">
        	<meta name="keywords" content="">
        	<meta property="og:title" content="<?php site_title(); ?>" />
        	<meta property="og:description" content="<?php echo get_description(); ?>" />
        	<meta property="og:type" content="website"/>
        	<meta property="og:url" content="<?=urlencode(SERVER_URL)?>"/>
        	<meta property="og:image" content="<?=SERVER_URL?>/assets/i/fb_meta.jpg"/>
			<meta property="og:site_name" content=""/>		    
        	<title><?php site_title(); ?></title>
        	<link href="<?=SERVER_URL?>/assets/css/styles.css" rel="stylesheet" type="text/css" />
			<?php if($_GET['type'] == 'admin' && $u->user_level == 1) : ?>
				<?php // jquery ui core ?>
				<script type="text/javascript" src="<?=SERVER_URL?>/assets/js/jquery-1.8.0.js"></script>
				<script src="<?=SERVER_URL?>/assets/js/jquery.ui.core.js"></script>
				<script src="<?=SERVER_URL?>/assets/js/jquery.ui.widget.js"></script>
				<script src="<?= SERVER_URL?>/assets/js/jquery.ui.mouse.js"></script>
				<script src="<?= SERVER_URL?>/assets/js/jquery.ui.sortable.js"></script>
				<script src="<?=SERVER_URL?>/assets/js/jquery.mjs.nestedSortable.js"></script>
			<?php else : ?>
				<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
				<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js"></script>
			<?php endif; ?>
        </head>
        <body>
        	<div id="fb-root"></div>
          	<script>
				(function(d, s, id) {
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
        	<div id="header-wrapper">
        		<div id="header">
					<a href="<?=SERVER_URL?>">
						<div id="logo"></div>
					</a>
				</div>
        	</div>
        	<div id="nav">
        		<div id="subnav-items">
					<?php $first = true; foreach($nav as $navItem) : ?>
						<div class="subnav_div"></div>
						<?php if(!isset($navItem['children'])) : ?>
							<a href="<?php echo SERVER_URL . $navItem['link']; ?>">
								<div class="subnav-item"><?php echo $navItem['label']; ?></div>
							</a>
						<?php else : ?>
							<div class="subnav_dropdown">
								<a href="<?php echo SERVER_URL . $navItem['link']; ?>">
									<div class="subnav-item">
									<?php echo $navItem['label']?><img src="/assets/i/arrow-down.png" class="dropdown_arrow"/>	
									</div>
								</a>
								<ul>
									<?php foreach($navItem['children'] as $child) : ?>
										<li>
											<a href="<?php echo SERVER_URL . $child['link']; ?>">
												<?php echo $child['label']; ?>
											</a>
										</li>
									<?php endforeach; ?>
								</ul>
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
           			<!--
					<a href="<?=SERVER_URL?>/about">
						<div class="subnav-item">About</div>
					</a>
           			<div class="subnav_div"></div>
					<div class="subnav_dropdown">Internet Freedom
						<ul>
							<li>
								<a href="<?= SERVER_URL ?>/digital-bill-of-rights" style="width:125px;">Digital Bill of Rights</a>
							</li>
							<li>
								<a class="multiline-dropdown" href="<?= SERVER_URL ?>/declaration-of-internet-freedom" style="width:125px;">Declaration of Internet Defense</a>
							</li>
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
					-->
            	</div>
        	</div>
        	<div id="nav-items">
			    <?php if($u->loggedin) : ?>
            		<a href="<?=SERVER_URL?>/edit/user"><div class="nav-item">Update Account</div></a>
            		<a href="<?=SERVER_URL?>?logout"><div class="nav-item">Logout</div></a>
					<?php if($u->user_level == 1) : ?>
						<a href="<?=SERVER_URL?>/admin"><div class="nav-item">Admin</div></a>
					<?php endif; ?>
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
            		<div id="content" <?php if($_GET['type'] == 'admin'){ echo "class='admin'"; } ?>>
<?php } 

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
          		</div>
      		</div>
		</body>
		</html>
<?php }  