<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 15 Agustus 2017
 * File Name	= stock_opn_form.php
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

$sql = "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$result = $this->db->query($sql)->result();
foreach($result as $row) :
	$PRJNAME = $row ->PRJNAME;
endforeach;

$currentRow = 0;
if($task == 'add')
{	
	$SOPNH_CODE		= '';
	$SOPNH_MCODE	= '';
	$SOPNH_DATE		= date('m/d/Y');
	$SOPNH_PRJCODE	= $PRJCODE;
	$SOPNH_PERIODE	= date('m/d/Y');
	$SOPNH_WH		= '';
	$SOPNH_NOTES	= '';
	$SOPNH_STAT		= 1;
	$SOPNH_REVMEMO	= '';
	$isApproved		= 0;
	
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
	$yearC 	= (int)$Pattern_YearAktive;
	$year 	= substr($Pattern_YearAktive,2,2);
	$month 	= (int)$Pattern_MonthAktive;
	$date 	= (int)$Pattern_DateAktive;

	$this->db->where('Patt_Year', $year);
	//$this->db->where('Patt_Month', $month);
	//$this->db->where('Patt_Date', $date);
	$myCount = $this->db->count_all('tbl_spp_header');
	
	$sql = "SELECT MAX(Patt_Number) as maxNumber FROM tbl_sopn_header
			WHERE Patt_Year = $yearC AND SOPNH_PRJCODE = '$PRJCODE'";
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
	
	$sql = "SELECT PRJCODE, PRJNAME FROM tbl_project
			WHERE PRJCODE = '$PRJCODE'";
	$resultProj = $this->db->query($sql)->result();
	foreach($resultProj as $row) :
		$PRJNAME = $row->PRJNAME;
	endforeach;
	$DocNumber 		= "$Pattern_Code$PRJCODE$groupPattern-$lastPatternNumb";
	
	$Patt_Year 		= date('Y');
	$Patt_Month		= date('m');
	$Patt_Date		= date('d');
	$Patt_Number	= $lastPatternNumb1;
}
else
{
	$SOPNH_CODE 	= $default['SOPNH_CODE'];
	$DocNumber		= $default['SOPNH_CODE'];
	$SOPNH_MCODE	= $default['SOPNH_MCODE'];
	$SOPNH_DATEX	= $default['SOPNH_DATE'];
	$SOPNH_DATE		= date('m/d/Y',strtotime($SOPNH_DATEX));
	$SOPNH_PRJCODE	= $default['SOPNH_PRJCODE'];
	$PRJCODE 		= $default['SOPNH_PRJCODE'];
	$SOPNH_PERIODEX = $default['SOPNH_PERIODE'];	
	$SOPNH_PERIODE	= date('m/d/Y',strtotime($SOPNH_PERIODEX));
	$SOPNH_WH 		= $default['SOPNH_WH'];
	$SOPNH_NOTES 	= $default['SOPNH_NOTES'];
	$SOPNH_STAT 	= $default['SOPNH_STAT'];
	$SOPNH_REVMEMO 	= $default['SOPNH_REVMEMO'];
	$Patt_Year 		= $default['Patt_Year'];
	$Patt_Month 	= $default['Patt_Month'];
	$Patt_Date 		= $default['Patt_Date'];
	$Patt_Number 	= $default['Patt_Number'];
}

