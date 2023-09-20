<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 15 Maret 2017
 * File Name	= project_recomendation_form.php
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

if($task == 'add')
{
	$proj_Type = 1;
	$proj_Category = 2;
	$proj_PM_EmpID = '';
	//$proj_CustCode = '';
	$PRJCURR = 'IDR';
	$default['PRJNAME'] = '';
	foreach($viewDocPattern as $row) :
		$Pattern_Code = $row->Pattern_Code;
		$Pattern_Position = $row->Pattern_Position;
		$Pattern_YearAktive = $row->Pattern_YearAktive;
		$Pattern_MonthAktive = $row->Pattern_MonthAktive;
		$Pattern_DateAktive = $row->Pattern_DateAktive;
		//$Pattern_Length = $row->Pattern_Length;
		$Pattern_Length = 3;
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
	$year = (int)$Pattern_YearAktive;
	$month = (int)$Pattern_MonthAktive;
	$date = (int)$Pattern_DateAktive;

	$this->db->where('Patt_Year', $year);
	$this->db->where('Patt_Month', $month);
	$this->db->where('Patt_Date', $date);
	$myCount = $this->db->count_all('tbl_project');
	
	$sql = "SELECT MAX(Patt_Number) as maxNumber FROM tbl_project_recom
			WHERE Patt_Year = $year";
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
	
	//echo $pattMonth;
	//echo "&nbsp;";
	//echo $pattDate;
	
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
	$DocNumber = "$Pattern_Code$groupPattern-$lastPatternNumb";
	
	$REC_DATEA 		= date('Y');
	$REC_DATEB 		= date('m');
	$REC_DATEC 		= date('d');
	$REC_DATED 		= "$REC_DATEB/$REC_DATEC/$REC_DATEA";
	
	$REC_CODE 			= $DocNumber;
	$REC_DATEAX			= substr($REC_DATEA, 2, 4);
	$REC_NO				= "$REC_DATEB$REC_DATEAX.$lastPatternNumb-S";
	$REC_NO1			= "$REC_DATEB$REC_DATEAX.$lastPatternNumb-";
	$REC_NO 			= $REC_NO;
	$REC_NO1 			= $REC_NO1;
	$PRJ_JNS			= 'S';
	$REC_CONC_NOTES		= '';
	$REC_LL_NO			= '';
	$REC_PAGE_NO		= 1;
	$REC_PRJNAME		= '';
	$REC_OWNER			= '';
	$REC_CURRENCY		= 'IDR';
	$REC_VALUE			= 0;
	$REC_CONSULT_ARS	= '';
	$REC_CONSULT_QS		= '';
	$REC_LOCATION		= '';
	$REC_DATE			= $REC_DATED;
	$REC_PQ_DATE		= $REC_DATED;
	$REC_TEND_DATE		= $REC_DATED;
	$REC_PRJTYPE		= '';
	$REC_FUNDSRC		= 'X';
	$REC_FUNDSRC_APBN	= 0;
	$REC_FUNDSRC_APBD	= 0;
	$REC_FUNDSRC_PRIV	= 1;
	$REC_FUNDSRC_LOAN	= 0;
	$REC_FUNDSRC_OTH	= 0;
	$REC_PAY_SYS		= '';
	$REC_DP				= '';
	$REC_TURNOVER		= '';
	$REC_EXP			= '';
	$REC_BASCAPAB		= '';
	$REC_FINCAPAB		= '';
	$REC_DATEXEC		= 0;
	$REC_DIFICLEV		= '';
	$REC_PRJFIELD		= '';
	$REC_MTRSRC			= '';
	$REC_TIMEXEC		= '';
	$REC_PQ_ESTIME		= '';
	$REC_TEND_ESTIME	= '';
	$REC_BIDDER			= '';
	$REC_BIDDER_QTY		= '';
	$REC_ESKAL_EST		= '';
	$REC_CONCLUTION		= '';
	$REC_CONC_TARGET	= '';
	$REC_SIGN_DATE		= $REC_DATED;
	$REC_MTRSRC			= '';
	$REC_USR_MRK_STAT	= 1;
	$REC_USR_MRK		= '';
	$REC_MNG_MRK_STAT	= 1;
	$REC_MNG_MRK		= '';
	$REC_DIR_MRK_STAT	= 1;
	$REC_DIR_MRK		= '';
	$REC_MNG_EST_STAT	= 1;
	$REC_MNG_EST		= '';
	$REC_PRESDIR		= 1;
	$REC_NOTES			= '';
	$REC_NOTES_EST		= '';
	
	$REC_FUNDSRC_NOTE	= '';
	$REC_PAY_SYS_NOTE	= '';
	$REC_DP_NOTE		= '';
	$REC_TURNOVER_NOTE	= '';
	$REC_EXP_NOTE		= '';
	$REC_BASCAPAB_NOTE	= '';
	$REC_FINCAPAB_NOTE	= '';
	$REC_DATEXEC_NOTE	= '';
	$REC_TIMEXEC_NOTE	= '';
	$REC_PQ_ESTIME_NOTE	= '';
	$REC_TEND_ESTIME_NOTE	= '';
	$REC_BIDDER_NOTE	= '';
	$REC_ESKAL_EST_NOTE	= '';
	
	$REC_STAT			= 0;
	$DOK_NO				= '';
	$REVISI				= '';
	$AMAND				= '';
	$ISAMANDEMEN		= 0;
	$REC_CREATER		= '';
	$Patt_Number		= $lastPatternNumb1;
}
else
{
	$REC_CODE 			= $default['REC_CODE'];
	$REC_NO 			= $default['REC_NO'];
	$REC_NO1 			= $default['REC_NO1'];
	$PRJ_JNS			= $default['PRJ_JNS'];
	$DocNumber			= $REC_CODE;
	$REC_PAGE_NO 		= $default['REC_PAGE_NO'];
	$REC_PRJNAME 		= $default['REC_PRJNAME'];
	$REC_VALUE 			= $default['REC_VALUE'];
	$REC_OWNER 			= $default['REC_OWNER'];
	$REC_CURRENCY		= $default['REC_CURRENCY'];
	$REC_CONSULT_ARS 	= $default['REC_CONSULT_ARS'];
	$REC_CONSULT_QS 	= $default['REC_CONSULT_QS'];
	$REC_LOCATION 		= $default['REC_LOCATION'];			
	$REC_DATE 			= $default['REC_DATE'];			
	$REC_TEND_DATED		= $default['REC_TEND_DATE'];
	$REC_TEND_DATE 		= date('m/d/Y',strtotime($REC_TEND_DATED));
	$REC_FUNDSRC		= $default['REC_FUNDSRC'];
	$REC_FUNDSRC_APBN	= $default['REC_FUNDSRC_APBN'];
	$REC_FUNDSRC_APBD	= $default['REC_FUNDSRC_APBD'];
	$REC_FUNDSRC_PRIV	= $default['REC_FUNDSRC_PRIV'];
	$REC_FUNDSRC_LOAN	= $default['REC_FUNDSRC_LOAN'];
	$REC_FUNDSRC_OTH	= $default['REC_FUNDSRC_OTH'];
	$REC_PAY_SYS 		= $default['REC_PAY_SYS'];
	$REC_DP 			= $default['REC_DP'];
	$REC_TURNOVER 		= $default['REC_TURNOVER'];			
	$REC_EXP			= $default['REC_EXP'];
	$REC_BASCAPAB		= $default['REC_BASCAPAB'];
	$REC_FINCAPAB		= $default['REC_FINCAPAB'];
	//$REC_DATEXECD 		= $default['REC_DATEXEC'];
	$REC_DATEXEC 		= $default['REC_DATEXEC'];
	$REC_PQ_ESTIME 		= $default['REC_PQ_ESTIME'];
	$REC_TEND_ESTIME 	= $default['REC_TEND_ESTIME'];
	$REC_BIDDER 		= $default['REC_BIDDER'];
	$REC_BIDDER_QTY 	= $default['REC_BIDDER_QTY'];
	$REC_ESKAL_EST 		= $default['REC_ESKAL_EST'];
	$REC_CONCLUTION		= $default['REC_CONCLUTION'];
	$REC_CONC_TARGET 	= $default['REC_CONC_TARGET'];
	$REC_CONC_NOTES 	= $default['REC_CONC_NOTES'];
	$REC_NOTES 			= $default['REC_NOTES'];
	$REC_NOTES_EST 		= $default['REC_NOTES_EST'];
	$REC_USR_MRK_STAT	= $default['REC_USR_MRK_STAT'];
	$REC_USR_MRK 		= $default['REC_USR_MRK'];
	$REC_MNG_MRK_STAT	= $default['REC_MNG_MRK_STAT'];
	$REC_MNG_MRK 		= $default['REC_MNG_MRK'];
	$REC_DIR_MRK_STAT	= $default['REC_DIR_MRK_STAT'];
	$REC_DIR_MRK 		= $default['REC_DIR_MRK'];
	$REC_MNG_EST_STAT	= $default['REC_MNG_EST_STAT'];
	$REC_MNG_EST 		= $default['REC_MNG_EST'];
	$REC_PRESDIR 		= $default['REC_PRESDIR'];
	$REC_NOTES 			= $default['REC_NOTES'];
							
	$REC_FUNDSRC_NOTE	= $default['REC_FUNDSRC_NOTE'];
	$REC_PAY_SYS_NOTE	= $default['REC_PAY_SYS_NOTE'];
	$REC_DP_NOTE		= $default['REC_DP_NOTE'];
	$REC_TURNOVER_NOTE	= $default['REC_TURNOVER_NOTE'];
	$REC_EXP_NOTE		= $default['REC_EXP_NOTE'];
	$REC_BASCAPAB_NOTE	= $default['REC_BASCAPAB_NOTE'];
	$REC_FINCAPAB_NOTE	= $default['REC_FINCAPAB_NOTE'];
	$REC_TIMEXEC_NOTE	= $default['REC_TIMEXEC_NOTE'];
	$REC_PQ_ESTIME_NOTE	= $default['REC_PQ_ESTIME_NOTE'];
	$REC_TEND_ESTIME_NOTE	= $default['REC_TEND_ESTIME_NOTE'];
	$REC_BIDDER_NOTE	= $default['REC_BIDDER_NOTE'];
	$REC_ESKAL_EST_NOTE	= $default['REC_ESKAL_EST_NOTE'];	
	
	$REC_STAT 			= $default['REC_STAT'];
	$DOK_NO 			= $default['DOK_NO'];
	$REVISI 			= $default['REVISI'];
	$AMAND 				= $default['AMAND'];
	$ISAMANDEMEN		= 0;
	$REC_CREATER 		= $default['REC_CREATER'];
	$Patt_Number 		= $default['Patt_Number'];
	$lastPatternNumb1	= $Patt_Number;
}

