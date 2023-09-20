<?php
/*
	* Author		= Dian Hermanto
	* Create Date	= 02 Januari 2018
	* File Name		= spk_form.php
	* Location		= -
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody    = $this->session->userdata('appBody');

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

// _global function
$this->db->select('Display_Rows,decFormat,APPLEV');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
	$APPLEV = $row->APPLEV;
endforeach;
$decFormat		= 2;

$FlagUSER 		= $this->session->userdata['FlagUSER'];
$DefEmp_ID 		= $this->session->userdata['Emp_ID'];

$sql = "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$result = $this->db->query($sql)->result();
foreach($result as $row) :
	$PRJNAME = $row ->PRJNAME;
endforeach;

$currentRow = 0;
$s_PattC	= "tbl_docpattern WHERE menu_code = '$MenuCode'";
$r_PattC 	= $this->db->count_all($s_PattC);
if($r_PattC > 0)
{
	$isSetDocNo = 1;
	$s_Patt		= "SELECT Pattern_Code, Pattern_Length FROM tbl_docpattern WHERE menu_code = '$MenuCode'";
	$r_Patt 	= $this->db->query($s_Patt)->result();
	foreach($r_Patt as $row) :
		$PATTCODE 	= $row->Pattern_Code;
	endforeach;
}
else
{
	$PATTCODE 		= "XXX";
}

if($task == 'add')
{
	$TRXTIME		= date('ymdHis');
	$WO_NUM			= "$PATTCODE$PRJCODE.$TRXTIME";
	$DocNumber 		= "";
	$WO_CODE		= $DocNumber;

	$WO_DATE		= date('d/m/Y');
	$WO_STARTD 		= date('d/m/Y');
	$WO_ENDD		= date('d/m/Y');
	$PRJCODE		= $PRJCODE;
	$SPLCODE		= '';
	$WO_DEPT		= '';
	$WO_CATEG		= 'U'; // SPK Upah
	$WO_TYPE		= 'PO';
	$JOBCODEID		= '';
	$PR_REFNO		= '';
	$JOBCODE1		= $PR_REFNO;
	$WO_NOTE		= '';
	$WO_NOTE2 		= '';
	$WO_PAYNOTE		= '';
	$WO_STAT 		= 1;
	$Patt_Year 		= date('Y');
	$Patt_Number	= 0;
	$WO_VALUE		= 0;
	$WO_MEMO		= '';
	$WO_REFNO		= '';
	$FPA_NUM		= '';
	$WO_QUOT		= '';
	$WO_NEGO		= '';
	$WO_PAYTYPE 	= 0;
	$WO_TENOR 		= 0;

	$WO_DPPER		= 0;
	$WO_DPREF		= '';
	$WO_DPREF1		= '';
	$WO_DPVAL		= 0;
	$WO_RETP		= 0;
	$WO_RETVAL		= 0;
	$WO_VALPPN 		= 0;
	$WO_VALPPH 		= 0;
	$WO_GTOTAL 		= 0;
}
else
{
	$isSetDocNo = 1;
	$WO_NUM 	= $default['WO_NUM'];
	$DocNumber	= $default['WO_NUM'];
	$WO_CODE 	= $default['WO_CODE'];
	$WO_DATE 	= date('d/m/Y', strtotime($default['WO_DATE']));
	$WO_STARTD 	= date('d/m/Y', strtotime($default['WO_STARTD']));
	$WO_ENDD 	= date('d/m/Y', strtotime($default['WO_ENDD']));
	$PRJCODE	= $default['PRJCODE'];
	$SPLCODE 	= $default['SPLCODE'];
	$WO_DEPT 	= $default['WO_DEPT'];
	$WO_CATEG 	= $default['WO_CATEG'];
	$WO_TYPE 	= $default['WO_TYPE'];
	$JOBCODEID 	= $default['JOBCODEID'];
	$PR_REFNO	= $default['JOBCODEID'];
	$JOBCODE1	= $PR_REFNO;
	$WO_NOTE 	= $default['WO_NOTE'];
	$WO_NOTE2 	= $default['WO_NOTE2'];
	$WO_PAYNOTE = $default['WO_PAYNOTE'];
	$WO_STAT 	= $default['WO_STAT'];
	$WO_MEMO 	= $default['WO_MEMO'];
	$WO_REFNO 	= $default['WO_REFNO'];
	$PRJNAME 	= $default['PRJNAME'];
	$FPA_NUM 	= $default['FPA_NUM'];
	$WO_QUOT 	= $default['WO_QUOT'];
	$WO_NEGO 	= $default['WO_NEGO'];
	$WO_DPPER 	= $default['WO_DPPER'];
	$WO_DPREF 	= $default['WO_DPREF'];
	$WO_DPREF1 	= $default['WO_DPREF1'];
	$WO_DPVAL 	= $default['WO_DPVAL'];
	$WO_RETP 	= $default['WO_RETP'];
	$WO_RETVAL 	= $default['WO_RETVAL'];
	$WO_VALUE	= $default['WO_VALUE'];
	$WO_VALPPN 	= $default['WO_VALPPN'];
	$WO_VALPPH 	= $default['WO_VALPPH'];
	$WO_GTOTAL 	= $default['WO_GTOTAL'];
	$WO_PAYTYPE = $default['WO_PAYTYPE'];
	$WO_TENOR 	= $default['WO_TENOR'];
	$Patt_Year 	= $default['Patt_Year'];
	$Patt_Month = $default['Patt_Month'];
	$Patt_Date 	= $default['Patt_Date'];
	$Patt_Number= $default['Patt_Number'];
}

if(isset($_POST['JOBCODE1']))
{
	$PR_REFNO	= $_POST['JOBCODE1'];
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $appName; ?></title>
        <!-- Tell the browser to be responsive to screen width -->
        <?php
	        $vers   = $this->session->userdata['vers'];

	        $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'css' AND isAct = 1 AND cssjs_vers IN ('$vers', 'All')";
	        $rescss = $this->db->query($sqlcss)->result();
	        foreach($rescss as $rowcss) :
	            $cssjs_lnk  = $rowcss->cssjs_lnk;
	            ?>
	                <link rel="stylesheet" href="<?php echo base_url($cssjs_lnk) ?>">
	            <?php
	        endforeach;

	        $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'jss' AND isAct = 1 AND cssjs_vers IN ('$vers', 'All')";
	        $rescss = $this->db->query($sqlcss)->result();
	        foreach($rescss as $rowcss) :
	            $cssjs_lnk1  = $rowcss->cssjs_lnk;
	            ?>
	                <script src="<?php echo base_url($cssjs_lnk1) ?>"></script>
	            <?php
        	endforeach;
        ?>

        <!-- Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    </head>

	<?php
		$this->load->view('template/mna');
		//______$this->load->view('template/topbar');
		//______$this->load->view('template/sidebar');

		$ISREAD 	= $this->session->userdata['ISREAD'];
		$ISCREATE 	= $this->session->userdata['ISCREATE'];
		$ISAPPROVE 	= $this->session->userdata['ISAPPROVE'];
		$ISDWONL 	= $this->session->userdata['ISDWONL'];
		$ISDELETE = $this->session->userdata['ISDELETE'];
		$LangID 	= $this->session->userdata['LangID'];

		$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
		$resTransl		= $this->db->query($sqlTransl)->result();
		foreach($resTransl as $rowTransl) :
			$TranslCode	= $rowTransl->MLANG_CODE;
			$LangTransl	= $rowTransl->LangTransl;

			if($TranslCode == 'Add')$Add = $LangTransl;
			if($TranslCode == 'Edit')$Edit = $LangTransl;
			if($TranslCode == 'Save')$Save = $LangTransl;
			if($TranslCode == 'Update')$Update = $LangTransl;
			if($TranslCode == 'Back')$Back = $LangTransl;
			if($TranslCode == 'WONo')$WONo = $LangTransl;
			if($TranslCode == 'Category')$Category = $LangTransl;
			if($TranslCode == 'Type')$Type = $LangTransl;
			if($TranslCode == 'RequestCode')$RequestCode = $LangTransl;
			if($TranslCode == 'WOCode')$WOCode = $LangTransl;
			if($TranslCode == 'Code')$Code = $LangTransl;
			if($TranslCode == 'Notes')$Notes = $LangTransl;
			if($TranslCode == 'Date')$Date = $LangTransl;
			if($TranslCode == 'Project')$Project = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'AlertRevisionorReject')$AlertRevisionorReject = $LangTransl;
			if($TranslCode == 'PaymentType')$PaymentType = $LangTransl;
			if($TranslCode == 'PaymentTerm')$PaymentTerm = $LangTransl;
			if($TranslCode == 'New')$New = $LangTransl;
			if($TranslCode == 'Confirm')$Confirm = $LangTransl;
			if($TranslCode == 'Close')$Close = $LangTransl;
			if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
			if($TranslCode == 'ItemName')$ItemName = $LangTransl;
			if($TranslCode == 'BudgetQty')$BudgetQty = $LangTransl;
			if($TranslCode == 'Planning')$Planning = $LangTransl;
			if($TranslCode == 'Requested')$Requested = $LangTransl;
			if($TranslCode == 'RequestNow')$RequestNow = $LangTransl;
			if($TranslCode == 'QtyOpnamed')$QtyOpnamed = $LangTransl;
			if($TranslCode == 'Quantity')$Quantity = $LangTransl;
			if($TranslCode == 'Unit')$Unit = $LangTransl;
			if($TranslCode == 'Primary')$Primary = $LangTransl;
			if($TranslCode == 'Secondary')$Secondary = $LangTransl;
			if($TranslCode == 'Amount')$Amount = $LangTransl;
			if($TranslCode == 'SelectItem')$SelectItem = $LangTransl;
			if($TranslCode == 'JobName')$JobName = $LangTransl;
			if($TranslCode == 'ItemQty')$ItemQty = $LangTransl;
			if($TranslCode == 'SPKDate')$SPKDate = $LangTransl;
			if($TranslCode == 'StartDate')$StartDate = $LangTransl;
			if($TranslCode == 'EndDate')$EndDate = $LangTransl;
			if($TranslCode == 'Approval')$Approval = $LangTransl;
			if($TranslCode == 'Approved')$Approved = $LangTransl;
			if($TranslCode == 'NotYetApproved')$NotYetApproved = $LangTransl;
			if($TranslCode == 'Awaiting')$Awaiting = $LangTransl;
			if($TranslCode == 'Price')$Price = $LangTransl;
			if($TranslCode == 'Supplier')$Supplier = $LangTransl;
			if($TranslCode == 'Tax')$Tax = $LangTransl;
			if($TranslCode == 'Search')$Search = $LangTransl;
			if($TranslCode == 'QuotNo')$QuotNo = $LangTransl;
			if($TranslCode == 'NegotNo')$NegotNo = $LangTransl;
			if($TranslCode == 'Reason')$Reason = $LangTransl;
			if($TranslCode == 'SupplierName')$SupplierName = $LangTransl;
			if($TranslCode == 'ItemList')$ItemList = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'StockQuantity')$StockQuantity = $LangTransl;
			if($TranslCode == 'Select')$Select = $LangTransl;
			if($TranslCode == 'itmSub')$itmSub = $LangTransl;
			if($TranslCode == 'Wage')$Wage = $LangTransl;

			if($TranslCode == 'ApproverNotes')$ApproverNotes = $LangTransl;
			if($TranslCode == 'reviseNotes')$reviseNotes = $LangTransl;
			if($TranslCode == 'Approve')$Approve = $LangTransl;
			if($TranslCode == 'Approver')$Approver = $LangTransl;
			if($TranslCode == 'Approved')$Approved = $LangTransl;
			if($TranslCode == 'Approval')$Approval = $LangTransl;
			if($TranslCode == 'NotYetApproved')$NotYetApproved = $LangTransl;
			if($TranslCode == 'InOthSett')$InOthSett = $LangTransl;
			if($TranslCode == 'JobList')$JobList = $LangTransl;
			if($TranslCode == 'JobNm')$JobNm = $LangTransl;
			if($TranslCode == 'Remain')$Remain = $LangTransl;
			if($TranslCode == 'Cancel')$Cancel = $LangTransl;
			if($TranslCode == 'sureVoid')$sureVoid = $LangTransl;
		endforeach;

		if($LangID == 'IND')
		{
			$subTitleH	= "Tambah SPK";
			$subTitleD	= "surat perintah kerja";
			$alert1		= "Silahkan masukan volume pekerjaan.";
			$alert2		= "Silahkan masukan detail pekerjaan.";
			$alert3		= "Silahkan pilih pekerjaan.";
			$alert4		= "Silahkan pilih Supplier.";
			$alert5		= "Silahkan masukan nomor FPA.";
			$alert6		= "Masukan alasan mengapa dokumen ini di-close.";
			$alert7		= "Masukan persentase DP.";
			$alert8		= "Masukan kode DP.";
			$alert9		= "Anda belum memilih detail item.";
			$alert10	= "Catatan SPK tidak boleh kosong";
			$alert11 	= "Masukan volume yang akan dibatalkan.";
			$alert12	= "Deskripsi item tidak boleh kosong";
			$alertVOID	= "Tidak dapat dibatalkan. Sudah digunakan oleh Dokumen No.: ";
			$isManual	= "Centang untuk kode manual.";
			$alertSubmit= "data sudah berhasil disimpan";
			$alertGreat = "Nilai yang Anda masukan melebihi sisa budget yang tersedia.";
		}
		else
		{
			$subTitleH	= "Add WO";
			$subTitleD	= "work order";
			$alert1		= "Please input qty of job volume.";
			$alert2		= "Please input job detail.";
			$alert3		= "Please select Job.";
			$alert4		= "Please select a Supplier.";
			$alert5		= "Please input FPA Number.";
			$alert6		= "Input the reason why you close this document.";
			$alert7		= "DP Percentation can not be empty.";
			$alert8		= "DP Code can not be empty.";
			$alert9		= "You have not selected the item details.";
			$alert10	= "WO Note can not be empty";
			$alert11 	= "Please enter the value that will you canceled.";
			$alert12	= "Item Description can not be empty";
			$alertVOID	= "Can not be void. Used by document No.: ";
			$isManual	= "Check to manual code.";
			$alertSubmit= "data has been successfully saved";
			$alertGreat = "The value you enter exceeds the remaining available budget.";
		}
		
		// START : APPROVE PROCEDURE
			if($APPLEV == 'HO')
				$PRJCODE_LEV	= $this->data['PRJCODE_HO'];
			else
				$PRJCODE_LEV	= $this->data['PRJCODE'];
			
			// DocNumber - PR_VALUE
			$EMPN_1 	= "";
			$EMPN_2 	= "";
			$EMPN_3 	= "";
			$EMPN_4		= "";
			$EMPN_5 	= "";

			$IS_LAST	= 0;
			$APP_LEVEL	= 0;
			$APPROVER_1	= '';
			$APPROVER_2	= '';
			$APPROVER_3	= '';
			$APPROVER_4	= '';
			$APPROVER_5	= '';	
			$disableAll	= 1;
			$DOCAPP_TYPE= 1;
			$sqlCAPP	= "tbl_docstepapp WHERE MENU_CODE = '$MenuApp' AND PRJCODE = '$PRJCODE_LEV'";
			$resCAPP	= $this->db->count_all($sqlCAPP);
			if($resCAPP > 0)
			{
				$sqlAPP	= "SELECT * FROM tbl_docstepapp WHERE MENU_CODE = '$MenuApp'
							AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE_LEV')";
				$resAPP	= $this->db->query($sqlAPP)->result();
				foreach($resAPP as $rowAPP) :
					$MAX_STEP		= $rowAPP->MAX_STEP;
					$APPROVER_1		= $rowAPP->APPROVER_1;
					if($APPROVER_1 != '')
					{
						$EMPN_1		= '';
						$sqlEMPC_1	= "tbl_employee WHERE Emp_ID = '$APPROVER_1' AND Emp_Status = '1'";
						$resEMPC_1	= $this->db->count_all($sqlEMPC_1);
						if($resEMPC_1 > 0)
						{
							$sqlEMP_1	= "SELECT First_Name, Last_Name FROM tbl_employee WHERE Emp_ID = '$APPROVER_1' AND Emp_Status = '1' LIMIT 1";
							$resEMP_1	= $this->db->query($sqlEMP_1)->result();
							foreach($resEMP_1 as $rowEMP) :
								$FN_1	= $rowEMP->First_Name;
								$LN_1	= $rowEMP->Last_Name;
							endforeach;
							$EMPN_1		= "$FN_1 $LN_1";
						}
					}
					$APPROVER_2	= $rowAPP->APPROVER_2;
					if($APPROVER_2 != '')
					{
						$EMPN_2		= '';
						$sqlEMPC_2	= "tbl_employee WHERE Emp_ID = '$APPROVER_2' AND Emp_Status = '1'";
						$resEMPC_2	= $this->db->count_all($sqlEMPC_2);
						if($resEMPC_2 > 0)
						{
							$sqlEMP_2	= "SELECT First_Name, Last_Name FROM tbl_employee WHERE Emp_ID = '$APPROVER_2' AND Emp_Status = '1' LIMIT 1";
							$resEMP_2	= $this->db->query($sqlEMP_2)->result();
							foreach($resEMP_2 as $rowEMP) :
								$FN_2	= $rowEMP->First_Name;
								$LN_2	= $rowEMP->Last_Name;
							endforeach;
							$EMPN_2		= "$FN_2 $LN_2";
						}
					}
					$APPROVER_3	= $rowAPP->APPROVER_3;
					if($APPROVER_3 != '')
					{
						$EMPN_3		= '';

						$sqlEMPC_3	= "tbl_employee WHERE Emp_ID = '$APPROVER_3' AND Emp_Status = '1'";
						$resEMPC_3	= $this->db->count_all($sqlEMPC_3);
						if($resEMPC_3 > 0)
						{
							$sqlEMP_3	= "SELECT First_Name, Last_Name FROM tbl_employee WHERE Emp_ID = '$APPROVER_3' AND Emp_Status = '1' LIMIT 1";
							$resEMP_3	= $this->db->query($sqlEMP_3)->result();
							foreach($resEMP_3 as $rowEMP) :
								$FN_3	= $rowEMP->First_Name;
								$LN_3	= $rowEMP->Last_Name;
							endforeach;
							$EMPN_3		= "$FN_3 $LN_3";
						}
					}
					$APPROVER_4	= $rowAPP->APPROVER_4;
					if($APPROVER_4 != '')
					{
						$EMPN_4		= '';
						$sqlEMPC_4	= "tbl_employee WHERE Emp_ID = '$APPROVER_4' AND Emp_Status = '1'";
						$resEMPC_4	= $this->db->count_all($sqlEMPC_4);
						if($resEMPC_4 > 0)
						{
							$sqlEMP_4	= "SELECT First_Name, Last_Name FROM tbl_employee WHERE Emp_ID = '$APPROVER_4' AND Emp_Status = '1' LIMIT 1";
							$resEMP_4	= $this->db->query($sqlEMP_4)->result();
							foreach($resEMP_4 as $rowEMP) :
								$FN_4	= $rowEMP->First_Name;
								$LN_4	= $rowEMP->Last_Name;
							endforeach;
							$EMPN_4		= "$FN_4 $LN_4";
						}
					}
					$APPROVER_5	= $rowAPP->APPROVER_5;
					if($APPROVER_5 != '')
					{
						$EMPN_5		= '';
						$sqlEMPC_5	= "tbl_employee WHERE Emp_ID = '$APPROVER_5' AND Emp_Status = '1'";
						$resEMPC_5	= $this->db->count_all($sqlEMPC_5);
						if($resEMPC_5 > 0)
						{
							$sqlEMP_5	= "SELECT First_Name, Last_Name FROM tbl_employee WHERE Emp_ID = '$APPROVER_5' AND Emp_Status = '1' LIMIT 1";
							$resEMP_5	= $this->db->query($sqlEMP_5)->result();
							foreach($resEMP_5 as $rowEMP) :
								$FN_5	= $rowEMP->First_Name;
								$LN_5	= $rowEMP->Last_Name;
							endforeach;
							$EMPN_5		= "$FN_5 $LN_5";
						}
					}
				endforeach;
				$disableAll	= 0;
			
				// CHECK AUTH APPROVE TYPE
				$sqlAPPT	= "SELECT DOCAPP_TYPE FROM tbl_docstepapp WHERE MENU_CODE = '$MenuApp'
								AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE_LEV')";
				$resAPPT	= $this->db->query($sqlAPP)->result();
				foreach($resAPPT as $rowAPPT) :
					$DOCAPP_TYPE	= $rowAPPT->DOCAPP_TYPE;
				endforeach;
			}
			
			$sqlSTEPAPP	= "tbl_docstepapp_det WHERE MENU_CODE = '$MenuApp' AND APPROVER_1 = '$DefEmp_ID'
							AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE_LEV')";
			$resSTEPAPP	= $this->db->count_all($sqlSTEPAPP);
			
			if($resSTEPAPP > 0)
			{
				$canApprove	= 1;
				$APPLIMIT_1	= 0;
				
				$sqlAPP	= "SELECT APPLIMIT_1, APP_STEP, MAX_STEP FROM tbl_docstepapp_det WHERE MENU_CODE = '$MenuApp'
							AND APPROVER_1 = '$DefEmp_ID' AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE_LEV')";
				$resAPP	= $this->db->query($sqlAPP)->result();
				foreach($resAPP as $rowAPP) :
					$APPLIMIT_1	= $rowAPP->APPLIMIT_1;
					$APP_STEP	= $rowAPP->APP_STEP;
					$MAX_STEP	= $rowAPP->MAX_STEP;
				endforeach;
				$sqlC_App	= "tbl_approve_hist WHERE AH_CODE = '$DocNumber'";
				$resC_App 	= $this->db->count_all($sqlC_App);
				
				$BefStepApp	= $APP_STEP - 1;
				if($resC_App == $BefStepApp)
				{
					$canApprove	= 1;
				}
				elseif($resC_App == $APP_STEP)
				{
					$canApprove	= 0;
					$descApp	= "You have Approved";
					$statcoloer	= "success";
				}
				else
				{
					$canApprove	= 0;
					$descApp	= "Awaiting";
					$statcoloer	= "warning";
				}
							 
				if($APP_STEP == $MAX_STEP)
					$IS_LAST		= 1;
				else
					$IS_LAST		= 0;
				
				// Mungkin dengan tahapan approval lolos, check kembali total nilai jika dan HANYA JIKA Type Approval Step is 1 = Ammount
				// This roles are for All Approval. Except PR and Receipt
				// NOTES
				// $APPLIMIT_1 		= Maximum Limit to Approve
				// $APPROVE_AMOUNT	= Amount must be Approved
				$APPROVE_AMOUNT 	= $WO_VALUE;
				//$APPROVE_AMOUNT	= 10000000000;
				//$DOCAPP_TYPE	= 1;
				if($DOCAPP_TYPE == 1)
				{
					if($APPLIMIT_1 < $APPROVE_AMOUNT)
					{
						$canApprove	= 0;
						$descApp	= "You can not approve caused of the max limit.";
						$statcoloer	= "danger";
					}
				}
			}
			else
			{
				$canApprove	= 0;
				$descApp	= "You can not approve this document.";
				$statcoloer	= "danger";
				$IS_LAST	= 0;
				$APP_STEP	= 0;
			}
			
			$APP_LEVEL	= $APP_STEP;
		// END : APPROVE PROCEDURE
		
		// VOID CHECKING FUNCTION
			$DOC_NO	= '';
			$sqlOPNC= "tbl_opn_header WHERE WO_NUM = '$WO_NUM' AND OPNH_STAT NOT IN (5,9)";
			$isUSED	= $this->db->count_all($sqlOPNC);
			if($isUSED > 0)
			{
				$noU	= 0;
				$sqlOPN	= "SELECT OPNH_CODE FROM tbl_opn_header WHERE WO_NUM = '$WO_NUM' AND OPNH_STAT NOT IN (5,9)";
				$resOPN	= $this->db->query($sqlOPN)->result();
				foreach($resOPN as $rowOPN):
					$noU	= $noU + 1;
					$DOCNO	= $rowOPN->OPNH_CODE;
					if($noU == 1)
						$DOC_NO = $DOCNO;
					else
						$DOC_NO	= $DOC_NO.", ".$DOCNO;
				endforeach;
			}
		
		$secAddURL	= site_url('c_project/c_s180d0bpk/add/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
		$secGenCode	= base_url().'index.php/c_project/c_s180d0bpk/genCode/'; // Generate Code
		$secGetJID	= base_url().'index.php/c_project/c_s180d0bpk/getJID/'; // Generate Code
		$secGetPPn	= base_url().'index.php/c_project/c_s180d0bpk/getPPN/'; // Generate Code
		$secGetPPh	= base_url().'index.php/c_project/c_s180d0bpk/getPPH/'; // Generate Code

        $comp_color = $this->session->userdata('comp_color');
    ?>

    <style type="text/css">
        .search-table, td, th {
            border-collapse: collapse;
        }
        .search-table-outter { overflow-x: scroll; }

        a[disabled="disabled"] {
            pointer-events: none;
        }
        label {
        	font-size: 9pt;
        }
    </style>
    
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
			<h1>
		    <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/wo.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $mnName; ?>
		    <small><?php echo $PRJNAME; ?></small>  </h1>
		</section>

		<section class="content">
		    <div class="row">
            	<form method="post" name="sendDate" id="sendDate" class="form-user" action="<?php echo $secGenCode; ?>" style="display:none">
		            <table>
		                <tr>
		                    <td>
		                        <input type="hidden" name="PRJCODEX" id="PRJCODEX" value="<?php echo $PRJCODE; ?>">
		                        <input type="hidden" name="Pattern_Code" id="Pattern_Code" value="<?php echo $PATTCODE; ?>">
		                        <input type="hidden" name="Pattern_Length" id="Pattern_Length" value="<?php echo $Pattern_Length; ?>">
		                        <input type="hidden" name="useYear" id="useYear" value="<?php echo $useYear; ?>">
		                        <input type="hidden" name="useMonth" id="useMonth" value="<?php echo $useMonth; ?>">
		                        <input type="hidden" name="useDate" id="useDate" value="<?php echo $useDate; ?>">
		                        <input type="hidden" name="WODate" id="WODate" value="">
		                    </td>
		                    <td><a class="tombol-date" id="dateClass">Simpan</a></td>
		                </tr>
		            </table>
		        </form>
		        <form method="post" name="collJID" id="collJID" class="form-user" action="<?php echo $secGetJID; ?>" style="display:none">
		            <table>
		                <tr>
		                    <td>
		                        <input type="hidden" name="WODate" id="WODate" value="">
		                    </td>
		                    <td><a class="tombol-date" id="dateClass">Simpan</a></td>
		                </tr>
		            </table>
		        </form>
		        <!-- after get Supplier code -->
		        <form name="frmsrch1" id="frmsrch1" action="" method=POST style="display:none">
		            <input type="text" name="PRJCODE1" id="PRJCODE1" value="<?php echo $PRJCODE; ?>" />
		            <input type="text" name="JOBCODE1" id="JOBCODE1" value="<?php echo $PR_REFNO; ?>" />
		            <input type="submit" class="button_css" name="submitSrch1" id="submitSrch1" value=" search " />
		        </form>
		        <!-- End -->

		        <form class="form-horizontal" name="frm" id="frm" method="post" action="<?php echo $form_action; ?>" enctype="multipart/form-data" onSubmit="return checkInp()">
					<div class="col-md-6">
						<div class="box box-primary">
							<div class="box-header with-border" style="display: none;">
								<i class="fa fa-cloud-upload"></i>
								<h3 class="box-title">&nbsp;</h3>
							</div>
							<div class="box-body">
					        	<input type="hidden" name="isUSED" id="isUSED" value="<?php echo $isUSED; ?>">
					            <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
					            <input type="hidden" name="Patt_Number" id="Patt_Number" value="<?php echo $Patt_Number; ?>" />
					            <input type="Hidden" name="rowCount" id="rowCount" value="0">
								<?php if($isSetDocNo == 0) { ?>
					                <div class="form-group">
					                    <label for="inputName" class="col-sm-2 control-label">&nbsp;</label>
					                    <div class="col-sm-9">
					                        <div class="alert alert-danger alert-dismissible">
					                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					                            <h4><i class="icon fa fa-ban"></i> <?php echo $docalert1; ?>!</h4>
					                            <?php echo $docalert2; ?>
					                        </div>
					                    </div>
					                </div>
				                <?php } ?>
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-2 control-label"><?php echo $WONo; ?></label>
				                    <div class="col-sm-10">
				                    	<input type="text" class="form-control" style="text-align:left" name="WO_NUMX" id="WO_NUMX" size="30" value="<?php echo $WO_NUM; ?>" disabled />
				                    	<input type="hidden" class="form-control" name="WO_NUM" id="WO_NUM" value="<?php echo $WO_NUM; ?>" />
				                    </div>
				                </div>
				                <div class="form-group">
				                    <label for="inputName" class="col-sm-2 control-label"><?php echo $WOCode; ?></label>
				                    <div class="col-sm-4">
				                        <input type="text" class="form-control" id="WO_CODE" name="WO_CODE" value="<?php echo $WO_CODE; ?>" readonly />
				                    </div>
				                    <div class="col-sm-2">
				                        <label for="inputName" class="control-label"><?php echo $SPKDate; ?></label>
				                    </div>
			                        <div class="col-sm-4">
				                    	<div class="input-group date">
				                        <div class="input-group-addon">
				                        <i class="fa fa-calendar"></i>&nbsp;</div><input type="text" name="WO_DATE" class="form-control pull-left" id="datepicker" value="<?php echo $WO_DATE; ?>"></div>
				                    </div>
				                </div>
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-2 control-label"><?php echo $StartDate; ?></label>
				                    <div class="col-sm-4">
				                    	<div class="input-group date">
				                        <div class="input-group-addon">
				                        <i class="fa fa-calendar"></i>&nbsp;</div><input type="text" name="WO_STARTD" class="form-control pull-left" id="datepicker2" value="<?php echo $WO_STARTD; ?>"></div>
				                    </div>
				                    <div class="col-sm-2">
				                    	<label for="inputName" class="control-label"><?php echo $EndDate; ?></label>
				                    </div>
				                    <div class="col-sm-4">
				                    	<div class="input-group date">
				                        <div class="input-group-addon">
				                        <i class="fa fa-calendar"></i>&nbsp;</div><input type="text" name="WO_ENDD" class="form-control pull-left" id="datepicker1" value="<?php echo $WO_ENDD; ?>"></div>
				                    </div>
				                </div>
				                <?php
									$WO_CODE	= '';
									$url_selFPAMDR	= site_url('c_project/c_s180d0bpk/s3l4llFP4MDR/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
									$url_selFPASUB	= site_url('c_project/c_s180d0bpk/s3l4llFP4SUB/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
									$url_selFPASALT	= site_url('c_project/c_s180d0bpk/s3l4llFP4/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
									$FPA_CODE		= '';
									if($WO_CATEG == 'U')
									{
										$FPA_CODE		= '';
										$sqlWOREQ		= "SELECT FPA_CODE FROM tbl_fpa_header WHERE FPA_NUM = '$FPA_NUM' LIMIT 1";
										$sqlWOREQ		= $this->db->query($sqlWOREQ)->result();
										foreach($sqlWOREQ as $rowWOREQ) :
											$FPA_CODE	= $rowWOREQ->FPA_CODE;
										endforeach;
									}
									else if($WO_CATEG == 'S')
									{
										$FPA_CODE		= '';
										$sqlWOREQ		= "SELECT FPA_CODE FROM tbl_fpa_header WHERE FPA_NUM = '$FPA_NUM' LIMIT 1";
										$sqlWOREQ		= $this->db->query($sqlWOREQ)->result();
										foreach($sqlWOREQ as $rowWOREQ) :
											$FPA_CODE	= $rowWOREQ->FPA_CODE;
										endforeach;
									}
									elseif($WO_CATEG == 'A')
									{
										$FPA_CODE		= '';
										$sqlWOREQ		= "SELECT WO_CODE FROM tbl_woreq_header WHERE WO_NUM = '$FPA_NUM' LIMIT 1";
										$sqlWOREQ		= $this->db->query($sqlWOREQ)->result();
										foreach($sqlWOREQ as $rowWOREQ) :
											$FPA_CODE	= $rowWOREQ->WO_CODE;
										endforeach;
									}
								?>
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Supplier; ?></label>
				                    <div class="col-sm-4">
				                    	<select name="WO_CATEG" id="WO_CATEG" class="form-control select2" onChange="changeCODE(this.value)" >
				                          	<option value="none"> --- </option>
				                          	<?php
				                          		$this->db->where_not_in("VendCat_Code", ["B","O"]);
				                          		$getWOCAT = $this->db->get("tbl_vendcat");
				                          		if($getWOCAT->num_rows() > 0)
				                          		{
				                          			foreach($getWOCAT->result() as $rWOCAT):
				                          				$VendCat_Code 	= $rWOCAT->VendCat_Code;
				                          				$VendCat_Name 	= $rWOCAT->VendCat_Name;
				                          				$VendCat_Desc 	= $rWOCAT->VendCat_Desc;
				                          				$VC_LA_PAYDP 	= $rWOCAT->VC_LA_PAYDP;
				                          				$VC_LA_PAYINV 	= $rWOCAT->VC_LA_PAYINV;
				                          				$VC_LA_OPNRET 	= $rWOCAT->VC_LA_OPNRET;
				                          				$VC_LA_RET 		= $rWOCAT->VC_LA_RET;
				                          				?>
				                          					<option value="<?=$VendCat_Code?>" <?php echo $WO_CATEG == $VendCat_Code ? 'selected':'' ?>><?=$VendCat_Name?></option>
				                          				<?php
				                          			endforeach;
				                          		}
				                          	?>
				                        </select>
				                    </div>
				                    <div class="col-sm-6">
				                        <select name="SPLCODE" id="SPLCODE" class="form-control select2" data-placeholder="&nbsp;&nbsp;<?php echo $SupplierName; ?>">
				                          <option value="0"> --- </option>
				                          <?php
				                            	$sqlSpl	= "SELECT SPLCODE, SPLDESC FROM tbl_supplier WHERE SPLSTAT = '1' ORDER BY SPLDESC ASC";
												$sqlSpl	= $this->db->query($sqlSpl)->result();
												foreach($sqlSpl as $row) :
													$SPLCODE1	= $row->SPLCODE;
													$SPLDESC1	= $row->SPLDESC;
													?>
														<option value="<?php echo "$SPLCODE1"; ?>" <?php if($SPLCODE1 == $SPLCODE) { ?> selected <?php } ?>>
															<?php echo "$SPLDESC1 - $SPLCODE1"; ?>
														</option>
													<?php
												endforeach;
				                            ?>
				                        </select>
				                    </div>
				                </div>
				                <script>
									function changeCODE(thisVal)
									{
										let WOCODE		= document.getElementById('WO_CODE').value;
										let PATT_CODE 	= WOCODE.substring(0, 3);
										let lnPATT 		= PATT_CODE.length;
										let PATT_CODEC 	= PATT_CODE.substring(lnPATT - 1);
										if(thisVal == 'U')
										{
											PATT_CODEN	= PATT_CODE.replace(PATT_CODEC, thisVal);
											WO_CODEN 	= WOCODE.replace(PATT_CODE, PATT_CODEN);
											document.getElementById('li2').style.display = 'block';
											document.getElementById('li3').style.display = 'none';
											document.getElementById('li4').style.display = 'none';
											document.getElementById('li5').style.display = 'none';
											document.getElementById('li6').style.display = 'none';
											//document.getElementById('woreq_ref').style.display	= 'none';
											//document.getElementById('WO_MEMO').disabled 		= true;
											var tableHeaderRowCount = 1;
											var table = document.getElementById('tbl');
											var rowCount = table.rows.length;
											for (var i = tableHeaderRowCount; i < rowCount; i++) {
											    table.deleteRow(tableHeaderRowCount);
											}
										}
										else if(thisVal == 'A')
										{
											PATT_CODEN	= PATT_CODE.replace(PATT_CODEC, thisVal);
											WO_CODEN 	= WOCODE.replace(PATT_CODE, PATT_CODEN);
											document.getElementById('li2').style.display = 'block';
											document.getElementById('li3').style.display = 'block';
											document.getElementById('li4').style.display = 'block';
											document.getElementById('li5').style.display = 'block';
											document.getElementById('li6').style.display = 'none';
											//document.getElementById('woreq_ref').style.display	= 'none';
											//document.getElementById('WO_MEMO').disabled 		= true;
											var tableHeaderRowCount = 1;
											var table = document.getElementById('tbl');
											var rowCount = table.rows.length;
											for (var i = tableHeaderRowCount; i < rowCount; i++) {
											    table.deleteRow(tableHeaderRowCount);
											}
										}
										else if(thisVal == 'S')
										{
											PATT_CODEN	= PATT_CODE.replace(PATT_CODEC, thisVal);
											WO_CODEN 	= WOCODE.replace(PATT_CODE, PATT_CODEN);
											document.getElementById('li2').style.display = 'block';
											document.getElementById('li3').style.display = 'block';
											document.getElementById('li4').style.display = 'block';
											document.getElementById('li5').style.display = 'block';
											document.getElementById('li6').style.display = 'none';
											//document.getElementById('woreq_ref').style.display	= 'none';
											//document.getElementById('WO_MEMO').disabled 		= true;
											var tableHeaderRowCount = 1;
											var table = document.getElementById('tbl');
											var rowCount = table.rows.length;
											for (var i = tableHeaderRowCount; i < rowCount; i++) {
											    table.deleteRow(tableHeaderRowCount);
											}
										}
										else
										{
											PATT_CODEN	= PATT_CODE.replace(PATT_CODEC, thisVal);
											WO_CODEN 	= WOCODE.replace(PATT_CODE, PATT_CODEN);
											document.getElementById('li2').style.display = 'block';
											document.getElementById('li3').style.display = 'block';
											document.getElementById('li4').style.display = 'block';
											document.getElementById('li5').style.display = 'block';
											document.getElementById('li6').style.display = 'none';
											//document.getElementById('woreq_ref').style.display	= '';
											//document.getElementById('WO_MEMO').disabled 		= false;
											var tableHeaderRowCount = 1;
											var table = document.getElementById('tbl');
											var rowCount = table.rows.length;
											for (var i = tableHeaderRowCount; i < rowCount; i++) {
											    table.deleteRow(tableHeaderRowCount);
											}
										}

										document.getElementById('WO_CODE').value = WO_CODEN;
									}
								</script>
								<div class="form-group" id="woreq_ref" style="display: none;">
									<label for="inputName" class="col-sm-2 control-label">Ref.</label>
									<div class="col-sm-9">
										<div class="input-group">
											<div class="input-group-btn">
												<button type="button" class="btn btn-primary"><?php echo $Search ?> </button>
											</div>
											<input type="hidden" class="form-control" name="FPA_NUM" id="FPA_NUM" style="max-width:160px" value="<?php echo $FPA_NUM; ?>" >
											<input type="text" class="form-control" name="FPA_CODE1" id="FPA_CODE1" value="<?php echo $FPA_CODE; ?>" onClick="pleaseCheck();" <?php if($WO_STAT != 1 && $WO_STAT != 6) { ?> disabled <?php } ?>>
										</div>
									</div>
								</div>
				                <script>
				                    var url1 = "<?php echo $url_selFPAMDR;?>";
				                    var url2 = "<?php echo $url_selFPASUB;?>";
				                    var url3 = "<?php echo $url_selFPASALT;?>";
				                    function pleaseCheck()
				                    {
				                        title = 'Select Item';
				                        w = 1000;
				                        h = 550;
				                        //window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
				                        var left = (screen.width/2)-(w/2);
				                        var top = (screen.height/2)-(h/2);

										WO_CATEG = document.getElementById('WO_CATEG').value;

										if(WO_CATEG == 'MDR')
										{
											return window.open(url1, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
										}
										else if(WO_CATEG == 'SUB')
										{
											return window.open(url2, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
										}
										else
										{
											return window.open(url3, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
										}
				                    }
				                </script>
				            	<div class="form-group" style="display:none">
				                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Type; ?></label>
				                    <div class="col-sm-9">
				                    	<select name="WO_TYPE" id="WO_TYPE" class="form-control" style="max-width:150px" >
				                          <option value="NSA" <?php if($WO_TYPE == 'NSA') { ?> selected <?php } ?>> Pekerjaan</option>
				                          <option value="SA" <?php if($WO_TYPE == 'SA') { ?> selected <?php } ?>> Sewa Alat</option>
				                        </select>
				                    </div>
				                </div>

				                <script>
									function getWO_NUM(selDate)
									{
										document.getElementById('WODate').value = selDate;
										document.getElementById('dateClass').click();
									}

									$(document).ready(function()
									{
										$(".tombol-date").click(function()
										{
											var add_PR	= "<?php echo $secGenCode; ?>";
											var formAction 	= $('#sendDate')[0].action;
											var data = $('.form-user').serialize();
											$.ajax({
												type: 'POST',
												url: formAction,
												data: data,
												success: function(response)
												{
													var myarr = response.split("~");
													document.getElementById('WO_NUMX').value = myarr[0];
													document.getElementById('WO_CODE').value = myarr[1];
												}
											});
										});
									});
								</script>
				            	<div class="form-group" style="display: none;">
				                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Project; ?></label>
				                    <div class="col-sm-9">
				                        <input type="hidden" class="form-control" style="max-width:350px;text-align:right" name="PRJCODE" id="PRJCODE" size="30" value="<?php echo $PRJCODE; ?>" />
				                    	<select name="PRJCODE" id="PRJCODE" class="form-control select2" onChange="chooseProject()" disabled>
				                          <option value="none">--- None ---</option>
				                          <?php echo $i = 0;
				                            if($countPRJ > 0)
				                            {
				                                foreach($vwPRJ as $row) :
				                                    $PRJCODE1 	= $row->PRJCODE;
				                                    $PRJNAME 	= $row->PRJNAME;
				                                    ?>
				                                  <option value="<?php echo $PRJCODE1; ?>" <?php if($PRJCODE1 == $PRJCODE) { ?> selected <?php } ?>><?php echo "$PRJCODE - $PRJNAME"; ?></option>
				                                  <?php
				                                endforeach;
				                            }
				                            else
				                            {
				                                ?>
				                                  <option value="none">--- No Unit Found ---</option>
				                              <?php
				                            }
				                            ?>
				                        </select>
				                    <input type="hidden" class="form-control" style="max-width:350px;text-align:right" id="PRJCODE" name="PRJCODE" size="20" value="<?php echo $PRJCODE; ?>" />
				                    </div>
				                </div>
				            	<div class="form-group" style="display: none;">
				                    <label for="inputName" class="col-sm-2 control-label"><?php echo $JobName; ?></label>
				                    <div class="col-sm-9">
				                        <select name="PR_REFNO[]" id="PR_REFNO" class="form-control select2" multiple="multiple" data-placeholder="&nbsp;&nbsp;&nbsp;<?php echo $JobName; ?>" onBlur="selJOB(this.value)">
				                        	<option value="A">--- None ---</option>
											<?php
				                                /*$Disabled_1	= 0;
				                                $sqlJob_1	= "SELECT JOBCODEID, JOBPARENT, JOBLEV, JOBDESC FROM tbl_joblist WHERE ISHEADER = '1' AND PRJCODE = '$PRJCODE'";
				                                $resJob_1	= $this->db->query($sqlJob_1)->result();
				                                foreach($resJob_1 as $row_1) :
				                                    $JOBCODEID_1	= $row_1->JOBCODEID;
				                                    $JOBPARENT_1	= $row_1->JOBPARENT;
				                                    $JOBLEV_1		= $row_1->JOBLEV;
				                                    $JOBDESC_1		= $row_1->JOBDESC;

				                                    if($JOBLEV_1 == 1)
													{
														$space_level_1	= "";
													}
													elseif($JOBLEV_1 == 2)
													{
														$space_level_1	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
													}
													elseif($JOBLEV_1 == 3)
													{
														$space_level_1	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
													}
													elseif($JOBLEV_1 == 4)
													{
														$space_level_1	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
													}
													elseif($JOBLEV_1 == 5)
													{
														$space_level_1	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
													}

													$JIDExplode = explode('~', $PR_REFNO);
													$JOBCODE1	= '';
													$SELECTED	= 0;
													foreach($JIDExplode as $i => $key)
													{
														$JOBCODE1	= $key;
														if($JOBCODEID_1 == $JOBCODE1)
														{
															$SELECTED	= 1;
														}
													}

				                                    $sqlC_2			= "tbl_joblist WHERE JOBPARENT = '$JOBCODEID_1' AND ISHEADER = '1' AND PRJCODE = '$PRJCODE'";
				                                    $resC_2 		= $this->db->count_all($sqlC_2);
				                                    if($resC_2 > 0)
				                                        $Disabled_1 = 1;
													else
														$Disabled_1 = 0;
				                                    ?>
				                                    <option value="<?php echo "$JOBCODEID_1"; ?>" <?php if($SELECTED == 1) { ?> selected <?php } if($Disabled_1 == 1) {?> disabled <?php } ?> title="<?php echo $JOBDESC_1; ?>">
				                                        <?php echo "$space_level_1 $JOBCODEID_1 : $JOBDESC_1"; ?>
				                                    </option>
				                                    <?php
				                                endforeach;*/
				                            ?>
				                        </select>
				                    </div>
				                </div>
								<script>
				                    function selJOB(PR_REFNO)
				                    {
				                        PR_REFNO1	= document.getElementById("PR_REFNO").value;
				                        document.getElementById("JOBCODE1").value = PR_REFNO;
				                        PRJCODE	= document.getElementById("PRJCODE").value
				                        document.getElementById("PRJCODE1").value = PRJCODE;
				                        document.frmsrch1.submitSrch1.click();
				                    }
				                </script>
		                        <div class="form-group">
		                       	  	<label for="inputName" class="col-sm-2 control-label"><?php echo $PaymentType ?> </label>
                                	<div class="col-sm-4">
                                		<!-- <input type="hidden" class="form-control" name="WO_PAYTYPE" id="WO_PAYTYPE" size="30" value="<?php echo $WO_PAYTYPE; ?>" /> -->
		                                <select name="WO_PAYTYPE" id="WO_PAYTYPE" class="form-control select2" onChange="selWO_PAYTYPE(this.value)">
		                                    <option value="0" <?php if($WO_PAYTYPE == 0) { ?> selected="selected" <?php } ?>>Cash</option>
		                                    <option value="1" <?php if($WO_PAYTYPE == 1) { ?> selected="selected" <?php } ?>>Credit</option>
		                                </select>
                                	</div>
                                	<div class="col-sm-6">
                                		<!-- <input type="hidden" class="form-control" name="WO_TENOR" id="WO_TENOR" size="30" value="<?php echo $WO_TENOR; ?>" /> -->
		                                <select name="WO_TENOR" id="WO_TENOR" class="form-control select2">
		                                    <option value="0" <?php if($WO_TENOR == 0) { ?> selected <?php } ?>>Cash</option>
		                                    <option value="7" <?php if($WO_TENOR == 7) { ?> selected <?php } ?>>7 Days</option>
		                                    <option value="14" <?php if($WO_TENOR == 14) { ?> selected <?php } ?>>15 Days</option>
		                                    <option value="21" <?php if($WO_TENOR == 21) { ?> selected <?php } ?>>21 Days</option>
		                                    <option value="30" <?php if($WO_TENOR == 30) { ?> selected <?php } ?>>30 Days</option>
		                                    <option value="45" <?php if($WO_TENOR == 45) { ?> selected <?php } ?>>45 Days</option>
		                                    <option value="60" <?php if($WO_TENOR == 60) { ?> selected <?php } ?>>60 Days</option>
		                                    <option value="101" <?php if($WO_TENOR == 101) { ?> selected <?php } ?>>Back to Back</option>
		                                    <option value="120" <?php if($WO_TENOR == 102) { ?> selected <?php } ?>>Turn Key</option>
		                                </select>
                                	</div>
		                        </div>
		                        <script>
									function selWO_PAYTYPE(theValue)
									{
										if(theValue == 1)
											$('#WO_TENOR').val(7).trigger('change');
										else
											$('#WO_TENOR').val(0).trigger('change');

										return false;
									}
									
									function selWO_TENOR(theValue)
									{
										if(theValue > 0)
											$('#WO_PAYTYPE').val(1).trigger('change');
										else
											$('#WO_PAYTYPE').val(0).trigger('change');

										return false;
									}
								</script>
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Notes; ?></label>
				                    <div class="col-sm-10">
				                    	<textarea name="WO_NOTE" class="form-control" id="WO_NOTE" cols="30" style="height: 85px" placeholder="Catatan SPK"><?php echo $WO_NOTE; ?></textarea>
				                    </div>
				                </div>
								<div class="form-group">
				                    <label for="inputName" class="col-sm-2 control-label"><?php echo "Cara Pembayaran"; ?></label>
				                    <div class="col-sm-10">
				                    	<textarea name="WO_PAYNOTE" class="form-control" id="WO_PAYNOTE" cols="30" style="height: 85px" placeholder="Cara Pembayaran"><?php echo $WO_PAYNOTE; ?></textarea>
				                    </div>
				                </div>
				            </div>
				        </div>
				    </div>

					<div class="col-md-6">
						<div class="box box-warning">
							<div class="box-header with-border" style="display: none;">
								<i class="fa fa-cloud-upload"></i>
								<h3 class="box-title">&nbsp;</h3>
							</div>
							<div class="box-body">
								<div class="form-group">
									<label for="inputName" class="col-sm-3 control-label">No. Kontrak</label>
									<div class="col-sm-9">
										<input type="text" class="form-control" name="WO_REFNO" id="WO_REFNO" value="<?php echo $WO_REFNO; ?>" placeholder="Nomor Kontrak" >
									</div>
								</div>
								<div class="form-group">
									<label for="inputName" class="col-sm-3 control-label"><?php echo $QuotNo; ?></label>
									<div class="col-sm-9">
										<input type="text" class="form-control" name="WO_QUOT" id="WO_QUOT" value="<?php echo $WO_QUOT; ?>" placeholder="<?php echo $QuotNo; ?>" >
									</div>
								</div>
								<div class="form-group" style="display: none;">
									<label for="inputName" class="col-sm-3 control-label"><?php echo $NegotNo; ?></label>
									<div class="col-sm-9">
										<input type="text" class="form-control" name="WO_NEGO" id="WO_NEGO" value="<?php echo $WO_NEGO; ?>" placeholder="<?php echo $NegotNo; ?>">
									</div>
								</div>
								<div class="form-group">
									<label for="inputName" class="col-sm-3 control-label">DP %</label>
									<div class="col-sm-4">
			                            <input type="hidden" class="form-control" style="text-align:right;" name="WO_DPPER" id="WO_DPPER" value="<?php echo $WO_DPPER; ?>">
			                            <input type="text" class="form-control" style="text-align:right;" name="WO_DPPERX" id="WO_DPPERX" value="<?php echo number_format($WO_DPPER, $decFormat); ?>" onBlur="getDPer(this)">
				                    </div>
				                    <div class="col-sm-5">
			                            <input type="hidden" class="form-control" style="text-align:right;" name="WO_DPREF" id="WO_DPREF" value="<?php echo $WO_DPREF; ?>" onClick="getDPREF();">
			                            <input type="hidden" class="form-control" name="WO_DPREF1" id="WO_DPREF1" value="<?php echo $WO_DPREF1; ?>" data-placeholder="Kode DP" onClick="getDPREF();">
			                            <input type="hidden" class="form-control" style="text-align:right;" name="WO_DPVAL" id="WO_DPVAL" value="<?php echo $WO_DPVAL; ?>">
			                            <input type="text" class="form-control" style="text-align:right;" name="WO_DPVALX" id="WO_DPVALX" value="<?php echo number_format($WO_DPVAL, 2); ?>" readonly>
									</div>
								</div>
				                <script>
									function getDPer(thisVal1)
									{
										var decFormat	= document.getElementById('decFormat').value;

										thisVal 		= parseFloat(Math.abs(eval(thisVal1).value.split(",").join("")));

										document.getElementById('WO_DPPER').value	= thisVal;
										document.getElementById('WO_DPPERX').value	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(thisVal)),decFormat));

										WO_GTOTAL 		= parseFloat(document.getElementById('WO_GTOTAL').value);
										WO_DPVAL		= parseFloat(thisVal * WO_GTOTAL) / 100;
										document.getElementById('WO_DPVAL').value	= RoundNDecimal(parseFloat(Math.abs(WO_DPVAL)),2);
										document.getElementById('WO_DPVALX').value	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(WO_DPVAL)),2));
									}
								</script>

								<?php

				                    $url_popdp	= site_url('c_project/c_s180d0bpk/ll_4p/?id=');
				                ?>
				                <script>
				                    var urlDP = "<?php echo "$url_popdp";?>";
				                    function getDPREF()
				                    {
										PRJCODE	= document.getElementById("PRJCODE").value;
										SPLCODE	= document.getElementById("SPLCODE").value;
										if(SPLCODE == '')
										{
											swal('Silahkan pilih suplier',
											{
												icon:"warning",
											})
											.then(function()
											{
												document.getElementById('SPLCODE').focus();
											});
											return false;
										}
				                        title = 'Select Item';
				                        w = 850;
				                        h = 550;

				                        var left = (screen.width/2)-(w/2);
				                        var top = (screen.height/2)-(h/2);
										return window.open(urlDP+PRJCODE+'&SPLCODE='+SPLCODE, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
				                    }
				                </script>
				                <div class="form-group">
									<label for="inputName" class="col-sm-3 control-label">Retensi (%)</label>
									<div class="col-sm-4">
			                            <input type="hidden" class="form-control" style="text-align:right;" name="WO_RETP" id="WO_RETP" value="<?php echo $WO_RETP; ?>">
			                            <input type="text" class="form-control" style="text-align:right;" name="WO_RETPX" id="WO_RETPX" value="<?php echo number_format($WO_RETP, $decFormat); ?>" onBlur="getRETP(this)" onKeyPress="return isIntOnlyNew(event);">
				                    </div>
				                    <div class="col-sm-5">
			                            <input type="hidden" class="form-control" style="text-align:right;" name="WO_RETVAL" id="WO_RETVAL" value="<?php echo $WO_RETVAL; ?>">
			                            <input type="text" class="form-control" style="text-align:right;" name="WO_RETVALX" id="WO_RETVALX" value="<?php echo number_format($WO_RETVAL, 2); ?>" readonly>
									</div>
								</div>
								<script>
									function getRETP(thisValR)
									{
										var decFormat	= document.getElementById('decFormat').value;

										thisVal 		= parseFloat(Math.abs(eval(thisValR).value.split(",").join("")));

										document.getElementById('WO_RETP').value	= thisVal;
										document.getElementById('WO_RETPX').value	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(thisVal)),decFormat));

										WO_GTOTAL 		= parseFloat(document.getElementById('WO_GTOTAL').value);
										WO_RETVAL		= parseFloat(thisVal * WO_GTOTAL) / 100;
										document.getElementById('WO_RETVAL').value	= RoundNDecimal(parseFloat(Math.abs(WO_RETVAL)),2);
										document.getElementById('WO_RETVALX').value	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(WO_RETVAL)),2));
									}
								</script>
				            	<div class="form-group" id="tblReason" style="display: none;">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Reason; ?></label>
				                    <div class="col-sm-9">
				                    	<textarea name="WO_MEMO" class="form-control" style="max-width:350px;" id="WO_MEMO" cols="30"><?php echo $WO_MEMO; ?></textarea>
				                    </div>
				                </div>
								<div class="form-group">
									<label for="inputName" class="col-sm-3 control-label">Total SPK / PPn</label>
									<div class="col-sm-4">
										<input type="hidden" name="WO_VALUE" id="WO_VALUE" class="form-control" style="max-width:150px; text-align:right" value="<?php echo $WO_VALUE; ?>">
		                    			<input type="text" name="WO_VALUEX" id="WO_VALUEX" class="form-control" style="text-align:right" value="<?php echo number_format($WO_VALUE, 2); ?>" disabled>
									</div>
									<div class="col-sm-5">
										<input type="hidden" name="WO_VALPPN" id="WO_VALPPN" class="form-control" style="max-width:150px; text-align:right" value="<?php echo $WO_VALPPN; ?>">
		                    			<input type="text" name="WO_VALPPNX" id="WO_VALPPNX" class="form-control" style="text-align:right" value="<?php echo number_format($WO_VALPPN, 2); ?>" disabled>
									</div>
								</div>
								<div class="form-group">
									<label for="inputName" class="col-sm-3 control-label">PPh / G. Total</label>
									<div class="col-sm-4">
										<input type="hidden" name="WO_VALPPH" id="WO_VALPPH" class="form-control" style="max-width:150px; text-align:right" value="<?php echo $WO_VALPPH; ?>">
		                    			<input type="text" name="WO_VALPPHX" id="WO_VALPPHX" class="form-control" style="text-align:right" value="<?php echo number_format($WO_VALPPH, 2); ?>" disabled>
									</div>
									<div class="col-sm-5">
										<input type="hidden" name="WO_GTOTAL" id="WO_GTOTAL" class="form-control" style="max-width:150px; text-align:right" value="<?php echo $WO_GTOTAL; ?>">
		                    			<input type="text" name="WO_GTOTALX" id="WO_GTOTALX" class="form-control" style="text-align:right" value="<?php echo number_format($WO_GTOTAL, 2); ?>" disabled>
									</div>
								</div>
								<div class="form-group">
									<label for="inputName" class="col-sm-3 control-label"><?php echo "Cat. Persetujuan"; ?></label>
									<div class="col-sm-9">
				                    	<textarea name="WO_NOTE2" class="form-control" style="height: 85px" id="WO_NOTE2" cols="30" placeholder="<?php echo $ApproverNotes; ?>" readonly><?php echo $WO_NOTE2; ?></textarea>                        
				                    </div>
								</div>
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Status; ?></label>
				                    <div class="col-sm-6">
				                    	<input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $WO_STAT; ?>">
				                        <select name="WO_STAT" id="WO_STAT" class="form-control select2" onChange="selStat(this.value)">
										<?php
											$isDisabled	= 0;
											if($WO_STAT == 6 || $WO_STAT == 7)
											{
												$isDisabled	= 1;
											}

											$disableBtn	= 0;
											if($WO_STAT == 5 || $WO_STAT == 6 || $WO_STAT == 9)
											{
												$disableBtn	= 1;
											}
											elseif($WO_STAT == 3 && $ISDELETE == 1)
											{
											    $disableBtn	= 0;
											}
											if($WO_STAT != 1 AND $WO_STAT != 4)
											{
												?>
													<option value="1"<?php if($WO_STAT == 1) { ?> selected <?php } ?> disabled>New</option>
													<option value="2"<?php if($WO_STAT == 2) { ?> selected <?php } ?> disabled>Confirm</option>
													<option value="3"<?php if($WO_STAT == 3) { ?> selected <?php } ?> disabled>Approve</option>
													<option value="4"<?php if($WO_STAT == 4) { ?> selected <?php } ?> disabled>Revising</option>
													<option value="5"<?php if($WO_STAT == 5) { ?> selected <?php } ?> disabled>Rejected</option>
													<option value="6"<?php if($WO_STAT == 6) { ?> selected <?php } if($disableBtn == 1) {?> disabled <?php } ?>>Closed</option>
													<option value="7"<?php if($WO_STAT == 7) { ?> selected <?php } ?> disabled>Waiting</option>
													<option value="9"<?php if($WO_STAT == 9) { ?> selected <?php } if($disableBtn == 1) {?> disabled <?php } ?>>Void</option>
												<?php
											}
											else
											{
												?>
													<option value="1"<?php if($WO_STAT == 1) { ?> selected <?php } ?>>New</option>
													<option value="2"<?php if($WO_STAT == 2) { ?> selected <?php } ?>>Confirm</option>
												<?php
											}
											$theProjCode 	= $PRJCODE;
											$url_AddItem	= site_url('c_project/c_spk/popupallitem/?id='.$this->url_encryption_helper->encode_url($theProjCode));
											$theProjCode 	= "$PRJCODE~$PR_REFNO";
				                        	$url_AddItem	= site_url('c_project/c_s180d0bpk/popupallitem/?id=');
				                        ?>
				                        </select>
				                    </div>
		                          	<?php if($WO_STAT == 1 || $WO_STAT == 4) { ?>
					                    <div class="col-sm-3">
					                        <div class="pull-right">
						                        <!-- <button class="btn btn-success" type="button" onClick="selectitem();">
						                        	<i class="glyphicon glyphicon-th-list"></i>&nbsp;&nbsp;<?php echo $SelectItem; ?>
						                        </button> -->
					                        	<a class="btn btn-sm btn-warning" name="btnMdl" id="btnMdl" data-toggle="modal" data-target="#mdl_addItm" id="btnModal">
					                        		<i class="glyphicon glyphicon-search"></i>&nbsp;&nbsp;<?php echo $SelectItem; ?>
					                        	</a>
					                        </div>
					                   	</div>
		                          	<?php } ?>
				                </div>
				                <script>
									function selStat(thisValue)
									{
										if(thisValue == 6)
										{
											document.getElementById('tblClose').style.display = '';
											document.getElementById('tblReason').style.display = '';
										}
										else if(thisValue == 9)
										{
											var isUSED	= document.getElementById('isUSED').value;
											if(isUSED > 0)
											{
												swal('<?php echo $alertVOID; ?>'+' <?php echo $DOC_NO; ?>',
												{
													icon:"warning",
												});
												return false;
											}
											else
											{
												document.getElementById('tblClose').style.display = '';
												document.getElementById('tblReason').style.display = '';
											}
										}
										else if(thisValue == 3)
										{
											document.getElementById('tblClose').style.display = 'none';
											document.getElementById('tblReason').style.display = 'none';
										}
									}
								</script>
				                <div class="form-group" style="display:none">
				                    <label for="inputName" class="col-sm-3 control-label">&nbsp;</label>
				                    <div class="col-sm-9">
				                        <script>
				                            var url = "<?php echo $url_AddItem;?>";

				                            function selectitem()
				                            {
												WO_CATEG	= document.getElementById("WO_CATEG").value;
												FPA_CODE	= document.getElementById("FPA_CODE1").value;
												if(WO_CATEG == 'SALT' && FPA_CODE == '')
												{
													swal('<?php echo $alert5; ?>',
													{
														icon:"warning",
													})
													.then(function()
													{
														document.getElementById('FPA_CODE1').focus();
													});
													return false;
												}

												SPLCODE		= document.getElementById('SPLCODE').value;
												if(SPLCODE == '0')
												{
													swal('<?php echo $alert4; ?>',
													{
														icon:"warning",
													})
													.then(function()
													{
														document.getElementById('SPLCODE').focus();
													});
													return false;
												}
												document.getElementById('btnMdl').click();
												/*PR_REFNO 	= '';
												PRJCODE		= document.getElementById('PRJCODE').value;

				                                title = 'Select Item';
				                                w = 1000;
				                                h = 550;
				                                //window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
				                                var left = (screen.width/2)-(w/2);
				                                var top = (screen.height/2)-(h/2);
				                                return window.open(url+PR_REFNO+'&pr1h0ec0JcoDe='+PRJCODE+'&pgfrm='+WO_CATEG, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);*/
				                            }
				                        </script>
				                        <?php if($WO_STAT == 1 || $WO_STAT == 4) { ?>
					                        <button class="btn btn-success" type="button" onClick="selectitem();">
					                        <i class="glyphicon glyphicon-th-list"></i>&nbsp;&nbsp;<?php echo $SelectItem; ?>
					                        </button>
					                    <?php } ?>
									</div>
				                </div>
							</div>
						</div>
					</div>

                    <div class="col-md-12">
                        <div class="box box-primary">
	                        <div class="search-table-outter">
	                            <table id="tbl" class="table table-bordered table-striped" width="100%">
		                        	<tr style="background:#CCCCCC">
			                            <th width="2%" style="text-align:left; vertical-align: middle;">&nbsp;</th>
			                            <th width="15%" style="text-align:center; vertical-align: middle;"><?php echo $ItemName ?> </th>
			                            <th width="5%" style="text-align:center; vertical-align: middle;"><?php echo $Planning; ?> </th>
			                            <th width="5%" style="text-align:center; vertical-align: middle;"><?php echo $Requested; ?> </th>
			                            <th width="5%" style="text-align:center; vertical-align: middle;"><?php echo "Volume"; ?></th>
			                            <th width="8%" style="text-align:center; vertical-align: middle;"><?php echo $Price; ?></th>
			                            <th width="5%" style="text-align:center; vertical-align: middle;"><?php echo $Unit ?> </th>
			                            <th width="9%" style="text-align:center; vertical-align: middle;"><?php echo $Amount ?></th>
										<th style="text-align:center;display: none;">Discount (%)</th>
										<th style="text-align:center;display: none;">Discount Price</th>
										<th style="text-align:center; vertical-align: middle;" nowrap><?php echo "% PPn"; ?></th>
			                            <th width="5%" style="text-align:center; vertical-align: middle;">
			                            	<?php //echo "$Tax PPN<br>"; ?>
			                            	<select name="TAXCODE1H" id="TAXCODE1H" class="form-control" onChange="setAllPPN(this.value);">
	                                        	<option value=""> --- </option>
	                                        	<?php
	                                        		$s_01 	= "SELECT TAXLA_NUM, TAXLA_DESC FROM tbl_tax_ppn";
	                                        		$r_01 	= $this->db->query($s_01)->result();
	                                        		foreach($r_01 as $rw_01):
	                                        			$PPN_NUM 	= $rw_01->TAXLA_NUM;
	                                        			$PPN_DESC = $rw_01->TAXLA_DESC;
	                                        			?>
	                                        				<option value="<?=$PPN_NUM?>"><?=$PPN_DESC?></option>
	                                        			<?php
	                                        		endforeach;
	                                        	?>
	                                        </select>
			                            </th>
										<th style="text-align:center; vertical-align: middle;" nowrap><?php echo "% PPh"; ?></th>
			                            <th width="5%" style="text-align:center; vertical-align: middle;">
			                            	<?php //echo "$Tax PPh<br>"; ?>
			                            	<select name="TAXCODE2H" id="TAXCODE2H" class="form-control" onChange="setAllPPH(this.value);">
	                                        	<option value=""> --- </option>
	                                        	<?php
	                                        		$s_01 	= "SELECT TAXLA_NUM, TAXLA_DESC FROM tbl_tax_la";
	                                        		$r_01 	= $this->db->query($s_01)->result();
	                                        		foreach($r_01 as $rw_01):
	                                        			$PPH_NUM 	= $rw_01->TAXLA_NUM;
	                                        			$PPH_DESC = $rw_01->TAXLA_DESC;
	                                        			?>
	                                        				<option value="<?=$PPH_NUM?>"><?=$PPH_DESC?></option>
	                                        			<?php
	                                        		endforeach;
	                                        	?>
	                                        </select>
			                            </th>
			                            <th width="7%" style="text-align:center; vertical-align: middle;">Total</th>
			                            <th width="10%" style="text-align:center; vertical-align: middle;">Des.</th>
		                          	</tr>
		                            <tr style="background:#CCCCCC">
		                            </tr>
		                            <?php
		                            if($task == 'edit')
		                            {
		                                /*$sqlDET	= "SELECT A.*,
														B.ITM_NAME,
														C.JOBDESC, C.JOBPARENT
													FROM tbl_wo_detail A
														INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
															AND B.PRJCODE = '$PRJCODE'
														INNER JOIN tbl_joblist_detail C ON A.JOBCODEID = C.JOBCODEID
															AND C.PRJCODE = '$PRJCODE'
															AND A.ITM_CODE = C.ITM_CODE
														LEFT JOIN tbl_wo_header D ON D.WO_NUM = A.WO_NUM
														    AND D.JOBCODEID = C.JOBPARENT
													WHERE A.WO_NUM = '$WO_NUM'
														AND B.PRJCODE = '$PRJCODE'";*/
		                                $sqlDET	= "SELECT A.*,
														C.JOBDESC, C.JOBPARENT, C.ITM_LASTP
													FROM tbl_wo_detail A
														INNER JOIN tbl_joblist_detail C ON A.JOBCODEID = C.JOBCODEID
															AND C.PRJCODE = '$PRJCODE'
															AND A.ITM_CODE = C.ITM_CODE
														LEFT JOIN tbl_wo_header D ON D.WO_NUM = A.WO_NUM
														    AND D.JOBCODEID = C.JOBPARENT
													WHERE A.WO_NUM = '$WO_NUM' AND A.PRJCODE = '$PRJCODE'";
		                                $result = $this->db->query($sqlDET)->result();
		                                $i		= 0;
		                                $j		= 0;

										foreach($result as $row) :
											$currentRow  	= ++$i;
											$WO_ID 			= $row->WO_ID;
											$WO_NUM 		= $row->WO_NUM;
											$WO_CODE 		= $row->WO_CODE;
											$WO_DATE 		= $row->WO_DATE;
											$PRJCODE 		= $row->PRJCODE;
											$JOBCODEDET		= $row->JOBCODEDET;
											$JOBCODEID 		= $row->JOBCODEID;
											$ITM_CODE 		= $row->ITM_CODE;
											$ITM_NAME 		= $row->JOBDESC;
											$SNCODE 		= $row->SNCODE;
											$ITM_UNIT 		= $row->ITM_UNIT;
											$WO_VOLM 		= $row->WO_VOLM;
											$ITM_LASTP 		= $row->ITM_LASTP;
											$ITM_PRICE 		= $row->ITM_PRICE;
											$WO_DISC		= $row->WO_DISC;
											$WO_DISCP		= $row->WO_DISCP;
											$WO_TOTAL 		= $row->WO_TOTAL;
											$WO_CVOL 		= $row->WO_CVOL;
											$WO_CAMN 		= $row->WO_CAMN;
											$WO_DESC 		= $row->WO_DESC;
											$TAXCODE1		= $row->TAXCODE1;
											$TAXPERC1		= $row->TAXPERC1;
											$TAXPRICE1		= $row->TAXPRICE1;
											$TAXCODE2		= $row->TAXCODE2;
											$TAXPERC2		= $row->TAXPERC2;
											$TAXPRICE2		= $row->TAXPRICE2;
											$WO_TOTAL2		= $row->WO_TOTAL2;
											$ITM_BUDG_VOL	= $row->ITM_BUDG_VOL;
											$ITM_BUDG_AMN	= $row->ITM_BUDG_AMN;
											$itemConvertion	= 1;

											$WO_TOTAL2 		= $WO_TOTAL + $TAXPRICE1 - $TAXPRICE2;

											$OPN_VOLM 		= $row->OPN_VOLM;
											$REM_VOLWO 		= $WO_VOLM - $OPN_VOLM;

											$UNITTYPE		= strtoupper($ITM_UNIT);
											if($UNITTYPE == 'LS' )
												$ITM_BUDQTY	= $ITM_BUDG_AMN;
											else
												$ITM_BUDQTY 	= $ITM_BUDG_VOL;

											$JOBPARENT		= $row->JOBPARENT;
											$JOBDESCH		= "";
											$sqlJOBDESC		= "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JOBPARENT' LIMIT 1";
											$resJOBDESC		= $this->db->query($sqlJOBDESC)->result();
											foreach($resJOBDESC as $rowJOBDESC) :
												$JOBDESCH	= $rowJOBDESC->JOBDESC;
											endforeach;

											/*if ($j==1) {
												echo "<tr class=zebra1>";
												$j++;
											} else {
												echo "<tr class=zebra2>";
												$j--;
											}*/
											?>
		                                    <tr id="tr_<?php echo $currentRow; ?>" style="vertical-align: middle;">
												<td height="25" style="text-align:center; vertical-align: middle;">
												  	<?php
														if($WO_STAT == 1)
														{
															?>
															<a href="#" onClick="deleteRow(<?php echo $currentRow; ?>)" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a>
															<?php
														}
			                                            else
			                                            {
			                                                //echo "$currentRow.";
			                                                if($WO_STAT == 3 && $WO_STAT > 0)
			                                                {
			                                                	$secDelD 	= base_url().'index.php/c_project/c_s180d0bpk/cancelItem/?id=';
																$canclRow 	= "$secDelD~$WO_NUM~$PRJCODE~$ITM_CODE~$ITM_NAME~$JOBDESCH~$WO_VOLM~$OPN_VOLM~$REM_VOLWO~$ITM_UNIT~$WO_ID";
			                                                	?>
			                                                		<input type="hidden" name="urldelD<?php echo $currentRow; ?>" id="urldelD<?php echo $currentRow; ?>" value="<?php echo $canclRow; ?>">
			                                                		<a onClick="cancelRow(<?php echo $currentRow; ?>)" title="Batalkan Volume SPK" class="btn btn-danger btn-xs"><i class="fa fa-repeat"></i></a>
			                                                	<?php
			                                                }
			                                                else
			                                                {
			                                                	echo "$currentRow.";
			                                                }
			                                            }
												  	?>
											    	<input type="hidden" id="chk" name="chk" value="<?php echo $currentRow; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">
											    	<input type="hidden" id="data<?php echo $currentRow; ?>WO_NUM" name="data[<?php echo $currentRow; ?>][WO_NUM]" value="<?php echo $WO_NUM;?>" class="form-control">
											    	<input type="hidden" id="data<?php echo $currentRow; ?>WO_CODE" name="data[<?php echo $currentRow; ?>][WO_CODE]" value="<?php echo $WO_CODE;?>" class="form-control">
											    	<input type="hidden" name="data[<?php echo $currentRow; ?>][JOBCODEDET]" id="data<?php echo $currentRow; ?>JOBCODEDET" value="<?php echo $JOBCODEDET;?>" class="form-control" >
											    	<input type="hidden" name="data[<?php echo $currentRow; ?>][JOBCODEID]" id="data<?php echo $currentRow; ?>JOBCODEID" value="<?php echo $JOBCODEID;?>" class="form-control" >
			                                    	<!-- Checkbox -->
			                                 	</td>
											  	<td style="text-align:left; min-width:100px; vertical-align: middle;" nowrap>
													<div>
												  		<span><?php echo "$JOBCODEID - $ITM_NAME"; ?></span>
												  		<span class="text-red" style="font-style: italic; font-weight: bold;">&nbsp;(LastP : <?=number_format($ITM_LASTP,2);?> )</span>
												  	</div>
												  	<div style="margin-left: 15px; font-style: italic;">
												  		<i class="text-muted fa fa-rss"></i>&nbsp;&nbsp;
												  		<?php
												  			$JOBDS 	= strlen($JOBDESCH);
												  			if($JOBDS > 50)
												  			{
												  				echo cut_text ($JOBDESCH, 45);
												  				echo " ...";
												  			}
												  			else
												  			{
												  				echo $JOBDESCH;
												  			}
												  		?>
												  	</div>
			                                      	<input type="hidden" name="data[<?php echo $currentRow; ?>][WO_NUM]" id="data<?php echo $currentRow; ?>WO_NUM" value="<?php echo $WO_NUM; ?>" class="form-control" style="max-width:300px;">
			                                      	<input type="hidden" name="data[<?php echo $currentRow; ?>][WO_CODE]" id="data<?php echo $currentRow; ?>WO_CODE" value="<?php echo $WO_CODE; ?>" class="form-control" style="max-width:300px;">
			                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][JOBCODEDET]" id="data<?php echo $currentRow; ?>JOBCODEDET" value="<?php echo $JOBCODEDET; ?>" class="form-control" >
			                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][JOBCODEID]" id="data<?php echo $currentRow; ?>JOBCODEID" value="<?php echo $JOBCODEID; ?>" class="form-control" >

			                                      	<input type="hidden" id="data<?php echo $currentRow; ?>ITM_CODE" name="data[<?php echo $currentRow; ?>][ITM_CODE]" value="<?php echo $ITM_CODE; ?>" class="form-control" style="max-width:300px;">
			                                      	<input type="hidden" id="data<?php echo $currentRow; ?>SNCODE" name="data[<?php echo $currentRow; ?>][SNCODE]" value="<?php echo $SNCODE; ?>" class="form-control" style="max-width:300px;">
			                                        <!-- Item Name -->
			                                 	</td>
												<?php
													// CARI TOTAL WORKED BUDGET APPROVED
														$WO_QTY2		= 0;
														$WO_AMOUNT2		= 0;
														/*$sqlTOTBUDG		= "SELECT SUM(A.WO_VOLM * A.ITM_PRICE) AS TOTWOAMOUNT,
																				SUM(A.WO_VOLM) AS TOTWOQTY
																			FROM tbl_wo_detail A
																				INNER JOIN tbl_wo_header B ON A.WO_NUM = B.WO_NUM
																					AND B.PRJCODE = '$PRJCODE'
																			WHERE A.ITM_CODE = '$ITM_CODE' AND A.PRJCODE = '$PRJCODE'
																				AND A.JOBCODEID = '$JOBCODEID' AND B.WO_STAT IN (3,6)
																				AND A.WO_NUM != '$WO_NUM'";*/
														$sqlTOTBUDG		= "SELECT SUM(A.WO_VOLM) AS TOTWOQTY,
																				SUM(A.WO_VOLM * A.ITM_PRICE) AS TOTWOAMOUNT
																			FROM tbl_wo_detail A
																				INNER JOIN tbl_wo_header B ON A.WO_NUM = B.WO_NUM
																					AND B.PRJCODE = '$PRJCODE'
																			WHERE A.ITM_CODE = '$ITM_CODE' AND A.PRJCODE = '$PRJCODE'
																				AND A.JOBCODEID = '$JOBCODEID' AND B.WO_STAT IN (2,3,6)
																				AND A.WO_NUM != '$WO_NUM'";
														$resTOTBUDG		= $this->db->query($sqlTOTBUDG)->result();
														foreach($resTOTBUDG as $rowTOTBUDG) :
															$WO_QTY2	= $rowTOTBUDG->TOTWOQTY;
															$WO_AMOUNT2	= $rowTOTBUDG->TOTWOAMOUNT;
														endforeach;
														$ITM_STOCK 		= $ITM_BUDG_VOL - $WO_QTY2;
														$ITM_STOCK_AMN 	= $ITM_BUDG_AMN - $WO_AMOUNT2;
														
														if($UNITTYPE == 'LS' )
															$TOTPRQTY	= $WO_AMOUNT2;
														else
															$TOTPRQTY 	= $WO_QTY2;

													// PEMBATALAN VOLUME
			                                            $WO_CVOLV 		= "";
														if($WO_CVOL > 0)
														{
															$WO_CVOLV 	= 	"<div style='white-space:nowrap;'>
																				<span class='text-red' style='white-space:nowrap;'><i class='glyphicon glyphicon-chevron-down'></i>
																		  		".number_format($WO_CVOL, 2)."</span>
																		  	</div>";
														}

													$disRow 		= 1;
													if($WO_STAT == 1 || $WO_STAT == 4)
													{
														$disRow 	= 0;
													}
												?>
												<td style="text-align:right; vertical-align: middle;"> <!-- Item Bdget -->
			                                    	<?php echo number_format($ITM_BUDQTY, 2); ?>
			                                        <input type="hidden" id="data<?php echo $currentRow; ?>ITM_BUDG_VOL" name="data[<?php echo $currentRow; ?>][ITM_BUDG_VOL]" value="<?php echo $ITM_BUDG_VOL; ?>">
			                                        <input type="hidden" id="data<?php echo $currentRow; ?>ITM_BUDG_AMN" name="data[<?php echo $currentRow; ?>][ITM_BUDG_AMN]" value="<?php echo $ITM_BUDG_AMN; ?>">
			                                        <input type="hidden" name="ITM_STOCK<?php echo $currentRow; ?>" id="ITM_STOCK<?php echo $currentRow; ?>" value="<?php echo $ITM_STOCK; ?>" class="form-control" style="text-align:right" >
			                                        <input type="hidden" name="ITM_STOCK_AMN<?php echo $currentRow; ?>" id="ITM_STOCK_AMN<?php echo $currentRow; ?>" value="<?php echo $ITM_STOCK_AMN; ?>" class="form-control" style="text-align:right" >
			                                  	</td>
											  	<td style="text-align:right; vertical-align: middle;">  <!-- Item Requested FOR INFORMATION ONLY -->
			                                    	<?php print number_format($TOTPRQTY, $decFormat); ?>
			                                        <input type="hidden" class="form-control" style="text-align:right" name="TOT_USEDQTY<?php echo $currentRow; ?>" id="TOT_USEDQTY<?php echo $currentRow; ?>" value="<?php echo $TOTPRQTY; ?>" >
			                                 	</td>
			                                    <td style="text-align:right; vertical-align: middle;" nowrap> <!-- Item Request Now -- PR_VOLM -->
			                                        <?php
														/*if($ITM_UNIT == 'LS' || $ITM_UNIT == 'LUMP')
														{
															if($disRow == 1) 
															{
																echo number_format($WO_VOLM, $decFormat);
																?>
					                                        	<input type="hidden" name="WO_VOLM<?php echo $currentRow; ?>" id="WO_VOLM<?php echo $currentRow; ?>" value="1.00" class="form-control" style="min-width:100px; max-width:100px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getWOVol(this,<?php echo $currentRow; ?>);" readonly >
																<input type="hidden" name="data[<?php echo $currentRow; ?>][WO_VOLM]" id="data<?php echo $currentRow; ?>WO_VOLM" value="1" class="form-control" style="max-width:300px;" >
						                                    <?php } else { ?>
					                                        	<input type="text" name="WO_VOLM<?php echo $currentRow; ?>" id="WO_VOLM<?php echo $currentRow; ?>" value="1.00" class="form-control" style="min-width:100px; max-width:100px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getWOVol(this,<?php echo $currentRow; ?>);" readonly >
																<input type="hidden" name="data[<?php echo $currentRow; ?>][WO_VOLM]" id="data<?php echo $currentRow; ?>WO_VOLM" value="1" class="form-control" style="max-width:300px;" >
							                                <?php
							                                }
														}
														else
														{*/
															if($disRow == 1) 
															{
																echo number_format($WO_VOLM, $decFormat);
																?>
					                                        	<input type="hidden" name="WO_VOLM<?php echo $currentRow; ?>" id="WO_VOLM<?php echo $currentRow; ?>" value="<?php echo number_format($WO_VOLM, 2); ?>" class="form-control" style="min-width:100px; max-width:100px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getWOVol(this,<?php echo $currentRow; ?>);" >
																<input type="hidden" name="data[<?php echo $currentRow; ?>][WO_VOLM]" id="data<?php echo $currentRow; ?>WO_VOLM" value="<?php echo $WO_VOLM; ?>" class="form-control" style="max-width:300px;" >
						                                    <?php } else { ?>
					                                        	<input type="text" name="WO_VOLM<?php echo $currentRow; ?>" id="WO_VOLM<?php echo $currentRow; ?>" value="<?php echo number_format($WO_VOLM, 2); ?>" class="form-control" style="min-width:100px; max-width:100px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getWOVol(this,<?php echo $currentRow; ?>);" >
																<input type="hidden" name="data[<?php echo $currentRow; ?>][WO_VOLM]" id="data<?php echo $currentRow; ?>WO_VOLM" value="<?php echo $WO_VOLM; ?>" class="form-control" style="max-width:300px;" >
							                                    <?php
							                                }
														//}
			                                        ?>
		                                      		<?=$WO_CVOLV?>
													<input type="hidden" name="ITM_REM_VOL<?php echo $currentRow; ?>" id="ITM_REM_VOL<?php echo $currentRow; ?>" value="<?php echo $ITM_STOCK; ?>" class="form-control" style="text-align:right" >
													<input type="hidden" name="ITM_REM_AMN<?php echo $currentRow; ?>" id="ITM_REM_AMN<?php echo $currentRow; ?>" value="<?php echo $ITM_STOCK_AMN; ?>" class="form-control" style="text-align:right" >
			                                    </td>
			                                    <td style="text-align:right; vertical-align: middle;" nowrap>
			                                        <?php
														if($ITM_UNIT == 'LS' || $ITM_UNIT == 'LUMP')
														{
															if($disRow == 1) 
															{
																echo number_format($ITM_PRICE, $decFormat);
																?>
						                                        	<input type="hidden" name="ITM_PRICE<?php echo $currentRow; ?>" id="ITM_PRICE<?php echo $currentRow; ?>" value="<?php echo number_format($ITM_PRICE, 2); ?>" class="form-control" style="min-width:100px; max-width:100px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getWOPrice(this,<?php echo $currentRow; ?>);">
						                                        	<input type="hidden" name="data[<?php echo $currentRow; ?>][ITM_PRICE]" id="data<?php echo $currentRow; ?>ITM_PRICE" value="<?php echo $ITM_PRICE; ?>" class="form-control" style="min-width:100px; max-width:100px;" >
						                                        	<input type="hidden" style="text-align:right" name="itemConvertion<?php echo $currentRow; ?>" id="itemConvertion<?php echo $currentRow; ?>" value="<?php echo $itemConvertion; ?>" >
							                                    <?php } else { ?>
						                                        	<input type="text" name="ITM_PRICE<?php echo $currentRow; ?>" id="ITM_PRICE<?php echo $currentRow; ?>" value="<?php echo number_format($ITM_PRICE, 2); ?>" class="form-control" style="min-width:100px; max-width:100px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getWOPrice(this,<?php echo $currentRow; ?>);">
						                                        	<input type="hidden" name="data[<?php echo $currentRow; ?>][ITM_PRICE]" id="data<?php echo $currentRow; ?>ITM_PRICE" value="<?php echo $ITM_PRICE; ?>" class="form-control" style="min-width:100px; max-width:100px;" >
						                                        	<input type="hidden" style="text-align:right" name="itemConvertion<?php echo $currentRow; ?>" id="itemConvertion<?php echo $currentRow; ?>" value="<?php echo $itemConvertion; ?>" >
							                                    <?php
							                                }
														}
														else
														{
															if($disRow == 1) 
															{
																echo number_format($ITM_PRICE, $decFormat);
																?>
						                                        	<input type="hidden" name="ITM_PRICE<?php echo $currentRow; ?>" id="ITM_PRICE<?php echo $currentRow; ?>" value="<?php echo number_format($ITM_PRICE, 2); ?>" class="form-control" style="min-width:100px; max-width:100px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getWOPrice(this,<?php echo $currentRow; ?>);" >
						                                        	<input type="hidden" name="data[<?php echo $currentRow; ?>][ITM_PRICE]" id="data<?php echo $currentRow; ?>ITM_PRICE" value="<?php echo $ITM_PRICE; ?>" class="form-control" style="min-width:100px; max-width:100px;" >
						                                        	<input type="hidden" style="text-align:right" name="itemConvertion<?php echo $currentRow; ?>" id="itemConvertion<?php echo $currentRow; ?>" value="<?php echo $itemConvertion; ?>" >
							                                    <?php } else { ?>
						                                        	<input type="text" name="ITM_PRICE<?php echo $currentRow; ?>" id="ITM_PRICE<?php echo $currentRow; ?>" value="<?php echo number_format($ITM_PRICE, 2); ?>" class="form-control" style="min-width:100px; max-width:100px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getWOPrice(this,<?php echo $currentRow; ?>);" >
						                                        	<input type="hidden" name="data[<?php echo $currentRow; ?>][ITM_PRICE]" id="data<?php echo $currentRow; ?>ITM_PRICE" value="<?php echo $ITM_PRICE; ?>" class="form-control" style="min-width:100px; max-width:100px;" >
						                                        	<input type="hidden" style="text-align:right" name="itemConvertion<?php echo $currentRow; ?>" id="itemConvertion<?php echo $currentRow; ?>" value="<?php echo $itemConvertion; ?>" >
							                                    <?php
							                                }
														}
			                                        ?>
			                                    </td>
											  	<td nowrap style="text-align:center; vertical-align: middle;">
												  <?php echo $ITM_UNIT; ?>
			                                    	<input type="hidden" name="data[<?php echo $currentRow; ?>][ITM_UNIT]" id="data<?php echo $currentRow; ?>ITM_UNIT" value="<?php echo $ITM_UNIT; ?>" class="form-control" style="max-width:300px;" >
			                                    <!-- Item Unit Type -- ITM_UNIT --></td>
											  	<td nowrap style="text-align:right; vertical-align: middle;">
			                                     	<?php if($disRow == 1) { ?>
			                                     		<?php echo number_format($WO_TOTAL, $decFormat); ?>
			                                    		<input type="hidden" class="form-control" style="min-width:100px; max-width:100px; text-align:right" name="WO_TOTAL<?php echo $currentRow; ?>" id="WO_TOTAL<?php echo $currentRow; ?>" value="<?php print number_format($WO_TOTAL, $decFormat); ?>" size="10" disabled >
				                                    <?php } else { ?>
			                                    		<input type="text" class="form-control" style="min-width:100px; max-width:100px; text-align:right" name="WO_TOTAL<?php echo $currentRow; ?>" id="WO_TOTAL<?php echo $currentRow; ?>" value="<?php print number_format($WO_TOTAL, $decFormat); ?>" size="10" disabled >
				                                    <?php } ?>
			                                        
			                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][WO_TOTAL]" id="data<?php echo $currentRow; ?>WO_TOTAL" value="<?php echo $WO_TOTAL; ?>" class="form-control" style="max-width:150px; text-align:right" onKeyPress="return isIntOnlyNew(event);" >
			                                    </td>
												<td style="text-align:center;display: none;">
			                                        <input type="number" name="WO_DISC<?php echo $currentRow; ?>" id="WO_DISC<?php echo $currentRow; ?>" min="0" max="100" step="0.01" value="<?php echo number_format($WO_DISC, $decFormat);?>" class="form-control" style="min-width:80px; max-width:80px; text-align:right;" onKeyPress="return isIntOnlyNew(event);" onBlur="getDiscount(this, <?php echo $currentRow; ?>);">
													<input type="hidden" name="data[<?php echo $currentRow; ?>][WO_DISC]" id="data<?php echo $currentRow; ?>WO_DISC" value="<?php echo $WO_DISC;?>" class="form-control" style="max-width:300px;" >
			                                    </td>
												<td style="text-align:center;display: none;">
													<input type="text" name="WO_DISCP<?php echo $currentRow; ?>" id="WO_DISCP<?php echo $currentRow; ?>" value="<?php echo number_format($WO_DISCP, $decFormat);?>" class="form-control" style="min-width:100px; max-width:100px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getDiscountP(this,<?php echo $currentRow; ?>);" >
													<input type="hidden" name="data[<?php echo $currentRow; ?>][WO_DISCP]" id="data<?php echo $currentRow; ?>WO_DISCP" value="<?php echo $WO_DISCP;?>" class="form-control" style="max-width:300px;" >
			                                    </td>
												<td style="text-align:center; vertical-align: middle;" nowrap>
			                                     	<?php if($disRow == 1) { ?>
			                                     		<?php echo number_format($TAXPERC1, 2); ?>
														 <input type="hidden" name="data[<?php echo $currentRow; ?>][TAXPERC1]" id="data<?php echo $currentRow; ?>TAXPERC1" size="20" value="<?php echo $TAXPERC1; ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:right">
				                                    <?php } else { ?>
					                                    <input type="text" name="TAXPERC1<?php echo $currentRow; ?>" id="TAXPERC1<?php echo $currentRow; ?>" size="20" value="<?php echo number_format($TAXPERC1, 2); ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:right" disabled>
														<input type="hidden" name="data[<?php echo $currentRow; ?>][TAXPERC1]" id="data<?php echo $currentRow; ?>TAXPERC1" size="20" value="<?php echo $TAXPERC1; ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:right">
				                                    <?php } ?>    
			                                    </td>
											  	<td nowrap style="text-align:right; vertical-align: middle;">
			                                     	<?php if($disRow == 1) { ?>
			                                     		<?php echo number_format($TAXPRICE1, $decFormat); ?>
				                                        <select name="data[<?php echo $currentRow; ?>][TAXCODE1]" id="TAXCODE1<?php echo $currentRow; ?>" class="form-control" style="max-width:150px; display: none;" onChange="getWOPPN(this.value,<?php echo $currentRow; ?>);">
				                                        	<option value=""> --- </option>
				                                        	<?php
				                                        		$s_01 	= "SELECT TAXLA_NUM, TAXLA_DESC FROM tbl_tax_ppn";
				                                        		$r_01 	= $this->db->query($s_01)->result();
				                                        		foreach($r_01 as $rw_01):
				                                        			$PPN_NUM 	= $rw_01->TAXLA_NUM;
				                                        			$PPN_DESC = $rw_01->TAXLA_DESC;
				                                        			?>
				                                        				<option value="<?=$PPN_NUM?>" <?php if($TAXCODE1 == $PPN_NUM) { ?> selected <?php } ?>><?=$PPN_DESC?></option>
				                                        			<?php
				                                        		endforeach;
				                                        	?>
				                                        </select>
				                                    <?php } else { ?>
				                                        <select name="data[<?php echo $currentRow; ?>][TAXCODE1]" id="TAXCODE1<?php echo $currentRow; ?>" class="form-control" style="min-width: 80px; max-width:150px" onChange="getWOPPN(this.value,<?php echo $currentRow; ?>);">
				                                        	<option value=""> --- </option>
				                                        	<?php
				                                        		$s_01 	= "SELECT TAXLA_NUM, TAXLA_DESC FROM tbl_tax_ppn";
				                                        		$r_01 	= $this->db->query($s_01)->result();
				                                        		foreach($r_01 as $rw_01):
				                                        			$PPN_NUM 	= $rw_01->TAXLA_NUM;
				                                        			$PPN_DESC = $rw_01->TAXLA_DESC;
				                                        			?>
				                                        				<option value="<?=$PPN_NUM?>" <?php if($TAXCODE1 == $PPN_NUM) { ?> selected <?php } ?>><?=$PPN_DESC?></option>
				                                        			<?php
				                                        		endforeach;
				                                        	?>
				                                        </select>
				                                    <?php } ?>
			                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][TAXPRICE1]" id="data<?php echo $currentRow; ?>TAXPRICE1" size="20" value="<?php echo $TAXPRICE1; ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:left">
			                                    </td>
												<td style="text-align:center; vertical-align: middle;" nowrap>
			                                     	<?php if($disRow == 1) { ?>
			                                     		<?php echo number_format($TAXPERC2, 2); ?>
														 <input type="hidden" name="data[<?php echo $currentRow; ?>][TAXPERC2]" id="data<?php echo $currentRow; ?>TAXPERC2" size="20" value="<?php echo $TAXPERC2; ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:right">
				                                    <?php } else { ?>
					                                    <input type="text" name="TAXPERC2<?php echo $currentRow; ?>" id="TAXPERC2<?php echo $currentRow; ?>" size="20" value="<?php echo number_format($TAXPERC2, 2); ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:right" disabled>
														<input type="hidden" name="data[<?php echo $currentRow; ?>][TAXPERC2]" id="data<?php echo $currentRow; ?>TAXPERC2" size="20" value="<?php echo $TAXPERC2; ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:right">
				                                    <?php } ?>    
			                                    </td>
			                                    <td nowrap style="text-align:right; vertical-align: middle;">
			                                     	<?php if($disRow == 1) { ?>
			                                     		<?php echo number_format($TAXPRICE2, $decFormat); ?>
					                                    <select name="data[<?php echo $currentRow; ?>][TAXCODE2]" id="TAXCODE2<?php echo $currentRow; ?>" class="form-control" style="max-width:150px; display: none;" onChange="getWOPPH(this.value,<?php echo $currentRow; ?>);">
				                                        	<option value=""> --- </option>
				                                        	<?php
				                                        		$s_01 	= "SELECT TAXLA_NUM, TAXLA_DESC FROM tbl_tax_la";
				                                        		$r_01 	= $this->db->query($s_01)->result();
				                                        		foreach($r_01 as $rw_01):
				                                        			$PPH_NUM 	= $rw_01->TAXLA_NUM;
				                                        			$PPH_DESC = $rw_01->TAXLA_DESC;
				                                        			?>
				                                        				<option value="<?=$PPH_NUM?>" <?php if($TAXCODE2 == $PPH_NUM) { ?> selected <?php } ?>><?=$PPH_DESC?></option>
				                                        			<?php
				                                        		endforeach;
				                                        	?>
				                                        </select>
				                                    <?php } else { ?>
					                                    <select name="data[<?php echo $currentRow; ?>][TAXCODE2]" id="TAXCODE2<?php echo $currentRow; ?>" class="form-control" style="min-width: 80px; max-width:150px" onChange="getWOPPH(this.value,<?php echo $currentRow; ?>);">
				                                        	<option value=""> --- </option>
				                                        	<?php
				                                        		$s_01 	= "SELECT TAXLA_NUM, TAXLA_DESC FROM tbl_tax_la";
				                                        		$r_01 	= $this->db->query($s_01)->result();
				                                        		foreach($r_01 as $rw_01):
				                                        			$PPH_NUM 	= $rw_01->TAXLA_NUM;
				                                        			$PPH_DESC = $rw_01->TAXLA_DESC;
				                                        			?>
				                                        				<option value="<?=$PPH_NUM?>" <?php if($TAXCODE2 == $PPH_NUM) { ?> selected <?php } ?>><?=$PPH_DESC?></option>
				                                        			<?php
				                                        		endforeach;
				                                        	?>
				                                        </select>
				                                    <?php } ?>
			                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][TAXPRICE2]" id="data<?php echo $currentRow; ?>TAXPRICE2" size="20" value="<?php echo $TAXPRICE2; ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:left">
			                                    </td>
											  	<td style="text-align:right; vertical-align: middle;">
			                                     	<?php if($disRow == 1) { ?>
			                                     		<?php echo number_format($WO_TOTAL2, $decFormat); ?>
					                                    <input type="hidden" name="data<?php echo $currentRow; ?>WO_TOTAL2X" id="WO_TOTAL2X<?php echo $currentRow; ?>" value="<?php echo number_format($WO_TOTAL2, 2); ?>" class="form-control" style="min-width:130px; max-width:130px; text-align:right;" onKeyPress="return isIntOnlyNew(event);" disabled >
				                                    <?php } else { ?>
					                                    <input type="text" name="data<?php echo $currentRow; ?>WO_TOTAL2X" id="WO_TOTAL2X<?php echo $currentRow; ?>" value="<?php echo number_format($WO_TOTAL2, 2); ?>" class="form-control" style="min-width:130px; max-width:130px; text-align:right;" onKeyPress="return isIntOnlyNew(event);" disabled >
				                                    <?php } ?>

			                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][WO_TOTAL2]" id="data<?php echo $currentRow; ?>WO_TOTAL2" value="<?php echo $WO_TOTAL2; ?>" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" >
			                              		</td>
											  	<td style="text-align:left;" nowrap>
			                                     	<?php if($disRow == 1) { ?>
			                                     		<?php echo $WO_DESC; ?>
					                                    <input type="hidden" name="data[<?php echo $currentRow; ?>][WO_DESC]" id="data<?php echo $currentRow; ?>WO_DESC" size="20" value="<?php echo $WO_DESC; ?>" class="form-control" style="min-width:130px; max-width:150px; text-align:left">
				                                    <?php } else { ?>
					                                    <input type="text" name="data[<?php echo $currentRow; ?>][WO_DESC]" id="data<?php echo $currentRow; ?>WO_DESC" size="20" value="<?php echo $WO_DESC; ?>" class="form-control" style="min-width:130px; max-width:150px; text-align:left">
				                                    <?php } ?>    
			                                    </td>
							          		</tr>
		                              	<?php
		                             	endforeach;
										?>
		                                	<input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">
		                                <?php
		                            }
		                            if($task == 'add')
		                            {
		                            	?>
		                                  <input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">
		                              <?php
		                            }
		                            ?>
		                        </table>
		                    </div>
		                </div>
		            </div>

					<div class="col-md-6">
		                <div class="form-group">
		                	<label for="inputName" class="col-sm-3 control-label">&nbsp;</label>
		                    <div class="col-sm-9">
		                    	<?php
									if($task=='add')
									{
										if($WO_STAT == 1 && $ISCREATE == 1)
										{
											?>
												<button class="btn btn-primary" id="btnSave">
												<i class="fa fa-save"></i></button>&nbsp;
											<?php
										}
									}
									elseif($task=='edit')
									{
										if(($ISCREATE == 1 && $ISAPPROVE == 1) && ($WO_STAT == 1))
										{
											?>
		                                        <button class="btn btn-primary" id="btnSave">
		                                        <i class="fa fa-save"></i></button>&nbsp;
											<?php
										}
										else if($ISCREATE == 1 && ($WO_STAT == 1 || $WO_STAT == 4))
										{
											?>
												<button class="btn btn-primary" id="btnSave">
												<i class="fa fa-save"></i></button>&nbsp;
											<?php
										}
									}
									?>
                                        <button class="btn btn-primary" style="display:none" id="tblClose">
                                        <i class="fa fa-save"></i></button>
									<?php

									$backURL	= site_url('c_project/c_s180d0bpk/gallS180d0bpk/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
									echo anchor("$backURL",'<button class="btn btn-danger" type="button" id="btnBack"><i class="fa fa-reply"></i></button>');
		                        ?>
		                    </div>
		                </div>
		          	</div>

			        <div class="col-md-12">
						<?php
                            $DOC_NUM	= $WO_NUM;
                            $sqlCAPPH	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM'";
                            $resCAPPH	= $this->db->count_all($sqlCAPPH);
							$sqlAPP		= "SELECT * FROM tbl_docstepapp WHERE MENU_CODE = '$MenuApp'
											AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE proj_Code = '$PRJCODE_LEV')";
							$resAPP		= $this->db->query($sqlAPP)->result();
							foreach($resAPP as $rowAPP) :
								$MAX_STEP		= $rowAPP->MAX_STEP;
								$APPROVER_1		= $rowAPP->APPROVER_1;
								$APPROVER_2		= $rowAPP->APPROVER_2;
								$APPROVER_3		= $rowAPP->APPROVER_3;
								$APPROVER_4		= $rowAPP->APPROVER_4;
								$APPROVER_5		= $rowAPP->APPROVER_5;
							endforeach;
							
                        	if($resCAPP == 0)
                        	{
                        		if($LangID == 'IND')
								{
									$zerSetApp	= "Belum ada pengaturan untuk persetujuan dokumen ini.";
								}
								else
								{
									$zerSetApp	= "There are no arrangements for the approval of this document.";
								}
                        		?>
                        			<div class="alert alert-warning alert-dismissible">
					                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					                <?php echo $zerSetApp; ?>
					              	</div>
                        		<?php
                        	}
                        ?>
		                <div class="row">
		                    <div class="col-md-12">
		                        <div class="box box-danger collapsed-box">
		                            <div class="box-header with-border">
		                                <h3 class="box-title"><?php echo $Approval; ?></h3>
		                                <div class="box-tools pull-right">
		                                    <span class="label label-danger"><?php echo "$Approved : $resCAPPH "; ?></span>
		                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
		                                    </button>
		                                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
		                                    </button>
		                                </div>
		                            </div>
		                            <div class="box-body">
		                            <?php
										$SHOWOTH		= 0;
										$AH_ISLAST		= 0;
										$APPROVER_1A	= 0;
										$APPROVER_2A	= 0;
										$APPROVER_3A	= 0;
										$APPROVER_4A	= 0;
										$APPROVER_5A	= 0;
		                                if($APPROVER_1 != '')
		                                {
		                                    $boxCol_1	= "red";
		                                    $sqlCAPPH_1	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_1'";
		                                    $resCAPPH_1	= $this->db->count_all($sqlCAPPH_1);
		                                    if($resCAPPH_1 > 0)
		                                    {
		                                        $boxCol_1	= "green";
		                                        $Approver	= $Approved;
		                                        $class		= "glyphicon glyphicon-ok-sign";
		                                        
		                                        $sqlAPPH_1	= "SELECT AH_APPROVED, AH_ISLAST 
																FROM tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_1'";
		                                        $resAPPH_1	= $this->db->query($sqlAPPH_1)->result();
		                                        foreach($resAPPH_1 as $rowAPPH_1):
		                                            $APPROVED_1	= $rowAPPH_1->AH_APPROVED;
		                                            $AH_ISLAST	= $rowAPPH_1->AH_ISLAST;
		                                        endforeach;
		                                    }
		                                    elseif($resCAPPH_1 == 0)
		                                    {
												$Approver	= $NotYetApproved;
												$class		= "glyphicon glyphicon-remove-sign";
												$APPROVED_1	= "Not Set";
												
												$sqlCAPPH_1A	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = 1";
												$resCAPPH_1A	= $this->db->count_all($sqlCAPPH_1A);
												if($resCAPPH_1A > 0)
												{
													$SHOWOTH	= 1;
													$APPROVER_1A= 1;
													$EMPN_1A	= '';
													$AH_ISLAST1A=0;
													$APPROVED_1A= '0000-00-00';
													$boxCol_1A	= "green";
													$Approver1A	= $Approved;
													$class1A	= "glyphicon glyphicon-ok-sign";
													
													$sqlAPPH_1A	= "SELECT A.AH_APPROVER, A.AH_APPROVED, A.AH_ISLAST,
																		CONCAT(B.First_Name, ' ', B.Last_Name) AS COMPNAME
																	FROM tbl_approve_hist A 
																		INNER JOIN tbl_employee B ON A.AH_APPROVER = B.Emp_ID
																	WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = 1";
													$resAPPH_1A	= $this->db->query($sqlAPPH_1A)->result();
													foreach($resAPPH_1A as $rowAPPH_1A):
														$EMPN_1A		= $rowAPPH_1A->COMPNAME;
														$AH_ISLAST1A	= $rowAPPH_1A->AH_ISLAST;
														$APPROVED_1A	= $rowAPPH_1A->AH_APPROVED;
													endforeach;
												}
		                                    }
											?>
												<div class="col-md-3">
													<div class="info-box bg-<?php echo $boxCol_1; ?>">
														<span class="info-box-icon"><i class="<?php echo $class; ?>"></i></span>
														<div class="info-box-content">

															<span class="info-box-text"><?php echo $Approver; ?></span>
															<span class="info-box-number"><?php echo cut_text ("$EMPN_1", 20); ?></span>
															<div class="progress">
																<div class="progress-bar" style="width: 50%"></div>
															</div>
															<span class="progress-description">
																<?php echo $APPROVED_1; ?>
															</span>
														</div>
													</div>
												</div>
											<?php
		                                }
		                                if($APPROVER_2 != '' && $AH_ISLAST == 0)
		                                {
		                                    $boxCol_2	= "red";
		                                    $sqlCAPPH_2	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_2'";
		                                    $resCAPPH_2	= $this->db->count_all($sqlCAPPH_2);
		                                    if($resCAPPH_2 > 0)
		                                    {
		                                        $boxCol_2	= "green";
		                                        $class		= "glyphicon glyphicon-ok-sign";
		                                        
		                                        $sqlAPPH_2	= "SELECT AH_APPROVED FROM tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_2'";
		                                        $resAPPH_2	= $this->db->query($sqlAPPH_2)->result();
		                                        foreach($resAPPH_2 as $rowAPPH_2):
		                                            $APPROVED_2	= $rowAPPH_2->AH_APPROVED;
		                                        endforeach;
		                                    }
		                                    elseif($resCAPPH_2 == 0)
		                                    {
		                                        $Approver	= $NotYetApproved;
		                                        $class		= "glyphicon glyphicon-remove-sign";
		                                        $APPROVED_2	= "Not Set";
												
												$sqlCAPPH_2A	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = 2";
												$resCAPPH_2A	= $this->db->count_all($sqlCAPPH_2A);
												if($resCAPPH_2A > 0)
												{
													$APPROVER_2A= 1;
													$EMPN_2A	= '';
													$AH_ISLAST2A=0;
													$APPROVED_2A= '0000-00-00';
													$boxCol_2A	= "green";
													$Approver2A	= $Approved;
													$class2A	= "glyphicon glyphicon-ok-sign";
													
													$sqlAPPH_2A	= "SELECT A.AH_APPROVER, A.AH_APPROVED, A.AH_ISLAST,
																		CONCAT(B.First_Name, ' ', B.Last_Name) AS COMPNAME
																	FROM tbl_approve_hist A 
																		INNER JOIN tbl_employee B ON A.AH_APPROVER = B.Emp_ID
																	WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = 2";
													$resAPPH_2A	= $this->db->query($sqlAPPH_2A)->result();
													foreach($resAPPH_2A as $rowAPPH_2A):
														$EMPN_2A		= $rowAPPH_2A->COMPNAME;
														$AH_ISLAST2A	= $rowAPPH_2A->AH_ISLAST;
														$APPROVED_2A	= $rowAPPH_2A->AH_APPROVED;
													endforeach;
												}
											}
		                                    
		                                    /*if($resCAPPH == 0)
		                                    {
		                                        $Approver	= $Awaiting;
		                                        $boxCol_2	= "yellow";
		                                        $class		= "glyphicon glyphicon-info-sign";
		                                    }*/
											?>
												<div class="col-md-3">
													<div class="info-box bg-<?php echo $boxCol_2; ?>">
														<span class="info-box-icon"><i class="<?php echo $class; ?>"></i></span>
														<div class="info-box-content">
															<span class="info-box-text"><?php echo $Approver; ?></span>
															<span class="info-box-number"><?php echo cut_text ("$EMPN_2", 20); ?></span>
															<div class="progress">
																<div class="progress-bar" style="width: 50%"></div>
															</div>
															<span class="progress-description">
																<?php echo $APPROVED_2; ?>
															</span>
														</div>
													</div>
												</div>
											<?php
		                                }
		                                if($APPROVER_3 != '' && $AH_ISLAST == 0)
		                                {
		                                    $boxCol_3	= "red";
		                                    $sqlCAPPH_3	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_3'";
		                                    $resCAPPH_3	= $this->db->count_all($sqlCAPPH_3);
		                                    if($resCAPPH_3 > 0)
		                                    {
		                                        $boxCol_3	= "green";
		                                        $class		= "glyphicon glyphicon-ok-sign";
		                                        
		                                        $sqlAPPH_3	= "SELECT AH_APPROVED FROM tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_3'";
		                                        $resAPPH_3	= $this->db->query($sqlAPPH_3)->result();
		                                        foreach($resAPPH_3 as $rowAPPH_3):
		                                            $APPROVED_3	= $rowAPPH_3->AH_APPROVED;
		                                        endforeach;
		                                    }
		                                    elseif($resCAPPH_3 == 0)
		                                    {
		                                        $Approver	= $NotYetApproved;
		                                        $class		= "glyphicon glyphicon-remove-sign";
		                                        $APPROVED_3	= "Not Set";
												
												$sqlCAPPH_3A	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = 3";
												$resCAPPH_3A	= $this->db->count_all($sqlCAPPH_3A);
												if($resCAPPH_3A > 0)
												{
													$APPROVER_3A= 1;
													$EMPN_3A	= '';
													$AH_ISLAST3A=0;
													$APPROVED_3A= '0000-00-00';
													$boxCol_3A	= "green";
													$Approver3A	= $Approved;
													$class3A	= "glyphicon glyphicon-ok-sign";
													
													$sqlAPPH_3A	= "SELECT A.AH_APPROVER, A.AH_APPROVED, A.AH_ISLAST,
																		CONCAT(B.First_Name, ' ', B.Last_Name) AS COMPNAME
																	FROM tbl_approve_hist A 
																		INNER JOIN tbl_employee B ON A.AH_APPROVER = B.Emp_ID
																	WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = 3";
													$resAPPH_3A	= $this->db->query($sqlAPPH_3A)->result();
													foreach($resAPPH_3A as $rowAPPH_3A):
														$EMPN_3A		= $rowAPPH_3A->COMPNAME;
														$AH_ISLAST3A	= $rowAPPH_3A->AH_ISLAST;
														$APPROVED_3A	= $rowAPPH_3A->AH_APPROVED;
													endforeach;
												}
		                                    }
											?>
												<div class="col-md-3">
													<div class="info-box bg-<?php echo $boxCol_3; ?>">
														<span class="info-box-icon"><i class="<?php echo $class; ?>"></i></span>
														<div class="info-box-content">
															<span class="info-box-text"><?php echo $Approver; ?></span>
															<span class="info-box-number"><?php echo cut_text ("$EMPN_3", 20); ?></span>
															<div class="progress">
																<div class="progress-bar" style="width: 50%"></div>
															</div>
															<span class="progress-description">
																<?php echo $APPROVED_3; ?>
															</span>
														</div>
													</div>
												</div>
											<?php
		                                }
		                                if($APPROVER_4 != '' && $AH_ISLAST == 0)
		                                {
		                                    $boxCol_4	= "red";
		                                    $sqlCAPPH_4	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_4'";
		                                    $resCAPPH_4	= $this->db->count_all($sqlCAPPH_4);
		                                    if($resCAPPH_4 > 0)
		                                    {
		                                        $boxCol_4	= "green";
		                                        $class		= "glyphicon glyphicon-ok-sign";
		                                        
		                                        $sqlAPPH_4	= "SELECT AH_APPROVED FROM tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_4'";
		                                        $resAPPH_4	= $this->db->query($sqlAPPH_4)->result();
		                                        foreach($resAPPH_4 as $rowAPPH_4):
		                                            $APPROVED_4	= $rowAPPH_4->AH_APPROVED;
		                                        endforeach;
		                                    }
		                                    elseif($resCAPPH_4 == 0)
		                                    {
		                                        $Approver	= $NotYetApproved;
		                                        $class		= "glyphicon glyphicon-remove-sign";
		                                        $APPROVED_4	= "Not Set";
												
												$sqlCAPPH_4A	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = 4";
												$resCAPPH_4A	= $this->db->count_all($sqlCAPPH_4A);
												if($resCAPPH_4A > 0)
												{
													$APPROVER_4A= 1;
													$EMPN_4A	= '';
													$AH_ISLAST4A=0;
													$APPROVED_4A= '0000-00-00';
													$boxCol_4A	= "green";
													$Approver4A	= $Approved;
													$class4A	= "glyphicon glyphicon-ok-sign";
													
													$sqlAPPH_4A	= "SELECT A.AH_APPROVER, A.AH_APPROVED, A.AH_ISLAST,
																		CONCAT(B.First_Name, ' ', B.Last_Name) AS COMPNAME
																	FROM tbl_approve_hist A 
																		INNER JOIN tbl_employee B ON A.AH_APPROVER = B.Emp_ID
																	WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = 4";
													$resAPPH_4A	= $this->db->query($sqlAPPH_4A)->result();
													foreach($resAPPH_4A as $rowAPPH_4A):
														$EMPN_4A		= $rowAPPH_4A->COMPNAME;
														$AH_ISLAST4A	= $rowAPPH_4A->AH_ISLAST;
														$APPROVED_4A	= $rowAPPH_4A->AH_APPROVED;
													endforeach;
												}
		                                    }
											?>
												<div class="col-md-3">
													<div class="info-box bg-<?php echo $boxCol_4; ?>">
														<span class="info-box-icon"><i class="<?php echo $class; ?>"></i></span>
														<div class="info-box-content">
															<span class="info-box-text"><?php echo $Approver; ?></span>
															<span class="info-box-number"><?php echo cut_text ("$EMPN_4", 20); ?></span>
															<div class="progress">
																<div class="progress-bar" style="width: 50%"></div>
															</div>
															<span class="progress-description">
																<?php echo $APPROVED_4; ?>
															</span>
														</div>
													</div>
												</div>
											<?php
		                                }
		                                if($APPROVER_5 != '' && $AH_ISLAST == 0)
		                                {
		                                    $boxCol_5	= "red";
		                                    $sqlCAPPH_5	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_5'";
		                                    $resCAPPH_5	= $this->db->count_all($sqlCAPPH_5);
		                                    if($resCAPPH_5 > 0)
		                                    {
		                                        $boxCol_5	= "green";
		                                        $class		= "glyphicon glyphicon-ok-sign";
		                                        
		                                        $sqlAPPH_5	= "SELECT AH_APPROVED FROM tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_5'";
		                                        $resAPPH_5	= $this->db->query($sqlAPPH_5)->result();
		                                        foreach($resAPPH_5 as $rowAPPH_5):
		                                            $APPROVED_5	= $rowAPPH_5->AH_APPROVED;
		                                        endforeach;
		                                    }
		                                    elseif($resCAPPH_5 == 0)
		                                    {
		                                        $Approver	= $NotYetApproved;
		                                        $class		= "glyphicon glyphicon-remove-sign";
		                                        $APPROVED_5	= "Not Set";
												
												$sqlCAPPH_5A	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = 5";
												$resCAPPH_5A	= $this->db->count_all($sqlCAPPH_5A);
												if($resCAPPH_5A > 0)
												{
													$APPROVER_5A= 1;
													$EMPN_5A	= '';
													$AH_ISLAST5A=0;
													$APPROVED_5A= '0000-00-00';
													$boxCol_5A	= "green";
													$Approver5A	= $Approved;
													$class5A	= "glyphicon glyphicon-ok-sign";
													
													$sqlAPPH_5A	= "SELECT A.AH_APPROVER, A.AH_APPROVED, A.AH_ISLAST,
																		CONCAT(B.First_Name, ' ', B.Last_Name) AS COMPNAME
																	FROM tbl_approve_hist A 
																		INNER JOIN tbl_employee B ON A.AH_APPROVER = B.Emp_ID
																	WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = 5";
													$resAPPH_5A	= $this->db->query($sqlAPPH_5A)->result();
													foreach($resAPPH_5A as $rowAPPH_5A):
														$EMPN_5A		= $rowAPPH_5A->COMPNAME;
														$AH_ISLAST5A	= $rowAPPH_5A->AH_ISLAST;
														$APPROVED_5A	= $rowAPPH_5A->AH_APPROVED;
													endforeach;
												}
		                                    }
											?>
												<div class="col-md-3">
													<div class="info-box bg-<?php echo $boxCol_5; ?>">
														<span class="info-box-icon"><i class="<?php echo $class; ?>"></i></span>
														<div class="info-box-content">
															<span class="info-box-text"><?php echo $Approver; ?></span>
															<span class="info-box-number"><?php echo cut_text ("$EMPN_5", 20); ?></span>
															<div class="progress">
																<div class="progress-bar" style="width: 50%"></div>
															</div>
															<span class="progress-description">
																<?php echo $APPROVED_5; ?>
															</span>
														</div>
													</div>
												</div>
											<?php
		                                }
		                            ?>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		                <?php if($SHOWOTH == 1) { ?>
		                    <div class="row">
		                        <div class="col-md-12">
		                            <div class="box box-danger collapsed-box">
		                                <div class="box-header with-border">
		                                    <h3 class="box-title"><?php echo $InOthSett; ?></h3>
		                                    <div class="box-tools pull-right">
		                                        <span class="label label-danger"><?php echo "$Approved : $resCAPPH "; ?></span>
		                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
		                                        </button>
		                                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
		                                        </button>
		                                    </div>
		                                </div>
		                                <div class="box-body">
		                                <?php
		                                    if($APPROVER_1A == 1)
		                                    {
		                                        ?>
		                                            <div class="col-md-3">
		                                                <div class="info-box bg-<?php echo $boxCol_1A; ?>">
		                                                    <span class="info-box-icon"><i class="<?php echo $class1A; ?>"></i></span>
		                                                    <div class="info-box-content">
		                                                        <span class="info-box-text"><?php echo $Approver1A; ?></span>
		                                                        <span class="info-box-number"><?php echo cut_text ("$EMPN_1A", 20); ?></span>
		                                                        <div class="progress">
		                                                            <div class="progress-bar" style="width: 50%"></div>
		                                                        </div>
		                                                        <span class="progress-description">
		                                                            <?php echo $APPROVED_1A; ?>
		                                                        </span>
		                                                    </div>
		                                                </div>
		                                            </div>
		                                        <?php
		                                    }
		                                    if($APPROVER_2A == 1)
		                                    {
		                                        ?>
		                                            <div class="col-md-3">
		                                                <div class="info-box bg-<?php echo $boxCol_2A; ?>">
		                                                    <span class="info-box-icon"><i class="<?php echo $class2A; ?>"></i></span>
		                                                    <div class="info-box-content">
		                                                        <span class="info-box-text"><?php echo $Approver2A; ?></span>
		                                                        <span class="info-box-number"><?php echo cut_text ("$EMPN_2A", 20); ?></span>
		                                                        <div class="progress">
		                                                            <div class="progress-bar" style="width: 50%"></div>
		                                                        </div>
		                                                        <span class="progress-description">
		                                                            <?php echo $APPROVED_2A; ?>
		                                                        </span>
		                                                    </div>
		                                                </div>
		                                            </div>
		                                        <?php
		                                    }
		                                    if($APPROVER_3A == 1)
		                                    {
		                                        ?>
		                                            <div class="col-md-3">
		                                                <div class="info-box bg-<?php echo $boxCol_3A; ?>">
		                                                    <span class="info-box-icon"><i class="<?php echo $class3A; ?>"></i></span>
		                                                    <div class="info-box-content">
		                                                        <span class="info-box-text"><?php echo $Approver3A; ?></span>
		                                                        <span class="info-box-number"><?php echo cut_text ("$EMPN_3A", 20); ?></span>
		                                                        <div class="progress">
		                                                            <div class="progress-bar" style="width: 50%"></div>
		                                                        </div>
		                                                        <span class="progress-description">
		                                                            <?php echo $APPROVED_3A; ?>
		                                                        </span>
		                                                    </div>
		                                                </div>
		                                            </div>
		                                        <?php
		                                    }
		                                    if($APPROVER_4A == 1)
		                                    {
		                                        ?>
		                                            <div class="col-md-3">
		                                                <div class="info-box bg-<?php echo $boxCol_4A; ?>">
		                                                    <span class="info-box-icon"><i class="<?php echo $class4A; ?>"></i></span>
		                                                    <div class="info-box-content">
		                                                        <span class="info-box-text"><?php echo $Approver4A; ?></span>
		                                                        <span class="info-box-number"><?php echo cut_text ("$EMPN_4A", 20); ?></span>
		                                                        <div class="progress">
		                                                            <div class="progress-bar" style="width: 50%"></div>
		                                                        </div>
		                                                        <span class="progress-description">
		                                                            <?php echo $APPROVED_4A; ?>
		                                                        </span>
		                                                    </div>
		                                                </div>
		                                            </div>
		                                        <?php
		                                    }
		                                    if($APPROVER_5A == 1)
		                                    {
		                                        ?>
		                                            <div class="col-md-3">
		                                                <div class="info-box bg-<?php echo $boxCol_5A; ?>">
		                                                    <span class="info-box-icon"><i class="<?php echo $class5A; ?>"></i></span>
		                                                    <div class="info-box-content">
		                                                        <span class="info-box-text"><?php echo $Approver5A; ?></span>
		                                                        <span class="info-box-number"><?php echo cut_text ("$EMPN_5A", 20); ?></span>
		                                                        <div class="progress">
		                                                            <div class="progress-bar" style="width: 50%"></div>
		                                                        </div>
		                                                        <span class="progress-description">
		                                                            <?php echo $APPROVED_5A; ?>
		                                                        </span>
		                                                    </div>
		                                                </div>
		                                            </div>
		                                        <?php
		                                    }
		                                ?>
		                                </div>
		                            </div>
		                        </div>
		                    </div>
		                <?php } ?>
			        </div>
		        </form>
		    </div>
	        <a class="btn btn-sm btn-warning" data-toggle="modal" data-target="#mdl_delItm" id="btnModalDel" style="display: none;">
        		<i class="glyphicon glyphicon-search"></i>
        	</a>

	    	<!-- ============ START MODAL ITEM =============== -->
		    	<style type="text/css">
		    		.modal-dialog{
					    position: relative;
					    display: table; /* This is important */ 
					    overflow-y: auto;    
					    overflow-x: auto;
					    width: auto;
					    min-width: 300px;   
					}
		    	</style>
		    	<?php
					$Active1		= "active";
					$Active2		= "";
					$Active3		= "";
					$Active4		= "";
					$Active5		= "";
					$Active6		= "";
					$Active1Cls		= "class='active'";
					$Active2Cls		= "";
					$Active3Cls		= "";
					$Active4Cls		= "";
					$Active5Cls		= "";
					$Active6Cls		= "";
		    	?>
		        <div class="modal fade" id="mdl_addItm" name='mdl_addItm' tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		            <div class="modal-dialog">
			            <div class="modal-content">
			                <div class="modal-body">
								<div class="row">
							    	<div class="col-md-12">
						              	<ul class="nav nav-tabs">
						                    <li id="li1" style="display: none;">
						                    	<a href="#itm1" data-toggle="tab" onClick="setType(1)"><?=$ItemList?></a>
						                    </li>	
						                    <li id="li2" <?php echo $Active1Cls; ?>>
						                    	<a href="#itm2" data-toggle="tab" onClick="setType(2)"><?=$Wage?></a>
						                    </li>
						                    <li id="li3">
						                    	<a href="#itm3" data-toggle="tab" onClick="setType(3)">Alat</a>
						                    </li>
						                    <li id="li4">
						                    	<a href="#itm4" data-toggle="tab" onClick="setType(4)">Subkon</a>
						                    </li>
						                    <li id="li5">
						                    	<a href="#itm5" data-toggle="tab" onClick="setType(5)">Overhead</a>
						                    </li>
						                    <li id="li6" style="display: none;">
						                    	<a href="#itm6" data-toggle="tab" onClick="setType(6)"><?=$JobList?></a>
						                    </li>
						                </ul>
							            <div class="box-body">
							            	<div class="<?php echo $Active1; ?> tab-pane" id="itm1" style="display: none;">
		                                        <div class="form-group">
		                                        	<form method="post" name="frmSearch1" id="frmSearch1" action="">
			                                            <table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
			                                                <thead>
			                                                    <tr>
											                        <th width="3%" style="text-align: center;">&nbsp;</th>
											                        <th width="32%" style="text-align: center;" nowrap><?php echo $Description; ?></th>
											                        <th width="5%" style="text-align: center;" nowrap><?php echo $Unit; ?></th>
											                        <th width="10%" style="text-align: center;" nowrap><?php echo $BudgetQty; ?>  </th>
											                        <th width="10%" style="text-align: center;" nowrap><?php echo $Requested; ?></th>
											                        <th width="10%" style="text-align: center;" nowrap><?php echo $QtyOpnamed; ?></th>
											                        <th width="10%" style="text-align: center;" nowrap><?php echo $StockQuantity; ?>  </th>
											                  	</tr>
			                                                </thead>
			                                                <tbody>
			                                                </tbody>
			                                            </table>
                                                    	<button class="btn btn-primary" type="button" id="btnDetail1" name="btnDetail1">
                                                    		<i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?>
                                                    	</button>
                                      					<button type="button" id="idClose1" class="btn btn-danger" data-dismiss="modal">
                                                    		<i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>
                                                    	</button>
                                      					<button class="btn btn-warning" type="button" id="idRefresh1" >
                                                    		<i class="glyphicon glyphicon-refresh"></i>
                                                    	</button>
		                                            </form>
		                                      	</div>
		                                    </div>

		                                    <div class="<?php echo $Active2; ?> tab-pane" id="itm2">
		                                        <div class="form-group">
		                                        	<form method="post" name="frmSearch2" id="frmSearch2" action="">
		                                        		<div class="search-table-outter">
				                                            <table id="example2" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
				                                                <thead>
			                                                        <tr>
												                        <th width="3%" style="text-align: center;">&nbsp;</th>
												                        <th width="32%" style="text-align: center;" nowrap><?php echo $Description; ?></th>
												                        <th width="5%" style="text-align: center;" nowrap><?php echo $Unit; ?></th>
												                        <th width="10%" style="text-align: center;" nowrap><?php echo $BudgetQty; ?>  </th>
												                        <th width="10%" style="text-align: center;" nowrap><?php echo $Requested; ?></th>
												                        <th width="10%" style="text-align: center;" nowrap><?php echo $QtyOpnamed; ?></th>
												                        <th width="10%" style="text-align: center;" nowrap><?php echo $StockQuantity; ?>  </th>
				                                                  </tr>
				                                                </thead>
				                                                <tbody>
				                                                </tbody>
				                                            </table>
				                                        </div>
                                                    	<button class="btn btn-primary" type="button" id="btnDetail2" name="btnDetail2">
                                                    		<i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?>
                                                    	</button>
                                      					<button type="button" id="idClose2" class="btn btn-danger" data-dismiss="modal">
                                                    		<i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>
                                                    	</button>
														<button class="btn btn-warning" type="button" id="idRefresh2" title="Refresh" >
															<i class="glyphicon glyphicon-refresh"></i>
														</button>
		                                            </form>
		                                      	</div>
		                                    </div>

		                                    <div class="<?php echo $Active3; ?> tab-pane" id="itm3" style="display: none;">
		                                        <div class="form-group">
		                                        	<form method="post" name="frmSearch3" id="frmSearch3" action="">
		                                        		<div class="search-table-outter">
				                                            <table id="example3" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
				                                                <thead>
			                                                        <tr>
												                        <th width="3%" style="text-align: center;">&nbsp;</th>
												                        <th width="32%" style="text-align: center;" nowrap><?php echo $Description; ?></th>
												                        <th width="5%" style="text-align: center;" nowrap><?php echo $Unit; ?></th>
												                        <th width="10%" style="text-align: center;" nowrap><?php echo $BudgetQty; ?>  </th>
												                        <th width="10%" style="text-align: center;" nowrap><?php echo $Requested; ?></th>
												                        <th width="10%" style="text-align: center;" nowrap><?php echo $QtyOpnamed; ?></th>
												                        <th width="10%" style="text-align: center;" nowrap><?php echo $StockQuantity; ?>  </th>
				                                                  </tr>
				                                                </thead>
				                                                <tbody>
				                                                </tbody>
				                                            </table>
				                                        </div>
                                                    	<button class="btn btn-primary" type="button" id="btnDetail3" name="btnDetail3">
                                                    		<i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?>
                                                    	</button>
                                      					<button type="button" id="idClose3" class="btn btn-danger" data-dismiss="modal">
                                                    		<i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>
                                                    	</button>
														<button class="btn btn-warning" type="button" id="idRefresh3" title="Refresh" >
															<i class="glyphicon glyphicon-refresh"></i>
														</button>
		                                            </form>
		                                      	</div>
		                                    </div>

							            	<div class="<?php echo $Active4; ?> tab-pane" id="itm4" style="display: none;">
		                                        <div class="form-group">
		                                        	<form method="post" name="frmSearch4" id="frmSearch4" action="">
			                                            <table id="example4" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
			                                                <thead>
		                                                        <tr>
											                        <th width="3%" style="text-align: center;">&nbsp;</th>
											                        <th width="32%" style="text-align: center;" nowrap><?php echo $Description; ?></th>
											                        <th width="5%" style="text-align: center;" nowrap><?php echo $Unit; ?></th>
											                        <th width="10%" style="text-align: center;" nowrap><?php echo $BudgetQty; ?>  </th>
											                        <th width="10%" style="text-align: center;" nowrap><?php echo $Requested; ?></th>
											                        <th width="10%" style="text-align: center;" nowrap><?php echo $QtyOpnamed; ?></th>
											                        <th width="10%" style="text-align: center;" nowrap><?php echo $StockQuantity; ?>  </th>
			                                                  </tr>
			                                                </thead>
			                                                <tbody>
			                                                </tbody>
			                                            </table>
                                                    	<button class="btn btn-primary" type="button" id="btnDetail4" name="btnDetail4">
                                                    		<i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?>
                                                    	</button>
                                      					<button type="button" id="idClose4" class="btn btn-danger" data-dismiss="modal">
                                                    		<i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>
                                                    	</button>
														<button class="btn btn-warning" type="button" id="idRefresh4" title="Refresh" >
															<i class="glyphicon glyphicon-refresh"></i>
														</button>
		                                            </form>
		                                      	</div>
		                                    </div>

		                                    <div class="<?php echo $Active5; ?> tab-pane" id="itm5" style="display: none;">
		                                        <div class="form-group">
		                                        	<form method="post" name="frmSearch5" id="frmSearch5" action="">
			                                            <table id="example5" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
			                                                <thead>
		                                                        <tr>
											                        <th width="3%" style="text-align: center;">&nbsp;</th>
											                        <th width="32%" style="text-align: center;" nowrap><?php echo $Description; ?></th>
											                        <th width="5%" style="text-align: center;" nowrap><?php echo $Unit; ?></th>
											                        <th width="10%" style="text-align: center;" nowrap><?php echo $BudgetQty; ?>  </th>
											                        <th width="10%" style="text-align: center;" nowrap><?php echo $Requested; ?></th>
											                        <th width="10%" style="text-align: center;" nowrap><?php echo $QtyOpnamed; ?></th>
											                        <th width="10%" style="text-align: center;" nowrap><?php echo $StockQuantity; ?>  </th>
			                                                  </tr>
			                                                </thead>
			                                                <tbody>
			                                                </tbody>
			                                            </table>
                                                    	<button class="btn btn-primary" type="button" id="btnDetail5" name="btnDetail5">
                                                    		<i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?>
                                                    	</button>
                                      					<button type="button" id="idClose5" class="btn btn-danger" data-dismiss="modal">
                                                    		<i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>
                                                    	</button>
														<button class="btn btn-warning" type="button" id="idRefresh5" title="Refresh" >
															<i class="glyphicon glyphicon-refresh"></i>
														</button>
		                                            </form>
		                                      	</div>
		                                    </div>

							            	<div class="<?php echo $Active6; ?> tab-pane" id="itm6" style="display: none;">
							            		<div class="col-md-4">
													<div class="box box-primary">
														<div class="box-header with-border" style="display: none;">
															<i class="fa fa-cloud-upload"></i>
															<h3 class="box-title">&nbsp;</h3>
														</div>
														<div class="box-body">
				                                        	<form method="post" name="frmSearch6" id="frmSearch6" action="">
				                                        		<div class="search-table-outter">
						                                            <table id="example6" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
						                                                <thead>
					                                                        <tr>
														                        <th width="3%" style="text-align: center;">&nbsp;</th>
														                        <th width="32%" style="text-align: center;" nowrap><?php echo $Description; ?></th>
						                                                  </tr>
						                                                </thead>
						                                                <tbody>
						                                                </tbody>
						                                            </table>
						                                        </div>
		                                                    	<button class="btn btn-primary" type="button" id="btnDetail6" name="btnDetail6">
		                                                    		<i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?>
		                                                    	</button>
		                                      					<button type="button" id="idClose6" class="btn btn-danger" data-dismiss="modal">
		                                                    		<i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>
		                                                    	</button>
																<button class="btn btn-warning" type="button" id="idRefresh6" title="Refresh" >
																	<i class="glyphicon glyphicon-refresh"></i>
																</button>
																<input type="hidden" name="rowCheckJOB" id="rowCheckJOB" value="0">
				                                            </form>
				                                        </div>
				                                    </div>
		                                      	</div>
							            		<div class="col-md-8">
													<div class="box box-success">
														<div class="box-header with-border" style="display: none;">
															<i class="fa fa-cloud-upload"></i>
															<h3 class="box-title">&nbsp;</h3>
														</div>
														<div class="box-body">
				                                        	<form method="post" name="frmSearch7" id="frmSearch7" action="">
				                                        		<div class="search-table-outter">
						                                            <table id="example7" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
						                                                <thead>
					                                                        <tr>
					                                                        	<th width="3%" style="text-align: center;">&nbsp;</th>
														                        <th width="32%" style="text-align: center;" nowrap><?php echo $Description; ?></th>
														                        <th width="5%" style="text-align: center;" nowrap><?php echo $Unit; ?></th>
														                        <th width="10%" style="text-align: center;" nowrap><?php echo $BudgetQty; ?>  </th>
														                        <th width="10%" style="text-align: center;" nowrap><?php echo $Requested; ?></th>
														                        <th width="10%" style="text-align: center;" nowrap><?php echo $QtyOpnamed; ?></th>
														                        <th width="10%" style="text-align: center;" nowrap><?php echo $StockQuantity; ?>  </th>
						                                                  </tr>
						                                                </thead>
						                                                <tbody>
						                                                </tbody>
						                                            </table>
						                                        </div>
		                                                    	<button class="btn btn-primary" type="button" id="btnDetail7" name="btnDetail7">
		                                                    		<i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?>
		                                                    	</button>
		                                      					<button type="button" id="idClose7" class="btn btn-danger" data-dismiss="modal">
		                                                    		<i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>
		                                                    	</button>
																<button class="btn btn-warning" type="button" id="idRefresh7" title="Refresh" >
																	<i class="glyphicon glyphicon-refresh"></i>
																</button>
				                                            </form>
				                                        </div>
				                                    </div>
		                                      	</div>
		                                    </div>
                                      	</div>
                                      	<input type="hidden" name="rowCheck" id="rowCheck" value="0">
                                      	<button type="button" id="idClose" class="btn btn-default" data-dismiss="modal" style="display: none";>Close</button>
	                                </div>
	                            </div>
			                </div>
				        </div>
				    </div>
				</div>

				<script type="text/javascript">
					$(document).ready(function()
					{
				    	$('#example1').DataTable(
				    	{
					        "processing": true, 
					        "serverSide": true,
							//"scrollX": false,
							"autoWidth": true,
							"filter": true,
					        //"ajax": "<?php // echo site_url('c_project/c_s180d0bpk/get_AllDataSRV/?id='.$PRJCODE)?>",
					        "ajax": "<?php echo site_url('c_project/c_s180d0bpk/get_AllDataM/?id='.$PRJCODE)?>",
					        "type": "POST",
							//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
							"lengthMenu": [[25, 50, 100, 200], [25, 50, 100, 200]],
							"columnDefs": [	{ targets: [0], className: 'dt-body-center' },
											{ targets: [2,3,4,5], className: 'dt-body-right' },
											{ sortable: false, targets: [2,3,4,5] }
										  ],
							"language": {
					            "infoFiltered":"",
					            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
					        },
						});

						$('#example2').DataTable(
				    	{
					        "processing": true, 
					        "serverSide": true,
							//"scrollX": false,
							"autoWidth": true,
							"filter": true,
					        "ajax": "<?php echo site_url('c_project/c_s180d0bpk/get_AllDataU/?id='.$PRJCODE)?>",
					        "type": "POST",
							//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
							"lengthMenu": [[25, 50, 100, 200], [25, 50, 100, 200]],
							"columnDefs": [	{ targets: [0], className: 'dt-body-center' },
											{ targets: [2,3,4,5], className: 'dt-body-right' },
											{ sortable: false, targets: [2,3,4,5] }
										  ],
							"language": {
					            "infoFiltered":"",
					            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
					        },
						});

						$('#example3').DataTable(
				    	{
					        "processing": true, 
					        "serverSide": true,
							//"scrollX": false,
							"autoWidth": true,
							"filter": true,
					        "ajax": "<?php echo site_url('c_project/c_s180d0bpk/get_AllDataA/?id='.$PRJCODE)?>",
					        "type": "POST",
							//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
							"lengthMenu": [[25, 50, 100, 200], [25, 50, 100, 200]],
							"columnDefs": [	{ targets: [0], className: 'dt-body-center' },
											{ targets: [2,3,4,5], className: 'dt-body-right' },
											{ sortable: false, targets: [2,3,4,5] }
										  ],
							"language": {
					            "infoFiltered":"",
					            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
					        },
						});

				    	$('#example4').DataTable(
				    	{
					        "processing": true, 
					        "serverSide": true,
							//"scrollX": false,
							"autoWidth": true,
							"filter": true,
					        "ajax": "<?php echo site_url('c_project/c_s180d0bpk/get_AllDataS/?id='.$PRJCODE)?>",
					        "type": "POST",
							//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
							"lengthMenu": [[25, 50, 100, 200], [25, 50, 100, 200]],
							"columnDefs": [	{ targets: [0], className: 'dt-body-center' },
											{ targets: [2,3,4,5], className: 'dt-body-right' },
											{ sortable: false, targets: [2,3,4,5] }
										  ],
							"language": {
					            "infoFiltered":"",
					            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
					        },
						});

						$('#example5').DataTable(
				    	{
					        "processing": true, 
					        "serverSide": true,
							//"scrollX": false,
							"autoWidth": true,
							"filter": true,
					        "ajax": "<?php echo site_url('c_project/c_s180d0bpk/get_AllDataO/?id='.$PRJCODE)?>",
					        "type": "POST",
							//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
							"lengthMenu": [[25, 50, 100, 200], [25, 50, 100, 200]],
							"columnDefs": [	{ targets: [0], className: 'dt-body-center' },
											{ targets: [2,3,4,5], className: 'dt-body-right' },
											{ sortable: false, targets: [2,3,4,5] }
										  ],
							"language": {
					            "infoFiltered":"",
					            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
					        },
						});

				    	$('#example6').DataTable(
				    	{
					        "processing": true, 
					        "serverSide": true,
							//"scrollX": false,
							"autoWidth": true,
							"filter": true,
					        "ajax": "<?php echo site_url('c_project/c_s180d0bpk/get_AllDataH/?id='.$PRJCODE)?>",
					        "type": "POST",
							//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
							"lengthMenu": [[10,25, 50, 100, 200], [10,25, 50, 100, 200]],
							"columnDefs": [	{ targets: [0], className: 'dt-body-center' }
										  ],
							"language": {
					            "infoFiltered":"",
					            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
					        },
						});
					});

					var selectedRows = 0;
					function pickThis1(thisobj) 
					{
						var favorite = [];
						$.each($("input[name='chk1']:checked"), function() {
					      	favorite.push($(this).val());
					    });
					    $("#rowCheck").val(favorite.length);
					}

					function pickThisJH(thisobj) 
					{
						arrItem		= thisobj.value.split("|");
						JOBCODEID 	= arrItem[0];

						var favorite = [];
						$.each($("input[name='chkJH']:checked"), function() {
					      	favorite.push($(this).val());
					    });
					    $("#rowCheckJOB").val(favorite.length);

				    	$('#example7').DataTable(
				    	{
				    		"destroy": true,
					        "processing": true, 
					        "serverSide": true,
							//"scrollX": false,
							"autoWidth": true,
							"filter": true,
					        "ajax": "<?php echo site_url('c_project/c_s180d0bpk/get_AllDataD/?id='.$PRJCODE.'&JOBCODEID=')?>"+JOBCODEID,
					        "type": "POST",
							//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
							"lengthMenu": [[25, 50, 100, 200], [25, 50, 100, 200]],
							"columnDefs": [	{ targets: [0], className: 'dt-body-center' }
										  ],
							"language": {
					            "infoFiltered":"",
					            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
					        },
						});
					}

					function pickThisJD(thisobj) 
					{
						var favorite = [];
						$.each($("input[name='chkJD']:checked"), function() {
					      	favorite.push($(this).val());
					    });
					    $("#rowCheck").val(favorite.length);
					}

					$(document).ready(function()
					{
					   	$("#btnDetail1").click(function()
					    {
							var totChck 	= $("#rowCheck").val();
							if(totChck == 0)
							{
								swal('<?php echo $alert2; ?>',
								{
									icon: "warning",
								})
								.then(function()
					            {
					                swal.close();
					            });
								return false;
							}

						    $.each($("input[name='chk1']:checked"), function()
						    {
						      	add_item($(this).val());
						    });

						    $('#mdl_addItm').on('hidden.bs.modal', function () {
							    $(this)
								    .find("input,textarea,select")
									    //.val('')
									    .end()
								    .find("input[type=checkbox], input[type=radio]")
								       .prop("checked", "")
								       .end();
							});
                        	document.getElementById("idClose").click()
					    });

					   	$("#btnDetail2").click(function()
					    {
							var totChck 	= $("#rowCheck").val();
							if(totChck == 0)
							{
								swal('<?php echo $alert2; ?>',
								{
									icon: "warning",
								})
								.then(function()
					            {
					                swal.close();
					            });
								return false;
							}

						    $.each($("input[name='chk1']:checked"), function()
						    {
						      	add_item($(this).val());
						    });

						    $('#mdl_addItm').on('hidden.bs.modal', function () {
							    $(this)
								    .find("input,textarea,select")
									    //.val('')
									    .end()
								    .find("input[type=checkbox], input[type=radio]")
								       .prop("checked", "")
								       .end();
							});
                        	document.getElementById("idClose").click()
					    });

					   	$("#btnDetail3").click(function()
					    {
							var totChck 	= $("#rowCheck").val();
							if(totChck == 0)
							{
								swal('<?php echo $alert2; ?>',
								{
									icon: "warning",
								})
								.then(function()
					            {
					                swal.close();
					            });
								return false;
							}

						    $.each($("input[name='chk1']:checked"), function()
						    {
						      	add_item($(this).val());
						    });

						    $('#mdl_addItm').on('hidden.bs.modal', function () {
							    $(this)
								    .find("input,textarea,select")
									    //.val('')
									    .end()
								    .find("input[type=checkbox], input[type=radio]")
								       .prop("checked", "")
								       .end();
							});
                        	document.getElementById("idClose").click()
					    });

					    $("#btnDetail4").click(function()
					    {
							var totChck 	= $("#rowCheck").val();
							if(totChck == 0)
							{
								swal('<?php echo $alert2; ?>',
								{
									icon: "warning",
								})
								.then(function()
					            {
					                swal.close();
					            });
								return false;
							}

						    $.each($("input[name='chk1']:checked"), function()
						    {
						      	add_item($(this).val());
						    });

						    $('#mdl_addItm').on('hidden.bs.modal', function () {
							    $(this)
								    .find("input,textarea,select")
									    //.val('')
									    .end()
								    .find("input[type=checkbox], input[type=radio]")
								       .prop("checked", "")
								       .end();
							});
                        	document.getElementById("idClose").click()
					    });

					    $("#btnDetail5").click(function()
					    {
							var totChck 	= $("#rowCheck").val();
							if(totChck == 0)
							{
								swal('<?php echo $alert2; ?>',
								{
									icon: "warning",
								})
								.then(function()
					            {
					                swal.close();
					            });
								return false;
							}

						    $.each($("input[name='chk1']:checked"), function()
						    {
						      	add_item($(this).val());
						    });

						    $('#mdl_addItm').on('hidden.bs.modal', function () {
							    $(this)
								    .find("input,textarea,select")
									    //.val('')
									    .end()
								    .find("input[type=checkbox], input[type=radio]")
								       .prop("checked", "")
								       .end();
							});
                        	document.getElementById("idClose").click()
					    });

					   	$("#btnDetail6").click(function()
					    {
							var totChck 	= $("#rowCheck").val();
							if(totChck == 0)
							{
								swal('<?php echo $alert2; ?>',
								{
									icon: "warning",
								})
								.then(function()
					            {
					                swal.close();
					            });
								return false;
							}

						    $.each($("input[name='chkJH']:checked"), function()
						    {
						      	add_item($(this).val());
						    });

						    $('#mdl_addItm').on('hidden.bs.modal', function () {
							    $(this)
								    .find("input,textarea,select")
									    //.val('')
									    .end()
								    .find("input[type=checkbox], input[type=radio]")
								       .prop("checked", "")
								       .end();
							});
                        	document.getElementById("idClose").click()
					    });

					   	$("#btnDetail7").click(function()
					    {
							var totChck 	= $("#rowCheck").val();
							if(totChck == 0)
							{
								swal('<?php echo $alert2; ?>',
								{
									icon: "warning",
								})
								.then(function()
					            {
					                swal.close();
					            });
								return false;
							}

						    $.each($("input[name='chkJD']:checked"), function()
						    {
						      	add_item($(this).val());
						    });

						    $('#mdl_addItm').on('hidden.bs.modal', function () {
							    $(this)
								    .find("input,textarea,select")
									    //.val('')
									    .end()
								    .find("input[type=checkbox], input[type=radio]")
								       .prop("checked", "")
								       .end();
							});
                        	document.getElementById("idClose").click()
					    });
					});

					$(document).ready(function()
					{
					   	$("#idRefresh1").click(function()
					    {
							$('#example1').DataTable().ajax.reload();
					    });
					    
					   	$("#idRefresh2").click(function()
					    {
							$('#example2').DataTable().ajax.reload();
					    });
					    
					   	$("#idRefresh3").click(function()
					    {
							$('#example3').DataTable().ajax.reload();
					    });
					    
					   	$("#idRefresh4").click(function()
					    {
							$('#example4').DataTable().ajax.reload();
					    });
					    
					   	$("#idRefresh5").click(function()
					    {
							$('#example5').DataTable().ajax.reload();
					    });

					    $("#idRefresh6").click(function()
					    {
							$('#example6').DataTable().ajax.reload();
					    });

					    $("#idRefresh7").click(function()
					    {
							$('#example7').DataTable().ajax.reload();
					    });
					});

					function setType(tabType)
					{
						if(tabType == 1)
						{
							document.getElementById('itm1').style.display	= 'block';
							document.getElementById('itm2').style.display	= 'none';
							document.getElementById('itm3').style.display	= 'none';
							document.getElementById('itm4').style.display	= 'none';
							document.getElementById('itm5').style.display	= 'none';
							document.getElementById('itm6').style.display	= 'none';
						}
						else if(tabType == 2)
						{
							document.getElementById('itm1').style.display	= 'none';
							document.getElementById('itm2').style.display	= 'block';
							document.getElementById('itm3').style.display	= 'none';
							document.getElementById('itm4').style.display	= 'none';
							document.getElementById('itm5').style.display	= 'none';
							document.getElementById('itm6').style.display	= 'none';
						}
						else if(tabType == 3)
						{
							document.getElementById('itm1').style.display	= 'none';
							document.getElementById('itm2').style.display	= 'none';
							document.getElementById('itm3').style.display	= 'block';
							document.getElementById('itm4').style.display	= 'none';
							document.getElementById('itm5').style.display	= 'none';
							document.getElementById('itm6').style.display	= 'none';
						}
						else if(tabType == 4)
						{
							document.getElementById('itm1').style.display	= 'none';
							document.getElementById('itm2').style.display	= 'none';
							document.getElementById('itm3').style.display	= 'none';
							document.getElementById('itm4').style.display	= 'block';
							document.getElementById('itm5').style.display	= 'none';
							document.getElementById('itm6').style.display	= 'none';
						}
						else if(tabType == 5)
						{
							document.getElementById('itm1').style.display	= 'none';
							document.getElementById('itm2').style.display	= 'none';
							document.getElementById('itm3').style.display	= 'none';
							document.getElementById('itm4').style.display	= 'none';
							document.getElementById('itm5').style.display	= 'block';
							document.getElementById('itm6').style.display	= 'none';
						}
						else if(tabType == 6)
						{
							document.getElementById('itm1').style.display	= 'none';
							document.getElementById('itm2').style.display	= 'none';
							document.getElementById('itm3').style.display	= 'none';
							document.getElementById('itm4').style.display	= 'none';
							document.getElementById('itm5').style.display	= 'none';
							document.getElementById('itm6').style.display	= 'block';
						}
					}
				</script>
	    	<!-- ============ END MODAL ITEM =============== -->

	    	<!-- ============ START MODAL CANCEL ITEM =============== -->
	    		<div class="modal fade" id="mdl_delItm" name='mdl_delItm' role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		            <div class="modal-dialog">
			            <div class="modal-content">
			                <div class="modal-body">
								<div class="row">
							    	<div class="col-md-12">
							            <div class="box-body">
	                                    	<form method="post" name="frmSearch1" id="frmSearch1" action="">
	                                        	<div class="col-md-12">
							                    	<div class="row">
								                    	<div class="col-md-3" style="white-space: nowrap;">
								                    		<?php echo "<strong>$ItemCode</strong>"; ?>
								                    	</div>
								                    	<div class="col-md-9" style="white-space: nowrap;">
								                    		<?php echo "<strong>$Description</strong>"; ?>
								                    	</div>
							                    	</div>
								                  	<div class="row">
								                    	<div class="col-md-3" style="white-space: nowrap;">
								                    		<i style='font-size: 14px;' id="itmCode"></i>
								                    	</div>
								                    	<div class="col-md-9" style="white-space: nowrap;">
								                    		<i style='font-size: 14px;' id="itmName"></i>
								                    	</div>
							                    	</div>
								                  	<div class="row">
								                    	<div class="col-md-3">&nbsp;</div>
								                    	<div class="col-md-9" style="white-space: nowrap;">
								                    		<i style='font-size: 14px;' id="jobName"></i>
								                    	</div>
							                    	</div>
								                  	<div class="row">
								                    	<div class="col-md-3">&nbsp;</div>
								                    	<div class="col-md-9">
								                    		<div class="row">
										                    	<div class="col-md-4" style="text-align: right; white-space: nowrap;">
										                    		<?php echo "<i><strong>$Planning:</strong></i><br><i class='text-primary' style='font-size: 16px;' id='itmPRVol'><strong></strong></i>"; ?>
										                    	</div>
										                    	<div class="col-md-4" style="text-align: right; white-space: nowrap;">
										                    		<?php echo "<i><strong>$Requested:</strong></i><br><i class='text-yellow' style='font-size: 16px;' id='itmPOVol'><strong></strong></i>"; ?>
										                    	</div>
										                    	<div class="col-md-4" style="text-align: right; white-space: nowrap;">
										                    		<?php echo "<i><strong>$Remain:</strong></i><br><i class='text-yellow' style='font-size: 16px;' id='itmREMVol'><strong></strong></i>"; ?>
										                    	</div>
									                    	</div>
								                    	</div>
							                    	</div>
							                    	<br>
								                  	<div class="row">
								                    	<div class="col-md-3" style="white-space: nowrap;">&nbsp;</div>
								                    	<div class="col-md-9" style="white-space: nowrap;">
										                    <?php echo "<i><strong>$alert11</strong></i>"; ?>
								                    	</div>
							                    	</div>
								                  	<div class="row">
								                    	<div class="col-md-3" style="white-space: nowrap;">
								                    		<?php echo "<strong>Vol. $Cancel</strong>"; ?>
								                    	</div>
								                    	<div class="col-md-9">
															<div class="input-group">
																<input type="text" name="WO_CVOLX" id="WO_CVOLX" value="" class="form-control" style="min-width:80px; max-width:110px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgCncVol(this.value);" >
																<input type="hidden" name="itmRow" id="itmRow" value="">
																<input type="hidden" name="WO_RVOL" id="WO_RVOL" value="">
																<input type="hidden" name="WO_CVOL" id="WO_CVOL" value="">
																&nbsp;
																<button type="button" class="btn btn-warning" onClick="proc_cnc()"><i class="fa fa-save"></i></button>
															</div>
								                    	</div>
							                    	</div>
							                    </div>
	                                        </form>
                                      	</div>
                                      	<input type="hidden" name="rowCheck" id="rowCheck" value="0">
                                      	<button type="button" id="idCloseDRow" class="btn btn-default" data-dismiss="modal" style="display: none;">Close</button>
	                                </div>
	                            </div>
			                </div>
				        </div>
				    </div>
				</div>
	    	<!-- ============ END MODAL CANCEL ITEM =============== -->
        	<?php
				$act_lnk = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        		if($DefEmp_ID == 'D15040004221')
                	echo "<font size='1'><i>$act_lnk</i></font>";
            ?>
		</section>
	</body>
