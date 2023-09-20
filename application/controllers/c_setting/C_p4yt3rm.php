<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 20 September 2018
 * File Name	= C_p4yt3rm.php
 * Location		= -
*/
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_p4yt3rm extends CI_Controller  
{
 	public function index() // G
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_setting/c_p4yt3rm/p4yt3rm_up/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function p4yt3rm_up() // G
	{
		$this->load->model('m_setting/m_payterm/m_payterm', '', TRUE);
		$this->load->model('m_updash/m_updash', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$LangID 				= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['h1_title']	= 'Cara Bayar dan Lainnya';
				$data['h2_title']	= 'Cetak SPK Alat';
			}
			else
			{
				$data['h1_title']	= 'Payment Term and Others';
				$data['h2_title']	= 'Print WO Tools';
			}
			
			$data['form_action']	= site_url('c_setting/C_p4yt3rm/update_process');
			$data["MenuCode"] 		= 'MN080';
			$getPayTerm 			= $this->m_payterm->get_data()->row();
			
			$data['default']['ID'] 			= $getPayTerm->ID;
			$data['default']['PT_DESC01'] 	= $getPayTerm->PT_DESC01;
			$data['default']['PT_DESC02'] 	= $getPayTerm->PT_DESC02;
			$data['default']['PT_DESC03'] 	= $getPayTerm->PT_DESC03;
			$data['default']['PT_DESC04'] 	= $getPayTerm->PT_DESC04;
			$data['default']['PT_DESC05'] 	= $getPayTerm->PT_DESC05;
			$data['default']['PTRM_DESC01'] = $getPayTerm->PTRM_DESC01;
			$data['default']['PTRM_DESC02'] = $getPayTerm->PTRM_DESC02;
			$data['default']['PTRM_DESC03'] = $getPayTerm->PTRM_DESC03;
			$data['default']['PTRM_DESC04'] = $getPayTerm->PTRM_DESC04;
			$data['default']['PTRM_DESC05'] = $getPayTerm->PTRM_DESC05;
			$data['default']['OTH_DESC01'] 	= $getPayTerm->OTH_DESC01;
			$data['default']['OTH_DESC02'] 	= $getPayTerm->OTH_DESC02;
			$data['default']['OTH_DESC03'] 	= $getPayTerm->OTH_DESC03;
			$data['default']['OTH_DESC04'] 	= $getPayTerm->OTH_DESC04;
			$data['default']['OTH_DESC05'] 	= $getPayTerm->OTH_DESC05;
			$data['default']['PT_JOBAREA1'] = $getPayTerm->PT_JOBAREA1;
			$data['default']['PT_JOBAREA2'] = $getPayTerm->PT_JOBAREA2;
			$data['default']['PTRM_TYPE'] 	= $getPayTerm->PTRM_TYPE;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= date('Y-m-d H:i:s');
				$MenuCode 		= 'MN080';
				$TTR_CATEG		= 'U';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$this->load->view('v_setting/v_payterm/payterm_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process() // OK
	{
		$this->load->model('m_setting/m_payterm/m_payterm', '', TRUE);
		$this->load->model('m_updash/m_updash', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$IDPT 			= $this->input->post('IDPT');
			$PT_DESC01 		= $this->input->post('PT_DESC01');
			$PT_DESC02 		= $this->input->post('PT_DESC02');
			$PT_DESC03 		= $this->input->post('PT_DESC03');
			$PT_DESC04 		= $this->input->post('PT_DESC04');
			$PT_DESC05 		= $this->input->post('PT_DESC05');
			$PTRM_DESC01 	= $this->input->post('PTRM_DESC01');
			$PTRM_DESC02 	= $this->input->post('PTRM_DESC02');
			$PTRM_DESC03 	= $this->input->post('PTRM_DESC03');
			$PTRM_DESC04 	= $this->input->post('PTRM_DESC04');
			$PTRM_DESC05 	= $this->input->post('PTRM_DESC05');
			$OTH_DESC01 	= $this->input->post('OTH_DESC01');
			$OTH_DESC02 	= $this->input->post('OTH_DESC02');
			$OTH_DESC03 	= $this->input->post('OTH_DESC03');
			$OTH_DESC04 	= $this->input->post('OTH_DESC04');
			$OTH_DESC05 	= $this->input->post('OTH_DESC05');
			$PT_JOBAREA1 	= $this->input->post('PT_JOBAREA1');
			$PT_JOBAREA2 	= $this->input->post('PT_JOBAREA2');
			$PTRM_TYPE 		= $this->input->post('PTRM_TYPE');
			
			$upPayTerm 	= array('PT_DESC01' 	=> $PT_DESC01,
								'PT_DESC02' 	=> $PT_DESC02,
								'PT_DESC03'		=> $PT_DESC03,
								'PT_DESC04'		=> $PT_DESC04,
								'PT_DESC05'		=> $PT_DESC05,
								'PTRM_DESC01' 	=> $PTRM_DESC01,
								'PTRM_DESC02' 	=> $PTRM_DESC02,
								'PTRM_DESC03'	=> $PTRM_DESC03,
								'PTRM_DESC04'	=> $PTRM_DESC04,
								'PTRM_DESC05'	=> $PTRM_DESC05,
								'OTH_DESC01' 	=> $OTH_DESC01,
								'OTH_DESC02' 	=> $OTH_DESC02,
								'OTH_DESC03'	=> $OTH_DESC03,
								'OTH_DESC04'	=> $OTH_DESC04,
								'OTH_DESC05'	=> $OTH_DESC05,
								'PT_JOBAREA1'	=> $PT_JOBAREA1,
								'PT_JOBAREA2'	=> $PT_JOBAREA2,
								'PTRM_TYPE'		=> $PTRM_TYPE,
								'LAST_UPDATE'	=> date('Y-m-d H:i:s'));
										
			$this->m_payterm->update($upPayTerm, $IDPT);
			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= date('Y-m-d H:i:s');
				$MenuCode 		= 'MN080';
				$TTR_CATEG		= 'UP';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
				
			$url			= site_url('c_setting/c_p4yt3rm/p4yt3rm_up/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
}