<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 1 Oktober 2017
 * File Name	= C_multi_lang.php
 * Location		= -
*/

class C_multi_lang extends CI_Controller
{
 	public function index() // OK
	{		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_setting/c_multi_lang/index1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function index1() // OK
	{
		$this->load->model('m_setting/m_multi_lang/m_multi_lang', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
					
			$data['title'] 				= $appName;
			$data['h2_title'] 			= 'Multi Language';		
			$data["MenuCode"] 			= 'MN081';
			
			$num_rows 					= $this->m_multi_lang->count_all_num_rows();
			$data["recordcount"] 		= $num_rows;
	 
			$data['viewCurrency'] 		= $this->m_multi_lang->get_all_multilang()->result();
			
			$this->load->view('v_setting/v_multi_lang/v_multi_lang', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function add() // OK
	{
		$this->load->model('m_setting/m_multi_lang/m_multi_lang', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			$data['h2_title'] 		= 'Add Translate';
			$data['h3_title'] 		= 'Multi Language';
			$data['main_view'] 		= 'v_setting/v_multi_lang/v_multi_lang_form';
			$data['form_action']	= site_url('c_setting/c_multi_lang/add_process');
			$data['link'] 			= array('link_back' => anchor('c_setting/c_multi_lang/','<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 		= site_url('c_setting/c_multi_lang/');
			$data["MenuCode"] 		= 'MN081';
			
			$this->load->view('v_setting/v_multi_lang/v_multi_lang_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function add_process() // OK
	{
		$this->load->model('m_setting/m_multi_lang/m_multi_lang', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();
	
			$InsML	= array('MLANG_CODE'	=> $this->input->post('MLANG_CODE'),
							'MLANG_IND'		=> $this->input->post('MLANG_IND'),
							'MLANG_ENG'		=> $this->input->post('MLANG_ENG'));
	
			$this->m_multi_lang->add($InsML);
			
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
			
			$url			= site_url('c_setting/c_multi_lang/index1/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
		
	function getTheCode($MLANG_CODE) // OK
	{ 	
		$this->load->model('m_setting/m_multi_lang/m_multi_lang', '', TRUE);
		$countMLCode 	= $this->m_multi_lang->count_ml_code($MLANG_CODE);
		echo $countMLCode;
	}
	
	function update()
	{
		$this->load->model('m_setting/m_multi_lang/m_multi_lang', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$MLANG_ID			= $_GET['id'];
		$MLANG_ID			= $this->url_encryption_helper->decode_url($MLANG_ID);
		$data["MenuCode"] 	= 'MN081';
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title'] 		= 'Update Translate';
			$data['form_action']	= site_url('c_setting/c_multi_lang/update_process');
			$data['link'] 			= array('link_back' => anchor('c_setting/c_multi_lang/','<input type="button" name="btnCancel" id="btnCancel" value="Cancel" class="btn btn-danger" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 		= site_url('c_setting/c_multi_lang/');
			$getMLang 				= $this->m_multi_lang->get_translate($MLANG_ID)->row();
			
			$data['default']['MLANG_ID']	= $getMLang->MLANG_ID;
			$data['default']['MLANG_CODE']	= $getMLang->MLANG_CODE;
			$data['default']['MLANG_IND'] 	= $getMLang->MLANG_IND;
			$data['default']['MLANG_ENG'] 	= $getMLang->MLANG_ENG;
			
			$this->load->view('v_setting/v_multi_lang/v_multi_lang_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update_process()
	{		
		$this->load->model('m_setting/m_multi_lang/m_multi_lang', '', TRUE);
		
		$MLANG_CODE	= $this->input->post('MLANG_CODE');
		
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();
	
			$UpdML	= array('MLANG_CODE'	=> $this->input->post('MLANG_CODE'),
							'MLANG_IND'		=> $this->input->post('MLANG_IND'),
							'MLANG_ENG'		=> $this->input->post('MLANG_ENG'));
							
			$this->m_multi_lang->update($MLANG_CODE, $UpdML);
			
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
			
			$url			= site_url('c_setting/c_multi_lang/index1/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
}