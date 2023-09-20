<?php
/*
	* Author		= Dian Hermanto
	* Create Date	= 23 Nopember 2020
	* File Name		= Y_5c4nQR_u5r.php
	* Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Y_5c4nQR_u5r extends CI_Controller  
{
	public function index()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName 	= $therow->app_name;
			$app_notes	= $therow->app_notes;
			$srvURL 	= $_SERVER['SERVER_ADDR'];
			$sqlAppDesc	= "SELECT TS_DESC FROM tbl_trashsys";
			$resAppDesc = $this->db->query($sqlAppDesc)->result();
			foreach($resAppDesc as $rowDesc) :
				$TS_DESC 	= $rowDesc->TS_DESC;
			endforeach;
		endforeach;
		$srvURL 	= $_SERVER['SERVER_ADDR'];
		
		if($this->crypt180c1c->sys_decsryptxx($srvURL, $appName, $app_notes, $TS_DESC) == TRUE)
			$url			= site_url('g3NQrc0d3/?id='.$this->url_encryption_helper->encode_url($appName));
		else
			$url			= site_url('g3NQrc0d3/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}

 	function g3NQrc0d3() // GOOD
	{
		$this->load->model('m_production/m_prodprocess', '', TRUE);
		
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url		= site_url('Y_5c4nQR/g3NQrc0d3_u5r/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function g3NQrc0d3_u5r() // GOOD
	{
		$this->load->model('m_production/m_prodprocess', '', TRUE);
					
		$data['title'] 		= "1stWeb Scanner";
		$data["appBody"] 	= "hold-transition skin-blue sidebar-collapse sidebar-mini fixed";
		$data["LangID"] 	= "IND";
		$data["h1_title"] 	= "Scan QR Code";
		$data['form_action']= site_url('c_production/c_pR04uctpr0535/add_process');

		$this->load->view('y_Y_5c4_nQR_u5r', $data);
	}
}