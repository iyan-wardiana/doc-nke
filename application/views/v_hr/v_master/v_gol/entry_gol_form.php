<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 31 Oktober 2017
 * File Name	= entry_gol_form.php
 * Location		= -
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$FlagUSER 	= $this->session->userdata['FlagUSER'];
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

// _global function

$this->db->trans_begin();date_default_timezone_set("Asia/Jakarta");
date_default_timezone_set("Asia/Jakarta");

$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
$decFormat		= 2;

$FlagUSER 		= $this->session->userdata['FlagUSER'];
$DefEmp_ID 		= $this->session->userdata['Emp_ID'];

$DATEY 		= date('Y');
$DATEM 		= date('m');
$DATED 		= date('d');
$DATEHH		= date('H');
$DATEMM		= date('i');
$DATESS		= date('s');

$DATEF		= "$DATEM/$DATED/$DATEY";
$DATEN		= "$DATEY$DATEM$DATED-$DATEHH$DATEMM$DATESS";

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
	
	$sql = "SELECT MAX(Patt_Number) as maxNumber FROM tbl_employee_gol
			WHERE Patt_Year = $yearC";
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
	$DocNumber 		= "$Pattern_Code$groupPattern-$lastPatternNumb";

	$EMPG_CODE		= $DocNumber;
	$EMPG_NAME		= '';
	$EMPG_RANK		= '';
	$EMPG_PARENT	= '';
	$EMPG_CHILD		= '1A1';
	$EMPG_BASAL		= 0;
	$EMPG_P_ALLOW	= 0;
	$EMPG_T_ALLOW	= 0;
	$EMPG_HM_ALLOW	= 0;
	$EMPG_H_ALLOW	= 0;
	$EMPG_C_ALLOW	= 0;
	$EMPG_A_ALLOW1	= 0;
	$EMPG_A_ALLOW2	= 0;
	$EMPG_M_ALLOW	= 0;
	$EMPG_PF_ALLOW	= 0;
	$EMPG_MK_ALLOW	= 0;
	$EMPG_I_ALLOW	= 0;
	$EMPG_K_ALLOW	= 0;
	$EMPG_STAT		= 1;
	$EMPG_NOTES		= '';
}
else
{
	$EMPG_CODE		= $default['EMPG_CODE'];
	$EMPG_NAME		= $default['EMPG_NAME'];
	$EMPG_RANK		= $default['EMPG_RANK'];
	$EMPG_PARENT	= $default['EMPG_PARENT'];
	$EMPG_CHILD		= $default['EMPG_CHILD'];
	$EMPG_BASAL		= $default['EMPG_BASAL'];
	$EMPG_P_ALLOW	= $default['EMPG_P_ALLOW'];
	$EMPG_T_ALLOW	= $default['EMPG_T_ALLOW'];
	$EMPG_HM_ALLOW	= $default['EMPG_HM_ALLOW'];
	$EMPG_H_ALLOW	= $default['EMPG_H_ALLOW'];
	$EMPG_C_ALLOW	= $default['EMPG_C_ALLOW'];
	$EMPG_A_ALLOW1	= $default['EMPG_A_ALLOW1'];
	$EMPG_A_ALLOW2	= $default['EMPG_A_ALLOW2'];
	$EMPG_M_ALLOW	= $default['EMPG_M_ALLOW'];
	$EMPG_PF_ALLOW	= $default['EMPG_PF_ALLOW'];
	$EMPG_MK_ALLOW	= $default['EMPG_MK_ALLOW'];
	$EMPG_I_ALLOW	= $default['EMPG_I_ALLOW'];
	$EMPG_K_ALLOW	= $default['EMPG_K_ALLOW'];
	$EMPG_COUNT		= $default['EMPG_COUNT'];					
	$EMPG_STAT		= $default['EMPG_STAT'];
	$EMPG_NOTES		= $default['EMPG_NOTES'];
	$lastPatternNumb1	=  $default['Patt_Number'];
}
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
		if($TranslCode == 'ParentCode')$ParentCode = $LangTransl;
		if($TranslCode == 'ChildCode')$ChildCode = $LangTransl;
		if($TranslCode == 'Code')$Code = $LangTransl;
		if($TranslCode == 'BasicSalary')$BasicSalary = $LangTransl;
		if($TranslCode == 'TransportAllowance')$TransportAllowance = $LangTransl;
		if($TranslCode == 'HomeAllowance')$HomeAllowance = $LangTransl;
		if($TranslCode == 'PositionAllowance')$PositionAllowance = $LangTransl;
		if($TranslCode == 'HealthAllowance')$HealthAllowance = $LangTransl;
		if($TranslCode == 'CommunicAllow')$CommunicAllow = $LangTransl;
		if($TranslCode == 'AcommAllow')$AcommAllow = $LangTransl;
		if($TranslCode == 'MealAllow')$MealAllow = $LangTransl;
		if($TranslCode == 'PerformAllow')$PerformAllow = $LangTransl;
		if($TranslCode == 'MarketAllow')$MarketAllow = $LangTransl;
		if($TranslCode == 'InsentAllow')$InsentAllow = $LangTransl;
		if($TranslCode == 'OtherAllow')$OtherAllow = $LangTransl;
		if($TranslCode == 'Status')$Status = $LangTransl;
		if($TranslCode == 'KoefPerc')$KoefPerc = $LangTransl;
		if($TranslCode == 'Notes')$Notes = $LangTransl;
		if($TranslCode == 'Gol')$Gol = $LangTransl;
		if($TranslCode == 'Save')$Save = $LangTransl;
		if($TranslCode == 'Update')$Update = $LangTransl;
		if($TranslCode == 'Back')$Back = $LangTransl;
		if($TranslCode == 'Rank')$Rank = $LangTransl;
	endforeach;
	
	if($LangID == 'IND')
	{
		$Alert1 = 'Silahkan masukan kode anak/turunan.';
		$Alert2 = 'Gaji pokok tidak boleh kosong.';
		$Alert3 = 'Kode anak/turunan sudah ada.';
		$Alert4 = 'Tidak bisa mengedit dokumen yang sudah disetujui.';
	}
	else
	{
		$Alert1 = 'Please input Child Code.';
		$Alert2 = 'Basic salary can not be zero.';
		$Alert3 = 'Child code is already exist.';
		$Alert4 = 'Can not change the approved document.';
	}
