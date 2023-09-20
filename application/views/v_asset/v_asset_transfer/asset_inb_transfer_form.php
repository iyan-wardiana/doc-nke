<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 28 Januari 2019
 * File Name	= item_inb_transfer_form.php
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

$currentRow 	= 0;
$isSetDocNo 	= 1;
$ASTSF_NUM		= $default['ASTSF_NUM'];
$DocNumber		= $default['ASTSF_NUM'];
$ASTSF_CODE		= $default['ASTSF_CODE'];
$ASTSF_DATE		= $default['ASTSF_DATE'];
$ASTSF_DATE		= date('m/d/Y',strtotime($ASTSF_DATE));
$ASTSF_SENDD	= $default['ASTSF_SENDD'];
$ASTSF_SENDD	= date('m/d/Y',strtotime($ASTSF_SENDD));
$ASTSF_RECD		= $default['ASTSF_RECD'];
$ASTSF_RECD		= date('m/d/Y',strtotime($ASTSF_RECD));
$PRJCODE		= $default['PRJCODE'];
$PRJCODE_DEST	= $default['PRJCODE_DEST'];
$JOBCODEID		= $default['JOBCODEID'];
$ASTSF_REFNO	= $JOBCODEID;
$ASTSF_NOTE		= $default['ASTSF_NOTE'];
$ASTSF_NOTE2	= $default['ASTSF_NOTE2'];
$ASTSF_REVMEMO	= $default['ASTSF_REVMEMO'];
$ASTSF_STAT		= $default['ASTSF_STAT'];
$ASTSF_AMOUNT	= $default['ASTSF_AMOUNT'];
$ASTSF_SENDER	= $default['ASTSF_SENDER'];
$ASTSF_RECEIVER	= $default['ASTSF_RECEIVER'];
$Patt_Year		= $default['Patt_Year'];
$Patt_Month		= $default['Patt_Month'];
$Patt_Date		= $default['Patt_Date'];
$Patt_Number	= $default['Patt_Number'];
$lastPatternNumb1= $default['Patt_Number'];

// Project List
$DefEmp_ID	= $this->session->userdata['Emp_ID'];
//$sqlPLC	= "tbl_project WHERE PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";
$sqlPLC		= "tbl_project WHERE PRJSTAT = '1'";
$resPLC		= $this->db->count_all($sqlPLC);

$sqlPL 		= "SELECT PRJCODE, PRJNAME FROM tbl_project WHERE PRJSTAT = '1' ORDER BY PRJNAME";
$resPL		= $this->db->query($sqlPL)->result();

$sqlPLC2	= "tbl_project WHERE PRJSTAT = '1' AND PRJCODE != '$PRJCODE'";
$resPLC2	= $this->db->count_all($sqlPLC2);

$sqlPL2		= "SELECT PRJCODE, PRJNAME FROM tbl_project WHERE PRJSTAT = '1' AND PRJCODE != '$PRJCODE' ORDER BY PRJNAME";
$resPL2		= $this->db->query($sqlPL2)->result();
	
