<?php
/*  
 * Author		= Hendar Permana 
 * Create Date	= 26 Mei 2017
 * File Name	= c_spp.php
 * Location		= -
*/

/*  
 * Author		= Hendar Permana 
 * Create Date	= 27 Juli 2017
 * File Name	= c_mail.php
 * Location		= -
*/

class C_mail  extends CI_Controller  
{
 	public function index()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_setting/c_mail/index1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	public function index1($offset=0)
	{
		$this->load->model('m_setting/m_mail/m_mail', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
					
			//$data['secAddURL'] 		= site_url('c_setting/c_mail_sd/add/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['h2_title'] 		= 'Mail List';
			$data['h3_title'] 		= 'Setting';
			$data['main_view'] 		= 'v_setting/v_mail/v_mail';
			$data['moffset'] 		= $offset;
			$data['perpage'] 		= 20;
			$data["MenuCode"] 		= 'MN293';
			
			$num_rows 				= $this->m_mail->count_all_num_rows($DefEmp_ID);
			$data["recordcount"] 	= $num_rows;
	 
			$data['viewmail'] = $this->m_mail->get_last_mail($DefEmp_ID)->result();
			
			$this->load->view('v_setting/v_mail/v_mail', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	/*function get_last_mailproj($offset=0)
	{
		$this->load->model('m_setting/m_mail/m_mail', '', TRUE);
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
						
			$data['title'] 				= $appName;
			$data['h2_title'] 			= 'SPP List';
			$data['h3_title'] 			= 'Setting';
			$data['main_view'] 			= 'v_setting/v_mail/v_mail';			
			$data['link'] 				= array('link_back' => anchor('c_setting/c_mail/','<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Back" />', array('style' => 'text-decoration: none;')));
			$data['PRJCODE'] 			= $PRJCODE;
			$data['moffset'] 			= $offset;
			
			$num_rows 					= $this->m_mail->count_all_num_rowsMR($PRJCODE);
			$data["recordcount"] 		= $num_rows;
	 
			$data['viewmail'] = $this->m_mail->get_last_mail($PRJCODE)->result();
			
			$this->load->view('v_setting/v_mail/v_mail', $data);
		}
		else
		{
			redirect('Auth');
		}
	}*/
	
	function add()
	{
		$this->load->model('m_setting/m_mail/m_mail', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$MG_CODE	= $_GET['id'];
			$MG_CODE	= $this->url_encryption_helper->decode_url($MG_CODE);
						
			$secSelItmURL				= site_url('c_setting/c_mail/popupallitem/?id='.$this->url_encryption_helper->encode_url($MG_CODE));
			$backURL					= site_url('c_setting/c_mail/index1/?id='.$this->url_encryption_helper->encode_url($MG_CODE));
			
			//$data['PRJCODE'] 			= $PRJCODE;	
			$docPatternPosition 		= 'Especially';	
			$data['title'] 				= $appName;
			$data['task'] 				= 'add';
			$data['h2_title']			= 'Add Grup';			
			$data['h2_title']			= 'Mail';
			$data['main_view'] 			= 'v_setting/v_mail/v_mail_form';
			$data['form_action']		= site_url('c_setting/c_mail/add_process');
			//$data['link'] 				= array('link_back' => anchor("$backURL",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 			= $backURL;
			$data["MenuCode"] 			= 'MN293';
			
			$data['recordcountProject']	= $this->m_mail->count_all_num_rowsProject();
			$data['viewProject'] 		= $this->m_mail->viewProject()->result();
			
			$MenuCode 					= 'MN101';
			$data['viewDocPattern'] 	= $this->m_mail->getDataDocPat($MenuCode)->result();
			
			$this->load->view('v_setting/v_mail/v_mail_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function popupallitem()
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_setting/m_mail/m_mail', '', TRUE);
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			
			$data['title'] 				= $appName;
			$data['h2_title'] 			= 'List Employee';
			$data['main_view'] 			= 'v_setting/v_mail/v_mail_form';
			$data['form_action']		= site_url('c_setting/c_mail/update_process');
			$data['PRJCODE'] 			= $PRJCODE;
			$data['secShowAll']			= site_url('c_setting/c_mail/popupallitem/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			$data['recordcountAllItem'] = $this->m_mail->count_all_num_rowsAllItem();
			$data['viewAllItem'] 		= $this->m_mail->viewAllItemMatBudget()->result();
					
			$this->load->view('v_setting/v_mail/v_mail_selectitem', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function add_process()
	{
		$this->load->model('m_setting/m_mail/m_mail', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{			
			//$SPPSTAT 					= $this->input->post('SPPSTAT'); // 1 = New, 2 = confirm, 3 = Close
			//$APPROVE 					= 1; // 1 = New, 2 = Awaiting, 3 = Approve, 4 = Revise, 5 = Reject
			//$Doc_Status 				= 1; // 1 = Open, 2 = confirm, 3 = Invoice, 4 = Close
			
			//setting MR Date
			//$TRXDATE					= date('Y-m-d',strtotime($this->input->post('TRXDATE')));
			//$Patt_Year					= date('Y',strtotime($this->input->post('TRXDATE')));
			//$Patt_Month					= date('m',strtotime($this->input->post('TRXDATE')));
			//$Patt_Date					= date('d',strtotime($this->input->post('TRXDATE')));
			
			//$PRJCODE 					= $this->input->post('PRJCODE');
			//$SPPNUM 					= $this->input->post('SPPNUM');
			//$DEPCODE					= $this->input->post('DEPCODE');
			
			$MG_CODE 					= $this->input->post('MG_CODE');
			$MG_NAME 					= $this->input->post('MG_NAME');
			echo "$MG_CODE - $MG_NAME";
			//return false;
			$projMatReqH = array('MG_CODE' 	=> $this->input->post('MG_CODE'),
							'MG_NAME' 	=> $this->input->post('MG_NAME'));
							/*'SPPCODE' 		=> $this->input->post('SPPCODE'),
							'DEPCODE'		=> $this->input->post('DEPCODE'),
							'TRXDATE'		=> $TRXDATE,
							'PRJCODE'		=> $PRJCODE,
							'TRXUSER'		=> $DefEmp_ID,
							'APPROVE'		=> $APPROVE,
							'SPPNOTE'		=> $this->input->post('SPPNOTE'),
							'SPPSTAT'		=> $SPPSTAT, 
							'Patt_Year'		=> $Patt_Year, 
							'Patt_Month'	=> $Patt_Month,
							'Patt_Date'		=> $Patt_Date,
							'Patt_Number'	=> $this->input->post('lastPatternNumb'));*/
							
			$this->m_mail->add($projMatReqH);
			
			// UPDATE TO TRANS-COUNT	
			//$STAT_BEFORE			= $this->input->post('STAT_BEFORE');	
			//$parameters = array(
			//		'DOC_CODE' 		=> $SPPNUM,
			//		'PRJCODE' 		=> $PRJCODE,
			//		'TR_TYPE'		=> "SPP",
			//		'TBL_NAME' 		=> "tbl_mail_header",// TABLE NAME
			//		'KEY_NAME'		=> "SPPNUM",		// KEY OF THE TABLE 
			//		'STAT_NAME' 	=> "SPPSTAT",		// NAMA FIELD STATUS
			//		'APPSTATDOC' 	=> "",
			//		'APPSTATDOCBEF'	=> $STAT_BEFORE,	// STAT SEBELUMNYA
			//		'FIELD_NM_CONF' => "SPP_VALUE",		// NAMA FIELD CONFIRM PADA TABEL
			//		'FIELD_NM_APP'	=> "SPP_VALUEAPP",	// NAMA FIELD APPROVED PADA TABEL
			//		'FIELD_NM_DASH1'=> "TOT_REQ",		// NAMA FIELD PADA TABEL tbl_dash_data (Confirm)
			//		'FIELD_NM_DASH2'=> "TOT_REQAPP"		// NAMA FIELD PADA TABEL tbl_dash_data (Approve)
			//	);
				
			//$this->m_mail->updateDashData($parameters);
			
			foreach($_POST['data'] as $d)
			{
				//$MGD_CODE = $d->MG_CODE;
				//echo "$MGD_CODE<br>";
				 $this->db->insert('tbl_mailgroup_detail',$d);
				//$this->m_mail->add($projMatReqH);
			}
			$url			= site_url('c_setting/c_mail/index1/?id='.$this->url_encryption_helper->encode_url($MG_CODE));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update()
	{
		$this->load->model('m_setting/m_mail/m_mail', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$MG_CODE		= $_GET['id'];
		$MG_CODE		= $this->url_encryption_helper->decode_url($MG_CODE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 					= $appName;
			$data['task'] 					= 'edit';
			$data['h2_title'] 				= 'Update';
			$data['main_view'] 				= 'v_setting/v_mail/v_mail_form';
			$data['form_action']			= site_url('c_setting/c_mail/update_process');
			$data["MenuCode"] 				= 'MN293';
			
			$data['recordcountProject']		= $this->m_mail->count_all_num_rowsProject();
			$data['viewProject'] 			= $this->m_mail->viewProject()->result();
			
			$getpurreq 						= $this->m_mail->get_Mail_by_number($MG_CODE)->row();
			
			$data['default']['MG_CODE'] 	= $getpurreq->MG_CODE;
			$data['default']['MG_NAME']		= $getpurreq->MG_NAME;
			/*$data['PRJCODE']				= $getpurreq->PRJCODE;
			$data['default']['SPPCODE']		= $getpurreq->SPPCODE;
			$PRJCODE 						= $getpurreq->PRJCODE;
			$data['default']['DEPCODE'] 	= $getpurreq->DEPCODE;
			$data['default']['TRXDATE'] 	= $getpurreq->TRXDATE;
			$data['default']['PRJCODE'] 	= $getpurreq->PRJCODE;
			$data['default']['TRXOPEN'] 	= $getpurreq->TRXOPEN;
			$data['default']['TRXUSER'] 	= $getpurreq->TRXUSER;
			$data['default']['APPROVE'] 	= $getpurreq->APPROVE;
			$data['default']['APPRUSR'] 	= $getpurreq->APPRUSR;
			$data['default']['JOBCODE'] 	= $getpurreq->JOBCODE;
			$data['default']['SPPNOTE'] 	= $getpurreq->SPPNOTE;
			$data['default']['SPPSTAT'] 	= $getpurreq->SPPSTAT;
			$data['default']['PRJNAME'] 	= $getpurreq->PRJNAME;
			$data['default']['Patt_Year'] 	= $getpurreq->Patt_Year;
			$data['default']['Patt_Month'] 	= $getpurreq->Patt_Month;
			$data['default']['Patt_Date'] 	= $getpurreq->Patt_Date;
			$data['default']['Patt_Number']	= $getpurreq->Patt_Number;
			$data['default']['REVMEMO']		= $getpurreq->REVMEMO;
			$data['default']['SPP_VALUE']	= $getpurreq->SPP_VALUE;
			$data['default']['SPP_VALUEAPP']= $getpurreq->SPP_VALUEAPP;*/
			
			$cancelURL					= site_url('c_setting/c_mail/index1/?id='.$this->url_encryption_helper->encode_url($MG_CODE));
			//$data['link'] 				= array('link_back' => anchor("$cancelURL",'<input type="button" name="btnCancel" id="btnCancel" value="Cancel" class="btn btn-primary" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 			= $cancelURL;
			
			$data['secSelItmURL']	= site_url('c_setting/c_mail/popupallitem/?id='.$this->url_encryption_helper->encode_url($MG_CODE));
			$data['secShowAll']		= site_url('c_setting/c_mail/popupallitem/?id='.$this->url_encryption_helper->encode_url($MG_CODE));
			
			$this->load->view('v_setting/v_mail/v_mail_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update_process()
	{
		$this->load->model('m_setting/m_mail/m_mail', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			//$SPPSTAT 					= $this->input->post('SPPSTAT'); // 1 = New, 2 = confirm, 3 = Close
			//$APPROVE 					= 1; // 1 = New, 2 = Awaiting, 3 = Approve, 4 = Revise, 5 = Reject
			//$Doc_Status 				= 1; // 1 = Open, 2 = confirm, 3 = Invoice, 4 = Close
			
			//setting MR Date
			//$TRXDATE					= date('Y-m-d',strtotime($this->input->post('TRXDATE')));
			//$Patt_Year					= date('Y',strtotime($this->input->post('TRXDATE')));
			//$Patt_Month					= date('m',strtotime($this->input->post('TRXDATE')));
			//$Patt_Date					= date('d',strtotime($this->input->post('TRXDATE')));
			
			//$SPPNUM 					= $this->input->post('SPPNUM');
			//$SPPCODE 					= $this->input->post('SPPCODE');
			//$PRJCODE 					= $this->input->post('PRJCODE');
			//$DEPCODE					= $this->input->post('DEPCODE');
			$MG_CODE					= $this->input->post('MG_CODE');
			$MG_NAME					= $this->input->post('MG_NAME');
			
			$projMatReqH = array('MG_CODE' 	=> $this->input->post('MG_CODE'),
							'MG_NAME' 	=> $this->input->post('MG_NAME'));
							
							/*'SPPNUM' 	=> $this->input->post('SPPNUM'),
							'SPPCODE' 		=> $this->input->post('SPPCODE'),
							'DEPCODE'		=> $this->input->post('DEPCODE'),
							'TRXDATE'		=> $TRXDATE,
							'PRJCODE'		=> $PRJCODE,
							'TRXUSER'		=> $DefEmp_ID,
							'APPROVE'		=> $APPROVE,
							'SPPNOTE'		=> $this->input->post('SPPNOTE'),
							'SPPSTAT'		=> $SPPSTAT, 
							'Patt_Year'		=> $Patt_Year, 
							'Patt_Month'	=> $Patt_Month,
							'Patt_Date'		=> $Patt_Date,
							'Patt_Number'	=> $this->input->post('lastPatternNumb'));*/
										
			$this->m_mail->update($MG_CODE, $projMatReqH);
			$this->m_mail->deleteDetail($MG_CODE);
			
			foreach($_POST['data'] as $d)
			{
				$this->db->insert('tbl_mailgroup_detail',$d);
			}
			
			// UPDATE TO TRANS-COUNT
			// Dapatkan status MR sebelum ada perubahan status
				/*$sqlSPPN 		= "SELECT APPROVE FROM tbl_mail_header WHERE SPPNUM = '$SPPNUM' AND PRJCODE = '$PRJCODE'";
				$resSPPN 		= $this->db->query($sqlSPPN)->result();
				foreach($resSPPN as $rowSPPN) :
					$APPROVEBEF = $rowSPPN->APPROVE;		
				endforeach;*/
				
				/*if($SPPSTAT != $APPROVEBEF)
				{
					if($SPPSTAT == 1) // Maka, status tetap belum ada perubahan, jadi tidak ada yang diupdate
					{
						// No comment
					}
					elseif($SPPSTAT == 2) // Maka, status sebelumnya pasti 1, ada peningkatan status. Ingat!!! Status tidak bisa turun ke bawah
					{
						// 1. Kurangi 1 hitungan untuk MR dengan status TR_NEW
							$sqlTRS 		= "SELECT TR_NEW FROM trans_count WHERE PRJCODE = '$PRJCODE' AND TR_TYPE = 'SPP'";
							$resTRS			= $this->db->query($sqlTRS)->result();
							foreach($resTRS as $rowTRS) :
								$TR_NEWBEF = $rowTRS->TR_NEW;		
							endforeach;							
							$TR_NEWNOW		= $TR_NEWBEF - 1;				
							$sqlUpd1		= "UPDATE trans_count SET TR_NEW = $TR_NEWNOW WHERE PRJCODE = '$PRJCODE' AND TR_TYPE = 'SPP'";
							$this->db->query($sqlUpd1);
							
						// 2. Tambahkan 1 hitungan untuk MR dengan status TR_CONFIRM						
							$sqlTRS 		= "SELECT TR_CONFIRM FROM trans_count WHERE PRJCODE = '$PRJCODE' AND TR_TYPE = 'SPP'";
							$resTRS			= $this->db->query($sqlTRS)->result();
							foreach($resTRS as $rowTRS) :
								$TR_CONFIRMBEF = $rowTRS->TR_CONFIRM;		
							endforeach;							
							$TR_CONFIRMNOW	= $TR_CONFIRMBEF + 1;
							$sqlUpd2		= "UPDATE trans_count SET TR_CONFIRM = $TR_CONFIRMNOW WHERE PRJCODE = '$PRJCODE' AND TR_TYPE = 'SPP'";
							$this->db->query($sqlUpd2);
					}
					elseif($SPPSTAT == 6) 	// Maka, status sebelumnya pasti 1 (status 2 tidak bisa diedit jadi close oleh creater), 
											// ada peningkatan status. Ingat!!! Status tidak bisa turun ke bawah
					{
						// 1. Kurangi 1 hitungan untuk MR dengan status TR_NEW
							$sqlTRS 		= "SELECT TR_NEW FROM trans_count WHERE PRJCODE = '$PRJCODE' AND TR_TYPE = 'SPP'";
							$resTRS			= $this->db->query($sqlTRS)->result();
							foreach($resTRS as $rowTRS) :
								$TR_NEWBEF = $rowTRS->TR_NEW;		
							endforeach;							
							$TR_NEWNOW		= $TR_NEWBEF - 1;			
							$sqlUpd1		= "UPDATE trans_count SET TR_NEW = $TR_NEWNOW WHERE PRJCODE = '$PRJCODE' AND TR_TYPE = 'SPP'";
							$this->db->query($sqlUpd1);
							
						// 2. Tambahkan 1 hitungan untuk MR dengan status TR_CONFIRM						
							$sqlTRS 		= "SELECT TR_CLOSE1 FROM trans_count WHERE PRJCODE = '$PRJCODE' AND TR_TYPE = 'SPP'";
							$resTRS			= $this->db->query($sqlTRS)->result();
							foreach($resTRS as $rowTRS) :
								$TR_CLOSE1BEF = $rowTRS->TR_CLOSE1;		
							endforeach;							
							$TR_CLOSE1NOW	= $TR_CLOSE1BEF + 1;				
							$sqlUpd2		= "UPDATE trans_count SET TR_CLOSE1 = $TR_CLOSE1NOW WHERE PRJCODE = '$PRJCODE' AND TR_TYPE = 'SPP'";
							$this->db->query($sqlUpd2);
					}
				}*/
				
			$url			= site_url('c_setting/c_mail/index1/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
	
 	/*public function inbox()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_setting/c_mail/inbox1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}*/
	
	/*public function inbox1($offset=0)
	{
		$this->load->model('m_setting/m_mail/m_mail', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			//$data['secAddURL'] 		= site_url('c_setting/c_mail_sd/add/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['h2_title']			= 'Planning List';
			$data['main_view'] 			= 'v_project/v_material_request/project_planning_inb';
			//$data['srch_url'] 		= $this->mza_secureurl->setSecureUrl_encode(site_url('c_setting/c_mail_sd'),'inbox_src');
			$data['moffset'] 			= $offset;

			//$num_rows = $this->m_mail->count_all_num_rows();
			$num_rows 					= $this->m_mail->count_all_num_rowsInbox($DefEmp_ID);		
			$data["recordcount"] 		= $num_rows;
			
			$config 					= array();
			$config['base_url'] 		= site_url('c_setting/project_planning/get_last_ten_project');
			$config["total_rows"] 		= $num_rows;
			$config["per_page"] 		= 20;
			$config["uri_segment"] 		= 3;				
			$config['full_tag_open'] 	= '<ul class="tsc_pagination tsc_paginationA tsc_paginationA01">';
			$config['full_tag_close'] 	= '</ul>';
			$config['prev_link'] 		= '&lt;';
			$config['prev_tag_open'] 	= '<li>';
			$config['prev_tag_close'] 	= '</li>';
			$config['next_link'] 		= '&gt;';
			$config['next_tag_open'] 	= '<li>';
			$config['next_tag_close'] 	= '</li>';
			$config['cur_tag_open'] 	= '<li class="current"><a href="#">';
			$config['cur_tag_close'] 	= '</a></li>';
			$config['num_tag_open'] 	= '<li>';
			$config['num_tag_close'] 	= '</li>';			
			$config['first_tag_open'] 	= '<li>';
			$config['first_tag_close'] 	= '</li>';
			$config['last_tag_open'] 	= '<li>';
			$config['last_tag_close'] 	= '</li>';			
			$config['first_link'] 		= '&lt;&lt;';
			$config['last_link'] 		= '&gt;&gt;';
	 
			$this->pagination->initialize($config);
		
			$data['vewproject'] 		= $this->m_mail->get_last_ten_projectInbox($config["per_page"], $offset, $DefEmp_ID)->result();
			$data["pagination"] 		= $this->pagination->create_links();
			
			$this->load->view('v_project/v_material_request/project_planning_inb', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
*/
/*	function get_last_ten_projMatReqInb($offset=0)
	{
		$this->load->model('m_setting/m_mail/m_mail', '', TRUE);
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
			
			// Secure URL
			$DefEmp_ID 					= $this->session->userdata['Emp_ID'];
			$DefProj_Code		 		= $PRJCODE; // Session Project Per User
			
			$data['title'] 				= $appName;
			$data['h2_title']			= 'Material Request';
			$data['main_view'] 			= 'v_project/v_material_request/material_request_inbox';
			//$data['srch_url']			= site_url('c_setting/c_mail/get_last_ten_projMatReqInb_src/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			$data['link'] 				= array('link_back' => anchor('c_setting/c_mail/inbox','<input type="button" name="btnCancel" id="btnCancel" class="btn btn-primary" value="Back" />', array('style' => 'text-decoration: none;')));
			$data['PRJCODE'] 			= $PRJCODE;
			$data['moffset'] 			= $offset;	
			
			$num_rows 					= $this->m_mail->count_all_num_rowsMR_Inb($PRJCODE);
			$data["recordcount"] 		= $num_rows;
			$data["recordcount1"]		= $num_rows;
			$config						= array();
			
			$config['base_url'] 		= site_url('c_setting/c_mail_sd/get_last_mail');
			$config["total_rows"] 		= $num_rows;
			$config["per_page"] 		= 15;
			$config["uri_segment"] 		= 3;				
			$config['full_tag_open'] 	= '<ul class="tsc_pagination tsc_paginationA tsc_paginationA01">';
			$config['full_tag_close'] 	= '</ul>';
			$config['prev_link'] 		= '&lt;';
			$config['prev_tag_open'] 	= '<li>';
			$config['prev_tag_close'] 	= '</li>';
			$config['next_link'] 		= '&gt;';
			$config['next_tag_open'] 	= '<li>';
			$config['next_tag_close'] 	= '</li>';
			$config['cur_tag_open'] 	= '<li class="current"><a href="#">';
			$config['cur_tag_close'] 	= '</a></li>';
			$config['num_tag_open'] 	= '<li>';
			$config['num_tag_close'] 	= '</li>';			
			$config['first_tag_open'] 	= '<li>';
			$config['first_tag_close'] 	= '</li>';
			$config['last_tag_open'] 	= '<li>';
			$config['last_tag_close'] 	= '</li>';			
			$config['first_link'] 		= '&lt;&lt;';
			$config['last_link'] 		= '&gt;&gt;';
	 
			$this->pagination->initialize($config);
	
			$data['viewprojmatreq'] 	= $this->m_mail->get_last_ten_projMatReqInb($PRJCODE, $config["per_page"], $offset)->result();
			$data["pagination"] 		= $this->pagination->create_links();
			
			//Start : Searching Function --- Untuk delete session
			//$dataSessSrc = array(
			//		'selSearchproj_Code' => $DefProj_Code,
			//		'selSearchType' => $this->input->post('selSearchType'),
			//		'txtSearch' => $this->input->post('txtSearch'));
			//$this->session->set_userdata('dtSessSrc1', $dataSessSrc);
			//$dataSessSrc   = $this->session->userdata('dtSessSrc1');
			// End : Searching Function	
			
			
			$this->load->view('v_project/v_material_request/material_request_inbox', $data);
		}
		else
		{
			redirect('Auth');
		}
	}*/
	
	/*function update_inbox()
	{
		$this->load->model('m_setting/m_mail/m_mail', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$SPPNUM		= $_GET['id'];
		$SPPNUM		= $this->url_encryption_helper->decode_url($SPPNUM);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 					= $appName;
			$data['task'] 					= 'edit';
			$data['h2_title'] 				= 'Update Request';
			$data['main_view'] 				= 'v_project/v_material_request/material_request_form_inbox';
			$data['form_action']			= site_url('c_setting/c_mail/update_process_inbox');
			
			$data['recordcountProject']		= $this->m_mail->count_all_num_rowsProject();
			$data['viewProject'] 			= $this->m_mail->viewProject()->result();
			
			$getpurreq 						= $this->m_mail->get_MR_by_number($SPPNUM)->row();
			$data['default']['SPPNUM'] 		= $getpurreq->SPPNUM;
			$data['default']['PRJCODE']		= $getpurreq->PRJCODE;
			$data['default']['SPPCODE']		= $getpurreq->SPPCODE;
			$PRJCODE 						= $getpurreq->PRJCODE;
			$data['PRJCODE']				= $getpurreq->PRJCODE;
			$data['default']['TRXDATE'] 	= $getpurreq->TRXDATE;
			$data['default']['PRJCODE'] 	= $getpurreq->PRJCODE;
			$data['default']['TRXOPEN'] 	= $getpurreq->TRXOPEN;
			$data['default']['TRXUSER'] 	= $getpurreq->TRXUSER;
			$data['default']['APPROVE'] 	= $getpurreq->APPROVE;
			$data['default']['APPRUSR'] 	= $getpurreq->APPRUSR;
			$data['default']['JOBCODE'] 	= $getpurreq->JOBCODE;
			$data['default']['SPPNOTE'] 	= $getpurreq->SPPNOTE;
			$data['default']['SPPSTAT'] 	= $getpurreq->SPPSTAT;
			$data['default']['REVMEMO'] 	= $getpurreq->REVMEMO;
			$data['default']['PRJNAME'] 	= $getpurreq->PRJNAME;
			$data['default']['Patt_Year'] 	= $getpurreq->Patt_Year;
			$data['default']['Patt_Month'] 	= $getpurreq->Patt_Month;
			$data['default']['Patt_Date'] 	= $getpurreq->Patt_Date;
			$data['default']['Patt_Number']	= $getpurreq->Patt_Number;
			
			
			$cancelURL						= site_url('c_setting/c_mail/get_last_ten_projMatReqInb/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['link'] 					= array('link_back' => anchor("$cancelURL",'<input type="button" name="btnCancel" id="btnCancel" value="Cancel" class="btn btn-primary" />', array('style' => 'text-decoration: none;')));			
			
			$this->load->view('v_project/v_material_request/material_request_form_inbox', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
*/	
	/*function update_process_inbox()
	{
		$this->load->model('m_setting/m_mail/m_mail', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			// Setting Approve Date
			date_default_timezone_set("Asia/Jakarta");
			
			$APPDATE 			= date('Y-m-d H:i:s');
			$PRJCODE 			= $this->input->post('PRJCODE');
			$SPPNUM 			= $this->input->post('SPPNUM');
			$SPPCODE 			= $this->input->post('SPPCODE');
			$REVMEMO			= $this->input->post('REVMEMO');
			$APPROVE			= $this->input->post('APPROVE');
			$APPRUSR			= $this->session->userdata['Emp_ID'];
			
			// Dapatkan status MR sebelum ada perubahan status
			$sqlSPPN 		= "SELECT APPROVE FROM sd_tmail_header WHERE SPPNUM = '$SPPNUM' AND PRJCODE = '$PRJCODE'";
			$resSPPN 		= $this->db->query($sqlSPPN)->result();
			foreach($resSPPN as $rowSPPN) :
				$APPROVEBEF = $rowSPPN->APPROVE;		
			endforeach;
			
			$projMatReqH = array('APPROVE'		=> $APPROVE,
							'SPPSTAT'			=> $APPROVE,
							'REVMEMO'			=> $REVMEMO,
							'APPDATE'			=> $APPDATE,
							'APPRUSR'			=> $APPRUSR);
							
			$this->m_mail->update_inbox($SPPNUM, $projMatReqH);
			if($APPROVE == 3)
			{
				foreach($_POST['data'] as $d)
				{
					$SPPVOLM 	= $d['SPPVOLM'];
					$CSTCODE 	= $d['CSTCODE'];
					$parameters = array(
							'PRJCODE' 	=> $PRJCODE,
							'SPPNUM' 	=> $SPPNUM,
							'SPPCODE' 	=> $SPPCODE,
							'SPPVOLM' 	=> $SPPVOLM,
							'CSTCODE' 	=> $CSTCODE							
						);
					$this->m_mail->updatePP($SPPNUM, $parameters);
				}
			}
			
			// UPDATE TO TRANS-COUNT
				if($APPROVEBEF != $APPROVE)
				{
					// Kurangi 1 hitungan untuk MR dengan status TR_CONFIRM
						$sqlTRS 		= "SELECT TR_CONFIRM FROM trans_count WHERE PRJCODE = '$PRJCODE' AND TR_TYPE = 'SPP'";
						$resTRS			= $this->db->query($sqlTRS)->result();
						foreach($resTRS as $rowTRS) :
							$TR_CONFIRMBEF = $rowTRS->TR_CONFIRM;		
						endforeach;							
						$TR_CONFIRMNOW		= $TR_CONFIRMBEF - 1;				
						$sqlUpd1		= "UPDATE trans_count SET TR_CONFIRM = $TR_CONFIRMNOW WHERE PRJCODE = '$PRJCODE' AND TR_TYPE = 'SPP'";
						$this->db->query($sqlUpd1);
						
					// JIKA APPROVE
					if($APPROVE == 3) // Kondisinya berarti Status Approve = 1, Status SPP = 2 (confirm)
					{
						$sqlTRS 		= "SELECT TR_APPROVED FROM trans_count WHERE PRJCODE = '$PRJCODE' AND TR_TYPE = 'SPP'";
						$resTRS			= $this->db->query($sqlTRS)->result();
						foreach($resTRS as $rowTRS) :
							$TR_APPROVEDBEF = $rowTRS->TR_APPROVED;		
						endforeach;							
						$TR_APPROVEDNOW		= $TR_APPROVEDBEF + 1;				
						$sqlUpd1		= "UPDATE trans_count SET TR_APPROVED = $TR_APPROVEDNOW WHERE PRJCODE = '$PRJCODE' AND TR_TYPE = 'SPP'";
						$this->db->query($sqlUpd1);
					}
					// JIKA REVISE
					elseif($APPROVE == 4)
					{
						$sqlTRS 		= "SELECT TR_REVISE FROM trans_count WHERE PRJCODE = '$PRJCODE' AND TR_TYPE = 'SPP'";
						$resTRS			= $this->db->query($sqlTRS)->result();
						foreach($resTRS as $rowTRS) :
							$TR_REVISEBEF = $rowTRS->TR_REVISE;		
						endforeach;							
						$TR_REVISENOW		= $TR_REVISEBEF + 1;				
						$sqlUpd1		= "UPDATE trans_count SET TR_REVISE = $TR_REVISENOW WHERE PRJCODE = '$PRJCODE' AND TR_TYPE = 'SPP'";
						$this->db->query($sqlUpd1);
					}
					// JIKA REVISE
					elseif($APPROVE == 5)
					{
						$sqlTRS 		= "SELECT TR_REJECT FROM trans_count WHERE PRJCODE = '$PRJCODE' AND TR_TYPE = 'SPP'";
						$resTRS			= $this->db->query($sqlTRS)->result();
						foreach($resTRS as $rowTRS) :
							$TR_REJECTBEF = $rowTRS->TR_REJECT;		
						endforeach;							
						$TR_REJECTNOW		= $TR_REJECTBEF + 1;				
						$sqlUpd1		= "UPDATE trans_count SET TR_REJECT = $TR_REJECTNOW WHERE PRJCODE = '$PRJCODE' AND TR_TYPE = 'SPP'";
						$this->db->query($sqlUpd1);
					}
				}
		
			$url			= site_url('c_setting/c_mail/get_last_ten_projMatReqInb/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
*/	
	/*function popupallpr()
	{
		$getAppName = $this->Menu_model->getAppName()->result();
		foreach($getAppName as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$data['title'] 			= $appName;
			$data['h2_title'] 		= 'Select Purchase Requisition';
			$data['form_action']	= site_url('c_setting/c_mail_sd/update_process');
			$data['txtRefference'] 	= '';
			$data['resultCount']	= 0;
			$data['pageFrom']		= 'PR';
			
			$data['recordcountAllPR'] = $this->m_mail->count_all_num_rowsAllPR();
			$data['viewAllPR'] 	= $this->m_mail->viewAllPR()->result();
					
			$this->load->view('v_project/v_listproject/setting_selectpr', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
*/	
	/*function popupallpr2()
	{
		$getAppName = $this->Menu_model->getAppName()->result();
		foreach($getAppName as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$data['title'] 			= $appName;
		$data['h2_title'] 		= 'Select Item';
		$data['form_action']	= site_url('c_setting/c_mail_sd/update_process');
		$data['txtRefference'] 	= '';
		$data['resultCount']	= 0;
		$data['pageFrom']		= 'DIR'; //DIR = Direct (non PR)
		
		$data['recordcountAllItem'] = $this->m_mail->count_all_num_rowsAllItem();
		$data['viewAllItem'] 	= $this->m_mail->viewAllItem()->result();
				
		$this->load->view('v_project/v_listproject/setting_selectitem', $data);
	}
*/	
	/*function delete($PR_Number)
	{
		$this->m_mail->delete($this->input->post('chkDetail'));
		$this->session->set_flashdata('message', 'Data successfull deleted');
		
		redirect('c_setting/c_mail_sd/');
	}
*/	
	/*function getVendAddress($vendCode)
	{
		$data['myVendCode']		= "$vendCode";
		$sql = "SELECT Vend_Code, Vend_Name, Vend_Address FROM tvendor
					WHERE Vend_Code = '$vendCode'";
		$result1 = $this->db->query($sql)->result();
		foreach($result1 as $row) :
			$Vend_Name = $row->Vend_Address;
		endforeach;
		echo $Vend_Name;
	}
*/
	
	/*function printdocument($MR_Number)
	{
		$getAppName = $this->Menu_model->getAppName()->result();
		foreach($getAppName as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title'] 		= 'Document Print';
			$data['form_action']	= site_url('c_setting/c_mail_sd/update_process_inbox');
			
			$data['recordcountVend'] 	= $this->m_mail->count_all_num_rowsVend();
			$data['viewvendor'] 	= $this->m_mail->viewvendor()->result();
			$data['recordcountDept'] 	= $this->m_mail->count_all_num_rowsDept();
			$data['viewDepartment'] 	= $this->m_mail->viewDepartment()->result();
			$data['recordcountEmpDept'] 	= $this->m_mail->count_all_num_rowsEmpDept();
			$data['viewEmployeeDept'] 	= $this->m_mail->viewEmployeeDept()->result();
			$data['recordcountProject']	= $this->m_mail->count_all_num_rowsProject();
			$data['viewProject'] 		= $this->m_mail->viewProject()->result();
			
			$getpurreq = $this->m_mail->get_MR_by_number($MR_Number)->row();
			
			$this->session->set_userdata('MR_Number', $getpurreq->MR_Number);
			
			$data['link'] 			= array('link_back' => anchor('c_setting/c_mail_sd/get_last_ten_projMatReqInb/'.$getpurreq->proj_Code,'<input type="button" name="btnCancel" id="btnCancel" value="Cancel" class="btn btn-primary" />', array('style' => 'text-decoration: none;')));
			
			$data['default']['proj_ID'] = $getpurreq->proj_ID;
			$data['default']['proj_Code'] = $getpurreq->proj_Code;
			$data['default']['MR_Number'] = $getpurreq->MR_Number;
			$data['default']['MR_Date'] = $getpurreq->MR_Date;
			$data['default']['req_date'] = $getpurreq->req_date;
			$data['default']['latest_date'] = $getpurreq->latest_date;
			$data['default']['MR_Class'] = $getpurreq->MR_Class;
			$data['default']['MR_Type'] = $getpurreq->MR_Type;
			$data['default']['MR_DepID'] = $getpurreq->MR_DepID;
			$data['default']['MR_EmpID'] = $getpurreq->MR_EmpID;
			$data['default']['Vend_Code'] = $getpurreq->Vend_Code;
			$data['default']['MR_Notes'] = $getpurreq->MR_Notes;
			$data['default']['MR_Status'] = $getpurreq->MR_Status;
			$data['default']['Approval_Status'] = $getpurreq->Approval_Status;
			$data['default']['Patt_Year'] = $getpurreq->Patt_Year;
			$data['default']['Patt_Number'] = $getpurreq->Patt_Number;
			$data['default']['Memo_Revisi'] = $getpurreq->Memo_Revisi;
							
			$this->load->view('v_project/v_material_request/print_matreq', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
*/
}