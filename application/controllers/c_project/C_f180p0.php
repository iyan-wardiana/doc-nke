<?php
/* 
 * Author		= Wardiana
 * Create Date	= 28 Agustus 2018
 * File Name	= C_f180p0.php
 * Location		= -
*/

class C_f180p0 extends CI_Controller  
{
  	function __construct() // GOOD
	{
		parent::__construct();
		
		$this->load->model('m_project/m_fpa/m_fpa', '', TRUE);
		$this->load->model('m_updash/m_updash', '', TRUE);
	
		$this->data['UserID'] 		= $this->session->userdata['Emp_ID'];
		$this->data['appName'] 		= $this->session->userdata['appName'];
		$this->data['ISCREATE'] 	= $this->session->userdata['ISCREATE'];
		$this->data['ISAPPROVE'] 	= $this->session->userdata['ISAPPROVE'];
		$this->data['LangID']		= $this->session->userdata['LangID'];
		
		function cut_text2($var, $len = 200, $txt_titik = "-") 
		{
			$var1	= explode("</p>",$var);
			$var	= $var1[0];
			if (strlen ($var) < $len) 
			{ 
				return $var; 
			}
			if (preg_match ("/(.{1,$len})\s/", $var, $match)) 
			{
				return $match [1] . $txt_titik;
			}
			else
			{
				return substr ($var, 0, $len) . $txt_titik;
			}
		}
		
		// DEFAULT PROJECT
			$sqlISHO 		= "SELECT PRJCODE FROM tbl_project WHERE isHO = 1 AND PRJSTAT = 1";
			$resISHO		= $this->db->query($sqlISHO)->result();
			foreach($resISHO as $rowISHO):
				$PRJCODE	= $rowISHO->PRJCODE;
			endforeach;
			$this->data['PRJCODE']		= $PRJCODE;
			$this->data['PRJCODE_HO']	= $PRJCODE;
		
		// GET PROJECT SELECT
			if(isset($_GET['id']))
			{
				$collDATA		= $_GET['id'];
				$EXP_COLLD1		= $this->url_encryption_helper->decode_url($collDATA);
			}
			else
			{
				$EXP_COLLD1		= '';
			}
			
			$EXP_COLLD		= explode('~', $EXP_COLLD1);	
			$C_COLLD1		= count($EXP_COLLD);
			if($C_COLLD1 > 1)
			{
				$EXP_COLLD1	= str_replace('~', '', $EXP_COLLD1);
				$PRJCODE_HO	= $EXP_COLLD[0];
			}
			else
			{
				$PRJCODE_HO	= $EXP_COLLD1;
			}
		
			$sqlISHO 		= "SELECT PRJCODE, PRJCODE_HO FROM tbl_project_budg WHERE PRJCODE_HO = '$PRJCODE_HO' AND PRJSTAT = 1";
			$resISHO		= $this->db->query($sqlISHO)->result();
			foreach($resISHO as $rowISHO):
				$this->data['PRJCODE']		= $rowISHO->PRJCODE;
				$this->data['PRJCODE_HO']	= $rowISHO->PRJCODE_HO;
			endforeach;
	}
	
