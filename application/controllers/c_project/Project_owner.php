<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 9 Februari 2017
 * File Name	= Project_owner.php
 * Location		= -
*/

class Project_owner extends CI_Controller
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
		
		$url			= site_url('c_project/project_owner/index1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	public function index1($offset=0)
	{
		$this->load->model('m_project/m_project_owner/m_project_owner', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
					
			$data['title'] 				= $appName;
			$data['h2_title']			= 'Project Owner';
			$data['secAddURL'] 			= site_url('c_project/project_owner/add/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['srch_url'] 			= site_url('c_project/project_owner/get_last_ten_docpattern_src/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$num_rows 					= $this->m_project_owner->count_all_num_rows();
			$data["recordcount"] 		= $num_rows;
			$data['MenuCode'] 			= 'MN230';
			
			// Start of Pagination
			$config 					= array();
			$config["total_rows"] 		= $num_rows;
			$config["per_page"] 		= 15;
			$config["uri_segment"]		= 4;
			$config['cur_page'] 		= $offset;
			$config['base_url'] 		= site_url('c_project/project_owner/get_last_ten_owner');				
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
			// End of Pagination
	 
			$this->pagination->initialize($config);
	 
			$data['viewOwner'] = $this->m_project_owner->get_last_ten_owner($config["per_page"], $offset)->result();
			$data["pagination"] = $this->pagination->create_links();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN230';
				$TTR_CATEG		= 'L';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$this->load->view('v_project/v_project_owner/project_owner', $data);
		}
		else
		{
			redirect('login');
		}
	}
	// End
	
	function add() // OK
	{
		$this->load->model('m_project/m_project_owner/m_project_owner', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			$data['h2_title']		= 'Add Owner';
			$data['main_view'] 		= 'v_project/v_project_owner/project_owner_sd_form';
			$data['form_action']	= site_url('c_project/project_owner/add_process');
			//$data['link'] 			= array('link_back' => anchor('c_project/project_owner/','<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 	= site_url('c_project/project_owner/');
			$data['default']['VendCat_Code'] = '';
			
			$MenuCode 				= 'MN230';
			$data['MenuCode'] 		= 'MN230';
			$data['viewDocPattern'] = $this->m_project_owner->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN230';
				$TTR_CATEG		= 'A';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$this->load->view('v_project/v_project_owner/project_owner_form', $data);
		}
		else
		{
			redirect('login'); // by. DH on 16 Maret 14 : Failed, so ... load back to login
		}
	}
	
	function add_process() // OK
	{
		$this->load->model('m_project/m_project_owner/m_project_owner', '', TRUE);
		
		$input	= $this->input->post('own_Add1');
		// memecah string input berdasarkan karakter '\r\n\r\n'
		$pecah 	= explode("\n", $input);
		// string kosong inisialisasi
		$text 	= "";
		$vgv	= count($pecah);
		// untuk setiap substring hasil pecahan, sisipkan <p> di awal dan </p> di akhir
		// lalu menggabungnya menjadi satu string utuh $text
		for ($i=0; $i<=count($pecah)-1; $i++)
		{
			$part = str_replace($pecah[$i], "".$pecah[$i]."<br>", $pecah[$i]);
			$text .= $part;
		}
		$own_Add1New	= $text;
			
		$owner = array('own_Code' 		=> $this->input->post('own_Code'),
						'own_Title'		=> $this->input->post('own_Title'),
						'own_Name'		=> $this->input->post('own_Name'),
						'own_Add1'		=> $own_Add1New,
						'own_Telp'		=> $this->input->post('own_Telp'),
						'own_CP'		=> $this->input->post('own_CP'),
						'own_Status'	=> $this->input->post('own_Status'),
						'patt_No'		=> $this->input->post('patt_No'));

		$this->m_project_owner->add($owner);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= $this->input->post('own_Code');
				$MenuCode 		= 'MN230';
				$TTR_CATEG		= 'C';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
		
		$url			= site_url('c_project/project_owner/index1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function update() // OK
	{
		$this->load->model('m_project/m_project_owner/m_project_owner', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$ownCode	= $_GET['id'];
		$ownCode	= $this->url_encryption_helper->decode_url($ownCode);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title'] 		= 'Project Owner | Edit Project Owner';
			$data['main_view'] 		= 'v_project/v_project_owner/project_owner_sd_form';
			$data['form_action']	= site_url('c_project/project_owner/update_process');
			$data['MenuCode'] 		= 'MN230';
			
			//$data['link'] 			= array('link_back' => anchor('c_project/project_owner/','<input type="button" name="btnCancel" id="btnCancel" value="Cancel" class="btn btn-danger" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 	= site_url('c_project/project_owner/');
			
			$getvendor = $this->m_project_owner->get_owner_by_code($ownCode)->row();
			
			$data['default']['own_Code'] 	= $getvendor->own_Code;
			$data['default']['own_Title']	= $getvendor->own_Title;
			$data['default']['own_Name'] 	= $getvendor->own_Name;		
			$data['default']['own_Add1'] 	= $getvendor->own_Add1;
			$data['default']['own_Telp'] 	= $getvendor->own_Telp;
			$data['default']['own_CP'] 		= $getvendor->own_CP;
			$data['default']['own_Status'] 	= $getvendor->own_Status;
			$data['default']['patt_No'] 	= $getvendor->patt_No;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= $getvendor->own_Code;
				$MenuCode 		= 'MN230';
				$TTR_CATEG		= 'U';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$this->load->view('v_project/v_project_owner/project_owner_form', $data);
		}
		else
		{
			redirect('login');
		}
	}
	
	function update_process() // OK
	{	
		$this->load->model('m_project/m_project_owner/m_project_owner', '', TRUE);
			
		$input	= $this->input->post('own_Add1');
		// memecah string input berdasarkan karakter '\r\n\r\n'
		$pecah 	= explode("\n", $input);
		// string kosong inisialisasi
		$text 	= "";
		$vgv	= count($pecah);
		// untuk setiap substring hasil pecahan, sisipkan <p> di awal dan </p> di akhir
		// lalu menggabungnya menjadi satu string utuh $text
		for ($i=0; $i<=count($pecah)-1; $i++)
		{
			$part = str_replace($pecah[$i], "".$pecah[$i]."<br>", $pecah[$i]);
			$text .= $part;
		}
		$own_Add1New	= $text;
		$own_Status		= 1;
		
		$own_Code	= $this->input->post('own_Code');
			
		$owner = array('own_Title'		=> $this->input->post('own_Title'),
						'own_Name'		=> $this->input->post('own_Name'),
						'own_Add1'		=> $own_Add1New,
						'own_Telp'		=> $this->input->post('own_Telp'),
						'own_CP'		=> $this->input->post('own_CP'),
						'own_Status'	=> $this->input->post('own_Status'),
						'patt_No'		=> $this->input->post('patt_No'));
						
		$this->m_project_owner->update($own_Code, $owner);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= $own_Code;
				$MenuCode 		= 'MN230';
				$TTR_CATEG		= 'UP';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
		
		$url			= site_url('c_project/project_owner/index1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	/*function delete($own_Code)
	{
		$owner = array('own_Status'		=> $this->input->post('own_Status'));
		$this->m_project_owner->delete($this->input->post('chkDetail'));
		$this->session->set_flashdata('message', 'Data succesfull deleted.');
		
		redirect('c_project/project_owner/');
	}*/
	
	/*function get_last_ten_owner_src($offset=0) // OK
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$MyAppName    			= $this->session->userdata['SessAppTitle']['app_title_name'];
			//$DefProj_Code			= $this->session->userdata['dtSessSrc2']['selSearchproj_Code'];
			$DefProj_Code			= $this->session->userdata['userSessProject']['userprojSession'];
								
			$data['title'] 			= $MyAppName;
			$data['h2_title'] 		= 'Project Owner';
			$data['main_view'] 		= 'v_project/v_project_owner/project_owner_sd';	
			$data['moffset'] 		= $offset;
			$data['perpage'] 		= 20;
			$data['theoffset'] 		= 0;
			
			$data['srch_url'] 		= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/project_owner_sd'),'get_last_ten_owner_src');
			
			$data['selSearchType'] 	= $this->input->post('selSearchType');
			$data['txtSearch'] 		= $this->input->post('txtSearch');
			$data['selOwnStatus']	= $this->input->post('selOwnStatus');
			
			if (isset($_POST['submitSrch']))
			{
				$selSearchType	= $this->input->post('selSearchType');
				$txtSearch 		= $this->input->post('txtSearch');
				$selOwnStatus 	= $this->input->post('selOwnStatus');
				$VendStat	 	= $this->input->post('selOwnStatus');
				
				$dataSessSrc = array(
					'selSearchType' => $this->input->post('selSearchType'),
					'txtSearch' => $this->input->post('txtSearch'),
					'selOwnStatus' => $this->input->post('selOwnStatus'));
					
				$this->session->set_userdata('dtSessSrc1', $dataSessSrc);
				
				$dataSessSrc   = $this->session->userdata('dtSessSrc1');
			}
			else
			{
				$selSearchType      = $this->session->userdata['dtSessSrc1']['selSearchType'];
				$txtSearch        	= $this->session->userdata['dtSessSrc1']['txtSearch'];

				$selOwnStatus      = $this->session->userdata['dtSessSrc1']['selOwnStatus'];
				
				$dataSessSrc = array(
					'selSearchType' => $this->session->userdata['dtSessSrc1']['selSearchType'],
					'txtSearch' => $this->session->userdata['dtSessSrc1']['txtSearch'],
					'selOwnStatus' => $this->session->userdata['dtSessSrc1']['selOwnStatus']);
					
				$this->session->set_userdata('dtSessSrc1', $dataSessSrc);
			}
			
			if($selSearchType == 'OwnCode')
			{
				$num_rows = $this->m_project_owner->count_all_num_rows_VCode($txtSearch, $VendStat);
			}
			else
			{
				$num_rows = $this->m_project_owner->count_all_num_rows_VName($txtSearch, $VendStat);
			}			
			
			$data["recordcount"] = $num_rows;
			
			// Start of Pagination
			$config 					= array();
			$config["total_rows"] 		= $num_rows;
			$config["per_page"] 		= 15;
			$config["uri_segment"]		= 4;
			$config['cur_page'] 		= $offset;
			$config['base_url'] 		= site_url('c_project/project_owner/get_last_ten_owner');				
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
			// End of Pagination
	 
			$this->pagination->initialize($config);
	 
			//$data['viewvendor'] = $this->m_project_owner->get_last_ten_owner($config["per_page"], $offset)->result();
			
			if($selSearchType == 'OwnCode')
			{
				$data['viewOwner'] = $this->m_project_owner->get_last_ten_owner_VCode($config["per_page"], $offset, $txtSearch, $VendStat)->result();
			}
			else
			{
				$data['viewOwner'] = $this->m_project_owner->get_last_ten_owner_VName($config["per_page"], $offset, $txtSearch, $VendStat)->result();
			}
			
			$data["pagination"] = $this->pagination->create_links();
			
			$this->load->view('template', $data);
		}
		else
		{
			redirect('login'); // by. DH on 16 Maret 14 : Failed, so ... load back to login
		}
	}*/
}