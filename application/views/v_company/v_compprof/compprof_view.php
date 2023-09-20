<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 5 Juli 2019
 * File Name	= compprof_view.php
 * Location		= -
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$FlagUSER 	= $this->session->userdata['FlagUSER'];
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
$appBody    = $this->session->userdata['appBody'];
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

$this->db->select('Display_Rows,decFormat,CompDesc');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows 	= $row->Display_Rows;
	$decFormat 		= $row->decFormat;
	$CompD			= $row->CompDesc;
endforeach;

$decFormat		= 2;

$DocNumber 			= $default['proj_Number'];	
$proj_Number 		= $default['proj_Number'];
$PRJCODEBEF         = $default['PRJCODE'];
$PRJCODE            = $default['PRJCODE'];
$PRJCNUM			= $default['PRJCNUM'];
$PRJNAME 			= $default['PRJNAME'];
$PRJLOCT 			= $default['PRJLOCT'];
$PRJTELP 			= $default['PRJTELP'];
$PRJFAX 			= $default['PRJFAX'];
$PRJMAIL 			= $default['PRJMAIL'];
$PRJADD				= $default['PRJADD'];
$PRJCATEG           = $default['PRJCATEG'];
$PRJSCATEG          = $default['PRJSCATEG'];
$PRJOWN 			= $default['PRJOWN'];
$PRJDATE 			= $default['PRJDATE'];
$PRJDATE_CO 		= $default['PRJDATE_CO'];
$PRJEDAT 			= $default['PRJEDAT'];
$ENDDATE			= $default['PRJEDAT'];
$isHO				= $default['isHO'];

$PRJDATEA 		= date("Y", strtotime($PRJDATE));
$PRJDATEB 		= date("m", strtotime($PRJDATE));
$PRJDATEC 		= date("d", strtotime($PRJDATE));
$PRJDATED 		= date('d/m/Y', strtotime($PRJDATE));

$PRJEDATA 		= date("Y", strtotime($PRJEDAT));
$PRJEDATB 		= date("m", strtotime($PRJEDAT));
$PRJEDATC 		= date("d", strtotime($PRJEDAT));
$PRJEDATD 		= date('d/m/Y', strtotime($PRJEDAT));
$ENDDATED		= $PRJEDAT;

$PRJDATE_COA 	= date("Y", strtotime($PRJDATE_CO));
$PRJDATE_COB 	= date("m", strtotime($PRJDATE_CO));
$PRJDATE_COC 	= date("d", strtotime($PRJDATE_CO));
$PRJDATE_CO 	= date('d/m/Y', strtotime($PRJDATE_CO));

$sqlsp0		= "SELECT ENDDATE FROM tbl_projhistory WHERE PRJCODE = '$PRJCODE'";
$sqlsp0R	= $this->db->query($sqlsp0)->result();
foreach($sqlsp0R as $rowsp0) :
	$ENDDATE		= $rowsp0->ENDDATE;
	$ENDDATE 		= date("m/d/Y", strtotime($ENDDATE));
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

if($PRJDATE == '0000-00-00')
{
	$sqlX = "SELECT PRJDATE
			FROM project WHERE PRJCODE = '$PRJCODE'";
	$result = $this->db->query($sqlX)->result();
	foreach($result as $rowx) :
		$PRJDATE		= $rowx->PRJDATE;
	endforeach;
	if($PRJDATE == '0000-00-00')
	{
		$PRJDATEA 	= date('Y');
		$PRJDATEB 	= date('m');
		$PRJDATEC 	= date('d');
		$PRJDATE 	= "$PRJDATEB/$PRJDATEC/$PRJDATEA";
	}
}

if($PRJEDAT == '0000-00-00')
{
	$PRJEDAT 		= $PRJDATE;
}

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

