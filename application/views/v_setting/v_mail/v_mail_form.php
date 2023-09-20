<?php
/*  
 * Author		= Hendar Permana 
 * Create Date	= 26 Mei 2017
 * File Name	= v_spp_form.php
 * Location		= -
*/

/*  
 * Author		= Hendar Permana 
 * Create Date	= 27 Juli 2017
 * File Name	= v_mail_form.php
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

/*$sql = "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$result = $this->db->query($sql)->result();
foreach($result as $row) :
	$PRJNAME = $row ->PRJNAME;
endforeach;*/

$currentRow = 0;
if($task == 'add')
{		
	/*$dataSessSrc = array(
			'selSearchproj_Code' => $PRJCODE,
			'selSearchType' => $this->input->post('selSearchType'),
			'txtSearch' => $this->input->post('txtSearch'));
	$this->session->set_userdata('dtSessSrc1', $dataSessSrc);
	$this->session->set_userdata('dtSessSrc2', $dataSessSrc);*/
	
	//$MR_DepID 				= 5;
	//$MR_EmpID 				= '';
	//$default['MR_EmpID']		= '';
	//$Vend_Code 				= '';
	//$default['Vend_Code'] 	= '';
	//$default['SPPSTAT'] 		= 1;
	//$SPPSTAT 					= 1;
	//$default['APPROVE'] 		= 1;
	//$APPROVE 					= 1;
	
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
		$Pattern_YearAktive = date('Y');
		$Pattern_MonthAktive = date('m');
		$Pattern_DateAktive = date('d');
	}
	$yearC = (int)$Pattern_YearAktive;
	$year = substr($Pattern_YearAktive,2,2);
	$month = (int)$Pattern_MonthAktive;
	$date = (int)$Pattern_DateAktive;

	$this->db->where('Patt_Year', $year);
	//$this->db->where('Patt_Month', $month);
	//$this->db->where('Patt_Date', $date);
	$myCount = $this->db->count_all('tbl_mailgroup_header');
	
	$sql = "SELECT MAX(MG_ID) as maxNumber FROM tbl_mailgroup_header";
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
	
	
	/*$sql = "SELECT PRJCODE, PRJNAME FROM tbl_project
			WHERE PRJCODE = '$PRJCODE'";
	$resultProj = $this->db->query($sql)->result();
	foreach($resultProj as $row) :
		$PRJNAME = $row->PRJNAME;
	endforeach;*/
	$DocNumber 		= "MG$groupPattern-$lastPatternNumb";
	$MG_CODE		= $DocNumber;
	$MG_NAME		= "";
	
	/*$TRXDATEY 		= date('Y');
	$TRXDATEM 		= date('m');
	$TRXDATED 		= date('d');
	$TRXDATE		= "$TRXDATEM/$TRXDATED/$TRXDATEY";
	$Patt_Year 		= date('Y');
	$Patt_Number	= $lastPatternNumb1;
	$req_date 		= $TRXDATE;
	$SPPNOTE		= '';
	
	$SPP_VALUE		= 0;
	$SPP_VALUEAPP	= 0;*/
}
else
{	
	$MG_CODE				= $default['MG_CODE'];
	//$DocNumber				= $default['MG_CODE'];
	$MG_NAME				= $default['MG_NAME'];
	
	/*$SPPNUM				= $default['SPPNUM'];
	$DocNumber			= $default['SPPNUM'];
	$SPPCODE			= $default['SPPCODE'];
	$TRXDATE			= $default['TRXDATE'];
	$TRXDATE			= date('m/d/Y',strtotime($TRXDATE));
	$PRJCODE			= $default['PRJCODE'];
	$DEPCODE			= $default['DEPCODE'];
	$TRXOPEN			= $default['TRXOPEN'];
	$TRXUSER			= $default['TRXUSER'];
	$APPROVE			= $default['APPROVE'];
	$APPRUSR			= $default['APPRUSR'];
	$JOBCODE			= $default['JOBCODE'];
	$PRJNAME			= $default['PRJNAME'];
	$SPPNOTE			= $default['SPPNOTE'];
	$SPPSTAT			= $default['SPPSTAT'];
	$REVMEMO			= $default['REVMEMO'];
	$SPP_VALUE			= $default['SPP_VALUE'];
	$SPP_VALUEAPP		= $default['SPP_VALUEAPP'];
	$Patt_Year			= $default['Patt_Year'];
	$Patt_Month			= $default['Patt_Month'];
	$Patt_Date			= $default['Patt_Date'];
	$Patt_Number		= $default['Patt_Number'];
	$lastPatternNumb1	= $default['Patt_Number'];*/
	
	/*$dataSessSrc = array(
			'selSearchproj_Code' => $PRJCODE,
			'selSearchType' => $this->input->post('selSearchType'),
			'txtSearch' => $this->input->post('txtSearch'));
	$this->session->set_userdata('dtSessSrc1', $dataSessSrc);
	$this->session->set_userdata('dtSessSrc2', $dataSessSrc);*/
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
		if($TranslCode == 'AddItem')$AddItem = $LangTransl;
		if($TranslCode == 'Save')$Save = $LangTransl;
		if($TranslCode == 'Update')$Update = $LangTransl;
		if($TranslCode == 'Back')$Back = $LangTransl;
		if($TranslCode == 'Code')$Code = $LangTransl;
		if($TranslCode == 'Name')$Name = $LangTransl;
		if($TranslCode == 'EmployeeID')$EmployeeID = $LangTransl;
		if($TranslCode == 'Email')$Email = $LangTransl;

	endforeach;
?>

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>
    <?php echo $h2_title; ?>
    <small>Form Mail Group</small>  </h1>
  <?php /*?><ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#">Tables</a></li>
    <li class="active">Data tables</li>
  </ol><?php */?>
</section>
<!-- Main content -->
<section class="content">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Input Data</h3>
        </div>
          <form name="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return validateInData();">
                <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
                <input type="Hidden" name="rowCount" id="rowCount" value="0">
              <table width="100%" border="0" style="size:auto">
              	  <tr>
                  	   <td>&nbsp;</td> 
                  </tr>
                  <tr>
                      <td width="13%" align="left" class="style1" nowrap><?php echo $Code ?> </td>
                      <td width="1%" align="left" class="style1">:</td>
                      <td width="86%" align="left" class="style1">
					  	<?php echo $MG_CODE; ?>
                        <input type="hidden" name="MG_CODE" id="MG_CODE" class="form-control" style="max-width:150px" value="<?php echo $MG_CODE; ?>">
                      </td>
                  </tr>
                  <tr>
                      <td align="left" class="style1"><?php echo $Name ?> </td>
                      <td align="left" class="style1">:</td>
                      <td align="left" class="style1"><input type="text" class="form-control" style="min-width:110px; max-width:350px; text-align:left" id="MG_NAME" name="MG_NAME" size="5" value="<?php echo $MG_NAME; ?>" /></td>
                  </tr>
                  
                  <tr>
                  	  <td colspan="4">&nbsp;</td>
                  </tr>
                  <?php
                        $mailCode 	= $MG_CODE;
                        $url_AddItem	= site_url('c_setting/c_mail/popupallitem/?id='.$this->url_encryption_helper->encode_url($mailCode));
                    ?>
                  <tr>
                      <td colspan="4" align="left" class="style1" style="font-style:italic">                        
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
                        Add Item [+]                </a>-->
                        <button class="btn btn-success" type="button" onClick="selectitem();">
                        <i class="cus-add-item-16x16"></i>&nbsp;&nbsp;<?php echo $AddItem; ?>
                        </button>
                      <?php 
					  /*?>-- 
					  
                        Delete Item [-]<?php */?></td> <!-- ADD ITEM -->
                  </tr>
                  	<tr>
                      	<td colspan="6" align="left" class="style1">
                            <table width="100%" border="1" id="tbl" >
                                <tr style="background:#CCCCCC">
                                  <th width="4%" height="25"  style="text-align:center">No</th>
                                  <th width="14%" height="25" style="text-align:center"><?php echo $EmployeeID ?></th>
                                  <th width="41%" height="25" style="text-align:center"><?php echo $Name ?></th>
                                  <th width="41%" height="25" style="text-align:center"><?php echo $Email ?></th>
                                </tr>
                                <tr style="background:#CCCCCC">
                                  
                                <?php					
                                if($task == 'edit')
                                {
                                    $sqlDET		= "SELECT * FROM tbl_mailgroup_detail WHERE MG_CODE='$MG_CODE' ORDER BY MG_CODE ASC";
                                    // count data
                                        $resultCount = $this->db->where('MG_CODE', $MG_CODE);
                                        $resultCount = $this->db->count_all_results('tbl_mailgroup_detail');
                                    // End count data
                                    $result = $this->db->query($sqlDET)->result();
                                    $i		= 0;
                                    $j		= 0;
                                    if($resultCount > 0)
                                    {
                                        foreach($result as $row) :
                                            $currentRow  	= ++$i;
                                            //$MGD_ID 		= $row->MGD_ID;
                                            $MG_CODE 		= $row->MG_CODE;
                                            $Emp_ID 		= $row->Emp_ID;
                                            $First_Name		= $row->First_Name;
                                            $Email	 		= $row->Email;
                                
                                            if ($j==1) {
                                                echo "<tr class=zebra1>";
                                                $j++;
                                            } else {
                                                echo "<tr class=zebra2>";
                                                $j--;
                                            }
                                            ?> 
                                                <td width="4%" height="25" style="text-align:center">
                                                  <?php echo "$currentRow."; ?>
                                                <td width="14%" style="text-align:center">
                                                  <?php echo $Emp_ID; ?>
                                                  <input type="hidden" id="data[<?php echo $currentRow; ?>][MG_CODE]" name="data[<?php echo $currentRow; ?>][MG_CODE]" value="<?php echo $MG_CODE; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">
                                                  <input type="hidden" id="data<?php echo $currentRow; ?>Emp_ID" name="data[<?php echo $currentRow; ?>][Emp_ID]" value="<?php echo $Emp_ID; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right"> <!-- Emp_ID -->                                    </td>
                                                <td width="41%" style="text-align:left">
                                                  <?php echo $First_Name; ?>
                                                  <input type="hidden" id="data<?php echo $currentRow; ?>First_Name" name="data[<?php echo $currentRow; ?>][First_Name]" value="<?php echo $Emp_ID; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">
                                                </td>
                                                <td width="41%" style="text-align:center">
                                                  <?php echo $Email; ?>
                                                  <input type="hidden" id="data<?php echo $currentRow; ?>Email" name="data[<?php echo $currentRow; ?>][Email]" value="<?php echo $Emp_ID; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">
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
                            </table>
                  	</td>
                  </tr>
                  <tr>
                    <td colspan="6" align="left" class="style1">&nbsp;</td>
                  </tr>
                  <tr>
                      <td colspan="6" align="left" class="style1">
                        
                  <!--<input type="hidden" name="isApproved" id="isApproved" value="0">-->
                  <?php
					if($ISCREATE == 1)
					{
						if($task=='add')
						{
							?>
								<button class="btn btn-primary">
								<i class="cus-save-16x16"></i>&nbsp;&nbsp;<?php echo $Save; ?>
								</button>&nbsp;
							<?php
						}
						else
						{
							?>
								<button class="btn btn-primary" >
								<i class="cus-save1-16x16"></i>&nbsp;&nbsp;<?php echo $Update; ?>
								</button>&nbsp;
							<?php
						}
					}
				
					echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="cus-back-16x16"></i>&nbsp;&nbsp;'.$Back.'</button>');
				?>        	</td>
                  </tr>
                  <tr>
                      <td colspan="6" align="left" class="style1">&nbsp;</td>
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
		//alert('1')
		arrItem = strItem.split('|');
		//alert('1')
		
		var objTable, objTR, objTD, intIndex, arrItem;
		ilvl = arrItem[1];
		//alert('2')
		
		//validateDouble(arrItem[0],arrItem[1])
		/*if(validateDouble(arrItem[0],arrItem[1]))
		{
			alert("Double Item for " + arrItem[0]);
			return;
		}*/
		//alert('a')
		
		Emp_ID 			= arrItem[0];
		First_Name 		= arrItem[1];
		Email	 		= arrItem[2];
		
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
		objTD.style.textAlign = "center";
		objTD.noWrap = true;
		objTD.innerHTML = ''+intIndex+'<input type="hidden" name="totalrow" id="totalrow" value="'+intIndex+'"><input type="hidden" id="chk" name="chk" value="'+intIndex+'" width="10" size="15" readonly class="form-control" style="max-width:300px;"><input type="hidden" id="data['+intIndex+'][MG_CODE]" name="data['+intIndex+'][MG_CODE]" value="<?php echo $MG_CODE; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">';
		
		//alert('b')
		// Emp_ID
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = ''+Emp_ID+'<input type="hidden" id="data['+intIndex+'][Emp_ID]" name="data['+intIndex+'][Emp_ID]" value="'+Emp_ID+'" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">';
				
		// First_Name
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'left';
		objTD.innerHTML = ''+First_Name+'<input type="hidden" id="data['+intIndex+'][First_Name]" name="data['+intIndex+'][First_Name]" value="'+First_Name+'" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">';
		
		// Email
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = ''+Email+'<input type="hidden" id="data['+intIndex+'][Email]" name="data['+intIndex+'][Email]" value="'+Email+'" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">';
		
		/*<input type="hidden" style="text-align:Left" name="Email'+intIndex+'" id="Email'+intIndex+'" size="10" value="'+Email+'" >';*/
		
		
		/*var decFormat												= document.getElementById('decFormat').value;
		var PPMat_Budget											= document.getElementById('PPMat_Qty'+intIndex).value
		document.getElementById('PPMat_Qty'+intIndex).value 		= parseFloat(Math.abs(PPMat_Budget));
		document.getElementById('PPMat_Qtyx'+intIndex).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PPMat_Budget)),decFormat));*/
		/*var PPMat_Requested											= document.getElementById('PPMat_Requested'+intIndex).value;
		document.getElementById('PPMat_Requested'+intIndex).value 	= parseFloat(Math.abs(PPMat_Requested));
		document.getElementById('PPMat_Requestedx'+intIndex).value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PPMat_Requested)),decFormat));*/
		//alert('c')
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
	
	function getConvertion(thisVal1, row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		
		thisVal 			= parseFloat(Math.abs(thisVal1.value))
		tempTotMax1 		= parseFloat(document.getElementById('tempTotMax'+row).value);
		// Start : 7 Mei 2015 : Permintaan tidak boleh melebihi
		
		reqQty1				= document.getElementById('SPPVOLM'+row).value;
		reqQty1x 			= parseFloat(reqQty1);
		document.getElementById('data'+row+'SPPVOLM').value = reqQty1x;
		document.getElementById('SPPVOLM'+row).value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(reqQty1x)),decFormat))
		
		itemConvertion		= document.getElementById('itemConvertion'+row).value;
		
		reqQty2 			= reqQty1 * itemConvertion;
		document.getElementById('SPPVOLM2'+row).value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(reqQty2)),decFormat));
		
		tempTotMaxx = parseFloat(Math.abs(tempTotMax1));
		
		if(reqQty1x > tempTotMaxx)
		{
			alert('Request Qty is Greater than Budget Qty. Maximum Qty is '+tempTotMaxx);
			document.getElementById('data'+row+'SPPVOLM').value = tempTotMaxx;
			document.getElementById('SPPVOLM'+row).value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(tempTotMaxx)),decFormat))
			document.getElementById('SPPVOLM2'+row).value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(tempTotMaxx)),decFormat))
			return false;
		}
		document.getElementById('data'+row+'SPPVOLM').value = reqQty1x;
		document.getElementById('SPPVOLM'+row).value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(reqQty1x)),decFormat))
		document.getElementById('SPPVOLM2'+row).value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(reqQty1x)),decFormat))
	}
	
	function RoundNDecimal(X, N)
	{
		var T, S=new String(Math.round(X*Number("1e"+N)))
		while (S.length<=N) S='0'+S
		return S.substr(0, T=(S.length-N)) + '.' + S.substr(T, N)
	}
	
	function submitForm(value)
	{
		var totrow 		= document.getElementById('totalrow').value;
		//var venCode 	= document.getElementById('Vend_Code').value;
		var isApproved 	= document.getElementById('isApproved').value;
		
		if(isApproved == 0)
		{
			for(i=1;i<=totrow;i++)
			{
				var SPPVOLM = parseFloat(document.getElementById('SPPVOLM'+i).value);
				if(SPPVOLM == 0)
				{
					alert('Please input qty of requisition.');
					document.getElementById('SPPVOLM'+i).value = '0';
					document.getElementById('SPPVOLM'+i).focus();
					return false;
				}
			}
			/*if(venCode == 0)
			{
				alert('Please select a Vendor.');
				document.getElementById('selVend_Code').focus();
				return false;
			}*/
			if(totrow == 0)
			{
				alert('Please input detail Material Request.');
				return false;		
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