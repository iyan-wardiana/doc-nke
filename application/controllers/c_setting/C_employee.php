<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 20 Februari 2017
 * File Name	= C_employee.php
 * Location		= -
*/
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_employee extends CI_Controller
{
 	function index()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_setting/c_employee/index1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function index1()
	{
		$this->load->model('m_setting/m_employee/m_employee', '', TRUE);
		
		$idAppName	= $_GET['id'];
		$appName	= $this->url_encryption_helper->decode_url($idAppName);
					
		$data['title'] 			= $appName;
		$data['h2_title'] 		= 'Employee Data';
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		
		$data["MenuCode"] 		= 'MN093';
		
		if ($this->session->userdata('login') == TRUE)
		{
			// START : PAGINATION - INDEX
				$key		= '';
				$page		= $this->input->get('per_page');
				$search		= array('Emp_ID'=> $key, 'First_Name'=> $key, 'Last_Name'=> $key);
				$siteURL	= site_url('c_setting/c_employee/data_search/?id='.$this->url_encryption_helper->encode_url($appName));
				$baseURL	= base_url() . 'index.php/c_setting/c_employee/index1/?id=';
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
			$this->load->view('v_setting/v_employee/employee', $data);
		}
		else
		{
			redirect('__l1y');
		}
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
		$data["MenuCode"] 	= 'MN093';	
		
		if ($this->session->userdata('login') == TRUE)
		{
			// START : PAGINATION - SEARCH
				$key		= $this->input->post('key');
				$page		= $this->input->get('per_page');
				$search		= array('Emp_ID'=> $key, 'First_Name'=> $key, 'Last_Name'=> $key);
				$siteURL	= site_url('c_setting/c_employee/data_search/?id='.$this->url_encryption_helper->encode_url($appName));
				$baseURL	= base_url() . 'index.php/c_setting/c_employee/index1/?id=';
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
			
			$this->load->view('v_setting/v_employee/employee', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function add()
	{
		$this->load->model('m_setting/m_employee/m_employee', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			$data['showSetting']	= 1;
			$data['form_action']	= site_url('c_setting/c_employee/add_process');
			$data['backURL'] 		= site_url('c_setting/c_employee/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$MenuCode 				= 'MN093';
			$data["MenuCode"] 		= 'MN093';
			$data['viewDocPattern'] = $this->m_employee->getDataDocPat($MenuCode)->result();
			
			$data['GolC'] 			= $this->m_employee->getCount_gol();		
			$data['GetGol'] 		= $this->m_employee->get_gol()->result();
			
			$data['PositionC'] 		= $this->m_employee->getCount_position();		
			$data['GetPosition'] 	= $this->m_employee->get_position()->result();
			
			$this->load->view('v_setting/v_employee/employee_form', $data);
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
		$this->load->model('m_setting/m_employee/m_employee', '', TRUE);
		
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
										
										'log_username'		=> $this->input->post('log_username'),
										'log_passHint'		=> $this->input->post('log_passHint'),
										'log_password'		=> $PassEncryp,
										'writeEMP'			=> 1,
										'editEMP'			=> 1,
										'readEMP'			=> 1);
						
			$employeeCp				= array('NK' 	=> $this->input->post('Emp_ID'),
										'U'			=> $this->input->post('log_username'),
										'P'			=> $this->input->post('log_password'));
						
			$employeeImg			= array('imgemp_empid' 	=> $this->input->post('Emp_ID'),
										'imgemp_filename'	=> 'username',
										'imgemp_filenameX'	=> 'username.jpg');
			
			// SAVE EMPLOYEE
			$this->m_employee->add($employee);
			
			// SAVE PASSWORD
			$this->m_employee->add2($employeeCp);
			
			// SAVE EMPLOYEE IMG
			$this->m_employee->add3($employeeImg);
			
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
			
			redirect('c_setting/c_employee/');
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update()
	{
		$this->load->model('m_setting/m_employee/m_employee', '', TRUE);
		
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
			$data['form_action']	= site_url('c_setting/c_employee/add_process');
			$data['backURL'] 		= site_url('c_setting/c_employee/?id='.$this->url_encryption_helper->encode_url($appName));			
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			$UserEmpDeptCode		= $this->session->userdata['Emp_DeptCode'];
			$data['Emp_ID']			= $Emp_ID;
			
			$MenuCode 				= 'MN093';
			$data["MenuCode"] 		= 'MN093';
			$data['viewDocPattern'] = $this->m_employee->getDataDocPat($MenuCode)->result();
			
			$data['GolC'] 			= $this->m_employee->getCount_gol();		
			$data['GetGol'] 		= $this->m_employee->get_gol()->result();
			
			$data['PositionC'] 		= $this->m_employee->getCount_position();		
			$data['GetPosition'] 	= $this->m_employee->get_position()->result();
			
			$this->load->view('v_setting/v_employee/employee_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process()
	{
		$this->load->model('m_setting/m_employee/m_employee', '', TRUE);

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
			$data['main_view'] 		= 'v_setting/v_employee/employee_form';
			$data['form_action']	= site_url('c_setting/c_employee/update_process');
			$data['link'] 			= array('link_back' => anchor('c_setting/c_employee/','<input type="button" name="btnCancel" id="btnCancel" value="Cancel" class="btn btn-danger" />', array('style' => 'text-decoration: none;')));
					
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
						
			$employeeCp				= array('NK' 	=> $this->input->post('Emp_ID'),
										'U'			=> $this->input->post('log_username'),
										'P'			=> $this->input->post('log_password'));
			// SAVE UPDATE PASSWORD	
			$this->m_employee->update2($this->input->post('Emp_ID'), $employeeCp);
										
			$employeeImg			= array('imgemp_empid' 	=> $this->input->post('Emp_ID'),
										'imgemp_filename'	=> $this->input->post('FileName'),
										'imgemp_filenameX'	=> $this->input->post('userfile'));
					
			$this->m_employee->update($this->input->post('Emp_ID'), $employee);
					
			$this->m_employee->update2($this->input->post('Emp_ID'), $employeeCp);
			
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
					redirect('Auth', 'refresh');
				}
			}
			redirect('c_setting/c_employee/');
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function do_upload()
	{ 
		$this->load->model('m_setting/m_employee/m_employee', '', TRUE);
		$Emp_ID 					= $this->input->post('Emp_ID');
		
		$data['task'] 				= 'add';
		$data['h2_title']			= 'Add Employee';
		$data['main_view'] 			= 'v_setting/v_employee/employee_form';
		
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
         $this->load->view('v_setting/v_employee/employee_form', $data);
	}

	function employee_authorization($offset=0)
	{
		$this->load->model('m_setting/m_employee/m_employee', '', TRUE);
		
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
			$data["MenuCode"] 		= 'MN093';
			$data['form_action']	= site_url('c_setting/c_employee/employee_authorization_process');
			//$data['link'] 			= array('link_back' => anchor('c_setting/c_employee/','<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));	
	 		$data['backURL'] 	= site_url('c_setting/c_employee/');
			
			$data['viewallmenu'] = $this->m_employee->get_allmenu($Emp_ID, $offset)->result();
				
			$this->load->view('v_setting/v_employee/employee_auth_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function employee_authorization_process()
	{
		$this->load->model('m_setting/m_employee/m_employee', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();
			$Emp_ID1	= $this->input->post('Emp_ID1');
			
			$this->m_employee->deleteAuthEmp($this->input->post('Emp_ID1'));		
			
			foreach($_POST['data'] as $d)
			{
				$menu_code = $d['menu_code'];
				$chkDetail = $d['isChkDetail'];
				if($chkDetail > 0)
				{
					$this->db->insert('tusermenu',$d);				
				}
			}
			//return false;
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
			
			$url			= site_url('c_setting/c_employee/');
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function employee_project($offset=0)
	{
		$this->load->model('m_setting/m_employee/m_employee', '', TRUE);
		
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
			$data["MenuCode"] 		= 'MN093';
			$data['form_action']	= site_url('c_setting/c_employee/employee_project_process');
			//$data['main_view'] 		= 'v_setting/v_employee/employee_setproj_form';	
			//$data['link'] 			= array('link_back' => anchor('c_setting/c_employee/','<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));	
	 		$data['backURL'] 		= site_url('c_setting/c_employee/');
			
			$data['viewallproject'] = $this->m_employee->get_allproject($Emp_ID, $offset)->result();
				
			$this->load->view('v_setting/v_employee/employee_setproj_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function employee_project_process()
	{
		$this->load->model('m_setting/m_employee/m_employee', '', TRUE);
			
		ob_start();
		$this->db->trans_begin();
		
		$this->m_employee->deleteEmpProjEmp($this->input->post('Emp_ID1'));	
		$empID	= $this->input->post('Emp_ID1');
		
		$packageelements=$_POST['packageelements'];
		if (count($packageelements)>0)
		{
			$mySelected=$_POST['packageelements'];
			foreach ($mySelected as $projCode)
			{
				$employeeProj = array('Emp_ID' => $empID, 'proj_Code' => $projCode);
				$this->m_employee->addEmpProj($employeeProj);
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
		
		redirect('c_setting/c_employee/');
	}

	function employee_dashboard($offset=0)
	{
		$this->load->model('m_setting/m_employee/m_employee', '', TRUE);
		
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
			$data["MenuCode"] 		= 'MN093';
			$data['form_action']	= site_url('c_setting/c_employee/employee_dashboard_process');
			//$data['link'] 			= array('link_back' => anchor('c_setting/c_employee/','<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));	
	 		$data['backURL'] 	= site_url('c_setting/c_employee/');
			
			$data['viewallproject'] = $this->m_employee->get_alldashboard($Emp_ID, $offset)->result();
				
			$this->load->view('v_setting/v_employee/employee_setdash_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function employee_dashboard_process()
	{
		$this->load->model('m_setting/m_employee/m_employee', '', TRUE);
			
		ob_start();
		$this->db->trans_begin();
		
		$this->m_employee->deleteEmpDashEmp($this->input->post('Emp_ID1'));	
		$empID	= $this->input->post('Emp_ID1');
		
		$packageelements	= $_POST['packageelements'];
		$Cpackageelements	= count($packageelements);
		
		//return false;
		if (count($packageelements)>0)
		{
			$mySelected	= $_POST['packageelements'];
			foreach ($mySelected as $DS_TYPE)
			{
				$employeeDash = array('EMP_ID' => $empID, 'DS_TYPE' => $DS_TYPE);
				$this->m_employee->addEmpDash($employeeDash);
			}
		}
		
		// HR DASHB
		$this->m_employee->deleteEmpDashEmpHR($this->input->post('Emp_ID1'));	
		$empID	= $this->input->post('Emp_ID1');
		
		$packageelements1	= $_POST['packageelements1'];
		$Cpackageelements1	= count($packageelements1);
		if (count($packageelements1)>0)
		{
			$mySelected	= $_POST['packageelements1'];
			foreach ($mySelected as $DS_TYPE)
			{
				$employeeDash = array('EMP_ID' => $empID, 'DS_TYPE' => $DS_TYPE);
				$this->m_employee->addEmpDashHR($employeeDash);
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
		
		redirect('c_setting/c_employee/');
	}

	function employee_auth_doc($offset=0)
	{
		$this->load->model('m_setting/m_employee/m_employee', '', TRUE);
		
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
			$data["MenuCode"] 		= 'MN093';
			$data['form_action']	= site_url('c_setting/c_employee/employee_authorization_process');
			$data['link'] 			= array('link_back' => anchor('c_setting/c_employee/','<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));	
	 		$data['backURL'] 		= site_url('c_setting/c_employee/');

			$data['viewalltype'] 	= $this->m_employee->get_all_doctype_list($Emp_ID, $offset)->result();
				
			$this->load->view('v_setting/v_employee/employee_setdoc_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function employee_docauth_process()
	{
		$this->load->model('m_setting/m_employee/m_employee', '', TRUE);
		
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
			
			$url			= site_url('c_setting/c_employee/');
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
}