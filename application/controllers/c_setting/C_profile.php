<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 16 Februari 2017
 * File Name	= C_profile.php
 * Location		= -
*/

class C_profile  extends CI_Controller
{
	var $limit = 2;
	var $title = 'NKE ITSys';
 	// Start : Index tiap halaman
 	public function index()
	{		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_setting/c_profile/index1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	public function index1($offset=0)
	{
		$this->load->model('m_setting/m_profile/m_profile', '', TRUE);	
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			//$Emp_ID	= $_GET['id'];
			//$Emp_ID	= $this->url_encryption_helper->decode_url($Emp_ID);
			$Emp_ID 			 	= $this->session->userdata('Emp_ID');

			$data['title'] 				= $appName;
			$data['h2_title'] 			= 'Profile';		
			
			$num_rows 					= $this->m_profile->count_all_num_rows($Emp_ID);
			$data["recordcount"] 		= $num_rows;
			
			// Start of Pagination
			$config 					= array();
			$config['base_url'] 		= site_url('c_setting/c_profile/index1');	
			$config["total_rows"] 		= $num_rows;
			$config["per_page"] 		= 20;
			$config["uri_segment"]		= 4;
			$config['cur_page'] 		= $offset;			
			$config['full_tag_open'] 	= '<ul class="tsc_pagination tsc_paginationA tsc_paginationA01">';
			$config['full_tag_close'] 	= '</ul>';
			$config['prev_link'] 		= '&lt;';
			$config['prev_tag_open']	= '<li>';
			$config['prev_tag_close'] 	= '</li>';
			$config['next_link'] 		= '&gt;';
			$config['next_tag_open'] 	= '<li>';
			$config['next_tag_close'] 	= '</li>';
			$config['cur_tag_open'] 	= '<li class="current"><a href="#">';
			$config['cur_tag_close'] 	= '</a></li>';
			$config['num_tag_open'] 	= '<li>';
			$config['num_tag_close'] 	= '</li>';			
			$config['first_tag_open'] 	= '<li>';
			$config['first_tag_close'] 	= '</li>';
			$config['last_tag_open'] 	= '<li>';
			$config['last_tag_close'] 	= '</li>';			
			$config['first_link'] 		= '&lt;&lt;';
			$config['last_link'] 		= '&gt;&gt;';
			$this->pagination->initialize($config);
			// End of Pagination
	 
			$data['viewEmployee'] 		= $this->m_profile->get_all_employee($Emp_ID)->result();
			$data["pagination"] 		= $this->pagination->create_links();	
			
			$this->load->view('v_setting/v_profile/profile', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	// End
	
	function viewEmployee()
	{
		$this->load->model('m_setting/m_profile/m_profile', '', TRUE);	
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$Emp_ID		= $_GET['id'];
			$Emp_ID		= $this->url_encryption_helper->decode_url($Emp_ID);
			
			$data['title'] 			= $appName;
			$data['h2_title'] 		= 'Profile';	
			$data['form_action']	= site_url('c_setting/c_profile/add_process');	
			$urlBacl				= site_url('c_setting/c_profile/index1/?id='.$this->url_encryption_helper->encode_url($Emp_ID));
			$data['link'] 			= array('link_back' => anchor("$urlBacl",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger btn-block" value="Back" />', array('style' => 'text-decoration: none;')));
			
			$num_rows 				= $this->m_profile->count_Emp_ID($Emp_ID);
			$data["recordcount"] 	= $num_rows;
			$data["Sel_Emp_ID"] 	= $Emp_ID;
	 
			//$data['viewEmployee'] 	= $this->m_profile->get_count_Emp_ID($Emp_ID)->result();
			$data["pagination"] 	= $this->pagination->create_links();	
			
			$this->load->view('v_setting/v_profile/profile_form_view', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function viewMyProfile()
	{
		$this->load->model('m_setting/m_profile/m_profile', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			//$Emp_ID				= $_GET['id'];
			//$Emp_ID				= $this->url_encryption_helper->decode_url($Emp_ID);
			$Emp_ID 			 	= $this->session->userdata('Emp_ID');

			$data['defMenu'] 		= site_url('c_setting/c_profile/viewMyProfile/?id=');

			$data['isUpdated'] 		= 0;
			$data['title'] 			= $appName;
			$data['h2_title'] 		= 'Profile';
			//$data['form_action']	= site_url('c_setting/c_profile/add_process');	
			$data['link'] 			= array('link_back' => anchor('c_setting/c_profile/','<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			$this->load->view('v_setting/v_profile/profile_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process()
	{
		date_default_timezone_set("Asia/Jakarta");
		$this->load->model('m_setting/m_profile/m_profile', '', TRUE);	
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
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
					
			$PassEncryp				= md5($this->input->post('log_password'));
			$Date_Of_Birth			= date('Y-m-d',strtotime($this->input->post('Date_Of_Birth')));
				
			$employee 				= array('Emp_ID' 		=> $this->input->post('Emp_ID'),
										'EmpNoIdentity'		=> $this->input->post('EmpNoIdentity'),
										'First_Name'		=> $this->input->post('First_Name'),
										'Last_Name'			=> $this->input->post('Last_Name'),
										'Birth_Place'		=> $this->input->post('Birth_Place'),
										'Date_Of_Birth'		=> $Date_Of_Birth,
										'gender'			=> $this->input->post('gender'),
										'Marital_Status'	=> $this->input->post('Marital_Status'),
										'Religion'			=> $this->input->post('Religion'),
										'Address1'			=> $this->input->post('Address1'),
										'Dept_Code'			=> $this->input->post('Dept_Code'),
										'city1'				=> $this->input->post('city1'),
										'country1'			=> $this->input->post('country1'),
										'State1'			=> $this->input->post('State1'),
										'Email'				=> $this->input->post('Email'),
										'Mobile_Phone'		=> $this->input->post('Mobile_Phone'),										
										'Emp_Location'		=> $this->input->post('Emp_Location'),										
										'Emp_Notes'			=> $this->input->post('Emp_Notes'),
										'log_username'		=> $this->input->post('log_username'),
										'log_password'		=> $PassEncryp);
						
			$employeeCp				= array('NK' 	=> $this->input->post('Emp_ID'),
										'U'			=> $this->input->post('log_username'),
										'P'			=> $this->input->post('log_password'),
										'UD'		=> date('Y-m-d H:i:s'));
					
			$this->m_profile->update($this->input->post('Emp_ID'), $employee);
					
			$this->m_profile->update2($this->input->post('Emp_ID'), $employeeCp);
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$data['isUpdated'] 		= 1;

			if($DefEmp_ID1 == $DefEmp_ID2)
			{
				if($username1 != $username2 || $password1 != $password2)
				{
					$this->session->sess_destroy();
					redirect('__l1y');
				}
			}
			
			$this->load->view('v_setting/v_profile/profile_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_processS()
	{
		date_default_timezone_set("Asia/Jakarta");
		$this->load->model('m_setting/m_profile/m_profile', '', TRUE);	
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$username1				= $this->session->userdata['username'];
		$password1				= $this->session->userdata['password'];
		$username2				= $_POST['log_username'];
		$password2				= $_POST['log_password'];
		$DefEmp_ID1 			= $this->session->userdata['Emp_ID'];
		$DefEmp_ID2 			= $_POST['Emp_ID'];
				
		$PassEncryp				= md5($_POST['log_password']);
		$Date_Of_Birth			= date('Y-m-d',strtotime($_POST['Date_Of_Birth']));
			
		$employee 				= array('Emp_ID' 		=> $_POST['Emp_ID'],
									'EmpNoIdentity'		=> $_POST['EmpNoIdentity'],
									'First_Name'		=> $_POST['First_Name'],
									'Last_Name'			=> $_POST['Last_Name'],
									'Birth_Place'		=> $_POST['Birth_Place'],
									'Date_Of_Birth'		=> $Date_Of_Birth,
									'gender'			=> $_POST['gender'],
									'Marital_Status'	=> $_POST['Marital_Status'],
									'Religion'			=> $_POST['Religion'],
									'Address1'			=> $_POST['Address1'],
									'Dept_Code'			=> $_POST['Dept_Code'],
									'city1'				=> $_POST['city1'],
									'country1'			=> $_POST['country1'],
									'State1'			=> $_POST['State1'],
									'Email'				=> $_POST['Email'],
									'Mobile_Phone'		=> $_POST['Mobile_Phone'],										
									'Emp_Location'		=> $_POST['Emp_Location'],										
									'Emp_Notes'			=> $_POST['Emp_Notes'],
									'log_username'		=> $_POST['log_username'],
									'log_password'		=> $PassEncryp);
					
		$employeeCp				= array('NK' 	=> $_POST['Emp_ID'],
									'U'			=> $_POST['log_username'],
									'P'			=> $_POST['log_password'],
									'UD'		=> date('Y-m-d H:i:s'));
				
		$this->m_profile->update($_POST['Emp_ID'], $employee);
				
		$this->m_profile->update2($_POST['Emp_ID'], $employeeCp);

		$this->session->sess_destroy();

		echo "1";
	}
	
	function do_upload()
	{ 
		$this->load->model('m_setting/m_profile/m_profile', '', TRUE);
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
		//$config['max_size']     	= 1000000; 
		//$config['max_width']    	= 10024; 
		//$config['max_height']    	= 10000;  
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
            $this->m_profile->updateProfPict($Emp_ID, $nameFile, $fileInpName);

            $updEMPImg 				= "UPDATE tbl_employee SET Emp_Image = '$nameFile' WHERE Emp_ID = '$Emp_ID'";
            $this->db->query($updEMPImg);
         }
         $this->load->view('v_setting/v_profile/profile_form', $data);
	}
}