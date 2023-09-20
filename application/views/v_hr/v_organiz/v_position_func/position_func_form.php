<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 1 November 2017
 * File Name	= position_form.php
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

if($task == "add")
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
	$year = substr((int)$Pattern_YearAktive, 2,2);
	$month = (int)$Pattern_MonthAktive;
	$date = (int)$Pattern_MonthAktive;
	$konst = "";
	
	//$this->db->where('Patt_Year', $year);
	//$this->db->where('Patt_Month', $month);
	//$this->db->where('Patt_Date', $date);
	//$myCount 	= $this->db->count_all('tbl_position_func');
	$myCount	= 0;
	$sqlMAX		= "SELECT MAX(Patt_Number) AS MAX_PATTNumb FROM tbl_position_func";
	$resMAX 	= $this->db->query($sqlMAX)->result();
	foreach($resMAX as $rowMAX) :
		$myCount= $rowMAX->MAX_PATTNumb;
	endforeach;
	
	//$this->db->where('Patt_Year', $year);
	//$this->db->where('Patt_Month', $month);
	//$this->db->where('Patt_Date', $date);
	//$this->db->select('Patt_Number');
	//$result = $this->db->get('tbl_position_func')->result();
	
	// karena untuk nomor employee tidak ada ketentuan berdasarkan tahun, bulan dan tanggal, maka lgsung menhgitung jumlah row.
	/*if($myCount>0)
	{
		foreach($result as $row) :
			$myMax = $row->Patt_Number;
		endforeach;
	}	else	{		$myMax = 1;	}*/
	
	$myMax = $myCount + 1;
	
	$thisMonth = $month;
	
	$lenMonth = strlen($thisMonth);
	if($lenMonth==1) $nolMonth="0";elseif($lenMonth==2) $nolMonth="";
	$pattMonth = $nolMonth.$thisMonth;
	
	$thisDate = 24;
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
	$len = strlen($lastPatternNumb);
	
	if($Pattern_Length==2)
	{
		if($len==1) $nol="0";
	}
	elseif($Pattern_Length==3)
	{if($len==1) $nol="00";else if($len==2) $nol="0";
	}
	elseif($Pattern_Length==4)
	{
		if($len==1) $nol="000";else if($len==2) $nol="00";else if($len==3) $nol="0";else if($len==4) $nol="";
	}
	elseif($Pattern_Length==5)
	{
		if($len==1) $nol="0000";else if($len==2) $nol="000";else if($len==3) $nol="00";else if($len==4) $nol="0";else if($len==5) $nol="";
	}
	elseif($Pattern_Length==6)
	{if($len==1) $nol="00000";else if($len==2) $nol="0000";else if($len==3) $nol="000";else if($len==4) $nol="00";else if($len==5) $nol="0";
	}
	elseif($Pattern_Length==7)
	{if($len==1) $nol="000000";else if($len==2) $nol="00000";else if($len==3) $nol="0000";else if($len==4) $nol="000";else if($len==5) $nol="00";else if($len==6) $nol="0";
	}
	
	$lastPatternNumb = $nol.$lastPatternNumb;
	$DocNumber 		= "$Pattern_Code$groupPattern$konst$lastPatternNumb";
	
	$POSF_NO 		= $DocNumber;	
	$POSF_CODE 		= '';		
	$POSF_NAME 		= '';
	$POSF_PARENT	= '';
	$POSF_DESC		= '';
	$POSF_ISLAST	= 0;
	$POSF_STAT		= 1;
}	
else
{
	$POSF_NO 	= $default['POSF_NO'];	
	$POSF_CODE 	= $default['POSF_CODE'];		
	$POSF_NAME 	= $default['POSF_NAME'];
	$POSF_PARENT= $default['POSF_PARENT'];
	$POSF_DESC	= $default['POSF_DESC'];
	$POSF_STAT	= $default['POSF_STAT'];
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
		if($TranslCode == 'Code')$Code = $LangTransl;
		if($TranslCode == 'PositionName')$PositionName = $LangTransl;
		if($TranslCode == 'SectionName')$SectionName = $LangTransl;
		if($TranslCode == 'Description')$Description = $LangTransl;
		if($TranslCode == 'SectionLevel')$SectionLevel = $LangTransl;
		if($TranslCode == 'Parent')$Parent = $LangTransl;
		if($TranslCode == 'LastPosition')$LastPosition = $LangTransl;
		if($TranslCode == 'Status')$Status = $LangTransl;
		if($TranslCode == 'Save')$Save = $LangTransl;
		if($TranslCode == 'Update')$Update = $LangTransl;
		if($TranslCode == 'Back')$Back = $LangTransl;
		if($TranslCode == 'StructuralPosition')$StructuralPosition = $LangTransl;
		if($TranslCode == 'Organization')$Organization = $LangTransl;
		if($TranslCode == 'Nomor')$Nomor = $LangTransl;
	endforeach;
?>

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>
    <?php echo $Add; ?>
    <small><?php echo $StructuralPosition; ?></small>
  </h1>
  <?php /*?><ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#">Tables</a></li>
    <li class="active">Data tables</li>
  </ol><?php */?>
</section>
<!-- Main content -->
<section class="content">	
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">&nbsp;</h3>                
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
        </div>
        <div class="box-body chart-responsive">
            <form class="form-horizontal" name="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return checkData()">
                <div class="form-group">
                  	<label class="col-sm-2 control-label"><?php echo $Nomor; ?> </label>
                  	<div class="col-sm-10">
                    	<input type="text" name="POSF_NO1" id="POSF_NO1" value="<?php echo $POSF_NO; ?>" class="form-control" style="max-width:150px" disabled>
                    	<input type="hidden" name="POSF_NO" id="POSF_NO" value="<?php echo $POSF_NO; ?>" class="form-control" style="max-width:150px">
                    </div>
                </div>
              	<div class="form-group">
                	<label for="inputName" class="col-sm-2 control-label"><?php echo $Code; ?> </label>
                	<div class="col-sm-10">
                    	<?php
							if($task == "add")
							{
								?>
       				  				<input type="text" name="POSF_CODE" id="POSF_CODE" value="" class="form-control" style="max-width:150px">
								<?php
							}
							else
							{
								?>
                                  	<input type="text" name="POSF_CODEX" id="POSF_CODEX" value="<?php echo $POSF_CODE; ?>" class="form-control" style="max-width:150px" disabled>
                                  	<input type="hidden" name="POSF_CODE" id="POSF_CODE" value="<?php echo $POSF_CODE; ?>" class="form-control" style="max-width:250px">
								<?php
							}
						?>
                    </div>
              	</div>
                <div class="form-group">
                  	<label class="col-sm-2 control-label"><?php echo $PositionName; ?> </label>
                  	<div class="col-sm-10">
                    	<input type="text" name="POSF_NAME" id="POSF_NAME" value="<?php echo $POSF_NAME; ?>" class="form-control" style="max-width:250px">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail" class="col-sm-2 control-label"><?php echo $SectionName; ?></label>
                    <div class="col-sm-10">
                        <select name="POSF_PARENT" id="POSF_PARENT" class="form-control" style="max-width:250px" >
                        	<option value="" > ---- None ----</option>
							<?php
							$POSS_PARENT = $POSF_PARENT;
                            if($countParent>0)
                            {
                                $i = 0;
                                foreach($vwParent as $row) :
									$POSS_CODE1		= $row->POSS_CODE;
									$POSS_NAME1		= $row->POSS_NAME;
									$POSS_PARENT1	= $row->POSS_PARENT;
									$space_level1	= "";
									
									$sqlC1		= "tbl_position_str WHERE POSS_PARENT = '$POSS_CODE1'";
									$ressqlC1 	= $this->db->count_all($sqlC1);
									?>
                                		<option value="<?php echo $POSS_CODE1;?>" <?php if($POSS_CODE1 == $POSS_PARENT) { ?>selected<?php } if($ressqlC1>0) {?> style="font-weight:bold"<?php } ?>><?php echo "$space_level1$POSS_NAME1";?></option>
                            		<?php
									if($ressqlC1 > 0)
									{
										$sqlDEPT	= "SELECT POSS_CODE, POSS_NAME, POSS_PARENT
														FROM tbl_position_str
														WHERE POSS_PARENT = '$POSS_CODE1'";
										$resDEPT 	= $this->db->query($sqlDEPT)->result();
										foreach($resDEPT as $rowDept) :
											$POSS_CODE2		= $rowDept->POSS_CODE;
											$POSS_NAME2		= $rowDept->POSS_NAME;
											$POSS_PARENT2	= $rowDept->POSS_PARENT1;
											$space_level2	= "&nbsp;&nbsp;&nbsp;";
											
											$sqlC2		= "tbl_position_str WHERE POSS_PARENT = '$POSS_CODE2'";
											$ressqlC2 	= $this->db->count_all($sqlC2);		
											?>
                                                <option value="<?php echo $POSS_CODE2;?>" <?php if($POSS_CODE2 == $POSS_PARENT) { ?>selected<?php } if($ressqlC2>0) {?> style="font-weight:bold"<?php } ?>><?php echo "$space_level2$POSS_NAME2";?></option>
                                            <?php
											if($ressqlC2 > 0)
											{
												$sqlDIV	= "SELECT POSS_CODE, POSS_NAME, POSS_PARENT
																FROM tbl_position_str
																WHERE POSS_PARENT = '$POSS_CODE2'";
												$resDIV 	= $this->db->query($sqlDIV)->result();
												foreach($resDIV as $rowDIV) :
													$POSS_CODE3		= $rowDIV->POSS_CODE;
													$POSS_NAME3		= $rowDIV->POSS_NAME;
													$POSS_PARENT3	= $rowDIV->POSS_PARENT1;
													$space_level3	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
													
													$sqlC3		= "tbl_position_str WHERE POSS_PARENT = '$POSS_CODE3'";
													$ressqlC3 	= $this->db->count_all($sqlC3);		
													?>
														<option value="<?php echo $POSS_CODE3;?>" <?php if($POSS_CODE3 == $POSS_PARENT) { ?>selected<?php } if($ressqlC3>0) {?> style="font-weight:bold"<?php } ?>><?php echo "$space_level3$POSS_NAME3";?></option>
													<?php
													if($ressqlC3 > 0)
													{
														$sqlUNT	= "SELECT POSS_CODE, POSS_NAME, POSS_PARENT
																		FROM tbl_position_str
																		WHERE POSS_PARENT = '$POSS_CODE3'";
														$resUNT 	= $this->db->query($sqlUNT)->result();
														foreach($resUNT as $rowUNT) :
															$POSS_CODE4		= $rowUNT->POSS_CODE;
															$POSS_NAME4		= $rowUNT->POSS_NAME;
															$POSS_PARENT4	= $rowUNT->POSS_PARENT1;
															$space_level4	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
															
															$sqlC4		= "tbl_position_str WHERE POSS_PARENT = '$POSS_CODE4'";
															$ressqlC4 	= $this->db->count_all($sqlC4);		
															?>
																<option value="<?php echo $POSS_CODE4;?>" <?php if($POSS_CODE4 == $POSS_PARENT) { ?>selected<?php } if($ressqlC4>0) {?> style="font-weight:bold"<?php } ?>><?php echo "$space_level4$POSS_NAME4";?></option>
															<?php
															if($ressqlC4 > 0)
															{
																$sqlURS	= "SELECT POSS_CODE, POSS_NAME, POSS_PARENT
																				FROM tbl_position_str
																				WHERE POSS_PARENT = '$POSS_CODE4'";
																$resURS 	= $this->db->query($sqlURS)->result();
																foreach($resURS as $rowURS) :
																	$POSS_CODE5		= $rowURS->POSS_CODE;
																	$POSS_NAME5		= $rowURS->POSS_NAME;
																	$POSS_PARENT5	= $rowURS->POSS_PARENT1;
																	$space_level5	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
																	
																	$sqlC5		= "tbl_position_str WHERE POSS_PARENT = '$POSS_CODE5'";
																	$ressqlC5 	= $this->db->count_all($sqlC4);		
																	?>
																		<option value="<?php echo $POSS_CODE5;?>" <?php if($POSS_CODE5 == $POSS_PARENT) { ?>selected<?php } if($ressqlC5>0) {?> style="font-weight:bold"<?php } ?>><?php echo "$space_level5$POSS_NAME5";?></option>
																	<?php
																	if($ressqlC5 > 0)
																	{
																		$sqlSTAF	= "SELECT POSS_CODE, POSS_NAME, POSS_PARENT
																						FROM tbl_position_str
																						WHERE POSS_PARENT = '$POSS_CODE5'";
																		$resSTAF 	= $this->db->query($sqlSTAF)->result();
																		foreach($resSTAF as $rowSTAF) :
																			$POSS_CODE6		= $rowSTAF->POSS_CODE;
																			$POSS_NAME6		= $rowSTAF->POSS_NAME;
																			$POSS_PARENT6	= $rowSTAF->POSS_PARENT1;
																			$space_level6	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
																			?>
																				<option value="<?php echo $POSS_CODE5;?>" <?php if($POSS_CODE5 == $POSS_PARENT) { ?>selected<?php } if($ressqlC5>0) {?> style="font-weight:bold"<?php } ?>><?php echo "$space_level5$POSS_NAME5";?></option>
																			<?php
																		endforeach;
																	}
																endforeach;
															}
														endforeach;
													}
												endforeach;
											}
										endforeach;
									}
								endforeach;
                            }
                            ?>
						</select>
                    </div>
                </div>
                <div class="form-group">
                  	<label class="col-sm-2 control-label"><?php echo $Description; ?></label>
                  	<div class="col-sm-10">
                    	<input type="text" name="POSF_DESC" id="POSF_DESC" value="<?php echo $POSF_DESC; ?>" class="form-control" style="max-width:450px">
                    </div>
                </div>
                <div class="form-group" style="display:none">
                    <label for="inputEmail" class="col-sm-2 control-label"><?php echo $LastPosition ?></label>
                    <div class="col-sm-10">
                    	<select name="POSF_ISLAST" id="POSF_ISLAST" class="form-control" style="max-width:70px" >
                            <option value="1" <?php if($POSF_ISLAST == 1) { ?> selected <?php } ?>>Yes</option>
                            <option value="0" <?php if($POSF_ISLAST == 0) { ?> selected <?php } ?>>No</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail" class="col-sm-2 control-label"><?php echo $Status ?></label>
                    <div class="col-sm-10">
                    	<select name="POSF_STAT" id="POSF_STAT" class="form-control" style="max-width:100px" >
                            <option value="1" <?php if($POSF_STAT == 1) { ?> selected <?php } ?>>Active</option>
                            <option value="0" <?php if($POSF_STAT == 0) { ?> selected <?php } ?>>In Active</option>
                        </select>
                    </div>
                </div>
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
            <script>
				function checkData()
				{
					POSF_CODE = document.getElementById('POSF_CODE').value;
					POSF_NAME = document.getElementById('POSF_NAME').value;
					if(POSF_CODE == '')
					{
						alert('Please input Department Code.');
						document.getElementById("POSF_CODE").focus();
						return false;
					}
					if(POSF_NAME == '')
					{
						alert('Please input Department Name.');
						document.getElementById("POSF_NAME").focus();
						return false;
					}		
				}
			</script>
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

<?php 
$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>