<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 29 September 2018
 * File Name	= C_r3s3t.php
 * Location		= -
*/

class C_r3s3t extends CI_Controller
{
 	public function index() // G
	{		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_setting/c_r3s3t/r3s3t_l41nd/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function r3s3t_l41nd() // G
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
					
			$data['title'] 			= $appName;		
			$LangID 				= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['h1_title']	= 'Atur Ulang';
				$data['h2_title']	= 'Pengaturan';
			}
			else
			{
				$data['h1_title']	= 'Reset';
				$data['h2_title']	= 'Setting';
			}
			$data["MenuCode"] 		= 'MN364';
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN364';
				$TTR_CATEG		= 'RESET';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$this->load->view('v_setting/v_reset/v_reset_project', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
}