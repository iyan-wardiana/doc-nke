<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 1 November 2017
 * File Name	= C_position_func.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_position_func extends CI_Controller  
{
 	public function index()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url	= site_url('c_hr/c_organiz/c_position_func/get_position_func/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function get_position_func($offset=0)
	{
		$this->load->model('m_hr/m_organiz/m_position_func', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			$EmpID 		= $this->session->userdata('Emp_ID');
					
			$data['title'] 		= $appName;
			$data["MenuCode"] 	= 'MN092';			
			$num_rows 			= $this->m_position_func->count_all();
			$data["countPosStr"]= $num_rows;	 
			$data['vwPosStr']	= $this->m_position_func->get_position_func()->result();
			
			$this->load->view('v_hr/v_organiz/v_position_func/position_func', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function add()
	{
		$this->load->model('m_hr/m_organiz/m_position_func', '', TRUE);
		$this->load->model('m_docpattern/m_docpattern', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['title'] 		= $appName;
			$data['task'] 		= 'add';
			$data['form_action']= site_url('c_hr/c_organiz/c_position_func/add_process');
			$data['backURL'] 	= site_url('c_hr/c_organiz/c_position_func/?id='.$this->url_encryption_helper->encode_url($appName));
			$data["MenuCode"] 	= 'MN092';
			
			$MenuCode			= 'MN092';			
			$data['vwDocPatt'] 	= $this->m_docpattern->getDataDocPat($MenuCode)->result();
			
			$data['countParent'] = $this->m_position_func->count_all_str();		
			$data['vwParent'] 	= $this->m_position_func->get_position_str()->result();
			
			$this->load->view('v_hr/v_organiz/v_position_func/position_func_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function add_process()
	{ 
		$this->load->model('m_hr/m_organiz/m_position_func', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$this->db->trans_begin();
			
			$position 	= array('POSF_NO'		=> $this->input->post('POSF_NO'),
								'POSF_CODE'		=> $this->input->post('POSF_CODE'),
								'POSF_NAME'		=> $this->input->post('POSF_NAME'),
								//'POSF_LEVEL'	=> $this->input->post('POSF_LEVEL'),
								'POSF_PARENT'	=> $this->input->post('POSF_PARENT'),
								'POSF_DESC'		=> $this->input->post('POSF_DESC'),
								//'POSF_ISLAST'	=> $this->input->post('POSF_ISLAST'),
								'POSF_STAT'		=> $this->input->post('POSF_STAT'));
	
			$this->m_position_func->add($position);			
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			redirect('c_hr/c_organiz/c_position_func/');
		}
		else
		{
			redirect('Auth');
		}	
	}
	
	function update()
	{
		$this->load->model('m_hr/m_organiz/m_position_func', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$POSS_CODE	= $_GET['id'];
			$POSS_CODE	= $this->url_encryption_helper->decode_url($POSS_CODE);
			
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_hr/c_organiz/c_position_func/update_process');
			$data['backURL'] 	= site_url('c_hr/c_organiz/c_position_func/?id='.$this->url_encryption_helper->encode_url($appName));
			$data["MenuCode"] 	= 'MN092';
			
			$data['countParent'] = $this->m_position_func->count_all();		
			$data['vwParent'] 	= $this->m_position_func->get_position_func_prn()->result();
			
			$getposition = $this->m_position_func->get_position_by_code($POSS_CODE)->row();
			
			$data['default']['POSF_NO'] 	= $getposition->POSF_NO;
			$data['default']['POSF_CODE'] 	= $getposition->POSF_CODE;
			$data['default']['POSF_NAME'] 	= $getposition->POSF_NAME;
			$data['default']['POSF_PARENT']	= $getposition->POSF_PARENT;
			$data['default']['POSF_DESC'] 	= $getposition->POSF_DESC;
			$data['default']['POSF_STAT']	= $getposition->POSF_STAT;
			
			$this->load->view('v_hr/v_organiz/v_position_func/position_func_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update_process()
	{
		$this->load->model('m_hr/m_organiz/m_position_func', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
				
		if ($this->session->userdata('login') == TRUE)
		{
			$POSS_CODE	= $this->input->post('POSS_CODE');
			
			$this->db->trans_begin();
			
			$POSF_NO	= $this->input->post('POSF_NO');
			
			$position 	= array('POSF_NO'		=> $this->input->post('POSF_NO'),
								'POSF_CODE'		=> $this->input->post('POSF_CODE'),
								'POSF_NAME'		=> $this->input->post('POSF_NAME'),
								//'POSF_LEVEL'	=> $this->input->post('POSF_LEVEL'),
								'POSF_PARENT'	=> $this->input->post('POSF_PARENT'),
								'POSF_DESC'		=> $this->input->post('POSF_DESC'),
								//'POSF_ISLAST'	=> $this->input->post('POSF_ISLAST'),
								'POSF_STAT'		=> $this->input->post('POSF_STAT'));						
			$this->m_position_func->update($POSF_NO, $position);
			
			redirect('c_hr/c_organiz/c_position_func/');
		}
		else
		{
			redirect('Auth');
		}
	}
}