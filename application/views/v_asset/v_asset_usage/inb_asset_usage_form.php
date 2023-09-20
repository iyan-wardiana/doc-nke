<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 7 April 2017
 * File Name	= asset_usage_form.php
 * Location		= -
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$FlagUSER 	= $this->session->userdata['FlagUSER'];
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

$ISREAD 	= $this->session->userdata['ISREAD'];
$ISCREATE 	= $this->session->userdata['ISCREATE'];
$ISAPPROVE 	= $this->session->userdata['ISAPPROVE'];

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
$LangID 		= $this->session->userdata['LangID'];

$currentRow 	= 0;

$isSetDocNo 	= 1;
$AU_CODE 		= $default['AU_CODE'];
$AUR_CODE 		= $default['AUR_CODE'];
$AU_JOBCODE 	= $default['AU_JOBCODE'];
$AU_AS_CODE 	= $default['AU_AS_CODE'];
$PRJCODE 		= $default['PRJCODE'];
$PRJCODE 		= $default['PRJCODE'];
$AU_DATE 		= $default['AU_DATE'];
$AU_DESC 		= $default['AU_DESC'];
$AU_STARTD		= $default['AU_STARTD'];
$AU_STARTD		= date('m/d/Y',strtotime($AU_STARTD));
$AU_ENDD 		= $default['AU_ENDD'];
$AU_ENDD		= date('m/d/Y',strtotime($AU_ENDD));
$AU_STARTT		= $default['AU_STARTT'];
$AU_STARTT		= date('H:i',strtotime($AU_STARTT));
$AU_ENDT 		= $default['AU_ENDT'];
$AU_ENDT		= date('H:i',strtotime($AU_ENDT));
$AP_QTYOPR 		= $default['AP_QTYOPR'];
$AP_QTYUNIT		= $default['AP_QTYUNIT'];
$AU_STAT 		= $default['AU_STAT'];	
$AU_PROCD1		= $default['AU_PROCD'];
if($AU_PROCD1 == '')
	$AU_PROCD1	= date("m/d/Y");
$AU_PROCD		= date('m/d/Y',strtotime($AU_PROCD1));
$AU_PROCT1		= $default['AU_PROCT'];
$AU_PROCT		= $default['AU_PROCT'];
if($AU_PROCT == '')
	$AU_PROCT	= "00:00";
else
	$AU_PROCT		= date('H:i',strtotime($AU_PROCT));
$Patt_Number	= $default['Patt_Number'];

$AU_DATEX 	= date('Y-m-d',strtotime($AU_DATE));
$AU_STARTDX = date('m/d/Y',strtotime($AU_STARTD));
$AU_ENDDX 	= date('m/d/Y',strtotime($AU_ENDD));
if(isset($_POST['AU_STARTDX']))
{
	$PRJCODE		= $_POST['PRJCODEX'];
	$AU_DATEX		= $_POST['AU_DATEX'];
		$AU_DATEX		= date('Y-m-d',strtotime($AU_DATEX));
	$AU_JOBCODE		= $_POST['AU_JOBCODEX'];
	$AU_AS_CODE		= $_POST['AU_AS_CODEX'];
	$AU_STARTDX 	= $_POST['AU_STARTDX'];
		$AU_STARTDX		= date('Y-m-d',strtotime($AU_STARTDX));
	$AU_ENDDX 		= $_POST['AU_ENDDX'];
		$AU_ENDDX 		= date('Y-m-d',strtotime($AU_ENDDX));
	$AU_STARTT 		= $_POST['AU_STARTTX'];
	$AU_ENDT 		= $_POST['AU_ENDTX'];
	$AU_DESC 		= $_POST['AU_DESCX'];
}
$AU_DATE	= date("m/d/Y",strtotime($AU_DATEX));
$AU_STARTD	= date("m/d/Y",strtotime($AU_STARTDX));
$AU_ENDD	= date("m/d/Y",strtotime($AU_ENDDX));
$varURL		= "$PRJCODE|$AU_STARTD|$AU_ENDD";	

$AU_NEEDITM	= $default['AU_NEEDITM'];
$AU_PROCS 	= $default['AU_PROCS'];
$AU_REFNO	= $default['AU_REFNO'];

if($AU_PROCS == 1)
{
	$IS_PROCS 	= 1;
}
else
{
	$IS_PROCS 	= 0;
}

$sqlUnitC	= "tbl_unittype";
$viewUnitC 	= $this->db->count_all($sqlUnitC);

$sqlUnit	= "SELECT Unit_Type_Code, UMCODE, Unit_Type_Name FROM tbl_unittype ORDER BY UMCODE ASC";
$viewUnit 	= $this->db->query($sqlUnit)->result();
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
    <link href="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/iCheck/flat/blue.css') ?>" rel="stylesheet" type="text/css" />
    <!-- Morris chart -->
    <link href="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/morris/morris.css') ?>" rel="stylesheet" type="text/css" />
    <!-- jvectormap -->
    <link href="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/jvectormap/jquery-jvectormap-1.2.2.css') ?>" rel="stylesheet" type="text/css" />
    <!-- Date Picker -->
    <link href="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/datepicker/datepicker3.css') ?>" rel="stylesheet" type="text/css" />
    <!-- Daterange picker -->
    <link href="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/daterangepicker/daterangepicker-bs3.css') ?>" rel="stylesheet" type="text/css" />
    <!-- bootstrap wysihtml5 - text editor -->
    <link href="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') ?>" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="<?php echo base_url('assets/css/ilmudetil.css') ?>">
    <script src="<?php echo base_url('assets/js/jquery-1.10.1.min.js') ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('assets/js/highcharts.js') ?>" type="text/javascript"></script>

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
		if($TranslCode == 'AddItem')$AddItem = $LangTransl;
		if($TranslCode == 'Save')$Save = $LangTransl;
		if($TranslCode == 'Update')$Update = $LangTransl;
		if($TranslCode == 'Back')$Back = $LangTransl;
		if($TranslCode == 'Code')$Code = $LangTransl;
		if($TranslCode == 'Date')$Date = $LangTransl;
		if($TranslCode == 'JobCode')$JobCode = $LangTransl;
		if($TranslCode == 'StartDate')$StartDate = $LangTransl;
		if($TranslCode == 'Time')$Time = $LangTransl;
		if($TranslCode == 'EndDate')$EndDate = $LangTransl;
		if($TranslCode == 'AssetName')$AssetName = $LangTransl;

		if($TranslCode == 'Description')$Description = $LangTransl;
		if($TranslCode == 'Production')$Production = $LangTransl;
		if($TranslCode == 'Quantity')$Quantity = $LangTransl;
		if($TranslCode == 'Unit')$Unit = $LangTransl;
		if($TranslCode == 'Status')$Status = $LangTransl;
		if($TranslCode == 'Search')$Search = $LangTransl;
		if($TranslCode == 'JobName')$JobName = $LangTransl;	
		
		if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
		if($TranslCode == 'ItemName')$ItemName = $LangTransl;
		if($TranslCode == 'Usage')$Usage = $LangTransl;
		if($TranslCode == 'Quantity')$Quantity = $LangTransl;
		if($TranslCode == 'Unit')$Unit = $LangTransl;
		if($TranslCode == 'Price')$Price = $LangTransl;
		if($TranslCode == 'Total')$Total = $LangTransl;
		if($TranslCode == 'Remarks')$Remarks = $LangTransl;
		if($TranslCode == 'RealQty')$RealQty = $LangTransl;
		if($TranslCode == 'Others')$Others = $LangTransl;
		if($TranslCode == 'ItemNeed')$ItemNeed = $LangTransl;
	endforeach;
	
	if($LangID == 'IND')
	{
		$subTitleH	= "Persetujuan";
		$subTitleD	= "permintaan pembelian";
		
		$alert1		= "Silahkan pilih status persetujuan.";
		$alert2		= "Silahkan tulis catatan persetujuan dokumen.";
		$alert3		= "Dokumenn ini memerlukan persetujuan khusus.";
	}
	else
	{
		$subTitleH	= "Approval";
		$subTitleD	= "purchase request";
		
		$alert1		= "Please select an approval status.";
		$alert2		= "Please write the document approval note.";
		$alert3		= "This document needs a special approval.";
	}
	
	$isLoadDone_1	= 1;
	
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
			$sqlC_App	= "tbl_approve_hist WHERE AH_CODE = '$AU_CODE'";
			$resC_App 	= $this->db->count_all($sqlC_App);
			//$appReady	= $APP_STEP;
			//if($resC_App == 0)
			//echo "APP_STEP = $APP_STEP = $resC_App = $MAX_STEP";
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
			$APPROVE_AMOUNT 	= 10000000000;
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