if($PRJCURR == '')
{
	$PRJCURR = 'IDR';
}
$imgLoc		= base_url('assets/AdminLTE-2.0.5/project_image/building.jpg');
$imgLoc		= base_url('assets/AdminLTE-2.0.5/project_image/'.$PRJCODE.'/'.$PRJ_IMGNAME);
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
        <title><?php echo $appName; ?></title>
        <!-- Tell the browser to be responsive to screen width -->
        <?php
          $vers     = $this->session->userdata['vers'];

          $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'css' AND isAct = 1 AND cssjs_vers IN ('$vers', 'All')";
          $rescss = $this->db->query($sqlcss)->result();
          foreach($rescss as $rowcss) :
              $cssjs_lnk  = $rowcss->cssjs_lnk;
              ?>
                  <link rel="stylesheet" href="<?php echo base_url($cssjs_lnk) ?>">
              <?php
          endforeach;

          $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'jss' AND isAct = 1 AND cssjs_vers IN ('$vers', 'All')";
          $rescss = $this->db->query($sqlcss)->result();
          foreach($rescss as $rowcss) :
              $cssjs_lnk1  = $rowcss->cssjs_lnk;
              ?>
                  <script src="<?php echo base_url($cssjs_lnk1) ?>"></script>
              <?php
          endforeach;
        ?>

        <!-- Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    </head>

    <?php
        $this->load->view('template/mna');
    	//______$this->load->view('template/topbar');
    	//______$this->load->view('template/sidebar');
    	
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
    		
    		if($TranslCode == 'UniqCode')$UniqCode = $LangTransl;
    		if($TranslCode == 'Code')$Code = $LangTransl;
    		if($TranslCode == 'Name')$Name = $LangTransl;
    		if($TranslCode == 'Date')$Date = $LangTransl;
    		if($TranslCode == 'CutOffDate')$CutOffDate = $LangTransl;
    		if($TranslCode == 'EndDate')$EndDate = $LangTransl;
    		if($TranslCode == 'ContractNo')$ContractNo = $LangTransl;
    		if($TranslCode == 'projectCateg')$projectCateg = $LangTransl;
            if($TranslCode == 'typeOfBuss')$typeOfBuss = $LangTransl;
    		if($TranslCode == 'ProjectOwner')$ProjectOwner = $LangTransl;
    		if($TranslCode == 'Amount')$Amount = $LangTransl;
    		if($TranslCode == 'Location')$Location = $LangTransl;
    		if($TranslCode == 'Address')$Address = $LangTransl;
    		if($TranslCode == 'TelpNo')$TelpNo = $LangTransl;
    		if($TranslCode == 'OutofTownProject')$OutofTownProject = $LangTransl;
    		if($TranslCode == 'Owner')$Owner = $LangTransl;
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
    		if($TranslCode == 'ProjectCost')$ProjectCost = $LangTransl;
    		if($TranslCode == 'Target')$Target = $LangTransl;
    		if($TranslCode == 'Margin')$Margin = $LangTransl;
    		if($TranslCode == 'AboutProject')$AboutProject = $LangTransl;
    		if($TranslCode == 'Information')$Information = $LangTransl;
    		if($TranslCode == 'Picture')$Picture = $LangTransl;
    		if($TranslCode == 'Add')$Add = $LangTransl;
    		if($TranslCode == 'None')$None = $LangTransl;
    		if($TranslCode == 'Code')$Code = $LangTransl;
    		if($TranslCode == 'Name')$Name = $LangTransl;
    		if($TranslCode == 'ChooseFile')$ChooseFile = $LangTransl;
    		if($TranslCode == 'UploadDoc')$UploadDoc = $LangTransl;
    		if($TranslCode == 'CompanyInfo')$CompanyInfo = $LangTransl;
    		if($TranslCode == 'compDesc')$compDesc = $LangTransl;
            if($TranslCode == 'typofBEmpy')$typofBEmpy = $LangTransl;
            if($TranslCode == 'officeNmEmpty')$officeNmEmpty = $LangTransl;
            if($TranslCode == 'CompanyName')$CompanyName = $LangTransl;
            if($TranslCode == 'Contact')$Contact = $LangTransl;
    	endforeach;
    	$urlUpdDoc		= site_url('c_comprof/c_30Mp70f/do_upload/?id='.$this->url_encryption_helper->encode_url($appName));
    	$urlUpdDesc		= site_url('c_comprof/c_30Mp70f/up_desc/?id='.$this->url_encryption_helper->encode_url($appName));
    	
    	if($LangID == 'IND')
    	{
    		$Yes	= "Ya";
    		$No		= "Bukan";
            $kontr  = "Kontraktor";
            $manuf  = "Manufaktur";
            $trad   = "Perdagangan";
    	}
    	else
    	{
    		$Yes	= "Yes";
    		$No		= "No";
            $kontr  = "Contractor";
            $manuf  = "Manufacture";
            $trad   = "Trading";
    	}
    ?>

    <style>
        .search-table, td, th {
            border-collapse: collapse;
        }
        .search-table-outter { overflow-x: scroll; }
    </style>
    <?php

        $comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
        <section class="content-header">
            <h1>
                <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/project.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $MenuName; ?>
                <small><?php //echo $Project; ?></small>
              </h1>
              <?php /*?><ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="#">Tables</a></li>
                <li class="active">Data tables</li>
              </ol><?php */?>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-md-3">
                  	<!-- Profile Image -->
                  	<div class="box box-primary">
                        <div class="box-body box-profile">
                            <img class="profile-user-img img-responsive img-circle" src="<?php echo $imgLoc; ?>" style="height:150px; width:150px" alt="User profile picture">
                            <h3 class="profile-username text-center"><br><?php echo "$PRJNAME"; ?></h3>                    
                            <p class="text-muted text-center"> <div style="word-break: break-all; text-align: center;"><?php echo $PRJADD; ?></div></p>
                        </div>
                  	</div>

                  <!-- About Me Box -->
                  	<div class="box box-primary">
                        <div class="box-header with-border">
                        	<h3 class="box-title"><?php echo $CompanyInfo; ?></h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                        	<strong><i class="fa fa-phone margin-r-5"></i> <?php echo $TelpNo; ?></strong>
                        	<p class="text-muted">
                        		<em><?php echo $PRJTELP; ?></em>
                        	</p>
                       		<hr>
                            <strong><i class="fa fa-fax margin-r-5"></i> Fax No.</strong>
                            <p class="text-muted"><?php echo $PRJFAX; ?></p>
                        	<hr>
                            <strong><i class="fa fa-envelope margin-r-5"></i> E-mail</strong>
                            <p class="text-muted"><?php echo $PRJMAIL; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
        			<div class="nav-tabs-custom">
                    	<ul class="nav nav-tabs">
                        	<li class="active"><a href="#settings" data-toggle="tab" onclick="showTab(1);"><?php echo $Information; ?></a></li> 		<!-- Tab 1 -->
                            <li><a href="#profPicture" data-toggle="tab" onclick="showTab(2);"><?php echo $Picture; ?></a></li>						<!-- Tab 2 -->
                            <li style="display: none;"><a href="#profNotes" data-toggle="tab" onclick="showTab(3);"><?php echo $compDesc; ?></a></li>						<!-- Tab 3 -->
                        </ul>
                        <!-- Biodata -->
                        <div class="tab-content">
                            <div class="active tab-pane" id="settings">
                                <form class="form-horizontal" name="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return validateInData()">
                                    <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
                                    <input type="Hidden" name="rowCount" id="rowCount" value="0">
                                    <div class="box box-primary">
                                        <div class="box-header with-border">
                                            <h3 class="box-title"><?php echo $CompanyInfo; ?></h3>
                                        </div>
                                        <br>
                                        <div class="form-group" style="display: none;">
                                            <label for="inputName" class="col-sm-2 control-label"><?php echo $UniqCode?></label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="PRJNUM1" id="PRJNUM1" value="<?php echo $DocNumber; ?>" disabled>
                                                <input type="hidden" name="proj_Number" id="proj_Number" value="<?php echo $DocNumber; ?>" >
                                            </div>
                                        </div>
                                        <?php
        									// CEK TRX
        									$sqlTRX	= "tbl_chartaccount WHERE PRJCODE = '$PRJCODE'";
        									$resTRX	= $this->db->count_all($sqlTRX);
        								?>
                                        <div class="form-group">
                                            <label for="inputName" class="col-sm-2 control-label"><?php echo $Code?></label>
                                            <div class="col-sm-4">
                                                <?php if($resTRX == 0) { ?>
                                                    <input type="text" class="form-control" name="PRJCODE" id="PRJCODE" value="<?php echo $PRJCODE; ?>">
                                                    <input type="hidden" class="form-control" name="PRJCODEBEF" id="PRJCODEBEF" value="<?php echo $PRJCODEBEF; ?>">
                                                <?php } else { ?>
                                                    <input type="hidden" class="form-control" name="PRJCODE" id="PRJCODE" value="<?php echo $PRJCODE; ?>">
                                                    <input type="hidden" class="form-control" name="PRJCODEBEF" id="PRJCODEBEF" value="<?php echo $PRJCODEBEF; ?>">
                                                    <input type="text" class="form-control" name="PRJCODE1" id="PRJCODE1" value="<?php echo $PRJCODE; ?>" disabled>
                                                <?php } ?>
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control" name="PRJNAME" id="PRJNAME" size="50" value="<?php echo $default['PRJNAME']; ?>" placeholder="<?php echo $CompanyName?>">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputName" class="col-sm-2 control-label"><?php echo $Date ?></label>
                                            <div class="col-sm-4">
                                                <div class="input-group date">
                                                    <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
                                                    <input type="text" name="PRJDATE" class="form-control pull-left" id="datepicker1" value="<?php echo $PRJDATED; ?>" style="width:120px">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control" name="PRJCNUM" id="PRJCNUM" value="<?php echo $PRJCNUM; ?>" placeholder="<?php echo $ContractNo?>" >
                                            </div>
                                        </div>
                                        <div class="form-group" style="display:none">
                                            <label for="inputName" class="col-sm-2 control-label"><?php echo $EndDate; ?></label>
                                            <div class="col-sm-10">
                                                <div class="input-group date">
                                                    <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>&nbsp;</div><input type="text" name="PRJEDAT" class="form-control pull-left" id="datepicker2" value="<?php echo $PRJEDATD; ?>" style="width:120px"></div>
                                            </div>
                                        </div>
                                        <div class="form-group" style="display: none;">
                                            <label for="inputName" class="col-sm-2 control-label"><?php echo $CutOffDate; ?></label>
                                            <div class="col-sm-10">
                                                <div class="input-group date">
                                                    <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>&nbsp;</div><input type="text" name="PRJDATE_CO" class="form-control pull-left" id="datepicker3" value="<?php echo $PRJDATE_CO; ?>" style="width:120px"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputName" class="col-sm-2 control-label"><?php echo $Contact; ?></label>
                                            <div class="col-sm-3">
                                                <input type="text" class="form-control" name="PRJTELP" id="PRJTELP" value="<?php echo $PRJTELP; ?>" placeholder="<?php echo $TelpNo?>">
                                            </div>
                                            <div class="col-sm-3">
                                                <input type="text" class="form-control" name="PRJFAX" id="PRJFAX" value="<?php echo $PRJFAX; ?>" placeholder="Fax No.">
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="email" class="form-control" name="PRJMAIL" id="PRJMAIL" placeholder="Email" value="<?php echo "$PRJMAIL"; ?>">
                                            </div>
                                        </div>
                                        <div class="form-group" style="display:none">
                                            <label for="inputName" class="col-sm-2 control-label"><?php echo $ProjectOwner; ?></label>
                                            <div class="col-sm-10">
                                                <select name="PRJOWN" id="PRJOWN" class="form-control select2" style="max-width:350px">
                                                    <option value="none">--- None ---</option>
                                                    <?php
                                                        $own_Code 	= '';
                                                        $CountOwn 	= $this->db->count_all('tbl_owner');
                                                        $sqlOwn 	= "SELECT own_Code, own_Title, own_Name 
        																FROM tbl_owner WHERE own_Status = 1
        																UNION ALL
        																SELECT CUST_CODE AS own_Code, '' AS own_Title,
        																	CUST_DESC AS own_Name 
        																FROM tbl_customer WHERE CUST_STAT = 1";
                                                        $resultOwn = $this->db->query($sqlOwn)->result();
                                                        if($CountOwn > 0)
                                                        {
                                                            foreach($resultOwn as $rowOwn) :
                                                                $own_Title = $rowOwn->own_Title;
                                                                $own_Code = $rowOwn->own_Code;
                                                                $own_Name = $rowOwn->own_Name;
                                                                ?>
                                                                <option value="<?php echo $own_Code; ?>" <?php if($own_Code == $PRJOWN) { ?>selected <?php } ?>> <?php echo $own_Name; if($own_Title != '') { echo", $own_Title"; } ?> </option>
                                                                <?php
                                                             endforeach;
                                                             }
                                                             else
                                                             {
                                                                ?>
                                                                <option value="none">--- None ---</option>
                                                                <?php
                                                         }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group" style="display:none">
                                            <label for="inputName" class="col-sm-2 control-label"><?php echo $Amount; ?> (Non PPn)</label>
                                            <div class="col-sm-10">
                                                <?php /*?><label>
                                                    <select name="PRJCURR" id="PRJCURR" class="form-control" onChange="selPRJCURR(this.value)" style="max-width:80px">
                                                        <option value="IDR" <?php if($PRJCURR == 'IDR') { ?>selected <?php } ?>>IDR</option>
                                                        <option value="USD" <?php if($PRJCURR == 'USD') { ?>selected <?php } ?> style="display:none">USD</option>
                                                    </select>
                                                </label>&nbsp;&nbsp;<?php */?>
                                                <label>
                                                    <?php if($PRJCURR == 'IDR')
                                                    { 
                                                        ?>
                                                        <input type="hidden" name="PRJCOST" id="PRJCOST" value="<?php echo $PRJCOST; ?>"  size="10" style="text-align:right" onKeyPress="return isIntOnlyNew(event);">
                                                        <input type="text" class="form-control" name="PRJCOST1" id="PRJCOST1" value="<?php print number_format($PRJCOST, $decFormat); ?>" style="max-width:170px;text-align:right" onKeyPress="return isIntOnlyNew(event);" onChange="checkdecimal();" >
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
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="inputName" class="col-sm-4 control-label"><?php echo $Location ?></label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control" name="PRJLOCT" id="PRJLOCT" value="<?php echo $PRJLOCT; ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="inputName" class="col-sm-4 control-label"><?php echo $Owner ?></label>
                                                    <div class="col-sm-8">
                                                        <select name="PRJ_MNG[]" id="PRJ_MNG" class="form-control select2" multiple="multiple" data-placeholder="&nbsp;&nbsp;&nbsp;<?php echo $Owner ?>">
                                                            <?php
                                                                /*$sqlEmp   = "SELECT Emp_ID, First_Name, Last_Name, Email
                                                                            FROM tbl_employee WHERE Pos_Code LIKE 'PM%' ORDER BY First_Name";*/
                                                                $sqlEmp = "SELECT Emp_ID, First_Name, Last_Name, Email
                                                                            FROM tbl_employee ORDER BY First_Name";
                                                                $sqlEmp = $this->db->query($sqlEmp)->result();
                                                                foreach($sqlEmp as $row) :
                                                                    $Emp_ID     = $row->Emp_ID;
                                                                    $First_Name = $row->First_Name;
                                                                    $Last_Name  = $row->Last_Name;
                                                                    $Email      = $row->Email;
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
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <div class="col-sm-12">
                                                        <textarea class="form-control" name="PRJADD"  id="PRJADD" style="height:85px" placeholder="<?php echo $Address; ?>"><?php echo $PRJADD; ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group" style="display:none">
                                            <label for="inputName" class="col-sm-2 control-label"><?php echo $OutofTownProject ?></label>
                                            <div class="col-sm-10">
                                                <select name="PRJLKOT" id="PRJLKOT" class="form-control" style="max-width:70px">
                                                    <option value="0" <?php if($PRJLKOT == 0) { ?> selected <?php } ?>>No</option>
                                                    <option value="1" <?php if($PRJLKOT == 1) { ?> selected <?php } ?>>Yes</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group" style="display:none">
                                            <label for="inputName" class="col-sm-2 control-label"><?php echo $projectCateg; ?></label>
                                            <div class="col-sm-10">
                                                <select name="PRJCATEG" id="PRJCATEG" class="form-control select2" style="max-width:350px; max-width:100px">
                                                    <option value="">--- none ---</option>
                                                    <option value="SPL" <?php if($PRJCATEG == 'SPL') { ?> selected <?php } ?>>Sipil</option>
                                                    <option value="GDG" <?php if($PRJCATEG == 'GDG') { ?> selected <?php } ?>>Gedung</option>
                                                </select>
                                            </div>
                                        </div>
                                        <?php

                                        ?>
                                        <?php if($DefEmp_ID == 'D15040004221') { ?>
                                            <div class="form-group">
                                                <label for="inputName" class="col-sm-2 control-label"><?php echo $typeOfBuss; ?></label>
                                                <div class="col-sm-10">
                                                    <select name="PRJSCATEG" id="PRJSCATEG" class="form-control select2">
                                                        <option value="0" > --- </option>
                                                        <option value="1" <?php if($PRJSCATEG == 1) { ?> selected <?php } ?>><?php echo $kontr; ?></option>
                                                        <option value="2" <?php if($PRJSCATEG == 2) { ?> selected <?php } ?>><?php echo $manuf; ?></option>
                                                        <option value="3" <?php if($PRJSCATEG == 3) { ?> selected <?php } ?>><?php echo $trad; ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                        <?php } else { ?>
                                            <input type="hidden" class="form-control" name="PRJSCATEG" id="PRJSCATEG" value="<?php echo $PRJSCATEG; ?>">
                                        <?php } ?>
                                        <div class="form-group" style="display: none;">
                                            <label for="inputName" class="col-sm-2 control-label">is Project ... ?</label>
                                            <div class="col-sm-10">
                                                <select name="isHO" id="isHO" class="form-control select2" style="max-width:350px; max-width:100px">
                                                    <option value="0" <?php if($isHO == 0) { ?> selected <?php } ?>><?php echo $Yes; ?></option>
                                                    <option value="1" <?php if($isHO == 1) { ?> selected <?php } ?>><?php echo $No; ?></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputName" class="col-sm-2 control-label"><?php echo $Notes ?></label>
                                            <div class="col-sm-10">
                                                <textarea class="form-control" name="PRJNOTE"  id="PRJNOTE" style="height:70px"><?php echo $PRJNOTE; ?></textarea>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <button class="btn btn-primary" >
                                                <i class="fa fa-save"></i></button>
                                            </div>
                                        </div><br>
                                    </div>
                                </form>
                            </div>
                            <script>
        						function validateInData()
        						{
        							PRJNAME = document.getElementById('PRJNAME').value;
        							if(PRJNAME == '')
        							{
        								swal('<?php echo $officeNmEmpty; ?>',
                                        {
                                            icon: "warning",
                                        })
                                        .then(function()
                                        {
                                            swal.close();
                                            $('#PRJNAME').focus();
                                        });
        								return false;
        							}
                                    
        							/*var PRJDATE  = new Date($('#datepicker1').val());
                                    var PRJEDATE = new Date($('#datepicker2').val());
        							
        							if(PRJEDAT < PRJDATE)
        							{
        								alert('End Date Project must be Greater than Start Date Project.');
        								return false;
        							}*/

                                    PRJSCATEG = document.getElementById('PRJSCATEG').value;
                                    if(PRJSCATEG == 0)
                                    {
                                        swal({
                                            icon: "warning",
                                            text: "<?php echo "$typofBEmpy"; ?>",
                                            closeOnConfirm: false
                                        })
                                        .then(function()
                                        {
                                            swal.close();
                                            $('#PRJSCATEG').focus();

                                        });
                                        return false;
                                    }
        						}
        					</script>
                            <div class="active tab-pane" id="profPicture" style="display: none;">
                                <form class="form-horizontal" name="frm" method="post" action="<?php echo $urlUpdDoc; ?>" enctype="multipart/form-data" onSubmit="return checkData()">
                                    <div class="box box-primary">
                                        <div class="box-header with-border">
                                            <h3 class="box-title"><?php echo $UploadDoc; ?></h3>
                                        </div>
                                        <br>
                                        <div class="form-group">
                                            <label for="inputName" class="col-sm-2 control-label"><?php echo $Code; ?> </label>
                                            <div class="col-sm-10">
                                              <input type="text" class="form-control" name="PRJCODE" id="PRJCODE" placeholder="Project Code" value="<?php echo $PRJCODE; ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputName" class="col-sm-2 control-label"><?php echo $Name ?> a</label>
                                            <div class="col-sm-10">
                                              <input type="text" class="form-control" name="PRJNAME" id="PRJNAME" placeholder="File Name" value="<?php echo $PRJNAME; ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputName" class="col-sm-2 control-label"><?php echo $ChooseFile ?> </label>
                                            <div class="col-sm-10">
                                              <input type="file" name="userfile" class="filestyle" data-buttonName="btn-primary"/>
                                            </div>
                                        </div>
                                        <br>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button class="btn btn-primary" >
                                            <i class="fa fa-save"></i></button>
                                        </div>
                                    </div>
                                </form>
                       		</div>
                            <div class="active tab-pane" id="profNotes" style="display: none;">
                                <form class="form-horizontal" name="frm" method="post" action="<?php echo $urlUpdDesc; ?>" enctype="multipart/form-data" onSubmit="return checkData()">
                                    <div class="box box-primary">
                                        <div class="box-header with-border">
                                            <h3 class="box-title"><?php echo $compDesc; ?></h3>
                                        </div>
                                        <br>
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                              <textarea class="form-control" name="CompDesc"  id="CompDesc" style="height:140px"><?php echo $CompD; ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <button class="btn btn-primary" >
                                            <i class="fa fa-save"></i></button>
                                        </div>
                                    </div>
                                </form>
                       		</div>
                            <script>
                                function checkData()
                                {
                                    filename    = document.getElementById('FileName').value;
                                    if(filename == '')
                                    {
                                        alert('Please input file name.');
                                        document.getElementById('FileName').focus();
                                        return false;
                                    }
                                }

                                function showTab(valTab)
                                {
                                    if(valTab == 1)
                                    {
                                        document.getElementById('settings').style.display       = '';
                                        document.getElementById('profPicture').style.display    = 'none';
                                        document.getElementById('profNotes').style.display      = 'none';
                                    }
                                    else if(valTab == 2)
                                    {
                                        document.getElementById('settings').style.display       = 'none';
                                        document.getElementById('profPicture').style.display    = '';
                                        document.getElementById('profNotes').style.display      = 'none';
                                    }
                                    else if(valTab == 3)
                                    {
                                        document.getElementById('settings').style.display       = 'none';
                                        document.getElementById('profPicture').style.display    = 'none';
                                        document.getElementById('profNotes').style.display      = '';
                                    }
                                }
        					</script>
        				</div> 
        			</div>
                </div>
            </div>
            <?php
                $DefID      = $this->session->userdata['Emp_ID'];
                $act_lnk    = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                if($DefID == 'D15040004221')
                    echo "<font size='1'><i>$act_lnk</i></font>";
            ?>
        </section>
    </body>
</html>

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
          Date: moment().subtract(29, 'days'),
          endDate: moment()
        },
        function (start, end) {
          $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }
    );
    
    //Date picker
    $.fn.datepicker.defaults.format = "dd/mm/yyyy";
    $('#datepicker').datepicker({
      autoclose: true,
      endDate: '+1d'
    });

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
    $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'js' AND isAct = 1 AND cssjs_vers IN ('$vers', 'All')";
    $rescss = $this->db->query($sqlcss)->result();
    foreach($rescss as $rowcss) :
        $cssjs_lnk  = $rowcss->cssjs_lnk;
        ?>
            <script src="<?php echo base_url($cssjs_lnk) ?>"></script>
        <?php
    endforeach;

    // Right side column. contains the Control Panel
    //______$this->load->view('template/aside');

    //______$this->load->view('template/js_data');

    //______$this->load->view('template/foot');
?>