</html>

<script>
	<?php
		// START : GENERATE MANUAL CODE
			if($task == 'add')
			{
				?>
					$(document).ready(function()
					{
						setInterval(function(){addUCODE()}, 1000);
					});
				<?php
			}
		// END : GENERATE MANUAL CODE
	?>

	function addUCODE()
	{
		var task 		= "<?=$task?>";
		var DOCNUM		= document.getElementById('WO_NUM').value;
		var DOCCODE		= document.getElementById('WO_CODE').value;
		var DOCDATE		= document.getElementById('datepicker').value;
		var ACC_ID		= "";
		var PDManNo 	= "";
		var DOCTYPE 	= "WO";
		var DOCCAT 		= document.getElementById('WO_CATEG').value;

		var formData 	= {
							PRJCODE 		: "<?=$PRJCODE?>",
							MNCODE 			: "<?=$MenuCode?>",
							DOCNUM 			: DOCNUM,
							DOCCODE 		: DOCCODE,
							DOCCODE2		: PDManNo,
							DOCDATE 		: DOCDATE,
							ACC_ID 			: ACC_ID,
							DOCTYPE 		: DOCTYPE,
							DOCCAT 			: DOCCAT
						};
		$.ajax({
            type: 'POST',
            url: "<?php echo site_url('__l1y/getLastDocNum')?>",
            data: formData,
            success: function(response)
            {
            	console.log(response)
            	var arrVar 	= response.split('~');
            	var docNum 	= arrVar[0];
            	var docCode	= arrVar[1];
            	var payCode = arrVar[2];
            	var ACCBAL 	= arrVar[3];

            	$('#WO_CODE').val(docCode);
            	$('#WO_CODEX').val(docCode);
            }
        });
	}

	$(function () {
		//Initialize Select2 Elements
		$(".select2").select2();

		//Datemask dd/mm/yyyy
		$("#datemask").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});
		//Datemask2 mm/dd/yyyy
		$("#datemask2").inputmask("mm/dd/yyyy", {"placeholder": "mm/dd/yyyy"});
		//Money Euro
		$("[data-mask]").inputmask();

		//Date range picker
		$('#reservation').daterangepicker();
		//Date range picker with time picker
		$('#reservationtime').daterangepicker({timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A'});
		//Date range as a button
		$('#daterange-btn').daterangepicker(
			{
			  ranges: {
				'Today': [moment(), moment()],
				'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
				'Last 7 Days': [moment().subtract(6, 'days'), moment()],
				'Last 30 Days': [moment().subtract(29, 'days'), moment()],
				'This Month': [moment().startOf('month'), moment().endOf('month')],
				'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
			  },
			  startDate: moment().subtract(29, 'days'),
			  endDate: moment()
			},
			function (start, end) {
			  $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
			}
		);

		//Date picker
		$.fn.datepicker.defaults.format = "dd/mm/yyyy";
		$('#datepicker').datepicker({
		  autoclose: true
		});

		//Date picker
		$('#datepicker1').datepicker({
		  autoclose: true
		});
		
		$('#datepicker2').datepicker({
		  autoclose: true
		});

		//iCheck for checkbox and radio inputs
		$('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
		  checkboxClass: 'icheckbox_minimal-blue',
		  radioClass: 'iradio_minimal-blue'
		});
		//Red color scheme for iCheck
		$('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
		  checkboxClass: 'icheckbox_minimal-red',
		  radioClass: 'iradio_minimal-red'
		});
		//Flat red color scheme for iCheck
		$('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
		  checkboxClass: 'icheckbox_flat-green',
		  radioClass: 'iradio_flat-green'
		});

		//Colorpicker
		$(".my-colorpicker1").colorpicker();
		//color picker with addon
		$(".my-colorpicker2").colorpicker();

		//Timepicker
		$(".timepicker").timepicker({
		  showInputs: false
		});

		/*$(document).bind('keydown keyup', function(e) {
		    if(e.which === 116) {
		       console.log('blocked');
		       return false;
		    }
		    if(e.which === 82 && e.ctrlKey) {
		       console.log('blocked');
		       return false;
		    }
		});*/

		$('#frm').validate({
	    	submitHandler: function(form)
	    	{
	    		WO_CATEG	= document.getElementById("WO_CATEG").value;
				FPA_CODE	= document.getElementById("FPA_CODE1").value;
				if(WO_CATEG == 'SALT' && FPA_CODE == '')
				{
					swal('<?php echo $alert5; ?>',
					{
						icon:"warning",
					})
					.then(function()
					{
						document.getElementById('FPA_CODE1').focus();
					});
					return false;
				}

				SPLCODE		= document.getElementById('SPLCODE').value;
				if(SPLCODE == '0')
				{
					swal('<?php echo $alert4; ?>',
					{
						icon:"warning",
					})
					.then(function()
					{
						document.getElementById('SPLCODE').focus();
					});
					return false;
				}


				PR_REFNO1	= $('#PR_REFNO').val();
				if(PR_REFNO1 == null)
				{
					swal('<?php echo $alert3; ?>',
					{
						icon:"warning",
					})
					.then(function()
					{
						document.getElementById('PR_REFNO').focus();
					});
					return false;
				}

				PR_REFNO 	= PR_REFNO1.join("~");
				PRJCODE		= document.getElementById('PRJCODE').value;

				if(PR_REFNO == '')
				{
					swal('<?php echo $alert3; ?>',
					{
						icon:"warning",
					})
					.then(function()
					{
						document.getElementById('PR_REFNO').focus();
					});
					return false;
				}

				var totrow 		= document.getElementById('totalrow').value;

				for(i=1;i<=totrow;i++)
				{
					let myObj 	= document.getElementById('WO_VOLM'+i);
					var values 	= typeof myObj !== 'undefined' ? myObj : '';

					//console.log(i+' = '+ values)
					
					if(values != null)
					{
						var WO_VOLM	= parseFloat(eval(document.getElementById('WO_VOLM'+i)).value.split(",").join(""));
						if(WO_VOLM == 0)
						{
							swal('<?php echo $alert1; ?>',
							{
								icon:"warning",
							})
							.then(function()
							{
								document.getElementById('WO_VOLM'+i).value = '0';
								document.getElementById('WO_VOLM'+i).focus();
							});
							return false;
						}
					}
				}

				WO_DPREF1	= document.getElementById("WO_DPREF1").value;
				if(WO_DPREF1 != '')
				{
					WO_DPPER	= document.getElementById('WO_DPPER').value;
					if(WO_DPPER == 0)
					{
						swal('<?php echo $alert7; ?>',
						{
							icon:"warning",
						})
						.then(function()
						{
							document.getElementById('WO_DPPER').focus();
						});
						return false;
					}
				}

				WO_DPPER	= document.getElementById("WO_DPPER").value;
				/*if(WO_DPPER != 0)
				{
					WO_DPREF1	= document.getElementById('WO_DPREF1').value;
					if(WO_DPREF1 == '')
					{
						swal('<?php echo $alert8; ?>');
						document.getElementById('WO_DPREF1').focus();
						return false;
					}
				}*/

				WO_STAT		= document.getElementById("WO_STAT").value;
				if(WO_STAT == 6)
				{
					WO_MEMO		= document.getElementById('WO_MEMO').value;
					if(WO_MEMO == '')
					{
						swal('<?php echo $alert6; ?>',
						{
							icon:"warning",
						})
						.then(function()
						{
							document.getElementById('WO_MEMO').focus();
						});
						return false;
					}
				}

				if(totrow == 0)
				{
					swal('<?php echo $alert2; ?>',
					{
						icon:"warning",
					});
					return false;
				}

				if($(form).data('submitted')==true){
			      swal('<?php echo $alertSubmit;?>');
			      return false;
			    } else {
			      //swal('submitting');
			      $(form).data('submitted', true);
			      return true;
			    }
	    	}
	    });
	});

	var selectedRows = 0;
	function check_all(chk)
	{
		var totRow = document.getElementById('totalrow').value;
		if(chk.checked == true)
		{
			for(i=1;i<=totRow;i++)
			{
				var aaaa = document.getElementById('data['+i+'][chk]').checked = true;
			}
		}
		else
		{
			for(i=1;i<=totRow;i++)
			{
				var aaaa = document.getElementById('data['+i+'][chk]').checked = false;
			}
		}
	}

	var selectedRows = 0;
	function pickThis(thisobj,ke)
	{
		if(thisobj.checked)
		{
			document.getElementById('chk'+thisobj.value).checked = true;
		}
		else
		{
			document.getElementById('chk'+thisobj.value).checked = false;
		}

		objTable = document.getElementById('tbl');
		intTable = objTable.rows.length;
		var NumOfRows = intTable-1;
		if (thisobj!= '')
		{
			if (thisobj.checked) selectedRows++;
			else selectedRows--;
		}

		if (selectedRows==NumOfRows)
		{
			document.frm.HChkAllItem.checked = true;
		}
		else
		{
			document.frm.HChkAllItem.checked = false;
		}
	}

	function deleteRow(btn)
	{
		var row = document.getElementById("tr_" + btn);
		row.remove();
	}

	function setAllPPN(PPNCODE)
	{
		PPNPERC 	= 0;
		var url		= "<?php echo $secGetPPn; ?>";
		$.ajax({
			type: 'POST',
			url: url,
			data: {PPNCODE:PPNCODE},
			success: function(response)
			{
				PPNPERC = response;
			}
		});

		var totrow 		= document.getElementById('totalrow').value;
		var i = 0;
		for(i=1;i<=totrow;i++)
		{
			let myObj 	= document.getElementById('TAXCODE1'+i);
			var values 	= typeof myObj !== 'undefined' ? myObj : '';
			
			if(values != null)
			{
				$('#TAXCODE1'+i).val(PPNCODE).trigger('change');
				var decFormat	= document.getElementById('decFormat').value;
				console.log('PPN i = '+i)
				if(PPNCODE == '')
				{
					WO_VOLM			= parseFloat(document.getElementById('data'+i+'WO_VOLM').value);
					WO_PRICE		= parseFloat(document.getElementById('data'+i+'ITM_PRICE').value);
					PPH_VAL			= parseFloat(document.getElementById('data'+i+'TAXPRICE2').value);

					WO_TOTAL 		= parseFloat(WO_VOLM) * parseFloat(WO_PRICE);
					PPN_VAL 		= parseFloat(0);

					document.getElementById('data'+i+'TAXPERC1').value	= parseFloat(0);
					document.getElementById('TAXPERC1'+i).value	= RoundNDecimal(parseFloat(Math.abs(0)), 2);
					document.getElementById('data'+i+'TAXPRICE1').value	= parseFloat(0);

					WOTOTAL 		= parseFloat(WO_TOTAL + PPN_VAL - PPH_VAL);
					document.getElementById('data'+i+'WO_TOTAL2').value 	= RoundNDecimal(parseFloat(Math.abs(WOTOTAL)), 2);
					document.getElementById('WO_TOTAL2X'+i).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(WOTOTAL)), 2));
				}
				else
				{
					WO_VOLM			= parseFloat(document.getElementById('data'+i+'WO_VOLM').value);
					WO_PRICE		= parseFloat(document.getElementById('data'+i+'ITM_PRICE').value);
					PPH_VAL			= parseFloat(document.getElementById('data'+i+'TAXPRICE2').value);

					WO_TOTAL 		= parseFloat(WO_VOLM) * parseFloat(WO_PRICE);
					PPN_VAL 		= parseFloat(WO_TOTAL) * parseFloat(PPNPERC) / 100;

					document.getElementById('data'+i+'TAXPERC1').value	= parseFloat(PPNPERC);
					document.getElementById('TAXPERC1'+i).value	= RoundNDecimal(parseFloat(Math.abs(PPNPERC)), 2);
					document.getElementById('data'+i+'TAXPRICE1').value	= PPN_VAL;

					WOTOTAL 		= parseFloat(WO_TOTAL + PPN_VAL - PPH_VAL);
					document.getElementById('data'+i+'WO_TOTAL2').value 	= RoundNDecimal(parseFloat(Math.abs(WOTOTAL)), 2);
					document.getElementById('WO_TOTAL2X'+i).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(WOTOTAL)), 2));
				}
			}
		}
		getTotalWO();
	}

	function setAllPPH(PPHCODE)
	{
		PPHPERC 	= 0;
		var url		= "<?php echo $secGetPPh; ?>";
		$.ajax({
			type: 'POST',
			url: url,
			data: {PPHCODE:PPHCODE},
			success: function(response)
			{
				PPHPERC 	= 0;
			}
		});

		var decFormat	= document.getElementById('decFormat').value;
		var totrow 		= document.getElementById('totalrow').value;
		var i = 0;
		console.log('totrow = '+totrow)
		for(i=1;i<=totrow;i++)
		{
			let myObj 	= document.getElementById('TAXCODE2'+i);
			var values 	= typeof myObj !== 'undefined' ? myObj : '';
			
			if(values != null)
			{
				$('#TAXCODE2'+i).val(PPHCODE).trigger('change');

				if(PPHCODE == '')
				{
					WO_VOLM			= parseFloat(document.getElementById('data'+i+'WO_VOLM').value);
					WO_PRICE		= parseFloat(document.getElementById('data'+i+'ITM_PRICE').value);
					PPN_VAL			= parseFloat(document.getElementById('data'+i+'TAXPRICE1').value);

					WO_TOTAL 		= parseFloat(WO_VOLM) * parseFloat(WO_PRICE);
					PPH_VAL 		= parseFloat(0);


					document.getElementById('data'+i+'TAXPERC2').value	= parseFloat(0);
					document.getElementById('TAXPERC2'+i).value	= RoundNDecimal(parseFloat(Math.abs(0)), 2);
					document.getElementById('data'+i+'TAXPRICE2').value	= parseFloat(0);

					WOTOTAL 		= parseFloat(WO_TOTAL + PPN_VAL - PPH_VAL);
					document.getElementById('data'+i+'WO_TOTAL2').value 	= RoundNDecimal(parseFloat(Math.abs(WOTOTAL)), 2);
					document.getElementById('WO_TOTAL2X'+i).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(WOTOTAL)), 2));
				}
				else
				{
					WO_VOLM			= parseFloat(document.getElementById('data'+i+'WO_VOLM').value);
					WO_PRICE		= parseFloat(document.getElementById('data'+i+'ITM_PRICE').value);
					PPN_VAL			= parseFloat(document.getElementById('data'+i+'TAXPRICE1').value);

					WO_TOTAL 		= parseFloat(WO_VOLM) * parseFloat(WO_PRICE);
					PPH_VAL 		= parseFloat(WO_TOTAL) * parseFloat(PPHPERC) / 100;

					document.getElementById('data'+i+'TAXPERC2').value	= parseFloat(PPHPERC);
					document.getElementById('TAXPERC2'+i).value	= RoundNDecimal(parseFloat(Math.abs(PPHPERC)), 2);
					document.getElementById('data'+i+'TAXPRICE2').value	= PPH_VAL;

					WOTOTAL 		= parseFloat(WO_TOTAL + PPN_VAL - PPH_VAL);
					document.getElementById('data'+i+'WO_TOTAL2').value 	= RoundNDecimal(parseFloat(Math.abs(WOTOTAL)), 2);
					document.getElementById('WO_TOTAL2X'+i).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(WOTOTAL)), 2));
				}
			}
		}
		getTotalWO();
	}

	function add_DP(strItem)
	{
		arrItem = strItem.split('|');
		DP_NUM		= arrItem[0];
		DP_CODE 	= arrItem[1];
		DP_AMOUNT 	= arrItem[2];
		document.getElementById('WO_DPREF').value	= DP_NUM;
		document.getElementById('WO_DPREF1').value	= DP_CODE;
		document.getElementById('WO_DPVAL').value	= DP_AMOUNT;
	}

	function add_FPA(strItem)
	{
		arrItem = strItem.split('|');
		WO_NUM		= arrItem[0];
		WO_CODE 	= arrItem[1];
		document.getElementById('FPA_NUM').value	= WO_NUM;
		document.getElementById('FPA_CODE1').value	= WO_CODE;
	}

	function add_item(strItem)
	{
		arrItem = strItem.split('|');
		var objTable, objTR, objTD, intIndex, arrItem;
		var WO_NUMX 	= "<?php echo $WO_NUM; ?>";

		var WO_CODEx 	= "<?php echo $WO_CODE; ?>";
		ilvl = arrItem[1];

		var decFormat	= document.getElementById('decFormat').value;

		/*validateDouble(arrItem[0],arrItem[1])
		if(validateDouble(arrItem[0],arrItem[1]))
		{
			swal("Double Item for " + arrItem[0]);
			return;
		}*/

		// FIELD LIST
			JOBCODEDET 		= arrItem[0];
			JOBCODEID 		= arrItem[1];
			JOBCODE 		= arrItem[2];
			PRJCODE 		= arrItem[3];
			ITM_CODE 		= arrItem[4];
			ITM_NAME 		= arrItem[5];
			ITM_SN			= arrItem[6];
			ITMUNIT 		= arrItem[7];
			ITM_PRICE 		= parseFloat(arrItem[8]);
			ITM_PRICEV		= parseFloat(ITM_PRICE);
			ITM_VOLM 		= parseFloat(arrItem[9]);	// ITM_VOLM_QTY = $ITM_VOLM + $ADD_VOLM;
			ITM_BUDG_VOL 	= parseFloat(arrItem[9]);	// ITM_VOLM_QTY = $ITM_VOLM + $ADD_VOLM;
			ITM_BUDG_AMN 	= parseFloat(arrItem[10]);	// ITM_VOLM_AMN = $ITM_BUDG + $ADD_JOBCOST;
			ITM_USED 		= parseFloat(arrItem[11]);
			ITM_USED_AM 	= parseFloat(arrItem[12]);
			tempTotMax		= parseFloat(arrItem[13]);
			PO_VOLM			= parseFloat(arrItem[14]);
			PO_AMOUNT		= parseFloat(arrItem[15]);
			WO_QTY			= parseFloat(arrItem[16]);
			WO_AMOUNT		= parseFloat(arrItem[17]);
			OPN_QTY			= parseFloat(arrItem[18]);
			OPN_AMOUNT		= parseFloat(arrItem[19]);
			TOT_USED_QTY	= parseFloat(arrItem[20]);
			TOT_USED_AMN	= parseFloat(arrItem[21]);
			ITM_STOCK		= parseFloat(arrItem[22]);
			ITM_STOCK_AMN	= parseFloat(arrItem[23]);
			ITM_REM_VOL		= parseFloat(arrItem[24]);
			ITM_REM_AMN		= parseFloat(arrItem[25]);
			JOBPARDESC		= parseFloat(arrItem[26]);
			JOB_ACC_UM		= parseFloat(arrItem[27]);
			TOT_USEDQTY		= parseFloat(arrItem[17]);
			ITM_LASTP 		= arrItem[28];
			/*WO_QTY			= arrItem[18];
			WO_AMOUNT		= arrItem[19];
			OPN_QTY			= arrItem[20];
			OPN_AMOUNT		= arrItem[21];*/

			let DescAcc 	= '<div style="font-style: italic;"><i class="text-muted fa fa-chevron-circle-right"></i>&nbsp;&nbsp;'+JOB_ACC_UM+'</div>';
			let ITM_LASTPV 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_LASTP)), 2));
			let DescLastP 	= '<span class="text-red" style="font-style: italic; font-weight: bold;">&nbsp;(LastP : '+ITM_LASTPV+')</span>';

		itemConvertion	= 1;

		ITM_UNIT		= ITMUNIT.toUpperCase();
		// VIEW ONLY
			if(ITM_UNIT == 'LS' )
			{
				ITM_BUDGV	= ITM_BUDG_AMN;
				TOT_USEDQTY	= TOT_USED_AMN;
			}
			else
			{
				ITM_BUDGV 	= ITM_BUDG_VOL;
				TOT_USEDQTY	= TOT_USED_QTY;
			}
		
		// START : SETTING ROWS INDEX
			intIndexA 		= parseFloat(document.getElementById('totalrow').value);
			intIndex 		= parseInt(intIndexA)+1;
			objTable 		= document.getElementById('tbl');
			intTable 		= objTable.rows.length;

			document.frm.rowCount.value = intIndex;
			
			objTR 			= objTable.insertRow(intTable);
			objTR.id 		= 'tr_' + intIndex;
		// START : SETTING ROWS INDEX

		console.log('b')
		// Checkbox -- WO_NUM, WO_CODE, JOBCODEDET, JOBCODEID
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = "center";
			objTD.noWrap = true;
			objTD.innerHTML = '<a href="#" onClick="deleteRow('+intIndex+')" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a><input type="hidden" id="chk" name="chk" value="'+intIndex+'" width="10" size="15" readonly class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'WO_NUM" name="data['+intIndex+'][WO_NUM]" value="'+WO_NUMX+'" class="form-control"><input type="hidden" id="data'+intIndex+'WO_CODE" name="data['+intIndex+'][WO_CODE]" value="'+WO_CODEx+'" class="form-control"><input type="hidden" name="data['+intIndex+'][JOBCODEDET]" id="data'+intIndex+'JOBCODEDET" value="'+JOBCODEDET+'" class="form-control" ><input type="hidden" name="data['+intIndex+'][JOBCODEID]" id="data'+intIndex+'JOBCODEID" value="'+JOBCODEID+'" class="form-control" >';

		console.log('c')
		// Item Code -- ITM_CODE, SNCODE,
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.display = 'none';
			objTD.innerHTML = ''+ITM_CODE+'<input type="hidden" id="data'+intIndex+'ITM_CODE" name="data['+intIndex+'][ITM_CODE]" value="'+ITM_CODE+'" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'SNCODE" name="data['+intIndex+'][SNCODE]" value="'+ITM_SN+'" class="form-control" style="max-width:300px;">';

		console.log('d')
		// Item Name
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = 'left';
			objTD.noWrap = true;
			objTD.innerHTML = JOBCODEID+' - '+ITM_NAME+DescLastP+DescAcc;

		console.log('e')
		// Item Budget -- ITM_BUDGV, ITM_BUDG_VOL, ITM_BUDG_AMN
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = ''+doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_BUDGV)), 2))+'<input type="hidden" id="data'+intIndex+'ITM_BUDG_VOL" name="data['+intIndex+'][ITM_BUDG_VOL]" value="'+ITM_BUDG_VOL+'"><input type="hidden" id="data'+intIndex+'ITM_BUDG_AMN" name="data['+intIndex+'][ITM_BUDG_AMN]" value="'+ITM_BUDG_AMN+'"><input type="hidden" name="ITM_STOCK'+intIndex+'" id="ITM_STOCK'+intIndex+'" value="'+ITM_STOCK+'" class="form-control" style="text-align:right" ><input type="hidden" name="ITM_STOCK_AMN'+intIndex+'" id="ITM_STOCK_AMN'+intIndex+'" value="'+ITM_STOCK_AMN+'" class="form-control" style="text-align:right" ><input type="hidden" name="ITM_REM_VOL'+intIndex+'" id="ITM_REM_VOL'+intIndex+'" value="'+ITM_REM_VOL+'" class="form-control" style="text-align:right" ><input type="hidden" name="ITM_REM_AMN'+intIndex+'" id="ITM_REM_AMN'+intIndex+'" value="'+ITM_REM_AMN+'" class="form-control" style="text-align:right" >';

		console.log('f')
		// Item Worked FOR INFORMATION ONLY : TOT_USEDQTY
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = ''+doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TOT_USEDQTY)), 2))+'<input type="hidden" class="form-control" style="text-align:right" name="TOT_USEDQTY'+intIndex+'" id="TOT_USEDQTY'+intIndex+'" value="'+TOT_USEDQTY+'" >';

		console.log('g')
		// Item Worked Now -- WO_VOLM (NOW) : WO_VOLM
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			/*if(ITM_UNIT == 'LS' || ITM_UNIT == 'LUMP')
			{
				WOVOLM 			= parseFloat(1);
				objTD.innerHTML = '<input type="text" name="WO_VOLM'+intIndex+'" id="WO_VOLM'+intIndex+'" value="1.00" class="form-control" style="min-width:100px; max-width:100px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getWOVol(this,'+intIndex+');" readonly ><input type="hidden" name="data['+intIndex+'][WO_VOLM]" id="data'+intIndex+'WO_VOLM" value="1" class="form-control" style="max-width:300px;" >';
			}
			else
			{*/
				WOVOLM 			= parseFloat(ITM_REM_VOL);
				objTD.innerHTML = '<input type="text" name="WO_VOLM'+intIndex+'" id="WO_VOLM'+intIndex+'" value="'+doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_REM_VOL)), 2))+'" class="form-control" style="min-width:100px; max-width:100px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getWOVol(this,'+intIndex+');" ><input type="hidden" name="data['+intIndex+'][WO_VOLM]" id="data'+intIndex+'WO_VOLM" value="'+ITM_REM_VOL+'" class="form-control" style="max-width:300px;" >';
			//}
			
		console.log('h')
		// Item Price -- ITM_PRICE
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			if(ITM_UNIT == 'LS' || ITM_UNIT == 'LUMP')
			{
				WOPRICE 			= parseFloat(ITM_PRICE);
				objTD.innerHTML = '<input type="text" name="ITM_PRICE'+intIndex+'" id="ITM_PRICE'+intIndex+'" value="'+doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_PRICEV)), 2))+'" class="form-control" style="min-width:100px; max-width:100px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getWOPrice(this,'+intIndex+');"><input type="hidden" name="data['+intIndex+'][ITM_PRICE]" id="data'+intIndex+'ITM_PRICE" value="'+ITM_PRICE+'" class="form-control" style="min-width:100px; max-width:100px;" ><input type="hidden" style="text-align:right" name="itemConvertion'+intIndex+'" id="itemConvertion'+intIndex+'" value="'+itemConvertion+'" >';
			}
			else
			{
				WOPRICE 			= parseFloat(ITM_PRICE);
				objTD.innerHTML = '<input type="text" name="ITM_PRICE'+intIndex+'" id="ITM_PRICE'+intIndex+'" value="'+doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_PRICEV)), 2))+'" class="form-control" style="min-width:100px; max-width:100px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getWOPrice(this,'+intIndex+');" ><input type="hidden" name="data['+intIndex+'][ITM_PRICE]" id="data'+intIndex+'ITM_PRICE" value="'+ITM_PRICE+'" class="form-control" style="min-width:100px; max-width:100px;" ><input type="hidden" style="text-align:right" name="itemConvertion'+intIndex+'" id="itemConvertion'+intIndex+'" value="'+itemConvertion+'" >';
			}

		WO_TOTAL 		= parseFloat(WOVOLM) * parseFloat(WOPRICE);

		console.log('i')
		// Item Unit Type -- ITM_UNIT
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'center';
			objTD.innerHTML = ''+ITM_UNIT+'<input type="hidden" name="data['+intIndex+'][ITM_UNIT]" id="data'+intIndex+'ITM_UNIT" value="'+ITM_UNIT+'" class="form-control" style="max-width:300px;" >';

		// WO JUMLAH PRICE X VOLM -- WO_TOTAL
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = '<input type="hidden" name="data['+intIndex+'][WO_TOTAL]" id="data'+intIndex+'WO_TOTAL" value="'+WO_TOTAL+'" class="form-control" style="min-width:100px; max-width:150px; text-align:right" onKeyPress="return isIntOnlyNew(event);" ><input type="text" class="form-control" style="min-width:100px; max-width:100px; text-align:right" name="WO_TOTAL'+intIndex+'" id="WO_TOTAL'+intIndex+'" value="'+doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(WO_TOTAL)), 2))+'" size="10" disabled >';

		console.log('j')
		// discount -- WO_DISC (HIDE)
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.style.display = 'none';
			objTD.innerHTML = '<input type="number" name="WO_DISC'+intIndex+'" id="WO_DISC'+intIndex+'" min="0" max="100" step="0.01" value="0.00" class="form-control" style="min-width:100px; max-width:100px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getDiscount(this,'+intIndex+');" ><input type="hidden" name="data['+intIndex+'][WO_DISC]" id="data'+intIndex+'WO_DISC" value="0.00" class="form-control" style="max-width:300px;" >';

		console.log('k')
		// discount Price : WO_DISCP (HIDE)
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.style.display = 'none';
			objTD.innerHTML = '<input type="text" name="WO_DISCP'+intIndex+'" id="WO_DISCP'+intIndex+'" value="0.00" class="form-control" style="min-width:100px; max-width:100px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getDiscountP(this,'+intIndex+');" ><input type="hidden" name="data['+intIndex+'][WO_DISCP]" id="data'+intIndex+'WO_DISCP" value="0.00" class="form-control" style="max-width:300px;" >';

		// % PPn
		objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = '<input type="text" name="TAXPERC1'+intIndex+'" id="TAXPERC1'+intIndex+'"  value="" class="form-control" style="min-width:70px; max-width:70px;" disabled><input type="hidden" name="data['+intIndex+'][TAXPERC1]" id="data'+intIndex+'TAXPERC1"  value="" class="form-control" style="min-width:70px; max-width:70px;">';

		console.log('l')
		// Tax PPN -- TAXCODE1, TAXPRICE1
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = '<select name="data['+intIndex+'][TAXCODE1]" id="TAXCODE1'+intIndex+'" class="form-control" style="min-width: 80px; max-width:150px" onChange="getWOPPN(this.value,'+intIndex+');"><option value=""> --- </option><?php $s_01 	= "SELECT TAXLA_NUM, TAXLA_DESC FROM tbl_tax_ppn"; $r_01 	= $this->db->query($s_01)->result(); foreach($r_01 as $rw_01): $PPN_NUM 	= $rw_01->TAXLA_NUM; $PPN_DESC = $rw_01->TAXLA_DESC; ?> <option value="<?php echo $PPN_NUM?>"><?php echo $PPN_DESC?></option> <?php endforeach; ?></select><input type="hidden" name="data['+intIndex+'][TAXPRICE1]" id="data'+intIndex+'TAXPRICE1" size="20" value="0" class="form-control" style="min-width:110px; max-width:300px; text-align:left">';

		// % PPh
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = '<input type="text" name="TAXPERC2'+intIndex+'" id="TAXPERC2'+intIndex+'"  value="" class="form-control" style="min-width:70px; max-width:70px;" disabled><input type="hidden" name="data['+intIndex+'][TAXPERC2]" id="data'+intIndex+'TAXPERC2"  value="" class="form-control" style="min-width:70px; max-width:70px;">';

		console.log('m')
		// Tax PPH -- TAXCODE2, TAXPRICE2
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = '<select name="data['+intIndex+'][TAXCODE2]" id="TAXCODE2'+intIndex+'" class="form-control" style="min-width: 80px; max-width:150px" onChange="getWOPPH(this.value,'+intIndex+');"><option value=""> --- </option><?php $s_02 	= "SELECT TAXLA_NUM, TAXLA_DESC FROM tbl_tax_la"; $r_02 	= $this->db->query($s_02)->result(); foreach($r_02 as $rw_02): $PPH_NUM 	= $rw_02->TAXLA_NUM; $PPH_DESC = $rw_02->TAXLA_DESC; ?> <option value="<?php echo $PPH_NUM?>"><?php echo $PPH_DESC?></option> <?php endforeach; ?></select><input type="hidden" name="data['+intIndex+'][TAXPRICE2]" id="data'+intIndex+'TAXPRICE2" size="20" value="0" class="form-control" style="min-width:110px; max-width:300px; text-align:left">';

		console.log('n')
		// Remarks -- WO_TOTAL2
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'center';
			objTD.innerHTML = '<input type="hidden" name="data['+intIndex+'][WO_TOTAL2]" id="data'+intIndex+'WO_TOTAL2" value="'+WO_TOTAL+'" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" ><input type="text" name="data'+intIndex+'WO_TOTAL2X" id="WO_TOTAL2X'+intIndex+'" value="'+doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(WO_TOTAL)), 2))+'" class="form-control" style="min-width:130px; max-width:130px; text-align:right;" onKeyPress="return isIntOnlyNew(event);" >';

		console.log('o')
		// Remarks -- WO_DESC
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'center';
			objTD.innerHTML = '<input type="text" name="data['+intIndex+'][WO_DESC]" id="data'+intIndex+'WO_DESC" size="20" value="'+ITM_NAME+'" class="form-control" style="min-width:130px; max-width:150px; text-align:left">';

		console.log('p')
		document.getElementById('totalrow').value = intIndex;
		getTotalWO();
	}

	function getWOVol(thisVal1, row)
	{
		var decFormat	= document.getElementById('decFormat').value;

		thisVal 		= parseFloat(Math.abs(eval(thisVal1).value.split(",").join("")));

		WO_VOLM			= parseFloat(thisVal);
		WO_PRICE		= parseFloat(document.getElementById('data'+row+'ITM_PRICE').value);
		WO_TOTAL 		= parseFloat(WO_VOLM) * parseFloat(WO_PRICE);

		ITM_REM_VOL		= parseFloat(document.getElementById('ITM_REM_VOL'+row).value);			// Remain Budget (Vol)
		ITM_REM_AMN		= parseFloat(document.getElementById('ITM_REM_AMN'+row).value);			// Remain Budget (Amn)
		
		ITMUNIT			= document.getElementById('data'+row+'ITM_UNIT').value;
		ITM_UNIT 		= ITMUNIT.toUpperCase();

		if(ITM_UNIT == 'LS')
		{
			if(WO_TOTAL > ITM_REM_AMN)
			{
				swal('<?php echo $alertGreat; ?>',
				{
					icon:"warning",
				})
				.then(function()
				{
					WO_VOLM 	= 0;
					document.getElementById('data'+row+'WO_VOLM').value		= 0;
					document.getElementById('WO_VOLM'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(0)), 2));
					document.getElementById('WO_VOLM'+row).focus();

					WO_TOTAL 	= parseFloat(WO_VOLM) * parseFloat(WO_PRICE);

					PPN_PERC	= parseFloat(document.getElementById('data'+row+'TAXPERC1').value);
					PPN_VAL 	= parseFloat(WO_TOTAL) * parseFloat(PPN_PERC) / 100;

					document.getElementById('data'+row+'WO_TOTAL').value 	= 0;
					document.getElementById('WO_TOTAL'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(0)), 2));
					document.getElementById('data'+row+'TAXPRICE1').value 	= 0;
					document.getElementById('data'+row+'TAXPRICE2').value 	= 0;
					document.getElementById('data'+row+'WO_TOTAL2').value 	= 0;
					document.getElementById('WO_TOTAL2X'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(0)), 2));
					getTotalWO();
				});
			}
			else
			{
				WO_TOTAL 	= parseFloat(WO_VOLM) * parseFloat(WO_PRICE);

				PPN_PERC	= parseFloat(document.getElementById('data'+row+'TAXPERC1').value);
				PPN_VAL 	= parseFloat(WO_TOTAL) * parseFloat(PPN_PERC) / 100;

				PPH_PERC	= parseFloat(document.getElementById('data'+row+'TAXPERC2').value);
				PPH_VAL 	= parseFloat(WO_TOTAL) * parseFloat(PPH_PERC) / 100;

				WO_TOTAL2 	= parseFloat(WO_TOTAL + PPN_VAL - PPH_VAL);

				document.getElementById('data'+row+'WO_VOLM').value		= WO_VOLM;
				document.getElementById('WO_VOLM'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(WO_VOLM)), 2));
				document.getElementById('data'+row+'WO_TOTAL').value 	= RoundNDecimal(parseFloat(Math.abs(WO_TOTAL)), 2);
				document.getElementById('WO_TOTAL'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(WO_TOTAL)), 2));
				document.getElementById('data'+row+'TAXPRICE1').value 	= PPN_VAL;
				document.getElementById('data'+row+'TAXPRICE2').value 	= PPH_VAL;
				document.getElementById('data'+row+'WO_TOTAL2').value 	= RoundNDecimal(parseFloat(Math.abs(WO_TOTAL2)), 2);
				document.getElementById('WO_TOTAL2X'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(WO_TOTAL2)), 2));
				getTotalWO();
			}
		}
		else
		{
			if(WO_TOTAL > ITM_REM_AMN)
			{
				swal('<?php echo $alertGreat; ?>',
				{
					icon:"warning",
				})
				.then(function()
				{
					WO_VOLM 	= parseFloat(ITM_REM_VOL);
					document.getElementById('data'+row+'WO_VOLM').value		= WO_VOLM;
					document.getElementById('WO_VOLM'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(WO_VOLM)), 2));
					document.getElementById('WO_VOLM'+row).focus();

					WO_TOTAL 	= parseFloat(WO_VOLM) * parseFloat(WO_PRICE);

					PPN_PERC	= parseFloat(document.getElementById('data'+row+'TAXPERC1').value);
					PPN_VAL 	= parseFloat(WO_TOTAL) * parseFloat(PPN_PERC) / 100;

					PPH_PERC	= parseFloat(document.getElementById('data'+row+'TAXPERC2').value);
					PPH_VAL 	= parseFloat(WO_TOTAL) * parseFloat(PPH_PERC) / 100;

					WO_TOTAL2 	= parseFloat(WO_TOTAL + PPN_VAL - PPH_VAL);

					document.getElementById('data'+row+'WO_VOLM').value		= WO_VOLM;
					document.getElementById('WO_VOLM'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(WO_VOLM)), 2));
					document.getElementById('data'+row+'WO_TOTAL').value 	= RoundNDecimal(parseFloat(Math.abs(WO_TOTAL)), 2);
					document.getElementById('WO_TOTAL'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(WO_TOTAL)), 2));
					document.getElementById('data'+row+'TAXPRICE1').value 	= PPN_VAL;
					document.getElementById('data'+row+'TAXPRICE2').value 	= PPH_VAL;
					document.getElementById('data'+row+'WO_TOTAL2').value 	= RoundNDecimal(parseFloat(Math.abs(WO_TOTAL2)), 2);
					document.getElementById('WO_TOTAL2X'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(WO_TOTAL2)), 2));
					getTotalWO();
				});
			}
			else
			{
				WO_TOTAL 	= parseFloat(WO_VOLM) * parseFloat(WO_PRICE);

				PPN_PERC	= parseFloat(document.getElementById('data'+row+'TAXPERC1').value);
				PPN_VAL 	= parseFloat(WO_TOTAL) * parseFloat(PPN_PERC) / 100;

				PPH_PERC	= parseFloat(document.getElementById('data'+row+'TAXPERC2').value);
				PPH_VAL 	= parseFloat(WO_TOTAL) * parseFloat(PPH_PERC) / 100;

				WO_TOTAL2 	= parseFloat(WO_TOTAL + PPN_VAL - PPH_VAL);

				document.getElementById('data'+row+'WO_VOLM').value		= WO_VOLM;
				document.getElementById('WO_VOLM'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(WO_VOLM)), 2));
				document.getElementById('data'+row+'WO_TOTAL').value 	= RoundNDecimal(parseFloat(Math.abs(WO_TOTAL)), 2);
				document.getElementById('WO_TOTAL'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(WO_TOTAL)), 2));
				document.getElementById('data'+row+'TAXPRICE1').value 	= PPN_VAL;
				document.getElementById('data'+row+'TAXPRICE2').value 	= PPH_VAL;
				document.getElementById('data'+row+'WO_TOTAL2').value 	= WO_TOTAL2;
				document.getElementById('WO_TOTAL2X'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(WO_TOTAL2)), 2));
				getTotalWO();
			}
		}
	}

	function getWOPrice(thisVal1, row)
	{
		var decFormat	= document.getElementById('decFormat').value;

		thisVal 		= parseFloat(Math.abs(eval(thisVal1).value.split(",").join("")));

		WO_PRICE		= parseFloat(thisVal);
		WO_VOLM			= parseFloat(document.getElementById('data'+row+'WO_VOLM').value);
		WO_TOTAL 		= parseFloat(WO_VOLM) * parseFloat(WO_PRICE);

		ITM_REM_VOL		= parseFloat(document.getElementById('ITM_REM_VOL'+row).value);			// Remain Budget (Vol)
		ITM_REM_AMN		= parseFloat(document.getElementById('ITM_REM_AMN'+row).value);			// Remain Budget (Amn)
		
		var ITMUNIT		= document.getElementById('data'+row+'ITM_UNIT').value;
		var ITM_UNIT 	= ITMUNIT.toUpperCase();

		if(WO_TOTAL > ITM_REM_AMN)
		{
			var WO_PRICE 	= parseFloat(ITM_REM_AMN) / parseFloat(ITM_REM_VOL);

			swal('<?php echo $alertGreat; ?>',
			{
				icon:"warning",
			})
			.then(function()
			{
				document.getElementById('data'+row+'ITM_PRICE').value	= WO_PRICE;
				document.getElementById('ITM_PRICE'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(WO_PRICE)), 2));
				document.getElementById('ITM_PRICE'+row).focus();

				WO_TOTAL 	= parseFloat(WO_VOLM) * parseFloat(WO_PRICE);

				PPN_PERC	= parseFloat(document.getElementById('data'+row+'TAXPERC1').value);
				PPN_VAL 	= parseFloat(WO_TOTAL) * parseFloat(PPN_PERC) / 100;

				PPH_PERC	= parseFloat(document.getElementById('data'+row+'TAXPERC2').value);
				PPH_VAL 	= parseFloat(WO_TOTAL) * parseFloat(PPH_PERC) / 100;

				WO_TOTAL2 	= parseFloat(WO_TOTAL + PPN_VAL - PPH_VAL);

				document.getElementById('data'+row+'WO_VOLM').value		= WO_VOLM;
				document.getElementById('WO_VOLM'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(WO_VOLM)), 2));
				document.getElementById('data'+row+'WO_TOTAL').value 	= RoundNDecimal(parseFloat(Math.abs(WO_TOTAL)), 2);
				document.getElementById('WO_TOTAL'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(WO_TOTAL)), 2));
				document.getElementById('data'+row+'TAXPRICE1').value 	= PPN_VAL;
				document.getElementById('data'+row+'TAXPRICE2').value 	= PPH_VAL;
				document.getElementById('data'+row+'WO_TOTAL2').value 	= RoundNDecimal(parseFloat(Math.abs(WO_TOTAL2)), 2);
				document.getElementById('WO_TOTAL2X'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(WO_TOTAL2)), 2));
				getTotalWO();

			});
		}
		else
		{
			document.getElementById('data'+row+'ITM_PRICE').value	= WO_PRICE;
			document.getElementById('ITM_PRICE'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(WO_PRICE)), 2));

			WO_TOTAL 	= parseFloat(WO_VOLM) * parseFloat(WO_PRICE);

			PPN_PERC	= parseFloat(document.getElementById('data'+row+'TAXPERC1').value);
			PPN_VAL 	= parseFloat(WO_TOTAL) * parseFloat(PPN_PERC) / 100;

			PPH_PERC	= parseFloat(document.getElementById('data'+row+'TAXPERC2').value);
			PPH_VAL 	= parseFloat(WO_TOTAL) * parseFloat(PPH_PERC) / 100;

			WO_TOTAL2 	= parseFloat(WO_TOTAL + PPN_VAL - PPH_VAL);

			document.getElementById('data'+row+'WO_VOLM').value		= WO_VOLM;
			document.getElementById('WO_VOLM'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(WO_VOLM)), 2));
			document.getElementById('data'+row+'WO_TOTAL').value 	= RoundNDecimal(parseFloat(Math.abs(WO_TOTAL)), 2);
			document.getElementById('WO_TOTAL'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(WO_TOTAL)), 2));
			document.getElementById('data'+row+'TAXPRICE1').value 	= PPN_VAL;
			document.getElementById('data'+row+'TAXPRICE2').value 	= PPH_VAL;
			document.getElementById('data'+row+'WO_TOTAL2').value 	= RoundNDecimal(parseFloat(Math.abs(WO_TOTAL2)), 2);
			document.getElementById('WO_TOTAL2X'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(WO_TOTAL2)), 2));
			getTotalWO();
		}
	}

	function getWOPPN(TAX_NUM, row)
	{
		var decFormat	= document.getElementById('decFormat').value;


		if(TAX_NUM == '')
		{
			WO_VOLM			= parseFloat(document.getElementById('data'+row+'WO_VOLM').value);
			WO_PRICE		= parseFloat(document.getElementById('data'+row+'ITM_PRICE').value);
			PPH_VAL			= parseFloat(document.getElementById('data'+row+'TAXPRICE2').value);

			WO_TOTAL 		= parseFloat(WO_VOLM) * parseFloat(WO_PRICE);
			PPN_VAL 		= parseFloat(0);

			document.getElementById('data'+row+'TAXPERC1').value	= parseFloat(0);
			document.getElementById('TAXPERC1'+row).value	= RoundNDecimal(parseFloat(Math.abs(0)), 2);
			document.getElementById('data'+row+'TAXPRICE1').value	= parseFloat(0);

			WOTOTAL 		= parseFloat(WO_TOTAL + PPN_VAL - PPH_VAL);
			document.getElementById('data'+row+'WO_TOTAL2').value 	= RoundNDecimal(parseFloat(Math.abs(WOTOTAL)), 2);
			document.getElementById('WO_TOTAL2X'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(WOTOTAL)), 2));
			
			getTotalWO();
		}
		else
		{
			var url			= "<?php echo $secGetPPn; ?>";
			$.ajax({
				type: 'POST',
				url: url,
				data: {TAX_NUM:TAX_NUM},
				success: function(response)
				{
					WO_VOLM			= parseFloat(document.getElementById('data'+row+'WO_VOLM').value);
					WO_PRICE		= parseFloat(document.getElementById('data'+row+'ITM_PRICE').value);
					PPH_VAL			= parseFloat(document.getElementById('data'+row+'TAXPRICE2').value);

					WO_TOTAL 		= parseFloat(WO_VOLM) * parseFloat(WO_PRICE);
					PPN_VAL 		= parseFloat(WO_TOTAL) * parseFloat(response) / 100;
					
					document.getElementById('data'+row+'TAXPERC1').value	= parseFloat(response);
					document.getElementById('TAXPERC1'+row).value	= RoundNDecimal(parseFloat(Math.abs(response)), 2);
					document.getElementById('data'+row+'TAXPRICE1').value	= PPN_VAL;


					WOTOTAL 		= parseFloat(WO_TOTAL + PPN_VAL - PPH_VAL);
					document.getElementById('data'+row+'WO_TOTAL2').value 	= RoundNDecimal(parseFloat(Math.abs(WOTOTAL)), 2);
					document.getElementById('WO_TOTAL2X'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(WOTOTAL)), 2));
					
					getTotalWO();
				}
			});
		}
	}

	function getWOPPH(TAX_NUM, row)
	{
		var decFormat	= document.getElementById('decFormat').value;

		if(TAX_NUM == '')
		{
			WO_VOLM			= parseFloat(document.getElementById('data'+row+'WO_VOLM').value);
			WO_PRICE		= parseFloat(document.getElementById('data'+row+'ITM_PRICE').value);
			PPN_VAL			= parseFloat(document.getElementById('data'+row+'TAXPRICE1').value);

			WO_TOTAL 		= parseFloat(WO_VOLM) * parseFloat(WO_PRICE);
			PPH_VAL 		= parseFloat(0);


			document.getElementById('data'+row+'TAXPERC2').value	= parseFloat(0);
			document.getElementById('TAXPERC2'+row).value	= RoundNDecimal(parseFloat(Math.abs(0)), 2);
			document.getElementById('data'+row+'TAXPRICE2').value	= parseFloat(0);

			WOTOTAL 		= parseFloat(WO_TOTAL + PPN_VAL - PPH_VAL);
			document.getElementById('data'+row+'WO_TOTAL2').value 	= RoundNDecimal(parseFloat(Math.abs(WOTOTAL)), 2);
			document.getElementById('WO_TOTAL2X'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(WOTOTAL)), 2));
			
			getTotalWO();
		}
		else
		{
			var url			= "<?php echo $secGetPPh; ?>";
			$.ajax({
				type: 'POST',
				url: url,
				data: {TAX_NUM:TAX_NUM},
				success: function(response)
				{
					WO_VOLM			= parseFloat(document.getElementById('data'+row+'WO_VOLM').value);
					WO_PRICE		= parseFloat(document.getElementById('data'+row+'ITM_PRICE').value);
					PPN_VAL			= parseFloat(document.getElementById('data'+row+'TAXPRICE1').value);

					WO_TOTAL 		= parseFloat(WO_VOLM) * parseFloat(WO_PRICE);
					PPH_VAL 		= parseFloat(WO_TOTAL) * parseFloat(response) / 100;

					document.getElementById('data'+row+'TAXPERC2').value	= parseFloat(response);
					document.getElementById('TAXPERC2'+row).value	= RoundNDecimal(parseFloat(Math.abs(response)), 2);
					document.getElementById('data'+row+'TAXPRICE2').value	= PPH_VAL;

					WOTOTAL 		= parseFloat(WO_TOTAL + PPN_VAL - PPH_VAL);
					document.getElementById('data'+row+'WO_TOTAL2').value 	= RoundNDecimal(parseFloat(Math.abs(WOTOTAL)), 2);
					document.getElementById('WO_TOTAL2X'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(WOTOTAL)), 2));
					
					getTotalWO();
				}
			});
		}
	}

	function cancelRow(row) 
	{
		var collID  	= document.getElementById('urldelD'+row).value;
        var myarr   	= collID.split("~");
        var url     	= myarr[0];
        var WO_NUM     	= myarr[1];
        var PRJCODE     = myarr[2];
        var ITM_CODE    = myarr[3];
        var ITM_NAME    = myarr[4];
        var JOBPARDESC1	= myarr[5];
        var WO_VOLM     = myarr[6];
        var OPN_VOLM    = myarr[7];
        var REM_VOLWO   = myarr[8];
        var ITM_UNIT   	= myarr[9];
        var WO_ID   	= myarr[10];

        var JOBPARDESC = stringDivider(JOBPARDESC1, 25, "<br/>\n");

        var JobNm 		= "<?=$JobNm?>";

		document.getElementById('itmCode').innerHTML 	= ITM_CODE;
		document.getElementById('itmName').innerHTML 	= ITM_NAME;
		//document.getElementById('itmUnit').innerHTML 	= ITM_UNIT;
		document.getElementById('itmRow').value 		= row;
		document.getElementById('jobName').innerHTML 	= JobNm+' : '+JOBPARDESC;
		document.getElementById('itmPRVol').innerHTML 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(WO_VOLM)), 2))+' '+ITM_UNIT;
		document.getElementById('itmPOVol').innerHTML 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPN_VOLM)), 2))+' '+ITM_UNIT;
		document.getElementById('itmREMVol').innerHTML 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(REM_VOLWO)), 2))+' '+ITM_UNIT;
		document.getElementById('WO_RVOL').value 		= RoundNDecimal(parseFloat(Math.abs(REM_VOLWO)), 2);

		document.getElementById('btnModalDel').click();
	}

	function chgCncVol(cncVol)
	{
		var REMVOL 	= parseFloat(document.getElementById('WO_RVOL').value);
		var CANVOL 	= parseFloat(cncVol);
		if(CANVOL > REMVOL)
		{
			swal("Jumlah yang akan dibatalkan lebih besar dari sisa volume.",
			{
				icon:"warning",
			})
			.then(function()
			{
				swal.close();
				document.getElementById('WO_CVOL').value 	= REMVOL;
				document.getElementById('WO_CVOLX').value	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(REMVOL)), 2));
			})
		}
		else
		{
			document.getElementById('WO_CVOL').value 		= CANVOL;
			document.getElementById('WO_CVOLX').value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(CANVOL)), 2));
		}
	}

	function proc_cnc(row)
	{
		var row 	= document.getElementById('itmRow').value;

        swal({
            text: "<?php echo $sureVoid; ?>",
            icon: "warning",
            buttons: ["No", "Yes"],
        })
        .then((willDelete) => 
        {
            if (willDelete) 
            {
            	var WO_CVOL 	= document.getElementById('WO_CVOL').value;
				var collID1  	= document.getElementById('urldelD'+row).value;
				var collID  	= collID1+'~'+WO_CVOL;
		        var myarr   	= collID.split("~");
		        var url     	= myarr[0];

                $.ajax({
                    type: 'POST',
                    url: url,
                    data: {collID: collID},
                    success: function(response)
                    {
                        swal(response, 
                        {
                            icon: "success",
                        });
                        document.getElementById('idCloseDRow').click();
                        window.location.reload();
                    }
                });
            } 
            else 
            {
                //...
            }
        });
    }

	function stringDivider(str, width, spaceReplacer)
	{
	    if (str.length>width) {
	        var p=width
	        for (;p>0 && str[p]!=' ';p--) {
	        }
	        if (p>0) {
	            var left = str.substring(0, p);
	            var right = str.substring(p+1);
	            return left + spaceReplacer + stringDivider(right, width, spaceReplacer);
	        }
	    }
	    return str;
	}

	function checkInp()
	{
		SPLCODE		= document.getElementById('SPLCODE').value;
		WO_NOTE		= document.getElementById('WO_NOTE').value;
		if(SPLCODE == '0')
		{
			swal('<?php echo $alert4; ?>',
			{
				icon:"warning",
			})
			.then(function()
			{
				document.getElementById('SPLCODE').focus();
			});
			return false;
		}

// 		if(WO_NOTE == '')
// 		{
// 			swal('<?php // echo $alert10; ?>',
// 			{
// 				icon:"warning",
// 			})
// 			.then(function()
// 			{
// 				document.getElementById('WO_NOTE').focus();
// 			});
// 			return false;
// 		}

		totalrow	= document.getElementById('totalrow').value;
		if(totalrow == 0)
		{
			swal('<?php echo $alert9; ?>',
			{
				icon: "warning",
			});
			return false;
		}
		else
		{
			for(let i=1;i<=totalrow;i++)
			{
				let WO_DESC 	= document.getElementById('data'+i+'WO_DESC');

				//console.log(i+' = '+ values)
				
				if(WO_DESC.value == '')
				{
					swal('<?php echo $alert12; ?>',
					{
						icon: "warning",
					}).then(function()
					{
						WO_DESC.focus();
					})
					return false;
				}
			}
		}
		document.getElementById('btnSave').style.display = 'none';
		document.getElementById('btnBack').style.display = 'none';
	}

	function validateDouble(vcode, SNCODE)
	{
		var thechk=new Array();
		var duplicate = false;

		var jumchk = document.getElementsByName('chk').length;
		if (jumchk!=null)
		{
			thechk=document.getElementsByName('chk');
			panjang = parseInt(thechk.length);
		}
		else
		{
			thechk[0]=document.getElementsByName('chk');
			panjang = 0;
		}
		var panjang = panjang + 1;
		for (var i=0;i<panjang;i++)
		{
			var temp = 'tr_'+parseInt(i+1);
			if(i>0)
			{
				var elitem1= document.getElementById('data'+i+'ITM_CODE').value;
				var iparent= document.getElementById('data'+i+'SNCODE').value;
				if (elitem1 == vcode && iparent == SNCODE)
				{
					if (elitem1 == vcode)
					{
						duplicate = true;
						break;
					}
				}
			}
		}
		return duplicate;
	}

	function getDiscount(thisVal1, row)
	{
		var decFormat		= document.getElementById('decFormat').value;

		thisVal 			= parseFloat(Math.abs(eval(thisVal1).value.split(",").join("")));

		$("#data"+row+"WO_DISC").val(thisVal);
		$("#WO_DISC"+row).val(doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(thisVal)),decFormat)));

		WO_TOTAL = parseFloat($("#data"+row+"WO_TOTAL").val());
		WO_DISC		= (WO_TOTAL * thisVal)/100;
		$("#data"+row+"WO_DISCP").val(WO_DISC);
		$("#WO_DISCP"+row).val(doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(WO_DISC)),decFormat)));

		thisVal				= parseFloat(document.getElementById('WO_VOLM'+row).value);				// Request Qty - Now
		getConvertion(thisVal, row);
	}

	function getDiscountP(thisVal1, row)
	{
		var decFormat		= document.getElementById('decFormat').value;

		thisVal 			= parseFloat(Math.abs(eval(thisVal1).value.split(",").join("")));

		$("#data"+row+"WO_DISCP").val(thisVal);
		$("#WO_DISCP"+row).val(doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(thisVal)),decFormat)));

		WO_TOTAL = parseFloat($("#data"+row+"WO_TOTAL").val());

		WO_DISCP	= (thisVal / WO_TOTAL) * 100;
		$("#data"+row+"WO_DISC").val(WO_DISCP);
		$("#WO_DISC"+row).val(doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(WO_DISCP)),decFormat)));

		thisVal				= parseFloat(document.getElementById('WO_VOLM'+row).value);				// Request Qty - Now
		getConvertion(thisVal, row);
	}

	function getConvertion(thisVal1, row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		ITM_UNIT			= document.getElementById('data'+row+'ITM_UNIT').value;

		itemConvertion		= document.getElementById('itemConvertion'+row).value;
		WO_VOLM 			= parseFloat(document.getElementById('data'+row+'WO_VOLM').value);		// wo volm
		ITM_PRICE			= parseFloat(document.getElementById('data'+row+'ITM_PRICE').value);	// Item Price
		ITM_PRICE1			= parseFloat(document.getElementById('ITM_PRICE'+row).value);			// Item Price
		console.log('a')
		ITM_BUDGQTY			= parseFloat(document.getElementById('ITM_BUDGQTY'+row).value);			// Budget Qty
		ITM_BUDG			= parseFloat(document.getElementById('data'+row+'ITM_BUDG').value);		// Budget Amount
		TOTWOQTY			= document.getElementById('TOTWOQTY'+row).value;						// Total Requested

		TOTPRAMOUNT			= parseFloat(TOTWOQTY) * parseFloat(ITM_PRICE);							// Total Requested Amount
		//REQ_NOW_QTY1		= parseFloat(document.getElementById('WO_VOLM'+row).value);				// Request Qty - Now
		if(WO_VOLM == 0)
			REQ_NOW_QTY1		= parseFloat(eval(document.getElementById('WO_VOLM'+row)).value.split(",").join(""));
		else
			REQ_NOW_QTY1		= parseFloat(document.getElementById('data'+row+'WO_VOLM').value);

		REQ_NOW_QTY2		= REQ_NOW_QTY1 * itemConvertion;						// Request Qty 2 - Now
		REQ_NOW_AMOUNT		= parseFloat(REQ_NOW_QTY1) * parseFloat(ITM_PRICE);						// Request Qty Amount - Now
		//swal(REQ_NOW_QTY1+'*'+ITM_PRICE+'='+REQ_NOW_AMOUNT);
		//swal(REQ_NOW_AMOUNT)
		if(ITM_UNIT == 'LS')
		{
			ITM_PRICE		= 1;
			REQ_NOW_AMOUNT	= parseFloat(REQ_NOW_QTY1) * parseFloat(ITM_PRICE);
			TOTPRAMOUNT		= parseFloat(TOTWOQTY) * parseFloat(ITM_PRICE1);
			REM_WO_QTY		= parseFloat(ITM_BUDG) - parseFloat(TOTPRAMOUNT);
			REM_WO_AMOUNT	= parseFloat(ITM_BUDG) - parseFloat(TOTPRAMOUNT);
		}
		else
		{
			REQ_NOW_AMOUNT	= parseFloat(REQ_NOW_QTY1) * parseFloat(ITM_PRICE);
			REM_WO_QTY		= parseFloat(ITM_BUDGQTY) - parseFloat(TOTWOQTY);
			REM_WO_AMOUNT	= parseFloat(ITM_BUDG) - parseFloat(TOTPRAMOUNT);
		}

		//swal(REQ_NOW_AMOUNT+ ">" +REM_WO_AMOUNT)

		if(REQ_NOW_AMOUNT > REM_WO_AMOUNT)
		{
			REM_WO_QTYV		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(REM_WO_QTY)),decFormat));
			REM_WO_AMOUNTV	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(REM_WO_AMOUNT)),decFormat));
			swal('Request Qty is Greater than Budget. Maximum Qty is '+REM_WO_QTY+' or in Amount is '+REM_WO_AMOUNT);

			document.getElementById('data'+row+'WO_VOLM').value 	= REM_WO_QTY;
			document.getElementById('WO_VOLM'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(REM_WO_QTY)),decFormat));

			document.getElementById('data'+row+'WO_VOLM').value 	= REM_WO_QTY;
			document.getElementById('WO_VOLM'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(REM_WO_QTY)),decFormat));
			NEW_PRICE = parseFloat(REM_WO_AMOUNT / REM_WO_QTY);
			document.getElementById('data'+row+'ITM_PRICE').value	= NEW_PRICE;
			document.getElementById('ITM_PRICE'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(NEW_PRICE)),decFormat));

			REQ_NOW_AMOUNT	= parseFloat(REM_WO_QTY) * parseFloat(NEW_PRICE);
			document.getElementById('data'+row+'WO_TOTAL').value 	= REQ_NOW_AMOUNT;
			document.getElementById('WO_TOTAL'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(REQ_NOW_AMOUNT)),decFormat));

			//GET DISCOUNT VALUE
			// WO_DISC				= parseFloat($("#data"+row+"WO_DISC").val()); // discount (%)
			// WO_DISCP			= parseFloat($("#data"+row+"WO_DISCP").val()); // discount Price

			// GET TAX VALUE
			TAXCODE1	= document.getElementById('TAXCODE1'+row).value;
			TAXCODE2	= document.getElementById('TAXCODE2'+row).value;
			TAXPRICE1			= 0;
			TAXPRICE2			= 0;
			WO_TOTAL2			= REQ_NOW_AMOUNT;
			if(REQ_NOW_AMOUNT == 0){
				WO_TOTAL2_POT	= 0;
			}else{
				WO_TOTAL2_POT	= REQ_NOW_AMOUNT;
			}
			if(TAXCODE1 == 'TAX01')
			{
				TAXPRICE1 	= parseFloat(0.1 * REQ_NOW_AMOUNT);
			}

			if(TAXCODE2 == 'TAX02')
			{
				TAXPRICE2 	= parseFloat(0.02 * REQ_NOW_AMOUNT);
			}
			else if(TAXCODE2 == 'TAX03')
			{
				TAXPRICE2 	= parseFloat(0.03 * REQ_NOW_AMOUNT);
			}
			else if(TAXCODE2 == 'TAX04')
			{
				TAXPRICE2 	= parseFloat(0.04 * REQ_NOW_AMOUNT);
			}
			else if(TAXCODE2 == 'TAX20')
			{
				TAXPRICE2 	= parseFloat(0.2 * REQ_NOW_AMOUNT);
			}

			WO_TOTAL2	= parseFloat(REQ_NOW_AMOUNT + TAXPRICE1 - TAXPRICE2);
			WO_TOTAL2_POT	= parseFloat(WO_TOTAL2);

			document.getElementById('data'+row+'WO_TOTAL2').value 	= WO_TOTAL2_POT; // after tax
			document.getElementById('WO_TOTAL2X'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(WO_TOTAL2_POT)),decFormat));
			document.getElementById('data'+row+'TAXPRICE1').value 	= TAXPRICE1;
			document.getElementById('data'+row+'TAXPRICE2').value 	= TAXPRICE2;

			//COUNT TOTAL SPK
			var totrow 		= document.getElementById('totalrow').value;

			var GTOTAL_WO	= 0;
			for(i=1;i<=totrow;i++)
			{
				let myObj 	= document.getElementById('data'+i+'WO_TOTAL2');
				var values 	= typeof myObj !== 'undefined' ? myObj : '';

				//console.log(i+' = '+ values)
				
				if(values != null)
				{
					var WO_TOTITEM	= parseFloat(document.getElementById('data'+i+'WO_TOTAL2').value);
					GTOTAL_WO		= parseFloat(GTOTAL_WO + WO_TOTITEM);
				}
			}
			document.getElementById('WO_VALUE').value = GTOTAL_WO;
			document.getElementById('WO_VALUEX').value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(GTOTAL_WO)),decFormat));

			return false;
		}
		document.getElementById('data'+row+'WO_TOTAL').value 	= REQ_NOW_AMOUNT;
		document.getElementById('WO_TOTAL'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(REQ_NOW_AMOUNT)),decFormat));

		document.getElementById('data'+row+'WO_VOLM').value 	= REQ_NOW_QTY1;
		document.getElementById('WO_VOLM'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(REQ_NOW_QTY1)),decFormat));

		//GET DISCOUNT VALUE
		// WO_DISC				= parseFloat($("#data"+row+"WO_DISC").val()); // discount (%)
		// WO_DISCP			= parseFloat($("#data"+row+"WO_DISCP").val()); // discount Price

		// GET TAX VALUE
		TAXCODE1			= document.getElementById('TAXCODE1'+row).value;
		TAXCODE2			= document.getElementById('TAXCODE2'+row).value;
		TAXPRICE1			= 0;
		TAXPRICE2			= 0;
		WO_TOTAL2			= REQ_NOW_AMOUNT;
		if(REQ_NOW_AMOUNT == 0){
			WO_TOTAL2_POT	= 0;
		}else{
			WO_TOTAL2_POT	= REQ_NOW_AMOUNT;
		}
		//swal(REQ_NOW_AMOUNT);
		//swal("WO_TOTAL2_POT	= "+REQ_NOW_AMOUNT+ "-" +WO_DISCP);
		if(TAXCODE1 == 'TAX01')
		{
			TAXPRICE1 		= parseFloat(0.1 * REQ_NOW_AMOUNT);
		}

		if(TAXCODE2 == 'TAX02')
		{
			TAXPRICE2 	= parseFloat(0.02 * REQ_NOW_AMOUNT);
		}
		else if(TAXCODE2 == 'TAX03')
		{
			TAXPRICE2 	= parseFloat(0.03 * REQ_NOW_AMOUNT);
		}
		else if(TAXCODE2 == 'TAX04')
		{
			TAXPRICE2 	= parseFloat(0.04 * REQ_NOW_AMOUNT);
		}
		else if(TAXCODE2 == 'TAX20')
		{
			TAXPRICE2 	= parseFloat(0.2 * REQ_NOW_AMOUNT);
		}

		WO_TOTAL2		= parseFloat(REQ_NOW_AMOUNT + TAXPRICE1 - TAXPRICE2);
		WO_TOTAL2_POT	= parseFloat(WO_TOTAL2);

		document.getElementById('data'+row+'WO_TOTAL2').value 	= WO_TOTAL2_POT; // After Plus or Min Tax - discount
		document.getElementById('WO_TOTAL2X'+row).value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(WO_TOTAL2_POT)),decFormat));
		document.getElementById('data'+row+'TAXPRICE1').value 	= TAXPRICE1;
		document.getElementById('data'+row+'TAXPRICE2').value 	= TAXPRICE2;

		//COUNT TOTAL SPK
		var totrow 		= document.getElementById('totalrow').value;

		var GTOTAL_WO	= 0;
		for(i=1;i<=totrow;i++)
		{
			var WO_TOTITEM	= parseFloat(document.getElementById('data'+i+'WO_TOTAL2').value);
			GTOTAL_WO		= parseFloat(GTOTAL_WO + WO_TOTITEM);
		}
		document.getElementById('WO_VALUE').value = GTOTAL_WO;
		document.getElementById('WO_VALUEX').value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(GTOTAL_WO)),decFormat));
	}

	function getTotalWO()
	{
		totalrow 	= document.getElementById('totalrow').value;
		totWO 		= 0;
		totPPN 		= 0;
		totPPH 		= 0;
		GTotWO 		= 0;
		for(i=1;i<=totalrow;i++)
		{
			let myObj 	= document.getElementById('data'+i+'WO_TOTAL2');
			var values 	= typeof myObj !== 'undefined' ? myObj : '';

			//console.log(i+' = '+ values)
			
			if(values != null)
			{
				WO_TOTAL	= parseFloat(document.getElementById('data'+i+'WO_TOTAL').value);
				WO_PPN		= parseFloat(document.getElementById('data'+i+'TAXPRICE1').value);
				WO_PPH		= parseFloat(document.getElementById('data'+i+'TAXPRICE2').value);
				WO_TOTAL2	= parseFloat(document.getElementById('data'+i+'WO_TOTAL2').value);
				totWO 		= parseFloat(totWO) + parseFloat(WO_TOTAL);
				totPPN 		= parseFloat(totPPN) + parseFloat(WO_PPN);
				totPPH 		= parseFloat(totPPH) + parseFloat(WO_PPH);
				GTotWO 		= parseFloat(GTotWO) + parseFloat(WO_TOTAL2);
			}
		}
		let WO_DPVAL 		= GTotWO * parseFloat(document.getElementById('WO_DPPER').value) / 100;
		let WO_RETVAL 		= GTotWO * parseFloat(document.getElementById('WO_RETP').value) / 100;
		document.getElementById('WO_VALUE').value 	= totWO;
		document.getElementById('WO_VALUEX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(totWO)), 2));
		document.getElementById('WO_VALPPN').value 	= totPPN;
		document.getElementById('WO_VALPPNX').value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(totPPN)), 2));
		document.getElementById('WO_VALPPH').value 	= totPPH;
		document.getElementById('WO_VALPPHX').value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(totPPH)), 2));
		document.getElementById('WO_DPVAL').value 	= WO_DPVAL;
		document.getElementById('WO_DPVALX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(WO_DPVAL)), 2));
		document.getElementById('WO_RETVAL').value 	= WO_RETVAL;
		document.getElementById('WO_RETVALX').value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(WO_RETVAL)), 2));
		document.getElementById('WO_GTOTAL').value 	= GTotWO;
		document.getElementById('WO_GTOTALX').value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(GTotWO)), 2));
	}

	function doDecimalFormat(angka) {
		var a, b, c, dec, i, j;
		angka = String(angka);
		if(angka.indexOf('.') > -1){ a = angka.split('.')[0] ; dec = angka.split('.')[1]
		} else { a = angka; dec = -1; }
		b = a.replace(/[^\d]/g,"");
		c = "";
		var panjang = b.length;
		j = 0;
		for (i = panjang; i > 0; i--) {
			j = j + 1;
			if (((j % 3) == 1) && (j != 1)) c = b.substr(i-1,1) + "," + c;
			else c = b.substr(i-1,1) + c;
		}
		if(dec == -1) return angka;
		else return (c + '.' + dec);
	}

	function RoundNDecimal(X, N) {
		var T, S=new String(Math.round(X*Number("1e"+N)))
		while (S.length<=N) S='0'+S
		return S.substr(0, T=(S.length-N)) + '.' + S.substr(T, N)
	}

	function isIntOnlyNew(evt)
	{
		if (evt.which){ var charCode = evt.which; }
		else if(document.all && event.keyCode){ var charCode = event.keyCode; }
		else { return true; }
		return ((charCode == 45) || (charCode == 46) || (charCode == 8) || (charCode >= 48) && (charCode <= 57));
	}

	function decimalin(ini)
	{
		var i, j;
		var bil2 = deletecommaperiod(ini.value,'both')
		var bil3 = ""
		j = 0
		for (i=bil2.length-1;i>=0;i--)
		{
			j = j + 1;
			if (j == 3)
			{
				bil3 = "." + bil3
			}
			else if ((j >= 6) && ((j % 3) == 0))
			{
				bil3 = "," + bil3
			}
			bil3 = bil2.charAt(i) + "" + bil3
		}
		ini.value = bil3
	}
</script>
<?php
    $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'js' AND isAct = 1 AND cssjs_vers IN ('$vers', 'All')";
    $rescss = $this->db->query($sqlcss)->result();
    foreach($rescss as $rowcss) :
        $cssjs_lnk  = $rowcss->cssjs_lnk;
        ?>
            <script src="<?php echo base_url($cssjs_lnk) ?>"></script>
        <?php
    endforeach;

    // Right side column. contains the Control Panel
    //______$this->load->view('template/aside');

    //______$this->load->view('template/js_data');

    //______$this->load->view('template/foot');
?>