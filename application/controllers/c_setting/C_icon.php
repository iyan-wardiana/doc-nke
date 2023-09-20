<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 21 Desember 2017
 * File Name	= C_icon.php
 * Location		= -
*/

class C_icon extends CI_Controller
{
 	public function index()
	{		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_setting/c_icon/index1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function index1($offset=0)
	{
		$this->load->model('m_setting/m_currency/m_currency', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
					
			$data['title'] 				= $appName;
			$data['h2_title'] 			= 'Icon';		
			$data["MenuCode"] 			= 'MN336';
			
			$this->load->view('v_setting/v_icon/icon', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
}