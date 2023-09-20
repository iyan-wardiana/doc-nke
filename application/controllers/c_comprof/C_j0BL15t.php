<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 5 Juli 2019
 * File Name	= C_j0BL15t.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_j0BL15t extends CI_Controller  
{
  	function __construct() // GOOD
	{
		parent::__construct();
		
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_company/m_joblistdet/m_joblistdet', '', TRUE);
		$this->load->model('m_updash/m_updash', '', TRUE);
	
		$this->data['UserID'] 		= $this->session->userdata['Emp_ID'];
		$this->data['appName'] 		= $this->session->userdata['appName'];
		$this->data['ISCREATE'] 	= $this->session->userdata['ISCREATE'];
		$this->data['ISAPPROVE'] 	= $this->session->userdata['ISAPPROVE'];
		$this->data['LangID']		= $this->session->userdata['LangID'];
		
		function cut_text2($var, $len = 200, $txt_titik = "-") 
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
	}
	
 	public function index() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_comprof/c_j0BL15t/prj180c21l/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function prj180c21l() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
				
			// START : GET MENU NAME
				$data['MenuCode'] 	= 'MN405';
				$MenuCode			= 'MN405';
				$getMNNm 		= $this->m_updash->get_menunm($MenuCode)->row();
				if($this->data['LangID'] == 'IND')
					$data['h1_title'] = $getMNNm->menu_name_IND;
				else
					$data['h1_title'] = $getMNNm->menu_name_ENG;
			// END : GET MENU NAME
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN405';	 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_comprof/c_j0BL15t/gl180c21JL/?id=";
			
			$data["secVIEW"]	= 'v_projectlist/project_list';
			if($this->session->userdata['nSELP'] == 0)
			{
				$PRJCODE	= $this->session->userdata['proj_Code'];
				$url		= site_url($data["secURL"].$this->url_encryption_helper->encode_url($PRJCODE));
				redirect($url);
			}
			else
			{
				$this->load->view($data["secVIEW"], $data);
			}
		}
		else
		{
			redirect('__l1y');
		}
	}

	function gl180c21JL() // OK
	{
		
		if ($this->session->userdata('login') == TRUE)
		{	
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			// START : GET MENU NAME
				$data['MenuCode'] 	= 'MN405';
				$MenuCode			= 'MN405';
				$getMNNm 		= $this->m_updash->get_menunm($MenuCode)->row();
				if($this->data['LangID'] == 'IND')
					$data['h1_title'] = $getMNNm->menu_name_IND;
				else
					$data['h1_title'] = $getMNNm->menu_name_ENG;
			// END : GET MENU NAME
			
			$PRJCODE			= $_GET['id'];
			$PRJCODE			= $this->url_encryption_helper->decode_url($PRJCODE);
			
			$data['title'] 		= $appName;
			$data['secAddURL'] 	= site_url('c_comprof/c_j0BL15t/add/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$cancel				= site_url('c_comprof/c_j0BL15t/projectlist/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$data['backURL'] 	= $cancel;
			$num_rows 			= $this->m_joblistdet->count_all_schedule($PRJCODE);
			$data['countjobl'] 	= $num_rows;
			$data['vwjoblist'] 	= $this->m_joblistdet->get_all_joblist($PRJCODE)->result();
			
			$getprojname 		= $this->m_joblistdet->get_project_name($PRJCODE)->row();			
			$data['PRJNAME'] 	= $getprojname->PRJNAME;
			$data['PRJCODE'] 	= $PRJCODE;
			
			$MenuCode 			= 'MN405';
			$data["MenuCode"] 	= 'MN405';
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN405';
				$TTR_CATEG		= 'L';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$this->load->view('v_company/v_joblistdet/joblistdet', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function add() // OK
	{
		$this->load->model('m_company/m_joblistdet/m_joblistdet', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Tambah Pekerjaan";
			}
			else
			{
				$data["h1_title"] 	= "Add Job";
			}
			
			$data['form_action']	= site_url('c_comprof/c_j0BL15t/add_process');
			$cancel					= site_url('c_comprof/c_j0BL15t/get_all_joblist/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			$data['countParent']	= $this->m_joblistdet->count_all_job1();		
			$data['vwParent'] 		= $this->m_joblistdet->get_all_job1()->result();
			
			$data['backURL'] 		= $cancel;
			$getprojname 			= $this->m_joblistdet->get_project_name($PRJCODE)->row();			
			$data['PRJNAME'] 		= $getprojname->PRJNAME;
			$data['PRJCODE'] 		= $PRJCODE;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN405';
				$TTR_CATEG		= 'A';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$this->load->view('v_company/v_joblistdet/joblistdet_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
		
	function getJLEV($COLDATA) // OK -- Update only
	{
		$splitCode 	= explode("~", $COLDATA);
		$JOBPARENT	= $splitCode[0];
		$PRJCODE	= $splitCode[1];
		
		$JOBLEV		= 0;
		$sqlJLEVC	= "tbl_joblist";
		$resJLEVC	= $this->db->count_all($sqlJLEVC);
		
		if($resJLEVC > 0)
		{
			$sqlJLEV	= "SELECT JOBLEV FROM tbl_joblist WHERE JOBCODEID = '$JOBPARENT'";
			$resJLEV	= $this->db->query($sqlJLEV)->result();
			foreach($resJLEV as $rowJLEV) :
				$JOBLEV = $rowJLEV->JOBLEV;
			endforeach;
			$JOBLEVV	= $JOBLEV + 1;
		}
		else
		{
			$JOBLEVV	= $JOBLEV + 1;
		}
		
		// GET MAX PATTERN NUMBER
		$sqlMAX		= "SELECT MAX(Patt_Number) AS MAXNO FROM tbl_joblist WHERE PRJCODE = '$PRJCODE' AND JOBLEV = $JOBLEVV";
		$resMAX		= $this->db->query($sqlMAX)->result();
		foreach($resMAX as $rowMAX) :
			$MAXNO 	= $rowMAX->MAXNO;
		endforeach;
		
		$Pattern_Length		= 5;
		$sqlJOBLN			= $MAXNO + 1;
		$len				= strlen($sqlJOBLN);
		$nol				= '';	
		if($Pattern_Length==2)
		{
			if($len==1) $nol="0";
		}
		elseif($Pattern_Length==3)
		{if($len==1) $nol="00";else if($len==2) $nol="0";
		}
		elseif($Pattern_Length==4)
		{if($len==1) $nol="000";else if($len==2) $nol="00";else if($len==3) $nol="0";
		}
		elseif($Pattern_Length==5)
		{if($len==1) $nol="0000";else if($len==2) $nol="000";else if($len==3) $nol="00";else if($len==4) $nol="0";
		}
		elseif($Pattern_Length==6)
		{if($len==1) $nol="00000";else if($len==2) $nol="0000";else if($len==3) $nol="000";else if($len==4) $nol="00";else if($len==5) $nol="0";
		}
		elseif($Pattern_Length==7)
		{if($len==1) $nol="000000";else if($len==2) $nol="00000";else if($len==3) $nol="0000";else if($len==4) $nol="000";else if($len==5) $nol="00";else if($len==6) $nol="0";
		}
		$JOB_CODE 	= $nol.$sqlJOBLN;
		$JOBCODEIDV	= "$JOBLEVV$PRJCODE$JOB_CODE";
		$SENDDATA	= "$JOBLEVV~$JOBCODEIDV~$JOB_CODE~$sqlJOBLN";
		echo $SENDDATA;
	}
		
	function getJOBCODE($COLDATA) // OK
	{
		$splitCode 	= explode("~", $COLDATA);
		$JOB_CODE	= $splitCode[0];
		$PRJCODE	= $splitCode[1];
		
		//$COUNTALL		= strlen($JOBCODEID);
		//$COUNTALLA	= $COUNTALL - 4;
		//$JOB_CODE		= substr($JOBCODEID, -4);
		//$PRJCODE		= substr($JOBCODEID, 0, $COUNTALLA);
		$this->load->model('m_company/m_joblistdet/m_joblistdet', '', TRUE);
		$countJOBCODE 	= $this->m_joblistdet->count_all_JOB($PRJCODE, $JOB_CODE);
		echo $countJOBCODE;
	}
	
	function add_process() // OK
	{
		$this->load->model('m_company/m_joblistdet/m_joblistdet', '', TRUE);
			
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{	
			$this->db->trans_begin();
			
			$JOBPARENT 	= $this->input->post('JOBPARENT');
			$PRJCODE 	= $this->input->post('PRJCODE');
			$JOBVOLM 	= $this->input->post('JOBVOLM');
			$PRICE 		= $this->input->post('PRICE');
			$JOBCOST	= $JOBVOLM * $PRICE;
			
			$sqlJobC	= "tbl_joblist WHERE JOBPARENT = '$JOBPARENT' AND PRJCODE = '$PRJCODE' AND ISHEADER = '1'";
			$resJobC	= $this->db->count_all($sqlJobC);
			$RUNNO		= $resJobC+1;
			
			// BOQ
			$boqlist 	= array('JOBCODEID' => $this->input->post('JOBCODEID'),
							'JOBCODEIDV'	=> $this->input->post('JOBCODEID'),
							'JOBPARENT'		=> $this->input->post('JOBPARENT'),
							'PRJCODE'		=> $this->input->post('PRJCODE'),
							'JOBDESC'		=> $this->input->post('JOBDESC'),
							'JOBGRP'		=> $this->input->post('ITM_GROUP'),
							'JOBUNIT'		=> $this->input->post('JOBUNIT'),
							'JOBLEV'		=> $this->input->post('JOBLEV'),
							'JOBVOLM'		=> $this->input->post('JOBVOLM'),
							'PRICE'			=> $this->input->post('PRICE'),
							'JOBCOST'		=> $JOBCOST,
							'BOQ_VOLM'		=> $this->input->post('JOBVOLM'),
							'BOQ_PRICE'		=> $this->input->post('PRICE'),
							'BOQ_JOBCOST'	=> $JOBCOST,
							'ISHEADER'		=> 1,
							'ITM_NEED'		=> 0,
							'ISLAST'		=> 0,
							'BOQ_STAT'		=> 1,
							'Patt_Number'	=> $RUNNO);
			$this->m_joblistdet->addBOQ($boqlist);
			
			// JOBLIST
			$joblist 	= array('JOBCODEID' => $this->input->post('JOBCODEID'),
							'JOBCODEIDV'	=> $this->input->post('JOBCODEID'),
							'JOBPARENT'		=> $this->input->post('JOBPARENT'),
							'PRJCODE'		=> $this->input->post('PRJCODE'),
							'JOBDESC'		=> $this->input->post('JOBDESC'),
							'JOBGRP'		=> $this->input->post('ITM_GROUP'),
							'JOBUNIT'		=> $this->input->post('JOBUNIT'),
							'JOBLEV'		=> $this->input->post('JOBLEV'),
							'JOBVOLM'		=> $this->input->post('JOBVOLM'),
							'PRICE'			=> $this->input->post('PRICE'),
							'JOBCOST'		=> $JOBCOST,
							'BOQ_VOLM'		=> $this->input->post('JOBVOLM'),
							'BOQ_PRICE'		=> $this->input->post('PRICE'),
							'BOQ_JOBCOST'	=> $JOBCOST,
							'ISHEADER'		=> 1,
							'ITM_NEED'		=> 0,
							'ISLAST'		=> 0,
							'WBS_STAT'		=> 1,
							'Patt_Number'	=> $RUNNO);
			$this->m_joblistdet->addJOB($joblist);
			
			$sqlJobC	= "tbl_joblist_detail WHERE JOBPARENT = '$JOBPARENT' AND PRJCODE = '$PRJCODE' AND ISLAST = '0'";
			$resJobC	= $this->db->count_all($sqlJobC);
			$RUNNO		= $resJobC+1;
			
			// JOBLISTDETAIL
			$joblistD 	= array('JOBCODEDET' => $this->input->post('JOBCODEID'),
							'JOBCODEID' 	=> $this->input->post('JOBCODEID'),
							'JOBPARENT'		=> $this->input->post('JOBPARENT'),
							'PRJCODE'		=> $this->input->post('PRJCODE'),
							'JOBDESC'		=> $this->input->post('JOBDESC'),
							'ITM_GROUP'		=> $this->input->post('ITM_GROUP'),
							'GROUP_CATEG'	=> $this->input->post('ITM_GROUP'),
							'ITM_UNIT'		=> 'LS',
							'ITM_VOLM'		=> 1,
							'ITM_PRICE'		=> $PRICE,
							'ITM_LASTP'		=> $PRICE,
							'ITM_BUDG'		=> $PRICE,
							'BOQ_VOLM'		=> 1,
							'BOQ_PRICE'		=> $PRICE,
							'BOQ_JOBCOST'	=> $PRICE,
							'IS_LEVEL'		=> $this->input->post('JOBLEV'),
							'ISCLOSE'		=> 0,
							'ISLAST'		=> 0,
							'WBSD_STAT'		=> 1,
							'Patt_Number'	=> $RUNNO);
			$this->m_joblistdet->addJOBDET($joblistD);
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $this->input->post('JOBCODEID');
				$MenuCode 		= 'MN405';
				$TTR_CATEG		= 'C';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_comprof/c_j0BL15t/gl180c21JL/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function upd1d0ebb() // OK
	{
		$this->load->model('m_company/m_joblistdet/m_joblistdet', '', TRUE);
		$JOBCODEID		= $_GET['id'];
		$JOBCODEID		= $this->url_encryption_helper->decode_url($JOBCODEID);
		
		$COUNTALL		= strlen($JOBCODEID);
		$COUNTALLA		= $COUNTALL - 4;
		$PRJCODE		= substr($JOBCODEID, 0, $COUNTALLA);
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title']		= 'Edit Job';
			$data['h3_title'] 		= 'project';
			$data['form_action']	= site_url('c_comprof/c_j0BL15t/update_process');			
			
			$getjoblist 			= $this->m_joblistdet->get_joblist_by_code($JOBCODEID)->row();
			
			$data['default']['JOBCODEID'] 	= $getjoblist->JOBCODEID;
			$data['default']['JOBCODEIDV'] 	= $getjoblist->JOBCODEIDV;
			$data['default']['JOBPARENT'] 	= $getjoblist->JOBPARENT;
			$PRJCODE						= $getjoblist->PRJCODE;
			$data['default']['PRJCODE'] 	= $getjoblist->PRJCODE;
			$data['default']['JOBCOD1'] 	= $getjoblist->JOBCOD1;
			$data['default']['JOBDESC'] 	= $getjoblist->JOBDESC;
			$data['default']['JOBCLASS'] 	= $getjoblist->JOBCLASS;
			$data['default']['JOBGRP'] 		= $getjoblist->JOBGRP;
			$data['default']['JOBTYPE'] 	= $getjoblist->JOBTYPE;
			$data['default']['JOBUNIT'] 	= $getjoblist->JOBUNIT;
			$data['default']['JOBLEV'] 		= $getjoblist->JOBLEV;
			$data['default']['JOBVOLM'] 	= $getjoblist->JOBVOLM;
			$data['default']['PRICE'] 		= $getjoblist->PRICE;
			$data['default']['JOBCOST'] 	= $getjoblist->JOBCOST;
			$data['default']['ISLAST'] 		= $getjoblist->ISLAST;
			$data['default']['ITM_NEED'] 	= $getjoblist->ITM_NEED;
			$data['default']['ITM_GROUP'] 	= $getjoblist->JOBGRP;
			$data['default']['ISHEADER'] 	= $getjoblist->ISHEADER;
			$data['default']['ITM_CODE'] 	= $getjoblist->ITM_CODE;
			$data['default']['Patt_Number']	= $getjoblist->Patt_Number;
			
			$cancel					= site_url('c_comprof/c_j0BL15t/get_all_joblist/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			$data['countParent']	= $this->m_joblistdet->count_all_job1();		
			$data['vwParent'] 		= $this->m_joblistdet->get_all_job1()->result();
			
			$data['backURL'] 		= $cancel;
			$getprojname 			= $this->m_joblistdet->get_project_name($PRJCODE)->row();			
			$data['PRJNAME'] 		= $getprojname->PRJNAME;
			$data['PRJCODE'] 		= $PRJCODE;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getjoblist->JOBCODEID;
				$MenuCode 		= 'MN405';
				$TTR_CATEG		= 'U';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$this->load->view('v_company/v_joblistdet/joblistdet_form_upt', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process() // 
	{
		$this->load->model('m_company/m_joblistdet/m_joblistdet', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{	
			$this->db->trans_begin();
			
			$JOBCODEID 	= $this->input->post('JOBCODEID');
			$JOBPARENT 	= $this->input->post('JOBPARENT');
			$COUNTALL	= strlen($JOBCODEID);
			$COUNTALLA	= $COUNTALL - 4;
			$PRJCODE	= substr($JOBCODEID, 0, $COUNTALLA);
			$PRJCODE 	= $this->input->post('PRJCODE');
			
			$joblist = array('JOBCODEID' 	=> $this->input->post('JOBCODEID'),
							'JOBCODEIDV'	=> $this->input->post('JOBCODEIDV'),
							'JOBPARENT'		=> $this->input->post('JOBPARENT'),
							'PRJCODE'		=> $this->input->post('PRJCODE'),
							'JOBCOD1'		=> $this->input->post('JOBCOD1'),
							'JOBDESC'		=> $this->input->post('JOBDESC'),
							'JOBCLASS'		=> $this->input->post('JOBCLASS'),
							'JOBGRP'		=> $this->input->post('JOBGRP'),
							'JOBTYPE'		=> $this->input->post('JOBTYPE'),
							'JOBUNIT'		=> $this->input->post('JOBUNIT'),
							'JOBLEV'		=> $this->input->post('JOBLEV'),
							'JOBVOLM'		=> $this->input->post('JOBVOLM'),
							'PRICE'			=> $this->input->post('ITM_PRICE'),
							'JOBCOST'		=> $this->input->post('JOBCOST'),
							'ITM_NEED'		=> $this->input->post('ITM_NEED'),
							'ISLAST'		=> $this->input->post('ISLAST'));
			$this->m_joblistdet->update($JOBCODEID, $joblist);
			
			// UPDATE TOTAL PARENT
			$this->m_joblistdet->updateParent($JOBPARENT, $JOBCODEID);
			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $this->input->post('JOBCODEID');
				$MenuCode 		= 'MN405';
				$TTR_CATEG		= 'UP';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_comprof/c_j0BL15t/get_all_joblist/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function getJOBLIST()
	{
		$levJOB 		= $_POST['depart'];
		$splitlevJOB	= explode("~", $levJOB);
		$JOBLEV			= $splitlevJOB[0];
		$JOBLEVN		= $JOBLEV - 1;
		$PRJCODE		= $splitlevJOB[1];
		
		$jl_arr = array();
		
		$jl_arr[]	= array("JOBCODEID" => "0", "JOBDESC" => "--- None ---", "ISDISABLED" => "");
		$Disabled_1	= 0;
		$sqlJob_1	= "SELECT JOBCODEID, JOBDESC FROM tbl_joblist WHERE JOBLEV = 1 AND PRJCODE = '$PRJCODE'";
		$resJob_1	= $this->db->query($sqlJob_1)->result();
		foreach($resJob_1 as $row_1) :
			$JOBCODEID_1	= $row_1->JOBCODEID;
			$JOBDESC_1		= $row_1->JOBDESC;			
			$space_lev_1	= ""; 
			$JOBDESCV_1		= "$space_lev_1 $JOBCODEID_1 : $JOBDESC_1";
			
			$sqlC_2			= "tbl_joblist WHERE JOBPARENT = '$JOBCODEID_1' AND ISHEADER = '1' AND PRJCODE = '$PRJCODE'";
			$resC_2 		= $this->db->count_all($sqlC_2);
			
			$ISDISABLED		= "disabled";
			if($JOBLEVN == 1)
				$ISDISABLED	= "";
				
			$jl_arr[]		= array("JOBCODEID" => $JOBCODEID_1, "JOBDESC" => $JOBDESCV_1, "ISDISABLED" => $ISDISABLED);
			
			if($resC_2 > 0)
			{
				$Disabled_2	= 0;
				$sqlJob_2	= "SELECT JOBCODEID, JOBDESC FROM tbl_joblist WHERE JOBPARENT = '$JOBCODEID_1' AND ISHEADER = '1' AND PRJCODE = '$PRJCODE'";
				$resJob_2	= $this->db->query($sqlJob_2)->result();
				foreach($resJob_2 as $row_2) :
					$JOBCODEID_2	= $row_2->JOBCODEID;
					$JOBDESC_2		= $row_2->JOBDESC;
					$space_lev_2	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; 
					$JOBDESCV_2		= "$space_lev_2 $JOBCODEID_2 : $JOBDESC_2";
					
					$sqlC_3		= "tbl_joblist WHERE JOBPARENT = '$JOBCODEID_2' AND ISHEADER = '1'";
					$resC_3 	= $this->db->count_all($sqlC_3);
					
					$ISDISABLED	= "disabled";
					if($JOBLEVN == 2)
						$ISDISABLED	= "";
						
					$jl_arr[]	= array("JOBCODEID" => $JOBCODEID_2, "JOBDESC" => $JOBDESCV_2, "ISDISABLED" => $ISDISABLED);
					
					if($resC_3 > 0)
					{
						$sqlJob_3	= "SELECT JOBCODEID, JOBDESC FROM tbl_joblist WHERE JOBPARENT = '$JOBCODEID_2' AND ISHEADER = '1'
										AND PRJCODE = '$PRJCODE'";
						$resJob_3	= $this->db->query($sqlJob_3)->result();
						foreach($resJob_3 as $row_3) :
							$JOBCODEID_3	= $row_3->JOBCODEID;
							$JOBDESC_3		= $row_3->JOBDESC;
							$space_lev_3	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; 
							$JOBDESCV_3		= "$space_lev_3 $JOBCODEID_3 : $JOBDESC_3";
							
							$sqlC_4		= "tbl_joblist WHERE JOBPARENT = '$JOBCODEID_3' AND ISHEADER = '1' AND PRJCODE = '$PRJCODE'";
							$resC_4 	= $this->db->count_all($sqlC_4);
							
							$ISDISABLED	= "disabled";
							if($JOBLEVN == 3)
								$ISDISABLED	= "";
								
							$jl_arr[]	= array("JOBCODEID" => $JOBCODEID_3, "JOBDESC" => $JOBDESCV_3, "ISDISABLED" => $ISDISABLED);
							
							if($resC_4 > 0)
							{
								$Disabled_4	= 0;
								$sqlJob_4	= "SELECT JOBCODEID, JOBDESC FROM tbl_joblist WHERE JOBPARENT = '$JOBCODEID_3' AND ISHEADER = '1' AND PRJCODE = '$PRJCODE'";
								$resJob_4	= $this->db->query($sqlJob_4)->result();
								foreach($resJob_4 as $row_4) :
									$JOBCODEID_4	= $row_4->JOBCODEID;
									$JOBDESC_4		= $row_4->JOBDESC;
									$space_lev_4	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; 
									$JOBDESCV_4		= "$space_lev_4 $JOBCODEID_4 : $JOBDESC_4";
									
									$sqlC_5		= "tbl_joblist WHERE JOBPARENT = '$JOBCODEID_4' AND ISHEADER = '1' AND PRJCODE = '$PRJCODE'";
									$resC_5 	= $this->db->count_all($sqlC_5);
									
									$ISDISABLED	= "disabled";
									if($JOBLEVN == 4)
										$ISDISABLED	= "";
										
									$jl_arr[]	= array("JOBCODEID" => $JOBCODEID_4, "JOBDESC" => $JOBDESCV_4, "ISDISABLED" => $ISDISABLED);
									
									if($resC_5 > 0)
									{
										$Disabled_5	= 0;
										$sqlJob_5	= "SELECT JOBCODEID, JOBDESC FROM tbl_joblist WHERE JOBPARENT = '$JOBCODEID_4' AND ISHEADER = '1' AND PRJCODE = '$PRJCODE'";
										$resJob_5	= $this->db->query($sqlJob_5)->result();
										foreach($resJob_5 as $row_5) :
											$JOBCODEID_5	= $row_5->JOBCODEID;
											$JOBDESC_5		= $row_5->JOBDESC;
											$space_lev_5	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; 
											$JOBDESCV_5		= "$space_lev_5 $JOBCODEID_5 : $JOBDESC_5";
											
											$sqlC_6		= "tbl_joblist WHERE JOBPARENT = '$JOBCODEID_5' AND ISHEADER = '1' AND PRJCODE = '$PRJCODE'";
											$resC_6 	= $this->db->count_all($sqlC_6);
											
											$ISDISABLED	= "disabled";
											if($JOBLEVN == 5)
												$ISDISABLED	= "";
												
											$jl_arr[]	= array("JOBCODEID" => $JOBCODEID_5, "JOBDESC" => $JOBDESCV_5, "ISDISABLED" => $ISDISABLED);
											
											if($resC_6 > 0)
											{
												$Disabled_6	= 0;
												$sqlJob_6	= "SELECT JOBCODEID, JOBDESC FROM tbl_joblist WHERE JOBPARENT = '$JOBCODEID_5' AND ISHEADER = '1' AND PRJCODE = '$PRJCODE'";
												$resJob_6	= $this->db->query($sqlJob_6)->result();
												foreach($resJob_6 as $row_6) :
													$JOBCODEID_6	= $row_6->JOBCODEID;
													$JOBDESC_6		= $row_6->JOBDESC;
													$space_lev_6	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; 
													$JOBDESCV_6		= "$space_lev_6 $JOBCODEID_6 : $JOBDESC_6";
													
													$sqlC_7			= "tbl_joblist WHERE JOBPARENT = '$JOBCODEID_6' AND ISHEADER = '1' 
																		AND PRJCODE = '$PRJCODE'";
													$resC_7 		= $this->db->count_all($sqlC_7);
																								
													$ISDISABLED		= "disabled";
													if($JOBLEVN == 6)
														$ISDISABLED	= "";
														
													$jl_arr[]	= array("JOBCODEID" => $JOBCODEID_5, "JOBDESC" => $JOBDESCV_5, "ISDISABLED" => $ISDISABLED);
												endforeach;
											}
										endforeach;
									}
								endforeach;
							}
						endforeach;
					}
				endforeach;
			}
		endforeach;
		echo json_encode($jl_arr);
	}
	
	function getCODEJOBLIST()
	{
		$levJOB 		= $_POST['depart'];
		$splitlevJOB	= explode("~", $levJOB);
		$JOBPARENT		= $splitlevJOB[0];
		$PRJCODE		= $splitlevJOB[1];
		
		$sqlJobC		= "tbl_joblist WHERE JOBPARENT = '$JOBPARENT' AND PRJCODE = '$PRJCODE' AND ISHEADER = '1'";
		$resJobC		= $this->db->count_all($sqlJobC);
		$RUNNO			= $resJobC+1;
		$NEWCODE		= "$JOBPARENT.$RUNNO";
		
		echo json_encode($NEWCODE);
	}
}