/*$AUTH_STOPN	= 0;
$sql 		= "SELECT AUTH_STOPN FROM tbl_employee_appauth WHERE AUTH_EMPID = '$DefEmp_ID'";
$resSTOPN 	= $this->db->query($sql)->result();
foreach($resSTOPN as $row) :
	$AUTH_STOPN = $row->AUTH_STOPN;
endforeach;*/

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
	$LangID 	= $this->session->userdata['LangID'];
	
	$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
	$resTransl		= $this->db->query($sqlTransl)->result();
	foreach($resTransl as $rowTransl) :
		$TranslCode	= $rowTransl->MLANG_CODE;
		$LangTransl	= $rowTransl->LangTransl;
		
		if($TranslCode == 'Add')$Add = $LangTransl;
		if($TranslCode == 'AddItem')$AddItem = $LangTransl;
		if($TranslCode == 'Save')$Save = $LangTransl;
		if($TranslCode == 'Update')$Update = $LangTransl;
		if($TranslCode == 'Back')$Back = $LangTransl;
		if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
		if($TranslCode == 'ItemName')$ItemName = $LangTransl;
		if($TranslCode == 'Unit')$Unit = $LangTransl;
		if($TranslCode == 'Price')$Price = $LangTransl;
		if($TranslCode == 'Receipt')$Receipt = $LangTransl;
		if($TranslCode == 'PrevMonth')$PrevMonth = $LangTransl;
		if($TranslCode == 'CurrentMonth')$CurrentMonth = $LangTransl;
		if($TranslCode == 'output')$output = $LangTransl;
		if($TranslCode == 'Remarks')$Remarks = $LangTransl;		
		if($TranslCode == 'InputNumber')$InputNumber = $LangTransl;
		if($TranslCode == 'ManualCode')$ManualCode = $LangTransl;
		if($TranslCode == 'Notes')$Notes = $LangTransl;
		if($TranslCode == 'Date')$Date = $LangTransl;
		if($TranslCode == 'Project')$Project = $LangTransl;
		if($TranslCode == 'Periode')$Periode = $LangTransl;
		if($TranslCode == 'Status')$Status = $LangTransl;
		if($TranslCode == 'AlertRevisionorReject')$AlertRevisionorReject = $LangTransl;
		if($TranslCode == 'Stock')$Stock = $LangTransl;
		

	endforeach;
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
        <div class="box-header with-border" style="display:none">
            <h3 class="box-title">Input Data</h3>
        </div>
        <br>
      	<form name="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return validateInData();">
       	  	<input type="hidden" name="SOPNH_STATX" id="SOPNH_STATX" value="<?php echo $SOPNH_STAT; ?>">
            <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
            <input type="Hidden" name="rowCount" id="rowCount" value="0">
          	<table width="100%" border="0" style="size:auto">
              <tr>
                  <td width="13%" align="left" class="style1" nowrap><?php echo $InputNumber ?></td>
                  <td width="1%" align="left" class="style1">:</td>
                  <td width="30%" align="left" class="style1"> <?php echo $DocNumber; ?>
                      <input type="hidden" class="form-control" style="max-width:350px;text-align:right" name="SOPNH_CODE" id="SOPNH_CODE" size="30" value="<?php echo $DocNumber; ?>" />
                      <input type="hidden" class="form-control" style="max-width:350px;text-align:right" name="SOPNH_PRJCODE" id="SOPNH_PRJCODE" size="30" value="<?php echo $PRJCODE; ?>" />
                      <input type="hidden" class="form-control" style="max-width:350px;text-align:right" name="Patt_Year" id="Patt_Year" size="30" value="<?php echo $Patt_Year; ?>" />
                  </td>
                  <td width="15%" align="left" class="style1"><?php echo $Date ?> </td>
                  <td width="1%" align="left" class="style1">:</td>
                  <td width="40%" align="left" class="style1">
                      <div class="input-group date">
                          <div class="input-group-addon">
                          <i class="fa fa-calendar"></i>&nbsp;</div>
                          <input type="text" name="SOPNH_DATE" class="form-control pull-left" id="datepicker" value="<?php echo $SOPNH_DATE; ?>" style="width:150px">
                      </div>
                      <input type="hidden" class="form-control" style="max-width:350px;text-align:right" id="Patt_Number" name="Patt_Number" size="20" value="<?php echo $Patt_Number; ?>" />
                  </td> <!-- DOCNUMBER AND TRXDATE -->
            </tr>
            <tr>
                  <td align="left" class="style1"><?php echo $ManualCode ?> </td>
                  <td align="left" class="style1">:</td>
                  <td align="left" class="style1"><input type="text" class="form-control" style="min-width:110px; max-width:100px; text-align:left" id="SOPNH_MCODE" name="SOPNH_MCODE" size="5" value="<?php echo $SOPNH_MCODE; ?>" /></td>
                  <td align="left" class="style1"><?php echo $Project ?> </td>
                  <td align="left" class="style1">:</td>
                  <td align="left" class="style1">
                      <select name="PRJCODE" id="PRJCODE" class="form-control" style="max-width:350px" onChange="chooseProject()" disabled>
                          <option value="none">--- None ---</option>
                          <?php echo $i = 0;
                            if($recCountPrj > 0)
                            {
                                foreach($vwProject as $row) :
                                    $PRJCODE1 	= $row->PRJCODE;
                                    $PRJNAME 	= $row->PRJNAME;
                                    ?>
                                <option value="<?php echo $PRJCODE1; ?>" <?php if($PRJCODE1 == $PRJCODE) { ?> selected <?php } ?>><?php echo "$PRJCODE - $PRJNAME"; ?></option>
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
                      <input type="hidden" class="form-control" style="max-width:350px;text-align:right" id="PRJCODE" name="PRJCODE" size="20" value="<?php echo $PRJCODE; ?>" />
                  </td>  <!-- SPPCODE AND PRJCODE -->
            </tr>
            <tr>
                  <td align="left" class="style1" valign="top"><?php echo $Notes ?> </td>
                  <td align="left" class="style1" valign="top">:</td>
                  <td class="style1" style="text-align:left">
                      <textarea name="SOPNH_NOTES" id="SOPNH_NOTES" class="form-control" style="max-width:350px;" cols="30"><?php echo $SOPNH_NOTES; ?></textarea>
                  </td>
                    <td class="style1" style="text-align:left; vertical-align:top"><?php echo $Periode ?> </td>
                    <td class="style1" style="text-align:left; vertical-align:top">:</td>
                    <td class="style1" style="text-align:left; vertical-align:top">
                      <div class="input-group date">
                          <div class="input-group-addon">
                          <i class="fa fa-calendar"></i>&nbsp;</div>
                          <input type="text" name="SOPNH_PERIODE" class="form-control pull-left" id="datepicker1" value="<?php echo $SOPNH_PERIODE; ?>" style="width:150px">
                      </div>
                  </td>
                  <!-- SOPNH_NOTES, SOPNH_PERIOD -->
            </tr>
            <tr>
                  <td colspan="3" align="left" valign="middle" class="style1"><hr></td>
                  <td align="left" class="style1"><?php echo $Status ?> </td>
                  <td align="left" class="style1">:</td>
                  <td align="left" class="style1">
                    <?php
						if($ISAPPROVE == 1)
						{
						?>
                        	<select name="SOPNH_STAT" id="SOPNH_STAT" class="form-control" style="max-width:100px" onChange="selStat(this.value)" >
                                <option value="1"<?php if($SOPNH_STAT == 1) { ?> selected <?php } ?>>New</option>
                                <option value="2"<?php if($SOPNH_STAT == 2) { ?> selected <?php } ?>>Confirm</option>
                                <option value="3"<?php if($SOPNH_STAT == 3) { ?> selected <?php } ?>>Approve</option>
                                <option value="4"<?php if($SOPNH_STAT == 4) { ?> selected <?php } ?>>Revise</option>
                                <option value="5"<?php if($SOPNH_STAT == 5) { ?> selected <?php } ?>>Reject</option>
                                <option value="6"<?php if($SOPNH_STAT == 6) { ?> selected <?php } ?>>Close</option>
							</select>
                    	<?php
						}
						else
						{
						?>
                        	<select name="SOPNH_STAT" id="SOPNH_STAT" class="form-control" style="max-width:100px" onChange="selStat(this.value)">
                                <option value="1"<?php if($SOPNH_STAT == 1) { ?> selected <?php } ?>>New</option>
                                <option value="2"<?php if($SOPNH_STAT == 2) { ?> selected <?php } ?>>Confirm</option>
                                <option value="6"<?php if($SOPNH_STAT == 6) { ?> selected <?php } ?> style="display:none">Close</option>
							</select>
                    	<?php
						}
					?>
                  </td> <!-- SOPNH_STAT -->
            </tr>
            <script>
				function selStat(thisValue)
				{
					var totrow 		= document.getElementById('totalrow').value;
					//alert(totrow)
					var SOPND_STAT	= document.getElementById('SOPNH_STAT').value;
					for(i=1;i<=totrow;i++)
					{
						document.getElementById('data'+i+'SOPND_STAT').value = SOPND_STAT;
					}
					
					if(thisValue == 4 || thisValue == 5)
					{
						document.getElementById('showMemo').style.display = '';
					}
					else
					{
						document.getElementById('showMemo').style.display = 'none';
					}
				}
			</script>
            <tr <?php if($SOPNH_STAT != 4 && $SOPNH_STAT != 5) { ?> style="display:none" <?php } ?>>
                <td align="left" class="style1" style="font-style:italic">&nbsp;</td>
                <td align="left" class="style1" style="font-style:italic">&nbsp;</td>
                <td align="left" class="style1" style="font-style:italic">
                </td>
                <td align="left" class="style1" style="font-style:italic; background:#CCCCCC"><?php echo $AlertRevisionorReject ?> </td>
                <td align="left" class="style1" style="font-style:italic; background:#CCCCCC">:</td>
                <td align="left" class="style1" style="font-style:italic; background:#CCCCCC"><?php echo $SOPNH_REVMEMO; ?></td> <!-- SPP MEMO REVISION -->
            </tr>
            <?php
                    $url_AddItem	= site_url('c_gl/profit_loss/popupallitem/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
                ?>
            <tr>
                <td colspan="6" align="left" class="style1" style="font-style:italic">                        
                  <script>
                        var url = "<?php echo $url_AddItem;?>";
                        function selectitem()
                        {
                            title = 'Select Item';
                            w = 1000;
                            h = 550;
                            //window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
                            var left = (screen.width/2)-(w/2);
                            var top = (screen.height/2)-(h/2);
                            return window.open(url, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
                        }
                    </script>
                  
                  <!--<a href="javascript:void(null);" onClick="selectitem();">
                  	<span class="label label-warning" style="font-size:12px">
                   		Add Item [+]
                    </span>
                  </a>-->
                  
    	            <button class="btn btn-success" type="button" onClick="selectitem();">
	                    <i class="cus-add-item-16x16"></i>&nbsp;&nbsp;<?php echo $AddItem; ?>
                    </button>
                <?php /*?>-- 
                    Delete Item [-]<?php */?></td> <!-- ADD ITEM -->
            </tr>
            <tr>
                <td colspan="6" align="left" class="style1">
                        <table width="100%" border="1" id="tbl" >
                            <tr style="background:#CCCCCC">
                                <th width="2%" height="25" rowspan="2" style="text-align:left">No.</th>
                                <th width="8%" rowspan="2" style="text-align:center"><?php echo $ItemCode ?> </th>
                                <th width="30%" rowspan="2" style="text-align:center"><?php echo $ItemName ?> </th>
                                <th rowspan="2" style="text-align:center"><?php echo $Unit ?> </th>
                                <th rowspan="2" style="text-align:center"><?php echo $Price ?> </th>
                                <th rowspan="2" style="text-align:center"><?php echo $Stock ?></th>
                                <th style="text-align:center; display:none">&nbsp;</th>
                              <th style="text-align:center"><?php echo $Receipt ?></th>
                                <th style="text-align:center; display:none">&nbsp;</th>
                              <th style="text-align:center"><?php echo $output ?></th>
                                <th width="13%" rowspan="2" style="text-align:center"><?php echo $Remarks ?> </th>
                            </tr>
                            <tr style="background:#CCCCCC">
                                <th style="text-align:center; display:none" nowrap><?php echo $PrevMonth ?> </th>
                                <th style="text-align:center;" nowrap><?php echo $CurrentMonth ?> </th>
                                <th style="text-align:center; display:none" nowrap><?php echo $PrevMonth ?>  </th>
                                <th style="text-align:center;" nowrap><?php echo $CurrentMonth ?> </th>
                            </tr>
                            <?php					
                                if($task == 'edit')
                                {
                                    $sqlDET		= "SELECT A.SOPNH_CODE, A.SOPND_DATE, A.SOPND_PRJCODE, A.ITEM_CODE, A.ITEM_NOTE, 
														A.ITEM_UNIT, A.ITEM_QTY, A.ITEM_PRICE, A.ITEM_IN_NOW, A.ITEM_IN_BEF0, A.ITEM_TYPE,
														A.ITEM_OUT_NOW, A.ITEM_OUT_BEF0, A.ITEM_REM_QTY, A.ITEM_REM_PRICE, A.SOPND_STAT,
                                                    	B.ITM_DESC as CSTDESC
                                                    FROM tbl_sopn_detail A
                                                        LEFT JOIN tbl_item B ON A.ITEM_CODE = B.ITM_CODE
														AND B.PRJCODE = '$SOPNH_PRJCODE'
                                                    WHERE SOPNH_CODE = '$SOPNH_CODE' 
                                                   		AND A.SOPND_PRJCODE = '$SOPNH_PRJCODE'
                                                    ORDER BY A.ITEM_CODE ASC";
                                    // count data
                                        $resultCount = $this->db->where('SOPNH_CODE', $SOPNH_CODE);
                                        $resultCount = $this->db->count_all_results('tbl_sopn_detail');
                                    // End count data
                                    $result = $this->db->query($sqlDET)->result();
                                    $i		= 0;
                                    $j		= 0;
                                    if($resultCount > 0)
                                    {
                                        foreach($result as $row) :
                                            $currentRow  	= ++$i;
                                            $SOPNH_CODE 	= $row->SOPNH_CODE;
											$SOPND_DATE		= $row->SOPND_DATE;
                                            $SOPND_PRJCODE 	= $row->SOPND_PRJCODE;
                                            $ITEM_CODE 		= $row->ITEM_CODE;
                                            $ITEM_NOTE 		= $row->ITEM_NOTE;
                                            $ITEM_UNIT 		= $row->ITEM_UNIT;
											$ITEM_TYPE		= $row->ITEM_TYPE;
                                            $ITEM_QTY 		= $row->ITEM_QTY;
                                            $ITEM_PRICE 	= $row->ITEM_PRICE;
                                            $ITEM_IN_NOW 	= $row->ITEM_IN_NOW;
                                            $ITEM_IN_BEF0 	= $row->ITEM_IN_BEF0;
                                            $ITEM_OUT_NOW 	= $row->ITEM_OUT_NOW;
                                            $ITEM_OUT_BEF0	= $row->ITEM_OUT_BEF0;
                                            $ITEM_REM_QTY	= $row->ITEM_REM_QTY;
                                            $ITEM_REM_PRICE	= $row->ITEM_REM_PRICE;											
                                            $CSTDESC		= $row->CSTDESC;
											$SOPND_STAT		= $row->SOPND_STAT;
                                            $itemConvertion	= 1;
                    
                                            if ($j==1) {
                                                echo "<tr class=zebra1>";
                                                $j++;
                                            } else {
                                                echo "<tr class=zebra2>";
                                                $j--;
                                            }
                                            ?> 
                                          	<tr>
                                          	  <td width="2%" height="25" style="text-align:left">
                                            	<?php echo "$currentRow."; ?> 
                                                <input type="Checkbox" id="data[<?php echo $currentRow; ?>][chk]" name="data[<?php echo $currentRow; ?>][chk]" value="<?php echo $currentRow; ?>" onclick="pickThis(this,<?php echo $currentRow; ?>)" style="display:none">
                                                <input type="Checkbox" style="display:none" id="chk<?php echo $currentRow; ?>" name="chk<?php echo $currentRow; ?>" value="" >
                                                <input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">
                                                <input type="hidden" id="chk" name="chk" value="<?php echo $currentRow; ?>" width="10" size="15" readonly class="form-control" style="max-width:300px;">
                                                <input type="hidden" id="data<?php echo $currentRow; ?>SOPNH_CODE" name="data[<?php echo $currentRow; ?>][SOPNH_CODE]" value="<?php echo $SOPNH_CODE; ?>" width="10" size="15" readonly class="form-control" style="max-width:300px;">
                                                <input type="hidden" id="data<?php echo $currentRow; ?>SOPND_PRJCODE" name="data[<?php echo $currentRow; ?>][SOPND_PRJCODE]" value="<?php echo $SOPND_PRJCODE; ?>" width="10" size="15" readonly class="form-control" style="max-width:300px;">
                                                <!-- Checkbox -->                                    		
                                          	</td>
                                          	<td width="8%" style="text-align:left">
												<?php echo $ITEM_CODE; ?>
                                                <input type="hidden" id="data<?php echo $currentRow; ?>ITEM_CODE" name="data[<?php echo $currentRow; ?>][ITEM_CODE]" value="<?php echo $ITEM_CODE; ?>" width="10" size="15" readonly class="form-control" style="max-width:300px;">
                                                <input type="hidden" id="data<?php echo $currentRow; ?>SOPND_DATE" name="data[<?php echo $currentRow; ?>][SOPND_DATE]" value="<?php echo $SOPND_DATE; ?>" width="10" size="15" class="form-control" style="max-width:300px;">
                                                <input type="hidden" id="data<?php echo $currentRow; ?>SOPND_STAT" name="data[<?php echo $currentRow; ?>][SOPND_STAT]" value="<?php echo $SOPND_STAT; ?>" width="10" size="15" class="form-control" style="max-width:300px;">
                                                <input type="hidden" id="data<?php echo $currentRow; ?>ITEM_TYPE" name="data[<?php echo $currentRow; ?>][ITEM_TYPE]" value="<?php echo $ITEM_TYPE; ?>" width="10" size="15" class="form-control" style="max-width:300px;">
                                                <!-- Item Code -->
                                          	</td>
                                          	<td width="30%" style="text-align:left">
												<?php echo $CSTDESC; ?>
                                                <!-- Item Name -->
                                          	</td>
                                          	<td width="3%" style="text-align:right" nowrap>
                                            	<?php echo $ITEM_UNIT; ?>
                                                <input type="hidden" id="data<?php echo $currentRow; ?>ITEM_UNIT" name="data[<?php echo $currentRow; ?>][ITEM_UNIT]" value="<?php echo $ITEM_UNIT; ?>" width="10" size="15" readonly class="form-control" style="max-width:300px;">
                                                <!-- Item Unit -->
                                            </td>
                                          	<td width="8%" nowrap style="text-align:right">
                                            	<input type="hidden" name="data[<?php echo $currentRow; ?>][ITEM_PRICE]" id="data<?php echo $currentRow; ?>ITEM_PRICE" size="10" value="<?php echo $ITEM_PRICE; ?>" class="form-control" style="max-width:300px;">
                                                <input type="text" name="ITEM_PRICEX<?php echo $currentRow; ?>" id="ITEM_PRICEX<?php echo $currentRow; ?>" value="<?php echo number_format($ITEM_PRICE, $decFormat); ?>" class="form-control" style="min-width:110px; min-width:110px; max-width:300px; text-align:right" disabled >
                                            </td>
                                          	<td width="7%" nowrap style="text-align:right"><input type="text" name="ITEM_QTY<?php echo $currentRow; ?>" id="ITEM_QTY<?php echo $currentRow; ?>" value="<?php echo number_format($ITEM_QTY, $decFormat); ?>" class="form-control" style="min-width:110px; min-width:110px; max-width:300px; text-align:right" disabled ></td>
                                          	<td width="8%" style="text-align:right; display:none" nowrap>
                                            	<input type="hidden" name="ITEM_IN_BEF0<?php echo $currentRow; ?>" id="ITEM_IN_BEF0<?php echo $currentRow; ?>" value="<?php echo number_format($ITEM_IN_BEF0, $decFormat); ?>" class="form-control" style="min-width:110px; min-width:110px; max-width:300px; text-align:right" disabled >
                                                <input type="hidden" name="data[<?php echo $currentRow; ?>][ITEM_IN_BEF0]" id="data<?php echo $currentRow; ?>ITEM_IN_BEF0" size="10" value="<?php echo $ITEM_IN_BEF0; ?>" class="form-control">
                                                <!-- Prev. Item Qty -->
                                            </td>
                                          	<td width="9%" style="text-align:right" nowrap>
                                            	<input type="text" name="ITEM_IN_NOW<?php echo $currentRow; ?>" id="ITEM_IN_NOW<?php echo $currentRow; ?>" size="10" value="<?php echo number_format($ITEM_IN_NOW, $decFormat); ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion_in(this,<?php echo $currentRow; ?>);" >
                                                <input type="hidden" name="data[<?php echo $currentRow; ?>][ITEM_IN_NOW]" id="data<?php echo $currentRow; ?>ITEM_IN_NOW" size="10" value="<?php echo $ITEM_IN_NOW; ?>" class="form-control" style="max-width:300px;">
                                            	<!-- Current Month -->
                                         	</td>
                                          	<td width="5%" style="text-align:center; display:none" nowrap>
                                            	<input type="hidden" name="ITEM_OUT_BEF0<?php echo $currentRow; ?>" id="ITEM_OUT_BEF0<?php echo $currentRow; ?>" value="<?php echo number_format($ITEM_OUT_BEF0, $decFormat); ?>" class="form-control" style="min-width:110px; min-width:110px; max-width:300px; text-align:right" disabled >
                                                <input type="hidden" name="data[<?php echo $currentRow; ?>][ITEM_OUT_BEF0]" id="data<?php echo $currentRow; ?>ITEM_OUT_BEF0" size="10" value="<?php echo $ITEM_OUT_BEF0; ?>" class="form-control">
                                                <!-- ITEM_OUT_BEF0 -->
                                          	</td>
                                          	<td width="7%" style="text-align:center" nowrap>
                                                <input type="text" name="ITEM_OUT_NOW<?php echo $currentRow; ?>" id="ITEM_OUT_NOW<?php echo $currentRow; ?>" size="10" value="<?php echo number_format($ITEM_OUT_NOW, $decFormat); ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion_out(this,<?php echo $currentRow; ?>);" >
                                                <input type="hidden" name="data[<?php echo $currentRow; ?>][ITEM_OUT_NOW]" id="data<?php echo $currentRow; ?>ITEM_OUT_NOW" size="10" value="<?php echo $ITEM_OUT_NOW; ?>" class="form-control" style="max-width:300px;">
                                                <!-- ITEM_OUT_NOW -->
                                          	</td>
                                          	<td width="13%" style="text-align:center">
                                            	<input type="text" name="data[<?php echo $currentRow; ?>][ITEM_NOTE]" id="data<?php echo $currentRow; ?>ITEM_NOTE" size="20" value="<?php echo $ITEM_NOTE; ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:left">
                                                <input type="hidden" name="data[<?php echo $currentRow; ?>][ITEM_REM_QTY]" id="data<?php echo $currentRow; ?>ITEM_REM_QTY" size="20" value="<?php echo $ITEM_REM_QTY; ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:left">
                                                <input type="hidden" name="data[<?php echo $currentRow; ?>][ITEM_REM_PRICE]" id="data<?php echo $currentRow; ?>ITEM_REM_PRICE" size="20" value="<?php echo $ITEM_REM_PRICE; ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:left">
                                                <!-- Remarks -->
											</td>
                                      </tr>
                                <?php
                                        endforeach;
                                    }
                                }
                                if($task == 'add')
                                {
                                    ?>
                                    <input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">
                                <?php
                                }
                                ?>
								<input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">
                        </table></td>
            </tr>
            <tr>
              <td colspan="6" align="left" class="style1">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="6" align="left" class="style1">
                    <?php
						$edited	= 0;
						if($ISCREATE == 1 && ($SOPNH_STAT == 1 || $SOPNH_STAT == 4)) // New || Revise
						{
							$edited	= 1;
						}
						elseif($SOPNH_STAT == 2) // Confirm
						{
							$edited	= 0;
							if($ISAPPROVE == 1)
								$edited	= 1;
						}
						elseif($SOPNH_STAT == 3 || $SOPNH_STAT == 5 || $SOPNH_STAT == 6) // Approve || Reject || Close
						{
							$edited	= 0;
						}
						
						
						if($edited == 1)
						/*{
							?>
								<input type="button" class="btn btn-primary" name="btnSubmt" id="btnSubmt" value="<?php if($task=='add')echo 'save'; else echo 'update';?>" onClick="submitForm(2);" /> &nbsp;
							<?php 
						}*/
						
						{
							if($task=='add')
							{
								?>
									<button type="button" class="btn btn-primary" onClick="submitForm(2);">
									<i class="cus-save-16x16"></i>&nbsp;&nbsp;<?php echo $Save; ?>
									</button>&nbsp;
								<?php
							}
							else
							{
								?>
									<button type="button" class="btn btn-primary" onClick="submitForm(2);">
									<i class="cus-save1-16x16"></i>&nbsp;&nbsp;<?php echo $Update; ?>
									</button>&nbsp;
								<?php
							}
						}
				
					echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="cus-back-16x16"></i>&nbsp;&nbsp;'.$Back.'</button>');

					?>
            	</td>
            </tr>
            <tr>
              <td align="left" class="style1" style="text-align:right; font-style:italic">&nbsp;</td>
              <td align="left" class="style1">&nbsp;</td>
              <td colspan="4" align="left" class="style1">&nbsp;</td>
            </tr> 
            <tr id="showMemo" <?php if($SOPNH_REVMEMO == '') { ?> style="display:none" <?php } ?>>
                <td align="left" class="style1" style="text-align:right; font-style:italic">Revise Memo</td>
                <td align="left" class="style1">:</td>
                <td colspan="4" align="left" class="style1"><textarea name="SOPNH_REVMEMO" id="SOPNH_REVMEMO" class="form-control" style="max-width:350px;" cols="30"><?php echo $SOPNH_REVMEMO; ?></textarea>
                </td>
            </tr>
            <tr>
              <td align="left" class="style1" style="text-align:right; font-style:italic">&nbsp;</td>
              <td align="left" class="style1">&nbsp;</td>
              <td colspan="4" align="left" class="style1">&nbsp;</td>
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
    $('#datepicker1').datepicker({
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
	var selectedRows = 0;
	function check_all(chk) 
	{
		var totRow = document.getElementById('totalrow').value;
		if(chk.checked == true)
		{
			for(i=1;i<=totRow;i++)
			{
				var aaaa = document.getElementById('data['+i+'][chk]').checked = true;
			}
		}
		else
		{
			for(i=1;i<=totRow;i++)
			{
				var aaaa = document.getElementById('data['+i+'][chk]').checked = false;
			}
		}
	}

	var selectedRows = 0;
	function pickThis(thisobj,ke)
	{
		if(thisobj.checked)
		{
			document.getElementById('chk'+thisobj.value).checked = true;
		}
		else
		{
			document.getElementById('chk'+thisobj.value).checked = false;
		}
		
		objTable = document.getElementById('tbl');
		intTable = objTable.rows.length;
		var NumOfRows = intTable-1;
		if (thisobj!= '') 
		{
			if (thisobj.checked) selectedRows++;
			else selectedRows--;
		}
		
		if (selectedRows==NumOfRows) 
		{
			document.frm.HChkAllItem.checked = true;
		}
		else
		{
			document.frm.HChkAllItem.checked = false;
		}
	}

	function add_item(strItem) 
	{
		arrItem = strItem.split('|');
		//alert(arrItem)
		
		var objTable, objTR, objTD, intIndex, arrItem;
		var SOPNH_CODE 		= "<?php echo $DocNumber; ?>";
		var SOPND_PRJCODE	= "<?php echo $PRJCODE; ?>";
		var SOPND_DATE		= "<?php echo $SOPNH_DATE; ?>";
		var SOPND_STAT		= 1;
		ilvl = arrItem[1];
		
		/*validateDouble(arrItem[0],arrItem[1])
		if(validateDouble(arrItem[0],arrItem[1]))
		{
			alert("Double Item for " + arrItem[0]);
			return;
		}*/
		//alert('a')
		itemcode 		= arrItem[0];
		itemserial 		= arrItem[1];
		itemname 		= arrItem[2];
		itemUnitType 	= arrItem[3];
		itemNameType 	= arrItem[4];
		itemUnitType2 	= arrItem[5];
		itemNameType2 	= arrItem[6];
		itemPrice 		= arrItem[7];
		itemType 		= arrItem[8];	// MTRL - TOOLS - UPAH
		itemVolume 		= arrItem[9];	// Stock
		
		ITEM_IN_BEF0	= 0;
		ITEM_OUT_BEF0	= 0;
		objTable 		= document.getElementById('tbl');
		intTable 		= objTable.rows.length;
		//alert('intTable = '+intTable)
		//intIndex = parseInt(document.frm.rowCount.value) + 1;
		intIndex = parseInt(objTable.rows.length) - 1;
		//intIndex = intTable;
		document.frm.rowCount.value = intIndex;
		
		objTR = objTable.insertRow(intTable);
		objTR.id = 'tr_' + intIndex;
		
		// Checkbox
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = "center";
		objTD.noWrap = true;
		objTD.innerHTML = ''+intIndex+'<input type="Checkbox" id="data['+intIndex+'][chk]" name="data['+intIndex+'][chk]" value="'+intIndex+'" onclick="pickThis(this,'+intIndex+')" style="display:none"><input type="Checkbox" style="display:none" id="chk'+intIndex+'" name="chk'+intIndex+'" value="" ><input type="hidden" name="totalrow" id="totalrow" value="'+intIndex+'"><input type="hidden" id="chk" name="chk" value="'+intIndex+'" width="10" size="15" readonly class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'SOPNH_CODE" name="data['+intIndex+'][SOPNH_CODE]" value="'+SOPNH_CODE+'" width="10" size="15" readonly class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'SOPND_PRJCODE" name="data['+intIndex+'][SOPND_PRJCODE]" value="'+SOPND_PRJCODE+'" width="10" size="15" readonly class="form-control" style="max-width:300px;">';	
		//alert('b')
		// Item Code
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'right';
		objTD.innerHTML = ''+itemcode+'<input type="hidden" id="data'+intIndex+'ITEM_CODE" name="data['+intIndex+'][ITEM_CODE]" value="'+itemcode+'" width="10" size="15" readonly class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'SOPND_DATE" name="data['+intIndex+'][SOPND_DATE]" value="'+SOPND_DATE+'" width="10" size="15" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'SOPND_STAT" name="data['+intIndex+'][SOPND_STAT]" value="'+SOPND_STAT+'" width="10" size="15" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'ITEM_TYPE" name="data['+intIndex+'][ITEM_TYPE]" value="'+itemType+'" width="10" size="15" class="form-control" style="max-width:300px;">';
		
		// Item Name
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.innerHTML = ''+itemname+'';
		
		// Item Unit
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = ''+itemNameType+'';
		
		// Item Price
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="hidden" name="data['+intIndex+'][ITEM_PRICE]" id="data'+intIndex+'ITEM_PRICE" size="10" value="0.00" class="form-control" style="max-width:300px;"><input type="text" name="ITEM_PRICEX'+intIndex+'" id="ITEM_PRICEX'+intIndex+'" value="'+itemPrice+'" class="form-control" style="min-width:110px; min-width:110px; max-width:300px; text-align:right" disabled >';
		
		// Item Stock
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="hidden" name="ITEM_VOLM'+intIndex+'" id="ITEM_VOLM'+intIndex+'" value="'+itemVolume+'" class="form-control" style="min-width:110px; min-width:110px; max-width:300px; text-align:right" ><input type="text" name="ITEM_VOLMX'+intIndex+'" id="ITEM_VOLMX'+intIndex+'" value="'+itemVolume+'" class="form-control" style="min-width:110px; min-width:110px; max-width:300px; text-align:right" disabled >';
		
		// Item Prev. Item
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.style.display = 'none';
		objTD.innerHTML = '<input type="text" name="ITEM_IN_BEF0'+intIndex+'" id="ITEM_IN_BEF0'+intIndex+'" value="'+ITEM_IN_BEF0+'" class="form-control" style="min-width:110px; min-width:110px; max-width:300px; text-align:right" disabled ><input type="hidden" name="data['+intIndex+'][ITEM_IN_BEF0]" id="data'+intIndex+'ITEM_IN_BEF0" size="10" value="'+ITEM_IN_BEF0+'" class="form-control">';
		
		// Item Current Month
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" name="ITEM_IN_NOW'+intIndex+'" id="ITEM_IN_NOW'+intIndex+'" size="10" value="0.00" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion_in(this,'+intIndex+');" ><input type="hidden" name="data['+intIndex+'][ITEM_IN_NOW]" id="data'+intIndex+'ITEM_IN_NOW" size="10" value="0.00" class="form-control" style="max-width:300px;">';
		
		// Item Out Prev. Month
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.style.display = 'none';
		objTD.innerHTML = '<input type="text" name="ITEM_OUT_BEF0'+intIndex+'" id="ITEM_OUT_BEF0'+intIndex+'" value="'+ITEM_OUT_BEF0+'" class="form-control" style="min-width:110px; min-width:110px; max-width:300px; text-align:right" disabled ><input type="hidden" name="data['+intIndex+'][ITEM_OUT_BEF0]" id="data'+intIndex+'ITEM_OUT_BEF0" size="10" value="'+ITEM_OUT_BEF0+'" class="form-control">';
		
		// Item Out Current Month
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" name="ITEM_OUT_NOW'+intIndex+'" id="ITEM_OUT_NOW'+intIndex+'" size="10" value="0.00" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion_out(this,'+intIndex+');" ><input type="hidden" name="data['+intIndex+'][ITEM_OUT_NOW]" id="data'+intIndex+'ITEM_OUT_NOW" size="10" value="0.00" class="form-control" style="max-width:300px;">';
		
		// Remarks
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.innerHTML = '<input type="text" name="data['+intIndex+'][ITEM_NOTE]" id="data'+intIndex+'ITEM_NOTE" size="20" value="" class="form-control" style="min-width:110px; max-width:300px; text-align:left"><input type="hidden" name="data['+intIndex+'][ITEM_REM_QTY]" id="data'+intIndex+'ITEM_REM_QTY" size="20" value="" class="form-control" style="min-width:110px; max-width:300px; text-align:left"><input type="hidden" name="data['+intIndex+'][ITEM_REM_PRICE]" id="data'+intIndex+'ITEM_REM_PRICE" size="20" value="" class="form-control" style="min-width:110px; max-width:300px; text-align:left">';
		
		var decFormat											= document.getElementById('decFormat').value;
		var itemPrice											= document.getElementById('ITEM_PRICEX'+intIndex).value;
		document.getElementById('data'+intIndex+'ITEM_PRICE').value = parseFloat(Math.abs(itemPrice));
		document.getElementById('ITEM_PRICEX'+intIndex).value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(itemPrice)),decFormat));
		document.getElementById('ITEM_VOLMX'+intIndex).value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(itemVolume)),decFormat));
		
		document.getElementById('totalrow').value = intIndex;
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
	
	function validateDouble(vcode,SNCODE) 
	{
		var thechk=new Array();
		var duplicate = false;
		
		var jumchk = document.getElementsByName('chk').length;
		if (jumchk!=null) 
		{
			thechk=document.getElementsByName('chk');
			panjang = parseInt(thechk.length);
		} 
		else 
		{
			thechk[0]=document.getElementsByName('chk');
			panjang = 0;
		}
		var panjang = panjang + 1;
		for (var i=0;i<panjang;i++) 
		{
			var temp = 'tr_'+parseInt(i+1);
			if(i>0)
			{
				var elitem1= document.getElementById('data'+i+'CSTCODE').value;
				var iparent= document.getElementById('data'+i+'SNCODE').value;
				if (elitem1 == vcode && iparent == SNCODE)
				{
					if (elitem1 == vcode) 
					{
						duplicate = true;
						break;
					}
				}
			}
		}
		return duplicate;
	}
	
	function getConvertion_in(thisVal1, row)
	{
		var decFormat	= document.getElementById('decFormat').value;
		
		thisVal 		= parseFloat(Math.abs(thisVal1.value));
		
		now_qty_in		= eval(document.getElementById('ITEM_IN_NOW'+row)).value.split(",").join("");
		now_qty_inx		= parseFloat(now_qty_in);
		document.getElementById('data'+row+'ITEM_IN_NOW').value = now_qty_inx;
		document.getElementById('ITEM_IN_NOW'+row).value	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(now_qty_inx)),decFormat))
		
		// ------------- GET REMAIN QTY -------------
			ITEM_PRICE		= document.getElementById('data'+row+'ITEM_PRICE').value;
			// GET TOTAL ACCEPTANCE VOLUME				
				// UNTUK SEMENTARA DIHIDE KARENA SEHARUSNYA PENERIMAAN BULAN SEBELUMNYA TIDAK EBRPENGARUH THD STOCK, MELAIN DIPENGRAHUI
				// OLEH STOCK TERSEDIA BULAN SEBELUMNYA
					//ITEM_IN_BEF0	= document.getElementById('data'+row+'ITEM_IN_BEF0').value;
				ITEM_IN_BEF0	= document.getElementById('ITEM_VOLM'+row).value;
				ITEM_IN_NOW		= document.getElementById('data'+row+'ITEM_IN_NOW').value;
				TOT_ITEM_IN		= parseFloat(ITEM_IN_BEF0) + parseFloat(ITEM_IN_NOW);
				TOT_PRICE_IN	= parseFloat(TOT_ITEM_IN) * parseFloat(ITEM_PRICE);
			
			// GET TOTAL EXPENDITURE VOLUME
				// UNTUK SEMENTARA DIHIDE KARENA SEHARUSNYA PENGELUARAN BULAN SEBELUMNYA TIDAK EBRPENGARUH THD STOCK, MELAIN DIPENGRAHUI
				// OLEH STOCK TERSEDIA BULAN SEBELUMNYA
					//ITEM_OUT_BEF0	= document.getElementById('data'+row+'ITEM_OUT_BEF0').value;
				ITEM_OUT_NOW	= document.getElementById('data'+row+'ITEM_OUT_NOW').value;
				//TOT_ITEM_OUT	= parseFloat(ITEM_OUT_BEF0) + parseFloat(ITEM_OUT_NOW);
				TOT_ITEM_OUT	= parseFloat(ITEM_OUT_NOW);
				TOT_PRICE_OUT	= parseFloat(TOT_ITEM_OUT) * parseFloat(ITEM_PRICE);
				
			// GET REMAIN
				REM_ITEM		= parseFloat(TOT_ITEM_IN) - parseFloat(TOT_ITEM_OUT);
				REM_PRICE		= parseFloat(TOT_PRICE_IN) - parseFloat(TOT_PRICE_OUT);
		
		document.getElementById('data'+row+'ITEM_REM_QTY').value	= REM_ITEM;
		document.getElementById('data'+row+'ITEM_REM_PRICE').value	= REM_PRICE;
	}
	
	function getConvertion_out(thisVal1, row)
	{
		var decFormat	= document.getElementById('decFormat').value;
		
		thisVal 		= parseFloat(Math.abs(thisVal1.value));
		
		//now_qty_out	= document.getElementById('ITEM_OUT_NOW'+row).value;
		now_qty_out		= eval(document.getElementById('ITEM_OUT_NOW'+row)).value.split(",").join("");
		now_qty_outx	= parseFloat(now_qty_out);
		document.getElementById('data'+row+'ITEM_OUT_NOW').value = now_qty_outx;
		document.getElementById('ITEM_OUT_NOW'+row).value	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(now_qty_outx)),decFormat))
		
		// ------------- GET REMAIN QTY -------------
			ITEM_PRICE		= document.getElementById('data'+row+'ITEM_PRICE').value;
			// GET TOTAL ACCEPTANCE VOLUME
				// UNTUK SEMENTARA DIHIDE KARENA SEHARUSNYA PENERIMAAN BULAN SEBELUMNYA TIDAK EBRPENGARUH THD STOCK, MELAIN DIPENGRAHUI
				// OLEH STOCK TERSEDIA BULAN SEBELUMNYA
					//ITEM_IN_BEF0	= document.getElementById('data'+row+'ITEM_IN_BEF0').value;
				ITEM_IN_BEF0	= document.getElementById('ITEM_VOLM'+row).value;
				ITEM_IN_NOW		= document.getElementById('data'+row+'ITEM_IN_NOW').value;
				TOT_ITEM_IN		= parseFloat(ITEM_IN_BEF0) + parseFloat(ITEM_IN_NOW);
				TOT_PRICE_IN	= parseFloat(TOT_ITEM_IN) * parseFloat(ITEM_PRICE);
			
			// GET TOTAL EXPENDITURE VOLUME
				// UNTUK SEMENTARA DIHIDE KARENA SEHARUSNYA PENGELUARAN BULAN SEBELUMNYA TIDAK EBRPENGARUH THD STOCK, MELAIN DIPENGRAHUI
				// OLEH STOCK TERSEDIA BULAN SEBELUMNYA
					//ITEM_OUT_BEF0	= document.getElementById('data'+row+'ITEM_OUT_BEF0').value;
				ITEM_OUT_NOW	= document.getElementById('data'+row+'ITEM_OUT_NOW').value;
				//TOT_ITEM_OUT	= parseFloat(ITEM_OUT_BEF0) + parseFloat(ITEM_OUT_NOW);
				TOT_ITEM_OUT	= parseFloat(ITEM_OUT_NOW);
				TOT_PRICE_OUT	= parseFloat(TOT_ITEM_OUT) * parseFloat(ITEM_PRICE);
			
			// GET REMAIN
				REM_ITEM		= parseFloat(TOT_ITEM_IN) - parseFloat(TOT_ITEM_OUT);
				REM_PRICE		= parseFloat(TOT_PRICE_IN) - parseFloat(TOT_PRICE_OUT);
		
		document.getElementById('data'+row+'ITEM_REM_QTY').value	= REM_ITEM;
		document.getElementById('data'+row+'ITEM_REM_PRICE').value	= REM_PRICE;
	}
	
	function RoundNDecimal(X, N)
	{
		var T, S=new String(Math.round(X*Number("1e"+N)))
		while (S.length<=N) S='0'+S
		return S.substr(0, T=(S.length-N)) + '.' + S.substr(T, N)
	}

	function formatDate(d)
	{
		var month = d.getMonth();
		var day = d.getDate();
		month = month + 1;
	
		month = month + "";
	
		if (month.length == 1)
		{
			month = "0" + month;
		}
	
		day = day + "";
	
		if (day.length == 1)
		{
			day = "0" + day;
		}
	
		return d.getFullYear() + '-' + month + '-' + day;
	}
	
	function submitForm(value)
	{
		var totrow 		= document.getElementById('totalrow').value;
		//var venCode 	= document.getElementById('Vend_Code').value;
		var SOPNH_STAT 	= document.getElementById('SOPNH_STATX').value;
		//var SOPNH_DATE	= document.getElementById('datepicker').value;
		var SOPNH_DATE	= document.getElementById('datepicker1').value; // DARI PERIODE
		var newDate1 	= new Date(SOPNH_DATE);
		var newDate 	= formatDate(newDate1);
		var SOPND_STAT	= document.getElementById('SOPNH_STAT').value;
		
		if(value == 2) // New || Revise
		{
			for(i=1;i<=totrow;i++)
			{
				document.getElementById('data'+i+'SOPND_DATE').value = newDate;
				document.getElementById('data'+i+'SOPND_STAT').value = SOPND_STAT;
				var ITEM_IN_NOW = parseFloat(document.getElementById('ITEM_IN_NOW'+i).value);
				if(ITEM_IN_NOW == 0)
				{
					/*alert('Please input current month qty.');
					document.getElementById('ITEM_IN_NOW'+i).value = '0';
					document.getElementById('ITEM_IN_NOW'+i).focus();
					return false;*/
				}
				var ITEM_OUT_NOW = parseFloat(document.getElementById('ITEM_OUT_NOW'+i).value);
				if(ITEM_OUT_NOW == 0)
				{
					/*alert('Please input out current month qty.');
					document.getElementById('ITEM_OUT_NOW'+i).value = '0';
					document.getElementById('ITEM_OUT_NOW'+i).focus();
					return false;*/
				}
				
				if(ITEM_IN_NOW == 0 && ITEM_OUT_NOW == 0)
				{
					alert('Please input in or out current month qty.');
					document.getElementById('ITEM_IN_NOW'+i).value = '0';
					document.getElementById('ITEM_IN_NOW'+i).focus();
					return false;
				}
			}
			
			var SOPNH_STAT 	= document.getElementById('SOPNH_STAT').value;
			if(SOPNH_STAT == 4 || SOPNH_STAT == 5)
			{
				SOPNH_REVMEMO	= document.getElementById('SOPNH_REVMEMO').value;
				if(SOPNH_REVMEMO == '')
				{
					alert('Please input the memo why you revise/reject this document.');
					document.getElementById('SOPNH_REVMEMO').focus();
					return false;
				}
			}
			
			if(totrow == 0)
			{
				alert('Please input detail of stock opname qty.');
				return false;		
			}
			else
			{
				document.frm.submit();
			}
		}
		else if(value == 3)
		{
			var SOPNH_STAT 	= document.getElementById('SOPNH_STAT').value;
			if(SOPNH_STAT == 4 || SOPNH_STAT == 5)
			{
				SOPNH_REVMEMO	= document.getElementById('SOPNH_REVMEMO').value;
				if(SOPNH_REVMEMO == '')
				{
					alert('Please input the memo why you revise/reject this document.');
					document.getElementById('SOPNH_REVMEMO').focus();
					return false;
				}
			}
			else
			{
				document.frm.submit();
			}
		}
		else
		{
			alert('Can not update this document. The document has Confirmed.');
			return false;
		}
	}
</script>
<?php 
$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>