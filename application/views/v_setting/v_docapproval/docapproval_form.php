<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 25 Januari 2018
	* File Name	= docapproval_form.php
	* Location		= -
*/

// _global function
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
$decFormat		= 2;
 
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody    = $this->session->userdata['appBody'];

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
	
if($task == 'add')
{
	$myCount 			= $this->db->count_all('tbl_docstepapp');
	
	$sql 				= "SELECT MAX(DOCAPP_ID) as maxNumber FROM tbl_docstepapp";
	$result 			= $this->db->query($sql)->result();
	if($myCount>0)
	{
		foreach($result as $row) :
			$myMax = $row->maxNumber;
			$myMax = $myMax+1;
		endforeach;
	}	else	{		$myMax = 1;	}
	
	$DOCAPP_ID			= $myMax;
	$PRJCODE			= '';
	$DOCCODE			= '';
	$DOCAPP_TYPE		= 2;
	$DOCAPP_NAME 		= "";
	$MENU_CODE 			= "";
	$APPROVER_1 		= "";
	$APPROVER_2 		= "";
	$APPROVER_3			= "";
	$APPROVER_4 		= "";
	$APPROVER_5 		= "";
	$APPLIMIT_1			= 0;
	$APPLIMIT_2 		= 0;
	$APPLIMIT_3 		= 0;
	$APPLIMIT_4 		= 0;
	$APPLIMIT_5 		= 0;
	$CREATED_BY			= $DefEmp_ID;
	
	$Step1Open			= 1;
	$Step2Open			= 0;
	$Step3Open			= 0;
	$Step4Open			= 0;
	$Step5Open			= 0;
	$POSCODE 			= '0';
}
else
{
	$DOCAPP_ID 		= $default['DOCAPP_ID'];
	$PRJCODE 		= $default['PRJCODE'];
	$DOCCODE 		= $default['DOCCODE'];
	$DOCAPP_TYPE 	= $default['DOCAPP_TYPE'];
	$DOCAPP_NAME 	= $default['DOCAPP_NAME'];
	$MENU_CODE 		= $default['MENU_CODE'];
	$POSCODE 		= $default['POSCODE'];
	$APPROVER_1 	= $default['APPROVER_1'];
	$APPROVER_2 	= $default['APPROVER_2'];
	$APPROVER_3 	= $default['APPROVER_3'];
	$APPROVER_4 	= $default['APPROVER_4'];
	$APPROVER_5 	= $default['APPROVER_5']; 
	$APPLIMIT_1 	= $default['APPLIMIT_1'];
	$APPLIMIT_2 	= $default['APPLIMIT_2'];
	$APPLIMIT_3 	= $default['APPLIMIT_3'];
	$APPLIMIT_4 	= $default['APPLIMIT_4'];
	$APPLIMIT_5 	= $default['APPLIMIT_5'];
	$CREATED_BY 	= $default['CREATED_BY'];
	
	$Step1Open			= 1;
	if($APPROVER_1 != '0')
	{
		$Step2Open		= 1; // Tetap dibuka agar bisa langsung memilih
		$Step3Open		= 0;
		$Step4Open		= 0;
		$Step5Open		= 0;
	}
	if($APPROVER_2 != '0')
	{
		$Step2Open		= 1;
		$Step3Open		= 1; // Tetap dibuka agar bisa langsung memilih
		$Step4Open		= 0;
		$Step5Open		= 0;
	}
	if($APPROVER_3 != '0')
	{
		$Step2Open		= 1;
		$Step3Open		= 1;
		$Step4Open		= 1; // Tetap dibuka agar bisa langsung memilih
		$Step5Open		= 0;
	}
	if($APPROVER_4 != '0')
	{
		$Step2Open		= 1;
		$Step3Open		= 1;
		$Step4Open		= 1;
		$Step5Open		= 1; // Tetap dibuka agar bisa langsung memilih
	}
	if($APPROVER_5 != '0')
	{
		$Step2Open		= 1;
		$Step3Open		= 1;
		$Step4Open		= 1;
		$Step5Open		= 1;
	}
}
?>
<!DOCTYPE html>
<html>
  	<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $appName; ?></title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <link rel="icon" type="image/png" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/favicon/lock-02.png'; ?>" sizes="32x32">
        <!-- Tell the browser to be responsive to screen width -->
        <?php
            $vers   = $this->session->userdata['vers'];

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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

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
			
			if($TranslCode == 'Add')$Add = $LangTransl;
			if($TranslCode == 'Edit')$Edit = $LangTransl;
			if($TranslCode == 'ApprovalType')$ApprovalType = $LangTransl;
			if($TranslCode == 'CompanyName')$CompanyName = $LangTransl;
			if($TranslCode == 'Position')$Position = $LangTransl;
			if($TranslCode == 'ApprovalName')$ApprovalName = $LangTransl;
			if($TranslCode == 'Department')$Department = $LangTransl;
			if($TranslCode == 'Approver')$Approver = $LangTransl;
			if($TranslCode == 'Cancel')$Cancel = $LangTransl;
			if($TranslCode == 'Save')$Save = $LangTransl;
			if($TranslCode == 'Update')$Update = $LangTransl;
			if($TranslCode == 'TotAmount')$TotAmount = $LangTransl;
			if($TranslCode == 'Position')$Position = $LangTransl;
			if($TranslCode == 'Delete')$Delete = $LangTransl;
		endforeach;

		$sqlGetMENU		= "SELECT menu_code, menu_name_IND AS menu_nameIND, menu_name_ENG AS menu_nameENG
								FROM tbl_menu 
							WHERE 
								isNeedStepAppr = 1 AND isActive = 1
							ORDER BY MENU_NAME_$LangID ASC";
		$resGetMENU		= $this->db->query($sqlGetMENU)->result();
		
		$sqlGetEMPN		= "SELECT Emp_ID, First_Name, Last_Name FROM tbl_employee WHERE FlagAppCheck = 1 AND Emp_Status = 1";
		$resGetEMPN		= $this->db->query($sqlGetEMPN)->result();
		
		if($LangID == 'IND')
		{
			$h_title	= "Persetujuan Dokumen";
			$h1_title	= "pengaturan";
			$alert1		= "Anda yakin akan menghapus ini?";
		}
		else
		{
			$h_title	= "Doc. Approval";
			$h1_title	= "setting";
			$alert1		= "Are you sure you want to delete?";
		}
		
		// DEFAULT STAT FORM - 1. SAVE, 2. DELETE
		$statFORM		= 1;

		$comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
			<h1>
		    <?php echo $h_title; ?>
		    <small><?php echo $h1_title; ?></small>
		  </h1>
		</section>

		<section class="content">
		    <div class="row">
		        <div class="col-md-12">
		            <div class="box box-primary">
		                <div class="box-header with-border" style="display:none">               
		              		<div class="box-tools pull-right">
		                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
		                        </button>
		                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
		                    </div>
		                </div>
		                <div class="box-body chart-responsive">
		                	<form class="form-horizontal" name="absen_form" method="post" action="<?php echo $form_action; ?>" onSubmit="return checkINP();">
		                		<input type="hidden" name="statFORM" id="statFORM" value="<?php echo $statFORM; ?>">
		                        <div class="form-group"> <!-- COMPANY NAME -->
		                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $CompanyName ?></label>
		                          	<div class="col-sm-10">
		                                <select name="PRJCODE" id="PRJCODE" class="form-control select2">
				                            <option value="none"> --- </option>
				                            <?php
											$sqlcPRJ	= "tbl_project WHERE PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";
											$rescPRJ	= $this->db->count_all($sqlcPRJ);
											/*$sqlPRJ		= "SELECT PRJCODE, PRJNAME FROM tbl_project WHERE PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";*/
											$sqlPRJ		= "SELECT PRJCODE, PRJNAME FROM tbl_project WHERE PRJSTAT = 1";
											$resPRJ	= $this->db->query($sqlPRJ)->result();
				                            if($rescPRJ > 0)
				                            {
				                                foreach($resPRJ as $row) :
				                                    $PRJCODE1 	= $row->PRJCODE;
				                                    $PRJNAME 	= $row->PRJNAME;
				                                    ?>
				                                  <option value="<?php echo $PRJCODE1; ?>" <?php if($PRJCODE1 == $PRJCODE) { ?> selected <?php } ?>><?php echo "$PRJCODE1 - $PRJNAME"; ?></option>
				                                  <?php
				                                endforeach;
				                            }
				                            else
				                            {
				                                ?>
				                                  <option value="none">--- No Unit Found ---</option>
				                              <?php
				                            }
				                            ?>
				                        </select>
		                          	</div>
		                        </div>
		                        <div class="form-group"> <!-- APPROVAL DEPARTMENT -->
		                            <label for="inputName" class="col-sm-2 control-label"><?php echo $Department ?></label>
		                            <div class="col-sm-10">
		                                <select name="POSCODE" id="POSCODE" class="form-control select2">
				                        	<option value="0"> --- </option>
				                            <?php
				                            	if($countParent>0)
					                            {
					                                $i = 0;
					                                foreach($vwParent as $row) :
														$POSS_CODE1		= $row->POSS_CODE;
														$POSS_NAME1		= $row->POSS_NAME;
														$POSS_PARENT1	= $row->POSS_PARENT;
														$POSS_LEVIDX1	= $row->POSS_LEVIDX;
														if($POSS_LEVIDX1 == 0)
															$SPACELEV 	= "";
														elseif($POSS_LEVIDX1 == 1)
															$SPACELEV 	= "&nbsp;&nbsp;&nbsp;&nbsp;";
														elseif($POSS_LEVIDX1 == 2)
															$SPACELEV 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
														elseif($POSS_LEVIDX1 == 3)
															$SPACELEV 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
														elseif($POSS_LEVIDX1 == 4)
															$SPACELEV 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
														elseif($POSS_LEVIDX1 == 5)
															$SPACELEV 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
														elseif($POSS_LEVIDX1 == 6)
															$SPACELEV 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
														elseif($POSS_LEVIDX1 == 7)
															$SPACELEV 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
														elseif($POSS_LEVIDX1 == 8)
															$SPACELEV 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
														elseif($POSS_LEVIDX1 == 9)
															$SPACELEV 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
														elseif($POSS_LEVIDX1 == 10)
															$SPACELEV 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
														
														$sqlC1		= "tbl_position_str WHERE POSS_PARENT = '$POSS_CODE1'";
														$ressqlC1 	= $this->db->count_all($sqlC1);
														?>
					                                		<option value="<?php echo $POSS_CODE1;?>" <?php if($POSS_CODE1 == $POSCODE) { ?>selected<?php } if($ressqlC1>0) {?> style="font-weight:bold"<?php } ?>><?php echo "$SPACELEV$POSS_NAME1";?></option>
					                            		<?php
													endforeach;
												}
				                            ?>
				                        </select>
		                            </div>
		                        </div>
		                        <div class="form-group" style="display: none;"> <!-- APPROVAL TYPE -->
		                            <label for="inputName" class="col-sm-2 control-label"><?php echo $ApprovalType; ?></label>
		                            <div class="col-sm-10">
		                                <input type="hidden" maxlength="50" name="CREATED_BY" id="CREATED_BY" size="20" value="<?php echo  $CREATED_BY;?>">
				                        <input type="hidden" maxlength="50" name="DOCAPP_ID" id="DOCAPP_ID" size="20" value="<?php echo  $DOCAPP_ID;?>">
				                        <input type="hidden" maxlength="50" name="DOCCODE" id="DOCCODE" size="20" value="<?php echo  $DOCCODE;?>">
				                        <select name="DOCAPP_TYPE" id="DOCAPP_TYPE" class="form-control select2" style="max-width:150px" >
				                        	<option value="2" <?php if($DOCAPP_TYPE == 2) { ?> selected <?php } ?>>Tahapan</option>
				                        </select>
		                            </div>
		                        </div>
		                        <div class="form-group"> <!-- APPROVAL NAME -->
		                            <label for="inputName" class="col-sm-2 control-label"><?php echo $ApprovalName ?></label>
		                            <div class="col-sm-10">
		                                <select name="MENU_CODE" id="MENU_CODE" class="form-control select2" onChange="openApp(this.value)">
				                        	<option value=""> --- </option>
				                            <?php
												foreach($resGetMENU as $rowMENU) :
													$menu_code1		= $rowMENU->menu_code;
													if($LangID == 'IND')
														$menu_name1	= $rowMENU->menu_nameIND;
													else
														$menu_name1	= $rowMENU->menu_nameENG;
													?>
														<option value="<?php echo $menu_code1;?>" <?php if($menu_code1 == $MENU_CODE) { ?>selected<?php } ?>><?php echo "$menu_code1 - $menu_name1";?></option>
													<?php
												endforeach;
				                            ?>
				                        </select>
		                            </div>
		                        </div>
				                <script>
									function openApp(value)
									{
										if(value == 0)
										{
											document.getElementById('APPROVER_1').disabled = true;
											document.getElementById('APPROVER_2').disabled = true;
											document.getElementById('APPROVER_3').disabled = true;
											document.getElementById('APPROVER_4').disabled = true;
											document.getElementById('APPROVER_5').disabled = true;
										}
										else
										{
											document.getElementById('APPROVER_1').disabled = false;
											//document.getElementById('APPROVER_2').disabled = false;
											//document.getElementById('APPROVER_3').disabled = false;
											//document.getElementById('APPROVER_4').disabled = false;
											//document.getElementById('APPROVER_5').disabled = false;
										}
									}
									
				                    function getChange(thisVal)
				                    {
				                        if(thisVal == 1)
				                        {
				                            APPROVER_1	= document.getElementById('APPROVER_1').value;
				                            if(APPROVER_1 == 0)
				                            {
				                                document.getElementById('APPLIMIT_1v').disabled	= 'true';
				                                document.getElementById('APPROVER_2').disabled 	= 'none';
				                                document.getElementById('APPROVER_3').disabled 	= 'none';
				                                document.getElementById('APPROVER_4').disabled 	= 'none';
				                                document.getElementById('APPROVER_5').disabled 	= 'none';
				                            }
				                            else
				                            {
				                                document.getElementById('APPLIMIT_1v').disabled	= '';
				                                document.getElementById('APPROVER_2').disabled 	= '';
				                            }
				                        }
				                        if(thisVal == 2)
				                        {
				                            APPROVER_2	= document.getElementById('APPROVER_2').value;
				                            if(APPROVER_2 == 0)
				                            {
				                                document.getElementById('APPLIMIT_2v').disabled	= 'true';
				                                document.getElementById('APPROVER_3').disabled 	= 'none';
				                                document.getElementById('APPROVER_4').disabled 	= 'none';
				                                document.getElementById('APPROVER_5').disabled 	= 'none';
				                            }
				                            else
				                            {
				                                document.getElementById('APPLIMIT_2v').disabled	= '';
				                                document.getElementById('APPROVER_3').disabled 	= '';
				                            }
				                        }
				                        if(thisVal == 3)
				                        {
				                            APPROVER_3	= document.getElementById('APPROVER_3').value;
				                            if(APPROVER_3 == 0)
				                            {
				                                document.getElementById('APPLIMIT_3v').disabled	= 'true';
				                                document.getElementById('APPROVER_4').disabled 	= 'none';
				                                document.getElementById('APPROVER_5').disabled 	= 'none';
				                            }
				                            else
				                            {
				                                document.getElementById('APPLIMIT_3v').disabled	= '';
				                                document.getElementById('APPROVER_4').disabled 	= '';
				                            }
				                        }
				                        if(thisVal == 4)
				                        {
				                            APPROVER_4	= document.getElementById('APPROVER_4').value;
				                            if(APPROVER_4 == 0)
				                            {
				                                document.getElementById('APPLIMIT_4v').disabled	= 'true';
				                                document.getElementById('APPROVER_5').disabled 	= 'none';
				                            }
				                            else
				                            {
				                                document.getElementById('APPLIMIT_4v').disabled	= '';
				                                document.getElementById('APPROVER_5').disabled 	= '';
				                            }
				                        }
				                        if(thisVal == 5)
				                        {
				                            APPROVER_5	= document.getElementById('APPROVER_5').value;
				                            if(APPROVER_5 == 0)
				                            {
				                                document.getElementById('APPLIMIT_5v').disabled	= 'true';
				                            }
				                            else
				                            {
				                                document.getElementById('APPLIMIT_5v').disabled	= '';
				                            }
				                        }
				                    }
				                    
				                    function changeLimit(thisVal)
				                    {
				                        var decFormat	= document.getElementById('decFormat').value;
				                        if(thisVal == 1)
				                        {
				                            APPLIMIT_1 = eval(document.getElementById('APPLIMIT_1v')).value.split(",").join("");
				                            document.getElementById('APPLIMIT_1').value = APPLIMIT_1;
				                            document.getElementById('APPLIMIT_1v').value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(APPLIMIT_1)),decFormat));
				                        }
				                        if(thisVal == 2)
				                        {
				                            APPLIMIT_2 = eval(document.getElementById('APPLIMIT_2v')).value.split(",").join("");
				                            document.getElementById('APPLIMIT_2').value = APPLIMIT_2;
				                            document.getElementById('APPLIMIT_2v').value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(APPLIMIT_2)),decFormat));
				                        }
				                        if(thisVal == 3)
				                        {
				                            APPLIMIT_3 = eval(document.getElementById('APPLIMIT_3v')).value.split(",").join("");
				                            document.getElementById('APPLIMIT_3').value = APPLIMIT_3;
				                            document.getElementById('APPLIMIT_3v').value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(APPLIMIT_3)),decFormat));
				                        }
				                        if(thisVal == 4)
				                        {
				                            APPLIMIT_4 = eval(document.getElementById('APPLIMIT_4v')).value.split(",").join("");
				                            document.getElementById('APPLIMIT_4').value = APPLIMIT_4;
				                            document.getElementById('APPLIMIT_4v').value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(APPLIMIT_4)),decFormat));
				                        }
				                        if(thisVal == 5)
				                        {
				                            APPLIMIT_5 = eval(document.getElementById('APPLIMIT_5v')).value.split(",").join("");
				                            document.getElementById('APPLIMIT_5').value = APPLIMIT_5;
				                            document.getElementById('APPLIMIT_5v').value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(APPLIMIT_5)),decFormat));
				                        }
				                    }
				                </script>
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-2 control-label"><?php echo $Approver ?> 1</label>
		                            <div class="col-sm-10">
		                                <div class="row">
		                                    <div class="col-xs-12">
		                                    	<select name="APPROVER_1" id="APPROVER_1" class="form-control select2" onChange="getChange(1)" <?php if($task == 'add') { ?> disabled <?php } ?>>
						                            <option value=""> --- </option>
						                            <?php
														foreach($resGetEMPN as $rowEMPN) :
															$Emp_ID1		= $rowEMPN->Emp_ID;			
															$First_Name1	= $rowEMPN->First_Name;
															$Last_Name1		= $rowEMPN->Last_Name;
															$compName1		= "$First_Name1 $Last_Name1";
															?>
																<option value="<?php echo $Emp_ID1;?>" <?php if($APPROVER_1 == $Emp_ID1) { ?>selected<?php } ?>><?php echo "$compName1 ($Emp_ID1)";?></option>
															<?php
														endforeach;
						                            ?>
						                        </select>
		                                    </div>
		                                    <div class="col-xs-3" style="display: none;">
		                                    	<input type="hidden" maxlength="15" name="APPLIMIT_1" id="APPLIMIT_1" size="15" value="<?php echo $APPLIMIT_1; ?>" />
		                        				<input type="text" class="form-control" style="max-width:150px; text-align:right" maxlength="15" name="APPLIMIT_1v" id="APPLIMIT_1v" value="<?php print number_format($APPLIMIT_1, $decFormat); ?>" onChange="changeLimit(1)" <?php if($task == 'add') { ?> disabled <?php } ?> />
		                                    </div>
		                            	</div>
		                            </div>
		                        </div>
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-2 control-label"><?php echo $Approver ?> 2</label>
		                            <div class="col-sm-10">
		                                <div class="row">
		                                    <div class="col-xs-12">
		                                    	<select name="APPROVER_2" id="APPROVER_2" class="form-control select2" onChange="getChange(2)" <?php if($task == 'add') { ?> disabled <?php } ?>>
						                            <option value=""> --- </option>
						                            <?php
														foreach($resGetEMPN as $rowEMPN) :
															$Emp_ID2		= $rowEMPN->Emp_ID;			
															$First_Name2	= $rowEMPN->First_Name;
															$Last_Name2		= $rowEMPN->Last_Name;
															$compName2		= "$First_Name2 $Last_Name2";
															?>
																<option value="<?php echo $Emp_ID2;?>" <?php if($APPROVER_2 == $Emp_ID2) { ?>selected<?php } ?>><?php echo $compName2;?></option>
															<?php
														endforeach;
						                            ?>
						                        </select>
		                                    </div>
		                                    <div class="col-xs-3" style="display: none;">
		                                    	<input type="hidden" maxlength="15" name="APPLIMIT_2" id="APPLIMIT_2" size="15" value="<?php echo $APPLIMIT_2; ?>" />
		                       					<input type="text" class="form-control" style="max-width:150px; text-align:right" maxlength="15" name="APPLIMIT_2v" id="APPLIMIT_2v" value="<?php print number_format($APPLIMIT_2, $decFormat); ?>" onChange="changeLimit(2)" <?php if($task == 'add') { ?> disabled <?php } ?> />
		                                    </div>
		                            	</div>
		                            </div>
		                        </div>
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-2 control-label"><?php echo $Approver ?> 3</label>
		                            <div class="col-sm-10">
		                                <div class="row">
		                                    <div class="col-xs-12">
		                                    	<select name="APPROVER_3" id="APPROVER_3" class="form-control select2" onChange="getChange(3)" <?php if($task == 'add') { ?> disabled <?php } ?>>
						                            <option value=""> --- </option>
						                            <?php
														foreach($resGetEMPN as $rowEMPN) :
															$Emp_ID3		= $rowEMPN->Emp_ID;			
															$First_Name3	= $rowEMPN->First_Name;
															$Last_Name3		= $rowEMPN->Last_Name;
															$compName3		= "$First_Name3 $Last_Name3";
															?>
																<option value="<?php echo $Emp_ID3;?>" <?php if($APPROVER_3 == $Emp_ID3) { ?>selected<?php } ?>><?php echo $compName3;?></option>
															<?php
														endforeach;
						                            ?>
						                        </select>
		                                    </div>
		                                    <div class="col-xs-3" style="display: none;">
		                                    	<input type="hidden" maxlength="15" name="APPLIMIT_3" id="APPLIMIT_3" size="15" value="<?php echo $APPLIMIT_3; ?>" />
		                        				<input type="text" class="form-control" style="max-width:150px; text-align:right" maxlength="15" name="APPLIMIT_3v" id="APPLIMIT_3v" value="<?php print number_format($APPLIMIT_3, $decFormat); ?>" onChange="changeLimit(3)" <?php if($task == 'add') { ?> disabled <?php } ?> />
		                                    </div>
		                            	</div>
		                            </div>
		                        </div>
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-2 control-label"><?php echo $Approver ?> 4</label>
		                            <div class="col-sm-10">
		                                <div class="row">
		                                    <div class="col-xs-12">
		                                    	<select name="APPROVER_4" id="APPROVER_4" class="form-control select2" onChange="getChange(4)" <?php if($task == 'add') { ?> disabled <?php } ?>>
						                            <option value=""> --- </option>
						                            <?php
														foreach($resGetEMPN as $rowEMPN) :
															$Emp_ID4		= $rowEMPN->Emp_ID;			
															$First_Name4	= $rowEMPN->First_Name;
															$Last_Name4		= $rowEMPN->Last_Name;
															$compName4		= "$First_Name4 $Last_Name4";
															?>
																<option value="<?php echo $Emp_ID4;?>" <?php if($APPROVER_4 == $Emp_ID4) { ?>selected<?php } ?>><?php echo $compName4;?></option>
															<?php
														endforeach;
						                            ?>
						                        </select>
		                                    </div>
		                                    <div class="col-xs-3" style="display: none;">
		                                    	<input type="hidden" maxlength="15" name="APPLIMIT_4" id="APPLIMIT_4" size="15" value="<?php echo $APPLIMIT_4; ?>" />
		                        				<input type="text" class="form-control" style="max-width:150px; text-align:right" maxlength="15" name="APPLIMIT_4v" id="APPLIMIT_4v" value="<?php print number_format($APPLIMIT_4, $decFormat); ?>" onChange="changeLimit(4)" <?php if($task == 'add') { ?> disabled <?php } ?> />
		                                    </div>
		                            	</div>
		                            </div>
		                        </div>
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-2 control-label"><?php echo $Approver ?> 5</label>
		                            <div class="col-sm-10">
		                                <div class="row">
		                                    <div class="col-xs-12">
		                                    	<select name="APPROVER_5" id="APPROVER_5" class="form-control select2" onChange="getChange(5)" <?php if($task == 'add') { ?> disabled <?php } ?>>
						                            <option value=""> --- </option>
						                            <?php
														foreach($resGetEMPN as $rowEMPN) :
															$Emp_ID5		= $rowEMPN->Emp_ID;			
															$First_Name5	= $rowEMPN->First_Name;
															$Last_Name5		= $rowEMPN->Last_Name;
															$compName5		= "$First_Name5 $Last_Name5";
															?>
																<option value="<?php echo $Emp_ID5;?>" <?php if($APPROVER_5 == $Emp_ID5) { ?>selected<?php } ?>><?php echo $compName5;?></option>
															<?php
														endforeach;
						                            ?>
						                        </select>
		                                    </div>
		                                    <div class="col-xs-3" style="display: none;">
		                                    	<input type="hidden" maxlength="15" name="APPLIMIT_5" id="APPLIMIT_5" size="15" value="<?php echo $APPLIMIT_5; ?>" />	
		                        				<input type="text" class="form-control" style="max-width:150px; text-align:right" maxlength="15" name="APPLIMIT_5v" id="APPLIMIT_5v" value="<?php print number_format($APPLIMIT_5, $decFormat); ?>" onChange="changeLimit(5)" <?php if($task == 'add') { ?> disabled <?php } ?> />
		                                    </div>
		                            	</div>
		                            </div>
		                        </div>
		                        <br>
		                        <div class="form-group">
		                            <div class="col-sm-offset-2 col-sm-10">
		                            <?php
										if($task=='add')
										{
											?>
												<button class="btn btn-primary" onClick="getstatFORM(1)">
												<i class="fa fa-save"></i>
												</button>&nbsp;
											<?php
										}
										else
										{
											?>
												<button class="btn btn-primary" onClick="getstatFORM(1)" >
												<i class="fa fa-save"></i>
												</button>&nbsp;
												<button class="btn btn-warning" onClick="getstatFORM(2)">
												<i class="fa fa-trash"></i>
												</button>&nbsp;
											<?php
										}
										$backURL	= site_url('c_setting/c_docapproval/?id=');
										echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>');
									?>
		                            </div>
		                        </div>
		                    </form>
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

	function getstatFORM(thisval)
	{
		document.getElementById('statFORM').value = thisval;
	}
	
	function checkINP(thisval)
	{
		PRJCODE		= document.getElementById('PRJCODE').value;
		MENU_CODE	= document.getElementById('MENU_CODE').value;
		APPROVER_1	= document.getElementById('APPROVER_1').value;

		if(PRJCODE == "none")
        {
            swal('<?php echo $CompanyName; ?> can not be empty',
	        {
	            icon: "warning",
	        })
	        .then(function()
	        {
	            swal.close();
	            $('#PRJCODE').focus();
	        });
	        return false;
        }

		if(MENU_CODE == "")
        {
            swal('<?php echo $ApprovalName; ?> can not be empty',
	        {
	            icon: "warning",
	        })
	        .then(function()
	        {
	            swal.close();
	            $('#MENU_CODE').focus();
	        });
	        return false;
        }

		if(APPROVER_1 == "")
        {
            swal('<?php echo $Approver; ?> 1 can not be empty',
	        {
	            icon: "warning",
	        })
	        .then(function()
	        {
	            swal.close();
	            $('#APPROVER_1').focus();
	        });
	        return false;
        }
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
	
	function RoundNDecimal(X, N)
	{
		var T, S=new String(Math.round(X*Number("1e"+N)))
		while (S.length<=N) S='0'+S
		return S.substr(0, T=(S.length-N)) + '.' + S.substr(T, N)
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