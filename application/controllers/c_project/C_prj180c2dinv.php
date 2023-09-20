<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 24 Maret 2018
 * File Name	= C_prj180c2dinv.php
 * Notes		= -
*/
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_prj180c2dinv extends CI_Controller  
{
  	function __construct() // GOOD
	{
		parent::__construct();

		//setlocale(LC_ALL, 'id-ID', 'id_ID');
		setlocale(LC_ALL, 'en_EN');
		
		$this->load->model('m_project/m_project_invoice/m_project_invoice', '', TRUE);
		$this->load->model('m_updash/m_updash', '', TRUE);
		$this->load->model('m_journal/m_journal', '', TRUE);
	
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

 	// Start : Index tiap halaman
 	public function index() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url		= site_url('c_project/c_prj180c2dinv/prj180c2dl/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function prj180c2dl() // OK
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
				$data["h1_title"] 	= "Faktur Proyek";
			}
			else
			{
				$data["h1_title"] 	= "Project Invoice";
			}
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN232';	 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_project/c_prj180c2dinv/gall180c2dinv/?id=";
			
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

	function gall180c2dinv() // OK
	{
		$this->load->model('m_project/m_project_invoice/m_project_invoice', '', TRUE);
		$this->load->model('m_project/m_project_invoice/m_project_invoice_RealINV', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE				= $_GET['id'];
			$PRJCODE				= $this->url_encryption_helper->decode_url($PRJCODE);
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			
			// Secure URL
			$data['showIdxMReq']		= site_url('c_project/c_prj180c2dinv/gall180c2dinv/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
				
			$data['title'] 		= $appName;
			$data['h2_title'] 	= 'Project Invoice List';	
			$data['h3_title'] 	= 'Project Invoice List';
			$data['addURL'] 	= site_url('c_project/c_prj180c2dinv/a180c2ddd/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['backURL'] 	= site_url('c_project/c_prj180c2dinv/?id='.$this->url_encryption_helper->encode_url($PRJCODE));			
			$data['PRJCODE'] 			= $PRJCODE;
			$data["MenuCode"] 			= 'MN232';
			
			$num_rows 					= $this->m_project_invoice->count_all_num_rowsProjInv($PRJCODE);
			
			$data["recordcount"] 		= $num_rows;
	 
			$data['viewprojinvoice'] 	= $this->m_project_invoice->get_last_ten_projinv($PRJCODE)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN232';
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
			
			$this->load->view('v_project/v_project_invoice/project_invoice', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

  	function get_AllData() // GOOD
	{
		$PRJCODE		= $_GET['id'];

		$LangID     = $this->session->userdata['LangID'];
        
        $sqlTransl      = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
        $resTransl      = $this->db->query($sqlTransl)->result();
        foreach($resTransl as $rowTransl) :
            $TranslCode = $rowTransl->MLANG_CODE;
            $LangTransl = $rowTransl->LangTransl;

    		if($TranslCode == 'ManualCode')$ManualCode = $LangTransl;
    		if($TranslCode == 'Date')$Date = $LangTransl;
    		if($TranslCode == 'DueDate')$DueDate = $LangTransl;
    		if($TranslCode == 'Description')$Description = $LangTransl;
    		if($TranslCode == 'SupplierName')$SupplierName = $LangTransl;
    		if($TranslCode == 'sourceDoc')$sourceDoc = $LangTransl;
    		if($TranslCode == 'NotReceipt')$NotReceipt = $LangTransl;
    		if($TranslCode == 'FullReceipt')$FullReceipt = $LangTransl;
    		if($TranslCode == 'HalfReceipt')$HalfReceipt = $LangTransl;
    		if($TranslCode == 'DownPayment')$DownPayment = $LangTransl;
    		if($TranslCode == 'InvoiceAmount')$InvoiceAmount = $LangTransl;
    		if($TranslCode == 'ReceiptAmount')$ReceiptAmount = $LangTransl;
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
			
			$columns_valid 	= array("PINV_MANNO",
									"PINV_NOTES",
									"PINV_DATE",
									"PINV_ENDDATE");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_project_invoice->get_AllDataC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_project_invoice->get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$PINV_CODE		= $dataI['PINV_CODE'];
                $PINV_MANNO		= $dataI['PINV_MANNO'];
                $PINV_STEP		= $dataI['PINV_STEP'];
                $PINV_CAT		= $dataI['PINV_CAT'];
                $PINV_SOURCE	= $dataI['PINV_SOURCE'];
                $PINV_OWNER		= $dataI['PINV_OWNER'];
                $PINV_DATE		= $dataI['PINV_DATE'];
                $PINV_ENDDATE	= $dataI['PINV_ENDDATE'];
                $PRJCODE		= $dataI['PRJCODE'];
                $PINV_PROG		= $dataI['PINV_PROG'];
                $PINV_PROGVAL	= $dataI['PINV_PROGVAL'];
                $PINV_NOTES		= $dataI['PINV_NOTES'];
                $MC_REF			= $dataI['MC_REF'];
                $PINV_STAT		= $dataI['PINV_STAT'];
                $PINV_STATD		= $dataI['PINV_STATD'];
                $GPINV_TOTVAL	= $dataI['GPINV_TOTVAL'];
                $PINV_PAIDAM	= $dataI['PINV_PAIDAM'];
				
				$date1 			= new DateTime($PINV_DATE);
				$date2 			= new DateTime($PINV_ENDDATE);

				$PINV_DATEV		= strftime('%d %b %Y', strtotime($PINV_DATE));
				$PINV_ENDDATEV	= strftime('%d %b %Y', strtotime($PINV_ENDDATE));

				if($PINV_CAT == 1)
					$PINV_CATD 	= "DP / ".$DownPayment;
				elseif($PINV_CAT == 2)
					$PINV_CATD 	= "MC / Montly Certificate";
				else
					$PINV_CATD 	= "SI / Site Instruction";

				$invAmn 		= 	"<div style='white-space:nowrap'>
											<strong><i class='fa fa-money margin-r-5'></i> ".$InvoiceAmount."</strong>
										</div>
									  	<div style='margin-left: 20px;'>
									  		".number_format($GPINV_TOTVAL, 2)."
									  	</div>";

				$invRec 		= 	"<div style='white-space:nowrap'>
											<strong><i class='fa fa-check-square-o margin-r-5'></i> ".$ReceiptAmount."</strong>
										</div>
									  	<div style='margin-left: 20px;'>
									  		".number_format($PINV_PAIDAM, 2)."
									  	</div>";

				$isDis 			= "";
				$numCol 		= "maroon";
				$PINV_PAYSTATD	= $NotReceipt;
				$PINV_PAYCOL	= "danger";
				if($PINV_STAT == 0)
				{
					$PINV_STATDes	= "fake";
					$STATCOL		= 'danger';
				}
				elseif($PINV_STAT == 1)
				{
					$PINV_STATDes 	= "New";
					$STATCOL		= 'warning';
					$PINV_PAYCOL	= "danger";
				}
				elseif($PINV_STAT == 2)
				{
					$PINV_STATDes 	= "Confirm";
					$STATCOL		= 'primary';
					$numCol 		= "orange";
					$PINV_PAYCOL	= "danger";
				}
				elseif($PINV_STAT == 3)
				{
					$PINV_STATDes 	= "Approve";
					$STATCOL		= 'success';
					$numCol 		= "olive";
					$PINV_PAYCOL	= "warning";
				}
				elseif($PINV_STAT == 4)
				{
					$PINV_STATDes 	= "Revised";
					$STATCOL		= 'danger';
				}
				elseif($PINV_STAT == 5)
				{
					$PINV_STATDes 	= "Rejected";
					$STATCOL		= 'danger';
					$STATCOL		= 'danger';
				}
				elseif($PINV_STAT == 6)
				{
					$PINV_STATDes 	= "Close";
					$STATCOL		= 'info';
					$numCol 		= "olive";
					$PINV_PAYCOL	= "success";
				}
				elseif($PINV_STAT == 7)
				{
					$PINV_STATDes 	= "Awaiting";
					$STATCOL		= 'warning';
					$numCol 		= "orange";
				}
				elseif($PINV_STAT == 9)
				{
					$PINV_STATDes 	= "Void";
					$STATCOL		= 'danger';
					$isDis			= "disabled";
					$STATCOL		= 'danger';
				}

				if($PINV_STATD == 'FR')
					$PINV_PAYSTATD	= $FullReceipt;
				elseif($PINV_STATD == 'HR')
					$PINV_PAYSTATD	= $HalfReceipt;

				if($PINV_STAT == 6)
					$PINV_PAYSTATD	= $FullReceipt;

				$SRC_DOC 			= "";
				$sqlDOC 			= "SELECT MC_MANNO FROM tbl_mcheader WHERE MC_CODE = '$PINV_SOURCE'";
				$resDOC 			= $this->db->query($sqlDOC)->result();
				foreach($resDOC as $rowDOC) :
					$SRC_DOC 		= $rowDOC->MC_MANNO;		
				endforeach;

				$getBR		= "tbl_br_detail A INNER JOIN tbl_br_header B ON A.BR_NUM = B.BR_NUM AND B.BR_STAT NOT IN (5,9)
								WHERE A.DocumentNo = '$PINV_CODE' AND A.PRJCODE = '$PRJCODE'";
				$resBR		= $this->db->count_all($getBR);

				if($resBR > 0)
					$isDis		= "disabled='disabled'";

				$secUpd			= site_url('c_project/c_prj180c2dinv/up180c2ddt//?id='.$this->url_encryption_helper->encode_url($PINV_CODE));
				$secPrint		= site_url('c_project/c_prj180c2dinv/printdocument/?id='.$this->url_encryption_helper->encode_url($PINV_CODE));
				$CollID			= "PINV~$PINV_CODE~$PRJCODE";
				$secDel 		= base_url().'index.php/__l1y/trashDOC/?id=';
				$secVoid 		= base_url().'index.php/__l1y/trashPRJINV/?id=';
				$delID 			= "$secDel~tbl_projinv_header~tbl_projinv_detail~PINV_CODE~$PINV_CODE~PRJCODE~$PRJCODE";
				$voidID 		= "$secVoid~tbl_projinv_header~tbl_projinv_detail~PINV_CODE~$PINV_CODE~PRJCODE~$PRJCODE";

				if($PINV_STAT == 1 || $PINV_STAT == 4)
				{
					$secAction	= 	"<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
								   	<a href='avascript:void(null);' class='btn btn-primary btn-xs' title='Print' onClick='printD(".$noU.")' disabled='disabled'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-warning btn-xs' onClick='voidDOC(".$noU.")' title='Void' disabled='disabled'>
										<i class='glyphicon glyphicon-off'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				elseif($PINV_STAT == 3 || $PINV_STAT == 6) 
				{
					$secAction	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
									<input type='hidden' name='urlVoid".$noU."' id='urlVoid".$noU."' value='".$voidID."'>
								   <label style='white-space:nowrap'>
								   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   </a>
								   <a href='avascript:void(null);' class='btn btn-primary btn-xs' title='Print' onClick='printD(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-warning btn-xs' onClick='voidDOC(".$noU.")' title='Void' ".$isDis.">
										<i class='glyphicon glyphicon-off'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				else
				{
					$secAction	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								   <label style='white-space:nowrap'>
								   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   </a>
								   <a href='avascript:void(null);' class='btn btn-primary btn-xs' title='Print' onClick='printD(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-warning btn-xs' onClick='voidDOC(".$noU.")' title='Void' disabled='disabled'>
										<i class='glyphicon glyphicon-off'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}

				$output['data'][] 	= array("<div style='white-space:nowrap'>
											  	<strong>".$PINV_MANNO."</strong>
											  	<div><strong><i class='fa fa-flag-o margin-r-5'></i> ".$sourceDoc." </strong></div>
										  		<div>
											  		<p class='text-muted' style='margin-left: 20px'>
											  			".$SRC_DOC."
											  		</p>
											  	</div>
										  	</div>",
										  	"<div style='white-space:nowrap'><button type='button' class='btn bg-".$numCol." btn-flat margin'>".$PINV_STEP."</button></div>",
										  	"<div style='white-space:nowrap'>
											  	<strong><i class='fa fa-calendar margin-r-5'></i> ".$Date." </strong>
										  		<div>
											  		<p class='text-muted' style='margin-left: 18px; white-space:nowrap'>
											  			".$PINV_DATEV."
											  		</p>
											  	</div>
											  	<div><strong><i class='fa fa-calendar-times-o margin-r-5'></i> ".$DueDate." </strong></div>
										  		<div>
											  		<p class='text-muted' style='margin-left: 18px'>
											  			".$PINV_ENDDATEV."
											  		</p>
											  	</div>
										  	</div>",
										  	"<div><strong><i class='fa fa-certificate margin-r-5'></i> ".$PINV_CATD." </strong></div>
										  	<div>
											  	<strong><i class='fa fa-pencil margin-r-5'></i> ".$Description." </strong>
										  		<div>
											  		<p class='text-muted' style='margin-left: 18px'>
											  			".$PINV_NOTES."
											  		</p>
											  	</div>
										  	</div>",
										  "<span class='label label-".$PINV_PAYCOL."' style='font-size:12px'>".$PINV_PAYSTATD."</span>",
										  "$invAmn $invRec",
										  "<span class='label label-".$STATCOL."' style='font-size:12px'>".$PINV_STATDes."</span>",
										  "<div style='white-space:nowrap'>".$secAction."</div");
										  
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

  	function get_AllDataMC() // GOOD
	{
		$PRJCODE		= $_GET['id'];

		$LangID     = $this->session->userdata['LangID'];
        
        $sqlTransl      = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
        $resTransl      = $this->db->query($sqlTransl)->result();
        foreach($resTransl as $rowTransl) :
            $TranslCode = $rowTransl->MLANG_CODE;
            $LangTransl = $rowTransl->LangTransl;

    		if($TranslCode == 'ManualCode')$ManualCode = $LangTransl;
    		if($TranslCode == 'Date')$Date = $LangTransl;
    		if($TranslCode == 'DueDate')$DueDate = $LangTransl;
    		if($TranslCode == 'Description')$Description = $LangTransl;
    		if($TranslCode == 'SupplierName')$SupplierName = $LangTransl;
    		if($TranslCode == 'sourceDoc')$sourceDoc = $LangTransl;
    		if($TranslCode == 'NotReceipt')$NotReceipt = $LangTransl;
    		if($TranslCode == 'FullReceipt')$FullReceipt = $LangTransl;
    		if($TranslCode == 'HalfReceipt')$HalfReceipt = $LangTransl;
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
									"MC_MANNO",
									"MC_DATE",
									"MC_TOTVAL",
									"MC_TOTVAL_PPh",
									"MC_NOTES");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_project_invoice->get_AllDataMCC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_project_invoice->get_AllDataMCL($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
                $pageFrom 		= "MC";
                $MC_CODE 		= $dataI['MC_CODE'];
                $MC_MANNO 		= $dataI['MC_MANNO'];
				if($MC_MANNO == '')
                	$MC_MANNO	= $MC_CODE;
                $MC_DATE 		= $dataI['MC_DATE'];
				$MC_DATEV		= strftime('%d %b %Y', strtotime($MC_DATE));
                $MC_ENDDATE 	= $dataI['MC_ENDDATE'];
                $MC_RETVAL 		= $dataI['MC_RETVAL'];
                $MC_PROG 		= $dataI['MC_PROG'];
                $MC_PROGVAL		= $dataI['MC_PROGVAL'];
                $MC_PROGCUR		= $dataI['MC_PROGCUR'];
                $MC_PROGCURVAL	= $dataI['MC_PROGCURVAL'];
                $MC_VALADD 		= $dataI['MC_VALADD'];
                $MC_MATVAL 		= $dataI['MC_MATVAL'];
                $MC_DPPER 		= $dataI['MC_DPPER'];
                $MC_DPVAL		= $dataI['MC_DPVAL'];
                $MC_DPBACK 		= $dataI['MC_DPBACK'];
                $MC_DPBACKCUR 	= $dataI['MC_DPBACKCUR'];
                $MC_RETCUTP 	= $dataI['MC_RETCUTP'];
                $MC_RETCUT 		= $dataI['MC_RETCUT'];
                $MC_RETCUTCUR 	= $dataI['MC_RETCUTCUR'];
                $MC_PROGAPP		= $dataI['MC_PROGAPP'];
                $MC_PROGAPPVAL	= $dataI['MC_PROGAPPVAL'];
                $MC_AKUMNEXT 	= $dataI['MC_AKUMNEXT'];
                $MC_VALBEF 		= $dataI['MC_VALBEF'];
                $MC_TOTVAL 		= $dataI['MC_TOTVAL'];
               	$MC_TOTVAL_PPn 	= $dataI['MC_TOTVAL_PPn'];
                $MC_TOTVAL_PPh	= $dataI['MC_TOTVAL_PPh'];
                $GMC_TOTVAL		= $MC_TOTVAL+$MC_TOTVAL_PPn-$MC_TOTVAL_PPh;
                $MC_NOTES 		= $dataI['MC_NOTES'];
                $MC_OWNER		= $dataI['MC_OWNER'];
                $MC_APPSTAT		= $dataI['MC_APPSTAT'];
                
                // GET INVOICE NUMBER BY MC_CODE
                    $PINV_MANNO 	= "-";
                    if($MC_APPSTAT == 1)
                    {
                        $sqlGetINV 			= "SELECT PINV_CODE, PINV_MANNO FROM tbl_projinv_header 
                                                WHERE PINV_SOURCE = '$MC_CODE'";
                        $resGetINV 			= $this->db->query($sqlGetINV)->result();
                        foreach($resGetINV as $rowGetINV) :
                            $PINV_CODE 		= $rowGetINV->PINV_CODE;
                            $PINV_MANNO 	= $rowGetINV->PINV_MANNO;
                        endforeach;
                    }	
                    else
                    {
                        $PINV_CODE 		= "-";
                        $PINV_MANNO 	= "-";
                    }	
                
                // GET LAST PAYMENT BEFOR
                    $sqlGetLPB 			= "SELECT PINV_AKUMNEXT FROM tbl_projinv_header 
                                            WHERE PRJCODE = '$PRJCODE'";
                    $resGetLPB 			= $this->db->query($sqlGetLPB)->result();
                    foreach($resGetLPB as $rowGetLPB) :
                        $PINV_AKUMNEXT 	= $rowGetLPB->PINV_AKUMNEXT;
                    endforeach;

                $OWN_INST 	= 'S';
                $sqlOWN 	= "SELECT own_Inst FROM tbl_owner WHERE own_Code = '$MC_OWNER'";
                $resOWN 	= $this->db->query($sqlOWN)->result();
                foreach($resOWN as $rowOWN) :
                    $OWN_INST     = $rowOWN ->own_Inst;
                endforeach;

				// OTHER SETT
                    $chkBox		= "<input type='radio' name='chk1' value='".$pageFrom."|".$MC_CODE."|".$MC_MANNO."|".$MC_DATE."|".$MC_ENDDATE."|".$MC_RETVAL."|".$MC_PROG."|".$MC_PROGVAL."|".$MC_PROGCUR."|".$MC_PROGCURVAL."|".$MC_VALADD."|".$MC_MATVAL."|".$MC_DPPER."|".$MC_DPVAL."|".$MC_DPBACK."|".$MC_DPBACKCUR."|".$MC_RETCUTP."|".$MC_RETCUT."|".$MC_RETCUTCUR."|".$MC_PROGAPP."|".$MC_PROGAPPVAL."|".$MC_AKUMNEXT."|".$MC_VALBEF."|".$MC_TOTVAL."|".$MC_TOTVAL_PPn."|".$MC_TOTVAL_PPh."|".$GMC_TOTVAL."|".$MC_NOTES."|".$MC_OWNER."|".$OWN_INST."' onClick='pickThis1(this);'/>";

				$output['data'][] 	= array($chkBox,
											$MC_MANNO,
											$MC_DATEV,
											number_format($MC_TOTVAL, 2),
											number_format($GMC_TOTVAL, 2),
											$MC_NOTES);

				/*$output['data'][] 	= array("<div style='white-space:nowrap'>
											  	<strong>".$MC_MANNO."</strong>
											  	<div><strong><i class='fa fa-flag-o margin-r-5'></i> ".$sourceDoc." </strong></div>
										  		<div>
											  		<p class='text-muted' style='margin-left: 20px'>
											  			".$SRC_DOC."
											  		</p>
											  	</div>
										  	</div>",
										  	"<div style='white-space:nowrap'><button type='button' class='btn bg-".$numCol." btn-flat margin'>".$PINV_STEP."</button></div>",
										  	"<div style='white-space:nowrap'>
											  	<strong><i class='fa fa-calendar margin-r-5'></i> ".$Date." </strong>
										  		<div>
											  		<p class='text-muted' style='margin-left: 18px; white-space:nowrap'>
											  			".$PINV_DATEV."
											  		</p>
											  	</div>
											  	<div><strong><i class='fa fa-calendar-times-o margin-r-5'></i> ".$DueDate." </strong></div>
										  		<div>
											  		<p class='text-muted' style='margin-left: 18px'>
											  			".$PINV_ENDDATEV."
											  		</p>
											  	</div>
										  	</div>",
										  	"<div>
											  	<strong><i class='fa fa-pencil margin-r-5'></i> ".$Description." </strong>
										  		<div>
											  		<p class='text-muted' style='margin-left: 18px'>
											  			".$PINV_NOTES."
											  		</p>
											  	</div>
										  	</div>",
										  "<span class='label label-".$STATCOL."' style='font-size:12px'>".$PINV_STATDes."</span>",
										  "<span class='label label-".$PINV_PAYCOL."' style='font-size:12px'>".$PINV_PAYSTATD."</span>",
										  "<div style='white-space:nowrap'>".$secAction."</div");*/
										  
				$noU		= $noU + 1;
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
	
	function printdocument() // OK
	{
		$this->load->model('m_project/m_project_invoice/m_project_invoice', '', TRUE);
		$this->load->model('m_project/m_project_invoice/m_project_invoice_RealINV', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		if ($this->session->userdata('login') == TRUE)
		{	
			$PINV_Number			= $_GET['id'];
			$PINV_Number			= $this->url_encryption_helper->decode_url($PINV_Number);
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
					
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title'] 		= 'Document Print';
			$data['h3_title'] 		= 'Document Print';
			
			$data['recordcountOwner'] 	= $this->m_project_invoice->count_all_num_rowsOwner();
			$data['viewOwner'] 			= $this->m_project_invoice->viewOwner()->result();
			$data['recordcountEmpDept'] = $this->m_project_invoice->count_all_num_rowsEmpDept();
			$data['viewEmployeeDept'] 	= $this->m_project_invoice->viewEmployeeDept()->result();
			$data['recordcountProject']	= $this->m_project_invoice->count_all_num_rowsProject();
			$data['viewProject'] 		= $this->m_project_invoice->viewProject()->result();
			
			$getpurINVO					= $this->m_project_invoice->get_PINV_by_number($PINV_Number)->row();
			
			$this->session->set_userdata('PINV_Number', $getpurINVO->PINV_CODE);
			
			$data['link'] 				= array('link_back' => anchor('c_project/c_prj180c2dinv/gall180c2dinv/'.$getpurINVO->PRJCODE,'<input type="button" name="btnCancel" id="btnCancel" value="Cancel" class="btn btn-danger" />', array('style' => 'text-decoration: none;')));
			
			$data['PINV_CODE'] 		= $getpurINVO->PINV_CODE;
			$data['PINV_MANNO'] 	= $getpurINVO->PINV_MANNO;
			$data['PINV_STEP'] 		= $getpurINVO->PINV_STEP;
			$data['PINV_CAT'] 		= $getpurINVO->PINV_CAT;
			$data['PINV_SOURCE']	= $getpurINVO->PINV_SOURCE;
			$data['PRJCODE'] 		= $getpurINVO->PRJCODE;
			$PRJCODE				= $getpurINVO->PRJCODE;
			$data['PINV_OWNER'] 	= $getpurINVO->PINV_OWNER;
			$data['PINV_DATE'] 		= $getpurINVO->PINV_DATE;
			$data['PINV_ENDDATE']	= $getpurINVO->PINV_ENDDATE; 
			$data['PINV_CHECKD'] 	= $getpurINVO->PINV_CHECKD; 
			$data['PINV_CREATED']	= $getpurINVO->PINV_CREATED;
			$data['PINV_PPNPERC'] 	= $getpurINVO->PINV_PPNPERC;
			$data['PINV_RETVAL'] 	= $getpurINVO->PINV_RETVAL;
			$data['PINV_RETCUT'] 	= $getpurINVO->PINV_RETCUT;
			$data['PINV_DPPER'] 	= $getpurINVO->PINV_DPPER;
			$data['PINV_DPVAL'] 	= $getpurINVO->PINV_DPVAL;
			$data['PINV_DPVALPPn']	= $getpurINVO->PINV_DPVALPPn;
			$data['PINV_DPBACK'] 	= $getpurINVO->PINV_DPBACK;
			$data['PINV_DPBACKPPn'] = $getpurINVO->PINV_DPBACKPPn;
			$data['PINV_PROG'] 		= $getpurINVO->PINV_PROG;
			$data['PINV_PROGVAL']	= $getpurINVO->PINV_PROGVAL;
			$data['PINV_PROGVALPPn']= $getpurINVO->PINV_PROGVALPPn;
			$data['PINV_PROGAPP']	= $getpurINVO->PINV_PROGAPP;
			$data['PINV_PROGAPPVAL']= $getpurINVO->PINV_PROGAPPVAL;
			$data['PINV_VALADD'] 	= $getpurINVO->PINV_VALADD;
			$data['PINV_VALADDPPn'] = $getpurINVO->PINV_VALADDPPn;
			$data['PINV_MATVAL'] 	= $getpurINVO->PINV_MATVAL;
			$data['PINV_VALBEF']	= $getpurINVO->PINV_VALBEF;
			$data['PINV_VALBEFPPn']	= $getpurINVO->PINV_VALBEFPPn;
			$data['PINV_AKUMNEXT']	= $getpurINVO->PINV_AKUMNEXT;
			$data['PINV_TOTVAL'] 	= $getpurINVO->PINV_TOTVAL;
			$data['PINV_TOTVALPPn'] = $getpurINVO->PINV_TOTVALPPn;
			$data['PINV_TOTVALPPh'] = $getpurINVO->PINV_TOTVALPPh;
			$data['PINV_TOTVALPPhP'] = $getpurINVO->PINV_TOTVALPPhP;
			$data['PINV_NOTES'] 	= $getpurINVO->PINV_NOTES;
			$data['PINV_EMPID'] 	= $getpurINVO->PINV_EMPID;
			$data['PINV_STAT'] 		= $getpurINVO->PINV_STAT;
			$data['PATT_YEAR'] 		= $getpurINVO->PATT_YEAR;
			$data['PATT_MONTH'] 	= $getpurINVO->PATT_MONTH;
			$data['PATT_DATE'] 		= $getpurINVO->PATT_DATE;
			$data['PATT_NUMBER'] 	= $getpurINVO->PATT_NUMBER;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getpurINVO->PINV_CODE;
				$MenuCode 		= 'MN232';
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
			
			$this->load->view('v_project/v_project_invoice/project_invoice_print', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function a180c2ddd() // OK
	{
		$this->load->model('m_project/m_project_invoice/m_project_invoice', '', TRUE);
		$this->load->model('m_project/m_project_invoice/m_project_invoice_RealINV', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		if ($this->session->userdata('login') == TRUE)
		{	
			$PRJCODE				= $_GET['id'];
			$PRJCODE				= $this->url_encryption_helper->decode_url($PRJCODE);
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			
			$cancel_url 			= site_url('c_project/c_prj180c2dinv/gall180c2dinv/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			$data['PRJCODE'] 		= $PRJCODE;
			$docPatternPosition 	= 'Especially';	
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			$data['h2_title']		= 'Add Project Invoice';
			$data['h3_title']		= 'project';
			$data['main_view'] 		= 'v_project/v_project_invoice/project_invoice_sd_form';
			$data['form_action']	= site_url('c_project/c_prj180c2dinv/add_process');
			//$data['link'] 			= array('link_back' => anchor("$cancel_url",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 		= $cancel_url ;
			$data['recordcountOwner'] 	= $this->m_project_invoice->count_all_num_rowsOwner();
			$data['viewOwner'] 			= $this->m_project_invoice->viewOwner()->result();
			$data['recordcountEmpDept'] = $this->m_project_invoice->count_all_num_rowsEmpDept();
			$data['viewEmployeeDept'] 	= $this->m_project_invoice->viewEmployeeDept()->result();
			$data['recordcountProject']	= $this->m_project_invoice->count_all_num_rowsProject();
			$data['viewProject'] 		= $this->m_project_invoice->viewProject()->result();
			
			$MenuCode 					= 'MN232';
			$data["MenuCode"] 			= 'MN232';
			$data['viewDocPattern'] 	= $this->m_project_invoice->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN232';
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
			
			$this->load->view('v_project/v_project_invoice/project_invoice_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function add_process() // OK
	{
		$this->load->model('m_project/m_project_invoice/m_project_invoice', '', TRUE);
		$this->load->model('m_project/m_project_invoice/m_project_invoice_RealINV', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		$comp_init 	= $this->session->userdata('comp_init');
		
		if ($this->session->userdata('login') == TRUE)
		{
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			
			date_default_timezone_set("Asia/Jakarta");
			
			$this->db->trans_begin();
			
			// 		PINV_STEP	PINV_CAT	PINV_MMC	PINV_SOURCE	PINV_SOURCE_MC	PRJCODE	PINV_OWNER	PINV_DATE	PINV_ENDDATE	PINV_CHECKD	PINV_CREATED	PINV_DPPER	PINV_PROG	PINV_DPVAL	PINV_DPVALPPn	PINV_DPBACK	PINV_DPBACKPPn	PINV_DPBACKCUR	PINV_PROGVAL	PINV_PROGVALPPn	PINV_PROGAPP	PINV_PROGAPPVAL	PINV_PROGCUR	PINV_PROGCURVAL	PINV_VALADD	PINV_VALADDPPn	PINV_MATVAL	PINV_RETVAL	PINV_RETCUTP	PINV_RETCUT	PINV_RETCUTCUR	PINV_VALBEF	PINV_VALBEFPPn	PINV_AKUMNEXT	PINV_TOTVAL	PINV_TOTVALPPn	PINV_TOTVALPPhP	PINV_TOTVALPPh	GPINV_TOTVAL	PINV_NOTES	PINV_EMPID	PINV_EMPIDAPP	PINV_STAT	PINV_STATD	PINV_PAIDDATE	PINV_PAIDAM	PINV_TAXDATE	PINV_TAXNO	isDP	MC_REF	PINV_LASTDATE	PATT_YEAR	PATT_MONTH	PATT_DATE	PATT_NUMBER

			$PINV_CODE 				= $this->input->post('PINV_CODE');
			$PINV_MANNO 			= $this->input->post('PINV_MANNO');
			$PINV_KWITNO 			= $this->input->post('PINV_KWITNO');
			$PINV_FAKNO 			= $this->input->post('PINV_FAKNO');
			$PINV_INVOICE 			= $this->input->post('PINV_INVOICE');
			$PRJCODE 				= $this->input->post('PRJCODE');			
			$PINV_CAT				= $this->input->post('PINV_CAT');
			$PINV_DATE				= date('Y-m-d',strtotime($this->input->post('PINV_DATE')));
			/*$PINV_DateY			= date('Y',strtotime($this->input->post('PINV_DATE')));
			$PINV_DateM				= date('m',strtotime($this->input->post('PINV_DATE')));
			$PINV_DateD				= date('d',strtotime($this->input->post('PINV_DATE')));
			$PINV_DATEX				= mktime(0,0,0,$PINV_DateM,$PINV_DateD,$PINV_DateY);
			$PINV_TTOTerm			= 30;
			$PINV_ENDDATE 			= date("Y-m-d",strtotime("+$PINV_TTOTerm days",$PINV_DATEX));*/
			$PINV_ENDDATE			= date('Y-m-d',strtotime($this->input->post('PINV_ENDDATE')));
			$PINV_TOTVAL			= $this->input->post('PINV_TOTVAL');
			//echo "PINV_TOTVAL= $PINV_TOTVAL";
			$PINV_CREATED			= date('Y-m-d H:i:s');
			$PATT_YEAR				= date('Y',strtotime($this->input->post('PINV_DATE')));
			
			$AH_CODE				= $PINV_CODE;
			$AH_APPLEV				= $this->input->post('APP_LEVEL');
			$AH_APPROVER			= $DefEmp_ID;
			$AH_APPROVED			= date('Y-m-d H:i:s');
			$AH_NOTES				= $this->input->post('PINV_NOTES');
			$AH_ISLAST				= $this->input->post('IS_LAST');
			$PINV_PPNPERC			= $this->input->post('PINV_PPNPERC');

			if($PINV_CAT == 1)
			{
				$PINV_SOURCE		= "";									// DP
				$REF_NUM			= "DP";				
				$PINV_TOTVAL		= $this->input->post('PINV_DPVAL');
				$PINV_TOTVALPPn		= $this->input->post('PINV_DPVALPPn');
				$PINV_TOTVALPPHP	= $this->input->post('PINV_TOTVALPPHP');
				$PINV_TOTVALPPH		= $this->input->post('PINV_TOTVALPPH');
				$PINV_TOTVALnPPN	= $PINV_TOTVAL + $PINV_TOTVALPPn - $PINV_TOTVALPPH;
			}
			elseif($PINV_CAT == 2)
			{
				$PINV_SOURCE		= $this->input->post('PINV_SOURCE1');	// MC
				$REF_NUM			= "MC";
				$PINV_TOTVAL		= $this->input->post('PINV_TOTVAL');
				$PINV_TOTVALPPn		= $this->input->post('PINV_TOTVALPPn');
				$PINV_TOTVALPPHP	= $this->input->post('PINV_TOTVALPPHP');
				$PINV_TOTVALPPH		= $this->input->post('PINV_TOTVALPPH');
				$PINV_DPBACKCUR		= $this->input->post('PINV_DPBACKCUR');
				$PINV_RETCUTCUR		= $this->input->post('PINV_RETCUTCUR');
				//$PINV_TOTVALnPPN	= $PINV_TOTVAL + $PINV_TOTVALPPn - $PINV_TOTVALPPH;
				$PINV_TOTVALnPPN	= $this->input->post('PINV_RECEIVETOT');
			}
			elseif($PINV_CAT == 3)
			{
				$PINV_SOURCE		= $this->input->post('PINV_SOURCE2');	// MC
				$REF_NUM			= "SI";
				$PINV_TOTVAL		= $this->input->post('PINV_TOTVAL');
				$PINV_TOTVALPPn		= $this->input->post('PINV_TOTVALPPn');
				$PINV_TOTVALPPHP	= $this->input->post('PINV_TOTVALPPHP');
				$PINV_TOTVALPPH		= $this->input->post('PINV_TOTVALPPH');
				//$PINV_TOTVALnPPN	= $PINV_TOTVAL + $PINV_TOTVALPPn - $PINV_TOTVALPPH;
				$PINV_TOTVALnPPN	= $this->input->post('PINV_RECEIVETOT');
			}
			
			$PINV_STAT				= $this->input->post('PINV_STAT');
			if($PINV_STAT == 1)
			{
				$MC_APPSTAT = 1;
			}
			elseif($PINV_STAT == 2)
			{
				$MC_APPSTAT = 2;
			}
			else
			{
				$MC_APPSTAT = 3;
			}
			
			$prohPINVH 	= array('PINV_CODE' 		=> $PINV_CODE,
								'PINV_MANNO'		=> $PINV_MANNO,
								'PINV_KWITNO'		=> $PINV_KWITNO,
								'PINV_FAKNO'		=> $PINV_FAKNO,
								'PINV_INVOICE'		=> $PINV_INVOICE,
								'PINV_STEP'			=> $this->input->post('PINV_STEP'),
								'PINV_CAT'			=> $this->input->post('PINV_CAT'),
								'PINV_MMC'			=> $this->input->post('PINV_MMC'),
								'PINV_SOURCE'		=> $PINV_SOURCE,
								'PINV_SOURCE_MN'	=> $this->input->post('PINV_SOURCE_MN'),
								'PRJCODE'			=> $PRJCODE,
								'PINV_DATE'			=> $PINV_DATE,
								'PINV_ENDDATE'		=> $PINV_ENDDATE,
								'PINV_CREATED'		=> $PINV_CREATED,
								'PINV_PPNPERC'		=> $PINV_PPNPERC,
								'PINV_OWNER'		=> $this->input->post('PINV_OWNER'),
								'PINV_RETVAL'		=> $this->input->post('PINV_RETVAL'),
								'PINV_RETCUT'		=> $this->input->post('PINV_RETCUT'),
								'PINV_RETCUTCUR'	=> $this->input->post('PINV_RETCUTCUR'),
								'PINV_DPPER'		=> $this->input->post('PINV_DPPER'),
								'PINV_DPVAL'		=> $this->input->post('PINV_DPVAL'),
								'PINV_DPVALPPn'		=> $this->input->post('PINV_DPVALPPn'),
								'PINV_DPBACK'		=> $this->input->post('PINV_DPBACK'),
								'PINV_DPBACKCUR'	=> $this->input->post('PINV_DPBACKCUR'),
								'PINV_PROG'			=> $this->input->post('PINV_PROG'),
								'PINV_RETCUTP'		=> $this->input->post('PINV_RETCUTP'),
								'PINV_PROGVAL'		=> $this->input->post('PINV_PROGVAL'),
								'PINV_PROGAPP'		=> $this->input->post('PINV_PROG'),
								'PINV_PROGAPPVAL'	=> $this->input->post('PINV_PROGVAL'),
								'PINV_PROGCUR'		=> $this->input->post('PINV_PROGCUR'),
								'PINV_PROGCURVAL'	=> $this->input->post('PINV_PROGCURVAL'),
								'PINV_VALADD'		=> $this->input->post('PINV_VALADD'),
								'PINV_VALBEF'		=> $this->input->post('PINV_VALBEF'),
								'PINV_AKUMNEXT'		=> $this->input->post('PINV_AKUMNEXT'),
								'PINV_TAXABLE'		=> $this->input->post('PINV_TAXABLE'),
								'PINV_TOTVAL'		=> $PINV_TOTVAL,
								'PINV_TOTVALPPn'	=> $PINV_TOTVALPPn,
								'PINV_TOTVALPPhP'	=> $PINV_TOTVALPPHP,
								'PINV_TOTVALPPh'	=> $PINV_TOTVALPPH,
								'GPINV_TOTVAL'		=> $this->input->post('PINV_RECEIVETOT'),
								'PINV_NOTES'		=> $this->input->post('PINV_NOTES'),
								'MC_REF'			=> $this->input->post('MC_REF'),
								'PINV_EMPID'		=> $DefEmp_ID,
								'PINV_STAT'			=> $PINV_STAT,
								'PATT_YEAR'			=> $PATT_YEAR,
								'PATT_NUMBER'		=> $this->input->post('PATT_NUMBER'));
			$this->m_project_invoice->add($prohPINVH);
			
			// START : ADD CONCLUSION
			if($PINV_CAT == 2)
			{
				$this->load->model('m_project/m_project_mc/m_project_mc', '', TRUE);
				$PINV_SOURCE		= $this->input->post('PINV_SOURCE1');
				$MC_CODE			= $this->input->post('PINV_SOURCE1');
				$INV_AMOUNT			= $this->input->post('PINV_PAYAKUM');
				//$INV_AMOUNT_PPn= $INV_AMOUNT * 0.1;
				//$INV_AMOUNT_PPh1= $INV_AMOUNT * 0.03;
				//$GINV_AMOUNT	= $INV_AMOUNT + $INV_AMOUNT_PPn - $INV_AMOUNT_PPh;
				$INV_AMOUNT_PPn		= $this->input->post('PINV_PAYAKUMPPn');
				$INV_AMOUNT_PPh		= $this->input->post('PINV_TOTVALPPH');
				$INV_AMOUNT_PPhP	= $this->input->post('PINV_TOTVALPPHP');
				$GINV_AMOUNT		= $this->input->post('PINV_TOTVAL');
				$dataMCH 			= array('INV_NUMBER' 		=> $PINV_CODE,
											'INV_DATE'			=> $PINV_DATE,
											'INV_SOURCE'		=> $PINV_SOURCE,
											'INV_AMOUNT'		=> $INV_AMOUNT,
											'INV_AMOUNT_PPn'	=> $INV_AMOUNT_PPn,
											'INV_AMOUNT_PPhP'	=> $INV_AMOUNT_PPhP,
											'INV_AMOUNT_PPh'	=> $INV_AMOUNT_PPh);
				$this->m_project_mc->updateConc($MC_CODE, $dataMCH, $PRJCODE);
			}
			// END : ADD CONCLUSION

			if($PINV_STAT == 2)
			{
				// START : UPDATE FINANCIAL DASHBOARD
					$PINV_VAL 	= $this->input->post('PINV_RECEIVETOT');
					$finDASH 	= array('PRJCODE'	=> $PRJCODE,
										'PERIODE'	=> $PINV_DATE,
										'FVAL'		=> $PINV_VAL,
										'FNAME'		=> "PINV_VAL");										
					$this->m_updash->updFINDASH($finDASH);
				// END : UPDATE FINANCIAL DASHBOARD
			}
			
			if($PINV_CAT == 2)
			{
				$MC_REF		= $this->input->post('MC_REF');
				$MC_REFB	= explode("|",$MC_REF);
				for($i=0;$i< count($MC_REFB);$i++)
				{
					$MC_CODE 	= $MC_REFB[$i];
					$this->m_project_invoice->updateMC($MC_REF, $MC_APPSTAT);
				}
			}
			elseif($PINV_CAT == 3)
			{
				$this->m_project_invoice->updateSIC($PINV_SOURCE, $MC_APPSTAT);
			}
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $PINV_CODE;
				$MenuCode 		= 'MN232';
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
			
			$url	= site_url('c_project/c_prj180c2dinv/gall180c2dinv/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function up180c2ddt() // OK
	{
		$this->load->model('m_project/m_project_invoice/m_project_invoice', '', TRUE);
		$this->load->model('m_project/m_project_invoice/m_project_invoice_RealINV', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		if ($this->session->userdata('login') == TRUE)
		{	
			$PINV_CODE					= $_GET['id'];
			$PINV_CODE					= $this->url_encryption_helper->decode_url($PINV_CODE);
			$DefEmp_ID 					= $this->session->userdata['Emp_ID'];
			
			$getpurINVO 				= $this->m_project_invoice->get_PINV_by_number($PINV_CODE)->row();
			$PRJCODE					= $getpurINVO->PRJCODE;
			
			$data['PRJCODE'] 			= $PRJCODE;	
			$data['proj_Code'] 			= $PRJCODE;	
			$docPatternPosition 		= 'Especially';	
			$data['title'] 				= $appName;
			$data['task'] 				= 'Edit';
			$data['h2_title']			= 'Add Project Invoice';
			$data['h3_title']			= 'project';
			$data['form_action']		= site_url('c_project/c_prj180c2dinv/update_process');
			$data["MenuCode"] 			= 'MN232';
			
			$data['recordcountOwner'] 	= $this->m_project_invoice->count_all_num_rowsOwner();
			$data['viewOwner'] 			= $this->m_project_invoice->viewOwner()->result();
			$data['recordcountEmpDept'] = $this->m_project_invoice->count_all_num_rowsEmpDept();
			$data['viewEmployeeDept'] 	= $this->m_project_invoice->viewEmployeeDept()->result();
			$data['recordcountProject']	= $this->m_project_invoice->count_all_num_rowsProject();
			$data['viewProject'] 		= $this->m_project_invoice->viewProject()->result();
			//echo "hahaha $PRJCODE<br>";
			//return false;
			$cancel_url 				= site_url('c_project/c_prj180c2dinv/gall180c2dinv/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			//$data['link'] 				= array('link_back' => anchor("$cancel_url",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 		= $cancel_url ;			

			$data['default']['PINV_CODE'] 		= $getpurINVO->PINV_CODE;
			$data['default']['PINV_MANNO'] 		= $getpurINVO->PINV_MANNO;
			$data['default']['PINV_KWITNO'] 	= $getpurINVO->PINV_KWITNO;
			$data['default']['PINV_FAKNO'] 		= $getpurINVO->PINV_FAKNO;
			$data['default']['PINV_INVOICE'] 	= $getpurINVO->PINV_INVOICE;
			$data['default']['PINV_STEP'] 		= $getpurINVO->PINV_STEP;
			$data['default']['PINV_CAT'] 		= $getpurINVO->PINV_CAT;
			$data['default']['PINV_MMC'] 		= $getpurINVO->PINV_MMC;
			$data['default']['PINV_SOURCE']		= $getpurINVO->PINV_SOURCE;
			$data['default']['PINV_SOURCE_MN']	= $getpurINVO->PINV_SOURCE_MN;
			$data['default']['PRJCODE'] 		= $getpurINVO->PRJCODE;
			$data['default']['PINV_OWNER'] 		= $getpurINVO->PINV_OWNER;
			$data['default']['PINV_DATE'] 		= $getpurINVO->PINV_DATE;
			$data['default']['PINV_ENDDATE']	= $getpurINVO->PINV_ENDDATE; 
			$data['default']['PINV_CHECKD'] 	= $getpurINVO->PINV_CHECKD; 
			$data['default']['PINV_CREATED']	= $getpurINVO->PINV_CREATED;
			$data['default']['PINV_RETVAL'] 	= $getpurINVO->PINV_RETVAL;
			$data['default']['PINV_RETCUT'] 	= $getpurINVO->PINV_RETCUT;
			$PINV_RETCUT						= $getpurINVO->PINV_RETCUT;
			$data['default']['PINV_RETCUTP'] 	= $getpurINVO->PINV_RETCUTP;
			$data['default']['PINV_RETCUTCUR'] 	= $getpurINVO->PINV_RETCUTCUR;
			$data['default']['PINV_RETCUTPPn'] 	= round(0.1 * $PINV_RETCUT);
			$data['default']['PINV_DPPER'] 		= $getpurINVO->PINV_DPPER;
			$data['default']['PINV_DPVAL'] 		= $getpurINVO->PINV_DPVAL;
			$data['default']['PINV_DPVALPPn']	= $getpurINVO->PINV_DPVALPPn;
			$data['default']['PINV_DPBACK'] 	= $getpurINVO->PINV_DPBACK;
			$data['default']['PINV_DPBACKCUR'] 	= $getpurINVO->PINV_DPBACKCUR;
			$data['default']['PINV_DPBACKPPn'] 	= $getpurINVO->PINV_DPBACKPPn;
			$data['default']['PINV_PROG'] 		= $getpurINVO->PINV_PROG;
			$data['default']['PINV_PROGVAL']	= $getpurINVO->PINV_PROGVAL;
			$data['default']['PINV_PROGVALPPn']	= $getpurINVO->PINV_PROGVALPPn;
			$data['default']['PINV_PROGAPP']	= $getpurINVO->PINV_PROGAPP;
			$data['default']['PINV_PROGAPPVAL'] = $getpurINVO->PINV_PROGAPPVAL;
			$data['default']['PINV_PROGCUR']	= $getpurINVO->PINV_PROGCUR;
			$data['default']['PINV_PROGCURVAL'] = $getpurINVO->PINV_PROGCURVAL;
			$data['default']['PINV_VALADD'] 	= $getpurINVO->PINV_VALADD;
			$data['default']['PINV_VALADDPPn'] 	= $getpurINVO->PINV_VALADDPPn;
			$data['default']['PINV_MATVAL'] 	= $getpurINVO->PINV_MATVAL;
			$data['default']['PINV_VALBEF']		= $getpurINVO->PINV_VALBEF;
			$data['default']['PINV_VALBEFPPn']	= $getpurINVO->PINV_VALBEFPPn;
			$data['default']['PINV_AKUMNEXT']	= $getpurINVO->PINV_AKUMNEXT;
			$data['default']['PINV_TOTVAL'] 	= $getpurINVO->PINV_TOTVAL;
			$data['default']['PINV_TOTVALPPn'] 	= $getpurINVO->PINV_TOTVALPPn;
			$data['default']['PINV_TOTVALPPhP'] = $getpurINVO->PINV_TOTVALPPhP;
			$data['default']['PINV_TOTVALPPh'] 	= $getpurINVO->PINV_TOTVALPPh;
			$data['default']['GPINV_TOTVAL'] 	= $getpurINVO->GPINV_TOTVAL;
			$data['default']['PINV_NOTES'] 		= $getpurINVO->PINV_NOTES;
			$data['default']['PINV_EMPID'] 		= $getpurINVO->PINV_EMPID;
			$data['default']['PINV_STAT'] 		= $getpurINVO->PINV_STAT;
			$data['default']['PATT_YEAR'] 		= $getpurINVO->PATT_YEAR;
			$data['default']['PATT_MONTH'] 		= $getpurINVO->PATT_MONTH;
			$data['default']['PATT_DATE'] 		= $getpurINVO->PATT_DATE;
			$data['default']['PINV_TAXABLE'] 	= $getpurINVO->PINV_TAXABLE;
			$data['default']['PATT_NUMBER'] 	= $getpurINVO->PATT_NUMBER;
			$data['default']['MC_REF'] 			= $getpurINVO->MC_REF;
			$data['PRJCODE'] 					= $getpurINVO->PRJCODE;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getpurINVO->PINV_CODE;
				$MenuCode 		= 'MN232';
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
			
			$this->load->view('v_project/v_project_invoice/project_invoice_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process() // OK
	{
		$this->load->model('m_project/m_project_invoice/m_project_invoice', '', TRUE);
		$this->load->model('m_project/m_project_invoice/m_project_invoice_RealINV', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		$comp_init 	= $this->session->userdata('comp_init');
		
		if ($this->session->userdata('login') == TRUE)
		{
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			
			date_default_timezone_set("Asia/Jakarta");
			
			$this->db->trans_begin();
			
			$PINV_CODE 				= $this->input->post('PINV_CODE');
			$PINV_MANNO 			= $this->input->post('PINV_MANNO');
			$PINV_KWITNO 			= $this->input->post('PINV_KWITNO');
			$PINV_FAKNO 			= $this->input->post('PINV_FAKNO');
			$PINV_INVOICE 			= $this->input->post('PINV_INVOICE');
			$PRJCODE 				= $this->input->post('PRJCODE');			
			$PINV_CAT				= $this->input->post('PINV_CAT');
			$PINV_DATE				= date('Y-m-d',strtotime($this->input->post('PINV_DATE')));
			/*$PINV_DateY			= date('Y',strtotime($this->input->post('PINV_DATE')));
			$PINV_DateM				= date('m',strtotime($this->input->post('PINV_DATE')));
			$PINV_DateD				= date('d',strtotime($this->input->post('PINV_DATE')));
			$PINV_DATEX				= mktime(0,0,0,$PINV_DateM,$PINV_DateD,$PINV_DateY);
			$PINV_TTOTerm			= 30;
			$PINV_ENDDATE 			= date("Y-m-d",strtotime("+$PINV_TTOTerm days",$PINV_DATEX));*/
			$PINV_ENDDATE			= date('Y-m-d',strtotime($this->input->post('PINV_ENDDATE')));
			$PINV_TOTVAL			= $this->input->post('PINV_TOTVAL');
			$PINV_OWNER				= $this->input->post('PINV_OWNER');
			
			$PINV_CREATED			= date('Y-m-d H:i:s');
			$PATT_YEAR				= date('Y',strtotime($this->input->post('PINV_DATE')));
			
			$AH_CODE				= $PINV_CODE;
			$AH_APPLEV				= $this->input->post('APP_LEVEL');
			$AH_APPROVER			= $DefEmp_ID;
			$AH_APPROVED			= date('Y-m-d H:i:s');
			$AH_NOTES				= $this->input->post('PINV_NOTES');
			$AH_ISLAST				= $this->input->post('IS_LAST');
			$PINV_STAT				= $this->input->post('PINV_STAT');
			$PINV_PPNPERC			= $this->input->post('PINV_PPNPERC');
			
			$PINV_RETCUTCUR			= $this->input->post('PINV_RETCUTCUR');

			$PINV_AMOUNT 			= $this->input->post('PINV_PAYAKUM');
			$PINV_RETVAL 			= $this->input->post('PINV_RETCUT');
			$PINV_PPNVAL 			= $this->input->post('PINV_PAYAKUMPPn');
			$PINV_PPHVAL 			= $this->input->post('PINV_TOTVALPPH');

			if($PINV_CAT == 1)
			{
				$PINV_SOURCE		= "";									// DP
				$REF_NUM			= "DP";
				$PINV_TOTVAL		= $this->input->post('PINV_DPVAL');
				$PINV_TOTVALPPn		= $this->input->post('PINV_DPVALPPn');
				$PINV_TOTVALPPHP	= $this->input->post('PINV_TOTVALPPHP');
				$PINV_TOTVALPPH		= $this->input->post('PINV_TOTVALPPH');
				//$PINV_TOTVALnPPN	= $PINV_TOTVAL + $PINV_TOTVALPPn - $PINV_TOTVALPPH;
				// KARENA ADA PERHITUNGAN PPH maka
				$PINV_TOTVALnPPN	= $PINV_TOTVAL;
			}
			elseif($PINV_CAT == 2)
			{
				$PINV_SOURCE		= $this->input->post('PINV_SOURCE1');	// MC
				$REF_NUM			= "MC";
				$PINV_TOTVAL		= $this->input->post('PINV_TOTVAL');
				$PINV_TOTVALPPn		= $this->input->post('PINV_TOTVALPPn');
				$PINV_TOTVALPPHP	= $this->input->post('PINV_TOTVALPPHP');
				$PINV_TOTVALPPH		= $this->input->post('PINV_TOTVALPPH');
				//$PINV_TOTVALnPPN	= $PINV_TOTVAL + $PINV_TOTVALPPn - $PINV_TOTVALPPH;
				$PINV_TOTVALnPPN	= $this->input->post('PINV_RECEIVETOT');
			}
			elseif($PINV_CAT == 3)
			{
				$PINV_SOURCE		= $this->input->post('PINV_SOURCE2');	// MC
				$REF_NUM			= "SI";
				$PINV_TOTVAL		= $this->input->post('PINV_TOTVAL');
				$PINV_TOTVALPPn		= $this->input->post('PINV_TOTVALPPn');
				$PINV_TOTVALPPHP	= $this->input->post('PINV_TOTVALPPHP');
				$PINV_TOTVALPPH		= $this->input->post('PINV_TOTVALPPH');
				//$PINV_TOTVALnPPN	= $PINV_TOTVAL + $PINV_TOTVALPPn - $PINV_TOTVALPPH;
				$PINV_TOTVALnPPN	= $this->input->post('PINV_RECEIVETOT');
			}
			
			if($PINV_STAT == 1)
			{
				$MC_APPSTAT = 1;
			}
			elseif($PINV_STAT == 2)
			{
				$MC_APPSTAT = 2;
			}
			elseif($PINV_STAT == 3)
			{
				$MC_APPSTAT = 3;
				
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
			}
			
			$prohPINVH 		= array('PINV_CODE' 		=> $PINV_CODE,
									'PINV_MANNO'		=> $PINV_MANNO,
									'PINV_KWITNO'		=> $PINV_KWITNO,
									'PINV_FAKNO'		=> $PINV_FAKNO,
									'PINV_INVOICE'		=> $PINV_INVOICE,
									'PINV_STEP'			=> $this->input->post('PINV_STEP'),
									'PINV_CAT'			=> $this->input->post('PINV_CAT'),
									'PINV_MMC'			=> $this->input->post('PINV_MMC'),
									'PINV_SOURCE'		=> $PINV_SOURCE,
									'PINV_SOURCE_MN'	=> $this->input->post('PINV_SOURCE_MN'),
									'PRJCODE'			=> $PRJCODE,
									'PINV_DATE'			=> $PINV_DATE,
									'PINV_ENDDATE'		=> $PINV_ENDDATE,
									'PINV_CREATED'		=> $PINV_CREATED,
									'PINV_PPNPERC'		=> $PINV_PPNPERC,
									'PINV_OWNER'		=> $this->input->post('PINV_OWNER'),
									'PINV_RETVAL'		=> $this->input->post('PINV_RETVAL'),
									'PINV_RETCUT'		=> $this->input->post('PINV_RETCUT'),
									'PINV_RETCUTCUR'	=> $this->input->post('PINV_RETCUTCUR'),
									'PINV_DPPER'		=> $this->input->post('PINV_DPPER'),
									'PINV_DPVAL'		=> $this->input->post('PINV_DPVAL'),
									'PINV_DPVALPPn'		=> $this->input->post('PINV_DPVALPPn'),
									'PINV_DPBACK'		=> $this->input->post('PINV_DPBACK'),
									'PINV_PROG'			=> $this->input->post('PINV_PROG'),
									'PINV_RETCUTP'		=> $this->input->post('PINV_RETCUTP'),
									'PINV_PROGVAL'		=> $this->input->post('PINV_PROGVAL'),
									'PINV_VALADD'		=> $this->input->post('PINV_VALADD'),					
									'PINV_VALBEF'		=> $this->input->post('PINV_VALBEF'),					
									'PINV_AKUMNEXT'		=> $this->input->post('PINV_AKUMNEXT'),
									'PINV_OWNER'		=> $this->input->post('PINV_OWNER'),
									'PINV_RETVAL'		=> $this->input->post('PINV_RETVAL'),
									'PINV_RETCUT'		=> $this->input->post('PINV_RETCUT'),
									'PINV_DPPER'		=> $this->input->post('PINV_DPPER'),
									'PINV_DPVAL'		=> $this->input->post('PINV_DPVAL'),
									'PINV_DPVALPPn'		=> $this->input->post('PINV_DPVALPPn'),
									'PINV_DPBACK'		=> $this->input->post('PINV_DPBACK'),
									'PINV_DPBACKCUR'	=> $this->input->post('PINV_DPBACKCUR'),
									'PINV_PROG'			=> $this->input->post('PINV_PROG'),
									'PINV_RETCUTP'		=> $this->input->post('PINV_RETCUTP'),
									'PINV_PROGVAL'		=> $this->input->post('PINV_PROGVAL'),
									'PINV_PROGAPP'		=> $this->input->post('PINV_PROG'),
									'PINV_PROGAPPVAL'	=> $this->input->post('PINV_PROGVAL'),
									'PINV_PROGCUR'		=> $this->input->post('PINV_PROGCUR'),
									'PINV_PROGCURVAL'	=> $this->input->post('PINV_PROGCURVAL'),
									'PINV_VALADD'		=> $this->input->post('PINV_VALADD'),
									'PINV_VALBEF'		=> $this->input->post('PINV_VALBEF'),
									'PINV_AKUMNEXT'		=> $this->input->post('PINV_AKUMNEXT'),
									'PINV_TAXABLE'		=> $this->input->post('PINV_TAXABLE'),
									'PINV_TOTVAL'		=> $PINV_TOTVAL,
									'PINV_TOTVALPPn'	=> $PINV_TOTVALPPn,
									'PINV_TOTVALPPhP'	=> $PINV_TOTVALPPHP,
									'PINV_TOTVALPPh'	=> $PINV_TOTVALPPH,
									'GPINV_TOTVAL'		=> $this->input->post('PINV_RECEIVETOT'),
									'MC_REF'			=> $this->input->post('MC_REF'),
									'PINV_EMPID'		=> $DefEmp_ID,
									'PINV_STAT'			=> $PINV_STAT,
									'PATT_YEAR'			=> $PATT_YEAR);							
			$this->m_project_invoice->update($PINV_CODE, $prohPINVH);
				
			// START : ADD CONCLUSION
			if($PINV_CAT == 2)
			{
				$this->load->model('m_project/m_project_mc/m_project_mc', '', TRUE);
				$PINV_SOURCE		= $this->input->post('PINV_SOURCE1');
				$MC_CODE			= $this->input->post('PINV_SOURCE1');
				$INV_AMOUNT			= $this->input->post('PINV_PAYAKUM');
				//$INV_AMOUNT_PPn= $INV_AMOUNT * 0.1;
				//$INV_AMOUNT_PPh1= $INV_AMOUNT * 0.03;
				//$GINV_AMOUNT	= $INV_AMOUNT + $INV_AMOUNT_PPn - $INV_AMOUNT_PPh;
				$INV_AMOUNT_PPn		= $this->input->post('PINV_PAYAKUMPPn');
				$INV_AMOUNT_PPh		= $this->input->post('PINV_TOTVALPPH');
				$INV_AMOUNT_PPhP	= $this->input->post('PINV_TOTVALPPHP');
				$GINV_AMOUNT		= $this->input->post('PINV_TOTVAL');
				$dataMCH 			= array('INV_NUMBER' 		=> $PINV_CODE,
											'INV_DATE'			=> $PINV_DATE,
											'INV_SOURCE'		=> $PINV_SOURCE,
											'INV_AMOUNT'		=> $INV_AMOUNT,
											'INV_AMOUNT_PPn'	=> $INV_AMOUNT_PPn,
											'INV_AMOUNT_PPh'	=> $INV_AMOUNT_PPh);
				$this->m_project_mc->updateConc($MC_CODE, $dataMCH, $PRJCODE);
			}
			// END : ADD CONCLUSION

			if($PINV_STAT == 2)
			{
				// START : UPDATE FINANCIAL DASHBOARD
					$PINV_VAL 	= $this->input->post('PINV_RECEIVETOT');
					$finDASH 	= array('PRJCODE'	=> $PRJCODE,
										'PERIODE'	=> $PINV_DATE,
										'FVAL'		=> $PINV_VAL,
										'FNAME'		=> "PINV_VAL");										
					$this->m_updash->updFINDASH($finDASH);
				// END : UPDATE FINANCIAL DASHBOARD
			}
			
			// CREATE JOURNAL
			if($PINV_STAT == 3 && $AH_ISLAST == 1)
			{
				$CREATED		= date('Y-m-d H:i:s');
				$CREATER		= $DefEmp_ID;
				
				if($PINV_CAT == 2)
				{
					// START : SETTING L/R
						$this->load->model('m_updash/m_updash', '', TRUE);
						$PERIODED	= $PINV_DATE;
						$PERIODM	= date('m', strtotime($PERIODED));
						$PERIODY	= date('Y', strtotime($PERIODED));
						$getLR		= "tbl_profitloss WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
										AND YEAR(PERIODE) = '$PERIODY'";
						$resLR		= $this->db->count_all($getLR);
						$resLR		= 0;
						if($resLR	== 0)
						{
							$this->m_updash->createLR($PRJCODE, $PERIODED);
						}
					// END : SETTING L/R
					
					// START : UPDATE L/R
						$MC_PROGAPP		= $this->input->post('MC_PROGAPP');
						$updLR			= "UPDATE tbl_profitloss SET INV_REAL = INV_REAL+$GINV_AMOUNT
											WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
												AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					// END : UPDATE L/R
				}
				elseif($PINV_CAT == 3)
				{
					$INV_AMOUNT_PPn		= $this->input->post('PINV_PAYAKUMPPn');
					$INV_AMOUNT_PPh		= $this->input->post('PINV_TOTVALPPH');
					$INV_AMOUNT_PPhP	= $this->input->post('PINV_TOTVALPPHP');
					$GINV_AMOUNT		= $this->input->post('PINV_PROGVAL');
					
					// START : SETTING L/R
						$this->load->model('m_updash/m_updash', '', TRUE);
						$PERIODED	= $PINV_DATE;
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
					
					// START : UPDATE L/R
						$PINV_PROG		= $this->input->post('PINV_PROG');
						$updLR			= "UPDATE tbl_profitloss SET SI_REAL = SI_REAL+$GINV_AMOUNT
											WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
												AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					// END : UPDATE L/R
				}
				
				if($PINV_CAT == 1)		// DP
				{
					// START : JOURNAL HEADER
						$this->load->model('m_journal/m_journal', '', TRUE);
						
						$JournalH_Code	= $PINV_CODE;
						$JournalType	= 'PRJINV';
						$JournalH_Date	= $PINV_DATE;
						$Company_ID		= $comp_init;
						$DOCSource		= $PINV_SOURCE;
						$LastUpdate		= $CREATED;
						$WH_CODE		= $PRJCODE;
						$Refer_Number	= $REF_NUM;
						$RefType		= 'PRJINV';
						$PRJCODE		= $PRJCODE;
						
						$parameters = array('JournalH_Code' 	=> $JournalH_Code,
											'JournalType'		=> $JournalType,
											'JournalH_Date' 	=> $JournalH_Date,
											'JournalH_Desc' 	=> $this->input->post('PINV_NOTES'),
											'Manual_No'			=> $PINV_MANNO,
											'Company_ID' 		=> $Company_ID,
											'Source'			=> $DOCSource,
											'Emp_ID'			=> $DefEmp_ID,
											'LastUpdate'		=> $LastUpdate,	
											'KursAmount_tobase'	=> 1,
											'WHCODE'			=> $WH_CODE,
											'Reference_Number'	=> $Refer_Number,
											'RefType'			=> $RefType,
											'PRJCODE'			=> $PRJCODE,
											'PPNH_Amount'		=> $PINV_TOTVALPPn,
											'PPHH_Amount'		=> $PINV_TOTVALPPH);
						$this->m_journal->createJournalH($JournalH_Code, $parameters);
					// END : JOURNAL HEADER
					
					// START : JOURNAL DETAIL
						$sqlCL_D	= "tbl_owner WHERE own_Code = '$PINV_OWNER'";
						$resCL_D	= $this->db->count_all($sqlCL_D);
						if($resCL_D > 0)
						{
							$ACC_NUM		= "110430";
							$ACC_NUM2		= "401";
							$ACC_NUM3		= "1107040";
							$ACC_NUM4		= "210501";
							$ACC_NUM5		= "210501";
							$sqlL_D			= "SELECT own_ACC_ID AS ACC_ID, own_ACC_ID2 AS ACC_ID2,
													own_ACC_ID3 AS ACC_ID3, own_ACC_ID4 AS ACC_ID4, own_ACC_ID5 AS ACC_ID5
												FROM tbl_owner WHERE own_Code = '$PINV_OWNER'";
							$resL_D = $this->db->query($sqlL_D)->result();					
							foreach($resL_D as $rowL_D):
								$ACC_NUM	= $rowL_D->ACC_ID;		// Akun Piutang
								$ACC_NUM2	= $rowL_D->ACC_ID2;		// Akun DP
								$ACC_NUM3	= $rowL_D->ACC_ID3;		// Akun (PPh Inv)
								$ACC_NUM4	= $rowL_D->ACC_ID4;		// Akun (PPn Inv)
								$ACC_NUM5	= $rowL_D->ACC_ID5;		// Akun Lain-Lain
							endforeach;
						}
						else
						{
							$ACC_NUM		= "110430";
							$ACC_NUM2		= "401";
							$ACC_NUM3		= "1107040";
							$ACC_NUM4		= "210501";
							$ACC_NUM5		= "210501";
						}
						
						// JOURNAL
							$JournalH_Code	= $PINV_CODE;
							$JournalType	= 'PRJINV';
							$JournalH_Date	= $PINV_DATE;
							$Company_ID		= $comp_init;
							$Currency_ID	= 'IDR';
							$DOCSource		= $PINV_SOURCE;
							$LastUpdate		= $CREATED;
							$WH_CODE		= $PRJCODE;
							$Refer_Number	= $REF_NUM;
							$RefType		= 'PRJINV';
							$JSource		= $REF_NUM;
							$PRJCODE		= $PRJCODE;
							
							$ITM_CODE 		= '';
							$ACC_ID 		= $ACC_NUM;
							$ITM_UNIT 		= "";
							$ITM_QTY 		= 1;
							$ITM_PRICE 		= $PINV_AMOUNT;
							$ITM_DISC 		= 0;
							$TAXCODE1 		= '';
							$TAXPRICE1		= $PINV_PPNVAL;
							$TAXPRICE1		= $PINV_PPHVAL;
							$PINV_NOTES 	= $this->input->post('PINV_NOTES');

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
												'TRANS_CATEG' 		=> 'PRJINV',			// PRJINV = PROJECT INVOICE
												'ITM_CODE' 			=> $ITM_CODE,
												'ACC_ID' 			=> $ACC_ID,
												'ACC_ID2' 			=> $ACC_NUM2,
												'ITM_UNIT' 			=> $ITM_UNIT,
												'ITM_QTY' 			=> $ITM_QTY,
												'ITM_PRICE' 		=> $PINV_AMOUNT,
												'ITM_RETENSI' 		=> $PINV_RETVAL,
												'ITM_DISC' 			=> $ITM_DISC,
												'TAXCODE1' 			=> $TAXCODE1,
												'TAXPRICE1' 		=> $PINV_PPNVAL,
												'TAXPRICE2'			=> $PINV_PPHVAL,
												'PINV_NOTES'		=> $PINV_NOTES);												
							$this->m_journal->createJournalD($JournalH_Code, $parameters);

						// HASIL RAPAT DENGAN ACCOUNTING PD TGL. 08 JUNI 2022 (TIDAK ADA JURNAL PPH)
						/* ADA USULAN JURNAL FAKTUR PROYEK TIPE UANG MUKA (DP)
							PIUTANG				1010	
							PPN KELUARAN				10
							UANG MUKA					1000
						*/

						/*if($PINV_TOTVALPPH > 0)
						{
							$JournalH_Code	= $JournalH_Code;
							$JournalType	= 'PRJINV3';
							$JournalH_Date	= $JournalH_Date;
							$Company_ID		= $comp_init;
							$Currency_ID	= 'IDR';
							$DOCSource		= $PINV_SOURCE;
							$LastUpdate		= $CREATED;
							$WH_CODE		= $PRJCODE;
							$Refer_Number	= $PINV_SOURCE;
							$RefType		= 'PRJINV';
							$JSource		= 'PRJINV3';
							$PRJCODE		= $PRJCODE;
							$Notes			= "PPh frm. Invoice";
							
							$ITM_CODE 		= $JournalH_Code;
							$ACC_ID 		= $ACC_NUM3;
							$ITM_UNIT 		= '';
							$ITM_QTY 		= 1;
							$ITM_PRICE 		= $PINV_TOTVALPPH;
							$ITM_DISC 		= 0;
							$TAXCODE1 		= "TAX02";
							$TAXPRICE1		= 0;
							
							$TRANS_CATEG 	= "PRJINV3~$PINV_OWNER";							// PERHATIKAN DI SINI. PENTING
							
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
												'TRANS_CATEG' 		=> $TRANS_CATEG,			// PINV = PURCHASE INVOICE
												'ITM_CODE' 			=> $ITM_CODE,
												'ACC_ID' 			=> $ACC_ID,
												'ITM_UNIT' 			=> $ITM_UNIT,
												'ITM_QTY' 			=> $ITM_QTY,
												'ITM_PRICE' 		=> $ITM_PRICE,
												'ITM_DISC' 			=> $ITM_DISC,
												'TAXCODE1' 			=> $TAXCODE1,
												'TAXPRICE1' 		=> $TAXPRICE1,
												'Notes' 			=> $Notes);												
							$this->m_journal->createJournalD($JournalH_Code, $parameters);
						}*/
						
						// PPN JIKA ADA
							if($PINV_TOTVALPPn > 0)
							{
								$PINV_TAXABLE	= $this->input->post('PINV_TAXABLE');
								$JournalH_Code	= $JournalH_Code;
								$JournalType	= 'PRJINV4';
								$JournalH_Date	= $JournalH_Date;
								$Company_ID		= $comp_init;
								$Currency_ID	= 'IDR';
								$DOCSource		= $PINV_SOURCE;
								$LastUpdate		= $CREATED;
								$WH_CODE		= $PRJCODE;
								$Refer_Number	= $PINV_SOURCE;
								$RefType		= 'PRJINV';
								$JSource		= 'PRJINV4';
								$PRJCODE		= $PRJCODE;
								$Notes			= "PPn $AH_NOTES";
								
								$ITM_CODE 		= $JournalH_Code;
								$ACC_ID 		= $ACC_NUM4;
								$ITM_UNIT 		= '';
								$ITM_QTY 		= 1;
								$ITM_PRICE 		= $PINV_PPNVAL;
								$ITM_DISC 		= 0;
								$TAXCODE1 		= "";
								$TAXPRICE1		= 0;
								
								$TRANS_CATEG 	= "PRJINV4~$PINV_OWNER";							// PERHATIKAN DI SINI. PENTING
								
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
													'TRANS_CATEG' 		=> $TRANS_CATEG,			// PINV = PURCHASE INVOICE
													'ITM_CODE' 			=> $ITM_CODE,
													'ACC_ID' 			=> $ACC_ID,
													'ITM_UNIT' 			=> $ITM_UNIT,
													'ITM_QTY' 			=> $ITM_QTY,
													'ITM_PRICE' 		=> $ITM_PRICE,
													'ITM_DISC' 			=> $ITM_DISC,
													'TAXCODE1' 			=> $TAXCODE1,
													'TAXPRICE1' 		=> $TAXPRICE1,
													'Notes' 			=> $Notes,
													'PINV_TAXABLE'		=> $PINV_TAXABLE);												
								$this->m_journal->createJournalD($JournalH_Code, $parameters);
							}

						// RETENSI JIKA ADA
							if($PINV_RETCUTCUR > 0)
							{
								$JournalH_Code	= $JournalH_Code;
								$JournalType	= 'PRJINV-MC';
								$JournalH_Date	= $JournalH_Date;
								$Company_ID		= $comp_init;
								$Currency_ID	= 'IDR';
								$DOCSource		= $PINV_SOURCE;
								$LastUpdate		= $CREATED;
								$WH_CODE		= $PRJCODE;
								$Refer_Number	= $PINV_SOURCE;
								$RefType		= 'PRJINV-MC';
								$JSource		= 'PRJINV5';
								$PRJCODE		= $PRJCODE;
								$Notes			= "Ret frm. Invoice Ref. $PINV_SOURCE";
								
								$ITM_CODE 		= $JournalH_Code;
								$ACC_ID 		= $ACC_NUM4;
								$ITM_UNIT 		= '';
								$ITM_QTY 		= 1;
								$ITM_PRICE 		= $PINV_RETCUTCUR;
								$ITM_DISC 		= 0;
								$TAXCODE1 		= "TAX02";
								$TAXPRICE1		= 0;
								
								$TRANS_CATEG 	= "PRJINV5~$PINV_OWNER";							// PERHATIKAN DI SINI. PENTING
								
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
													'TRANS_CATEG' 		=> $TRANS_CATEG,			// PINV = PURCHASE INVOICE
													'ITM_CODE' 			=> $ITM_CODE,
													'ACC_ID' 			=> $ACC_ID,
													'ITM_UNIT' 			=> $ITM_UNIT,
													'ITM_QTY' 			=> $ITM_QTY,
													'ITM_PRICE' 		=> $ITM_PRICE,
													'ITM_DISC' 			=> $ITM_DISC,
													'TAXCODE1' 			=> $TAXCODE1,
													'TAXPRICE1' 		=> $TAXPRICE1,
													'Notes' 			=> $Notes);												
								$this->m_journal->createJournalD($JournalH_Code, $parameters);
							}
					// END : JOURNAL DETAIL
				}
				elseif($PINV_CAT == 2)	// MC
				{
					$TOT_K		= 0;
					// START : JOURNAL HEADER
						$this->load->model('m_journal/m_journal', '', TRUE);
						
						$JournalH_Code	= $PINV_CODE;
						$JournalType	= 'PRJINV';
						$JournalH_Date	= $PINV_DATE;
						$Company_ID		= $comp_init;
						$DOCSource		= $PINV_SOURCE;
						$LastUpdate		= $CREATED;
						$WH_CODE		= $PRJCODE;
						$Refer_Number	= $REF_NUM;
						$RefType		= 'PRJINV-MC';
						$PRJCODE		= $PRJCODE;
						
						$parameters = array('JournalH_Code' 	=> $JournalH_Code,
											'JournalType'		=> $JournalType,
											'JournalH_Date' 	=> $JournalH_Date,
											'JournalH_Desc' 	=> $this->input->post('PINV_NOTES'),
											'Manual_No'			=> $PINV_MANNO,
											'Company_ID' 		=> $Company_ID,
											'Source'			=> $DOCSource,
											'Emp_ID'			=> $DefEmp_ID,
											'LastUpdate'		=> $LastUpdate,	
											'KursAmount_tobase'	=> 1,
											'WHCODE'			=> $WH_CODE,
											'Reference_Number'	=> $Refer_Number,
											'RefType'			=> $RefType,
											'PRJCODE'			=> $PRJCODE);
						$this->m_journal->createJournalH($JournalH_Code, $parameters);
					// END : JOURNAL HEADER
					
					// START : JOURNAL DETAIL
						// JORUNAL
							$ACC_NUM		= "";
							$ACC_NUM2		= "";
							$ACC_NUM3		= "";
							$ACC_NUM4		= "";
							$ACC_NUM5		= "";
							$ACC_NUM6		= "";

							// GET ACCOUNT AR FROM GLOBAL SETTING
								$ACC_NUM_B 	= "";
								$ACC_NUM2_B = "";
								$ACC_NUM3_B = "";
								$ACC_NUM4_B = "";
								$ACC_NUM5_B = "";
								$ACC_NUM6_B = "";
								$sqlL_D			= "SELECT ACC_ID_MCR, ACC_ID_MCP, ACC_ID_MCT, ACC_ID_MCRET, ACC_ID_MCI, ACC_ID_MCPPn FROM tglobalsetting";
								$resL_D = $this->db->query($sqlL_D)->result();					
								foreach($resL_D as $rowL_D):
									$ACC_NUM_B	= $rowL_D->ACC_ID_MCR;		// Piutang
									$ACC_NUM2_B	= $rowL_D->ACC_ID_MCP;		// Utang		- Pengembalian DP
									$ACC_NUM3_B	= $rowL_D->ACC_ID_MCT;		// Tax PPh
									$ACC_NUM4_B	= $rowL_D->ACC_ID_MCRET;	// Retensi
									$ACC_NUM5_B	= $rowL_D->ACC_ID_MCI;		// Income
									$ACC_NUM6_B	= $rowL_D->ACC_ID_MCPPn;	// PPn
								endforeach;

							$ACC_NUM 	= $ACC_NUM_B;
							$ACC_NUM2 	= $ACC_NUM2_B;
							$ACC_NUM3 	= $ACC_NUM3_B;
							$ACC_NUM4	= $ACC_NUM4_B;
							$ACC_NUM5 	= $ACC_NUM5_B;
							$ACC_NUM6 	= $ACC_NUM6_B;

							if($ACC_NUM_A == "")
								$ACC_NUM 	= $ACC_NUM_B;
							if($ACC_NUM2_A == "")
								$ACC_NUM2 	= $ACC_NUM2_B;
							if($ACC_NUM3_A == "")
								$ACC_NUM3 	= $ACC_NUM3_B;
							if($ACC_NUM6_A == "")
								$ACC_NUM6 	= $ACC_NUM6_B;
								
							$JournalH_Code	= $PINV_CODE;
							$JournalType	= 'PRJINV-MC';
							$JournalH_Date	= $PINV_DATE;
							$Company_ID		= $comp_init;
							$Currency_ID	= 'IDR';
							$DOCSource		= $PINV_SOURCE;
							$LastUpdate		= $CREATED;
							$WH_CODE		= $PRJCODE;
							$Refer_Number	= $REF_NUM;
							$RefType		= 'PRJINV-MC';
							$JSource		= $REF_NUM;
							$PRJCODE		= $PRJCODE;
							
							$ITM_CODE 		= '';
							$ACC_ID 		= $ACC_NUM;
							$ITM_UNIT 		= "";
							$ITM_QTY 		= 1;
							$ITM_PRICE 		= $PINV_TOTVALnPPN;
							$ITM_DISC 		= 0;
							$TAXCODE1 		= '';
							//$TAXPRICE1	= $PINV_TOTVALPPn - $PINV_TOTVALPPH;
							$TAXPRICE1		= 0;
							
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
												'TRANS_CATEG' 		=> 'PRJINV-MC',			// PRJINV = PROJECT INVOICE
												'ITM_CODE' 			=> $ITM_CODE,
												'ACC_ID' 			=> $ACC_ID,
												'ACC_ID2' 			=> $ACC_NUM2,
												'ITM_UNIT' 			=> $ITM_UNIT,
												'ITM_QTY' 			=> $ITM_QTY,
												'ITM_PRICE' 		=> $ITM_PRICE,
												'ITM_DISC' 			=> $ITM_DISC,
												'TAXCODE1' 			=> $TAXCODE1,
												'TAXPRICE1' 		=> $TAXPRICE1);												
							$this->m_journal->createJournalD($JournalH_Code, $parameters);
						
						// PENGEMBALIAN DP JIKA ADA 	===> D E B I T  O N L Y
							$PINV_DPBACK	= $this->input->post('PINV_DPBACK');
							$PINV_DPBACKCUR	= $this->input->post('PINV_DPBACKCUR');
							if($PINV_DPBACKCUR > 0)
							{
								$JournalH_Code	= $JournalH_Code;
								$JournalType	= 'PRJINV-MC';
								$JournalH_Date	= $JournalH_Date;
								$Company_ID		= $comp_init;
								$Currency_ID	= 'IDR';
								$DOCSource		= $PINV_SOURCE;
								$LastUpdate		= $CREATED;
								$WH_CODE		= $PRJCODE;
								$Refer_Number	= $PINV_SOURCE;
								$RefType		= 'PRJINV-MC';
								$JSource		= 'PRJINV5';
								$PRJCODE		= $PRJCODE;
								$Notes			= "DP Back frm Inv. Ref. $PINV_SOURCE";
								
								$ITM_CODE 		= $JournalH_Code;
								$ACC_ID 		= $ACC_NUM2;
								$ITM_UNIT 		= '';
								$ITM_QTY 		= 1;
								$ITM_PRICE 		= $PINV_DPBACKCUR;
								$ITM_DISC 		= 0;
								$TAXCODE1 		= "TAX01";
								$TAXPRICE1		= 0;
								
								$TRANS_CATEG 	= "PRJINV5~$PINV_OWNER";							// PERHATIKAN DI SINI. PENTING
								
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
													'TRANS_CATEG' 		=> $TRANS_CATEG,			// PINV = PURCHASE INVOICE
													'ITM_CODE' 			=> $ITM_CODE,
													'ACC_ID' 			=> $ACC_ID,
													'ITM_UNIT' 			=> $ITM_UNIT,
													'ITM_QTY' 			=> $ITM_QTY,
													'ITM_PRICE' 		=> $ITM_PRICE,
													'ITM_DISC' 			=> $ITM_DISC,
													'TAXCODE1' 			=> $TAXCODE1,
													'TAXPRICE1' 		=> $TAXPRICE1,
													'Notes' 			=> $Notes);												
								$this->m_journal->createJournalD($JournalH_Code, $parameters);
							}
						
						// PPH JIKA ADA 				===> D E B I T  O N L Y
							if($PINV_TOTVALPPH > 0)
							{
								$JournalH_Code	= $JournalH_Code;
								$JournalType	= 'PRJINV-MC';
								$JournalH_Date	= $JournalH_Date;
								$Company_ID		= $comp_init;
								$Currency_ID	= 'IDR';
								$DOCSource		= $PINV_SOURCE;
								$LastUpdate		= $CREATED;
								$WH_CODE		= $PRJCODE;
								$Refer_Number	= $PINV_SOURCE;
								$RefType		= 'PRJINV-MC';
								$JSource		= 'PRJINV3';
								$PRJCODE		= $PRJCODE;
								$Notes			= "PPh frm. Invoice Ref. $PINV_SOURCE";
								
								$ITM_CODE 		= $JournalH_Code;
								$ACC_ID 		= $ACC_NUM3;
								$ITM_UNIT 		= '';
								$ITM_QTY 		= 1;
								$ITM_PRICE 		= $PINV_TOTVALPPH;
								$ITM_DISC 		= 0;
								$TAXCODE1 		= "TAX02";
								$TAXPRICE1		= 0;
								
								$TRANS_CATEG 	= "PRJINV3~$PINV_OWNER";							// PERHATIKAN DI SINI. PENTING
								
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
													'TRANS_CATEG' 		=> $TRANS_CATEG,			// PINV = PURCHASE INVOICE
													'ITM_CODE' 			=> $ITM_CODE,
													'ACC_ID' 			=> $ACC_ID,
													'ITM_UNIT' 			=> $ITM_UNIT,
													'ITM_QTY' 			=> $ITM_QTY,
													'ITM_PRICE' 		=> $ITM_PRICE,
													'ITM_DISC' 			=> $ITM_DISC,
													'TAXCODE1' 			=> $TAXCODE1,
													'TAXPRICE1' 		=> $TAXPRICE1,
													'Notes' 			=> $Notes);												
								$this->m_journal->createJournalD($JournalH_Code, $parameters);
							}
						
						// RETENSI JIKA ADA 			===> D E B I T  O N L Y
							$PINV_RETCUTCUR	= $this->input->post('PINV_RETCUTCUR');
							if($PINV_RETCUTCUR > 0)
							{
								$JournalH_Code	= $JournalH_Code;
								$JournalType	= 'PRJINV-MC';
								$JournalH_Date	= $JournalH_Date;
								$Company_ID		= $comp_init;
								$Currency_ID	= 'IDR';
								$DOCSource		= $PINV_SOURCE;
								$LastUpdate		= $CREATED;
								$WH_CODE		= $PRJCODE;
								$Refer_Number	= $PINV_SOURCE;
								$RefType		= 'PRJINV-MC';
								$JSource		= 'PRJINV5';
								$PRJCODE		= $PRJCODE;
								$Notes			= "Ret frm. Invoice Ref. $PINV_SOURCE";
								
								$ITM_CODE 		= $JournalH_Code;
								$ACC_ID 		= $ACC_NUM4;
								$ITM_UNIT 		= '';
								$ITM_QTY 		= 1;
								$ITM_PRICE 		= $PINV_RETCUTCUR;
								$ITM_DISC 		= 0;
								$TAXCODE1 		= "TAX02";
								$TAXPRICE1		= 0;
								
								$TRANS_CATEG 	= "PRJINV5~$PINV_OWNER";							// PERHATIKAN DI SINI. PENTING
								
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
													'TRANS_CATEG' 		=> $TRANS_CATEG,			// PINV = PURCHASE INVOICE
													'ITM_CODE' 			=> $ITM_CODE,
													'ACC_ID' 			=> $ACC_ID,
													'ITM_UNIT' 			=> $ITM_UNIT,
													'ITM_QTY' 			=> $ITM_QTY,
													'ITM_PRICE' 		=> $ITM_PRICE,
													'ITM_DISC' 			=> $ITM_DISC,
													'TAXCODE1' 			=> $TAXCODE1,
													'TAXPRICE1' 		=> $TAXPRICE1,
													'Notes' 			=> $Notes);												
								$this->m_journal->createJournalD($JournalH_Code, $parameters);
							}
						
						// PPN JIKA ADA
							if($PINV_TOTVALPPn > 0)
							{
								$JournalH_Code	= $JournalH_Code;
								$JournalType	= 'PRJINV-MC';
								$JournalH_Date	= $JournalH_Date;
								$Company_ID		= $comp_init;
								$Currency_ID	= 'IDR';
								$DOCSource		= $PINV_SOURCE;
								$LastUpdate		= $CREATED;
								$WH_CODE		= $PRJCODE;
								$Refer_Number	= $PINV_SOURCE;
								$RefType		= 'PRJINV-MC';
								$JSource		= 'PRJINV4';
								$PRJCODE		= $PRJCODE;
								$Notes			= "PPn frm. Invoice Ref. $PINV_SOURCE";
								
								$ITM_CODE 		= $JournalH_Code;
								$ACC_ID 		= $ACC_NUM6;
								$ITM_UNIT 		= '';
								$ITM_QTY 		= 1;
								$ITM_PRICE 		= $PINV_TOTVALPPn;
								$ITM_DISC 		= 0;
								$TAXCODE1 		= "TAX02";
								$TAXPRICE1		= 0;
								
								$TRANS_CATEG 	= "PRJINV4~$PINV_OWNER";							// PERHATIKAN DI SINI. PENTING
								
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
													'TRANS_CATEG' 		=> $TRANS_CATEG,			// PINV = PURCHASE INVOICE
													'ITM_CODE' 			=> $ITM_CODE,
													'ACC_ID' 			=> $ACC_ID,
													'ITM_UNIT' 			=> $ITM_UNIT,
													'ITM_QTY' 			=> $ITM_QTY,
													'ITM_PRICE' 		=> $ITM_PRICE,
													'ITM_DISC' 			=> $ITM_DISC,
													'TAXCODE1' 			=> $TAXCODE1,
													'TAXPRICE1' 		=> $TAXPRICE1,
													'Notes' 			=> $Notes);												
								$this->m_journal->createJournalD($JournalH_Code, $parameters);
							}
						
						// PENDAPATAN
							$TOT_K		= $PINV_TOTVALnPPN + $PINV_DPBACKCUR + $PINV_TOTVALPPH + $PINV_RETCUTCUR - $PINV_TOTVALPPn;
							if($TOT_K > 0)
							{
								$JournalH_Code	= $JournalH_Code;
								$JournalType	= 'PRJINV-MC';
								$JournalH_Date	= $JournalH_Date;
								$Company_ID		= $comp_init;
								$Currency_ID	= 'IDR';
								$DOCSource		= $PINV_SOURCE;
								$LastUpdate		= $CREATED;
								$WH_CODE		= $PRJCODE;
								$Refer_Number	= $PINV_SOURCE;
								$RefType		= 'PRJINV-MC';
								$JSource		= 'PRJINV4';
								$PRJCODE		= $PRJCODE;
								$Notes			= "Pend. from MC Ref. $PINV_SOURCE";
								
								$ITM_CODE 		= $JournalH_Code;
								$ACC_ID 		= $ACC_NUM5;
								$ITM_UNIT 		= '';
								$ITM_QTY 		= 1;
								$ITM_PRICE 		= $TOT_K;
								$ITM_DISC 		= 0;
								$TAXCODE1 		= "TAX01";
								$TAXPRICE1		= 0;
								
								$TRANS_CATEG 	= "PRJINV4~$PINV_OWNER";							// PERHATIKAN DI SINI. PENTING
								
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
													'TRANS_CATEG' 		=> $TRANS_CATEG,			// PINV = PURCHASE INVOICE
													'ITM_CODE' 			=> $ITM_CODE,
													'ACC_ID' 			=> $ACC_ID,
													'ITM_UNIT' 			=> $ITM_UNIT,
													'ITM_QTY' 			=> $ITM_QTY,
													'ITM_PRICE' 		=> $ITM_PRICE,
													'ITM_DISC' 			=> $ITM_DISC,
													'TAXCODE1' 			=> $TAXCODE1,
													'TAXPRICE1' 		=> $TAXPRICE1,
													'Notes' 			=> $Notes);												
								$this->m_journal->createJournalD($JournalH_Code, $parameters);
							}
					// END : JOURNAL DETAIL
				}
				elseif($PINV_CAT == 3)	// SI
				{
					$TOT_K		= 0;
					// START : JOURNAL HEADER
						$this->load->model('m_journal/m_journal', '', TRUE);
						
						$JournalH_Code	= $PINV_CODE;
						$JournalType	= 'PRJINV';
						$JournalH_Date	= $PINV_DATE;
						$Company_ID		= $comp_init;
						$DOCSource		= $PINV_SOURCE;
						$LastUpdate		= $CREATED;
						$WH_CODE		= $PRJCODE;
						$Refer_Number	= $REF_NUM;
						$RefType		= 'PRJINV-SI';
						$PRJCODE		= $PRJCODE;
						
						$parameters = array('JournalH_Code' 	=> $JournalH_Code,
											'JournalType'		=> $JournalType,
											'JournalH_Date' 	=> $JournalH_Date,
											'JournalH_Desc' 	=> $this->input->post('PINV_NOTES'),
											'Manual_No'			=> $PINV_MANNO,
											'Company_ID' 		=> $Company_ID,
											'Source'			=> $DOCSource,
											'Emp_ID'			=> $DefEmp_ID,
											'LastUpdate'		=> $LastUpdate,	
											'KursAmount_tobase'	=> 1,
											'WHCODE'			=> $WH_CODE,
											'Reference_Number'	=> $Refer_Number,
											'RefType'			=> $RefType,
											'PRJCODE'			=> $PRJCODE);
						$this->m_journal->createJournalH($JournalH_Code, $parameters);
					// END : JOURNAL HEADER
					
					// START : JOURNAL DETAIL
						$ACC_NUM		= "110430";
						$ACC_NUM2		= "2106";
						$ACC_NUM3		= "1107040";
						$ACC_NUM4		= "110420";
						$ACC_NUM5		= "4101";
						$ACC_NUM6		= "210501";
						$sqlL_D			= "SELECT ACC_ID_MCR, ACC_ID_MCP, ACC_ID_MCT, ACC_ID_MCRET, ACC_ID_MCI, ACC_ID_MCPPn FROM tglobalsetting";
						$resL_D = $this->db->query($sqlL_D)->result();					
						foreach($resL_D as $rowL_D):
							$ACC_NUM	= $rowL_D->ACC_ID_MCR;		// Piutang
							$ACC_NUM2	= $rowL_D->ACC_ID_MCP;		// Utang		- Pengembalian DP
							$ACC_NUM3	= $rowL_D->ACC_ID_MCT;		// Tax
							$ACC_NUM4	= $rowL_D->ACC_ID_MCRET;	// Retensi
							$ACC_NUM5	= $rowL_D->ACC_ID_MCI;		// Income
							$ACC_NUM6	= $rowL_D->ACC_ID_MCPPn;		// PPn
						endforeach;
							
						$JournalH_Code	= $PINV_CODE;
						$JournalType	= 'PRJINV-SI';
						$JournalH_Date	= $PINV_DATE;
						$Company_ID		= $comp_init;
						$Currency_ID	= 'IDR';
						$DOCSource		= $PINV_SOURCE;
						$LastUpdate		= $CREATED;
						$WH_CODE		= $PRJCODE;
						$Refer_Number	= $REF_NUM;
						$RefType		= 'PRJINV-SI';
						$JSource		= $REF_NUM;
						$PRJCODE		= $PRJCODE;
						
						$ITM_CODE 		= '';
						$ACC_ID 		= $ACC_NUM;
						$ITM_UNIT 		= "";
						$ITM_QTY 		= 1;
						$ITM_PRICE 		= $PINV_TOTVALnPPN;
						$ITM_DISC 		= 0;
						$TAXCODE1 		= '';
						//$TAXPRICE1	= $PINV_TOTVALPPn - $PINV_TOTVALPPH;
						$TAXPRICE1		= 0;
						
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
											'TRANS_CATEG' 		=> 'PRJINV-MC',			// PRJINV = PROJECT INVOICE
											'ITM_CODE' 			=> $ITM_CODE,
											'ACC_ID' 			=> $ACC_ID,
											'ACC_ID2' 			=> $ACC_NUM2,
											'ITM_UNIT' 			=> $ITM_UNIT,
											'ITM_QTY' 			=> $ITM_QTY,
											'ITM_PRICE' 		=> $ITM_PRICE,
											'ITM_DISC' 			=> $ITM_DISC,
											'TAXCODE1' 			=> $TAXCODE1,
											'TAXPRICE1' 		=> $TAXPRICE1);												
						$this->m_journal->createJournalD($JournalH_Code, $parameters);
						
						// PENGEMBALIAN DP JIKA ADA
						$PINV_DPBACK	= $this->input->post('PINV_DPBACK');
						$PINV_DPBACKCUR	= $this->input->post('PINV_DPBACKCUR');
						if($PINV_DPBACKCUR > 0)
						{
							$JournalH_Code	= $JournalH_Code;
							$JournalType	= 'PRJINV-SI';
							$JournalH_Date	= $JournalH_Date;
							$Company_ID		= $comp_init;
							$Currency_ID	= 'IDR';
							$DOCSource		= $PINV_SOURCE;
							$LastUpdate		= $CREATED;
							$WH_CODE		= $PRJCODE;
							$Refer_Number	= $PINV_SOURCE;
							$RefType		= 'PRJINV-SI';
							$JSource		= 'PRJINV5';
							$PRJCODE		= $PRJCODE;
							$Notes			= "DP Back frm Inv. Ref. $PINV_SOURCE";
							
							$ITM_CODE 		= $JournalH_Code;
							$ACC_ID 		= $ACC_NUM2;
							$ITM_UNIT 		= '';
							$ITM_QTY 		= 1;
							$ITM_PRICE 		= $PINV_DPBACKCUR;
							$ITM_DISC 		= 0;
							$TAXCODE1 		= "TAX01";
							$TAXPRICE1		= 0;
							
							$TRANS_CATEG 	= "PRJINV5~$PINV_OWNER";							// PERHATIKAN DI SINI. PENTING
							
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
												'TRANS_CATEG' 		=> $TRANS_CATEG,			// PINV = PURCHASE INVOICE
												'ITM_CODE' 			=> $ITM_CODE,
												'ACC_ID' 			=> $ACC_ID,
												'ITM_UNIT' 			=> $ITM_UNIT,
												'ITM_QTY' 			=> $ITM_QTY,
												'ITM_PRICE' 		=> $ITM_PRICE,
												'ITM_DISC' 			=> $ITM_DISC,
												'TAXCODE1' 			=> $TAXCODE1,
												'TAXPRICE1' 		=> $TAXPRICE1,
												'Notes' 			=> $Notes);												
							$this->m_journal->createJournalD($JournalH_Code, $parameters);
						}
						
						// PPH JIKA ADA
						if($PINV_TOTVALPPH > 0)
						{
							$JournalH_Code	= $JournalH_Code;
							$JournalType	= 'PRJINV-SI';
							$JournalH_Date	= $JournalH_Date;
							$Company_ID		= $comp_init;
							$Currency_ID	= 'IDR';
							$DOCSource		= $PINV_SOURCE;
							$LastUpdate		= $CREATED;
							$WH_CODE		= $PRJCODE;
							$Refer_Number	= $PINV_SOURCE;
							$RefType		= 'PRJINV-SI';
							$JSource		= 'PRJINV3';
							$PRJCODE		= $PRJCODE;
							$Notes			= "PPh frm. Invoice Ref. $PINV_SOURCE";
							
							$ITM_CODE 		= $JournalH_Code;
							$ACC_ID 		= $ACC_NUM3;
							$ITM_UNIT 		= '';
							$ITM_QTY 		= 1;
							$ITM_PRICE 		= $PINV_TOTVALPPH;
							$ITM_DISC 		= 0;
							$TAXCODE1 		= "TAX02";
							$TAXPRICE1		= 0;
							
							$TRANS_CATEG 	= "PRJINV3~$PINV_OWNER";							// PERHATIKAN DI SINI. PENTING
							
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
												'TRANS_CATEG' 		=> $TRANS_CATEG,			// PINV = PURCHASE INVOICE
												'ITM_CODE' 			=> $ITM_CODE,
												'ACC_ID' 			=> $ACC_ID,
												'ITM_UNIT' 			=> $ITM_UNIT,
												'ITM_QTY' 			=> $ITM_QTY,
												'ITM_PRICE' 		=> $ITM_PRICE,
												'ITM_DISC' 			=> $ITM_DISC,
												'TAXCODE1' 			=> $TAXCODE1,
												'TAXPRICE1' 		=> $TAXPRICE1,
												'Notes' 			=> $Notes);												
							$this->m_journal->createJournalD($JournalH_Code, $parameters);
						}
						
						// RETENSI JIKA ADA
						$PINV_RETCUTCUR	= $this->input->post('PINV_RETCUTCUR');
						if($PINV_RETCUTCUR > 0)
						{
							$JournalH_Code	= $JournalH_Code;
							$JournalType	= 'PRJINV-SI';
							$JournalH_Date	= $JournalH_Date;
							$Company_ID		= $comp_init;
							$Currency_ID	= 'IDR';
							$DOCSource		= $PINV_SOURCE;
							$LastUpdate		= $CREATED;
							$WH_CODE		= $PRJCODE;
							$Refer_Number	= $PINV_SOURCE;
							$RefType		= 'PRJINV-SI';
							$JSource		= 'PRJINV5';
							$PRJCODE		= $PRJCODE;
							$Notes			= "Ret frm. Invoice Ref. $PINV_SOURCE";
							
							$ITM_CODE 		= $JournalH_Code;
							$ACC_ID 		= $ACC_NUM4;
							$ITM_UNIT 		= '';
							$ITM_QTY 		= 1;
							$ITM_PRICE 		= $PINV_RETCUTCUR;
							$ITM_DISC 		= 0;
							$TAXCODE1 		= "TAX02";
							$TAXPRICE1		= 0;
							
							$TRANS_CATEG 	= "PRJINV5~$PINV_OWNER";							// PERHATIKAN DI SINI. PENTING
							
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
												'TRANS_CATEG' 		=> $TRANS_CATEG,			// PINV = PURCHASE INVOICE
												'ITM_CODE' 			=> $ITM_CODE,
												'ACC_ID' 			=> $ACC_ID,
												'ITM_UNIT' 			=> $ITM_UNIT,
												'ITM_QTY' 			=> $ITM_QTY,
												'ITM_PRICE' 		=> $ITM_PRICE,
												'ITM_DISC' 			=> $ITM_DISC,
												'TAXCODE1' 			=> $TAXCODE1,
												'TAXPRICE1' 		=> $TAXPRICE1,
												'Notes' 			=> $Notes);												
							$this->m_journal->createJournalD($JournalH_Code, $parameters);
						}
						
						// PPN JIKA ADA
						if($PINV_TOTVALPPn > 0)
						{
							$JournalH_Code	= $JournalH_Code;
							$JournalType	= 'PRJINV-SI';
							$JournalH_Date	= $JournalH_Date;
							$Company_ID		= $comp_init;
							$Currency_ID	= 'IDR';
							$DOCSource		= $PINV_SOURCE;
							$LastUpdate		= $CREATED;
							$WH_CODE		= $PRJCODE;
							$Refer_Number	= $PINV_SOURCE;
							$RefType		= 'PRJINV-SI';
							$JSource		= 'PRJINV2';
							$PRJCODE		= $PRJCODE;
							$Notes			= "PPh frm. Invoice Ref. $PINV_SOURCE";
							
							$ITM_CODE 		= $JournalH_Code;
							$ACC_ID 		= $ACC_NUM6;
							$ITM_UNIT 		= '';
							$ITM_QTY 		= 1;
							$ITM_PRICE 		= $PINV_TOTVALPPn;
							$ITM_DISC 		= 0;
							$TAXCODE1 		= "TAX02";
							$TAXPRICE1		= 0;
							
							$TRANS_CATEG 	= "PRJINV2~$PINV_OWNER";							// PERHATIKAN DI SINI. PENTING
							
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
												'TRANS_CATEG' 		=> $TRANS_CATEG,			// PINV = PURCHASE INVOICE
												'ITM_CODE' 			=> $ITM_CODE,
												'ACC_ID' 			=> $ACC_ID,
												'ITM_UNIT' 			=> $ITM_UNIT,
												'ITM_QTY' 			=> $ITM_QTY,
												'ITM_PRICE' 		=> $ITM_PRICE,
												'ITM_DISC' 			=> $ITM_DISC,
												'TAXCODE1' 			=> $TAXCODE1,
												'TAXPRICE1' 		=> $TAXPRICE1,
												'Notes' 			=> $Notes);												
							$this->m_journal->createJournalD($JournalH_Code, $parameters);
						}
						
						// PENDAPATAN
						$TOT_K		= $PINV_TOTVALnPPN + $PINV_DPBACKCUR + $PINV_TOTVALPPH + $PINV_RETCUTCUR - $PINV_TOTVALPPn;						
						if($TOT_K > 0)
						{
							$JournalH_Code	= $JournalH_Code;
							$JournalType	= 'PRJINV-SI';
							$JournalH_Date	= $JournalH_Date;
							$Company_ID		= $comp_init;
							$Currency_ID	= 'IDR';
							$DOCSource		= $PINV_SOURCE;
							$LastUpdate		= $CREATED;
							$WH_CODE		= $PRJCODE;
							$Refer_Number	= $PINV_SOURCE;
							$RefType		= 'PRJINV-SI';
							$JSource		= 'PRJINV4';
							$PRJCODE		= $PRJCODE;
							$Notes			= "Pend. from MC Ref. $PINV_SOURCE";
							
							$ITM_CODE 		= $JournalH_Code;
							$ACC_ID 		= $ACC_NUM5;
							$ITM_UNIT 		= '';
							$ITM_QTY 		= 1;
							$ITM_PRICE 		= $TOT_K;
							$ITM_DISC 		= 0;
							$TAXCODE1 		= "TAX01";
							$TAXPRICE1		= 0;
							
							$TRANS_CATEG 	= "PRJINV4~$PINV_OWNER";							// PERHATIKAN DI SINI. PENTING
							
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
												'TRANS_CATEG' 		=> $TRANS_CATEG,			// PINV = PURCHASE INVOICE
												'ITM_CODE' 			=> $ITM_CODE,
												'ACC_ID' 			=> $ACC_ID,
												'ITM_UNIT' 			=> $ITM_UNIT,
												'ITM_QTY' 			=> $ITM_QTY,
												'ITM_PRICE' 		=> $ITM_PRICE,
												'ITM_DISC' 			=> $ITM_DISC,
												'TAXCODE1' 			=> $TAXCODE1,
												'TAXPRICE1' 		=> $TAXPRICE1,
												'Notes' 			=> $Notes);												
							$this->m_journal->createJournalD($JournalH_Code, $parameters);
						}
					// END : JOURNAL DETAIL
				}
			}

			if($PINV_CAT == 2)
			{
				$MC_APPSTAT	= 2;
				$MC_REF		= $this->input->post('MC_REF');
				$MC_REFB	= explode("|",$MC_REF);
				for($i=0;$i< count($MC_REFB);$i++)
				{
					$MC_CODE 	= $MC_REFB[$i];
					$this->m_project_invoice->updateMC($MC_CODE, $MC_APPSTAT);
				}
			}
			elseif($PINV_CAT == 3)
			{
				$this->m_project_invoice->updateSIC($PINV_SOURCE, $MC_APPSTAT);
			}
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $PINV_CODE;
				$MenuCode 		= 'MN232';
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
			
			$url	= site_url('c_project/c_prj180c2dinv/gall180c2dinv/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function pall180c2dmc() // OK
	{
		$this->load->model('m_project/m_project_invoice/m_project_invoice', '', TRUE);
		$this->load->model('m_project/m_project_invoice/m_project_invoice_RealINV', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		if ($this->session->userdata('login') == TRUE)
		{	
			$PRJCODE				= $_GET['id'];
			$PRJCODE				= $this->url_encryption_helper->decode_url($PRJCODE);
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
						
			$data['title'] 			= $appName;
			$data['h2_title'] 		= 'Select MC Number';
			$data['h3_title'] 		= 'project invoice';
			$data['txtRefference'] 	= '';
			$data['resultCount']	= 0;
			$data['pageFrom']		= 'MC';
			$data['PRJCODE']		= $PRJCODE;
					
			$this->load->view('v_project/v_project_invoice/project_selectmcgrp', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function pall180c2dsi() // 
	{
		$this->load->model('m_project/m_project_invoice/m_project_invoice', '', TRUE);
		$this->load->model('m_project/m_project_invoice/m_project_invoice_RealINV', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE				= $_GET['id'];
			$PRJCODE				= $this->url_encryption_helper->decode_url($PRJCODE);
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			
			$data['title'] 			= $appName;
			$data['txtRefference'] 	= '';
			$data['resultCount']	= 0;
			$data['pageFrom']		= 'SI';
			$data['PRJCODE']		= $PRJCODE;
					
			$this->load->view('v_project/v_project_invoice/project_selectsi', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
    function indexInbox()  // 
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$secIndex 		= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/c_prj180c2dinv'),'inbox');
			redirect($secIndex);
		}
		else
		{
			redirect('__l1y');
		}
    }

	function get_last_ten_projList($offset=0) // 
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$getAppName = $this->Menu_model->getAppName()->result();
			foreach($getAppName as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			// Secure URL
			$DefEmp_ID 					= $this->session->userdata['Emp_ID'];
			$DefProj_Code		 		= $this->session->userdata['dtSessSrc2']['selSearchproj_Code']; // Session Project Per User
			
			$data['secAddURL'] 			= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/c_prj180c2dinv'),'add');
			$data['showIndex'] 			= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/c_prj180c2dinv'),'index');
			$data['srch_url'] 			= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/c_prj180c2dinv'),'get_last_ten_projList_src');
			
			$data['title'] 				= $appName;
			$data['h2_title'] 			= 'Project Invoice | Project Planning List';
			$data['main_view'] 			= 'v_project/v_project_invoice/project_list_sd';
			$data['moffset'] 			= $offset;
			$data['perpage'] 			= 15;
			$data['theoffset']			= 0;
			
			$num_rows					= $this->m_project_invoice->count_all_num_rows($DefEmp_ID);
			$data["recordcount"] 		= $num_rows;
			$config 					= array();
			
			$config["total_rows"] 		= $num_rows;
			$config["per_page"] 		= 15;
			$config["uri_segment"] 		= 3;
			$config['cur_page'] 		= $offset;
			$config['base_url'] 		= site_url('c_project/c_prj180c2dinv/get_last_ten_projList');				
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
	 
			$data['vewproject'] = $this->m_project_invoice->get_last_ten_project($config["per_page"], $offset, $DefEmp_ID)->result();
			$data["pagination"] = $this->pagination->create_links();
			
			// // Start : Searching Function --- Untuk delete session
			$dataSessSrc = array(
					'selSearchproj_Code' => $DefProj_Code,
					'selSearchType' => $this->input->post('selSearchType'),
					'txtSearch' => $this->input->post('txtSearch'));
			$this->session->set_userdata('dtSessSrc1', $dataSessSrc);
			$this->session->set_userdata('dtSessSrc2', $dataSessSrc);
			// End : Searching Function	
			
			$this->load->view('template', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function get_last_ten_projList_src($offset=0) // 
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$getAppName = $this->Menu_model->getAppName()->result();
			foreach($getAppName as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			// Secure URL
			$DefEmp_ID 					= $this->session->userdata['Emp_ID'];
			$DefProj_Code		 		= $this->session->userdata['dtSessSrc2']['selSearchproj_Code']; // Session Project Per User
			
			$data['secAddURL'] 			= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/c_prj180c2dinv'),'add');
			$data['showIndex'] 			= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/c_prj180c2dinv'),'index');
			$data['srch_url'] 			= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/c_prj180c2dinv'),'get_last_ten_projList_src');
			
			$data['title'] 				= $appName;
			$data['h2_title'] 			= 'Project Invoice | Project Planning List';
			$data['main_view'] 			= 'v_project/v_project_invoice/project_list_sd';
			$data['moffset'] 			= $offset;
			$data['perpage'] 			= 15;
			$data['theoffset']			= 0;
			$data['moffset'] 			= $offset;	
			
			$data['selSearchType'] 		= $this->input->post('selSearchType');
			$data['txtSearch'] 			= $this->input->post('txtSearch');
			
			if (isset($_POST['submitSrch']))
			{
				$selSearchproj_Code	= $this->input->post('txtSearch');
				$selSearchType		= $this->input->post('selSearchType');
				$txtSearch 			= $this->input->post('txtSearch');
			}
			else
			{
				$selSearchproj_Code = $this->session->userdata['dtSessSrc1']['selSearchproj_Code'];
				$selSearchType      = $this->session->userdata['dtSessSrc1']['selSearchType'];
				$txtSearch        	= $this->session->userdata['dtSessSrc1']['txtSearch'];
			}
			
			if($selSearchType == 'ProjNumber')
			{
				$num_rows = $this->m_project_invoice->count_all_num_rows_PNo($txtSearch, $DefEmp_ID);
			}
			else
			{
				$num_rows = $this->m_project_invoice->count_all_num_rows_PNm($txtSearch, $DefEmp_ID);
			}
			
			$data["recordcount"] 		= $num_rows;
			$config 					= array();
			
			$config["total_rows"] 		= $num_rows;
			$config["per_page"] 		= 15;
			$config["uri_segment"] 		= 3;
			$config['cur_page'] 		= $offset;
			
			if($selSearchType == 'ProjNumber')
			{
				$num_rows 				= $this->m_project_invoice->count_all_num_rows_PNo($txtSearch, $DefEmp_ID);
				$data["recordcount"] 	= $num_rows;
				$data['vewproject'] 	= $this->m_project_invoice->get_last_ten_project_PNo($config["per_page"], $offset, $txtSearch, $DefEmp_ID)->result();
			}
			else
			{
				$num_rows 				= $this->m_project_invoice->count_all_num_rows_PNm($txtSearch, $DefEmp_ID);
				$data["recordcount"] 	= $num_rows;
				$data['vewproject'] 	= $this->m_project_invoice->get_last_ten_project_PNm($config["per_page"], $offset, $txtSearch, $DefEmp_ID)->result();
			}
			
			if($num_rows > 0)
			{
				$selSearchproj_Code	= $this->input->post('txtSearch');
				$selSearchType		= $this->input->post('selSearchType');
				$txtSearch 			= $this->input->post('txtSearch');
			}
			else
			{
				$selSearchproj_Code = $this->session->userdata['dtSessSrc1']['selSearchproj_Code'];
				$selSearchType      = $this->session->userdata['dtSessSrc1']['selSearchType'];
				$txtSearch        	= $this->session->userdata['dtSessSrc1']['txtSearch'];
			}
			
			$dataSessSrc = array(
				'selSearchproj_Code' => $selSearchproj_Code,
				'selSearchType' => $selSearchType,
				'txtSearch' => $txtSearch);
			$this->session->set_userdata('dtSessSrc1', $dataSessSrc);
			$this->session->set_userdata('dtSessSrc2', $dataSessSrc);
			
			$config['base_url'] 		= site_url('c_project/c_prj180c2dinv/get_last_ten_projList');				
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
	 
			$data["pagination"] = $this->pagination->create_links();	
			
			$this->load->view('template', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function get_last_ten_projinv_src($offset=0) // 
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$getAppName = $this->Menu_model->getAppName()->result();
			foreach($getAppName as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$PRJCODE = $this->session->userdata['dtSessSrc1']['selSearchproj_Code'];
			
			// Secure URL
			$data['showIdxMReq']	= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/c_prj180c2dinv'),'gall180c2dinv');
			$data['srch_url'] 		= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/c_prj180c2dinv'),'get_last_ten_projinv_src');			
			$data['title'] 			= $appName;
			$data['h2_title'] 		= 'Project Invoice List';
			$data['main_view'] 		= 'v_project/v_project_invoice/project_invoice_sd';			
			$data['link'] 			= array('link_back' => anchor('c_project/c_prj180c2dinv/','<input type="button" name="btnCancel" id="btnCancel" class="button_css" value="Back" />', array('style' => 'text-decoration: none;')));
			$data['proj_Code'] 		= $PRJCODE;
			$data['moffset'] 		= $offset;
			
			if (isset($_POST['submitSrch']))
			{
				$selSearchproj_Code = $this->session->userdata['dtSessSrc1']['selSearchproj_Code'];
				$selSearchType		= $this->input->post('selSearchType'); // projINV_No
				$txtSearch 			= $this->input->post('txtSearch');
				
				$dataSessSrc = array(
					'selSearchproj_Code' => $selSearchproj_Code,
					'selSearchType' => $selSearchType,
					'txtSearch' => $txtSearch);
				$this->session->set_userdata('dtSessSrc1', $dataSessSrc);
				$this->session->set_userdata('dtSessSrc2', $dataSessSrc);
			}
			else
			{
				$selSearchproj_Code = $this->session->userdata['dtSessSrc1']['selSearchproj_Code'];
				$selSearchType      = $this->session->userdata['dtSessSrc1']['selSearchType'];
				$txtSearch        	= $this->session->userdata['dtSessSrc1']['txtSearch'];
				
				$dataSessSrc = array(
					'selSearchproj_Code' => $selSearchproj_Code,
					'selSearchType' => $selSearchType,
					'txtSearch' => $txtSearch);
				$this->session->set_userdata('dtSessSrc1', $dataSessSrc);
				$this->session->set_userdata('dtSessSrc2', $dataSessSrc);
			}
			
			if($selSearchType == 'projINV_No')
			{
				$num_rows = $this->m_project_invoice->count_all_num_rows_projINV_srcINV($txtSearch, $PRJCODE);
			}
			else
			{
				$num_rows = $this->m_project_invoice->count_all_num_rows_projMatReq_srcPN($txtSearch, $PRJCODE);
			}
			
			$data['PRJCODE'] 			= $PRJCODE;
			$data['selSearchType']		= $selSearchType;
			$data['txtSearch'] 			= $txtSearch;
			
			$data["recordcount"] 		= $num_rows;
			
			$config 					= array();
			$config['base_url'] 		= site_url('c_project/c_prj180c2dinv/get_last_ten_projinv_src');
			$config["total_rows"] 		= $num_rows;			
			$config["per_page"] 		= 15;
			$config["uri_segment"] 		= 4;				
			$config['full_tag_open'] 	= '<ul class="tsc_pagination tsc_paginationA tsc_paginationA01">';
			$config['full_tag_close'] 	= '</ul>';
			$config['prev_link'] 		= '&lt;';
			$config['prev_tag_open'] 	= '<li>';
			$config['prev_tag_close'] 	= '</li>';
			$config['next_link'] 		= '&gt;';
			$config['next_tag_open'] 	= '<li>';
			$config['next_tag_close']	= '</li>';
			$config['cur_tag_open'] 	= '<li class="current"><a href="#">';
			$config['cur_tag_close'] 	= '</a></li>';
			$config['num_tag_open'] 	= '<li>';
			$config['num_tag_close'] 	= '</li>';			
			$config['first_tag_open'] 	= '<li>';
			$config['first_tag_close'] 	= '</li>';
			$config['last_tag_open'] 	= '<li>';
			$config['last_tag_close'] 	= '</li>';			
			$config['first_link'] 		= '&lt;&lt;';
			$config['last_link']		= '&gt;&gt;';
	 
			$this->pagination->initialize($config);
			
			if($selSearchType == 'projINV_No')
			{
				$data['viewprojinvoice'] = $this->m_project_invoice->get_last_ten_projinv_INVNo($config["per_page"], $offset, $txtSearch, $PRJCODE)->result();
			}
			else
			{
				$data['viewprojinvoice'] = $this->m_project_invoice->get_last_ten_projinv_PNm($config["per_page"], $offset, $txtSearch, $PRJCODE)->result();
			}
						
			$data["pagination"] = $this->pagination->create_links();
			
			$this->load->view('template', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function printdocument1($PINV_Number) // 
	{
		if ($this->session->userdata('login') == TRUE)
		{	
			$getAppName = $this->Menu_model->getAppName()->result();
			foreach($getAppName as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title'] 		= 'Document Print';
			
			//$data['recordcountVend'] 	= $this->m_project_invoice->count_all_num_rowsVend();
			//$data['viewvendor'] 		= $this->m_project_invoice->viewvendor()->result();
			$data['recordcountOwner'] 	= $this->m_project_invoice->count_all_num_rowsOwner();
			$data['viewOwner'] 			= $this->m_project_invoice->viewOwner()->result();
			$data['recordcountEmpDept'] = $this->m_project_invoice->count_all_num_rowsEmpDept();
			$data['viewEmployeeDept'] 	= $this->m_project_invoice->viewEmployeeDept()->result();
			$data['recordcountProject']	= $this->m_project_invoice->count_all_num_rowsProject();
			$data['viewProject'] 		= $this->m_project_invoice->viewProject()->result();
			
			$getpurINVO					= $this->m_project_invoice->get_PINV_by_number($PINV_Number)->row();
			
			$this->session->set_userdata('PINV_Number', $getpurINVO->PINV_CODE);
			
			$data['link'] 				= array('link_back' => anchor('c_project/c_prj180c2dinv/gall180c2dinv/'.$getpurINVO->PRJCODE,'<input type="button" name="btnCancel" id="btnCancel" value="Cancel" class="button_css" />', array('style' => 'text-decoration: none;')));
			
			$data['PINV_CODE'] 		= $getpurINVO->PINV_CODE;
			$data['PINV_MANNO'] 	= $getpurINVO->PINV_MANNO;
			$data['PINV_STEP'] 		= $getpurINVO->PINV_STEP;
			$data['PINV_CAT'] 		= $getpurINVO->PINV_CAT;
			$data['PINV_SOURCE']	= $getpurINVO->PINV_SOURCE;
			$data['PRJCODE'] 		= $getpurINVO->PRJCODE;
			$data['PINV_OWNER'] 	= $getpurINVO->PINV_OWNER;
			$data['PINV_DATE'] 		= $getpurINVO->PINV_DATE;
			$data['PINV_ENDDATE']	= $getpurINVO->PINV_ENDDATE; 
			$data['PINV_CHECKD'] 	= $getpurINVO->PINV_CHECKD; 
			$data['PINV_CREATED']	= $getpurINVO->PINV_CREATED;
			$data['PINV_RETVAL'] 	= $getpurINVO->PINV_RETVAL;
			$data['PINV_RETCUT'] 	= $getpurINVO->PINV_RETCUT;
			$data['PINV_DPPER'] 	= $getpurINVO->PINV_DPPER;
			$data['PINV_DPVAL'] 	= $getpurINVO->PINV_DPVAL;
			$data['PINV_DPVALPPn']	= $getpurINVO->PINV_DPVALPPn;
			$data['PINV_DPBACK'] 	= $getpurINVO->PINV_DPBACK;
			$data['PINV_DPBACKPPn'] = $getpurINVO->PINV_DPBACKPPn;
			$data['PINV_PROG'] 		= $getpurINVO->PINV_PROG;
			$data['PINV_PROGVAL']	= $getpurINVO->PINV_PROGVAL;
			$data['PINV_PROGVALPPn']= $getpurINVO->PINV_PROGVALPPn;
			$data['PINV_PROGAPP']	= $getpurINVO->PINV_PROGAPP;
			$data['PINV_PROGAPPVAL']= $getpurINVO->PINV_PROGAPPVAL;
			$data['PINV_VALADD'] 	= $getpurINVO->PINV_VALADD;
			$data['PINV_VALADDPPn'] = $getpurINVO->PINV_VALADDPPn;
			$data['PINV_MATVAL'] 	= $getpurINVO->PINV_MATVAL;
			$data['PINV_VALBEF']	= $getpurINVO->PINV_VALBEF;
			$data['PINV_VALBEFPPn']	= $getpurINVO->PINV_VALBEFPPn;
			$data['PINV_AKUMNEXT']	= $getpurINVO->PINV_AKUMNEXT;
			$data['PINV_TOTVAL'] 	= $getpurINVO->PINV_TOTVAL;
			$data['PINV_TOTVALPPn'] = $getpurINVO->PINV_TOTVALPPn;
			$data['PINV_NOTES'] 	= $getpurINVO->PINV_NOTES;
			$data['PINV_EMPID'] 	= $getpurINVO->PINV_EMPID;
			$data['PINV_STAT'] 		= $getpurINVO->PINV_STAT;
			$data['PATT_YEAR'] 		= $getpurINVO->PATT_YEAR;
			$data['PATT_MONTH'] 	= $getpurINVO->PATT_MONTH;
			$data['PATT_DATE'] 		= $getpurINVO->PATT_DATE;
			$data['PATT_NUMBER'] 	= $getpurINVO->PATT_NUMBER;
			
			$this->load->view('v_project/v_project_invoice/project_invoice_sd_print1', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function popupallitem($DocNumber) // U
	{
		$getAppName = $this->Menu_model->getAppName()->result();
		foreach($getAppName as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$proj_Code		 		= $this->session->userdata['dtProjSess']['myProjSession'];
		
		$data['title'] 			= $appName;
		$data['DocNumber'] 		= $DocNumber;
		$data['h2_title'] 		= 'Input Adendum';
		$data['main_view'] 		= 'v_project/v_project_invoice/project_invoice_sd_form';
				
		$this->load->view('v_project/v_project_invoice/project_selectinv_sd', $data);
	}
	
	function editdocument($PINV_Number) // U
	{		
		if ($this->session->userdata('login') == TRUE)
		{	
			$getAppName = $this->Menu_model->getAppName()->result();
			foreach($getAppName as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$data['title'] 				= $appName;
			$data['task'] 				= 'edit';
			$data['h2_title'] 			= 'Edit Document';
			$data['form_action']		= site_url('c_project/c_prj180c2dinv/editdocument_process');
			
			//$data['recordcountVend'] 	= $this->m_project_invoice->count_all_num_rowsVend();
			//$data['viewvendor'] 		= $this->m_project_invoice->viewvendor()->result();
			$data['recordcountOwner'] 	= $this->m_project_invoice->count_all_num_rowsOwner();
			$data['viewOwner'] 			= $this->m_project_invoice->viewOwner()->result();
			$data['recordcountEmpDept'] = $this->m_project_invoice->count_all_num_rowsEmpDept();
			$data['viewEmployeeDept'] 	= $this->m_project_invoice->viewEmployeeDept()->result();
			$data['recordcountProject']	= $this->m_project_invoice->count_all_num_rowsProject();
			$data['viewProject'] 		= $this->m_project_invoice->viewProject()->result();
			
			$getpurINVO 				= $this->m_project_invoice->get_PINV_by_number($PINV_Number)->row();
			
			$this->session->set_userdata('PINV_Number', $getpurINVO->PINV_Number);
			
			$data['link'] 				= array('link_back' => anchor('c_project/c_prj180c2dinv/gall180c2dinv/'.$getpurINVO->proj_Code,'<input type="button" name="btnCancel" id="btnCancel" value="Cancel" class="button_css" />', array('style' => 'text-decoration: none;')));
			
			$data['proj_Code'] 			= $getpurINVO->proj_Code;
			$data['PINV_Number'] 		= $getpurINVO->PINV_Number;
			$data['PINV_SPKNo'] 		= $getpurINVO->PINV_SPKNo;
			$data['PINV_Date'] 			= $getpurINVO->PINV_Date;
			$data['PINV_EndDate'] 		= $getpurINVO->PINV_EndDate;
			$data['PINV_Class']			= $getpurINVO->PINV_Class;
			$data['PINV_Type'] 			= $getpurINVO->PINV_Type;
			$data['Owner_Code'] 		= $getpurINVO->Owner_Code;
			$data['PINV_EmpID'] 		= $getpurINVO->PINV_EmpID;
			$data['PINV_Notes'] 		= $getpurINVO->PINV_Notes;
			$data['PINV_Status'] 		= $getpurINVO->PINV_Status;
			$data['PINV_STAT'] 			= $getpurINVO->PINV_STAT;
			$data['Patt_Year'] 			= $getpurINVO->Patt_Year;
			$data['Patt_Number'] 		= $getpurINVO->Patt_Number;
			$data['Memo_Revisi'] 		= $getpurINVO->Memo_Revisi;
			
			$this->load->view('v_project/v_project_invoice/project_invoice_sd_edit', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
    function project_invoiceRealInd() // U
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$secIndex 		= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/c_prj180c2dinv'),'get_last_ten_projListRealINV');
			redirect($secIndex);
		}
		else
		{
			redirect('__l1y');
		}
    }

	function get_last_ten_projListRealINV($offset=0) // U
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$getAppName = $this->Menu_model->getAppName()->result();
			foreach($getAppName as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			// Secure URL
			$DefEmp_ID 					= $this->session->userdata['Emp_ID'];
			$DefProj_Code		 		= $this->session->userdata['dtSessSrc2']['selSearchproj_Code']; // Session Project Per User
			
			$data['secAddURL'] 			= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/c_prj180c2dinv'),'addRealInd');
			$data['showIndex'] 			= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/c_prj180c2dinv'),'indexRealInd');
			$data['srch_url'] 			= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/c_prj180c2dinv'),'get_last_ten_projListRealInd_src');
			
			$data['title'] 				= $appName;
			$data['h2_title'] 			= 'Project Invoice | Project Planning List';
			$data['main_view'] 			= 'v_project/v_project_invoice/project_list_sd_RealInd';
			//$data['srch_url'] = site_url('c_project/c_prj180c2dinv/get_last_ten_projList_src');
			$data['moffset'] 			= $offset;
			$data['perpage'] 			= 15;
			$data['theoffset']			= 0;			
			$num_rows 					= $this->m_project_invoice_RealINV->count_all_num_rows($DefEmp_ID);
			$data["recordcount"] 		= $num_rows;
			
			$config 					= array();
			$config["total_rows"]		= $num_rows;
			$config["per_page"] 		= 15;
			$config["uri_segment"] 		= 4;
			$config['cur_page'] 		= $offset;
			$config['base_url'] 		= site_url('c_project/c_prj180c2dinv/get_last_ten_projListRealInd');
			$config['full_tag_open']	= '<ul class="tsc_pagination tsc_paginationA tsc_paginationA01">';
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
	 
			$data['vewproject'] 		= $this->m_project_invoice_RealINV->get_last_ten_project($config["per_page"], $offset, $DefEmp_ID)->result();
			$data["pagination"] 		= $this->pagination->create_links();
			
			// // Start : Searching Function --- Untuk delete session
			$dataSessSrc = array(
					'selSearchproj_Code' => $DefProj_Code,
					'selSearchType' => $this->input->post('selSearchType'),
					'txtSearch' => $this->input->post('txtSearch'));
			$this->session->set_userdata('dtSessSrc1', $dataSessSrc);
			$this->session->set_userdata('dtSessSrc2', $dataSessSrc);
			// End : Searching Function	
			
			$this->load->view('template', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function get_last_ten_projinvRealINV($proj_Code, $offset=0) // U
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE					= $proj_Code;
			$getAppName = $this->Menu_model->getAppName()->result();
			foreach($getAppName as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			// Secure URL
			//$data['secAddURL']		= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/c_prj180c2dinv'),'addRealINV');
			$data['showIdxMReq']		= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/c_prj180c2dinv'),'get_last_ten_projinvRealINV');
			$data['srch_url'] 			= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/c_prj180c2dinv'),'get_last_ten_projinvRealINV_src');
			
			$data['title'] 				= $appName;
			$data['h2_title'] 			= 'Project Invoice Realization List';
			$data['main_view'] 			= 'v_project/v_project_invoice/project_invoice_sd_RealINV';
			//$data['srch_url'] 		= site_url('c_project/c_prj180c2dinv/get_last_ten_projinv_src/'.$proj_Code);
			
			$data['link'] 				= array('link_back' => anchor('c_project/c_prj180c2dinv/project_invoiceRealInd','<input type="button" name="btnCancel" id="btnCancel" class="button_css" value="Back" />', array('style' => 'text-decoration: none;')));
			$data['PRJCODE'] 			= $proj_Code;
			$data['moffset'] 			= $offset;		
			
			$num_rows 					= $this->m_project_invoice_RealINV->count_all_num_rowsProjInv($PRJCODE);
			$data["recordcount"] 		= $num_rows;
						
			$config 					= array();
			$config["total_rows"]		= $num_rows;
			$config["per_page"] 		= 15;
			$config["uri_segment"] 		= 4;
			$config['cur_page'] 		= $offset;
			$config['base_url'] 		= site_url('c_project/c_prj180c2dinv/get_last_ten_projinvRealINV');
			$config['full_tag_open']	= '<ul class="tsc_pagination tsc_paginationA tsc_paginationA01">';
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
	 
			$data['viewprojinvoice']	= $this->m_project_invoice_RealINV->get_last_ten_projinv($PRJCODE, $config["per_page"], $offset)->result();
			$data["pagination"] 		= $this->pagination->create_links();
			
			$selSearchproj_Code = $proj_Code;
			$selSearchType      = $this->session->userdata['dtSessSrc1']['selSearchType'];
			$txtSearch        	= $this->session->userdata['dtSessSrc1']['txtSearch'];
				
			$dataSessSrc = array(
				'selSearchproj_Code' => $selSearchproj_Code,
				'selSearchType' => $selSearchType,
				'txtSearch' => $txtSearch);
			$this->session->set_userdata('dtSessSrc1', $dataSessSrc);
			$this->session->set_userdata('dtSessSrc2', $dataSessSrc);
			
			$myProjectSess = array(
					'myProjSession' => $selSearchproj_Code);
			$this->session->set_userdata('dtProjSess', $myProjectSess);
			
			$this->load->view('template', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function addRealINV($proj_Code) // U
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$getAppName = $this->Menu_model->getAppName()->result();
			foreach($getAppName as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$data['proj_Code'] 			= $proj_Code;	
			$data['PRJCODE'] 			= $proj_Code;	
			$docPatternPosition 		= 'Especially';	
			$data['title'] 				= $appName;
			$data['task'] 				= 'add';
			$data['h2_title']			= 'Project Invoice | Add Project Invoice Realization';
			$data['main_view'] 			= 'v_project/v_project_invoice/project_invoice_sd_formRealINV';
			$data['form_action']		= site_url('c_project/c_prj180c2dinv/add_processRealINV');
			$data['link'] 				= array('link_back' => anchor('c_project/c_prj180c2dinv/get_last_ten_projinvRealINV/'.$proj_Code,'<input type="button" name="btnCancel" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			//$data['recordcountVend'] 	= $this->m_project_invoice->count_all_num_rowsVend();
			//$data['viewvendor'] 		= $this->m_project_invoice->viewvendor()->result();
			$data['recordcountOwner'] 	= $this->m_project_invoice_RealINV->count_all_num_rowsOwner();
			$data['viewOwner'] 			= $this->m_project_invoice_RealINV->viewOwner()->result();
			$data['recordcountEmpDept'] = $this->m_project_invoice_RealINV->count_all_num_rowsEmpDept();
			$data['viewEmployeeDept'] 	= $this->m_project_invoice_RealINV->viewEmployeeDept()->result();
			$data['recordcountProject']	= $this->m_project_invoice_RealINV->count_all_num_rowsProject();
			$data['viewProject'] 		= $this->m_project_invoice_RealINV->viewProject()->result();
			
			$MenuCode 				= 'MN233';
			$data['viewDocPattern'] 	= $this->m_project_invoice_RealINV->getDataDocPat($MenuCode)->result();
	
			$this->load->view('template', $data);
			
			$myProjectSess = array(
					'myProjSession' => $proj_Code);
			$this->session->set_userdata('dtProjSess', $myProjectSess);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function add_processRealINV() // U
	{ 
		$getAppName = $this->Menu_model->getAppName()->result();
		foreach($getAppName as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$this->db->trans_begin();
		
		//setting PINV Date	
		$PINV_Number		= $this->input->post('PINV_Number');
		$PRINV_Number		= $this->input->post('PRINV_Number');
		$PRINV_Date			= date('Y-m-d',strtotime($this->input->post('PRINV_CreateDate')));
		$PRINV_CreateDate	= date('Y-m-d',strtotime($this->input->post('PRINV_CreateDate')));
		$PINV_Amount		= $this->input->post('PINV_Amount');
		$RealINVAmount		= $this->input->post('RealINVAmount');
		$RealINVAmountPPh	= $this->input->post('RealINVAmountPPh');
		$RealINVOtherAm		= $this->input->post('RealINVOtherAm');
		$isPPh				= $this->input->post('isPPh');
		$proj_Code			= $this->input->post('proj_Code');
		$PRINV_Notes		= $this->input->post('PRINV_Notes');
		$Patt_Year			= date('Y',strtotime($this->input->post('PRINV_Date')));		
		$Patt_Number		= $this->input->post('Patt_Number');	
		$PRINV_Deviation	= $this->input->post('PRINV_Deviation');
		
		$prohPINVH = array('PINV_Number' 	=> $PINV_Number,
						'PRINV_Number'		=> $PRINV_Number,
						'PRINV_Date'		=> $PRINV_Date,
						'PRINV_CreateDate'	=> $PRINV_CreateDate,
						'PINV_Amount'		=> $PINV_Amount,
						'RealINVAmount'		=> $RealINVAmount,
						'RealINVAmountPPh'	=> $RealINVAmountPPh,
						'RealINVOtherAm'	=> $RealINVOtherAm, 
						'isPPh'				=> $isPPh,
						'proj_Code'			=> $proj_Code,
						'PRINV_Notes'		=> $PRINV_Notes,
						'Patt_Number'		=> $Patt_Number,
						'PRINV_Deviation'	=> $PRINV_Deviation,
						'Patt_Year'			=> $Patt_Year);
						
		$this->m_project_invoice_RealINV->add($prohPINVH);
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
		}
		else
		{
			$this->db->trans_commit();
		}
		
		redirect('c_project/c_prj180c2dinv/get_last_ten_projinvRealINV/'.$proj_Code);
	}
	
	function popupallINV() // U
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$getAppName = $this->Menu_model->getAppName()->result();
			foreach($getAppName as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$data['title'] 			= $appName;
			$DefProj_Code    		= $this->session->userdata['dtSessSrc1']['selSearchproj_Code'];
			$data['h2_title'] 		= 'Select Project Invoice';
			$data['txtRefference'] 	= '';
			$data['resultCount']	= 0;
			$data['pageFrom']		= 'PR';
			
			$data['recordcountAllPINV'] = $this->m_project_invoice_RealINV->count_all_num_rowsAllINV($DefProj_Code);
			$data['viewAllPINV'] 		= $this->m_project_invoice_RealINV->viewAllINV($DefProj_Code)->result();
					
			$this->load->view('v_project/v_project_invoice/project_selectinv_sd_selectINV', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function updateRealINV($PRINV_Number) // U
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$getAppName = $this->Menu_model->getAppName()->result();
			foreach($getAppName as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title'] 		= 'Invoice Realization | Edit Invoice Realization';
			$data['main_view'] 		= 'v_project/v_project_invoice/project_invoice_sd_formRealINV';
			$data['form_action']	= site_url('c_project/c_prj180c2dinv/update_processRealINV');
			
			$data['recordcountProject']	= $this->m_project_invoice_RealINV->count_all_num_rowsProject();
			$data['viewProject'] 		= $this->m_project_invoice_RealINV->viewProject()->result();
			$getpurINVO 				= $this->m_project_invoice_RealINV->get_PRINV_by_number($PRINV_Number)->row();
			
			$this->session->set_userdata('PRINV_Number', $getpurINVO->PRINV_Number);
			
			$data['link'] 			= array('link_back' => anchor('c_project/c_prj180c2dinv/project_invoiceRealInd/'.$getpurINVO->proj_Code,'<input type="button" name="btnCancel" id="btnCancel" value="Cancel" class="button_css" />', array('style' => 'text-decoration: none;')));
			
			$data['default']['PINV_Number'] 		= $getpurINVO->PINV_Number;
			$data['default']['PRINV_Number'] 		= $getpurINVO->PRINV_Number;
			$data['default']['PRINV_Date'] 			= $getpurINVO->PRINV_Date;
			$data['default']['PRINV_CreateDate'] 	= $getpurINVO->PRINV_CreateDate;
			$data['default']['PRINV_Deviation'] 	= $getpurINVO->PRINV_Deviation;
			$data['default']['PINV_Amount'] 		= $getpurINVO->PINV_Amount;
			$data['default']['RealINVAmount'] 		= $getpurINVO->RealINVAmount; 
			$data['default']['RealINVAmountPPh'] 	= $getpurINVO->RealINVAmountPPh; 
			$data['default']['RealINVOtherAm'] 		= $getpurINVO->RealINVOtherAm;
			$data['default']['isPPh'] 				= $getpurINVO->isPPh;
			$data['default']['proj_Code'] 			= $getpurINVO->proj_Code;
			$data['default']['PRJCODE'] 			= $getpurINVO->proj_Code;
			$data['default']['Patt_Number'] 		= $getpurINVO->Patt_Number;
			$data['default']['PRINV_Notes'] 		= $getpurINVO->PRINV_Notes;
			$data['default']['Patt_Year'] 			= $getpurINVO->Patt_Year;
				
			$this->load->view('template', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_processRealINV() // U
	{ 
		$getAppName = $this->Menu_model->getAppName()->result();
		foreach($getAppName as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$this->db->trans_begin();
		
		//setting PINV Date	
		$PINV_Number		= $this->input->post('PINV_Number');
		$PRINV_Number		= $this->input->post('PRINV_Number');
		$PRINV_Date			= date('Y-m-d',strtotime($this->input->post('PRINV_CreateDate')));
		$PRINV_CreateDate	= date('Y-m-d',strtotime($this->input->post('PRINV_CreateDate')));
		$PINV_Amount		= $this->input->post('PINV_Amount');
		$RealINVAmount		= $this->input->post('RealINVAmount');
		$RealINVAmountPPh	= $this->input->post('RealINVAmountPPh');
		$RealINVOtherAm		= $this->input->post('RealINVOtherAm');
		$isPPh				= $this->input->post('isPPh');
		$proj_Code			= $this->input->post('proj_Code');
		$PRINV_Notes		= $this->input->post('PRINV_Notes');
		$Patt_Year			= date('Y',strtotime($this->input->post('PRINV_Date')));		
		$Patt_Number		= $this->input->post('Patt_Number');	
		$PRINV_Deviation	= $this->input->post('PRINV_Deviation');
		
		$prohPINVH = array('PINV_Number' 	=> $PINV_Number,
						'PRINV_Number'		=> $PRINV_Number,
						'PRINV_Date'		=> $PRINV_Date,
						'PRINV_CreateDate'	=> $PRINV_CreateDate,
						'PINV_Amount'		=> $PINV_Amount,
						'RealINVAmount'		=> $RealINVAmount,
						'RealINVAmountPPh'	=> $RealINVAmountPPh,
						'RealINVOtherAm'	=> $RealINVOtherAm, 
						'isPPh'				=> $isPPh,
						'proj_Code'			=> $proj_Code,
						'PRINV_Notes'		=> $PRINV_Notes,
						'Patt_Number'		=> $Patt_Number,
						'PRINV_Deviation'	=> $PRINV_Deviation,
						'Patt_Year'			=> $Patt_Year);
						
		$this->m_project_invoice_RealINV->updareRealPRINV($PRINV_Number, $prohPINVH);
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
		}
		else
		{
			$this->db->trans_commit();
		}
		
		redirect('c_project/c_prj180c2dinv/get_last_ten_projinvRealINV/'.$proj_Code);
	}
	
	function printdocumentRealInv($PRINV_Number) // U
	{		
		if ($this->session->userdata('login') == TRUE)
		{	
			$getAppName = $this->Menu_model->getAppName()->result();
			foreach($getAppName as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title'] 		= 'Document Print';
			
			//$data['recordcountVend'] 	= $this->m_project_invoice->count_all_num_rowsVend();
			//$data['viewvendor'] 		= $this->m_project_invoice->viewvendor()->result();
			$data['recordcountOwner'] 	= $this->m_project_invoice_RealINV->count_all_num_rowsOwner();
			$data['viewOwner'] 			= $this->m_project_invoice_RealINV->viewOwner()->result();
			$data['recordcountEmpDept'] = $this->m_project_invoice_RealINV->count_all_num_rowsEmpDept();
			$data['viewEmployeeDept'] 	= $this->m_project_invoice_RealINV->viewEmployeeDept()->result();
			$data['recordcountProject']	= $this->m_project_invoice_RealINV->count_all_num_rowsProject();
			$data['viewProject'] 		= $this->m_project_invoice_RealINV->viewProject()->result();
			
			$getpurINVO					= $this->m_project_invoice_RealINV->get_PRINV_by_number($PRINV_Number)->row();
			
			$this->session->set_userdata('PRINV_Number', $getpurINVO->PRINV_Number);
			
			$data['link'] 				= array('link_back' => anchor('c_project/c_prj180c2dinv/gall180c2dinv/'.$getpurINVO->proj_Code,'<input type="button" name="btnCancel" id="btnCancel" value="Cancel" class="button_css" />', array('style' => 'text-decoration: none;')));
			
			/*$data['default']['PINV_Number'] 		= $getpurINVO->PINV_Number;
			$data['default']['PRINV_Number'] 		= $getpurINVO->PRINV_Number;
			$data['default']['PRINV_Date'] 			= $getpurINVO->PRINV_Date;
			$data['default']['PRINV_CreateDate'] 	= $getpurINVO->PRINV_CreateDate;
			$data['default']['PRINV_Deviation'] 	= $getpurINVO->PRINV_Deviation;
			$data['default']['PINV_Amount'] 		= $getpurINVO->PINV_Amount;
			$data['default']['RealINVAmount'] 		= $getpurINVO->RealINVAmount; 
			$data['default']['RealINVAmountPPh'] 	= $getpurINVO->RealINVAmountPPh; 
			$data['default']['RealINVOtherAm'] 		= $getpurINVO->RealINVOtherAm;
			$data['default']['isPPh'] 				= $getpurINVO->isPPh;
			$data['default']['proj_Code'] 			= $getpurINVO->proj_Code;
			$data['default']['PRJCODE'] 			= $getpurINVO->proj_Code;
			$data['default']['Patt_Number'] 		= $getpurINVO->Patt_Number;
			$data['default']['PRINV_Notes'] 		= $getpurINVO->PRINV_Notes;
			$data['default']['Patt_Year'] 			= $getpurINVO->Patt_Year;*/
								
			$data['proj_Code'] 			= $getpurINVO->proj_Code;
			$data['PINV_Number'] 		= $getpurINVO->PINV_Number;
			$data['PINV_SPKNo'] 		= $getpurINVO->PINV_SPKNo;
			$data['PINV_Date'] 			= $getpurINVO->PINV_Date;
			$data['PINV_EndDate'] 		= $getpurINVO->PINV_EndDate;
			$data['PINV_Class']			= $getpurINVO->PINV_Class;
			$data['PINV_Type'] 			= $getpurINVO->PINV_Type;
			$data['Owner_Code'] 		= $getpurINVO->Owner_Code;
			$data['PINV_EmpID'] 		= $getpurINVO->PINV_EmpID;
			$data['PINV_Notes'] 		= $getpurINVO->PINV_Notes;
			$data['PINV_Status'] 		= $getpurINVO->PINV_Status;
			$data['PINV_STAT'] 			= $getpurINVO->PINV_STAT;
			$data['Patt_Year'] 			= $getpurINVO->Patt_Year;
			$data['Patt_Number'] 		= $getpurINVO->Patt_Number;
			$data['Memo_Revisi'] 		= $getpurINVO->Memo_Revisi;
			$this->load->view('v_project/v_project_invoice/project_invoice_sd_print', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function popupallpr()
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$getAppName = $this->Menu_model->getAppName()->result();
			foreach($getAppName as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$data['title'] 			= $appName;
			$data['h2_title'] 		= 'Select Purchase Requisition';
			$data['form_action']	= site_url('c_project/c_prj180c2dinv/update_process');
			$data['txtRefference'] 	= '';
			$data['resultCount']	= 0;
			$data['pageFrom']		= 'PR';
			
			$data['recordcountAllPR'] = $this->m_project_invoice->count_all_num_rowsAllPR();
			$data['viewAllPR'] 	= $this->m_project_invoice->viewAllPR()->result();
					
			$this->load->view('v_project/v_listproject/purchase_selectpr', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function popupallpr2()
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$getAppName = $this->Menu_model->getAppName()->result();
			foreach($getAppName as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$data['title'] 			= $appName;
			$data['h2_title'] 		= 'Select Item';
			$data['form_action']	= site_url('c_project/c_prj180c2dinv/update_process');
			$data['txtRefference'] 	= '';
			$data['resultCount']	= 0;
			$data['pageFrom']		= 'DIR'; //DIR = Direct (non PR)
			
			$data['recordcountAllItem'] = $this->m_project_invoice->count_all_num_rowsAllItem();
			$data['viewAllItem'] 	= $this->m_project_invoice->viewAllItem()->result();
					
			$this->load->view('v_project/v_listproject/purchase_selectitem', $data);	
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function delete($PR_Number)
	{
		$this->m_project_invoice->delete($this->input->post('chkDetail'));
		$this->session->set_flashdata('message', 'Data successfull deleted');
		
		redirect('c_project/c_prj180c2dinv/');
	}
	
    function inbox() 
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$secIndex 		= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/c_prj180c2dinv'),'inboxInbox');
			redirect($secIndex);
		}
		else
		{
			redirect('__l1y');
		}
    }
	
    function inboxInbox($offset=0)
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$getAppName = $this->Menu_model->getAppName()->result();
			foreach($getAppName as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			// Secure URL
			$DefEmp_ID 					= $this->session->userdata['Emp_ID'];
			$DefProj_Code		 		= $this->session->userdata['dtSessSrc2']['selSearchproj_Code']; // Session Project Per User
		
			$data['title'] 		= $appName;
			$data['h2_title']	= 'Project Planning List';
			$data['main_view'] 	= 'v_project/v_project_invoice/project_invoice_sd_inb';
			$data['srch_url'] = site_url('c_project/c_prj180c2dinv/inbox_src');
			$data['moffset'] = $offset;

			//$num_rows = $this->m_project_invoice->count_all_num_rows();
			$num_rows = $this->m_project_invoice->count_all_num_rowsInbox($DefEmp_ID);		
			$data["recordcount"] = $num_rows;
			$config = array();
			$config['base_url'] = site_url('c_project/project_planning/get_last_ten_project');
			$config["total_rows"] = $num_rows;
			$config["per_page"] = 20;
			$config["uri_segment"] = 3;
				
			$config['full_tag_open'] = '<ul class="tsc_pagination tsc_paginationA tsc_paginationA01">';
			$config['full_tag_close'] = '</ul>';
			$config['prev_link'] = '&lt;';
			$config['prev_tag_open'] = '<li>';
			$config['prev_tag_close'] = '</li>';
			$config['next_link'] = '&gt;';
			$config['next_tag_open'] = '<li>';
			$config['next_tag_close'] = '</li>';
			$config['cur_tag_open'] = '<li class="current"><a href="#">';
			$config['cur_tag_close'] = '</a></li>';
			$config['num_tag_open'] = '<li>';
			$config['num_tag_close'] = '</li>';
			
			$config['first_tag_open'] = '<li>';
			$config['first_tag_close'] = '</li>';
			$config['last_tag_open'] = '<li>';
			$config['last_tag_close'] = '</li>';
			
			$config['first_link'] = '&lt;&lt;';
			$config['last_link'] = '&gt;&gt;';
	 
			$this->pagination->initialize($config);
		
			$data['vewproject'] = $this->m_project_invoice->get_last_ten_projectInbox($config["per_page"], $offset)->result();
			$data["pagination"] = $this->pagination->create_links();
		
			// // Start : Searching Function --- Untuk delete session
			$dataSessSrc = array(
					'selSearchproj_Code' => $DefProj_Code,
					'selSearchType' => $this->input->post('selSearchType'),
					'txtSearch' => $this->input->post('txtSearch'));
			$this->session->set_userdata('dtSessSrc1', $dataSessSrc);
			$dataSessSrc   = $this->session->userdata('dtSessSrc1');
			$dataSessSrc   = $this->session->userdata('dtSessSrc2');
			// End : Searching Function	
			
			$this->load->view('template', $data);
		}
		else
		{
			redirect('__l1y');
		}
    }
	
    function inbox_src($offset=0)
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$getAppName = $this->Menu_model->getAppName()->result();
			foreach($getAppName as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			// Secure URL
			$DefEmp_ID 					= $this->session->userdata['Emp_ID'];
			$DefProj_Code		 		= $this->session->userdata['dtSessSrc2']['selSearchproj_Code']; // Session Project Per User
			
			$data['title'] 		= $appName;
			$data['h2_title']	= 'Project Planning List';
			$data['main_view'] 	= 'v_project/v_project_invoice/project_invoice_sd_inb';
			$data['srch_url'] = site_url('c_project/c_prj180c2dinv/inbox_src');
			$data['moffset'] = $offset;
		
			$data['selSearchType'] = $this->input->post('selSearchType');
			$data['txtSearch'] = $this->input->post('txtSearch');		
			
			if (isset($_POST['submitSrch']))
			{
				$selSearchType	= $this->input->post('selSearchType');
				$txtSearch 		= $this->input->post('txtSearch');
			}
			else
			{
				$selSearchType      = $this->session->userdata['dtSessSrc1']['selSearchType'];
				$txtSearch        	= $this->session->userdata['dtSessSrc1']['txtSearch'];
			}	
			
			if($selSearchType == 'ProjNumber')
			{				
				$num_rows = $this->m_project_invoice->count_all_num_rows_PNo($txtSearch, $DefEmp_ID);
			}
			else
			{
				$num_rows = $this->m_project_invoice->count_all_num_rows_PNm($txtSearch, $DefEmp_ID);
			}	
			
			$data["recordcount"] = $num_rows;
			$config = array();
			$config['base_url'] = site_url('c_project/project_planning/get_last_ten_project');
			$config["total_rows"] = $num_rows;
			$config["per_page"] = 10;
			$config["uri_segment"] = 3;
				
			$config['full_tag_open'] = '<ul class="tsc_pagination tsc_paginationA tsc_paginationA01">';
			$config['full_tag_close'] = '</ul>';
			$config['prev_link'] = '&lt;';
			$config['prev_tag_open'] = '<li>';
			$config['prev_tag_close'] = '</li>';
			$config['next_link'] = '&gt;';
			$config['next_tag_open'] = '<li>';
			$config['next_tag_close'] = '</li>';
			$config['cur_tag_open'] = '<li class="current"><a href="#">';
			$config['cur_tag_close'] = '</a></li>';
			$config['num_tag_open'] = '<li>';
			$config['num_tag_close'] = '</li>';
			
			$config['first_tag_open'] = '<li>';
			$config['first_tag_close'] = '</li>';
			$config['last_tag_open'] = '<li>';
			$config['last_tag_close'] = '</li>';
			
			$config['first_link'] = '&lt;&lt;';
			$config['last_link'] = '&gt;&gt;';
	 
			$this->pagination->initialize($config);
			
			if($selSearchType == 'ProjNumber')
			{
				$data['vewproject'] = $this->m_project_invoice->get_last_ten_project_PNo($config["per_page"], $offset, $txtSearch, $DefEmp_ID)->result();
			}
			else
			{
				$data['vewproject'] = $this->m_project_invoice->get_last_ten_project_PNm($config["per_page"], $offset, $txtSearch, $DefEmp_ID)->result();
			}
			
			$data["pagination"] = $this->pagination->create_links();
		
			// // Start : Searching Function --- Untuk delete session
			$dataSessSrc = array(
					'selSearchproj_Code' => $DefProj_Code,
					'selSearchType' => $this->input->post('selSearchType'),
					'txtSearch' => $this->input->post('txtSearch'));
			$this->session->set_userdata('dtSessSrc1', $dataSessSrc);
			$dataSessSrc   = $this->session->userdata('dtSessSrc1');
			// End : Searching Function	
			
			$this->load->view('template', $data);
		}
		else
		{
			redirect('__l1y');
		}
    }

	function get_last_ten_projinvInb($proj_Code, $offset=0)
	{
		$getAppName = $this->Menu_model->getAppName()->result();
		foreach($getAppName as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		// Secure URL
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		$DefProj_Code		 	= $proj_Code; // Session Project Per User
		
		$data['title'] 		= $appName;
		$data['h2_title']	= 'Project Invoice';
		$data['main_view'] 	= 'v_project/v_project_invoice/project_invoice_sd_inbox';
		$data['srch_url'] 	= site_url('c_project/c_prj180c2dinv/get_last_ten_projinvInb_src/'.$proj_Code);
		$data['link'] 		= array('link_back' => anchor('c_project/c_prj180c2dinv/','<input type="button" name="btnCancel" id="btnCancel" class="button_css" value="Back" />', array('style' => 'text-decoration: none;')));
		//$data['proj_ID1'] = $proj_ID;
		$data['proj_Code1'] = $proj_Code;
		$data['moffset'] 	= $offset;	
		
		$num_rows = $this->m_project_invoice->count_all_num_rowsProjInv($proj_Code);
		$num_rows1 = $this->m_project_invoice->count_all_num_rowsProjInv1($proj_Code);
        $data["recordcount"] = $num_rows;
        $data["recordcount1"] = $num_rows1;
		$config = array();
        $config['base_url'] = site_url('c_project/c_prj180c2dinv/gall180c2dinv');
        $config["total_rows"] = $num_rows;
        $config["per_page"] = 15;
        $config["uri_segment"] = 3;
			
		$config['full_tag_open'] = '<ul class="tsc_pagination tsc_paginationA tsc_paginationA01">';
		$config['full_tag_close'] = '</ul>';
		$config['prev_link'] = '&lt;';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		$config['next_link'] = '&gt;';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="current"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		
		$config['first_link'] = '&lt;&lt;';
		$config['last_link'] = '&gt;&gt;';
 
        $this->pagination->initialize($config);

        $data['viewprojinvoice'] = $this->m_project_invoice->get_last_ten_projinvInb($proj_Code, $config["per_page"], $offset)->result();
        $data["pagination"] = $this->pagination->create_links();
		
		// // Start : Searching Function --- Untuk delete session
		$dataSessSrc = array(
                'selSearchproj_Code' => $DefProj_Code,
                'selSearchType' => $this->input->post('selSearchType'),
                'txtSearch' => $this->input->post('txtSearch'));
		$this->session->set_userdata('dtSessSrc1', $dataSessSrc);
		$dataSessSrc   = $this->session->userdata('dtSessSrc1');
		// End : Searching Function	
		
		$this->load->view('template', $data);
	}

	function get_last_ten_projinvInb_src($proj_Code, $offset=0)
	{
		$getAppName = $this->Menu_model->getAppName()->result();
		foreach($getAppName as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$data['title'] = $appName;
		$data['h2_title'] = 'Project Invoice';
		$data['main_view'] = 'v_project/v_project_invoice/project_invoice_sd_inbox';
			$data['srch_url'] = site_url('c_project/c_prj180c2dinv/get_last_ten_projinvInb_src/'.$proj_Code);
		$data['link'] 			= array('link_back' => anchor('c_project/c_prj180c2dinv/','<input type="button" name="btnCancel" id="btnCancel" class="button_css" value="Back" />', array('style' => 'text-decoration: none;')));
		//$data['proj_ID1'] = $proj_ID;
		$data['proj_Code1'] = $proj_Code;
		$data['moffset'] = $offset;
		
		$data['selSearchType'] = $this->input->post('selSearchType');
		$data['txtSearch'] = $this->input->post('txtSearch');		
		
		if (isset($_POST['submitSrch']))
		{
			$selSearchType	= $this->input->post('selSearchType');
			$txtSearch 		= $this->input->post('txtSearch');
			
			$dataSessSrc = array(
                'selSearchType' => $this->input->post('selSearchType'),
                'txtSearch' => $this->input->post('txtSearch'));
				
			$this->session->set_userdata('dtSessSrc1', $dataSessSrc);
			
			$dataSessSrc   = $this->session->userdata('dtSessSrc1');
		}
		else
		{
			$selSearchType      = $this->session->userdata['dtSessSrc1']['selSearchType'];
			$txtSearch        	= $this->session->userdata['dtSessSrc1']['txtSearch'];
			
			$dataSessSrc = array(
                'selSearchType' => $this->session->userdata['dtSessSrc1']['selSearchType'],
                'txtSearch' => $this->session->userdata['dtSessSrc1']['txtSearch']);
				
			$this->session->set_userdata('dtSessSrc1', $dataSessSrc);
		}
		
		$num_rows = $this->m_project_invoice->count_all_num_rows();
        $data["recordcount"] = $num_rows;
		$config = array();
        $config['base_url'] = site_url('c_project/c_prj180c2dinv/gall180c2dinv');
        $config["total_rows"] = $num_rows;
        $config["per_page"] = 15;
        $config["uri_segment"] = 3;
			
		$config['full_tag_open'] = '<ul class="tsc_pagination tsc_paginationA tsc_paginationA01">';
		$config['full_tag_close'] = '</ul>';
		$config['prev_link'] = '&lt;';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		$config['next_link'] = '&gt;';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="current"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		
		$config['first_link'] = '&lt;&lt;';
		$config['last_link'] = '&gt;&gt;';
 
        $this->pagination->initialize($config);
		
		if($selSearchType == 'projINV_No')
		{
			$data['viewprojinvoice'] = $this->m_project_invoice->get_last_ten_projinvInb_MRNo($config["per_page"], $offset, $txtSearch)->result();
		}
		else
		{
			$data['viewprojinvoice'] = $this->m_project_invoice->get_last_ten_projinvInb_PNm($config["per_page"], $offset, $txtSearch)->result();
		}
 
        $data["pagination"] = $this->pagination->create_links();
		
		// // Start : Searching Function --- Untuk delete session
		$dataSessSrc = array(
                'selSearchType' => $this->input->post('selSearchType'),
                'txtSearch' => $this->input->post('txtSearch'));
		$this->session->set_userdata('dtSessSrc1', $dataSessSrc);
		$dataSessSrc   = $this->session->userdata('dtSessSrc1');
		// End : Searching Function	
		
		$this->load->view('template', $data);
	}
	
	function update_inbox($PINV_Number)
	{
		$getAppName = $this->Menu_model->getAppName()->result();
		foreach($getAppName as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$data['title'] 			= $appName;
		$data['task'] 			= 'edit';
		$data['h2_title'] 		= 'Project Invoice | Update';
		$data['main_view'] 		= 'v_project/v_project_invoice/project_invoice_sd_form_inbox';
		$data['form_action']	= site_url('c_project/c_prj180c2dinv/update_process_inbox');
		
		$data['recordcountVend'] 	= $this->m_project_invoice->count_all_num_rowsVend();
		$data['viewvendor'] 	= $this->m_project_invoice->viewvendor()->result();
		$data['recordcountDept'] 	= $this->m_project_invoice->count_all_num_rowsDept();
		$data['viewDepartment'] 	= $this->m_project_invoice->viewDepartment()->result();
		$data['recordcountEmpDept'] 	= $this->m_project_invoice->count_all_num_rowsEmpDept();
		$data['viewEmployeeDept'] 	= $this->m_project_invoice->viewEmployeeDept()->result();
		$data['recordcountProject']	= $this->m_project_invoice->count_all_num_rowsProject();
		$data['viewProject'] 		= $this->m_project_invoice->viewProject()->result();
		
		$getpurINVO = $this->m_project_invoice->get_PINV_by_number($PINV_Number)->row();
		
		$this->session->set_userdata('PINV_Number', $getpurINVO->PINV_Number);
		
		$data['link'] 			= array('link_back' => anchor('c_project/c_prj180c2dinv/get_last_ten_projinvInb/'.$getpurINVO->proj_Code,'<input type="button" name="btnCancel" id="btnCancel" value="Cancel" class="button_css" />', array('style' => 'text-decoration: none;')));
		
		$data['default']['proj_ID'] = $getpurINVO->proj_ID;
		$data['default']['proj_Code'] = $getpurINVO->proj_Code;
		$data['default']['PINV_Number'] = $getpurINVO->PINV_Number;
		$data['default']['PINV_Date'] = $getpurINVO->PINV_Date;
		$data['default']['req_date'] = $getpurINVO->req_date;
		$data['default']['PINV_EndDate'] = $getpurINVO->PINV_EndDate;
		$data['default']['PINV_Class'] = $getpurINVO->PINV_Class;
		$data['default']['PINV_Type'] = $getpurINVO->PINV_Type;
		$data['default']['Owner_Code'] = $getpurINVO->Owner_Code;
		$data['default']['PINV_EmpID'] = $getpurINVO->PINV_EmpID;
		$data['default']['Vend_Code'] = $getpurINVO->Vend_Code;
		$data['default']['PINV_Notes'] = $getpurINVO->PINV_Notes;
		$data['default']['PINV_Status'] = $getpurINVO->PINV_Status;
		$data['default']['PINV_STAT'] = $getpurINVO->PINV_STAT;
		$data['default']['Patt_Year'] = $getpurINVO->Patt_Year;
		$data['default']['Patt_Number'] = $getpurINVO->Patt_Number;
		$data['default']['Memo_Revisi'] = $getpurINVO->Memo_Revisi;
			
		$this->load->view('template', $data);
	}
	
	function update_process_inbox()
	{
		$getAppName = $this->Menu_model->getAppName()->result();
		foreach($getAppName as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$data['title'] 			= $appName;
		$data['task'] 			= 'edit';
		$data['h2_title'] 		= 'Project Invoice | Update Project Invoice';
		$data['main_view'] 		= 'v_project/v_project_invoice/project_invoice_sd_form';
		$data['form_action']	= site_url('c_project/c_prj180c2dinv/update_inbox');
		$data['link'] 			= array('link_back' => anchor('c_project/c_prj180c2dinv/','<input type="button" name="btnCancel" id="btnCancel" value="Cancel" class="button_css" />', array('style' => 'text-decoration: none;')));
		
		//setting MR Date
		$PINV_Date= date('Y-m-d',strtotime($this->input->post('PINV_Date')));
		$Patt_Year= date('Y',strtotime($this->input->post('PINV_Date')));
		//setting Requested Date	
		$req_date= date('Y-m-d',strtotime($this->input->post('req_date')));
		//setting Latest Date
		$PINV_EndDate= date('Y-m-d',strtotime($this->input->post('PINV_EndDate')));
		
		date_default_timezone_set("Asia/Jakarta");
		$Approve_Date = date('Y-m-d H:i:s');
		
		$proj_ID = $this->input->post('proj_ID');
		$proj_Code = $this->input->post('proj_Code');
		
		$valAppStatus = $this->input->post('PINV_STAT');		
		if($valAppStatus == 1 || $valAppStatus == 2)
		{
			$PINV_Status = 2; // 1 = New, 2 = confirm, 3 = Approved, 4 = Close, 5 = Reject
		}
		elseif($valAppStatus == 3)
		{
			$PINV_Status = 3; // 1 = New, 2 = confirm, 3 = Approved, 4 = Close, 5 = Reject
		}
		elseif($valAppStatus == 4)
		{
			$PINV_Status = 1; // 1 = New, 2 = confirm, 3 = Approved, 4 = Close, 5 = Reject
		}
		elseif($valAppStatus == 5)
		{
			$PINV_Status = 5; // 1 = New, 2 = confirm, 3 = Approved, 4 = Close, 5 = Reject
		}
		
		$prohPINVH = array('PINV_Number' 	=> $this->input->post('PINV_Number'),
						'proj_ID'		=> $this->input->post('proj_ID'),
						'proj_Code'		=> $this->input->post('proj_Code'),
						'PINV_Date'		=> $PINV_Date,
						'req_date'		=> $req_date,
						'PINV_EndDate'	=> $PINV_EndDate,
						'Approve_Date'		=> $Approve_Date,
						'PINV_Class'		=> $this->input->post('PINV_Class'),
						'PINV_Type'		=> $this->input->post('PINV_Type'), 
						'Owner_Code'		=> $this->input->post('Owner_Code'),
						'PINV_EmpID'		=> $this->input->post('PINV_EmpID'),
						'Vend_Code'		=> $this->input->post('Vend_Code'),
						'PINV_Notes'		=> $this->input->post('PINV_Notes'),
						'PINV_Status'		=> $PINV_Status,
						'PINV_STAT'	=> $this->input->post('PINV_STAT'),
						'Patt_Year'		=> $this->input->post('Patt_Year'),
						'Patt_Number'	=> $this->input->post('lastPatternNumb'),
						'Memo_Revisi'	=> $this->input->post('Memo_Revisi'));
						
		$this->m_project_invoice->update($this->session->userdata('PINV_Number'), $prohPINVH);	
		
		$this->m_project_invoice->deleteDetail($this->input->post('PINV_Number'));
		
		foreach($_POST['data'] as $d)
		{
			if($valAppStatus == 3)
			{
				$PINV_Number = $d['PINV_Number'];
				$Item_code = $d['Item_code'];
				$request_qty1 = $d['request_qty1'];
				$request_qty2 = $d['request_qty2'];
				// Update Qty  PO for Project Plan per Item Per Project
				$parameters = array(
					'PINV_Number' => $PINV_Number,
					'Item_code' => $Item_code,
					'proj_ID' => $proj_ID,
					'proj_Code' => $proj_Code,
					'request_qty1' => $request_qty1,
					'request_qty2' => $request_qty2
				);
				$this->m_project_invoice->updatePP($PO_Number, $parameters);	
			}
        	$this->db->insert('tproject_mrdetail',$d);
        }
		
		$this->session->set_flashdata('message', 'Data succesful to update.');
		
		redirect('c_project/c_prj180c2dinv/get_last_ten_projinvInb/'.$proj_Code);
	}
	
	function getVendAddress($vendCode)
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
}