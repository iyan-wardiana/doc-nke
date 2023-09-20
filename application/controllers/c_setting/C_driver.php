<?php
/*  
 * Author		= Hendar Permana 
 * Create Date	= 10 Mei 2017
 * File Name	= C_office_inventory.php
 * Location		= -
*/

/*  
 * Author		= Hendar Permana 
 * Create Date	= 02 Maret 2018
 * File Name	= c_driver.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class c_driver  extends CI_Controller  
{
  	function __construct() // GOOD
	{
		parent::__construct();
		
		$this->load->model('m_purchase/m_purchase_po/m_purchase_po', '', TRUE);
		$this->load->model('m_updash/m_updash', '', TRUE);
	
		$this->data['UserID'] 		= $this->session->userdata['Emp_ID'];
		$this->data['appName'] 		= $this->session->userdata['appName'];
		$this->data['ISCREATE'] 	= $this->session->userdata['ISCREATE'];
		$this->data['ISAPPROVE'] 	= $this->session->userdata['ISAPPROVE'];
		$this->data['LangID']		= $this->session->userdata['LangID'];
	}

 	public function index()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_setting/c_driver/index1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	public function index1($offset=0)
	{
		$this->load->model('m_setting/m_driver/m_driver', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$LangID 	= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN418';
				$data["MenuApp"] 	= 'MN418';
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
				{
					$data['mnTitl']	= 'Daftar';
					$data["mnName"] = $getMN->menu_name_IND;
				}
				else
				{
					$data['mnTitl']	= 'List';
					$data["mnName"] = $getMN->menu_name_ENG;
				}

			$data['title'] 				= $appName;
			$data['h2_title']			= 'Driver';
			$data['h3_title']			= 'Setting';
			$data['secAddURL'] 			= site_url('c_setting/c_driver/add/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['srch_url'] 			= site_url('c_setting/c_driver/get_last_ten_docpattern_src/?id='.$this->url_encryption_helper->encode_url($appName));
			
			
			$num_rows 					= $this->m_driver->count_all_num_rows();
			$data["recordcount"] 		= $num_rows;
	 		$data["MenuCode"] 			= 'MN356';
			
			$data['vAssetGroup']		= $this->m_driver->get_last_ten_AG()->result();
			
			$this->load->view('v_setting/v_driver/v_driver', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	// End
	
	function add() // OK
	{	
		$this->load->model('m_setting/m_driver/m_driver', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$LangID 	= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN418';
				$data["MenuApp"] 	= 'MN418';
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
				{
					$data['mnTitl']	= 'Tambah';
					$data["mnName"] = $getMN->menu_name_IND;
				}
				else
				{
					$data['mnTitl']	= 'Add';
					$data["mnName"] = $getMN->menu_name_ENG;
				}
			
			$docPatternPosition 		= 'Especially';	
			$data['title'] 				= $appName;
			$data['task'] 				= 'add';
			$data['form_action']		= site_url('c_setting/c_driver/add_process');
			$data['link'] 				= array('link_back' => anchor('c_setting/c_driver/','<input type="button" class="btn btn-danger" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 			= site_url('c_setting/c_driver/');
			$MenuCode 					= 'MN418';
			$data["MenuCode"] 			= 'MN418';
			$data['countDocPattern'] 	= $this->m_driver->getDataDocPatC($MenuCode);
			$data['viewDocPattern'] 	= $this->m_driver->getDataDocPat($MenuCode)->result();
			
			$this->load->view('v_setting/v_driver/v_driver_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function add_process() // OK
	{	
		$this->load->model('m_setting/m_driver/m_driver', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$this->db->trans_begin();

			$InsAG 		= array('DRIV_CODE' 	=> $this->input->post('DRIV_CODE'),
								'DRIV_NAME' 	=> $this->input->post('DRIV_NAME'),
								'DRIV_CONTACT'	=> $this->input->post('DRIV_CONTACT'),
								'DRIV_STAT'		=> $this->input->post('DRIV_STAT'),
								'DRIV_CREATER'	=> $this->session->userdata['Emp_ID'],
								'DRIV_CREATED' 	=> date('Y-m-d H:i:s'));

			$this->m_driver->add($InsAG);
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_setting/c_driver/index1/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update() // OK
	{	
		$this->load->model('m_setting/m_driver/m_driver', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$DRIV_CODE	= $_GET['id'];
			$DRIV_CODE	= $this->url_encryption_helper->decode_url($DRIV_CODE);
			
			$LangID 	= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN418';
				$data["MenuApp"] 	= 'MN418';
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
				{
					$data['mnTitl']	= 'Tambah';
					$data["mnName"] = $getMN->menu_name_IND;
				}
				else
				{
					$data['mnTitl']	= 'Add';
					$data["mnName"] = $getMN->menu_name_ENG;
				}
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['form_action']	= site_url('c_setting/c_driver/update_process');
			$data['link'] 			= array('link_back' => anchor('c_setting/c_driver/','<input type="button" class="btn btn-danger" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data["MenuCode"] 		= 'MN418';
			$data['backURL'] 		= site_url('c_setting/c_driver/');
			
			$getAG 					= $this->m_driver->get_AG($DRIV_CODE)->row();
			
			$data['default']['DRIV_CODE'] 		= $getAG->DRIV_CODE;
			$data['default']['DRIV_NAME'] 		= $getAG->DRIV_NAME;
			$data['default']['DRIV_CONTACT'] 	= $getAG->DRIV_CONTACT;
			$data['default']['DRIV_STAT'] 		= $getAG->DRIV_STAT;
			
			$this->load->view('v_setting/v_driver/v_driver_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update_process()
	{	
		$this->load->model('m_setting/m_driver/m_driver', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$this->db->trans_begin();
			
			$DRIV_CODE	= $this->input->post('DRIV_CODE');

			$UpdAG 		= array('DRIV_CODE' 	=> $this->input->post('DRIV_CODE'),
								'DRIV_NAME' 	=> $this->input->post('DRIV_NAME'),
								'DRIV_CONTACT'	=> $this->input->post('DRIV_CONTACT'),
								'DRIV_STAT'		=> $this->input->post('DRIV_STAT'),
								'DRIV_CREATER'	=> $this->session->userdata['Emp_ID'],
								'DRIV_CREATED' 	=> date('Y-m-d H:i:s'));
								
			$this->m_driver->update($DRIV_CODE, $UpdAG);
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_setting/c_driver/index1/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function delete()
	{
		$this->load->model('m_setting/m_driver/m_driver', '', TRUE);
		$CODE			= $_GET['id'];
		$DRIVER_CODE	= $this->url_encryption_helper->decode_url($CODE);
		
		$resultaApp = $this->m_assets->appname();
		foreach ($resultaApp as $therow) {
			$appName	= $therow['app_name'];		
		}
		
		$this->m_driver->deleteDR($DRIVER_CODE);
		
		$url			= site_url('c_setting/c_driver/index1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
}