$isShow1 = 0;
$isShow2 = 0;
$isShow3 = 0;
$isShowx = 0;
// Get Employee's Authorization (Add) ---- Masih manual inject to database
$sqlGetC1			= "tbl_auth WHERE Emp_ID = '$DefEmp_ID' AND AUTH_CODE = 'ADD_PRJ_REKOM'";
$resGetC1			= $this->db->count_all($sqlGetC1);
// Get Employee's Authorization (Confirm)
$sqlGetC2			= "tbl_auth WHERE Emp_ID = '$DefEmp_ID' AND AUTH_CODE = 'CONF_PRJ_REKOM'";
$resGetC2			= $this->db->count_all($sqlGetC2);
// Get Employee's Authorization (App)
$sqlGetC3			= "tbl_auth WHERE Emp_ID = '$DefEmp_ID' AND AUTH_CODE = 'APP_PRJ_REKOM'";
$resGetC3			= $this->db->count_all($sqlGetC3);

if($resGetC1 > 0)
{
	$isShow1 = 1;
	$isShowx = 1;
}
if($resGetC2 > 0)
{
	$isShow2 = 1;
	$isShowx = 2;
}
if($resGetC3 > 0)
{
	$isShow3 = 1;
	$isShowx = 3;
}

if($FlagUSER == 'SUPERADMIN')
{
	$isShow1 = 1;
	$isShow2 = 1;
	$isShow3 = 1;
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
	if($TranslCode == 'Back')$Back = $LangTransl;
	if($TranslCode == 'Save')$Save = $LangTransl;
	if($TranslCode == 'Update')$Update = $LangTransl;

endforeach;


?>

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>
    <?php echo $h2_title; ?>
    <small><?php echo $h3_title; ?></small>
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
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body chart-responsive">
                	<form class="form-horizontal" name="frm" id="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return saveRecomend()">
                    	<input type="hidden" name="REC_STAT" id="REC_STAT" value="<?php echo $REC_STAT; ?>" />
                    	<input type="hidden" name="REC_STAT_USR" id="REC_STAT_USR" value="<?php echo $REC_USR_MRK_STAT; ?>" />
                    	<input type="hidden" name="REC_STAT_EST" id="REC_STAT_EST" value="<?php echo $REC_MNG_EST_STAT; ?>" />
                    	<input type="hidden" name="task" id="task" value="<?php echo $task; ?>" />
                    	<input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
            			<input type="Hidden" name="rowCount" id="rowCount" value="0">
                        <input type="hidden" name="REC_DATE" class="form-control pull-left" value="<?php echo $REC_DATE; ?>" style="width:150px">
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Doc. Code</label>
                          	<div class="col-sm-10">
                            	<?php echo $DocNumber; ?>
                                <input type="hidden"  id="lastPatternNumb" name="lastPatternNumb" size="20" value="<?php echo $lastPatternNumb1; ?>" />
                                <input type="hidden" name="Patt_Number" id="Patt_Number" value="<?php echo $Patt_Number; ?>">
                                <input type="hidden" name="REC_CODE" id="REC_CODE" value="<?php echo $DocNumber; ?>" >
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Recomen. No.</label>
                          	<div class="col-sm-10">
                            	<label>
                                <input type="text" class="form-control" style="max-width:200px;" name="REC_NO" id="REC_NO" value="<?php echo $REC_NO; ?>" maxlength="15" onChange="functioncheck(this.value)">
                                <input type="hidden" class="form-control" style="max-width:200px;" name="REC_NO1" id="REC_NO1" value="<?php echo $REC_NO1; ?>" maxlength="15" onChange="functioncheck(this.value)">
                            	</label><label>&nbsp;&nbsp;</label><label id="isHidden"></label>&nbsp;&nbsp;&nbsp;
                            	<input type="hidden" name="CheckThe_Code" id="CheckThe_Code" size="20" maxlength="25" value="0" >
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Jenis Proyek</label>
                          	<div class="col-sm-10">
                                <select name="PRJ_JNS" id="PRJ_JNS" class="form-control" style="max-width:100px" <?php if($isShowx != 1) { ?> onClick="checkAuth('PRJ_JNS', '<?php echo $PRJ_JNS; ?>')" <?php } ?> onChange="changeJNS(this)">
                                <option value="S" <?php if($PRJ_JNS == "S") { ?>selected <?php } ?>>Sipil</option>
                                <option value="G" <?php if($PRJ_JNS == "G") { ?>selected <?php } ?>>Gedung</option>
                                <option value="M" <?php if($PRJ_JNS == "M") { ?>selected <?php } ?> style="display:none">MEP</option>
                                <option value="C" <?php if($PRJ_JNS == "C") { ?>selected <?php } ?> style="display:none">Composite</option>
                            </select>
                          	</div>
                        </div>
						<script>
							function changeJNS(thisVal)
							{
								REC_JNS	= thisVal.value;
								REC_NO1	= document.getElementById("REC_NO1").value;
								REC_NO	= REC_NO1+REC_JNS;
								document.getElementById("REC_NO").value = REC_NO;
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
                                            document.getElementById("isHidden").innerHTML = ' Recomendation No already exist ... !';
                                            document.getElementById("isHidden").style.color = "#ff0000";
                                        }
                                        else
                                        {
                                            document.getElementById('CheckThe_Code').value= recordcount;
                                            document.getElementById("isHidden").innerHTML = ' Recomendation No : OK .. !';
                                            document.getElementById("isHidden").style.color = "green";
                                        }
                                    }
                                }
                                var REC_NO = document.getElementById('REC_NO').value;
                                
                                ajaxRequest.open("GET", "<?php echo base_url().'index.php/c_project/project_recomendation/getTheCode/';?>" + REC_NO, true);
                                ajaxRequest.send(null);
                            }
                        </script>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Nama Proyek</label>
                          	<div class="col-sm-10">
                            	<?php 
									if($isShowx == 1) 
									{ 
										?>
										<input type="text" class="form-control" style="max-width:350px" name="REC_PRJNAME" id="REC_PRJNAME" size="50" value="<?php echo $REC_PRJNAME; ?>" >
										<?php
									}
									else
									{
										?>
										<input type="text" class="form-control" style="max-width:350px" name="REC_PRJNAME" id="REC_PRJNAME" size="50" value="<?php echo $REC_PRJNAME; ?>"  onClick="checkAuth('REC_PRJNAME', '<?php echo $REC_PRJNAME; ?>')" disabled >
                                        <?php
									}
								?>
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Mata Uang</label>
                          	<div class="col-sm-10">
                            	<?php 
									if($isShowx == 1) 
									{ 
										?>
                                            <select name="REC_CURRENCY" id="REC_CURRENCY" class="form-control" style="max-width:80px" <?php if($isShowx != 1) { ?> onClick="checkAuth('REC_CURRENCY', '<?php echo $REC_CURRENCY; ?>')" <?php } ?>>
                                                <option value="IDR" <?php if($REC_CURRENCY == "IDR") { ?>selected <?php } ?>>IDR</option>
                                                <option value="USD" <?php if($REC_CURRENCY == "USD") { ?>selected <?php } ?>>USD</option>
                                            </select>
										<?php
									}
									else
									{
										?>
                                            <select name="REC_CURRENCY" id="REC_CURRENCY" class="form-control" style="max-width:80px" <?php if($isShowx != 1) { ?> onClick="checkAuth('REC_CURRENCY', '<?php echo $REC_CURRENCY; ?>')" <?php } ?> disabled>
                                                <option value="IDR" <?php if($REC_CURRENCY == "IDR") { ?>selected <?php } ?>>IDR</option>
                                                <option value="USD" <?php if($REC_CURRENCY == "USD") { ?>selected <?php } ?>>USD</option>
                                            </select>
                                        <?php
									}
								?>
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Nilai (+ / -)</label>
                          	<div class="col-sm-10">
                            	<?php 
									if($isShowx == 1) 
									{ 
										?>
                                            <input type="text" class="form-control" style="max-width:200px; text-align:right" name="REC_VALUE1" id="REC_VALUE1" value="<?php print number_format($REC_VALUE, $decFormat); ?>" maxlength="15" onBlur="getValue(this)" onKeyPress="return isIntOnlyNew(event);" >
                                            <input type="hidden" class="form-control" style="max-width:200px; text-align:right" name="REC_VALUE" id="REC_VALUE" value="<?php echo $REC_VALUE; ?>" maxlength="15" onBlur="getValue(this.value)" onKeyPress="return isIntOnlyNew(event);" >
										<?php
									}
									else
									{
										?>
                                            <input type="text" class="form-control" style="max-width:200px; text-align:right" name="REC_VALUE1" id="REC_VALUE1" value="<?php print number_format($REC_VALUE, $decFormat); ?>" maxlength="15" onBlur="getValue(this)" onKeyPress="return isIntOnlyNew(event);" disabled >
                                            <input type="hidden" class="form-control" style="max-width:200px; text-align:right" name="REC_VALUE" id="REC_VALUE" value="<?php echo $REC_VALUE; ?>" maxlength="15" onBlur="getValue(this.value)" onKeyPress="return isIntOnlyNew(event);" >
                                        <?php
									}
								?>
                          	</div>
                        </div>
                        <script>
							function getValue(thisVal)
							{
								var decFormat		= document.getElementById('decFormat').value;
								var REC_VALUEX		= eval(thisVal).value.split(",").join("");
								document.getElementById('REC_VALUE1').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(REC_VALUEX)), decFormat));
								document.getElementById('REC_VALUE').value 		= REC_VALUEX;
							}
						</script>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Nama Owner</label>
                          	<div class="col-sm-10">
                            	<?php 
									if($isShowx == 1) 
									{ 
										?>
                                            <select name="REC_OWNER" id="REC_OWNER" class="form-control" style="max-width:350px" >
                                            <option value=""> --- none ---</option>
                                            <?php
                                                $own_Code 	= '';
                                                $CountOwn 	= $this->db->count_all('tbl_owner');
                                                $sqlOwn 	= "SELECT own_Code, own_Title, own_Name FROM tbl_owner";
                                                $resultOwn = $this->db->query($sqlOwn)->result();
                                                if($CountOwn > 0)
                                                {
                                                    foreach($resultOwn as $rowOwn) :
                                                        $own_Title = $rowOwn->own_Title;
                                                        $own_Code = $rowOwn->own_Code;
                                                        $own_Name = $rowOwn->own_Name;
                                                        ?>
                                                        <option value="<?php echo $own_Code; ?>" <?php if($own_Code == $REC_OWNER) { ?>selected <?php } ?>> <?php echo $own_Name; if($own_Title != '') { echo", $own_Title"; } ?> </option>
                                                        <?php
                                                     endforeach;
                                                 }
                                            ?>
                                        </select>
										<?php
									}
									else
									{
										?>
                                            <select name="REC_OWNER" id="REC_OWNER" class="form-control" style="max-width:350px" <?php if($isShowx != 1) { ?> onClick="checkAuth('REC_OWNER', '<?php echo $REC_OWNER; ?>')" <?php } ?> disabled>
                                            <option value=""> --- none ---</option>
                                            <?php
                                                $own_Code 	= '';
                                                $CountOwn 	= $this->db->count_all('tbl_owner');
                                                $sqlOwn 	= "SELECT own_Code, own_Title, own_Name FROM tbl_owner";
                                                $resultOwn = $this->db->query($sqlOwn)->result();
                                                if($CountOwn > 0)
                                                {
                                                    foreach($resultOwn as $rowOwn) :
                                                        $own_Title = $rowOwn->own_Title;
                                                        $own_Code = $rowOwn->own_Code;
                                                        $own_Name = $rowOwn->own_Name;
                                                        ?>
                                                        <option value="<?php echo $own_Code; ?>" <?php if($own_Code == $REC_OWNER) { ?>selected <?php } ?>> <?php echo $own_Name; if($own_Title != '') { echo", $own_Title"; } ?> </option>
                                                        <?php
                                                     endforeach;
                                                 }
                                            ?>
                                        </select>
                                        <?php
									}
								?>
                                
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Konsultan ARS</label>
                          	<div class="col-sm-10">
                            	<?php 
									if($isShowx == 1) 
									{ 
										?>
                            				<input type="text" class="form-control" style="max-width:350px" name="REC_CONSULT_ARS" id="REC_CONSULT_ARS" size="50" value="<?php echo $REC_CONSULT_ARS; ?>" <?php if($isShowx != 1) { ?> onClick="checkAuth('REC_CONSULT_ARS', '<?php echo $REC_CONSULT_ARS; ?>')" <?php } ?>>
										<?php
									}
									else
									{
										?>
                            				<input type="text" class="form-control" style="max-width:350px" name="REC_CONSULT_ARS" id="REC_CONSULT_ARS" size="50" value="<?php echo $REC_CONSULT_ARS; ?>" <?php if($isShowx != 1) { ?> onClick="checkAuth('REC_CONSULT_ARS', '<?php echo $REC_CONSULT_ARS; ?>')" <?php } ?> disabled>
                                        <?php
									}
								?>
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">QS</label>
                          	<div class="col-sm-10">
                            	<?php 
									if($isShowx == 1) 
									{ 
										?>
                            				<input type="text" class="form-control" style="max-width:350px" name="REC_CONSULT_QS" id="REC_CONSULT_QS" size="50" value="<?php echo $REC_CONSULT_QS; ?>" <?php if($isShowx != 1) { ?> onClick="checkAuth('REC_CONSULT_QS', '<?php echo $REC_CONSULT_QS; ?>')" <?php } ?>>
										<?php
									}
									else
									{
										?>
                            				<input type="text" class="form-control" style="max-width:350px" name="REC_CONSULT_QS" id="REC_CONSULT_QS" size="50" value="<?php echo $REC_CONSULT_QS; ?>" <?php if($isShowx != 1) { ?> onClick="checkAuth('REC_CONSULT_QS', '<?php echo $REC_CONSULT_QS; ?>')" <?php } ?> disabled>
                                        <?php
									}
								?>
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Lokasi</label>
                          	<div class="col-sm-10">
                            	<?php 
									if($isShowx == 1) 
									{ 
										?>
                            				<input type="text" class="form-control" style="max-width:350px" name="REC_LOCATION" id="REC_LOCATION" size="50" value="<?php echo $REC_LOCATION; ?>" <?php if($isShowx != 1) { ?> onClick="checkAuth('REC_LOCATION', '<?php echo $REC_LOCATION; ?>')" <?php } ?>>
										<?php
									}
									else
									{
										?>
                            				<input type="text" class="form-control" style="max-width:350px" name="REC_LOCATION" id="REC_LOCATION" size="50" value="<?php echo $REC_LOCATION; ?>" <?php if($isShowx != 1) { ?> onClick="checkAuth('REC_LOCATION', '<?php echo $REC_LOCATION; ?>')" <?php } ?> disabled>
                                        <?php
									}
								?>
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Perkiraan waktu tender</label>
                          	<div class="col-sm-10">
                            	<div class="input-group date">
                                    <div class="input-group-addon">
                                    	<i class="fa fa-calendar"></i>&nbsp;
                                    </div>
									<?php 
                                        if($isShowx == 1) 
                                        { 
                                            ?>
                                    			<input type="text" name="REC_TEND_DATE" class="form-control pull-left" id="datepicker1" value="<?php echo $REC_TEND_DATE; ?>" style="width:150px" <?php if($isShowx != 1) { ?> onClick="checkAuth('REC_PRJNAME', '<?php echo $REC_PRJNAME; ?>')" <?php } ?>>
                                            <?php
                                        }
                                        else
                                        {
                                            ?>
                                    			<input type="text" name="REC_TEND_DATE" class="form-control pull-left" id="datepicker1" value="<?php echo $REC_TEND_DATE; ?>" style="width:150px" <?php if($isShowx != 1) { ?> onClick="checkAuth('REC_PRJNAME', '<?php echo $REC_PRJNAME; ?>')" <?php } ?> disabled>
                                            <?php
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Perkiraan Waktu PQ</label>
                          	<div class="col-sm-10">
							<?php 
                                if($isShowx == 1) 
                                { 
                                    ?>
                                        <input type="radio" name="REC_PQ_ESTIME" id="REC_PQ_ESTIME1" value="1" <?php if($REC_PQ_ESTIME == 1) { ?> checked <?php } ?> class="minimal">
                                        &nbsp;&nbsp;Cukup Waktu&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="radio" name="REC_PQ_ESTIME" id="REC_PQ_ESTIME2" value="2" <?php if($REC_PQ_ESTIME == 2) { ?> checked <?php } ?> class="minimal">
                                        &nbsp;&nbsp;Sedang / Normal&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="radio" name="REC_PQ_ESTIME" id="REC_PQ_ESTIME3" value="3" <?php if($REC_PQ_ESTIME == 3) { ?> checked <?php } ?> class="minimal">
                                        &nbsp;&nbsp;Mendesak
                                    <?php
                                }
                                else
                                {
                                    ?>
                                        <input type="radio" name="REC_PQ_ESTIME" id="REC_PQ_ESTIME1" value="1" <?php if($REC_PQ_ESTIME == 1) { ?> checked <?php } if($isShowx != 1) { ?> onClick="checkAuthRad('REC_PQ_ESTIME1', '<?php echo $REC_PQ_ESTIME; ?>')" <?php } else { ?> class="minimal" <?php } ?> disabled>
                                        &nbsp;&nbsp;Cukup Waktu&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="radio" name="REC_PQ_ESTIME" id="REC_PQ_ESTIME2" value="2" <?php if($REC_PQ_ESTIME == 2) { ?> checked <?php } if($isShowx != 1) { ?> onClick="checkAuthRad('REC_PQ_ESTIME2', '<?php echo $REC_PQ_ESTIME; ?>')" <?php } else { ?> class="minimal" <?php } ?> disabled>
                                        &nbsp;&nbsp;Sedang / Normal&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="radio" name="REC_PQ_ESTIME" id="REC_PQ_ESTIME3" value="3" <?php if($REC_PQ_ESTIME == 3) { ?> checked <?php } if($isShowx != 1) { ?> onClick="checkAuthRad('REC_PQ_ESTIME3', '<?php echo $REC_PQ_ESTIME; ?>')" <?php } else { ?> class="minimal" <?php } ?> disabled>
                                        &nbsp;&nbsp;Mendesak
                                    <?php
                                }
                            ?>
                            </div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Catatan Perkiraan PQ</label>
                          	<div class="col-sm-10">
							<?php 
                                if($isShowx == 1) 
                                { 
                                    ?>
                            			<input type="text" class="form-control" style="max-width:250px" name="REC_PQ_ESTIME_NOTE" id="REC_PQ_ESTIME_NOTE" size="50" value="<?php echo $REC_PQ_ESTIME_NOTE; ?>" >
                                    <?php
                                }
                                else
                                {
                                    ?>
                            			<input type="text" class="form-control" style="max-width:250px" name="REC_PQ_ESTIME_NOTE" id="REC_PQ_ESTIME_NOTE" size="50" value="<?php echo $REC_PQ_ESTIME_NOTE; ?>" <?php if($isShowx != 1) { ?> onClick="checkAuth('REC_PQ_ESTIME_NOTE', '<?php echo $REC_PQ_ESTIME_NOTE; ?>')" <?php } ?> disabled>
                                    <?php
                                }
                            ?>
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Sumber Dana</label>
                          	<div class="col-sm-10">
							<?php 
                                if($isShowx == 1) 
                                { 
                                    ?>
                                        <input type="text" class="form-control" style="max-width:250px; display:none" name="REC_FUNDSRC" id="REC_FUNDSRC" size="50" value="<?php echo $REC_FUNDSRC; ?>" >
                                            
                                        <input type="checkbox" name="REC_FUNDSRC_APBN" id="REC_FUNDSRC_APBN" value="1" class="minimal" <?php if($REC_FUNDSRC_APBN==1) { ?> checked <?php } ?>>
                                        &nbsp;&nbsp;APBN&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="checkbox" name="REC_FUNDSRC_APBD" id="REC_FUNDSRC_APBD" value="1" class="minimal" <?php if($REC_FUNDSRC_APBD==1) { ?> checked <?php } ?>>
                                        &nbsp;&nbsp;APBD&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="checkbox" name="REC_FUNDSRC_PRIV" id="REC_FUNDSRC_PRIV" value="1" class="minimal" <?php if($REC_FUNDSRC_PRIV==1) { ?> checked <?php } ?>>
                                        &nbsp;&nbsp;Private&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="checkbox" name="REC_FUNDSRC_LOAN" id="REC_FUNDSRC_LOAN" value="1" class="minimal" <?php if($REC_FUNDSRC_LOAN==1) { ?> checked <?php } ?>>
                                        &nbsp;&nbsp;LOAN&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="checkbox" name="REC_FUNDSRC_OTH" id="REC_FUNDSRC_OTH" value="1" class="minimal" <?php if($REC_FUNDSRC_OTH==1) { ?> checked <?php } ?>>
                                        &nbsp;&nbsp;Lainnya
                                    <?php
                                }
                                else
                                {
                                    ?>
                                        <input type="text" class="form-control" style="max-width:250px; display:none" name="REC_FUNDSRC" id="REC_FUNDSRC" size="50" value="<?php echo $REC_FUNDSRC; ?>" <?php if($isShowx != 1) { ?> onClick="checkAuth('REC_FUNDSRC', '<?php echo $REC_FUNDSRC; ?>')" <?php } ?>>
                                            
                                        <input type="checkbox" name="REC_FUNDSRC_APBN" id="REC_FUNDSRC_APBN" value="1" class="minimal" <?php if($REC_FUNDSRC_APBN==1) { ?> checked <?php } ?>>
                                        &nbsp;&nbsp;APBN&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="checkbox" name="REC_FUNDSRC_APBD" id="REC_FUNDSRC_APBD" value="1" class="minimal" <?php if($REC_FUNDSRC_APBD==1) { ?> checked <?php } ?>>
                                        &nbsp;&nbsp;APBD&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="checkbox" name="REC_FUNDSRC_PRIV" id="REC_FUNDSRC_PRIV" value="1" class="minimal" <?php if($REC_FUNDSRC_PRIV==1) { ?> checked <?php } ?>>
                                        &nbsp;&nbsp;Private&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="checkbox" name="REC_FUNDSRC_LOAN" id="REC_FUNDSRC_LOAN" value="1" class="minimal" <?php if($REC_FUNDSRC_LOAN==1) { ?> checked <?php } ?>>
                                        &nbsp;&nbsp;LOAN&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="checkbox" name="REC_FUNDSRC_OTH" id="REC_FUNDSRC_OTH" value="1" class="minimal" <?php if($REC_FUNDSRC_OTH==1) { ?> checked <?php } ?>>
                                        &nbsp;&nbsp;Lainnya
                                    <?php
                                }
                            ?>
                            </div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Catatan Sumber Dana</label>
                          	<div class="col-sm-10">
							<?php 
                                if($isShowx == 1) 
                                { 
                                    ?>
                            			<input type="text" class="form-control" style="max-width:250px" name="REC_FUNDSRC_NOTE" id="REC_FUNDSRC_NOTE" size="50" value="<?php echo $REC_FUNDSRC_NOTE; ?>" >
                                    <?php
                                }
                                else
                                {
                                    ?>
                            			<input type="text" class="form-control" style="max-width:250px" name="REC_FUNDSRC_NOTE" id="REC_FUNDSRC_NOTE" size="50" value="<?php echo $REC_FUNDSRC_NOTE; ?>" <?php if($isShowx != 1) { ?> onClick="checkAuth('REC_FUNDSRC_NOTE', '<?php echo $REC_FUNDSRC_NOTE; ?>')" <?php } ?> disabled>
                                    <?php
                                }
                            ?>
                          	</div>
                        </div>
                        <script>	
							function checkAuthRad(idName, theValue)
							{
								window.location.reload();
							}
                        </script>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Sistem Pembayaran</label>
                          	<div class="col-sm-10"> 
							<?php 
                                if($isShowx == 1) 
                                { 
                                    ?>                         	
                                        <input type="radio" name="REC_PAY_SYS" id="REC_PAY_SYS1" value="1" <?php if($REC_PAY_SYS == 1) { ?> checked <?php } ?> class="minimal" >
                                        &nbsp;&nbsp;Progres Bulanan&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="radio" name="REC_PAY_SYS" id="REC_PAY_SYS2" value="2" <?php if($REC_PAY_SYS == 2) { ?> checked <?php } ?> class="minimal" >
                                        &nbsp;&nbsp;Termijn&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="radio" name="REC_PAY_SYS" id="REC_PAY_SYS3" value="3" <?php if($REC_PAY_SYS == 3) { ?> checked <?php } ?> class="minimal" >
                                        &nbsp;&nbsp;Turnkey
                                    <?php
                                }
                                else
                                {
                                    ?>                         	
                                        <input type="radio" name="REC_PAY_SYS" id="REC_PAY_SYS1" value="1" <?php if($REC_PAY_SYS == 1) { ?> checked <?php } if($isShowx != 1) { ?> onClick="checkAuthRad('REC_PAY_SYS1', '<?php echo $REC_PAY_SYS; ?>')" <?php } else { ?> class="minimal" <?php } ?> disabled>
                                        &nbsp;&nbsp;Progres Bulanan&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="radio" name="REC_PAY_SYS" id="REC_PAY_SYS2" value="2" <?php if($REC_PAY_SYS == 2) { ?> checked <?php } if($isShowx != 1) { ?> onClick="checkAuthRad('REC_PAY_SYS2', '<?php echo $REC_PAY_SYS; ?>')" <?php } else { ?> class="minimal" <?php } ?> disabled>
                                        &nbsp;&nbsp;Termijn&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="radio" name="REC_PAY_SYS" id="REC_PAY_SYS3" value="3" <?php if($REC_PAY_SYS == 3) { ?> checked <?php } if($isShowx != 1) { ?> onClick="checkAuthRad('REC_PAY_SYS3', '<?php echo $REC_PAY_SYS; ?>')" <?php } else { ?> class="minimal" <?php } ?> disabled>
                                        &nbsp;&nbsp;Turnkey
                                    <?php
                                }
                            ?> 
                            </div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Catatan Sistem Pembayaran</label>
                          	<div class="col-sm-10">
							<?php 
                                if($isShowx == 1) 
                                { 
                                    ?>
                            			<input type="text" class="form-control" style="max-width:250px" name="REC_PAY_SYS_NOTE" id="REC_PAY_SYS_NOTE" size="50" value="<?php echo $REC_PAY_SYS_NOTE; ?>" >
                                    <?php
                                }
                                else
                                {
                                    ?>
                            			<input type="text" class="form-control" style="max-width:250px" name="REC_PAY_SYS_NOTE" id="REC_PAY_SYS_NOTE" size="50" value="<?php echo $REC_PAY_SYS_NOTE; ?>" <?php if($isShowx != 1) { ?> onClick="checkAuth('REC_PAY_SYS_NOTE', '<?php echo $REC_PAY_SYS_NOTE; ?>')" <?php } ?> disabled>
                                    <?php
                                }
                            ?>
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Uang Muka</label>
                          	<div class="col-sm-10">
							<?php 
                                if($isShowx == 1) 
                                { 
                                    ?>                        	
                                        <input type="radio" name="REC_DP" id="REC_DP1" value="1" <?php if($REC_DP == 1) { ?> checked <?php } ?> class="minimal">
                                        &nbsp;&nbsp;Ada&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="radio" name="REC_DP" id="REC_DP2" value="2" <?php if($REC_DP == 2) { ?> checked <?php } ?> class="minimal">
                                        &nbsp;&nbsp;Tidak Ada&nbsp;&nbsp;&nbsp;&nbsp;
                                    <?php
                                }
                                else
                                {
                                    ?>                           	
                                        <input type="radio" name="REC_DP" id="REC_DP1" value="1" <?php if($REC_DP == 1) { ?> checked <?php } if($isShowx != 1) { ?> onClick="checkAuthRad('REC_DP1', '<?php echo $REC_DP; ?>')" <?php } else { ?> class="minimal" <?php } ?> disabled>
                                        &nbsp;&nbsp;Ada&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="radio" name="REC_DP" id="REC_DP2" value="2" <?php if($REC_DP == 2) { ?> checked <?php } if($isShowx != 1) { ?> onClick="checkAuthRad('REC_DP2', '<?php echo $REC_DP; ?>')" <?php } else { ?> class="minimal" <?php } ?> disabled>
                                        &nbsp;&nbsp;Tidak Ada&nbsp;&nbsp;&nbsp;&nbsp;
                                    <?php
                                }
                            ?>
                            </div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Catatan Uang Muka</label>
                          	<div class="col-sm-10">
							<?php 
                                if($isShowx == 1) 
                                { 
                                    ?>
                            			<input type="text" class="form-control" style="max-width:250px" name="REC_DP_NOTE" id="REC_DP_NOTE" size="50" value="<?php echo $REC_DP_NOTE; ?>" >
                                    <?php
                                }
                                else
                                {
                                    ?>
                            			<input type="text" class="form-control" style="max-width:250px" name="REC_DP_NOTE" id="REC_DP_NOTE" size="50" value="<?php echo $REC_DP_NOTE; ?>" <?php if($isShowx != 1) { ?> onClick="checkAuth('REC_DP_NOTE', '<?php echo $REC_DP_NOTE; ?>')" <?php } ?> disabled>
                                    <?php
                                }
                            ?>
                          	</div>
                        </div>
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title" style="font-weight:bold">Persyaratan Utama PQ:</h3>
                            </div>
                            <div class="form-group">
                                <label for="inputName" class="col-sm-2 control-label">Turn Over</label>
                                <div class="col-sm-10">
								<?php 
                                    if($isShowx == 1) 
                                    { 
                                        ?>
                                            <input type="radio" name="REC_TURNOVER" id="REC_TURNOVER1" value="1" <?php if($REC_TURNOVER == 1) { ?> checked <?php } ?> class="minimal" >
                                        &nbsp;&nbsp;Terpenuhi&nbsp;&nbsp;&nbsp;&nbsp;
                                            <input type="radio" name="REC_TURNOVER" id="REC_TURNOVER2" value="2" <?php if($REC_TURNOVER == 2) { ?> checked  <?php } ?> class="minimal" >
                                        &nbsp;&nbsp;Tidak Terpenuhi&nbsp;&nbsp;&nbsp;&nbsp;
                                        <?php
                                    }
                                    else
                                    {
                                        ?>
                                            <input type="radio" name="REC_TURNOVER" id="REC_TURNOVER1" value="1" <?php if($REC_TURNOVER == 1) { ?> checked <?php } if($isShowx != 1) { ?> onClick="checkAuthRad('REC_TURNOVER1', '<?php echo $REC_TURNOVER; ?>')" <?php } else { ?> class="minimal" <?php } ?> disabled>
                                        &nbsp;&nbsp;Terpenuhi&nbsp;&nbsp;&nbsp;&nbsp;
                                            <input type="radio" name="REC_TURNOVER" id="REC_TURNOVER2" value="2" <?php if($REC_TURNOVER == 2) { ?> checked <?php } if($isShowx != 1) { ?> onClick="checkAuthRad('REC_TURNOVER2', '<?php echo $REC_TURNOVER; ?>')" <?php } else { ?> class="minimal" <?php } ?> disabled>
                                        &nbsp;&nbsp;Tidak Terpenuhi&nbsp;&nbsp;&nbsp;&nbsp;
                                        <?php
                                    }
                                ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputName" class="col-sm-2 control-label">Catatan Turn Over</label>
                                <div class="col-sm-10">
								<?php 
                                    if($isShowx == 1) 
                                    { 
                                        ?>
                                            <input type="text" class="form-control" style="max-width:250px" name="REC_TURNOVER_NOTE" id="REC_TURNOVER_NOTE" size="50" value="<?php echo $REC_TURNOVER_NOTE; ?>" >
                                        <?php
                                    }
                                    else
                                    {
                                        ?>
                                            <input type="text" class="form-control" style="max-width:250px" name="REC_TURNOVER_NOTE" id="REC_TURNOVER_NOTE" size="50" value="<?php echo $REC_TURNOVER_NOTE; ?>" <?php if($isShowx != 1) { ?> onClick="checkAuth('REC_TURNOVER_NOTE', '<?php echo $REC_TURNOVER_NOTE; ?>')" <?php } ?> disabled>
                                        <?php
                                    }
                                ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputName" class="col-sm-2 control-label">Pengalaman Sejenis</label>
                                <div class="col-sm-10">
								<?php 
                                    if($isShowx == 1) 
                                    { 
                                        ?>                          	
                                            <input type="radio" name="REC_EXP" id="REC_EXP1" value="1" <?php if($REC_EXP == 1) { ?> checked <?php } if($isShowx != 1) { ?> onClick="checkAuthRad('REC_EXP1', '<?php echo $REC_EXP; ?>')" <?php } else { ?> class="minimal" <?php } ?>>
                                            &nbsp;&nbsp;Terpenuhi&nbsp;&nbsp;&nbsp;&nbsp;
                                            <input type="radio" name="REC_EXP" id="REC_EXP2" value="2" <?php if($REC_EXP == 2) { ?> checked <?php } if($isShowx != 1) { ?> onClick="checkAuthRad('REC_EXP2', '<?php echo $REC_EXP; ?>')" <?php } else { ?> class="minimal" <?php } ?>>
                                            &nbsp;&nbsp;Tidak Terpenuhi&nbsp;&nbsp;&nbsp;&nbsp;
                                        <?php
                                    }
                                    else
                                    {
                                        ?>                          	
                                            <input type="radio" name="REC_EXP" id="REC_EXP1" value="1" <?php if($REC_EXP == 1) { ?> checked <?php } if($isShowx != 1) { ?> onClick="checkAuthRad('REC_EXP1', '<?php echo $REC_EXP; ?>')" <?php } else { ?> class="minimal" <?php } ?> disabled>
                                            &nbsp;&nbsp;Terpenuhi&nbsp;&nbsp;&nbsp;&nbsp;
                                            <input type="radio" name="REC_EXP" id="REC_EXP2" value="2" <?php if($REC_EXP == 2) { ?> checked <?php } if($isShowx != 1) { ?> onClick="checkAuthRad('REC_EXP2', '<?php echo $REC_EXP; ?>')" <?php } else { ?> class="minimal" <?php } ?> disabled>
                                            &nbsp;&nbsp;Tidak Terpenuhi&nbsp;&nbsp;&nbsp;&nbsp;
                                        <?php
                                    }
                                ?> 
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputName" class="col-sm-2 control-label">Catatan Pengalaman Sejenis</label>
                                <div class="col-sm-10">
								<?php 
                                    if($isShowx == 1) 
                                    { 
                                        ?>
                                    		<input type="text" class="form-control" style="max-width:250px" name="REC_EXP_NOTE" id="REC_EXP_NOTE" size="50" value="<?php echo $REC_EXP_NOTE; ?>" <?php if($isShowx != 1) { ?> onClick="checkAuth('REC_EXP_NOTE', '<?php echo $REC_EXP_NOTE; ?>')" <?php } ?>>
                                        <?php
                                    }
                                    else
                                    {
                                        ?>
                                    		<input type="text" class="form-control" style="max-width:250px" name="REC_EXP_NOTE" id="REC_EXP_NOTE" size="50" value="<?php echo $REC_EXP_NOTE; ?>" <?php if($isShowx != 1) { ?> onClick="checkAuth('REC_EXP_NOTE', '<?php echo $REC_EXP_NOTE; ?>')" <?php } ?> disabled>
                                        <?php
                                    }
                                ?> 
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputName" class="col-sm-2 control-label">Kemampuan Dasar</label>
                                <div class="col-sm-10">
								<?php 
                                    if($isShowx == 1) 
                                    { 
                                        ?>
                                            <input type="radio" name="REC_BASCAPAB" id="REC_BASCAPAB1" value="1" <?php if($REC_BASCAPAB == 1) { ?> checked <?php } if($isShowx != 1) { ?> onClick="checkAuthRad('REC_BASCAPAB1', '<?php echo $REC_BASCAPAB; ?>')" <?php } else { ?> class="minimal" <?php } ?>> 
                                            &nbsp;&nbsp;Terpenuhi&nbsp;&nbsp;&nbsp;&nbsp;
                                            <input type="radio" name="REC_BASCAPAB" id="REC_BASCAPAB2" value="2" <?php if($REC_BASCAPAB == 2) { ?> checked <?php } if($isShowx != 1) { ?> onClick="checkAuthRad('REC_BASCAPAB2', '<?php echo $REC_BASCAPAB; ?>')" <?php } else { ?> class="minimal" <?php } ?>>
                                            &nbsp;&nbsp;Tidak Terpenuhi&nbsp;&nbsp;&nbsp;&nbsp;
                                        <?php
                                    }
                                    else
                                    {
                                        ?>
                                            <input type="radio" name="REC_BASCAPAB" id="REC_BASCAPAB1" value="1" <?php if($REC_BASCAPAB == 1) { ?> checked <?php } if($isShowx != 1) { ?> onClick="checkAuthRad('REC_BASCAPAB1', '<?php echo $REC_BASCAPAB; ?>')" <?php } else { ?> class="minimal" <?php } ?> disabled> 
                                            &nbsp;&nbsp;Terpenuhi&nbsp;&nbsp;&nbsp;&nbsp;
                                            <input type="radio" name="REC_BASCAPAB" id="REC_BASCAPAB2" value="2" <?php if($REC_BASCAPAB == 2) { ?> checked <?php } if($isShowx != 1) { ?> onClick="checkAuthRad('REC_BASCAPAB2', '<?php echo $REC_BASCAPAB; ?>')" <?php } else { ?> class="minimal" <?php } ?> disabled>
                                            &nbsp;&nbsp;Tidak Terpenuhi&nbsp;&nbsp;&nbsp;&nbsp;
                                        <?php
                                    }
                                ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputName" class="col-sm-2 control-label">Catatan Kemampuan Dasar</label>
                                <div class="col-sm-10">
								<?php 
                                    if($isShowx == 1) 
                                    { 
                                        ?>
                                    		<input type="text" class="form-control" style="max-width:250px" name="REC_BASCAPAB_NOTE" id="REC_BASCAPAB_NOTE" size="50" value="<?php echo $REC_BASCAPAB_NOTE; ?>" <?php if($isShowx != 1) { ?> onClick="checkAuth('REC_BASCAPAB_NOTE', '<?php echo $REC_BASCAPAB_NOTE; ?>')" <?php } ?>>
                                        <?php
                                    }
                                    else
                                    {
                                        ?>
                                    		<input type="text" class="form-control" style="max-width:250px" name="REC_BASCAPAB_NOTE" id="REC_BASCAPAB_NOTE" size="50" value="<?php echo $REC_BASCAPAB_NOTE; ?>" <?php if($isShowx != 1) { ?> onClick="checkAuth('REC_BASCAPAB_NOTE', '<?php echo $REC_BASCAPAB_NOTE; ?>')" <?php } ?> disabled>
                                        <?php
                                    }
                                ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputName" class="col-sm-2 control-label">Kemampuan Keuangan</label>
                                <div class="col-sm-10">
								<?php 
                                    if($isShowx == 1) 
                                    { 
                                        ?>                          	
                                            <input type="radio" name="REC_FINCAPAB" id="REC_FINCAPAB1" value="1" <?php if($REC_FINCAPAB == 1) { ?> checked <?php } if($isShowx != 1) { ?> onClick="checkAuthRad('REC_FINCAPAB1', '<?php echo $REC_FINCAPAB; ?>')" <?php } else { ?> class="minimal" <?php } ?>>
                                            &nbsp;&nbsp;Terpenuhi&nbsp;&nbsp;&nbsp;&nbsp;
                                            <input type="radio" name="REC_FINCAPAB" id="REC_FINCAPAB2" value="2" <?php if($REC_FINCAPAB == 2) { ?> checked <?php } if($isShowx != 1) { ?> onClick="checkAuthRad('REC_FINCAPAB2', '<?php echo $REC_FINCAPAB; ?>')" <?php } else { ?> class="minimal" <?php } ?>>
                                            &nbsp;&nbsp;Tidak Terpenuhi&nbsp;&nbsp;&nbsp;&nbsp;
                                        <?php
                                    }
                                    else
                                    {
                                        ?>                          	
                                            <input type="radio" name="REC_FINCAPAB" id="REC_FINCAPAB1" value="1" <?php if($REC_FINCAPAB == 1) { ?> checked <?php } if($isShowx != 1) { ?> onClick="checkAuthRad('REC_FINCAPAB1', '<?php echo $REC_FINCAPAB; ?>')" <?php } else { ?> class="minimal" <?php } ?> disabled>
                                            &nbsp;&nbsp;Terpenuhi&nbsp;&nbsp;&nbsp;&nbsp;
                                            <input type="radio" name="REC_FINCAPAB" id="REC_FINCAPAB2" value="2" <?php if($REC_FINCAPAB == 2) { ?> checked <?php } if($isShowx != 1) { ?> onClick="checkAuthRad('REC_FINCAPAB2', '<?php echo $REC_FINCAPAB; ?>')" <?php } else { ?> class="minimal" <?php } ?> disabled>
                                            &nbsp;&nbsp;Tidak Terpenuhi&nbsp;&nbsp;&nbsp;&nbsp;
                                        <?php
                                    }
                                ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputName" class="col-sm-2 control-label">Catatan Kemampuan Keuangan</label>
                                <div class="col-sm-10">
								<?php 
                                    if($isShowx == 1) 
                                    { 
                                        ?>
                                   			<input type="text" class="form-control" style="max-width:250px" name="REC_FINCAPAB_NOTE" id="REC_FINCAPAB_NOTE" size="50" value="<?php echo $REC_FINCAPAB_NOTE; ?>" <?php if($isShowx != 1) { ?> onClick="checkAuth('REC_FINCAPAB_NOTE', '<?php echo $REC_FINCAPAB_NOTE; ?>')" <?php } ?>>
                                        <?php
                                    }
                                    else
                                    {
                                        ?>
                                   			<input type="text" class="form-control" style="max-width:250px" name="REC_FINCAPAB_NOTE" id="REC_FINCAPAB_NOTE" size="50" value="<?php echo $REC_FINCAPAB_NOTE; ?>" <?php if($isShowx != 1) { ?> onClick="checkAuth('REC_FINCAPAB_NOTE', '<?php echo $REC_FINCAPAB_NOTE; ?>')" <?php } ?> disabled>
                                        <?php
                                    }
                                ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Waktu Pelaksanaan</label>
                          	<div class="col-sm-10">
                            	<div class="input-group date">
                                    <div class="input-group-addon" style="display:none">
                                    	<i class="fa fa-calendar"></i>&nbsp;
                                    </div>
									<?php 
                                        if($isShowx == 1) 
                                        { 
                                            ?>
                                                <label>
                                                <input type="text" name="REC_DATEXEC" id="REC_DATEXEC" class="form-control pull-left" value="<?php echo $REC_DATEXEC; ?>" style="width:150px; text-align:right">&nbsp;&nbsp;hari&nbsp;</label>
                                            <?php
                                        }
                                        else
                                        {
                                            ?>
                                                <label>
                                                <input type="text" name="REC_DATEXEC" id="REC_DATEXEC" class="form-control pull-left" value="<?php echo $REC_DATEXEC; ?>" style="width:150px; text-align:right" disabled>&nbsp;&nbsp;hari&nbsp;</label>
                                            <?php
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Catatan Waktu Pelaksanaan</label>
                          	<div class="col-sm-10">
							<?php 
                                if($isShowx == 1) 
                                { 
                                    ?>
                            			<input type="text" class="form-control" style="max-width:250px" name="REC_TIMEXEC_NOTE" id="REC_TIMEXEC_NOTE" size="50" value="<?php echo $REC_TIMEXEC_NOTE; ?>" <?php if($isShowx != 1) { ?> onClick="checkAuth('REC_TIMEXEC_NOTE', '<?php echo $REC_TIMEXEC_NOTE; ?>')" <?php } ?>>
                                    <?php
                                }
                                else
                                {
                                    ?>
                            			<input type="text" class="form-control" style="max-width:250px" name="REC_TIMEXEC_NOTE" id="REC_TIMEXEC_NOTE" size="50" value="<?php echo $REC_TIMEXEC_NOTE; ?>" <?php if($isShowx != 1) { ?> onClick="checkAuth('REC_TIMEXEC_NOTE', '<?php echo $REC_TIMEXEC_NOTE; ?>')" <?php } ?> disabled>
                                    <?php
                                }
                            ?>
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Perkiraan Waktu Tender</label>
                          	<div class="col-sm-10">
							<?php 
                                if($isShowx == 1) 
                                { 
                                    ?>
                                        <input type="radio" name="REC_TEND_ESTIME" id="REC_TEND_ESTIME1" value="1" <?php if($REC_TEND_ESTIME == 1) { ?> checked <?php } if($isShowx != 1) { ?> onClick="checkAuthRad('REC_TEND_ESTIME1', '<?php echo $REC_TEND_ESTIME; ?>')" <?php } else { ?> class="minimal" <?php } ?>>
                                        &nbsp;&nbsp;Cukup Waktu&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="radio" name="REC_TEND_ESTIME" id="REC_TEND_ESTIME2" value="2" <?php if($REC_TEND_ESTIME == 2) { ?> checked <?php } if($isShowx != 1) { ?> onClick="checkAuthRad('REC_TEND_ESTIME2', '<?php echo $REC_TEND_ESTIME; ?>')" <?php } else { ?> class="minimal" <?php } ?>>
                                        &nbsp;&nbsp;Sedang / Normal&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="radio" name="REC_TEND_ESTIME" id="REC_TEND_ESTIME3" value="3" <?php if($REC_TEND_ESTIME == 3) { ?> checked <?php } if($isShowx != 1) { ?> onClick="checkAuthRad('REC_TEND_ESTIME3', '<?php echo $REC_TEND_ESTIME; ?>')" <?php } else { ?> class="minimal" <?php } ?>>
                                        &nbsp;&nbsp;Mendesak
                                    <?php
                                }
                                else
                                {
                                    ?>
                                        <input type="radio" name="REC_TEND_ESTIME" id="REC_TEND_ESTIME1" value="1" <?php if($REC_TEND_ESTIME == 1) { ?> checked <?php } if($isShowx != 1) { ?> onClick="checkAuthRad('REC_TEND_ESTIME1', '<?php echo $REC_TEND_ESTIME; ?>')" <?php } else { ?> class="minimal" <?php } ?> disabled>
                                        &nbsp;&nbsp;Cukup Waktu&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="radio" name="REC_TEND_ESTIME" id="REC_TEND_ESTIME2" value="2" <?php if($REC_TEND_ESTIME == 2) { ?> checked <?php } if($isShowx != 1) { ?> onClick="checkAuthRad('REC_TEND_ESTIME2', '<?php echo $REC_TEND_ESTIME; ?>')" <?php } else { ?> class="minimal" <?php } ?> disabled>
                                        &nbsp;&nbsp;Sedang / Normal&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="radio" name="REC_TEND_ESTIME" id="REC_TEND_ESTIME3" value="3" <?php if($REC_TEND_ESTIME == 3) { ?> checked <?php } if($isShowx != 1) { ?> onClick="checkAuthRad('REC_TEND_ESTIME3', '<?php echo $REC_TEND_ESTIME; ?>')" <?php } else { ?> class="minimal" <?php } ?> disabled>
                                        &nbsp;&nbsp;Mendesak
                                    <?php
                                }
                            ?>
                            </div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Catatan Perkiraan Tender</label>
                          	<div class="col-sm-10">
							<?php 
                                if($isShowx == 1) 
                                { 
                                    ?>
                                        <input type="text" class="form-control" style="max-width:250px" name="REC_TEND_ESTIME_NOTE" id="REC_TEND_ESTIME_NOTE" value="<?php echo $REC_TEND_ESTIME_NOTE; ?>" <?php if($isShowx != 1) { ?> onClick="checkAuth('REC_TEND_ESTIME_NOTE', '<?php echo $REC_TEND_ESTIME_NOTE; ?>')" <?php } ?>>
                                    <?php
                                }
                                else
                                {
                                    ?>
                                        <input type="text" class="form-control" style="max-width:250px" name="REC_TEND_ESTIME_NOTE" id="REC_TEND_ESTIME_NOTE" value="<?php echo $REC_TEND_ESTIME_NOTE; ?>" <?php if($isShowx != 1) { ?> onClick="checkAuth('REC_TEND_ESTIME_NOTE', '<?php echo $REC_TEND_ESTIME_NOTE; ?>')" <?php } ?> disabled>
                                    <?php
                                }
                            ?>
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Kompetitor / Bidders</label>
                          	<div class="col-sm-10">
							<?php 
                                if($isShowx == 1) 
                                { 
                                    ?>
                                		<input type="text" class="form-control" name="REC_BIDDER" id="REC_BIDDER" value="<?php echo $REC_BIDDER; ?>" style="max-width:100px;" <?php if($isShowx != 1) { ?> onClick="checkAuth('REC_BIDDER', '<?php echo $REC_BIDDER; ?>')" <?php } ?> >
                                    <?php
                                }
                                else
                                {
                                    ?>
                                		<input type="text" class="form-control" name="REC_BIDDER" id="REC_BIDDER" value="<?php echo $REC_BIDDER; ?>" style="max-width:100px;" <?php if($isShowx != 1) { ?> onClick="checkAuth('REC_BIDDER', '<?php echo $REC_BIDDER; ?>')" <?php } ?> disabled >
                                    <?php
                                }
                            ?>
                            </div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Kompetitor Qty</label>
                          	<div class="col-sm-10">
							<?php 
                                if($isShowx == 1) 
                                { 
                                    ?>
                                		<input type="text" class="form-control" name="REC_BIDDER_QTY" id="REC_BIDDER_QTY" value="<?php echo $REC_BIDDER_QTY; ?>" style="max-width:100px;text-align:right" onKeyPress="return isIntOnlyNew(event);" <?php if($isShowx != 1) { ?> onClick="checkAuth('REC_BIDDER_QTY', '<?php echo $REC_BIDDER_QTY; ?>')" <?php } ?> >
                                    <?php
                                }
                                else
                                {
                                    ?>
                                		<input type="text" class="form-control" name="REC_BIDDER_QTY" id="REC_BIDDER_QTY" value="<?php echo $REC_BIDDER_QTY; ?>" style="max-width:100px;text-align:right" onKeyPress="return isIntOnlyNew(event);" <?php if($isShowx != 1) { ?> onClick="checkAuth('REC_BIDDER_QTY', '<?php echo $REC_BIDDER_QTY; ?>')" <?php } ?> disabled >
                                    <?php
                                }
                            ?>
                            </div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Catatan Kompetitor</label>
                          	<div class="col-sm-10">
							<?php 
                                if($isShowx == 1) 
                                { 
                                    ?>
                                		<input type="text" class="form-control" name="REC_BIDDER_NOTE" id="REC_BIDDER_NOTE" value="<?php echo $REC_BIDDER_NOTE; ?>" style="max-width:250px;" <?php if($isShowx != 1) { ?> onClick="checkAuth('REC_BIDDER_NOTE', '<?php echo $REC_BIDDER_NOTE; ?>')" <?php } ?> >
                                    <?php
                                }
                                else
                                {
                                    ?>
                                		<input type="text" class="form-control" name="REC_BIDDER_NOTE" id="REC_BIDDER_NOTE" value="<?php echo $REC_BIDDER_NOTE; ?>" style="max-width:250px;" <?php if($isShowx != 1) { ?> onClick="checkAuth('REC_BIDDER_NOTE', '<?php echo $REC_BIDDER_NOTE; ?>')" <?php } ?> disabled>
                                    <?php
                                }
                            ?>
                            </div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Perkiraan Eskalasi</label>
                          	<div class="col-sm-10">
							<?php 
                                if($isShowx == 1) 
                                { 
                                    ?>
                                        <input type="radio" name="REC_ESKAL_EST" id="REC_ESKAL_EST1" value="1" <?php if($REC_ESKAL_EST == 1) { ?> checked <?php } if($isShowx != 1) { ?> onClick="checkAuthRad('REC_ESKAL_EST1', '<?php echo $REC_ESKAL_EST; ?>')" <?php } else { ?> class="minimal" <?php } ?>>
                                        &nbsp;&nbsp;Ada&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="radio" name="REC_ESKAL_EST" id="REC_ESKAL_EST2" value="2" <?php if($REC_ESKAL_EST == 2) { ?> checked <?php } if($isShowx != 1) { ?> onClick="checkAuthRad('REC_ESKAL_EST2', '<?php echo $REC_ESKAL_EST; ?>')" <?php } else { ?> class="minimal" <?php } ?>>
                                		&nbsp;&nbsp;Tidak ada
                                    <?php
                                }
                                else
                                {
                                    ?>
                                        <input type="radio" name="REC_ESKAL_EST" id="REC_ESKAL_EST1" value="1" <?php if($REC_ESKAL_EST == 1) { ?> checked <?php } if($isShowx != 1) { ?> onClick="checkAuthRad('REC_ESKAL_EST1', '<?php echo $REC_ESKAL_EST; ?>')" <?php } else { ?> class="minimal" <?php } ?> disabled>
                                        &nbsp;&nbsp;Ada&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="radio" name="REC_ESKAL_EST" id="REC_ESKAL_EST2" value="2" <?php if($REC_ESKAL_EST == 2) { ?> checked <?php } if($isShowx != 1) { ?> onClick="checkAuthRad('REC_ESKAL_EST2', '<?php echo $REC_ESKAL_EST; ?>')" <?php } else { ?> class="minimal" <?php } ?> disabled>
                                		&nbsp;&nbsp;Tidak ada
                                    <?php
                                }
                            ?>
                            </div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Catatan Eskalasi</label>
                          	<div class="col-sm-10">
							<?php 
                                if($isShowx == 1) 
                                { 
                                    ?>
                                        <input type="text" class="form-control" name="REC_ESKAL_EST_NOTE" id="REC_ESKAL_EST_NOTE" value="<?php echo $REC_ESKAL_EST_NOTE; ?>" style="max-width:250px;" <?php if($isShowx != 1) { ?> onClick="checkAuth('REC_ESKAL_EST_NOTE', '<?php echo $REC_ESKAL_EST_NOTE; ?>')" <?php } ?> >
                                    <?php
                                }
                                else
                                {
                                    ?>
                                        <input type="text" class="form-control" name="REC_ESKAL_EST_NOTE" id="REC_ESKAL_EST_NOTE" value="<?php echo $REC_ESKAL_EST_NOTE; ?>" style="max-width:250px;" <?php if($isShowx != 1) { ?> onClick="checkAuth('REC_ESKAL_EST_NOTE', '<?php echo $REC_ESKAL_EST_NOTE; ?>')" <?php } ?> disabled>
                                    <?php
                                }
                            ?>
                            </div>
                        </div>
                        <input type="hidden" name="isShowx" id="isShowx" value="<?php echo $isShowx; ?>" >
                        <div class="form-group" <?php if($isShow1 == 0) { ?> style="display:none" <?php } ?>>
                          	<label for="inputName" class="col-sm-2 control-label">Status (Marketing)</label>
                          	<div class="col-sm-10">
                                <input type="radio" name="REC_USR_MRK_STAT" id="REC_USR_MRK_STAT1" value="1" <?php if($REC_USR_MRK_STAT == 1) { ?> checked <?php } ?>>
                                &nbsp;&nbsp;Draft&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="radio" name="REC_USR_MRK_STAT" id="REC_USR_MRK_STAT2" value="2" <?php if($REC_USR_MRK_STAT == 2) { ?> checked <?php } ?>>
                                &nbsp;&nbsp;Confirm&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="hidden" name="REC_USR_MRK" id="REC_USR_MRK" class="form-control pull-left" value="<?php echo $DefEmp_ID; ?>" >
                            </div>
                        </div>
                        <div class="form-group" <?php if($isShow2 == 0) { ?> style="display:none" <?php } ?>>
                          	<label for="inputName" class="col-sm-2 control-label">Status (Mgr.Estimate)</label>
                          	<div class="col-sm-10">
                                <input type="radio" name="REC_MNG_EST_STAT" id="REC_MNG_EST_STAT1" value="1" <?php if($REC_MNG_EST_STAT == 1) { ?> checked <?php } ?>>
                                &nbsp;&nbsp;Draft&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="radio" name="REC_MNG_EST_STAT" id="REC_MNG_EST_STAT2" value="2" <?php if($REC_MNG_EST_STAT == 2) { ?> checked <?php } ?>>
                                &nbsp;&nbsp;Confirm&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="hidden" name="REC_MNG_EST" id="REC_MNG_EST" class="form-control pull-left" value="<?php echo $DefEmp_ID; ?>" >
                            </div>
                        </div>
                        <div class="form-group" <?php if($isShow2 != 1) { ?> style="display:none" <?php } ?>>
                            <label for="inputName" class="col-sm-2 control-label">Catatan Persetujuan Estimate</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="REC_NOTES_EST"  id="REC_NOTES_EST" style="max-width:500px;height:70px"><?php echo $REC_NOTES_EST; ?></textarea>
                            </div>
                        </div>
                        <div class="form-group" <?php if($isShowx == 2) { ?> style="display:none" <?php } ?>>
                            <label for="inputName" class="col-sm-2 control-label">Catatan Persetujuan Estimate</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="REC_NOTES_ESTX"  id="REC_NOTES_ESTX" style="max-width:500px;height:70px" disabled><?php echo $REC_NOTES_EST; ?></textarea>
                            </div>
                        </div>
                        <div class="form-group" <?php if($isShow3 != 1) { ?> style="display:none" <?php } ?>>
                          	<label for="inputName" class="col-sm-2 control-label">Keputus. : beri tanda <i class="fa fa-check"></i></label>
                          	<div class="col-sm-10">
                            	<label>
                                    <input type="radio" name="REC_CONCLUTION" id="REC_CONCLUTION1" value="1" <?php if($REC_CONCLUTION == 1) { ?> checked <?php } ?>>
                                    &nbsp;&nbsp;Ikut Tender / PQ, *)Target:&nbsp;&nbsp;
								</label>
                                <label>
                                	<input type="text" class="form-control" style="max-width:200px; " name="REC_CONC_TARGET" id="REC_CONC_TARGET" value="<?php echo $REC_CONC_TARGET; ?>" maxlength="15" onChange="functioncheck(this.value)">
                                </label><BR>
                                <label>
                                	<input type="radio" name="REC_CONCLUTION" id="REC_CONCLUTION2" value="2" <?php if($REC_CONCLUTION == 2) { ?> checked <?php } ?>> &nbsp;&nbsp;Tidak Ikut Tender / PQ*) : &nbsp;&nbsp;
                                </label>
                                <label>
                                	<input type="text" class="form-control" style="max-width:200px; " name="REC_CONC_NOTES" id="REC_CONC_NOTES" value="<?php echo $REC_CONC_NOTES; ?>" maxlength="15" onChange="functioncheck(this.value)">
                                </label>
                            </div>
                         </div>
                         <div class="form-group" <?php if($isShow3 != 1) { ?> style="display:none" <?php } ?>>
                          	<label for="inputName" class="col-sm-2 control-label">Catatan Keputusan</label>
                          	<div class="col-sm-10">
                                <textarea class="form-control" name="REC_NOTES"  id="REC_NOTES" style="max-width:500px;height:70px"><?php echo $REC_NOTES; ?></textarea>
                            </div>
                        </div>
                        <div class="form-group" <?php if($isShow3 == 0) { ?> style="display:none" <?php } ?>>
                          	<label for="inputName" class="col-sm-2 control-label">Status (Ka.Dept.Marketing)</label>
                          	<div class="col-sm-10">
                                <input type="radio" name="REC_MNG_MRK_STAT" id="REC_MNG_MRK_STAT1" value="1" <?php if($REC_MNG_MRK_STAT == 1) { ?> checked <?php } ?>>
                                &nbsp;&nbsp;Draft&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="radio" name="REC_MNG_MRK_STAT" id="REC_MNG_MRK_STAT3" value="3" <?php if($REC_MNG_MRK_STAT == 3) { ?> checked <?php } ?>>
                                &nbsp;&nbsp;Approve
                                <input type="hidden" name="REC_MNG_MRK" id="REC_MNG_MRK" class="form-control pull-left" value="<?php echo $DefEmp_ID; ?>" >
                            </div>
                        </div>
                        <div class="form-group" <?php if($isShow3 == 0) { ?> style="display:none" <?php } ?>>
                          	<label for="inputName" class="col-sm-2 control-label">Amandemen?</label>
                          	<div class="col-sm-10">
                                <input type="radio" name="ISAMANDEMEN" id="ISAMANDEMEN1" value="1" <?php if($ISAMANDEMEN == 1) { ?> checked <?php } ?>>
                                &nbsp;&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="radio" name="ISAMANDEMEN" id="ISAMANDEMEN3" value="0" <?php if($ISAMANDEMEN == 0) { ?> checked <?php } ?>>
                                &nbsp;&nbsp;No
                            </div>
                        </div>
                        <br>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                            	<?php
									//echo "$isShowx == 3 && $REC_USR_MRK_STAT == 2 && $REC_MNG_EST_STAT == 2";
									if($isShowx == 1)
									{ 
										?>
											<!--<input type="submit" name="submitAdd" id="submitAdd" class="btn btn-primary" value="Save" onClick="return buttonApp1(1)" >&nbsp;-->
                                            <button class="btn btn-primary"><i class="cus-save-16x16" onClick="return buttonApp1(1)"></i>&nbsp;&nbsp;<?php echo $Save; ?></button>&nbsp;
										<?php
									}
									if($isShowx == 2 && $REC_USR_MRK_STAT == 2)
									{ 
										?>
											<input type="submit" name="submitUpd2" id="submitUpd2" class="btn btn-primary" value="Update A" onClick="return buttonApp2(2)" >&nbsp;
                                            <button class="btn btn-primary"><i class="cus-save1-16x16" onClick="return buttonApp1(2)"></i>&nbsp;&nbsp;<?php echo $Update; ?></button>&nbsp;
										<?php
									}
									if($isShowx == 3 && $REC_USR_MRK_STAT == 2 && $REC_MNG_EST_STAT == 2)
									{ 
										?>
											<input type="submit" name="submitUpd3" id="submitUpd3" class="btn btn-primary" value="Update B" onClick="return buttonApp3(3)" >&nbsp;
                                            <button class="btn btn-primary"><i class="cus-save1-16x16" onClick="return buttonApp1(3)"></i>&nbsp;&nbsp;<?php echo $Update; ?></button>&nbsp;
										<?php
									}
								?>
                                <script>
									var url = "<?php echo $url_AddItem;?>";
									function buttonApp3()
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
                                <?php 
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
	function checkAuth(idName, theValue)
	{
		alert('You can not access to change this document');
		document.getElementById(idName).value = theValue;
		return false;
	}
	
	function saveRecomend()
	{
		ISAMANDEMEN		= document.getElementById("ISAMANDEMEN1").checked;
		REC_STAT 		= document.getElementById('REC_STAT').value;
		if(REC_STAT == 3)
		{
			if(ISAMANDEMEN == false)
			{
				//('Document has been approved. Can not be update.');
				alert('Tidak bisa dirubah, sudah Anda setujui. Kecuali diamandemen.');
				return false;
			}
		}
		
		REC_NO = document.getElementById('REC_NO').value;
		if(REC_NO == '')
		{
			alert('Recomendation No. can not be empty');
			document.getElementById('REC_NO').focus();
			return false;
		}
		
		nextornot = document.getElementById('CheckThe_Code').value;
		if(task == 'add')
		{
			if(nextornot > 0)
			{
				alert('Recomendation No. Already Exist. Please Change.');
				document.getElementById('REC_NO').value = '';
				document.getElementById('REC_NO').focus();
				return false;
			}
		}
		
		REC_PRJNAME = document.getElementById('REC_PRJNAME').value;
		if(REC_PRJNAME == '')
		{
			alert('Project Name can not be empty');
			document.getElementById('REC_PRJNAME').focus();
			return false;
		}
		
		REC_VALUE = document.getElementById('REC_VALUE').value;
		if(REC_VALUE == 0)
		{
			alert('Please input value of the Project.');
			document.getElementById('REC_VALUE1').focus();
			return false;
		}
		
		REC_OWNER = document.getElementById('REC_OWNER').value;
		if(REC_OWNER == '')
		{
			alert('Please chose one of Owner Project.');
			document.getElementById('REC_OWNER').focus();
			return false;
		}
		
		REC_CONSULT_ARS = document.getElementById('REC_CONSULT_ARS').value;
		if(REC_CONSULT_ARS == '')
		{
			alert('Please input ARS of Project Consultant.');
			document.getElementById('REC_CONSULT_ARS').focus();
			return false;
		}	
		
		REC_CONSULT_QS = document.getElementById('REC_CONSULT_QS').value;
		if(REC_CONSULT_QS == '')
		{
			alert('Please input QS of Project Consultant.');
			document.getElementById('REC_CONSULT_QS').focus();
			return false;
		}
		
		REC_LOCATION = document.getElementById('REC_LOCATION').value;
		if(REC_LOCATION == '')
		{
			alert('Please input Project Location.');
			document.getElementById('REC_LOCATION').focus();
			return false;
		}
		
		REC_FUNDSRC = document.getElementById('REC_FUNDSRC').value;
		
		/*isChecked1 = 0; isChecked2 = 0; isChecked3 = 0;
		REC_FUNDSRC1 = document.getElementById('REC_FUNDSRC1').checked;
			if(REC_FUNDSRC1 == false) isChecked1 = 0; else isChecked1 = 1;
		REC_FUNDSRC2 = document.getElementById('REC_FUNDSRC2').checked;
			if(REC_FUNDSRC2 == false) isChecked2 = 0; else isChecked2 = 1;
		REC_FUNDSRC3 = document.getElementById('REC_FUNDSRC3').checked;
			if(REC_FUNDSRC3 == false) isChecked3 = 0; else isChecked3 = 1;
		totREC_FUNDSRC	= isChecked1 + isChecked2 + isChecked3;*/
		if(REC_FUNDSRC == '')
		{
			alert('Silahkan isi status Sumber Dana.');
			//document.getElementById('REC_FUNDSRC1').checked;
			return false;
		}
		
		isChecked1 = 0; isChecked2 = 0; isChecked3 = 0;
		REC_PAY_SYS1 = document.getElementById('REC_PAY_SYS1').checked;
			if(REC_PAY_SYS1 == false) isChecked1 = 0; else isChecked1 = 1;
		REC_PAY_SYS2 = document.getElementById('REC_PAY_SYS2').checked;
			if(REC_PAY_SYS2 == false) isChecked2 = 0; else isChecked2 = 1;
		REC_PAY_SYS3 = document.getElementById('REC_PAY_SYS3').checked;
			if(REC_PAY_SYS3 == false) isChecked3 = 0; else isChecked3 = 1;
		
		totREC_PAY_SYS	= isChecked1 + isChecked2 + isChecked3;
		if(totREC_PAY_SYS == 0)
		{
			alert('Silahkan pilih status Sistem Pembayaran.');
			//document.getElementById('REC_TURNOVE1').checked;
			return false;
		}
		
		isChecked1 = 0; isChecked2 = 0;
		REC_DP1 = document.getElementById('REC_DP1').checked;
			if(REC_DP1 == false) isChecked1 = 0; else isChecked1 = 1;
		REC_DP2 = document.getElementById('REC_DP2').checked;
			if(REC_DP2 == false) isChecked2 = 0; else isChecked2 = 1;
		totREC_DP	= isChecked1 + isChecked2;
		if(totREC_DP == 0)
		{
			alert('Silahkan pilih status Uang Muka.');
			//document.getElementById('REC_DP1').checked;
			return false;
		}
		
		isChecked1 = 0; isChecked2 = 0;
		REC_TURNOVER1 = document.getElementById('REC_TURNOVER1').checked;
			if(REC_TURNOVER1 == false) isChecked1 = 0; else isChecked1 = 1;
		REC_TURNOVER2 = document.getElementById('REC_TURNOVER2').checked;
			if(REC_TURNOVER2 == false) isChecked2 = 0; else isChecked2 = 1;
		totREC_TURNOVER	= isChecked1 + isChecked2;
		if(totREC_TURNOVER == 0)
		{
			alert('Silahkan pilih status Turn Over.');
			//document.getElementById('REC_TURNOVER1').checked;
			return false;
		}
		
		isChecked1 = 0; isChecked2 = 0;
		REC_EXP1 = document.getElementById('REC_EXP1').checked;
			if(REC_EXP1 == false) isChecked1 = 0; else isChecked1 = 1;
		REC_EXP2 = document.getElementById('REC_EXP2').checked;
			if(REC_EXP2 == false) isChecked2 = 0; else isChecked2 = 1;
		totREC_EXP	= isChecked1 + isChecked2;
		if(totREC_EXP == 0)
		{
			alert('Silahkan pilih status Penglaman Sejenis.');
			//document.getElementById('REC_EXP1').checked;
			return false;
		}
		
		isChecked1 = 0; isChecked2 = 0;
		REC_BASCAPAB1 = document.getElementById('REC_BASCAPAB1').checked;
			if(REC_BASCAPAB1 == false) isChecked1 = 0; else isChecked1 = 1;
		REC_BASCAPAB2 = document.getElementById('REC_BASCAPAB2').checked;
			if(REC_BASCAPAB2 == false) isChecked2 = 0; else isChecked2 = 1;
		totREC_BASCAPAB	= isChecked1 + isChecked2;
		if(totREC_BASCAPAB == 0)
		{
			alert('Silahkan pilih status Kemampuan Dasar.');
			//document.getElementById('REC_BASCAPAB1').checked;
			return false;
		}
		
		isChecked1 = 0; isChecked2 = 0;
		REC_FINCAPAB1 = document.getElementById('REC_FINCAPAB1').checked;
			if(REC_FINCAPAB1 == false) isChecked1 = 0; else isChecked1 = 1;
		REC_FINCAPAB2 = document.getElementById('REC_FINCAPAB2').checked;
			if(REC_FINCAPAB2 == false) isChecked2 = 0; else isChecked2 = 1;
		totREC_FINCAPAB	= isChecked1 + isChecked2;
		if(totREC_FINCAPAB == 0)
		{
			alert('Silahkan pilih status Kemampaun Keuangan.');
			//document.getElementById('REC_FINCAPAB1').checked;
			return false;
		}
		
		isChecked1 = 0; isChecked2 = 0; isChecked3 = 0;
		REC_PQ_ESTIME1 = document.getElementById('REC_PQ_ESTIME1').checked;
			if(REC_PQ_ESTIME1 == false) isChecked1 = 0; else isChecked1 = 1;
		REC_PQ_ESTIME2 = document.getElementById('REC_PQ_ESTIME2').checked;
			if(REC_PQ_ESTIME2 == false) isChecked2 = 0; else isChecked2 = 1;
		REC_PQ_ESTIME3 = document.getElementById('REC_PQ_ESTIME3').checked;
			if(REC_PQ_ESTIME3 == false) isChecke3 = 0; else isChecked3 = 1;
		totREC_PQ_ESTIME	= isChecked1 + isChecked2 + isChecked3;
		if(totREC_PQ_ESTIME == 0)
		{
			alert('Silahkan pilih status Perkiraan Waktu PQ.');
			//document.getElementById('REC_PQ_ESTIME1').checked;
			return false;
		}
		
		isChecked1 = 0; isChecked2 = 0; isChecked3 = 0;
		REC_TEND_ESTIME1 = document.getElementById('REC_TEND_ESTIME1').checked;
			if(REC_TEND_ESTIME1 == false) isChecked1 = 0; else isChecked1 = 1;
		REC_TEND_ESTIME2 = document.getElementById('REC_TEND_ESTIME2').checked;
			if(REC_TEND_ESTIME2 == false) isChecked2 = 0; else isChecked2 = 1;
		REC_TEND_ESTIME3 = document.getElementById('REC_TEND_ESTIME3').checked;
			if(REC_TEND_ESTIME3 == false) isChecke3 = 0; else isChecked3 = 1;
		totREC_TEND_ESTIME	= isChecked1 + isChecked2 + isChecked3;
		if(totREC_TEND_ESTIME == 0)
		{
			alert('Silahkan pilih status Perkiraan Waktu Tender.');
			//document.getElementById('REC_TEND_ESTIME1').checked;
			return false;
		}
		
		REC_BIDDER_QTY = document.getElementById('REC_BIDDER_QTY').value;
		if(REC_BIDDER_QTY == 0)
		{
			alert('Please input of Project Competitor Qty.');
			document.getElementById('REC_BIDDER_QTY').focus();
			return false;
		}
		
		isChecked1 = 0; isChecked2 = 0;
		REC_ESKAL_EST1 = document.getElementById('REC_ESKAL_EST1').checked;
			if(REC_ESKAL_EST1 == false) isChecked1 = 0; else isChecked1 = 1;
		REC_ESKAL_EST2 = document.getElementById('REC_ESKAL_EST2').checked;
			if(REC_ESKAL_EST2 == false) isChecked2 = 0; else isChecked2 = 1;
		totREC_ESKAL_EST	= isChecked1 + isChecked2;
		if(totREC_ESKAL_EST == 0)
		{
			alert('Please check of Project Eskalation Estimate.');
			//document.getElementById('REC_ESKAL_EST1').checked;
			return false;
		}	
		
		isShowx = document.getElementById('isShowx').value;
		
		if(isShowx == 3)
		{
			isChecked1 = 0; isChecked2 = 0;
			REC_CONCLUTION1 = document.getElementById('REC_CONCLUTION1').checked;
				if(REC_CONCLUTION1 == false) isChecked1 = 0; else isChecked1 = 1;
			REC_CONCLUTION2 = document.getElementById('REC_CONCLUTION2').checked;
				if(REC_CONCLUTION2 == false) isChecked2 = 0; else isChecked2 = 1;
			totREC_CONCLUTION	= isChecked1 + isChecked2;
			if(totREC_CONCLUTION == 0)
			{
				alert('Silahkan tentukan pilihan kesimpulan.');
				//document.getElementById('REC_CONCLUTION1').checked;
				return false;
			}
		
			REC_NOTES = document.getElementById('REC_NOTES').value;
			if(REC_NOTES == '')
			{
				alert('Silahkan isi catatan kesimpulan.');
				document.getElementById('REC_NOTES').focus();
				return false;
			}
		}
		
		if(isShowx == 1)
		{
			REC_STAT_USR		= document.getElementById('REC_STAT_USR').value;
			if(REC_STAT_USR == 2)
			{
				alert('Tidak bisa dirubah, dokument ini sudah Anda setujui.');
				return false;
			}
		}
		
		if(isShowx == 2)
		{
			REC_STAT_EST		= document.getElementById('REC_STAT_EST').value;
			if(REC_STAT_EST == 2)
			{
				alert('Tidak bisa dirubah, dokument ini sudah Anda setujui.');
				return false;
			}
			REC_MNG_MRK_STAT3 	= document.getElementById('REC_MNG_MRK_STAT3').checked;
			if(REC_MNG_MRK_STAT3 == true)
			{
				ISAMANDEMEN			= document.getElementById("ISAMANDEMEN").checked;
				if(ISAMANDEMEN == false)
				{
					alert('Tidak bisa dirubah, dokument ini sudah diapprove.');
					return false;
				}				
			}
			REC_NOTES_EST = document.getElementById('REC_NOTES_EST').value;
			if(REC_NOTES_EST == '')
			{
				alert('Silahkan isi catatan kesimpulan.');
				document.getElementById('REC_NOTES_EST').focus();
				return false;
			}
		}
		/*return false;
		
		isChecked1 = 0; isChecked2 = 0; isChecked3 = 0;
		REC_STAT1 = document.getElementById('REC_STAT1').checked;
			if(REC_STAT1 == false) isChecked1 = 0; else isChecked1 = 1;
		REC_STAT2 = document.getElementById('REC_STAT2').checked;
			if(REC_STAT2 == false) isChecked2 = 0; else isChecked2 = 1;
		REC_STAT3 = document.getElementById('REC_STAT3').checked;
			if(REC_STAT3 == false) isChecke3 = 0; else isChecked3 = 1;
		totREC_STAT	= isChecked1 + isChecked2 + isChecked3;
		if(totREC_STAT == 0)
		{
			alert('Please check of Project Status.');
			//document.getElementById('REC_STAT1').checked;
			return false;
		}*/
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