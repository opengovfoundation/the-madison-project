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
  <?php if($_SERVER['REQUEST_URI']== "/sopa"){ ?>
    <h1 style="font-size:2em; float:left;width:550px;">House Judiciary Committee Markup of #SOPA: H.R. 3261, the Stop Online Piracy Act</h1>
    <span style="float:left;margin-bottom:20px; width:550px; font-size:1.2em;">
    On December 15-16, 2011, the House Judiciary Committee considered the Stop Online Piracy Act (SOPA). <a href="<?= SERVER_URL ?>/login?redirect=<?=$_SERVER['REQUEST_URI']?>">Log in</a> to comment or edit the bill and visit the SOPA markup <a href="<?= SERVER_URL ?>/sopa">page</a> to see the debate and legislative action.  If you're looking for a SOPA alternative, click here to see our plan: <a href="<?=SERVER_URL?>/open">OPEN.</a>
    </span>
  <?php } ?>
  <?php if($_SERVER['REQUEST_URI'] == "/pipa"){ ?>
    <h1 style="font-size:2em; float:left;width:550px;">Senate Judiciary Committee Markup of #PIPA: S. 968, the Protecting Real Online Threats to Economic Creativity and Theft of Intellectual Property Act</h1>
    <span style="float:left;margin-bottom:20px; width:550px; font-size:1.2em;">
    On May 26, 2011, the Senate Judiciary Committee approved PIPA.  <a href="<?= SERVER_URL ?>/login?redirect=<?=$_SERVER['REQUEST_URI']?>">Log in</a> to comment or edit the bill.  If you're looking for a PIPA alternative, click here to see our plan: <a href="<?=SERVER_URL?>/open">OPEN.</a>
    </span>
  <?php }?>
	<h1 style="font-size:1.5em; float:left;"><?=$b->bill?><?= $_SERVER['REQUEST_URI'] == "/sopa" ? "<span style='font-style:italic; font-size:.6em; margin-left:5px;'>(Click <a href='/open'>here</a> to read the OPEN Act)</span>" : ""?></h1>
	  <?php if(in_array($_SERVER['REQUEST_URI'], array("/open"))){ ?>
	    <span style="width:550px;display:block; float:left; margin-bottom:10px;">
	      The OPEN Act was formally introduced in the U.S. House of Representatives on January 18, 2012. This version includes <a href="/user-comments">user-generated improvements</a> to the <a href="/open-archive">draft version</a>. OPEN secures two fundamental principles. First, Americans have a right to benefit from what they've created. And second, Americans have a right to an open Internet. Our duty is to protect these rights. That's why congressional Republicans, Democrats and the public came together to write the OPEN Act. But it's only a start. We need your ideas: <a href="/signup">sign up</a>, comment and collaborate to build a better bill.</span>
	  <?php }elseif($_SERVER['REQUEST_URI'] == '/open-archive'){ ?>
	  <span style="width:550px;display:block; float:left; margin-bottom:10px;">The OPEN Act secures two fundamental principles. First, Americans have a right to benefit from what they've created. And second, Americans have a right to an open internet. Our duty is to protect these rights. That's why congressional Republicans and Democrats came together to write the OPEN Act. But it's only a start. We need your ideas: <a href="/signup">sign up</a>, comment and collaborate to build a better bill.</span>
	  <?php }elseif(in_array($_SERVER['REQUEST_URI'], array('/acta'))){ ?>
	    <span style="width:550px; display:block; float:left; margin-bottom:10px">
	      Stopping <a href="/sopa">SOPA</a> and <a href="/pipa">PIPA</a> was a historic victory for digital citizens, but ACTA potentially poses a similar threat to the global Internet community.  While the agreement’s stated goal of strengthening intellectual property rights is one all should support, it does so by undermining individual privacy rights and by empowering an unaccountable enforcement bureaucracy.  And just like SOPA and PIPA, ACTA was crafted without input from citizens and key stakeholders in a secretive, closed-door process.
<br/><br/>
        Worse, ACTA appears to be an unconstitutional power grab started by President George W. Bush and completed by President Barack Obama - despite the White House’s January 14 criticism of legislative solutions that harm the Internet and erode individual rights.  The Constitution gives Congress the power to pass intellectual property legislation - like SOPA and PIPA - and gives the Senate the power to ratify treaties.  But the Obama Administration maintains that ACTA is not even a treaty, justifying the exclusion of both American citizens and their elected representatives.  It is a practice Vice President Joe Biden <a href="http://nyti.ms/zOXd75">decried</a> as a U.S. Senator.

        Closed doesn’t cut it.  We opened up ACTA in <a href="/about">Madison</a> so you can <a href="/signup">sign up</a>, speak out and collaborate to build a better “treaty.”
	    </span>
	  <?php }elseif(in_array($_SERVER['REQUEST_URI'], array('/fisma'))){ ?>
	    <span style="width:550px; display:block; float:left; margin-bottom:10px">
	      <div style="margin:10px 0;">
	        <span style="font-weight:bold;">STATUS:</span> <a href="http://oversight.house.gov/release/release-house-approves-fisma/">Passed by the U.S. House of Representatives on April 26, 2012 by Unanimous Voice Vote</a>
	      </div>
	      Cybersecurity threats represent one of the most serious national security, public safety, and economic challenges we face as a nation.  These threats range from individual criminal hackers to organized criminal groups, from terrorist networks to advanced nation states.  Unfortunately, it also appears that this threat will likely never go away, so we must get more proficient at confronting the threat.  In the words of the President, “cybersecurity is a challenge that we as a government or as a country are not adequately prepared to counter.” 
	      <br/><br/>
	      The Federal Information Security Amendments Act of 2012 enhances the <a href="http://csrc.nist.gov/drivers/documents/FISMA-final.pdf">Federal Information Security Management Act (FISMA) of 2002</a> by improving the framework for ensuring security over the information technology systems that support the federal government.  It establishes a mechanism for stronger oversight through a focus on automated and continuous monitoring of cybersecurity threats and conducting regular threat assessments. 
	      <br/><br/>
	      Currently, federal agencies are struggling with cyber-security threats, and this update to FISMA is aimed to incorporate the last decade of technological innovation, while also addressing FISMA shortcomings realized over the past years.  FISMA had become a compliance activity, even at times when compliance appeared to supersede security. 
	      <br/><br/>
	      This update is long overdue and inaction is no longer acceptable: that’s why <a href="https://twitter.com/#!/darrellissa">Rep. Darrell Issa (R-CA)</a> is introducing FISMA 2.0 here in <a href="/about">Madison</a>.  The bill is the product of years of <a href="http://oversight.house.gov">House Oversight Committee</a> fact-finding and close consultation with key stakeholders on the front lines of government and private-sector cybersecurity efforts – including public <a href="http://oversight.house.gov/hearing/cybersecurity-assessing-the-nations-ability-to-address-the-growing-cyber-threat/">full committee</a> and <a href="http://oversight.house.gov/hearing/cybersecurity-assessing-the-immediate-threat-to-the-united-states/">subcommittee hearings</a>. 
	      <br/><br/>
	      Bottom line: FISMA 2.0 addresses the cybersecurity threats of today while giving agencies the agility and flexibility to adapt to the threats of tomorrow.
	      <br/><br/>
	      So <a href="/signup">sign up</a>, weigh in and help us deliver a government that cooperates, communicates and collaborates to better protect its vital information systems.
	    </span>
	  <?php }elseif(in_array($_SERVER['REQUEST_URI'], array('/tpp'))){?>
	    <span style="width:550px; display:block; float:left; margin-bottom:10px">
	      Closed-door negotiations are continuing on the Trans Pacific Partnership (TPP), which could potentially impact the way the Internet works for American taxpayers, innovators and the global Internet community. Because the Obama Administration has kept citizens, many-stakeholders and Congress in the dark, little is known about the intellectual property (IP) rights chapter of TPP beyond rumors and a February 2011 U.S. draft proposal. While we share the agreements stated goal of strengthening IP rights protections, the process should be done openly and collaboratively, with any outcome protecting individual rights and the expanding vibrancy and growth of the Internet. To date, we believe that TPP falls short. 
	      <br/><br/>
	      With careful consideration, and in response to the Administration’s continued refusal to negotiate transparently and inclusively, we have opened the February 2011 United States TPP proposal for public comment, edits and improvements here in the Madison platform. We invite the Administration and other TPP negotiating countries to listen to your input, because we believe you deserve better than more back-room deal-making that could result in a legally-binding product that harms the Internet, does little to enhance IP protections, and further erodes your ability to freely access information. And as we have learned from <a href="/sopa">SOPA</a>, <a href="/pipa">PIPA</a> and <a href="/acta">ACTA</a>, the public has a strong desire for the Federal government to engage in an open and collaborative process on matters impacting the Internet. Listening to your input and working with you can lead to better results for stakeholders, innovators and individual Internet users. 
	      <br/><br/>
	      <?php
	        global $facebook;
          $loginUrl = $facebook->getLoginUrl(array(
            'scope' => 'email',
            'redirect_uri' => SERVER_URL . '/login?via=fb&redirect=' . $_SERVER['REQUEST_URI']
          ));
        
        ?>
	      <a href="/signup">Sign up</a> or log in with <a href="<?=$loginUrl?>">Facebook</a> – as an individual, business or stakeholder – to be heard as we work to open and improve the secretive TPP. 
	    </span>
	  <?php }elseif(in_array($_SERVER['REQUEST_URI'], array('/data'))){ ?>
	    <span style="width:550px; display:block; float:left; margin-bottom:10px">
	      <div style="margin:10px 0;">
	        <span style="font-weight:bold;">STATUS:</span> <a href="http://1.usa.gov/IjgkoJ">Unanimously passed by the US House of Representatives on April 25, 2012</a>
	      </div>
	      Americans have a right to know how government spends their tax dollars.  But federal spending data, as currently available, is <a href="http://sunlightfoundation.com/clearspending/">not accurate, not consistent, not complete</a> and not useful. H.R. 2146, the <a href="http://oversight.house.gov/release/issa-introduces-sweeping-open-government-spending-transparency-reforms/">Digital Accountability and Transparency Act (DATA Act)</a>, will implement strong reporting standards, allowing <a href="http://oversight.house.gov/release/oversight-committee-releases-agency-responses-on-federal-management-it-systems/">fractured and ineffective reporting systems</a> to be streamlined, providing useful information to policymakers, agency managers, and the American people. The DATA Act will reduce bureaucratic waste, strengthen our ability to prevent and prosecute fraud and abuse of taxpayer dollars, and help Congress reduce federal spending- saving billions of dollars each year. The Data Act, based on a <a href="http://oversight.house.gov/release/video-top-watchdog-supports-transparency-legislation/">working system</a>, is <a href="http://oversight.house.gov/release/oversight-committee-approves-federal-spending-transparency-bill/">bipartisan</a>, <a href="http://oversight.house.gov/release/issa-statement-on-introduction-of-senate-version-of-data-act/">bicameral</a>, and <a href="http://oversight.house.gov/release/transparency-legislation-builds-momentum-issa-announces-endorsements/">broadly supported.</a>  
	      Recent endorsement letters include:
	      <ul>
         <li><a href="http://oversight.house.gov/wp-content/uploads/2012/04/DATA-Act-Support-April-23.pdf">24-Member Open Government Coalition including the Sunlight Foundation, the Project on Government Oversight (POGO) and the Electronic Frontier Foundation (EFF)</a></li>
         <li><a href="http://oversight.house.gov/wp-content/uploads/2012/04/04-23-12-AICPA-Letter-in-Support-of-HR-2146.pdf">The American Institute of Certified Public Accountants (AICPA)</a></li>
         <li><a href="http://oversight.house.gov/wp-content/uploads/2012/04/Level_One-support-letter.pdf">Level One Technologies </a></li>
         <li><a href="http://oversight.house.gov/wp-content/uploads/2012/04/04232012lt_DATA.pdf">Americans for Tax Reform (ATR)</a></li>
	      </ul>
	      The DATA Act is expected to come up for a vote in the US House of Representatives soon. Below, you’ll find text of the current manager’s amendment in <a href="http://keepthewebopen.com/about">Madison</a>, reflecting months of works with transparency groups, federal agencies, federal funding recipients and now, you.
	      <br/><br/>
	      So <a href="http://keepthewebopen.com/signup">sign up</a>, comment and collaborate to build a better bill. 
	    </span>
	  <?php }elseif(in_array($_SERVER['REQUEST_URI'], array('/cispa'))){ ?>
	    <span style="width:550px; display:block; float:left; margin-bottom:10px">
	      <div style="margin:10px 0;">
	        <span style="font-weight:bold;">STATUS:</span> <a href="http://www.govtrack.us/congress/bills/112/hr3523">Passed by the U.S. House of Representatives on April 26, 2012; Referred to U.S. Senate Select Committee on Intelligence on May 7, 2012</a>
	      </div>
	      The Cyber Intelligence Sharing and Protection Act (CISPA) is a bill that would allow private-sector Internet and certain critical infrastructure companies to voluntarily share information on cyberattacks with the U.S. government.  It was publicly introduced on <a href="http://intelligence.house.gov/press-release/rogers-ruppersberger-introduce-cybersecurity-bill-protect-american-businesses-">November 30, 2011</a>, marked up on <a href="http://intelligence.house.gov/full-committee-business-meeting-december-1-2011">December 1, 2011</a>, and was <a href="http://intelligence.house.gov/press-release/chairman-rogers-and-ranking-member-ruppersberger-announce-important-amendments-cyber">amended</a> in April 2012 in part to address some legitimate concerns privacy groups and the Internet community raised. 
        <br /><br />
	      While CISPA awaits action in the U.S. Senate, we have posted the latest bill text here to gather additional comments, questions and suggested improvements so that we can work with you, private sector stakeholders and key leaders in the House and Senate to ensure that - if CISPA becomes law - it is the right legislation for both Internet users and those who protect America’s vital information systems and infrastructure.
        <br /><br />
        Congressman Issa and reddit co-founder Alexis Ohanian had an in-depth discussion on CISPA.  Check it out below and <a href="/signup">sign up</a> to share your comments and ideas to make CISPA work better.  Thank you.
        <br /><br />
        <a class="cispa_question_action" href="javascript:openQuestion('1');">Question 1</a>
        <div class="cispa_questions">
          <div id="cispa_question_1" class="cispa_question">
            <a href="http://www.reddit.com/user/Kn0thing/" target="_blank">Kn0thing</a> Question #1: "Why are we in such a rush to force these bills through? Why aren't we sitting down with committees of experts who truly understand all aspects of these issues, and actually putting together a bill we could all feel good about?”
            <br /><br />
            <a href="http://www.reddit.com/user/darrell_issa/" target="_blank">Darrell_Issa</a> Answer #1: “…I thought long and hard before deciding that the benefits of CISPA outweigh the potential costs. And since I’ve been listening to the privacy concerns still being raised on here and across the Internet. They were not fully addressed in the legislation and need to be dealt with before anything becomes law. You read it here first: I will assist my colleagues in the Senate to improve CISPA now, and in the likely event of bill changes, I will work in the House to do the same before a final vote…” <a href="http://www.reddit.com/r/AskReddit/comments/t38d6/having_lunch_with_darrell_issa_tomorrow_now_that/c4msrh2">Read More</a>
          </div>
          <a class="cispa_question_action" href="javascript:openQuestion('2');">Question 2</a>
          <div id="cispa_question_2" class="cispa_question">
            <a href="http://www.reddit.com/user/Kn0thing/" target="_blank">Kn0thing</a> Question #2: "What do you think is good about CISPA? Please tell us all the good things, then tell us what you think are good about them. That way, we can try to understand why our representatives are in favor of it--for reasons other than corporate campaign contributions."
            <br /><br />
            <a href="http://www.reddit.com/user/darrell_issa/" target="_blank">Darrell_Issa</a> Answer #2: “…Right now, when a private or public-sector security team identifies a threat to these vital systems and data, they are not encouraged - or in many cases even allowed to - share the threat or their solution. When it is allowed, current law makes meaningful cooperation impossible…” <a href="http://www.reddit.com/r/AskReddit/comments/t38d6/having_lunch_with_darrell_issa_tomorrow_now_that/c4mst45">Read More</a>
          </div>
          <a class="cispa_question_action" href="javascript:openQuestion('3');">Question 3</a>
          <div id="cispa_question_3" class="cispa_question">
            <a href="http://www.reddit.com/user/Kn0thing/" target="_blank">Kn0thing</a> Question #3: "What affect would CISPA have on the White Hat community?"
            <br /><br />
            <a href="http://www.reddit.com/user/darrell_issa/" target="_blank">Darrell_Issa</a>Answer #3: “…Under CISPA, I believe White Hats will continue to serve an important role finding and exposing security holes. Now, their cooperation with other private- and public-sector security teams will be protected. Ultimately, I think getting better data into more hands, faster is the best way to defeat all threat types, from targeted to systemic….” <a href="http://www.reddit.com/r/AskReddit/comments/t38d6/having_lunch_with_darrell_issa_tomorrow_now_that/c4mstfz">Read More</a>
          </div>
        </div>
	    </span>
	    <script type="text/javascript">
        function openQuestion(num){
          
          if($("#cispa_question_" + num).css('max-height') == '150px'){
            $("#cispa_question_" + num).css('max-height', '0');
          }
          else{
            $(".cispa_question").css('max-height', '0');
            $("#cispa_question_" + num).css('max-height', '150px');
          }
        }
      </script>
	  <?php } elseif(in_array($_SERVER['REQUEST_URI'], array('/digital-bill-of-rights', '/'))){ ?>
	    <span style="width:550px; display:block; float:left; margin-bottom:10px">
	      I believe that individuals possess certain fundamental rights. Government should exist to protect those rights against those who would violate them. That is the revolutionary principle at the heart of the American Declaration of Independence and U.S. Constitution. No one should trample our right to life, liberty and the pursuit of happiness. That's why the Bill of Rights is an American citizen's first line of defense against all forms of tyranny.
        <br /><br />
	      But where can a digital citizen turn for protection against the powerful? This question lay at the heart of the fight to stop SOPA and PIPA and keep the web open. While I do not have all the answers, the remarkable cooperation we witnessed in defense of an open Internet showed me three things. First, government is flying blind, interfering and regulating without understanding even the basics. Second, we have a rare opportunity to give government marching orders on how to treat the Internet, those who use it and the innovation it supports. And third, we must get to work immediately because our opponents are not giving up. 
        <br /><br />
	      We need to frame a digital Bill of Rights. This is my first draft. I need your help to get this right, so I published it here in Madison for everyone to comment, criticize and collaborate. I look forward to hearing from you and continuing to work together to keep the web open. 
        <br /><br />
	      -Congressman Darrell Issa
	    </span>
	  <?php } elseif(in_array($_SERVER['REQUEST_URI'], array('/declaration-of-internet-freedom'))){ ?>
		<span style="width:550px; display:block; float:left; margin-bottom:10px">
			<p style="font-style:italic; text-align:center;">
				"I believe that individuals deserve an open and unobstructed Internet, so they are free to innovate, collaborate and participate in building a stronger America and better world.  It is crucial that we secure the principles outlined in the Declaration of Internet Freedom and the Digital Citizen's Bill of Rights to defend against those who seek to interfere and disrupt our vibrant online community and the economic growth it supports.  I'm proud to sign the Declaration and look forward to working with my colleagues in Congress and Internet users everywhere to develop common-sense policy that ensures future generations enjoy the Internet freedoms we do today." 
			</p>
			<p style="text-align:center;">- Congressman Darrell Issa, July 9, 2012</p>
			
			<p style="text-align:left;">PREAMBLE</p>
			We believe that a free and open Internet can bring about a better world. To keep the Internet free and open, we call on communities, industries and countries to recognize these principles. We believe that they will help to bring about more creativity, more innovation and more open societies.  
			<br/>
			We are joining an international movement to defend our freedoms because we believe that they are worth fighting for.
			<br/><br/>
			Let’s discuss these principles — agree or disagree with them, debate them, translate them, make them your own and broaden the discussion with your community — as only the Internet can make possible.
			<br/><br/>
			Join us in keeping the Internet free and open.
			<br/><br/>
			[You can interact with the following text on <a href="http://www.reddit.com/r/internetdeclaration">reddit</a>, <a href="http://www.techdirt.com/netdeclaration">Techdirt</a>, <a href="http://chzb.gr/LnKkhs">Cheezburger</a>, <a href="https://github.com/connectedio/internetdeclaration">Github</a> and <a href="http://archive.rhizome.org/internetfreedom/">Rhizome</a>.]
		</span>
	  <?php }?>
	  
    <div id="reader">
        <div id="reader-tools"></div>
        <div id="reader-nav">
            <?php $i = 1; foreach($b->sections as $sec) : ?>
            <div class="reader-section-el" id="rsec-<?=$sec['id']?>"><?=$i?></div>
            <?php $i++; endforeach; ?>
            <?php if(in_array($_SERVER['REQUEST_URI'], array('/open'))) :?>
              <div class="user-comment-button"><a href="/user-comments">Click Here for Crowdsourced OPEN Improvements</a></div>
            <?php endif; ?>
        </div>
        <div id="reader-content">
          <ol id="section-content" style="list-style-type:none"></ol>
        </div>
        <?php if($u->loggedin) : ?>
        <div id="view-edits"><?=$b->view_user_edits ? 'Hide My Edits' : 'Show My Edits'?></div>
        <?php endif; ?>
        <div id="reader-legend">
          <?php if(in_array($_SERVER['REQUEST_URI'], array('/open'))) : ?>
            <div id="user-generated-legend"></div><div class="left" style="margin-right:15px;"><a href="/user-comments">User-Generated Passage</a></div>
          <?php endif;?>
        	<div id="selected-legend"></div><div class="left" style="margin-right:15px;">Selected Passage</div>
        	<div id="has-note-legend"></div><div class="left">Has User Edits/Comments</div>
        	<?php if(in_array($_SERVER['REQUEST_URI'], array('/data'))) : ?>
            <div style="margin-left:10px;" id="user-generated-legend"></div><div>Bill Changes Before House Vote</div>
          <?php endif; ?>
        </div>
    </div>
    <div style="clear:both; margin:5px;">
    <?php if(in_array($_SERVER['REQUEST_URI'], array("/open"))){ ?>	
    	<div style="float:left;"><a href="/contact-us?f=q">Questions about OPEN?</a> or <a href="/contact-us?f=s">Have a Suggestion?</a></div>
    	<div style="float:right;"><a href="/assets/pdfs/OPEN.pdf">Download the OPEN Act</a></div>
    <?php }elseif(in_array($_SERVER['REQUEST_URI'], array('/acta'))){?>
      <div style="float:left;"><a href="/contact-us?f=q">Questions about ACTA?</a> or <a href="/contact-us?f=s">Have a Suggestion?</a></div>
    	<div style="float:right;"><a href="/assets/pdfs/ACTA-eng.pdf">Download ACTA</a></div>
    <?php }elseif(in_array($_SERVER['REQUEST_URI'], array('/fisma'))){?>
      <div style="float:left;"><a href="/contact-us?f=q">Questions about FISMA?</a> or <a href="/contact-us?f=s">Have a Suggestion?</a></div>
    	<div style="float:right;"><a href="/assets/pdfs/3-21-12%20FISMA%20Draft%20Text.pdf">Download FISMA</a></div>
    <?php }elseif(in_array($_SERVER['REQUEST_URI'], array('/data'))){ ?>
      <div style="float:left;"><a href="/contact-us?f=q">Questions about DATA?</a> or <a href="/contact-us?f=s">Have a Suggestion?</a></div>
    	<div style="float:right;"><a href="http://docs.house.gov/billsthisweek/20120423/BILLS-112hr2146-SUS.pdf" target="_blank">Download DATA PDF</a></div>
    	<div style="float:right; margin-right:10px;"><a href="http://docs.house.gov/billsthisweek/20120423/BILLS-112hr2146-SUS.xml" target="_blank">Download DATA XML</a></div>
    <?php }elseif(in_array($_SERVER['REQUEST_URI'], array('/tpp'))){ ?>
      <div style="float:left;"><a href="/contact-us?f=q">Questions about TPP?</a> or <a href="/contact-us?f=s">Have a Suggestion?</a></div>
    	<div style="float:right;"><a href="/assets/pdfs/TPP IP Chapter Proposal.pdf" target="_blank">Download TPP PDF</a></div>
    <?php }elseif(in_array($_SERVER['REQUEST_URI'], array('/cispa'))){ ?>
      <div style="float:left;"><a href="/contact-us?f=q">Questions about CISPA?</a> or <a href="/contact-us?f=s">Have a Suggestion?</a></div>
    	<div style="float:right;"><a href="/assets/pdfs/5-7-12 H. R. 3523 CISPA (Referred-in-Senate).pdf" target="_blank">Download CISPA PDF</a></div>
    <?php } ?>
    
    </div>
    <div style="clear:both; margin:5px;"></div>
