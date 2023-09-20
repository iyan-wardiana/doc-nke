<?php
/*
	* Author		= Dian Hermanto
	* Create Date	= 15 Maret 2022
	* File Name		= v_budprop_form.php
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

$currRow = 0;
if($task == 'add')
{
	$yearC 		= date('Y');
	$this->db->where('Patt_Year', $yearC);
	$myCount 	= $this->db->count_all('tbl_bprop_header');

	$sql 		= "SELECT MAX(Patt_Number) as maxNumber FROM tbl_bprop_header
					WHERE Patt_Year = $yearC AND PRJCODE = '$PRJCODE'";
	$result 	= $this->db->query($sql)->result();
	if($myCount>0)
	{
		foreach($result as $row) :
			$myMax = $row->maxNumber;
			$myMax = $myMax+1;
		endforeach;
	}	else	{		$myMax = 1;	}

	$lastPatt 	= $myMax;
	$len 		= strlen($lastPatt);

	$Pattern_Length 	= 4;
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
	$lastPatt = $nol.$lastPatt;

	$sql = "SELECT PRJCODE, PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
	$resultProj = $this->db->query($sql)->result();
	foreach($resultProj as $row) :
		$PRJNAME = $row->PRJNAME;
	endforeach;

	$PROP_CODE 		= "$PRJCODE.$lastPatt";
	$TRXTIME 		= date('ymdHis');
	$PROP_NUM		= "$PRJCODE.$TRXTIME";

	$PROP_DATE		= date('Y-m-d');
	$PROP_DATEV		= strftime('%d %B %Y', strtotime($PROP_DATE));
	$PRJCODE		= $PRJCODE;
	$EMP_ID			= '';
	$PROP_NOTE		= '';
	$PROP_NOTE2 	= '';
	$VLK_CREATED 	= 0;
	$PROP_STAT 		= 1;
	$PROP_MEMO		= '';
	$PROP_VALUE		= 0;
	$PROP_VALUEAPP 	= 0;
	$PROP_GTOTAL 	= 0;
	$FPA_NUM		= '';

	$dataColl 		= "$PRJCODE~tbl_bprop_header~$Pattern_Length";
	$dataTarget		= "PROP_CODE";

	$Patt_Year 		= date('Y');
	$Patt_Number	= $lastPatt;
}
else
{
	$PROP_NUM 		= $default['PROP_NUM'];
	$PROP_CODE 		= $default['PROP_CODE'];
	$PROP_DATE 		= $default['PROP_DATE'];
	$PROP_DATEV		= strftime('%d %B %Y', strtotime($PROP_DATE));
	$PRJCODE		= $default['PRJCODE'];
	$EMP_ID 		= $default['EMP_ID'];
	$PROP_NOTE 		= $default['PROP_NOTE'];
	$PROP_NOTE2 	= $default['PROP_NOTE2'];
	$VLK_CREATED 	= $default['VLK_CREATED'];
	$PROP_STAT 		= $default['PROP_STAT'];
	$PROP_MEMO 		= $default['PROP_MEMO'];
	$PROP_VALUE 	= $default['PROP_VALUE'];
	$PROP_VALUEAPP 	= $default['PROP_VALUEAPP'];
	$PROP_GTOTAL 	= $default['PROP_GTOTAL'];
	$PROP_ISCLOSE 	= $default['PROP_ISCLOSE'];
	$Patt_Number	= $default['Patt_Number'];
}

if(isset($_POST['JOBCODE1']))
{
	$PR_REFNO	= $_POST['JOBCODE1'];
}

$img_filenameX 	= "";
$sqlGetIMG		= "SELECT imgemp_filename, imgemp_filenameX FROM tbl_employee_img WHERE imgemp_empid = '$DefEmp_ID'";
$resGetIMG 		= $this->db->query($sqlGetIMG)->result();
foreach($resGetIMG as $rowGIMG) :
	$imgemp_filename 	= $rowGIMG ->imgemp_filename;
	$img_filenameX 		= $rowGIMG ->imgemp_filenameX;
endforeach;
$imgLoc				= base_url('assets/AdminLTE-2.0.5/emp_image/'.$DefEmp_ID.'/'.$img_filenameX);
if (!file_exists('assets/AdminLTE-2.0.5/emp_image/'.$DefEmp_ID))
{
	$imgLoc			= base_url('assets/AdminLTE-2.0.5/emp_image/username.jpg');
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
			if($TranslCode == 'submissNo')$submissNo = $LangTransl;
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
			
			// PROP_CODE - PR_VALUE
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
				$sqlC_App	= "tbl_approve_hist WHERE AH_CODE = '$PROP_CODE'";
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
				$APPROVE_AMOUNT 	= $PROP_VALUE;
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
    </style>
    
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
			<h1>
		    <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/wo.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $mnName; ?>
		    <small><?php echo $PRJNAME; ?></small>  </h1>
		</section>

		<section class="content">
		    <div class="row">
		        <form class="form-horizontal" name="frm" id="frm" method="post" action="<?php echo $form_action; ?>" enctype="multipart/form-data" onSubmit="return checkInp()">
					<div class="col-md-5">
						<div class="box box-primary">
							<div class="box-body">
			                	<?php
		                    		$compName 	= "-";
		                    		$s_Empl		= "SELECT CONCAT(First_Name,' ', Last_Name) AS compName, Birth_Place, Date_Of_Birth, Pos_Code
		                    						FROM tbl_employee
		                    						WHERE Emp_ID = '$DefEmp_ID' LIMIT 1";
									$r_Empl 	= $this->db->query($s_Empl)->result();
									foreach($r_Empl as $rw_Empl) :
										$compName 	= $rw_Empl->compName;
										$bPlace 	= $rw_Empl->Birth_Place;
										$bDate 		= strftime('%d %b %Y', strtotime($rw_Empl->Date_Of_Birth));
										$PosCode 	= $rw_Empl->Pos_Code;

										$POSS_NAME 	= "-";			
										$s_possC	= "SELECT POSS_NAME, POSS_DESC FROM tbl_position_str WHERE POSS_CODE = '$PosCode' LIMIT 1";
										$r_possC 	= $this->db->query($s_possC)->result();
										foreach($r_possC as $rw_possC) :
											$POSS_NAME 	= $rw_possC->POSS_NAME;
											$POSS_DESC 	= $rw_possC->POSS_DESC;
										endforeach;
									endforeach;
			                	?>
				                <div class="col-sm-5" style="text-align: center;">
				                  	<img class="profile-user-img img-responsive img-circle" src="<?php echo $imgLoc; ?>" alt="User profile picture">
				                </div>

				                <div class="col-sm-7">
				                  	<div class="row">
				                    	<div class="col-md-12">
				                    		<?php echo "<strong>No. Pengajuan</strong>"; ?>
				                    	</div>
				                    	<div class="col-md-12" style="font-style: italic;">
				                    		<?php echo "$PROP_CODE / $PROP_DATEV"; ?>
				                    	</div>
				                    	<div class="col-md-12">
				                    		<?php echo "<strong>Nama Lengkap</strong>"; ?>
				                    	</div>
				                    	<div class="col-md-12" style="font-style: italic;">
				                    		<?php echo "$compName"; ?>
				                    	</div>
				                    	<div class="col-md-12">
				                    		<?php echo "<strong>TTL</strong>"; ?>
				                    	</div>
				                    	<div class="col-md-12" style="font-style: italic;">
				                    		<?php echo "$bPlace, $bDate"; ?>
				                    	</div>
			                    	</div>
			                    </div>
			                </div>
			            </div>
			        </div>

					<div class="col-md-7">
						<div class="box box-warning">
							<div class="box-body">
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Notes; ?></label>
				                    <div class="col-sm-5">
				                    	<textarea name="PROP_NOTE" class="form-control" id="PROP_NOTE" style="height: 60px" placeholder="Catatan Pengajuan"><?php echo $PROP_NOTE; ?></textarea>
				                    	<input type="hidden" name="Patt_Number" class="form-control pull-left" id="Patt_Number" value="<?php echo $Patt_Number; ?>">
				                    	<input type="hidden" name="decFormat" class="form-control pull-left" id="decFormat" value="<?php echo $decFormat; ?>">
				                    	<input type="hidden" name="PROP_DATE" class="form-control pull-left" id="datepicker" value="<?php echo $PROP_DATE; ?>">
				                        <input type="hidden" class="form-control" style="text-align:left" id="PROP_CODE" name="PROP_CODE" value="<?php echo $PROP_CODE; ?>" readonly />
				                        <input type="hidden" class="form-control" name="PRJCODE" id="PRJCODE" size="30" value="<?php echo $PRJCODE; ?>" />
				                    	<input type="hidden" class="form-control" style="max-width:350px;text-align:right" name="PROP_NUM" id="PROP_NUM" size="30" value="<?php echo $PROP_NUM; ?>" />
				                    </div>
				                    <div class="col-sm-4">
				                    	<label for="inputName">Total (Rp.)</label>
				                        <input type="hidden" class="form-control" style="text-align:right;" id="PROP_VALUE" name="PROP_VALUE" value="<?php echo $PROP_VALUE; ?>" />
				                        <input type="text" class="form-control" style="text-align:right;" id="PROP_VALUEX" name="PROP_VALUEX" value="<?php echo number_format($PROP_VALUE,2); ?>" readonly />
				                    </div>
				                </div>

				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Status; ?></label>
				                    <div class="col-sm-5">
				                    	<input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $PROP_STAT; ?>">
				                        <select name="PROP_STAT" id="PROP_STAT" class="form-control select2" onChange="selStat(this.value)">
										<?php
											$isDisabled	= 0;
											if($PROP_STAT == 6 || $PROP_STAT == 7)
											{
												$isDisabled	= 1;
											}

											$disableBtn	= 0;
											if($PROP_STAT == 5 || $PROP_STAT == 6 || $PROP_STAT == 9)
											{
												$disableBtn	= 1;
											}
											elseif($PROP_STAT == 3 && $ISDELETE == 1)
											{
											    $disableBtn	= 0;
											}
											if($PROP_STAT != 1 AND $PROP_STAT != 4)
											{
												?>
													<option value="1"<?php if($PROP_STAT == 1) { ?> selected <?php } ?> disabled>New</option>
													<option value="2"<?php if($PROP_STAT == 2) { ?> selected <?php } ?> disabled>Confirm</option>
													<option value="3"<?php if($PROP_STAT == 3) { ?> selected <?php } ?> disabled>Approve</option>
													<option value="4"<?php if($PROP_STAT == 4) { ?> selected <?php } ?> disabled>Revising</option>
													<option value="5"<?php if($PROP_STAT == 5) { ?> selected <?php } ?> disabled>Rejected</option>
													<option value="6"<?php if($PROP_STAT == 6) { ?> selected <?php } if($disableBtn == 1) {?> disabled <?php } ?>>Closed</option>
													<option value="7"<?php if($PROP_STAT == 7) { ?> selected <?php } ?> disabled>Waiting</option>
													<option value="9"<?php if($PROP_STAT == 9) { ?> selected <?php } if($disableBtn == 1) {?> disabled <?php } ?>>Void</option>
												<?php
											}
											else
											{
												?>
													<option value="1"<?php if($PROP_STAT == 1) { ?> selected <?php } ?>>New</option>
													<option value="2"<?php if($PROP_STAT == 2) { ?> selected <?php } ?>>Confirm</option>
												<?php
											}
				                        ?>
				                        </select>
				                    </div>
		                          	<?php if($PROP_STAT == 1 || $PROP_STAT == 4) { ?>
					                    <div class="col-sm-4">
					                        <div class="pull-left">
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
										else if(thisValue == 3)
										{
											document.getElementById('tblClose').style.display = 'none';
											document.getElementById('tblReason').style.display = 'none';
										}
									}
								</script>
							</div>
						</div>
					</div>

                    <div class="col-md-12">
                        <div class="box box-primary">
	                        <div class="search-table-outter">
	                            <table id="tbl" class="table table-bordered table-striped" width="100%">
		                        	<tr style="background:#CCCCCC">
			                            <th width="5%" style="text-align:left; vertical-align: middle;">&nbsp;</th>
			                            <th width="25%" style="text-align:center; vertical-align: middle;"><?php echo $ItemName ?> </th>
			                            <th width="10%" style="text-align:center; vertical-align: middle;"><?php echo $BudgetQty; ?> </th>
			                            <th width="10%" style="text-align:center; vertical-align: middle;"><?php echo $Requested; ?> </th>
			                            <th width="10%" style="text-align:center; vertical-align: middle;"><?php echo "Volume"; ?></th>
			                            <th width="10%" style="text-align:center; vertical-align: middle;"><?php echo $Price; ?></th>
			                            <th width="5%" style="text-align:center; vertical-align: middle;"><?php echo $Unit ?> </th>
			                            <th width="10%" style="text-align:center; vertical-align: middle;"><?php echo $Amount ?></th>
			                            <th width="15%" style="text-align:center; vertical-align: middle;">Des.</th>
		                          	</tr>
		                            <tr style="background:#CCCCCC">
		                            </tr>
		                            <?php
			                            if($task == 'edit')
			                            {
			                                $sqlDET	= "SELECT A.*
														FROM tbl_bprop_detail A
															INNER JOIN tbl_bprop_header B ON B.PROP_NUM = A.PROP_NUM
														WHERE A.PROP_NUM = '$PROP_NUM' AND A.PRJCODE = '$PRJCODE'";
			                                $result = $this->db->query($sqlDET)->result();
			                                $i		= 0;
			                                $j		= 0;

											foreach($result as $row) :
												$currRow  	= ++$i;
												$PROP_ID 		= $row->PROP_ID;
												$PROP_NUM 		= $row->PROP_NUM;
												$PROP_CODE 		= $row->PROP_CODE;
												$PROP_DATE 		= $row->PROP_DATE;
												$PRJCODE 		= $row->PRJCODE;
												$JOBCODEID 		= $row->JOBCODEID;
												$JOBDESC 		= $row->JOBDESC;
												$ITM_CODE 		= $row->ITM_CODE;
												$ITM_NAME 		= $row->ITM_NAME;
												$ITM_UNIT 		= $row->ITM_UNIT;
												$ITM_UNITV 		= strtoupper($ITM_UNIT);
												$ITM_RAPV 		= $row->ITM_RAPV;
												$ITM_RAPP 		= $row->ITM_RAPP;
												$ITM_VOLM		= $row->ITM_VOLM;
												$ITM_PRICE		= $row->ITM_PRICE;
												$PROP_TOTAL 	= $row->PROP_TOTAL;
												$PROP_DESC 		= $row->PROP_DESC;

												if($PROP_STAT == 1 || $PROP_STAT || 4) 
												{
													$disRow 	= 0;
												}

												/*if ($j==1) {
													echo "<tr class=zebra1>";
													$j++;
												} else {
													echo "<tr class=zebra2>";
													$j--;
												}*/
												?>
			                                    <tr id="tr_<?php echo $currRow; ?>" style="vertical-align: middle;">
													<td height="25" style="text-align:center; vertical-align: middle;">
													  	<?php
															if($PROP_STAT == 1)
															{
																?>
																<a href="#" onClick="deleteRow(<?php echo $currRow; ?>)" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a>
																<?php
															}
													  	?>
													  	<input type="hidden" id="data<?php echo $currRow; ?>PROP_NUM" name="data[<?php echo $currRow; ?>][PROP_NUM]" value="<?php echo $PROP_NUM; ?>" class="form-control">
													  	<input type="hidden" id="data<?php echo $currRow; ?>PROP_CODE" name="data[<?php echo $currRow; ?>][PROP_CODE]" value="<?php echo $PROP_CODE; ?>" class="form-control">
													  	<input type="hidden" name="data[<?php echo $currRow; ?>][JOBCODEID]" id="data<?php echo $currRow; ?>JOBCODEID" value="<?php echo $JOBCODEID; ?>" class="form-control" >
													  	<input type="hidden" id="data<?php echo $currRow; ?>ITM_CODE" name="data[<?php echo $currRow; ?>][ITM_CODE]" value="<?php echo $ITM_CODE; ?>" class="form-control" style="max-width:300px;">
				                                 	</td>
												  	<td style="text-align:left; min-width:100px; vertical-align: middle;" nowrap>
														<div>
													  		<span><?php echo "$ITM_CODE - $ITM_NAME"; ?></span>
													  	</div>
													  	<div style="margin-left: 15px; font-style: italic;">
													  		<i class="text-muted fa fa-rss"></i>&nbsp;&nbsp;
													  		<?php
													  			$JOBDS 	= strlen($JOBDESC);
													  			if($JOBDS > 50)
													  			{
													  				echo cut_text ($JOBDESC, 45);
													  				echo " ...";
													  			}
													  			else
													  			{
													  				echo $JOBDESC;
													  			}
													  		?>
													  	</div>
				                                 	</td>
													<td style="text-align:right; vertical-align: middle;"> <!-- Item Bdget -->
				                                    	<?php echo number_format($ITM_RAPV, 2); ?>
				                                        <input type="hidden" id="data<?php echo $currRow; ?>ITM_RAPV" name="data[<?php echo $currRow; ?>][ITM_RAPV]" value="<?php echo $ITM_RAPV; ?>">
				                                        <input type="hidden" id="data<?php echo $currRow; ?>ITM_RAPP" name="data[<?php echo $currRow; ?>][ITM_RAPP]" value="<?php echo $ITM_RAPP; ?>">
				                                  	</td>
													<?php
														// CARI TOTAL WORKED BUDGET APPROVED
															$GTOT_REQ		= 0;
															$GTOT_REQAMN	= 0;
															$sqlTOTBUDG		= "SELECT SUM(REQ_VOLM) AS TOT_REQ1, SUM(REQ_AMOUNT) AS  TOT_REQ1AMN,
																					SUM(WO_QTY) AS TOT_REQ2, SUM(WO_AMOUNT) AS TOT_REQ2AMN
																				FROM tbl_joblist_detail A
																				WHERE A.ITM_CODE = '$ITM_CODE' AND A.PRJCODE = '$PRJCODE'
																					AND A.JOBCODEID = '$JOBCODEID'";
															$resTOTBUDG		= $this->db->query($sqlTOTBUDG)->result();
															foreach($resTOTBUDG as $rowTOTBUDG) :
																$TOT_REQ1		= $rowTOTBUDG->TOT_REQ1;
																$TOT_REQ1AMN	= $rowTOTBUDG->TOT_REQ1AMN;
																$TOT_REQ2		= $rowTOTBUDG->TOT_REQ2;
																$TOT_REQ2AMN	= $rowTOTBUDG->TOT_REQ2AMN;

																$GTOT_REQ 		= $TOT_REQ1 + $TOT_REQ2;
																$GTOT_REQAMN	= $TOT_REQ1AMN + $TOT_REQ2AMN;
															endforeach;

															$GTOT_PROP		= 0;
															$GTOT_PROPAMN	= 0;
															$sqlTOTPROP		= "SELECT SUM(A.ITM_VOLM) AS GTOT_PROP, SUM(A.PROP_TOTAL) AS  GTOT_PROPAMN
																				FROM tbl_bprop_detail A
																					INNER JOIN tbl_bprop_header B ON B.PROP_NUM = A.PROP_NUM
																				WHERE A.ITM_CODE = '$ITM_CODE' AND A.PRJCODE = '$PRJCODE'
																					AND A.JOBCODEID = '$JOBCODEID' AND A.PROP_NUM != '$PROP_NUM'
																					AND B.PROP_STAT IN (1,2,4)";
															$resTOTPROP		= $this->db->query($sqlTOTPROP)->result();
															foreach($resTOTPROP as $rowTOTPROP) :
																$GTOT_PROP		= $rowTOTPROP->GTOT_PROP;
																$GTOT_PROPAMN	= $rowTOTPROP->GTOT_PROPAMN;
															endforeach;
															
															if($ITM_UNITV == 'LS' )
															{
																$TOTPRQTY		= $GTOT_REQAMN;
																$ITM_RAPAMN 	= $ITM_RAPV * $ITM_RAPP;
																$ITM_REM_VOL	= $ITM_RAPV - $GTOT_REQ - $GTOT_PROP;
																$ITM_REM_AMN	= $ITM_RAPAMN - $GTOT_REQAMN - $GTOT_PROPAMN;
															}
															else
															{
																$TOTPRQTY 		= $GTOT_REQ;
																$ITM_REM_VOL	= $ITM_RAPV - $GTOT_REQ - $GTOT_PROP;
																$ITM_REM_AMN	= $ITM_RAPAMN - $GTOT_REQAMN - $GTOT_PROPAMN;
															}
													?>
												  	<td style="text-align:right; vertical-align: middle;">  <!-- Item Requested FOR INFORMATION ONLY -->
				                                    	<?php print number_format($TOTPRQTY, $decFormat); ?>
				                                        <input type="hidden" class="form-control" style="text-align:right" name="ITM_REM_VOL<?php echo $currRow; ?>" id="ITM_REM_VOL<?php echo $currRow; ?>" value="<?php echo $ITM_REM_VOL; ?>" >
				                                        <input type="hidden" class="form-control" style="text-align:right" name="ITM_REM_AMN<?php echo $currRow; ?>" id="ITM_REM_AMN<?php echo $currRow; ?>" value="<?php echo $ITM_REM_AMN; ?>" >
				                                 	</td>
				                                    <td style="text-align:right; vertical-align: middle;" nowrap> <!-- Item Request Now -- PR_VOLM -->
				                                        <?php
															if($PROP_STAT == 1 || $PROP_STAT || 4) 
															{
																?>
																	<input type="text" name="ITM_VOLM<?php echo $currRow; ?>" id="ITM_VOLM<?php echo $currRow; ?>" value="<?php echo number_format($ITM_VOLM, $decFormat); ?>" class="form-control" style="min-width:100px; max-width:100px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getWOVol(this,<?php echo $currRow; ?>);" >
																	<input type="hidden" name="data[<?php echo $currRow; ?>][ITM_VOLM]" id="data<?php echo $currRow; ?>ITM_VOLM" value="<?php echo $ITM_VOLM; ?>" class="form-control" style="max-width:300px;" >
						                                   		<?php 
						                                	}
						                                    else
						                                    {
																echo number_format($ITM_VOLM, $decFormat);
						                                    	?>
																	<input type="text" name="ITM_VOLM<?php echo $currRow; ?>" id="ITM_VOLM<?php echo $currRow; ?>" value="<?php echo number_format($ITM_VOLM, $decFormat); ?>" class="form-control" style="min-width:100px; max-width:100px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getWOVol(this,<?php echo $currRow; ?>);" >
																	<input type="hidden" name="data[<?php echo $currRow; ?>][ITM_VOLM]" id="data<?php echo $currRow; ?>ITM_VOLM" value="<?php echo $ITM_VOLM; ?>" class="form-control" style="max-width:300px;" >
							                                    <?php
							                                }
				                                        ?>
				                                    </td>
				                                    <td style="text-align:right; vertical-align: middle;" nowrap>
				                                        <?php
															if($PROP_STAT == 1 || $PROP_STAT || 4) 
															{
																?>
																	<input type="text" name="ITM_PRICE<?php echo $currRow; ?>" id="ITM_PRICE<?php echo $currRow; ?>" value="<?php echo number_format($ITM_PRICE, $decFormat); ?>" class="form-control" style="min-width:100px; max-width:100px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getWOPrice(this,<?php echo $currRow; ?>);">
																	<input type="hidden" name="data[<?php echo $currRow; ?>][ITM_PRICE]" id="data<?php echo $currRow; ?>ITM_PRICE" value="<?php echo $ITM_PRICE; ?>" class="form-control" style="min-width:100px; max-width:100px;" >
						                                   		<?php 
						                                	}
						                                    else
						                                    {
																echo number_format($ITM_PRICE, $decFormat);
						                                    	?>
																	<input type="text" name="ITM_PRICE<?php echo $currRow; ?>" id="ITM_PRICE<?php echo $currRow; ?>" value="<?php echo number_format($ITM_PRICE, $decFormat); ?>" class="form-control" style="min-width:100px; max-width:100px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getWOPrice(this,<?php echo $currRow; ?>);">
																	<input type="hidden" name="data[<?php echo $currRow; ?>][ITM_PRICE]" id="data<?php echo $currRow; ?>ITM_PRICE" value="<?php echo $ITM_PRICE; ?>" class="form-control" style="min-width:100px; max-width:100px;" >
							                                    <?php
							                                }
				                                        ?>
				                                    </td>
												  	<td nowrap style="text-align:center; vertical-align: middle;">
													  <?php echo $ITM_UNIT; ?>
				                                    	<input type="hidden" name="data[<?php echo $currRow; ?>][ITM_UNIT]" id="data<?php echo $currRow; ?>ITM_UNIT" value="<?php echo $ITM_UNIT; ?>" class="form-control" style="max-width:300px;" >
				                                    </td>
												  	<td nowrap style="text-align:right; vertical-align: middle;">
				                                     	<?php if($disRow == 1) { ?>
				                                     		<?php echo number_format($PROP_TOTAL, $decFormat); ?>
				                                    		<input type="hidden" class="form-control" style="min-width:100px; max-width:100px; text-align:right" name="PROP_TOTAL<?php echo $currRow; ?>" id="PROP_TOTAL<?php echo $currRow; ?>" value="<?php print number_format($PROP_TOTAL, $decFormat); ?>" size="10" disabled >
					                                    <?php } else { ?>
				                                    		<input type="text" class="form-control" style="min-width:100px; max-width:100px; text-align:right" name="PROP_TOTAL<?php echo $currRow; ?>" id="PROP_TOTAL<?php echo $currRow; ?>" value="<?php print number_format($PROP_TOTAL, $decFormat); ?>" size="10" disabled >
					                                    <?php } ?>
				                                        
				                                        <input type="hidden" name="data[<?php echo $currRow; ?>][PROP_TOTAL]" id="data<?php echo $currRow; ?>PROP_TOTAL" value="<?php echo $PROP_TOTAL; ?>" class="form-control" style="max-width:150px; text-align:right" onKeyPress="return isIntOnlyNew(event);" >
				                                    </td>
												  	<td style="text-align:left;" nowrap>
				                                     	<?php if($disRow == 1) { ?>
				                                     		<?php echo $PROP_DESC; ?>
						                                    <input type="hidden" name="data[<?php echo $currRow; ?>][PROP_DESC]" id="data<?php echo $currRow; ?>PROP_DESC" size="20" value="<?php echo $PROP_DESC; ?>" class="form-control" style="min-width:130px; max-width:150px; text-align:left">
					                                    <?php } else { ?>
						                                    <input type="text" name="data[<?php echo $currRow; ?>][PROP_DESC]" id="data<?php echo $currRow; ?>PROP_DESC" size="20" value="<?php echo $PROP_DESC; ?>" class="form-control" style="min-width:130px; max-width:150px; text-align:left">
					                                    <?php } ?>    
				                                    </td>
								          		</tr>
			                              	<?php
			                             	endforeach;
											?>
			                                	<input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currRow; ?>">
			                                <?php
			                            }
			                            if($task == 'add')
			                            {
			                            	?>
			                                  <input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currRow; ?>">
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
										if($PROP_STAT == 1 && $ISCREATE == 1)
										{
											?>
												<button class="btn btn-primary" id="btnSave">
												<i class="fa fa-save"></i></button>&nbsp;
											<?php
										}
									}
									elseif($task=='edit')
									{
										if(($ISCREATE == 1 && $ISAPPROVE == 1) && ($PROP_STAT == 1))
										{
											?>
		                                        <button class="btn btn-primary" id="btnSave">
		                                        <i class="fa fa-save"></i></button>&nbsp;
											<?php
										}
										else if($ISCREATE == 1 && ($PROP_STAT == 1 || $PROP_STAT == 4))
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

									$backURL	= site_url('c_project/c_budprop/gallpR0p/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
									echo anchor("$backURL",'<button class="btn btn-danger" type="button" id="btnBack"><i class="fa fa-reply"></i></button>');
		                        ?>
		                    </div>
		                </div>
		          	</div>

			        <div class="col-md-12">
						<?php
                            $DOC_NUM	= $PROP_NUM;
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
					$Active1Cls		= "class='active'";
					$Active2Cls		= "";
					$Active3Cls		= "";
					$Active4Cls		= "";
		    	?>
		        <div class="modal fade" id="mdl_addItm" name='mdl_addItm' tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		            <div class="modal-dialog">
			            <div class="modal-content">
			                <div class="modal-body">
								<div class="row">
							    	<div class="col-md-12">
						              	<ul class="nav nav-tabs">
						                    <li id="li1" <?php echo $Active1Cls; ?>>
						                    	<a href="#itm1" data-toggle="tab" onClick="setType(1)"><?=$ItemList?></a>
						                    </li>	
						                    <li id="li2">
						                    	<a href="#itm2" data-toggle="tab" onClick="setType(2)">Subkon</a>
						                    </li>
						                    <li id="li3">
						                    	<a href="#itm3" data-toggle="tab" onClick="setType(3)"><?=$Wage?></a>
						                    </li>
						                    <li id="li4">
						                    	<a href="#itm4" data-toggle="tab" onClick="setType(4)"><?=$JobList?></a>
						                    </li>
						                </ul>
							            <div class="box-body">
							            	<div class="<?php echo $Active1; ?> tab-pane" id="itm1">
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

							            	<div class="<?php echo $Active2; ?> tab-pane" id="itm2" style="display: none;">
		                                        <div class="form-group">
		                                        	<form method="post" name="frmSearch2" id="frmSearch2" action="">
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
							            		<div class="col-md-4">
													<div class="box box-primary">
														<div class="box-header with-border" style="display: none;">
															<i class="fa fa-cloud-upload"></i>
															<h3 class="box-title">&nbsp;</h3>
														</div>
														<div class="box-body">
				                                        	<form method="post" name="frmSearch4" id="frmSearch4" action="">
				                                        		<div class="search-table-outter">
						                                            <table id="example4" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
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
		                                                    	<button class="btn btn-primary" type="button" id="btnDetail4" name="btnDetail4">
		                                                    		<i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?>
		                                                    	</button>
		                                      					<button type="button" id="idClose4" class="btn btn-danger" data-dismiss="modal">
		                                                    		<i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>
		                                                    	</button>
																<button class="btn btn-warning" type="button" id="idRefresh4" title="Refresh" >
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
				                                        	<form method="post" name="frmSearch4" id="frmSearch4" action="">
				                                        		<div class="search-table-outter">
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
						                                        </div>
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

				    	$('#example3').DataTable(
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

				    	$('#example4').DataTable(
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

				    	$('#example5').DataTable(
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
					});

					function setType(tabType)
					{
						if(tabType == 1)
						{
							document.getElementById('itm1').style.display	= '';
							document.getElementById('itm2').style.display	= 'none';
							document.getElementById('itm3').style.display	= 'none';
							document.getElementById('itm4').style.display	= 'none';
						}
						else if(tabType == 2)
						{
							document.getElementById('itm1').style.display	= 'none';
							document.getElementById('itm2').style.display	= '';
							document.getElementById('itm3').style.display	= 'none';
							document.getElementById('itm4').style.display	= 'none';
						}
						else if(tabType == 3)
						{
							document.getElementById('itm1').style.display	= 'none';
							document.getElementById('itm2').style.display	= 'none';
							document.getElementById('itm3').style.display	= '';
							document.getElementById('itm4').style.display	= 'none';
						}
						else if(tabType == 4)
						{
							document.getElementById('itm1').style.display	= 'none';
							document.getElementById('itm2').style.display	= 'none';
							document.getElementById('itm3').style.display	= 'none';
							document.getElementById('itm4').style.display	= '';
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
																<input type="text" name="PROP_CVOLX" id="PROP_CVOLX" value="" class="form-control" style="min-width:80px; max-width:110px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgCncVol(this.value);" >
																<input type="hidden" name="itmRow" id="itmRow" value="">
																<input type="hidden" name="PROP_RVOL" id="PROP_RVOL" value="">
																<input type="hidden" name="PROP_CVOL" id="PROP_CVOL" value="">
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
						setInterval(function(){countTask()}, 1000);
					});

					function countTask()
					{
						var formData 	= {
											MenuCode		: "<?=$MenuCode?>",
											JournalType		: "",
											tblName			: 'tbl_bprop_header',
											attDate			: 'PROP_DATE',
											attDOCODE		: 'PROP_CODE',
											attPRJCODE		: 'PRJCODE',
											PRJCODE			: "<?=$PRJCODE?>",
											CREATER			: "<?=$DefEmp_ID?>",
											attCATEG		: $('#PROP_CATEG').val()		// FOR SPK (UPAH/U, ALAT/A, SUBKON/S)
										};
						$.ajax({
				            type: 'POST',
				            url: "<?php echo site_url('__l1y/getLastNumb')?>",
				            data: formData,
				            success: function(response)
				            {
				            	//console.log(response)

				            	$('#PROP_CODE').val(response);
				            }
				        });
					}
				<?php
			}
		// END : GENERATE MANUAL CODE
	?>

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
		$('#datepicker').datepicker({
		  autoclose: true
		});

		//Date picker
		$('#datepicker1').datepicker({
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
	    		PROP_CATEG	= document.getElementById("PROP_CATEG").value;
				FPA_CODE	= document.getElementById("FPA_CODE1").value;
				if(PROP_CATEG == 'SALT' && FPA_CODE == '')
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
					let myObj 	= document.getElementById('ITM_VOLM'+i);
					var values 	= typeof myObj !== 'undefined' ? myObj : '';

					//console.log(i+' = '+ values)
					
					if(values != null)
					{
						var ITM_VOLM	= parseFloat(eval(document.getElementById('ITM_VOLM'+i)).value.split(",").join(""));
						if(ITM_VOLM == 0)
						{
							swal('<?php echo $alert1; ?>',
							{
								icon:"warning",
							})
							.then(function()
							{
								document.getElementById('ITM_VOLM'+i).value = '0';
								document.getElementById('ITM_VOLM'+i).focus();
							});
							return false;
						}
					}
				}

				PROP_DPREF1	= document.getElementById("PROP_DPREF1").value;
				if(PROP_DPREF1 != '')
				{
					PROP_DPPER	= document.getElementById('PROP_DPPER').value;
					if(PROP_DPPER == 0)
					{
						swal('<?php echo $alert7; ?>',
						{
							icon:"warning",
						})
						.then(function()
						{
							document.getElementById('PROP_DPPER').focus();
						});
						return false;
					}
				}

				PROP_DPPER	= document.getElementById("PROP_DPPER").value;
				/*if(PROP_DPPER != 0)
				{
					PROP_DPREF1	= document.getElementById('PROP_DPREF1').value;
					if(PROP_DPREF1 == '')
					{
						swal('<?php echo $alert8; ?>');
						document.getElementById('PROP_DPREF1').focus();
						return false;
					}
				}*/

				PROP_STAT		= document.getElementById("PROP_STAT").value;
				if(PROP_STAT == 6)
				{
					PROP_MEMO		= document.getElementById('PROP_MEMO').value;
					if(PROP_MEMO == '')
					{
						swal('<?php echo $alert6; ?>',
						{
							icon:"warning",
						})
						.then(function()
						{
							document.getElementById('PROP_MEMO').focus();
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
	function check_all(chk)						// OK
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
	function pickThis(thisobj,ke)				// OK
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

	function deleteRow(btn)						// OK
	{
		var row = document.getElementById("tr_" + btn);
		row.remove();
	}

	function add_item(strItem)					// OK
	{
		arrItem = strItem.split('|');
		var objTable, objTR, objTD, intIndex, arrItem;
		var PROP_NUM 	= "<?php echo $PROP_NUM; ?>";

		var PROP_CODEx 	= "<?php echo $PROP_CODE; ?>";
		ilvl = arrItem[1];

		var decFormat	= document.getElementById('decFormat').value;

		validateDouble(arrItem[4])
		if(validateDouble(arrItem[4]))
		{
			swal("Double Item : "+arrItem[4]+' '+arrItem[5]);
			return;
		}

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
			PROP_QTY		= parseFloat(arrItem[16]);
			PROP_AMOUNT		= parseFloat(arrItem[17]);
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
			/*PROP_QTY		= arrItem[18];
			PROP_AMOUNT		= arrItem[19];
			OPN_QTY			= arrItem[20];
			OPN_AMOUNT		= arrItem[21];*/

			var DescAcc 	= '<div style="font-style: italic;"><i class="text-muted fa fa-chevron-circle-right"></i>&nbsp;&nbsp;'+JOB_ACC_UM+'</div>';

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

			//document.frm.rowCount.value = intIndex;
			
			objTR 			= objTable.insertRow(intTable);
			objTR.id 		= 'tr_' + intIndex;
		// START : SETTING ROWS INDEX

		// PROP_NUM, PROP_CODE
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = "center";
			objTD.noWrap = true;
			objTD.innerHTML = '<a href="#" onClick="deleteRow('+intIndex+')" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a><input type="hidden" id="chk" name="chk" value="'+intIndex+'" width="10" size="15" readonly class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'PROP_NUM" name="data['+intIndex+'][PROP_NUM]" value="'+PROP_NUM+'" class="form-control"><input type="hidden" id="data'+intIndex+'PROP_CODE" name="data['+intIndex+'][PROP_CODE]" value="'+PROP_CODEx+'" class="form-control"><input type="hidden" name="data['+intIndex+'][JOBCODEID]" id="data'+intIndex+'JOBCODEID" value="'+JOBCODEID+'" class="form-control" ><input type="hidden" id="data'+intIndex+'ITM_CODE" name="data['+intIndex+'][ITM_CODE]" value="'+ITM_CODE+'" class="form-control" style="max-width:300px;">';

		// ITM_NAME
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = 'left';
			objTD.noWrap = true;
			objTD.innerHTML = ''+ITM_NAME+DescAcc+'';

		// ITM_RAPV, ITM_RAPP
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = ''+doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_BUDGV)), 2))+'<input type="hidden" id="data'+intIndex+'ITM_RAPV" name="data['+intIndex+'][ITM_RAPV]" value="'+ITM_BUDG_VOL+'"><input type="hidden" id="data'+intIndex+'ITM_RAPP" name="data['+intIndex+'][ITM_RAPP]" value="'+ITM_BUDG_AMN+'">';

		//INFORMATION ONLY : TOT_USEDQTY
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = ''+doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TOT_USEDQTY)), 2))+'<input type="hidden" class="form-control" style="text-align:right" name="ITM_REM_VOL'+intIndex+'" id="ITM_REM_VOL'+intIndex+'" value="'+ITM_REM_VOL+'" ><input type="hidden" class="form-control" style="text-align:right" name="ITM_REM_AMN'+intIndex+'" id="ITM_REM_AMN'+intIndex+'" value="'+ITM_REM_AMN+'" >';

		// ITM_VOLM (NOW) : ITM_VOLM
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			WOVOLM 			= parseFloat(ITM_REM_VOL);
			objTD.innerHTML = '<input type="text" name="ITM_VOLM'+intIndex+'" id="ITM_VOLM'+intIndex+'" value="'+doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_REM_VOL)), 2))+'" class="form-control" style="min-width:100px; max-width:100px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getWOVol(this,'+intIndex+');" ><input type="hidden" name="data['+intIndex+'][ITM_VOLM]" id="data'+intIndex+'ITM_VOLM" value="'+ITM_REM_VOL+'" class="form-control" style="max-width:300px;" >';
			
		// ITM_PRICE
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			if(ITM_UNIT == 'LS' || ITM_UNIT == 'LUMP')
			{
				WOPRICE 			= parseFloat(ITM_PRICE);
				objTD.innerHTML = '<input type="text" name="ITM_PRICE'+intIndex+'" id="ITM_PRICE'+intIndex+'" value="'+doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_PRICEV)), 2))+'" class="form-control" style="min-width:100px; max-width:100px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getWOPrice(this,'+intIndex+');"><input type="hidden" name="data['+intIndex+'][ITM_PRICE]" id="data'+intIndex+'ITM_PRICE" value="'+ITM_PRICE+'" class="form-control" style="min-width:100px; max-width:100px;" >';
			}
			else
			{
				WOPRICE 			= parseFloat(ITM_PRICE);
				objTD.innerHTML = '<input type="text" name="ITM_PRICE'+intIndex+'" id="ITM_PRICE'+intIndex+'" value="'+doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_PRICEV)), 2))+'" class="form-control" style="min-width:100px; max-width:100px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getWOPrice(this,'+intIndex+');" ><input type="hidden" name="data['+intIndex+'][ITM_PRICE]" id="data'+intIndex+'ITM_PRICE" value="'+ITM_PRICE+'" class="form-control" style="min-width:100px; max-width:100px;" >';
			}

		PROP_TOTAL 		= parseFloat(WOVOLM) * parseFloat(WOPRICE);
			
		// ITM_UNIT
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'center';
			objTD.innerHTML = ''+ITM_UNIT+'<input type="hidden" name="data['+intIndex+'][ITM_UNIT]" id="data'+intIndex+'ITM_UNIT" value="'+ITM_UNIT+'" class="form-control" style="max-width:300px;" >';

		// WO JUMLAH PRICE X VOLM -- PROP_TOTAL
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = '<input type="hidden" name="data['+intIndex+'][PROP_TOTAL]" id="data'+intIndex+'PROP_TOTAL" value="'+PROP_TOTAL+'" class="form-control" style="min-width:100px; max-width:150px; text-align:right" onKeyPress="return isIntOnlyNew(event);" ><input type="text" class="form-control" style="min-width:100px; max-width:100px; text-align:right" name="PROP_TOTAL'+intIndex+'" id="PROP_TOTAL'+intIndex+'" value="'+doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PROP_TOTAL)), 2))+'" size="10" disabled >';

		// PROP_DESC
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'center';
			objTD.innerHTML = '<input type="text" name="data['+intIndex+'][PROP_DESC]" id="data'+intIndex+'PROP_DESC" size="20" value="" class="form-control" style="min-width:130px; max-width:150px; text-align:left">';

		document.getElementById('totalrow').value = intIndex;
		getTotalWO();
	}

	function getWOVol(thisVal1, row)			// OK
	{
		var decFormat	= document.getElementById('decFormat').value;

		thisVal 		= parseFloat(Math.abs(eval(thisVal1).value.split(",").join("")));
		ITM_VOLM		= parseFloat(thisVal);
		PROP_PRICE		= parseFloat(document.getElementById('data'+row+'ITM_PRICE').value);
		PROP_TOTAL 		= parseFloat(ITM_VOLM) * parseFloat(PROP_PRICE);

		ITM_REM_VOL		= parseFloat(document.getElementById('ITM_REM_VOL'+row).value);			// Remain Budget (Vol)
		ITM_REM_AMN		= parseFloat(document.getElementById('ITM_REM_AMN'+row).value);			// Remain Budget (Amn)
		
		ITMUNIT			= document.getElementById('data'+row+'ITM_UNIT').value;
		ITM_UNIT 		= ITMUNIT.toUpperCase();
		console.log('e = '+ITM_UNIT)

		if(ITM_UNIT == 'LS')
		{
			if(PROP_TOTAL > ITM_REM_AMN)
			{
				swal('<?php echo $alertGreat; ?>',
				{
					icon:"warning",
				})
				.then(function()
				{
					ITM_VOLM 	= 0;
					document.getElementById('data'+row+'ITM_VOLM').value	= 0;
					document.getElementById('ITM_VOLM'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(0)), 2));
					document.getElementById('ITM_VOLM'+row).focus();

					PROP_TOTAL 	= parseFloat(ITM_VOLM) * parseFloat(PROP_PRICE);

					document.getElementById('data'+row+'PROP_TOTAL').value 	= 0;
					console.log('j')
					document.getElementById('PROP_TOTAL'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(0)), 2));
					getTotalWO();
				});
			}
			else
			{
				PROP_TOTAL 	= parseFloat(ITM_VOLM) * parseFloat(PROP_PRICE);

				document.getElementById('data'+row+'ITM_VOLM').value	= ITM_VOLM;
				document.getElementById('ITM_VOLM'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_VOLM)), 2));
				document.getElementById('data'+row+'PROP_TOTAL').value 	= RoundNDecimal(parseFloat(Math.abs(PROP_TOTAL)), 2);
				document.getElementById('PROP_TOTAL'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PROP_TOTAL)), 2));
				getTotalWO();
			}
		}
		else
		{
			if(PROP_TOTAL > ITM_REM_AMN)
			{
				swal('<?php echo $alertGreat; ?>',
				{
					icon:"warning",
				})
				.then(function()
				{
					ITM_VOLM 	= parseFloat(ITM_REM_VOL);
					document.getElementById('data'+row+'ITM_VOLM').value	= ITM_VOLM;
					document.getElementById('ITM_VOLM'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_VOLM)), 2));
					document.getElementById('ITM_VOLM'+row).focus();

					PROP_TOTAL 	= parseFloat(ITM_VOLM) * parseFloat(PROP_PRICE);

					document.getElementById('data'+row+'ITM_VOLM').value	= ITM_VOLM;
					document.getElementById('ITM_VOLM'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_VOLM)), 2));
					document.getElementById('data'+row+'PROP_TOTAL').value 	= RoundNDecimal(parseFloat(Math.abs(PROP_TOTAL)), 2);
					document.getElementById('PROP_TOTAL'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PROP_TOTAL)), 2));
					getTotalWO();
				});
			}
			else
			{
				PROP_TOTAL 	= parseFloat(ITM_VOLM) * parseFloat(PROP_PRICE);

				document.getElementById('data'+row+'ITM_VOLM').value	= ITM_VOLM;
				document.getElementById('ITM_VOLM'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_VOLM)), 2));
				document.getElementById('data'+row+'PROP_TOTAL').value 	= RoundNDecimal(parseFloat(Math.abs(PROP_TOTAL)), 2);
				document.getElementById('PROP_TOTAL'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PROP_TOTAL)), 2));
				getTotalWO();
			}
		}
	}

	function getWOPrice(thisVal1, row)			// OK
	{
		var decFormat	= document.getElementById('decFormat').value;

		thisVal 		= parseFloat(Math.abs(eval(thisVal1).value.split(",").join("")));

		PROP_PRICE		= parseFloat(thisVal);
		ITM_VOLM			= parseFloat(document.getElementById('data'+row+'ITM_VOLM').value);
		PROP_TOTAL 		= parseFloat(ITM_VOLM) * parseFloat(PROP_PRICE);

		ITM_REM_VOL		= parseFloat(document.getElementById('ITM_REM_VOL'+row).value);			// Remain Budget (Vol)
		ITM_REM_AMN		= parseFloat(document.getElementById('ITM_REM_AMN'+row).value);			// Remain Budget (Amn)
		
		var ITMUNIT		= document.getElementById('data'+row+'ITM_UNIT').value;
		var ITM_UNIT 	= ITMUNIT.toUpperCase();

		if(PROP_TOTAL > ITM_REM_AMN)
		{
			var PROP_PRICE 	= parseFloat(ITM_REM_AMN) / parseFloat(ITM_REM_VOL);

			swal('<?php echo $alertGreat; ?>',
			{
				icon:"warning",
			})
			.then(function()
			{
				document.getElementById('data'+row+'ITM_PRICE').value	= PROP_PRICE;
				document.getElementById('ITM_PRICE'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PROP_PRICE)), 2));
				document.getElementById('ITM_PRICE'+row).focus();

				PROP_TOTAL 	= parseFloat(ITM_VOLM) * parseFloat(PROP_PRICE);

				document.getElementById('data'+row+'ITM_VOLM').value	= ITM_VOLM;
				document.getElementById('ITM_VOLM'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_VOLM)), 2));
				document.getElementById('data'+row+'PROP_TOTAL').value 	= RoundNDecimal(parseFloat(Math.abs(PROP_TOTAL)), 2);
				document.getElementById('PROP_TOTAL'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PROP_TOTAL)), 2));
				getTotalWO();

			});
		}
		else
		{
			document.getElementById('data'+row+'ITM_PRICE').value	= PROP_PRICE;
			document.getElementById('ITM_PRICE'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PROP_PRICE)), 2));

			PROP_TOTAL 	= parseFloat(ITM_VOLM) * parseFloat(PROP_PRICE);

			document.getElementById('data'+row+'ITM_VOLM').value	= ITM_VOLM;
			document.getElementById('ITM_VOLM'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_VOLM)), 2));
			document.getElementById('data'+row+'PROP_TOTAL').value 	= RoundNDecimal(parseFloat(Math.abs(PROP_TOTAL)), 2);
			document.getElementById('PROP_TOTAL'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PROP_TOTAL)), 2));
			getTotalWO();
		}
	}

	function checkInp()
	{
		SPLCODE		= document.getElementById('SPLCODE').value;
		PROP_NOTE		= document.getElementById('PROP_NOTE').value;
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

		if(PROP_NOTE == '')
		{
			swal('<?php echo $alert10; ?>',
			{
				icon:"warning",
			})
			.then(function()
			{
				document.getElementById('PROP_NOTE').focus();
			});
			return false;
		}

		totalrow	= document.getElementById('totalrow').value;
		if(totalrow == 0)
		{
			swal('<?php echo $alert9; ?>',
			{
				icon: "warning",
			});
			return false;
		}
		document.getElementById('btnSave').style.display = 'none';
		document.getElementById('btnBack').style.display = 'none';
	}

	function validateDouble(vcode)
	{
		var thechk=new Array();
		var duplicate = false;
		console.log('a')

		// START : SETTING ROWS INDEX
			objTable 		= document.getElementById('tbl');
			panjang 		= objTable.rows.length;

		console.log('panjang = '+panjang)
		var panjang = panjang - 1;
		for (var i=0;i<panjang;i++)
		{
		console.log('f1')
			var temp = 'tr_'+parseInt(i+1);
			if(i>0)
			{
		console.log('g')
				var elitem1= document.getElementById('data'+i+'ITM_CODE').value;
		console.log('elitem1 = '+elitem1+' == '+vcode)
				if (elitem1 == vcode)
				{
					duplicate = true;
					break;
				}
			}
		}
		return duplicate;
	}

	function getConvertion(thisVal1, row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		ITM_UNIT			= document.getElementById('data'+row+'ITM_UNIT').value;

		itemConvertion		= 1;
		ITM_VOLM 			= parseFloat(document.getElementById('data'+row+'ITM_VOLM').value);		// wo volm
		ITM_PRICE			= parseFloat(document.getElementById('data'+row+'ITM_PRICE').value);	// Item Price
		ITM_PRICE1			= parseFloat(document.getElementById('ITM_PRICE'+row).value);			// Item Price
		ITM_BUDGQTY			= parseFloat(document.getElementById('ITM_BUDGQTY'+row).value);			// Budget Qty
		ITM_BUDG			= parseFloat(document.getElementById('data'+row+'ITM_BUDG').value);		// Budget Amount
		TOTWOQTY			= document.getElementById('TOTWOQTY'+row).value;						// Total Requested

		TOTPRAMOUNT			= parseFloat(TOTWOQTY) * parseFloat(ITM_PRICE);							// Total Requested Amount
		//REQ_NOW_QTY1		= parseFloat(document.getElementById('ITM_VOLM'+row).value);				// Request Qty - Now
		if(ITM_VOLM == 0)
			REQ_NOW_QTY1		= parseFloat(eval(document.getElementById('ITM_VOLM'+row)).value.split(",").join(""));
		else
			REQ_NOW_QTY1		= parseFloat(document.getElementById('data'+row+'ITM_VOLM').value);

		REQ_NOW_QTY2		= REQ_NOW_QTY1 * itemConvertion;						// Request Qty 2 - Now
		REQ_NOW_AMOUNT		= parseFloat(REQ_NOW_QTY1) * parseFloat(ITM_PRICE);						// Request Qty Amount - Now
		//swal(REQ_NOW_QTY1+'*'+ITM_PRICE+'='+REQ_NOW_AMOUNT);
		//swal(REQ_NOW_AMOUNT)
		if(ITM_UNIT == 'LS')
		{
			ITM_PRICE		= 1;
			REQ_NOW_AMOUNT	= parseFloat(REQ_NOW_QTY1) * parseFloat(ITM_PRICE);
			TOTPRAMOUNT		= parseFloat(TOTWOQTY) * parseFloat(ITM_PRICE1);
			REM_PROP_QTY		= parseFloat(ITM_BUDG) - parseFloat(TOTPRAMOUNT);
			REM_PROP_AMOUNT	= parseFloat(ITM_BUDG) - parseFloat(TOTPRAMOUNT);
		}
		else
		{
			REQ_NOW_AMOUNT	= parseFloat(REQ_NOW_QTY1) * parseFloat(ITM_PRICE);
			REM_PROP_QTY		= parseFloat(ITM_BUDGQTY) - parseFloat(TOTWOQTY);
			REM_PROP_AMOUNT	= parseFloat(ITM_BUDG) - parseFloat(TOTPRAMOUNT);
		}

		//swal(REQ_NOW_AMOUNT+ ">" +REM_PROP_AMOUNT)

		if(REQ_NOW_AMOUNT > REM_PROP_AMOUNT)
		{
			REM_PROP_QTYV		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(REM_PROP_QTY)),decFormat));
			REM_PROP_AMOUNTV	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(REM_PROP_AMOUNT)),decFormat));
			swal('Request Qty is Greater than Budget. Maximum Qty is '+REM_PROP_QTY+' or in Amount is '+REM_PROP_AMOUNT);

			document.getElementById('data'+row+'ITM_VOLM').value 	= REM_PROP_QTY;
			document.getElementById('ITM_VOLM'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(REM_PROP_QTY)),decFormat));

			document.getElementById('data'+row+'ITM_VOLM').value 	= REM_PROP_QTY;
			document.getElementById('ITM_VOLM'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(REM_PROP_QTY)),decFormat));
			NEW_PRICE = parseFloat(REM_PROP_AMOUNT / REM_PROP_QTY);
			document.getElementById('data'+row+'ITM_PRICE').value	= NEW_PRICE;
			document.getElementById('ITM_PRICE'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(NEW_PRICE)),decFormat));

			REQ_NOW_AMOUNT	= parseFloat(REM_PROP_QTY) * parseFloat(NEW_PRICE);
			document.getElementById('data'+row+'PROP_TOTAL').value 	= REQ_NOW_AMOUNT;
			document.getElementById('PROP_TOTAL'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(REQ_NOW_AMOUNT)),decFormat));

			//GET DISCOUNT VALUE
			// PROP_DISC				= parseFloat($("#data"+row+"PROP_DISC").val()); // discount (%)
			// PROP_DISCP			= parseFloat($("#data"+row+"PROP_DISCP").val()); // discount Price

			// GET TAX VALUE
			TAXCODE1	= document.getElementById('TAXCODE1'+row).value;
			TAXCODE2	= document.getElementById('TAXCODE2'+row).value;
			TAXPRICE1			= 0;
			TAXPRICE2			= 0;
			PROP_TOTAL2			= REQ_NOW_AMOUNT;
			if(REQ_NOW_AMOUNT == 0){
				PROP_TOTAL2_POT	= 0;
			}else{
				PROP_TOTAL2_POT	= REQ_NOW_AMOUNT;
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

			PROP_TOTAL2	= parseFloat(REQ_NOW_AMOUNT + TAXPRICE1 - TAXPRICE2);
			PROP_TOTAL2_POT	= parseFloat(PROP_TOTAL2);

			document.getElementById('data'+row+'PROP_TOTAL2').value 	= PROP_TOTAL2_POT; // after tax
			document.getElementById('PROP_TOTAL2X'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PROP_TOTAL2_POT)),decFormat));
			document.getElementById('data'+row+'TAXPRICE1').value 	= TAXPRICE1;
			document.getElementById('data'+row+'TAXPRICE2').value 	= TAXPRICE2;

			//COUNT TOTAL SPK
			var totrow 		= document.getElementById('totalrow').value;

			var GTOTAL_WO	= 0;
			for(i=1;i<=totrow;i++)
			{
				let myObj 	= document.getElementById('data'+i+'PROP_TOTAL2');
				var values 	= typeof myObj !== 'undefined' ? myObj : '';

				//console.log(i+' = '+ values)
				
				if(values != null)
				{
					var PROP_TOTITEM	= parseFloat(document.getElementById('data'+i+'PROP_TOTAL2').value);
					GTOTAL_WO		= parseFloat(GTOTAL_WO + PROP_TOTITEM);
				}
			}
			document.getElementById('PROP_VALUE').value = GTOTAL_WO;
			document.getElementById('PROP_VALUEX').value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(GTOTAL_WO)),decFormat));

			return false;
		}
		document.getElementById('data'+row+'PROP_TOTAL').value 	= REQ_NOW_AMOUNT;
		document.getElementById('PROP_TOTAL'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(REQ_NOW_AMOUNT)),decFormat));

		document.getElementById('data'+row+'ITM_VOLM').value 	= REQ_NOW_QTY1;
		document.getElementById('ITM_VOLM'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(REQ_NOW_QTY1)),decFormat));

		//GET DISCOUNT VALUE
		// PROP_DISC				= parseFloat($("#data"+row+"PROP_DISC").val()); // discount (%)
		// PROP_DISCP			= parseFloat($("#data"+row+"PROP_DISCP").val()); // discount Price

		// GET TAX VALUE
		TAXCODE1			= document.getElementById('TAXCODE1'+row).value;
		TAXCODE2			= document.getElementById('TAXCODE2'+row).value;
		TAXPRICE1			= 0;
		TAXPRICE2			= 0;
		PROP_TOTAL2			= REQ_NOW_AMOUNT;
		if(REQ_NOW_AMOUNT == 0){
			PROP_TOTAL2_POT	= 0;
		}else{
			PROP_TOTAL2_POT	= REQ_NOW_AMOUNT;
		}
		//swal(REQ_NOW_AMOUNT);
		//swal("PROP_TOTAL2_POT	= "+REQ_NOW_AMOUNT+ "-" +PROP_DISCP);
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

		PROP_TOTAL2		= parseFloat(REQ_NOW_AMOUNT + TAXPRICE1 - TAXPRICE2);
		PROP_TOTAL2_POT	= parseFloat(PROP_TOTAL2);

		document.getElementById('data'+row+'PROP_TOTAL2').value 	= PROP_TOTAL2_POT; // After Plus or Min Tax - discount
		document.getElementById('PROP_TOTAL2X'+row).value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PROP_TOTAL2_POT)),decFormat));
		document.getElementById('data'+row+'TAXPRICE1').value 	= TAXPRICE1;
		document.getElementById('data'+row+'TAXPRICE2').value 	= TAXPRICE2;

		//COUNT TOTAL SPK
		var totrow 		= document.getElementById('totalrow').value;

		var GTOTAL_WO	= 0;
		for(i=1;i<=totrow;i++)
		{
			var PROP_TOTITEM	= parseFloat(document.getElementById('data'+i+'PROP_TOTAL2').value);
			GTOTAL_WO		= parseFloat(GTOTAL_WO + PROP_TOTITEM);
		}
		document.getElementById('PROP_VALUE').value = GTOTAL_WO;
		document.getElementById('PROP_VALUEX').value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(GTOTAL_WO)),decFormat));
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
			let myObj 	= document.getElementById('data'+i+'PROP_TOTAL');
			var values 	= typeof myObj !== 'undefined' ? myObj : '';

			//console.log(i+' = '+ values)
			
			if(values != null)
			{
				PROP_TOTAL	= parseFloat(document.getElementById('data'+i+'PROP_TOTAL').value);
				totWO 		= parseFloat(totWO) + parseFloat(PROP_TOTAL);
			}
		}
		document.getElementById('PROP_VALUE').value 	= totWO;
		document.getElementById('PROP_VALUEX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(totWO)), 2));
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