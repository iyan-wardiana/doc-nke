<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 7 Februari 2017
 * File Name	= C_currency.php
 * Location		= -
*/

class C_currency  extends CI_Controller
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
		
		$url			= site_url('c_setting/c_currency/index1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	public function index1($offset=0)
	{
		$this->load->model('m_setting/m_currency/m_currency', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
					
			$data['title'] 				= $appName;
			$data['h2_title'] 			= 'Currency';		
			$data["MenuCode"] 			= 'MN078';
			
			$num_rows 					= $this->m_currency->count_all_num_rows();
			$data["recordcount"] 		= $num_rows;
			
			// Start of Pagination
			$config 					= array();
			$config['base_url'] 		= site_url('c_setting/c_currency/get_last_ten_currency');	
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
	 
			$data['viewCurrency'] 		= $this->m_currency->get_last_ten_currency($config["per_page"], $offset)->result();
			$data["pagination"] 		= $this->pagination->create_links();	
			
			$this->load->view('v_setting/v_currency/currency', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	// End
	
	function add()
	{
		$this->load->model('m_setting/m_currency/m_currency', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			$data['h2_title'] 		= 'Add Currency';
			$data['main_view'] 		= 'v_setting/v_currency/currency_form';
			$data['form_action']	= site_url('c_setting/c_currency/add_process');
			$data['link'] 			= array('link_back' => anchor('c_setting/c_currency/','<input type="button" name="btnCancel" id="btnCancel" class="btn btn-primary" value="Cancel" />', array('style' => 'text-decoration: none;')));			
			$data['backURL'] 		= site_url('c_setting/c_currency/');
			
			$data["MenuCode"] 		= 'MN078';
			
			$this->load->view('v_setting/v_currency/currency_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function add_process()
	{
		$this->load->model('m_setting/m_currency/m_currency', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();
	
			$InsCurr	= array('CURR_ID' 	=> $this->input->post('CURR_ID'),
							'CURR_CODE'		=> $this->input->post('CURR_CODE'),
							'CURR_NOTES'	=> $this->input->post('CURR_NOTES'),
							'CURR_STAT'		=> $this->input->post('CURR_STAT'));
	
			$this->m_currency->add($InsCurr);
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
		
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp 	= $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$url			= site_url('c_setting/c_currency/index1/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update()
	{
		$this->load->model('m_setting/m_currency/m_currency', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$CURR_ID				= $_GET['id'];
		$CURR_ID				= $this->url_encryption_helper->decode_url($CURR_ID);
		$data["MenuCode"] 		= 'MN078';
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 				= $appName;
			$data['task'] 				= 'edit';
			$data['h2_title'] 			= 'Currency | Edit Currency';
			$data['form_action']		= site_url('c_setting/c_currency/update_process');
			$data['link'] 				= array('link_back' => anchor('c_setting/c_currency/','<input type="button" name="btnCancel" id="btnCancel" value="Cancel" class="btn btn-primary" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 			= site_url('c_setting/c_currency/');
			
			$getdocapproval = $this->m_currency->get_curr_by_code($CURR_ID)->row();
	
			$data['default']['CURR_ID'] 	= $getdocapproval->CURR_ID;
			$data['default']['CURR_CODE']	= $getdocapproval->CURR_CODE;
			$data['default']['CURR_NOTES'] 	= $getdocapproval->CURR_NOTES;
			$data['default']['CURR_STAT'] 	= $getdocapproval->CURR_STAT;
			
			$this->load->view('v_setting/v_currency/currency_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update_process()
	{		
		$this->load->model('m_setting/m_currency/m_currency', '', TRUE);
		
		$CURR_ID	= $this->input->post('CURR_ID');
		
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();			
	
			$InsCurr	= array('CURR_ID' 	=> $this->input->post('CURR_ID'),
							'CURR_CODE'		=> $this->input->post('CURR_CODE'),
							'CURR_NOTES'	=> $this->input->post('CURR_NOTES'),
							'CURR_STAT'		=> $this->input->post('CURR_STAT'));
							
			$this->m_currency->update($CURR_ID, $InsCurr);
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
		
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp 	= $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$url			= site_url('c_setting/c_currency/index1/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
}