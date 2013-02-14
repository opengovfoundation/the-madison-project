<?php
/**
 * 	Madison Document Class
 * 
 * 	@copyright Copyright &copy; 2012 by The OpenGov Foundation
 *	@license http://www.gnu.org/licenses/ GNU GPL v.3
 */

class Bill extends Object {
	
	protected $flagged_threshold = 5;
	
	var $sections;
	var $content	 = array();
	
	var $first_section;
	var $section_part_ids = array();
	
	var $suggestions = array();
	var $comments	 = array();
	
	var $view_user_edits = true;
	
	var $id;
	var $title;
	var $shortname;
	var $description;
	var $twitter_text;
	var $twitter_hash;
	var $doc_location;
	
	/* BILL CONSTRUCTOR
	=====================================================================*/
	public function __construct($id = 0, $db = NULL) 
	{
		$this->db = $db;
		
		$billInfo = mysql_query('select * from bills where id = ' . $id, $this->db->mySQLconnR);
		
		if(mysql_num_rows($billInfo) >= 1){
			$res = mysql_fetch_array($billInfo);
			$this->title = $res['bill'];
			$this->shortname = $res['shortname'];
			$this->description = $res['description'];
			$this->twitter_text = $res['twitter_text'];
			$this->twitter_hash = $res['twitter_hash'];
			$this->doc_location = $res['doc_location'];
			
		}
		
		if($id)
		{
			$this->id = $id;
			
			parent::initialize($id, DB_TBL_BILLS);
			
			$this->refresh_notes();
			
			//GRAB ALL TOP-LEVEL SECTION PARTS
			$r = mysql_query("SELECT * FROM ".DB_TBL_BILL_CONTENT." WHERE bill_id='".$this->id."' AND parent='0' ORDER BY id ASC", $this->db->mySQLconnR);	
			while($s = mysql_fetch_assoc($r))
				$this->sections[$s['id']] = $s;	
		}
	}
	
	/**
	*	Update the bill's basic info
	*/
	public function update_info(){
		print_r($this->id);
		
		$this->db->update(DB_TBL_BILLS, array('bill'=>$this->title, 'description'=>$this->description, 'twitter_text'=>$this->twitter_text, 'twitter_hash'=>$this->twitter_hash), "id=" . $this->id);
	}
	
	/* GRAB ALL NOTES AND UPDATE BILL
	=====================================================================*/
	public function refresh_notes($pid = 0)
	{
		//"TRUNCATE" SUGGESTIONS AND COMMENTS
		$this->suggestions = $this->comments = array();

		$sql = "SELECT n.*, u.id AS uid, u.fname, u.lname, u.company FROM ".DB_TBL_TOP_NOTES." AS t, ".DB_TBL_NOTES." as n, ".DB_TBL_USERS." AS u 
				WHERE u.id = n.user AND n.bill_id='".$this->db->clean($this->id)."' AND t.note_id=n.id AND n.flags <=".$this->flagged_threshold."  ORDER BY t.score DESC";
				
		if($pid > 0)
		{
			$sql = "SELECT n.*, u.id AS uid, u.fname, u.lname, u.company FROM ".DB_TBL_NOTES." as n, ".DB_TBL_USERS." AS u 
					WHERE u.id = n.user AND n.bill_id='".$this->db->clean($this->id)."' AND n.part_id='".$this->db->clean($pid)."' AND n.flags <=".$this->flagged_threshold." ORDER BY n.likes DESC, n.time_stamp DESC";
		}

		//GET ALL NOTES WITH USER INFO ATTACHED
		$r = mysql_query($sql, $this->db->mySQLconnR);	
		while($n = mysql_fetch_assoc($r))
		{
			$n['user'] 		 = $n['company'] != '' ? $n['company'] : $n['fname'].' '.strtoupper(substr($n['lname'], 0, 1)).'.';
			$n['user'] 		 = '<a href="'.SERVER_URL.'/user/'.$n['uid'].'">'.$n['user'].'</a>';
			$n['time_stamp'] = date('M jS, Y g:i a', $n['time_stamp']);
			
			$this->{$n['type'].'s'}[$n['id']] = $n;
		}
		
		return true;
	}
	
