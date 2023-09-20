<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 5 April 2018
 * File Name	= opname_inv_form.php
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

$sql = "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$result = $this->db->query($sql)->result();
foreach($result as $row) :
	$PRJNAME = $row ->PRJNAME;
endforeach;

$OPNH_NUM 	= $default['OPNH_NUM'];
$OPNH_CODE 	= $default['OPNH_CODE'];

$sqlOPNH	= "tbl_opn_inv WHERE OPNH_NUM = '$OPNH_NUM' AND PRJCODE = '$PRJCODE' AND OPNI_STAT = '3'";
$resOPNH 	= $this->db->count_all($sqlOPNH);
if($resOPNH == 0)
{
	$yearC		= date('Y');
	$sql 		= "tbl_opn_inv WHERE Patt_Year = $yearC AND PRJCODE = '$PRJCODE'";
	$myCount 	= $this->db->count_all($sql);
	$myMax		= $myCount + 1;
	$len 		= strlen($myMax);

	$currentRow 	= 0;
	$Pattern_Length	= 5;
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
	$lastPatternNumb = $nol.$myMax;

	$groupDate	= date('ymd');

	$OPNI_NUM	= "$PRJCODE.OPN$groupDate-$lastPatternNumb-F";
	$OPNI_CODE	= "$lastPatternNumb-F";
}
else
{
	$sqlOPNI	= "SELECT OPNI_NUM, OPNI_CODE FROM tbl_opn_inv
					WHERE OPNH_NUM = '$OPNH_NUM' AND PRJCODE = '$PRJCODE' AND OPNI_STAT = '3'";
	$resOPNI 	= $this->db->query($sqlOPNI)->result();
	foreach($resOPNI as $rosOPNI):
		$OPNI_NUM	= $rosOPNI->OPNI_NUM;
		$OPNI_CODE	= $rosOPNI->OPNI_CODE;
	endforeach;
}

$OPNI_DATE		= date('m/d/Y');
if(function_exists('date_default_timezone_set')) date_default_timezone_set('Asia/Jakarta');
$date 			= date_create($OPNI_DATE);
date_add($date, date_interval_create_from_date_string('45 day'));
$OPNI_DUEDATE	= date_format($date, 'm/d/Y');

$OPNI_STAT		= 3; // default 3
$OPNI_NOTES		= '';
$PRJCODE		= $default['PRJCODE'];
$JOBCODEID 		= $default['JOBCODEID'];
$SPLCODE 		= $default['SPLCODE'];

$OPNH_NUM 		= $default['OPNH_NUM'];
$OPNH_CODE 		= $default['OPNH_CODE'];

$WO_NUM 		= $default['WO_NUM'];

$OPNH_NOTE 		= $default['OPNH_NOTE'];
$OPNH_NOTE2 	= $default['OPNH_NOTE2'];
$PRJNAME 		= $default['PRJNAME'];

