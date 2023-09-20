<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 23 Maret 2017
 * File Name	= joblist_form.php
 * Location		= -
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody    = $this->session->userdata('appBody');

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
	$JOBCODEID	= '';
	$JOBCODEIDV	= '';
	$JOBPARENT	= '';
	$PRJCODE	= $PRJCODE;
	$JOBCOD1 	= '';
	$JOBCOD2 	= '';
	$JOBDESC 	= '';
	$JOBCLASS	= '';
	$JOBGRP 	= '';
	$JOBTYPE 	= '';
	$JOBLEV		= 1;
	$JOBUNIT	= '';
	$JOBVOLM	= 0;
	$JOBCOST	= 0;
	$ISLAST		= '';
	$ITM_NEED	= '';
}
else
{
	$JOBCODEID 	= $default['JOBCODEID'];
	$JOBCODEIDV	= $default['JOBCODEIDV'];
	$JOBPARENT	= $default['JOBPARENT'];
	$PRJCODE 	= $default['PRJCODE'];
	$JOBCOD1 	= '';
	$JOBCOD2 	= $default['JOBCOD1'];
	$JOBDESC 	= $default['JOBDESC'];
	$JOBCLASS	= $default['JOBCLASS'];
	$JOBGRP 	= $default['JOBGRP'];
	$JOBTYPE 	= $default['JOBTYPE'];
	$JOBLEV 	= $default['JOBLEV'];
	$JOBUNIT 	= $default['JOBUNIT'];
	$JOBVOLM 	= $default['JOBVOLM'];
	$JOBCOST 	= $default['JOBCOST'];
	$ISLAST 	= $default['ISLAST'];
	$ITM_NEED 	= $default['ITM_NEED'];
	$Patt_Number= $default['Patt_Number'];
}

