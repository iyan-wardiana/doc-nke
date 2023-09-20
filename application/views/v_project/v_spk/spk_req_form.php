<?php
/*
 * Author		= Dian Hermanto
 * Create Date	= 07 Agustus 2018
 * File Name	= spk_req_form.php
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

$FlagUSER 		= $this->session->userdata['FlagUSER'];
$DefEmp_ID 		= $this->session->userdata['Emp_ID'];

$sql = "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$result = $this->db->query($sql)->result();
foreach($result as $row) :
	$PRJNAME = $row ->PRJNAME;
endforeach;

$currentRow = 0;
if($task == 'add')
{
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
	$LangID 	= $this->session->userdata['LangID'];
	if(isset($Pattern_Position))
	{
		$isSetDocNo = 1;
		if($Pattern_Position == 'Especially')
		{
			$Pattern_YearAktive 	= date('Y');
			$Pattern_MonthAktive 	= date('m');
			$Pattern_DateAktive 	= date('d');
		}
		$year 						= (int)$Pattern_YearAktive;
		$month 						= (int)$Pattern_MonthAktive;
		$date 						= (int)$Pattern_DateAktive;
	}
	else
	{
		$isSetDocNo = 0;
		$Pattern_Code 			= "XXX";
		$Pattern_Length 		= "5";
		$useYear 				= 1;
		$useMonth 				= 1;
		$useDate 				= 1;

		$Pattern_YearAktive 	= date('Y');
		$Pattern_MonthAktive 	= date('m');
		$Pattern_DateAktive 	= date('d');
		$year 					= (int)$Pattern_YearAktive;
		$month 					= (int)$Pattern_MonthAktive;
		$date 					= (int)$Pattern_DateAktive;

		if($LangID == 'IND')
		{
			$docalert1	= 'Peringatan';
			$docalert2	= 'Anda belum men-setting penomoran untuk dokumen ini. Sehingga, akan diberikan penomoran secara default dari sistem. Silahkan atur dari menu pengaturan. Silahkan atur  penomoran dokumen pada menu pengaturan.';
		}
		else
		{
			$docalert1	= 'Warning';
			$docalert2	= 'You have not set the numbering for this document. So, numbering will be given by default from the system. Please set document numbering in the Setting menu.';
		}
	}

	$yearC = (int)$Pattern_YearAktive;
	$year = substr($Pattern_YearAktive,2,2);
	$month = (int)$Pattern_MonthAktive;
	$date = (int)$Pattern_DateAktive;

	$this->db->where('Patt_Year', $year);
	//$this->db->where('Patt_Month', $month);
	//$this->db->where('Patt_Date', $date);
	$myCount = $this->db->count_all('tbl_woreq_header');

	$sql = "SELECT MAX(Patt_Number) as maxNumber FROM tbl_woreq_header
			WHERE Patt_Year = $yearC AND PRJCODE = '$PRJCODE'";
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
	$WO_NUM			= "$DocNumber";
	$WO_CODE		= "$lastPatternNumb";

	$WOCODE			= substr($lastPatternNumb, -4);
	$WOYEAR			= date('y');
	$WOMONTH		= date('m');
	$WO_CODE		= "FPA.$WOCODE.$WOYEAR.$WOMONTH"; // MANUAL CODE

	$WO_DATE		= date('m/d/Y');
	$WO_STARTD		= date('m/d/Y');
	$WO_ENDD		= date('m/d/Y');
	$PRJCODE		= $PRJCODE;
	$SPLCODE		= '';
	$WO_DEPT		= '';
	$WO_CATEG		= 'SALT';
	$WO_TYPE		= 'REQ';
	$JOBCODEID		= '';
	$PR_REFNO		= '';
	$JOBCODE1		= $PR_REFNO;
	$WO_NOTE		= '';
	$WO_STAT 		= 1;
	$Patt_Year 		= date('Y');
	$Patt_Number	= $lastPatternNumb1;
	$WO_VALUE		= 0;

	$dataColl 		= "$PRJCODE~$Pattern_Code~tbl_woreq_header~$Pattern_Length";
	$dataTarget		= "WO_CODE";
}
else
{
	$isSetDocNo = 1;
	$WO_NUM 	= $default['WO_NUM'];
	$DocNumber	= $default['WO_NUM'];
	$WO_CODE 	= $default['WO_CODE'];
	$WO_DATE 	= $default['WO_DATE'];
	$WO_STARTD 	= $default['WO_STARTD'];
	$WO_ENDD 	= $default['WO_ENDD'];
	$WO_DATE	= date('m/d/Y', strtotime($WO_DATE));
	$WO_STARTD	= date('m/d/Y', strtotime($WO_STARTD));
	$WO_ENDD	= date('m/d/Y', strtotime($WO_ENDD));

	$PRJCODE	= $default['PRJCODE'];
	$SPLCODE 	= $default['SPLCODE'];
	$WO_DEPT 	= $default['WO_DEPT'];
	$WO_CATEG 	= $default['WO_CATEG'];
	$WO_TYPE 	= $default['WO_TYPE'];
	$JOBCODEID 	= $default['JOBCODEID'];
	$PR_REFNO	= $default['JOBCODEID'];
	$JOBCODE1	= $PR_REFNO;
	$WO_NOTE 	= $default['WO_NOTE'];
	$WO_NOTE2 	= $default['WO_NOTE2'];
	$WO_VALUE	= $default['WO_VALUE'];
	$WO_STAT 	= $default['WO_STAT'];
	$WO_MEMO 	= $default['WO_MEMO'];
	$PRJNAME 	= $default['PRJNAME'];
	$Patt_Year 	= $default['Patt_Year'];
	$Patt_Month = $default['Patt_Month'];
	$Patt_Date 	= $default['Patt_Date'];
	$Patt_Number= $default['Patt_Number'];
}

if(isset($_POST['JOBCODE1']))
{
	$PR_REFNO	= $_POST['JOBCODE1'];
	echo "PR_REFNO = $PR_REFNO";
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
		$this->load->view('template/topbar');
		$this->load->view('template/sidebar');

		$ISREAD 	= $this->session->userdata['ISREAD'];
		$ISCREATE 	= $this->session->userdata['ISCREATE'];
		$ISAPPROVE 	= $this->session->userdata['ISAPPROVE'];
		$ISDWONL 	= $this->session->userdata['ISDWONL'];
		$ISDELETE	= $this->session->userdata['ISDELETE'];
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
			if($TranslCode == 'WONo')$WONo = $LangTransl;
			if($TranslCode == 'Category')$Category = $LangTransl;
			if($TranslCode == 'Type')$Type = $LangTransl;
			if($TranslCode == 'RequestCode')$RequestCode = $LangTransl;
			if($TranslCode == 'RequestCode')$RequestCode = $LangTransl;
			if($TranslCode == 'Notes')$Notes = $LangTransl;
			if($TranslCode == 'Date')$Date = $LangTransl;
			if($TranslCode == 'Project')$Project = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'AlertRevisionorReject')$AlertRevisionorReject = $LangTransl;
			if($TranslCode == 'New')$New = $LangTransl;
			if($TranslCode == 'Confirm')$Confirm = $LangTransl;
			if($TranslCode == 'Close')$Close = $LangTransl;
			if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
			if($TranslCode == 'ItemName')$ItemName = $LangTransl;
			if($TranslCode == 'BudgetQty')$BudgetQty = $LangTransl;
			if($TranslCode == 'Planning')$Planning = $LangTransl;
			if($TranslCode == 'Requested')$Requested = $LangTransl;
			if($TranslCode == 'RequestNow')$RequestNow = $LangTransl;
			if($TranslCode == 'Quantity')$Quantity = $LangTransl;
			if($TranslCode == 'Unit')$Unit = $LangTransl;
			if($TranslCode == 'Primary')$Primary = $LangTransl;
			if($TranslCode == 'Secondary')$Secondary = $LangTransl;
			if($TranslCode == 'Amount')$Amount = $LangTransl;
			if($TranslCode == 'AddItem')$AddItem = $LangTransl;
			if($TranslCode == 'JobName')$JobName = $LangTransl;
			if($TranslCode == 'ItemQty')$ItemQty = $LangTransl;
			if($TranslCode == 'StartDate')$StartDate = $LangTransl;
			if($TranslCode == 'EndDate')$EndDate = $LangTransl;
			if($TranslCode == 'Approval')$Approval = $LangTransl;
			if($TranslCode == 'Approved')$Approved = $LangTransl;
			if($TranslCode == 'NotYetApproved')$NotYetApproved = $LangTransl;
			if($TranslCode == 'Awaiting')$Awaiting = $LangTransl;
			if($TranslCode == 'Price')$Price = $LangTransl;
			if($TranslCode == 'Supplier')$Supplier = $LangTransl;
			if($TranslCode == 'Tax')$Tax = $LangTransl;
			if($TranslCode == 'Remarks')$Remarks = $LangTransl;
		endforeach;

		if($LangID == 'IND')
		{
			$subTitleH	= "Tambah";
			$subTitleD	= "permintaan alat";
			$alert1		= "Silahkan masukan volume pekerjaan.";
			$alert2		= "Silahkan masukan detail pekerjaan.";
			$alert3		= "Silahkan pilih pekerjaan.";
			$alert4		= "Silahkan pilih Supplier.";
			$isManual	= "Centang untuk kode manual.";
			$InOthSett	= "Dalam pengaturan lain";
		}
		else
		{
			$subTitleH	= "Add";
			$subTitleD	= "request tools";
			$alert1		= "Please input qty of job volume.";
			$alert2		= "Please input job detail.";
			$alert3		= "Please select Job.";
			$alert4		= "Please select a Supplier.";
			$isManual	= "Check to manual code.";
			$InOthSett	= "In other settings";
		}

		// CEK ACCESS OTORIZATION
			$resAPP	= 0;
			$sqlAPP	= "tbl_docstepapp_det WHERE MENU_CODE = '$MenuCode1'
						AND PRJCODE = '$PRJCODE' AND APPROVER_1 = '$DefEmp_ID'";
			$resAPP	= $this->db->count_all($sqlAPP);

		$IS_LAST	= 0;
		$APP_LEVEL	= 0;
		$APPROVER_1	= '';
		$APPROVER_2	= '';
		$APPROVER_3	= '';
		$APPROVER_4	= '';
		$APPROVER_5	= '';
		$disableAll	= 1;
		$sqlCAPP	= "tbl_docstepapp WHERE MENU_CODE = '$MenuCode1' AND PRJCODE = '$PRJCODE'";
		$resCAPP	= $this->db->count_all($sqlCAPP);
		if($resCAPP > 0)
		{
			$sqlAPP	= "SELECT * FROM tbl_docstepapp WHERE MENU_CODE = '$MenuCode1' AND PRJCODE = '$PRJCODE'";
			$resAPP	= $this->db->query($sqlAPP)->result();
			foreach($resAPP as $rowAPP) :
				$MAX_STEP	= $rowAPP->MAX_STEP;
				$APPROVER_1		= $rowAPP->APPROVER_1;
				if($APPROVER_1 != '')
				{
					$EMPN_1		= '';
					$sqlEMPC_1	= "tbl_employee WHERE Emp_ID = '$APPROVER_1' AND Emp_Status = '1'";
					$resEMPC_1	= $this->db->count_all($sqlEMPC_1);
					if($resEMPC_1 > 0)
					{
						$sqlEMP_1	= "SELECT First_Name, Last_Name FROM tbl_employee WHERE Emp_ID = '$APPROVER_1' AND Emp_Status = '1' LIMIT 1";
						$resEMP_1	= $this->db->query($sqlEMP_1)->result();
						foreach($resEMP_1 as $rowEMP) :
							$FN_1	= $rowEMP->First_Name;
							$LN_1	= $rowEMP->Last_Name;
						endforeach;
						$EMPN_1		= "$FN_1 $LN_1";
					}
					$APPLIMIT_1	= $rowAPP->APPLIMIT_1;
					if($APPROVER_1	== $DefEmp_ID)
					{
						$APPROVER_PR	= $APPROVER_1;
						$APP_LEVEL		= 1;
					}
				}
				$APPROVER_2	= $rowAPP->APPROVER_2;
				if($APPROVER_2 != '')
				{
					$EMPN_2		= '';
					$sqlEMPC_2	= "tbl_employee WHERE Emp_ID = '$APPROVER_2' AND Emp_Status = '1'";
					$resEMPC_2	= $this->db->count_all($sqlEMPC_2);
					if($resEMPC_2 > 0)
					{
						$sqlEMP_2	= "SELECT First_Name, Last_Name FROM tbl_employee WHERE Emp_ID = '$APPROVER_2' AND Emp_Status = '1' LIMIT 1";
						$resEMP_2	= $this->db->query($sqlEMP_2)->result();
						foreach($resEMP_2 as $rowEMP) :
							$FN_2	= $rowEMP->First_Name;
							$LN_2	= $rowEMP->Last_Name;
						endforeach;
						$EMPN_2		= "$FN_2 $LN_2";
					}
					$APPLIMIT_2	= $rowAPP->APPLIMIT_2;
					if($APPROVER_2	== $DefEmp_ID)
					{
						$APPROVER_PR	= $APPROVER_2;
						$APP_LEVEL		= 2;
					}
				}
				$APPROVER_3	= $rowAPP->APPROVER_3;
				if($APPROVER_3 != '')
				{
					$EMPN_3		= '';
					$sqlEMPC_3	= "tbl_employee WHERE Emp_ID = '$APPROVER_3' AND Emp_Status = '1'";
					$resEMPC_3	= $this->db->count_all($sqlEMPC_3);
					if($resEMPC_3 > 0)
					{
						$sqlEMP_3	= "SELECT First_Name, Last_Name FROM tbl_employee WHERE Emp_ID = '$APPROVER_3' AND Emp_Status = '1' LIMIT 1";
						$resEMP_3	= $this->db->query($sqlEMP_3)->result();
						foreach($resEMP_3 as $rowEMP) :
							$FN_3	= $rowEMP->First_Name;
							$LN_3	= $rowEMP->Last_Name;
						endforeach;
						$EMPN_3		= "$FN_3 $LN_3";
					}
					$APPLIMIT_3	= $rowAPP->APPLIMIT_3;
					if($APPROVER_3	== $DefEmp_ID)
					{
						$APPROVER_PR	= $APPROVER_3;
						$APP_LEVEL		= 3;
					}
				}
				$APPROVER_4	= $rowAPP->APPROVER_4;
				if($APPROVER_4 != '')
				{
					$EMPN_4		= '';
					$sqlEMPC_4	= "tbl_employee WHERE Emp_ID = '$APPROVER_4' AND Emp_Status = '1'";
					$resEMPC_4	= $this->db->count_all($sqlEMPC_4);
					if($resEMPC_4 > 0)
					{
						$sqlEMP_4	= "SELECT First_Name, Last_Name FROM tbl_employee WHERE Emp_ID = '$APPROVER_4' AND Emp_Status = '1' LIMIT 1";
						$resEMP_4	= $this->db->query($sqlEMP_4)->result();
						foreach($resEMP_4 as $rowEMP) :
							$FN_4	= $rowEMP->First_Name;
							$LN_4	= $rowEMP->Last_Name;
						endforeach;
						$EMPN_4		= "$FN_4 $LN_4";
					}
					$APPLIMIT_4	= $rowAPP->APPLIMIT_4;
					if($APPROVER_4	== $DefEmp_ID)
					{
						$APPROVER_PR	= $APPROVER_4;
						$APP_LEVEL		= 4;
					}
				}
				$APPROVER_5	= $rowAPP->APPROVER_5;
				if($APPROVER_5 != '')
				{
					$EMPN_5		= '';
					$sqlEMPC_5	= "tbl_employee WHERE Emp_ID = '$APPROVER_5' AND Emp_Status = '1'";
					$resEMPC_5	= $this->db->count_all($sqlEMPC_5);
					if($resEMPC_5 > 0)
					{
						$sqlEMP_5	= "SELECT First_Name, Last_Name FROM tbl_employee WHERE Emp_ID = '$APPROVER_5' AND Emp_Status = '1' LIMIT 1";
						$resEMP_5	= $this->db->query($sqlEMP_5)->result();
						foreach($resEMP_5 as $rowEMP) :
							$FN_5	= $rowEMP->First_Name;
							$LN_5	= $rowEMP->Last_Name;
						endforeach;
						$EMPN_5		= "$FN_5 $LN_5";
					}
					$APPLIMIT_5	= $rowAPP->APPLIMIT_5;
					if($APPROVER_4	== $DefEmp_ID)
					{
						$APPROVER_PR	= $APPROVER_4;
						$APP_LEVEL		= 4;
					}
				}
			endforeach;

			if($MAX_STEP == $APP_LEVEL)
			{
				$IS_LAST		= 1;
			}
			$disableAll	= 0;
		}
		$secAddURL	= site_url('c_project/c_s180d0bpk/add/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
		$secGenCode	= base_url().'index.php/c_project/c_s180d0bpk/genCode/'; // Generate Code
		$secGetJID	= base_url().'index.php/c_project/c_s180d0bpk/getJID/'; // Generate Code
	?>
    
    <body class="<?php echo $appBody; ?>">
        <div class="content-wrapper">
			<section class="content-header">
			<h1>
			    <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/wo.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $subTitleH; ?>
			    <small><?php echo $subTitleD; ?></small>  </h1>
			  <?php /*?><ol class="breadcrumb">
			    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			    <li><a href="#">Tables</a></li>
			    <li class="active">Data tables</li>
			  </ol><?php */?>
			</section>

			<section class="content">
			    <div class="box box-primary">
			    	<div class="box-body chart-responsive">
			        <form method="post" name="sendDate" id="sendDate" class="form-user" action="<?php echo $secGenCode; ?>" style="display:none">
			            <table>
			                <tr>
			                    <td>
			                        <input type="hidden" name="PRJCODEX" id="PRJCODEX" value="<?php echo $PRJCODE; ?>">
			                        <input type="hidden" name="Pattern_Code" id="Pattern_Code" value="<?php echo $Pattern_Code; ?>">
			                        <input type="hidden" name="Pattern_Length" id="Pattern_Length" value="<?php echo $Pattern_Length; ?>">
			                        <input type="hidden" name="useYear" id="useYear" value="<?php echo $useYear; ?>">
			                        <input type="hidden" name="useMonth" id="useMonth" value="<?php echo $useMonth; ?>">
			                        <input type="hidden" name="useDate" id="useDate" value="<?php echo $useDate; ?>">
			                        <input type="hidden" name="WODate" id="WODate" value="">
			                    </td>
			                    <td><a class="tombol-date" id="dateClass">Simpan</a></td>
			                </tr>
			            </table>
			        </form>
			        <form method="post" name="collJID" id="collJID" class="form-user" action="<?php echo $secGetJID; ?>" style="display:none">
			            <table>
			                <tr>
			                    <td>
			                        <input type="hidden" name="WODate" id="WODate" value="">
			                    </td>
			                    <td><a class="tombol-date" id="dateClass">Simpan</a></td>
			                </tr>
			            </table>
			        </form>
			        <!-- after get Supplier code -->
			        <form name="frmsrch1" id="frmsrch1" action="" method=POST style="display:none">
			            <input type="text" name="PRJCODE1" id="PRJCODE1" value="<?php echo $PRJCODE; ?>" />
			            <input type="text" name="JOBCODE1" id="JOBCODE1" value="<?php echo $PR_REFNO; ?>" />
			            <input type="submit" class="button_css" name="submitSrch1" id="submitSrch1" value=" search " />
			        </form>
			        <!-- End -->
			        <form class="form-horizontal" name="frm" method="post" action="<?php echo $form_action; ?>" enctype="multipart/form-data" onSubmit="return validateInData()">
			            <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
			            <input type="hidden" name="Patt_Number" id="Patt_Number" value="<?php echo $Patt_Number; ?>" />
			            <input type="Hidden" name="rowCount" id="rowCount" value="0">
							<?php if($isSetDocNo == 0) { ?>
			                <div class="form-group">
			                    <label for="inputName" class="col-sm-2 control-label">&nbsp;</label>
			                    <div class="col-sm-10">
			                        <div class="alert alert-danger alert-dismissible">
			                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			                            <h4><i class="icon fa fa-ban"></i> <?php echo $docalert1; ?>!</h4>
			                            <?php echo $docalert2; ?>
			                        </div>
			                    </div>
			                </div>
			                <?php } ?>
			            	<div class="form-group" style="display:none">
			                    <label for="inputName" class="col-sm-2 control-label"><?php echo $WONo; ?></label>
			                    <div class="col-sm-10">
			                    	<input type="text" class="form-control" style="text-align:left" name="WO_NUM1" id="WO_NUM1" size="30" value="<?php echo $DocNumber; ?>" disabled />
			                    	<input type="hidden" class="form-control" style="text-align:right" name="WO_NUM" id="WO_NUM" size="30" value="<?php echo $DocNumber; ?>" />
			                    </div>
			                </div>
			            	<div class="form-group">
			                    <label for="inputName" class="col-sm-2 control-label"><?php echo $RequestCode; ?></label>
			                    <div class="col-sm-10">
			                        <label>
			                            <input type="text" class="form-control" style="min-width:110px; max-width:150px; text-align:left" id="WO_CODE" name="WO_CODE" value="<?php echo $WO_CODE; ?>" readonly />
			                        </label>
			                        <label style="display:none;">
			                            &nbsp;&nbsp;<input type="checkbox" name="isManual" id="isManual">
			                        </label>
			                        <label style="font-style:italic;display:none;">
			                            <?php echo $isManual; ?>
			                        </label>
			                    </div>
			                </div>
			            	<div class="form-group">
			                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Date; ?></label>
			                    <div class="col-sm-10">
			                    	<div class="input-group date">
			                        <div class="input-group-addon">
			                        <i class="fa fa-calendar"></i>&nbsp;</div><input type="text" name="WO_DATE" class="form-control pull-left" id="datepicker" value="<?php echo $WO_DATE; ?>" style="width:106px"></div>
			                    </div>
			                </div>
			            	<div class="form-group">
			                    <label for="inputName" class="col-sm-2 control-label"><?php echo $StartDate; ?></label>
			                    <div class="col-sm-10">
			                    	<div class="input-group date">
			                        <div class="input-group-addon">
			                        <i class="fa fa-calendar"></i>&nbsp;</div><input type="text" name="WO_STARTD" class="form-control pull-left" id="datepicker1" value="<?php echo $WO_STARTD; ?>" style="width:106px"></div>
			                    </div>
			                </div>
			            	<div class="form-group">
			                    <label for="inputName" class="col-sm-2 control-label"><?php echo $EndDate; ?></label>
			                    <div class="col-sm-10">
			                    	<div class="input-group date">
			                        <div class="input-group-addon">
			                        <i class="fa fa-calendar"></i>&nbsp;</div><input type="text" name="WO_ENDD" class="form-control pull-left" id="datepicker2" value="<?php echo $WO_ENDD; ?>" style="width:106px"></div>
			                    </div>
			                </div>
			            	<div class="form-group" style="display:none">
			                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Category; ?></label>
			                    <div class="col-sm-10">
			                    	<select name="WO_CATEG" id="WO_CATEG" class="form-control select2" onChange="changeCODE(this.value)" >
			                          <option value="MDR" <?php if($WO_CATEG == 'MDR') { ?> selected <?php } ?> disabled> Mandor</option>
			                          <option value="SALT" selected> Sewa Alat</option>
			                          <option value="SUB" <?php if($WO_CATEG == 'SUB') { ?> selected <?php } ?> disabled> Sub Contractor</option>
			                        </select>
			                    </div>
			                </div>
			            	<div class="form-group" style="display:none">
			                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Type; ?></label>
			                    <div class="col-sm-10">
			                    	<select name="WO_TYPE" id="WO_TYPE" class="form-control select2" >
			                          <option value="NSA"> Pekerjaan</option>
			                          <option value="SA" selected> Sewa Alat</option>
			                        </select>
			                    </div>
			                </div>
			                <script>
								function changeCODE(thisVal)
								{
									var WOCODE		= document.getElementById('WO_CODE').value;
									var len 		= WOCODE.length;
									var WO_CODE 	= WOCODE.substring(2, len);
									if(thisVal == 'MDR')
										WO_CODEN	= 'PK'+WO_CODE;
									else if(thisVal == 'SUB')
										WO_CODEN	= 'KK'+WO_CODE;
									else
										WO_CODEN	= 'SA'+WO_CODE;

									document.getElementById('WO_CODE').value = WO_CODEN;
								}
							</script>
			            	<div class="form-group" style="display:none">
			                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Supplier; ?></label>
			                    <div class="col-sm-10">
			                    	<select name="SPLCODE" id="SPLCODE" class="form-control select2" >
			                          <option value="">--- None ---</option>
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
			                <script>
								function getWO_NUM(selDate)
								{
									document.getElementById('WODate').value = selDate;
									document.getElementById('dateClass').click();
								}

								$(document).ready(function()
								{
									$(".tombol-date").click(function()
									{
										var add_PR	= "<?php echo $secGenCode; ?>";
										var formAction 	= $('#sendDate')[0].action;
										var data = $('.form-user').serialize();
										$.ajax({
											type: 'POST',
											url: formAction,
											data: data,
											success: function(response)
											{
												var myarr = response.split("~");
												document.getElementById('WO_NUM1').value = myarr[0];
												document.getElementById('WO_CODE').value = myarr[1];
											}
										});
									});
								});
							</script>
			            	<div class="form-group">
			                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Project; ?></label>
			                    <div class="col-sm-10">
			                        <input type="hidden" class="form-control" style="max-width:400px;text-align:right" name="PRJCODE" id="PRJCODE" size="30" value="<?php echo $PRJCODE; ?>" />
			                    	<select name="PRJCODE" id="PRJCODE" class="form-control select2" onChange="chooseProject()" disabled>
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
			                    <input type="hidden" class="form-control" style="max-width:400px;text-align:right" id="PRJCODE" name="PRJCODE" size="20" value="<?php echo $PRJCODE; ?>" />
			                    </div>
			                </div>
			            	<div class="form-group">
			                    <label for="inputName" class="col-sm-2 control-label"><?php echo $JobName; ?></label>
			                    <div class="col-sm-10">
			                        <select name="PR_REFNO[]" id="PR_REFNO" class="form-control select2" multiple="multiple" data-placeholder="&nbsp;<?php echo $JobName; ?>" onBlur="selJOB(this.value)">
			                        	<option value="">--- None ---</option>
										<?php
			                                $Disabled_1	= 0;
			                                $sqlJob_1	= "SELECT JOBCODEID, JOBPARENT, JOBLEV, JOBDESC FROM tbl_joblist
															WHERE ISHEADER = '1' AND PRJCODE  = '$PRJCODE'";
			                                $resJob_1	= $this->db->query($sqlJob_1)->result();
			                                foreach($resJob_1 as $row_1) :
			                                    $JOBCODEID_1	= $row_1->JOBCODEID;
			                                    $JOBPARENT_1	= $row_1->JOBPARENT;
			                                    $JOBLEV_1		= $row_1->JOBLEV;
			                                    $JOBDESC_1		= $row_1->JOBDESC;

			                                    if($JOBLEV_1 == 1)
												{
													$space_level_1	= "";
												}
												elseif($JOBLEV_1 == 2)
												{
													$space_level_1	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
												}
												elseif($JOBLEV_1 == 3)
												{
													$space_level_1	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
												}
												elseif($JOBLEV_1 == 4)
												{
													$space_level_1	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
												}
												elseif($JOBLEV_1 == 5)
												{
													$space_level_1	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
												}

												$JIDExplode = explode('~', $PR_REFNO);
												$JOBCODE1	= '';
												$SELECTED	= 0;
												foreach($JIDExplode as $i => $key)
												{
													$JOBCODE1	= $key;
													if($JOBCODEID_1 == $JOBCODE1)
													{
														$SELECTED	= 1;
													}
												}

			                                    $sqlC_2			= "tbl_joblist WHERE JOBPARENT = '$JOBCODEID_1' AND ISHEADER = '1'
																	 AND PRJCODE  = '$PRJCODE'";
			                                    $resC_2 		= $this->db->count_all($sqlC_2);
			                                    if($resC_2 > 0)
			                                        $Disabled_1 = 1;
												else
													$Disabled_1 = 0;
			                                    ?>
			                                    <option value="<?php echo "$JOBCODEID_1"; ?>" <?php if($SELECTED == 1) { ?> selected <?php } if($Disabled_1 == 1) {?> disabled <?php } ?> title="<?php echo $JOBDESC_1; ?>">
			                                        <?php echo "$space_level_1 $JOBCODEID_1 : $JOBDESC_1"; ?>
			                                    </option>
			                                    <?php
			                                endforeach;
			                            ?>
			                        </select>
			                    </div>
			                </div>
							<script>
			                    function selJOB(PR_REFNO)
			                    {
			                        PR_REFNO1	= document.getElementById("PR_REFNO").value;
			                        document.getElementById("JOBCODE1").value = PR_REFNO;
			                        PRJCODE	= document.getElementById("PRJCODE").value
			                        document.getElementById("PRJCODE1").value = PRJCODE;
			                        document.frmsrch1.submitSrch1.click();
			                    }
			                </script>
			            	<div class="form-group">
			                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Notes; ?></label>
			                    <div class="col-sm-10">
			                    	<textarea name="WO_NOTE" class="form-control" id="WO_NOTE" cols="30"><?php echo $WO_NOTE; ?></textarea>
			                    </div>
			                </div>
			            	<div class="form-group">
			                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Status; ?></label>
			                    <div class="col-sm-10"><input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $WO_STAT; ?>">
									<?php
										$isDisabled = 1;
										if($WO_STAT == 1 || $WO_STAT == 4)
										{
											$isDisabled = 0;
										}
										?>
											<select name="WO_STAT" id="WO_STAT" class="form-control" onChange="chkSTAT(this.value)">
												<?php
												$disableBtn	= 0;
												if($WO_STAT == 5 || $WO_STAT == 6 || $WO_STAT == 9)
												{
													$disableBtn	= 1;
												}
												if($WO_STAT != 1 AND $WO_STAT != 4)
												{
													?>
														<option value="1"<?php if($WO_STAT == 1) { ?> selected <?php } ?> disabled>New</option>
														<option value="2"<?php if($WO_STAT == 2) { ?> selected <?php } ?> disabled>Confirm</option>
														<option value="3"<?php if($WO_STAT == 3) { ?> selected <?php } ?> disabled>Approve</option>
														<option value="4"<?php if($WO_STAT == 4) { ?> selected <?php } ?> disabled>Revising</option>
														<option value="5"<?php if($WO_STAT == 5) { ?> selected <?php } ?> disabled>Rejected</option>
														<option value="6"<?php if($WO_STAT == 6) { ?> selected <?php } if($disableBtn == 1) {?> disabled <?php } ?>>Closed</option>
														<option value="7"<?php if($WO_STAT == 7) { ?> selected <?php } ?> disabled>Waiting</option>
														<option value="9"<?php if($WO_STAT == 9) { ?> selected <?php } if($disableBtn == 1) {?> disabled <?php } ?>>Void</option>
													<?php
												}
												else
												{
													?>
														<option value="1"<?php if($WO_STAT == 1) { ?> selected <?php } ?>>New</option>
														<option value="2"<?php if($WO_STAT == 2) { ?> selected <?php } ?>>Confirm</option>
													<?php
												}
												?>
											</select>
										<?php
										$theProjCode 	= "$PRJCODE~$PR_REFNO";
			                        	$url_AddItem	= site_url('c_project/c_s180d0bpk/p0_fp4pUp4llItm/?id=');
			                        ?>
			                    </div>
			                </div>
							<script>
			                    function chkSTAT(selSTAT)
			                    {
									if(selSTAT == 5 || selSTAT == 9)
									{
										document.getElementById('btnREJECT').style.display 	= '';
										//document.getElementById('AppNotes').style.display 	= '';
										//document.getElementById('PR_NOTE2').disabled		= false;
									}
									else if(selSTAT == 6)
									{
										document.getElementById('btnREJECT').style.display 	= '';
										//document.getElementById('AppNotes').style.display 	= '';
									}
									else
									{
										//document.getElementById('AppNotes').style.display 	= 'none';
									}
			                    }
			                </script>
			                <div class="form-group" <?php if($isDisabled == 1) { ?> style="display:none" <?php } ?>>
			                    <label for="inputName" class="col-sm-2 control-label">&nbsp;</label>
			                    <div class="col-sm-10">
			                        <script>
			                            var url = "<?php echo $url_AddItem;?>";

			                            function selectitem()
			                            {
											SPLCODE		= document.getElementById('SPLCODE').value;
											if(SPLCODE == '')
											{
												//swal('<?php echo $alert4; ?>');
												//return false;
											}

											WO_CATEG	= document.getElementById("WO_CATEG").value;

											PR_REFNO1	= $('#PR_REFNO').val();
											if(PR_REFNO1 == null)
											{
												swal('<?php echo $alert3; ?>',
												{
													icon: "warning",
												});
												return false;
											}

											PR_REFNO 	= PR_REFNO1.join("~");
											PRJCODE		= document.getElementById('PRJCODE').value;

											if(PR_REFNO == '')
											{
												swal('Silahkan pilih nama pekerjaan',
												{
													icon: "warning",
												});
												document.getElementById('PR_REFNO').focus();
												return false;
											}
			                                title = 'Select Item';
			                                w = 1000;
			                                h = 550;
			                                //window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
			                                var left = (screen.width/2)-(w/2);
			                                var top = (screen.height/2)-(h/2);
											return window.open(url+PR_REFNO+'&pr1h0ec0JcoDe='+PRJCODE+'&pgfrm='+WO_CATEG, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
			                            }
			                        </script>
			                        <button class="btn btn-success" type="button" onClick="selectitem();">
			                        <i class="glyphicon glyphicon-th-list"></i>&nbsp;&nbsp;<?php echo $AddItem; ?>
			                        </button>
			                        </div>
			                </div>
			                <div class="row">
			                    <div class="col-md-12">
			                        <div class="box box-primary">
			                        <br>
			                        <table width="100%" border="1" id="tbl">
			                        	<tr style="background:#CCCCCC">
			                                <th width="2%" height="25" rowspan="2" style="text-align:left">&nbsp;</th>
			                              	<th width="2%" rowspan="2" style="text-align:center; display:none"><?php echo $ItemCode ?> </th>
			                              	<th width="29%" rowspan="2" style="text-align:center"><?php echo $ItemName ?> </th>
			                              	<th colspan="4" style="text-align:center"><?php echo $ItemQty; ?> </th>
			                              	<th rowspan="2" style="text-align:center"><?php echo $Unit ?> </th>
			                              	<th rowspan="2" style="text-align:center"><?php echo $Amount ?></th>
			                                <th rowspan="2" style="text-align:center">Total</th>
			                              	<th width="18%" rowspan="2" style="text-align:center;">
			                                <?php
			                                	//echo $Tax ;
												echo $Remarks;
			                                ?>
			                                </th>
			                          	</tr>
			                            <tr style="background:#CCCCCC">
			                              <th style="text-align:center;"><?php echo $Planning; ?> </th>
			                              <th style="text-align:center;"><?php echo $Requested; ?> </th>
			                              <th style="text-align:center"><?php echo $RequestNow; ?></th>
			                              <th style="text-align:center"><?php echo $Price; ?></th>
			                            </tr>
			                            <?php
			                            if($task == 'edit')
			                            {
			                                $sqlDET	= "SELECT A.*,
															B.ITM_NAME,
															C.JOBDESC
														FROM tbl_woreq_detail A
															INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
																AND B.PRJCODE = '$PRJCODE'
															INNER JOIN tbl_joblist_detail C ON A.JOBCODEID = C.JOBCODEID
																AND C.PRJCODE = '$PRJCODE'
															INNER JOIN tbl_woreq_header D ON D.WO_NUM = A.WO_NUM
        														AND D.JOBCODEID = C.JOBPARENT
														WHERE A.WO_NUM = '$WO_NUM'
															AND A.PRJCODE = '$PRJCODE'";
			                                $result = $this->db->query($sqlDET)->result();
			                                $i		= 0;
			                                $j		= 0;

											foreach($result as $row) :
												$currentRow  	= ++$i;
												$WO_NUM 		= $row->WO_NUM;
												$WO_CODE 		= $row->WO_CODE;
												$WO_DATE 		= $row->WO_DATE;
												$PRJCODE 		= $row->PRJCODE;
												$JOBCODEDET		= $row->JOBCODEDET;
												$JOBCODEID 		= $row->JOBCODEID;
												$ITM_CODE 		= $row->ITM_CODE;
												$ITM_NAME 		= $row->ITM_NAME;
												$ITM_NAME 		= $row->JOBDESC;
												$SNCODE 		= $row->SNCODE;
												$ITM_UNIT 		= $row->ITM_UNIT;
												$WO_VOLM 		= $row->WO_VOLM;
												$WO_VOLM2 		= $row->WO_VOLM2;
												$ITM_PRICE 		= $row->ITM_PRICE;
												$WO_TOTAL 		= $row->WO_TOTAL;
												$WO_DESC 		= $row->WO_DESC;
												$ITM_BUDG		= $row->ITM_BUDG;
												$TAXCODE1		= $row->TAXCODE1;
												$TAXCODE2		= $row->TAXCODE2;
												$TAXPRICE1		= $row->TAXPRICE1;
												$TAXPRICE2		= $row->TAXPRICE2;
												$WO_TOTAL2		= $row->WO_TOTAL2;
												$itemConvertion	= 1;

											/*	if ($j==1) {
													echo "<tr class=zebra1>";
													$j++;
												} else {
													echo "<tr class=zebra2>";
													$j--;
												}*/
												?>
			                                    <tr id="tr_<?php echo $currentRow; ?>">
												<td width="2%" height="25" style="text-align:left">
												  <?php
													if($WO_STAT == 1)
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
											    <input type="hidden" id="chk" name="chk" value="<?php echo $currentRow; ?>" width="10" size="15" readonly class="form-control" style="max-width:400px;text-align:right">
			                                    	<!-- Checkbox -->
			                                 	</td>
											 	<td width="2%" style="text-align:left; display:none"> <!-- Item Code -->
												  	<?php echo $ITM_CODE; ?>
			                                      	<input type="hidden" name="data[<?php echo $currentRow; ?>][WO_NUM]" id="data<?php echo $currentRow; ?>WO_NUM" value="<?php echo $WO_NUM; ?>" class="form-control" style="max-width:300px;">
			                                      	<input type="hidden" name="data[<?php echo $currentRow; ?>][WO_CODE]" id="data<?php echo $currentRow; ?>WO_CODE" value="<?php echo $WO_CODE; ?>" class="form-control" style="max-width:300px;">
			                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][JOBCODEDET]" id="data<?php echo $currentRow; ?>JOBCODEDET" value="<?php echo $JOBCODEDET; ?>" class="form-control" >
			                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][JOBCODEID]" id="data<?php echo $currentRow; ?>JOBCODEID" value="<?php echo $JOBCODEID; ?>" class="form-control" >
			                                  	</td>
											  	<td width="29%" style="text-align:left">
													<?php echo $ITM_NAME; ?>
			                                      	<input type="hidden" id="data<?php echo $currentRow; ?>ITM_CODE" name="data[<?php echo $currentRow; ?>][ITM_CODE]" value="<?php echo $ITM_CODE; ?>" class="form-control" style="max-width:300px;">
			                                      	<input type="hidden" id="data<?php echo $currentRow; ?>SNCODE" name="data[<?php echo $currentRow; ?>][SNCODE]" value="<?php echo $SNCODE; ?>" class="form-control" style="max-width:300px;">
			                                        <!-- Item Name -->
			                                 	</td>
												<?php
													// CARI TOTAL WORKED BUDGET APPROVED
													$TOTPRAMOUNT	= 0;
													$TOTPRQTY		= 0;
													$sqlTOTBUDG		= "SELECT SUM(A.WO_VOLM * A.ITM_PRICE) AS TOTWOAMOUNT, SUM(A.WO_VOLM) AS TOTWOQTY FROM tbl_wo_detail A
																		INNER JOIN tbl_woreq_header B ON A.WO_NUM = B.WO_NUM
																			AND B.PRJCODE = '$PRJCODE'
																		WHERE A.ITM_CODE = '$ITM_CODE' AND A.PRJCODE = '$PRJCODE' AND A.JOBCODEDET = '$JOBCODEDET' AND B.WO_STAT = '3'";
													$resTOTBUDG		= $this->db->query($sqlTOTBUDG)->result();
													foreach($resTOTBUDG as $rowTOTBUDG) :
														$TOTWOAMOUNT	= $rowTOTBUDG->TOTWOAMOUNT;
														$TOTWOQTY		= $rowTOTBUDG->TOTWOQTY;
													endforeach;

													$TOT_USEBUDG	= $TOTPRAMOUNT;					// 15
													$ITM_BUDG		= $row->ITM_BUDG;				// 16
													if($ITM_BUDG == '')
														$ITM_BUDG	= 0;

													if($PRJCODE == 'KTR')
													{
														$sqlITM		= "SELECT DISTINCT ITM_VOLM FROM tbl_item
																		WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE' LIMIT 1";
													}
													else
													{
														$sqlITM		= "SELECT DISTINCT ITM_VOLM FROM tbl_joblist_detail
																		WHERE PRJCODE = '$PRJCODE' AND JOBCODEDET = '$JOBCODEDET' LIMIT 1";
													}
													$ITM_BUDQTY		= 0;
													$resITM			= $this->db->query($sqlITM)->result();
													foreach($resITM as $rowITM) :
														$ITM_BUDQTY	= $rowITM->ITM_VOLM;
													endforeach;

													// SISA QTY PR
													$REMPRQTY		= $ITM_BUDQTY - $TOTPRQTY;
												?>
												<td width="7%" style="text-align:right"> <!-- Item Bdget -->
			                                        <input type="text" class="form-control" style="min-width:100px; max-width:100px; text-align:right" name="ITM_BUDGQTYx<?php echo $currentRow; ?>" id="ITM_BUDGQTYx<?php echo $currentRow; ?>" value="<?php echo number_format($ITM_BUDQTY, $decFormat); ?>" size="10" disabled >
			                                        <input type="hidden" style="text-align:right" name="ITM_BUDGQTY<?php echo $currentRow; ?>" id="ITM_BUDGQTY<?php echo $currentRow; ?>" value="<?php echo $ITM_BUDQTY; ?>" >
			                                        <input type="hidden" id="data<?php echo $currentRow; ?>ITM_BUDG" name="data[<?php echo $currentRow; ?>][ITM_BUDG]" value="<?php echo $ITM_BUDG; ?>">
			                                  	</td>
											  	<td width="7%" style="text-align:right">  <!-- Item Requested FOR INFORMATION ONLY -->
			                                        <input type="text" class="form-control" style="min-width:100px; max-width:100px; text-align:right" name="TOTWOQTY<?php echo $currentRow; ?>" id="TOTWOQTYx<?php echo $currentRow; ?>" value="<?php print number_format($TOTPRQTY, $decFormat); ?>" size="10" disabled >
			                                        <input type="hidden" class="form-control" style="min-width:100px; max-width:100px; text-align:right" name="ITM_REQUESTED<?php echo $currentRow; ?>" id="ITM_REQUESTED<?php echo $currentRow; ?>" value="<?php print $TOTWOAMOUNT; ?>" >
			                                        <input type="hidden" class="form-control" style="text-align:right" name="TOTWOQTY<?php echo $currentRow; ?>" id="TOTWOQTY<?php echo $currentRow; ?>" value="<?php print $TOTWOQTY; ?>" >
			                                 	</td>
			                                     <td width="12%" style="text-align:right"> <!-- Item Request Now -- PR_VOLM -->
			                                        <input type="text" name="WO_VOLM<?php echo $currentRow; ?>" id="WO_VOLM<?php echo $currentRow; ?>" value="<?php echo number_format($WO_VOLM, $decFormat); ?>" class="form-control" style="min-width:100px; max-width:100px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,<?php echo $currentRow; ?>);" >
			                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][WO_VOLM]" id="data<?php echo $currentRow; ?>WO_VOLM" value="<?php echo $WO_VOLM; ?>" class="form-control" style="max-width:300px;" >
			                                        </td>
			                                     <td width="6%" style="text-align:center; font-style:italic">
			                                     	none
			                                        <input type="hidden" name="ITM_PRICE<?php echo $currentRow; ?>" id="ITM_PRICE<?php echo $currentRow; ?>" value="<?php echo number_format($ITM_PRICE, $decFormat); ?>" class="form-control" style="min-width:100px; max-width:100px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion_1(this,<?php echo $currentRow; ?>);" >
			                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][ITM_PRICE]" id="data<?php echo $currentRow; ?>ITM_PRICE" value="<?php echo $ITM_PRICE; ?>" class="form-control" style="min-width:100px; max-width:100px;" >
			                                        <input type="hidden" style="text-align:right" name="itemConvertion<?php echo $currentRow; ?>" id="itemConvertion<?php echo $currentRow; ?>" value="<?php echo $itemConvertion; ?>" >
			                                     </td>
											  	<td width="3%" nowrap style="text-align:center">
												  <?php echo $ITM_UNIT; ?>
			                                    	<input type="hidden" name="data[<?php echo $currentRow; ?>][ITM_UNIT]" id="data<?php echo $currentRow; ?>ITM_UNIT" value="<?php echo $ITM_UNIT; ?>" class="form-control" style="max-width:300px;" >
			                                    <!-- Item Unit Type -- ITM_UNIT --></td>
											  	<td width="6%" nowrap style="text-align:center; font-style:italic">
			                                    	none
			                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][WO_TOTAL]" id="data<?php echo $currentRow; ?>WO_TOTAL" value="<?php echo $WO_TOTAL; ?>" class="form-control" style="max-width:150px; text-align:right" onKeyPress="return isIntOnlyNew(event);" >
			                                    	<input type="hidden" class="form-control" style="min-width:100px; max-width:100px; text-align:right" name="WO_TOTAL<?php echo $currentRow; ?>" id="WO_TOTAL<?php echo $currentRow; ?>" value="<?php print number_format($WO_TOTAL, $decFormat); ?>" size="10" disabled >
			                                    </td>
											  	<td width="8%" nowrap style="text-align:right; font-style:italic">
			                                        <select name="data[<?php echo $currentRow; ?>][TAXCODE1]" id="TAXCODE1<?php echo $currentRow; ?>" class="form-control" style="max-width:150px; display:none" onChange="getConvertion(this,<?php echo $currentRow; ?>);">
			                                        	<option value=""> no tax </option>
			                                        	<option value="TAX01" <?php if($TAXCODE1 == 'TAX01') { ?> selected <?php } ?>>PPn 10%</option>
			                                        	<option value="TAX02" <?php if($TAXCODE1 == 'TAX02') { ?> selected <?php } ?>>PPh 3%</option>
			                                        </select>
			                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][TAXPRICE1]" id="data<?php echo $currentRow; ?>TAXPRICE1" size="20" value="<?php echo $TAXPRICE1; ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:left">
			                                        <!-- TOTAL --->
			                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][WO_TOTAL2]" id="data<?php echo $currentRow; ?>WO_TOTAL2" value="<?php echo $WO_TOTAL2; ?>" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" >
			                                        <input type="hidden" name="data<?php echo $currentRow; ?>WO_TOTAL2A" id="WO_TOTAL2A<?php echo $currentRow; ?>" value="<?php echo number_format($WO_TOTAL2, 2); ?>" class="form-control" style="min-width:130px; max-width:130px; text-align:right;" onKeyPress="return isIntOnlyNew(event);" disabled >
			                                    </td>
											  	<td width="18%" style="text-align:center;">
			                                    	<input type="text" name="data[<?php echo $currentRow; ?>][WO_DESC]" id="data<?php echo $currentRow; ?>WO_DESC" value="<?php echo $WO_DESC; ?>" class="form-control" style="max-width:300px;" >
			                              		</td>
										  </tr>
			                              	<?php
			                             	endforeach;
											?>
			                                	<input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">
			                                <?php
			                            }
			                            if($task == 'add')
			                            {
			                                ?>
			                                  <input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">
			                              <?php
			                            }
			                            ?>
			                        </table>
			                      </div>
			                    </div>
			                </div>
			            	<div class="form-group" style="display:none">
			                    <label for="inputName" class="col-sm-2 control-label">Total - SPK</label>
			                    <div class="col-sm-10">
			                    	<input type="hidden" name="WO_VALUE" id="WO_VALUE" class="form-control" style="max-width:150px; text-align:right" value="<?php echo $WO_VALUE; ?>">
			                    	<input type="text" name="WO_VALUE1" id="WO_VALUE1" class="form-control" style="max-width:150px; text-align:right" value="<?php echo number_format($WO_VALUE, 2); ?>" disabled>
			                    </div>
			                </div>
			                <br>
			                <div class="form-group">
			                    <div class="col-sm-offset-2 col-sm-10">
			                        <?php
										if($task=='add')
										{
											if(($WO_STAT == 1 || $WO_STAT == 4) && $ISCREATE == 1)
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
											if(($WO_STAT == 1 || $WO_STAT == 4) && $ISCREATE == 1)
											{
												?>
													<button class="btn btn-primary" >
													<i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Update; ?>
													</button>&nbsp;
												<?php
											}
											elseif($WO_STAT == 3 && $ISDELETE == 1)
											{
												?>
													<button class="btn btn-primary" id="btnREJECT" style="display:none" >
													<i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Update; ?>
													</button>&nbsp;
												<?php
											}
											elseif($resAPP == 1)
											{
												?>
													<button class="btn btn-primary" id="btnREJECT" style="display:none" >
													<i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Update; ?>
													</button>&nbsp;
												<?php
											}
										}

										$backURL	= site_url('c_project/c_s180d0bpk/r3q_70ls_1ll1a/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
										echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i>&nbsp;&nbsp;'.$Back.'</button>');
			                        ?>
			                    </div>
			                </div>
			                <?php
								$DOC_NUM	= $WO_NUM;
			                    $sqlCAPPH	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM'";
			                    $resCAPPH	= $this->db->count_all($sqlCAPPH);
			                ?>
			                <div class="row">
			                    <div class="col-md-12">
			                        <div class="box box-danger collapsed-box">
			                            <div class="box-header with-border">
			                                <h3 class="box-title"><?php echo $Approval; ?></h3>
			                                <div class="box-tools pull-right">
			                                    <span class="label label-danger"><?php echo "$Approved : $resCAPPH "; ?></span>
			                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
			                                    </button>
			                                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
			                                    </button>
			                                </div>
			                            </div>
			                            <div class="box-body">
			                            <?php
											$SHOWOTH		= 0;
											$AH_ISLAST		= 0;
											$APPROVER_1A	= 0;
											$APPROVER_2A	= 0;
											$APPROVER_3A	= 0;
											$APPROVER_4A	= 0;
											$APPROVER_5A	= 0;
			                                if($APPROVER_1 != '')
			                                {
			                                    $boxCol_1	= "red";
			                                    $sqlCAPPH_1	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_1'";
			                                    $resCAPPH_1	= $this->db->count_all($sqlCAPPH_1);
			                                    if($resCAPPH_1 > 0)
			                                    {
			                                        $boxCol_1	= "green";
			                                        $Approver	= $Approved;
			                                        $class		= "glyphicon glyphicon-ok-sign";

			                                        $sqlAPPH_1	= "SELECT AH_APPROVED, AH_ISLAST
																	FROM tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_1'";
			                                        $resAPPH_1	= $this->db->query($sqlAPPH_1)->result();
			                                        foreach($resAPPH_1 as $rowAPPH_1):
			                                            $APPROVED_1	= $rowAPPH_1->AH_APPROVED;
			                                            $AH_ISLAST	= $rowAPPH_1->AH_ISLAST;
			                                        endforeach;
			                                    }
			                                    elseif($resCAPPH_1 == 0)
			                                    {
													$Approver	= $NotYetApproved;
													$class		= "glyphicon glyphicon-remove-sign";
													$APPROVED_1	= "Not Set";

													$sqlCAPPH_1A	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = 1";
													$resCAPPH_1A	= $this->db->count_all($sqlCAPPH_1A);
													if($resCAPPH_1A > 0)
													{
														$SHOWOTH	= 1;
														$APPROVER_1A= 1;
														$EMPN_1A	= '';
														$AH_ISLAST1A=0;
														$APPROVED_1A= '0000-00-00';
														$boxCol_1A	= "green";
														$Approver1A	= $Approved;
														$class1A	= "glyphicon glyphicon-ok-sign";

														$sqlAPPH_1A	= "SELECT A.AH_APPROVER, A.AH_APPROVED, A.AH_ISLAST,
																			CONCAT(B.First_Name, ' ', B.Last_Name) AS COMPNAME
																		FROM tbl_approve_hist A
																			INNER JOIN tbl_employee B ON A.AH_APPROVER = B.Emp_ID
																		WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = 1";
														$resAPPH_1A	= $this->db->query($sqlAPPH_1A)->result();
														foreach($resAPPH_1A as $rowAPPH_1A):
															$EMPN_1A		= $rowAPPH_1A->COMPNAME;
															$AH_ISLAST1A	= $rowAPPH_1A->AH_ISLAST;
															$APPROVED_1A	= $rowAPPH_1A->AH_APPROVED;
														endforeach;
													}
			                                    }
												?>
													<div class="col-md-3">
														<div class="info-box bg-<?php echo $boxCol_1; ?>">
															<span class="info-box-icon"><i class="<?php echo $class; ?>"></i></span>
															<div class="info-box-content">

																<span class="info-box-text"><?php echo $Approver; ?></span>
																<span class="info-box-number"><?php echo cut_text ("$EMPN_1", 20); ?></span>
																<div class="progress">
																	<div class="progress-bar" style="width: 50%"></div>
																</div>
																<span class="progress-description">
																	<?php echo $APPROVED_1; ?>
																</span>
															</div>
														</div>
													</div>
												<?php
			                                }
			                                if($APPROVER_2 != '' && $AH_ISLAST == 0)
			                                {
			                                    $boxCol_2	= "red";
			                                    $sqlCAPPH_2	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_2'";
			                                    $resCAPPH_2	= $this->db->count_all($sqlCAPPH_2);
			                                    if($resCAPPH_2 > 0)
			                                    {
			                                        $boxCol_2	= "green";
			                                        $class		= "glyphicon glyphicon-ok-sign";

			                                        $sqlAPPH_2	= "SELECT AH_APPROVED FROM tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_2'";
			                                        $resAPPH_2	= $this->db->query($sqlAPPH_2)->result();
			                                        foreach($resAPPH_2 as $rowAPPH_2):
			                                            $APPROVED_2	= $rowAPPH_2->AH_APPROVED;
			                                        endforeach;
			                                    }
			                                    elseif($resCAPPH_2 == 0)
			                                    {
			                                        $Approver	= $NotYetApproved;
			                                        $class		= "glyphicon glyphicon-remove-sign";
			                                        $APPROVED_2	= "Not Set";

													$sqlCAPPH_2A	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = 2";
													$resCAPPH_2A	= $this->db->count_all($sqlCAPPH_2A);
													if($resCAPPH_2A > 0)
													{
														$APPROVER_2A= 1;
														$EMPN_2A	= '';
														$AH_ISLAST2A=0;
														$APPROVED_2A= '0000-00-00';
														$boxCol_2A	= "green";
														$Approver2A	= $Approved;
														$class2A	= "glyphicon glyphicon-ok-sign";

														$sqlAPPH_2A	= "SELECT A.AH_APPROVER, A.AH_APPROVED, A.AH_ISLAST,
																			CONCAT(B.First_Name, ' ', B.Last_Name) AS COMPNAME
																		FROM tbl_approve_hist A
																			INNER JOIN tbl_employee B ON A.AH_APPROVER = B.Emp_ID
																		WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = 2";
														$resAPPH_2A	= $this->db->query($sqlAPPH_2A)->result();
														foreach($resAPPH_2A as $rowAPPH_2A):
															$EMPN_2A		= $rowAPPH_2A->COMPNAME;
															$AH_ISLAST2A	= $rowAPPH_2A->AH_ISLAST;
															$APPROVED_2A	= $rowAPPH_2A->AH_APPROVED;
														endforeach;
													}
												}

			                                    /*if($resCAPPH == 0)
			                                    {
			                                        $Approver	= $Awaiting;
			                                        $boxCol_2	= "yellow";
			                                        $class		= "glyphicon glyphicon-info-sign";
			                                    }*/
												?>
													<div class="col-md-3">
														<div class="info-box bg-<?php echo $boxCol_2; ?>">
															<span class="info-box-icon"><i class="<?php echo $class; ?>"></i></span>
															<div class="info-box-content">
																<span class="info-box-text"><?php echo $Approver; ?></span>
																<span class="info-box-number"><?php echo cut_text ("$EMPN_2", 20); ?></span>
																<div class="progress">
																	<div class="progress-bar" style="width: 50%"></div>
																</div>
																<span class="progress-description">
																	<?php echo $APPROVED_2; ?>
																</span>
															</div>
														</div>
													</div>
												<?php
			                                }
			                                if($APPROVER_3 != '' && $AH_ISLAST == 0)
			                                {
			                                    $boxCol_3	= "red";
			                                    $sqlCAPPH_3	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_3'";
			                                    $resCAPPH_3	= $this->db->count_all($sqlCAPPH_3);
			                                    if($resCAPPH_3 > 0)
			                                    {
			                                        $boxCol_3	= "green";
			                                        $class		= "glyphicon glyphicon-ok-sign";

			                                        $sqlAPPH_3	= "SELECT AH_APPROVED FROM tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_3'";
			                                        $resAPPH_3	= $this->db->query($sqlAPPH_3)->result();
			                                        foreach($resAPPH_3 as $rowAPPH_3):
			                                            $APPROVED_3	= $rowAPPH_3->AH_APPROVED;
			                                        endforeach;
			                                    }
			                                    elseif($resCAPPH_3 == 0)
			                                    {
			                                        $Approver	= $NotYetApproved;
			                                        $class		= "glyphicon glyphicon-remove-sign";
			                                        $APPROVED_3	= "Not Set";

													$sqlCAPPH_3A	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = 3";
													$resCAPPH_3A	= $this->db->count_all($sqlCAPPH_3A);
													if($resCAPPH_3A > 0)
													{
														$APPROVER_3A= 1;
														$EMPN_3A	= '';
														$AH_ISLAST3A=0;
														$APPROVED_3A= '0000-00-00';
														$boxCol_3A	= "green";
														$Approver3A	= $Approved;
														$class3A	= "glyphicon glyphicon-ok-sign";

														$sqlAPPH_3A	= "SELECT A.AH_APPROVER, A.AH_APPROVED, A.AH_ISLAST,
																			CONCAT(B.First_Name, ' ', B.Last_Name) AS COMPNAME
																		FROM tbl_approve_hist A
																			INNER JOIN tbl_employee B ON A.AH_APPROVER = B.Emp_ID
																		WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = 3";
														$resAPPH_3A	= $this->db->query($sqlAPPH_3A)->result();
														foreach($resAPPH_3A as $rowAPPH_3A):
															$EMPN_3A		= $rowAPPH_3A->COMPNAME;
															$AH_ISLAST3A	= $rowAPPH_3A->AH_ISLAST;
															$APPROVED_3A	= $rowAPPH_3A->AH_APPROVED;
														endforeach;
													}
			                                    }

			                                    /*if($resCAPPH == 1)
			                                    {
			                                        $Approver	= $Awaiting;
			                                        $boxCol_3	= "yellow";
			                                        $class		= "glyphicon glyphicon-info-sign";
			                                    }*/
												?>
													<div class="col-md-3">
														<div class="info-box bg-<?php echo $boxCol_3; ?>">
															<span class="info-box-icon"><i class="<?php echo $class; ?>"></i></span>
															<div class="info-box-content">
																<span class="info-box-text"><?php echo $Approver; ?></span>
																<span class="info-box-number"><?php echo cut_text ("$EMPN_3", 20); ?></span>
																<div class="progress">
																	<div class="progress-bar" style="width: 50%"></div>
																</div>
																<span class="progress-description">
																	<?php echo $APPROVED_3; ?>
																</span>
															</div>
														</div>
													</div>
												<?php
			                                }
			                                if($APPROVER_4 != '' && $AH_ISLAST == 0)
			                                {
			                                    $boxCol_4	= "red";
			                                    $sqlCAPPH_4	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_4'";
			                                    $resCAPPH_4	= $this->db->count_all($sqlCAPPH_4);
			                                    if($resCAPPH_4 > 0)
			                                    {
			                                        $boxCol_4	= "green";
			                                        $class		= "glyphicon glyphicon-ok-sign";

			                                        $sqlAPPH_4	= "SELECT AH_APPROVED FROM tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_4'";
			                                        $resAPPH_4	= $this->db->query($sqlAPPH_4)->result();
			                                        foreach($resAPPH_4 as $rowAPPH_4):
			                                            $APPROVED_4	= $rowAPPH_4->AH_APPROVED;
			                                        endforeach;
			                                    }
			                                    elseif($resCAPPH_4 == 0)
			                                    {
			                                        $Approver	= $NotYetApproved;
			                                        $class		= "glyphicon glyphicon-remove-sign";
			                                        $APPROVED_4	= "Not Set";

													$sqlCAPPH_4A	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = 4";
													$resCAPPH_4A	= $this->db->count_all($sqlCAPPH_4A);
													if($resCAPPH_4A > 0)
													{
														$APPROVER_4A= 1;
														$EMPN_4A	= '';
														$AH_ISLAST4A=0;
														$APPROVED_4A= '0000-00-00';
														$boxCol_4A	= "green";
														$Approver4A	= $Approved;
														$class4A	= "glyphicon glyphicon-ok-sign";

														$sqlAPPH_4A	= "SELECT A.AH_APPROVER, A.AH_APPROVED, A.AH_ISLAST,
																			CONCAT(B.First_Name, ' ', B.Last_Name) AS COMPNAME
																		FROM tbl_approve_hist A
																			INNER JOIN tbl_employee B ON A.AH_APPROVER = B.Emp_ID
																		WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = 4";
														$resAPPH_4A	= $this->db->query($sqlAPPH_4A)->result();
														foreach($resAPPH_4A as $rowAPPH_4A):
															$EMPN_4A		= $rowAPPH_4A->COMPNAME;
															$AH_ISLAST4A	= $rowAPPH_4A->AH_ISLAST;
															$APPROVED_4A	= $rowAPPH_4A->AH_APPROVED;
														endforeach;
													}
			                                    }

			                                    /*if($resCAPPH == 2)
			                                    {
			                                        $Approver	= $Awaiting;
			                                        $boxCol_4	= "yellow";
			                                        $class		= "glyphicon glyphicon-info-sign";
			                                    }*/
												?>
													<div class="col-md-3">
														<div class="info-box bg-<?php echo $boxCol_4; ?>">
															<span class="info-box-icon"><i class="<?php echo $class; ?>"></i></span>
															<div class="info-box-content">
																<span class="info-box-text"><?php echo $Approver; ?></span>
																<span class="info-box-number"><?php echo cut_text ("$EMPN_4", 20); ?></span>
																<div class="progress">
																	<div class="progress-bar" style="width: 50%"></div>
																</div>
																<span class="progress-description">
																	<?php echo $APPROVED_4; ?>
																</span>
															</div>
														</div>
													</div>
												<?php
			                                }
			                                if($APPROVER_5 != '' && $AH_ISLAST == 0)
			                                {
			                                    $boxCol_5	= "red";
			                                    $sqlCAPPH_5	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_5'";
			                                    $resCAPPH_5	= $this->db->count_all($sqlCAPPH_5);
			                                    if($resCAPPH_5 > 0)
			                                    {
			                                        $boxCol_5	= "green";
			                                        $class		= "glyphicon glyphicon-ok-sign";

			                                        $sqlAPPH_5	= "SELECT AH_APPROVED FROM tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_5'";
			                                        $resAPPH_5	= $this->db->query($sqlAPPH_5)->result();
			                                        foreach($resAPPH_5 as $rowAPPH_5):
			                                            $APPROVED_5	= $rowAPPH_5->AH_APPROVED;
			                                        endforeach;
			                                    }
			                                    elseif($resCAPPH_5 == 0)
			                                    {
			                                        $Approver	= $NotYetApproved;
			                                        $class		= "glyphicon glyphicon-remove-sign";
			                                        $APPROVED_5	= "Not Set";

													$sqlCAPPH_5A	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = 5";
													$resCAPPH_5A	= $this->db->count_all($sqlCAPPH_5A);
													if($resCAPPH_5A > 0)
													{
														$APPROVER_5A= 1;
														$EMPN_5A	= '';
														$AH_ISLAST5A=0;
														$APPROVED_5A= '0000-00-00';
														$boxCol_5A	= "green";
														$Approver5A	= $Approved;
														$class5A	= "glyphicon glyphicon-ok-sign";

														$sqlAPPH_5A	= "SELECT A.AH_APPROVER, A.AH_APPROVED, A.AH_ISLAST,
																			CONCAT(B.First_Name, ' ', B.Last_Name) AS COMPNAME
																		FROM tbl_approve_hist A
																			INNER JOIN tbl_employee B ON A.AH_APPROVER = B.Emp_ID
																		WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = 5";
														$resAPPH_5A	= $this->db->query($sqlAPPH_5A)->result();
														foreach($resAPPH_5A as $rowAPPH_5A):
															$EMPN_5A		= $rowAPPH_5A->COMPNAME;
															$AH_ISLAST5A	= $rowAPPH_5A->AH_ISLAST;
															$APPROVED_5A	= $rowAPPH_5A->AH_APPROVED;
														endforeach;
													}
			                                    }

			                                    /*if($resCAPPH == 3)
			                                    {
			                                        $Approver	= $Awaiting;
			                                        $boxCol_5	= "yellow";
			                                        $class		= "glyphicon glyphicon-info-sign";
			                                    }*/
												?>
													<div class="col-md-3">
														<div class="info-box bg-<?php echo $boxCol_5; ?>">
															<span class="info-box-icon"><i class="<?php echo $class; ?>"></i></span>
															<div class="info-box-content">
																<span class="info-box-text"><?php echo $Approver; ?></span>
																<span class="info-box-number"><?php echo cut_text ("$EMPN_5", 20); ?></span>
																<div class="progress">
																	<div class="progress-bar" style="width: 50%"></div>
																</div>
																<span class="progress-description">
																	<?php echo $APPROVED_5; ?>
																</span>
															</div>
														</div>
													</div>
												<?php
			                                }
			                            ?>
			                            </div>
			                        </div>
			                    </div>
			                </div>
			                <?php if($SHOWOTH == 1) { ?>
			                    <div class="row">
			                        <div class="col-md-12">
			                            <div class="box box-danger collapsed-box">
			                                <div class="box-header with-border">
			                                    <h3 class="box-title"><?php echo $InOthSett; ?></h3>
			                                    <div class="box-tools pull-right">
			                                        <span class="label label-danger"><?php echo "$Approved : $resCAPPH "; ?></span>
			                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
			                                        </button>
			                                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
			                                        </button>
			                                    </div>
			                                </div>
			                                <div class="box-body">
			                                <?php
			                                    if($APPROVER_1A == 1)
			                                    {
			                                        ?>
			                                            <div class="col-md-3">
			                                                <div class="info-box bg-<?php echo $boxCol_1A; ?>">
			                                                    <span class="info-box-icon"><i class="<?php echo $class1A; ?>"></i></span>
			                                                    <div class="info-box-content">
			                                                        <span class="info-box-text"><?php echo $Approver1A; ?></span>
			                                                        <span class="info-box-number"><?php echo cut_text ("$EMPN_1A", 20); ?></span>
			                                                        <div class="progress">
			                                                            <div class="progress-bar" style="width: 50%"></div>
			                                                        </div>
			                                                        <span class="progress-description">
			                                                            <?php echo $APPROVED_1A; ?>
			                                                        </span>
			                                                    </div>
			                                                </div>
			                                            </div>
			                                        <?php
			                                    }
			                                    if($APPROVER_2A == 1)
			                                    {
			                                        ?>
			                                            <div class="col-md-3">
			                                                <div class="info-box bg-<?php echo $boxCol_2A; ?>">
			                                                    <span class="info-box-icon"><i class="<?php echo $class2A; ?>"></i></span>
			                                                    <div class="info-box-content">
			                                                        <span class="info-box-text"><?php echo $Approver2A; ?></span>
			                                                        <span class="info-box-number"><?php echo cut_text ("$EMPN_2A", 20); ?></span>
			                                                        <div class="progress">
			                                                            <div class="progress-bar" style="width: 50%"></div>
			                                                        </div>
			                                                        <span class="progress-description">
			                                                            <?php echo $APPROVED_2A; ?>
			                                                        </span>
			                                                    </div>
			                                                </div>
			                                            </div>
			                                        <?php
			                                    }
			                                    if($APPROVER_3A == 1)
			                                    {
			                                        ?>
			                                            <div class="col-md-3">
			                                                <div class="info-box bg-<?php echo $boxCol_3A; ?>">
			                                                    <span class="info-box-icon"><i class="<?php echo $class3A; ?>"></i></span>
			                                                    <div class="info-box-content">
			                                                        <span class="info-box-text"><?php echo $Approver3A; ?></span>
			                                                        <span class="info-box-number"><?php echo cut_text ("$EMPN_3A", 20); ?></span>
			                                                        <div class="progress">
			                                                            <div class="progress-bar" style="width: 50%"></div>
			                                                        </div>
			                                                        <span class="progress-description">
			                                                            <?php echo $APPROVED_3A; ?>
			                                                        </span>
			                                                    </div>
			                                                </div>
			                                            </div>
			                                        <?php
			                                    }
			                                    if($APPROVER_4A == 1)
			                                    {
			                                        ?>
			                                            <div class="col-md-3">
			                                                <div class="info-box bg-<?php echo $boxCol_4A; ?>">
			                                                    <span class="info-box-icon"><i class="<?php echo $class4A; ?>"></i></span>
			                                                    <div class="info-box-content">
			                                                        <span class="info-box-text"><?php echo $Approver4A; ?></span>
			                                                        <span class="info-box-number"><?php echo cut_text ("$EMPN_4A", 20); ?></span>
			                                                        <div class="progress">
			                                                            <div class="progress-bar" style="width: 50%"></div>
			                                                        </div>
			                                                        <span class="progress-description">
			                                                            <?php echo $APPROVED_4A; ?>
			                                                        </span>
			                                                    </div>
			                                                </div>
			                                            </div>
			                                        <?php
			                                    }
			                                    if($APPROVER_5A == 1)
			                                    {
			                                        ?>
			                                            <div class="col-md-3">
			                                                <div class="info-box bg-<?php echo $boxCol_5A; ?>">
			                                                    <span class="info-box-icon"><i class="<?php echo $class5A; ?>"></i></span>
			                                                    <div class="info-box-content">
			                                                        <span class="info-box-text"><?php echo $Approver5A; ?></span>
			                                                        <span class="info-box-number"><?php echo cut_text ("$EMPN_5A", 20); ?></span>
			                                                        <div class="progress">
			                                                            <div class="progress-bar" style="width: 50%"></div>
			                                                        </div>
			                                                        <span class="progress-description">
			                                                            <?php echo $APPROVED_5A; ?>
			                                                        </span>
			                                                    </div>
			                                                </div>
			                                            </div>
			                                        <?php
			                                    }
			                                ?>
			                                </div>
			                            </div>
			                        </div>
			                    </div>
			                <?php } ?>
						</form>
			    	</div>
			    </div>
			</section>
		</div>
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
	$('#datepicker').datepicker({
	  autoclose: true
	});

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

	<?php
	if($task == 'add')
	{
		?>
		$(document).ready(function()
		{
			setInterval(function(){getNewCode()}, 1000);
		});

		function getNewCode()
		{
				var task						= "<?=$task?>";
				var JournalType			= "";
				var dataTarget			= "WO_CODE";
				var myProj_code			= "PRJCODE";
				var PRJCODE					= "<?=$PRJCODE?>";
				var Pattern_Code		= "<?=$Pattern_Code?>";
				var PattTable 			= "tbl_woreq_header";
				var Pattern_Length	= "<?=$Pattern_Length?>";
				var TRXDATE					= "WO_DATE";
				var DATE 						= $('#datepicker').val();
				var isManual 				= $('#isManual').prop('checked');

				if(isManual == true){
					$('#'+dataTarget).focus();
				}else{
					$.ajax({
						url: "<?php echo base_url().'index.php/__l1y/GetCodeDoc/';?>",
						type: "POST",
						data: {task:task, JournalType:JournalType, myProj_code:myProj_code, PRJCODE:PRJCODE, Pattern_Code:Pattern_Code, PattTable:PattTable, Pattern_Length:Pattern_Length, TRXDATE:TRXDATE, DATE:DATE},
						success: function(data){
							$('#'+dataTarget).val(data);
						}
					});
				}

		}

		function getNewCode1()
		{
			var	PRJCODE		= '<?php echo $dataColl; ?>';
			var isManual	= document.getElementById('isManual').checked;

			if(window.XMLHttpRequest)
			{
				//code for IE7+,Firefox,Chrome,Opera,Safari
				xmlhttpTask=new XMLHttpRequest();
			}
			else
			{
				xmlhttpTask=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttpTask.onreadystatechange=function()
			{
				if(xmlhttpTask.readyState==4&&xmlhttpTask.status==200)
				{
					if(xmlhttpTask.responseText != '')
					{
						if(isManual == false)
							document.getElementById('<?php echo $dataTarget; ?>').value  = xmlhttpTask.responseText;
					}
					else
					{
						if(isManual == false)
							document.getElementById('<?php echo $dataTarget; ?>').value  = '';
					}
				}
			}
			xmlhttpTask.open("GET","<?php echo base_url().'index.php/__l1y/GetCodeDoc/';?>"+PRJCODE,true);
			xmlhttpTask.send();
		}
		<?php
	}
	else
	{
			$WO_STAT 	= $default['WO_STAT'];
			$WO_CODE 	= $default['WO_CODE'];
			if($WO_STAT == 1)
			{
				?>
				$(document).ready(function()
				{
					setInterval(function(){getNewCode()}, 1000);
				});

				function getNewCode()
				{
						var task						= "<?=$task?>";
						var dataTarget			= "WO_CODE";
						var Manual_Code			= "<?=$WO_CODE?>";
						var isManual 				= $('#isManual').prop('checked');

						if(isManual == true){
							$('#'+dataTarget).focus();
						}else{
							$.ajax({
								url: "<?php echo base_url().'index.php/__l1y/GetCodeDoc/';?>",
								type: "POST",
								data: {task:task, Manual_Code:Manual_Code},
								success: function(data){
									$('#'+dataTarget).val(data);
								}
							});
						}

				}
				<?php
			}
	}
	?>

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

	function deleteRow(btn)
	{
		var row = document.getElementById("tr_" + btn);
		row.remove();
	}

	function add_item(strItem)
	{
		arrItem = strItem.split('|');
		var objTable, objTR, objTD, intIndex, arrItem;
		var WO_NUMx 	= "<?php echo $DocNumber; ?>";

		var WO_CODEx 	= "<?php echo $WO_CODE; ?>";
		ilvl = arrItem[1];

		/*validateDouble(arrItem[0],arrItem[1])
		if(validateDouble(arrItem[0],arrItem[1]))
		{
			swal("Double Item for " + arrItem[0]);
			return;
		}*/

		JOBCODEDET 		= arrItem[0];
		JOBCODEID 		= arrItem[1];
		JOBCODE 		= arrItem[2];
		PRJCODE 		= arrItem[3];
		ITM_CODE 		= arrItem[4];
		ITM_NAME 		= arrItem[5];
		ITM_SN			= arrItem[6];
		ITM_UNIT 		= arrItem[7];
		ITM_PRICE 		= arrItem[8];
		ITM_VOLM 		= arrItem[9];
		ITM_BUDGQTY		= ITM_VOLM;
		ITM_STOCK 		= arrItem[10];
		ITM_USED 		= arrItem[11];
		itemConvertion	= arrItem[12];
		TotPrice		= arrItem[13];
		tempTotMax		= arrItem[14];
		TOT_USEBUDG		= arrItem[15];
		ITM_BUDG		= arrItem[16];
		TOT_USEDQTY		= parseFloat(arrItem[17]);
		WO_QTY			= arrItem[18];
		WO_AMOUNT		= arrItem[19];
		OPN_QTY			= arrItem[20];
		OPN_AMOUNT		= arrItem[21];

		objTable 		= document.getElementById('tbl');
		intTable 		= objTable.rows.length;
		//swal('intTable = '+intTable)
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
		objTD.innerHTML = '<a href="#" onClick="deleteRow('+intIndex+')" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a><input type="hidden" id="chk" name="chk" value="'+intIndex+'" width="10" size="15" readonly class="form-control" style="max-width:300px;"><input type="hidden" name="totalrow" id="totalrow" value="'+intIndex+'">';

		// Item Code
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.display = 'none';
		objTD.innerHTML = ''+ITM_CODE+'<input type="hidden" id="data'+intIndex+'WO_NUM" name="data['+intIndex+'][WO_NUM]" value="'+WO_NUMx+'" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'WO_CODE" name="data['+intIndex+'][WO_CODE]" value="'+WO_CODEx+'" class="form-control" style="max-width:300px;"><input type="hidden" name="data['+intIndex+'][JOBCODEDET]" id="data'+intIndex+'JOBCODEDET" value="'+JOBCODEDET+'" class="form-control" ><input type="hidden" name="data['+intIndex+'][JOBCODEID]" id="data'+intIndex+'JOBCODEID" value="'+JOBCODEID+'" class="form-control" >';

		// Item Name
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.noWrap = true;
		objTD.innerHTML = ''+ITM_NAME+'<input type="hidden" id="data'+intIndex+'ITM_CODE" name="data['+intIndex+'][ITM_CODE]" value="'+ITM_CODE+'" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'SNCODE" name="data['+intIndex+'][SNCODE]" value="'+ITM_SN+'" class="form-control" style="max-width:300px;">';

		// Item Budget
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" class="form-control" style="min-width:90px; max-width:90px; text-align:right" name="ITM_BUDGQTYx'+intIndex+'" id="ITM_BUDGQTYx'+intIndex+'" value="'+ITM_BUDGQTY+'" disabled ><input type="hidden" style="text-align:right" name="ITM_BUDGQTY'+intIndex+'" id="ITM_BUDGQTY'+intIndex+'" value="'+ITM_BUDGQTY+'" ><input type="hidden" id="data'+intIndex+'ITM_BUDG" name="data['+intIndex+'][ITM_BUDG]" value="'+ITM_BUDG+'">';

		// Item Worked FOR INFORMATION ONLY
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="hidden" class="form-control" style="min-width:100px; max-width:100px; text-align:right" name="ITM_REQUESTED'+intIndex+'" id="ITM_REQUESTED'+intIndex+'" value="'+TOT_USEBUDG+'" ><input type="hidden" class="form-control" style="min-width:110px; max-width:300px; text-align:right" name="ITM_REQUESTEDx'+intIndex+'" id="ITM_REQUESTEDx'+intIndex+'" value="'+TOT_USEBUDG+'" ><input type="hidden" class="form-control" style="text-align:right" name="TOTWOQTY'+intIndex+'" id="TOTWOQTY'+intIndex+'" value="'+TOT_USEDQTY+'" ><input type="text" class="form-control" style="min-width:90px; max-width:90px; text-align:right" name="TOTWOQTY'+intIndex+'" id="TOTWOQTYx'+intIndex+'" value="'+TOT_USEDQTY+'" disabled >';

		// Item Worked Now -- WO_VOLM
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" name="WO_VOLM'+intIndex+'" id="WO_VOLM'+intIndex+'" value="0.00" class="form-control" style="min-width:100px; max-width:100px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,'+intIndex+');" ><input type="hidden" name="data['+intIndex+'][WO_VOLM]" id="data'+intIndex+'WO_VOLM" value="0.00" class="form-control" style="max-width:300px;" >';

		// Item Price -- ITM_PRICE
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.style.fontStyle = 'italic';
		objTD.innerHTML = 'none<input type="hidden" name="ITM_PRICE'+intIndex+'" id="ITM_PRICE'+intIndex+'" value="'+ITM_PRICE+'" class="form-control" style="min-width:100px; max-width:100px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion_1(this,'+intIndex+');" ><input type="hidden" name="data['+intIndex+'][ITM_PRICE]" id="data'+intIndex+'ITM_PRICE" value="'+ITM_PRICE+'" class="form-control" style="min-width:100px; max-width:100px;" ><input type="hidden" style="text-align:right" name="itemConvertion'+intIndex+'" id="itemConvertion'+intIndex+'" value="'+itemConvertion+'" >';

		// Item Unit Type -- ITM_UNIT
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = ''+ITM_UNIT+'<input type="hidden" name="data['+intIndex+'][ITM_UNIT]" id="data'+intIndex+'ITM_UNIT" value="'+ITM_UNIT+'" class="form-control" style="max-width:300px;" >';

		// WO JUMLAH PRICE X VOLM
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.style.fontStyle = 'italic';
		objTD.innerHTML = 'none<input type="hidden" name="data['+intIndex+'][WO_TOTAL]" id="data'+intIndex+'WO_TOTAL" value="0.00" class="form-control" style="max-width:150px; text-align:right" onKeyPress="return isIntOnlyNew(event);" ><input type="hidden" class="form-control" style="min-width:100px; max-width:100px; text-align:right" name="WO_TOTAL'+intIndex+'" id="WO_TOTAL'+intIndex+'" value="" size="10" disabled >';

		// Tax
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.style.fontStyle = 'italic';
		objTD.innerHTML = 'none<select name="data['+intIndex+'][TAXCODE1]" id="TAXCODE1'+intIndex+'" class="form-control" style="max-width:150px; display:none" onChange="getConvertion(this,'+intIndex+');"><option value=""> no tax </option><option value="TAX01">PPn 10%</option><option value="TAX02">PPh 3%</option></select><input type="hidden" name="data['+intIndex+'][TAXPRICE1]" id="data'+intIndex+'TAXPRICE1" size="20" value="0" class="form-control" style="min-width:110px; max-width:300px; text-align:left"><input type="hidden" name="data['+intIndex+'][WO_TOTAL2]" id="data'+intIndex+'WO_TOTAL2" value="" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" ><input type="hidden" name="data'+intIndex+'WO_TOTAL2A" id="WO_TOTAL2A'+intIndex+'" value="" class="form-control" style="min-width:130px; max-width:130px; text-align:right;" onKeyPress="return isIntOnlyNew(event);" ><input type="hidden" name="data['+intIndex+'][WO_DESC]" id="data'+intIndex+'WO_DESC" size="20" value="" class="form-control" style="min-width:110px; max-width:100px; text-align:left">';

		// Remarks -- WO_DESC
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = '<input type="text" name="data['+intIndex+'][WO_DESC]" id="data'+intIndex+'WO_DESC" size="20" value="" class="form-control" style="max-width:300px;">';

		var decFormat												= document.getElementById('decFormat').value;
		var PPMat_Budget											= document.getElementById('ITM_BUDGQTY'+intIndex).value
		document.getElementById('ITM_BUDGQTY'+intIndex).value 		= parseFloat(Math.abs(PPMat_Budget));
		document.getElementById('ITM_BUDGQTYx'+intIndex).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PPMat_Budget)),decFormat));
		var ITM_REQUESTED											= document.getElementById('ITM_REQUESTED'+intIndex).value;
		document.getElementById('TOTWOQTY'+intIndex).value 			= parseFloat(Math.abs(TOT_USEDQTY));
		document.getElementById('ITM_REQUESTED'+intIndex).value 	= parseFloat(Math.abs(ITM_REQUESTED));
		document.getElementById('ITM_REQUESTEDx'+intIndex).value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_REQUESTED)),decFormat));
		document.getElementById('TOTWOQTY'+intIndex).value 			= parseFloat(Math.abs(TOT_USEDQTY));
		document.getElementById('TOTWOQTYx'+intIndex).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TOT_USEDQTY)),decFormat));
		document.getElementById('ITM_PRICE'+intIndex).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_PRICE)),decFormat));
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

	function validateDouble(vcode, SNCODE)
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
				var elitem1= document.getElementById('data'+i+'ITM_CODE').value;
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

	function getConvertion_1(thisVal1, row)
	{
		var decFormat		= document.getElementById('decFormat').value;

		thisVal 			= parseFloat(Math.abs(eval(thisVal1).value.split(",").join("")));

		document.getElementById('data'+row+'ITM_PRICE').value	= thisVal;
		document.getElementById('ITM_PRICE'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(thisVal)),decFormat));

		thisVal				= parseFloat(document.getElementById('WO_VOLM'+row).value);				// Request Qty - Now
		getConvertion(thisVal, row);
	}

	function getConvertion(thisVal1, row)
	{
		var decFormat		= document.getElementById('decFormat').value;

		//thisVal 			= parseFloat(Math.abs(thisVal1.value));
		itemConvertion		= document.getElementById('itemConvertion'+row).value;
		ITM_UNIT			= document.getElementById('data'+row+'ITM_UNIT').value;					// Item Unit
		ITM_PRICE			= parseFloat(document.getElementById('data'+row+'ITM_PRICE').value);	// Item Price
		ITM_BUDGQTY			= parseFloat(document.getElementById('ITM_BUDGQTY'+row).value);			// Budget Qty
		ITM_BUDG			= parseFloat(document.getElementById('data'+row+'ITM_BUDG').value);		// Budget Amount
		TOTWOQTY			= document.getElementById('TOTWOQTY'+row).value;						// Total Requested
		TOTPRAMOUNT			= parseFloat(TOTWOQTY) * parseFloat(ITM_PRICE);							// Total Requested Amount

		if(ITM_UNIT == 'LS')
			TOTPRAMOUNT		= parseFloat(TOTWOQTY);

		REQ_NOW_QTY1		= parseFloat(eval(document.getElementById('WO_VOLM'+row)).value.split(",").join(""));	// Request Qty - Now
		REQ_NOW_QTY2		= REQ_NOW_QTY1 * itemConvertion;										// Request Qty 2 - Now
		REQ_NOW_AMOUNT		= parseFloat(REQ_NOW_QTY1) * parseFloat(ITM_PRICE);						// Request Qty Amount - Now

		if(ITM_UNIT == 'LS')
			REQ_NOW_AMOUNT	= parseFloat(REQ_NOW_QTY1);

		REM_WO_QTY			= parseFloat(ITM_BUDGQTY) - parseFloat(TOTWOQTY);
		REM_WO_AMOUNT		= parseFloat(ITM_BUDG) - parseFloat(TOTPRAMOUNT);

		if(REQ_NOW_AMOUNT > REM_WO_AMOUNT)
		{
			REM_WO_QTYV		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(REM_WO_QTY)),decFormat));
			REM_WO_AMOUNTV	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(REM_WO_AMOUNT)),decFormat));
			swal('Request Qty is Greater than Budget. Maximum Qty is '+REM_WO_QTY+' or in Amount is '+REM_WO_AMOUNT,
			{
				icon: "warning",
			});
			document.getElementById('data'+row+'WO_VOLM').value = REM_WO_QTY;
			document.getElementById('WO_VOLM'+row).value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(REM_WO_QTY)),decFormat));
			REQ_NOW_AMOUNT	= parseFloat(REM_WO_QTY) * parseFloat(ITM_PRICE);
			document.getElementById('data'+row+'WO_TOTAL').value 	= REQ_NOW_AMOUNT;
			document.getElementById('WO_TOTAL'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(REQ_NOW_AMOUNT)),decFormat));
			document.getElementById('data'+row+'WO_VOLM').value 	= REM_WO_QTY;
			document.getElementById('WO_VOLM'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(REM_WO_QTY)),decFormat));
			NEW_PRICE = parseFloat(REM_WO_AMOUNT / REM_WO_QTY);
			document.getElementById('data'+row+'ITM_PRICE').value	= NEW_PRICE;
			document.getElementById('ITM_PRICE'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(NEW_PRICE)),decFormat));

			// GET TAX VALUE
			TAXCODE1	= document.getElementById('TAXCODE1'+row).value;
			if(TAXCODE1 == 'TAX01')
			{
				TAXPRICE1 	= parseFloat(0.1 * REQ_NOW_AMOUNT);
				WO_TOTAL2	= parseFloat(REQ_NOW_AMOUNT + TAXPRICE1);
			}
			else if(TAXCODE1 == 'TAX02')
			{
				TAXPRICE1 	= parseFloat(0.03 * REQ_NOW_AMOUNT);
				WO_TOTAL2	= parseFloat(REQ_NOW_AMOUNT - TAXPRICE1);
			}
			document.getElementById('data'+row+'WO_TOTAL2').value 	= WO_TOTAL2; // after tax
			document.getElementById('WO_TOTAL2A'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(WO_TOTAL2)),decFormat));
			document.getElementById('data'+row+'TAXPRICE1').value 	= TAXPRICE1;
			return false;
		}

		document.getElementById('data'+row+'WO_TOTAL').value 	= REQ_NOW_AMOUNT;
		document.getElementById('WO_TOTAL'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(REQ_NOW_AMOUNT)),decFormat));
		document.getElementById('data'+row+'WO_VOLM').value 	= REQ_NOW_QTY1;
		document.getElementById('WO_VOLM'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(REQ_NOW_QTY1)),decFormat));

		// GET TAX VALUE
		TAXCODE1	= document.getElementById('TAXCODE1'+row).value;
		TAXPRICE1	= 0;
		WO_TOTAL2	= REQ_NOW_AMOUNT;
		if(TAXCODE1 == 'TAX01')
		{
			TAXPRICE1 	= parseFloat(0.1 * REQ_NOW_AMOUNT);
			WO_TOTAL2	= parseFloat(REQ_NOW_AMOUNT + TAXPRICE1);
		}
		else if(TAXCODE1 == 'TAX02')
		{
			TAXPRICE1 	= parseFloat(0.03 * REQ_NOW_AMOUNT);
			WO_TOTAL2	= parseFloat(REQ_NOW_AMOUNT - TAXPRICE1);
		}

		document.getElementById('data'+row+'WO_TOTAL2').value 	= WO_TOTAL2; // After Plus or Min Tax
			document.getElementById('WO_TOTAL2A'+row).value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(WO_TOTAL2)),decFormat));
		document.getElementById('data'+row+'TAXPRICE1').value 	= TAXPRICE1;

		//COUNT TOTAL SPK
		var totrow 		= document.getElementById('totalrow').value;

		var GTOTAL_WO	= 0;
		for(i=1;i<=totrow;i++)
		{
			var WO_TOTITEM	= parseFloat(document.getElementById('data'+i+'WO_TOTAL2').value);
			GTOTAL_WO		= parseFloat(GTOTAL_WO + WO_TOTITEM);
		}
		document.getElementById('WO_VALUE').value = GTOTAL_WO;
		document.getElementById('WO_VALUE1').value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(GTOTAL_WO)),decFormat));
	}

	function RoundNDecimal(X, N)
	{
		var T, S=new String(Math.round(X*Number("1e"+N)))
		while (S.length<=N) S='0'+S
		return S.substr(0, T=(S.length-N)) + '.' + S.substr(T, N)
	}

	function validateInData()
	{
		var totrow 		= document.getElementById('totalrow').value;
		//var venCode 	= document.getElementById('Vend_Code').value;

		for(i=1;i<=totrow;i++)
		{
			var WO_VOLM	= parseFloat(eval(document.getElementById('WO_VOLM'+i)).value.split(",").join(""));
			if(WO_VOLM == 0)
			{
				swal('<?php echo $alert1; ?>',
				{
					icon: "warning",
				});
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
			swal('<?php echo $alert2; ?>',
			{
				icon: "warning",
			});
			return false;
		}
		else
		{
			document.frm.submit();
		}
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
    $this->load->view('template/aside');

    $this->load->view('template/js_data');

    $this->load->view('template/foot');
?>