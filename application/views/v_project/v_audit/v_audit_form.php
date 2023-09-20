<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 8 Agustus 2019
 * File Name	= v_audit_form.php
 * Location		= -
*/
$this->load->view('template/head');

$appName    = $this->session->userdata('appName');
$appBody    = $this->session->userdata('appBody');
$comp_color = $this->session->userdata('comp_color');
$FlagUSER   = $this->session->userdata['FlagUSER'];
$DefEmp_ID  = $this->session->userdata['Emp_ID'];

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
	$sqlMAXMN 	= "SELECT COUNT(*) AS MAXSTEP FROM tbl_audit_repot";
	$resMAXMN 	= $this->db->query($sqlMAXMN)->result();
	foreach($resMAXMN as $rowMAXMN) :
		$MAXDOC = $rowMAXMN->MAXSTEP;
	endforeach;
	$MAXDOC	= $MAXDOC + 1;
	
	$result = $this->db->get('tbl_audit_repot')->result();
	$myMax = $MAXDOC;
		
	$lastPatternNumb = $myMax;
	$len = strlen($lastPatternNumb);
	
	$Pattern_Length	= 3;
	
	if($Pattern_Length==2)
	{
		if($len==1) $nol="0";
	}
	elseif($Pattern_Length==3)
	{
		if($len==1) $nol="00";else if($len==2) $nol="0";else $nol="";
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
	$DocNumber 		= date('YmdHis')."-".$lastPatternNumb;
	
	$AUI_NUM		= $DocNumber;	
	$AUI_CODE		= '135';
	$AUI_CODE1		= '';
	$PRJCODE		= '';	
	$PRJNAME		= '';
	$AUI_STEP		= '';
	$AUI_ORDNO		= '';
	$AUI_INIT		= '';
	$AUI_DEPT		= '';
	$AUI_SUBJEK		= '';
	$AUI_LOC		= '';
	$AUI_CC			= '';
	$AUI_DATE		= date('m/d/Y');
	$AUI_DATE_NCR	= date('m/d/Y');
	$tgl1 			= date('Y-m-d');
	$tgl2 			= date('Y-m-d', strtotime('+3 days', strtotime($tgl1)));
	$AUI_TARGETD	= date('m/d/Y', strtotime($tgl2));
	$AUI_AUDITOR	= '';
	$AUI_REFDOC		= '';
	$AUI_PROBLDESC	= '';
	$AUI_KLAUS1		= '';
	$AUI_KLAUS2		= '';
	$AUI_KLAUS3		= '';
	$AUI_KLAUS4		= '';
	$AUI_TYPE		= '';
	$AUI_SCOPE1		= '';
	$AUI_SCOPE2		= '';
	$AUI_SCOPE3		= '';
	$AUI_SYSPROC1	= '';
	$AUI_SYSPROC2	= '';
	$AUI_SYSPROC3	= '';
	$AUI_SYSPROC4	= '';
	$AUI_CAUSE		= '';
	$AUI_CORACT		= '';
	$AUI_CORSTEP	= '';
	$AUI_PREVENT	= '';
	$AUI_FINISHP	= date('m/d/Y');
	$AUI_EVIDEN		= '';
	$AUI_REVIEWD	= date('m/d/Y');
	$AUI_CONCL		= '';
	$AUI_NCRNO		= '';
	$AUI_NCRD		= date('m/d/Y');
	$AUI_NOTESREV	= '';
	$AUI_AUDITOR1	= '';
	$AUI_AUDITOR2	= '';
	$AUI_SIGN1		= '';
	$AUI_SIGN2		= '';
	$AUI_STAT1		= 0;
	$AUI_STAT2		= 0;
	$AUI_STAT3		= 0;
	
	// CATATAN
	$AUN_NUM		= date('YmdHis')."-".$lastPatternNumb;
	$AUN_CODE		= '';
	$AUN_DATE		= date('m/d/Y');
	$AUN_DEPT		= '';
	$AUN_AUDITEE	= '';
	$AUN_ACUAN		= '';
	$AUN_AUDITOR	= '';
	$AUN_STAT		= 0;
	$AUN_DESC		= '';
	$TYPE			= 1;
	
}	
else
{
	$AUI_NUM		= $default['AUI_NUM'];
	$DocNumber		= $AUI_NUM;
	$AUI_CODE		= $default['AUI_CODE'];
	$PRJCODE		= $default['PRJCODE'];
	$PRJNAME		= $default['PRJNAME']; 
	$AUI_STEP		= $default['AUI_STEP'];
	$AUI_ORDNO		= $default['AUI_ORDNO'];
	$AUI_INIT		= $default['AUI_INIT'];
	$AUI_DEPT		= $default['AUI_DEPT'];
	$AUI_SUBJEK		= $default['AUI_SUBJEK'];
	$AUI_LOC		= $default['AUI_LOC'];
	$AUI_CC			= $default['AUI_CC'];
	$AUI_DATE		= $default['AUI_DATE'];
	$AUI_TARGETD	= $default['AUI_TARGETD'];
	$AUI_DATE_NCR	= $default['AUI_DATE_NCR'];
	$AUI_DATE		= date('m/d/Y', strtotime($AUI_DATE));
	$AUI_TARGETD	= date('m/d/Y', strtotime($AUI_TARGETD));
	$AUI_DATE_NCR	= date('m/d/Y', strtotime($AUI_DATE_NCR));
	$AUI_AUDITOR	= $default['AUI_AUDITOR'];
	$AUI_REFDOC		= $default['AUI_REFDOC'];
	$AUI_PROBLDESC	= $default['AUI_PROBLDESC'];
	$AUI_KLAUS1		= $default['AUI_KLAUS1'];
	$AUI_KLAUS2		= $default['AUI_KLAUS2'];
	$AUI_KLAUS3		= $default['AUI_KLAUS3'];
	$AUI_KLAUS4		= $default['AUI_KLAUS4'];
	$AUI_TYPE		= $default['AUI_TYPE'];
	$AUI_SCOPE1		= $default['AUI_SCOPE1'];
	$AUI_SCOPE2		= $default['AUI_SCOPE2'];
	$AUI_SCOPE3		= $default['AUI_SCOPE3'];
	$AUI_SYSPROC1	= $default['AUI_SYSPROC1'];
	$AUI_SYSPROC2	= $default['AUI_SYSPROC2'];
	$AUI_SYSPROC3	= $default['AUI_SYSPROC3'];
	$AUI_SYSPROC4	= $default['AUI_SYSPROC4'];
	$AUI_CORACT		= $default['AUI_CORACT'];
	$AUI_CORSTEP	= $default['AUI_CORSTEP'];
	$AUI_PREVENT	= $default['AUI_PREVENT'];
	$AUI_FINISHP	= $default['AUI_FINISHP'];
	$AUI_EVIDEN		= $default['AUI_EVIDEN'];
	$AUI_REVIEWD	= $default['AUI_REVIEWD'];
	$AUI_FINISHP	= date('m/d/Y', strtotime($AUI_FINISHP));
	$AUI_REVIEWD	= date('m/d/Y', strtotime($AUI_REVIEWD));
	$AUI_CONCL		= $default['AUI_CONCL'];
	$AUI_NCRNO		= $default['AUI_NCRNO'];
	$AUI_NCRD		= $default['AUI_NCRD'];
	$AUI_NOTESREV	= $default['AUI_NOTESREV'];
	$AUI_NCRD		= date('m/d/Y', strtotime($AUI_NCRD));
	$AUI_STAT1		= $default['AUI_STAT1'];
	$AUI_STAT2		= $default['AUI_STAT2'];
	$AUI_STAT3		= $default['AUI_STAT3'];
	$AUI_STAT		= $default['AUI_STAT'];
	
	// CATATAN
	$AUN_NUM		= $default['AUN_NUM'];
	$AUN_CODE		= $default['AUN_CODE'];
	$AUN_DATE		= $default['AUN_DATE'];
	$AUN_DATE		= date('m/d/Y', strtotime($AUN_DATE));
	$AUN_DEPT		= $default['AUN_DEPT'];
	$AUN_AUDITEE	= $default['AUN_AUDITEE'];
	$AUN_ACUAN		= $default['AUN_ACUAN'];
	$AUN_AUDITOR	= $default['AUN_AUDITOR'];
	$AUN_STAT		= $default['AUN_STAT'];
	$AUN_DESC		= $default['AUN_DESC'];
	$TYPE			= $default['TYPE'];
}