?>

<body class="hold-transition skin-blue sidebar-mini" <?php if($task == 'add') { ?> onLoad="return getChildLoad();" <?php } ?>>
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>
    <?php echo "$Add $Gol"; ?>
    <small><?php echo $Gol; ?></small>
  </h1>
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
                <div class="box-header with-border">               
              		<div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body chart-responsive">
                	<form name="frmsrch" id="frmsrch" action="" method=POST style="display:none">
                        <input type="hidden" name="" id="" class="textbox" value="" />
                        <input type="hidden" class="button_css" name="submitSrch" id="submitSrch" value=" search " />
                    </form>
                	<form class="form-horizontal" name="frm" id="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return saveGroup()">
                    	<input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Code; ?> </label>
                          	<div class="col-sm-10">
                                <input type="text" class="form-control" style="max-width:130px" name="EMPG_CODE" id="EMPG_CODE" value="<?php echo $EMPG_CODE; ?> " disabled/>
                                <input type="hidden" class="form-control" style="max-width:200px" name="EMPG_CODE" id="EMPG_CODE" value="<?php echo $EMPG_CODE; ?> " />
                                <input type="hidden" name="Patt_Number" id="Patt_Number" value="<?php echo $lastPatternNumb1; ?> " />
                                <input type="hidden" name="task" id="task" value="<?php echo $task; ?> " />
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $ParentCode; ?> </label>
                          	<div class="col-sm-10">
                                <select name="EMPG_PARENT" id="EMPG_PARENT" class="form-control" style="max-width:70px" onChange="getChild(this.value)" >
                                    <option value="1A"<?php if($EMPG_PARENT == '1A') { ?> selected <?php } ?>>1A</option>
                                    <option value="1B"<?php if($EMPG_PARENT == '1B') { ?> selected <?php } ?>>1B</option>
                                    <option value="1C"<?php if($EMPG_PARENT == '1C') { ?> selected <?php } ?>>1C</option>
                                    <option value="1D"<?php if($EMPG_PARENT == '1D') { ?> selected <?php } ?>>1D</option>
                                    <option value="1E"<?php if($EMPG_PARENT == '1E') { ?> selected <?php } ?>>1E</option>
                                    <option value="2A"<?php if($EMPG_PARENT == '2A') { ?> selected <?php } ?>>2A</option>
                                    <option value="2B"<?php if($EMPG_PARENT == '2B') { ?> selected <?php } ?>>2B</option>
                                    <option value="2C"<?php if($EMPG_PARENT == '2C') { ?> selected <?php } ?>>2C</option>
                                    <option value="2D"<?php if($EMPG_PARENT == '2D') { ?> selected <?php } ?>>2D</option>
                                    <option value="2E"<?php if($EMPG_PARENT == '2E') { ?> selected <?php } ?>>2E</option>
                                    <option value="3A"<?php if($EMPG_PARENT == '3A') { ?> selected <?php } ?>>3A</option>
                                    <option value="3B"<?php if($EMPG_PARENT == '3B') { ?> selected <?php } ?>>3B</option>
                                    <option value="3C"<?php if($EMPG_PARENT == '3C') { ?> selected <?php } ?>>3C</option>
                                    <option value="3D"<?php if($EMPG_PARENT == '3D') { ?> selected <?php } ?>>3D</option>
                                    <option value="3E"<?php if($EMPG_PARENT == '3E') { ?> selected <?php } ?>>3E</option>
                                    <option value="4A"<?php if($EMPG_PARENT == '4A') { ?> selected <?php } ?>>4A</option>
                                    <option value="4B"<?php if($EMPG_PARENT == '4B') { ?> selected <?php } ?>>4B</option>
                                    <option value="4C"<?php if($EMPG_PARENT == '4C') { ?> selected <?php } ?>>4C</option>
                                    <option value="4D"<?php if($EMPG_PARENT == '4D') { ?> selected <?php } ?>>4D</option>
                                    <option value="4E"<?php if($EMPG_PARENT == '4E') { ?> selected <?php } ?>>4E</option>
                                    <option value="5A"<?php if($EMPG_PARENT == '5A') { ?> selected <?php } ?>>5A</option>
                                    <option value="5B"<?php if($EMPG_PARENT == '5B') { ?> selected <?php } ?>>5B</option>
                                    <option value="5C"<?php if($EMPG_PARENT == '5C') { ?> selected <?php } ?>>5C</option>
                                    <option value="5D"<?php if($EMPG_PARENT == '5D') { ?> selected <?php } ?>>5D</option>
                                    <option value="5E"<?php if($EMPG_PARENT == '5E') { ?> selected <?php } ?>>5E</option>
                                    <option value="6A"<?php if($EMPG_PARENT == '6A') { ?> selected <?php } ?>>6A</option>
                                    <option value="6B"<?php if($EMPG_PARENT == '6B') { ?> selected <?php } ?>>6B</option>
                                    <option value="6C"<?php if($EMPG_PARENT == '6C') { ?> selected <?php } ?>>6C</option>
                                    <option value="6D"<?php if($EMPG_PARENT == '6D') { ?> selected <?php } ?>>6D</option>
                                    <option value="6E"<?php if($EMPG_PARENT == '6E') { ?> selected <?php } ?>>6E</option>
                                </select>
                          	</div>
                        </div>
                        <script>
							function getChildLoad()
							{
								childCode	= document.getElementById('EMPG_CHILD').value;
								functioncheck(childCode);
							}
							
							function getChild(thisValue)
							{
								childCode = thisValue+'1';
								document.getElementById('EMPG_CHILD').value = childCode;
								functioncheck(childCode);
							}
							
							function functioncheck(myValue)
							{
								var ajaxRequest;
								try
								{
									ajaxRequest = new XMLHttpRequest();
								}
								catch (e)
								{
									alert("Something is wrong");
									return false;
								}
								ajaxRequest.onreadystatechange = function()
								{
									if(ajaxRequest.readyState == 4)
									{
										recordcount = ajaxRequest.responseText;
										if(recordcount > 0)
										{
											document.getElementById('CheckThe_Code').value= recordcount;
											document.getElementById("isHidden").innerHTML = ' Child Code already exist ... !';
											document.getElementById("isHidden").style.color = "#ff0000";
										}
										else
										{
											document.getElementById('CheckThe_Code').value= recordcount;
											document.getElementById("isHidden").innerHTML = ' Child Code : OK .. !';
											document.getElementById("isHidden").style.color = "green";
										}
									}
								}
								var CHILDC = document.getElementById('EMPG_CHILD').value;
								
								ajaxRequest.open("GET", "<?php echo base_url().'index.php/c_hr/c_master/c_gol/getCode/';?>" + CHILDC, true);
								ajaxRequest.send(null);
							}
						</script>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $ChildCode; ?> </label>
                          	<div class="col-sm-10">
                            	<label>
                                <input type="text" class="form-control" style="max-width:70px" name="EMPG_CHILD" id="EMPG_CHILD" value="<?php echo $EMPG_CHILD; ?>" onBlur="functioncheck(this.value);" />
                                </label><label>&nbsp;&nbsp;</label><label id="isHidden"></label>
                                <input type="hidden" name="CheckThe_Code" id="CheckThe_Code" value="" size="20" maxlength="25" >
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Rank; ?> </label>
                          	<div class="col-sm-10">
                                <input type="text" class="form-control" style="max-width:200px" name="EMPG_RANK" id="EMPG_RANK" value="<?php echo $EMPG_RANK; ?>" />
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $BasicSalary; ?> </label>
                          	<div class="col-sm-10">
                                <input type="text" class="form-control" style="text-align:right; max-width:130px" name="EMPG_BASAL1" id="EMPG_BASAL1" size="10" onKeyPress="return isIntOnlyNew(event);" value="<?php  echo number_format($EMPG_BASAL, $decFormat);?>" onBlur="changeVALUE(this, 'EMPG_BASAL')" />
                    			<input type="hidden" class="textbox" style="text-align:right" name="EMPG_BASAL" id="EMPG_BASAL" size="10" value="<?php  echo $EMPG_BASAL;?>" />
                            </div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $TransportAllowance; ?> </label>
                          	<div class="col-sm-10">
                                <input type="text" class="form-control" style="text-align:right; max-width:130px" name="EMPG_T_ALLOW1" id="EMPG_T_ALLOW1" size="10" onKeyPress="return isIntOnlyNew(event);" value="<?php  echo number_format($EMPG_T_ALLOW, $decFormat);?>" onBlur="changeVALUE(this, 'EMPG_T_ALLOW')" />
                    			<input type="hidden" class="textbox" style="text-align:right" name="EMPG_T_ALLOW" id="EMPG_T_ALLOW" size="10" value="<?php  echo $EMPG_T_ALLOW;?>" />
                            </div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $HomeAllowance; ?> </label>
                          	<div class="col-sm-10">
                                <input type="text" class="form-control" style="text-align:right; max-width:130px" name="EMPG_HM_ALLOW1" id="EMPG_HM_ALLOW1" size="10" onKeyPress="return isIntOnlyNew(event);" value="<?php  echo number_format($EMPG_HM_ALLOW, $decFormat);?>" onBlur="changeVALUE(this, 'EMPG_HM_ALLOW')" />
                    			<input type="hidden" class="textbox" style="text-align:right" name="EMPG_HM_ALLOW" id="EMPG_HM_ALLOW" size="10" value="<?php  echo $EMPG_HM_ALLOW;?>" />
                            </div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $PositionAllowance; ?> </label>
                          	<div class="col-sm-10">
                                <input type="text" class="form-control" style="text-align:right; max-width:130px" name="EMPG_P_ALLOW1" id="EMPG_P_ALLOW1" size="10" onKeyPress="return isIntOnlyNew(event);" value="<?php  echo number_format($EMPG_P_ALLOW, $decFormat);?>" onBlur="changeVALUE(this, 'EMPG_P_ALLOW')" />
                    			<input type="hidden" class="textbox" style="text-align:right" name="EMPG_P_ALLOW" id="EMPG_P_ALLOW" size="10" value="<?php  echo $EMPG_P_ALLOW;?>" />
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $HealthAllowance; ?> </label>
                          	<div class="col-sm-10">
                            	<input type="text" class="form-control" style="text-align:right; max-width:130px" name="EMPG_H_ALLOW1" id="EMPG_H_ALLOW1" size="10" onKeyPress="return isIntOnlyNew(event);" value="<?php  echo number_format($EMPG_H_ALLOW, $decFormat);?>" onBlur="changeVALUE(this, 'EMPG_H_ALLOW')" />
                    			<input type="hidden" class="textbox" style="text-align:right" name="EMPG_H_ALLOW" id="EMPG_H_ALLOW" size="10" value="<?php  echo $EMPG_H_ALLOW;?>" />
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $CommunicAllow; ?> </label>
                          	<div class="col-sm-10">
                            	<input type="text" class="form-control" style="text-align:right; max-width:130px" name="EMPG_C_ALLOW1" id="EMPG_C_ALLOW1" size="10" onKeyPress="return isIntOnlyNew(event);" value="<?php  echo number_format($EMPG_C_ALLOW, $decFormat);?>" onBlur="changeVALUE(this, 'EMPG_C_ALLOW')" />
                    			<input type="hidden" class="textbox" style="text-align:right" name="EMPG_C_ALLOW" id="EMPG_C_ALLOW" size="10" value="<?php  echo $EMPG_C_ALLOW;?>" />
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo "$AcommAllow 1"; ?> </label>
                          	<div class="col-sm-10">
                            	<input type="text" class="form-control" style="text-align:right; max-width:130px" name="EMPG_A_ALLOW11" id="EMPG_A_ALLOW11" size="10" onKeyPress="return isIntOnlyNew(event);" value="<?php  echo number_format($EMPG_A_ALLOW1, $decFormat);?>" onBlur="changeVALUE(this, 'EMPG_A_ALLOW1')" />
                    			<input type="hidden" class="textbox" style="text-align:right" name="EMPG_A_ALLOW1" id="EMPG_A_ALLOW1" size="10" value="<?php  echo $EMPG_A_ALLOW1;?>" />
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo "$AcommAllow 2"; ?> </label>
                          	<div class="col-sm-10">
                            	<input type="text" class="form-control" style="text-align:right; max-width:130px" name="EMPG_A_ALLOW21" id="EMPG_A_ALLOW21" size="10" onKeyPress="return isIntOnlyNew(event);" value="<?php  echo number_format($EMPG_A_ALLOW2, $decFormat);?>" onBlur="changeVALUE(this, 'EMPG_A_ALLOW2')" />
                    			<input type="hidden" class="textbox" style="text-align:right" name="EMPG_A_ALLOW2" id="EMPG_A_ALLOW2" size="10" value="<?php  echo $EMPG_A_ALLOW2;?>" />
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $MealAllow; ?> </label>
                          	<div class="col-sm-10">
                            	<input type="text" class="form-control" style="text-align:right; max-width:130px" name="EMPG_M_ALLOW1" id="EMPG_M_ALLOW1" size="10" onKeyPress="return isIntOnlyNew(event);" value="<?php  echo number_format($EMPG_M_ALLOW, $decFormat);?>" onBlur="changeVALUE(this, 'EMPG_M_ALLOW')" />
                    			<input type="hidden" class="textbox" style="text-align:right" name="EMPG_M_ALLOW" id="EMPG_M_ALLOW" size="10" value="<?php  echo $EMPG_M_ALLOW;?>" />
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $PerformAllow; ?> </label>
                          	<div class="col-sm-10">
                            	<input type="text" class="form-control" style="text-align:right; max-width:130px" name="EMPG_PF_ALLOW1" id="EMPG_PF_ALLOW1" size="10" onKeyPress="return isIntOnlyNew(event);" value="<?php  echo number_format($EMPG_PF_ALLOW, $decFormat);?>" onBlur="changeVALUE(this, 'EMPG_PF_ALLOW')" />
                    			<input type="hidden" class="textbox" style="text-align:right" name="EMPG_PF_ALLOW" id="EMPG_PF_ALLOW" size="10" value="<?php  echo $EMPG_PF_ALLOW;?>" />
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $MarketAllow; ?> </label>
                          	<div class="col-sm-10">
                            	<input type="text" class="form-control" style="text-align:right; max-width:130px" name="EMPG_MK_ALLOW1" id="EMPG_MK_ALLOW1" size="10" onKeyPress="return isIntOnlyNew(event);" value="<?php  echo number_format($EMPG_MK_ALLOW, $decFormat);?>" onBlur="changeVALUE(this, 'EMPG_MK_ALLOW')" />
                    			<input type="hidden" class="textbox" style="text-align:right" name="EMPG_MK_ALLOW" id="EMPG_MK_ALLOW" size="10" value="<?php  echo $EMPG_MK_ALLOW;?>" />
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $InsentAllow; ?> </label>
                          	<div class="col-sm-10">
                            	<input type="text" class="form-control" style="text-align:right; max-width:130px" name="EMPG_I_ALLOW1" id="EMPG_I_ALLOW1" size="10" onKeyPress="return isIntOnlyNew(event);" value="<?php  echo number_format($EMPG_I_ALLOW, $decFormat);?>" onBlur="changeVALUE(this, 'EMPG_I_ALLOW')" />
                    			<input type="hidden" class="textbox" style="text-align:right" name="EMPG_I_ALLOW" id="EMPG_I_ALLOW" size="10" value="<?php  echo $EMPG_I_ALLOW;?>" />
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $KoefPerc; ?> (%) </label>
                          	<div class="col-sm-10">
                            	<input type="text" class="form-control" style="text-align:right; max-width:70px" name="EMPG_K_ALLOW1" id="EMPG_K_ALLOW1" size="10" onKeyPress="return isIntOnlyNew(event);" value="<?php  echo number_format($EMPG_K_ALLOW, $decFormat);?>" onBlur="changeVALUE(this, 'EMPG_K_ALLOW')" />
                    			<input type="hidden" class="textbox" style="text-align:right" name="EMPG_K_ALLOW" id="EMPG_K_ALLOW" size="10" value="<?php  echo $EMPG_K_ALLOW;?>" />
                          	</div>
                        </div>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $Notes ?></label>
                            <div class="col-sm-10">
                                <textarea name="EMPG_NOTES" class="form-control" id="EMPG_NOTES" style="height:50px; width:300px" ><?php echo $EMPG_NOTES; ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $Status ?></label>
                            <div class="col-sm-10">
								<?php
                                	if($ISAPPROVE == 1)
                                    {
                                    ?>
                                        <select name="EMPG_STAT" id="EMPG_STAT" class="form-control" style="max-width:100px" onChange="selStat(this.value)" >
                                            <option value="1"<?php if($EMPG_STAT == 1) { ?> selected <?php } ?>>New</option>
                                            <option value="2"<?php if($EMPG_STAT == 2) { ?> selected <?php } ?>>Confirm</option>
                                            <option value="3"<?php if($EMPG_STAT == 3) { ?> selected <?php } ?>>Approve</option>
                                            <option value="4"<?php if($EMPG_STAT == 4) { ?> selected <?php } ?>>Revise</option>
                                            <option value="5"<?php if($EMPG_STAT == 5) { ?> selected <?php } ?>>Reject</option>
                                            <option value="6"<?php if($EMPG_STAT == 6) { ?> selected <?php } ?>>Close</option>
                                        </select>
                                    <?php
                                    }
                                    else
                                    {
                                    ?>
                                        <select name="EMPG_STAT" id="EMPG_STAT" class="form-control" style="max-width:100px" onChange="selStat(this.value)">
                                            <option value="1"<?php if($EMPG_STAT == 1) { ?> selected <?php } ?>>New</option>
                                            <option value="2"<?php if($EMPG_STAT == 2) { ?> selected <?php } ?>>Confirm</option>
                                            <option value="6"<?php if($EMPG_STAT == 6) { ?> selected <?php } ?> style="display:none">Close</option>
                                        </select>
                                    <?php
                                    }
                                ?>
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
											<i class="cus-save-16x16"></i>&nbsp;&nbsp;<?php echo $Update; ?>
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
	function changeVALUE(thisValue, idName)
	{
		var decFormat									= document.getElementById('decFormat').value;
		var idNameVal									= eval(thisValue).value.split(",").join("");
		document.getElementById(''+idName+'').value 	= idNameVal;
		document.getElementById(''+idName+'1').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(idNameVal)),decFormat));
		EMPG_BASAL										= document.getElementById('EMPG_BASAL').value;
		EMPG_CHILD 										= document.getElementById("EMPG_CHILD").value;
		task 											= document.getElementById("task").value;
		
		if(EMPG_CHILD == 0)
		{
			alert('<?php echo $Alert1; ?>');
			document.getElementById(''+idName+'').value 	= 0;
			document.getElementById(''+idName+'1').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(0)),decFormat));
			document.getElementById("EMPG_CHILD").focus();
			return false;
		}
		
		if(task == 'Add')
		{
			countCode 	= document.getElementById("CheckThe_Code").value;
			if(countCode > 0)
			{
				alert('<?php echo $Alert3; ?>');
				document.getElementById(''+idName+'').value 	= 0;
				document.getElementById(''+idName+'1').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(0)),decFormat));
				document.getElementById("EMPG_CHILD").focus();
				return false;
			}
		}
		
		sendCode										= EMPG_CHILD+'~'+EMPG_BASAL;
		changeKoef(sendCode)
	}
						
	function changeKoef(sendCode)
	{
		var decFormat									= document.getElementById('decFormat').value;
		
		var ajaxRequest;
		try
		{
			ajaxRequest = new XMLHttpRequest();
		}
		catch (e)
		{
			alert("Something is wrong");
			return false;
		}
		ajaxRequest.onreadystatechange = function()
		{
			if(ajaxRequest.readyState == 4)
			{
				koefVal = ajaxRequest.responseText;
				document.getElementById('EMPG_K_ALLOW').value= koefVal;
				document.getElementById('EMPG_K_ALLOW1').value= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(koefVal)),decFormat));;
			}
		}
		
		ajaxRequest.open("GET", "<?php echo base_url().'index.php/c_hr/c_master/c_gol/getKoef/';?>" + sendCode, true);
		ajaxRequest.send(null);
	}
	
	function saveGroup()
	{
		EMPG_CHILD 	= document.getElementById("EMPG_CHILD").value;
		if(EMPG_CHILD == 0)
		{
			alert('<?php echo $Alert1; ?>');
			document.getElementById("EMPG_CHILD").focus();
			return false;
		}
		
		EMPG_BASAL 	= document.getElementById("EMPG_BASAL").value;
		if(EMPG_BASAL == 0)
		{
			alert('<?php echo $Alert2; ?>');
			document.getElementById("EMPG_BASAL1").focus();
			return false;
		}
		
		countCode 	= document.getElementById("CheckThe_Code").value;
		if(countCode > 0)
		{
			alert('<?php echo $Alert3; ?>');
			document.getElementById("EMPG_CHILD").focus();
			return false;
		}
		
		EMPG_STAT 	= document.getElementById("EMPG_STAT").value;
		if(EMPG_STAT == 3 || EMPG_STAT == 5)
		{
			alert('<?php echo $Alert4; ?>');
			return false;
		}
	}
	
	var decFormat		= 2;
	
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