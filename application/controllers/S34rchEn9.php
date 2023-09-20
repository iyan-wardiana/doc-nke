<?php
/*  
 * Author		= Dian Hermanto
 * Create Date	= 26 Oktober 2020
 * File Name	= s34rchEn9.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class S34rchEn9  extends CI_Controller  
{
  	function __construct() // GOOD
	{
		parent::__construct();
		
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
		
		$url			= site_url('s34rchEn9/ids34rchEn9/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	public function ids34rchEn9($offset=0)
	{
		$this->load->model('m_howtouse', '', TRUE);
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			
		// GET MENU DESC
			$mnCode				= 'MN046';
			$data["MenuApp"] 	= 'MN046';
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
					
			$data['title'] 				= $appName;
			$data['h2_title']			= 'Pencarian';
			$data['h3_title']			= 'Help';

			$this->load->view('v_s34rchEn9/v_s34rchEn9', $data); 
		}
		else
		{
			redirect('Auth');
		}
	}
	
	public function ids34rchEn9_det($offset=0)
	{
		$this->load->model('m_howtouse', '', TRUE);
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			
		// GET MENU DESC
			$mnCode				= 'MN046';
			$data["MenuApp"] 	= 'MN046';
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$HOW_ID	= $_GET['id'];
			$HOW_ID	= $this->url_encryption_helper->decode_url($HOW_ID);
					
			$data['HOW_ID'] 			= $HOW_ID;
			$data['title'] 				= "Cara Penggunaan";
			$data['h2_title']			= 'Pencarian';
			$data['h3_title']			= 'Help';

			$this->load->view('v_s34rchEn9/v_s34rchEn9_det', $data); 
		}
		else
		{
			redirect('Auth');
		}
	}
}