<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 17 April 2017
 * File Name	= C_asset_report.php
 * Location		= -
*/

/* 
 * Author		= Hendar Permana
 * Create Date	= 23 Oktober 2017
 * File Name	= c_jo_report.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class c_jo_report extends CI_Controller  
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
			
			$url			= site_url('c_project/c_jo_report/r_jo/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
    }
	
	function r_jo() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$data['h2_title']		= 'J O';
			$data['h3_title']		= 'Report';
			$data['form_action'] 	= site_url('c_project/c_jo_report/r_jo_view/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_project/v_jo_report/v_jo', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function r_jo_view() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$data['title'] 		= $appName;
			$data['h2_title']	= 'J O';
			$data['h3_title']	= 'Report';
			
			/*$data['vPeriod']	= $this->input->post('vPeriod');
			$vPeriod			= $this->input->post('vPeriod');
			if($vPeriod == "daily")
			{
				$data['Start_Date'] 	= $this->input->post('Start_Date');
				$data['End_Date'] 		= $this->input->post('End_Date');
			}
			elseif($vPeriod == "weekly")
			{
				$data['Start_Date'] 	= $this->input->post('Start_Date');
			}
			else
			{
				$data['End_Date'] 		= $this->input->post('End_Date');
			}*/
			$viewProj 			= $this->input->post('viewProj');	// All Project or Selected Project
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
			}			
			$data['Sort1'] 			= $this->input->post('Sort1');
			$data['Start_Date'] 	= $this->input->post('Start_Date');
			$data['End_Date'] 		= $this->input->post('End_Date');
			$data['viewProj'] 		= $this->input->post('viewProj');	// All Project or Selected Project			
			$data['CFType'] 		= $this->input->post('CFType');		// Summary or Detail
			$data['viewType'] 		= $this->input->post('viewType');	// View or Excel
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			//echo "hahah $DefEmp_ID";
			if($DefEmp_ID == 'H17050004765' or 'D15040004221')
			{
				$this->load->view('v_project/v_jo_report/v_jo_report_adm', $data);
			}
			else
			{
				$this->load->view('v_project/v_jo_report/v_jo/v_jo_report', $data);
			}
		}
		else
		{
			redirect('Auth');
		}
	}

	
}