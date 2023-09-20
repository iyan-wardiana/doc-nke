<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 17 April 2017
 * File Name	= C_asset_report.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_si_project_report extends CI_Controller  
{	
    function index()
	{		
		if ($this->session->userdata('login') == TRUE)
		{
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$url			= site_url('c_project/c_si_project_report/si_project/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
    }
	
	function si_project() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$data['h2_title']		= 'SI Project';
			$data['h3_title']		= 'Report';
			$data['form_action'] 	= site_url('c_project/c_si_project_report/si_project_view/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_project/v_report/v_si_project/v_si_project', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function si_project_view() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$data['title'] 		= $appName;
			$data['h2_title']	= 'SI Project';
			$data['h3_title']	= 'Report';
		
			$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
			$data['PRJCODE'] 	= $this->input->post('PRJCODE');
			$PRJCODE			= $this->input->post('PRJCODE');
			$getproject = "SELECT A.PRJCODE, A.PRJNAME FROM tbl_project A
							WHERE A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID') 
							ORDER BY A.PRJCODE";
			$qProject 		= $this->db->query($getproject)->result();
			foreach($qProject as $rowPRJ) :
				$data['PRJNAME'] = $rowPRJ->PRJNAME;
			endforeach;
			
			$data['Start_Date'] 	= $this->input->post('Start_Date');
			$data['End_Date'] 		= $this->input->post('End_Date');
			//$data['TYPE'] 			= $this->input->post('TYPE');
			$data['CFType'] 		= $this->input->post('CFType');		// Summary or Detail
			$data['viewType'] 		= $this->input->post('viewType');	// View or Excel
			
			//echo "hahah $DefEmp_ID";
			if($DefEmp_ID == 'D15040004221' or $DefEmp_ID == 'H17050004765')
			{
				$this->load->view('v_project/v_report/v_si_project/v_si_project_report_adm', $data);
			}
			else
			{
				$this->load->view('v_project/v_report/v_si_project/v_si_project_report', $data);
			}
		}
		else
		{
			redirect('Auth');
		}
	}

}