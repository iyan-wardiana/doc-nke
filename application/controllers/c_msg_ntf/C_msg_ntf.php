<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 25 April 2019
 * File Name	= C_msg_ntf
 * Function		= -
*/

class C_msg_ntf extends CI_Controller
{
 	function index()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_msg_ntf/c_msg_ntf/m59_N7f/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function m59_N7f()
	{
		$this->load->model('m_help/m_task_request', '', TRUE);
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['title'] 		= $appName;
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "1stWeb Assistance";
				$data["h2_title"] 	= "Mengapa saya melihat ini?";
				$data["tab1"] 		= "Perawatan";
				$data["tab2"] 		= "Pemeliharaan";
				$data["tab3"] 		= "Faktur Tertunda";
			}
			else
			{
				$data["h1_title"] 	= "1stWeb Assistance";
				$data["h2_title"] 	= "Why did I see this message?";
				$data["tab1"] 		= "Nursing";
				$data["tab2"] 		= "Maintenance";
				$data["tab3"] 		= "Invoice pending";
			}
			
			$this->load->view('msg_ntf/msg_ntf', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
}
?>