<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 12 April 2017
 * File Name	= asset_maintenance_form.php
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

$currentRow = 0;
if($task == 'add')
{
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
	
	$sql = "tbl_asset_mainten";
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
	$year		= substr($year, 2,4);
	$useYear	= 1;
	$useMonth	= 1;
	$useDate	= 1;
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
	
	$Pattern_Length	= 4;
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
	$DocNumber 		= "$Pattern_Code$PRJCODE$groupPattern-$lastPatternNumb";
	
	$AM_CODE 		= $DocNumber;
	$AM_JOBCODE		= '';
	$AM_AS_CODE		= '';
	$AM_PRJCODE		= $PRJCODE;
	$AM_DATE		= date("m/d/Y");
	$AM_DESC		= '';
	$AM_STARTD		= date("m/d/Y");
	$AM_STARTT		= '00:00';
	$AM_ENDD		= date("m/d/Y");
	$AM_ENDT		= '00:00';
	$AM_STAT		= 0;
	
	$Patt_Number	= $lastPatternNumb1;
	
	$AM_PROCS 	= 0;
	$IS_PROCS 	= 0;
}
else
{
	$isSetDocNo 	= 1;
	$AM_CODE 		= $default['AM_CODE'];
	$AM_AS_CODE 	= $default['AM_AS_CODE'];
	$AM_PRJCODE 	= $default['AM_PRJCODE'];
	$PRJCODE 		= $default['AM_PRJCODE'];
	$AM_DATE 		= $default['AM_DATE'];
	$AM_DESC 		= $default['AM_DESC'];
	$AM_STARTD		= $default['AM_STARTD'];
	$AM_STARTD		= date('m/d/Y',strtotime($AM_STARTD));
	$AM_ENDD 		= $default['AM_ENDD'];
	$AM_ENDD		= date('m/d/Y',strtotime($AM_ENDD));
	$AM_STARTT		= $default['AM_STARTT'];
	$AM_STARTT		= date('H:i',strtotime($AM_STARTT));
	$AM_ENDT 		= $default['AM_ENDT'];
	$AM_ENDT		= date('H:i',strtotime($AM_ENDT));
	$AM_STAT 		= $default['AM_STAT'];
	$AM_PROCD1		= $default['AM_PROCD'];
	if($AM_PROCD1 == '')
		$AM_PROCD1	= date("m/d/Y");
	$AM_PROCD		= date('m/d/Y',strtotime($AM_PROCD1));
	$AM_PROCT		= $default['AM_PROCT'];
	if($AM_PROCT == '')
		$AM_PROCT	= "00:00";
	else
		$AM_PROCT		= date('H:i',strtotime($AM_PROCT));
	$Patt_Number	= $default['Patt_Number'];		
	
	$AM_PROCS 	= $default['AM_PROCS'];
	
	if($AM_PROCS == 1)
	{
		$IS_PROCS 	= 1;
	}
	else
	{
		$IS_PROCS 	= 0;
	}
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
		if($TranslCode == 'AddItem')$AddItem = $LangTransl;
		if($TranslCode == 'Save')$Save = $LangTransl;
		if($TranslCode == 'Update')$Update = $LangTransl;
		if($TranslCode == 'Back')$Back = $LangTransl;
		if($TranslCode == 'Code')$Code = $LangTransl;
		if($TranslCode == 'AssetName')$AssetName = $LangTransl;
		if($TranslCode == 'Date')$Date = $LangTransl;
		if($TranslCode == 'Description')$Description = $LangTransl;
		if($TranslCode == 'StartDate')$StartDate = $LangTransl;
		if($TranslCode == 'EndDate')$EndDate = $LangTransl;
		if($TranslCode == 'Time')$Time = $LangTransl;
		if($TranslCode == 'Status')$Status = $LangTransl;
		if($TranslCode == 'ProcessStat')$ProcessStat = $LangTransl;
		
		if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
		if($TranslCode == 'ItemName')$ItemName = $LangTransl;
		if($TranslCode == 'Usage')$Usage = $LangTransl;
		if($TranslCode == 'Quantity')$Quantity = $LangTransl;
		if($TranslCode == 'RealQty')$RealQty = $LangTransl;
		if($TranslCode == 'Unit')$Unit = $LangTransl;
		if($TranslCode == 'Price')$Price = $LangTransl;
		if($TranslCode == 'Total')$Total = $LangTransl;
		if($TranslCode == 'Remarks')$Remarks = $LangTransl;

	endforeach;
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
                <div class="box-header with-border">               
              		<div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body chart-responsive">
                    <form class="form-horizontal" name="frm" id="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return checkInp()">
                    	<input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
                    	<input type="hidden" name="AM_PRJCODE" id="AM_PRJCODE" value="<?php echo $AM_PRJCODE; ?>" />
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
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Code ?></label>
                          	<div class="col-sm-10">
                            	<?php echo $AM_CODE; ?>
                                <input type="hidden" name="Patt_Number" id="Patt_Number" value="<?php echo $Patt_Number; ?>">
                                <input type="hidden" name="AM_CODE" id="AM_CODE" value="<?php echo $AM_CODE; ?>" >
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $AssetName ?></label>
                          	<div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-btn">
										<button type="button" class="btn btn-primary">Seacrh</button>
                                    </div>
                                    <input type="text" class="form-control" name="AM_AS_CODE" id="AM_AS_CODE" style="max-width:160px" value="<?php echo $AM_AS_CODE; ?>" onClick="pleaseCheck();">
                                </div>
                            </div>
                        </div>
						<?php
							$url_selAURCODE		= site_url('c_asset/c_453tM41Nt/popupallasset/?id='.$this->url_encryption_helper->encode_url($AM_PRJCODE));
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
							
							function add_header(strItem) 
							{
								arrItem = strItem.split('|');
								ilvl = arrItem[1];
								
								AM_AS_CODE		= arrItem[0];
								
								document.getElementById("AM_AS_CODE").value = AM_AS_CODE;
								//document.frmsrch1.submitSrch1.click();
							}
						</script>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Date ?></label>
                          	<div class="col-sm-10">
                                <div class="input-group date">
                                    <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
                                    <input type="text" name="AM_DATE" class="form-control pull-left" id="datepicker1" value="<?php echo $AM_DATE; ?>" style="width:150px">
                                </div>
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Description ?></label>
                          	<div class="col-sm-10">
                                <textarea class="form-control" name="AM_DESC"  id="AM_DESC" style="max-width:350px;height:70px"><?php echo $AM_DESC; ?></textarea>
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $StartDate ?></label>
                          	<div class="col-sm-10">
                                <div class="input-group date">
                                    <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
                                    <input type="text" name="AM_STARTD" class="form-control pull-left" id="datepicker2" value="<?php echo $AM_STARTD; ?>" style="width:150px">
                                </div>
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Time ?></label>
                          	<div class="col-sm-10">
                                <input type="text" class="form-control" style="max-width:80px; text-align:left" name="AM_STARTT" id="AM_STARTT" value="<?php echo $AM_STARTT; ?>" onKeyUp="toTimeString1(this.value)" >
                          	</div>
                        </div>
                        <script>
							function toTimeString1(AM_STARTT)
							{
								var totTxt 	= AM_STARTT.length;
								var noHour	= /^[0-2]+$/;
								var noMinut	= /^[0-5]+$/;
								if(totTxt == 1)
								{
									isHour	= document.getElementById('AM_STARTT').value;
									if(!isHour.match(noHour))
									{
										alert('Range no [0 - 2]');
										document.getElementById('AM_STARTT').value = 0;
										document.getElementById('AM_STARTT').focus();
										return false;
									}
								}
								if(totTxt == 2)
								{
									isHour	= document.getElementById('AM_STARTT').value;
									if(isHour > 24)
									{
										alert('Hour must be less then 24');
										document.getElementById('AM_STARTT').value = '';
										document.getElementById('AM_STARTT').focus();
										return false;
									}
									else
									{
										document.getElementById('AM_STARTT').value = isHour+':';
										document.getElementById('AM_STARTT').focus();
									}
								}
								
								if(totTxt == 4)
								{
									isHour		= document.getElementById('AM_STARTT').value;
									isMinutes	= isHour.substr(3,4);
									if(!isMinutes.match(noMinut))
									{
										alert('Range no [0 - 5]');
										isHour	= isHour.substr(0,3);
										document.getElementById('AM_STARTT').value = isHour;
										document.getElementById('AM_STARTT').focus();
										return false;
									}									
								}
							}
						</script>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $EndDate ?></label>
                          	<div class="col-sm-10">
                                <div class="input-group date">
                                    <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
                                    <input type="text" name="AM_ENDD" class="form-control pull-left" id="datepicker3" value="<?php echo $AM_ENDD; ?>" style="width:150px">
                                </div>
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Time ?></label>
                          	<div class="col-sm-10">
                                <input type="text" class="form-control" style="max-width:80px; text-align:left" name="AM_ENDT" id="AM_ENDT"value="<?php echo $AM_ENDT; ?>" onKeyUp="toTimeString2(this.value)" >
                          	</div>
                        </div>
                        <script>
							function toTimeString2(AM_ENDT)
							{
								var totTxt 	= AM_ENDT.length;
								var noHour	= /^[0-2]+$/;
								var noMinut	= /^[0-5]+$/;
								if(totTxt == 1)
								{
									isHour	= document.getElementById('AM_ENDT').value;
									if(!isHour.match(noHour))
									{
										alert('Range no [0 - 2]');
										document.getElementById('AM_ENDT').value = 0;
										document.getElementById('AM_ENDT').focus();
										return false;
									}									
								}
								if(totTxt == 2)
								{
									isHour	= document.getElementById('AM_ENDT').value;
									if(isHour > 24)
									{
										alert('Hour must be less then 24');
										document.getElementById('AM_ENDT').value = '';
										document.getElementById('AM_ENDT').focus();
										return false;
									}
									else
									{
										document.getElementById('AM_ENDT').value = isHour+':';
										document.getElementById('AM_ENDT').focus();
									}
								}
								
								if(totTxt == 4)
								{
									isHour		= document.getElementById('AM_ENDT').value;
									isMinutes	= isHour.substr(3,4);
									if(!isMinutes.match(noMinut))
									{
										alert('Range no [0 - 5]');
										isHour	= isHour.substr(0,3);
										document.getElementById('AM_ENDT').value = isHour;
										document.getElementById('AM_ENDT').focus();
										return false;
									}									
								}
							}
						</script>
                        <!--
                        	APPROVE STATUS
                            1 - New
                            2 - Confirm
                            3 - Approve
                        -->
                        <div class="form-group" >
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Status ?></label>
                          	<div class="col-sm-10">
                                <select name="AM_STAT" id="AM_STAT" class="form-control" style="max-width:100px">
                                	<option value="1" <?php if($AM_STAT == 1) { ?> selected <?php } ?>>New</option>
                                	<option value="2" <?php if($AM_STAT == 2) { ?> selected <?php } ?> style="display:none">Confirm</option>
                                	<option value="3" <?php if($AM_STAT == 3) { ?> selected <?php } ?>>Approve</option>
                                </select>
                            </div>
                        </div>
                        <?php if($AM_STAT == 3) 
						{ 
							?>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $ProcessStat ?></label>
                                    <div class="col-sm-10">
                                        <select name="AM_PROCS" id="AM_PROCS" class="form-control" style="max-width:130px" <?php if($AM_PROCS != 1) { ?> disabled <?php } ?>>
                                            <option value="1" <?php if($AM_PROCS == 1) { ?> selected <?php } ?>>Processing</option>
                                            <option value="2" <?php if($AM_PROCS == 2) { ?> selected <?php } ?>>Finish</option>
                                            <option value="3" <?php if($AM_PROCS == 3) { ?> selected <?php } ?>>Canceled</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Date ?></label>
                                    <div class="col-sm-10">
                                        <div class="input-group date">
                                            <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
                                            <input type="text" name="AM_PROCD" class="form-control pull-left" id="datepicker4" value="<?php echo $AM_PROCD; ?>" style="width:150px">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Time ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" style="max-width:80px" name="AM_PROCT" id="AM_PROCT" value="<?php echo $AM_PROCT; ?>" onKeyUp="toTimeString3(this.value)" >
                                    </div>
                                </div>
								<script>
                                    function toTimeString3(AM_PROCT)
                                    {
                                        var totTxt 	= AM_PROCT.length;
                                        var noHour	= /^[0-2]+$/;
                                        var noMinut	= /^[0-5]+$/;
                                        if(totTxt == 1)
                                        {
                                            isHour	= document.getElementById('AM_PROCT').value;
                                            if(!isHour.match(noHour))
                                            {
                                                alert('Range no [0 - 2]');
                                                document.getElementById('AM_PROCT').value = 0;
                                                document.getElementById('AM_PROCT').focus();
                                                return false;
                                            }
                                        }
                                        if(totTxt == 2)
                                        {
                                            isHour	= document.getElementById('AM_PROCT').value;
                                            if(isHour > 24)
                                            {
                                                alert('Hour must be less then 24');
                                                document.getElementById('AM_PROCT').value = '';
                                                document.getElementById('AM_PROCT').focus();
                                                return false;
                                            }
                                            else
                                            {
                                                document.getElementById('AM_PROCT').value = isHour+':';
                                                document.getElementById('AM_PROCT').focus();
                                            }
                                        }
                                        
                                        if(totTxt == 4)
                                        {
                                            isHour		= document.getElementById('AM_PROCT').value;
                                            isMinutes	= isHour.substr(3,4);
                                            if(!isMinutes.match(noMinut))
                                            {
                                                alert('Range no [0 - 5]');
                                                isHour	= isHour.substr(0,3);
                                                document.getElementById('AM_PROCT').value = isHour;
                                                document.getElementById('AM_PROCT').focus();
                                                return false;
                                            }									
                                        }
                                    }
                                </script>
							<?php
						}
							$url_AddItem	= site_url('c_asset/c_453tM41Nt/popupallitem/?id='.$this->url_encryption_helper->encode_url($AM_PRJCODE));
                        ?>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">&nbsp;</label>
                          	<div class="col-sm-10">
								<script>
									var url = "<?php echo $url_AddItem;?>";
									function selectitem()
									{
										AM_ASTCODE	= document.getElementById("AM_AS_CODE").value;
										if(AM_ASTCODE == '')
										{
											alert('Please select an Asset');
											pleaseCheck();
											return false;
										}
										
										title = 'Select Item';
										w = 1000;
										h = 550;
										//window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
										var left = (screen.width/2)-(w/2);
										var top = (screen.height/2)-(h/2);
										return window.open(url, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
									}
								</script>
								<!--<a href="javascript:void(null);" onClick="selectitem();">
									Add Sparepart [+]
                                </a>-->
                                
                                <button class="btn btn-success" type="button" onClick="selectitem();">
                                    <i class="cus-add-item-16x16"></i>&nbsp;&nbsp;<?php echo $AddItem; ?>
                                </button>
                                </div>
                        </div>
                        <div class="form-group">
                          	<div class="col-sm-10">
                                <div class="box-tools pull-right">
                                    <table width="100%" border="1" id="tbl">
                                        <tr style="background:#CCCCCC">
                                            <th width="4%" height="25" rowspan="2" style="text-align:left">&nbsp;</th>
                                          <th width="10%" rowspan="2" style="text-align:center"><?php echo $ItemCode ?> </th>
                                          <th width="40%" rowspan="2" style="text-align:center"><?php echo $ItemName ?> </th>
                                          <th colspan="<?php if($AM_STAT != 3) { ?> 2 <?php } else { ?>3 <?php } ?>" style="text-align:center"><?php echo $Usage ?> </th>
                                            <th rowspan="2" style="text-align:center"><?php echo $Price ?> </th>
                                            <th rowspan="2" style="text-align:center"><?php echo $Total ?> </th>
                                            <th width="29%" rowspan="2" style="text-align:center"><?php echo $Remarks ?> </th>
                                      </tr>
                                        <tr style="background:#CCCCCC">
                                            <th style="text-align:center;"><?php echo $Quantity ?> </th>
                                            <th style="text-align:center; <?php if($AM_STAT != 3) { ?> display:none <?php } ?>" nowrap><?php echo $RealQty ?> </th>
                                            <th style="text-align:center;"><?php echo $Unit ?> </th>
                                        </tr>
                                        <?php					
                                        if($task == 'edit')
                                        {
                                            $sqlDET		= "SELECT A.AM_CODE, A.AM_PRJCODE, A.ITM_CODE, A.ITM_QTY_P, A.ITM_QTY, A.ITM_UNIT, 
															A.ITM_PRICE, A.NOTES, A.ITM_KIND,
															B.ITM_DESC,
                                                            C.Unit_Type_Code, C.UMCODE, C.Unit_Type_Name
                                                            FROM tbl_asset_maintendet A
                                                                INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
                                                                INNER JOIN tbl_unittype C ON C.UMCODE = A.ITM_UNIT
                                                            WHERE A.AM_CODE = '$AM_CODE' 
                                                            AND B.PRJCODE = '$PRJCODE'";
                                            // count data
                                                $resultCount = $this->db->where('AM_CODE', $AM_CODE);
                                                $resultCount = $this->db->count_all_results('tbl_asset_maintendet');
                                            // End count data
                                            $result = $this->db->query($sqlDET)->result();
                                            $i		= 0;
                                            $j		= 0;
                                            if($resultCount > 0)
                                            {
                                                foreach($result as $row) :
                                                    $currentRow  	= ++$i;
                                                    $AM_CODE 		= $row->AM_CODE;
                                                    $ITM_CODE 		= $row->ITM_CODE;
                                                    $ITM_DESC 		= $row->ITM_DESC;
                                                    $AM_PRJCODE		= $row->AM_PRJCODE;
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
																			AND PRJCODE = '$AM_PRJCODE'
																			AND STATUS = 1";
													$resQTY			= $this->db->query($sqlQTY)->result();
													foreach($resQTY as $rowQTY) :
														$ITM_IN 		= $rowQTY->ITM_IN;
														$ITM_OUT	 	= $rowQTY->ITM_OUT;
													endforeach;
													
                                        
                                                    if ($j==1) {
                                                        echo "<tr class=zebra1>";
                                                        $j++;
                                                    } else {
                                                        echo "<tr class=zebra2>";
                                                        $j--;
                                                    }
                                                    ?> 
                                                    <tr><td width="4%" height="25" style="text-align:left">
                                                     	<?php echo "$currentRow."; ?>
                                               	    	<input type="Checkbox" id="data[<?php echo $currentRow; ?>][chk]" name="data[<?php echo $currentRow; ?>][chk]" value="<?php echo $currentRow; ?>" onClick="pickThis(this,<?php echo $currentRow; ?>)" style="display:none">
                                                        <input type="Checkbox" style="display:none" id="chk<?php echo $currentRow; ?>" name="chk<?php echo $currentRow; ?>" value=""><input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">
                                                        <input type="hidden" id="chk" name="chk" value="<?php echo $currentRow; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">
                                                        <input type="hidden" id="data<?php echo $currentRow; ?>AM_CODE" name="data[<?php echo $currentRow; ?>][AM_CODE]" value="<?php echo $AM_CODE; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">
                                                        <input type="hidden" id="data<?php echo $currentRow; ?>AM_PRJCODE" name="data[<?php echo $currentRow; ?>][AM_PRJCODE]" value="<?php echo $AM_PRJCODE; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">
                                                        <input type="hidden" id="data<?php echo $currentRow; ?>ITM_KIND" name="data[<?php echo $currentRow; ?>][ITM_KIND]" value="<?php echo $ITM_KIND; ?>" width="10">
                                                    	<!-- Checkbox -->
                                                    </td>
                                               	  	<td width="10%" style="text-align:left" nowrap>
                                                      	<?php echo $ITM_CODE; ?>
                                           				<input type="hidden" id="data<?php echo $currentRow; ?>AM_ASTCODE" name="data[<?php echo $currentRow; ?>][AM_ASTCODE]" value="<?php echo $AM_AS_CODE; ?>" width="10" size="15" readonly class="form-control">
                                           				<input type="hidden" id="data<?php echo $currentRow; ?>ITM_CODE" name="data[<?php echo $currentRow; ?>][ITM_CODE]" value="<?php echo $ITM_CODE; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">
                                                        <!-- Item Code -->													</td>
                                               	  	<td width="40%" style="text-align:left">
                                                      	<?php echo $ITM_DESC; ?>
														<!-- Item Name --></td>
                                               	  	<td width="7%" nowrap style="text-align:right">
                                            			<input type="text" class="form-control" style="min-width:100px; max-width:350px; text-align:right" name="ITM_QTY_Px<?php echo $currentRow; ?>" id="ITM_QTY_Px<?php echo $currentRow; ?>" value="<?php print number_format($ITM_QTY_P, $decFormat); ?>" disabled >
                                                        <input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][ITM_QTY_P]" id="ITM_QTY_P<?php echo $currentRow; ?>" size="10" value="<?php echo $ITM_QTY_P; ?>" >
                                                        <!-- Item Price -->													</td>
                                               	  	<td width="7%" nowrap style="text-align:right; <?php if($AM_STAT != 3) { ?> display:none <?php } ?>">
                                            			<input type="text" class="form-control" style="min-width:100px; max-width:350px; text-align:right" name="ITM_QTYx<?php echo $currentRow; ?>" id="ITM_QTYx<?php echo $currentRow; ?>" value="<?php print number_format($ITM_QTY, $decFormat); ?>" onBlur="changeValue(this, <?php echo $currentRow; ?>, 1)" >
                                                        <input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][ITM_QTY]" id="ITM_QTY<?php echo $currentRow; ?>" size="10" value="<?php echo $ITM_QTY; ?>" >
                                                        <input type="hidden" style="text-align:right" name="ITM_QTY_MIN<?php echo $currentRow; ?>" id="ITM_QTY_MIN<?php echo $currentRow; ?>" size="10" value="<?php echo $ITM_QTY; ?>" ></td>
                                               	  	<td width="2%" nowrap style="text-align:center">
                                                      	<?php echo $ITM_UNIT; ?>
                                       					<input type="hidden" class="form-control" style="max-width:350px; text-align:right" name="data[<?php echo $currentRow; ?>][ITM_UNIT]" id="ITM_UNIT<?php echo $currentRow; ?>" size="10" value="<?php echo $ITM_UNIT; ?>" >
                                                      	<!-- Item Unit -->													</td>
                                               	  	<td width="1%" nowrap style="text-align:center">
                                                  		<input type="text" class="form-control" style="min-width:100px; max-width:350px; text-align:right" name="ITM_PRICEx<?php echo $currentRow; ?>" id="ITM_PRICEx<?php echo $currentRow; ?>" value="<?php print number_format($ITM_PRICE, $decFormat); ?>" onBlur="changeValuePrc(this, <?php echo $currentRow; ?>)" >
                                                        <input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][ITM_PRICE]" id="ITM_PRICE<?php echo $currentRow; ?>" size="10" value="<?php echo $ITM_PRICE; ?>" >													</td>
                                               	  	<td width="0%" nowrap style="text-align:center">
                                                  		<input type="text" class="form-control" style="min-width:100px; max-width:350px; text-align:right" name="ITM_TOTALx<?php echo $currentRow; ?>" id="ITM_TOTALx<?php echo $currentRow; ?>" value="<?php print number_format($ITM_TOTAL, $decFormat); ?>" >
                                                        <input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][ITM_TOTAL]" id="ITM_TOTAL<?php echo $currentRow; ?>" size="10" value="<?php echo $ITM_TOTAL; ?>" ></td>
                                               	  	<td width="29%" style="text-align:center">
                                           				<input type="text" name="data[<?php echo $currentRow; ?>][NOTES]" id="data<?php echo $currentRow; ?>NOTES" value="<?php echo $NOTES; ?>" class="form-control" style="max-width:450px;text-align:left">
                                                        <!-- Notes -->													</td>
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
										if($AM_STAT == 3 && $AM_PROCS != 1)
										{
										}
										else
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
	var decFormat		= 2;
	
	function checkInp()
	{
		AM_AS_CODE	= document.getElementById("AM_AS_CODE").value;
		if(AM_AS_CODE == 0)
		{
			alert("Please select Asset Code");
			document.getElementById("AM_AS_CODE").focus();
			pleaseCheck();
			return false;
		}
		
		AM_DESC	= document.getElementById("AM_DESC").value;
		if(AM_DESC == 0)
		{
			alert("Please input usage description.");
			document.getElementById("AM_DESC").focus();
			return false;
		}
		
		AM_STARTT	= document.getElementById("AM_STARTT").value;
		if(AM_STARTT == '00:00')
		{
			alert("Please input start time");
			document.getElementById("AM_STARTT").value = '06:00';
			document.getElementById("AM_STARTT").focus();
			return false;
		}
		
		AM_ENDT		= document.getElementById("AM_ENDT").value;
		if(AM_ENDT == '00:00')
		{
			alert("Please input end time");
			document.getElementById("AM_ENDT").value = '11:00';
			document.getElementById("AM_ENDT").focus();
			return false;
		}
		
		IS_PROCS	= document.getElementById("IS_PROCS").value;
		if(IS_PROCS > 0)
		{
			AM_PROCS	= document.getElementById("AM_PROCS").value;
			AM_PROCT	= document.getElementById("AM_PROCT").value;
			if(AM_PROCS > 1)
			{
				if(AM_PROCT == '00:00')
				{
					alert("Please input finish time.");
					document.getElementById("AM_PROCT").value = '00:00';
					document.getElementById("AM_PROCT").focus();
					return false;
				}
			}
		}
		
		totRow	= document.getElementById('totalrow').value;
		if(totRow == 0)
		{
			alert("Please select 1 or more Item detail.");
			selectitem();
			return false;			
		}
	}
	
	function changeValue(thisVal, theRow, isReal)
	{
		var ITM_QTY_MIN	= document.getElementById('ITM_QTY_MIN'+theRow).value;
		var ITM_QTYx 	= eval(thisVal).value.split(",").join("");
		if(parseFloat(ITM_QTYx) > parseFloat(ITM_QTY_MIN))
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
		}
		
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
		var AM_CODE 	= "<?php echo $AM_CODE; ?>";
		var AM_PRJCODE 	= "<?php echo $AM_PRJCODE; ?>";
		var AM_ASTCODE 	= document.getElementById("AM_AS_CODE").value;
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
		objTD.innerHTML = ''+intIndex+'<input type="Checkbox" id="data['+intIndex+'][chk]" name="data['+intIndex+'][chk]" value="'+intIndex+'" onClick="pickThis(this,'+intIndex+')" style="display:none"><input type="Checkbox" style="display:none" id="chk'+intIndex+'" name="chk'+intIndex+'" value=""><input type="hidden" name="totalrow" id="totalrow" value="'+intIndex+'"><input type="hidden" id="chk" name="chk" value="'+intIndex+'" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right"><input type="hidden" id="data'+intIndex+'AM_CODE" name="data['+intIndex+'][AM_CODE]" value="'+AM_CODE+'" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right"><input type="hidden" id="data'+intIndex+'AM_PRJCODE" name="data['+intIndex+'][AM_PRJCODE]" value="'+AM_PRJCODE+'" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right"><input type="hidden" id="data'+intIndex+'ITM_KIND" name="data['+intIndex+'][ITM_KIND]" value="'+itemKind+'" width="10">';	
		
		// Item Code
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'right';
		objTD.innerHTML = ''+itemcode+'<input type="hidden" id="data'+intIndex+'ITM_CODE" name="data['+intIndex+'][ITM_CODE]" value="'+itemcode+'" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right"><input type="hidden" id="data'+intIndex+'AM_ASTCODE" name="data['+intIndex+'][AM_ASTCODE]" value="'+AM_ASTCODE+'" class="form-control">';
		
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
		objTD.style.textAlign = 'right';
		objTD.innerHTML = ''+itemUnitName+'<input type="hidden" class="form-control" style="max-width:350px; text-align:right" name="data['+intIndex+'][ITM_UNIT]" id="ITM_UNIT'+intIndex+'" size="10" value="'+itemUnit+'" >';
		
		// Item Price
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" class="form-control" style="min-width:100px; max-width:350px; text-align:right" name="ITM_PRICEx'+intIndex+'" id="ITM_PRICEx'+intIndex+'" value="'+itemPrice+'" onBlur="changeValuePrc(this, '+intIndex+')" ><input type="hidden" style="text-align:right" name="data['+intIndex+'][ITM_PRICE]" id="ITM_PRICE'+intIndex+'" size="10" value="'+itemPrice+'" >';
		
		// Item Price Total
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" class="form-control" style="min-width:130px; max-width:350px; text-align:right" name="ITM_TOTALx'+intIndex+'" id="ITM_TOTALx'+intIndex+'" value="'+ITM_TOTAL+'" ><input type="hidden" style="text-align:right" name="data['+intIndex+'][ITM_TOTAL]" id="ITM_TOTAL'+intIndex+'" size="10" value="'+ITM_TOTAL+'" >';
		
		// Notes
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" name="data['+intIndex+'][NOTES]" id="data'+intIndex+'NOTES" value="" class="form-control" style="max-width:450px;text-align:left">';
		
		var decFormat											= document.getElementById('decFormat').value;
		var ITM_QTY												= document.getElementById('ITM_QTY'+intIndex).value
		document.getElementById('ITM_QTY'+intIndex).value 		= parseFloat(Math.abs(ITM_QTY));
		document.getElementById('ITM_QTYx'+intIndex).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_QTY)),decFormat));
		
		var ITM_PRICE											= document.getElementById('ITM_PRICE'+intIndex).value
		document.getElementById('ITM_PRICE'+intIndex).value 	= parseFloat(Math.abs(ITM_PRICE));
		document.getElementById('ITM_PRICEx'+intIndex).value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_PRICE)),decFormat));
		
		var ITM_TOTAL											= document.getElementById('ITM_TOTAL'+intIndex).value
		document.getElementById('ITM_TOTAL'+intIndex).value 	= parseFloat(Math.abs(ITM_TOTAL));
		document.getElementById('ITM_TOTALx'+intIndex).value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_TOTAL)),decFormat));
		
		document.getElementById('totalrow').value 				= intIndex;
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
				if (elitem1 == vcode)
				{
					duplicate = true;
					break;
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