<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 6 Febrruari 2018
 * File Name	= v_reservation_mr_adm_form.php
 * Location		= -
*/
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$FlagUSER 	= $this->session->userdata['FlagUSER'];
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

// _global function

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
$CURR_DATE		= date('m/d/Y');

if($task == 'add')
{
	$RSV_CODE		= date('YmdHis');
	$RSV_CATEG		= 'VH';				// Meeting Room
	$CATEG_CODE		= '';
	$RSV_STARTD		= date('m/d/Y');
	$RSV_ENDD		= date('m/d/Y');
	$RSV_STARTT		= '00:00';
	$RSV_ENDT		= '00:00';
	$RSV_TITLE		= '';
	$RSV_QTY		= 0;
	$RSV_DESC		= '';
	$RSV_MEMO		= '';
	$RSV_EMPID		= '';
	$RSV_SUBMITTER	= '';
	$RSV_STAT		= 1;
	
	$RSV_STARTD2	= date('m/d/Y');
	$RSV_ENDD2		= date('m/d/Y');
	$RSV_STARTT2	= '00:00';
	$RSV_ENDT2		= '00:00';
	$RSV_MAIL		= '';
}
else
{
	$RSV_CODE 		= $default['RSV_CODE'];
	$RSV_CATEG		= $default['RSV_CATEG'];
	$CATEG_CODE		= $default['CATEG_CODE'];
	
	if($CATEG_CODE != '')
	{
		$VH_CODE		= $default['VH_CODE'];
		$VH_TYPE		= $default['VH_TYPE'];	
		$VH_MEREK		= $default['VH_MEREK'];
		$VH_NOPOL		= $default['VH_NOPOL'];
		$VH_STAT		= $default['VH_STAT'];	
	}
	else
	{
		$VH_CODE		= "";
		$VH_TYPE		= "";	
		$VH_MEREK		= "";
		$VH_NOPOL		= "";
		$VH_STAT		= "";	
	}
	
	$DRIVER_CODEX	= $default['DRIVER_CODE'];
	if($DRIVER_CODEX != '')
	{
		$DRIVER			= $default['DRIVER'];
		$DRIVER_STAT	= $default['DRIVER_STAT'];
	}
	else
	{
		$DRIVER			= "";
		$DRIVER_STAT	= 0;
	}
	
	$RSV_STARTD		= $default['RSV_STARTD'];
	$RSV_ENDD		= $default['RSV_ENDD'];
	$RSV_STARTD		= date('m/d/Y',strtotime($RSV_STARTD));
	$RSV_ENDD		= date('m/d/Y',strtotime($RSV_ENDD));
	$RSV_STARTT		= $default['RSV_STARTT'];
	$RSV_ENDT		= $default['RSV_ENDT'];
	$RSV_STARTT		= date('H:i',strtotime($RSV_STARTT));
	$RSV_ENDT		= date('H:i',strtotime($RSV_ENDT));
	$RSV_TITLE		= $default['RSV_TITLE'];
	$RSV_DESC		= $default['RSV_DESC'];
	$RSV_QTY		= $default['RSV_QTY'];
	$RSV_MEMO		= $default['RSV_MEMO'];
	$RSV_SUBMITTER	= $default['RSV_SUBMITTER'];
	$RSV_MAIL 		= $default['RSV_MAIL'];
	$RSV_STAT 		= $default['RSV_STAT'];
	
	
	$RSV_STARTD2	= $default['RSV_STARTD2'];
	$RSV_ENDD2		= $default['RSV_ENDD2'];	
	$RSV_STARTD2	= date('m/d/Y',strtotime($RSV_STARTD2));
	$RSV_ENDD2		= date('m/d/Y',strtotime($RSV_ENDD2));
	
	$RSV_STARTT2	= $default['RSV_STARTT2'];
	$RSV_ENDT2		= $default['RSV_ENDT2'];
	$RSV_STARTT2	= date('H:i',strtotime($RSV_STARTT2));
	$RSV_ENDT2		= date('H:i',strtotime($RSV_ENDT2));
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
		if($TranslCode == 'Code')$Code = $LangTransl;
		if($TranslCode == 'StartDate')$StartDate = $LangTransl;
		if($TranslCode == 'EndDate')$EndDate = $LangTransl;
		if($TranslCode == 'Description')$Description = $LangTransl;
		if($TranslCode == 'Status')$Status = $LangTransl;
		if($TranslCode == 'Vehicle')$Vehicle = $LangTransl;
		if($TranslCode == 'Driver')$Driver = $LangTransl;
		if($TranslCode == 'NOPOL')$NOPOL = $LangTransl;
		if($TranslCode == 'Destination')$Destination = $LangTransl;
		if($TranslCode == 'Time')$Time = $LangTransl;
		if($TranslCode == 'Topic')$Topic = $LangTransl;
		if($TranslCode == 'Participants')$Participants = $LangTransl;
		if($TranslCode == 'Qty')$Qty = $LangTransl;
		if($TranslCode == 'Notes')$Notes = $LangTransl;
		if($TranslCode == 'Email')$Email = $LangTransl;
	endforeach;
	
	if($LangID == 'IND')
	{
		$h1_title		= 'Pemesanan';
		$h2_title		= 'Kendaraan';		
		$alert1			= 'Tanggal/waktu selesai harus lebih besar dari tanggal/waktu mulai.';
		$alert2			= 'Anda harus menentukan waktu mulai penggunaan kendaraan.';
		$alert3			= 'Anda harus menentukan waktu selesai penggunaan kendaraan.';
		$alert4			= 'Pilih salah satu kendaraan.';
		$alert5			= 'Tuliskan catatan peminjaman kendaraan.';
		$alert6			= 'Format waktu salah.';
		$alert7			= 'Tuliskan tujuannya.';
		$alert8			= 'Masukan jumlah peserta.';
		$alert9			= 'Tuliskan pengaju pemesanan.';
		$Submitter		= 'Pengaju';
	}
	else
	{
		$h1_title		= 'Reservation';	
		$h2_title		= 'Vehicle';
		$alert1			= 'End date/time must be greater than Start date/time.';
		$alert2			= 'You must specify the start time of using the vehicle.';
		$alert3			= 'You must specify the end time of using the vehicle.';
		$alert4			= 'Select a vehicle.';
		$alert5			= 'Write the reservation note of the vehicle.';
		$alert6			= 'Error time format.';
		$alert7			= 'Write the destination.';
		$alert8			= 'Input qty of participants.';
		$alert9			= 'Write submitter.';
		$Submitter		= 'Submitter';
	}
?>

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>
    <?php echo $h1_title; ?>
    <small><?php echo $h2_title; ?></small>
</h1>
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
                	<form class="form-horizontal" name="frm" id="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return chkForm()">
                    	<input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Code; ?> </label>
                          	<div class="col-sm-10">
                                <input type="text" class="form-control" style="max-width:150px" name="RSVCODE" id="RSVCODE" value="<?php echo $RSV_CODE; ?>" disabled/>
                                <input type="hidden" class="form-control" style="max-width:200px" name="RSV_CODE" id="RSV_CODE" value="<?php echo $RSV_CODE; ?>" />
                                <input type="hidden" class="form-control" style="max-width:200px" name="RSV_CATEG" id="RSV_CATEG" value="<?php echo $RSV_CATEG; ?>" />
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $StartDate; ?> </label>
                          	<div class="col-sm-10">
                            	<input type="text" name="RSV_STARTD" class="form-control pull-left" id="datepicker1" value="<?php echo $RSV_STARTD; ?>" style="width:107px" disabled ><label>&nbsp;&nbsp;</label><label><input type="text" class="form-control" style="max-width:80px" name="RSV_STARTT" id="RSV_STARTT" value="<?php echo $RSV_STARTT; ?>" disabled ></label>
							</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $EndDate; ?> </label>
                          	<div class="col-sm-10">
                            	<input type="text" name="RSV_ENDD" class="form-control pull-left" id="datepicker2" value="<?php echo $RSV_ENDD; ?>" style="width:107px" disabled><label>&nbsp;&nbsp;</label><label><input type="text" class="form-control" style="max-width:80px" name="RSV_ENDT" id="RSV_ENDT" value="<?php echo $RSV_ENDT; ?>" disabled ></label>
                          	</div>
                        </div>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $Vehicle; ?></label>
                            <div class="col-sm-10">
                                <select name="CATEG_CODEX" id="CATEG_CODEX" class="form-control" style="max-width:250px" onChange="cekVH_TYPE();">
                                    <option value="">--- None ---</option>
                                    <?php
										$CountCTG 	= $this->db->count_all('tbl_vehicle');
                                        $sqlCTG 	= "SELECT VH_CODE, VH_TYPE, VH_MEREK, VH_NOPOL, VH_STAT FROM tbl_vehicle";
                                        $resCTG		= $this->db->query($sqlCTG)->result();
                                        if($CountCTG > 0)
                                        {
                                            foreach($resCTG as $rowCTG) :
                                                $VH_CODE 	= $rowCTG->VH_CODE;
                                                //$VH_TYPE 	= $rowCTG->VH_TYPE;
                                                $VH_MEREK 	= $rowCTG->VH_MEREK;
                                                //$VH_NOPOL 	= $rowCTG->VH_NOPOL;
												$VH_STAT	= $rowCTG->VH_STAT;
												$isDisabled	= 0;
												$isDisDesc	= "";
												
												if($VH_STAT == 1)
												{
													$isDisabled	= 1;
													$isDisDesc	= " - (Used)";
												}
                                                ?>
                                                <option value="<?php echo $VH_CODE; ?>" <?php if($VH_CODE == $CATEG_CODE) { ?>selected <?php } if($isDisabled == 1) { ?> disabled <?php } ?>>
													<?php echo "$VH_MEREK$isDisDesc"; ?>
                                                </option>
                                                <?php
											endforeach;
										}
										else
										{
											?>
												<option value="">--- None ---</option>
											<?php
										}
                                    ?>
                                </select>
                            </div>
                        </div>
                        <input type="hidden" name="CATEG_CODE" id="CATEG_CODE" value="<?=$CATEG_CODE?>">
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $NOPOL; ?></label>
                            <div class="col-sm-10">
                                <input type="text" id="VH_NOPOL" name="VH_NOPOL" class="form-control" value="<?=$VH_NOPOL?>" style="max-width:250px" readonly>
                            </div>
                        </div>
                        <input type="hidden" name="DRIVER_CODE" id="DRIVER_CODE" value="<?=$DRIVER_CODEX?>">
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $Driver; ?></label>
                            <div class="col-sm-10">
                                <select name="DRIVER_CODEX" id="DRIVER_CODEX" class="form-control" style="max-width:250px">
                                    <option value="">--- None ---</option>
                                    <?php
                                        $CountDR 	= $this->db->count_all('tbl_driver');
                                        $sqlDR 	= "SELECT * FROM tbl_driver";
                                        $resDR		= $this->db->query($sqlDR)->result();
                                        if($CountDR > 0)
                                        {
                                            foreach($resDR as $rowDR) :
                                                $DRIVER_CODE 	= $rowDR->DRIVER_CODE;
                                                $DRIVER 		= $rowDR->DRIVER;
                                                $DRIVER_STAT	= $rowDR->DRIVER_STAT;
												$isDisabled	= 0;
												$isDisDesc	= "";
												
												if($DRIVER_STAT == 1)
												{
													$isDisabled	= 1;
													$isDisDesc	= " - $DRIVER_STAT";
												}
                                                ?>
                                                <option value="<?php echo $DRIVER_CODE; ?>"<?php if($DRIVER_CODE == $DRIVER_CODEX) { ?>selected <?php } if($isDisabled == 1) { ?> disabled <?php } ?>>
													<?php echo "$DRIVER$isDisDesc"; ?>
                                                </option>
                                                <?php
											endforeach;
										}
										else
										{
											?>
												<option value="">--- None ---</option>
											<?php
										}
                                    ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Destination; ?> </label>
                          	<div class="col-sm-10">
                                <input type="text" class="form-control" style="max-width:350px" name="RSV_TITLE" id="RSV_TITLE" value="<?php echo $RSV_TITLE; ?>" disabled />
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Notes; ?></label>
                          	<div class="col-sm-10">
                            	<textarea name="RSV_DESC" id="RSV_DESC" class="form-control" style="max-width:350px;" cols="30"><?php echo $RSV_DESC; ?></textarea>
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo "$Participants ($Qty)"; ?> </label>
                          	<div class="col-sm-10">
                                <input type="text" class="form-control" style="max-width:50px; text-align:right" name="RSV_QTY" id="RSV_QTY" value="<?php echo $RSV_QTY; ?>" disabled />
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Submitter; ?> </label>
                          	<div class="col-sm-10">
                                <input type="text" class="form-control" style="max-width:150px;" name="RSV_SUBMITTER" id="RSV_SUBMITTER" value="<?php echo $RSV_SUBMITTER; ?>" />
                          	</div>
                        </div>
                        <div class="form-group">
                            <label for="inputSkills" class="col-sm-2 control-label"><?php echo $Email ?> </label>
                            <div class="col-sm-10">
                            <input type="email" class="form-control" style="max-width:250px;" name="RSV_MAIL" id="RSV_MAIL" placeholder="Email" value="<?php echo "$RSV_MAIL"; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Notes; ?>&nbsp;Admin </label>
                          	<div class="col-sm-10">
                            	<textarea name="RSV_MEMO" id="RSV_MEMO" class="form-control" style="max-width:350px;" cols="30"><?php echo $RSV_MEMO; ?></textarea>
                          	</div>
                        </div>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $Status ?></label>
                            <div class="col-sm-10">
								<?php
                                	if($ISAPPROVE == 1)
                                    {
                                    ?>
                                        <select name="RSV_STAT" id="RSV_STAT" class="form-control" style="max-width:120px" onChange="selStat(this.value)" >
                                            <option value="1"<?php if($RSV_STAT == 1) { ?> selected <?php } ?>>New</option>
                                            <option value="2"<?php if($RSV_STAT == 2) { ?> selected <?php } ?>>Confirm</option>
                                            <option value="3"<?php if($RSV_STAT == 3) { ?> selected <?php } ?>>Approve</option>
                                            <option value="4"<?php if($RSV_STAT == 4) { ?> selected <?php } ?>>Reschedule</option>
                                            <option value="5"<?php if($RSV_STAT == 5) { ?> selected <?php } ?>>Reject</option>
                                            <option value="6"<?php if($RSV_STAT == 6) { ?> selected <?php } ?>>Close</option>
                                            <option value="8"<?php if($RSV_STAT == 8) { ?> selected <?php } ?>>In used</option>
                                        </select>
                                    <?php
                                    }
                                ?>
                            </div>
                        </div>
                        <script>
							function selStat(statValue)
							{
								if(statValue == 4)
								{
									document.getElementById('ReSech1').style.display = '';
									document.getElementById('ReSech2').style.display = '';
									document.getElementById('ReSech3').style.display = '';
									document.getElementById('ReSech4').style.display = '';
								}
								else
								{
									document.getElementById('ReSech1').style.display = 'none';
									document.getElementById('ReSech2').style.display = 'none';
									document.getElementById('ReSech3').style.display = 'none';
									document.getElementById('ReSech4').style.display = 'none';
								}
							}
						</script>
                        <div class="form-group" id="ReSech1" <?php if($RSV_STAT != 4) { ?> style="display:none" <?php } ?>>
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $StartDate; ?> </label>
                          	<div class="col-sm-10">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>&nbsp;</div>
                                    <input type="text" name="RSV_STARTD2" class="form-control pull-left" id="datepicker3" value="<?php echo $RSV_STARTD2; ?>" style="width:107px" onChange="changeStartD2(this.value)">
                                </div>
                          	</div>
                        </div>
                        <script>
							function changeStartD2(startDate)
							{
								document.getElementById('datepicker4').value = startDate;
							}
						</script>
                        <div class="form-group" id="ReSech2" <?php if($RSV_STAT != 4) { ?> style="display:none" <?php } ?>>
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Time ?> </label>
                          	<div class="col-sm-10">
                                <input type="text" class="form-control" style="max-width:80px" name="RSV_STARTT2" id="RSV_STARTT2" value="<?php echo $RSV_STARTT2; ?>" onKeyUp="toTimeString3(this.value)" onChange="chkLength3(this.value)" >
                          	</div>
                        </div>
                        <script>
							function chkLength3(dateLength)
							{
								var totTxt 	= dateLength.length;
								
								if(totTxt < 5)
								{
										alert('<?php echo $alert6; ?>');
										document.getElementById('RSV_STARTT2').value = '';
										document.getElementById('RSV_STARTT2').focus();
								}
							}
							
							function toTimeString3(RSV_STARTT)
							{
								var totTxt 	= RSV_STARTT.length;
								var noHour	= /^[0-2]+$/;
								var noMinut	= /^[0-5]+$/;
								if(totTxt == 1)
								{
									isHour	= document.getElementById('RSV_STARTT2').value;
									if(!isHour.match(noHour))
									{
										alert('Range no [00 - 23]');
										document.getElementById('RSV_STARTT2').value = '';
										document.getElementById('RSV_STARTT2').focus();
										return false;
									}
								}
								if(totTxt == 2)
								{
									isHour	= document.getElementById('RSV_STARTT2').value;
									if(isHour > 23)
									{
										alert('Hour must be less then 23:59');
										document.getElementById('RSV_STARTT2').value = '';
										document.getElementById('RSV_STARTT2').focus();
										return false;
									}
									else
									{
										document.getElementById('RSV_STARTT2').value = isHour+':';
										document.getElementById('RSV_STARTT2').focus();
									}
								}
								
								if(totTxt == 4)
								{
									isHour		= document.getElementById('RSV_STARTT2').value;
									isMinutes	= isHour.substr(3,4);
									if(!isMinutes.match(noMinut))
									{
										alert('Range no [00 - 59]');
										isHour	= isHour.substr(0,3);
										document.getElementById('RSV_STARTT2').value = isHour;
										document.getElementById('RSV_STARTT2').focus();
										return false;
									}									
								}
							}
						</script>
                        <div class="form-group" id="ReSech3" <?php if($RSV_STAT != 4) { ?> style="display:none" <?php } ?>>
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $EndDate; ?> </label>
                          	<div class="col-sm-10">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>&nbsp;</div>
                                    <input type="text" name="RSV_ENDD2" class="form-control pull-left" id="datepicker4" value="<?php echo $RSV_ENDD2; ?>" style="width:107px">
                                </div>
                          	</div>
                        </div>
                        <div class="form-group" id="ReSech4" <?php if($RSV_STAT != 4) { ?> style="display:none" <?php } ?>>
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Time ?> </label>
                          	<div class="col-sm-10">
                                <input type="text" class="form-control" style="max-width:80px" name="RSV_ENDT2" id="RSV_ENDT2" value="<?php echo $RSV_ENDT2; ?>" onKeyUp="toTimeString4(this.value);" onChange="chkLength4(this.value)" >
                          	</div>
                        </div>
                        <script>
							function chkLength4(dateLength)
							{
								var totTxt 	= dateLength.length;
								
								if(totTxt < 5)
								{
										alert('<?php echo $alert6; ?>');
										document.getElementById('RSV_ENDT2').value = '';
										document.getElementById('RSV_ENDT2').focus();
								}
							}
							
							function toTimeString4(RSV_ENDT)
							{
								var totTxt 	= RSV_ENDT.length;
								var noHour	= /^[0-2]+$/;
								var noMinut	= /^[0-5]+$/;
								if(totTxt == 1)
								{
									isHour	= document.getElementById('RSV_ENDT2').value;
									if(!isHour.match(noHour))
									{
										alert('Range no [00 - 23]');
										document.getElementById('RSV_ENDT2').value = '';
										document.getElementById('RSV_ENDT2').focus();
										return false;
									}									
								}
								if(totTxt == 2)
								{
									isHour	= document.getElementById('RSV_ENDT2').value;
									if(isHour > 24)
									{
										alert('Hour must be less then 23:59');
										document.getElementById('RSV_ENDT2').value = '';
										document.getElementById('RSV_ENDT2').focus();
										return false;
									}
									else
									{
										document.getElementById('RSV_ENDT2').value = isHour+':';
										document.getElementById('RSV_ENDT2').focus();
									}
								}
								
								if(totTxt == 4)
								{
									isHour		= document.getElementById('RSV_ENDT2').value;
									isMinutes	= isHour.substr(3,4);
									if(!isMinutes.match(noMinut))
									{
										alert('Range no [00 - 59]');
										isHour	= isHour.substr(0,3);
										document.getElementById('RSV_ENDT2').value = isHour;
										document.getElementById('RSV_ENDT2').focus();
										return false;
									}									
								}
								
								RSV_ENDT	= document.getElementById('RSV_ENDT2').value;
								//document.getElementById('AU_PROCT').value = RSV_ENDT;
							}
						</script>
                        <br>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                            <?php
							
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
									
							
								echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-mail-reply"></i>&nbsp;&nbsp;'.$Back.'</button>');

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
  
	function chkForm()
	{
		RSV_STARTD	= document.getElementById('datepicker3').value;
		RSV_ENDD	= document.getElementById('datepicker4').value;
		RSV_STARTT	= document.getElementById('RSV_STARTT2').value;
		RSV_ENDT	= document.getElementById('RSV_ENDT2').value;
		
		RSV_STARTT	= document.getElementById('RSV_STARTT2').value;
		if(RSV_STARTT == '00:00' || RSV_STARTT == '')
		{
			alert('<?php echo $alert2; ?>');
			document.getElementById('RSV_STARTT2').value = '';
			document.getElementById('RSV_STARTT2').focus();
			return false;
		}
		
		RSV_ENDT	= document.getElementById('RSV_ENDT2').value;
		if(RSV_ENDT == '00:00' || RSV_ENDT == '')
		{
			alert('<?php echo $alert3; ?>');
			document.getElementById('RSV_ENDT2').value = '';
			document.getElementById('RSV_ENDT2').focus();
			return false;
		}
		
		// CHECK DATE TIME
		fullTimeS	= new Date(RSV_STARTD+' '+RSV_STARTT);
		fullTimeE	= new Date(RSV_ENDD+' '+RSV_ENDT);	
		
		if(fullTimeS > fullTimeE)
		{
			alert('<?php echo $alert1; ?>');
			document.getElementById('datepicker4').focus();
			return false;
		}
		
		CATEG_CODE	= document.getElementById('CATEG_CODE').value;
		
		if(CATEG_CODE == '')
		{
			alert('<?php echo $alert4; ?>');
			document.getElementById('CATEG_CODE').focus();
			return false;
		}
		
		RSV_SUBMITTER	= document.getElementById('RSV_SUBMITTER').value;
		if(RSV_SUBMITTER == '')
		{
			alert('<?php echo $alert9; ?>');
			document.getElementById('RSV_SUBMITTER').value = '';
			document.getElementById('RSV_SUBMITTER').focus();
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
	
	function cekVH_TYPE()
	{
		var CATEG_CODE = document.getElementById('CATEG_CODEX').value;
		//alert(CATEG_CODE);
		//var data = CATEG_CODE;
		var xhttp = new XMLHttpRequest();
		try
		{
			xhttp = new XMLHttpRequest();
		}
		catch (e)
		{
			alert("Something is wrong");
			return false;
		}
		xhttp.onreadystatechange = function()
		{
			//alert("test");
			//alert(this.status);
			if(this.readyState == 4 && this.status == 200)
			{
				VH_NOPOLX = this.responseText;
				//alert(VH_NOPOLX);
				if(CATEG_CODE != "")
				{
					document.getElementById('VH_NOPOL').value = VH_NOPOLX;
					document.getElementById('CATEG_CODE').value = CATEG_CODE;
				}
				else
				{
					document.getElementById('VH_NOPOL').value = "";
					document.getElementById('CATEG_CODE').value = "";	
				}
				//alert("test");
				//TYPE = this.responseText;
				//alert(TYPE);
				//var TYPE_split = TYPE.split('~')
				//alert(TYPE_split[0]);
				//var x = document.getElementById('VH_MEREK').options[1].text;
				 
				
			}
		};
		xhttp.open("GET", "<?php echo base_url().'index.php/reservation/GET_VH/'; ?>" + CATEG_CODE, true);
		xhttp.send(null);
		
	}
	
</script>
<?php 
$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>