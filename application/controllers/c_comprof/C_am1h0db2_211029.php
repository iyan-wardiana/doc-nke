<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 22 April 2018
 * File Name	= C_am1h0db2.php
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
				$JOBDESC	= $dataI['JOBDESC'];
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
										  $JOBDESC,
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
			
			$AMD_JOBPAR		= $this->input->post('JOBCODEID');	// PARENT
			$AMD_JOBID		= $this->input->post('JOBCODEID');
			
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

			// DI HOLD
				/*if($AMD_CATEG == 'SI')
				{
					$AMD_FUNC 		= $this->input->post('AMD_FUNC');		// Plus / Minus
					$AMD_REFNO 		= $this->input->post('AMD_REFNO');		// SI Number
					$AMD_REFNOAM 	= $this->input->post('AMD_REFNOAM');	// SI Total
					$AMD_DESC 		= $this->input->post('AMD_DESC');		// Job Name if SI New
					$AMD_UNIT 		= $this->input->post('AMD_UNIT');
				}
				if($AMD_CATEG == 'SINJ')
				{
					$AMD_REFNO 		= $this->input->post('AMD_REFNO');
					$AMD_REFNOAM 	= $this->input->post('AMD_REFNOAM');
					$NEW_JOBCODEID 	= $this->input->post('NEW_JOBCODEID');
					$AMD_DESC 		= $this->input->post('AMD_DESC');
					$AMD_UNIT 		= $this->input->post('AMD_UNIT');
					
					//$AMD_JOBPAR	= $this->input->post('JOBCODEID');		// PARENT
					//$AMD_JOBID	= $this->input->post('NEW_JOBCODEID');	// diganti dengan JOBCODEID karena header ada fasilitas khusus
					//$AMD_JOBID	= $this->input->post('JOBCODEID');
				}*/
			
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
								'AMD_TYPE'		=> $AMD_TYPE,
								'AMD_CATEG'		=> $AMD_CATEG,
								'AMD_FUNC'		=> $AMD_FUNC,
								'AMD_REFNO'		=> $AMD_REFNO,
								'AMD_REFNOAM'	=> $AMD_REFNOAM,
								'AMD_JOBPAR'	=> $AMD_JOBPAR,
								'AMD_JOBID'		=> $AMD_JOBID,
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

			foreach($_POST['data'] as $d)
			{
				$d['AMD_NUM']	= $AMD_NUM;
				//$d['JOBCODEID']	= $AMD_JOBID;
				$d['JOBPARENT']	= $AMD_JOBPAR;
				$this->db->insert('tbl_amd_detail',$d);
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
			
			$AMD_JOBPAR		= $this->input->post('JOBCODEID');	// PARENT
			$AMD_JOBID		= $this->input->post('JOBCODEID');

			if($AMD_CATEG == 'SI' || $AMD_CATEG == 'SINJ')
			{
				$AMD_FUNC 		= $this->input->post('AMD_FUNC');		// Plus / Minus
				$AMD_REFNO 		= $this->input->post('AMD_REFNO');		// SI Number
				$AMD_REFNOAM 	= $this->input->post('AMD_REFNOAM');	// SI Total
				$AMD_DESC 		= $this->input->post('AMD_DESC');		// Job Name if SI New
				$AMD_UNIT 		= $this->input->post('AMD_UNIT');
			}
			
			// HOLD
				/*$AMD_FUNC 		= '';
				$AMD_REFNO 		= '';
				$AMD_REFNOAM	= 0;
				$AMD_DESC		= '';
				$NEW_JOBCODEID	= '';
				$AMD_UNIT		= '';
				if($AMD_CATEG == 'SI')
				{
					$AMD_FUNC 		= $this->input->post('AMD_FUNC');		// Plus / Minus
					$AMD_REFNO 		= $this->input->post('AMD_REFNO');		// SI Number
					$AMD_REFNOAM 	= $this->input->post('AMD_REFNOAM');	// SI Total
					$AMD_DESC 		= $this->input->post('AMD_DESC');		// Job Name if SI New
					$AMD_UNIT 		= $this->input->post('AMD_UNIT');
				}
				if($AMD_CATEG == 'SINJ')
				{
					$AMD_REFNO 		= $this->input->post('AMD_REFNO');
					$AMD_REFNOAM 	= $this->input->post('AMD_REFNOAM');
					$NEW_JOBCODEID 	= $this->input->post('NEW_JOBCODEID');
					$AMD_DESC 		= $this->input->post('AMD_DESC');
					$AMD_UNIT 		= $this->input->post('AMD_UNIT');
					
					//$AMD_JOBPAR		= $this->input->post('JOBCODEID');		// PARENT
					//$AMD_JOBID		= $this->input->post('NEW_JOBCODEID');	// diganti dengan JOBCODEID karena header ada fasilitas khusus
					//$AMD_JOBID		= $this->input->post('JOBCODEID');
				}*/
			
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
			
			if($AMD_STAT == 9) // VOID
			{
				$updateAMD 	= array('AMD_STAT'		=> $AMD_STAT);
				$this->m_project_amd->updateAMD($AMD_NUM, $updateAMD);
				
				foreach($_POST['data'] as $d)
				{
					if($AMD_CATEG == 'OB')			// OK - Over Budget. Not added new item, update qty only
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
											'JOBPARENT'		=> $JOBPARENT1,
											'JOBCODEID'		=> $JOBCODEID,
											'ITM_CODE'		=> $ITM_CODE,
											'AMD_CLASS'		=> $AMD_CLASS,
											'PRJCODE'		=> $PRJCODE);
						$this->m_project_amd->updateWBSMin($paramWBS);
					}
					elseif($AMD_CATEG == 'SI') 		// OK - Site Instruction. Fure Update
					{
						// Dalam kondisi jika hanya perubahan atau penambahan item pada pekerjaan yang sudah ada.	
						if($AMD_FUNC == 'PLUS')
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
												'JOBCODEID'		=> $JOBCODEID,
												'ITM_CODE'		=> $ITM_CODE,
												'AMD_CLASS'		=> $AMD_CLASS,
												'PRJCODE'		=> $PRJCODE);
							$this->m_project_amd->updateWBSMin($paramWBS);
						}
						elseif($AMD_FUNC == 'MIN')
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
												'JOBCODEID'		=> $JOBCODEID,
												'ITM_CODE'		=> $ITM_CODE,
												'AMD_CLASS'		=> $AMD_CLASS,
												'PRJCODE'		=> $PRJCODE);
							$this->m_project_amd->updateWBSMPlus($paramWBS);
						}
						
						// UPDATE SI
						$paramSIH 	= array('SI_AMANDNO'	=> $AMD_NUM,
											'SI_AMANDVAL'	=> $JOBCOST,
											'SI_AMANDSTAT'	=> $AMD_STAT,
											'SI_CODE'		=> $AMD_REFNO,
											'PRJCODE'		=> $PRJCODE);
						$this->m_project_amd->updateSIHCanc($paramSIH);
					}
					elseif($AMD_CATEG == 'SINJ') 	// OK - Site Instruction. Adding new job. Add new item
					{
						$JOBCODEID	= $d['JOBCODEID'];
						$ITM_CODE	= $d['ITM_CODE'];
						$PRJCODE	= $PRJCODE;
						$ADD_VOLM	= $d['AMD_VOLM'];
						$ADD_PRICE	= $d['AMD_PRICE'];
						$AMD_CLASS	= $d['AMD_CLASS'];
						$ADD_JOBCOST= $ADD_VOLM * $ADD_PRICE;
						$JOBCOST	= $ADD_VOLM * $ADD_PRICE;
						
						// REMOVE NEW JOB
						$this->m_project_amd->deleteBOQ($AMD_NUM);
						$this->m_project_amd->deleteJL($AMD_NUM);
						$this->m_project_amd->deleteJLD($AMD_NUM);
						
						// UPDATE SI
						$paramSIH 	= array('SI_AMANDNO'	=> $AMD_NUM,
											'SI_AMANDVAL'	=> $JOBCOST,
											'SI_AMANDSTAT'	=> $AMD_STAT,
											'SI_CODE'		=> $AMD_REFNO,
											'PRJCODE'		=> $PRJCODE);
						$this->m_project_amd->updateSIHCanc($paramSIH);	
						
						$ITM_CODE	= $d['ITM_CODE'];
						
						$sqlITMC 	= "tbl_joblist_detail WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
						$resITMC 	= $this->db->count_all($sqlITMC);
						if($resITMC > 0)
						{
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
							
							$ITM_VOLMBG	= 0;
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
							$ADDMVOLM2		= $ADDMVOLM - $ADD_VOLM;
							
							$sql1	= "UPDATE tbl_item SET ITM_VOLMBGR = $ITM_VOLMBGR, ADDMVOLM = $ADDMVOLM2
										WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
							$this->db->query($sql1);
						}
						else
						{
							$sql4	= "UPDATE tbl_item A SET A.ITM_VOLMBG = 0 WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_CODE = '$ITM_CODE'";
							$this->db->query($sql4);
							
							$sql1	= "UPDATE tbl_item SET ITM_VOLMBGR = 0, ADDMVOLM = 0 WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
							$this->db->query($sql1);
						}
			
						$sql6	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST - $JOBCOST
									WHERE JOBCODEID = '$JOBPARENT1' AND PRJCODE = '$PRJCODE'";
						$this->db->query($sql6);
						
						$sql7	= "UPDATE tbl_boqlist SET JOBCOST = JOBCOST - $JOBCOST
									WHERE JOBCODEID = '$JOBPARENT1' AND PRJCODE = '$PRJCODE'";
						$this->db->query($sql7);
						
						
					}
					elseif($AMD_CATEG == 'NB') 		// OK - Not Budgeting
					{
						$JOBCODEID	= $d['JOBCODEID'];
						$ITM_CODE	= $d['ITM_CODE'];
						$PRJCODE	= $PRJCODE;
						$ADD_VOLM	= $d['AMD_VOLM'];
						$ADD_PRICE	= $d['AMD_PRICE'];
						$AMD_CLASS	= $d['AMD_CLASS'];
						$ADD_JOBCOST= $ADD_VOLM * $ADD_PRICE;
						$JOBCOST	= $ADD_VOLM * $ADD_PRICE;
						
						// REMOVE NEW JOB
							$this->m_project_amd->deleteBOQ($AMD_NUM);
							$this->m_project_amd->deleteJL($AMD_NUM);
							$this->m_project_amd->deleteJLD($AMD_NUM);
						
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
						
						$ITM_VOLMBG	= 0;
						//$ADDMVOLM	= 0;
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
						$ADDVOLM2		= $ADDMVOLM - $ADD_VOLM;
						
						$sql1	= "UPDATE tbl_item SET ITM_VOLMBGR = $ITM_VOLMBGR, ADDVOLM = ADDVOLM - $ADD_VOLM,
										ADDCOST = ADDCOST - $JOBCOST
									WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
						$this->db->query($sql1);
		
						$sql6	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST - $JOBCOST
									WHERE JOBCODEID = '$JOBPARENT1' AND PRJCODE = '$PRJCODE'";
						$this->db->query($sql6);
						
						$sql7	= "UPDATE tbl_boqlist SET JOBCOST = JOBCOST - $JOBCOST
									WHERE JOBCODEID = '$JOBPARENT1' AND PRJCODE = '$PRJCODE'";
						$this->db->query($sql7);
					}
					elseif($AMD_CATEG == 'OTH') 	// OK - Other
					{
						$JOBCODEID	= $d['JOBCODEID'];
						$ITM_CODE	= $d['ITM_CODE'];
						$PRJCODE	= $PRJCODE;
						$ADD_VOLM	= $d['AMD_VOLM'];
						$ADD_PRICE	= $d['AMD_PRICE'];
						$AMD_CLASS	= $d['AMD_CLASS'];
						$ADD_JOBCOST= $ADD_VOLM * $ADD_PRICE;
						$JOBCOST	= $ADD_VOLM * $ADD_PRICE;
						
						// REMOVE NEW JOB
						$this->m_project_amd->deleteBOQ($AMD_NUM);
						$this->m_project_amd->deleteJL($AMD_NUM);
						$this->m_project_amd->deleteJLD($AMD_NUM);
						
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
						
						$ITM_VOLMBG	= 0;
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
						$ADDMVOLM2		= $ADDMVOLM - $ADD_VOLM;
						
						$sql1		= "UPDATE tbl_item SET ITM_VOLMBGR = $ITM_VOLMBGR, ADDMVOLM = $ADDMVOLM2
										WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
						$this->db->query($sql1);
		
						$sql6	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST - $JOBCOST
									WHERE JOBCODEID = '$JOBPARENT1' AND PRJCODE = '$PRJCODE'";
						$this->db->query($sql6);
						
						$sql7	= "UPDATE tbl_boqlist SET JOBCOST = JOBCOST - $JOBCOST
									WHERE JOBCODEID = '$JOBPARENT1' AND PRJCODE = '$PRJCODE'";
						$this->db->query($sql7);
					}
				}
			}
			else
			{
				$updateAMD 	= array('AMD_CODE'		=> $AMD_CODE,
									'PRJCODE'		=> $PRJCODE,
									'AMD_TYPE'		=> $AMD_TYPE,
									'AMD_CATEG'		=> $AMD_CATEG,
									'AMD_FUNC'		=> $AMD_FUNC,
									'AMD_REFNO'		=> $AMD_REFNO,
									'AMD_REFNOAM'	=> $AMD_REFNOAM,
									'AMD_JOBPAR'	=> $AMD_JOBPAR,
									'AMD_JOBID'		=> $AMD_JOBID,
									'AMD_DATE'		=> $AMD_DATE,
									'AMD_DESC'		=> $AMD_DESC,
									'AMD_UNIT'		=> $AMD_UNIT,
									'AMD_NOTES'		=> $AMD_NOTES,
									'AMD_AMOUNT'	=> $AMD_AMOUNT,
									'AMD_STAT'		=> $AMD_STAT);
				$this->m_project_amd->updateAMD($AMD_NUM, $updateAMD);
				
				$this->m_project_amd->deleteAMDDet($AMD_NUM);
				
				foreach($_POST['data'] as $d)
				{
					$d['AMD_NUM']	= $AMD_NUM;
					//$d['JOBCODEID']	= $AMD_JOBID;
					$d['JOBPARENT']	= $AMD_JOBPAR;	
					$this->db->insert('tbl_amd_detail',$d);
				}
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
		$this->load->model('m_company/m_project_amd/m_project_amd', '', TRUE);
		$this->load->model('m_updash/m_updash', '', TRUE);
		
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
			$AMD_JOBID		= $this->input->post('JOBCODEID');
			
			$AMD_FUNC 		= '';
			$AMD_REFNO 		= '';
			$AMD_REFNOAM	= 0;
			$AMD_DESC		= '';
			$NEW_JOBCODEID	= '';
			$AMD_UNIT		= '';
			//echo $AMD_CATEG;
			//return false;
			if($AMD_CATEG == 'SI' || $AMD_CATEG == 'SINJ')
			{
				$AMD_FUNC 		= $this->input->post('AMD_FUNC');		// Plus / Minus
				$AMD_REFNO 		= $this->input->post('AMD_REFNO');		// SI Number
				$AMD_REFNOAM 	= $this->input->post('AMD_REFNOAM');	// SI Total
				$AMD_DESC 		= $this->input->post('AMD_DESC');		// Job Name if SI New
				$AMD_UNIT 		= $this->input->post('AMD_UNIT');
			}
			/*if($AMD_CATEG == 'SINJ')
			{
				$AMD_REFNO 		= $this->input->post('AMD_REFNO');
				$AMD_REFNOAM 	= $this->input->post('AMD_REFNOAM');
				$NEW_JOBCODEID 	= $this->input->post('NEW_JOBCODEID');
				$AMD_DESC 		= $this->input->post('AMD_DESC');
				$AMD_UNIT 		= $this->input->post('AMD_UNIT');
				
				//$AMD_JOBPAR		= $this->input->post('JOBCODEID');		// PARENT
				//$AMD_JOBID		= $this->input->post('NEW_JOBCODEID');	// diganti dengan JOBCODEID karena header ada fasilitas khusus
				//$AMD_JOBID		= $this->input->post('JOBCODEID');
			}*/
			
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
			
			$JOBPARENT1	= '';
			$JOBLEV		= 0;
			$sqlLEV		= "SELECT JOBPARENT, JOBLEV FROM tbl_joblist WHERE JOBCODEID = '$AMD_JOBID'";
			$resLEV 	= $this->db->query($sqlLEV)->result();
			foreach($resLEV as $rowLEV) :
				$JOBPARENT1	= $rowLEV->JOBPARENT;
				$JOBLEV		= $rowLEV->JOBLEV;
			endforeach;
			
			$JOBLEVNEW	= $JOBLEV + 1;
				
			$AH_CODE		= $AMD_NUM;
			$AH_APPLEV		= $this->input->post('APP_LEVEL');
			$AH_APPROVER	= $DefEmp_ID;
			$AH_APPROVED	= $AMD_APPROVED;
			$AH_NOTES		= addslashes($this->input->post('AMD_NOTES'));
			$AH_ISLAST		= $this->input->post('IS_LAST');
			
			$sqlCHLDC 		= "tbl_joblist WHERE JOBPARENT = '$AMD_JOBID'";
			$resCHLDC 		= $this->db->count_all($sqlCHLDC);
			
			$sqlCHLDDC 		= "tbl_joblist_detail WHERE JOBPARENT = '$AMD_JOBID'";
			$resCHLDDC 		= $this->db->count_all($sqlCHLDDC);
			
			$sqlLSPatt		= "SELECT MAX(Patt_Number) AS LastPatt FROM tbl_joblist";
			$resLSPatt 		= $this->db->query($sqlLSPatt)->result();
			foreach($resLSPatt as $rowLSP) :
				$LastPatt = $rowLSP->LastPatt;		
			endforeach;
			
			$sqlLSPattD	= "SELECT MAX(Patt_Number) AS LastPatt FROM tbl_joblist_detail";
			$resLSPattD 	= $this->db->query($sqlLSPattD)->result();
			foreach($resLSPattD as $rowLSPD) :
				$LastPattD = $rowLSPD->LastPatt;		
			endforeach;
			
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
					$AH_CODE		= $AMD_NUM;
					$AH_APPLEV		= $this->input->post('APP_LEVEL');
					$AH_APPROVER	= $DefEmp_ID;
					$AH_APPROVED	= $AMD_APPROVED;
					$AH_NOTES		= addslashes($this->input->post('AMD_NOTES'));
					$AH_ISLAST		= $this->input->post('IS_LAST');

					$updateAMD 		= array('AMD_MEMO'	=> $AMD_MEMO,
										'AMD_STAT'		=> $AMD_STAT);
					$this->m_project_amd->updateAMD($AMD_NUM, $updateAMD);
					
					// CREATE HEADER PEKERJAAN					
						$rowDet			= 0;
						$Pattern_Length	= 3;
						$theLast		= $resCHLDC;
						$theLastD		= $resCHLDDC;

					// PROCEDURE UNTUK GET LAST ORD_ID
						// GET FROM PARENT
							// MENGHITUNG TOTAL DETAIL YANG AKAN DITAMBAHKAN. DIGUNAKAN UNTUK NOT BUDGETING ATAU SI
								$rTotChld 	= 0;
								foreach($_POST['data'] as $d)
								{
									$rTotChld = $rTotChld+1;
								}

							// DAPATKAN NILAI ORD_ID HEADER, DIGUNAKAN SEBAGAI NILAI MAXIMUM ORD_ID
								$maxC		= 0;
								/*$sLastChld	= "SELECT IF(ISNULL(MAX(ORD_ID)), 0, MAX(ORD_ID)) as maxChild FROM tbl_joblist_detail
												WHERE JOBPARENT = '$AMD_JOBPAR' AND PRJCODE = '$PRJCODE'";*/
								$sLastChld	= "SELECT IF(ISNULL(MAX(ORD_ID)), 0, MAX(ORD_ID)) as maxChild FROM tbl_joblist_detail
												WHERE JOBCODEID = '$AMD_JOBPAR' AND PRJCODE = '$PRJCODE'";
								$rLastChld 	= $this->db->query($sLastChld)->result();
								foreach($rLastChld as $lastChld) :
									$maxC 	= $lastChld->maxChild;
								endforeach;
								$nxChld 	= $maxC;

							if($AMD_CATEG == 'NB' || $AMD_CATEG == 'SINJ')
							{
								// TANGGULANGI JIKA ADA ORD_ID YG SAMA DENGAN ORD_ID INDUK YANG DITAMBAHKAN DLM 1 ANGGARAN
									$qsLastHD0	= "SELECT JOBCODEID FROM tbl_joblist_detail WHERE ORD_ID = $maxC AND PRJCODE = '$PRJCODE'";
									$rsLastHD0 	= $this->db->query($qsLastHD0)->result();
									foreach($rsLastHD0 as $rrowHD0) :
										$JCODE 	= $rrowHD0->JOBCODEID;

										if($JCODE != $AMD_JOBPAR)
										{
											$supHD1	= "UPDATE tbl_joblist_detail SET ORD_ID = ORD_ID+$maxC WHERE JOBCODEID = '$JCODE' AND PRJCODE = '$PRJCODE'";
											$this->db->query($supHD1);
										}
									endforeach;

								// UPDATE SELURUH ORD_ID YANG ORD_ID > ORD_ID HEADER DALAM 1 ANGGARAN
									$supChld	= "UPDATE tbl_joblist_detail SET ORD_ID = ORD_ID+$rTotChld WHERE ORD_ID > $maxC AND PRJCODE = '$PRJCODE'";
									$this->db->query($supChld);
								}

						// UPDATE OTHER ORD_ID
							foreach($_POST['data'] as $d)
							{
								if($AMD_CATEG == 'OB') // Over Budget. Not added new item, update qty only
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
														'JOBPARENT'		=> $JOBPARENT1,
														'JOBCODEID'		=> $JOBCODEID,
														'ITM_CODE'		=> $ITM_CODE,
														'AMD_CLASS'		=> $AMD_CLASS,
														'PRJCODE'		=> $PRJCODE);
									$this->m_project_amd->updateWBS($paramWBS);
						
									//$this->m_project_amd->updateWBSD($paramWBS);
								}
								elseif($AMD_CATEG == 'NB') // Not Budgeting
								{
									$rowDet		= $rowDet + $resCHLDDC + 1;
									$theLast	= $LastPatt + 1;
									$theLastD	= $LastPattD + 1;
									$JOBPARENT	= $AMD_JOBID;		// TO BE A PARENT
									
									$nol		= "";
									$len 		= strlen($rowDet);
									if($Pattern_Length==3)
									{
										if($len==1) $nol="00";else if($len==2) $nol="0";
									}
									elseif($Pattern_Length==2)
									{
										if($len==1) $nol="0";
									}
									else
									{
										$nol	= "";
									}
									$lastNumb = $nol.$rowDet;
									
									$nxChld				= $nxChld+1;
									$e['ORD_ID']		= $nxChld;
									$e['JOBPARENT']		= $JOBPARENT;
									$JOBCODEID			= "$JOBPARENT.$lastNumb";
									$e['JOBCODEID']		= $JOBCODEID;
									$e['JOBCODEIDV']	= $JOBCODEID;
									$e['JOBCOD2']		= $AMD_NUM;
									$e['PRJCODE']		= $PRJCODE;
									$e['PRJCODE_HO']	= $PRJCODE_HO;
									$e['ITM_CODE']		= $d['ITM_CODE'];
									$e['JOBDESC']		= $d['JOBDESC'];
									$e['JOBGRP']		= $d['ITM_GROUP'];
									$e['JOBTYPE']		= "NB";
									$e['JOBUNIT']		= $d['ITM_UNIT'];
									$e['JOBLEV']		= $JOBLEVNEW;
									$e['JOBVOLM']		= $d['AMD_VOLM'];
									$e['PRICE']			= $d['AMD_PRICE'];
									$e['JOBCOST']		= $d['AMD_PRICE'];							
									$JOBVOLM			= $d['AMD_VOLM'];
									$PRICE				= $d['AMD_PRICE'];
									$JOBCOST			= $JOBVOLM * $PRICE;
									$e['JOBCOST']		= $JOBCOST;
									$e['Patt_Number']	= $theLast;
									$e['ISHEADER']		= 0;
									//$e['ITM_NEED']	= 1;
									$e['ISLAST']		= 1;
									
									$dt['ORD_ID']		= $nxChld;
									$dt['JOBCODEDET']	= "D.$JOBCODEID";
									$dt['JOBCODEID']	= $JOBCODEID;
									$dt['JOBPARENT']	= $JOBPARENT;
									$dt['JOBCOD2']		= $AMD_NUM;
									$dt['PRJCODE']		= $PRJCODE;
									$dt['PRJCODE_HO']	= $PRJCODE_HO;
									$dt['JOBDESC']		= $d['JOBDESC'];
									$dt['ITM_GROUP']	= $d['ITM_GROUP'];
									$dt['GROUP_CATEG']	= $d['ITM_GROUP'];
									$dt['ITM_CODE']		= $d['ITM_CODE'];
									$dt['ITM_UNIT']		= $d['ITM_UNIT'];
									//$dt['ITM_VOLM']		= $d['AMD_VOLM'];
									$ADD_VOLM			= $d['AMD_VOLM'];
									$dt['ITM_PRICE']	= $d['AMD_PRICE'];
									$dt['ITM_LASTP']	= $d['AMD_PRICE'];
									//$dt['ITM_BUDG']	= $JOBCOST;
									$dt['ADD_VOLM']		= $d['AMD_VOLM'];
									$dt['ADD_PRICE']	= $d['AMD_PRICE'];
									$dt['ADD_JOBCOST']	= $JOBCOST;
									$dt['IS_LEVEL']		= $JOBLEVNEW;
									$dt['ISLAST']		= 1;
									$dt['Patt_Number']	= $theLastD;
									
									$bq['ORD_ID']		= $nxChld;
									$bq['JOBCODEID']	= $JOBCODEID;
									$bq['JOBCODEIDV']	= $JOBCODEID;
									$bq['JOBPARENT']	= $JOBPARENT;
									$bq['JOBCOD2']		= $AMD_NUM;
									$bq['PRJCODE']		= $PRJCODE;
									$bq['PRJCODE_HO']	= $PRJCODE_HO;
									$bq['ITM_CODE']		= $d['ITM_CODE'];
									$bq['JOBDESC']		= $d['JOBDESC'];
									$bq['JOBGRP']		= $d['ITM_GROUP'];
									$bq['JOBTYPE']		= "OTH";
									$bq['JOBUNIT']		= $d['ITM_UNIT'];
									$bq['JOBLEV']		= $JOBLEVNEW;
									$bq['JOBVOLM']		= $d['AMD_VOLM'];
									$bq['PRICE']		= $d['AMD_PRICE'];
									$bq['JOBCOST']		= $d['AMD_PRICE'];							
									$JOBVOLM			= $d['AMD_VOLM'];
									$PRICE				= $d['AMD_PRICE'];
									$JOBCOST			= $JOBVOLM * $PRICE;
									$bq['JOBCOST']		= $JOBCOST;
									$bq['Patt_Number']	= $theLast;
									$bq['ISHEADER']		= 0;
									//$bq['ITM_NEED']	= 1;
									$bq['ISLAST']		= 1;
									
									// ADDING NEW JOB
									$this->db->insert('tbl_boqlist',$bq);
									$this->db->insert('tbl_joblist',$e);
									$this->db->insert('tbl_joblist_detail',$dt);
									
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
									$ADDMVOLM2		= $ADDMVOLM + $ADD_VOLM;*/
									
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
								elseif($AMD_CATEG == 'SI') // Site Instruction. Fure Update
								{
									// Dalam kondisi jika hanya perubahan atau penambahan item pada pekerjaan yang sudah ada.
									if($AMD_FUNC == 'PLUS')
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
									elseif($AMD_FUNC == 'MIN')
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
									$rowDet		= $rowDet + $resCHLDDC + 1;
									$theLast	= $LastPatt + 1;
									$theLastD	= $LastPattD + 1;
									$JOBPARENT	= $AMD_JOBID;		// TO BE A PARENT
									
									$nol		= "";
									$len 		= strlen($rowDet);
									if($Pattern_Length==3)
									{
										if($len==1) $nol="00";else if($len==2) $nol="0";
									}
									elseif($Pattern_Length==2)
									{
										if($len==1) $nol="0";
									}
									else
									{
										$nol	= "";
									}
									$lastNumb = $nol.$rowDet;
									
									$nxChld				= $nxChld+1;
									$e['ORD_ID']		= $nxChld;
									$e['JOBPARENT']		= $JOBPARENT;
									$JOBCODEID			= "$JOBPARENT.$lastNumb";
									$e['JOBCODEID']		= $JOBCODEID;
									$e['JOBCODEIDV']	= $JOBCODEID;
									$e['JOBCOD2']		= $AMD_NUM;
									$e['PRJCODE']		= $PRJCODE;
									$e['PRJCODE_HO']	= $PRJCODE_HO;
									$e['ITM_CODE']		= $d['ITM_CODE'];
									$e['JOBDESC']		= $d['JOBDESC'];
									$e['JOBGRP']		= $d['ITM_GROUP'];
									$e['JOBTYPE']		= "NB";
									$e['JOBUNIT']		= $d['ITM_UNIT'];
									$e['JOBLEV']		= $JOBLEVNEW;
									$e['JOBVOLM']		= $d['AMD_VOLM'];
									$e['PRICE']			= $d['AMD_PRICE'];
									$e['JOBCOST']		= $d['AMD_PRICE'];							
									$JOBVOLM			= $d['AMD_VOLM'];
									$PRICE				= $d['AMD_PRICE'];
									$JOBCOST			= $JOBVOLM * $PRICE;
									$e['JOBCOST']		= $JOBCOST;
									$e['Patt_Number']	= $theLast;
									$e['ISHEADER']		= 0;
									//$e['ITM_NEED']	= 1;
									$e['ISLAST']		= 1;
									
									$dt['ORD_ID']		= $nxChld;
									$dt['JOBCODEDET']	= "D.$JOBCODEID";
									$dt['JOBCODEID']	= $JOBCODEID;
									$dt['JOBPARENT']	= $JOBPARENT;
									$dt['JOBCOD2']		= $AMD_NUM;
									$dt['PRJCODE']		= $PRJCODE;
									$dt['PRJCODE_HO']	= $PRJCODE_HO;
									$dt['JOBDESC']		= $d['JOBDESC'];
									$dt['ITM_GROUP']	= $d['ITM_GROUP'];
									$dt['GROUP_CATEG']	= $d['ITM_GROUP'];
									$dt['ITM_CODE']		= $d['ITM_CODE'];
									$dt['ITM_UNIT']		= $d['ITM_UNIT'];
									//$dt['ITM_VOLM']		= $d['AMD_VOLM'];
									$ADD_VOLM			= $d['AMD_VOLM'];
									$dt['ITM_PRICE']	= $d['AMD_PRICE'];
									$dt['ITM_LASTP']	= $d['AMD_PRICE'];
									//$dt['ITM_BUDG']	= $JOBCOST;
									$dt['ADD_VOLM']		= $d['AMD_VOLM'];
									$dt['ADD_PRICE']	= $d['AMD_PRICE'];
									$dt['ADD_JOBCOST']	= $JOBCOST;
									$dt['IS_LEVEL']		= $JOBLEVNEW;
									$dt['ISLAST']		= 1;
									$dt['Patt_Number']	= $theLastD;
									
									$bq['ORD_ID']		= $nxChld;
									$bq['JOBCODEID']	= $JOBCODEID;
									$bq['JOBCODEIDV']	= $JOBCODEID;
									$bq['JOBPARENT']	= $JOBPARENT;
									$bq['JOBCOD2']		= $AMD_NUM;
									$bq['PRJCODE']		= $PRJCODE;
									$bq['PRJCODE_HO']	= $PRJCODE_HO;
									$bq['ITM_CODE']		= $d['ITM_CODE'];
									$bq['JOBDESC']		= $d['JOBDESC'];
									$bq['JOBGRP']		= $d['ITM_GROUP'];
									$bq['JOBTYPE']		= "OTH";
									$bq['JOBUNIT']		= $d['ITM_UNIT'];
									$bq['JOBLEV']		= $JOBLEVNEW;
									$bq['JOBVOLM']		= $d['AMD_VOLM'];
									$bq['PRICE']		= $d['AMD_PRICE'];
									$bq['JOBCOST']		= $d['AMD_PRICE'];							
									$JOBVOLM			= $d['AMD_VOLM'];
									$PRICE				= $d['AMD_PRICE'];
									$JOBCOST			= $JOBVOLM * $PRICE;
									$bq['JOBCOST']		= $JOBCOST;
									$bq['Patt_Number']	= $theLast;
									$bq['ISHEADER']		= 0;
									//$bq['ITM_NEED']	= 1;
									$bq['ISLAST']		= 1;
									
									// ADDING NEW JOB
									$this->db->insert('tbl_boqlist',$bq);
									$this->db->insert('tbl_joblist',$e);
									$this->db->insert('tbl_joblist_detail',$dt);
									
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
								elseif($AMD_CATEG == 'OTH') // Other
								{
									$rowDet		= $rowDet + $resCHLDDC + 1;
									$theLast	= $LastPatt + 1;
									$theLastD	= $LastPattD + 1;
									$JOBPARENT	= $AMD_JOBID;		// TO BE A PARENT
									
									$nol	= "";
									$len = strlen($rowDet);
									if($Pattern_Length==3)
									{
										if($len==1) $nol="00";else if($len==2) $nol="0";
									}
									elseif($Pattern_Length==2)
									{
										if($len==1) $nol="0";
									}
									$lastNumb = $nol.$rowDet;
									
									$e['JOBPARENT']		= $JOBPARENT;
									$JOBCODEID			= "$JOBPARENT.$lastNumb";
									$e['JOBCODEID']		= $JOBCODEID;
									$e['JOBCODEIDV']	= $JOBCODEID;
									$e['JOBCOD2']		= $AMD_NUM;
									$e['PRJCODE']		= $PRJCODE;
									$e['PRJCODE_HO']	= $PRJCODE_HO;
									$e['ITM_CODE']		= $d['ITM_CODE'];
									$e['JOBDESC']		= $d['JOBDESC'];
									$e['JOBGRP']		= $d['ITM_GROUP'];
									$e['JOBTYPE']		= "OTH";
									$e['JOBUNIT']		= $d['ITM_UNIT'];
									$e['JOBLEV']		= $JOBLEVNEW;
									$e['JOBVOLM']		= $d['AMD_VOLM'];
									$e['PRICE']			= $d['AMD_PRICE'];
									$e['JOBCOST']		= $d['AMD_PRICE'];							
									$JOBVOLM			= $d['AMD_VOLM'];
									$PRICE				= $d['AMD_PRICE'];
									$JOBCOST			= $JOBVOLM * $PRICE;
									$e['JOBCOST']		= $JOBCOST;
									$e['Patt_Number']	= $theLast;
									$e['ISHEADER']		= 0;
									//$e['ITM_NEED']	= 1;
									$e['ISLAST']		= 1;
									
									$dt['JOBCODEDET']	= "$JOBCODEID";
									$dt['JOBCODEID']	= $JOBCODEID;
									$dt['JOBPARENT']	= $JOBPARENT;
									$dt['JOBCOD2']		= $AMD_NUM;
									$dt['PRJCODE']		= $PRJCODE;
									$dt['PRJCODE_HO']	= $PRJCODE_HO;
									$dt['JOBDESC']		= $d['JOBDESC'];
									$dt['ITM_GROUP']	= $d['ITM_GROUP'];
									$dt['GROUP_CATEG']	= $d['ITM_GROUP'];
									$dt['ITM_CODE']		= $d['ITM_CODE'];
									$dt['ITM_UNIT']		= $d['ITM_UNIT'];
									//$dt['ITM_VOLM']		= $d['AMD_VOLM'];
									$dt['ITM_PRICE']	= $d['AMD_PRICE'];
									$dt['ITM_LASTP']	= $d['AMD_PRICE'];
									//$dt['ITM_BUDG']	= $JOBCOST;
									$dt['ADD_VOLM']		= $d['AMD_VOLM'];
									$dt['ADD_PRICE']	= $d['AMD_PRICE'];
									$dt['ADD_JOBCOST']	= $JOBCOST;
									
									$ADD_VOLM			= $d['AMD_VOLM'];
									$dt['IS_LEVEL']		= $JOBLEVNEW;
									$dt['ISLAST']		= 1;
									$dt['Patt_Number']	= $theLastD;
									
									$bq['JOBCODEID']	= $JOBCODEID;
									$bq['JOBCODEIDV']	= $JOBCODEID;
									$bq['JOBPARENT']	= $JOBPARENT;
									$bq['JOBCOD2']		= $AMD_NUM;
									$bq['PRJCODE']		= $PRJCODE;
									$bq['PRJCODE_HO']	= $PRJCODE_HO;
									$bq['ITM_CODE']		= $d['ITM_CODE'];
									$bq['JOBDESC']		= $d['JOBDESC'];
									$bq['JOBGRP']		= $d['ITM_GROUP'];
									$bq['JOBTYPE']		= "OTH";
									$bq['JOBUNIT']		= $d['ITM_UNIT'];
									$bq['JOBLEV']		= $JOBLEVNEW;
									$bq['JOBVOLM']		= $d['AMD_VOLM'];
									$bq['PRICE']			= $d['AMD_PRICE'];
									$bq['JOBCOST']		= $d['AMD_PRICE'];							
									$JOBVOLM			= $d['AMD_VOLM'];
									$PRICE				= $d['AMD_PRICE'];
									$JOBCOST			= $JOBVOLM * $PRICE;
									$bq['JOBCOST']		= $JOBCOST;
									$bq['Patt_Number']	= $theLast;
									$bq['ISHEADER']		= 0;
									//$bq['ITM_NEED']	= 1;
									$bq['ISLAST']		= 1;
									
									// ADDING NEW JOB
									$this->db->insert('tbl_boqlist',$bq);
									$this->db->insert('tbl_joblist',$e);
									$this->db->insert('tbl_joblist_detail',$dt);
									
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
									$ADDMVOLM2		= $ADDMVOLM + $ADD_VOLM;*/
									
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
									
									/*$sql1		= "UPDATE tbl_item SET ITM_VOLMBGR = $ITM_VOLMBGR, ADDMVOLM = $ADDMVOLM2
													WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";*/
									$sql1		= "UPDATE tbl_item SET ITM_VOLMBGR = $ITM_VOLMBGR, ADDVOLM = ADDVOLM + $ADD_VOLM,
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

		setlocale(LC_ALL, 'id-ID', 'id_ID');

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
				$ORD_ID 		= $dataI['ORD_ID'];
				$JOBCODEDET 	= $dataI['JOBCODEDET'];
				$JOBCODEID 		= $dataI['JOBCODEID'];
				$JOBPARENT 		= $dataI['JOBPARENT'];
				$JOBCODE 		= $dataI['JOBCODE'];
				$JOBDESC		= $dataI['JOBDESC'];
				$ITM_GROUP		= $dataI['ITM_GROUP'];
				$ITM_CODE		= $dataI['ITM_CODE'];
				$ITM_UNIT		= $dataI['ITM_UNIT'];
				$JOBLEV			= $dataI['IS_LEVEL'];
				$IS_LEVEL		= $dataI['IS_LEVEL'];
				$ISLAST			= $dataI['ISLAST'];

				$sgetChild 		= "tbl_joblist_detail WHERE JOBPARENT = '$JOBCODEID' AND ISLAST = 0";
				$rgetChild		= $this->db->count_all($sgetChild);

				$CELL_COL 		= "";
				if($rgetChild > 0)
				{
					$CELL_COL	= "style='font-weight:bold;'";
				}

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

					$chkBox	= "<input type='radio' name='chk' id='chk_'".$noU."'' value='".$JOBCODEID."|".$JOBDESC."'' onClick='getRow('".$noU."')' />";

					$strLENH 	= strlen($JOBDESC);
					$JOBDESC	= substr("$JOBDESC", 0, 100);
					$JOBDESC 	= $JOBDESC;
					if($strLENH > 50)
						$JOBDESC 	= $JOBDESC."...";

					$JobView		= "$spaceLev $JOBCODEID - $JOBDESC";

				/*$output['data'][] 	= array("<div style='text-align:center;'>".$chkBox."</div>",
											"<div>
										  		<p><span ".$CELL_COL.">".$JobView."</span></p>
										  	</div>
										  	<div style='margin-left: 15px; font-style: italic;'>
										  		<i class='text-muted fa fa-rss'>&nbsp;&nbsp;".$JOBDESCH."
										  	</div>",
											"<div style='text-align:center;'><span ".$CELL_COL.">".$JOBUNITV."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".$JOBVOLMV."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".$TOT_REQV."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".$PO_VOLMV."</span></div>",
											"<div style='text-align:right;'>".$statRem."</div>");*/

				$output['data'][] 	= array($chkBox,
											"<div><span ".$CELL_COL.">".$JobView."</span></div>");

				$noU			= $noU + 1;
			}
			//$output['data'][] 	= array("$PRJCODE","","");
			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

  	function get_AllDataITM() // GOOD
	{
		//$PRJCODE		= $_GET['id'];

		setlocale(LC_ALL, 'id-ID', 'id_ID');

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
			
			$columns_valid 	= array("JOBCODEID", 
									"ITM_UNIT", 
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
				$ORD_ID 		= $dataI['ORD_ID'];
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
				$ITM_VOLMBG		= $dataI['ITM_VOLM'];
				$JOBPRICE		= $dataI['ITM_PRICE'];
				$ITM_PRICE		= $dataI['ITM_PRICE'];
				$ITM_LASTP		= $dataI['ITM_LASTP'];
				$ADD_VOLM 		= $dataI['ADD_VOLM'];
				$ADD_PRICE		= $dataI['ADD_PRICE'];
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
				$JOBCOST		= $dataI['ITM_BUDG'];
				$IS_LEVEL		= $dataI['IS_LEVEL'];
				$ISLAST			= $dataI['ISLAST'];
				$TOT_USEDQTY 	= $dataI['REQ_VOLM'];

				$serialNumber	= '';
				$itemConvertion	= 1;
				$TOT_VOLM 		= $ITM_VOLMBG + $ADD_VOLM;
				$TOT_AMOUNT		= ($JOBPRICE * $ITM_VOLMBG) + ($ADD_VOLM * $ADD_PRICE);

				// GET ITM_NAME
					$ITM_NAME		= '';
					$ITM_CODE_H		= '';
					$ITM_TYPE		= '';
					$sqlITMNM		= "SELECT ITM_NAME, ITM_CODE_H, ITM_TYPE
										FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' 
											AND PRJCODE = '$PRJCODE' LIMIT 1";
					$resITMNM		= $this->db->query($sqlITMNM)->result();
					foreach($resITMNM as $rowITMNM) :
						$ITM_NAME	= $rowITMNM->ITM_NAME;			// 5
						$ITM_CODE_H	= $rowITMNM->ITM_CODE_H;
						$ITM_TYPE	= $rowITMNM->ITM_TYPE;
					endforeach;

				// GET PR DETAIL
					$TOT_PRVOL		= 0;
					$TOT_PRAMN		= 0;
					$sqlITMVC		= "SELECT SUM(A.PR_VOLM) AS TOT_REQ, 
											SUM(A.PR_TOTAL) AS TOT_REQAM 
										FROM tbl_pr_detail A
											INNER JOIN tbl_pr_header B ON A.PR_NUM = B.PR_NUM
										WHERE B.PRJCODE = '$PRJCODE'
											AND A.ITM_CODE = '$ITM_CODE'
											AND A.JOBCODEID = '$JOBCODEID'
											AND B.PR_STAT = 2";
					$resITMVC		= $this->db->query($sqlITMVC)->result();
					foreach($resITMVC as $rowITMVC) :
						$TOT_PRVOL	= $rowITMVC->TOT_REQ;
						$TOT_PAMN	= $rowITMVC->TOT_REQAM;
					endforeach;
					if($TOT_PRVOL == '')
						$TOT_PRVOL	= 0;
					if($TOT_PRAMN == '')
						$TOT_PRAMN	= 0;

				// LS PROCEDURE 1
					if($JOBUNIT == 'LS')
					{
						$TOT_REQ 	= $REQ_AMOUNT + $TOT_PAMN;
						$MAX_REQ	= $TOT_AMOUNT - $TOT_REQ;		// 14
						$PO_VOLM	= $PO_AMOUNT;
						$TOT_BUDG	= $TOT_AMOUNT;
					}
					else
					{
						$TOT_REQ 	= $REQ_VOLM + $TOT_PRVOL;
						$MAX_REQ	= $TOT_VOLM - $TOT_REQ;			// 14
						$PO_VOLM	= $PO_VOLM;
						$TOT_BUDG	= $TOT_VOLM;
					}
				
					$tempTotMax		= $MAX_REQ;

					$REMREQ_QTY		= $TOT_VOLM - $TOT_REQ;
					$REMREQ_AMN		= $TOT_AMOUNT - $TOT_BUDG;
					
					$disabledB		= 0;
					if($JOBUNIT == 'LS')
					{
						if($REMREQ_AMN <= 0)
							$disabledB	= 1;
						
						$ITM_PRICE		= 1;
						$TOT_VOLM		= $TOT_AMOUNT;
					}
					else
					{
						if($REMREQ_QTY <= 0)
							$disabledB	= 1;
					}
				
					if($ITM_TYPE == 'SUBS')
					{
						$disabledB	= 0;															
					}

				// IS LAST SETT
					if($ISLAST == 0)	// HEADER
					{
						$$disabledB	= 1;
						$JOBDESC1 	= $JOBDESC;
						$STATCOL	= 'primary';
						$CELL_COL	= "style='font-weight:bold; white-space:nowrap'";
						$statRem 	= "";
						$JOBUNITV 	= "";
						$JOBVOLMV 	= "";
						$TOT_REQV 	= "";
						$PO_VOLMV	= "";
					}
					else
					{
						$strLEN 	= strlen($JOBDESC);
						$JOBDESCA	= substr("$JOBDESC", 0, 50);
						$JOBDESC1 	= $JOBDESCA;
						if($strLEN > 60)
							$JOBDESC1 	= $JOBDESCA."...";
						$STATCOL	= 'success';
						$CELL_COL	= "style='white-space:nowrap'";

						$JOBUNITV 	= $JOBUNIT;
						$JOBVOLMV	= number_format($JOBVOLM, 2);
						$TOT_REQV	= number_format($TOT_REQ, 2);
						$PO_VOLMV	= number_format($PO_VOLM, 2);
						if($disabledB == 0)
							$statRem = number_format($REMREQ_QTY, 2);
						else
							$statRem = "<span class='label label-danger' style='font-size:12px'>".number_format($REMREQ_QTY, 2)."</span>";
					}

				// OTHER SETT
					$chkBox		= "<input type='checkbox' name='chk' value='".$JOBCODEDET."|".$JOBCODEID."|".$JOBCODE."|".$PRJCODE."|".$ITM_CODE."|".$ITM_NAME."|".$serialNumber."|".$ITM_UNIT."|".$ITM_PRICE."|".$TOT_VOLM."|".$ITM_STOCK."|".$ITM_USED."|".$itemConvertion."|".$TOT_AMOUNT."|".$tempTotMax."|".$PO_AMOUNT."|".$TOT_AMOUNT."|".$TOT_USEDQTY."|".$ITM_GROUP."' onClick='pickThis(this);'/>";

					$JOBDESCH		= "";
					//$sqlJOBDESC	= "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JOBPARENT' LIMIT 1";
					$sqlJOBDESC		= "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JPARENT' LIMIT 1";
					$resJOBDESC		= $this->db->query($sqlJOBDESC)->result();
					foreach($resJOBDESC as $rowJOBDESC) :
						$JOBDESCH	= $rowJOBDESC->JOBDESC;
					endforeach;
					$strLENH 	= strlen($JOBDESCH);
					$JOBDESCHA	= substr("$JOBDESCH", 0, 50);
					$JOBDESCH 	= $JOBDESCHA;
					if($strLENH > 50)
						$JOBDESCH 	= $JOBDESCHA."...";

					//$JobView		= "$spaceLev $JOBCODEID - $JOBDESC1";
					$JobView		= "$ITM_CODE - $JOBDESC1";

					$ADDVOL_VW 		= "";
					if($ADD_VOLM > 0)
					{
						$ADDVOL_VW 	= 	"<div>
											<p class='text-yellow'><i class='glyphicon glyphicon-triangle-top'></i>
									  		".number_format($ADD_VOLM, 2)."</p>
									  	</div>";
					}

				$output['data'][] 	= array("<div style='text-align:center;'>".$chkBox."</div>",
											"<div>
										  		<p><span ".$CELL_COL.">".$JobView."</span></p>
										  	</div>",
											"<div style='text-align:center;'><span ".$CELL_COL.">".$JOBUNITV."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".$JOBVOLMV.$ADDVOL_VW."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".$TOT_REQV."</span></div>",
											"<div style='text-align:right;'>".$statRem."</div>");

				$noU			= $noU + 1;
			}
			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
}