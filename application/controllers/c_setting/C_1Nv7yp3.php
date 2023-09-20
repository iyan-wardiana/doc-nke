<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 30 Maret 2019
 * File Name	= C_1Nv7yp3.php
 * Location		= -
*/

class C_1Nv7yp3 extends CI_Controller
{
 	function index() // G
	{		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_setting/c_1Nv7yp3/iN4x/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function iN4x() // G
	{
		$this->load->model('m_setting/m_unittype/m_unittype', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
					
			$data['title'] 			= $appName;	
			$data["MenuCode"] 		= 'MN097';	
			$LangID 				= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Master Unit";
				$data['h2_title'] 	= 'pengaturan';
			}
			else
			{
				$data["h1_title"] 	= "Master Unit";
				$data['h2_title'] 	= 'setting';
			}
			
			$num_rows 					= $this->m_unittype->count_all_num_rows();
			$data["recordcount"] 		= $num_rows;
	 
			$data['viewCurrency'] 		= $this->m_unittype->get_all_data()->result();
			
			$this->load->view('v_setting/v_unittype/v_unittype', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function add() // G
	{
		$this->load->model('m_setting/m_unittype/m_unittype', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			
			$LangID 				= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Master Unit";
				$data['h2_title'] 	= 'pengaturan';
			}
			else
			{
				$data["h1_title"] 	= "Master Unit";
				$data['h2_title'] 	= 'setting';
			}
			
			$data['form_action']	= site_url('c_setting/c_1Nv7yp3/add_process');
			$data['link'] 			= array('link_back' => anchor('c_setting/c_1Nv7yp3/','<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 		= site_url('c_setting/c_1Nv7yp3/');
			$data["MenuCode"] 		= 'MN097';
			
			$this->load->view('v_setting/v_unittype/v_unittype_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
		
	function getTheCode($Unit_Type_Code) // G
	{ 	
		$this->load->model('m_setting/m_unittype/m_unittype', '', TRUE);
		$countMLCode 	= $this->m_unittype->count_ml_code($Unit_Type_Code);
		echo $countMLCode;
	}
	
	function add_process() // OK
	{
		$this->load->model('m_setting/m_unittype/m_unittype', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();
	
			$InsML	= array('Unit_Type_Code'	=> $this->input->post('Unit_Type_Code'),
							'UMCODE'			=> $this->input->post('Unit_Type_Code'),
							'Unit_Type_Name'	=> $this->input->post('Unit_Type_Name'),
							'Unit_Type_Desc'	=> $this->input->post('Unit_Type_Desc'));
	
			$this->m_unittype->add($InsML);
			
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
			
			$url			= site_url('c_setting/c_1Nv7yp3/iN4x/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update()
	{
		$this->load->model('m_setting/m_unittype/m_unittype', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$Unit_Type_ID		= $_GET['id'];
		$Unit_Type_ID		= $this->url_encryption_helper->decode_url($Unit_Type_ID);
		$data["MenuCode"] 	= 'MN097';
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			
			$LangID 				= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Master Unit";
				$data['h2_title'] 	= 'pengaturan';
			}
			else
			{
				$data["h1_title"] 	= "Master Unit";
				$data['h2_title'] 	= 'setting';
			}
			
			$data['form_action']	= site_url('c_setting/c_1Nv7yp3/update_process');
			$data['link'] 			= array('link_back' => anchor('c_setting/c_1Nv7yp3/','<input type="button" name="btnCancel" id="btnCancel" value="Can cel" class="btn btn-danger" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 		= site_url('c_setting/c_1Nv7yp3/');
			$getMLang 				= $this->m_unittype->get_unit($Unit_Type_ID)->row();
			
			$data['default']['Unit_Type_ID']	= $getMLang->Unit_Type_ID;
			$data['default']['Unit_Type_Code']	= $getMLang->Unit_Type_Code;
			$data['default']['Unit_Type_Name'] 	= $getMLang->Unit_Type_Name;
			$data['default']['Unit_Type_Desc'] 	= $getMLang->Unit_Type_Desc;
			
			$this->load->view('v_setting/v_unittype/v_unittype_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process()
	{		
		$this->load->model('m_setting/m_unittype/m_unittype', '', TRUE);
		
		$Unit_Type_Code	= $this->input->post('Unit_Type_Code');
		
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();
	
			$UpdML	= array('Unit_Type_Code'	=> $this->input->post('Unit_Type_Code'),
							'UMCODE'			=> $this->input->post('Unit_Type_Code'),
							'Unit_Type_Name'	=> $this->input->post('Unit_Type_Name'),
							'Unit_Type_Desc'	=> $this->input->post('Unit_Type_Desc'));
							
			$this->m_unittype->update($Unit_Type_Code, $UpdML);
			
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
			
			$url			= site_url('c_setting/c_1Nv7yp3/iN4x/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
}