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
	$PRJTELP		= '';
	$PRJADD 		= '';
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
	$PRJSCATEG		= 0;
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
	$PRJTELP 			= $default['PRJTELP'];
	$PRJADD 			= $default['PRJADD'];
	$PRJCATEG 			= $default['PRJCATEG'];
	$PRJSCATEG 			= $default['PRJSCATEG'];
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

	$PRJBOQ				= $default['PRJBOQ'];	// NILAI KONTRAK	
	$PRJRAP				= $default['PRJRAP'];
	$PRJLKOT 			= $default['PRJLKOT'];
	$PRJCBNG			= $default['PRJCBNG'];
	$PRJSTAT 			= $default['PRJSTAT'];
	$PRJNOTE			= $default['PRJNOTE'];
	$PRJ_MNG			= $default['PRJ_MNG'];
	$PRJ_IMGNAME		= $default['PRJ_IMGNAME'];
	$Patt_Year 			= $default['Patt_Year'];
	$Patt_Number 		= $default['Patt_Number'];
	$lastPatternNumb1	= $Patt_Number;

	$ISCHANGE		= $default['ISCHANGE']; // 0. No, 1. Yes
	$REFCHGNO		= $default['REFCHGNO'];	// References No.
	$PRJCOST2		= $default['PRJCOST2'];	// Nilai Kontrak Baru
	$CHGUSER		= $default['CHGUSER'];
	$CHGSTAT		= $default['CHGSTAT'];
}

