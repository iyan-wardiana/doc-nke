<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 13 Maret 2017
 * File Name	= project_sicer_form.php
 * Location		= -
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$FlagUSER 	= $this->session->userdata['FlagUSER'];
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
$appBody    = $this->session->userdata('appBody');

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
if($task == 'add')
{
	$SIC_DateY	= date('Y');
	$SIC_DateM 	= date('m');
	$SIC_DateD 	= date('d');
	$SIC_DATE	= "$SIC_DateY-$SIC_DateM-$SIC_DateD";
	$SIC_DATE	= "$SIC_DateM/$SIC_DateD/$SIC_DateY";
	$SIC_DATEx	= mktime(0,0,0,$SIC_DateM,$SIC_DateD,$SIC_DateY);
	$SIC_TTOTerm	= 30;
	$SIC_ENDDATE = date("Y-m-d",strtotime("+$SIC_TTOTerm days",$SIC_DATEx));
	$SIC_ENDDATE = date("m/d/Y",strtotime("+$SIC_TTOTerm days",$SIC_DATEx));
}
else
{
	$SIC_DATEX 		=  $default['SIC_DATE'];
	$SIC_DATE 		= date("m/d/Y",strtotime($SIC_DATEX));
	$SIC_ENDDATEX 	=  $default['SIC_DATE'];
	$SIC_ENDDATE 	= date("m/d/Y",strtotime($SIC_ENDDATEX));
}

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

	$this->db->where('PATT_YEAR', $year);
	$myCount = $this->db->count_all('tbl_siheader');
	
	$sql = "SELECT MAX(PATT_NUMBER) as maxNumber FROM tbl_sicertificate WHERE PRJCODE = '$PRJCODE'";
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
	
	$sql = "SELECT proj_Number, PRJCODE, PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
	$resultProj = $this->db->query($sql)->result();
	foreach($resultProj as $row) :
		$proj_Number = $row->proj_Number;
		$PRJCODE = $row->PRJCODE;
		$PRJNAME = $row->PRJNAME;
	endforeach;
	
	$DocNumber 		= "$Pattern_Code$PRJCODE$groupPattern-$lastPatternNumb";
	$SIC_CODE 		= "$Pattern_Code$PRJCODE$groupPattern-$lastPatternNumb";
	$SIC_INCCON		= 0;
	$SIC_STEP		= 0;
	$PRJCODE		= $PRJCODE;
	$SIC_OWNER		= '';
	
	$SIC_DateY 		= date('Y');
	$SIC_DateM 		= date('m');
	$SIC_DateD 		= date('d');
	$SIC_Date 		= "$SIC_DateY-$SIC_DateM-$SIC_DateD";
	//$SIC_ENDDATE 	= $SIC_Date;
	$SIC_APPDATE	= "$SIC_DateY-$SIC_DateM-$SIC_DateD";
	$SIC_CREATED 	= "$SIC_DateY-$SIC_DateM-$SIC_DateD";
	$SIC_NOTES		= '';
	
		$SIC_DPPER1	= 0;
		$SIC_DPVAL1	= 0;
		$DPPPNVAL 	= 0;
		$sqlDP 		= "SELECT SUM(PINV_DPPER) AS DPPERCENT, SUM(PINV_DPVAL) AS DPVALUE, SUM(PINV_DPVALPPn) AS DPPPNVAL
						FROM tbl_projinv_header WHERE PRJCODE = '$PRJCODE' AND PINV_CAT = 1 AND PINV_STAT = 2";
		$resDP 		= $this->db->query($sqlDP)->result();
		foreach($resDP as $rowDP) :
			$SIC_DPPER 	= $rowDP ->DPPERCENT;
			$SIC_DPVALA 	= $rowDP ->DPVALUE;
			$DPPPNVALA 	= $rowDP ->DPPPNVAL;
			$SIC_DPPER1	= $SIC_DPPER1 + $SIC_DPPER;
			$SIC_DPVAL1	= $SIC_DPVAL1 + $SIC_DPVALA + $DPPPNVALA;
		endforeach;
	
	$SIC_DPPER		= $SIC_DPPER1;		
	$SIC_DPVAL		= $SIC_DPVAL1;
	$SIC_PROG		= 0;
	$SIC_PROGVAL	= 0;
	$SIC_APPPROG 	= 0; 
	$SIC_APPPROGVAL = 0;
	$SIC_TOTVAL		= 0;
	$SIC_VALUE		= 0;
	$SIC_APPVAL		= 0;
	$SIC_NOTES		= '';
	$SIC_EMPID		= $DefEmp_ID;
	$SIC_STAT		= 1;
	
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
	
	$SIC_MANNO		= "";
}
else
{
	$isSetDocNo = 1;
	$DocNumber		= $default['SIC_CODE'];
	$SIC_CODE 		= $default['SIC_CODE'];
	$SIC_MANNO 		= $default['SIC_MANNO'];
	$SIC_STEP 		= $default['SIC_STEP'];
	$MAXSTEP		= $SIC_STEP;
	$PRJCODE 		= $default['PRJCODE'];
	$SIC_OWNER 		= $default['SIC_OWNER'];
	$SIC_DATEx 		= $default['SIC_DATE'];
	$SIC_DATE 		= date("m/d/Y",strtotime($SIC_DATEx));
	$SIC_APPDATE 	= $default['SIC_APPDATE']; 
	$SIC_CREATED 	= $default['SIC_CREATED'];
	$SIC_PROG 		= $default['SIC_PROG']; 
	$SIC_PROGVAL 	= $default['SIC_PROGVAL'];
	$SIC_APPPROG 	= $default['SIC_APPPROG']; 
	$SIC_APPPROGVAL = $default['SIC_APPPROGVAL'];
	$SIC_TOTVAL 	= $default['SIC_TOTVAL'];
	$SIC_NOTES 		= $default['SIC_NOTES'];
	$SIC_EMPID 		= $default['SIC_EMPID'];
	$SIC_STAT 		= $default['SIC_STAT'];
	$PATT_YEAR 		= $default['PATT_YEAR'];
	$PATT_MONTH 	= $default['PATT_MONTH'];
	$PATT_DATE 		= $default['PATT_DATE'];
	$PATT_NUMBER	= $default['PATT_NUMBER'];
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
			if($TranslCode == 'AddItem')$AddItem = $LangTransl;
			if($TranslCode == 'Save')$Save = $LangTransl;
			if($TranslCode == 'Update')$Update = $LangTransl;
			if($TranslCode == 'Back')$Back = $LangTransl;
			if($TranslCode == 'SICode')$SICode = $LangTransl;
			if($TranslCode == 'SIManualNumber')$SIManualNumber = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'Asked')$Asked = $LangTransl;
			if($TranslCode == 'Date')$Date = $LangTransl;
			if($TranslCode == 'Value')$Value = $LangTransl;
			if($TranslCode == 'Approve')$Approve = $LangTransl;
			
			
			if($TranslCode == 'CertificateNo')$CertificateNo = $LangTransl;
			if($TranslCode == 'CertificateManualNo')$CertificateManualNo = $LangTransl;
			if($TranslCode == 'Changesto')$Changesto = $LangTransl;
			if($TranslCode == 'Project')$Project = $LangTransl;
			if($TranslCode == 'ProjectValue')$ProjectValue = $LangTransl;
			if($TranslCode == 'PPNValue')$PPNValue = $LangTransl;
			if($TranslCode == 'TotalProjectValue')$TotalProjectValue = $LangTransl;
			if($TranslCode == 'ChecktoCalculation')$ChecktoCalculation = $LangTransl;
			if($TranslCode == 'TotAmount')$TotAmount = $LangTransl;
			if($TranslCode == 'PercentProgress')$PercentProgress = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'ProgressApproved')$ProgressApproved = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'New')$New = $LangTransl;
			if($TranslCode == 'Confirm')$Confirm = $LangTransl;
		endforeach;
		if($LangID == 'IND')
		{
			$h1_title	= "Tambah";
			$h2_title	= "Sertifikat SI";
			$AddSI		= "Tambah SI";
			$sureDelete	= "Anda yakin akan menghapus data ini?";
		}
		else
		{
			$h1_title	= "Add";
			$h2_title	= "SI Certificate";
			$AddSI		= "Add SI";
			$sureDelete	= "Are your sure want to delete?";
		}
		
		// START : APPROVE PROCEDURE
			$IS_LAST	= 0;
			$APP_LEVEL	= 0;
			$APPROVER_1	= '';
			$APPROVER_2	= '';
			$APPROVER_3	= '';
			$APPROVER_4	= '';
			$APPROVER_5	= '';	
			$disableAll	= 1;
			$DOCAPP_TYPE= 1;
			$sqlCAPP	= "tbl_docstepapp WHERE MENU_CODE = '$MenuCode' AND PRJCODE = '$PRJCODE'";
			$resCAPP	= $this->db->count_all($sqlCAPP);
			if($resCAPP > 0)
			{
				$sqlAPP	= "SELECT * FROM tbl_docstepapp WHERE MENU_CODE = '$MenuCode'
							AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";
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
				$sqlAPPT	= "SELECT DOCAPP_TYPE FROM tbl_docstepapp WHERE MENU_CODE = '$MenuCode'
								AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";
				$resAPPT	= $this->db->query($sqlAPP)->result();
				foreach($resAPPT as $rowAPPT) :
					$DOCAPP_TYPE	= $rowAPPT->DOCAPP_TYPE;
				endforeach;
			}
			
			$sqlSTEPAPP	= "tbl_docstepapp_det WHERE MENU_CODE = '$MenuCode' AND APPROVER_1 = '$DefEmp_ID'
							AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";
			$resSTEPAPP	= $this->db->count_all($sqlSTEPAPP);
			
			if($resSTEPAPP > 0)
			{
				$canApprove	= 1;
				$APPLIMIT_1	= 0;
				
				$sqlAPP	= "SELECT APPLIMIT_1, APP_STEP, MAX_STEP FROM tbl_docstepapp_det WHERE MENU_CODE = '$MenuCode'
							AND APPROVER_1 = '$DefEmp_ID' AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";
				$resAPP	= $this->db->query($sqlAPP)->result();
				foreach($resAPP as $rowAPP) :
					$APPLIMIT_1	= $rowAPP->APPLIMIT_1;
					$APP_STEP	= $rowAPP->APP_STEP;
					$MAX_STEP	= $rowAPP->MAX_STEP;
				endforeach;
				$sqlC_App	= "tbl_approve_hist WHERE AH_CODE = '$SIC_CODE'";
				$resC_App 	= $this->db->count_all($sqlC_App);
				//$appReady	= $APP_STEP;
				//if($resC_App == 0)
				//echo "APP_STEP = $APP_STEP = $resC_App = $MAX_STEP";
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
				$APPROVE_AMOUNT 	= $SIC_TOTVAL;
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

        $comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">	
		<section class="content-header">
			<h1>
		    <?php echo $h1_title; ?>
		    <small><?php echo $h2_title; ?></small>  </h1>
		  <?php /*?><ol class="breadcrumb">
		    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		    <li><a href="#">Tables</a></li>
		    <li class="active">Data tables</li>
		  </ol><?php */?>
		</section>

		<section class="content">
		    <div class="box box-primary">
				<form name="frm" id="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return submitForm();">
		            <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
		            <input type="Hidden" name="rowCount" id="rowCount" value="0">
		            <input type="hidden" name="FlagUSER" id="FlagUSER" value="<?php echo $FlagUSER; ?>">
		            <input type="hidden" name="SIC_STATX" id="SIC_STATX" value="<?php echo $SIC_STAT; ?>">
		            <input type="hidden" name="ISAPPROVEX" id="ISAPPROVEX" value="<?php echo $ISAPPROVE; ?>">
					<?php if($isSetDocNo == 0) { ?>
		                <div class="col-sm-12">
		                    <div class="alert alert-danger alert-dismissible">
		                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		                        <h4><i class="icon fa fa-ban"></i> <?php echo $docalert1; ?>!</h4>
		                        <?php echo $docalert2; ?>
		                    </div>
		                </div>
		            <?php } ?>
					<table width="100%" border="0" style="size:auto">
		                <tr>
		                    <td width="16%" align="left" class="style1">&nbsp;</td>
		                  	<td width="1%" align="left" class="style1">&nbsp;</td>
		                  	<td width="36%" align="left" class="style1">&nbsp;</td>
		                    <td width="12%" align="left" class="style1">&nbsp;</td>
		                    <td width="35%" align="left" class="style1">&nbsp;</td>
		                    <!-- SIC_CODE, PATT_NUMBER, SIC_MANNO -->
		                </tr>
		                <tr>
		                    <td align="left" class="style1">&nbsp;&nbsp;<?php echo $CertificateNo ?> </td>
		                    <td align="left" class="style1">:</td>
		                    <td align="left" class="style1"><input type="text" name="SIC_CODE" id="SIC_CODE" value="<?php echo $DocNumber; ?>" class="form-control" style="max-width:200px" disabled>
		                    <input type="hidden" class="textbox" name="SIC_CODE" id="SIC_CODE" size="30" value="<?php echo $SIC_CODE; ?>" />
		                    <input type="hidden" class="textbox" name="PATT_NUMBER" id="PATT_NUMBER" size="30" value="<?php echo $PATT_NUMBER; ?>" /></td>
		                    <td align="left" class="style1"><?php echo $Date ?>  </td>
		                    <td align="left" class="style1">
		                          <div class="input-group date">
		                            <div class="input-group-addon">
		                            <i class="fa fa-calendar"></i>&nbsp;</div><input type="text" name="SIC_DATE" class="form-control pull-left" id="datepicker" value="<?php echo $SIC_DATE; ?>" style="width:150px"></div></td>
		                    <!-- SIC_CODE, PATT_NUMBER, SIC_MANNO -->
		                </tr>
		                <tr>
		                    <td align="left" class="style1">&nbsp;&nbsp;<?php echo $CertificateManualNo ?> </td>
		                    <td align="left" class="style1">:</td>
		                    <td align="left" class="style1"><input type="text" name="SIC_MANNO" id="SIC_MANNO" value="<?php echo $SIC_MANNO; ?>" class="form-control" style="max-width:200px" onBlur="getValue(this.value);"></td>
		                    <td align="left" class="style1">&nbsp;</td>
		                  <td align="left" class="style1">&nbsp;</td>
		                  <!-- SIC_CODE, PATT_NUMBER, SIC_MANNO -->
		                </tr>
		                <tr>
		                    <td align="left" class="style1">&nbsp;&nbsp;<?php echo $Changesto ?> </td>
		                    <td align="left" class="style1">:</td>
		                    <td align="left" class="style1">
		                    <select name="SIC_STEP1" id="SIC_STEP1" class="form-control" style="max-width:70px" disabled>
		                        <?php
		                            for($STEP=0;$STEP<=30;$STEP++)
		                            {
		                            ?>
		                                <option value="<?php echo $STEP; ?>" <?php if($STEP == $MAXSTEP) { ?> selected <?php } ?>><?php echo $STEP; ?></option>
		                                <?php
		                            }
		                        ?>
		                    </select>
		                    <input type="hidden" name="SIC_STEP" id="SIC_STEP" value="<?php echo $MAXSTEP; ?>">            </td>
		                    <td align="left" class="style1">&nbsp;</td>
		                    <td align="left" class="style1">&nbsp;</td>
		                    <!-- SIC_STEP, SIC_ENDDATE -->
		                </tr>
		                <tr>
		                    <td align="left" class="style1">&nbsp;&nbsp;<?php echo $Project ?> </td>
		                    <td align="left" class="style1">:</td>
		                    <td colspan="3" align="left" class="style1">
		                    <select name="PRJCODE1" id="PRJCODE1" class="form-control" style="max-width:300px" onChange="chooseProject()" disabled>
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
		                  <!-- PRJCODE1, PRJCODE -->
		                </tr>
		                <tr>
		                    <td align="left" class="style1">&nbsp;&nbsp;<?php echo $ProjectValue ?> </td>
		                    <td align="left" class="style1">:</td>
		                    <td colspan="3" align="left" class="style1" style="font-weight:bold">
		                        <input type="text" class="form-control" style="text-align:right; max-width:200px" name="proj_amountIDR1" id="proj_amountIDR1" value="<?php print number_format($proj_amountIDR, $decFormat); ?>" disabled>
		                        <input type="hidden" size="15" class="textbox" style="text-align:right;" name="proj_amountIDR" id="proj_amountIDR" value="<?php echo $proj_amountIDR; ?>">            </td> 
		                </tr>
		                <tr>
		                    <td align="left" class="style1">&nbsp;&nbsp;<?php echo $PPNValue ?>  (10%)</td>
		                    <td align="left" class="style1">:</td>
		                    <td colspan="3" align="left" class="style1" style="font-weight:bold">
		                      <?php
		                        $proj_amountPPnIDR 	= $proj_amountIDR * 0.1;
		                        $proj_amountnPPnIDR = $proj_amountIDR + $proj_amountPPnIDR;
		                    ?>
		                      <input type="text" class="form-control" style="text-align:right; max-width:200px" name="proj_amountPPnIDR1" id="proj_amountPPnIDR1" value="<?php print number_format($proj_amountPPnIDR, $decFormat); ?>" disabled>
		                      <input type="hidden" size="15" class="textbox" style="text-align:right;" name="proj_amountPPnIDR" id="proj_amountPPnIDR" value="<?php echo $proj_amountPPnIDR; ?>">              </td> 
		                </tr>
		                <tr>
		                    <td align="left" class="style1" nowrap>&nbsp;&nbsp;<?php echo $TotalProjectValue ?>  (+ PPn 10%)</td>
		                    <td align="left" class="style1">:</td>
		                    <td colspan="3" align="left" class="style1" style="font-weight:bold">
		                        <input type="text" class="form-control" style="text-align:right; max-width:200px" name="proj_amountTotIDR1" id="proj_amountTotIDR1" value="<?php print number_format($proj_amountnPPnIDR, $decFormat); ?>" disabled>
		                        <input type="hidden" size="15" class="textbox" style="text-align:right;" name="proj_amountTotIDR" id="proj_amountTotIDR" value="<?php echo $proj_amountnPPnIDR; ?>">            </td> 
		                </tr>
		                <tr>
		                    <td align="left" class="style1">&nbsp;&nbsp;<?php echo $ChecktoCalculation ?> </td>
		                    <td align="left" class="style1">:</td>
		                    <td align="left" class="style1"><input type="checkbox" name="checkCalCul" id="checkCalCul" value="1" onClick="getAkum();" <?php  if($task == 'edit') { ?> checked <?php } ?>></td> 
		                    <td align="left" class="style1">&nbsp;</td>
		                    <td align="left" class="style1">&nbsp;</td>
		                </tr>
		                <tr>
		                    <td align="left" class="style1">&nbsp;&nbsp;<?php echo $TotAmount ?> </td>
		                    <td align="left" class="style1">:</td>
		                    <td align="left" class="style1">
		                        <input type="text" class="form-control" style="text-align:right; max-width:200px" name="SIC_TOTVAL1" id="SIC_TOTVAL1" value="<?php print number_format($SIC_TOTVAL, $decFormat); ?>" disabled>
		                        <input type="hidden" size="15" class="textbox" style="text-align:right;" name="SIC_TOTVAL" id="SIC_TOTVAL" value="<?php echo $SIC_TOTVAL; ?>">            </td>
		                    <td colspan="2" align="left" class="style1"><?php echo $ProgressApproved; ?></td>
		                </tr>
		                <tr>
		                    <td align="left" class="style1">&nbsp;&nbsp;<?php echo $PercentProgress ?>  (%)</td>
		                    <td align="left" class="style1">:</td>
		                    <td align="left" class="style1">
		                        <input type="text" class="form-control" style="text-align:right; max-width:70px" name="SIC_PROG1" id="SIC_PROG1" value="<?php print number_format($SIC_PROG, 4); ?>" onBlur="getPROGValue(this.value)" onKeyPress="return isIntOnlyNew(event);" disabled>
		                        <input type="hidden" size="2" class="textbox" style="text-align:right;" name="SIC_PROG" id="SIC_PROG" value="<?php echo $SIC_PROG; ?>"></td>
		                     
		                    <td align="left" class="style1"><?php echo $Approve ?>  (%)</td>
		                    <td align="left" class="style1">
		                    	<input type="text" class="form-control" style="text-align:right; max-width:70px" name="SIC_APPPROG1" id="SIC_APPPROG1" value="<?php print number_format($SIC_APPPROG, 4); ?>" onBlur="getPROGAPPValue(this.value)" onKeyPress="return isIntOnlyNew(event);" <?php if($ISAPPROVE == 0) { ?> disabled <?php } ?>>
		                        <input type="hidden" class="form-control" style="text-align:right; max-width:70px" name="SIC_APPPROG" id="SIC_APPPROG" value="<?php print $SIC_APPPROG; ?>" ></td>
		                </tr>
		                <tr>
		                    <td align="left" class="style1">&nbsp;&nbsp;<?php echo $PercentProgress ?> (IDR)</td>
		                    <td align="left" class="style1">:</td>
		                    <td align="left" class="style1"><input type="text" class="form-control" style="text-align:right; max-width:200px" name="SIC_PROGVAL1" id="SIC_PROGVAL1" value="<?php print number_format($SIC_PROGVAL, $decFormat); ?>" disabled><input type="hidden" size="17" class="textbox" style="text-align:right;" name="SIC_PROGVAL" id="SIC_PROGVAL" value="<?php echo $SIC_PROGVAL; ?>"></td>
		                     
		                    <td align="left" class="style1"><?php echo $Approve ?></td>
		                    <td align="left" class="style1">
		                    <input type="text" class="form-control" style="text-align:right; max-width:200px" name="SIC_APPPROGVAL1" id="SIC_APPPROGVAL1" value="<?php print number_format($SIC_APPPROGVAL, $decFormat); ?>" disabled><input type="hidden" size="17" class="textbox" style="text-align:right;" name="SIC_APPPROGVAL" id="SIC_APPPROGVAL" value="<?php echo $SIC_APPPROGVAL; ?>"></td>
		                </tr>
		                <script>
		                    function getPROGValue(thisVal)
		                    {
		                        var decFormat		= document.getElementById('decFormat').value;
		                        myValue				= eval(document.getElementById('SIC_PROG1')).value.split(",").join("");
		                        myValue1			= parseInt(myValue);
		                        var FlagUSER		= document.getElementById('FlagUSER').value;
		                        
		                        if(FlagUSER == 'APPSI')
		                        {					
		                            if(myValue == 0)
		                            {
		                                swal('Please input Approved Percentation.');
		                                document.getElementById('SIC_PROG1').focus();
		                            }
		                        }
		                        var SIC_TOTVAL	= document.getElementById('SIC_TOTVAL').value;				
		                        SIC_PROGVAL	= parseFloat(Math.round(myValue1 / 100 * SIC_TOTVAL));
		                        document.getElementById('SIC_PROGVAL1').value 		= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(SIC_PROGVAL)),decFormat));
		                        document.getElementById('SIC_PROGVAL').value 		= SIC_PROGVAL;
		                        
		                        document.getElementById('SIC_PROG1').value 			= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(myValue1)),decFormat));
		                        document.getElementById('SIC_PROG').value 			= myValue1;
		                    }
		                    
		                    function getPROGAPPValue(thisVal)
		                    {
		                        var decFormat		= document.getElementById('decFormat').value;
		                        myValue				= eval(document.getElementById('SIC_APPPROG1')).value.split(",").join("");
		                        myValue1			= parseInt(myValue);
		                        var FlagUSER		= document.getElementById('FlagUSER').value;
		                        
		                        if(FlagUSER == 'APPSI')
		                        {					
		                            if(myValue == 0)
		                            {
		                                swal('Please input Approved Percentation.');
		                                document.getElementById('SIC_APPPROG1').focus();
		                            }
		                        }
		                        var SIC_TOTVAL	= document.getElementById('SIC_TOTVAL').value;				
		                        SIC_APPPROGVAL	= parseFloat(Math.round(myValue1 / 100 * SIC_TOTVAL));
		                        document.getElementById('SIC_APPPROGVAL1').value 		= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(SIC_APPPROGVAL)),decFormat));
		                        document.getElementById('SIC_APPPROGVAL').value 		= SIC_APPPROGVAL;
		                        
		                        document.getElementById('SIC_APPPROG1').value 			= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(myValue1)),decFormat));
		                        document.getElementById('SIC_APPPROG').value 			= myValue1;
		                    }
		                </script>
		                <tr>
		                    <td align="left" class="style1" valign="top">&nbsp;&nbsp;<?php echo $Description ?></td>
		                    <td align="left" class="style1" valign="top">:</td>
		                    <td align="left" class="style1">
		                        <textarea class="form-control" name="SIC_NOTES"id="SIC_NOTES" style="height:50px"><?php echo $SIC_NOTES; ?></textarea>            </td> 
		                    <td align="left" class="style1" valign="top" <?php if($ISAPPROVE == 0) { ?> style="display:none" <?php } ?>>&nbsp;</td>
		                    <td align="left" class="style1" <?php if($ISAPPROVE == 0) { ?> style="display:none" <?php } ?> valign="top">&nbsp;</td>
		                    <!-- SIC_NOTES -->
		                </tr>
		                <tr>
		                    <td align="left" valign="middle" class="style1">&nbsp;&nbsp;<?php echo $Status; ?></td>
		                    <td align="left" valign="middle" class="style1">:</td>
		                    <td align="left" valign="middle" class="style1">
		                    	<?php					
									if($ISAPPROVE == 1)
									{
										// START : FOR ALL APPROVAL FUNCTION								
											if($disableAll == 0)
											{
												if($canApprove == 1)
												{
													$disButton	= 0;
													$sqlCAPPHE	= "tbl_approve_hist WHERE AH_CODE = '$SIC_CODE' AND AH_APPROVER = '$DefEmp_ID'";
													$resCAPPHE	= $this->db->count_all($sqlCAPPHE);
													if($resCAPPHE > 0)
														$disButton	= 1;
													?>
		                                            <select name="SIC_STAT" id="SIC_STAT" class="form-control" style="max-width:100px" onChange="selStat(this.value)" <?php if($disButton == 1) { ?> disabled <?php } ?> >
		                                            <option value="0"> -- </option>
		                                            <option value="3"<?php if($SIC_STAT == 3) { ?> selected <?php } ?>>Approved</option>
		                                            <option value="4"<?php if($SIC_STAT == 4) { ?> selected <?php } ?>>Revised</option>
		                                            <option value="5"<?php if($SIC_STAT == 5) { ?> selected <?php } ?>>Rejected</option>
		                                            <option value="6"<?php if($SIC_STAT == 6) { ?> selected <?php } ?>>Closed</option>
		                                            <option value="7"<?php if($SIC_STAT == 7) { ?> selected <?php } ?> style="display:none">Awaiting</option>
														</select>
													<?php
												}
												else
												{
													?>
														<a href="" class="btn btn-<?php echo $statcoloer; ?> btn-xs" title="ssss">
															<?php echo $descApp; ?>
														</a>
													<?php
												}
											}
											else
											{
												?>
													<a href="" class="btn btn-danger btn-xs">
														Step approval not set.
													</a>
												<?php
											}
										// END : FOR ALL APPROVAL FUNCTION
									}
									else
									{
									?>
										<select name="SIC_STAT" id="SIC_STAT" class="form-control" style="max-width:110px" onChange="selStat(this.value)">
											<option value="1"<?php if($SIC_STAT == 1) { ?> selected <?php } ?>>New</option>
											<option value="2"<?php if($SIC_STAT == 2) { ?> selected <?php } ?>>Confirm</option>
											<option value="3"<?php if($SIC_STAT == 3) { ?> selected <?php } ?> disabled>Approved</option>
											<option value="4"<?php if($SIC_STAT == 4) { ?> selected <?php } ?> disabled>Revised</option>
											<option value="5"<?php if($SIC_STAT == 5) { ?> selected <?php } ?> disabled>Rejected</option>
											<option value="6"<?php if($SIC_STAT == 6) { ?> selected <?php } ?> disabled>Closed</option>
											<option value="7"<?php if($SIC_STAT == 7) { ?> selected <?php } ?> disabled>Awaiting</option>
										</select>
									<?php
									}
									$theProjCode 	= $PRJCODE;
		                        	$url_AddItem	= site_url('c_purchase/c_purchase_req/popupallitem/?id='.$this->url_encryption_helper->encode_url($theProjCode));
		                        ?>
		                    </td>
		                  	<td align="left" class="style1" valign="top">&nbsp;</td>
		                  <td align="left" class="style1" valign="top">&nbsp;</td>
		                </tr>
		                <?php
		                    $theProjCode 	= $PRJCODE;
		                    $url_AddSI		= site_url('c_project/c_si180c2ecer/pall180c2esi/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
		                
							if($SIC_STAT == 1)
							{
							?>
		                    <tr>
		                        <td align="left" class="style1">&nbsp;</td>
		                        <td align="left" class="style1">&nbsp;</td>
		                        <td align="left" class="style1">       
		                            <script>
		                                var url = "<?php echo $url_AddSI;?>";
		                                function selectitem()
		                                {
		                                    title = 'Select Site Instruction';
		                                    w = 900;
		                                    h = 550;
		                                    //window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
		                                    var left = (screen.width/2)-(w/2);
		                                    var top = (screen.height/2)-(h/2);
		                                    return window.open(url, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
		                                }
		                            </script>
		                            <button class="btn btn-success" type="button" onClick="selectitem();">
		                            <i class="glyphicon glyphicon-th-list"></i>&nbsp;&nbsp;<?php echo $AddSI; ?>
		                            </button>
		                        </td> 
		                        <td align="left" class="style1">&nbsp;</td>
		                        <td align="left" class="style1">&nbsp;</td>
		                    </tr>
							<?php
		                }
		                ?>
		                <tr>
		                    <td colspan="5" align="left" class="style1" style="font-style:italic">&nbsp;</td>
		                </tr>
		                <script>
		                    function check_all(chk) 
		                    {
		                        totalrow	= document.getElementById('totalrow').value;
		                        swal(totalrow)
		                    }
		                </script>
		                <tr>
		                    <td colspan="5" align="left" class="style1">
		                        <div class="row">
		                            <div class="col-md-12">
		                                <div class="box box-primary">
		                                <br>
		                                <table width="100%" border="1" id="tbl" >
		                                    <tr style="background:#CCCCCC">
		                                        <th width="3%" height="25" rowspan="2" style="text-align:center">&nbsp;
		                                            <input type="checkbox" name="ChkAllItem" id="ChkAllItem" onClick="check_all(this)" style="display:none" checked />
		                                        </th>
		                                        <th width="9%" rowspan="2" style="text-align:center"><?php echo $SICode ?> </th>
		                                        <th width="15%" rowspan="2" style="text-align:center"><?php echo $SIManualNumber ?> </th>
		                                        <th width="36%" rowspan="2" style="text-align:center"><?php echo $Description ?> </th>
		                                        <th colspan="2" style="text-align:center"><?php echo $Asked ?> </th>
		                                        <th colspan="2" style="text-align:center"><?php echo $Approve ?> </th>
		                                    </tr>
		                                    <tr style="background:#CCCCCC">
		                                        <th style="text-align:center;"><?php echo $Date ?> </th>
		                                        <th style="text-align:center;"><?php echo $Value ?> </th>
		                                        <th style="text-align:center;"><?php echo $Date ?> </th>
		                                        <th style="text-align:center;"><?php echo $Value ?> </th>
		                                    </tr>
		                                    <?php					
		                                    if($task == 'edit')
		                                    {
		                                        $sqlDET		= "SELECT A.SIC_CODE, A.SIC_MANNO, C.SI_CODE, C.SI_MANNO, C.SI_DATE, C.SI_APPDATE, C.SI_DESC, C.SI_DESC, C.SI_VALUE, C.SI_APPVAL,
		                                                            B.SIC_NOTES
		                                                        FROM tbl_sicertificatedet A
		                                                        INNER JOIN tbl_sicertificate B ON A.SIC_CODE = B.SIC_CODE
		                                                        INNER JOIN tbl_siheader C ON A.SI_CODE = C.SI_CODE
		                                                        WHERE A.SIC_CODE = '$SIC_CODE' 
		                                                            AND B.PRJCODE = '$PRJCODE'
		                                                        ORDER BY A.SI_CODE ASC";
		                                        // count data
		                                            $resultCount = $this->db->where('PRJCODE', $PRJCODE);
		                                            $resultCount = $this->db->count_all_results('tbl_sicertificate');
		                                        // End count data
		                                        $result 	= $this->db->query($sqlDET)->result();
		                                        $i			= 0;
		                                        $ISCHK	= 1;
		                                        if($resultCount > 0)
		                                        {
		                                            foreach($result as $row) :
		                                                $currentRow  	= ++$i;
		                                                $SIC_CODE 		= $row->SIC_CODE;
		                                                $SIC_MANNO 		= $row->SIC_MANNO;
		                                                $SIC_NOTES 		= $row->SIC_NOTES;
		                                                $SI_CODE 		= $row->SI_CODE;
		                                                $SI_MANNO 		= $row->SI_MANNO;
		                                                $SI_DATE 		= $row->SI_DATE;
		                                                $SI_DESC 		= $row->SI_DESC;
		                                                $SI_VALUE 		= $row->SI_VALUE;
		                                                $SI_APPVAL 		= $row->SI_APPVAL;
		                                                $SI_APPDATE		= $row->SI_APPDATE;
		                                                $itemConvertion	= 1;
		                                            ?>
		                                            <script>
		                                                function CheckThis(thisVal)
		                                                {
		                                                    ISCHKx = document.getElementById('ISCHKx'+thisVal).checked;
		                                                    if(ISCHKx == true)
		                                                        document.getElementById('data'+thisVal+'ISCHK').value	= 1;
		                                                    else
		                                                        document.getElementById('data'+thisVal+'ISCHK').value	= 0;
		                                                }
		                                            </script>
		                                            <tr>
		                                                <td width="3%" height="25" style="text-align:center"><?php
															if($SIC_STAT == 1)
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
		                                                    <input type="checkbox" name="ISCHKx<?php echo $currentRow; ?>" id="ISCHKx<?php echo $currentRow; ?>" onClick="CheckThis('<?php echo $currentRow; ?>');" style="display:none" checked>
		                                                    <input type="hidden" name="data[<?php echo $currentRow; ?>][ISCHK]" id="data<?php echo $currentRow; ?>ISCHK" value="<?php echo $ISCHK; ?>">
		                                                    <input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">
		                                                    <!-- currentRow -->                                    </td>
		                                                <td width="9%" style="text-align:left" nowrap>
		                                                    <?php echo $SI_CODE; ?>
		                                                    <input type="hidden" id="data[<?php echo $currentRow; ?>][SI_CODE]" name="data[<?php echo $currentRow; ?>][SI_CODE]" value="<?php echo $SI_CODE; ?>" width="10" size="15" readonly class="textbox">
		                                                    <!-- SI_CODE -->                                    </td>
		                                                <td width="15%" style="text-align:left" nowrap>
		                                                    <?php echo $SI_MANNO; ?>
		                                                    <input type="hidden" id="data<?php echo $currentRow; ?>SI_MANNO" name="data[<?php echo $currentRow; ?>][SI_MANNO]" value="<?php echo $SI_MANNO; ?>" width="10" size="15" readonly class="textbox">
		                                                    <!-- SI_MANNO -->                                    </td>
		                                                <td width="36%" style="text-align:left">
		                                                    <?php echo $SI_DESC; ?>
		                                                    <!-- SI_DESC -->                                    </td>
		                                                <td width="8%" style="text-align:center" nowrap>
		                                                    <?php echo $SI_DATE; ?>
		                                                    <!-- SI_DATE -->                                    </td>
		                                                <td width="9%" style="text-align:right" nowrap>
		                                                    <?php print number_format($SI_VALUE, 2); ?>
		                                                    <input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][SI_VALUE]" id="data<?php echo $currentRow; ?>SI_VALUE" size="10" value="<?php echo $SI_VALUE; ?>" class="textbox" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,'<?php echo $currentRow; ?>);" >
		                                                    <!-- SI_VALUE -->                                    </td>
		                                                <td width="10%" style="text-align:center" nowrap>
		                                                    <?php
		                                                        $SI_APPDATE1				= date('Y-m-d',strtotime($SI_APPDATE));
		                                                        echo $SI_APPDATE1; 
		                                                    ?>
		                                                    <!-- SI_APPDATE -->                                    </td>
		                                                <td width="10%" style="text-align:right" nowrap>
		                                                    <?php print number_format($SI_APPVAL, 2); ?>
		                                                    <input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][SI_APPVAL]" id="data<?php echo $currentRow; ?>SI_APPVAL" size="10" value="<?php echo $SI_APPVAL; ?>" class="textbox" onKeyPress="return isIntOnlyNew(event);" >
		                                                    <!-- SI_APPVAL -->                                    </td>
		                                                <input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">
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
		                                </div>
		                            </div>
		                        </div>
		                	</td>
		                </tr>
		                <tr>
		                    <td align="left" class="style1">&nbsp;</td>
		                    <td align="left" class="style1">&nbsp;</td>
		                    <td colspan="3" align="left" class="style1">
		                        <?php
									if($disableAll == 0)
									{
										if(($SIC_STAT == 2 || $SIC_STAT == 7) && $canApprove == 1)
										{
											?>
												<button class="btn btn-primary">
												<i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Update; ?>
												</button>&nbsp;
											<?php
										}
										elseif($SIC_STAT == 1 || $SIC_STAT == 4)
										{
												?>
													<button class="btn btn-primary" >
													<i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Save; ?>
													</button>&nbsp;
												<?php
										}
									}
		                            echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i>&nbsp;&nbsp;'.$Back.'</button>');
		                        ?>
		                    </td>
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
	function add_item(strItem) 
	{
		var decFormat	= document.getElementById('decFormat').value;
		arrItem = strItem.split('|');
		
		var objTable, objTR, objTD, intIndex, arrItem, SI_APPVALX = 0;
		var SIC_CODE 	= "<?php echo $SIC_CODE; ?>";
		var SIC_MANNO 	= "<?php echo $SIC_MANNO; ?>";
		
		ilvl = arrItem[1];
		
		SI_CODE 		= arrItem[0];
		SI_MANNO 		= arrItem[1];
		SI_DATE 		= arrItem[2];
		SI_APPDATE		= arrItem[3];
		SI_DESC 		= arrItem[4];
		SI_VALUE 		= arrItem[5];
		SI_APPVAL 		= arrItem[6];
		
		validateDouble(SI_CODE)
		if(validateDouble(SI_CODE))
		{
			swal("Double Item for " + SI_CODE);
			return;
		}
		
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
		objTD.style.textAlign = 'center';
		objTD.innerHTML = '<a href="#" onClick="deleteRow('+intIndex+')" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a><input type="checkbox" name="ISCHKx'+intIndex+'" id="ISCHKx'+intIndex+'" onClick="CheckThis('+intIndex+');" style="display:none" checked><input type="hidden" name="data['+intIndex+'][ISCHK]" id="data'+intIndex+'ISCHK" value="1"><input type="hidden" id="chk" name="chk" value="'+intIndex+'" width="10" size="15" readonly class="textbox">';
		
		// SI_CODE, 
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'left';
		objTD.noWrap = true;
		objTD.innerHTML = ''+SI_CODE+'<input type="hidden" id="data'+intIndex+'SI_CODE" name="data['+intIndex+'][SI_CODE]" value="'+SI_CODE+'" width="10" size="15" readonly class="textbox">';
		
		// SI_MANNO
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'left';
		objTD.innerHTML = ''+SI_MANNO+'<input type="hidden" id="data'+intIndex+'SI_MANNO" name="data['+intIndex+'][SI_MANNO]" value="'+SI_MANNO+'" width="10" size="15" readonly class="textbox">';
		
		// SI_DESC
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'left';
		objTD.innerHTML = ''+SI_DESC+'';
		
		// SI_DATE
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = ''+SI_DATE+'';
		
		// SI_VALUE
		var SI_VALUE1		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(SI_VALUE)),2));
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = ''+SI_VALUE1+'<input type="hidden" style="text-align:right" name="data['+intIndex+'][SI_VALUE]" id="data'+intIndex+'SI_VALUE" size="10" value="'+SI_VALUE+'" class="textbox" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,'+intIndex+');" >';
		
		// SI_APPDATE
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = ''+SI_APPDATE+'';
		
		// SI_APPVAL
		var SI_APPVAL1		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(SI_APPVAL)), 2))
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = ''+SI_APPVAL1+'<input type="hidden" style="text-align:right" name="data['+intIndex+'][SI_APPVAL]" id="data'+intIndex+'SI_APPVAL" size="10" value="'+SI_APPVAL+'" class="textbox" onKeyPress="return isIntOnlyNew(event);" >';
		
		//document.getElementById('SI_VALUE'+intIndex).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(SI_VALUE)), 2));
		//document.getElementById('SI_APPVAL'+intIndex).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(SI_APPVAL)), 2));
		document.getElementById('totalrow').value = intIndex;
		document.getElementById('ChkAllItem').checked = true;
	}
	
	function deleteRow(btn)
	{
		var row = document.getElementById("tr_" + btn);
		row.remove();
	}
	
	function getAkum()
	{
		var decFormat	= document.getElementById('decFormat').value;
		checkCalCul		= document.getElementById('checkCalCul').checked;
		totRow			= document.getElementById('totalrow').value;
		var SI_APPVAL1	= 0;
		
		if(checkCalCul == true)
		{
			if(totRow == 0)
			{
				swal('Please add at least one Site Instruction document. Please select one.');
				document.getElementById('checkCalCul').checked = false;
				selectitem();
			}
			for(var i=1;i<=totRow;i++) 
			{
				var SI_APPVAL	= parseFloat(document.getElementById('data'+i+'SI_APPVAL').value);
				var SI_APPVAL1	= parseFloat(SI_APPVAL1) + parseFloat(SI_APPVAL);
			}
		}
		else
		{
			var SI_APPVAL	= 0;
			var SI_APPVAL1	= 0;
		}
		var SIC_PROG		= 100;
		document.getElementById('SIC_TOTVAL1').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(SI_APPVAL1)), 2));
		document.getElementById('SIC_TOTVAL').value 	= SI_APPVAL1;
		document.getElementById('SIC_PROG1').value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(SIC_PROG)), 2));
		document.getElementById('SIC_PROG').value 		= SIC_PROG;
		document.getElementById('SIC_PROGVAL1').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(SI_APPVAL1)), 2));
		document.getElementById('SIC_PROGVAL').value 	= SI_APPVAL1;
	}
	
	function validateDouble(SI_CODE) 
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
				var SI_CODE1	= document.getElementById('data'+i+'SI_CODE').value;
				if(SI_CODE1 == SI_CODE)
				{
					duplicate = true;
					break;
				}
			}
		}
		return duplicate;
	}
	
	function submitForm(value)
	{
		var SIC_MANNO	= document.getElementById('SIC_MANNO').value;
		//swal('SIC_MANNO = '+SIC_MANNO)
		var SIC_STEP	= parseFloat(document.getElementById('SIC_STEP').value);
		//swal('SIC_STEP = '+SIC_STEP)
		var SIC_TOTVAL	= document.getElementById('SIC_TOTVAL').value;
		//swal('SIC_TOTVAL = '+SIC_TOTVAL)
		var FlagUSER	= document.getElementById('FlagUSER').value;
		//swal('FlagUSER = '+FlagUSER)
		var SIC_STAT	= document.getElementById('SIC_STAT').value;
		//swal('SIC_STATX = '+SIC_STATX)
		var ISAPPROVEX	= document.getElementById('ISAPPROVEX').value;
		
		if(ISAPPROVEX == 1)
		{
			var SIC_APPPROG	= document.getElementById('SIC_APPPROGVAL').value;
			if(SIC_APPPROG == 0)
			{
				swal('Please input SI Approved Progress.');
				document.getElementById('SIC_APPPROG1').focus();
				return false;
			}
		}
		
		if(SIC_MANNO == '')
		{
			swal('Please input SI Manual Number.');
			document.getElementById('SIC_MANNO').focus();
			return false;
		}
		
		if(SIC_STEP == 0)
		{
			swal('Please select step of Site Instruction.');
			document.getElementById('SIC_STEP').focus();
			return false;
		}
		
		if(SIC_TOTVAL == 0)
		{
			swal('Please klik the Check to Calculation checkbox.');
			document.getElementById('checkCalCul').checked = true;
			getAkum();
			return false;
		}
		
		if(SIC_STAT == 0)
		{
			swal('Silahkan pili status dokumen.')
			document.getElementById('SIC_STAT').focus();
			return false;
		}
		/*else
		{
			document.frm.submit();
		}*/
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