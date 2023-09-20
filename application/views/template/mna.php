<?php
	if(empty($MenuCode))
	{
		$MenuCode	= "";
	}

	$LangID			= $this->session->userdata['LangID'];
	if(isset($_POST['LangID']))
	{
		$LangID	= $_POST['LangID'];
	}
	$LangDataSess = array('LangID' => $LangID);
	$this->session->set_userdata($LangDataSess);	

	$username 		= $this->session->userdata['username'];
	$Emp_ID 		= $this->session->userdata['Emp_ID'];
	$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
	$LangID			= $this->session->userdata['LangID'];
	$vend_app		= $this->session->userdata['vend_app'];

	if($LangID == 'IND')
	{
		$taskalert	= "Tidak ada permintaan bantuan.";
	}
	else
	{
		$taskalert	= "You have no task.";
	}

	$ISREAD 		= 0;
	$ISCREATE 		= 0;
	$ISAPPROVE 		= 0;
	$ISDELETE		= 0;
	$ISDWONL		= 0;
	$sqlAUTH		= "SELECT ISREAD, ISCREATE, ISAPPROVE, ISDELETE, ISDWONL
						FROM tusermenu WHERE emp_id = '$DefEmp_ID' AND menu_code = '$MenuCode'";
	$resAUTH 		= $this->db->query($sqlAUTH)->result();
	foreach($resAUTH as $rowAUTH) :
		$ISREAD 	= $rowAUTH->ISREAD;
		$ISCREATE 	= $rowAUTH->ISCREATE;
		$ISAPPROVE 	= $rowAUTH->ISAPPROVE;
		$ISDELETE	= $rowAUTH->ISDELETE;
		$ISDWONL	= $rowAUTH->ISDWONL;
	endforeach;

	if($ISDELETE == 1)
	{
		$ISREAD		= 1;
		$ISCREATE	= 1;
		$ISAPPROVE	= 1;
		$ISDWONL	= 1;
	}
	//echo "$ISDELETE = $ISREAD - $ISCREATE - $ISAPPROVE";
	$data = array('ISREAD' => $ISREAD, 'ISCREATE' => $ISCREATE, 'ISAPPROVE' => $ISAPPROVE, 'ISDWONL' => $ISDWONL, 'ISDELETE' => $ISDELETE);
	$this->session->set_userdata($data);

	$imgemp_filename 	= '';
	$imgemp_filenameX	= '';
	$sqlGetIMG			= "SELECT imgemp_filename, imgemp_filenameX FROM tbl_employee_img WHERE imgemp_empid = '$Emp_ID'";
	$resGetIMG 			= $this->db->query($sqlGetIMG)->result();
	foreach($resGetIMG as $rowGIMG) :
		$imgemp_filename 	= $rowGIMG ->imgemp_filename;
		$imgemp_filenameX 	= $rowGIMG ->imgemp_filenameX;
	endforeach;
	$imgLoc				= base_url('assets/AdminLTE-2.0.5/emp_image/'.$Emp_ID.'/'.$imgemp_filenameX);
	$imgLoc1			= base_url('assets/AdminLTE-2.0.5/emp_image/'.$Emp_ID);
	if (!file_exists('assets/AdminLTE-2.0.5/emp_image/'.$Emp_ID))
	{
		$imgLoc			= base_url('assets/AdminLTE-2.0.5/emp_image/username.jpg');
	}

	$viewFriends		= site_url('c_setting/c_profile/index1/?id='.$this->url_encryption_helper->encode_url($Emp_ID));
	$viewMyProfile		= site_url('c_setting/c_profile/viewMyProfile/?id='.$this->url_encryption_helper->encode_url($Emp_ID));
	$viewMailBox		= site_url('c_mailbox/c_mailbox/?id='.$this->url_encryption_helper->encode_url($Emp_ID));
	//$viewMailBox		= '';
	//$viewTaskList1	= site_url('c_chat/c_chat/?id='.$this->url_encryption_helper->encode_url($Emp_ID));
	$viewTaskList		= site_url('c_help/c_t180c2hr/?id='.$this->url_encryption_helper->encode_url($Emp_ID));

	// NEW LINK IN IFRAME
		$urlIDProfVw 	= "pr0fVw";
		$urlIDfRnList 	= "fRnLst";
		$urlIDtAskList 	= "t45kLst";
		$viewMyProfile	= site_url('__180c2f/?urlID='.$this->url_encryption_helper->encode_url($urlIDProfVw));
		$viewFriends	= site_url('__180c2f/?urlID='.$this->url_encryption_helper->encode_url($urlIDfRnList));
		$viewTaskList	= site_url('__180c2f/?urlID='.$this->url_encryption_helper->encode_url($urlIDtAskList));

	$sqlApp 		= "SELECT * FROM tappname";
	$resultaApp 	= $this->db->query($sqlApp)->result();
	foreach($resultaApp as $therow) :
		$appName	= $therow->app_name;
		$top_name 	= $therow->top_name;
        $comp_init  = $therow->comp_init;
        $comp_name  = $therow->comp_name;
	endforeach;

	$completeName	= $this->session->userdata('completeName');
	$sysMode		= $this->session->userdata['sysMode'];
	$collData		= "$Emp_ID~$username~$completeName~$appName~$sysMode";
	$urlLock		= site_url('__l1y/lockSystem/?id='.$this->url_encryption_helper->encode_url($collData));

	function cut_text($var, $len = 200, $txt_titik = "...") 
	{
		$var1	= explode("</p>",$var);
		$var	= $var1[0];
		if (strlen ($var) < $len) 
		{ 
			return $var; 
		}
		if (preg_match ("/(.{1,$len})\s/", $var, $match)) 
		{
			return $match [1] . $txt_titik;
		}
		else
		{
			return substr ($var, 0, $len) . $txt_titik;
		}
	}

	$vwAllTask	= site_url('__l1y/showListTask/');
	$vwAllMail	= site_url('__l1y/showListMail/');

	$urlLock	= site_url('__l1y/logout/?id=');
?>