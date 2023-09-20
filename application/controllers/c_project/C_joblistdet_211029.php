<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 24 Maret 2017
 * File Name	= C_joblistdet.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_joblistdet extends CI_Controller  
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
		
		$url			= site_url('c_project/c_joblistdet/prj180c21l/?id='.$this->url_encryption_helper->encode_url($appName));
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
			
			$LangID 	= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN272';
				$data["MenuApp"] 	= 'MN272';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN272';	 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_project/c_joblistdet/gl180c21JL/?id=";
			
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
		$this->load->model('m_project/m_joblistdet/m_joblistdet', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{	
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;

			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			
			$LangID 	= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN272';
				$data["MenuCode"] 	= 'MN272';
				$data["MenuApp"] 	= 'MN272';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$data['title'] 		= $appName;
			$data['h2_title'] 	= 'BoQ List';
			$data['h3_title'] 	= 'project';
			$data['secAddURL'] 	= site_url('c_project/c_joblistdet/add/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$cancel				= site_url('c_project/c_joblistdet/prj180c21l/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$data['backURL'] 	= $cancel;
			$num_rows 			= $this->m_joblistdet->count_all_schedule($PRJCODE);
			$data['countjobl'] 	= $num_rows;
			$data['vwjoblist'] 	= $this->m_joblistdet->get_all_joblist($PRJCODE)->result();
			
			$getprojname 		= $this->m_joblistdet->get_project_name($PRJCODE)->row();			
			$data['PRJNAME'] 	= $getprojname->PRJNAME;
			$data['PRJCODE'] 	= $PRJCODE;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN272';
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
			
			//$this->load->view('v_project/v_joblistdet/joblistdet', $data);
			$this->load->view('v_project/v_joblistdet/v_joblistdet', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function add() // OK
	{
		$this->load->model('m_project/m_joblistdet/m_joblistdet', '', TRUE);
		
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
			
			$data['form_action']	= site_url('c_project/c_joblistdet/add_process');
			$cancel					= site_url('c_project/c_joblistdet/get_all_joblist/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
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
				$MenuCode 		= 'MN272';
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
			
			$this->load->view('v_project/v_joblistdet/joblistdet_form', $data);
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
		$this->load->model('m_project/m_joblistdet/m_joblistdet', '', TRUE);
		$countJOBCODE 	= $this->m_joblistdet->count_all_JOB($PRJCODE, $JOB_CODE);
		echo $countJOBCODE;
	}
	
	function add_process() // OK
	{
		$this->load->model('m_project/m_joblistdet/m_joblistdet', '', TRUE);
			
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
							'JOBDESC'		=> addslashes($this->input->post('JOBDESC')),
							'JOBGRP'		=> $this->input->post('ITM_GROUP'),
							'JOBUNIT'		=> addslashes($this->input->post('JOBUNIT')),
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
							'JOBDESC'		=> addslashes($this->input->post('JOBDESC')),
							'JOBGRP'		=> $this->input->post('ITM_GROUP'),
							'JOBUNIT'		=> addslashes($this->input->post('JOBUNIT')),
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
							'JOBDESC'		=> addslashes($this->input->post('JOBDESC')),
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
				$MenuCode 		= 'MN272';
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
			
			$url			= site_url('c_project/c_joblistdet/gl180c21JL/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function upd1d0ebb() // OK
	{
		$this->load->model('m_project/m_joblistdet/m_joblistdet', '', TRUE);
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
			$data['form_action']	= site_url('c_project/c_joblistdet/update_process');			
			
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
			
			$cancel					= site_url('c_project/c_joblistdet/get_all_joblist/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
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
				$MenuCode 		= 'MN272';
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
			
			$this->load->view('v_project/v_joblistdet/joblistdet_form_upt', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process() // 
	{
		$this->load->model('m_project/m_joblistdet/m_joblistdet', '', TRUE);
		
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
							'JOBDESC'		=> addslashes($this->input->post('JOBDESC')),
							'JOBCLASS'		=> $this->input->post('JOBCLASS'),
							'JOBGRP'		=> $this->input->post('JOBGRP'),
							'JOBTYPE'		=> $this->input->post('JOBTYPE'),
							'JOBUNIT'		=> addslashes($this->input->post('JOBUNIT')),
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
				$MenuCode 		= 'MN272';
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
			
			$url			= site_url('c_project/c_joblistdet/get_all_joblist/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
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
	
    function sH0wJD() 
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;
			
		$collData	= $_GET['id'];
		$collData	= $this->url_encryption_helper->decode_url($collData);
		$data1		= explode("~", $collData);
		$PRJCODE	= $data1[0];
		$JOBCODEID	= $data1[1];
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$LangID 				= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['title'] 		= $appName;
				$data['h1_title'] 	= 'Detil Penggunaan Anggaran';
			}
			else
			{
				$data['title'] 		= $appName;
				$data['h1_title'] 	= 'Use Budget Detail';
			}
			
			$TOTPROJ				= 1;
		
			$data['PRJCODE'] 		= $PRJCODE;
			$data['JOBCODEID'] 		= $JOBCODEID;
			
			$this->load->view('v_project/v_joblistdet/joblistdet_vd', $data);
		}
		else
		{
			redirect('__l1y');
		}
    }

    function syncJLD()
    {
    	$PRJCODE 	= $_POST['PRJCODE'];
    	$sqlJLD 	= "SELECT JOBCODEID, PRJCODE, ITM_CODE, ITM_GROUP, ITM_VOLM, ITM_PRICE, ITM_LASTP, ITM_AVGP, REQ_VOLM, REQ_AMOUNT, PO_VOLM, PO_AMOUNT,
    						IR_VOLM, IR_AMOUNT, WO_QTY, WO_AMOUNT, OPN_QTY, OPN_AMOUNT, ITM_USED, ITM_USED_AM, ITM_STOCK, ITM_STOCK_AM
    					FROM tbl_joblist_detail
						WHERE PRJCODE = '$PRJCODE' AND ISLAST = 1";
		$resJLD		= $this->db->query($sqlJLD)->result();
		foreach($resJLD as $row):
			$JOBCODEID		= $row->JOBCODEID;
			$PRJCODE		= $row->PRJCODE;
			$ITM_CODE		= $row->ITM_CODE;
			$ITM_GROUP		= $row->ITM_GROUP;
			$ITM_VOLM		= $row->ITM_VOLM;
			$ITM_PRICE		= $row->ITM_PRICE;
			$ITM_LASTP		= $row->ITM_LASTP;
			$ITM_AVGP		= $row->ITM_AVGP;
			$REQ_VOLM		= $row->REQ_VOLM;
			$REQ_AMOUNT		= $row->REQ_AMOUNT;
			$PO_VOLM		= $row->PO_VOLM;
			$PO_AMOUNT		= $row->PO_AMOUNT;
			$IR_VOLM		= $row->IR_VOLM;
			$IR_AMOUNT		= $row->IR_AMOUNT;
			$WO_QTY			= $row->WO_QTY;
			$WO_AMOUNT		= $row->WO_AMOUNT;
			$OPN_QTY		= $row->OPN_QTY;
			$OPN_AMOUNT		= $row->OPN_AMOUNT;
			$ITM_USED		= $row->ITM_USED;
			$ITM_USED_AM	= $row->ITM_USED_AM;
			$ITM_STOCK		= $row->ITM_STOCK;
			$ITM_STOCK_AM	= $row->ITM_STOCK_AM;

			if($ITM_GROUP == 'M')
			{
				// 1. UPDATE REQUEST
			    	$sqlPR 		= "SELECT SUM(A.PR_VOLM) AS TOT_PRVOL, SUM(A.PR_TOTAL) AS TOT_PRAMN FROM tbl_pr_detail A 
										INNER JOIN tbl_pr_header B ON B.PR_NUM = A.PR_NUM
									WHERE A.JOBCODEID = '$JOBCODEID' AND A.PRJCODE = '$PRJCODE' AND B.PR_STAT IN (3,6)";
					$resPR		= $this->db->query($sqlPR)->result();
					foreach($resPR as $rowPR):
						$TOT_PRVOL	= $rowPR->TOT_PRVOL;
						$TOT_PRAMN	= $rowPR->TOT_PRAMN;
					endforeach;
					if($TOT_PRVOL == '')
					{
						$TOT_PRVOL	= 0;
						$TOT_PRAMN	= 0;
					}

				// 2. UPDATE PO
			    	$sqlPR 		= "SELECT SUM(A.PO_VOLM) AS TOT_POVOL, SUM(A.PO_COST) AS TOT_POAMN FROM tbl_po_detail A 
										INNER JOIN tbl_po_header B ON B.PO_NUM = A.PO_NUM
									WHERE A.JOBCODEID = '$JOBCODEID' AND A.PRJCODE = '$PRJCODE' AND B.PO_STAT IN (3,6)";
					$resPR		= $this->db->query($sqlPR)->result();
					foreach($resPR as $rowPR):
						$TOT_POVOL	= $rowPR->TOT_POVOL;
						$TOT_POAMN	= $rowPR->TOT_POAMN;
					endforeach;
					if($TOT_POVOL == '')
					{
						$TOT_POVOL	= 0;
						$TOT_POAMN	= 0;
					}

				// 3. UPDATE IR
			    	$sqlPR 		= "SELECT SUM(A.ITM_QTY) AS TOT_IRVOL, SUM(A.ITM_TOTAL) AS TOT_IRAMN FROM tbl_ir_detail A 
										INNER JOIN tbl_ir_header B ON B.IR_NUM = A.IR_NUM
									WHERE A.JOBCODEID = '$JOBCODEID' AND A.PRJCODE = '$PRJCODE' AND B.IR_STAT IN (3,6)";
					$resPR		= $this->db->query($sqlPR)->result();
					foreach($resPR as $rowPR):
						$TOT_IRVOL	= $rowPR->TOT_IRVOL;
						$TOT_IRAMN	= $rowPR->TOT_IRAMN;
					endforeach;
					if($TOT_IRVOL == '')
					{
						$TOT_IRVOL	= 0;
						$TOT_IRAMN	= 0;
					}

				// 4. UPDATE PENGGUNAAN
			    	$sqlPR 		= "SELECT SUM(A.ITM_QTY) AS TOT_UMVOL, SUM(A.ITM_TOTAL) AS TOT_UMAMN FROM tbl_um_detail A 
										INNER JOIN tbl_um_header B ON B.UM_NUM = A.UM_NUM
									WHERE A.JOBCODEID = '$JOBCODEID' AND A.PRJCODE = '$PRJCODE' AND B.UM_STAT IN (3,6)";
					$resPR		= $this->db->query($sqlPR)->result();
					foreach($resPR as $rowPR):
						$TOT_UMVOL	= $rowPR->TOT_UMVOL;
						$TOT_UMAMN	= $rowPR->TOT_UMAMN;
					endforeach;
					if($TOT_UMVOL == '')
					{
						$TOT_UMVOL	= 0;
						$TOT_UMAMN	= 0;
					}

					$ITM_STOCK 		= $TOT_IRVOL - $TOT_UMVOL;
					$ITM_STOCK_AM 	= $TOT_IRAMN - $TOT_UMAMN;

				// 5. UPDATE JOBLIST
					$sqlUPDJL	= "UPDATE tbl_joblist_detail SET REQ_VOLM = $TOT_PRVOL, REQ_AMOUNT = $TOT_PRAMN,
																 PO_VOLM = $TOT_POVOL, PO_AMOUNT = $TOT_POAMN,
																 IR_VOLM = $TOT_IRVOL, IR_AMOUNT = $TOT_IRAMN,
																 ITM_USED = $TOT_UMVOL, ITM_USED_AM = $TOT_UMAMN,
																 ITM_STOCK = $ITM_STOCK, ITM_STOCK_AM = $ITM_STOCK_AM
									WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";
					$this->db->query($sqlUPDJL);
			}
			else
			{
				// 2. UPDATE PO
			    	$sqlPR 		= "SELECT SUM(A.WO_VOLM) AS TOT_WOVOL, SUM(A.WO_TOTAL) AS TOT_WOAMN FROM tbl_wo_detail A 
										INNER JOIN tbl_wo_header B ON B.WO_NUM = A.WO_NUM
									WHERE A.JOBCODEID = '$JOBCODEID' AND A.PRJCODE = '$PRJCODE' AND B.WO_STAT IN (3,6)";
					$resPR		= $this->db->query($sqlPR)->result();
					foreach($resPR as $rowPR):
						$TOT_POVOL	= $rowPR->TOT_WOVOL;
						$TOT_POAMN	= $rowPR->TOT_WOAMN;
					endforeach;
					if($TOT_POVOL == '')
					{
						$TOT_POVOL	= 0;
						$TOT_POAMN	= 0;
					}

				// 4. UPDATE PENGGUNAAN
			    	$sqlPR 		= "SELECT SUM(A.OPND_VOLM) AS TOT_OPNVOL, SUM(A.OPND_ITMTOTAL) AS TOT_OPNAMN FROM tbl_opn_detail A 
										INNER JOIN tbl_opn_header B ON B.OPNH_NUM = A.OPNH_NUM
									WHERE A.JOBCODEID = '$JOBCODEID' AND A.PRJCODE = '$PRJCODE' AND B.OPNH_STAT IN (3,6)";
					$resPR		= $this->db->query($sqlPR)->result();
					foreach($resPR as $rowPR):
						$TOT_UMVOL	= $rowPR->TOT_OPNVOL;
						$TOT_UMAMN	= $rowPR->TOT_OPNAMN;
					endforeach;
					if($TOT_UMVOL == '')
					{
						$TOT_UMVOL	= 0;
						$TOT_UMAMN	= 0;
					}

					$ITM_STOCK 		= $TOT_POVOL - $TOT_UMVOL;
					$ITM_STOCK_AM 	= $TOT_POAMN - $TOT_UMAMN;

				// 5. UPDATE JOBLIST
					$sqlUPDJL	= "UPDATE tbl_joblist_detail SET REQ_VOLM = $TOT_POVOL, REQ_AMOUNT = $TOT_POAMN,
																 PO_VOLM = $TOT_POVOL, PO_AMOUNT = $TOT_POAMN,
																 ITM_USED = $TOT_UMVOL, ITM_USED_AM = $TOT_UMAMN,
																 ITM_STOCK = $ITM_STOCK, ITM_STOCK_AM = $ITM_STOCK_AM
									WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";
					$this->db->query($sqlUPDJL);
			}
		endforeach;
		$isFinish = 1;
    	echo $isFinish;
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
			$num_rows 		= $this->m_budget->get_AllDataJLC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			
			$query 			= $this->m_budget->get_AllDataJLL($PRJCODE, $search, $length, $start, $order, $dir);
								
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
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 4)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 5)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 6)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 7)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 8)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 9)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 10)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 11)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 12)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

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

				$JOBVOLMV 	= $JOBVOLM;
				$JOBPRICEV 	= $JOBPRICE;
				if($JOBUNIT == 'LUMP')
				{
					$JOBVOLMV 	= 1;
					$ADD_VOLMV 	= 1;
					$ADD_PRICEV = $ADD_VOLM;
					$JOBPRICEV 	= $JOBVOLM;
					if($ISLAST == 0)
					{
						$JOBPRICEV 	= $JOBCOST;
					}
				}
				else
				{
					$ADD_VOLMV 	= $ADD_VOLM;
					$ADD_PRICEV = $ADD_PRICE;
				}

				$ADDVOL_VW 		= "";
				$ADDPRC_VW 		= "";
				if($ISLAST == 1)
				{
					if($ADD_VOLM > 0)
					{
						$ADDVOL_VW 	= 	"<div style='white-space:nowrap;'>
											<p class='text-yellow' style='white-space:nowrap;'><i class='glyphicon glyphicon-triangle-top'></i>
									  		".number_format($ADD_VOLMV, 2)."</p>
									  	</div>";
						$ADDPRC_VW 	= 	"<div style='white-space:nowrap;'>
											<p class='text-yellow' style='white-space:nowrap;'><i class='glyphicon glyphicon-triangle-top'></i>
									  		".number_format($ADD_PRICEV, 2)."</p>
									  	</div>";
					}
					if($ADDM_VOLM > 0)
					{
						$ADDVOL_VW 	= 	"<div style='white-space:nowrap;'>
											<p class='text-yellow' style='white-space:nowrap;'><i class='glyphicon glyphicon-triangle-top'></i>
									  		".number_format($ADDM_VOLM, 2)."</p>
									  	</div>";
					}
				}

				$output['data'][] 	= array("<span ".$CELL_COL.">".$JobView."</span>",
											"<div style='text-align:center;'><span ".$CELL_COL.">".$JOBUNIT."</span></div>",
											"<div style='white-space:nowrap; text-align:right;'><span ".$CELL_COL.">".number_format($JOBVOLMV, 2).$ADDVOL_VW."</span></div>",
											"<div style='white-space:nowrap; text-align:right;'><span ".$CELL_COL.">".number_format($JOBPRICEV, 2).$ADDPRC_VW."</span></div>",
											"<div style='text-align:right; white-space:nowrap'><span class='label label-".$STATCOL."' style='font-size:12px'>".number_format($JOBCOST,2)."</span></div>",
											"<div style='text-align:right; white-space:nowrap'><span class='label label-".$STATCOL."' style='font-size:12px'>".number_format($ADD_JOBCOST,2)."</span></div>",
											"<div style='text-align:right; white-space:nowrap'><span class='label label-".$STATCOL."' style='font-size:12px'>".number_format($ITM_USED_AM,2)."</span></div>",
											"<div style='text-align:right; white-space:nowrap'><span class='label label-".$STATCOL."' style='font-size:12px'>".number_format($REMAIN,2)."</span></div>",
											"<div style='text-align:right; white-space:nowrap'><span class='label label-".$STATCOL."' style='font-size:12px'>".number_format($TOTPRJC_01,2)."</span></div>");

				$noU			= $noU + 1;
			}
			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
}