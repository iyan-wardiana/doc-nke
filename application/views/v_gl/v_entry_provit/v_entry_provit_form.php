<?php
/* 
 * Author		= Hendar Permana
 * Create Date	= 14 Juni 2017
 * File Name	= v_entry_provit_form.php
 * Location		= -
*/
?>
<?php
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
if($decFormat == 0)
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
			$AS_PREFIX	= 'XXX';
		}
		else
		{
			$PREFC	= 0;
		}
	}
	
	
	
	$sql = "tbl_profloss_man";
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
	
	
	
	$CODE_PROFLOSS		= date('ymd').$lastPatternNumb;
	$PRJCODE			= '';
	$DATEA		 		= date('Y');
	$DATEB 				= date('m');
	$DATEC 				= date('d');
	$DATED 				= "$DATEB/$DATEC/$DATEA";
	$DATE				= $DATED;
//	$DATE				= date('m/d/Y');
	$PROG_KONTRAKTUIL	= 0.00;
	$PEKERJAAN			= 0.00;
	$PROYEK_WIP			= 0.00;
	$PROYEK_MATERIAL	= 0.00;
	$PROYEK_UPAH		= 0.00;
	$PROYEK_SUBKON 		= 0.00;
	$PROYEK_ALAT		= 0.00;
	$PROYEK_OVERHEAD 	= 0.00;
	$PUSAT_MATERIAL 	= 0.00;
	$PUSAT_SUBKON 		= 0.00;
	$PUSAT_ALAT 		= 0.00;
	$PUSAT_OVERHEAD 	= 0.00;
	$STOK 				= 0.00;
	$STOK_MATERIAL 		= 0.00;
	$STOK_PELUMAS_BBM 	= 0.00;
	$STOK_SUKU_CADANG 	= 0.00;
	$STOK_LAIN_LAIN 	= 0.00;
	$STOK_INV_BEKISTING = 0.00;
	$OVER_BOOKING		= 0.00;
	$BEBAN_ALAT			= 0.00;
	
}
else
{

	$CODE_PROFLOSS		= $default['CODE_PROFLOSS'];
	$PRJCODE			= $default['PRJCODE'];
	$DATE				= $default['DATE'];
	
	$DATEA 		= date("Y", strtotime($DATE));
	$DATEB 		= date("m", strtotime($DATE));
	$DATEC 		= date("d", strtotime($DATE));
	$DATE 		= "$DATEB/$DATEC/$DATEA";
	
	$PROG_KONTRAKTUIL	= $default['PROG_KONTRAKTUIL'];
	$PEKERJAAN			= $default['PEKERJAAN'];
	$PROYEK_WIP			= $default['PROYEK_WIP'];
	$PROYEK_MATERIAL	= $default['PROYEK_MATERIAL'];
	$PROYEK_UPAH		= $default['PROYEK_UPAH'];
	$PROYEK_SUBKON 		= $default['PROYEK_SUBKON'];
	$PROYEK_ALAT		= $default['PROYEK_ALAT'];
	$PROYEK_OVERHEAD 	= $default['PROYEK_OVERHEAD'];
	$PUSAT_MATERIAL 	= $default['PUSAT_MATERIAL'];
	$PUSAT_SUBKON 		= $default['PUSAT_SUBKON'];
	$PUSAT_ALAT 		= $default['PUSAT_ALAT'];
	$PUSAT_OVERHEAD 	= $default['PUSAT_OVERHEAD'];
	$STOK 				= $default['STOK'];
	$STOK_MATERIAL 		= $default['STOK_MATERIAL'];
	$STOK_PELUMAS_BBM 	= $default['STOK_PELUMAS_BBM'];
	$STOK_SUKU_CADANG 	= $default['STOK_SUKU_CADANG'];
	$STOK_LAIN_LAIN 	= $default['STOK_LAIN_LAIN'];
	$STOK_INV_BEKISTING = $default['STOK_INV_BEKISTING'];
	$OVER_BOOKING		= $default['OVER_BOOKING'];
	$BEBAN_ALAT			= $default['BEBAN_ALAT'];
	
	
	if($DATE == '0000-00-00')
	{
		$sqlX = "SELECT DATE
				FROM tbl_profloss_man WHERE CODE_PROFLOSS = '$CODE_PROFLOSS'";
		$result = $this->db->query($sqlX)->result();
		foreach($result as $rowx) :
			$DATE		= $rowx->DATE;
		endforeach;
		if($DATE == '0000-00-00')
		{
			$DATEA 	= date('Y');
			$DATEB 	= date('m');
			$DATEC 	= date('d');
			$DATE 	= "$DATEB/$DATEC/$DATEA";
		}
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
                	

                    <form name="frmsrch" id="frmsrch" action="" method=POST style="display:none">
                        <input type="text" name="CODE_PROFLOSS" id="CODE_PROFLOSS" class="textbox" value="<?php echo $CODE_PROFLOSS; ?>" />
                        <input type="submit" class="button_css" name="submitSrch" id="submitSrch" value=" search " />
                    </form>
		
                    <form class="form-horizontal" name="frm" id="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return checkInp()">
                    	<input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
           				<input type="Hidden" name="rowCount" id="rowCount" value="0">
    
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Code</label>
                          	<div class="col-sm-10">
                            	<input type="text" class="form-control" style="max-width:200px; "  name="CODE_PROFLOSS" id="CODE_PROFLOSS" value="<?php echo $CODE_PROFLOSS; ?>" >
                          	</div>
                        </div>
        				<div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Project Code</label>
                          	<div class="col-sm-10">
                             <select name="PRJCODE" id="PRJCODE" class="form-control" style="max-width:200px">
                             <option value="-">--- None ---</option>
                             <?php
                                    $sqlPRJ 		= "SELECT PRJCODE, PRJNAME FROM tbl_project ORDER BY PRJNAME";
                                    $resultPRJ 	= $this->db->query($sqlPRJ)->result();
									
									foreach($resultPRJ as $rowPRJ) :
										$PRJCODE1 	= $rowPRJ->PRJCODE;
										$PRJNAME1 	= $rowPRJ->PRJNAME;
							?>
											<option value="<?php echo $PRJCODE1; ?>" <?php if($PRJCODE1 == $PRJCODE) { ?>selected <?php } ?>>
												<?php echo "$PRJCODE1 - $PRJNAME1"; ?>
											</option>
							<?php
									 endforeach;
                            ?>
                             

             			 </select>
                        </div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Date</label>
                          	<div class="col-sm-10">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>&nbsp;</div>
                                    <input type="text" name="DATE" class="form-control pull-left" id="datepicker1" value="<?php echo $DATE; ?>" style="width:157px">
                                </div>
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Progres Kontraktuil</label>
                          	<div class="col-sm-10">
                                <input type="text" class="form-control" style="max-width:200px" name="PROG_KONTRAKTUIL" id="PROG_KONTRAKTUIL"value="<?php echo number_format($PROG_KONTRAKTUIL, $decFormat); ?>" >
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Pekerjaan / MC Katrolan</label>
                          	<div class="col-sm-10">
                                <input type="text" class="form-control" style="max-width:200px" name="PEKERJAAN" id="PEKERJAAN"value="<?php echo number_format($PEKERJAAN, $decFormat); ?>" >
                          	</div>
                        </div>

                        <div class="form-group">

                          	<label for="inputName" class="col-sm-2 control-label">WIP Proyek</label>
                          	<div class="col-sm-10">
                    			<input type="text" class="form-control" style="max-width:200px" name="PROYEK_WIP" id="PROYEK_WIP"value="<?php echo number_format($PROYEK_WIP, $decFormat); ?>" >
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Proyek Material</label>
                          	<div class="col-sm-10">
                            <input type="text" class="form-control" style="max-width:200px" name="PROYEK_MATERIAL" id="PROYEK_MATERIAL"value="<?php echo number_format($PROYEK_MATERIAL, $decFormat); ?>" >
                          	</div>
                        </div>                      
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Proyek Upah</label>
                          	<div class="col-sm-10">
                            	<input type="text" class="form-control" style="max-width:200px" name="PROYEK_UPAH" id="PROYEK_UPAH"value="<?php echo number_format($PROYEK_UPAH, $decFormat); ?>" >
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Proyek Subkon</label>
                          	<div class="col-sm-10">
                            	<input type="text" class="form-control" style="max-width:200px" name="PROYEK_SUBKON" id="PROYEK_SUBKON"value="<?php echo number_format($PROYEK_SUBKON, $decFormat); ?>" >
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Proyek Alat</label>
                          	<div class="col-sm-10">
                            	<input type="text" class="form-control" style="max-width:200px" name="PROYEK_ALAT" id="PROYEK_ALAT"value="<?php echo number_format($PROYEK_ALAT, $decFormat); ?>" >
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Proyek Overhead</label>
                          	<div class="col-sm-10">
                            	<input type="text" class="form-control" style="max-width:200px" name="PROYEK_OVERHEAD" id="PROYEK_OVERHEAD"value="<?php echo number_format($PROYEK_OVERHEAD, $decFormat); ?>" >
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Pusat Material</label>
                          	<div class="col-sm-10">
                            	<input type="text" class="form-control" style="max-width:200px" name="PUSAT_MATERIAL" id="PUSAT_MATERIAL"value="<?php echo number_format($PROYEK_MATERIAL, $decFormat); ?>" >
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Pusat Subkon</label>
                          	<div class="col-sm-10">
                            	<input type="text" class="form-control" style="max-width:200px" name="PUSAT_SUBKON" id="PUSAT_SUBKON"value="<?php echo number_format($PUSAT_SUBKON, $decFormat); ?>" >
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Pusat Alat</label>
                          	<div class="col-sm-10">
                            	<input type="text" class="form-control" style="max-width:200px" name="PUSAT_ALAT" id="PUSAT_ALAT"value="<?php echo number_format($PUSAT_ALAT, $decFormat); ?>" >
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Pusat Overhead</label>
                          	<div class="col-sm-10">
                            	<input type="text" class="form-control" style="max-width:200px" name="PUSAT_OVERHEAD" id="PUSAT_OVERHEAD"value="<?php echo number_format($PUSAT_OVERHEAD, $decFormat); ?>" >
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Stok</label>
                          	<div class="col-sm-10">
                            	<input type="text" class="form-control" style="max-width:200px" name="STOK" id="STOK"value="<?php echo number_format($STOK, $decFormat); ?>" >
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Stok Material</label>
                          	<div class="col-sm-10">
                            	<input type="text" class="form-control" style="max-width:200px" name="STOK_MATERIAL" id="STOK_MATERIAL"value="<?php echo number_format($STOK_MATERIAL, $decFormat); ?>" >
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Stok Pelumas BBM</label>
                          	<div class="col-sm-10">
                            	<input type="text" class="form-control" style="max-width:200px" name="STOK_PELUMAS_BBM" id="STOK_PELUMAS_BBM"value="<?php echo number_format($STOK_PELUMAS_BBM, $decFormat); ?>" >
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Stok Suku Cadang</label>
                          	<div class="col-sm-10">
                            	<input type="text" class="form-control" style="max-width:200px" name="STOK_SUKU_CADANG" id="STOK_SUKU_CADANG"value="<?php echo number_format($STOK_SUKU_CADANG, $decFormat); ?>" >
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Stok Lain-Lain</label>
                          	<div class="col-sm-10">
                            	<input type="text" class="form-control" style="max-width:200px" name="STOK_LAIN_LAIN" id="STOK_LAIN_LAIN"value="<?php echo number_format($STOK_LAIN_LAIN, $decFormat); ?>" >
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Stok Inventaris Bekisting</label>
                          	<div class="col-sm-10">
                            	<input type="text" class="form-control" style="max-width:200px" name="STOK_INV_BEKISTING" id="STOK_INV_BEKISTING"value="<?php echo number_format($STOK_INV_BEKISTING, $decFormat); ?>" >
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Over Booking</label>
                          	<div class="col-sm-10">
                            	<input type="text" class="form-control" style="max-width:200px" name="OVER_BOOKING" id="OVER_BOOKING"value="<?php echo number_format($OVER_BOOKING, $decFormat); ?>" >
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Beban Alat</label>
                          	<div class="col-sm-10">
                            	<input type="text" class="form-control" style="max-width:200px" name="BEBAN_ALAT" id="BEBAN_ALAT"value="<?php echo number_format($BEBAN_ALAT, $decFormat); ?>" >
                          	</div>
                        </div>
                        <br>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                            	<input type="submit" class="btn btn-primary" name="submit" id="submit" value="<?php if($task=='add')echo 'save'; else echo 'update';?>" align="left" />&nbsp;
								<?php 
									if ( ! empty($link))
									{
										foreach($link as $links)
										{
											echo $links;
										}
									}
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
</script>

<script>
	function validateInData()
	{
		nextornot = document.getElementById('CheckThe_Code').value;
		if(nextornot > 0)
		{
			alert('Project Code Already Exist. Please Change.');
			document.getElementById('PRJCODE').value = '';
			document.getElementById('PRJCODE').focus();
			return false;
		}
		
		PRJNAME = document.getElementById('PRJNAME').value;
		if(PRJNAME == '')
		{
			alert('Project Name can not be empty');
			document.getElementById('PRJNAME').focus();
			return false;
		}
		
		PRJOWN = document.getElementById('PRJOWN').value;
		if(PRJOWN == 'none')
		{
			//alert('Please chose one of Owner Project.');
			//document.getElementById('PRJOWN').focus();
			//return false;
		}
		
		var PRJDATE = new Date(document.frm.PRJDATE.value);
		
		var PRJEDAT = new Date(document.frm.PRJEDAT.value);
		
		if(PRJEDAT < PRJDATE)
		{
			alert('End Date Project must be Greater than Start Date Project.');
			return false;
		}
		
		var ISCHANGE	= document.getElementById('ISCHANGEX').value;
		if(ISCHANGE == 1)
		{
			var REFCHGNO	= document.getElementById('REFCHGNO').value;
			if(REFCHGNO == '')
			{
				alert('Please input reference number.');
				document.getElementById('REFCHGNO').focus();
				return false;
			}
			
			var PRJCOST2 = eval(document.getElementById('PRJCOST22a')).value.split(",").join("");
			if(PRJCOST2 == 0)
			{
				alert('Please input new of Contract Value.');
				document.getElementById('PRJCOST22a').focus();
				return false;
			}
		}
		else
		{
			document.getElementById('REFCHGNO').value 	= '';
			document.getElementById('PRJCOST22a').value = '0.00';
			document.getElementById('PRJCOST22').value 	= '0';
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