if($WO_NUM != '')
{
	$sqlWOH		= "SELECT WO_CODE
					FROM tbl_wo_header
					WHERE WO_NUM = '$WO_NUM' AND PRJCODE = '$PRJCODE' AND WO_STAT = 3";
	$resWOH 	= $this->db->query($sqlWOH)->result();
	foreach($resWOH as $rosWOH):
		$WO_CODE	= $rosWOH->WO_CODE;
	endforeach;
}
else
{
	$WO_CODE	= '';
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
    <link href="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/iCheck/flat/blue.css') ?>" rel="stylesheet" type="text/css" />
    <!-- Morris chart -->
    <link href="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/morris/morris.css') ?>" rel="stylesheet" type="text/css" />
    <!-- jvectormap -->
    <link href="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/jvectormap/jquery-jvectormap-1.2.2.css') ?>" rel="stylesheet" type="text/css" />
    <!-- Date Picker -->
    <link href="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/datepicker/datepicker3.css') ?>" rel="stylesheet" type="text/css" />
    <!-- Daterange picker -->
    <link href="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/daterangepicker/daterangepicker-bs3.css') ?>" rel="stylesheet" type="text/css" />
    <!-- bootstrap wysihtml5 - text editor -->
    <link href="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') ?>" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="<?php echo base_url('assets/css/ilmudetil.css') ?>">
    <script src="<?php echo base_url('assets/js/jquery-1.10.1.min.js') ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('assets/js/highcharts.js') ?>" type="text/javascript"></script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/select2/select2.min.css'; ?>">
</head>

<script src="<?php echo base_url('assets/js/jquery-1.10.1.min.js') ?>" type="text/javascript"></script>
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
		if($TranslCode == 'Cancel')$Cancel = $LangTransl;
		if($TranslCode == 'INVNo')$INVNo = $LangTransl;
		if($TranslCode == 'OpnNo')$OpnNo = $LangTransl;
		if($TranslCode == 'NoSPK')$NoSPK = $LangTransl;
		if($TranslCode == 'INVCode')$INVCode = $LangTransl;
		if($TranslCode == 'NotesOPN')$NotesOPN = $LangTransl;
		if($TranslCode == 'Notes')$Notes = $LangTransl;
		if($TranslCode == 'Date')$Date = $LangTransl;
		if($TranslCode == 'DueDate')$DueDate = $LangTransl;
		if($TranslCode == 'Project')$Project = $LangTransl;
		if($TranslCode == 'Status')$Status = $LangTransl;
		if($TranslCode == 'New')$New = $LangTransl;
		if($TranslCode == 'Confirm')$Confirm = $LangTransl;
		if($TranslCode == 'Close')$Close = $LangTransl;		
		if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
		if($TranslCode == 'ItemName')$ItemName = $LangTransl;
		if($TranslCode == 'BudgetQty')$BudgetQty = $LangTransl;
		if($TranslCode == 'SPKQty')$SPKQty = $LangTransl;
		if($TranslCode == 'QtyOpnamed')$QtyOpnamed = $LangTransl;
		if($TranslCode == 'RequestNow')$RequestNow = $LangTransl;
		if($TranslCode == 'Quantity')$Quantity = $LangTransl;
		if($TranslCode == 'Unit')$Unit = $LangTransl;
		if($TranslCode == 'Primary')$Primary = $LangTransl;
		if($TranslCode == 'Secondary')$Secondary = $LangTransl;
		if($TranslCode == 'Remarks')$Remarks = $LangTransl;
		if($TranslCode == 'JobName')$JobName = $LangTransl;
		if($TranslCode == 'ItemQty')$ItemQty = $LangTransl;
		if($TranslCode == 'Supplier')$Supplier = $LangTransl;
		if($TranslCode == 'Print')$Print = $LangTransl;
		if($TranslCode == 'Download')$Download = $LangTransl;
		if($TranslCode == 'Price')$Price = $LangTransl;
	endforeach;
	
	if($LangID == 'IND')
	{
		$subTitleH	= "Buat Faktur";
		$subTitleD	= "opname proyek";
		$Invoiced	= " sudah dibuatkan faktur";
	}
	else
	{
		$subTitleH	= "Add Invoice";
		$subTitleD	= "project opname";
		$Invoiced	= " has already been created an invoice.";
	}
	
	if(isset($_POST['OPNI_NUMX']))
	{
		$OPNI_NUM	= $_POST['OPNI_NUMX'];
		$sqlCINV	= "tbl_opn_inv WHERE OPNI_NUM = '$OPNI_NUM' AND OPNI_STAT = '3'";
		$resCINV 	= $this->db->count_all($sqlCINV);
		if($resCINV == 0)
		{
			$OPNI_NUM	= "OPN$PRJCODE$groupDate-$lastPatternNumb-F";
			$OPNI_CODE	= "$lastPatternNumb-F";		
			//setting faktur Date
			$OPNI_DATE		= date('Y-m-d',strtotime($_POST['OPNI_DATE']));
				$Patt_Year	= date('Y',strtotime($_POST['OPNI_DATE']));
				$Patt_Month	= date('m',strtotime($_POST['OPNI_DATE']));
				$Patt_Date	= date('d',strtotime($_POST['OPNI_DATE']));
			$OPNI_DUEDATE	= date('Y-m-d',strtotime($_POST['OPNI_DUEDATE']));
				$Patt_Year	= date('Y',strtotime($_POST['OPNI_DUEDATE']));
				$Patt_Month	= date('m',strtotime($_POST['OPNI_DUEDATE']));
				$Patt_Date	= date('d',strtotime($_POST['OPNI_DUEDATE']));
				
			$OPNH_NUM 		= $default['OPNH_NUM'];
			$OPNH_CODE 		= $default['OPNH_CODE'];
			$WO_NUM 		= $default['WO_NUM'];
			$WO_CODE 		= $_POST['WO_CODE'];
			$OPNI_STAT 		= $_POST['OPNI_STAT'];
			$OPNI_PAYSTAT	= "NP";
			$OPNI_NOTES		= $_POST['OPNI_NOTES'];
			$OPNI_CREATED	= date('Y-m-d H:i:s');
			$OPNI_CREATER	= $DefEmp_ID;
			$PRJCODE		= $default['PRJCODE'];
			$JOBCODEID 		= $default['JOBCODEID'];
			$SPLCODE 		= $_POST['SPLCODE'];
			$OPNI_AMOUNT	= $_POST['OPNI_AMOUNT'];
			$OPNI_PPN		= 0;
			$OPNI_AMOUNT_PAID	= 0;
			
			$STAT_BEFORE	= $_POST['STAT_BEFORE'];
			
			$yearC		= date('Y');
			$sql 		= "tbl_opn_inv WHERE Patt_Year = $yearC AND PRJCODE = '$PRJCODE'";
			$myCount 	= $this->db->count_all($sql);
			$myMax		= $myCount + 1;
			$len 		= strlen($myMax);
			
			$currentRow = 0;
			$Pattern_Length	= 5;
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
			$lastPatternNumb = $nol.$myMax;
			
			$groupDate		= date('ymd');
			
			$OPNI_NUM		= "$PRJCODE.OPN$groupDate-$lastPatternNumb-F";
			$OPNI_CODE		= "$lastPatternNumb-F";
	
			$OpniAdd 		= array('OPNI_NUM' 		=> $OPNI_NUM,
									'OPNI_CODE' 	=> $OPNI_CODE,
									'OPNI_DATE'		=> $OPNI_DATE,
									'OPNI_DUEDATE'	=> $OPNI_DUEDATE,
									'OPNH_NUM'		=> $OPNH_NUM,
									'OPNH_CODE'		=> $OPNH_CODE,
									'WO_NUM'		=> $WO_NUM,
									'WO_CODE'		=> $WO_CODE,
									'OPNI_STAT'		=> $OPNI_STAT,
									'OPNI_NOTES'	=> $OPNI_NOTES,
									'OPNI_CREATED'	=> date('Y-m-d H:i:s'),
									'OPNI_CREATER'	=> $DefEmp_ID,
									'PRJCODE'		=> $PRJCODE, 
									'JOBCODEID'		=> $JOBCODEID, 
									'SPLCODE'		=> $SPLCODE, 
									'OPNI_AMOUNT'	=> $OPNI_AMOUNT,
									'Patt_Year'		=> $Patt_Year, 
									'Patt_Month'	=> $Patt_Month,
									'Patt_Date'		=> $Patt_Date,
									'Patt_Number'	=> $myMax);
			$this->db->insert('tbl_opn_inv', $OpniAdd);
			
			// CREATE JOURNAL
			$JournalH_Code	= $OPNI_NUM;
			$JournalType	= 'OPNI';
			$JournalH_Date	= $OPNI_DATE;
			$Company_ID		= 'NKE';
			$Source			= $OPNH_NUM;
			$Emp_ID			= $DefEmp_ID;
			$LastUpdate		= $OPNI_CREATED;
			$KursAm_tobase	= 1;
			$Wh_id			= $PRJCODE;
			$REFNumb		= "OPNH";
			$RefType		= "OPNAME";
			$proj_Code		= $PRJCODE;
			
			// Save Journal Header
			$sqlGEJH 			= "INSERT INTO tbl_journalheader (JournalH_Code, JournalType, JournalH_Date, Company_ID, Source, 
									Emp_ID, LastUpdate, KursAmount_tobase, Wh_id, Reference_Number, Reference_Type, proj_Code)
									VALUES ('$JournalH_Code', '$JournalType', '$JournalH_Date', '$Company_ID', '$Source', 
									'$Emp_ID', '$LastUpdate', $KursAm_tobase, '$Wh_id', '$REFNumb', '$RefType', '$proj_Code')";
			$this->db->query($sqlGEJH);
			
			
			foreach($_POST['data'] as $d)
			{
				$this->db->insert('tbl_opn_invdet',$d);
				
				$ITM_CODE 		= $d['ITM_CODE'];
				$ACC_ID 		= $d['ACC_ID'];
				$ITM_UNIT 		= $d['ITM_UNIT'];
				$ITM_QTY 		= $d['OPNI_VOLM'];
				$ITM_PRICE 		= $d['OPNI_ITMPRICE'];
				$ITM_DISC 		= 0;
				$TAXCODE1 		= '';
				$TAXPRICE1		= 0;
				
				$JournalH_Code 		= $OPNI_NUM;
				$JournalType 		= "OPNI";
				$JournalH_Date 		= $OPNI_DATE;
				$Company_ID			= 'NKE';
				$Currency_ID		= 'IDR';
				$Source				= $OPNH_NUM;
				$Emp_ID 			= $DefEmp_ID;
				$LastUpdate 		= $OPNI_CREATED;
				$KursAm_tobase		= 1;
				$Wh_id				= $PRJCODE;
				$REFNumb 			= "OPNH";
				$RefType 			= "OPNAME";
				$proj_Code 			= $PRJCODE;
				$JSource			= "OPNI";
				$TRANS_CATEG		= "PINV";		// Journal Invoicing
				$Transaction_Date	= $OPNI_DATE;
				$Item_Code 			= $ITM_CODE;
				$ACC_ID 			= $ACC_ID;
				$Qty_Plus 			= $ITM_QTY;
				$Item_Price 		= $ITM_PRICE;
				$Item_Disc 			= 0;
				$TAXCODE1 			= '';
				$TAXPRICE1 			= 0;
				
				$ITM_AMOUNT			= ($Qty_Plus * $Item_Price) - $Item_Disc;
				$AMOUNT_PPN			= 0;
				$AMOUNT_PPh			= 0;
				if($TAXCODE1 == 'TAX01')
					$AMOUNT_PPN		= $TAXPRICE1;
				elseif($TAXCODE1 == 'TAX02')
					$AMOUNT_PPh		= $TAXPRICE1;
					
				$Unit_Price 		= $Item_Price;
				
				$transacValue 		= ($Qty_Plus * $Item_Price) - $Item_Disc;
				
				// ---------------------------- START : D E B I T ----------------------------
					$ACC_NUM	= $ACC_ID; // HUTANG USAHA PADA SUBKON
					if($ACC_NUM == '')
						$ACC_NUM	= '5207'; // UPAH RUMAH TANGGA PROYEK
						
					// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
					$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D'
									AND Journal_Type = 'NTAX'";
					$resCGEJ	= $this->db->count_all($sqlCGEJ);
					
					if($resCGEJ == 0)
					{
							$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
											JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect, 
											Journal_DK, Other_Desc)
										VALUES ('$JournalH_Code','$ACC_NUM','$proj_Code','IDR',$transacValue,$transacValue,$transacValue,
											'Default', 1, 0, 'D', 'NOT_SET_LA')";
							$this->db->query($sqlGEJDD);
					}
					else
					{
							$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Debet = JournalD_Debet+$transacValue,
												Base_Debet = Base_Debet+$transacValue, COA_Debet = COA_Debet+$transacValue
											WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' AND Journal_Type = 'NTAX'";
							$this->db->query($sqlUpdCOAD);
					}
					// START : Update to COA - Debit
						$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
											Base_Debet2 = Base_Debet2+$transacValue
										WHERE Account_Number = '$ACC_NUM'";
						$this->db->query($sqlUpdCOAD);
					// END : Update to COA - Debit
				// ---------------------------- END : D E B I T ----------------------------
				
				// ---------------------------- START : K R E D I T ----------------------------
				
					$ACC_NUM	= "2101100"; // Hutang Supplier/Sewa
					// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
						$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
										AND Journal_Type = 'NTAX'";
						$resCGEJ	= $this->db->count_all($sqlCGEJ);
						
						if($resCGEJ == 0)
						{
							// START : Save Journal Detail - Debit
								$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id,
												JournalD_Kredit, Base_Kredit, 
												COA_Kredit, CostCenter, curr_rate, isDirect, Journal_DK, Other_Desc)
											VALUES ('$JournalH_Code','$ACC_NUM','$proj_Code','IDR',$transacValue,$transacValue,
											$transacValue, 'Default', 1, 0, 'K', 'NOT_SET_LA')";
								$this->db->query($sqlGEJDD);
							// END : Save Journal Detail - Debit
						}
						else
						{
							// START : UPDATE Journal Detail - Debit
								$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Kredit = JournalD_Kredit+$transacValue,
													Base_Kredit = Base_Kredit+$transacValue, COA_Kredit = COA_Kredit+$transacValue
												WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K' AND Journal_Type = 'NTAX'";
								$this->db->query($sqlUpdCOAD);
							// END : UPDATE Journal Detail - Debit
						}
					// START : Update to COA - Debit
						$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue, 
											Base_Kredit2 = Base_Kredit2+$transacValue
										WHERE Account_Number = '$ACC_NUM'";
						$this->db->query($sqlUpdCOAD);
					// END : Update to COA - Debit
				// ---------------------------- END : K R E D I T ----------------------------
			}
			
			$OpnUpd			= array('OPNI_DATE' => $OPNI_DATE);
			$this->db->where('OPNI_NUM', $OPNI_NUM);
			$this->db->update('tbl_opn_invdet', $OpnUpd);
		}
		
		$OPNI_NUMX		= $OPNI_NUM;
	}
	else
	{
		$OPNI_NUMX		= $OPNI_NUM;
	}
	
	$sqlCINV	= "tbl_opn_inv WHERE OPNI_NUM = '$OPNI_NUMX' AND OPNI_STAT = '3'";
	$resCINV 	= $this->db->count_all($sqlCINV);
	if($resCINV > 0)
		$ISCREATED	= 1;
	else
		$ISCREATED	= 0;		
