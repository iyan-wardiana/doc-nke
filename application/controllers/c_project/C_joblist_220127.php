<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 20 Desember 2021
	* File Name		= C_joblist.php
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
			redirect('__l1y');
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
			redirect('__l1y');
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
			redirect('__l1y');
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
			redirect('__l1y');
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
			redirect('__l1y');
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
			$num_rows 		= $this->m_joblist->get_AllDataBQC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			
			$query 			= $this->m_joblist->get_AllDataBQL($PRJCODE, $search, $length, $start, $order, $dir);
								
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
				$ISLASTH		= $dataI['ISLASTH'];
				$ISLAST			= $dataI['ISLAST'];

				$CollID			= "$PRJCODE~$JOBCODEID";

				$secDel 		= base_url().'index.php/c_project/c_joblist/delJL/?id=';
				$delJobID 		= "$secDel~$PRJCODE~$JOBCODEID";

				$s_GDET1 		= "tbl_joblist_detail WHERE JOBPARENT = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";
				$r_GDET1 		= $this->db->count_all($s_GDET1);

				$r_GDET 		= $r_GDET1;
				$COLITM0 		= "";
				$secDelete 		= "";
				if($r_GDET == 0)
				{
					$secDelete	= 	"<input type='hidden' name='urlDelJob".$noU."' id='urlDelJob".$noU."' value='".$delJobID."'>
								   	<label style='white-space:nowrap'>
										<a href='javascript:void(null);' class='btn bg-red btn-xs' onClick='deleteJL(".$noU.")' title='Hapus'>
											<i class='glyphicon glyphicon-trash'></i>
										</a>
									</label>";
				}
				else
				{
					$s_ITM0 	= "SELECT ITM_CODE FROM tbl_joblist_detail WHERE JOBPARENT = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";
					$r_ITM0 	= $this->db->query($s_ITM0)->result();
					foreach($r_ITM0 as $rw_ITM0):
						$ITM_CODE0	= $rw_ITM0->ITM_CODE;
						$COLITM0	= "$COLITM0, $ITM_CODE0";
					endforeach;
				}

				$secUpd			= site_url('c_project/c_joblist/upd1d0ebb/?id='.$this->url_encryption_helper->encode_url($CollID));
				$secPrint		= 	"<input type='hidden' name='urlUpdate".$noU."' id='urlUpdate".$noU."' value='".$secUpd."'>
									<label style='white-space:nowrap'>
									   	<a href='javascript:void(null);' class='btn btn-warning btn-xs' onClick='updJob(".$noU.")'>
											<i class='glyphicon glyphicon-pencil'></i>
										</a>
									</label>";

				$secAddDetV 	= "";
				$r_RAPC 		= "";
				if($ISLASTH == 1)
				{
					$secAddDet		= site_url('c_project/c_joblist/addJDet/?id='.$this->url_encryption_helper->encode_url($CollID));
					$s_RAPC 		= "tbl_jobcreate_header WHERE PRJCODE = '$PRJCODE' AND JOB_PARCODE = '$JOBCODEID'";
					$r_RAPC 		= $this->db->count_all($s_RAPC);
					if($r_RAPC > 0)
					{
						$secAddDet	= site_url('c_project/c_joblist/updJDet/?id='.$this->url_encryption_helper->encode_url($CollID));
						$secAddDetV	= 	"<input type='hidden' name='urlAddJDet".$noU."' id='urlAddJDet".$noU."' value='".$secAddDet."'>
										<label style='white-space:nowrap'>
											<a href='".$secAddDet."' class='btn btn-success btn-xs' title='Update'>
												<i class='glyphicon glyphicon-edit'></i>
											</a>
										</div>";
					}
					else
					{
						$secAddDetV	= 	"<input type='hidden' name='urlAddJDet".$noU."' id='urlAddJDet".$noU."' value='".$secAddDet."'>
										<label style='white-space:nowrap'>
											<a href='".$secAddDet."' class='btn btn-warning btn-xs' title='Create'>
												<i class='glyphicon glyphicon-plus-sign'></i>
											</a>
										</div>";
					}
				}

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
					
				//$JOBVOLM 		= 1;

				// SPACE
					if($JOBLEV == 1)
						$spaceLev 	= 0;
					elseif($JOBLEV == 2)
						$spaceLev 	= 15;
					elseif($JOBLEV == 3)
						$spaceLev 	= 30;
					elseif($JOBLEV == 4)
						$spaceLev 	= 45;
					elseif($JOBLEV == 5)
						$spaceLev 	= 60;
					elseif($JOBLEV == 6)
						$spaceLev 	= 75;
					elseif($JOBLEV == 7)
						$spaceLev 	= 90;
					elseif($JOBLEV == 8)
						$spaceLev 	= 105;
					elseif($JOBLEV == 9)
						$spaceLev 	= 120;
					elseif($JOBLEV == 10)
						$spaceLev 	= 135;
					elseif($JOBLEV == 11)
						$spaceLev 	= 150;
					elseif($JOBLEV == 12)
						$spaceLev 	= 165;

				$JobView1		= "$JOBCODEID - $JOBDESC1";
				$JobView 		= wordwrap($JobView1, 80, "<br>", true);

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

				$output['data'][] 	= array("<div style='text-align:center;'><label style='white-space:nowrap;'>$secDelete $secAddDetV</label></div>",
											"<span ".$CELL_COL." title='".$COLITM0."'><div style='margin-left: ".$spaceLev."px;'>".$JobView."</div></span>",
											"<div style='text-align:center;'><span ".$CELL_COL.">".$JOBLEV."</span></div>",
											"<div style='text-align:center;'><span ".$CELL_COL.">".$JOBUNIT."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".number_format($JOBVOLM, 2)."</span></div>",
											"<div style='text-align:right;'><span class='label label-".$STATCOL."' style='font-size:12px'>".number_format($BOQ_JOBCOST,2)."</span></div>",
											"<div style='text-align:right;'><span class='label label-".$STATCOL."' style='font-size:12px'>".number_format($JOBCOST,2)."</span></div>",
											"<div style='text-align:right;'><span class='label label-".$STATCOL."' style='font-size:12px'>".number_format($BOQ_DEVIASI,2)."</span></div>",
											"<div style='text-align:right;'><span class='label label-".$STATCOL."' style='font-size:12px'>".number_format($ADD_JOBCOST,2)."</span></div>",
											"<div style='text-align:right;'><span class='label label-".$STATCOL."' style='font-size:12px'>".number_format($ITM_USED_AM,2)."</span></div>");

				$noU			= $noU + 1;
			}

			/*$output['data'][] 	= array("A",
										"B",
										"C",
										"D",
										"E",
										"F",
										"F",
										"G",
										"H");*/
			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function addJDet() // G
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$CollID1	= $_GET['id'];
		$CollID		= $this->url_encryption_helper->decode_url($CollID1);
		$CollIDEXPL	= explode("~", $CollID);
		$PRJCODE 	= $CollIDEXPL[0];
		$JOBPARCODE = $CollIDEXPL[1];
			
		// GET MENU DESC
			$mnCode				= 'MN271';
			$data["MenuApp"] 	= 'MN271';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$data['PRJCODE'] 	= $PRJCODE;
			$data['JOBPARCODE'] = $JOBPARCODE;
			$data['task'] 		= 'add';
			$data['totalrow'] 	= 0;
			$data['form_action']= site_url('c_project/c_joblist/addJDet_process');
			$data['backURL'] 	= site_url('c_project/c_joblist/get_all_joblist/?id='.$this->url_encryption_helper->encode_url($PRJCODE));

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $appName;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN271';
				$TTR_CATEG		= 'A-RAP';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$this->load->view('v_project/v_joblist/v_addjobdet_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function addJDet_process() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			date_default_timezone_set("Asia/Jakarta");

			$this->db->trans_begin();

				$PRJCODE 		= $this->input->post('PRJCODE');
				$JOB_NUM 		= $this->input->post('JOB_NUM');
				$JOB_PARCODE 	= $this->input->post('JOB_PARCODE');
				$JOB_PARDESC 	= $this->input->post('JOB_PARDESC');
				$JOB_UNIT 		= $this->input->post('JOB_UNIT');
				$JOB_BOQV 		= $this->input->post('JOB_BOQV');
				$JOB_BOQP 		= $this->input->post('JOB_BOQP');
				$JOB_BOQT 		= $this->input->post('JOB_BOQT');
				$JOB_RAPV 		= $this->input->post('JOB_RAPV');
				$JOB_RAPP 		= $this->input->post('JOB_RAPP');
				$JOB_RAPT 		= $this->input->post('JOB_RAPT');
				$JOB_NOTE 		= $this->input->post('JOB_NOTE');
				$JOB_STAT 		= $this->input->post('JOB_STAT');

				$jobAddH 		= array('PRJCODE' 		=> $this->input->post('PRJCODE'),
										'JOB_NUM' 		=> $this->input->post('JOB_NUM'),
										'JOB_PARCODE' 	=> $this->input->post('JOB_PARCODE'),
										'JOB_PARDESC' 	=> $this->input->post('JOB_PARDESC'),
										'JOB_UNIT' 		=> $this->input->post('JOB_UNIT'),
										'JOB_BOQV' 		=> $this->input->post('JOB_BOQV'),
										'JOB_BOQP' 		=> $this->input->post('JOB_BOQP'),
										'JOB_BOQT' 		=> $this->input->post('JOB_BOQT'),
										'JOB_RAPV' 		=> $this->input->post('JOB_RAPV'),
										'JOB_RAPP' 		=> $this->input->post('JOB_RAPP'),
										'JOB_RAPT' 		=> $this->input->post('JOB_RAPT'),
										'JOB_NOTE' 		=> $this->input->post('JOB_NOTE'),
										'JOB_STAT' 		=> $this->input->post('JOB_STAT'));
				$this->m_joblist->addRAP($jobAddH);

				foreach($_POST['data'] as $d)
				{
					$d['JOB_NUM'] 	= $JOB_NUM;
					$this->db->insert('tbl_jobcreate_detail',$d);
				}
				
				// START : UPDATE TO T-TRACK
					date_default_timezone_set("Asia/Jakarta");
					$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
					$TTR_PRJCODE	= $PRJCODE;
					$TTR_REFDOC		= $this->input->post('JOBCODEID');
					$MenuCode 		= 'MN271';
					$TTR_CATEG		= 'C-RAP';
					
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
			
			$url			= site_url('c_project/c_joblist/get_all_joblist/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function updJDet() // OK
	{
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$CollID1	= $_GET['id'];
		$CollID		= $this->url_encryption_helper->decode_url($CollID1);
		$CollIDEXPL	= explode("~", $CollID);
		$PRJCODE 	= $CollIDEXPL[0];
		$JOBPARCODE = $CollIDEXPL[1];
			
		// GET MENU DESC
			$mnCode				= 'MN271';
			$data["MenuApp"] 	= 'MN271';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		if ($this->session->userdata('login') == TRUE)
		{				
			$docPatternPosition = 'Especially';	
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_project/c_joblist/updJDet_process');
			$data['backURL'] 	= site_url('c_project/c_joblist/get_all_joblist/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			$getJPAR 			= $this->m_joblist->get_par_by_number($JOBPARCODE, $PRJCODE)->row();
			$data['PRJCODE']	= $getJPAR->PRJCODE;
			$data['JOB_NUM'] 	= $getJPAR->JOB_NUM;
			$data['JOB_PARCODE']= $getJPAR->JOB_PARCODE;
			$JOB_PARCODE 		= $getJPAR->JOB_PARCODE;
			$data['JOB_PARDESC']= $getJPAR->JOB_PARDESC;
			$data['JOB_UNIT'] 	= $getJPAR->JOB_UNIT;
			$data['JOB_BOQV'] 	= $getJPAR->JOB_BOQV;
			$data['JOB_BOQP'] 	= $getJPAR->JOB_BOQP;
			$data['JOB_BOQT'] 	= $getJPAR->JOB_BOQT;
			$data['JOB_RAPV'] 	= $getJPAR->JOB_RAPV;
			$data['JOB_RAPP'] 	= $getJPAR->JOB_RAPP;
			$data['JOB_RAPT'] 	= $getJPAR->JOB_RAPT;
			$data['JOB_NOTE'] 	= $getJPAR->JOB_NOTE;
			$data['JOB_STAT'] 	= $getJPAR->JOB_STAT;
			$data['JOBPARCODE'] = $JOBPARCODE;

			$sqlDET	= "tbl_jobcreate_detail WHERE JOBPARENT = '$JOB_PARCODE' AND PRJCODE =  '$PRJCODE'";
			$resDET = $this->db->count_all($sqlDET);
			$data['totalrow'] 	= $resDET;

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= "$PRJCODE";
				$TTR_REFDOC		= $getJPAR->JOB_NUM;
				$MenuCode 		= 'MN271';
				$TTR_CATEG		= 'U-RAP';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$this->load->view('v_project/v_joblist/v_addjobdet_form', $data); 
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function updJDet_process() // 
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			date_default_timezone_set("Asia/Jakarta");

			$this->db->trans_begin();

				$PRJCODE 		= $this->input->post('PRJCODE');
				$JOB_NUM 		= $this->input->post('JOB_NUM');
				$JOB_PARCODE 	= $this->input->post('JOB_PARCODE');
				$JOB_PARDESC 	= $this->input->post('JOB_PARDESC');
				$JOB_UNIT 		= $this->input->post('JOB_UNIT');
				$JOB_BOQV 		= $this->input->post('JOB_BOQV');
				$JOB_BOQP 		= $this->input->post('JOB_BOQP');
				$JOB_BOQT 		= $this->input->post('JOB_BOQT');
				$JOB_RAPV 		= $this->input->post('JOB_RAPV');
				$JOB_RAPP 		= $this->input->post('JOB_RAPP');
				$JOB_RAPT 		= $this->input->post('JOB_RAPT');
				$JOB_NOTE 		= $this->input->post('JOB_NOTE');
				$JOB_STAT 		= $this->input->post('JOB_STAT');

				$jobAddH 		= array('PRJCODE' 		=> $this->input->post('PRJCODE'),
										'JOB_NUM' 		=> $this->input->post('JOB_NUM'),
										'JOB_PARCODE' 	=> $this->input->post('JOB_PARCODE'),
										'JOB_PARDESC' 	=> $this->input->post('JOB_PARDESC'),
										'JOB_UNIT' 		=> $this->input->post('JOB_UNIT'),
										'JOB_BOQV' 		=> $this->input->post('JOB_BOQV'),
										'JOB_BOQP' 		=> $this->input->post('JOB_BOQP'),
										'JOB_BOQT' 		=> $this->input->post('JOB_BOQT'),
										'JOB_RAPV' 		=> $this->input->post('JOB_RAPV'),
										'JOB_RAPP' 		=> $this->input->post('JOB_RAPP'),
										'JOB_RAPT' 		=> $this->input->post('JOB_RAPT'),
										'JOB_NOTE' 		=> $this->input->post('JOB_NOTE'),
										'JOB_STAT' 		=> $this->input->post('JOB_STAT'));
				$this->m_joblist->updRAPH($JOB_NUM, $jobAddH);

				$s_00 	= "DELETE FROM tbl_jobcreate_detail WHERE JOB_NUM = '$JOB_NUM' AND ISLOCK = 0";
				$this->db->query($s_00);

				foreach($_POST['data'] as $d)
				{
					$d['JOB_NUM'] 	= $JOB_NUM;
					$this->db->insert('tbl_jobcreate_detail',$d);
				}
				
				// START : UPDATE TO T-TRACK
					date_default_timezone_set("Asia/Jakarta");
					$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
					$TTR_PRJCODE	= $PRJCODE;
					$TTR_REFDOC		= $this->input->post('JOBCODEID');
					$MenuCode 		= 'MN271';
					$TTR_CATEG		= 'C-RAP';
					
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
			
			$url			= site_url('c_project/c_joblist/get_all_joblist/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}

  	function get_AllDataRAPDET() // GOOD
	{
		$collData		= $_GET['id'];
		$arrCDATA 		= explode("~", $collData);
		$PRJCODE 		= $arrCDATA[0];
		$JOBPARENT 		= $arrCDATA[1];

		setlocale(LC_ALL, 'id-ID', 'id_ID');

		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

		$LangID     = $this->session->userdata['LangID'];
        
        $sqlTransl      = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
        $resTransl      = $this->db->query($sqlTransl)->result();
        foreach($resTransl as $rowTransl) :
            $TranslCode = $rowTransl->MLANG_CODE;
            $LangTransl = $rowTransl->LangTransl;

    		if($TranslCode == 'Director')$Director = $LangTransl;
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
									"ITM_CODE",
									"JOBPARENT",
									"ITM_NAME");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_joblist->get_AllDataRAPDC($PRJCODE, $JOBPARENT, $search); 
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();

			$s_00 			= "tbl_joblist_detail WHERE JOBPARENT = '$JOBPARENT' AND PRJCODE = '$PRJCODE'";
			$r_00 			= $this->db->count_all($s_00);

			if($num_rows == 0 && $r_00 > 1)
			{
				$this->m_joblist->addJC($PRJCODE, $JOBPARENT, $search, $length, $start, $order, $dir);
			}
			
			$num_rows 		= $this->m_joblist->get_AllDataRAPDC($PRJCODE, $JOBPARENT, $search);
			$totalrow 		= $num_rows;
			$query 			= $this->m_joblist->get_AllDataRAPDL($PRJCODE, $JOBPARENT, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$JOBD_ID 		= $dataI['JOBD_ID'];
				$JOB_NUM 		= $dataI['JOB_NUM'];
				$JOBCODEID		= $dataI['JOBCODEID'];
				$JOBPARENT		= $dataI['JOBPARENT'];
				$ITM_CODE		= $dataI['ITM_CODE'];
				$ITM_NAME		= $dataI['ITM_NAME'];
				$ITM_UNIT		= $dataI['ITM_UNIT'];
				$ITM_GROUP		= $dataI['ITM_GROUP'];
				$ITM_KOEF		= $dataI['ITM_KOEF'];
				$ITM_RAPV		= $dataI['ITM_RAPV'];
				$ITM_RAPP		= $dataI['ITM_RAPP'];
				$ITM_TOTAL		= $dataI['ITM_TOTAL'];
				$ITM_NOTES		= $dataI['ITM_NOTES'];
				$ISLOCK			= $dataI['ISLOCK'];
				$LOCKER_ID		= $dataI['LOCKER_ID'];
				$LOCKER_NM		= $dataI['LOCKER_NM'];

				$CollID			= "$PRJCODE~$JOBCODEID";

				// CHECK TRANSACTION
					$s_trx 		= 	"SELECT COUNT(*) AS TOT_TRX FROM tbl_pr_detail WHERE PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBCODEID'
									UNION
									SELECT COUNT(*) AS TOT_TRX FROM tbl_wo_detail WHERE PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBCODEID'
									UNION
									SELECT COUNT(*) AS TOT_TRX FROM tbl_journaldetail WHERE proj_Code = '$PRJCODE' AND JOBCODEID = '$JOBCODEID'";
					$r_trx 		= $this->db->query($s_trx)->result();
					foreach($r_trx as $rw_trx):
						$TOT_TRX= $rw_trx->TOT_TRX;
					endforeach;
					if($TOT_TRX == '')
						$TOT_TRX= 0;

				$secDel 		= base_url().'index.php/c_project/c_joblist/delComp/?id=';
				$delID 			= "$secDel~$PRJCODE~$JOB_NUM~$JOBPARENT~$ITM_CODE~$ITM_NAME~$JOBD_ID";
                $secLock 		= base_url().'index.php/c_project/c_joblist/lockComp/?id=';
                $lockID 		= "$secLock~$PRJCODE~$JOB_NUM~$JOBPARENT~$ITM_CODE~$ITM_NAME";
                $secUndo 		= base_url().'index.php/c_project/c_joblist/undoComp/?id=';
                $undoID 		= "$secUndo~$PRJCODE~$JOB_NUM~$JOBPARENT~$ITM_CODE~$JOBCODEID~$JOBD_ID";
				if($ISLOCK == 0)
				{
					$secAction	= 	"<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								   	<input type='hidden' name='urlLock".$noU."' id='urlLock".$noU."' value='".$lockID."'>
								   	<label style='white-space:nowrap'>
										<a href='javascript:void(null);' class='btn bg-red btn-xs' onClick='delRow(".$noU.")' title='Hapus'>
											<i class='glyphicon glyphicon-trash'></i>
										</a>
										<a href='javascript:void(null);' class='btn bg-yellow btn-xs' onClick='lockRow(".$noU.")' title='Kunci'>
											<i class='fa fa-unlock-alt'></i>
										</a>
									</label>";

					$output['data'][] 	= array($secAction,
												"$ITM_CODE
	                                          	<input type='hidden' id='data".$noU."ITM_CODE' name='data[".$noU."][ITM_CODE]' value='".$ITM_CODE."' class='form-control' style='max-width:300px;'>
	                                          	<input type='hidden' id='data".$noU."PRJCODE' name='data[".$noU."][PRJCODE]' value='".$PRJCODE."' class='form-control' style='max-width:300px;'>
	                                          	<input type='hidden' id='data".$noU."JOBPARENT' name='data[".$noU."][JOBPARENT]' value='".$JOBPARENT."' class='form-control' style='max-width:300px;'>
	                                          	<input type='hidden' id='data".$noU."ITM_GROUP' name='data[".$noU."][ITM_GROUP]' value='".$ITM_GROUP."' class='form-control' style='max-width:300px;'>",
												"<div style='white-space:nowrap'>$ITM_NAME
												<input type='hidden' id='data".$noU."ITM_NAME' name='data[".$noU."][ITM_NAME]' value='".$ITM_NAME."' class='form-control' style='max-width:300px;'></div>",
												"<input type='text' name='ITM_RAPVX".$noU."' id='ITM_RAPVX".$noU."' value='".number_format($ITM_RAPV, 2)."' class='form-control' style='min-width:80px; max-width:90px; text-align:right' onKeyPress='return isIntOnlyNew(event);' onBlur='chgVOL(this,".$noU.");' >
												<input type='hidden' name='data[".$noU."][ITM_RAPV]' id='data".$noU."ITM_RAPV' value='".$ITM_RAPV."' class='form-control' style='max-width:300px;' >",
												"<input type='text' name='ITM_KOEFX".$noU."' id='ITM_KOEFX".$noU."' value='".number_format($ITM_KOEF, 4)."' class='form-control' style='min-width:60px; max-width:70px; text-align:right' onKeyPress='return isIntOnlyNew(event);' onBlur='chgKOEF(this,".$noU.");' >
												<input type='hidden' name='data[".$noU."][ITM_KOEF]' id='data".$noU."ITM_KOEF' value='".$ITM_KOEF."' class='form-control' style='max-width:300px;' >",
												"<input type='text' name='ITM_RAPPX".$noU."' id='ITM_RAPPX".$noU."' value='".number_format($ITM_RAPP, 2)."' class='form-control' style='min-width:110px; max-width:120px; text-align:right' onKeyPress='return isIntOnlyNew(event);' onBlur='chgPRICE(this,".$noU.");' >
												<input type='hidden' name='data[".$noU."][ITM_RAPP]' id='data".$noU."ITM_RAPP' value='".$ITM_RAPP."' class='form-control' style='max-width:300px;' >",
												"<input type='text' name='ITM_TOTALX".$noU."' id='ITM_TOTALX".$noU."' value='".number_format($ITM_TOTAL, 2)."' class='form-control' style='min-width:110px; max-width:130px; text-align:right' onKeyPress='return isIntOnlyNew(event);' >
												<input type='hidden' name='data[".$noU."][ITM_TOTAL]' id='data".$noU."ITM_TOTAL' value='".$ITM_TOTAL."' class='form-control' style='max-width:300px;' >",
												"$ITM_UNIT
												<input type='hidden' id='data".$noU."ITM_UNIT' name='data[".$noU."][ITM_UNIT]' value='".$ITM_UNIT."' class='form-control'>",
												"<input type='text' id='data".$noU."ITM_NOTES' name='data[".$noU."][ITM_NOTES]' value='".$ITM_NOTES."' class='form-control' style='max-width:300px;'><input type='hidden' id='data".$noU."ISLOCK' name='data[".$noU."][ISLOCK]' value='".$ISLOCK."' class='form-control' style='max-width:300px;'>");
				}
				else
				{
					$btnUndo 	= "";
					// UNTUK SEMENTARA BELUM ADA PENGUNCIAN KARENA TRANSAKSI. REQ BY PAK EDI S TGL. 25 JAN 2022 : 14.00 WIB
					$TOT_TRX 	= 0;
					if($TOT_TRX == 0)
					{
						$btnUndo= "<input type='hidden' name='urlUndo".$noU."' id='urlUndo".$noU."' value='".$undoID."'>
									<a href='javascript:void(null);' class='btn bg-yellow btn-xs' onClick='undoRow(".$noU.")' title='Batalkan RAP'>
										<i class='fa fa-undo'></i>
									</a>";
					}
					$secAction	= 	"<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								   	<input type='hidden' name='urlLock".$noU."' id='urlLock".$noU."' value='".$lockID."'>
								   	<label style='white-space:nowrap'>
										<a href='javascript:void(null);' class='btn bg-green btn-xs' onClick='lockRow(".$noU.")' title='Lock' disabled='disabled'>
											<i class='fa fa-lock'></i>
										</a>
										".$btnUndo."
									</label>";

					$output['data'][] 	= array($secAction,
												"$ITM_CODE
	                                          	<input type='hidden' id='data".$noU."ITM_CODE' name='data[".$noU."][ITM_CODE]' value='".$ITM_CODE."' class='form-control' style='max-width:300px;'>
	                                          	<input type='hidden' id='data".$noU."PRJCODE' name='data[".$noU."][PRJCODE]' value='".$PRJCODE."' class='form-control' style='max-width:300px;'>
	                                          	<input type='hidden' id='data".$noU."JOBPARENT' name='data[".$noU."][JOBPARENT]' value='".$JOBPARENT."' class='form-control' style='max-width:300px;'>
	                                          	<input type='hidden' id='data".$noU."ITM_GROUP' name='data[".$noU."][ITM_GROUP]' value='".$ITM_GROUP."' class='form-control' style='max-width:300px;'>",
												"<div style='white-space:nowrap'>$ITM_NAME
												<input type='hidden' id='data".$noU."ITM_NAME' name='data[".$noU."][ITM_NAME]' value='".$ITM_NAME."' class='form-control' style='max-width:300px;'></div>",
												"".number_format($ITM_RAPV, 2)."
												<input type='hidden' name='ITM_RAPVX".$noU."' id='ITM_RAPVX".$noU."' value='".number_format($ITM_RAPV, 2)."' class='form-control' style='min-width:80px; max-width:90px; text-align:right' onKeyPress='return isIntOnlyNew(event);' onBlur='chgVOL(this,".$noU.");' >
												<input type='hidden' name='data[".$noU."][ITM_RAPV]' id='data".$noU."ITM_RAPV' value='".$ITM_RAPV."' class='form-control' style='max-width:300px;' >",
												"".number_format($ITM_KOEF, 4)."
												<input type='hidden' name='ITM_KOEFX".$noU."' id='ITM_KOEFX".$noU."' value='".number_format($ITM_KOEF, 4)."' class='form-control' style='min-width:60px; max-width:70px; text-align:right' onKeyPress='return isIntOnlyNew(event);' onBlur='chgKOEF(this,".$noU.");' >
												<input type='hidden' name='data[".$noU."][ITM_KOEF]' id='data".$noU."ITM_KOEF' value='".$ITM_KOEF."' class='form-control' style='max-width:300px;' >",
												"".number_format($ITM_RAPP, 2)."
												<input type='hidden' name='ITM_RAPPX".$noU."' id='ITM_RAPPX".$noU."' value='".number_format($ITM_RAPP, 2)."' class='form-control' style='min-width:110px; max-width:120px; text-align:right' onKeyPress='return isIntOnlyNew(event);' onBlur='chgPRICE(this,".$noU.");' >
												<input type='hidden' name='data[".$noU."][ITM_RAPP]' id='data".$noU."ITM_RAPP' value='".$ITM_RAPP."' class='form-control' style='max-width:300px;' >",
												"".number_format($ITM_TOTAL, 2)."
												<input type='hidden' name='ITM_TOTALX".$noU."' id='ITM_TOTALX".$noU."' value='".number_format($ITM_TOTAL, 2)."' class='form-control' style='min-width:110px; max-width:130px; text-align:right' onKeyPress='return isIntOnlyNew(event);' onBlur='chgVOL(this,".$noU.");' >
												<input type='hidden' name='data[".$noU."][ITM_TOTAL]' id='data".$noU."ITM_TOTAL' value='".$ITM_TOTAL."' class='form-control' style='max-width:300px;' >",
												"$ITM_UNIT
												<input type='hidden' id='data".$noU."ITM_UNIT' name='data[".$noU."][ITM_UNIT]' value='".$ITM_UNIT."' class='form-control'>",
												"".$ITM_NOTES."
												<input type='hidden' id='data".$noU."ITM_NOTES' name='data[".$noU."][ITM_NOTES]' value='".$ITM_NOTES."' class='form-control' style='max-width:300px;'><input type='hidden' id='data".$noU."ISLOCK' name='data[".$noU."][ISLOCK]' value='".$ISLOCK."' class='form-control' style='max-width:300px;'>");
				}

				$noU			= $noU + 1;
			}

			/*$output['data'][] 	= array("A = $totalrow",
										"B",
										"C",
										"D",
										"E",
										"F",
										"F",
										"G",
										"H");*/
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

  	function get_AllDataITM2() // GOOD
	{
		setlocale(LC_ALL, 'id-ID', 'id_ID');

		$PRJCODE 	= $_GET['id'];
		$JOBPARENT 	= $_GET['JID'];

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
    		if($TranslCode == 'Code')$Code = $LangTransl;
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
									"ITM_NAME", 
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
				$ITM_NAMEV		= wordwrap($ITM_NAME, 50, "<br>", TRUE);
				$ITM_UNIT		= $dataI['ITM_UNIT'];
				$ITM_GROUP		= $dataI['ITM_GROUP'];
				$ITM_VOLM		= $dataI['ITM_VOLM'];
				$ITM_PRICE		= $dataI['ITM_PRICE'];
				$ITM_BOQV		= $dataI['BOQ_ITM_VOLM'];
				$ITM_BOQP		= $dataI['BOQ_ITM_TOTALP'];
				$PR_VOLM		= $dataI['PR_VOLM'];
				$PR_AMOUNT		= $dataI['PR_AMOUNT'];
				$ITM_REMV 		= $ITM_BOQV - $PR_VOLM;
				$ITM_REMP 		= $ITM_BOQP - $PR_AMOUNT;	

				$JOBPARDESC 	= "-";
				$sqlJlD			= "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JOBPARENT' AND  PRJCODE = '$PRJCODE' LIMIT 1";
			    $resJlD			= $this->db->query($sqlJlD)->result();
			    foreach($resJlD as $rowJLD) :
				    $JOBPARDESC = $rowJLD->JOBDESC;
				endforeach;

				$chkBox			= "<input type='checkbox' name='chk1' value='".$PRJCODE."|".$JOBPARENT."|".$JOBPARDESC."|".$ITM_CODE."|".$ITM_NAME."|".$ITM_UNIT."|".$ITM_GROUP."' onClick='pickThis1(this);'/>";

				$output['data'][] 	= array($chkBox,
											"<div style='white-space:nowrap'>
										  		<span>".$ITM_NAMEV."</span>
										  	</div>
										  	<div style='margin-left: 15px; font-style: italic; white-space:nowrap'>
										  		$Code : ".$ITM_CODE."
										  	</div>",
											$ITM_UNIT,
											$ITM_GROUP,
											"<div style='white-space:nowrap' title='Volume'>
										  		<span>".number_format($ITM_BOQV, 2)." (V)</span>
										  	</div>
										  	<div style='margin-left: 15px; white-space:nowrap' title='Jumlah'>
										  		".number_format($ITM_BOQP, 2)." (J)
										  	</div>",
											"<div style='white-space:nowrap' title='Volume'>
										  		<span>".number_format($PR_VOLM, 2)." (V)</span>
										  	</div>
										  	<div style='margin-left: 15px; white-space:nowrap' title='Jumlah'>
										  		".number_format($PR_AMOUNT, 2)." (J)
										  	</div>",
											"<div style='white-space:nowrap' title='Volume'>
										  		<span>".number_format($ITM_REMV, 2)." (V)</span>
										  	</div>
										  	<div style='margin-left: 15px; white-space:nowrap' title='Jumlah'>
										  		".number_format($ITM_REMP, 2)." (J)
										  	</div>",);

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

  	function get_AllDataITM3() // GOOD
	{
		setlocale(LC_ALL, 'id-ID', 'id_ID');

		$PRJCODE 	= $_GET['id'];
		$JOBPARENT 	= $_GET['JID'];
		$ITM_GROUP 	= $_GET['CATEG'];

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
    		if($TranslCode == 'Code')$Code = $LangTransl;
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
									"ITM_NAME", 
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
			$num_rows 		= $this->m_joblist->get_AllDataITMHOC($ITM_GROUP, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			
			$query 			= $this->m_joblist->get_AllDataITMHOL($ITM_GROUP, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$ITM_CODE		= $dataI['ITM_CODE'];
				$ITM_NAME		= $dataI['ITM_NAME'];
				$ITM_NAMEV		= wordwrap($ITM_NAME, 50, "<br>", TRUE);
				$ITM_UNIT		= $dataI['ITM_UNIT'];
				$ITM_GROUP		= $dataI['ITM_GROUP'];
				$ITM_VOLM		= $dataI['ITM_VOLM'];
				$ITM_PRICE		= $dataI['ITM_PRICE'];
				$ITM_BOQV		= $dataI['BOQ_ITM_VOLM'];
				$ITM_BOQP		= $dataI['BOQ_ITM_TOTALP'];
				$PR_VOLM		= $dataI['PR_VOLM'];
				$PR_AMOUNT		= $dataI['PR_AMOUNT'];
				$ITM_REMV 		= $ITM_BOQV - $PR_VOLM;
				$ITM_REMP 		= $ITM_BOQP - $PR_AMOUNT;	

				$JOBPARDESC 	= "-";
				$sqlJlD			= "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JOBPARENT' AND  PRJCODE = '$PRJCODE' LIMIT 1";
			    $resJlD			= $this->db->query($sqlJlD)->result();
			    foreach($resJlD as $rowJLD) :
				    $JOBPARDESC = $rowJLD->JOBDESC;
				endforeach;

				$chkBox			= "<input type='checkbox' name='chk1' value='".$PRJCODE."|".$JOBPARENT."|".$JOBPARDESC."|".$ITM_CODE."|".$ITM_NAME."|".$ITM_UNIT."|".$ITM_GROUP."' onClick='pickThis1(this);'/>";

				$output['data'][] 	= array($chkBox,
											"<div style='white-space:nowrap'>
										  		<span>".$ITM_NAMEV."</span>
										  	</div>
										  	<div style='margin-left: 15px; font-style: italic; white-space:nowrap'>
										  		$Code : ".$ITM_CODE."
										  	</div>",
											$ITM_UNIT);

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
			redirect('__l1y');
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
			
			$data['PRJCODE'] 	= $PRJCODE;
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
		$PRJCODE	= $_GET['id'];

		$LangID     = $this->session->userdata['LangID'];
        
        $sqlTransl      = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
        $resTransl      = $this->db->query($sqlTransl)->result();
        foreach($resTransl as $rowTransl) :
            $TranslCode = $rowTransl->MLANG_CODE;
            $LangTransl = $rowTransl->LangTransl;

    		if($TranslCode == 'Complete')$Complete = $LangTransl;
    		if($TranslCode == 'Processed')$Processed = $LangTransl;
    		if($TranslCode == 'mstAnl')$mstAnl = $LangTransl;
    		if($TranslCode == 'JobCost')$JobCost = $LangTransl;
    		if($TranslCode == 'RAPAmn')$RAPAmn = $LangTransl;
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
			$num_rows 		= $this->m_joblist->get_AllDataJANC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_joblist->get_AllDataJANL($PRJCODE, $search, $length, $start, $order, $dir);
								
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
				$JOBCOST 		= $dataI['JOBCOST'];
				$MAN_NUM		= $dataI['MAN_NUM'];
				$JAN_STAT		= $dataI['JAN_STAT'];
				$JAN_CREATER 	= $dataI['JAN_CREATER'];
				$JAN_CREATED	= $dataI['JAN_CREATED'];
				$JAN_CREATEDV	= date('d M Y', strtotime($JAN_CREATED));		

				$JOBDESC 		= "-";
				$sqlJlD			= "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JOBCODEID' AND  PRJCODE = '$PRJCODE' LIMIT 1";
			    $resJlD			= $this->db->query($sqlJlD)->result();
			    foreach($resJlD as $rowJLD) :
				    $JOBDESC 		= $rowJLD->JOBDESC;
				endforeach;

				$TOT_RAP 		= 0;
				$sqlTRAP		= "SELECT SUM(ITM_TOTAL) AS TOT_RAP FROM tbl_janalysis_detail WHERE JAN_NUM = '$JAN_NUM' AND PRJCODE = '$PRJCODE'";
			    $resTRAP		= $this->db->query($sqlTRAP)->result();
			    foreach($resTRAP as $rwTRAP) :
				    $TOT_RAP 	= $rwTRAP->TOT_RAP;
				endforeach;
				
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
				
				$output['data'][] 	= array("$noU.",
										  	$JAN_CODE,
										  	$JAN_NAME,
											"<div style='white-space:nowrap'>
											  	<strong><i class='fa fa-cogs margin-r-5'></i>".$mstAnl."</strong><br>
										  		<span class='text-muted' style='margin-left: 18px'>
										  			".$JOBDESC."
										  		</span>
									  		</div>
									  		<div style='white-space:nowrap'>
									  			<div class='row'>
										  			<div class='col-sm-6'>
													  	<strong><i class='fa fa-money margin-r-5'></i>".$JobCost."</strong><br>
												  		<span class='text-muted' style='margin-left: 18px'>
												  			".number_format($JOBCOST,2)."
												  		</span>
											  		</div>
										  			<div class='col-sm-6'>
													  	<strong><i class='fa fa-money margin-r-5'></i>".$RAPAmn."</strong><br>
												  		<span class='text-muted' style='margin-left: 18px'>
												  			".number_format($JOBCOST,2)."
												  		</span>
											  		</div>
											  	</div>
									  		</div>",
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
				$ISLASTH		= $dataI['ISLASTH'];
				$ISLAST			= $dataI['ISLAST'];

				// IS LAST SETT
					$JOBDESC1 	= wordwrap($JOBDESC, 40, "<br>", true);
					$STATCOL	= 'primary';
					$CELL_COL	= "style='font-weight:bold; white-space:nowrap'";

				/*$s_01 		= "tbl_joblist_detail WHERE JOBPARENT = '$JOBCODEID' AND ISLAST = '1'";
				$q_01 			= $this->db->count_all($s_01);*/
				if($ISLASTH == 1)
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
											"<div style='white-space:nowrap'>".$JobView."</div>",
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
				$ISLASTH		= $dataI['ISLASTH'];
				$ISLAST			= $dataI['ISLAST'];

				// IS LAST SETT
					$JOBDESC1 	= wordwrap($JOBDESC, 40, "<br>", true);
					$STATCOL	= 'primary';
					$CELL_COL	= "style='font-weight:bold; white-space:nowrap'";

				/*$s_01 		= "tbl_joblist_detail WHERE JOBPARENT = '$JOBCODEID' AND ISLAST = '1'";
				$q_01 			= $this->db->count_all($s_01);*/
				if($ISLASTH == 1)
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
											"<div style='white-space:nowrap'>".$JobView."</div>",
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
			redirect('__l1y');
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

	function delComp()
	{
		date_default_timezone_set("Asia/Jakarta");

		$collID 	= $_POST['collID'];
		$colExpl	= explode("~", $collID);
        $PRJCODE 	= $colExpl[1];
        $JOB_NUM	= $colExpl[2];
        $JOBPARENT	= $colExpl[3];
        $ITM_CODE	= $colExpl[4];
        $ITM_NAME	= $colExpl[5];
        $JOBD_ID	= $colExpl[6];

        $sqlDel		= "DELETE FROM tbl_jobcreate_detail
        				WHERE JOBD_ID = '$JOBD_ID' AND JOB_NUM = '$JOB_NUM' AND ITM_CODE = '$ITM_CODE'
        					AND JOBPARENT = '$JOBPARENT'AND PRJCODE = '$PRJCODE'";
        $this->db->query($sqlDel);

        $LangID 	= $this->session->userdata['LangID'];
		if($LangID == 'IND')
		{
			$alert1	= "Komponen $ITM_NAME telah dihapus.";
		}
		else
		{
			$alert1	= "Component $ITM_NAME has been deleted.";
		}
		echo "$alert1";
	}

	function lockComp()
	{
		date_default_timezone_set("Asia/Jakarta");

		$collID 	= $_POST['collID'];
		$colExpl	= explode("~", $collID);
        $PRJCODE 	= $colExpl[1];
        $JOB_NUM	= $colExpl[2];
        $JOBPARENT	= $colExpl[3];
        $ITM_CODE	= $colExpl[4];
        $ITM_NAME	= $colExpl[5];

		$PRJ_HO 	= "";
		$s_00 		= "SELECT PRJCODE_HO FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
		$r_00 		= $this->db->query($s_00)->result();
		foreach($r_00 as $rw_00) :
			$PRJ_HO = $rw_00->PRJCODE_HO;
		endforeach;

		// START : INSERT PROCEDURE
			// START : HEADER DETIAL
				$ORD_IDL	= 0;		// Last ORD_ID
				$JOBLEV 	= 0;
				$JOBIDP 	= $JOBPARENT;
				$s_02		= "SELECT ORD_ID, IS_LEVEL FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBPARENT' AND PRJCODE = '$PRJCODE'";
				$r_02 		= $this->db->query($s_02)->result();
				foreach($r_02 as $rw_02) :
					$ORD_IDL	= $rw_02->ORD_ID;
					$JOBLEV 	= $rw_02->IS_LEVEL;
				endforeach;
			// END : HEADER DETIAL

			// START : GET MAXIMUM ORD_ID DETAIL BERDASARKAN INDUK PEKERJAAN. KECUALI JIKA HEADER TSB BELUM MEMILIKI TURUNAN
				$s_TCHLD 	= "tbl_joblist_detail WHERE PRJCODE = '$PRJCODE' AND JOBPARENT = '$JOBPARENT'";
				$r_TCHLD 	= $this->db->count_all($s_TCHLD);
				if($r_TCHLD == 0)
				{
					$nNo 	= 0;						// For next ORD_IDDETIL
				}
				else
				{
					$s_03	= "SELECT MAX(ORD_ID) AS ORD_ID, COUNT(ORD_ID) AS TOT_RW FROM tbl_joblist_detail
								WHERE JOBPARENT = '$JOBPARENT' AND PRJCODE = '$PRJCODE'";
					$r_03 	= $this->db->query($s_03)->result();
					foreach($r_03 as $rw_03) :
						$ORD_ID		= $rw_03->ORD_ID;
						$TOT_RW		= $rw_03->TOT_RW;
						if($ORD_ID == '')
							$ORD_ID = 0;
						if($TOT_RW == '')
							$TOT_RW = 0;
					endforeach;
					$ORD_IDL 	= $ORD_ID;
					$nNo 		= $ORD_ID+1;
				}
			// END : GET MAXIMUM ORD_ID DETAIL BERDASARKAN INDUK PEKERJAAN. KECUALI JIKA HEADER TSB BELUM MEMILIKI TURUNAN

			// START : REORDER OR_ID UNTUK SEMUA ORD_ID SETELAH ORD_ID TERAKHIR PADA JOBPARENT TERTENTU
				$s_04B 		= 	"UPDATE tbl_joblist_detail SET ORD_ID = ORD_ID + 1
									WHERE ORD_ID > $ORD_IDL AND PRJCODE = '$PRJCODE'";
				$this->db->query($s_04B);
			// END : REORDER OR_ID JIKA ADA ORD_ID YG SAMA

			// START : INSERT INTO JOBLIST DETAIL
				$nNo 		= $nNo+1;
				$len 		= strlen($nNo);

				if($len==1) $nol="0"; else $nol="";

				$nextNo 	= $nol.$nNo;

				$ORD_IDN 	= $ORD_IDL+1;
				$JOBID_DET 	= $JOBPARENT.".".$nextNo;
				$JOBLEV_DET = $JOBLEV+1;

				$s_ITM		= "SELECT ITM_UNIT, ITM_GROUP, ITM_KOEF, ITM_RAPV, ITM_RAPP, ITM_TOTAL
								FROM tbl_jobcreate_detail WHERE JOB_NUM = '$JOB_NUM' AND ITM_CODE = '$ITM_CODE'
									AND JOBPARENT = '$JOBPARENT'AND PRJCODE = '$PRJCODE'";
				$r_ITM 		= $this->db->query($s_ITM)->result();
				foreach($r_ITM as $rw_ITM) :
					$ITM_UNIT	= $rw_ITM->ITM_UNIT;
					$ITM_GROUP	= $rw_ITM->ITM_GROUP;
					$ITM_RAPV	= $rw_ITM->ITM_RAPV;
					$ITM_RAPP	= $rw_ITM->ITM_RAPP;
					$ITM_TOTAL	= $rw_ITM->ITM_TOTAL;
				endforeach;

				// INSERT INTO JOBDET
					$s_05	= "INSERT INTO tbl_joblist_detail (ORD_ID, JOBCODEDET, JOBCODEID, JOBPARENT, JOBCODEID_P,
									PRJCODE, PRJCODE_HO, ITM_CODE, JOBDESC, ITM_GROUP, GROUP_CATEG, ITM_UNIT, ITM_VOLM,
									ITM_PRICE, ITM_LASTP, PRJPERIOD, PRJPERIOD_P, ITM_BUDG, BOQ_VOLM, BOQ_PRICE, BOQ_JOBCOST, IS_LEVEL, 
									ISLAST, Patt_Number)
								VALUES ($ORD_IDN, '$JOBID_DET', '$JOBID_DET', '$JOBPARENT', '$JOBPARENT',
									'$PRJCODE', '$PRJ_HO', '$ITM_CODE', '$ITM_NAME', '$ITM_GROUP','$ITM_GROUP', '$ITM_UNIT', '$ITM_RAPV', 
									'$ITM_RAPP', '$ITM_RAPP', '$PRJCODE', '$PRJ_HO', '$ITM_TOTAL', '0', '0', '0', '$JOBLEV_DET', 
									'1', '$nNo')";
					$this->db->query($s_05);

				// UPDATE ITEM BUDGET
					$TOTVOLBUDG = 0;
					$TOTAMNBUDG = 1;
					$s_06	= "SELECT SUM(B.ITM_VOLM) AS TOTVOLBUDG, SUM(B.ITM_BUDG) AS TOTAMNBUDG FROM tbl_joblist_detail B
									WHERE B.ITM_CODE = '$ITM_CODE' AND B.PRJCODE = '$PRJCODE'";
					$r_06 	= $this->db->query($s_06)->result();
					foreach($r_06 as $rw_06) :
						$TOTVOLBUDG = $rw_06->TOTVOLBUDG;
						$TOTAMNBUDG = $rw_06->TOTAMNBUDG;
					endforeach;
					$ITM_VOLMBG = $TOTVOLBUDG ?: 0;
					$ITM_VOLMBGP=$ITM_VOLMBG;
					if($ITM_VOLMBG == 0)
						$ITM_VOLMBGP = 1;
					$ITM_AMNBG 	= $TOTAMNBUDG ?: 1;
					//$TOTAMNBUD= $ITM_VOLMBG * $ITM_PRICE;
					$ITM_AVGP 	= round($ITM_AMNBG / $ITM_VOLMBGP, 4);

					$s_07		= "UPDATE tbl_item SET ITM_VOLMBG = $ITM_VOLMBG, ITM_PRICE = $ITM_AVGP, ITM_LASTP = $ITM_AVGP,
										ITM_TOTALP = $ITM_AMNBG, ITM_AVGP = $ITM_AVGP
									WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
					$this->db->query($s_07);
			// END : INSERT INTO JOBLIST DETAIL
		// END : INSERT PROCEDURE

		// UPDATE STATUS ANALISA
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			$compName 		= "";
			$sqlEmp 		= "SELECT CONCAT(First_Name, ' ', Last_Name) AS compName, Log_Act FROM tbl_employee WHERE Emp_ID = '$DefEmp_ID'";
			$resEmp 		= $this->db->query($sqlEmp)->result();
			foreach($resEmp as $row) :
				$compName 	= $row->compName;
				$LogAct 	= $row->Log_Act;
			endforeach;

			$s_08			= 	"UPDATE tbl_jobcreate_detail SET ISLOCK = 1, LOCKER_ID = '$DefEmp_ID', LOCKER_NM = '$compName', JOBCODEID = '$JOBID_DET'
								WHERE JOB_NUM = '$JOB_NUM' AND ITM_CODE = '$ITM_CODE' AND JOBPARENT = '$JOBPARENT'AND PRJCODE = '$PRJCODE'";
			$this->db->query($s_08);


		// UPDATE ALL HEADER IN HIERARKI
			$s_u2a0	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST + $ITM_TOTAL, PRICE = JOBCOST / JOBVOLM
						WHERE JOBCODEID = '$JOBPARENT' AND PRJCODE = '$PRJCODE'";
			$this->db->query($s_u2a0);

			$s_u2b0	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG + $ITM_TOTAL, ITM_PRICE = ITM_BUDG / ITM_VOLM
						WHERE JOBCODEID = '$JOBPARENT' AND PRJCODE = '$PRJCODE'";
			$this->db->query($s_u2b0);

			$s_P2 	= "SELECT JOBPARENT FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBPARENT' LIMIT 1";
			$r_P2 	= $this->db->query($s_P2)->result();
			foreach($r_P2 as $rw_P2) :
				$JP2 	= $rw_P2->JOBPARENT;

				$s_u2a	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST + $ITM_TOTAL, PRICE = JOBCOST / JOBVOLM
							WHERE JOBCODEID = '$JP2' AND PRJCODE = '$PRJCODE'";
				$this->db->query($s_u2a);

				$s_u2b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG + $ITM_TOTAL, ITM_PRICE = ITM_BUDG / ITM_VOLM
							WHERE JOBCODEID = '$JP2' AND PRJCODE = '$PRJCODE'";
				$this->db->query($s_u2b);

				$s_P3 	= "SELECT JOBPARENT, JOBCOST FROM tbl_joblist WHERE JOBCODEID = '$JP2' LIMIT 1";
				$r_P3 	= $this->db->query($s_P3)->result();
				foreach($r_P3 as $rw_P3) :
					$JP3 	= $rw_P3->JOBPARENT;
					$s_u3	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST + $ITM_TOTAL, PRICE = JOBCOST / JOBVOLM
								WHERE JOBCODEID = '$JP3' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_u3);

					$s_u3b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG + $ITM_TOTAL, ITM_PRICE = ITM_BUDG / ITM_VOLM
								WHERE JOBCODEID = '$JP3' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_u3b);

					$s_P4 	= "SELECT JOBPARENT, JOBCOST FROM tbl_joblist WHERE JOBCODEID = '$JP3' LIMIT 1";
					$r_P4 	= $this->db->query($s_P4)->result();
					foreach($r_P4 as $rw_P4) :
						$JP4 	= $rw_P4->JOBPARENT;
						$s_u4	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST + $ITM_TOTAL, PRICE = JOBCOST / JOBVOLM
									WHERE JOBCODEID = '$JP4' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_u4);

						$s_u4b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG + $ITM_TOTAL, ITM_PRICE = ITM_BUDG / ITM_VOLM
									WHERE JOBCODEID = '$JP4' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_u4b);
					
						$s_P5 	= "SELECT JOBPARENT, JOBCOST FROM tbl_joblist WHERE JOBCODEID = '$JP4' LIMIT 1";
						$r_P5 	= $this->db->query($s_P5)->result();
						foreach($r_P5 as $rw_P5) :
							$JP5 	= $rw_P5->JOBPARENT;
							$s_u5	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST + $ITM_TOTAL, PRICE = JOBCOST / JOBVOLM
										WHERE JOBCODEID = '$JP5' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_u5);

							$s_u5b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG + $ITM_TOTAL, ITM_PRICE = ITM_BUDG / ITM_VOLM
										WHERE JOBCODEID = '$JP5' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_u5b);
					
							$s_P6 	= "SELECT JOBPARENT, JOBCOST FROM tbl_joblist WHERE JOBCODEID = '$JP5' LIMIT 1";
							$r_P6 	= $this->db->query($s_P6)->result();
							foreach($r_P6 as $rw_P6) :
								$JP6 	= $rw_P6->JOBPARENT;
								$s_u6	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST + $ITM_TOTAL, PRICE = JOBCOST / JOBVOLM
											WHERE JOBCODEID = '$JP6' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_u6);

								$s_u6b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG + $ITM_TOTAL, ITM_PRICE = ITM_BUDG / ITM_VOLM
											WHERE JOBCODEID = '$JP6' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_u6b);
					
								$s_P7 	= "SELECT JOBPARENT, JOBCOST FROM tbl_joblist WHERE JOBCODEID = '$JP6' LIMIT 1";
								$r_P7 	= $this->db->query($s_P7)->result();
								foreach($r_P7 as $rw_P7) :
									$JP7 	= $rw_P7->JOBPARENT;
									$s_u7	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST + $ITM_TOTAL, PRICE = JOBCOST / JOBVOLM
												WHERE JOBCODEID = '$JP7' AND PRJCODE = '$PRJCODE'";
									$this->db->query($s_u7);

									$s_u7b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG + $ITM_TOTAL, ITM_PRICE = ITM_BUDG / ITM_VOLM
												WHERE JOBCODEID = '$JP7' AND PRJCODE = '$PRJCODE'";
									$this->db->query($s_u7b);

									$s_P8 	= "SELECT JOBPARENT, JOBCOST FROM tbl_joblist WHERE JOBCODEID = '$JP7' LIMIT 1";
									$r_P8 	= $this->db->query($s_P8)->result();
									foreach($r_P8 as $rw_P8) :
										$JP8 	= $rw_P8->JOBPARENT;
										$s_u8	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST + $ITM_TOTAL, PRICE = JOBCOST / JOBVOLM
													WHERE JOBCODEID = '$JP8' AND PRJCODE = '$PRJCODE'";
										$this->db->query($s_u8);

										$s_u8b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG + $ITM_TOTAL, ITM_PRICE = ITM_BUDG / ITM_VOLM
													WHERE JOBCODEID = '$JP8' AND PRJCODE = '$PRJCODE'";
										$this->db->query($s_u8b);

										$s_P9 	= "SELECT JOBPARENT, JOBCOST FROM tbl_joblist WHERE JOBCODEID = '$JP8' LIMIT 1";
										$r_P9 	= $this->db->query($s_P9)->result();
										foreach($r_P9 as $rw_P9) :
											$JP9 	= $rw_P9->JOBPARENT;
											$s_u9	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST + $ITM_TOTAL, PRICE = JOBCOST / JOBVOLM
														WHERE JOBCODEID = '$JP9' AND PRJCODE = '$PRJCODE'";
											$this->db->query($s_u9);

											$s_u9b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG + $ITM_TOTAL, ITM_PRICE = ITM_BUDG / ITM_VOLM
														WHERE JOBCODEID = '$JP9' AND PRJCODE = '$PRJCODE'";
											$this->db->query($s_u9b);

											$s_P10 	= "SELECT JOBPARENT, JOBCOST FROM tbl_joblist WHERE JOBCODEID = '$JP9' LIMIT 1";
											$r_P10 	= $this->db->query($s_P10)->result();
											foreach($r_P10 as $rw_P10) :
												$JP10 	= $rw_P10->JOBPARENT;
												$s_u10	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST + $ITM_TOTAL, PRICE = JOBCOST / JOBVOLM
															WHERE JOBCODEID = '$JP10' AND PRJCODE = '$PRJCODE'";
												$this->db->query($s_u10);

												$s_u10b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG + $ITM_TOTAL, ITM_PRICE = ITM_BUDG / ITM_VOLM
															WHERE JOBCODEID = '$JP10' AND PRJCODE = '$PRJCODE'";
												$this->db->query($s_u10b);
											endforeach;
										endforeach;
									endforeach;
								endforeach;
							endforeach;
						endforeach;
					endforeach;
				endforeach;
			endforeach;

		echo "Analisa RAP sudah kami proses. Silahkan cek di daftar RAP..!";
    }

	function undoComp()
	{
		date_default_timezone_set("Asia/Jakarta");

		$collID 	= $_POST['collID'];
		$colExpl	= explode("~", $collID);
        $PRJCODE 	= $colExpl[1];
        $JOB_NUM	= $colExpl[2];
        $JOBPARENT	= $colExpl[3];
        $ITM_CODE	= $colExpl[4];
        $JOBCODEID	= $colExpl[5];
        $JOBD_ID	= $colExpl[6];

		// START : UPDATE PROCEDURE
			$ITM_BUDG = 0;
			$s_00 	= "SELECT ITM_BUDG FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBCODEID' LIMIT 1";
			$r_00 	= $this->db->query($s_00)->result();
			foreach($r_00 as $rw_00) :
				$ITM_BUDG 	= $rw_00->ITM_BUDG;
			endforeach;
			if($ITM_BUDG == '')
				$ITM_BUDG 	= 0;

			/*$s_01			= 	"UPDATE tbl_jobcreate_detail SET ISLOCK = 0, LOCKER_ID = '', LOCKER_NM = ''
								WHERE JOB_NUM = '$JOB_NUM' AND ITM_CODE = '$ITM_CODE' AND JOBCODEID = '$JOBCODEID'AND PRJCODE = '$PRJCODE'";*/
			$s_01			= 	"UPDATE tbl_jobcreate_detail SET ISLOCK = 0, LOCKER_ID = '', LOCKER_NM = ''
								WHERE JOB_NUM = '$JOB_NUM' AND ITM_CODE = '$ITM_CODE' AND JOBD_ID = '$JOBD_ID'AND PRJCODE = '$PRJCODE'";
			$this->db->query($s_01);

			$s_02			= 	"DELETE FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";
			$this->db->query($s_02);

			// UPDATE ITEM BUDGET
				$TOTVOLBUDG = 0;
				$TOTAMNBUDG = 1;
				$s_03	= "SELECT SUM(B.ITM_VOLM) AS TOTVOLBUDG, SUM(B.ITM_BUDG) AS TOTAMNBUDG FROM tbl_joblist_detail B
								WHERE B.ITM_CODE = '$ITM_CODE' AND B.PRJCODE = '$PRJCODE'";
				$r_03 	= $this->db->query($s_03)->result();
				foreach($r_03 as $rw_03) :
					$TOTVOLBUDG = $rw_03->TOTVOLBUDG;
					$TOTAMNBUDG = $rw_03->TOTAMNBUDG;
				endforeach;
				$ITM_VOLMBG = $TOTVOLBUDG ?: 0;
				$ITM_VOLMBGP=$ITM_VOLMBG;
				if($ITM_VOLMBG == 0)
					$ITM_VOLMBGP = 1;
				$ITM_AMNBG 	= $TOTAMNBUDG ?: 1;
				//$TOTAMNBUD= $ITM_VOLMBG * $ITM_PRICE;
				$ITM_AVGP 	= round($ITM_AMNBG / $ITM_VOLMBGP, 4);

				$s_04		= "UPDATE tbl_item SET ITM_VOLMBG = $ITM_VOLMBG, ITM_PRICE = $ITM_AVGP, ITM_LASTP = $ITM_AVGP,
									ITM_TOTALP = $ITM_AMNBG, ITM_AVGP = $ITM_AVGP
								WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($s_04);
		// END : INSERT INTO JOBLIST DETAIL

		// UPDATE STATUS ANALISA
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			$compName 		= "";
			$sqlEmp 		= "SELECT CONCAT(First_Name, ' ', Last_Name) AS compName, Log_Act FROM tbl_employee WHERE Emp_ID = '$DefEmp_ID'";
			$resEmp 		= $this->db->query($sqlEmp)->result();
			foreach($resEmp as $row) :
				$compName 	= $row->compName;
				$LogAct 	= $row->Log_Act;
			endforeach;


		// UPDATE ALL HEADER IN HIERARKI
			$s_u2a0	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST - $ITM_BUDG, PRICE = JOBCOST / JOBVOLM
						WHERE JOBCODEID = '$JOBPARENT' AND PRJCODE = '$PRJCODE'";
			$this->db->query($s_u2a0);

			$s_u2b0	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG - $ITM_BUDG, ITM_PRICE = ITM_BUDG / ITM_VOLM
						WHERE JOBCODEID = '$JOBPARENT' AND PRJCODE = '$PRJCODE'";
			$this->db->query($s_u2b0);

			$s_P2 	= "SELECT JOBPARENT FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBPARENT' LIMIT 1";
			$r_P2 	= $this->db->query($s_P2)->result();
			foreach($r_P2 as $rw_P2) :
				$JP2 	= $rw_P2->JOBPARENT;

				$s_u2a	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST - $ITM_BUDG, PRICE = JOBCOST / JOBVOLM
							WHERE JOBCODEID = '$JP2' AND PRJCODE = '$PRJCODE'";
				$this->db->query($s_u2a);

				$s_u2b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG - $ITM_BUDG, ITM_PRICE = ITM_BUDG / ITM_VOLM
							WHERE JOBCODEID = '$JP2' AND PRJCODE = '$PRJCODE'";
				$this->db->query($s_u2b);

				$s_P3 	= "SELECT JOBPARENT, JOBCOST FROM tbl_joblist WHERE JOBCODEID = '$JP2' LIMIT 1";
				$r_P3 	= $this->db->query($s_P3)->result();
				foreach($r_P3 as $rw_P3) :
					$JP3 	= $rw_P3->JOBPARENT;
					$s_u3	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST - $ITM_BUDG, PRICE = JOBCOST / JOBVOLM
								WHERE JOBCODEID = '$JP3' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_u3);

					$s_u3b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG - $ITM_BUDG, ITM_PRICE = ITM_BUDG / ITM_VOLM
								WHERE JOBCODEID = '$JP3' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_u3b);

					$s_P4 	= "SELECT JOBPARENT, JOBCOST FROM tbl_joblist WHERE JOBCODEID = '$JP3' LIMIT 1";
					$r_P4 	= $this->db->query($s_P4)->result();
					foreach($r_P4 as $rw_P4) :
						$JP4 	= $rw_P4->JOBPARENT;
						$s_u4	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST - $ITM_BUDG, PRICE = JOBCOST / JOBVOLM
									WHERE JOBCODEID = '$JP4' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_u4);

						$s_u4b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG - $ITM_BUDG, ITM_PRICE = ITM_BUDG / ITM_VOLM
									WHERE JOBCODEID = '$JP4' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_u4b);
					
						$s_P5 	= "SELECT JOBPARENT, JOBCOST FROM tbl_joblist WHERE JOBCODEID = '$JP4' LIMIT 1";
						$r_P5 	= $this->db->query($s_P5)->result();
						foreach($r_P5 as $rw_P5) :
							$JP5 	= $rw_P5->JOBPARENT;
							$s_u5	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST - $ITM_BUDG, PRICE = JOBCOST / JOBVOLM
										WHERE JOBCODEID = '$JP5' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_u5);

							$s_u5b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG - $ITM_BUDG, ITM_PRICE = ITM_BUDG / ITM_VOLM
										WHERE JOBCODEID = '$JP5' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_u5b);
					
							$s_P6 	= "SELECT JOBPARENT, JOBCOST FROM tbl_joblist WHERE JOBCODEID = '$JP5' LIMIT 1";
							$r_P6 	= $this->db->query($s_P6)->result();
							foreach($r_P6 as $rw_P6) :
								$JP6 	= $rw_P6->JOBPARENT;
								$s_u6	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST - $ITM_BUDG, PRICE = JOBCOST / JOBVOLM
											WHERE JOBCODEID = '$JP6' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_u6);

								$s_u6b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG - $ITM_BUDG, ITM_PRICE = ITM_BUDG / ITM_VOLM
											WHERE JOBCODEID = '$JP6' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_u6b);
					
								$s_P7 	= "SELECT JOBPARENT, JOBCOST FROM tbl_joblist WHERE JOBCODEID = '$JP6' LIMIT 1";
								$r_P7 	= $this->db->query($s_P7)->result();
								foreach($r_P7 as $rw_P7) :
									$JP7 	= $rw_P7->JOBPARENT;
									$s_u7	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST - $ITM_BUDG, PRICE = JOBCOST / JOBVOLM
												WHERE JOBCODEID = '$JP7' AND PRJCODE = '$PRJCODE'";
									$this->db->query($s_u7);

									$s_u7b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG - $ITM_BUDG, ITM_PRICE = ITM_BUDG / ITM_VOLM
												WHERE JOBCODEID = '$JP7' AND PRJCODE = '$PRJCODE'";
									$this->db->query($s_u7b);

									$s_P8 	= "SELECT JOBPARENT, JOBCOST FROM tbl_joblist WHERE JOBCODEID = '$JP7' LIMIT 1";
									$r_P8 	= $this->db->query($s_P8)->result();
									foreach($r_P8 as $rw_P8) :
										$JP8 	= $rw_P8->JOBPARENT;
										$s_u8	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST - $ITM_BUDG, PRICE = JOBCOST / JOBVOLM
													WHERE JOBCODEID = '$JP8' AND PRJCODE = '$PRJCODE'";
										$this->db->query($s_u8);

										$s_u8b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG - $ITM_BUDG, ITM_PRICE = ITM_BUDG / ITM_VOLM
													WHERE JOBCODEID = '$JP8' AND PRJCODE = '$PRJCODE'";
										$this->db->query($s_u8b);

										$s_P9 	= "SELECT JOBPARENT, JOBCOST FROM tbl_joblist WHERE JOBCODEID = '$JP8' LIMIT 1";
										$r_P9 	= $this->db->query($s_P9)->result();
										foreach($r_P9 as $rw_P9) :
											$JP9 	= $rw_P9->JOBPARENT;
											$s_u9	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST - $ITM_BUDG, PRICE = JOBCOST / JOBVOLM
														WHERE JOBCODEID = '$JP9' AND PRJCODE = '$PRJCODE'";
											$this->db->query($s_u9);

											$s_u9b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG - $ITM_BUDG, ITM_PRICE = ITM_BUDG / ITM_VOLM
														WHERE JOBCODEID = '$JP9' AND PRJCODE = '$PRJCODE'";
											$this->db->query($s_u9b);

											$s_P10 	= "SELECT JOBPARENT, JOBCOST FROM tbl_joblist WHERE JOBCODEID = '$JP9' LIMIT 1";
											$r_P10 	= $this->db->query($s_P10)->result();
											foreach($r_P10 as $rw_P10) :
												$JP10 	= $rw_P10->JOBPARENT;
												$s_u10	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST - $ITM_BUDG, PRICE = JOBCOST / JOBVOLM
															WHERE JOBCODEID = '$JP10' AND PRJCODE = '$PRJCODE'";
												$this->db->query($s_u10);

												$s_u10b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG - $ITM_BUDG, ITM_PRICE = ITM_BUDG / ITM_VOLM
															WHERE JOBCODEID = '$JP10' AND PRJCODE = '$PRJCODE'";
												$this->db->query($s_u10b);
											endforeach;
										endforeach;
									endforeach;
								endforeach;
							endforeach;
						endforeach;
					endforeach;
				endforeach;
			endforeach;

		echo "Pembatalan komponen RAP sudah kami proses. Silahkan cek perubahannya di daftar RAP..!";
    }

	function addItmTmp()
	{
		date_default_timezone_set("Asia/Jakarta");

		$strItem 		= $_POST['collDt'];
		$arrItem 		= explode("|", $strItem);

		$PRJCODE 		= $arrItem[0];
		$JOBPARENT		= $arrItem[1];
		$JOBPARDESC 	= $arrItem[2];
		$ITM_CODE 		= $arrItem[3];
		$ITM_NAME 		= $arrItem[4];
		$ITM_UNIT 		= $arrItem[5];
		$ITM_GROUP		= $arrItem[6];
		$JOB_NUM		= $arrItem[7];
		$JOB_NOTE		= $arrItem[8];

		// START : PARENT JOB DETAIL
			$JOBDESC 		= "";
			$JOBUNIT 		= "";
			$JOBLEV 		= 0;
			$JOBVOLM 		= 0;
			$JOBPRICE 		= 0;
			$JOBCOST 		= 0;
			$BOQ_VOLM 		= 0;
			$BOQ_PRICE 		= 0;
			$BOQ_JOBCOST	= 0;
			$sqlJlD			= "SELECT JOBDESC, JOBUNIT, JOBLEV, JOBVOLM, PRICE, JOBCOST, BOQ_VOLM, BOQ_PRICE, BOQ_JOBCOST
								FROM tbl_joblist WHERE JOBCODEID = '$JOBPARENT' AND  PRJCODE = '$PRJCODE' LIMIT 1";
			$resJlD			= $this->db->query($sqlJlD)->result();
			foreach($resJlD as $rowJLD) :
			    $JOBDESC 	= $rowJLD->JOBDESC;
			    $JOBUNIT 	= $rowJLD->JOBUNIT;
			    $JOBLEV 	= $rowJLD->JOBLEV;
			    $JOBVOLM 	= $rowJLD->JOBVOLM;
			    $JOBPRICE 	= $rowJLD->PRICE;
			    $JOBCOST 	= $rowJLD->JOBCOST;
			    $BOQ_VOLM 	= $rowJLD->BOQ_VOLM;
			    $BOQ_PRICE 	= $rowJLD->BOQ_PRICE;
			    $BOQ_JOBCOST= $rowJLD->BOQ_JOBCOST;
			endforeach;
		// END : PARENT JOB DETAIL

		// START : SAVE HEADER
			$s_00 			= "tbl_jobcreate_header WHERE JOB_PARCODE = '$JOBPARENT' AND PRJCODE = '$PRJCODE'";
			$r_00 			= $this->db->count_all($s_00);

			if($r_00 == 0)
			{
				$jobAddH 		= array('PRJCODE' 		=> $PRJCODE,
										'JOB_NUM' 		=> $JOB_NUM,
										'JOB_PARCODE' 	=> $JOBPARENT,
										'JOB_PARDESC' 	=> $JOBDESC,
										'JOB_UNIT' 		=> $JOBUNIT,
										'JOB_BOQV' 		=> $BOQ_VOLM,
										'JOB_BOQP' 		=> $BOQ_PRICE,
										'JOB_BOQT' 		=> $BOQ_JOBCOST,
										'JOB_RAPV' 		=> 0,
										'JOB_RAPP' 		=> 0,
										'JOB_RAPT' 		=> 0,
										'JOB_NOTE' 		=> $JOB_NOTE,
										'JOB_STAT' 		=> 1);
				$this->m_joblist->addRAP($jobAddH);
			}
		// END : SAVE HEADER

		// START : SAVE DETAIL
			$s_02	= "INSERT INTO tbl_jobcreate_detail (PRJCODE, JOB_NUM, JOBCODEID, JOBPARENT, ITM_CODE, ITM_NAME, ITM_UNIT, ITM_GROUP, ITM_BOQV, ITM_BOQP,
							ITM_KOEF, ITM_RAPV, ITM_RAPP, ITM_TOTAL, ITM_NOTES, ISLOCK)
						VALUES ('$PRJCODE', '$JOB_NUM', '', '$JOBPARENT', '$ITM_CODE', '$ITM_NAME', '$ITM_UNIT', '$ITM_GROUP', '$BOQ_VOLM', '$BOQ_PRICE',
							0, 0, 0, 0, '', 0)";
			$this->db->query($s_02);
		// END : SAVE DETAIL

		$s_COUNT	= "tbl_jobcreate_detail WHERE JOB_NUM = '$JOB_NUM' AND PRJCODE =  '$PRJCODE'";
		$r_COUNT 	= $this->db->count_all($s_COUNT);
		echo $r_COUNT;
	}

	function chgVOLKOEF()
	{
		$strItem 		= $_POST['collID'];
		$arrItem 		= explode("~", $strItem);

		$PRJCODE 		= $arrItem[0];
		$JOB_NUM		= $arrItem[1];
		$ITM_CODE 		= $arrItem[2];
		$ITM_RAPV 		= $arrItem[3];
		$ITM_KOEF 		= $arrItem[4];
		$ITM_RAPP 		= $arrItem[5];
		$ITM_TOTAL 		= $arrItem[6];
		$JOBPARENT 		= $arrItem[7];

		$s_00 		= "UPDATE tbl_jobcreate_detail SET ITM_KOEF = $ITM_KOEF, ITM_RAPV = $ITM_RAPV, ITM_RAPP = $ITM_RAPP, ITM_TOTAL = $ITM_TOTAL
						WHERE ITM_CODE = '$ITM_CODE' AND JOBPARENT = '$JOBPARENT' AND PRJCODE = '$PRJCODE'";
		$this->db->query($s_00);

		/*$s_ITM		= "SELECT SUM(ITM_RAPV) AS TOT_VOLM, SUM(ITM_TOTAL) AS TOT_JOBCOST
						FROM tbl_jobcreate_detail WHERE JOB_NUM = '$JOB_NUM' AND JOBPARENT = '$JOBPARENT'AND PRJCODE = '$PRJCODE'";*/
		// TIDAK PERLU MENGGUNAKAN FILTERISASI JOB_NUM, KARENA PASTI HANYA ADA 1 DOKUMEN INDUK
		$s_ITM		= "SELECT SUM(ITM_RAPV) AS TOT_VOLM, SUM(ITM_TOTAL) AS TOT_JOBCOST
						FROM tbl_jobcreate_detail WHERE JOBPARENT = '$JOBPARENT'AND PRJCODE = '$PRJCODE'";
		$r_ITM 		= $this->db->query($s_ITM)->result();
		foreach($r_ITM as $rw_ITM) :
			$TOT_VOLM	= $rw_ITM->TOT_VOLM;
			$TOT_JOBC	= $rw_ITM->TOT_JOBCOST;

			if($TOT_VOLM == 0 || $TOT_VOLM == '')
				$TOT_VOLM 	= 1;

			$ITM_RAPP 	= $TOT_JOBC / $TOT_VOLM;
			/*$s_updH 	= "UPDATE tbl_jobcreate_header SET JOB_RAPV = $TOT_VOLM, JOB_RAPP = $ITM_RAPP, JOB_RAPT = $TOT_JOBC
							WHERE JOB_NUM = '$JOB_NUM' AND JOB_PARCODE = '$JOBPARENT'AND PRJCODE = '$PRJCODE'";*/
			$s_updH 	= "UPDATE tbl_jobcreate_header SET JOB_RAPV = $TOT_VOLM, JOB_RAPP = $ITM_RAPP, JOB_RAPT = $TOT_JOBC
							WHERE JOB_PARCODE = '$JOBPARENT'AND PRJCODE = '$PRJCODE'";
			$this->db->query($s_updH);
		endforeach;
		echo $s_00;
	}

	function SyncVal()
	{
		$strItem 		= $_POST['collID'];
		$arrItem 		= explode("~", $strItem);

		$PRJCODE 		= $arrItem[0];
		$JOB_NUM		= $arrItem[1];
		$JOBPARENT 		= $arrItem[2];

		$s_ITM		= "SELECT SUM(ITM_RAPV) AS TOT_VOLM, SUM(ITM_TOTAL) AS TOT_JOBCOST
						FROM tbl_jobcreate_detail WHERE JOB_NUM = '$JOB_NUM' AND JOBPARENT = '$JOBPARENT'AND PRJCODE = '$PRJCODE'";
		$r_ITM 		= $this->db->query($s_ITM)->result();
		foreach($r_ITM as $rw_ITM) :
			$TOT_VOLM	= $rw_ITM->TOT_VOLM;
			$TOT_JOBC	= $rw_ITM->TOT_JOBCOST;

			if($TOT_VOLM == 0 || $TOT_VOLM == '')
				$TOT_VOLM 	= 1;

			$ITM_RAPP 	= $TOT_JOBC / $TOT_VOLM;
			$s_updH 	= "UPDATE tbl_jobcreate_header SET JOB_RAPV = $TOT_VOLM, JOB_RAPP = $ITM_RAPP, JOB_RAPT = $TOT_JOBC
							WHERE JOB_NUM = '$JOB_NUM' AND JOB_PARCODE = '$JOBPARENT'AND PRJCODE = '$PRJCODE'";
			$this->db->query($s_updH);

			
			// START : UPDATE ALL HEADER IN HIERARKI
				$s_u2a0	= "UPDATE tbl_joblist SET JOBCOST = $TOT_JOBC, PRICE = JOBCOST / JOBVOLM
							WHERE JOBCODEID = '$JOBPARENT' AND PRJCODE = '$PRJCODE'";
				$this->db->query($s_u2a0);

				$s_u2b0	= "UPDATE tbl_joblist_detail SET ITM_BUDG = $TOT_JOBC, ITM_PRICE = ITM_BUDG / ITM_VOLM
							WHERE JOBCODEID = '$JOBPARENT' AND PRJCODE = '$PRJCODE'";
				$this->db->query($s_u2b0);

				$s_P2 	= "SELECT JOBPARENT FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBPARENT' LIMIT 1";
				$r_P2 	= $this->db->query($s_P2)->result();
				foreach($r_P2 as $rw_P2) :
					$JP2 	= $rw_P2->JOBPARENT;
					$TCOST2 = 0;
					$s_TJP2	= "SELECT SUM(ITM_BUDG) AS TOT_BUDG FROM tbl_joblist_detail
								WHERE JOBPARENT = '$JP2'AND PRJCODE = '$PRJCODE'";
					$r_TJP2 = $this->db->query($s_TJP2)->result();
					foreach($r_TJP2 as $rw_TJP2) :
						$TCOST2	= $rw_TJP2->TOT_BUDG;
					endforeach;
					if($TCOST2 == '') $TCOST2 = 0;

					$s_u2a	= "UPDATE tbl_joblist SET JOBCOST = $TCOST2, PRICE = JOBCOST / JOBVOLM
								WHERE JOBCODEID = '$JP2' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_u2a);

					$s_u2b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = $TCOST2, ITM_PRICE = ITM_BUDG / ITM_VOLM
								WHERE JOBCODEID = '$JP2' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_u2b);

					$s_P3 	= "SELECT JOBPARENT, JOBCOST FROM tbl_joblist WHERE JOBCODEID = '$JP2' LIMIT 1";
					$r_P3 	= $this->db->query($s_P3)->result();
					foreach($r_P3 as $rw_P3) :
						$JP3 	= $rw_P3->JOBPARENT;
						$TCOST3 = 0;
						$s_TJP3	= "SELECT SUM(ITM_BUDG) AS TOT_BUDG FROM tbl_joblist_detail
									WHERE JOBPARENT = '$JP3'AND PRJCODE = '$PRJCODE'";
						$r_TJP3 = $this->db->query($s_TJP3)->result();
						foreach($r_TJP3 as $rw_TJP3) :
							$TCOST3	= $rw_TJP3->TOT_BUDG;
						endforeach;
						if($TCOST3 == '') $TCOST3 = 0;

						$s_u3a	= "UPDATE tbl_joblist SET JOBCOST = $TCOST3, PRICE = JOBCOST / JOBVOLM
									WHERE JOBCODEID = '$JP3' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_u3a);

						$s_u3b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = $TCOST3, ITM_PRICE = ITM_BUDG / ITM_VOLM
									WHERE JOBCODEID = '$JP3' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_u3b);

						$s_P4 	= "SELECT JOBPARENT, JOBCOST FROM tbl_joblist WHERE JOBCODEID = '$JP3' LIMIT 1";
						$r_P4 	= $this->db->query($s_P4)->result();
						foreach($r_P4 as $rw_P4) :
							$JP4 	= $rw_P4->JOBPARENT;
							$TCOST4 = 0;
							$s_TJP4	= "SELECT SUM(ITM_BUDG) AS TOT_BUDG FROM tbl_joblist_detail
										WHERE JOBPARENT = '$JP4'AND PRJCODE = '$PRJCODE'";
							$r_TJP4 = $this->db->query($s_TJP4)->result();
							foreach($r_TJP4 as $rw_TJP4) :
								$TCOST4	= $rw_TJP4->TOT_BUDG;
							endforeach;
							if($TCOST4 == '') $TCOST4 = 0;

							$s_u4a	= "UPDATE tbl_joblist SET JOBCOST = $TCOST4, PRICE = JOBCOST / JOBVOLM
										WHERE JOBCODEID = '$JP4' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_u4a);

							$s_u4b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = $TCOST4, ITM_PRICE = ITM_BUDG / ITM_VOLM
										WHERE JOBCODEID = '$JP4' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_u4b);
						
							$s_P5 	= "SELECT JOBPARENT, JOBCOST FROM tbl_joblist WHERE JOBCODEID = '$JP4' LIMIT 1";
							$r_P5 	= $this->db->query($s_P5)->result();
							foreach($r_P5 as $rw_P5) :
								$JP5 	= $rw_P5->JOBPARENT;
								$TCOST5 = 0;
								$s_TJP5	= "SELECT SUM(ITM_BUDG) AS TOT_BUDG FROM tbl_joblist_detail
											WHERE JOBPARENT = '$JP5'AND PRJCODE = '$PRJCODE'";
								$r_TJP5 = $this->db->query($s_TJP5)->result();
								foreach($r_TJP5 as $rw_TJP5) :
									$TCOST5	= $rw_TJP5->TOT_BUDG;
								endforeach;
								if($TCOST5 == '') $TCOST5 = 0;

								$s_u5a	= "UPDATE tbl_joblist SET JOBCOST = $TCOST5, PRICE = JOBCOST / JOBVOLM
											WHERE JOBCODEID = '$JP5' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_u5a);

								$s_u5b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = $TCOST5, ITM_PRICE = ITM_BUDG / ITM_VOLM
											WHERE JOBCODEID = '$JP5' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_u5b);
						
								$s_P6 	= "SELECT JOBPARENT, JOBCOST FROM tbl_joblist WHERE JOBCODEID = '$JP5' LIMIT 1";
								$r_P6 	= $this->db->query($s_P6)->result();
								foreach($r_P6 as $rw_P6) :
									$JP6 	= $rw_P6->JOBPARENT;
									$TCOST6 = 0;
									$s_TJP6	= "SELECT SUM(ITM_BUDG) AS TOT_BUDG FROM tbl_joblist_detail
												WHERE JOBPARENT = '$JP6'AND PRJCODE = '$PRJCODE'";
									$r_TJP6 = $this->db->query($s_TJP6)->result();
									foreach($r_TJP6 as $rw_TJP6) :
										$TCOST6	= $rw_TJP6->TOT_BUDG;
									endforeach;
									if($TCOST6 == '') $TCOST6 = 0;

									$s_u6a	= "UPDATE tbl_joblist SET JOBCOST = $TCOST6, PRICE = JOBCOST / JOBVOLM
												WHERE JOBCODEID = '$JP6' AND PRJCODE = '$PRJCODE'";
									$this->db->query($s_u6a);

									$s_u6b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = $TCOST6, ITM_PRICE = ITM_BUDG / ITM_VOLM
												WHERE JOBCODEID = '$JP6' AND PRJCODE = '$PRJCODE'";
									$this->db->query($s_u6b);
						
									$s_P7 	= "SELECT JOBPARENT, JOBCOST FROM tbl_joblist WHERE JOBCODEID = '$JP6' LIMIT 1";
									$r_P7 	= $this->db->query($s_P7)->result();
									foreach($r_P7 as $rw_P7) :
										$JP7 	= $rw_P7->JOBPARENT;
										$TCOST7 = 0;
										$s_TJP7	= "SELECT SUM(ITM_BUDG) AS TOT_BUDG FROM tbl_joblist_detail
													WHERE JOBPARENT = '$JP7'AND PRJCODE = '$PRJCODE'";
										$r_TJP7 = $this->db->query($s_TJP7)->result();
										foreach($r_TJP7 as $rw_TJP7) :
											$TCOST7	= $rw_TJP7->TOT_BUDG;
										endforeach;
										if($TCOST7 == '') $TCOST7 = 0;

										$s_u7a	= "UPDATE tbl_joblist SET JOBCOST = $TCOST7, PRICE = JOBCOST / JOBVOLM
													WHERE JOBCODEID = '$JP7' AND PRJCODE = '$PRJCODE'";
										$this->db->query($s_u7a);

										$s_u7b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = $TCOST7, ITM_PRICE = ITM_BUDG / ITM_VOLM
													WHERE JOBCODEID = '$JP7' AND PRJCODE = '$PRJCODE'";
										$this->db->query($s_u7b);

										$s_P8 	= "SELECT JOBPARENT, JOBCOST FROM tbl_joblist WHERE JOBCODEID = '$JP7' LIMIT 1";
										$r_P8 	= $this->db->query($s_P8)->result();
										foreach($r_P8 as $rw_P8) :
											$JP8 	= $rw_P8->JOBPARENT;
											$TCOST8 = 0;
											$s_TJP8	= "SELECT SUM(ITM_BUDG) AS TOT_BUDG FROM tbl_joblist_detail
														WHERE JOBPARENT = '$JP8'AND PRJCODE = '$PRJCODE'";
											$r_TJP8 = $this->db->query($s_TJP8)->result();
											foreach($r_TJP8 as $rw_TJP8) :
												$TCOST8	= $rw_TJP8->TOT_BUDG;
											endforeach;
											if($TCOST8 == '') $TCOST8 = 0;

											$s_u8a	= "UPDATE tbl_joblist SET JOBCOST = $TCOST8, PRICE = JOBCOST / JOBVOLM
														WHERE JOBCODEID = '$JP8' AND PRJCODE = '$PRJCODE'";
											$this->db->query($s_u8a);

											$s_u8b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = $TCOST8, ITM_PRICE = ITM_BUDG / ITM_VOLM
														WHERE JOBCODEID = '$JP8' AND PRJCODE = '$PRJCODE'";
											$this->db->query($s_u8b);

											$s_P9 	= "SELECT JOBPARENT, JOBCOST FROM tbl_joblist WHERE JOBCODEID = '$JP8' LIMIT 1";
											$r_P9 	= $this->db->query($s_P9)->result();
											foreach($r_P9 as $rw_P9) :
												$JP9 	= $rw_P9->JOBPARENT;
												$TCOST9 = 0;
												$s_TJP9	= "SELECT SUM(ITM_BUDG) AS TOT_BUDG FROM tbl_joblist_detail
															WHERE JOBPARENT = '$JP9'AND PRJCODE = '$PRJCODE'";
												$r_TJP9 = $this->db->query($s_TJP9)->result();
												foreach($r_TJP9 as $rw_TJP9) :
													$TCOST9	= $rw_TJP9->TOT_BUDG;
												endforeach;
												if($TCOST9 == '') $TCOST9 = 0;

												$s_u9a	= "UPDATE tbl_joblist SET JOBCOST = $TCOST9, PRICE = JOBCOST / JOBVOLM
															WHERE JOBCODEID = '$JP9' AND PRJCODE = '$PRJCODE'";
												$this->db->query($s_u9a);

												$s_u9b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = $TCOST9, ITM_PRICE = ITM_BUDG / ITM_VOLM
															WHERE JOBCODEID = '$JP9' AND PRJCODE = '$PRJCODE'";
												$this->db->query($s_u9b);

												$s_P10 	= "SELECT JOBPARENT, JOBCOST FROM tbl_joblist WHERE JOBCODEID = '$JP9' LIMIT 1";
												$r_P10 	= $this->db->query($s_P10)->result();
												foreach($r_P10 as $rw_P10) :
													$JP10 	= $rw_P10->JOBPARENT;
													$TCOST10 = 0;
													$s_TJP10	= "SELECT SUM(ITM_BUDG) AS TOT_BUDG FROM tbl_joblist_detail
																WHERE JOBPARENT = '$JP10'AND PRJCODE = '$PRJCODE'";
													$r_TJP10 = $this->db->query($s_TJP10)->result();
													foreach($r_TJP10 as $rw_TJP10) :
														$TCOST10	= $rw_TJP10->TOT_BUDG;
													endforeach;
													if($TCOST10 == '') $TCOST10 = 0;

													$s_u10a	= "UPDATE tbl_joblist SET JOBCOST = $TCOST10, PRICE = JOBCOST / JOBVOLM
																WHERE JOBCODEID = '$JP10' AND PRJCODE = '$PRJCODE'";
													$this->db->query($s_u10a);

													$s_u10b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = $TCOST10, ITM_PRICE = ITM_BUDG / ITM_VOLM
																WHERE JOBCODEID = '$JP10' AND PRJCODE = '$PRJCODE'";
													$this->db->query($s_u10b);
												endforeach;
											endforeach;
										endforeach;
									endforeach;
								endforeach;
							endforeach;
						endforeach;
					endforeach;
				endforeach;
			// END : UPDATE ALL HEADER IN HIERARKI
			
			$TOT_V 	= $TOT_VOLM;
			$TOT_C 	= $TOT_JOBC;
			if($TOT_V == '' || $TOT_V == 0)
				$AVG_P 	= $TOT_C / 1;
			else
				$AVG_P 	= $TOT_C / $TOT_V;

			echo "$TOT_V~$AVG_P~$TOT_C";
		endforeach;
	}

	function delJL()
	{
		$strItem 		= $_POST['collID'];
		$arrItem 		= explode("~", $strItem);

		$PRJCODE		= $arrItem[1];
		$JOBCODEID 		= $arrItem[2];

		$s_L01			= "SELECT ORD_ID FROM tbl_boqlist WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND PRJCODE = '$PRJCODE'";
		$r_L01 			= $this->db->query($s_L01)->result();
		foreach($r_L01 as $rw_L01) :
			$ORD_ID01	= $rw_L01->ORD_ID;
		endforeach;

		$s_L02			= "SELECT ORD_ID FROM tbl_joblist WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND PRJCODE = '$PRJCODE'";
		$r_L02 			= $this->db->query($s_L02)->result();
		foreach($r_L02 as $rw_L02) :
			$ORD_ID02	= $rw_L02->ORD_ID;
		endforeach;

		$s_L03			= "SELECT ORD_ID FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND PRJCODE = '$PRJCODE'";
		$r_L03 			= $this->db->query($s_L03)->result();
		foreach($r_L03 as $rw_L03) :
			$ORD_ID03	= $rw_L03->ORD_ID;
		endforeach;

		// UPDATE
			$s_upd00 	= "UPDATE tbl_boqlist SET ORD_ID = ORD_ID + 1 WHERE ORD_ID > $ORD_ID01 AND PRJCODE = '$PRJCODE'";
			$this->db->query($s_upd00);

			$s_upd01 	= "UPDATE tbl_joblist SET ORD_ID = ORD_ID-1 WHERE ORD_ID > $ORD_ID02 AND PRJCODE = '$PRJCODE'";
			$this->db->query($s_upd01);
			
			$s_upd02 	= "UPDATE tbl_joblist_detail SET ORD_ID = ORD_ID-1 WHERE ORD_ID > $ORD_ID03 AND PRJCODE = '$PRJCODE'";
			$this->db->query($s_upd02);

		// DELETE
			$s_del00 		= "DELETE FROM tbl_boqlist WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";
			$this->db->query($s_del00);

			$s_del01 		= "DELETE FROM tbl_joblist WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";
			$this->db->query($s_del01);

			$s_del02 		= "DELETE FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";
			$this->db->query($s_del02);

			echo "$s_L01";
	}
	
	function get_AllDataRAPDETC() // G
	{
		$collData		= $_GET['id'];
		$arrCDATA 		= explode("~", $collData);
		$PRJCODE 		= $arrCDATA[0];
		$JOBPARENT 		= $arrCDATA[1];

		$sqlDET			= "tbl_jobcreate_detail WHERE JOBPARENT = '$JOBPARENT' AND PRJCODE =  '$PRJCODE'";
		$resDET 		= $this->db->count_all($sqlDET);
		echo $resDET;
	}
}