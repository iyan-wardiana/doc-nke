<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 17 April 2017
 * File Name	= C_asset_report.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_asset_report extends CI_Controller  
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
			
			$url			= site_url('c_asset/c_asset_report/r_usagereq/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
    }
	
	function r_assetlist() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$data['h2_title']		= 'Asset List';
			$data['h3_title']		= 'report';
			$data['form_action'] 	= site_url('c_asset/c_asset_report/r_assetlist_view/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_asset/v_report/r_assetlist/r_assetlist', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function r_assetlist_view() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$data['title'] 		= $appName;
			$data['h2_title']	= 'Asset List';
			$data['h3_title']	= 'report';
			
			$data['sortBy'] 	= $this->input->post('sortBy');
			$data['sortType'] 	= $this->input->post('sortType');
			$data['viewType'] 	= $this->input->post('viewType');
			$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
			
			if($DefEmp_ID == 'H17050004765' or 'D15040004221')
			{
				$this->load->view('v_asset/v_report/r_assetlist/r_assetlist_report_adm', $data);
			}
			else
			{
				$this->load->view('v_asset/v_report/r_assetlist/r_assetlist_report', $data);
			}
		}
		else
		{
			redirect('__l1y');
		}
	}

	function r_usagereq() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$data['h2_title']		= 'Asset Usage Request';
			$data['h3_title']		= 'report';
			$data['form_action'] 	= site_url('c_asset/c_asset_report/r_usagereq_view/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_asset/v_report/r_usagereq/r_usagereq', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function r_usagereq_view() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$data['title'] 		= $appName;
			$data['h2_title']	= 'Asset Usage Request';
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
			$data['CFType'] 		= $this->input->post('CFType');		// Summary or Detail
			$data['viewType'] 		= $this->input->post('viewType');	// View or Excel
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			//echo "hahah $DefEmp_ID";
			if($DefEmp_ID == 'H17050004765' or 'D15040004221')
			{
				$this->load->view('v_asset/v_report/r_usagereq/r_usagereq_report_adm', $data);
			}
			else
			{
				$this->load->view('v_asset/v_report/r_usagereq/r_usagereq_report', $data);
			}
		}
		else
		{
			redirect('__l1y');
		}
	}

	function r_usage() // OK
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
			redirect('__l1y');
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
			if($DefEmp_ID == 'H17050004765' or 'D15040004221')
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
			redirect('__l1y');
		}
	}

	function r_product() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$url			= site_url('c_asset/c_asset_report/r_productIdx/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function r_productIdx() // OK
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
			redirect('__l1y');
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
			$data['viewProj'] 		= $this->input->post('viewProj');	// All Project or Selected Project
			$data['viewAsset'] 		= $this->input->post('viewAsset');	// All Asset or Selected Asset
			$data['Start_Date'] 	= $this->input->post('Start_Date');
			$data['End_Date'] 		= $this->input->post('End_Date');
			$data['CFType'] 		= $this->input->post('CFType');		// Summary or Detail
			$data['viewType'] 		= $this->input->post('viewType');	// View or Excel
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			//echo "hahah $DefEmp_ID";
			if($DefEmp_ID == 'H17050004765'  or 'D15040004221')
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
			redirect('__l1y');
		}
	}

	function r_vocref() // OK
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
			redirect('__l1y');
		}
	}
	
	function r_vocref_view() // OK
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
			if($DefEmp_ID == 'H17050004765' or 'D15040004221')
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
			redirect('__l1y');
		}
	}
	
	function r_assetexp() // OK
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
			redirect('__l1y');
		}
	}
	
	function r_assetexp_view() // OK
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
			if($DefEmp_ID == 'H17050004765' or 'D15040004221')
			{
				$this->load->view('v_asset/v_report/r_assetexp/r_assetexp_report_adm', $data);
			}
			else
			{
				$this->load->view('v_asset/v_report/r_assetexp/r_assetexp_report', $data);
			}
		}
	}

	function r_assetcost() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$url			= site_url('c_asset/c_asset_report/r_assetcostIdx/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function r_assetcostIdx() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$data['h2_title']		= 'Asset Cost';
			$data['h3_title']		= 'report';
			$data['form_action'] 	= site_url('c_asset/c_asset_report/r_assetcost_view/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_asset/v_report/r_cost/r_cost', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function r_assetcost_view() // OK
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
			$data['viewProj'] 		= $this->input->post('viewProj');	// All Project or Selected Project
			$data['viewAsset'] 		= $this->input->post('viewAsset');	// All Asset or Selected Asset
			$data['Start_Date'] 	= $this->input->post('Start_Date');
			$data['End_Date'] 		= $this->input->post('End_Date');
			$data['CFType'] 		= $this->input->post('CFType');		// Summary or Detail
			$data['viewType'] 		= $this->input->post('viewType');	// View or Excel
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			//echo "hahah $DefEmp_ID";
			if($DefEmp_ID == 'H17050004765' or 'D15040004221')
			{
				$this->load->view('v_asset/v_report/r_cost/r_cost_report_adm', $data);
			}
			else
			{
				$this->load->view('v_asset/v_report/r_cost/r_cost_report', $data);
			}
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function r_asset_HvyEquipment() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$data['h2_title']		= 'Rekapitulasi Time Sheet Sewa Alat Berat';
			$data['h3_title']		= 'report';
			$data['form_action'] 	= site_url('c_asset/c_asset_report/r_asset_HvyEquipment_view/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_asset/v_report/r_asset_HvyEquipment/r_asset_HvyEquipment', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function r_asset_HvyEquipment_view() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$data['title'] 		= $appName;
			$data['h2_title']	= 'Rekapitulasi Time Sheet Sewa Alat Berat';
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
			$AS_CODE1				= $this->input->post('AS_CODE');
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			
			$selStep	= 0;
			$AS_CODE2	= '';
			if($AS_CODE1 != '')
			{
				foreach ($AS_CODE1 as $sel_AS)
				{
					$selStep	= $selStep + 1;
					if($selStep == 1)
					{
						$user_to	= explode ("|",$sel_AS);
						$user_ID	= $user_to[0];
						$AS_CODE	= "'$user_ID'";
						$AS_CODE2	= "$AS_CODE";
					}
					else
					{					
						$user_to	= explode ("|",$sel_AS);
						$user_ID	= $user_to[0];			
						$AS_CODE2	= "$AS_CODE2,'$user_ID'";
					}
				}
			}
			$data['AS_CODE'] 		= $AS_CODE2;
			//echo $data['AS_CODE'];
			//return false;
			
			//echo "hahah $DefEmp_ID";
			if($DefEmp_ID == 'H17050004765' or 'D15040004221')
			{
				$this->load->view('v_asset/v_report/r_asset_HvyEquipment/r_asset_HvyEquipment_report_adm', $data);
			}
			else
			{
				$this->load->view('v_asset/v_report/r_asset_HvyEquipment/r_asset_HvyEquipment_report', $data);
			}
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function r_asset_Equipment_traffic() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$data['h2_title']		= 'Lalu Lintas Alat Dan Barang';
			$data['h3_title']		= 'report';
			$data['form_action'] 	= site_url('c_asset/c_asset_report/r_asset_Equipment_traffic_view/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_asset/v_report/r_asset_Equipment_traffic/r_asset_Equipment_traffic', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function r_asset_Equipment_traffic_view() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$data['title'] 		= $appName;
			$data['h2_title']	= 'Lalu Lintas Alat Dan Barang';
			$data['h3_title']	= 'report';
			
			$data['vPeriod']	= $this->input->post('vPeriod');
			$vPeriod			= $this->input->post('vPeriod');
			if($vPeriod == "daily")
			{
				$data['SendDate'] 	= $this->input->post('SendDate');
				$data['ReceiptDate'] 		= $this->input->post('ReceiptDate');
			}
			elseif($vPeriod == "weekly")
			{
				$data['SendDate'] 	= $this->input->post('SendDate');
			}
			else
			{
				$data['ReceiptDate'] 		= $this->input->post('ReceiptDate');
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
			$AS_CODE1				= $this->input->post('AS_CODE');
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			
			//echo "hahah $DefEmp_ID";
			if($DefEmp_ID == 'H17050004765' or 'D15040004221')
			{
				$this->load->view('v_asset/v_report/r_asset_Equipment_traffic/r_asset_Equipment_traffic_report_adm', $data);
			}
			else
			{
				$this->load->view('v_asset/v_report/r_asset_Equipment_traffic/r_asset_Equipment_traffic_report', $data);
			}
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function r_mainten() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$data['h2_title']		= 'Asset Maintenance';
			$data['h3_title']		= 'report';
			$data['form_action'] 	= site_url('c_asset/c_asset_report/r_mainten_view/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_asset/v_report/r_mainten/r_mainten', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function r_mainten_view() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$data['title'] 		= $appName;
			$data['h2_title']	= 'Asset Maintenance';
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
			if($DefEmp_ID == 'H17050004765' or 'D15040004221')
			{
				$this->load->view('v_asset/v_report/r_mainten/r_mainten_report_adm', $data);
			}
			else
			{
				$this->load->view('v_asset/v_report/r_mainten/r_mainten_report', $data);
			}
		}
		else
		{
			redirect('__l1y');
		}
	}
	
}