</div>
<div class="right" style="width:360px;<?= $_SERVER['REQUEST_URI'] == "/sopa" ? "margin-top:125px;" : "margin-top:68px;" ?>">
	<div id="share-tools">
    	<input id="show-link-popup" value="Link" style="float:right;" onclick="show_popup('link')" />
    	<div style="float:right;">
        	<a href="https://twitter.com/share" class="twitter-share-button" data-lang="en" 
        	<?php if($_SERVER['REQUEST_URI'] == '/pipa'){?>data-text="Click here to read, edit or comment on #PIPA:" data-url="http://bit.ly/AqlhJd" data-hashtags="SOPA, stopSOPA, OPEN" <?php } ?>
        	<?php if($_SERVER['REQUEST_URI'] == '/open'){?>data-text="Let's Keep the Web #OPEN " data-url="http://www.keepthewebopen.com" data-hashtags="sopa, pipa, acta" <?php } ?>
      	  <?php if($_SERVER['REQUEST_URI'] == '/acta'){?>data-text="Internet users &amp; innovators deserve better than #ACTA  Click here to change it " data-url="http://www.keepthewebopen.com/acta" data-hashtags="sopa, pipa, open" <?php } ?>
      	  <?php if($_SERVER['REQUEST_URI'] == '/fisma'){?>data-text="FISMA 2.0 adapts federal #cybersecurity for 21st Century. Click to comment &amp; mash it up" data-url="http://www.keepthewebopen.com/fisma" data-hashtags="tech, opengov" <?php } ?>  
      	  <?php if($_SERVER['REQUEST_URI'] == '/data'){?>data-text="#DATA = accessible, accurate gov’t spending data. Click to learn more, comment & help improve it:" data-url="http://www.keepthewebopen.com/data" data-hashtags="opendata, opengov" <?php } ?>    
      	  <?php if($_SERVER['REQUEST_URI'] == '/tpp'){?>data-text="Does #TPP = #SOPA? Click here to learn more, weigh in or suggest changes to the secretive #IP agreement " data-url="http://www.keepthewebopen.com/tpp" data-hashtags="" <?php } ?>      
      	  <?php if($_SERVER['REQUEST_URI'] == '/cispa'){?>data-text="Something to say on #CISPA? Click here to comment, discuss & suggest changes" data-url="http://www.keepthewebopen.com/cispa" data-hashtags="opengov, tech, cybersecurity" <?php } ?>   
      	  <?php if(in_array($_SERVER['REQUEST_URI'], array('/digital-bill-of-rights', '/'))){?>data-text="Click here to help write a Digital Citizen's Bill of Rights" data-url="http://www.keepthewebopen.com/digital-bill-of-rights" data-hashtags="opengov, tech" <?php } ?>  
			<?php if(in_array($_SERVER['REQUEST_URI'], array('/declaration-of-internet-freedom'))){?>data-text="Click here read/edit/mash-up the Declaration of Internet Freedom" data-url="http://bit.ly/Oy6OfK" data-hashtags="opengov, netdeclaration" <?php } ?>   
        	  >Tweet</a> 
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
    <?php /*if($_SERVER['REQUEST_URI'] != "/sopa"){?><a class="sopa_live_button" href="/sopa">Click Here to Watch SOPA Markup Live</a><?php }*/?>
    <div id="keep_updated">
      <span>Keep me updated!</span>
      <form action="" method="post">
        <label for="email">Email:</label>
        <input type="text" name="email" />
        <input type="button" id="subscribe_updates" onclick="subscribe();" value="Subscribe"/>
        <input type="hidden" name="bill" value="<?= $_SERVER['REQUEST_URI'] == "/sopa" ? "sopa" : "open"?>" /> 
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