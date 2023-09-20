<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class __180c2f extends CI_Controller
{
  	function __construct() // GOOD
	{
		parent::__construct();

		$this->load->model('m_gl/m_coa/m_coa', '', TRUE);
	}
	
	public function index()
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$Emp_ID 	= $this->session->userdata['Emp_ID'];
			
			$this->load->model('menu_model', '', TRUE);
			$data['h2_title'] 	= 'Dashboard';
			$data['h3_title'] 	= 'Dashboard';

			$LangID 			= $this->session->userdata['LangID'];
			$data['defNm'] 		= "Dashboard";
			$data['defMn'] 		= "";
			$data['DCode'] 		= "";
			if(isset($_GET['urlID']))
			{
				$urlID	= $_GET['urlID'];
				$urlID	= $this->url_encryption_helper->decode_url($urlID);
				$urlIDx	= explode('~', $urlID);	
				$curlID	= count($urlIDx);

				if($curlID == 2)
				{
					$urlID	= $urlIDx[0];
					$DCode	= $urlIDx[1];	// Doc. Code
					
					if($urlID == 'thR3q')
					{
						if($LangID == 'IND')
						{
							$data['defNm'] 	= "Tinjau Permintaan";
						}
						else
						{
							$data['defNm'] 	= "Request View";
						}

						$data['defMn'] 	= "c_help/c_t180c2hr/t180c2htread/?id=".$DCode;
						$data['DCode'] 	= $DCode;
					}
				}
				else
				{
					if($urlID == 'pr0fVw')
					{
						if($LangID == 'IND')
						{
							$data['defNm'] 	= "Profil";
						}
						else
						{
							$data['defNm'] 	= "Profile";
						}

						$data['defMn'] 	= "c_setting/c_profile/viewMyProfile/?id=";
					}
					elseif($urlID == 'fRnLst')
					{
						if($LangID == 'IND')
						{
							$data['defNm'] 	= "Daftar Pengguna";
						}
						else
						{
							$data['defNm'] 	= "User List";
						}

						$data['defMn'] 	= "c_setting/c_profile/index1/?id=";
					}
					elseif($urlID == 't45kLst')
					{
						if($LangID == 'IND')
						{
							$data['defNm'] 	= "Pertolongan";
						}
						else
						{
							$data['defNm'] 	= "Request List";
						}

						$data['defMn'] 	= "c_help/c_t180c2hr/?id=";
					}
					elseif($urlID == 'eMLst')
					{
						if($LangID == 'IND')
						{
							$data['defNm'] 	= "Surat Menyurat";
						}
						else
						{
							$data['defNm'] 	= "Mailing List";
						}

						$data['defMn'] 	= "_eM/efR/?id=";
					}
					else
					{
						$data['defNm'] 	= "Dashboard";
						$data['defMn'] 	= "__180c2f/dahsBoard/?id=";
						
						// START : TEMPORARY
							/*if($Emp_ID != 'D15040004221')
								$data['defMn'] 	= "__180c2f/underconstruction/?id=";*/
						// END : TEMPORARY
					}
				}
			}
			else
			{
				$data['defNm'] 	= "Dashboard";
				$data['defMn'] 	= "__180c2f/dahsBoard/?id=";
						
				// START : TEMPORARY
					/*if($Emp_ID != 'D15040004221')
						$data['defMn'] 	= "__180c2f/underconstruction/?id=";*/
				// END : TEMPORARY
			}

			//$this->load->view('dashboard1', $data);
			$this->load->view('dashboard_iframe', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function underconstruction()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			$username 				= $this->session->userdata('username');
			
			$data['title'] 			= $appName;
			$data['username'] 		= $username;
			$data['appName'] 		= $appName;
			$data['h2_title'] 		= 'Page Not Found';
			
			//$this->load->view('blank', $data);
			$this->load->view('dashboard1_uc', $data);
		}
		else
		{
			redirect('login');
		}
	}

	function dahsBoard()
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('menu_model', '', TRUE);
			$data['h2_title'] 		= 'Dashboard';
			$data['h3_title'] 		= 'Dashboard';
				
			$this->load->view('dashboard1', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function dahsBoard_uc()
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('menu_model', '', TRUE);
			$data['h2_title'] 		= 'Dashboard';
			$data['h3_title'] 		= 'Dashboard';
				
			$this->load->view('dashboard1_uc', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function prjlist($offset=0)
	{
		$this->load->model('m_finance/m_outapprove/m_outapprove', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['h2_title'] 		= 'Project List';
			$data['h3_title'] 		= 'Dashboard';
			$data['moffset'] 		= $offset;
			$data['perpage'] 		= 20;
			$data['theoffset']		= 0;
			
			$num_rows 				= $this->m_outapprove->count_all_project($DefEmp_ID);
			$data["recordcount"] 	= $num_rows;
			$config 				= array();
			$config["total_rows"] 	= $num_rows;
			$config["per_page"] 	= 20;
			$data['vewproject'] 	= $this->m_outapprove->get_last_ten_project($config["per_page"], $offset, $DefEmp_ID)->result();
			
			$this->load->view('v_finance/v_outapprove/project_list', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function aboutcomp()
	{
		$DefEmp_ID 					= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$comp_name	= '';
			$sqlApp 	= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$comp_name = $therow->comp_name;		
			endforeach;
		
			$data['h2_title'] 		= 'About Company';
			$data['h3_title'] 		= $comp_name;
			
			$this->load->view('aboutcomp', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function crtLogV()
	{
		$vers   = $this->session->userdata['vers'];
		$DNOW	= date('Y-m-d H:i:s');
		$Emp_ID	= $_GET['id'];
		$Emp_ID	= $this->url_encryption_helper->decode_url($Emp_ID);
		
		$comp_name	= '';
		$insCLogV 	= "INSERT INTO tbl_emp_vers (EMP_ID, VERS, DATE, STATUS) VALUES ('$Emp_ID', '$vers', '$DNOW', 1)";
		$this->db->query($insCLogV);
	}
	
	function shwLstDoc() // OK
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$data['prjC'] 	= $_GET['prjC'];
			$collData1 		= $_GET['theTbl'];
			$collData		= explode('~', $collData1);
			$data['theTbl'] = $collData[0];
			$data['fldCd'] 	= $collData[1];
			$data['fldDt']	= $collData[2];
			$data['fldNt'] 	= $collData[3];
			$data['theStt'] = $collData[4];
			$data['theTyp'] = $collData[5];
					
			$this->load->view('shwLstDoc', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
}