?>

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">
<style type="text/css">
	.search-table, td, th {
		border-collapse: collapse;
	}
	.search-table-outter { overflow-x: scroll; }
	
    a[disabled="disabled"] {
        pointer-events: none;
    }
</style>
<?php /*?><h1><?php echo $subTitleH; ?>
    <small><?php echo $subTitleD; ?></small>  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#">Tables</a></li>
    <li class="active">Data tables</li>
  </ol><?php */?>
</section>
<!-- Main content -->
<section class="content">
	<?php if($ISCREATED == 1)
	{
		?>
        <div class="callout callout-success" style="vertical-align:top">
            <font size="+2">Opname <?php echo $OPNH_CODE; ?></font>
    		<small><?php echo $Invoiced; ?></small>
        </div>
        <?php
	}
	else
	{
		?>
        <div class="callout callout-danger" style="vertical-align:top">
            <font size="+2"><?php echo $subTitleH; ?></font>
    		<small><?php echo $subTitleD; ?></small>
        </div>
        <?php
	}
	?>

	<div class="search-table-outter">
    <div class="box box-primary">
    	<div class="box-body chart-responsive">
        <form class="form-horizontal" name="frm" method="post" action="" enctype="multipart/form-data" onSubmit="return validateInData()">
            <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
            <input type="hidden" name="SPLCODE" id="SPLCODE" value="<?php echo $SPLCODE; ?>" />
            <input type="Hidden" name="rowCount" id="rowCount" value="0">
            	<div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label"><?php echo $INVNo; ?></label>
                    <div class="col-sm-10">
                    	<input type="text" class="form-control" style="max-width:180px;text-align:left" name="OPNI_NUM1" id="OPNI_NUM1" size="30" value="<?php echo $OPNI_NUM; ?>" disabled />
                    	<input type="hidden" class="form-control" style="max-width:180px;text-align:left" name="OPNI_NUMX" id="OPNI_NUMX" size="30" value="<?php echo $OPNI_NUM; ?>" />
                    	<input type="hidden" class="form-control" style="max-width:350px;text-align:right" name="OPNI_NUM" id="OPNI_NUM" size="30" value="<?php echo $OPNI_NUM; ?>" />
                    </div>
                </div>
            	<div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label"><?php echo $INVCode; ?></label>
                    <div class="col-sm-10">
                    	<input type="text" class="form-control" style="min-width:110px; max-width:100px; text-align:left" id="OPNI_CODE" name="OPNI_CODE" value="<?php echo $OPNI_CODE; ?>" />
                    </div>
                </div>
            	<div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Date; ?></label>
                    <div class="col-sm-10">
                    	<div class="input-group date">
                        <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>&nbsp;</div><input type="text" name="OPNI_DATE" class="form-control pull-left" id="datepicker" value="<?php echo $OPNI_DATE; ?>" style="width:106px"></div>
                    </div>
                </div>
            	<div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label"><?php echo $DueDate; ?></label>
                    <div class="col-sm-10">
                    	<div class="input-group date">
                        <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>&nbsp;</div><input type="text" name="OPNI_DUEDATE" class="form-control pull-left" id="datepicker1" value="<?php echo $OPNI_DUEDATE; ?>" style="width:106px"></div>
                    </div>
                </div>
            	<div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label"><?php echo $OpnNo; ?></label>
                    <div class="col-sm-10">
                    	<input type="text" class="form-control" style="max-width:180px;text-align:left" name="OPNH_NUM1" id="OPNH_NUM1" size="30" value="<?php echo $OPNH_NUM; ?>" disabled />
                    	<input type="hidden" class="form-control" style="max-width:350px" name="OPNH_NUM" id="OPNH_NUM" size="30" value="<?php echo $OPNH_NUM; ?>" />
                    	<input type="hidden" class="form-control" style="max-width:350px" name="OPNH_CODE" id="OPNH_CODE" size="30" value="<?php echo $OPNH_CODE; ?>" />
                    	<input type="hidden" class="form-control" style="max-width:350px" name="WO_NUM" id="WO_NUM" size="30" value="<?php echo $WO_NUM; ?>" />
                    	<input type="hidden" class="form-control" style="max-width:350px" name="WO_CODE" id="WO_CODE" size="30" value="<?php echo $WO_CODE; ?>" />
                    </div>
                </div>
            	<div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Project; ?></label>
                    <div class="col-sm-10">
                        <input type="hidden" class="form-control" style="max-width:350px;text-align:right" name="PRJCODE" id="PRJCODE" size="30" value="<?php echo $PRJCODE; ?>" />
                    	<select name="PRJCODE" id="PRJCODE" class="form-control" style="max-width:350px" onChange="chooseProject()" disabled>
                          <option value="none">--- None ---</option>
                          <?php echo $i = 0;
                            if($countPRJ > 0)
                            {
                                foreach($vwPRJ as $row) :
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
                    </div>
                </div>
            	<div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label"><?php echo $JobName; ?></label>
                    <div class="col-sm-10">
                    	<input type="hidden" name="JOBCODEID" id="JOBCODEID" value="<?php echo $JOBCODEID; ?>">
                    	<select name="JOBCODEID1" id="JOBCODEID1" class="form-control" style="max-width:350px" disabled>
                          <option value="none">--- None ---</option>
                          <?php
                            	$sqlJob	= "SELECT JOBCODEID, JOBDESC FROM tbl_joblist WHERE ISHEADER = 1 ORDER BY JOBDESC";
								$sqlJob	= $this->db->query($sqlJob)->result();
								foreach($sqlJob as $row) :
									$JOBCODEIDA		= $row->JOBCODEID;
									$JOBDESCA		= $row->JOBDESC;
									?>
										<option value="<?php echo "$JOBCODEIDA"; ?>" <?php if($JOBCODEIDA == $JOBCODEID) { ?> selected <?php } ?>>
											<?php echo "$JOBDESCA"; ?>
										</option>
									<?php
								endforeach;
                            ?>
                        </select>
                    </div>
                </div>
            	<div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Supplier; ?></label>
                    <div class="col-sm-10">
                    	<select name="SPLCODE" id="SPLCODE" class="form-control" style="max-width:350px">
                          <option value="none">--- None ---</option>
                          <?php
                            	$sqlSpl	= "SELECT SPLCODE, SPLDESC FROM tbl_supplier WHERE SPLSTAT = '1' ORDER BY SPLDESC ASC";
								$sqlSpl	= $this->db->query($sqlSpl)->result();
								foreach($sqlSpl as $row) :
									$SPLCODE1	= $row->SPLCODE;
									$SPLDESC1	= $row->SPLDESC;
									?>
										<option value="<?php echo "$SPLCODE1"; ?>" <?php if($SPLCODE1 == $SPLCODE) { ?> selected <?php } ?>>
											<?php echo "$SPLDESC1 - $SPLCODE1"; ?>
										</option>
									<?php
								endforeach;
                            ?>
                        </select>
                    </div>
                </div>
            	<div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label"><?php echo $NotesOPN; ?></label>
                    <div class="col-sm-10">
                    	<textarea name="OPNH_NOTE" id="OPNH_NOTE" class="form-control" style="max-width:350px;" cols="30" disabled><?php echo $OPNH_NOTE; ?></textarea>                        
                    </div>
                </div>
            	<div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Notes; ?></label>
                    <div class="col-sm-10">
                    	<textarea name="OPNI_NOTES" class="form-control" style="max-width:350px;" id="OPNI_NOTES" cols="30"><?php echo $OPNI_NOTES; ?></textarea>                        
                    </div>
                </div>
            	<div class="form-group" style="display:none">
                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Status; ?></label>
                    <div class="col-sm-10">
                    	<input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $OPNI_STAT; ?>">
                    	<input type="hidden" name="OPNI_STAT" id="OPNI_STAT" value="<?php echo $OPNI_STAT; ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-primary">
                        <br>
                        <table width="100%" border="1" id="example1">
                        	<tr style="background:#CCCCCC">
                              <th width="3%" height="25" rowspan="2" style="text-align:left">&nbsp;</th>
                              <th width="3%" rowspan="2" style="text-align:center"><?php echo $ItemCode ?> </th>
                              <th width="39%" rowspan="2" style="text-align:center"><?php echo $ItemName ?> </th>
                              <th colspan="4" style="text-align:center"><?php echo $ItemQty; ?> </th>
                              <th rowspan="2" style="text-align:center"><?php echo $Unit ?> </th>
                              <th width="24%" rowspan="2" style="text-align:center"><?php echo $Remarks ?> </th>
                          	</tr>
                            <tr style="background:#CCCCCC">
                              <th style="text-align:center;">SPK </th>
                              <th style="text-align:center;"><?php echo $QtyOpnamed ?> </th>
                              <th style="text-align:center">Opname</th>
                              <th style="text-align:center"><?php echo $Price ?></th>
                            </tr>
                            <?php
																		
								//*from data
								$sqlDET		= "SELECT A.JOBCODEDET, A.JOBCODEID, A.ITM_CODE, A.ITM_UNIT, A.OPND_VOLM,
													A.OPND_VOLM2, A.OPND_ITMPRICE,
													A.OPND_ITMTOTAL, A.OPND_DESC,
													B.WO_NUM, B.PRJCODE, C.ITM_NAME, C.ACC_ID
												FROM tbl_opn_detail A
													INNER JOIN tbl_opn_header B ON A.OPNH_NUM = B.OPNH_NUM
														AND B.PRJCODE = '$PRJCODE'
													INNER JOIN tbl_item C ON A.ITM_CODE = C.ITM_CODE
														AND C.PRJCODE = '$PRJCODE'
												WHERE 
													A.OPNH_NUM = '$OPNH_NUM' 
													AND B.PRJCODE = '$PRJCODE'";
								$resDETWO = $this->db->query($sqlDET)->result();
								// count data
								$sqlDETC	= "tbl_opn_detail A
													INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
												WHERE 
													A.OPNH_NUM = '$OPNH_NUM' 
													AND B.PRJCODE = '$PRJCODE'";
								$resultC 	= $this->db->count_all($sqlDETC);
								
									
								$OPNI_AMOUNT	= 0;
								if($resultC > 0)
								{
									$i		= 0;
									$j		= 0;
									
									foreach($resDETWO as $row) :
										$currentRow  	= ++$i;
										$WO_NUM 		= $row->WO_NUM;
										$PRJCODE 		= $row->PRJCODE;
										$JOBCODEDET		= $row->JOBCODEDET;
										$JOBCODEID 		= $row->JOBCODEID;
										$ACC_ID 		= $row->ACC_ID;
										$ITM_CODE 		= $row->ITM_CODE;
										$ITM_NAME 		= $row->ITM_NAME;
										$ITM_UNIT 		= $row->ITM_UNIT;
										$OPND_VOLM 		= $row->OPND_VOLM;
										$OPND_VOLM2 	= $row->OPND_VOLM2;
										$ITM_PRICE 		= $row->OPND_ITMPRICE;
										$OPNI_ITMTOTAL	= $row->OPND_ITMTOTAL;
										$OPNI_AMOUNT	= $OPNI_AMOUNT + $OPNI_ITMTOTAL;
										$OPNI_DESC 		= $row->OPND_DESC;
										$itemConvertion	= 1;
										
										?> 
										<tr id="tr_<?php echo $currentRow; ?>">
										<td width="3%" height="25" style="text-align:left">
										<?php
											if($OPNI_STAT == 1)
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
										<input type="hidden" id="chk" name="chk" value="<?php echo $currentRow; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">
											<!-- Checkbox -->
										</td>
										<td width="3%" style="text-align:left"> <!-- Item Code -->
											<?php echo $ITM_CODE; ?>
											<input type="hidden" name="data[<?php echo $currentRow; ?>][OPNI_NUM]" id="data<?php echo $currentRow; ?>OPNI_NUM" value="<?php echo $OPNI_NUM; ?>" class="form-control" style="max-width:300px;">
											<input type="hidden" name="data[<?php echo $currentRow; ?>][OPNI_CODE]" id="data<?php echo $currentRow; ?>OPNI_CODE" value="<?php echo $OPNI_CODE; ?>" class="form-control" style="max-width:300px;">
											<input type="hidden" name="data[<?php echo $currentRow; ?>][PRJCODE]" id="data<?php echo $currentRow; ?>PRJCODE" value="<?php echo $PRJCODE; ?>" class="form-control" >
											<input type="hidden" name="data[<?php echo $currentRow; ?>][JOBCODEDET]" id="data<?php echo $currentRow; ?>JOBCODEDET" value="<?php echo $JOBCODEDET; ?>" class="form-control" >
											<input type="hidden" name="data[<?php echo $currentRow; ?>][JOBCODEID]" id="data<?php echo $currentRow; ?>JOBCODEID" value="<?php echo $JOBCODEID; ?>" class="form-control" >
                                            <input type="TEXT" id="data<?php echo $currentRow; ?>ACC_ID" name="data[<?php echo $currentRow; ?>][ACC_ID]" value="<?php print $ACC_ID; ?>">
										</td>
										<td width="39%" style="text-align:left">
											<?php echo $ITM_NAME; ?>
											<input type="hidden" id="data<?php echo $currentRow; ?>ITM_CODE" name="data[<?php echo $currentRow; ?>][ITM_CODE]" value="<?php echo $ITM_CODE; ?>" class="form-control" style="max-width:300px;">
											<input type="hidden" id="data<?php echo $currentRow; ?>ITM_UNIT" name="data[<?php echo $currentRow; ?>][ITM_UNIT]" value="<?php echo $ITM_UNIT; ?>" class="form-control" style="max-width:300px;" >
											<!-- Item Name -->
										</td>
										<?php
											// TOTAL SPK YANG DIPIIH
											$TOTWOAMOUNT	= 0;
											$TOTWOQTY		= 0;
											$sqlTOTWO		= "SELECT SUM(A.WO_VOLM * A.ITM_PRICE) AS TOTWOAMOUNT, SUM(A.WO_VOLM) AS TOTWOQTY 
																FROM tbl_wo_detail A
																INNER JOIN tbl_wo_header B ON A.WO_NUM = B.WO_NUM
																	AND B.PRJCODE = '$PRJCODE'
																WHERE A.WO_NUM = '$WO_NUM' AND A.ITM_CODE = '$ITM_CODE' AND A.PRJCODE = '$PRJCODE'
																	AND A.JOBCODEDET = '$JOBCODEDET'";
											$resTOTWO		= $this->db->query($sqlTOTWO)->result();
											foreach($resTOTWO as $rowTOTWO) :
												$TOTWOAMOUNT	= $rowTOTWO->TOTWOAMOUNT;
												$TOTWOQTY		= $rowTOTWO->TOTWOQTY;
											endforeach;
											
											// TOTAL OPN APPROVED
											$TOTOPNAMOUNT	= 0;
											$TOTOPNQTY		= 0;
											$sqlTOTOPN		= "SELECT SUM(A.OPND_VOLM * A.OPND_ITMPRICE) AS TOTOPNAMOUNT, SUM(A.OPND_VOLM) AS TOTOPNQTY 
																FROM tbl_opn_detail A
																INNER JOIN tbl_opn_header B ON A.OPNH_NUM = B.OPNH_NUM
																	AND B.PRJCODE = '$PRJCODE'
																WHERE B.WO_NUM = '$WO_NUM' AND A.ITM_CODE = '$ITM_CODE' AND A.PRJCODE = '$PRJCODE'
																	AND A.JOBCODEDET = '$JOBCODEDET' AND B.OPNH_STAT = '3'";
											$resTOTOPN		= $this->db->query($sqlTOTOPN)->result();
											foreach($resTOTOPN as $rowTOTOPN) :
												$TOTOPNAMOUNT	= $rowTOTOPN->TOTOPNAMOUNT;
												$TOTOPNQTY		= $rowTOTOPN->TOTOPNQTY;
												if($TOTOPNAMOUNT == '')
													$TOTOPNAMOUNT	= 0;
												if($TOTOPNQTY == '')
													$TOTOPNQTY	= 0;
											endforeach;
											
											$REMOPNQTY		= $OPND_VOLM;
										?>
										<td width="9%" style="text-align:right" nowrap> <!-- SPK Qty -->
										  	<?php echo number_format($TOTWOQTY, $decFormat); ?>
										  	<input type="hidden" style="text-align:right" name="TOTWOQTY<?php echo $currentRow; ?>" id="TOTWOQTY<?php echo $currentRow; ?>" value="<?php echo $TOTWOQTY; ?>" >
										  	<input type="hidden" style="text-align:right" name="TOTWOAMOUNT<?php echo $currentRow; ?>" id="TOTWOAMOUNT<?php echo $currentRow; ?>" value="<?php echo $TOTWOAMOUNT; ?>" >
									  </td>
									  <td width="9%" style="text-align:right" nowrap> <!-- Opname Approved Qty-->
											<input type="hidden" class="form-control" style="text-align:right" name="TOTOPNQTY<?php echo $currentRow; ?>" id="TOTOPNQTY<?php echo $currentRow; ?>" value="<?php print $TOTOPNQTY; ?>" >
											<input type="hidden" class="form-control" style="text-align:right" name="TOTOPNAMOUNT<?php echo $currentRow; ?>" id="TOTOPNAMOUNT<?php echo $currentRow; ?>" value="<?php print $TOTOPNAMOUNT; ?>" >
											<?php print number_format($TOTOPNQTY, $decFormat); ?>
									 </td>
									 <td width="5%" nowrap style="text-align:right"> <!-- Opname Now -->
										<?php echo number_format($REMOPNQTY, $decFormat); ?>
										<input type="hidden" name="data[<?php echo $currentRow; ?>][OPNI_VOLM]" id="data<?php echo $currentRow; ?>OPNI_VOLM" value="<?php echo $REMOPNQTY; ?>" class="form-control" style="max-width:300px;" >
										<input type="hidden" name="data[<?php echo $currentRow; ?>][OPNI_ITMTOTAL]" id="data<?php echo $currentRow; ?>OPNI_ITMTOTAL" value="<?php echo $OPNI_ITMTOTAL; ?>" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" >
										</td>
									 <td width="4%" nowrap style="text-align:right">
										<input type="hidden" name="data[<?php echo $currentRow; ?>][OPNI_ITMPRICE]" id="data<?php echo $currentRow; ?>OPNI_ITMPRICE" value="<?php echo $ITM_PRICE; ?>" class="form-control" style="max-width:300px;" >
                                        <?php echo number_format($ITM_PRICE, $decFormat); ?>
                                        </td>
										<td width="4%" style="text-align:center" nowrap>
										  	<?php echo $ITM_UNIT; ?>
											<!-- Item Unit Type -- ITM_UNIT -->
                                        </td>
										<td width="24%" style="text-align:center">
											<?php print $OPNI_DESC; ?>
											<input type="hidden" name="data[<?php echo $currentRow; ?>][OPNI_DESC]" id="data<?php echo $currentRow; ?>OPNI_DESC" size="20" value="<?php print $OPNI_DESC; ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:left">
											<input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>"></td>
								  </tr>
									<?php
									endforeach;
								}
							?>
                        </table>
                      </div>
                    </div>
                </div>
            	<div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label">Total Opname</label>
                    <div class="col-sm-10">
                        <input type="hidden" class="form-control" style="min-width:110px; max-width:100px; text-align:left" id="OPNI_AMOUNT" name="OPNI_AMOUNT" value="<?php echo $OPNI_AMOUNT; ?>" /> 
                        <input type="text" class="form-control" style="min-width:150px; max-width:100px; text-align:right" id="OPNI_AMOUNT1" name="OPNI_AMOUNT1" value="<?php echo number_format($OPNI_AMOUNT, 2); ?>" disabled />                     
                    </div>
                </div>
                <br>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button class="btn btn-primary" <?php if($ISCREATED == 1) { ?> style="display:none" <?php } ?> >
                        	<i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Save; ?>
                        </button>
                        <button class="btn btn-success" <?php if($ISCREATED == 0) { ?> style="display:none" <?php } ?> >
                        	<i class="fa fa-print"></i>&nbsp;&nbsp;<?php echo $Print; ?>
                        </button>
                        <button class="btn btn-warning" <?php if($ISCREATED == 0) { ?> style="display:none" <?php } ?> >
                        	<i class="fa fa-print"></i>&nbsp;&nbsp;<?php echo $Download; ?>
                        </button>
                        <button class="btn btn-danger" type="button" onClick="window.close()">
                        	<i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>
                        </button>
                    </div>
                </div>
			</form>
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
	
	function submitForm(value)
	{
		var totrow 		= document.getElementById('totalrow').value;
		//var venCode 	= document.getElementById('Vend_Code').value;
		var isApproved 	= document.getElementById('isApproved').value;
		
		if(isApproved == 0)
		{
			for(i=1;i<=totrow;i++)
			{
				var WO_VOLM = parseFloat(document.getElementById('WO_VOLM'+i).value);
				if(WO_VOLM == 0)
				{
					swal('Please input qty of requisition.');
					document.getElementById('WO_VOLM'+i).value = '0';
					document.getElementById('WO_VOLM'+i).focus();
					return false;
				}
			}
			/*if(venCode == 0)
			{
				swal('Please select a Vendor.');
				document.getElementById('selVend_Code').focus();
				return false;
			}*/
			if(totrow == 0)
			{
				swal('Please input detail Material Request.');
				return false;		
			}
			else
			{
				document.frm.submit();
			}
		}
		else
		{
			swal('Can not update this document. The document has Confirmed.');
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
	
	function RoundNDecimal(X, N)
	{
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