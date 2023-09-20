<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 5 April 2017
 * File Name	= item_list_form.php
 * Location		= -
*/

/* 
 * Author		= Hendar Permana
 * Edit Date	= 05 September 2017
 * File Name	= entry_other_form.php
 * Location		= -
*/

?>
<?php
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');

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
	
	
	$FM_CODE		= $DATEN;
	$FM_PERIODE		= $DATEF;
	$FM_PRJCODE		= $PRJCODE;
	$FM_PROGRES		= 0;
	$FM_PREDICTION	= 0;
	$FM_MCKATROLAN	= 0;
	$FM_STATUS		= '';
	$FM_NOTE		= '';
	$FM_CREATER		= $DefEmp_ID;
	$FM_CREATED		= $DATEF;
	
	//echo $DefEmp_ID;
	//$FM_CODE		= '';
	//$FM_CODE		= '';
	
	//$PRJCODE		= $PRJCODE;
	//$ITM_CATEG 		= 'MTRL';
	//$ITM_REFR		= '';
	//$ITM_NAME		= '';
	//$ITM_DESC 		= '';
	//$ITM_TYPE 		= 0;
	//$ITM_UNIT 		= 'ls';
	//$ITM_CURRENCY 	= "IDR";
	//$ITM_PRICE		= 0;
	//$ITM_VOLM 		= 0;
	//$ISRENT			= 0;
	//$ISPART 		= 0;
	//$ISFUEL 		= 0;
	//$ISLUBRIC 		= 0;
	//$ISFASTM 		= 0;
	//$ISWAGE 		= 0;
	//$ITM_KIND		= 0;
	
	//$STATUS			= 1;
	
	//if(isset($_POST['ITM_CATEGx']))
	//{
	//	$ITM_CATEG = $_POST['ITM_CATEGx'];
	//}
	
	//if($ITM_CATEG == 'MTRL')
	//{
	//	$ITM_TYPE = 'MTR';
	//}
	//elseif($ITM_CATEG == 'SPT')
	//{
	//	$ITM_TYPE = 'SPT';
	//}
	//elseif($ITM_CATEG == 'SUPL')
	//{
	//	$ITM_TYPE = 'SPL';
	//}
	//elseif($ITM_CATEG == 'TLS')
	//{
	//	$ITM_TYPE = 'TLS';
	//}
	//elseif($ITM_CATEG == 'UPAH')
	//{
	//	$ITM_TYPE = 'U';
	//}
	//elseif($ITM_CATEG == 'SRVC')
	//{
	//	$ITM_TYPE = 'S';
	//}
	/*elseif($ITM_CATEG == 'TLS')
	{
		$ITM_TYPE = 'T';
	}*/
	//elseif($ITM_CATEG == 'INDIR')
	//{
	//	$ITM_TYPE = 'I';
	//}
	//elseif($ITM_CATEG == 'REIMB')
	//{
	//	$ITM_TYPE = 'R';
	//}
	
	/*$myCount = $this->db->count_all('tbl_FM');
	
	$sql = "SELECT MAX(FM_CODE) as maxNumber FROM tbl_FM WHERE FM_PRJCODE = '$FM_PRJCODE'";
	$result = $this->db->query($sql)->result();
	if($myCount>0)
	{
		foreach($result as $row) :
			$myMax = $row->maxNumber;
			$myMax = $myMax+1;
		endforeach;
	}
	else
	{
		$myMax = 1;
	}
	
	$LASTNO				= $myMax;
	
	$Pattern_Length = 0;
	$LASTNO = $myMax;
	$LASTNO1 = $myMax;
	$len = strlen($LASTNO);
	
	if($len==1) $nol="0000";else if($len==2) $nol="000";else if($len==3) $nol="00";else if($len==4) $nol="0";

	$ItemCodeRN = $nol.$LASTNO;
	$ITM_CODE	= $ITM_TYPE.$nol.$LASTNO;
	$ITM_IN		= 0;*/
}
else
{
	$FM_CODE 		= $default['FM_CODE'];
	$FM_PERIODE		= $default['FM_PERIODE'];
	$FM_PERIODE		= date('m/d/Y',strtotime($FM_PERIODE));
	$FM_PRJCODE		= $default['FM_PRJCODE'];
	$FM_PROGRES 	= $default['FM_PROGRES'];
	$FM_PREDICTION 	= $default['FM_PREDICTION'];
	$FM_MCKATROLAN 	= $default['FM_MCKATROLAN'];
	$FM_STATUS 		= $default['FM_STATUS'];
	$FM_NOTE 		= $default['FM_NOTE'];
	$FM_CREATED		= $DATEF;
	$FM_CREATER		= $DefEmp_ID;

	
	//$ITM_CODE 		= $default['ITM_CODE'];
	//$ITM_NAME 		= $default['ITM_NAME'];
	//$ITM_CATEG 		= $default['ITM_CATEG'];
	//$ITM_DESC 		= $default['ITM_DESC'];
	//$ITM_TYPE 		= $default['ITM_TYPE'];
	//$ITM_UNIT 		= $default['ITM_UNIT'];
	//$ITM_CURRENCY	= $default['ITM_CURRENCY'];
	//$ITM_VOLM 		= $default['ITM_VOLM'];
	//$ITM_IN 		= $default['ITM_IN'];
	//$ITM_OUT 		= $default['ITM_OUT'];
	//$ITM_PRICE		= $default['ITM_PRICE'];
	//$UMCODE 		= $default['UMCODE'];
	//$Unit_Type_Name = $default['Unit_Type_Name'];
	//$UMCODE 		= $default['UMCODE'];
	//$STATUS 		= $default['STATUS'];
	//$ISRENT 		= $default['ISRENT'];
	//$ISPART 		= $default['ISPART'];
	//$ISFUEL 		= $default['ISFUEL'];
	//$ISLUBRIC 		= $default['ISLUBRIC'];
	//$ISFASTM 		= $default['ISFASTM'];
	//$ISWAGE 		= $default['ISWAGE'];
	//$ITM_KIND 		= $default['ITM_KIND'];
	//$LASTNO 		= $default['LASTNO'];
	//$PRJCODE 		= $default['PRJCODE'];
	
	//echo "fdfrgrgtg $ISLUBRIC = $ISFASTM";
	
	//if(isset($_POST['ITM_CATEGx']))
	//{
	//	$ITM_CATEG = $_POST['ITM_CATEGx'];
	//}
	
	//if($ITM_CATEG == 'MTRL')
	//{
	//	$ITM_TYPE = 'M';
	//}
	//elseif($ITM_CATEG == 'SUPL')
	//{
	//	$ITM_TYPE = 'S';
	//}
	//elseif($ITM_CATEG == 'UPAH')
	//{
	//	$ITM_TYPE = 'U';
	//}
	//elseif($ITM_CATEG == 'SRVC')
	//{
	//	$ITM_TYPE = 'S';
	//}
	//elseif($ITM_CATEG == 'TLS')
	//{
	//	$ITM_TYPE = 'T';
	//}
	//elseif($ITM_CATEG == 'INDIR')
	//{
	//	$ITM_TYPE = 'I';
	//}
	//elseif($ITM_CATEG == 'REIMB')
	//{
	//	$ITM_TYPE = 'R';
	//}
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
		if($TranslCode == 'Periode')$Periode = $LangTransl;
		if($TranslCode == 'Project')$Project = $LangTransl;
		if($TranslCode == 'ProgresPlan')$ProgresPlan = $LangTransl;
		if($TranslCode == 'PredictionValue')$PredictionValue = $LangTransl;
		if($TranslCode == 'MCKatrolan')$MCKatrolan = $LangTransl;
		if($TranslCode == 'Status')$Status = $LangTransl;
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
                        
                        <!--<div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Item Category</label>
                          	<div class="col-sm-10">
                            	<select name="ITM_CATEG" id="ITM_CATEG" class="form-control" style="max-width:150px" onChange="chooseCategory(this.value)">
									<?php /*echo $i = 0;
                                    if($recordcountCateg > 0)
                                    {
                                        foreach($viewCateg as $row) :
                                            $CategCode1 = $row->itemCategory_code;
                                            $ItemCodeCat1 = $row->ItemCodeCat;
                                            $ItemCategory_name1 = $row->ItemCategory_name;
                                            ?>
                                            <option value="<?php echo $CategCode1; ?>" <?php if($CategCode1 == $ITM_CATEG) { ?> selected <?php } ?>><?php echo $ItemCategory_name1; ?></option>
                                            <?php
                                        endforeach;
                                    }
                                    else
                                    {*/
                                        ?>
                                            <option value="none">--- No Unit Found ---</option>
                                        <?php
                                   // }
                                    ?>
                                </select>
                          	</div>
                        </div>-->
                        
                        <!--<div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Project</label>
                          	<div class="col-sm-10">
                            	<input type="hidden" name="PRJCODE" id="PRJCODE" value="<?php //echo $PRJCODE; ?>" />
                                <select name="PRJCODE1" id="PRJCODE1" class="form-control" style="max-width:300px" onChange="chooseProject()" disabled>
                                  <?php
                                        //if($resPLC > 0)
                                        //{
                                         //   foreach($resPL as $rowPL) :
                                         //       $proj_ID1 = $rowPL->proj_ID;
                                         //       $PRJCODE1 = $rowPL->PRJCODE;
                                          //      $PRJNAME1 = $rowPL->PRJNAME;
                                                ?>
                                  				<option value="<?php //echo $PRJCODE1; ?>" <?php //if($PRJCODE1 == $PRJCODE) { ?> selected <?php// } ?>><?php// echo "$PRJCODE1 - $PRJNAME1"; ?></option>
                                  <?php
                                           // endforeach;
                                        //}
                                        //else
                                       // {
                                            ?>
                                  				<option value="none">--- No Project Found ---</option>
                                  <?php
                                       // }
                                        ?>
                                </select>
                          	</div>
                        </div>-->
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Code ?> </label>
                          	<div class="col-sm-10">
                            	<!--<input type="hidden" class="textbox" name="ITM_TYPE" id="ITM_TYPE" size="5" value="<?php //echo $ITM_TYPE; ?>" />-->
                                <input type="text" class="form-control" style="max-width:200px" name="FM_CODE" id="FM_CODE" value="<?php echo $FM_CODE; ?> " disabled/>
                                <input type="hidden" class="form-control" style="max-width:200px" name="FM_CODE" id="FM_CODE" value="<?php echo $FM_CODE; ?> " />
                                <input type="hidden" class="textbox" name="FM_CREATER" id="FM_CREATER" size="3" value="<?php echo $FM_CREATER; ?>" />
                                <input type="hidden" class="textbox" name="FM_CREATED" id="FM_CREATED" size="5" value="<?php echo $FM_CREATED; ?>" />
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Periode ?></label>
                          	<div class="col-sm-10">
                                <input type="text" name="FM_PERIODE" class="form-control pull-left" id="datepicker1" value="<?php echo $FM_PERIODE; ?>" style="width:150px">
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Project ?></label>
                          	<div class="col-sm-10">
                                <input type="text" class="form-control" style="max-width:200px" name="FM_PRJCODE" id="FM_PRJCODE" value="<?php echo $FM_PRJCODE;?>" title="Input Project" disabled/>
                                <input type="hidden" class="form-control" style="max-width:200px" name="FM_PRJCODE" id="FM_PRJCODE" value="<?php echo $FM_PRJCODE;?>" title="Input Project"/>
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $ProgresPlan ?> %</label>
                          	<div class="col-sm-10">
                    			<!--<input type="hidden" class="form-control" style="max-width:50px" name="FM_TYPE" id="FM_TYPE" value="<?php //echo $FM_TYPE; ?>" title="<?php //echo $FM_TYPE; ?>" />
                          		<input type="hidden" name="FM_TYPE" id="FM_TYPE" value="<?php //echo $FM_TYPE; ?>" />
                                <select name="FM_TYPE" id="FM_TYPE" class="form-control" style="max-width:150px">
                                		<option value="0" <?php //if($FM_TYPE == 0) { ?> selected <?php //} ?> >FM Versi Proyek</option>
                                 		<option value="1" <?php //if($FM_TYPE == 1) { ?> selected <?php //} ?> >Material</option>
                               			<option value="2" <?php //if($FM_TYPE == 2) { ?> selected <?php //} ?> >Upah</option>
                               			<option value="3" <?php //if($FM_TYPE == 3) { ?> selected <?php //} ?> >Subkon</option>
                               			<option value="4" <?php //if($FM_TYPE == 4) { ?> selected <?php //} ?> >Alat</option>
                                </select>-->
                                <input type="text" class="form-control" style="max-width:100px; text-align:right" name="FM_PROGRES" id="FM_POGRES" value="<?php echo $FM_PROGRES;?>"/>
                                
                            </div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $PredictionValue ?> (Rp)</label>
                          	<div class="col-sm-10">
                            	<!--<input type="text" class="form-control" style="text-align:right; max-width:130px" name="FM_VALUE1" id="FM_VALUE1" size="10" value="<?php  //echo number_format($FM_VALUE, $decFormat);?>" onBlur="changeVALUE(this)" />
                    			<input type="hidden" class="textbox" style="text-align:right" name="FM_VALUE" id="FM_VALUE" size="10" value="<?php  //echo $FM_VALUE;?>" />-->
                                <input type="text" class="form-control" style="max-width:200px; text-align:right" name="FM_PREDICTION" id="FM_PREDICTION" value="<?php echo $FM_PREDICTION;?>"/>
                                
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $MCKatrolan ?> (%)</label>
                          	<div class="col-sm-10">
                            	<!--<input type="text" class="form-control" style="text-align:right; max-width:130px" name="FM_VALUE1" id="FM_VALUE1" size="10" value="<?php  //echo number_format($FM_VALUE, $decFormat);?>" onBlur="changeVALUE(this)" />
                    			<input type="hidden" class="textbox" style="text-align:right" name="FM_VALUE" id="FM_VALUE" size="10" value="<?php  //echo $FM_VALUE;?>" />-->
                                <input type="text" class="form-control" style="max-width:200px; text-align:right" name="FM_MCKATROLAN" id="FM_MCKATROLAN" value="<?php echo $FM_MCKATROLAN;?>"/>
                                
                          	</div>
                        </div>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $Status ?></label>
                            <div class="col-sm-10">
                                <select name="FM_STATUS" id="FM_STATUS" class="form-control" style="max-width:100px">
                                    <option value="1" <?php if($FM_STATUS == 1) { ?> selected <?php } ?>>New</option>
                                    <option value="2" <?php if($FM_STATUS == 2) { ?> selected <?php } ?>>Confirm</option>
                                    <option value="3" <?php if($FM_STATUS == 3) { ?> selected <?php } ?>>Approve</option>
                                    <option value="6" <?php if($FM_STATUS == 6) { ?> selected <?php } ?>>Close</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $Notes ?></label>
                            <div class="col-sm-10">
                                <textarea name="FM_NOTE" class="form-control" id="FM_NOTE" style="height:100px; max-width:200px" ><?php echo $FM_NOTE; ?></textarea>
                            </div>
                        </div>
                        <!--<div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Item Price</label>
                          	<div class="col-sm-10">
                            	<input type="text" class="form-control" style="text-align:right; max-width:130px" name="ITM_PRICE1" id="ITM_PRICE1" size="10" value="<?php  //echo number_format($ITM_PRICE, $decFormat);?>" onBlur="changePRICE(this)" />
                    			<input type="hidden" class="textbox" style="text-align:right" name="ITM_PRICE" id="ITM_PRICE" size="10" value="<?php // echo $ITM_PRICE;?>"  />
                          	</div>
                        </div>-->
                        <!--<div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Volume</label>
                          	<div class="col-sm-10">
                            	<input type="text" class="form-control" style="text-align:right; max-width:130px" name="ITM_VOLM1" id="ITM_VOLM1" size="10" value="<?php // echo number_format($ITM_VOLM, $decFormat);?>" onBlur="changeVOLM(this)" <?php //if($ITM_IN > 0) { ?> disabled <?php //} ?> />
                    			<input type="hidden" class="textbox" style="text-align:right" name="ITM_VOLM" id="ITM_VOLM" size="10" value="<?php // echo $ITM_VOLM;?>" />
                          	</div>
                        </div>-->
                        <!--<div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Item Type</label>
                          	<div class="col-sm-10">
                            <input type="radio" name="ITM_KIND" id="ISRENT" value="1" class="minimal" <?php //if($ISRENT==1) { ?> checked <?php //} ?>>
                            &nbsp;&nbsp;Rent. Expenses&nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="radio" name="ITM_KIND" id="ISPART" value="2" class="minimal" <?php// if($ISPART==1) { ?> checked <?php //} ?>>
                            &nbsp;&nbsp;Part&nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="radio" name="ITM_KIND" id="ISFUEL" value="3" class="minimal" <?php //if($ISFUEL==1) { ?> checked <?php //} ?>>
                            &nbsp;&nbsp;Fuel&nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="radio" name="ITM_KIND" id="ISLUBRIC" value="4" class="minimal" <?php //if($ISLUBRIC==1) { ?> checked <?php //} ?>>
                            &nbsp;&nbsp;Lubricants&nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="radio" name="ITM_KIND" id="ISFASTM" value="5" class="minimal" <?php //if($ISFASTM==1) { ?> checked <?php //} ?>>
                            &nbsp;&nbsp;Fast Move&nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="radio" name="ITM_KIND" id="ISWAGE" value="6" class="minimal" <?php //if($ISWAGE==1) { ?> checked <?php //} ?>>
                            &nbsp;&nbsp;Wage
                          	</div>
                        </div>-->
                        <!--<div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Status</label>
                          	<div class="col-sm-10">
                            	<input type="radio" name="STATUS" id="STATUS1" value="1" <?php //if($STATUS == 1) { ?> checked <?php //} ?>>
                                Active&nbsp;
                                <input type="radio" name="STATUS" id="STATUS2" value="0" <?php //if($STATUS == 0) { ?> checked <?php //} ?>>
                                Inaktive
                            </div>
                        </div>-->
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
	function changePRICE(thisValue)
	{
		var decFormat								= document.getElementById('decFormat').value;
		var ITM_PRICE								= eval(thisValue).value.split(",").join("");
		document.getElementById('ITM_PRICE').value 	= ITM_PRICE;
		document.getElementById('ITM_PRICE1').value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_PRICE)),decFormat));	
	}
	
	function changeVALUE(thisValue)
	{
		var decFormat								= document.getElementById('decFormat').value;
		var FM_VALUE								= eval(thisValue).value.split(",").join("");
		document.getElementById('FM_VALUE').value 	= FM_VALUE;	
		document.getElementById('FM_VALUE1').value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(FM_VALUE)),decFormat));	
	}
	
	function chooseCategory(catType)
	{
		document.getElementById('ITM_CATEGx').value = catType;
		document.frmsrch.submitSrch.click();
	}
	
	function saveRecomend()
	{		
		ITM_NAME = document.getElementById("ITM_NAME").value;
		if(ITM_NAME == 0)
		{
			alert('Please Item Name');
			document.getElementById("ITM_NAME").focus();
			return false;
		}
		
		ITM_UNIT = document.getElementById("ITM_UNIT").value;
		if(ITM_UNIT == 0)
		{
			alert('Please Item Unit');
			document.getElementById("ITM_UNIT").focus();
			return false;
		}
		
		ITM_PRICE1 = document.getElementById("ITM_PRICE1").value;
		if(ITM_PRICE1 == 0)
		{
			alert('Please Item Price');
			document.getElementById("ITM_PRICE1").focus();
			return false;
		}
		
		ITM_VOLM1 = document.getElementById("ITM_VOLM1").value;
		if(ITM_VOLM1 == 0)
		{
			alert('Please Item Volume');
			document.getElementById("ITM_VOLM1").focus();
			return false;
		}
		
		isChek = 0;
		ISRENT	= document.getElementById("ISRENT").checked;
		if(ISRENT == true) isChek = isChek + 1;
		ISPART	= document.getElementById("ISPART").checked;
		if(ISPART == true) isChek = isChek + 1;
		ISFUEL	= document.getElementById("ISFUEL").checked;
		if(ISFUEL == true) isChek = isChek + 1;
		ISLUBRIC	= document.getElementById("ISLUBRIC").checked;
		if(ISLUBRIC == true) isChek = isChek + 1;
		ISFASTM	= document.getElementById("ISFASTM").checked;
		if(ISFASTM == true) isChek = isChek + 1;
		ISWAGE	= document.getElementById("ISWAGE").checked;
		if(ISWAGE == true) isChek = isChek + 1;
		
		if(isChek == 0)
		{
			alert('Please check one or more of Item Type');
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