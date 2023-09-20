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

		$this->load->model('m_project/m_project_amd/m_project_amd', '', TRUE);
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
		$this->load->model('m_project/m_project_amd/m_project_amd', '', TRUE);

		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		$url			= site_url('c_project/c_am1h0db2/ix1h0db2/?id='.$this->url_encryption_helper->encode_url($appName));
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
				$mnCode				= 'MN339';
				$data["MenuCode"] 	= 'MN339';
				$data["MenuApp"] 	= 'MN340';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_project/c_am1h0db2/gall1h0db2amd/?id=";

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
		$this->load->model('m_project/m_project_amd/m_project_amd', '', TRUE);

		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

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
				$data["url_search"] = site_url('c_project/c_am1h0db2/f4n7_5rcH/?id='.$this->url_encryption_helper->encode_url($PRJCODE));

				//$num_rows 			= $this->m_project_amd->count_all_amd($PRJCODE, $key);
				//$data["cData"] 		= $num_rows;
				//$data['vData']		= $this->m_project_amd->get_all_amd($PRJCODE, $start, $end, $key)->result();
			// -------------------- END : SEARCHING METHOD --------------------
			
			$LangID 	= $this->session->userdata['LangID'];
			// GET MENU DESC
				$mnCode				= 'MN339';
				$data["MenuCode"] 	= 'MN339';
				$data["MenuApp"] 	= 'MN340';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
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
			$data["MenuCode"] 	= 'MN339';
			$data['backURL'] 	= site_url('c_project/c_am1h0db2/?id='.$this->url_encryption_helper->encode_url($PRJCODE));

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN339';
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

			$this->load->view('v_project/v_project_amd/v_amd_list', $data);
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
			$url			= site_url('c_project/c_am1h0db2/gall1h0db2amd/?id='.$this->url_encryption_helper->encode_url($collDATA));
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
				
				$CollID		= "$PRJCODE~$AMD_NUM";
				$secUpd		= site_url('c_project/c_am1h0db2/updateAMD/?id='.$this->url_encryption_helper->encode_url($CollID));
				$secPrint	= site_url('c_project/c_am1h0db2/p_R1n7/?id='.$this->url_encryption_helper->encode_url($AMD_NUM));
				$CollID		= "AMD~$AMD_NUM~$PRJCODE";
				$secDel_DOC = base_url().'index.php/__l1y/trash_DOC/?id='.$CollID;

				if($AMD_STAT == 1)
				{
					$secAction	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
								   <label style='white-space:nowrap'>
								   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   </a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' disabled='disabled'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='#' onClick='deleteDOC('".$secDel_DOC."')' title='Delete file' class='btn btn-danger btn-xs'>
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
									<a href='#' onClick='deleteDOC('".$secDel_DOC."')' title='Delete file' class='btn btn-danger btn-xs' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}

				if($JOBDESC == '')
					$JOBDESC	= $AMD_DESC;

				$output['data'][] = array("$noU.",
										  "<label style='white-space:nowrap'>".$dataI['AMD_CODE']."</label>",
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
			$this->load->model('m_project/m_project_amd/m_project_amd', '', TRUE);

			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;
			endforeach;
			$COLLID		= $_GET['id'];
			$COLLID		= $this->url_encryption_helper->decode_url($COLLID);
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
			$data['countAllItem']	= $this->m_project_amd->count_all_num_rowsAllItem($PRJCODE, $AMD_CATEG, $JOBCODE);
			$data['vwAllItem'] 		= $this->m_project_amd->viewAllItemMatBudget($PRJCODE, $AMD_CATEG, $JOBCODE)->result();

			$this->load->view('v_project/v_project_amd/v_amd_selitem', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function i180dahdd() // OK
	{
		$this->load->model('m_project/m_project_amd/m_project_amd', '', TRUE);

		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;
			
		$LangID 	= $this->session->userdata['LangID'];
		// GET MENU DESC
			$mnCode				= 'MN339';
			$data["MenuCode"] 	= 'MN339';
			$data["MenuApp"] 	= 'MN340';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE		= $_GET['id'];
			$PRJCODE		= $this->url_encryption_helper->decode_url($PRJCODE);
			$EmpID 			= $this->session->userdata('Emp_ID');

			$docPatternPosition 	= 'Especially';
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			$data['form_action']	= site_url('c_project/c_am1h0db2/addamd_process');

			// Untuk penomoran secara systemik
			$data['PRJCODE'] 		= $PRJCODE;

			$MenuCode 				= 'MN339';
			$data["MenuCode"] 		= 'MN339';
			$data['viewDocPattern'] = $this->m_project_amd->getDataDocPat($MenuCode)->result();

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN339';
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

			$this->load->view('v_project/v_project_amd/v_amd_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function addamd_process() // OK
	{
		$this->load->model('m_project/m_project_amd/m_project_amd', '', TRUE);

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
			$AMD_CATEG 		= $this->input->post('AMD_CATEG');

			$AMD_JOBPAR		= $this->input->post('JOBCODEID');	// PARENT
			$AMD_JOBID		= $this->input->post('JOBCODEID');

			$AMD_FUNC 		= '';
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
			}

			$AMD_DATE		= date('Y-m-d',strtotime(str_replace('/','-', $this->input->post('AMD_DATE'))));
			$AMD_NOTES 		= addslashes($this->input->post('AMD_NOTES'));
			$AMD_AMOUNT		= $this->input->post('AMD_AMOUNT');
			$AMD_STAT		= $this->input->post('AMD_STAT');
			$AMD_CREATER	= $DefEmp_ID;
			$AMD_CREATED	= date('Y-m-d H:i:s');
			$Patt_Year		= date('Y',strtotime(str_replace('/','-', $this->input->post('AMD_DATE'))));
			$Patt_Number	= $this->input->post('Patt_Number');

			// START - PEMBENTUKAN GENERATE CODE
				$this->load->model('m_projectlist/m_projectlist', '', TRUE);
				$Pattern_Code	= "XX";
				$MenuCode 		= 'MN339';
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
				$MenuCode 		= 'MN339';
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

			$url			= site_url('c_project/c_am1h0db2/gall1h0db2amd/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function updateAMD() // OK
	{
		$this->load->model('m_project/m_project_amd/m_project_amd', '', TRUE);

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

			$EmpID 		= $this->session->userdata('Emp_ID');

			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_project/c_am1h0db2/updateamd_process');
			
			$LangID 	= $this->session->userdata['LangID'];
			// GET MENU DESC
				$mnCode				= 'MN339';
				$data["MenuCode"] 	= 'MN339';
				$data["MenuApp"] 	= 'MN340';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$MenuCode 			= 'MN339';
			$data["MenuCode"] 	= 'MN339';
			$data["PRJCODE"] 	= $PRJCODE;
			
			$getAMD				= $this->m_project_amd->get_amd_by_number($AMD_NUM)->row();								
			$data['default']['AMD_NUM'] 	= $getAMD->AMD_NUM;
			$data['default']['AMD_CODE'] 	= $getAMD->AMD_CODE;
			$data['default']['PRJCODE'] 	= $getAMD->PRJCODE;
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
				$MenuCode 		= 'MN339';
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

			$this->load->view('v_project/v_project_amd/v_amd_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function updateamd_process() // OK
	{
		$this->load->model('m_project/m_project_amd/m_project_amd', '', TRUE);

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
			$AMD_CATEG 		= $this->input->post('AMD_CATEG');

			$AMD_JOBPAR		= $this->input->post('JOBCODEID');	// PARENT
			$AMD_JOBID		= $this->input->post('JOBCODEID');

			$AMD_FUNC 		= '';
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
			}

			$AMD_DATE		= date('Y-m-d',strtotime(str_replace('/','-', $this->input->post('AMD_DATE'))));
			$AMD_NOTES 		= addslashes($this->input->post('AMD_NOTES'));
			$AMD_AMOUNT		= $this->input->post('AMD_AMOUNT');
			$AMD_STAT		= $this->input->post('AMD_STAT');
			$AMD_CREATER	= $DefEmp_ID;
			$AMD_CREATED	= date('Y-m-d H:i:s');
			$Patt_Year		= date('Y',strtotime(str_replace('/','-', $this->input->post('AMD_DATE'))));
			$Patt_Number	= $this->input->post('Patt_Number');

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
				$MenuCode 		= 'MN339';
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

			$url			= site_url('c_project/c_am1h0db2/gall1h0db2amd/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}

 	function i180dah() // OK
	{
		$this->load->model('m_project/m_project_amd/m_project_amd', '', TRUE);

		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		$url			= site_url('c_project/c_am1h0db2/pR7_l157_14x/?id='.$this->url_encryption_helper->encode_url($appName));
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
				$mnCode				= 'MN340';
				$data["MenuCode"] 	= 'MN340';
				$data["MenuApp"] 	= 'MN340';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_project/c_am1h0db2/i1dah80Idx/?id=";

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
		$this->load->model('m_project/m_project_amd/m_project_amd', '', TRUE);

		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

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
				$data['backURL'] 	= site_url('c_project/c_am1h0db2/pR7_l157_14x/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
				$data["url_search"] = site_url('c_project/c_am1h0db2/f4n7_5rcH1nB/?id='.$this->url_encryption_helper->encode_url($PRJCODE));

				//$num_rows 			= $this->m_project_amd->count_all_AMDInb($PRJCODE, $key, $DefEmp_ID);
				//$data["cData"] 		= $num_rows;
				//$data['vData']		= $this->m_project_amd->get_all_AMDInb($PRJCODE, $start, $end, $key, $DefEmp_ID)->result();
			// -------------------- END : SEARCHING METHOD --------------------
			
			$LangID 	= $this->session->userdata['LangID'];
			// GET MENU DESC
				$mnCode				= 'MN340';
				$data["MenuCode"] 	= 'MN340';
				$data["MenuApp"] 	= 'MN340';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$data['title'] 		= $appName;	
			$data["PRJCODE"] 	= $PRJCODE;
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h2_title"] 	= "Persetujuan";
				$data['h3_title']	= "Amandemen Anggaran";
			}
			else
			{
				$data["h2_title"] 	= "Approval";
				$data['h3_title']	= "Budget Amendment";
			}

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
				$MenuCode 		= 'MN340';
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

			$this->load->view('v_project/v_project_amd/v_inb_amd_list', $data);
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
			$url			= site_url('c_project/c_am1h0db2/i1dah80Idx/?id='.$this->url_encryption_helper->encode_url($collDATA));
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
				$secUpd		= site_url('c_project/c_am1h0db2/update_inb/?id='.$this->url_encryption_helper->encode_url($CollID));
				$secPrint	= site_url('c_project/c_am1h0db2/p_R1n7/?id='.$this->url_encryption_helper->encode_url($AMD_NUM));
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
										  $dataI['AMD_CODE'],
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
		$this->load->model('m_project/m_project_amd/m_project_amd', '', TRUE);

		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		if ($this->session->userdata('login') == TRUE)
		{
			$EmpID 		= $this->session->userdata('Emp_ID');
			$COLLDATA	= $_GET['id'];
			$COLLDATA	= $this->url_encryption_helper->decode_url($COLLDATA);
			$EXTRACTCOL	= explode("~", $COLLDATA);
			$PRJCODE	= $EXTRACTCOL[0];
			$AMD_NUM	= $EXTRACTCOL[1];
			
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_project/c_am1h0db2/updateamd_process_inb');
			
			$LangID 	= $this->session->userdata['LangID'];
			// GET MENU DESC
				$mnCode				= 'MN340';
				$data["MenuCode"] 	= 'MN340';
				$data["MenuApp"] 	= 'MN340';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
				
			$data["PRJCODE"] 	= $PRJCODE;
			
			$getAMD				= $this->m_project_amd->get_amd_by_number($AMD_NUM)->row();	
			$data['default']['AMD_NUM'] 	= $getAMD->AMD_NUM;
			$data['default']['AMD_CODE'] 	= $getAMD->AMD_CODE;
			$data['default']['PRJCODE'] 	= $getAMD->PRJCODE;
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
				$MenuCode 		= 'MN339';
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

			$this->load->view('v_project/v_project_amd/v_inb_amd_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function updateamd_process_inb() // OK
	{
		$this->load->model('m_project/m_project_amd/m_project_amd', '', TRUE);

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
			}

			$AMD_DATE		= date('Y-m-d',strtotime($this->input->post('AMD_DATE')));

			$AMD_DESC 		= $this->input->post('AMD_DESC');
			$AMD_UNIT 		= $this->input->post('AMD_UNIT');
			$AMD_NOTES 		= addslashes($this->input->post('AMD_NOTES'));
			$AMD_MEMO 		= addslashes($this->input->post('AMD_MEMO'));
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

			$updateAMD 		= array('AMD_STAT'	=> 7);
			$this->m_project_amd->updateAMD($AMD_NUM, $updateAMD);

			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "AMD_NUM",
										'DOC_CODE' 		=> $AMD_NUM,
										'DOC_STAT' 		=> 7,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> "",
										'TBLNAME'		=> "tbl_amd_header");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS

			$AH_CODE		= $AMD_NUM;
			$AH_APPLEV		= $this->input->post('APP_LEVEL');
			$AH_APPROVER	= $DefEmp_ID;
			$AH_APPROVED	= $AMD_APPROVED;
			$AH_NOTES		= addslashes($this->input->post('AMD_NOTES'));
			$AH_ISLAST		= $this->input->post('IS_LAST');

			$sqlCHLDC 	= "tbl_joblist WHERE JOBPARENT = '$AMD_JOBID'";
			$resCHLDC 	= $this->db->count_all($sqlCHLDC);

			$sqlCHLDDC 	= "tbl_joblist_detail WHERE JOBPARENT = '$AMD_JOBID'";
			$resCHLDDC 	= $this->db->count_all($sqlCHLDDC);

			$sqlLSPatt	= "SELECT MAX(Patt_Number) AS LastPatt FROM tbl_joblist";
			$resLSPatt 	= $this->db->query($sqlLSPatt)->result();
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

				//$this->m_purchase_req->updateJobDet($PR_NUM, $PRJCODE); // UPDATE JOBD ETAIL DAN PR
			}

			if($AH_ISLAST == 1)
			{
				$this->load->model('m_updash/m_updash', '', TRUE);

				$AH_CODE		= $AMD_NUM;
				$AH_APPLEV		= $this->input->post('APP_LEVEL');
				$AH_APPROVER	= $DefEmp_ID;
				$AH_APPROVED	= $AMD_APPROVED;
				$AH_NOTES		= addslashes($this->input->post('AMD_NOTES'));
				$AH_ISLAST		= $this->input->post('IS_LAST');

				$updAMD 		= array('AMD_MEMO'	=> addslashes($this->input->post('AMD_MEMO')),
										'AMD_STAT'	=> $this->input->post('AMD_STAT'));
				$this->m_project_amd->updateAMD($AMD_NUM, $updAMD);

				// UPDATE JOBDETAIL ITEM
				if($AMD_STAT == 3)
				{
					$updateAMD 	= array('AMD_MEMO'		=> $AMD_MEMO,
										'AMD_STAT'		=> $AMD_STAT);
					$this->m_project_amd->updateAMD($AMD_NUM, $updateAMD);

					// CREATE HEADER PEKERJAAN
					$rowDet			= 0;
					$Pattern_Length	= 2;
					$theLast		= $resCHLDC;
					$theLastD		= $resCHLDDC;

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
													'JOBCODEID'		=> $JOBCODEID,
													'ITM_CODE'		=> $ITM_CODE,
													'AMD_CLASS'		=> $AMD_CLASS,
													'PRJCODE'		=> $PRJCODE);
								$this->m_project_amd->updateWBSM($paramWBS);

								//$this->m_project_amd->updateWBSDM($paramWBS);
							}

							// UPDATE SI
							$paramSIH 	= array('SI_AMANDNO'	=> $AMD_NUM,
												'SI_AMANDVAL'	=> $JOBCOST,
												'SI_AMANDSTAT'	=> $AMD_STAT,
												'SI_CODE'		=> $AMD_REFNO,
												'PRJCODE'		=> $PRJCODE);
							$this->m_project_amd->updateSIH($paramSIH);
						}
						elseif($AMD_CATEG == 'SINJ') // Site Instruction. Adding new job. Add new item
						{
							echo "test<br>";
							$rowDet		= $rowDet + 1;
							$LastPatt	= $LastPatt + 1;	// Last Row Joblist
							$LastPattD	= $LastPattD + 1;	// Last Row Joblist Detail

							$theLast	= $theLast + 1;
							$theLastD	= $theLastD + 1;

							$nolH	= "";
							$lenH = strlen($theLast);
							if($Pattern_Length==2)
							{
								if($lenH==1) $nolH="0";
								else $nolH="";
							}
							$lastNumbH 	= $nolH.$theLast;
							$JOBCODEID2= "$AMD_JOBID.$lastNumbH";

							$nolD="";
							$lenD = strlen($theLastD);
							if($Pattern_Length==2)
							{
								if($lenD==1) $nolD="0";
								else $nolD="";
							}
							$lastNumbD = $nolD.$theLastD;
							$JOBCODEIDD2= "D$JOBCODEID2";

							// JOBLIST
							$e['JOBPARENT']		= $AMD_JOBID;
							$e['JOBCODEID']		= $JOBCODEID2;
							$e['JOBCODEIDV']	= $JOBCODEID2;
							$e['JOBCOD2']		= $AMD_NUM;
							$e['PRJCODE']		= $PRJCODE;
							$e['ITM_CODE']		= $d['ITM_CODE'];
							$e['JOBDESC']		= $d['JOBDESC'];
							$e['JOBGRP']		= $d['ITM_GROUP'];
							$e['JOBTYPE']		= "SINJ";
							$e['JOBUNIT']		= $d['ITM_UNIT'];
							$e['JOBLEV']		= $JOBLEVNEW;
							$e['JOBVOLM']		= $d['AMD_VOLM'];
							$e['PRICE']			= $d['AMD_PRICE'];
							$JOBVOLM			= $d['AMD_VOLM'];
							$PRICE				= $d['AMD_PRICE'];
							$ADD_VOLM			= $d['AMD_VOLM'];
							$JOBCOST			= $JOBVOLM * $PRICE;
							$e['JOBCOST']		= $JOBCOST;
							$e['ISHEADER']		= 0;
							$e['ISLAST']		= 1;
							$e['Patt_Number']	= $LastPatt;

							// JOBLIST_DETAIL
							$dt['JOBCODEDET']	= $JOBCODEIDD2;
							$dt['JOBCODEID']	= $JOBCODEID2;
							$dt['JOBPARENT']	= $AMD_JOBID;
							$dt['JOBCODE']		= $JOBCODEID2;
							$dt['JOBCOD2']		= $AMD_NUM;
							$dt['PRJCODE']		= $PRJCODE;
							$dt['JOBDESC']		= $d['JOBDESC'];
							$dt['ITM_GROUP']	= $d['ITM_GROUP'];
							$dt['GROUP_CATEG']	= $d['ITM_GROUP'];
							$dt['ITM_CODE']		= $d['ITM_CODE'];
							$dt['ITM_UNIT']		= $d['ITM_UNIT'];
							$dt['ITM_VOLM']		= $d['AMD_VOLM'];
							$dt['ITM_PRICE']	= $d['AMD_PRICE'];
							$dt['ITM_LASTP']	= $d['AMD_PRICE'];
							$dt['ITM_BUDG']		= $JOBCOST;
							$dt['IS_LEVEL']		= $JOBLEVNEW;
							$dt['ISLAST']		= 1;
							$dt['Patt_Number']	= $LastPattD;

							// BOQLIST
							$bq['JOBCODEID']	= $JOBCODEID2;
							$bq['JOBCODEIDV']	= $JOBCODEID2;
							$bq['JOBCOD2']		= $JOBCODEID2;
							$bq['JOBPARENT']	= $AMD_JOBID;
							$bq['JOBCOD2']		= $AMD_NUM;
							$bq['PRJCODE']		= $PRJCODE;
							$bq['ITM_CODE']		= $d['ITM_CODE'];
							$bq['JOBDESC']		= $d['JOBDESC'];
							$bq['JOBGRP']		= $d['ITM_GROUP'];
							$bq['JOBTYPE']		= "SINJ";
							$bq['JOBUNIT']		= $d['ITM_UNIT'];
							$bq['JOBLEV']		= $JOBLEVNEW;
							$bq['JOBVOLM']		= $d['AMD_VOLM'];
							$bq['PRICE']		= $d['AMD_PRICE'];
							$JOBVOLM			= $d['AMD_VOLM'];
							$PRICE				= $d['AMD_PRICE'];
							$JOBCOST			= $JOBVOLM * $PRICE;
							$bq['JOBCOST']		= $JOBCOST;
							$bq['ISHEADER']		= 0;
							$bq['ISLAST']		= 1;
							$bq['Patt_Number']	= $LastPatt;

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
							$ADDMVOLM2		= $ADDMVOLM + $ADD_VOLM;

							$sql1	= "UPDATE tbl_item SET ITM_VOLMBGR = $ITM_VOLMBGR, ADDMVOLM = $ADDMVOLM2
										WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
							$this->db->query($sql1);

							$sql6	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST + $JOBCOST
										WHERE JOBCODEID = '$JOBPARENT1' AND PRJCODE = '$PRJCODE'";
							$this->db->query($sql6);

							$sql7	= "UPDATE tbl_boqlist SET JOBCOST = JOBCOST + $JOBCOST
										WHERE JOBCODEID = '$JOBPARENT1' AND PRJCODE = '$PRJCODE'";
							$this->db->query($sql7);
						}
						elseif($AMD_CATEG == 'NB') // Not Budgeting
						{
							$rowDet		= $rowDet + $resCHLDDC + 1;
							$theLast	= $LastPatt + 1;
							$theLastD	= $LastPattD + 1;
							$JOBPARENT	= $AMD_JOBID;		// TO BE A PARENT

							$nol	= "";
							$len = strlen($rowDet);
							if($Pattern_Length==2)
							{
								if($len==1) $nol="0";
							}
							else
							{
								$nol	= "";
							}
							$lastNumb = $nol.$rowDet;

							$e['JOBPARENT']		= $JOBPARENT;
							$JOBCODEID			= "$JOBPARENT.$lastNumb";
							$e['JOBCODEID']		= $JOBCODEID;
							$e['JOBCODEIDV']	= $JOBCODEID;
							$e['JOBCOD2']		= $AMD_NUM;
							$e['PRJCODE']		= $PRJCODE;
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

							$dt['JOBCODEDET']	= "D.$JOBCODEID";
							$dt['JOBCODEID']	= $JOBCODEID;
							$dt['JOBPARENT']	= $JOBPARENT;
							$dt['JOBCOD2']		= $AMD_NUM;
							$dt['PRJCODE']		= $PRJCODE;
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

							$bq['JOBCODEID']	= $JOBCODEID;
							$bq['JOBCODEIDV']	= $JOBCODEID;
							$bq['JOBPARENT']	= $JOBPARENT;
							$bq['JOBCOD2']		= $AMD_NUM;
							$bq['PRJCODE']		= $PRJCODE;
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
						elseif($AMD_CATEG == 'OTH') // Other
						{
							$rowDet		= $rowDet + $resCHLDDC + 1;
							$theLast	= $LastPatt + 1;
							$theLastD	= $LastPattD + 1;
							$JOBPARENT	= $AMD_JOBID;		// TO BE A PARENT

							$nol	= "";
							$len = strlen($rowDet);
							if($Pattern_Length==2)
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

							$dt['JOBCODEDET']	= "D.$JOBCODEID";
							$dt['JOBCODEID']	= $JOBCODEID;
							$dt['JOBPARENT']	= $JOBPARENT;
							$dt['JOBCOD2']		= $AMD_NUM;
							$dt['PRJCODE']		= $PRJCODE;
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
				}

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
			//return false;
			/*$sqlSUMQTY 	= "UPDATE tbl_item A SET A.ITM_VOLMBG = (SELECT sum(B.ITM_VOLM) FROM tbl_joblist_detail B
								WHERE B.ITM_CODE = A.ITM_CODE AND B.PRJCODE = '$PRJCODE')
							WHERE A.PRJCODE = '$PRJCODE'";
			$this->db->query($sqlSUMQTY);*/

			if($AMD_STAT == 4 || $AMD_STAT == 5)
			{
				$updAMD 		= array('AMD_MEMO'	=> $this->input->post('AMD_MEMO'),
										'AMD_STAT'	=> $this->input->post('AMD_STAT'));
				$this->m_project_amd->updateAMD($AMD_NUM, $updAMD);

				if($AMD_STAT == 4)
				{
					// CLEAR APROVE HISTORY
					$this->load->model('m_updash/m_updash', '', TRUE);
					$this->m_updash->delAppHist($AH_CODE);
				}

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
				$MenuCode 		= 'MN340';
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

			$url			= site_url('c_project/c_am1h0db2/i1dah80Idx/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
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

			$this->load->view('v_project/v_project_amd/v_amd_sel_si', $data);
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
			$data['default']['JOBCODEID']	= $getAMD->JOBCODEID;
			$data['default']['AMD_DATE'] 	= $getAMD->AMD_DATE;
			$data['default']['AMD_DESC'] 	= $getAMD->AMD_DESC;
			$data['default']['AMD_UNIT'] 	= $getAMD->AMD_UNIT;
			$data['default']['AMD_NOTES'] 	= $getAMD->AMD_NOTES;
			$data['default']['AMD_MEMO'] 	= $getAMD->AMD_MEMO;
			$data['default']['AMD_AMOUNT'] 	= $getAMD->AMD_AMOUNT;
			$data['default']['AMD_STAT'] 	= $getAMD->AMD_STAT;
			$data['default']['Patt_Number'] = $getAMD->Patt_Number;

			$this->load->view('v_project/v_project_amd/v_amd_print', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

  function chckAMD_CODE()
  {
    $this->load->model('m_project/m_project_amd/m_project_amd', '', TRUE);
    $data = $this->m_project_amd->chckManual_Code()->num_rows();
    echo json_encode($data);
  }

  function ManualCode_AMD()
  {
    $MenuCode  = 'MN339';
    $vwDocPatt = $this->m_project_amd->getDataDocPat($MenuCode)->result();
    foreach($vwDocPatt as $row) :
  		$Pattern_Code = $row->Pattern_Code;
  		$Pattern_Position = $row->Pattern_Position;
  		$Pattern_YearAktive = $row->Pattern_YearAktive;
  		$Pattern_MonthAktive = $row->Pattern_MonthAktive;
  		$Pattern_DateAktive = $row->Pattern_DateAktive;
  		$Pattern_Length = $row->Pattern_Length;
  		$useYear = $row->useYear;
  		$useMonth = $row->useMonth;
  		$useDate = $row->useDate;
  	endforeach;
  	if(isset($Pattern_Position))
  	{
  		$isSetDocNo = 1;
  		if($Pattern_Position == 'Especially')
  		{
  			$Pattern_YearAktive 	= (int)$Pattern_YearAktive;
  			$Pattern_MonthAktive 	= date('m');
  			$Pattern_DateAktive 	= date('d');
  		}
  		$year 						= (int)$Pattern_YearAktive;
  		$month 						= (int)$Pattern_MonthAktive;
  		$date 						= (int)$Pattern_DateAktive;
  	}
  	else
  	{
  		$isSetDocNo = 0;
  		$Pattern_Code 			= "XXX";
  		$Pattern_Length 		= "5";
  		$useYear 				= 1;
  		$useMonth 				= 1;
  		$useDate 				= 1;

  		$Pattern_YearAktive 	= (int)$Pattern_YearAktive;
  		$Pattern_MonthAktive 	= date('m');
  		$Pattern_DateAktive 	= date('d');
  		$year 					= (int)$Pattern_YearAktive;
  		$month 					= (int)$Pattern_MonthAktive;
  		$date 					= (int)$Pattern_DateAktive;
  	}

    $yearC = (int)$Pattern_YearAktive;
  	$year = substr($Pattern_YearAktive,2,2);
  	$month = (int)$Pattern_MonthAktive;
  	$date = (int)$Pattern_DateAktive;

    $AMD_DATE = date('Y-m-d', strtotime(str_replace('/','-', $this->input->post('AMD_DATE'))));
    $PRJCODE       = $this->input->post('PRJCODE');
    $data = $this->db->get_where('tbl_amd_header',
             array('YEAR(AMD_DATE)' => $AMD_DATE,
                   'PRJCODE' => $PRJCODE))->num_rows();

    $myMax = $data+1;

    $thisMonth = $month;

  	$lenMonth = strlen($thisMonth);
  	if($lenMonth==1) $nolMonth="0";elseif($lenMonth==2) $nolMonth="";
  	$pattMonth = $nolMonth.$thisMonth;

  	$thisDate = $date;
  	$lenDate = strlen($thisDate);
  	if($lenDate==1) $nolDate="0";elseif($lenDate==2) $nolDate="";
  	$pattDate = $nolDate.$thisDate;

  	// group year, month and date
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

  	if($Pattern_Length==2)
  	{
  		if($len==1) $nol="0";
  	}
  	elseif($Pattern_Length==3)
  	{if($len==1) $nol="00";else if($len==2) $nol="0";
  	}
  	elseif($Pattern_Length==4)
  	{if($len==1) $nol="000";else if($len==2) $nol="00";else if($len==3) $nol="0";
  	}
  	elseif($Pattern_Length==5)
  	{if($len==1) $nol="0000";else if($len==2) $nol="000";else if($len==3) $nol="00";else if($len==4) $nol="0";
  	}
  	elseif($Pattern_Length==6)
  	{if($len==1) $nol="00000";else if($len==2) $nol="0000";else if($len==3) $nol="000";else if($len==4) $nol="00";else if($len==5) $nol="0";
  	}
  	elseif($Pattern_Length==7)
  	{if($len==1) $nol="000000";else if($len==2) $nol="00000";else if($len==3) $nol="0000";else if($len==4) $nol="000";else if($len==5) $nol="00";else if($len==6) $nol="0";
  	}
  	$lastPatternNumb = $nol.$lastPatternNumb;
    $Manual_No		= "$Pattern_Code-$PRJCODE-$lastPatternNumb";
    echo $Manual_No;
  }
}