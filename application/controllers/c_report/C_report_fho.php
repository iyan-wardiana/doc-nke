<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 17 April 2017
 * File Name	= C_asset_report.php
 * Location		= -
*/

/* 
 * Author		= Hendar Permana
 * Edit Date	= 9 Agustus 2017
 * File Name	= c_report_fho.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_report_fho extends CI_Controller  
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
			
			$url			= site_url('c_report/c_report_fho/fho/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
    }
	
	function fho() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$data['h2_title']		= 'FHO';
			$data['h3_title']		= 'Report';
			$data['form_action'] 	= site_url('c_report/c_report_fho/fho_view/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_report/v_fho/v_fho', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function fho_view() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$data['title'] 		= $appName;
			$data['h2_title']	= 'FHO';
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
			$data['TYPE'] 			= $this->input->post('TYPE');
			$data['CFType'] 		= $this->input->post('CFType');		// Summary or Detail
			$data['viewType'] 		= $this->input->post('viewType');	// View or Excel
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			//echo "hahah $DefEmp_ID";
			if($DefEmp_ID == 'D15040004221' or $DefEmp_ID == 'H17050004765')
			{
				$this->load->view('v_report/v_fho/v_fho_report_adm', $data);
			}
			else
			{
				$this->load->view('v_report/v_fho/v_fho_report', $data);
			}
		}
		else
		{
			redirect('Auth');
		}
	}

/*	function r_usage() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$data['h2_title']		= 'Asset Usage';
			$data['h3_title']		= 'report';
			$data['form_action'] 	= site_url('c_asset/c_asset_report/r_usage_view/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_asset/v_report/r_usage/r_usage', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function r_usage_view() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$data['title'] 		= $appName;
			$data['h2_title']	= 'Asset Usage';
			$data['h3_title']	= 'report';
			
			$data['vPeriod']	= $this->input->post('vPeriod');
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
			}
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
			$data['CFType'] 		= $this->input->post('CFType');		// Summary or Detail
			$data['viewType'] 		= $this->input->post('viewType');	// View or Excel
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			//echo "hahah $DefEmp_ID";
			if($DefEmp_ID == 'D15040004221')
			{
				$this->load->view('v_asset/v_report/r_usage/r_usage_report_adm', $data);
			}
			else
			{
				$this->load->view('v_asset/v_report/r_usage/r_usage_report', $data);
			}
		}
		else
		{
			redirect('Auth');
		}
	}*/

/*	function r_product() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$data['h2_title']		= 'Asset Production';
			$data['h3_title']		= 'report';
			$data['form_action'] 	= site_url('c_asset/c_asset_report/r_product_view/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_asset/v_report/r_product/r_product', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function r_product_view() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$data['title'] 		= $appName;
			$data['h2_title']	= 'Asset Usage';
			$data['h3_title']	= 'report';
			
		
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
				$data['PRJCODECOL'] = "'$PRJCODE1'";
			}
			else
			{
				$data['TOTPROJ'] 	= 0;
				$PRJCODE1			= "";
				$data['PRJCODECOL'] = "'$PRJCODE1'";
			}
			
			$viewAsset 			= $this->input->post('viewAsset');	// All Project or Selected Project
			if($viewAsset == 0)
			{
				$packageelementsAst	= $_POST['packageelementsAst'];
				$TOTAST			= count($packageelementsAst);
				if (count($packageelementsAst)>0)
				{
					$mySelected	= $_POST['packageelementsAst'];
					$row		= 0;
					foreach ($mySelected as $ASTCODE)
					{
						$row	= $row + 1;
						if($row == 1)
						{
							$ASTCODE1	= $ASTCODE;
						}
						else
						{
							$ASTCODE1	= "$ASTCODE1','$ASTCODE";
						}
					}
				}
				$data['TOTAST'] 	= $TOTAST;
				$data['ASTCODECOL'] 	= "'$ASTCODE1'";
			}
			else
			{
				$data['TOTAST'] 	= 0;
				$ASTCODE1			= "";
				$data['ASTCODECOL'] = "'$ASTCODE1'";
			}			
			$data['viewAsset'] 		= $this->input->post('viewAsset');	// All Asset or Selected Asset	
			$data['viewProj'] 		= $this->input->post('viewProj');	// All Project or Selected Project		
			$data['CFType'] 		= $this->input->post('CFType');		// Summary or Detail
			$data['viewType'] 		= $this->input->post('viewType');	// View or Excel
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			//echo "hahah $DefEmp_ID";
			if($DefEmp_ID == 'D15040004221')
			{
				$this->load->view('v_asset/v_report/r_product/r_product_report_adm', $data);
			}
			else
			{
				$this->load->view('v_asset/v_report/r_product/r_product_report', $data);
			}
		}
		else
		{
			redirect('Auth');
		}
	}*/

