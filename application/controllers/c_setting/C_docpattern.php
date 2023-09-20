<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 7 Februari 2017
 * File Name	= c_docpattern.php
 * Location		= -
*/

class C_docpattern extends CI_Controller
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
		
		$url			= site_url('c_setting/C_docpattern/index1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	public function index1($offset=0)
	{
		$this->load->model('m_setting/m_docpattern/m_docpattern', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
					
			$data['title'] 				= $appName;
			$data['h2_title']			= 'Document Pattern';
			$data['secAddURL'] 			= site_url('c_setting/C_docpattern/add/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['srch_url'] 			= site_url('c_setting/C_docpattern/get_last_ten_docpattern_src/?id='.$this->url_encryption_helper->encode_url($appName));
			$data["MenuCode"] 			= 'MN073';
			
			$num_rows 					= $this->m_docpattern->count_all_num_rows();
			$data["recordcount"] 		= $num_rows;
			
			$config 					= array();
			$config['base_url'] 		= site_url('c_setting/C_docpattern/get_last_ten_docpattern');
			
			$config["total_rows"] 		= $num_rows;
			$config["per_page"] 		= 20;
			$config["uri_segment"] 		= 4;							
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
	 
			$data['viewdocpattern'] 	= $this->m_docpattern->get_last_ten_docpattern($config["per_page"], $offset)->result();
			$data["pagination"] 		= $this->pagination->create_links();
			
			$this->load->view('v_setting/v_docpattern/docpattern', $data);
		}
		else
		{
			redirect('login');
		}
	}
	// End
	
	function add() // OK
	{	
		$this->load->model('m_setting/m_docpattern/m_docpattern', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			$data['h2_title']		= 'Add Document Pattern';
			$data['form_action']	= site_url('c_setting/C_docpattern/add_process');
			$data["MenuCode"] 		= 'MN073';
				
			$data['link'] 			= array('link_back' => anchor('c_setting/C_docpattern/','<input type="button" class="btn btn-primary" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 	= site_url('c_setting/C_docpattern/');
			
			$data['default']['Pattern_NameEdited'] = 0;
			$data['default']['menu_code'] = '';
			$data['viewMenuPattern'] = $this->m_docpattern->get_MenuToPattern()->result();
			
			$this->load->view('v_setting/v_docpattern/docpattern_form', $data);
		}
		else
		{
			redirect('login');
		}
	}
	
	function add_process()
	{	
		$this->load->model('m_setting/m_docpattern/m_docpattern', '', TRUE);
				
		$docpattern = array('Pattern_Code' 			=> $this->input->post('Pattern_Code'),
						'Pattern_Position'			=> $this->input->post('Pattern_Position'),
						'Pattern_Name'				=> $this->input->post('Pattern_Name'),
						'menu_code'					=> $this->input->post('menu_code'),
						'Pattern_NameEdited'		=> $this->input->post('Pattern_NameEdited'),
						'Pattern_YearAktive'		=> $this->input->post('Pattern_YearAktive'),
						'Pattern_MonthAktive'		=> $this->input->post('Pattern_MonthAktive'),
						'Pattern_DateAktive'		=> $this->input->post('Pattern_DateAktive'),
						'Pattern_Length'			=> $this->input->post('Pattern_Length'),
						'useYear'					=> $this->input->post('useYear'),
						'useMonth'					=> $this->input->post('useMonth'),
						'useDate'					=> $this->input->post('useDate'));

		$this->m_docpattern->add($docpattern);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_setting/C_docpattern/index1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function update()
	{		
		$this->load->model('m_setting/m_docpattern/m_docpattern', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$Pattern_Code	= $_GET['id'];
		$Pattern_Code	= $this->url_encryption_helper->decode_url($Pattern_Code);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$data['task'] 			= 'update';
			$data['h2_title']		= 'Document Pattern | Add Document Pattern';
			$data['form_action']	= site_url('c_setting/C_docpattern/update_process');
			$data["MenuCode"] 		= 'MN073';
				
			$data['link'] 			= array('link_back' => anchor('c_setting/C_docpattern/','<input type="button" class="btn btn-primary" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 	= site_url('c_setting/C_docpattern/');
			
			$data['viewMenuPattern'] 	= $this->m_docpattern->get_MenuToPattern()->result();
			
			$getdocpattern 				= $this->m_docpattern->get_docpatern_by_code($Pattern_Code)->row();
			
			$data['default']['Pattern_ID'] 			= $getdocpattern->Pattern_ID;
			$data['default']['Pattern_Code'] 		= $getdocpattern->Pattern_Code;
			$data['default']['Pattern_Position'] 	= $getdocpattern->Pattern_Position;
			$data['default']['Pattern_Name'] 		= $getdocpattern->Pattern_Name;
			$data['default']['menu_code']			= $getdocpattern->menu_code;
			$data['default']['Pattern_NameEdited'] 	= $getdocpattern->Pattern_NameEdited;
			$data['default']['Pattern_YearAktive'] 	= $getdocpattern->Pattern_YearAktive;
			$data['default']['Pattern_MonthAktive'] = $getdocpattern->Pattern_MonthAktive;
			$data['default']['Pattern_DateAktive'] 	= $getdocpattern->Pattern_DateAktive;	
			$data['default']['Pattern_Length'] 		= $getdocpattern->Pattern_Length;
			$data['default']['useYear'] 			= $getdocpattern->useYear;
			$data['default']['useMonth'] 			= $getdocpattern->useMonth;
			$data['default']['useDate'] 			= $getdocpattern->useDate;
			
			$this->load->view('v_setting/v_docpattern/docpattern_form', $data);
		}
		else
		{
			redirect('login');
		}
	}
	
	function update_process()
	{	
		$this->load->model('m_setting/m_docpattern/m_docpattern', '', TRUE);
		
		$Pattern_ID	= $this->input->post('Pattern_ID');
		
		$docpattern = array('Pattern_Code' 			=> $this->input->post('Pattern_Code'),
						'Pattern_Position'			=> $this->input->post('Pattern_Position'),
						'Pattern_Name'				=> $this->input->post('Pattern_Name'),
						'menu_code'					=> $this->input->post('menu_code'),
						'Pattern_NameEdited'		=> $this->input->post('Pattern_NameEdited'),
						'Pattern_YearAktive'		=> $this->input->post('Pattern_YearAktive'),
						'Pattern_MonthAktive'		=> $this->input->post('Pattern_MonthAktive'),
						'Pattern_DateAktive'		=> $this->input->post('Pattern_DateAktive'),
						'Pattern_Length'			=> $this->input->post('Pattern_Length'),
						'useYear'					=> $this->input->post('useYear'),
						'useMonth'					=> $this->input->post('useMonth'),
						'useDate'					=> $this->input->post('useDate'));
							
		$this->m_docpattern->update($Pattern_ID, $docpattern);
	
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_setting/C_docpattern/index1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function delete($Pattern_Code) // HOLD
	{		
		redirect('c_setting/C_docpattern/');
	}
}