$PRJNAME		= '';
$sqlPRJ 		= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE  = '$PRJCODE'";
$resultPRJ 		= $this->db->query($sqlPRJ)->result();
foreach($resultPRJ as $rowPRJ) :
	$PRJNAME 	= $rowPRJ->PRJNAME;
endforeach;
?>

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>
    <?php echo $h1_title; ?>
    <small><?php echo $PRJNAME; ?></small>  </h1>
  <?php /*?><ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#">Tables</a></li>
    <li class="active">Data tables</li>
  </ol><?php */?>
</section>
<!-- Main content -->
<section class="content">	
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border" style="display:none">               
              		<div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body chart-responsive">
                	<form name="frmsrch1" id="frmsrch1" action="" method=POST style="display:none" >
                        <input type="text" name="PRJCODEX" id="PRJCODEX" class="textbox" value="<?php echo $PRJCODE; ?>" />
                        <input type="text" name="AU_DATEX" id="AU_DATEX" class="textbox" value="<?php echo $AU_DATE; ?>" />
                        <input type="text" name="AU_JOBCODEX" id="AU_JOBCODEX" class="textbox" value="<?php echo $AU_JOBCODE; ?>" />
                        <input type="text" name="AU_AS_CODEX" id="AU_AS_CODEX" class="textbox" value="<?php echo $AU_AS_CODE; ?>" />
                        <input type="text" name="AU_STARTDX" id="AU_STARTDX" class="textbox" value="<?php echo $AU_STARTD; ?>" />
                        <input type="text" name="AU_ENDDX" id="AU_ENDDX" class="textbox" value="<?php echo $AU_ENDD; ?>" />
                        <input type="text" name="AU_STARTTX" id="AU_STARTTX" class="textbox" value="<?php echo $AU_STARTT; ?>" />
                        <input type="text" name="AU_ENDTX" id="AU_ENDTX" class="textbox" value="<?php echo $AU_ENDT; ?>" />
                        <input type="text" name="AU_DESCX" id="AU_DESCX" class="textbox" value="<?php echo $AU_DESC; ?>" />
                        <input type="submit" class="button_css" name="submitSrch1" id="submitSrch1" value=" search " />
                    </form>
                	<form name="frmsrch" id="frmsrch" action="" method=POST style="display:none">
                        <input type="hidden" name="AUR_CODEX" id="AUR_CODEX" class="textbox" value="<?php echo $AUR_CODE; ?>" />
                        <input type="submit" class="button_css" name="submitSrch" id="submitSrch" value=" search " />
                    </form>
                    <form class="form-horizontal" name="frm" id="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return checkInp()">
                        <input type="hidden" name="IS_LAST" id="IS_LAST" value="<?php echo $IS_LAST; ?>">
                        <input type="hidden" name="APP_LEVEL" id="APP_LEVEL" value="<?php echo $APP_LEVEL; ?>">
                    	<input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
                    	<input type="hidden" name="PRJCODE" id="PRJCODE" value="<?php echo $PRJCODE; ?>" />
                    	<input type="hidden" name="IS_PROCS" id="IS_PROCS" value="<?php echo $IS_PROCS; ?>" />
           				<input type="hidden" name="rowCount" id="rowCount" value="0">
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
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Code ?> </label>
                          	<div class="col-sm-10">
                                <input type="hidden" name="Patt_Number" id="Patt_Number" value="<?php echo $Patt_Number; ?>">
                                <input type="hidden" name="AU_CODE" id="AU_CODE" value="<?php echo $AU_CODE; ?>" >
                                <input type="text" class="form-control" name="AU_CODE1" id="AU_CODE1" style="max-width:200px" value="<?php echo $AU_CODE; ?>" disabled>
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Date ?> </label>
                          	<div class="col-sm-10">
                                <div class="input-group date">
                                <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>&nbsp;</div>
                                <input type="hidden" class="form-control" name="AU_DATE" id="AU_DATE" style="max-width:160px" value="<?php echo $AU_DATE; ?>">
                                <input type="text" name="AU_DATE1" class="form-control pull-left" id="datepicker1" value="<?php echo $AU_DATE; ?>" style="width:106px" disabled></div>
                          	</div>
                        </div>
                        <div class="form-group" style="display:none">
                          	<label for="inputName" class="col-sm-2 control-label">Request Code</label>
                          	<div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-btn">
										<button type="button" class="btn btn-primary">Seacrh</button>
                                    </div>
                                    <input type="hidden" class="form-control" name="AUR_CODE" id="AUR_CODE" style="max-width:160px" value="<?php echo $AUR_CODE; ?>" onClick="pleaseCheck();" disabled>
                                    <input type="text" class="form-control" name="AUR_CODE1" id="AUR_CODE1" style="max-width:160px" value="<?php echo $AUR_CODE; ?>" onClick="pleaseCheck();" disabled>
                                </div>
                            </div>
                        </div>
						<?php
							//$url_selAURCODE	= site_url('c_asset/c_453tu55493/popupallaur/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
							$url_selAURCODE		= site_url('c_asset/c_453tu55493/popupallasset/?id='.$this->url_encryption_helper->encode_url($varURL));
                        ?>
                        <script>
							var url1 = "<?php echo $url_selAURCODE;?>";
							function pleaseCheck()
							{
								title = 'Select Item';
								w = 1000;
								h = 550;
								//window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
								var left = (screen.width/2)-(w/2);
								var top = (screen.height/2)-(h/2);
								return window.open(url1, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
							}
						</script>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $JobCode ?> </label>
                          	<div class="col-sm-10">
                            	<input type="hidden" class="form-control" name="AU_JOBCODE" id="AU_JOBCODE" style="max-width:160px" value="<?php echo $AU_JOBCODE; ?>">
                                <select name="AU_JOBCODE1" id="AU_JOBCODE1" class="form-control select2" data-placeholder="&nbsp;&nbsp;&nbsp;<?php echo $JobName; ?>" style="max-width:400px;" disabled>
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
                                            <option value="<?php echo "$JOBCODEID_1"; ?>" <?php if($JOBCODEID_1 == $AU_JOBCODE) { ?> selected <?php } if($Disabled_1 == 1) {?> disabled <?php } ?> title="<?php echo $JOBDESC_1; ?>">
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
                                                    <option value="<?php echo "$JOBCODEID_2"; ?>" <?php if($JOBCODEID_2 == $AU_JOBCODE) { ?> selected <?php } if($Disabled_2 == 1) {?> disabled <?php } ?> title="<?php echo $JOBDESC_2; ?>">
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
                                                            <option value="<?php echo "$JOBCODEID_3"; ?>" <?php if($JOBCODEID_3 == $AU_JOBCODE) { ?> selected <?php } if($Disabled_3 == 1) {?> disabled <?php } ?> title="<?php echo $JOBDESC_3; ?>">
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
                                                                    <option value="<?php echo "$JOBCODEID_4"; ?>" <?php if($JOBCODEID_4 == $AU_JOBCODE) { ?> selected <?php } if($Disabled_4 == 1) {?> disabled <?php } ?> title="<?php echo $JOBDESC_4; ?>">
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
                                                                            <option value="<?php echo "$JOBCODEID_5"; ?>" <?php if($JOBCODEID_5 == $AU_JOBCODE) { ?> selected <?php } if($Disabled_5 == 1) {?> disabled <?php } ?> title="<?php echo $JOBDESC_5; ?>">
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
                                                                                    <option value="<?php echo "$JOBCODEID_6"; ?>" <?php if($JOBCODEID_6 == $AU_JOBCODE) { ?> selected <?php } if($Disabled_6 == 1) {?> disabled <?php } ?> title="<?php echo $JOBDESC_6; ?>">
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
                                    <option value="OTH" <?php if($AU_JOBCODE == 'OTH') { ?> selected <?php } ?>><?php echo $Others; ?></option>
                                </select>
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $StartDate ?> </label>
                          	<div class="col-sm-10">
                                <div class="input-group date">
                                    <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
                                    <input type="hidden" class="form-control" name="AU_STARTD" id="AU_STARTD" style="max-width:160px" value="<?php echo $AU_STARTD; ?>">
                                    <input type="text" name="AU_STARTD1" class="form-control pull-left" id="datepicker2" value="<?php echo $AU_STARTD; ?>" style="width:100px" disabled>
                                </div>
                          	</div>
                        </div>
                        <script>
							function checkChange() 
							{
								document.getElementById("PRJCODEX").value 	= document.getElementById("PRJCODE").value;
								document.getElementById("AU_DATEX").value 		= document.getElementById("datepicker1").value;
								document.getElementById("AU_JOBCODEX").value 	= document.getElementById("AU_JOBCODE").value;
								document.getElementById("AU_AS_CODEX").value 	= document.getElementById("AU_AS_CODE").value;
								document.getElementById("AU_STARTDX").value 	= document.getElementById("datepicker2").value;
								document.getElementById("AU_ENDDX").value 		= document.getElementById("datepicker3").value;
								document.getElementById("AU_STARTTX").value 	= document.getElementById("AU_STARTT").value;
								document.getElementById("AU_ENDTX").value 		= document.getElementById("AU_ENDT").value;
								document.getElementById("AU_DESCX").value 		= document.getElementById("AU_DESC").value;
								document.frmsrch1.submitSrch1.click();
							}
						</script>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Time ?> </label>
                          	<div class="col-sm-10">
                            	<input type="hidden" class="form-control" name="AU_STARTT" id="AU_STARTT" style="max-width:160px" value="<?php echo $AU_STARTT; ?>">
                                <input type="text" class="form-control" style="max-width:80px" name="AU_STARTT1" id="AU_STARTT1" value="<?php echo $AU_STARTT; ?>" onKeyUp="toTimeString1(this.value)" disabled>
                          	</div>
                        </div>
                        <script>
							function toTimeString1(AU_STARTT)
							{
								var totTxt 	= AU_STARTT.length;
								var noHour	= /^[0-2]+$/;
								var noMinut	= /^[0-5]+$/;
								if(totTxt == 1)
								{
									isHour	= document.getElementById('AU_STARTT').value;
									if(!isHour.match(noHour))
									{
										alert('Range no [0 - 2]');
										document.getElementById('AU_STARTT').value = 0;
										document.getElementById('AU_STARTT').focus();
										return false;
									}
								}
								if(totTxt == 2)
								{
									isHour	= document.getElementById('AU_STARTT').value;
									if(isHour > 24)
									{
										alert('Hour must be less then 24');
										document.getElementById('AU_STARTT').value = '';
										document.getElementById('AU_STARTT').focus();
										return false;
									}
									else
									{
										document.getElementById('AU_STARTT').value = isHour+':';
										document.getElementById('AU_STARTT').focus();
									}
								}
								
								if(totTxt == 4)
								{
									isHour		= document.getElementById('AU_STARTT').value;
									isMinutes	= isHour.substr(3,4);
									if(!isMinutes.match(noMinut))
									{
										alert('Range no [0 - 5]');
										isHour	= isHour.substr(0,3);
										document.getElementById('AU_STARTT').value = isHour;
										document.getElementById('AU_STARTT').focus();
										return false;
									}									
								}
							}
						</script>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $EndDate ?> </label>
                          	<div class="col-sm-10">
                                <div class="input-group date">
                                    <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
                                    <input type="hidden" class="form-control" name="AU_ENDD" id="AU_ENDD" style="max-width:160px" value="<?php echo $AU_ENDD; ?>">
                                    <input type="text" name="AU_ENDD1" class="form-control pull-left" id="datepicker3" value="<?php echo $AU_ENDD; ?>" style="width:100px" onChange="changeFinishD(this.value)" disabled>
                                </div>
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Time ?> </label>
                          	<div class="col-sm-10">
                            	<input type="hidden" class="form-control" name="AU_ENDT" id="AU_ENDT" style="max-width:160px" value="<?php echo $AU_ENDT; ?>">
                                <input type="text" class="form-control" style="max-width:80px" name="AU_ENDT1" id="AU_ENDT1" value="<?php echo $AU_ENDT; ?>" onKeyUp="toTimeString2(this.value)" disabled>
                          	</div>
                        </div>
                        <script>
							function toTimeString2(AU_ENDT)
							{
								var totTxt 	= AU_ENDT.length;
								var noHour	= /^[0-2]+$/;
								var noMinut	= /^[0-5]+$/;
								if(totTxt == 1)
								{
									isHour	= document.getElementById('AU_ENDT').value;
									if(!isHour.match(noHour))
									{
										alert('Range no [0 - 2]');
										document.getElementById('AU_ENDT').value = 0;
										document.getElementById('AU_ENDT').focus();
										return false;
									}									
								}
								if(totTxt == 2)
								{
									isHour	= document.getElementById('AU_ENDT').value;
									if(isHour > 24)
									{
										alert('Hour must be less then 24');
										document.getElementById('AU_ENDT').value = '';
										document.getElementById('AU_ENDT').focus();
										return false;
									}
									else
									{
										document.getElementById('AU_ENDT').value = isHour+':';
										document.getElementById('AU_ENDT').focus();
									}
								}
								
								if(totTxt == 4)
								{
									isHour		= document.getElementById('AU_ENDT').value;
									isMinutes	= isHour.substr(3,4);
									if(!isMinutes.match(noMinut))
									{
										alert('Range no [0 - 5]');
										isHour	= isHour.substr(0,3);
										document.getElementById('AU_ENDT').value = isHour;
										document.getElementById('AU_ENDT').focus();
										return false;
									}									
								}
								
								AU_ENDT	= document.getElementById('AU_ENDT').value;
								document.getElementById('AU_PROCT').value = AU_ENDT;
							}
							
							function changeFinishD(AU_ENDD)
							{
								AU_ENDD	= document.getElementById('datepicker3').value;
								document.getElementById('datepicker4').value = AU_ENDD;
							}
						</script>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $AssetName ?> </label>
                          	<div class="col-sm-10">
                            	<div class="row">
                                    <div class="col-xs-3">
                                    	<div class="input-group">
		                                    <div class="input-group-btn">
												<button type="button" class="btn btn-primary"><?php echo $Search ?></button>
		                                    </div>
		                                    <input type="hidden" class="form-control" name="AU_AS_CODE" id="AU_AS_CODE" style="max-width:160px" value="<?php echo $AU_AS_CODE; ?>">
		                                    <input type="text" class="form-control" name="AU_AS_CODE1" id="AU_AS_CODE1" style="max-width:160px" value="<?php echo $AU_AS_CODE; ?>" onClick="pleaseCheck();" disabled>
		                                </div>
                                    </div>
                                    <div class="col-xs-9">
				                    	<select name="AU_REFNO" id="AU_REFNO" class="form-control select2">
                            				<option value="">--- None ---</option>
                            				<?php
				                                $sqlWO_1	= "SELECT WO_NUM, WO_CODE FROM tbl_wo_header 
				                                				WHERE WO_NUM = '$AU_REFNO' AND PRJCODE = '$PRJCODE'";
				                                $resWO_1	= $this->db->query($sqlWO_1)->result();
				                                foreach($resWO_1 as $row_1) :
				                                    $WO_NUM		= $row_1->WO_NUM;
				                                    $WO_CODE	= $row_1->WO_CODE;
				                                    ?>
				                                    <option value="<?php echo "$WO_NUM"; ?>" <?php if($WO_NUM == $AU_REFNO) { ?> selected <?php } ?>>
				                                        <?php echo $WO_CODE; ?>
				                                    </option>
				                                    <?php
				                                endforeach;
				                            ?>
				                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Description ?> </label>
                          	<div class="col-sm-10">
                                <textarea class="form-control" name="AU_DESC"  id="AU_DESC" style="max-width:350px;height:70px"><?php echo $AU_DESC; ?></textarea>
                          	</div>
                        </div>
						<?php
							if($AU_STAT == 3)
							{ 
								?>
                                    <div class="form-group">
                                        <label for="inputName" class="col-sm-2 control-label"><?php echo $Production ?> </label>
                                        <div class="col-sm-10">
                                            <input type="hidden" class="form-control" style="max-width:100px; text-align:right" name="AP_QTYOPR" id="AP_QTYOPR" value="<?php echo $AP_QTYOPR; ?>">
                                            <label><?php echo $Quantity ?> 
                                            <input type="text" class="form-control" style="max-width:100px; text-align:right" name="AP_QTYOPR1" id="AP_QTYOPR1" value="<?php print number_format($AP_QTYOPR, $decFormat); ?>" onBlur="changeValueProd(this)" disabled>
                                            </label>
                                            <label><?php echo $Unit ?> 
                                            <input type="hidden" class="form-control" style="max-width:100px; text-align:right" name="AP_QTYUNIT" id="AP_QTYUNIT" value="<?php echo $AP_QTYUNIT; ?>">
                                            <select name="AP_QTYUNIT1" id="AP_QTYUNIT1" class="form-control" style="max-width:100px" onChange="checkUnit(this.value)" disabled>
                                                <option value="0">None</option>
                                                <?php
                                                    if($viewUnitC > 0)
                                                    {
                                                        foreach($viewUnit as $row) :
                                                            $Unit_Type_Code = $row->Unit_Type_Code;
                                                            $UMCODE 		= $row->UMCODE;
                                                            $Unit_Type_Name	= $row->Unit_Type_Name;
                                                            ?>
                                                            <option value="<?php echo $Unit_Type_Code; ?>" <?php if($UMCODE == $AP_QTYUNIT) { ?> selected <?php } ?>><?php echo $Unit_Type_Name; ?></option>
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
                                            </label>
                                        </div>
                                    </div>
                                    <script>
                                        function changeValueProd(thisVal)
                                        {
                                            var AP_QTYOPR 	= eval(thisVal).value.split(",").join("");
                                            document.getElementById('AP_QTYOPR').value 		= parseFloat(Math.abs(AP_QTYOPR));
                                            document.getElementById('AP_QTYOPR1').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(AP_QTYOPR)),decFormat));
                                        }
                                    </script>
								<?php
							}
                        ?>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $ItemNeed; ?> ?</label>
                            <div class="col-sm-10">
                            	<input type="hidden" name="AU_NEEDITM" id="AU_NEEDITM" value="<?php echo $AU_NEEDITM; ?>">
                                <select name="AU_NEEDITM" id="AU_NEEDITM1" class="form-control select2" style="max-width:130px" onChange="chgshowItm(this.value)" disabled>
                                    <option value="1" <?php if($AU_NEEDITM == 1) { ?> selected <?php } ?>>Yes</option>
                                    <option value="0" <?php if($AU_NEEDITM == 0) { ?> selected <?php } ?>>No</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group" >
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Status ?> </label>
                          	<div class="col-sm-10">
                                <input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $AU_STAT; ?>">
								<?php
                                    // START : FOR ALL APPROVAL FUNCTION
                                        if($disableAll == 0)
										{
											if($canApprove == 1)
											{
												$disButton	= 0;
												$sqlCAPPHE	= "tbl_approve_hist WHERE AH_CODE = '$AU_CODE' AND AH_APPROVER = '$DefEmp_ID'";
												$resCAPPHE	= $this->db->count_all($sqlCAPPHE);
												if($resCAPPHE > 0)
													$disButton	= 1;
												?>
													<select name="AU_STAT" id="AU_STAT" class="form-control select2" style="max-width:130px" <?php if($disButton == 1) { ?> disabled <?php } ?> >
														<option value="0"> -- </option>
														<option value="3"<?php if($AU_STAT == 3) { ?> selected <?php } ?>>Approved</option>
														<option value="4"<?php if($AU_STAT == 4) { ?> selected <?php } ?>>Revised</option>
														<option value="5"<?php if($AU_STAT == 5) { ?> selected <?php } ?>>Rejected</option>
														<option value="7"<?php if($AU_STAT == 7) { ?> selected <?php } ?> style="display:none">Awaiting</option>
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
													Step approval not set.
												</a>
											<?php
										}
                                    // END : FOR ALL APPROVAL FUNCTION
                                ?>
                            </div>
                        </div>
                        <?php
						// DI HIDDEN SEMENTARA KARENA MENGINGINKAN AUTO-FINISH
						// if($AU_STAT == 3)
						{ 
							?>
                                <div class="form-group" style="display:none">
                                    <label for="inputName" class="col-sm-2 control-label">Process Status</label>
                                    <div class="col-sm-10">
                                        <select name="AU_PROCS" id="AU_PROCS" class="form-control" style="max-width:130px" <?php if($AU_PROCS != 1) { ?> disabled <?php } ?>>
                                            <option value="1" <?php if($AU_PROCS == 1) { ?> selected <?php } ?>>Processing</option>
                                            <option value="2" <?php if($AU_PROCS == 2) { ?> selected <?php } ?>>Finish</option>
                                            <option value="3" <?php if($AU_PROCS == 3) { ?> selected <?php } ?>>Canceled</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group" style="display:none">
                                    <label for="inputName" class="col-sm-2 control-label">Date</label>
                                    <div class="col-sm-10">
                                        <div class="input-group date">
                                            <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
                                            <input type="text" name="AU_PROCD" class="form-control pull-left" id="datepicker4" value="<?php echo $AU_PROCD; ?>" style="width:150px">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" style="display:none">
                                    <label for="inputName" class="col-sm-2 control-label">Time</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" style="max-width:80px" name="AU_PROCT" id="AU_PROCT" value="<?php echo $AU_PROCT; ?>" onKeyUp="toTimeString3(this.value)" >
                                    </div>
                                </div>
								<script>
                                    function toTimeString3(AU_PROCT)
                                    {
                                        var totTxt 	= AU_PROCT.length;
                                        var noHour	= /^[0-2]+$/;
                                        var noMinut	= /^[0-5]+$/;
                                        if(totTxt == 1)
                                        {
                                            isHour	= document.getElementById('AU_PROCT').value;
                                            if(!isHour.match(noHour))
                                            {
                                                alert('Range no [0 - 2]');
                                                document.getElementById('AU_PROCT').value = 0;
                                                document.getElementById('AU_PROCT').focus();
                                                return false;
                                            }
                                        }
                                        if(totTxt == 2)
                                        {
                                            isHour	= document.getElementById('AU_PROCT').value;
                                            if(isHour > 24)
                                            {
                                                alert('Hour must be less then 24');
                                                document.getElementById('AU_PROCT').value = '';
                                                document.getElementById('AU_PROCT').focus();
                                                return false;
                                            }
                                            else
                                            {
                                                document.getElementById('AU_PROCT').value = isHour+':';
                                                document.getElementById('AU_PROCT').focus();
                                            }
                                        }
                                        
                                        if(totTxt == 4)
                                        {
                                            isHour		= document.getElementById('AU_PROCT').value;
                                            isMinutes	= isHour.substr(3,4);
                                            if(!isMinutes.match(noMinut))
                                            {
                                                alert('Range no [0 - 5]');
                                                isHour	= isHour.substr(0,3);
                                                document.getElementById('AU_PROCT').value = isHour;
                                                document.getElementById('AU_PROCT').focus();
                                                return false;
                                            }									
                                        }
                                    }
                                </script>
							<?php
						}
                        ?>
                        <div class="form-group" id="showItem1" <?php if($AU_NEEDITM == 0) { ?> style="display:none"> <?php } ?>>
                          	<div class="col-sm-12">
                                <div class="box-tools pull-left">
                                    <table width="100%" border="1" id="tbl">
                                        <tr style="background:#CCCCCC">
                                            <th width="4%" height="25" rowspan="2" style="text-align:left">&nbsp;</th>
                                          <th width="10%" rowspan="2" style="text-align:center"><?php echo $ItemCode ?> </th>
                                          <th width="40%" rowspan="2" style="text-align:center"><?php echo $ItemName ?> </th>
                                          <th colspan="<?php if($AU_STAT != 3) { ?> 2 <?php } else { ?>3 <?php } ?>" style="text-align:center;"><?php echo $Usage ?> </th>
                                            <th rowspan="2" style="text-align:center"><?php echo $Price ?> </th>
                                            <th rowspan="2" style="text-align:center"><?php echo $Total ?> </th>
                                            <th width="29%" rowspan="2" style="text-align:center"><?php echo $Remarks ?> </th>
                                      </tr>
                                        <tr style="background:#CCCCCC">
                                            <th style="text-align:center;"><?php echo $Quantity ?></th>
                                            <th style="text-align:center; <?php if($AU_STAT != 3) { ?> display:none <?php } ?>" nowrap><?php echo $RealQty ?> </th>
                                            <th style="text-align:center;"><?php echo $Unit ?> </th>
                                        </tr>
                                        <?php					
                                        if($task == 'edit')
                                        {
                                            $sqlDET		= "SELECT A.AU_CODE, A.PRJCODE, A.ITM_CODE, A.ITM_QTY_P, A.ITM_QTY, A.ITM_UNIT, 
															A.ITM_PRICE, A.NOTES, A.ITM_KIND,
															B.ITM_DESC,
                                                            C.Unit_Type_Code, C.UMCODE, C.Unit_Type_Name
                                                            FROM tbl_asset_usagedet A
                                                                INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
                                                                INNER JOIN tbl_unittype C ON C.UMCODE = A.ITM_UNIT
                                                            WHERE A.AU_CODE = '$AU_CODE' 
                                                            AND B.PRJCODE = '$PRJCODE'";
                                            // count data
                                                $resultCount = $this->db->where('AU_CODE', $AU_CODE);
                                                $resultCount = $this->db->count_all_results('tbl_asset_usagedet');
                                            // End count data
                                            $result = $this->db->query($sqlDET)->result();
                                            $i		= 0;
                                            $j		= 0;
                                            if($resultCount > 0)
                                            {
                                                foreach($result as $row) :
                                                    $currentRow  	= ++$i;
                                                    $AU_CODE 		= $row->AU_CODE;
                                                    $ITM_CODE 		= $row->ITM_CODE;
                                                    $ITM_DESC 		= $row->ITM_DESC;
                                                    $PRJCODE		= $row->PRJCODE;
                                                    $ITM_QTY_P 		= $row->ITM_QTY_P;
                                                    $ITM_QTY 		= $row->ITM_QTY;
                                                    $ITM_UNIT 		= $row->ITM_UNIT;
                                                    $ITM_PRICE 		= $row->ITM_PRICE;
                                                    $Unit_Type_Code	= $row->Unit_Type_Code;
                                                    $UMCODE 		= $row->UMCODE;
                                                    $Unit_Type_Name	= $row->Unit_Type_Name;
                                                    $NOTES			= $row->NOTES;
                                                    $ITM_KIND		= $row->ITM_KIND;
                                                    $itemConvertion	= 1;
													
													$ITM_TOTAL		= $ITM_QTY * $ITM_PRICE;
													
													// GET REMAIN QTY
													$sqlQTY			= "SELECT ITM_IN, ITM_OUT FROM tbl_item
																		WHERE
																			ITM_CODE = '$ITM_CODE'
																			AND PRJCODE = '$PRJCODE'
																			AND STATUS = 1";
													$resQTY			= $this->db->query($sqlQTY)->result();
													foreach($resQTY as $rowQTY) :
														$ITM_IN 		= $rowQTY->ITM_IN;
														$ITM_OUT	 	= $rowQTY->ITM_OUT;
													endforeach;
													                                        
                                                    /*if ($j==1) {
                                                        echo "<tr class=zebra1>";
                                                        $j++;
                                                    } else {
                                                        echo "<tr class=zebra2>";
                                                        $j--;
                                                    }*/
                                                    ?>
                                                    <!-- Checkbox -->
                                                    <td width="4%" height="25" style="text-align:left">
                                                        <?php
															if($AU_STAT == 1)
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
                                                        <input type="hidden" id="chk" name="chk" value="<?php echo $currentRow; ?>" width="10" size="15" readonly class="form-control" style="max-width:300px;">
                                                        <input type="Checkbox" id="data[<?php echo $currentRow; ?>][chk]" name="data[<?php echo $currentRow; ?>][chk]" value="<?php echo $currentRow; ?>" onClick="pickThis(this,<?php echo $currentRow; ?>)" style="display:none">
                                                        <input type="Checkbox" style="display:none" id="chk<?php echo $currentRow; ?>" name="chk<?php echo $currentRow; ?>" value="">
                                                        <input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">
                                                        <input type="hidden" id="chk" name="chk" value="<?php echo $currentRow; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">
                                                        <input type="hidden" id="data<?php echo $currentRow; ?>AU_CODE" name="data[<?php echo $currentRow; ?>][AU_CODE]" value="<?php echo $AU_CODE; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">
                                                        <input type="hidden" id="data<?php echo $currentRow; ?>PRJCODE" name="data[<?php echo $currentRow; ?>][PRJCODE]" value="<?php echo $PRJCODE; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">
                                                        <input type="hidden" id="data<?php echo $currentRow; ?>ITM_KIND" name="data[<?php echo $currentRow; ?>][ITM_KIND]" value="<?php echo $ITM_KIND; ?>" width="10">
                                                    </td>
                                                    
                                                    <!-- Item Code -->
                                               	  	<td width="10%" style="text-align:left" nowrap>
                                                      	<?php echo $ITM_CODE; ?>
                                           				<input type="hidden" id="data<?php echo $currentRow; ?>ITM_CODE" name="data[<?php echo $currentRow; ?>][ITM_CODE]" value="<?php echo $ITM_CODE; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">
                                                    </td>
                                                    
                                                    <!-- Item Name -->
                                               	  	<td width="40%" style="text-align:left">
                                                      	<?php echo $ITM_DESC; ?>
                                                        <input type="hidden" style="text-align:right" name="ITM_NAME<?php echo $currentRow; ?>" id="ITM_NAME<?php echo $currentRow; ?>" size="10" value="<?php echo $ITM_DESC; ?>" >
													</td>
                                                    
                                                    <!-- Item Qty -->
                                               	  	<td width="7%" nowrap style="text-align:right">
                                                    	<?php echo number_format($ITM_QTY,2); ?>
                                                    	<input type="hidden" class="form-control" style="min-width:100px; max-width:350px; text-align:right" name="ITM_QTYx<?php echo $currentRow; ?>" id="ITM_QTYx<?php echo $currentRow; ?>" value="<?php echo number_format($ITM_QTY,2); ?>" onBlur="changeValue(this, <?php echo $currentRow; ?>, 0)" disabled>
                                                        <input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][ITM_QTY]" id="ITM_QTY<?php echo $currentRow; ?>" size="10" value="<?php echo $ITM_QTY; ?>" >
                                                        <input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][ITM_QTY_P]" id="ITM_QTY_P<?php echo $currentRow; ?>" size="10" value="<?php echo $ITM_QTY; ?>" >
                                                        <input type="hidden" style="text-align:right" name="ITM_QTY_MIN<?php echo $currentRow; ?>" id="ITM_QTY_MIN<?php echo $currentRow; ?>" size="10" value="<?php echo $ITM_QTY; ?>" >
                                                    </td>
                                                    
                                                    <!-- Item Unit -->
                                               	  	<td width="2%" nowrap style="text-align:center">
                                                      	<?php echo $ITM_UNIT; ?>
                                       					<input type="hidden" class="form-control" style="max-width:350px; text-align:right" name="data[<?php echo $currentRow; ?>][ITM_UNIT]" id="ITM_UNIT<?php echo $currentRow; ?>" size="10" value="<?php echo $ITM_UNIT; ?>" >
                                                    </td>
                                                    
                                                    <!-- Item Price -->
                                               	  	<td width="1%" nowrap style="text-align:center">
                                                  		hidden
                                                        <input type="hidden" class="form-control" style="min-width:100px; max-width:350px; text-align:right" name="ITM_PRICEx<?php echo $currentRow; ?>" id="ITM_PRICEx<?php echo $currentRow; ?>" value="<?php print number_format($ITM_PRICE, $decFormat); ?>" onBlur="changeValuePrc(this, <?php echo $currentRow; ?>)" >
                                                        <input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][ITM_PRICE]" id="ITM_PRICE<?php echo $currentRow; ?>" size="10" value="<?php echo $ITM_PRICE; ?>" >
                                                   	</td>
                                                    
                                                    <!-- Item Total -->
                                               	  	<td width="0%" nowrap style="text-align:center">
                                                    	hidden
                                                  		<input type="hidden" class="form-control" style="min-width:100px; max-width:350px; text-align:right" name="ITM_TOTALx<?php echo $currentRow; ?>" id="ITM_TOTALx<?php echo $currentRow; ?>" value="<?php print number_format($ITM_TOTAL, $decFormat); ?>" >
                                                        <input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][ITM_TOTAL]" id="ITM_TOTAL<?php echo $currentRow; ?>" size="10" value="<?php echo $ITM_TOTAL; ?>" >
                                                    </td>
                                                    
                                                    <!-- Notes -->
                                               	  	<td width="29%" style="text-align:center">
                                                    	<?php echo $NOTES; ?>
                                           				<input type="hidden" name="data[<?php echo $currentRow; ?>][NOTES]" id="data<?php echo $currentRow; ?>NOTES" value="<?php echo $NOTES; ?>" class="form-control" style="max-width:450px;text-align:left" disabled>
                                                    </td>
                                                    <input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">
                                              </tr>
                                          <?php
                                                endforeach;
                                            }
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
                        <br>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                            <?php
								if($disableAll == 0)
								{
									if(($AU_STAT == 2 || $AU_STAT == 7) && $canApprove == 1)
									{
										?>
											<button class="btn btn-primary" >
											<i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Update; ?>
											</button>&nbsp;
										<?php
									}
								}
                                echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i>&nbsp;&nbsp;'.$Back.'</button>');
                            ?>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
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
	
	//Date picker
	$('#datepicker2').datepicker({
	  autoclose: true
	});
	
	//Date picker
	$('#datepicker3').datepicker({
	  autoclose: true
	});
	
	//Date picker
	$('#datepicker4').datepicker({
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
	
	var decFormat		= 2;

	function add_header(strItem) 
	{
		arrItem = strItem.split('|');
		ilvl = arrItem[1];
		
		AU_AS_CODE		= arrItem[0];
		
		document.getElementById("AU_AS_CODE").value = AU_AS_CODE;
		//document.frmsrch1.submitSrch1.click();
	}
	
	function checkInp()
	{
		AU_STAT		= document.getElementById('AU_STAT').value;
		if(AU_STAT == 0)
		{
			alert('<?php echo $alert1; ?>');
			document.getElementById('AU_STAT').focus();
			return false;
		}
	}
	
	function changeValue(thisVal, theRow, isReal)
	{
		var ITM_QTY_MIN	= document.getElementById('ITM_QTY_MIN'+theRow).value;
		var ITM_QTYx 	= eval(thisVal).value.split(",").join("");
		
		// untuk sementara tidak ada batasan
		// karena tidak mengurangu stock
		/*if(parseFloat(ITM_QTYx) > parseFloat(ITM_QTY_MIN))
		{
			alert('Qty can not greater then '+ ITM_QTY_MIN);
			document.getElementById('ITM_QTY'+theRow).value 		= parseFloat(Math.abs(ITM_QTY_MIN));
			if(isReal == 0)
			{
				document.getElementById('ITM_QTY_P'+theRow).value 		= parseFloat(Math.abs(ITM_QTY_MIN));
			}
			document.getElementById('ITM_QTYx'+theRow).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_QTY_MIN)),decFormat));
		}
		else
		{
			document.getElementById('ITM_QTY'+theRow).value 		= parseFloat(Math.abs(ITM_QTYx));
			if(isReal == 0)
			{
				document.getElementById('ITM_QTY_P'+theRow).value 		= parseFloat(Math.abs(ITM_QTYx));
			}
			document.getElementById('ITM_QTYx'+theRow).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_QTYx)),decFormat));
		}*/
		
		document.getElementById('ITM_QTY'+theRow).value 		= parseFloat(Math.abs(ITM_QTYx));
		document.getElementById('ITM_QTY_P'+theRow).value 		= parseFloat(Math.abs(ITM_QTYx));
		if(isReal == 0)
		{
			document.getElementById('ITM_QTY_P'+theRow).value 	= parseFloat(Math.abs(ITM_QTYx));
		}
		document.getElementById('ITM_QTYx'+theRow).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_QTYx)),decFormat));
		
		var ITM_QTY 	= document.getElementById('ITM_QTY'+theRow).value;
		var ITM_PRICE 	= document.getElementById('ITM_PRICE'+theRow).value;
		ITM_TOTAL		= parseFloat(ITM_QTY) * parseFloat(ITM_PRICE);
		document.getElementById('ITM_TOTAL'+theRow).value 		= parseFloat(Math.abs(ITM_TOTAL));
		document.getElementById('ITM_TOTALx'+theRow).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_TOTAL)),decFormat));
	}
	
	function changeValuePrc(thisVal, theRow)
	{
		var ITM_PRICEx 	= eval(thisVal).value.split(",").join("");
		
		document.getElementById('ITM_PRICE'+theRow).value 		= parseFloat(Math.abs(ITM_PRICEx));
		document.getElementById('ITM_PRICEx'+theRow).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_PRICEx)),decFormat));
		
		var ITM_QTY 	= document.getElementById('ITM_QTY'+theRow).value;
		var ITM_PRICE 	= document.getElementById('ITM_PRICE'+theRow).value;
		ITM_TOTAL		= parseFloat(ITM_QTY) * parseFloat(ITM_PRICE);
		document.getElementById('ITM_TOTAL'+theRow).value 		= parseFloat(Math.abs(ITM_TOTAL));
		document.getElementById('ITM_TOTALx'+theRow).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_TOTAL)),decFormat));
	}

	function add_item(strItem) 
	{
		arrItem = strItem.split('|');
		var objTable, objTR, objTD, intIndex, arrItem;
		var AU_CODE 	= "<?php echo $AU_CODE; ?>";
		var PRJCODE 	= "<?php echo $PRJCODE; ?>";
		ilvl = arrItem[1];
		
		itemcode 		= arrItem[0];
		itemserial 		= arrItem[1];
		itemname 		= arrItem[2];
		itemUnit 		= arrItem[3];
		itemUnitName 	= arrItem[4];
		itemUnit2 		= arrItem[5];
		itemUnitName2 	= arrItem[6];
		itemConvertion 	= arrItem[9];
		itemQty 		= arrItem[10];
		itemPrice 		= arrItem[11];
		itemKind 		= arrItem[12];
		
		ITM_TOTAL		= itemQty * itemPrice;
		
		validateDouble(arrItem[0])
		if(validateDouble(arrItem[0]))
		{
			alert("Double Item for " + itemname);
			return false;
		}
		
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
		objTD.innerHTML = '<a href="#" onClick="deleteRow('+intIndex+')" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a><input type="hidden" id="chk" name="chk" value="'+intIndex+'" width="10" size="15" readonly class="form-control" style="max-width:300px;"><input type="Checkbox" id="data['+intIndex+'][chk]" name="data['+intIndex+'][chk]" value="'+intIndex+'" onClick="pickThis(this,'+intIndex+')" style="display:none"><input type="Checkbox" style="display:none" id="chk'+intIndex+'" name="chk'+intIndex+'" value=""><input type="hidden" name="totalrow" id="totalrow" value="'+intIndex+'"><input type="hidden" id="chk" name="chk" value="'+intIndex+'" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right"><input type="hidden" id="data'+intIndex+'AU_CODE" name="data['+intIndex+'][AU_CODE]" value="'+AU_CODE+'" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right"><input type="hidden" id="data'+intIndex+'PRJCODE" name="data['+intIndex+'][PRJCODE]" value="'+PRJCODE+'" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right"><input type="hidden" id="data'+intIndex+'ITM_KIND" name="data['+intIndex+'][ITM_KIND]" value="'+itemKind+'" width="10">';	
		
		// Item Code
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'right';
		objTD.noWrap = true;
		objTD.innerHTML = ''+itemcode+'<input type="hidden" id="data'+intIndex+'ITM_CODE" name="data['+intIndex+'][ITM_CODE]" value="'+itemcode+'" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">';
		
		// Item Name
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.innerHTML = ''+itemname+'';
		
		// Item Qty
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" class="form-control" style="min-width:100px; max-width:350px; text-align:right" name="ITM_QTYx'+intIndex+'" id="ITM_QTYx'+intIndex+'" value="'+itemQty+'" onBlur="changeValue(this, '+intIndex+', 0)" ><input type="hidden" style="text-align:right" name="data['+intIndex+'][ITM_QTY]" id="ITM_QTY'+intIndex+'" size="10" value="'+itemQty+'" ><input type="hidden" style="text-align:right" name="data['+intIndex+'][ITM_QTY_P]" id="ITM_QTY_P'+intIndex+'" size="10" value="'+itemQty+'" ><input type="hidden" style="text-align:right" name="ITM_QTY_MIN'+intIndex+'" id="ITM_QTY_MIN'+intIndex+'" size="10" value="'+itemQty+'" >';
		
		// Item Unit
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = ''+itemUnitName+'<input type="hidden" class="form-control" style="max-width:350px; text-align:right" name="data['+intIndex+'][ITM_UNIT]" id="ITM_UNIT'+intIndex+'" size="10" value="'+itemUnit+'" >';
		
		// Item Price
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = 'hidden <input type="hidden" class="form-control" style="min-width:100px; max-width:350px; text-align:right" name="ITM_PRICEx'+intIndex+'" id="ITM_PRICEx'+intIndex+'" value="'+itemPrice+'" onBlur="changeValuePrc(this, '+intIndex+')" ><input type="hidden" style="text-align:right" name="data['+intIndex+'][ITM_PRICE]" id="ITM_PRICE'+intIndex+'" size="10" value="'+itemPrice+'" >';
		
		// Item Price Total
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = 'hidden <input type="hidden" class="form-control" style="min-width:130px; max-width:350px; text-align:right" name="ITM_TOTALx'+intIndex+'" id="ITM_TOTALx'+intIndex+'" value="'+ITM_TOTAL+'" ><input type="hidden" style="text-align:right" name="data['+intIndex+'][ITM_TOTAL]" id="ITM_TOTAL'+intIndex+'" size="10" value="'+ITM_TOTAL+'" >';
		
		// Notes
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" name="data['+intIndex+'][NOTES]" id="data'+intIndex+'NOTES" value="" class="form-control" style="max-width:450px;text-align:left">';
		
		var decFormat											= document.getElementById('decFormat').value;
		var ITM_QTY												= document.getElementById('ITM_QTY'+intIndex).value;
		document.getElementById('ITM_QTY'+intIndex).value 		= parseFloat(Math.abs(ITM_QTY));
		document.getElementById('ITM_QTY_P'+intIndex).value 		= parseFloat(Math.abs(ITM_QTY));
		document.getElementById('ITM_QTYx'+intIndex).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_QTY)),decFormat));
		
		var ITM_PRICE											= document.getElementById('ITM_PRICE'+intIndex).value
		document.getElementById('ITM_PRICE'+intIndex).value 	= parseFloat(Math.abs(ITM_PRICE));
		document.getElementById('ITM_PRICEx'+intIndex).value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_PRICE)),decFormat));
		
		var ITM_TOTAL											= document.getElementById('ITM_TOTAL'+intIndex).value
		document.getElementById('ITM_TOTAL'+intIndex).value 	= parseFloat(Math.abs(ITM_TOTAL));
		document.getElementById('ITM_TOTALx'+intIndex).value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_TOTAL)),decFormat));
		
		document.getElementById('totalrow').value 				= intIndex;
	}	
	
	function deleteRow(btn)
	{
		var row = document.getElementById("tr_" + btn);
		row.remove();
	}
	
	function validateDouble(vcode) 
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
				//var iparent= document.getElementById('data'+i+'ITM_SNCODE').value;
				//if (elitem1 == vcode && iparent == SNCODE)
				if (elitem1 == vcode)
				{
					/*if (elitem1 == vcode) 
					{*/
						duplicate = true;
						break;
					//}
				}
			}
		}
		return duplicate;
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
$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>