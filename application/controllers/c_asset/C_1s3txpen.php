<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 26 Juni 2018
 * File Name	= C_1s3txpen.php
 * Location		= -
*/
class C_1s3txpen extends CI_Controller  
{
 	public function index() // G
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_asset/c_1s3txpen/projectlist/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function projectlist() // G
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
				$data["h1_title"] 	= "Pembebanan Aset";
			}
			else
			{
				$data["h1_title"] 	= "Asset Expense";
			}
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN017';	 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_asset/c_1s3txpen/gall1s3txpen/?id=";
			
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
	
	function gall1s3txpen() // G
	{
		$this->load->model('m_asset/m_asset_expense', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			date_default_timezone_set("Asia/Jakarta");
			
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			
			$data['title'] 		= $appName;
			$data['addURL'] 	= site_url('c_asset/c_1s3txpen/add/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['backURL'] 	= site_url('c_asset/c_1s3txpen/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
			
			$num_rows 			= $this->m_asset_expense->count_all_asexp($PRJCODE);
			$data["cASEXP"] 	= $num_rows;	 
			$data['vwASEXP'] 	= $this->m_asset_expense->get_all_asexp($PRJCODE)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN345';
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
			
			$this->load->view('v_asset/v_asset_usage/asset_exp', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function add() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_asset/m_asset_expense', '', TRUE);
		
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
			
			$data['PRJCODE'] 	= $PRJCODE;	
			$docPatternPosition = 'Especially';	
			$data['title'] 		= $appName;
			$data['task'] 		= 'add';
			$data['form_action']= site_url('c_asset/c_1s3txpen/add_process');
			$data['backURL'] 	= site_url('c_asset/c_1s3txpen/get_all_asexp/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
			
			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			
			$MenuCode 			= 'MN345';
			$data["MenuCode"] 	= 'MN345';
			$data['vwDocPatt'] 	= $this->m_asset_expense->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN345';
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
			
			$this->load->view('v_asset/v_asset_usage/asset_exp_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function popupallast() // G
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_asset/m_asset_expense', '', TRUE);
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			$COLLID		= $_GET['id'];
			$COLLID		= $this->url_encryption_helper->decode_url($COLLID);
			$plitWord	= explode('~', $COLLID);
			$PRJCODE	= $plitWord[0];
			$JOBCODE	= $plitWord[1];
			
			$data['title'] 			= $appName;
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h2_title"] 	= "Daftar Asset";
			}
			else
			{
				$data["h2_title"] 	= "Asset List";
			}
			
			$data['form_action']	= site_url('c_asset/c_1s3txpen/update_process');
			$data['PRJCODE'] 		= $PRJCODE;
			$data['secShowAll']		= site_url('c_asset/c_1s3txpen/popupallast/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			$data['cAllAsset']		= $this->m_asset_expense->count_all_asset($PRJCODE, $JOBCODE);
			$data['vwAllAsset'] 	= $this->m_asset_expense->get_all_asset($PRJCODE, $JOBCODE)->result();
			
			//$data['cAllItem']		= $this->m_asset_expense->count_all_asset($PRJCODE, $JOBCODE);
			//$data['vwAllItem'] 		= $this->m_asset_expense->get_all_asset($PRJCODE, $JOBCODE)->result();
					
			$this->load->view('v_asset/v_asset_usage/asset_exp_selaset', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function popupallitem() // G
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_asset/m_asset_expense', '', TRUE);
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			$COLLID		= $_GET['id'];
			$COLLID		= $this->url_encryption_helper->decode_url($COLLID);
			$plitWord	= explode('~', $COLLID);
			$PRJCODE	= $plitWord[0];
			$JOBCODE	= $plitWord[1];
			$THEROW		= $_GET['theRow'];
			
			$data['title'] 			= $appName;
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h2_title"] 	= "Daftar Item";
			}
			else
			{
				$data["h2_title"] 	= "Item List";
			}
			
			$data['form_action']	= site_url('c_asset/c_1s3txpen/update_process');
			$data['PRJCODE'] 		= $PRJCODE;
			$data['THEROW'] 		= $THEROW;
			$data['secShowAll']		= site_url('c_asset/c_1s3txpen/popupallast/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			//$data['cAllAsset']		= $this->m_asset_expense->count_all_asset($PRJCODE, $JOBCODE);
			//$data['vwAllAsset'] 	= $this->m_asset_expense->get_all_asset($PRJCODE, $JOBCODE)->result();
			
			$data['cAllItem']		= $this->m_asset_expense->count_all_item($PRJCODE, $JOBCODE);
			$data['vwAllItem'] 		= $this->m_asset_expense->get_all_item($PRJCODE, $JOBCODE)->result();
					
			$this->load->view('v_asset/v_asset_usage/asset_exp_selitem', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function genCode() // G - HOLD
	{
		$PRJCODEX	= $this->input->post('PRJCODEX');
		$PattCode	= $this->input->post('Pattern_Code');
		$PattLength	= $this->input->post('Pattern_Length');
		$useYear	= $this->input->post('useYear');
		$useMonth	= $this->input->post('useMonth');
		$useDate	= $this->input->post('useDate');
		
		$ASEXPDate	= date('Y-m-d',strtotime($this->input->post('ASEXPDate')));
		$year		= date('Y',strtotime($this->input->post('ASEXPDate')));
		$month 		= (int)date('m',strtotime($this->input->post('ASEXPDate')));
		$date 		= (int)date('d',strtotime($this->input->post('ASEXPDate')));
		
		$this->db->where('Patt_Year', $year);
		$myCount = $this->db->count_all('tbl_asset_exph');
		
		$sql	 = "SELECT MAX(Patt_Number) as maxNumber FROM tbl_asset_exph
					WHERE Patt_Year = $year AND PRJCODE = '$PRJCODEX'";
		$result = $this->db->query($sql)->result();
		if($myCount>0)
		{
			$myMax	= 0;
			foreach($result as $row) :
				$myMax = $row->maxNumber;
				$myMax = $myMax+1;
			endforeach;
		}	
		else
		{
			$myMax = 1;
		}
		
		$thisMonth = $month;
	
		$lenMonth = strlen($thisMonth);
		if($lenMonth==1) $nolMonth="0";elseif($lenMonth==2) $nolMonth="";
		$pattMonth = $nolMonth.$thisMonth;
		
		$thisDate = $date;
		$lenDate = strlen($thisDate);
		if($lenDate==1) $nolDate="0";elseif($lenDate==2) $nolDate="";
		$pattDate = $nolDate.$thisDate;
		
		// group year, month and date
		$year = substr($year,2,2);
		if(($useYear == 1) && ($useMonth == 1) && ($useDate == 1))
			$groupPattern = "$year$pattMonth$pattDate";
		elseif(($useYear == 1) && ($useMonth == 1) && ($useDate == 0))
			$groupPattern = "$year$pattMonth";
		elseif(($useYear == 1) && ($useMonth == 0) && ($useDate == 1))
			$groupPattern = "$year$pattDate";
		elseif(($useYear == 0) && ($useMonth == 1) && ($useDate == 1))
			$groupPattern = "$pattMonth$pattDate";
		elseif(($useYear == 1) && ($useMonth == 0) && ($useDate == 0))
			$groupPattern = "$year";
		elseif(($useYear == 0) && ($useMonth == 1) && ($useDate == 0))
			$groupPattern = "$pattMonth";
		elseif(($useYear == 0) && ($useMonth == 0) && ($useDate == 1))
			$groupPattern = "$pattDate";
		elseif(($useYear == 0) && ($useMonth == 0) && ($useDate == 0))
			$groupPattern = "";
			
		$lastPatternNumb = $myMax;
		$lastPatternNumb1 = $myMax;
		$len = strlen($lastPatternNumb);
		
		if($PattLength==2)
		{
			if($len==1) $nol="0";
		}
		elseif($PattLength==3)
		{if($len==1) $nol="00";else if($len==2) $nol="0";
		}
		elseif($PattLength==4)
		{if($len==1) $nol="000";else if($len==2) $nol="00";else if($len==3) $nol="0";
		}
		elseif($PattLength==5)
		{if($len==1) $nol="0000";else if($len==2) $nol="000";else if($len==3) $nol="00";else if($len==4) $nol="0";
		}
		elseif($PattLength==6)
		{if($len==1) $nol="00000";else if($len==2) $nol="0000";else if($len==3) $nol="000";else if($len==4) $nol="00";else if($len==5) $nol="0";
		}
		elseif($PattLength==7)
		{if($len==1) $nol="000000";else if($len==2) $nol="00000";else if($len==3) $nol="0000";else if($len==4) $nol="000";else if($len==5) $nol="00";else if($len==6) $nol="0";
		}
		$lastPatternNumb	= $nol.$lastPatternNumb;
		$DocNumber 			= "$PattCode$PRJCODEX$groupPattern-$lastPatternNumb";
		echo "$DocNumber~$lastPatternNumb";
	}
	
	function add_process() // G
	{
		$this->load->model('m_asset/m_asset_expense', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			date_default_timezone_set("Asia/Jakarta");
			
			$ASEXP_NUM 		= $this->input->post('ASEXP_NUM');
			$ASEXP_CODE 	= $this->input->post('ASEXP_CODE');
			$PRJCODE 		= $this->input->post('PRJCODE');
			$JOBCODEID 		= $this->input->post('ASEXP_REFNO');
			$ASEXP_NOTE		= $this->input->post('ASEXP_NOTE');
			$ASEXP_NOTE2	= $this->input->post('ASEXP_NOTE2');
			$ASEXP_REVMEMO	= $this->input->post('ASEXP_REVMEMO');
			$ASEXP_STAT 	= $this->input->post('ASEXP_STAT');
			
			$ASEXP_CREATER	= $DefEmp_ID;
			$ASEXP_CREATED 	= date('Y-m-d H:i:s');
			$ASEXP_AMOUNT	= $this->input->post('ASEXP_AMOUNT');
			
			//setting ASEXP Date
			$ASEXP_DATE		= date('Y-m-d',strtotime($this->input->post('ASEXP_DATE')));
			$Patt_Year		= date('Y',strtotime($this->input->post('ASEXP_DATE')));
			$Patt_Month		= date('m',strtotime($this->input->post('ASEXP_DATE')));
			$Patt_Date		= date('d',strtotime($this->input->post('ASEXP_DATE')));
			
			$insASEXPH		= array('ASEXP_NUM' 	=> $ASEXP_NUM,
									'ASEXP_CODE'	=> $ASEXP_CODE,
									'ASEXP_DATE'	=> $ASEXP_DATE,
									'PRJCODE'		=> $PRJCODE,
									'JOBCODEID'		=> $JOBCODEID,
									'ASEXP_NOTE'	=> $ASEXP_NOTE,
									'ASEXP_NOTE2'	=> $ASEXP_NOTE2,
									'ASEXP_REVMEMO'	=> $ASEXP_REVMEMO,
									'ASEXP_STAT'	=> $ASEXP_STAT,
									'ASEXP_CREATER'	=> $ASEXP_CREATER,
									'ASEXP_CREATED'	=> $ASEXP_CREATED,
									'ASEXP_AMOUNT'	=> $ASEXP_AMOUNT,
									'Patt_Year'		=> $Patt_Year,
									'Patt_Month'	=> $Patt_Month,
									'Patt_Date'		=> $Patt_Date,
									'Patt_Number'	=> $this->input->post('Patt_Number'));
			$this->m_asset_expense->add($insASEXPH);
			
			foreach($_POST['data'] as $d)
			{
				$this->db->insert('tbl_asset_expd',$d);
			}
			
			// UPDATE DETAIL
				$this->m_asset_expense->updateDet($ASEXP_NUM, $PRJCODE, $ASEXP_DATE);
				
			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('ASEXP_STAT');			// IF "ADD" CONDITION ALWAYS = PR_STAT
				$parameters 	= array('DOC_CODE' 		=> $ASEXP_NUM,		// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "ASEXP",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_asset_exph",// TABLE NAME
										'KEY_NAME'		=> "ASEXP_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "ASEXP_STAT",	// NAMA FIELD STATUS
										'STATDOC' 		=> $ASEXP_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_REQ",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_REQ_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_REQ_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_REQ_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_REQ_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_REQ_RJ",	// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_REQ_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "ASEXP_NUM",
										'DOC_CODE' 		=> $ASEXP_NUM,
										'DOC_STAT' 		=> $ASEXP_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_asset_exph");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $ASEXP_NUM;
				$MenuCode 		= 'MN345';
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
			
			$url			= site_url('c_asset/c_1s3txpen/gall1s3txpen/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_asset/m_asset_expense', '', TRUE);
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$ASEXP_NUM		= $_GET['id'];
		$ASEXP_NUM		= $this->url_encryption_helper->decode_url($ASEXP_NUM);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$docPatternPosition = 'Especially';	
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_asset/c_1s3txpen/update_process');
			
			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			
			$getASEXP 			= $this->m_asset_expense->get_asexp_by_number($ASEXP_NUM)->row();									
									
			$data['default']['ASEXP_NUM'] 		= $getASEXP->ASEXP_NUM;
			$data['default']['ASEXP_CODE'] 		= $getASEXP->ASEXP_CODE;
			$data['default']['ASEXP_DATE'] 		= $getASEXP->ASEXP_DATE;
			$data['default']['PRJCODE'] 		= $getASEXP->PRJCODE;
			$data['PRJCODE']					= $getASEXP->PRJCODE;
			$PRJCODE 							= $getASEXP->PRJCODE;
			$data['default']['JOBCODEID'] 		= $getASEXP->JOBCODEID;
			$data['default']['ASEXP_NOTE'] 		= $getASEXP->ASEXP_NOTE;
			$data['default']['ASEXP_NOTE2'] 	= $getASEXP->ASEXP_NOTE2;
			$data['default']['ASEXP_REVMEMO'] 	= $getASEXP->ASEXP_REVMEMO;
			$data['default']['ASEXP_STAT'] 		= $getASEXP->ASEXP_STAT;
			$data['default']['ASEXP_AMOUNT'] 	= $getASEXP->ASEXP_AMOUNT;
			$data['default']['Patt_Year'] 		= $getASEXP->Patt_Year;
			$data['default']['Patt_Month'] 		= $getASEXP->Patt_Month;
			$data['default']['Patt_Date'] 		= $getASEXP->Patt_Date;
			$data['default']['Patt_Number']		= $getASEXP->Patt_Number;
			
			$MenuCode 			= 'MN345';
			$data["MenuCode"] 	= 'MN345';
			$data['vwDocPatt'] 	= $this->m_asset_expense->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getASEXP->ASEXP_NUM;
				$MenuCode 		= 'MN345';
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
			
			$this->load->view('v_asset/v_asset_usage/asset_exp_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process() // G
	{
		$this->load->model('m_asset/m_asset_expense', '', TRUE);
		$this->load->model('m_updash/m_updash', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName 	= $therow->app_name;		
		endforeach;
		
		$comp_init 		= $this->session->userdata('comp_init');
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			date_default_timezone_set("Asia/Jakarta");
			
			$ASEXP_NUM 		= $this->input->post('ASEXP_NUM');
			$ASEXP_CODE 	= $this->input->post('ASEXP_CODE');
			$PRJCODE 		= $this->input->post('PRJCODE');
			$ASEXP_DATE		= date('Y-m-d',strtotime($this->input->post('ASEXP_DATE')));
			$JOBCODEID 		= $this->input->post('ASEXP_REFNO');
			$ASEXP_NOTE		= $this->input->post('ASEXP_NOTE');
			$ASEXP_NOTE2	= $this->input->post('ASEXP_NOTE2');
			$ASEXP_REVMEMO	= $this->input->post('ASEXP_REVMEMO');
			$ASEXP_STAT 	= $this->input->post('ASEXP_STAT');
			
			$ASEXP_CREATER	= $DefEmp_ID;
			$ASEXP_CREATED 	= date('Y-m-d H:i:s');
			$ASEXP_AMOUNT	= $this->input->post('ASEXP_AMOUNT');
			
			//setting ASEXP Date
			$ASEXP_DATE		= date('Y-m-d',strtotime($this->input->post('ASEXP_DATE')));
			$Patt_Year		= date('Y',strtotime($this->input->post('ASEXP_DATE')));
			$Patt_Month		= date('m',strtotime($this->input->post('ASEXP_DATE')));
			$Patt_Date		= date('d',strtotime($this->input->post('ASEXP_DATE')));

			$updASEXPH		= array('ASEXP_NUM' 	=> $ASEXP_NUM,
									'ASEXP_CODE'	=> $ASEXP_CODE,
									'ASEXP_DATE'	=> $ASEXP_DATE,
									'PRJCODE'		=> $PRJCODE,
									'JOBCODEID'		=> $JOBCODEID,
									'ASEXP_NOTE'	=> $ASEXP_NOTE,
									'ASEXP_NOTE2'	=> $ASEXP_NOTE2,
									'ASEXP_REVMEMO'	=> $ASEXP_REVMEMO,
									//'ASEXP_CREATER'	=> $ASEXP_CREATER,
									//'ASEXP_CREATED'	=> $ASEXP_CREATED,
									'ASEXP_AMOUNT'	=> $ASEXP_AMOUNT,
									'ASEXP_STAT'	=> $ASEXP_STAT,
									'Patt_Year'		=> $Patt_Year,
									'Patt_Month'	=> $Patt_Month,
									'Patt_Date'		=> $Patt_Date);										
			$this->m_asset_expense->update($ASEXP_NUM, $updASEXPH);

			$this->load->model('m_updash/m_updash', '', TRUE);
			
			$AH_CODE		= $ASEXP_NUM;
			$AH_APPLEV		= $this->input->post('APP_LEVEL');
			$AH_APPROVER	= $DefEmp_ID;
			$AH_APPROVED	= date('Y-m-d H:i:s');
			$AH_NOTES		= $this->input->post('ASEXP_NOTE');
			$AH_ISLAST		= $this->input->post('IS_LAST');

			if($ASEXP_STAT == 9)
			{
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "ASEXP_NUM",
											'DOC_CODE' 		=> $ASEXP_NUM,
											'DOC_STAT' 		=> $ASEXP_STAT,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> $completeName,
											'TBLNAME'		=> "tbl_asset_exph");
					$this->m_updash->updateStatus($paramStat);

				// 1. UPDATE STATUS
					$updASEXPH		= array('ASEXP_STAT'	=> $ASEXP_STAT);
					$this->m_asset_expense->update($ASEXP_NUM, $updASEXPH);
			
					$paramSTAT 	= array('JournalHCode' 	=> $ASEXP_NUM);
					$this->m_updash->updSTATJD($paramSTAT);

					$upJH	= "UPDATE tbl_journalheader SET GEJ_STAT = 9, STATCOL = 'danger', STATDESC = 'Void' WHERE JournalH_Code = '$ASEXP_NUM'";
					$this->db->query($upJH);

					$upJD	= "UPDATE tbl_journaldetail SET GEJ_STAT = 9, isVoid = 1 WHERE JournalH_Code = '$ASEXP_NUM'";
					$this->db->query($upJD);

				// 2. MEMBUAT JURNAL PEMBALIK
					$sqlDET 	= "SELECT JournalH_Date, Acc_Id, proj_Code, JOBCODEID, ITM_CODE, ITM_GROUP, ITM_VOLM, ITM_PRICE, Base_Debet, Base_Kredit, Journal_DK
									FROM tbl_journaldetail WHERE JournalH_Code = '$ASEXP_NUM'";
					$resDET 	= $this->db->query($sqlDET)->result();
					foreach($resDET as $rowDET) :
						$JournalH_Date 	= $rowDET->JournalH_Date;
						$ACC_NUM 		= $rowDET->Acc_Id;
						$PRJCODE 		= $rowDET->proj_Code;
						$JOBCODEID 		= $rowDET->JOBCODEID;
						$ITM_CODE 		= $rowDET->ITM_CODE;
						$ITM_GROUP 		= $rowDET->ITM_GROUP;
						$ITM_VOLM 		= $rowDET->ITM_VOLM;
						$ITM_PRICE 		= $rowDET->ITM_PRICE;
						$Base_Debet 	= $rowDET->Base_Debet;
						$Base_Kredit 	= $rowDET->Base_Kredit;
						$Journal_DK 	= $rowDET->Journal_DK;

						$ITM_TYPE 	= $this->m_updash->get_itmType($PRJCODE, $ITM_CODE);
						if($ITM_TYPE == 0)
							$ITM_TYPE	= 1;

						$PRJCODE		= $PRJCODE;
						$JOURN_DATE		= $JournalH_Date;
						$ITM_GROUP		= $ITM_GROUP;
						$ITM_TYPE		= $ITM_TYPE;
						$ITM_QTY 		= $ITM_VOLM;
						if($ITM_QTY == 0 || $ITM_QTY == '')
							$ITM_QTY	= 1;

						if($Journal_DK == 'D')
						{
							$JOURN_VAL	= $Base_Debet;

							$parameters = array('PRJCODE' 		=> $PRJCODE,
												'JOURN_DATE' 	=> $JOURN_DATE,
												'ITM_CODE' 		=> $ITM_CODE,
												'ITM_GROUP' 	=> $ITM_GROUP,
												'ITM_TYPE' 		=> $ITM_TYPE,
												'ITM_QTY' 		=> $ITM_QTY,
												'JOURN_VAL' 	=> $JOURN_VAL);
							$this->m_journal->updateLR_VUM($parameters);
						}
						else
						{
							$JOURN_VAL	= $Base_Kredit;
						}

						$isHO			= 0;
						$syncPRJ		= '';
						$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
											WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
						$resISHO		= $this->db->query($sqlISHO)->result();
						foreach($resISHO as $rowISHO):
							$isHO		= $rowISHO->isHO;
							$syncPRJ	= $rowISHO->syncPRJ;
						endforeach;
						$dataPecah 	= explode("~",$syncPRJ);
						$jmD 		= count($dataPecah);
					
						if($jmD > 0)
						{
							$SYNC_PRJ	= '';
							for($i=0; $i < $jmD; $i++)
							{
								$SYNC_PRJ	= $dataPecah[$i];
								if($Journal_DK == 'D')
								{
									$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet-$Base_Debet,
														Base_Debet2 = Base_Debet2-$Base_Debet
													WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
								}
								else
								{
									$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit-$Base_Kredit,
														Base_Kredit2 = Base_Kredit2-$Base_Kredit
													WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
								}
								$this->db->query($sqlUpdCOA);
							}
						}
					endforeach;
			}
			else
			{
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "ASEXP_NUM",
											'DOC_CODE' 		=> $ASEXP_NUM,
											'DOC_STAT' 		=> $ASEXP_STAT,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> $completeName,
											'TBLNAME'		=> "tbl_asset_exph");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS

				// START : SAVE APPROVE HISTORY					
					if($ASEXP_STAT == 3)
					{
						// START : SAVE APPROVE HISTORY
							$this->load->model('m_updash/m_updash', '', TRUE);
							
							$insAppHist 	= array('AH_CODE'		=> $AH_CODE,
													'AH_APPLEV'		=> $AH_APPLEV,
													'AH_APPROVER'	=> $AH_APPROVER,
													'AH_APPROVED'	=> $AH_APPROVED,
													'AH_NOTES'		=> $AH_NOTES,
													'AH_ISLAST'		=> $AH_ISLAST);										
							$this->m_updash->insAppHist($insAppHist);
						// END : SAVE APPROVE HISTORY

						$updASEXPH		= array('ASEXP_STAT'	=> 7);
						$this->m_asset_expense->update($ASEXP_NUM, $updASEXPH);

						// START : UPDATE STATUS
							$completeName 	= $this->session->userdata['completeName'];
							$paramStat 		= array('PM_KEY' 		=> "ASEXP_NUM",
													'DOC_CODE' 		=> $ASEXP_NUM,
													'DOC_STAT' 		=> 7,
													'PRJCODE' 		=> $PRJCODE,
													'CREATERNM'		=> $completeName,
													'TBLNAME'		=> "tbl_asset_exph");
							$this->m_updash->updateStatus($paramStat);
						// END : UPDATE STATUS
					}
				// END : SAVE APPROVE HISTORY
			
				$this->m_asset_expense->deleteDetail($ASEXP_NUM);
			
				// START : SETTING L/R
					$this->load->model('m_updash/m_updash', '', TRUE);
					$PERIODED	= $ASEXP_DATE;
					$PERIODM	= date('m', strtotime($PERIODED));
					$PERIODY	= date('Y', strtotime($PERIODED));
					$getLR		= "tbl_profitloss WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
									AND YEAR(PERIODE) = '$PERIODY'";
					$resLR		= $this->db->count_all($getLR);
					if($resLR	== 0)
					{
						$this->m_updash->createLR($PRJCODE, $PERIODED);
					}
				// END : SETTING L/R
				
				foreach($_POST['data'] as $d)
				{
					$ASEXP_AMOUNT	= $d['ASEXP_AMOUNT'];
					$this->db->insert('tbl_asset_expd',$d);
					
					// UPDATE DETAIL
					$this->m_asset_expense->updateDet($ASEXP_NUM, $PRJCODE, $ASEXP_DATE);
				}
				
				if($ASEXP_STAT == 3 && $AH_ISLAST == 1)
				{
					$updASEXPH		= array('ASEXP_STAT'	=> $ASEXP_STAT);										
					$this->m_asset_expense->update($ASEXP_NUM, $updASEXPH);
							
					// START : JOURNAL HEADER
						$this->load->model('m_journal/m_journal', '', TRUE);
						
						$JournalH_Code	= $ASEXP_NUM;
						$JournalType	= 'ASEXP';
						$JournalH_Date	= $ASEXP_DATE;
						$Company_ID		= $comp_init;
						$DOCSource		= $ASEXP_NUM;
						$LastUpdate		= $ASEXP_CREATED;
						$WH_CODE		= $PRJCODE;
						$Refer_Number	= '';
						$RefType		= 'ASEXP';
						$PRJCODE		= $PRJCODE;
						
						$parameters = array('JournalH_Code' 	=> $JournalH_Code,
											'JournalType'		=> $JournalType,
											'JournalH_Desc'		=> $ASEXP_NOTE2,
											'JournalH_Date' 	=> $JournalH_Date,
											'Company_ID' 		=> $Company_ID,
											'Source'			=> $DOCSource,
											'Emp_ID'			=> $DefEmp_ID,
											'LastUpdate'		=> $LastUpdate,	
											'KursAmount_tobase'	=> 1,
											'WHCODE'			=> $WH_CODE,
											'Reference_Number'	=> $Refer_Number,
											'Manual_No'			=> $ASEXP_CODE,
											'RefType'			=> $RefType,
											'PRJCODE'			=> $PRJCODE);
						$this->m_journal->createJournalH($JournalH_Code, $parameters); // OK
					// END : JOURNAL HEADER
					
					// START : JOURNAL DETAIL
						foreach($_POST['data'] as $d)
						{
							$this->load->model('m_journal/m_journal', '', TRUE);
							
							$ITM_CODE 		= $d['ITM_CODE'];
							$JOBCODEID 		= $JOBCODEID;
							$ACC_ID 		= '';			// 
							$ITM_UNIT 		= 'UNIT';
							$ITM_GROUP 		= $d['ITM_GROUP'];		//
							$ITM_TYPE 		= $d['ITM_TYPE'];		//
							$ITM_QTY 		= 1;					// By Amount
							$ITM_PRICE 		= $d['ASEXP_AMOUNT'];	//
							$ITM_DISC 		= 0;
							$TAXCODE1 		= '';
							$TAXPRICE1 		= 0;
							
							$JournalH_Code	= $ASEXP_NUM;
							$JournalType	= 'UM-ASEXP';
							$JournalH_Date	= $ASEXP_DATE;
							$Company_ID		= $comp_init;
							$Currency_ID	= 'IDR';
							$LastUpdate		= $AH_APPROVED;
							$WH_CODE		= $PRJCODE;
							$Refer_Number	= '';
							$RefType		= 'UM-ASEXP';
							$JSource		= 'UM-ASEXP';
							$PRJCODE		= $PRJCODE;
								
							$parameters = array('JournalH_Code' 	=> $JournalH_Code,
												'JournalType'		=> $JournalType,
												'JournalH_Date' 	=> $JournalH_Date,
												'Company_ID' 		=> $Company_ID,
												'Currency_ID' 		=> $Currency_ID,
												'Source'			=> $DOCSource,
												'Emp_ID'			=> $DefEmp_ID,
												'LastUpdate'		=> $LastUpdate,	
												'KursAmount_tobase'	=> 1,
												'WHCODE'			=> $WH_CODE,
												'Reference_Number'	=> $Refer_Number,
												'RefType'			=> $RefType,
												'PRJCODE'			=> $PRJCODE,
												'JSource'			=> $JSource,
												'TRANS_CATEG' 		=> 'UM-ASEXP',		// UM = Use Material - Asset Expense
												'ITM_CODE' 			=> $ITM_CODE,
												'ACC_ID' 			=> $ACC_ID,			// 
												'ITM_UNIT' 			=> $ITM_UNIT,		
												'ITM_GROUP' 		=> $ITM_GROUP,		// 
												'ITM_TYPE' 			=> $ITM_TYPE,		// 
												'ITM_QTY' 			=> $ITM_QTY,		// 
												'ITM_PRICE' 		=> $ITM_PRICE,		//
												'ITM_DISC' 			=> $ITM_DISC,
												'TAXCODE1' 			=> $TAXCODE1,
												'TAXPRICE1' 		=> $TAXPRICE1);
							$this->m_journal->createJournalD($JournalH_Code, $parameters);

							// START : UPDATE PROFIT AND LOSS
								$this->load->model('m_updash/m_updash', '', TRUE);

								$ITM_TYPE 	= $this->m_updash->getItmType($PRJCODE, $ITM_CODE);
								$PERIODED	= $JournalH_Date;
								$FIELDNME	= "";
								$FIELDVOL	= $ITM_QTY;
								$FIELDPRC	= $ITM_PRICE;
								$ADDTYPE	= "MIN";		// PENGURANGAN KARENA SEBAGAI BAHAN MASUKAN

								$parameters1 = array('PERIODED' 	=> $PERIODED,
													'FIELDNME'		=> $FIELDNME,
													'FIELDVOL' 		=> $FIELDVOL,
													'FIELDPRC' 		=> $FIELDPRC,
													'ADDTYPE' 		=> $ADDTYPE,
													'ITM_CODE'		=> $ITM_CODE,
													'ITM_TYPE'		=> $ITM_TYPE);
								$this->m_updash->updateLR_NForm($PRJCODE, $parameters1);
							// END : UPDATE PROFIT AND LOSS
						}
					// END : JOURNAL DETAIL

					// START : UPDATE STATUS
						$completeName 	= $this->session->userdata['completeName'];
						$paramStat 		= array('PM_KEY' 		=> "ASEXP_NUM",
												'DOC_CODE' 		=> $ASEXP_NUM,
												'DOC_STAT' 		=> $ASEXP_STAT,
												'PRJCODE' 		=> $PRJCODE,
												'CREATERNM'		=> $completeName,
												'TBLNAME'		=> "tbl_asset_exph");
						$this->m_updash->updateStatus($paramStat);
					// END : UPDATE STATUS
				}

				if($ASEXP_STAT == 3)
				{
					// START : UPDATE TO T-TRACK
						date_default_timezone_set("Asia/Jakarta");
						$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
						$TTR_PRJCODE	= $PRJCODE;
						$TTR_REFDOC		= $ASEXP_NUM;
						$MenuCode 		= 'MN345';
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
				}
				else
				{
					// START : UPDATE STATUS
						$completeName 	= $this->session->userdata['completeName'];
						$paramStat 		= array('PM_KEY' 		=> "ASEXP_NUM",
												'DOC_CODE' 		=> $ASEXP_NUM,
												'DOC_STAT' 		=> $ASEXP_STAT,
												'PRJCODE' 		=> $PRJCODE,
												'CREATERNM'		=> $completeName,
												'TBLNAME'		=> "tbl_asset_exph");
						$this->m_updash->updateStatus($paramStat);
					// END : UPDATE STATUS
				}
			}

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $ASEXP_NUM;
				$MenuCode 		= 'MN345';
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

			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('ASEXP_STAT');			// IF "ADD" CONDITION ALWAYS = PR_STAT
				$parameters 	= array('DOC_CODE' 		=> $ASEXP_NUM,		// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "ASEXP",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_asset_exph",// TABLE NAME
										'KEY_NAME'		=> "ASEXP_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "ASEXP_STAT",	// NAMA FIELD STATUS
										'STATDOC' 		=> $ASEXP_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_REQ",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_REQ_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_REQ_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_REQ_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_REQ_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_REQ_RJ",	// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_REQ_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT
							
			$url			= site_url('c_asset/c_1s3txpen/gall1s3txpen/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function printdocument()
	{
		$this->load->model('m_asset/m_asset_expense', '', TRUE);
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$PR_NUM		= $_GET['id'];
		$PR_NUM		= $this->url_encryption_helper->decode_url($PR_NUM);
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$data['title'] 	= $appName;
			
			$getpurreq = $this->m_asset_expense->get_MR_by_number($PR_NUM)->row();
			
			$data['default']['PR_NUM'] 		= $getpurreq->PR_NUM;
			$data['default']['PR_STAT'] 	= $getpurreq->PR_STAT;
			$data['default']['PR_CODE'] 	= $getpurreq->PR_CODE;
			$data['default']['PR_DATE'] 	= $getpurreq->PR_DATE;
			$data['default']['PRJCODE'] 	= $getpurreq->PRJCODE;
			$data['default']['SPLCODE']		= $getpurreq->SPLCODE;
			$data['default']['PR_DEPT']		= $getpurreq->PR_DEPT;
			$data['default']['PR_NOTE'] 	= $getpurreq->PR_NOTE;
			$data['default']['PR_NOTE2']	= $getpurreq->PR_NOTE2;
			$data['default']['PR_PLAN_IR'] 	= $getpurreq->PR_PLAN_IR;
			$data['default']['PR_MEMO'] 	= $getpurreq->PR_MEMO;
			$data['default']['PR_CREATER'] 	= $getpurreq->PR_CREATER;
							
			$this->load->view('v_asset/v_asset_usage/print_matreq', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
}