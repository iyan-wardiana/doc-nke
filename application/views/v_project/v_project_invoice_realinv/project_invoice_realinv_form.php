<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 17 Desember 2017
 * File Name	= project_invoice_realinv_form.php
 * Location		= -
*/

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

$currentRow = 0;

$proj_amountIDR1	= 0;
$sqlPRJ 			= "SELECT ISCHANGE, PRJCOST, PRJCOST2 FROM tbl_project
						WHERE PRJCODE = '$PRJCODE'";
$resultPRJ 			= $this->db->query($sqlPRJ)->result();
foreach($resultPRJ  as $rowPRJ) :
	$ISCHANGE		= $rowPRJ->ISCHANGE;
	$proj_amountIDR1= $rowPRJ->PRJCOST;
	$PRJCOST2		= $rowPRJ->PRJCOST2;
	if($ISCHANGE == 1)
	{
		$proj_amountIDR1= $PRJCOST2;
	}		
endforeach;

$proj_amountIDR2	= 0;
$sqlPRJ2 			= "SELECT SI_APPVAL FROM tbl_siheader
						WHERE PRJCODE = '$PRJCODE' AND SI_INCCON = 2";
$resultPRJ2			= $this->db->query($sqlPRJ2)->result();
foreach($resultPRJ2  as $rowPRJ2) :
	$proj_amountIDR2= $rowPRJ2->SI_APPVAL;
endforeach;
$proj_amountIDR		= $proj_amountIDR1 + $proj_amountIDR2;
	
// MENCACRI APAKAH ADA SI INCLUDE TO PROJECT
	
$currentRow = 0;
if($task == 'add')
{
	$DefEmp_ID		= $this->session->userdata['Emp_ID'];
	
	foreach($viewDocPattern as $row) :
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
		$Pattern_YearAktive 	= date('Y');
		$Pattern_MonthAktive 	= date('m');
		$Pattern_DateAktive 	= date('d');
	}
	
	$yearC 	= (int)$Pattern_YearAktive;
	$year 	= substr($Pattern_YearAktive,2,2);
	$month 	= (int)$Pattern_MonthAktive;
	$date 	= (int)$Pattern_DateAktive;

	$this->db->where('Patt_Year', $year);
	//$this->db->where('Patt_Month', $month);
	//$this->db->where('Patt_Date', $date);
	$myCount = $this->db->count_all('tbl_projinv_realh');
	
	/*$sql = "SELECT MAX(Patt_Number) as maxNumber FROM tbl_projinv_header WHERE PRJCODE = '$PRJCODE'";
	$result = $this->db->query($sql)->result();
	if($myCount>0)
	{
		foreach($result as $row) :
			$myMax 		= $row->maxNumber;
			$myMaxRUN	= $row->maxNumber;
			$myMax 		= $myMax+1;
			$myMaxRUN 	= $myMaxRUN+1;
		endforeach;
	}
	else
	{
		$myMax 		= 1;
		$myMaxRUN	= 1;
	}*/
	$sqlRUNN	= "tbl_projinv_realh WHERE PRJCODE = '$PRJCODE'";
	$myRUNN 	= $this->db->count_all($sqlRUNN);

	$myMax 		= $myRUNN+1;
	$myMaxRUN 	= $myRUNN+1;
	
	$thisMonth 	= $month;
	
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
	$lastPatternNumbX 	= $myMaxRUN;
	$lastPatternNumb1 	= $myMax;
	$Patt_Number		= $lastPatternNumb1;
	
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
	
	$lastPatternNumb 	= $nol.$lastPatternNumb;
	$lastPatternNumbX 	= $nol.$lastPatternNumbX;
	
	$sql = "SELECT proj_Number, PRJCODE, PRJNAME FROM tbl_project
			WHERE PRJCODE = '$PRJCODE'";
	$resultProj = $this->db->query($sql)->result();
	foreach($resultProj as $row) :
		$proj_Number 	= $row->proj_Number;
		$PRJCODE 		= $row->PRJCODE;
		$PRJNAME 		= $row->PRJNAME;
	endforeach;
	
	$DocNumber 			= "$Pattern_Code$PRJCODE$groupPattern-$lastPatternNumbX";
	$PRINV_Number		= "$Pattern_Code$PRJCODE$groupPattern-$lastPatternNumbX";
	$PRINV_Date			= date('m/d/Y');
	$PRINV_ManNo		= '';
	$PINV_Number		= '';
	$PINV_Date			= '';
	$PRINV_Deviation	= 0;
	$PRJCODE			= $PRJCODE;
	$PINV_Amount		= 0;
	$PINV_AmountPPn		= 0;
	$PINV_AmountPPh		= 0;
	$PRINV_Amount		= 0;
	$PRINV_AmountPPn	= 0;
	$PRINV_AmountPPh	= 0;
	$PRINV_AmountOTH	= 0;
	$PRINV_Notes		= '';
	$isPPh				= 0;
	$PRINV_STAT			= 1;
	$PINV_TOTAMOUNT		= 0;
	$PRINV_TOTAMOUNT	= 0;
	$OWN_CODE			= '';
}
else
{
	$DocNumber 			= $default['PRINV_Number'];
	$OWN_CODE 			= $default['OWN_CODE'];
	$PRINV_Number 		= $default['PRINV_Number'];
	$PRINV_ManNo 		= $default['PRINV_ManNo'];
	$PRINV_Date 		= $default['PRINV_Date'];
	$PINV_Number 		= $default['PINV_Number'];
	$PINV_Date 			= $default['PINV_Date'];
	$PRINV_Deviation	= $default['PRINV_Deviation'];
	$PINV_Amount 		= $default['PINV_Amount'];
	$PINV_AmountPPn 	= $default['PINV_AmountPPn'];
	$PINV_AmountPPh 	= $default['PINV_AmountPPh'];
	$PRINV_Amount		= $default['PRINV_Amount']; 
	$PRINV_AmountPPn 	= $default['PRINV_AmountPPn']; 
	$PRINV_AmountPPh	= $default['PRINV_AmountPPh'];
	$PRINV_AmountOTH 	= $default['PRINV_AmountOTH'];
	$PRINV_Notes 		= $default['PRINV_Notes'];
	$PRINV_STAT 		= $default['PRINV_STAT'];
	$lastPatternNumb1	= $default['Patt_Number'];
	$PRJCODE 			= $default['PRJCODE'];
	
	$PINV_TOTAMOUNT		= $PINV_Amount + $PINV_AmountPPn;
	$PRINV_TOTAMOUNT	= $PRINV_Amount + $PRINV_AmountPPn - $PRINV_AmountPPh + $PRINV_AmountOTH;
}

