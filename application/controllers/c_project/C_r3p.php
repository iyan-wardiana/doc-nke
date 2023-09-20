<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 30 Juli 2018
 * File Name	= C_r3p.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_r3p extends CI_Controller  
{
  	function __construct() // GOOD
	{
		parent::__construct();

		$this->load->model('m_updash/m_updash', '', TRUE);
	
		$this->data['UserID'] 		= $this->session->userdata['Emp_ID'];
		$this->data['appName'] 		= $this->session->userdata['appName'];
		$this->data['ISCREATE'] 	= $this->session->userdata['ISCREATE'];
		$this->data['ISAPPROVE'] 	= $this->session->userdata['ISAPPROVE'];
		$this->data['LangID']		= $this->session->userdata['LangID'];
	}

    function index()
	{		
		if ($this->session->userdata('login') == TRUE)
		{
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$url			= site_url('c_project/c_r3p/Am4/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
    }
	
	function Am4() // G
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$data['MenuCode']		= 'MN351';
			
			$LangID 	= $this->session->userdata['LangID'];
				
			// START : GET MENU NAME
				$mnCode				= 'MN351';
				$data["MenuApp"] 	= 'MN351';
				$data["MenuCode"] 	= 'MN351';

				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			// END : GET MENU NAME

			$data['form_action'] 	= site_url('c_project/c_r3p/am1h0db2VwR3p/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_project/v_report/v_amandemen/v_amandemen', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function am1h0db2VwR3p() // G
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$data['title'] 		= $appName;
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['h1_title']	= 'LAPORAN RINGKASAN AMANDEMEN';
				$data['h2_title']	= 'Report';
			}
			else
			{
				$data['h1_title']	= 'BUDGET SUMMARY REPORT';
				$data['h2_title']	= 'Report';
			}
		
			$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
			$data['PRJCODE'] 	= $this->input->post('PRJCODE');
			$PRJCODE			= $this->input->post('PRJCODE');
			$getproject 		= "SELECT A.PRJCODE, A.PRJNAME FROM tbl_project A WHERE A.PRJCODE = '$PRJCODE' ORDER BY A.PRJCODE";
			$qProject 		= $this->db->query($getproject)->result();
			foreach($qProject as $rowPRJ) :
				$data['PRJNAME'] = $rowPRJ->PRJNAME;
			endforeach;
			
			$datePeriod1		= $this->input->post('datePeriod');
			$datePeriod2		= explode(" - ", $datePeriod1);
			$Start_Date			= date('Y-m-d', strtotime($datePeriod2[0]));
			$End_Date			= date('Y-m-d', strtotime($datePeriod2[1]));
			
			$data['Start_Date'] = $Start_Date;
			$data['End_Date'] 	= $End_Date;
			$data['CFType'] 	= $this->input->post('CFType');		// List or Summary or Detail
			$CFType				= $this->input->post('CFType');		// Summary or Detail
			$data['viewType'] 	= $this->input->post('viewType');	// View or Excel
			
			$LangID 	= $this->session->userdata['LangID'];
			if($CFType == 1)
			{
				$data['CFStat'] 		= $this->input->post('CFStat');
				if($LangID == 'IND')
				{
					$data['h1_title']	= 'LAPORAN DAFTAR AMANDEMEN';
				}
				else
				{
					$data['h1_title']	= 'AMENDMENT LIST REPORT';
				}
				$this->load->view('v_project/v_report/v_amandemen/v_amandemen_report', $data);
			}
			else if($CFType == 2)
			{
				if($LangID == 'IND')
				{
					$data['h1_title']	= 'LAPORAN RINGKASAN AMANDEMEN';
				}
				else
				{
					$data['h1_title']	= 'AMENDMENT SUMMARY REPORT';
				}
				$this->load->view('v_project/v_report/v_amandemen/v_amandemen_report_sum', $data);
			}
			else
			{
				$data['ITM_CODE'] 		= $this->input->post('ITM_CODE');
				if($LangID == 'IND')
				{
					$data['h1_title']	= 'LAPORAN DETIL ITEM AMANDEMEN';
				}
				else
				{
					$data['h1_title']	= 'AMENDMENT ITEM DETAIL REPORT';
				}
				$this->load->view('v_project/v_report/v_amandemen/v_amandemen_report_det', $data);
			}
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function spk() // G
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$url			= site_url('c_project/c_r3p/s5pK/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function s5pK() // G
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$data['MenuCode']		= 'MN352';
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['h1_title']	= 'Laporan SPK';
				$data['h2_title']	= 'Laporan';
			}
			else
			{
				$data['h1_title']	= 'SPK Report';
				$data['h2_title']	= 'Report';
			}
			$data['form_action'] 	= site_url('c_project/c_r3p/s5pKvw/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_project/v_report/v_spk/v_spk', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function s5pKvw() // G
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['h1_title']	= 'Laporan SPK';
				$data['h2_title']	= 'Laporan';
			}
			else
			{
				$data['h1_title']	= 'SPK Report';
				$data['h2_title']	= 'Report';
			}
		
			$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
			$data['PRJCODE'] 	= $this->input->post('PRJCODE');
			$PRJCODE			= $this->input->post('PRJCODE');
			$getproject 		= "SELECT A.PRJCODE, A.PRJNAME FROM tbl_project A WHERE A.PRJCODE = '$PRJCODE'";
			$qProject 		= $this->db->query($getproject)->result();
			foreach($qProject as $rowPRJ) :
				$data['PRJNAME'] = $rowPRJ->PRJNAME;
			endforeach;
			
			$datePeriod1		= $this->input->post('datePeriod');
			$datePeriod2		= explode(" - ", $datePeriod1);
			$Start_Date			= date('Y-m-d', strtotime($datePeriod2[0]));
			$End_Date			= date('Y-m-d', strtotime($datePeriod2[1]));
			
			$data['Start_Date'] = $Start_Date;
			$data['End_Date'] 	= $End_Date;
			$data['viewType'] 	= $this->input->post('viewType');	// View or Excel
			$this->load->view('v_project/v_report/v_spk/v_spk_report', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function s70ck0p() // G
	{		
		if ($this->session->userdata('login') == TRUE)
		{
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$url			= site_url('c_project/c_r3p/s70ck0px/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function s70ck0px() // G
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$data['MenuCode']		= 'MN304';
			
			$LangID 	= $this->session->userdata['LangID'];
				
			// START : GET MENU NAME
				$mnCode				= 'MN304';
				$data["MenuApp"] 	= 'MN304';
				$data["MenuCode"] 	= 'MN304';

				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			// END : GET MENU NAME

			$data['form_action'] 	= site_url('c_project/c_r3p/s70ck0pvw/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_project/v_report/v_stock_opname/v_stock_opname', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function s70ck0pvw() // G
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$data['title'] 		= $appName;
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['h1_title']	= 'LAPORAN STOK OPNAME';
				$data['h2_title']	= 'Laporan';
			}
			else
			{
				$data['h1_title']	= 'STOCK OPNAME REPORT';
				$data['h2_title']	= 'Report';
			}
		
			/*$viewProj 			= $this->input->post('viewProj');	// All Project or Selected Project
			if($viewProj == 0)
			{
				$packageelements	= $_POST['packageelements'];
				$TOTPROJ			= count($packageelements);
				if (count($packageelements)>0)
				{
					$mySelected	= $_POST['packageelements'];
					$row		= 0;
					foreach ($mySelected as $projCode)
					{
						$row	= $row + 1;
						if($row == 1)
						{
							$PRJCODE1	= $projCode;
						}
						else
						{
							$PRJCODE1	= "$PRJCODE1','$projCode";
						}
					}
				}
				$data['TOTPROJ'] 	= $TOTPROJ;
				$data['PRJCODECOL'] 	= "'$PRJCODE1'";
			}
			else
			{
				$data['TOTPROJ'] 	= 0;
				$PRJCODE1			= "";
				$data['PRJCODECOL'] = "'$PRJCODE1'";
			}*/
			
			
			$PRJCODE 			= $this->input->post('PRJCODE');
			$data['PRJCODE'] 	= $PRJCODE;
			$data['ITM_GROUP'] 	= $this->input->post('ITM_GROUP');
			$datePeriod1		= $this->input->post('datePeriod');
			$datePeriod2		= explode(" - ", $datePeriod1);
			$Start_Date			= date('Y-m-d', strtotime($datePeriod2[0]));
			$End_Date			= date('Y-m-d', strtotime($datePeriod2[1]));
			$data['datePeriod'] = $datePeriod1;
			$data['Start_Date'] = $Start_Date;
			$data['End_Date'] 	= $End_Date;
			$data['viewType'] 	= $this->input->post('viewType');	// View or Excel
			$this->load->view('v_project/v_report/v_stock_opname/v_stock_opname_report_sum', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function pRj1N() // G
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$url			= site_url('c_project/c_r3p/pRj1Nx/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function pRj1Nx() // G
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$data['MenuCode']		= 'MN137';
			
			$LangID 	= $this->session->userdata['LangID'];
				
			// START : GET MENU NAME
				$mnCode				= 'MN137';
				$data["MenuApp"] 	= 'MN137';
				$data["MenuCode"] 	= 'MN137';

				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			// END : GET MENU NAME

			$data['form_action'] 	= site_url('c_project/c_r3p/pRj1NVwR3p/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_project/v_report/v_projinv/v_projinv', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function pRj1NVwR3p() // G
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$data['title'] 		= $appName;
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['h1_title']	= 'LAPORAN FAKTUR PROYEK';
				$data['h2_title']	= 'Report';
			}
			else
			{
				$data['h1_title']	= 'PROJECT INVOICE REPORT';
				$data['h2_title']	= 'Report';
			}
		
			$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
			$data['PRJCODE'] 	= $this->input->post('PRJCODE');
			$PRJCODE			= $this->input->post('viewProj');
			$getproject = "SELECT A.PRJCODE, A.PRJNAME FROM tbl_project A
							WHERE A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID') 
							AND PRJCODE = '$PRJCODE' ORDER BY A.PRJCODE";
			$qProject 		= $this->db->query($getproject)->result();
			foreach($qProject as $rowPRJ) :
				$data['PRJNAME'] = $rowPRJ->PRJNAME;
			endforeach;
			
			//$datePeriod1		= $this->input->post('datePeriod');
			//$datePeriod2		= explode(" - ", $datePeriod1);
			//$Start_Date			= date('Y-m-d', strtotime($datePeriod2[0]));
			//$End_Date			= date('Y-m-d', strtotime($datePeriod2[1]));
			
			//$data['Start_Date'] = $Start_Date;
			//$data['End_Date'] 	= $End_Date;
			$data['viewType'] 	= $this->input->post('viewType');	// View or Excel
			
			$this->load->view('v_project/v_report/v_projinv/v_projinv_report', $data);
			
		}
		else
		{
			redirect('__l1y');
		}
	}
	
    function pr073ctr3p()
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$url			= site_url('c_project/c_r3p/pr073ctr3px/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('__l1y'); 
		}
    }
	
    function pr073ctr3px()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$data['MenuCode']		= 'MN130';
			
			$LangID 	= $this->session->userdata['LangID'];
				
			// START : GET MENU NAME
				$mnCode				= 'MN130';
				$data["MenuApp"] 	= 'MN130';
				$data["MenuCode"] 	= 'MN130';

				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			// END : GET MENU NAME

			$data['form_action'] 	= site_url('c_project/c_r3p/pr073ctr3pvw/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_project/v_report/v_project/v_project', $data);
		}
		else
		{
			redirect('__l1y');
		}
    }
	
	function pr073ctr3pvw() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$data['title'] 		= $appName;
			$data['SuppStat'] 	= $this->input->post('SuppStat');
			$data['SortBy'] 	= $this->input->post('SortBy');
			$data['viewType'] 	= $this->input->post('viewType');	// View or Excel
			$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
			
			$SortBy		= $data['SortBy'];
			
			$LangID 			= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['h1_title'] 	= 'DAFTAR PROYEK';
			}
			else
			{
				$data['h1_title'] 	= 'PEOJECT LIST';
			}
			
			$this->load->view('v_project/v_report/v_project/v_project_report', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function M47R3qR3p() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$url			= site_url('c_project/c_r3p/M47R3qR3p_1n4/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function M47R3qR3p_1n4() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$data['MenuCode']		= 'MN131';
			
			$LangID 	= $this->session->userdata['LangID'];
				
			// START : GET MENU NAME
				$mnCode				= 'MN131';
				$data["MenuApp"] 	= 'MN131';
				$data["MenuCode"] 	= 'MN131';

				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			// END : GET MENU NAME

			$data['form_action'] 	= site_url('c_project/c_r3p/M47R3qR3p2VwR3p/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_project/v_report/v_budgetmtring/v_budgetmtring', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function M47R3qR3p2VwR3p() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$data['title'] 		= $appName;
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['h1_title']	= 'LAPORAN MONITORING ANGGARAN';
				$data['h2_title']	= 'Laporan';
			}
			else
			{
				$data['h1_title']	= 'BUDGET MONITORING REPORT';
				$data['h2_title']	= 'Report';
			}
		
			$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
			$data['PRJCODE'] 	= $this->input->post('PRJCODE');
			$PRJCODE			= $this->input->post('PRJCODE');
			$PRJNAME			= '';
			$getproject 		= "SELECT A.PRJCODE, A.PRJNAME FROM tbl_project A WHERE A.PRJCODE = '$PRJCODE' ORDER BY A.PRJCODE";
			$qProject 			= $this->db->query($getproject)->result();
			foreach($qProject as $rowPRJ) :
				$PRJNAME		= $rowPRJ->PRJNAME;
			endforeach;
			$data['PRJNAME'] 	= $rowPRJ->PRJNAME;
			
			$data['ITM_GROUP'] 	= $this->input->post('ITM_GROUP');
			$data['JOBPARENT'] 	= $this->input->post('JOBPARENT');
			$JOBPARENT 			= $this->input->post('JOBPARENT');
			$ITM_GROUP			= $this->input->post('ITM_GROUP');
			if($ITM_GROUP == 'All')
			{
				$ITMGRP_NM		= "All Item";
			}
			else
			{
				$ITMGRP_NM		= '';
				$sqlITMGRP		= "SELECT IG_Name FROM tbl_itemgroup WHERE IG_Code = '$ITM_GROUP' LIMIT 1";
				$resITMGRP		= $this->db->query($sqlITMGRP)->result();
				foreach($resITMGRP as $rowITMGRP) :
					$ITMGRP_NM 	= $rowITMGRP->IG_Name;
				endforeach;
			}
			$data['ITMGRP_NM'] 	= $ITMGRP_NM;
			
			$datePeriod1		= $this->input->post('datePeriod');
			$datePeriod2		= explode(" - ", $datePeriod1);
			$Start_Date			= date('Y-m-d', strtotime($datePeriod2[0]));
			$End_Date			= date('Y-m-d', strtotime($datePeriod2[1]));
			$data['datePeriod'] = $datePeriod1;
			$data['Start_Date'] = $Start_Date;
			$data['End_Date'] 	= $End_Date;
			$data['CFType'] 	= $this->input->post('CFType');		// 1. Summary or 2. Detail --> Always Summary
			$CFType				= $this->input->post('CFType');		// Summary or Detail
			$data['viewType'] 	= $this->input->post('viewType');	// View or Excel
			
			//$this->load->view('v_project/v_report/v_budgetmtring/v_budgetmtring_report', $data);
			if($JOBPARENT == '')
				$this->load->view('v_project/v_report/v_budgetmtring/v_sdbp_report_item', $data);
			else
				$this->load->view('v_project/v_report/v_budgetmtring/v_sdbp_report', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function i7M43t41L() // G
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$sqlApp 		= "SELECT * FROM tappname LIMIT 1";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$url			= site_url('c_project/c_r3p/i7M43t41Lx/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
    }
	
	function i7M43t41Lx() // G
	{
		$idAppName	= $_GET['id'];
		$appName	= $this->url_encryption_helper->decode_url($idAppName);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$data['appName'] 		= $appName;
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Detil Budget Item";
				$data["h2_title"] 	= "Laporan";
			}
			else
			{
				$data["h1_title"] 	= "Item Budget Detail";
				$data["h2_title"] 	= "Report";
			}
			
			$data['form_action'] 	= site_url('c_project/c_r3p/ri7M43t41Lx_0fv13w/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_project/v_report/v_itembudet/v_itembudet', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function ri7M43t41Lx_0fv13w() // G
	{
		$idAppName	= $_GET['id'];
		$appName	= $this->url_encryption_helper->decode_url($idAppName);
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$data['title'] 		= $appName;
			
			$LangID 	= $this->session->userdata['LangID'];

			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "LAPORAN BUDGET - DETAIL";
				$data["h2_title"] 	= "Detail Budget Detail";
			}
			else
			{
				$data["h1_title"] 	= "BUDGET REPORT - DETAIL";
				$data["h2_title"] 	= "Detail Budget Detail";
			}
			
			$data['PRJCODE'] 	= $this->input->post('PRJCODE');
			$data['JOBPARENT'] 	= $this->input->post('JOBPARENT');
			$data['ITM_CODE'] 	= $this->input->post('ITM_CODE');
			// $TransType 	        = $this->input->post('TransType');	// Request or Realization
			$datePeriod1		= $this->input->post('datePeriod');
			$datePeriod2		= explode(" - ", $datePeriod1);
			$data['datePeriod']	= $datePeriod1;
			$data['Start_Date']	= date('Y-m-d', strtotime(str_replace('/', '-', $datePeriod2[0])));
			$data['End_Date']	= date('Y-m-d', strtotime(str_replace('/', '-', $datePeriod2[1])));
			$data['viewType'] 	= $this->input->post('viewType');	// View or Excel

			$this->load->view('v_project/v_report/v_itembudet/v_itembudet_report', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function getJOBLEV()
	{
		$PRJCODE    = $this->input->post('PRJCODE');
		$this->db->select("IS_LEVEL");
		$this->db->distinct("IS_LEVEL");
		$this->db->from("tbl_joblist_detail");
		$this->db->where(['PRJCODE' => $PRJCODE]);
		$this->db->order_by('IS_LEVEL', 'ASC');
		$data = $this->db->get()->result();
		echo json_encode($data);
	}

	function getJOBPARENT1()
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE    = $this->input->post('PRJCODE');
			$this->db->from("tbl_joblist_detail");
			$this->db->where(['PRJCODE' => $PRJCODE, 'ISLAST' => 0]);
			$this->db->order_by('ORD_ID','ASC');
			$data = $this->db->get()->result();
		    echo json_encode($data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function getJOBPARENT()
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE    = $this->input->post('PRJCODE');
			// $JOBLEV    	= $this->input->post('JOBLEV');
			$this->db->from("tbl_joblist_detail");
			$this->db->where(['PRJCODE' => $PRJCODE, 'ISLAST' => 0]);
			// if($JOBLEV[0] != 0) $this->db->where_in("IS_LEVEL", $JOBLEV);
			$this->db->order_by('ORD_ID','ASC');
			$data = $this->db->get()->result();
		    echo json_encode($data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function getITM_CODE()
	{
	    if ($this->session->userdata('login') == TRUE)
		{
		    $JOBPARENT    	= $this->input->post('JOBPARENT');
		    $PRJCODE    	= $this->input->post('PRJCODE');
		    $qJOBITEM 		= "SELECT A.JOBCODEID, A.JOBDESC, A.ITM_CODE, B.ITM_NAME FROM tbl_joblist_detail A 
		    					INNER JOIN tbl_item B ON B.ITM_CODE = A.ITM_CODE AND B.PRJCODE = A.PRJCODE
		    					WHERE A.PRJCODE = '$PRJCODE' AND A.JOBPARENT = '$JOBPARENT' AND ISLAST = 1 
		    					ORDER BY ORD_ID ASC";
		    $resJOBITEM 	= $this->db->query($qJOBITEM);
		    $data 			= $resJOBITEM->result();
		    echo json_encode($data);
		}
		else
		{
		    redirect('__l1y');
		}
	}
	
	function s1R3p_1n4() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$data['MenuCode']		= 'MN132';
			
			$LangID 	= $this->session->userdata['LangID'];
				
			// START : GET MENU NAME
				$mnCode				= 'MN132';
				$data["MenuApp"] 	= 'MN132';
				$data["MenuCode"] 	= 'MN132';

				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			// END : GET MENU NAME

			$data['form_action'] 	= site_url('c_project/c_r3p/s1R3p2VwR3p/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_project/v_report/v_si_project/v_si_project', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function s1R3p2VwR3p() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$data['title'] 		= $appName;
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['h1_title']	= 'LAPORAN DETIL PEKERJAAN TAMBAH/KURANG';
				$data['h2_title']	= 'Laporan';
			}
			else
			{
				$data['h1_title']	= 'DETAIL OF SITE INSTRUCTION REPORT';
				$data['h2_title']	= 'Report';
			}
		
			$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
			$data['PRJCODE'] 	= $this->input->post('PRJCODE');
			$PRJCODE			= $this->input->post('PRJCODE');
			$PRJNAME			= '';
			$getproject 		= "SELECT A.PRJCODE, A.PRJNAME FROM tbl_project A WHERE A.PRJCODE = '$PRJCODE' ORDER BY A.PRJCODE";
			$qProject 			= $this->db->query($getproject)->result();
			foreach($qProject as $rowPRJ) :
				$PRJNAME		= $rowPRJ->PRJNAME;
			endforeach;
			$data['PRJNAME'] 	= $rowPRJ->PRJNAME;
			
			$data['CFType'] 	= $this->input->post('CFType');		// 1. Summary or 2. Detail --> Always Summary
			$CFType				= $this->input->post('CFType');		// Summary or Detail
			$data['viewType'] 	= $this->input->post('viewType');	// View or Excel
			
			$this->load->view('v_project/v_report/v_si_project/v_si_project_report', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
}