	/* GET A SECTION BY ID
	=====================================================================*/
	public function get_section($sid, $force_refresh = false, $show_edits = 0)
	{
		if(!isset($this->content[$sid]) || $force_refresh)
		{
			if($show_edits)
				$this->refresh_notes(0, $show_edits);
			
			$this->content[$sid] = array('id'=>$sid, 'content'=>$this->sections[$sid]['content'], 'edit'=>$this->get_latest_user_suggestion($show_edits, $sid), 'children'=>array());
			$this->get_top_level_content(array($sid), $show_edits);
		}

		$this->section_part_ids = array();
		foreach($this->get_section_parts($sid, $this->content[$sid]['children']) as $pid)
			$this->section_part_ids[$pid] = array();
			
		$this->get_all_section_notes();
			
		return $this->content[$sid];
	}
	
	/* BUILD SECTION TREE - THIS FUNCTION PROBABLY NEEDS WORK
	=====================================================================*/
	private function get_top_level_content($gen, $show_edits = 0)
	{
		
	   	$r = mysql_query("SELECT * FROM ".DB_TBL_BILL_CONTENT." WHERE parent='".$gen[count($gen) - 1]."' ORDER BY child_priority ASC", $this->db->mySQLconnR);
	   	if(mysql_num_rows($r))
	   	{
			while($c = mysql_fetch_assoc($r))
		  	{
				$edit = $this->get_latest_user_suggestion($show_edits, $c['id']);
				$eval = "\$this->content";
				
				foreach($gen as $i=>$g)
					$eval .= "[".$g."]['children']";
					
				$eval .= "[\$c['id']] = array('id'=>\$c['id'], 'content'=>\$c['content'], 'edit'=>\$edit, 'children'=>array());";

				eval($eval);

			 	$this->get_top_level_content(array_merge($gen, array($c['id'])), $show_edits);
		  	}
	   	}
	}
	
	/* GET A PART OF A SECTION BY ID - ASSUMES $THIS->CONTENT FOR INITIAL $PARTS ARRAY
	=====================================================================*/
	private function get_latest_user_suggestion($uid, $pid)
	{
		if(!$uid)
			return '';
		
		$notes = $this->get_notes_by_user($uid, $pid, true);
		
		if(empty($notes['suggestions']))
			return '';
			
		krsort($notes['suggestions']);
		$s = array_slice($notes['suggestions'], 0, 1);

		return $s[0]['note'];
	}
	
	/* GET A PART OF A SECTION BY ID - ASSUMES $THIS->CONTENT FOR INITIAL $PARTS ARRAY
	=====================================================================*/
	public function get_section_parts($root, $children)
	{
		$res = array($root);
		if(!empty($children))
			foreach($children as $k=>$child)
				$res = array_merge($res, $this->get_section_parts($k, $child['children']));		
		return $res;
	}
	
	/* GET A PART OF A SECTION BY ID - ASSUMES $THIS->CONTENT FOR INITIAL $PARTS ARRAY
	=====================================================================*/
	public function get_section_part($pid, $parts)
	{
		if(isset($parts[$pid]))
			return $parts[$pid];
		foreach($parts as $p)
			if(!empty($p['children']))
				return $this->get_section_part($pid, $p['children']); //BRANCH AND SEARCH CHILDREN
	}
	
	/* CONVERTS A SECTION TREE INTO A NESTED UNORDERED LIST
	=====================================================================*/
	public function section_to_html($sec, $view, $children, $level = 0)
	{
		//$ol_types = array('lower-alpha', 'decimal', 'upper-alpha', 'lower-roman'); //DEPRECATED - BILL DESIGNATION BY LEVEL

		$response = '<li><span id="'.$sec['id'].'"'.(!empty($this->section_part_ids[$sec['id']]) ? ' class="has-notes"' : '').'>';
		
		if(!empty($this->section_part_ids[$sec['id']]))
			$response .= '<div class="note-count">'.count($this->section_part_ids[$sec['id']]).'</div>';
		
		$response .= ($sec[$view] == '' || $sec[$view] == NULL ? $sec['content'] : $sec[$view]).'</span><br>';

		if(!empty($children))
		{
			$response .= '<ol id="sp'.$sec['id'].'" style="list-style:none;">';
			foreach($children as $child)
				$response .= $this->section_to_html($child, $view, $child['children'], $level + 1); //BRANCH AND STYLE CHILDREN
			$response .= '</ol>';
		}

		return $response.'</li>';
		
	}
	
