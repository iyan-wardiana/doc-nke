<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 7 Februari 2017
 * File Name	= C_currency_conv.php
 * Location		= -
*/

class C_currency_conv  extends CI_Controller
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
		
		$url			= site_url('c_setting/c_currency_conv/index1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	public function index1($offset=0)
	{
		$this->load->model('m_setting/m_currency_conv/m_currency_conv', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
					
			$data['title'] 				= $appName;
			$data['h2_title'] 			= 'Currency Convertion';
			$data['main_view']			= 'v_setting/v_currency_conv/currency_conv';			
			$data["MenuCode"] 			= 'MN079';
			
			$num_rows 					= $this->m_currency_conv->count_all_num_rows();
			$data["recordcount"] 		= $num_rows;
			
			// Start of Pagination
			$config 					= array();
			$config['base_url'] 		= site_url('c_setting/c_currency_conv/get_last_ten_currconv');	
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
	 
			$data['viewCurrconv'] 		= $this->m_currency_conv->get_last_ten_currconv($config["per_page"], $offset)->result();
			$data["pagination"] 		= $this->pagination->create_links();
			
			$this->load->view('v_setting/v_currency_conv/currency_conv', $data);
		}
		else
		{
			redirect('login');
		}
	}
	// End
	
	function add()
	{
		$this->load->model('m_setting/m_currency_conv/m_currency_conv', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			$data['h2_title'] 		= 'Currency Convertion';
			$data['form_action']	= site_url('c_setting/c_currency_conv/add_process');
			//$data['link'] 			= array('link_back' => anchor('c_setting/c_currency_conv/','<input type="button" name="btnCancel" id="btnCancel" class="btn btn-primary" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 		= site_url('c_setting/c_currency_conv/');
			$data["MenuCode"] 		= 'MN079';
			
			$this->load->view('v_setting/v_currency_conv/currency_conv_form', $data);
		}
		else
		{
			redirect('login');
		}
	}
	
	function add_process()
	{
		$this->load->model('m_setting/m_currency_conv/m_currency_conv', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();
			
			date_default_timezone_set("Asia/Jakarta");
			
			$Yeras 			= date('Y');
			$Month 			= date('m');
			$Days			= date('d');
			$Hours			= date("his");
			$CC_STARTD		= $this->input->post('CC_STARTD');
			$CC_STARTD		= date('Y-m-d',strtotime($this->input->post('CC_STARTD')));
			$CC_ENDD		= date('Y-m-d',strtotime($this->input->post('CC_ENDD')));			
			$YearsL			= substr($Yeras,-2);
			$CC_CODE		= "$YearsL$Month$Days$Hours";
			
			$InsCurrConv	= array('CC_CODE' 	=> $CC_CODE,
								'CURR_ID1'		=> $this->input->post('CURR_ID1'),
								'CURR_ID2'		=> $this->input->post('CURR_ID2'),
								'CC_STARTD'		=> $this->input->post('CC_STARTD'),
								'CC_DURATION'	=> $this->input->post('CC_DURATION'),
								'CC_ENDD'		=> $this->input->post('CC_ENDD'),
								'CC_VALUE'		=> $this->input->post('CC_VALUE'));
	
			$this->m_currency_conv->add($InsCurrConv);
			
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
			
			$url			= site_url('c_setting/c_currency_conv/index1/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('login');
		}
	}
	
	function update()
	{
		$this->load->model('m_setting/m_currency_conv/m_currency_conv', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$CC_CODE	= $_GET['id'];
		$CC_CODE	= $this->url_encryption_helper->decode_url($CC_CODE);
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 				= $appName;
			$data['task'] 				= 'edit';
			$data['h2_title'] 			= 'Currency Convertion | Edit Currency Convertion';
			$data['main_view'] 			= 'v_setting/v_currency_conv/currency_conv_form';
			$data['form_action']		= site_url('c_setting/c_currency_conv/update_process');
			//$data['link'] 				= array('link_back' => anchor('c_setting/c_currency_conv/','<input type="button" name="btnCancel" id="btnCancel" value="Cancel" class="btn btn-primary" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 		= site_url('c_setting/c_currency_conv/');
			$data["MenuCode"] 			= 'MN079';
			
			$getdocapproval = $this->m_currency_conv->get_curr_by_code($CC_CODE)->row();
	
			$data['default']['CC_CODE'] 	= $CC_CODE;
			$data['default']['CURR_ID1'] 	= $getdocapproval->CURR_ID1;
			$data['default']['CURR_ID2']	= $getdocapproval->CURR_ID2;
			$data['default']['CC_STARTD'] 	= $getdocapproval->CC_STARTD;
			$data['default']['CC_DURATION'] = $getdocapproval->CC_DURATION;
			$data['default']['CC_ENDD']		= $getdocapproval->CC_ENDD;
			$data['default']['CC_VALUE'] 	= $getdocapproval->CC_VALUE;
			
			$this->load->view('v_setting/v_currency_conv/currency_conv_form', $data);
		}
		else
		{
			redirect('login');
		}
	}
	
	function update_process()
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();			
	
			$InsCurr	= array('CURR_ID' 	=> $this->input->post('CURR_ID'),
							'CURR_CODE'		=> $this->input->post('CURR_CODE'),
							'CURR_NOTES'	=> $this->input->post('CURR_NOTES'));
							
			$this->m_currency_conv->update($this->input->post('CURR_ID'), $InsCurr);
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			redirect('c_setting/c_currency_conv/');
		}
		else
		{
			redirect('login');
		}
	}
	
	function delete($ReqApproval_ID)
	{		
		redirect('c_setting/c_currency_conv/');
	}
}