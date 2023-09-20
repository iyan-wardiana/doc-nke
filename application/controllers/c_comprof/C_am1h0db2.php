<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 22 April 2018
	* File Name		= C_am1h0db2.php
	* Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_am1h0db2 extends CI_Controller  
{
  	function __construct() // GOOD
	{ 
		parent::__construct();
		
		$this->load->model('m_company/m_project_amd/m_project_amd', '', TRUE);
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
				$PRJCODE	= $EXP_COLLD[0];
			}
			else
			{
				$PRJCODE	= $EXP_COLLD1;
			}
		
			$sqlISHO 		= "SELECT PRJCODE, PRJCODE_HO FROM tbl_project_budg WHERE PRJCODE = '$PRJCODE' AND PRJSTAT = 1";
			$resISHO		= $this->db->query($sqlISHO)->result();
			foreach($resISHO as $rowISHO):
				$this->data['PRJCODE']		= $rowISHO->PRJCODE;
				$this->data['PRJCODE_HO']	= $rowISHO->PRJCODE_HO;
			endforeach;
	}
	
 	function index() // OK
	{
		$this->load->model('m_company/m_project_amd/m_project_amd', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;
		
		$url			= site_url('c_comprof/c_am1h0db2/ix1h0db2/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function ix1h0db2() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$LangID 	= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN406';
				$data["MenuApp"] 	= 'MN407';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;

			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN406';
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_comprof/c_am1h0db2/gall1h0db2amd/?id=";
			
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
	
	function gall1h0db2amd() // OK
	{
		$this->load->model('m_company/m_project_amd/m_project_amd', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN406';
			$data["MenuApp"] 	= 'MN407';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$EmpID 		= $this->session->userdata('Emp_ID');
			
			// -------------------- START : SEARCHING METHOD --------------------
				
				$collDATA		= $_GET['id'];
				$EXP_COLLD1		= $this->url_encryption_helper->decode_url($collDATA);	
				$EXP_COLLD		= explode('~', $EXP_COLLD1);	
				$C_COLLD1		= count($EXP_COLLD);
				
				if($C_COLLD1 > 1)
				{
					$EXP_COLLD1	= str_replace('~', '', $EXP_COLLD1);
					$key		= $EXP_COLLD[0];
					$PRJCODE	= $EXP_COLLD[1];
					$mxLS		= $EXP_COLLD[2];
					$end		= $EXP_COLLD[3];
					$start		= 0;
				}
				else
				{
					$key		= '';
					$PRJCODE	= $EXP_COLLD1;
					$start		= 0;
					$end		= 50;
				}
				$data["url_search"] = site_url('c_comprof/c_am1h0db2/f4n7_5rcH/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
				//$num_rows 			= $this->m_project_amd->count_all_amd($PRJCODE, $key);
				//$data["cData"] 		= $num_rows;	 
				//$data['vData']		= $this->m_project_amd->get_all_amd($PRJCODE, $start, $end, $key)->result();
			// -------------------- END : SEARCHING METHOD --------------------
			
			$data['title'] 		= $appName;
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h2_title"] 	= "Amandemen";
				$data['h3_title']	= "anggaran proyek";
			}
			else
			{
				$data["h2_title"] 	= "Amendment";
				$data['h3_title']	= "project budget";
			}
			$data['PRJCODE']	= $PRJCODE;
			$data["MenuCode"] 	= 'MN406';
			$data['backURL'] 	= site_url('c_comprof/c_am1h0db2/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
						
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN406';
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
			
			$this->load->view('v_company/v_project_amd/v_amd_list', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	// -------------------- START : SEARCHING METHOD --------------------
		function f4n7_5rcH()
		{
			$gEn5rcH		= $_POST['gEn5rcH'];
			$mxLS			= $_POST['maxLimDf'];
			$mxLF			= $_POST['maxLimit'];
			$PRJCODE		= $_GET['id'];
			$PRJCODE		= $this->url_encryption_helper->decode_url($PRJCODE);
			$collDATA		= "$gEn5rcH~$PRJCODE~$mxLS~$mxLF";
			$url			= site_url('c_comprof/c_am1h0db2/gall1h0db2amd/?id='.$this->url_encryption_helper->encode_url($collDATA));
			redirect($url);
		}
	// -------------------- END : SEARCHING METHOD --------------------

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
									"AMD_CODE", 
									"AMD_DATE", 
									"JOBDESC", 
									"AMD_NOTES", 
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
			$num_rows 		= $this->m_project_amd->get_AllDataC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_project_amd->get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{							

				$AMD_ID 	= $dataI['AMD_ID'];
				$AMD_NUM 	= $dataI['AMD_NUM'];
				$AMD_CODE 	= $dataI['AMD_CODE'];
				$AMD_DATE 	= $dataI['AMD_DATE'];
				$AMD_DATEV	= date('d M Y', strtotime($AMD_DATE));
				$PRJCODE	= $dataI['PRJCODE'];
				$AMD_DESC	= $dataI['AMD_DESC'];
				$AMD_NOTES 	= $dataI['AMD_NOTES'];
				$AMD_MEMO 	= $dataI['AMD_MEMO'];
				$AMD_AMOUNT = $dataI['AMD_AMOUNT'];
				$AMD_STAT	= $dataI['AMD_STAT'];
				$AMD_JOBID	= $dataI['AMD_JOBID'];			// ITEM_CODE
				$JOBDESC	= $dataI['JOBDESC'];			// ITM_NAME
				$STATDESC	= $dataI['STATDESC'];
				$STATCOL	= $dataI['STATCOL'];
				$CREATERNM	= $dataI['CREATERNM'];
				$empName	= cut_text2 ("$CREATERNM", 15);
				
				$CollID 	= "$PRJCODE~$AMD_NUM";
				$secUpd		= site_url('c_comprof/c_am1h0db2/updateAMD/?id='.$this->url_encryption_helper->encode_url($CollID));
				$secPrint	= site_url('c_comprof/c_am1h0db2/p_R1n7/?id='.$this->url_encryption_helper->encode_url($AMD_NUM));
				$CollID		= "AMD~$AMD_NUM~$PRJCODE";
				$secDelURL = base_url().'index.php/__l1y/trashDOC/?id=';
				$delID 		= "$secDelURL~tbl_amd_header~tbl_amd_detail~AMD_NUM~$AMD_NUM~PRJCODE~$PRJCODE";
                                    
				if($AMD_STAT == 1 || $AMD_STAT == 4) 
				{
					$secAction	= 	"<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete'>
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
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' title='Delete' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
								
				if($JOBDESC == '')
					$JOBDESC	= $AMD_DESC;
					
				$output['data'][] = array("$noU.",
										  "<div style='white-space:nowrap'>".$dataI['AMD_CODE']."</div>",
										  $AMD_DATEV,
										  $AMD_JOBID." : ".$JOBDESC,
										  $AMD_NOTES,
										  "<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
										  $secAction);
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function g374llItem_7im() // OK
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_company/m_project_amd/m_project_amd', '', TRUE);
			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			$COLLID		= $_GET['id'];
			$COLLID		= $this->url_encryption_helper->decode_url($COLLID);
			$COLLID 	= $_GET['clDat4'];
			$plitWord	= explode('~', $COLLID);
			$PRJCODE	= $plitWord[0];
			$AMD_CATEG	= $plitWord[1];
			$JOBCODE	= $plitWord[2];
			
			$data['title'] 			= $appName;
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h2_title"] 	= "Daftar Material";
			}
			else
			{
				$data["h2_title"] 	= "List Item";
			}
			
			$data['PRJCODE'] 		= $PRJCODE;	
			$data['AMD_CATEG'] 		= $AMD_CATEG;	
			$data['JPARENT'] 		= $JOBCODE;			
			//$data['countAllItem']	= $this->m_project_amd->count_all_num_rowsAllItem($PRJCODE, $AMD_CATEG, $JOBCODE);
			//$data['vwAllItem'] 		= $this->m_project_amd->viewAllItemMatBudget($PRJCODE, $AMD_CATEG, $JOBCODE)->result();
					
			$this->load->view('v_company/v_project_amd/v_amd_selitem', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function i180dahdd() // OK
	{
		$this->load->model('m_company/m_project_amd/m_project_amd', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE		= $_GET['id'];
			$PRJCODE		= $this->url_encryption_helper->decode_url($PRJCODE);
			$EmpID 			= $this->session->userdata('Emp_ID');
			
			$docPatternPosition 	= 'Especially';	
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			$data['form_action']	= site_url('c_comprof/c_am1h0db2/addamd_process');
			
			// Untuk penomoran secara systemik
			$data['PRJCODE'] 		= $PRJCODE;
			
			// GET MENU DESC
				$mnCode				= 'MN406';
				$data["MenuApp"] 	= 'MN407';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$MenuCode 				= 'MN406';
			$data["MenuCode"] 		= 'MN406';
			$data['viewDocPattern'] = $this->m_project_amd->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN406';
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
	
			$this->load->view('v_company/v_project_amd/v_amd_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function addamd_process() // OK
	{
		$this->load->model('m_company/m_project_amd/m_project_amd', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$this->db->trans_begin();
			
			date_default_timezone_set("Asia/Jakarta");
			
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			$COMPANY_ID		= $this->session->userdata['COMPANY_ID'];
			
			$AMD_NUM 		= $this->input->post('AMD_NUM');
			$AMD_CODE 		= $this->input->post('AMD_CODE');
			$PRJCODE 		= $this->input->post('PRJCODE');
			$AMD_TYPE 		= $this->input->post('AMD_TYPE');
			$AMD_DATE		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('AMD_DATE'))));
			$AMD_CATEG 		= $this->input->post('AMD_CATEG');
			
			// ADA PERUBAHAN PROSEDUR. BY REQUEST PAK WAWAN
			// 1. PILIH ITEM KOMPNEN (SINGLE)
			// 2. PILIH PEKERJAN (MULTIPLE)
			// NOTE : 21.003/MN-IT.NKE /XII/2021 (22 DESEMBER 2021)
			$AMD_JOBPAR		= $this->input->post('ITM_CODEH');		// MENJADI KODE ITEM / ITM_CODE
			$AMD_JOBID		= $this->input->post('ITM_CODEH');		// MENJADI KODE ITEM / ITM_CODE
			$AMD_JOBID		= $this->input->post('ITM_CODEH');		// MENJADI KODE ITEM / ITM_CODE
			$AMD_JOBDESC	= $this->input->post('AMD_JOBDESC');	// ITM_NAME
			
			$AMD_FUNC 		= '';
			$AMD_REFNO 		= '';
			$AMD_REFNOAM	= 0;
			$AMD_DESC		= '';
			$NEW_JOBCODEID	= '';
			$AMD_UNIT		= '';

			if($AMD_CATEG == 'SI' || $AMD_CATEG == 'SINJ')
			{
				$AMD_FUNC 		= $this->input->post('AMD_FUNC');		// Plus / Minus
				$AMD_REFNO 		= $this->input->post('AMD_REFNO');		// SI Number
				$AMD_REFNOAM 	= $this->input->post('AMD_REFNOAM');	// SI Total
				$AMD_DESC 		= $this->input->post('AMD_DESC');		// Job Name if SI New
				$AMD_UNIT 		= $this->input->post('AMD_UNIT');
			}
			
			$AMD_NOTES 		= addslashes($this->input->post('AMD_NOTES'));
			$AMD_AMOUNT		= $this->input->post('AMD_AMOUNT');
			$AMD_STAT		= $this->input->post('AMD_STAT');
			$AMD_CREATER	= $DefEmp_ID;
			$AMD_CREATED	= date('Y-m-d H:i:s');
			$Patt_Year		= date('Y',strtotime($AMD_DATE));
			$Patt_Number	= $this->input->post('Patt_Number');
			
			// START - PEMBENTUKAN GENERATE CODE
				$this->load->model('m_projectlist/m_projectlist', '', TRUE);
				$Pattern_Code	= "XX";
				$MenuCode 		= 'MN406';
				$vwDocPatt		= $this->m_projectlist->getDataDocPat($MenuCode)->result();
				foreach($vwDocPatt as $row) :
					$Pattern_Code = $row->Pattern_Code;
				endforeach;
				
				$PRJCODE 		= $this->input->post('PRJCODE');
				$TRXTIME1		= date('ymdHis');
				$AMD_NUM		= "$Pattern_Code$PRJCODE-$TRXTIME1";
			// END - PEMBENTUKAN GENERATE CODE
			
			$insertAMD 	= array('AMD_NUM' 		=> $AMD_NUM,
								'AMD_CODE'		=> $AMD_CODE,
								'PRJCODE'		=> $PRJCODE,
								'AMD_TYPE'		=> "PRJ",
								'AMD_CATEG'		=> $AMD_CATEG,
								'AMD_FUNC'		=> $AMD_FUNC,
								'AMD_REFNO'		=> $AMD_REFNO,
								'AMD_REFNOAM'	=> $AMD_REFNOAM,
								'AMD_JOBPAR'	=> $AMD_JOBPAR,
								'AMD_JOBID'		=> $AMD_JOBID,
								'AMD_JOBDESC' 	=> $AMD_JOBDESC,
								'AMD_DATE'		=> $AMD_DATE,
								'AMD_DESC'		=> $AMD_DESC,
								'AMD_UNIT'		=> $AMD_UNIT,
								'AMD_NOTES'		=> $AMD_NOTES,
								'AMD_AMOUNT'	=> $AMD_AMOUNT,
								'AMD_STAT'		=> $AMD_STAT,
								'AMD_CREATER'	=> $AMD_CREATER,
								'AMD_CREATED'	=> $AMD_CREATED,
								'Patt_Year'		=> $Patt_Year,
								'Patt_Number'	=> $Patt_Number);
			$this->m_project_amd->addAMD($insertAMD);

			if($AMD_CATEG == 'OTH')
			{
				foreach($_POST['dataIOB'] as $d)
				{
					$d['AMD_NUM']	= $AMD_NUM;
					$ITM_CODE 		= $d['ITM_CODE'];
					$ITM_GROUP 		= "";
					$ITM_UNIT 		= "";
					$s_01 			= "SELECT ITM_GROUP, ITM_UNIT FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' LIMIT 1";
					$r_01 			= $this->db->query($s_01)->result();
					foreach($r_01 as $rw_01):
						$ITM_GROUP 	= $rw_01->ITM_GROUP;
						$ITM_UNIT 	= $rw_01->ITM_UNIT;
					endforeach;
					$d['PRJCODE']	= $PRJCODE;
					$d['ITM_GROUP']	= $ITM_GROUP;
					$d['ITM_UNIT']	= $ITM_UNIT;
					$d['AMD_TOTTSF']= $d['AMD_TOTAL'];

					$this->db->insert('tbl_amd_detail',$d);
				}

				foreach($_POST['dataSUB'] as $ds)
				{
					$ds['AMD_NUM']	= $AMD_NUM;
					$ds['PRJCODE']	= $PRJCODE;
					$ITM_CODE 		= $ds['ITM_CODE'];
					$ITM_GROUP 		= "";
					$ITM_UNIT 		= "";
					$s_01 			= "SELECT ITM_GROUP, ITM_UNIT FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' LIMIT 1";
					$r_01 			= $this->db->query($s_01)->result();
					foreach($r_01 as $rw_01):
						$ITM_GROUP 	= $rw_01->ITM_GROUP;
						$ITM_UNIT 	= $rw_01->ITM_UNIT;
					endforeach;
					$ds['ITM_GROUP']= $ITM_GROUP;
					$ds['ITM_UNIT']	= $ITM_UNIT;

					$this->db->insert('tbl_amd_detail_subs',$ds);
				}
			}
			else
			{
				foreach($_POST['data'] as $d)
				{
					$d['AMD_NUM']	= $AMD_NUM;
					$ITM_CODE 		= $d['ITM_CODE'];
					$ITM_GROUP 		= "";
					$ITM_UNIT 		= "";
					$s_01 			= "SELECT ITM_GROUP, ITM_UNIT FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' LIMIT 1";
					$r_01 			= $this->db->query($s_01)->result();
					foreach($r_01 as $rw_01):
						$ITM_GROUP 	= $rw_01->ITM_GROUP;
						$ITM_UNIT 	= $rw_01->ITM_UNIT;
					endforeach;
					$d['ITM_GROUP']	= $ITM_GROUP;
					$d['ITM_UNIT']	= $ITM_UNIT;
					$d['AMD_TOTTSF']= $d['AMD_TOTAL'];
					$d['PRJCODE']	= $PRJCODE;

					$this->db->insert('tbl_amd_detail',$d);
				}
			}

			if($AMD_STAT == 2)
			{
				// START : UPDATE FINANCIAL DASHBOARD
					$AMD_VAL 	= $AMD_AMOUNT;
					$finDASH 	= array('PRJCODE'	=> $PRJCODE,
										'PERIODE'	=> $AMD_DATE,
										'FVAL'		=> $AMD_VAL,
										'FNAME'		=> "AMD_VAL");										
					$this->m_updash->updFINDASH($finDASH);
				// END : UPDATE FINANCIAL DASHBOARD
			}

			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "AMD_NUM",
										'DOC_CODE' 		=> $AMD_NUM,
										'DOC_STAT' 		=> $AMD_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_amd_header");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $AMD_NUM;
				$MenuCode 		= 'MN406';
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
			
			$url			= site_url('c_comprof/c_am1h0db2/gall1h0db2amd/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function updateAMD() // OK
	{
		$this->load->model('m_company/m_project_amd/m_project_amd', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$COLLDATA	= $_GET['id'];
			$COLLDATA	= $this->url_encryption_helper->decode_url($COLLDATA);
			$EXTRACTCOL	= explode("~", $COLLDATA);
			$PRJCODE	= $EXTRACTCOL[0];
			$AMD_NUM	= $EXTRACTCOL[1];
			
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_comprof/c_am1h0db2/updateamd_process');
			
			// GET MENU DESC
				$mnCode				= 'MN406';
				$data["MenuCode"] 	= 'MN406';
				$data["MenuApp"] 	= 'MN407';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$getAMD				= $this->m_project_amd->get_amd_by_number($AMD_NUM)->row();								
			$data['default']['AMD_NUM'] 	= $getAMD->AMD_NUM;
			$data['default']['AMD_CODE'] 	= $getAMD->AMD_CODE;
			$data['default']['PRJCODE'] 	= $getAMD->PRJCODE;
			$data["PRJCODE"] 				= $PRJCODE;
			$PRJCODE						= $getAMD->PRJCODE;
			$data['default']['AMD_TYPE'] 	= $getAMD->AMD_TYPE;
			$data['default']['AMD_CATEG'] 	= $getAMD->AMD_CATEG;
			$data['default']['AMD_FUNC'] 	= $getAMD->AMD_FUNC;
			$data['default']['AMD_REFNO'] 	= $getAMD->AMD_REFNO;
			$data['default']['AMD_REFNOAM'] = $getAMD->AMD_REFNOAM;
			$data['default']['AMD_JOBPAR'] 	= $getAMD->AMD_JOBPAR;
			$data['default']['AMD_JOBID'] 	= $getAMD->AMD_JOBID;
			$data['default']['AMD_JOBDESC'] = $getAMD->AMD_JOBDESC;
			$data['default']['AMD_DATE'] 	= $getAMD->AMD_DATE;
			$data['default']['AMD_DESC'] 	= $getAMD->AMD_DESC;
			$data['default']['AMD_UNIT'] 	= $getAMD->AMD_UNIT;
			$data['default']['AMD_NOTES'] 	= $getAMD->AMD_NOTES;
			$data['default']['AMD_MEMO'] 	= $getAMD->AMD_MEMO;
			$data['default']['AMD_AMOUNT'] 	= $getAMD->AMD_AMOUNT;
			$data['default']['AMD_STAT'] 	= $getAMD->AMD_STAT;
			$data['default']['Patt_Number'] = $getAMD->Patt_Number;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getAMD->AMD_NUM;
				$MenuCode 		= 'MN406';
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
			
			$this->load->view('v_company/v_project_amd/v_amd_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function updateamd_process() // OK
	{
		$this->load->model('m_company/m_project_amd/m_project_amd', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$this->db->trans_begin();
			
			date_default_timezone_set("Asia/Jakarta");
			
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			$COMPANY_ID		= $this->session->userdata['COMPANY_ID'];
			
			$AMD_NUM 		= $this->input->post('AMD_NUM');
			$AMD_CODE 		= $this->input->post('AMD_CODE');
			$PRJCODE 		= $this->input->post('PRJCODE');
			$AMD_TYPE 		= $this->input->post('AMD_TYPE');
			$AMD_DATE		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('AMD_DATE'))));
			$AMD_CATEG 		= $this->input->post('AMD_CATEG');
			
			// ADA PERUBAHAN PROSEDUR. BY REQUEST PAK WAWAN
			// 1. PILIH ITEM KOMPNEN (SINGLE)
			// 2. PILIH PEKERJAN (MULTIPLE)
			// NOTE : 21.003/MN-IT.NKE /XII/2021 (22 DESEMBER 2021)
			/*$AMD_JOBPAR		= $this->input->post('JOBCODEID');	// PARENT
			$AMD_JOBID		= $this->input->post('JOBCODEID');*/
			$AMD_JOBPAR		= $this->input->post('ITM_CODEH');		// MENJADI KODE ITEM / ITM_CODE
			$AMD_JOBID		= $this->input->post('ITM_CODEH');		// MENJADI KODE ITEM / ITM_CODE
			$AMD_JOBID		= $this->input->post('ITM_CODEH');		// MENJADI KODE ITEM / ITM_CODE
			$AMD_JOBDESC	= $this->input->post('AMD_JOBDESC');	// ITM_NAME

			$AMD_FUNC 		= "";		// Plus / Minus
			$AMD_REFNO 		= "";		// SI Number
			$AMD_REFNOAM 	= 0;		// SI Total
			$AMD_DESC 		= "";		// Job Name if SI New
			$AMD_UNIT 		= "";
			if($AMD_CATEG == 'SI' || $AMD_CATEG == 'SINJ')
			{
				$AMD_FUNC 		= $this->input->post('AMD_FUNC');		// Plus / Minus
				$AMD_REFNO 		= $this->input->post('AMD_REFNO');		// SI Number
				$AMD_REFNOAM 	= $this->input->post('AMD_REFNOAM');	// SI Total
				$AMD_DESC 		= $this->input->post('AMD_DESC');		// Job Name if SI New
				$AMD_UNIT 		= $this->input->post('AMD_UNIT');
			}
			
			$AMD_NOTES 		= addslashes($this->input->post('AMD_NOTES'));
			$AMD_AMOUNT		= $this->input->post('AMD_AMOUNT');
			$AMD_STAT		= $this->input->post('AMD_STAT');
			$AMD_CREATER	= $DefEmp_ID;
			$Patt_Year		= date('Y',strtotime($AMD_DATE));
			
			$JOBPARENT1	= '';
			$sqlLEV		= "SELECT JOBPARENT, JOBLEV FROM tbl_joblist WHERE JOBCODEID = '$AMD_JOBID'";
			$resLEV 	= $this->db->query($sqlLEV)->result();
			foreach($resLEV as $rowLEV) :
				$JOBPARENT1	= $rowLEV->JOBPARENT;
				$JOBLEV		= $rowLEV->JOBLEV;
			endforeach;

			$updateAMD 	= array('AMD_CODE'		=> $AMD_CODE,
								'PRJCODE'		=> $PRJCODE,
								'AMD_TYPE'		=> $AMD_TYPE,
								'AMD_CATEG'		=> $AMD_CATEG,
								'AMD_FUNC'		=> $AMD_FUNC,
								'AMD_REFNO'		=> $AMD_REFNO,
								'AMD_REFNOAM'	=> $AMD_REFNOAM,
								'AMD_JOBPAR'	=> $AMD_JOBPAR,
								'AMD_JOBID'		=> $AMD_JOBID,
								'AMD_JOBDESC' 	=> $AMD_JOBDESC,
								'AMD_DATE'		=> $AMD_DATE,
								'AMD_DESC'		=> $AMD_DESC,
								'AMD_UNIT'		=> $AMD_UNIT,
								'AMD_NOTES'		=> $AMD_NOTES,
								'AMD_AMOUNT'	=> $AMD_AMOUNT,
								'AMD_STAT'		=> $AMD_STAT);
			$this->m_project_amd->updateAMD($AMD_NUM, $updateAMD);
			
			$this->m_project_amd->deleteAMDDet($AMD_NUM);

			if($AMD_CATEG == 'OTH')
			{
				$this->m_project_amd->deleteAMDDetSUBS($AMD_NUM);
				
				foreach($_POST['dataIOB'] as $d)
				{
					$d['AMD_NUM']	= $AMD_NUM;
					$ITM_CODE 		= $d['ITM_CODE'];
					$ITM_GROUP 		= "";
					$ITM_UNIT 		= "";
					$s_01 			= "SELECT ITM_GROUP, ITM_UNIT FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' LIMIT 1";
					$r_01 			= $this->db->query($s_01)->result();
					foreach($r_01 as $rw_01):
						$ITM_GROUP 	= $rw_01->ITM_GROUP;
						$ITM_UNIT 	= $rw_01->ITM_UNIT;
					endforeach;
					$d['PRJCODE']	= $PRJCODE;
					$d['ITM_GROUP']	= $ITM_GROUP;
					$d['ITM_UNIT']	= $ITM_UNIT;
					$d['AMD_TOTTSF']= $d['AMD_TOTAL'];

					$this->db->insert('tbl_amd_detail',$d);
				}

				foreach($_POST['dataSUB'] as $ds)
				{
					$ds['AMD_NUM']	= $AMD_NUM;
					$ds['PRJCODE']	= $PRJCODE;
					$ITM_CODE 		= $ds['ITM_CODE'];
					$ITM_GROUP 		= "";
					$ITM_UNIT 		= "";
					$s_01 			= "SELECT ITM_GROUP, ITM_UNIT FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' LIMIT 1";
					$r_01 			= $this->db->query($s_01)->result();
					foreach($r_01 as $rw_01):
						$ITM_GROUP 	= $rw_01->ITM_GROUP;
						$ITM_UNIT 	= $rw_01->ITM_UNIT;
					endforeach;
					$ds['ITM_GROUP']= $ITM_GROUP;
					$ds['ITM_UNIT']	= $ITM_UNIT;
					$d['PRJCODE']	= $PRJCODE;

					$this->db->insert('tbl_amd_detail_subs',$ds);
				}
			}
			else
			{
				foreach($_POST['data'] as $d)
				{
					$d['AMD_NUM']	= $AMD_NUM;
					$ITM_CODE 		= $d['ITM_CODE'];
					$ITM_GROUP 		= "";
					$ITM_UNIT 		= "";
					$s_01 			= "SELECT ITM_GROUP, ITM_UNIT FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' LIMIT 1";
					$r_01 			= $this->db->query($s_01)->result();
					foreach($r_01 as $rw_01):
						$ITM_GROUP 	= $rw_01->ITM_GROUP;
						$ITM_UNIT 	= $rw_01->ITM_UNIT;
					endforeach;
					$d['ITM_GROUP']	= $ITM_GROUP;
					$d['ITM_UNIT']	= $ITM_UNIT;
					$d['PRJCODE']	= $PRJCODE;

					$this->db->insert('tbl_amd_detail',$d);
				}
			}

			if($AMD_STAT == 2)
			{
				// START : UPDATE FINANCIAL DASHBOARD
					$AMD_VAL 	= $AMD_AMOUNT;
					$finDASH 	= array('PRJCODE'	=> $PRJCODE,
										'PERIODE'	=> $AMD_DATE,
										'FVAL'		=> $AMD_VAL,
										'FNAME'		=> "AMD_VAL");										
					$this->m_updash->updFINDASH($finDASH);
				// END : UPDATE FINANCIAL DASHBOARD
			}
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "AMD_NUM",
										'DOC_CODE' 		=> $AMD_NUM,
										'DOC_STAT' 		=> $AMD_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_amd_header");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $AMD_NUM;
				$MenuCode 		= 'MN406';
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
			
			$url			= site_url('c_comprof/c_am1h0db2/gall1h0db2amd/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
 	function i180dah() // OK
	{
		$this->load->model('m_company/m_project_amd/m_project_amd', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;
		
		$url			= site_url('c_comprof/c_am1h0db2/pR7_l157_14x/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function pR7_l157_14x() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$LangID 	= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN406';
				$data["MenuCode"] 	= 'MN407';
				$data["MenuApp"] 	= 'MN407';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;

			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN40';
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_comprof/c_am1h0db2/i1dah80Idx/?id=";
			
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
	
	function i1dah80Idx() // OK
	{
		$this->load->model('m_company/m_project_amd/m_project_amd', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		$LangID 	= $this->session->userdata['LangID'];
			
		// GET MENU DESC
			$mnCode				= 'MN407';
			$data["MenuCode"] 	= 'MN407';
			$data["MenuApp"] 	= 'MN407';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			// -------------------- START : SEARCHING METHOD --------------------
				
				$collDATA		= $_GET['id'];
				$EXP_COLLD1		= $this->url_encryption_helper->decode_url($collDATA);	
				$EXP_COLLD		= explode('~', $EXP_COLLD1);	
				$C_COLLD1		= count($EXP_COLLD);
				
				if($C_COLLD1 > 1)
				{
					$EXP_COLLD1	= str_replace('~', '', $EXP_COLLD1);
					$key		= $EXP_COLLD[0];
					$PRJCODE	= $EXP_COLLD[1];
					$mxLS		= $EXP_COLLD[2];
					$end		= $EXP_COLLD[3];
					$start		= 0;
				}
				else
				{
					$key		= '';
					$PRJCODE	= $EXP_COLLD1;
					$start		= 0;
					$end		= 50;
				}
				$data['backURL'] 	= site_url('c_comprof/c_am1h0db2/pR7_l157_14x/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
				$data["url_search"] = site_url('c_comprof/c_am1h0db2/f4n7_5rcH1nB/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
				//$num_rows 			= $this->m_project_amd->count_all_AMDInb($PRJCODE, $key, $DefEmp_ID);
				//$data["cData"] 		= $num_rows;	 
				//$data['vData']		= $this->m_project_amd->get_all_AMDInb($PRJCODE, $start, $end, $key, $DefEmp_ID)->result();
			// -------------------- END : SEARCHING METHOD --------------------
			
			$data['title'] 		= $appName;	
			$data["PRJCODE"] 	= $PRJCODE;
			
			/*$getAMDC				= $this->m_project_amd->get_amd_by_numberLC($DefEmp_ID);
			$PRJCODE				= '';
			if($getAMDC > 1)
			{
				$getAMD				= $this->m_project_amd->get_amd_by_numberL($DefEmp_ID)->row();
				$PRJCODE 			= $getAMD->PRJCODE;
			}*/
						
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN407';
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
			
			$this->load->view('v_company/v_project_amd/v_inb_amd_list', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	// -------------------- START : SEARCHING METHOD --------------------
		function f4n7_5rcH1nB()
		{
			$gEn5rcH		= $_POST['gEn5rcH'];
			$mxLS			= $_POST['maxLimDf'];
			$mxLF			= $_POST['maxLimit'];
			$PRJCODE		= $_GET['id'];
			$PRJCODE		= $this->url_encryption_helper->decode_url($PRJCODE);
			$collDATA		= "$gEn5rcH~$PRJCODE~$mxLS~$mxLF";
			$url			= site_url('c_comprof/c_am1h0db2/i1dah80Idx/?id='.$this->url_encryption_helper->encode_url($collDATA));
			redirect($url);
		}
	// -------------------- END : SEARCHING METHOD --------------------

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
									"AMD_CODE", 
									"AMD_DATE", 
									"JOBDESC", 
									"AMD_NOTES", 
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
			$num_rows 		= $this->m_project_amd->get_AllDataC_1n2($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_project_amd->get_AllDataL_1n2($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{							

				$AMD_ID 	= $dataI['AMD_ID'];
				$AMD_NUM 	= $dataI['AMD_NUM'];
				$AMD_CODE 	= $dataI['AMD_CODE'];
				$AMD_DATE 	= $dataI['AMD_DATE'];
				$AMD_DATEV	= date('d M Y', strtotime($AMD_DATE));
				$PRJCODE	= $dataI['PRJCODE'];
				$AMD_DESC	= $dataI['AMD_DESC'];
				$AMD_NOTES 	= $dataI['AMD_NOTES'];
				$AMD_MEMO 	= $dataI['AMD_MEMO'];
				$AMD_AMOUNT = $dataI['AMD_AMOUNT'];
				$AMD_STAT	= $dataI['AMD_STAT'];
				$JOBDESC	= $dataI['JOBDESC'];
				$STATDESC	= $dataI['STATDESC'];
				$STATCOL	= $dataI['STATCOL'];
				$CREATERNM	= $dataI['CREATERNM'];
				$empName	= cut_text2 ("$CREATERNM", 15);
				
				$CollID 	= "$PRJCODE~$AMD_NUM";
				$secUpd		= site_url('c_comprof/c_am1h0db2/update_inb/?id='.$this->url_encryption_helper->encode_url($CollID));
				$secPrint	= site_url('c_comprof/c_am1h0db2/p_R1n7/?id='.$this->url_encryption_helper->encode_url($AMD_NUM));
				$CollID		= "AMD~$AMD_NUM~$PRJCODE";
				$secDel_DOC = base_url().'index.php/__l1y/trash_DOC/?id='.$CollID;
				
				$secAction	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
							   <label style='white-space:nowrap'>
							   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
									<i class='glyphicon glyphicon-pencil'></i>
							   </a>
								<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
									<i class='glyphicon glyphicon-print'></i>
								</a>
								<a href='#' onClick='deleteDOC('".$secDel_DOC."')' title='Delete file' class='btn btn-danger btn-xs' disabled='disabled'>
									<i class='fa fa-trash-o'></i>
								</a>
								</label>";
								
				$output['data'][] = array("$noU.",
										  "<div style='white-space:nowrap'>".$dataI['AMD_CODE']."</div>",
										  $AMD_DATEV,
										  $JOBDESC,
										  $AMD_NOTES,
										  "<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
										  $secAction);
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function update_inb() // OK
	{
		$this->load->model('m_company/m_project_amd/m_project_amd', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN407';
			$data["MenuApp"] 	= 'MN407';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		$EmpID 		= $this->session->userdata('Emp_ID');
		if ($this->session->userdata('login') == TRUE)
		{
			$COLLDATA	= $_GET['id'];
			$COLLDATA	= $this->url_encryption_helper->decode_url($COLLDATA);
			$EXTRACTCOL	= explode("~", $COLLDATA);
			$PRJCODE	= $EXTRACTCOL[0];
			$AMD_NUM	= $EXTRACTCOL[1];
			
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_comprof/c_am1h0db2/updateamd_process_inb');
			
			$MenuCode 			= 'MN407';
			$data["MenuCode"] 	= 'MN407';
			
			$getAMD				= $this->m_project_amd->get_amd_by_number($AMD_NUM)->row();	
			$data['default']['AMD_NUM'] 	= $getAMD->AMD_NUM;
			$data['default']['AMD_CODE'] 	= $getAMD->AMD_CODE;
			$data['default']['PRJCODE'] 	= $getAMD->PRJCODE;
			$data['PRJCODE'] 				= $getAMD->PRJCODE;
			$PRJCODE						= $getAMD->PRJCODE;
			$data['default']['AMD_TYPE'] 	= $getAMD->AMD_TYPE;
			$data['default']['AMD_CATEG'] 	= $getAMD->AMD_CATEG;
			$data['default']['AMD_FUNC'] 	= $getAMD->AMD_FUNC;
			$data['default']['AMD_REFNO'] 	= $getAMD->AMD_REFNO;
			$data['default']['AMD_REFNOAM'] = $getAMD->AMD_REFNOAM;
			$data['default']['AMD_JOBPAR'] 	= $getAMD->AMD_JOBPAR;
			$data['default']['AMD_JOBID'] 	= $getAMD->AMD_JOBID;
			$data['default']['AMD_JOBDESC'] = $getAMD->AMD_JOBDESC;
			$data['default']['AMD_DATE'] 	= $getAMD->AMD_DATE;
			$data['default']['AMD_DESC'] 	= $getAMD->AMD_DESC;
			$data['default']['AMD_UNIT'] 	= $getAMD->AMD_UNIT;
			$data['default']['AMD_NOTES'] 	= $getAMD->AMD_NOTES;
			$data['default']['AMD_MEMO'] 	= $getAMD->AMD_MEMO;
			$data['default']['AMD_AMOUNT'] 	= $getAMD->AMD_AMOUNT;
			$data['default']['AMD_STAT'] 	= $getAMD->AMD_STAT;
			$data['default']['Patt_Number'] = $getAMD->Patt_Number;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getAMD->AMD_NUM;
				$MenuCode 		= 'MN407';
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
			
			$this->load->view('v_company/v_project_amd/v_inb_amd_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function updateamd_process_inb() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$this->db->trans_begin();
			
			date_default_timezone_set("Asia/Jakarta");
			
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			$COMPANY_ID		= $this->session->userdata['COMPANY_ID'];
			
			$AMD_NUM 		= $this->input->post('AMD_NUM');
			$AMD_CODE 		= $this->input->post('AMD_CODE');
			$PRJCODE 		= $this->input->post('PRJCODE');
			$PRJCODE_HO		= $this->data['PRJCODE_HO'];
			$AMD_TYPE 		= $this->input->post('AMD_TYPE');
			$AMD_CATEG 		= $this->input->post('AMD_CATEG');
			$AMD_JOBPAR		= $this->input->post('JOBCODEID');	// PARENT
			$JOBPARENT1 	= $this->input->post('JOBCODEID');	// PARENT
			$JOBPARDESC		= $this->input->post('JOBPARDESC');
			$JOBLEV			= $this->input->post('JOBPARLEV');
			
			$AMD_FUNC 		= '';
			$AMD_REFNO 		= '';
			$AMD_REFNOAM	= 0;
			$AMD_DESC		= '';
			$NEW_JOBCODEID	= '';
			$AMD_UNIT		= '';
			if($AMD_CATEG == 'SI' || $AMD_CATEG == 'SINJ')
			{
				$AMD_FUNC 		= $this->input->post('AMD_FUNC');		// Plus / Minus
				$AMD_REFNO 		= $this->input->post('AMD_REFNO');		// SI Number
				$AMD_REFNOAM 	= $this->input->post('AMD_REFNOAM');	// SI Total
				$AMD_DESC 		= $this->input->post('AMD_DESC');		// Job Name if SI New
				$AMD_UNIT 		= $this->input->post('AMD_UNIT');
			}
			
			$AMD_DATE		= date('Y-m-d',strtotime($this->input->post('AMD_DATE')));
			$AMD_DESC 		= $this->input->post('AMD_DESC');
			$AMD_UNIT 		= $this->input->post('AMD_UNIT');
			$AMD_NOTES 		= addslashes($this->input->post('AMD_NOTES'));
			$AMD_MEMO 		= $this->input->post('AMD_MEMO');
			$AMD_AMOUNT		= $this->input->post('AMD_AMOUNT');
			$AMD_STAT		= $this->input->post('AMD_STAT');
			$AH_ISLAST		= $this->input->post('IS_LAST');
			$AMD_CREATER	= $DefEmp_ID;
			$AMD_CREATED	= date('Y-m-d H:i:s');
			$AH_ISLAST		= $this->input->post('IS_LAST');			
			$AMD_APPROVED	= date('Y-m-d H:i:s');
				
			$AH_CODE		= $AMD_NUM;
			$AH_APPLEV		= $this->input->post('APP_LEVEL');
			$AH_APPROVER	= $DefEmp_ID;
			$AH_APPROVED	= $AMD_APPROVED;
			$AH_NOTES		= addslashes($this->input->post('AMD_NOTES'));
			$AH_ISLAST		= $this->input->post('IS_LAST');
			
			// UPDATE JOBDETAIL ITEM
			if($AMD_STAT == 3)
			{
				$updateAMD 		= array('AMD_STAT'	=> 7);			
				$this->m_project_amd->updateAMD($AMD_NUM, $updateAMD);
				
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "AMD_NUM",
											'DOC_CODE' 		=> $AMD_NUM,
											'DOC_STAT' 		=> 7,
											'PRJCODE' 		=> $PRJCODE,
											//'CREATERNM'		=> "",
											'TBLNAME'		=> "tbl_amd_header");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS

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

				if($AH_ISLAST == 1)
				{
					$Pattern_Length	= 2;
					$AH_CODE		= $AMD_NUM;
					$AH_APPLEV		= $this->input->post('APP_LEVEL');
					$AH_APPROVER	= $DefEmp_ID;
					$AH_APPROVED	= $AMD_APPROVED;
					$AH_NOTES		= addslashes($this->input->post('AMD_NOTES'));
					$AH_ISLAST		= $this->input->post('IS_LAST');

					$updateAMD 		= array('AMD_MEMO'	=> $AMD_MEMO,
										'AMD_STAT'		=> $AMD_STAT);
					$this->m_project_amd->updateAMD($AMD_NUM, $updateAMD);

					// UPDATE OTHER ORD_ID
						if($AMD_CATEG == 'OB') // Over Budget. Not added new item, update qty only
						{
							foreach($_POST['data'] as $d)
							{
								$JOBCODEID	= $d['JOBCODEID'];
								$ITM_CODE	= $d['ITM_CODE'];
								$PRJCODE	= $PRJCODE;
								$ADD_VOLM	= $d['AMD_VOLM'];
								$ADD_PRICE	= $d['AMD_PRICE'];
								$AMD_CLASS	= $d['AMD_CLASS'];

								$ADD_JOBCOST= $ADD_VOLM * $ADD_PRICE;

								// UPDATE TO WBS / JOBLIST AND JOBDEATIL. LAST CHECKED : 21-10-29
									$paramWBS 	= array('ADD_VOLM' 		=> $ADD_VOLM,
														'ADD_PRICE'		=> $ADD_PRICE,
														'ADD_JOBCOST'	=> $ADD_JOBCOST,
														'JOBPARENT'		=> $JOBPARENT1,
														'JOBCODEID'		=> $JOBCODEID,
														'ITM_CODE'		=> $ITM_CODE,
														'AMD_CLASS'		=> $AMD_CLASS,
														'PRJCODE'		=> $PRJCODE);
									$this->m_project_amd->updateWBS($paramWBS);
							}
						}
						elseif($AMD_CATEG == 'NB') // Not Budgeting
						{
							$theLastD 		= 0;
							foreach($_POST['data'] as $d)
							{
								$theLastD 	= $theLastD+1;
								$JOBCODEID	= $d['JOBCODEID'];
								$JOBPARENT	= $d['JOBPARENT'];

								$maxC		= 0;
								$sLastChld	= "SELECT IF(ISNULL(MAX(ORD_ID)), 0, MAX(ORD_ID)) as maxChild FROM tbl_joblist_detail
												WHERE JOBCODEID = '$JOBPARENT' AND PRJCODE = '$PRJCODE'";
								$rLastChld 	= $this->db->query($sLastChld)->result();
								foreach($rLastChld as $lastChld) :
									$maxC 	= $lastChld->maxChild;
								endforeach;
								$nxChld 	= $maxC+1;

								$NEWLEV 	= 0;
								$sLevHead 	= "SELECT IS_LEVEL FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBPARENT' AND PRJCODE = '$PRJCODE'";
								$rLevHead	= $this->db->query($sLevHead)->result();
								foreach($rLevHead as $rwLevHead):
									$NEWLEV = $rwLevHead->IS_LEVEL;
								endforeach;
								$JOBLEV 	= $NEWLEV+1;
								
								// UNTUK AMANDEMEN KATEGORI NB DAN SINJ, KODE BUDGET ITEM/KOMPONEN SUDAH DIBENTUK SAAT INPUT DOKUMEN AMANDEMEN
								// SEHINGGA TIDAK PERLU LAGI MEMBUAT PROSEDUR PENOMORAN BUDGET
									/*$nol		= "";
									$len 		= strlen($nxChld);
									if($Pattern_Length==2)
									{
										if($len==1) $nol="0";
									}
									else
									{
										$nol	= "";
									}
									$lastNumb = $nol.$nxChld;*/

								// CEK SAME ORD_ID
									$nxX 	= $nxChld;
									$sORID 	= "SELECT JOBCODEID FROM tbl_joblist_detail WHERE ORD_ID = $nxChld AND PRJCODE = '$PRJCODE'";
									$rORID	= $this->db->query($sORID)->result();
									foreach($rORID as $rwORID):
										$nxX 	= $nxX+1;
									endforeach;

									$supChld	= "UPDATE tbl_joblist_detail SET ORD_ID = ORD_ID+1 WHERE ORD_ID > $nxChld
													AND PRJCODE = '$PRJCODE'";
									$this->db->query($supChld);

									$sUPD 	= "UPDATE tbl_joblist_detail SET ORD_ID = $nxX WHERE JOBCODEID = '$JOBCODEID'
												AND PRJCODE = '$PRJCODE'";
									$this->db->query($sUPD);
								
									$dt['ORD_ID']		= $nxX;
									$dt['JOBCODEDET']	= $JOBCODEID;
									$dt['JOBCODEID']	= $JOBCODEID;
									$dt['JOBPARENT']	= $JOBPARENT;
									$dt['JOBCOD2']		= $AMD_NUM;
									$dt['PRJCODE']		= $PRJCODE;
									$dt['PRJCODE_HO']	= $PRJCODE_HO;
									$dt['PRJPERIOD']	= $PRJCODE;
									$dt['PRJPERIOD_P']	= $PRJCODE_HO;
									$dt['JOBDESC']		= $d['JOBDESC'];
									$dt['ITM_GROUP']	= $d['ITM_GROUP'];
									$dt['GROUP_CATEG']	= $d['ITM_GROUP'];
									$dt['ITM_CODE']		= $d['ITM_CODE']; 
									$dt['ITM_UNIT']		= $d['ITM_UNIT'];
									$ADD_VOLM			= $d['AMD_VOLM'];
									$dt['ITM_PRICE']	= $d['AMD_PRICE'];
									$dt['ITM_LASTP']	= $d['AMD_PRICE'];
									$dt['ADD_VOLM']		= $d['AMD_VOLM'];
									$dt['ADD_PRICE']	= $d['AMD_PRICE'];
									$ADD_PRICE			= $d['AMD_PRICE'];					
									$JOBVOLM			= $d['AMD_VOLM'];
									$JOBCOST			= $ADD_VOLM * $ADD_PRICE;
									$dt['ADD_JOBCOST']	= $JOBCOST;
									$dt['IS_LEVEL']		= $JOBLEV;
									$dt['ISLAST']		= 1;
									$dt['Patt_Number']	= $theLastD;
								
								// ADDING NEW JOB
									//$this->db->insert('tbl_boqlist',$bq);
									//$this->db->insert('tbl_joblist',$e);
									$this->db->insert('tbl_joblist_detail',$dt);

								// UPDATE WBS	
									$JOBCODEID	= $d['JOBCODEID'];
									$ITM_CODE	= $d['ITM_CODE'];
									$PRJCODE	= $PRJCODE;
									$ADD_VOLM	= $d['AMD_VOLM'];
									$ADD_PRICE	= $d['AMD_PRICE'];

									// UPDATE TO WBS / JOBLIST AND JOBDEATIL. LAST CHECKED : 21-10-29
										$paramWBS 	= array('ADD_VOLM' 		=> $ADD_VOLM,
															'ADD_PRICE'		=> $ADD_PRICE,
															'ADD_JOBCOST'	=> $JOBCOST,
															'JOBPARENT'		=> $JOBPARENT,
															'JOBCODEID'		=> $JOBCODEID,
															'ITM_CODE'		=> $ITM_CODE,
															'PRJCODE'		=> $PRJCODE);
										$this->m_project_amd->updateWBSH($paramWBS);

								// UPDATE ORD_ID
									$supChld	= "UPDATE tbl_joblist_detail SET ORD_ID = ORD_ID+1 WHERE ORD_ID > $nxChld AND PRJCODE = '$PRJCODE'";
									$this->db->query($supChld);
							}
						}
						elseif($AMD_CATEG == 'SI') // Site Instruction. Fure Update
						{
							// Dalam kondisi jika hanya perubahan atau penambahan item pada pekerjaan yang sudah ada.
							if($AMD_FUNC == 'PLUS')
							{
								foreach($_POST['data'] as $d)
								{
									$JOBCODEID	= $d['JOBCODEID'];
									$ITM_CODE	= $d['ITM_CODE'];
									$PRJCODE	= $PRJCODE;
									$ADD_VOLM	= $d['AMD_VOLM'];
									$ADD_PRICE	= $d['AMD_PRICE'];
									$AMD_CLASS	= $d['AMD_CLASS'];
									$ADD_JOBCOST= $ADD_VOLM * $ADD_PRICE;
									
									// UPDATE TO WBS / JOBLIST AND JOBDEATIL
									$paramWBS 	= array('ADD_VOLM' 		=> $ADD_VOLM,
														'ADD_PRICE'		=> $ADD_PRICE,
														'ADD_JOBCOST'	=> $ADD_JOBCOST,
														'JOBPARENT'		=> $AMD_JOBPAR,
														'JOBCODEID'		=> $JOBCODEID,
														'ITM_CODE'		=> $ITM_CODE,
														'AMD_CLASS'		=> $AMD_CLASS,
														'PRJCODE'		=> $PRJCODE);
									$this->m_project_amd->updateWBS($paramWBS);
						
									//$this->m_project_amd->updateWBSD($paramWBS);
								}
							}
							elseif($AMD_FUNC == 'MIN')
							{
								foreach($_POST['data'] as $d)
								{
									$JOBCODEID	= $d['JOBCODEID'];
									$ITM_CODE	= $d['ITM_CODE'];
									$PRJCODE	= $PRJCODE;
									$ADD_VOLM	= $d['AMD_VOLM'];
									$ADD_PRICE	= $d['AMD_PRICE'];
									$AMD_CLASS	= $d['AMD_CLASS'];
									$ADD_JOBCOST= $ADD_VOLM * $ADD_PRICE;
									
									// UPDATE TO WBS / JOBLIST AND JOBDEATIL
									$paramWBS 	= array('ADD_VOLM' 		=> $ADD_VOLM,
														'ADD_PRICE'		=> $ADD_PRICE,
														'ADD_JOBCOST'	=> $ADD_JOBCOST,
														'JOBPARENT'		=> $AMD_JOBPAR,
														'JOBCODEID'		=> $JOBCODEID,
														'ITM_CODE'		=> $ITM_CODE,
														'AMD_CLASS'		=> $AMD_CLASS,
														'PRJCODE'		=> $PRJCODE);
									$this->m_project_amd->updateWBSM($paramWBS);
						
									//$this->m_project_amd->updateWBSDM($paramWBS);
								}
							}
							
							// UPDATE SI
								$paramSIH 	= array('SI_AMANDNO'	=> $AMD_NUM,
													'SI_AMANDVAL'	=> $ADD_JOBCOST,
													'SI_AMANDSTAT'	=> $AMD_STAT,
													'SI_CODE'		=> $AMD_REFNO,
													'PRJCODE'		=> $PRJCODE);
								$this->m_project_amd->updateSIH($paramSIH);
						}
						elseif($AMD_CATEG == 'SINJ') // Site Instruction. Adding new job. Add new item
						{
							foreach($_POST['data'] as $d)
							{
								$JOBCODEID	= $d['JOBCODEID'];
								$JOBPARENT	= $d['JOBPARENT'];

								$maxC		= 0;
								$sLastChld	= "SELECT IF(ISNULL(MAX(ORD_ID)), 0, MAX(ORD_ID)) as maxChild FROM tbl_joblist_detail
												WHERE JOBCODEID = '$JOBPARENT' AND PRJCODE = '$PRJCODE'";
								$rLastChld 	= $this->db->query($sLastChld)->result();
								foreach($rLastChld as $lastChld) :
									$maxC 	= $lastChld->maxChild;
								endforeach;
								$nxChld 	= $maxC+1;

								$NEWLEV 	= 0;
								$sLevHead 	= "SELECT IS_LEVEL FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBPARENT' AND PRJCODE = '$PRJCODE'";
								$rLevHead	= $this->db->query($sLevHead)->result();
								foreach($rLevHead as $rwLevHead):
									$NEWLEV = $rwLevHead->IS_LEVEL;
								endforeach;
								
								// UNTUK AMANDEMEN KATEGORI NB DAN SINJ, KODE BUDGET ITEM/KOMPONEN SUDAH DIBENTUK SAAT INPUT DOKUMEN AMANDEMEN
								// SEHINGGA TIDAK PERLU LAGI MEMBUAT PROSEDUR PENOMORAN BUDGET
									/*$nol		= "";
									$len 		= strlen($nxChld);
									if($Pattern_Length==2)
									{
										if($len==1) $nol="0";
									}
									else
									{
										$nol	= "";
									}
									$lastNumb = $nol.$nxChld;*/

								// CEK SAME ORD_ID
									$nxX 	= $nxChld;
									$sORID 	= "SELECT JOBCODEID FROM tbl_joblist_detail WHERE ORD_ID = $nxChld AND PRJCODE = '$PRJCODE'";
									$rORID	= $this->db->query($sORID)->result();
									foreach($rORID as $rwORID):
										$nxX 	= $nxX+1;
									endforeach;

									$supChld	= "UPDATE tbl_joblist_detail SET ORD_ID = ORD_ID+1 WHERE ORD_ID > $nxChld
													AND PRJCODE = '$PRJCODE'";
									$this->db->query($supChld);

									$sUPD 	= "UPDATE tbl_joblist_detail SET ORD_ID = $nxX WHERE JOBCODEID = '$JOBCODEID'
												AND PRJCODE = '$PRJCODE'";
									$this->db->query($sUPD);

								// START : ADD TO BOQ
									$bq['ORD_ID']		= $nxX;
									$bq['JOBCODEID']	= $JOBCODEID;
									$bq['JOBCODEIDV']	= $JOBCODEID;
									$bq['JOBPARENT']	= $JOBPARENT;
									$bq['JOBCOD2']		= $AMD_NUM;
									$bq['PRJCODE']		= $PRJCODE;
									$bq['PRJCODE_HO']	= $PRJCODE_HO;
									$bq['PRJPERIOD']	= $PRJCODE;
									$bq['PRJPERIOD_P']	= $PRJCODE_HO;
									$bq['ITM_CODE']		= $d['ITM_CODE'];
									$bq['JOBDESC']		= $d['JOBDESC'];
									$bq['JOBGRP']		= $d['ITM_GROUP'];
									$bq['JOBTYPE']		= "SINJ";
									$bq['JOBUNIT']		= $d['ITM_UNIT'];
									$bq['JOBLEV']		= $NEWLEV;
									$bq['JOBVOLM']		= $d['AMD_VOLM'];
									$bq['PRICE']		= $d['AMD_PRICE'];
									$bq['JOBCOST']		= $d['AMD_PRICE'];							
									$JOBVOLM			= $d['AMD_VOLM'];
									$PRICE				= $d['AMD_PRICE'];
									$JOBCOST			= $JOBVOLM * $PRICE;
									$bq['JOBCOST']		= $JOBCOST;
									$bq['Patt_Number']	= $nxChld;
									$bq['ISHEADER']		= 0;
									//$bq['ITM_NEED']	= 1;
									$bq['ISLAST']		= 1;

									$this->db->insert('tbl_boqlist',$bq);
								// END : ADD TO BOQ

								// START : ADD TO JOBLIST
									$nxChld				= $nxChld+1;
									$e['ORD_ID']		= $nxChld;
									$e['JOBPARENT']		= $JOBPARENT;
									$e['JOBCODEID']		= $JOBCODEID;
									$e['JOBCODEIDV']	= $JOBCODEID;
									$e['JOBCOD2']		= $AMD_NUM;
									$e['PRJCODE']		= $PRJCODE;
									$e['PRJCODE_HO']	= $PRJCODE_HO;
									$e['PRJPERIOD']		= $PRJCODE;
									$e['PRJPERIOD_P']	= $PRJCODE_HO;
									$e['ITM_CODE']		= $d['ITM_CODE'];
									$e['JOBDESC']		= $d['JOBDESC'];
									$e['JOBGRP']		= $d['ITM_GROUP'];
									$e['JOBTYPE']		= "SINJ";
									$e['JOBUNIT']		= $d['ITM_UNIT'];
									$e['JOBLEV']		= $NEWLEV;
									$e['JOBVOLM']		= $d['AMD_VOLM'];
									$e['PRICE']			= $d['AMD_PRICE'];
									$e['JOBCOST']		= $d['AMD_PRICE'];							
									$JOBVOLM			= $d['AMD_VOLM'];
									$PRICE				= $d['AMD_PRICE'];
									$JOBCOST			= $JOBVOLM * $PRICE;
									$e['JOBCOST']		= $JOBCOST;
									$e['Patt_Number']	= $nxChld;
									$e['ISHEADER']		= 0;
									//$e['ITM_NEED']	= 1;
									$e['ISLAST']		= 1;

									$this->db->insert('tbl_joblist',$e);
								// END : ADD TO JOBLIST

								// START : ADD TO JOBLIST DETAIL
									$dt['ORD_ID']		= $nxChld;
									$dt['JOBCODEDET']	= $JOBCODEID;
									$dt['JOBCODEID']	= $JOBCODEID;
									$dt['JOBPARENT']	= $JOBPARENT;
									$dt['JOBCOD2']		= $AMD_NUM;
									$dt['PRJCODE']		= $PRJCODE;
									$dt['PRJCODE_HO']	= $PRJCODE_HO;
									$dt['PRJPERIOD']	= $PRJCODE;
									$dt['PRJPERIOD_P']	= $PRJCODE_HO;
									$dt['JOBDESC']		= $d['JOBDESC'];
									$dt['ITM_GROUP']	= $d['ITM_GROUP'];
									$dt['GROUP_CATEG']	= $d['ITM_GROUP'];
									$dt['ITM_CODE']		= $d['ITM_CODE'];
									$dt['ITM_UNIT']		= $d['ITM_UNIT'];
									$dt['ITM_VOLM']		= $d['AMD_VOLM'];
									$ADD_VOLM			= $d['AMD_VOLM'];
									$dt['ITM_PRICE']	= $d['AMD_PRICE'];
									$dt['ITM_LASTP']	= $d['AMD_PRICE'];
									$dt['ITM_BUDG']		= $JOBCOST;
									//$dt['ADD_VOLM']	= $d['AMD_VOLM'];
									//$dt['ADD_PRICE']	= $d['AMD_PRICE'];
									//$dt['ADD_JOBCOST']= $JOBCOST;
									$dt['IS_LEVEL']		= $NEWLEV;
									$dt['ISLAST']		= 1;
									$dt['Patt_Number']	= $nxChld;
									
									$this->db->insert('tbl_joblist_detail',$dt);
								// START : ADD TO JOBLIST DETAIL
								
								// UPDATE SI
								$paramSIH 	= array('SI_AMANDNO'	=> $AMD_NUM,
													'SI_AMANDVAL'	=> $JOBCOST,
													'SI_AMANDSTAT'	=> $AMD_STAT,
													'SI_CODE'		=> $AMD_REFNO,
													'PRJCODE'		=> $PRJCODE);
								$this->m_project_amd->updateSIH($paramSIH);	
								
								$ITM_CODE	= $d['ITM_CODE'];
								$sql4		= "UPDATE tbl_item A SET A.ITM_VOLMBG = 
												(
													SELECT SUM(B.ITM_VOLM + B.ADD_VOLM - B.ADDM_VOLM) 
													FROM tbl_joblist_detail B 
													WHERE B.ITM_CODE = A.ITM_CODE
														AND B.PRJCODE = '$PRJCODE'
														AND B.ITM_CODE = '$ITM_CODE'
												)
												WHERE A.PRJCODE = '$PRJCODE'
													AND A.ITM_CODE = '$ITM_CODE'";
								$this->db->query($sql4);

								/*$ITM_VOLMBG	= 0;
								$ADDMVOLM	= 0;
								$ITM_IN		= 0;
								$sqlITM		= "SELECT ITM_VOLMBG, ADDMVOLM, ITM_IN FROM tbl_item 
												WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
								$resITM		= $this->db->query($sqlITM)->result();
								foreach($resITM as $rowITM):
									$ITM_VOLMBG	= $rowITM->ITM_VOLMBG;
									$ADDMVOLM	= $rowITM->ADDMVOLM;
									$ITM_IN		= $rowITM->ITM_IN;
								endforeach;
								$ITM_VOLMBGR	= $ITM_VOLMBG - $ITM_IN;
								$ADDMVOLM2		= $ADDMVOLM + $ADD_VOLM;

								$sql1	= "UPDATE tbl_item SET ITM_VOLMBGR = $ITM_VOLMBGR, ADDMVOLM = $ADDMVOLM2
											WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
								$this->db->query($sql1);
				
								$sql6	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST + $JOBCOST
											WHERE JOBCODEID = '$JOBPARENT1' AND PRJCODE = '$PRJCODE'";
								$this->db->query($sql6);
								
								$sql7	= "UPDATE tbl_boqlist SET JOBCOST = JOBCOST + $JOBCOST
											WHERE JOBCODEID = '$JOBPARENT1' AND PRJCODE = '$PRJCODE'";
								$this->db->query($sql7);*/
								
								$ITM_VOLMBG	= 0;
								$ADDMVOLM	= 0;
								$ADDVOLM	= 0;
								$ADDCOST	= 0;
								$ITM_IN		= 0;
								$sqlITM		= "SELECT ITM_VOLMBG, ADDVOLM, ADDCOST, ITM_IN FROM tbl_item 
												WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
								$resITM		= $this->db->query($sqlITM)->result();
								foreach($resITM as $rowITM):
									$ITM_VOLMBG	= $rowITM->ITM_VOLMBG;
									$ADDVOLM	= $rowITM->ADDVOLM;
									$ADDCOST	= $rowITM->ADDCOST;
									$ITM_IN		= $rowITM->ITM_IN;
								endforeach;	
								$ITM_VOLMBGR	= $ITM_VOLMBG - $ITM_IN;
								//$ADDMVOLM2	= $ADDMVOLM + $ADD_VOLM;
								
								$sql1	= "UPDATE tbl_item SET ITM_VOLMBGR = $ITM_VOLMBGR, ADDVOLM = ADDVOLM + $ADD_VOLM,
												ADDCOST = ADDCOST + $JOBCOST
											WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
								$this->db->query($sql1);
				
								$sql6	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST + $JOBCOST
											WHERE JOBCODEID = '$JOBPARENT1' AND PRJCODE = '$PRJCODE'";
								$this->db->query($sql6);
								
								$sql7	= "UPDATE tbl_boqlist SET JOBCOST = JOBCOST + $JOBCOST
											WHERE JOBCODEID = '$JOBPARENT1' AND PRJCODE = '$PRJCODE'";
								$this->db->query($sql7);	
							}
						}
						elseif($AMD_CATEG == 'OTH') // Other
						{
							foreach($_POST['dataIOB'] as $d)
							{
								$JOBCODEID		= $d['JOBCODEID'];
								$JOBPARENT		= $d['JOBPARENT'];
								$ITM_CODE		= $d['ITM_CODE'];
								$ADD_VOLM		= $d['AMD_VOLM'];
								$ADD_PRICE		= $d['AMD_PRICE'];
								$AMD_TOTAL		= $d['AMD_TOTAL'];
								$AMD_CLASS		= $d['AMD_CLASS'];
								$ADD_JOBCOST	= $AMD_TOTAL;

								$ITM_GROUP 		= "";
								$ITM_UNIT 		= "";
								$s_01 			= "SELECT ITM_GROUP, ITM_UNIT FROM tbl_item
													WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' LIMIT 1";
								$r_01 			= $this->db->query($s_01)->result();
								foreach($r_01 as $rw_01):
									$ITM_GROUP 	= $rw_01->ITM_GROUP;
									$ITM_UNIT 	= $rw_01->ITM_UNIT;
								endforeach;
								$d['PRJCODE']	= $PRJCODE;
								$d['ITM_GROUP']	= $ITM_GROUP;
								$d['ITM_UNIT']	= $ITM_UNIT;

								// UPDATE TO WBS / JOBLIST AND JOBDEATIL. LAST CHECKED : 21-10-29
									$paramWBS 	= array('ADD_VOLM' 		=> $ADD_VOLM,
														'ADD_PRICE'		=> $ADD_PRICE,
														'ADD_JOBCOST'	=> $ADD_JOBCOST,
														'JOBPARENT'		=> $JOBPARENT,
														'JOBCODEID'		=> $JOBCODEID,
														'ITM_CODE'		=> $ITM_CODE,
														'AMD_CLASS'		=> $AMD_CLASS,
														'PRJCODE'		=> $PRJCODE);
									$this->m_project_amd->updateOTHWBS($paramWBS);
							}

							foreach($_POST['dataSUB'] as $ds)
							{
								$JOBCODEID	= $ds['JOBCODEID'];				// JOBCODEID YANG DIKURANGI
								$JOBPARENT	= $ds['JOBPARENT'];
								$ITM_CODE	= $ds['ITM_CODE'];				// ITEM YANG DIKURANGI
								$ADD_VOLM	= $ds['AMD_VOLM'];				// VOLUME SAAT INI (SISA YANG BELUM DIGUNAKAN / DIREQUEST)
								$ADD_PRICE	= $ds['AMD_PRICE'];				// HARGA = NILAI TOTAL (SETELAH - NILAI TRANSFER) / VOLUME SAAT INI
								$AMD_TOTAL	= $ds['AMD_TOTAL'];				// VOLUME SISA * HARGA TERBARU
								$AMD_TOTTSF	= $ds['AMD_TOTTSF'];			// NILAI YANG DITRANSFER
								$AMD_CLASS	= $ds['AMD_CLASS'];				// ALWAYS 0
								$ADD_JOBCOST= $AMD_TOTAL;

								$ds['AMD_NUM']	= $AMD_NUM;
								$ds['PRJCODE']	= $PRJCODE;
								$ITM_CODE 		= $ds['ITM_CODE'];
								$ITM_GROUP 		= "";
								$ITM_UNIT 		= "";
								$s_01 			= "SELECT ITM_GROUP, ITM_UNIT FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' LIMIT 1";
								$r_01 			= $this->db->query($s_01)->result();
								foreach($r_01 as $rw_01):
									$ITM_GROUP 	= $rw_01->ITM_GROUP;
									$ITM_UNIT 	= $rw_01->ITM_UNIT;
								endforeach;
								$ds['ITM_GROUP']= $ITM_GROUP;
								$ds['ITM_UNIT']	= $ITM_UNIT;


								// UPDATE TO WBS / JOBLIST AND JOBDEATIL. LAST CHECKED : 21-10-29
									$paramWBS 	= array('ADD_VOLM' 		=> $ADD_VOLM,
														'ADD_PRICE'		=> $ADD_PRICE,
														'ADD_JOBCOST'	=> $ADD_JOBCOST,
														'AMD_TOTTSF'	=> $AMD_TOTTSF,
														'JOBPARENT'		=> $JOBPARENT,
														'JOBCODEID'		=> $JOBCODEID,
														'ITM_CODE'		=> $ITM_CODE,
														'AMD_CLASS'		=> $AMD_CLASS,
														'PRJCODE'		=> $PRJCODE);
									$this->m_project_amd->updateWBSMINUS_OTH($paramWBS);
							}
						}

					// START : UPDATE STATUS
						$completeName 	= $this->session->userdata['completeName'];
						$paramStat 		= array('PM_KEY' 		=> "AMD_NUM",
												'DOC_CODE' 		=> $AMD_NUM,
												'DOC_STAT' 		=> $AMD_STAT,
												'PRJCODE' 		=> $PRJCODE,
												//'CREATERNM'		=> "",
												'TBLNAME'		=> "tbl_amd_header");
						$this->m_updash->updateStatus($paramStat);
					// END : UPDATE STATUS
				}
			}
			elseif($AMD_STAT == 4)
			{
				$updAMD 		= array('AMD_MEMO'	=> $this->input->post('AMD_MEMO'),
										'AMD_STAT'	=> $this->input->post('AMD_STAT'));
				$this->m_project_amd->updateAMD($AMD_NUM, $updAMD);

				// CLEAR APROVE HISTORY
					$this->load->model('m_updash/m_updash', '', TRUE);
					$this->m_updash->delAppHist($AH_CODE);

				
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "AMD_NUM",
											'DOC_CODE' 		=> $AMD_NUM,
											'DOC_STAT' 		=> $AMD_STAT,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> "",
											'TBLNAME'		=> "tbl_amd_header");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
			}
			elseif($AMD_STAT == 5)
			{
				$updAMD 		= array('AMD_MEMO'	=> $this->input->post('AMD_MEMO'),
										'AMD_STAT'	=> $this->input->post('AMD_STAT'));
				$this->m_project_amd->updateAMD($AMD_NUM, $updAMD);
				
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "AMD_NUM",
											'DOC_CODE' 		=> $AMD_NUM,
											'DOC_STAT' 		=> $AMD_STAT,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> "",
											'TBLNAME'		=> "tbl_amd_header");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
			}

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $AMD_NUM;
				$MenuCode 		= 'MN407';
				$TTR_CATEG		= 'UP-APP';
				
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
			
			$url			= site_url('c_comprof/c_am1h0db2/i1dah80Idx/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function pop1h0f0gSI() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE		= $_GET['id'];
			$PRJCODE		= $this->url_encryption_helper->decode_url($PRJCODE);
			$EmpID 			= $this->session->userdata('Emp_ID');
			
			$data['title'] 				= $appName;
			$data['pageFrom']			= 'PR';
			$data['PRJCODE']			= $PRJCODE;
			
			$this->load->view('v_company/v_project_amd/v_amd_sel_si', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function p_R1n7()
	{
		$this->load->model('m_purchase/m_purchase_req/m_purchase_req', '', TRUE);
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$AMD_NUM		= $_GET['id'];
		$AMD_NUM		= $this->url_encryption_helper->decode_url($AMD_NUM);
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$data['title'] 	= $appName;
			
			$getAMD = $this->m_project_amd->get_amd_by_number($AMD_NUM)->row();
			
			$data['default']['AMD_NUM'] 	= $getAMD->AMD_NUM;
			$data['default']['AMD_CODE'] 	= $getAMD->AMD_CODE;
			$data['default']['PRJCODE'] 	= $getAMD->PRJCODE;
			$PRJCODE						= $getAMD->PRJCODE;
			$data['default']['AMD_TYPE'] 	= $getAMD->AMD_TYPE;
			$data['default']['AMD_CATEG'] 	= $getAMD->AMD_CATEG;
			$data['default']['AMD_FUNC'] 	= $getAMD->AMD_FUNC;
			$data['default']['AMD_REFNO'] 	= $getAMD->AMD_REFNO;
			$data['default']['AMD_REFNOAM'] = $getAMD->AMD_REFNOAM;
			$data['default']['AMD_JOBID'] 	= $getAMD->AMD_JOBID;
			$data['default']['JOBCODEID'] 	= $getAMD->AMD_JOBID;
			$data['default']['AMD_DATE'] 	= $getAMD->AMD_DATE;
			$data['default']['AMD_DESC'] 	= $getAMD->AMD_DESC;
			$data['default']['AMD_UNIT'] 	= $getAMD->AMD_UNIT;
			$data['default']['AMD_NOTES'] 	= $getAMD->AMD_NOTES;
			$data['default']['AMD_MEMO'] 	= $getAMD->AMD_MEMO;
			$data['default']['AMD_AMOUNT'] 	= $getAMD->AMD_AMOUNT;
			$data['default']['AMD_STAT'] 	= $getAMD->AMD_STAT;
			$data['default']['Patt_Number'] = $getAMD->Patt_Number;
							
			$this->load->view('v_company/v_project_amd/v_amd_print', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function popupallJL()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE		= $_GET['id'];
			$PRJCODE		= $this->url_encryption_helper->decode_url($PRJCODE);
			$EmpID 			= $this->session->userdata('Emp_ID');
			
			$data['title'] 				= $appName;
			$data['pageFrom']			= 'PR';
			$data['PRJCODE']			= $PRJCODE;
					
			$this->load->view('v_company/v_project_amd/v_amd_seljob', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

  	function get_AllDataJL() // GOOD
	{
		//$PRJCODE		= $_GET['id'];

		//setlocale(LC_ALL, 'id-ID', 'id_ID');
		setlocale(LC_ALL, 'en_EN');

		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		$PRJCODE	= $_GET['id'];

		$LangID     = $this->session->userdata['LangID'];
        
        $sqlTransl      = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
        $resTransl      = $this->db->query($sqlTransl)->result();
        foreach($resTransl as $rowTransl) :
            $TranslCode = $rowTransl->MLANG_CODE;
            $LangTransl = $rowTransl->LangTransl;

    		if($TranslCode == 'Director')$Director = $LangTransl;
            if($TranslCode == 'Compailer')$Compailer = $LangTransl;
            if($TranslCode == 'Location')$Location = $LangTransl;
    		if($TranslCode == 'Active')$Active = $LangTransl;
    		if($TranslCode == 'Inactive')$Inactive = $LangTransl;
    		if($TranslCode == 'Contact')$Contact = $LangTransl;
    		if($TranslCode == 'CompanyName')$CompanyName = $LangTransl;
    		if($TranslCode == 'Budget')$Budget = $LangTransl;
    		if($TranslCode == 'Phone')$Phone = $LangTransl;
    		if($TranslCode == 'Notes')$Notes = $LangTransl;
        endforeach;
		
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
			
			$columns_valid 	= array("JOBCODEID", 
									"JOBDESC");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_project_amd->get_AllDataJLC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			
			$query 			= $this->m_project_amd->get_AllDataJLL($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			$TotBOQ			= 0;
			$TotBUD			= 0;
			$TotADD			= 0;
			$TotADD2		= 0;
			$TotADD3		= 0;
			$TotADD4		= 0;
			$TotADD5		= 0;
			$TotALL			= 0;
			$TotREM			= 0;
			$REMAIN2		= 0;
			$TotUSE			= 0;
			$TotPC			= 0;	// Total Project Complete
			foreach ($query->result_array() as $dataI) 
			{
				$JOBCODEID 		= $dataI['JOBCODEID'];				// 1
				$JOBDESC		= $dataI['JOBDESC'];
				$ITM_UNIT		= $dataI['JOBUNIT'];
				$JOBLEV			= $dataI['JOBLEV'];
				$ISLAST			= $dataI['ISLAST'];
				$ISLAST_BOQ		= $dataI['ISLASTH'];

				// SPACE
					if($JOBLEV == 1)
						$spaceLev 	= "";
					elseif($JOBLEV == 2)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 3)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 4)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 5)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 6)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 7)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 8)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 9)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 10)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 11)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 12)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

					if($ISLAST_BOQ == 0)
					{
						$chkBox	= "";
						$fntBld = " style='font-weight:bold;'";
					}
					else
					{
						$chkBox	= "<input type='radio' name='chk0' value='".$JOBCODEID."|".$JOBDESC."' onClick='pickThis0(this);'/>";
						$fntBld = "";
					}

					$strLENH 	= strlen($JOBDESC);
					$JOBDESC	= substr("$JOBDESC", 0, 100);
					$JOBDESC 	= $JOBDESC;
					if($strLENH > 100)
						$JOBDESC 	= $JOBDESC."...";

					$JobView		= "$spaceLev $JOBCODEID - $JOBDESC";

				/*$output['data'][] 	= array("<div style='text-align:center;'>".$chkBox."</div>",
											"<div>
										  		<p><span ".$CELL_COL.">".$JobView."</span></p>
										  	</div>
										  	<div style='margin-left: 15px; font-style: italic;'>
										  		<i class='text-muted fa fa-rss'>&nbsp;&nbsp;".$JDESCH_1."
										  	</div>",
											"<div style='text-align:center;'><span ".$CELL_COL.">".$JOBUNITV."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".$JOBVOLMV."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".$TOT_REQV."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".$PO_VOLMV."</span></div>",
											"<div style='text-align:right;'>".$statRem."</div>");*/

				$output['data'][] 	= array($chkBox,
											"<div><span ".$fntBld.">".$JobView."</span></div>");

				$noU			= $noU + 1;
			}
			//$output['data'][] 	= array("$PRJCODE","","");
			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

  	function get_AllDataJLD() // GOOD 
	{
		//$PRJCODE		= $_GET['id'];

		//setlocale(LC_ALL, 'id-ID', 'id_ID');
		setlocale(LC_ALL, 'en_EN');

		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		$PRJCODE	= $_GET['id'];
		$collData	= explode("~", $_GET['collData']);
		$ITM_CODEH	= $collData[0];
		$AMD_CATEG	= $collData[1];

		$LangID     = $this->session->userdata['LangID'];
        
        $sqlTransl      = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
        $resTransl      = $this->db->query($sqlTransl)->result();
        foreach($resTransl as $rowTransl) :
            $TranslCode = $rowTransl->MLANG_CODE;
            $LangTransl = $rowTransl->LangTransl;

    		if($TranslCode == 'Director')$Director = $LangTransl;
            if($TranslCode == 'Compailer')$Compailer = $LangTransl;
            if($TranslCode == 'Location')$Location = $LangTransl;
    		if($TranslCode == 'Active')$Active = $LangTransl;
    		if($TranslCode == 'Inactive')$Inactive = $LangTransl;
    		if($TranslCode == 'Contact')$Contact = $LangTransl;
    		if($TranslCode == 'CompanyName')$CompanyName = $LangTransl;
    		if($TranslCode == 'Budget')$Budget = $LangTransl;
    		if($TranslCode == 'Phone')$Phone = $LangTransl;
    		if($TranslCode == 'Notes')$Notes = $LangTransl;
        endforeach;
		
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
			
			$columns_valid 	= array("JOBCODEID", 
									"JOBDESC");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_project_amd->get_AllDataJLDC($PRJCODE, $ITM_CODEH, $AMD_CATEG, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			
			$query 			= $this->m_project_amd->get_AllDataJLDL($PRJCODE, $ITM_CODEH, $AMD_CATEG, $search, $length, $start, $order, $dir); 
								
			$noU			= $start + 1;
			$TotBOQ			= 0;
			$TotBUD			= 0;
			$TotADD			= 0;
			$TotADD2		= 0;
			$TotADD3		= 0;
			$TotADD4		= 0;
			$TotADD5		= 0;
			$TotALL			= 0;
			$TotREM			= 0;
			$REMAIN2		= 0;
			$TotUSE			= 0;
			$TotPC			= 0;	// Total Project Complete
			foreach ($query->result_array() as $dataI) 
			{
				$JCODEH_0 		= $dataI['JOBCODEID'];				// JOBCODE HEADER LEVEL 1 FROM ITEM
				$JDESCH_0		= $dataI['JOBDESC'];				// JOBDESC HEADER LEVEL 1 FROM ITEM
				$ITM_UNIT		= $dataI['JOBUNIT'];
				$JOBLEV			= $dataI['JOBLEV'];
				$ISLAST			= $dataI['ISLAST'];
				$ISLAST_BOQ		= $dataI['ISLASTH'];
				$IS_LEVEL		= $dataI['IS_LEVEL'];
				$TABMAX_0 		= ($IS_LEVEL-1) * 10;

				$JOBCODEID 	= "";
				$ITM_REMV 	= 0;
				$ITM_PRICE 	= 0;
				$ITM_REMVAL = 0;
				$s_JID 		= "SELECT JOBCODEID, IF(ITM_UNIT = 'LS', ITM_VOLM, (ITM_VOLM + ADD_VOLM - REQ_VOLM - ADDM_VOLM)) AS ITM_REMV, ITM_LASTP,
									(ITM_BUDG + ADD_JOBCOST - REQ_AMOUNT - ADDM_JOBCOST) AS ITM_REMVAL
								FROM tbl_joblist_detail
								WHERE ITM_CODE = '$ITM_CODEH' AND JOBPARENT = '$JCODEH_0' AND PRJCODE = '$PRJCODE'";
				$r_JID		= $this->db->query($s_JID)->result();
				foreach($r_JID as $rw_JID):
					$JOBCODEID	= $rw_JID->JOBCODEID;
					$ITM_REMV	= $rw_JID->ITM_REMV;
					$ITM_REMVAL	= $rw_JID->ITM_REMVAL;
					//$ITM_PRICE	= $rw_JID->ITM_LASTP;
					$ITM_REMVP 	= $ITM_REMV;
					if($ITM_REMV == 0)
						$ITM_REMVP = 1;
					$ITM_PRICE	= $ITM_REMVAL/$ITM_REMVP;
				endforeach;

				// MAXIMUM NUMBER OF JOBLIST DETIL BY JOBPARENT
					/*$MAXNumb= 0;
					$s_JIDC = "SELECT MAX(Patt_Number) AS MAXNumb FROM tbl_joblist_detail WHERE JOBPARENT = '$JCODEH_0' AND PRJCODE = '$PRJCODE'";
					$r_JIDC		= $this->db->query($s_JIDC)->result();
					foreach($r_JIDC as $rw_JIDC):
						$MAXNumb	= $rw_JIDC->MAXNumb;
					endforeach;*/
					$MAXNumb= 0;
					$s_JIDC = "tbl_joblist_detail WHERE JOBPARENT = '$JCODEH_0' AND PRJCODE = '$PRJCODE'";
					$r_JIDC	= $this->db->count_all($s_JIDC);

					$nextNo = $r_JIDC+1;
					if($nextNo < 10)
						$nwJID 	= $JCODEH_0.".0".$nextNo;
					else
						$nwJID 	= $JCODEH_0.".".$nextNo;

				$JDESCH_0 	= wordwrap($JDESCH_0, 60, "<br>", TRUE);
				$JobView	= "$JDESCH_0<br><i style='font-style: italic;'>$JCODEH_0</i>";

				// SHOW PARENT JOB
					$JCODEH_1 	= "";
					$JDESCH_1 	= "";
					$JSHOWD_1 	= "";
					$JSHOWD_2 	= "";
					$JSHOWD_3 	= "";
					$JSHOWD_4 	= "";
					$JSHOWD_5 	= "";
					$JSHOWD_6 	= "";
					$JSHOWD_7 	= "";
					$JSHOWD_8 	= "";
					$JSHOWD_9 	= "";
					$JSHOWD_10 	= "";
					$s_JIDH_1 	= "SELECT JOBCODEID, JOBDESC FROM tbl_joblist_detail
									WHERE JOBCODEID IN (SELECT JOBPARENT FROM tbl_joblist_detail WHERE JOBCODEID = '$JCODEH_0')";
					$r_JIDH_1		= $this->db->query($s_JIDH_1)->result();
					foreach($r_JIDH_1 as $rw_JIDH_1):
						$JCODEH_1	= $rw_JIDH_1->JOBCODEID;
						$JDESCH_1	= strtoupper(wordwrap($rw_JIDH_1->JOBDESC, 60, "<br>", TRUE));
						$TABMAX_1 	= $TABMAX_0-10;
						$JSHOWD_1	= "<div style='margin-left: ".$TABMAX_1."px; font-style: italic; white-space:nowrap'>
									  		<label style='white-space:nowrap'>$JCODEH_1 : $JDESCH_1</label>
									  	</div>";
						$JCODEH_2 	= "";
						$JDESCH_2 	= "";
						$JSHOWD_2 	= "";
						$s_JIDH_2 	= "SELECT JOBCODEID, JOBDESC FROM tbl_joblist_detail
										WHERE JOBCODEID IN (SELECT JOBPARENT FROM tbl_joblist_detail WHERE JOBCODEID = '$JCODEH_1')";
						$r_JIDH_2		= $this->db->query($s_JIDH_2)->result();
						foreach($r_JIDH_2 as $rw_JIDH_2):
							$JCODEH_2	= $rw_JIDH_2->JOBCODEID;
							$JDESCH_2	= strtoupper(wordwrap($rw_JIDH_2->JOBDESC, 60, "<br>", TRUE));
							$TABMAX_2 	= $TABMAX_1-10;
							$JSHOWD_2	= "<div style='margin-left: ".$TABMAX_2."px; font-style: italic; white-space:nowrap'>
										  		<label style='white-space:nowrap'>$JCODEH_2 : $JDESCH_2</label>
										  	</div>";
							$JCODEH_3 	= "";
							$JDESCH_3 	= "";
							$JSHOWD_3 	= "";
							$s_JIDH_3 	= "SELECT JOBCODEID, JOBDESC FROM tbl_joblist_detail
											WHERE JOBCODEID IN (SELECT JOBPARENT FROM tbl_joblist_detail WHERE JOBCODEID = '$JCODEH_2')";
							$r_JIDH_3		= $this->db->query($s_JIDH_3)->result();
							foreach($r_JIDH_3 as $rw_JIDH_3):
								$JCODEH_3	= $rw_JIDH_3->JOBCODEID;
								$JDESCH_3	= strtoupper(wordwrap($rw_JIDH_3->JOBDESC, 60, "<br>", TRUE));
								$TABMAX_3 	= $TABMAX_2-10;
								$JSHOWD_3	= "<div style='margin-left: ".$TABMAX_3."px; font-style: italic; white-space:nowrap'>
											  		<label style='white-space:nowrap'>$JCODEH_3 : $JDESCH_3</label>
											  	</div>";
								$JCODEH_4 	= "";
								$JDESCH_4 	= "";
								$JSHOWD_4 	= "";
								$s_JIDH_4 	= "SELECT JOBCODEID, JOBDESC FROM tbl_joblist_detail
												WHERE JOBCODEID IN (SELECT JOBPARENT FROM tbl_joblist_detail WHERE JOBCODEID = '$JCODEH_3')";
								$r_JIDH_4		= $this->db->query($s_JIDH_4)->result();
								foreach($r_JIDH_4 as $rw_JIDH_4):
									$JCODEH_4	= $rw_JIDH_4->JOBCODEID;
									$JDESCH_4	= strtoupper(wordwrap($rw_JIDH_4->JOBDESC, 60, "<br>", TRUE));
									$TABMAX_4 	= $TABMAX_3-10;
									$JSHOWD_4	= "<div style='margin-left: ".$TABMAX_4."px; font-style: italic; white-space:nowrap'>
												  		<label style='white-space:nowrap'>$JCODEH_4 : $JDESCH_4</label>
												  	</div>";
									$JCODEH_5 	= "";
									$JDESCH_5 	= "";
									$JSHOWD_5 	= "";
									$s_JIDH_5 	= "SELECT JOBCODEID, JOBDESC FROM tbl_joblist_detail
													WHERE JOBCODEID IN (SELECT JOBPARENT FROM tbl_joblist_detail WHERE JOBCODEID = '$JCODEH_4')";
									$r_JIDH_5		= $this->db->query($s_JIDH_5)->result();
									foreach($r_JIDH_5 as $rw_JIDH_5):
										$JCODEH_5	= $rw_JIDH_5->JOBCODEID;
										$JDESCH_5	= strtoupper(wordwrap($rw_JIDH_5->JOBDESC, 60, "<br>", TRUE));
										$TABMAX_5 	= $TABMAX_4-10;
										$JSHOWD_5	= "<div style='margin-left: ".$TABMAX_5."px; font-style: italic; white-space:nowrap'>
													  		<label style='white-space:nowrap'>$JCODEH_5 : $JDESCH_5</label>
													  	</div>";
										$JCODEH_6 	= "";
										$JDESCH_6 	= "";
										$JSHOWD_6 	= "";
										$s_JIDH_6 	= "SELECT JOBCODEID, JOBDESC FROM tbl_joblist_detail
														WHERE JOBCODEID IN (SELECT JOBPARENT FROM tbl_joblist_detail WHERE JOBCODEID = '$JCODEH_5')";
										$r_JIDH_6		= $this->db->query($s_JIDH_6)->result();
										foreach($r_JIDH_6 as $rw_JIDH_6):
											$JCODEH_6	= $rw_JIDH_6->JOBCODEID;
											$JDESCH_6	= strtoupper(wordwrap($rw_JIDH_6->JOBDESC, 60, "<br>", TRUE));
											$TABMAX_6 	= $TABMAX_5-10;
											$JSHOWD_6	= "<div style='margin-left: ".$TABMAX_6."px; font-style: italic; white-space:nowrap'>
														  		<label style='white-space:nowrap'>$JCODEH_6 : $JDESCH_6</label>
														  	</div>";
											$JCODEH_7 	= "";
											$JDESCH_7 	= "";
											$JSHOWD_7 	= "";
											$s_JIDH_7 	= "SELECT JOBCODEID, JOBDESC FROM tbl_joblist_detail
															WHERE JOBCODEID IN (SELECT JOBPARENT FROM tbl_joblist_detail WHERE JOBCODEID = '$JCODEH_6')";
											$r_JIDH_7		= $this->db->query($s_JIDH_7)->result();
											foreach($r_JIDH_7 as $rw_JIDH_7):
												$JCODEH_7	= $rw_JIDH_7->JOBCODEID;
												$JDESCH_7	= strtoupper(wordwrap($rw_JIDH_7->JOBDESC, 60, "<br>", TRUE));
												$TABMAX_7 	= $TABMAX_6-10;
												$JSHOWD_7	= "<div style='margin-left: ".$TABMAX_7."px; font-style: italic; white-space:nowrap'>
															  		<label style='white-space:nowrap'>$JCODEH_7 : $JDESCH_7</label>
															  	</div>";
												$JCODEH_8 	= "";
												$JDESCH_8 	= "";
												$JSHOWD_8 	= "";
												$s_JIDH_8 	= "SELECT JOBCODEID, JOBDESC FROM tbl_joblist_detail
																WHERE JOBCODEID IN (SELECT JOBPARENT FROM tbl_joblist_detail WHERE JOBCODEID = '$JCODEH_7')";
												$r_JIDH_8		= $this->db->query($s_JIDH_8)->result();
												foreach($r_JIDH_8 as $rw_JIDH_8):
													$JCODEH_8	= $rw_JIDH_8->JOBCODEID;
													$JDESCH_8	= strtoupper(wordwrap($rw_JIDH_8->JOBDESC, 60, "<br>", TRUE));
													$TABMAX_8 	= $TABMAX_7-10;
													$JSHOWD_8	= "<div style='margin-left: ".$TABMAX_8."px; font-style: italic; white-space:nowrap'>
																  		<label style='white-space:nowrap'>$JCODEH_8 : $JDESCH_8</label>
																  	</div>";
													$JCODEH_9 	= "";
													$JDESCH_9 	= "";
													$JSHOWD_9 	= "";
													$s_JIDH_9 	= "SELECT JOBCODEID, JOBDESC FROM tbl_joblist_detail
																	WHERE JOBCODEID IN (SELECT JOBPARENT FROM tbl_joblist_detail WHERE JOBCODEID = '$JCODEH_8')";
													$r_JIDH_9		= $this->db->query($s_JIDH_9)->result();
													foreach($r_JIDH_9 as $rw_JIDH_9):
														$JCODEH_9	= $rw_JIDH_9->JOBCODEID;
														$JDESCH_9	= strtoupper(wordwrap($rw_JIDH_9->JOBDESC, 60, "<br>", TRUE));
														$TABMAX_9 	= $TABMAX_8-10;
														$JSHOWD_9	= "<div style='margin-left: ".$TABMAX_9."px; font-style: italic; white-space:nowrap'>
																	  		<label style='white-space:nowrap'>$JCODEH_9 : $JDESCH_9</label>
																	  	</div>";
														$JCODEH_10 	= "";
														$JDESCH_10 	= "";
														$JSHOWD_10 	= "";
														$s_JIDH_10 	= "SELECT JOBCODEID, JOBDESC FROM tbl_joblist_detail
																		WHERE JOBCODEID IN (SELECT JOBPARENT FROM tbl_joblist_detail WHERE JOBCODEID = '$JCODEH_9')";
														$r_JIDH_10		= $this->db->query($s_JIDH_10)->result();
														foreach($r_JIDH_10 as $rw_JIDH_10):
															$JCODEH_10	= $rw_JIDH_10->JOBCODEID;
															$JDESCH_10	= strtoupper(wordwrap($rw_JIDH_10->JOBDESC, 60, "<br>", TRUE));
															$TABMAX_10 	= $TABMAX_9-10;
															$JSHOWD_10	= "<div style='margin-left: ".$TABMAX_10."px; font-style: italic; white-space:nowrap'>
																		  		<label style='white-space:nowrap'>$JCODEH_10 : $JDESCH_10</label>
																		  	</div>";
														endforeach;
													endforeach;
												endforeach;
											endforeach;
										endforeach;
									endforeach;
								endforeach;
							endforeach;
						endforeach;
					endforeach;

				if($ISLAST_BOQ == 0)
				{
					$chkBox	= "";
					$fntBld = "'";
				}
				else
				{
					$chkBox	= "<input type='checkbox' name='chk0' value='".$JOBCODEID."|".$JCODEH_0."|".$JDESCH_0."|".$ITM_UNIT."|".$nwJID."|".$ITM_REMV."|".$ITM_PRICE."|".$ITM_REMVAL."' onClick='pickThis0(this);'/>";
					$fntBld = " style='font-weight:bold;'";
				}

				$output['data'][] 	= array("$chkBox",
											"<div><span ".$fntBld.">".$JobView."</span></div>",
											"$JSHOWD_10$JSHOWD_9$JSHOWD_8$JSHOWD_7$JSHOWD_6$JSHOWD_5$JSHOWD_4$JSHOWD_3$JSHOWD_2$JSHOWD_1");

				$noU			= $noU + 1;
			}
			
			//$output['data'][] 	= array("$PRJCODE","","");
			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

  	function get_AllDataJLDSUBS() // GOOD
	{
		//$PRJCODE		= $_GET['id'];

		//setlocale(LC_ALL, 'id-ID', 'id_ID');
		setlocale(LC_ALL, 'en_EN');

		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		$PRJCODE	= $_GET['id'];
		$collData	= explode("~", $_GET['collData']);
		$ITM_CODEH	= $collData[0];
		$AMD_CATEG	= $collData[1];
		$JOBID_SEL	= $collData[2];		// JOBCODEID ITEM YANG OVER BUDGET
		$JOBPAR_SEL	= $collData[3]; 	// JOBCODEID HEADER PEKERJAAN ITEM YANG DITAMBAHKAN

		$LangID     = $this->session->userdata['LangID'];
        
        $sqlTransl      = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
        $resTransl      = $this->db->query($sqlTransl)->result();
        foreach($resTransl as $rowTransl) :
            $TranslCode = $rowTransl->MLANG_CODE;
            $LangTransl = $rowTransl->LangTransl;

    		if($TranslCode == 'Director')$Director = $LangTransl;
            if($TranslCode == 'Compailer')$Compailer = $LangTransl;
            if($TranslCode == 'Location')$Location = $LangTransl;
    		if($TranslCode == 'Active')$Active = $LangTransl;
    		if($TranslCode == 'Inactive')$Inactive = $LangTransl;
    		if($TranslCode == 'Contact')$Contact = $LangTransl;
    		if($TranslCode == 'CompanyName')$CompanyName = $LangTransl;
    		if($TranslCode == 'Budget')$Budget = $LangTransl;
    		if($TranslCode == 'Phone')$Phone = $LangTransl;
    		if($TranslCode == 'Notes')$Notes = $LangTransl;
        endforeach;
		
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
			
			$columns_valid 	= array("JOBCODEID", 
									"JOBDESC");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_project_amd->get_AllDataJLDETC($PRJCODE, $ITM_CODEH, $AMD_CATEG, $JOBID_SEL, $JOBPAR_SEL, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			
			$query 			= $this->m_project_amd->get_AllDataJLDETL($PRJCODE, $ITM_CODEH, $AMD_CATEG, $JOBID_SEL, $JOBPAR_SEL, $search, $length, $start, $order, $dir); 
								
			$noU			= $start + 1;
			$TotBOQ			= 0;
			$TotBUD			= 0;
			$TotADD			= 0;
			$TotADD2		= 0;
			$TotADD3		= 0;
			$TotADD4		= 0;
			$TotADD5		= 0;
			$TotALL			= 0;
			$TotREM			= 0;
			$REMAIN2		= 0;
			$TotUSE			= 0;
			$TotPC			= 0;	// Total Project Complete
			foreach ($query->result_array() as $dataI) 
			{
				/*$JCODEH_0 	= $dataI['JOBCODEID'];				// JOBCODE HEADER LEVEL 1 FROM ITEM
				$JDESCH_0		= $dataI['JOBDESC'];				// JOBDESC HEADER LEVEL 1 FROM ITEM*/
				$JOBCODEID 		= $dataI['JOBCODEID'];
				$JOBPARENT 		= $dataI['JOBPARENT'];
				$JOBDESC		= $dataI['JOBDESC'];
				$ITM_CODE		= $dataI['ITM_CODE'];
				$ITM_UNIT		= $dataI['JOBUNIT'];
				$ITM_VOLM		= $dataI['ITM_VOLM'];
				$ITM_PRICE		= $dataI['ITM_PRICE'];
				$ITM_BUDG		= $dataI['ITM_BUDG'];
				$ADD_VOLM		= $dataI['ADD_VOLM'];
				$ADD_PRICE		= $dataI['ADD_PRICE'];
				$ADD_JOBCOST	= $dataI['ADD_JOBCOST'];
				$REQ_VOLM		= $dataI['REQ_VOLM'];
				$REQ_AMOUNT		= $dataI['REQ_AMOUNT'];
				$ITM_LASTP		= $dataI['ITM_LASTP'];
				$JOBLEV			= $dataI['JOBLEV'];
				$ISLAST			= $dataI['ISLAST'];
				$ISLAST_BOQ		= $dataI['ISLASTH'];
				$IS_LEVEL		= $dataI['IS_LEVEL'];
				$TABMAX_0 		= ($IS_LEVEL-1) * 10;


				$TOT_BUDGVOL	= $ITM_VOLM + $ADD_VOLM;
				$TOT_BUDGVAL 	= $ITM_BUDG + $ADD_JOBCOST;

				$ITM_REMVOL		= $TOT_BUDGVOL - $REQ_VOLM;
				$ITM_REMVAL		= $TOT_BUDGVAL - $REQ_AMOUNT;

				$disITM 		= 0;
				if($ITM_REMVAL <= 0 || $ISLAST == 0)
					$disITM 	= 1;

				// MAXIMUM NUMBER OF JOBLIST DETIL BY JOBPARENT
					$s_JIDC = "tbl_joblist_detail WHERE JOBCODEID = '$JOBPARENT' AND PRJCODE = '$PRJCODE'";
					$r_JIDC	= $this->db->count_all($s_JIDC);
					$nextNo = $r_JIDC+1;
					$nwJID 	= $JOBCODEID."-".$nextNo;

				$ITM_NAME 	= "";
				$s_ITM 		= "SELECT ITM_NAME FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
				$r_ITM		= $this->db->query($s_ITM)->result();
				foreach($r_ITM as $rw_ITM):
					$ITM_NAME	= $rw_ITM->ITM_NAME;
				endforeach;

				// SHOW PARENT JOB
					$JCODEH_1 	= "";
					$JDESCH_1 	= "";
					$JSHOWD_1 	= "";
					$JSHOWD_2 	= "";
					$JSHOWD_3 	= "";
					$JSHOWD_4 	= "";
					$JSHOWD_5 	= "";
					$JSHOWD_6 	= "";
					$JSHOWD_7 	= "";
					$JSHOWD_8 	= "";
					$JSHOWD_9 	= "";
					$JSHOWD_10 	= "";
					$s_JIDH_1 	= "SELECT JOBCODEID, JOBPARENT, JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JOBPARENT'";
					$r_JIDH_1		= $this->db->query($s_JIDH_1)->result();
					foreach($r_JIDH_1 as $rw_JIDH_1):
						$JCODE_1	= $rw_JIDH_1->JOBCODEID;
						$JCODEH_1	= $rw_JIDH_1->JOBPARENT;
						$JDESCH_1	= strtoupper(wordwrap($rw_JIDH_1->JOBDESC, 60, "<br>", TRUE));
						$TABMAX_1 	= $TABMAX_0-10;
						$JSHOWD_1	= "<div style='margin-left: ".$TABMAX_1."px; font-style: italic; white-space:nowrap'>
									  		<label style='white-space:nowrap'>$JCODE_1 : $JDESCH_1</label>
									  	</div>";
						$JCODEH_2 	= "";
						$JDESCH_2 	= "";
						$JSHOWD_2 	= "";
						$s_JIDH_2 	= "SELECT JOBCODEID, JOBPARENT, JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JCODEH_1' AND PRJCODE = '$PRJCODE'";
						$r_JIDH_2		= $this->db->query($s_JIDH_2)->result();
						foreach($r_JIDH_2 as $rw_JIDH_2):
							$JCODE_2	= $rw_JIDH_2->JOBCODEID;
							$JCODEH_2	= $rw_JIDH_2->JOBPARENT;
							$JDESCH_2	= strtoupper(wordwrap($rw_JIDH_2->JOBDESC, 60, "<br>", TRUE));
							$TABMAX_2 	= $TABMAX_1-10;
							$JSHOWD_2	= "<div style='margin-left: ".$TABMAX_2."px; font-style: italic; white-space:nowrap'>
										  		<label style='white-space:nowrap'>$JCODE_2 : $JDESCH_2</label>
										  	</div>";
							$JCODEH_3 	= "";
							$JDESCH_3 	= "";
							$JSHOWD_3 	= "";
							$s_JIDH_3 	= "SELECT JOBCODEID, JOBPARENT, JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JCODEH_2' AND PRJCODE = '$PRJCODE'";
							$r_JIDH_3		= $this->db->query($s_JIDH_3)->result();
							foreach($r_JIDH_3 as $rw_JIDH_3):
								$JCODE_3	= $rw_JIDH_3->JOBCODEID;
								$JCODEH_3	= $rw_JIDH_3->JOBPARENT;
								$JDESCH_3	= strtoupper(wordwrap($rw_JIDH_3->JOBDESC, 60, "<br>", TRUE));
								$TABMAX_3 	= $TABMAX_2-10;
								$JSHOWD_3	= "<div style='margin-left: ".$TABMAX_3."px; font-style: italic; white-space:nowrap'>
											  		<label style='white-space:nowrap'>$JCODE_3 : $JDESCH_3</label>
											  	</div>";
								$JCODEH_4 	= "";
								$JDESCH_4 	= "";
								$JSHOWD_4 	= "";
								$s_JIDH_4 	= "SELECT JOBCODEID, JOBPARENT, JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JCODEH_3' AND PRJCODE = '$PRJCODE'";
								$r_JIDH_4		= $this->db->query($s_JIDH_4)->result();
								foreach($r_JIDH_4 as $rw_JIDH_4):
									$JCODE_4	= $rw_JIDH_4->JOBCODEID;
									$JCODEH_4	= $rw_JIDH_4->JOBPARENT;
									$JDESCH_4	= strtoupper(wordwrap($rw_JIDH_4->JOBDESC, 60, "<br>", TRUE));
									$TABMAX_4 	= $TABMAX_3-10;
									$JSHOWD_4	= "<div style='margin-left: ".$TABMAX_4."px; font-style: italic; white-space:nowrap'>
												  		<label style='white-space:nowrap'>$JCODE_4 : $JDESCH_4</label>
												  	</div>";
									$JCODEH_5 	= "";
									$JDESCH_5 	= "";
									$JSHOWD_5 	= "";
									$s_JIDH_5 	= "SELECT JOBCODEID, JOBPARENT, JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JCODEH_4' AND PRJCODE = '$PRJCODE'";
									$r_JIDH_5		= $this->db->query($s_JIDH_5)->result();
									foreach($r_JIDH_5 as $rw_JIDH_5):
										$JCODE_5	= $rw_JIDH_5->JOBCODEID;
										$JCODEH_5	= $rw_JIDH_5->JOBPARENT;
										$JDESCH_5	= strtoupper(wordwrap($rw_JIDH_5->JOBDESC, 60, "<br>", TRUE));
										$TABMAX_5 	= $TABMAX_4-10;
										$JSHOWD_5	= "<div style='margin-left: ".$TABMAX_5."px; font-style: italic; white-space:nowrap'>
													  		<label style='white-space:nowrap'>$JCODE_5 : $JDESCH_5</label>
													  	</div>";
										$JCODEH_6 	= "";
										$JDESCH_6 	= "";
										$JSHOWD_6 	= "";
										$s_JIDH_6 	= "SELECT JOBCODEID, JOBPARENT, JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JCODEH_5' AND PRJCODE = '$PRJCODE'";
										$r_JIDH_6		= $this->db->query($s_JIDH_6)->result();
										foreach($r_JIDH_6 as $rw_JIDH_6):
											$JCODE_6	= $rw_JIDH_6->JOBCODEID;
											$JCODEH_6	= $rw_JIDH_6->JOBPARENT;
											$JDESCH_6	= strtoupper(wordwrap($rw_JIDH_6->JOBDESC, 60, "<br>", TRUE));
											$TABMAX_6 	= $TABMAX_5-10;
											$JSHOWD_6	= "<div style='margin-left: ".$TABMAX_6."px; font-style: italic; white-space:nowrap'>
														  		<label style='white-space:nowrap'>$JCODE_6 : $JDESCH_6</label>
														  	</div>";
											$JCODEH_7 	= "";
											$JDESCH_7 	= "";
											$JSHOWD_7 	= "";
											$s_JIDH_7 	= "SELECT JOBCODEID, JOBPARENT, JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JCODEH_6' AND PRJCODE = '$PRJCODE'";
											$r_JIDH_7		= $this->db->query($s_JIDH_7)->result();
											foreach($r_JIDH_7 as $rw_JIDH_7):
												$JCODE_7	= $rw_JIDH_7->JOBCODEID;
												$JCODEH_7	= $rw_JIDH_7->JOBPARENT;
												$JDESCH_7	= strtoupper(wordwrap($rw_JIDH_7->JOBDESC, 60, "<br>", TRUE));
												$TABMAX_7 	= $TABMAX_6-10;
												$JSHOWD_7	= "<div style='margin-left: ".$TABMAX_7."px; font-style: italic; white-space:nowrap'>
															  		<label style='white-space:nowrap'>$JCODE_7 : $JDESCH_7</label>
															  	</div>";
												$JCODEH_8 	= "";
												$JDESCH_8 	= "";
												$JSHOWD_8 	= "";
												$s_JIDH_8 	= "SELECT JOBCODEID, JOBPARENT, JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JCODEH_7' AND PRJCODE = '$PRJCODE'";
												$r_JIDH_8		= $this->db->query($s_JIDH_8)->result();
												foreach($r_JIDH_8 as $rw_JIDH_8):
													$JCODE_8	= $rw_JIDH_8->JOBCODEID;
													$JCODEH_8	= $rw_JIDH_8->JOBPARENT;
													$JDESCH_8	= strtoupper(wordwrap($rw_JIDH_8->JOBDESC, 60, "<br>", TRUE));
													$TABMAX_8 	= $TABMAX_7-10;
													$JSHOWD_8	= "<div style='margin-left: ".$TABMAX_8."px; font-style: italic; white-space:nowrap'>
																  		<label style='white-space:nowrap'>$JCODE_8 : $JDESCH_8</label>
																  	</div>";
													$JCODEH_9 	= "";
													$JDESCH_9 	= "";
													$JSHOWD_9 	= "";
													$s_JIDH_9 	= "SELECT JOBCODEID, JOBPARENT, JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JCODEH_8' AND PRJCODE = '$PRJCODE'";
													$r_JIDH_9		= $this->db->query($s_JIDH_9)->result();
													foreach($r_JIDH_9 as $rw_JIDH_9):
														$JCODE_9	= $rw_JIDH_9->JOBCODEID;
														$JCODEH_9	= $rw_JIDH_9->JOBPARENT;
														$JDESCH_9	= strtoupper(wordwrap($rw_JIDH_9->JOBDESC, 60, "<br>", TRUE));
														$TABMAX_9 	= $TABMAX_8-10;
														$JSHOWD_9	= "<div style='margin-left: ".$TABMAX_9."px; font-style: italic; white-space:nowrap'>
																	  		<label style='white-space:nowrap'>$JCODE_9 : $JDESCH_9</label>
																	  	</div>";
														$JCODEH_10 	= "";
														$JDESCH_10 	= "";
														$JSHOWD_10 	= "";
														$s_JIDH_10 	= "SELECT JOBCODEID, JOBPARENT, JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JCODEH_9' AND PRJCODE = '$PRJCODE'";
														$r_JIDH_10		= $this->db->query($s_JIDH_10)->result();
														foreach($r_JIDH_10 as $rw_JIDH_10):
															$JCODE_10	= $rw_JIDH_10->JOBCODEID;
															$JCODEH_10	= $rw_JIDH_10->JOBPARENT;
															$JDESCH_10	= strtoupper(wordwrap($rw_JIDH_10->JOBDESC, 60, "<br>", TRUE));
															$TABMAX_10 	= $TABMAX_9-10;
															$JSHOWD_10	= "<div style='margin-left: ".$TABMAX_10."px; font-style: italic; white-space:nowrap'>
																		  		<label style='white-space:nowrap'>$JCODE_10 : $JDESCH_10</label>
																		  	</div>";
														endforeach;
													endforeach;
												endforeach;
											endforeach;
										endforeach;
									endforeach;
								endforeach;
							endforeach;
						endforeach;
					endforeach;

				$ITM_NAME1 	= wordwrap($ITM_NAME, 60, "<br>", TRUE);
				$ITM_NAMEV	= "$ITM_CODE : $ITM_NAME1<br><i style='font-style: italic;'></i>";

				if($disITM == 1)
				{
					$chkBox	= "";
					$fntBld = "'";
				}
				else
				{
					/*$chkBox	= "<input type='checkbox' name='chk3' value='".$JOBID_SEL."|".$JOBCODEID."|".$JOBPARENT."|".$JDESCH_1."|".$ITM_UNIT."|".$nwJID."|".$ITM_REMVOL."|".$ITM_PRICE."|".$ITM_CODEH."' onClick='pickThis3(this);'/>";*/
					$JOBPARENTD 	= $JDESCH_1;
					$chkBox			= "<input type='checkbox' name='chk3' value='".$JOBID_SEL."|".$JOBCODEID."|".$JOBPARENT."|".$JOBPARENTD."|".$ITM_CODE."|".$ITM_NAME."|".$ITM_UNIT."|".$ITM_REMVOL."|".$ITM_REMVAL."|".$ITM_PRICE."' onClick='pickThis3(this);'/>";
					$fntBld 		= " style='font-weight:bold;'";
				}

				$output['data'][] 	= array("$chkBox",
											"<div><span ".$fntBld.">".$ITM_NAMEV."</span>$JSHOWD_3$JSHOWD_2$JSHOWD_1</div>",
											"$ITM_UNIT",
											"<div><label><span class='label label-success' style='font-size:12px'>".number_format($TOT_BUDGVOL, 2)." (V)</span></label></div>
											<div><label><span class='label label-warning' style='font-size:12px'>".number_format($TOT_BUDGVAL, 2)." (J)</span></label></div>",
											"<div><label><span class='label label-success' style='font-size:12px'>".number_format($REQ_VOLM, 2)." (V)</span></label></div>
											<div><label><span class='label label-warning' style='font-size:12px'>".number_format($REQ_AMOUNT, 2)." (J)</span></label></div>",
											"<div><label><span class='label label-success' style='font-size:12px'>".number_format($ITM_REMVOL, 2)." (V)</span></label></div>
											<div><label><span class='label label-warning' style='font-size:12px'>".number_format($ITM_REMVAL, 2)." (J)</span></label></div>");

				$noU			= $noU + 1;
			}
			//$output['data'][] 	= array("$PRJCODE","","");
			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

  	function get_AllDataITM() // GOOD
	{
		//$PRJCODE		= $_GET['id'];

		//setlocale(LC_ALL, 'id-ID', 'id_ID');
		setlocale(LC_ALL, 'en_EN');

		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		$collID		= $_GET['id'];
		$exlID		= explode("~", $collID);
		$PRJCODE 	= $exlID[0];
		$AMD_CATEG 	= $exlID[1];
		$JPARENT 	= $exlID[2];

		$LangID     = $this->session->userdata['LangID'];
        
        $sqlTransl      = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
        $resTransl      = $this->db->query($sqlTransl)->result();
        foreach($resTransl as $rowTransl) :
            $TranslCode = $rowTransl->MLANG_CODE;
            $LangTransl = $rowTransl->LangTransl;

    		if($TranslCode == 'Director')$Director = $LangTransl;
            if($TranslCode == 'Compailer')$Compailer = $LangTransl;
            if($TranslCode == 'Location')$Location = $LangTransl;
    		if($TranslCode == 'Active')$Active = $LangTransl;
    		if($TranslCode == 'Inactive')$Inactive = $LangTransl;
    		if($TranslCode == 'Contact')$Contact = $LangTransl;
    		if($TranslCode == 'CompanyName')$CompanyName = $LangTransl;
    		if($TranslCode == 'Budget')$Budget = $LangTransl;
    		if($TranslCode == 'Phone')$Phone = $LangTransl;
    		if($TranslCode == 'Notes')$Notes = $LangTransl;
        endforeach;
		
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
									"JOBDESC");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_project_amd->get_AllDataITMC($PRJCODE, $AMD_CATEG, $JPARENT, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			
			$query 			= $this->m_project_amd->get_AllDataITML($PRJCODE, $AMD_CATEG, $JPARENT, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			$TotBOQ			= 0;
			$TotBUD			= 0;
			$TotADD			= 0;
			$TotADD2		= 0;
			$TotADD3		= 0;
			$TotADD4		= 0;
			$TotADD5		= 0;
			$TotALL			= 0;
			$TotREM			= 0;
			$REMAIN2		= 0;
			$TotUSE			= 0;
			$TotPC			= 0;	// Total Project Complete
			foreach ($query->result_array() as $dataI) 
			{
				$JOBCODEDET 	= $dataI['JOBCODEDET'];
				$JOBCODEID 		= $dataI['JOBCODEID'];
				$JOBPARENT 		= $dataI['JOBPARENT'];
				$JOBCODE 		= $dataI['JOBCODE'];
				$JOBDESC		= $dataI['JOBDESC'];
				$ITM_GROUP		= $dataI['ITM_GROUP'];
				$ITM_CODE		= $dataI['ITM_CODE'];
				$ITM_UNIT		= $dataI['ITM_UNIT'];
				$JOBUNIT 		= strtoupper($dataI['ITM_UNIT']);
				if($JOBUNIT == '')
					$JOBUNIT= 'LS';
				$JOBLEV			= $dataI['IS_LEVEL'];

				$JOBVOLM		= $dataI['ITM_VOLM'];
				$JOBPRICE		= $dataI['ITM_PRICE'];
				$ITM_LASTP		= $dataI['ITM_LASTP'];
				$ADD_VOLM 		= $dataI['ADD_VOLM'];
				$ADD_PRICE		= $dataI['ADD_PRICE'];
				$JOBCOST		= $dataI['ITM_BUDG'];
				$REQ_VOLM		= $dataI['REQ_VOLM'];
				$REQ_AMOUNT		= $dataI['REQ_AMOUNT'];
				$PO_VOLM		= $dataI['PO_VOLM'];
				$PO_AMOUNT		= $dataI['PO_AMOUNT'];
				$IR_VOLM		= $dataI['IR_VOLM'];
				$IR_AMOUNT		= $dataI['IR_AMOUNT'];
				$ITM_USED		= $dataI['ITM_USED'];
				$ITM_USED_AM	= $dataI['ITM_USED_AM'];
				$ITM_STOCK		= $dataI['ITM_STOCK'];
				$ITM_STOCK_AM	= $dataI['ITM_STOCK_AM'];
				$IS_LEVEL		= $dataI['IS_LEVEL'];
				$ISLAST			= $dataI['ISLAST'];

				$serialNumber	= '';
				$itemConvertion	= 1;
				$TOTBUD_VOLM 	= $JOBVOLM + $ADD_VOLM;
				$TOTBUD_AMOUNT	= ($JOBPRICE * $JOBVOLM) + ($ADD_VOLM * $ADD_PRICE);

				// LS PROCESUDE
					$s_isLS 	= "tbl_unitls WHERE ITM_UNIT IN (SELECT ITM_UNIT FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE')";
					$r_isLS 	= $this->db->count_all($s_isLS);
					if($r_isLS == 1)
					{
						$JOBVOLM		= $dataI['ITM_VOLM'] * $dataI['ITM_PRICE'];
						$JOBPRICE		= 1;
						$ITM_LASTP		= 1;
						$ADD_VOLM 		= $dataI['ADD_VOLM'] * $dataI['ADD_PRICE'];
						$ADD_PRICE		= 1;
						$JOBCOST		= $JOBVOLM;
						$REQ_VOLM		= $dataI['REQ_AMOUNT'];
						$REQ_AMOUNT		= 1;
						$PO_VOLM		= $dataI['PO_AMOUNT'];
						$PO_AMOUNT		= 1;
						$IR_VOLM		= $dataI['IR_AMOUNT'];
						$IR_AMOUNT		= 1;
						$ITM_USED		= $dataI['ITM_USED_AM'];
						$ITM_USED_AM	= 1;
						$ITM_STOCK		= $dataI['ITM_STOCK_AM'];
						$ITM_STOCK_AM	= 1;

						$serialNumber	= '';
						$itemConvertion	= 1;
						$TOTBUD_VOLM 	= $JOBVOLM + $ADD_VOLM;
						$TOTBUD_AMOUNT	= 1;
					}

				// BUDGET COLUMN
					$JOBVOLMV 		= number_format($JOBVOLM, 2);

					$ADDVOL_VW 	= "";
					if($ADD_VOLM > 0)
					{
						$ADDVOL_VW 	= 	"<div>
											<p class='text-yellow'><i class='glyphicon glyphicon-triangle-top'></i>
									  		".number_format($ADD_VOLM, 2)."</p>
									  	</div>";
					}

				// REQUEST COLUMN
					$TOT_REQV 		= number_format($REQ_VOLM, 2);

				// REMAIN COLUMN
					$REMBUDG_VOL	= $TOTBUD_VOLM - $REQ_VOLM;
					$REMBUDG_AMN	= $TOTBUD_AMOUNT - $REQ_AMOUNT;
					$REMBUDG_VOLV 	= number_format($REMBUDG_VOL, 2);

					$disabledB 		= 0;
					if($REMBUDG_VOL <= 0)
						$disabledB	= 1;

					if($disabledB == 0)
						$statRem = number_format($REMBUDG_VOL, 2);
					else
						$statRem = "<span class='label label-danger' style='font-size:12px'>".number_format($REMBUDG_VOL, 2)."</span>";

					$CELL_COL	= "style='white-space:nowrap'";

				// OTHER SETT
					$chkBox		= "<input type='checkbox' name='chk1' value='".$JOBCODEDET."|".$JOBCODEID."|".$JOBCODE."|".$PRJCODE."|".$ITM_CODE."|".$JOBDESC."|".$serialNumber."|".$ITM_UNIT."|".$JOBPRICE."|".$TOTBUD_VOLM."|".$ITM_STOCK."|".$ITM_USED."|".$itemConvertion."|".$TOTBUD_AMOUNT."|".$REMBUDG_VOL."|".$PO_AMOUNT."|".$TOTBUD_AMOUNT."|".$REQ_VOLM."|".$ITM_GROUP."|".$JPARENT."' onClick='pickThis1(this);'/>";

					$JOBDESC 		= wordwrap($JOBDESC, 60, "<br>", TRUE);

				$output['data'][] 	= array("<div style='text-align:center;'>".$chkBox."</div>",
											"<div style='white-space:nowrap'>$JOBDESC</div>
										  	<div style='margin-left: 10px; font-style: italic; white-space:nowrap'>
										  		<label style='white-space:nowrap'>Kode : ".$ITM_CODE."</label>
										  	</div>",
											"<div style='text-align:center;'><span ".$CELL_COL.">".$JOBUNIT."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".$JOBVOLMV.$ADDVOL_VW."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".$TOT_REQV."</span></div>",
											"<div style='text-align:right;'>".$statRem."</div>");

				$noU			= $noU + 1;
			}

			/*$output['data'][] 	= array("A",
										"B",
										"C",
										"D",
										"E",
										"F");*/
			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

  	function get_AllDataITMH() // GOOD
	{
		$PRJCODE		= $_GET['id'];

		//setlocale(LC_ALL, 'id-ID', 'id_ID');
		setlocale(LC_ALL, 'en_EN');

		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		/*$collID		= $_GET['id'];
		$exlID		= explode("~", $collID);
		$PRJCODE 	= $exlID[0];
		$AMD_CATEG 	= $exlID[1];
		$JPARENT 	= $exlID[2];*/

		$LangID     = $this->session->userdata['LangID'];
        
        $sqlTransl      = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
        $resTransl      = $this->db->query($sqlTransl)->result();
        foreach($resTransl as $rowTransl) :
            $TranslCode = $rowTransl->MLANG_CODE;
            $LangTransl = $rowTransl->LangTransl;

    		if($TranslCode == 'Director')$Director = $LangTransl;
            if($TranslCode == 'Compailer')$Compailer = $LangTransl;
            if($TranslCode == 'Location')$Location = $LangTransl;
    		if($TranslCode == 'Active')$Active = $LangTransl;
    		if($TranslCode == 'Inactive')$Inactive = $LangTransl;
    		if($TranslCode == 'Contact')$Contact = $LangTransl;
    		if($TranslCode == 'CompanyName')$CompanyName = $LangTransl;
    		if($TranslCode == 'Budget')$Budget = $LangTransl;
    		if($TranslCode == 'Phone')$Phone = $LangTransl;
    		if($TranslCode == 'Notes')$Notes = $LangTransl;
        endforeach;
		
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

			$columns_valid 	= array("ITM_NAME",
									"ITM_NAME",
									"ITM_UNIT",
									"ITM_VOLMBG",
									"PR_VOLM");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_project_amd->get_AllDataITMHC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			
			$query 			= $this->m_project_amd->get_AllDataITMHL($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			$TotBOQ			= 0;
			$TotBUD			= 0;
			$TotADD			= 0;
			$TotADD2		= 0;
			$TotADD3		= 0;
			$TotADD4		= 0;
			$TotADD5		= 0;
			$TotALL			= 0;
			$TotREM			= 0;
			$REMAIN2		= 0;
			$TotUSE			= 0;
			$TotPC			= 0;	// Total Project Complete
			foreach ($query->result_array() as $dataI) 
			{
				$ITM_CODE		= $dataI['ITM_CODE'];
				$ITM_GROUP		= $dataI['ITM_GROUP'];
				$ITM_CATEG		= $dataI['ITM_CATEG'];
				$ITM_NAME		= $dataI['ITM_NAME'];
				$ITM_UNIT 		= strtoupper($dataI['ITM_UNIT']);
				if($ITM_UNIT == '')
					$ITM_UNIT= 'LS';

				$ITM_VOLMBG		= $dataI['ITM_VOLMBG'];
				$ITM_PRICE		= $dataI['ITM_PRICE'];
				$ITM_TOTALP		= $dataI['ITM_TOTALP'];
				$PR_VOLM		= $dataI['PR_VOLM'];
				$PR_AMOUNT 		= $dataI['PR_AMOUNT'];
				$ADDVOLM		= $dataI['ADDVOLM'];
				$ADDCOST		= $dataI['ADDCOST'];

				// LS PROCESUDE
					$ITM_VOLMBGV 	= $ITM_VOLMBG;
					$ADDVOLMV 		= $ADDVOLM;
					$PR_VOLMV 		= $PR_VOLM;
					$s_isLS 		= "tbl_unitls WHERE ITM_UNIT IN (SELECT ITM_UNIT FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE')";
					$r_isLS 		= $this->db->count_all($s_isLS);
					if($r_isLS == 1)
					{
						$ITM_VOLMBGV	= $ITM_TOTALP;
						$ADDVOLMV 		= $ADDCOST;
						$PR_VOLMV 		= $PR_AMOUNT;
					}
					$ITM_REMV 			= $ITM_VOLMBGV + $ADDVOLMV - $PR_VOLMV;

				// BUDGET COLUMN
					$JOBVOLMV 		= number_format($ITM_VOLMBGV, 2);

					$ADDVOL_VW 		= "";
					if($ADDVOLM > 0)
					{
						$ADDVOL_VW 	= 	"<div>
											<p class='text-yellow'><i class='glyphicon glyphicon-triangle-top'></i>
									  		".number_format($ADDVOLM, 2)."</p>
									  	</div>";
					}

				// REQUEST COLUMN
					$TOT_REQV 		= number_format($PR_VOLMV, 2);

				// REMAIN COLUMN
					$REMBUDG_VOLV 	= number_format($ITM_REMV, 2);


				// EN/DISABLED SETTING
					$disabledB 		= 0;
					if($ITM_REMV <= 0)
						$disabledB	= 1;

					if($disabledB == 0)
						$statRem = number_format($ITM_REMV, 2);
					else
						$statRem = "<span class='label label-danger' style='font-size:12px'>".number_format($ITM_REMV, 2)."</span>";

					$CELL_COL	= "style='white-space:nowrap'";

				// OTHER SETT
					//$chkBox	= "<input type='radio' name='chk1' value='".$ITM_CODE."|".$ITM_NAME."|".$ITM_GROUP."' onClick='pickThis1(this);'/>";
					$chkBox		= "<input type='radio' name='chk1' value='".$ITM_CODE."|".$ITM_NAME."|".$ITM_UNIT."' onClick='pickThis1(this);'/>";

					$ITM_NAMEV 	= wordwrap($ITM_NAME, 60, "<br>", TRUE);

				$output['data'][] 	= array("<div style='text-align:center;'>".$chkBox."</div>",
											"<div style='white-space:nowrap'>$ITM_NAMEV</div>
										  	<div style='margin-left: 10px; font-style: italic; white-space:nowrap'>
										  		<label style='white-space:nowrap'>Kode : ".$ITM_CODE."</label>
										  	</div>",
											"<div style='text-align:center;'><span ".$CELL_COL.">".$ITM_UNIT."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".$JOBVOLMV.$ADDVOL_VW."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".$TOT_REQV."</span></div>",
											"<div style='text-align:right;'>".$statRem."</div>");

				$noU			= $noU + 1;
			}

			/*$output['data'][] 	= array("A",
										"B",
										"C",
										"D",
										"E",
										"F");*/
			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

  	function get_AllDataSI() // GOOD
	{
		//$PRJCODE		= $_GET['id'];

		//setlocale(LC_ALL, 'id-ID', 'id_ID');
		setlocale(LC_ALL, 'en_EN');

		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		$PRJCODE		= $_GET['id'];

		$LangID     = $this->session->userdata['LangID'];
		
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
									"SI_MANNO",
									"SI_DATE",
									"SI_STEP",
									"SI_DESC",
									"SI_VALUE");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_project_amd->get_AllDataSIC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			
			$query 			= $this->m_project_amd->get_AllDataSIL($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			$TotBOQ			= 0;
			$TotBUD			= 0;
			$TotADD			= 0;
			$TotADD2		= 0;
			$TotADD3		= 0;
			$TotADD4		= 0;
			$TotADD5		= 0;
			$TotALL			= 0;
			$TotREM			= 0;
			$REMAIN2		= 0;
			$TotUSE			= 0;
			$TotPC			= 0;	// Total Project Complete
			foreach ($query->result_array() as $dataI) 
			{
				$SI_CODE 		= $dataI['SI_CODE'];
				$SI_CODE 		= $dataI['SI_CODE'];
				$SI_MANNO 		= $dataI['SI_MANNO'];
				$SI_STEP 		= $dataI['SI_STEP'];
				$SI_OWNER		= $dataI['SI_OWNER'];
				$SI_DATE		= $dataI['SI_DATE'];
				$SI_DATED		= date('d M Y', strtotime($SI_DATE));
				$SI_ENDDATE		= $dataI['SI_ENDDATE'];
				$SI_ENDDATED	= date('d M Y', strtotime($SI_ENDDATE));
				$SI_DESC		= $dataI['SI_DESC'];
				$SI_VALUE		= $dataI['SI_VALUE'];
				$SI_APPVAL		= $dataI['SI_APPVAL'];
				
				$CELL_COL		= "style='white-space:nowrap'";

				$chkBox			= "<input type='checkbox' name='chk2' value='".$SI_CODE."|".$SI_MANNO."|".$SI_VALUE."|".$SI_APPVAL."' onClick='pickThis2(this);'/>";

				$output['data'][] 	= array("<div style='text-align:center;'>".$chkBox."</div>",
											"<div>
										  		<p><span ".$CELL_COL.">".$SI_MANNO."</span></p>
										  	</div>",
											"<div><span ".$CELL_COL.">".$SI_DATED."</span></div>",
											"<div><span ".$CELL_COL.">".$SI_STEP."</span></div>",
											"<div>".$SI_DESC."</div>",
											"<div>".number_format($SI_VALUE, 2)."</div>");

				$noU			= $noU + 1;
			}

			/*$output['data'][] 	= array("A",
										"B",
										"C",
										"D",
										"E",
										"F");*/
			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

  	function get_AllDataJLH() // GOOD
	{
		//$PRJCODE		= $_GET['id'];

		//setlocale(LC_ALL, 'id-ID', 'id_ID');
		setlocale(LC_ALL, 'en_EN');

		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		$PRJCODE	= $_GET['id'];

		$LangID     = $this->session->userdata['LangID'];
        
        $sqlTransl      = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
        $resTransl      = $this->db->query($sqlTransl)->result();
        foreach($resTransl as $rowTransl) :
            $TranslCode = $rowTransl->MLANG_CODE;
            $LangTransl = $rowTransl->LangTransl;

    		if($TranslCode == 'Director')$Director = $LangTransl;
            if($TranslCode == 'Compailer')$Compailer = $LangTransl;
            if($TranslCode == 'Location')$Location = $LangTransl;
    		if($TranslCode == 'Active')$Active = $LangTransl;
    		if($TranslCode == 'Inactive')$Inactive = $LangTransl;
    		if($TranslCode == 'Contact')$Contact = $LangTransl;
    		if($TranslCode == 'CompanyName')$CompanyName = $LangTransl;
    		if($TranslCode == 'Budget')$Budget = $LangTransl;
    		if($TranslCode == 'Phone')$Phone = $LangTransl;
    		if($TranslCode == 'Notes')$Notes = $LangTransl;
        endforeach;
		
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
			
			$columns_valid 	= array("JOBCODEID", 
									"JOBDESC");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_project_amd->get_AllDataJLHC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			
			$query 			= $this->m_project_amd->get_AllDataJLHL($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			$TotBOQ			= 0;
			$TotBUD			= 0;
			$TotADD			= 0;
			$TotADD2		= 0;
			$TotADD3		= 0;
			$TotADD4		= 0;
			$TotADD5		= 0;
			$TotALL			= 0;
			$TotREM			= 0;
			$REMAIN2		= 0;
			$TotUSE			= 0;
			$TotPC			= 0;	// Total Project Complete
			foreach ($query->result_array() as $dataI) 
			{
				$ORD_ID 		= $dataI['ORD_ID'];	
				$JOBCODEID 		= $dataI['JOBCODEID'];
				$JOBDESC		= $dataI['JOBDESC'];
				$ITM_UNIT		= $dataI['JOBUNIT'];
				$JOBLEV			= $dataI['JOBLEV'];
				$ISLAST			= $dataI['ISLAST'];

				// SPACE
					if($JOBLEV == 1)
						$spaceLev 	= "";
					elseif($JOBLEV == 2)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 3)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 4)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 5)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 6)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 7)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 8)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 9)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 10)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 11)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 12)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

					$chkBox	= "<input type='radio' name='chk0' value='".$ORD_ID."|".$JOBCODEID."|".$JOBDESC."|".$JOBLEV."' onClick='pickThis0(this);'/>";
					$fntBld = " style='font-weight:bold;'";

					$strLENH 	= strlen($JOBDESC);
					$JOBDESC	= substr("$JOBDESC", 0, 100);
					$JOBDESC 	= $JOBDESC;
					if($strLENH > 100)
						$JOBDESC 	= $JOBDESC."...";

					$JobView		= "$spaceLev $JOBCODEID - $JOBDESC";

				/*$output['data'][] 	= array("<div style='text-align:center;'>".$chkBox."</div>",
											"<div>
										  		<p><span ".$CELL_COL.">".$JobView."</span></p>
										  	</div>
										  	<div style='margin-left: 15px; font-style: italic;'>
										  		<i class='text-muted fa fa-rss'>&nbsp;&nbsp;".$JDESCH_1."
										  	</div>",
											"<div style='text-align:center;'><span ".$CELL_COL.">".$JOBUNITV."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".$JOBVOLMV."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".$TOT_REQV."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".$PO_VOLMV."</span></div>",
											"<div style='text-align:right;'>".$statRem."</div>");*/

				$output['data'][] 	= array($chkBox,
											"<div><span ".$fntBld.">".$JobView."</span></div>");

				$noU			= $noU + 1;
			}
			//$output['data'][] 	= array("$PRJCODE","","");
			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
 	function c_am1h0db2_Pek() // OK
	{
		$this->load->model('m_company/m_project_amd/m_project_amd', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;
		
		$url			= site_url('c_comprof/c_am1h0db2/ix1h0db2_Pek/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function ix1h0db2_Pek() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$LangID 	= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN037';
				$data["MenuApp"] 	= 'MN038';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;

			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN406';
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_comprof/c_am1h0db2/gall1h0db2amd_Pek/?id=";
			
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
	
	function gall1h0db2amd_Pek() // OK
	{
		$this->load->model('m_company/m_project_amd/m_project_amd', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN037';
			$data["MenuApp"] 	= 'MN038';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$EmpID 		= $this->session->userdata('Emp_ID');
			
			// -------------------- START : SEARCHING METHOD --------------------
				
				$collDATA		= $_GET['id'];
				$EXP_COLLD1		= $this->url_encryption_helper->decode_url($collDATA);	
				$EXP_COLLD		= explode('~', $EXP_COLLD1);	
				$C_COLLD1		= count($EXP_COLLD);
				
				if($C_COLLD1 > 1)
				{
					$EXP_COLLD1	= str_replace('~', '', $EXP_COLLD1);
					$key		= $EXP_COLLD[0];
					$PRJCODE	= $EXP_COLLD[1];
					$mxLS		= $EXP_COLLD[2];
					$end		= $EXP_COLLD[3];
					$start		= 0;
				}
				else
				{
					$key		= '';
					$PRJCODE	= $EXP_COLLD1;
					$start		= 0;
					$end		= 50;
				}
				$data["url_search"] = site_url('c_comprof/c_am1h0db2/f4n7_5rcH/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
				//$num_rows 			= $this->m_project_amd->count_all_amd($PRJCODE, $key);
				//$data["cData"] 		= $num_rows;	 
				//$data['vData']		= $this->m_project_amd->get_all_amd($PRJCODE, $start, $end, $key)->result();
			// -------------------- END : SEARCHING METHOD --------------------
			
			$data['title'] 		= $appName;
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h2_title"] 	= "Amandemen";
				$data['h3_title']	= "anggaran proyek";
			}
			else
			{
				$data["h2_title"] 	= "Amendment";
				$data['h3_title']	= "project budget";
			}
			$data['PRJCODE']	= $PRJCODE;
			$data["MenuCode"] 	= 'MN037';
			$data['backURL'] 	= site_url('c_comprof/c_am1h0db2/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
						
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN406';
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
			
			$this->load->view('v_company/v_project_amd_pek/v_amd_list', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

  	function get_AllData_pek() // GOOD
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
									"AMD_CODE", 
									"AMD_DATE", 
									"JOBDESC", 
									"AMD_NOTES", 
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
			$num_rows 		= $this->m_project_amd->get_AllDataC_pek($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_project_amd->get_AllDataL_pek($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{

				$AMD_ID 	= $dataI['AMD_ID'];
				$AMD_NUM 	= $dataI['AMD_NUM'];
				$AMD_CODE 	= $dataI['AMD_CODE'];
				$AMD_DATE 	= $dataI['AMD_DATE'];
				$AMD_DATEV	= date('d M Y', strtotime($AMD_DATE));
				$PRJCODE	= $dataI['PRJCODE'];
				$AMD_DESC	= $dataI['AMD_DESC'];
				$AMD_NOTES 	= $dataI['AMD_NOTES'];
				$AMD_MEMO 	= $dataI['AMD_MEMO'];
				$AMD_AMOUNT = $dataI['AMD_AMOUNT'];
				$AMD_STAT	= $dataI['AMD_STAT'];
				$AMD_JOBID	= $dataI['AMD_JOBID'];			// ITEM_CODE
				$JOBDESC	= $dataI['JOBDESC'];			// ITM_NAME
				$STATDESC	= $dataI['STATDESC'];
				$STATCOL	= $dataI['STATCOL'];
				$CREATERNM	= $dataI['CREATERNM'];
				$empName	= cut_text2 ("$CREATERNM", 15);
				
				$CollID 	= "$PRJCODE~$AMD_NUM";
				$secUpd		= site_url('c_comprof/c_am1h0db2/updateAMD_pek/?id='.$this->url_encryption_helper->encode_url($CollID));
				$secPrint	= site_url('c_comprof/c_am1h0db2/p_R1n7/?id='.$this->url_encryption_helper->encode_url($AMD_NUM));
				$CollID		= "AMD~$AMD_NUM~$PRJCODE";
				$secDelURL = base_url().'index.php/__l1y/trashDOC/?id=';
				$delID 		= "$secDelURL~tbl_amd_header~tbl_amd_detail~AMD_NUM~$AMD_NUM~PRJCODE~$PRJCODE";
                                    
				if($AMD_STAT == 1 || $AMD_STAT == 4) 
				{
					$secAction	= 	"<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete'>
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
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' title='Delete' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
								
				if($JOBDESC == '')
					$JOBDESC	= $AMD_DESC;
					
				$output['data'][] = array("$noU.",
										  "<div style='white-space:nowrap'>".$dataI['AMD_CODE']."</div>",
										  $AMD_DATEV,
										  $AMD_JOBID." : ".$JOBDESC,
										  $AMD_NOTES,
										  "<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
										  $secAction);
				$noU		= $noU + 1;
			}

			/*$output['data'][] 	= array("A = $query",
										"B",
										"C",
										"D",
										"E",
										"F",
										"F");*/

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function i180dahdd_pek() // OK
	{
		$this->load->model('m_company/m_project_amd/m_project_amd', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE		= $_GET['id'];
			$PRJCODE		= $this->url_encryption_helper->decode_url($PRJCODE);
			$EmpID 			= $this->session->userdata('Emp_ID');
			
			$docPatternPosition 	= 'Especially';	
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			$data['form_action']	= site_url('c_comprof/c_am1h0db2/addamd_process_pek');
			
			// Untuk penomoran secara systemik
			$data['PRJCODE'] 		= $PRJCODE;
			
			// GET MENU DESC
				$mnCode				= 'MN037';
				$data["MenuApp"] 	= 'MN038';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$MenuCode 				= 'MN037';
			$data["MenuCode"] 		= 'MN037';
			$data['viewDocPattern'] = $this->m_project_amd->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN406';
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
	
			$this->load->view('v_company/v_project_amd_pek/v_amd_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

  	function get_AllDataITM_pek() // GOOD
	{
		//$PRJCODE		= $_GET['id'];

		//setlocale(LC_ALL, 'id-ID', 'id_ID');
		setlocale(LC_ALL, 'en_EN');

		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		$collID		= $_GET['id'];
		$exlID		= explode("~", $collID);
		$PRJCODE 	= $exlID[0];
		$AMD_CATEG 	= $exlID[1];
		$JPARENT 	= $exlID[2];

		$LangID     = $this->session->userdata['LangID'];
        
        $sqlTransl      = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
        $resTransl      = $this->db->query($sqlTransl)->result();
        foreach($resTransl as $rowTransl) :
            $TranslCode = $rowTransl->MLANG_CODE;
            $LangTransl = $rowTransl->LangTransl;

    		if($TranslCode == 'Director')$Director = $LangTransl;
            if($TranslCode == 'Compailer')$Compailer = $LangTransl;
            if($TranslCode == 'Location')$Location = $LangTransl;
    		if($TranslCode == 'Active')$Active = $LangTransl;
    		if($TranslCode == 'Inactive')$Inactive = $LangTransl;
    		if($TranslCode == 'Contact')$Contact = $LangTransl;
    		if($TranslCode == 'CompanyName')$CompanyName = $LangTransl;
    		if($TranslCode == 'Budget')$Budget = $LangTransl;
    		if($TranslCode == 'Phone')$Phone = $LangTransl;
    		if($TranslCode == 'Notes')$Notes = $LangTransl;
        endforeach;
		
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
									"JOBDESC");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_project_amd->get_AllDataITMC_pek($PRJCODE, $AMD_CATEG, $JPARENT, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			
			$query 			= $this->m_project_amd->get_AllDataITML_pek($PRJCODE, $AMD_CATEG, $JPARENT, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			$TotBOQ			= 0;
			$TotBUD			= 0;
			$TotADD			= 0;
			$TotADD2		= 0;
			$TotADD3		= 0;
			$TotADD4		= 0;
			$TotADD5		= 0;
			$TotALL			= 0;
			$TotREM			= 0;
			$REMAIN2		= 0;
			$TotUSE			= 0;
			$TotPC			= 0;	// Total Project Complete
			foreach ($query->result_array() as $dataI) 
			{
				$JOBCODEDET 	= $dataI['JOBCODEDET'];
				$JOBCODEID 		= $dataI['JOBCODEID'];
				$JOBPARENT 		= $dataI['JOBPARENT'];
				$JOBCODE 		= $dataI['JOBCODE'];
				$JOBDESC		= $dataI['JOBDESC'];
				$ITM_GROUP		= $dataI['ITM_GROUP'];
				$ITM_CODE		= $dataI['ITM_CODE'];
				$ITM_UNIT		= $dataI['ITM_UNIT'];
				$JOBUNIT 		= strtoupper($dataI['ITM_UNIT']);
				if($JOBUNIT == '')
					$JOBUNIT= 'LS';
				$JOBLEV			= $dataI['IS_LEVEL'];

				$JOBVOLM		= $dataI['ITM_VOLM'];
				$JOBPRICE		= $dataI['ITM_PRICE'];
				$ITM_LASTP		= $dataI['ITM_LASTP'];
				$ADD_VOLM 		= $dataI['ADD_VOLM'];
				$ADD_PRICE		= $dataI['ADD_PRICE'];
				$JOBCOST		= $dataI['ITM_BUDG'];
				$REQ_VOLM		= $dataI['REQ_VOLM'];
				$REQ_AMOUNT		= $dataI['REQ_AMOUNT'];
				$PO_VOLM		= $dataI['PO_VOLM'];
				$PO_AMOUNT		= $dataI['PO_AMOUNT'];
				$IR_VOLM		= $dataI['IR_VOLM'];
				$IR_AMOUNT		= $dataI['IR_AMOUNT'];
				$ITM_USED		= $dataI['ITM_USED'];
				$ITM_USED_AM	= $dataI['ITM_USED_AM'];
				$ITM_STOCK		= $dataI['ITM_STOCK'];
				$ITM_STOCK_AM	= $dataI['ITM_STOCK_AM'];
				$IS_LEVEL		= $dataI['IS_LEVEL'];
				$ISLAST			= $dataI['ISLAST'];

				$sqlLLEV		= "tbl_joblist_detail WHERE JOBPARENT = '$JPARENT' AND PRJCODE = '$PRJCODE'";
				$LastLev 		= $this->db->count_all($sqlLLEV);

				if($JOBUNIT != 'M')
					$REQ_VOLM 	= $ITM_USED;

				$serialNumber	= '';
				$itemConvertion	= 1;
				$TOTBUD_VOLM 	= $JOBVOLM + $ADD_VOLM;
				$TOTBUD_AMOUNT	= ($JOBPRICE * $JOBVOLM) + ($ADD_VOLM * $ADD_PRICE);

				// LS PROCESUDE
					$s_isLS 	= "tbl_unitls WHERE ITM_UNIT IN (SELECT ITM_UNIT FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE')";
					$r_isLS 	= $this->db->count_all($s_isLS);
					if($r_isLS == 1)
					{
						$JOBVOLM		= $dataI['ITM_VOLM'] * $dataI['ITM_PRICE'];
						$JOBPRICE		= 1;
						$ITM_LASTP		= 1;
						$ADD_VOLM 		= $dataI['ADD_VOLM'] * $dataI['ADD_PRICE'];
						$ADD_PRICE		= 1;
						$JOBCOST		= $JOBVOLM;
						$REQ_VOLM		= $dataI['REQ_AMOUNT'];
						$REQ_AMOUNT		= 1;
						$PO_VOLM		= $dataI['PO_AMOUNT'];
						$PO_AMOUNT		= 1;
						$IR_VOLM		= $dataI['IR_AMOUNT'];
						$IR_AMOUNT		= 1;
						$ITM_USED		= $dataI['ITM_USED_AM'];
						$ITM_USED_AM	= 1;
						$ITM_STOCK		= $dataI['ITM_STOCK_AM'];
						$ITM_STOCK_AM	= 1;

						$serialNumber	= '';
						$itemConvertion	= 1;
						$TOTBUD_VOLM 	= $JOBVOLM + $ADD_VOLM;
						$TOTBUD_AMOUNT	= 1;
					}

				// BUDGET COLUMN
					$JOBVOLMV 		= number_format($JOBVOLM, 2);

					$ADDVOL_VW 	= "";
					if($ADD_VOLM > 0)
					{
						$ADDVOL_VW 	= 	"<div>
											<p class='text-yellow'><i class='glyphicon glyphicon-triangle-top'></i>
									  		".number_format($ADD_VOLM, 2)."</p>
									  	</div>";
					}

				// REQUEST COLUMN
					$TOT_REQV 		= number_format($REQ_VOLM, 2);

				// REMAIN COLUMN
					$REMBUDG_VOL	= $TOTBUD_VOLM - $REQ_VOLM;
					$REMBUDG_AMN	= $TOTBUD_AMOUNT - $REQ_AMOUNT;
					$REMBUDG_VOLV 	= number_format($REMBUDG_VOL, 2);

					$disabledB 		= 0;
					if($REMBUDG_VOL <= 0)
						$disabledB	= 1;

					if($disabledB == 0)
						$statRem = number_format($REMBUDG_VOL, 2);
					else
						$statRem = "<span class='label label-danger' style='font-size:12px'>".number_format($REMBUDG_VOL, 2)."</span>";

					$CELL_COL	= "style='white-space:nowrap'";

				// OTHER SETT
					$chkBox		= "<input type='checkbox' name='chk1' value='".$JOBCODEDET."|".$JOBCODEID."|".$JOBCODE."|".$PRJCODE."|".$ITM_CODE."|".$JOBDESC."|".$serialNumber."|".$ITM_UNIT."|".$JOBPRICE."|".$TOTBUD_VOLM."|".$ITM_STOCK."|".$ITM_USED."|".$itemConvertion."|".$TOTBUD_AMOUNT."|".$REMBUDG_VOL."|".$PO_AMOUNT."|".$TOTBUD_AMOUNT."|".$REQ_VOLM."|".$ITM_GROUP."|".$JPARENT."|".$LastLev."|".$r_isLS."' onClick='pickThis1(this);'/>";

					$JOBDESC 		= wordwrap($JOBDESC, 60, "<br>", TRUE);

				$output['data'][] 	= array("<div style='text-align:center;'>".$chkBox."</div>",
											"<div style='white-space:nowrap'>$JOBDESC</div>
										  	<div style='margin-left: 10px; font-style: italic; white-space:nowrap'>
										  		<label style='white-space:nowrap'>Kode : ".$ITM_CODE."</label>
										  	</div>",
											"<div style='text-align:center;'><span ".$CELL_COL.">".$JOBUNIT."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".$JOBVOLMV.$ADDVOL_VW."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".$TOT_REQV."</span></div>",
											"<div style='text-align:right;'>".$statRem."</div>");

				$noU			= $noU + 1;
			}

			/*$output['data'][] 	= array("A",
										"B",
										"C",
										"D",
										"E",
										"F");*/
			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function addamd_process_pek() // OK
	{
		$this->load->model('m_company/m_project_amd/m_project_amd', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$this->db->trans_begin();
			
			date_default_timezone_set("Asia/Jakarta");
			
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			$COMPANY_ID		= $this->session->userdata['COMPANY_ID'];
			
			$AMD_NUM 		= $this->input->post('AMD_NUM');
			$AMD_CODE 		= $this->input->post('AMD_CODE');
			$PRJCODE 		= $this->input->post('PRJCODE');
			$AMD_TYPE 		= $this->input->post('AMD_TYPE');
			$AMD_DATE		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('AMD_DATE'))));
			$AMD_CATEG 		= $this->input->post('AMD_CATEG');
			$JOBCODEID		= $this->input->post('JOBCODEID');

			// ADA PERUBAHAN PROSEDUR. BY REQUEST PAK WAWAN
			// 1. PILIH ITEM KOMPNEN (SINGLE)
			// 2. PILIH PEKERJAN (MULTIPLE)
			// NOTE : 21.003/MN-IT.NKE /XII/2021 (22 DESEMBER 2021)
			$AMD_JOBPAR		= $this->input->post('ITM_CODEH');		// MENJADI KODE ITEM / ITM_CODE
			$AMD_JOBID		= $this->input->post('ITM_CODEH');		// MENJADI KODE ITEM / ITM_CODE
			$AMD_JOBID		= $this->input->post('ITM_CODEH');		// MENJADI KODE ITEM / ITM_CODE
			$AMD_JOBDESC	= $this->input->post('AMD_JOBDESC');	// ITM_NAME
			
			$AMD_FUNC 		= '';
			$AMD_REFNO 		= '';
			$AMD_REFNOAM	= 0;
			$AMD_DESC		= '';
			$NEW_JOBCODEID	= '';
			$AMD_UNIT		= '';

			if($AMD_CATEG == 'SI' || $AMD_CATEG == 'SINJ')
			{
				$AMD_FUNC 		= $this->input->post('AMD_FUNC');		// Plus / Minus
				$AMD_REFNO 		= $this->input->post('AMD_REFNO');		// SI Number
				$AMD_REFNOAM 	= $this->input->post('AMD_REFNOAM');	// SI Total
				$AMD_DESC 		= $this->input->post('AMD_DESC');		// Job Name if SI New
				$AMD_UNIT 		= $this->input->post('AMD_UNIT');
			}
			
			$AMD_NOTES 		= addslashes($this->input->post('AMD_NOTES'));
			$AMD_AMOUNT		= $this->input->post('AMD_AMOUNT');
			$AMD_STAT		= $this->input->post('AMD_STAT');
			$AMD_CREATER	= $DefEmp_ID;
			$AMD_CREATED	= date('Y-m-d H:i:s');
			$Patt_Year		= date('Y',strtotime($AMD_DATE));
			$Patt_Number	= $this->input->post('Patt_Number');
			
			// START - PEMBENTUKAN GENERATE CODE
				$this->load->model('m_projectlist/m_projectlist', '', TRUE);
				$Pattern_Code	= "XX";
				$MenuCode 		= 'MN406';
				$vwDocPatt		= $this->m_projectlist->getDataDocPat($MenuCode)->result();
				foreach($vwDocPatt as $row) :
					$Pattern_Code = $row->Pattern_Code;
				endforeach;
				
				$PRJCODE 		= $this->input->post('PRJCODE');
				$TRXTIME1		= date('ymdHis');
				$AMD_NUM		= "$Pattern_Code$PRJCODE-$TRXTIME1";
			// END - PEMBENTUKAN GENERATE CODE

			$sqlLEV		= "SELECT JOBPARENT, JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";
			$resLEV 	= $this->db->query($sqlLEV)->result();
			foreach($resLEV as $rowLEV) :
				$JOBPARENT	= $rowLEV->JOBPARENT;
				$JOBDESC	= $rowLEV->JOBDESC;
			endforeach;
			
			$insertAMD 	= array('AMD_NUM' 		=> $AMD_NUM,
								'AMD_CODE'		=> $AMD_CODE,
								'PRJCODE'		=> $PRJCODE,
								'AMD_TYPE'		=> "OVH",
								'AMD_CATEG'		=> $AMD_CATEG,
								'AMD_FUNC'		=> $AMD_FUNC,
								'AMD_REFNO'		=> $AMD_REFNO,
								'AMD_REFNOAM'	=> $AMD_REFNOAM,
								'AMD_JOBPAR'	=> $JOBPARENT,
								'AMD_JOBID'		=> $JOBCODEID,
								'AMD_JOBDESC' 	=> $JOBDESC,
								'AMD_DATE'		=> $AMD_DATE,
								'AMD_DESC'		=> $AMD_DESC,
								'AMD_UNIT'		=> $AMD_UNIT,
								'AMD_NOTES'		=> $AMD_NOTES,
								'AMD_AMOUNT'	=> $AMD_AMOUNT,
								'AMD_STAT'		=> $AMD_STAT,
								'AMD_CREATER'	=> $AMD_CREATER,
								'AMD_CREATED'	=> $AMD_CREATED,
								'Patt_Year'		=> $Patt_Year,
								'Patt_Number'	=> $Patt_Number);
			$this->m_project_amd->addAMD($insertAMD);

			if($AMD_CATEG == 'OTH')
			{
				foreach($_POST['dataIOB'] as $d)
				{
					$d['AMD_NUM']	= $AMD_NUM;
					$ITM_CODE 		= $d['ITM_CODE'];
					$ITM_GROUP 		= "";
					$ITM_UNIT 		= "";
					$s_01 			= "SELECT ITM_GROUP, ITM_UNIT FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' LIMIT 1";
					$r_01 			= $this->db->query($s_01)->result();
					foreach($r_01 as $rw_01):
						$ITM_GROUP 	= $rw_01->ITM_GROUP;
						$ITM_UNIT 	= $rw_01->ITM_UNIT;
					endforeach;
					$d['PRJCODE']	= $PRJCODE;
					$d['ITM_GROUP']	= $ITM_GROUP;
					$d['ITM_UNIT']	= $ITM_UNIT;
					$d['AMD_TOTTSF']= $d['AMD_TOTAL'];

					$this->db->insert('tbl_amd_detail',$d);
				}

				foreach($_POST['dataSUB'] as $ds)
				{
					$ds['AMD_NUM']	= $AMD_NUM;
					$ds['PRJCODE']	= $PRJCODE;
					$ITM_CODE 		= $ds['ITM_CODE'];
					$ITM_GROUP 		= "";
					$ITM_UNIT 		= "";
					$s_01 			= "SELECT ITM_GROUP, ITM_UNIT FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' LIMIT 1";
					$r_01 			= $this->db->query($s_01)->result();
					foreach($r_01 as $rw_01):
						$ITM_GROUP 	= $rw_01->ITM_GROUP;
						$ITM_UNIT 	= $rw_01->ITM_UNIT;
					endforeach;
					$ds['ITM_GROUP']= $ITM_GROUP;
					$ds['ITM_UNIT']	= $ITM_UNIT;

					$this->db->insert('tbl_amd_detail_subs',$ds);
				}
			}
			else
			{
				foreach($_POST['data'] as $d)
				{
					$d['AMD_NUM']	= $AMD_NUM;
					$ITM_CODE 		= $d['ITM_CODE'];
					$AMD_PRICE 		= $d['AMD_PRICE'];
					$ITM_GROUP 		= "";
					$ITM_UNIT 		= "";
					$s_01 			= "SELECT ITM_GROUP, ITM_UNIT FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' LIMIT 1";
					$r_01 			= $this->db->query($s_01)->result();
					foreach($r_01 as $rw_01):
						$ITM_GROUP 	= $rw_01->ITM_GROUP;
						$ITM_UNIT 	= $rw_01->ITM_UNIT;
					endforeach;
					$d['JOBPARENT']	= $JOBCODEID;
					$d['ITM_GROUP']	= $ITM_GROUP;
					$d['ITM_UNIT']	= $ITM_UNIT;
					$d['PRJCODE']	= $PRJCODE;
					$d['AMD_TOTTSF']= $d['AMD_TOTAL'];
					$d['PRJCODE']	= $PRJCODE;

					$this->db->insert('tbl_amd_detail',$d);
				}
			}

			if($AMD_STAT == 2)
			{
				// START : UPDATE FINANCIAL DASHBOARD
					$AMD_VAL 	= $AMD_AMOUNT;
					$finDASH 	= array('PRJCODE'	=> $PRJCODE,
										'PERIODE'	=> $AMD_DATE,
										'FVAL'		=> $AMD_VAL,
										'FNAME'		=> "AMD_VAL");										
					$this->m_updash->updFINDASH($finDASH);
				// END : UPDATE FINANCIAL DASHBOARD
			}

			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "AMD_NUM",
										'DOC_CODE' 		=> $AMD_NUM,
										'DOC_STAT' 		=> $AMD_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_amd_header");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $AMD_NUM;
				$MenuCode 		= 'MN037';
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
			
			$url			= site_url('c_comprof/c_am1h0db2/gall1h0db2amd_pek/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function updateAMD_pek() // OK
	{
		$this->load->model('m_company/m_project_amd/m_project_amd', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$COLLDATA	= $_GET['id'];
			$COLLDATA	= $this->url_encryption_helper->decode_url($COLLDATA);
			$EXTRACTCOL	= explode("~", $COLLDATA);
			$PRJCODE	= $EXTRACTCOL[0];
			$AMD_NUM	= $EXTRACTCOL[1];
			
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_comprof/c_am1h0db2/updateamd_process_pek');
			
			// GET MENU DESC
				$mnCode				= 'MN037';
				$data["MenuCode"] 	= 'MN037';
				$data["MenuApp"] 	= 'MN038';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$getAMD				= $this->m_project_amd->get_amd_by_number($AMD_NUM)->row();								
			$data['default']['AMD_NUM'] 	= $getAMD->AMD_NUM;
			$data['default']['AMD_CODE'] 	= $getAMD->AMD_CODE;
			$data['default']['PRJCODE'] 	= $getAMD->PRJCODE;
			$data["PRJCODE"] 				= $PRJCODE;
			$PRJCODE						= $getAMD->PRJCODE;
			$data['default']['AMD_TYPE'] 	= $getAMD->AMD_TYPE;
			$data['default']['AMD_CATEG'] 	= $getAMD->AMD_CATEG;
			$data['default']['AMD_FUNC'] 	= $getAMD->AMD_FUNC;
			$data['default']['AMD_REFNO'] 	= $getAMD->AMD_REFNO;
			$data['default']['AMD_REFNOAM'] = $getAMD->AMD_REFNOAM;
			$data['default']['AMD_JOBPAR'] 	= $getAMD->AMD_JOBPAR;
			$data['default']['AMD_JOBID'] 	= $getAMD->AMD_JOBID;
			$data['default']['AMD_JOBDESC'] = $getAMD->AMD_JOBDESC;
			$data['default']['AMD_DATE'] 	= $getAMD->AMD_DATE;
			$data['default']['AMD_DESC'] 	= $getAMD->AMD_DESC;
			$data['default']['AMD_UNIT'] 	= $getAMD->AMD_UNIT;
			$data['default']['AMD_NOTES'] 	= $getAMD->AMD_NOTES;
			$data['default']['AMD_MEMO'] 	= $getAMD->AMD_MEMO;
			$data['default']['AMD_AMOUNT'] 	= $getAMD->AMD_AMOUNT;
			$data['default']['AMD_STAT'] 	= $getAMD->AMD_STAT;
			$data['default']['Patt_Number'] = $getAMD->Patt_Number;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getAMD->AMD_NUM;
				$MenuCode 		= 'MN037';
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
			
			$this->load->view('v_company/v_project_amd_pek/v_amd_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function updateamd_process_pek() // OK
	{
		$this->load->model('m_company/m_project_amd/m_project_amd', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$this->db->trans_begin();
			
			date_default_timezone_set("Asia/Jakarta");
			
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			$COMPANY_ID		= $this->session->userdata['COMPANY_ID'];
			
			$AMD_NUM 		= $this->input->post('AMD_NUM');
			$AMD_CODE 		= $this->input->post('AMD_CODE');
			$PRJCODE 		= $this->input->post('PRJCODE');
			$AMD_TYPE 		= $this->input->post('AMD_TYPE');
			$AMD_DATE		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('AMD_DATE'))));
			$AMD_CATEG 		= $this->input->post('AMD_CATEG');
			$JOBCODEID		= $this->input->post('JOBCODEID');
			
			// ADA PERUBAHAN PROSEDUR. BY REQUEST PAK WAWAN
			// 1. PILIH ITEM KOMPNEN (SINGLE)
			// 2. PILIH PEKERJAN (MULTIPLE)
			// NOTE : 21.003/MN-IT.NKE /XII/2021 (22 DESEMBER 2021)
			/*$AMD_JOBPAR		= $this->input->post('JOBCODEID');	// PARENT
			$AMD_JOBID		= $this->input->post('JOBCODEID');*/
			$AMD_JOBPAR		= $this->input->post('ITM_CODEH');		// MENJADI KODE ITEM / ITM_CODE
			$AMD_JOBID		= $this->input->post('ITM_CODEH');		// MENJADI KODE ITEM / ITM_CODE
			$AMD_JOBID		= $this->input->post('ITM_CODEH');		// MENJADI KODE ITEM / ITM_CODE
			$AMD_JOBDESC	= $this->input->post('AMD_JOBDESC');	// ITM_NAME

			$AMD_FUNC 		= "";		// Plus / Minus
			$AMD_REFNO 		= "";		// SI Number
			$AMD_REFNOAM 	= 0;		// SI Total
			$AMD_DESC 		= "";		// Job Name if SI New
			$AMD_UNIT 		= "";
			if($AMD_CATEG == 'SI' || $AMD_CATEG == 'SINJ')
			{
				$AMD_FUNC 		= $this->input->post('AMD_FUNC');		// Plus / Minus
				$AMD_REFNO 		= $this->input->post('AMD_REFNO');		// SI Number
				$AMD_REFNOAM 	= $this->input->post('AMD_REFNOAM');	// SI Total
				$AMD_DESC 		= $this->input->post('AMD_DESC');		// Job Name if SI New
				$AMD_UNIT 		= $this->input->post('AMD_UNIT');
			}
			
			$AMD_NOTES 		= addslashes($this->input->post('AMD_NOTES'));
			$AMD_AMOUNT		= $this->input->post('AMD_AMOUNT');
			$AMD_STAT		= $this->input->post('AMD_STAT');
			$AMD_CREATER	= $DefEmp_ID;
			$Patt_Year		= date('Y',strtotime($AMD_DATE));
			
			$JOBDESC1 	= '';
			$JOBPARENT1	= '';
			$sqlLEV		= "SELECT JOBDESC, JOBPARENT, JOBLEV FROM tbl_joblist WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";
			$resLEV 	= $this->db->query($sqlLEV)->result();
			foreach($resLEV as $rowLEV) :
				$JOBDESC1	= $rowLEV->JOBDESC;
				$JOBPARENT1	= $rowLEV->JOBPARENT;
				$JOBLEV		= $rowLEV->JOBLEV;
			endforeach;

			$updateAMD 	= array('AMD_CODE'		=> $AMD_CODE,
								'PRJCODE'		=> $PRJCODE,
								'AMD_TYPE'		=> $AMD_TYPE,
								'AMD_CATEG'		=> $AMD_CATEG,
								'AMD_FUNC'		=> $AMD_FUNC,
								'AMD_REFNO'		=> $AMD_REFNO,
								'AMD_REFNOAM'	=> $AMD_REFNOAM,
								'AMD_JOBPAR'	=> $JOBPARENT1,
								'AMD_JOBID'		=> $JOBCODEID,
								'AMD_JOBDESC' 	=> $JOBDESC1,
								'AMD_DATE'		=> $AMD_DATE,
								'AMD_DESC'		=> $AMD_DESC,
								'AMD_UNIT'		=> $AMD_UNIT,
								'AMD_NOTES'		=> $AMD_NOTES,
								'AMD_AMOUNT'	=> $AMD_AMOUNT,
								'AMD_STAT'		=> $AMD_STAT);
			$this->m_project_amd->updateAMD($AMD_NUM, $updateAMD);
			
			$this->m_project_amd->deleteAMDDet($AMD_NUM);

			if($AMD_CATEG == 'OTH')
			{
				$this->m_project_amd->deleteAMDDetSUBS($AMD_NUM);
				
				foreach($_POST['dataIOB'] as $d)
				{
					$d['AMD_NUM']	= $AMD_NUM;
					$ITM_CODE 		= $d['ITM_CODE'];
					$ITM_GROUP 		= "";
					$ITM_UNIT 		= "";
					$s_01 			= "SELECT ITM_GROUP, ITM_UNIT FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' LIMIT 1";
					$r_01 			= $this->db->query($s_01)->result();
					foreach($r_01 as $rw_01):
						$ITM_GROUP 	= $rw_01->ITM_GROUP;
						$ITM_UNIT 	= $rw_01->ITM_UNIT;
					endforeach;
					$d['PRJCODE']	= $PRJCODE;
					$d['ITM_GROUP']	= $ITM_GROUP;
					$d['ITM_UNIT']	= $ITM_UNIT;
					$d['AMD_TOTTSF']= $d['AMD_TOTAL'];

					$this->db->insert('tbl_amd_detail',$d);
				}

				foreach($_POST['dataSUB'] as $ds)
				{
					$ds['AMD_NUM']	= $AMD_NUM;
					$ds['PRJCODE']	= $PRJCODE;
					$ITM_CODE 		= $ds['ITM_CODE'];
					$ITM_GROUP 		= "";
					$ITM_UNIT 		= "";
					$s_01 			= "SELECT ITM_GROUP, ITM_UNIT FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' LIMIT 1";
					$r_01 			= $this->db->query($s_01)->result();
					foreach($r_01 as $rw_01):
						$ITM_GROUP 	= $rw_01->ITM_GROUP;
						$ITM_UNIT 	= $rw_01->ITM_UNIT;
					endforeach;
					$ds['ITM_GROUP']= $ITM_GROUP;
					$ds['ITM_UNIT']	= $ITM_UNIT;

					$this->db->insert('tbl_amd_detail_subs',$ds);
				}
			}
			else
			{
				foreach($_POST['data'] as $d)
				{
					$d['AMD_NUM']	= $AMD_NUM;
					$ITM_CODE 		= $d['ITM_CODE'];
					$ITM_GROUP 		= "";
					$ITM_UNIT 		= "";
					$s_01 			= "SELECT ITM_GROUP, ITM_UNIT FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' LIMIT 1";
					$r_01 			= $this->db->query($s_01)->result();
					foreach($r_01 as $rw_01):
						$ITM_GROUP 	= $rw_01->ITM_GROUP;
						$ITM_UNIT 	= $rw_01->ITM_UNIT;
					endforeach;
					$d['ITM_GROUP']	= $ITM_GROUP;
					$d['ITM_UNIT']	= $ITM_UNIT;

					$this->db->insert('tbl_amd_detail',$d);
				}
			}

			if($AMD_STAT == 2)
			{
				// START : UPDATE FINANCIAL DASHBOARD
					$AMD_VAL 	= $AMD_AMOUNT;
					$finDASH 	= array('PRJCODE'	=> $PRJCODE,
										'PERIODE'	=> $AMD_DATE,
										'FVAL'		=> $AMD_VAL,
										'FNAME'		=> "AMD_VAL");										
					$this->m_updash->updFINDASH($finDASH);
				// END : UPDATE FINANCIAL DASHBOARD
			}
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "AMD_NUM",
										'DOC_CODE' 		=> $AMD_NUM,
										'DOC_STAT' 		=> $AMD_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_amd_header");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $AMD_NUM;
				$MenuCode 		= 'MN406';
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
			
			$url			= site_url('c_comprof/c_am1h0db2/gall1h0db2amd_pek/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
 	function i180dah_pek() // OK
	{
		$this->load->model('m_company/m_project_amd/m_project_amd', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;
		
		$url			= site_url('c_comprof/c_am1h0db2/pR7_l157_14x_pek/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function pR7_l157_14x_pek() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$LangID 	= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN037';
				$data["MenuCode"] 	= 'MN038';
				$data["MenuApp"] 	= 'MN038';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;

			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN40';
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_comprof/c_am1h0db2/i1dah80Idx_pek/?id=";
			
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
	
	function i1dah80Idx_pek() // OK
	{
		$this->load->model('m_company/m_project_amd/m_project_amd', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		$LangID 	= $this->session->userdata['LangID'];
			
		// GET MENU DESC
			$mnCode				= 'MN037';
			$data["MenuCode"] 	= 'MN037';
			$data["MenuApp"] 	= 'MN038';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			// -------------------- START : SEARCHING METHOD --------------------
				
				$collDATA		= $_GET['id'];
				$EXP_COLLD1		= $this->url_encryption_helper->decode_url($collDATA);	
				$EXP_COLLD		= explode('~', $EXP_COLLD1);	
				$C_COLLD1		= count($EXP_COLLD);
				
				if($C_COLLD1 > 1)
				{
					$EXP_COLLD1	= str_replace('~', '', $EXP_COLLD1);
					$key		= $EXP_COLLD[0];
					$PRJCODE	= $EXP_COLLD[1];
					$mxLS		= $EXP_COLLD[2];
					$end		= $EXP_COLLD[3];
					$start		= 0;
				}
				else
				{
					$key		= '';
					$PRJCODE	= $EXP_COLLD1;
					$start		= 0;
					$end		= 50;
				}
				$data['backURL'] 	= site_url('c_comprof/c_am1h0db2/pR7_l157_14x_pek/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
				$data["url_search"] = site_url('c_comprof/c_am1h0db2/f4n7_5rcH1nB/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
				//$num_rows 			= $this->m_project_amd->count_all_AMDInb($PRJCODE, $key, $DefEmp_ID);
				//$data["cData"] 		= $num_rows;	 
				//$data['vData']		= $this->m_project_amd->get_all_AMDInb($PRJCODE, $start, $end, $key, $DefEmp_ID)->result();
			// -------------------- END : SEARCHING METHOD --------------------
			
			$data['title'] 		= $appName;	
			$data["PRJCODE"] 	= $PRJCODE;
			
			/*$getAMDC				= $this->m_project_amd->get_amd_by_numberLC($DefEmp_ID);
			$PRJCODE				= '';
			if($getAMDC > 1)
			{
				$getAMD				= $this->m_project_amd->get_amd_by_numberL($DefEmp_ID)->row();
				$PRJCODE 			= $getAMD->PRJCODE;
			}*/
						
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN038';
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
			
			$this->load->view('v_company/v_project_amd_pek/v_inb_amd_list', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

  	function get_AllData_1n2_pek() // GOOD
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
									"AMD_CODE", 
									"AMD_DATE", 
									"JOBDESC", 
									"AMD_NOTES", 
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
			$num_rows 		= $this->m_project_amd->get_AllDataC_1n2_pek($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_project_amd->get_AllDataL_1n2_pek($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{							

				$AMD_ID 	= $dataI['AMD_ID'];
				$AMD_NUM 	= $dataI['AMD_NUM'];
				$AMD_CODE 	= $dataI['AMD_CODE'];
				$AMD_DATE 	= $dataI['AMD_DATE'];
				$AMD_DATEV	= date('d M Y', strtotime($AMD_DATE));
				$PRJCODE	= $dataI['PRJCODE'];
				$AMD_DESC	= $dataI['AMD_DESC'];
				$AMD_NOTES 	= $dataI['AMD_NOTES'];
				$AMD_MEMO 	= $dataI['AMD_MEMO'];
				$AMD_AMOUNT = $dataI['AMD_AMOUNT'];
				$AMD_STAT	= $dataI['AMD_STAT'];
				$JOBDESC	= $dataI['JOBDESC'];
				$STATDESC	= $dataI['STATDESC'];
				$STATCOL	= $dataI['STATCOL'];
				$CREATERNM	= $dataI['CREATERNM'];
				$empName	= cut_text2 ("$CREATERNM", 15);
				
				$CollID 	= "$PRJCODE~$AMD_NUM";
				$secUpd		= site_url('c_comprof/c_am1h0db2/update_inb_pek/?id='.$this->url_encryption_helper->encode_url($CollID));
				$secPrint	= site_url('c_comprof/c_am1h0db2/p_R1n7/?id='.$this->url_encryption_helper->encode_url($AMD_NUM));
				$CollID		= "AMD~$AMD_NUM~$PRJCODE";
				$secDel_DOC = base_url().'index.php/__l1y/trash_DOC/?id='.$CollID;
				
				$secAction	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
							   <label style='white-space:nowrap'>
							   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
									<i class='glyphicon glyphicon-pencil'></i>
							   </a>
								<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
									<i class='glyphicon glyphicon-print'></i>
								</a>
								<a href='#' onClick='deleteDOC('".$secDel_DOC."')' title='Delete file' class='btn btn-danger btn-xs' disabled='disabled'>
									<i class='fa fa-trash-o'></i>
								</a>
								</label>";
								
				$output['data'][] = array("$noU.",
										  "<div style='white-space:nowrap'>".$dataI['AMD_CODE']."</div>",
										  $AMD_DATEV,
										  $JOBDESC,
										  $AMD_NOTES,
										  "<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
										  $secAction);
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function update_inb_pek() // OK
	{
		$this->load->model('m_company/m_project_amd/m_project_amd', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN037';
			$data["MenuApp"] 	= 'MN038';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		$EmpID 		= $this->session->userdata('Emp_ID');
		if ($this->session->userdata('login') == TRUE)
		{
			$COLLDATA	= $_GET['id'];
			$COLLDATA	= $this->url_encryption_helper->decode_url($COLLDATA);
			$EXTRACTCOL	= explode("~", $COLLDATA);
			$PRJCODE	= $EXTRACTCOL[0];
			$AMD_NUM	= $EXTRACTCOL[1];
			
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_comprof/c_am1h0db2/updateamd_process_inb_pek');
			
			$MenuCode 			= 'MN037';
			$data["MenuCode"] 	= 'MN037';
			
			$getAMD				= $this->m_project_amd->get_amd_by_number($AMD_NUM)->row();	
			$data['default']['AMD_NUM'] 	= $getAMD->AMD_NUM;
			$data['default']['AMD_CODE'] 	= $getAMD->AMD_CODE;
			$data['default']['PRJCODE'] 	= $getAMD->PRJCODE;
			$data['PRJCODE'] 				= $getAMD->PRJCODE;
			$PRJCODE						= $getAMD->PRJCODE;
			$data['default']['AMD_TYPE'] 	= $getAMD->AMD_TYPE;
			$data['default']['AMD_CATEG'] 	= $getAMD->AMD_CATEG;
			$data['default']['AMD_FUNC'] 	= $getAMD->AMD_FUNC;
			$data['default']['AMD_REFNO'] 	= $getAMD->AMD_REFNO;
			$data['default']['AMD_REFNOAM'] = $getAMD->AMD_REFNOAM;
			$data['default']['AMD_JOBPAR'] 	= $getAMD->AMD_JOBPAR;
			$data['default']['AMD_JOBID'] 	= $getAMD->AMD_JOBID;
			$data['default']['AMD_JOBDESC'] = $getAMD->AMD_JOBDESC;
			$data['default']['AMD_DATE'] 	= $getAMD->AMD_DATE;
			$data['default']['AMD_DESC'] 	= $getAMD->AMD_DESC;
			$data['default']['AMD_UNIT'] 	= $getAMD->AMD_UNIT;
			$data['default']['AMD_NOTES'] 	= $getAMD->AMD_NOTES;
			$data['default']['AMD_MEMO'] 	= $getAMD->AMD_MEMO;
			$data['default']['AMD_AMOUNT'] 	= $getAMD->AMD_AMOUNT;
			$data['default']['AMD_STAT'] 	= $getAMD->AMD_STAT;
			$data['default']['Patt_Number'] = $getAMD->Patt_Number;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getAMD->AMD_NUM;
				$MenuCode 		= 'MN037';
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
			
			$this->load->view('v_company/v_project_amd_pek/v_inb_amd_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function updateamd_process_inb_pek() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$this->db->trans_begin();
			
			date_default_timezone_set("Asia/Jakarta");
			
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			$COMPANY_ID		= $this->session->userdata['COMPANY_ID'];
			
			$AMD_NUM 		= $this->input->post('AMD_NUM');
			$AMD_CODE 		= $this->input->post('AMD_CODE');
			$PRJCODE 		= $this->input->post('PRJCODE');
			$PRJCODE_HO		= $this->data['PRJCODE_HO'];
			$AMD_TYPE 		= $this->input->post('AMD_TYPE');
			$AMD_CATEG 		= $this->input->post('AMD_CATEG');
			$AMD_JOBPAR		= $this->input->post('JOBCODEID');	// PARENT
			$JOBPARENT1 	= $this->input->post('JOBCODEID');	// PARENT
			$JOBPARDESC		= $this->input->post('JOBPARDESC');
			$JOBLEV			= $this->input->post('JOBPARLEV');
			
			$AMD_FUNC 		= '';
			$AMD_REFNO 		= '';
			$AMD_REFNOAM	= 0;
			$AMD_DESC		= '';
			$NEW_JOBCODEID	= '';
			$AMD_UNIT		= '';
			if($AMD_CATEG == 'SI' || $AMD_CATEG == 'SINJ')
			{
				$AMD_FUNC 		= $this->input->post('AMD_FUNC');		// Plus / Minus
				$AMD_REFNO 		= $this->input->post('AMD_REFNO');		// SI Number
				$AMD_REFNOAM 	= $this->input->post('AMD_REFNOAM');	// SI Total
				$AMD_DESC 		= $this->input->post('AMD_DESC');		// Job Name if SI New
				$AMD_UNIT 		= $this->input->post('AMD_UNIT');
			}
			
			$AMD_DATE		= date('Y-m-d',strtotime($this->input->post('AMD_DATE')));
			$AMD_DESC 		= $this->input->post('AMD_DESC');
			$AMD_UNIT 		= $this->input->post('AMD_UNIT');
			$AMD_NOTES 		= addslashes($this->input->post('AMD_NOTES'));
			$AMD_MEMO 		= $this->input->post('AMD_MEMO');
			$AMD_AMOUNT		= $this->input->post('AMD_AMOUNT');
			$AMD_STAT		= $this->input->post('AMD_STAT');
			$AH_ISLAST		= $this->input->post('IS_LAST');
			$AMD_CREATER	= $DefEmp_ID;
			$AMD_CREATED	= date('Y-m-d H:i:s');
			$AH_ISLAST		= $this->input->post('IS_LAST');			
			$AMD_APPROVED	= date('Y-m-d H:i:s');
				
			$AH_CODE		= $AMD_NUM;
			$AH_APPLEV		= $this->input->post('APP_LEVEL');
			$AH_APPROVER	= $DefEmp_ID;
			$AH_APPROVED	= $AMD_APPROVED;
			$AH_NOTES		= addslashes($this->input->post('AMD_NOTES'));
			$AH_ISLAST		= $this->input->post('IS_LAST');
			
			// UPDATE JOBDETAIL ITEM
			if($AMD_STAT == 3)
			{
				$updateAMD 		= array('AMD_STAT'	=> 7);			
				$this->m_project_amd->updateAMD($AMD_NUM, $updateAMD);
				
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "AMD_NUM",
											'DOC_CODE' 		=> $AMD_NUM,
											'DOC_STAT' 		=> 7,
											'PRJCODE' 		=> $PRJCODE,
											//'CREATERNM'		=> "",
											'TBLNAME'		=> "tbl_amd_header");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS

				// START : SAVE APPROVE HISTORY
					$this->load->model('m_updash/m_updash', '', TRUE);
					
					$insAppHist 	= array('AH_CODE'		=> $AH_CODE,
											'AH_APPLEV'		=> $AH_APPLEV,
											'AH_APPROVER'	=> $AH_APPROVER,
											'AH_APPROVED'	=> $AH_APPROVED,
											'AH_NOTES'		=> $AH_NOTES,
											'PRJCODE'		=> $PRJCODE,
											'AH_ISLAST'		=> $AH_ISLAST);										
					$this->m_updash->insAppHist($insAppHist);
				// END : SAVE APPROVE HISTORY

				if($AH_ISLAST == 1)
				{
					$Pattern_Length	= 2;
					$AH_CODE		= $AMD_NUM;
					$AH_APPLEV		= $this->input->post('APP_LEVEL');
					$AH_APPROVER	= $DefEmp_ID;
					$AH_APPROVED	= $AMD_APPROVED;
					$AH_NOTES		= addslashes($this->input->post('AMD_NOTES'));
					$AH_ISLAST		= $this->input->post('IS_LAST');

					$updateAMD 		= array('AMD_MEMO'	=> $AMD_MEMO,
										'AMD_STAT'		=> $AMD_STAT);
					$this->m_project_amd->updateAMD($AMD_NUM, $updateAMD);

					// UPDATE OTHER ORD_ID
						if($AMD_CATEG == 'OB') // Over Budget. Not added new item, update qty only
						{
							foreach($_POST['data'] as $d)
							{
								$JOBCODEID	= $d['JOBCODEID'];
								$ITM_CODE	= $d['ITM_CODE'];
								$PRJCODE	= $PRJCODE;
								$ADD_VOLM	= $d['AMD_VOLM'];
								$ADD_PRICE	= $d['AMD_PRICE'];
								$AMD_CLASS	= $d['AMD_CLASS'];

								$ADD_JOBCOST= $ADD_VOLM * $ADD_PRICE;

								// UPDATE TO WBS / JOBLIST AND JOBDEATIL. LAST CHECKED : 21-10-29
									$paramWBS 	= array('ADD_VOLM' 		=> $ADD_VOLM,
														'ADD_PRICE'		=> $ADD_PRICE,
														'ADD_JOBCOST'	=> $ADD_JOBCOST,
														'JOBPARENT'		=> $JOBPARENT1,
														'JOBCODEID'		=> $JOBCODEID,
														'ITM_CODE'		=> $ITM_CODE,
														'AMD_CLASS'		=> $AMD_CLASS,
														'PRJCODE'		=> $PRJCODE);
									$this->m_project_amd->updateWBS($paramWBS);
							}
						}
						elseif($AMD_CATEG == 'NB') // Not Budgeting
						{
							$theLastD 		= 0;
							foreach($_POST['data'] as $d)
							{
								$theLastD 	= $theLastD+1;
								$JOBCODEID	= $d['JOBCODEID'];
								$JOBPARENT	= $JOBPARENT1;

								$maxC		= 0;
								$sLastChld	= "SELECT IF(ISNULL(MAX(ORD_ID)), 0, MAX(ORD_ID)) as maxChild FROM tbl_joblist_detail
												WHERE JOBCODEID = '$JOBPARENT' AND PRJCODE = '$PRJCODE'";
								$rLastChld 	= $this->db->query($sLastChld)->result();
								foreach($rLastChld as $lastChld) :
									$maxC 	= $lastChld->maxChild;
								endforeach;
								$nxChld 	= $maxC+1;

								$NEWLEV 	= 0;
								$sLevHead 	= "SELECT IS_LEVEL FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBPARENT' AND PRJCODE = '$PRJCODE'";
								$rLevHead	= $this->db->query($sLevHead)->result();
								foreach($rLevHead as $rwLevHead):
									$NEWLEV = $rwLevHead->IS_LEVEL;
								endforeach;
								$JOBLEV 	= $NEWLEV+1;
								
								// UNTUK AMANDEMEN KATEGORI NB DAN SINJ, KODE BUDGET ITEM/KOMPONEN SUDAH DIBENTUK SAAT INPUT DOKUMEN AMANDEMEN
								// SEHINGGA TIDAK PERLU LAGI MEMBUAT PROSEDUR PENOMORAN BUDGET
									/*$nol		= "";
									$len 		= strlen($nxChld);
									if($Pattern_Length==2)
									{
										if($len==1) $nol="0";
									}
									else
									{
										$nol	= "";
									}
									$lastNumb = $nol.$nxChld;*/

								// CEK SAME ORD_ID
									$nxX 	= $nxChld;
									$sORID 	= "SELECT JOBCODEID FROM tbl_joblist_detail WHERE ORD_ID = $nxChld AND PRJCODE = '$PRJCODE'";
									$rORID	= $this->db->query($sORID)->result();
									foreach($rORID as $rwORID):
										$nxX 	= $nxX+1;
									endforeach;

									$supChld	= "UPDATE tbl_joblist_detail SET ORD_ID = ORD_ID+1 WHERE ORD_ID > $nxChld
													AND PRJCODE = '$PRJCODE'";
									$this->db->query($supChld);

									$sUPD 	= "UPDATE tbl_joblist_detail SET ORD_ID = $nxX WHERE JOBCODEID = '$JOBCODEID'
												AND PRJCODE = '$PRJCODE'";
									$this->db->query($sUPD);
								
									$dt['ORD_ID']		= $nxX;
									$dt['JOBCODEDET']	= $JOBCODEID;
									$dt['JOBCODEID']	= $JOBCODEID;
									$dt['JOBPARENT']	= $JOBPARENT;
									$dt['JOBCOD2']		= $AMD_NUM;
									$dt['PRJCODE']		= $PRJCODE;
									$dt['PRJCODE_HO']	= $PRJCODE_HO;
									$dt['PRJPERIOD']	= $PRJCODE;
									$dt['PRJPERIOD_P']	= $PRJCODE_HO;
									$dt['JOBDESC']		= $d['JOBDESC'];
									$dt['ITM_GROUP']	= $d['ITM_GROUP'];
									$dt['GROUP_CATEG']	= $d['ITM_GROUP'];
									$dt['ITM_CODE']		= $d['ITM_CODE']; 
									$dt['ITM_UNIT']		= $d['ITM_UNIT'];
									$ADD_VOLM			= $d['AMD_VOLM'];
									$dt['ITM_PRICE']	= $d['AMD_PRICE'];
									$dt['ITM_LASTP']	= $d['AMD_PRICE'];
									$dt['ADD_VOLM']		= $d['AMD_VOLM'];
									$dt['ADD_PRICE']	= $d['AMD_PRICE'];
									$ADD_PRICE			= $d['AMD_PRICE'];					
									$JOBVOLM			= $d['AMD_VOLM'];
									$JOBCOST			= $ADD_VOLM * $ADD_PRICE;
									$dt['ADD_JOBCOST']	= $JOBCOST;
									$dt['IS_LEVEL']		= $JOBLEV;
									$dt['ISLAST']		= 1;
									$dt['Patt_Number']	= $theLastD;
								
								// ADDING NEW JOB
									//$this->db->insert('tbl_boqlist',$bq);
									//$this->db->insert('tbl_joblist',$e);
									$this->db->insert('tbl_joblist_detail',$dt);

								// UPDATE WBS	
									$JOBCODEID	= $d['JOBCODEID'];
									$ITM_CODE	= $d['ITM_CODE'];
									$PRJCODE	= $PRJCODE;
									$ADD_VOLM	= $d['AMD_VOLM'];
									$ADD_PRICE	= $d['AMD_PRICE'];

									// UPDATE TO WBS / JOBLIST AND JOBDEATIL. LAST CHECKED : 21-10-29
										$paramWBS 	= array('ADD_VOLM' 		=> $ADD_VOLM,
															'ADD_PRICE'		=> $ADD_PRICE,
															'ADD_JOBCOST'	=> $JOBCOST,
															'JOBPARENT'		=> $JOBPARENT,
															'JOBCODEID'		=> $JOBCODEID,
															'ITM_CODE'		=> $ITM_CODE,
															'PRJCODE'		=> $PRJCODE);
										$this->m_project_amd->updateWBSH($paramWBS);
							}
						}
						elseif($AMD_CATEG == 'SI') // Site Instruction. Fure Update
						{
							// Dalam kondisi jika hanya perubahan atau penambahan item pada pekerjaan yang sudah ada.
							if($AMD_FUNC == 'PLUS')
							{
								foreach($_POST['data'] as $d)
								{
									$JOBCODEID	= $d['JOBCODEID'];
									$ITM_CODE	= $d['ITM_CODE'];
									$PRJCODE	= $PRJCODE;
									$ADD_VOLM	= $d['AMD_VOLM'];
									$ADD_PRICE	= $d['AMD_PRICE'];
									$AMD_CLASS	= $d['AMD_CLASS'];
									$ADD_JOBCOST= $ADD_VOLM * $ADD_PRICE;
									
									// UPDATE TO WBS / JOBLIST AND JOBDEATIL
									$paramWBS 	= array('ADD_VOLM' 		=> $ADD_VOLM,
														'ADD_PRICE'		=> $ADD_PRICE,
														'ADD_JOBCOST'	=> $ADD_JOBCOST,
														'JOBPARENT'		=> $AMD_JOBPAR,
														'JOBCODEID'		=> $JOBCODEID,
														'ITM_CODE'		=> $ITM_CODE,
														'AMD_CLASS'		=> $AMD_CLASS,
														'PRJCODE'		=> $PRJCODE);
									$this->m_project_amd->updateWBS($paramWBS);
						
									//$this->m_project_amd->updateWBSD($paramWBS);
								}
							}
							elseif($AMD_FUNC == 'MIN')
							{
								foreach($_POST['data'] as $d)
								{
									$JOBCODEID	= $d['JOBCODEID'];
									$ITM_CODE	= $d['ITM_CODE'];
									$PRJCODE	= $PRJCODE;
									$ADD_VOLM	= $d['AMD_VOLM'];
									$ADD_PRICE	= $d['AMD_PRICE'];
									$AMD_CLASS	= $d['AMD_CLASS'];
									$ADD_JOBCOST= $ADD_VOLM * $ADD_PRICE;
									
									// UPDATE TO WBS / JOBLIST AND JOBDEATIL
									$paramWBS 	= array('ADD_VOLM' 		=> $ADD_VOLM,
														'ADD_PRICE'		=> $ADD_PRICE,
														'ADD_JOBCOST'	=> $ADD_JOBCOST,
														'JOBPARENT'		=> $AMD_JOBPAR,
														'JOBCODEID'		=> $JOBCODEID,
														'ITM_CODE'		=> $ITM_CODE,
														'AMD_CLASS'		=> $AMD_CLASS,
														'PRJCODE'		=> $PRJCODE);
									$this->m_project_amd->updateWBSM($paramWBS);
						
									//$this->m_project_amd->updateWBSDM($paramWBS);
								}
							}
							
							// UPDATE SI
								$paramSIH 	= array('SI_AMANDNO'	=> $AMD_NUM,
													'SI_AMANDVAL'	=> $ADD_JOBCOST,
													'SI_AMANDSTAT'	=> $AMD_STAT,
													'SI_CODE'		=> $AMD_REFNO,
													'PRJCODE'		=> $PRJCODE);
								$this->m_project_amd->updateSIH($paramSIH);
						}
						elseif($AMD_CATEG == 'SINJ') // Site Instruction. Adding new job. Add new item
						{
							foreach($_POST['data'] as $d)
							{
								$JOBCODEID	= $d['JOBCODEID'];
								$JOBPARENT	= $d['JOBPARENT'];

								$maxC		= 0;
								$sLastChld	= "SELECT IF(ISNULL(MAX(ORD_ID)), 0, MAX(ORD_ID)) as maxChild FROM tbl_joblist_detail
												WHERE JOBCODEID = '$JOBPARENT' AND PRJCODE = '$PRJCODE'";
								$rLastChld 	= $this->db->query($sLastChld)->result();
								foreach($rLastChld as $lastChld) :
									$maxC 	= $lastChld->maxChild;
								endforeach;
								$nxChld 	= $maxC+1;

								$NEWLEV 	= 0;
								$sLevHead 	= "SELECT IS_LEVEL FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBPARENT' AND PRJCODE = '$PRJCODE'";
								$rLevHead	= $this->db->query($sLevHead)->result();
								foreach($rLevHead as $rwLevHead):
									$NEWLEV = $rwLevHead->IS_LEVEL;
								endforeach;
								
								// UNTUK AMANDEMEN KATEGORI NB DAN SINJ, KODE BUDGET ITEM/KOMPONEN SUDAH DIBENTUK SAAT INPUT DOKUMEN AMANDEMEN
								// SEHINGGA TIDAK PERLU LAGI MEMBUAT PROSEDUR PENOMORAN BUDGET
									/*$nol		= "";
									$len 		= strlen($nxChld);
									if($Pattern_Length==2)
									{
										if($len==1) $nol="0";
									}
									else
									{
										$nol	= "";
									}
									$lastNumb = $nol.$nxChld;*/

								// CEK SAME ORD_ID
									$nxX 	= $nxChld;
									$sORID 	= "SELECT JOBCODEID FROM tbl_joblist_detail WHERE ORD_ID = $nxChld AND PRJCODE = '$PRJCODE'";
									$rORID	= $this->db->query($sORID)->result();
									foreach($rORID as $rwORID):
										$nxX 	= $nxX+1;
									endforeach;

									$supChld	= "UPDATE tbl_joblist_detail SET ORD_ID = ORD_ID+1 WHERE ORD_ID > $nxChld
													AND PRJCODE = '$PRJCODE'";
									$this->db->query($supChld);

									$sUPD 	= "UPDATE tbl_joblist_detail SET ORD_ID = $nxX WHERE JOBCODEID = '$JOBCODEID'
												AND PRJCODE = '$PRJCODE'";
									$this->db->query($sUPD);

								// START : ADD TO BOQ
									$bq['ORD_ID']		= $nxX;
									$bq['JOBCODEID']	= $JOBCODEID;
									$bq['JOBCODEIDV']	= $JOBCODEID;
									$bq['JOBPARENT']	= $JOBPARENT;
									$bq['JOBCOD2']		= $AMD_NUM;
									$bq['PRJCODE']		= $PRJCODE;
									$bq['PRJCODE_HO']	= $PRJCODE_HO;
									$bq['PRJPERIOD']	= $PRJCODE;
									$bq['PRJPERIOD_P']	= $PRJCODE_HO;
									$bq['ITM_CODE']		= $d['ITM_CODE'];
									$bq['JOBDESC']		= $d['JOBDESC'];
									$bq['JOBGRP']		= $d['ITM_GROUP'];
									$bq['JOBTYPE']		= "SINJ";
									$bq['JOBUNIT']		= $d['ITM_UNIT'];
									$bq['JOBLEV']		= $NEWLEV;
									$bq['JOBVOLM']		= $d['AMD_VOLM'];
									$bq['PRICE']		= $d['AMD_PRICE'];
									$bq['JOBCOST']		= $d['AMD_PRICE'];							
									$JOBVOLM			= $d['AMD_VOLM'];
									$PRICE				= $d['AMD_PRICE'];
									$JOBCOST			= $JOBVOLM * $PRICE;
									$bq['JOBCOST']		= $JOBCOST;
									$bq['Patt_Number']	= $nxChld;
									$bq['ISHEADER']		= 0;
									//$bq['ITM_NEED']	= 1;
									$bq['ISLAST']		= 1;

									$this->db->insert('tbl_boqlist',$bq);
								// END : ADD TO BOQ

								// START : ADD TO JOBLIST
									$nxChld				= $nxChld+1;
									$e['ORD_ID']		= $nxChld;
									$e['JOBPARENT']		= $JOBPARENT;
									$e['JOBCODEID']		= $JOBCODEID;
									$e['JOBCODEIDV']	= $JOBCODEID;
									$e['JOBCOD2']		= $AMD_NUM;
									$e['PRJCODE']		= $PRJCODE;
									$e['PRJCODE_HO']	= $PRJCODE_HO;
									$e['PRJPERIOD']		= $PRJCODE;
									$e['PRJPERIOD_P']	= $PRJCODE_HO;
									$e['ITM_CODE']		= $d['ITM_CODE'];
									$e['JOBDESC']		= $d['JOBDESC'];
									$e['JOBGRP']		= $d['ITM_GROUP'];
									$e['JOBTYPE']		= "SINJ";
									$e['JOBUNIT']		= $d['ITM_UNIT'];
									$e['JOBLEV']		= $NEWLEV;
									$e['JOBVOLM']		= $d['AMD_VOLM'];
									$e['PRICE']			= $d['AMD_PRICE'];
									$e['JOBCOST']		= $d['AMD_PRICE'];							
									$JOBVOLM			= $d['AMD_VOLM'];
									$PRICE				= $d['AMD_PRICE'];
									$JOBCOST			= $JOBVOLM * $PRICE;
									$e['JOBCOST']		= $JOBCOST;
									$e['Patt_Number']	= $nxChld;
									$e['ISHEADER']		= 0;
									//$e['ITM_NEED']	= 1;
									$e['ISLAST']		= 1;

									$this->db->insert('tbl_joblist',$e);
								// END : ADD TO JOBLIST

								// START : ADD TO JOBLIST DETAIL
									$dt['ORD_ID']		= $nxChld;
									$dt['JOBCODEDET']	= $JOBCODEID;
									$dt['JOBCODEID']	= $JOBCODEID;
									$dt['JOBPARENT']	= $JOBPARENT;
									$dt['JOBCOD2']		= $AMD_NUM;
									$dt['PRJCODE']		= $PRJCODE;
									$dt['PRJCODE_HO']	= $PRJCODE_HO;
									$dt['PRJPERIOD']	= $PRJCODE;
									$dt['PRJPERIOD_P']	= $PRJCODE_HO;
									$dt['JOBDESC']		= $d['JOBDESC'];
									$dt['ITM_GROUP']	= $d['ITM_GROUP'];
									$dt['GROUP_CATEG']	= $d['ITM_GROUP'];
									$dt['ITM_CODE']		= $d['ITM_CODE'];
									$dt['ITM_UNIT']		= $d['ITM_UNIT'];
									$dt['ITM_VOLM']		= $d['AMD_VOLM'];
									$ADD_VOLM			= $d['AMD_VOLM'];
									$dt['ITM_PRICE']	= $d['AMD_PRICE'];
									$dt['ITM_LASTP']	= $d['AMD_PRICE'];
									$dt['ITM_BUDG']		= $JOBCOST;
									//$dt['ADD_VOLM']	= $d['AMD_VOLM'];
									//$dt['ADD_PRICE']	= $d['AMD_PRICE'];
									//$dt['ADD_JOBCOST']= $JOBCOST;
									$dt['IS_LEVEL']		= $NEWLEV;
									$dt['ISLAST']		= 1;
									$dt['Patt_Number']	= $nxChld;
									
									$this->db->insert('tbl_joblist_detail',$dt);
								// START : ADD TO JOBLIST DETAIL
								
								// UPDATE SI
								$paramSIH 	= array('SI_AMANDNO'	=> $AMD_NUM,
													'SI_AMANDVAL'	=> $JOBCOST,
													'SI_AMANDSTAT'	=> $AMD_STAT,
													'SI_CODE'		=> $AMD_REFNO,
													'PRJCODE'		=> $PRJCODE);
								$this->m_project_amd->updateSIH($paramSIH);	
								
								$ITM_CODE	= $d['ITM_CODE'];
								$sql4		= "UPDATE tbl_item A SET A.ITM_VOLMBG = 
												(
													SELECT SUM(B.ITM_VOLM + B.ADD_VOLM - B.ADDM_VOLM) 
													FROM tbl_joblist_detail B 
													WHERE B.ITM_CODE = A.ITM_CODE
														AND B.PRJCODE = '$PRJCODE'
														AND B.ITM_CODE = '$ITM_CODE'
												)
												WHERE A.PRJCODE = '$PRJCODE'
													AND A.ITM_CODE = '$ITM_CODE'";
								$this->db->query($sql4);

								/*$ITM_VOLMBG	= 0;
								$ADDMVOLM	= 0;
								$ITM_IN		= 0;
								$sqlITM		= "SELECT ITM_VOLMBG, ADDMVOLM, ITM_IN FROM tbl_item 
												WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
								$resITM		= $this->db->query($sqlITM)->result();
								foreach($resITM as $rowITM):
									$ITM_VOLMBG	= $rowITM->ITM_VOLMBG;
									$ADDMVOLM	= $rowITM->ADDMVOLM;
									$ITM_IN		= $rowITM->ITM_IN;
								endforeach;
								$ITM_VOLMBGR	= $ITM_VOLMBG - $ITM_IN;
								$ADDMVOLM2		= $ADDMVOLM + $ADD_VOLM;

								$sql1	= "UPDATE tbl_item SET ITM_VOLMBGR = $ITM_VOLMBGR, ADDMVOLM = $ADDMVOLM2
											WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
								$this->db->query($sql1);
				
								$sql6	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST + $JOBCOST
											WHERE JOBCODEID = '$JOBPARENT1' AND PRJCODE = '$PRJCODE'";
								$this->db->query($sql6);
								
								$sql7	= "UPDATE tbl_boqlist SET JOBCOST = JOBCOST + $JOBCOST
											WHERE JOBCODEID = '$JOBPARENT1' AND PRJCODE = '$PRJCODE'";
								$this->db->query($sql7);*/
								
								$ITM_VOLMBG	= 0;
								$ADDMVOLM	= 0;
								$ADDVOLM	= 0;
								$ADDCOST	= 0;
								$ITM_IN		= 0;
								$sqlITM		= "SELECT ITM_VOLMBG, ADDVOLM, ADDCOST, ITM_IN FROM tbl_item 
												WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
								$resITM		= $this->db->query($sqlITM)->result();
								foreach($resITM as $rowITM):
									$ITM_VOLMBG	= $rowITM->ITM_VOLMBG;
									$ADDVOLM	= $rowITM->ADDVOLM;
									$ADDCOST	= $rowITM->ADDCOST;
									$ITM_IN		= $rowITM->ITM_IN;
								endforeach;	
								$ITM_VOLMBGR	= $ITM_VOLMBG - $ITM_IN;
								//$ADDMVOLM2	= $ADDMVOLM + $ADD_VOLM;
								
								$sql1	= "UPDATE tbl_item SET ITM_VOLMBGR = $ITM_VOLMBGR, ADDVOLM = ADDVOLM + $ADD_VOLM,
												ADDCOST = ADDCOST + $JOBCOST
											WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
								$this->db->query($sql1);
				
								$sql6	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST + $JOBCOST
											WHERE JOBCODEID = '$JOBPARENT1' AND PRJCODE = '$PRJCODE'";
								$this->db->query($sql6);
								
								$sql7	= "UPDATE tbl_boqlist SET JOBCOST = JOBCOST + $JOBCOST
											WHERE JOBCODEID = '$JOBPARENT1' AND PRJCODE = '$PRJCODE'";
								$this->db->query($sql7);	
							}
						}
						elseif($AMD_CATEG == 'OTH') // Other
						{
							foreach($_POST['dataIOB'] as $d)
							{
								$JOBCODEID		= $d['JOBCODEID'];
								$JOBPARENT		= $d['JOBPARENT'];
								$ITM_CODE		= $d['ITM_CODE'];
								$ADD_VOLM		= $d['AMD_VOLM'];
								$ADD_PRICE		= $d['AMD_PRICE'];
								$AMD_TOTAL		= $d['AMD_TOTAL'];
								$AMD_CLASS		= $d['AMD_CLASS'];
								$ADD_JOBCOST	= $AMD_TOTAL;

								$ITM_GROUP 		= "";
								$ITM_UNIT 		= "";
								$s_01 			= "SELECT ITM_GROUP, ITM_UNIT FROM tbl_item
													WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' LIMIT 1";
								$r_01 			= $this->db->query($s_01)->result();
								foreach($r_01 as $rw_01):
									$ITM_GROUP 	= $rw_01->ITM_GROUP;
									$ITM_UNIT 	= $rw_01->ITM_UNIT;
								endforeach;
								$d['PRJCODE']	= $PRJCODE;
								$d['ITM_GROUP']	= $ITM_GROUP;
								$d['ITM_UNIT']	= $ITM_UNIT;

								// UPDATE TO WBS / JOBLIST AND JOBDEATIL. LAST CHECKED : 21-10-29
									$paramWBS 	= array('ADD_VOLM' 		=> $ADD_VOLM,
														'ADD_PRICE'		=> $ADD_PRICE,
														'ADD_JOBCOST'	=> $ADD_JOBCOST,
														'JOBPARENT'		=> $JOBPARENT,
														'JOBCODEID'		=> $JOBCODEID,
														'ITM_CODE'		=> $ITM_CODE,
														'AMD_CLASS'		=> $AMD_CLASS,
														'PRJCODE'		=> $PRJCODE);
									$this->m_project_amd->updateOTHWBS($paramWBS);
							}

							foreach($_POST['dataSUB'] as $ds)
							{
								$JOBCODEID	= $ds['JOBCODEID'];				// JOBCODEID YANG DIKURANGI
								$JOBPARENT	= $ds['JOBPARENT'];
								$ITM_CODE	= $ds['ITM_CODE'];				// ITEM YANG DIKURANGI
								$ADD_VOLM	= $ds['AMD_VOLM'];				// VOLUME SAAT INI (SISA YANG BELUM DIGUNAKAN / DIREQUEST)
								$ADD_PRICE	= $ds['AMD_PRICE'];				// HARGA = NILAI TOTAL (SETELAH - NILAI TRANSFER) / VOLUME SAAT INI
								$AMD_TOTAL	= $ds['AMD_TOTAL'];				// VOLUME SISA * HARGA TERBARU
								$AMD_TOTTSF	= $ds['AMD_TOTTSF'];			// NILAI YANG DITRANSFER
								$AMD_CLASS	= $ds['AMD_CLASS'];				// ALWAYS 0
								$ADD_JOBCOST= $AMD_TOTAL;

								$ds['AMD_NUM']	= $AMD_NUM;
								$ds['PRJCODE']	= $PRJCODE;
								$ITM_CODE 		= $ds['ITM_CODE'];
								$ITM_GROUP 		= "";
								$ITM_UNIT 		= "";
								$s_01 			= "SELECT ITM_GROUP, ITM_UNIT FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' LIMIT 1";
								$r_01 			= $this->db->query($s_01)->result();
								foreach($r_01 as $rw_01):
									$ITM_GROUP 	= $rw_01->ITM_GROUP;
									$ITM_UNIT 	= $rw_01->ITM_UNIT;
								endforeach;
								$ds['ITM_GROUP']= $ITM_GROUP;
								$ds['ITM_UNIT']	= $ITM_UNIT;


								// UPDATE TO WBS / JOBLIST AND JOBDEATIL. LAST CHECKED : 21-10-29
									$paramWBS 	= array('ADD_VOLM' 		=> $ADD_VOLM,
														'ADD_PRICE'		=> $ADD_PRICE,
														'ADD_JOBCOST'	=> $ADD_JOBCOST,
														'AMD_TOTTSF'	=> $AMD_TOTTSF,
														'JOBPARENT'		=> $JOBPARENT,
														'JOBCODEID'		=> $JOBCODEID,
														'ITM_CODE'		=> $ITM_CODE,
														'AMD_CLASS'		=> $AMD_CLASS,
														'PRJCODE'		=> $PRJCODE);
									$this->m_project_amd->updateWBSMINUS_OTH($paramWBS);
							}
						}

					// START : UPDATE STATUS
						$completeName 	= $this->session->userdata['completeName'];
						$paramStat 		= array('PM_KEY' 		=> "AMD_NUM",
												'DOC_CODE' 		=> $AMD_NUM,
												'DOC_STAT' 		=> $AMD_STAT,
												'PRJCODE' 		=> $PRJCODE,
												//'CREATERNM'		=> "",
												'TBLNAME'		=> "tbl_amd_header");
						$this->m_updash->updateStatus($paramStat);
					// END : UPDATE STATUS
				}
			}
			elseif($AMD_STAT == 4)
			{
				$updAMD 		= array('AMD_MEMO'	=> $this->input->post('AMD_MEMO'),
										'AMD_STAT'	=> $this->input->post('AMD_STAT'));
				$this->m_project_amd->updateAMD($AMD_NUM, $updAMD);

				// CLEAR APROVE HISTORY
					$this->load->model('m_updash/m_updash', '', TRUE);
					$this->m_updash->delAppHist($AH_CODE);

				
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "AMD_NUM",
											'DOC_CODE' 		=> $AMD_NUM,
											'DOC_STAT' 		=> $AMD_STAT,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> "",
											'TBLNAME'		=> "tbl_amd_header");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
			}
			elseif($AMD_STAT == 5)
			{
				$updAMD 		= array('AMD_MEMO'	=> $this->input->post('AMD_MEMO'),
										'AMD_STAT'	=> $this->input->post('AMD_STAT'));
				$this->m_project_amd->updateAMD($AMD_NUM, $updAMD);
				
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "AMD_NUM",
											'DOC_CODE' 		=> $AMD_NUM,
											'DOC_STAT' 		=> $AMD_STAT,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> "",
											'TBLNAME'		=> "tbl_amd_header");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
			}

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $AMD_NUM;
				$MenuCode 		= 'MN038';
				$TTR_CATEG		= 'UP-APP';
				
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
			
			$url			= site_url('c_comprof/c_am1h0db2/i1dah80Idx_pek/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
}