	/* ADD USER NOTE TO DB AND OBJECT
	=====================================================================*/
	public function add_note($part_id, $type, $note, $uid, $parent = 0)
	{
		if($this->sorry_carlin($note))
			return 0;
		
		if($type == 'suggestion') //USES DIFF FUNCTION TO RETURN THE DIFFERENCE BETWEEN THE ORIGINAL PASSAGE AND THE USER SUPPLIED SUGGESTION
		{
			$part = $this->get_section_part($part_id, $this->content);
			$note = $this->edit_diff($part['content'], $note);
		}
		
		$props   = array('user'=>$uid, 'bill_id'=>$this->id, 'part_id'=>$part_id, 'parent'=>$parent, 'type'=>$type, 'note'=>$note, 'time_stamp'=>time());
		$note_id = $this->db->insert(DB_TBL_NOTES, $props);

		$this->{$type.'s'}[$note_id] = $props;
		
		return $note_id;
	}
	
	/* LIKE, DISLIKE, AND FLAG NOTES
	=====================================================================*/
	public function ldf_note($note_id, $type, $uid)
	{
		$admins = array(1, 9, 10);
		
		$n_type = isset($this->comments[$note_id]) ? 'comments' : 'suggestions';
		
		$r = mysql_query("SELECT meta_key FROM ".DB_TBL_USER_META." WHERE user='".$this->db->clean($uid)."' AND meta_key='".$this->db->clean($type)."' AND meta_value='".$this->db->clean($note_id)."'", $this->db->mySQLconnR);
		if(mysql_num_rows($r) > 0)
			return false;
		
		//Allow Admins to Auto Take Down Comments and Suggestions with 1 flag Click.
		$this->{$n_type}[$note_id][$type.'s'] += in_array($uid, $admins) && $type == 'flag' ? 6 : 1;

		$this->db->insert(DB_TBL_USER_META, array('user'=>$uid, 'meta_key'=>$type, 'meta_value'=>$note_id));
		$this->db->update(DB_TBL_NOTES, array($type.'s'=>$this->{$n_type}[$note_id][$type.'s']), "id='".$note_id."'");
		
		return $note_id;
	}
	
	/* GET ALL NOTES FOR AN EXISTING BILL 'PART' AND SORT BY LIKES
	=====================================================================*/
	public function get_notes_by_part($pid, $type, $shorten = false)
	{
		$notes = array();
							 
		foreach($this->{$type} as $n)
		{
			if($n['parent'] == 0 && ($pid == 0 || ($pid != 0 && $n['part_id'] == $pid)))
			{
				if($shorten) // CONVERT NOTE TO EXERPT
				{
					$note = explode(' ', $n['note']);
					
					if(count($note) > 20)
					{
						$note_exerpt = implode(' ', array_splice($note, 0, 20));
						
						if(strpos($note_exerpt, '<ins>') !== false && (strrpos($note_exerpt, '<ins>') > strrpos($note_exerpt, '</ins>')))
							$note_exerpt .= '</ins> ...';
						elseif(strpos($note_exerpt, '<del>') !== false && (strrpos($note_exerpt, '<del>') > strrpos($note_exerpt, '</del>')))
							$note_exerpt .= '</del> ...';
						else
							$note_exerpt .= ' ...';
	
						$n['note'] = $note_exerpt;
					}
				}
				
				$notes[] = $n;
			}
		}
		
		return $notes;
	}
	
	/* CHECK IF SECTION HAS NOTES ON ANY PART WITH OUT GRABBING ALL NOTES
	=====================================================================*/
	public function get_all_section_notes()
	{
		$sids = array_keys($this->section_part_ids);
		sort($sids);
		
		$r = mysql_query("SELECT id,part_id FROM ".DB_TBL_NOTES." WHERE bill_id='".$this->id."' AND parent=0 AND part_id >='".$sids[0]."' AND part_id <='".$sids[count($sids) - 1]."' AND flags <='".$this->flagged_threshold."'", $this->db->mySQLconnR);
		while($n = mysql_fetch_assoc($r))
			$this->section_part_ids[$n['part_id']][] = $n['id'];

		return true;
	}
	
