<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 1 Agustus 2019
 * File Name	= v_budget_form.php
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

if($task == 'add')
{
	$myCount = $this->db->count_all('tbl_project_budg');
	$myMax = $myCount + 1;
	
	$lastPatternNumb 	= $myMax;
	$lastPatternNumb1 	= $myMax;
	$len = strlen($lastPatternNumb);
	
	$Pattern_Length		= 2;
	if($Pattern_Length==2)
	{
		if($len==1) $nol="0"; else $nol="";
	}
	
	$Pattern		= date('YmdHis');
	$lastPatternNumb = $nol.$lastPatternNumb;
	$DocNumber = "$Pattern-$lastPatternNumb";
	
	$PRJDATED 		= date('d/m/Y');
	$PRJEDAT 		= date('d/m/Y');
	$proj_addDate 	= date('d/m/Y');
	$proj_amountUSD	= 0;
	$PRJBOQ			= 0;
	$PRJRAP			= 0;
	$BASEAMOUNT		= 0;
	
	$PRJCODE 		= '';
	$PRJCODE_HO		= '';
	$PRJCNUM		= '';
	$PRJNAME 		= '';
	$PRJLOCT 		= '';
	$PRJOWN			= '';
	$PRJDATE 		= date('d/m/Y');
	$PRJEDAT 		= date('d/m/Y');
	$ENDDATE		= date('d/m/Y');
	$PRJDATE_CO		= date('d/m/Y');
	$PRJEDATD 		= date('d/m/Y');
	
	$PRJCOST 		= 0;
	$PRJLKOT 		= '';
	$PRJCBNG		= '';
	$PRJCURR		= 'IDR';
	$CURRRATE		= 1;
	$CURRRATEUSD	= 13000;
	$PRJSTAT 		= 0;
	$PRJNOTE		= '';
	$Patt_Number	= $lastPatternNumb1;
	
	$ISCHANGE		= 0; 	// 0. No, 1. Yes
	$REFCHGNO		= '';	// References No.
	$PRJCOST2		= 0;	// Nilai Kontrak Baru
	$CHGUSER		= $DefEmp_ID;
	$CHGSTAT		= 0;
	$PRJ_MNG		= '';
	$QTY_SPYR		= 0;
	$PRC_STRK		= '';
	$PRC_ARST		= '';
	$PRC_MKNK		= '';
	$PRC_ELCT		= '';
	$PRJCATEG		= 'SPL';
	$PRJ_IMGNAME	= "building.jpg";
	$isHO			= 0;				// 0 = Project 1 = Head Office
	$PRJPERIOD		= '';
}
else
{
	$DocNumber 			= $default['proj_Number'];	
	$proj_Number 		= $default['proj_Number'];
	$PRJCODE 			= $default['PRJCODE'];
	$PRJCODE_HO			= $default['PRJCODE_HO'];
	$PRJPERIOD 			= $default['PRJPERIOD'];
	$PRJCNUM			= $default['PRJCNUM'];
	$PRJNAME 			= $default['PRJNAME'];
	$PRJLOCT 			= $default['PRJLOCT'];
	$PRJCATEG 			= $default['PRJCATEG'];
	$PRJOWN 			= $default['PRJOWN'];
	$PRJDATE 			= $default['PRJDATE'];
	$PRJDATE_CO 		= $default['PRJDATE_CO'];
	$PRJEDAT 			= $default['PRJEDAT'];
	$ENDDATE			= $default['PRJEDAT'];
	$isHO				= $default['isHO'];
	
	$PRJDATED 		= date('d/m/Y', strtotime($PRJDATE));
	$PRJEDATD 		= date('d/m/Y', strtotime($PRJEDAT));
	$PRJDATE_CO 	= date('d/m/Y', strtotime($PRJDATE_CO));
	
	$sqlsp0		= "SELECT ENDDATE FROM tbl_projhistory WHERE PRJCODE = '$PRJCODE'";
	$sqlsp0R	= $this->db->query($sqlsp0)->result();
	foreach($sqlsp0R as $rowsp0) :
		$ENDDATE		= $rowsp0->ENDDATE;
		$ENDDATE 		= date("d/m/Y", strtotime($ENDDATE));
	endforeach;

	$PRJCOST 			= $default['PRJCOST'];	// NILAI KONTRAK
	$proj_amountUSD		= $default['PRJCOST'];
	$PRJBOQ				= $default['PRJBOQ'];	// NILAI KONTRAK	
	$PRJRAP				= $default['PRJRAP'];
	$PRJLKOT 			= $default['PRJLKOT'];
	$PRJCBNG			= $default['PRJCBNG'];
	$PRJCURR			= $default['PRJCURR'];
	$CURRRATE			= $default['CURRRATE'];
	$CURRRATEUSD		= $default['CURRRATE'];
	$PRJSTAT 			= $default['PRJSTAT'];
	$PRJNOTE			= $default['PRJNOTE'];
	$PRJ_MNG			= $default['PRJ_MNG'];
	$QTY_SPYR			= $default['QTY_SPYR'];
	$PRC_STRK			= $default['PRC_STRK'];
	$PRC_ARST			= $default['PRC_ARST'];
	$PRC_MKNK			= $default['PRC_MKNK'];
	$PRC_ELCT			= $default['PRC_ELCT'];
	$PRJ_IMGNAME		= $default['PRJ_IMGNAME'];
	$Patt_Year 			= $default['Patt_Year'];
	$Patt_Number 		= $default['Patt_Number'];
	$lastPatternNumb1	= $Patt_Number;
	
	if($PRJCURR == 'USD')
	{
		$BASEAMOUNT	= $proj_amountUSD * $CURRRATEUSD;
	}
	else
	{
		$BASEAMOUNT	= $PRJCOST;
	}
	
	$ISCHANGE		= $default['ISCHANGE']; // 0. No, 1. Yes
	$REFCHGNO		= $default['REFCHGNO'];	// References No.
	$PRJCOST2		= $default['PRJCOST2'];	// Nilai Kontrak Baru
	$CHGUSER		= $default['CHGUSER'];
	$CHGSTAT		= $default['CHGSTAT'];
}

