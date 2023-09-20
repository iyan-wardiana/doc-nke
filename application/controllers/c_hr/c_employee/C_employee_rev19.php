<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 31 Oktober 2017
 * File Name	= C_employee.php
 * Location		= -
*/
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_employee extends CI_Controller
{
  	function __construct() // GOOD
	{
		parent::__construct();
		
		$this->load->model('m_hr/m_employee/m_employee', '', TRUE);
		$this->load->model('m_updash/m_updash', '', TRUE);
	
		$this->data['UserID'] 		= $this->session->userdata['Emp_ID'];
		$this->data['appName'] 		= $this->session->userdata['appName'];
		$this->data['ISCREATE'] 	= $this->session->userdata['ISCREATE'];
		$this->data['ISAPPROVE'] 	= $this->session->userdata['ISAPPROVE'];
		$this->data['LangID']		= $this->session->userdata['LangID'];
		
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
	}
	
 	function index()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_hr/c_employee/c_employee/iN4x_3Mp/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function iN4x_3Mp()
	{
		$this->load->model('m_hr/m_employee/m_employee', '', TRUE);
		
		$idAppName	= $_GET['id'];
		$appName	= $this->url_encryption_helper->decode_url($idAppName);
					
		$data['title'] 			= $appName;
		$data['h2_title'] 		= 'Employee Data';
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		
		$data["MenuCode"] 		= 'MN324';
		
		if ($this->session->userdata('login') == TRUE)
		{
			// START : PAGINATION - INDEX
				$key		= '';
				$page		= $this->input->get('per_page');
				$search		= array('Emp_ID'=> $key, 'First_Name'=> $key, 'Last_Name'=> $key);
				$siteURL	= site_url('c_hr/c_employee/c_employee/data_search/?id='.$this->url_encryption_helper->encode_url($appName));
				$baseURL	= base_url() . 'index.php/c_hr/c_employee/c_employee/iN4x_3Mp/?id=';
				$totalrows	= $this->m_employee->count_all_emp_src($search);
				
				$this->load->library('pagination');
				
				$batas		= 10;
				if(!$page):
				   $offset = 0;
				else:
				   $offset = $page;
				endif;
				
				$data['search_action']		= $siteURL;
				$config['page_query_string']= TRUE;
				$config['base_url'] 		= $baseURL;
				
				$config['total_rows'] 		= $totalrows;
				$config['per_page'] 		= $batas;
				$config['uri_segment'] 		= $page;
		 
				$config['full_tag_open'] 	= '<ul class="pagination">';
				$config['full_tag_close'] 	= '</ul>';
				$config['first_link'] 		= '&laquo; First';
				$config['first_tag_open'] 	= '<li class="prev page">';
				$config['first_tag_close'] 	= '</li>';
		 
				$config['last_link'] 		= 'Last &raquo;';
				$config['last_tag_open'] 	= '<li class="next page">';
				$config['last_tag_close'] 	= '</li>';
		 
				$config['next_link'] 		= 'Next &rarr;';
				$config['next_tag_open'] 	= '<li class="next page">';
				$config['next_tag_close'] 	= '</li>';
		 
				$config['prev_link']		= '&larr; Prev';
				$config['prev_tag_open'] 	= '<li class="prev page">';
				$config['prev_tag_close'] 	= '</li>';
		 
				$config['cur_tag_open'] 	= '<li class="current"><a href="">';
				$config['cur_tag_close'] 	= '</a></li>';
		 
				$config['num_tag_open'] 	= '<li class="page">';
				$config['num_tag_close'] 	= '</li>';
				$this->pagination->initialize($config);
				$data['paging']				= $this->pagination->create_links();
				$data['jlhpage']			= $page;
				$data['key']				= '';
			// END : PAGINATION - INDEX
			
			$data['viewdata'] 				= $this->m_employee->get_all_emp($batas, $offset);
			$this->load->view('v_hr/v_employee/employee', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

  	function get_AllData() // GOOD
	{
		$PRJCODE		= "";
		
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
			
			$columns_valid 	= array("JournalH_Id",
									"Manual_No", 
									"JournalH_Date", 
									"JournalH_Desc",
									"Journal_Amount");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_cproj_payment->get_AllDataC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_cproj_payment->get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$ID				= $dataI['ID'];  
				$Emp_ID			= $dataI['Emp_ID'];            
				$First_Name		= $dataI['First_Name'];
				$Middle_Name	= $dataI['Middle_Name'];
				$Last_Name		= $dataI['Last_Name'];
				$CompleteNm		= "$First_Name&nbsp;$Last_Name";
				$Birth_Place	= $dataI['Birth_Place'];
				$Date_Of_Birth	= $dataI['Date_Of_Birth'];
				$empSetting		= site_url('c_hr/c_employee/c_employee/i4x3mp_4p4/?id='.$this->url_encryption_helper->encode_url($Emp_ID));
				$Gol_Code		= $dataI['Gol_Code'];
				$EMPG_RANK		= '';
				$sqlGol			= "SELECT EMPG_CODE, EMPG_RANK FROM tbl_employee_gol WHERE EMPG_CHILD = '$Gol_Code' LIMIT 1";
				$resGol			= $this->db->query($sqlGol)->result();
				foreach($resGol as $rowGol) :
					$EMPG_CODE 	= $rowGol->EMPG_CODE;
					$EMPG_RANK 	= $rowGol->EMPG_RANK;
				endforeach;
						
				$POSF_CODE		= $dataI['Pos_Code'];
				$POSF_PARENT	= '';
				$POSF_NAME 		= '';
				$sqlPos			= "SELECT POSF_PARENT, POSF_CODE, POSF_NAME
									FROM tbl_position_func WHERE POSF_CODE = '$POSF_CODE' LIMIT 1";
				$resPos			= $this->db->query($sqlPos)->result();
				foreach($resPos as $rowPos) :
					$POSF_PARENT= $rowPos->POSF_PARENT;
					$POSF_CODE 	= $rowPos->POSF_CODE;
					$POSF_NAME 	= $rowPos->POSF_NAME;
				endforeach;
				
				$POSF_PARENT	= $POSF_PARENT;
				$POSS_NAME 		= '';
				$sqlPos			= "SELECT POSS_CODE, POSS_NAME
									FROM tbl_position_str WHERE POSS_CODE = '$POSF_PARENT' LIMIT 1";
				$resPos			= $this->db->query($sqlPos)->result();
				foreach($resPos as $rowPos) :
					$POSS_CODE 	= $rowPos->POSS_CODE;
					$POSS_NAME 	= $rowPos->POSS_NAME;
				endforeach;
				
				$empUpdate		= site_url('c_hr/c_employee/c_employee/i4x3mp_4p4/?id='.$this->url_encryption_helper->encode_url($Emp_ID));
				$empProject		= site_url('c_hr/c_employee/c_employee/employee_project/?id='.$this->url_encryption_helper->encode_url($Emp_ID));
				$empAuthorize	= site_url('c_hr/c_employee/c_employee/employee_authorization/?id='.$this->url_encryption_helper->encode_url($Emp_ID));
				$secDashURL		= site_url('c_hr/c_employee/c_employee/employee_dashboard/?id='.$this->url_encryption_helper->encode_url($Emp_ID));
				$secDocURL		= site_url('c_hr/c_employee/c_employee/employee_auth_doc/?id='.$this->url_encryption_helper->encode_url($Emp_ID));
				$secPrint1		= site_url('c_finance/c_cpa70d18/printdocument/?id='.$this->url_encryption_helper->encode_url($Emp_ID));
				
				$secPrint		= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint1."'>
								   <label style='white-space:nowrap'>
								   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   </a>
								   <a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									</label>";
								
				$output['data'][] = array("$noU.",
										  anchor("$empSetting",$Emp_ID,array('class' => 'update')).' ',
										  "<div style='white-space:nowrap'>$Manual_No</div>",
										  $JournalH_DateV,
										  $dataI['JournalH_Desc'],
										  $Journal_Amount,
										  $empName,
										  "<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
										  $secPrint);
				$noU			= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function data_search()
	{
		$this->load->model('m_setting/m_employee/m_employee', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$idAppName	= $_GET['id'];
		$appName	= $this->url_encryption_helper->decode_url($idAppName);
		
		$data['title'] 		= $appName;
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
		$data["MenuCode"] 	= 'MN324';	
		
		if ($this->session->userdata('login') == TRUE)
		{
			// START : PAGINATION - SEARCH
				$key		= $this->input->post('key');
				$page		= $this->input->get('per_page');
				$search		= array('Emp_ID'=> $key, 'First_Name'=> $key, 'Last_Name'=> $key);
				$siteURL	= site_url('c_hr/c_employee/c_employee/data_search/?id='.$this->url_encryption_helper->encode_url($appName));
				$baseURL	= base_url() . 'index.php/c_hr/c_employee/c_employee/iN4x_3Mp/?id=';
				$totalrows	= $this->m_employee->count_all_emp_src($search);
				
				$this->load->library('pagination');
				
				$batas		= 10;
				if(!$page):
				   $offset = 0;
				else:
				   $offset = $page;
				endif;
				
				$data['search_action']		= $siteURL;
				$config['page_query_string']= TRUE;
				$config['base_url'] 		= $baseURL;
				
				$config['total_rows'] 		= $this->m_employee->count_all_emp_src($search);
				$config['per_page'] 		= $batas;
				$config['uri_segment'] 		= $page;
		 
				$config['full_tag_open'] 	= '<ul class="pagination">';
				$config['full_tag_close'] 	= '</ul>';
				$config['first_link'] 		= '&laquo; First';
				$config['first_tag_open'] 	= '<li class="prev page">';
				$config['first_tag_close'] 	= '</li>';
		 
				$config['last_link'] 		= 'Last &raquo;';
				$config['last_tag_open'] 	= '<li class="next page">';
				$config['last_tag_close'] 	= '</li>';
		 
				$config['next_link'] 		= 'Next &rarr;';
				$config['next_tag_open'] 	= '<li class="next page">';
				$config['next_tag_close'] 	= '</li>';
		 
				$config['prev_link']		= '&larr; Prev';
				$config['prev_tag_open'] 	= '<li class="prev page">';
				$config['prev_tag_close'] 	= '</li>';
		 
				$config['cur_tag_open'] 	= '<li class="current"><a href="">';
				$config['cur_tag_close'] 	= '</a></li>';
		 
				$config['num_tag_open'] 	= '<li class="page">';
				$config['num_tag_close'] 	= '</li>';
				$this->pagination->initialize($config);
				$data['paging']				= $this->pagination->create_links();
				$data['jlhpage']			= $page;
				$data['key']				= $key;
			// START : PAGINATION - SEARCH
			
			$data['viewdata'] 		= $this->m_employee->get_all_emp($batas,$offset,$search);
			
			$this->load->view('v_hr/v_employee/employee', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function add()
	{
		$this->load->model('m_hr/m_employee/m_employee', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			$data['showSetting']	= 1;
			$data['form_action']	= site_url('c_hr/c_employee/c_employee/add_process');
			$data['backURL'] 		= site_url('c_hr/c_employee/c_employee/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$MenuCode 				= 'MN324';
			$data["MenuCode"] 		= 'MN324';
			$data['viewDocPattern'] = $this->m_employee->getDataDocPat($MenuCode)->result();
			
			// GET TOTAL
			/*$data['TotACT'] 	= $this->m_employee->getCount_ACT();
			$data['TotNACT'] 	= $this->m_employee->getCount_NACT();
			$data['TotNEW'] 	= $this->m_employee->getCount_NEW();
			$data['TotBOD'] 	= $this->m_employee->getCount_BOD();
			$data['TotGM'] 		= $this->m_employee->getCount_GM();
			$data['TotMNG'] 	= $this->m_employee->getCount_MNG();
			$data['TotKEPU'] 	= $this->m_employee->getCount_KEPU();
			$data['TotPM'] 		= $this->m_employee->getCount_PM();
			$data['TotKU'] 		= $this->m_employee->getCount_KU();
			$data['TotSM'] 		= $this->m_employee->getCount_SM();
			$data['TotSPEC'] 	= $this->m_employee->getCount_SPEC();
			$data['TotSTF'] 	= $this->m_employee->getCount_STF();
			$data['TotNSTF'] 	= $this->m_employee->getCount_NSTF();*/
			
			$data['PositionC'] 		= $this->m_employee->getCount_position();		
			$data['GetPosition'] 	= $this->m_employee->get_position()->result();
			
			$this->load->view('v_hr/v_employee/employee_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function getAge($newDate)
	{
		$today 		= new DateTime('today');
		$splitCode 	= explode("~", $newDate);
		$month		= $splitCode[0];
		$day		= $splitCode[1];
		$year		= $splitCode[2];
		$birthDate	= "$year-$month-$day";
		$birthDt 	= new DateTime($birthDate);
		$y 			= $today->diff($birthDt)->y;
		echo $y;
	}
	
	function add_process() // USE
	{
		date_default_timezone_set("Asia/Jakarta");
		$this->load->model('m_hr/m_employee/m_employee', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();
			
			$PassEncryp		= md5($this->input->post('log_password'));
			$Date_Of_Birth	= date('Y-m-d',strtotime($this->input->post('Date_Of_Birth')));
			$Emp_ID			= $this->input->post('Emp_ID');
			$First_Name		= $this->input->post('First_Name');
			$Last_Name		= $this->input->post('Last_Name');
			$compName		= "$First_Name $Last_Name";
			$theUsername	= $this->input->post('log_username');
			$thePassword	= $this->input->post('log_password');
			
			$EmployeeStatus	= $this->input->post('EmployeeStatus');
			$TaxStstus		= $this->input->post('TaxStstus');
			
			//echo $EmployeeStatus;
			//return false;
			
			$EMAIL 			= $this->input->post('Email');
				
			$employee 			= array('Emp_ID' 			=> $this->input->post('Emp_ID'),
										'Gol_Code'			=> $this->input->post('Gol_Code'),
										'Pos_Code'			=> $this->input->post('Pos_Code'),
										'EmpNoIdentity'		=> $this->input->post('EmpNoIdentity'),
										'First_Name'		=> $this->input->post('First_Name'),
										'Middle_Name'		=> $this->input->post('Middle_Name'),
										'Last_Name'			=> $this->input->post('Last_Name'),
										'Birth_Place'		=> $this->input->post('Birth_Place'),
										'Date_Of_Birth'		=> $Date_Of_Birth,
										'gender'			=> $this->input->post('gender'),
										'Religion'			=> $this->input->post('Religion'),
										'Marital_Status'	=> $this->input->post('Marital_Status'),
										'Email		'		=> $this->input->post('Email'),
										'Mobile_Phone'		=> $this->input->post('Mobile_Phone'),
										'Address1'			=> $this->input->post('Address1'),
										'city1'				=> $this->input->post('city1'),
										'country1'			=> $this->input->post('country1'),
										'State1'			=> $this->input->post('State1'),										
										'Emp_Location'		=> $this->input->post('Emp_Location'),
										'Emp_Status'		=> $this->input->post('Employee_status'),
										'Employee_status'	=> $this->input->post('Employee_status'),
										'FlagUSER'			=> $this->input->post('FlagUSER'),
										//'Emp_DeptCode'		=> $this->input->post('POS_CODE'),
										'Emp_Status'		=> $EmployeeStatus,
										'Tax_Status'		=> $TaxStstus,
										
										'log_username'		=> $this->input->post('log_username'),
										'log_passHint'		=> $this->input->post('log_passHint'),
										'log_password'		=> $PassEncryp,
										'writeEMP'			=> 1,
										'editEMP'			=> 1,
										'readEMP'			=> 1);
						
			$employeeCp				= array('NK' 	=> $this->input->post('Emp_ID'),
										'U'			=> $this->input->post('log_username'),
										'P'			=> $this->input->post('log_password'),
										'UD'		=> date('Y-m-d H:i:s'));
						
			$employeeImg			= array('imgemp_empid' 	=> $this->input->post('Emp_ID'),
										'imgemp_filename'	=> 'username',
										'imgemp_filenameX'	=> 'username.jpg');
						
			$employeePRJ			= array('Emp_ID' 	=> $this->input->post('Emp_ID'),
										'proj_Code'		=> 'KTR');
			
			// SAVE EMPLOYEE
			$this->m_employee->add($employee);
			
			// SAVE PASSWORD
			$this->m_employee->add2($employeeCp);
			
			// SAVE EMPLOYEE IMG
			$this->m_employee->add3($employeeImg);
			
			// SAVE EMPLOYEE PROJECT
			$this->m_employee->add4($employeePRJ);
			
			// SAVE TO DASHBOARD
			$this->m_employee->updateDash();
			
			// END MAIL
			$this->m_employee->sendMail($Emp_ID, $compName, $theUsername, $thePassword, $EMAIL);
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			redirect('c_hr/c_employee/c_employee/');
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function i4x3mp_4p4()
	{
		$this->load->model('m_hr/m_employee/m_employee', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$Emp_ID		= $_GET['id'];
		$Emp_ID		= $this->url_encryption_helper->decode_url($Emp_ID);
		
		if ($this->session->userdata('login') == TRUE)
		{
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['showSetting']	= 1;
			$data['form_action']	= site_url('c_hr/c_employee/c_employee/update_process');
			$data['backURL'] 		= site_url('c_hr/c_employee/c_employee/?id='.$this->url_encryption_helper->encode_url($appName));			
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			$UserEmpDeptCode		= $this->session->userdata['Emp_DeptCode'];
			$data['Emp_ID']			= $Emp_ID;
			
			$MenuCode 				= 'MN324';
			$data["MenuCode"] 		= 'MN324';
			$data['viewDocPattern'] = $this->m_employee->getDataDocPat($MenuCode)->result();
			
			// GET TOTAL
			/*$data['TotACT'] 	= $this->m_employee->getCount_ACT();
			$data['TotNACT'] 	= $this->m_employee->getCount_NACT();
			$data['TotNEW'] 	= $this->m_employee->getCount_NEW();
			$data['TotBOD'] 	= $this->m_employee->getCount_BOD();
			$data['TotGM'] 		= $this->m_employee->getCount_GM();
			$data['TotMNG'] 	= $this->m_employee->getCount_MNG();
			$data['TotKEPU'] 	= $this->m_employee->getCount_KEPU();
			$data['TotPM'] 		= $this->m_employee->getCount_PM();
			$data['TotKU'] 		= $this->m_employee->getCount_KU();
			$data['TotSM'] 		= $this->m_employee->getCount_SM();
			$data['TotSPEC'] 	= $this->m_employee->getCount_SPEC();
			$data['TotSTF'] 	= $this->m_employee->getCount_STF();
			$data['TotNSTF'] 	= $this->m_employee->getCount_NSTF();*/
			
			$data['GolC'] 			= $this->m_employee->getCount_gol();		
			$data['GetGol'] 		= $this->m_employee->get_gol()->result();
			
			$data['PositionC'] 		= $this->m_employee->getCount_position();		
			$data['GetPosition'] 	= $this->m_employee->get_position()->result();
			
			$this->load->view('v_hr/v_employee/employee_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process()
	{
		date_default_timezone_set("Asia/Jakarta");
		$this->load->model('m_hr/m_employee/m_employee', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();
			
			$username1				= $this->session->userdata['username'];
			$password1				= $this->session->userdata['password'];
			$username2				= $this->input->post('log_username');
			$password2				= $this->input->post('log_password');
			$DefEmp_ID1 			= $this->session->userdata['Emp_ID'];
			$DefEmp_ID2 			= $this->input->post('Emp_ID');
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title'] 		= 'Employee Data | Update Employee Data';
			$data['main_view'] 		= 'v_hr/v_employee/employee_form';
			$data['form_action']	= site_url('c_hr/c_employee/c_employee/update_process');
			$data['link'] 			= array('link_back' => anchor('c_hr/c_employee/c_employee/','<input type="button" name="btnCancel" id="btnCancel" value="Cancel" class="btn btn-danger" />', array('style' => 'text-decoration: none;')));
					
			$PassEncryp				= md5($this->input->post('log_password'));
			$Date_Of_Birth			= date('Y-m-d',strtotime($this->input->post('Date_Of_Birth')));
				
			$employee 				= array('Emp_ID' 			=> $this->input->post('Emp_ID'),
											'Gol_Code'			=> $this->input->post('Gol_Code'),
											'Pos_Code'			=> $this->input->post('Pos_Code'),
											'EmpNoIdentity'		=> $this->input->post('EmpNoIdentity'),
											'First_Name'		=> $this->input->post('First_Name'),
											'Middle_Name'		=> $this->input->post('Middle_Name'),
											'Last_Name'			=> $this->input->post('Last_Name'),
											'Birth_Place'		=> $this->input->post('Birth_Place'),
											'Date_Of_Birth'		=> $Date_Of_Birth,
											'gender'			=> $this->input->post('gender'),
											'Religion'			=> $this->input->post('Religion'),
											'Marital_Status'	=> $this->input->post('Marital_Status'),
											'Email'				=> $this->input->post('Email'),
											'Mobile_Phone'		=> $this->input->post('Mobile_Phone'),
											'Address1'			=> $this->input->post('Address1'),
											
											'Emp_Location'		=> $this->input->post('Emp_Location'),
											'Emp_Status'		=> $this->input->post('Employee_status'),
											'Employee_status'	=> $this->input->post('Employee_status'),
											'FlagUSER'			=> $this->input->post('FlagUSER'),
											'Emp_DeptCode'		=> $this->input->post('POS_CODE'),
											
											'log_username'		=> $this->input->post('log_username'),
											'log_passHint'		=> $this->input->post('log_passHint'),
											'log_password'		=> $PassEncryp,
											'writeEMP'			=> $this->input->post('writeEMP'),
											'editEMP'			=> $this->input->post('editEMP'),
											'readEMP'			=> $this->input->post('readEMP'));
			// SAVE UPDATE EMPLOYEE	
			$this->m_employee->update($this->input->post('Emp_ID'), $employee);
						
			$employeeCp				= array('NK'	=> $this->input->post('Emp_ID'),
										'U'			=> $this->input->post('log_username'),
										'P'			=> $this->input->post('log_password'),
										'UD'		=> date('Y-m-d H:i:s'));
			// SAVE UPDATE PASSWORD	
			$this->m_employee->update2($this->input->post('Emp_ID'), $employeeCp);
										
			$employeeImg			= array('imgemp_empid' 	=> $this->input->post('Emp_ID'),
										'imgemp_filename'	=> $this->input->post('FileName'),
										'imgemp_filenameX'	=> $this->input->post('userfile'));
			
			// SAVE TO DASHBOARD
			$this->m_employee->updateDash();
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			if($DefEmp_ID1 == $DefEmp_ID2)
			{
				if($username1 != $username2 || $password1 != $password2)
				{
					$this->session->sess_destroy();
					//redirect('Auth', 'refresh');
					redirect('__l1y', 'refresh');
				}
			}
			redirect('c_hr/c_employee/c_employee/');
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function do_upload()
	{ 
		$this->load->model('m_hr/m_employee/m_employee', '', TRUE);
		$Emp_ID 					= $this->input->post('Emp_ID');
		
		$data['task'] 				= 'add';
		$data['h2_title']			= 'Add Employee';
		$data['main_view'] 			= 'v_hr/v_employee/employee_form';
		
		// CEK FILE
        $file 						= $_FILES['userfile'];
		$nameFile					= $_FILES["userfile"]["name"];
		$ext 						= end((explode(".", $nameFile)));
       	$fileInpName 				= $this->input->post('FileName');
			
		if (!file_exists('assets/AdminLTE-2.0.5/emp_image/'.$Emp_ID))
		{
			mkdir('assets/AdminLTE-2.0.5/emp_image/'.$Emp_ID, 0777, true);
		}
		
		$file 						= $_FILES['userfile'];
		$file_name 					= $file['name'];
		$config['upload_path']   	= "assets/AdminLTE-2.0.5/emp_image/$Emp_ID/"; 
		$config['allowed_types']	= 'gif|jpg|png'; 
		$config['overwrite'] 		= TRUE;
		$config['max_size']     	= 1000000; 
		$config['max_width']    	= 10024; 
		$config['max_height']    	= 10000;  
		$config['file_name']       	= $file['name'];
		
        $this->load->library('upload', $config);
		
        if ( ! $this->upload->do_upload('userfile')) 
		{
			$data['Emp_ID']			= $Emp_ID;
			$data['task'] 			= 'edit';
         }
         else 
		 {
            $data['path']			= $file_name;
			$data['Emp_ID']			= $Emp_ID;
			$data['task'] 			= 'edit';
            $data['showSetting']	= 0;
            $this->m_employee->updateProfPict($Emp_ID, $nameFile, $fileInpName);
         }
         $this->load->view('v_hr/v_employee/employee_form', $data);
	}

	function employee_authorization($offset=0)
	{
		$this->load->model('m_hr/m_employee/m_employee', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$Emp_ID		= $_GET['id'];
		$Emp_ID		= $this->url_encryption_helper->decode_url($Emp_ID);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['Emp_ID'] 		= $Emp_ID;
			$data['title'] 			= $appName;
			$data['h2_title'] 		= 'Employee Authorization';
			$data["MenuCode"] 		= 'MN324';
			$data['form_action']	= site_url('c_hr/c_employee/c_employee/employee_authorization_process');
			$data['link'] 			= array('link_back' => anchor('c_hr/c_employee/c_employee/','<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));	
	 
			$data['viewallmenu'] = $this->m_employee->get_allmenu($Emp_ID, $offset)->result();
				
			$this->load->view('v_hr/v_employee/employee_auth_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function employee_authorization_process()
	{
		$this->load->model('m_hr/m_employee/m_employee', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();
			$Emp_ID1	= $this->input->post('Emp_ID1');
			
			$this->m_employee->deleteAuthEmp($this->input->post('Emp_ID1'));		
			
			foreach($_POST['data'] as $d)
			{
				$menu_code = $d['menu_code'];
				$chkDetail = $d['isChkDetail'];
				//echo "$chkDetail - $menu_code<br>";
				if($chkDetail > 0)
				{
					$this->db->insert('tusermenu',$d);				
				}
			}
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$url			= site_url('c_hr/c_employee/c_employee/');
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function employee_project($offset=0)
	{
		$this->load->model('m_hr/m_employee/m_employee', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$Emp_ID		= $_GET['id'];
		$Emp_ID		= $this->url_encryption_helper->decode_url($Emp_ID);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['Emp_ID'] 		= $Emp_ID;
			$data['title'] 			= $appName;
			$data['h2_title'] 		= 'Employees Project';
			$data["MenuCode"] 		= 'MN324';
			$data['form_action']	= site_url('c_hr/c_employee/c_employee/employee_project_process');
			//$data['main_view'] 		= 'v_hr/v_employee/employee_setproj_form';	
			$data['link'] 			= array('link_back' => anchor('c_hr/c_employee/c_employee/','<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));	
	 
			$data['viewallproject'] = $this->m_employee->get_allproject($Emp_ID, $offset)->result();
				
			$this->load->view('v_hr/v_employee/employee_setproj_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function employee_project_process()
	{
		$this->load->model('m_hr/m_employee/m_employee', '', TRUE);
			
		ob_start();
		$this->db->trans_begin();
		
		$this->m_employee->deleteEmpProjEmp($this->input->post('Emp_ID1'));	
		$empID	= $this->input->post('Emp_ID1');
		
		$packelementsPRJ	= $_POST['packageelements'];
		if (count($packelementsPRJ) > 0)
		{
			$mySelected = $_POST['packageelements'];
			foreach ($mySelected as $projCode)
			{
				$employeeProj = array('Emp_ID' => $empID, 'proj_Code' => $projCode);
				$this->m_employee->addEmpProj($employeeProj);
			}
		}
		
		$this->m_employee->deleteEmpAcc($this->input->post('Emp_ID1'));
			
		$packelementsACC	= $_POST['packageelementsCB'];
		if (count($packelementsACC) > 0)
		{
			$mySelectedCB = $_POST['packageelementsCB'];
			foreach ($mySelectedCB as $Acc_Numb)
			{				
				if (count($packelementsPRJ) > 0)
				{
					$mySelected = $_POST['packageelements'];
					foreach ($mySelected as $projCode)
					{
						$employeeAcc = array('Emp_ID' => $empID, 'PRJCODE' => $projCode, 'Acc_Number' => $Acc_Numb);
						$this->m_employee->addEmpAcc($employeeAcc);
					}
				}
			}
		}
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
		}
		else
		{
			$this->db->trans_commit();
		}
		
		redirect('c_hr/c_employee/c_employee/');
	}

	function employee_dashboard($offset=0)
	{
		$this->load->model('m_hr/m_employee/m_employee', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$Emp_ID		= $_GET['id'];
		$Emp_ID		= $this->url_encryption_helper->decode_url($Emp_ID);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['Emp_ID'] 		= $Emp_ID;
			$data['title'] 			= $appName;
			$data['h2_title'] 		= 'Employee\'s Dashboard';
			$data["MenuCode"] 		= 'MN324';
			$data['form_action']	= site_url('c_hr/c_employee/c_employee/employee_dashboard_process');
			$data['link'] 			= array('link_back' => anchor('c_hr/c_employee/c_employee/','<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));	
	 
			$data['viewallproject'] = $this->m_employee->get_alldashboard($Emp_ID, $offset)->result();
				
			$this->load->view('v_hr/v_employee/employee_setdash_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function employee_dashboard_process()
	{
		$this->load->model('m_hr/m_employee/m_employee', '', TRUE);
			
		ob_start();
		$this->db->trans_begin();
		
		$this->m_employee->deleteEmpDashEmp($this->input->post('Emp_ID1'));	
		$empID	= $this->input->post('Emp_ID1');
		
		$packageelements	= $_POST['packageelements'];
		$Cpackageelements	= count($packageelements);
		echo $Cpackageelements;
		//return false;
		if (count($packageelements)>0)
		{
			$mySelected	= $_POST['packageelements'];
			foreach ($mySelected as $DS_TYPE)
			{
				echo "$empID = $DS_TYPE<br>";
				$employeeDash = array('EMP_ID' => $empID, 'DS_TYPE' => $DS_TYPE);
				$this->m_employee->addEmpDash($employeeDash);
			}
		}
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
		}
		else
		{
			$this->db->trans_commit();
		}
		
		redirect('c_hr/c_employee/c_employee/');
	}

	function employee_auth_doc($offset=0)
	{
		$this->load->model('m_hr/m_employee/m_employee', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$Emp_ID		= $_GET['id'];
		$Emp_ID		= $this->url_encryption_helper->decode_url($Emp_ID);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['Emp_ID'] 		= $Emp_ID;
			$data['title'] 			= $appName;
			$data['h2_title'] 		= 'Employee\'s Document Authorization';
			$data["MenuCode"] 		= 'MN324';
			$data['form_action']	= site_url('c_hr/c_employee/c_employee/employee_authorization_process');
			$data['link'] 			= array('link_back' => anchor('c_hr/c_employee/c_employee/','<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));	
	 
			$data['viewalltype'] 	= $this->m_employee->get_all_doctype_list($Emp_ID, $offset)->result();
				
			$this->load->view('v_hr/v_employee/employee_setdoc_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function employee_docauth_process()
	{
		$this->load->model('m_hr/m_employee/m_employee', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();
			$Emp_ID1	= $this->input->post('Emp_ID1');
			
			$this->m_employee->deleteAccDocEmp($this->input->post('Emp_ID1'));		
			
			foreach($_POST['data'] as $d)
			{
				$chkDetail = $d['isChkDetail'];
				if($chkDetail > 0)
				{
					$this->db->insert('tbl_userdoctype',$d);
				}
			}
			
			$DAU_WRITE	= $this->input->post('DAU_WRITE');
				if($DAU_WRITE == '')
					$DAU_WRITE = 0;
			$DAU_READ	= $this->input->post('DAU_READ');
				if($DAU_READ == '')
					$DAU_READ = 0;
			$DAU_DL		= $this->input->post('DAU_DL');
				if($DAU_DL == '')
					$DAU_DL = 0;
			
			$this->m_employee->deleteAuthDocEmp($this->input->post('Emp_ID1'));	
						
			$INSAUTDOC	= array('DAU_EMPID' 	=> $Emp_ID1,
								'DAU_WRITE'		=> $DAU_WRITE,
								'DAU_READ'		=> $DAU_READ,
								'DAU_DL'		=> $DAU_DL);
								
			$this->m_employee->addEMPAUTH($INSAUTDOC);
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$url			= site_url('c_hr/c_employee/c_employee/');
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
		
	function getTheCode($log_username) // OK
	{ 	
		$this->load->model('m_hr/m_employee/m_employee', '', TRUE);
		$countUNCode 	= $this->m_employee->count_log_username($log_username);
		echo $countUNCode;
	}
}