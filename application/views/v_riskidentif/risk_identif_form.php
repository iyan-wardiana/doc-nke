<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 13 April 2017
 * File Name	= risk_identif_form.php
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
$decFormat		= 2;
	
if($task == 'add')
{
	foreach($viewDocPattern as $row) :
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
	
	$sql = "SELECT MAX(Patt_Number) as maxNumber FROM tbl_riskidentif";
	$result = $this->db->query($sql)->result();
	
	foreach($result as $row) :
		$myMax = $row->maxNumber;
		$myMax = $myMax+1;
	endforeach;	
		
	$lastPatternNumb = $myMax;
	$lastPatternNumb1 = $myMax;
	$len = strlen($lastPatternNumb);
	$nol = '';
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
	$DocNumber = "$Pattern_Code$lastPatternNumb";
	
	$RID_CODE			= $DocNumber;
	$RID_PRJCODE		= 'KTR';
	$RID_DIVDEP			= '';
	$RID_RCATCODE		= '';
	$RID_CAUSE			= '';
	$RID_RISK			= '';
	$RID_IMPACT			= '';
	$RID_POLICY			= '';
	$Patt_Number		= $myMax;
	
	$DefEmp_ID	= $this->session->userdata['Emp_ID'];
}
else
{
	$RID_CODE 		= $default['RID_CODE'];
	$RID_PRJCODE	= $default['RID_PRJCODE'];
	$RID_DIVDEP		= $default['RID_DIVDEP'];
	$RID_RCATCODE	= $default['RID_RCATCODE'];
	$RID_CAUSE		= $default['RID_CAUSE'];
	$RID_RISK 		= $default['RID_RISK'];		
	$RID_IMPACT 	= $default['RID_IMPACT'];	
	$RID_POLICY 	= $default['RID_POLICY'];
	$EMP_ID			= $default['EMP_ID'];
	$Patt_Number	= $default['Patt_Number'];
	$DefEmp_ID		= $EMP_ID;
}

// Project List
$sqlPLC		= "tbl_project";
$resPLC		= $this->db->count_all($sqlPLC);

$sqlPL 		= "SELECT PRJCODE, PRJNAME
				FROM tbl_project WHERE PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
				ORDER BY PRJNAME";
$resPL		= $this->db->query($sqlPL)->result();

$sqlRC 		= "SELECT RCAT_CODE, RCAT_DESC
				FROM tbl_riskcategory
				ORDER BY RCAT_DESC";
$resRC		= $this->db->query($sqlRC)->result();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/style.css'; ?>");</style>
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
	$ISDWONL 	= $this->session->userdata['ISDWONL'];$LangID 	= $this->session->userdata['LangID'];

	$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
	$resTransl		= $this->db->query($sqlTransl)->result();
	foreach($resTransl as $rowTransl) :
		$TranslCode	= $rowTransl->MLANG_CODE;
		$LangTransl	= $rowTransl->LangTransl;
		
		if($TranslCode == 'Add')$Add = $LangTransl;
		if($TranslCode == 'Edit')$Edit = $LangTransl;
		if($TranslCode == 'Save')$Save = $LangTransl;
		if($TranslCode == 'Back')$Back = $LangTransl;
		if($TranslCode == 'Code')$Code = $LangTransl;
		if($TranslCode == 'Project')$Project = $LangTransl;
		if($TranslCode == 'Category')$Category = $LangTransl;
		if($TranslCode == 'CauseofRisk')$CauseofRisk = $LangTransl;
		if($TranslCode == 'RiskDescription')$RiskDescription = $LangTransl;
		if($TranslCode == 'Impact')$Impact = $LangTransl;
		if($TranslCode == 'RiskPolicy')$RiskPolicy = $LangTransl;
		
		if($TranslCode == 'Description')$Description = $LangTransl;
		if($TranslCode == 'Frequency')$Frequency = $LangTransl;
		if($TranslCode == 'Score')$Score = $LangTransl;
		if($TranslCode == 'Rate')$Rate = $LangTransl;

	endforeach;
?>

