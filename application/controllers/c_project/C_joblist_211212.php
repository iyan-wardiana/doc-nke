<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 23 Maret 2017
 * File Name	= C_joblist.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_joblist extends CI_Controller  
{
  	function __construct() // GOOD
	{
		parent::__construct();
		
		$this->load->model('m_company/m_budget/m_budget', '', TRUE);
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_project/m_boq/m_boq', '', TRUE);
		$this->load->model('m_updash/m_updash', '', TRUE);
		$this->load->model('m_project/m_joblistdet/m_joblistdet', '', TRUE);
		$this->load->model('m_project/m_joblist/m_joblist', '', TRUE);
	
		$this->data['UserID'] 		= $this->session->userdata['Emp_ID'];
		$this->data['appName'] 		= $this->session->userdata['appName'];
		$this->data['ISCREATE'] 	= $this->session->userdata['ISCREATE'];
		$this->data['ISAPPROVE'] 	= $this->session->userdata['ISAPPROVE'];
		$this->data['LangID']		= $this->session->userdata['LangID'];
		$this->data['nSELP']		= $this->session->userdata['nSELP'];
		
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
		
		// DEFAULT PROJECT
			$sqlISHO 		= "SELECT PRJCODE FROM tbl_project WHERE isHO = 1 AND PRJSTAT = 1";
			$resISHO		= $this->db->query($sqlISHO)->result();
			foreach($resISHO as $rowISHO):
				$PRJCODE	= $rowISHO->PRJCODE;
			endforeach;
			$this->data['PRJCODE']		= $PRJCODE;
			$this->data['PRJCODE_HO']	= $PRJCODE;
		
		// GET PROJECT SELECT
			if(isset($_GET['id']))
			{
				$collDATA		= $_GET['id'];
				$EXP_COLLD1		= $this->url_encryption_helper->decode_url($collDATA);
			}
			else
			{
				$EXP_COLLD1		= '';
			}

			$EXP_COLLD		= explode('~', $EXP_COLLD1);	
			$C_COLLD1		= count($EXP_COLLD);
			if($C_COLLD1 > 1)
			{
				$EXP_COLLD1	= str_replace('~', '', $EXP_COLLD1);
				$PRJCODE	= $EXP_COLLD[0];
			}
			else
			{
				$PRJCODE	= $EXP_COLLD1;
			}
		
			$sqlISHO 		= "SELECT PRJCODE, PRJCODE_HO FROM tbl_project_budg WHERE PRJCODE = '$PRJCODE' AND PRJSTAT = 1";
			$resISHO		= $this->db->query($sqlISHO)->result();
			foreach($resISHO as $rowISHO):
				$this->data['PRJCODE']		= $rowISHO->PRJCODE;
				$this->data['PRJCODE_HO']	= $rowISHO->PRJCODE_HO;
			endforeach;
	}
	
 	function index() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_project/c_joblist/projectlist/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function projectlist() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$LangID 	= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN271';
				$data["MenuApp"] 	= 'MN271';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN271';	 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_project/c_joblist/get_all_joblist/?id=";
			
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
	
	function projectlistA() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Daftar Pekerjaan";
			}
			else
			{
				$data["h1_title"] 	= "Work Breakdown";
			}
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;	 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			// START EDIT
				$data["secURL"] = "c_project/c_joblist/get_all_joblist/?id=";
			// END EDIT	
			
			$this->load->view('v_projectlist/project_list', $data);
		}
		else
		{
			redirect('Auth');
		}
	}

	function get_all_joblist($offset=0) // OK
	{
		if ($this->session->userdata('login') == TRUE)
		{	
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			// GET MENU DESC
				$mnCode				= 'MN271';
				$data["MenuApp"] 	= 'MN271';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$PRJCODE			= $_GET['id'];
			$PRJCODE			= $this->url_encryption_helper->decode_url($PRJCODE);
			
			$data['title'] 		= $appName;
			$data['h2_title'] 	= 'Work Breakdown List';
			$data['h3_title'] 	= 'project';
			$data['secAddURL'] 	= site_url('c_project/c_joblist/add/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$cancel				= site_url('c_project/c_joblist/projectlist/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$data['backURL'] 	= $cancel;
			//$num_rows 			= $this->m_joblist->count_all_schedule($PRJCODE);
			//$data['countjobl'] 	= $num_rows;
			//$data['vwjoblist'] 	= $this->m_joblist->get_all_joblist($PRJCODE)->result();
			
			$getprojname 		= $this->m_joblist->get_project_name($PRJCODE)->row();			
			$data['PRJNAME'] 	= $getprojname->PRJNAME;
			$data['PRJCODE'] 	= $PRJCODE;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN271';
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
			
			//$this->load->view('v_project/v_joblist/joblist', $data);
			$this->load->view('v_project/v_joblist/v_joblist', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function add() // OK
	{
		$this->load->model('m_project/m_joblist/m_joblist', '', TRUE);
		
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
			$data['h2_title']		= 'Add Job';
			$data['h3_title'] 		= 'project';
			$data['form_action']	= site_url('c_project/c_joblist/add_process');
			$cancel					= site_url('c_project/c_joblist/get_all_joblist/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			//$data['link'] 			= array('link_back' => anchor($cancel,'<input type="button" class="btn btn-danger" id="btnCancel" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			$data['countParent']	= $this->m_joblist->count_all_job1();		
			$data['vwParent'] 		= $this->m_joblist->get_all_job1()->result();
			
			$data['backURL'] 		= $cancel;
			$getprojname 			= $this->m_joblist->get_project_name($PRJCODE)->row();			
			$data['PRJNAME'] 		= $getprojname->PRJNAME;
			$data['PRJCODE'] 		= $PRJCODE;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN271';
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
			
			$this->load->view('v_project/v_joblist/joblist_form', $data);
		}
		else
		{
			redirect('Auth');
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
		$this->load->model('m_project/m_joblist/m_joblist', '', TRUE);
		$countJOBCODE 	= $this->m_joblist->count_all_JOB($PRJCODE, $JOB_CODE);
		echo $countJOBCODE;
	}
	
	function add_process() // OK
	{
		$this->load->model('m_project/m_joblist/m_joblist', '', TRUE);
			
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
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
							'JOBCOST'		=> $this->input->post('JOBCOST'),
							'ISLAST'		=> $this->input->post('ISLAST'),
							'ITM_NEED'		=> $this->input->post('ITM_NEED'),
							'Patt_Number'	=> $this->input->post('Patt_Number'));
			$this->m_joblist->add($joblist);
			
			// UPDATE TOTAL PARENT
			$this->m_joblist->updateParent($JOBPARENT, $JOBCODEID);
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $this->input->post('JOBCODEID');
				$MenuCode 		= 'MN271';
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
			
			$url			= site_url('c_project/c_joblist/get_all_joblist/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update() // 
	{
		$this->load->model('m_project/m_joblist/m_joblist', '', TRUE);
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
			$data['form_action']	= site_url('c_project/c_joblist/update_process');			
			
			$getjoblist 			= $this->m_joblist->get_joblist_by_code($JOBCODEID)->row();
			
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
			$data['default']['JOBCOST'] 	= $getjoblist->JOBCOST;
			$data['default']['ISLAST'] 		= $getjoblist->ISLAST;
			$data['default']['ITM_NEED'] 	= $getjoblist->ITM_NEED;
			$data['default']['Patt_Number']	= $getjoblist->Patt_Number;
			
			$cancel					= site_url('c_project/c_joblist/get_all_joblist/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			$data['countParent']	= $this->m_joblist->count_all_job1();		
			$data['vwParent'] 		= $this->m_joblist->get_all_job1()->result();
			
			$data['backURL'] 		= $cancel;
			$getprojname 			= $this->m_joblist->get_project_name($PRJCODE)->row();			
			$data['PRJNAME'] 		= $getprojname->PRJNAME;
			$data['PRJCODE'] 		= $PRJCODE;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getjoblist->JOBCODEID;
				$MenuCode 		= 'MN271';
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
			
			$this->load->view('v_project/v_joblist/joblist_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update_process() // 
	{
		$this->load->model('m_project/m_joblist/m_joblist', '', TRUE);
			
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		
		if ($this->session->userdata('login') == TRUE)
		{
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
							'JOBCOST'		=> $this->input->post('JOBCOST'),
							'ITM_NEED'		=> $this->input->post('ITM_NEED'),
							'ISLAST'		=> $this->input->post('ISLAST'));
			$this->m_joblist->update($JOBCODEID, $joblist);
			
			// UPDATE TOTAL PARENT
			$this->m_joblist->updateParent($JOBPARENT, $JOBCODEID);
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $this->input->post('JOBCODEID');
				$MenuCode 		= 'MN271';
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
			
			$url			= site_url('c_project/c_joblist/get_all_joblist/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}

  	function get_AllDataJL() // GOOD
	{
		$PRJCODE		= $_GET['id'];

		setlocale(LC_ALL, 'id-ID', 'id_ID');

		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		$PRJCODE	= $_GET['id'];

		$LangID     = $this->session->userdata['LangID'];
        
        $sqlTransl      = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
        $resTransl      = $this->db->query($sqlTransl)->result();
        foreach($resTransl as $rowTransl) :
            $TranslCode = $rowTransl->MLANG_CODE;
            $LangTransl = $rowTransl->LangTransl;

    		if($TranslCode == 'Director')$Director = $LangTransl;
            if($TranslCode == 'Compailer')$Compailer = $LangTransl;
            if($TranslCode == 'Location')$Location = $LangTransl;
    		if($TranslCode == 'Active')$Active = $LangTransl;
    		if($TranslCode == 'Inactive')$Inactive = $LangTransl;
    		if($TranslCode == 'Contact')$Contact = $LangTransl;
    		if($TranslCode == 'CompanyName')$CompanyName = $LangTransl;
    		if($TranslCode == 'Budget')$Budget = $LangTransl;
    		if($TranslCode == 'Phone')$Phone = $LangTransl;
    		if($TranslCode == 'Notes')$Notes = $LangTransl;
        endforeach;
		
		// START : FOR SERVER-SIDE
			$order 	= $this->input->get("order");

			$col 	= 0;
			$dir 	= "";
			if(!empty($order)) {
				foreach($order as $o) {
					$col 	= $o['column'];
					$dir	= $o['dir'];
				}
			}
			
			if($dir != "asc" && $dir != "desc") 
			{
            	$dir = "asc";
        	}
			
			$columns_valid 	= array("JOBCODEID", 
									"ITM_UNIT", 
									"JOBDESC");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_joblist->get_AllDataJLC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			
			$query 			= $this->m_joblist->get_AllDataJLL($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			$TotBOQ			= 0;
			$TotBUD			= 0;
			$TotADD			= 0;
			$TotADD2		= 0;
			$TotADD3		= 0;
			$TotADD4		= 0;
			$TotADD5		= 0;
			$TotALL			= 0;
			$TotREM			= 0;
			$REMAIN2		= 0;
			$TotUSE			= 0;
			$TotPC			= 0;	// Total Project Complete
			foreach ($query->result_array() as $dataI) 
			{
				$ORD_ID 		= $dataI['ORD_ID'];
				$JOBCODEID 		= $dataI['JOBCODEID'];
				$JOBDESC		= $dataI['JOBDESC'];
				$JOBLEV			= $dataI['IS_LEVEL'];
				$ITM_UNIT		= $dataI['ITM_UNIT'];
				$JOBUNIT 		= strtoupper($dataI['ITM_UNIT']);
				if($JOBUNIT == '')
					$JOBUNIT= 'LS';

				$ITM_GROUP		= $dataI['ITM_GROUP'];
				$JOBVOLM		= $dataI['ITM_VOLM'];
				$JOBPRICE		= $dataI['ITM_PRICE'];
				$ITM_LASTP		= $dataI['ITM_LASTP'];
				$JOBCOST		= $dataI['ITM_BUDG'];
				$BOQ_JOBCOST	= $dataI['BOQ_JOBCOST'];
				$BOQ_DEVIASI	= $BOQ_JOBCOST - $JOBCOST;
				$ADD_VOLM 		= $dataI['ADD_VOLM'];
				$ADD_PRICE		= $dataI['ADD_PRICE'];
				$ADD_JOBCOST	= $dataI['ADD_JOBCOST'];
				$ADDM_VOLM 		= $dataI['ADDM_VOLM'];
				$ADDM_JOBCOST	= $dataI['ADDM_JOBCOST'];
				$ITM_USED		= $dataI['ITM_USED'];
				$ITM_USED_AM	= $dataI['ITM_USED_AM'];
				$ISLAST			= $dataI['ISLAST'];

				$CollID			= "$PRJCODE~$JOBCODEID";
				$secUpd			= site_url('c_comprof/c_bUd93tL15t/upd1d0ebb/?id='.$this->url_encryption_helper->encode_url($CollID));
                
				$secPrint		= 	"<input type='hidden' name='urlUpdate".$noU."' id='urlUpdate".$noU."' value='".$secUpd."'>
									<label style='white-space:nowrap'>
									   	<a href='javascript:void(null);' class='btn btn-warning btn-xs' onClick='updJob(".$noU.")'>
											<i class='glyphicon glyphicon-pencil'></i>
										</a>
									</label>";

				$JOBDESC1 	= $JOBDESC;
				$STATCOL	= 'primary';
				$CELL_COL	= "style='font-weight:bold; white-space:nowrap'";

				// PENJUMLAHAN ADDENDUM
					/*$TOT_BUDGAM	= 0;
					$TOT_ADDAM	= 0;
					$TOT_USEDAM	= 0;
					$sqlTBUDG	= "SELECT SUM(ITM_BUDG) AS TOT_BUDGAM, SUM(ADD_JOBCOST) AS TOT_ADDAM, SUM(ITM_USED_AM) AS TOT_USEDAM
									FROM tbl_joblist_detail
									WHERE JOBPARENT LIKE '$JOBCODEID%' AND PRJCODE = '$PRJCODE'
										AND IS_LEVEL > $JOBLEV AND ISLAST = 1";
					$resTBUDG 	= $this->db->query($sqlTBUDG)->result();
					foreach($resTBUDG as $rowTBUDG) :
						$TOT_BUDGAM	= $rowTBUDG->TOT_BUDGAM;
						$TOT_ADDAM 	= $rowTBUDG->TOT_ADDAM;
						$TOT_USEDAM = $rowTBUDG->TOT_USEDAM;
					endforeach;
					$JOBCOST		= $TOT_BUDGAM;

					$JOBPRICE 	= $TOT_BUDGAM;
					$ADD_JOBCOST 	= $TOT_ADDAM;
					$ITM_USED_AM 	= $TOT_USEDAM;
					$REMAIN			= $JOBPRICE + $ADD_JOBCOST - $ITM_USED_AM;*/
					
					$JOBVOLM 		= 1;
					$TOTPRJC_01		= 0;
					$TotPC			= $TotPC + $TOTPRJC_01;

				// SPACE
					if($JOBLEV == 1)
						$spaceLev 	= "";
					elseif($JOBLEV == 2)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 3)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 4)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 5)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 6)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 7)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 8)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 9)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 10)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 11)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 12)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

				$JobView		= "$spaceLev $JOBCODEID - $JOBDESC1";

				$TOT_JOBCOST	= $JOBCOST + $ADD_JOBCOST - $ITM_USED_AM;
				$TOT_JOBCOSTP 	= $TOT_JOBCOST ?: 1;
				$percBDG		= $ITM_USED_AM / $TOT_JOBCOSTP * 100;		// Used Percentation
				$STATCOL1		= 'success';
				if($percBDG > 85)
					$STATCOL	= 'danger';
				elseif($percBDG > 60)
					$STATCOL	= 'warning';
				elseif($percBDG > 0)
					$STATCOL	= 'success';

				$ADDVOL_VW 		= "";
				$ADDPRC_VW 		= "";

				$output['data'][] 	= array("<span ".$CELL_COL.">".$JobView."</span>",
											"<div style='text-align:center;'><span ".$CELL_COL.">".$JOBUNIT."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".number_format($JOBVOLM, 2).$ADDVOL_VW."</span></div>",
											"<div style='text-align:right;'><span class='label label-".$STATCOL."' style='font-size:12px'>".number_format($BOQ_JOBCOST,2)."</span></div>",
											"<div style='text-align:right;'><span class='label label-".$STATCOL."' style='font-size:12px'>".number_format($JOBCOST,2)."</span></div>",
											"<div style='text-align:right;'><span class='label label-".$STATCOL."' style='font-size:12px'>".number_format($BOQ_DEVIASI,2)."</span></div>",
											"<div style='text-align:right;'><span class='label label-".$STATCOL."' style='font-size:12px'>".number_format($ADD_JOBCOST,2)."</span></div>",
											"<div style='text-align:right;'><span class='label label-".$STATCOL."' style='font-size:12px'>".number_format($ITM_USED_AM,2)."</span></div>");

				$noU			= $noU + 1;
			}
			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function m4analysis() // G
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			date_default_timezone_set("Asia/Jakarta");
			
			// GET MENU DESC
				$mnCode				= 'MN048';
				$data["MenuCode"] 	= 'MN048';
				$data["MenuApp"] 	= 'MN048';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$data['title'] 		= $appName;
			$data['addURL'] 	= site_url('c_project/c_joblist/addMAnl/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['backURL'] 	= site_url('c_project/c_joblist/m4analysis/?id='.$this->url_encryption_helper->encode_url($appName));
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $appName;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN048';
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
			
			$this->load->view('v_project/v_joblist/v_manalysis', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

  	function get_AllDataMANAL() // GOOD
	{
		// START : FOR SERVER-SIDE
			$order 	= $this->input->get("order");

			$col 	= 0;
			$dir 	= "";
			if(!empty($order)) {
				foreach($order as $o) {
					$col 	= $o['column'];
					$dir	= $o['dir'];
				}
			}
			
			if($dir != "asc" && $dir != "desc") 
			{
            	$dir = "asc";
        	}
			
			$columns_valid 	= array("",
									"MAN_CODE", 
									"MAN_NAME", 
									"MAN_DESC", 
									"", 
									"");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_joblist->get_AllDataMANC($search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_joblist->get_AllDataMANL($search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$MAN_NUM		= $dataI['MAN_NUM'];
				$MAN_CODE		= $dataI['MAN_CODE'];
				$MAN_NAME		= $dataI['MAN_NAME'];
				$MAN_DESC		= $dataI['MAN_DESC'];
				$MAN_STAT		= $dataI['MAN_STAT'];
				$MAN_CREATER 	= $dataI['MAN_CREATER'];
				$MAN_CREATED	= $dataI['MAN_CREATED'];
				$MAN_CREATEDV	= date('d M Y', strtotime($MAN_CREATED));
				
				$secUpd			= site_url('c_project/c_joblist/MAnal_update/?id='.$this->url_encryption_helper->encode_url($MAN_NUM));
				$secDelIcut 	= base_url().'index.php/__l1y/trashMANL/?id=';
				$delID 			= "$secDelIcut~$MAN_NUM";
				$PRJCODE 		= "";
				$delID 			= "$secDelIcut~tbl_manalysis_header~tbl_manalysis_detail~MAN_NUM~$MAN_NUM~PRJCODE~$PRJCODE";

				// CEK PO
					$sqlMAN	= "tbl_janalysis_header WHERE MAN_NUM = '$MAN_NUM' AND JAN_STAT =  '1'";
					$resMAN = $this->db->count_all($sqlMAN);
 	
 				$disD 		= "";
				if($resMAN > 0)
					$disD 	= "disabled='disabled'";
                                    
				$secAction	= 	"<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								<label style='white-space:nowrap'>
							   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
									<i class='glyphicon glyphicon-pencil'></i>
							   	</a>
								<a href='javascript:void(null);' class='btn btn-primary btn-xs' disabled='disabled'>
									<i class='glyphicon glyphicon-print'></i>
								</a>
								<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete' ".$disD.">
									<i class='fa fa-trash-o'></i>
								</a>
								</label>";
				if($MAN_STAT == 1)
				{
					$STATCOL 	= "success";
					$STATDESC 	= "Aktif";
				}
				else
				{
					$STATCOL 	= "danger";
					$STATDESC 	= "Non Aktif";
				}
				
				$output['data'][] = array("$noU.",
										  $MAN_CODE,
										  $MAN_NAME,
										  $MAN_DESC,
										  $MAN_CREATER,
										  $MAN_CREATEDV,
										  "<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
										  $secAction);
				$noU		= $noU + 1;
			}

			/*$output['data'][] = array(".",
									  "A",
									  "B",
									  "C",
									  "D",
									  "E");*/

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

  	function get_AllDataMANAL2() // GOOD
	{
		// START : FOR SERVER-SIDE
			$order 	= $this->input->get("order");

			$col 	= 0;
			$dir 	= "";
			if(!empty($order)) {
				foreach($order as $o) {
					$col 	= $o['column'];
					$dir	= $o['dir'];
				}
			}
			
			if($dir != "asc" && $dir != "desc") 
			{
            	$dir = "asc";
        	}
			
			$columns_valid 	= array("",
									"MAN_CODE", 
									"MAN_NAME", 
									"MAN_DESC");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_joblist->get_AllDataMANC($search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_joblist->get_AllDataMANL($search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$MAN_NUM		= $dataI['MAN_NUM'];
				$MAN_CODE		= $dataI['MAN_CODE'];
				$MAN_NAME		= $dataI['MAN_NAME'];
				$MAN_DESC		= $dataI['MAN_DESC'];
				$MAN_STAT		= $dataI['MAN_STAT'];
				$MAN_CREATER 	= $dataI['MAN_CREATER'];
				$MAN_CREATED	= $dataI['MAN_CREATED'];
				$MAN_CREATEDV	= date('d M Y', strtotime($MAN_CREATED));
				
				$chkBox			= "<input type='checkbox' name='chk1' value='".$MAN_NUM."|".$MAN_CODE."|".$MAN_NAME."' onClick='pickThis1(this);'/>";
				
				$output['data'][] = array($chkBox,
										  $MAN_CODE,
										  "<div style='white-space:nowrap'>".$MAN_NAME."</div>",
										  "<div style='white-space:nowrap'>".$MAN_DESC."</div>");
				$noU		= $noU + 1;
			}

			/*$output['data'][] = array(".",
									  "A",
									  "B",
									  "C");*/

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function addMAnl() // G
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
			
		// GET MENU DESC
			$mnCode				= 'MN048';
			$data["MenuApp"] 	= 'MN048';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 		= $appName;
			$data['task'] 		= 'add';
			$data['form_action']= site_url('c_project/c_joblist/addMAnl_process');
			$data['backURL'] 	= site_url('c_project/c_joblist/m4analysis/?id='.$this->url_encryption_helper->encode_url($appName));
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $appName;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN048';
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
			
			$this->load->view('v_project/v_joblist/v_manalysis_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

  	function get_AllDataITM() // GOOD
	{
		setlocale(LC_ALL, 'id-ID', 'id_ID');

		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

		$LangID     = $this->session->userdata['LangID'];
        
        $sqlTransl      = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
        $resTransl      = $this->db->query($sqlTransl)->result();
        foreach($resTransl as $rowTransl) :
            $TranslCode = $rowTransl->MLANG_CODE;
            $LangTransl = $rowTransl->LangTransl;

    		if($TranslCode == 'Director')$Director = $LangTransl;
            if($TranslCode == 'Compailer')$Compailer = $LangTransl;
            if($TranslCode == 'Location')$Location = $LangTransl;
    		if($TranslCode == 'Active')$Active = $LangTransl;
    		if($TranslCode == 'Inactive')$Inactive = $LangTransl;
    		if($TranslCode == 'Contact')$Contact = $LangTransl;
    		if($TranslCode == 'CompanyName')$CompanyName = $LangTransl;
    		if($TranslCode == 'Budget')$Budget = $LangTransl;
    		if($TranslCode == 'Phone')$Phone = $LangTransl;
    		if($TranslCode == 'Notes')$Notes = $LangTransl;
        endforeach;
		
		// START : FOR SERVER-SIDE
			$order 	= $this->input->get("order");

			$col 	= 0;
			$dir 	= "";
			if(!empty($order)) {
				foreach($order as $o) {
					$col 	= $o['column'];
					$dir	= $o['dir'];
				}
			}
			
			if($dir != "asc" && $dir != "desc") 
			{
            	$dir = "asc";
        	}
			
			$columns_valid 	= array("JOBCODEID", 
									"ITM_UNIT", 
									"JOBDESC");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_joblist->get_AllDataITMC($search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			
			$query 			= $this->m_joblist->get_AllDataITML($search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$ITM_CODE		= $dataI['ITM_CODE'];
				$ITM_NAME		= $dataI['ITM_NAME'];
				$ITM_UNIT		= $dataI['ITM_UNIT'];
				$ITM_GROUP		= $dataI['ITM_GROUP'];
				$ITM_VOLM		= $dataI['ITM_VOLM'];
				$ITM_PRICE		= $dataI['ITM_PRICE'];

				$chkBox			= "<input type='checkbox' name='chk1' value='".$ITM_CODE."|".$ITM_NAME."|".$ITM_UNIT."|".$ITM_GROUP."|".$ITM_VOLM."|".$ITM_PRICE."' onClick='pickThis1(this);'/>";

				$output['data'][] 	= array($chkBox,
											$ITM_CODE,
											"<div style='white-space:nowrap'>".$ITM_NAME."</div>",
											$ITM_UNIT,
											$ITM_GROUP,
											number_format($ITM_PRICE, 2),
											"&nbsp;");

				$noU			= $noU + 1;
			}

			/*$output['data'][] = array("1",
									  "2",
									  "3",
									  "4",
									  "5",
									  "6",
									  "7");*/

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function addMAnl_process() // G
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		$completeName 	= $this->session->userdata['completeName'];
		
		if ($this->session->userdata('login') == TRUE)
		{
			date_default_timezone_set("Asia/Jakarta");

			$MAN_NUM 		= $this->input->post('MAN_NUM');
			$MAN_CODE		= $this->input->post('MAN_CODE');
			$MAN_NAME		= $this->input->post('MAN_NAME');
			$MAN_DESC 		= $this->input->post('MAN_DESC');
			
			$inpMANL 		= array('MAN_NUM' 		=> $MAN_NUM,
									'MAN_CODE' 		=> $MAN_CODE,
									'MAN_NAME'		=> $MAN_NAME,
									'MAN_DESC'		=> $MAN_DESC,
									'MAN_STAT'		=> 1,
									'MAN_CREATER'	=> $DefEmp_ID,
									'MAN_CREATED'	=> date('Y-m-d H:i:s'));
			$this->m_joblist->addMAnl($inpMANL);

			$rowDet 			= 0;
			foreach($_POST['data'] as $d)
			{
				$d['MAN_NUM']	= $MAN_NUM;
				$d['MAN_CODE']	= $MAN_CODE;

				$this->db->insert('tbl_manalysis_detail',$d);
			}
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= "";
				$TTR_REFDOC		= $MAN_NUM;
				$MenuCode 		= 'MN048';
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
			
			$url			= site_url('c_project/c_joblist/m4analysis/?id=');
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function MAnal_update() // OK
	{
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$MAN_NUM	= $_GET['id'];
		$MAN_NUM	= $this->url_encryption_helper->decode_url($MAN_NUM);
		
		if ($this->session->userdata('login') == TRUE)
		{
			// GET MENU DESC
				$mnCode				= 'MN048';
				$data["MenuApp"] 	= 'MN048';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
				
			$docPatternPosition = 'Especially';	
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_project/c_joblist/MAnal_update_process');
			$data['backURL'] 	= site_url('c_project/c_joblist/m4analysis/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$getMANL 			= $this->m_joblist->get_manl_by_number($MAN_NUM)->row();
			$data['MAN_NUM'] 	= $getMANL->MAN_NUM;
			$data['MAN_CODE'] 	= $getMANL->MAN_CODE;
			$data['MAN_NAME'] 	= $getMANL->MAN_NAME;
			$data['MAN_DESC'] 	= $getMANL->MAN_DESC;

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= "";
				$TTR_REFDOC		= $getMANL->MAN_NUM;
				$MenuCode 		= 'MN048';
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
			
			$this->load->view('v_project/v_joblist/v_manalysis_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function MAnal_update_process() // 
	{
		date_default_timezone_set("Asia/Jakarta");

		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		$completeName 	= $this->session->userdata['completeName'];

		if ($this->session->userdata('login') == TRUE)
		{
			$MAN_NUM 		= $this->input->post('MAN_NUM');
			$MAN_CODE		= $this->input->post('MAN_CODE');
			$MAN_NAME		= $this->input->post('MAN_NAME');
			$MAN_DESC 		= $this->input->post('MAN_DESC');
			
			$updMANL 		= array('MAN_CODE' 		=> $MAN_CODE,
									'MAN_NAME'		=> $MAN_NAME,
									'MAN_DESC'		=> $MAN_DESC,
									'MAN_UPDATER'	=> $DefEmp_ID,
									'MAN_UPDATED'	=> date('Y-m-d H:i:s'));
			$this->m_joblist->MAnl_update($MAN_NUM, $updMANL);

			$this->m_joblist->deleteDetail($MAN_NUM);

			$rowDet 			= 0;
			foreach($_POST['data'] as $d)
			{
				$d['MAN_NUM']	= $MAN_NUM;
				$d['MAN_CODE']	= $MAN_CODE;

				$this->db->insert('tbl_manalysis_detail',$d);
			}
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= "";
				$TTR_REFDOC		= $MAN_NUM;
				$MenuCode 		= 'MN048';
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
			
			$url			= site_url('c_project/c_joblist/m4analysis/?id=');
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function j4analysis() // G
	{
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$LangID 	= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN049';
				$data["MenuApp"] 	= 'MN049';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN049';	 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_project/c_joblist/j4analysis_x/?id=";
			
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
	
	function j4analysis_x() // G
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			date_default_timezone_set("Asia/Jakarta");
			
			// GET MENU DESC
				$mnCode				= 'MN049';
				$data["MenuCode"] 	= 'MN049';
				$data["MenuApp"] 	= 'MN049';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
		
			if ($this->session->userdata('login') == TRUE)
			{
				// -------------------- START : SEARCHING METHOD --------------------
					
					$collDATA		= $_GET['id'];
					$EXP_COLLD1		= $this->url_encryption_helper->decode_url($collDATA);	
					$EXP_COLLD		= explode('~', $EXP_COLLD1);	
					$C_COLLD1		= count($EXP_COLLD);
					
					if($C_COLLD1 > 1)
					{
						$EXP_COLLD1	= str_replace('~', '', $EXP_COLLD1);
						$key		= $EXP_COLLD[0];
						$PRJCODE	= $EXP_COLLD[1];
						$mxLS		= $EXP_COLLD[2];
						$end		= $EXP_COLLD[3];
						$start		= 0;
					}
					else
					{
						$key		= '';
						$PRJCODE	= $EXP_COLLD1;
						$start		= 0;
						$end		= 50;
					}
				// -------------------- END : SEARCHING METHOD --------------------
			}
			
			$data['title'] 		= $appName;
			$data['addURL'] 	= site_url('c_project/c_joblist/addJAnl/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['backURL'] 	= site_url('c_project/c_joblist/j4analysis/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $appName;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN049';
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
			
			$this->load->view('v_project/v_joblist/v_janalysis', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

  	function get_AllDataJANAL() // GOOD
	{
		$LangID     = $this->session->userdata['LangID'];
        
        $sqlTransl      = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
        $resTransl      = $this->db->query($sqlTransl)->result();
        foreach($resTransl as $rowTransl) :
            $TranslCode = $rowTransl->MLANG_CODE;
            $LangTransl = $rowTransl->LangTransl;

    		if($TranslCode == 'Complete')$Complete = $LangTransl;
    		if($TranslCode == 'Processed')$Processed = $LangTransl;
        endforeach;

		// START : FOR SERVER-SIDE
			$order 	= $this->input->get("order");

			$col 	= 0;
			$dir 	= "";
			if(!empty($order)) {
				foreach($order as $o) {
					$col 	= $o['column'];
					$dir	= $o['dir'];
				}
			}
			
			if($dir != "asc" && $dir != "desc") 
			{
            	$dir = "asc";
        	}
			
			$columns_valid 	= array("",
									"JAN_CODE", 
									"JAN_NAME", 
									"JAN_DESC", 
									"", 
									"");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_joblist->get_AllDataJANC($search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_joblist->get_AllDataJANL($search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$JAN_NUM		= $dataI['JAN_NUM'];
				$JAN_CODE		= $dataI['JAN_CODE'];
				$JAN_NAME		= $dataI['JAN_NAME'];
				$JAN_DESC		= $dataI['JAN_DESC'];
				$PRJCODE		= $dataI['PRJCODE'];
				$JOBCODEID		= $dataI['JOBCODEID'];
				$JOBDESC 		= $dataI['JOBDESC'];
				$MAN_NUM		= $dataI['MAN_NUM'];
				$JAN_STAT		= $dataI['JAN_STAT'];
				$JAN_CREATER 	= $dataI['JAN_CREATER'];
				$JAN_CREATED	= $dataI['JAN_CREATED'];
				$JAN_CREATEDV	= date('d M Y', strtotime($JAN_CREATED));
				
				$secUpd			= site_url('c_project/c_joblist/JAnal_update/?id='.$this->url_encryption_helper->encode_url($JAN_NUM));
				$secDelIcut 	= base_url().'index.php/__l1y/trashJANL/?id=';
				$delID 			= "$secDelIcut~$JAN_NUM";
				$delID 			= "$secDelIcut~tbl_janalysis_header~tbl_janalysis_detail~JAN_NUM~$JAN_NUM~PRJCODE~$PRJCODE";

				// CEK PO
					$sqlMAN	= "tbl_janalysis_header WHERE MAN_NUM = '$MAN_NUM' AND JAN_STAT =  '1'";
					$resMAN = $this->db->count_all($sqlMAN);
 	
 				$disD 		= "";
				if($JAN_STAT != 0 && $JAN_STAT != 1)
					$disD 	= "disabled='disabled'";
                                    
				$secAction	= 	"<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								<label style='white-space:nowrap'>
							   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
									<i class='glyphicon glyphicon-pencil'></i>
							   	</a>
								<a href='javascript:void(null);' class='btn btn-primary btn-xs' disabled='disabled'>
									<i class='glyphicon glyphicon-print'></i>
								</a>
								<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete' ".$disD.">
									<i class='fa fa-trash-o'></i>
								</a>
								</label>";

				if($JAN_STAT == 1)
				{
					$STATCOL 	= "primary";
					$STATDESC 	= "Draft";
				}
				if($JAN_STAT == 2)
				{
					$STATCOL 	= "success";
					$STATDESC 	= "Ready";
				}
				if($JAN_STAT == 3)
				{
					$STATCOL 	= "info";
					$STATDESC 	= $Processed;
				}
				
				$output['data'][] = array("$noU.",
										  $JAN_CODE,
										  $JAN_NAME,
										  $JAN_DESC,
										  $JAN_CREATER,
										  $JAN_CREATEDV,
										  "<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
										  $secAction);
				$noU		= $noU + 1;
			}

			/*$output['data'][] = array("1",
									  "2",
									  "3",
									  "4",
									  "5",
									  "6",
									  "7",
									  "8");*/

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function addJAnl() // G
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		$PRJCODE	= $_GET['id'];
		$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			
		// GET MENU DESC
			$mnCode				= 'MN049';
			$data["MenuApp"] 	= 'MN049';
			$data["MenuApp"] 	= 'MN049';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$data["PRJCODE"] 	= $PRJCODE;
			$data['title'] 		= $appName;
			$data['task'] 		= 'add';
			$data['form_action']= site_url('c_project/c_joblist/addJAnl_process');
			$data['backURL'] 	= site_url('c_project/c_joblist/j4analysis_x/?id='.$this->url_encryption_helper->encode_url($PRJCODE));

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $appName;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN049';
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
			
			$this->load->view('v_project/v_joblist/v_janalysis_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

  	function get_AllDataSRVP() // GOOD
	{
		//$PRJCODE		= $_GET['id'];

		setlocale(LC_ALL, 'id-ID', 'id_ID');

		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		$PRJCODE	= $_GET['id'];

		$LangID     = $this->session->userdata['LangID'];
        
        $sqlTransl      = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
        $resTransl      = $this->db->query($sqlTransl)->result();
        foreach($resTransl as $rowTransl) :
            $TranslCode = $rowTransl->MLANG_CODE;
            $LangTransl = $rowTransl->LangTransl;

    		if($TranslCode == 'Director')$Director = $LangTransl;
            if($TranslCode == 'Compailer')$Compailer = $LangTransl;
            if($TranslCode == 'Location')$Location = $LangTransl;
    		if($TranslCode == 'Active')$Active = $LangTransl;
    		if($TranslCode == 'Inactive')$Inactive = $LangTransl;
    		if($TranslCode == 'Contact')$Contact = $LangTransl;
    		if($TranslCode == 'CompanyName')$CompanyName = $LangTransl;
    		if($TranslCode == 'Budget')$Budget = $LangTransl;
    		if($TranslCode == 'Phone')$Phone = $LangTransl;
    		if($TranslCode == 'Notes')$Notes = $LangTransl;
        endforeach;
		
		// START : FOR SERVER-SIDE
			$order 	= $this->input->get("order");

			$col 	= 0;
			$dir 	= "";
			if(!empty($order)) {
				foreach($order as $o) {
					$col 	= $o['column'];
					$dir	= $o['dir'];
				}
			}
			
			if($dir != "asc" && $dir != "desc") 
			{
            	$dir = "asc";
        	}
			
			$columns_valid 	= array("JOBCODEID",
									"JOBDESC");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_joblist->get_AllDataSRVCX($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			
			$query 			= $this->m_joblist->get_AllDataSRVLX($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			$TotBOQ			= 0;
			$TotBUD			= 0;
			$TotADD			= 0;
			$TotADD2		= 0;
			$TotADD3		= 0;
			$TotADD4		= 0;
			$TotADD5		= 0;
			$TotALL			= 0;
			$TotREM			= 0;
			$REMAIN2		= 0;
			$TotUSE			= 0;
			$TotPC			= 0;	// Total Project Complete
			foreach ($query->result_array() as $dataI) 
			{
				$ORD_ID 		= $dataI['ORD_ID'];
				$JOBCODEID 		= $dataI['JOBCODEID'];
				$JOBPARENT 		= $dataI['JOBPARENT'];
				$JOBDESC		= $dataI['JOBDESC'];
				$ITM_UNIT		= $dataI['JOBUNIT'];
				$JOBCOST		= $dataI['JOBCOST'];
				$JOBLEV			= $dataI['JOBLEV'];
				$ISLAST			= $dataI['ISLAST'];

				// IS LAST SETT
					$JOBDESC1 	= $JOBDESC;
					$STATCOL	= 'primary';
					$CELL_COL	= "style='font-weight:bold; white-space:nowrap'";

				/*$s_01 		= "tbl_joblist_detail WHERE JOBPARENT = '$JOBCODEID' AND ISLAST = '1'";
				$q_01 			= $this->db->count_all($s_01);*/
				if($ISLAST == 1)
				{
					$JOBCOSTV	= number_format($dataI['JOBCOST'], 2);
					$chkBox		= "<input type='radio' name='chkPar' value='".$JOBCODEID."|".$PRJCODE."|".$JOBDESC."|".$ITM_UNIT."|".$JOBCOST."' onClick='pickThisPar(this);'/>";
				}
				else
				{
					$JOBCOSTV	= "-";
					$chkBox		= "";
				}


				// SPACE
					if($JOBLEV == 1)
						$spaceLev 	= "";
					elseif($JOBLEV == 2)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 3)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 4)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 5)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 6)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 7)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 8)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 9)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 10)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 11)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 12)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

				$JobView		= "$spaceLev $JOBCODEID - $JOBDESC1";

				$output['data'][] 	= array($chkBox,
											"<span>".$JobView."</span>",
											$JOBCOSTV);

				$noU			= $noU + 1;
			}

			/*$output['data'][] 	= array("A",
										"B"
									);*/
			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

  	function get_AllDataSRV() // GOOD
	{
		//$PRJCODE		= $_GET['id'];

		setlocale(LC_ALL, 'id-ID', 'id_ID');

		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		$PRJCODE	= $_GET['id'];

		$LangID     = $this->session->userdata['LangID'];
        
        $sqlTransl      = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
        $resTransl      = $this->db->query($sqlTransl)->result();
        foreach($resTransl as $rowTransl) :
            $TranslCode = $rowTransl->MLANG_CODE;
            $LangTransl = $rowTransl->LangTransl;

    		if($TranslCode == 'Director')$Director = $LangTransl;
            if($TranslCode == 'Compailer')$Compailer = $LangTransl;
            if($TranslCode == 'Location')$Location = $LangTransl;
    		if($TranslCode == 'Active')$Active = $LangTransl;
    		if($TranslCode == 'Inactive')$Inactive = $LangTransl;
    		if($TranslCode == 'Contact')$Contact = $LangTransl;
    		if($TranslCode == 'CompanyName')$CompanyName = $LangTransl;
    		if($TranslCode == 'Budget')$Budget = $LangTransl;
    		if($TranslCode == 'Phone')$Phone = $LangTransl;
    		if($TranslCode == 'Notes')$Notes = $LangTransl;
        endforeach;
		
		// START : FOR SERVER-SIDE
			$order 	= $this->input->get("order");

			$col 	= 0;
			$dir 	= "";
			if(!empty($order)) {
				foreach($order as $o) {
					$col 	= $o['column'];
					$dir	= $o['dir'];
				}
			}
			
			if($dir != "asc" && $dir != "desc") 
			{
            	$dir = "asc";
        	}
			
			$columns_valid 	= array("JOBCODEID",
									"JOBDESC");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_joblist->get_AllDataSRVCX($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			
			$query 			= $this->m_joblist->get_AllDataSRVLX($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			$TotBOQ			= 0;
			$TotBUD			= 0;
			$TotADD			= 0;
			$TotADD2		= 0;
			$TotADD3		= 0;
			$TotADD4		= 0;
			$TotADD5		= 0;
			$TotALL			= 0;
			$TotREM			= 0;
			$REMAIN2		= 0;
			$TotUSE			= 0;
			$TotPC			= 0;	// Total Project Complete
			foreach ($query->result_array() as $dataI) 
			{
				$ORD_ID 		= $dataI['ORD_ID'];
				$JOBCODEID 		= $dataI['JOBCODEID'];
				$JOBPARENT 		= $dataI['JOBPARENT'];
				$JOBDESC		= $dataI['JOBDESC'];
				$ITM_UNIT		= $dataI['JOBUNIT'];
				$JOBCOST		= $dataI['JOBCOST'];
				$JOBLEV			= $dataI['JOBLEV'];
				$ISLAST			= $dataI['ISLAST'];

				// IS LAST SETT
					$JOBDESC1 	= $JOBDESC;
					$STATCOL	= 'primary';
					$CELL_COL	= "style='font-weight:bold; white-space:nowrap'";

				/*$s_01 		= "tbl_joblist_detail WHERE JOBPARENT = '$JOBCODEID' AND ISLAST = '1'";
				$q_01 			= $this->db->count_all($s_01);*/
				if($ISLAST == 1)
				{
					$JOBCOSTV	= number_format($dataI['JOBCOST'], 2);
					$chkBox		= "<input type='checkbox' name='chk0' value='".$JOBCODEID."|".$PRJCODE."|".$JOBDESC."|".$ITM_UNIT."|".$JOBCOST."' onClick='pickThis0(this);'/>";
				}
				else
				{
					$JOBCOSTV	= "-";
					$chkBox		= "";
				}


				// SPACE
					if($JOBLEV == 1)
						$spaceLev 	= "";
					elseif($JOBLEV == 2)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 3)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 4)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 5)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 6)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 7)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 8)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 9)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 10)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 11)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 12)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

				$JobView		= "$spaceLev $JOBCODEID - $JOBDESC1";

				$output['data'][] 	= array($chkBox,
											"<span>".$JobView."</span>",
											$JOBCOSTV);

				$noU			= $noU + 1;
			}

			/*$output['data'][] 	= array("A",
										"B"
									);*/
			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function addJAnl_process() // G
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		$completeName 	= $this->session->userdata['completeName'];
		
		if ($this->session->userdata('login') == TRUE)
		{
			date_default_timezone_set("Asia/Jakarta");

			$this->db->trans_begin();

				$JAN_NUM 		= $this->input->post('JAN_NUM');
				$JAN_CODE		= $this->input->post('JAN_CODE');
				$JAN_NAME		= $this->input->post('JAN_NAME');
				$JAN_DESC 		= $this->input->post('JAN_DESC');
				$JAN_TYPE 		= $this->input->post('JAN_TYPE');
				$PRJCODE 		= $this->input->post('PRJCODE');
				$JOBCODEID 		= $this->input->post('JOBCODEID');
				$JOBDESC 		= $this->input->post('JOBDESC');
				$JOBVOLM 		= $this->input->post('JOBVOLM');
				$JOBUNIT 		= $this->input->post('JOBUNIT');
				$JOBCOST 		= $this->input->post('JOBCOST');
				$MAN_NUM 		= $this->input->post('MAN_NUM');
				$MAN_CODE 		= $this->input->post('MAN_CODE');
				$JAN_STAT 		= $this->input->post('JAN_STAT');
				$JAN_CREATER 	= $DefEmp_ID;
				$JAN_CREATED 	= date('Y-m-d H:i:s');
				
				$inpJANL 		= array('JAN_NUM' 		=> $JAN_NUM,
										'JAN_CODE' 		=> $JAN_CODE,
										'JAN_NAME'		=> $JAN_NAME,
										'JAN_DESC'		=> $JAN_DESC,
										'JAN_TYPE'		=> $JAN_TYPE,
										'PRJCODE'		=> $PRJCODE,
										'JOBCODEID'		=> $JOBCODEID,
										'JOBDESC'		=> $JOBDESC,
										'JOBVOLM'		=> $JOBVOLM,
										'JOBUNIT'		=> $JOBUNIT,
										'JOBCOST'		=> $JOBCOST,
										'MAN_NUM'		=> $MAN_NUM,
										'MAN_CODE'		=> $MAN_CODE,
										'JAN_STAT'		=> $JAN_STAT,
										'JAN_CREATER'	=> $JAN_CREATER,
										'JAN_CREATED'	=> $JAN_CREATED);
				$this->m_joblist->addJAnl($inpJANL);

				foreach($_POST['dataMANL'] as $dMAnl)
				{
					$dMAnl['JAN_NUM']	= $JAN_NUM;
					$dMAnl['JAN_CODE']	= $JAN_CODE;
					$dMAnl['PRJCODE']	= $PRJCODE;
					$MAN_NUM 			= $dMAnl['MAN_NUM'];

					$this->db->insert('tbl_janalysis_manl',$dMAnl);
				}

				foreach($_POST['dataJL'] as $dJL)
				{
					$dJL['JAN_NUM']		= $JAN_NUM;
					$dJL['JAN_CODE']	= $JAN_CODE;
					$dJL['PRJCODE']		= $PRJCODE;

					$this->db->insert('tbl_janalysis_jlist',$dJL);
				}

				$rowDet 			= 0;
				foreach($_POST['data'] as $d)
				{
					$d['JAN_NUM']	= $JAN_NUM;
					$d['JAN_CODE']	= $JAN_CODE;
					$d['JOBCODEID']	= $JOBCODEID;
					$d['JOBDESC']	= $JOBDESC;

					$this->db->insert('tbl_janalysis_detail',$d);
				}

			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= "";
				$TTR_REFDOC		= $JAN_NUM;
				$MenuCode 		= 'MN049';
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
			
			$url			= site_url('c_project/c_joblist/j4analysis_x/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function JAnal_update() // OK
	{
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$JAN_NUM	= $_GET['id'];
		$JAN_NUM	= $this->url_encryption_helper->decode_url($JAN_NUM);
		
		if ($this->session->userdata('login') == TRUE)
		{
			// GET MENU DESC
				$mnCode				= 'MN049';
				$data["MenuApp"] 	= 'MN049';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
				
			$docPatternPosition = 'Especially';	
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_project/c_joblist/JAnal_update_process');

			$getJANL 			= $this->m_joblist->get_janl_by_number($JAN_NUM)->row();
			$data['JAN_NUM'] 	= $getJANL->JAN_NUM;
			$data['JAN_CODE'] 	= $getJANL->JAN_CODE;
			$data['JAN_NAME'] 	= $getJANL->JAN_NAME;
			$data['JAN_DESC'] 	= $getJANL->JAN_DESC;
			$data['JAN_TYPE'] 	= $getJANL->JAN_TYPE;
			$data['PRJCODE'] 	= $getJANL->PRJCODE;
			$PRJCODE 			= $getJANL->PRJCODE;
			$data['JOBCODEID'] 	= $getJANL->JOBCODEID;
			$data['JOBDESC'] 	= $getJANL->JOBDESC;
			$data['JOBVOLM'] 	= $getJANL->JOBVOLM;
			$data['JOBUNIT'] 	= $getJANL->JOBUNIT;
			$data['JOBCOST'] 	= $getJANL->JOBCOST;
			$data['MAN_NUM'] 	= $getJANL->MAN_NUM;
			$data['MAN_CODE'] 	= $getJANL->MAN_CODE;
			$data['JAN_STAT'] 	= $getJANL->JAN_STAT;
			$data['backURL'] 	= site_url('c_project/c_joblist/j4analysis_x/?id='.$this->url_encryption_helper->encode_url($PRJCODE));

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= "";
				$TTR_REFDOC		= $getJANL->MAN_NUM;
				$MenuCode 		= 'MN049';
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
			
			$this->load->view('v_project/v_joblist/v_janalysis_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function JAnal_update_process() // 
	{
		date_default_timezone_set("Asia/Jakarta");

		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		$completeName 	= $this->session->userdata['completeName'];

		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();

				$JAN_NUM 		= $this->input->post('JAN_NUM');
				$JAN_CODE		= $this->input->post('JAN_CODE');
				$JAN_NAME		= $this->input->post('JAN_NAME');
				$JAN_DESC 		= $this->input->post('JAN_DESC');
				$JAN_TYPE 		= $this->input->post('JAN_TYPE');
				$PRJCODE 		= $this->input->post('PRJCODE');
				$JOBCODEID 		= $this->input->post('JOBCODEID');
				$JOBDESC 		= $this->input->post('JOBDESC');
				$JOBVOLM 		= $this->input->post('JOBVOLM');
				$JOBUNIT 		= $this->input->post('JOBUNIT');
				$JOBCOST 		= $this->input->post('JOBCOST');
				$MAN_NUM 		= $this->input->post('MAN_NUM');
				$MAN_CODE 		= $this->input->post('MAN_CODE');
				$JAN_STAT 		= $this->input->post('JAN_STAT');
				$JAN_UPDATER 	= $DefEmp_ID;
				$JAN_UPDATED 	= date('Y-m-d H:i:s');
				
				$updJANL 		= array('JAN_CODE' 		=> $JAN_CODE,
										'JAN_NAME'		=> $JAN_NAME,
										'JAN_DESC'		=> $JAN_DESC,
										'JAN_TYPE'		=> $JAN_TYPE,
										'PRJCODE'		=> $PRJCODE,
										'JOBCODEID'		=> $JOBCODEID,
										'JOBDESC'		=> $JOBDESC,
										'JOBVOLM'		=> $JOBVOLM,
										'JOBUNIT'		=> $JOBUNIT,
										'JOBCOST'		=> $JOBCOST,
										'MAN_NUM'		=> $MAN_NUM,
										'MAN_CODE'		=> $MAN_CODE,
										'JAN_STAT'		=> $JAN_STAT,
										'JAN_UPDATER'	=> $JAN_UPDATER,
										'JAN_UPDATED'	=> $JAN_UPDATED);
				$this->m_joblist->JAnl_update($JAN_NUM, $updJANL);

				$this->m_joblist->deleteMANL($JAN_NUM, $PRJCODE);
				foreach($_POST['dataMANL'] as $dMAnl)
				{
					$dMAnl['JAN_NUM']	= $JAN_NUM;
					$dMAnl['JAN_CODE']	= $JAN_CODE;
					$dMAnl['PRJCODE']	= $PRJCODE;
					$MAN_NUM 			= $dMAnl['MAN_NUM'];

					$this->db->insert('tbl_janalysis_manl',$dMAnl);
				}

				$this->m_joblist->deleteJL($JAN_NUM, $PRJCODE);
				foreach($_POST['dataJL'] as $dJL)
				{
					$dJL['JAN_NUM']		= $JAN_NUM;
					$dJL['JAN_CODE']	= $JAN_CODE;
					$dJL['PRJCODE']		= $PRJCODE;

					$this->db->insert('tbl_janalysis_jlist',$dJL);
				}

				$this->m_joblist->deleteJANLD($JAN_NUM, $PRJCODE);
				foreach($_POST['data'] as $d)
				{
					$d['JAN_NUM']	= $JAN_NUM;
					$d['JAN_CODE']	= $JAN_CODE;
					$d['JOBCODEID']	= $JOBCODEID;
					$d['JOBDESC']	= $JOBDESC;

					$this->db->insert('tbl_janalysis_detail',$d);
				}

			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= "";
				$TTR_REFDOC		= $JAN_NUM;
				$MenuCode 		= 'MN049';
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
			
			$url			= site_url('c_project/c_joblist/j4analysis_x/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}

	function getMANDet()
	{
		$PRJCODE 	= $_GET['id'];
		$MAN_NUM 	= $_POST['MAN_NUM'];

		$s_01 		= "SELECT * FROM tbl_manalysis_detail WHERE MAN_NUM = '$MAN_NUM'";
		$r_01 		= $this->db->query($s_01)->result();
		foreach($r_01 as $rw_01):
			$MAN_NUM 	= $rw_01->MAN_NUM;
			$MAN_CODE 	= $rw_01->MAN_CODE;
			$ITM_CODE 	= $rw_01->ITM_CODE;
			$ITM_UNIT 	= $rw_01->ITM_UNIT;
			$ITM_GROUP 	= $rw_01->ITM_GROUP;
			$ITM_QTY 	= $rw_01->ITM_QTY;
			$ITM_PRICE 	= $rw_01->ITM_PRICE;
			$ITM_KOEF 	= $rw_01->ITM_KOEF;
			$ITM_TOTAL 	= $rw_01->ITM_TOTAL;

			$ITM_NAME 	= "";
			$s_02 		= "SELECT ITM_NAME FROM tbl_item WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE' LIMIT 1";
			$r_02 		= $this->db->query($s_02)->result();
			foreach($r_02 as $rw_02):
				$ITM_NAME 	= $rw_02->ITM_NAME;
			endforeach;

			//echO "$MAN_NUM~$MAN_CODE~$ITM_CODE~$ITM_NAME~$ITM_UNIT~$ITM_GROUP~$ITM_QTY~$ITM_PRICE~$ITM_KOEF~$ITM_TOTAL";

			echo "addItem($MAN_NUM~$MAN_CODE~$ITM_CODE~$ITM_NAME~$ITM_UNIT~$ITM_GROUP~$ITM_QTY~$ITM_PRICE~$ITM_KOEF~$ITM_TOTAL)";
		endforeach;
	}

  	function get_AllDataITMMANAL() // GOOD
	{
		setlocale(LC_ALL, 'id-ID', 'id_ID');

		$collMANL	= $_GET['id'];
		$PRJCODE	= $_GET['PRJCODE'];
		$COLLMANL 	= "'$collMANL'";

		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		$LangID     = $this->session->userdata['LangID'];
		
		// START : FOR SERVER-SIDE
			$order 	= $this->input->get("order");

			$col 	= 0;
			$dir 	= "";
			if(!empty($order)) {
				foreach($order as $o) {
					$col 	= $o['column'];
					$dir	= $o['dir'];
				}
			}
			
			if($dir != "asc" && $dir != "desc") 
			{
            	$dir = "asc";
        	}
			
			$columns_valid 	= array("JOBCODEID", 
									"ITM_UNIT", 
									"JOBDESC");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_joblist->get_AllDataITMMANC($COLLMANL);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			
			$query 			= $this->m_joblist->get_AllDataITMMANL($COLLMANL);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{								
				$MAN_NUM		= $dataI['MAN_NUM'];
				$MAN_CODE		= $dataI['MAN_CODE'];
				$ITM_CODE		= $dataI['ITM_CODE'];
				$ITM_NAME		= $dataI['ITM_NAME'];
				$ITM_UNIT		= $dataI['ITM_UNIT'];
				$ITM_GROUP		= $dataI['ITM_GROUP'];
				//$ITM_QTY		= $dataI['ITM_QTY'];
				$ITM_QTY		= 0;
				$ITM_PRICE		= $dataI['ITM_PRICE'];
				$ITM_KOEF		= $dataI['ITM_KOEF'];
				//$ITM_TOTAL	= $dataI['ITM_TOTAL'];
				$ITM_TOTAL		= 0;

				$output['data'][] 	= array("<a href='#' onClick='deleteRow_MAnlDet(".$noU.")' title='Delete Document' class='btn btn-danger btn-xs'><i class='fa fa-trash-o'></i></a>",
											"$ITM_CODE
												<input type='hidden' id='data".$noU."MAN_NUM' name='data[".$noU."][MAN_NUM]' value='".$MAN_NUM."' class='form-control' style='max-width:300px;'>
	                                          	<input type='hidden' id='data".$noU."MAN_CODE' name='data[".$noU."][MAN_CODE]' value='".$MAN_CODE."' class='form-control' style='max-width:300px;'>
	                                          	<input type='hidden' id='data".$noU."PRJCODE' name='data[".$noU."][PRJCODE]' value='".$PRJCODE."' class='form-control' style='max-width:300px;'>
	                                          	<input type='hidden' id='data".$noU."ITM_CODE' name='data[".$noU."][ITM_CODE]' value='".$ITM_CODE."' class='form-control' style='max-width:300px;'>
	                                          	<input type='hidden' id='data".$noU."ITM_GROUP' name='data[".$noU."][ITM_GROUP]' value='".$ITM_GROUP."' class='form-control' style='max-width:300px;'>",
											"<div style='white-space:nowrap'>".$ITM_NAME."</div>
												<input type='hidden' id='data".$noU."ITM_NAME' name='data[".$noU."][ITM_NAME]' value='".$ITM_NAME."' class='form-control' style='max-width:300px;'>",
											"<input type='text' name='ITM_QTY".$noU."' id='ITM_QTY".$noU."' value='".number_format($ITM_QTY, 2)."' class='form-control' style='max-width:100px; text-align:right' onKeyPress='return isIntOnlyNew(event);' onBlur='chgQty(this,".$noU.");' readonly >
												<input type='hidden' name='data[".$noU."][ITM_QTY]' id='data".$noU."ITM_QTY' value='".$ITM_QTY."' class='form-control'>",
											"$ITM_UNIT
												<input type='hidden' name='data[".$noU."][ITM_UNIT]' id='data".$noU."ITM_UNIT' value='".$ITM_UNIT."' class='form-control' style='max-width:300px;' >",
											"<input type='text' name='ITM_PRICE".$noU."' id='ITM_PRICE".$noU."' value='".number_format($ITM_PRICE, 2)."' class='form-control' style='max-width:120px; text-align:right' onKeyPress='return isIntOnlyNew(event);' onBlur='chgPrc(this,".$noU.");' >
												<input type='hidden' name='data[".$noU."][ITM_PRICE]' id='data".$noU."ITM_PRICE' value='".$ITM_PRICE."' class='form-control'>",
											"<input type='text' name='ITM_KOEF".$noU."' id='ITM_KOEF".$noU."' value='". number_format($ITM_KOEF, 2)."' class='form-control' style='max-width:90px; text-align:right' onKeyPress='return isIntOnlyNew(event);' onBlur='chgKoef(this,".$noU.");' >
												<input type='hidden' name='data[".$noU."][ITM_KOEF]' id='data".$noU."ITM_KOEF' value='".$ITM_KOEF."' class='form-control'>",
											"<input type='text' name='ITM_TOTAL".$noU."' id='ITM_TOTAL".$noU."' value='".number_format($ITM_TOTAL, 2)."' class='form-control' style='max-width:150px; text-align:right' onKeyPress='return isIntOnlyNew(event);' readonly >
												<input type='hidden' name='data[".$noU."][ITM_TOTAL]' id='data".$noU."ITM_TOTAL' value='". $ITM_TOTAL."' class='form-control'>
												<script type=\"text/javascript\">
													document.getElementById('totalrowMANDET').value = ".$noU.";
												</script>
											"
										);

				$noU			= $noU + 1;
			}

			/*$output['data'][] = array('$COLLMANL',
									  '2',
									  '3',
									  '4',
									  '5',
									  '6',
									  '7',
									  '8');*/

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
}