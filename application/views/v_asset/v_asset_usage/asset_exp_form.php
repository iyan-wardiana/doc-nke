<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 26 Juni 2018
 * File Name	= asset_exp_form.php
 * Location		= -
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');

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
	$myCount = $this->db->count_all('tbl_asset_exph');
	
	$sql = "SELECT MAX(Patt_Number) as maxNumber FROM tbl_asset_exph
			WHERE Patt_Year = $yearC AND PRJCODE = '$PRJCODE'";
	$result = $this->db->query($sql)->result();
	if($myCount>0)
	{
		foreach($result as $row) :
			$myMax = $row->maxNumber;
			$myMax = $myMax+1;
		endforeach;
	}	else	{		$myMax = 1;	}
	
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
	
	
	$sql = "SELECT PRJCODE, PRJNAME FROM tbl_project
			WHERE PRJCODE = '$PRJCODE'";
	$resultProj = $this->db->query($sql)->result();
	foreach($resultProj as $row) :
		$PRJNAME = $row->PRJNAME;
	endforeach;
	
	$PACODE			= substr($lastPatternNumb, -4);
	$DocNumber 		= "$Pattern_Code$PRJCODE$groupPattern-$lastPatternNumb";
	$ASEXP_NUM		= "$DocNumber";
	
	$ASEXPCODE		= substr($lastPatternNumb, -4);
	$ASEXPYEAR		= date('y');
	$ASEXPMONTH		= date('m');
	$ASEXP_CODE		= "AE.$ASEXPCODE.$ASEXPYEAR.$ASEXPMONTH"; // MANUAL CODE	
	
	$ASEXP_DATE		= date('m/d/Y');
	$PRJCODE		= $PRJCODE;
	$JOBCODEID		= '';
	$ASEXP_REFNO	= $JOBCODEID;
	$ASEXP_NOTE		= '';
	$ASEXP_NOTE2	= '';
	$ASEXP_REVMEMO	= '';
	$ASEXP_STAT 	= 1;
	$ASEXP_AMOUNT	= 0;		
	$Patt_Year 		= date('Y');
	$Patt_Number	= $lastPatternNumb1;
}
else
{
	$isSetDocNo = 1;	
	$ASEXP_NUM		= $default['ASEXP_NUM'];
	$DocNumber		= $default['ASEXP_NUM'];
	$ASEXP_CODE		= $default['ASEXP_CODE'];
	$ASEXP_DATE		= $default['ASEXP_DATE'];
	$ASEXP_DATE		= date('m/d/Y',strtotime($ASEXP_DATE));
	$PRJCODE		= $default['PRJCODE'];
	$JOBCODEID		= $default['JOBCODEID'];
	$ASEXP_REFNO	= $JOBCODEID;
	$ASEXP_NOTE		= $default['ASEXP_NOTE'];
	$ASEXP_NOTE2	= $default['ASEXP_NOTE2'];
	$ASEXP_REVMEMO	= $default['ASEXP_REVMEMO'];
	$ASEXP_STAT		= $default['ASEXP_STAT'];
	$ASEXP_REVMEMO		= $default['ASEXP_REVMEMO'];
	$ASEXP_AMOUNT	= $default['ASEXP_AMOUNT'];
	$Patt_Year		= $default['Patt_Year'];
	$Patt_Month		= $default['Patt_Month'];
	$Patt_Date		= $default['Patt_Date'];
	$Patt_Number	= $default['Patt_Number'];
	$lastPatternNumb1= $default['Patt_Number'];
}

