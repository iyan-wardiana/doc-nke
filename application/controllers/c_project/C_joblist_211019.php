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
	
 	public function index() // OK
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

				if($ISLAST == 0)	// HEADER
				{
					$JOBDESC1 	= $JOBDESC;
					$STATCOL	= 'primary';
					$CELL_COL	= "style='font-weight:bold; white-space:nowrap'";

					// PENJUMLAHAN ADDENDUM
						$TOT_BUDGAM	= 0;
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

						$JOBVOLM 		= 1;
						$JOBPRICE 		= $TOT_BUDGAM;
						$ADD_JOBCOST 	= $TOT_ADDAM;
						$ITM_USED_AM 	= $TOT_USEDAM;
						$REMAIN			= $JOBPRICE + $ADD_JOBCOST - $ITM_USED_AM;
						
						$TOTPRJC_01		= 0;
						$TotPC			= $TotPC + $TOTPRJC_01;
				}
				else
				{
					$strLEN 	= strlen($JOBDESC);
					$JOBDESCA	= substr("$JOBDESC", 0, 60);
					$JOBDESC1 	= $JOBDESCA;
					if($strLEN > 60)
						$JOBDESC1 	= $JOBDESCA."...";
					$STATCOL	= 'success';
					$CELL_COL	= "style='white-space:nowrap'";

					$TotBUD		= $TotBUD + $JOBCOST;
					
					$TotBOQ		= $TotBOQ + $JOBCOST + $ADD_JOBCOST;
					$TotADD		= $TotADD + $ADD_JOBCOST;
					$TotALL		= $TotBOQ + $TotADD;
					$TotUSE		= $TotUSE + $ITM_USED_AM;
					
					$REMAIN		= $JOBCOST + $ADD_JOBCOST - $ITM_USED_AM;
					$TotREM		= $TotREM + $REMAIN2;

					// COUNT PROJECTION COMPLETED
						$TOTUSED_01	= $ITM_USED_AM;
						if($JOBUNIT == 'LS')
							$TOTREMA_01	= ($JOBCOST + $ADD_JOBCOST - $TOTUSED_01);
						else
							$TOTREMA_01	= ($JOBVOLM + $ADD_VOLM - $ITM_USED) * $ITM_LASTP;
						
						$TOTPRJC_01	= $TOTUSED_01 + $TOTREMA_01;	// Total Projection Complete
						$TotPC		= $TotPC + $TOTPRJC_01;
				}

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
}