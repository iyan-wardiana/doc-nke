<?php
/*
 * Author		= Dian Hermanto
 * Create Date	= 26 April 2019
 * File Name	= C_m4cH1n.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_m4cH1n extends CI_Controller  
{
  	function __construct() // GOOD
	{
		parent::__construct();
		
		$this->load->model('m_updash/m_updash', '', TRUE);
	}

 	function index() // G
	{
		$this->load->model('m_setting/m_machine/m_machine', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;	
		endforeach;

		$sqlPRJ		= "SELECT PRJCODE FROM tbl_project  WHERE isHO = 1";
		$resPRJ 	= $this->db->query($sqlPRJ)->result();
		foreach($resPRJ as $rowPRJ) :
			$PRJCODE = $rowPRJ->PRJCODE;		
		endforeach;
		
		$url			= site_url('c_setting/c_m4ch1n/gls3xtm4ch/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
		redirect($url);
	}

	function gls3xtm4ch() // G
	{
		$this->load->model('m_setting/m_machine/m_machine', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);

			$data['title'] 		= $appName;		
			$LangID 			= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN398';
				$data["MenuCode"] 	= 'MN398';
				$data["MenuApp"] 	= 'MN398';
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($LangID == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$data['addURL'] 	= site_url('c_setting/c_m4ch1n/a44_m4ch/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['backURL'] 	= site_url('c_setting/c_m4ch1n/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
			
			$num_rows 			= $this->m_machine->count_all_STEP($PRJCODE);
			$data["countSTP"] 	= $num_rows;
	 
			$data['vwSTP'] 		= $this->m_machine->get_all_STEP($PRJCODE)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN398';
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
			
			$this->load->view('v_setting/v_machine/v_machine', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function a44_m4ch() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_setting/m_machine/m_machine', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE			= $_GET['id'];
			$PRJCODE			= $this->url_encryption_helper->decode_url($PRJCODE);
			$EmpID 				= $this->session->userdata('Emp_ID');

			$data['title'] 		= $appName;
			$data['task']		= 'add';		
			$LangID 			= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN398';
				$data["MenuCode"] 	= 'MN398';
				$data["MenuApp"] 	= 'MN398';
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($LangID == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$data['form_action']	= site_url('c_setting/c_m4ch1n/add_process');
			$cancelURL				= site_url('c_setting/c_m4ch1n/gl20M/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['link'] 			= array('link_back' => anchor("$cancelURL",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			$data['PRJCODE']		= $PRJCODE;
			
			$MenuCode 				= 'MN398';
			$data["MenuCode"] 		= 'MN398';
			$data['viewDocPattern'] = $this->m_machine->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN398';
				$TTR_CATEG		= 'A';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
	
			$this->load->view('v_setting/v_machine/v_machine_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function add_process() // G
	{
		$this->load->model('m_setting/m_machine/m_machine', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		date_default_timezone_set("Asia/Jakarta");
		
		$MCN_CREATED 	= date('Y-m-d H:i:s');
			
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();
			
			$MCN_NUM		= date('ymdHis');
			$MCN_CODE 		= $this->input->post('MCN_CODE');
			$MCN_NAME		= $this->input->post('MCN_NAME');
			$MCN_DESC		= $this->input->post('MCN_DESC');
			$MCN_ITMCAL		= $this->input->post('MCN_ITMCAL');
			$MCN_STAT		= $this->input->post('MCN_STAT');

			$MCNITMCODE		= $this->input->post('MCN_ITMCODE');
			if($MCNITMCODE != '')
			{
				$refStep	= 0;
				foreach ($MCNITMCODE as $ITM_REFCODE)
				{
					$refStep	= $refStep + 1;
					if($refStep == 1)
					{
						$MCN_ITMCODE	= "$ITM_REFCODE";
					}
					else
					{
						$MCN_ITMCODE	= "$MCN_ITMCODE~$ITM_REFCODE";
					}
				}
			}
			else
			{
				$MCN_ITMCODE	= '';
			}

			$MCNPSTEP		= $this->input->post('MCN_PSTEP');
			if($MCNPSTEP != '')
			{
				$refStep	= 0;
				foreach ($MCNPSTEP as $PSTEP_REFCODE)
				{
					$refStep	= $refStep + 1;
					if($refStep == 1)
					{
						$MCN_PSTEP	= "$PSTEP_REFCODE";
					}
					else
					{
						$MCN_PSTEP	= "$MCN_PSTEP~$PSTEP_REFCODE";
					}
				}
			}
			else
			{
				$MCN_PSTEP	= '';
			}
			
			$AddMCN			= array('MCN_NUM' 		=> $MCN_NUM,
									'MCN_CODE' 		=> $MCN_CODE,
									'MCN_NAME'		=> $MCN_NAME,
									'MCN_DESC'		=> $MCN_DESC,
									'MCN_ITMCODE'	=> $MCN_ITMCODE,
									'MCN_PSTEP'		=> $MCN_PSTEP,
									'MCN_ITMCAL'	=> $MCN_ITMCAL,
									'MCN_STAT'		=> $MCN_STAT,
									'MCN_CREATER' 	=> $DefEmp_ID,
									'MCN_CREATED' 	=> $MCN_CREATED);
			$this->m_machine->add($AddMCN);
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= $MCN_NUM;
				$MenuCode 		= 'MN398';
				$TTR_CATEG		= 'C';
				
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
			
			$url			= site_url('c_setting/c_m4ch1n');
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function u775o_m4ch() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_setting/m_machine/m_machine', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;

		$sqlPRJ		= "SELECT PRJCODE FROM tbl_project  WHERE isHO = 1";
		$resPRJ 	= $this->db->query($sqlPRJ)->result();
		foreach($resPRJ as $rowPRJ) :
			$PRJCODE = $rowPRJ->PRJCODE;		
		endforeach;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		
		if ($this->session->userdata('login') == TRUE)
		{
			$MCN_NUM			= $_GET['id'];
			$MCN_NUM			= $this->url_encryption_helper->decode_url($MCN_NUM);
								
			$getSTP				= $this->m_machine->get_stp_by_number($MCN_NUM)->row();
			$data["MenuCode"] 	= 'MN398';
						
			$data['title'] 		= $appName;
			$data['task']		= 'edit';		
			$LangID 			= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN398';
				$data["MenuCode"] 	= 'MN398';
				$data["MenuApp"] 	= 'MN398';
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($LangID == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$data['form_action']	= site_url('c_setting/c_m4ch1n/update_process');
			$cancelURL				= site_url('c_setting/c_m4ch1n/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['link'] 			= array('link_back' => anchor("$cancelURL",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			$MenuCode 				= 'MN398';
			$data["MenuCode"] 		= 'MN398';
			$data["PRJCODE"] 		= $PRJCODE;
		
			$data['default']['MCN_NUM'] 	= $getSTP->MCN_NUM;
			$data['default']['MCN_CODE'] 	= $getSTP->MCN_CODE;
			$data['default']['MCN_NAME'] 	= $getSTP->MCN_NAME;
			$data['default']['MCN_DESC'] 	= $getSTP->MCN_DESC;
			$data['default']['MCN_ITMCODE'] = $getSTP->MCN_ITMCODE;
			$data['default']['MCN_PSTEP'] 	= $getSTP->MCN_PSTEP;
			$data['default']['MCN_ITMCAL'] 	= $getSTP->MCN_ITMCAL;
			$data['default']['MCN_STAT'] 	= $getSTP->MCN_STAT;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= $getSTP->MCN_NUM;
				$MenuCode 		= 'MN398';
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
			
			$this->load->view('v_setting/v_machine/v_machine_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process() // G
	{
		$this->load->model('m_setting/m_machine/m_machine', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		date_default_timezone_set("Asia/Jakarta");
		
		$MCN_CREATED 	= date('Y-m-d H:i:s');
			
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();
			
			$MCN_NUM		= $this->input->post('MCN_NUM');
			$MCN_CODE 		= $this->input->post('MCN_CODE');
			$MCN_NAME		= $this->input->post('MCN_NAME');
			$MCN_DESC		= $this->input->post('MCN_DESC');
			$MCN_ITMCAL		= $this->input->post('MCN_ITMCAL');
			$MCN_STAT		= $this->input->post('MCN_STAT');

			$MCNITMCODE		= $this->input->post('MCN_ITMCODE');
			if($MCNITMCODE != '')
			{
				$refITM	= 0;
				foreach ($MCNITMCODE as $ITM_REFCODE)
				{
					$refITM	= $refITM + 1;
					if($refITM == 1)
					{
						$MCN_ITMCODE	= "$ITM_REFCODE";
					}
					else
					{
						$MCN_ITMCODE	= "$MCN_ITMCODE~$ITM_REFCODE";
					}
				}
			}
			else
			{
				$MCN_ITMCODE	= '';
			}

			$MCNPSTEP		= $this->input->post('MCN_PSTEP');
			if($MCNPSTEP != '')
			{
				$refStep	= 0;
				foreach ($MCNPSTEP as $PSTEP_REFCODE)
				{
					$refStep	= $refStep + 1;
					if($refStep == 1)
					{
						$MCN_PSTEP	= "$PSTEP_REFCODE";
					}
					else
					{
						$MCN_PSTEP	= "$MCN_PSTEP~$PSTEP_REFCODE";
					}
				}
			}
			else
			{
				$MCN_PSTEP	= '';
			}

			$UpdMCN			= array('MCN_CODE' 		=> $MCN_CODE,
									'MCN_NAME'		=> $MCN_NAME,
									'MCN_DESC'		=> $MCN_DESC,
									'MCN_ITMCODE'	=> $MCN_ITMCODE,
									'MCN_PSTEP'		=> $MCN_PSTEP,
									'MCN_ITMCAL'	=> $MCN_ITMCAL,
									'MCN_STAT'		=> $MCN_STAT);
			$this->m_machine->updateMCN($MCN_NUM, $UpdMCN);
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= $MCN_NUM;
				$MenuCode 		= 'MN398';
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
			
			$url			= site_url('c_setting/c_m4ch1n/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
}