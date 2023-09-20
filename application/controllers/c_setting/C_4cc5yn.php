<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 15 Januari 2019
 * File Name	= C_4cc5yn.php
 * Location		= -
*/

class C_4cc5yn extends CI_Controller
{
 	public function index() // G
	{		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_setting/c_4cc5yn/cc5yn_l41nd/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function cc5yn_l41nd() // G
	{
		$this->load->model('m_setting/m_tax/m_tax', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
					
			$data['title'] 			= $appName;		
			$LangID 				= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['h1_title']	= 'Sinkronisasi Akun';
				$data['h2_title']	= 'Pengaturan';
			}
			else
			{
				$data['h1_title']	= 'Account Syncronization';
				$data['h2_title']	= 'Setting';
			}
			
			$data['form_action']	= site_url('c_setting/c_4cc5yn/update_process');
			$data["MenuCode"] 		= 'MN387';
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN387';
				$TTR_CATEG		= 'ACC_SYNC';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$this->load->view('v_setting/v_accsync/v_accsync', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process() // G
	{	
		$this->load->model('m_gl/m_coa/m_coa', '', TRUE);	
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
				
		$this->db->trans_begin();
		
		// COUNT ALL PROJECT RELATION
			$PRJCODE	= $_POST['PRJCODE'];
						
			$packPRJ	= $_POST['packPRJ'];
			$TOTPROJ	= count($packPRJ);
			if (count($TOTPROJ)>0)
			{
				$selPRJ	= $_POST['packPRJ'];
				$row		= 0;
				foreach ($selPRJ as $projCode)
				{
					$row	= $row + 1;
					if($row == 1)
					{
						$PRJCODE1	= $projCode;
					}
					else
					{
						$PRJCODE1	= "$PRJCODE1~$projCode";
					}
				}
			}
			
			$packACC	= $_POST['packACC'];
			$TOTACC		= count($packACC);
			if (count($TOTACC)>0)
			{
				$selACC	= $_POST['packACC'];
				$row		= 0;
				foreach ($selACC as $ACCCODE)
				{
					$sqlUPDCOA	= "UPDATE tbl_chartaccount SET isSync = 1, syncPRJ = '$PRJCODE1'
									WHERE Account_Number =  '$ACCCODE' AND PRJCODE = '$PRJCODE'";
					$resUPDCOA	= $this->db->query($sqlUPDCOA);
				}
			}
			
		$MenuCode 			= 'MN387';
		$data["MenuCode"] 	= 'MN387';
		$data["selPRJCODE"] = 'AllPRJ';
			
		$data['secAdd'] 	= site_url('c_gl/c_ch1h0fbeart/ch1h0fbeart_upl/?id='.$this->url_encryption_helper->encode_url($collPRJ));
		
		// START : UPDATE TO T-TRACK
			date_default_timezone_set("Asia/Jakarta");
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			$TTR_PRJCODE	= '';
			$TTR_REFDOC		= '';
			$MenuCode 		= 'MN387';
			$TTR_CATEG		= 'UP';
			
			$this->load->model('m_updash/m_updash', '', TRUE);				
			$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
									'TTR_DATE' 		=> date('Y-m-d H:i:s'),
									'TTR_MNCODE'	=> $MenuCode,
									'TTR_CATEG'		=> $TTR_CATEG,
									'TTR_PRJCODE'	=> $TTR_PRJCODE,
									'TTR_REFDOC'	=> $TTR_REFDOC);
			$this->m_updash->updateTrack($paramTrack);
		// END : UPDATE TO T-TRACK
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
		}
		else
		{
			$this->db->trans_commit();
		}
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_setting/c_4cc5yn/cc5yn_l41nd/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
}