<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 1 November 2017
 * File Name	= C_departm.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_departm extends CI_Controller  
{
 	public function index()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_hr/c_organiz/c_departm/get_all_department/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function get_all_department($offset=0)
	{
		$this->load->model('m_hr/m_organiz/m_departm', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			$EmpID 		= $this->session->userdata('Emp_ID');
					
			$data['title'] 		= $appName;
			$data['task']		= 'Add';
			$data["MenuCode"] 	= 'MN269';
			$data['addURL'] 	= site_url('c_hr/c_organiz/c_departm/add/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$num_rows 			= $this->m_departm->count_all_dept();
			$data["countDept"] 	= $num_rows;
	 
			$data['vwDepartm'] 	= $this->m_departm->get_all_department()->result();
			
			$this->load->view('v_hr/v_organiz/v_departm/department', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function add()
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			$data['form_action']	= site_url('c_hr/c_organiz/c_departm/add_process');
			$data['backURL'] 		= site_url('c_hr/c_organiz/c_departm/?id='.$this->url_encryption_helper->encode_url($appName));
			$data["MenuCode"] 		= 'MN269';
			
			$this->load->view('v_hr/v_organiz/v_departm/department_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function add_process()
	{
		$this->load->model('m_hr/m_organiz/m_departm', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$this->db->trans_begin();
			
			$department = array('DEPCODE'	=> $this->input->post('DEPCODE'),
							'DEPDESC'		=> $this->input->post('DEPDESC'),
							'DEPINIT'		=> $this->input->post('DEPINIT'));
	
			$this->m_departm->add($department);			
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			redirect('c_hr/c_organiz/c_departm/');
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update()
	{
		$this->load->model('m_hr/m_organiz/m_departm', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$DEPCODE	= $_GET['id'];
			$DEPCODE	= $this->url_encryption_helper->decode_url($DEPCODE);
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title']		= 'Edit Department';
			$data['h3_title']		= 'setting';
			$data['form_action']	= site_url('c_hr/c_organiz/c_departm/update_process');
			$data['link'] 			= array('link_back' => anchor('c_hr/c_organiz/c_departm/','<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data["MenuCode"] 		= 'MN269';
			
			$getdepartment 			= $this->m_departm->get_department_by_code($DEPCODE)->row();
			
			$data['default']['DEPCODE'] 	= $getdepartment->DEPCODE;
			$data['default']['DEPDESC'] 	= $getdepartment->DEPDESC;
			$data['default']['DEPINIT'] 	= $getdepartment->DEPINIT;
		
			$this->load->view('v_hr/v_organiz/v_departm/department_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update_process()
	{
		$this->load->model('m_hr/m_organiz/m_departm', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
				
		if ($this->session->userdata('login') == TRUE)
		{
			$DEPCODE	= $this->input->post('DEPCODE');
			$department = array('DEPCODE'	=> $this->input->post('DEPCODE'),
							'DEPDESC'		=> $this->input->post('DEPDESC'),
							'DEPINIT'		=> $this->input->post('DEPINIT'));
							
			$this->m_departm->update($DEPCODE, $department);
			
			$this->session->set_flashdata('message', 'Data succesful to update.');
			
			redirect('c_hr/c_organiz/c_departm/');
		}
		else
		{
			redirect('Auth');
		}
	}
}