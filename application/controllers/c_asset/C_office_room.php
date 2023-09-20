<?php
/*  
 * Author		= Hendar Permana 
 * Create Date	= 26 Mei 2017
 * File Name	= c_spp.php
 * Location		= -
*/

/*  
 * Author		= Hendar Permana 
 * Edit Date	= 24 Agustus 2017
 * File Name	= c_office_room.php
 * Location		= -
*/
class c_office_room  extends CI_Controller  
{
 	public function index()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_asset/c_office_room/index1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	public function index1($offset=0)
	{
		$this->load->model('m_asset/m_office_room', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
					
			//$data['secAddURL'] 		= site_url('c_setting/c_office_room_sd/add/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['h2_title'] 		= 'Room List';
			$data['h3_title'] 		= 'Asset';
			$data['main_view'] 		= 'v_asset/v_office_room/v_office_room';
			$data['moffset'] 		= $offset;
			$data['perpage'] 		= 20;
			
			$num_rows 				= $this->m_office_room->count_all_num_rows($DefEmp_ID);
			$data["recordcount"] 	= $num_rows;
	 		$data["MenuCode"] 		= 'MN238';
			$data['viewroom'] = $this->m_office_room->get_last_room($DefEmp_ID)->result();
			
			$this->load->view('v_asset/v_office_room/v_office_room', $data);
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
		$this->load->model('m_asset/m_office_room', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$ROOM_CODE	= $_GET['id'];
			$ROOM_CODE	= $this->url_encryption_helper->decode_url($ROOM_CODE);
						
			$secSelItmURL				= site_url('c_asset/c_office_room/popupallitem/?id='.$this->url_encryption_helper->encode_url($ROOM_CODE));
			$backURL					= site_url('c_asset/c_office_room/index1/?id='.$this->url_encryption_helper->encode_url($ROOM_CODE));
			
			//$data['PRJCODE'] 			= $PRJCODE;	
			$docPatternPosition 		= 'Especially';	
			$data['title'] 				= $appName;
			$data['task'] 				= 'add';
			$data['h2_title']			= 'Add Room';			
			$data['h2_title']			= 'Room';
			$data['main_view'] 			= 'v_asset/v_office_room/v_office_room_form';
			$data['form_action']		= site_url('c_asset/c_office_room/add_process');
			//$data['link'] 				= array('link_back' => anchor("$backURL",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 			= $backURL;
			
			$data['recordcountProject']	= $this->m_office_room->count_all_num_rowsProject();
			$data['viewProject'] 		= $this->m_office_room->viewProject()->result();
			
			$MenuCode 					= 'MN238';
			$data["MenuCode"] 			= 'MN238';
			$data['viewDocPattern'] 	= $this->m_office_room->getDataDocPat($MenuCode)->result();
			
			$this->load->view('v_asset/v_office_room/v_office_room_form', $data);
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
			$this->load->model('m_asset/m_office_room', '', TRUE);
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			
			$data['title'] 				= $appName;
			$data['h2_title'] 			= 'List Employee';
			$data['main_view'] 			= 'v_asset/v_office_room/v_office_room_form';
			$data['form_action']		= site_url('c_asset/c_office_room/update_process');
			$data['PRJCODE'] 			= $PRJCODE;
			$data['secShowAll']			= site_url('c_asset/c_office_room/popupallitem/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			$data['recordcountAllItem'] = $this->m_office_room->count_all_num_rowsAllItem();
			$data['viewAllItem'] 		= $this->m_office_room->viewAllItemMatBudget()->result();
					
			$this->load->view('v_asset/v_office_room/v_office_room_selectitem', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function add_process()
	{
		$this->load->model('m_asset/m_office_room', '', TRUE);
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
			
			$ROOM_CODE 					= $this->input->post('ROOM_CODE');
			$ROOM_NAME 					= $this->input->post('ROOM_NAME');
			$ROOM_USER 					= $this->input->post('ROOM_USER');
			$ROOM_PERIODE 				= date('Y-m-d',strtotime($this->input->post('ROOM_PERIODE')));
			$ROOM_CREATER 				= $this->input->post('ROOM_CREATER');
			$ROOM_CREATED 				= date('Y-m-d',strtotime($this->input->post('ROOM_CREATED')));
			
			//echo "$MG_CODE - $MG_NAME";
			//return false;
			$projMatReqH = array('ROOM_CODE' 	=> $this->input->post('ROOM_CODE'),
							'ROOM_NAME' 	=> $this->input->post('ROOM_NAME'),
							'ROOM_USER' 	=> $this->input->post('ROOM_USER'),
							'ROOM_PERIODE' 	=> date('Y-m-d',strtotime($this->input->post('ROOM_PERIODE'))),
							'ROOM_CREATER' 	=> $this->input->post('ROOM_CREATER'),
							'ROOM_CREATED' 	=> date('Y-m-d',strtotime($this->input->post('ROOM_CREATED'))));
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
							
			$this->m_office_room->add($projMatReqH);
			
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
				 $this->db->insert('tbl_office_room_detail',$d);
				//$this->m_mail->add($projMatReqH);
			}
			$url			= site_url('c_asset/c_office_room/Index1/?id='.$this->url_encryption_helper->encode_url($ROOM_CODE));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update()
	{
		$this->load->model('m_asset/m_office_room', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$ROOM_CODE		= $_GET['id'];
		$ROOM_CODE		= $this->url_encryption_helper->decode_url($ROOM_CODE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 					= $appName;
			$data['task'] 					= 'edit';
			$data['h2_title'] 				= 'Update';
			$data['main_view'] 				= 'v_asset/v_office_room/v_office_room_form';
			$data['form_action']			= site_url('c_asset/c_office_room/update_process');
			
			$data['recordcountProject']		= $this->m_office_room->count_all_num_rowsProject();
			$data['viewProject'] 			= $this->m_office_room->viewProject()->result();
			$data["MenuCode"] 				= 'MN238';
			$getpurreq 						= $this->m_office_room->get_room_by_number($ROOM_CODE)->row();
			
			$data['default']['ROOM_CODE'] 		= $getpurreq->ROOM_CODE;
			$data['default']['ROOM_NAME']		= $getpurreq->ROOM_NAME;
			$data['default']['ROOM_USER']		= $getpurreq->ROOM_USER;
			$data['default']['ROOM_PERIODE']	= $getpurreq->ROOM_PERIODE;
			$data['default']['ROOM_CREATER']	= $getpurreq->ROOM_CREATER;
			$data['default']['ROOM_CREATED']	= $getpurreq->ROOM_CREATED;
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
			
			$cancelURL					= site_url('c_asset/c_office_room/index1/?id='.$this->url_encryption_helper->encode_url($ROOM_CODE));
			//$data['link'] 				= array('link_back' => anchor("$cancelURL",'<input type="button" name="btnCancel" id="btnCancel" value="Cancel" class="btn btn-primary" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 			= $cancelURL;
			$data['secSelItmURL']	= site_url('c_asset/c_office_room/popupallitem/?id='.$this->url_encryption_helper->encode_url($ROOM_CODE));
			$data['secShowAll']		= site_url('c_asset/c_office_room/popupallitem/?id='.$this->url_encryption_helper->encode_url($ROOM_CODE));
			
			$this->load->view('v_asset/v_office_room/v_office_room_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update_process()
	{
		$this->load->model('m_asset/m_office_room', '', TRUE);
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
			$ROOM_CODE					= $this->input->post('ROOM_CODE');
			$ROOM_NAME					= $this->input->post('ROOM_NAME');
			$ROOM_USER					= $this->input->post('ROOM_USER');
			$ROOM_PERIODE				= date('Y-m-d',strtotime($this->input->post('ROOM_PERIODE')));
			$ROOM_CREATER				= $this->input->post('ROOM_CREATER');
			$ROOM_CREATED				= date('Y-m-d',strtotime($this->input->post('ROOM_CREATED')));
			
			$projMatReqH = array('ROOM_CODE' 	=> $this->input->post('ROOM_CODE'),
							'ROOM_NAME' 	=> $this->input->post('ROOM_NAME'),
							'ROOM_USER' 	=> $this->input->post('ROOM_USER'),
							'ROOM_PERIODE' 	=> date('Y-m-d',strtotime($this->input->post('ROOM_PERIODE'))),
							'ROOM_CREATER' 	=> $this->input->post('ROOM_CREATER'),
							'ROOM_CREATED' 	=> date('Y-m-d',strtotime($this->input->post('ROOM_CREATED'))));
																	
			$this->m_office_room->update($ROOM_CODE, $projMatReqH);
			$this->m_office_room->deleteDetail($ROOM_CODE);
			
			foreach($_POST['data'] as $d)
			{
				$this->db->insert('tbl_office_room_detail',$d);
			}
				
			$url			= site_url('c_asset/c_office_room/index1/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
}