<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 15 Maret 2022
	* File Name		= C_budprop.php
	* Location		= -
*/

class C_budprop extends CI_Controller
{
  	function __construct() 										// OK
	{
		parent::__construct();

		setlocale(LC_ALL, 'id-ID', 'id_ID');

		$this->load->model('m_project/m_budprop/m_budprop', '', TRUE);
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
	
 	function index() 									// OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		$url			= site_url('c_project/c_budprop/prj180d0blst/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}

	function prj180d0blst() 									// OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_project/m_budprop/m_budprop', '', TRUE);

		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);

			$LangID 	= $this->session->userdata['LangID'];
			// GET MENU DESC
				$mnCode				= 'MN059';
				$MenuCode			= 'MN059';
				$data["MenuCode"] 	= 'MN059';
				$data["MenuApp"] 	= 'MN059';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;

			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN059';
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data["secURL"] 	= "c_project/c_budprop/gallpR0p/?id=";

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

	function gallpR0p() 										// OK
	{
		$this->load->model('m_project/m_budprop/m_budprop', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		$LangID 	= $this->session->userdata['LangID'];
		// GET MENU DESC
			$mnCode				= 'MN059';
			$MenuCode			= 'MN059';
			$data["MenuCode"] 	= 'MN059';
			$data["MenuApp"] 	= 'MN059';
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
				$data["url_search"] = site_url('c_project/c_budprop/f4n7_5rcH5pK/?id='.$this->url_encryption_helper->encode_url($PRJCODE));

				//$num_rows 			= $this->m_budprop->count_all_WO($PRJCODE, $key);
				//$data["cData"] 		= $num_rows;
				//$data['vData']		= $this->m_budprop->get_all_WO($PRJCODE, $start, $end, $key)->result();
			// -------------------- END : SEARCHING METHOD --------------------

			$data['title'] 		= $appName;
			$data['addURL'] 	= site_url('c_project/c_budprop/pR0p_144n3/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['backURL'] 	= site_url('c_project/c_budprop/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
			$MenuCode 			= 'MN059';
			$data["MenuCode"] 	= 'MN059';

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN059';
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

			$this->load->view('v_project/v_budprop/v_budprop', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

  	function get_AllData() 										// OK
	{
		$PRJCODE		= $_GET['id'];

		$LangID     	= $this->session->userdata['LangID'];
        
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
    		if($TranslCode == 'SPKVal')$SPKVal = $LangTransl;
    		if($TranslCode == 'QtyOpnamed')$QtyOpnamed = $LangTransl;
    		if($TranslCode == 'cancled')$cancled = $LangTransl;
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

			$columns_valid 	= array("PROP_CODE",
									"PROP_CATEG", 
									"PROP_DATE", 
									"PROP_NOTE", 
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
			$num_rows 		= $this->m_budprop->get_AllDataC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_budprop->get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir);

			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI)
			{
				$PROP_NUM		= $dataI['PROP_NUM'];
				$PROP_CODE		= $dataI['PROP_CODE'];
				$PROP_DATE		= $dataI['PROP_DATE'];
				$PROP_DATEV		= strftime('%d %b %Y', strtotime($PROP_DATE));
				$PRJCODE		= $dataI['PRJCODE'];
				$EMP_ID			= $dataI['EMP_ID'];
				$PROP_NOTE		= $dataI['PROP_NOTE'];
				$PROP_NOTED		= '';
				if($PROP_NOTE != '')
					$PROP_NOTED	= "$PROP_NOTE";

				$VLK_CREATED	= $dataI['VLK_CREATED'];
				$PROP_STAT		= $dataI['PROP_STAT'];
				$PROP_VALUE		= $dataI['PROP_VALUE'];
				$PROP_VALUEAPP	= $dataI['PROP_VALUEAPP'];
				$PROP_GTOTAL	= $dataI['PROP_GTOTAL'];
				$PROP_CREATER	= $dataI['PROP_CREATER'];
				$PROP_CREATED	= $dataI['PROP_CREATED'];
				$STATDESC		= $dataI['STATDESC'];
				$STATCOL		= $dataI['STATCOL']; 
				$CREATERNM1		= $dataI['CREATERNM'];
				$CREATERNM		= cut_text2 ("$CREATERNM1", 15);

				$propV 			= 	"<div style='white-space:nowrap'>
											<strong><i class='fa fa-money margin-r-5'></i> Diajukan</strong>
										</div>
									  	<div style='margin-left: 20px;'>
									  		".number_format($PROP_VALUE, 2)."
									  	</div>";

				$appV 			= 	"<div style='white-space:nowrap'>
											<strong><i class='fa fa-check-square-o margin-r-5'></i> Disetujui</strong>
										</div>
									  	<div style='margin-left: 20px;'>
									  		".number_format($PROP_VALUEAPP, 2)."
									  	</div>";

				$EMP_NAME		= '';
				$sqlEMPD		= "SELECT CONCAT(First_Name, ' ', Last_Name) AS compName FROM tbl_employee WHERE Emp_ID = '$EMP_ID' LIMIT 1";
				$resEMPD		= $this->db->query($sqlEMPD)->result();
				foreach($resEMPD as $rowEMPD) :
					$EMP_NAME	= $rowEMPD->compName;		
				endforeach;

				$POSS_NAME		= '';
				$sqlPOSS		= "SELECT B.POSS_NAME from tbl_employee A LEFT JOIN tbl_position_str B ON A.Pos_Code = B.POSS_CODE
									WHERE A.Emp_ID = '$EMP_ID'";
				$resPOSS		= $this->db->query($sqlPOSS)->result();
				foreach($resPOSS as $rowPOSS) :
					$POSS_NAME	= $rowPOSS->POSS_NAME;		
				endforeach;

				$CollID		= "$PRJCODE~$PROP_NUM";
				$secUpd		= site_url('c_project/c_budprop/up180d0bdt/?id='.$this->url_encryption_helper->encode_url($CollID));
				$secCreate	= site_url('c_project/c_budprop/cr3473d0c_m4d/?id='.$this->url_encryption_helper->encode_url($PROP_NUM));
				$secPrint	= site_url('c_project/c_budprop/pr1n7d0c_e/?id='.$this->url_encryption_helper->encode_url($PROP_NUM));

				$CollID		= "$PROP_NUM~$PRJCODE";
				$secDel_WO 	= base_url().'index.php/c_project/c_budprop/trash_WO/?id='.$PROP_NUM;
				$secDelIcut = base_url().'index.php/__l1y/trashDOC/?id=';
				$delID 		= "$secDelIcut~tbl_bprop_header~tbl_bprop_detail~PROP_NUM~$PROP_NUM~PRJCODE~$PRJCODE";

				if($PROP_STAT == 1)
				{
					$secAction	= 	"<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")' disabled='disabled'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				else if($PROP_STAT == 2)
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
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete' disabled='disabled'>
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
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='#' onClick='deleteDOC('".$secDel_WO."')' title='Delete file' class='btn btn-danger btn-xs' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}

				$output['data'][] 	= array("<div style='white-space:nowrap'>
												<strong>$PROP_CODE</strong><br>
												<strong><i class='fa fa-calendar margin-r-5'></i> ".$Date." </strong>
										  		<div>
											  		<p class='text-muted' style='margin-left: 15px'>
											  			".$PROP_DATEV."
											  		</p>
											  	</div>
											</div>",
										  	"<strong><i class='fa fa-pencil margin-r-5'></i> $Description </strong>
										  	<div class='text-muted' style='margin-left: 18px'>
										  		".$PROP_NOTE."
										  	</div>",
											"$propV $appV",
										  	"<div style='white-space:nowrap'>
											  	<strong><i class='fa fa-user margin-r-5'></i> ".$EMP_NAME." </strong>
										  		<p class='text-muted' style='margin-left: 18px'>
										  			".$EMP_ID."<br>".$POSS_NAME."
										  		</p>
										  	</div>",
											"<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
											$secAction);
				$noU		= $noU + 1;
			}

			/*$output['data'][] 	= array("A",
									  	"B",
									  	"C",
										"D",
										"E",
										"F",
										"G",
										"H");*/

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

  	function get_AllDataSH() 									// OK
	{
		$PRJCODE		= $_GET['id'];
		$ISCLS			= $_GET['ISCLS'];

		$LangID     	= $this->session->userdata['LangID'];
        
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
    		if($TranslCode == 'SPKVal')$SPKVal = $LangTransl;
    		if($TranslCode == 'QtyOpnamed')$QtyOpnamed = $LangTransl;
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

			$columns_valid 	= array("PROP_CODE",
									"PROP_CATEG", 
									"PROP_DATE", 
									"PROP_NOTE", 
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
			$num_rows 		= $this->m_budprop->get_AllDataSHC($PRJCODE, $ISCLS, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_budprop->get_AllDataSHL($PRJCODE, $ISCLS, $search, $length, $start, $order, $dir);

			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI)
			{
				$PROP_NUM		= $dataI['PROP_NUM'];
				$PROP_CODE		= $dataI['PROP_CODE'];
				$PROP_DATE		= $dataI['PROP_DATE'];
				$WO_ENDD		= $dataI['WO_ENDD'];
				//$PROP_DATEV	= date('d M Y', strtotime($PROP_DATE));
				$PROP_DATEV1	= strftime('%d %b %Y', strtotime($PROP_DATE));
				$WO_ENDDV		= strftime('%d %b %Y', strtotime($WO_ENDD));

				//$PROP_DATEV 	= $PROP_DATEV1." - ".$WO_ENDDV;
				$PROP_DATEV 	= $PROP_DATEV1;

				$WO_CATEG		= $dataI['WO_CATEG'];
				$PRJCODE		= $dataI['PRJCODE'];
				$PROP_NOTE		= $dataI['PROP_NOTE'];
				$PROP_NOTED		= '';
				if($PROP_NOTE != '')
					$PROP_NOTED	= "$PROP_NOTE";
				$PROP_STAT		= $dataI['PROP_STAT'];
				$WO_REFNO		= $dataI['WO_REFNO'];
				$WO_CREATER		= $dataI['WO_CREATER'];
				$WO_ISCLOSE		= $dataI['WO_ISCLOSE'];
				$JOBCODEID		= $dataI['JOBCODEID'];
				$WO_CDOC		= $dataI['WO_CDOC'];
				$JOBDESC		= $dataI['JOBDESC']; 
				$SPLCODE		= $dataI['SPLCODE'];
				$PROP_VALUE		= $dataI['PROP_VALUE'];

				// OPN TOTAL
					$TOTOPNAMN	= 0;
					$sqlTOT_OPN	= "SELECT SUM(A.OPN_AMOUNT) AS TOTOPN_AMN FROM tbl_bprop_detail A
									WHERE A.PROP_NUM = '$PROP_NUM' AND A.PRJCODE = '$PRJCODE'";
					$resTOT_OPN		= $this->db->query($sqlTOT_OPN)->result();
					foreach($resTOT_OPN as $rowTOT_OPN) :
						$TOTOPNAMN	= $rowTOT_OPN->TOTOPN_AMN;
						if($TOTOPNAMN == '')
							$TOTOPNAMN	= 0;
					endforeach;

				$spkD 			= 	"<div style='white-space:nowrap'>
											<strong><i class='fa fa-bullhorn margin-r-5'></i> ".$SPKVal."</strong>
										</div>
									  	<div style='margin-left: 20px;'>
									  		".number_format($PROP_VALUE, 2)."
									  	</div>";

				$opnameD 		= 	"<div style='white-space:nowrap'>
											<strong><i class='fa fa-check-square-o margin-r-5'></i> ".$QtyOpnamed."</strong>
										</div>
									  	<div style='margin-left: 20px;'>
									  		".number_format($TOTOPNAMN, 2)."
									  	</div>";
				
				$SPLDESC		= '';
				$sqlSPLD		= "SELECT SPLDESC FROM tbl_supplier WHERE SPLCODE = '$SPLCODE' LIMIT 1";
				$resSPLD		= $this->db->query($sqlSPLD)->result();
				foreach($resSPLD as $rowSPLD) :
					$SPLDESC	= $rowSPLD->SPLDESC;		
				endforeach;
				
				if($JOBDESC == '') $JOBDESC = "";
				$JOBDESCD		= "$JOBDESC$PROP_NOTED";
				if($JOBDESCD == '')
				{
					/*$expJOB		= explode("~", $JOBCODEID);
					$JOBCODEID1	= $expJOB[0];
					$JOBDESC1	= '';
					$sqlJOB		= "SELECT JOBDESC FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBCODEID1'";
					$resJOB		= $this->db->query($sqlJOB)->result();
					foreach($resJOB as $rowJOB) :
						$JOBDESC1 = $rowJOB->JOBDESC;		
					endforeach;
					$JOBDESCD	= $JOBDESC1;*/
					$JOBDESCDV 	= "";
				}
				else
				{
					$JOBDESCDV 	= "<div class='text-muted' style='margin-left: 18px'>
										  		".$JOBDESCD."
										  	</div>";
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
					$WO_CATEGD 	= 'Sewa Alat';
				}
				$STATDESC	= $dataI['STATDESC'];
				$STATCOL	= $dataI['STATCOL'];
				$CREATERNM	= $dataI['CREATERNM'];
				$empName	= cut_text2 ("$CREATERNM", 15);
				$WO_REFNO	= $dataI['WO_REFNO'];

				$CollID		= "$PRJCODE~$PROP_NUM";
				$secUpd		= site_url('c_project/c_budprop/up180d0bdt/?id='.$this->url_encryption_helper->encode_url($CollID));
				$secCreate	= site_url('c_project/c_budprop/cr3473d0c_m4d/?id='.$this->url_encryption_helper->encode_url($PROP_NUM));
				// if($WO_CATEG == 'MDR')
				// 	$secPrint	= site_url('c_project/c_budprop/pr1n7d0c_m4d/?id='.$this->url_encryption_helper->encode_url($PROP_NUM));
				// else
					$secPrint	= site_url('c_project/c_budprop/pr1n7d0c_e/?id='.$this->url_encryption_helper->encode_url($PROP_NUM));

				$CollID		= "$PROP_NUM~$PRJCODE";
				$secDel_WO 	= base_url().'index.php/c_project/c_budprop/trash_WO/?id='.$PROP_NUM;
				$secDelIcut = base_url().'index.php/__l1y/trashDOC/?id=';
				$delID 		= "$secDelIcut~tbl_bprop_header~tbl_bprop_detail~PROP_NUM~$PROP_NUM~PRJCODE~$PRJCODE";

				if($PROP_STAT == 1)
				{
					$secAction	= 	"<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")' disabled='disabled'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				else if($PROP_STAT == 2)
				{
					$secAction	= 	"<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")' disabled='disabled'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete' disabled='disabled'>
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
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='#' onClick='deleteDOC('".$secDel_WO."')' title='Delete file' class='btn btn-danger btn-xs' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}

				$output['data'][] 	= array("<div style='white-space:nowrap'>
												<strong>$PROP_CODE</strong><br>
												<strong><i class='fa fa-calendar margin-r-5'></i> ".$Date." </strong>
										  		<div>
											  		<p class='text-muted' style='margin-left: 15px'>
											  			".$PROP_DATEV."
											  		</p>
											  	</div>
											</div>",
										  	"<div style='white-space:nowrap'>
											  	<strong><i class='fa fa-user margin-r-5'></i> ".$SPLDESC." / ".$SPLCODE." </strong><br>
											  	<strong><i class='fa fa-cog margin-r-5'></i> ".$Category." </strong>
										  		<div>
											  		<p class='text-muted' style='margin-left: 18px'>
											  			".$WO_CATEGD."
											  		</p>
											  	</div>
										  	</div>",
										  	"<strong><i class='fa fa-pencil margin-r-5'></i> ".$Description." </strong>
										  	".$JOBDESCDV."
										  	<strong><i class='fa fa-paperclip margin-r-5'></i> No. SK </strong>
									  		<div class='text-muted' style='margin-left: 18px'>
										  		".$WO_REFNO."
										  	</div>",
											"$spkD $opnameD",
											$empName,
											"<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
											$secAction);
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

	function pR0p_144n3() 										// OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_project/m_budprop/m_budprop', '', TRUE);

		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		$LangID 	= $this->session->userdata['LangID'];
		// GET MENU DESC
			$mnCode				= 'MN059';
			$MenuCode			= 'MN059';
			$data["MenuCode"] 	= 'MN059';
			$data["MenuApp"] 	= 'MN059';
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
			$data['form_action']= site_url('c_project/c_budprop/add_process');
			$data['backURL'] 	= site_url('c_project/c_budprop/gallpR0p/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;

			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();

			$MenuCode 			= 'MN059';
			$data["MenuCode"] 	= 'MN059';
			$data["MenuCode1"] 	= 'MN059';
			$data['vwDocPatt'] 	= $this->m_budprop->getDataDocPat($MenuCode)->result();

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN059';
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

			$this->load->view('v_project/v_budprop/v_budprop_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function add_process() 										// OK
	{
		$this->load->model('m_project/m_budprop/m_budprop', '', TRUE);
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

			$PROP_STAT 		= $this->input->post('PROP_STAT');
			$PROP_NUM 		= $this->input->post('PROP_NUM');
			$PROP_CODE 		= $this->input->post('PROP_CODE');
			$PROP_DATE		= date('Y-m-d',strtotime($this->input->post('PROP_DATE')));
				$Patt_Year	= date('Y',strtotime($this->input->post('PROP_DATE')));
				$Patt_Month	= date('m',strtotime($this->input->post('PROP_DATE')));
				$Patt_Date	= date('d',strtotime($this->input->post('PROP_DATE')));

			$PRJCODE 		= $this->input->post('PRJCODE');
			$EMP_ID 		= $DefEmp_ID;
			$PROP_NOTE 		= addslashes($this->input->post('PROP_NOTE'));
			$PROP_NOTE2 	= addslashes($this->input->post('PROP_NOTE2'));

			$VLK_CREATED	= 0;
			$PROP_VALUE		= $this->input->post('PROP_VALUE');
			$PROP_GTOTAL	= $this->input->post('PROP_GTOTAL');
			$PROP_CREATER	= $DefEmp_ID;
			$PROP_CREATED	= date('Y-m-f H:i:s');

			$projBPROPH 	= array('PROP_NUM' 		=> $PROP_NUM,
									'PROP_CODE' 	=> $PROP_CODE,
									'PROP_DATE'		=> $PROP_DATE,
									'PRJCODE'		=> $PRJCODE,
									'EMP_ID'		=> $EMP_ID,
									'PROP_NOTE'		=> $PROP_NOTE,
									'PROP_NOTE2'	=> $PROP_NOTE2,
									'VLK_CREATED'	=> 0,
									'PROP_STAT'		=> $PROP_STAT,
									'PROP_VALUE'	=> $PROP_VALUE,
									'PROP_GTOTAL'	=> $PROP_GTOTAL,
									'PROP_CREATER'	=> $DefEmp_ID,
									'PROP_CREATED'	=> date('Y-m-d H:i:s'),
									'PROP_ISCLOSE'	=> 0,
									'Patt_Number'	=> $this->input->post('Patt_Number'));
			$this->m_budprop->add($projBPROPH);

			$PROP_GTOTAL	= 0;
      		$PROPID 		= 0;
			foreach($_POST['data'] as $d)
			{
				$PROPID 		= $PROPID+1;
				$d['PROP_ID']	= $PROPID;
				$d['PROP_NUM']	= $PROP_NUM;
				$d['PROP_CODE']	= $PROP_CODE;
				$d['PRJCODE']	= $PRJCODE;

				$JOBCODEID 		= $d['JOBCODEID'];
				$JOBDESC		= "";
				$sqlJOB			= "SELECT JOBDESC FROM tbl_joblist_detail
									WHERE JOBCODEID IN (SELECT JOBPARENT FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE')
									AND PRJCODE = '$PRJCODE'";
				$resJOB			= $this->db->query($sqlJOB)->result();
				foreach($resJOB as $rowJOB) :
					$JOBDESC 	= $rowJOB->JOBDESC;		
				endforeach;	
				$d['JOBDESC']	= $JOBDESC;

				$ITM_CODE 		= $d['ITM_CODE'];
				$ITM_NAME 		= "";
				$sqlITM			= "SELECT ITM_NAME FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
				$resITM			= $this->db->query($sqlITM)->result();
				foreach($resITM as $rowITM) :
					$ITM_NAME 	= $rowITM->ITM_NAME;		
				endforeach;	
				$d['ITM_NAME']	= $ITM_NAME;

				$this->db->insert('tbl_bprop_detail',$d);
			}

			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "PROP_NUM",
										'DOC_CODE' 		=> $PROP_NUM,
										'DOC_STAT' 		=> $PROP_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_bprop_header");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $PROP_NUM;
				$MenuCode 		= 'MN059';
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

			$url			= site_url('c_project/c_budprop/gallpR0p/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function up180d0bdt() 										// OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_project/m_budprop/m_budprop', '', TRUE);
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

		$LangID 	= $this->session->userdata['LangID'];
		// GET MENU DESC
			$mnCode				= 'MN059';
			$MenuCode			= 'MN059';
			$data["MenuCode"] 	= 'MN059';
			$data["MenuApp"] 	= 'MN059';
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
			$PROP_NUM		= $EXTRACTCOL[1];
			
			$docPatternPosition = 'Especially';
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_project/c_budprop/update_process');
			$data['backURL'] 	= site_url('c_project/c_budprop/gallpR0p/?id='.$this->url_encryption_helper->encode_url($PRJCODE));

			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();

			$getwodata 							= $this->m_budprop->get_PROP_by_number($PROP_NUM)->row();

			$data['default']['PROP_NUM'] 		= $getwodata->PROP_NUM;
			$data['default']['PROP_CODE'] 		= $getwodata->PROP_CODE;
			$data['default']['PROP_DATE'] 		= $getwodata->PROP_DATE;
			$data['default']['PRJCODE']			= $getwodata->PRJCODE;
			$data['PRJCODE']					= $getwodata->PRJCODE;
			$PRJCODE 							= $getwodata->PRJCODE;
			$data['default']['EMP_ID'] 			= $getwodata->EMP_ID;
			$data['default']['PROP_NOTE'] 		= $getwodata->PROP_NOTE;
			$data['default']['PROP_NOTE2'] 		= $getwodata->PROP_NOTE2;
			$data['default']['VLK_CREATED'] 	= $getwodata->VLK_CREATED;
			$data['default']['PROP_STAT'] 		= $getwodata->PROP_STAT;
			$data['default']['PROP_MEMO'] 		= $getwodata->PROP_MEMO;
			$data['default']['PROP_VALUE'] 		= $getwodata->PROP_VALUE;
			$data['default']['PROP_VALUEAPP'] 	= $getwodata->PROP_VALUEAPP;
			$data['default']['PROP_GTOTAL'] 	= $getwodata->PROP_GTOTAL;
			$data['default']['PROP_ISCLOSE'] 	= $getwodata->PROP_ISCLOSE;
			$data['default']['Patt_Number']		= $getwodata->Patt_Number;

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getwodata->PROP_NUM;
				$MenuCode 		= 'MN059';
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

			$this->load->view('v_project/v_budprop/v_budprop_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function update_process() 									// OK
	{
		$this->load->model('m_project/m_budprop/m_budprop', '', TRUE);
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

			$PROP_STAT 		= $this->input->post('PROP_STAT');
			$PROP_NUM 		= $this->input->post('PROP_NUM');
			$PROP_CODE 		= $this->input->post('PROP_CODE');
			$PROP_DATE		= date('Y-m-d',strtotime($this->input->post('PROP_DATE')));
				$Patt_Year	= date('Y',strtotime($this->input->post('PROP_DATE')));
				$Patt_Month	= date('m',strtotime($this->input->post('PROP_DATE')));
				$Patt_Date	= date('d',strtotime($this->input->post('PROP_DATE')));

			$PRJCODE 		= $this->input->post('PRJCODE');
			$EMP_ID 		= $DefEmp_ID;
			$PROP_NOTE 		= addslashes($this->input->post('PROP_NOTE'));
			$PROP_NOTE2 	= addslashes($this->input->post('PROP_NOTE2'));

			$VLK_CREATED	= 0;
			$PROP_VALUE		= $this->input->post('PROP_VALUE');
			$PROP_GTOTAL	= $this->input->post('PROP_GTOTAL');
			$PROP_CREATER	= $DefEmp_ID;
			$PROP_CREATED	= date('Y-m-f H:i:s');

			$projBPROPH 	= array('PROP_CODE' 	=> $PROP_CODE,
									'PROP_DATE'		=> $PROP_DATE,
									'PRJCODE'		=> $PRJCODE,
									'EMP_ID'		=> $EMP_ID,
									'PROP_NOTE'		=> $PROP_NOTE,
									'PROP_NOTE2'	=> $PROP_NOTE2,
									'VLK_CREATED'	=> 0,
									'PROP_STAT'		=> $PROP_STAT,
									'PROP_VALUE'	=> $PROP_VALUE,
									'PROP_GTOTAL'	=> $PROP_GTOTAL,
									'PROP_CREATER'	=> $DefEmp_ID,
									'PROP_CREATED'	=> date('Y-m-d H:i:s'),
									'PROP_ISCLOSE'	=> 0,
									'Patt_Number'	=> $this->input->post('Patt_Number'));

			if($PROP_STAT == 3)
			{
				$projWOH 		= array('PROP_STAT'		=> $PROP_STAT,
										'PROP_NOTE'		=> $PROP_NOTE);
				$this->m_budprop->update($PROP_NUM, $projWOH);

				foreach($_POST['data'] as $d)
				{
					$PROP_NUM	= $d['PROP_NUM'];
					$JOBCODEID1	= $d['JOBCODEID'];
					$ITM_CODE	= $d['ITM_CODE'];
					$PROP_VOLM	= $d['ITM_VOLM'];
					$PROP_TOTAL	= $d['PROP_TOTAL'];
					$param 		= array('PROP_NUM' 		=> $PROP_NUM,
										'JOBCODEID' 	=> $JOBCODEID1,
										'ITM_CODE' 		=> $ITM_CODE,
										'PROP_VOLM'		=> $PROP_VOLM,
										'PROP_TOTAL'	=> $PROP_TOTAL);
					// $this->m_budprop->closedUPDWO($PROP_NUM, $PRJCODE, $param);
				}

				// SEKALIAN SYNC ALL
					// $this->m_budprop->closedWO($PROP_NUM, $PRJCODE);
			
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "PROP_NUM",
											'DOC_CODE' 		=> $PROP_NUM,
											'DOC_STAT' 		=> $PROP_STAT,
											'PRJCODE' 		=> $PRJCODE,
											//'CREATERNM'	=> $completeName,
											'TBLNAME'		=> "tbl_bprop_header");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
			}
			else
			{
				$this->m_budprop->update($PROP_NUM, $projBPROPH);
				
				$this->m_budprop->deleteDetail($PROP_NUM);

				$PROP_GTOTAL	= 0;
	      		$PROPID 		= 0;
				foreach($_POST['data'] as $d)
				{
					$PROPID 		= $PROPID+1;
					$d['PROP_ID']	= $PROPID;
					$d['PROP_NUM']	= $PROP_NUM;
					$d['PROP_CODE']	= $PROP_CODE;
					$d['PRJCODE']	= $PRJCODE;

					$JOBCODEID 		= $d['JOBCODEID'];
					$JOBDESC		= "";
					$sqlJOB			= "SELECT JOBDESC FROM tbl_joblist_detail
										WHERE JOBCODEID IN (SELECT JOBPARENT FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE')
										 AND PRJCODE = '$PRJCODE'";
					$resJOB			= $this->db->query($sqlJOB)->result();
					foreach($resJOB as $rowJOB) :
						$JOBDESC 	= $rowJOB->JOBDESC;		
					endforeach;	
					$d['JOBDESC']	= $JOBDESC;

					$ITM_CODE 		= $d['ITM_CODE'];
					$ITM_NAME 		= "";
					$sqlITM			= "SELECT ITM_NAME FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
					$resITM			= $this->db->query($sqlITM)->result();
					foreach($resITM as $rowITM) :
						$ITM_NAME 	= $rowITM->ITM_NAME;		
					endforeach;	
					$d['ITM_NAME']	= $ITM_NAME;

					$this->db->insert('tbl_bprop_detail',$d);
				}
			
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "PROP_NUM",
											'DOC_CODE' 		=> $PROP_NUM,
											'DOC_STAT' 		=> $PROP_STAT,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> $completeName,
											'TBLNAME'		=> "tbl_bprop_header");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
			}

			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "PROP_NUM",
										'DOC_CODE' 		=> $PROP_NUM,
										'DOC_STAT' 		=> $PROP_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_bprop_header");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $PROP_NUM;
				$MenuCode 		= 'MN059';
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
			$url			= site_url('c_project/c_budprop/gallpR0p/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function trash_WO() // U
	{
		$this->load->model('m_project/m_budprop/m_budprop', '', TRUE);
		/*$CollID		= $_GET['id'];
		$splitCode 	= explode("~", $CollID);
		$PR_NUM		= $splitCode[0];
		$PRJCODE	= $splitCode[1];*/
		$PROP_NUM		= $_GET['id'];
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

			$this->m_budprop->deleteWO($PROP_NUM);

			// START : UPDATE TO TRANS-COUNT
				/*$this->load->model('m_updash/m_updash', '', TRUE);

				$PR_STAT		= 1;
				$STAT_BEFORE	= 1;										// IF "ADD" CONDITION ALWAYS = PR_STAT
				$parameters 	= array('DOC_CODE' 		=> $PR_NUM,			// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "PR",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_bprop_header",	// TABLE NAME
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

		$url			= site_url('c_project/c_budprop/pR7_l5t_5pKx1/?id='.$this->url_encryption_helper->encode_url($appName));
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
				$mnCode				= 'MN059';
				$data["MenuApp"] 	= 'MN059';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN059';
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data["secURL"] 	= "c_project/c_budprop/s5pK_1nb_5pKa/?id=";

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
		$this->load->model('m_project/m_budprop/m_budprop', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		$LangID 	= $this->session->userdata['LangID'];
		// GET MENU DESC
			$mnCode				= 'MN059';
			$MenuCode			= 'MN059';
			$data["MenuCode"] 	= 'MN059';
			$data["MenuApp"] 	= 'MN059';
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
				$data["url_search"] = site_url('c_project/c_budprop/f4n7_5rcH1nB5pK/?id='.$this->url_encryption_helper->encode_url($PRJCODE));

				//$num_rows 			= $this->m_budprop->count_all_WOInx($PRJCODE, $key, $DefEmp_ID);
				//$data["cData"] 		= $num_rows;
				//$data['vData']		= $this->m_budprop->get_all_WOInb($PRJCODE, $start, $end, $key, $DefEmp_ID)->result();
			// -------------------- END : SEARCHING METHOD --------------------
			
			// GET MENU DESC
				$mnCode				= 'MN059';
				$data["MenuApp"] 	= 'MN059';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$data['title'] 		= $appName;
			$data['addURL'] 	= site_url('c_project/c_budprop/add/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['backURL'] 	= site_url('c_project/c_budprop/inbox/');
			$data['PRJCODE'] 	= $PRJCODE;
			$data['MenuCode']	= 'MN059';

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN059';
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
			$url			= site_url('c_project/c_budprop/s5pK_1nb_5pKa/?id='.$this->url_encryption_helper->encode_url($collDATA));
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
    		if($TranslCode == 'SPKVal')$SPKVal = $LangTransl;
    		if($TranslCode == 'QtyOpnamed')$QtyOpnamed = $LangTransl;
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

			$columns_valid 	= array("PROP_CODE",
									"WO_CATEG", 
									"PROP_DATE", 
									"PROP_NOTE", 
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
			$num_rows 		= $this->m_budprop->get_AllDataC_1n2($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_budprop->get_AllDataL_1n2($PRJCODE, $search, $length, $start, $order, $dir);

			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI)
			{
				$PROP_NUM			= $dataI['PROP_NUM'];
				$PROP_CODE		= $dataI['PROP_CODE'];
				$PROP_DATE		= $dataI['PROP_DATE'];
				$WO_ENDD		= $dataI['WO_ENDD'];
				//$PROP_DATEV		= date('d M Y', strtotime($PROP_DATE));
				$PROP_DATEV		= strftime('%d %b %Y', strtotime($PROP_DATE));
				$WO_ENDDV		= strftime('%d %b %Y', strtotime($WO_ENDD));

				$WO_CATEG		= $dataI['WO_CATEG'];
				$PRJCODE		= $dataI['PRJCODE'];
				$PROP_NOTE		= $dataI['PROP_NOTE'];
				$PROP_NOTED		= '';
				if($PROP_NOTE != '')
					$PROP_NOTED	= "$PROP_NOTE";

				$PROP_STAT		= $dataI['PROP_STAT'];
				$WO_REFNO		= $dataI['WO_REFNO'];
				$WO_CREATER		= $dataI['WO_CREATER'];
				$WO_ISCLOSE		= $dataI['WO_ISCLOSE'];
				$JOBCODEID		= $dataI['JOBCODEID'];
				$WO_CDOC		= $dataI['WO_CDOC'];
				$JOBDESC		= $dataI['JOBDESC'];
				$SPLCODE		= $dataI['SPLCODE'];
				$PROP_VALUE		= $dataI['PROP_VALUE'];

				// OPN TOTAL
					$TOTOPNAMN	= 0;
					$sqlTOT_OPN	= "SELECT SUM(A.OPN_AMOUNT) AS TOTOPN_AMN FROM tbl_bprop_detail A
									WHERE A.PROP_NUM = '$PROP_NUM' AND A.PRJCODE = '$PRJCODE'";
					$resTOT_OPN		= $this->db->query($sqlTOT_OPN)->result();
					foreach($resTOT_OPN as $rowTOT_OPN) :
						$TOTOPNAMN	= $rowTOT_OPN->TOTOPN_AMN;
						if($TOTOPNAMN == '')
							$TOTOPNAMN	= 0;
					endforeach;

				$spkD 			= 	"<div style='white-space:nowrap'>
											<strong><i class='fa fa-bullhorn margin-r-5'></i> ".$SPKVal."</strong>
										</div>
									  	<div style='margin-left: 20px;'>
									  		".number_format($PROP_VALUE, 2)."
									  	</div>";

				$opnameD 		= 	"<div style='white-space:nowrap'>
											<strong><i class='fa fa-check-square-o margin-r-5'></i> ".$QtyOpnamed."</strong>
										</div>
									  	<div style='margin-left: 20px;'>
									  		".number_format($TOTOPNAMN, 2)."
									  	</div>";
				
				$SPLDESC		= '';
				$sqlSPLD		= "SELECT SPLDESC FROM tbl_supplier WHERE SPLCODE = '$SPLCODE' LIMIT 1";
				$resSPLD		= $this->db->query($sqlSPLD)->result();
				foreach($resSPLD as $rowSPLD) :
					$SPLDESC	= $rowSPLD->SPLDESC;		
				endforeach;
				
				$JOBDESCD		= "$JOBDESC$PROP_NOTED";
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
					$JOBDESCDV 	= "";
				}
				else
				{
					$JOBDESCDV 	= "<div class='text-muted' style='margin-left: 18px'>
										  		".$JOBDESCD."
										  	</div>";
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

				$CollID		= "$PRJCODE~$PROP_NUM";
				$secUpd		= site_url('c_project/c_budprop/update_inb/?id='.$this->url_encryption_helper->encode_url($CollID));
				$secPrint	= site_url('c_project/c_budprop/printdocument/?id='.$this->url_encryption_helper->encode_url($PROP_NUM));

				if($WO_CATEG == 'MDR')
					$secPrint	= site_url('c_project/c_budprop/pr1n7d0c_m4d/?id='.$this->url_encryption_helper->encode_url($PROP_NUM));
				else
					$secPrint	= site_url('c_project/c_budprop/pr1n7d0c_e/?id='.$this->url_encryption_helper->encode_url($PROP_NUM));

				$CollID		= "$PROP_NUM~$PRJCODE";
				$secDel_WO 	= base_url().'index.php/c_project/c_budprop/trash_WO/?id='.$PROP_NUM;

				if($PROP_STAT == 1)
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
												<strong>$PROP_CODE</strong><br>
												<strong><i class='fa fa-calendar margin-r-5'></i> ".$Date." </strong>
										  		<div>
											  		<p class='text-muted' style='margin-left: 15px'>
											  			".$PROP_DATEV."
											  		</p>
											  	</div>
											</div>",
										  	"<div style='white-space:nowrap'>
											  	<strong><i class='fa fa-user margin-r-5'></i> ".$SPLDESC." / ".$SPLCODE." </strong><br>
											  	<strong><i class='fa fa-cog margin-r-5'></i> ".$Category." </strong>
										  		<div>
											  		<p class='text-muted' style='margin-left: 18px'>
											  			".$WO_CATEGD."
											  		</p>
											  	</div>
										  	</div>",
										  	"<strong><i class='fa fa-pencil margin-r-5'></i> ".$Description." </strong>
									  		".$JOBDESCDV."<br>
										  	<strong><i class='fa fa-paperclip margin-r-5'></i> No. SK </strong>
									  		<div class='text-muted' style='margin-left: 18px'>
										  		".$WO_REFNO."
										  	</div>",
											"$spkD $opnameD",
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
		$this->load->model('m_project/m_budprop/m_budprop', '', TRUE);
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
			
		// GET MENU DESC
			$mnCode				= 'MN059';
			$MenuCode			= 'MN059';
			$data["MenuCode"] 	= 'MN059';
			$data["MenuApp"] 	= 'MN059';
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
			$PROP_NUM		= $EXTRACTCOL[1];
			
			$docPatternPosition = 'Especially';
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_project/c_budprop/update_process_inb');

			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();

			$getwodata 						= $this->m_budprop->get_PROP_by_number($PROP_NUM)->row();
			$data['default']['PROP_NUM'] 		= $getwodata->PROP_NUM;
			$data['default']['PROP_CODE'] 	= $getwodata->PROP_CODE;
			$data['default']['PROP_DATE'] 	= $getwodata->PROP_DATE;
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
			$data['default']['PROP_NOTE'] 	= $getwodata->PROP_NOTE;
			$data['default']['PROP_NOTE2'] 	= $getwodata->PROP_NOTE2;
			$data['default']['PROP_STAT'] 	= $getwodata->PROP_STAT;
			$data['default']['PROP_VALUE'] 	= $getwodata->PROP_VALUE;
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
			$data['default']['PROP_VALUE'] 	= $getwodata->PROP_VALUE;
			$data['default']['WO_VALPPN'] 	= $getwodata->WO_VALPPN;
			$data['default']['WO_VALPPH'] 	= $getwodata->WO_VALPPH;
			$data['default']['PROP_GTOTAL'] 	= $getwodata->PROP_GTOTAL;
			$data['default']['Patt_Year'] 	= $getwodata->Patt_Year;
			$data['default']['Patt_Month'] 	= $getwodata->Patt_Month;
			$data['default']['Patt_Date'] 	= $getwodata->Patt_Date;
			$data['default']['Patt_Number']	= $getwodata->Patt_Number;

			$MenuCode 			= 'MN059';
			$data["MenuCode"] 	= 'MN059';

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getwodata->PROP_NUM;
				$MenuCode 		= 'MN059';
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
		$this->load->model('m_project/m_budprop/m_budprop', '', TRUE);
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

			$PROP_NUM 		= $this->input->post('PROP_NUM');
			$PROP_DATE		= date('Y-m-d',strtotime($this->input->post('PROP_DATE')));
			$PRJCODE 		= $this->input->post('PRJCODE');
			$PROP_STAT 		= $this->input->post('PROP_STAT');
			$WO_CATEG 		= $this->input->post('WO_CATEG');
			$PROP_NOTE2		= addslashes($this->input->post('PROP_NOTE2'));

			if($PROP_STAT == 3)
			{
				// START : SAVE APPROVE HISTORY
					$this->load->model('m_updash/m_updash', '', TRUE);

					$AH_CODE		= $PROP_NUM;
					$AH_APPLEV		= $this->input->post('APP_LEVEL');
					$AH_APPROVER	= $DefEmp_ID;
					$AH_APPROVED	= date('Y-m-d H:i:s');
					$AH_NOTES		= addslashes($this->input->post('PROP_NOTE2'));
					$AH_ISLAST		= $this->input->post('IS_LAST');

					$insAppHist 	= array('AH_CODE'		=> $AH_CODE,
											'AH_APPLEV'		=> $AH_APPLEV,
											'AH_APPROVER'	=> $AH_APPROVER,
											'AH_APPROVED'	=> $AH_APPROVED,
											'AH_NOTES'		=> $AH_NOTES,
											'AH_ISLAST'		=> $AH_ISLAST);
					$this->m_updash->insAppHist($insAppHist);

					$projWOH		= array('PROP_STAT'	=> 7,
											'PROP_NOTE2'	=> $PROP_NOTE2);		// Default ke waiting jika masih ada approver yang lain
					$this->m_budprop->update($PROP_NUM, $projWOH);
				// END : SAVE APPROVE HISTORY

				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "PROP_NUM",
											'DOC_CODE' 		=> $PROP_NUM,
											'DOC_STAT' 		=> 7,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> '',
											'TBLNAME'		=> "tbl_bprop_header");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
			}			
			if($PROP_STAT == 3 && $AH_ISLAST == 1)
			{
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "PROP_NUM",
											'DOC_CODE' 		=> $PROP_NUM,
											'DOC_STAT' 		=> $PROP_STAT,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> '',
											'TBLNAME'		=> "tbl_bprop_header");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS

				$projWOH 		= array('WO_APPROVER'	=> $DefEmp_ID,
										'WO_APPROVED'	=> date('Y-m-d H:i:s'),
										'PROP_NOTE2'		=> addslashes($this->input->post('PROP_NOTE2')),
										'PROP_STAT'		=> $this->input->post('PROP_STAT'));
				$this->m_budprop->update($PROP_NUM, $projWOH);

				// UPDATE JOBDETAIL ITEM
				if($PROP_STAT == 3)
				{
					$this->m_budprop->updateWODet($PROP_NUM, $PRJCODE);
				}
			}

			if($PROP_STAT == 4)
			{
				$this->load->model('m_updash/m_updash', '', TRUE);
				// START : CLEAR HISTORY
					$cllPar = array('AH_CODE' 		=> $PROP_NUM,
									'AH_APPROVER'	=> $DefEmp_ID);
					$this->m_updash->clearHist($cllPar);

					$projWOH 		= array('PROP_NOTE2'		=> $PROP_NOTE2,
											'PROP_STAT'		=> $PROP_STAT);
					$this->m_budprop->update($PROP_NUM, $projWOH);

					// START : UPDATE STATUS
						$completeName 	= $this->session->userdata['completeName'];
						$paramStat 		= array('PM_KEY' 		=> "PROP_NUM",
												'DOC_CODE' 		=> $PROP_NUM,
												'DOC_STAT' 		=> $PROP_STAT,
												'PRJCODE' 		=> $PRJCODE,
												'CREATERNM'		=> '',
												'TBLNAME'		=> "tbl_bprop_header");
						$this->m_updash->updateStatus($paramStat);
					// END : UPDATE STATUS
				// END : CLEAR HISTORY
			}
			elseif($PROP_STAT == 5)
			{
				$this->load->model('m_updash/m_updash', '', TRUE);

				$projWOH 		= array('PROP_NOTE2'		=> $PROP_NOTE2,
										'PROP_STAT'		=> $PROP_STAT);
				$this->m_budprop->update($PROP_NUM, $projWOH);

				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "PROP_NUM",
											'DOC_CODE' 		=> $PROP_NUM,
											'DOC_STAT' 		=> $PROP_STAT,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> '',
											'TBLNAME'		=> "tbl_bprop_header");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
			}

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $PROP_NUM;
				$MenuCode 		= 'MN059';
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
				$parameters 	= array('DOC_CODE' 		=> $PROP_NUM,			// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "WO",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_bprop_header",	// TABLE NAME
										'KEY_NAME'		=> "PROP_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "PROP_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $PROP_STAT,		// TRANSACTION STATUS
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
										'TR_DATE' 		=> $PROP_DATE,
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
				$url	= site_url('c_project/c_budprop/ibx1_er/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			elseif($WO_CATEG == 'SUB')
				$url	= site_url('c_project/c_budprop/s5uB_1nb_5pK5uB/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			else
				$url	= site_url('c_project/c_budprop/s5pK_1nb_5pKa/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
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

		$url			= site_url('c_project/c_budprop/prj180d0blst_5uB/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}

	function prj180d0blst_5uB() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_project/m_budprop/m_budprop', '', TRUE);

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
			$data["secURL"] 	= "c_project/c_budprop/s5uB_1nb_5pK5uB/?id=";

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
		$this->load->model('m_project/m_budprop/m_budprop', '', TRUE);
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
				$data["url_search"] = site_url('c_project/c_budprop/f4n7_5rcH1nB5pK5Ub/?id='.$this->url_encryption_helper->encode_url($PRJCODE));

				//$num_rows 			= $this->m_budprop->count_all_WOInx_sub($PRJCODE, $key);
				//$data["cData"] 		= $num_rows;
				//$data['vData']		= $this->m_budprop->get_all_WOInb_sub($PRJCODE, $start, $end, $key)->result();
			// -------------------- END : SEARCHING METHOD --------------------

			$data['title'] 		= $appName;
			$data['backURL'] 	= site_url('c_project/c_budprop/inbox_sub/');
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
			$url			= site_url('c_project/c_budprop/s5uB_1nb_5pK5uB/?id='.$this->url_encryption_helper->encode_url($collDATA));
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
    		if($TranslCode == 'SPKVal')$SPKVal = $LangTransl;
    		if($TranslCode == 'QtyOpnamed')$QtyOpnamed = $LangTransl;
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

			$columns_valid 	= array("PROP_CODE",
									"WO_CATEG", 
									"PROP_DATE", 
									"PROP_NOTE", 
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
			$num_rows 		= $this->m_budprop->get_AllDataCSC_1n2($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_budprop->get_AllDataLSC_1n2($PRJCODE, $search, $length, $start, $order, $dir);

			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI)
			{
				$PROP_NUM			= $dataI['PROP_NUM'];
				$PROP_CODE		= $dataI['PROP_CODE'];
				$PROP_DATE		= $dataI['PROP_DATE'];
				$WO_ENDD		= $dataI['WO_ENDD'];
				//$PROP_DATEV		= date('d M Y', strtotime($PROP_DATE));
				$PROP_DATEV1		= strftime('%d %b %Y', strtotime($PROP_DATE));
				$WO_ENDDV		= strftime('%d %b %Y', strtotime($WO_ENDD));

				$PROP_DATEV 		= $PROP_DATEV1." - ".$WO_ENDDV;

				$WO_CATEG		= $dataI['WO_CATEG'];
				$PRJCODE		= $dataI['PRJCODE'];
				$PROP_NOTE		= $dataI['PROP_NOTE'];
				$PROP_NOTED		= '';
				if($PROP_NOTE != '')
					$PROP_NOTED	= "$PROP_NOTE";

				$PROP_STAT		= $dataI['PROP_STAT'];
				$WO_REFNO		= $dataI['WO_REFNO'];
				$WO_CREATER		= $dataI['WO_CREATER'];
				$WO_ISCLOSE		= $dataI['WO_ISCLOSE'];
				$JOBCODEID		= $dataI['JOBCODEID'];
				$WO_CDOC		= $dataI['WO_CDOC'];
				$JOBDESC		= $dataI['JOBDESC'];
				$SPLCODE		= $dataI['SPLCODE'];
				$PROP_VALUE		= $dataI['PROP_VALUE'];

				// OPN TOTAL
					$TOTOPNAMN	= 0;
					$sqlTOT_OPN	= "SELECT SUM(A.OPN_AMOUNT) AS TOTOPN_AMN FROM tbl_bprop_detail A
									WHERE A.PROP_NUM = '$PROP_NUM' AND A.PRJCODE = '$PRJCODE'";
					$resTOT_OPN		= $this->db->query($sqlTOT_OPN)->result();
					foreach($resTOT_OPN as $rowTOT_OPN) :
						$TOTOPNAMN	= $rowTOT_OPN->TOTOPN_AMN;
						if($TOTOPNAMN == '')
							$TOTOPNAMN	= 0;
					endforeach;

				$spkD 			= 	"<div style='white-space:nowrap'>
											<strong><i class='fa fa-bullhorn margin-r-5'></i> ".$SPKVal."</strong>
										</div>
									  	<div style='margin-left: 20px;'>
									  		".number_format($PROP_VALUE, 2)."
									  	</div>";

				$opnameD 		= 	"<div style='white-space:nowrap'>
											<strong><i class='fa fa-check-square-o margin-r-5'></i> ".$QtyOpnamed."</strong>
										</div>
									  	<div style='margin-left: 20px;'>
									  		".number_format($TOTOPNAMN, 2)."
									  	</div>";
				
				$SPLDESC		= '';
				$sqlSPLD		= "SELECT SPLDESC FROM tbl_supplier WHERE SPLCODE = '$SPLCODE' LIMIT 1";
				$resSPLD		= $this->db->query($sqlSPLD)->result();
				foreach($resSPLD as $rowSPLD) :
					$SPLDESC	= $rowSPLD->SPLDESC;		
				endforeach;
				
				$JOBDESCD		= "$JOBDESC$PROP_NOTED";
				if($JOBDESCD == '')
				{
					/*$expJOB		= explode("~", $JOBCODEID);
					$JOBCODEID1	= $expJOB[0];
					$JOBDESC1	= '';
					$sqlJOB		= "SELECT JOBDESC FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBCODEID1'";
					$resJOB		= $this->db->query($sqlJOB)->result();
					foreach($resJOB as $rowJOB) :
						$JOBDESC1 = $rowJOB->JOBDESC;		
					endforeach;
					$JOBDESCD	= $JOBDESC1;*/
					$JOBDESCDV 	= "";
				}
				else
				{
					$JOBDESCDV 	= "<div class='text-muted' style='margin-left: 18px'>
										  		".$JOBDESCD."
										  	</div>";
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
				
				$CollID		= "$PRJCODE~$PROP_NUM";
				$secUpd		= site_url('c_project/c_budprop/update_inb_sub/?id='.$this->url_encryption_helper->encode_url($CollID));
				$secPrint	= site_url('c_project/c_budprop/printdocument/?id='.$this->url_encryption_helper->encode_url($PROP_NUM));
				if($WO_CATEG == 'MDR')
					$secPrint	= site_url('c_project/c_budprop/pr1n7d0c_m4d/?id='.$this->url_encryption_helper->encode_url($PROP_NUM));
				else
					$secPrint	= site_url('c_project/c_budprop/pr1n7d0c_e/?id='.$this->url_encryption_helper->encode_url($PROP_NUM));

				$CollID		= "$PROP_NUM~$PRJCODE";
				$secDel_WO 	= base_url().'index.php/c_project/c_budprop/trash_WO/?id='.$PROP_NUM;

				if($PROP_STAT == 1)
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
												<strong>$PROP_CODE</strong><br>
												<strong><i class='fa fa-calendar margin-r-5'></i> ".$Date." </strong>
										  		<div>
											  		<p class='text-muted' style='margin-left: 15px; white-space:nowrap'>
											  			".$PROP_DATEV."
											  		</p>
											  	</div>
											</div>",
										  	"<div style='white-space:nowrap'>
											  	<strong><i class='fa fa-user margin-r-5'></i> ".$SPLDESC." / ".$SPLCODE." </strong><br>
											  	<strong><i class='fa fa-cog margin-r-5'></i> ".$Category." </strong>
										  		<div>
											  		<p class='text-muted' style='margin-left: 18px'>
											  			".$WO_CATEGD."
											  		</p>
											  	</div>
										  	</div>",
										  	"<strong><i class='fa fa-pencil margin-r-5'></i> ".$Description." </strong>
									  		".$JOBDESCDV."
										  	<strong><i class='fa fa-paperclip margin-r-5'></i> No. SK </strong>
									  		<div class='text-muted' style='margin-left: 18px'>
										  		".$WO_REFNO."
										  	</div>",
											"$spkD $opnameD",
											$empName,
											"<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
											$secAction);

				/*$output['data'][] 	= array("<strong>$PROP_CODE</strong>",
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
										  			".$PROP_DATEV."
										  		</p>
										  	</div>
										  	<strong><i class='fa fa-pencil margin-r-5'></i> ".$Description." </strong>
									  		<div class='text-muted' style='margin-left: 18px'>
										  			".$JOBDESCD."
										  	</div>",
										  $empName,
										  "<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
										  $secAction);*/
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

	function update_inb_sub()
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_project/m_budprop/m_budprop', '', TRUE);
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
			$PROP_NUM		= $EXTRACTCOL[1];
			
			$docPatternPosition = 'Especially';
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_project/c_budprop/update_process_inb');

			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();

			$getwodata 						= $this->m_budprop->get_PROP_by_number($PROP_NUM)->row();
			$data['default']['PROP_NUM'] 		= $getwodata->PROP_NUM;
			$data['default']['PROP_CODE'] 	= $getwodata->PROP_CODE;
			$data['default']['PROP_DATE'] 	= $getwodata->PROP_DATE;
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
			$data['default']['PROP_NOTE'] 	= $getwodata->PROP_NOTE;
			$data['default']['PROP_NOTE2'] 	= $getwodata->PROP_NOTE2;
			$data['default']['PROP_STAT'] 	= $getwodata->PROP_STAT;
			$data['default']['PROP_VALUE'] 	= $getwodata->PROP_VALUE;
			$data['default']['PROP_GTOTAL'] 	= $getwodata->PROP_GTOTAL;
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
			$data['default']['PROP_VALUE'] 	= $getwodata->PROP_VALUE;
			$data['default']['WO_VALPPN'] 	= $getwodata->WO_VALPPN;
			$data['default']['WO_VALPPH'] 	= $getwodata->WO_VALPPH;
			$data['default']['PROP_GTOTAL'] 	= $getwodata->PROP_GTOTAL;
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
				$TTR_REFDOC		= $getwodata->PROP_NUM;
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
		$this->load->model('m_project/m_budprop/m_budprop', '', TRUE);
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

		$PROP_NUM		= $_GET['id'];
		$PROP_NUM		= $this->url_encryption_helper->decode_url($PROP_NUM);

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

			$getwodata 					= $this->m_budprop->get_PROP_by_number($PROP_NUM)->row();
			$PRJCODE					= $getwodata->PRJCODE;
			$data['def']['PRJCODE'] 	= $getwodata->PRJCODE;
			$data['def']['WOP_NUM'] 	= $getwodata->PROP_NUM;
			$data['def']['WOP_CODE'] 	= $getwodata->PROP_CODE;

			$num_rows 						= $this->m_budprop->get_WOP_count($PROP_NUM);
			if($num_rows > 0)
			{
				$getwopdata 				= $this->m_budprop->get_WOP_by_number($PROP_NUM)->row();
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

			$MenuCode 			= 'MN059';
			$data["MenuCode"] 	= 'MN059';

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getwodata->PROP_NUM;
				$MenuCode 		= 'MN059';
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
		$this->load->model('m_project/m_budprop/m_budprop', '', TRUE);
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
      	$data['appName'] = $appName;
      	$data['comp_add'] = $therow->comp_add;
		endforeach;
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

		$PROP_NUM		= $_GET['id'];
		$PROP_NUM		= $this->url_encryption_helper->decode_url($PROP_NUM);

		if ($this->session->userdata('login') == TRUE)
		{
			$getwodata 						= $this->m_budprop->get_PROP_by_number($PROP_NUM)->row();
			$data['default']['PROP_NUM'] 		= $getwodata->PROP_NUM;
			$data['default']['PROP_CODE'] 	= $getwodata->PROP_CODE;
			$data['default']['PROP_DATE'] 	= $getwodata->PROP_DATE;
			$data['default']['PRJCODE']		= $getwodata->PRJCODE;
			$data['PRJCODE']				= $getwodata->PRJCODE;
			$PRJCODE 						= $getwodata->PRJCODE;
			$data['default']['PROP_NOTE'] 	= $getwodata->PROP_NOTE;
			$data['default']['PROP_NOTE2'] 	= $getwodata->PROP_NOTE2;
			$data['default']['PROP_STAT'] 	= $getwodata->PROP_STAT;
			$data['default']['PROP_VALUE'] 	= $getwodata->PROP_VALUE;
			$data['default']['PRJNAME'] 	= $getwodata->PRJNAME;
			$data['default']['Patt_Year'] 	= $getwodata->Patt_Year;
			$data['default']['Patt_Month'] 	= $getwodata->Patt_Month;
			$data['default']['Patt_Date'] 	= $getwodata->Patt_Date;
			$data['default']['Patt_Number']	= $getwodata->Patt_Number;

      		$data['countwodet'] = $this->m_budprop->count_WODET_by_number($PROP_NUM);
      		$data['vwodet']   = $this->m_budprop->get_WODET_by_number($PROP_NUM);

			$MenuCode 			= 'MN059';
			$data["MenuCode"] 	= 'MN059';

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getwodata->PROP_NUM;
				$MenuCode 		= 'MN059';
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
			
			// if($WO_CATEG == 'SALT')
			// 	$this->load->view('v_project/v_spk/spk_printdoc_salt', $data);
			// else
			// 	$this->load->view('v_project/v_spk/spk_printdoc_sub', $data);

			$this->load->view('v_project/v_budprop/prop_printdoc', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function pr1n7d0c_m4d()		// Khusus mandor
	{
		$this->load->model('m_project/m_budprop/m_budprop', '', TRUE);
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

		$PROP_NUM		= $_GET['id'];
		$PROP_NUM		= $this->url_encryption_helper->decode_url($PROP_NUM);

		if ($this->session->userdata('login') == TRUE)
		{
			$getwodata 						= $this->m_budprop->get_PROP_by_number($PROP_NUM)->row();
			$data['default']['PROP_NUM'] 		= $getwodata->PROP_NUM;
			$data['default']['PROP_CODE'] 	= $getwodata->PROP_CODE;
			$data['default']['PROP_DATE'] 	= $getwodata->PROP_DATE;
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
			$data['default']['PROP_NOTE'] 	= $getwodata->PROP_NOTE;
			$data['default']['PROP_NOTE2'] 	= $getwodata->PROP_NOTE2;
			$data['default']['PROP_STAT'] 	= $getwodata->PROP_STAT;
			$data['default']['PROP_VALUE'] 	= $getwodata->PROP_VALUE;
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

			$MenuCode 			= 'MN059';
			$data["MenuCode"] 	= 'MN059';

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getwodata->PROP_NUM;
				$MenuCode 		= 'MN059';
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
		$this->load->model('m_project/m_budprop/m_budprop', '', TRUE);
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

		$PROP_NUM		= $_GET['id'];
		$PROP_NUM		= $this->url_encryption_helper->decode_url($PROP_NUM);

		if ($this->session->userdata('login') == TRUE)
		{
			$getwodata 						= $this->m_budprop->get_WOTLS_by_number($PROP_NUM)->row();
			$data['default']['PROP_NUM'] 		= $getwodata->PROP_NUM;
			$data['default']['PROP_CODE'] 	= $getwodata->PROP_CODE;
			$data['default']['PROP_DATE'] 	= $getwodata->PROP_DATE;
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
			$data['default']['PROP_NOTE'] 	= $getwodata->PROP_NOTE;
			$data['default']['PROP_NOTE2'] 	= $getwodata->PROP_NOTE2;
			$data['default']['PROP_STAT'] 	= $getwodata->PROP_STAT;
			$data['default']['PROP_VALUE'] 	= $getwodata->PROP_VALUE;
			$data['default']['WO_MEMO'] 	= $getwodata->WO_MEMO;
			$data['default']['PRJNAME'] 	= $getwodata->PRJNAME;
			//$data['default']['FPA_NUM'] 	= $getwodata->FPA_NUM;
			//$data['default']['FPA_CODE'] 	= $getwodata->FPA_CODE;
			$data['default']['Patt_Year'] 	= $getwodata->Patt_Year;
			$data['default']['Patt_Month'] 	= $getwodata->Patt_Month;
			$data['default']['Patt_Date'] 	= $getwodata->Patt_Date;
			$data['default']['Patt_Number']	= $getwodata->Patt_Number;

			$MenuCode 			= 'MN059';
			$data["MenuCode"] 	= 'MN059';

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getwodata->PROP_NUM;
				$MenuCode 		= 'MN059';
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

		$url			= site_url('c_project/c_budprop/prj180d0blst_4L7/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}

	function prj180d0blst_4L7() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_project/m_budprop/m_budprop', '', TRUE);

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
			$data["secURL"] 	= "c_project/c_budprop/ibx1_er/?id=";

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
		$this->load->model('m_project/m_budprop/m_budprop', '', TRUE);
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
				$data["url_search"] = site_url('c_project/c_budprop/f4n7_5rcH1nB5pK4L7/?id='.$this->url_encryption_helper->encode_url($PRJCODE));

				//$num_rows 			= $this->m_budprop->count_all_WOInx_er($PRJCODE, $key);
				//$data["cData"] 		= $num_rows;
				//$data['vData']		= $this->m_budprop->get_all_WOInb_er($PRJCODE, $start, $end, $key)->result();
			// -------------------- END : SEARCHING METHOD --------------------

			$data['title'] 		= $appName;
			$data['backURL'] 	= site_url('c_project/c_budprop/inbox_er/');
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
			$url			= site_url('c_project/c_budprop/ibx1_er/?id='.$this->url_encryption_helper->encode_url($collDATA));
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
    		if($TranslCode == 'SPKVal')$SPKVal = $LangTransl;
    		if($TranslCode == 'QtyOpnamed')$QtyOpnamed = $LangTransl;
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

			$columns_valid 	= array("PROP_CODE",
									"WO_CATEG", 
									"PROP_DATE", 
									"PROP_NOTE", 
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
			$num_rows 		= $this->m_budprop->get_AllDataCTLS_1n2($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_budprop->get_AllDataLTLS_1n2($PRJCODE, $search, $length, $start, $order, $dir);

			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI)
			{
				$PROP_NUM			= $dataI['PROP_NUM'];
				$PROP_CODE		= $dataI['PROP_CODE'];
				$PROP_DATE		= $dataI['PROP_DATE'];
				$WO_ENDD		= $dataI['WO_ENDD'];
				//$PROP_DATEV		= date('d M Y', strtotime($PROP_DATE));
				$PROP_DATEV1		= strftime('%d %b %Y', strtotime($PROP_DATE));
				$WO_ENDDV		= strftime('%d %b %Y', strtotime($WO_ENDD));

				$PROP_DATEV 		= $PROP_DATEV1." - ".$WO_ENDDV;

				$WO_CATEG		= $dataI['WO_CATEG'];
				$PRJCODE		= $dataI['PRJCODE'];
				$PROP_NOTE		= $dataI['PROP_NOTE'];
				$PROP_NOTED		= '';
				if($PROP_NOTE != '')
					$PROP_NOTED	= "$PROP_NOTE";

				$PROP_STAT		= $dataI['PROP_STAT'];
				$WO_REFNO		= $dataI['WO_REFNO'];
				$WO_CREATER		= $dataI['WO_CREATER'];
				$WO_ISCLOSE		= $dataI['WO_ISCLOSE'];
				$JOBCODEID		= $dataI['JOBCODEID'];
				$WO_CDOC		= $dataI['WO_CDOC'];
				$JOBDESC		= $dataI['JOBDESC'];
				$SPLCODE		= $dataI['SPLCODE'];
    		
				$PROP_VALUE		= $dataI['PROP_VALUE'];

				// OPN TOTAL
					$TOTOPNAMN	= 0;
					$sqlTOT_OPN	= "SELECT SUM(A.OPN_AMOUNT) AS TOTOPN_AMN FROM tbl_bprop_detail A
									WHERE A.PROP_NUM = '$PROP_NUM' AND A.PRJCODE = '$PRJCODE'";
					$resTOT_OPN		= $this->db->query($sqlTOT_OPN)->result();
					foreach($resTOT_OPN as $rowTOT_OPN) :
						$TOTOPNAMN	= $rowTOT_OPN->TOTOPN_AMN;
						if($TOTOPNAMN == '')
							$TOTOPNAMN	= 0;
					endforeach;

				$spkD 			= 	"<div style='white-space:nowrap'>
											<strong><i class='fa fa-bullhorn margin-r-5'></i> ".$SPKVal."</strong>
										</div>
									  	<div style='margin-left: 20px;'>
									  		".number_format($PROP_VALUE, 2)."
									  	</div>";

				$opnameD 		= 	"<div style='white-space:nowrap'>
											<strong><i class='fa fa-check-square-o margin-r-5'></i> ".$QtyOpnamed."</strong>
										</div>
									  	<div style='margin-left: 20px;'>
									  		".number_format($TOTOPNAMN, 2)."
									  	</div>";
				
				$SPLDESC		= '';
				$sqlSPLD		= "SELECT SPLDESC FROM tbl_supplier WHERE SPLCODE = '$SPLCODE' LIMIT 1";
				$resSPLD		= $this->db->query($sqlSPLD)->result();
				foreach($resSPLD as $rowSPLD) :
					$SPLDESC	= $rowSPLD->SPLDESC;		
				endforeach;
				
				$JOBDESCD		= "$JOBDESC$PROP_NOTED";
				if($JOBDESCD == '')
				{
					/*$expJOB		= explode("~", $JOBCODEID);
					$JOBCODEID1	= $expJOB[0];
					$JOBDESC1	= '';
					$sqlJOB		= "SELECT JOBDESC FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBCODEID1'";
					$resJOB		= $this->db->query($sqlJOB)->result();
					foreach($resJOB as $rowJOB) :
						$JOBDESC1 = $rowJOB->JOBDESC;		
					endforeach;
					$JOBDESCD	= $JOBDESC1;*/
					$JOBDESCDV 	= "";
				}
				else
				{
					$JOBDESCDV 	= "<div class='text-muted' style='margin-left: 18px'>
										  		".$JOBDESCD."
										  	</div>";
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
				
				$CollID		= "$PRJCODE~$PROP_NUM";
				$secUpd		= site_url('c_project/c_budprop/up4t_ibx1_er/?id='.$this->url_encryption_helper->encode_url($CollID));
				if($WO_CATEG == 'MDR')
					$secPrint	= site_url('c_project/c_budprop/pr1n7d0c_m4d/?id='.$this->url_encryption_helper->encode_url($PROP_NUM));
				else
					$secPrint	= site_url('c_project/c_budprop/pr1n7d0c_e/?id='.$this->url_encryption_helper->encode_url($PROP_NUM));

				$CollID		= "$PROP_NUM~$PRJCODE";
				$secDel_WO 	= base_url().'index.php/c_project/c_budprop/trash_WO/?id='.$PROP_NUM;

				if($PROP_STAT == 1)
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
												<strong>$PROP_CODE</strong><br>
												<strong><i class='fa fa-calendar margin-r-5'></i> ".$Date." </strong>
										  		<div>
											  		<p class='text-muted' style='margin-left: 15px'>
											  			".$PROP_DATEV."
											  		</p>
											  	</div>
											</div>",
										  	"<div style='white-space:nowrap'>
											  	<strong><i class='fa fa-user margin-r-5'></i> ".$SPLDESC." / ".$SPLCODE." </strong><br>
											  	<strong><i class='fa fa-cog margin-r-5'></i> ".$Category." </strong>
										  		<div>
											  		<p class='text-muted' style='margin-left: 18px'>
											  			".$WO_CATEGD."
											  		</p>
											  	</div>
										  	</div>",
										  	"<strong><i class='fa fa-pencil margin-r-5'></i> ".$Description." </strong>
									  		".$JOBDESCDV."
										  	<strong><i class='fa fa-paperclip margin-r-5'></i> No. SK </strong>
									  		<div class='text-muted' style='margin-left: 18px'>
										  		".$WO_REFNO."
										  	</div>",
											"$spkD $opnameD",
											$empName,
											"<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
											$secAction);

				$noU		= $noU + 1;
			}

			/*$output['data'][] 	= array("<strong>$PROP_CODE</strong>",
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
									  			".$PROP_DATEV."
									  		</p>
									  	</div>
									  	<strong><i class='fa fa-pencil margin-r-5'></i> ".$Description." </strong>
								  		<div class='text-muted' style='margin-left: 18px'>
									  			".$JOBDESCD."
									  	</div>",
									  $empName,
									  "<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
									  $secAction);*/


			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

	function up4t_ibx1_er()
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_project/m_budprop/m_budprop', '', TRUE);
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
			$PROP_NUM		= $EXTRACTCOL[1];
			
			$docPatternPosition = 'Especially';
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_project/c_budprop/update_process_inb');

			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();

			$getwodata 						= $this->m_budprop->get_PROP_by_number($PROP_NUM)->row();
			$data['default']['PROP_NUM'] 		= $getwodata->PROP_NUM;
			$data['default']['PROP_CODE'] 	= $getwodata->PROP_CODE;
			$data['default']['PROP_DATE'] 	= $getwodata->PROP_DATE;
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
			$data['default']['PROP_NOTE'] 	= $getwodata->PROP_NOTE;
			$data['default']['PROP_NOTE2'] 	= $getwodata->PROP_NOTE2;
			$data['default']['PROP_STAT'] 	= $getwodata->PROP_STAT;
			$data['default']['PROP_VALUE'] 	= $getwodata->PROP_VALUE;
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
			$data['default']['PROP_VALUE'] 	= $getwodata->PROP_VALUE;
			$data['default']['WO_VALPPN'] 	= $getwodata->WO_VALPPN;
			$data['default']['WO_VALPPH'] 	= $getwodata->WO_VALPPH;
			$data['default']['PROP_GTOTAL'] 	= $getwodata->PROP_GTOTAL;
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
				$TTR_REFDOC		= $getwodata->PROP_NUM;
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

		$url			= site_url('c_project/c_budprop/r3q_70ls_1a/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}

	function r3q_70ls_1a() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_project/m_budprop/m_budprop', '', TRUE);

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
			$data["secURL"] 	= "c_project/c_budprop/r3q_70ls_1ll1a/?id=";

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
		$this->load->model('m_project/m_budprop/m_budprop', '', TRUE);
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
				$data["url_search"] = site_url('c_project/c_budprop/f4n7_5rcH/?id='.$this->url_encryption_helper->encode_url($PRJCODE));

				//$num_rows 			= $this->m_budprop->count_all_WOTLS($PRJCODE, $key);
				//$data["cData"] 		= $num_rows;
				//$data['vData']		= $this->m_budprop->get_all_WOTLS($PRJCODE, $start, $end, $key)->result();
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
			$data['addURL'] 	= site_url('c_project/c_budprop/r3q_70ls_1dd1a/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['backURL'] 	= site_url('c_project/c_budprop/r3q_70ls_1a/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
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
			$url			= site_url('c_project/c_budprop/r3q_70ls_1ll1a/?id='.$this->url_encryption_helper->encode_url($collDATA));
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
			$num_rows 		= $this->m_budprop->get_AllDataCRT($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_budprop->get_AllDataLRT($PRJCODE, $search, $length,$start);

			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI)
			{
				$PROP_NUM		= $dataI['PROP_NUM'];
				$PROP_CODE	= $dataI['PROP_CODE'];

				$PROP_DATE	= $dataI['PROP_DATE'];
				$PROP_DATEV	= date('d M Y', strtotime($PROP_DATE));

				$PROP_NOTE	= $dataI['PROP_NOTE'];
				$PROP_NOTED	= '';
				if($PROP_NOTE != '')
					$PROP_NOTED	= "$PROP_NOTE";

				$PROP_STAT	= $dataI['PROP_STAT'];
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
				
				$JOBDESCD		= "$JOBDESC$PROP_NOTED";
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

				$CollID		= "$PRJCODE~$PROP_NUM";
				$secUpd		= site_url('c_project/c_budprop/r3q_70ls_up1a/?id='.$this->url_encryption_helper->encode_url($CollID));
				$secPrint	= site_url('c_project/c_budprop/r3q_70ls_pr1n7/?id='.$this->url_encryption_helper->encode_url($PROP_NUM));
				$CollID		= "$PROP_NUM~$PRJCODE";
				$secDel_WO 	= base_url().'index.php/c_project/c_budprop/trash_WO/?id='.$PROP_NUM;

				if($PROP_STAT == 1)
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

				$output['data'][] = array("<label style='white-space:nowrap'>".$dataI['PROP_CODE']."</label>",
										  $PROP_DATEV,
										  "$JOBDESC$PROP_NOTED",
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
		$this->load->model('m_project/m_budprop/m_budprop', '', TRUE);

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
			$data['form_action']= site_url('c_project/c_budprop/add_req_process');
			$data['backURL'] 	= site_url('c_project/c_budprop/gallpR0p/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;

			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();

			$MenuCode 			= 'MN355';
			$data["MenuCode"] 	= 'MN355';
			$data["MenuCode1"] 	= 'MN356';
			$data['vwDocPatt'] 	= $this->m_budprop->getDataDocPat($MenuCode)->result();

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
		$this->load->model('m_project/m_budprop/m_budprop', '', TRUE);
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

			$PROP_STAT 		= $this->input->post('PROP_STAT'); // 1 = New, 2 = confirm, 3 = Close

			$PROP_NUM 		= $this->input->post('PROP_NUM');
			$PROP_CODE 		= $this->input->post('PROP_CODE');
			//setting WO Date
			$PROP_DATE		= date('Y-m-d',strtotime($this->input->post('PROP_DATE')));
			$WO_STARTD		= date('Y-m-d',strtotime($this->input->post('WO_STARTD')));
				$Patt_Year	= date('Y',strtotime($this->input->post('PROP_DATE')));
				$Patt_Month	= date('m',strtotime($this->input->post('PROP_DATE')));
				$Patt_Date	= date('d',strtotime($this->input->post('PROP_DATE')));
			$WO_ENDD		= date('Y-m-d',strtotime($this->input->post('WO_ENDD')));
			$PRJCODE 		= $this->input->post('PRJCODE');
			$SPLCODE 		= $this->input->post('SPLCODE');
			$WO_DEPT 		= '';
			$WO_CATEG 		= $this->input->post('WO_CATEG');
			$WO_TYPE 		= $this->input->post('WO_TYPE');
			$PROP_NOTE 		= addslashes($this->input->post('PROP_NOTE'));
			$PROP_STAT 		= $this->input->post('PROP_STAT');

			//$JOBCODEID	= $this->input->post('JOBCODEID');
			$PRREFNO		= $this->input->post('PR_REFNO');
			$PROP_VALUE		= $this->input->post('PROP_VALUE');
			if($PRREFNO != '')
			{
				$refStep	= 0;
				foreach ($PRREFNO as $PR_REFNO)
				{
					$refStep		= $refStep + 1;
					$COLPR_REFNO	= $PR_REFNO;
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
				$PROP_NUM			= "$Pattern_Code$PRJCODE-$TRXTIME1";
			// END - PEMBENTUKAN GENERATE CODE

			$projWOH 		= array('PROP_NUM' 		=> $PROP_NUM,
									'PROP_CODE' 		=> $PROP_CODE,
									'PROP_DATE'		=> $PROP_DATE,
									'WO_STARTD'		=> $WO_STARTD,
									'WO_ENDD'		=> $WO_ENDD,
									'PRJCODE'		=> $PRJCODE,
									'SPLCODE'		=> $SPLCODE,
									'WO_DEPT'		=> $WO_DEPT,
									'WO_CATEG'		=> $WO_CATEG,
									'WO_TYPE'		=> $WO_TYPE,
									'JOBCODEID'		=> $JOBCODEID,
									'PROP_NOTE'		=> $PROP_NOTE,
									'PROP_VALUE'		=> $PROP_VALUE,
									'PROP_STAT'		=> $PROP_STAT,
									'WO_CREATER'	=> $DefEmp_ID,
									'WO_CREATED'	=> date('Y-m-d H:i:s'),
									'Patt_Year'		=> $Patt_Year,
									'Patt_Month'	=> $Patt_Month,
									'Patt_Date'		=> $Patt_Date,
									'Patt_Number'	=> $this->input->post('Patt_Number'));
			$this->m_budprop->addWOTLS($projWOH);

			foreach($_POST['data'] as $d)
			{
				$d['PROP_NUM']	= $PROP_NUM;
				$WO_TOTAL2	= $d['WO_TOTAL2'];
				if($WO_TOTAL2 == '')
					$d['WO_TOTAL2']	= 0;

				$this->db->insert('tbl_woreq_detail',$d);
			}

			// UPDATE DETAIL
				$this->m_budprop->updateDetWOTLS($PROP_NUM, $PRJCODE, $PROP_DATE);

			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);

				$STAT_BEFORE	= $this->input->post('PROP_STAT');			// IF "ADD" CONDITION ALWAYS = PR_STAT
				$parameters 	= array('DOC_CODE' 		=> $PROP_NUM,			// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "WO",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_woreq_header",	// TABLE NAME
										'KEY_NAME'		=> "PROP_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "PROP_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $PROP_STAT,		// TRANSACTION STATUS
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
				$paramStat 		= array('PM_KEY' 		=> "PROP_NUM",
										'DOC_CODE' 		=> $PROP_NUM,
										'DOC_STAT' 		=> $PROP_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_woreq_header");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $PROP_NUM;
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

			$url			= site_url('c_project/c_budprop/r3q_70ls_1ll1a/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
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
		$this->load->model('m_project/m_budprop/m_budprop', '', TRUE);
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

		$PROP_NUM		= $_GET['id'];
		$PROP_NUM		= $this->url_encryption_helper->decode_url($PROP_NUM);

		if ($this->session->userdata('login') == TRUE)
		{
			$COLLDATA	= $_GET['id'];
			$COLLDATA	= $this->url_encryption_helper->decode_url($COLLDATA);
			$EXTRACTCOL	= explode("~", $COLLDATA);
			$PRJCODE	= $EXTRACTCOL[0];
			$PROP_NUM		= $EXTRACTCOL[1];
			
			$docPatternPosition = 'Especially';
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_project/c_budprop/update_req_process');

			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();

			$getwodata 						= $this->m_budprop->get_WOTLS_by_number($PROP_NUM)->row();
			$data['default']['PROP_NUM'] 		= $getwodata->PROP_NUM;
			$data['default']['PROP_CODE'] 	= $getwodata->PROP_CODE;
			$data['default']['PROP_DATE'] 	= $getwodata->PROP_DATE;
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
			$data['default']['PROP_NOTE'] 	= $getwodata->PROP_NOTE;
			$data['default']['PROP_NOTE2'] 	= $getwodata->PROP_NOTE2;
			$data['default']['PROP_STAT'] 	= $getwodata->PROP_STAT;
			$data['default']['PROP_VALUE'] 	= $getwodata->PROP_VALUE;
			$data['default']['WO_MEMO'] 	= $getwodata->WO_MEMO;
			$data['default']['PRJNAME'] 	= $getwodata->PRJNAME;
			$data['default']['Patt_Year'] 	= $getwodata->Patt_Year;
			$data['default']['Patt_Month'] 	= $getwodata->Patt_Month;
			$data['default']['Patt_Date'] 	= $getwodata->Patt_Date;
			$data['default']['Patt_Number']	= $getwodata->Patt_Number;

			$MenuCode 			= 'MN355';
			$data["MenuCode"] 	= 'MN355';
			$data["MenuCode1"] 	= 'MN356';
			$data['vwDocPatt'] 	= $this->m_budprop->getDataDocPat($MenuCode)->result();

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getwodata->PROP_NUM;
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
		$this->load->model('m_project/m_budprop/m_budprop', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();

			date_default_timezone_set("Asia/Jakarta");

			$PROP_STAT 		= $this->input->post('PROP_STAT'); // 1 = New, 2 = confirm, 3 = Close

			$PROP_NUM 		= $this->input->post('PROP_NUM');
			$PROP_CODE 		= $this->input->post('PROP_CODE');
			//setting WO Date
			$PROP_DATE		= date('Y-m-d',strtotime($this->input->post('PROP_DATE')));
			$WO_STARTD		= date('Y-m-d',strtotime($this->input->post('PROP_DATE')));
				$Patt_Year	= date('Y',strtotime($this->input->post('PROP_DATE')));
				$Patt_Month	= date('m',strtotime($this->input->post('PROP_DATE')));
				$Patt_Date	= date('d',strtotime($this->input->post('PROP_DATE')));
			$WO_ENDD		= date('Y-m-d',strtotime($this->input->post('WO_ENDD')));
			$PRJCODE 		= $this->input->post('PRJCODE');
			$SPLCODE 		= $this->input->post('SPLCODE');
			$WO_DEPT 		= '';
			$WO_CATEG 		= $this->input->post('WO_CATEG');
			$WO_TYPE 		= $this->input->post('WO_TYPE');
			$PROP_NOTE 		= addslashes($this->input->post('PROP_NOTE'));
			$PROP_STAT 		= $this->input->post('PROP_STAT');
			//$JOBCODEID	= $this->input->post('JOBCODEID');
			$PRREFNO		= $this->input->post('PR_REFNO');
			$PROP_VALUE		= $this->input->post('PROP_VALUE');
			if($PRREFNO != '')
			{
				$refStep	= 0;
				foreach ($PRREFNO as $PR_REFNO)
				{
					$refStep		= $refStep + 1;
					$COLPR_REFNO	= $PR_REFNO;
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

			$projWOH 		= array('PROP_NUM' 		=> $PROP_NUM,
									'PROP_CODE' 		=> $PROP_CODE,
									'PROP_DATE'		=> $PROP_DATE,
									'WO_STARTD'		=> $WO_STARTD,
									'WO_ENDD'		=> $WO_ENDD,
									'PRJCODE'		=> $PRJCODE,
									'SPLCODE'		=> $SPLCODE,
									'WO_DEPT'		=> $WO_DEPT,
									'WO_CATEG'		=> $WO_CATEG,
									'WO_TYPE'		=> $WO_TYPE,
									'PROP_VALUE'		=> $PROP_VALUE,
									'JOBCODEID'		=> $JOBCODEID,
									'PROP_NOTE'		=> $PROP_NOTE,
									'PROP_STAT'		=> $PROP_STAT,
									'Patt_Year'		=> $Patt_Year,
									'Patt_Month'	=> $Patt_Month,
									'Patt_Date'		=> $Patt_Date,
									'Patt_Number'	=> $this->input->post('Patt_Number'));
			$this->m_budprop->updateWOTLS($PROP_NUM, $projWOH);

			$this->m_budprop->deleteDetailWOTLS($PROP_NUM);

			foreach($_POST['data'] as $d)
			{
				$WO_TOTAL2	= $d['WO_TOTAL2'];
				if($WO_TOTAL2 == '')
					$d['WO_TOTAL2']	= 0;

				$this->db->insert('tbl_woreq_detail',$d);
			}

			// UPDATE DETAIL
				$this->m_budprop->updateDetWOTLS($PROP_NUM, $PRJCODE, $PROP_DATE);

			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);

				$STAT_BEFORE	= $this->input->post('STAT_BEFORE');		// IF "ADD" CONDITION ALWAYS = PR_STAT
				$parameters 	= array('DOC_CODE' 		=> $PROP_NUM,			// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "WO",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_bprop_header",	// TABLE NAME
										'KEY_NAME'		=> "PROP_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "PROP_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $PROP_STAT,		// TRANSACTION STATUS
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
				$paramStat 		= array('PM_KEY' 		=> "PROP_NUM",
										'DOC_CODE' 		=> $PROP_NUM,
										'DOC_STAT' 		=> $PROP_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_woreq_header");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $PROP_NUM;
				$MenuCode 		= 'MN059';
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

			$url			= site_url('c_project/c_budprop/r3q_70ls_1ll1a/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
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

		$url			= site_url('c_project/c_budprop/pR7_l5t_x1/?id='.$this->url_encryption_helper->encode_url($appName));
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
			$data["secURL"] 	= "c_project/c_budprop/r3q_70ls_1nb_a/?id=";

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
		$this->load->model('m_project/m_budprop/m_budprop', '', TRUE);

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
				$data["url_search"] = site_url('c_project/c_budprop/f4n7_5rcH1nB/?id='.$this->url_encryption_helper->encode_url($PRJCODE));

				//$num_rows 			= $this->m_budprop->count_all_WOTLSInx_er($PRJCODE, $key, $DefEmp_ID);
				//$data["cData"] 		= $num_rows;
				//$data['vData']		= $this->m_budprop->get_all_WOTLSInb_er($PRJCODE, $start, $end, $key, $DefEmp_ID)->result();
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
			$data['backURL'] 	= site_url('c_project/c_budprop/inbox_er/');
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
			$url			= site_url('c_project/c_budprop/r3q_70ls_1nb_a/?id='.$this->url_encryption_helper->encode_url($collDATA));
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
			$num_rows 		= $this->m_budprop->get_AllDataCRT_1n2($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_budprop->get_AllDataLRT_1n2($PRJCODE, $search, $length,$start);

			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI)
			{
				$PROP_NUM		= $dataI['PROP_NUM'];
				$PROP_CODE	= $dataI['PROP_CODE'];

				$PROP_DATE	= $dataI['PROP_DATE'];
				$PROP_DATEV	= date('d M Y', strtotime($PROP_DATE));

				$PROP_NOTE	= $dataI['PROP_NOTE'];
				$PROP_NOTED	= '';
				if($PROP_NOTE != '')
					$PROP_NOTED	= "$PROP_NOTE";

				$PROP_STAT	= $dataI['PROP_STAT'];
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
				
				$JOBDESCD		= "$JOBDESC$PROP_NOTED";
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

				$CollID		= "$PRJCODE~$PROP_NUM";
				$secUpd		= site_url('c_project/c_budprop/r3q_70ls_1nbup4t/?id='.$this->url_encryption_helper->encode_url($CollID));
				$secPrint	= site_url('c_project/c_budprop/r3q_70ls_pr1n7/?id='.$this->url_encryption_helper->encode_url($PROP_NUM));
				$CollID		= "$PROP_NUM~$PRJCODE";
				$secDel_PO 	= base_url().'index.php/c_project/c_budprop/trash_PO/?id='.$PROP_NUM;

				$secAction	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
							   <label style='white-space:nowrap'>
							   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
									<i class='glyphicon glyphicon-pencil'></i>
							   </a>
							   <a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printDocument(".$noU.")'>
							   		<i class='glyphicon glyphicon-print'></i>
							   </a>
							   </label>";

				$output['data'][] = array($dataI['PROP_CODE'],
										  $PROP_DATEV,
										  "$JOBDESC$PROP_NOTED",
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
		$this->load->model('m_project/m_budprop/m_budprop', '', TRUE);
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
			$PROP_NUM		= $EXTRACTCOL[1];
			
			$docPatternPosition = 'Especially';
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_project/c_budprop/update_req_process_inb');

			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();

			$getwodata 						= $this->m_budprop->get_WOTLS_by_number($PROP_NUM)->row();
			$data['default']['PROP_NUM'] 		= $getwodata->PROP_NUM;
			$data['default']['PROP_CODE'] 	= $getwodata->PROP_CODE;
			$data['default']['PROP_DATE'] 	= $getwodata->PROP_DATE;
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
			$data['default']['PROP_NOTE'] 	= $getwodata->PROP_NOTE;
			$data['default']['PROP_NOTE2'] 	= $getwodata->PROP_NOTE2;
			$data['default']['PROP_STAT'] 	= $getwodata->PROP_STAT;
			$data['default']['PROP_VALUE'] 	= $getwodata->PROP_VALUE;
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
				$TTR_REFDOC		= $getwodata->PROP_NUM;
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
		$this->load->model('m_project/m_budprop/m_budprop', '', TRUE);
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

			$PROP_NUM 		= $this->input->post('PROP_NUM');
			$PRJCODE 		= $this->input->post('PRJCODE');
			$PROP_STAT 		= $this->input->post('PROP_STAT');
			
			$AH_CODE		= $PROP_NUM;
  			$AH_APPLEV		= $this->input->post('APP_LEVEL');
  			$AH_APPROVER	= $DefEmp_ID;
  			$AH_APPROVED	= date('Y-m-d H:i:s');
  			$AH_NOTES		= addslashes($this->input->post('PROP_NOTE2'));
  			$AH_ISLAST		= $this->input->post('IS_LAST');

			if($PROP_STAT == 3)
			{
        // START : SAVE APPROVE HISTORY

  				$AH_CODE		= $PROP_NUM;
  				$AH_APPLEV		= $this->input->post('APP_LEVEL');
  				$AH_APPROVER	= $DefEmp_ID;
  				$AH_APPROVED	= date('Y-m-d H:i:s');
  				$AH_NOTES		= $this->input->post('PROP_NOTE2');
  				$AH_ISLAST		= $this->input->post('IS_LAST');

  				$insAppHist 	= array('AH_CODE'		=> $AH_CODE,
  										'AH_APPLEV'		=> $AH_APPLEV,
  										'AH_APPROVER'	=> $AH_APPROVER,
  										'AH_APPROVED'	=> $AH_APPROVED,
  										'AH_NOTES'		=> $AH_NOTES,
  										'AH_ISLAST'		=> $AH_ISLAST);
  				$this->m_updash->insAppHist($insAppHist);

  			// END : SAVE APPROVE HISTORY

				$projWOH		= array('PROP_STAT'	=> 7);		// Default ke waiting jika masih ada approver yang lain
				$this->m_budprop->updateWOTLS($PROP_NUM, $projWOH);
			}
			else
			{
				$projWOH		= array('PROP_STAT'	=> $PROP_STAT);		// Default ke waiting jika masih ada approver yang lain
				$this->m_budprop->updateWOTLS($PROP_NUM, $projWOH);
			}

			if($PROP_STAT == 3 && $AH_ISLAST == 1)
			{
				$projWOH 		= array('WO_APPROVER'	=> $DefEmp_ID,
										'WO_APPROVED'	=> date('Y-m-d H:i:s'),
										'PROP_NOTE2'		=> addslashes($this->input->post('PROP_NOTE2')),
										'PROP_STAT'		=> $PROP_STAT);
				$this->m_budprop->updateWOTLS($PROP_NUM, $projWOH);

				// UPDATE JOBDETAIL ITEM
				// if($PROP_STAT == 3)
				// {
				// 	//$this->m_budprop->updateWOTLSDet($PROP_NUM, $PRJCODE); hidden by DIAN on Aug/7/18
				// }

				// START : UPDATE TO TRANS-COUNT
					$this->load->model('m_updash/m_updash', '', TRUE);

					$STAT_BEFORE	= $this->input->post('STAT_BEFORE');		// IF "ADD" CONDITION ALWAYS = PR_STAT
					$parameters 	= array('DOC_CODE' 		=> $PROP_NUM,			// TRANSACTION CODE
											'PRJCODE' 		=> $PRJCODE,		// PROJECT
											'TR_TYPE'		=> "WO",			// TRANSACTION TYPE
											'TBL_NAME' 		=> "tbl_woreq_header",	// TABLE NAME
											'KEY_NAME'		=> "PROP_NUM",		// KEY OF THE TABLE
											'STAT_NAME' 	=> "PROP_STAT",		// NAMA FIELD STATUS
											'STATDOC' 		=> $PROP_STAT,		// TRANSACTION STATUS
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
					$paramStat 		= array('PM_KEY' 		=> "PROP_NUM",
											'DOC_CODE' 		=> $PROP_NUM,
											'DOC_STAT' 		=> $PROP_STAT,
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
					$paramStat 		= array('PM_KEY' 		=> "PROP_NUM",
											'DOC_CODE' 		=> $PROP_NUM,
											'DOC_STAT' 		=> 7,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> $completeName,
											'TBLNAME'		=> "tbl_woreq_header");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
			}

			// START : CLEAR HISTORY
				$this->load->model('m_updash/m_updash', '', TRUE);
				if($PROP_STAT == 4)
				{
					$cllPar = array('AH_CODE' 		=> $PROP_NUM,
									'AH_APPROVER'	=> $DefEmp_ID);
					$this->m_updash->clearHist($cllPar);

					// START : UPDATE STATUS
						$completeName 	= $this->session->userdata['completeName'];
						$paramStat 		= array('PM_KEY' 		=> "PROP_NUM",
												'DOC_CODE' 		=> $PROP_NUM,
												'DOC_STAT' 		=> $PROP_STAT,
												'PRJCODE' 		=> $PRJCODE,
												'CREATERNM'		=> $completeName,
												'TBLNAME'		=> "tbl_woreq_header");
						$this->m_updash->updateStatus($paramStat);
					// END : UPDATE STATUS
				}
			// END : CLEAR HISTORY

      if($PROP_STAT == 5)
      {
        $cllPar = array('AH_CODE' 		=> $PROP_NUM,
                'AH_APPROVER'	=> $DefEmp_ID);

        // START : UPDATE STATUS
          $completeName 	= $this->session->userdata['completeName'];
          $paramStat 		= array('PM_KEY' 		=> "PROP_NUM",
                      'DOC_CODE' 		=> $PROP_NUM,
                      'DOC_STAT' 		=> $PROP_STAT,
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
				$TTR_REFDOC		= $PROP_NUM;
				$MenuCode 		= 'MN059';
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

			$url			= site_url('c_project/c_budprop/r3q_70ls_1nb_a/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
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

			$url		= site_url('c_project/c_budprop/ll_4p1/?id='.$this->url_encryption_helper->encode_url($collData));
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
			$num_rows 		= $this->m_budprop->get_AllDataITMC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			
			$query 			= $this->m_budprop->get_AllDataITML($PRJCODE, $search, $length, $start, $order, $dir);
								
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
										FROM tbl_bprop_detail A
											INNER JOIN tbl_bprop_header B ON A.PROP_NUM = B.PROP_NUM
												AND B.PRJCODE = '$PRJCODE'
										WHERE A.ITM_CODE = '$ITM_CODE' AND A.PRJCODE = '$PRJCODE'
											AND A.JOBCODEID = '$JOBCODEID' AND B.PROP_STAT IN (2)";
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
						$JOBDESC1 	= wordwrap($JOBDESC, 60, "<br>", TRUE);
						$STATCOL	= 'primary';
						$CELL_COL	= "style='font-weight:bold; white-space:nowrap'";
					}
					else
					{
						$disabledB	= 0;
						$JOBDESC1 	= wordwrap($JOBDESC, 60, "<br>", TRUE);
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
				$JobView		= "$spaceLev $JOBCODEID $JOBDESC1";

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

  	function get_AllDataM() // GOOD
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
			$num_rows 		= $this->m_budprop->get_AllDataITMMC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			
			$query 			= $this->m_budprop->get_AllDataITMML($PRJCODE, $search, $length, $start, $order, $dir);
								
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
				$JOBPARENT 		= $dataI['JOBPARENT'];
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
										FROM tbl_bprop_detail A
											INNER JOIN tbl_bprop_header B ON A.PROP_NUM = B.PROP_NUM
												AND B.PRJCODE = '$PRJCODE'
										WHERE A.ITM_CODE = '$ITM_CODE' AND A.PRJCODE = '$PRJCODE'
											AND A.JOBCODEID = '$JOBCODEID' AND B.PROP_STAT IN (2)";
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
						$JOBDESC1 	= wordwrap($JOBDESC, 60, "<br>", TRUE);
						$STATCOL	= 'primary';
						$CELL_COL	= "style='font-weight:bold; white-space:nowrap'";
					}
					else
					{
						$JOBDESC1 	= wordwrap($JOBDESC, 60, "<br>", TRUE);
						$STATCOL	= 'success';
						$CELL_COL	= "style='white-space:nowrap'";
						$disabledB	= 0;
					}

				$JOBPARDESC = "";
				$sqlJDP 	= "SELECT JOBDESC FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBPARENT' AND PRJCODE = '$PRJCODE' LIMIT 1";
				$resJDP 	= $this->db->query($sqlJDP)->result();
				foreach($resJDP as $rowJDP) :
					$JOBPARDESC = $rowJDP->JOBDESC;
				endforeach;
				$JOBPARDESC = wordwrap($JOBPARDESC, 60, "<br>", TRUE);

				$ACC_ID_UM		= "";
				$s_ACCID		= "SELECT ACC_ID_UM FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' LIMIT 1";
				$r_ACCID		= $this->db->query($s_ACCID)->result();
				foreach($r_ACCID as $rw_ACCID) :
					$ACC_ID_UM	= $rw_ACCID->ACC_ID_UM;
				endforeach;

				// SPACE
					$spaceLev 		= "";

				// OTHER SETT
					if($disabledB == 0)
					{
						$chkBox	= "<input type='checkbox' name='chk1' value='".$JOBCODEDET."|".$JOBCODEID."|".$JOBCODE."|".$PRJCODE."|".$ITM_CODE."|".$JOBDESC."|".$serialNumber."|".$ITM_UNIT."|".$ITM_PRICE."|".$ITM_VOLM_QTY."|".$ITM_VOLM_AMN."|".$ITM_USED."|".$ITM_USED_AM."|".$tempTotMax."|".$PO_VOLM."|".$PO_AMOUNT."|".$WO_QTY."|".$WO_AMOUNT."|".$OPN_QTY."|".$OPN_AMOUNT."|".$TOT_USED_QTY."|".$TOT_USED_AMN."|".$ITM_STOCK."|".$ITM_STOCK_AM."|".$ITM_REMVOL."|".$ITM_REMAMN."|".$JOBPARDESC."|".$ACC_ID_UM."' onClick='pickThis1(this);'/>";
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
				$JobView		= "$JOBCODEID $JOBDESC1";

				$output['data'][] 	= array($chkBox,
											"<div style='white-space:nowrap'>
												".$JobView."
											</div>
										  	<div style='margin-left: 18px; font-style: italic'>
										  		<strong><i class='fa fa-feed margin-r-5'></i> ".$JOBPARDESC."</strong>
										  	</div>",
											"<div style='text-align:center;'><span ".$CELL_COL.">".$ITM_UNIT."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".number_format($ITM_BUDGV, 2)."</span></div>",
											"<div style='text-align:right'><span ".$CELL_COL.">".number_format($REQ_VOLMV, 2).$TOTWOQTY_V."</span></div>",
											//"<div style='text-align:right;'><span ".$CELL_COL.">".number_format($PO_VOLMV, 2)."</span></div>",
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

  	function get_AllDataS() // GOOD
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
			$num_rows 		= $this->m_budprop->get_AllDataITMSC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			
			$query 			= $this->m_budprop->get_AllDataITMSL($PRJCODE, $search, $length, $start, $order, $dir);
								
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
				$JOBPARENT 		= $dataI['JOBPARENT'];
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
										FROM tbl_bprop_detail A
											INNER JOIN tbl_bprop_header B ON A.PROP_NUM = B.PROP_NUM
												AND B.PRJCODE = '$PRJCODE'
										WHERE A.ITM_CODE = '$ITM_CODE' AND A.PRJCODE = '$PRJCODE'
											AND A.JOBCODEID = '$JOBCODEID' AND B.PROP_STAT IN (2)";
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
						$JOBDESC1 	= wordwrap($JOBDESC, 60, "<br>", TRUE);
						$STATCOL	= 'primary';
						$CELL_COL	= "style='font-weight:bold; white-space:nowrap'";
					}
					else
					{
						$JOBDESC1 	= wordwrap($JOBDESC, 60, "<br>", TRUE);
						$STATCOL	= 'success';
						$CELL_COL	= "style='white-space:nowrap'";
						$disabledB	= 0;
					}

				$JOBPARDESC = "";
				$sqlJDP 	= "SELECT JOBDESC FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBPARENT' AND PRJCODE = '$PRJCODE' LIMIT 1";
				$resJDP 	= $this->db->query($sqlJDP)->result();
				foreach($resJDP as $rowJDP) :
					$JOBPARDESC = $rowJDP->JOBDESC;
				endforeach;
				$JOBPARDESC = wordwrap($JOBPARDESC, 60, "<br>", TRUE);

				$ACC_ID_UM		= "";
				$s_ACCID		= "SELECT ACC_ID_UM FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' LIMIT 1";
				$r_ACCID		= $this->db->query($s_ACCID)->result();
				foreach($r_ACCID as $rw_ACCID) :
					$ACC_ID_UM	= $rw_ACCID->ACC_ID_UM;
				endforeach;

				// SPACE
					$spaceLev 		= "";

				// OTHER SETT
					if($disabledB == 0)
					{
						$chkBox	= "<input type='checkbox' name='chk1' value='".$JOBCODEDET."|".$JOBCODEID."|".$JOBCODE."|".$PRJCODE."|".$ITM_CODE."|".$JOBDESC."|".$serialNumber."|".$ITM_UNIT."|".$ITM_PRICE."|".$ITM_VOLM_QTY."|".$ITM_VOLM_AMN."|".$ITM_USED."|".$ITM_USED_AM."|".$tempTotMax."|".$PO_VOLM."|".$PO_AMOUNT."|".$WO_QTY."|".$WO_AMOUNT."|".$OPN_QTY."|".$OPN_AMOUNT."|".$TOT_USED_QTY."|".$TOT_USED_AMN."|".$ITM_STOCK."|".$ITM_STOCK_AM."|".$ITM_REMVOL."|".$ITM_REMAMN."|".$JOBPARDESC."|".$ACC_ID_UM."' onClick='pickThis1(this);'/>";
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
				$JobView		= "$JOBCODEID $JOBDESC1";

				$output['data'][] 	= array($chkBox,
											"<div style='white-space:nowrap'>
												".$JobView."
											</div>
										  	<div style='margin-left: 18px; font-style: italic'>
										  		<strong><i class='fa fa-feed margin-r-5'></i> ".$JOBPARDESC."</strong>
										  	</div>",
											"<div style='text-align:center;'><span ".$CELL_COL.">".$ITM_UNIT."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".number_format($ITM_BUDGV, 2)."</span></div>",
											"<div style='text-align:right'><span ".$CELL_COL.">".number_format($REQ_VOLMV, 2).$TOTWOQTY_V."</span></div>",
											//"<div style='text-align:right;'><span ".$CELL_COL.">".number_format($PO_VOLMV, 2)."</span></div>",
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

  	function get_AllDataU() // GOOD
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
			$num_rows 		= $this->m_budprop->get_AllDataITMUC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			
			$query 			= $this->m_budprop->get_AllDataITMUL($PRJCODE, $search, $length, $start, $order, $dir);
								
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
				$JOBPARENT 		= $dataI['JOBPARENT'];
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

				$ITM_LASTP 		= $dataI['ITM_LASTP'];

				// IS LAST SETT
					if($ISLAST == 0)	// HEADER
					{
						$disabledB	= 1;
						$JOBDESC1 	= wordwrap($JOBDESC, 60, "<br>", TRUE);
						$STATCOL	= 'primary';
						$CELL_COL	= "style='font-weight:bold; white-space:nowrap'";
					}
					else
					{
						$JOBDESC1 	= wordwrap($JOBDESC, 60, "<br>", TRUE);
						$STATCOL	= 'success';
						$CELL_COL	= "style='white-space:nowrap'";
						$disabledB	= 0;
					}

				$JOBPARDESC = "";
				$sqlJDP 	= "SELECT JOBDESC FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBPARENT' AND PRJCODE = '$PRJCODE' LIMIT 1";
				$resJDP 	= $this->db->query($sqlJDP)->result();
				foreach($resJDP as $rowJDP) :
					$JOBPARDESC = $rowJDP->JOBDESC;
				endforeach;
				$JOBPARDESC = wordwrap($JOBPARDESC, 60, "<br>", TRUE);

				$ACC_ID_UM		= "";
				$s_ACCID		= "SELECT ACC_ID_UM FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' LIMIT 1";
				$r_ACCID		= $this->db->query($s_ACCID)->result();
				foreach($r_ACCID as $rw_ACCID) :
					$ACC_ID_UM	= $rw_ACCID->ACC_ID_UM;
				endforeach;

				// SPACE
					$spaceLev 		= "";

				// OTHER SETT
					if($disabledB == 0)
					{
						$chkBox	= "<input type='checkbox' name='chk1' value='".$JOBCODEDET."|".$JOBCODEID."|".$JOBCODE."|".$PRJCODE."|".$ITM_CODE."|".$JOBDESC."|".$serialNumber."|".$ITM_UNIT."|".$ITM_PRICE."|".$ITM_VOLM_QTY."|".$ITM_VOLM_AMN."|".$ITM_USED."|".$ITM_USED_AM."|".$tempTotMax."|".$PO_VOLM."|".$PO_AMOUNT."|".$WO_QTY."|".$WO_AMOUNT."|".$OPN_QTY."|".$OPN_AMOUNT."|".$TOT_USED_QTY."|".$TOT_USED_AMN."|".$ITM_STOCK."|".$ITM_STOCK_AM."|".$ITM_REMVOL."|".$ITM_REMAMN."|".$JOBPARDESC."|".$ACC_ID_UM."|".$ITM_LASTP."' onClick='pickThis1(this);'/>";
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
				$JobView		= "$JOBCODEID $JOBDESC1";

				$output['data'][] 	= array($chkBox,
											"<div style='white-space:nowrap'>
												".$JobView."
											</div>
										  	<div style='margin-left: 18px; font-style: italic'>
										  		<strong><i class='fa fa-feed margin-r-5'></i> ".$JOBPARDESC."</strong>
										  	</div>",
											"<div style='text-align:center;'><span ".$CELL_COL.">".$ITM_UNIT."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".number_format($ITM_BUDGV, 2)."</span></div>",
											"<div style='text-align:right'><span ".$CELL_COL.">".number_format($REQ_VOLMV, 2).$TOTWOQTY_V."</span></div>",
											//"<div style='text-align:right;'><span ".$CELL_COL.">".number_format($PO_VOLMV, 2)."</span></div>",
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

  	function get_AllDataOVH() // GOOD
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
			$num_rows 		= $this->m_budprop->get_AllDataITMOVHC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			
			$query 			= $this->m_budprop->get_AllDataITMOVHL($PRJCODE, $search, $length, $start, $order, $dir);
								
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
				$JOBPARENT 		= $dataI['JOBPARENT'];
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

				$ITM_LASTP 		= $dataI['ITM_LASTP'];

				// IS LAST SETT
					if($ISLAST == 0)	// HEADER
					{
						$disabledB	= 1;
						$JOBDESC1 	= wordwrap($JOBDESC, 60, "<br>", TRUE);
						$STATCOL	= 'primary';
						$CELL_COL	= "style='font-weight:bold; white-space:nowrap'";
					}
					else
					{
						$JOBDESC1 	= wordwrap($JOBDESC, 60, "<br>", TRUE);
						$STATCOL	= 'success';
						$CELL_COL	= "style='white-space:nowrap'";
						$disabledB	= 0;
					}

				$JOBPARDESC = "";
				$sqlJDP 	= "SELECT JOBDESC FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBPARENT' AND PRJCODE = '$PRJCODE' LIMIT 1";
				$resJDP 	= $this->db->query($sqlJDP)->result();
				foreach($resJDP as $rowJDP) :
					$JOBPARDESC = $rowJDP->JOBDESC;
				endforeach;
				$JOBPARDESC = wordwrap($JOBPARDESC, 60, "<br>", TRUE);

				$ACC_ID_UM		= "";
				$s_ACCID		= "SELECT ACC_ID_UM FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' LIMIT 1";
				$r_ACCID		= $this->db->query($s_ACCID)->result();
				foreach($r_ACCID as $rw_ACCID) :
					$ACC_ID_UM	= $rw_ACCID->ACC_ID_UM;
				endforeach;

				// SPACE
					$spaceLev 		= "";

				// OTHER SETT
					if($disabledB == 0)
					{
						$chkBox	= "<input type='checkbox' name='chk1' value='".$JOBCODEDET."|".$JOBCODEID."|".$JOBCODE."|".$PRJCODE."|".$ITM_CODE."|".$JOBDESC."|".$serialNumber."|".$ITM_UNIT."|".$ITM_PRICE."|".$ITM_VOLM_QTY."|".$ITM_VOLM_AMN."|".$ITM_USED."|".$ITM_USED_AM."|".$tempTotMax."|".$PO_VOLM."|".$PO_AMOUNT."|".$WO_QTY."|".$WO_AMOUNT."|".$OPN_QTY."|".$OPN_AMOUNT."|".$TOT_USED_QTY."|".$TOT_USED_AMN."|".$ITM_STOCK."|".$ITM_STOCK_AM."|".$ITM_REMVOL."|".$ITM_REMAMN."|".$JOBPARDESC."|".$ACC_ID_UM."|".$ITM_LASTP."' onClick='pickThis1(this);'/>";
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
				$JobView		= "$JOBCODEID $JOBDESC1";

				$output['data'][] 	= array($chkBox,
											"<div style='white-space:nowrap'>
												".$JobView."
											</div>
										  	<div style='margin-left: 18px; font-style: italic'>
										  		<strong><i class='fa fa-feed margin-r-5'></i> ".$JOBPARDESC."</strong>
										  	</div>",
											"<div style='text-align:center;'><span ".$CELL_COL.">".$ITM_UNIT."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".number_format($ITM_BUDGV, 2)."</span></div>",
											"<div style='text-align:right'><span ".$CELL_COL.">".number_format($REQ_VOLMV, 2).$TOTWOQTY_V."</span></div>",
											//"<div style='text-align:right;'><span ".$CELL_COL.">".number_format($PO_VOLMV, 2)."</span></div>",
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

	function cancelItem()
	{
		date_default_timezone_set("Asia/Jakarta");

		$collID 	= $_POST['collID'];
		$colExpl	= explode("~", $collID);
		$PROP_NUM     = $colExpl[1];
		$PRJCODE    = $colExpl[2];
		$ITM_CODE   = $colExpl[3];
		$ITM_NAME   = $colExpl[4];
		$JOBPARDESC = $colExpl[5];
		$WO_VOLM    = $colExpl[6];
		$OPN_VOLM   = $colExpl[7];
		$REM_VOLWO  = $colExpl[8];
		$ITM_UNIT   = $colExpl[9];
		$PROP_ID   	= $colExpl[10];
		$WO_CVOL   	= $colExpl[11];		// Vol. yang akan dibatalkan

		$s_01 			= "SELECT JOBCODEID, WO_VOLM, ITM_PRICE, WO_TOTAL, OPN_VOLM, OPN_AMOUNT, TAXPERC1, TAXPERC2
							FROM tbl_bprop_detail
							WHERE PROP_ID = $PROP_ID AND ITM_CODE = '$ITM_CODE' AND PROP_NUM = '$PROP_NUM' AND PRJCODE = '$PRJCODE'";
		$r_01 			= $this->db->query($s_01)->result();
		foreach($r_01 as $rw_01) :
			$JOBCODEID 	= $rw_01->JOBCODEID;
			$WO_VOLM 	= $rw_01->WO_VOLM;
			$WO_VOLMN 	= $WO_VOLM - $WO_CVOL;				// VOLUME BARU

			$ITM_PRICE 	= $rw_01->ITM_PRICE;
			$WO_CTOTAL 	= $WO_CVOL * $ITM_PRICE;			// NILAI PEMBATALAN

			$WO_TOTAL 	= $rw_01->WO_TOTAL;
			$WO_TOTALN 	= $WO_TOTAL - $WO_CTOTAL;			// NILAI WO BARU PER ITEM

			$TAXPERC1 	= $rw_01->TAXPERC1;
			$TAXPERC2 	= $rw_01->TAXPERC2;

			$TAXPRICE1 	= $TAXPERC1 * $WO_TOTALN / 100;
			$TAXPRICE2 	= $TAXPERC2 * $WO_TOTALN / 100;

			$WO_TOTAL2 	= $WO_TOTALN + $TAXPRICE1 - $TAXPRICE2;
			// UPDATE STATUS SPK
				$s_02 	= 	"UPDATE tbl_bprop_detail SET WO_VOLM = $WO_VOLMN, WO_TOTAL = $WO_TOTALN, WO_CVOL = WO_CVOL + $WO_CVOL,
									WO_CAMN = WO_CAMN + $WO_CTOTAL,TAXPRICE1 = $TAXPRICE1, TAXPRICE2 = $TAXPRICE2, WO_TOTAL2 = $WO_TOTAL2
								WHERE PROP_ID = $PROP_ID AND ITM_CODE = '$ITM_CODE' AND PROP_NUM = '$PROP_NUM' AND PRJCODE = '$PRJCODE'";
				$this->db->query($s_02);

			// PENGEMBALIAN SISA VOLUME KE BUDGET
				$s_03 	= 	"UPDATE tbl_joblist_detail SET REQ_VOLM = REQ_VOLM - $WO_CVOL, REQ_AMOUNT = REQ_AMOUNT - $WO_CTOTAL
								WHERE JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
				$this->db->query($s_03);

			// DELETE IF WO_VOLM = 0
				$s_04 	= 	"DELETE FROM tbl_bprop_detail WHERE ITM_CODE = '$ITM_CODE' AND PROP_NUM = '$PROP_NUM' AND PRJCODE = '$PRJCODE'";
				//$this->db->query($s_04);

			// ADD HISTORY
				$Emp_ID = $this->session->userdata['Emp_ID'];
				$s_05 	= 	"INSERT INTO tbl_bprop_detail_canc (PROP_ID, PROP_NUM, PROP_CODE, PROP_DATE, PRJCODE, WO_REFNO, JOBCODEDET, JOBCODEID, ITM_CODE, SNCODE, ITM_UNIT, 
								WO_VOLM, ITM_PRICE, WO_DISC, WO_DISCP, WO_TOTAL, WO_CVOL, WO_CAMN, WO_DESC, TAXCODE1, TAXPERC1, TAXPRICE1,
								TAXCODE2, TAXPERC2, TAXPRICE2, WO_TOTAL2, ITM_BUDG_VOL, ITM_BUDG_AMN, OPN_VOLM, OPN_VOLM1, OPN_AMOUNT,
								WO_VOLMB, WO_VOLMB1, WO_TOTALB, ISCLOSE, CANC_EMP)
							SELECT PROP_ID, PROP_NUM, PROP_CODE, PROP_DATE, PRJCODE, WO_REFNO, JOBCODEDET, JOBCODEID, ITM_CODE, SNCODE, ITM_UNIT, 
								WO_VOLM, ITM_PRICE, WO_DISC, WO_DISCP, WO_TOTAL, WO_CVOL, WO_CAMN, WO_DESC, TAXCODE1, TAXPERC1, TAXPRICE1,
								TAXCODE2, TAXPERC2, TAXPRICE2, WO_TOTAL2, ITM_BUDG_VOL, ITM_BUDG_AMN, OPN_VOLM, OPN_VOLM1, OPN_AMOUNT,
								WO_VOLMB, WO_VOLMB1, WO_TOTALB, ISCLOSE, '$Emp_ID'
								FROM tbl_bprop_detail WHERE PROP_ID = $PROP_ID AND ITM_CODE = '$ITM_CODE' AND PROP_NUM = '$PROP_NUM' AND PRJCODE = '$PRJCODE'";
				$this->db->query($s_05);
		endforeach;

		// RECOUNT AMOUNT FOR THIS SPK
		$TOT_WO 		= 0;
		$TOT_TAX1 		= 0;
		$TOT_TAX2 		= 0;
		$TOT_CAMN 		= 0;
		$s_06 			= "SELECT WO_TOTAL, TAXPERC1, TAXPERC2, WO_CAMN FROM tbl_bprop_detail WHERE PROP_NUM = '$PROP_NUM' AND PRJCODE = '$PRJCODE'";
		$r_06 			= $this->db->query($s_06)->result();
		foreach($r_06 as $rw_06) :
			$WO_TOTAL 	= $rw_06->WO_TOTAL;
			$TAXPERC1 	= $rw_06->TAXPERC1;
			$TAXPERC2 	= $rw_06->TAXPERC2;
			$WO_CAMN 	= $rw_06->WO_CAMN;
			$TOT_CAMN 	= $TOT_CAMN + $WO_CAMN;

			$TOT_WO 	= $TOT_WO + $WO_TOTAL;
			$TOT_TAX1 	= $TOT_TAX1 + $TAXPRICE1;
			$TOT_TAX2 	= $TOT_TAX2 + $TAXPRICE2;
		endforeach;
		$PROP_GTOTAL 	= $TOT_WO + $TOT_TAX1 - $TOT_TAX2;

		$s_07 			= "UPDATE tbl_bprop_header SET PROP_VALUE = $TOT_WO, PROP_VALUEAPP = $TOT_WO,
								WO_VALPPN = $TOT_TAX1, WO_VALPPH = $TOT_TAX2, PROP_GTOTAL = $PROP_GTOTAL, WO_TCAMN = $TOT_CAMN
							WHERE PROP_NUM = '$PROP_NUM' AND PRJCODE = '$PRJCODE'";
		$this->db->query($s_07);

		echo "Volume item $ITM_NAME sudah dikembalikan ke budget.";
	}
}