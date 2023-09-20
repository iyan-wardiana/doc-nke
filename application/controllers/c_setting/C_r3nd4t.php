<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 17 Oktober 2021
 * File Name	= C_r3nd4t.php
 * Location		= -
*/

class C_r3nd4t  extends CI_Controller
{
	public function index($offset=0)
	{
		$idAppName				= $_GET['id'];
		$appName				= $this->url_encryption_helper->decode_url($idAppName);
		
		$data['title'] 			= $appName;
		
		$LangID 	= $this->session->userdata['LangID'];
		if($LangID == 'IND')
		{
			$data["h1_title"] 	= "Pengaturan";
			$data["h2_title"] 	= "Render Data";
		}
		else
		{
			$data["h1_title"] 	= "Setting";
			$data["h2_title"] 	= "Data Render";
		}
		
		// START : UPDATE TO T-TRACK
			date_default_timezone_set("Asia/Jakarta");
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			$TTR_PRJCODE	= '';
			$TTR_REFDOC		= '';
			$MenuCode 		= 'MN050';
			$TTR_CATEG		= 'L';
			
			$this->load->model('m_updash/m_updash', '', TRUE);				
			$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
									'TTR_DATE' 		=> date('Y-m-d H:i:s'),
									'TTR_MNCODE'	=> $MenuCode,
									'TTR_CATEG'		=> $TTR_CATEG,
									'TTR_PRJCODE'	=> $TTR_PRJCODE,
									'TTR_REFDOC'	=> $TTR_REFDOC);
			$this->m_updash->updateTrack($paramTrack);
		// END : UPDATE TO T-TRACK
		
		$this->load->view('v_setting/v_render/v_render', $data);
	}
}