if(isset($_POST['JOBCODE1']))
{
	$ASEXP_REFNO	= $_POST['JOBCODE1'];
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
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() . 'assets/css/spritecss.css'; ?>">
    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

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
		if($TranslCode == 'RequestCode')$RequestCode = $LangTransl;
		if($TranslCode == 'ExpNo')$ExpNo = $LangTransl;
		if($TranslCode == 'Code')$Code = $LangTransl;
		if($TranslCode == 'Notes')$Notes = $LangTransl;
		if($TranslCode == 'ApproverNotes')$ApproverNotes = $LangTransl;
		if($TranslCode == 'Date')$Date = $LangTransl;
		if($TranslCode == 'Project')$Project = $LangTransl;
		if($TranslCode == 'Status')$Status = $LangTransl;
		if($TranslCode == 'AlertRevisionorReject')$AlertRevisionorReject = $LangTransl;
		if($TranslCode == 'New')$New = $LangTransl;
		if($TranslCode == 'Confirm')$Confirm = $LangTransl;
		if($TranslCode == 'Close')$Close = $LangTransl;		
		if($TranslCode == 'AssetCode')$AssetCode = $LangTransl;
		if($TranslCode == 'AssetDescription')$AssetDescription = $LangTransl;
		if($TranslCode == 'BudgetQty')$BudgetQty = $LangTransl;
		if($TranslCode == 'Planning')$Planning = $LangTransl;
		if($TranslCode == 'AssetCapacity')$AssetCapacity = $LangTransl;
		if($TranslCode == 'RequestNow')$RequestNow = $LangTransl;
		if($TranslCode == 'Quantity')$Quantity = $LangTransl;
		if($TranslCode == 'Unit')$Unit = $LangTransl;
		if($TranslCode == 'Primary')$Primary = $LangTransl;
		if($TranslCode == 'Secondary')$Secondary = $LangTransl;
		if($TranslCode == 'Remarks')$Remarks = $LangTransl;
		if($TranslCode == 'SelAsset')$SelAsset = $LangTransl;
		if($TranslCode == 'JobName')$JobName = $LangTransl;
		if($TranslCode == 'ItemQty')$ItemQty = $LangTransl;
		if($TranslCode == 'ReceiptDate')$ReceiptDate = $LangTransl;
		if($TranslCode == 'reviseNotes')$reviseNotes = $LangTransl;
		if($TranslCode == 'Budget')$Budget = $LangTransl;
		if($TranslCode == 'PerMonth')$PerMonth = $LangTransl;
	endforeach;
	
	if($LangID == 'IND')
	{
		$subTitleH	= "Tambah";
		$subTitleD	= "pembebanan aset";
		
		$alert1		= "Masukan nilai pembebanan";
		$alert2		= "Pilih salah satu Aset.";
		$alert3		= "Silahkan pilih status persetujuan.";
	}
	else
	{
		$subTitleH	= "Add";
		$subTitleD	= "asset expenses";
		
		$alert1		= "Please input amount expense.";
		$alert2		= "Please select asset.";
		$alert3		= "Please select an approval status.";
	}
	
	$secGenCode	= base_url().'index.php/c_asset/c_1s3txpen/genCode/'; // Generate Code
	
	// START : APPROVE PROCEDURE
		if($APPLEV == 'HO')
			$PRJCODE_LEV	= $PRJCODE;
		else
			$PRJCODE_LEV	= $PRJCODE;
		
		// DocNumber - ASEXP_AMOUNT
		$IS_LAST	= 0;
		$APP_LEVEL	= 0;
		$APPROVER_1	= '';
		$APPROVER_2	= '';
		$APPROVER_3	= '';
		$APPROVER_4	= '';
		$APPROVER_5	= '';	
		$disableAll	= 1;
		$DOCAPP_TYPE= 1;
		$sqlCAPP	= "tbl_docstepapp WHERE MENU_CODE = '$MenuCode' AND PRJCODE = '$PRJCODE_LEV'";
		$resCAPP	= $this->db->count_all($sqlCAPP);
		if($resCAPP > 0)
		{
			$sqlAPP	= "SELECT * FROM tbl_docstepapp WHERE MENU_CODE = '$MenuCode'
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
			$sqlAPPT	= "SELECT DOCAPP_TYPE FROM tbl_docstepapp WHERE MENU_CODE = '$MenuCode'
							AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE_LEV')";
			$resAPPT	= $this->db->query($sqlAPP)->result();
			foreach($resAPPT as $rowAPPT) :
				$DOCAPP_TYPE	= $rowAPPT->DOCAPP_TYPE;
			endforeach;
		}
		
		$sqlSTEPAPP	= "tbl_docstepapp_det WHERE MENU_CODE = '$MenuCode' AND APPROVER_1 = '$DefEmp_ID'
						AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE_LEV')";
		$resSTEPAPP	= $this->db->count_all($sqlSTEPAPP);
		
		if($resSTEPAPP > 0)
		{
			$canApprove	= 1;
			$APPLIMIT_1	= 0;
			
			$sqlAPP	= "SELECT APPLIMIT_1, APP_STEP, MAX_STEP FROM tbl_docstepapp_det WHERE MENU_CODE = '$MenuCode'
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
			$APPROVE_AMOUNT 	= $ASEXP_AMOUNT;
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
    <?php echo $subTitleH; ?>
    <small><?php echo $subTitleD; ?></small>  </h1>
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
                        <input type="hidden" name="ASEXPJCODEX" id="ASEXPJCODEX" value="<?php echo $PRJCODE; ?>">
                        <input type="hidden" name="Pattern_Code" id="Pattern_Code" value="<?php echo $Pattern_Code; ?>">
                        <input type="hidden" name="Pattern_Length" id="Pattern_Length" value="<?php echo $Pattern_Length; ?>">
                        <input type="hidden" name="useYear" id="useYear" value="<?php echo $useYear; ?>">
                        <input type="hidden" name="useMonth" id="useMonth" value="<?php echo $useMonth; ?>">
                        <input type="hidden" name="useDate" id="useDate" value="<?php echo $useDate; ?>">
                        <input type="hidden" name="ASEXPDate" id="ASEXPDate" value="">
                    </td>
                    <td><a class="tombol-date" id="dateClass">Simpan</a></td>
                </tr>
            </table>
        </form>
        <!-- after get Supplier code -->
        <form name="frmsrch1" id="frmsrch1" action="" method=POST style="display:none">
            <input type="text" name="PRJCODE1" id="PRJCODE1" value="<?php echo $PRJCODE; ?>" />
            <input type="text" name="JOBCODE1" id="JOBCODE1" value="<?php echo $ASEXP_REFNO; ?>" />
            <input type="submit" class="button_css" name="submitSrch1" id="submitSrch1" value=" search " />
        </form>
        <!-- End -->
        <form class="form-horizontal" name="frm" method="post" action="<?php echo $form_action; ?>" enctype="multipart/form-data" onSubmit="return validateInData()">
            <input type="hidden" name="IS_LAST" id="IS_LAST" value="<?php echo $IS_LAST; ?>">
            <input type="hidden" name="APP_LEVEL" id="APP_LEVEL" value="<?php echo $APP_LEVEL; ?>">
            <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
            <input type="hidden" name="Patt_Number" id="Patt_Number" value="<?php echo $Patt_Number; ?>" />
            <input type="Hidden" name="rowCount" id="rowCount" value="0">
            	<div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label"><?php echo $ExpNo; ?></label>
                    <div class="col-sm-10">
                    	<input type="text" class="form-control" style="max-width:400px;text-align:left" name="ASEXP_NUM1" id="ASEXP_NUM1" size="30" value="<?php echo $DocNumber; ?>" disabled />
                    	<input type="hidden" class="form-control" style="max-width:350px;text-align:right" name="ASEXP_NUM" id="ASEXP_NUM" size="30" value="<?php echo $DocNumber; ?>" />
                    </div>
                </div>
            	<div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Code; ?></label>
                    <div class="col-sm-10">
                    	<input type="text" class="form-control" style="min-width:400px; max-width:250px; text-align:left" id="ASEXP_CODE" name="ASEXP_CODE" size="5" value="<?php echo $ASEXP_CODE; ?>" />
                    </div>
                </div>
            	<div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Date; ?></label>
                    <div class="col-sm-10">
                    	<div class="input-group date">
                        <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>&nbsp;</div><input type="text" name="ASEXP_DATE" class="form-control pull-left" id="datepicker" value="<?php echo $ASEXP_DATE; ?>" style="width:106px" onChange="getASEXP_NUM(this.value)"></div>
                    </div>
                </div>
                <script>
					function getASEXP_NUM(selDate)
					{
						document.getElementById('ASEXPDate').value = selDate;
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
									document.getElementById('ASEXP_NUM1').value = myarr[0];
									document.getElementById('ASEXP_CODE').value = myarr[1];
								}
							});
						});
					});
				</script>
            	<div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Project; ?></label>
                    <div class="col-sm-10">
                    	<select name="PRJCODE1" id="PRJCODE1" class="form-control" style="max-width:400px" onChange="chooseProject()" disabled>
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
            	<div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label"><?php echo $JobName; ?></label>
                    <div class="col-sm-10">
                        <select name="ASEXP_REFNO" id="ASEXP_REFNO" class="form-control select2" data-placeholder="&nbsp;&nbsp;&nbsp;<?php echo $JobName; ?>" style="max-width:400px;" onChange="selJOB(this.value)">
                        	<option value="">--- None ---</option>
							<?php
                                $Disabled_1	= 0;
                                $sqlJob_1	= "SELECT JOBCODEID, JOBDESC FROM tbl_joblist WHERE JOBLEV = 1 AND PRJCODE = '$PRJCODE'";
                                $resJob_1	= $this->db->query($sqlJob_1)->result();
                                foreach($resJob_1 as $row_1) :
                                    $JOBCODEID_1	= $row_1->JOBCODEID;
                                    $JOBDESC_1		= $row_1->JOBDESC;
                                    $space_level_1	= "";
                                    
                                    $sqlC_2		= "tbl_joblist WHERE JOBPARENT = '$JOBCODEID_1' AND ISHEADER = '1' AND PRJCODE = '$PRJCODE'";
                                    $resC_2 	= $this->db->count_all($sqlC_2);
                                    if($resC_2 > 0)
                                        $Disabled_1 = 1;
                                    ?>
                                    <option value="<?php echo "$JOBCODEID_1"; ?>" <?php if($JOBCODEID_1 == $ASEXP_REFNO) { ?> selected <?php } if($Disabled_1 == 1) {?> disabled <?php } ?> title="<?php echo $JOBDESC_1; ?>">
                                        <?php echo "$space_level_1 $JOBCODEID_1 : $JOBDESC_1"; ?>
                                    </option>
                                    <?php
                                    if($resC_2 > 0)
                                    {
                                        $Disabled_2	= 0;
                                        $sqlJob_2	= "SELECT JOBCODEID, JOBDESC FROM tbl_joblist WHERE JOBPARENT = '$JOBCODEID_1' AND ISHEADER = '1' AND PRJCODE = '$PRJCODE'";
                                        $resJob_2	= $this->db->query($sqlJob_2)->result();
                                        foreach($resJob_2 as $row_2) :
                                            $JOBCODEID_2	= $row_2->JOBCODEID;
                                            $JOBDESC_2		= $row_2->JOBDESC;
                                            $space_level_2	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                            
                                            $sqlC_3		= "tbl_joblist WHERE JOBPARENT = '$JOBCODEID_2' AND ISHEADER = '1' AND PRJCODE = '$PRJCODE'";
                                            $resC_3 	= $this->db->count_all($sqlC_3);
                                            if($resC_3 > 0)
                                                $Disabled_2 = 1;
                                            else
                                                $Disabled_2 = 0;
                                            ?>
                                            <option value="<?php echo "$JOBCODEID_2"; ?>" <?php if($JOBCODEID_2 == $ASEXP_REFNO) { ?> selected <?php } if($Disabled_2 == 1) {?> disabled <?php } ?> title="<?php echo $JOBDESC_2; ?>">
                                                <?php echo "$space_level_2 $JOBCODEID_2 : $JOBDESC_2"; ?>
                                            </option>
                                            <?php
                                            if($resC_3 > 0)
                                            {
                                                $sqlJob_3	= "SELECT JOBCODEID, JOBDESC FROM tbl_joblist WHERE JOBPARENT = '$JOBCODEID_2' AND ISHEADER = '1' AND PRJCODE = '$PRJCODE'";
                                                $resJob_3	= $this->db->query($sqlJob_3)->result();
                                                foreach($resJob_3 as $row_3) :
                                                    $JOBCODEID_3	= $row_3->JOBCODEID;
                                                    $JOBDESC_3		= $row_3->JOBDESC;
                                                    $space_level_3	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                    
                                                    $sqlC_4		= "tbl_joblist WHERE JOBPARENT = '$JOBCODEID_3' AND ISHEADER = '1' AND PRJCODE = '$PRJCODE'";
                                                    $resC_4 	= $this->db->count_all($sqlC_4);
                                                    if($resC_4 > 0)
                                                        $Disabled_3 = 1;
                                                    else
                                                        $Disabled_3 = 0;
                                                    ?>
                                                    <option value="<?php echo "$JOBCODEID_3"; ?>" <?php if($JOBCODEID_3 == $ASEXP_REFNO) { ?> selected <?php } if($Disabled_3 == 1) {?> disabled <?php } ?> title="<?php echo $JOBDESC_3; ?>">
                                                        <?php echo "$space_level_3 $JOBCODEID_3 : $JOBDESC_3"; ?>
                                                    </option>
                                                    <?php
                                                    if($resC_4 > 0)
                                                    {
                                                        $Disabled_4	= 0;
                                                        $sqlJob_4	= "SELECT JOBCODEID, JOBDESC FROM tbl_joblist WHERE JOBPARENT = '$JOBCODEID_3' AND ISHEADER = '1' AND PRJCODE = '$PRJCODE'";
                                                        $resJob_4	= $this->db->query($sqlJob_4)->result();
                                                        foreach($resJob_4 as $row_4) :
                                                            $JOBCODEID_4	= $row_4->JOBCODEID;
                                                            $JOBDESC_4		= $row_4->JOBDESC;
                                                            $space_level_4	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                            
                                                            $sqlC_5		= "tbl_joblist WHERE JOBPARENT = '$JOBCODEID_4' AND ISHEADER = '1' AND PRJCODE = '$PRJCODE'";
                                                            $resC_5 	= $this->db->count_all($sqlC_5);
                                                            if($resC_5 > 0)
                                                                $Disabled_4 = 1;
                                                            else
                                                                $Disabled_4 = 0;
                                                            ?>
                                                            <option value="<?php echo "$JOBCODEID_4"; ?>" <?php if($JOBCODEID_4 == $ASEXP_REFNO) { ?> selected <?php } if($Disabled_4 == 1) {?> disabled <?php } ?> title="<?php echo $JOBDESC_4; ?>">
                                                                <?php echo "$space_level_4 $JOBCODEID_4 : $JOBDESC_4"; ?>
                                                            </option>
                                                            <?php
                                                            if($resC_5 > 0)
                                                            {
                                                                $Disabled_5	= 0;
                                                                $sqlJob_5	= "SELECT JOBCODEID, JOBDESC FROM tbl_joblist WHERE JOBPARENT = '$JOBCODEID_4' AND ISHEADER = '1' AND PRJCODE = '$PRJCODE'";
                                                                $resJob_5	= $this->db->query($sqlJob_5)->result();
                                                                foreach($resJob_5 as $row_5) :
                                                                    $JOBCODEID_5	= $row_5->JOBCODEID;
                                                                    $JOBDESC_5		= $row_5->JOBDESC;
                                                                    $space_level_5	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    
                                                                    $sqlC_6		= "tbl_joblist WHERE JOBPARENT = '$JOBCODEID_5' AND ISHEADER = '1' AND PRJCODE = '$PRJCODE'";
                                                                    $resC_6 	= $this->db->count_all($sqlC_6);
                                                                    if($resC_6 > 0)
                                                                        $Disabled_5 = 1;
                                                                    else
                                                                        $Disabled_5 = 0;
                                                                    ?>
                                                                    <option value="<?php echo "$JOBCODEID_5"; ?>" <?php if($JOBCODEID_5 == $ASEXP_REFNO) { ?> selected <?php } if($Disabled_5 == 1) {?> disabled <?php } ?> title="<?php echo $JOBDESC_5; ?>">
                                                                        <?php echo "$space_level_5 $JOBCODEID_5 : $JOBDESC_5"; ?>
                                                                    </option>
                                                                    <?php
                                                                    if($resC_6 > 0)
                                                                    {
                                                                        $Disabled_6	= 0;
                                                                        $sqlJob_6	= "SELECT JOBCODEID, JOBDESC FROM tbl_joblist WHERE JOBPARENT = '$JOBCODEID_5' AND ISHEADER = '1' AND PRJCODE = '$PRJCODE'";
                                                                        $resJob_6	= $this->db->query($sqlJob_6)->result();
                                                                        foreach($resJob_6 as $row_6) :
                                                                            $JOBCODEID_6	= $row_6->JOBCODEID;
                                                                            $JOBDESC_6		= $row_6->JOBDESC;
                                                                            $space_level_6	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                            
                                                                            $sqlC_7		= "tbl_joblist WHERE JOBPARENT = '$JOBCODEID_6' AND ISHEADER = '1' AND PRJCODE = '$PRJCODE'";
                                                                            $resC_7 	= $this->db->count_all($sqlC_7);
                                                                            if($resC_7 > 0)
                                                                                $Disabled_6 = 1;
                                                                            else
                                                                                $Disabled_6 = 0;
                                                                            ?>
                                                                            <option value="<?php echo "$JOBCODEID_6"; ?>" <?php if($JOBCODEID_6 == $ASEXP_REFNO) { ?> selected <?php } if($Disabled_6 == 1) {?> disabled <?php } ?> title="<?php echo $JOBDESC_6; ?>">
                                                                                <?php echo "$space_level_6 $JOBCODEID_6 : $JOBDESC_6"; ?>
                                                                            </option>
                                                                            <?php
                                                                        endforeach;
                                                                    }
                                                                endforeach;
                                                            }
                                                        endforeach;
                                                    }
                                                endforeach;
                                            }
                                        endforeach;
                                    }
                                endforeach;
                            ?>
                        </select>
                    </div>
                </div>
				<script>
                    function selJOB(ASEXP_REFNO) 
                    {
                        document.getElementById("JOBCODE1").value = ASEXP_REFNO;
                        PRJCODE	= document.getElementById("PRJCODE").value
                        document.getElementById("PRJCODE1").value = PRJCODE;
                        document.frmsrch1.submitSrch1.click();
                    }
                </script>
            	<div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Notes; ?></label>
                    <div class="col-sm-10">
                    	<textarea name="ASEXP_NOTE" class="form-control" style="max-width:400px;" id="ASEXP_NOTE" cols="30"><?php echo $ASEXP_NOTE; ?></textarea>                        
                    </div>
                </div>
                <?php
				if($ASEXP_NOTE2 != '')
				{
					?>
                    <div class="form-group">
                        <label for="inputName" class="col-sm-2 control-label"><?php echo $ApproverNotes; ?></label>
                        <div class="col-sm-10">
                            <textarea name="ASEXP_NOTE2" class="form-control" style="max-width:400px;" id="ASEXP_NOTE2" cols="30" disabled><?php echo $ASEXP_NOTE2; ?></textarea>                        
                        </div>
                    </div>
                	<?php
				}
				?>
            	<div id="revMemo" class="form-group" <?php if($ASEXP_REVMEMO == '') { ?> style="display:none" <?php } ?>>
                    <label for="inputName" class="col-sm-2 control-label"><?php echo $reviseNotes; ?></label>
                    <div class="col-sm-10">
                    	<textarea name="ASEXP_REVMEMO" class="form-control" style="max-width:400px;" id="ASEXP_REVMEMO" cols="30"><?php echo $ASEXP_REVMEMO; ?></textarea>                        
                    </div>
                </div>
            	<div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Status; ?></label>
                    <div class="col-sm-10">
                    	<input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $ASEXP_STAT; ?>">
						<?php
							// START : FOR ALL APPROVAL FUNCTION
								if($ASEXP_STAT == 3)
								{
									?>
										<select name="ASEXP_STAT" id="ASEXP_STAT" class="form-control" style="max-width:120px" onChange="selStat(this.value)" <?php if($disButton == 1) { ?> disabled <?php } ?> >
											<option value="0"> -- </option>
											<option value="3"<?php if($ASEXP_STAT == 3) { ?> selected <?php } ?>  disabled>Approved</option>
											<option value="4"<?php if($ASEXP_STAT == 4) { ?> selected <?php } ?> disabled>Revised</option>
											<option value="5"<?php if($ASEXP_STAT == 5) { ?> selected <?php } ?> disabled>Rejected</option>
											<option value="6"<?php if($ASEXP_STAT == 6) { ?> selected <?php } ?> disabled>Closed</option>
											<option value="7"<?php if($ASEXP_STAT == 7) { ?> selected <?php } ?> style="display:none">Awaiting</option>
											<option value="9"<?php if($ASEXP_STAT == 9) { ?> selected <?php } ?>>Void</option>
										</select>
									<?php
								}
								else
								{
									if($ISAPPROVE == 1)
									{
										if($disableAll == 0)
										{
											if($canApprove == 1)
											{
												$disButton	= 0;
												$sqlCAPPHE	= "tbl_approve_hist WHERE AH_CODE = '$ASEXP_NUM' AND AH_APPROVER = '$DefEmp_ID'";
												$resCAPPHE	= $this->db->count_all($sqlCAPPHE);
												if($resCAPPHE > 0)
													$disButton	= 1;
												?>
													<select name="ASEXP_STAT" id="ASEXP_STAT" class="form-control" style="max-width:120px" onChange="selStat(this.value)" <?php if($disButton == 1) { ?> disabled <?php } ?> >
														<option value="0"> -- </option>
														<option value="3"<?php if($ASEXP_STAT == 3) { ?> selected <?php } ?>>Approved</option>
														<option value="4"<?php if($ASEXP_STAT == 4) { ?> selected <?php } ?>>Revised</option>
														<option value="5"<?php if($ASEXP_STAT == 5) { ?> selected <?php } ?>>Rejected</option>
														<option value="6"<?php if($ASEXP_STAT == 6) { ?> selected <?php } ?>>Closed</option>
														<option value="7"<?php if($ASEXP_STAT == 7) { ?> selected <?php } ?> style="display:none">Awaiting</option>
													</select>
												<?php
											}
											else
											{
												?>
													<a href="" class="btn btn-<?php echo $statcoloer; ?> btn-xs" title="ssss">
														<?php echo $descApp; ?>
													</a>
												<?php
											}
										}
										else
										{
											?>
												<a href="" class="btn btn-danger btn-xs">
													Step approval not set;
												</a>
											<?php
										}
									}
									elseif($ISCREATE == 1)
									{
										?>
											<select name="ASEXP_STAT" id="ASEXP_STAT" class="form-control" style="max-width:100px" onChange="selStat(this.value)">
												<option value="1"<?php if($ASEXP_STAT == 1) { ?> selected <?php } ?>>New</option>
												<option value="2"<?php if($ASEXP_STAT == 2) { ?> selected <?php } ?>>Confirm</option>
	                                            <option value="3"<?php if($ASEXP_STAT == 3) { ?> selected <?php } ?> disabled>Approved</option>
	                                            <option value="4"<?php if($ASEXP_STAT == 4) { ?> selected <?php } ?> disabled>Revised</option>
	                                            <option value="5"<?php if($ASEXP_STAT == 5) { ?> selected <?php } ?> disabled>Rejected</option>
	                                            <option value="6"<?php if($ASEXP_STAT == 6) { ?> selected <?php } ?> disabled>Closed</option>
	                                            <option value="7"<?php if($ASEXP_STAT == 7) { ?> selected <?php } ?> disabled>Awaiting</option>
											</select>
										<?php
									}									
								}
							// END : FOR ALL APPROVAL FUNCTION
							
							$theProjCode 	= "$PRJCODE~$ASEXP_REFNO";
							$url_AddAset	= site_url('c_asset/c_1s3txpen/popupallast/?id='.$this->url_encryption_helper->encode_url($theProjCode));
							$url_AddItem	= site_url('c_asset/c_1s3txpen/popupallitem/?id='.$this->url_encryption_helper->encode_url($theProjCode));
						?>
                        <input type="hidden" name="ASEXP_AMOUNT" id="ASEXP_AMOUNT" value="<?php echo $ASEXP_AMOUNT; ?>">
                    </div>
                </div>
                <script>
                	function selStat(sTat)
                	{
                		STAT_BEFORE = document.getElementById('STAT_BEFORE').value;
                		if (sTat == 9)
                		{
                			document.getElementById('revMemo').style.display = '';
                			document.getElementById('btnUpd').style.display = '';
                		}
                		else
                		{
                			document.getElementById('revMemo').style.display = 'none';
                			document.getElementById('btnUpd').style.display = 'none';                			
                		}
                	}
                </script>
                <?php if($ASEXP_STAT == 1 || $ASEXP_STAT == 4) { ?>
                <div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label">&nbsp;</label>
                    <div class="col-sm-10">
                        <script>
                            var url1 = "<?php echo $url_AddAset;?>";
                            function selectAset()
                            {
								ASEXP_REFNO	= document.getElementById('ASEXP_REFNO').value;
								if(ASEXP_REFNO == '')
								{
									alert('Silahkan pilih nama pekerjaan');
									document.getElementById('ASEXP_REFNO').focus();
									return false;
								}
								
                                title = 'Select Item';
                                w = 1000;
                                h = 550;
                                //window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
                                var left = (screen.width/2)-(w/2);
                                var top = (screen.height/2)-(h/2);
                                return window.open(url1, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
                            }
                        </script>
                        <button class="btn btn-success" type="button" onClick="selectAset();">
                        <i class="glyphicon glyphicon-th-list"></i>&nbsp;&nbsp;<?php echo $SelAsset; ?>
                        </button>
                        </div>
                </div>
                <?php } ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-primary">
                        <br>
                        <table width="100%" border="1" id="tbl">
                        	<tr style="background:#CCCCCC">
                              <th width="2%" height="25" style="text-align:left">&nbsp;</th>
                              <th width="15%" style="text-align:center" nowrap><?php echo $AssetCode ?> </th>
                              <th width="35%" style="text-align:center"><?php echo $AssetDescription ?> </th>
                              <th width="9%"  style="text-align:center">Brand </th>
                              <th width="8%"  style="text-align:center"><?php echo $AssetCapacity ?> </th>
                              <th width="18%"  style="text-align:center">Ref. Item</th>
                              <th width="13%"  style="text-align:center"><?php echo "$Budget<br>$PerMonth"; ?></th>
                            </tr>
                            <?php					
                            if($task == 'edit')
                            {
                                $sqlDET	= "SELECT A.*, B.AS_NAME, B.AS_DESC, B.AS_BRAND, B.AS_CAPACITY, B.AS_LINKACC, B.AS_LADEPREC, B.AG_CODE,
												B.AST_CODE
											FROM tbl_asset_expd A
												INNER JOIN tbl_asset_list B ON A.AS_CODE = B.AS_CODE
											WHERE ASEXP_NUM = '$ASEXP_NUM' 
												AND A.PRJCODE = '$PRJCODE'";
                                $result = $this->db->query($sqlDET)->result();
                                $i		= 0;
                                $j		= 0;
								
								foreach($result as $row) :
									$currentRow  	= ++$i;
									$ASEXP_NUM 		= $row->ASEXP_NUM;
									$ASEXP_CODE 	= $row->ASEXP_CODE;
									//$PRJCODE 		= $row->PRJCODE;
									$ASEXP_DATE 	= $row->ASEXP_DATE;
									
									$ITM_CODE 		= $row->ITM_CODE;
									$AS_CODE 		= $row->AS_CODE;
									$AS_NAME 		= $row->AS_NAME;
									$AS_DESC 		= $row->AS_DESC;
									$AS_NAMED		= "$AS_NAME - $AS_DESC";
									$AS_BRAND 		= $row->AS_BRAND;
									$AS_CAPACITY	= $row->AS_CAPACITY;
									
									$ASEXP_AMOUNT	= $row->ASEXP_AMOUNT;
									$ASEXP_DESC 	= $row->ASEXP_DESC;
									$AS_LINKACC 	= $row->AS_LINKACC;
									$AS_LADEPREC 	= $row->AS_LADEPREC;
									$AG_CODE 		= $row->AG_CODE;
									$AST_CODE 		= $row->AST_CODE;
									
									$ITM_NAME		= '';
									$PRJCODE_HO		= '';
									$ITM_GROUP		= '';
									$ITM_DESC		= '';
									$ISMTRL			= 0;
									$ISRENT			= 0;
									$ISPART			= 0;
									$ISFUEL			= 0;
									$ISLUBRIC		= 0;
									$ISFASTM		= 0;
									$sqlITM			= "SELECT ITM_NAME, PRJCODE, PRJCODE_HO, ITM_GROUP, ITM_DESC, ISMTRL, ISRENT, ISPART, ISFUEL, 
															ISLUBRIC, ISFASTM
														FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
									$resITM			= $this->db->query($sqlITM)->result();
									foreach($resITM as $rowITM) :
										$ITM_NAME	= $rowITM->ITM_NAME;
										$PRJCODE	= $rowITM->PRJCODE;
										$PRJCODE_HO	= $rowITM->PRJCODE_HO;
										$ITM_GROUP	= $rowITM->ITM_GROUP;
										$ITM_DESC	= $rowITM->ITM_DESC;
										$ISMTRL		= $rowITM->ISMTRL;
										$ISRENT		= $rowITM->ISRENT;
										$ISPART		= $rowITM->ISPART;
										$ISFUEL		= $rowITM->ISFUEL;
										$ISLUBRIC	= $rowITM->ISLUBRIC;
										$ISFASTM	= $rowITM->ISFASTM;
									endforeach;
															
									if($ISMTRL == 1)
										$ITM_TYPE	= 1;
									elseif($ISRENT == 1)
										$ITM_TYPE	= 2;
									elseif($ISPART == 1)
										$ITM_TYPE	= 3;
									elseif($ISFUEL == 1)
										$ITM_TYPE	= 4;
									elseif($ISLUBRIC == 1)
										$ITM_TYPE	= 5;
									elseif($ISFASTM == 1)
										$ITM_TYPE	= 6;
									else
										$ITM_TYPE	= 1;
						
								/*	if ($j==1) {
										echo "<tr class=zebra1>";
										$j++;
									} else {
										echo "<tr class=zebra2>";
										$j--;
									}*/
									?> 
                                    <tr id="tr_<?php echo $currentRow; ?>">
									<td width="2%" height="25" style="text-align:left">
									  	<?php
											if($ASEXP_STAT == 1)
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
								    	<input type="hidden" id="chk" name="chk" value="<?php echo $currentRow; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">
                                 	</td>
								 	<td width="15%" style="text-align:left" nowrap>
									  	<?php echo $AS_CODE; ?>                                      
                                      	<input type="hidden" id="data<?php echo $currentRow; ?>ASEXP_NUM" name="data[<?php echo $currentRow; ?>][ASEXP_NUM]" value="<?php echo $ASEXP_NUM; ?>" class="form-control" style="max-width:300px;">
                                      	<input type="hidden" id="data<?php echo $currentRow; ?>ASEXP_CODE" name="data[<?php echo $currentRow; ?>][ASEXP_CODE]" value="<?php echo $ASEXP_CODE; ?>" class="form-control" style="max-width:300px;">
                                      	<input type="hidden" id="data<?php echo $currentRow; ?>AS_CODE" name="data[<?php echo $currentRow; ?>][AS_CODE]" value="<?php echo $AS_CODE; ?>" class="form-control" style="max-width:300px;">
                                        <input type="hidden" id="data<?php echo $currentRow; ?>JOBCODEID" name="data[<?php echo $currentRow; ?>][JOBCODEID]" value="<?php echo $JOBCODEID; ?>" class="form-control" style="max-width:300px;">
                                        <input type="hidden" id="data<?php echo $currentRow; ?>AS_LINKACC" name="data[<?php echo $currentRow; ?>][AS_LINKACC]" value="<?php echo $AS_LINKACC; ?>" class="form-control" style="max-width:300px;">
                                        <input type="hidden" id="data<?php echo $currentRow; ?>AS_LADEPREC" name="data[<?php echo $currentRow; ?>][AS_LADEPREC]" value="<?php echo $AS_LADEPREC; ?>" class="form-control" style="max-width:300px;">
                                        <input type="hidden" id="data<?php echo $currentRow; ?>AG_CODE" name="data[<?php echo $currentRow; ?>][AG_CODE]" value="<?php echo $AG_CODE; ?>" class="form-control" style="max-width:300px;">
                                        <input type="hidden" id="data<?php echo $currentRow; ?>AST_CODE" name="data[<?php echo $currentRow; ?>][AST_CODE]" value="<?php echo $AST_CODE; ?>" class="form-control" style="max-width:300px;">
                                  	</td>
								  	<td width="35%" style="text-align:left">
										<?php echo $AS_NAMED; ?>
                                 	</td>
									<td width="9%">
										<?php echo $AS_BRAND; ?>
                                  	</td>
								  	<td width="8%" style="text-align:center">
                                    	<?php echo $AS_CAPACITY; ?>
                                    </td>
								  	<td width="18%" style="text-align:left">
                                    	<?php 
											if($ASEXP_STAT != 1 || $ASEXP_STAT != 4)
											{
												echo $ITM_CODE;
												?>
                                                	<input type="hidden" name="data[<?php echo $currentRow; ?>][ITM_CODE]" id="data<?php echo $currentRow; ?>ITM_CODE" value="<?php echo $ITM_CODE; ?>" class="form-control" onClick="selectitem(<?php echo $currentRow; ?>);">
                                                <?php
											}
											else
											{
												?>
                                                	<input type="text" name="data[<?php echo $currentRow; ?>][ITM_CODE]" id="data<?php echo $currentRow; ?>ITM_CODE" value="<?php echo $ITM_CODE; ?>" class="form-control" onClick="selectitem(<?php echo $currentRow; ?>);">
                                                <?php
											}
										?>
                                    	<input type="hidden" name="data[<?php echo $currentRow; ?>][ITM_GROUP]" id="data<?php echo $currentRow; ?>ITM_GROUP" value="<?php echo $ITM_GROUP; ?>" class="form-control" onClick="selectitem(<?php echo $currentRow; ?>);">
                                    	<input type="hidden" name="data[<?php echo $currentRow; ?>][ITM_TYPE]" id="data<?php echo $currentRow; ?>ITM_TYPE" value="<?php echo $ITM_TYPE; ?>" class="form-control" onClick="selectitem(<?php echo $currentRow; ?>);">
                                    </td>
								 	<td width="13%" style="text-align:right">
                                    	<input type="text" name="ASEXP_AMOUNT<?php echo $currentRow; ?>" id="ASEXP_AMOUNT<?php echo $currentRow; ?>" value="<?php echo number_format($ASEXP_AMOUNT, 2); ?>" class="form-control" style="max-width:150px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,<?php echo $currentRow; ?>);" >
                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][ASEXP_AMOUNT]" id="data<?php echo $currentRow; ?>ASEXP_AMOUNT" value="<?php echo $ASEXP_AMOUNT; ?>" class="form-control" style="max-width:300px;" >
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
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <?php
							if($ISAPPROVE == 1)
								$ISCREATE	= 1;
								
							if($task=='add')
							{
								if($ISCREATE == 1 && $ASEXP_STAT == 1)
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
								if($ISCREATE == 1 && $ASEXP_STAT == 1)
								{
									?>
										<button class="btn btn-primary">
										<i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Update; ?>
										</button>&nbsp;
									<?php
								}
								//elseif($ISAPPROVE == 1 && $ASEXP_STAT == 2 && $canApprove == 1)
								elseif(($ASEXP_STAT == 2 || $ASEXP_STAT == 7) && $ISAPPROVE == 1 && $canApprove == 1)
								{
									?>
										<button class="btn btn-primary">
										<i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Update; ?>
										</button>&nbsp;
									<?php
								}
								elseif($ASEXP_STAT == 3 && $ISCREATE == 1)
								{
									?>
										<button class="btn btn-primary" id="btnUpd" style="display: none;">
										<i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Update; ?>
										</button>&nbsp;
									<?php
								}
							}
							$backURL	= site_url('c_asset/c_1s3txpen/gall1s3txpen/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
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
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/timepicker/bootstrap-timepicker.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/fastclick/fastclick.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE/dist/js/demo.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/colorpicker/bootstrap-colorpicker.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datepicker/bootstrap-datepicker.js'; ?>"></script>

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
<!-- bootstrap color picker -->
<!-- bootstrap time picker -->
<!-- SlimScroll 1.3.0 -->
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
	
	function add_asset(strItem) 
	{
		arrItem = strItem.split('|');		
		var objTable, objTR, objTD, intIndex, arrItem;
		var ASEXP_NUMx 	= "<?php echo $DocNumber; ?>";		
		var ASEXP_CODEx = "<?php echo $ASEXP_CODE; ?>";
		var JOBCODEID 	= "<?php echo $ASEXP_REFNO; ?>";
		
		ilvl = arrItem[1];
		
		/*validateDouble(arrItem[0],arrItem[1])
		if(validateDouble(arrItem[0],arrItem[1]))
		{
			alert("Double Item for " + arrItem[0]);
			return;
		}*/
		
		JOBCODEDET 		= arrItem[0];
		//JOBCODEID 	= arrItem[1];
		JOBCODE 		= arrItem[2];
		PRJCODE 		= arrItem[3];
		AS_CODE 		= arrItem[4];
		AS_CODE_M 		= arrItem[5];
		AS_NAME			= arrItem[6];
		AS_DESC 		= arrItem[7];
		AS_NAMED		= ''+AS_NAME+' - '+AS_DESC+'';
		
		AS_TYPECODE		= arrItem[8];
		AS_BRAND 		= arrItem[9];
		AS_CAPACITY		= arrItem[10];
		ITM_CODE 		= '';
		ASEXP_AMOUNT 	= arrItem[11];
		
		objTable 		= document.getElementById('tbl');
		intTable 		= objTable.rows.length;
		//alert('intTable = '+intTable)
		//intIndex = parseInt(document.frm.rowCount.value) + 1;
		intIndex = parseInt(objTable.rows.length) - 1;
		//intIndex = intTable;
		document.frm.rowCount.value = intIndex;
		
		objTR = objTable.insertRow(intTable);
		objTR.id = 'tr_' + intIndex;
		
		// Checkbox
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = "center";
		objTD.noWrap = true;
		objTD.innerHTML = '<a href="#" onClick="deleteRow('+intIndex+')" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a><input type="hidden" id="chk" name="chk" value="'+intIndex+'" width="10" size="15" readonly class="form-control" style="max-width:300px;">';
		
		// Asset Code
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.innerHTML = ''+AS_CODE+'<input type="hidden" id="data'+intIndex+'ASEXP_NUM" name="data['+intIndex+'][ASEXP_NUM]" value="'+ASEXP_NUMx+'" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'ASEXP_CODE" name="data['+intIndex+'][ASEXP_CODE]" value="'+ASEXP_CODEx+'" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'AS_CODE" name="data['+intIndex+'][AS_CODE]" value="'+AS_CODE+'" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'JOBCODEID" name="data['+intIndex+'][JOBCODEID]" value="'+JOBCODEID+'" class="form-control" style="max-width:300px;">';
		
		// Asset Name
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.noWrap = true;
		objTD.innerHTML = ''+AS_NAMED+'';
		
		// Brand
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'left';
		objTD.innerHTML = ''+AS_BRAND+'';
		
		// Capacity
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = ''+AS_CAPACITY+'';
		
		// Item Ref.
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" name="data['+intIndex+'][ITM_CODE]" id="data'+intIndex+'ITM_CODE" value="'+ITM_CODE+'" class="form-control" onClick="selectitem('+intIndex+');">';
		
		// Expense Per Month
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" name="ASEXP_AMOUNT'+intIndex+'" id="ASEXP_AMOUNT'+intIndex+'" value="'+ASEXP_AMOUNT+'" class="form-control" style="max-width:150px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,'+intIndex+');" ><input type="hidden" name="data['+intIndex+'][ASEXP_AMOUNT]" id="data'+intIndex+'ASEXP_AMOUNT" value="'+ASEXP_AMOUNT+'" class="form-control" >';
		
		var decFormat												= document.getElementById('decFormat').value;
		document.getElementById('ASEXP_AMOUNT'+intIndex).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ASEXP_AMOUNT)),decFormat));
		document.getElementById('data'+intIndex+'ASEXP_AMOUNT').value = parseFloat(Math.abs(ASEXP_AMOUNT));
		totRow	= parseFloat(intIndex+1);
		document.getElementById('totalrow').value = parseFloat(totRow);
	}
	
	function add_item(strItem) 
	{
		arrItem = strItem.split('|');
		
		THEROW 		= arrItem[0];
		ITM_CODE	= arrItem[1];
		
		document.getElementById('data'+THEROW+'ITM_CODE').value 	= ITM_CODE;
	}
	
	var url = "<?php echo $url_AddItem;?>";
	function selectitem(theRow)
	{
		title = 'Select Item';
		w = 1000;
		h = 550;
		//window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		return window.open(url+'&theRow='+theRow, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
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
	
	function getConvertion(thisVal1, row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		
		ASEXP_AMOUNT			= parseFloat(eval(thisVal1).value.split(",").join(""));
		
		document.getElementById('data'+row+'ASEXP_AMOUNT').value 	= ASEXP_AMOUNT;
		document.getElementById('ASEXP_AMOUNT'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ASEXP_AMOUNT)),decFormat))
		//document.getElementById('ASEXP_VOLM2'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(reqQty1x)),decFormat))
	}
	
	function RoundNDecimal(X, N)
	{
		var T, S=new String(Math.round(X*Number("1e"+N)))
		while (S.length<=N) S='0'+S
		return S.substr(0, T=(S.length-N)) + '.' + S.substr(T, N)
	}
	
	function validateInData(value)
	{
		var totrow 		= document.getElementById('totalrow').value;
		var ASEXP_STAT 	= document.getElementById('ASEXP_STAT').value;
		if(ASEXP_STAT == 0)
		{
			alert('<?php echo $alert3; ?>');
			document.getElementById('ASEXP_STAT').focus();
			return false;
		}
		
		for(i=1;i<=totrow;i++)
		{
			var ASEXP_AMOUNT = parseFloat(document.getElementById('ASEXP_AMOUNT'+i).value);
			if(ASEXP_AMOUNT == 0)
			{
				alert('<?php echo $alert1; ?>');
				document.getElementById('ASEXP_AMOUNT'+i).value = '0';
				document.getElementById('ASEXP_AMOUNT'+i).focus();
				return false;
			}
		}
		
		if(totrow == 0)
		{
			alert('<?php echo $alert2; ?>');
			return false;		
		}
		else
		{
			//document.frm.submit();
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