 	public function index() // G
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_project/c_f180p0/prj180d0blst/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function prj180d0blst() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_project/m_fpa/m_fpa', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "FPA";
			}
			else
			{
				$data["h1_title"] 	= "FPA";
			}
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN084';
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_project/c_f180p0/gallS180d0bpk/?id=";
			
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
	
	function gallS180d0bpk() // G
	{
		$this->load->model('m_project/m_fpa/m_fpa', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$data["mnCodeApp"] 	= 'MN084';
			$mnCode				= 'MN084';
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			
			$data['title'] 		= $appName;
			$data['addURL'] 	= site_url('c_project/c_f180p0/s180d0bpk_144n3/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['backURL'] 	= site_url('c_project/c_f180p0/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
			$MenuCode 			= 'MN084';
			$data["MenuCode"] 	= 'MN084';
			
			//$num_rows 			= $this->m_fpa->count_all_FPA($PRJCODE);
			//$data["countPR"] 	= $num_rows;
	 
			//$data['vwprojmatreq'] = $this->m_fpa->get_all_FPA($PRJCODE)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN084';
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
			
			$this->load->view('v_project/v_fpa/fpa_list', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

  	function get_AllData() // GOOD
	{		
		$PRJCODE		= $_GET['id'];
		
		// START : FOR SERVER-SIDE
			$order 	= $this->input->get("order");

			$col 	= 0;
			$dir 	= "";
			if(!empty($order)) {
				foreach($order as $o) {
					$col 	= $o['column'];
					$dir	= $o['dir'];
				}
			}
			
			if($dir != "asc" && $dir != "desc") 
			{
            	$dir = "asc";
        	}
			
			$columns_valid 	= array("",
									"FPA_CODE", 
									"FPA_DATE", 
									"FPA_NOTE", 
									"CREATERNM", 
									"STATDESC");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_fpa->get_AllDataC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_fpa->get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{	
				$FPA_NUM		= $dataI['FPA_NUM'];
				$FPA_CODE		= $dataI['FPA_CODE'];
				$FPA_DATE		= $dataI['FPA_DATE'];
				$FPA_DATEV		= date('d M Y', strtotime($FPA_DATE));
				$PRJCODE		= $dataI['PRJCODE'];
				$FPA_CATEG1		= $dataI['FPA_CATEG'];
				$mnCodeApp		= 'MN084';
				if($FPA_CATEG1 == 'MDR')
				{
					$mnCodeApp	= 'MN195';
					$FPA_CATEG 	= 'Mandor';
				}
				elseif($FPA_CATEG1 == 'SUB')
				{
					$mnCodeApp	= 'MN193';
					$FPA_CATEG 	= 'Subkontraktor';
				}
				
				$FPA_NOTE		= $dataI['FPA_NOTE'];
				$FPA_NOTED		= '';
				if($FPA_NOTE != '')
					$FPA_NOTED	= " : $FPA_NOTE";
				$FPA_STAT		= $dataI['FPA_STAT'];
				$JOBCODEID		= $dataI['JOBCODEID'];
				$FPA_CREATER	= $dataI['FPA_CREATER'];
				$JOBCODEID		= $dataI['JOBCODEID'];
				$JOBDESCD		= $dataI['JOBDESC'];
				$JOBDESC		= "$JOBDESCD - $FPA_NOTED";
					
				$STATDESC		= $dataI['STATDESC'];
				$STATCOL		= $dataI['STATCOL'];
				$CREATERNM		= $dataI['CREATERNM'];
				$empName		= cut_text2 ("$CREATERNM", 15);
				
				$mnCodeApp		= $mnCodeApp;
				$FPANUM			= "$PRJCODE~$FPA_NUM~$mnCodeApp";
				$secUpd			= site_url('c_project/c_f180p0/up180d0bdt/?id='.$this->url_encryption_helper->encode_url($FPANUM));
				$secPrint		= site_url('c_project/c_f180p0/printdocument/?id='.$this->url_encryption_helper->encode_url($FPA_NUM));
				$CollID			= "$FPA_NUM~$PRJCODE";
				$secDel_WO 		= base_url().'index.php/c_project/c_f180p0/trash_WO/?id='.$FPA_NUM;
                                    
				if($FPA_STAT == 1) 
				{
					$secAction	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
								   <label style='white-space:nowrap'>
								   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   </a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='#' onClick='deleteDOC('".$secDel_WO."')' title='Delete file' class='btn btn-danger btn-xs'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}                                            
				else
				{
					$secAction	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
								   <label style='white-space:nowrap'>
								   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   </a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='#' onClick='deleteDOC('".$secDel_WO."')' title='Delete file' class='btn btn-danger btn-xs' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
								
				
				$output['data'][] = array("$noU.",
										  $dataI['FPA_CODE'],
										  $FPA_DATEV,
										  $FPA_CATEG,
										  $JOBDESC,
										  $empName,
										  "<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
										  $secAction);
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function s180d0bpk_144n3() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_project/m_fpa/m_fpa', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$data["mnCodeApp"] 	= 'MN084';
			$mnCode				= 'MN084';
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			
			$data['PRJCODE'] 	= $PRJCODE;	
			$docPatternPosition = 'Especially';	
			$data['title'] 		= $appName;
			$data['task'] 		= 'add';
			$data['form_action']= site_url('c_project/c_f180p0/add_process');
			$data['backURL'] 	= site_url('c_project/c_f180p0/get_all_PR/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
			
			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			
			$MenuCode 			= 'MN084';
			$data["MenuCode"] 	= 'MN084';
			$data["MenuCode1"] 	= 'MN084';
			$data['vwDocPatt'] 	= $this->m_fpa->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN084';
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
			
			$this->load->view('v_project/v_fpa/fpa_form', $data);
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
			$this->load->model('m_project/m_fpa/m_fpa', '', TRUE);
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			$PRJCODE	= $_GET['pr1h0ec0JcoDe'];
			$JOBCODEID	= $_GET['pgfrm'];
			$FPA_CATEG	= $_GET['c4739'];
			
			$data['title'] 			= $appName;
			$data['PRJCODE'] 		= $PRJCODE;
			$data['secShowAll']		= site_url('c_project/c_f180p0/popupallitem/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			if($FPA_CATEG != '')
			{
				$data['countAllItem']	= $this->m_fpa->count_all_ItemServX($PRJCODE, $JOBCODEID, $FPA_CATEG);
				$data['vwAllItem'] 		= $this->m_fpa->viewAllItemServX($PRJCODE, $JOBCODEID, $FPA_CATEG)->result();
			}
			else
			{
				$data['countAllItem']	= $this->m_fpa->count_all_ItemServ($PRJCODE, $JOBCODEID);
				$data['vwAllItem'] 		= $this->m_fpa->viewAllItemServ($PRJCODE, $JOBCODEID)->result();
			}
			
			$this->load->view('v_project/v_fpa/fpa_selitem', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function genCode() // OK
	{
		$PRJCODEX	= $this->input->post('PRJCODEX');
		$PattCode	= $this->input->post('Pattern_Code');
		$PattLength	= $this->input->post('Pattern_Length');
		$useYear	= $this->input->post('useYear');
		$useMonth	= $this->input->post('useMonth');
		$useDate	= $this->input->post('useDate');
		
		$PRDate		= date('Y-m-d',strtotime($this->input->post('WODate')));
		$year		= date('Y',strtotime($this->input->post('WODate')));
		$month 		= (int)date('m',strtotime($this->input->post('WODate')));
		$date 		= (int)date('d',strtotime($this->input->post('WODate')));
		
		$this->db->where('Patt_Year', $year);
		$myCount = $this->db->count_all('tbl_fpa_header');
		
		$sql	 = "SELECT MAX(Patt_Number) as maxNumber FROM tbl_fpa_header
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
		$this->load->model('m_project/m_fpa/m_fpa', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{	
			$this->db->trans_begin();
			
			date_default_timezone_set("Asia/Jakarta");
			
			$FPA_STAT 		= $this->input->post('FPA_STAT'); // 1 = New, 2 = confirm, 3 = Close
			
			$FPA_NUM 		= $this->input->post('FPA_NUM');
			$FPA_CODE 		= $this->input->post('FPA_CODE');
			//setting WO Date
			$FPA_DATE		= date('Y-m-d',strtotime($this->input->post('FPA_DATE')));
				$Patt_Year	= date('Y',strtotime($this->input->post('FPA_DATE')));
				$Patt_Month	= date('m',strtotime($this->input->post('FPA_DATE')));
				$Patt_Date	= date('d',strtotime($this->input->post('FPA_DATE')));
			$FPA_TSFD		= date('Y-m-d',strtotime($this->input->post('FPA_TSFD')));
			$PRJCODE 		= $this->input->post('PRJCODE');
			$FPA_CATEG 		= $this->input->post('FPA_CATEG');
			$FPA_NOTE 		= $this->input->post('FPA_NOTE');
			$FPA_MEMO 		= $this->input->post('FPA_MEMO');
			$FPA_STAT 		= $this->input->post('FPA_STAT');
			
			//$JOBCODEID	= $this->input->post('JOBCODEID');
			$PRREFNO		= $this->input->post('JOBCODEID');
			$FPA_VALUE		= $this->input->post('FPA_VALUE');
			if($PRREFNO != '')
			{
				$refStep	= 0;
				foreach ($PRREFNO as $PR_REFNO)
				{
					$refStep	= $refStep + 1;
					if($refStep == 1)
					{
						$COLPR_REFNO	= "$PR_REFNO";
					}
					else
					{
						$COLPR_REFNO	= "$COLPR_REFNO~$PR_REFNO";
					}
				}
			}
			else
			{
				$COLPR_REFNO	= '';
			}
			$JOBCODEID		= $COLPR_REFNO;
			
			// START - PEMBENTUKAN GENERATE CODE
				$this->load->model('m_projectlist/m_projectlist', '', TRUE);
				$Pattern_Code	= "XX";
				$MenuCode 		= 'MN084';
				$vwDocPatt		= $this->m_projectlist->getDataDocPat($MenuCode)->result();
				foreach($vwDocPatt as $row) :
					$Pattern_Code = $row->Pattern_Code;
				endforeach;
				
				$PRJCODE 		= $this->input->post('PRJCODE');
				$TRXTIME1		= date('ymdHis');
				$FPA_NUM		= "$Pattern_Code$PRJCODE-$TRXTIME1";
			// END - PEMBENTUKAN GENERATE CODE
			
			$projWOH 		= array('FPA_NUM' 		=> $FPA_NUM,
									'FPA_CODE' 		=> $FPA_CODE,
									'FPA_DATE'		=> $FPA_DATE,
									'FPA_TSFD'		=> $FPA_TSFD,
									'PRJCODE'		=> $PRJCODE,
									'FPA_CATEG'		=> $FPA_CATEG,
									'FPA_TYPE'		=> 'FPA',
									'JOBCODEID'		=> $JOBCODEID,
									'FPA_NOTE'		=> $FPA_NOTE,
									'FPA_MEMO'		=> $FPA_MEMO,
									'FPA_VALUE'		=> $FPA_VALUE,
									'FPA_STAT'		=> $FPA_STAT,
									'FPA_CREATER'	=> $DefEmp_ID,
									'FPA_CREATED'	=> date('Y-m-d H:i:s'),
									'Patt_Year'		=> $Patt_Year, 
									'Patt_Month'	=> $Patt_Month,
									'Patt_Date'		=> $Patt_Date,
									'Patt_Number'	=> $this->input->post('Patt_Number'));
			$this->m_fpa->add($projWOH);
			
			foreach($_POST['data'] as $d)
			{
				$d['FPA_NUM']	= $FPA_NUM;
				$d['FPA_CODE']	= $FPA_CODE;
				$d['PRJCODE']	= $PRJCODE;
				$d['FPA_DATE']	= $FPA_DATE;
				$ITMUNIT		= $d['ITM_UNIT'];
				$UNITTYPE		= strtoupper($ITMUNIT);
				if($UNITTYPE == 'LS')
				{
					$d['ITM_PRICE']	= 1;
				}
				$this->db->insert('tbl_fpa_detail',$d);
			}
			
			// UPDATE DETAIL
				//$this->m_fpa->updateDet($FPA_NUM, $PRJCODE, $FPA_DATE);
				
			// START : UPDATE TO TRANS-COUNT
				/*$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('FPA_STAT');			// IF "ADD" CONDITION ALWAYS = PR_STAT
				$parameters 	= array('DOC_CODE' 		=> $FPA_NUM,			// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "WO",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_fpa_header",	// TABLE NAME
										'KEY_NAME'		=> "FPA_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "FPA_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $FPA_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_WO",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_FPA_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_FPA_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_FPA_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_FPA_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_FPA_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_FPA_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
				$this->m_updash->updateDashData($parameters);*/
			// END : UPDATE TO TRANS-COUNT
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "FPA_NUM",
										'DOC_CODE' 		=> $FPA_NUM,
										'DOC_STAT' 		=> $FPA_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_fpa_header");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $FPA_NUM;
				$MenuCode 		= 'MN238';
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
			
			$url			= site_url('c_project/c_f180p0/gallS180d0bpk/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function up180d0bdt() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_project/m_fpa/m_fpa', '', TRUE);
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$FPANUM		= $_GET['id'];
		$FPANUM		= $this->url_encryption_helper->decode_url($FPANUM);
		$FPACOLL	= explode('~', $FPANUM);
		$FPA_NUM	= $FPACOLL[1];
		$mnCode		= $FPACOLL[2];
			
		// GET MENU DESC
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$data["mnCodeApp"] 	= $mnCode;
			$mnCode				= 'MN084';
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$docPatternPosition = 'Especially';	
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_project/c_f180p0/update_process');
			
			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			
			$getpurreq 						= $this->m_fpa->get_FPA_by_number($FPA_NUM)->row();
			$data['default']['FPA_NUM'] 	= $getpurreq->FPA_NUM;
			$data['default']['FPA_CODE'] 	= $getpurreq->FPA_CODE;
			$data['default']['FPA_DATE'] 	= $getpurreq->FPA_DATE;
			$data['default']['FPA_TSFD'] 	= $getpurreq->FPA_TSFD;
			$data['default']['PRJCODE']		= $getpurreq->PRJCODE;
			$data['PRJCODE']				= $getpurreq->PRJCODE;
			$PRJCODE 						= $getpurreq->PRJCODE;
			$data['default']['FPA_CATEG'] 	= $getpurreq->FPA_CATEG;
			$data['default']['JOBCODEID'] 	= $getpurreq->JOBCODEID;
			$data['default']['FPA_NOTE'] 	= $getpurreq->FPA_NOTE;
			$data['default']['FPA_NOTE2'] 	= $getpurreq->FPA_NOTE2;
			$data['default']['FPA_STAT'] 	= $getpurreq->FPA_STAT;
			$data['default']['FPA_VALUE'] 	= $getpurreq->FPA_VALUE;
			$data['default']['FPA_MEMO'] 	= $getpurreq->FPA_MEMO;
			$data['default']['PRJNAME'] 	= $getpurreq->PRJNAME;
			$data['default']['Patt_Year'] 	= $getpurreq->Patt_Year;
			$data['default']['Patt_Month'] 	= $getpurreq->Patt_Month;
			$data['default']['Patt_Date'] 	= $getpurreq->Patt_Date;
			$data['default']['Patt_Number']	= $getpurreq->Patt_Number;
			
			$MenuCode 			= 'MN084';
			$data["MenuCode"] 	= 'MN084';
			$data["MenuCode1"] 	= 'MN154';
			$data['vwDocPatt'] 	= $this->m_fpa->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getpurreq->FPA_NUM;
				$MenuCode 		= 'MN238';
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
			
			$this->load->view('v_project/v_fpa/fpa_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process() // G
	{
		$this->load->model('m_project/m_fpa/m_fpa', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{	
			$this->db->trans_begin();
			
			date_default_timezone_set("Asia/Jakarta");
			
			$FPA_STAT 		= $this->input->post('FPA_STAT'); // 1 = New, 2 = confirm, 3 = Close
			
			$FPA_NUM 		= $this->input->post('FPA_NUM');
			$FPA_CODE 		= $this->input->post('FPA_CODE');
			//setting WO Date
			$FPA_DATE		= date('Y-m-d',strtotime($this->input->post('FPA_DATE')));
				$Patt_Year	= date('Y',strtotime($this->input->post('FPA_DATE')));
				$Patt_Month	= date('m',strtotime($this->input->post('FPA_DATE')));
				$Patt_Date	= date('d',strtotime($this->input->post('FPA_DATE')));
			$FPA_TSFD		= date('Y-m-d',strtotime($this->input->post('FPA_TSFD')));
			$PRJCODE 		= $this->input->post('PRJCODE');
			$FPA_CATEG 		= $this->input->post('FPA_CATEG');
			$FPA_NOTE 		= $this->input->post('FPA_NOTE');
			$FPA_MEMO 		= $this->input->post('FPA_MEMO');
			$FPA_STAT 		= $this->input->post('FPA_STAT');
			
			//$JOBCODEID	= $this->input->post('JOBCODEID');
			$PRREFNO		= $this->input->post('JOBCODEID');
			$FPA_VALUE		= $this->input->post('FPA_VALUE');
			if($PRREFNO != '')
			{
				$refStep	= 0;
				foreach ($PRREFNO as $PR_REFNO)
				{
					$refStep	= $refStep + 1;
					if($refStep == 1)
					{
						$COLPR_REFNO	= "$PR_REFNO";
					}
					else
					{
						$COLPR_REFNO	= "$COLPR_REFNO~$PR_REFNO";
					}
				}
			}
			else
			{
				$COLPR_REFNO	= '';
			}
			$JOBCODEID		= $COLPR_REFNO;
			
			$projWOH 		= array('FPA_CODE' 		=> $FPA_CODE,
									'FPA_DATE'		=> $FPA_DATE,
									'FPA_TSFD'		=> $FPA_TSFD,
									'PRJCODE'		=> $PRJCODE,
									'FPA_CATEG'		=> $FPA_CATEG,
									'FPA_TYPE'		=> 'FPA',
									'JOBCODEID'		=> $JOBCODEID,
									'FPA_NOTE'		=> $FPA_NOTE,
									'FPA_MEMO'		=> $FPA_MEMO,
									'FPA_VALUE'		=> $FPA_VALUE,
									'FPA_STAT'		=> $FPA_STAT,
									'FPA_CREATER'	=> $DefEmp_ID,
									'FPA_CREATED'	=> date('Y-m-d H:i:s'),
									'Patt_Year'		=> $Patt_Year, 
									'Patt_Month'	=> $Patt_Month,
									'Patt_Date'		=> $Patt_Date);
			$this->m_fpa->update($FPA_NUM, $projWOH);
			
			if($FPA_STAT == 6 || $FPA_STAT = 9)
			{
				//
			}
			else
			{
				$this->m_fpa->deleteDetail($FPA_NUM);
			
				foreach($_POST['data'] as $d)
				{
					$d['FPA_NUM']	= $FPA_NUM;
					$d['FPA_CODE']	= $FPA_CODE;
					$d['PRJCODE']	= $PRJCODE;
					$d['FPA_DATE']	= $FPA_DATE;
					$ITMUNIT		= $d['ITM_UNIT'];
					$UNITTYPE		= strtoupper($ITMUNIT);
					if($UNITTYPE == 'LS')
					{
						$d['ITM_PRICE']	= 1;
					}
					$this->db->insert('tbl_fpa_detail',$d);
				}
			}
				
			// START : UPDATE TO TRANS-COUNT
				/*$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('STAT_BEFORE');		// IF "ADD" CONDITION ALWAYS = PR_STAT
				$parameters 	= array('DOC_CODE' 		=> $FPA_NUM,			// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "WO",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_fpa_header",	// TABLE NAME
										'KEY_NAME'		=> "FPA_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "FPA_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $FPA_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_WO",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_FPA_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_FPA_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_FPA_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_FPA_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_FPA_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_FPA_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
				$this->m_updash->updateDashData($parameters);*/
			// END : UPDATE TO TRANS-COUNT
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "FPA_NUM",
										'DOC_CODE' 		=> $FPA_NUM,
										'DOC_STAT' 		=> $FPA_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_fpa_header");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $FPA_NUM;
				$MenuCode 		= 'MN084';
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
			$url			= site_url('c_project/c_f180p0/gallS180d0bpk/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
 	function inbox() // G
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_project/c_f180p0/prM_dR/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function prM_dR() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_project/m_fpa/m_fpa', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Persetujuan FPA - Mandor";
			}
			else
			{
				$data["h1_title"] 	= "FPA Approval - Mandor";
			}
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN195';
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_project/c_f180p0/gallS180d0bpk_1n2/?id=";
			
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
	
	function gallS180d0bpk_1n2() // G
	{
		$this->load->model('m_project/m_fpa/m_fpa', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$data["mnCodeApp"] 	= 'MN195';
			$mnCode				= 'MN195';
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			
			$data['title'] 		= $appName;
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Persetujuan";
				$data["h2_title"] 	= "FPA Mandor";
			}
			else
			{
				$data["h1_title"] 	= "Approval";
				$data["h2_title"] 	= "FPA Mandor";
			}
			
			$data['addURL'] 	= site_url('c_project/c_f180p0/add/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['backURL'] 	= site_url('c_project/c_f180p0/inbox/');
			$data['PRJCODE'] 	= $PRJCODE;
			
			$num_rows 			= $this->m_fpa->count_all_FPAInx($DefEmp_ID);
			$data["countPR"] 	= $num_rows;
	 
			$data['vwprojmatreq'] = $this->m_fpa->get_all_FPAInb($DefEmp_ID)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN195';
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
			
			$this->load->view('v_project/v_fpa/fpa_inb', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

  	function get_AllData_1n2() // GOOD
	{		
		$PRJCODE		= $_GET['id'];
		
		// START : FOR SERVER-SIDE
			$order 	= $this->input->get("order");

			$col 	= 0;
			$dir 	= "";
			if(!empty($order)) {
				foreach($order as $o) {
					$col 	= $o['column'];
					$dir	= $o['dir'];
				}
			}
			
			if($dir != "asc" && $dir != "desc") 
			{
            	$dir = "asc";
        	}
			
			$columns_valid 	= array("",
									"FPA_CODE", 
									"FPA_DATE", 
									"FPA_NOTE", 
									"CREATERNM", 
									"STATDESC");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_fpa->get_AllDataC_1n2($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_fpa->get_AllDataL_1n2($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{	
				$FPA_NUM		= $dataI['FPA_NUM'];
				$FPA_CODE		= $dataI['FPA_CODE'];
				$FPA_DATE		= $dataI['FPA_DATE'];
				$FPA_DATEV		= date('d M Y', strtotime($FPA_DATE));
				$PRJCODE		= $dataI['PRJCODE'];
				$FPA_CATEG1		= $dataI['FPA_CATEG'];
				$mnCodeApp		= 'MN084';
				if($FPA_CATEG1 == 'MDR')
				{
					$mnCodeApp	= 'MN195';
					$FPA_CATEG 	= 'Mandor';
				}
				elseif($FPA_CATEG1 == 'SUB')
				{
					$mnCodeApp	= 'MN193';
					$FPA_CATEG 	= 'Subkontraktor';
				}
				
				$FPA_NOTE		= $dataI['FPA_NOTE'];
				$FPA_NOTED		= '';
				if($FPA_NOTE != '')
					$FPA_NOTED	= " : $FPA_NOTE";
				$FPA_STAT		= $dataI['FPA_STAT'];
				$JOBCODEID		= $dataI['JOBCODEID'];
				$FPA_CREATER	= $dataI['FPA_CREATER'];
				$JOBCODEID		= $dataI['JOBCODEID'];
				$JOBDESCD		= $dataI['JOBDESC'];
				$JOBDESC		= "$JOBDESCD - $FPA_NOTED";
					
				$STATDESC		= $dataI['STATDESC'];
				$STATCOL		= $dataI['STATCOL'];
				$CREATERNM		= $dataI['CREATERNM'];
				$empName		= cut_text2 ("$CREATERNM", 15);
				
				$mnCodeApp		= $mnCodeApp;
				$FPANUM			= "$PRJCODE~$FPA_NUM~$mnCodeApp";
				$secUpd			= site_url('c_project/c_f180p0/gallS180d0bpk_1nu/?id='.$this->url_encryption_helper->encode_url($FPANUM));
				$secPrint		= site_url('c_project/c_f180p0/printdocument/?id='.$this->url_encryption_helper->encode_url($FPA_NUM));
				$CollID			= "$FPA_NUM~$PRJCODE";
				$secDel_WO 		= base_url().'index.php/c_project/c_f180p0/trash_WO/?id='.$FPA_NUM;
                
				$secAction	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
								   <label style='white-space:nowrap'>
								   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   </a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='#' onClick='deleteDOC('".$secDel_WO."')' title='Delete file' class='btn btn-danger btn-xs' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				
				$output['data'][] = array("$noU.",
										  $dataI['FPA_CODE'],
										  $FPA_DATEV,
										  $JOBDESC,
										  $empName,
										  "<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",

										  $secAction);
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function gallS180d0bpk_1nu() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_project/m_fpa/m_fpa', '', TRUE);
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$FPANUM		= $_GET['id'];
		$FPANUM		= $this->url_encryption_helper->decode_url($FPANUM);
		$FPACOLL	= explode('~', $FPANUM);
		$FPA_NUM	= $FPACOLL[1];
		$mnCode		= $FPACOLL[2];
			
		// GET MENU DESC
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$data["mnCodeApp"] 	= $mnCode;
			$mnCode				= 'MN195';
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$docPatternPosition = 'Especially';	
			$data['title'] 		= $appName;
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Persetujuan";
				$data["h2_title"] 	= "FPA Mandor";
			}
			else
			{
				$data["h1_title"] 	= "Approval";
				$data["h2_title"] 	= "FPA Mandor";
			}
			
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_project/c_f180p0/update_process_inb');
			
			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			
			$getpurreq 						= $this->m_fpa->get_FPA_by_number($FPA_NUM)->row();			
			$data['default']['FPA_NUM'] 	= $getpurreq->FPA_NUM;
			$data['default']['FPA_CODE'] 	= $getpurreq->FPA_CODE;
			$data['default']['FPA_DATE'] 	= $getpurreq->FPA_DATE;
			$data['default']['FPA_TSFD'] 	= $getpurreq->FPA_TSFD;
			$data['default']['PRJCODE']		= $getpurreq->PRJCODE;
			$data['PRJCODE']				= $getpurreq->PRJCODE;
			$PRJCODE 						= $getpurreq->PRJCODE;
			$data['default']['FPA_CATEG'] 	= $getpurreq->FPA_CATEG;
			$data['default']['JOBCODEID'] 	= $getpurreq->JOBCODEID;
			$data['default']['FPA_NOTE'] 	= $getpurreq->FPA_NOTE;
			$data['default']['FPA_NOTE2'] 	= $getpurreq->FPA_NOTE2;
			$data['default']['FPA_STAT'] 	= $getpurreq->FPA_STAT;
			$data['default']['FPA_VALUE'] 	= $getpurreq->FPA_VALUE;
			$data['default']['FPA_MEMO'] 	= $getpurreq->FPA_MEMO;
			$data['default']['PRJNAME'] 	= $getpurreq->PRJNAME;
			$data['default']['Patt_Year'] 	= $getpurreq->Patt_Year;
			$data['default']['Patt_Month'] 	= $getpurreq->Patt_Month;
			$data['default']['Patt_Date'] 	= $getpurreq->Patt_Date;
			$data['default']['Patt_Number']	= $getpurreq->Patt_Number;
			
			$MenuCode 			= 'MN195';
			$data["MenuCode"] 	= 'MN195';
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getpurreq->FPA_NUM;
				$MenuCode 		= 'MN195';
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
			
			$this->load->view('v_project/v_fpa/fpa_inb_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
 	function inbox2() // G
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_project/c_f180p0/prj5_Ub/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function prj5_Ub() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_project/m_fpa/m_fpa', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Persetujuan FPA - Mandor";
			}
			else
			{
				$data["h1_title"] 	= "FPA Approval - Mandor";
			}
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN195';
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_project/c_f180p0/gallS180d0bpk_1n2A/?id=";
			
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
	
	function gallS180d0bpk_1n2A() // G
	{
		$this->load->model('m_project/m_fpa/m_fpa', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$data["mnCodeApp"] 	= 'MN193';
			$mnCode				= 'MN193';
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			$data['title'] 		= $appName;
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Persetujuan";
				$data["h2_title"] 	= "FPA Subkon";
			}
			else
			{
				$data["h1_title"] 	= "Approval";
				$data["h2_title"] 	= "FPA Subkon";
			}
			
			$data['addURL'] 	= site_url('c_project/c_f180p0/add/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['backURL'] 	= site_url('c_project/c_f180p0/prj5_Ub/');
			$data['PRJCODE'] 	= $PRJCODE;
			
			//$num_rows 			= $this->m_fpa->count_all_FPAInxS($DefEmp_ID);
			//$data["countPR"] 	= $num_rows;
	 
			//$data['vwprojmatreq'] = $this->m_fpa->get_all_FPAInbS($DefEmp_ID)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN193';
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
			
			$this->load->view('v_project/v_fpa/sub_fpa_inb', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

  	function get_AllData_1n25uB() // GOOD
	{		
		$PRJCODE		= $_GET['id'];
		
		// START : FOR SERVER-SIDE
			$order 	= $this->input->get("order");

			$col 	= 0;
			$dir 	= "";
			if(!empty($order)) {
				foreach($order as $o) {
					$col 	= $o['column'];
					$dir	= $o['dir'];
				}
			}
			
			if($dir != "asc" && $dir != "desc") 
			{
            	$dir = "asc";
        	}
			
			$columns_valid 	= array("",
									"FPA_CODE", 
									"FPA_DATE", 
									"FPA_NOTE", 
									"CREATERNM", 
									"STATDESC");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_fpa->get_AllDataC_1n25uB($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_fpa->get_AllDataL_1n25uB($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{	
				$FPA_NUM		= $dataI['FPA_NUM'];
				$FPA_CODE		= $dataI['FPA_CODE'];
				$FPA_DATE		= $dataI['FPA_DATE'];
				$FPA_DATEV		= date('d M Y', strtotime($FPA_DATE));
				$PRJCODE		= $dataI['PRJCODE'];
				$FPA_CATEG1		= $dataI['FPA_CATEG'];
				$mnCodeApp		= 'MN084';
				if($FPA_CATEG1 == 'MDR')
				{
					$mnCodeApp	= 'MN195';
					$FPA_CATEG 	= 'Mandor';
				}
				elseif($FPA_CATEG1 == 'SUB')
				{
					$mnCodeApp	= 'MN193';
					$FPA_CATEG 	= 'Subkontraktor';
				}
				
				$FPA_NOTE		= $dataI['FPA_NOTE'];
				$FPA_NOTED		= '';
				if($FPA_NOTE != '')
					$FPA_NOTED	= " : $FPA_NOTE";
				$FPA_STAT		= $dataI['FPA_STAT'];
				$JOBCODEID		= $dataI['JOBCODEID'];
				$FPA_CREATER	= $dataI['FPA_CREATER'];
				$JOBCODEID		= $dataI['JOBCODEID'];
				$JOBDESCD		= $dataI['JOBDESC'];
				$JOBDESC		= "$JOBDESCD - $FPA_NOTED";
					
				$STATDESC		= $dataI['STATDESC'];
				$STATCOL		= $dataI['STATCOL'];
				$CREATERNM		= $dataI['CREATERNM'];
				$empName		= cut_text2 ("$CREATERNM", 15);
				
				$mnCodeApp		= $mnCodeApp;
				$FPANUM			= "$PRJCODE~$FPA_NUM~$mnCodeApp";
				$secUpd			= site_url('c_project/c_f180p0/gallS180d0bpk_1nuA/?id='.$this->url_encryption_helper->encode_url($FPANUM));
				$secPrint		= site_url('c_project/c_f180p0/printdocument/?id='.$this->url_encryption_helper->encode_url($FPA_NUM));
				$CollID			= "$FPA_NUM~$PRJCODE";
				$secDel_WO 		= base_url().'index.php/c_project/c_f180p0/trash_WO/?id='.$FPA_NUM;
                
				$secAction	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
								   <label style='white-space:nowrap'>
								   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   </a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='#' onClick='deleteDOC('".$secDel_WO."')' title='Delete file' class='btn btn-danger btn-xs' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				
				$output['data'][] = array("$noU.",
										  $dataI['FPA_CODE'],
										  $FPA_DATEV,
										  $JOBDESC,
										  $empName,
										  "<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",

										  $secAction);
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function gallS180d0bpk_1nuA() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_project/m_fpa/m_fpa', '', TRUE);
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$FPANUM		= $_GET['id'];
		$FPANUM		= $this->url_encryption_helper->decode_url($FPANUM);
		$FPACOLL	= explode('~', $FPANUM);
		$FPA_NUM	= $FPACOLL[1];
		$mnCode		= $FPACOLL[2];
			
		// GET MENU DESC
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$data["mnCodeApp"] 	= $mnCode;
			$mnCode				= 'MN193';
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$docPatternPosition = 'Especially';	
			$data['title'] 		= $appName;
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Persetujuan";
				$data["h2_title"] 	= "FPA Subkon";
			}
			else
			{
				$data["h1_title"] 	= "Approval";
				$data["h2_title"] 	= "FPA Subcont";
			}
			
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_project/c_f180p0/update_process_inb');
			
			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			
			$getpurreq 						= $this->m_fpa->get_FPA_by_number($FPA_NUM)->row();			
			$data['default']['FPA_NUM'] 	= $getpurreq->FPA_NUM;
			$data['default']['FPA_CODE'] 	= $getpurreq->FPA_CODE;
			$data['default']['FPA_DATE'] 	= $getpurreq->FPA_DATE;
			$data['default']['FPA_TSFD'] 	= $getpurreq->FPA_TSFD;
			$data['default']['PRJCODE']		= $getpurreq->PRJCODE;
			$data['PRJCODE']				= $getpurreq->PRJCODE;
			$PRJCODE 						= $getpurreq->PRJCODE;
			$data['default']['FPA_CATEG'] 	= $getpurreq->FPA_CATEG;
			$data['default']['JOBCODEID'] 	= $getpurreq->JOBCODEID;
			$data['default']['FPA_NOTE'] 	= $getpurreq->FPA_NOTE;
			$data['default']['FPA_NOTE2'] 	= $getpurreq->FPA_NOTE2;
			$data['default']['FPA_STAT'] 	= $getpurreq->FPA_STAT;
			$data['default']['FPA_VALUE'] 	= $getpurreq->FPA_VALUE;
			$data['default']['FPA_MEMO'] 	= $getpurreq->FPA_MEMO;
			$data['default']['PRJNAME'] 	= $getpurreq->PRJNAME;
			$data['default']['Patt_Year'] 	= $getpurreq->Patt_Year;
			$data['default']['Patt_Month'] 	= $getpurreq->Patt_Month;
			$data['default']['Patt_Date'] 	= $getpurreq->Patt_Date;
			$data['default']['Patt_Number']	= $getpurreq->Patt_Number;
			
			$MenuCode 			= 'MN193';
			$data["MenuCode"] 	= 'MN193';
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getpurreq->FPA_NUM;
				$MenuCode 		= 'MN193';
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
			
			$this->load->view('v_project/v_fpa/sub_fpa_inb_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process_inb() // G
	{
		$this->load->model('m_project/m_fpa/m_fpa', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{	
			$this->db->trans_begin();
			
			date_default_timezone_set("Asia/Jakarta");
						
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			
			$FPA_NUM 		= $this->input->post('FPA_NUM');
			$PRJCODE 		= $this->input->post('PRJCODE');
			$FPA_STAT 		= $this->input->post('FPA_STAT');
			$FPA_CATEG 		= $this->input->post('FPA_CATEG');
			
			// START : SAVE APPROVE HISTORY
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$AH_CODE		= $FPA_NUM;
				$AH_APPLEV		= $this->input->post('APP_LEVEL');
				$AH_APPROVER	= $DefEmp_ID;
				$AH_APPROVED	= date('Y-m-d H:i:s');
				$AH_NOTES		= $this->input->post('FPA_NOTE2');
				$AH_ISLAST		= $this->input->post('IS_LAST');
				
				$insAppHist 	= array('AH_CODE'		=> $AH_CODE,
										'AH_APPLEV'		=> $AH_APPLEV,
										'AH_APPROVER'	=> $AH_APPROVER,
										'AH_APPROVED'	=> $AH_APPROVED,
										'AH_NOTES'		=> $AH_NOTES,
										'AH_ISLAST'		=> $AH_ISLAST);										
				$this->m_updash->insAppHist($insAppHist);
			
				$projWOH		= array('FPA_STAT'	=> 7);		// Default ke waiting jika masih ada approver yang lain									
				$this->m_fpa->update($FPA_NUM, $projWOH);
				
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "FPA_NUM",
											'DOC_CODE' 		=> $FPA_NUM,
											'DOC_STAT' 		=> 7,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> '',
											'TBLNAME'		=> "tbl_fpa_header");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
			// END : SAVE APPROVE HISTORY
			
			if($AH_ISLAST == 1)
			{
				$projWOH 		= array('FPA_APPROVER'	=> $DefEmp_ID,
										'FPA_APPROVED'	=> date('Y-m-d H:i:s'),
										'FPA_NOTE2'		=> $this->input->post('FPA_NOTE2'),
										'FPA_STAT'		=> $this->input->post('FPA_STAT'));										
				$this->m_fpa->update($FPA_NUM, $projWOH);
				
				// START : UPDATE TO TRANS-COUNT
					/*$this->load->model('m_updash/m_updash', '', TRUE);
					
					$STAT_BEFORE	= $this->input->post('STAT_BEFORE');		// IF "ADD" CONDITION ALWAYS = PR_STAT
					$parameters 	= array('DOC_CODE' 		=> $FPA_NUM,			// TRANSACTION CODE
											'PRJCODE' 		=> $PRJCODE,		// PROJECT
											'TR_TYPE'		=> "FPA",			// TRANSACTION TYPE
											'TBL_NAME' 		=> "tbl_fpa_header",	// TABLE NAME
											'KEY_NAME'		=> "FPA_NUM",		// KEY OF THE TABLE
											'STAT_NAME' 	=> "FPA_STAT",		// NAMA FIELD STATUS
											'STATDOC' 		=> $FPA_STAT,		// TRANSACTION STATUS
											'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
											'FIELD_NM_ALL'	=> "TOT_WO",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
											'FIELD_NM_N'	=> "TOT_FPA_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
											'FIELD_NM_C'	=> "TOT_FPA_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
											'FIELD_NM_A'	=> "TOT_FPA_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
											'FIELD_NM_R'	=> "TOT_FPA_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
											'FIELD_NM_RJ'	=> "TOT_FPA_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_data
											'FIELD_NM_CL'	=> "TOT_FPA_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
					$this->m_updash->updateDashData($parameters);*/
				// END : UPDATE TO TRANS-COUNT
				
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "FPA_NUM",
											'DOC_CODE' 		=> $FPA_NUM,
											'DOC_STAT' 		=> $FPA_STAT,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> '',
											'TBLNAME'		=> "tbl_fpa_header");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
			}
			
			
			// START : CLEAR HISTORY
				$this->load->model('m_updash/m_updash', '', TRUE);
				if($FPA_STAT != 3)
				{
					if($FPA_STAT == 4)
					{
						$cllPar = array('AH_CODE' 		=> $FPA_NUM,
										'AH_APPROVER'	=> $DefEmp_ID);
						$this->m_updash->clearHist($cllPar);
					}
			
					$projWOH		= array('FPA_STAT'	=> $FPA_STAT);
					$this->m_fpa->update($FPA_NUM, $projWOH);
					
					// START : UPDATE STATUS
						$completeName 	= $this->session->userdata['completeName'];
						$paramStat 		= array('PM_KEY' 		=> "FPA_NUM",
												'DOC_CODE' 		=> $FPA_NUM,
												'DOC_STAT' 		=> $FPA_STAT,
												'PRJCODE' 		=> $PRJCODE,
												'CREATERNM'		=> '',
												'TBLNAME'		=> "tbl_fpa_header");
						$this->m_updash->updateStatus($paramStat);
					// END : UPDATE STATUS
				}
			// END : CLEAR HISTORY
				
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $FPA_NUM;
				$MenuCode 		= 'MN193';
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
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			if($FPA_CATEG == 'MDR')
				$url		= site_url('c_project/c_f180p0/gallS180d0bpk_1n2/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			else
				$url		= site_url('c_project/c_f180p0/gallS180d0bpk_1n2A/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
				
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
}