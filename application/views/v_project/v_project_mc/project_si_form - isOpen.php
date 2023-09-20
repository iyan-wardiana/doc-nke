<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 27 Februari 2017
 * File Name	= project_si_form.php
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

$FlagUSER 		= $this->session->userdata['FlagUSER'];
$DefEmp_ID 		= $this->session->userdata['Emp_ID'];

if($FlagUSER == 'APPSI' || $FlagUSER == 'APPUSR')
{
	$isOpen	= 1;
}
else
{
	$isOpen	= 0;
}

$currentRow = 0;
if($task == 'add')
{
	$SI_DateY	= date('Y');
	$SI_DateM 	= date('m');
	$SI_DateD 	= date('d');
	$SI_DATE	= "$SI_DateY-$SI_DateM-$SI_DateD";
	$SI_DATEx	= mktime(0,0,0,$SI_DateM,$SI_DateD,$SI_DateY);
	$SI_TTOTerm	= 30;
	$SI_ENDDATE = date("Y-m-d",strtotime("+$SI_TTOTerm days",$SI_DATEx));
}
else
{
	$SI_DATE 		=  $default['SI_DATE'];
	$SI_ENDDATE 	=  $default['SI_DATE'];
}

$FlagAppCheck 		= $this->session->userdata['FlagAppCheck'];

$proj_amountIDR	= 0;	
$sqlPRJ 		= "SELECT PRJCOST FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$resultPRJ 		= $this->db->query($sqlPRJ)->result();
foreach($resultPRJ  as $rowPRJ) :
	$proj_amountIDR = $rowPRJ->PRJCOST;
endforeach;