<body class="hold-transition skin-blue sidebar-mini" <?php if($task == 'add') { ?> onLoad="functioncheck()" <?php } ?>>
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
                	<form class="form-horizontal" name="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return checkInp()">
                        <input type="hidden" name="Patt_Number" id="Patt_Number" value="<?php echo $Patt_Number; ?>" />
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Code ?> </label>
                       	  <div class="col-sm-10">
                                <input type="text" name="RID_CODE1" id="RID_CODE1" class="form-control" style="max-width:150px" value="<?php echo $RID_CODE; ?>" onChange="functioncheck(this.value)" <?php if($task == 'edit') { ?> disabled <?php } ?>/>
                                <input type="hidden" name="RID_CODE" id="RID_CODE" value="<?php echo $RID_CODE; ?>" />
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Project ?> </label>
                       	  <div class="col-sm-10">
                                <select name="RID_PRJCODE" id="RID_PRJCODE" class="form-control" style="max-width:300px" onChange="chooseProject(this.value)">
                                  <?php
                                        if($resPLC > 0)
                                        {
                                            foreach($resPL as $rowPL) :
                                                $PRJCODE = $rowPL->PRJCODE;
                                                $PRJNAME = $rowPL->PRJNAME;
                                                ?>
                                  				<option value="<?php echo $PRJCODE; ?>" <?php if($PRJCODE == $RID_PRJCODE) { ?> selected <?php } ?>><?php echo "$PRJCODE - $PRJNAME"; ?></option>
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
                          	</div>
                        </div>
                        <script>
							function chooseProject(thisVal)
							{
								if(thisVal == 'KTR')
								{
									document.getElementById('div-dep').style.display = '';
									document.getElementById('data11RIDD_PRJCODE1').value = 'KTR';
									document.getElementById('data12RIDD_PRJCODE2').value = 'KTR';
									document.getElementById('data13RIDD_PRJCODE3').value = 'KTR';
								}
								else
								{
									document.getElementById('div-dep').style.display = 'none';
									document.getElementById('data11RIDD_PRJCODE1').value = thisVal;
									document.getElementById('data12RIDD_PRJCODE2').value = thisVal;
									document.getElementById('data13RIDD_PRJCODE3').value = thisVal;
								}
							}
						</script>
                        <div class="form-group" id="div-dep" <?php if($RID_PRJCODE != 'KTR') { ?> style="display:none" <?php } ?>>
                          	<label for="inputName" class="col-sm-2 control-label">Div. / Department</label>
                          	<div class="col-sm-10">
                            	<input type="text" class="form-control" style="max-width:250px" name="RID_DIVDEP" id="RID_DIVDEP" value="<?php echo $RID_DIVDEP; ?>" />
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Category ?> </label>
                       	  <div class="col-sm-10">
                                <select name="RID_RCATCODE" id="RID_RCATCODE" class="form-control" style="max-width:300px">
                                  	<?php
										foreach($resRC as $rowPL) :
											$RCAT_CODE = $rowPL->RCAT_CODE;
											$RCAT_DESC = $rowPL->RCAT_DESC;
											?>
											<option value="<?php echo $RCAT_CODE; ?>" <?php if($RCAT_CODE == $RID_RCATCODE) { ?> selected <?php } ?>><?php echo "$RCAT_DESC"; ?></option>
							  				<?php
										endforeach;
                               		?>
                                </select>
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $CauseofRisk ?> </label>
                          	<div class="col-sm-10">
                                <input type="text" class="form-control" style="max-width:350px" name="RID_CAUSE" id="RID_CAUSE" value="<?php echo $RID_CAUSE; ?>" />
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $RiskDescription ?> </label>
                          	<div class="col-sm-10">
                                <div class="box box-widget" style="max-width:100%">
                                    <div class="box-header with-border">
                                        <div class="user-block">&nbsp;</div>
                                        <div class="box-tools">
                                            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                                <i class="fa fa-minus"></i>
                                            </button>
                                            <button type="button" class="btn btn-box-tool" data-widget="remove">
                                                <i class="fa fa-times"></i>
                                            </button>&nbsp;&nbsp;&nbsp;&nbsp;
                                            <button type="button" class="btn btn-box-tool" data-toggle="tooltip" title="Add Risk">
                                                <i class="fa fa-plus-square" onClick="add_row1()"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="box-body">
                                        <input type="hidden" name="rowCount1" id="rowCount1" value="0">
                                        <table width="100%" border="0" id="tbl1">
											<tr>
                                                <td width="75%" nowrap><?php echo $Description ?> </td>
                                              	<td width="11%" nowrap style="text-align:center"><?php echo $Frequency ?> </td>
                       						  <td width="14%" nowrap style="text-align:center"><?php echo $Score ?> </td>
                                       	  </tr>
                                            <?php
                                                if($task == "edit")
                                                {
													$sqlDET1	= "SELECT * FROM tbl_riskdescdet
																	WHERE RIDD_CODE1 = '$RID_CODE'
																	AND RIDD_PRJCODE1 = '$RID_PRJCODE'
																	AND RIDD_GROUP1 = 1";
													$resDET1 	= $this->db->query($sqlDET1)->result();
													$i1			= 0;
													foreach($resDET1 as $row1) :
														$currentRow  	= ++$i1;
														$RIDD_CODE1 	= $row1->RIDD_CODE1;
														$RIDD_PRJCODE1 	= $RID_PRJCODE;
														$RIDD_GROUP1 	= $row1->RIDD_GROUP1;
														$RIDD_DESC1 	= $row1->RIDD_DESC1;
														$RIDD_LEVEL1 	= $row1->RIDD_LEVEL1;
														$RIDD_TYPE1 	= $row1->RIDD_TYPE1;
														$RIDD_STAT1 	= $row1->RIDD_STAT1;
														$EMP_ID1 		= $row1->EMP_ID1;
                                                    ?>
                                        				<input type="hidden" name="rowCount1" id="rowCount1" value="0">
                                                        <tr>
                                                            <td width="75%" nowrap>
                                        <input type="hidden" class="form-control" style="max-width:250px" name="data1[<?php echo $currentRow; ?>][RIDD_CODE1]" id="data1<?php echo $currentRow; ?>RIDD_CODE1" value="<?php echo $RIDD_CODE1; ?>" />
                                                                <input type="hidden" class="form-control" style="max-width:250px" name="data1[<?php echo $currentRow; ?>][RIDD_PRJCODE1]" id="data1<?php echo $currentRow; ?>RIDD_PRJCODE1" value="<?php echo $RIDD_PRJCODE1; ?>" />
                                                                <input type="hidden" class="form-control" style="max-width:250px" name="data1[<?php echo $currentRow; ?>][RIDD_GROUP1]" id="data1<?php echo $currentRow; ?>RIDD_GROUP1" value="1" />
                                                                <input type="text" class="form-control" style="max-width:650px" name="data1[<?php echo $currentRow; ?>][RIDD_DESC1]" id="data1<?php echo $currentRow; ?>RIDD_DESC1" value="<?php echo $RIDD_DESC1; ?>" />
                                                          <input type="hidden" class="form-control" style="max-width:70px" name="data1[<?php echo $currentRow; ?>][EMP_ID1]" id="data1<?php echo $currentRow; ?>EMP_ID1" value="<?php echo $EMP_ID1; ?>" />                                                             </td>
                                                      <td width="11%" nowrap style="text-align:center">
<select name="data1[<?php echo $currentRow; ?>][RIDD_TYPE1]" id="data1<?php echo $currentRow; ?>RIDD_TYPE1" class="form-control" style="width:140px">
                                                                    <option value="1" <?php if($RIDD_TYPE1 == 1) { ?> selected <?php } ?>>Jarang Sekali</option>
                                                                    <option value="2" <?php if($RIDD_TYPE1 == 2) { ?> selected <?php } ?>>Jarang</option>
                                                                    <option value="3" <?php if($RIDD_TYPE1 == 3) { ?> selected <?php } ?>>Kadang</option>
                                                                    <option value="4" <?php if($RIDD_TYPE1 == 4) { ?> selected <?php } ?>>Sering</option>
                                                                    <option value="5" <?php if($RIDD_TYPE1 == 5) { ?> selected <?php } ?>>Sering Sekali</option>
                                                        </select></td>
                                                      <td width="14%" nowrap style="text-align:left">
<input type="text" class="form-control" style="max-width:200px; text-align:right" name="data1[<?php echo $currentRow; ?>][RIDD_LEVEL1]" id="data1<?php echo $currentRow; ?>RIDD_LEVEL1" value="<?php echo $RIDD_LEVEL1; ?>" onKeyPress="return isIntOnlyNew(event);" />                                                           
<select name="data1[<?php echo $currentRow; ?>][RIDD_STAT1]" id="data1<?php echo $currentRow; ?>RIDD_STAT1" class="form-control" style="width:100px; display:none">
                                                                    <option value="1" <?php if($RIDD_STAT1 == 1) { ?> selected <?php } ?>>Enable</option>
                                                                    <option value="0" <?php if($RIDD_STAT1 == 0) { ?> selected <?php } ?>>Disable</option>
                                                                </select>
                                                            </td>
                                          </tr>
                                                    <?php
													endforeach;
                                                }
                                            ?>
                                        </table>
                                  </div>
                                </div>
                            </div>
                      	</div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Impact ?> </label>
                          	<div class="col-sm-10">
                                <div class="box box-widget" style="max-width:100%">
                                    <div class="box-header with-border">
                                        <div class="user-block">&nbsp;</div>
                                        <div class="box-tools">
                                            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                                <i class="fa fa-minus"></i>
                                            </button>
                                            <button type="button" class="btn btn-box-tool" data-widget="remove">
                                                <i class="fa fa-times"></i>
                                            </button>&nbsp;&nbsp;&nbsp;&nbsp;
                                            <button type="button" class="btn btn-box-tool" data-toggle="tooltip" title="Add Impact">
                                                <i class="fa fa-plus-square" onClick="add_row2()"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="box-body">
                                        <input type="hidden" name="rowCount2" id="rowCount2" value="0">
                                        <table width="100%" border="0" id="tbl2">
                                            <tr>
                                                <td width="75%" nowrap><?php echo $Description ?> </td>
                                              <td width="11%" nowrap style="text-align:center"><?php echo $Frequency ?> </td>
                                              <td width="14%" nowrap style="text-align:center"><?php echo $Rate ?> </td>
                                          </tr>
                                            <?php
                                                if($task == "edit")
                                                {
													$sqlDET2	= "SELECT * FROM tbl_riskimpactdet
																	WHERE RIDD_CODE2 = '$RID_CODE'
																	AND RIDD_PRJCODE2 = '$RID_PRJCODE'
																	AND RIDD_GROUP2 = 2";
													$resDET2 	= $this->db->query($sqlDET2)->result();
													$i2			= 0;
													foreach($resDET2 as $row2) :
														$currentRow  	= ++$i2;
														$RIDD_CODE2 	= $row2->RIDD_CODE2;
														$RIDD_IMPNO2 	= $row2->RIDD_IMPNO2;
														$RIDD_PRJCODE2 	= $RID_PRJCODE;
														$RIDD_GROUP2 	= $row2->RIDD_GROUP2;
														$RIDD_DESC2 	= $row2->RIDD_DESC2;
														$RIDD_LEVEL2 	= $row2->RIDD_LEVEL2;
														$RIDD_TYPE2 	= $row2->RIDD_TYPE2;
														$RIDD_STAT2 	= $row2->RIDD_STAT2;
														$EMP_ID2 		= $row2->EMP_ID2;
                                                    ?>
                                                        <tr>
                                                            <td width="75%" nowrap>
                                                        <input type="hidden" class="form-control" style="max-width:250px" name="data2[<?php echo $currentRow; ?>][RIDD_CODE2]" id="data2<?php echo $currentRow; ?>RIDD_CODE2" value="<?php echo $RIDD_CODE2; ?>" />
                                                                <input type="hidden" class="form-control" style="max-width:250px" name="data2[<?php echo $currentRow; ?>][RIDD_PRJCODE2]" id="data2<?php echo $currentRow; ?>RIDD_PRJCODE2" value="<?php echo $RIDD_PRJCODE2; ?>" />
                                                                <input type="hidden" class="form-control" style="max-width:250px" name="data2[<?php echo $currentRow; ?>][RIDD_GROUP2]" id="data2<?php echo $currentRow; ?>RIDD_GROUP2" value="2" />
                                                                <input type="hidden" class="form-control" style="max-width:100px" name="data3[<?php echo $currentRow; ?>][RIDD_IMPNO2]" id="data3<?php echo $currentRow; ?>RIDD_IMPNO2" value="<?php echo $RIDD_IMPNO2; ?>" />
                                                                <input type="text" class="form-control" style="max-width:650px" name="data2[<?php echo $currentRow; ?>][RIDD_DESC2]" id="data2<?php echo $currentRow; ?>RIDD_DESC2" value="<?php echo $RIDD_DESC2; ?>" />
                                                                <input type="hidden" class="form-control" style="max-width:70px" name="data2[<?php echo $currentRow; ?>][EMP_ID2]" id="data2<?php echo $currentRow; ?>EMP_ID2" value="<?php echo $EMP_ID2; ?>" /></td>
                                                      <td width="11%" nowrap style="text-align:center">
<select name="data2[<?php echo $currentRow; ?>][RIDD_TYPE2]" id="data2<?php echo $currentRow; ?>RIDD_TYPE2" class="form-control" style="width:140px">
                                                                    <option value="1" <?php if($RIDD_TYPE2 == 1) { ?> selected <?php } ?>>Ringan</option>
                                                                    <option value="2" <?php if($RIDD_TYPE2 == 2) { ?> selected <?php } ?>>Sedang</option>
                                                                    <option value="3" <?php if($RIDD_TYPE2 == 3) { ?> selected <?php } ?>>Agak Berat</option>
                                                                    <option value="4" <?php if($RIDD_TYPE2 == 4) { ?> selected <?php } ?>>Berat</option>
                                                                    <option value="5" <?php if($RIDD_TYPE2 == 5) { ?> selected <?php } ?>>Ekstreme</option>
                                                                </select> </td>
                                                      <td width="14%" nowrap style="text-align:left">
                                        <input type="text" class="form-control" style="max-width:200px; text-align:right" name="data2[<?php echo $currentRow; ?>][RIDD_LEVEL2]" id="data2<?php echo $currentRow; ?>RIDD_LEVEL2" value="<?php echo $RIDD_LEVEL2; ?>" onKeyPress="return isIntOnlyNew(event);" />
                                                                <select name="data2[<?php echo $currentRow; ?>][RIDD_STAT2]" id="data2<?php echo $currentRow; ?>RIDD_STAT2" class="form-control" style="max-width:200px; display:none">
                                                                    <option value="1" <?php if($RIDD_STAT2 == 1) { ?> selected <?php } ?>>Enable</option>
                                                                    <option value="0" <?php if($RIDD_STAT2 == 0) { ?> selected <?php } ?>>Disable</option>
                                                                </select> 
                                                            </td>
                                          </tr>
                                                    <?php
													endforeach;
                                                }
                                            ?>
                                        </table>
                                    </div>
                                </div>
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $RiskPolicy ?> </label>
                          	<div class="col-sm-10">
                                <div class="box box-widget" style="max-width:100%">
                                    <div class="box-header with-border">
                                        <div class="user-block">&nbsp;</div>
                                        <div class="box-tools">
                                            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                                <i class="fa fa-minus"></i>
                                            </button>
                                            <button type="button" class="btn btn-box-tool" data-widget="remove">
                                                <i class="fa fa-times"></i>
                                            </button>&nbsp;&nbsp;&nbsp;&nbsp;
                                            <button type="button" class="btn btn-box-tool" data-toggle="tooltip" title="Add Risk Policy" style="display:none">
                                                <i class="fa fa-plus-square" onClick="add_row3()"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="box-body">
                                        <input type="hidden" name="rowCount3" id="rowCount3" value="0">
                                        <table width="100%" border="0" id="tbl3">
                                            <tr>
                                                <td width="75%" nowrap><?php echo $Description ?> </td>
                                              <td width="11%" nowrap style="text-align:center"><?php echo $Frequency ?> </td>
                                              <td width="14%" nowrap style="text-align:center"><?php echo $Rate ?> </td>
                                          </tr>
                                            <?php
                                                if($task == "edit")
                                                {
													$sqlDET3	= "SELECT * FROM tbl_riskpolicydet
																	WHERE RIDD_CODE3 = '$RID_CODE'
																	AND RIDD_PRJCODE3 = '$RID_PRJCODE'
																	AND RIDD_GROUP3 = 3";
													$resDET3 	= $this->db->query($sqlDET3)->result();
													$i3			= 0;
													foreach($resDET3 as $row3) :
														$currentRow  	= ++$i3;
														$RIDD_CODE3 	= $row3->RIDD_CODE3;
														$RIDD_IMPNO3 	= $row3->RIDD_IMPNO3;
														$RIDD_PRJCODE3 	= $RID_PRJCODE;
														$RIDD_GROUP3 	= $row3->RIDD_GROUP3;
														$RIDD_DESC3 	= $row3->RIDD_DESC3;
														$RIDD_LEVEL3 	= $row3->RIDD_LEVEL3;
														$RIDD_TYPE3 	= $row3->RIDD_TYPE3;
														$RIDD_STAT3 	= $row3->RIDD_STAT3;
														$EMP_ID3 		= $row3->EMP_ID3;
                                                    ?>
                                                        <tr>
                                                            <td width="75%" nowrap>
                                                        <input type="hidden" class="form-control" style="max-width:250px" name="data3[<?php echo $currentRow; ?>][RIDD_CODE3]" id="data3<?php echo $currentRow; ?>RIDD_CODE3" value="<?php echo $RIDD_CODE3; ?>" />
                                                                <input type="hidden" class="form-control" style="max-width:250px" name="data3[<?php echo $currentRow; ?>][RIDD_PRJCODE3]" id="data3<?php echo $currentRow; ?>RIDD_PRJCODE3" value="<?php echo $RIDD_PRJCODE3; ?>" />
                                                                <input type="hidden" class="form-control" style="max-width:250px" name="data3[<?php echo $currentRow; ?>][RIDD_GROUP3]" id="data3<?php echo $currentRow; ?>RIDD_GROUP3" value="3" />
                                                                <input type="hidden" class="form-control" style="max-width:100px" name="data3[<?php echo $currentRow; ?>][RIDD_IMPNO3]" id="data3<?php echo $currentRow; ?>RIDD_IMPNO3" value="<?php echo $RIDD_IMPNO3; ?>" />
                                                                <input type="text" class="form-control" style="max-width:650px" name="data3[<?php echo $currentRow; ?>][RIDD_DESC3]" id="data3<?php echo $currentRow; ?>RIDD_DESC3" value="<?php echo $RIDD_DESC3; ?>" />
                                                                <input type="hidden" class="form-control" style="max-width:70px" name="data3[<?php echo $currentRow; ?>][EMP_ID3]" id="data3<?php echo $currentRow; ?>EMP_ID3" value="<?php echo $EMP_ID3; ?>" />                                                            </td>
                                                      <td width="11%" nowrap style="text-align:center">  
<select name="data3[<?php echo $currentRow; ?>][RIDD_TYPE3]" id="data3<?php echo $currentRow; ?>RIDD_TYPE3" class="form-control" style="width:140px">
                                                                    <option value="1" <?php if($RIDD_TYPE3 == 1) { ?> selected <?php } ?>>Kadang</option>
                                                                    <option value="2" <?php if($RIDD_TYPE3 == 2) { ?> selected <?php } ?>>Jarang</option>
                                                                    <option value="3" <?php if($RIDD_TYPE3 == 3) { ?> selected <?php } ?>>Jarang Sekali</option>
                                                                    <option value="4" <?php if($RIDD_TYPE3 == 4) { ?> selected <?php } ?>>Sering</option>
                                                                    <option value="5" <?php if($RIDD_TYPE3 == 5) { ?> selected <?php } ?>>Sering Sekali</option>
                                                                </select> </td>
                                                      <td width="14%" nowrap style="text-align:center">
                                        <input type="text" class="form-control" style="max-width:200px; text-align:right" name="data3[<?php echo $currentRow; ?>][RIDD_LEVEL3]" id="data3<?php echo $currentRow; ?>RIDD_LEVEL3" value="<?php echo $RIDD_LEVEL3; ?>" onKeyPress="return isIntOnlyNew(event);" />
                                                                <select name="data3[<?php echo $currentRow; ?>][RIDD_STAT3]" id="data3<?php echo $currentRow; ?>RIDD_STAT3" class="form-control" style="width:100px; display:none">
                                                                    <option value="1" <?php if($RIDD_STAT == 1) { ?> selected <?php } ?>>Enable</option>
                                                                    <option value="0" <?php if($RIDD_STAT == 0) { ?> selected <?php } ?>>Disable</option>
                                                                </select> 
                                                            </td>
                                          </tr>
                                                    <?php
													endforeach;
                                                }
                                            ?>
                                        </table>
                                    </div>
                                </div>
                          	</div>
                        </div>
                        <br>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <!--<input type="submit" name="submitAdd" id="submitAdd" class="btn btn-primary" value="Save" onClick="return buttonShowPhoto(1)">&nbsp;-->
                                <button class="btn btn-primary" onClick="return buttonShowPhoto(1)"><i class="cus-save-16x16"></i>&nbsp;&nbsp;<?php echo $Save; ?></button>&nbsp;
                                
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
	var decFormat		= 2;
	
	function checkInp()
	{
		
		RID_PRJCODE	= document.getElementById("RID_PRJCODE").value;
		if(RID_PRJCODE == 'KTR')
		{
			RID_DIVDEP	= document.getElementById("RID_DIVDEP").value;
			if(RID_DIVDEP == '')
			{
				alert("Please Input Division / Department.");
				document.getElementById("RID_DIVDEP").focus();
				return false;
			}
		}
		
		RID_CAUSE	= document.getElementById("RID_CAUSE").value;
		if(RID_CAUSE == '')
		{
			alert("Please Input Cause of Risk.");
			document.getElementById("RID_CAUSE").focus();
			return false;
		}
	}

	function add_row1() 
	{
		var objTable, objTR, objTD, intIndex, arrItem;
		var RIDD_CODE 		= "<?php echo $RID_CODE; ?>";
		var RIDD_PRJCODE	= document.getElementById('RID_PRJCODE').value;
		var EMP_ID1			= "<?php echo $DefEmp_ID; ?>";
		
		objTable 		= document.getElementById('tbl1');
		intTable 		= objTable.rows.length;
		//alert('intTable = '+intTable)
		//intIndex = parseInt(document.frm.rowCount.value) + 1;
		intIndex = parseInt(objTable.rows.length) - 1;
		//intIndex = intTable;
		document.frm.rowCount1.value = intIndex;
		
		objTR = objTable.insertRow(intTable);
		objTR.id = 'tr_' + intIndex;
		
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = "left";
		objTD.noWrap = true;
		objTD.innerHTML = '<input type="hidden" class="form-control" style="max-width:250px" name="data1['+intIndex+'][RIDD_CODE1]" id="data1'+intIndex+'RIDD_CODE1" value="'+RIDD_CODE+'" /><input type="hidden" class="form-control" style="max-width:250px" name="data1['+intIndex+'][RIDD_PRJCODE1]" id="data1'+intIndex+'RIDD_PRJCODE1" value="'+RIDD_PRJCODE+'" /><input type="hidden" class="form-control" style="max-width:350px" name="data1['+intIndex+'][RIDD_GROUP1]" id="data1'+intIndex+'RIDD_GROUP1" value="1" /><input type="text" class="form-control" style="max-width:650px" name="data1['+intIndex+'][RIDD_DESC1]" id="data1'+intIndex+'RIDD_DESC1" value="" placeholder="Risk Description ..." />';
		
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.innerHTML = '<select name="data1['+intIndex+'][RIDD_TYPE1]" id="data1'+intIndex+'RIDD_TYPE1" class="form-control" style="width:140px"><option value="1">Jarang Sekali</option><option value="2" >Jarang</option><option value="3">Kadang</option><option value="4">Sering</option><option value="5">Sering Sekali</option></select>';
		
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.innerHTML = '<input type="text" class="form-control" style="max-width:90px" name="data1['+intIndex+'][RIDD_LEVEL1]" id="data1'+intIndex+'RIDD_LEVEL1" value="" onKeyPress="return isIntOnlyNew(event);" placeholder="Score ..." /><input type="hidden" class="form-control" style="max-width:70px" name="data1['+intIndex+'][EMP_ID1]" id="data1'+intIndex+'EMP_ID1" value="'+EMP_ID1+'" /><select name="data1['+intIndex+'][RIDD_STAT1]" id="data1'+intIndex+'RIDD_STAT1" class="form-control" style="width:100px; display:none"><option value="1">Enable</option><option value="0" >Disable</option></select>';
	}

	function add_row2() 
	{
		var objTable, objTR, objTD, intIndex, arrItem;
		var RIDD_CODE 		= "<?php echo $RID_CODE; ?>";
		var RIDD_PRJCODE	= document.getElementById('RID_PRJCODE').value;
		var EMP_ID2			= "<?php echo $DefEmp_ID; ?>";
		
		objTable 		= document.getElementById('tbl2');
		intTable 		= objTable.rows.length;
		//alert('intTable = '+intTable)
		//intIndex = parseInt(document.frm.rowCount.value) + 1;
		intIndex = parseInt(objTable.rows.length) - 1;
		//intIndex = intTable;
		document.frm.rowCount1.value = intIndex;
		
		objTR = objTable.insertRow(intTable);
		objTR.id = 'tr_' + intIndex;
		
		intIndex2			= intIndex + 1;
		var RIDD_IMPNO2		= ''+RIDD_CODE+''+intIndex2+'';
		
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = "center";
		objTD.noWrap = true;
		objTD.innerHTML = '<input type="hidden" class="form-control" style="max-width:250px" name="data2['+intIndex+'][RIDD_CODE2]" id="data2'+intIndex+'RIDD_CODE2" value="'+RIDD_CODE+'" /><input type="hidden" class="form-control" style="max-width:250px" name="data2['+intIndex+'][RIDD_PRJCODE2]" id="data2'+intIndex+'RIDD_PRJCODE2" value="'+RIDD_PRJCODE+'" /><input type="hidden" class="form-control" style="max-width:350px" name="data2['+intIndex+'][RIDD_GROUP2]" id="data2'+intIndex+'RIDD_GROUP2" value="2" /><input type="hidden" class="form-control" style="max-width:100px" name="data2['+intIndex+'][RIDD_IMPNO2]" id="data2'+intIndex+'RIDD_IMPNO2" value="'+RIDD_IMPNO2+'" /><input type="text" class="form-control" style="max-width:650px" name="data2['+intIndex+'][RIDD_DESC2]" id="data2'+intIndex+'RIDD_DESC2" value="" placeholder="Impact Description ..." />';	
		
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'right';
		objTD.innerHTML = '<select name="data2['+intIndex+'][RIDD_TYPE2]" id="data2'+intIndex+'RIDD_TYPE2" class="form-control" style="width:140px"><option value="1">Ringan</option><option value="2" >Sedang</option><option value="3">Agak Berat</option><option value="4">Berat</option><option value="5">Ekstreme</option></select>';
		
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.innerHTML = '<input type="text" class="form-control" style="max-width:200px; text-align:right" name="data2['+intIndex+'][RIDD_LEVEL2]" id="data2'+intIndex+'RIDD_LEVEL2" value="" onKeyPress="return isIntOnlyNew(event);" placeholder="Rate ..." /><input type="hidden" class="form-control" style="max-width:70px" name="data2['+intIndex+'][EMP_ID2]" id="data2'+intIndex+'EMP_ID2" value="'+EMP_ID2+'" /><select name="data2['+intIndex+'][RIDD_STAT2]" id="data2'+intIndex+'RIDD_STAT2" class="form-control" style="width:100px; display:none"><option value="1">Enable</option><option value="0" >Disable</option></select>';
		
		add_row3();
	}

	function add_row3() 
	{
		var objTable, objTR, objTD, intIndex, arrItem;
		var RIDD_CODE 		= "<?php echo $RID_CODE; ?>";
		var RIDD_PRJCODE	= document.getElementById('RID_PRJCODE').value;
		var EMP_ID3			= "<?php echo $DefEmp_ID; ?>";
		
		objTable 		= document.getElementById('tbl3');
		intTable 		= objTable.rows.length;
		//alert('intTable = '+intTable)
		//intIndex = parseInt(document.frm.rowCount.value) + 1;
		intIndex = parseInt(objTable.rows.length) - 1;
		//intIndex = intTable;
		document.frm.rowCount1.value = intIndex;
		
		objTR = objTable.insertRow(intTable);
		objTR.id = 'tr_' + intIndex;
		
		intIndex3			= intIndex + 1;
		var RIDD_IMPNO3		= ''+RIDD_CODE+''+intIndex3+'';
		
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = "center";
		objTD.noWrap = true;
		objTD.innerHTML = '<input type="hidden" class="form-control" style="max-width:250px" name="data3['+intIndex+'][RIDD_CODE3]" id="data3'+intIndex+'RIDD_CODE3" value="'+RIDD_CODE+'" /><input type="hidden" class="form-control" style="max-width:250px" name="data3['+intIndex+'][RIDD_PRJCODE3]" id="data3'+intIndex+'RIDD_PRJCODE3" value="'+RIDD_PRJCODE+'" /><input type="hidden" class="form-control" style="max-width:350px" name="data3['+intIndex+'][RIDD_GROUP3]" id="data3'+intIndex+'RIDD_GROUP3" value="3" /><input type="hidden" class="form-control" style="max-width:100px" name="data3['+intIndex+'][RIDD_IMPNO3]" id="data3'+intIndex+'RIDD_IMPNO3" value="'+RIDD_IMPNO3+'" /><input type="text" class="form-control" style="max-width:650px" name="data3['+intIndex+'][RIDD_DESC3]" id="data3'+intIndex+'RIDD_DESC3" value="" placeholder="Risk Policy ..." />';	
		
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'right';
		objTD.innerHTML = '<select name="data3['+intIndex+'][RIDD_TYPE3]" id="data3'+intIndex+'RIDD_TYPE3" class="form-control" style="width:140px"><option value="1">Kadang</option><option value="2" >Jarang</option><option value="3">Jarang Sekali</option><option value="4">Sering</option><option value="5">Sering Sekali</option></select>';
		
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.innerHTML = '<input type="text" class="form-control" style="max-width:200px; text-align:right" name="data3['+intIndex+'][RIDD_LEVEL3]" id="data3'+intIndex+'RIDD_LEVEL3" value="" onKeyPress="return isIntOnlyNew(event);" placeholder="Rate ..." /><input type="hidden" class="form-control" style="max-width:70px" name="data3['+intIndex+'][EMP_ID3]" id="data3'+intIndex+'EMP_ID3" value="'+EMP_ID3+'" /><select name="data3['+intIndex+'][RIDD_STAT3]" id="data3'+intIndex+'RIDD_STAT3" class="form-control" style="width:100px; display:none"><option value="1">Enable</option><option value="0" >Disable</option></select>';
	}
	
	function doDecimalFormat(angka)
	{
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