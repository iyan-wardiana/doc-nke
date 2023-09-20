<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 1 Maret 2017
 * File Name	= asset_list_form.php
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
$ISDELETE 	= $this->session->userdata['ISDELETE'];

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
		$Pattern_Length = 4;
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
	
	$AS_PREFIX		= 'XXX';
	if(isset($_POST['AS_PREFIXX']))
	{
		$AS_PREFIX 	= $_POST['AS_PREFIXX'];
		$sql0 		= "tbl_asset_list WHERE AS_PREFIX = '$AS_PREFIX'";
		$result0 	= $this->db->count_all($sql0);
		if($result0 > 0)
		{
			$PREFC		= 1;
			$AS_PREFIX	= $AS_PREFIX;
		}
		else
		{
			$PREFC	= 0;
		}
	}
	
	$sql = "tbl_asset_list";
	$result = $this->db->count_all($sql);
	
	$myMax = $result;
	$myMax = $myMax+1;			
	
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
	{if($len==1) $nol="000";else if($len==2) $nol="00";else if($len==3) $nol="0"; else $nol="";
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
	
	$Y_HEADER		= substr(date('Y'), 2,4);
	
	//$DocNumber 		= "$Pattern_Code$groupPattern-$lastPatternNumb";
	$AG_CODE		= '';
	$AG_MANCODE		= 'XXX';
	if(isset($_POST['AG_CODEX']))
	{
		$AG_CODE 	= $_POST['AG_CODEX'];
		$sqlAG0		= "SELECT AG_CODE, AG_MANCODE, AG_NAME FROM tbl_asset_group WHERE AG_CODE = '$AG_CODE'";
		$resultAG0 	= $this->db->query($sqlAG0)->result();
		foreach($resultAG0 as $rowAG0) :
			$AG_MANCODE = $rowAG0->AG_MANCODE;
		endforeach;												
	}
	$DocNumber 		= "$AS_PREFIX$Y_HEADER-$lastPatternNumb";
		
	$AS_CODE 		= $DocNumber;
	$AS_CODE_M		= $AS_CODE;
	$AST_CODE		= 'HM';
	$AST_REFNO		= '';
	$AS_NAME		= '';
	$AS_DESC		= '';
	$AS_TYPECODE	= '';
	$AS_BRAND		= '';
	$AS_SN			= '';
	$AS_CAPACITY	= 0;
	$AS_MACHINE		= '';
	$AS_MACH_TYPE	= '';
	$AS_MACH_SN		= '';
	$AS_EXPMONTH	= 0;
	$AS_PRICE		= 0;
	$AS_YEAR		= date('Y');
	$AS_HM			= 0;
	$AS_LASTPOS		= '';
	$AS_LASTSTAT	= 0;
	$AS_STAT		= 1;
	$AS_LINKACC		= '';
	$AS_LADEPREC	= '';
	
	$Patt_Number	= $lastPatternNumb1;
	
	$AS_VOLM		= 0;
}
else
{	
	$AS_CODE 		= $default['AS_CODE'];
	$AS_CODE_M 		= $default['AS_CODE_M'];
	$AS_PREFIX 		= $default['AS_PREFIX'];
	$AG_CODE 		= $default['AG_CODE'];
	$AST_CODE 		= $default['AST_CODE'];
	$AST_REFNO 		= $default['AST_REFNO'];
	$AS_NAME 		= $default['AS_NAME'];
	$AS_DESC 		= $default['AS_DESC'];
	$AS_TYPECODE 	= $default['AS_TYPECODE'];
	$AS_BRAND 		= $default['AS_BRAND'];
	$AS_SN 			= $default['AS_SN'];
	$AS_CAPACITY 	= $default['AS_CAPACITY'];
	$AS_MACHINE		= $default['AS_MACHINE'];
	$AS_MACH_TYPE 	= $default['AS_MACH_TYPE'];
	$AS_MACH_SN 	= $default['AS_MACH_SN'];
	$AS_EXPMONTH 	= $default['AS_EXPMONTH'];
	$AS_PRICE 		= $default['AS_PRICE'];
	$AS_YEAR 		= $default['AS_YEAR'];
	if($AS_YEAR == 0)
	{
		$AS_YEAR	= date('Y');
	}
	$AS_HM 			= $default['AS_HM'];
	$AS_LASTPOS		= $default['AS_LASTPOS'];
	$AS_LASTSTAT	= $default['AS_LASTSTAT'];
	$AS_STAT		= $default['AS_STAT'];
	$AS_VOLM		= $default['AS_VOLM'];
	$AS_LINKACC		= $default['AS_LINKACC'];
	$AS_LADEPREC	= $default['AS_LADEPREC'];
	$Patt_Number 	= $default['Patt_Number'];
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
		if($TranslCode == 'Edit')$Edit = $LangTransl;
		if($TranslCode == 'Save')$Save = $LangTransl;
		if($TranslCode == 'Update')$Update = $LangTransl;
		if($TranslCode == 'Back')$Back = $LangTransl;
		if($TranslCode == 'AssetCode')$AssetCode = $LangTransl;
		if($TranslCode == 'ManualCode')$ManualCode = $LangTransl;
		if($TranslCode == 'AssetPrefix')$AssetPrefix = $LangTransl;
		if($TranslCode == 'AssetGroup')$AssetGroup = $LangTransl;
		if($TranslCode == 'Ownership')$Ownership = $LangTransl;
		if($TranslCode == 'AssetName')$AssetName = $LangTransl;
		if($TranslCode == 'AssetType')$AssetType = $LangTransl;
		if($TranslCode == 'Year')$Year = $LangTransl;
		if($TranslCode == 'HourMeter')$HourMeter = $LangTransl;
		if($TranslCode == 'AssetBrand')$AssetBrand = $LangTransl;
		if($TranslCode == 'AssetPrice')$AssetPrice = $LangTransl;
		if($TranslCode == 'AssetNumber')$AssetNumber = $LangTransl;
		if($TranslCode == 'AssetCapacity')$AssetCapacity = $LangTransl;
		if($TranslCode == 'MachineBrand')$MachineBrand = $LangTransl;
		if($TranslCode == 'MachineNumber')$MachineNumber = $LangTransl;
		if($TranslCode == 'Description')$Description = $LangTransl;
		if($TranslCode == 'LastPosition')$LastPosition = $LangTransl;
		if($TranslCode == 'Status')$Status = $LangTransl;
		if($TranslCode == 'Budget')$Budget = $LangTransl;
		if($TranslCode == 'PerMonth')$PerMonth = $LangTransl;
		if($TranslCode == 'AccountCode')$AccountCode = $LangTransl;
		if($TranslCode == 'ExpAccount')$ExpAccount = $LangTransl;
		if($TranslCode == 'Search')$Search = $LangTransl;
	endforeach;
	
	if($LangID == 'IND')
	{
		$h2_title	= 'Tambah Alat';
		$h3_title	= 'manajemen alat';
		$alert1		= 'Anda harus memilih Nomor SPK untuk input Alat Sewa.';
	}
	else
	{
		$h2_title	= 'Add Tools';
		$h3_title	= 'asset management';
		$alert1		= 'You must input SPK No. to Input this Tools.';
	}
?>

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>
    <?php echo $h2_title; ?>
    <small><?php echo $h3_title; ?></small>  </h1>
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
                	<h4 class="box-title">&nbsp;</h4>
              		<div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body chart-responsive">
                	<form name="frmsrch" id="frmsrch" action="" method=POST style="display:none">
                        <input type="text" name="AG_CODEX" id="AG_CODEX" class="textbox" value="<?php echo $AG_CODE; ?>" />
                        <input type="submit" class="button_css" name="submitSrch" id="submitSrch" value=" search " />
                    </form>
                	<form name="frmsrch1" id="frmsrch1" action="" method=POST style="display:none">
                        <input type="text" name="AS_PREFIXX" id="AS_PREFIXX" class="textbox" value="<?php echo $AS_PREFIX; ?>" />
                        <input type="submit" class="button_css" name="submitSrch1" id="submitSrch1" value=" search " />
                    </form>
                    <form class="form-horizontal" name="frm" id="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return checkInp()">
                    	<input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
           				<input type="Hidden" name="rowCount" id="rowCount" value="0">
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $AssetCode; ?> </label>
                          	<div class="col-sm-10">
                                <input type="hidden" name="Patt_Number" id="Patt_Number" value="<?php echo $Patt_Number; ?>">
                                <input type="hidden" name="AS_CODE" id="AS_CODE" value="<?php echo $AS_CODE; ?>" >
                                <input type="text" name="AS_CODE1" id="AS_CODE1" value="<?php echo $AS_CODE; ?>" class="form-control" style="max-width:170px;" disabled >
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $AssetPrefix ?> </label>
                          	<div class="col-sm-10">
                            	<input type="text" class="form-control" style="max-width:70px; "  name="AS_PREFIX" id="AS_PREFIX" value="<?php echo $AS_PREFIX; ?>" onChange="checkPref(this.value)" maxlength="5" >
                          	</div>
                        </div>
                        <script>
							function checkPref(assetGroup)
							{
								document.getElementById('AS_PREFIXX').value = assetGroup;
								document.frmsrch1.submitSrch1.click();
							}
						</script>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $ManualCode ?> </label>
                          	<div class="col-sm-10">
                            	<input type="text" class="form-control" style="max-width:170px;" name="AS_CODE_M" id="AS_CODE_M" value="<?php echo $AS_CODE_M; ?>" >
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Ownership ?> </label>
                          	<div class="col-sm-10">
                            	<select name="AST_CODE" id="AST_CODE" class="form-control select2" style="max-width:170px" onChange="getAstCode(this.value)">
                                    <option value="HM" <?php if($AST_CODE == 'HM') { ?>selected<?php } ?>>HM - Hak Milik</option>
                                    <option value="SW" <?php if($AST_CODE == 'SW') { ?>selected<?php } ?>>SW - Sewa</option>
                            	</select>
                          	</div>
                        </div>
                        <div class="form-group" id="woreq_ref" <?php if($AST_CODE != 'SW') { ?> style="display:none" <?php } ?>>
                            <label for="inputName" class="col-sm-2 control-label">SPK No.</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-btn">
                                        <button type="button" class="btn btn-primary"><?php echo $Search ?> </button>
                                    </div>
                                    <input type="hidden" class="form-control" name="AST_REFNO" id="AST_REFNO" style="max-width:160px" value="<?php echo $AST_REFNO; ?>" >
                                <input type="text" class="form-control" name="AST_REFNO1" id="AST_REFNO1" style="max-width:200px" value="<?php echo $AST_REFNO; ?>" onClick="pleaseCheck();" >
                                </div>
                            </div>
                        </div>
						<?php
                            $url_selFPA	= site_url('c_project/c_s180d0bpk/s3l4ll5PK/?id='.$this->url_encryption_helper->encode_url($DefEmp_ID));
                        ?>
                        <script>
							function getAstCode(thisValue)
							{
								if(thisValue == 'SW')
								{
									document.getElementById('woreq_ref').style.display	= '';s
								}
								else
								{
									document.getElementById('AST_REFNO').value			= '';
									document.getElementById('AST_REFNO1').value			= '';
									document.getElementById('woreq_ref').style.display	= 'none';
								}
							}
							
                            var url1 = "<?php echo $url_selFPA;?>";
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
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $AssetGroup ?> </label>
                          	<div class="col-sm-10">
                            	<select name="AG_CODE" id="AG_CODE" class="form-control select2" style="max-width:250px">
                                    <option value="XXX">--- None ---</option>
                                    <?php
                                        $own_Code 	= '';
                                        $CountAG 	= $this->db->count_all('tbl_asset_group');
                                        $sqlAG 		= "SELECT AG_CODE, AG_NAME FROM tbl_asset_group ORDER BY AG_NAME";
                                        $resultAG 	= $this->db->query($sqlAG)->result();
                                        if($CountAG > 0)
                                        {
                                            foreach($resultAG as $rowAG) :
                                                $AG_CODEA = $rowAG->AG_CODE;
                                                $AG_NAMEA = $rowAG->AG_NAME;
                                                ?>
                                                    <option value="<?php echo $AG_CODEA; ?>" <?php if($AG_CODEA == $AG_CODE) { ?>selected <?php } ?>>
                                                        <?php echo $AG_NAMEA; ?>
                                                    </option>
                                                <?php
                                             endforeach;
                                         }
                                    ?>
                            	</select>
                          	</div>
                        </div>
                        <script>
							function chooseGroup(assetGroup)
							{
								document.getElementById('AG_CODEX').value = assetGroup;
								document.frmsrch.submitSrch.click();
							}
						</script>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $AssetName ?> </label>
                          	<div class="col-sm-10">
                            	<input type="text" class="form-control" style="max-width:250px; "  name="AS_NAME" id="AS_NAME" value="<?php echo $AS_NAME; ?>" >
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $AssetType ?> </label>
                          	<div class="col-sm-10">
                                <input type="text" class="form-control" style="max-width:150px" name="AS_TYPECODE" id="AS_TYPECODE"value="<?php echo $AS_TYPECODE; ?>" >
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Year ?> </label>
                          	<div class="col-sm-10">
                                <input type="text" class="form-control" style="max-width:150px" name="AS_YEAR" id="AS_YEAR"value="<?php echo $AS_YEAR; ?>" >
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $HourMeter ?> </label>
                          	<div class="col-sm-10">
                                <input type="hidden" name="AS_HM" id="AS_HM"value="<?php echo $AS_HM; ?>" >
                                <input type="text" class="form-control" style="max-width:150px; text-align:right" name="AS_HM1" id="AS_HM1"value="<?php echo number_format($AS_HM, $decFormat); ?>" onBlur="changeValue(this)" onKeyPress="return isIntOnlyNew(event);" >
                          	</div>
                        </div>
                        <script>
							function changeValue(thisVal)
							{
								var thisVal 	= eval(thisVal).value.split(",").join("");
								
								document.getElementById('AS_HM').value 		= parseFloat(Math.abs(thisVal));
								document.getElementById('AS_HM1').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(thisVal)),decFormat));
							}
						</script>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $AssetBrand ?> </label>
                          	<div class="col-sm-10">
                    			<input type="text" class="form-control" style="max-width:150px" name="AS_BRAND" id="AS_BRAND"value="<?php echo $AS_BRAND; ?>" >
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $AssetPrice ?>  (Rp)</label>
                          	<div class="col-sm-10">
                            	<input type="hidden" name="AS_PRICE" id="AS_PRICE"value="<?php echo $AS_PRICE; ?>" >
                            	<input type="text" class="form-control" style="max-width:150px; text-align:right" name="AS_PRICE1" id="AS_PRICE1" value="<?php echo number_format($AS_PRICE, $decFormat); ?>" onBlur="changeValuePrice(this)" onKeyPress="return isIntOnlyNew(event);" >
                          	</div>
                        </div>
                        <script>
							function changeValuePrice(thisVal)
							{
								var thisVal 	= eval(thisVal).value.split(",").join("");
								
								document.getElementById('AS_PRICE').value 	= parseFloat(Math.abs(thisVal));
								document.getElementById('AS_PRICE1').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(thisVal)),decFormat));
							}
						</script>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $AssetNumber ?>  (SN)</label>
                          	<div class="col-sm-10">
                            	<input type="text" class="form-control" style="max-width:150px" name="AS_SN" id="AS_SN"value="<?php echo $AS_SN; ?>" >
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $AssetCapacity ?> </label>
                          	<div class="col-sm-10">
                            	<input type="hidden" name="AS_CAPACITY" id="AS_CAPACITY"value="<?php echo $AS_CAPACITY; ?>" >
                            	<input type="text" class="form-control" style="max-width:150px;" name="AS_CAPACITY1" id="AS_CAPACITY1" value="<?php echo $AS_CAPACITY; ?>" onBlur="changeValueCapacity(this)" >
                          	</div>
                        </div>
                        <script>
							function changeValueCapacity(thisVal)
							{
								var thisVal = thisVal.value.split(",").join("");
								
								document.getElementById('AS_CAPACITY').value 	= thisVal;
								document.getElementById('AS_CAPACITY1').value 	= thisVal;
							}
						</script>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $MachineBrand ?> </label>
                          	<div class="col-sm-10">
                            	<input type="text" class="form-control" style="max-width:150px" name="AS_MACHINE" id="AS_MACHINE"value="<?php echo $AS_MACHINE; ?>" >
                            </div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $MachineNumber ?>  (SN)</label>
                          	<div class="col-sm-10">
                            	<input type="text" class="form-control" style="max-width:150px" name="AS_MACH_SN" id="AS_MACH_SN"value="<?php echo $AS_MACH_SN; ?>" >
                            </div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo "$Budget $PerMonth"; ?></label>
                          	<div class="col-sm-10">
                            	<input type="text" class="form-control" style="max-width:150px; text-align:right" name="AS_EXPMONTH1" id="AS_EXPMONTH1" value="<?php echo number_format($AS_EXPMONTH, 2); ?>" onBlur="chgExpPerMonth(this);" >
                            	<input type="hidden" class="form-control" style="max-width:150px" name="AS_EXPMONTH" id="AS_EXPMONTH" value="<?php echo $AS_EXPMONTH; ?>" >
                            </div>
                        </div>
                        <script>
							function chgExpPerMonth(thisval)
							{
								var decFormat	= document.getElementById('decFormat').value;
								
								AS_EXPMONTH1	= document.getElementById('AS_EXPMONTH1');
								AS_EXPMONTH		= parseFloat(eval(AS_EXPMONTH1).value.split(",").join(""));
								document.getElementById('AS_EXPMONTH').value 	= parseFloat(Math.abs(AS_EXPMONTH));
								document.getElementById('AS_EXPMONTH1').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(AS_EXPMONTH)), 2));
							}
						</script>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Qty</label>
                          	<div class="col-sm-10">
                            	<input type="text" class="form-control" style="max-width:150px; text-align:right" name="AS_VOLM1" id="AS_VOLM1" value="<?php echo number_format($AS_VOLM, 2); ?>" onBlur="chgQty(this);" >
                            	<input type="hidden" class="form-control" style="max-width:150px" name="AS_VOLM" id="AS_VOLM" value="<?php echo $AS_VOLM; ?>" >
                            </div>
                        </div>
                        <script>
							function chgQty(thisval)
							{
								var decFormat	= document.getElementById('decFormat').value;
								
								AS_VOLM1	= document.getElementById('AS_VOLM1');
								AS_VOLM		= parseFloat(eval(AS_VOLM1).value.split(",").join(""));
								document.getElementById('AS_VOLM').value 	= parseFloat(Math.abs(AS_VOLM));
								document.getElementById('AS_VOLM1').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(AS_VOLM)), 2));
							}
						</script>
                  		<div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Description ?> </label>
                          	<div class="col-sm-10">
                          	  <textarea class="form-control" name="AS_DESC"  id="AS_DESC" style="max-width:400px;height:70px"><?php echo $AS_DESC; ?></textarea>
                          	</div>
                      	</div>
                        <div class="form-group">
                        	<input type="hidden" name="AS_LASTSTAT" id="AS_LASTSTAT" value="<?php echo $AS_LASTSTAT; ?>">
                        	<input type="hidden" name="AS_LASTPOSX" id="AS_LASTPOSX" value="<?php echo $AS_LASTPOS; ?>">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $LastPosition ?> </label>
                          	<div class="col-sm-10">
                            	<select name="AS_LASTPOS" id="AS_LASTPOS" class="form-control select2"style="max-width:400px" onChange="checkSTAT()">
                                    <?php
                                        $sqlPRJ 	= "SELECT PRJCODE, PRJNAME FROM tbl_project WHERE PRJSTAT = 1
														ORDER BY PRJCODE";
                                        $resultPRJ 	= $this->db->query($sqlPRJ)->result();
										
										foreach($resultPRJ as $rowPRJ) :
											$PRJCODEA = $rowPRJ->PRJCODE;
											$PRJNAMEA = $rowPRJ->PRJNAME;
											?>
												<option value="<?php echo $PRJCODEA; ?>" <?php if($PRJCODEA == $AS_LASTPOS) { ?>selected <?php } ?>>
													<?php echo "$PRJCODEA - $PRJNAMEA"; ?>
												</option>
											<?php
										 endforeach;
                                    ?>
                            	</select>
                          	</div>
                        </div>
                        <script>
							function checkSTAT()
							{
								AS_LASTSTAT	= document.getElementById('AS_LASTSTAT').value;
								AS_LASTPOSX	= document.getElementById('AS_LASTPOSX').value;
								if(AS_LASTSTAT != 0)
								{
									alert('Sorry, can not change last position. The Asset is being used in '+AS_LASTPOSX);
									return false;
								}
							}
						</script>
						<?php
							$PRJCODE	= 'KTR';
							$sqlPRJHO	= "SELECT PRJCODE FROM tbl_project WHERE isHO = 1 LIMIT 1";
                            $resPRJHO 	= $this->db->query($sqlPRJHO)->result();
							foreach($resPRJHO as $rowPRJHO) :
								$PRJCODE	= $rowPRJHO->PRJCODE;
							endforeach;
							
                            /*$sqlC0a		= "tbl_chartaccount WHERE Account_Level = '0' AND Account_Category = '1' 
												AND PRJCODE = '$PRJCODE'";
                            $resC0a 	= $this->db->count_all($sqlC0a);
							$sqlC0b		= "SELECT DISTINCT Acc_ID, Account_Number, Account_NameEn, Account_NameId, 
												Acc_ParentList, Acc_DirParent, isLast
                                            FROM tbl_chartaccount WHERE Account_Level = '0' AND Account_Category = '1' 
												AND PRJCODE = '$PRJCODE'";
                            $resC0b 	= $this->db->query($sqlC0b)->result();*/
							
							
                            $sqlC0a		= "tbl_chartaccount WHERE Account_Category = '1' AND PRJCODE = '$PRJCODE'";
                            $resC0a 	= $this->db->count_all($sqlC0a);
                            
                            $sqlC0b		= "SELECT DISTINCT Acc_ID, Account_Number, Account_Level, Account_NameEn, Account_NameId, Acc_ParentList,
												Acc_DirParent, isLast
                                            FROM tbl_chartaccount WHERE Account_Category = '1' AND PRJCODE = '$PRJCODE' ORDER BY ORD_ID";
                            $resC0b 	= $this->db->query($sqlC0b)->result();
                        ?>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $AccountCode; ?></label>
                            <div class="col-sm-10">
                                <select name="AS_LINKACC" id="AS_LINKACC" class="form-control select2" style="max-width:400px">
                        			<option value="" > ---- None ----</option>
                                    <?php
									if($resC0a>0)
									{
										foreach($resC0b as $rowC0b) :
											$Acc_ID0		= $rowC0b->Acc_ID;
											$Account_Number0= $rowC0b->Account_Number;
											$Acc_DirParent0	= $rowC0b->Acc_DirParent;
											$Account_Level0	= $rowC0b->Account_Level;
											if($LangID == 'IND')
											{
												$Account_Name0	= $rowC0b->Account_NameId;
											}
											else
											{
												$Account_Name0	= $rowC0b->Account_NameEn;
											}
											
											$Acc_ParentList0	= $rowC0b->Acc_ParentList;
											$isLast_0			= $rowC0b->isLast;
											$disbaled_0			= 0;
											if($isLast_0 == 0)
												$disbaled_0		= 1;
												
											if($Account_Level0 == 0)
												$level_coa1			= "";
											elseif($Account_Level0 == 1)
												$level_coa1			= "&nbsp;&nbsp;&nbsp;";
											elseif($Account_Level0 == 2)
												$level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
											elseif($Account_Level0 == 3)
												$level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
											elseif($Account_Level0 == 4)
												$level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
											elseif($Account_Level0 == 5)
												$level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
											elseif($Account_Level0 == 6)
												$level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
											elseif($Account_Level0 == 7)
												$level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
											
											$collData0	= "$Account_Number0";
											?>
												<option value="<?php echo $Account_Number0; ?>" <?php if($Account_Number0 == $AS_LINKACC) { ?> selected <?php } if($disbaled_0 == 1) { ?> disabled style="font-weight:bold" <?php } ?>><?php echo "$level_coa1$collData0 - $Account_Name0"; ?></option>
											<?php
										endforeach;
									}
									?>
                                </select>
                            </div>
                        </div>
						<?php
                            /*$sqlC0a		= "tbl_chartaccount WHERE Account_Level = '0' AND Account_Category IN (1,8,5) 
												AND PRJCODE = '$PRJCODE'";
                            $resC0a 	= $this->db->count_all($sqlC0a);
							$sqlC0b		= "SELECT DISTINCT Acc_ID, Account_Number, Account_NameEn, Account_NameId, 
												Acc_ParentList, Acc_DirParent, isLast
                                            FROM tbl_chartaccount WHERE Account_Level = '0' AND Account_Category IN (1,8,5) 
												AND PRJCODE = '$PRJCODE'";
                            $resC0b 	= $this->db->query($sqlC0b)->result();*/
							
                            $sqlC0a		= "tbl_chartaccount WHERE Account_Category IN (1,8,5) AND PRJCODE = '$PRJCODE'";
                            $resC0a 	= $this->db->count_all($sqlC0a);
                            
                            $sqlC0b		= "SELECT DISTINCT Acc_ID, Account_Number, Account_Level, Account_NameEn, Account_NameId, Acc_ParentList,
												Acc_DirParent, isLast
                                            FROM tbl_chartaccount WHERE Account_Category IN (1,8,5) AND PRJCODE = '$PRJCODE' ORDER BY ORD_ID";
                            $resC0b 	= $this->db->query($sqlC0b)->result();
                        ?>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $ExpAccount; ?></label>
                            <div class="col-sm-10">
                                <select name="AS_LADEPREC" id="AS_LADEPREC" class="form-control select2" style="max-width:400px">
                        			<option value="" > ---- None ----</option>
                                    <?php
									if($resC0a>0)
									{
										foreach($resC0b as $rowC0b) :
											$Acc_ID0		= $rowC0b->Acc_ID;
											$Account_Number0= $rowC0b->Account_Number;
											$Acc_DirParent0	= $rowC0b->Acc_DirParent;
											$Account_Level0	= $rowC0b->Account_Level;
											if($LangID == 'IND')
											{
												$Account_Name0	= $rowC0b->Account_NameId;
											}
											else
											{
												$Account_Name0	= $rowC0b->Account_NameEn;
											}
											
											$Acc_ParentList0	= $rowC0b->Acc_ParentList;
											$isLast_0			= $rowC0b->isLast;
											$disbaled_0			= 0;
											if($isLast_0 == 0)
												$disbaled_0		= 1;
												
											if($Account_Level0 == 0)
												$level_coa1			= "";
											elseif($Account_Level0 == 1)
												$level_coa1			= "&nbsp;&nbsp;&nbsp;";
											elseif($Account_Level0 == 2)
												$level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
											elseif($Account_Level0 == 3)
												$level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
											elseif($Account_Level0 == 4)
												$level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
											elseif($Account_Level0 == 5)
												$level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
											elseif($Account_Level0 == 6)
												$level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
											elseif($Account_Level0 == 7)
												$level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
											
											$collData0	= "$Account_Number0";
											?>
												<option value="<?php echo $Account_Number0; ?>" <?php if($Account_Number0 == $AS_LADEPREC) { ?> selected <?php } if($disbaled_0 == 1) { ?> disabled style="font-weight:bold" <?php } ?>><?php echo "$level_coa1$collData0 - $Account_Name0"; ?></option>
											<?php
										endforeach;
									}
									?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Status ?> </label>
                          	<div class="col-sm-10">
                                <select name="AS_STAT" id="AS_STAT" class="form-control select2" style="max-width:100px">
                                	<option value="1" <?php if($AS_STAT == 1) { ?> selected <?php } ?>>Active</option>
                                	<option value="2" <?php if($AS_STAT == 2) { ?> selected <?php } ?>>In Active</option>
                                	<option value="3" <?php if($AS_STAT == 3) { ?> selected <?php } ?>>Used</option>
                                	<option value="4" <?php if($AS_STAT == 4) { ?> selected <?php } ?>>Repair</option>
                                    <?php if($ISDELETE == 1 && $FlagUSER = 'SUPERADMIN') { ?>
                                	<option value="9" <?php if($AS_STAT == 9) { ?> selected <?php } ?>>Delete</option>
                                    <?php } ?>
                                </select>
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
											<i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Save; ?>
											</button>&nbsp;
										<?php
									}
									else
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
  
	function add_FPA(strItem) 
	{
		arrItem = strItem.split('|');		
		WO_NUM		= arrItem[0];
		WO_CODE 	= arrItem[1];
		document.getElementById('AST_REFNO').value	= WO_NUM;
		document.getElementById('AST_REFNO1').value	= WO_CODE;
	}
	
	function checkInp()
	{
		AST_CODE = document.getElementById('AST_CODE').value;
		if(AST_CODE == 'SW')
		{
			AST_REFNO = document.getElementById('AST_REFNO').value;
			if(AST_REFNO == '')
			{
				alert('<?php echo $alert1; ?>');
				document.getElementById('AST_REFNO1').focus();
				return false;
			}
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