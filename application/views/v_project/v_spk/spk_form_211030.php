<?php
/*
 * Author		= Dian Hermanto
 * Create Date	= 02 Januari 2018
 * File Name	= spk_form.php
 * Location		= -
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody    = $this->session->userdata('appBody');

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

// _global function
$this->db->select('Display_Rows,decFormat,APPLEV');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
	$APPLEV = $row->APPLEV;
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
	$myCount = $this->db->count_all('tbl_wo_header');

	$sql = "SELECT MAX(Patt_Number) as maxNumber FROM tbl_wo_header
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
	$WO_CODE		= "PK.$WOCODE.$WOYEAR.$WOMONTH"; // MANUAL CODE

	$WO_DATE		= date('m/d/Y');
	$WO_ENDD		= date('m/d/Y');
	$PRJCODE		= $PRJCODE;
	$SPLCODE		= '';
	$WO_DEPT		= '';
	$WO_CATEG		= 'MDR';
	$WO_TYPE		= 'PO';
	$JOBCODEID		= '';
	$PR_REFNO		= '';
	$JOBCODE1		= $PR_REFNO;
	$WO_NOTE		= '';
	$WO_STAT 		= 1;
	$Patt_Year 		= date('Y');
	$Patt_Number	= $lastPatternNumb1;
	$WO_VALUE		= 0;
	$WO_MEMO		= '';
	$WO_REFNO		= '';
	$FPA_NUM		= '';
	$WO_QUOT		= '';
	$WO_NEGO		= '';

	$dataColl 		= "$PRJCODE~$Pattern_Code~tbl_wo_header~$Pattern_Length";
	$dataTarget		= "WO_CODE";

	$WO_DPPER		= 0;
	$WO_DPREF		= '';
	$WO_DPREF1		= '';
	$WO_DPVAL		= 0;
}
else
{
	$isSetDocNo = 1;
	$WO_NUM 	= $default['WO_NUM'];
	$DocNumber	= $default['WO_NUM'];
	$WO_CODE 	= $default['WO_CODE'];
	$WO_DATE 	= date('m/d/Y', strtotime($default['WO_DATE']));
	$WO_STARTD 	= date('m/d/Y', strtotime($default['WO_STARTD']));
	$WO_ENDD 	= date('m/d/Y', strtotime($default['WO_ENDD']));
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
	$WO_REFNO 	= $default['WO_REFNO'];
	$PRJNAME 	= $default['PRJNAME'];
	$FPA_NUM 	= $default['FPA_NUM'];
	$WO_QUOT 	= $default['WO_QUOT'];
	$WO_NEGO 	= $default['WO_NEGO'];
	$WO_DPPER 	= $default['WO_DPPER'];
	$WO_DPREF 	= $default['WO_DPREF'];
	$WO_DPREF1 	= $default['WO_DPREF1'];
	$WO_DPVAL 	= $default['WO_DPVAL'];
	$Patt_Year 	= $default['Patt_Year'];
	$Patt_Month = $default['Patt_Month'];
	$Patt_Date 	= $default['Patt_Date'];
	$Patt_Number= $default['Patt_Number'];
}

if(isset($_POST['JOBCODE1']))
{
	$PR_REFNO	= $_POST['JOBCODE1'];
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
		$ISDELETE = $this->session->userdata['ISDELETE'];
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
			if($TranslCode == 'WOCode')$WOCode = $LangTransl;
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
			if($TranslCode == 'SelectItem')$SelectItem = $LangTransl;
			if($TranslCode == 'JobName')$JobName = $LangTransl;
			if($TranslCode == 'ItemQty')$ItemQty = $LangTransl;
			if($TranslCode == 'EndDate')$EndDate = $LangTransl;
			if($TranslCode == 'Approval')$Approval = $LangTransl;
			if($TranslCode == 'Approved')$Approved = $LangTransl;
			if($TranslCode == 'NotYetApproved')$NotYetApproved = $LangTransl;
			if($TranslCode == 'Awaiting')$Awaiting = $LangTransl;
			if($TranslCode == 'Price')$Price = $LangTransl;
			if($TranslCode == 'Supplier')$Supplier = $LangTransl;
			if($TranslCode == 'Tax')$Tax = $LangTransl;
			if($TranslCode == 'Search')$Search = $LangTransl;
			if($TranslCode == 'QuotNo')$QuotNo = $LangTransl;
			if($TranslCode == 'NegotNo')$NegotNo = $LangTransl;
			if($TranslCode == 'Reason')$Reason = $LangTransl;
			if($TranslCode == 'SupplierName')$SupplierName = $LangTransl;

			if($TranslCode == 'ApproverNotes')$ApproverNotes = $LangTransl;
			if($TranslCode == 'reviseNotes')$reviseNotes = $LangTransl;
			if($TranslCode == 'Approve')$Approve = $LangTransl;
			if($TranslCode == 'Approver')$Approver = $LangTransl;
			if($TranslCode == 'Approved')$Approved = $LangTransl;
			if($TranslCode == 'Approval')$Approval = $LangTransl;
			if($TranslCode == 'NotYetApproved')$NotYetApproved = $LangTransl;
			if($TranslCode == 'InOthSett')$InOthSett = $LangTransl;
		endforeach;

		if($LangID == 'IND')
		{
			$subTitleH	= "Tambah SPK";
			$subTitleD	= "surat perintah kerja";
			$alert1		= "Silahkan masukan volume pekerjaan.";
			$alert2		= "Silahkan masukan detail pekerjaan.";
			$alert3		= "Silahkan pilih pekerjaan.";
			$alert4		= "Silahkan pilih Supplier.";
			$alert5		= "Silahkan masukan nomor FPA.";
			$alert6		= "Masukan alasan mengapa dokumen ini di-close.";
			$alert7		= "Masukan persentase DP.";
			$alert8		= "Masukan kode DP.";
			$alertVOID	= "Tidak dapat dibatalkan. Sudah digunakan oleh Dokumen No.: ";
			$isManual	= "Centang untuk kode manual.";
			$alertSubmit= "data sudah berhasil disimpan";
		}
		else
		{
			$subTitleH	= "Add WO";
			$subTitleD	= "work order";
			$alert1		= "Please input qty of job volume.";
			$alert2		= "Please input job detail.";
			$alert3		= "Please select Job.";
			$alert4		= "Please select a Supplier.";
			$alert5		= "Please input FPA Number.";
			$alert6		= "Input the reason why you close this document.";
			$alert7		= "DP Percentation can not be empty.";
			$alert8		= "DP Code can not be empty.";
			$alertVOID	= "Can not be void. Used by document No.: ";
			$isManual	= "Check to manual code.";
			$alertSubmit= "data has been successfully saved";
		}
		
		// START : APPROVE PROCEDURE
			if($APPLEV == 'HO')
				$PRJCODE_LEV	= $this->data['PRJCODE_HO'];
			else
				$PRJCODE_LEV	= $this->data['PRJCODE'];
			
			// DocNumber - PR_VALUE
			$EMPN_1 	= "";
			$EMPN_2 	= "";
			$EMPN_3 	= "";
			$EMPN_4		= "";
			$EMPN_5 	= "";

			$IS_LAST	= 0;
			$APP_LEVEL	= 0;
			$APPROVER_1	= '';
			$APPROVER_2	= '';
			$APPROVER_3	= '';
			$APPROVER_4	= '';
			$APPROVER_5	= '';	
			$disableAll	= 1;
			$DOCAPP_TYPE= 1;
			$sqlCAPP	= "tbl_docstepapp WHERE MENU_CODE = '$MenuApp' AND PRJCODE = '$PRJCODE_LEV'";
			$resCAPP	= $this->db->count_all($sqlCAPP);
			if($resCAPP > 0)
			{
				$sqlAPP	= "SELECT * FROM tbl_docstepapp WHERE MENU_CODE = '$MenuApp'
							AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE_LEV')";
				$resAPP	= $this->db->query($sqlAPP)->result();
				foreach($resAPP as $rowAPP) :
					$MAX_STEP		= $rowAPP->MAX_STEP;
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
					}
				endforeach;
				$disableAll	= 0;
			
				// CHECK AUTH APPROVE TYPE
				$sqlAPPT	= "SELECT DOCAPP_TYPE FROM tbl_docstepapp WHERE MENU_CODE = '$MenuApp'
								AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE_LEV')";
				$resAPPT	= $this->db->query($sqlAPP)->result();
				foreach($resAPPT as $rowAPPT) :
					$DOCAPP_TYPE	= $rowAPPT->DOCAPP_TYPE;
				endforeach;
			}
			
			$sqlSTEPAPP	= "tbl_docstepapp_det WHERE MENU_CODE = '$MenuApp' AND APPROVER_1 = '$DefEmp_ID'
							AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE_LEV')";
			$resSTEPAPP	= $this->db->count_all($sqlSTEPAPP);
			
			if($resSTEPAPP > 0)
			{
				$canApprove	= 1;
				$APPLIMIT_1	= 0;
				
				$sqlAPP	= "SELECT APPLIMIT_1, APP_STEP, MAX_STEP FROM tbl_docstepapp_det WHERE MENU_CODE = '$MenuApp'
							AND APPROVER_1 = '$DefEmp_ID' AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE_LEV')";
				$resAPP	= $this->db->query($sqlAPP)->result();
				foreach($resAPP as $rowAPP) :
					$APPLIMIT_1	= $rowAPP->APPLIMIT_1;
					$APP_STEP	= $rowAPP->APP_STEP;
					$MAX_STEP	= $rowAPP->MAX_STEP;
				endforeach;
				$sqlC_App	= "tbl_approve_hist WHERE AH_CODE = '$DocNumber'";
				$resC_App 	= $this->db->count_all($sqlC_App);
				
				$BefStepApp	= $APP_STEP - 1;
				if($resC_App == $BefStepApp)
				{
					$canApprove	= 1;
				}
				elseif($resC_App == $APP_STEP)
				{
					$canApprove	= 0;
					$descApp	= "You have Approved";
					$statcoloer	= "success";
				}
				else
				{
					$canApprove	= 0;
					$descApp	= "Awaiting";
					$statcoloer	= "warning";
				}
							 
				if($APP_STEP == $MAX_STEP)
					$IS_LAST		= 1;
				else
					$IS_LAST		= 0;
				
				// Mungkin dengan tahapan approval lolos, check kembali total nilai jika dan HANYA JIKA Type Approval Step is 1 = Ammount
				// This roles are for All Approval. Except PR and Receipt
				// NOTES
				// $APPLIMIT_1 		= Maximum Limit to Approve
				// $APPROVE_AMOUNT	= Amount must be Approved
				$APPROVE_AMOUNT 	= $WO_VALUE;
				//$APPROVE_AMOUNT	= 10000000000;
				//$DOCAPP_TYPE	= 1;
				if($DOCAPP_TYPE == 1)
				{
					if($APPLIMIT_1 < $APPROVE_AMOUNT)
					{
						$canApprove	= 0;
						$descApp	= "You can not approve caused of the max limit.";
						$statcoloer	= "danger";
					}
				}
			}
			else
			{
				$canApprove	= 0;
				$descApp	= "You can not approve this document.";
				$statcoloer	= "danger";
				$IS_LAST	= 0;
				$APP_STEP	= 0;
			}
			
			$APP_LEVEL	= $APP_STEP;
		// END : APPROVE PROCEDURE
		
		// VOID CHECKING FUNCTION
			$DOC_NO	= '';
			$sqlOPNC= "tbl_opn_header WHERE WO_NUM = '$WO_NUM' AND OPNH_STAT NOT IN (5,9)";
			$isUSED	= $this->db->count_all($sqlOPNC);
			if($isUSED > 0)
			{
				$noU	= 0;
				$sqlOPN	= "SELECT OPNH_CODE FROM tbl_opn_header WHERE WO_NUM = '$WO_NUM' AND OPNH_STAT NOT IN (5,9)";
				$resOPN	= $this->db->query($sqlOPN)->result();
				foreach($resOPN as $rowOPN):
					$noU	= $noU + 1;
					$DOCNO	= $rowOPN->OPNH_CODE;
					if($noU == 1)
						$DOC_NO = $DOCNO;
					else
						$DOC_NO	= $DOC_NO.", ".$DOCNO;
				endforeach;
			}
		
		$secAddURL	= site_url('c_project/c_s180d0bpk/add/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
		$secGenCode	= base_url().'index.php/c_project/c_s180d0bpk/genCode/'; // Generate Code
		$secGetJID	= base_url().'index.php/c_project/c_s180d0bpk/getJID/'; // Generate Code

        $comp_color = $this->session->userdata('comp_color');
    ?>

    <style type="text/css">
        .search-table, td, th {
            border-collapse: collapse;
        }
        .search-table-outter { overflow-x: scroll; }

        a[disabled="disabled"] {
            pointer-events: none;
        }
    </style>
    
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
			<h1>
		    <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/wo.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $Add; ?>
		    <small><?php echo $mnName; ?></small>  </h1>
		</section>

		<section class="content">
		    <div class="row">
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

		        <form class="form-horizontal" name="frm" id="frm" method="post" action="<?php echo $form_action; ?>" enctype="multipart/form-data">
					<div class="col-md-6">
						<div class="box box-primary">
							<div class="box-header with-border" style="display: none;">
								<i class="fa fa-cloud-upload"></i>
								<h3 class="box-title">&nbsp;</h3>
							</div>
							<div class="box-body">
					        	<input type="hidden" name="isUSED" id="isUSED" value="<?php echo $isUSED; ?>">
					            <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
					            <input type="hidden" name="Patt_Number" id="Patt_Number" value="<?php echo $Patt_Number; ?>" />
					            <input type="Hidden" name="rowCount" id="rowCount" value="0">
								<?php if($isSetDocNo == 0) { ?>
				                <div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label">&nbsp;</label>
				                    <div class="col-sm-9">
				                        <div class="alert alert-danger alert-dismissible">
				                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				                            <h4><i class="icon fa fa-ban"></i> <?php echo $docalert1; ?>!</h4>
				                            <?php echo $docalert2; ?>
				                        </div>
				                    </div>
				                </div>
				                <?php } ?>
				            	<div class="form-group" style="display:none">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $WONo; ?></label>
				                    <div class="col-sm-9">
				                    	<input type="text" class="form-control" style="text-align:left" name="WO_NUM1" id="WO_NUM1" size="30" value="<?php echo $DocNumber; ?>" disabled />
				                    	<input type="hidden" class="form-control" style="max-width:350px;text-align:right" name="WO_NUM" id="WO_NUM" size="30" value="<?php echo $DocNumber; ?>" />
				                    </div>
				                </div>
				                <div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $WOCode; ?></label>
				                    <div class="col-sm-9">
				                        <input type="text" class="form-control" style="text-align:left" id="WO_CODE" name="WO_CODE" value="<?php echo $WO_CODE; ?>" readonly />
				                    </div>
				                </div>
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Date; ?></label>
				                    <div class="col-sm-3">
				                    	<div class="input-group date">
				                        <div class="input-group-addon">
				                        <i class="fa fa-calendar"></i>&nbsp;</div><input type="text" name="WO_DATE" class="form-control pull-left" id="datepicker" value="<?php echo $WO_DATE; ?>" style="width:106px"></div>
				                    </div>
				                    <div class="col-sm-2">
				                    	<label for="inputName" class="col-sm-10 control-label">Tgl.Selesai</label>
				                    </div>
				                    <div class="col-sm-4">
				                    	<div class="input-group date">
				                        <div class="input-group-addon">
				                        <i class="fa fa-calendar"></i>&nbsp;</div><input type="text" name="WO_ENDD" class="form-control pull-left" id="datepicker1" value="<?php echo $WO_ENDD; ?>"></div>
				                    </div>
				                </div>
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Category; ?></label>
				                    <div class="col-sm-9">
				                    	<select name="WO_CATEG" id="WO_CATEG" class="form-control select2" onChange="changeCODE(this.value)" >
				                          <option value="none">--- None ---</option>
				                          <option value="MDR" <?php if($WO_CATEG == 'MDR') { ?> selected <?php } ?>> Mandor</option>
				                          <option value="SALT" <?php if($WO_CATEG == 'SALT') { ?> selected <?php } ?>> Sewa Alat</option>
				                          <option value="SUB" <?php if($WO_CATEG == 'SUB') { ?> selected <?php } ?>> Sub Contractor</option>
				                        </select>
				                    </div>
				                </div>
				                <?php
									$WO_CODE	= '';
									$url_selFPAMDR	= site_url('c_project/c_s180d0bpk/s3l4llFP4MDR/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
									$url_selFPASUB	= site_url('c_project/c_s180d0bpk/s3l4llFP4SUB/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
									$url_selFPASALT	= site_url('c_project/c_s180d0bpk/s3l4llFP4/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
									$FPA_CODE		= '';
									if($WO_CATEG == 'MDR')
									{
										$FPA_CODE		= '';
										$sqlWOREQ		= "SELECT FPA_CODE FROM tbl_fpa_header WHERE FPA_NUM = '$FPA_NUM' LIMIT 1";
										$sqlWOREQ		= $this->db->query($sqlWOREQ)->result();
										foreach($sqlWOREQ as $rowWOREQ) :
											$FPA_CODE	= $rowWOREQ->FPA_CODE;
										endforeach;
									}
									else if($WO_CATEG == 'SUB')
									{
										$FPA_CODE		= '';
										$sqlWOREQ		= "SELECT FPA_CODE FROM tbl_fpa_header WHERE FPA_NUM = '$FPA_NUM' LIMIT 1";
										$sqlWOREQ		= $this->db->query($sqlWOREQ)->result();
										foreach($sqlWOREQ as $rowWOREQ) :
											$FPA_CODE	= $rowWOREQ->FPA_CODE;
										endforeach;
									}
									elseif($WO_CATEG == 'SALT')
									{
										$FPA_CODE		= '';
										$sqlWOREQ		= "SELECT WO_CODE FROM tbl_woreq_header WHERE WO_NUM = '$FPA_NUM' LIMIT 1";
										$sqlWOREQ		= $this->db->query($sqlWOREQ)->result();
										foreach($sqlWOREQ as $rowWOREQ) :
											$FPA_CODE	= $rowWOREQ->WO_CODE;
										endforeach;
									}
								?>
								<div class="form-group" id="woreq_ref">
									<label for="inputName" class="col-sm-3 control-label">Ref.</label>
									<div class="col-sm-9">
										<div class="input-group">
											<div class="input-group-btn">
												<button type="button" class="btn btn-primary"><?php echo $Search ?> </button>
											</div>
											<input type="hidden" class="form-control" name="FPA_NUM" id="FPA_NUM" style="max-width:160px" value="<?php echo $FPA_NUM; ?>" >
											<input type="text" class="form-control" name="FPA_CODE1" id="FPA_CODE1" value="<?php echo $FPA_CODE; ?>" onClick="pleaseCheck();" <?php if($WO_STAT != 1 && $WO_STAT != 6) { ?> disabled <?php } ?>>
										</div>
									</div>
								</div>
				                <script>
				                    var url1 = "<?php echo $url_selFPAMDR;?>";
				                    var url2 = "<?php echo $url_selFPASUB;?>";
				                    var url3 = "<?php echo $url_selFPASALT;?>";
				                    function pleaseCheck()
				                    {
				                        title = 'Select Item';
				                        w = 1000;
				                        h = 550;
				                        //window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
				                        var left = (screen.width/2)-(w/2);
				                        var top = (screen.height/2)-(h/2);

										WO_CATEG = document.getElementById('WO_CATEG').value;

										if(WO_CATEG == 'MDR')
										{
											return window.open(url1, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
										}
										else if(WO_CATEG == 'SUB')
										{
											return window.open(url2, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
										}
										else
										{
											return window.open(url3, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
										}
				                    }
				                </script>
				            	<div class="form-group" style="display:none">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Type; ?></label>
				                    <div class="col-sm-9">
				                    	<select name="WO_TYPE" id="WO_TYPE" class="form-control" style="max-width:150px" >
				                          <option value="NSA" <?php if($WO_TYPE == 'NSA') { ?> selected <?php } ?>> Pekerjaan</option>
				                          <option value="SA" <?php if($WO_TYPE == 'SA') { ?> selected <?php } ?>> Sewa Alat</option>
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
										{
											WO_CODEN	= 'PK'+WO_CODE;
											//document.getElementById('woreq_ref').style.display	= 'none';
											//document.getElementById('WO_MEMO').disabled 		= true;
										}
										else if(thisVal == 'SUB')
										{
											WO_CODEN	= 'KK'+WO_CODE;
											//document.getElementById('woreq_ref').style.display	= 'none';
											//document.getElementById('WO_MEMO').disabled 		= true;
										}
										else
										{
											WO_CODEN	= 'SA'+WO_CODE;
											//document.getElementById('woreq_ref').style.display	= '';
											//document.getElementById('WO_MEMO').disabled 		= false;
										}

										document.getElementById('WO_CODE').value = WO_CODEN;
									}
								</script>
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Supplier; ?></label>
				                    <div class="col-sm-9">
				                        <select name="SPLCODE" id="SPLCODE" class="form-control select2" data-placeholder="&nbsp;&nbsp;<?php echo $SupplierName; ?>">
				                          <option value="0"> -- </option>
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
				            	<div class="form-group" style="display: none;">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Project; ?></label>
				                    <div class="col-sm-9">
				                        <input type="hidden" class="form-control" style="max-width:350px;text-align:right" name="PRJCODE" id="PRJCODE" size="30" value="<?php echo $PRJCODE; ?>" />
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
				                    <input type="hidden" class="form-control" style="max-width:350px;text-align:right" id="PRJCODE" name="PRJCODE" size="20" value="<?php echo $PRJCODE; ?>" />
				                    </div>
				                </div>
				            	<div class="form-group" style="display: none;">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $JobName; ?></label>
				                    <div class="col-sm-9">
				                        <select name="PR_REFNO[]" id="PR_REFNO" class="form-control select2" multiple="multiple" data-placeholder="&nbsp;&nbsp;&nbsp;<?php echo $JobName; ?>" onBlur="selJOB(this.value)">
				                        	<option value="A">--- None ---</option>
											<?php
				                                /*$Disabled_1	= 0;
				                                $sqlJob_1	= "SELECT JOBCODEID, JOBPARENT, JOBLEV, JOBDESC FROM tbl_joblist WHERE ISHEADER = '1' AND PRJCODE = '$PRJCODE'";
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

				                                    $sqlC_2			= "tbl_joblist WHERE JOBPARENT = '$JOBCODEID_1' AND ISHEADER = '1' AND PRJCODE = '$PRJCODE'";
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
				                                endforeach;*/
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
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Notes; ?></label>
				                    <div class="col-sm-9">
				                    	<textarea name="WO_NOTE" class="form-control" id="WO_NOTE" cols="30" style="height: 87px" placeholder="Catatan SPK"><?php echo $WO_NOTE; ?></textarea>
				                    </div>
				                </div>
				            </div>
				        </div>
				    </div>
					<div class="col-md-6">
						<div class="box box-warning">
							<div class="box-header with-border" style="display: none;">
								<i class="fa fa-cloud-upload"></i>
								<h3 class="box-title">&nbsp;</h3>
							</div>
							<div class="box-body">
								<div class="form-group">
									<label for="inputName" class="col-sm-3 control-label">No. SK</label>
									<div class="col-sm-9">
										<input type="text" class="form-control" name="WO_REFNO" id="WO_REFNO" value="<?php echo $WO_REFNO; ?>" placeholder="Nomor Surat Keputusan" >
									</div>
								</div>
								<div class="form-group">
									<label for="inputName" class="col-sm-3 control-label"><?php echo $QuotNo; ?></label>
									<div class="col-sm-9">
										<input type="text" class="form-control" name="WO_QUOT" id="WO_QUOT" value="<?php echo $WO_QUOT; ?>" placeholder="<?php echo $QuotNo; ?>" >
									</div>
								</div>
								<div class="form-group">
									<label for="inputName" class="col-sm-3 control-label"><?php echo $NegotNo; ?></label>
									<div class="col-sm-9">
										<input type="text" class="form-control" name="WO_NEGO" id="WO_NEGO" value="<?php echo $WO_NEGO; ?>" placeholder="<?php echo $NegotNo; ?>">
									</div>
								</div>
								<div class="form-group">
									<label for="inputName" class="col-sm-3 control-label">DP %</label>
									<div class="col-sm-9">
				                        <label>
				                            <input type="hidden" class="form-control" style="max-width:200px; text-align:right;" name="WO_DPPER" id="WO_DPPER" value="<?php echo $WO_DPPER; ?>">
				                            <input type="text" class="form-control" style="max-width:80px; text-align:right;" name="WO_DPPERX" id="WO_DPPERX" value="<?php echo number_format($WO_DPPER, $decFormat); ?>" onBlur="getDPer(this)">
				                            <input type="hidden" class="form-control" style="max-width:200px; text-align:right;" name="WO_DPVAL" id="WO_DPVAL" value="<?php echo $WO_DPVAL; ?>">
				                            </label>
				                            <label>
				                            <input type="hidden" class="form-control" style="max-width:140px; text-align:right;" name="WO_DPREF" id="WO_DPREF" value="<?php echo $WO_DPREF; ?>" onClick="getDPREF();">
				                            <input type="hidden" class="form-control" style="max-width:200px;" name="WO_DPREF1" id="WO_DPREF1" value="<?php echo $WO_DPREF1; ?>" data-placeholder="Kode DP" onClick="getDPREF();">
				                      </label>
									</div>
								</div>
				                <script>
									function getDPer(thisVal1)
									{
										var decFormat		= document.getElementById('decFormat').value;

										thisVal 			= parseFloat(Math.abs(eval(thisVal1).value.split(",").join("")));

										document.getElementById('WO_DPPER').value	= thisVal;
										document.getElementById('WO_DPPERX').value	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(thisVal)),decFormat));
									}
								</script>

								<?php

				                    $url_popdp	= site_url('c_project/c_s180d0bpk/ll_4p/?id=');
				                ?>
				                <script>
				                    var urlDP = "<?php echo "$url_popdp";?>";
				                    function getDPREF()
				                    {
										PRJCODE	= document.getElementById("PRJCODE").value;
										SPLCODE	= document.getElementById("SPLCODE").value;
										if(SPLCODE == '')
										{
											swal('Silahkan pilih suplier',
											{
												icon:"warning",
											})
											.then(function()
											{
												document.getElementById('SPLCODE').focus();
											});
											return false;
										}
				                        title = 'Select Item';
				                        w = 850;
				                        h = 550;

				                        var left = (screen.width/2)-(w/2);
				                        var top = (screen.height/2)-(h/2);
										return window.open(urlDP+PRJCODE+'&SPLCODE='+SPLCODE, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
				                    }
				                </script>
				            	<div class="form-group" <?php if($WO_STAT != 6 ) { ?> style="display:none" <?php } ?> id="tblReason">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Reason; ?></label>
				                    <div class="col-sm-9">
				                    	<textarea name="WO_MEMO" class="form-control" style="max-width:350px;" id="WO_MEMO" cols="30"><?php echo $WO_MEMO; ?></textarea>
				                    </div>
				                </div>
								<div class="form-group">
									<label for="inputName" class="col-sm-3 control-label">Total - SPK</label>
									<div class="col-sm-9">
										<input type="hidden" name="WO_VALUE" id="WO_VALUE" class="form-control" style="max-width:150px; text-align:right" value="<?php echo $WO_VALUE; ?>">
		                    			<input type="text" name="WO_VALUE1" id="WO_VALUE1" class="form-control" style="text-align:right" value="<?php echo number_format($WO_VALUE, 2); ?>" disabled>
									</div>
								</div>
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Status; ?></label>
				                    <div class="col-sm-9">
				                    	<input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $WO_STAT; ?>">
				                        <select name="WO_STAT" id="WO_STAT" class="form-control select2" onChange="selStat(this.value)">
										<?php
											$isDisabled	= 0;
											if($WO_STAT == 6 || $WO_STAT == 7)
											{
												$isDisabled	= 1;
											}

											$disableBtn	= 0;
											if($WO_STAT == 5 || $WO_STAT == 6 || $WO_STAT == 9)
											{
												$disableBtn	= 1;
											}
											elseif($WO_STAT == 3 && $ISDELETE == 1)
											{
											    $disableBtn	= 0;
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
											$theProjCode 	= $PRJCODE;
											$url_AddItem	= site_url('c_project/c_spk/popupallitem/?id='.$this->url_encryption_helper->encode_url($theProjCode));
											$theProjCode 	= "$PRJCODE~$PR_REFNO";
				                        	$url_AddItem	= site_url('c_project/c_s180d0bpk/popupallitem/?id=');
				                        ?>
				                        </select>
				                    </div>
				                </div>
				                <script>
									function selStat(thisValue)
									{
										if(thisValue == 6)
										{
											document.getElementById('tblClose').style.display = '';
											document.getElementById('tblReason').style.display = '';
										}
										else if(thisValue == 9)
										{
											var isUSED	= document.getElementById('isUSED').value;
											if(isUSED > 0)
											{
												swal('<?php echo $alertVOID; ?>'+' <?php echo $DOC_NO; ?>',
												{
													icon:"warning",
												});
												return false;
											}
											else
											{
												document.getElementById('tblClose').style.display = '';
												document.getElementById('tblReason').style.display = '';
											}
										}
										else if(thisValue == 3)
										{
											document.getElementById('tblClose').style.display = 'none';
											document.getElementById('tblReason').style.display = 'none';
										}
									}
								</script>
				                <div class="form-group" <?php if($isDisabled == 1) { ?> style="display:none" <?php } ?>>
				                    <label for="inputName" class="col-sm-3 control-label">&nbsp;</label>
				                    <div class="col-sm-9">
				                        <script>
				                            var url = "<?php echo $url_AddItem;?>";

				                            function selectitem()
				                            {
												WO_CATEG	= document.getElementById("WO_CATEG").value;
												FPA_CODE	= document.getElementById("FPA_CODE1").value;
												if(WO_CATEG == 'SALT' && FPA_CODE == '')
												{
													swal('<?php echo $alert5; ?>',
													{
														icon:"warning",
													})
													.then(function()
													{
														document.getElementById('FPA_CODE1').focus();
													});
													return false;
												}

												SPLCODE		= document.getElementById('SPLCODE').value;
												if(SPLCODE == '')
												{
													swal('<?php echo $alert4; ?>',
													{
														icon:"warning",
													})
													.then(function()
													{
														document.getElementById('SPLCODE').focus();
													});
													return false;
												}

												/*PR_REFNO1	= $('#PR_REFNO').val();
												if(PR_REFNO1 == null)
												{
													swal('<?php echo $alert3; ?>',
													{
														icon:"warning",
													})
													.then(function()
													{
														document.getElementById('PR_REFNO').focus();
													});
													return false;
												}*/

												PR_REFNO 	= '';
												//PR_REFNO 	= PR_REFNO1.join("~");
												PRJCODE		= document.getElementById('PRJCODE').value;

												/*if(PR_REFNO == '')
												{
													swal('<?php echo $alert3; ?>');
													document.getElementById('PR_REFNO').focus();
													return false;
												}*/
				                                title = 'Select Item';
				                                w = 1000;
				                                h = 550;
				                                //window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
				                                var left = (screen.width/2)-(w/2);
				                                var top = (screen.height/2)-(h/2);
				                                return window.open(url+PR_REFNO+'&pr1h0ec0JcoDe='+PRJCODE+'&pgfrm='+WO_CATEG, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
				                            }
				                        </script>
				                        <?php if($WO_STAT == 1 || $WO_STAT == 4) { ?>
					                        <button class="btn btn-success" type="button" onClick="selectitem();">
					                        <i class="glyphicon glyphicon-th-list"></i>&nbsp;&nbsp;<?php echo $SelectItem; ?>
					                        </button>
					                    <?php } ?>
									</div>
				                </div>
							</div>
						</div>
					</div>
                    <div class="col-md-12">
                        <div class="box box-primary">
	                        <div class="search-table-outter">
	                            <table id="example" class="table table-bordered table-striped" width="100%">
		                        	<tr style="background:#CCCCCC">
			                            <th width="2%" style="text-align:left; vertical-align: middle;">&nbsp;</th>
			                            <th width="15%" style="text-align:center; vertical-align: middle;"><?php echo $ItemName ?> </th>
			                            <th width="5%" style="text-align:center; vertical-align: middle;"><?php echo $Planning; ?> </th>
			                            <th width="5%" style="text-align:center; vertical-align: middle;"><?php echo $Requested; ?> </th>
			                            <th width="5%" style="text-align:center; vertical-align: middle;"><?php echo $RequestNow; ?></th>
			                            <th width="8%" style="text-align:center; vertical-align: middle;"><?php echo $Price; ?></th>
			                            <th width="5%" style="text-align:center; vertical-align: middle;"><?php echo $Unit ?> </th>
			                            <th width="9%" style="text-align:center; vertical-align: middle;"><?php echo $Amount ?></th>
										<th style="text-align:center;display: none;">Discount (%)</th>
										<th style="text-align:center;display: none;">Discount Price</th>
			                            <th width="5%" style="text-align:center; vertical-align: middle;"><?php echo $Tax ?> PPN</th>
			                            <th width="5%" style="text-align:center; vertical-align: middle;"><?php echo $Tax ?> PPH</th>
			                            <th width="7%" style="text-align:center; vertical-align: middle;">Total</th>
			                            <th width="10%" style="text-align:center; vertical-align: middle;">Des.</th>
		                          	</tr>
		                            <tr style="background:#CCCCCC">
		                            </tr>
		                            <?php
		                            if($task == 'edit')
		                            {
		                                $sqlDET	= "SELECT A.*,
														B.ITM_NAME,
														C.JOBDESC, C.JOBPARENT
													FROM tbl_wo_detail A
														INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
															AND B.PRJCODE = '$PRJCODE'
														INNER JOIN tbl_joblist_detail C ON A.JOBCODEID = C.JOBCODEID
															AND C.PRJCODE = '$PRJCODE'
															AND A.ITM_CODE = C.ITM_CODE
														LEFT JOIN tbl_wo_header D ON D.WO_NUM = A.WO_NUM
														    AND D.JOBCODEID = C.JOBPARENT
													WHERE A.WO_NUM = '$WO_NUM'
														AND B.PRJCODE = '$PRJCODE'";
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
											$WO_DISC		= $row->WO_DISC;
											$WO_DISCP		= $row->WO_DISCP;
											$WO_TOTAL 		= $row->WO_TOTAL;
											$WO_DESC 		= $row->WO_DESC;
											$ITM_BUDG		= $row->ITM_BUDG;
											$TAXCODE1		= $row->TAXCODE1;
											$TAXCODE2		= $row->TAXCODE2;
											$TAXPRICE1		= $row->TAXPRICE1;
											$TAXPRICE2		= $row->TAXPRICE2;
											$WO_TOTAL2		= $row->WO_TOTAL2;
											$itemConvertion	= 1;

											$JOBPARENT		= $row->JOBPARENT;
											$JOBDESCH		= "";
											$sqlJOBDESC		= "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JOBPARENT' LIMIT 1";
											$resJOBDESC		= $this->db->query($sqlJOBDESC)->result();
											foreach($resJOBDESC as $rowJOBDESC) :
												$JOBDESCH	= $rowJOBDESC->JOBDESC;
											endforeach;

										/*	if ($j==1) {
												echo "<tr class=zebra1>";
												$j++;
											} else {
												echo "<tr class=zebra2>";
												$j--;
											}*/
											?>
		                                    <tr id="tr_<?php echo $currentRow; ?>" style="vertical-align: middle;">
											<td height="25" style="text-align:left; vertical-align: middle;">
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
										    <input type="hidden" id="chk" name="chk" value="<?php echo $currentRow; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">
		                                    	<!-- Checkbox -->
		                                 	</td>
										  	<td style="text-align:left; min-width:100px; vertical-align: middle;" nowrap>
												<div>
											  		<span><?php echo "$JOBCODEID - $ITM_NAME"; ?></span>
											  	</div>
											  	<div style="margin-left: 15px; font-style: italic;">
											  		<i class="text-muted fa fa-rss"></i>&nbsp;&nbsp;
											  		<?php
											  			$JOBDS 	= strlen($JOBDESCH);
											  			if($JOBDS > 50)
											  			{
											  				echo cut_text ($JOBDESCH, 45);
											  				echo " ...";
											  			}
											  			else
											  			{
											  				echo $JOBDESCH;
											  			}
											  		?>
											  	</div>
		                                      	<input type="hidden" name="data[<?php echo $currentRow; ?>][WO_NUM]" id="data<?php echo $currentRow; ?>WO_NUM" value="<?php echo $WO_NUM; ?>" class="form-control" style="max-width:300px;">
		                                      	<input type="hidden" name="data[<?php echo $currentRow; ?>][WO_CODE]" id="data<?php echo $currentRow; ?>WO_CODE" value="<?php echo $WO_CODE; ?>" class="form-control" style="max-width:300px;">
		                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][JOBCODEDET]" id="data<?php echo $currentRow; ?>JOBCODEDET" value="<?php echo $JOBCODEDET; ?>" class="form-control" >
		                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][JOBCODEID]" id="data<?php echo $currentRow; ?>JOBCODEID" value="<?php echo $JOBCODEID; ?>" class="form-control" >

		                                      	<input type="hidden" id="data<?php echo $currentRow; ?>ITM_CODE" name="data[<?php echo $currentRow; ?>][ITM_CODE]" value="<?php echo $ITM_CODE; ?>" class="form-control" style="max-width:300px;">
		                                      	<input type="hidden" id="data<?php echo $currentRow; ?>SNCODE" name="data[<?php echo $currentRow; ?>][SNCODE]" value="<?php echo $SNCODE; ?>" class="form-control" style="max-width:300px;">
		                                        <!-- Item Name -->
		                                 	</td>
											<?php
												// CARI TOTAL WORKED BUDGET APPROVED
												$TOTPRAMOUNT	= 0;
												$TOTPRQTY		= 0;
												$sqlTOTBUDG		= "SELECT SUM(A.WO_VOLM * A.ITM_PRICE) AS TOTWOAMOUNT,
																		SUM(A.WO_VOLM) AS TOTWOQTY
																	FROM tbl_wo_detail A
																		INNER JOIN tbl_wo_header B ON A.WO_NUM = B.WO_NUM
																			AND B.PRJCODE = '$PRJCODE'
																	WHERE A.ITM_CODE = '$ITM_CODE' AND A.PRJCODE = '$PRJCODE'
																		AND A.JOBCODEID = '$JOBCODEID' AND B.WO_STAT IN (3,6)
																		AND A.WO_NUM != '$WO_NUM'";
												$resTOTBUDG		= $this->db->query($sqlTOTBUDG)->result();
												foreach($resTOTBUDG as $rowTOTBUDG) :
													$TOTWOAMOUNT	= $rowTOTBUDG->TOTWOAMOUNT;
													$TOTWOQTY		= $rowTOTBUDG->TOTWOQTY;
												endforeach;

												$TOT_USEBUDG	= $TOTPRAMOUNT;					// 15
												$ITM_BUDG		= $row->ITM_BUDG;				// 16
												$UNITTYPE		= strtoupper($ITM_UNIT);

												if($ITM_BUDG == '')
													$ITM_BUDG	= 0;

												/* HIDDEN ON 27 JAN 2019
												if($PRJCODE == 'KTR')
												{
													$sqlITM		= "SELECT DISTINCT ITM_VOLM, ITM_TOTALP FROM tbl_item
																	WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE' LIMIT 1";
												}
												else
												{*/
													$sqlITM		= "SELECT DISTINCT ITM_VOLM, ITM_BUDG AS ITM_TOTALP
																		FROM tbl_joblist_detail
																	WHERE PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBCODEID' LIMIT 1";
												//}
												$ITM_BUDQTY		= 0;
												$resITM			= $this->db->query($sqlITM)->result();
												foreach($resITM as $rowITM) :
													$ITM_BUDQTY	= $rowITM->ITM_VOLM;
													$ITM_TOTALP	= $rowITM->ITM_TOTALP;
												endforeach;

												$disPrice 		= 0;
												$TOTPRQTY		= $TOTWOQTY;

												if($UNITTYPE == 'LS')
												{
													$disPrice 	= 1;
													$ITM_BUDG	= $ITM_TOTALP;
													$ITM_BUDQTY	= $ITM_TOTALP;
													$TOTPRQTY	= $TOTWOAMOUNT;
												}

												$disRow 		= 1;
												if($WO_STAT == 1 || $WO_STAT == 4)
												{
													$disRow 	= 0;
												}

												// SISA QTY PR
												$REMPRQTY		= $ITM_BUDQTY - $TOTPRQTY;
											?>
											<td style="text-align:right; vertical-align: middle;"> <!-- Item Bdget -->
		                                    	<?php echo number_format($ITM_BUDQTY, $decFormat); ?>
		                                        <input type="hidden" class="form-control" style="min-width:100px; max-width:100px; text-align:right" name="ITM_BUDGQTYx<?php echo $currentRow; ?>" id="ITM_BUDGQTYx<?php echo $currentRow; ?>" value="<?php echo number_format($ITM_BUDQTY, $decFormat); ?>" size="10" disabled >
		                                        <input type="hidden" style="text-align:right" name="ITM_BUDGQTY<?php echo $currentRow; ?>" id="ITM_BUDGQTY<?php echo $currentRow; ?>" value="<?php echo $ITM_BUDQTY; ?>" >
		                                        <input type="hidden" id="data<?php echo $currentRow; ?>ITM_BUDG" name="data[<?php echo $currentRow; ?>][ITM_BUDG]" value="<?php echo $ITM_BUDG; ?>">
		                                  	</td>
										  	<td style="text-align:right; vertical-align: middle;">  <!-- Item Requested FOR INFORMATION ONLY -->
		                                    	<?php print number_format($TOTPRQTY, $decFormat); ?>
		                                        <input type="hidden" class="form-control" style="min-width:100px; max-width:100px; text-align:right" name="TOTWOQTY<?php echo $currentRow; ?>" id="TOTWOQTYx<?php echo $currentRow; ?>" value="<?php print number_format($TOTPRQTY, $decFormat); ?>" size="10" disabled >
		                                        <input type="hidden" class="form-control" style="min-width:100px; max-width:100px; text-align:right" name="ITM_REQUESTED<?php echo $currentRow; ?>" id="ITM_REQUESTED<?php echo $currentRow; ?>" value="<?php print $TOTWOAMOUNT; ?>" >
		                                        <input type="hidden" class="form-control" style="text-align:right" name="TOTWOQTY<?php echo $currentRow; ?>" id="TOTWOQTY<?php echo $currentRow; ?>" value="<?php print $TOTWOQTY; ?>" >
		                                 	</td>
		                                    <td style="text-align:right; vertical-align: middle;" nowrap> <!-- Item Request Now -- PR_VOLM -->
		                                     	<?php if($disRow == 1) { ?>
		                                     		<?php echo number_format($WO_VOLM, $decFormat); ?>
		                                        	<input type="hidden" name="WO_VOLM<?php echo $currentRow; ?>" id="WO_VOLM<?php echo $currentRow; ?>" value="<?php echo number_format($WO_VOLM, $decFormat); ?>" class="form-control" style="min-width:100px; max-width:100px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion_2(this,<?php echo $currentRow; ?>);" >
			                                    <?php } else { ?>
		                                        	<input type="text" name="WO_VOLM<?php echo $currentRow; ?>" id="WO_VOLM<?php echo $currentRow; ?>" value="<?php echo number_format($WO_VOLM, $decFormat); ?>" class="form-control" style="min-width:100px; max-width:100px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion_2(this,<?php echo $currentRow; ?>);" >
			                                    <?php } ?>
		                                        
		                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][WO_VOLM]" id="data<?php echo $currentRow; ?>WO_VOLM" value="<?php echo $WO_VOLM; ?>" class="form-control" style="max-width:300px;" >
		                                    </td>
		                                    <td style="text-align:right; vertical-align: middle;" nowrap>
		                                     	<?php if($disRow == 1) { ?>
		                                     		<?php echo number_format($ITM_PRICE, $decFormat); ?>
		                                        	<input type="hidden" name="ITM_PRICE<?php echo $currentRow; ?>" id="ITM_PRICE<?php echo $currentRow; ?>" value="<?php echo number_format($ITM_PRICE, $decFormat); ?>" class="form-control" style="min-width:100px; max-width:100px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion_1(this,<?php echo $currentRow; ?>);" >
			                                    <?php } else { ?>
		                                        	<?php if($disPrice == 1) { ?>
		                                        		<?php echo number_format($ITM_PRICE, $decFormat); ?>
		                                        		<input type="hidden" name="ITM_PRICE<?php echo $currentRow; ?>" id="ITM_PRICE<?php echo $currentRow; ?>" value="<?php echo number_format($ITM_PRICE, $decFormat); ?>" class="form-control" style="min-width:100px; max-width:100px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion_1(this,<?php echo $currentRow; ?>);" >
				                                    <?php } else { ?>
			                                        	<input type="text" name="ITM_PRICE<?php echo $currentRow; ?>" id="ITM_PRICE<?php echo $currentRow; ?>" value="<?php echo number_format($ITM_PRICE, $decFormat); ?>" style="min-width:100px; max-width:100px; text-align:right" class="form-control" style="text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion_1(this,<?php echo $currentRow; ?>);" >
				                                    <?php } ?>
			                                    <?php } ?>
		                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][ITM_PRICE]" id="data<?php echo $currentRow; ?>ITM_PRICE" value="<?php echo $ITM_PRICE; ?>" class="form-control" style="min-width:100px; max-width:100px;" >
		                                        <input type="hidden" style="text-align:right" name="itemConvertion<?php echo $currentRow; ?>" id="itemConvertion<?php echo $currentRow; ?>" value="<?php echo $itemConvertion; ?>" >
		                                    </td>
										  	<td nowrap style="text-align:center; vertical-align: middle;">
											  <?php echo $ITM_UNIT; ?>
		                                    	<input type="hidden" name="data[<?php echo $currentRow; ?>][ITM_UNIT]" id="data<?php echo $currentRow; ?>ITM_UNIT" value="<?php echo $ITM_UNIT; ?>" class="form-control" style="max-width:300px;" >
		                                    <!-- Item Unit Type -- ITM_UNIT --></td>
										  	<td nowrap style="text-align:right; vertical-align: middle;">
		                                     	<?php if($disRow == 1) { ?>
		                                     		<?php echo number_format($WO_TOTAL, $decFormat); ?>
		                                    		<input type="hidden" class="form-control" style="min-width:100px; max-width:100px; text-align:right" name="WO_TOTAL<?php echo $currentRow; ?>" id="WO_TOTAL<?php echo $currentRow; ?>" value="<?php print number_format($WO_TOTAL, $decFormat); ?>" size="10" disabled >
			                                    <?php } else { ?>
		                                    		<input type="text" class="form-control" style="min-width:100px; max-width:100px; text-align:right" name="WO_TOTAL<?php echo $currentRow; ?>" id="WO_TOTAL<?php echo $currentRow; ?>" value="<?php print number_format($WO_TOTAL, $decFormat); ?>" size="10" disabled >
			                                    <?php } ?>
		                                        
		                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][WO_TOTAL]" id="data<?php echo $currentRow; ?>WO_TOTAL" value="<?php echo $WO_TOTAL; ?>" class="form-control" style="max-width:150px; text-align:right" onKeyPress="return isIntOnlyNew(event);" >
		                                    </td>
											<td style="text-align:center;display: none;">
		                                        <input type="number" name="WO_DISC<?php echo $currentRow; ?>" id="WO_DISC<?php echo $currentRow; ?>" min="0" max="100" step="0.01" value="<?php echo number_format($WO_DISC, $decFormat);?>" class="form-control" style="min-width:80px; max-width:80px; text-align:right;" onKeyPress="return isIntOnlyNew(event);" onBlur="getDiscount(this, <?php echo $currentRow; ?>);">
												<input type="hidden" name="data[<?php echo $currentRow; ?>][WO_DISC]" id="data<?php echo $currentRow; ?>WO_DISC" value="<?php echo $WO_DISC;?>" class="form-control" style="max-width:300px;" >
		                                    </td>
											<td style="text-align:center;display: none;">
												<input type="text" name="WO_DISCP<?php echo $currentRow; ?>" id="WO_DISCP<?php echo $currentRow; ?>" value="<?php echo number_format($WO_DISCP, $decFormat);?>" class="form-control" style="min-width:100px; max-width:100px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getDiscountP(this,<?php echo $currentRow; ?>);" >
												<input type="hidden" name="data[<?php echo $currentRow; ?>][WO_DISCP]" id="data<?php echo $currentRow; ?>WO_DISCP" value="<?php echo $WO_DISCP;?>" class="form-control" style="max-width:300px;" >
		                                    </td>
										  	<td nowrap style="text-align:right; vertical-align: middle;">
		                                     	<?php if($disRow == 1) { ?>
		                                     		<?php echo number_format($TAXPRICE1, $decFormat); ?>
			                                        <select name="data[<?php echo $currentRow; ?>][TAXCODE1]" id="TAXCODE1<?php echo $currentRow; ?>" class="form-control" style="max-width:150px; display: none;" onChange="getConvertion(this,<?php echo $currentRow; ?>);">
			                                        	<option value=""> --- </option>
			                                        	<option value="TAX01" <?php if($TAXCODE1 == 'TAX01') { ?> selected <?php } ?>>PPn 10%</option>
			                                        </select>
			                                    <?php } else { ?>
			                                        <select name="data[<?php echo $currentRow; ?>][TAXCODE1]" id="TAXCODE1<?php echo $currentRow; ?>" class="form-control" style="min-width: 80px; max-width:150px" onChange="getConvertion(this,<?php echo $currentRow; ?>);">
			                                        	<option value=""> --- </option>
			                                        	<option value="TAX01" <?php if($TAXCODE1 == 'TAX01') { ?> selected <?php } ?>>PPn 10%</option>
			                                        </select>
			                                    <?php } ?>
		                                        
		                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][TAXPRICE1]" id="data<?php echo $currentRow; ?>TAXPRICE1" size="20" value="<?php echo $TAXPRICE1; ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:left">
		                                    </td>
		                                    <td nowrap style="text-align:right; vertical-align: middle;">
		                                     	<?php if($disRow == 1) { ?>
		                                     		<?php echo number_format($TAXPRICE2, $decFormat); ?>
				                                    <select name="data[<?php echo $currentRow; ?>][TAXCODE2]" id="TAXCODE2<?php echo $currentRow; ?>" class="form-control" style="max-width:150px; display: none;" onChange="getConvertion(this,<?php echo $currentRow; ?>);">
			                                        	<option value=""> no tax </option>
			                                        	<option value="TAX02" <?php if($TAXCODE2 == 'TAX02') { ?> selected <?php } ?>>PPh 2%</option>
			                                        	<option value="TAX03" <?php if($TAXCODE2 == 'TAX03') { ?> selected <?php } ?>>PPh 3%</option>
			                                        	<option value="TAX04" <?php if($TAXCODE2 == 'TAX04') { ?> selected <?php } ?>>PPh 4%</option>
			                                        	<option value="TAX20" <?php if($TAXCODE2 == 'TAX20') { ?> selected <?php } ?>>PPh 20%</option>
			                                        </select>
			                                    <?php } else { ?>
				                                    <select name="data[<?php echo $currentRow; ?>][TAXCODE2]" id="TAXCODE2<?php echo $currentRow; ?>" class="form-control" style="min-width: 80px; max-width:150px" onChange="getConvertion(this,<?php echo $currentRow; ?>);">
			                                        	<option value=""> --- </option>
			                                        	<option value="TAX02" <?php if($TAXCODE2 == 'TAX02') { ?> selected <?php } ?>>PPh 2%</option>
			                                        	<option value="TAX03" <?php if($TAXCODE2 == 'TAX03') { ?> selected <?php } ?>>PPh 3%</option>
			                                        	<option value="TAX04" <?php if($TAXCODE2 == 'TAX04') { ?> selected <?php } ?>>PPh 4%</option>
			                                        	<option value="TAX20" <?php if($TAXCODE2 == 'TAX20') { ?> selected <?php } ?>>PPh 20%</option>
			                                        </select>
			                                    <?php } ?>

		                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][TAXPRICE2]" id="data<?php echo $currentRow; ?>TAXPRICE2" size="20" value="<?php echo $TAXPRICE2; ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:left">
		                                    </td>
										  	<td style="text-align:right; vertical-align: middle;">
		                                     	<?php if($disRow == 1) { ?>
		                                     		<?php echo number_format($WO_TOTAL2, $decFormat); ?>
				                                    <input type="hidden" name="data<?php echo $currentRow; ?>WO_TOTAL2A" id="WO_TOTAL2A<?php echo $currentRow; ?>" value="<?php echo number_format($WO_TOTAL2, 2); ?>" class="form-control" style="min-width:130px; max-width:130px; text-align:right;" onKeyPress="return isIntOnlyNew(event);" disabled >
			                                    <?php } else { ?>
				                                    <input type="text" name="data<?php echo $currentRow; ?>WO_TOTAL2A" id="WO_TOTAL2A<?php echo $currentRow; ?>" value="<?php echo number_format($WO_TOTAL2, 2); ?>" class="form-control" style="min-width:130px; max-width:130px; text-align:right;" onKeyPress="return isIntOnlyNew(event);" disabled >
			                                    <?php } ?>

		                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][WO_TOTAL2]" id="data<?php echo $currentRow; ?>WO_TOTAL2" value="<?php echo $WO_TOTAL2; ?>" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" >
		                              		</td>
										  	<td style="text-align:left;" nowrap>
		                                     	<?php if($disRow == 1) { ?>
		                                     		<?php echo $WO_DESC; ?>
				                                    <input type="hidden" name="data[<?php echo $currentRow; ?>][WO_DESC]" id="data<?php echo $currentRow; ?>WO_DESC" size="20" value="<?php echo $WO_DESC; ?>" class="form-control" style="min-width:130px; max-width:150px; text-align:left">
			                                    <?php } else { ?>
				                                    <input type="text" name="data[<?php echo $currentRow; ?>][WO_DESC]" id="data<?php echo $currentRow; ?>WO_DESC" size="20" value="<?php echo $WO_DESC; ?>" class="form-control" style="min-width:130px; max-width:150px; text-align:left">
			                                    <?php } ?>

		                                        
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
					<div class="col-md-6">
		                <div class="form-group">
		                	<label for="inputName" class="col-sm-3 control-label">&nbsp;</label>
		                    <div class="col-sm-9">
		                    	<?php
									if($task=='add')
									{
										if($WO_STAT == 1 && $ISCREATE == 1)
										{
											?>
												<button class="btn btn-primary">
												<i class="fa fa-save"></i></button>&nbsp;
											<?php
										}
									}
									elseif($task=='edit')
									{
										if(($ISCREATE == 1 && $ISAPPROVE == 1) && ($WO_STAT == 1))
										{
											?>
		                                        <button class="btn btn-primary">
		                                        <i class="fa fa-save"></i></button>&nbsp;
											<?php
										}
										else if($ISCREATE == 1 && ($WO_STAT == 1 || $WO_STAT == 4))
										{
											?>
												<button class="btn btn-primary" >
												<i class="fa fa-save"></i></button>&nbsp;
											<?php
										}
									}
									?>
                                        <button class="btn btn-primary" style="display:none" id="tblClose">
                                        <i class="fa fa-save"></i></button>
									<?php

									$backURL	= site_url('c_project/c_s180d0bpk/gallS180d0bpk/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
									echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>');
		                        ?>
		                    </div>
		                </div>
		          	</div>
			        <div class="col-md-12">
						<?php
                            $DOC_NUM	= $WO_NUM;
                            $sqlCAPPH	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM'";
                            $resCAPPH	= $this->db->count_all($sqlCAPPH);
							$sqlAPP		= "SELECT * FROM tbl_docstepapp WHERE MENU_CODE = '$MenuApp'
											AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE proj_Code = '$PRJCODE_LEV')";
							$resAPP		= $this->db->query($sqlAPP)->result();
							foreach($resAPP as $rowAPP) :
								$MAX_STEP		= $rowAPP->MAX_STEP;
								$APPROVER_1		= $rowAPP->APPROVER_1;
								$APPROVER_2		= $rowAPP->APPROVER_2;
								$APPROVER_3		= $rowAPP->APPROVER_3;
								$APPROVER_4		= $rowAPP->APPROVER_4;
								$APPROVER_5		= $rowAPP->APPROVER_5;
							endforeach;
							
                        	if($resCAPP == 0)
                        	{
                        		if($LangID == 'IND')
								{
									$zerSetApp	= "Belum ada pengaturan untuk persetujuan dokumen ini.";
								}
								else
								{
									$zerSetApp	= "There are no arrangements for the approval of this document.";
								}
                        		?>
                        			<div class="alert alert-warning alert-dismissible">
					                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					                <?php echo $zerSetApp; ?>
					              	</div>
                        		<?php
                        	}
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
			        </div>
		        </form>
		    </div>
        	<?php
				$act_lnk = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        		if($DefEmp_ID == 'D15040004221')
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

		/*$(document).bind('keydown keyup', function(e) {
		    if(e.which === 116) {
		       console.log('blocked');
		       return false;
		    }
		    if(e.which === 82 && e.ctrlKey) {
		       console.log('blocked');
		       return false;
		    }
		});*/

		$('#frm').validate({
	    	submitHandler: function(form)
	    	{
	    		WO_CATEG	= document.getElementById("WO_CATEG").value;
				FPA_CODE	= document.getElementById("FPA_CODE1").value;
				if(WO_CATEG == 'SALT' && FPA_CODE == '')
				{
					swal('<?php echo $alert5; ?>',
					{
						icon:"warning",
					})
					.then(function()
					{
						document.getElementById('FPA_CODE1').focus();
					});
					return false;
				}

				SPLCODE		= document.getElementById('SPLCODE').value;
				if(SPLCODE == '')
				{
					swal('<?php echo $alert4; ?>',
					{
						icon:"warning",
					})
					.then(function()
					{
						document.getElementById('SPLCODE').focus();
					});
					return false;
				}


				PR_REFNO1	= $('#PR_REFNO').val();
				if(PR_REFNO1 == null)
				{
					swal('<?php echo $alert3; ?>',
					{
						icon:"warning",
					})
					.then(function()
					{
						document.getElementById('PR_REFNO').focus();
					});
					return false;
				}

				PR_REFNO 	= PR_REFNO1.join("~");
				PRJCODE		= document.getElementById('PRJCODE').value;

				if(PR_REFNO == '')
				{
					swal('<?php echo $alert3; ?>',
					{
						icon:"warning",
					})
					.then(function()
					{
						document.getElementById('PR_REFNO').focus();
					});
					return false;
				}

				var totrow 		= document.getElementById('totalrow').value;

				for(i=1;i<=totrow;i++)
				{
					var WO_VOLM	= parseFloat(eval(document.getElementById('WO_VOLM'+i)).value.split(",").join(""));
					if(WO_VOLM == 0)
					{
						swal('<?php echo $alert1; ?>',
						{
							icon:"warning",
						})
						.then(function()
						{
							document.getElementById('WO_VOLM'+i).value = '0';
							document.getElementById('WO_VOLM'+i).focus();
						});
						return false;
					}
				}

				WO_DPREF1	= document.getElementById("WO_DPREF1").value;
				if(WO_DPREF1 != '')
				{
					WO_DPPER	= document.getElementById('WO_DPPER').value;
					if(WO_DPPER == 0)
					{
						swal('<?php echo $alert7; ?>',
						{
							icon:"warning",
						})
						.then(function()
						{
							document.getElementById('WO_DPPER').focus();
						});
						return false;
					}
				}

				WO_DPPER	= document.getElementById("WO_DPPER").value;
				/*if(WO_DPPER != 0)
				{
					WO_DPREF1	= document.getElementById('WO_DPREF1').value;
					if(WO_DPREF1 == '')
					{
						swal('<?php echo $alert8; ?>');
						document.getElementById('WO_DPREF1').focus();
						return false;
					}
				}*/

				WO_STAT		= document.getElementById("WO_STAT").value;
				if(WO_STAT == 6)
				{
					WO_MEMO		= document.getElementById('WO_MEMO').value;
					if(WO_MEMO == '')
					{
						swal('<?php echo $alert6; ?>',
						{
							icon:"warning",
						})
						.then(function()
						{
							document.getElementById('WO_MEMO').focus();
						});
						return false;
					}
				}

				if(totrow == 0)
				{
					swal('<?php echo $alert2; ?>',
					{
						icon:"warning",
					});
					return false;
				}

				if($(form).data('submitted')==true){
			      swal('<?php echo $alertSubmit;?>');
			      return false;
			    } else {
			      //swal('submitting');
			      $(form).data('submitted', true);
			      return true;
			    }
	    	}
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
				var PattTable 			= "tbl_wo_header";
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
			var WO_DATE = $('#datepicker').val();
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
			xmlhttpTask.open("GET","<?php echo base_url().'index.php/__l1y/GetCodeDoc/';?>"+PRJCODE+"~"+WO_DATE,true);
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

	function add_DP(strItem)
	{
		arrItem = strItem.split('|');
		DP_NUM		= arrItem[0];
		DP_CODE 	= arrItem[1];
		DP_AMOUNT 	= arrItem[2];
		document.getElementById('WO_DPREF').value	= DP_NUM;
		document.getElementById('WO_DPREF1').value	= DP_CODE;
		document.getElementById('WO_DPVAL').value	= DP_AMOUNT;
	}

	function add_FPA(strItem)
	{
		arrItem = strItem.split('|');
		WO_NUM		= arrItem[0];
		WO_CODE 	= arrItem[1];
		document.getElementById('FPA_NUM').value	= WO_NUM;
		document.getElementById('FPA_CODE1').value	= WO_CODE;
	}

	function add_item(strItem)
	{
		arrItem = strItem.split('|');
		var objTable, objTR, objTD, intIndex, arrItem;
		var WO_NUMx 	= "<?php echo $DocNumber; ?>";

		var WO_CODEx 	= "<?php echo $WO_CODE; ?>";
		ilvl = arrItem[1];

		var decFormat	= document.getElementById('decFormat').value;

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
		ITMUNIT 		= arrItem[7];
		ITM_PRICE 		= arrItem[8];
		ITM_PRICEV		= ITM_PRICE;
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

		ITM_UNIT		= ITMUNIT.toUpperCase();

		// VIEW ONLY
		if(ITM_UNIT == 'LS' )
		{
			/*ITM_BUDGQTY	= ITM_BUDG;
			//TOT_USEDQTY	= TOT_USEBUDG; proses sebelumnya
			TOT_USEDQTY	= TOT_USEDQTY;
			ITM_PRICEV	= 1;*/
		}

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
		objTD.innerHTML = ''+doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_BUDGQTY)),decFormat))+'<input type="hidden" class="form-control" style="min-width:90px; max-width:90px; text-align:right" name="ITM_BUDGQTYx'+intIndex+'" id="ITM_BUDGQTYx'+intIndex+'" value="'+ITM_BUDGQTY+'" disabled ><input type="hidden" style="text-align:right" name="ITM_BUDGQTY'+intIndex+'" id="ITM_BUDGQTY'+intIndex+'" value="'+ITM_BUDGQTY+'" ><input type="hidden" id="data'+intIndex+'ITM_BUDG" name="data['+intIndex+'][ITM_BUDG]" value="'+ITM_BUDG+'">';

		// Item Worked FOR INFORMATION ONLY
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = ''+doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TOT_USEDQTY)),decFormat))+'<input type="hidden" class="form-control" style="min-width:100px; max-width:100px; text-align:right" name="ITM_REQUESTED'+intIndex+'" id="ITM_REQUESTED'+intIndex+'" value="'+TOT_USEBUDG+'" ><input type="hidden" class="form-control" style="min-width:110px; max-width:300px; text-align:right" name="ITM_REQUESTEDx'+intIndex+'" id="ITM_REQUESTEDx'+intIndex+'" value="'+TOT_USEBUDG+'" ><input type="hidden" class="form-control" style="text-align:right" name="TOTWOQTY'+intIndex+'" id="TOTWOQTY'+intIndex+'" value="'+TOT_USEDQTY+'" ><input type="hidden" class="form-control" style="min-width:90px; max-width:90px; text-align:right" name="TOTWOQTY'+intIndex+'" id="TOTWOQTYx'+intIndex+'" value="'+TOT_USEDQTY+'" disabled >';

		// Item Worked Now -- WO_VOLM
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" name="WO_VOLM'+intIndex+'" id="WO_VOLM'+intIndex+'" value="0.00" class="form-control" style="min-width:100px; max-width:100px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion_2(this,'+intIndex+');" ><input type="hidden" name="data['+intIndex+'][WO_VOLM]" id="data'+intIndex+'WO_VOLM" value="0" class="form-control" style="max-width:300px;" >';

		// Item Price -- ITM_PRICE
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		if(ITM_UNIT == 'LS' || ITM_UNIT == 'LUMP')
		{
			objTD.innerHTML = '-<input type="hidden" name="ITM_PRICE'+intIndex+'" id="ITM_PRICE'+intIndex+'" value="'+ITM_PRICEV+'" class="form-control" style="min-width:100px; max-width:100px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion_1(this,'+intIndex+');" disabled><input type="hidden" name="data['+intIndex+'][ITM_PRICE]" id="data'+intIndex+'ITM_PRICE" value="'+ITM_PRICE+'" class="form-control" style="min-width:100px; max-width:100px;" ><input type="hidden" style="text-align:right" name="itemConvertion'+intIndex+'" id="itemConvertion'+intIndex+'" value="'+itemConvertion+'" >';
		}
		else
		{
			objTD.innerHTML = '<input type="text" name="ITM_PRICE'+intIndex+'" id="ITM_PRICE'+intIndex+'" value="'+ITM_PRICEV+'" class="form-control" style="min-width:100px; max-width:100px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion_1(this,'+intIndex+');" ><input type="hidden" name="data['+intIndex+'][ITM_PRICE]" id="data'+intIndex+'ITM_PRICE" value="'+ITM_PRICE+'" class="form-control" style="min-width:100px; max-width:100px;" ><input type="hidden" style="text-align:right" name="itemConvertion'+intIndex+'" id="itemConvertion'+intIndex+'" value="'+itemConvertion+'" >';
		}

		// Item Unit Type -- ITM_UNIT
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = ''+ITM_UNIT+'<input type="hidden" name="data['+intIndex+'][ITM_UNIT]" id="data'+intIndex+'ITM_UNIT" value="'+ITM_UNIT+'" class="form-control" style="max-width:300px;" >';

		// WO JUMLAH PRICE X VOLM
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="hidden" name="data['+intIndex+'][WO_TOTAL]" id="data'+intIndex+'WO_TOTAL" value="0.00" class="form-control" style="max-width:150px; text-align:right" onKeyPress="return isIntOnlyNew(event);" ><input type="text" class="form-control" style="min-width:100px; max-width:100px; text-align:right" name="WO_TOTAL'+intIndex+'" id="WO_TOTAL'+intIndex+'" value="0.00" size="10" disabled >';

		//discount
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.style.display = 'none';
		objTD.innerHTML = '<input type="number" name="WO_DISC'+intIndex+'" id="WO_DISC'+intIndex+'" min="0" max="100" step="0.01" value="0.00" class="form-control" style="min-width:100px; max-width:100px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getDiscount(this,'+intIndex+');" ><input type="hidden" name="data['+intIndex+'][WO_DISC]" id="data'+intIndex+'WO_DISC" value="0.00" class="form-control" style="max-width:300px;" >';

		//discount Price
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.style.display = 'none';
		objTD.innerHTML = '<input type="text" name="WO_DISCP'+intIndex+'" id="WO_DISCP'+intIndex+'" value="0.00" class="form-control" style="min-width:100px; max-width:100px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getDiscountP(this,'+intIndex+');" ><input type="hidden" name="data['+intIndex+'][WO_DISCP]" id="data'+intIndex+'WO_DISCP" value="0.00" class="form-control" style="max-width:300px;" >';

		// Tax PPN
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<select name="data['+intIndex+'][TAXCODE1]" id="TAXCODE1'+intIndex+'" class="form-control" style="min-width: 80px; max-width:150px" onChange="getConvertion(this,'+intIndex+');"><option value=""> --- </option><option value="TAX01">PPn 10%</option></select><input type="hidden" name="data['+intIndex+'][TAXPRICE1]" id="data'+intIndex+'TAXPRICE1" size="20" value="0" class="form-control" style="min-width:110px; max-width:300px; text-align:left">';

		// Tax PPH
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<select name="data['+intIndex+'][TAXCODE2]" id="TAXCODE2'+intIndex+'" class="form-control" style="min-width: 80px; max-width:150px" onChange="getConvertion(this.value,'+intIndex+');"><option value=""> --- </option><option value="TAX02">PPh 2%</option><option value="TAX03">PPh 3%</option><option value="TAX04">PPh 4%</option><option value="TAX20">PPh 20%</option></select><input type="hidden" name="data['+intIndex+'][TAXPRICE2]" id="data'+intIndex+'TAXPRICE2" size="20" value="0" class="form-control" style="min-width:110px; max-width:300px; text-align:left">';

		// Remarks -- WO_DESC
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = '<input type="hidden" name="data['+intIndex+'][WO_TOTAL2]" id="data'+intIndex+'WO_TOTAL2" value="0.00" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" ><input type="text" name="data'+intIndex+'WO_TOTAL2A" id="WO_TOTAL2A'+intIndex+'" value="0.00" class="form-control" style="min-width:130px; max-width:130px; text-align:right;" onKeyPress="return isIntOnlyNew(event);" ><input type="hidden" name="data['+intIndex+'][WO_DESC]" id="data'+intIndex+'WO_DESC" size="20" value="" class="form-control" style="min-width:110px; max-width:100px; text-align:left">';

		// Remarks -- WO_DESC
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = '<input type="text" name="data['+intIndex+'][WO_DESC]" id="data'+intIndex+'WO_DESC" size="20" value="" class="form-control" style="min-width:130px; max-width:150px; text-align:left">';

		var PPMat_Budget											= document.getElementById('ITM_BUDGQTY'+intIndex).value
		document.getElementById('ITM_BUDGQTY'+intIndex).value 		= parseFloat(Math.abs(PPMat_Budget));
		//document.getElementById('ITM_BUDGQTYx'+intIndex).value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PPMat_Budget)),decFormat));
		var ITM_REQUESTED											= document.getElementById('ITM_REQUESTED'+intIndex).value;
		document.getElementById('TOTWOQTY'+intIndex).value 			= parseFloat(Math.abs(TOT_USEDQTY));
		document.getElementById('ITM_REQUESTED'+intIndex).value 	= parseFloat(Math.abs(ITM_REQUESTED));
		document.getElementById('ITM_REQUESTEDx'+intIndex).value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_REQUESTED)),decFormat));
		document.getElementById('TOTWOQTY'+intIndex).value 			= parseFloat(Math.abs(TOT_USEDQTY));
		document.getElementById('TOTWOQTYx'+intIndex).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TOT_USEDQTY)),decFormat));
		document.getElementById('ITM_PRICE'+intIndex).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_PRICEV)),decFormat));
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

	function getDiscount(thisVal1, row)
	{
		var decFormat		= document.getElementById('decFormat').value;

		thisVal 			= parseFloat(Math.abs(eval(thisVal1).value.split(",").join("")));

		$("#data"+row+"WO_DISC").val(thisVal);
		$("#WO_DISC"+row).val(doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(thisVal)),decFormat)));

		WO_TOTAL = parseFloat($("#data"+row+"WO_TOTAL").val());
		WO_DISC		= (WO_TOTAL * thisVal)/100;
		$("#data"+row+"WO_DISCP").val(WO_DISC);
		$("#WO_DISCP"+row).val(doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(WO_DISC)),decFormat)));

		thisVal				= parseFloat(document.getElementById('WO_VOLM'+row).value);				// Request Qty - Now
		getConvertion(thisVal, row);
	}

	function getDiscountP(thisVal1, row)
	{
		var decFormat		= document.getElementById('decFormat').value;

		thisVal 			= parseFloat(Math.abs(eval(thisVal1).value.split(",").join("")));

		$("#data"+row+"WO_DISCP").val(thisVal);
		$("#WO_DISCP"+row).val(doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(thisVal)),decFormat)));

		WO_TOTAL = parseFloat($("#data"+row+"WO_TOTAL").val());

		WO_DISCP	= (thisVal / WO_TOTAL) * 100;
		$("#data"+row+"WO_DISC").val(WO_DISCP);
		$("#WO_DISC"+row).val(doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(WO_DISCP)),decFormat)));

		thisVal				= parseFloat(document.getElementById('WO_VOLM'+row).value);				// Request Qty - Now
		getConvertion(thisVal, row);
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

	function getConvertion_2(thisVal1, row)
	{
		var decFormat		= document.getElementById('decFormat').value;

		thisVal 			= parseFloat(Math.abs(eval(thisVal1).value.split(",").join("")));

		document.getElementById('data'+row+'WO_VOLM').value		= thisVal;
		document.getElementById('WO_VOLM'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(thisVal)),decFormat));

		thisVal				= parseFloat(document.getElementById('WO_VOLM'+row).value);				// Request Qty - Now
		getConvertion(thisVal, row);
	}

	function getConvertion(thisVal1, row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		ITM_UNIT			= document.getElementById('data'+row+'ITM_UNIT').value;

		//swal(thisVal1)

		itemConvertion		= document.getElementById('itemConvertion'+row).value;
		WO_VOLM 			= parseFloat(document.getElementById('data'+row+'WO_VOLM').value);	// wo volm
		ITM_PRICE			= parseFloat(document.getElementById('data'+row+'ITM_PRICE').value);	// Item Price
		ITM_PRICE1			= parseFloat(document.getElementById('ITM_PRICE'+row).value);			// Item Price

		ITM_BUDGQTY			= parseFloat(document.getElementById('ITM_BUDGQTY'+row).value);			// Budget Qty
		ITM_BUDG			= parseFloat(document.getElementById('data'+row+'ITM_BUDG').value);		// Budget Amount
		TOTWOQTY			= document.getElementById('TOTWOQTY'+row).value;						// Total Requested

		TOTPRAMOUNT			= parseFloat(TOTWOQTY) * parseFloat(ITM_PRICE);							// Total Requested Amount
		//REQ_NOW_QTY1		= parseFloat(document.getElementById('WO_VOLM'+row).value);				// Request Qty - Now
		if(WO_VOLM == 0)
			REQ_NOW_QTY1		= parseFloat(eval(document.getElementById('WO_VOLM'+row)).value.split(",").join(""));
		else
			REQ_NOW_QTY1		= parseFloat(document.getElementById('data'+row+'WO_VOLM').value);

		REQ_NOW_QTY2		= REQ_NOW_QTY1 * itemConvertion;						// Request Qty 2 - Now
		REQ_NOW_AMOUNT		= parseFloat(REQ_NOW_QTY1) * parseFloat(ITM_PRICE);						// Request Qty Amount - Now
		//swal(REQ_NOW_QTY1+'*'+ITM_PRICE+'='+REQ_NOW_AMOUNT);
		//swal(REQ_NOW_AMOUNT)
		if(ITM_UNIT == 'LS')
		{
			ITM_PRICE		= 1;
			REQ_NOW_AMOUNT	= parseFloat(REQ_NOW_QTY1) * parseFloat(ITM_PRICE);
			TOTPRAMOUNT		= parseFloat(TOTWOQTY) * parseFloat(ITM_PRICE1);
			REM_WO_QTY		= parseFloat(ITM_BUDG) - parseFloat(TOTPRAMOUNT);
			REM_WO_AMOUNT	= parseFloat(ITM_BUDG) - parseFloat(TOTPRAMOUNT);
		}
		else
		{
			REQ_NOW_AMOUNT	= parseFloat(REQ_NOW_QTY1) * parseFloat(ITM_PRICE);
			REM_WO_QTY		= parseFloat(ITM_BUDGQTY) - parseFloat(TOTWOQTY);
			REM_WO_AMOUNT	= parseFloat(ITM_BUDG) - parseFloat(TOTPRAMOUNT);
		}

		//swal(REQ_NOW_AMOUNT+ ">" +REM_WO_AMOUNT)

		if(REQ_NOW_AMOUNT > REM_WO_AMOUNT)
		{
			REM_WO_QTYV		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(REM_WO_QTY)),decFormat));
			REM_WO_AMOUNTV	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(REM_WO_AMOUNT)),decFormat));
			swal('Request Qty is Greater than Budget. Maximum Qty is '+REM_WO_QTY+' or in Amount is '+REM_WO_AMOUNT);

			document.getElementById('data'+row+'WO_VOLM').value 	= REM_WO_QTY;
			document.getElementById('WO_VOLM'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(REM_WO_QTY)),decFormat));

			document.getElementById('data'+row+'WO_VOLM').value 	= REM_WO_QTY;
			document.getElementById('WO_VOLM'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(REM_WO_QTY)),decFormat));
			NEW_PRICE = parseFloat(REM_WO_AMOUNT / REM_WO_QTY);
			document.getElementById('data'+row+'ITM_PRICE').value	= NEW_PRICE;
			document.getElementById('ITM_PRICE'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(NEW_PRICE)),decFormat));

			REQ_NOW_AMOUNT	= parseFloat(REM_WO_QTY) * parseFloat(NEW_PRICE);
			document.getElementById('data'+row+'WO_TOTAL').value 	= REQ_NOW_AMOUNT;
			document.getElementById('WO_TOTAL'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(REQ_NOW_AMOUNT)),decFormat));

			//GET DISCOUNT VALUE
			// WO_DISC				= parseFloat($("#data"+row+"WO_DISC").val()); // discount (%)
			// WO_DISCP			= parseFloat($("#data"+row+"WO_DISCP").val()); // discount Price

			// GET TAX VALUE
			TAXCODE1	= document.getElementById('TAXCODE1'+row).value;
			TAXCODE2	= document.getElementById('TAXCODE2'+row).value;
			TAXPRICE1			= 0;
			TAXPRICE2			= 0;
			WO_TOTAL2			= REQ_NOW_AMOUNT;
			if(REQ_NOW_AMOUNT == 0){
				WO_TOTAL2_POT	= 0;
			}else{
				WO_TOTAL2_POT	= REQ_NOW_AMOUNT;
			}
			if(TAXCODE1 == 'TAX01')
			{
				TAXPRICE1 	= parseFloat(0.1 * REQ_NOW_AMOUNT);
			}

			if(TAXCODE2 == 'TAX02')
			{
				TAXPRICE2 	= parseFloat(0.02 * REQ_NOW_AMOUNT);
			}
			else if(TAXCODE2 == 'TAX03')
			{
				TAXPRICE2 	= parseFloat(0.03 * REQ_NOW_AMOUNT);
			}
			else if(TAXCODE2 == 'TAX04')
			{
				TAXPRICE2 	= parseFloat(0.04 * REQ_NOW_AMOUNT);
			}
			else if(TAXCODE2 == 'TAX20')
			{
				TAXPRICE2 	= parseFloat(0.2 * REQ_NOW_AMOUNT);
			}

			WO_TOTAL2	= parseFloat(REQ_NOW_AMOUNT + TAXPRICE1 - TAXPRICE2);
			WO_TOTAL2_POT	= parseFloat(WO_TOTAL2);

			document.getElementById('data'+row+'WO_TOTAL2').value 	= WO_TOTAL2_POT; // after tax
			document.getElementById('WO_TOTAL2A'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(WO_TOTAL2_POT)),decFormat));
			document.getElementById('data'+row+'TAXPRICE1').value 	= TAXPRICE1;
			document.getElementById('data'+row+'TAXPRICE2').value 	= TAXPRICE2;

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

			return false;
		}
		document.getElementById('data'+row+'WO_TOTAL').value 	= REQ_NOW_AMOUNT;
		document.getElementById('WO_TOTAL'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(REQ_NOW_AMOUNT)),decFormat));

		document.getElementById('data'+row+'WO_VOLM').value 	= REQ_NOW_QTY1;
		document.getElementById('WO_VOLM'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(REQ_NOW_QTY1)),decFormat));

		//GET DISCOUNT VALUE
		// WO_DISC				= parseFloat($("#data"+row+"WO_DISC").val()); // discount (%)
		// WO_DISCP			= parseFloat($("#data"+row+"WO_DISCP").val()); // discount Price

		// GET TAX VALUE
		TAXCODE1			= document.getElementById('TAXCODE1'+row).value;
		TAXCODE2			= document.getElementById('TAXCODE2'+row).value;
		TAXPRICE1			= 0;
		TAXPRICE2			= 0;
		WO_TOTAL2			= REQ_NOW_AMOUNT;
		if(REQ_NOW_AMOUNT == 0){
			WO_TOTAL2_POT	= 0;
		}else{
			WO_TOTAL2_POT	= REQ_NOW_AMOUNT;
		}
		//swal(REQ_NOW_AMOUNT);
		//swal("WO_TOTAL2_POT	= "+REQ_NOW_AMOUNT+ "-" +WO_DISCP);
		if(TAXCODE1 == 'TAX01')
		{
			TAXPRICE1 		= parseFloat(0.1 * REQ_NOW_AMOUNT);
		}

		if(TAXCODE2 == 'TAX02')
		{
			TAXPRICE2 	= parseFloat(0.02 * REQ_NOW_AMOUNT);
		}
		else if(TAXCODE2 == 'TAX03')
		{
			TAXPRICE2 	= parseFloat(0.03 * REQ_NOW_AMOUNT);
		}
		else if(TAXCODE2 == 'TAX04')
		{
			TAXPRICE2 	= parseFloat(0.04 * REQ_NOW_AMOUNT);
		}
		else if(TAXCODE2 == 'TAX20')
		{
			TAXPRICE2 	= parseFloat(0.2 * REQ_NOW_AMOUNT);
		}

		WO_TOTAL2		= parseFloat(REQ_NOW_AMOUNT + TAXPRICE1 - TAXPRICE2);
		WO_TOTAL2_POT	= parseFloat(WO_TOTAL2);

		document.getElementById('data'+row+'WO_TOTAL2').value 	= WO_TOTAL2_POT; // After Plus or Min Tax - discount
		document.getElementById('WO_TOTAL2A'+row).value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(WO_TOTAL2_POT)),decFormat));
		document.getElementById('data'+row+'TAXPRICE1').value 	= TAXPRICE1;
		document.getElementById('data'+row+'TAXPRICE2').value 	= TAXPRICE2;

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