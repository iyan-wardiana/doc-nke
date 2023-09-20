<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 5 April 2017
 * File Name	= item_list_form.php
 * Location		= -
*/

/* 
 * Author		= Hendar Permana
 * Edit Date	= 29 September 2017
 * File Name	= c_fuel_usage_form.php
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

$DATEY 		= date('Y');
$DATEM 		= date('m');
$DATED 		= date('d');
$DATEHH		= date('H');
$DATEMM		= date('i');
$DATESS		= date('s');

$DATEF		= "$DATEM/$DATED/$DATEY";
$DATEN		= "FU$DATEY$DATEM$DATED-$DATEHH$DATEMM";

if($task == 'add')
{
	
	
	$FU_CODE		= $DATEN;
	$FU_DATE		= $DATEF;
	$FU_TIME1		= '00:00';
	$FU_TIME		= date('H:i',strtotime($FU_TIME1));
	$FU_PRJCODE		= $PRJCODE;
	$FU_ASSET		= '';	
	$FU_ASSET1		= '';
	$FU_QTY			= 0;
	$ITM_UNIT		= 'LTR';
	$ITM_VOLM		= 0;
	$FU_PRICE		= 0;
		$sqlFUEL	= "SELECT ITM_CODE, ITM_UNIT, ITM_VOLM, ITM_PRICE 
						FROM tbl_item
						WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = 'MTR00001' LIMIT 1";
		$resFUEL	= $this->db->query($sqlFUEL)->result();
		foreach($resFUEL as $rowF) :
			$ITM_CODE	= $rowF->ITM_CODE;
			$ITM_UNIT	= $rowF->ITM_UNIT;
			$ITM_VOLM	= $rowF->ITM_VOLM;
			$ITM_PRICE	= $rowF->ITM_PRICE;
		endforeach;
	//$FU_PRICE		= $ITM_PRICE;
		
	$FU_NOTE		= '';
	$FU_CREATER		= $DefEmp_ID;
	$FU_CREATED		= $DATEF;
	$FU_STAT		= 1;
	$varURL			= "$PRJCODE";
}
else
{
	$FU_CODE 		= $default['FU_CODE'];
	$FU_DATE		= $default['FU_DATE'];
	$FU_TIME		= $default['FU_TIME'];
	$FU_DATE		= date('m/d/Y',strtotime($FU_DATE));
	$FU_PRJCODE		= $default['FU_PRJCODE'];
	$FU_ASSET		= $default['FU_ASSET'];
	$FU_QTY 		= $default['FU_QTY'];
	$ITM_UNIT		= 'LTR';
	$FU_PRICE 		= $default['FU_PRICE'];
	$FU_NOTE 		= $default['FU_NOTE'];
	$FU_STAT 		= $default['FU_STAT'];
	$FU_CREATER		= $DefEmp_ID;
	$FU_CREATED		= $DATEF;
	$ITM_VOLM		= 0;
	$sqlFUEL	= "SELECT ITM_CODE, ITM_UNIT, ITM_VOLM, ITM_PRICE 
						FROM tbl_item
						WHERE PRJCODE = '$FU_PRJCODE' AND ITM_CODE = 'MTR00001' LIMIT 1";
		$resFUEL	= $this->db->query($sqlFUEL)->result();
		foreach($resFUEL as $rowF) :
			$ITM_CODE	= $rowF->ITM_CODE;
			$ITM_UNIT	= $rowF->ITM_UNIT;
			$ITM_VOLM	= $rowF->ITM_VOLM;
			$ITM_PRICE	= $rowF->ITM_PRICE;
		endforeach;
	
//	$varURL		= "$PRJCODE";

}

// Project List
$sqlPLC	= "tbl_project";
$resPLC	= $this->db->count_all($sqlPLC);

$sqlPL 	= "SELECT PRJCODE, PRJNAME
			FROM tbl_project WHERE PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
			ORDER BY PRJNAME";
$resPL	= $this->db->query($sqlPL)->result();
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
		if($TranslCode == 'Date')$Date = $LangTransl;
		if($TranslCode == 'Project')$Project = $LangTransl;
		if($TranslCode == 'Asset')$Asset = $LangTransl;
		if($TranslCode == 'Quantity')$Quantity = $LangTransl;
		if($TranslCode == 'Stock')$Stock = $LangTransl;
		if($TranslCode == 'Notes')$Notes = $LangTransl;

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
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body chart-responsive">
                	<form name="frmsrch" id="frmsrch" action="" method=POST style="display:none">
                        <input type="hidden" name="" id="" class="textbox" value="" />
                        <input type="hidden" class="button_css" name="submitSrch" id="submitSrch" value=" search " />
                    </form>
                	<form class="form-horizontal" name="frm" id="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return saveRecomend()">
                    	<input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Code ?></label>
                          	<div class="col-sm-10">
                                <input type="text" class="form-control" style="max-width:200px" name="FU_CODE" id="FU_CODE" value="<?php echo $FU_CODE; ?> " disabled/>
                                <input type="hidden" class="form-control" style="max-width:200px" name="FU_CODE" id="FU_CODE" value="<?php echo $FU_CODE; ?> " />
                                <input type="hidden" class="textbox" name="FU_CREATER" id="FU_CREATER" size="3" value="<?php echo $FU_CREATER; ?>" />
                                <input type="hidden" class="textbox" name="FU_CREATED" id="FU_CREATED" size="5" value="<?php echo $FU_CREATED; ?>" />
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Date ?></label>
                          	<div class="col-sm-10">
                            	<div class="input-group date">
                                    <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
                                    <input type="text" name="FU_DATE" class="form-control pull-left" id="datepicker1" value="<?php echo $FU_DATE; ?>" style="width:150px">
                                </div>
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Time</label>
                          	<div class="col-sm-10">
                                <input type="text" class="form-control" style="max-width:80px" name="FU_TIME" id="FU_TIME" value="<?php echo $FU_TIME; ?>" onKeyUp="toTimeString1(this.value)" >
                          	</div>
                        </div>
                        <script>
							function toTimeString1(FU_TIME)
							{
								var totTxt 	= FU_TIME.length;
								var noHour	= /^[0-2]+$/;
								var noMinut	= /^[0-5]+$/;
								if(totTxt == 1)
								{
									isHour	= document.getElementById('FU_TIME').value;
									if(!isHour.match(noHour))
									{
										alert('Range no [0 - 2]');
										document.getElementById('FU_TIME').value = 0;
										document.getElementById('FU_TIME').focus();
										return false;
									}
								}
								if(totTxt == 2)
								{
									isHour	= document.getElementById('FU_TIME').value;
									if(isHour > 24)
									{
										alert('Hour must be less then 24');
										document.getElementById('FU_TIME').value = '';
										document.getElementById('FU_TIME').focus();
										return false;
									}
									else
									{
										document.getElementById('FU_TIME').value = isHour+':';
										document.getElementById('FU_TIME').focus();
									}
								}
								
								if(totTxt == 4)
								{
									isHour		= document.getElementById('FU_TIME').value;
									isMinutes	= isHour.substr(3,4);
									if(!isMinutes.match(noMinut))
									{
										alert('Range no [0 - 5]');
										isHour	= isHour.substr(0,3);
										document.getElementById('FU_TIME').value = isHour;
										document.getElementById('FU_TIME').focus();
										return false;
									}									
								}
							}
						</script>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Project ?></label>
                          	<div class="col-sm-10">
                                <input type="text" class="form-control" style="max-width:200px" name="FU_PRJCODE" id="FU_PRJCODE" value="<?php echo $FU_PRJCODE;?>" disabled/>
                                <input type="hidden" class="form-control" style="max-width:200px" name="FU_PRJCODE" id="FU_PRJCODE" value="<?php echo $FU_PRJCODE;?>"/>
                          	</div>
                        </div>
                      <!--  <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Asset</label>
                          	<div class="col-sm-10">
                             <input type="text" class="form-control" style="max-width:200px" name="FU_ASSET" id="FU_ASSET" value="<?php //echo $FU_ASSET;?>" title="Input Asset"/>
                               
                                <select name="FU_TYPE" id="FU_TYPE" class="form-control" style="max-width:150px">
                               			<option value=""> --- wip type ---</option>
                                		<option value="" <?php //if($FU_TYPE == 'x') { ?> selected <?php //} ?> style="display:none" >FU Versi Proyek</option>
                                 		<option value="1" <?php //if($FU_TYPE == 1) { ?> selected <?php //} ?> style="display:none" >Material</option>
                               			<option value="2" <?php //if($FU_TYPE == 2) { ?> selected <?php //} ?> style="display:none" >Upah</option>
                               			<option value="3" <?php //if($FU_TYPE == 3) { ?> selected <?php //} ?> >Subkon</option>
                               			<option value="4" <?php //if($FU_TYPE == 4) { ?> selected <?php //} ?> >Alat</option>
                                </select>
                            </div>
                            
                        </div>-->
                        <?php
                        	$sqlA 	= "SELECT AS_NAME FROM tbl_asset_list WHERE AS_CODE='$FU_ASSET'";
							$resA	= $this->db->query($sqlA)->result();
								foreach($resA as $rowA) :
        							$FU_ASSET1 	= $rowA->AS_NAME;
								endforeach
						?>			
                         <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Asset ?></label>
                          	<div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-btn">
										<button type="button" class="btn btn-primary">Seacrh</button>
                                    </div>
                                    <input type="text" class="form-control" name="FU_ASSET1" id="FU_ASSET1" style="max-width:160px" value="<?php echo $FU_ASSET1; ?>" onClick="pleaseCheck();">
                                    <input type="hidden" class="form-control" name="FU_ASSET" id="FU_ASSET" style="max-width:160px" value="<?php echo $FU_ASSET; ?>">
                                </div>
                            </div>
                        </div>
                        <?php
							//$url_selAURCODE	= site_url('c_asset/c_asset_usage/popupallaur/?id='.$this->url_encryption_helper->encode_url($AU_PRJCODE));
							$url_selAURCODE		= site_url('c_asset/c_fuel_usage/popupallasset/?id='.$this->url_encryption_helper->encode_url($FU_PRJCODE));
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
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo "$Quantity ($ITM_UNIT)"; ?></label>
                          	<div class="col-sm-10">
                            	<input type="text" class="form-control" style="text-align:right; max-width:130px" name="FU_QTY1" id="FU_QTY1" size="10" value="<?php  echo number_format($FU_QTY, $decFormat);?>" onBlur="changeQTY(this)" />
                    			<input type="hidden" class="textbox" style="text-align:right" name="FU_QTY" id="FU_QTY" size="10" value="<?php  echo $FU_QTY;?>" />
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo "$Stock ($ITM_UNIT)"; ?></label>
                          	<div class="col-sm-10">
                            	<input type="text" class="form-control" style="text-align:right; max-width:130px" name="ITM_VOLM" id="ITM_VOLM" size="10" value="<?php  echo number_format($ITM_VOLM, $decFormat);?>" disabled />
                          	</div>
                        </div>
                       <div class="form-group" style="display:none">
                          	<label for="inputName" class="col-sm-2 control-label">Price</label>
                          	<div class="col-sm-10">
                            	<input type="text" class="form-control" style="text-align:right; max-width:130px" name="FU_PRICE1" id="FU_PRICE1" size="10" value="<?php  echo number_format($FU_PRICE, $decFormat);?>" onBlur="changePRICE(this)" />
                    			<input type="hidden" class="textbox" style="text-align:right" name="FU_PRICE" id="FU_PRICE" size="10" value="<?php // echo $FU_PRICE;?>" />
                          	</div>
                        </div>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $Notes ?></label>
                            <div class="col-sm-10">
                                <textarea name="FU_NOTE" class="form-control" id="FU_NOTE" style="height:100px; width:300px" ><?php echo $FU_NOTE; ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label">Status</label>
                            <div class="col-sm-10">
                                <select name="FU_STAT" id="FU_STAT" class="form-control" style="max-width:100px">
                                    <option value="1" <?php if($FU_STAT == 1) { ?> selected <?php } ?>>New</option>
                                    <option value="2" <?php if($FU_STAT == 2) { ?> selected <?php } ?>>Confirm</option>
                                    <?php
									if($ISAPPROVE == 1)
									{
									?>
                                    	<option value="3" <?php if($FU_STAT == 3) { ?> selected <?php } ?>>Approve</option>
                                    <?php
									}
									?>
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
	function add_header(strItem) 
	{
		
		arrItem = strItem.split('|');
		//ilvl = arrItem[1];
		
		ASSET_CODE		= arrItem[0];
		ASSET_NAME		= arrItem[1];
		//alert (FU_ASSET)
		document.getElementById("FU_ASSET").value = ASSET_CODE;
		document.getElementById("FU_ASSET1").value = ASSET_NAME;
		//document.frmsrch1.submitSrch1.click();
	}
	
	function changeQTY(thisValue)
	{
		var decFormat								= document.getElementById('decFormat').value;
		var FU_QTY									= parseFloat(eval(thisValue).value.split(",").join(""));
		var ITM_VOLM								= parseFloat(eval(document.getElementById('ITM_VOLM')).value.split(",").join(""));
		var ITM_VOLMV								= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_VOLM)),decFormat));
		if(FU_QTY > ITM_VOLM)
		{
			alert('Qty can not greater than '+ITM_VOLMV);
			document.getElementById('FU_QTY').value		= ITM_VOLM;
			document.getElementById('FU_QTY1').value	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_VOLM)),decFormat));
			return false;
		}		
		
		document.getElementById('FU_QTY').value 	= FU_QTY;
		document.getElementById('FU_QTY1').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(FU_QTY)),decFormat));
	}
	
	function changePRICE(thisValue)
	{
		var decFormat								= document.getElementById('decFormat').value;
		var FU_PRICE								= eval(thisValue).value.split(",").join("");
		document.getElementById('FU_PRICE').value 	= FU_PRICE;	
		document.getElementById('FU_PRICE1').value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(FU_PRICE)),decFormat));	
	}
	
	function chooseCategory(catType)
	{
		document.getElementById('ITM_CATEGx').value = catType;
		document.frmsrch.submitSrch.click();
	}
	
	function saveRecomend()
	{
		FU_TYPE 	= document.getElementById("FU_TYPE").value;
		if(FU_TYPE == '')
		{
			alert('Please select a FU Type');
			document.getElementById("FU_TYPE").focus();
			return false;
		}
		
		FU_VALUE 	= document.getElementById("FU_VALUE").value;
		if(FU_VALUE == 0)
		{
			alert('Please input FU value');
			document.getElementById("FU_VALUE1").focus();
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