if($PRJCURR == '')
{
	$PRJCURR = 'IDR';
}
$imgLoc		= base_url('assets/AdminLTE-2.0.5/project_image/budget_02.png');
if (!file_exists('assets/AdminLTE-2.0.5/project_image/'.$PRJCODE))
{
	$imgLoc			= base_url('assets/AdminLTE-2.0.5/project_image/building.jpg');
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/style.css'; ?>");</style>
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/styleZebra.css'; ?>");</style>
    <title><?php echo $appName; ?></title>
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
		
		if($TranslCode == 'Budgeting')$Budgeting = $LangTransl;
		if($TranslCode == 'BudNo')$BudNo = $LangTransl;
		if($TranslCode == 'BudCode')$BudCode = $LangTransl;
		if($TranslCode == 'ProjectName')$ProjectName = $LangTransl;
		if($TranslCode == 'StartDate')$StartDate = $LangTransl;
		if($TranslCode == 'CutOffDate')$CutOffDate = $LangTransl;
		if($TranslCode == 'EndDate')$EndDate = $LangTransl;
		if($TranslCode == 'CompanyCode')$CompanyCode = $LangTransl;
		if($TranslCode == 'ContractNo')$ContractNo = $LangTransl;
		if($TranslCode == 'projectCateg')$projectCateg = $LangTransl;
		if($TranslCode == 'ProjectOwner')$ProjectOwner = $LangTransl;
		if($TranslCode == 'Amount')$Amount = $LangTransl;
		if($TranslCode == 'Location')$Location = $LangTransl;
		if($TranslCode == 'OutofTownProject')$OutofTownProject = $LangTransl;
		if($TranslCode == 'Compailer')$Compailer = $LangTransl;
		if($TranslCode == 'SurveyorQty')$SurveyorQty = $LangTransl;
		if($TranslCode == 'Notes')$Notes = $LangTransl;
		if($TranslCode == 'PlannedBy')$PlannedBy = $LangTransl;
		if($TranslCode == 'Structure')$Structure = $LangTransl;
		if($TranslCode == 'Architect')$Architect = $LangTransl;
		if($TranslCode == 'Mechanical')$Mechanical = $LangTransl;
		if($TranslCode == 'Electrical')$Electrical = $LangTransl;
		if($TranslCode == 'ProjectStatus')$ProjectStatus = $LangTransl;
		if($TranslCode == 'New')$New = $LangTransl;
		if($TranslCode == 'Approve')$Approve = $LangTransl;
		if($TranslCode == 'Save')$Save = $LangTransl;
		if($TranslCode == 'Update')$Update = $LangTransl;
		if($TranslCode == 'Cancel')$Cancel = $LangTransl;
		if($TranslCode == 'Active')$Active = $LangTransl;
		if($TranslCode == 'Inactive')$Inactive = $LangTransl;
		if($TranslCode == 'Project')$Project = $LangTransl;
		if($TranslCode == 'BudgTotal')$BudgTotal = $LangTransl;
		if($TranslCode == 'Target')$Target = $LangTransl;
		if($TranslCode == 'Margin')$Margin = $LangTransl;
		if($TranslCode == 'AboutProject')$AboutProject = $LangTransl;
		if($TranslCode == 'Description')$Description = $LangTransl;
		if($TranslCode == 'Information')$Information = $LangTransl;
		if($TranslCode == 'Picture')$Picture = $LangTransl;
		if($TranslCode == 'Add')$Add = $LangTransl;
		if($TranslCode == 'None')$None = $LangTransl;
		if($TranslCode == 'Code')$Code = $LangTransl;
		if($TranslCode == 'ProjectName')$ProjectName = $LangTransl;
		if($TranslCode == 'ChooseFile')$ChooseFile = $LangTransl;
		if($TranslCode == 'UploadDoc')$UploadDoc = $LangTransl;
		if($TranslCode == 'DetInfo')$DetInfo = $LangTransl;
		if($TranslCode == 'important')$important = $LangTransl;
		if($TranslCode == 'PeriodeCode')$PeriodeCode = $LangTransl;
	endforeach;
	$urlUpdDoc		= site_url('c_comprof/c_bUd93tL15t/do_upload/?id='.$this->url_encryption_helper->encode_url($appName));
	
	if($LangID == 'IND')
	{
		$Yes	= "Ya";
		$No		= "Bukan";
	}
	else
	{
		$Yes	= "Yes";
		$No		= "No";
	}
?>

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->

<section class="content-header">
<h1>
    <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/project.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $Add; ?>
    <small><?php echo $Budgeting; ?></small>
  </h1>
  <?php /*?><ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#">Tables</a></li>
    <li class="active">Data tables</li>
  </ol><?php */?>
</section>
<style>
	.search-table, td, th {
		border-collapse: collapse;
	}
	.search-table-outter { overflow-x: scroll; }
</style>
<!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-3">
          	<!-- Profile Image -->
          	<div class="box box-primary">
                <div class="box-body box-profile">
                    <img class="profile-user-img img-responsive img-circle" src="<?php echo $imgLoc; ?>" style="height:150px; width:150px" alt="User profile picture">
                    <h3 class="profile-username text-center"><?php echo "$PRJNAME"; ?></h3>                    
                    <p class="text-muted text-center"><?php echo $PRJLOCT; ?></p>
                    <ul class="list-group list-group-unbordered">
                    	<li class="list-group-item">
                    		<b><?php echo $BudgTotal; ?></b> <a class="pull-right"><?php print number_format($PRJBOQ, 0); ?></a>
                    	</li>
                    	<li class="list-group-item">
                    		<b><?php echo "RAB"; ?></b> <a class="pull-right"><?php print number_format($PRJRAP, 0); ?></a>
                    	</li>
                        <?php
							if($PRJBOQ == '')
								$PRJBOQ = $PRJCOST;
								
                        	$TargetFee	= $PRJBOQ - $PRJRAP;
						?>
                    	<li class="list-group-item">
                    		<b><?php echo "$Target $Margin"; ?></b> <a class="pull-right"><?php print number_format($TargetFee, 0); ?></a>
                    	</li>
                    </ul>
                    <a href="#" class="btn btn-primary btn-block" style="display:none"><b>Follow</b></a>
                </div>
          	</div>
        </div>
        <div class="col-md-9">
			<div class="nav-tabs-custom">
            	<ul class="nav nav-tabs">
                	<li class="active"><a href="#settings" data-toggle="tab"><?php echo $Information; ?></a></li> 		<!-- Tab 1 -->
                    <li><a href="#profPicture" data-toggle="tab" style="display:none"><?php echo $Picture; ?></a></li>	<!-- Tab 2 -->
                </ul>
                <!-- Biodata -->
                <div class="tab-content">
                    <div class="active tab-pane" id="settings">
                        <form class="form-horizontal" name="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return validateInData()">
                            <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
                            <input type="hidden" name="rowCount" id="rowCount" value="0">
                            
                            <input type="hidden" name="PRJCNUM" id="PRJCNUM" value="">
                            <input type="hidden" name="PRJCATEG" id="PRJCATEG" value="SPL">
                            <input type="hidden" name="isHO" id="isHO" value="0">
                            <input type="hidden" name="PRJCODE" id="PRJCODE" value="<?php echo $PRJCODE; ?>">
                            <input type="hidden" name="PRJOWN" id="PRJOWN" value="<?php echo $PRJOWN; ?>">
                            <input type="hidden" name="PRJCATEG" id="PRJCATEG" value="SPL">
                            <div class="box box-primary">
                                <br>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-3 control-label"><?php echo $BudNo?></label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="PRJNUM1" id="PRJNUM1" value="<?php echo $DocNumber; ?>" disabled>
                                        <input type="hidden" name="Patt_Number" id="Patt_Number" value="<?php echo $Patt_Number; ?>">
                                        <input type="hidden" name="proj_Number" id="proj_Number" value="<?php echo $DocNumber; ?>" >
                                        <input type="hidden"  id="lastPatternNumb" name="lastPatternNumb" size="20" value="<?php echo $lastPatternNumb1; ?>" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-3 control-label"><?php echo $CompanyCode; ?></label>
                                    <div class="col-sm-9">
                                        <select name="PRJCODE_HO" id="PRJCODE_HO" class="form-control select2">
                                            <option value="none">--- None ---</option>
                                            <?php
                                                $PRJ_Code 	= '';
                                                $CountPRJ 	= $this->db->count_all('tbl_project');
                                                $sqlPRJ 	= "SELECT PRJCODE, PRJNAME
																FROM tbl_project WHERE PRJSTAT = 1 AND BUDG_LEVEL = 0";
                                                $resultPRJ = $this->db->query($sqlPRJ)->result();
                                                if($CountPRJ > 0)
                                                {
                                                    foreach($resultPRJ as $rowPRJ) :
                                                        $PRJCODE1 = $rowPRJ->PRJCODE;
                                                        $PRJNAME1 = $rowPRJ->PRJNAME;
														$sqlCOA	= "tbl_chartaccount WHERE PRJCODE = '$PRJCODE1'";
														$resCOA = $this->db->count_all($sqlCOA);
                                                        ?>
                                                        <option value="<?php echo $PRJCODE1; ?>" <?php if($PRJCODE1 == $PRJCODE_HO) { ?>selected <?php } ?>> <?php echo $PRJNAME1; ?> </option>
                                                        <?php
													endforeach;
                                                 }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-3 control-label"><?php echo $BudCode?></label>
                                    <div class="col-sm-9">
                                        <label>
                                            <input type="text" class="form-control" name="PRJPERIOD" id="PRJPERIOD" placeholder="<?php echo $PeriodeCode; ?>" value="<?php echo $PRJPERIOD; ?>" maxlength="20" onChange="functioncheck(this.value)" <?php if($task == 'edit') { ?> readonly <?php } ?>>
                                        </label><label>&nbsp;&nbsp;</label><label id="isHidden"></label>
                                        <input type="hidden" name="CheckThe_Code" id="CheckThe_Code" value="" size="20" maxlength="25" >
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Description?></label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="PRJNAME" id="PRJNAME" value="<?php echo $PRJNAME; ?>" placeholder="<?php echo $Description; ?>" >
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-3 control-label"><?php echo $StartDate ?></label>
                                    <div class="col-sm-9">
                                        <div class="input-group date">
                                            <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
                                            <input type="text" name="PRJDATE" class="form-control pull-left" id="datepicker1" value="<?php echo $PRJDATED; ?>" style="width:100px">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-3 control-label"><?php echo $EndDate; ?></label>
                                    <div class="col-sm-9">
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>&nbsp;</div><input type="text" name="PRJEDAT" class="form-control pull-left" id="datepicker2" value="<?php echo $PRJEDATD; ?>" style="width:100px"></div>
                                    </div>
                                </div>
                                <div class="form-group" style="display:none">
                                    <label for="inputName" class="col-sm-3 control-label"><?php echo $CutOffDate; ?></label>
                                    <div class="col-sm-9">
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>&nbsp;</div><input type="text" name="PRJDATE_CO" class="form-control pull-left" id="datepicker3" value="<?php echo $PRJDATE_CO; ?>" style="width:150px"></div>
                                    </div>
                                </div>
                                <script>
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
                                                    document.getElementById("isHidden").innerHTML = ' Project Code already exist ... !';
                                                    document.getElementById("isHidden").style.color = "#ff0000";
                                                    document.getElementById("btnSave").style.display = "none";
                                                }
                                                else
                                                {
                                                    document.getElementById('CheckThe_Code').value= recordcount;
                                                    document.getElementById("isHidden").innerHTML = ' Project Code : OK .. !';
                                                    document.getElementById("isHidden").style.color = "green";
                                                    document.getElementById("btnSave").style.display = "";
                                                }
                                            }
                                        }
                                        var PRJPERIOD = document.getElementById('PRJPERIOD').value;
										
                                        ajaxRequest.open("GET", "<?php echo base_url().'index.php/c_comprof/c_bUd93tL15t/getTheCode/';?>" + PRJPERIOD, true);
                                        ajaxRequest.send(null);
                                    }
                                </script>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Amount; ?></label>
                                    <div class="col-sm-9">
                                        <label>
                                            <?php if($PRJCURR == 'IDR')
                                            { 
                                                ?>
                                                <input type="hidden" name="PRJCOST" id="PRJCOST" value="<?php echo $PRJCOST; ?>"  size="10" style="text-align:right" onKeyPress="return isIntOnlyNew(event);">
                                                <input type="text" class="form-control" name="PRJCOST1" id="PRJCOST1" value="<?php print number_format($PRJCOST, $decFormat); ?>" style="max-width:145px;text-align:right" onKeyPress="return isIntOnlyNew(event);" onChange="checkdecimal();" >
                                                <?php
                                            }
                                            ?>
                                        </label>
                                    </div>
                                </div>
                                <script>                        
                                    function checkdecimal()
                                    {
                                        var decFormat	= document.getElementById('decFormat').value;
                                        PRJCOST = eval(document.getElementById('PRJCOST1')).value.split(",").join("");
                                        document.getElementById('PRJCOST').value = PRJCOST;
                                        document.getElementById('PRJCOST1').value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PRJCOST)),decFormat));
                                    }
                                </script>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Compailer ?></label>
                                    <div class="col-sm-9">
                                        <select name="PRJ_MNG[]" id="PRJ_MNG" class="form-control select2" multiple="multiple" data-placeholder="&nbsp;&nbsp;&nbsp;<?php echo $Compailer ?>">
                                            <?php
                                                $sqlEmp	= "SELECT Emp_ID, First_Name, Last_Name, Email
                                                            FROM tbl_employee ORDER BY First_Name";
                                                $sqlEmp	= $this->db->query($sqlEmp)->result();
                                                foreach($sqlEmp as $row) :
                                                    $Emp_ID		= $row->Emp_ID;
                                                    $First_Name	= $row->First_Name;
                                                    $Last_Name	= $row->Last_Name;
                                                    $Email		= $row->Email;
                                                    ?>
                                                        <option value="<?php echo "$Emp_ID"; ?>" <?php if($PRJ_MNG == $Emp_ID) { ?> selected <?php } ?>>
                                                            <?php echo "$First_Name $Last_Name"; ?>
                                                        </option>
                                                    <?php
                                                endforeach;
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Notes ?></label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control" name="PRJNOTE"  id="PRJNOTE" style="height:70px" placeholder="<?php echo $Notes; ?>"><?php echo set_value('PRJNOTE', isset($default['PRJNOTE']) ? $default['PRJNOTE'] : ''); ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-3 control-label">Status</label>
                                    <div class="col-sm-9">
                                        <select name="PRJSTAT" id="PRJSTAT" class="form-control select2" style="max-width:120px">
                                            <option value="1" <?php if($PRJSTAT == 1) { ?> selected <?php } ?>><?php echo $Active; ?></option>
                                            <option value="0" <?php if($PRJSTAT == 0) { ?> selected <?php } ?>><?php echo $Inactive; ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-3 control-label">&nbsp;</label>
                                    <div class="col-sm-9">
                                        <div class="alert alert-danger alert-dismissible">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                            <h4><i class="icon fa fa-info"></i> <?php echo $important; ?>!</h4>
                                            Jika Anda mengaktifkan salah satu Anggaran, maka secara otomatis sistem akan menonaktifkan anggaran yang lainnya. Sehingga, semua transaksi akan menggunakan anggaran yang Anda aktifkan.
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-3 control-label">&nbsp;</label>
                                    <div class="col-sm-9">
                                        <?php
											if($ISCREATE == 1)
											{
												if($task=='add')
												{
													?>
														<button class="btn btn-primary" id="btnSave">
														<i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Save; ?>
														</button>&nbsp;
													<?php
												}
												else
												{
													?>
														<button class="btn btn-primary" id="btnSave">
														<i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Update; ?>
														</button>&nbsp;
													<?php
												}
											}
											echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;'.$Cancel.'</button>');
										?>
                                    </div>
                                </div><br>
                            </div>
                        </form>
                    </div>
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
							
							var PRJDATE = new Date(document.frm.PRJDATE.value);
							
							var PRJEDAT = new Date(document.frm.PRJEDAT.value);
							
							if(PRJEDAT < PRJDATE)
							{
								alert('End Date Project must be Greater than Start Date Project.');
								return false;
							}
						}
					</script>
				</div>
			</div>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

    </section>
    <!-- /.content -->
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
	$.fn.datepicker.defaults.format = "dd/mm/yyyy";
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
		
	function isIntOnlyNew(evt)
	{
		if (evt.which){ var charCode = evt.which; }
		else if(document.all && event.keyCode){ var charCode = event.keyCode; }
		else { return true; }
		return ((charCode == 45) || (charCode == 46) || (charCode == 8) || (charCode >= 48) && (charCode <= 57));
	}
</script>
<?php 
$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>