$Pattern_Length	= 5;
$sqlJOBL		= "tbl_joblist WHERE PRJCODE = '$PRJCODE'";
$sqlJOBL		= $this->db->count_all($sqlJOBL);
$sqlJOBLN		= $sqlJOBL + 1;
$len 			= strlen($sqlJOBLN);
$nol			= '';	
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
$JOB_CODE = $nol.$sqlJOBLN;
if($task == 'add')
{
	$JOBCODEID		= "WBS$PRJCODE$JOB_CODE";
	$JOBCODEIDV		= "1$PRJCODE$JOB_CODE";
	$Patt_Number	= $sqlJOBLN;
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
          $vers     = $this->session->userdata('vers');

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
    		
    		if($TranslCode == 'Add')$Add = $LangTransl;
    		if($TranslCode == 'Edit')$Edit = $LangTransl;
    		if($TranslCode == 'Save')$Save = $LangTransl;
    		if($TranslCode == 'Update')$Update = $LangTransl;
    		if($TranslCode == 'Back')$Back = $LangTransl;
    		if($TranslCode == 'JobCode')$JobCode = $LangTransl;
    		if($TranslCode == 'Description')$Description = $LangTransl;
    		if($TranslCode == 'Class')$Class = $LangTransl;
    		if($TranslCode == 'Group')$Group = $LangTransl;
    		if($TranslCode == 'Type')$Type = $LangTransl;
    		if($TranslCode == 'Unit')$Unit = $LangTransl;
    		if($TranslCode == 'Level')$Level = $LangTransl;
    		if($TranslCode == 'Volume')$Volume = $LangTransl;
    		if($TranslCode == 'Cost')$Cost = $LangTransl;
    		if($TranslCode == 'LastLevel')$LastLevel = $LangTransl;
    		if($TranslCode == 'Parent')$Parent = $LangTransl;
    		if($TranslCode == 'ItemNeed')$ItemNeed = $LangTransl;
    	endforeach;

        $comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
        <section class="content-header">
            <h1>
                <?php echo $h2_title; ?>
                <small><?php echo $PRJNAME; ?></small>
            </h1>
        </section>
        
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
                        	<form class="form-horizontal" name="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return saveJobList()">
                            	<input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
                                <div class="form-group">
                                  	<label for="inputName" class="col-sm-2 control-label"><?php echo $JobCode ?> </label>
                                  	<div class="col-sm-10">
                                        <label>
                                        <input type="text" name="JOB_CODE1" id="JOB_CODE1" class="form-control" style="max-width:100px" value="<?php echo $JOB_CODE; ?>" onChange="functioncheck(this.value)" <?php if($task == 'edit') { ?> disabled <?php } ?>/>
                                        <input type="hidden" name="JOBCODEID" id="JOBCODEID" value="<?php echo $JOBCODEID; ?>" />
                                        <input type="hidden" name="JOBCODEIDV" id="JOBCODEIDV" value="<?php echo $JOBCODEIDV; ?>" />
                                        <input type="hidden" name="PRJCODE" id="PRJCODE" value="<?php echo $PRJCODE; ?>" />
                                        <input type="hidden" name="JOB_CODE" id="JOB_CODE" value="<?php echo $JOB_CODE; ?>" />
                                        <input type="hidden" name="Patt_Number" id="Patt_Number" value="<?php echo $Patt_Number; ?>" />
                                    	</label><label>&nbsp;&nbsp;</label><label id="theCode"></label>&nbsp;&nbsp;&nbsp;
                                    	<input type="hidden" name="CheckThe_Code" id="CheckThe_Code" size="20" maxlength="25" value="0" >
                                  	</div>
                                </div>
        						<script>
                                    function functioncheck()
                                    {
                                        JOBCODEID	= document.getElementById('JOBCODEID').value;
                                        JOB_CODE1	= document.getElementById('JOB_CODE1').value;
                                        PRJCODE		= document.getElementById('PRJCODE').value;
                                        //document.getElementById('JOB_CODE').value	= JOB_CODE1;
        								
                                        var ajaxRequest;
                                        try
                                        {
                                            ajaxRequest = new XMLHttpRequest();
                                        }
                                        catch (e)
                                        {
                                            swal("Something is wrong");
                                            return false;
                                        }
                                        
                                        ajaxRequest.onreadystatechange = function()
                                        {
                                            if(ajaxRequest.readyState == 4)
                                            {
                                                recordcount = ajaxRequest.responseText;
                                                if(recordcount > 0)
                                                {
                                                    document.getElementById('CheckThe_Code').value	= recordcount;
                                                    document.getElementById("theCode").innerHTML 	= ' The code already exist ... !';
                                                    document.getElementById("theCode").style.color 	= "#ff0000";
        											//document.getElementById("JOBCODEID").value		= '';
                                                }
                                                else
                                                {
                                                    document.getElementById('CheckThe_Code').value	= recordcount;
                                                    document.getElementById("theCode").innerHTML 	= ' The code : OK ... !';
                                                    document.getElementById("theCode").style.color 	= "green";
        											//document.getElementById("JOBCODEID").value		= PRJCODE + JOB_CODE1;
                                                }
                                            }				
                                        }
                                        var JOB_CODE1 	= document.getElementById('JOB_CODE1').value;
        								var data		= JOB_CODE1+'~'+PRJCODE;
        								
                                        ajaxRequest.open("GET", "<?php echo base_url().'index.php/c_project/c_joblist/getJOBCODE/';?>" + data, true);
                                        ajaxRequest.send(null);
                                    }
                                </script>
                                <div class="form-group">
                                  	<label for="inputName" class="col-sm-2 control-label"><?php echo $Description ?> </label>
                                  	<div class="col-sm-10">
                                        <textarea class="form-control" name="JOBDESC" id="JOBDESC" style="max-width:350px;height:70px" ><?php echo $JOBDESC; ?></textarea>
                                  	</div>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail" class="col-sm-2 control-label"><?php echo $Parent ?></label>
                                    <div class="col-sm-10">
                                        <select name="JOBPARENT" id="JOBPARENT" class="form-control" style="max-width:250px" onChange="getLevel()" >
                                            <option value="0" > ---- None ----</option>
                                            <?php
                                            if($countParent>0)
                                            {
                                                $i = 0;
                                                foreach($vwParent as $row) :
                                                    $JOBCODEID1		= $row->JOBCODEID;
                                                    $JOBPARENT1		= $row->JOBPARENT;
                                                    $JOBDESC1		= $row->JOBDESC;
                                                    $space_level1	= "";
                                                    
                                                    $sqlC1		= "tbl_joblist WHERE JOBPARENT = '$JOBCODEID1' AND ISLAST = 0";
                                                    $ressqlC1 	= $this->db->count_all($sqlC1);
                                                    ?>
                                                        <option value="<?php echo $JOBCODEID1;?>" <?php if($JOBCODEID1 == $JOBPARENT) { ?>selected<?php } if($ressqlC1>0) {?> style="font-weight:bold"<?php } ?>><?php echo "$space_level1$JOBDESC1";?></option>
                                                    <?php
                                                    if($ressqlC1 > 0)
                                                    {
                                                        $sqlDEPT	= "SELECT JOBCODEID, JOBDESC, JOBPARENT
                                                                        FROM tbl_joblist
                                                                        WHERE JOBPARENT = '$JOBCODEID1' AND ISLAST = 0";
                                                        $resDEPT 	= $this->db->query($sqlDEPT)->result();
                                                        foreach($resDEPT as $rowDept) :
                                                            $JOBCODEID2		= $rowDept->JOBCODEID;
                                                            $JOBDESC2		= $rowDept->JOBDESC;
                                                            $JOBPARENT2	= $rowDept->JOBPARENT1;
                                                            $space_level2	= "&nbsp;&nbsp;&nbsp;";
                                                            
                                                            $sqlC2		= "tbl_joblist WHERE JOBPARENT = '$JOBCODEID2' AND ISLAST = 0";
                                                            $ressqlC2 	= $this->db->count_all($sqlC2);
                                                            ?>
                                                                <option value="<?php echo $JOBCODEID2;?>" <?php if($JOBCODEID2 == $JOBPARENT) { ?>selected<?php } if($ressqlC2>0) {?> style="font-weight:bold"<?php } ?>><?php echo "$space_level2$JOBDESC2";?></option>
                                                            <?php
                                                            if($ressqlC2 > 0)
                                                            {
                                                                $sqlDIV	= "SELECT JOBCODEID, JOBDESC, JOBPARENT
                                                                                FROM tbl_joblist
                                                                                WHERE JOBPARENT = '$JOBCODEID2' AND ISLAST = 0";
                                                                $resDIV 	= $this->db->query($sqlDIV)->result();
                                                                foreach($resDIV as $rowDIV) :
                                                                    $JOBCODEID3		= $rowDIV->JOBCODEID;
                                                                    $JOBDESC3		= $rowDIV->JOBDESC;
                                                                    $JOBPARENT3	= $rowDIV->JOBPARENT1;
                                                                    $space_level3	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    
                                                                    $sqlC3		= "tbl_joblist WHERE JOBPARENT = '$JOBCODEID3' AND ISLAST = 0";
                                                                    $ressqlC3 	= $this->db->count_all($sqlC3);		
                                                                    ?>
                                                                        <option value="<?php echo $JOBCODEID3;?>" <?php if($JOBCODEID3 == $JOBPARENT) { ?>selected<?php } if($ressqlC3>0) {?> style="font-weight:bold"<?php } ?>><?php echo "$space_level3$JOBDESC3";?></option>
                                                                    <?php
                                                                    if($ressqlC3 > 0)
                                                                    {
                                                                        $sqlUNT	= "SELECT JOBCODEID, JOBDESC, JOBPARENT
                                                                                        FROM tbl_joblist
                                                                                        WHERE JOBPARENT = '$JOBCODEID3' AND ISLAST = 0";
                                                                        $resUNT 	= $this->db->query($sqlUNT)->result();
                                                                        foreach($resUNT as $rowUNT) :
                                                                            $JOBCODEID4		= $rowUNT->JOBCODEID;
                                                                            $JOBDESC4		= $rowUNT->JOBDESC;
                                                                            $JOBPARENT4	= $rowUNT->JOBPARENT1;
                                                                            $space_level4	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                            
                                                                            $sqlC4		= "tbl_joblist WHERE JOBPARENT = '$JOBCODEID4' AND ISLAST = 0";
                                                                            $ressqlC4 	= $this->db->count_all($sqlC4);		
                                                                            ?>
                                                                                <option value="<?php echo $JOBCODEID4;?>" <?php if($JOBCODEID4 == $JOBPARENT) { ?>selected<?php } if($ressqlC4>0) {?> style="font-weight:bold"<?php } ?>><?php echo "$space_level4$JOBDESC4";?></option>
                                                                            <?php
                                                                            if($ressqlC4 > 0)
                                                                            {
                                                                                $sqlURS	= "SELECT JOBCODEID, JOBDESC, JOBPARENT
                                                                                                FROM tbl_joblist
                                                                                                WHERE JOBPARENT = '$JOBCODEID4' AND ISLAST = 0";
                                                                                $resURS 	= $this->db->query($sqlURS)->result();
                                                                                foreach($resURS as $rowURS) :
                                                                                    $JOBCODEID5		= $rowURS->JOBCODEID;
                                                                                    $JOBDESC5		= $rowURS->JOBDESC;
                                                                                    $JOBPARENT5	= $rowURS->JOBPARENT1;
                                                                                    $space_level5	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                                    
                                                                                    $sqlC5		= "tbl_joblist WHERE JOBPARENT = '$JOBCODEID5'";
                                                                                    $ressqlC5 	= $this->db->count_all($sqlC4);		
                                                                                    ?>
                                                                                        <option value="<?php echo $JOBCODEID5;?>" <?php if($JOBCODEID5 == $JOBPARENT) { ?>selected<?php } if($ressqlC5>0) {?> style="font-weight:bold"<?php } ?>><?php echo "$space_level5$JOBDESC5";?></option>
                                                                                    <?php
                                                                                    if($ressqlC5 > 0)
                                                                                    {
                                                                                        $sqlSTAF	= "SELECT JOBCODEID, JOBDESC, JOBPARENT
                                                                                                        FROM tbl_joblist
                                                                                                        WHERE JOBPARENT = '$JOBCODEID5'";
                                                                                        $resSTAF 	= $this->db->query($sqlSTAF)->result();
                                                                                        foreach($resSTAF as $rowSTAF) :
                                                                                            $JOBCODEID6		= $rowSTAF->JOBCODEID;
                                                                                            $JOBDESC6		= $rowSTAF->JOBDESC;
                                                                                            $JOBPARENT6	= $rowSTAF->JOBPARENT1;
                                                                                            $space_level6	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                                            ?>
                                                                                                <option value="<?php echo $JOBCODEID5;?>" <?php if($JOBCODEID5 == $JOBPARENT) { ?>selected<?php } if($ressqlC5>0) {?> style="font-weight:bold"<?php } ?>><?php echo "$space_level5$JOBDESC5";?></option>
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
                                <script>
        							function getLevel()
        							{
        								var ajaxRequest;
        								try
        								{
        									ajaxRequest = new XMLHttpRequest();
        								}
        								catch (e)
        								{
        									swal("Something is wrong");
        									return false;
        								}
        								ajaxRequest.onreadystatechange = function()
        								{
        									if(ajaxRequest.readyState == 4)
        									{
        										recordcount 	= ajaxRequest.responseText;
        										var arr 		= recordcount.split('~');
        										var	JOBLEV		= arr[0];
        										var	JOBCODEIDV	= arr[1];
        										var JOB_CODE	= arr[2];
        										var PATT_NUM	= arr[3];
        										document.getElementById('JOBLEV').value			= JOBLEV;
        										document.getElementById('JOBCODEIDV').value		= JOBCODEIDV;
        										document.getElementById('JOB_CODE').value		= JOB_CODE;
        										document.getElementById('JOB_CODE1').value		= JOB_CODE;
        										document.getElementById('Patt_Number').value	= PATT_NUM;
        									}
        								}
        								var JOBPARENT 	= document.getElementById('JOBPARENT').value;
        								var PRJCODE 	= document.getElementById('PRJCODE').value;
        								data			= JOBPARENT+'~'+PRJCODE;
        								
        								ajaxRequest.open("GET", "<?php echo base_url().'index.php/c_project/c_joblist/getJLEV/';?>" + data, true);
        								ajaxRequest.send(null);
        							}
        						</script>
                                <div class="form-group">
                                  	<label for="inputName" class="col-sm-2 control-label"><?php echo $Level ?> </label>
                                  	<div class="col-sm-10">
                                    <select name="JOBLEV" id="JOBLEV" class="form-control" style="max-width:60px">
                                        <option value="1" <?php if($JOBLEV == '1') { ?>selected <?php } ?>> 1 </option>
                                        <option value="2" <?php if($JOBLEV == '2') { ?>selected <?php } ?>> 2 </option>
                                        <option value="3" <?php if($JOBLEV == '3') { ?>selected <?php } ?>> 3 </option>
                                        <option value="4" <?php if($JOBLEV == '4') { ?>selected <?php } ?>> 4 </option>
                                        <option value="5" <?php if($JOBLEV == '5') { ?>selected <?php } ?>> 5 </option>
                                  	</select>
                                  	</div>
                                </div>
                                <div class="form-group">
                                  	<label for="inputName" class="col-sm-2 control-label"><?php echo $Group ?> </label>
                                  	<div class="col-sm-10">
                                    <select name="JOBGRP" id="JOBGRP" class="form-control" style="max-width:150px">
                                        <option value="" <?php if($JOBGRP == '') { ?>selected <?php } ?>> - </option>
                                        <option value="M" <?php if($JOBGRP == 'M') { ?>selected <?php } ?>> M -Material </option>
                                        <option value="U" <?php if($JOBGRP == 'U') { ?>selected <?php } ?>> U -Upah </option>
                                        <option value="S" <?php if($JOBGRP == 'S') { ?>selected <?php } ?>> S -Servis </option>
                                        <option value="T" <?php if($JOBGRP == 'T') { ?>selected <?php } ?>> T -Tools </option>
                                        <option value="I" <?php if($JOBGRP == 'I') { ?>selected <?php } ?>> I -Indirect </option>
                                        <option value="R" <?php if($JOBGRP == 'R') { ?>selected <?php } ?>> R -Reimburstment </option>
                                  	</select>
                                  	</div>
                                </div>
                                <div class="form-group">
                                  	<label for="inputName" class="col-sm-2 control-label"><?php echo $Class ?> </label>
                                  	<div class="col-sm-10">
                                    <select name="JOBCLASS" id="JOBCLASS" class="form-control" style="max-width:100px">
                                        <option value="" <?php if($JOBCLASS == '') { ?>selected <?php } ?>> - </option>
                                        <option value="ANSTR" <?php if($JOBCLASS == 'ANSTR') { ?>selected <?php } ?>> An. STR </option>
                                        <option value="WALL" <?php if($JOBCLASS == 'WALL') { ?>selected <?php } ?>> Dinding </option>
                                        <option value="FLOOR" <?php if($JOBCLASS == 'FLOOR') { ?>selected <?php } ?>> Lantai </option>
                                        <option value="SUNT" <?php if($JOBCLASS == 'SUNT') { ?>selected <?php } ?>> Sanitair </option>
                                        <option value="SUND" <?php if($JOBCLASS == 'SUND') { ?>selected <?php } ?>> Sundries </option>
                                        <option value="OTHR" <?php if($JOBCLASS == 'OTHR') { ?>selected <?php } ?>> Others </option>
                                  	</select>
                                  	</div>
                                </div>
                                <div class="form-group">
                                  	<label for="inputName" class="col-sm-2 control-label"><?php echo $Type ?> </label>
                                  	<div class="col-sm-10">
                                    <select name="JOBTYPE" id="JOBTYPE" class="form-control" style="max-width:150px">
                                        <option value="" <?php if($JOBTYPE == '') { ?>selected <?php } ?>> - </option>
                                        <option value="S" <?php if($JOBTYPE == 'S') { ?>selected <?php } ?>> S - Selfdone </option>
                                        <option value="C" <?php if($JOBTYPE == 'C') { ?>selected <?php } ?>> C - Subcontracted </option>
                                        <option value="O" <?php if($JOBTYPE == 'O') { ?>selected <?php } ?>> O - Others </option>
                                  	</select>
                                  	</div>
                                </div>
                                <div class="form-group">
                                  	<label for="inputName" class="col-sm-2 control-label"><?php echo $Unit ?> </label>
                                  	<div class="col-sm-10">
                                    <select name="JOBUNIT" id="JOBUNIT" class="form-control" style="max-width:100px">
        							<?php
                                        $sqlUnit	= "SELECT Unit_Type_Code, Unit_Type_Name FROM tbl_unittype ORDER BY Unit_Type_Name";
                                        $sqlUnit	= $this->db->query($sqlUnit)->result();
                                        foreach($sqlUnit as $row) :
                                            $Type_Code		= $row->Unit_Type_Code;
                                            $Unit_Type_Name	= $row->Unit_Type_Name;
        									?>
        										<option value="<?php echo $Type_Code; ?>" <?php if($Type_Code == $JOBUNIT) { ?>selected <?php } ?>>
        											<?php echo $Unit_Type_Name; ?>
        										</option>
        									<?php
                                        endforeach;
                                    ?>
                                  	</select>
                                  	</div>
                                </div>
                                <div class="form-group">
                                  	<label for="inputName" class="col-sm-2 control-label"><?php echo $Volume ?> </label>
                                  	<div class="col-sm-10">
                                    	<input type="text" class="form-control" style="max-width:150px; text-align:right;" name="JOBVOLM1" id="JOBVOLM1" value="<?php print number_format($JOBVOLM, $decFormat); ?>" onBlur="getJOBVOLM(this)">
                            			<input type="hidden" class="form-control" style="max-width:150px; text-align:right;" name="JOBVOLM" id="JOBVOLM" value="<?php echo $JOBVOLM; ?>">
                                  	</div>
                                </div>
        						<script>
                                    function getJOBVOLM(thisVal)
                                    {
                                        var decFormat	= document.getElementById('decFormat').value;
                                        var thisVal		= eval(thisVal).value.split(",").join("");
                                        JOBVOLM			= thisVal;
                                        document.getElementById('JOBVOLM').value 	= JOBVOLM;
                                        document.getElementById('JOBVOLM1').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.round(JOBVOLM)),decFormat));
                                    }
                                </script>
                                <div class="form-group">
                                  	<label for="inputName" class="col-sm-2 control-label"><?php echo $Cost ?>  (IDR)</label>
                                  	<div class="col-sm-10">
                                    	<input type="text" class="form-control" style="max-width:150px; text-align:right;" name="JOBCOST1" id="JOBCOST1" value="<?php print number_format($JOBCOST, $decFormat); ?>" onBlur="getJOBCOST(this)">
                            			<input type="hidden" class="form-control" style="max-width:150px; text-align:right;" name="JOBCOST" id="JOBCOST" value="<?php echo $JOBCOST; ?>">
                                  	</div>
                                </div>
        						<script>
                                    function getJOBCOST(thisVal)
                                    {
                                        var decFormat	= document.getElementById('decFormat').value;
                                        var thisVal		= eval(thisVal).value.split(",").join("");
                                        JOBCOST			= thisVal;
                                        document.getElementById('JOBCOST').value 	= JOBCOST;
                                        document.getElementById('JOBCOST1').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.round(JOBCOST)),decFormat));
                                    }
                                </script>
                                <div class="form-group">
                                  	<label for="inputName" class="col-sm-2 control-label"><?php echo $ItemNeed; ?>?</label>
                                  	<div class="col-sm-10">
                                    <select name="ITM_NEED" id="ITM_NEED" class="form-control" style="max-width:70px">
                                        <option value="" <?php if($ITM_NEED == '') { ?>selected <?php } ?>> - </option>
                                        <option value="1" <?php if($ITM_NEED == '1') { ?>selected <?php } ?>> Yes </option>
                                        <option value="0" <?php if($ITM_NEED == '0') { ?>selected <?php } ?>> No </option>
                                  	</select>
                                  	</div>
                                </div>
                                <div class="form-group">
                                  	<label for="inputName" class="col-sm-2 control-label"><?php echo $LastLevel ?>?</label>
                                  	<div class="col-sm-10">
                                    <select name="ISLAST" id="ISLAST" class="form-control" style="max-width:70px">
                                        <option value="" <?php if($ISLAST == '') { ?>selected <?php } ?>> - </option>
                                        <option value="1" <?php if($ISLAST == '1') { ?>selected <?php } ?>> Yes </option>
                                        <option value="0" <?php if($ISLAST == '0') { ?>selected <?php } ?>> No </option>
                                  	</select>
                                  	</div>
                                </div>
                                <br>
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <!--<input type="submit" name="submitAdd" id="submitAdd" class="btn btn-primary" value="Save" onClick="return buttonShowPhoto(1)">&nbsp;-->
                                        <button class="btn btn-primary" onClick="return buttonShowPhoto(1)"> <i class="cus-save-16x16"></i>&nbsp;&nbsp;<?php echo $Save;?></button>&nbsp;
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
	function saveJobList()
	{
		CheckThe_Code = document.getElementById('CheckThe_Code').value;
		if(CheckThe_Code > 0)
		{
			swal('Job Code is already exist.');
			document.getElementById('JOB_CODE').value = '';
			document.getElementById('JOB_CODE').focus();
			JOB_CODE = document.getElementById('JOB_CODE').value;
			functioncheck()
			return false;
		}
		
		JOB_CODE = document.getElementById('JOB_CODE').value;
		if(JOB_CODE == '')
		{
			swal('Job Code can not be empty.');
			document.getElementById('JOB_CODE1').focus();
			return false;			
		}
		
		JOBDESC = document.getElementById('JOBDESC').value;
		if(JOBDESC == '')
		{
			swal('Job Description can not be empty.');
			document.getElementById('JOBDESC').focus();
			return false;			
		}
		
		JOBCLASS = document.getElementById('JOBCLASS').value;
		if(JOBCLASS == '')
		{
			swal('Please select one of Job Class.');
			document.getElementById('JOBCLASS').focus();
			return false;			
		}
		
		JOBTYPE = document.getElementById('JOBTYPE').value;
		if(JOBTYPE == '')
		{
			swal('Please select one of Job Type.');
			document.getElementById('JOBTYPE').focus();
			return false;			
		}
		
		ISLAST = document.getElementById('ISLAST').value;
		if(ISLAST == '')
		{
			swal('Please select Last / Not Last Job Condition.');
			document.getElementById('ISLAST').focus();
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