if(isset($_POST['JOBCODE1']))
{
	$ASTSF_REFNO	= $_POST['JOBCODE1'];
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
		if($TranslCode == 'TsfNo')$TsfNo = $LangTransl;
		if($TranslCode == 'Sender')$Sender = $LangTransl;
		if($TranslCode == 'Receiver')$Receiver = $LangTransl;
		if($TranslCode == 'ReceiptDate')$ReceiptDate = $LangTransl;
		if($TranslCode == 'Code')$Code = $LangTransl;
		if($TranslCode == 'Notes')$Notes = $LangTransl;
		if($TranslCode == 'ApproverNotes')$ApproverNotes = $LangTransl;
		if($TranslCode == 'Date')$Date = $LangTransl;
		if($TranslCode == 'SendDate')$SendDate = $LangTransl;
		if($TranslCode == 'Destination')$Destination = $LangTransl;
		if($TranslCode == 'Status')$Status = $LangTransl;
		if($TranslCode == 'New')$New = $LangTransl;
		if($TranslCode == 'Confirm')$Confirm = $LangTransl;
		if($TranslCode == 'Close')$Close = $LangTransl;		
		if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
		if($TranslCode == 'ItemName')$ItemName = $LangTransl;
		if($TranslCode == 'Unit')$Unit = $LangTransl;
		if($TranslCode == 'MachineBrand')$MachineBrand = $LangTransl;
		if($TranslCode == 'Volume')$Volume = $LangTransl;
		if($TranslCode == 'Description')$Description = $LangTransl;
		if($TranslCode == 'Quantity')$Quantity = $LangTransl;
		if($TranslCode == 'Unit')$Unit = $LangTransl;
		if($TranslCode == 'Primary')$Primary = $LangTransl;
		if($TranslCode == 'Secondary')$Secondary = $LangTransl;
		if($TranslCode == 'Remarks')$Remarks = $LangTransl;
		if($TranslCode == 'SelectItem')$SelectItem = $LangTransl;
		if($TranslCode == 'JobName')$JobName = $LangTransl;
		if($TranslCode == 'ReceiptDate')$ReceiptDate = $LangTransl;
		if($TranslCode == 'reviseNotes')$reviseNotes = $LangTransl;
		if($TranslCode == 'Budget')$Budget = $LangTransl;
		if($TranslCode == 'PerMonth')$PerMonth = $LangTransl;
		if($TranslCode == 'TsfFrom')$TsfFrom = $LangTransl;
		if($TranslCode == 'TsfVol')$TsfVol = $LangTransl;
	endforeach;
	
	if($LangID == 'IND')
	{
		$subTitleH	= "Tambah";
		$subTitleD	= "pemindahan alat";
		
		$alert1		= "Masukan jumlah item yang akan dikirim.";
		$alert2		= "Pilih salah satu Aset.";
		$alert3		= "Silahkan pilih status persetujuan.";
		$alert4		= "Jumlah yang ditransfer lebih besar dari stok.";
		$alert5		= "Silahkan tulis alasan revisi dokumen.";
		$isManual	= "Centang untuk kode manual.";
	}
	else
	{
		$subTitleH	= "Add";
		$subTitleD	= "tools transfer";
		
		$alert1		= "Please input Transfer Qty.";
		$alert2		= "Please select asset.";
		$alert3		= "Please select an approval status.";
		$alert4		= "The Qty Transferred is greater than the Qty Stock.";
		$alert5		= "Plese input the reason why you revise the document.";
		$isManual	= "Check to manual code.";
	}
	
	$secGenCode	= base_url().'index.php/c_asset/c_1s3txpen/genCode/'; // Generate Code
	
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
			
			$sqlC_App	= "tbl_approve_hist WHERE AH_CODE = '$ASTSF_NUM'";
			$resC_App 	= $this->db->count_all($sqlC_App);
			//$appReady	= $APP_STEP;
			//if($resC_App == 0)
			//echo "APP_STEP = $APP_STEP = $resC_App = $MAX_STEP";
			$BefStepApp	= $APP_STEP - 1;
			//echo "1. resC_App = $resC_App == BefStepApp = $BefStepApp == canApprove = $canApprove<br>";
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
			$APPROVE_AMOUNT = $ASTSF_AMOUNT;
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
                        <input type="hidden" name="ASTSFJCODEX" id="ASTSFJCODEX" value="<?php echo $PRJCODE; ?>">
                        <input type="hidden" name="Pattern_Code" id="Pattern_Code" value="<?php echo $Pattern_Code; ?>">
                        <input type="hidden" name="Pattern_Length" id="Pattern_Length" value="<?php echo $Pattern_Length; ?>">
                        <input type="hidden" name="useYear" id="useYear" value="<?php echo $useYear; ?>">
                        <input type="hidden" name="useMonth" id="useMonth" value="<?php echo $useMonth; ?>">
                        <input type="hidden" name="useDate" id="useDate" value="<?php echo $useDate; ?>">
                        <input type="hidden" name="ASTSFDate" id="ASTSFDate" value="">
                    </td>
                    <td><a class="tombol-date" id="dateClass">Simpan</a></td>
                </tr>
            </table>
        </form>
        <!-- after get Supplier code -->
        <form name="frmsrch1" id="frmsrch1" action="" method=POST style="display:none">
            <input type="text" name="PRJCODE1" id="PRJCODE1" value="<?php echo $PRJCODE; ?>" />
            <input type="text" name="JOBCODE1" id="JOBCODE1" value="<?php echo $ASTSF_REFNO; ?>" />
            <input type="submit" class="button_css" name="submitSrch1" id="submitSrch1" value=" search " />
        </form>
        <!-- End -->
        <form class="form-horizontal" name="frm" method="post" action="<?php echo $form_action; ?>" enctype="multipart/form-data" onSubmit="return checkInData()">
            <input type="hidden" name="IS_LAST" id="IS_LAST" value="<?php echo $IS_LAST; ?>">
            <input type="hidden" name="APP_LEVEL" id="APP_LEVEL" value="<?php echo $APP_LEVEL; ?>">
            <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
            <input type="hidden" name="Patt_Number" id="Patt_Number" value="<?php echo $Patt_Number; ?>" />
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
            	<div class="form-group" style="display:none">
                    <label for="inputName" class="col-sm-2 control-label"><?php echo $TsfNo; ?></label>
                    <div class="col-sm-10">
                    	<input type="text" class="form-control" style="max-width:180px;text-align:left" name="ASTSF_NUM1" id="ASTSF_NUM1" size="30" value="<?php echo $DocNumber; ?>" disabled />
                    	<input type="hidden" class="form-control" style="max-width:350px;text-align:right" name="ASTSF_NUM" id="ASTSF_NUM" size="30" value="<?php echo $DocNumber; ?>" />
                        <input type="hidden" class="form-control" style="max-width:350px;text-align:right" name="PRJCODE" id="PRJCODE" size="30" value="<?php echo $PRJCODE; ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label"><?php echo $TsfNo; ?> </label>
                    <div class="col-sm-10">
                        <label>
                            <input type="hidden" class="form-control" style="min-width:width:150px; max-width:150px" name="ASTSF_CODE" id="ASTSF_CODE" value="<?php echo $ASTSF_CODE; ?>" >
                            <input type="text" class="form-control" style="min-width:width:150px; max-width:150px" name="ASTSF_CODE1" id="ASTSF_CODE1" value="<?php echo $ASTSF_CODE; ?>" disabled >
                        </label>
                        <label style="display:none">
                            &nbsp;&nbsp;<input type="checkbox" name="isManual" id="isManual">
                        </label>
                        <label style="font-style:italic; display:none">
                            <?php echo $isManual; ?>
                        </label>
                    </div>
                </div>
            	<div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Date; ?></label>
                    <div class="col-sm-10">
                    	<div class="input-group date">
                        <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>&nbsp;</div><input type="text" name="ASTSF_DATE1" class="form-control pull-left" id="datepicker" value="<?php echo $ASTSF_DATE; ?>" style="width:106px" disabled><input type="hidden" name="ASTSF_DATE" class="form-control pull-left" id="datepicker" value="<?php echo $ASTSF_DATE; ?>" style="width:106px" onChange="getASTSF_NUM(this.value)"></div>
                    </div>
                </div>
            	<div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label"><?php echo $SendDate; ?></label>
                    <div class="col-sm-10">
                    	<div class="input-group date">
                        <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>&nbsp;</div><input type="text" name="ASTSF_SENDD1" class="form-control pull-left" id="datepicker1" value="<?php echo $ASTSF_SENDD; ?>" style="width:106px" disabled><input type="hidden" name="ASTSF_SENDD" class="form-control pull-left" id="datepicker1" value="<?php echo $ASTSF_SENDD; ?>" style="width:106px" onChange="getASTSF_NUM(this.value)"></div>
                    </div>
                </div>
            	<div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label"><?php echo $TsfFrom; ?></label>
                    <div class="col-sm-10">
                        <select name="PRJCODE1" id="PRJCODE1" class="form-control" style="max-width:400px" disabled >
							<?php
                                if($resPLC > 0)
                                {
                                    foreach($resPL as $rowPL) :
                                        $PRJCODE1 = $rowPL->PRJCODE;
                                        $PRJNAME1 = $rowPL->PRJNAME;
                                        ?>
                                        <option value="<?php echo $PRJCODE1; ?>" <?php if($PRJCODE1 == $PRJCODE) { ?> selected <?php } ?>><?php echo "$PRJCODE1 - $PRJNAME1"; ?></option>
                            			<?php
                                    endforeach;
                                }
                                else
                                {
                                    ?>
                                        <option value="none">--- No Project Found ---</option>
                            		<?php
                                }
                            ?>
                        </select>
                    	<input type="hidden" class="form-control" id="PRJCODE" name="PRJCODE" value="<?php echo $PRJCODE; ?>" />                        
                    </div>
                </div>
            	<div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Destination; ?></label>
                    <div class="col-sm-10">
                        <select name="PRJCODE_DEST1" id="PRJCODE_DEST1" class="form-control" style="max-width:400px" disabled >
							<?php
                                if($resPLC2 > 0)
                                {
                                    foreach($resPL2 as $rowPL2) :
                                        $PRJCODE1 = $rowPL2->PRJCODE;
                                        $PRJNAME1 = $rowPL2->PRJNAME;
                                        ?>
                                        <option value="<?php echo $PRJCODE1; ?>" <?php if($PRJCODE1 == $PRJCODE_DEST) { ?> selected <?php } ?>><?php echo "$PRJCODE1 - $PRJNAME1"; ?></option>
                            			<?php
                                    endforeach;
                                }
                                else
                                {
                                    ?>
                                        <option value="none">--- No Project Found ---</option>
                            		<?php
                                }
                            ?>
                        </select>
                    	<input type="hidden" class="form-control" id="PRJCODE_DEST" name="PRJCODE_DEST" value="<?php echo $PRJCODE_DEST; ?>" />
                    </div>
                </div>
                <?php
				$isHidden	= 1;
				if($isHidden == 0)
				{
					?>
					<div class="form-group" style="display:none">
						<label for="inputName" class="col-sm-2 control-label"><?php echo $JobName; ?></label>
						<div class="col-sm-10">
							<select name="ASTSF_REFNO" id="ASTSF_REFNO" class="form-control select2" data-placeholder="&nbsp;&nbsp;&nbsp;<?php echo $JobName; ?>" style="max-width:400px;" onChange="selJOB(this.value)">
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
										<option value="<?php echo "$JOBCODEID_1"; ?>" <?php if($JOBCODEID_1 == $ASTSF_REFNO) { ?> selected <?php } if($Disabled_1 == 1) {?> disabled <?php } ?> title="<?php echo $JOBDESC_1; ?>">
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
												<option value="<?php echo "$JOBCODEID_2"; ?>" <?php if($JOBCODEID_2 == $ASTSF_REFNO) { ?> selected <?php } if($Disabled_2 == 1) {?> disabled <?php } ?> title="<?php echo $JOBDESC_2; ?>">
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
														<option value="<?php echo "$JOBCODEID_3"; ?>" <?php if($JOBCODEID_3 == $ASTSF_REFNO) { ?> selected <?php } if($Disabled_3 == 1) {?> disabled <?php } ?> title="<?php echo $JOBDESC_3; ?>">
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
																<option value="<?php echo "$JOBCODEID_4"; ?>" <?php if($JOBCODEID_4 == $ASTSF_REFNO) { ?> selected <?php } if($Disabled_4 == 1) {?> disabled <?php } ?> title="<?php echo $JOBDESC_4; ?>">
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
																		<option value="<?php echo "$JOBCODEID_5"; ?>" <?php if($JOBCODEID_5 == $ASTSF_REFNO) { ?> selected <?php } if($Disabled_5 == 1) {?> disabled <?php } ?> title="<?php echo $JOBDESC_5; ?>">
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
																				<option value="<?php echo "$JOBCODEID_6"; ?>" <?php if($JOBCODEID_6 == $ASTSF_REFNO) { ?> selected <?php } if($Disabled_6 == 1) {?> disabled <?php } ?> title="<?php echo $JOBDESC_6; ?>">
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
						function selJOB(ASTSF_REFNO) 
						{
							document.getElementById("JOBCODE1").value = ASTSF_REFNO;
							PRJCODE	= document.getElementById("PRJCODE").value
							document.getElementById("PRJCODE1").value = PRJCODE;
							document.frmsrch1.submitSrch1.click();
						}
					</script>
					<?php
				}
				?>
                <div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Sender; ?> </label>
                    <div class="col-sm-10">
                    	<input type="text" class="form-control" style="max-width:400px" name="ASTSF_SENDER" id="ASTSF_SENDER" value="<?php echo $ASTSF_SENDER; ?>" placeholder="<?php echo $Sender; ?>" disabled >
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Notes; ?></label>
                    <div class="col-sm-10">
                        <textarea name="ASTSF_NOTE" class="form-control" style="max-width:400px;" id="ASTSF_NOTE" cols="30" disabled><?php echo $ASTSF_NOTE; ?></textarea>                        
                    </div>
                </div>
                <?php
					if($ISAPPROVE == 1)
					{
						?>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $Receiver; ?> </label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" style="min-width:width:150px; max-width:400px" name="ASTSF_RECEIVER" id="ASTSF_RECEIVER" value="<?php echo $ASTSF_RECEIVER; ?>" placeholder="<?php echo $Receiver; ?>" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $ReceiptDate; ?> </label>
                            <div class="col-sm-10">
                                <div class="input-group date">
                                <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>&nbsp;</div><input type="text" name="ASTSF_RECD" class="form-control pull-left" id="datepicker2" value="<?php echo $ASTSF_RECD; ?>" style="width:106px" ></div>
                            </div>
                        </div>
                		<?php
					}
				?>
                <div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label"><?php echo $ApproverNotes; ?></label>
                    <div class="col-sm-10">
                        <textarea name="ASTSF_NOTE2" class="form-control" style="max-width:400px;" id="ASTSF_NOTE2" cols="30"><?php echo $ASTSF_NOTE2; ?></textarea>                        
                    </div>
                </div>
            	<div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Status; ?></label>
                    <div class="col-sm-10">
                    	<input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $ASTSF_STAT; ?>">
						<?php
							// START : FOR ALL APPROVAL FUNCTION
										if($disableAll == 0)
										{
											if($canApprove == 1)
											{
												$disButton	= 0;
												$sqlCAPPHE	= "tbl_approve_hist WHERE AH_CODE = '$ASTSF_NUM' AND AH_APPROVER = '$DefEmp_ID'";
												$resCAPPHE	= $this->db->count_all($sqlCAPPHE);
												if($resCAPPHE > 0)
													$disButton	= 1;
												?>
													<select name="ASTSF_STAT" id="ASTSF_STAT" class="form-control select2" style="max-width:120px"  <?php if($disButton == 1) { ?> disabled <?php } ?> >
														<option value="0"> -- </option>
														<option value="3"<?php if($ASTSF_STAT == 3) { ?> selected <?php } ?>>Approved</option>
														<option value="4"<?php if($ASTSF_STAT == 4) { ?> selected <?php } ?>>Revised</option>
														<option value="5"<?php if($ASTSF_STAT == 5) { ?> selected <?php } ?>>Rejected</option>
														<option value="6"<?php if($ASTSF_STAT == 6) { ?> selected <?php } ?>>Closed</option>
														<option value="7"<?php if($ASTSF_STAT == 7) { ?> selected <?php } ?> style="display:none">Awaiting</option>
													</select>
												<?php
											}
											else
											{
												?>
													<a href="" class="btn btn-<?php echo $statcoloer; ?> btn-xs">
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
									// END : FOR ALL APPROVAL FUNCTION
						?>
                        <input type="hidden" name="ASTSF_AMOUNT" id="ASTSF_AMOUNT" value="<?php echo $ASTSF_AMOUNT; ?>">
                    </div>
                </div>
                <?php
					if($ASTSF_STAT == 1 || $ASTSF_STAT == 4)
					{
						$theProjCode 	= "$PRJCODE~$ASTSF_REFNO";
						$url_AddAset	= site_url('c_asset/c_tr4n5p3r/p0p_4llM7R/?id='.$this->url_encryption_helper->encode_url($theProjCode));
						?>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label">&nbsp;</label>
                            <div class="col-sm-10">
                                <script>
                                    var url = "<?php echo $url_AddAset;?>";
                                    function selectitem()
                                    {
										/*alert('a')
                                        ASTSF_REFNO	= document.getElementById('ASTSF_REFNO').value;
										alert('b')
                                        if(ASTSF_REFNO == '')
                                        {
                                            alert('Silahkan pilih nama pekerjaan');
                                            document.getElementById('ASTSF_REFNO').focus();
                                            return false;
                                        }
										alert('c')*/
                                        
                                        title = 'Select Item';
                                        w = 1000;
                                        h = 550;
                                        //window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
                                        var left = (screen.width/2)-(w/2);
                                        var top = (screen.height/2)-(h/2);
                                        return window.open(url, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
                                    }
                                </script>
                                <button class="btn btn-success" type="button" onClick="selectitem();">
                                <i class="glyphicon glyphicon-th-list"></i>&nbsp;&nbsp;<?php echo $SelectItem; ?>
                                </button>
                            </div>
                        </div>
                        <?php
					}
				?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-primary">
                        <br>
                        <table width="100%" border="1" id="tbl">
                        	<tr style="background:#CCCCCC">
                              <th width="3%" height="25" style="text-align:left">&nbsp;</th>
                              <th width="10%" style="text-align:center" nowrap><?php echo $ItemCode ?> </th>
                              <th width="42%" style="text-align:center"><?php echo $ItemName ?> </th>
                              <th width="4%" style="text-align:center"><?php echo $Unit ?></th>
                              <th width="8%" style="text-align:center"><?php echo $Volume ?></th>
                              <th width="9%"  style="text-align:center"><?php echo $TsfVol; ?> </th>
                              <th width="24%"  style="text-align:center" nowrap><?php echo $Description ?> </th>
                            </tr>
                            <?php					
                            if($task == 'edit')
                            {
                                $sqlDET	= "SELECT A.ASTSF_NUM, A.ASTSF_CODE, A.ASTSF_DATE, A.PRJCODE, A.PRJCODE_DEST, A.JOBCODEID, 
												A.ITM_CODE, A.ITM_GROUP, A.ITM_NAME, A.ITM_UNIT, A.ITM_VOLM, A.ASTSF_VOLM, 
												A.ASTSF_PRICE, A.ASTSF_DESC
											FROM tbl_asset_tsfd A
											WHERE A.ASTSF_NUM = '$ASTSF_NUM' AND A.PRJCODE = '$PRJCODE'";
                                $resDET = $this->db->query($sqlDET)->result();
                                $i		= 0;
                                $j		= 0;
								
								foreach($resDET as $row) :
									$currentRow  	= ++$i;
									$ASTSF_NUM 		= $row->ASTSF_NUM;
									$ASTSF_CODE 	= $row->ASTSF_CODE;
									$ASTSF_DATE 	= $row->ASTSF_DATE;
									$PRJCODE 		= $row->PRJCODE;
									$PRJCODE_DEST	= $row->PRJCODE_DEST;
									$JOBCODEID 		= $row->JOBCODEID;									
									$ITM_CODE 		= $row->ITM_CODE;
									$ITM_GROUP 		= $row->ITM_GROUP;
									$ITM_NAME 		= $row->ITM_NAME;
									$ITM_UNIT 		= $row->ITM_UNIT;
									$ITM_VOLM 		= $row->ITM_VOLM;
									$ASTSF_VOLM		= $row->ASTSF_VOLM;
									$ASTSF_PRICE	= $row->ASTSF_PRICE;
									$ASTSF_DESC		= $row->ASTSF_DESC;
									
									$sqlITM	= "SELECT ITM_VOLM FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' LIMIT 1";
									$resITM = $this->db->query($sqlITM)->result();
									foreach($resITM as $rowITM) :
										$ITM_VOLM 	= $rowITM->ITM_VOLM;
									endforeach;
									
									// CEK EXISTING ITEM IN DESTINATION
									$sqlITMC= "tbl_item WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE_DEST' AND STATUS = 1";
									$resITMC= $this->db->count_all($sqlITMC);
						
								/*	if ($j==1) {
										echo "<tr class=zebra1>";
										$j++;
									} else {
										echo "<tr class=zebra2>";
										$j--;
									}*/
									?> 
                                    <tr id="tr_<?php echo $currentRow; ?>">
									<td width="3%" height="25" style="text-align:left">
									  	<?php
											if($ASTSF_STAT == 1)
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
								 	<td width="10%" style="text-align:left" nowrap>
									  	<?php echo $ITM_CODE; ?>                                      
                                      	<input type="hidden" id="data<?php echo $currentRow; ?>DEST_EXIST" name="data[<?php echo $currentRow; ?>][DEST_EXIST]" value="<?php echo $resITMC; ?>" class="form-control" style="max-width:300px;">                                      
                                      	<input type="hidden" id="data<?php echo $currentRow; ?>ASTSF_NUM" name="data[<?php echo $currentRow; ?>][ASTSF_NUM]" value="<?php echo $ASTSF_NUM; ?>" class="form-control" style="max-width:300px;">
                                      	<input type="hidden" id="data<?php echo $currentRow; ?>ASTSF_CODE" name="data[<?php echo $currentRow; ?>][ASTSF_CODE]" value="<?php echo $ASTSF_CODE; ?>" class="form-control" style="max-width:300px;">
                                      	<input type="hidden" id="data<?php echo $currentRow; ?>ITM_CODE" name="data[<?php echo $currentRow; ?>][ITM_CODE]" value="<?php echo $ITM_CODE; ?>" class="form-control" style="max-width:300px;">
                                        <input type="hidden" id="data<?php echo $currentRow; ?>JOBCODEID" name="data[<?php echo $currentRow; ?>][JOBCODEID]" value="<?php echo $JOBCODEID; ?>" class="form-control" style="max-width:300px;">
                                        <input type="hidden" id="data<?php echo $currentRow; ?>PRJCODE" name="data[<?php echo $currentRow; ?>][PRJCODE]" value="<?php echo $PRJCODE; ?>" class="form-control" style="max-width:300px;">
                                        <input type="hidden" id="data<?php echo $currentRow; ?>ITM_GROUP" name="data[<?php echo $currentRow; ?>][ITM_GROUP]" value="<?php echo $ITM_GROUP; ?>" class="form-control" style="max-width:300px;">
                                  	</td>
								  	<td style="text-align:left">
										<?php echo $ITM_NAME; ?>
                                        <input type="hidden" id="data<?php echo $currentRow; ?>ITM_NAME" name="data[<?php echo $currentRow; ?>][ITM_NAME]" value="<?php echo $ITM_NAME; ?>" class="form-control" style="max-width:300px;">
                                 	</td>
								  	<td style="text-align:center">
                                    	<?php echo $ITM_UNIT; ?>
                                        <input type="hidden" id="data<?php echo $currentRow; ?>ITM_UNIT" name="data[<?php echo $currentRow; ?>][ITM_UNIT]" value="<?php echo $ITM_UNIT; ?>" class="form-control" style="max-width:300px;">
                                    </td>
								  	<td style="text-align:right">
										<?php echo number_format($ITM_VOLM, 2); ?>
                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][ITM_VOLM]" id="data<?php echo $currentRow; ?>ITM_VOLM" value="<?php echo $ITM_VOLM; ?>" class="form-control" >
                                    </td>
									<td width="9%" style="text-align:right">
                                    	<?php echo number_format($ASTSF_VOLM, 2); ?>
                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][ASTSF_VOLM]" id="data<?php echo $currentRow; ?>ASTSF_VOLM" value="<?php echo $ASTSF_VOLM; ?>" class="form-control" >
                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][ASTSF_PRICE]" id="data<?php echo $currentRow; ?>ASTSF_PRICE" value="<?php echo $ASTSF_PRICE; ?>" class="form-control" >
                                  	</td>
								  	<td width="24%" style="text-align:left">
                                    	<?php echo $ASTSF_DESC; ?>
                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][ASTSF_DESC]" id="data<?php echo $currentRow; ?>ASTSF_DESC" value="<?php echo $ASTSF_DESC; ?>" class="form-control" >
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
								$ISCREATE = 1;
								
							if(($ASTSF_STAT == 2 || $ASTSF_STAT == 7) && $ISAPPROVE == 1 && $canApprove == 1)
							{
								?>
									<button class="btn btn-primary">
									<i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Update; ?>
									</button>&nbsp;
								<?php
							}
							
							$backURL	= site_url('c_asset/c_tr4n5p3r/tr4n5p3r_i4x/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
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
	
	//Date picker
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
  
	<?php
	if($task == 'add')
	{
		?>
		$(document).ready(function()
		{
			setInterval(function(){getNewCode()}, 1000);
		});
		
		function getNewCode()
		{
			var	PRJCODE		= '<?php echo $dataColl; ?>';
			var isManual	= document.getElementById('isManual').checked;
			
			if(window.XMLHttpRequest)
			{
				//code for IE7+,Firefox,Chrome,Opera,Safari
				xmlhttpTask=new XMLHttpRequest();
			}
			else
			{
				xmlhttpTask=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttpTask.onreadystatechange=function()
			{
				if(xmlhttpTask.readyState==4&&xmlhttpTask.status==200)
				{
					if(xmlhttpTask.responseText != '')
					{
						if(isManual == false)
							document.getElementById('<?php echo $dataTarget; ?>').value  = xmlhttpTask.responseText;
					}
					else
					{
						if(isManual == false)
							document.getElementById('<?php echo $dataTarget; ?>').value  = '';
					}
				}
			}
			xmlhttpTask.open("GET","<?php echo base_url().'index.php/__l1y/GetCodeDoc/';?>"+PRJCODE,true);
			xmlhttpTask.send();
		}
		<?php
	}
	?>
  
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
	
	function add_item(strItem) 
	{
		arrItem = strItem.split('|');		
		var objTable, objTR, objTD, intIndex, arrItem;
		var ASTSF_NUMx = "<?php echo $DocNumber; ?>";		
		var ASTSF_CODE = "<?php echo $ASTSF_CODE; ?>";
		var JOBCODEID 	= "<?php echo $ASTSF_REFNO; ?>";
		
		ilvl = arrItem[1];
		
		/*validateDouble(arrItem[0],arrItem[1])
		if(validateDouble(arrItem[0],arrItem[1]))
		{
			alert("Double Item for " + arrItem[0]);
			return;
		}*/
		
		JOBCODEID 		= arrItem[0];		// OK
		PRJCODE 		= arrItem[1];		// OK
		ITM_CODE 		= arrItem[2];		// OK
		ITM_CODE_H 		= arrItem[3];
		ITM_NAME 		= arrItem[4];		// OK
		ITM_DESC 		= arrItem[5];
		ITM_GROUP		= arrItem[6];		// OK
		ITM_CATEG 		= arrItem[7];
		ITM_UNIT 		= arrItem[8];		// OK
		ITM_VOLM 		= arrItem[9];		// OK
		ITM_PRICE 		= arrItem[10];
		
		objTable 		= document.getElementById('tbl');
		intTable 		= objTable.rows.length;
		//alert('intTable = '+intTable)
		//intIndex = parseInt(document.frm.rowCount.value) + 1;
		intIndex = parseInt(objTable.rows.length);
		//intIndex = intTable;
		document.frm.rowCount.value = intIndex;
		
		objTR = objTable.insertRow(intTable);
		objTR.id = 'tr_' + intIndex;
		
		// Checkbox
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = "center";
		objTD.noWrap = true;
		objTD.innerHTML = '<a href="#" onClick="deleteRow('+intIndex+')" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a><input type="hidden" id="chk" name="chk" value="'+intIndex+'" width="10" size="15" readonly class="form-control" style="max-width:300px;">';
		
		// Item Code
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.innerHTML = ''+ITM_CODE+'<input type="hidden" id="data'+intIndex+'ASTSF_CODE" name="data['+intIndex+'][ASTSF_CODE]" value="'+ASTSF_CODE+'" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'ITM_CODE" name="data['+intIndex+'][ITM_CODE]" value="'+ITM_CODE+'" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'JOBCODEID" name="data['+intIndex+'][JOBCODEID]" value="'+JOBCODEID+'" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'PRJCODE" name="data['+intIndex+'][PRJCODE]" value="'+PRJCODE+'" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'ITM_GROUP" name="data['+intIndex+'][ITM_GROUP]" value="'+ITM_GROUP+'" class="form-control" style="max-width:300px;">';
		
		// Item Name
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.noWrap = true;
		objTD.innerHTML = ''+ITM_NAME+'<input type="hidden" name="data['+intIndex+'][ITM_NAME]" id="data'+intIndex+'ITM_NAME" value="'+ITM_NAME+'" class="form-control" >';
		
		// Item Unit
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.noWrap = true;
		objTD.innerHTML = ''+ITM_UNIT+'<input type="hidden" name="data['+intIndex+'][ITM_UNIT]" id="data'+intIndex+'ITM_UNIT" value="'+ITM_UNIT+'" class="form-control" >';
				
		// Volume Stock
		var ITM_VOLMV	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_VOLM)), 2))
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.noWrap = true;
		objTD.innerHTML = ''+ITM_VOLMV+'<input type="hidden" name="data['+intIndex+'][ITM_VOLM]" id="data'+intIndex+'ITM_VOLM" value="'+ITM_VOLM+'" class="form-control" >';
		
		// Transfer Volume
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		//objTD.style.display = 'none';
		objTD.innerHTML = '<input type="text" name="ASTSF_VOLM'+intIndex+'" id="ASTSF_VOLM'+intIndex+'" value="0.0" class="form-control" style="max-width:100px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,'+intIndex+');" ><input type="hidden" name="data['+intIndex+'][ASTSF_VOLM]" id="data'+intIndex+'ASTSF_VOLM" value="0.0" class="form-control" ><input type="hidden" name="data['+intIndex+'][ASTSF_PRICE]" id="data'+intIndex+'ASTSF_PRICE" value="'+ITM_PRICE+'" class="form-control" >';
		
		// Description
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'left';
		objTD.innerHTML = '<input type="text" name="data['+intIndex+'][ASTSF_DESC]" id="data'+intIndex+'ASTSF_DESC" value="" class="form-control" >';
		
		totRow	= parseFloat(intIndex+1);
		document.getElementById('totalrow').value = parseFloat(totRow);
	}
	
	function getConvertion(thisVal1, row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		
		var ASTSF_VOLM		= parseFloat(eval(thisVal1).value.split(",").join(""));
		
		var ITM_VOLM		= parseFloat(document.getElementById('data'+row+'ITM_VOLM').value);
		
		if(ASTSF_VOLM > ITM_VOLM)
		{
			alert('<?php echo $alert4; ?>');
			var ASTSF_VOLM	= parseFloat(ITM_VOLM);
		}
		
		document.getElementById('data'+row+'ASTSF_VOLM').value 	= ASTSF_VOLM;
		document.getElementById('ASTSF_VOLM'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ASTSF_VOLM)),decFormat));
	}
	
	function checkInData(value)
	{
		var totrow 		= document.getElementById('totalrow').value;
		var ASTSF_STAT = document.getElementById('ASTSF_STAT').value;
		
		if(ASTSF_STAT == 0)
		{
			alert('<?php echo $alert3; ?>');
			document.getElementById('ASTSF_STAT').focus();
			return false;
		}
		
		if(ASTSF_STAT == 4)
		{
			ASTSF_NOTE2	= document.getElementById('ASTSF_NOTE2').value;
			if(ASTSF_NOTE2 == '')
			{
				alert('<?php echo $alert5; ?>');
				document.getElementById('ASTSF_NOTE2').focus();
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
</script>
<?php 
$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>