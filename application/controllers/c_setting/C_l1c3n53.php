<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 01 Maret 2021
 * File Name	= C_l1c3n53.php
 * Location		= -
*/

class C_l1c3n53 extends CI_Controller
{
  	function __construct() // GOOD
	{
		parent::__construct();
		
		$this->load->model('m_updash/m_updash', '', TRUE);
	}

 	function index() // G
	{		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_setting/C_l1c3n53/aCt_4pL/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function aCt_4pL() // G
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
					
			$data['title'] 	= $appName;
			$LangID 		= $this->session->userdata['LangID'];
				
			// GET MENU DESC
				$mnCode				= 'MN243';
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($LangID == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;

			$data['form_action']	= site_url('c_setting/C_l1c3n53/act_process');
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN243';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> "ACT",
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$this->load->view('v_setting/v_license/v_license', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
}