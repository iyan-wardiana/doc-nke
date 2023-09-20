<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 10 November 2018
 * File Name	= C_l4cc0un7.php
 * Location		= -
*/
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_l4cc0un7 extends CI_Controller
{
 	function index()  // G
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_setting/c_l4cc0un7/l4cc0un7/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function l4cc0un7() // G
	{
		$this->load->model('m_setting/m_linkaccount/m_linkaccount', '', TRUE);
		
		$idAppName	= $_GET['id'];
		$appName	= $this->url_encryption_helper->decode_url($idAppName);
					
		$data['title'] 			= $appName;
		$data['h2_title'] 		= 'Employee Data';
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		
		$data["MenuCode"] 		= 'MN387';
		$data["task"] 			= 'Add';
		$data['form_action']	= site_url('c_setting/c_l4cc0un7/download_summary/?id='.$this->url_encryption_helper->encode_url($appName));
		
		if ($this->session->userdata('login') == TRUE)
		{
			$sqlPRJ		= "SELECT PRJCODE FROM tbl_project WHERE isHO = 1";
			$resPRJ 	= $this->db->query($sqlPRJ)->result();
			foreach($resPRJ as $rowPRJ) :
				$PRJCODE= $rowPRJ->PRJCODE;
			endforeach;
			
			$data['vwAccAll'] 	= $this->m_linkaccount->get_all_accall($PRJCODE)->result();
			$data['vwAccount1'] = $this->m_linkaccount->get_all_acc1($PRJCODE)->result();
			$data['vwAccount2'] = $this->m_linkaccount->get_all_acc2($PRJCODE)->result();
			$data['vwAccount3'] = $this->m_linkaccount->get_all_acc3($PRJCODE)->result();
			$data['vwAccount4'] = $this->m_linkaccount->get_all_acc4($PRJCODE)->result();
			$data['vwAccount5'] = $this->m_linkaccount->get_all_acc5($PRJCODE)->result();
			$data['PRJCODE'] 	= $PRJCODE;
			$this->load->view('v_setting/v_linkaccount/v_linkaccount', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function download_summary()
	{
		$viewType 				= $this->input->post('viewType');
		$data['viewType'] 		= $this->input->post('viewType');
		$data['REPORT_PERIOD'] 	= date('Y-m-d',strtotime($this->input->post('REPORT_PERIOD')));
		$REPORT_PERIOD 			= date('Y-m-d',strtotime($this->input->post('REPORT_PERIOD')));
		$this->load->view('v_hr/v_allowance/v_allow_count/v_allow_download', $data);
	}
}