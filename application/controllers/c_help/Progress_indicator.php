<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 22 Maret 2017
 * File Name	= Project_owner.php
 * Location		= -
*/

class Progress_indicator extends CI_Controller
{
 	public function index()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_help/progress_indicator/index1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	public function index1($offset=0)
	{
		$this->load->model('m_help/m_progress_indicator', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
					
			$data['title'] 				= $appName;
			$data['h2_title']			= 'Project Owner';
			$data['secAddURL'] 			= site_url('c_help/progress_indicator/add/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['srch_url'] 			= site_url('c_help/progress_indicator/get_last_ten_docpattern_src/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$num_rows 					= $this->m_progress_indicator->count_all_num_rows();
			$data["recordcount"] 		= $num_rows;
	 
			$data['viewIndic'] = $this->m_progress_indicator->get_last_ten_indic()->result();
			
			$this->load->view('v_help/v_progress_indicator/progress_indicator', $data);
		}
		else
		{
			redirect('login');
		}
	}
	// End
	
	function add() // OK
	{
		$this->load->model('m_help/m_progress_indicator', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			$data['h2_title']		= 'Add Indicator';
			$data['h3_title']		= 'dashboard';
			$data['main_view'] 		= 'v_help/v_progress_indicator/progress_indicator_form';
			$data['form_action']	= site_url('c_help/progress_indicator/add_process');
			$data['link'] 			= array('link_back' => anchor('c_help/progress_indicator/','<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 	= site_url('c_help/progress_indicator/');
			
			$data['default']['VendCat_Code'] = '';
			
			$this->load->view('v_help/v_progress_indicator/progress_indicator_form', $data);
		}
		else
		{
			redirect('login'); // by. DH on 16 Maret 14 : Failed, so ... load back to login
		}
	}
	
	function add_process() // OK
	{
		$this->load->model('m_help/m_progress_indicator', '', TRUE);
		
		$IK_PARENT	= $this->input->post('IK_PARENT');
		$sqlINDICLV	= "SELECT IK_LEVEL
						FROM tbl_indikator WHERE IK_CODE = '$IK_PARENT'";
		$resINDICLV	= $this->db->query($sqlINDICLV)->result();
		foreach($resINDICLV as $rowINDICLV) :
			$IK_LEVEL	= $rowINDICLV->IK_LEVEL;
		endforeach;
		$IK_LEVELNEW	= $IK_LEVEL + 1;										
			
		$indic = array('IK_CODE' 		=> $this->input->post('IK_CODE'),
						'IK_PARENT'		=> $this->input->post('IK_PARENT'),
						'IK_DESC'		=> $this->input->post('IK_DESC'),
						'IK_TARGET'		=> $this->input->post('IK_TARGET'),
						'IK_PROCESSED'	=> $this->input->post('IK_PROCESSED'),
						'IK_ISHEADER'	=> $this->input->post('IK_ISHEADER'),
						'IK_LEVEL'		=> $IK_LEVELNEW);

		$this->m_progress_indicator->add($indic);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_help/progress_indicator/index1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function update() // OK
	{
		$this->load->model('m_help/m_progress_indicator', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$IK_CODE	= $_GET['id'];
		$IK_CODE	= $this->url_encryption_helper->decode_url($IK_CODE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title'] 		= 'Edit Indicator';
			$data['h3_title'] 		= 'dashboard';
			$data['main_view'] 		= 'v_help/v_progress_indicator/progress_indicator_form';
			$data['form_action']	= site_url('c_help/progress_indicator/update_process');
			
			$data['link'] 			= array('link_back' => anchor('c_help/progress_indicator/','<input type="button" name="btnCancel" id="btnCancel" value="Cancel" class="btn btn-danger" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 		= site_url('c_help/progress_indicator/');
			$getindic = $this->m_progress_indicator->get_indic_by_code($IK_CODE)->row();
			
			$data['default']['IK_CODE'] 	= $getindic->IK_CODE;
			$data['default']['IK_PARENT']	= $getindic->IK_PARENT;
			$data['default']['IK_DESC'] 	= $getindic->IK_DESC;		
			$data['default']['IK_TARGET'] 	= $getindic->IK_TARGET;
			$data['default']['IK_PROCESSED']= $getindic->IK_PROCESSED;
			$data['default']['IK_ISHEADER'] = $getindic->IK_ISHEADER;
			$data['default']['IK_LEVEL'] 	= $getindic->IK_LEVEL;
			
			$this->load->view('v_help/v_progress_indicator/progress_indicator_form', $data);
		}
		else
		{
			redirect('login');
		}
	}
	
	function update_process() // OK
	{	
		$this->load->model('m_help/m_progress_indicator', '', TRUE);
		
		$IK_CODE	= $this->input->post('IK_CODE');
		$IK_PARENT	= $this->input->post('IK_PARENT');
		$sqlINDICLV	= "SELECT IK_LEVEL
						FROM tbl_indikator WHERE IK_CODE = '$IK_PARENT'";
		$resINDICLV	= $this->db->query($sqlINDICLV)->result();
		foreach($resINDICLV as $rowINDICLV) :
			$IK_LEVEL	= $rowINDICLV->IK_LEVEL;
		endforeach;
		$IK_LEVELNEW	= $IK_LEVEL + 1;										
			
		$indic = array('IK_CODE' 		=> $this->input->post('IK_CODE'),
						'IK_PARENT'		=> $this->input->post('IK_PARENT'),
						'IK_DESC'		=> $this->input->post('IK_DESC'),
						'IK_TARGET'		=> $this->input->post('IK_TARGET'),
						'IK_PROCESSED'	=> $this->input->post('IK_PROCESSED'),
						'IK_ISHEADER'	=> $this->input->post('IK_ISHEADER'),
						'IK_LEVEL'		=> $IK_LEVELNEW);

		$this->m_progress_indicator->update($IK_CODE, $indic);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_help/progress_indicator/index1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
}