<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 17 April 2017
 * File Name	= C_asset_report.php
 * Location		= -
*/

/* 
 * Author		= Hendar Permana
 * Edit Date	= 20 September 2017
 * File Name	= c_asset_position_report.php
 * Location		= -
*/

class C_asset_position_report extends CI_Controller  
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
			
			$url			= site_url('c_asset/C_asset_position_report/asset_position/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
    }
	

	function asset_position() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$data['h2_title']		= 'SKA & SKT';
			$data['h3_title']		= 'Report';
			$data['form_action'] 	= site_url('c_asset/c_asset_position_report/asset_position_view/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_asset/v_asset_position_report/v_asset_position', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function asset_position_view() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$data['title'] 		= $appName;
			$data['h2_title']	= 'SKA & SKT';
			$data['h3_title']	= 'Report';
		
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
			$data['viewProj'] 		= $this->input->post('viewProj');	// All Project or Selected Project
			$data['Start_Date'] 	= $this->input->post('Start_Date');
			$data['End_Date'] 		= $this->input->post('End_Date');
			//$data['TYPE'] 			= $this->input->post('TYPE');
			//$data['SUB_BIDANG']		= $this->input->post('SUB_BIDANG');
			$data['GROUP']		= $this->input->post('GROUP');
			$data['CFType'] 		= $this->input->post('CFType');		// Summary or Detail
			$data['viewType'] 		= $this->input->post('viewType');	// View or Excel
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			//echo "hahah $DefEmp_ID";
			if($DefEmp_ID == 'D15040004221' or $DefEmp_ID == 'H17050004765')
			{
				$this->load->view('v_asset/v_asset_position_report/v_asset_position_report_adm', $data);
			}
			else
			{
				$this->load->view('v_asset/v_asset_position_report/v_asset_position_report', $data);
			}
		}
		else
		{
			redirect('Auth');
		}
	}


}