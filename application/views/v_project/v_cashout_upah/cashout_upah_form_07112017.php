<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 24 Oktober 2017
 * File Name	= purchase_req_form.php
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
	/*
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
	if($Pattern_Position == 'Especially')
	{
		$Pattern_YearAktive = date('Y');
		$Pattern_MonthAktive = date('m');
		$Pattern_DateAktive = date('d');
	}
	$yearC = (int)$Pattern_YearAktive;
	$year = substr($Pattern_YearAktive,2,2);
	$month = (int)$Pattern_MonthAktive;
	$date = (int)$Pattern_DateAktive;

	$this->db->where('Patt_Year', $year);
	//$this->db->where('Patt_Month', $month);
	//$this->db->where('Patt_Date', $date);
	$myCount = $this->db->count_all('tbl_pr_header');
	
	$sql = "SELECT MAX(Patt_Number) as maxNumber FROM tbl_pr_header
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
	
	*/
	
	$sql = "SELECT PRJCODE, PRJNAME FROM tbl_project
			WHERE PRJCODE = '$PRJCODE'";
	$resultProj = $this->db->query($sql)->result();
	foreach($resultProj as $row) :
		$PRJNAME = $row->PRJNAME;
	endforeach;
		
	$HRDOC_CREATED		= date('Y-m-d-H:i:s');
	$DocNumber 			= "COU$HRDOC_CREATED";
	$COU_PeriodeCode	= "$DocNumber";
	
	$PRJCODE		= $PRJCODE;
}
else
{	
	$COU_PeriodeCode 	= $default['COU_PeriodeCode'];
	$COU_Periode	= $default['COU_Periode'];
	$COU_ExpanceName 	= $default['COU_ExpanceName'];		
	$COU_ValuePlan 	= $default['COU_ValuePlan'];
	$COU_RealizationValue 	= $default['COU_RealizationValue'];
	$COU_Created 	= $default['COU_Created'];
	$own_Status = $default['own_Status'];
	$patt_No 	= $default['patt_No'];
	
	/*
	$dataSessSrc = array(
			'selSearchproj_Code' => $PRJCODE,
			'selSearchType' => $this->input->post('selSearchType'),
			'txtSearch' => $this->input->post('txtSearch'));
	$this->session->set_userdata('dtSessSrc1', $dataSessSrc);
	$this->session->set_userdata('dtSessSrc2', $dataSessSrc);
	
	*/
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
		if($TranslCode == 'Back')$Back = $LangTransl;
		if($TranslCode == 'RequestNo')$RequestNo = $LangTransl;
		if($TranslCode == 'RequestCode')$RequestCode = $LangTransl;
		if($TranslCode == 'SPPCode')$SPPCode = $LangTransl;
		if($TranslCode == 'Notes')$Notes = $LangTransl;
		if($TranslCode == 'Date')$Date = $LangTransl;
		if($TranslCode == 'Project')$Project = $LangTransl;
		if($TranslCode == 'Status')$Status = $LangTransl;
		if($TranslCode == 'AlertRevisionorReject')$AlertRevisionorReject = $LangTransl;
		if($TranslCode == 'New')$New = $LangTransl;
		if($TranslCode == 'Confirm')$Confirm = $LangTransl;
		if($TranslCode == 'Close')$Close = $LangTransl;		
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
		if($TranslCode == 'PR_Code')$PR_Code = $LangTransl;
		if($TranslCode == 'AddItem')$AddItem = $LangTransl;
	endforeach;
	
	if($LangID == 'IND')
	{
		$subTitleH	= "Tambah";
		$subTitleD	= "permintaan pembelian";
	}
	else
	{
		$subTitleH	= "Add";
		$subTitleD	= "purchase request";
	}
	
	$secAddURL	= site_url('c_purchase/c_purchase_req/add/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
	$secGenCode	= base_url().'index.php/c_purchase/c_purchase_req/genCode/'; // Generate Code
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
            <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
            <input type="Hidden" name="rowCount" id="rowCount" value="0">
            	<div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label"><?php echo $RequestNo; ?></label>
                    <div class="col-sm-10">
                    	<input type="text" class="form-control" style="max-width:180px;text-align:left" name="PR_NUM1" id="PR_NUM1" size="30" value="<?php echo $DocNumber; ?>" disabled />
                    	<input type="hidden" class="form-control" style="max-width:350px;text-align:right" name="PR_NUM" id="PR_NUM" size="30" value="<?php echo $DocNumber; ?>" />
                        <input type="hidden" class="form-control" style="max-width:350px;text-align:right" name="PRJCODE" id="PRJCODE" size="30" value="<?php echo $PRJCODE; ?>" />
                    </div>
                </div>
            	<div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label"><?php echo $PR_Code; ?></label>
                    <div class="col-sm-10">
                    	<input type="text" class="form-control" style="min-width:110px; max-width:100px; text-align:left" id="PR_CODE" name="PR_CODE" size="5" value="<?php echo $PR_CODE; ?>" />
                    </div>
                </div>
            	<div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Date; ?></label>
                    <div class="col-sm-10">
                    	<div class="input-group date">
                        <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>&nbsp;</div><input type="text" name="PR_DATE" class="form-control pull-left" id="datepicker" value="<?php echo $PR_DATE; ?>" style="width:106px" onChange="getPR_NUM(this.value)"></div>
                    </div>
                </div>
                <script>
					function getPR_NUM(selDate)
					{
						document.getElementById('PRDate').value = selDate;
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
									document.getElementById('PR_NUM1').value = myarr[0];
									document.getElementById('PR_CODE').value = myarr[1];
								}
							});
						});
					});
				</script>
            	<div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Project; ?></label>
                    <div class="col-sm-10">
                    	<select name="PRJCODE" id="PRJCODE" class="form-control" style="max-width:350px" onChange="chooseProject()" disabled>
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
                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Notes; ?></label>
                    <div class="col-sm-10">
                    	<textarea name="PR_NOTE" class="form-control" style="max-width:350px;" id="PR_NOTE" cols="30"><?php echo $PR_NOTE; ?></textarea>                        
                    </div>
                </div>
            	<div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Status; ?></label>
                    <div class="col-sm-10">
						<?php
                            if($ISAPPROVE == 1)
                            {
                            ?>
                                <select name="PR_STAT" id="PR_STAT" class="form-control" style="max-width:100px" onChange="selStat(this.value)" >
                                    <option value="1"<?php if($PR_STAT == 1) { ?> selected <?php } ?>>New</option>
                                    <option value="2"<?php if($PR_STAT == 2) { ?> selected <?php } ?>>Confirm</option>
                                    <option value="3"<?php if($PR_STAT == 3) { ?> selected <?php } ?>>Approve</option>
                                    <option value="4"<?php if($PR_STAT == 4) { ?> selected <?php } ?>>Revise</option>
                                    <option value="5"<?php if($PR_STAT == 5) { ?> selected <?php } ?>>Reject</option>
                                    <option value="6"<?php if($PR_STAT == 6) { ?> selected <?php } ?>>Close</option>
                                </select>
                            <?php
                            }
                            else
                            {
                            ?>
                                <select name="PR_STAT" id="PR_STAT" class="form-control" style="max-width:100px" onChange="selStat(this.value)">
                                    <option value="1"<?php if($PR_STAT == 1) { ?> selected <?php } ?>>New</option>
                                    <option value="2"<?php if($PR_STAT == 2) { ?> selected <?php } ?>>Confirm</option>
                                    <option value="6"<?php if($PR_STAT == 6) { ?> selected <?php } ?> style="display:none">Close</option>
                                </select>
                            <?php
                            }
							$theProjCode 	= $PRJCODE;
                        	$url_AddItem	= site_url('c_purchase/c_purchase_req/popupallitem/?id='.$this->url_encryption_helper->encode_url($theProjCode));
                        ?>
                        <input type="hidden" name="PR_VALUE" id="PR_VALUE" value="<?php echo $PR_VALUE; ?>">
                      	<input type="hidden" name="PR_VALUEAPP" id="PR_VALUEAPP" value="<?php echo $PR_VALUEAPP; ?>">
                    </div>
                </div>
                <div class="form-group">
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
                <div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label">&nbsp;</label>
                    <div class="col-sm-10">
                        <div class="box-tools pull-left">
                        <table width="100%" border="1" id="tbl" >
                            <tr style="background:#CCCCCC">
                              <th width="2%" height="25" rowspan="2" style="text-align:left">No.</th>
                              <th width="13%" rowspan="2" style="text-align:center"><?php echo $ItemCode ?> </th>
                              <th width="40%" rowspan="2" style="text-align:center"><?php echo $ItemName ?> </th>
                              <th colspan="2" style="text-align:center"><?php echo $BudgetQty ?> </th>
                              <th colspan="2" style="text-align:center"><?php echo $RequestNow ?> </th>
                              <th colspan="2" style="text-align:center"><?php echo $Unit ?> </th>
                              <th width="10%" rowspan="2" style="text-align:center"><?php echo $Remarks ?> </th>
                            </tr>
                            <tr style="background:#CCCCCC">
                              <th style="text-align:center;"><?php echo $Planning ?> </th>
                              <th style="text-align:center;"><?php echo $Requested ?> </th>
                              <th style="text-align:center;" nowrap><?php echo $Quantity ?>  1</th>
                              <th style="text-align:center;" nowrap><?php echo $Quantity ?>  2</th>
                              <th style="text-align:center;"><?php echo $Primary ?> </th>
                              <th style="text-align:center;"><?php echo $Secondary ?> </th>
                            </tr>
                            <?php					
                            if($task == 'edit')
                            {
                                $sqlDET		= "SELECT A.SPPCODE, A.CSTCODE, A.SNCODE, A.CSTUNIT, A.SPPVOLM, A.OPVOLM, 
                                                    A.IRVOLM, A.SPPVPRS, A.SPPDESC,
                                                    B.CSTDESC,
                                                    C.Unit_Type_Code, C.UMCODE, C.Unit_Type_Name
                                                FROM tbl_pr_detail A
                                                    INNER JOIN tbl_cost B ON A.CSTCODE = B.CSTCODE
                                                    INNER JOIN tbl_unittype C ON C.UMCODE = A.CSTUNIT
                                                WHERE SPPNUM = '$SPPNUM' 
                                                AND B.PRJCODE = '$PRJCODE'
                                                ORDER BY A.CSTCODE ASC";
                                // count data
                                    $resultCount = $this->db->where('SPPCODE', $SPPCODE);
                                    $resultCount = $this->db->count_all_results('tbl_pr_detail');
                                // End count data
                                $result = $this->db->query($sqlDET)->result();
                                $i		= 0;
                                $j		= 0;
                                if($resultCount > 0)
                                {
                                    foreach($result as $row) :
                                        $currentRow  	= ++$i;
                                        $SPPCODE 		= $row->SPPCODE;
                                        $CSTCODE 		= $row->CSTCODE;
                                        $CSTDESC 		= $row->CSTDESC;
                                        $SNCODE 		= $row->SNCODE;
                                        $CSTUNIT 		= $row->CSTUNIT;
                                        $SPPVOLM 		= $row->SPPVOLM;
                                        $OPVOLM 		= $row->OPVOLM;
                                        $IRVOLM 		= $row->IRVOLM;
                                        $SPPVPRS 		= $row->SPPVPRS;
                                        $SPPDESC 		= $row->SPPDESC;
                                        $Unit_Type_Code	= $row->Unit_Type_Code;
                                        $UMCODE 		= $row->UMCODE;
                                        $Unit_Type_Name	= $row->Unit_Type_Name;
                                        $itemConvertion	= 1;
                            
                                        if ($j==1) {
                                            echo "<tr class=zebra1>";
                                            $j++;
                                        } else {
                                            echo "<tr class=zebra2>";
                                            $j--;
                                        }
                                        ?> 
                                        <td width="2%" height="25" style="text-align:left">
                                          <?php echo "$currentRow."; ?>
                                          <input type="Checkbox" id="data[<?php echo $currentRow; ?>][chk]" name="data[<?php echo $currentRow; ?>][chk]" value="<?php echo $currentRow; ?>" onClick="pickThis(this,<?php echo $currentRow; ?>)" style="display:none">
                                          <input type="Checkbox" style="display:none" id="chk<?php echo $currentRow; ?>" name="chk<?php echo $currentRow; ?>" value="" >
                                          <input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">
                                          <input type="hidden" id="chk" name="chk" value="<?php echo $currentRow; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">
                                          <input type="hidden" id="data<?php echo $currentRow; ?>SNCODE" name="data[<?php echo $currentRow; ?>][SNCODE]" value="<?php echo $SNCODE; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">
                                          <input type="hidden" id="data<?php echo $currentRow; ?>SPPNUM" name="data[<?php echo $currentRow; ?>][SPPNUM]" value="<?php echo $SPPNUM; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right"> <!-- Checkbox -->                                    </td>
                                      <td width="13%" style="text-align:left">
                                          <?php echo $CSTCODE; ?>
                                          <input type="hidden" id="data[<?php echo $currentRow; ?>][SPPCODE]" name="data[<?php echo $currentRow; ?>][SPPCODE]" value="<?php echo $SPPCODE; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">
                                          <input type="hidden" id="data<?php echo $currentRow; ?>CSTCODE" name="data[<?php echo $currentRow; ?>][CSTCODE]" value="<?php echo $CSTCODE; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right"> <!-- Item Code -->                                    </td>
                                      <td width="40%" style="text-align:left">
                                          <?php echo $CSTDESC; ?> <!-- Item Name -->                                    </td>
                                      <?php
                                            $sqlgetQty	= "SELECT A.PPMat_Qty, A.PPMat_Qty2, A.request_qty, A.request_qty2
                                                            FROM tbl_projplan_material A
                                                            INNER JOIN tbl_cost B ON A.CSTCODE = B.CSTCODE
                                                            WHERE A.CSTCODE = '$CSTCODE' AND A.PRJCODE = '$PRJCODE'";
                                            $resultgetQty = $this->db->query($sqlgetQty)->result();
                                            foreach($resultgetQty as $nRow) :
                                                $PPMat_Qty 		= $nRow->PPMat_Qty;
                                                $PPMat_Qty2 	= $nRow->PPMat_Qty2;
                                                $request_qty 	= $nRow->request_qty;
                                                $request_qty2	= $nRow->request_qty2;
                                            endforeach;
                                            
                                            // mencari sisa maximum request => ProjectPlan_Qty - TotalRequest
                                            $Req_Qty1 = $SPPVOLM;
                                            $Req_Qty2 = $SPPVOLM;
                                            $tempTotMax	= $PPMat_Qty - $request_qty + $Req_Qty1;
                                        ?>
                                      <td width="5%" style="text-align:right" nowrap>
                                          <input type="text" class="form-control" style="min-width:100px; max-width:350px; text-align:right" name="PPMat_Qtyx<?php echo $currentRow; ?>" id="PPMat_Qtyx<?php echo $currentRow; ?>" value="<?php print number_format($PPMat_Qty, $decFormat); ?>" disabled >
                                          <input type="hidden" style="text-align:right" name="PPMat_Qty<?php echo $currentRow; ?>" id="PPMat_Qty<?php echo $currentRow; ?>" size="10" value="<?php echo $PPMat_Qty; ?>" > <!-- Item Bdget -->                                    </td>
                                      <td width="6%" style="text-align:right" nowrap>
                                          <input type="hidden" class="form-control" style="max-width:350px; text-align:right" name="PPMat_Requested<?php echo $currentRow; ?>" id="PPMat_Requested<?php echo $currentRow; ?>" size="10" value="<?php echo $request_qty; ?>" >
                                          <input type="text" class="form-control" style="min-width:110px; max-width:300px; text-align:right" name="PPMat_Requestedx<?php echo $currentRow; ?>" id="PPMat_Requestedx<?php echo $currentRow; ?>" value="<?php print number_format($request_qty, $decFormat); ?>" disabled > <!-- Item Requested FOR INFORMATION ONLY -->                                   	</td>
                                      <td width="7%" style="text-align:right" nowrap>
                                          <input type="text" name="SPPVOLM<?php echo $currentRow; ?>" id="SPPVOLM<?php echo $currentRow; ?>" value="<?php print number_format($SPPVOLM, $decFormat); ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,<?php echo $currentRow; ?>);" >
                                          <input type="hidden" name="data[<?php echo $currentRow; ?>][SPPVOLM]" id="data<?php echo $currentRow; ?>SPPVOLM" value="<?php echo $SPPVOLM; ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,<?php echo $currentRow; ?>);" >
                                          <input type="hidden" style="text-align:right" name="itemConvertion<?php echo $currentRow; ?>" id="itemConvertion<?php echo $currentRow; ?>" value="<?php echo $itemConvertion; ?>" > <!-- Item Request Now -- SPPVOLM -->                                    </td>
                                      <td width="7%" style="text-align:right" nowrap>
                                          <input type="hidden" style="text-align:right" name="tempTotMax<?php echo $currentRow; ?>" id="tempTotMax<?php echo $currentRow; ?>" value="<?php echo $tempTotMax; ?>" >
                                          <input type="text" name="SPPVOLM2<?php echo $currentRow; ?>" id="SPPVOLM2<?php echo $currentRow; ?>" value="<?php print number_format($SPPVOLM, $decFormat); ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="decimalin(this);" disabled > <!-- tem Request Now 2 FOR INFORMATION ONLY -->                                    </td>
                                      <td width="4%" style="text-align:center" nowrap>
                                          <?php echo $Unit_Type_Name; ?>
                                          <input type="hidden" name="data[<?php echo $currentRow; ?>][CSTUNIT]" id="data<?php echo $currentRow; ?>CSTUNIT" size="15" value="<?php echo $CSTUNIT; ?>" class="form-control" style="max-width:350px;text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="decimalin(this);" > <!-- Item Unit Type -- CSTUNIT -->                                    </td>
                                      <td width="6%" style="text-align:center" nowrap>
                                          <?php print $Unit_Type_Name; ?> <!-- Item Unit Type 2 FOR INFORMATION ONLY -->                                    </td>
                                      <td width="10%" style="text-align:center">
                                          <input type="text" name="data[<?php echo $currentRow; ?>][SPPDESC]" id="data<?php echo $currentRow; ?>SPPDESC" value="<?php print $SPPDESC; ?>" class="form-control" style="max-width:450px;text-align:left"> <!-- Remarks -- SPPDESC -->                                    </td>
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
							if($ISCREATE == 1)
							{
								if($task=='add')
								{
									?>
										<button class="btn btn-primary">
										<i class="cus-save-16x16"></i>&nbsp;&nbsp;<?php echo $Save; ?>
										</button>&nbsp;
									<?php
								}
								else
								{
									?>
										<button class="btn btn-primary" >
										<i class="cus-save1-16x16"></i>&nbsp;&nbsp;<?php echo $Update; ?>
										</button>&nbsp;
									<?php
								}
							}
							echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="cus-back-16x16"></i>&nbsp;&nbsp;'.$Back.'</button>');
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
</script>

<script>
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

	function add_item(strItem) 
	{
		arrItem = strItem.split('|');
		
		var objTable, objTR, objTD, intIndex, arrItem;
		var SPPNUMx 	= "<?php echo $DocNumber; ?>";
		var SPPCODEx 	= "<?php echo $PR_CODE; ?>";
		ilvl = arrItem[1];
		
		validateDouble(arrItem[0],arrItem[1])
		if(validateDouble(arrItem[0],arrItem[1]))
		{
			alert("Double Item for " + arrItem[0]);
			return;
		}
		
		itemcode 		= arrItem[0];
		itemserial 		= arrItem[1];
		itemname 		= arrItem[2];
		itemUnitType 	= arrItem[3];
		itemNameType 	= arrItem[4];
		itemUnitType2 	= arrItem[5];
		itemNameType2 	= arrItem[6];
		PPMatQty 		= arrItem[7];
		PPMatQty2 		= arrItem[8];
		itemConvertion 	= arrItem[9];
		maxReqQty 		= arrItem[10];
		requestedQty 	= arrItem[11];
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
		objTD.innerHTML = ''+intIndex+'<input type="Checkbox" id="data['+intIndex+'][chk]" name="data['+intIndex+'][chk]" value="'+intIndex+'" onclick="pickThis(this,'+intIndex+')" style="display:none"><input type="Checkbox" style="display:none" id="chk'+intIndex+'" name="chk'+intIndex+'" value="" ><input type="hidden" name="totalrow" id="totalrow" value="'+intIndex+'"><input type="hidden" id="chk" name="chk" value="'+intIndex+'" width="10" size="15" readonly class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'SNCODE" name="data['+intIndex+'][SNCODE]" value="'+itemserial+'" width="10" readonly class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'SPPNUM" name="data['+intIndex+'][SPPNUM]" value="'+SPPNUMx+'" width="10" size="15" readonly class="form-control" style="max-width:300px;">';	
		
		// Item Code
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'right';
		objTD.innerHTML = ''+itemcode+'<input type="hidden" id="data['+intIndex+'][SPPCODE]" name="data['+intIndex+'][SPPCODE]" value="'+SPPCODEx+'" width="10" size="15" readonly class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'CSTCODE" name="data['+intIndex+'][CSTCODE]" value="'+itemcode+'" width="10" size="15" readonly class="form-control" style="max-width:300px;">';
		
		/*objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.innerHTML = ''+arrItem[1]+'<input type="hidden" id="data'+intIndex+'SNCODE" name="data['+intIndex+'][SNCODE]" value="'+arrItem[1]+'" width="10" size="15" readonly class="form-control" style="max-width:300px;">';*/
		
		// Item Name
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.noWrap = true;
		objTD.innerHTML = ''+itemname+'';
		
		// Item Budget
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" class="form-control" style="min-width:110px; min-width:110px; max-width:300px; text-align:right" name="PPMat_Qtyx'+intIndex+'" id="PPMat_Qtyx'+intIndex+'" value="'+PPMatQty+'" disabled ><input type="hidden" style="text-align:right" name="PPMat_Qty'+intIndex+'" id="PPMat_Qty'+intIndex+'" size="10" value="'+PPMatQty+'" >';
		
		// Item Requested FOR INFORMATION ONLY
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="hidden" class="form-control" style="min-width:110px; min-width:110px; max-width:300px; text-align:right" name="PPMat_Requested'+intIndex+'" id="PPMat_Requested'+intIndex+'" size="10" value="'+requestedQty+'" ><input type="text" class="form-control" style="min-width:110px; min-width:110px; max-width:300px; text-align:right" name="PPMat_Requestedx'+intIndex+'" id="PPMat_Requestedx'+intIndex+'" size="10" value="'+requestedQty+'" disabled >';
		
		// Item Request Now -- SPPVOLM
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" name="SPPVOLM'+intIndex+'" id="SPPVOLM'+intIndex+'" size="10" value="0.00" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,'+intIndex+');" ><input type="hidden" name="data['+intIndex+'][SPPVOLM]" id="data'+intIndex+'SPPVOLM" size="10" value="0.00" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,'+intIndex+');" ><input type="hidden" style="text-align:right" name="itemConvertion'+intIndex+'" id="itemConvertion'+intIndex+'" size="10" value="'+itemConvertion+'" >';
		
		// Item Request Now 2 FOR INFORMATION ONLY
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="hidden" style="text-align:right" name="tempTotMax'+intIndex+'" id="tempTotMax'+intIndex+'" size="10" value="'+maxReqQty+'" ><input type="text" name="SPPVOLM2'+intIndex+'" id="SPPVOLM2'+intIndex+'" value="0.00" class="form-control" style="min-width:110px; min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="decimalin(this);" disabled >';
		
		// Item Unit Type -- CSTUNIT
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = ''+itemNameType+'<input type="hidden" name="data['+intIndex+'][CSTUNIT]" id="data'+intIndex+'CSTUNIT" size="15" value="'+itemUnitType+'" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" onBlur="decimalin(this);" >';
		
		// Item Unit Type 2 FOR INFORMATION ONLY
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = ''+itemNameType2+'';
		
		// Remarks -- SPPDESC
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.innerHTML = '<input type="text" name="data['+intIndex+'][SPPDESC]" id="data'+intIndex+'SPPDESC" size="20" value="" class="form-control" style="min-width:110px; max-width:300px; text-align:left">';
		
		var decFormat												= document.getElementById('decFormat').value;
		var PPMat_Budget											= document.getElementById('PPMat_Qty'+intIndex).value
		document.getElementById('PPMat_Qty'+intIndex).value 		= parseFloat(Math.abs(PPMat_Budget));
		document.getElementById('PPMat_Qtyx'+intIndex).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PPMat_Budget)),decFormat));
		var PPMat_Requested											= document.getElementById('PPMat_Requested'+intIndex).value;
		document.getElementById('PPMat_Requested'+intIndex).value 	= parseFloat(Math.abs(PPMat_Requested));
		document.getElementById('PPMat_Requestedx'+intIndex).value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PPMat_Requested)),decFormat));
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
	
	function validateDouble(vcode,SNCODE) 
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
				var elitem1= document.getElementById('data'+i+'CSTCODE').value;
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
		
		thisVal 			= parseFloat(Math.abs(thisVal1.value))
		tempTotMax1 		= parseFloat(document.getElementById('tempTotMax'+row).value);
		// Start : 7 Mei 2015 : Permintaan tidak boleh melebihi
		
		reqQty1				= document.getElementById('SPPVOLM'+row).value;
		reqQty1x 			= parseFloat(reqQty1);
		document.getElementById('data'+row+'SPPVOLM').value = reqQty1x;
		document.getElementById('SPPVOLM'+row).value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(reqQty1x)),decFormat))
		
		itemConvertion		= document.getElementById('itemConvertion'+row).value;
		
		reqQty2 			= reqQty1 * itemConvertion;
		document.getElementById('SPPVOLM2'+row).value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(reqQty2)),decFormat));
		
		tempTotMaxx = parseFloat(Math.abs(tempTotMax1));
		
		if(reqQty1x > tempTotMaxx)
		{
			alert('Request Qty is Greater than Budget Qty. Maximum Qty is '+tempTotMaxx);
			document.getElementById('data'+row+'SPPVOLM').value = tempTotMaxx;
			document.getElementById('SPPVOLM'+row).value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(tempTotMaxx)),decFormat))
			document.getElementById('SPPVOLM2'+row).value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(tempTotMaxx)),decFormat))
			return false;
		}
		document.getElementById('data'+row+'SPPVOLM').value = reqQty1x;
		document.getElementById('SPPVOLM'+row).value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(reqQty1x)),decFormat))
		document.getElementById('SPPVOLM2'+row).value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(reqQty1x)),decFormat))
	}
	
	function RoundNDecimal(X, N)
	{
		var T, S=new String(Math.round(X*Number("1e"+N)))
		while (S.length<=N) S='0'+S
		return S.substr(0, T=(S.length-N)) + '.' + S.substr(T, N)
	}
	
	function submitForm(value)
	{
		var totrow 		= document.getElementById('totalrow').value;
		//var venCode 	= document.getElementById('Vend_Code').value;
		var isApproved 	= document.getElementById('isApproved').value;
		
		if(isApproved == 0)
		{
			for(i=1;i<=totrow;i++)
			{
				var SPPVOLM = parseFloat(document.getElementById('SPPVOLM'+i).value);
				if(SPPVOLM == 0)
				{
					alert('Please input qty of requisition.');
					document.getElementById('SPPVOLM'+i).value = '0';
					document.getElementById('SPPVOLM'+i).focus();
					return false;
				}
			}
			/*if(venCode == 0)
			{
				alert('Please select a Vendor.');
				document.getElementById('selVend_Code').focus();
				return false;
			}*/
			if(totrow == 0)
			{
				alert('Please input detail Material Request.');
				return false;		
			}
			else
			{
				document.frm.submit();
			}
		}
		else
		{
			alert('Can not update this document. The document has Confirmed.');
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