$sql 			= "SELECT proj_Number, PRJCODE, PRJNAME, PRJCOST, PRJCOST_PPN, PRJCOST2, PRJCOST2_PPN FROM tbl_project
					WHERE PRJCODE = '$PRJCODE'";
$resultProj 	= $this->db->query($sql)->result();
foreach($resultProj as $row) :
	$proj_Number 	= $row->proj_Number;
	$PRJCODE 		= $row->PRJCODE;
	$PRJNAME 		= $row->PRJNAME;
	$PRJCOST 		= $row->PRJCOST;
	$PRJCOST_PPN	= $row->PRJCOST_PPN;
	$PRJCOST2 		= $row->PRJCOST2;
	$PRJCOST2_PPN	= $row->PRJCOST2_PPN;
endforeach;
if($PRJCOST_PPN == 0)
{
	$PRJCOST_PPN	= 0.1 * $PRJCOST;
}
$PRJCOSTnPPN		= $PRJCOST + $PRJCOST_PPN;

if (isset($_POST['submitSrch']))
{
	$PINV_CAT		= $_POST['PINV_CATSRCH'];
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
		if($TranslCode == 'InvoiceRealization')$InvoiceRealization = $LangTransl;
		if($TranslCode == 'Search')$Search = $LangTransl;
	endforeach;
	if($LangID == 'IND')
	{
		$alert1		= "Nilai Faktur sudah nol atau kurang dari nol.";
		$alert2		= "Jumlah yang akan direalisasi melebihi total faktur.";
	}
	else
	{
		$alert1		= "Invoice Amount can not be zero.";
		$alert2		= "Amount to be received exceeds the invoice amount.";
	}

?>

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>
    <?php echo $InvoiceRealization; ?>
    <small><?php echo $PRJNAME; ?></small>  </h1>
  <?php /*?><ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#">Tables</a></li>
    <li class="active">Data tables</li>
  </ol><?php */?>
</section>
<!-- Main content -->
<section class="content">
    <div class="box box-primary">
    	<form name="frmsrch" id="frmsrch" action="" method=POST>
            <table width="100%" border="0">
                <tr>
                    <td>
                        <input type="hidden" name="PINV_Numbera" id="PINV_Numbera" class="textbox" value="<?php echo $PINV_Number; ?>" />
                        <input type="submit" class="button_css" name="submitSrch" id="submitSrch" value=" search " style="display:none" />
                    </td>
                </tr>
            </table>
        </form>
      	<form name="frm" id="frm" method="post" action="<?php echo $form_action; ?>">
            <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
          	<input type="Hidden" name="rowCount" id="rowCount" value="0">
       	  	<input type="hidden" name="PRINV_STAT" id="PRINV_STAT" value="<?php echo $PRINV_STAT; ?>">
            <table width="100%" border="0" style="size:auto">
                <tr>
                    <td width="17%" align="left" class="style1" nowrap> Invoice Realization No.</td>
                    <td width="1%" align="left" class="style1">:</td>
                    <td width="35%" align="left" class="style1">
                    	<input type="text" class="form-control" name="DocNumber" id="DocNumber" style="max-width:180px" value="<?php echo $DocNumber; ?>" disabled>
                        <input type="hidden" class="textbox" name="PRINV_Number" id="PRINV_Number" size="30" value="<?php echo $PRINV_Number; ?>" />
                        <input type="hidden" class="textbox" name="Patt_Number" id="Patt_Number" size="30" value="<?php echo $lastPatternNumb1; ?>" />
                    </td>
                    <td width="13%" align="left" class="style1" nowrap>Invoice Number</td>
                    <td width="34%" align="left" class="style1">
                        <input type="hidden" class="form-control" name="PINV_Number" id="PINV_Number" style="max-width:160px" value="<?php echo $PINV_Number; ?>" >
                        <input type="text" class="form-control" name="PINV_Number1" id="PINV_Number1" style="max-width:180px" value="<?php echo $PINV_Number; ?>" onClick="selectINV();" > 
					<?php
                        $secAllINV	= site_url('c_project/c_prj180c2einvr/pall180c2einv/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
                    ?>         
                        <script>
                            var urlINV = "<?php echo $secAllINV; ?>";
                            function selectINV()
                            {
                                title = 'Select Item';
                                w = 850;
                                h = 550;
                                //window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
                                var left = (screen.width/2)-(w/2);
                                var top = (screen.height/2)-(h/2);
                                return window.open(urlINV, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
                            }
                        </script>
                    </td>
      			</tr>
        		<tr>
                    <td align="left" class="style1">Project</td>
                    <td align="left" class="style1">:</td>
                    <td align="left" class="style1">          
                        <select name="PRJCODE1" id="PRJCODE1" class="form-control" style="max-width:300px"  disabled>
                            <option value="none">--- None ---</option>
                            <?php echo $i = 0;
                            if($countPRJ > 0)
                            {
                                foreach($vwPRJ as $row) :
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
                        <input type="text" name="PRJCODE" id="PRJCODE" value="<?php echo $PRJCODE; ?>" style="display:none">            </td>
                        <td align="left" class="style1">Invoice Amount</td>
                        <td align="left" class="style1">
                            <input type="hidden" class="form-control" style="text-align:right; max-width:180px" name="PINV_Amount" id="PINV_Amount" value="<?php print $PINV_Amount; ?>">
                            <input type="text" class="form-control" style="text-align:right; max-width:180px" name="PINV_Amount1" id="PINV_Amount1" value="<?php print number_format($PINV_Amount, $decFormat); ?>" disabled>
                        </td>
        </tr>
        <tr>
          <td align="left" class="style1">Project Value</td>
          	<td align="left" class="style1">:</td>
            <td align="left" class="style1" style="font-weight:bold">
            	<input type="text" class="form-control" style="text-align:right; max-width:180px" name="PRJCOST1" id="PRJCOST1" value="<?php print number_format($PRJCOST, 2); ?>" disabled>
                <input type="text" class="form-control" style="text-align:right; max-width:180px; display:none" name="PRJCOST" id="PRJCOST" value="<?php print $PRJCOST; ?>" ></td> 
            <td align="left" class="style1">Invoice PPn</td>
            <td align="left" class="style1">
          <input type="hidden" class="form-control" style="text-align:right; max-width:180px" name="PINV_AmountPPn" id="PINV_AmountPPn" value="<?php print $PINV_AmountPPn; ?>">
          <input type="text" class="form-control" style="text-align:right; max-width:180px" name="PINV_AmountPPn1" id="PINV_AmountPPn1" value="<?php print number_format($PINV_AmountPPn, 2); ?>" disabled>            </td>
        </tr>
        <tr>
            <td align="left" class="style1">PPn Value (10%)</td>
          	<td align="left" class="style1">:</td>
            <td align="left" class="style1" style="font-weight:bold">
              <input type="text" class="form-control" style="text-align:right; max-width:180px" name="PRJCOST_PPN1" id="PRJCOST_PPN1" value="<?php print number_format($PRJCOST_PPN, 2); ?>" disabled>
              <input type="hidden" class="form-control" style="text-align:right; max-width:180px" name="PRJCOST_PPN" id="PRJCOST_PPN" value="<?php echo $PRJCOST_PPN; ?>">              </td> 
            <td align="left" class="style1" id="labelProject1">Total Invoice</td>
            <td align="left" class="style1" id="labelProject2">
                <input type="hidden" class="form-control" style="text-align:right; max-width:180px" name="PINV_TOTAMOUNT" id="PINV_TOTAMOUNT" value="<?php print $PINV_TOTAMOUNT; ?>" disabled>
                <input type="text" class="form-control" style="text-align:right; max-width:180px" name="PINV_TOTAMOUNT1" id="PINV_TOTAMOUNT1" value="<?php print number_format($PINV_TOTAMOUNT, 2); ?>" disabled>
                </td>
      </tr>
        <tr>
            <td align="left" class="style1" nowrap>Total Project  Value (+ PPn 10%)</td>
          	<td align="left" class="style1">:</td>
            <td align="left" class="style1" style="font-weight:bold">
            <input type="text" class="form-control" style="text-align:right; max-width:180px" name="PRJCOSTnPPN1" id="PRJCOSTnPPN1" value="<?php print number_format($PRJCOSTnPPN, 2); ?>" disabled>
            <input type="hidden" class="form-control" style="text-align:right; max-width:180px" name="PRJCOSTnPPN" id="PRJCOSTnPPN" value="<?php echo $PRJCOSTnPPN; ?>"></td> 
            <td align="left" class="style1" id="labelProject1">Owner</td>
            <td align="left" class="style1" id="labelProject2">
            	<select name="OWN_CODE" id="OWN_CODE" class="form-control" style="max-width:300px">
                    <option value="none">--- None ---</option>
                    <?php echo $i = 0;
                    if($countOWN > 0)
                    {
                        foreach($viewOwner as $row) :
                            $own_Code 	= $row->own_Code;
							$own_Title1	= '';
                            $own_Title 	= $row->own_Title;
							if($own_Title != '')
								$own_Title1	= ", $own_Title";
                            $own_Name 	= $row->own_Name;
                            ?>
                            <option value="<?php echo $own_Code; ?>" <?php if($own_Code == $OWN_CODE) { ?> selected <?php } ?>><?php echo "$own_Name$own_Title1"; ?></option>
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
            </td>
        </tr>
        <tr id="DPA" >
            <td align="left" class="style1" style="font-weight:bold">REALIZATION DETAIL</td>
            <td colspan="4" align="left" class="style1" style="font-weight:bold"><hr></td>
        </tr>
        <tr id="DPB">
            <td align="left" class="style1">Realization Amount</td>
            <td align="left" class="style1">:</td>
            <td align="left" class="style1">
                <input type="text" class="form-control" style="text-align:right; max-width:180px" name="PRINV_Amount1" id="PRINV_Amount1" value="<?php print number_format($PRINV_Amount, $decFormat); ?>" onChange="changeRealINVAmount();" >
                <input type="hidden" class="form-control" style="text-align:right; max-width:180px" name="PRINV_Amount" id="PRINV_Amount" value="<?php echo $PRINV_Amount; ?>">            </td> 
            <td align="left" class="style1" id="labelProject1" nowrap>Realization Date</td>
            <td align="left" class="style1" id="labelProject2"><div class="input-group date">
                <div class="input-group-addon">
                <i class="fa fa-calendar"></i>&nbsp;</div><input type="text" name="PRINV_Date" class="form-control pull-left" id="datepicker" value="<?php echo $PRINV_Date; ?>" style="width:150px"></div></td>
        </tr>
        <script>
			function changeRealINVAmount()
			{
				PINV_TOTAMOUNT		= parseFloat(document.getElementById('PINV_TOTAMOUNT').value);
				var INVAmount		= eval(document.getElementById('PRINV_Amount1')).value.split(",").join("");
				
				if(PINV_TOTAMOUNT <= 0)
				{
					alert('<?php echo $alert1; ?>');
					document.getElementById('PRINV_Amount').value	= 0;
					document.getElementById('PRINV_Amount1').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(0)), 2));
					return false;
				}
				
				if(INVAmount > PINV_TOTAMOUNT)
				{
					alert('<?php echo $alert2; ?>');
					document.getElementById('PRINV_Amount').value	= PINV_TOTAMOUNT;
					document.getElementById('PRINV_Amount1').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(PINV_TOTAMOUNT)), 2));
					INVAmount		= parseFloat(PINV_TOTAMOUNT);
				}
				
				document.getElementById('PRINV_Amount').value		= parseFloat(INVAmount);
				document.getElementById('PRINV_Amount1').value 		= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(INVAmount)), 2));
				
				
				//RealAmountPPn	= eval(document.getElementById('PRINV_AmountPPn1')).value.split(",").join("");	
				//if(RealAmountPPn == 0)			
				RealAmountPPn	= 0.1 * parseFloat(INVAmount);
				document.getElementById('PRINV_AmountPPn').value	= parseFloat(RealAmountPPn);
				document.getElementById('PRINV_AmountPPn1').value	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(RealAmountPPn)), 2));
				// PPn ditiadakan karena penerimaan
				//NewRealAmount	= parseFloat(INVAmount) + parseFloat(RealAmountPPn);
				NewRealAmount	= parseFloat(INVAmount);
				
				RealAmountPPh	= parseFloat(NewRealAmount) * 0.03;
				document.getElementById('PRINV_AmountPPh').value	= RealAmountPPh;
				document.getElementById('PRINV_AmountPPh1').value	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(RealAmountPPh)), 2));
				PRINV_TOTAMOUNT	= parseFloat(INVAmount) + parseFloat(RealAmountPPn) - parseFloat(RealAmountPPh);
				document.getElementById('PRINV_TOTAMOUNT').value	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(PRINV_TOTAMOUNT)), 2));
			}
			
			function changePPn()
			{
				PRINV_Amount		= parseFloat(document.getElementById('PRINV_Amount').value);
				PRINV_AmountPPn1	= eval(document.getElementById('PRINV_AmountPPn1')).value.split(",").join("");
				
				RealAmountPPn		= parseFloat(PRINV_AmountPPn1);
				document.getElementById('PRINV_AmountPPn').value	= parseFloat(RealAmountPPn);
				document.getElementById('PRINV_AmountPPn1').value	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(RealAmountPPn)), 2));
				NewRealAmount		= parseFloat(PRINV_Amount) + parseFloat(RealAmountPPn);
				
				RealAmountPPh	= parseFloat(NewRealAmount) * 0.03;
				document.getElementById('PRINV_AmountPPh').value	= RealAmountPPh;
				document.getElementById('PRINV_AmountPPh1').value	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(RealAmountPPh)), 2));
				PRINV_TOTAMOUNT	= parseFloat(PRINV_Amount) + parseFloat(RealAmountPPn) - parseFloat(RealAmountPPh);
				document.getElementById('PRINV_TOTAMOUNT').value	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(PRINV_TOTAMOUNT)), 2));
			}
			
			function changePPh()
			{
				PRINV_Amount		= parseFloat(document.getElementById('PRINV_Amount').value);
				RealAmountPPn		= eval(document.getElementById('PRINV_AmountPPn1')).value.split(",").join("");
				NewRealAmount		= parseFloat(PRINV_Amount) + parseFloat(RealAmountPPn);
				
				PRINV_AmountPPh1	= eval(document.getElementById('PRINV_AmountPPh1')).value.split(",").join("");
				RealAmountPPh		= parseFloat(PRINV_AmountPPh1);
				document.getElementById('PRINV_AmountPPh').value	= RealAmountPPh;
				document.getElementById('PRINV_AmountPPh1').value	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(RealAmountPPh)), 2));
				PRINV_TOTAMOUNT	= parseFloat(PRINV_Amount) + parseFloat(RealAmountPPn) - parseFloat(RealAmountPPh);
				document.getElementById('PRINV_TOTAMOUNT').value	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(PRINV_TOTAMOUNT)), 2));
			}
		</script>
        <tr>
            <td align="left" class="style1">PPn Amount</td>
          	<td align="left" class="style1">:</td>
            <td align="left" class="style1"> <input type="text" class="form-control" style="text-align:right; max-width:180px" name="PRINV_AmountPPn1" id="PRINV_AmountPPn1" value="<?php print number_format($PRINV_AmountPPn, $decFormat); ?>" onChange="changePPn(this.value)" >
              <input type="hidden" size="17" class="textbox" style="text-align:right;" name="PRINV_AmountPPn" id="PRINV_AmountPPn" value="<?php echo $PRINV_AmountPPn; ?>"></td> 
            <td align="left" class="style1">&nbsp;</td>
            <td align="left" class="style1">&nbsp;</td>
        </tr>
        <tr>
            <td align="left" class="style1">PPh Amount</td>
          	<td align="left" class="style1">:</td>
            <td align="left" class="style1"> <input type="text" class="form-control" style="text-align:right; max-width:180px" name="PRINV_AmountPPh1" id="PRINV_AmountPPh1" value="<?php print number_format($PRINV_AmountPPh, $decFormat); ?>" onChange="changePPh()" >
              <input type="hidden" size="17" class="textbox" style="text-align:right;" name="PRINV_AmountPPh" id="PRINV_AmountPPh" value="<?php echo $PRINV_AmountPPh; ?>"></td> 
            <td align="left" class="style1">&nbsp;</td>
            <td align="left" class="style1">&nbsp;</td>
        </tr>
        <tr>
            <td align="left" class="style1">Other Realization Amount</td>
          	<td align="left" class="style1">:</td>
            <td align="left" class="style1"> <input type="text" class="form-control" style="text-align:right; max-width:180px" name="PRINV_AmountOTH1" id="PRINV_AmountOTH1" value="<?php print number_format($PRINV_AmountOTH, $decFormat); ?>" onChange="changeOthINVAmount(this.value);" >
              <input type="hidden" size="17" class="textbox" style="text-align:right;" name="PRINV_AmountOTH" id="PRINV_AmountOTH" value="<?php echo $PRINV_AmountOTH; ?>"></td> 
            <td align="left" class="style1">Receipt Total</td>
            <td align="left" class="style1"><input type="text" class="form-control" style="text-align:right; max-width:180px" name="PRINV_TOTAMOUNT" id="PRINV_TOTAMOUNT" value="<?php print number_format($PRINV_TOTAMOUNT, 2); ?>" disabled></td>
        </tr>
        <tr>
            <td align="left" class="style1" valign="top">Notes</td>
            <td align="left" class="style1" valign="top">:</td>
          	<td colspan="3" align="left" class="style1">
            <textarea name="PRINV_Notes" id="PRINV_Notes" class="form-control" style="max-width:350px;" cols="30"><?php echo $PRINV_Notes; ?></textarea>
          	</td> 
            </tr>
        <tr>
			<td align="left" class="style1">Status</td>
			<td align="left" class="style1">:</td>
			<td align="left" class="style1">
            	<select name="PRINV_STAT" id="PRINV_STAT" class="form-control" style="max-width:100px" onChange="selStat(this.value)">
                    <option value="1"<?php if($PRINV_STAT == 1) { ?> selected <?php } ?>>New</option>
                    <option value="2"<?php if($PRINV_STAT == 2) { ?> selected <?php } ?>>Confirm</option>
                    <?php
					if($ISAPPROVE == 1)
					{
						?>
                    		<option value="3"<?php if($PRINV_STAT == 3) { ?> selected <?php } ?>>Approve</option>
                    	<?php
					}
					else
					{
						?>
                    		<option value="3"<?php if($PRINV_STAT == 3) { ?> selected <?php } ?> disabled>Approve</option>
                    	<?php
					}
					?>
                    <option value="6"<?php if($PRINV_STAT == 6) { ?> selected <?php } ?> style="display:none">Close</option>
                </select>
            </td>
			<td align="left" class="style1">&nbsp;</td>
			<td align="left" class="style1">&nbsp;</td>
        </tr> 
        <tr>
          	<td colspan="5" align="left" class="style1">&nbsp;</td>
        </tr> 
        <tr>
            <td align="left" class="style1">&nbsp;</td>
            <td align="left" class="style1">&nbsp;</td>
            <td align="left" class="style1">
            	<?php
					if($task=='add')
					{
						if($PRINV_STAT == 1 && $ISCREATE == 1)
						{
							?>
								<button class="btn btn-primary">
								<i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Save; ?>
								</button>&nbsp;
							<?php
						}
					}
					else
					{
						if($PRINV_STAT == 1 && $ISCREATE == 1)
						{
							?>
								<button class="btn btn-primary" >
								<i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Update; ?>
								</button>&nbsp;
							<?php
						}
					}
					echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i>&nbsp;&nbsp;'.$Back.'</button>');
				?>
            </td>
            <td align="left" class="style1">&nbsp;</td>
            <td style="font-weight:bold; font-style:italic; text-align:right" nowrap>&nbsp;</td>
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
  
	function getDetail(strItem) 
	{
		arrItem 		= strItem.split('|');		
		
		pageFrom 		= arrItem[0];
		
		var decFormat	= document.getElementById('decFormat').value;
		
			PINV_CODE 			= arrItem[0];		// 0
			PINV_MANNO 			= arrItem[1];		// 1
			PINV_STEP 			= arrItem[2];		// 2
			PINV_MMC 			= arrItem[3];		// 3
			PINV_SOURCE 		= arrItem[4];		// 4
			PINV_DATE			= arrItem[5];		// 5
			PINV_ENDDATE 		= arrItem[6];		// 6
			PINV_TOTVAL			= arrItem[7];		// 7
			PINV_TOTVALPPn 		= arrItem[8];		// 8
			var PINV_TOTAMOUNT	= parseFloat(PINV_TOTVAL + PINV_TOTVALPPn);
			
			document.getElementById('PINV_Number').value 	= PINV_CODE;
			document.getElementById('PINV_Number1').value 	= PINV_CODE
			document.getElementById('PINV_Amount').value 	= PINV_TOTVAL;
			document.getElementById('PINV_Amount1').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(PINV_TOTVAL)),4));
			document.getElementById('PINV_AmountPPn').value = PINV_TOTVALPPn;
			document.getElementById('PINV_AmountPPn1').value = doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(PINV_TOTVALPPn)),4));
			document.getElementById('PINV_TOTAMOUNT').value = PINV_TOTAMOUNT;
			document.getElementById('PINV_TOTAMOUNT1').value = doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(PINV_TOTAMOUNT)),4));
	}
	
	function submitForm(value)
	{
		var PINV_MANNO	= document.getElementById('PINV_MANNO').value;
		var PINV_STEP	= document.getElementById('PINV_STEP').value;
		var PINV_PROG	= document.getElementById('PINV_PROG').value;
		var FlagUSER	= document.getElementById('FlagUSER').value;
		var CatSelected	= document.getElementById('CatSelected').value;
		var PINV_STATX	= document.getElementById('PINV_STATX').value;
		var PINV_CATX	= document.getElementById('PINV_CATX').value;
		var isEdit		= document.getElementById('isEdit').value;
		
		if(PINV_CATX == 2)
		{
			PINV_SOURCE1	= document.getElementById('PINV_SOURCE1').value;
			if(PINV_SOURCE1 == '')
			{
				alert('Please select one of MC Number');
				return false;
			}
		}
		else if(PINV_CATX == 3)
		{
			PINV_SOURCE2	= document.getElementById('PINV_SOURCE2').value;
			if(PINV_SOURCE2 == '')
			{
				alert('Please select one of SI Number');
				return false;
			}
		}
		
		if(PINV_MANNO == '')
		{
			alert('Please input MC Manual Number.');
			document.getElementById('PINV_MANNO').focus();
			return false;
		}
		
		if(PINV_STEP == 0)
		{
			alert('Please select step of MC.');
			document.getElementById('PINV_STEP').focus();
			return false;
		}
		
		if(CatSelected != 1)
		{
			if(PINV_PROG == 0)
			{
				alert('Please input Progress Percentation.');
				document.getElementById('PINV_PROG1').value = '';
				document.getElementById('PINV_PROG1').focus();
				return false;
			}
		}
		
		if(isEdit == 'N')
		{
			alert('Sorry ... You can not update this document.')
			return false;
		}
		else
		{
			document.frm.submit();
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
		//else return (c + '.' + dec);
		else return (c);  // untuk menghilangkan 2 angka di belakang koma
	}
	
	function doDecimalFormatxx(angka) {
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