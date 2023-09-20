<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 8 Februari 2017
 * File Name	= listproject_form.php
 * Location		= -
*/
?>
<?php
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');

$this->load->view('template/topbar');
$this->load->view('template/sidebar');

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

if($task == 'add')
{
	$proj_Type = 1;
	$proj_Category = 2;
	$proj_PM_EmpID = '';
	//$proj_CustCode = '';
	$PRJCURR = 'IDR';
	$default['PRJNAME'] = '';
	foreach($viewDocPattern as $row) :
		$Pattern_Code = $row->Pattern_Code;
		$Pattern_Position = $row->Pattern_Position;
		$Pattern_YearAktive = $row->Pattern_YearAktive;
		$Pattern_MonthAktive = $row->Pattern_MonthAktive;
		$Pattern_DateAktive = $row->Pattern_DateAktive;
		//$Pattern_Length = $row->Pattern_Length;
		$Pattern_Length = 4;
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
	$year = (int)$Pattern_YearAktive;
	$month = (int)$Pattern_MonthAktive;
	$date = (int)$Pattern_DateAktive;

	$this->db->where('Patt_Year', $year);
	$this->db->where('Patt_Month', $month);
	$this->db->where('Patt_Date', $date);
	$myCount = $this->db->count_all('tbl_project');
	
	$sql = "SELECT MAX(Patt_Number) as maxNumber FROM tbl_project
			WHERE Patt_Year = $year";
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
	
	//echo $pattMonth;
	//echo "&nbsp;";
	//echo $pattDate;
	
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
	$lastPatternNumb1 = $myMax;
	$len = strlen($lastPatternNumb);
	
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
	$DocNumber = "$Pattern_Code$groupPattern-$lastPatternNumb";
	
	$REC_DATEA 		= date('Y');
	$REC_DATEB 		= date('m');
	$REC_DATEC 		= date('d');
	$REC_DATED 		= "$REC_DATEB/$REC_DATEC/$REC_DATEA";
	
	$REC_CODE 			= $DocNumber;
	$REC_NO 			= '';
	$REC_LL_NO			= '';
	$REC_PAGE_NO		= 1;
	$REC_PRJNAME		= '';
	$REC_OWNER			= '';
	$REC_CONSULT		= '';
	$REC_LOCATION		= '';
	$REC_DATE			= $REC_DATED;
	$REC_PQ_DATE		= $REC_DATED;
	$REC_TEND_DATE		= $REC_DATED;
	$REC_PRJTYPE		= '';
	$REC_FUNDSRC		= '';
	$REC_TURNOVER		= '';
	$REC_EXP			= '';
	$REC_BASCAPAB		= '';
	$REC_FINCAPAB		= '';
	$REC_DIFICLEV		= '';
	$REC_PRJFIELD		= '';
	$REC_MTRSRC			= '';
	$REC_TIMEXEC		= '';
	$REC_PQ_ESTIME		= '';
	$REC_TEND_ESTIME	= '';
	$REC_BIDDER			= '';
	$REC_BIDDER_QTY		= '';
	$REC_ESKAL_EST		= '';
	$REC_CONCLUTION		= '';
	$REC_SIGN_DATE		= $REC_DATED;
	$REC_MTRSRC			= '';
	$REC_MNG_MRK		= '';
	$REC_DIR_MRK		= '';
	$REC_MNG_EST		= '';
	$REC_PRESDIR		= '';
	$REC_NOTES			= '';
	$REC_STAT			= 0;
	$DOK_NO				= '';
	$REVISI				= '';
	$AMAND				= '';
	$REC_CREATER		= '';
	$Patt_Number		= $lastPatternNumb1;
}
else
{
	$REC_CODE 			= $default['REC_CODE'];
	$DocNumber			= $default['REC_CODE'];
	$REC_NO 			= $default['REC_NO'];
	$REC_LL_NO 			= $default['REC_LL_NO'];
	$REC_PAGE_NO 		= $default['REC_PAGE_NO'];
	$REC_PRJNAME 		= $default['REC_PRJNAME'];
	$REC_OWNER 			= $default['REC_OWNER'];
	$REC_CONSULT 		= $default['REC_CONSULT'];
	$REC_LOCATION 		= $default['REC_LOCATION'];	
	$REC_DATE 			= $default['REC_DATE'];
	$REC_PQ_DATE 		= $default['REC_PQ_DATE'];
	$REC_TEND_DATE		= $default['REC_TEND_DATE'];
	$REC_PRJTYPE		= $default['REC_PRJTYPE'];
	$REC_FUNDSRC		= $default['REC_FUNDSRC'];
	$REC_TURNOVER 		= $default['REC_TURNOVER'];
	$REC_EXP 			= $default['REC_EXP'];	
	$REC_BASCAPAB		= $default['REC_BASCAPAB'];
	$REC_FINCAPAB		= $default['REC_FINCAPAB'];
	$REC_DIFICLEV 		= $default['REC_DIFICLEV'];
	$REC_PRJFIELD 		= $default['REC_PRJFIELD'];
	$REC_MTRSRC 		= $default['REC_MTRSRC'];
	$REC_TIMEXEC 		= $default['REC_TIMEXEC'];
	$REC_PQ_ESTIME 		= $default['REC_PQ_ESTIME'];
	$REC_TEND_ESTIME 	= $default['REC_TEND_ESTIME'];
	$REC_BIDDER 		= $default['REC_BIDDER'];
	$REC_BIDDER_QTY 	= $default['REC_BIDDER_QTY'];
	$REC_ESKAL_EST 		= $default['REC_ESKAL_EST'];
	$REC_CONCLUTION 	= $default['REC_CONCLUTION'];
	$REC_SIGN_DATE 		= $default['REC_SIGN_DATE'];
	$REC_MTRSRC 		= $default['REC_MTRSRC'];
	$REC_MNG_MRK 		= $default['REC_MNG_MRK'];
	$REC_DIR_MRK 		= $default['REC_DIR_MRK'];
	$REC_MNG_EST 		= $default['REC_MNG_EST'];
	$REC_PRESDIR 		= $default['REC_PRESDIR'];
	$REC_NOTES 			= $default['REC_NOTES'];
	$REC_STAT 			= $default['REC_STAT'];
	$DOK_NO 			= $default['DOK_NO'];
	$REVISI 			= $default['REVISI'];
	$AMAND 				= $default['AMAND'];
	$REC_CREATER 		= $default['REC_CREATER'];
	$Patt_Number 		= $default['Patt_Number'];
	$lastPatternNumb1	= $Patt_Number;
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

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>
    <?php echo $h2_title; ?>
    <small>project</small>  </h1>
  <?php /*?><ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#">Tables</a></li>
    <li class="active">Data tables</li>
  </ol><?php */?>
</section>
<!-- Main content -->
<section class="content">
    <div class="box box-primary"><br>
      <form name="frm" id="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return validateInData();">
            <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
            <input type="Hidden" name="rowCount" id="rowCount" value="0">
            <table width="100%" border="0" style="size:auto" bgcolor="#FFFFFF">
                <tr>
                    <td width="12%" align="left" nowrap>Doc. Code</td>
               	  	<td width="1%" align="left">:</td>
                  	<td colspan="4" align="left">
                        <?php echo $DocNumber; ?>
       			    <input type="hidden"  id="lastPatternNumb" name="lastPatternNumb" size="20" value="<?php echo $lastPatternNumb1; ?>" />
                      	<input type="hidden" name="Patt_Number" id="Patt_Number" value="<?php echo $Patt_Number; ?>">
   			  	  	<input type="hidden" name="REC_CODE" id="REC_CODE" value="<?php echo $DocNumber; ?>" ></td>
              	</tr>
                <tr>
                    <td width="12%" align="left" nowrap>Recomen. No.</td>
                  	<td width="1%" align="left">:</td>
                  	<td colspan="4" align="left">
                        <label>
                            <input type="text" class="form-control" style="max-width:200px; " name="REC_NO" id="REC_NO" value="<?php echo $REC_NO; ?>" maxlength="15" onChange="functioncheck(this.value)">
                        </label><label>&nbsp;&nbsp;</label><label id="isHidden"></label>&nbsp;&nbsp;&nbsp;
                    <input type="hidden" name="CheckThe_Code" id="CheckThe_Code" size="20" maxlength="25" value="0" ></td>
              	</tr>
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
									document.getElementById("isHidden").innerHTML = ' Recomendation No already exist ... !';
									document.getElementById("isHidden").style.color = "#ff0000";
                                }
                                else
                                {
                                    document.getElementById('CheckThe_Code').value= recordcount;
									document.getElementById("isHidden").innerHTML = ' Recomendation No : OK .. !';
									document.getElementById("isHidden").style.color = "green";
                                }
                            }
                        }
                        var REC_NO = document.getElementById('REC_NO').value;
						
                        ajaxRequest.open("GET", "<?php echo base_url().'index.php/c_project/project_recomendation/getTheCode/';?>" + REC_NO, true);
                        ajaxRequest.send(null);
                    }
                </script>
                <tr>
                    <td align="left" nowrap>Project Name</td>
                    <td align="left">:</td>
                    <td colspan="4" align="left" valign="middle"><input type="text" class="form-control" style="max-width:350px" name="REC_PRJNAME" id="REC_PRJNAME" size="50" value="<?php echo $REC_PRJNAME; ?>" ></td> 
              	</tr>
                <tr>
                    <td align="left" nowrap>Project Owner</td>
                    <td align="left">:</td>
                    <td colspan="4" align="left">
                    <select name="REC_OWNER" id="REC_OWNER" class="form-control" style="max-width:350px">
                            <?php
                                $own_Code 	= '';
                                $CountOwn 	= $this->db->count_all('tbl_owner');
                                $sqlOwn 	= "SELECT own_Code, own_Title, own_Name FROM tbl_owner";
                                $resultOwn = $this->db->query($sqlOwn)->result();
                                if($CountOwn > 0)
                                {
                                    foreach($resultOwn as $rowOwn) :
                                        $own_Title = $rowOwn->own_Title;
                                        $own_Code = $rowOwn->own_Code;
                                        $own_Name = $rowOwn->own_Name;
                                        ?>
                                        <option value="<?php echo $own_Code; ?>" <?php if($own_Code == $REC_OWNER) { ?>selected <?php } ?>> <?php echo $own_Name; if($own_Title != '') { echo", $own_Title"; } ?> </option>
                                        <?php
                                     endforeach;
                                 }
                            ?>
                        </select>
					</td>
              	</tr>
                <tr>
                  	<td align="left" nowrap>PQ Est. Date</td>
               	  	<td align="left">:</td>
                  	<td colspan="4" align="left" style="vertical-align:top" >
                    	<div class="input-group date">
                            <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>&nbsp;</div>
                            <input type="hidden" name="REC_DATE" class="form-control pull-left" value="<?php echo $REC_DATE; ?>" style="width:150px">
                            <input type="text" name="REC_PQ_DATE" class="form-control pull-left" id="datepicker1" value="<?php echo $REC_DATE; ?>" style="width:150px">
                            </div>
					</td>
              	</tr>
                <tr>
                    <td align="left" nowrap> Tender Est. Date </td>
                  	<td align="left">:</td>
                    <td colspan="4" align="left">
                          <div class="input-group date">
                            <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>&nbsp;</div><input type="text" name="REC_TEND_DATE" class="form-control pull-left" id="datepicker2" value="<?php echo $REC_TEND_DATE; ?>" style="width:150px"></div></td>
              	</tr>
                <tr>
                    <td align="left" nowrap>Consultant</td>
                    <td align="left">:</td>
                    <td colspan="4" align="left"><input type="text" class="form-control" style="max-width:350px" name="REC_CONSULT" id="REC_CONSULT" value="<?php echo $REC_CONSULT; ?>" size="30" ></td>
              	</tr>
                <tr>
                    <td align="left" nowrap>Location</td>
                    <td align="left">:</td>
                    <td colspan="4" align="left"><input type="text" class="form-control" name="REC_LOCATION" id="REC_LOCATION" value="<?php echo $REC_LOCATION; ?>" style="max-width:200px;text-align:left" onChange="checkdecimal();" ></td>
              	</tr>
                <tr style="height:30px">
                  	<td align="left" nowrap>Project Value</td>
                  	<td align="left">:</td>
                  	<td colspan="4" align="left" style="vertical-align:middle" >
                        <input type="radio" name="REC_PRJTYPE" id="REC_PRJTYPE1" value="1" class="minimal" <?php if($REC_PRJTYPE == 1) { ?> checked <?php } ?>>
                        &nbsp;&nbsp;Kecil&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio" name="REC_PRJTYPE" id="REC_PRJTYPE2" value="2" class="minimal" <?php if($REC_PRJTYPE == 2) { ?> checked <?php } ?>>
                        &nbsp;&nbsp;Sedang&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio" name="REC_PRJTYPE" id="REC_PRJTYPE3" value="3" class="minimal" <?php if($REC_PRJTYPE == 3) { ?> checked <?php } ?>>
                        &nbsp;&nbsp;Besar
                	</td>
                </tr>
                <tr style="height:30px">
                  	<td align="left" nowrap>Funds Source</td>
                  	<td align="left">:</td>
                  	<td colspan="4" align="left" style="vertical-align:middle" >
                        <input type="radio" name="REC_FUNDSRC" id="REC_FUNDSRC1" value="1" class="minimal" <?php if($REC_FUNDSRC == 1) { ?> checked <?php } ?>>
                        &nbsp;&nbsp;Tersedia (aman)&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio" name="REC_FUNDSRC" id="REC_FUNDSRC2" value="2" class="minimal" <?php if($REC_FUNDSRC == 2) { ?> checked <?php } ?>>
                        &nbsp;&nbsp;Bertahap&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio" name="REC_FUNDSRC" id="REC_FUNDSRC3" value="3" class="minimal" <?php if($REC_FUNDSRC == 3) { ?> checked <?php } ?>>
                        &nbsp;&nbsp;Meragukan
                	</td>
                </tr>
                <tr style="height:30px">
                  	<td align="left" nowrap>Turn Over</td>
                  	<td align="left">:</td>
                  	<td colspan="4" align="left" style="vertical-align:middle" >
                        <input type="radio" name="REC_TURNOVER" id="REC_TURNOVER1" value="1" class="minimal" <?php if($REC_TURNOVER == 1) { ?> checked <?php } ?>>
                        &nbsp;&nbsp;Terpenuhi&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio" name="REC_TURNOVER" id="REC_TURNOVER2" value="2" class="minimal" <?php if($REC_TURNOVER == 2) { ?> checked <?php } ?>>
                        &nbsp;&nbsp;Tidak Terpenuhi&nbsp;&nbsp;&nbsp;&nbsp;
                	</td>
                </tr>
                <tr style="height:30px">
                  	<td align="left" nowrap>Experience</td>
                  	<td align="left">:</td>
                  	<td colspan="4" align="left" style="vertical-align:middle" >
                        <input type="radio" name="REC_EXP" id="REC_EXP1" value="1" class="minimal" <?php if($REC_EXP == 1) { ?> checked <?php } ?>>
                        &nbsp;&nbsp;Terpenuhi&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio" name="REC_EXP" id="REC_EXP2" value="2" class="minimal" <?php if($REC_EXP == 2) { ?> checked <?php } ?>>
                        &nbsp;&nbsp;Tidak Terpenuhi&nbsp;&nbsp;&nbsp;&nbsp;
                	</td>
                </tr>
                <tr style="height:30px">
                  	<td align="left" nowrap>Basic Capability</td>
                  	<td align="left">:</td>
                  	<td colspan="4" align="left" style="vertical-align:middle" >
                        <input type="radio" name="REC_BASCAPAB" id="REC_BASCAPAB1" value="1" class="minimal" <?php if($REC_BASCAPAB == 1) { ?> checked <?php } ?>>
                        &nbsp;&nbsp;Terpenuhi&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio" name="REC_BASCAPAB" id="REC_BASCAPAB2" value="2" class="minimal" <?php if($REC_BASCAPAB == 2) { ?> checked <?php } ?>>
                        &nbsp;&nbsp;Tidak Terpenuhi&nbsp;&nbsp;&nbsp;&nbsp;
                	</td>
                </tr>
                <tr style="height:30px">
                  	<td align="left" nowrap>Finance Capability</td>
                  	<td align="left">:</td>
                  	<td colspan="4" align="left" style="vertical-align:middle" >
                        <input type="radio" name="REC_FINCAPAB" id="REC_FINCAPAB1" value="1" class="minimal" <?php if($REC_FINCAPAB == 1) { ?> checked <?php } ?>>
                        &nbsp;&nbsp;Terpenuhi&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio" name="REC_FINCAPAB" id="REC_FINCAPAB2" value="2" class="minimal" <?php if($REC_FINCAPAB == 2) { ?> checked <?php } ?>>
                        &nbsp;&nbsp;Tidak Terpenuhi&nbsp;&nbsp;&nbsp;&nbsp;
                	</td>
                </tr>
                <tr style="height:30px">
                  	<td align="left" nowrap>Dificulty Level</td>
                  	<td align="left">:</td>
                  	<td colspan="4" align="left" style="vertical-align:middle" >
                        <input type="radio" name="REC_DIFICLEV" id="REC_DIFICLEV1" value="1" class="minimal" <?php if($REC_DIFICLEV == 1) { ?> checked <?php } ?>>
                        &nbsp;&nbsp;Mudah/Typical&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio" name="REC_DIFICLEV" id="REC_DIFICLEV2" value="2" class="minimal" <?php if($REC_DIFICLEV == 2) { ?> checked <?php } ?>>
                        &nbsp;Sedang / Normal&nbsp;&nbsp;&nbsp;
                        <input type="radio" name="REC_DIFICLEV" id="REC_DIFICLEV3" value="3" class="minimal" <?php if($REC_DIFICLEV == 3) { ?> checked <?php } ?>>
                        &nbsp;&nbsp;Rumit / Spesifik
                	</td>
                </tr>
                <tr style="height:30px">
                  	<td align="left" nowrap>Project Field</td>
                  	<td align="left">:</td>
                  	<td colspan="4" align="left" style="vertical-align:middle" >
                        <input type="radio" name="REC_PRJFIELD" id="REC_PRJFIELD1" value="1" class="minimal" <?php if($REC_PRJFIELD == 1) { ?> checked <?php } ?>>
                        &nbsp;&nbsp;Mudah/Terjangkau&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio" name="REC_PRJFIELD" id="REC_PRJFIELD2" value="2" class="minimal" <?php if($REC_PRJFIELD == 2) { ?> checked <?php } ?>>
                        &nbsp;&nbsp; Sedang / Normal&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio" name="REC_PRJFIELD" id="REC_PRJFIELD3" value="3" class="minimal" <?php if($REC_PRJFIELD == 3) { ?> checked <?php } ?>>
                        &nbsp;&nbsp;Sulit / Rawan
                	</td>
                </tr>
                <tr style="height:30px">
                  	<td align="left" nowrap>Material Resource</td>
                  	<td align="left">:</td>
                  	<td colspan="4" align="left" style="vertical-align:middle" >
                        <input type="radio" name="REC_MTRSRC" id="REC_MTRSRC1" value="1" class="minimal" <?php if($REC_MTRSRC == 1) { ?> checked <?php } ?>>
                        &nbsp;&nbsp;Mudah Didapat&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio" name="REC_MTRSRC" id="REC_MTRSRC2" value="2" class="minimal" <?php if($REC_MTRSRC == 2) { ?> checked <?php } ?>>
                        &nbsp;&nbsp;Sedang / Normal&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio" name="REC_MTRSRC" id="REC_MTRSRC3" value="3" class="minimal" <?php if($REC_MTRSRC == 3) { ?> checked <?php } ?>>
                        &nbsp;&nbsp;Sulit Didapat
                	</td>
                </tr>
                <tr style="height:30px">
                  	<td align="left" nowrap>Time Implementation</td>
                  	<td align="left">:</td>
                  	<td colspan="4" align="left" style="vertical-align:middle" >
                        <input type="radio" name="REC_TIMEXEC" id="REC_TIMEXEC1" value="1" class="minimal" <?php if($REC_TIMEXEC == 1) { ?> checked <?php } ?>>
                        &nbsp;&nbsp;Longgar&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio" name="REC_TIMEXEC" id="REC_TIMEXEC2" value="2" class="minimal" <?php if($REC_TIMEXEC == 2) { ?> checked <?php } ?>>
                        &nbsp;&nbsp;Sedang / Normal&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio" name="REC_TIMEXEC" id="REC_TIMEXEC3" value="3" class="minimal" <?php if($REC_TIMEXEC == 3) { ?> checked <?php } ?>>
                        &nbsp;&nbsp;Ketat
                	</td>
                </tr>
                <tr style="height:30px">
                  	<td align="left" nowrap>PQ Est. Time</td>
                  	<td align="left">:</td>
                  	<td colspan="4" align="left" style="vertical-align:middle" >
                        <input type="radio" name="REC_PQ_ESTIME" id="REC_PQ_ESTIME1" value="1" class="minimal" <?php if($REC_PQ_ESTIME == 1) { ?> checked <?php } ?>>
                        &nbsp;&nbsp;Cukup Waktu&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio" name="REC_PQ_ESTIME" id="REC_PQ_ESTIME2" value="2" class="minimal" <?php if($REC_PQ_ESTIME == 2) { ?> checked <?php } ?>>
                        &nbsp;&nbsp;Sedang / Normal&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio" name="REC_PQ_ESTIME" id="REC_PQ_ESTIME3" value="3" class="minimal" <?php if($REC_PQ_ESTIME == 3) { ?> checked <?php } ?>>
                        &nbsp;&nbsp;Mendesak
                	</td>
                </tr>
                <tr style="height:30px">
                  	<td align="left" nowrap>Tender Ets. Time</td>
                  	<td align="left">:</td>
                  	<td colspan="4" align="left" style="vertical-align:middle" >
                        <input type="radio" name="REC_TEND_ESTIME" id="REC_TEND_ESTIME1" value="1" class="minimal" <?php if($REC_TEND_ESTIME == 1) { ?> checked <?php } ?>>
                        &nbsp;&nbsp;Cukup Waktu&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio" name="REC_TEND_ESTIME" id="REC_TEND_ESTIME2" value="2" class="minimal" <?php if($REC_TEND_ESTIME == 2) { ?> checked <?php } ?>>
                        &nbsp;&nbsp;Sedang / Normal&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio" name="REC_TEND_ESTIME" id="REC_TEND_ESTIME3" value="3" class="minimal" <?php if($REC_TEND_ESTIME == 3) { ?> checked <?php } ?>>
                        &nbsp;&nbsp;Mendesak
                	</td>
                </tr>
                <tr style="height:30px">
                  	<td align="left" nowrap>Competitor</td>
                  	<td align="left">:</td>
                  	<td colspan="4" align="left" style="vertical-align:middle" >
                        <input type="radio" name="REC_BIDDER" id="REC_BIDDER1" value="1" class="minimal" <?php if($REC_BIDDER == 1) { ?> checked <?php } ?>>
                        &nbsp;&nbsp;Ringan&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio" name="REC_BIDDER" id="REC_BIDDER2" value="2" class="minimal" <?php if($REC_BIDDER == 2) { ?> checked <?php } ?>>
                        &nbsp;&nbsp;Sedang / Normal
                        <input type="radio" name="REC_BIDDER" id="REC_BIDDER3" value="3" class="minimal" <?php if($REC_BIDDER == 3) { ?> checked <?php } ?>>
                        &nbsp;&nbsp;Berat
                	</td>
                </tr>
                <tr>
                    <td align="left">Competitor Qty</td>
                    <td align="left">:</td>
                    <td colspan="4" align="left"><input type="text" class="form-control" name="REC_BIDDER_QTY" id="REC_BIDDER_QTY" value="<?php echo $REC_BIDDER_QTY; ?>" style="max-width:100px;text-align:right" onKeyPress="return isIntOnlyNew(event);" ></td>
             	</tr>
                <tr style="height:30px">
                  	<td align="left" nowrap>Eskalation Est.</td>
                  	<td align="left">:</td>
                  	<td colspan="4" align="left" style="vertical-align:middle" >
                        <input type="radio" name="REC_ESKAL_EST" id="REC_ESKAL_EST1" value="1" class="minimal" <?php if($REC_ESKAL_EST == 1) { ?> checked <?php } ?>>
                        &nbsp;&nbsp;Ada&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio" name="REC_ESKAL_EST" id="REC_ESKAL_EST2" value="2" class="minimal" <?php if($REC_ESKAL_EST == 2) { ?> checked <?php } ?>>
                        &nbsp;&nbsp;Tidak ada
                	</td>
                </tr>
                <tr style="height:30px">
                  	<td align="left" nowrap>Conclusion</td>
                  	<td align="left">:</td>
                  	<td colspan="4" align="left" style="vertical-align:middle" >
                        <input type="radio" name="REC_CONCLUTION" id="REC_CONCLUTION1" value="1" class="minimal" <?php if($REC_CONCLUTION == 1) { ?> checked <?php } ?>>
                        &nbsp;&nbsp;Ikut&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio" name="REC_CONCLUTION" id="REC_CONCLUTION2" value="2" class="minimal" <?php if($REC_CONCLUTION == 2) { ?> checked <?php } ?>>
                        &nbsp;&nbsp;Tidak Ikut
                	</td>
                </tr>
                <tr>
                    <td align="left" style="vertical-align:top">Notes</td>
                    <td align="left" style="vertical-align:top">:</td>
                    <td colspan="4" align="left" style="font-style:italic"><textarea class="form-control" name="REC_NOTES"  id="REC_NOTES" style="max-width:500px;height:70px"><?php echo $REC_NOTES; ?></textarea></td>
              	</tr>
                <tr style="height:30px">
                  	<td align="left" nowrap>Approval Status</td>
                  	<td align="left">:</td>
                  	<td colspan="4" align="left" style="vertical-align:middle" >
                        <input type="radio" name="REC_STAT" id="REC_STAT1" value="1" class="minimal" <?php if($REC_STAT == 1) { ?> checked <?php } ?>>
                        &nbsp;&nbsp;New&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio" name="REC_STAT" id="REC_STAT2" value="2" class="minimal" <?php if($REC_STAT == 2) { ?> checked <?php } ?>>
                        &nbsp;&nbsp;Confirm&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio" name="REC_STAT" id="REC_STAT3" value="3" class="minimal" <?php if($REC_STAT == 3) { ?> checked <?php } ?>>
                        &nbsp;&nbsp;Approve
                	</td>
                </tr>
                <tr>
                    <td align="left">&nbsp;</td>
                    <td align="left">&nbsp;</td>
                    <td align="left">&nbsp;</td>
                    <td align="left">&nbsp;</td>
                    <td align="left">&nbsp;</td>
                    <td align="left">&nbsp;</td>
                </tr>
                <tr>
                    <td align="left">&nbsp;</td>
                    <td align="left">&nbsp;</td>
                    <td align="left">
                    <a class="btn btn-app" onClick="saveRecomend()">
                    	<i class="fa fa-save"></i> Save
                    </a>
                    <a class="btn btn-app" onClick="cancelRecomend()">
                    	<i class="fa fa-repeat"></i> Cancel
                    </a>
                    <input type="submit" class="btn btn-primary" name="submit" id="submit" value="<?php if($task=='add') echo 'save'; else echo 'update';?>" align="left" style="display:none" />
                    </td>
                    <td align="left">&nbsp;</td>
                    <td align="left">&nbsp;</td>
                    <td align="left" nowrap style="font-weight:bold; font-style:italic; text-align:right">&nbsp;</td>
                </tr>
                <tr>
                  <td align="left">&nbsp;</td>
                  <td align="left">&nbsp;</td>
                  <td align="left">&nbsp;</td>
                  <td align="left">&nbsp;</td>
                  <td align="left">&nbsp;</td>
                  <td align="left">&nbsp;</td>
                </tr>
          </table>
      </form>
    </div>
    <form name="frmCancel" id="frmCancel" action="<?php echo $cancel_url; ?>" method=POST style="display:none">
        <input type="submit" class="btn btn-primary" name="subCancel" id="subCancel" />
    </form>
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
	function saveRecomend()
	{
		document.frm.submit.click();
	}
	
	function cancelRecomend()
	{
		document.frmCancel.subCancel.click();
	}
	
	function validateInData()
	{		
		REC_NO = document.getElementById('REC_NO').value;
		if(REC_NO == '')
		{
			alert('Recomendation No. can not be empty');
			document.getElementById('REC_NO').focus();
			return false;
		}
		
		nextornot = document.getElementById('CheckThe_Code').value;
		
		if(nextornot > 0)
		{
			alert('Recomendation No. Already Exist. Please Change.');
			document.getElementById('REC_NO').value = '';
			document.getElementById('REC_NO').focus();
			return false;
		}
		
		REC_PRJNAME = document.getElementById('REC_PRJNAME').value;
		if(REC_PRJNAME == '')
		{
			alert('Project Name can not be empty');
			document.getElementById('REC_PRJNAME').focus();
			return false;
		}
		
		REC_OWNER = document.getElementById('REC_OWNER').value;
		if(REC_OWNER == 'none')
		{
			alert('Please chose one of Owner Project.');
			document.getElementById('REC_OWNER').focus();
			return false;
		}
		
		REC_CONSULT = document.getElementById('REC_CONSULT').value;
		if(REC_CONSULT == '')
		{
			alert('Please input Project Consultant.');
			document.getElementById('REC_CONSULT').focus();
			return false;
		}
		
		REC_LOCATION = document.getElementById('REC_LOCATION').value;
		if(REC_LOCATION == '')
		{
			alert('Please input Project Location.');
			document.getElementById('REC_LOCATION').focus();
			return false;
		}
		
		isChecked1 = 0; isChecked2 = 0; isChecked3 = 0;
		REC_PRJTYPE1 = document.getElementById('REC_PRJTYPE1').checked;
			if(REC_PRJTYPE1 == false) isChecked1 = 0; else isChecked1 = 1;
		REC_PRJTYPE2 = document.getElementById('REC_PRJTYPE2').checked;
			if(REC_PRJTYPE2 == false) isChecked2 = 0; else isChecked2 = 1;
		REC_PRJTYPE3 = document.getElementById('REC_PRJTYPE3').checked;
			if(REC_PRJTYPE3 == false) isChecked3 = 0; else isChecked3 = 1;
		totREC_PRJTYPE	= isChecked1 + isChecked2 + isChecked3;
		if(totREC_PRJTYPE == 0)
		{
			alert('Please check of Project Type.');
			document.getElementById('REC_PRJTYPE1').checked;
			return false;
		}
		
		isChecked1 = 0; isChecked2 = 0; isChecked3 = 0;
		REC_FUNDSRC1 = document.getElementById('REC_FUNDSRC1').checked;
			if(REC_FUNDSRC1 == false) isChecked1 = 0; else isChecked1 = 1;
		REC_FUNDSRC2 = document.getElementById('REC_FUNDSRC2').checked;
			if(REC_FUNDSRC2 == false) isChecked2 = 0; else isChecked2 = 1;
		REC_FUNDSRC3 = document.getElementById('REC_FUNDSRC3').checked;
			if(REC_FUNDSRC3 == false) isChecked3 = 0; else isChecked3 = 1;
		totREC_FUNDSRC	= isChecked1 + isChecked2 + isChecked3;
		if(totREC_FUNDSRC == 0)
		{
			alert('Please check of Funds Source.');
			//document.getElementById('REC_FUNDSRC1').checked;
			return false;
		}
		
		isChecked1 = 0; isChecked2 = 0;
		REC_TURNOVER1 = document.getElementById('REC_TURNOVER1').checked;
			if(REC_TURNOVER1 == false) isChecked1 = 0; else isChecked1 = 1;
		REC_TURNOVER2 = document.getElementById('REC_TURNOVER2').checked;
			if(REC_TURNOVER2 == false) isChecked2 = 0; else isChecked2 = 1;
		totREC_TURNOVER	= isChecked1 + isChecked2;
		if(totREC_TURNOVER == 0)
		{
			alert('Please check of Project Turn Over.');
			//document.getElementById('REC_TURNOVE1').checked;
			return false;
		}
		
		isChecked1 = 0; isChecked2 = 0;
		REC_EXP1 = document.getElementById('REC_EXP1').checked;
			if(REC_EXP1 == false) isChecked1 = 0; else isChecked1 = 1;
		REC_EXP2 = document.getElementById('REC_EXP2').checked;
			if(REC_EXP2 == false) isChecked2 = 0; else isChecked2 = 1;
		totREC_EXP	= isChecked1 + isChecked2;
		if(totREC_EXP == 0)
		{
			alert('Please check of Project Experience.');
			//document.getElementById('REC_EXP1').checked;
			return false;
		}
		
		isChecked1 = 0; isChecked2 = 0;
		REC_BASCAPAB1 = document.getElementById('REC_BASCAPAB1').checked;
			if(REC_BASCAPAB1 == false) isChecked1 = 0; else isChecked1 = 1;
		REC_BASCAPAB2 = document.getElementById('REC_BASCAPAB2').checked;
			if(REC_BASCAPAB2 == false) isChecked2 = 0; else isChecked2 = 1;
		totREC_BASCAPAB	= isChecked1 + isChecked2;
		if(totREC_BASCAPAB == 0)
		{
			alert('Please check of Basic Capability.');
			//document.getElementById('REC_BASCAPAB1').checked;
			return false;
		}
		
		isChecked1 = 0; isChecked2 = 0;
		REC_FINCAPAB1 = document.getElementById('REC_FINCAPAB1').checked;
			if(REC_FINCAPAB1 == false) isChecked1 = 0; else isChecked1 = 1;
		REC_FINCAPAB2 = document.getElementById('REC_FINCAPAB2').checked;
			if(REC_FINCAPAB2 == false) isChecked2 = 0; else isChecked2 = 1;
		totREC_FINCAPAB	= isChecked1 + isChecked2;
		if(totREC_FINCAPAB == 0)
		{
			alert('Please check of Financial Capability.');
			//document.getElementById('REC_FINCAPAB1').checked;
			return false;
		}
		
		isChecked1 = 0; isChecked2 = 0; isChecked3 = 0;
		REC_DIFICLEV1 = document.getElementById('REC_DIFICLEV1').checked;
			if(REC_DIFICLEV1 == false) isChecked1 = 0; else isChecked1 = 1;
		REC_DIFICLEV2 = document.getElementById('REC_DIFICLEV2').checked;
			if(REC_DIFICLEV2 == false) isChecked2 = 0; else isChecked2 = 1;
		REC_DIFICLEV3 = document.getElementById('REC_DIFICLEV3').checked;
			if(REC_DIFICLEV3 == false) isChecke3 = 0; else isChecked3 = 1;
		totREC_DIFICLEV	= isChecked1 + isChecked2 + isChecked3;
		if(totREC_DIFICLEV == 0)
		{
			alert('Please check of Dificulty Level.');
			//document.getElementById('REC_DIFICLEV1').checked;
			return false;
		}
		
		isChecked1 = 0; isChecked2 = 0; isChecked3 = 0;
		REC_PRJFIELD1 = document.getElementById('REC_PRJFIELD1').checked;
			if(REC_PRJFIELD1 == false) isChecked1 = 0; else isChecked1 = 1;
		REC_PRJFIELD2 = document.getElementById('REC_PRJFIELD2').checked;
			if(REC_PRJFIELD2 == false) isChecked2 = 0; else isChecked2 = 1;
		REC_PRJFIELD3 = document.getElementById('REC_PRJFIELD3').checked;
			if(REC_PRJFIELD3 == false) isChecke3 = 0; else isChecked3 = 1;
		totREC_PRJFIELD	= isChecked1 + isChecked2 + isChecked3;
		if(totREC_PRJFIELD == 0)
		{
			alert('Please check of Project Field.');
			//document.getElementById('REC_PRJFIELD1').checked;
			return false;
		}
		
		isChecked1 = 0; isChecked2 = 0; isChecked3 = 0;
		REC_MTRSRC1 = document.getElementById('REC_MTRSRC1').checked;
			if(REC_MTRSRC1 == false) isChecked1 = 0; else isChecked1 = 1;
		REC_MTRSRC2 = document.getElementById('REC_MTRSRC2').checked;
			if(REC_MTRSRC2 == false) isChecked2 = 0; else isChecked2 = 1;
		REC_MTRSRC3 = document.getElementById('REC_MTRSRC3').checked;
			if(REC_MTRSRC3 == false) isChecke3 = 0; else isChecked3 = 1;
		totREC_MTRSRC	= isChecked1 + isChecked2 + isChecked3;
		if(totREC_MTRSRC == 0)
		{
			alert('Please check of Project Material Resources.');
			//document.getElementById('REC_MTRSRC1').checked;
			return false;
		}
		
		isChecked1 = 0; isChecked2 = 0; isChecked3 = 0;
		REC_TIMEXEC1 = document.getElementById('REC_TIMEXEC1').checked;
			if(REC_TIMEXEC1 == false) isChecked1 = 0; else isChecked1 = 1;
		REC_TIMEXEC2 = document.getElementById('REC_TIMEXEC2').checked;
			if(REC_TIMEXEC2 == false) isChecked2 = 0; else isChecked2 = 1;
		REC_TIMEXEC3 = document.getElementById('REC_TIMEXEC3').checked;
			if(REC_TIMEXEC3 == false) isChecke3 = 0; else isChecked3 = 1;
		totREC_TIMEXEC	= isChecked1 + isChecked2 + isChecked3;
		if(totREC_TIMEXEC == 0)
		{
			alert('Please check of Project Time Implementation.');
			//document.getElementById('REC_TIMEXEC1').checked;
			return false;
		}
		
		isChecked1 = 0; isChecked2 = 0; isChecked3 = 0;
		REC_PQ_ESTIME1 = document.getElementById('REC_PQ_ESTIME1').checked;
			if(REC_PQ_ESTIME1 == false) isChecked1 = 0; else isChecked1 = 1;
		REC_PQ_ESTIME2 = document.getElementById('REC_PQ_ESTIME2').checked;
			if(REC_PQ_ESTIME2 == false) isChecked2 = 0; else isChecked2 = 1;
		REC_PQ_ESTIME3 = document.getElementById('REC_PQ_ESTIME3').checked;
			if(REC_PQ_ESTIME3 == false) isChecke3 = 0; else isChecked3 = 1;
		totREC_PQ_ESTIME	= isChecked1 + isChecked2 + isChecked3;
		if(totREC_PQ_ESTIME == 0)
		{
			alert('Please check of Project PQ Estimation.');
			//document.getElementById('REC_PQ_ESTIME1').checked;
			return false;
		}
		
		isChecked1 = 0; isChecked2 = 0; isChecked3 = 0;
		REC_TEND_ESTIME1 = document.getElementById('REC_TEND_ESTIME1').checked;
			if(REC_TEND_ESTIME1 == false) isChecked1 = 0; else isChecked1 = 1;
		REC_TEND_ESTIME2 = document.getElementById('REC_TEND_ESTIME2').checked;
			if(REC_TEND_ESTIME2 == false) isChecked2 = 0; else isChecked2 = 1;
		REC_TEND_ESTIME3 = document.getElementById('REC_TEND_ESTIME3').checked;
			if(REC_TEND_ESTIME3 == false) isChecke3 = 0; else isChecked3 = 1;
		totREC_TEND_ESTIME	= isChecked1 + isChecked2 + isChecked3;
		if(totREC_TEND_ESTIME == 0)
		{
			alert('Please check of Project Tender Estimation.');
			//document.getElementById('REC_TEND_ESTIME1').checked;
			return false;
		}
		
		isChecked1 = 0; isChecked2 = 0; isChecked3 = 0;
		REC_BIDDER1 = document.getElementById('REC_BIDDER1').checked;
			if(REC_BIDDER1 == false) isChecked1 = 0; else isChecked1 = 1;
		REC_BIDDER2 = document.getElementById('REC_BIDDER2').checked;
			if(REC_BIDDER2 == false) isChecked2 = 0; else isChecked2 = 1;
		REC_BIDDER3 = document.getElementById('REC_BIDDER3').checked;
			if(REC_BIDDER3 == false) isChecke3 = 0; else isChecked3 = 1;
		totREC_BIDDER	= isChecked1 + isChecked2 + isChecked3;
		if(totREC_BIDDER == 0)
		{
			alert('Please check of Project Competitor.');
			//document.getElementById('REC_BIDDER1').checked;
			return false;
		}
		
		REC_BIDDER_QTY = document.getElementById('REC_BIDDER_QTY').value;
		if(REC_BIDDER_QTY == 0)
		{
			alert('Please input of Project Competitor Qty.');
			document.getElementById('REC_BIDDER_QTY').focus();
			return false;
		}
		
		isChecked1 = 0; isChecked2 = 0;
		REC_ESKAL_EST1 = document.getElementById('REC_ESKAL_EST1').checked;
			if(REC_ESKAL_EST1 == false) isChecked1 = 0; else isChecked1 = 1;
		REC_ESKAL_EST2 = document.getElementById('REC_ESKAL_EST2').checked;
			if(REC_ESKAL_EST2 == false) isChecked2 = 0; else isChecked2 = 1;
		totREC_ESKAL_EST	= isChecked1 + isChecked2;
		if(totREC_ESKAL_EST == 0)
		{
			alert('Please check of Project Eskalation Time.');
			//document.getElementById('REC_ESKAL_EST1').checked;
			return false;
		}
		
		isChecked1 = 0; isChecked2 = 0;
		REC_CONCLUTION1 = document.getElementById('REC_CONCLUTION1').checked;
			if(REC_CONCLUTION1 == false) isChecked1 = 0; else isChecked1 = 1;
		REC_CONCLUTION2 = document.getElementById('REC_CONCLUTION2').checked;
			if(REC_CONCLUTION2 == false) isChecked2 = 0; else isChecked2 = 1;
		totREC_CONCLUTION	= isChecked1 + isChecked2;
		if(totREC_CONCLUTION == 0)
		{
			alert('Please check of Project Conclusion.');
			//document.getElementById('REC_CONCLUTION1').checked;
			return false;
		}
		
		REC_NOTES = document.getElementById('REC_NOTES').value;
		if(REC_NOTES == '')
		{
			alert('Please input of Project Notes.');
			document.getElementById('REC_NOTES').focus();
			return false;
		}
		
		isChecked1 = 0; isChecked2 = 0; isChecked3 = 0;
		REC_STAT1 = document.getElementById('REC_STAT1').checked;
			if(REC_STAT1 == false) isChecked1 = 0; else isChecked1 = 1;
		REC_STAT2 = document.getElementById('REC_STAT2').checked;
			if(REC_STAT2 == false) isChecked2 = 0; else isChecked2 = 1;
		REC_STAT3 = document.getElementById('REC_STAT3').checked;
			if(REC_STAT3 == false) isChecke3 = 0; else isChecked3 = 1;
		totREC_STAT	= isChecked1 + isChecked2 + isChecked3;
		if(totREC_STAT == 0)
		{
			alert('Please check of Project Status.');
			//document.getElementById('REC_STAT1').checked;
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