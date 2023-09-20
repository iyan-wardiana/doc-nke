<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 29 Maret 2017
 * File Name	= C_menu.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_menu extends CI_Controller  
{
 	public function index()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_setting/c_menu/get_last_ten_menu/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function get_last_ten_menu($offset=0)
	{
		$this->load->model('m_setting/m_menu/m_menu', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			$EmpID 		= $this->session->userdata('Emp_ID');
					
			$data['title'] 			= $appName;
			$data['h2_title']		= 'Menu';
			$data['h3_title']		= 'setting';
			$data['main_view'] 		= 'v_setting/v_menu/menu';
			$data["MenuCode"] 		= 'MN273';
			
			$num_rows 				= $this->m_menu->count_all_num_rows();
			$data["recordcount"] 	= $num_rows;
	 
			$data['viewmenu'] 		= $this->m_menu->get_last_ten_menu()->result();
			
			$this->load->view('v_setting/v_menu/menu', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function add()
	{
		$this->load->model('m_setting/m_menu/m_menu', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			$data['h2_title'] 		= 'Add Menu';
			$data['h3_title'] 		= 'setting';
			$data['form_action']	= site_url('c_setting/c_menu/add_process');
			//$data['link'] 			= array('link_back' => anchor('c_setting/c_menu/','<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 		= site_url('c_setting/c_menu/');
			
			$MenuCode 				= 'MN273';
			$data["MenuCode"] 		= 'MN273';
			$data['viewDocPattern'] = $this->m_menu->getDataDocPat($MenuCode)->result();
			
			$data['MenuParentC'] 	= $this->m_menu->getCount_forParent();		
			$data['MenuParent'] 	= $this->m_menu->get_forParent()->result();
			
			$this->load->view('v_setting/v_menu/menu_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function add_process()
	{ 
		$this->load->model('m_setting/m_menu/m_menu', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$this->db->trans_begin();
			$isNeedPattern	= $this->input->post('isNeedStepAppr');
			
			$insMenu = array('menu_code'		=> $this->input->post('menu_code'),
							'menu_name_IND'		=> $this->input->post('menu_name_IND'),
							'menu_name_ENG'		=> $this->input->post('menu_name_ENG'),
							'isNeedPattern'		=> $this->input->post('isNeedPattern'), 
							'isNeedStepAppr'	=> $this->input->post('isNeedStepAppr'),
							'no_urut'			=> $this->input->post('no_urut'),
							'isHeader'			=> $this->input->post('isHeader'),
							'level_menu'		=> $this->input->post('level_menu'),
							'parent_code'		=> $this->input->post('parent_code'),
							'link_alias'		=> $this->input->post('link_alias'),
							'link_alias_sd'		=> $this->input->post('link_alias'),
							'menu_user'			=> $this->input->post('isActive'),
							'isActive'			=> $this->input->post('isActive'),
							'Pattern_No'		=> $this->input->post('Pattern_No'));
	
			$this->m_menu->add($insMenu);			
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			redirect('c_setting/c_menu/');
		}
		else
		{
			redirect('Auth');
		}	
	}
	
	function update()
	{
		$this->load->model('m_setting/m_menu/m_menu', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$menu_code	= $_GET['id'];
			$menu_code	= $this->url_encryption_helper->decode_url($menu_code);
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title']		= 'Edit Menu';
			$data['h3_title']		= 'setting';
			$data['main_view'] 		= 'v_setting/v_menu/menu_form';
			$data['form_action']	= site_url('c_setting/c_menu/update_process');
			//$data['link'] 			= array('link_back' => anchor('c_setting/c_menu/','<input type="button" name="btnCancel" id="btnCancel" value="Cancel" class="btn btn-danger" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 		= site_url('c_setting/c_menu/');
			$data["MenuCode"] 		= 'MN273';
			
			$getmenu = $this->m_menu->get_menu_by_code($menu_code)->row();
		
			$data['default']['menu_code'] 		= $getmenu->menu_code;
			$data['default']['menu_name_IND'] 	= $getmenu->menu_name_IND;
			$data['default']['menu_name_ENG'] 	= $getmenu->menu_name_ENG;
			$data['default']['isNeedPattern'] 	= $getmenu->isNeedPattern;
			$data['default']['isNeedStepAppr'] 	= $getmenu->isNeedStepAppr;
			$data['default']['no_urut']			= $getmenu->no_urut;
			$data['default']['isHeader']		= $getmenu->isHeader;
			$data['default']['level_menu']		= $getmenu->level_menu;
			$data['default']['parent_code']		= $getmenu->parent_code;
			$data['default']['link_alias']		= $getmenu->link_alias;
			$data['default']['link_alias_sd']	= $getmenu->link_alias_sd;
			$data['default']['isActive']		= $getmenu->isActive;
			$data['default']['Pattern_No']		= $getmenu->Pattern_No;
			
			$data['MenuParentC'] 	= $this->m_menu->getCount_forParent();		
			$data['MenuParent'] = $this->m_menu->get_forParent()->result();
			
			$this->load->view('v_setting/v_menu/menu_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update_process()
	{
		$this->load->model('m_setting/m_menu/m_menu', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
				
		if ($this->session->userdata('login') == TRUE)
		{
			$menu_code	= $this->input->post('menu_code');
			$this->db->trans_begin();
			
			$insMenu = array('menu_code'		=> $this->input->post('menu_code'),
							'menu_name_IND'		=> $this->input->post('menu_name_IND'),
							'menu_name_ENG'		=> $this->input->post('menu_name_ENG'),
							'isNeedPattern'		=> $this->input->post('isNeedPattern'), 
							'isNeedStepAppr'	=> $this->input->post('isNeedStepAppr'),
							'no_urut'			=> $this->input->post('no_urut'),
							'isHeader'			=> $this->input->post('isHeader'),
							'level_menu'		=> $this->input->post('level_menu'),
							'parent_code'		=> $this->input->post('parent_code'),
							'link_alias'		=> $this->input->post('link_alias'),
							'link_alias_sd'		=> $this->input->post('link_alias'),
							'menu_user'			=> $this->input->post('isActive'),
							'isActive'			=> $this->input->post('isActive'));
						
			$this->m_menu->update($menu_code, $insMenu);
			
			redirect('c_setting/c_menu/');
		}
		else
		{
			redirect('Auth');
		}
	}
}