	/* GRABS ALL NOTES IN OBJECT ATTRIBUTED TO GIVEN USER ID WITH OPTIONAL PART ID
	=====================================================================*/
	public function get_notes_by_user($uid, $pid = 0, $ooc = false)
	{
		$notes = array('suggestions'=>array(), 'comments'=>array());	

		if($ooc)
		{
			$sql = "SELECT n.*, u.id AS uid, u.fname, u.lname, u.company FROM ".DB_TBL_NOTES." as n, ".DB_TBL_USERS." AS u 
					WHERE u.id = n.user".($uid ? " AND u.id='".$uid."'" : "")." AND n.bill_id='".$this->db->clean($this->id)."' AND n.part_id='".$this->db->clean($pid)."'";
			$r   = mysql_query($sql);
			while($n = mysql_fetch_assoc($r))
				$notes[$n['type'].'s'][$n['id']] = $n;
		}
		else
		{
			foreach($notes as $n_type=>$a)
				foreach($this->{$n_type} as $n)
					if($n['parent'] == 0 && $n['uid'] == $uid && ($pid == 0 || $n['part_id'] == $pid)) //ONLY RETURN TIER ONE NOTES - IGNORE COMMENTS ON OTHER COMMENTS OR SUGGESTIONS
						$notes[$n_type][$n['id']] = $n;
		}
		
		return $notes;
	}
	
	
	private function sorry_carlin($input)
	{
 		$words = array("shit", "piss", "fuck", "cunt", "cocksucker", "tits", "damn");
  
  		foreach($words as $word)
    		if(stristr($input, $word))
      			return true;//returns true if Carlin should be censored
 		return false;//return false if censorship is unneccessary
	}
	
	function diff($old, $new)
	{
		foreach($old as $oindex => $ovalue){
				$nkeys = array_keys($new, $ovalue);
				foreach($nkeys as $nindex){
						$matrix[$oindex][$nindex] = isset($matrix[$oindex - 1][$nindex - 1]) ?
								$matrix[$oindex - 1][$nindex - 1] + 1 : 1;
						if($matrix[$oindex][$nindex] > $maxlen){
								$maxlen = $matrix[$oindex][$nindex];
								$omax = $oindex + 1 - $maxlen;
								$nmax = $nindex + 1 - $maxlen;
						}
				}       
		}
		if($maxlen == 0) return array(array('d'=>$old, 'i'=>$new));
		return array_merge(
				$this->diff(array_slice($old, 0, $omax), array_slice($new, 0, $nmax)),
				array_slice($new, $nmax, $maxlen),
				$this->diff(array_slice($old, $omax + $maxlen), array_slice($new, $nmax + $maxlen)));
	}
	
	function edit_diff($old, $new)
	{
		$diff = $this->diff(explode(' ', $old), explode(' ', $new));
		foreach($diff as $k){
				if(is_array($k))
						$ret .= (!empty($k['d'])?"<del>".implode(' ',$k['d'])."</del> ":'').
								(!empty($k['i'])?"<ins>".implode(' ',$k['i'])."</ins> ":'');
				else $ret .= $k . ' ';
		}
		return $ret;
	} 
	
	/* CALCULATE DIFFERENCE AND ADDS DEL AND INS TAGS RESPECTIVELY FOR CSS PURPOSES
	=====================================================================*/
	public function edit_diff_old($old, $new)
	{
		$old = preg_replace ('/ +/', ' ', $old);
	  	$new = preg_replace ('/ +/', ' ', $new);
	 
	  	$lo = explode (" ", trim ($old));
	  	$ln = explode (" ", trim ($new));
	  	$size = max (count ($lo), count ($ln));
	
	  	$equ = array_intersect ($lo, $ln);
	  	$ins = array_diff ($ln, $lo);
	 	$del = array_diff ($lo, $ln);
	 
	  	$out = '';
	 
	  	for ($i = 0; $i < $size; $i++)
	  	{
			if (isset ($del [$i]))
		  		$out .= '<del>' . $del [$i] . '</del> ';
			if (isset ($equ [$i]))
		  		$out .= $equ[$i].' ';
			if (isset ($ins[$i]))
		  		$out .= '<ins>' . $ins [$i] . '</ins> ';
	  }
	 
	  return $out;
	} 
}
	
?>