$imgLoc		= base_url('assets/AdminLTE-2.0.5/project_image/budget_02.png');
if (!file_exists('assets/AdminLTE-2.0.5/project_image/'.$PRJCODE))
{
	$imgLoc			= base_url('assets/AdminLTE-2.0.5/project_image/building.jpg');
}
?>
<!DOCTYPE html>

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
			
			if($TranslCode == 'Code')$Code = $LangTransl;
			if($TranslCode == 'Budgeting')$Budgeting = $LangTransl;
			if($TranslCode == 'Parent')$Parent = $LangTransl;
			if($TranslCode == 'BudCode')$BudCode = $LangTransl;
			if($TranslCode == 'ProjectName')$ProjectName = $LangTransl;
			if($TranslCode == 'StartDate')$StartDate = $LangTransl;
			if($TranslCode == 'CutOffDate')$CutOffDate = $LangTransl;
			if($TranslCode == 'EndDate')$EndDate = $LangTransl;
			if($TranslCode == 'CompanyCode')$CompanyCode = $LangTransl;
			if($TranslCode == 'Address')$Address = $LangTransl;
			if($TranslCode == 'projectCateg')$projectCateg = $LangTransl;
			if($TranslCode == 'ProjectOwner')$ProjectOwner = $LangTransl;
			if($TranslCode == 'Amount')$Amount = $LangTransl;
			if($TranslCode == 'Location')$Location = $LangTransl;
			if($TranslCode == 'Address')$Address = $LangTransl;
			if($TranslCode == 'Director')$Director = $LangTransl;
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
			if($TranslCode == 'Back')$Back = $LangTransl;
			if($TranslCode == 'Active')$Active = $LangTransl;
			if($TranslCode == 'Inactive')$Inactive = $LangTransl;
			if($TranslCode == 'Project')$Project = $LangTransl;
			if($TranslCode == 'BudgTotal')$BudgTotal = $LangTransl;
			if($TranslCode == 'Target')$Target = $LangTransl;
			if($TranslCode == 'Margin')$Margin = $LangTransl;
			if($TranslCode == 'AboutProject')$AboutProject = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'CompanyName')$CompanyName = $LangTransl;
			if($TranslCode == 'Name')$Name = $LangTransl;
			if($TranslCode == 'CompanyInfo')$CompanyInfo = $LangTransl;
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
			if($TranslCode == 'DeedNo')$DeedNo = $LangTransl;
			if($TranslCode == 'typeOfBuss')$typeOfBuss = $LangTransl;
			if($TranslCode == 'PeriodeCode')$PeriodeCode = $LangTransl;
			if($TranslCode == 'codeExist')$codeExist = $LangTransl;
			if($TranslCode == 'budEmpt')$budEmpt = $LangTransl;
			if($TranslCode == 'selPG')$selPG = $LangTransl;
			if($TranslCode == 'typofBEmpy')$typofBEmpy = $LangTransl;
		endforeach;
		$urlUpdDoc		= site_url('c_comprof/c_bUd93tL15t/do_upload/?id='.$this->url_encryption_helper->encode_url($appName));
		
		if($LangID == 'IND')
		{
			$Yes		= "Ya";
			$No			= "Bukan";

			$alert1		= "Silahkan tentukan ";
			$alert2		= " tidak boleh kosong";
		}
		else
		{
			$Yes		= "Yes";
			$No			= "No";
			$alert1		= "Please select ";
			$alert2		= " can not be empty";
		}

		if($PRJCATEG == 1)
	        $PRJCATEGD  = 'Kontraktor';
	    elseif($PRJCATEG == 2)
	        $PRJCATEGD  = 'Manufacture';
	    elseif($PRJCATEG == 3)
	        $PRJCATEGD  = 'Tambang';
	    else
	        $PRJCATEGD  = 'Lainnya';

	    $DirName	= '';
	   	$sqlEmp1	= "SELECT Emp_ID, First_Name, Last_Name FROM tbl_employee WHERE Emp_ID = '$PRJ_MNG'";
	    $sqlEmp1	= $this->db->query($sqlEmp1)->result();
	    foreach($sqlEmp1 as $row1) :
	        $First_Name	= $row1->First_Name;
	        $Last_Name	= $row1->Last_Name;
	        $DirName	= "$First_Name $Last_Name";
	    endforeach;
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
			    <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/project.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $Add; ?>
			    <small><?php echo $mnName; ?></small>
			  </h1>
		</section>

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
	                    		<b><?php echo $typeOfBuss; ?></b> <a class="pull-right"><?php print $PRJCATEGD; ?></a>
	                    	</li>
	                    	<li class="list-group-item">
	                    		<b>Telp. </b> <a class="pull-right"><?php print $PRJTELP; ?></a>
	                    	</li>
	                        <?php
								if($PRJBOQ == '')
									$PRJBOQ = $PRJCOST;
									
	                        	$TargetFee	= $PRJBOQ - $PRJRAP;
							?>
	                    	<li class="list-group-item">
	                    		<b><?php echo $Director; ?> (s)</b> <a class="pull-right"><?php print $DirName; ?></a>
	                    	</li>
	                    </ul>
	                    <a href="#" class="btn btn-primary btn-block" style="display:none"><b>Follow</b></a>
	                </div>
	          	</div>
	        </div>
	        <div class="col-md-9">
				<div class="nav-tabs-custom box box-success">
	            	<ul class="nav nav-tabs" style="display: none;">
	                	<li class="active"><a href="#settings" data-toggle="tab"><?php echo $CompanyInfo; ?></a></li> 		<!-- Tab 1 -->
	                    <li><a href="#profPicture" data-toggle="tab" style="display:none"><?php echo $Picture; ?></a></li>	<!-- Tab 2 -->
	                </ul>
	                <!-- Biodata -->
	                <div class="tab-content">
	                    <div class="active tab-pane" id="settings">
	                        <form class="form-horizontal" name="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return chkInput()">
	                            <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
	                            <input type="hidden" name="rowCount" id="rowCount" value="0">
	                            <input type="hidden" name="isHO" id="isHO" value="0">
	                            
	                                <div class="box-header with-border">
	                                    <h3 class="box-title"><?php echo $CompanyInfo; ?></h3>
	                                </div>
	                                <br>
	                                <div class="form-group" style="display: none;">
	                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $CompanyCode?></label>
	                                    <div class="col-sm-10">
	                                        <input type="text" class="form-control" name="PRJNUM1" id="PRJNUM1" value="<?php echo $DocNumber; ?>" disabled>
	                                        <input type="hidden" name="Patt_Number" id="Patt_Number" value="<?php echo $Patt_Number; ?>">
	                                        <input type="hidden" name="proj_Number" id="proj_Number" value="<?php echo $DocNumber; ?>" >
	                                        <input type="hidden"  id="lastPatternNumb" name="lastPatternNumb" size="20" value="<?php echo $lastPatternNumb1; ?>" />
	                                    </div>
	                                </div>
	                                <div class="form-group">
	                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Parent; ?></label>
	                                    <div class="col-sm-10">
	                                        <select name="PRJCODE_HO" id="PRJCODE_HO" class="form-control select2">
	                                            <option value="0"> --- </option>
	                                            <?php
	                                                $PRJ_Code 	= '';
	                                                $CountPRJ 	= $this->db->count_all('tbl_project');
	                                                $sqlPRJ 	= "SELECT PRJCODE, PRJNAME
																	FROM tbl_project WHERE PRJSTAT = 1 AND PRJTYPE = 1";
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
	                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Code?></label>
	                                    <div class="col-sm-10">
	                                        <!-- <label> -->
	                                            <input type="text" class="form-control" name="PRJCODE" id="PRJCODE" placeholder="<?php echo $CompanyCode; ?>" value="<?php echo $PRJCODE; ?>" maxlength="20" onChange="functioncheck(this.value)" <?php if($task == 'edit') { ?> readonly <?php } ?>>
	                                        <!-- </label><label>&nbsp;&nbsp;</label> -->
	                                        <label id="isHidden" style="display: none;"></label>
	                                        <input type="hidden" name="CheckThe_Code" id="CheckThe_Code" value="" size="20" maxlength="25" >
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
	                                                    //document.getElementById("isHidden").innerHTML = ' Project Code already exist ... !';
	                                                    //document.getElementById("isHidden").style.color = "#ff0000";
	                                                    document.getElementById("btnSave").style.display = "none";
														swal({
										                    icon: "error",
										                    text: "<?php echo $codeExist; ?>",
										                    closeOnConfirm: false
										                })
										                .then(function()
										                {
									                        swal.close();
									                        $('#PRJCODE').focus();

										                });
										                document.getElementById('PRJCODE').value = '';
										                return false;
	                                                }
	                                                else
	                                                {
	                                                    document.getElementById('CheckThe_Code').value= recordcount;
	                                                    //document.getElementById("isHidden").innerHTML = ' Project Code : OK .. !';
	                                                    //document.getElementById("isHidden").style.color = "green";
	                                                    document.getElementById("btnSave").style.display = "";
	                                                }
	                                            }
	                                        }
	                                        var PRJCODE = document.getElementById('PRJCODE').value;
											
	                                        ajaxRequest.open("GET", "<?php echo base_url().'index.php/c_comprof/c_c0mPL15t/getTheCode/';?>" + PRJCODE, true);
	                                        ajaxRequest.send(null);
	                                    }
	                                </script>
	                                <div class="form-group">
	                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Name?></label>
	                                    <div class="col-sm-10">
	                                        <input type="text" class="form-control" name="PRJNAME" id="PRJNAME" value="<?php echo $PRJNAME; ?>" placeholder="<?php echo $CompanyName; ?>" >
	                                    </div>
	                                </div>
	                                <div class="form-group">
	                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Director ?></label>
	                                    <div class="col-sm-10">
	                                        <select name="PRJ_MNG[]" id="PRJ_MNG" class="form-control select2" multiple="multiple" data-placeholder="&nbsp;&nbsp;&nbsp;<?php echo $Director ?>">
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
	                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $StartDate ?></label>
	                                    <div class="col-sm-10">
	                                        <div class="input-group date">
	                                            <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
	                                            <input type="text" name="PRJDATE" class="form-control pull-left" id="datepicker1" value="<?php echo $PRJDATED; ?>" style="width:100px">
	                                        </div>
	                                    </div>
	                                </div>
	                                <div class="form-group" style="display: none;">
	                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $EndDate; ?></label>
	                                    <div class="col-sm-10">
	                                        <div class="input-group date">
	                                            <div class="input-group-addon">
	                                        <i class="fa fa-calendar"></i>&nbsp;</div><input type="text" name="PRJEDAT" class="form-control pull-left" id="datepicker2" value="<?php echo $PRJEDATD; ?>" style="width:100px"></div>
	                                    </div>
	                                </div>
	                                <div class="form-group" style="display:none">
	                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $CutOffDate; ?></label>
	                                    <div class="col-sm-10">
	                                        <div class="input-group date">
	                                            <div class="input-group-addon">
	                                        <i class="fa fa-calendar"></i>&nbsp;</div><input type="text" name="PRJDATE_CO" class="form-control pull-left" id="datepicker3" value="<?php echo $PRJDATE_CO; ?>" style="width:150px"></div>
	                                    </div>
	                                </div>
	                                <div class="form-group">
	                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $DeedNo?></label>
	                                    <div class="col-sm-10">
	                                        <input type="text" class="form-control" name="PRJCNUM" id="PRJCNUM" value="<?php echo $PRJCNUM; ?>" placeholder="<?php echo $DeedNo; ?>" >
	                                    </div>
	                                </div>
	                                <div class="form-group">
	                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Location?></label>
	                                    <div class="col-sm-10">
	                                        <input type="text" class="form-control" name="PRJLOCT" id="PRJLOCT" value="<?php echo $PRJLOCT; ?>" placeholder="<?php echo $Location; ?>" >
	                                    </div>
	                                </div>
	                                <div class="form-group">
	                                    <label for="inputName" class="col-sm-2 control-label">Telp.</label>
	                                    <div class="col-sm-10">
	                                        <input type="text" class="form-control" name="PRJTELP" id="PRJTELP" value="<?php echo $PRJTELP; ?>" placeholder="Telephone" >
	                                    </div>
	                                </div>
	                                <div class="form-group">
	                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Address?></label>
	                                    <div class="col-sm-10">
	                                        <textarea class="form-control" name="PRJADD"  id="PRJADD" style="height:70px" placeholder="<?php echo $Address; ?>"><?php echo set_value('PRJADD', isset($default['PRJADD']) ? $default['PRJADD'] : ''); ?></textarea>
	                                    </div>
	                                </div>
	                                <div class="form-group" style="display: none;">
	                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Amount; ?></label>
	                                    <div class="col-sm-10">
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
	                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Notes ?></label>
	                                    <div class="col-sm-10">
	                                        <textarea class="form-control" name="PRJNOTE"  id="PRJNOTE" style="height:70px" placeholder="<?php echo $Notes; ?>"><?php echo set_value('PRJNOTE', isset($default['PRJNOTE']) ? $default['PRJNOTE'] : ''); ?></textarea>
	                                    </div>
	                                </div>
	                                <div class="form-group">
	                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $typeOfBuss; ?></label>
	                                    <div class="col-sm-10">
	                                        <select name="PRJSCATEG" id="PRJSCATEG" class="form-control select2">
	                                            <option value="0" > --- </option>
	                                            <option value="1" <?php if($PRJSCATEG == 1) { ?> selected <?php } ?>>Kontraktor</option>
	                                            <option value="2" <?php if($PRJSCATEG == 2) { ?> selected <?php } ?>>Manufacture</option>
	                                            <option value="3" <?php if($PRJSCATEG == 3) { ?> selected <?php } ?>>Tambang</option>
	                                        </select>
	                                    </div>
	                                </div>
	                                <div class="form-group">
	                                    <label for="inputName" class="col-sm-2 control-label">Status</label>
	                                    <div class="col-sm-10">
	                                        <select name="PRJSTAT" id="PRJSTAT" class="form-control select2">
	                                            <option value="1" <?php if($PRJSTAT == 1) { ?> selected <?php } ?>><?php echo $Active; ?></option>
	                                            <option value="0" <?php if($PRJSTAT == 0) { ?> selected <?php } ?>><?php echo $Inactive; ?></option>
	                                        </select>
	                                    </div>
	                                </div>
	                                <div class="form-group" style="display: none;">
	                                    <label for="inputName" class="col-sm-2 control-label">&nbsp;</label>
	                                    <div class="col-sm-10">
	                                        <div class="alert alert-danger alert-dismissible">
	                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	                                            <h4><i class="icon fa fa-info"></i> <?php echo $important; ?>!</h4>
	                                            Jika Anda mengaktifkan salah satu Anggaran, maka secara otomatis sistem akan menonaktifkan anggaran yang lainnya. Sehingga, semua transaksi akan menggunakan anggaran yang Anda aktifkan.
	                                        </div>
	                                    </div>
	                                </div><br>
	                                <div class="form-group">
	                                    <label for="inputName" class="col-sm-2 control-label">&nbsp;</label>
	                                    <div class="col-sm-10">
	                                        <?php
												if($ISCREATE == 1 || $ISAPPROVE == 1)
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
												echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i>&nbsp;&nbsp;'.$Back.'</button>');
											?>
	                                    </div>
	                                </div><br>
	                        </form>
	                    </div>
	                    <script>
							function chkInput()
							{
								PRJCODE_HO = document.getElementById('PRJCODE_HO').value;
								if(PRJCODE_HO == 0)
								{
									swal({
					                    icon: "warning",
					                    text: "<?php echo "$alert1 $Parent"; ?>",
					                    closeOnConfirm: false
					                })
					                .then(function()
					                {
				                        swal.close();
				                        $('#PRJCODE_HO').focus();

					                });
									return false;
								}

								PRJCODE = document.getElementById('PRJCODE').value;
	                        	if(PRJCODE == '')
	                        	{
									swal({
					                    icon: "warning",
					                    text: "<?php echo "$CompanyCode$alert2"; ?>",
					                    closeOnConfirm: false
					                })
					                .then(function()
					                {
				                        swal.close();
				                        $('#PRJCODE').focus();

					                });
	                        		return false;
	                        	}

								PRJNAME = document.getElementById('PRJNAME').value;
								if(PRJNAME == '')
								{
									swal({
					                    icon: "warning",
					                    text: "<?php echo "$CompanyName$alert2"; ?>",
					                    closeOnConfirm: false
					                })
					                .then(function()
					                {
				                        swal.close();
				                        $('#PRJNAME').focus();

					                });
	                        		return false;
								}

								/*nextornot = document.getElementById('CheckThe_Code').value;
								if(nextornot > 0)
								{
									alert('Project Code Already Exist. Please Change.');
									document.getElementById('PRJCODE').value = '';
									document.getElementById('PRJCODE').focus();
									return false;
								}*/

								PRJLOCT = document.getElementById('PRJLOCT').value;
								if(PRJLOCT == '')
								{
									swal({
					                    icon: "warning",
					                    text: "<?php echo "$Location$alert2"; ?>",
					                    closeOnConfirm: false
					                })
					                .then(function()
					                {
				                        swal.close();
				                        $('#PRJLOCT').focus();

					                });
	                        		return false;
								}

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
								
								/*var PRJDATE = new Date(document.frm.PRJDATE.value);
								
								var PRJEDAT = new Date(document.frm.PRJEDAT.value);
								
								if(PRJEDAT < PRJDATE)
								{
									alert('End Date Project must be Greater than Start Date Project.');
									return false;
								}*/
							}
						</script>
					</div>
				</div>
	        </div>
	        <!-- /.col -->
	      </div>
	      <!-- /.row -->
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