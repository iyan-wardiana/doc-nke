<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 30 Januari 2018
	* File Name	= gej_entry_form.php
	* Location		= -
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody 	= $this->session->userdata['appBody'];
$comp_color	= $this->session->userdata['comp_color'];

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
	$PRJNAME = $row->PRJNAME;
endforeach;
if($PRJCODE == $PRJCODE_HO)
	$PRJNAMEHO	= "";
else
	$PRJNAMEHO	= "$PRJNAME : ";

$currentRow = 0;
if($task == 'add')
{
	$PRJPERIOD	= $PRJCODE;
	$sql 		= "SELECT PRJCODE, PRJPERIOD FROM tbl_project_budg WHERE PRJCODE_HO = '$PRJCODE_HO' AND PRJSTAT = 1 AND BUDG_LEVEL = 2";
	$result 	= $this->db->query($sql)->result();
	foreach($result as $row) :
		$PRJCODE 	= $row->PRJCODE;
		$PRJPERIOD 	= $row->PRJPERIOD;
	endforeach;

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
	$LangID 	= $this->session->userdata['LangID'];
	if(isset($Pattern_Position))
	{
		$isSetDocNo = 1;
		if($Pattern_Position == 'Especially')
		{
			$Pattern_YearAktive 	= date('Y');
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
		
		$Pattern_YearAktive 	= date('Y');
		$Pattern_MonthAktive 	= date('m');
		$Pattern_DateAktive 	= date('d');
		$year 					= (int)$Pattern_YearAktive;
		$month 					= (int)$Pattern_MonthAktive;
		$date 					= (int)$Pattern_DateAktive;
		
		if($LangID == 'IND')
		{
			$docalert1	= 'Peringatan';
			$docalert2	= 'Anda belum men-setting penomoran untuk dokumen ini. Sehingga, akan diberikan penomoran secara default dari sistem. Silahkan atur dari menu pengaturan. Silahkan atur  penomoran dokumen pada menu pengaturan.';
		}
		else
		{
			$docalert1	= 'Warning';
			$docalert2	= 'You have not set the numbering for this document. So, numbering will be given by default from the system. Please set document numbering in the Setting menu.';
		}
	}
	
	$yearC = (int)$Pattern_YearAktive;
	$year = substr($Pattern_YearAktive,2,2);
	$month = (int)$Pattern_MonthAktive;
	$date = (int)$Pattern_DateAktive;

	$this->db->where('Patt_Year', $year);
	//$this->db->where('Patt_Month', $month);
	//$this->db->where('Patt_Date', $date);
	$myCount = $this->db->count_all('tbl_pr_header');
	
	$sql 	= "tbl_journalheader WHERE JournalType = 'GEJ' AND YEAR(JournalH_Date) = $yearC";
	$result = $this->db->count_all($sql);
	$myMax 	= $result+1;
	
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
	//echo "lastPatternNumb = $lastPatternNumb<br>";
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
	
	$theTimeCode	= date('YmdHis');
	$DocNumber 		= "$Pattern_Code$PRJCODE$theTimeCode";
	$Manual_No		= "$Pattern_Code-$PRJCODE-$lastPatternNumb";
	//echo "Manual_No = $Manual_No";
	$JournalH_Code	= "$DocNumber";
	$JournalH_Date	= date('d/m/Y');
	$JournalH_Desc	= $SOURCEDOC;
	$JournalH_Desc2	= '';
	$REF_NUM		= '';
	if($JournalH_Desc != '')
	{
		$sql1 = "SELECT FPA_CODE FROM tbl_fpa_header
					WHERE FPA_NUM = '$SOURCEDOC'";
		$res1 = $this->db->query($sql1)->result();
		foreach($res1 as $row1) :
			$JournalH_Desc = $row1->FPA_CODE;
		endforeach;
		$REF_NUM	= $SOURCEDOC;
	}
	$PRJCODE		= $PRJCODE;
	$PRJCODE_HO		= $PRJCODE_HO;
	$PRJPERIOD		= $PRJPERIOD;
	$GEJ_STAT		= 1;
	$Journal_Amount	= 0;
	$Pattern_Type	= 'GEJ';
	$SPLCODE		= '';
}
else
{
	$isSetDocNo = 1;
	$JournalH_Code		= $default['JournalH_Code'];
	$DocNumber			= $default['JournalH_Code'];
	$Manual_No			= $default['Manual_No'];
	$JournalH_Date		= $default['JournalH_Date'];
	$JournalH_Date		= date('d/m/Y',strtotime($JournalH_Date));
	$JournalH_Desc		= $default['JournalH_Desc'];
	$JournalH_Desc2		= $default['JournalH_Desc2'];
	$PRJCODE			= $default['proj_Code'];
	$PRJCODE_HO			= $default['proj_CodeHO'];
	$PRJPERIOD			= $default['PRJPERIOD'];
	$PRJCODE			= $PRJCODE;
	$GEJ_STAT			= $default['GEJ_STAT'];
	$Journal_Amount		= $default['Journal_Amount'];
	$REF_NUM			= $default['REF_NUM'];
	$Pattern_Type		= $default['Pattern_Type'];
	$SPLCODE			= $default['SPLCODE'];
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
            $vers 	= $this->session->userdata['vers'];

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
		$ISDELETE 	= $this->session->userdata['ISDELETE'];
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
			if($TranslCode == 'RequestNo')$RequestNo = $LangTransl;
			if($TranslCode == 'Account')$Account = $LangTransl;
			if($TranslCode == 'PR_CODE')$PR_CODE = $LangTransl;
			if($TranslCode == 'Notes')$Notes = $LangTransl;
			if($TranslCode == 'Date')$Date = $LangTransl;
			if($TranslCode == 'Budget')$Budget = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'SelectAccount')$SelectAccount = $LangTransl;
			if($TranslCode == 'New')$New = $LangTransl;
			if($TranslCode == 'Confirm')$Confirm = $LangTransl;
			if($TranslCode == 'Close')$Close = $LangTransl;		
			if($TranslCode == 'AccountNo')$AccountNo = $LangTransl;
			if($TranslCode == 'AccountPosition')$AccountPosition = $LangTransl;
			if($TranslCode == 'RefNumber')$RefNumber = $LangTransl;
			if($TranslCode == 'Amount')$Amount = $LangTransl;
			if($TranslCode == 'Remarks')$Remarks = $LangTransl;
			if($TranslCode == 'GeneralJournal')$GeneralJournal = $LangTransl;
			if($TranslCode == 'JournalCode')$JournalCode = $LangTransl;
			if($TranslCode == 'JournalType')$JournalType = $LangTransl;
			if($TranslCode == 'Date')$Date = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'Reason')$Reason = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'Tax')$Tax = $LangTransl;
			if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
			if($TranslCode == 'ManualCode')$ManualCode = $LangTransl;
			if($TranslCode == 'Type')$Type = $LangTransl;
			if($TranslCode == 'Loan')$Loan = $LangTransl;
			if($TranslCode == 'GeneralJournal')$GeneralJournal = $LangTransl;
			if($TranslCode == 'SupplierName')$SupplierName = $LangTransl;
			if($TranslCode == 'reviseNotes')$reviseNotes = $LangTransl;
			if($TranslCode == 'Approval')$Approval = $LangTransl;
			if($TranslCode == 'Approved')$Approved = $LangTransl;
			if($TranslCode == 'NotYetApproved')$NotYetApproved = $LangTransl;
			if($TranslCode == 'rejected')$rejected = $LangTransl;
			if($TranslCode == 'InOthSett')$InOthSett = $LangTransl;
			if($TranslCode == 'inpJTrans')$inpJTrans = $LangTransl;
		endforeach;
		
		if($LangID == 'IND')
		{
			$subTitleH	= "Tambah";
			$subTitleD	= "permintaan pembelian";
			$alert0		= "Masukan deskripsi jurnal transaksi.";
			$alert1		= "Silahkan pilih Nomor Akun.";
			$alert2		= "Silahkan tuliskan deskripsi jurnal.";
			$alert3		= "Tentukan posisi akun; Debit atau Kredit.";
			$alert4		= "Tentukan posisi jenis transaksi, pajak atau bukan.";
			$alert5		= "Masukan nomor referensi jurnal transaksi.";
			$alert6		= "Nilai transaksi tidak boleh 0.";
			$alert7		= "Belum ada akun untuk sisi Debit.";
			$alert8		= "Belum ada akun untuk sisi Kredit.";
			$alert9		= "Nilai Debit dan Kredit tidak sama.";
			$alert10	= "Masukan alasan mengapa dokumen ini di-revise / reject.";
			$alert11	= "Silahkan pilih nama supplier.";
			$alertCls1	= "Anda yakin?";
			$alertCls2	= "Sistem akan mengosongkan data inputan Anda.";
			$alertCls3	= "Data Anda aman.";
		}
		else
		{
			$subTitleH	= "Add";
			$subTitleD	= "purchase request";
			$alert0		= "Please input description of journal transaction.";
			$alert1		= "Please select account number.";
			$alert2		= "Please write of journal description";
			$alert3		= "Set the Account Position; Debit or Credit.";
			$alert4		= "Set the type transaction, Tax or Non Tax.";
			$alert5		= "Insert Reference Number of transaction";
			$alert6		= "The amount cen not be zero.";
			$alert7		= "Debit Side transaction can not be empty.";
			$alert8		= "Credit Side transaction can not be empty";
			$alert9		= "Debit dan Credit Amount must be same.";
			$alert10	= "Input the reason why you revise/reject this document.";
			$alert11	= "Please select a supplier.";
			$alertCls1	= "Are you sure?";
			$alertCls2	= "The system will empty the data you entered.";
			$alertCls3	= "Your data is safe.";
		}
		
		// START : APPROVE PROCEDURE
			if($APPLEV == 'HO')
				$PRJCODE_LEV	= $this->data['PRJCODE_HO'];
			else
				$PRJCODE_LEV	= $this->data['PRJCODE'];
			
			// DocNumber - Journal_Amount
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
				$APPROVE_AMOUNT = $Journal_Amount;
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
		
		$url_AddItem	= site_url('c_gl/cgeje0b28t18/puSA0b28t18/?id=');
	?>
    
    <body class="<?php echo $appBody; ?>">
        <div style="background-color: <?=$comp_color?>">
			<section class="content-header">
				<h1>
				    <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/journal.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $mnName; ?>
				    <small><?php echo $PRJNAME; ?></small>  </h1>
				  <?php /*?><ol class="breadcrumb">
				    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
				    <li><a href="#">Tables</a></li>
				    <li class="active">Data tables</li>
				  </ol><?php */?>
			</section>
			<!-- Main content -->
			<section class="content">
			    <div class="box box-primary">
			    	<div class="box-body chart-responsive">
				        <form class="form-horizontal" name="frm" method="post" action="<?php echo $form_action; ?>" enctype="multipart/form-data" onSubmit="return validateInData()">
				            <input type="hidden" name="IS_LAST" id="IS_LAST" value="<?php echo $IS_LAST; ?>">
				            <input type="hidden" name="APP_LEVEL" id="APP_LEVEL" value="<?php echo $APP_LEVEL; ?>">
				            <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
				            <input type="Hidden" name="rowCount" id="rowCount" value="0">
							<?php if($isSetDocNo == 0) { ?>
			                <div class="form-group">
			                    <label for="inputName" class="col-sm-2 control-label">&nbsp;</label>
			                    <div class="col-sm-10">
			                        <div class="alert alert-danger alert-dismissible">
			                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			                            <h4><i class="icon fa fa-ban"></i> <?php echo $docalert1; ?>!</h4>
			                            <?php echo $docalert2; ?>
			                        </div>
			                    </div>
			                </div>
			                <?php } ?>
			            	<div class="form-group" style="display: none;">
			                    <label for="inputName" class="col-sm-2 control-label"><?php echo $JournalCode; ?></label>
			                    <div class="col-sm-10">
			                    	<input type="text" class="form-control" style="text-align:left" name="JournalH_Code1" id="JournalH_Code1" size="30" value="<?php echo $DocNumber; ?>" disabled />
			                    	<input type="hidden" class="form-control" style="max-width:350px;text-align:right" name="JournalH_Code" id="JournalH_Code" size="30" value="<?php echo $DocNumber; ?>" />
			                        <input type="hidden" class="form-control" style="max-width:350px;text-align:right" name="PRJCODE" id="PRJCODE" size="30" value="<?php echo $PRJCODE; ?>" />
			                        <input type="hidden" class="form-control" style="max-width:350px;text-align:right" name="proj_CodeHO" id="proj_CodeHO" size="30" value="<?php echo $PRJCODE_HO; ?>" />
			                        <input type="hidden" class="form-control" style="max-width:350px;text-align:right" name="PRJPERIOD" id="PRJPERIOD" size="30" value="<?php echo $PRJPERIOD; ?>" />
			                    </div>
			                </div>
			            	<div class="form-group">
			                    <label for="inputName" class="col-sm-2 control-label"><?php echo $ManualCode; ?></label>
			                    <div class="col-sm-10">
			                    	<input type="text" class="form-control" name="Manual_No1" id="JournalH_Code1" size="30" value="<?php echo $Manual_No; ?>" readonly />
			                    	<input type="hidden" class="form-control" name="Manual_No" id="JournalH_Code" size="30" value="<?php echo $Manual_No; ?>" />
			                    </div>
			                </div>
			            	<div class="form-group" style="display:none">
			                    <label for="inputName" class="col-sm-2 control-label">Referen.</label>
			                    <div class="col-sm-10">
			                    	<input type="text" class="form-control" style="max-width:180px;" name="REF_NUM" id="REF_NUM" size="30" value="<?php echo $REF_NUM; ?>" />
			                    </div>
			                </div>
			            	<div class="form-group">
			                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Date; ?></label>
			                    <div class="col-sm-10">
			                    	<div class="input-group date">
			                        <div class="input-group-addon">
			                        <i class="fa fa-calendar"></i>&nbsp;</div><input type="text" name="JournalH_Date" class="form-control pull-left" id="datepicker" value="<?php echo $JournalH_Date; ?>" style="width:106px"></div>
			                    </div>
			                    
			                </div>
			            	<div class="form-group">
			                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Budget; ?></label>
			                    <div class="col-sm-10">
			                    	<select name="PRJCODEX" id="PRJCODEX" class="form-control" disabled>
			                          <option value="none">---</option>
			                          <?php
			                            if($countPRJ > 0)
			                            {
			                                foreach($vwPRJ as $row) :
			                                    $PRJCODE1 	= $row->PRJCODE;
			                                    $PRJNAME1 	= $row->PRJNAME;
			                                    ?>
			                                  <option value="<?php echo $PRJCODE1; ?>" <?php if($PRJCODE1 == $PRJCODE) { ?> selected <?php } ?>><?php echo "$PRJNAMEHO$PRJNAME1"; ?></option>
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
			                    <input type="hidden" class="form-control" style="max-width:350px;text-align:right" id="proj_Code" name="proj_Code" size="20" value="<?php echo $PRJCODE; ?>" />                        
			                    </div>
			                </div>
			            	<div class="form-group" style="display: none;">
			                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Type; ?></label>
			                    <div class="col-sm-10">
			                    	<select name="Pattern_Type" id="Pattern_Type" class="form-control select2" style="max-width:150px" onChange="chkSPL(this.value)">
			                          <option value="GEJ" <?php if($Pattern_Type == 'GEJ') { ?> selected <?php } ?>><?php echo $GeneralJournal; ?></option>
			                          <option value="LOAN" <?php if($Pattern_Type == 'LOAN') { ?> selected <?php } ?>><?php echo $Loan; ?></option>
			                        </select>
			                    <input type="hidden" class="form-control" style="max-width:350px;text-align:right" id="proj_Code" name="proj_Code" size="20" value="<?php echo $PRJCODE; ?>" />                        
			                    </div>
			                </div>
			                <script>
								function chkSPL(GEJTYPE)
								{
									if(GEJTYPE == 'GEJ')
									{
										document.getElementById('splID').style.display = 'none';
									}
									else
									{
										document.getElementById('splID').style.display = '';
									}
								}
							</script>
			                <?php
								$sqlSPL	= "SELECT SPLCODE, SPLDESC FROM tbl_supplier WHERE SPLSTAT = '1'";
								$resSPL	= $this->db->query($sqlSPL)->result();
								foreach($resSPL as $rowSPL) :
									$SPLCODE1	= $rowSPL->SPLCODE;
									$SPLDESC1	= $rowSPL->SPLDESC;
								endforeach;
							?>
			            	<div class="form-group" id="splID" <?php if($Pattern_Type == 'GEJ') { ?> style="display:none" <?php } ?>>
			                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Budget; ?></label>
			                    <div class="col-sm-10">
			                        <select name="SPLCODE" id="SPLCODE" class="form-control select2" data-placeholder="&nbsp;&nbsp;&nbsp;<?php echo $SupplierName; ?>" style="width:350px;">
			                        	<option value=""> --- </option>
										<?php
			                            $i = 0;
										$sqlSPLC	= "tbl_supplier WHERE SPLSTAT = '1'";
										$resSPLC	= $this->db->count_all($sqlSPLC);
			                            if($resSPLC > 0)
			                            {
											$sqlSPL	= "SELECT SPLCODE, SPLDESC FROM tbl_supplier WHERE SPLSTAT = '1'";
											$resSPL	= $this->db->query($sqlSPL)->result();
			                                foreach($resSPL as $rowSPL) :
			                                    $SPLCODE1	= $rowSPL->SPLCODE;
			                                    $SPLDESC1	= $rowSPL->SPLDESC;
			                                    ?>
			                                        <option value="<?php echo $SPLCODE1; ?>" <?php if($SPLCODE1 == $SPLCODE) { ?> selected <?php } ?>><?php echo "$SPLDESC1 - $SPLCODE1"; ?></option>
			                                    <?php
			                                endforeach;
			                            }
			                            else
			                            {
			                                ?>
			                                    <option value="">--- No Vendor Found ---</option>
			                                <?php
			                            }
			                            ?>
			                        </select>                      
			                    </div>
			                </div>
			            	<div class="form-group">
			                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Description; ?></label>
			                    <div class="col-sm-10">
			                    	<textarea name="JournalH_Desc" class="form-control" id="JournalH_Desc" cols="30"><?php echo $JournalH_Desc; ?></textarea>    
			                    	<input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="3">
			                        <input type="hidden" name="Journal_Amount" id="Journal_Amount" value="">
			                        <input type="hidden" name="Journal_AmountD" id="Journal_AmountD" value="">
			                        <input type="hidden" name="Journal_AmountK" id="Journal_AmountK" value="">
			                    </div>
			                </div>
			            	<div class="form-group" <?php if($JournalH_Desc2 == '' ) { ?> style="display:none" <?php } ?> id="tblReason">
			                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Reason; ?></label>
			                    <div class="col-sm-10">
			                    	<textarea name="JournalH_Desc2" class="form-control" id="JournalH_Desc2" cols="30"><?php echo $JournalH_Desc2; ?></textarea>  
			                    </div>
			                </div>
			                <?php
								//echo "canApprove = $canApprove";
							?>
			            	<div class="form-group">
			                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Status; ?></label>
			                    <div class="col-sm-10">
			                    	<input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $GEJ_STAT; ?>">
									<?php
										// START : FOR ALL APPROVAL FUNCTION
											$disButton	= 0;
											$disButtonE	= 0;
											if($task == 'add')
											{
												if($ISCREATE == 1 || $ISAPPROVE == 1)
												{
													?>
														<select name="GEJ_STAT" id="GEJ_STAT" class="form-control select2" onChange="selStat(this.value)" >
															<option value="1">New</option>
															<option value="2">Confirm</option>
														</select>
													<?php
												}
											}
											else
											{
												$disButton	= 1;
												if(($ISCREATE == 1 && $ISAPPROVE == 1))
												{
													if($GEJ_STAT == 1 || $GEJ_STAT == 4)
													{
														$disButton	= 0;
														?>
															<select name="GEJ_STAT" id="GEJ_STAT" class="form-control select2" onChange="selStat(this.value)" >
																<option value="1">New</option>
																<option value="2">Confirm</option>
															</select>
														<?php
													}
													elseif($GEJ_STAT == 2 || $GEJ_STAT == 7)
													{
														$disButton	= 0;
														if($canApprove == 0)
															$disButton	= 1;
														
														$sqlCAPPHE	= "tbl_approve_hist WHERE AH_CODE = '$JournalH_Code' AND AH_APPROVER = '$DefEmp_ID'";
														$resCAPPHE	= $this->db->count_all($sqlCAPPHE);
														if($resCAPPHE > 0)
															$disButton	= 1;
													
														?>
															<select name="GEJ_STAT" id="GEJ_STAT" class="form-control select2" onChange="selStat(this.value)" <?php if($disButton == 1) { ?> disabled <?php } ?>>
																<option value="1"<?php if($GEJ_STAT == 1) { ?> selected <?php } ?> disabled >New</option>
																<option value="2"<?php if($GEJ_STAT == 2) { ?> selected <?php } ?> >Confirm</option>
																<option value="3"<?php if($GEJ_STAT == 3) { ?> selected <?php } ?> >Approved</option>
																<option value="4"<?php if($GEJ_STAT == 4) { ?> selected <?php } ?> >Revising</option>
																<option value="5"<?php if($GEJ_STAT == 5) { ?> selected <?php } ?> >Rejected</option>
																<option value="6"<?php if($GEJ_STAT == 6) { ?> selected <?php } ?> disabled>Closed</option>
																<option value="7"<?php if($GEJ_STAT == 7) { ?> selected <?php } ?> disabled>Waiting</option>
																<?php if($GEJ_STAT == 3 || $GEJ_STAT == 9) { ?>
																<option value="9"<?php if($GEJ_STAT == 9) { ?> selected <?php } ?>>Void</option>
																<?php } ?>
															</select>
														<?php
													}
													elseif($GEJ_STAT == 3)
													{
														$disButton	= 0;
														if($canApprove == 0)
															$disButton	= 1;

														$sqlCAPPHE	= "tbl_approve_hist WHERE AH_CODE = '$JournalH_Code' AND AH_APPROVER = '$DefEmp_ID'";
														$resCAPPHE	= $this->db->count_all($sqlCAPPHE);
														if($resCAPPHE > 0)
															$disButton	= 1;
														if($ISDELETE == 1 || $ISAPPROVE == 1)
															$disButton	= 0;

														?>
															<select name="GEJ_STAT" id="GEJ_STAT" class="form-control select2" onChange="selStat(this.value)" <?php if($disButton == 1){ ?> disabled <?php } ?>>
																<option value="1"<?php if($GEJ_STAT == 1) { ?> selected <?php } ?> disabled>New</option>
																<option value="2"<?php if($GEJ_STAT == 2) { ?> selected <?php } ?> disabled>Confirm</option>
																<option value="3"<?php if($GEJ_STAT == 3) { ?> selected <?php } ?> >Approved</option>
																<option value="4"<?php if($GEJ_STAT == 4) { ?> selected <?php } ?> disabled>Revising</option>
																<option value="5"<?php if($GEJ_STAT == 5) { ?> selected <?php } ?> disabled>Rejected</option>
																<option value="6"<?php if($GEJ_STAT == 6) { ?> selected <?php } ?> disabled>Closed</option>
																<option value="7"<?php if($GEJ_STAT == 7) { ?> selected <?php } ?> disabled >Waiting</option>
																<option value="9"<?php if($GEJ_STAT == 9) { ?> selected <?php } ?>>Void</option>
															</select>
														<?php
													}
												}
												elseif($ISAPPROVE == 1)
												{
													if($GEJ_STAT == 1 || $GEJ_STAT == 4)
													{
														$disButton	= 1;
														?>
															<select name="GEJ_STAT" id="GEJ_STAT" class="form-control select2" onChange="selStat(this.value)" <?php if($disButton == 1) { ?> disabled <?php } ?>>
																<option value="1">New</option>
																<option value="2">Confirm</option>
															</select>
														<?php
													}
													else if($GEJ_STAT == 2 || $GEJ_STAT == 3 || $GEJ_STAT == 7)
													{
														$disButton	= 0;
														if($canApprove == 0)
															$disButton	= 1;
															
														$sqlCAPPHE	= "tbl_approve_hist WHERE AH_CODE = '$JournalH_Code' AND AH_APPROVER = '$DefEmp_ID'";
														$resCAPPHE	= $this->db->count_all($sqlCAPPHE);
														if($resCAPPHE > 0)
															$disButton	= 1;
													
														?>
															<select name="GEJ_STAT" id="GEJ_STAT" class="form-control select2" onChange="selStat(this.value)" <?php if($disButton == 1) { ?> disabled <?php } ?>>
																<option value="1"<?php if($GEJ_STAT == 1) { ?> selected <?php } ?> disabled >New</option>
																<option value="2"<?php if($GEJ_STAT == 2) { ?> selected <?php } ?> >Confirm</option>
																<option value="3"<?php if($GEJ_STAT == 3) { ?> selected <?php } ?> >Approved</option>
																<option value="4"<?php if($GEJ_STAT == 4) { ?> selected <?php } ?> >Revising</option>
																<option value="5"<?php if($GEJ_STAT == 5) { ?> selected <?php } ?> >Rejected</option>
																<option value="6"<?php if($GEJ_STAT == 6) { ?> selected <?php } ?> disabled>Closed</option>
																<option value="7"<?php if($GEJ_STAT == 7) { ?> selected <?php } ?> disabled>Waiting</option>
																<?php if($GEJ_STAT == 3 || $GEJ_STAT == 9) { ?>
																<option value="9"<?php if($GEJ_STAT == 9) { ?> selected <?php } ?> >Void</option>
																<?php } ?>
															</select>
														<?php
													}
												}
												elseif($ISCREATE == 1)
												{
													if($GEJ_STAT == 1 || $GEJ_STAT == 4)
													{
														$disButton	= 0;
														?>
															<select name="GEJ_STAT" id="GEJ_STAT" class="form-control select2" onChange="selStat(this.value)" <?php if($disButton == 1) { ?> disabled <?php } ?>>
																<option value="1"<?php if($GEJ_STAT == 1) { ?> selected <?php } ?> >New</option>
																<option value="2"<?php if($GEJ_STAT == 2) { ?> selected <?php } ?> >Confirm</option>
																<option value="3"<?php if($GEJ_STAT == 3) { ?> selected <?php } ?> disabled >Approved</option>
																<option value="4"<?php if($GEJ_STAT == 4) { ?> selected <?php } ?> disabled >Revising</option>
																<option value="5"<?php if($GEJ_STAT == 5) { ?> selected <?php } ?> disabled >Rejected</option>
																<option value="6"<?php if($GEJ_STAT == 6) { ?> selected <?php } ?> disabled>Closed</option>
																<option value="7"<?php if($GEJ_STAT == 7) { ?> selected <?php } ?> disabled>Waiting</option>
																<?php if($GEJ_STAT == 3 || $GEJ_STAT == 9) { ?>
																<option value="9"<?php if($GEJ_STAT == 9) { ?> selected <?php } ?> disabled>Void</option>
																<?php } ?>
															</select>
														<?php
													}
													else
													{
														$disButton	= 1;
														?>
															<select name="GEJ_STAT" id="GEJ_STAT" class="form-control select2"  onChange="selStat(this.value)" <?php if($disButton == 1) { ?> disabled <?php } ?>>
																<option value="1"<?php if($GEJ_STAT == 1) { ?> selected <?php } ?> disabled >New</option>
																<option value="2"<?php if($GEJ_STAT == 2) { ?> selected <?php } ?> disabled >Confirm</option>
																<option value="3"<?php if($GEJ_STAT == 3) { ?> selected <?php } ?> disabled >Approved</option>
																<option value="4"<?php if($GEJ_STAT == 4) { ?> selected <?php } ?> disabled >Revising</option>
																<option value="5"<?php if($GEJ_STAT == 5) { ?> selected <?php } ?> disabled >Rejected</option>
																<option value="6"<?php if($GEJ_STAT == 6) { ?> selected <?php } ?> disabled>Closed</option>
																<option value="7"<?php if($GEJ_STAT == 7) { ?> selected <?php } ?> disabled>Waiting</option>
																<?php if($GEJ_STAT == 3 || $GEJ_STAT == 9) { ?>
																<option value="9"<?php if($GEJ_STAT == 9) { ?> selected <?php } ?> disabled>Void</option>
																<?php } ?>
															</select>
														<?php
													}
												}
											}
										// END : FOR ALL APPROVAL FUNCTION
			                        ?>
			                    </div>
			                </div>
			                <script>
								function selStat(thisValue)
								{
									if(thisValue == 4 || thisValue == 5 || thisValue == 9)
									{
										//document.getElementById('tblUpdate').style.display = '';
										document.getElementById('tblReason').style.display = '';
									}
									else
									{
										//document.getElementById('tblUpdate').style.display = 'none';
										document.getElementById('tblReason').style.display = 'none';
									}
								}
							</script>
							<?php
			                   if($ISCREATE == 1 && $GEJ_STAT == 1)
			                    {
			                        ?>
			                        <div class="form-group">
			                            <label for="inputName" class="col-sm-2 control-label">&nbsp;</label>
			                            <div class="col-sm-10">
			                                <button class="btn btn-success" type="button" onClick="add_listAcc();">
			                                <?php echo $Account; ?>&nbsp;&nbsp;[ <i class="glyphicon glyphicon-plus"></i> ]
			                                </button>
			                            </div>
			                        </div>
									<?php
			                    }
			                ?>
							<script>
			                    var url = "<?php echo $url_AddItem;?>";
			                    function selectAccount(theRow)
			                    {
			                        PRJCODE	= '<?php echo $PRJCODE;?>';
									
			                        title = 'Select Item';
			                        w = 800;
			                        h = 550;
			                        //window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
			                        var left = (screen.width/2)-(w/2);
			                        var top = (screen.height/2)-(h/2);
			                        return window.open(url+PRJCODE+'&theRow='+theRow, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
			                    }
			                </script>
			                <div class="row">
			                    <div class="col-md-12">
			                        <div class="box box-primary">
			                        	<div class="search-table-outter">
					                       	<table id="tbl" class="table table-bordered table-striped" width="100%">
					                        <!-- <table width="100%" border="1" id="tbl"> -->
					                        	<tr style="background:#CCCCCC">
					                              	<th width="2%" height="25" style="text-align:left">&nbsp;</th>
					                              	<th width="10%" style="text-align:center"><?php echo $AccountNo; ?> </th>
					                              	<th width="30%" style="text-align:center"><?php echo $Description; ?> </th>
					                              	<th width="5%" style="text-align:center" nowrap><?php echo $AccountPosition; ?> </th>
					                              	<th style="text-align:center; display:none" nowrap><?php echo $Tax; ?></th>
					                              	<th width="10%" style="text-align:center"><?php echo $RefNumber; ?> </th>
					                              	<th width="10%" style="text-align:center"><?php echo $Amount; ?></th>
					                              	<th width="15%" style="text-align:center"><?php echo $Remarks; ?></th>
					                          	</tr>
					                            <?php
					                            $editable	= 0;
					                            if($GEJ_STAT == 1 || $GEJ_STAT == 4)
                                                {
                                                	$editable	= 1;
                                                }
					                            if($task == 'edit')
					                            {
					                                $sqlDET	= "SELECT A.JournalH_Code, A.Acc_Id, A.proj_Code, A.JOBCODEID, A.JournalD_Debet, 
																	A.JournalD_Debet_tax, A.JournalD_Kredit, A.JournalD_Kredit_tax, A.isDirect, 
																	A.Notes, A.ITM_CODE, A.Ref_Number, A.Other_Desc, A.Journal_DK, A.isTax,
																	A.ITM_CATEG
																FROM tbl_journaldetail A
																WHERE JournalH_Code = '$JournalH_Code' 
																	AND A.proj_Code = '$PRJCODE'";
					                                $result = $this->db->query($sqlDET)->result();
					                                $i		= 0;
					                                $j		= 0;
													
													foreach($result as $row) :
														$currentRow  		= ++$i;
														$JournalH_Code 		= $row->JournalH_Code;
														$Acc_Id 			= $row->Acc_Id;
														$JOBCODEID 			= $row->JOBCODEID;
														$JournalD_Debet 	= $row->JournalD_Debet;
														$JournalD_Debet_tax = $row->JournalD_Debet_tax;
														$JournalD_Kredit 	= $row->JournalD_Kredit;
														$JournalD_Kredit_tax= $row->JournalD_Kredit_tax;
														$isDirect 			= $row->isDirect;
														$Notes 				= $row->Notes;
														$ITM_CODE 			= $row->ITM_CODE;
														$ITM_CATEG 			= $row->ITM_CATEG;
														$Ref_Number 		= $row->Ref_Number;
														$Other_Desc 		= $row->Other_Desc;
														$Journal_DK 		= $row->Journal_DK;
														$isTax 				= $row->isTax;
														
														if($Journal_DK == 'D')
														{
															$AmountV		= $JournalD_Debet;
														}
														else
														{
															$AmountV		= $JournalD_Kredit;
														}
															
														if($isTax == 1)
														{
															if($Journal_DK == 'D')
															{
																$AmountV		= $JournalD_Debet_tax;
															}
															else
															{
																$AmountV		= $JournalD_Kredit_tax;
															}
															$isTaxD			= 'Tax';
														}
														else
														{
															$isTaxD			= 'No';
														}
															
														$ITM_NAME			= '';
														if($ITM_CODE != '')
														{
															$sqlITM		= "SELECT ITM_NAME FROM tbl_item WHERE ITM_CODE = '$ITM_CODE'";
															$resITM 	= $this->db->query($sqlITM)->result();
															foreach($resITM as $rowITM) :
																$ITM_NAME 	= $rowITM->ITM_NAME;
															endforeach;
														}
														else
														{
															$sqlITM		= "SELECT Account_NameId FROM tbl_chartaccount 
																			WHERE Account_Number = '$Acc_Id'";
															$resITM 	= $this->db->query($sqlITM)->result();
															foreach($resITM as $rowITM) :
																$ITM_NAME 	= $rowITM->Account_NameId;
															endforeach;
														}
														
														/*if ($j==1) {
															echo "<tr class=zebra1>";
															$j++;
														} else {
															echo "<tr class=zebra2>";
															$j--;
														}*/
														?> 
					                                    <tr id="tr_<?php echo $currentRow; ?>">
					                                        <td height="25" style="text-align:center;" nowrap>
					                                            <?php
					                                                if($GEJ_STAT == 1 || $GEJ_STAT == 4)
					                                                {

					                                                    ?>
					                                                    <a href="#" onClick="deleteRow(<?php echo $currentRow; ?>)" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a>
					                                                    <?php
					                                                }
					                                                else
					                                                {
					                                                    echo "$currentRow.";
					                                                }
					                                            ?>
					                                        </td>
					                                        <td style="text-align:left" nowrap>
					                                        	<?php if($editable == 1) { ?>
					                                            	<input type="text" name="data[<?php echo $currentRow; ?>][Acc_Id]" id="data<?php echo $currentRow; ?>Acc_Id" value="<?php echo $Acc_Id; ?>" class="form-control" style="max-width:150px;" onClick="selectAccount(<?php echo $currentRow; ?>);" placeholder="<?php echo $SelectAccount; ?>" >
					                                        	<?php } else { ?>
					                                        		<?php echo $Acc_Id; ?>
					                                            	<input type="hidden" name="data[<?php echo $currentRow; ?>][Acc_Id]" id="data<?php echo $currentRow; ?>Acc_Id" value="<?php echo $Acc_Id; ?>" class="form-control" style="max-width:150px;" onClick="selectAccount(<?php echo $currentRow; ?>);" placeholder="<?php echo $SelectAccount; ?>" >
					                                        	<?php } ?>

					                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][JournalH_Code]" id="data<?php echo $currentRow; ?>JournalH_Code" value="<?php echo $JournalH_Code; ?>" class="form-control" style="max-width:150px;" >
					                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][proj_Code]" id="data<?php echo $currentRow; ?>proj_Code" value="<?php echo $PRJCODE; ?>" class="form-control" style="max-width:150px;" >
					                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][proj_CodeHO]" id="data<?php echo $currentRow; ?>proj_CodeHO" value="<?php echo $PRJCODE_HO; ?>" class="form-control" style="max-width:150px;" ><input type="hidden" name="data[<?php echo $currentRow; ?>][PRJPERIOD]" id="data<?php echo $currentRow; ?>PRJPERIOD" value="<?php echo $PRJPERIOD; ?>" class="form-control" style="max-width:150px;" >
					                                        </td>
					                                        <td style="text-align:left" nowrap>
					                                        	<?php if($editable == 1) { ?>
					                                            	<input type="text" name="data[<?php echo $currentRow; ?>][JournalD_Desc]" id="data<?php echo $currentRow; ?>JournalD_Desc" value="<?php echo $ITM_NAME; ?>" class="form-control" style="max-width:500px;" placeholder="<?php echo $Description; ?>">
					                                        	<?php } else { ?>
					                                        		<?php echo $ITM_NAME; ?>
					                                            	<input type="hidden" name="data[<?php echo $currentRow; ?>][JournalD_Desc]" id="data<?php echo $currentRow; ?>JournalD_Desc" value="<?php echo $ITM_NAME; ?>" class="form-control" style="max-width:500px;" placeholder="<?php echo $Description; ?>">
					                                        	<?php } ?>

					                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][ITM_CODE]" id="data<?php echo $currentRow; ?>ITM_CODE" value="<?php echo $ITM_CODE; ?>" class="form-control" style="max-width:500px;" placeholder="<?php echo $ItemCode; ?>">
					                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][ITM_CATEG]" id="data<?php echo $currentRow; ?>ITM_CATEG" value="<?php echo $ITM_CATEG; ?>" class="form-control" style="max-width:500px;">
					                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][JOBCODEID]" id="data<?php echo $currentRow; ?>JOBCODEID" value="<?php echo $JOBCODEID; ?>" class="form-control" style="max-width:500px;">
					                                        </td>
					                                        <td nowrap style="text-align:center" nowrap>
					                                        	<?php if($editable == 1) { ?>
						                                            <select name="data[<?php echo $currentRow; ?>][JournalD_Pos]" id="data<?php echo $currentRow; ?>JournalD_Pos" class="form-control select2" style="max-width:100px" onChange="countAmount()">
						                                                <option value="" <?php if($Journal_DK == "") { ?> selected <?php } ?>>--</option>
						                                                <option value="D" <?php if($Journal_DK == "D") { ?> selected <?php } ?>>D</option>
						                                                <option value="K" <?php if($Journal_DK == "K") { ?> selected <?php } ?>>K</option>
						                                            </select>
					                                        	<?php } else { ?>
					                                        		<?php echo $Journal_DK; ?>
						                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][JournalD_Pos]" id="data<?php echo $currentRow; ?>JournalD_Pos" value="<?php echo $Journal_DK; ?>" class="form-control">
					                                        	<?php } ?>
					                                        </td>
					                                        <td nowrap style="text-align:center; display:none" nowrap>
					                                            <select name="data[<?php echo $currentRow; ?>][isTax]" id="data<?php echo $currentRow; ?>isTax" class="form-control" style="max-width:100px" onChange="countAmount()">
					                                                <option value="" <?php if($isTax == "") { ?> selected <?php } ?>>--</option>
					                                                <option value="0" selected >N</option>
					                                                <option value="1" <?php if($isTax == '1') { ?> selected <?php } ?>>Y</option>
					                                            </select>
					                                        </td>
					                                        <td style="text-align:left" nowrap>
					                                        	<?php if($editable == 1) { ?>
						                                            <input type="text" name="data[<?php echo $currentRow; ?>][Ref_Number]" id="data<?php echo $currentRow; ?>Ref_Number" value="<?php echo $Ref_Number; ?>" class="form-control" style="max-width:150px;" placeholder="<?php echo $RefNumber; ?>" >
					                                        	<?php } else { ?>
					                                        		<?php echo $Ref_Number; ?>
						                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][Ref_Number]" id="data<?php echo $currentRow; ?>Ref_Number" value="<?php echo $Ref_Number; ?>" class="form-control" style="max-width:150px;" placeholder="<?php echo $RefNumber; ?>" >
					                                        	<?php } ?>
					                                        </td>
					                                        <td style="text-align:right" nowrap>
					                                        	<?php if($editable == 1) { ?>
						                                            <input type="text" class="form-control" style="text-align:right" name="JournalD_Amount<?php echo $currentRow; ?>" id="JournalD_Amount<?php echo $currentRow; ?>" value="<?php echo number_format($AmountV, 2); ?>" placeholder="<?php echo $Amount; ?>" onBlur="chgAmount(this,<?php echo $currentRow; ?>)" onKeyPress="return isIntOnlyNew(event);" title="<?php echo number_format($AmountV, 2); ?>">
					                                        	<?php } else { ?>
					                                        		<?php echo number_format($AmountV, 2); ?>
						                                            <input type="hidden" class="form-control" style="text-align:right" name="JournalD_Amount<?php echo $currentRow; ?>" id="JournalD_Amount<?php echo $currentRow; ?>" value="<?php echo number_format($AmountV, 2); ?>" placeholder="<?php echo $Amount; ?>" onBlur="chgAmount(this,<?php echo $currentRow; ?>)" onKeyPress="return isIntOnlyNew(event);" title="<?php echo number_format($AmountV, 2); ?>">
					                                        	<?php } ?>
					                                            
					                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][JournalD_Amount]" id="data<?php echo $currentRow; ?>JournalD_Amount" value="<?php echo $AmountV; ?>" class="form-control" style="max-width:150px;" >
					                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][isDirect]" id="data<?php echo $currentRow; ?>isDirect" value="1" class="form-control" style="max-width:150px;" >
					                                        </td>
					                                        <td style="text-align:left" nowrap>
					                                        	<?php if($editable == 1) { ?>
						                                            <input type="text" name="data[<?php echo $currentRow; ?>][Other_Desc]" id="data<?php echo $currentRow; ?>Other_Desc" size="20" value="<?php echo $Other_Desc; ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:left" placeholder="<?php echo $Remarks; ?>">
					                                        	<?php } else { ?>
					                                        		<?php echo $Other_Desc; ?>
						                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][Other_Desc]" id="data<?php echo $currentRow; ?>Other_Desc" size="20" value="<?php echo $Other_Desc; ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:left" placeholder="<?php echo $Remarks; ?>">
					                                        	<?php } ?>
					                                            
					                                        </td>
										          		</tr>
					                              	<?php
					                             	endforeach;
												}
												?>
												<input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">
					                        </table>
					                    </div>
			                      	</div>
			                    </div>
			                </div>
			                <br>
			                <?php
								//echo "$ISAPPROVE == 1 && $GEJ_STAT == 2 && $canApprove == 1";
							?>
			                <div class="form-group">
			                    <div class="col-sm-offset-2 col-sm-10">
			                        <?php
										$backURL	= site_url('c_gl/cgeje0b28t18/gej0b28t18/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
										if($task=='add')
										{
											if($GEJ_STAT == 1 && $ISCREATE == 1 && $disButton == 0 && $resCAPP != 0)
											{
												?>
													<button class="btn btn-primary" id="tblUpdate">
													<i class="fa fa-save"></i></button>&nbsp;
												<?php
											}
										}
										else
										{
											if($disButton == 0 && $resCAPP != 0)
											{
												?>
			                                        <button class="btn btn-primary" id="tblUpdate">
			                                        <i class="fa fa-save"></i></button>&nbsp;
												<?php
											}
										}
										
										//echo anchor("$backURL",'<button class="btn btn-danger" id="tblClose" type="button"><i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;'.$Back.'</button>');
			                        ?>
			                        <button class="btn btn-danger" id="tblClose" type="button"><i class="fa fa-reply"></i></button>
			                    </div>
			                </div>
			                <?php
								// APPROVE HIST
								$DOC_NUM	= $JournalH_Code;
			                    $sqlCAPPH	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM'";
			                    $resCAPPH	= $this->db->count_all($sqlCAPPH);
			                ?>
			                <script type="text/javascript">
			                	$('#tblClose').on('click',function(e) 
								{
									var task = "<?php echo $task; ?>";
									var totR = $('#totalrow').val();
									if(totR > 0 && task == "add")
									{
									    swal({
											  title: "<?php echo $alertCls1; ?>",
											  text: "<?php echo $alertCls2; ?>",
											  //icon: "warning",
											  buttons: ["No", "Yes"],
											  //dangerMode: true,
											})
											.then((willDelete) => {
											if (willDelete) 
											{
											    window.location = "<?php echo $backURL; ?>";
											}
										});
									}
									else
									{
										window.location = "<?php echo $backURL; ?>";
									}
								});
			                </script>
							<?php
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
			                                    
			                                    /*if($resCAPPH == 1)
			                                    {
			                                        $Approver	= $Awaiting;
			                                        $boxCol_3	= "yellow";
			                                        $class		= "glyphicon glyphicon-info-sign";
			                                    }*/
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
			                                    
			                                    /*if($resCAPPH == 2)
			                                    {
			                                        $Approver	= $Awaiting;
			                                        $boxCol_4	= "yellow";
			                                        $class		= "glyphicon glyphicon-info-sign";
			                                    }*/
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
			                                    
			                                    /*if($resCAPPH == 3)
			                                    {
			                                        $Approver	= $Awaiting;
			                                        $boxCol_5	= "yellow";
			                                        $class		= "glyphicon glyphicon-info-sign";
			                                    }*/
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
						</form>
			    	</div>
			    </div>
                <?php
                    $DefID      = $this->session->userdata['Emp_ID'];
                    $act_lnk    = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                    if($DefID == 'D15040004221')
                        echo "<font size='1'><i>$act_lnk</i></font>";
                ?>
			</section>
		</div>
	</body>
</html>

<script>
  	$(function ()
  	{
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
	    // LOCK DATE
	    /*$('#datepicker').datepicker({
			autoclose: true,
			startDate: '-6d',
			endDate: '+6d'
	    });*/
		$.fn.datepicker.defaults.format = "dd/mm/yyyy";
	    $('#datepicker').datepicker({
			autoclose: true,
			startDate: '-6d',
			endDate: '+0d'
	    });

	    //Date picker
	    // LOCK DATE
	    /*$('#datepicker2').datepicker({
	      autoclose: true,
		  startDate: '-6d'
	    });*/
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
  	});
  
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
	
	function add_listAcc(strItem) 
	{
		var objTable, objTR, objTD, intIndex;
		
		JournalH_Code		= '';
		Acc_Id				= '';
		ITM_CODE			= '';
		JOBCODEID			= '';
		JournalD_Desc		= '';
		Currency_id			= 'IDR';
		JournalD_Debet		= 0;
		JournalD_Debet_tax	= 0;
		JournalD_Kredit		= 0;
		JournalD_Kredit_tax	= 0;
		Base_Debet 			= 0;
		Base_Debet_tax		= 0;
		Base_Kredit			= 0;
		Base_Kredit_tax		= 0;
		COA_Debet 			= 0;
		COA_Debet_tax		= 0;
		COA_Kredit 			= 0;
		COA_Kredit_tax		= 0;
		Ref_Number			= '';
		JournalD_Pos		= 'D';
		
		objTable 		= document.getElementById('tbl');
		intTable 		= objTable.rows.length;
		//swal('intTable = '+intTable)
		//intIndex = parseInt(document.frm.rowCount.value) + 1;
		intIndex = parseInt(objTable.rows.length);
		//intIndex = intTable;
		document.frm.rowCount.value = intIndex;
		
		objTR = objTable.insertRow(intTable);
		objTR.id = 'tr_' + intIndex;
		
		// Account Icon
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.noWrap = true;
		objTD.innerHTML = '<a href="#" onClick="deleteRow('+intIndex+')" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a><input type="hidden" id="chk" name="chk" value="'+intIndex+'" width="10" size="15" readonly class="form-control" style="max-width:300px;">';
		
		// Account Number
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'right';
		objTD.innerHTML = '<input type="hidden" name="data['+intIndex+'][JournalH_Code]" id="data'+intIndex+'JournalH_Code" value="<?php echo $JournalH_Code; ?>" class="form-control" style="max-width:150px;" ><input type="hidden" name="data['+intIndex+'][proj_Code]" id="data'+intIndex+'proj_Code" value="<?php echo $PRJCODE; ?>" class="form-control" style="max-width:150px;" ><input type="hidden" name="data['+intIndex+'][proj_CodeHO]" id="data'+intIndex+'proj_CodeHO" value="<?php echo $PRJCODE_HO; ?>" class="form-control" style="max-width:150px;" ><input type="hidden" name="data['+intIndex+'][PRJPERIOD]" id="data'+intIndex+'PRJPERIOD" value="<?php echo $PRJPERIOD; ?>" class="form-control" style="max-width:150px;" ><input type="text" name="data['+intIndex+'][Acc_Id]" id="data'+intIndex+'Acc_Id" value="'+Acc_Id+'" class="form-control" onClick="selectAccount('+intIndex+');" placeholder="<?php echo $SelectAccount; ?>" >';
		
		// Account Description
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.noWrap = true;
		objTD.innerHTML = '<input type="hidden" name="data['+intIndex+'][ITM_CODE]" id="data'+intIndex+'ITM_CODE" value="'+ITM_CODE+'" class="form-control" style="max-width:500px;" placeholder="<?php echo $ItemCode; ?>"><input type="text" name="data['+intIndex+'][JournalD_Desc]" id="data'+intIndex+'JournalD_Desc" value="'+JournalD_Desc+'" class="form-control" style="max-width:500px;" placeholder="<?php echo $Description; ?>"><input type="hidden" name="data['+intIndex+'][ITM_CATEG]" id="data'+intIndex+'ITM_CATEG" value="'+JournalD_Desc+'" class="form-control" style="max-width:500px;"><input type="hidden" name="data['+intIndex+'][JOBCODEID]" id="data'+intIndex+'JOBCODEID" value="'+JOBCODEID+'" class="form-control" style="max-width:500px;">';
		
		// Account Position
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<select name="data['+intIndex+'][JournalD_Pos]" id="data'+intIndex+'JournalD_Pos" class="form-control select2" onChange="countAmount()"><option value="">--</option><option value="D">D</option><option value="K">K</option></select>';
		
		// Account is Tax
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.display = 'none';
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<select name="data['+intIndex+'][isTax]" id="data'+intIndex+'isTax" class="form-control" style="max-width:100px" onChange="countAmount()"><option value="">--</option><option value="0" selected>N</option><option value="1">Y</option></select>';
		
		// Account Reference
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.noWrap = true;
		objTD.innerHTML = '<input type="text" name="data['+intIndex+'][Ref_Number]" id="data'+intIndex+'Ref_Number" value="'+Ref_Number+'" class="form-control" placeholder="<?php echo $RefNumber; ?>" >';
		
		// Account Amount
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" class="form-control" style="text-align:right" name="JournalD_Amount'+intIndex+'" id="JournalD_Amount'+intIndex+'" value="'+JournalD_Debet+'" placeholder="<?php echo $Amount; ?>" onBlur="chgAmount(this,'+intIndex+')" onKeyPress="return isIntOnlyNew(event);"><input type="hidden" name="data['+intIndex+'][JournalD_Amount]" id="data'+intIndex+'JournalD_Amount" value="'+JournalD_Debet+'" class="form-control" style="max-width:150px;" ><input type="hidden" name="data['+intIndex+'][isDirect]" id="data'+intIndex+'isDirect" value="1" class="form-control" style="max-width:150px;" >';
		
		// Remarks
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.innerHTML = '<input type="text" name="data['+intIndex+'][Other_Desc]" id="data'+intIndex+'Other_Desc" size="20" value="" class="form-control" style="min-width:110px; max-width:300px; text-align:left" placeholder="<?php echo $Remarks; ?>">';
		
		var decFormat														= document.getElementById('decFormat').value;
		var JournalD_Amount													= document.getElementById('JournalD_Amount'+intIndex).value
		document.getElementById('JournalD_Amount'+intIndex).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(JournalD_Amount)),decFormat));
		document.getElementById('data'+intIndex+'JournalD_Amount').value 	= parseFloat(Math.abs(JournalD_Amount));
		document.getElementById('totalrow').value = intIndex;
	}
	
	function chgAmount(thisval, row)
	{
		var decFormat													= document.getElementById('decFormat').value;
		var Acc_Amount		= parseFloat(eval(thisval).value.split(",").join(""));
		document.getElementById('JournalD_Amount'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(Acc_Amount)),decFormat));
		document.getElementById('data'+row+'JournalD_Amount').value 	= parseFloat(Math.abs(Acc_Amount));
		
		var totRow = document.getElementById('totalrow').value;
		var totAmount	= 0;
		var totAmountD	= 0;
		var totAmountK	= 0;
		for(i=1;i<=totRow;i++)
		{
			JournalD_Pos 	= document.getElementById('data'+i+'JournalD_Pos').value;
			totAmountA 		= parseFloat(document.getElementById('data'+i+'JournalD_Amount').value);
			//totAmount 	= parseFloat(totAmount) + parseFloat(totAmountA);
			if(JournalD_Pos == 'D')
				totAmountD	= parseFloat(totAmountD) + parseFloat(totAmountA);
			else
				totAmountK	= parseFloat(totAmountK) + parseFloat(totAmountA);
		}
		document.getElementById('Journal_Amount').value = totAmountD;
		document.getElementById('Journal_AmountD').value = totAmountD;
		document.getElementById('Journal_AmountK').value = totAmountK;
	}
	
	function countAmount()
	{
		var totRow = document.getElementById('totalrow').value;
		var totAmount	= 0;
		var totAmountD	= 0;
		var totAmountK	= 0;
		for(i=1;i<=totRow;i++)
		{
			JournalD_Pos 	= document.getElementById('data'+i+'JournalD_Pos').value;
			totAmountA 		= parseFloat(document.getElementById('data'+i+'JournalD_Amount').value);
			//totAmount 	= parseFloat(totAmount) + parseFloat(totAmountA);
			if(JournalD_Pos == 'D')
				totAmountD	= parseFloat(totAmountD) + parseFloat(totAmountA);
			else
				totAmountK	= parseFloat(totAmountK) + parseFloat(totAmountA);
		}
		document.getElementById('Journal_Amount').value = totAmountD;
		document.getElementById('Journal_AmountD').value = totAmountD;
		document.getElementById('Journal_AmountK').value = totAmountK;
	}
	
	function add_item(strItem) 
	{
		arrItem = strItem.split('|');
		
		/*validateDouble(arrItem[0],arrItem[1])
		if(validateDouble(arrItem[0],arrItem[1]))
		{
			swal("Double Item for " + arrItem[0]);
			return;
		}*/
		
		Acc_Id 			= arrItem[0];
		JournalD_Desc 	= arrItem[1];
		Item_Code 		= arrItem[2];
		Item_Categ		= arrItem[3];
		theRow 			= arrItem[4];
		JOBCODEID		= arrItem[5];
		//swal(Acc_Id)
		document.getElementById('data'+theRow+'Acc_Id').value			= Acc_Id;
		document.getElementById('data'+theRow+'ITM_CODE').value			= Item_Code;
		document.getElementById('data'+theRow+'JournalD_Desc').value	= JournalD_Desc;
		document.getElementById('data'+theRow+'ITM_CATEG').value		= Item_Categ;
		document.getElementById('data'+theRow+'JOBCODEID').value		= JOBCODEID;
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
	
	function RoundNDecimal(X, N)
	{
		var T, S=new String(Math.round(X*Number("1e"+N)))
		while (S.length<=N) S='0'+S
		return S.substr(0, T=(S.length-N)) + '.' + S.substr(T, N)
	}
	
	function validateInData()
	{
		var totrow 			= document.getElementById('totalrow').value;
		var JournalH_Desc 	= document.getElementById('JournalH_Desc').value;
		var totAmountD		= parseFloat(document.getElementById('Journal_AmountD').value);
		var totAmountK		= parseFloat(document.getElementById('Journal_AmountK').value);
		
		Pattern_Type	= document.getElementById("Pattern_Type").value;
		if(Pattern_Type == 'LOAN')
		{
			SPLCODE	= document.getElementById("SPLCODE").value;
			if(SPLCODE == '')
			{
				swal('<?php echo $alert11; ?>',
				{
					icon: "warning",
				})
				.then(function()
				{
					swal.close();
					$('#SPLCODE').focus();
				});
				return false;
			}
		}
		
		if(JournalH_Desc == '')
		{
			swal('<?php echo $alert0; ?>',
			{
				icon: "warning",
			})
			.then(function()
			{
				swal.close();
				$('#JournalH_Desc').focus();
			});
			return false;
		}
		
		var rowD		= 0;
		var rowK		= 0;
		for(i=1;i<=totrow;i++)
		{
			var Acc_Id 			= document.getElementById('data'+i+'Acc_Id').value;
			var JournalD_Desc 	= document.getElementById('data'+i+'JournalD_Desc').value;
			var JournalD_Pos 	= document.getElementById('data'+i+'JournalD_Pos').value;
			var isTax			= document.getElementById('data'+i+'isTax').value;
			var Ref_Number		= document.getElementById('data'+i+'Ref_Number').value;
			var JournalD_Amount	= parseFloat(document.getElementById('data'+i+'JournalD_Amount').value);
			var Other_Desc		= document.getElementById('data'+i+'Other_Desc').value;
			
			if(Acc_Id == '')
			{
				swal('<?php echo $alert1; ?>',
				{
					icon: "warning",
				})
				.then(function()
				{
					swal.close();
					document.getElementById('data'+i+'Acc_Id').focus();
				});
				return false;
			}
			
			if(JournalD_Desc == '')
			{
				swal('<?php echo $alert2; ?>',
				{
					icon: "warning",
				})
				.then(function()
				{
					swal.close();
					document.getElementById('data'+i+'JournalD_Desc').focus();
				});
				return false;
			}
			
			if(JournalD_Pos == '')
			{
				swal('<?php echo $alert3; ?>',
				{
					icon: "warning",
				})
				.then(function()
				{
					swal.close();
					document.getElementById('data'+i+'JournalD_Pos').focus();
				});
				return false;
			}
			
			if(isTax == '')
			{
				swal('<?php echo $alert4; ?>',
				{
					icon: "warning",
				})
				.then(function()
				{
					swal.close();
					document.getElementById('data'+i+'isTax').focus();
				});
				return false;
			}
			
			if(Ref_Number == '')
			{
				//swal('<?php echo $alert5; ?>');
				//document.getElementById('data'+i+'Ref_Number').focus();
				//return false;
			}
			
			if(JournalD_Amount == 0)
			{
				swal('<?php echo $alert6; ?>',
				{
					icon: "warning",
				})
				.then(function()
				{
					swal.close();
					document.getElementById('JournalD_Amount'+i).focus();
				});
				return false;
			}
			
			if(JournalD_Pos == 'D')
				var rowD	= parseFloat(rowD) + 1;
			else
				var rowK	= parseFloat(rowK) + 1;
		}
		
		if(rowD == 0)
		{
			swal('<?php echo $alert7; ?>',
			{
				icon: "warning",
			});
			return false;
		}
		
		if(rowK == 0)
		{
			swal('<?php echo $alert8; ?>',
			{
				icon: "warning",
			});
			return false;
		}
		
		var totD 	= parseFloat(totAmountD).toFixed(2);
		var totK 	= parseFloat(totAmountK).toFixed(2);
		//if(totAmountD != totAmountK)
		if(totD != totK)
		{
			swal("<?php echo $alert9; ?>", 
			{
				icon: "error",
			});
			return false;
		}
		
		GEJ_STAT	= document.getElementById("GEJ_STAT").value;
		if(GEJ_STAT == 4 || GEJ_STAT == 5 || GEJ_STAT == 9)
		{
			JournalH_Desc2	= document.getElementById('JournalH_Desc2').value;
			if(JournalH_Desc2 == '')
			{
				swal('<?php echo $alert10; ?>',
				{
					icon: "warning",
				})
				.then(function()
				{
					document.getElementById('JournalH_Desc2').focus();
				});
				return false;
			}
		}
		
		if(totrow == 0)
		{
			swal('<?php echo $inpJTrans; ?>',
			{
				icon: "warning",
			});
			return false;		
		}
		else
		{
			document.getElementById('tblUpdate').style.display = 'none';
			document.frm.submit();
		}
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