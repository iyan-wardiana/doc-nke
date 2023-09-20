<?php
/*
 * Author		= Dian Hermanto
 * Create Date	= 22 Oktober 2018
 * File Name	= C_pr0ds73p.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_pr0ds73p extends CI_Controller  
{
  	function __construct() // GOOD
	{
		parent::__construct();
		
		$this->load->model('m_updash/m_updash', '', TRUE);
	}

 	function index() // G
	{
		$this->load->model('m_setting/m_prodstep/m_prodstep', '', TRUE);
		
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
		
		$url			= site_url('c_setting/c_pr0ds73p/gls3xts73p/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}

	function gls3xts73p() // G
	{
		$this->load->model('m_setting/m_prodstep/m_prodstep', '', TRUE);
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
			$LangID 				= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN385';
				$data["MenuCode"] 	= 'MN385';
				$data["MenuApp"] 	= 'MN385';
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($LangID == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$data['addURL'] 	= site_url('c_setting/c_pr0ds73p/a44_pr0ds73p/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['backURL'] 	= site_url('c_setting/c_pr0ds73p/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
			
			$num_rows 			= $this->m_prodstep->count_all_STEP($PRJCODE);
			$data["countSTP"] 	= $num_rows;
	 
			$data['vwSTP'] 		= $this->m_prodstep->get_all_STEP($PRJCODE)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN385';
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
			
			$this->load->view('v_setting/v_prodstep/v_prodstep', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function a44_pr0ds73p() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_setting/m_prodstep/m_prodstep', '', TRUE);
		
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
				$mnCode				= 'MN385';
				$data["MenuCode"] 	= 'MN385';
				$data["MenuApp"] 	= 'MN385';
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($LangID == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$data['form_action']	= site_url('c_setting/c_pr0ds73p/add_process');
			$cancelURL				= site_url('c_setting/c_pr0ds73p/gl20M/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['link'] 			= array('link_back' => anchor("$cancelURL",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			$data['PRJCODE']		= $PRJCODE;
			
			$MenuCode 				= 'MN385';
			$data["MenuCode"] 		= 'MN385';
			$data['viewDocPattern'] = $this->m_prodstep->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN385';
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
	
			$this->load->view('v_setting/v_prodstep/v_prodstep_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function add_process() // G
	{
		$this->load->model('m_setting/m_prodstep/m_prodstep', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		date_default_timezone_set("Asia/Jakarta");
		
		$PRODS_CREATED 	= date('Y-m-d H:i:s');
			
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();
			
			$PRODS_NUM		= date('ymdHis');
			$PRODS_CODE 	= $this->input->post('PRODS_CODE');
			$PRODS_ORDER 	= $this->input->post('PRODS_ORDER');
			if($PRODS_ORDER == 1)
				$PRODS_STEP	= 'ONE';
			elseif($PRODS_ORDER == 2)
				$PRODS_STEP	= 'TWO';
			elseif($PRODS_ORDER == 3)
				$PRODS_STEP	= 'THR';
			elseif($PRODS_ORDER == 4)
				$PRODS_STEP	= 'FOU';
			elseif($PRODS_ORDER == 5)
				$PRODS_STEP	= 'FIV';
			elseif($PRODS_ORDER == 6)
				$PRODS_STEP	= 'SIX';
			elseif($PRODS_ORDER == 7)
				$PRODS_STEP	= 'SEV';
			elseif($PRODS_ORDER == 8)
				$PRODS_STEP	= 'EIG';
			elseif($PRODS_ORDER == 9)
				$PRODS_STEP	= 'NIN';
			elseif($PRODS_ORDER == 10)
				$PRODS_STEP	= 'TEN';
			elseif($PRODS_ORDER == 11)
				$PRODS_STEP	= 'ELV';
			elseif($PRODS_ORDER == 12)
				$PRODS_STEP	= 'TWL';
			elseif($PRODS_ORDER == 13)
				$PRODS_STEP	= 'THD';
			elseif($PRODS_ORDER == 14)
				$PRODS_STEP	= 'FOD';
			elseif($PRODS_ORDER == 15)
				$PRODS_STEP	= 'FID';
			else
				$PRODS_STEP	= 'MAX';

			$PRODS_NAME		= $this->input->post('PRODS_NAME');
			$PRODS_DESC		= $this->input->post('PRODS_DESC');
			$PRODS_STAT		= $this->input->post('PRODS_STAT');			
			$PRODS_LAST		= $this->input->post('PRODS_LAST');
			
			$AddSTP			= array('PRODS_NUM' 		=> $PRODS_NUM,
									'PRODS_CODE' 		=> $PRODS_CODE,
									'PRODS_STEP' 		=> $PRODS_STEP,
									'PRODS_NAME'		=> $PRODS_NAME,
									'PRODS_DESC'		=> $PRODS_DESC,
									'PRODS_STAT'		=> $PRODS_STAT,
									'PRODS_LAST'		=> $PRODS_LAST,
									'PRODS_ORDER'		=> $PRODS_ORDER,
									'PRODS_CREATER' 	=> $DefEmp_ID,
									'PRODS_CREATED' 	=> $PRODS_CREATED);
			$this->m_prodstep->add($AddSTP);
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= $PRODS_NUM;
				$MenuCode 		= 'MN385';
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
			
			$url			= site_url('c_setting/c_pr0ds73p');
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function u775o_pr0ds73p() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_setting/m_prodstep/m_prodstep', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PRODS_NUM			= $_GET['id'];
			$PRODS_NUM			= $this->url_encryption_helper->decode_url($PRODS_NUM);
								
			$getSTP				= $this->m_prodstep->get_stp_by_number($PRODS_NUM)->row();
			$data["MenuCode"] 	= 'MN385';
						
			$data['title'] 		= $appName;
			$data['task']		= 'edit';		
			$LangID 			= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN385';
				$data["MenuCode"] 	= 'MN385';
				$data["MenuApp"] 	= 'MN385';
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($LangID == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$data['form_action']	= site_url('c_setting/c_pr0ds73p/update_process');
			$cancelURL				= site_url('c_setting/c_pr0ds73p/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['link'] 			= array('link_back' => anchor("$cancelURL",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			$MenuCode 				= 'MN385';
			$data["MenuCode"] 		= 'MN385';
		
			$data['default']['PRODS_NUM'] 	= $getSTP->PRODS_NUM;
			$data['default']['PRODS_CODE'] 	= $getSTP->PRODS_CODE;
			$data['default']['PRODS_STEP'] 	= $getSTP->PRODS_STEP;
			$data['default']['PRODS_NAME'] 	= $getSTP->PRODS_NAME;
			$data['default']['PRODS_DESC'] 	= $getSTP->PRODS_DESC;
			$data['default']['PRODS_STAT'] 	= $getSTP->PRODS_STAT;
			$data['default']['PRODS_LAST'] 	= $getSTP->PRODS_LAST;
			$data['default']['PRODS_ORDER'] = $getSTP->PRODS_ORDER;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= $getSTP->PRODS_NUM;
				$MenuCode 		= 'MN385';
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
			
			$this->load->view('v_setting/v_prodstep/v_prodstep_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process() // G
	{
		$this->load->model('m_setting/m_prodstep/m_prodstep', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		date_default_timezone_set("Asia/Jakarta");
		
		$PRODS_CREATED 	= date('Y-m-d H:i:s');
			
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();
			
			$PRODS_NUM		= $this->input->post('PRODS_NUM');
			$PRODS_CODE 	= $this->input->post('PRODS_CODE');
			$PRODS_ORDER 	= $this->input->post('PRODS_ORDER');
			if($PRODS_ORDER == 1)
				$PRODS_STEP	= 'ONE';
			elseif($PRODS_ORDER == 2)
				$PRODS_STEP	= 'TWO';
			elseif($PRODS_ORDER == 3)
				$PRODS_STEP	= 'THR';
			elseif($PRODS_ORDER == 4)
				$PRODS_STEP	= 'FOU';
			elseif($PRODS_ORDER == 5)
				$PRODS_STEP	= 'FIV';
			elseif($PRODS_ORDER == 6)
				$PRODS_STEP	= 'SIX';
			elseif($PRODS_ORDER == 7)
				$PRODS_STEP	= 'SEV';
			elseif($PRODS_ORDER == 8)
				$PRODS_STEP	= 'EIG';
			elseif($PRODS_ORDER == 9)
				$PRODS_STEP	= 'NIN';
			elseif($PRODS_ORDER == 10)
				$PRODS_STEP	= 'TEN';
			elseif($PRODS_ORDER == 11)
				$PRODS_STEP	= 'ELV';
			elseif($PRODS_ORDER == 12)
				$PRODS_STEP	= 'TWL';
			elseif($PRODS_ORDER == 13)
				$PRODS_STEP	= 'THD';
			elseif($PRODS_ORDER == 14)
				$PRODS_STEP	= 'FOD';
			elseif($PRODS_ORDER == 15)
				$PRODS_STEP	= 'FID';
			else
				$PRODS_STEP	= 'MAX';

			$PRODS_NAME		= $this->input->post('PRODS_NAME');
			$PRODS_DESC		= $this->input->post('PRODS_DESC');
			$PRODS_STAT		= $this->input->post('PRODS_STAT');			
			$PRODS_LAST		= $this->input->post('PRODS_LAST');
			
			$UpdSTP			= array('PRODS_CODE' 		=> $PRODS_CODE,
									'PRODS_STEP' 		=> $PRODS_STEP,
									'PRODS_NAME'		=> $PRODS_NAME,
									'PRODS_DESC'		=> $PRODS_DESC,
									'PRODS_STAT'		=> $PRODS_STAT,
									'PRODS_LAST'		=> $PRODS_LAST,
									'PRODS_ORDER'		=> $PRODS_ORDER);
			$this->m_prodstep->updateSTP($PRODS_NUM, $UpdSTP);
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= $PRODS_NUM;
				$MenuCode 		= 'MN385';
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
			
			$url			= site_url('c_setting/c_pr0ds73p/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
}