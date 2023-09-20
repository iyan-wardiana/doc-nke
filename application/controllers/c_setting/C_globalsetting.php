<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 7 Februari 2017
 * File Name	= C_globalsetting.php
 * Location		= -
*/

class C_globalsetting  extends CI_Controller
{
	var $limit = 2;
	var $title = 'NKE ITSys';
	
 	// Start : Index tiap halaman
 	public function index()
	{		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_setting/c_globalsetting/index1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	public function index1($offset=0)
	{
		$this->load->model('m_setting/m_globalsetting/m_globalsetting', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName				= $_GET['id'];
			$appName				= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['title'] 			= $appName;
			$data['Emp_DeptCode'] 	= $this->session->userdata('Emp_DeptCode');
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Pengaturan Umum";
				$data["h2_title"] 	= "pengaturan";
			}
			else
			{
				$data["h1_title"] 	= "Global Setting";
				$data["h2_title"] 	= "setting";
			}
			
			$data['form_action']	= site_url('c_setting/c_globalsetting/update_process');
			$data['link'] 			= array('link_back' => anchor('c_setting/c_globalsetting/','<input type="button" name="btnCancel" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data["MenuCode"] 		= 'MN075';
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN075';
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
			
			$data['viewglobalsetting'] 	= $this->m_globalsetting->viewglobalsetting();
			$data['viewCurrency'] 		= $this->m_globalsetting->viewCurrency();
			
			$this->load->view('v_setting/v_globalsetting/globalsetting_form', $data);
		}
		else
		{
			redirect('login');
		}
	}
	// End
	
	function update_process()
	{
		$this->load->model('m_setting/m_globalsetting/m_globalsetting', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$genSett	= $this->input->post('genSett');
		$invDP		= $this->input->post('invDP');
		$invMC		= $this->input->post('invMC');
		
		if($genSett == 1)
		{
			$globalsetting = array('Display_Rows' 	=> $this->input->post('Display_Rows'), 
							'decFormat'			=> $this->input->post('decFormat'),
							'currency_ID'		=> $this->input->post('currency_ID'),
							'purchasePrice'		=> $this->input->post('purchasePrice'),
							'salesPrice'		=> $this->input->post('salesPrice'),
							'RateType_SO'		=> $this->input->post('RateType_SO'),
							'RateType_PO'		=> $this->input->post('RateType_PO'),
							'RateType_SN'		=> $this->input->post('RateType_SN'),
							'RateType_RR'		=> $this->input->post('RateType_RR'),
							'RateType_SI'		=> $this->input->post('RateType_SI'),
							'RateType_VI'		=> $this->input->post('RateType_VI'),
							'RateType_GL'		=> $this->input->post('RateType_GL'),
							'recountType'		=> $this->input->post('recountType'),
							'isUpdOutApp'		=> $this->input->post('isUpdOutApp'),
							'isUpdProfLoss'		=> $this->input->post('isUpdProfLoss'),
							'ACC_ID_RDP'		=> $this->input->post('ACC_ID_RDP'),
							'ACC_ID_IR'			=> $this->input->post('ACC_ID_IR'),
							'ACC_ID_PROD'		=> $this->input->post('ACC_ID_PROD'),
							'ACC_ID_SN'			=> $this->input->post('ACC_ID_SN'),
							'ACC_ID_SPOT'		=> $this->input->post('ACC_ID_SPOT'),
							'ACC_ID_SPPN'		=> $this->input->post('ACC_ID_SPPN'),
							'ACC_ID_SPPH'		=> $this->input->post('ACC_ID_SPPH'),
							'ACC_ID_RET'		=> $this->input->post('ACC_ID_RET'),
							'ACC_ID_POT'		=> $this->input->post('ACC_ID_POT'),
							'ACC_ID_MC'			=> $this->input->post('ACC_ID_MC'),
							'RESET_JOURN'		=> $this->input->post('RESET_JOURN'),
							'APPLEV'			=> $this->input->post('APPLEV')); 
			$this->m_globalsetting->update($globalsetting);
		
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= 'UPD-GENSET';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN075';
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
		}
		elseif($invDP == 1)
		{
			$globalsetting = array('Display_Rows' 	=> $this->input->post('Display_Rows'), 
							'decFormat'			=> $this->input->post('decFormat'),
							'currency_ID'		=> $this->input->post('currency_ID'),
							'purchasePrice'		=> $this->input->post('purchasePrice'),
							'salesPrice'		=> $this->input->post('salesPrice'),
							'RateType_SO'		=> $this->input->post('RateType_SO'),
							'RateType_PO'		=> $this->input->post('RateType_PO'),
							'RateType_SN'		=> $this->input->post('RateType_SN'),
							'RateType_RR'		=> $this->input->post('RateType_RR'),
							'RateType_SI'		=> $this->input->post('RateType_SI'),
							'RateType_VI'		=> $this->input->post('RateType_VI'),
							'RateType_GL'		=> $this->input->post('RateType_GL'),
							'recountType'		=> $this->input->post('recountType'),
							'isUpdOutApp'		=> $this->input->post('isUpdOutApp'),
							'isUpdProfLoss'		=> $this->input->post('isUpdProfLoss'),
							'ACC_ID_RDP'		=> $this->input->post('ACC_ID_RDP'),
							'ACC_ID_IR'			=> $this->input->post('ACC_ID_IR'),
							'ACC_ID_PROD'		=> $this->input->post('ACC_ID_PROD'),
							'ACC_ID_SN'			=> $this->input->post('ACC_ID_SN'),
							'ACC_ID_SPOT'		=> $this->input->post('ACC_ID_SPOT'),
							'ACC_ID_SPPN'		=> $this->input->post('ACC_ID_SPPN'),
							'ACC_ID_SPPH'		=> $this->input->post('ACC_ID_SPPH'),
							'ACC_ID_RET'		=> $this->input->post('ACC_ID_RET'),
							'ACC_ID_POT'		=> $this->input->post('ACC_ID_POT'),
							'ACC_ID_MC'			=> $this->input->post('ACC_ID_MC'),
							'RESET_JOURN'		=> $this->input->post('RESET_JOURN')); 
			$this->m_globalsetting->update($globalsetting);
		
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= 'UPD-INCDP';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN075';
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
		}
		elseif($invMC == 1)
		{
			$globalsetting = array( 'ACC_ID_MCR'	=> $this->input->post('ACC_ID_MCR'),
									'ACC_ID_MCP'	=> $this->input->post('ACC_ID_MCP'),
									'ACC_ID_MCT'	=> $this->input->post('ACC_ID_MCT'),
									'ACC_ID_MCRET'	=> $this->input->post('ACC_ID_MCRET'),
									'ACC_ID_MCI'	=> $this->input->post('ACC_ID_MCI'),
									'ACC_ID_MCPPn'	=> $this->input->post('ACC_ID_MCPPn')); 
			$this->m_globalsetting->update($globalsetting);
		
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= 'UPD-INVMC';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN075';
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
		}
		
		$MJR_APP1	= $this->input->post('MJR_APP');
		$selStep	= 0;
		$MJR_APP	= '';
		if($MJR_APP1 != '')
		{
			foreach ($MJR_APP1 as $sel_pm)
			{
				$selStep	= $selStep + 1;
				if($selStep == 1)
				{
					$user_to	= explode ("|",$sel_pm);
					$user_ID	= $user_to[0];
					$MJR_APP	= $user_ID;
					//$coll_MADD	= $user_ADD;
				}
				else
				{					
					$user_to	= explode ("|",$sel_pm);
					$user_ID	= $user_to[0];			
					$MJR_APP	= "$user_ID";
				}
			}
		}
		$mjr_app 	= array('Emp_ID1' 	=> $MJR_APP); 
		$this->m_globalsetting->updateMJRAPP($mjr_app, $MJR_APP);
		
		$url			= site_url('c_setting/c_globalsetting/index1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
}