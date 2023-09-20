<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 30 Januari 2018
 * File Name	= gej_entry_form.php
 * Location		= -
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

// _global function
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
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
if($task == 'add')
{	
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
	
	
	$sql = "SELECT PRJCODE, PRJNAME FROM tbl_project
			WHERE PRJCODE = '$PRJCODE'";
	$resultProj = $this->db->query($sql)->result();
	foreach($resultProj as $row) :
		$PRJNAME = $row->PRJNAME;
	endforeach;
	$theTimeCode	= date('YmdHis');
	$DocNumber 		= "$Pattern_Code$PRJCODE$theTimeCode";
	$Manual_No		= "$Pattern_Code-$PRJCODE-$lastPatternNumb";
	//echo "Manual_No = $Manual_No";
	$JournalH_Code	= "$DocNumber";
	$JournalH_Date	= date('m/d/Y');
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
	$proj_Code		= $PRJCODE;
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
	$JournalH_Date		= date('m/d/Y',strtotime($JournalH_Date));
	$JournalH_Desc		= $default['JournalH_Desc'];
	$JournalH_Desc2		= $default['JournalH_Desc2'];
	$proj_Code			= $default['proj_Code'];
	$PRJCODE			= $proj_Code;
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
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/style.css'; ?>");</style>
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/styleZebra.css'; ?>");</style>
    <title><?php echo $appName; ?> | Data Tables</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/bootstrap/css/bootstrapa.min.css'; ?>">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/css/font-awesome.min.css'; ?>">
    <!-- Ionicons -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/css/ionicons.min.css'; ?>">
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE.min.css'; ?>">
    <!-- DataTables -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/dataTables.bootstrap.css'; ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/AdminLTE.minaa.css'; ?>">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/skins/_all-skins.min.css'; ?>">
        <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE.css'; ?>">
    <!-- daterange picker -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/daterangepicker/daterangepicker.css'; ?>">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datepicker/datepicker3.css'; ?>">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/iCheck/all.css'; ?>">
    <!-- Bootstrap Color Picker -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/colorpicker/bootstrap-colorpicker.min.css'; ?>">
    <!-- Bootstrap time Picker -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/timepicker/bootstrap-timepicker.min.css'; ?>">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/dataTables.bootstrap.css'; ?>">
    <!-- Select2 -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/select2/select2.min.css'; ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/AdminLTE.min.css'; ?>">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/skins/_all-skins.min.css'; ?>">
    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<script src="<?php echo base_url('assets/js/jquery-1.10.1.min.js') ?>" type="text/javascript"></script>
<?php
	$this->load->view('template/topbar');
	$this->load->view('template/sidebar');
	
	$ISREAD 	= $this->session->userdata['ISREAD'];
	$ISCREATE 	= $this->session->userdata['ISCREATE'];
	$ISAPPROVE 	= $this->session->userdata['ISAPPROVE'];
	$ISDWONL 	= $this->session->userdata['ISDWONL'];
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
		if($TranslCode == 'Cancel')$Cancel = $LangTransl;
		if($TranslCode == 'RequestNo')$RequestNo = $LangTransl;
		if($TranslCode == 'Account')$Account = $LangTransl;
		if($TranslCode == 'PR_CODE')$PR_CODE = $LangTransl;
		if($TranslCode == 'Notes')$Notes = $LangTransl;
		if($TranslCode == 'Date')$Date = $LangTransl;
		if($TranslCode == 'Project')$Project = $LangTransl;
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
	endforeach;
	
	if($LangID == 'IND')
	{
		$subTitleH	= "Tambah";
		$subTitleD	= "permintaan pembelian";
		$alert0		= "Masukan deskripsi jurnal transaksi.";
		$alert1		= "Silahkan pilih Nomor Akun.";
		$alert2		= "Silahkan tuliskan deskripsi jurnal.";
		$alert3		= "Tentukan posisi akun; di Debit atau Kredit.";
		$alert4		= "Tentukan posisi jenis transaksi, pajak atau bukan.";
		$alert5		= "Masukan nomor referensi jurnal transaksi.";
		$alert6		= "Transaksi tidak boleh 0.";
		$alert7		= "Belum ada akun untuk sisi Debit.";
		$alert8		= "Belum ada akun untuk sisi Kredit.";
		$alert9		= "Nilai Debit dan Kredit tidak sama.";
		$alert10	= "Masukan alasan mengapa dokumen ini di-reject.";
		$alert11	= "Silahkan pilih nama supplier.";
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
		$alert10	= "Input the reason why you reject this document.";
		$alert11	= "Please select a supplier.";
	}
	
	$secAddURL	= site_url('c_purchase/c_purchase_req/add/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
	$secGenCode	= base_url().'index.php/c_purchase/c_purchase_req/genCode/'; // Generate Code
	
	// START : APPROVE PROCEDURE
		$IS_LAST	= 0;
		$APP_LEVEL	= 0;
		$APPROVER_1	= '';
		$APPROVER_2	= '';
		$APPROVER_3	= '';
		$APPROVER_4	= '';
		$APPROVER_5	= '';	
		$disableAll	= 1;
		$DOCAPP_TYPE= 1;
		$sqlCAPP	= "tbl_docstepapp WHERE MENU_CODE = '$MenuCode' AND PRJCODE = '$PRJCODE'";
		$resCAPP	= $this->db->count_all($sqlCAPP);
		if($resCAPP > 0)
		{
			$sqlAPP	= "SELECT * FROM tbl_docstepapp WHERE MENU_CODE = '$MenuCode'
						AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE')";
			$resAPP	= $this->db->query($sqlAPP)->result();
			foreach($resAPP as $rowAPP) :
				$MAX_STEP	= $rowAPP->MAX_STEP;
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
			$sqlAPPT	= "SELECT DOCAPP_TYPE FROM tbl_docstepapp WHERE MENU_CODE = '$MenuCode'
							AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE')";
			$resAPPT	= $this->db->query($sqlAPP)->result();
			foreach($resAPPT as $rowAPPT) :
				$DOCAPP_TYPE	= $rowAPPT->DOCAPP_TYPE;
			endforeach;
		}
		
		$sqlSTEPAPP	= "tbl_docstepapp_det WHERE MENU_CODE = '$MenuCode' AND APPROVER_1 = '$DefEmp_ID'
						AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE')";
		$resSTEPAPP	= $this->db->count_all($sqlSTEPAPP);
		if($resSTEPAPP > 0)
		{
			$canApprove	= 1;
			$APPLIMIT_1	= 0;
			
			$sqlAPP	= "SELECT APPLIMIT_1, APP_STEP, MAX_STEP FROM tbl_docstepapp_det WHERE MENU_CODE = '$MenuCode'
						AND APPROVER_1 = '$DefEmp_ID' AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE')";
			$resAPP	= $this->db->query($sqlAPP)->result();
			foreach($resAPP as $rowAPP) :
				$APPLIMIT_1	= $rowAPP->APPLIMIT_1;
				$APP_STEP	= $rowAPP->APP_STEP;
				$MAX_STEP	= $rowAPP->MAX_STEP;
			endforeach;
			
			$sqlC_App	= "tbl_approve_hist WHERE AH_CODE = '$DocNumber'";
			$resC_App 	= $this->db->count_all($sqlC_App);
			//$appReady	= $APP_STEP;
			//if($resC_App == 0)
			//echo "APP_STEP = $APP_STEP = $resC_App = $MAX_STEP";
			$BefStepApp	= $APP_STEP - 1;
			//echo "1. resC_App = $resC_App == BefStepApp = $BefStepApp == canApprove = $canApprove == APP_STEP = $APP_STEP<br>";
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
			//echo "2. canApprove = $canApprove<br>"; 
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
?>

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>
    <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/journal.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $subTitleH; ?>
    <small><?php echo $GeneralJournal; ?></small>  </h1>
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
        <form method="post" name="sendDate" id="sendDate" class="form-user" action="<?php echo $secGenCode; ?>" style="display:none">		
            <table>
                <tr>
                    <td>
                        <input type="hidden" name="PRJCODEX" id="PRJCODEX" value="<?php echo $PRJCODE; ?>">
                        <input type="hidden" name="Pattern_Code" id="Pattern_Code" value="<?php echo $Pattern_Code; ?>">
                        <input type="hidden" name="Pattern_Length" id="Pattern_Length" value="<?php echo $Pattern_Length; ?>">
                        <input type="hidden" name="useYear" id="useYear" value="<?php echo $useYear; ?>">
                        <input type="hidden" name="useMonth" id="useMonth" value="<?php echo $useMonth; ?>">
                        <input type="hidden" name="useDate" id="useDate" value="<?php echo $useDate; ?>">
                        <input type="hidden" name="PRDate" id="PRDate" value="">
                    </td>
                    <td><a class="tombol-date" id="dateClass">Simpan</a></td>
                </tr>
            </table>
        </form>
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
            	<div class="form-group" <?php if($task == 'add') { ?> style="display:none" <?php } ?>>
                    <label for="inputName" class="col-sm-2 control-label"><?php echo $JournalCode; ?></label>
                    <div class="col-sm-10">
                    	<input type="text" class="form-control" style="max-width:400px;text-align:left" name="JournalH_Code1" id="JournalH_Code1" size="30" value="<?php echo $DocNumber; ?>" disabled />
                    	<input type="hidden" class="form-control" style="max-width:350px;text-align:right" name="JournalH_Code" id="JournalH_Code" size="30" value="<?php echo $DocNumber; ?>" />
                        <input type="hidden" class="form-control" style="max-width:350px;text-align:right" name="PRJCODE" id="PRJCODE" size="30" value="<?php echo $PRJCODE; ?>" />
                    </div>
                </div>
            	<div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label"><?php echo $ManualCode; ?></label>
                    <div class="col-sm-10">
                    	<input type="text" class="form-control" style="max-width:400px;" name="Manual_No" id="JournalH_Code" size="30" value="<?php echo $Manual_No; ?>" />
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
                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Project; ?></label>
                    <div class="col-sm-10">
                    	<select name="proj_Code" id="proj_Code" class="form-control" style="max-width:400px" disabled>
                          <option value="none">--- None ---</option>
                          <?php
						  	$theProjCode 	= $PRJCODE;
                        	$url_AddItem	= site_url('c_gl/cgeje0b28t18/puSA0b28t18/?id=');
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
                    <input type="hidden" class="form-control" style="max-width:350px;text-align:right" id="proj_Code" name="proj_Code" size="20" value="<?php echo $proj_Code; ?>" />                        
                    </div>
                </div>
            	<div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Type; ?></label>
                    <div class="col-sm-10">
                    	<select name="Pattern_Type" id="Pattern_Type" class="form-control select2" style="max-width:150px" onChange="chkSPL(this.value)">
                          <option value="GEJ" <?php if($Pattern_Type == 'GEJ') { ?> selected <?php } ?>><?php echo $GeneralJournal; ?></option>
                          <option value="LOAN" <?php if($Pattern_Type == 'LOAN') { ?> selected <?php } ?>><?php echo $Loan; ?></option>
                        </select>
                    <input type="hidden" class="form-control" style="max-width:350px;text-align:right" id="proj_Code" name="proj_Code" size="20" value="<?php echo $proj_Code; ?>" />                        
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
                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Project; ?></label>
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
                    	<textarea name="JournalH_Desc" class="form-control" style="max-width:400px;" id="JournalH_Desc" cols="30"><?php echo $JournalH_Desc; ?></textarea>    
                    	<input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="3">
                        <input type="hidden" name="Journal_Amount" id="Journal_Amount" value="">
                        <input type="hidden" name="Journal_AmountD" id="Journal_AmountD" value="">
                        <input type="hidden" name="Journal_AmountK" id="Journal_AmountK" value="">
                    </div>
                </div>
            	<div class="form-group" <?php if($JournalH_Desc2 == '' ) { ?> style="display:none" <?php } ?> id="tblReason">
                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Reason; ?></label>
                    <div class="col-sm-10">
                    	<textarea name="JournalH_Desc2" class="form-control" style="max-width:400px;" id="JournalH_Desc2" cols="30"><?php echo $JournalH_Desc2; ?></textarea>  
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
											<select name="GEJ_STAT" id="GEJ_STAT" class="form-control select2" style="max-width:120px" onChange="selStat(this.value)" >
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
												<select name="GEJ_STAT" id="GEJ_STAT" class="form-control select2" style="max-width:120px" onChange="selStat(this.value)" >
													<option value="1">New</option>
													<option value="2">Confirm</option>
												</select>
											<?php
										}
										elseif($GEJ_STAT == 2 || $GEJ_STAT == 3 || $GEJ_STAT == 7)
										{
											$disButton	= 0;
											if($canApprove == 0)
												$disButton	= 1;
											
											$sqlCAPPHE	= "tbl_approve_hist WHERE AH_CODE = '$JournalH_Code' AND AH_APPROVER = '$DefEmp_ID'";
											$resCAPPHE	= $this->db->count_all($sqlCAPPHE);
											if($resCAPPHE > 0)
												$disButton	= 1;
										
											?>
												<select name="GEJ_STAT" id="GEJ_STAT" class="form-control select2" style="max-width:120px" onChange="selStat(this.value)" <?php if($disButton == 1) { ?> disabled <?php } ?>>
													<option value="1"<?php if($GEJ_STAT == 1) { ?> selected <?php } ?> disabled >New</option>
													<option value="2"<?php if($GEJ_STAT == 2) { ?> selected <?php } ?> >Confirm</option>
													<option value="3"<?php if($GEJ_STAT == 3) { ?> selected <?php } ?> >Approved</option>
													<option value="4"<?php if($GEJ_STAT == 4) { ?> selected <?php } ?> >Revising</option>
													<option value="5"<?php if($GEJ_STAT == 5) { ?> selected <?php } ?> >Rejected</option>
													<option value="6"<?php if($GEJ_STAT == 6) { ?> selected <?php } ?> style="display:none" >Closed</option>
													<option value="7"<?php if($GEJ_STAT == 7) { ?> selected <?php } ?> >Waiting</option>
													<?php if($GEJ_STAT == 3 || $GEJ_STAT == 9) { ?>
													<option value="9"<?php if($GEJ_STAT == 9) { ?> selected <?php } ?>>Void</option>
													<?php } ?>
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
												<select name="GEJ_STAT" id="GEJ_STAT" class="form-control select2" style="max-width:120px" onChange="selStat(this.value)" <?php if($disButton == 1) { ?> disabled <?php } ?>>
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
												<select name="GEJ_STAT" id="GEJ_STAT" class="form-control select2" style="max-width:120px" onChange="selStat(this.value)" <?php if($disButton == 1) { ?> disabled <?php } ?>>
													<option value="1"<?php if($GEJ_STAT == 1) { ?> selected <?php } ?> disabled >New</option>
													<option value="2"<?php if($GEJ_STAT == 2) { ?> selected <?php } ?> >Confirm</option>
													<option value="3"<?php if($GEJ_STAT == 3) { ?> selected <?php } ?> >Approved</option>
													<option value="4"<?php if($GEJ_STAT == 4) { ?> selected <?php } ?> >Revising</option>
													<option value="5"<?php if($GEJ_STAT == 5) { ?> selected <?php } ?> >Rejected</option>
													<option value="6"<?php if($GEJ_STAT == 6) { ?> selected <?php } ?> style="display:none" >Closed</option>
													<option value="7"<?php if($GEJ_STAT == 7) { ?> selected <?php } ?> >Waiting</option>
													<?php if($GEJ_STAT == 3 || $GEJ_STAT == 9) { ?>
													<option value="9"<?php if($GEJ_STAT == 9) { ?> selected <?php } ?>>Void</option>
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
												<select name="GEJ_STAT" id="GEJ_STAT" class="form-control select2" style="max-width:120px" onChange="selStat(this.value)" <?php if($disButton == 1) { ?> disabled <?php } ?>>
													<option value="1"<?php if($GEJ_STAT == 1) { ?> selected <?php } ?> >New</option>
													<option value="2"<?php if($GEJ_STAT == 2) { ?> selected <?php } ?> >Confirm</option>
													<option value="3"<?php if($GEJ_STAT == 3) { ?> selected <?php } ?> disabled >Approved</option>
													<option value="4"<?php if($GEJ_STAT == 4) { ?> selected <?php } ?> disabled >Revising</option>
													<option value="5"<?php if($GEJ_STAT == 5) { ?> selected <?php } ?> disabled >Rejected</option>
													<option value="6"<?php if($GEJ_STAT == 6) { ?> selected <?php } ?> style="display:none" >Closed</option>
													<option value="7"<?php if($GEJ_STAT == 7) { ?> selected <?php } ?> disabled >Waiting</option>
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
												<select name="GEJ_STAT" id="GEJ_STAT" class="form-control select2" style="max-width:120px" onChange="selStat(this.value)" <?php if($disButton == 1) { ?> disabled <?php } ?>>
													<option value="1"<?php if($GEJ_STAT == 1) { ?> selected <?php } ?> disabled >New</option>
													<option value="2"<?php if($GEJ_STAT == 2) { ?> selected <?php } ?> disabled >Confirm</option>
													<option value="3"<?php if($GEJ_STAT == 3) { ?> selected <?php } ?> disabled >Approved</option>
													<option value="4"<?php if($GEJ_STAT == 4) { ?> selected <?php } ?> disabled >Revising</option>
													<option value="5"<?php if($GEJ_STAT == 5) { ?> selected <?php } ?> disabled >Rejected</option>
													<option value="6"<?php if($GEJ_STAT == 6) { ?> selected <?php } ?> style="display:none" >Closed</option>
													<option value="7"<?php if($GEJ_STAT == 7) { ?> selected <?php } ?> disabled >Waiting</option>
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
						if(thisValue == 3 || thisValue == 4 || thisValue == 5 || thisValue == 9)
						{
							//document.getElementById('tblClose').style.display = '';
							document.getElementById('tblReason').style.display = '';
						}
						else
						{
							//document.getElementById('tblClose').style.display = 'none';
							document.getElementById('tblReason').style.display = 'none';
						}
					}
				</script>
				<?php
                   if($ISCREATE == 1)
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
                        <br>
                        <table width="100%" border="1" id="tbl">
                        	<tr style="background:#CCCCCC">
                              <th width="4%" height="25" style="text-align:left">&nbsp;</th>
                              <th width="14%" style="text-align:center"><?php echo $AccountNo; ?> </th>
                              <th width="31%" style="text-align:center"><?php echo $Description; ?> </th>
                              <th style="text-align:center" nowrap><?php echo $AccountPosition; ?> </th>
                              <th style="text-align:center; display:none" nowrap><?php echo $Tax; ?></th>
                              <th width="14%" style="text-align:center"><?php echo $RefNumber; ?> </th>
                              <th width="14%" style="text-align:center"><?php echo $Amount; ?></th>
                              <th width="12%" style="text-align:center"><?php echo $Remarks; ?></th>
                          	</tr>
                            <?php					
                            if($task == 'edit')
                            {
                                $sqlDET	= "SELECT A.JournalH_Code, A.Acc_Id, A.proj_Code, A.JournalD_Debet, 
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
									
									if ($j==1) {
										echo "<tr class=zebra1>";
										$j++;
									} else {
										echo "<tr class=zebra2>";
										$j--;
									}
									?> 
                                    <tr id="tr_<?php echo $currentRow; ?>">
                                        <td width="4%" height="25" style="text-align:center;" nowrap>
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
                                        <td width="14%" style="text-align:left">
                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][JournalH_Code]" id="data<?php echo $currentRow; ?>JournalH_Code" value="<?php echo $JournalH_Code; ?>" class="form-control" style="max-width:150px;" >
                                            <input type="text" name="data[<?php echo $currentRow; ?>][Acc_Id]" id="data<?php echo $currentRow; ?>Acc_Id" value="<?php echo $Acc_Id; ?>" class="form-control" style="max-width:150px;" onClick="selectAccount(<?php echo $currentRow; ?>);" placeholder="<?php echo $SelectAccount; ?>" >
                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][proj_Code]" id="data<?php echo $currentRow; ?>proj_Code" value="<?php echo $proj_Code; ?>" class="form-control" style="max-width:150px;" >
                                        </td>
                                        <td width="31%" style="text-align:left">
                                            <input type="text" name="data[<?php echo $currentRow; ?>][JournalD_Desc]" id="data<?php echo $currentRow; ?>JournalD_Desc" value="<?php echo $ITM_NAME; ?>" class="form-control" style="max-width:500px;" placeholder="<?php echo $Description; ?>">
                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][ITM_CODE]" id="data<?php echo $currentRow; ?>ITM_CODE" value="<?php echo $ITM_CODE; ?>" class="form-control" style="max-width:500px;" placeholder="<?php echo $ItemCode; ?>">
                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][ITM_CATEG]" id="data<?php echo $currentRow; ?>ITM_CATEG" value="<?php echo $ITM_CATEG; ?>" class="form-control" style="max-width:500px;">
                                        </td>
                                        <td width="5%" nowrap style="text-align:center">
                                            <select name="data[<?php echo $currentRow; ?>][JournalD_Pos]" id="data<?php echo $currentRow; ?>JournalD_Pos" class="form-control" style="max-width:100px" onChange="countAmount()">
                                                <option value="" <?php if($Journal_DK == "") { ?> selected <?php } ?>>--</option>
                                                <option value="D" <?php if($Journal_DK == "D") { ?> selected <?php } ?>>D</option>
                                                <option value="K" <?php if($Journal_DK == "K") { ?> selected <?php } ?>>K</option>
                                            </select>
                                        </td>
                                        <td width="6%" nowrap style="text-align:center; display:none">
                                            <select name="data[<?php echo $currentRow; ?>][isTax]" id="data<?php echo $currentRow; ?>isTax" class="form-control" style="max-width:100px" onChange="countAmount()">
                                                <option value="" <?php if($isTax == "") { ?> selected <?php } ?>>--</option>
                                                <option value="0" selected >N</option>
                                                <option value="1" <?php if($isTax == '1') { ?> selected <?php } ?>>Y</option>
                                            </select>
                                        </td>
                                        <td width="14%" style="text-align:left">
                                            <input type="text" name="data[<?php echo $currentRow; ?>][Ref_Number]" id="data<?php echo $currentRow; ?>Ref_Number" value="<?php echo $Ref_Number; ?>" class="form-control" style="max-width:150px;" placeholder="<?php echo $RefNumber; ?>" >
                                        </td>
                                        <td width="14%" style="text-align:right">
                                            <input type="text" class="form-control" style="text-align:right" name="JournalD_Amount<?php echo $currentRow; ?>" id="JournalD_Amount<?php echo $currentRow; ?>" value="<?php echo number_format($AmountV, 2); ?>" placeholder="<?php echo $Amount; ?>" onBlur="chgAmount(this,<?php echo $currentRow; ?>)" onKeyPress="return isIntOnlyNew(event);" title="<?php echo number_format($AmountV, 2); ?>">
                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][JournalD_Amount]" id="data<?php echo $currentRow; ?>JournalD_Amount" value="<?php echo $AmountV; ?>" class="form-control" style="max-width:150px;" >
                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][isDirect]" id="data<?php echo $currentRow; ?>isDirect" value="1" class="form-control" style="max-width:150px;" >
                                        </td>
                                        <td width="12%" style="text-align:left">
                                            <input type="text" name="data[<?php echo $currentRow; ?>][Other_Desc]" id="data<?php echo $currentRow; ?>Other_Desc" size="20" value="<?php echo $Other_Desc; ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:left" placeholder="<?php echo $Remarks; ?>">
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
                <br>
                <?php
					//echo "$ISAPPROVE == 1 && $GEJ_STAT == 2 && $canApprove == 1";
				?>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <?php
							if($task=='add')
							{
								if($GEJ_STAT == 1 && $ISCREATE == 1 && $disButton == 0)
								{
									?>
										<button class="btn btn-primary">
										<i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Save; ?>
										</button>&nbsp;
									<?php
								}
							}
							else
							{
								if($disButton == 0)
								{
									?>
                                        <button class="btn btn-primary" id="tblClose">
                                        <i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Update; ?>
                                        </button>&nbsp;
									<?php
								}
							}
							
							$backURL	= site_url('c_gl/cgeje0b28t18/gej0b28t18/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
							echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;'.$Cancel.'</button>');
                        ?>
                    </div>
                </div>
			</form>
    	</div>
    </div>
</section>
</body>

</html>

<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/jQuery/jquery-2.2.3.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/bootstrap/js/bootstrap.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/jquery.dataTables.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/dataTables.bootstrap.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/slimScroll/jquery.slimscroll.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/fastclick/fastclick.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE/dist/js/demo.js'; ?>"></script>

<!-- Select2 -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/select2/select2.full.min.js'; ?>"></script>
<!-- InputMask -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/input-mask/jquery.inputmask.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/input-mask/jquery.inputmask.date.extensions.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/input-mask/jquery.inputmask.extensions.js'; ?>"></script>
<!-- date-range-picker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/daterangepicker/daterangepicker.js'; ?>"></script>
<!-- bootstrap datepicker -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datepicker/bootstrap-datepicker.js'; ?>"></script>
<!-- bootstrap color picker -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/colorpicker/bootstrap-colorpicker.min.js'; ?>"></script>
<!-- bootstrap time picker -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/timepicker/bootstrap-timepicker.min.js'; ?>"></script>
<!-- SlimScroll 1.3.0 -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/slimScroll/jquery.slimscroll.min.js'; ?>"></script>
<!-- iCheck 1.0.1 -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/iCheck/icheck.min.js'; ?>"></script>
<!-- Page script -->
<script>
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
      autoclose: true,
    });

    //Date picker
    $('#datepicker2').datepicker({
      autoclose: true,
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
		proj_Code 			= '';
		ITM_CODE			= '';
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
		objTD.align = "center";
		objTD.noWrap = true;
		objTD.innerHTML = '<a href="#" onClick="deleteRow('+intIndex+')" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a><input type="hidden" id="chk" name="chk" value="'+intIndex+'" width="10" size="15" readonly class="form-control" style="max-width:300px;">';
		
		// Account Number
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'right';
		objTD.innerHTML = '<input type="hidden" name="data['+intIndex+'][JournalH_Code]" id="data'+intIndex+'JournalH_Code" value="<?php echo $JournalH_Code; ?>" class="form-control" style="max-width:150px;" ><input type="text" name="data['+intIndex+'][Acc_Id]" id="data'+intIndex+'Acc_Id" value="'+Acc_Id+'" class="form-control" style="max-width:150px;" onClick="selectAccount('+intIndex+');" placeholder="<?php echo $SelectAccount; ?>" ><input type="hidden" name="data['+intIndex+'][proj_Code]" id="data'+intIndex+'proj_Code" value="<?php echo $proj_Code; ?>" class="form-control" style="max-width:150px;" >';
		
		// Account Description
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.noWrap = true;
		objTD.innerHTML = '<input type="hidden" name="data['+intIndex+'][ITM_CODE]" id="data'+intIndex+'ITM_CODE" value="'+ITM_CODE+'" class="form-control" style="max-width:500px;" placeholder="<?php echo $ItemCode; ?>"><input type="text" name="data['+intIndex+'][JournalD_Desc]" id="data'+intIndex+'JournalD_Desc" value="'+JournalD_Desc+'" class="form-control" style="max-width:500px;" placeholder="<?php echo $Description; ?>"><input type="hidden" name="data['+intIndex+'][ITM_CATEG]" id="data'+intIndex+'ITM_CATEG" value="'+JournalD_Desc+'" class="form-control" style="max-width:500px;">';
		
		// Account Position
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<select name="data['+intIndex+'][JournalD_Pos]" id="data'+intIndex+'JournalD_Pos" class="form-control" style="max-width:100px" onChange="countAmount()"><option value="">--</option><option value="D">D</option><option value="K">K</option></select>';
		
		// Account is Tax
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.display = 'none';
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<select name="data['+intIndex+'][isTax]" id="data'+intIndex+'isTax" class="form-control" style="max-width:100px" onChange="countAmount()"><option value="">--</option><option value="0" selected>N</option><option value="1">Y</option></select>';
		
		// Account Reference
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.noWrap = true;
		objTD.innerHTML = '<input type="text" name="data['+intIndex+'][Ref_Number]" id="data'+intIndex+'Ref_Number" value="'+Ref_Number+'" class="form-control" style="max-width:150px;" placeholder="<?php echo $RefNumber; ?>" >';
		
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
		//swal(Acc_Id)
		document.getElementById('data'+theRow+'Acc_Id').value			= Acc_Id;
		document.getElementById('data'+theRow+'ITM_CODE').value			= Item_Code;
		document.getElementById('data'+theRow+'JournalD_Desc').value	= JournalD_Desc;
		document.getElementById('data'+theRow+'ITM_CATEG').value		= Item_Categ;
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
				swal('<?php echo $alert11; ?>');
				document.getElementById('SPLCODE').focus();
				return false;
			}
		}
		
		if(JournalH_Desc == '')
		{
			swal('<?php echo $alert0; ?>');
			document.getElementById('JournalH_Desc').focus();
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
				swal('<?php echo $alert1; ?>');
				document.getElementById('data'+i+'Acc_Id').focus();
				return false;
			}
			
			if(JournalD_Desc == '')
			{
				swal('<?php echo $alert2; ?>');
				document.getElementById('data'+i+'JournalD_Desc').focus();
				return false;
			}
			
			if(JournalD_Pos == '')
			{
				swal('<?php echo $alert3; ?>');
				document.getElementById('data'+i+'JournalD_Pos').focus();
				return false;
			}
			
			if(isTax == '')
			{
				swal('<?php echo $alert4; ?>');
				document.getElementById('data'+i+'isTax').focus();
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
				swal('<?php echo $alert6; ?>');
				document.getElementById('JournalD_Amount'+i).focus();
				return false;
			}
			
			if(JournalD_Pos == 'D')
				var rowD	= parseFloat(rowD) + 1;
			else
				var rowK	= parseFloat(rowK) + 1;
		}
		
		if(rowD == 0)
		{
			swal('<?php echo $alert7; ?>')
			return false;
		}
		
		if(rowK == 0)
		{
			swal('<?php echo $alert8; ?>')
			return false;
		}
			
		if(totAmountD != totAmountK)
		{
			swal('<?php echo $alert9; ?>');
			return false;
		}
		
		GEJ_STAT	= document.getElementById("GEJ_STAT").value;
		if(GEJ_STAT == 5 || GEJ_STAT == 9)
		{
			JournalH_Desc2	= document.getElementById('JournalH_Desc2').value;
			if(JournalH_Desc2 == '')
			{
				swal('<?php echo $alert10; ?>');
				document.getElementById('JournalH_Desc2').focus();
				return false;
			}
		}
		
		if(totrow == 0)
		{
			swal('Please input detail Material Request.');
			return false;		
		}
		else
		{
			document.frm.submit();
		}
	}
</script>
<?php 
$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>