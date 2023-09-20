<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 02 Januari 2018
	* File Name	= C_s180d0bpk.php
	* Location		= -
*/

class C_s180d0bpk extends CI_Controller
{
  	function __construct() // GOOD
	{
		parent::__construct();

		setlocale(LC_ALL, 'id-ID', 'id_ID');

		$this->load->model('m_project/m_spk/m_spk', '', TRUE);
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
	
 	public function index() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		$url			= site_url('c_project/c_s180d0bpk/prj180d0blst/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}

	function prj180d0blst() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_project/m_spk/m_spk', '', TRUE);

		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);

			$LangID 	= $this->session->userdata['LangID'];
			// GET MENU DESC
				$mnCode				= 'MN238';
				$MenuCode			= 'MN238';
				$data["MenuCode"] 	= 'MN238';
				$data["MenuApp"] 	= 'MN239';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;

			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN238';
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data["secURL"] 	= "c_project/c_s180d0bpk/gallS180d0bpk/?id=";

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

	function gallS180d0bpk() // OK
	{
		$this->load->model('m_project/m_spk/m_spk', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		$LangID 	= $this->session->userdata['LangID'];
		// GET MENU DESC
			$mnCode				= 'MN238';
			$MenuCode			= 'MN238';
			$data["MenuCode"] 	= 'MN238';
			$data["MenuApp"] 	= 'MN239';
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
				$data["url_search"] = site_url('c_project/c_s180d0bpk/f4n7_5rcH5pK/?id='.$this->url_encryption_helper->encode_url($PRJCODE));

				//$num_rows 			= $this->m_spk->count_all_WO($PRJCODE, $key);
				//$data["cData"] 		= $num_rows;
				//$data['vData']		= $this->m_spk->get_all_WO($PRJCODE, $start, $end, $key)->result();
			// -------------------- END : SEARCHING METHOD --------------------

			$data['title'] 		= $appName;
			$data['addURL'] 	= site_url('c_project/c_s180d0bpk/s180d0bpk_144n3/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['backURL'] 	= site_url('c_project/c_s180d0bpk/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
			$MenuCode 			= 'MN238';
			$data["MenuCode"] 	= 'MN238';

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN238';
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

			$this->load->view('v_project/v_spk/spk_list', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	// -------------------- START : SEARCHING METHOD --------------------
		function f4n7_5rcH5pK()
		{
			$gEn5rcH		= $_POST['gEn5rcH'];
			$mxLS			= $_POST['maxLimDf'];
			$mxLF			= $_POST['maxLimit'];
			$PRJCODE		= $_GET['id'];
			$PRJCODE		= $this->url_encryption_helper->decode_url($PRJCODE);
			$collDATA		= "$gEn5rcH~$PRJCODE~$mxLS~$mxLF";
			$url			= site_url('c_project/c_s180d0bpk/gallS180d0bpk/?id='.$this->url_encryption_helper->encode_url($collDATA));
			redirect($url);
		}
	// -------------------- END : SEARCHING METHOD --------------------

  	function get_AllData() // GOOD
	{
		$PRJCODE		= $_GET['id'];

		$LangID     = $this->session->userdata['LangID'];
        
        $sqlTransl      = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
        $resTransl      = $this->db->query($sqlTransl)->result();
        foreach($resTransl as $rowTransl) :
            $TranslCode = $rowTransl->MLANG_CODE;
            $LangTransl = $rowTransl->LangTransl;

    		if($TranslCode == 'Date')$Date = $LangTransl;
    		if($TranslCode == 'Category')$Category = $LangTransl;
    		if($TranslCode == 'Description')$Description = $LangTransl;
    		if($TranslCode == 'SupplierName')$SupplierName = $LangTransl;
    		if($TranslCode == 'NegotNo')$NegotNo = $LangTransl;
        endforeach;

		// START : FOR SERVER-SIDE
			$order1 	= $this->input->get("order");
			$order 	= $this->input->get("order");

			$col 	= 0;
			$dir 	= "desc";
			if(!empty($order)) {
				foreach($order as $o) {
					$col 	= $o['column'];
					$dir	= $o['dir'];
				}
			}

			if($dir != "asc" && $dir != "desc")
			{
            	$dir = "desc";
        	}

			$columns_valid 	= array("WO_CODE",
									"WO_CATEG", 
									"WO_DATE", 
									"WO_NOTE", 
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
			$num_rows 		= $this->m_spk->get_AllDataC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_spk->get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir);

			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI)
			{
				$WO_NUM			= $dataI['WO_NUM'];
				$WO_CODE		= $dataI['WO_CODE'];
				$WO_DATE		= $dataI['WO_DATE'];
				$WO_ENDD		= $dataI['WO_ENDD'];
				//$WO_DATEV		= date('d M Y', strtotime($WO_DATE));
				$WO_DATEV1		= strftime('%d %b %Y', strtotime($WO_DATE));
				$WO_ENDDV		= strftime('%d %b %Y', strtotime($WO_ENDD));

				//$WO_DATEV 	= $WO_DATEV1." - ".$WO_ENDDV;
				$WO_DATEV 		= $WO_DATEV1;

				$WO_CATEG		= $dataI['WO_CATEG'];
				$PRJCODE		= $dataI['PRJCODE'];
				$WO_NOTE		= $dataI['WO_NOTE'];
				$WO_NOTED		= '';
				if($WO_NOTE != '')
					$WO_NOTED	= "$WO_NOTE";
				$WO_STAT		= $dataI['WO_STAT'];
				$WO_REFNO		= $dataI['WO_REFNO'];
				$WO_CREATER		= $dataI['WO_CREATER'];
				$WO_ISCLOSE		= $dataI['WO_ISCLOSE'];
				$JOBCODEID		= $dataI['JOBCODEID'];
				$WO_CDOC		= $dataI['WO_CDOC'];
				$JOBDESC		= $dataI['JOBDESC'];
				$SPLCODE		= $dataI['SPLCODE'];
				
				$SPLDESC		= '';
				$sqlSPLD		= "SELECT SPLDESC FROM tbl_supplier WHERE SPLCODE = '$SPLCODE' LIMIT 1";
				$resSPLD		= $this->db->query($sqlSPLD)->result();
				foreach($resSPLD as $rowSPLD) :
					$SPLDESC	= $rowSPLD->SPLDESC;		
				endforeach;
				
				if($JOBDESC == '') $JOBDESC = "-";
				$JOBDESCD		= "$JOBDESC$WO_NOTED";
				if($JOBDESCD == '')
				{
					$expJOB		= explode("~", $JOBCODEID);
					$JOBCODEID1	= $expJOB[0];
					$JOBDESC1	= '';
					$sqlJOB		= "SELECT JOBDESC FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBCODEID1'";
					$resJOB		= $this->db->query($sqlJOB)->result();
					foreach($resJOB as $rowJOB) :
						$JOBDESC1 = $rowJOB->JOBDESC;		
					endforeach;
					$JOBDESCD	= $JOBDESC1;				
				}
								
				if($WO_CATEG == 'MDR')
				{
					$WO_CATEGD	= 'Mandor';
				}
				elseif($WO_CATEG == 'SUB')
				{
					$WO_CATEGD 	= 'Subkon';
				}
				else
				{
					$WO_CATEGD 	= 'Alat';
				}
				$STATDESC	= $dataI['STATDESC'];
				$STATCOL	= $dataI['STATCOL'];
				$CREATERNM	= $dataI['CREATERNM'];
				$empName	= cut_text2 ("$CREATERNM", 15);
				$WO_REFNO	= $dataI['WO_REFNO'];

				$CollID		= "$PRJCODE~$WO_NUM";
				$secUpd		= site_url('c_project/c_s180d0bpk/up180d0bdt/?id='.$this->url_encryption_helper->encode_url($CollID));
				$secCreate	= site_url('c_project/c_s180d0bpk/cr3473d0c_m4d/?id='.$this->url_encryption_helper->encode_url($WO_NUM));
				// if($WO_CATEG == 'MDR')
				// 	$secPrint	= site_url('c_project/c_s180d0bpk/pr1n7d0c_m4d/?id='.$this->url_encryption_helper->encode_url($WO_NUM));
				// else
					$secPrint	= site_url('c_project/c_s180d0bpk/pr1n7d0c_e/?id='.$this->url_encryption_helper->encode_url($WO_NUM));

				$CollID		= "$WO_NUM~$PRJCODE";
				$secDel_WO 	= base_url().'index.php/c_project/c_s180d0bpk/trash_WO/?id='.$WO_NUM;
				$secDelIcut = base_url().'index.php/__l1y/trashDOC/?id=';
				$delID 		= "$secDelIcut~tbl_wo_header~tbl_wo_detail~WO_NUM~$WO_NUM~PRJCODE~$PRJCODE";

				if($WO_STAT == 1)
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
					$secAction	= 	"<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")' style='display: none'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='#' onClick='deleteDOC('".$secDel_WO."')' title='Delete file' class='btn btn-danger btn-xs' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}

				$output['data'][] 	= array("<div style='white-space:nowrap'>
												<strong>$WO_CODE</strong><br>
												<strong><i class='fa fa-calendar margin-r-5'></i> ".$Date." </strong>
										  		<div>
											  		<p class='text-muted' style='margin-left: 15px'>
											  			".$WO_DATEV."
											  		</p>
											  	</div>
											</div>",
										  	"<div style='white-space:nowrap'>
											  	<strong><i class='fa fa-user margin-r-5'></i> ".$SPLDESC." </strong>
										  		<div>
											  		<p class='text-muted' style='margin-left: 15px'>
											  			".$SPLCODE."
											  		</p>
											  	</div>
											  	<strong><i class='fa fa-cog margin-r-5'></i> ".$Category." </strong>
										  		<div>
											  		<p class='text-muted' style='margin-left: 18px'>
											  			".$WO_CATEGD."
											  		</p>
											  	</div>
										  	</div>",
										  	"<strong><i class='fa fa-pencil margin-r-5'></i> ".$Description." </strong>
									  		<div class='text-muted' style='margin-left: 18px'>
										  		".$JOBDESCD."
										  	</div><br>
										  	<strong><i class='fa fa-paperclip margin-r-5'></i> No. SK </strong>
									  		<div class='text-muted' style='margin-left: 18px'>
										  		".$WO_REFNO."
										  	</div>",
											$empName,
											"<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
											$secAction);
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

	function s180d0bpk_144n3() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_project/m_spk/m_spk', '', TRUE);

		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		$LangID 	= $this->session->userdata['LangID'];
		// GET MENU DESC
			$mnCode				= 'MN238';
			$MenuCode			= 'MN238';
			$data["MenuCode"] 	= 'MN238';
			$data["MenuApp"] 	= 'MN239';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
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
			$data['form_action']= site_url('c_project/c_s180d0bpk/add_process');
			$data['backURL'] 	= site_url('c_project/c_s180d0bpk/get_all_PR/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;

			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();

			$MenuCode 			= 'MN238';
			$data["MenuCode"] 	= 'MN238';
			$data["MenuCode1"] 	= 'MN342';
			$data['vwDocPatt'] 	= $this->m_spk->getDataDocPat($MenuCode)->result();

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN238';
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

			$this->load->view('v_project/v_spk/spk_form', $data);
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
		$myCount = $this->db->count_all('tbl_wo_header');

		$sql	 = "SELECT MAX(Patt_Number) as maxNumber FROM tbl_wo_header
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

	function popupallitem() // OK
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_project/m_spk/m_spk', '', TRUE);
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;
			endforeach;
			$COLLID		= $_GET['id'];
			$PRJCODE	= $_GET['pr1h0ec0JcoDe'];
			$PGFROM		= $_GET['pgfrm'];
			//$COLLID	= $this->url_encryption_helper->decode_url($COLLID);
			$JIDExplode = explode('~', $COLLID);
			$JOBCODE	= '';
			foreach($JIDExplode as $i => $key)
			{
				if($i == 0)
				{
					$JOBCODE1	= $key;
					$JOBCODE	= "'$key'";
				}
				elseif($i > 0)
				{
					$JOBCODE	= "$JOBCODE,'$key'";
				}
			}

			$data['title'] 			= $appName;
			$data['PRJCODE'] 		= $PRJCODE;
			$data['secShowAll']		= site_url('c_project/c_s180d0bpk/popupallitem/?id='.$this->url_encryption_helper->encode_url($PRJCODE));

			$data['countAllItem']	= $this->m_spk->count_all_ItemServ($PRJCODE, $JOBCODE, $PGFROM);
			$data['vwAllItem'] 		= $this->m_spk->viewAllItemServ($PRJCODE, $JOBCODE, $PGFROM)->result();

			$this->load->view('v_project/v_spk/spk_selitem', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function p0_fp4pUp4llItm() // OK
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_project/m_spk/m_spk', '', TRUE);
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;
			endforeach;
			$COLLID		= $_GET['id'];
			$PRJCODE	= $_GET['pr1h0ec0JcoDe'];
			$PGFROM		= $_GET['pgfrm'];
			//$COLLID	= $this->url_encryption_helper->decode_url($COLLID);
			$JIDExplode = explode('~', $COLLID);
			$JOBCODE	= '';
			foreach($JIDExplode as $i => $key)
			{
				if($i == 0)
				{
					$JOBCODE1	= $key;
					$JOBCODE	= "'$key'";
				}
				elseif($i > 0)
				{
					$JOBCODE	= "$JOBCODE,'$key'";
				}
			}

			$data['title'] 			= $appName;
			$data['PRJCODE'] 		= $PRJCODE;
			$data['secShowAll']		= site_url('c_project/c_s180d0bpk/popupallitem/?id='.$this->url_encryption_helper->encode_url($PRJCODE));

			$data['countAllItem']	= $this->m_spk->count_all_ItemFPA($PRJCODE, $JOBCODE, $PGFROM);
			$data['vwAllItem'] 		= $this->m_spk->viewAllItemFPA($PRJCODE, $JOBCODE, $PGFROM)->result();

			$this->load->view('v_project/v_spk/spk_selitem_fpa', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function s3l4llFP4MDR() // OK
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_project/m_spk/m_spk', '', TRUE);
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;
			endforeach;
			$COLLID		= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($COLLID);

			$data['title'] 		= $appName;
			$data['PRJCODE'] 	= $PRJCODE;

			$data['countFPA']	= $this->m_spk->count_all_FPAMDR($PRJCODE);
			$data['vwFPA'] 		= $this->m_spk->view_all_FPAMDR($PRJCODE)->result();

			$this->load->view('v_project/v_spk/spk_selFPAMDR', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function s3l4llFP4SUB() // OK
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_project/m_spk/m_spk', '', TRUE);
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;
			endforeach;
			$COLLID		= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($COLLID);

			$data['title'] 		= $appName;
			$data['PRJCODE'] 	= $PRJCODE;

			$data['countFPA']	= $this->m_spk->count_all_FPASUB($PRJCODE);
			$data['vwFPA'] 		= $this->m_spk->view_all_FPASUB($PRJCODE)->result();

			$this->load->view('v_project/v_spk/spk_selFPAMDR', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function s3l4llFP4() // OK
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_project/m_spk/m_spk', '', TRUE);
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;
			endforeach;
			$COLLID		= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($COLLID);

			$data['title'] 		= $appName;
			$data['PRJCODE'] 	= $PRJCODE;

			$data['countFPA']	= $this->m_spk->count_all_FPA($PRJCODE);
			$data['vwFPA'] 		= $this->m_spk->view_all_FPA($PRJCODE)->result();

			$this->load->view('v_project/v_spk/spk_selFPA', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function s3l4ll5PK() // OK
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_project/m_spk/m_spk', '', TRUE);
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;
			endforeach;
			$COLLID		= $_GET['id'];
			$DefEmp_ID	= $this->url_encryption_helper->decode_url($COLLID);

			$data['title'] 		= $appName;

			$data['countFPA']	= $this->m_spk->count_all_ASTSPK($DefEmp_ID);
			$data['vwFPA'] 		= $this->m_spk->view_all_ASTFPA($DefEmp_ID)->result();

			$this->load->view('v_project/v_spk/spk_selASTFPA', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function add_process() // OK
	{
		$this->load->model('m_project/m_spk/m_spk', '', TRUE);
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

			$WO_STAT 		= $this->input->post('WO_STAT'); // 1 = New, 2 = confirm, 3 = Close

			$WO_NUM 		= $this->input->post('WO_NUM');
			$WO_CODE 		= $this->input->post('WO_CODE');
			//setting WO Date
			$WO_DATE		= date('Y-m-d',strtotime($this->input->post('WO_DATE')));
			$WO_STARTD		= date('Y-m-d',strtotime($this->input->post('WO_DATE')));
				$Patt_Year	= date('Y',strtotime($this->input->post('WO_DATE')));
				$Patt_Month	= date('m',strtotime($this->input->post('WO_DATE')));
				$Patt_Date	= date('d',strtotime($this->input->post('WO_DATE')));
			$WO_ENDD		= date('Y-m-d',strtotime($this->input->post('WO_ENDD')));
			$PRJCODE 		= $this->input->post('PRJCODE');
			$SPLCODE 		= $this->input->post('SPLCODE');
			$WO_DEPT 		= '';
			$WO_CATEG 		= $this->input->post('WO_CATEG');
			$WO_TYPE 		= $this->input->post('WO_TYPE');
			//$WO_PAYTYPE 	= $this->input->post('WO_PAYTYPE');
			$WO_NOTE 		= addslashes($this->input->post('WO_NOTE'));
			$WO_NOTE2 		= addslashes($this->input->post('WO_NOTE2'));
			$FPA_NUM 		= $this->input->post('FPA_NUM');
			$WO_MEMO 		= $this->input->post('WO_MEMO');
			$WO_REFNO 		= $this->input->post('WO_REFNO');
			$WO_STAT 		= $this->input->post('WO_STAT');
			$WO_QUOT 		= $this->input->post('WO_QUOT');
			$WO_NEGO 		= $this->input->post('WO_NEGO');

			//$JOBCODEID	= $this->input->post('JOBCODEID');
			$PRREFNO		= $this->input->post('PR_REFNO');
			$WO_VALUE		= $this->input->post('WO_VALUE');
			$WO_DPPER		= $this->input->post('WO_DPPER');
			$WO_DPREF		= $this->input->post('WO_DPREF');
			$WO_DPREF1		= $this->input->post('WO_DPREF1');
			$WO_DPVAL		= $this->input->post('WO_DPVAL');
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
			$FPA_NUM		= $this->input->post('FPA_NUM');
			$FPA_CODE		= $this->input->post('FPA_CODE1');

			// START - PEMBENTUKAN GENERATE CODE
				$this->load->model('m_projectlist/m_projectlist', '', TRUE);
				$Pattern_Code	= "XX";
				$MenuCode 		= 'MN238';
				$vwDocPatt		= $this->m_projectlist->getDataDocPat($MenuCode)->result();
				foreach($vwDocPatt as $row) :
					$Pattern_Code = $row->Pattern_Code;
				endforeach;

				$PRJCODE 		= $this->input->post('PRJCODE');
				$TRXTIME1		= date('ymdHis');
				$WO_NUM			= "$Pattern_Code$PRJCODE-$TRXTIME1";
			// END - PEMBENTUKAN GENERATE CODE

			$projWOH 		= array('WO_NUM' 		=> $WO_NUM,
									'WO_CODE' 		=> $WO_CODE,
									'WO_DATE'		=> $WO_DATE,
									'WO_STARTD'		=> $WO_STARTD,
									'WO_ENDD'		=> $WO_ENDD,
									'PRJCODE'		=> $PRJCODE,
									'SPLCODE'		=> $SPLCODE,
									'WO_DEPT'		=> $WO_DEPT,
									'WO_CATEG'		=> $WO_CATEG,
									'WO_TYPE'		=> $WO_TYPE,
									//'WO_PAYTYPE'	=> $WO_PAYTYPE,
									'JOBCODEID'		=> $JOBCODEID,
									'WO_NOTE'		=> $WO_NOTE,
									'WO_NOTE2'		=> $WO_NOTE2,
									'WO_MEMO'		=> $WO_MEMO,
									'WO_VALUE'		=> $WO_VALUE,
									'WO_STAT'		=> $WO_STAT,
									'WO_CREATER'	=> $DefEmp_ID,
									'WO_CREATED'	=> date('Y-m-d H:i:s'),
									'WO_REFNO'		=> $WO_REFNO,
									'FPA_NUM'		=> $FPA_NUM,
									'FPA_CODE'		=> $FPA_CODE,
									'WO_QUOT'		=> $WO_QUOT,
									'WO_NEGO'		=> $WO_NEGO,
									'WO_DPPER'		=> $WO_DPPER,
									'WO_DPREF'		=> $WO_DPREF,
									'WO_DPREF1'		=> $WO_DPREF1,
									'WO_DPVAL'		=> $WO_DPVAL,
									'Patt_Year'		=> $Patt_Year,
									'Patt_Month'	=> $Patt_Month,
									'Patt_Date'		=> $Patt_Date,
									'Patt_Number'	=> $this->input->post('Patt_Number'));
			$this->m_spk->add($projWOH);

			$TOT_PPN	= 0;
      		$TOT_PPH    = 0;
      		$TOT_DISC   = 0;
			$WO_GTOTAL	= 0;
      		$WOID 		= 0;
			foreach($_POST['data'] as $d)
			{
				$WOID 			= $WOID+1;
				$d['WO_ID']		= $WOID;
				$d['WO_NUM']	= $WO_NUM;
				$d['WO_CODE']	= $WO_CODE;
				$ITMUNIT		= $d['ITM_UNIT'];
				$TAXCODE1		= $d['TAXCODE1'];
				$TAXCODE2		= $d['TAXCODE2'];

				$TAX_PPN		= $d['TAXPRICE1'];
				$TOT_PPN		= $TOT_PPN + $TAX_PPN;
				$TAX_PPH		= $d['TAXPRICE2'];
				$TOT_PPH		= $TOT_PPH + $TAX_PPH;

				/*$WO_DISCP   	= $d['WO_DISCP'];
			    $TOT_DISC   	= $TOT_DISC + $WO_DISCP;*/

				$WOTOTAL		= $d['WO_TOTAL'];
				$WO_GTOTAL		= $WO_GTOTAL + $WOTOTAL + $TOT_PPN - $TOT_PPH;

				/*$WO_DISCP   	= $d['WO_DISCP'];
			    $TOT_DISC   = $TOT_DISC + $WO_DISCP;*/
				
				$UNITTYPE		= strtoupper($ITMUNIT);
				$this->db->insert('tbl_wo_detail',$d);
			}

			// UPDATE HEADER : TOTAL PPN
				$projWOH 		= array('WO_VALUE' 	=> $WO_GTOTAL,
										'WO_VALPPN' => $TOT_PPN, 'WO_VALPPH' => $TOT_PPH);
				$this->m_spk->update($WO_NUM, $projWOH);

			// UPDATE DETAIL
				$this->m_spk->updateDet($WO_NUM, $PRJCODE, $WO_DATE);

			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);

				$STAT_BEFORE	= $this->input->post('WO_STAT');			// IF "ADD" CONDITION ALWAYS = PR_STAT
				$parameters 	= array('DOC_CODE' 		=> $WO_NUM,			// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "WO",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_wo_header",	// TABLE NAME
										'KEY_NAME'		=> "WO_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "WO_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $WO_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_WO",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_WO_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_WO_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_WO_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_WO_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_WO_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_WO_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT

			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "WO_NUM",
										'DOC_CODE' 		=> $WO_NUM,
										'DOC_STAT' 		=> $WO_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_wo_header");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $WO_NUM;
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

			// START : UPDATE QTY_COLLECTIVE
				$this->load->model('m_updash/m_updash', '', TRUE);

				$parameters 	= array('TR_TYPE'		=> "WO",
										'TR_DATE' 		=> $WO_DATE,
										'PRJCODE'		=> $PRJCODE);
				$this->m_updash->updateQtyColl($parameters);
			// END : UPDATE QTY_COLLECTIVE

			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}

			$url			= site_url('c_project/c_s180d0bpk/gallS180d0bpk/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function up180d0bdt() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_project/m_spk/m_spk', '', TRUE);
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

		$LangID 	= $this->session->userdata['LangID'];
		// GET MENU DESC
			$mnCode				= 'MN238';
			$MenuCode			= 'MN238';
			$data["MenuCode"] 	= 'MN238';
			$data["MenuApp"] 	= 'MN239';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;

		if ($this->session->userdata('login') == TRUE)
		{
			$COLLDATA	= $_GET['id'];
			$COLLDATA	= $this->url_encryption_helper->decode_url($COLLDATA);
			$EXTRACTCOL	= explode("~", $COLLDATA);
			$PRJCODE	= $EXTRACTCOL[0];
			$WO_NUM		= $EXTRACTCOL[1];
			
			$docPatternPosition = 'Especially';
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_project/c_s180d0bpk/update_process');

			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();

			$getwodata 						= $this->m_spk->get_WO_by_number($WO_NUM)->row();
			$data['default']['WO_NUM'] 		= $getwodata->WO_NUM;
			$data['default']['WO_CODE'] 	= $getwodata->WO_CODE;
			$data['default']['WO_DATE'] 	= $getwodata->WO_DATE;
			$data['default']['WO_STARTD'] 	= $getwodata->WO_STARTD;
			$data['default']['WO_ENDD'] 	= $getwodata->WO_ENDD;
			$data['default']['PRJCODE']		= $getwodata->PRJCODE;
			$data['PRJCODE']				= $getwodata->PRJCODE;
			$PRJCODE 						= $getwodata->PRJCODE;
			$data['default']['SPLCODE'] 	= $getwodata->SPLCODE;
			$data['default']['WO_DEPT'] 	= $getwodata->WO_DEPT;
			$data['default']['WO_CATEG'] 	= $getwodata->WO_CATEG;
			$WO_CATEG						= $getwodata->WO_CATEG;
			$data['default']['WO_TYPE'] 	= $getwodata->WO_TYPE;
			//$data['default']['WO_PAYTYPE'] 	= $getwodata->WO_PAYTYPE;
			$data['default']['JOBCODEID'] 	= $getwodata->JOBCODEID;
			$data['default']['WO_NOTE'] 	= $getwodata->WO_NOTE;
			$data['default']['WO_NOTE2'] 	= $getwodata->WO_NOTE2;
			$data['default']['WO_STAT'] 	= $getwodata->WO_STAT;
			$data['default']['WO_VALUE'] 	= $getwodata->WO_VALUE;
			$data['default']['WO_MEMO'] 	= $getwodata->WO_MEMO;
			$data['default']['WO_REFNO'] 	= $getwodata->WO_REFNO;
			$data['default']['PRJNAME'] 	= $getwodata->PRJNAME;
			$data['default']['FPA_NUM'] 	= $getwodata->FPA_NUM;
			$data['default']['FPA_CODE'] 	= $getwodata->FPA_CODE;
			$data['default']['WO_QUOT'] 	= $getwodata->WO_QUOT;
			$data['default']['WO_NEGO'] 	= $getwodata->WO_NEGO;
			$data['default']['WO_DPPER'] 	= $getwodata->WO_DPPER;
			$data['default']['WO_DPREF'] 	= $getwodata->WO_DPREF;
			$data['default']['WO_DPREF1'] 	= $getwodata->WO_DPREF1;
			$data['default']['WO_DPVAL'] 	= $getwodata->WO_DPVAL;
			$data['default']['Patt_Year'] 	= $getwodata->Patt_Year;
			$data['default']['Patt_Month'] 	= $getwodata->Patt_Month;
			$data['default']['Patt_Date'] 	= $getwodata->Patt_Date;
			$data['default']['Patt_Number']	= $getwodata->Patt_Number;

			$MenuCode 			= 'MN238';
			$data["MenuCode"] 	= 'MN238';
			if($WO_CATEG == 'MDR')
				$data["MenuCode1"] 	= 'MN239';
			elseif($WO_CATEG == 'SUB')
				$data["MenuCode1"] 	= 'MN342';
			else
				$data["MenuCode1"] 	=  'MN354';

			$data['vwDocPatt'] 	= $this->m_spk->getDataDocPat($MenuCode)->result();

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getwodata->WO_NUM;
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

			$this->load->view('v_project/v_spk/spk_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function update_process() // OK
	{
		$this->load->model('m_project/m_spk/m_spk', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();

			date_default_timezone_set("Asia/Jakarta");

			$WO_STAT 		= $this->input->post('WO_STAT'); // 1 = New, 2 = confirm, 3 = Close

			$WO_NUM 		= $this->input->post('WO_NUM');
			$WO_CODE 		= $this->input->post('WO_CODE');
			//setting WO Date
			$WO_DATE		= date('Y-m-d',strtotime($this->input->post('WO_DATE')));
			$WO_STARTD		= date('Y-m-d',strtotime($this->input->post('WO_DATE')));
				$Patt_Year	= date('Y',strtotime($this->input->post('WO_DATE')));
				$Patt_Month	= date('m',strtotime($this->input->post('WO_DATE')));
				$Patt_Date	= date('d',strtotime($this->input->post('WO_DATE')));
			$WO_ENDD		= date('Y-m-d',strtotime($this->input->post('WO_ENDD')));
			$PRJCODE 		= $this->input->post('PRJCODE');
			$SPLCODE 		= $this->input->post('SPLCODE');
			$WO_DEPT 		= '';
			$WO_CATEG 		= $this->input->post('WO_CATEG');
			$WO_TYPE 		= $this->input->post('WO_TYPE');
			$WO_NOTE 		= addslashes($this->input->post('WO_NOTE'));
			$WO_NOTE2 		= addslashes($this->input->post('WO_NOTE2'));
			$WO_STAT 		= $this->input->post('WO_STAT');	
			//$JOBCODEID	= $this->input->post('JOBCODEID');
			$PRREFNO		= $this->input->post('PR_REFNO');
			$WO_REFNO		= $this->input->post('WO_REFNO');
			$WO_VALUE		= $this->input->post('WO_VALUE');
			$PRREFNO		= $this->input->post('PR_REFNO');
			$WO_DPPER		= $this->input->post('WO_DPPER');
			$WO_DPREF		= $this->input->post('WO_DPREF');
			$WO_DPREF1		= $this->input->post('WO_DPREF1');
			$WO_DPVAL		= $this->input->post('WO_DPVAL');
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
			$FPA_NUM		= $this->input->post('FPA_NUM');
			$FPA_CODE		= $this->input->post('FPA_CODE1');
			$WO_QUOT 		= $this->input->post('WO_QUOT');
			$WO_NEGO 		= $this->input->post('WO_NEGO');
			$WO_MEMO 		= $this->input->post('WO_MEMO');

			$WO_GTOTAL	= 0;
			$TOT_PPN	= 0;
      		$TOT_PPH    = 0;
      		$TOT_DISC   = 0;
			foreach($_POST['data'] as $d)
			{
				$TAX_PPN		= $d['TAXPRICE1'];
				$TOT_PPN		= $TOT_PPN + $TAX_PPN;
				$TAX_PPH		= $d['TAXPRICE2'];
				$TOT_PPH		= $TOT_PPH + $TAX_PPH;

				/*$WO_DISCP   	= $d['WO_DISCP'];
			    $TOT_DISC   	= $TOT_DISC + $WO_DISCP;*/

				$WOTOTAL		= $d['WO_TOTAL'];
				$WO_GTOTAL		= $WO_GTOTAL + $WOTOTAL + $TOT_PPN - $TOT_PPH;
			}

			if($WO_STAT == 6)
			{
				$projWOH 		= array('WO_STAT'		=> $WO_STAT,
										'WO_MEMO'		=> $WO_MEMO);
				$this->m_spk->update($WO_NUM, $projWOH);

				foreach($_POST['data'] as $d)
				{
					$WO_NUM		= $d['WO_NUM'];
					$JOBCODEID1	= $d['JOBCODEID'];
					$ITM_CODE	= $d['ITM_CODE'];
					$WO_VOLM	= $d['WO_VOLM'];
					$WO_TOTAL	= $d['WO_TOTAL'];
					$param 		= array('WO_NUM' 		=> $WO_NUM,
										'JOBCODEID' 	=> $JOBCODEID1,
										'ITM_CODE' 		=> $ITM_CODE,
										'WO_VOLM'		=> $WO_VOLM,
										'WO_TOTAL'		=> $WO_TOTAL);
					$this->m_spk->closedUPDWO($WO_NUM, $PRJCODE, $param);
				}

				// SEKALIAN SYNC ALL
					$this->m_spk->closedWO($WO_NUM, $PRJCODE);
			
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "WO_NUM",
											'DOC_CODE' 		=> $WO_NUM,
											'DOC_STAT' 		=> $WO_STAT,
											'PRJCODE' 		=> $PRJCODE,
											//'CREATERNM'	=> $completeName,
											'TBLNAME'		=> "tbl_wo_header");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
			}
			elseif($WO_STAT == 9)
			{
				$projWOH 		= array('WO_STAT'		=> $WO_STAT,
										'WO_MEMO'		=> $WO_MEMO);
				$this->m_spk->update($WO_NUM, $projWOH);
				
				foreach($_POST['data'] as $d)
				{
					$WO_NUM		= $d['WO_NUM'];
					$JOBCODEID1	= $d['JOBCODEID'];
					$ITM_CODE	= $d['ITM_CODE'];
					$WO_VOLM	= $d['WO_VOLM'];
					$WO_TOTAL	= $d['WO_TOTAL'];
					$param 		= array('WO_NUM' 		=> $WO_NUM,
										'JOBCODEID' 	=> $JOBCODEID1,
										'ITM_CODE' 		=> $ITM_CODE,
										'WO_VOLM'		=> $WO_VOLM,
										'WO_TOTAL'		=> $WO_TOTAL);
					$this->m_spk->voidUPDJO($WO_NUM, $PRJCODE, $param);
				}
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "WO_NUM",
											'DOC_CODE' 		=> $WO_NUM,
											'DOC_STAT' 		=> $WO_STAT,
											'PRJCODE' 		=> $PRJCODE,
											//'CREATERNM'		=> $completeName,
											'TBLNAME'		=> "tbl_wo_header");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
			}
			else
			{
				$projWOH 		= array('WO_NUM' 		=> $WO_NUM,
										'WO_CODE' 		=> $WO_CODE,
										'WO_DATE'		=> $WO_DATE,
										'WO_STARTD'		=> $WO_STARTD,
										'WO_ENDD'		=> $WO_ENDD,
										'PRJCODE'		=> $PRJCODE,
										'SPLCODE'		=> $SPLCODE,
										'WO_DEPT'		=> $WO_DEPT,
										'WO_CATEG'		=> $WO_CATEG,
										'WO_TYPE'		=> $WO_TYPE,
										//'WO_PAYTYPE'	=> $WO_PAYTYPE,
										'WO_VALUE'		=> $WO_VALUE,
										'JOBCODEID'		=> $JOBCODEID,
										'WO_NOTE'		=> $WO_NOTE,
										'WO_NOTE2'		=> $WO_NOTE2,
										'WO_MEMO'		=> $WO_MEMO,
										'WO_STAT'		=> $WO_STAT,
										'FPA_NUM'		=> $FPA_NUM,
										'WO_REFNO'		=> $WO_REFNO,
										'FPA_CODE'		=> $FPA_CODE,
										'WO_QUOT'		=> $WO_QUOT,
										'WO_NEGO'		=> $WO_NEGO,
										'WO_DPPER'		=> $WO_DPPER,
										'WO_DPREF'		=> $WO_DPREF,
										'WO_DPREF1'		=> $WO_DPREF1,
										'WO_DPVAL'		=> $WO_DPVAL,
										'Patt_Year'		=> $Patt_Year, 
										'Patt_Month'	=> $Patt_Month,
										'Patt_Date'		=> $Patt_Date,
										'Patt_Number'	=> $this->input->post('Patt_Number'));
				$this->m_spk->update($WO_NUM, $projWOH);
				
				$this->m_spk->deleteDetail($WO_NUM);
				
				$WOID 			= 0;
				foreach($_POST['data'] as $d)
				{
					$d['WO_ID']		= $WOID+1;
					$this->db->insert('tbl_wo_detail',$d);
				}

				// UPDATE HEADER : TOTAL PPN
					$projWOH 		= array('WO_VALUE' 	=> $WO_GTOTAL,
											'WO_VALPPN' => $TOT_PPN, 'WO_VALPPH' => $TOT_PPH);
					$this->m_spk->update($WO_NUM, $projWOH);

				// UPDATE DETAIL
					$this->m_spk->updateDet($WO_NUM, $PRJCODE, $WO_DATE);
			
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "WO_NUM",
											'DOC_CODE' 		=> $WO_NUM,
											'DOC_STAT' 		=> $WO_STAT,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> $completeName,
											'TBLNAME'		=> "tbl_wo_header");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
			}

			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);

				$STAT_BEFORE	= $this->input->post('STAT_BEFORE');		// IF "ADD" CONDITION ALWAYS = PR_STAT
				$parameters 	= array('DOC_CODE' 		=> $WO_NUM,			// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "WO",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_wo_header",	// TABLE NAME
										'KEY_NAME'		=> "WO_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "WO_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $WO_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_WO",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_WO_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_WO_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_WO_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_WO_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_WO_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_WO_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT

			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "WO_NUM",
										'DOC_CODE' 		=> $WO_NUM,
										'DOC_STAT' 		=> $WO_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_wo_header");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $WO_NUM;
				$MenuCode 		= 'MN238';
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

			// START : UPDATE QTY_COLLECTIVE
				$this->load->model('m_updash/m_updash', '', TRUE);

				$parameters 	= array('TR_TYPE'		=> "WO",
										'TR_DATE' 		=> $WO_DATE,
										'PRJCODE'		=> $PRJCODE);
				$this->m_updash->updateQtyColl($parameters);
			// END : UPDATE QTY_COLLECTIVE

			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			$url			= site_url('c_project/c_s180d0bpk/gallS180d0bpk/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function trash_WO() // U
	{
		$this->load->model('m_project/m_spk/m_spk', '', TRUE);
		/*$CollID		= $_GET['id'];
		$splitCode 	= explode("~", $CollID);
		$PR_NUM		= $splitCode[0];
		$PRJCODE	= $splitCode[1];*/
		$WO_NUM		= $_GET['id'];
		$PRJCODE	= 999;

		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();

			date_default_timezone_set("Asia/Jakarta");

			$this->m_spk->deleteWO($WO_NUM);

			// START : UPDATE TO TRANS-COUNT
				/*$this->load->model('m_updash/m_updash', '', TRUE);

				$PR_STAT		= 1;
				$STAT_BEFORE	= 1;										// IF "ADD" CONDITION ALWAYS = PR_STAT
				$parameters 	= array('DOC_CODE' 		=> $PR_NUM,			// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "PR",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_wo_header",	// TABLE NAME
										'KEY_NAME'		=> "PR_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "PR_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $PR_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_REQ",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_REQ_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_REQ_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_REQ_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_REQ_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_REQ_RJ",	// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_REQ_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
				$this->m_updash->updateDashData($parameters);*/
			// END : UPDATE TO TRANS-COUNT

			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}

			return $this->m_mailbox->get_all_mail_inbox($DefEmp_ID)->result();
		}
		else
		{
			redirect('__l1y');
		}
	}

 	function inbox() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		$url			= site_url('c_project/c_s180d0bpk/pR7_l5t_5pKx1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}

	function pR7_l5t_5pKx1() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_purchase/m_purchase_req/m_purchase_req', '', TRUE);

		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);

			$LangID 	= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN239';
				$data["MenuApp"] 	= 'MN239';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN239';
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data["secURL"] 	= "c_project/c_s180d0bpk/s5pK_1nb_5pKa/?id=";

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

	function s5pK_1nb_5pKa() // OK
	{
		$this->load->model('m_project/m_spk/m_spk', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		$LangID 	= $this->session->userdata['LangID'];
		// GET MENU DESC
			$mnCode				= 'MN239';
			$MenuCode			= 'MN239';
			$data["MenuCode"] 	= 'MN239';
			$data["MenuApp"] 	= 'MN239';
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
				$data["url_search"] = site_url('c_project/c_s180d0bpk/f4n7_5rcH1nB5pK/?id='.$this->url_encryption_helper->encode_url($PRJCODE));

				//$num_rows 			= $this->m_spk->count_all_WOInx($PRJCODE, $key, $DefEmp_ID);
				//$data["cData"] 		= $num_rows;
				//$data['vData']		= $this->m_spk->get_all_WOInb($PRJCODE, $start, $end, $key, $DefEmp_ID)->result();
			// -------------------- END : SEARCHING METHOD --------------------
			
			// GET MENU DESC
				$mnCode				= 'MN239';
				$data["MenuApp"] 	= 'MN239';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$data['title'] 		= $appName;
			$data['addURL'] 	= site_url('c_project/c_s180d0bpk/add/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['backURL'] 	= site_url('c_project/c_s180d0bpk/inbox/');
			$data['PRJCODE'] 	= $PRJCODE;
			$data['MenuCode']	= 'MN239';

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN239';
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

			$this->load->view('v_project/v_spk/spk_inb', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	// -------------------- START : SEARCHING METHOD --------------------
		function f4n7_5rcH1nB5pK()
		{
			$gEn5rcH		= $_POST['gEn5rcH'];
			$mxLS			= $_POST['maxLimDf'];
			$mxLF			= $_POST['maxLimit'];
			$PRJCODE		= $_GET['id'];
			$PRJCODE		= $this->url_encryption_helper->decode_url($PRJCODE);
			$collDATA		= "$gEn5rcH~$PRJCODE~$mxLS~$mxLF";
			$url			= site_url('c_project/c_s180d0bpk/s5pK_1nb_5pKa/?id='.$this->url_encryption_helper->encode_url($collDATA));
			redirect($url);
		}
	// -------------------- END : SEARCHING METHOD --------------------

  	function get_AllData_1n2() // GOOD
	{
		$PRJCODE		= $_GET['id'];

		$LangID     = $this->session->userdata['LangID'];
        
        $sqlTransl      = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
        $resTransl      = $this->db->query($sqlTransl)->result();
        foreach($resTransl as $rowTransl) :
            $TranslCode = $rowTransl->MLANG_CODE;
            $LangTransl = $rowTransl->LangTransl;

    		if($TranslCode == 'Date')$Date = $LangTransl;
    		if($TranslCode == 'Category')$Category = $LangTransl;
    		if($TranslCode == 'Description')$Description = $LangTransl;
    		if($TranslCode == 'SupplierName')$SupplierName = $LangTransl;
        endforeach;

		// START : FOR SERVER-SIDE
			$order 	= $this->input->get("order");

			$col 	= 0;
			$dir 	= "desc";
			if(!empty($order)) {
				foreach($order as $o) {
					$col 	= $o['column'];
					$dir	= $o['dir'];
				}
			}

			if($dir != "asc" && $dir != "desc")
			{
            	$dir = "desc";
        	}

			$columns_valid 	= array("WO_CODE",
									"WO_CATEG", 
									"WO_DATE", 
									"WO_NOTE", 
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
			$num_rows 		= $this->m_spk->get_AllDataC_1n2($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_spk->get_AllDataL_1n2($PRJCODE, $search, $length, $start, $order, $dir);

			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI)
			{
				$WO_NUM			= $dataI['WO_NUM'];
				$WO_CODE		= $dataI['WO_CODE'];
				$WO_DATE		= $dataI['WO_DATE'];
				$WO_ENDD		= $dataI['WO_ENDD'];
				//$WO_DATEV		= date('d M Y', strtotime($WO_DATE));
				$WO_DATEV		= strftime('%d %b %Y', strtotime($WO_DATE));
				$WO_ENDDV		= strftime('%d %b %Y', strtotime($WO_ENDD));

				$WO_CATEG		= $dataI['WO_CATEG'];
				$PRJCODE		= $dataI['PRJCODE'];
				$WO_NOTE		= $dataI['WO_NOTE'];
				$WO_NOTED		= '';
				if($WO_NOTE != '')
					$WO_NOTED	= " : $WO_NOTE";
				$WO_STAT		= $dataI['WO_STAT'];
				$WO_REFNO		= $dataI['WO_REFNO'];
				$WO_CREATER		= $dataI['WO_CREATER'];
				$WO_ISCLOSE		= $dataI['WO_ISCLOSE'];
				$JOBCODEID		= $dataI['JOBCODEID'];
				$WO_CDOC		= $dataI['WO_CDOC'];
				$JOBDESC		= $dataI['JOBDESC'];
				$SPLCODE		= $dataI['SPLCODE'];
				
				$SPLDESC		= '';
				$sqlSPLD		= "SELECT SPLDESC FROM tbl_supplier WHERE SPLCODE = '$SPLCODE' LIMIT 1";
				$resSPLD		= $this->db->query($sqlSPLD)->result();
				foreach($resSPLD as $rowSPLD) :
					$SPLDESC	= $rowSPLD->SPLDESC;		
				endforeach;
				
				$JOBDESCD		= "$JOBDESC$WO_NOTED";
				if($JOBDESCD == '')
				{
					$expJOB		= explode("~", $JOBCODEID);
					$JOBCODEID1	= $expJOB[0];
					$JOBDESC1	= '-';
					$sqlJOB		= "SELECT JOBDESC FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBCODEID1'";
					$resJOB		= $this->db->query($sqlJOB)->result();
					foreach($resJOB as $rowJOB) :
						$JOBDESC1 = $rowJOB->JOBDESC;		
					endforeach;
					$JOBDESCD	= $JOBDESC1;				
				}
								
				if($WO_CATEG == 'MDR')
				{
					$WO_CATEGD	= 'Mandor';
				}
				elseif($WO_CATEG == 'SUB')
				{
					$WO_CATEGD 	= 'Subkon';
				}
				else
				{
					$WO_CATEGD 	= 'Alat';
				}
				$STATDESC	= $dataI['STATDESC'];
				$STATCOL	= $dataI['STATCOL'];
				$CREATERNM	= $dataI['CREATERNM'];
				$empName	= cut_text2 ("$CREATERNM", 15);
				$WO_REFNO	= $dataI['WO_REFNO'];

				$CollID		= "$PRJCODE~$WO_NUM";
				$secUpd		= site_url('c_project/c_s180d0bpk/update_inb/?id='.$this->url_encryption_helper->encode_url($CollID));
				$secPrint	= site_url('c_project/c_s180d0bpk/printdocument/?id='.$this->url_encryption_helper->encode_url($WO_NUM));

				if($WO_CATEG == 'MDR')
					$secPrint	= site_url('c_project/c_s180d0bpk/pr1n7d0c_m4d/?id='.$this->url_encryption_helper->encode_url($WO_NUM));
				else
					$secPrint	= site_url('c_project/c_s180d0bpk/pr1n7d0c_e/?id='.$this->url_encryption_helper->encode_url($WO_NUM));

				$CollID		= "$WO_NUM~$PRJCODE";
				$secDel_WO 	= base_url().'index.php/c_project/c_s180d0bpk/trash_WO/?id='.$WO_NUM;

				if($WO_STAT == 1)
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

				$output['data'][] 	= array("<div style='white-space:nowrap'>
												<strong>$WO_CODE</strong><br>
												<strong><i class='fa fa-calendar margin-r-5'></i> ".$Date." </strong>
										  		<div>
											  		<p class='text-muted' style='margin-left: 15px'>
											  			".$WO_DATEV."
											  		</p>
											  	</div>
											</div>",
										  	"<div style='white-space:nowrap'>
											  	<strong><i class='fa fa-cog margin-r-5'></i> ".$Category." </strong>
										  		<div>
											  		<p class='text-muted' style='margin-left: 15px'>
											  			".$WO_CATEGD."
											  		</p>
											  	</div>
											  	<strong><i class='fa fa-user margin-r-5'></i> Supplier / Sub. </strong>
										  		<div>
											  		<p class='text-muted' style='margin-left: 15px'>
											  			".$SPLDESC."
											  		</p>
											  	</div>
										  	</div>",
										  	"<strong><i class='fa fa-pencil margin-r-5'></i> ".$Description." </strong>
									  		<div class='text-muted' style='margin-left: 15px'>
										  		".$JOBDESCD."
										  	</div><br>
										  	<strong><i class='fa fa-paperclip margin-r-5'></i> No. SK </strong>
									  		<div class='text-muted' style='margin-left: 18px'>
										  		".$WO_REFNO."
										  	</div>",
											$empName,
											"<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
											$secAction);
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

	function update_inb()
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_project/m_spk/m_spk', '', TRUE);
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
			
		// GET MENU DESC
			$mnCode				= 'MN239';
			$MenuCode			= 'MN239';
			$data["MenuCode"] 	= 'MN239';
			$data["MenuApp"] 	= 'MN239';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;

		if ($this->session->userdata('login') == TRUE)
		{
			$COLLDATA	= $_GET['id'];
			$COLLDATA	= $this->url_encryption_helper->decode_url($COLLDATA);
			$EXTRACTCOL	= explode("~", $COLLDATA);
			$PRJCODE	= $EXTRACTCOL[0];
			$WO_NUM		= $EXTRACTCOL[1];
			
			$docPatternPosition = 'Especially';
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_project/c_s180d0bpk/update_process_inb');

			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();

			$getwodata 						= $this->m_spk->get_WO_by_number($WO_NUM)->row();
			$data['default']['WO_NUM'] 		= $getwodata->WO_NUM;
			$data['default']['WO_CODE'] 	= $getwodata->WO_CODE;
			$data['default']['WO_DATE'] 	= $getwodata->WO_DATE;
			$data['default']['WO_STARTD'] 	= $getwodata->WO_STARTD;
			$data['default']['WO_ENDD'] 	= $getwodata->WO_ENDD;
			$data['default']['PRJCODE']		= $getwodata->PRJCODE;
			$data['PRJCODE']				= $getwodata->PRJCODE;
			$PRJCODE 						= $getwodata->PRJCODE;
			$data['default']['SPLCODE'] 	= $getwodata->SPLCODE;
			$data['default']['WO_DEPT'] 	= $getwodata->WO_DEPT;
			$data['default']['WO_CATEG'] 	= $getwodata->WO_CATEG;
			$WO_CATEG						= $getwodata->WO_CATEG;
			$data['default']['WO_TYPE'] 	= $getwodata->WO_TYPE;
			//$data['default']['WO_PAYTYPE'] 	= $getwodata->WO_PAYTYPE;
			$data['default']['JOBCODEID'] 	= $getwodata->JOBCODEID;
			$data['default']['WO_NOTE'] 	= $getwodata->WO_NOTE;
			$data['default']['WO_NOTE2'] 	= $getwodata->WO_NOTE2;
			$data['default']['WO_STAT'] 	= $getwodata->WO_STAT;
			$data['default']['WO_VALUE'] 	= $getwodata->WO_VALUE;
			$data['default']['WO_MEMO'] 	= $getwodata->WO_MEMO;
			$data['default']['WO_REFNO'] 	= $getwodata->WO_REFNO;
			$data['default']['PRJNAME'] 	= $getwodata->PRJNAME;
			$data['default']['FPA_NUM'] 	= $getwodata->FPA_NUM;
			$data['default']['FPA_CODE'] 	= $getwodata->FPA_CODE;
			$data['default']['WO_QUOT'] 	= $getwodata->WO_QUOT;
			$data['default']['WO_NEGO'] 	= $getwodata->WO_NEGO;
			$data['default']['WO_DPPER'] 	= $getwodata->WO_DPPER;
			$data['default']['WO_DPREF'] 	= $getwodata->WO_DPREF;
			$data['default']['WO_DPREF1'] 	= $getwodata->WO_DPREF1;
			$data['default']['WO_DPVAL'] 	= $getwodata->WO_DPVAL;
			$data['default']['Patt_Year'] 	= $getwodata->Patt_Year;
			$data['default']['Patt_Month'] 	= $getwodata->Patt_Month;
			$data['default']['Patt_Date'] 	= $getwodata->Patt_Date;
			$data['default']['Patt_Number']	= $getwodata->Patt_Number;

			$MenuCode 			= 'MN239';
			$data["MenuCode"] 	= 'MN239';

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getwodata->WO_NUM;
				$MenuCode 		= 'MN239';
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

			$this->load->view('v_project/v_spk/spk_inb_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function update_process_inb() // OK
	{
		$this->load->model('m_project/m_spk/m_spk', '', TRUE);
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

			$WO_NUM 		= $this->input->post('WO_NUM');
			$WO_DATE		= date('Y-m-d',strtotime($this->input->post('WO_DATE')));
			$PRJCODE 		= $this->input->post('PRJCODE');
			$WO_STAT 		= $this->input->post('WO_STAT');
			$WO_CATEG 		= $this->input->post('WO_CATEG');
			$WO_NOTE2		= addslashes($this->input->post('WO_NOTE2'));

			if($WO_STAT == 3)
			{
				// START : SAVE APPROVE HISTORY
					$this->load->model('m_updash/m_updash', '', TRUE);

					$AH_CODE		= $WO_NUM;
					$AH_APPLEV		= $this->input->post('APP_LEVEL');
					$AH_APPROVER	= $DefEmp_ID;
					$AH_APPROVED	= date('Y-m-d H:i:s');
					$AH_NOTES		= addslashes($this->input->post('WO_NOTE2'));
					$AH_ISLAST		= $this->input->post('IS_LAST');

					$insAppHist 	= array('AH_CODE'		=> $AH_CODE,
											'AH_APPLEV'		=> $AH_APPLEV,
											'AH_APPROVER'	=> $AH_APPROVER,
											'AH_APPROVED'	=> $AH_APPROVED,
											'AH_NOTES'		=> $AH_NOTES,
											'AH_ISLAST'		=> $AH_ISLAST);
					$this->m_updash->insAppHist($insAppHist);

					$projWOH		= array('WO_STAT'	=> 7,
											'WO_NOTE2'	=> $WO_NOTE2);		// Default ke waiting jika masih ada approver yang lain
					$this->m_spk->update($WO_NUM, $projWOH);
				// END : SAVE APPROVE HISTORY

				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "WO_NUM",
											'DOC_CODE' 		=> $WO_NUM,
											'DOC_STAT' 		=> 7,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> '',
											'TBLNAME'		=> "tbl_wo_header");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
			}			
			if($WO_STAT == 3 && $AH_ISLAST == 1)
			{
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "WO_NUM",
											'DOC_CODE' 		=> $WO_NUM,
											'DOC_STAT' 		=> $WO_STAT,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> '',
											'TBLNAME'		=> "tbl_wo_header");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS

				$projWOH 		= array('WO_APPROVER'	=> $DefEmp_ID,
										'WO_APPROVED'	=> date('Y-m-d H:i:s'),
										'WO_NOTE2'		=> addslashes($this->input->post('WO_NOTE2')),
										'WO_STAT'		=> $this->input->post('WO_STAT'));
				$this->m_spk->update($WO_NUM, $projWOH);

				// UPDATE JOBDETAIL ITEM
				if($WO_STAT == 3)
				{
					$this->m_spk->updateWODet($WO_NUM, $PRJCODE);
				}
			}

			if($WO_STAT == 4)
			{
				$this->load->model('m_updash/m_updash', '', TRUE);
				// START : CLEAR HISTORY
					$cllPar = array('AH_CODE' 		=> $WO_NUM,
									'AH_APPROVER'	=> $DefEmp_ID);
					$this->m_updash->clearHist($cllPar);

					$projWOH 		= array('WO_NOTE2'		=> $WO_NOTE2,
											'WO_STAT'		=> $WO_STAT);
					$this->m_spk->update($WO_NUM, $projWOH);

					// START : UPDATE STATUS
						$completeName 	= $this->session->userdata['completeName'];
						$paramStat 		= array('PM_KEY' 		=> "WO_NUM",
												'DOC_CODE' 		=> $WO_NUM,
												'DOC_STAT' 		=> $WO_STAT,
												'PRJCODE' 		=> $PRJCODE,
												'CREATERNM'		=> '',
												'TBLNAME'		=> "tbl_wo_header");
						$this->m_updash->updateStatus($paramStat);
					// END : UPDATE STATUS
				// END : CLEAR HISTORY
			}
			elseif($WO_STAT == 5)
			{
				$this->load->model('m_updash/m_updash', '', TRUE);

				$projWOH 		= array('WO_NOTE2'		=> $WO_NOTE2,
										'WO_STAT'		=> $WO_STAT);
				$this->m_spk->update($WO_NUM, $projWOH);

				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "WO_NUM",
											'DOC_CODE' 		=> $WO_NUM,
											'DOC_STAT' 		=> $WO_STAT,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> '',
											'TBLNAME'		=> "tbl_wo_header");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
			}

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $WO_NUM;
				$MenuCode 		= 'MN239';
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

			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);

				$STAT_BEFORE	= $this->input->post('STAT_BEFORE');		// IF "ADD" CONDITION ALWAYS = PR_STAT
				$parameters 	= array('DOC_CODE' 		=> $WO_NUM,			// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "WO",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_wo_header",	// TABLE NAME
										'KEY_NAME'		=> "WO_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "WO_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $WO_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_WO",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_WO_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_WO_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_WO_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_WO_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_WO_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_WO_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT

			// START : UPDATE QTY_COLLECTIVE
				$this->load->model('m_updash/m_updash', '', TRUE);

				$parameters 	= array('TR_TYPE'		=> "WO",
										'TR_DATE' 		=> $WO_DATE,
										'PRJCODE'		=> $PRJCODE);
				$this->m_updash->updateQtyColl($parameters);
			// END : UPDATE QTY_COLLECTIVE

			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}

			if($WO_CATEG == 'SALT')
				$url	= site_url('c_project/c_s180d0bpk/ibx1_er/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			elseif($WO_CATEG == 'SUB')
				$url	= site_url('c_project/c_s180d0bpk/s5uB_1nb_5pK5uB/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			else
				$url	= site_url('c_project/c_s180d0bpk/s5pK_1nb_5pKa/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}

 	function inbox_sub() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		$url			= site_url('c_project/c_s180d0bpk/prj180d0blst_5uB/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}

	function prj180d0blst_5uB() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_project/m_spk/m_spk', '', TRUE);

		// GET MENU DESC
			$mnCode				= 'MN342';
			$MenuCode			= 'MN342';
			$data["MenuCode"] 	= 'MN342';
			$data["MenuApp"] 	= 'MN342';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);

			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Persetujuan SPK Sub.";
			}
			else
			{
				$data["h1_title"] 	= "Approval Sub. WO";
			}
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN342';
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data["secURL"] 	= "c_project/c_s180d0bpk/s5uB_1nb_5pK5uB/?id=";

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

	function s5uB_1nb_5pK5uB() // OK
	{
		$this->load->model('m_project/m_spk/m_spk', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;
		
		// GET MENU DESC
			$mnCode				= 'MN342';
			$MenuCode			= 'MN342';
			$data["MenuCode"] 	= 'MN342';
			$data["MenuApp"] 	= 'MN342';
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
				$data["url_search"] = site_url('c_project/c_s180d0bpk/f4n7_5rcH1nB5pK5Ub/?id='.$this->url_encryption_helper->encode_url($PRJCODE));

				//$num_rows 			= $this->m_spk->count_all_WOInx_sub($PRJCODE, $key);
				//$data["cData"] 		= $num_rows;
				//$data['vData']		= $this->m_spk->get_all_WOInb_sub($PRJCODE, $start, $end, $key)->result();
			// -------------------- END : SEARCHING METHOD --------------------

			$data['title'] 		= $appName;
			$data['backURL'] 	= site_url('c_project/c_s180d0bpk/inbox_sub/');
			$data['PRJCODE'] 	= $PRJCODE;
			$data['MenuCode']	= 'MN342';

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN342';
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

			$this->load->view('v_project/v_spk/sub_spk_inb', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	// -------------------- START : SEARCHING METHOD --------------------
		function f4n7_5rcH1nB5pK5Ub()
		{
			$gEn5rcH		= $_POST['gEn5rcH'];
			$mxLS			= $_POST['maxLimDf'];
			$mxLF			= $_POST['maxLimit'];
			$PRJCODE		= $_GET['id'];
			$PRJCODE		= $this->url_encryption_helper->decode_url($PRJCODE);
			$collDATA		= "$gEn5rcH~$PRJCODE~$mxLS~$mxLF";
			$url			= site_url('c_project/c_s180d0bpk/s5uB_1nb_5pK5uB/?id='.$this->url_encryption_helper->encode_url($collDATA));
			redirect($url);
		}
	// -------------------- END : SEARCHING METHOD --------------------

  	function get_AllDataSC_1n2() // GOOD
	{
		$PRJCODE		= $_GET['id'];

		$LangID     = $this->session->userdata['LangID'];
        
        $sqlTransl      = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
        $resTransl      = $this->db->query($sqlTransl)->result();
        foreach($resTransl as $rowTransl) :
            $TranslCode = $rowTransl->MLANG_CODE;
            $LangTransl = $rowTransl->LangTransl;

    		if($TranslCode == 'Date')$Date = $LangTransl;
    		if($TranslCode == 'Category')$Category = $LangTransl;
    		if($TranslCode == 'Description')$Description = $LangTransl;
    		if($TranslCode == 'SupplierName')$SupplierName = $LangTransl;
        endforeach;

		// START : FOR SERVER-SIDE
			$order 	= $this->input->get("order");

			$col 	= 0;
			$dir 	= "desc";
			if(!empty($order)) {
				foreach($order as $o) {
					$col 	= $o['column'];
					$dir	= $o['dir'];
				}
			}

			if($dir != "asc" && $dir != "desc")
			{
            	$dir = "desc";
        	}

			$columns_valid 	= array("WO_CODE",
									"WO_CATEG", 
									"WO_DATE", 
									"WO_NOTE", 
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
			$num_rows 		= $this->m_spk->get_AllDataCSC_1n2($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_spk->get_AllDataLSC_1n2($PRJCODE, $search, $length, $start, $order, $dir);

			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI)
			{
				$WO_NUM			= $dataI['WO_NUM'];
				$WO_CODE		= $dataI['WO_CODE'];
				$WO_DATE		= $dataI['WO_DATE'];
				$WO_ENDD		= $dataI['WO_ENDD'];
				//$WO_DATEV		= date('d M Y', strtotime($WO_DATE));
				$WO_DATEV1		= strftime('%d %b %Y', strtotime($WO_DATE));
				$WO_ENDDV		= strftime('%d %b %Y', strtotime($WO_ENDD));

				$WO_DATEV 		= $WO_DATEV1." - ".$WO_ENDDV;

				$WO_CATEG		= $dataI['WO_CATEG'];
				$PRJCODE		= $dataI['PRJCODE'];
				$WO_NOTE		= $dataI['WO_NOTE'];
				$WO_NOTED		= '';
				if($WO_NOTE != '')
					$WO_NOTED	= " : $WO_NOTE";
				$WO_STAT		= $dataI['WO_STAT'];
				$WO_REFNO		= $dataI['WO_REFNO'];
				$WO_CREATER		= $dataI['WO_CREATER'];
				$WO_ISCLOSE		= $dataI['WO_ISCLOSE'];
				$JOBCODEID		= $dataI['JOBCODEID'];
				$WO_CDOC		= $dataI['WO_CDOC'];
				$JOBDESC		= $dataI['JOBDESC'];
				$SPLCODE		= $dataI['SPLCODE'];
				
				$SPLDESC		= '';
				$sqlSPLD		= "SELECT SPLDESC FROM tbl_supplier WHERE SPLCODE = '$SPLCODE' LIMIT 1";
				$resSPLD		= $this->db->query($sqlSPLD)->result();
				foreach($resSPLD as $rowSPLD) :
					$SPLDESC	= $rowSPLD->SPLDESC;		
				endforeach;
				
				$JOBDESCD		= "$JOBDESC$WO_NOTED";
				if($JOBDESCD == '')
				{
					$expJOB		= explode("~", $JOBCODEID);
					$JOBCODEID1	= $expJOB[0];
					$JOBDESC1	= '';
					$sqlJOB		= "SELECT JOBDESC FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBCODEID1'";
					$resJOB		= $this->db->query($sqlJOB)->result();
					foreach($resJOB as $rowJOB) :
						$JOBDESC1 = $rowJOB->JOBDESC;		
					endforeach;
					$JOBDESCD	= $JOBDESC1;				
				}
								
				if($WO_CATEG == 'MDR')
				{
					$WO_CATEGD	= 'Mandor';
				}
				elseif($WO_CATEG == 'SUB')
				{
					$WO_CATEGD 	= 'Subkon';
				}
				else
				{
					$WO_CATEGD 	= 'Alat';
				}
				$STATDESC	= $dataI['STATDESC'];
				$STATCOL	= $dataI['STATCOL'];
				$CREATERNM	= $dataI['CREATERNM'];
				$empName	= cut_text2 ("$CREATERNM", 15);
				
				$CollID		= "$PRJCODE~$WO_NUM";
				$secUpd		= site_url('c_project/c_s180d0bpk/update_inb_sub/?id='.$this->url_encryption_helper->encode_url($CollID));
				$secPrint	= site_url('c_project/c_s180d0bpk/printdocument/?id='.$this->url_encryption_helper->encode_url($WO_NUM));
				if($WO_CATEG == 'MDR')
					$secPrint	= site_url('c_project/c_s180d0bpk/pr1n7d0c_m4d/?id='.$this->url_encryption_helper->encode_url($WO_NUM));
				else
					$secPrint	= site_url('c_project/c_s180d0bpk/pr1n7d0c_e/?id='.$this->url_encryption_helper->encode_url($WO_NUM));

				$CollID		= "$WO_NUM~$PRJCODE";
				$secDel_WO 	= base_url().'index.php/c_project/c_s180d0bpk/trash_WO/?id='.$WO_NUM;

				if($WO_STAT == 1)
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

				$output['data'][] 	= array("<strong>$WO_CODE</strong>",
										  	"<div style='white-space:nowrap'>
											  	<strong><i class='fa fa-user margin-r-5'></i> ".$SupplierName." </strong>
										  		<div>
											  		<p class='text-muted' style='margin-left: 15px'>
											  			".$SPLDESC."
											  		</p>
											  	</div>
											  	<strong><i class='fa fa-cog margin-r-5'></i> ".$Category." </strong>
										  		<div>
											  		<p class='text-muted' style='margin-left: 15px'>
											  			".$WO_CATEGD."
											  		</p>
											  	</div>
										  	</div>",
										  	"<strong><i class='fa fa-calendar margin-r-5'></i> ".$Date." </strong>
									  		<div>
										  		<p class='text-muted' style='margin-left: 15px'>
										  			".$WO_DATEV."
										  		</p>
										  	</div>
										  	<strong><i class='fa fa-pencil margin-r-5'></i> ".$Description." </strong>
									  		<div class='text-muted' style='margin-left: 15px'>
										  			".$JOBDESCD."
										  	</div>",
										  $empName,
										  "<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
										  $secAction);
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

	function update_inb_sub()
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_project/m_spk/m_spk', '', TRUE);
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;
		
		// GET MENU DESC
			$mnCode				= 'MN342';
			$MenuCode			= 'MN342';
			$data["MenuCode"] 	= 'MN342';
			$data["MenuApp"] 	= 'MN342';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

		if ($this->session->userdata('login') == TRUE)
		{
			$COLLDATA	= $_GET['id'];
			$COLLDATA	= $this->url_encryption_helper->decode_url($COLLDATA);
			$EXTRACTCOL	= explode("~", $COLLDATA);
			$PRJCODE	= $EXTRACTCOL[0];
			$WO_NUM		= $EXTRACTCOL[1];
			
			$docPatternPosition = 'Especially';
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_project/c_s180d0bpk/update_process_inb');

			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();

			$getwodata 						= $this->m_spk->get_WO_by_number($WO_NUM)->row();
			$data['default']['WO_NUM'] 		= $getwodata->WO_NUM;
			$data['default']['WO_CODE'] 	= $getwodata->WO_CODE;
			$data['default']['WO_DATE'] 	= $getwodata->WO_DATE;
			$data['default']['WO_STARTD'] 	= $getwodata->WO_STARTD;
			$data['default']['WO_ENDD'] 	= $getwodata->WO_ENDD;
			$data['default']['PRJCODE']		= $getwodata->PRJCODE;
			$data['PRJCODE']				= $getwodata->PRJCODE;
			$PRJCODE 						= $getwodata->PRJCODE;
			$data['default']['SPLCODE'] 	= $getwodata->SPLCODE;
			$data['default']['WO_DEPT'] 	= $getwodata->WO_DEPT;
			$data['default']['WO_CATEG'] 	= $getwodata->WO_CATEG;
			$WO_CATEG						= $getwodata->WO_CATEG;
			$data['default']['WO_TYPE'] 	= $getwodata->WO_TYPE;
			//$data['default']['WO_PAYTYPE'] 	= $getwodata->WO_PAYTYPE;
			$data['default']['JOBCODEID'] 	= $getwodata->JOBCODEID;
			$data['default']['WO_NOTE'] 	= $getwodata->WO_NOTE;
			$data['default']['WO_NOTE2'] 	= $getwodata->WO_NOTE2;
			$data['default']['WO_STAT'] 	= $getwodata->WO_STAT;
			$data['default']['WO_VALUE'] 	= $getwodata->WO_VALUE;
			$data['default']['WO_MEMO'] 	= $getwodata->WO_MEMO;
			$data['default']['WO_REFNO'] 	= $getwodata->WO_REFNO;
			$data['default']['PRJNAME'] 	= $getwodata->PRJNAME;
			$data['default']['FPA_NUM'] 	= $getwodata->FPA_NUM;
			$data['default']['FPA_CODE'] 	= $getwodata->FPA_CODE;
			$data['default']['WO_QUOT'] 	= $getwodata->WO_QUOT;
			$data['default']['WO_NEGO'] 	= $getwodata->WO_NEGO;
			$data['default']['WO_DPPER'] 	= $getwodata->WO_DPPER;
			$data['default']['WO_DPREF'] 	= $getwodata->WO_DPREF;
			$data['default']['WO_DPREF1'] 	= $getwodata->WO_DPREF1;
			$data['default']['WO_DPVAL'] 	= $getwodata->WO_DPVAL;
			$data['default']['Patt_Year'] 	= $getwodata->Patt_Year;
			$data['default']['Patt_Month'] 	= $getwodata->Patt_Month;
			$data['default']['Patt_Date'] 	= $getwodata->Patt_Date;
			$data['default']['Patt_Number']	= $getwodata->Patt_Number;

			$MenuCode 			= 'MN342';
			$data["MenuCode"] 	= 'MN342';

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getwodata->WO_NUM;
				$MenuCode 		= 'MN342';
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

			$this->load->view('v_project/v_spk/sub_spk_inb_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function cr3473d0c_m4d()	// Create Document
	{
		$this->load->model('m_project/m_spk/m_spk', '', TRUE);
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

		$WO_NUM		= $_GET['id'];
		$WO_NUM		= $this->url_encryption_helper->decode_url($WO_NUM);

		if ($this->session->userdata('login') == TRUE)
		{
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Buat Draft Baru";
				$data["page"] 		= "Halaman";
			}
			else
			{
				$data["h1_title"] 	= "Create New Draft";
				$data["page"] 		= "Page";
			}

			$getwodata 					= $this->m_spk->get_WO_by_number($WO_NUM)->row();
			$PRJCODE					= $getwodata->PRJCODE;
			$data['def']['PRJCODE'] 	= $getwodata->PRJCODE;
			$data['def']['WOP_NUM'] 	= $getwodata->WO_NUM;
			$data['def']['WOP_CODE'] 	= $getwodata->WO_CODE;

			$num_rows 						= $this->m_spk->get_WOP_count($WO_NUM);
			if($num_rows > 0)
			{
				$getwopdata 				= $this->m_spk->get_WOP_by_number($WO_NUM)->row();
				$PRJCODE					= $getwopdata->PRJCODE;
				$data['def']['PRJCODE'] 	= $getwopdata->PRJCODE;
				$data['def']['WOP_NUM'] 	= $getwopdata->WOP_NUM;
				$data['def']['WOP_CODE'] 	= $getwopdata->WOP_CODE;
				$data['def']['WOP_TITLE'] 	= $getwopdata->WOP_TITLE;
				$data['def']['WOP_PAGE1'] 	= $getwopdata->WOP_PAGE1;
				$data['def']['WOP_PAGE2'] 	= $getwopdata->WOP_PAGE2;
				$data['def']['WOP_PAGE3'] 	= $getwopdata->WOP_PAGE3;
				$data['def']['WOP_PAGE4']	= $getwopdata->WOP_PAGE4;
				$data['def']['WOP_PAGE5']	= $getwopdata->WOP_PAGE5;
			}
			else
			{
				$data['def']['WOP_TITLE'] 	= '';
				$data['def']['WOP_PAGE1'] 	= '';
				$data['def']['WOP_PAGE2'] 	= '';
				$data['def']['WOP_PAGE3'] 	= '';
				$data['def']['WOP_PAGE4']	= '';
				$data['def']['WOP_PAGE5']	= '';
			}

			$MenuCode 			= 'MN238';
			$data["MenuCode"] 	= 'MN238';

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getwodata->WO_NUM;
				$MenuCode 		= 'MN239';
				$TTR_CATEG		= 'C-DOC';

				$this->load->model('m_updash/m_updash', '', TRUE);
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK

			$this->load->view('v_project/v_spk/spk_createdoc', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function pr1n7d0c_e()
	{
		$this->load->model('m_project/m_spk/m_spk', '', TRUE);
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
      	$data['appName'] = $appName;
      	$data['comp_add'] = $therow->comp_add;
		endforeach;
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

		$WO_NUM		= $_GET['id'];
		$WO_NUM		= $this->url_encryption_helper->decode_url($WO_NUM);

		if ($this->session->userdata('login') == TRUE)
		{
			$getwodata 						= $this->m_spk->get_WO_by_number($WO_NUM)->row();
			$data['default']['WO_NUM'] 		= $getwodata->WO_NUM;
			$data['default']['WO_CODE'] 	= $getwodata->WO_CODE;
			$data['default']['WO_DATE'] 	= $getwodata->WO_DATE;
			$data['default']['WO_STARTD'] 	= $getwodata->WO_STARTD;
			$data['default']['WO_ENDD'] 	= $getwodata->WO_ENDD;
			$data['default']['PRJCODE']		= $getwodata->PRJCODE;
			$data['PRJCODE']				= $getwodata->PRJCODE;
			$PRJCODE 						= $getwodata->PRJCODE;
			$data['default']['SPLCODE'] 	= $getwodata->SPLCODE;
			$data['default']['WO_DEPT'] 	= $getwodata->WO_DEPT;
			$data['default']['WO_CATEG'] 	= $getwodata->WO_CATEG;
			$WO_CATEG 						= $getwodata->WO_CATEG;
			$data["WO_CATEG"] 				= $getwodata->WO_CATEG;
			$data['default']['WO_TYPE'] 	= $getwodata->WO_TYPE;
			//$data['default']['WO_PAYTYPE'] 	= $getwodata->WO_PAYTYPE;
			$data['default']['JOBCODEID'] 	= $getwodata->JOBCODEID;
			$data['default']['WO_NOTE'] 	= $getwodata->WO_NOTE;
			$data['default']['WO_NOTE2'] 	= $getwodata->WO_NOTE2;
			$data['default']['WO_STAT'] 	= $getwodata->WO_STAT;
			$data['default']['WO_VALUE'] 	= $getwodata->WO_VALUE;
			$data['default']['WO_MEMO'] 	= $getwodata->WO_MEMO;
			$data['default']['PRJNAME'] 	= $getwodata->PRJNAME;
			$data['default']['FPA_NUM'] 	= $getwodata->FPA_NUM;
			$data['default']['FPA_CODE'] 	= $getwodata->FPA_CODE;
			$data['default']['WO_QUOT'] 	= $getwodata->WO_QUOT;
			$data['default']['WO_NEGO'] 	= $getwodata->WO_NEGO;
			$data['default']['Patt_Year'] 	= $getwodata->Patt_Year;
			$data['default']['Patt_Month'] 	= $getwodata->Patt_Month;
			$data['default']['Patt_Date'] 	= $getwodata->Patt_Date;
			$data['default']['Patt_Number']	= $getwodata->Patt_Number;

      		$data['countwodet'] = $this->m_spk->count_WODET_by_number($WO_NUM);
      		$data['vwodet']   = $this->m_spk->get_WODET_by_number($WO_NUM);

			$MenuCode 			= 'MN238';
			$data["MenuCode"] 	= 'MN238';

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getwodata->WO_NUM;
				$MenuCode 		= 'MN239';
				$TTR_CATEG		= 'P';

				$this->load->model('m_updash/m_updash', '', TRUE);
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			if($WO_CATEG == 'SALT')
				$this->load->view('v_project/v_spk/spk_printdoc_salt', $data);
			else
				$this->load->view('v_project/v_spk/spk_printdoc_sub', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function pr1n7d0c_m4d()		// Khusus mandor
	{
		$this->load->model('m_project/m_spk/m_spk', '', TRUE);
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

		$WO_NUM		= $_GET['id'];
		$WO_NUM		= $this->url_encryption_helper->decode_url($WO_NUM);

		if ($this->session->userdata('login') == TRUE)
		{
			$getwodata 						= $this->m_spk->get_WO_by_number($WO_NUM)->row();
			$data['default']['WO_NUM'] 		= $getwodata->WO_NUM;
			$data['default']['WO_CODE'] 	= $getwodata->WO_CODE;
			$data['default']['WO_DATE'] 	= $getwodata->WO_DATE;
			$data['default']['WO_STARTD'] 	= $getwodata->WO_STARTD;
			$data['default']['WO_ENDD'] 	= $getwodata->WO_ENDD;
			$data['default']['PRJCODE']		= $getwodata->PRJCODE;
			$data['PRJCODE']				= $getwodata->PRJCODE;
			$PRJCODE 						= $getwodata->PRJCODE;
			$data['default']['SPLCODE'] 	= $getwodata->SPLCODE;
			$data['default']['WO_DEPT'] 	= $getwodata->WO_DEPT;
			$data['default']['WO_CATEG'] 	= $getwodata->WO_CATEG;
			$WO_CATEG 						= $getwodata->WO_CATEG;
			$data["WO_CATEG"] 				= $getwodata->WO_CATEG;
			$data['default']['WO_TYPE'] 	= $getwodata->WO_TYPE;
			//$data['default']['WO_PAYTYPE'] 	= $getwodata->WO_PAYTYPE;
			$data['default']['JOBCODEID'] 	= $getwodata->JOBCODEID;
			$data['default']['WO_NOTE'] 	= $getwodata->WO_NOTE;
			$data['default']['WO_NOTE2'] 	= $getwodata->WO_NOTE2;
			$data['default']['WO_STAT'] 	= $getwodata->WO_STAT;
			$data['default']['WO_VALUE'] 	= $getwodata->WO_VALUE;
			$data['default']['WO_MEMO'] 	= $getwodata->WO_MEMO;
			$data['default']['PRJNAME'] 	= $getwodata->PRJNAME;
			$data['default']['WO_REFNO'] 	= $getwodata->WO_REFNO;
			$data['default']['FPA_NUM'] 	= $getwodata->FPA_NUM;
			$data['default']['FPA_CODE'] 	= $getwodata->FPA_CODE;
			$data['default']['WO_QUOT'] 	= $getwodata->WO_QUOT;
			$data['default']['WO_NEGO'] 	= $getwodata->WO_NEGO;
			$data['default']['Patt_Year'] 	= $getwodata->Patt_Year;
			$data['default']['Patt_Month'] 	= $getwodata->Patt_Month;
			$data['default']['Patt_Date'] 	= $getwodata->Patt_Date;
			$data['default']['Patt_Number']	= $getwodata->Patt_Number;

			$MenuCode 			= 'MN238';
			$data["MenuCode"] 	= 'MN238';

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getwodata->WO_NUM;
				$MenuCode 		= 'MN239';
				$TTR_CATEG		= 'P';

				$this->load->model('m_updash/m_updash', '', TRUE);
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK

			$this->load->view('v_project/v_spk/spk_printdoc_mdr', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function r3q_70ls_pr1n7()
	{
		$this->load->model('m_project/m_spk/m_spk', '', TRUE);
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

		$WO_NUM		= $_GET['id'];
		$WO_NUM		= $this->url_encryption_helper->decode_url($WO_NUM);

		if ($this->session->userdata('login') == TRUE)
		{
			$getwodata 						= $this->m_spk->get_WOTLS_by_number($WO_NUM)->row();
			$data['default']['WO_NUM'] 		= $getwodata->WO_NUM;
			$data['default']['WO_CODE'] 	= $getwodata->WO_CODE;
			$data['default']['WO_DATE'] 	= $getwodata->WO_DATE;
			$data['default']['WO_STARTD'] 	= $getwodata->WO_STARTD;
			$data['default']['WO_ENDD'] 	= $getwodata->WO_ENDD;
			$data['default']['PRJCODE']		= $getwodata->PRJCODE;
			$data['PRJCODE']				= $getwodata->PRJCODE;
			$PRJCODE 						= $getwodata->PRJCODE;
			$data['default']['SPLCODE'] 	= $getwodata->SPLCODE;
			$data['default']['WO_DEPT'] 	= $getwodata->WO_DEPT;
			$data['default']['WO_CATEG'] 	= $getwodata->WO_CATEG;
			$data['default']['WO_TYPE'] 	= $getwodata->WO_TYPE;
			//$data['default']['WO_PAYTYPE'] 	= $getwodata->WO_PAYTYPE;
			$data['default']['JOBCODEID'] 	= $getwodata->JOBCODEID;
			$data['default']['WO_NOTE'] 	= $getwodata->WO_NOTE;
			$data['default']['WO_NOTE2'] 	= $getwodata->WO_NOTE2;
			$data['default']['WO_STAT'] 	= $getwodata->WO_STAT;
			$data['default']['WO_VALUE'] 	= $getwodata->WO_VALUE;
			$data['default']['WO_MEMO'] 	= $getwodata->WO_MEMO;
			$data['default']['PRJNAME'] 	= $getwodata->PRJNAME;
			//$data['default']['FPA_NUM'] 	= $getwodata->FPA_NUM;
			//$data['default']['FPA_CODE'] 	= $getwodata->FPA_CODE;
			$data['default']['Patt_Year'] 	= $getwodata->Patt_Year;
			$data['default']['Patt_Month'] 	= $getwodata->Patt_Month;
			$data['default']['Patt_Date'] 	= $getwodata->Patt_Date;
			$data['default']['Patt_Number']	= $getwodata->Patt_Number;

			$MenuCode 			= 'MN238';
			$data["MenuCode"] 	= 'MN238';

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getwodata->WO_NUM;
				$MenuCode 		= 'MN239';
				$TTR_CATEG		= 'P';

				$this->load->model('m_updash/m_updash', '', TRUE);
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK

			$this->load->view('v_project/v_spk/spk_req_print', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

 	function inbox_er() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		$url			= site_url('c_project/c_s180d0bpk/prj180d0blst_4L7/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}

	function prj180d0blst_4L7() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_project/m_spk/m_spk', '', TRUE);

		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);

			$LangID 	= $this->session->userdata['LangID'];
			// GET MENU DESC
				$mnCode				= 'MN354';
				$MenuCode			= 'MN354';
				$data["MenuCode"] 	= 'MN354';
				$data["MenuApp"] 	= 'MN354';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;

			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN354';
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data["secURL"] 	= "c_project/c_s180d0bpk/ibx1_er/?id=";

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

	function ibx1_er() // OK
	{
		$this->load->model('m_project/m_spk/m_spk', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;
		
		// GET MENU DESC
			$mnCode				= 'MN354';
			$MenuCode			= 'MN354';
			$data["MenuCode"] 	= 'MN354';
			$data["MenuApp"] 	= 'MN354';
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
				$data["url_search"] = site_url('c_project/c_s180d0bpk/f4n7_5rcH1nB5pK4L7/?id='.$this->url_encryption_helper->encode_url($PRJCODE));

				//$num_rows 			= $this->m_spk->count_all_WOInx_er($PRJCODE, $key);
				//$data["cData"] 		= $num_rows;
				//$data['vData']		= $this->m_spk->get_all_WOInb_er($PRJCODE, $start, $end, $key)->result();
			// -------------------- END : SEARCHING METHOD --------------------

			$data['title'] 		= $appName;
			$data['backURL'] 	= site_url('c_project/c_s180d0bpk/inbox_er/');
			$data['PRJCODE'] 	= $PRJCODE;
			$data['MenuCode'] 		= 'MN354';

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN354';
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

			$this->load->view('v_project/v_spk/er_spk_inb', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	// -------------------- START : SEARCHING METHOD --------------------
		function f4n7_5rcH1nB5pK4L7()
		{
			$gEn5rcH		= $_POST['gEn5rcH'];
			$mxLS			= $_POST['maxLimDf'];
			$mxLF			= $_POST['maxLimit'];
			$PRJCODE		= $_GET['id'];
			$PRJCODE		= $this->url_encryption_helper->decode_url($PRJCODE);
			$collDATA		= "$gEn5rcH~$PRJCODE~$mxLS~$mxLF";
			$url			= site_url('c_project/c_s180d0bpk/ibx1_er/?id='.$this->url_encryption_helper->encode_url($collDATA));
			redirect($url);
		}
	// -------------------- END : SEARCHING METHOD --------------------

  	function get_AllDataTLS_1n2() // GOOD
	{
		$PRJCODE		= $_GET['id'];

		$LangID     = $this->session->userdata['LangID'];
        
        $sqlTransl      = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
        $resTransl      = $this->db->query($sqlTransl)->result();
        foreach($resTransl as $rowTransl) :
            $TranslCode = $rowTransl->MLANG_CODE;
            $LangTransl = $rowTransl->LangTransl;

    		if($TranslCode == 'Date')$Date = $LangTransl;
    		if($TranslCode == 'Category')$Category = $LangTransl;
    		if($TranslCode == 'Description')$Description = $LangTransl;
    		if($TranslCode == 'SupplierName')$SupplierName = $LangTransl;
        endforeach;

		// START : FOR SERVER-SIDE
			$order 	= $this->input->get("order");

			$col 	= 0;
			$dir 	= "desc";
			if(!empty($order)) {
				foreach($order as $o) {
					$col 	= $o['column'];
					$dir	= $o['dir'];
				}
			}

			if($dir != "asc" && $dir != "desc")
			{
            	$dir = "desc";
        	}

			$columns_valid 	= array("WO_CODE",
									"WO_CATEG", 
									"WO_DATE", 
									"WO_NOTE", 
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
			$num_rows 		= $this->m_spk->get_AllDataCTLS_1n2($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_spk->get_AllDataLTLS_1n2($PRJCODE, $search, $length, $start, $order, $dir);

			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI)
			{
				$WO_NUM			= $dataI['WO_NUM'];
				$WO_CODE		= $dataI['WO_CODE'];
				$WO_DATE		= $dataI['WO_DATE'];
				$WO_ENDD		= $dataI['WO_ENDD'];
				//$WO_DATEV		= date('d M Y', strtotime($WO_DATE));
				$WO_DATEV1		= strftime('%d %b %Y', strtotime($WO_DATE));
				$WO_ENDDV		= strftime('%d %b %Y', strtotime($WO_ENDD));

				$WO_DATEV 		= $WO_DATEV1." - ".$WO_ENDDV;

				$WO_CATEG		= $dataI['WO_CATEG'];
				$PRJCODE		= $dataI['PRJCODE'];
				$WO_NOTE		= $dataI['WO_NOTE'];
				$WO_NOTED		= '';
				if($WO_NOTE != '')
					$WO_NOTED	= " : $WO_NOTE";
				$WO_STAT		= $dataI['WO_STAT'];
				$WO_REFNO		= $dataI['WO_REFNO'];
				$WO_CREATER		= $dataI['WO_CREATER'];
				$WO_ISCLOSE		= $dataI['WO_ISCLOSE'];
				$JOBCODEID		= $dataI['JOBCODEID'];
				$WO_CDOC		= $dataI['WO_CDOC'];
				$JOBDESC		= $dataI['JOBDESC'];
				$SPLCODE		= $dataI['SPLCODE'];
				
				$SPLDESC		= '';
				$sqlSPLD		= "SELECT SPLDESC FROM tbl_supplier WHERE SPLCODE = '$SPLCODE' LIMIT 1";
				$resSPLD		= $this->db->query($sqlSPLD)->result();
				foreach($resSPLD as $rowSPLD) :
					$SPLDESC	= $rowSPLD->SPLDESC;		
				endforeach;
				
				$JOBDESCD		= "$JOBDESC$WO_NOTED";
				if($JOBDESCD == '')
				{
					$expJOB		= explode("~", $JOBCODEID);
					$JOBCODEID1	= $expJOB[0];
					$JOBDESC1	= '';
					$sqlJOB		= "SELECT JOBDESC FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBCODEID1'";
					$resJOB		= $this->db->query($sqlJOB)->result();
					foreach($resJOB as $rowJOB) :
						$JOBDESC1 = $rowJOB->JOBDESC;		
					endforeach;
					$JOBDESCD	= $JOBDESC1;				
				}
								
				if($WO_CATEG == 'MDR')
				{
					$WO_CATEGD	= 'Mandor';
				}
				elseif($WO_CATEG == 'SUB')
				{
					$WO_CATEGD 	= 'Subkon';
				}
				else
				{
					$WO_CATEGD 	= 'Alat';
				}
				$STATDESC	= $dataI['STATDESC'];
				$STATCOL	= $dataI['STATCOL'];
				$CREATERNM	= $dataI['CREATERNM'];
				$empName	= cut_text2 ("$CREATERNM", 15);
				
				$CollID		= "$PRJCODE~$WO_NUM";
				$secUpd		= site_url('c_project/c_s180d0bpk/up4t_ibx1_er/?id='.$this->url_encryption_helper->encode_url($CollID));
				if($WO_CATEG == 'MDR')
					$secPrint	= site_url('c_project/c_s180d0bpk/pr1n7d0c_m4d/?id='.$this->url_encryption_helper->encode_url($WO_NUM));
				else
					$secPrint	= site_url('c_project/c_s180d0bpk/pr1n7d0c_e/?id='.$this->url_encryption_helper->encode_url($WO_NUM));

				$CollID		= "$WO_NUM~$PRJCODE";
				$secDel_WO 	= base_url().'index.php/c_project/c_s180d0bpk/trash_WO/?id='.$WO_NUM;

				if($WO_STAT == 1)
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

				$output['data'][] 	= array("<strong>$WO_CODE</strong>",
										  	"<div style='white-space:nowrap'>
											  	<strong><i class='fa fa-user margin-r-5'></i> ".$SupplierName." </strong>
										  		<div>
											  		<p class='text-muted' style='margin-left: 15px'>
											  			".$SPLDESC."
											  		</p>
											  	</div>
											  	<strong><i class='fa fa-cog margin-r-5'></i> ".$Category." </strong>
										  		<div>
											  		<p class='text-muted' style='margin-left: 15px'>
											  			".$WO_CATEGD."
											  		</p>
											  	</div>
										  	</div>",
										  	"<strong><i class='fa fa-calendar margin-r-5'></i> ".$Date." </strong>
									  		<div>
										  		<p class='text-muted' style='margin-left: 15px'>
										  			".$WO_DATEV."
										  		</p>
										  	</div>
										  	<strong><i class='fa fa-pencil margin-r-5'></i> ".$Description." </strong>
									  		<div class='text-muted' style='margin-left: 15px'>
										  			".$JOBDESCD."
										  	</div>",
										  $empName,
										  "<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
										  $secAction);
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

	function up4t_ibx1_er()
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_project/m_spk/m_spk', '', TRUE);
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;
		
		// GET MENU DESC
			$mnCode				= 'MN354';
			$MenuCode			= 'MN354';
			$data["MenuCode"] 	= 'MN354';
			$data["MenuApp"] 	= 'MN354';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
			
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

		if ($this->session->userdata('login') == TRUE)
		{
			$COLLDATA	= $_GET['id'];
			$COLLDATA	= $this->url_encryption_helper->decode_url($COLLDATA);
			$EXTRACTCOL	= explode("~", $COLLDATA);
			$PRJCODE	= $EXTRACTCOL[0];
			$WO_NUM		= $EXTRACTCOL[1];
			
			$docPatternPosition = 'Especially';
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_project/c_s180d0bpk/update_process_inb');

			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();

			$getwodata 						= $this->m_spk->get_WO_by_number($WO_NUM)->row();
			$data['default']['WO_NUM'] 		= $getwodata->WO_NUM;
			$data['default']['WO_CODE'] 	= $getwodata->WO_CODE;
			$data['default']['WO_DATE'] 	= $getwodata->WO_DATE;
			$data['default']['WO_STARTD'] 	= $getwodata->WO_STARTD;
			$data['default']['WO_ENDD'] 	= $getwodata->WO_ENDD;
			$data['default']['PRJCODE']		= $getwodata->PRJCODE;
			$data['PRJCODE']				= $getwodata->PRJCODE;
			$PRJCODE 						= $getwodata->PRJCODE;
			$data['default']['SPLCODE'] 	= $getwodata->SPLCODE;
			$data['default']['WO_DEPT'] 	= $getwodata->WO_DEPT;
			$data['default']['WO_CATEG'] 	= $getwodata->WO_CATEG;
			$WO_CATEG						= $getwodata->WO_CATEG;
			$data['default']['WO_TYPE'] 	= $getwodata->WO_TYPE;
			//$data['default']['WO_PAYTYPE'] 	= $getwodata->WO_PAYTYPE;
			$data['default']['JOBCODEID'] 	= $getwodata->JOBCODEID;
			$data['default']['WO_NOTE'] 	= $getwodata->WO_NOTE;
			$data['default']['WO_NOTE2'] 	= $getwodata->WO_NOTE2;
			$data['default']['WO_STAT'] 	= $getwodata->WO_STAT;
			$data['default']['WO_VALUE'] 	= $getwodata->WO_VALUE;
			$data['default']['WO_MEMO'] 	= $getwodata->WO_MEMO;
			$data['default']['WO_REFNO'] 	= $getwodata->WO_REFNO;
			$data['default']['PRJNAME'] 	= $getwodata->PRJNAME;
			$data['default']['FPA_NUM'] 	= $getwodata->FPA_NUM;
			$data['default']['FPA_CODE'] 	= $getwodata->FPA_CODE;
			$data['default']['WO_QUOT'] 	= $getwodata->WO_QUOT;
			$data['default']['WO_NEGO'] 	= $getwodata->WO_NEGO;
			$data['default']['WO_DPPER'] 	= $getwodata->WO_DPPER;
			$data['default']['WO_DPREF'] 	= $getwodata->WO_DPREF;
			$data['default']['WO_DPREF1'] 	= $getwodata->WO_DPREF1;
			$data['default']['WO_DPVAL'] 	= $getwodata->WO_DPVAL;
			$data['default']['Patt_Year'] 	= $getwodata->Patt_Year;
			$data['default']['Patt_Month'] 	= $getwodata->Patt_Month;
			$data['default']['Patt_Date'] 	= $getwodata->Patt_Date;
			$data['default']['Patt_Number']	= $getwodata->Patt_Number;

			$MenuCode 			= 'MN354';
			$data["MenuCode"] 	= 'MN354';

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getwodata->WO_NUM;
				$MenuCode 		= 'MN354';
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

			$this->load->view('v_project/v_spk/er_spk_inb_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

 	function r3q_t0ls_1a() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		$url			= site_url('c_project/c_s180d0bpk/r3q_70ls_1a/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}

	function r3q_70ls_1a() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_project/m_spk/m_spk', '', TRUE);

		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);

			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Permintaan Alat";
			}
			else
			{
				$data["h1_title"] 	= "Request - Tools";
			}
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN355';
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data["secURL"] 	= "c_project/c_s180d0bpk/r3q_70ls_1ll1a/?id=";

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

	function r3q_70ls_1ll1a() // OK
	{
		$this->load->model('m_project/m_spk/m_spk', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$EmpID 				= $this->session->userdata('Emp_ID');

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
				$data["url_search"] = site_url('c_project/c_s180d0bpk/f4n7_5rcH/?id='.$this->url_encryption_helper->encode_url($PRJCODE));

				//$num_rows 			= $this->m_spk->count_all_WOTLS($PRJCODE, $key);
				//$data["cData"] 		= $num_rows;
				//$data['vData']		= $this->m_spk->get_all_WOTLS($PRJCODE, $start, $end, $key)->result();
			// -------------------- END : SEARCHING METHOD --------------------

			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Permintaan Alat";
			}
			else
			{
				$data["h1_title"] 	= "Request - Tools";
			}

			$data['title'] 		= $appName;
			$data['addURL'] 	= site_url('c_project/c_s180d0bpk/r3q_70ls_1dd1a/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['backURL'] 	= site_url('c_project/c_s180d0bpk/r3q_70ls_1a/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
			$MenuCode 			= 'MN355';
			$data["MenuCode"] 	= 'MN355';

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN355';
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

			$this->load->view('v_project/v_spk/spk_req_list', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	// -------------------- START : FUNCTION TO SEARCH ENGINE --------------------
		function f4n7_5rcH()
		{
			$gEn5rcH		= $_POST['gEn5rcH'];
			$mxLS			= $_POST['maxLimDf'];
			$mxLF			= $_POST['maxLimit'];
			$PRJCODE		= $_GET['id'];
			$PRJCODE		= $this->url_encryption_helper->decode_url($PRJCODE);
			$collDATA		= "$gEn5rcH~$PRJCODE~$mxLS~$mxLF";
			$url			= site_url('c_project/c_s180d0bpk/r3q_70ls_1ll1a/?id='.$this->url_encryption_helper->encode_url($collDATA));
			redirect($url);
		}
	// -------------------- END : FUNCTION TO SEARCH ENGINE --------------------

  	function get_AllDataRT() // GOOD
	{
		$PRJCODE		= $_GET['id'];

		// START : FOR SERVER-SIDE
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_spk->get_AllDataCRT($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_spk->get_AllDataLRT($PRJCODE, $search, $length,$start);

			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI)
			{
				$WO_NUM		= $dataI['WO_NUM'];
				$WO_CODE	= $dataI['WO_CODE'];

				$WO_DATE	= $dataI['WO_DATE'];
				$WO_DATEV	= date('d M Y', strtotime($WO_DATE));

				$WO_NOTE	= $dataI['WO_NOTE'];
				$WO_NOTED	= '';
				if($WO_NOTE != '')
					$WO_NOTED	= " : $WO_NOTE";

				$WO_STAT	= $dataI['WO_STAT'];
				$WO_REFNO	= $dataI['WO_REFNO'];
				$STATDESC	= $dataI['STATDESC'];
				$STATCOL	= $dataI['STATCOL'];
				$CREATERNM	= $dataI['CREATERNM'];
				$empName	= cut_text2 ("$CREATERNM", 15);
				$JOBCODEID	= $dataI['JOBCODEID'];

				// CARI TOTAL REGUSEST BUDGET APPROVED
					$JOBDESC		= '';
					$sqlJOBDESC		= "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JOBCODEID' LIMIT 1";
					$resJOBDESC		= $this->db->query($sqlJOBDESC)->result();
					foreach($resJOBDESC as $rowJOBDESC) :
						$JOBDESC	= $rowJOBDESC->JOBDESC;
					endforeach;
				
				$JOBDESCD		= "$JOBDESC$WO_NOTED";
				if($JOBDESCD == '')
				{
					$expJOB		= explode("~", $JOBCODEID);
					$JOBCODEID1	= $expJOB[0];
					$JOBDESC1	= '';
					$sqlJOB		= "SELECT JOBDESC FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBCODEID1'";
					$resJOB		= $this->db->query($sqlJOB)->result();
					foreach($resJOB as $rowJOB) :
						$JOBDESC1 = $rowJOB->JOBDESC;		
					endforeach;
					$JOBDESCD	= $JOBDESC1;				
				}

				$CollID		= "$PRJCODE~$WO_NUM";
				$secUpd		= site_url('c_project/c_s180d0bpk/r3q_70ls_up1a/?id='.$this->url_encryption_helper->encode_url($CollID));
				$secPrint	= site_url('c_project/c_s180d0bpk/r3q_70ls_pr1n7/?id='.$this->url_encryption_helper->encode_url($WO_NUM));
				$CollID		= "$WO_NUM~$PRJCODE";
				$secDel_WO 	= base_url().'index.php/c_project/c_s180d0bpk/trash_WO/?id='.$WO_NUM;

				if($WO_STAT == 1)
				{
					$secAction	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
							   <label style='white-space:nowrap'>
							   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
									<i class='glyphicon glyphicon-pencil'></i>
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
							   <a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printDocument(".$noU.")'>
							   		<i class='glyphicon glyphicon-print'></i>
							   </a>
							   </label>";
				}

				$output['data'][] = array("<label style='white-space:nowrap'>".$dataI['WO_CODE']."</label>",
										  $WO_DATEV,
										  "$JOBDESC$WO_NOTED",
										  $empName,
										  "<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
										  $secAction);
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

	function r3q_70ls_1dd1a() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_project/m_spk/m_spk', '', TRUE);

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
			$data['form_action']= site_url('c_project/c_s180d0bpk/add_req_process');
			$data['backURL'] 	= site_url('c_project/c_s180d0bpk/get_all_PR/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;

			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();

			$MenuCode 			= 'MN355';
			$data["MenuCode"] 	= 'MN355';
			$data["MenuCode1"] 	= 'MN356';
			$data['vwDocPatt'] 	= $this->m_spk->getDataDocPat($MenuCode)->result();

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN355';
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

			$this->load->view('v_project/v_spk/spk_req_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function add_req_process() // OK
	{
		$this->load->model('m_project/m_spk/m_spk', '', TRUE);
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

			$WO_STAT 		= $this->input->post('WO_STAT'); // 1 = New, 2 = confirm, 3 = Close

			$WO_NUM 		= $this->input->post('WO_NUM');
			$WO_CODE 		= $this->input->post('WO_CODE');
			//setting WO Date
			$WO_DATE		= date('Y-m-d',strtotime($this->input->post('WO_DATE')));
			$WO_STARTD		= date('Y-m-d',strtotime($this->input->post('WO_STARTD')));
				$Patt_Year	= date('Y',strtotime($this->input->post('WO_DATE')));
				$Patt_Month	= date('m',strtotime($this->input->post('WO_DATE')));
				$Patt_Date	= date('d',strtotime($this->input->post('WO_DATE')));
			$WO_ENDD		= date('Y-m-d',strtotime($this->input->post('WO_ENDD')));
			$PRJCODE 		= $this->input->post('PRJCODE');
			$SPLCODE 		= $this->input->post('SPLCODE');
			$WO_DEPT 		= '';
			$WO_CATEG 		= $this->input->post('WO_CATEG');
			$WO_TYPE 		= $this->input->post('WO_TYPE');
			$WO_NOTE 		= addslashes($this->input->post('WO_NOTE'));
			$WO_STAT 		= $this->input->post('WO_STAT');

			//$JOBCODEID	= $this->input->post('JOBCODEID');
			$PRREFNO		= $this->input->post('PR_REFNO');
			$WO_VALUE		= $this->input->post('WO_VALUE');
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
				$MenuCode 		= 'MN355';
				$vwDocPatt		= $this->m_projectlist->getDataDocPat($MenuCode)->result();
				foreach($vwDocPatt as $row) :
					$Pattern_Code = $row->Pattern_Code;
				endforeach;

				$PRJCODE 		= $this->input->post('PRJCODE');
				$TRXTIME1		= date('ymdHis');
				$WO_NUM			= "$Pattern_Code$PRJCODE-$TRXTIME1";
			// END - PEMBENTUKAN GENERATE CODE

			$projWOH 		= array('WO_NUM' 		=> $WO_NUM,
									'WO_CODE' 		=> $WO_CODE,
									'WO_DATE'		=> $WO_DATE,
									'WO_STARTD'		=> $WO_STARTD,
									'WO_ENDD'		=> $WO_ENDD,
									'PRJCODE'		=> $PRJCODE,
									'SPLCODE'		=> $SPLCODE,
									'WO_DEPT'		=> $WO_DEPT,
									'WO_CATEG'		=> $WO_CATEG,
									'WO_TYPE'		=> $WO_TYPE,
									'JOBCODEID'		=> $JOBCODEID,
									'WO_NOTE'		=> $WO_NOTE,
									'WO_VALUE'		=> $WO_VALUE,
									'WO_STAT'		=> $WO_STAT,
									'WO_CREATER'	=> $DefEmp_ID,
									'WO_CREATED'	=> date('Y-m-d H:i:s'),
									'Patt_Year'		=> $Patt_Year,
									'Patt_Month'	=> $Patt_Month,
									'Patt_Date'		=> $Patt_Date,
									'Patt_Number'	=> $this->input->post('Patt_Number'));
			$this->m_spk->addWOTLS($projWOH);

			foreach($_POST['data'] as $d)
			{
				$d['WO_NUM']	= $WO_NUM;
				$WO_TOTAL2	= $d['WO_TOTAL2'];
				if($WO_TOTAL2 == '')
					$d['WO_TOTAL2']	= 0;

				$this->db->insert('tbl_woreq_detail',$d);
			}

			// UPDATE DETAIL
				$this->m_spk->updateDetWOTLS($WO_NUM, $PRJCODE, $WO_DATE);

			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);

				$STAT_BEFORE	= $this->input->post('WO_STAT');			// IF "ADD" CONDITION ALWAYS = PR_STAT
				$parameters 	= array('DOC_CODE' 		=> $WO_NUM,			// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "WO",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_woreq_header",	// TABLE NAME
										'KEY_NAME'		=> "WO_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "WO_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $WO_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_WO",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_WO_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_WO_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_WO_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_WO_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_WO_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_WO_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
				//$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT

			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "WO_NUM",
										'DOC_CODE' 		=> $WO_NUM,
										'DOC_STAT' 		=> $WO_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_woreq_header");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $WO_NUM;
				$MenuCode 		= 'MN355';
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

			$url			= site_url('c_project/c_s180d0bpk/r3q_70ls_1ll1a/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function r3q_70ls_up1a() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_project/m_spk/m_spk', '', TRUE);
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

		$WO_NUM		= $_GET['id'];
		$WO_NUM		= $this->url_encryption_helper->decode_url($WO_NUM);

		if ($this->session->userdata('login') == TRUE)
		{
			$COLLDATA	= $_GET['id'];
			$COLLDATA	= $this->url_encryption_helper->decode_url($COLLDATA);
			$EXTRACTCOL	= explode("~", $COLLDATA);
			$PRJCODE	= $EXTRACTCOL[0];
			$WO_NUM		= $EXTRACTCOL[1];
			
			$docPatternPosition = 'Especially';
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_project/c_s180d0bpk/update_req_process');

			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();

			$getwodata 						= $this->m_spk->get_WOTLS_by_number($WO_NUM)->row();
			$data['default']['WO_NUM'] 		= $getwodata->WO_NUM;
			$data['default']['WO_CODE'] 	= $getwodata->WO_CODE;
			$data['default']['WO_DATE'] 	= $getwodata->WO_DATE;
			$data['default']['WO_STARTD'] 	= $getwodata->WO_STARTD;
			$data['default']['WO_ENDD'] 	= $getwodata->WO_ENDD;
			$data['default']['PRJCODE']		= $getwodata->PRJCODE;
			$data['PRJCODE']				= $getwodata->PRJCODE;
			$PRJCODE 						= $getwodata->PRJCODE;
			$data['default']['SPLCODE'] 	= $getwodata->SPLCODE;
			$data['default']['WO_DEPT'] 	= $getwodata->WO_DEPT;
			$data['default']['WO_CATEG'] 	= $getwodata->WO_CATEG;
			$data['default']['WO_TYPE'] 	= $getwodata->WO_TYPE;
			$data['default']['JOBCODEID'] 	= $getwodata->JOBCODEID;
			$data['default']['WO_NOTE'] 	= $getwodata->WO_NOTE;
			$data['default']['WO_NOTE2'] 	= $getwodata->WO_NOTE2;
			$data['default']['WO_STAT'] 	= $getwodata->WO_STAT;
			$data['default']['WO_VALUE'] 	= $getwodata->WO_VALUE;
			$data['default']['WO_MEMO'] 	= $getwodata->WO_MEMO;
			$data['default']['PRJNAME'] 	= $getwodata->PRJNAME;
			$data['default']['Patt_Year'] 	= $getwodata->Patt_Year;
			$data['default']['Patt_Month'] 	= $getwodata->Patt_Month;
			$data['default']['Patt_Date'] 	= $getwodata->Patt_Date;
			$data['default']['Patt_Number']	= $getwodata->Patt_Number;

			$MenuCode 			= 'MN355';
			$data["MenuCode"] 	= 'MN355';
			$data["MenuCode1"] 	= 'MN356';
			$data['vwDocPatt'] 	= $this->m_spk->getDataDocPat($MenuCode)->result();

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getwodata->WO_NUM;
				$MenuCode 		= 'MN355';
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

			$this->load->view('v_project/v_spk/spk_req_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function update_req_process() // OK
	{
		$this->load->model('m_project/m_spk/m_spk', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();

			date_default_timezone_set("Asia/Jakarta");

			$WO_STAT 		= $this->input->post('WO_STAT'); // 1 = New, 2 = confirm, 3 = Close

			$WO_NUM 		= $this->input->post('WO_NUM');
			$WO_CODE 		= $this->input->post('WO_CODE');
			//setting WO Date
			$WO_DATE		= date('Y-m-d',strtotime($this->input->post('WO_DATE')));
			$WO_STARTD		= date('Y-m-d',strtotime($this->input->post('WO_DATE')));
				$Patt_Year	= date('Y',strtotime($this->input->post('WO_DATE')));
				$Patt_Month	= date('m',strtotime($this->input->post('WO_DATE')));
				$Patt_Date	= date('d',strtotime($this->input->post('WO_DATE')));
			$WO_ENDD		= date('Y-m-d',strtotime($this->input->post('WO_ENDD')));
			$PRJCODE 		= $this->input->post('PRJCODE');
			$SPLCODE 		= $this->input->post('SPLCODE');
			$WO_DEPT 		= '';
			$WO_CATEG 		= $this->input->post('WO_CATEG');
			$WO_TYPE 		= $this->input->post('WO_TYPE');
			$WO_NOTE 		= addslashes($this->input->post('WO_NOTE'));
			$WO_STAT 		= $this->input->post('WO_STAT');
			//$JOBCODEID	= $this->input->post('JOBCODEID');
			$PRREFNO		= $this->input->post('PR_REFNO');
			$WO_VALUE		= $this->input->post('WO_VALUE');
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
						$COLPR_REFNO	= "$COLPR_REFNO$PR_REFNO";
					}
				}
			}
			else
			{
				$COLPR_REFNO	= '';
			}
			$JOBCODEID		= $COLPR_REFNO;

			$projWOH 		= array('WO_NUM' 		=> $WO_NUM,
									'WO_CODE' 		=> $WO_CODE,
									'WO_DATE'		=> $WO_DATE,
									'WO_STARTD'		=> $WO_STARTD,
									'WO_ENDD'		=> $WO_ENDD,
									'PRJCODE'		=> $PRJCODE,
									'SPLCODE'		=> $SPLCODE,
									'WO_DEPT'		=> $WO_DEPT,
									'WO_CATEG'		=> $WO_CATEG,
									'WO_TYPE'		=> $WO_TYPE,
									'WO_VALUE'		=> $WO_VALUE,
									'JOBCODEID'		=> $JOBCODEID,
									'WO_NOTE'		=> $WO_NOTE,
									'WO_STAT'		=> $WO_STAT,
									'Patt_Year'		=> $Patt_Year,
									'Patt_Month'	=> $Patt_Month,
									'Patt_Date'		=> $Patt_Date,
									'Patt_Number'	=> $this->input->post('Patt_Number'));
			$this->m_spk->updateWOTLS($WO_NUM, $projWOH);

			$this->m_spk->deleteDetailWOTLS($WO_NUM);

			foreach($_POST['data'] as $d)
			{
				$WO_TOTAL2	= $d['WO_TOTAL2'];
				if($WO_TOTAL2 == '')
					$d['WO_TOTAL2']	= 0;

				$this->db->insert('tbl_woreq_detail',$d);
			}

			// UPDATE DETAIL
				$this->m_spk->updateDetWOTLS($WO_NUM, $PRJCODE, $WO_DATE);

			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);

				$STAT_BEFORE	= $this->input->post('STAT_BEFORE');		// IF "ADD" CONDITION ALWAYS = PR_STAT
				$parameters 	= array('DOC_CODE' 		=> $WO_NUM,			// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "WO",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_wo_header",	// TABLE NAME
										'KEY_NAME'		=> "WO_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "WO_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $WO_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_WO",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_WO_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_WO_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_WO_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_WO_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_WO_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_WO_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
				//$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT

			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "WO_NUM",
										'DOC_CODE' 		=> $WO_NUM,
										'DOC_STAT' 		=> $WO_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_woreq_header");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $WO_NUM;
				$MenuCode 		= 'MN238';
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

			$url			= site_url('c_project/c_s180d0bpk/r3q_70ls_1ll1a/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}

 	function r3q_70ls_1nb() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		$url			= site_url('c_project/c_s180d0bpk/pR7_l5t_x1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}

	function pR7_l5t_x1() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_purchase/m_purchase_req/m_purchase_req', '', TRUE);

		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);

			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Persetujuan Permintaan";
			}
			else
			{
				$data["h1_title"] 	= "Request Approval";
			}

			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN356';
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data["secURL"] 	= "c_project/c_s180d0bpk/r3q_70ls_1nb_a/?id=";

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

	function r3q_70ls_1nb_a() // OK
	{
		$this->load->model('m_project/m_spk/m_spk', '', TRUE);

		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName 	= $therow->app_name;
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
				$data["url_search"] = site_url('c_project/c_s180d0bpk/f4n7_5rcH1nB/?id='.$this->url_encryption_helper->encode_url($PRJCODE));

				//$num_rows 			= $this->m_spk->count_all_WOTLSInx_er($PRJCODE, $key, $DefEmp_ID);
				//$data["cData"] 		= $num_rows;
				//$data['vData']		= $this->m_spk->get_all_WOTLSInb_er($PRJCODE, $start, $end, $key, $DefEmp_ID)->result();
			// -------------------- END : SEARCHING METHOD --------------------

			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Permintaan Alat";
				$data["h2_title"] 	= "Persetujuan";
			}
			else
			{
				$data["h1_title"] 	= "Request - Tools";
				$data["h2_title"] 	= "Approval";
			}

			$data['title'] 		= $appName;
			$data['backURL'] 	= site_url('c_project/c_s180d0bpk/inbox_er/');
			$data['PRJCODE'] 	= $PRJCODE;
			$data['MenuCode'] 		= 'MN356';

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN356';
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

			$this->load->view('v_project/v_spk/spk_req_inb', $data);
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
			$url			= site_url('c_project/c_s180d0bpk/r3q_70ls_1nb_a/?id='.$this->url_encryption_helper->encode_url($collDATA));
			redirect($url);
		}
	// -------------------- END : SEARCHING METHOD --------------------

  	function get_AllDataRT_1n2() // GOOD
	{
		$PRJCODE		= $_GET['id'];

		// START : FOR SERVER-SIDE
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_spk->get_AllDataCRT_1n2($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_spk->get_AllDataLRT_1n2($PRJCODE, $search, $length,$start);

			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI)
			{
				$WO_NUM		= $dataI['WO_NUM'];
				$WO_CODE	= $dataI['WO_CODE'];

				$WO_DATE	= $dataI['WO_DATE'];
				$WO_DATEV	= date('d M Y', strtotime($WO_DATE));

				$WO_NOTE	= $dataI['WO_NOTE'];
				$WO_NOTED	= '';
				if($WO_NOTE != '')
					$WO_NOTED	= " : $WO_NOTE";

				$WO_STAT	= $dataI['WO_STAT'];
				$WO_REFNO	= $dataI['WO_REFNO'];
				$STATDESC	= $dataI['STATDESC'];
				$STATCOL	= $dataI['STATCOL'];
				$CREATERNM	= $dataI['CREATERNM'];
				$empName	= cut_text2 ("$CREATERNM", 15);
				$JOBCODEID	= $dataI['JOBCODEID'];

				// CARI TOTAL REGUSEST BUDGET APPROVED
					$JOBDESC		= '';
					$sqlJOBDESC		= "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JOBCODEID' LIMIT 1";
					$resJOBDESC		= $this->db->query($sqlJOBDESC)->result();
					foreach($resJOBDESC as $rowJOBDESC) :
						$JOBDESC	= $rowJOBDESC->JOBDESC;
					endforeach;
				
				$JOBDESCD		= "$JOBDESC$WO_NOTED";
				if($JOBDESCD == '')
				{
					$expJOB		= explode("~", $JOBCODEID);
					$JOBCODEID1	= $expJOB[0];
					$JOBDESC1	= '';
					$sqlJOB		= "SELECT JOBDESC FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBCODEID1'";
					$resJOB		= $this->db->query($sqlJOB)->result();
					foreach($resJOB as $rowJOB) :
						$JOBDESC1 = $rowJOB->JOBDESC;		
					endforeach;
					$JOBDESCD	= $JOBDESC1;				
				}

				$CollID		= "$PRJCODE~$WO_NUM";
				$secUpd		= site_url('c_project/c_s180d0bpk/r3q_70ls_1nbup4t/?id='.$this->url_encryption_helper->encode_url($CollID));
				$secPrint	= site_url('c_project/c_s180d0bpk/r3q_70ls_pr1n7/?id='.$this->url_encryption_helper->encode_url($WO_NUM));
				$CollID		= "$WO_NUM~$PRJCODE";
				$secDel_PO 	= base_url().'index.php/c_project/c_s180d0bpk/trash_PO/?id='.$WO_NUM;

				$secAction	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
							   <label style='white-space:nowrap'>
							   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
									<i class='glyphicon glyphicon-pencil'></i>
							   </a>
							   <a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printDocument(".$noU.")'>
							   		<i class='glyphicon glyphicon-print'></i>
							   </a>
							   </label>";

				$output['data'][] = array($dataI['WO_CODE'],
										  $WO_DATEV,
										  "$JOBDESC$WO_NOTED",
										  $empName,
										  "<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
										  $secAction);
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

	function r3q_70ls_1nbup4t()
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_project/m_spk/m_spk', '', TRUE);
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

		if ($this->session->userdata('login') == TRUE)
		{
			$COLLDATA	= $_GET['id'];
			$COLLDATA	= $this->url_encryption_helper->decode_url($COLLDATA);
			$EXTRACTCOL	= explode("~", $COLLDATA);
			$PRJCODE	= $EXTRACTCOL[0];
			$WO_NUM		= $EXTRACTCOL[1];
			
			$docPatternPosition = 'Especially';
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_project/c_s180d0bpk/update_req_process_inb');

			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();

			$getwodata 						= $this->m_spk->get_WOTLS_by_number($WO_NUM)->row();
			$data['default']['WO_NUM'] 		= $getwodata->WO_NUM;
			$data['default']['WO_CODE'] 	= $getwodata->WO_CODE;
			$data['default']['WO_DATE'] 	= $getwodata->WO_DATE;
			$data['default']['WO_STARTD'] 	= $getwodata->WO_STARTD;
			$data['default']['WO_ENDD'] 	= $getwodata->WO_ENDD;
			$data['default']['PRJCODE']		= $getwodata->PRJCODE;
			$data['PRJCODE']				= $getwodata->PRJCODE;
			$PRJCODE 						= $getwodata->PRJCODE;
			$data['default']['SPLCODE'] 	= $getwodata->SPLCODE;
			$data['default']['WO_DEPT'] 	= $getwodata->WO_DEPT;
			$data['default']['WO_CATEG'] 	= $getwodata->WO_CATEG;
			$data['default']['WO_TYPE'] 	= $getwodata->WO_TYPE;
			$data['default']['JOBCODEID'] 	= $getwodata->JOBCODEID;
			$data['default']['WO_NOTE'] 	= $getwodata->WO_NOTE;
			$data['default']['WO_NOTE2'] 	= $getwodata->WO_NOTE2;
			$data['default']['WO_STAT'] 	= $getwodata->WO_STAT;
			$data['default']['WO_VALUE'] 	= $getwodata->WO_VALUE;
			$data['default']['WO_MEMO'] 	= $getwodata->WO_MEMO;
			$data['default']['PRJNAME'] 	= $getwodata->PRJNAME;
			$data['default']['Patt_Year'] 	= $getwodata->Patt_Year;
			$data['default']['Patt_Month'] 	= $getwodata->Patt_Month;
			$data['default']['Patt_Date'] 	= $getwodata->Patt_Date;
			$data['default']['Patt_Number']	= $getwodata->Patt_Number;

			$MenuCode 			= 'MN356';
			$data["MenuCode"] 	= 'MN356';

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getwodata->WO_NUM;
				$MenuCode 		= 'MN356';
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

			$this->load->view('v_project/v_spk/spk_req_inb_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function update_req_process_inb() // OK
	{
		$this->load->model('m_project/m_spk/m_spk', '', TRUE);
    $this->load->model('m_updash/m_updash', '', TRUE);

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

			$WO_NUM 		= $this->input->post('WO_NUM');
			$PRJCODE 		= $this->input->post('PRJCODE');
			$WO_STAT 		= $this->input->post('WO_STAT');
			
			$AH_CODE		= $WO_NUM;
  			$AH_APPLEV		= $this->input->post('APP_LEVEL');
  			$AH_APPROVER	= $DefEmp_ID;
  			$AH_APPROVED	= date('Y-m-d H:i:s');
  			$AH_NOTES		= addslashes($this->input->post('WO_NOTE2'));
  			$AH_ISLAST		= $this->input->post('IS_LAST');

			if($WO_STAT == 3)
			{
        // START : SAVE APPROVE HISTORY

  				$AH_CODE		= $WO_NUM;
  				$AH_APPLEV		= $this->input->post('APP_LEVEL');
  				$AH_APPROVER	= $DefEmp_ID;
  				$AH_APPROVED	= date('Y-m-d H:i:s');
  				$AH_NOTES		= $this->input->post('WO_NOTE2');
  				$AH_ISLAST		= $this->input->post('IS_LAST');

  				$insAppHist 	= array('AH_CODE'		=> $AH_CODE,
  										'AH_APPLEV'		=> $AH_APPLEV,
  										'AH_APPROVER'	=> $AH_APPROVER,
  										'AH_APPROVED'	=> $AH_APPROVED,
  										'AH_NOTES'		=> $AH_NOTES,
  										'AH_ISLAST'		=> $AH_ISLAST);
  				$this->m_updash->insAppHist($insAppHist);

  			// END : SAVE APPROVE HISTORY

				$projWOH		= array('WO_STAT'	=> 7);		// Default ke waiting jika masih ada approver yang lain
				$this->m_spk->updateWOTLS($WO_NUM, $projWOH);
			}
			else
			{
				$projWOH		= array('WO_STAT'	=> $WO_STAT);		// Default ke waiting jika masih ada approver yang lain
				$this->m_spk->updateWOTLS($WO_NUM, $projWOH);
			}

			if($WO_STAT == 3 && $AH_ISLAST == 1)
			{
				$projWOH 		= array('WO_APPROVER'	=> $DefEmp_ID,
										'WO_APPROVED'	=> date('Y-m-d H:i:s'),
										'WO_NOTE2'		=> addslashes($this->input->post('WO_NOTE2')),
										'WO_STAT'		=> $WO_STAT);
				$this->m_spk->updateWOTLS($WO_NUM, $projWOH);

				// UPDATE JOBDETAIL ITEM
				// if($WO_STAT == 3)
				// {
				// 	//$this->m_spk->updateWOTLSDet($WO_NUM, $PRJCODE); hidden by DIAN on Aug/7/18
				// }

				// START : UPDATE TO TRANS-COUNT
					$this->load->model('m_updash/m_updash', '', TRUE);

					$STAT_BEFORE	= $this->input->post('STAT_BEFORE');		// IF "ADD" CONDITION ALWAYS = PR_STAT
					$parameters 	= array('DOC_CODE' 		=> $WO_NUM,			// TRANSACTION CODE
											'PRJCODE' 		=> $PRJCODE,		// PROJECT
											'TR_TYPE'		=> "WO",			// TRANSACTION TYPE
											'TBL_NAME' 		=> "tbl_woreq_header",	// TABLE NAME
											'KEY_NAME'		=> "WO_NUM",		// KEY OF THE TABLE
											'STAT_NAME' 	=> "WO_STAT",		// NAMA FIELD STATUS
											'STATDOC' 		=> $WO_STAT,		// TRANSACTION STATUS
											'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
											'FIELD_NM_ALL'	=> "TOT_WO",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
											'FIELD_NM_N'	=> "TOT_WO_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
											'FIELD_NM_C'	=> "TOT_WO_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
											'FIELD_NM_A'	=> "TOT_WO_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
											'FIELD_NM_R'	=> "TOT_WO_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
											'FIELD_NM_RJ'	=> "TOT_WO_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_data
											'FIELD_NM_CL'	=> "TOT_WO_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
					//$this->m_updash->updateDashData($parameters);
				// END : UPDATE TO TRANS-COUNT

				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "WO_NUM",
											'DOC_CODE' 		=> $WO_NUM,
											'DOC_STAT' 		=> $WO_STAT,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> '',
											'TBLNAME'		=> "tbl_woreq_header");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
			}
			else
			{
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "WO_NUM",
											'DOC_CODE' 		=> $WO_NUM,
											'DOC_STAT' 		=> 7,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> $completeName,
											'TBLNAME'		=> "tbl_woreq_header");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
			}

			// START : CLEAR HISTORY
				$this->load->model('m_updash/m_updash', '', TRUE);
				if($WO_STAT == 4)
				{
					$cllPar = array('AH_CODE' 		=> $WO_NUM,
									'AH_APPROVER'	=> $DefEmp_ID);
					$this->m_updash->clearHist($cllPar);

					// START : UPDATE STATUS
						$completeName 	= $this->session->userdata['completeName'];
						$paramStat 		= array('PM_KEY' 		=> "WO_NUM",
												'DOC_CODE' 		=> $WO_NUM,
												'DOC_STAT' 		=> $WO_STAT,
												'PRJCODE' 		=> $PRJCODE,
												'CREATERNM'		=> $completeName,
												'TBLNAME'		=> "tbl_woreq_header");
						$this->m_updash->updateStatus($paramStat);
					// END : UPDATE STATUS
				}
			// END : CLEAR HISTORY

      if($WO_STAT == 5)
      {
        $cllPar = array('AH_CODE' 		=> $WO_NUM,
                'AH_APPROVER'	=> $DefEmp_ID);

        // START : UPDATE STATUS
          $completeName 	= $this->session->userdata['completeName'];
          $paramStat 		= array('PM_KEY' 		=> "WO_NUM",
                      'DOC_CODE' 		=> $WO_NUM,
                      'DOC_STAT' 		=> $WO_STAT,
                      'PRJCODE' 		=> $PRJCODE,
                      'CREATERNM'		=> $completeName,
                      'TBLNAME'		=> "tbl_woreq_header");
          $this->m_updash->updateStatus($paramStat);
        // END : UPDATE STATUS
      }

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $WO_NUM;
				$MenuCode 		= 'MN239';
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

			$url			= site_url('c_project/c_s180d0bpk/r3q_70ls_1nb_a/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function ll_4p() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE	= $_GET['id'];
			$SPLCODE	= $_GET['SPLCODE'];
			$collData	= "$PRJCODE~$SPLCODE";

			$url		= site_url('c_project/c_s180d0bpk/ll_4p1/?id='.$this->url_encryption_helper->encode_url($collData));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function ll_4p1() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE				= $_GET['id'];
			$collData				= $this->url_encryption_helper->decode_url($PRJCODE);
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];

			$DATExplode 			= explode('~', $collData);
			$PRJCODE				= $DATExplode[0];
			$SPLCODE				= $DATExplode[1];

			$data['title'] 			= $appName;
			$data['h2_title'] 		= 'Select DP Number';
			$data['txtRefference'] 	= '';
			$data['resultCount']	= 0;
			$data['pageFrom']		= 'SPK';
			$data['PRJCODE']		= $PRJCODE;
			$data['SPLCODE']		= $SPLCODE;

			$this->load->view('v_project/v_spk/spk_seldp', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

  	function get_AllDataSRV() // GOOD
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
			$num_rows 		= $this->m_spk->get_AllDataITMC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			
			$query 			= $this->m_spk->get_AllDataITML($PRJCODE, $search, $length, $start, $order, $dir);
								
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
				$disabledB		= 0;
				$JOBCODEDET 	= $dataI['JOBCODEDET'];				// 0
				$JOBCODEID 		= $dataI['JOBCODEID'];				// 1
				$JOBCODE 		= $dataI['JOBCODE'];				// 2
				$ITM_CODE 		= $dataI['ITM_CODE'];				// 4
				$JOBDESC		= $dataI['JOBDESC'];				// 5
				$serialNumber	= '';								// 6
				$ITM_UNIT 		= $dataI['ITM_UNIT'];				// 7
				$UNITTYPE		= strtoupper($ITM_UNIT);			// 
				$ITM_PRICE		= $dataI['ITM_PRICE'];				// 8
				$ITM_VOLM 		= $dataI['ITM_VOLM'];				//
				$ADD_VOLM		= $dataI['ADD_VOLM'];				// 
				$ITM_VOLM_QTY	= $ITM_VOLM + $ADD_VOLM;			// 9
				if($ITM_VOLM_QTY == '')
					$ITM_VOLM_QTY	= 0;
				$ITM_BUDG 		= $dataI['ITM_BUDG'];				//
				$ADD_JOBCOST	= $dataI['ADD_JOBCOST'];			//
				$ITM_VOLM_AMN	= $ITM_BUDG + $ADD_JOBCOST;			// 10
				if($ITM_VOLM_AMN == '')
					$ITM_VOLM_AMN	= 0;
				$ITM_STOCK 		= $dataI['ITM_STOCK'];				//
				$ITM_STOCK_AM 	= $dataI['ITM_STOCK_AM'];			//
				$ITM_USED 		= $dataI['ITM_USED'];				// 11
				$ITM_USED_AM 	= $dataI['ITM_USED_AM'];			// 12
				$tempTotMax		= $ITM_VOLM - $ITM_USED;			// 13
				
				if($UNITTYPE == 'LS')
					$tempTotMax	= $ITM_BUDG - $ITM_USED_AM;			// 13

				$PO_VOLM 		= $dataI['PO_VOLM'];				// 14
				$PO_AMOUNT		= $dataI['PO_AMOUNT'];				// 15
				$WO_QTY			= $dataI['WO_QTY'];					// 16
				$WO_AMOUNT		= $dataI['WO_AMOUNT'];				// 17
				$OPN_QTY		= $dataI['OPN_QTY'];				// 18
				$OPN_AMOUNT		= $dataI['OPN_AMOUNT'];				// 19

				// GET WO TOTAL IN (2,3)
					$WO_QTY2		= 0;
					$WO_AMOUNT2		= 0;
					$sqlTOTBUDG		= "SELECT SUM(A.WO_VOLM) AS TOTWOQTY,
											SUM(A.WO_VOLM * A.ITM_PRICE) AS TOTWOAMOUNT
										FROM tbl_wo_detail A
											INNER JOIN tbl_wo_header B ON A.WO_NUM = B.WO_NUM
												AND B.PRJCODE = '$PRJCODE'
										WHERE A.ITM_CODE = '$ITM_CODE' AND A.PRJCODE = '$PRJCODE'
											AND A.JOBCODEID = '$JOBCODEID' AND B.WO_STAT IN (2)";
					$resTOTBUDG		= $this->db->query($sqlTOTBUDG)->result();
					foreach($resTOTBUDG as $rowTOTBUDG) :
						$WO_QTY2	= $rowTOTBUDG->TOTWOQTY;
						$WO_AMOUNT2	= $rowTOTBUDG->TOTWOAMOUNT;
					endforeach;
					$TOT_USED_QTY 	= $WO_QTY + $WO_QTY2;			// 20
					$TOT_USED_AMN 	= $WO_AMOUNT + $WO_AMOUNT2;		// 21

					$REMREQ_QTY		= $ITM_VOLM_QTY - $TOT_USED_QTY;
					$REMREQ_AMN		= $ITM_VOLM_AMN - $TOT_USED_AMN;
					$ITM_STOCK 		= $REMREQ_QTY;					// 22
					$ITM_STOCK_AM 	= $REMREQ_AMN;					// 23
					
					if($UNITTYPE == 'LS')
					{
						$REMREQ_QTY 	= 0;
						if($REMREQ_AMN <= 0)
							$disabledB	= 1;
					}
					else
					{
						if($REMREQ_QTY <= 0)
							$disabledB	= 1;
						if($REMREQ_AMN <= 0)
							$disabledB	= 1;
					}

					$ITM_REMVOL		= $REMREQ_QTY;
					$ITM_REMAMN		= $REMREQ_AMN;

				$ITM_GROUP		= $dataI['ITM_GROUP'];
				if($ITM_GROUP == 'M')
					$disabledB	= 1;

				$itemConvertion	= 1;
				$REQ_VOLM 		= $dataI['REQ_VOLM'];
				$REQ_AMOUNT		= $dataI['REQ_AMOUNT'];
				if($UNITTYPE == 'LS')
				{
					$ITM_BUDGV	= $ITM_VOLM_AMN;
					$REQ_VOLMV	= $REQ_AMOUNT;
					$TOTWOQTY_V = "";
					if($WO_AMOUNT2 > 0)
					{
						$TOTWOQTY_V = 	"<div>
											<p class='text-yellow' style='white-space:nowrap'><i class='fa fa-exclamation-triangle'></i>
									  		".number_format($WO_AMOUNT2, 2)."</p>
									  	</div>";
					}
					$PO_VOLMV	= $PO_AMOUNT;
					$ITM_STOCKV	= $REMREQ_AMN;
				}
				else
				{
					$ITM_BUDGV	= $ITM_VOLM_QTY;
					$REQ_VOLMV	= $REQ_VOLM;
					$TOTWOQTY_V = "";
					if($WO_QTY2 > 0)
					{
						$TOTWOQTY_V = 	"<div>
											<p class='text-yellow' style='white-space:nowrap'><i class='fa fa-exclamation-triangle'></i>
									  		".number_format($WO_QTY2, 2)."</p>
									  	</div>";
					}
					$PO_VOLMV	= $PO_VOLM;
					$ITM_STOCKV	= $REMREQ_QTY;
				}

				$ISLAST			= $dataI['ISLAST'];
				$JOBLEV			= $dataI['IS_LEVEL'];

				// IS LAST SETT
					if($ISLAST == 0)	// HEADER
					{
						$disabledB	= 1;
						$JOBDESC1 	= $JOBDESC;
						$STATCOL	= 'primary';
						$CELL_COL	= "style='font-weight:bold; white-space:nowrap'";
					}
					else
					{
						$strLEN 	= strlen($JOBDESC);
						$JOBDESCA	= substr("$JOBDESC", 0, 60);
						$JOBDESC1 	= $JOBDESCA;
						if($strLEN > 60)
							$JOBDESC1 	= $JOBDESCA."...";
						$STATCOL	= 'success';
						$CELL_COL	= "style='white-space:nowrap'";
					}

				// SPACE
					if($JOBLEV == 1)
						$spaceLev 	= "";
					elseif($JOBLEV == 2)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 3)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 4)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 5)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 6)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 7)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 8)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 9)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 10)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 11)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 12)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					else
						$spaceLev 	= "";

				// OTHER SETT
					if($disabledB == 0)
					{
						/*$chkBox	= "<input type='checkbox' name='chk1' value='".$JOBCODEDET."|".$JOBCODEID."|".$JOBCODE."|".$PRJCODE."|".$ITM_CODE."|".$JOBDESC."|".$serialNumber."|".$ITM_UNIT."|".$ITM_PRICE."|".$ITM_VOLM_QTY."|".$ITM_VOLM_AMN."|".$ITM_STOCK."|".$ITM_USED."|".$itemConvertion."|".$ITM_AMOUNT."|".$tempTotMax."|".$PO_AMOUNT."|".$TOT_USEDQTY."|".$WO_QTY."|".$WO_AMOUNT."|".$OPN_QTY."|".$OPN_AMOUNT."' onClick='pickThis1(this);'/>";*/

						$chkBox	= "<input type='checkbox' name='chk1' value='".$JOBCODEDET."|".$JOBCODEID."|".$JOBCODE."|".$PRJCODE."|".$ITM_CODE."|".$JOBDESC."|".$serialNumber."|".$ITM_UNIT."|".$ITM_PRICE."|".$ITM_VOLM_QTY."|".$ITM_VOLM_AMN."|".$ITM_USED."|".$ITM_USED_AM."|".$tempTotMax."|".$PO_VOLM."|".$PO_AMOUNT."|".$WO_QTY."|".$WO_AMOUNT."|".$OPN_QTY."|".$OPN_AMOUNT."|".$TOT_USED_QTY."|".$TOT_USED_AMN."|".$ITM_STOCK."|".$ITM_STOCK_AM."|".$ITM_REMVOL."|".$ITM_REMAMN."' onClick='pickThis1(this);'/>";
					}
					else
					{
						$chkBox	= "<input type='checkbox' name='chk1' value='' style='display: none' />";
					}

				$secUpd			= site_url('c_comprof/c_bUd93tL15t/update/?id='.$this->url_encryption_helper->encode_url($JOBCODEID));
                
				$secPrint		= 	"<label style='white-space:nowrap'>
									   	<a href='".$secUpd."' class='btn btn-warning btn-xs' title='Update'>
											<i class='glyphicon glyphicon-pencil'></i>
									   	</a>
									</label>";
				$JobView		= "$spaceLev $JOBCODEID - $JOBDESC1";

				$output['data'][] 	= array($chkBox,
											"<span ".$CELL_COL.">".$JobView."</span>",
											"<div style='text-align:center;'><span ".$CELL_COL.">".$ITM_UNIT."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".number_format($ITM_BUDGV, 2)."</span></div>",
											"<div style='text-align:right'><span ".$CELL_COL.">".number_format($REQ_VOLMV, 2).$TOTWOQTY_V."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".number_format($PO_VOLMV, 2)."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".number_format($ITM_STOCKV, 2)."</span></div>");

				$noU			= $noU + 1;
			}

			/*$output['data'][] 	= array("A",
										"B",
										"C",
										"D",
										"E",
										"F",
										"G");*/

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

	function getPPN()
	{
		date_default_timezone_set("Asia/Jakarta");
		
		$TAX_NUM 	= $_POST['TAX_NUM'];

		$sqlTax 	= "SELECT TAXLA_PERC FROM tbl_tax_ppn WHERE TAXLA_NUM = '$TAX_NUM'";
	    $resTax     = $this->db->query($sqlTax)->result();
	    foreach($resTax as $rowTax) :
	        $taxPerc = $rowTax->TAXLA_PERC;
	    endforeach;

		echo $taxPerc;
	}

	function getPPH()
	{
		date_default_timezone_set("Asia/Jakarta");
		
		$TAX_NUM 	= $_POST['TAX_NUM'];

		$sqlTax 	= "SELECT TAXLA_PERC FROM tbl_tax_la WHERE TAXLA_NUM = '$TAX_NUM'";
	    $resTax     = $this->db->query($sqlTax)->result();
	    foreach($resTax as $rowTax) :
	        $taxPerc = $rowTax->TAXLA_PERC;
	    endforeach;

		echo $taxPerc;
	}
}