$backURL1 			= site_url('c_project/c_4uD1NT/');
$backURL2 			= site_url('c_project/c_4uD1NT/U5r');

$inStt1	= "disabled";
$inStt2	= "disabled";
$inStt3	= "disabled";
if($AUI_STAT1 == 0)
{
	$frm1 	= 1;
	$frm2 	= 0;
	$frm3 	= 0;
	$inStt1	= "";
}
else if($AUI_STAT2 == 0)
{
	$frm1 	= 0;
	$frm2	= 1;
	$frm3 	= 0;
	$inStt2	= "";
}
else if($AUI_STAT3 == 0)
{
	$frm1 	= 0;
	$frm2 	= 0;
	$frm3	= 1;
	$inStt3	= "";
}
else
{
	$frm1 = 0;
	$frm2 = 0;
	$frm3 = 0;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $appName; ?></title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
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
	//$this->load->view('template/topbar');
	//$this->load->view('template/sidebar');
	
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
		if($TranslCode == 'Date')$Date = $LangTransl;
		if($TranslCode == 'Code')$Code = $LangTransl;
		if($TranslCode == 'MenuNameIND')$MenuNameIND = $LangTransl;
		if($TranslCode == 'MenuNameENG')$MenuNameENG = $LangTransl;
		if($TranslCode == 'MenuParent')$MenuParentTR = $LangTransl;
		if($TranslCode == 'MenuLevel')$MenuLevel = $LangTransl;
		if($TranslCode == 'OrderNo')$OrderNo = $LangTransl;
		if($TranslCode == 'LinkAlias')$LinkAlias = $LangTransl;
		if($TranslCode == 'NeedPattern')$NeedPattern = $LangTransl;
		if($TranslCode == 'NeedStepApprove')$NeedStepApprove = $LangTransl;
		if($TranslCode == 'UseHeader')$UseHeader = $LangTransl;
		if($TranslCode == 'Status')$Status = $LangTransl;
		if($TranslCode == 'Yes')$Yes = $LangTransl;
		if($TranslCode == 'No')$No = $LangTransl;

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
    <div class="nav-tabs-custom">
    	<ul class="nav nav-tabs">
            <li <?php if($TYPE == 1) { ?>class="active"<?php } ?>>
            	<a href="#ncr" data-toggle="tab">NCR</a>
            </li>
            <li <?php if($TYPE == 2) { ?>class="active"<?php } ?>>
            	<a href="#catatan" data-toggle="tab">Catatan</a>
            </li>
        </ul>
        <div class="tab-content">
        	<div class="active tab-pane" id="ncr" <?php if($TYPE == 2 && $task == 'edit') { ?> style="display:none" <?php } ?>>
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title">Form Audit <em>(Auditor)</em></h3>                
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body chart-responsive">
                        <form class="form-horizontal" name="frm1" method="post" action="<?php echo $form_action1; ?>" onSubmit="return checkFrm1()">
                            <div class="form-group">
                                <label for="inputName" class="col-sm-2 control-label">Audit ke-</label>
                                <div class="col-sm-10">
                                    <label>
                                        <input type="hidden" name="AUI_NUM" id="AUI_NUM" value="<?php echo $AUI_NUM; ?>" class="form-control" style="max-width:250px">
                                        <input type="text" name="AUI_CODE1" id="AUI_CODE1" value="135" class="form-control" style="max-width:50px" disabled >
                                        <input type="hidden" name="AUI_CODE" id="AUI_CODE" value="135" class="form-control" style="max-width:50px" >
                                    </label>
                                    <label>
                                        <input type="text" name="AUI_STEP" id="AUI_STEP" value="<?php echo $AUI_STEP; ?>" class="form-control" style="max-width:100px" <?php echo $inStt1; ?>>
                                    </label>
                                    <label>
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;No Urut
                                    </label>
                                    <label>
                                        <input type="text" name="AUI_ORDNO" id="AUI_ORDNO" value="<?php echo $AUI_ORDNO; ?>" class="form-control" style="max-width:100px" <?php echo $inStt1; ?>>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputName" class="col-sm-2 control-label"><?php echo $Date?></label>
                                <div class="col-sm-10">
                                    <div class="input-group date">
                                        <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
                                        <input type="text" name="AUI_DATE" class="form-control pull-left" id="datepicker1" value="<?php echo $AUI_DATE; ?>" style="width:100px" <?php echo $inStt1; ?>>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputName" class="col-sm-2 control-label"><?php echo $Date?> NCR Terbit</label>
                                <div class="col-sm-10">
                                    <div class="input-group date">
                                        <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
                                        <input type="text" name="AUI_DATE_NCR" class="form-control pull-left" id="datepicker2" value="<?php echo $AUI_DATE_NCR; ?>" style="width:100px" onChange="chgDate(this.value)">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputName" class="col-sm-2 control-label">Tgl.  Pelengkapan</label>
                                <div class="col-sm-10">
                                    <div class="input-group date">
                                        <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
                                        <input type="text" name="AUI_TARGETD" class="form-control pull-left" id="datepicker6" value="<?php echo $AUI_TARGETD; ?>" style="width:100px" <?php echo $inStt1; ?>>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputName" class="col-sm-2 control-label">&nbsp;</label>
                                <div class="col-sm-10">
                                    <div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                        <h4><i class="icon fa fa-ban"></i> Tgl. Persiapan</h4>
                                        adalah batas pelengkapan dokumen Butir 2 s.d. 6 dan disampaikan AUDITOR paling lambat 3 haris ejak NCR terbit.
                                    </div>
                                </div>
                            </div>
                            <?php
                                $sqlPRJ 	= "SELECT PRJCODE, PRJNAME FROM tbl_project ORDER BY PRJCODE";
                                $resPRJ 	= $this->db->query($sqlPRJ)->result();
                            ?>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Lokasi</label>
                                <div class="col-sm-10">
                                    <select name="PRJCODE" id="PRJCODE" class="form-control select2" <?php echo $inStt1; ?>>
                                        <option value="none">--- None ---</option>
                                        <?php
                                            foreach($resPRJ as $row) :
                                                $PRJCODE1 	= $row->PRJCODE;
                                                $PRJNAME 	= $row->PRJNAME;
                                                ?>
                                              <option value="<?php echo $PRJCODE1; ?>" <?php if($PRJCODE1 == $PRJCODE) { ?> selected <?php } ?>><?php echo "$PRJCODE1 - $PRJNAME"; ?></option>
                                              <?php
                                            endforeach;
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Lokasi Temuan</label>
                                <div class="col-sm-10">
                                    <input type="text" name="AUI_LOC" id="AUI_LOC" value="<?php echo $AUI_LOC; ?>" class="form-control" <?php echo $inStt1; ?>>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Bagian</label>
                                <div class="col-sm-10">
                                    <input type="text" name="AUI_DEPT" id="AUI_DEPT" value="<?php echo $AUI_DEPT; ?>" class="form-control" <?php echo $inStt1; ?>>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputEmail" class="col-sm-2 control-label">Auditee</label>
                                <div class="col-sm-10">
                                    <select name="AUI_SUBJEK[]" id="AUI_SUBJEK" class="form-control select2" multiple="multiple" <?php echo $inStt1; ?>>
                                        <?php
                                            if($AUI_STAT1 == 0)
                                            {
                                                $sqlEmp	= "SELECT Emp_ID, First_Name, Last_Name FROM tbl_employee WHERE Emp_Status = 1 ORDER BY Emp_ID ASC LIMIT 1000";
                                            }
                                            else
                                            {
                                                $sqlEmp	= "SELECT Emp_ID, First_Name, Last_Name FROM tbl_employee WHERE Emp_ID = '$AUI_SUBJEK' LIMIT 1";
                                            }
                                            $sqlEmp	= $this->db->query($sqlEmp)->result();
                                            foreach($sqlEmp as $row) :
                                                $Emp_ID		= $row->Emp_ID;
                                                $First_Name	= $row->First_Name;
                                                $Last_Name	= $row->Last_Name;
                                                $Email		= $row->Email;
                                                ?>
                                                    <option value="<?php echo "$Emp_ID"; ?>" <?php if($AUI_SUBJEK == $Emp_ID) { ?> selected <?php } ?>>
                                                        <?php echo "$Emp_ID - $First_Name $Last_Name"; ?>
                                                    </option>
                                                <?php
                                            endforeach;
                                            
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputEmail" class="col-sm-2 control-label">Auditor</label>
                                <div class="col-sm-10">
                                    <select name="AUI_AUDITOR[]" id="AUI_AUDITOR" class="form-control select2" multiple="multiple" <?php echo $inStt1; ?>>
                                        <?php
                                            $getData	= "SELECT A.Emp_ID, CONCAT(B.First_Name, ' ', B.Last_Name) AS CompName
                                                                FROM tbl_audit_emp A
                                                                INNER JOIN tbl_employee B ON B.Emp_ID = A.Emp_ID
                                                                WHERE A.Emp_Stat = '1'";
                                                $resGetData 	= $this->db->query($getData)->result();
                                                foreach($resGetData as $rowData) :
                                                    $Emp_ID1 	= $rowData->Emp_ID;
                                                    $CompName1 	= $rowData->CompName;
                                                    
                                                    $JIDExplode = explode(';', $AUI_AUDITOR);
                                                    $AUDITOR	= '';
                                                    $SELECTED	= 0;
                                                    foreach($JIDExplode as $i => $key)
                                                    {
                                                        $AUDITOR	= $key;
                                                        if($Emp_ID1 == $AUDITOR)
                                                        {
                                                            $SELECTED	= 1;
                                                        }
                                                    }
                                                    ?>
                                                        <option value="<?php echo "$Emp_ID1"; ?>" <?php if($SELECTED == 1) { ?> selected <?php } ?>>
                                                            <?php echo "$Emp_ID1 - $CompName1"; ?>
                                                        </option>
                                                    <?php
                                            endforeach;
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">CC </label>
                                <div class="col-sm-10">
                                    <select name="AUI_CC[]" id="AUI_CC" class="form-control select2" multiple="multiple" <?php echo $inStt1; ?>>
                                        <?php
                                            $getData	= "SELECT A.Emp_ID, CONCAT(B.First_Name, ' ', B.Last_Name) AS CompName
															FROM tbl_audit_emp A
															INNER JOIN tbl_employee B ON B.Emp_ID = A.Emp_ID
															WHERE A.Emp_Stat = '1'";
											$resData 	= $this->db->query($getData)->result();
											foreach($resData as $rowData) :
												$Emp_ID1 	= $rowData->Emp_ID;
												$CompName1 	= $rowData->CompName;
												
												$JIDExplode = explode('~', $AUI_CC);
												$AUI_CC1	= '';
												$SELECTED	= 0;
												foreach($JIDExplode as $i => $key)
												{
													$AUI_CC1	= $key;
													if($Emp_ID1 == $AUI_CC1)
													{
														$SELECTED	= 1;
													}
												}
												?>
													<option value="<?php echo "$Emp_ID1"; ?>" <?php if($SELECTED == 1) { ?> selected <?php } ?>>
														<?php echo "$Emp_ID1 - $CompName1"; ?>
													</option>
												<?php
                                            endforeach;
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Ref. Dok. </label>
                                <div class="col-sm-10">
                                    <select name="AUI_REFDOC[]" id="AUI_REFDOC" class="form-control select2" multiple="multiple" <?php echo $inStt1; ?>>
                                        <?php
                                            $getDataN	= "SELECT A.AUN_NUM, A.AUN_CODE, A.AUN_DESC FROM tbl_auditn_h A WHERE A.AUN_STAT = 1";
											$resDataN 	= $this->db->query($getDataN)->result();
											foreach($resDataN as $rowDataN) :
												$AUN_NUM1 	= $rowDataN->AUN_NUM;
												$AUN_CODE1 	= $rowDataN->AUN_CODE;
												$AUN_DESC1 	= $rowDataN->AUN_DESC;
												
												$JIDExplode = explode('~', $AUI_REFDOC);
												$REFDOC1	= '';
												$SELECTED	= 0;
												foreach($JIDExplode as $i => $key)
												{
													$REFDOC1	= $key;
													if($Emp_ID1 == $REFDOC1)
													{
														$SELECTED	= 1;
													}
												}
												?>
													<option value="<?php echo "$AUN_NUM1"; ?>" <?php if($SELECTED == 1) { ?> selected <?php } ?>>
														<?php echo "$AUN_CODE1 - $AUN_DESC1"; ?>
													</option>
												<?php
                                            endforeach;
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Ketidaksesuaian</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="AUI_PROBLDESC"  id="AUI_PROBLDESC" style="height:70px" <?php echo $inStt1; ?>><?php echo set_value('AUI_PROBLDESC', isset($default['AUI_PROBLDESC']) ? $default['AUI_PROBLDESC'] : ''); ?></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Klausul</label>
                                <div class="col-sm-10">
                                    <label>
                                        SMK3 PP No 50, 2012:
                                        <input type="text" name="AUI_KLAUS1" id="AUI_KLAUS1" value="<?php echo $AUI_KLAUS1; ?>" class="form-control" style="max-width:250px" <?php echo $inStt1; ?>>
                                    </label>
                                    <label>
                                        OHSAS 18001:2007:
                                        <input type="text" name="AUI_KLAUS2" id="AUI_KLAUS2" value="<?php echo $AUI_KLAUS2; ?>" class="form-control" style="max-width:250px" <?php echo $inStt1; ?>>
                                    </label>
                                    <label>
                                        ISO 14001:2015:
                                        <input type="text" name="AUI_KLAUS3" id="AUI_KLAUS3" value="<?php echo $AUI_KLAUS3; ?>" class="form-control" style="max-width:250px" <?php echo $inStt1; ?>>
                                    </label>
                                    <label>
                                        ISO 9001:2015:
                                        <input type="text" name="AUI_KLAUS4" id="AUI_KLAUS4" value="<?php echo $AUI_KLAUS4; ?>" class="form-control" style="max-width:250px" <?php echo $inStt1; ?>>
                                    </label>
                                </div>
                            </div> 
                            <div class="form-group">
                                 <label for="inputEmail" class="col-sm-2 control-label">Temuan</label>
                                <div class="col-sm-10">
                                    <input type="radio" name="AUI_TYPE" id="AUI_TYPE1" value="1" class="minimal" <?php if($AUI_TYPE == 1) { ?> checked <?php } ?>>
                                    &nbsp;&nbsp;Minor&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input type="radio" name="AUI_TYPE" id="AUI_TYPE2" value="2" class="minimal" <?php if($AUI_TYPE == 2) { ?> checked <?php } ?>>
                                    &nbsp;&nbsp;Mayor
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Scope</label>
                                <div class="col-sm-10">
                                    <label>
                                    <input type="checkbox" name="AUI_SCOPE1" id="AUI_SCOPE1" class="minimal" value="1" <?php if($AUI_SCOPE1==1) { ?> checked <?php } ?>>
                                      K3L
                                    </label>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <label>
                                    <input type="checkbox" name="AUI_SCOPE2" id="AUI_SCOPE2" class="minimal" value="1" <?php if($AUI_SCOPE2==1) { ?> checked <?php } ?>>
                                      Biaya
                                    </label>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <label>
                                    <input type="checkbox" name="AUI_SCOPE3" id="AUI_SCOPE3" class="minimal" value="1" <?php if($AUI_SCOPE3==1) { ?> checked <?php } ?>>
                                      Mutu dan Waktu
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Mng Sys Process</label>
                                <div class="col-sm-10">
                                    <label>
                                        <input type="checkbox" name="AUI_SYSPROC1" id="AUI_SYSPROC1" class="minimal" value="1" <?php if($AUI_SYSPROC1==1) { ?> checked <?php } ?>>P
                                    </label>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <label>
                                        <input type="checkbox" name="AUI_SYSPROC2" id="AUI_SYSPROC2" class="minimal" value="1" <?php if($AUI_SYSPROC2==1) { ?> checked <?php } ?>>D
                                    </label>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <label>
                                        <input type="checkbox" name="AUI_SYSPROC3" id="AUI_SYSPROC3" class="minimal" value="1" <?php if($AUI_SYSPROC3==1) { ?> checked <?php } ?>>C
                                    </label>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <label>
                                        <input type="checkbox" name="AUI_SYSPROC4" id="AUI_SYSPROC4" class="minimal" value="1" <?php if($AUI_SYSPROC4==1) { ?> checked <?php } ?>>A
                                    </label>
                                </div>
                            </div>
                            <?php if($frm1 == 1 && $DefEmp_ID != $AUI_SUBJEK) { ?>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Status</label>
                                    <div class="col-sm-10">
                                        <input type="hidden" name="SRC" id="SRC" value="1" class="form-control" style="max-width:100px">
                                        <input type="hidden" name="AUI_FINISHP" id="AUI_FINISHP" value="<?php echo $AUI_FINISHP; ?>" class="form-control" >
                                        <input type="hidden" name="AUI_REVIEWD" id="AUI_REVIEWD" value="<?php echo $AUI_REVIEWD; ?>" class="form-control" >
                                        <input type="hidden" name="AUI_NCRD" id="AUI_NCRD" value="<?php echo $AUI_NCRD; ?>" class="form-control" >
                                        <select name="AUI_STAT1" id="AUI_STAT1" class="form-control select2" style="max-width:100px">
                                            <option value="0"<?php if($AUI_STAT1 == 0) { ?> selected <?php } ?>>New</option>
                                            <option value="1"<?php if($AUI_STAT1 == 1) { ?> selected <?php } ?>>Confirm</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <?php
                                            if($task=='add')
                                            {
                                                ?>
                                                    <button class="btn btn-primary" >
                                                    <i class="cus-save-16x16"></i>&nbsp;&nbsp;<?php echo $Save; ?>
                                                    </button>&nbsp;
                                                <?php
                                            }
                                            else
                                            {
                                                ?>
                                                    <button class="btn btn-primary" <?php if($frm1 == 0) { ?> disabled <?php } ?>>
                                                    <i class="cus-save1-16x16"></i>&nbsp;&nbsp;<?php echo $Update; ?>
                                                    </button>&nbsp;
                                                <?php
                                            }
                                        
                                            echo anchor("$backURL1",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>');
                                        ?>
                                    </div>
                                </div>
                            <?php } $nowD	= date('Y-m-d');?>
                        </form>
                    </div>
                </div>
                <script>
                    function chgDate(dateSel)
                    {
                        var nw			= dateSel;
                        var months 		= ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];
                        var nowD		= new Date(nw);
                        var harIni		= nowD.getDate() + 3;
                        if(harIni < 10)
                            var harIni	= "0"+harIni;
                        var blnIni		= months[nowD.getMonth()];
                        var thnIni		= nowD.getFullYear();
                        var fulDate		= blnIni+"/"+harIni+"/"+thnIni;
                        var selDate		= new Date(fulDate);
                        document.getElementById('datepicker6').value	= ('0' + (selDate.getMonth() + 1)).slice(-2) + '/' + ('0' + selDate.getDate()).slice(-2) + '/' + selDate.getFullYear();
                    }
                    
                    function checkFrm1()
                    {			
                        AUI_STEP 		= document.getElementById('AUI_STEP').value;
                        AUI_ORDNO 		= document.getElementById('AUI_ORDNO').value;
                        PRJCODE 		= document.getElementById('PRJCODE').value;
                        AUI_DEPT 		= document.getElementById('AUI_DEPT').value;
                        AUI_SUBJEK 		= document.getElementById('AUI_SUBJEK').value;
                        AUI_AUDITOR		= document.getElementById('AUI_AUDITOR').value;
                        AUI_PROBLDESC 	= document.getElementById('AUI_REFDOC').value;
                        if(AUI_STEP == '')
                        {
                            alert('Tahapan audit tidak boleh kosong.');
                            document.getElementById("AUI_STEP").focus();
                            return false;
                        }
                        if(AUI_ORDNO == '')
                        {
                            alert('No. urut audit tidak boleh kosong.');
                            document.getElementById("AUI_ORDNO").focus();
                            return false;
                        }
                        if(PRJCODE == 'none')
                        {
                            alert('Tentukan nama proyek.');
                            document.getElementById("PRJCODE").focus();
                            return false;
                        }
                        if(AUI_DEPT == '')
                        {
                            alert('Masukan nama bagian yang diaudit.');
                            document.getElementById("AUI_DEPT").focus();
                            return false;
                        }
                        if(AUI_SUBJEK == '')
                        {
                            alert('Tentukan nama karyawan yang diaudit.');
                            document.getElementById("AUI_SUBJEK").focus();
                            return false;
                        }
                        if(AUI_AUDITOR == '')
                        {
                            alert('Tentukan nama auditor.');
                            document.getElementById("AUI_AUDITOR").focus();
                            return false;
                        }
                        if(AUI_PROBLDESC == '')
                        {
                            alert('Masukan daftar ketidaksesuaian.');
                            document.getElementById("AUI_PROBLDESC").focus();
                            return false;
                        }
                    }
                </script>
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">Form Isian Rencana Penyelesaian <em>(Auditee)</em></h3>                
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body chart-responsive">
                        <form class="form-horizontal" name="frm2" method="post" action="<?php echo $form_action2; ?>" onSubmit="return checkData()">
                            <div class="form-group" <?php if($AUI_NOTESREV == '') { ?> style="display:none" <?php } ?>>
                                <label class="col-sm-2 control-label">Catatan Perbaikan</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="AUI_NOTESREVX"  id="AUI_NOTESREVX" style="height:70px; border-color:#F00; border-width:medium" disabled><?php echo set_value('AUI_NOTESREV', isset($default['AUI_NOTESREV']) ? $default['AUI_NOTESREV'] : ''); ?></textarea>
                                </div>
                            </div> 
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Penyebab</label>
                                <div class="col-sm-10">
                                    <input type="hidden" name="AUI_NUM" id="AUI_NUM" value="<?php echo $AUI_NUM; ?>" class="form-control" style="max-width:250px">
                                    <textarea class="form-control" name="AUI_CAUSE"  id="AUI_CAUSE" style="height:70px" <?php echo $inStt2; ?>><?php echo set_value('AUI_CAUSE', isset($default['AUI_CAUSE']) ? $default['AUI_CAUSE'] : ''); ?></textarea>
                                </div>
                            </div>   
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Perbaikan</label>
                                <div class="col-sm-10">
                                    <strong>Tindakan Perbaikan</strong>
                                    <textarea class="form-control" name="AUI_CORACT"  id="AUI_CORACT" style="height:70px" <?php echo $inStt2; ?>><?php echo set_value('AUI_CORACT', isset($default['AUI_CORACT']) ? $default['AUI_CORACT'] : ''); ?></textarea><br>
                                    <strong>Proses / Langkah Perbaikan</strong>
                                    <textarea class="form-control" name="AUI_CORSTEP"  id="AUI_CORSTEP" style="height:70px" <?php echo $inStt2; ?>><?php echo set_value('AUI_CORSTEP', isset($default['AUI_CORSTEP']) ? $default['AUI_CORSTEP'] : ''); ?></textarea>
                                </div>
                            </div>            
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Pencegahan</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="AUI_PREVENT"  id="AUI_PREVENT" style="height:70px" <?php echo $inStt2; ?>><?php echo set_value('AUI_PREVENT', isset($default['AUI_PREVENT']) ? $default['AUI_PREVENT'] : ''); ?></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputName" class="col-sm-2 control-label">Tgl. Rencana Selesai</label>
                                <div class="col-sm-10">
                                    <div class="input-group date">
                                        <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
                                        <input type="text" name="AUI_FINISHP" class="form-control pull-left" id="datepicker3" value="<?php echo $AUI_FINISHP; ?>" style="width:100px" <?php echo $inStt2; ?>>
                                    </div>
                                </div>
                            </div>
                            <?php if($frm2 == 1 && $DefEmp_ID == $AUI_SUBJEK): ?>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Eviden/Bukti Closing</label>
                                <div class="col-sm-10">
                                    <button type="button" id="btnUpload" class="btn btn-success"><i class="fa fa-cloud-upload"></i>&nbsp;Upload Eviden</button>
                                </div>
                            </div>
                        	<?php endif;?>
                            <?php if($frm2 == 1 && $DefEmp_ID == $AUI_SUBJEK) { ?>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Status</label>
                                    <div class="col-sm-10">
                                        <input type="hidden" name="SRC" id="SRC" value="2" class="form-control" style="max-width:100px">
                                        <input type="hidden" name="AUI_REVIEWD" id="AUI_REVIEWD" value="<?php echo $AUI_REVIEWD; ?>" class="form-control" >
                                        <input type="hidden" name="AUI_NCRD" id="AUI_NCRD" value="<?php echo $AUI_NCRD; ?>" class="form-control" >
                                        <select name="AUI_STAT2" id="AUI_STAT2" class="form-control select2" style="max-width:100px">
                                            <option value="0"<?php if($AUI_STAT2 == 0) { ?> selected <?php } ?>>--</option>
                                            <option value="1"<?php if($AUI_STAT2 == 1) { ?> selected <?php } ?>>Confirm</option>
                                        </select>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <?php
                                        if($frm2 == 1 && $DefEmp_ID == $AUI_SUBJEK)
                                        {
                                            if($task=='add')
                                            {
                                                ?>
                                                    <button class="btn btn-primary" >
                                                    <i class="cus-save-16x16"></i>&nbsp;&nbsp;<?php echo $Save; ?>
                                                    </button>&nbsp;
                                                <?php
                                            }
                                            else
                                            {
                                                ?>
                                                    <button class="btn btn-primary" <?php if($frm2 == 0) { ?> disabled <?php } ?>>
                                                    <i class="cus-save1-16x16"></i>&nbsp;&nbsp;<?php echo $Update; ?>
                                                    </button>&nbsp;
                                                <?php
                                            }
                                        }
                                    
                                        echo anchor("$backURL2",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>');
                                    ?>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Form Isian Peninjauan Ulang <em>(Auditor)</em></h3>                
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body chart-responsive">
                        <form class="form-horizontal" name="frm3" method="post" action="<?php echo $form_action3; ?>" onSubmit="return checkData()">
                            <input type="hidden" name="AUI_NUM" id="AUI_NUM" value="<?php echo $AUI_NUM; ?>" class="form-control" style="max-width:250px">
                            <div class="form-group">
                                <label for="inputName" class="col-sm-2 control-label">Tgl. Peninjauan</label>
                                <div class="col-sm-10">
                                    <div class="input-group date">
                                        <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
                                        <input type="text" name="AUI_REVIEWD" class="form-control pull-left" id="datepicker4" value="<?php echo $AUI_REVIEWD; ?>" style="width:100px" <?php echo $inStt3; ?>>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputName" class="col-sm-2 control-label">Hasil Pemeriksaan</label>
                                <div class="col-sm-10">
                                      <input type="radio" name="AUI_CONCL" class="flat-red" id="AUI_CONCL1" value="1" <?php if($AUI_CONCL == 1) { ?> checked <?php } ?> <?php echo $inStt3; ?>> Tindakan Perbaikan dan pencegahan telah dilakukan dan efeltif<br>
                                      <input type="radio" name="AUI_CONCL" class="flat-red" id="AUI_CONCL2" value="2" <?php if($AUI_CONCL == 2) { ?> checked <?php } ?> onFocus="chkCONCL()" <?php echo $inStt3; ?>> Tindakan perbaikan dan pencegahan belum dilakukan atau tidak efektif, perlu dibuatkan NCR baru No <label><input type="text" name="AUI_NCRNO" id="AUI_NCRNO" value="<?php echo $AUI_NCRNO; ?>" class="form-control" style="max-width:150px" onFocus="chkNCRNO()" <?php echo $inStt3; ?>></label>&nbsp;Tgl. &nbsp;<label><input type="text" name="AUI_NCRD" id="datepicker5" value="<?php echo $AUI_NCRD; ?>" class="form-control" style="max-width:100px" <?php echo $inStt3; ?>></label>
                                </div>
                            </div>           
                            <div class="form-group" id="ReviewNotes" <?php if($AUI_NOTESREV == '') { ?> style="display:none" <?php } ?>>
                                <label class="col-sm-2 control-label">Pencegahan</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="AUI_NOTESREV"  id="AUI_NOTESREV" style="height:70px" <?php echo $inStt3; ?>><?php echo set_value('AUI_NOTESREV', isset($default['AUI_NOTESREV']) ? $default['AUI_NOTESREV'] : ''); ?></textarea>
                                </div>
                            </div>
                            <?php if($frm3 == 1 && $DefEmp_ID != $AUI_SUBJEK) { ?>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Status</label>
                                    <div class="col-sm-10">
                                        <input type="hidden" name="SRC" id="SRC" value="3" class="form-control" style="max-width:100px">
                                        <input type="hidden" name="AUI_STAT" id="AUI_STAT" value="<?php echo $AUI_STAT; ?>" class="form-control" style="max-width:100px">
                                        <input type="hidden" name="AUI_STAT2" id="AUI_STAT2" value="<?php echo $AUI_STAT2; ?>" class="form-control" style="max-width:100px">
                                        <select name="AUI_STAT3" id="AUI_STAT3" class="form-control select2" style="max-width:100px">
                                            <option value="0"<?php if($AUI_STAT3 == 0) { ?> selected <?php } ?>>--</option>
                                            <option value="1"<?php if($AUI_STAT3 == 1) { ?> selected <?php } ?>>Confirm</option>
                                            <option value="2"<?php if($AUI_STAT3 == 2) { ?> selected <?php } ?>>Approve</option>
                                        </select>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <?php
                                        if($frm3 == 1 && $DefEmp_ID != $AUI_SUBJEK)
                                        {
                                            if($task=='add')
                                            {
                                                ?>
                                                    <button class="btn btn-primary" >
                                                    <i class="cus-save-16x16"></i>&nbsp;&nbsp;<?php echo $Save; ?>
                                                    </button>&nbsp;
                                                <?php
                                            }
                                            else
                                            {
                                                ?>
                                                    <button class="btn btn-primary" <?php if($frm3 == 0) { ?> disabled <?php } ?>>
                                                    <i class="cus-save1-16x16"></i>&nbsp;&nbsp;<?php echo $Update; ?>
                                                    </button>&nbsp;
                                                <?php
                                            }
                                        }
                                        echo anchor("$backURL1",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>');
                                    ?>
                                </div>
                            </div>
                        </form>
                    </div>
                    <script>
                        $(document).ready(function () {
                            $('#AUI_CONCL1').on('ifChecked', function(event){
                                $('#ReviewNotes').hide();
                                $('#AUI_NCRNO').val('');
                                $('#AUI_NOTESREV').val('');
                                $("#AUI_NCRNO").prop('disabled', true);
                                $("#datepicker5").prop('disabled', true);
                            });
                            $('#AUI_CONCL2').on('ifChecked', function(event){
                                $('#ReviewNotes').show();
                                $("#AUI_NCRNO").prop('disabled', false);
                                $("#datepicker5").prop('disabled', false);
                            });
                        });
                        
                        function chkNCRNO()
                        {
                            $('#AUI_CONCL2').iCheck('check');
                        }
                    </script>
                </div>
            </div>
        	<div class="active tab-pane" id="catatan" <?php if($TYPE == 1 && $task == 'edit') { ?> style="display:none" <?php } ?>>
        	<div class="tab-pane" id="catatan">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">Form Catatan Audit Internal</h3>                
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body chart-responsive">
                        <form class="form-horizontal" name="frm" method="post" action="<?php echo $form_action4; ?>" onSubmit="return checkFrm2()">
                            <input type="Hidden" name="rowCount" id="rowCount" value="0">
                            <div class="form-group">
                                <label for="inputName" class="col-sm-2 control-label">Kode</label>
                                <div class="col-sm-10">
                                    <input type="hidden" name="AUN_NUM" id="AUN_NUM" value="<?php echo $AUN_NUM; ?>" class="form-control">
                                    <input type="text" name="AUN_CODE" id="AUN_CODE" value="<?php echo $AUN_CODE; ?>" class="form-control" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputName" class="col-sm-2 control-label"><?php echo $Date?></label>
                                <div class="col-sm-10">
                                    <div class="input-group date">
                                        <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
                                        <input type="text" name="AUN_DATE" class="form-control pull-left" id="datepicker7" value="<?php echo $AUN_DATE; ?>" style="width:100px">
                                    </div>
                                </div>
                            </div>
                            <?php
                                $sqlPRJ 	= "SELECT PRJCODE, PRJNAME FROM tbl_project ORDER BY PRJCODE";
                                $resPRJ 	= $this->db->query($sqlPRJ)->result();
                            ?>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Lokasi</label>
                                <div class="col-sm-10">
                                    <select name="PRJCODEN" id="PRJCODEN" class="form-control select2">
                                        <option value="none">--- None ---</option>
                                        <?php
                                            foreach($resPRJ as $row) :
                                                $PRJCODE1 	= $row->PRJCODE;
                                                $PRJNAME 	= $row->PRJNAME;
                                                ?>
                                              <option value="<?php echo $PRJCODE1; ?>" <?php if($PRJCODE1 == $PRJCODE) { ?> selected <?php } ?>><?php echo "$PRJCODE1 - $PRJNAME"; ?></option>
                                              <?php
                                            endforeach;
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputName" class="col-sm-2 control-label">Acuan</label>
                                <div class="col-sm-10">
                                    <div class="input-group date">
                                        <input type="text" name="AUN_ACUAN" id="AUN_ACUAN" value="<?php echo $AUN_ACUAN; ?>" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Bagian</label>
                                <div class="col-sm-10">
                                    <input type="text" name="AUN_DEPT" id="AUN_DEPT" value="<?php echo $AUN_DEPT; ?>" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputEmail" class="col-sm-2 control-label">Auditee</label>
                                <div class="col-sm-10">
                                    <select name="AUN_AUDITEE[]" id="AUN_AUDITEE" class="form-control select2" multiple="multiple">
                                        <?php
                                            if($AUI_STAT1 == 0)
                                            {
                                                $sqlEmp	= "SELECT Emp_ID, First_Name, Last_Name FROM tbl_employee WHERE Emp_Status = 1 ORDER BY First_Name LIMIT 10";
                                            }
                                            else
                                            {
                                                $sqlEmp	= "SELECT Emp_ID, First_Name, Last_Name FROM tbl_employee WHERE Emp_ID = '$AUI_SUBJEK' LIMIT 1";
                                            }
                                            $sqlEmp	= $this->db->query($sqlEmp)->result();
                                            foreach($sqlEmp as $row) :
                                                $Emp_ID		= $row->Emp_ID;
                                                $First_Name	= $row->First_Name;
                                                $Last_Name	= $row->Last_Name;
                                                $Email		= $row->Email;
                                                ?>
                                                    <option value="<?php echo "$Emp_ID"; ?>" <?php if($AUN_AUDITEE == $Emp_ID) { ?> selected <?php } ?>>
                                                        <?php echo "$Emp_ID - $First_Name $Last_Name"; ?>
                                                    </option>
                                                <?php
                                            endforeach;
                                            
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputEmail" class="col-sm-2 control-label">Auditor</label>
                                <div class="col-sm-10">
                                    <select name="AUN_AUDITOR[]" id="AUN_AUDITOR" class="form-control select2" multiple="multiple">
                                        <?php
                                            $getData	= "SELECT A.Emp_ID, CONCAT(B.First_Name, ' ', B.Last_Name) AS CompName
                                                            FROM tbl_audit_emp A
                                                            INNER JOIN tbl_employee B ON B.Emp_ID = A.Emp_ID
                                                            WHERE A.Emp_Stat = '1'";
                                            $resGetData 	= $this->db->query($getData)->result();
                                            foreach($resGetData as $rowData) :
                                                $Emp_ID1 	= $rowData->Emp_ID;
                                                $CompName1 	= $rowData->CompName;
                                                
                                                $JIDExplode = explode(';', $AUN_AUDITOR);
                                                $AUDITOR	= '';
                                                $SELECTED	= 0;
                                                foreach($JIDExplode as $i => $key)
                                                {
                                                    $AUDITOR	= $key;
                                                    if($Emp_ID1 == $AUDITOR)
                                                    {
                                                        $SELECTED	= 1;
                                                    }
                                                }
                                                ?>
                                                    <option value="<?php echo "$Emp_ID1"; ?>" <?php if($SELECTED == 1) { ?> selected <?php } ?>>
                                                        <?php echo "$Emp_ID1 - $CompName1"; ?>
                                                    </option>
                                                <?php
                                            endforeach;
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputName" class="col-sm-2 control-label">Catatan</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="AUN_DESC"  id="AUN_DESC" style="height:70px"><?php echo set_value('AUN_DESC', isset($default['AUN_DESC']) ? $default['AUN_DESC'] : ''); ?></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Status</label>
                                <div class="col-sm-10">
                                    <select name="AUN_STAT" id="AUN_STAT" class="form-control select2" style="max-width:100px">
                                        <option value="0"<?php if($AUN_STAT == 0) { ?> selected <?php } ?>>New</option>
                                        <option value="1"<?php if($AUN_STAT == 1) { ?> selected <?php } ?>>Confirm</option>
                                    </select>
                                </div>
                            </div>
                            <?php
                               if($AUN_STAT == 0)
                                {
                                    ?>
                                    <div class="form-group">
                                        <label for="inputName" class="col-sm-2 control-label">&nbsp;</label>
                                        <div class="col-sm-10">
                                            <button class="btn btn-success" type="button" onClick="add_listAcc();">
                                            [ <i class="glyphicon glyphicon-plus"></i> ] Detil
                                            </button>
                                        </div>
                                    </div>
                                    <?php
                                }
                            ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="box box-primary">
                                    <br>
                                    <table width="100%" border="1" id="tbl">
                                        <tr style="background:#CCCCCC">
                                          <th width="3%" height="25" rowspan="2" style="text-align:left">&nbsp;</th>
                                          <th width="67%" rowspan="2" style="text-align:center">Catatan</th>
                                          <th colspan="3" style="text-align:center; background-color:#00BEEE">Focus </th>
                                          <th colspan="4" style="text-align:center; background-color:#00A65B">Process Sys.</th>
                                          <th colspan="3" style="text-align:center; background-color:#F39F12">Temuan</th>
                                        </tr>
                                        <tr style="background:#CCCCCC">
                                          <th width="3%" style="text-align:center; background-color:#00BEEE">K3L</th>
                                          <th width="3%" style="text-align:center; background-color:#00BEEE">MW</th>
                                          <th width="3%" style="text-align:center; background-color:#00BEEE">B</th>
                                          <th width="2%" style="text-align:center; background-color:#00A65B">P</th>
                                          <th width="2%" style="text-align:center; background-color:#00A65B">D</th>
                                          <th width="2%" style="text-align:center; background-color:#00A65B">C</th>
                                          <th width="2%" style="text-align:center; background-color:#00A65B">A</th>
                                          <th width="2%" style="text-align:center; background-color:#F39F12">Cat</th>
                                          <th width="4%" style="text-align:center; background-color:#F39F12">Min</th>
                                          <th width="4%" style="text-align:center; background-color:#F39F12">Mj</th>
                                        </tr>
                                        <?php
                                        $currentRow	= 0;
                                        if($task == 'edit')
                                        {
                                            $sqlDET	= "SELECT * FROM tbl_auditn_d WHERE AUN_NUM = '$AUN_NUM'";
                                            $result = $this->db->query($sqlDET)->result();
                                            $i		= 0;
                                            $j		= 0;                                          
                                            foreach($result as $row) :
                                                $currentRow = ++$i;
                                                $AUN_NUM 	= $row->AUN_NUM;
                                                $AUN_CODE 	= $row->AUN_CODE;
                                                $AUN_NOTES 	= $row->AUN_NOTES;
                                                $FOC_1 		= $row->FOC_1;
                                                $FOC_2 		= $row->FOC_2;
                                                $FOC_3 		= $row->FOC_3;
                                                $PSYS_1 	= $row->PSYS_1;
                                                $PSYS_2 	= $row->PSYS_2;
                                                $PSYS_3 	= $row->PSYS_3;
                                                $PSYS_4 	= $row->PSYS_4;
                                                $TEM_1 		= $row->TEM_1;
                                                $TEM_2 		= $row->TEM_2;
                                                $TEM_3 		= $row->TEM_3;
                                                
                                                if ($j==1) {
                                                    echo "<tr class=zebra1>";
                                                    $j++;
                                                } else {
                                                    echo "<tr class=zebra2>";
                                                    $j--;
                                                }
                                                ?> 
                                                <tr id="tr_<?php echo $currentRow; ?>">
                                                    <td width="3%" height="25" style="text-align:center;" nowrap>
                                                        <?php
                                                            if($AUN_STAT == 0)
                                                            {
                                                                ?>
                                                                <a href="#" onClick="deleteRow(<?php echo $currentRow; ?>)" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a>
                                                                <?php
                                                            }
                                                            else
                                                            {
                                                                echo "$currentRow.";
                                                            }
                                                        ?>
                                                    </td>
                                                    <td width="67%" style="text-align:left">
                                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][AUN_NUM]" id="data<?php echo $currentRow; ?>AUN_NUM" value="<?php echo $AUN_NUM; ?>" class="form-control" style="max-width:150px;" >
                                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][AUN_CODE]" id="data<?php echo $currentRow; ?>AUN_CODE" value="<?php echo $AUN_CODE; ?>" class="form-control" >
                                                        <input type="text" name="data[<?php echo $currentRow; ?>][AUN_NOTES]" id="data<?php echo $currentRow; ?>AUN_NOTES" value="<?php echo $AUN_NOTES; ?>" class="form-control" >
                                                    </td>
                                                    <td style="text-align:center">
                                                        <input type="checkbox" name="data[<?php echo $currentRow; ?>][FOC_1]" id="data<?php echo $currentRow; ?>FOC_1" class="minimal" value="1" <?php if($FOC_1==1) { ?> checked <?php } ?>></td>
                                                    <td style="text-align:center">
                                                        <input type="checkbox" name="data[<?php echo $currentRow; ?>][FOC_2]" id="data<?php echo $currentRow; ?>FOC_2" class="minimal" value="1" <?php if($FOC_2==1) { ?> checked <?php } ?>></td>
                                                    <td style="text-align:center">
                                                        <input type="checkbox" name="data[<?php echo $currentRow; ?>][FOC_3]" id="data<?php echo $currentRow; ?>FOC_3" class="minimal" value="1" <?php if($FOC_3==1) { ?> checked <?php } ?>></td>
                                                    <td style="text-align:center">
                                                        <input type="checkbox" name="data[<?php echo $currentRow; ?>][PSYS_1]" id="data<?php echo $currentRow; ?>PSYS_1" class="minimal" value="1" <?php if($PSYS_1==1) { ?> checked <?php } ?>></td>
                                                    <td style="text-align:center">
                                                        <input type="checkbox" name="data[<?php echo $currentRow; ?>][PSYS_2]" id="data<?php echo $currentRow; ?>PSYS_2" class="minimal" value="1" <?php if($PSYS_2==1) { ?> checked <?php } ?>></td>
                                                    <td style="text-align:center">
                                                        <input type="checkbox" name="data[<?php echo $currentRow; ?>][PSYS_3]" id="data<?php echo $currentRow; ?>PSYS_3" class="minimal" value="1" <?php if($PSYS_3==1) { ?> checked <?php } ?>></td>
                                                    <td style="text-align:center">
                                                        <input type="checkbox" name="data[<?php echo $currentRow; ?>][PSYS_4]" id="data<?php echo $currentRow; ?>PSYS_4" class="minimal" value="1" <?php if($PSYS_4==1) { ?> checked <?php } ?>></td>
                                                    <td style="text-align:center">
                                                        <input type="checkbox" name="data[<?php echo $currentRow; ?>][TEM_1]" id="data<?php echo $currentRow; ?>TEM_1" class="minimal" value="1" <?php if($TEM_1==1) { ?> checked <?php } ?>></td>
                                                    <td style="text-align:center">
                                                        <input type="checkbox" name="data[<?php echo $currentRow; ?>][TEM_2]" id="data<?php echo $currentRow; ?>TEM_2" class="minimal" value="1" <?php if($TEM_2==1) { ?> checked <?php } ?>></td>
                                                    <td style="text-align:center">
                                                        <input type="checkbox" name="data[<?php echo $currentRow; ?>][TEM_3]" id="data<?php echo $currentRow; ?>TEM_3" class="minimal" value="1" <?php if($TEM_3==1) { ?> checked <?php } ?>></td>
                                                </tr>
                                                <?php
                                            endforeach;
                                        }
                                        ?>
                                        <input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">
                                    </table>
                                  </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <?php
                                        if($task=='add')
                                        {
                                            ?>
                                                <button class="btn btn-primary" >
                                                <i class="cus-save-16x16"></i>&nbsp;&nbsp;<?php echo $Save; ?>
                                                </button>&nbsp;
                                            <?php
                                        }
                                        else
                                        {
                                            ?>
                                                <button class="btn btn-primary" <?php if($AUN_STAT == 1) { ?> disabled <?php } ?>>
                                                <i class="cus-save1-16x16"></i>&nbsp;&nbsp;<?php echo $Update; ?>
                                                </button>&nbsp;
                                            <?php
                                        }
                                    
                                        echo anchor("$backURL1",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>');
                                    ?>
                                </div>
                            </div>
                        </form>
                    </div>
                    <script>
                        function checkFrm2()
                        {			
                            AUN_CODE 		= document.getElementById('AUN_CODE').value;
                            PRJCODE 		= document.getElementById('PRJCODEN').value;
                            AUN_ACUAN 		= document.getElementById('AUN_ACUAN').value;
                            AUN_DEPT 		= document.getElementById('AUN_DEPT').value;
                            AUN_AUDITEE		= document.getElementById('AUN_AUDITEE').value;
                            AUN_AUDITOR		= document.getElementById('AUN_AUDITOR').value;
                            if(AUN_CODE == '')
                            {
                                alert('Kode catatan tidak boleh kosong.');
                                document.getElementById("AUN_CODE").focus();
                                return false;
                            }
                            if(PRJCODE == 'none')
                            {
                                alert('Tentukan nama proyek.');
                                document.getElementById("PRJCODEN").focus();
                                return false;
                            }
                            if(AUN_ACUAN == '')
                            {
                                alert('Kode acuan audit tidak boleh kosong.');
                                document.getElementById("AUN_ACUAN").focus();
                                return false;
                            }
                            if(AUN_DEPT == '')
                            {
                                alert('Masukan nama bagian yang diaudit.');
                                document.getElementById("AUN_DEPT").focus();
                                return false;
                            }
                            if(AUN_AUDITEE == '')
                            {
                                alert('Tentukan nama karyawan yang diaudit.');
                                document.getElementById("AUN_AUDITEE").focus();
                                return false;
                            }
                            if(AUN_AUDITOR == '')
                            {
                                alert('Tentukan nama auditor.');
                                document.getElementById("AUN_AUDITOR").focus();
                                return false;
                            }
                        }
                    </script>
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
	
    //Date picker
    $('#datepicker5').datepicker({
      autoclose: true
    });
	
    //Date picker
    $('#datepicker6').datepicker({
      autoclose: true
    });
	
    //Date picker
    $('#datepicker7').datepicker({
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

    $('#btnUpload').on('click', function(){
    	var url	= "<?php echo base_url().'index.php/c_project/c_4uD1NT/uploadEVD/?id='.$this->url_encryption_helper->encode_url($AUI_NUM);?>";    	
		w = 500;
		h = 400;
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		window.open(url, 'formpopup', 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
		form.target = 'formpopup';
    });

  });
  
	function add_listAcc() 
	{		
		var objTable, objTR, objTD, intIndex;
		
		AUN_NUM		= '<?php echo $AUN_NUM; ?>';
		AUN_CODE	= '<?php echo $AUN_CODE; ?>';
		AUN_NOTES 	= '';
		
		objTable 		= document.getElementById('tbl');
		intTable 		= objTable.rows.length;
		//alert('intTable = '+intTable)
		//intIndex = parseInt(document.frm.rowCount.value) + 1;
		intIndex = parseInt(objTable.rows.length);
		//intIndex = intTable;
		document.frm.rowCount.value = intIndex;
		
		objTR = objTable.insertRow(intTable);
		objTR.id = 'tr_' + intIndex;
		
		// Delete Icon
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = "center";
		objTD.noWrap = true;
		objTD.innerHTML = '<a href="#" onClick="deleteRow('+intIndex+')" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a><input type="hidden" id="chk" name="chk" value="'+intIndex+'" width="10" size="15" readonly class="form-control" style="max-width:300px;">';
		
		// Notes
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = '<input type="hidden" name="data['+intIndex+'][AUN_NUM]" id="data'+intIndex+'AUN_NUM" value="'+AUN_NUM+'" class="form-control" style="max-width:150px;" ><input type="hidden" name="data['+intIndex+'][AUN_CODE]" id="data'+intIndex+'AUN_CODE" value="'+AUN_CODE+'" class="form-control" style="max-width:150px;"><input type="text" name="data['+intIndex+'][AUN_NOTES]" id="data'+intIndex+'AUN_NOTES" value="" class="form-control" >';
		
		// CHK
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.noWrap = true;
		objTD.innerHTML = '<input type="checkbox" name="data['+intIndex+'][FOC_1]" id="data'+intIndex+'FOC_1" class="minimal" value="1">';
		
		// CHK
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.noWrap = true;
		objTD.innerHTML = '<input type="checkbox" name="data['+intIndex+'][FOC_2]" id="data'+intIndex+'FOC_2" class="minimal" value="1">';
		
		// CHK
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.noWrap = true;
		objTD.innerHTML = '<input type="checkbox" name="data['+intIndex+'][FOC_3]" id="data'+intIndex+'FOC_3" class="minimal" value="1">';
		
		// CHK
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.noWrap = true;
		objTD.innerHTML = '<input type="checkbox" name="data['+intIndex+'][PSYS_1]" id="data'+intIndex+'PSYS_1" class="minimal" value="1">';
		
		// CHK
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.noWrap = true;
		objTD.innerHTML = '<input type="checkbox" name="data['+intIndex+'][PSYS_2]" id="data'+intIndex+'PSYS_2" class="minimal" value="1">';
		
		// CHK
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.noWrap = true;
		objTD.innerHTML = '<input type="checkbox" name="data['+intIndex+'][PSYS_3]" id="data'+intIndex+'PSYS_3" class="minimal" value="1">';
		
		// CHK
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.noWrap = true;
		objTD.innerHTML = '<input type="checkbox" name="data['+intIndex+'][PSYS_4]" id="data'+intIndex+'PSYS_4" class="minimal" value="1">';
		
		// CHK
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.noWrap = true;
		objTD.innerHTML = '<input type="checkbox" name="data['+intIndex+'][TEM_1]" id="data'+intIndex+'TEM_1" class="minimal" value="1">';
		
		// CHK
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.noWrap = true;
		objTD.innerHTML = '<input type="checkbox" name="data['+intIndex+'][TEM_2]" id="data'+intIndex+'TEM_2" class="minimal" value="1">';
		
		// CHK
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.noWrap = true;
		objTD.innerHTML = '<input type="checkbox" name="data['+intIndex+'][TEM_3]" id="data'+intIndex+'TEM_3" class="minimal" value="1">';
		
		document.getElementById('totalrow').value = intIndex;
	}
	
	function deleteRow(btn)
	{
		var row = document.getElementById("tr_" + btn);
		row.remove();
	}
</script>

<?php 
$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>