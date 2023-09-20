<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 11 April 2017
 * File Name	= C_asset_usagereq.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_asset_usagereq extends CI_Controller  
{
 	public function index() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_asset/c_asset_usagereq/listproject/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function listproject($offset=0) // OK
	{
		$this->load->model('m_asset/m_asset_usagereq', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
					
			$data['title'] 				= $appName;
			$data['h2_title']			= 'Project List';
			$data['h3_title']			= 'usage request';
			
			$num_rows 					= $this->m_asset_usagereq->count_all_project();
			$data["recordcount"] 		= $num_rows;	 
			$data['vewproject']			= $this->m_asset_usagereq->get_last_ten_project()->result();
			
			$this->load->view('v_asset/v_asset_usagereq/listproject', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function index1()
	{
		$this->load->model('m_asset/m_asset_usagereq', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
					
			$data['title'] 				= $appName;
			$data['h2_title']			= 'Usage Request';
			$data['h3_title']			= 'asset management';
			$linkBack					= site_url('c_asset/c_asset_usagereq/?id='.$this->url_encryption_helper->encode_url($appName));
			//$data['link'] 				= array('link_back' => anchor("$linkBack",'<input type="button" class="btn btn-danger" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 			= $linkBack;
			$data['PRJCODE']			= $PRJCODE;
			
			$num_rows 					= $this->m_asset_usagereq->count_all_num_rows($PRJCODE);
			$data["recordcount"] 		= $num_rows;
	 		$data["MenuCode"] 			= 'MN061';
			$data['vAssetUsage']		= $this->m_asset_usagereq->get_last_ten_AU($PRJCODE)->result();
			
			$this->load->view('v_asset/v_asset_usagereq/asset_usagereq', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	// End
	
	function add() // OK
	{
		$this->load->model('m_asset/m_asset_usagereq', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			
			$data['title'] 				= $appName;
			
			$docPatternPosition 		= 'Especially';	
			$data['title'] 				= $appName;
			$data['task'] 				= 'add';
			$data['h2_title']			= 'Add Usage Request';
			$data['h3_title']			= 'asset management';
			$data['form_action']		= site_url('c_asset/c_asset_usagereq/add_process');
			$linkBack					= site_url('c_asset/c_asset_usagereq/index1/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			//$data['link'] 				= array('link_back' => anchor("$linkBack",'<input type="button" class="btn btn-danger" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 			= $linkBack;
			$data['PRJCODE']			= $PRJCODE;
			$data["MenuCode"] 			= 'MN061';			
			$this->load->view('v_asset/v_asset_usagereq/asset_usagereq_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function popupallasset()
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_asset/m_asset_usagereq', '', TRUE);
			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName 	= $therow->app_name;		
			endforeach;
			$varURL			= $_GET['id'];
			$varURL			= $this->url_encryption_helper->decode_url($varURL);
			$varURLArr 		= explode("|", $varURL);
			$PRJCODE 		= $varURLArr[0];
			$StartDate 		= $varURLArr[1];
			$EndDate 		= $varURLArr[2];
			
			$data['title'] 				= $appName;
			$data['h2_title'] 			= 'Asset List';
			$data['h3_title'] 			= 'asset';
			$data['PRJCODE'] 			= $PRJCODE;
			$data['StartDate'] 			= $StartDate;
			$data['EndDate'] 			= $EndDate;
			
			$data['reCountAllAsset'] 	= $this->m_asset_usagereq->count_all_num_rowsAllAsset($PRJCODE, $StartDate, $EndDate);
			$data['viewAllAsset'] 		= $this->m_asset_usagereq->viewAllIAsset($PRJCODE, $StartDate, $EndDate)->result();
					
			$this->load->view('v_asset/v_asset_usagereq/asset_usagereq_selectasset', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function add_process() // OK
	{	
		$this->load->model('m_asset/m_asset_usagereq', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$this->db->trans_begin();
			
			$AUR_CODE		= $this->input->post('AUR_CODE');
			$AUR_PRJCODE 	= $this->input->post('AUR_PRJCODE');
			$AUR_STAT		= $this->input->post('AUR_STAT');
			
			date_default_timezone_set("Asia/Jakarta");
			$AUR_CONFD	= '';
			$AUR_APPD	= '';
			
			$AUR_STARTD		= date('Y-m-d',strtotime($this->input->post('AUR_STARTD')));
			$AUR_STARTT 	= date('H:i:s',strtotime($this->input->post('AUR_STARTT')));
			$AUR_STARTD		= "$AUR_STARTD $AUR_STARTT";
			
			$AUR_ENDD		= date('Y-m-d',strtotime($this->input->post('AUR_STARTT')));
			$AUR_ENDT		= date('H:i:s',strtotime($this->input->post('AUR_ENDT')));
			$AUR_ENDD		= "$AUR_ENDD $AUR_ENDT";
			
			if($AUR_STAT == 2)
			{				
				$AUR_CONFD 	= date('Y-m-d H:i:s');
			}
			else if($AUR_STAT == 3)
			{
				$AUR_APPD 	= date('Y-m-d H:i:s');
			}
			
			$InsAU 		= array('AUR_CODE' 		=> $this->input->post('AUR_CODE'),
								'AUR_JOBCODE'	=> $this->input->post('AUR_JOBCODE'),
								'AUR_AS_CODE'	=> $this->input->post('AUR_AS_CODE'),
								'AUR_DATE'		=> date('Y-m-d',strtotime($this->input->post('AUR_DATE'))),
								'AUR_PRJCODE'	=> $this->input->post('AUR_PRJCODE'),
								'AUR_DESC'		=> $this->input->post('AUR_DESC'),
								'AUR_STARTD'	=> $AUR_STARTD,
								'AUR_ENDD'		=> $AUR_ENDD,
								'AUR_STARTT'	=> $AUR_STARTT,
								'AUR_ENDT'		=> $AUR_ENDT,
								'AUR_STAT'		=> $this->input->post('AUR_STAT'),
								'AUR_CONFD'		=> $AUR_CONFD,
								'AUR_APPD'		=> $AUR_APPD,
								'Patt_Number'	=> $this->input->post('Patt_Number'));
												
			$this->m_asset_usagereq->add($InsAU);
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_asset/c_asset_usagereq/index1/?id='.$this->url_encryption_helper->encode_url($AUR_PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update() // OK
	{	
		$this->load->model('m_asset/m_asset_usagereq', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$AUR_CODE	= $_GET['id'];
			$AUR_CODE	= $this->url_encryption_helper->decode_url($AUR_CODE);
			
			$getAU 					= $this->m_asset_usagereq->get_AU($AUR_CODE)->row();
			
			$PRJCODE				= $getAU->AUR_PRJCODE;
			
			$data['default']['AUR_CODE'] 		= $getAU->AUR_CODE;
			$data['default']['AUR_JOBCODE'] 	= $getAU->AUR_JOBCODE;
			$data['default']['AUR_AS_CODE'] 	= $getAU->AUR_AS_CODE;
			$data['default']['AUR_DATE'] 		= $getAU->AUR_DATE;
			$data['default']['AUR_PRJCODE'] 	= $getAU->AUR_PRJCODE;
			$data['default']['AUR_DESC'] 		= $getAU->AUR_DESC;
			$data['default']['AUR_STARTD'] 		= $getAU->AUR_STARTD;
			$data['default']['AUR_ENDD'] 		= $getAU->AUR_ENDD;
			$data['default']['AUR_STARTT'] 		= $getAU->AUR_STARTT;
			$data['default']['AUR_ENDT'] 		= $getAU->AUR_ENDT;
			$data['default']['AUR_STAT'] 		= $getAU->AUR_STAT;
			$data['default']['AUR_CONFD'] 		= $getAU->AUR_CONFD;
			$data['default']['AUR_APPD'] 		= $getAU->AUR_APPD;
			$data['default']['Patt_Number'] 	= $getAU->Patt_Number;
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title']		= 'Add usage request';
			$data['h3_title']		= 'asset management';
			$data['form_action']	= site_url('c_asset/c_asset_usagereq/update_process');
			$data["MenuCode"] 		= 'MN061';
			$linkBack				= site_url('c_asset/c_asset_usagereq/index1/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			//$data['link'] 			= array('link_back' => anchor("$linkBack",'<input type="button" class="btn btn-danger" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 		= $linkBack;
			$this->load->view('v_asset/v_asset_usagereq/asset_usagereq_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update_process()
	{	
		$this->load->model('m_asset/m_asset_usagereq', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$this->db->trans_begin();
			
			$AUR_CODE		= $this->input->post('AUR_CODE');
			$AUR_PRJCODE 	= $this->input->post('AUR_PRJCODE');
			$AUR_STAT		= $this->input->post('AUR_STAT');
			
			date_default_timezone_set("Asia/Jakarta");
			$AUR_CONFD	= '';
			$AUR_APPD	= '';			
			
			
			$AUR_STARTD		= date('Y-m-d',strtotime($this->input->post('AUR_STARTD')));
			$AUR_STARTT 	= date('H:i:s',strtotime($this->input->post('AUR_STARTT')));
			$AUR_STARTD		= "$AUR_STARTD $AUR_STARTT";
			
			$AUR_ENDD		= date('Y-m-d',strtotime($this->input->post('AUR_STARTT')));
			$AUR_ENDT		= date('H:i:s',strtotime($this->input->post('AUR_ENDT')));
			$AUR_ENDD		= "$AUR_ENDD $AUR_ENDT";
			
			if($AUR_STAT == 2)
			{				
				$AUR_CONFD 	= date('Y-m-d H:i:s');
			}
			else if($AUR_STAT == 3)
			{
				$AUR_APPD 	= date('Y-m-d H:i:s');
			}
			
			$UpdAU 		= array('AUR_CODE' 		=> $this->input->post('AUR_CODE'),
								'AUR_JOBCODE'	=> $this->input->post('AUR_JOBCODE'),
								'AUR_AS_CODE'	=> $this->input->post('AS_CODE'),
								'AUR_DATE'		=> date('Y-m-d',strtotime($this->input->post('AUR_DATE'))),
								'AUR_PRJCODE'	=> $this->input->post('AUR_PRJCODE'),
								'AUR_DESC'		=> $this->input->post('AUR_DESC'),
								'AUR_STARTD'	=> $AUR_STARTD,
								'AUR_ENDD'		=> $AUR_ENDD,
								'AUR_STARTT'	=> $AUR_STARTT,
								'AUR_ENDT'		=> $AUR_ENDT,
								'AUR_STAT'		=> $this->input->post('AUR_STAT'),
								'AUR_CONFD'		=> $AUR_CONFD,
								'AUR_APPD'		=> $AUR_APPD,
								'Patt_Number'	=> $this->input->post('Patt_Number'));
								
			$this->m_asset_usagereq->update($AUR_CODE, $UpdAU);
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_asset/c_asset_usagereq/index1/?id='.$this->url_encryption_helper->encode_url($AUR_PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function inbox($offset=0) // OK
	{
		$this->load->model('m_asset/m_asset_usagereq', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		if ($this->session->userdata('login') == TRUE)
		{					
			$data['title'] 				= $appName;
			$data['h2_title']			= 'Project List';
			$data['h3_title']			= 'usage request inbox';
			
			$num_rows 					= $this->m_asset_usagereq->count_all_project_inb();
			$data["recordcount"] 		= $num_rows;	 
			$data['vewproject']			= $this->m_asset_usagereq->get_last_ten_project_inb()->result();
			
			$this->load->view('v_asset/v_asset_usagereq/inb_listproject', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function inbox_reqlist()
	{
		$this->load->model('m_asset/m_asset_usagereq', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
					
			$data['title'] 				= $appName;
			$data['h2_title']			= 'Usage Request';
			$data['h3_title']			= 'asset management';
			$data['secAddURL'] 			= site_url('c_asset/c_asset_usagereq/add/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['srch_url'] 			= site_url('c_asset/c_asset_usagereq/get_last_ten_docpattern_src/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['PRJCODE']			= $PRJCODE;
			
			$num_rows 					= $this->m_asset_usagereq->count_all_num_rows_inb($PRJCODE);
			$data["recordcount"] 		= $num_rows;
	 
			$data['vAssetUsage']		= $this->m_asset_usagereq->get_last_ten_AU($PRJCODE)->result();
			
			$this->load->view('v_asset/v_asset_usagereq/inb_asset_usagereq', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function inbox_update() // OK
	{	
		$this->load->model('m_asset/m_asset_usagereq', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$AUR_CODE	= $_GET['id'];
			$AUR_CODE	= $this->url_encryption_helper->decode_url($AUR_CODE);
			
			$getAU 					= $this->m_asset_usagereq->get_AU($AUR_CODE)->row();
			
			$PRJCODE				= $getAU->AUR_PRJCODE;
			
			$data['default']['AUR_CODE'] 		= $getAU->AUR_CODE;
			$data['default']['AUR_JOBCODE'] 	= $getAU->AUR_JOBCODE;
			$data['default']['AUR_AS_CODE'] 	= $getAU->AUR_AS_CODE;
			$data['default']['AUR_DATE'] 		= $getAU->AUR_DATE;
			$data['default']['AUR_PRJCODE'] 	= $getAU->AUR_PRJCODE;
			$data['default']['AUR_DESC'] 		= $getAU->AUR_DESC;
			$data['default']['AUR_STARTD'] 		= $getAU->AUR_STARTD;
			$data['default']['AUR_ENDD'] 		= $getAU->AUR_ENDD;
			$data['default']['AUR_STARTT'] 		= $getAU->AUR_STARTT;
			$data['default']['AUR_ENDT'] 		= $getAU->AUR_ENDT;
			$data['default']['AUR_STAT'] 		= $getAU->AUR_STAT;
			$data['default']['AUR_CONFD'] 		= $getAU->AUR_CONFD;
			$data['default']['AUR_APPD'] 		= $getAU->AUR_APPD;
			$data['default']['Patt_Number'] 	= $getAU->Patt_Number;
			
			$data['title'] 			= $appName;
			$data['h2_title']		= 'usage request';
			$data['h3_title']		= 'approve';
			$data['form_action']	= site_url('c_asset/c_asset_usagereq/inbox_update_process');
			$linkBack				= site_url('c_asset/c_asset_usagereq/index1/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['link'] 			= array('link_back' => anchor("$linkBack",'<input type="button" class="btn btn-danger" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			$this->load->view('v_asset/v_asset_usagereq/inb_asset_usagereq_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function inbox_update_process()
	{	
		$this->load->model('m_asset/m_asset_usagereq', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$this->db->trans_begin();
			
			$AUR_CODE		= $this->input->post('AUR_CODE');
			$AUR_PRJCODE 	= $this->input->post('AUR_PRJCODE');
			$AUR_STAT		= $this->input->post('AUR_STAT');
			
			date_default_timezone_set("Asia/Jakarta");
			$AUR_CONFD	= '';
			$AUR_APPD	= '';			
			
			
			$AUR_STARTD		= date('Y-m-d',strtotime($this->input->post('AUR_STARTD')));
			$AUR_STARTT 	= date('H:i:s',strtotime($this->input->post('AUR_STARTT')));
			$AUR_STARTD		= "$AUR_STARTD $AUR_STARTT";
			
			$AUR_ENDD		= date('Y-m-d',strtotime($this->input->post('AUR_STARTT')));
			$AUR_ENDT		= date('H:i:s',strtotime($this->input->post('AUR_ENDT')));
			$AUR_ENDD		= "$AUR_ENDD $AUR_ENDT";
			
			if($AUR_STAT == 2)
			{				
				$AUR_CONFD 	= date('Y-m-d H:i:s');
			}
			else if($AUR_STAT == 3)
			{
				$AUR_APPD 	= date('Y-m-d H:i:s');
			}
			
			$UpdAU 		= array('AUR_CODE' 		=> $this->input->post('AUR_CODE'),
								'AUR_STAT'		=> $this->input->post('AUR_STAT'),
								'AUR_APPD'		=> $AUR_APPD);
								
			$this->m_asset_usagereq->update($AUR_CODE, $UpdAU);
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_asset/c_asset_usagereq/inbox_reqlist/?id='.$this->url_encryption_helper->encode_url($AUR_PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
}