<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 05 Januari 2018
 * File Name	= C_boqlist.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_boqlist extends CI_Controller  
{
 	public function index() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_project/c_boqlist/projectlist/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function projectlist() // OK
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
				$data["h1_title"] 	= "Bill of Quantity";
			}
			else
			{
				$data["h1_title"] 	= "Bill of Quantity";
			}
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;	 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			// START EDIT
				$data["secURL"] = "c_project/c_boqlist/get_all_joblist/?id=";		
			// END EDIT	
			
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
			redirect('__I1y');
		}
	}

	function get_all_joblist($offset=0) // OK
	{
		$this->load->model('m_project/m_boqlist/m_boqlist', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{	
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$PRJCODE			= $_GET['id'];
			$PRJCODE			= $this->url_encryption_helper->decode_url($PRJCODE);
			
			$data['title'] 		= $appName;
			$data['h2_title'] 	= 'BoQ List';
			$data['h3_title'] 	= 'project';
			$data['secAddURL'] 	= site_url('c_project/c_boqlist/add/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$cancel				= site_url('c_project/c_boqlist/projectlist/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$data['backURL'] 	= $cancel;
			$num_rows 			= $this->m_boqlist->count_all_schedule($PRJCODE);
			$data['countjobl'] 	= $num_rows;
			$data['vwjoblist'] 	= $this->m_boqlist->get_all_joblist($PRJCODE)->result();
			
			$getprojname 		= $this->m_boqlist->get_project_name($PRJCODE)->row();			
			$data['PRJNAME'] 	= $getprojname->PRJNAME;
			$data['PRJCODE'] 	= $PRJCODE;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN291';
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
			
			$this->load->view('v_project/v_boqlist/boqlist', $data);
		}
		else
		{
			redirect('__I1y');
		}
	}
	
	function add() // OK
	{
		$this->load->model('m_project/m_boqlist/m_boqlist', '', TRUE);
		
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
			$data['form_action']	= site_url('c_project/c_boqlist/add_process');
			$cancel					= site_url('c_project/c_boqlist/get_all_joblist/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			//$data['link'] 			= array('link_back' => anchor($cancel,'<input type="button" class="btn btn-danger" id="btnCancel" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			$data['countParent']	= $this->m_boqlist->count_all_job1();		
			$data['vwParent'] 		= $this->m_boqlist->get_all_job1()->result();
			
			$data['backURL'] 		= $cancel;
			$getprojname 			= $this->m_boqlist->get_project_name($PRJCODE)->row();			
			$data['PRJNAME'] 		= $getprojname->PRJNAME;
			$data['PRJCODE'] 		= $PRJCODE;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN291';
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
			
			$this->load->view('v_project/v_boqlist/boqlist_form', $data);
		}
		else
		{
			redirect('__I1y');
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
		$this->load->model('m_project/m_boqlist/m_boqlist', '', TRUE);
		$countJOBCODE 	= $this->m_boqlist->count_all_JOB($PRJCODE, $JOB_CODE);
		echo $countJOBCODE;
	}
	
	function add_process() // OK
	{
		$this->load->model('m_project/m_boqlist/m_boqlist', '', TRUE);
		
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
			$this->m_boqlist->add($joblist);
			
			// UPDATE TOTAL PARENT
			$this->m_boqlist->updateParent($JOBPARENT, $JOBCODEID);
			
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
				$MenuCode 		= 'MN291';
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
			
			$url			= site_url('c_project/c_boqlist/get_all_joblist/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__I1y');
		}
	}
	
	function update() // 
	{
		$this->load->model('m_project/m_boqlist/m_boqlist', '', TRUE);
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
			$data['form_action']	= site_url('c_project/c_boqlist/update_process');			
			
			$getjoblist 			= $this->m_boqlist->get_joblist_by_code($JOBCODEID)->row();
			
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
			
			$cancel					= site_url('c_project/c_boqlist/get_all_joblist/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			$data['countParent']	= $this->m_boqlist->count_all_job1();		
			$data['vwParent'] 		= $this->m_boqlist->get_all_job1()->result();
			
			$data['backURL'] 		= $cancel;
			$getprojname 			= $this->m_boqlist->get_project_name($PRJCODE)->row();			
			$data['PRJNAME'] 		= $getprojname->PRJNAME;
			$data['PRJCODE'] 		= $PRJCODE;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getjoblist->JOBCODEID;
				$MenuCode 		= 'MN291';
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
			
			$this->load->view('v_project/v_boqlist/boqlist_form', $data);
		}
		else
		{
			redirect('__I1y');
		}
	}
	
	function update_process() // 
	{
		$this->load->model('m_project/m_boqlist/m_boqlist', '', TRUE);
		
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
			$this->m_boqlist->update($JOBCODEID, $joblist);
			
			// UPDATE TOTAL PARENT
			$this->m_boqlist->updateParent($JOBPARENT, $JOBCODEID);
			
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
				$MenuCode 		= 'MN291';
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
			
			$url			= site_url('c_project/c_boqlist/get_all_joblist/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__I1y');
		}
	}
}