/*	function r_vocref() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$data['h2_title']		= 'Voucher References';
			$data['h3_title']		= 'report';
			$data['form_action'] 	= site_url('c_asset/c_asset_report/r_vocref_view/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_asset/v_report/r_vocref/r_vocref', $data);
		}
		else
		{
			redirect('Auth');
		}
	}*/
	
/*	function r_vocref_view() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$data['title'] 		= $appName;
			$data['h2_title']	= 'Voucher Reference';
			$data['h3_title']	= 'report';
			
		
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
				$data['PRJCODECOL'] = "'$PRJCODE1'";
			}
			else
			{
				$data['TOTPROJ'] 	= 0;
				$PRJCODE1			= "";
				$data['PRJCODECOL'] = "'$PRJCODE1'";
			}
			
			$viewAsset 			= $this->input->post('viewAsset');	// All Project or Selected Project
			if($viewAsset == 0)
			{
				$packageelementsAst	= $_POST['packageelementsAst'];
				$TOTAST			= count($packageelementsAst);
				if (count($packageelementsAst)>0)
				{
					$mySelected	= $_POST['packageelementsAst'];
					$row		= 0;
					foreach ($mySelected as $ASTCODE)
					{
						$row	= $row + 1;
						if($row == 1)
						{
							$ASTCODE1	= $ASTCODE;
						}
						else
						{
							$ASTCODE1	= "$ASTCODE1','$ASTCODE";
						}
					}
				}
				$data['TOTAST'] 	= $TOTAST;
				$data['ASTCODECOL'] 	= "'$ASTCODE1'";
			}
			else
			{
				$data['TOTAST'] 	= 0;
				$ASTCODE1			= "";
				$data['ASTCODECOL'] = "'$ASTCODE1'";
			}			
			$data['viewAsset'] 		= $this->input->post('viewAsset');	// All Asset or Selected Asset	
			$data['viewProj'] 		= $this->input->post('viewProj');	// All Project or Selected Project		
			$data['CFType'] 		= $this->input->post('CFType');		// Summary or Detail
			$data['viewType'] 		= $this->input->post('viewType');	// View or Excel
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			//echo "hahah $DefEmp_ID";
			if($DefEmp_ID == 'D15040004221')
			{
				$this->load->view('v_asset/v_report/r_vocref/r_vocref_report_adm', $data);
			}
			else
			{
				$this->load->view('v_asset/v_report/r_vocref/r_vocref_report', $data);
			}
		}
		else
		{
			redirect('Auth');
		}
	}*/
	
/*	function r_assetexp() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$data['h2_title']		= 'Asset Expenses';
			$data['h3_title']		= 'report';
			$data['form_action'] 	= site_url('c_asset/c_asset_report/r_assetexp_view/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_asset/v_report/r_assetexp/r_assetexp', $data);
		}
		else
		{
			redirect('Auth');
		}
	}*/
	
/*	function r_assetexp_view() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$data['title'] 		= $appName;
			$data['h2_title']	= 'Asset Usage';
			$data['h3_title']	= 'report';
			
			$data['vPeriod']	= $this->input->post('vPeriod');
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
			}
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
			$data['CFType'] 		= $this->input->post('CFType');		// Summary or Detail
			$data['viewType'] 		= $this->input->post('viewType');	// View or Excel
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			//echo "hahah $DefEmp_ID";
			if($DefEmp_ID == 'D15040004221')
			{
				$this->load->view('v_asset/v_report/r_assetexp/r_assetexp_report_adm', $data);
			}
			else
			{
				$this->load->view('v_asset/v_report/r_assetexp/r_assetexp_report', $data);
			}
		}
	}*/
}