$currentRow = 0;
if($task == 'add')
{
	$DefEmp_ID		= $this->session->userdata['Emp_ID'];
	
	$dataSessSrc = array(
			'selSearchproj_Code' => $PRJCODE,
			'selSearchType' => $this->input->post('selSearchType'),
			'selSearchType' => $this->input->post('selSearchType'),
			'txtSearch' => $this->input->post('txtSearch'));
	$this->session->set_userdata('dtSessSrc1', $dataSessSrc);
	$this->session->set_userdata('dtSessSrc2', $dataSessSrc);
	
	/*foreach($viewDocPattern as $row) :
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
	}*/
	$Pattern_Code	= "SI";
	$Pattern_Length	= 4;
	$useYear 		= 1;
	$useMonth 		= 1;
	$useDate 		= 1;
		
	$Pattern_YearAktive = date('Y');
	$Pattern_MonthAktive = date('m');
	$Pattern_DateAktive = date('d');
		
	$yearC = (int)$Pattern_YearAktive;
	$year = substr($Pattern_YearAktive,2,2);
	$month = (int)$Pattern_MonthAktive;
	$date = (int)$Pattern_DateAktive;

	$this->db->where('PATT_YEAR', $year);
	$myCount = $this->db->count_all('tbl_siheader');
	
	$sql = "SELECT MAX(PATT_NUMBER) as maxNumber FROM tbl_siheader WHERE PRJCODE = '$PRJCODE'";
	$result = $this->db->query($sql)->result();
	if($myCount>0)
	{
		foreach($result as $row) :
			$myMax = $row->maxNumber;
			$myMax = $myMax+1;
		endforeach;
	}	else	{		$myMax = 1;	}
	
	$thisMonth = $month;
	
	$lenMonth = strlen($thisMonth);
	if($lenMonth==1) $nolMonth="0";elseif($lenMonth==2) $nolMonth="";
	$pattMonth = $nolMonth.$thisMonth;
	
	$thisDate = $date;
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
	
		
	$lastPatternNumb 	= $myMax;
	$lastPatternNumb1 	= $myMax;
	$MAXSTEP			= $myMax;
	
	$PATT_NUMBER		= $lastPatternNumb1;
	
	$len 	= strlen($lastPatternNumb);
	$nol	="";
	if($Pattern_Length==2)
	{
		if($len==1) $nol="0";
	}
	elseif($Pattern_Length==3)
	{
		if($len==1) $nol="00";else if($len==2) $nol="0";
	}
	elseif($Pattern_Length==4)
	{
		if($len==1) $nol="000";else if($len==2) $nol="00";else if($len==3) $nol="0";
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
	
	$sql = "SELECT proj_Number, PRJCODE, PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
	$resultProj = $this->db->query($sql)->result();
	foreach($resultProj as $row) :
		$proj_Number = $row->proj_Number;
		$PRJCODE = $row->PRJCODE;
		$PRJNAME = $row->PRJNAME;
	endforeach;
	
	$DocNumber 		= "$Pattern_Code$PRJCODE$groupPattern-$lastPatternNumb";
	$SI_CODE 		= "$Pattern_Code$PRJCODE$groupPattern-$lastPatternNumb";
	$SI_INCCON		= 0;
	$SI_STEP		= 0;
	$PRJCODE		= $PRJCODE;
	$SI_OWNER		= '';
	
	$SI_DateY 		= date('Y');
	$SI_DateM 		= date('m');
	$SI_DateD 		= date('d');
	$SI_Date 		= "$SI_DateY-$SI_DateM-$SI_DateD";
	//$SI_ENDDATE 	= $SI_Date;
	$SI_APPDATE		= "$SI_DateY-$SI_DateM-$SI_DateD";
	$SI_CREATED 	= "$SI_DateY-$SI_DateM-$SI_DateD";
	$SI_DESC		= '';
	
		$SI_DPPER1	= 0;
		$SI_DPVAL1	= 0;
		$DPPPNVAL 	= 0;
		$sqlDP 	= "SELECT SUM(PINV_DPPER) AS DPPERCENT, SUM(PINV_DPVAL) AS DPVALUE, SUM(PINV_DPVALPPn) AS DPPPNVAL
					FROM tbl_projinv_header WHERE PRJCODE = '$PRJCODE' AND PINV_CAT = 1 AND PINV_STAT = 2";
		$resDP 	= $this->db->query($sqlDP)->result();
		foreach($resDP as $rowDP) :
			$SI_DPPER 	= $rowDP ->DPPERCENT;
			$SI_DPVALA 	= $rowDP ->DPVALUE;
			$DPPPNVALA 	= $rowDP ->DPPPNVAL;
			$SI_DPPER1	= $SI_DPPER1 + $SI_DPPER;
			$SI_DPVAL1	= $SI_DPVAL1 + $SI_DPVALA + $DPPPNVALA;
		endforeach;
	
	$SI_DPPER		= $SI_DPPER1;		
	$SI_DPVAL		= $SI_DPVAL1;
	$SI_VALUE		= 0;
	$SI_APPVAL		= 0;
	$SI_PROPPERC	= 0;
	$SI_PROPVAL		= 0;
	$SI_NOTES		= '';
	$SI_EMPID		= $DefEmp_ID;
	$SI_STAT		= 1;
	$SI_AMAND		= 2;
	$SI_AMANDNO		= '';
	$SI_AMANDVAL	= 0;
	$SI_AMANDSTAT	= 0;
	
	$PATT_YEAR 		= date('Y');
	$PATT_YEAR1		= date('y');
	$PATT_MONTH		= date('m');
	$PATT_DATE 		= date('d');
	
	if($PATT_MONTH == "01")
		$ROM_MONTH	= "I";
	elseif($PATT_MONTH == "02")
		$ROM_MONTH	= "II";
	elseif($PATT_MONTH == "03")
		$ROM_MONTH	= "III";
	elseif($PATT_MONTH == "04")
		$ROM_MONTH	= "IV";
	elseif($PATT_MONTH == "05")
		$ROM_MONTH	= "V";
	elseif($PATT_MONTH == "06")
		$ROM_MONTH	= "VI";
	elseif($PATT_MONTH == "07")
		$ROM_MONTH	= "VII";
	elseif($PATT_MONTH == "08")
		$ROM_MONTH	= "VIII";
	elseif($PATT_MONTH == "09")
		$ROM_MONTH	= "IX";
	elseif($PATT_MONTH == "10")
		$ROM_MONTH	= "X";
	elseif($PATT_MONTH == "11")
		$ROM_MONTH	= "XI";
	elseif($PATT_MONTH == "12")
		$ROM_MONTH	= "XII";
	
	//$SI_MANNO		= "$lastPatternNumb/JLM-ST/SI/$ROM_MONTH/$PATT_YEAR1";
	$SI_MANNO		= "";
}
else
{
	$DocNumber		= $default['SI_CODE'];
	$SI_CODE 		= $default['SI_CODE'];
	$SI_MANNO 		= $default['SI_MANNO'];
	$SI_INCCON 		= $default['SI_INCCON'];
	$SI_STEP 		= $default['SI_STEP'];
	$MAXSTEP		= $SI_STEP;
	$PRJCODE 		= $default['PRJCODE'];
	$SI_OWNER 		= $default['SI_OWNER'];
	$SI_DATE 		= $default['SI_DATE'];
	if($SI_DATE == '0000-00-00')
	{
		$SI_DateY 		= date('Y');
		$SI_DateM 		= date('m');
		$SI_DateD 		= date('d');
		$SI_DATE 		= "$SI_DateY-$SI_DateM-$SI_DateD";
	}
	
	$SI_ENDDATE 	= $default['SI_ENDDATE']; 
	$SI_APPDATE		= $default['SI_APPDATE'];
	$SI_CREATED 	= $default['SI_CREATED'];
	$SI_DESC 		= $default['SI_DESC'];
	$SI_DPPER 		= $default['SI_DPPER'];
	$SI_DPVAL 		= $default['SI_DPVAL'];
	$SI_VALUE 		= $default['SI_VALUE'];
	$SI_APPVAL 		= $default['SI_APPVAL'];
	$SI_PROPPERC	= $default['SI_PROPPERC'];
	$SI_PROPVAL		= $default['SI_PROPVAL'];
	$SI_AMAND		= $default['SI_AMAND'];
	$SI_AMANDNO		= $default['SI_AMANDNO'];
	$SI_AMANDVAL	= $default['SI_AMANDVAL'];
	$SI_AMANDSTAT	= $default['SI_AMANDSTAT'];
	$SI_NOTES 		= $default['SI_NOTES'];
	$SI_EMPID 		= $default['SI_EMPID'];
	$SI_STAT 		= $default['SI_STAT'];
	$PATT_YEAR 		= $default['PATT_YEAR'];
	$PATT_MONTH 	= $default['PATT_MONTH'];
	$PATT_DATE 		= $default['PATT_DATE'];
	$PATT_NUMBER 	= $default['PATT_NUMBER'];
}
?>
<!DOCTYPE html>
<html>
<head>
  	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/style.css'; ?>");</style>
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/styleZebra.css'; ?>");</style>
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
?>

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>
    <?php echo $h2_title; ?>
    <small><?php echo $h3_title; ?></small>  </h1>
  <?php /*?><ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#">Tables</a></li>
    <li class="active">Data tables</li>
  </ol><?php */?>
</section>
<!-- Main content -->
<section class="content">
    <div class="box box-primary">
<form name="frm" id="frm" method="post" action="<?php echo $form_action; ?>">
            <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
            <input type="hidden" name="FlagUSER" id="FlagUSER" value="<?php echo $FlagUSER; ?>" />
            <input type="Hidden" name="rowCount" id="rowCount" value="0">
            <table width="100%" border="0" style="size:auto">
                <tr>
                    <td width="15%" align="left" class="style1" valign="top">&nbsp;</td>
                    <td width="1%" align="left" class="style1" valign="top">&nbsp;</td>
                    <td width="36%" align="left" class="style1">&nbsp;</td> 
                    <td width="13%" align="left" class="style1" id="labelProject1" valign="top">&nbsp;</td>
                    <td width="35%" align="left" class="style1" id="labelProject2" valign="top">&nbsp;</td>
                </tr>
                <tr>
                    <td width="15%" align="left" class="style1" valign="top">&nbsp;&nbsp;Include to Contract</td>
                    <td width="1%" align="left" class="style1" valign="top">:</td>
                    <td width="36%" align="left" class="style1">
                        <input type="radio" name="SI_INCCON" id="isInclude1" value="1" <?php if($SI_INCCON == 1) { ?> checked <?php } ?>> 
                        Yes&nbsp;&nbsp;
                        <input type="radio" name="SI_INCCON" id="isInclude2" value="0" <?php if($SI_INCCON == 0) { ?> checked <?php } ?>> 
                        No&nbsp;&nbsp;
                        <input type="radio" name="SI_INCCON" id="isInclude2" value="0" <?php if($SI_INCCON == 2) { ?> checked <?php } ?>> Add To Invoice                    </td> 
                    <td width="13%" align="left" class="style1" id="labelProject1" valign="top">&nbsp;</td>
                    <td width="35%" align="left" class="style1" id="labelProject2" valign="top">&nbsp;</td>
                </tr>
                <tr>
                    <td align="left" class="style1">&nbsp;&nbsp;SI Number</td>
                    <td align="left" class="style1">:</td>
                    <td align="left" class="style1">
						<?php echo $DocNumber; ?>
                    	<input type="hidden" class="textbox" name="SI_CODE" id="SI_CODE" size="30" value="<?php echo $SI_CODE; ?>" />
               	  		<input type="hidden" class="textbox" name="PATT_NUMBER" id="PATT_NUMBER" size="30" value="<?php echo $PATT_NUMBER; ?>" />                  	</td>
                    <td align="left" class="style1"><?php if($FlagUSER == 'USRSI') { ?> Date of Filing <?php } else { ?>SI Date <?php } ?></td>
                    <td align="left" class="style1">
                          <div class="input-group date">
                            <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>&nbsp;</div><input type="text" name="SI_DATE" class="form-control pull-left" id="datepicker" value="<?php echo $SI_DATE; ?>" style="width:150px"></div></td>
                    <!-- SI_CODE, PATT_NUMBER, SI_MANNO -->
                </tr>
                <tr>
                    <td align="left" class="style1">&nbsp;&nbsp;Manual Number</td>
                    <td align="left" class="style1">:</td>
                    <td colspan="3" align="left" class="style1">
                    	<input type="text" name="SI_MANNO" id="SI_MANNO" value="<?php echo $SI_MANNO; ?>" class="form-control" style="max-width:250px"></td>
                    <!-- SI_CODE, PATT_NUMBER, SI_MANNO -->
                </tr>
                <tr>
                    <td align="left" class="style1">&nbsp;&nbsp;SI Step</td>
                    <td align="left" class="style1">:</td>
                    <td align="left" class="style1">
						<?php /*?><select name="SI_STEP1" id="SI_STEP1" class="listmenu" onChange="changeStep(this.value)">
                            <?php
                                for($STEP=0;$STEP<=30;$STEP++)
                                {
                                ?>
                                    <option value="<?php echo $STEP; ?>" <?php if($STEP == $MAXSTEP) { ?> selected <?php } ?>><?php echo $STEP; ?></option>
                                    <?php
                                }
                            ?>
                        </select><?php */?>
                        <input type="text" name="SI_STEP" id="SI_STEP" value="<?php echo $MAXSTEP; ?>" class="form-control" style="max-width:80px">                    </td>
                    <td align="left" class="style1" style="display:none">SI End Date</td>
                    <td align="left" class="style1" style="display:none">: &nbsp;
                        <script type="text/javascript">SunFishERP_DateTimePicker('SI_ENDDATE','<?php echo $SI_ENDDATE;?>','onMouseOver="mybirdthdates();"');</script>					</td>
                    <!-- SI_STEP, SI_ENDDATE -->
                </tr>
                <script>
                    function changeStep(thisVal)
                    {
                        document.getElementById('SI_STEP').value = thisVal;
                    }
                </script>
                <tr>
                    <td align="left" class="style1">&nbsp;&nbsp;Project</td>
                    <td align="left" class="style1">:</td>
                    <td align="left" class="style1">
                    <select name="PRJCODE1" id="PRJCODE1" onChange="chooseProject()" class="form-control" style="max-width:300px" disabled>
                        <option value="none">--- None ---</option>
                        <?php 
                        if($recordcountProject > 0)
                        {
                            foreach($viewProject as $row) :
                                $PRJCODE1 	= $row->PRJCODE;
                                $PRJNAME 	= $row->PRJNAME;
                                ?>
                                <option value="<?php echo $PRJCODE1; ?>" <?php if($PRJCODE1 == $PRJCODE) { ?> selected <?php } ?>><?php echo $PRJNAME; ?></option>
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
                    <input type="hidden" name="PRJCODE" id="PRJCODE" value="<?php echo $PRJCODE; ?>" ></td>
                    <td align="left" class="style1" nowrap>&nbsp;</td>
                <td align="left" class="style1">&nbsp;</td>
                  <!-- PRJCODE1, PRJCODE -->
                </tr>
                <tr>
                    <td align="left" class="style1">&nbsp;&nbsp;Project Value</td>
                    <td align="left" class="style1">:</td>
                    <td align="left" class="style1" style="font-weight:bold">
                        <input type="text" class="form-control" style="max-width:200px; text-align:right" name="proj_amountIDR1" id="proj_amountIDR1" value="<?php print number_format($proj_amountIDR, $decFormat); ?>" disabled>
                        <input type="hidden" size="15" class="textbox" style="text-align:right;" name="proj_amountIDR" id="proj_amountIDR" value="<?php echo $proj_amountIDR; ?>">            </td> 
                    <td align="left" class="style1" valign="top" nowrap>Charges Filed</td>
                    <td align="left" class="style1" valign="top">
                        <input type="text" class="form-control" style="max-width:200px; text-align:right" name="SI_VALUE1" id="SI_VALUE1" value="<?php print number_format($SI_VALUE, 2); ?>" onBlur="getSIValue(this)" onKeyPress="return isIntOnlyNew(event);" <?php if($SI_STAT != 1) { ?> readonly <?php } ?>>
                      <input type="hidden" size="15" class="textbox" style="text-align:right;" name="SI_VALUE" id="SI_VALUE" value="<?php echo $SI_VALUE; ?>">
                      <input type="hidden" name="SI_STATVAL" id="SI_STATVAL" value="<?php echo $SI_STAT; ?>">
                      <input type="hidden" size="17" class="textbox" style="text-align:right;" name="SI_DPVAL" id="SI_DPVAL" value="<?php echo $SI_DPVAL; ?>">
                      <input type="hidden" size="2" class="textbox" style="text-align:right;" name="SI_DPPER" id="SI_DPPER" value="<?php echo $SI_DPPER; ?>">                  </td>
                    <!-- SI_RETVAL, SI_DPPER, SI_DPPER1 -->
                </tr>
                <tr>
                    <td align="left" class="style1">&nbsp;&nbsp;PPn Value (10%)</td>
                    <td align="left" class="style1">:</td>
                    <td align="left" class="style1" style="font-weight:bold">
                      <?php
                        $proj_amountPPnIDR 	= $proj_amountIDR * 0.1;
                        $proj_amountnPPnIDR = $proj_amountIDR + $proj_amountPPnIDR;
                    ?>
                      <input type="text" class="form-control" style="max-width:200px; text-align:right" name="proj_amountPPnIDR1" id="proj_amountPPnIDR1" value="<?php print number_format($proj_amountPPnIDR, $decFormat); ?>" disabled>
                      <input type="hidden" size="15" class="textbox" style="text-align:right;" name="proj_amountPPnIDR" id="proj_amountPPnIDR" value="<?php echo $proj_amountPPnIDR; ?>">              </td> 
                    <td align="left" class="style1">Charges Approved</td>
                    <td align="left" class="style1"><input type="text" class="form-control" style="max-width:200px; text-align:right" name="SI_APPVAL1" id="SI_APPVAL1" value="<?php print number_format($SI_APPVAL, 2); ?>" onBlur="getSIAPPVal(this)" onKeyPress="return isIntOnlyNew(event);" <?php //if($isOpen != 1) { ?>  <?php //} ?>>
                      <input type="hidden" size="15" class="textbox" style="text-align:right;" name="SI_APPVAL" id="SI_APPVAL" value="<?php echo $SI_APPVAL; ?>">                    </td>
                    <!-- SI_DPVAL, SI_DPVAL1 -->
                </tr>
                <script>
                    function getDPValue(thisVal)
                    {
                        var decFormat		= document.getElementById('decFormat').value;
                        document.getElementById('SI_DPPER1').value 		= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(thisVal)),decFormat));
                        document.getElementById('SI_DPPER').value 		= thisVal;
                        proj_amountIDR		= document.getElementById('proj_amountTotIDR').value;
                        SI_DPVALx			= thisVal * proj_amountIDR / 100;
                        document.getElementById('SI_DPVAL').value 		= SI_DPVALx;
                        document.getElementById('SI_DPVAL1').value 		= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(SI_DPVALx)),decFormat));
                    }
                </script>
                <tr>
                    <td align="left" class="style1" nowrap>&nbsp;&nbsp;Tot. Project  Value (+ PPn 10%)</td>
                    <td align="left" class="style1">:</td>
                    <td align="left" class="style1" style="font-weight:bold">
                        <input type="text" class="form-control" style="max-width:200px; text-align:right" name="proj_amountTotIDR1" id="proj_amountTotIDR1" value="<?php print number_format($proj_amountnPPnIDR, $decFormat); ?>" disabled>
                        <input type="hidden" size="15" class="textbox" style="text-align:right;" name="proj_amountTotIDR" id="proj_amountTotIDR" value="<?php echo $proj_amountnPPnIDR; ?>">            </td>
                    <td align="left" class="style1" <?php //if($isOpen != 1) { ?>  <?php //} ?> nowrap>Charges Approved (%)</td>
                    <td align="left" class="style1" <?php //if($isOpen != 1) { ?>  <?php //} ?>><input type="text" class="form-control" style="max-width:80px; text-align:right" name="SI_PROPPERC1" id="SI_PROPPERC1" value="<?php print number_format($SI_PROPPERC, 4); ?>" onBlur="getPROPPERValue(this.value)">
                      <input type="hidden" size="2" class="textbox" style="text-align:right;" name="SI_PROPPERC" id="SI_PROPPERC" value="<?php echo $SI_PROPPERC; ?>">                    </td> 
                </tr>
                <tr>
                    <td align="left" class="style1" valign="top">&nbsp;&nbsp;Job Description</td>
                    <td align="left" class="style1" valign="top">:</td>
                    <td align="left" class="style1"><textarea class="textbox" name="SI_DESC"id="SI_DESC" cols="30" style="height:50px"><?php echo $SI_DESC; ?></textarea></td>
                    <td align="left" class="style1" valign="top" <?php //if($isOpen != 1 || $FlagUSER != 'APPUSR') { ?> style="display:none" <?php //} ?>>Proposed App. Value</td>
                    <td align="left" class="style1" valign="top" <?php //if($isOpen != 1) { ?> style="display:none" <?php //} ?>><input type="text" size="15" class="textbox" style="text-align:right;" name="SI_PROPVAL1" id="SI_PROPVAL1" value="<?php print number_format($SI_PROPVAL, 2); ?>" onKeyPress="return isIntOnlyNew(event);" >
                        <input type="hidden" size="15" class="textbox" style="text-align:right;" name="SI_PROPVAL" id="SI_PROPVAL" value="<?php echo $SI_PROPVAL; ?>">                    </td> 
                </tr>
                <?php
                    //echo "$FlagUSER == 'USRSI' && $SI_STAT == 1";
                ?>
                <tr <?php if($FlagUSER == 'USRSI' || $SI_STAT == 1) { ?> style="display:none" <?php } ?> >
                    <td align="left" class="style1" valign="top">&nbsp;&nbsp;Amandement this SI</td>
                    <td align="left" class="style1" valign="top">:</td>
                    <td align="left" class="style1">
                        <input type="radio" name="SI_AMAND" id="SI_AMAND1" value="1" <?php if($SI_AMAND == 1) { ?> checked <?php } ?> onClick="showAmandemen(1);"> 
                        Yes&nbsp;&nbsp;
                        <input type="radio" name="SI_AMAND" id="SI_AMAND2" value="2" <?php if($SI_AMAND == 2) { ?> checked <?php } ?> onClick="showAmandemen(2);"> 
                        No
                        <input type="hidden" name="SI_IS_AMAND" id="SI_IS_AMAND" value="<?php echo $SI_AMAND; ?>">                    </td> 
                    <td align="left" class="style1" id="labelProject1" nowrap>&nbsp;</td>
                    <td align="left" class="style1" id="labelProject2">&nbsp;</td>
                </tr>
                <script>
                    function showAmandemen(thisVal)
                    {
                        if(thisVal == 2)
                        {
                            document.getElementById('isamandement').style.display 		= 'none';
                            document.getElementById('isamandementstat').style.display 	= 'none';
                            document.getElementById('sistatus1').style.display 			= '';
                            document.getElementById('sistatus2').style.display 			= '';
                        }
                        else
                        {
                            document.getElementById('isamandement').style.display		= '';
                            document.getElementById('isamandementstat').style.display 	= '';
                            document.getElementById('sistatus1').style.display 			= 'none';
                            document.getElementById('sistatus2').style.display 			= 'none';
                        }
                        document.getElementById('SI_IS_AMAND').value			= thisVal;
                    }
                </script>
                <?php /*?><tr id="isamandement" <?php if($SI_AMAND == 2) { ?> style="display:none" <?php } ?> ><?php */?>
                <tr id="isamandement" <?php if($SI_STAT == 1) { ?> style="display:none" <?php } ?> >
                    <td align="left" class="style1">&nbsp;&nbsp;Amandement Number</td>
                    <td align="left" class="style1">:</td>
                    <td align="left" class="style1"><input type="text" size="20" class="textbox" name="SI_AMANDNO" id="SI_AMANDNO" value="<?php echo $SI_AMANDNO; ?>"></td> 
                    <td align="left" class="style1" id="labelProject1">Amandement Value</td>
                    <td align="left" class="style1" id="labelProject2">:&nbsp; 
                        <input type="text" size="15" class="textbox" style="text-align:right;" name="SI_AMANDVAL1" id="SI_AMANDVAL1" value="<?php print number_format($SI_AMANDVAL, 2); ?>" onKeyPress="return isIntOnlyNew(event);" onBlur="getAMANDValue(this.value)">
                        <input type="hidden" size="15" class="textbox" style="text-align:right;" name="SI_AMANDVAL" id="SI_AMANDVAL" value="<?php echo $SI_AMANDVAL; ?>">                    </td>
                    <!-- SI_PROG, SI_PROG1 -->
                </tr>
                <?php /*?><tr id="isamandementstat" <?php if($isOpen != 1 || $SI_AMAND == 2) { ?> style="display:none" <?php } ?>><?php */?>
                <tr id="isamandementstat" <?php if($FlagUSER == 'USRSI' || $SI_STAT == 1) { ?> style="display:none" <?php } ?> >
                    <td align="left" class="style1">&nbsp;</td>
                    <td align="left" class="style1">&nbsp;</td>
                    <td align="left" class="style1">&nbsp;</td> 
                    <td align="left" class="style1">Amandement Stat</td>
                    <td align="left" class="style1">: &nbsp;
                        <input type="radio" name="SI_AMANDSTAT" id="SI_AMANDSTAT1" value="1" <?php if($SI_AMANDSTAT == 1) { ?> checked <?php } ?> onClick="changeStatAmand(1)">
                        New&nbsp;&nbsp;
                        <input type="radio" name="SI_AMANDSTAT" id="SI_AMANDSTAT2" value="2" <?php if($SI_AMANDSTAT == 2) { ?> checked <?php } ?> onClick="changeStatAmand(2)">
                        Approve
                        <input type="hidden" name="SI_AMANDSTATVAL" id="SI_AMANDSTATVAL" value="<?php echo $SI_AMANDSTAT; ?>" >                    </td>
                </tr>
                <script>
                    function getAMANDValue(thisVal)
                    {
                        var decFormat		= document.getElementById('decFormat').value;
                        document.getElementById('SI_AMANDVAL1').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(thisVal)),decFormat));
                        document.getElementById('SI_AMANDVAL').value 	= thisVal;
                    }
                    
                    function changeStatAmand(thisVal)
                    {
                        document.getElementById('SI_AMANDSTATVAL').value 	= thisVal;
                    }
                </script>
                <script>
                    function getSIValue(thisVal)
                    {
                        var decFormat		= document.getElementById('decFormat').value;
                        SI_VALUE1			= eval(thisVal).value.split(",").join("");
                        document.getElementById('SI_VALUE1').value 		= RoundNDecimal(parseFloat(Math.ceil(SI_VALUE1)),decFormat);
                        document.getElementById('SI_VALUE').value 		= SI_VALUE1;
                    }
                </script>
                <tr>
                    <td align="left" class="style1" valign="top">&nbsp;&nbsp;Notesa</td>
                    <td align="left" class="style1" valign="top">:</td>
                    <td align="left" class="style1"><textarea name="SI_NOTES" class="textbox" id="SI_NOTES" cols="30" style="height:50px"><?php echo $SI_NOTES; ?></textarea></td>
                    <td align="left" class="style1" id="sistatus1" <?php if($isOpen != 1 || $SI_AMAND == 1) { ?> style="display:none" <?php } ?> valign="top">Status</td>
                    <td align="left" class="style1" id="sistatus2" <?php if($isOpen != 1 || $SI_AMAND == 1) { ?> style="display:none" <?php } ?> valign="top">: &nbsp;
                        <input type="radio" name="SI_STAT" id="SI_STAT1" value="1" <?php if($SI_STAT == 1) { ?> checked <?php } ?>>
                        New&nbsp;&nbsp;
                        <input type="radio" name="SI_STAT" id="SI_STAT2" value="2" <?php if($SI_STAT == 2) { ?> checked <?php } ?>>
                        Approve
                        &nbsp;&nbsp;
                        <input type="radio" name="SI_STAT" id="SI_STAT3" value="2" <?php if($SI_STAT == 3) { ?> checked <?php } ?>>
                        Close                      </td> 
                </tr>
                <script>
                    function getSIAPPVal(thisVal)
                    {
                        var decFormat		= document.getElementById('decFormat').value;
                        SI_APPVAL1			= eval(thisVal).value.split(",").join("");
                         //doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(adend_Value2AX)),decFormat));
                         if(SI_APPVAL1 > 0)
                         {
                            document.getElementById('SI_APPVAL1').value = doDecimalFormatxx(RoundNDecimal(parseFloat(Math.ceil(SI_APPVAL1)),2));
                            //doDecimalFormat(RoundNDecimal(parseFloat(Math.ceil(SI_APPVAL1)),2));
                         }
                         else
                         {
                            document.getElementById('SI_APPVAL1').value = RoundNDecimal(parseFloat(Math.ceil(SI_APPVAL1)),decFormat);
                         }
                            document.getElementById('SI_APPVAL').value 	= SI_APPVAL1;
                        
                        //getPROPPERValue(PROPPER);
                        var SI_VALUE		= document.getElementById('SI_VALUE').value;
                        var percenT			= SI_APPVAL1 / SI_VALUE * 100;
                        document.getElementById('SI_PROPPERC1').value 	= RoundNDecimal(parseFloat(Math.ceil(percenT)),decFormat);
                        document.getElementById('SI_PROPPERC').value 	= percenT;
                    }
                </script>        <script>
                    function getPROPPERValue(thisVal)
                    {
                        var decFormat		= document.getElementById('decFormat').value;
                        document.getElementById('SI_PROPPERC1').value 	= RoundNDecimal(parseFloat(Math.ceil(thisVal)),decFormat);
                        document.getElementById('SI_PROPPERC').value 	= thisVal;
                        SI_APPVAL			= document.getElementById('SI_APPVAL').value;
                        SI_PROPVALx			= thisVal * SI_APPVAL / 100;
                        document.getElementById('SI_PROPVAL').value 	= SI_PROPVALx;
                        document.getElementById('SI_PROPVAL1').value 	= RoundNDecimal(parseFloat(Math.ceil(SI_PROPVALx)),decFormat);
                    }
                </script> 
                <tr>
                    <td colspan="5" align="left" class="style1">&nbsp;</td>
                </tr> 
                <tr>
                    <td align="left" class="style1">&nbsp;</td>
                    <td align="left" class="style1">&nbsp;</td>
                    <td colspan="3" align="left" class="style1">
                        <?php
                            if($SI_STAT == 2 || $SI_STAT == 3)
                            //if($SI_STAT == 3)
                            {
                                ?>
                                    <input type="button" class="btn btn-primary" name="btnSubmt" id="btnSubmt" value="<?php if($task=='add') echo 'save'; else echo 'update';?>" onClick="submitForm(1);" />
                                <?php 
                            }
                            else
                            {
                                ?>
                                    <input type="button" class="btn btn-primary" name="btnSubmt" id="btnSubmt" value="<?php if($task=='add')echo 'save'; else echo 'update';?>" onClick="submitForm(2);" />
                                <?php 				
                            }
                        ?>
                        &nbsp;
                        <?php 
                            if ( ! empty($link))
                            {
                                foreach($link as $links)
                                {
                                    echo $links;
                                }
                            }
                        ?>					</td>
                </tr>
                <tr>
                  <td colspan="5" align="left" class="style1">&nbsp;</td>
                </tr>
   		  </table>
      </form>
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
    $('#datepicker').datepicker({
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
</script>
<script>	
	function submitForm(value)
	{
		var SI_MANNO	= document.getElementById('SI_MANNO').value;
		var SI_STEP		= document.getElementById('SI_STEP').value;
		var SI_VALUE	= document.getElementById('SI_VALUE').value;
		var SI_STATVAL	= document.getElementById('SI_STATVAL').value;
		var FlagUSER	= document.getElementById('FlagUSER').value;
		var SI_IS_AMAND	= document.getElementById('SI_IS_AMAND').value;
		
		if(SI_STATVAL != 1)
		{
			if(SI_IS_AMAND == 2)
			{
				alert('Document has Approved/Closed. You can not update this document.');
				return false;
			}			
		}
		
		if(SI_MANNO == '')
		{
			alert('Please input SI Manual Number.');
			document.getElementById('SI_MANNO').focus();
			return false;
		}
		
		if(SI_STEP == 0)
		{
			alert('Please select step of Site Instruction.');
			document.getElementById('SI_STEP').focus();
			return false;
		}
		
		if(SI_VALUE == 0)
		{
			alert('Please input Charges Value.');
			document.getElementById('SI_VALUE1').value = '';
			document.getElementById('SI_VALUE1').focus();
			return false;
		}
		
		if(FlagUSER == 'APPSI')
		{
			var SI_APPVAL	= document.getElementById('SI_APPVAL').value;
			var SI_PROPPERC	= document.getElementById('SI_PROPPERC').value;
			
			if(SI_APPVAL == 0)
			{
				alert('Please input Charges Approved Value.');
				document.getElementById('SI_APPVAL1').value = '';
				document.getElementById('SI_APPVAL1').focus();
				return false;
			}
			if(SI_PROPPERC == 0)
			{
				alert('Please input Proposed Percentation.');
				document.getElementById('SI_PROPPERC1').value = '';
				document.getElementById('SI_PROPPERC1').focus();
				return false;
			}
		}
		
		if(SI_IS_AMAND == 1)
		{
			var SI_AMANDNO		= document.getElementById('SI_AMANDNO').value;
			var SI_AMANDVAL		= document.getElementById('SI_AMANDVAL').value;
			var SI_AMANDSTATVAL	= document.getElementById('SI_AMANDSTATVAL').value;
			if(SI_AMANDNO == '')
			{
				alert('Please input Amandement Number.');
				document.getElementById('SI_AMANDNO').focus();
				return false;
			}
			
			if(SI_AMANDVAL == 0)
			{
				alert('Please input Amandement Value.');
				document.getElementById('SI_AMANDVAL1').value = '';
				document.getElementById('SI_AMANDVAL1').focus();
				return false;
			}
			
			if(SI_AMANDSTATVAL == 0)
			{
				alert('Please select Amandement Status.');
				document.getElementById('SI_AMANDSTATVAL').value 	= 1;
				document.getElementById('SI_AMANDSTAT1').checked	= true;
				return false;
			}
		}
		
		document.frm.submit();
	}
	
	function getTermPayment(thisval, thirow)
	{
		var decFormat		= document.getElementById('decFormat').value;
		var adend_Value1Ax	= eval(document.getElementById('adend_Value1Ax'+thirow)).value.split(",").join("");
		document.getElementById('adend_Value1A'+thirow).value = adend_Value1Ax;
		
		var adend_Percentx	= eval(document.getElementById('adend_Percentx'+thirow)).value.split(",").join("");
		adend_Value2AX		= adend_Value1Ax * adend_Percentx / 100;
		
		document.getElementById('adend_Value2AX'+thirow).value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(adend_Value2AX)),decFormat));
		document.getElementById('adend_Value2A'+thirow).value 	= adend_Value2AX;
		document.getElementById('adend_Value1Ax'+thirow).value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(adend_Value1Ax)),decFormat));
		document.getElementById('adend_Value1A'+thirow).value 	= adend_Value1Ax;
		document.getElementById('adend_Percentx'+thirow).value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(adend_Percent)),decFormat));
		document.getElementById('adend_Percent'+thirow).value 	= adend_Percentx;
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
		//else return (c + '.' + dec);
		else return (c);  // untuk menghilangkan 2 angka di belakang koma
	}
	
	function doDecimalFormatxx(angka)
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
		//else return (c);  // untuk menghilangkan 2 angka di belakang koma
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
	
	function changeFDate(thisVal)
	{		
		var date 			= new Date(thisVal);
		var datey 			= new Date(date.getFullYear(), date.getMonth(), date.getDate(), 0, 0, 0);
		var theM			= datey.getMonth();
		var dateDesc		= datey.getFullYear()+ "-" + theM + "-" + datey.getDate();
		document.getElementById('PINV_TTODatex').value 	= formatDate(datey);
		var FDate			= document.getElementById('PINV_TTODatex').value
		changeDueDate(FDate)
	}
	
	function changeDueDate(thisVal)
	{
		var FDate			= document.getElementById('PINV_TTODatex').value
		var date 			= new Date(FDate);
		//alert(date)
		PINV_TTOTerm		= parseInt(document.getElementById('PINV_TTOTerm').value);
		var datey 			= new Date(date.getFullYear(), date.getMonth(), date.getDate() + PINV_TTOTerm, 0, 0, 0);
		var theM			= datey.getMonth();
		if(theM == 0)
		{
			theMD	= 'January';
		}
		else if(theM == 1)
		{
			theMD	= 'February';
		}
		else if(theM == 2)
		{
			theMD	= 'March';
		}
		else if(theM == 3)
		{
			theMD	= 'April';
		}
		else if(theM == 4)
		{
			theMD	= 'May';
		}
		else if(theM == 5)
		{
			theMD	= 'June';
		}
		else if(theM == 6)
		{
			theMD	= 'July';
		}
		else if(theM == 7)
		{
			theMD	= 'August';
		}
		else if(theM == 8)
		{
			theMD	= 'September';
		}
		else if(theM == 9)
		{
			theMD	= 'October';
		}
		else if(theM == 10)
		{
			theMD	= 'November';
		}
		else if(theM == 11)
		{
			theMD	= 'December';
		}
		var dateDesc	=  datey.getDate()+ " " + theMD + " " + datey.getFullYear();
		document.getElementById('PINV_TTODDate').value 	= formatDate(datey);
		document.getElementById('PINV_TTODDatex').value	= dateDesc;
	}
	
	function formatDate(d) 
	{
		var dd = d.getDate()
		if ( dd < 10 ) dd = '0' + dd
		
		var mm = d.getMonth()+1
		if ( mm < 10 ) mm = '0' + mm
		
		var yy = d.getFullYear()
		
		return yy+'-'+mm+'-'+dd
	}

</script>
<?php 
$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>