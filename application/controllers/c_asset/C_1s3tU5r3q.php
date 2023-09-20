<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 11 Agustus 2018
 * File Name	= C_1s3tU5r3q.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_1s3tU5r3q extends CI_Controller  
{
 	public function index() // G
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_asset/c_1s3tU5r3q/listproject/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function listproject($offset=0) // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Permintaan Aset";
				$data["h3_title"] 	= "Permintaan Aset";
			}
			else
			{
				$data["h1_title"] 	= "Asset Request";
				$data["h3_title"] 	= "Permintaan Aset";
			}
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN017';	 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_asset/c_1s3tU5r3q/index1/?id=";
			
			$data["secVIEW"]	= 'v_projectlist/project_list';
			if($this->session->userdata['nSELP'] == 0)
			{
				$PRJCODE	= $this->session->userdata['proj_Code'];
				$url		= site_url($data["secURL"].$this->url_encryption_helper->encode_url($PRJCODE));
				redirect($url);
			}
			else
			{
				$this->load->view($data["secVIEW"], $data);
			}
		}
		else
		{
			redirect('__l1y');
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
					
			$data['title'] 			= $appName;			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h2_title"] 	= "Permintaan Aset";
				$data["h3_title"] 	= "Permintaan Aset";
			}
			else
			{
				$data["h2_title"] 	= "Asset Request";
				$data["h3_title"] 	= "Permintaan Aset";
			}
			
			$linkBack					= site_url('c_asset/c_1s3tU5r3q/?id='.$this->url_encryption_helper->encode_url($appName));
			//$data['link'] 				= array('link_back' => anchor("$linkBack",'<input type="button" class="btn btn-danger" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 			= $linkBack;
			$data['PRJCODE']			= $PRJCODE;
			
			$num_rows 					= $this->m_asset_usagereq->count_all_num_rows($PRJCODE);
			$data["recordcount"] 		= $num_rows;
	 		$data["MenuCode"] 			= 'MN061';
			$data['vAssetUsage']		= $this->m_asset_usagereq->get_last_ten_AU($PRJCODE)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN061';
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
			
			$this->load->view('v_asset/v_asset_usagereq/asset_usagereq', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	// End
	
	function a441s3tU5r3q() // OK
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
			
			$data['title'] 			= $appName;
			
			$docPatternPosition		= 'Especially';	
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h2_title"] 	= "Permintaan Aset";
				$data["h3_title"] 	= "Permintaan Aset";
			}
			else
			{
				$data["h2_title"] 	= "Asset Request";
				$data["h3_title"] 	= "Permintaan Aset";
			}
			
			$data['form_action']	= site_url('c_asset/c_1s3tU5r3q/add_process');
			$linkBack				= site_url('c_asset/c_1s3tU5r3q/index1/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			//$data['link'] 			= array('link_back' => anchor("$linkBack",'<input type="button" class="btn btn-danger" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 	= $linkBack;
			$data['PRJCODE']	= $PRJCODE;	
			$MenuCode 			= 'MN061';
			$data["MenuCode"] 	= 'MN061';
			$data["MenuCode1"] 	= 'MN061';
			$data['vwDocPatt']	= $this->m_asset_usagereq->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN061';
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
			
			$this->load->view('v_asset/v_asset_usagereq/asset_usagereq_form', $data);
		}
		else
		{
			redirect('__l1y');
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
			redirect('__l1y');
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
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $AUR_CODE;
				$MenuCode 		= 'MN061';
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
			
			$url			= site_url('c_asset/c_1s3tU5r3q/index1/?id='.$this->url_encryption_helper->encode_url($AUR_PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
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
			$data['form_action']	= site_url('c_asset/c_1s3tU5r3q/update_process');
			$data["MenuCode"] 		= 'MN061';
			$linkBack				= site_url('c_asset/c_1s3tU5r3q/index1/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			//$data['link'] 			= array('link_back' => anchor("$linkBack",'<input type="button" class="btn btn-danger" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 		= $linkBack;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $AUR_CODE;
				$MenuCode 		= 'MN061';
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
			
			$this->load->view('v_asset/v_asset_usagereq/asset_usagereq_form', $data);
		}
		else
		{
			redirect('__l1y');
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
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $AUR_CODE;
				$MenuCode 		= 'MN061';
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
			
			$url			= site_url('c_asset/c_1s3tU5r3q/index1/?id='.$this->url_encryption_helper->encode_url($AUR_PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
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
			redirect('__l1y');
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
			$data['secAddURL'] 			= site_url('c_asset/c_1s3tU5r3q/add/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['srch_url'] 			= site_url('c_asset/c_1s3tU5r3q/get_last_ten_docpattern_src/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['PRJCODE']			= $PRJCODE;
			
			$num_rows 					= $this->m_asset_usagereq->count_all_num_rows_inb($PRJCODE);
			$data["recordcount"] 		= $num_rows;
	 
			$data['vAssetUsage']		= $this->m_asset_usagereq->get_last_ten_AU($PRJCODE)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $AUR_CODE;
				$MenuCode 		= 'AST_REQ';
				$TTR_CATEG		= 'APP-L';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$this->load->view('v_asset/v_asset_usagereq/inb_asset_usagereq', $data);
		}
		else
		{
			redirect('__l1y');
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
			$data['form_action']	= site_url('c_asset/c_1s3tU5r3q/inbox_update_process');
			$linkBack				= site_url('c_asset/c_1s3tU5r3q/index1/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['link'] 			= array('link_back' => anchor("$linkBack",'<input type="button" class="btn btn-danger" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $AUR_CODE;
				$MenuCode 		= 'AST_REQ';
				$TTR_CATEG		= 'APP-U';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$this->load->view('v_asset/v_asset_usagereq/inb_asset_usagereq_form', $data);
		}
		else
		{
			redirect('__l1y');
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
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $AUR_CODE;
				$MenuCode 		= 'AST_REQ';
				$TTR_CATEG		= 'APP-UP';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$url			= site_url('c_asset/c_1s3tU5r3q/inbox_reqlist/?id='.$this->url_encryption_helper->encode_url($AUR_PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
}