<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 29 Agustus 2018
 * File Name	= fpa_inb_form.php
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
	
$FPA_NUM 	= $default['FPA_NUM'];
$DocNumber	= $default['FPA_NUM'];
$FPA_CODE 	= $default['FPA_CODE'];
$FPA_DATE 	= $default['FPA_DATE'];
$FPA_DATE	= date('m/d/Y',strtotime($FPA_DATE));
$FPA_TSFD 	= $default['FPA_TSFD'];
$FPA_TSFD	= date('m/d/Y',strtotime($FPA_TSFD));
$PRJCODE	= $default['PRJCODE'];
$FPA_CATEG 	= $default['FPA_CATEG'];
$JOBCODEID 	= $default['JOBCODEID'];
$JOBCODE1	= $JOBCODEID;
$FPA_NOTE 	= $default['FPA_NOTE'];
$FPA_NOTE2 	= $default['FPA_NOTE2'];
$FPA_VALUE	= $default['FPA_VALUE'];
$FPA_STAT 	= $default['FPA_STAT'];
$FPA_MEMO 	= $default['FPA_MEMO'];
$PRJNAME 	= $default['PRJNAME'];
$Patt_Year 	= $default['Patt_Year'];
$Patt_Month = $default['Patt_Month'];
$Patt_Date 	= $default['Patt_Date'];
$Patt_Number= $default['Patt_Number'];

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/style.css'; ?>");</style>
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
		if($TranslCode == 'NoFPA')$NoFPA = $LangTransl;
		if($TranslCode == 'TFDate')$TFDate = $LangTransl;
		if($TranslCode == 'FPACode')$FPACode = $LangTransl;
		if($TranslCode == 'Notes')$Notes = $LangTransl;
		if($TranslCode == 'Date')$Date = $LangTransl;
		if($TranslCode == 'Project')$Project = $LangTransl;
		if($TranslCode == 'Status')$Status = $LangTransl;
		if($TranslCode == 'AlertRevisionorReject')$AlertRevisionorReject = $LangTransl;
		if($TranslCode == 'New')$New = $LangTransl;
		if($TranslCode == 'Confirm')$Confirm = $LangTransl;
		if($TranslCode == 'Close')$Close = $LangTransl;
		if($TranslCode == 'Category')$Category = $LangTransl;
		if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
		if($TranslCode == 'ItemName')$ItemName = $LangTransl;
		if($TranslCode == 'BudgetQty')$BudgetQty = $LangTransl;
		if($TranslCode == 'Planning')$Planning = $LangTransl;
		if($TranslCode == 'Requested')$Requested = $LangTransl;
		if($TranslCode == 'RequestNow')$RequestNow = $LangTransl;
		if($TranslCode == 'Quantity')$Quantity = $LangTransl;
		if($TranslCode == 'Unit')$Unit = $LangTransl;
		if($TranslCode == 'Primary')$Primary = $LangTransl;
		if($TranslCode == 'Secondary')$Secondary = $LangTransl;
		if($TranslCode == 'Remarks')$Remarks = $LangTransl;
		if($TranslCode == 'AddItem')$AddItem = $LangTransl;
		if($TranslCode == 'JobName')$JobName = $LangTransl;
		if($TranslCode == 'ItemQty')$ItemQty = $LangTransl;
		if($TranslCode == 'EndDate')$EndDate = $LangTransl;
		if($TranslCode == 'ApproverNotes')$ApproverNotes = $LangTransl;
		if($TranslCode == 'Approve')$Approve = $LangTransl;
		if($TranslCode == 'Approver')$Approver = $LangTransl;
		if($TranslCode == 'Approved')$Approved = $LangTransl;
		if($TranslCode == 'Approval')$Approval = $LangTransl;
		if($TranslCode == 'NotYetApproved')$NotYetApproved = $LangTransl;
		if($TranslCode == 'Awaiting')$Awaiting = $LangTransl;
		if($TranslCode == 'Price')$Price = $LangTransl;
		if($TranslCode == 'Supplier')$Supplier = $LangTransl;
		if($TranslCode == 'Tax')$Tax = $LangTransl;
		if($TranslCode == 'Amount')$Amount = $LangTransl;
	endforeach;
	
	if($LangID == 'IND')
	{
		$subTitleH	= "Persetujuan";
		$subTitleD	= "surat perintah kerja";
		$alert1		= 'Silahkan pilih status Persetujuan FPA.';
	}
	else
	{
		$subTitleH	= "Approval";
		$subTitleD	= "work order";
		$alert1		= 'Please select FPA Approval status.';
	}
	
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
			
			$sqlC_App	= "tbl_approve_hist WHERE AH_CODE = '$FPA_NUM'";
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
			$APPROVE_AMOUNT = $FPA_VALUE;
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
	
	$secAddURL	= site_url('c_project/c_f180p0/add/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
	$secGenCode	= base_url().'index.php/c_project/c_f180p0/genCode/'; // Generate Code
?>

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>
    <?php echo $h1_title; ?>
    <small><?php echo $h2_title; ?></small>  </h1>
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
                        <input type="hidden" name="WODate" id="WODate" value="">
                    </td>
                    <td><a class="tombol-date" id="dateClass">Simpan</a></td>
                </tr>
            </table>
        </form>
        <form class="form-horizontal" name="frm" method="post" action="<?php echo $form_action; ?>" enctype="multipart/form-data" onSubmit="return checkInp()">
                <input type="hidden" name="IS_LAST" id="IS_LAST" value="<?php echo $IS_LAST; ?>">
                <input type="hidden" name="APP_LEVEL" id="APP_LEVEL" value="<?php echo $APP_LEVEL; ?>">
                <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
                <input type="hidden" name="Patt_Number" id="Patt_Number" value="<?php echo $Patt_Number; ?>" />
                <input type="Hidden" name="rowCount" id="rowCount" value="0">
            	<div class="form-group" style="display:none">
                    <label for="inputName" class="col-sm-2 control-label"><?php echo $NoFPA; ?></label>
                    <div class="col-sm-10">
                    	<input type="text" class="form-control" style="max-width:180px;text-align:left" name="FPA_NUM1" id="FPA_NUM1" size="30" value="<?php echo $DocNumber; ?>" disabled />
                    	<input type="hidden" class="form-control" style="max-width:350px;text-align:right" name="FPA_NUM" id="FPA_NUM" size="30" value="<?php echo $DocNumber; ?>" />
                    </div>
                </div>
            	<div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label"><?php echo $FPACode; ?></label>
                    <div class="col-sm-10">
                    	<input type="hidden" class="form-control" style="min-width:110px; max-width:150px; text-align:left" id="FPA_CODE" name="FPA_CODE" size="5" value="<?php echo $FPA_CODE; ?>" />
                    	<input type="text" class="form-control" style="min-width:110px; max-width:150px; text-align:left" id="FPA_CODE1" name="FPA_CODE1" size="5" value="<?php echo $FPA_CODE; ?>" disabled />
                    </div>
                </div>
            	<div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Date; ?></label>
                    <div class="col-sm-10">
                    	<div class="input-group date">
                        <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>&nbsp;</div><input type="text" name="FPA_DATE" class="form-control pull-left" id="datepicker" value="<?php echo $FPA_DATE; ?>" style="width:106px" disabled></div>
                    </div>
                </div>
            	<div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label"><?php echo $TFDate; ?></label>
                    <div class="col-sm-10">
                    	<div class="input-group date">
                        <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>&nbsp;</div><input type="text" name="FPA_TSFD" class="form-control pull-left" id="datepicker1" value="<?php echo $FPA_TSFD; ?>" style="width:106px" onChange="getFPA_NUM(this.value)" disabled></div>
                    </div>
                </div>
            	<div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Category; ?></label>
                    <div class="col-sm-10">
                    	<select name="FPA_CATEG1" id="FPA_CATEG1" class="form-control" style="max-width:150px" disabled>
                          <option value="">--- None ---</option>
                          <option value="MDR" <?php if($FPA_CATEG == 'MDR') { ?> selected <?php } ?>> Mandor</option>
                          <option value="SUB" <?php if($FPA_CATEG == 'SUB') { ?> selected <?php } ?>> Sub Contractor</option>
                        </select>
                        <input type="hidden" class="form-control" style="max-width:350px;text-align:right" name="FPA_CATEG" id="FPA_CATEG" size="30" value="<?php echo $FPA_CATEG; ?>" />
                    </div>
                </div>
            	<div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Project; ?></label>
                    <div class="col-sm-10">
                        <input type="hidden" class="form-control" style="max-width:350px;text-align:right" name="PRJCODE" id="PRJCODE" size="30" value="<?php echo $PRJCODE; ?>" />
                    	<select name="PRJCODE" id="PRJCODE" class="form-control" style="max-width:350px" onChange="chooseProject()" disabled>
                          <option value="none">--- None ---</option>
                          <?php
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
                    	<input type="hidden" class="form-control" style="max-width:350px;text-align:right" id="JOBCODEID" name="JOBCODEID" size="20" value="<?php echo $JOBCODEID; ?>" />                 
                    	<select name="PR_REFNO[]" id="PR_REFNO" class="form-control select2" multiple="multiple" data-placeholder="&nbsp;&nbsp;&nbsp;<?php echo $JobName; ?>" style="max-width:350px;" onBlur="selJOB(this.value)" disabled>
							<?php
                                $Disabled_1	= 0;
                                $sqlJob_1	= "SELECT JOBCODEID, JOBPARENT, JOBLEV, JOBDESC FROM tbl_joblist WHERE ISHEADER = '1'";
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
									
									$JIDExplode = explode('~', $JOBCODEID);
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
									
                                    $sqlC_2			= "tbl_joblist WHERE JOBPARENT = '$JOBCODEID_1' AND ISHEADER = '1'";
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
                                endforeach;
                            ?>
                        </select>
                    </div>
                </div>
            	<div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Notes; ?></label>
                    <div class="col-sm-10">
                    	<textarea name="FPA_NOTE" class="form-control" style="max-width:350px; display:none" id="FPA_NOTE" cols="30"><?php echo $FPA_NOTE; ?></textarea>
                    	<textarea name="FPA_NOTE1" class="form-control" style="max-width:350px;" id="FPA_NOTE1" cols="30" disabled><?php echo $FPA_NOTE; ?></textarea>
                    </div>
                </div>
            	<div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label"><?php echo $ApproverNotes; ?></label>
                    <div class="col-sm-10">
                    	<textarea name="FPA_NOTE2" class="form-control" style="max-width:350px;" id="FPA_NOTE2" cols="30"><?php echo $FPA_NOTE2; ?></textarea>                        
                    </div>
                </div>
            	<div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Status; ?></label>
                    <div class="col-sm-10">
                    	<input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $FPA_STAT; ?>">
						<?php
							//echo "disableAll == $disableAll = $canApprove";
							if($disableAll == 0)
							{
								if($canApprove == 1)
								{
									$disButton	= 0;
									$sqlCAPPHE	= "tbl_approve_hist WHERE AH_CODE = '$FPA_NUM' AND AH_APPROVER = '$DefEmp_ID'";
									$resCAPPHE	= $this->db->count_all($sqlCAPPHE);
									if($resCAPPHE > 0)
										$disButton	= 1;
									?>
										<select name="FPA_STAT" id="FPA_STAT" class="form-control" style="max-width:120px" onChange="selStat(this.value)" <?php if($disButton == 1) { ?> disabled <?php } ?> >
											<option value="0"> -- </option>
											<option value="3"<?php if($FPA_STAT == 3) { ?> selected <?php } ?>>Approve</option>
											<option value="4"<?php if($FPA_STAT == 4) { ?> selected <?php } ?>>Revise</option>
											<option value="5"<?php if($FPA_STAT == 5) { ?> selected <?php } ?>>Reject</option>
											<option value="6"<?php if($FPA_STAT == 6) { ?> selected <?php } ?>>Close</option>
											<option value="7"<?php if($FPA_STAT == 7) { ?> selected <?php } ?>>Awaiting</option>
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
									<a href="" class="btn btn-danger btn-xs" title="ssss">
										Step approval not set;
									</a>
								<?php
							}
							$theProjCode 	= $PRJCODE;
                        	$url_AddItem	= site_url('c_project/c_f180p0/popupallitem/?id='.$this->url_encryption_helper->encode_url($theProjCode));
                        ?>
                    </div>
                </div>
                <div class="form-group" style="display:none">
                    <label for="inputName" class="col-sm-2 control-label">&nbsp;</label>
                    <div class="col-sm-10">
                        <script>
                            var url = "<?php echo $url_AddItem;?>";
                            function selectitem()
                            {
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
                        <i class="cus-add-item-16x16"></i>&nbsp;&nbsp;<?php echo $AddItem; ?>
                        </button>
                        </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-primary">
                        <br>
                        <table width="100%" border="1" id="tbl">
                        	<tr style="background:#CCCCCC">
                              <th width="2%" height="25" rowspan="2" style="text-align:left">&nbsp;</th>
                              <th width="2%" rowspan="2" style="text-align:center; display:none"><?php echo $ItemCode ?> </th>
                              <th width="30%" rowspan="2" style="text-align:center"><?php echo $ItemName ?> </th>
                              <th colspan="4" style="text-align:center"><?php echo $ItemQty; ?> </th>
                              <th rowspan="2" style="text-align:center"><?php echo $Unit ?> </th>
                              <th rowspan="2" style="text-align:center"><span style="text-align:center;"><?php echo $Amount ?></span></th>
                              <th rowspan="2" style="text-align:center"><?php echo $Tax ?></th>
                              <th width="13%" rowspan="2" style="text-align:center;"><span style="text-align:center">Total</span></th>
                          	</tr>
                            <tr style="background:#CCCCCC">
                              <th style="text-align:center;"><?php echo $Planning; ?> </th>
                              <th style="text-align:center;"><?php echo $Requested; ?> </th>
                              <th style="text-align:center"><?php echo $RequestNow; ?></th>
                              <th style="text-align:center"><?php echo $Price; ?></th>
                            </tr>
                            <?php					
                            if($task == 'edit')
                            {
                                $sqlDET	= "SELECT A.*
											FROM tbl_fpa_detail A
											WHERE A.FPA_NUM = '$FPA_NUM' 
												AND A.PRJCODE = '$PRJCODE'";
                                $result = $this->db->query($sqlDET)->result();
                                $i		= 0;
                                $j		= 0;
								
								foreach($result as $row) :
									$currentRow  	= ++$i;
									$FPA_NUM 		= $row->FPA_NUM;
									$FPA_CODE 		= $row->FPA_CODE;
									$FPA_DATE 		= $row->FPA_DATE;
									$PRJCODE 		= $row->PRJCODE;
									$JOBCODEDET		= $row->JOBCODEDET;
									$JOBCODEID 		= $row->JOBCODEID;
									$ITM_CODE 		= $row->ITM_CODE;
									// GET ITM_NAME
									$ITM_NAME		= '';
									$sqlITMNM		= "SELECT ITM_NAME FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' 
														AND PRJCODE = '$PRJCODE' LIMIT 1";
									$resITMNM		= $this->db->query($sqlITMNM)->result();
									foreach($resITMNM as $rowITMNM) :
										$ITM_NAME	= $rowITMNM->ITM_NAME;
									endforeach;
									$SNCODE 		= $row->SNCODE;
									$ITM_UNIT 		= $row->ITM_UNIT;
									$FPA_VOLM 		= $row->FPA_VOLM;
									$FPA_VOLM2 		= $row->FPA_VOLM2;
									$ITM_PRICE 		= $row->ITM_PRICE;
									$FPA_TOTAL 		= $row->FPA_TOTAL;
									$FPA_DESC 		= $row->FPA_DESC;
									$ITM_BUDG		= $row->ITM_BUDG;
									$TAXCODE1		= $row->TAXCODE1;
									$TAXCODE2		= $row->TAXCODE2;
									$TAXPRICE1		= $row->TAXPRICE1;
									$TAXPRICE2		= $row->TAXPRICE2;
									$FPA_TOTAL2		= $row->FPA_TOTAL2;
									if($FPA_TOTAL2 == '')
										$FPA_TOTAL2	= 0;
										
									$itemConvertion	= 1;
						
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
										if($FPA_STAT == 1)
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
                                    	<!-- Checkbox -->
                                 	</td>
								 	<td width="2%" style="text-align:left; display:none"> <!-- Item Code -->
									  	<?php echo $ITM_CODE; ?>                                      
                                      	<input type="hidden" name="data[<?php echo $currentRow; ?>][FPA_NUM]" id="data<?php echo $currentRow; ?>FPA_NUM" value="<?php echo $FPA_NUM; ?>" class="form-control" style="max-width:300px;">
                                      	<input type="hidden" name="data[<?php echo $currentRow; ?>][FPA_CODE]" id="data<?php echo $currentRow; ?>FPA_CODE" value="<?php echo $FPA_CODE; ?>" class="form-control" style="max-width:300px;">
                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][JOBCODEDET]" id="data<?php echo $currentRow; ?>JOBCODEDET" value="<?php echo $JOBCODEDET; ?>" class="form-control" >
                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][JOBCODEID]" id="data<?php echo $currentRow; ?>JOBCODEID" value="<?php echo $JOBCODEID; ?>" class="form-control" >
                                  	</td>
								  	<td width="30%" style="text-align:left">
										<?php echo $ITM_NAME; ?>
                                      	<input type="hidden" id="data<?php echo $currentRow; ?>ITM_CODE" name="data[<?php echo $currentRow; ?>][ITM_CODE]" value="<?php echo $ITM_CODE; ?>" class="form-control" style="max-width:300px;">
                                      	<input type="hidden" id="data<?php echo $currentRow; ?>SNCODE" name="data[<?php echo $currentRow; ?>][SNCODE]" value="<?php echo $SNCODE; ?>" class="form-control" style="max-width:300px;">
                                        <!-- Item Name -->
                                 	</td>
									<?php
										// CARI TOTAL WORKED BUDGET APPROVED
										$TOTPRAMOUNT	= 0;
										$TOTPRQTY		= 0;
										$sqlTOTBUDG		= "SELECT SUM(A.FPA_VOLM * A.ITM_PRICE) AS TOTWOAMOUNT, 
																SUM(A.FPA_VOLM) AS TOTWOQTY 
															FROM tbl_fpa_detail A
																INNER JOIN tbl_fpa_header B ON A.FPA_NUM = B.FPA_NUM
																	AND B.PRJCODE = '$PRJCODE'
															WHERE A.ITM_CODE = '$ITM_CODE' AND A.PRJCODE = '$PRJCODE' 
																AND A.JOBCODEDET = '$JOBCODEDET' AND B.FPA_STAT = '3'";
										$resTOTBUDG		= $this->db->query($sqlTOTBUDG)->result();
										foreach($resTOTBUDG as $rowTOTBUDG) :
											$TOTWOAMOUNT	= $rowTOTBUDG->TOTWOAMOUNT;
											$TOTWOQTY		= $rowTOTBUDG->TOTWOQTY;
										endforeach;
										
										$TOT_USEBUDG	= $TOTPRAMOUNT;					// 15
										$ITM_BUDG		= $row->ITM_BUDG;				// 16
										$UNITTYPE		= strtoupper($ITM_UNIT);
										
										if($ITM_BUDG == '')
											$ITM_BUDG	= 0;	
											
										$sqlITM		= "SELECT DISTINCT ITM_VOLM, ITM_BUDG AS ITM_TOTALP
															FROM tbl_joblist_detail 
														WHERE PRJCODE = '$PRJCODE' AND JOBCODEDET = '$JOBCODEDET' LIMIT 1";
														
										$ITM_BUDQTY		= 0;
										$ITM_TOTALP		= 0;
										$resITM			= $this->db->query($sqlITM)->result();
										foreach($resITM as $rowITM) :
											$ITM_BUDQTY	= $rowITM->ITM_VOLM;
											$ITM_TOTALP	= $rowITM->ITM_TOTALP;
										endforeach;
										
										$TOTPRQTY		= $TOTWOQTY;
										if($UNITTYPE == 'LS')
										{
											$ITM_BUDG	= $ITM_TOTALP;
											$ITM_BUDQTY	= $ITM_TOTALP;
											$TOTPRQTY	= $TOTWOAMOUNT;
										}
										
										// SISA QTY PR
										$REMPRQTY		= $ITM_BUDQTY - $TOTPRQTY;
									?>
									<td width="8%" style="text-align:right"> <!-- Item Bdget -->
                                        <input type="text" class="form-control" style="min-width:100px; max-width:100px; text-align:right" name="ITM_BUDGQTYx<?php echo $currentRow; ?>" id="ITM_BUDGQTYx<?php echo $currentRow; ?>" value="<?php echo number_format($ITM_BUDQTY, $decFormat); ?>" size="10" disabled >
                                        <input type="hidden" style="text-align:right" name="ITM_BUDGQTY<?php echo $currentRow; ?>" id="ITM_BUDGQTY<?php echo $currentRow; ?>" value="<?php echo $ITM_BUDQTY; ?>" >
                                        <input type="hidden" id="data<?php echo $currentRow; ?>ITM_BUDG" name="data[<?php echo $currentRow; ?>][ITM_BUDG]" value="<?php echo $ITM_BUDG; ?>">
                                  	</td>
								  	<td width="8%" style="text-align:right">  <!-- Item Requested FOR INFORMATION ONLY -->
                                        <input type="text" class="form-control" style="min-width:100px; max-width:100px; text-align:right" name="TOTWOQTY<?php echo $currentRow; ?>" id="TOTWOQTYx<?php echo $currentRow; ?>" value="<?php print number_format($TOTPRQTY, $decFormat); ?>" size="10" disabled >
                                        <input type="hidden" class="form-control" style="min-width:100px; max-width:100px; text-align:right" name="ITM_REQUESTED<?php echo $currentRow; ?>" id="ITM_REQUESTED<?php echo $currentRow; ?>" value="<?php print $TOTWOAMOUNT; ?>" >
                                        <input type="hidden" class="form-control" style="text-align:right" name="TOTWOQTY<?php echo $currentRow; ?>" id="TOTWOQTY<?php echo $currentRow; ?>" value="<?php print $TOTWOQTY; ?>" >
                                 	</td>
                                     <td width="8%" style="text-align:right"> <!-- Item Request Now -- PR_VOLM -->
                                        <input type="text" name="FPA_VOLM<?php echo $currentRow; ?>" id="FPA_VOLM<?php echo $currentRow; ?>" value="<?php echo number_format($FPA_VOLM, $decFormat); ?>" class="form-control" style="min-width:100px; max-width:100px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,<?php echo $currentRow; ?>);" disabled >
                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][FPA_VOLM]" id="data<?php echo $currentRow; ?>FPA_VOLM" value="<?php echo $FPA_VOLM; ?>" class="form-control" style="max-width:300px;" >                                    
                                        </td>
                                     <td width="7%" style="text-align:right">
                                        <input type="text" name="ITM_PRICE<?php echo $currentRow; ?>" id="ITM_PRICE<?php echo $currentRow; ?>" value="<?php echo number_format($ITM_PRICE, $decFormat); ?>" class="form-control" style="min-width:100px; max-width:100px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion_1(this,<?php echo $currentRow; ?>);" disabled >
                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][ITM_PRICE]" id="data<?php echo $currentRow; ?>ITM_PRICE" value="<?php echo $ITM_PRICE; ?>" class="form-control" style="min-width:100px; max-width:100px;" >
                                        <input type="hidden" style="text-align:right" name="itemConvertion<?php echo $currentRow; ?>" id="itemConvertion<?php echo $currentRow; ?>" value="<?php echo $itemConvertion; ?>" >                                    
                                     </td>
								  	<td width="4%" nowrap style="text-align:center">
									  <?php echo $ITM_UNIT; ?>
                                    	<input type="hidden" name="data[<?php echo $currentRow; ?>][ITM_UNIT]" id="data<?php echo $currentRow; ?>ITM_UNIT" value="<?php echo $ITM_UNIT; ?>" class="form-control" style="max-width:300px;" >
                                    <!-- Item Unit Type -- ITM_UNIT --></td>
								  	<td width="3%" nowrap style="text-align:center">
                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][FPA_TOTAL]" id="data<?php echo $currentRow; ?>FPA_TOTAL" value="<?php echo $FPA_TOTAL; ?>" class="form-control" style="max-width:150px; text-align:right" onKeyPress="return isIntOnlyNew(event);" >
                                    	<input type="text" class="form-control" style="min-width:100px; max-width:100px; text-align:right" name="FPA_TOTAL<?php echo $currentRow; ?>" id="FPA_TOTAL<?php echo $currentRow; ?>" value="<?php print number_format($FPA_TOTAL, $decFormat); ?>" size="10" disabled >
                                    </td>
								  	<td width="15%" nowrap style="text-align:center">
                                        <select name="data[<?php echo $currentRow; ?>][TAXCODE1]" id="TAXCODE1<?php echo $currentRow; ?>" class="form-control" style="max-width:150px" onChange="getConvertion(this,<?php echo $currentRow; ?>);">
                                        	<option value=""> no tax </option>
                                        	<option value="TAX01" <?php if($TAXCODE1 == 'TAX01') { ?> selected <?php } ?>>PPn 10%</option>
                                        	<option value="TAX02" <?php if($TAXCODE1 == 'TAX02') { ?> selected <?php } ?>>PPh 3%</option>
                                        </select>
                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][TAXPRICE1]" id="data<?php echo $currentRow; ?>TAXPRICE1" size="20" value="<?php echo $TAXPRICE1; ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:left">
                                    </td>
								  	<td width="13%" style="text-align:center;">
                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][FPA_TOTAL2]" id="data<?php echo $currentRow; ?>FPA_TOTAL2" value="<?php echo $FPA_TOTAL2; ?>" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" >
                                        <input type="text" name="data<?php echo $currentRow; ?>FPA_TOTAL2A" id="FPA_TOTAL2A<?php echo $currentRow; ?>" value="<?php echo number_format($FPA_TOTAL2, 2); ?>" class="form-control" style="min-width:130px; max-width:130px; text-align:right;" onKeyPress="return isIntOnlyNew(event);" disabled >
                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][FPA_DESC]" id="data<?php echo $currentRow; ?>FPA_DESC" size="20" value="" class="form-control" style="min-width:110px; max-width:100px; text-align:left">
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
            	<div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label">Total - FPA</label>
                    <div class="col-sm-10">
                    	<input type="hidden" name="FPA_VALUE" id="FPA_VALUE" class="form-control" style="max-width:150px; text-align:right" value="<?php echo $FPA_VALUE; ?>"> 
                    	<input type="text" name="FPA_VALUE1" id="FPA_VALUE1" class="form-control" style="max-width:150px; text-align:right" value="<?php echo number_format($FPA_VALUE, 2); ?>" disabled>                        
                    </div>
                </div>
                <br>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <?php
							if($disableAll == 0)
							{
								if(($FPA_STAT == 2 || $FPA_STAT == 7) && $canApprove == 1)
								{
									?>
										<button class="btn btn-primary" >
										<i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Update; ?>
										</button>&nbsp;
									<?php
								}
							}
							$backURL	= site_url('c_project/c_f180p0/gallS180d0bpk_1n2/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
							echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;'.$Cancel.'</button>');
                        ?>
                    </div>
                </div>
				<?php
					$DOC_NUM	= $FPA_NUM;
                    $sqlCAPPH	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM'";
                    $resCAPPH	= $this->db->count_all($sqlCAPPH);
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
                                if($APPROVER_1 != '')
                                {
                                    $boxCol_1	= "red";
                                    $sqlCAPPH_1	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_1'";
                                    $resCAPPH_1	= $this->db->count_all($sqlCAPPH_1);
                                    if($resCAPPH_1 > 0)
                                    {
                                        $boxCol_1	= "green";
                                        $class		= "glyphicon glyphicon-ok-sign";
                                        
                                        $sqlAPPH_1	= "SELECT AH_APPROVED FROM tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_1'";
                                        $resAPPH_1	= $this->db->query($sqlAPPH_1)->result();
                                        foreach($resAPPH_1 as $rowAPPH_1):
                                            $APPROVED_1	= $rowAPPH_1->AH_APPROVED;
                                        endforeach;
                                    }
                                    elseif($resCAPPH_1 == 0)
                                    {
                                        $Approver	= $NotYetApproved;
                                        $class		= "glyphicon glyphicon-remove-sign";
                                        $APPROVED_1	= "Not Set";
                                    }
                                ?>
                                    <div class="col-md-3">
                                        <div class="info-box bg-<?php echo $boxCol_1; ?>">
                                            <span class="info-box-icon"><i class="<?php echo $class; ?>"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text"><?php echo $Approver; ?></span>
                                                <span class="info-box-number"><?php echo $EMPN_1; ?></span>
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
                                if($APPROVER_2 != '')
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
                                    }
                                    
                                    if($resCAPPH_1 < 1)
                                    {
                                        $Approver	= $Awaiting;
                                        $boxCol_2	= "yellow";
                                        $class		= "glyphicon glyphicon-info-sign";
                                    }
                                ?>
                                    <div class="col-md-3">
                                        <div class="info-box bg-<?php echo $boxCol_2; ?>">
                                            <span class="info-box-icon"><i class="<?php echo $class; ?>"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text"><?php echo $Approver; ?></span>
                                                <span class="info-box-number"><?php echo $EMPN_2; ?></span>
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
                                if($APPROVER_3 != '')
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
                                    }
                                    
                                    if($resCAPPH_2 < 2)
                                    {
                                        $Approver	= $Awaiting;
                                        $boxCol_3	= "yellow";
                                        $class		= "glyphicon glyphicon-info-sign";
                                    }
                                ?>
                                    <div class="col-md-3">
                                        <div class="info-box bg-<?php echo $boxCol_3; ?>">
                                            <span class="info-box-icon"><i class="<?php echo $class; ?>"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text"><?php echo $Approver; ?></span>
                                                <span class="info-box-number"><?php echo $EMPN_3; ?></span>
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
                                if($APPROVER_4 != '')
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
                                    }
                                    
                                    if($resCAPPH_3 < 3)
                                    {
                                        $Approver	= $Awaiting;
                                        $boxCol_4	= "yellow";
                                        $class		= "glyphicon glyphicon-info-sign";
                                    }
                                ?>
                                    <div class="col-md-3">
                                        <div class="info-box bg-<?php echo $boxCol_4; ?>">
                                            <span class="info-box-icon"><i class="<?php echo $class; ?>"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text"><?php echo $Approver; ?></span>
                                                <span class="info-box-number"><?php echo $EMPN_4; ?></span>
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
                                if($APPROVER_5 != '')
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
                                    }
                                    
                                    if($resCAPPH_4 < 4)
                                    {
                                        $Approver	= $Awaiting;
                                        $boxCol_5	= "yellow";
                                        $class		= "glyphicon glyphicon-info-sign";
                                    }
                                ?>
                                    <div class="col-md-3">
                                        <div class="info-box bg-<?php echo $boxCol_5; ?>">
                                            <span class="info-box-icon"><i class="<?php echo $class; ?>"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text"><?php echo $Approver; ?></span>
                                                <span class="info-box-number"><?php echo $EMPN_5; ?></span>
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
	
	function add_item(strItem) 
	{
		arrItem = strItem.split('|');		
		var objTable, objTR, objTD, intIndex, arrItem;
		var FPA_NUMx 	= "<?php echo $DocNumber; ?>";
		
		var FPA_CODEx 	= "<?php echo $FPA_CODE; ?>";
		ilvl = arrItem[1];
		
		/*validateDouble(arrItem[0],arrItem[1])
		if(validateDouble(arrItem[0],arrItem[1]))
		{
			alert("Double Item for " + arrItem[0]);
			return;
		}*/
		
		JOBCODEDET 		= arrItem[0];
		JOBCODEID 		= arrItem[1];
		JOBCODE 		= arrItem[2];
		PRJCODE 		= arrItem[3];
		ITM_CODE 		= arrItem[4];
		ITM_NAME 		= arrItem[5];
		ITM_SN			= arrItem[6];
		ITM_UNIT 		= arrItem[7];
		ITM_PRICE 		= arrItem[8];
		ITM_VOLM 		= arrItem[9];
		ITM_BUDGQTY		= ITM_VOLM;
		ITM_STOCK 		= arrItem[10];
		ITM_USED 		= arrItem[11];
		itemConvertion	= arrItem[12];
		TotPrice		= arrItem[13];
		tempTotMax		= arrItem[14];
		TOT_USEBUDG		= arrItem[15];
		ITM_BUDG		= arrItem[16];
		TOT_USEDQTY		= arrItem[17];
		FPA_QTY			= arrItem[18];
		FPA_AMOUNT		= arrItem[19];
		OPN_QTY			= arrItem[20];
		OPN_AMOUNT		= arrItem[21];
		
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
		objTD.innerHTML = '<a href="#" onClick="deleteRow('+intIndex+')" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a><input type="hidden" id="chk" name="chk" value="'+intIndex+'" width="10" size="15" readonly class="form-control" style="max-width:300px;"><input type="hidden" name="totalrow" id="totalrow" value="'+intIndex+'">';
		
		// Item Code
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'right';
		objTD.innerHTML = ''+ITM_CODE+'<input type="hidden" id="data'+intIndex+'FPA_NUM" name="data['+intIndex+'][FPA_NUM]" value="'+FPA_NUMx+'" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'FPA_CODE" name="data['+intIndex+'][FPA_CODE]" value="'+FPA_CODEx+'" class="form-control" style="max-width:300px;"><input type="hidden" name="data['+intIndex+'][JOBCODEDET]" id="data'+intIndex+'JOBCODEDET" value="'+JOBCODEDET+'" class="form-control" ><input type="hidden" name="data['+intIndex+'][JOBCODEID]" id="data'+intIndex+'JOBCODEID" value="'+JOBCODEID+'" class="form-control" >';
		
		// Item Name
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.noWrap = true;
		objTD.innerHTML = ''+ITM_NAME+'<input type="hidden" id="data'+intIndex+'ITM_CODE" name="data['+intIndex+'][ITM_CODE]" value="'+ITM_CODE+'" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'SNCODE" name="data['+intIndex+'][SNCODE]" value="'+ITM_SN+'" class="form-control" style="max-width:300px;">';
		
		// Item Budget
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" class="form-control" style="min-width:100px; max-width:300px; text-align:right" name="ITM_BUDGQTYx'+intIndex+'" id="ITM_BUDGQTYx'+intIndex+'" value="'+ITM_BUDGQTY+'" disabled ><input type="hidden" style="text-align:right" name="ITM_BUDGQTY'+intIndex+'" id="ITM_BUDGQTY'+intIndex+'" value="'+ITM_BUDGQTY+'" ><input type="hidden" id="data'+intIndex+'ITM_BUDG" name="data['+intIndex+'][ITM_BUDG]" value="'+ITM_BUDG+'">';
		
		// Item Worked FOR INFORMATION ONLY
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="hidden" class="form-control" style="min-width:100px; max-width:300px; text-align:right" name="ITM_REQUESTED'+intIndex+'" id="ITM_REQUESTED'+intIndex+'" value="'+TOT_USEBUDG+'" ><input type="hidden" class="form-control" style="min-width:110px; max-width:300px; text-align:right" name="ITM_REQUESTEDx'+intIndex+'" id="ITM_REQUESTEDx'+intIndex+'" value="'+TOT_USEBUDG+'" ><input type="hidden" class="form-control" style="text-align:right" name="TOTWOQTY'+intIndex+'" id="TOTWOQTY'+intIndex+'" value="'+TOT_USEDQTY+'" ><input type="text" class="form-control" style="min-width:110px; max-width:300px; text-align:right" name="TOTWOQTY'+intIndex+'" id="TOTWOQTYx'+intIndex+'" value="'+TOT_USEDQTY+'" disabled >';
		
		// Item Worked Now -- FPA_VOLM
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" name="FPA_VOLM'+intIndex+'" id="FPA_VOLM'+intIndex+'" value="0.00" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,'+intIndex+');" ><input type="hidden" name="data['+intIndex+'][FPA_VOLM]" id="data'+intIndex+'FPA_VOLM" value="0.00" class="form-control" style="max-width:300px;" ><input type="hidden" name="data['+intIndex+'][ITM_PRICE]" id="data'+intIndex+'ITM_PRICE" value="'+ITM_PRICE+'" class="form-control" style="max-width:300px;" ><input type="hidden" name="data['+intIndex+'][FPA_TOTAL]" id="data'+intIndex+'FPA_TOTAL" value="'+TotPrice+'" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" ><input type="hidden" style="text-align:right" name="itemConvertion'+intIndex+'" id="itemConvertion'+intIndex+'" value="'+itemConvertion+'" >';
		
		// Item Unit Type -- ITM_UNIT
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = ''+ITM_UNIT+'<input type="hidden" name="data['+intIndex+'][ITM_UNIT]" id="data'+intIndex+'ITM_UNIT" value="'+ITM_UNIT+'" class="form-control" style="max-width:300px;" >';
		
		// Remarks -- FPA_DESC
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.innerHTML = '<input type="text" name="data['+intIndex+'][FPA_DESC]" id="data'+intIndex+'FPA_DESC" size="20" value="" class="form-control" style="min-width:110px; max-width:300px; text-align:left">';
		
		var decFormat												= document.getElementById('decFormat').value;
		var PPMat_Budget											= document.getElementById('ITM_BUDGQTY'+intIndex).value
		document.getElementById('ITM_BUDGQTY'+intIndex).value 		= parseFloat(Math.abs(PPMat_Budget));
		document.getElementById('ITM_BUDGQTYx'+intIndex).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PPMat_Budget)),decFormat));
		var ITM_REQUESTED											= document.getElementById('ITM_REQUESTED'+intIndex).value;
		document.getElementById('TOTWOQTY'+intIndex).value 			= parseFloat(Math.abs(TOT_USEDQTY));
		document.getElementById('ITM_REQUESTED'+intIndex).value 	= parseFloat(Math.abs(ITM_REQUESTED));
		document.getElementById('ITM_REQUESTEDx'+intIndex).value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_REQUESTED)),decFormat));
		document.getElementById('TOTWOQTY'+intIndex).value 			= parseFloat(Math.abs(TOT_USEDQTY));
		document.getElementById('TOTWOQTYx'+intIndex).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TOT_USEDQTY)),decFormat));
		document.getElementById('totalrow').value = intIndex;
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
		
		thisVal 			= parseFloat(Math.abs(thisVal1.value));
		itemConvertion		= document.getElementById('itemConvertion'+row).value;	
		ITM_PRICE			= parseFloat(document.getElementById('data'+row+'ITM_PRICE').value);	// Item Price
		ITM_BUDGQTY			= parseFloat(document.getElementById('ITM_BUDGQTY'+row).value);			// Budget Qty
		ITM_BUDG			= parseFloat(document.getElementById('data'+row+'ITM_BUDG').value);		// Budget Amount
		TOTWOQTY			= document.getElementById('TOTWOQTY'+row).value;						// Total Requested
		TOTPRAMOUNT			= parseFloat(TOTWOQTY) * parseFloat(ITM_PRICE);							// Total Requested Amount
		REQ_NOW_QTY1		= parseFloat(document.getElementById('FPA_VOLM'+row).value);				// Request Qty - Now
		REQ_NOW_QTY2		= REQ_NOW_QTY1 * itemConvertion;										// Request Qty 2 - Now
		REQ_NOW_AMOUNT		= parseFloat(REQ_NOW_QTY1) * parseFloat(ITM_PRICE);						// Request Qty Amount - Now
		
		REM_FPA_QTY			= parseFloat(ITM_BUDGQTY) - parseFloat(TOTWOQTY);
		REM_FPA_AMOUNT		= parseFloat(ITM_BUDG) - parseFloat(TOTPRAMOUNT);
				
		if(REQ_NOW_AMOUNT > REM_FPA_AMOUNT)
		{
			REM_FPA_QTYV		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(REM_FPA_QTY)),decFormat));
			REM_FPA_AMOUNTV	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(REM_FPA_AMOUNT)),decFormat));
			alert('Request Qty is Greater than Budget. Maximum Qty is '+REM_FPA_QTY);
			document.getElementById('data'+row+'FPA_VOLM').value = REM_FPA_QTY;
			document.getElementById('FPA_VOLM'+row).value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(REM_FPA_QTY)),decFormat));
			return false;
		}
		
		document.getElementById('data'+row+'FPA_TOTAL').value 	= REQ_NOW_AMOUNT;
		document.getElementById('data'+row+'FPA_VOLM').value 	= REQ_NOW_QTY1;
		document.getElementById('FPA_VOLM'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(REQ_NOW_QTY1)),decFormat))
	}
	
	function RoundNDecimal(X, N)
	{
		var T, S=new String(Math.round(X*Number("1e"+N)))
		while (S.length<=N) S='0'+S
		return S.substr(0, T=(S.length-N)) + '.' + S.substr(T, N)
	}
	
	function checkInp()
	{
		FPA_STAT		= document.getElementById('FPA_STAT').value;
		
		if(FPA_STAT == 0)
		{
			alert('<?php echo $alert1; ?>');
			document.getElementById('FPA_STAT').focus();
			return false;
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