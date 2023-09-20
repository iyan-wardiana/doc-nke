<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 15 Maret 2017
 * File Name	= C_position.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_position extends CI_Controller  
{
 	public function index()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_setting/c_position/get_last_ten_position/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function get_last_ten_position($offset=0)
	{
		$this->load->model('m_setting/m_position/m_position', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			$EmpID 		= $this->session->userdata('Emp_ID');
					
			$data['title'] 			= $appName;
			$data['h2_title']		= 'Setting Position';
			$data['h3_title']		= 'setting';
			$data['main_view'] 		= 'v_setting/v_position/position';
			$data["MenuCode"] 		= 'MN092';
			
			$num_rows 				= $this->m_position->count_all_num_rows();
			$data["recordcount"] 	= $num_rows;
	 
			$data['viewposition'] 	= $this->m_position->get_last_ten_position()->result();
			
			$this->load->view('v_setting/v_position/position', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function add()
	{
		$this->load->model('m_setting/m_position/m_position', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			$data['h2_title'] 		= 'Add Position';
			$data['h3_title'] 		= 'setting';
			$data['form_action']	= site_url('c_setting/c_position/add_process');
			$data['link'] 			= array('link_back' => anchor('c_setting/c_position/','<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data["MenuCode"] 		= 'MN092';
			
			$data['deptCount'] 		= $this->m_position->getCount_department();		
			$data['getDepartment'] 	= $this->m_position->get_department()->result();
			
			$data['PositParentC'] 	= $this->m_position->getCount_position_forParent();		
			$data['PositionParent'] = $this->m_position->get_position_forParent()->result();
			
			$this->load->view('v_setting/v_position/position_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function add_process()
	{ 
		$this->load->model('m_setting/m_position/m_position', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$this->db->trans_begin();
			
			$position = array('POS_CODE'		=> $this->input->post('POS_CODE'),
								'POS_NAME'		=> $this->input->post('POS_NAME'),
								'POS_DESC'		=> $this->input->post('POS_DESC'), 
								'POS_LEVEL'		=> $this->input->post('POS_LEVEL'),
								'POS_PARENT'	=> $this->input->post('POS_PARENT'),
								'DEPCODE'		=> $this->input->post('DEPCODE'),
								'POS_ISLAST'	=> $this->input->post('POS_ISLAST'),
								'POS_STAT'		=> $this->input->post('POS_STAT'));
	
			$this->m_position->add($position);			
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			redirect('c_setting/c_position/');
		}
		else
		{
			redirect('Auth');
		}	
	}
	
	function update()
	{
		$this->load->model('m_setting/m_position/m_position', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$POS_CODE	= $_GET['id'];
			$POS_CODE	= $this->url_encryption_helper->decode_url($POS_CODE);
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title']		= 'Edit Position';
			$data['h3_title']		= 'setting';
			$data['main_view'] 		= 'v_setting/v_position/position_form';
			$data['form_action']	= site_url('c_setting/c_position/update_process');
			$data['link'] 			= array('link_back' => anchor('c_setting/c_position/','<input type="button" name="btnCancel" id="btnCancel" value="Cancel" class="btn btn-danger" />', array('style' => 'text-decoration: none;')));
			$data["MenuCode"] 		= 'MN092';
			
			$getposition = $this->m_position->get_position_by_code($POS_CODE)->row();
		
			$data['default']['POS_CODE'] 	= $getposition->POS_CODE;
			$data['default']['POS_NAME'] 	= $getposition->POS_NAME;
			$data['default']['POS_DESC'] 	= $getposition->POS_DESC;
			$data['default']['POS_LEVEL'] 	= $getposition->POS_LEVEL;
			$data['default']['POS_PARENT']	= $getposition->POS_PARENT;
			$data['default']['DEPCODE']		= $getposition->DEPCODE;
			$data['default']['POS_ISLAST']	= $getposition->POS_ISLAST;
			$data['default']['POS_STAT']	= $getposition->POS_STAT;
			
			$data['deptCount'] 		= $this->m_position->getCount_department();		
			$data['getDepartment'] 	= $this->m_position->get_department()->result();
			
			$data['PositParentC'] 	= $this->m_position->getCount_position_forParent();		
			$data['PositionParent'] = $this->m_position->get_position_forParent()->result();
			
			$this->load->view('v_setting/v_position/position_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update_process()
	{
		$this->load->model('m_setting/m_position/m_position', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
				
		if ($this->session->userdata('login') == TRUE)
		{
			$POS_CODE	= $this->input->post('POS_CODE');
			$this->db->trans_begin();
			
			$position = array('POS_CODE'		=> $this->input->post('POS_CODE'),
								'POS_NAME'		=> $this->input->post('POS_NAME'),
								'POS_DESC'		=> $this->input->post('POS_DESC'), 
								'POS_LEVEL'		=> $this->input->post('POS_LEVEL'),
								'POS_PARENT'	=> $this->input->post('POS_PARENT'),
								'DEPCODE'		=> $this->input->post('DEPCODE'),
								'POS_ISLAST'	=> $this->input->post('POS_ISLAST'),
								'POS_STAT'		=> $this->input->post('POS_STAT'));
						
			$this->m_position->update($POS_CODE, $position);
			
			redirect('c_setting/c_position/');
		}
		else
		{
			redirect('Auth');
		}
	}
}