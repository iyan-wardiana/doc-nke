<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 27 Februari 2017
 * File Name	= project_si_form.php
 * Location		= -
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$FlagUSER 	= $this->session->userdata['FlagUSER'];
$appBody    = $this->session->userdata('appBody');

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
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

//if($FlagUSER == 'APPSI' || $FlagUSER == 'APPUSR')
if($ISAPPROVE == 1)
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
	$SI_DATEY	= date('Y');
	$SI_DATEM 	= date('m');
	$SI_DATED 	= date('d');
	$SI_DATEx	= mktime(0,0,0,$SI_DATEM,$SI_DATED,$SI_DATEY);
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
	
	$SI_DATEY 		= date('Y');
	$SI_DATEM 		= date('m');
	$SI_DATED 		= date('d');
	$SI_DATE		= "$SI_DATEM/$SI_DATED/$SI_DATEY";
	$SI_ENDDATE		= $SI_DATE;
	//$SI_ENDDATE 	= $SI_DATE;
	//$SI_APPDATE	= "$SI_DATEY-$SI_DATEM-$SI_DATED";
	//$SI_CREATED 	= "$SI_DATEY-$SI_DATEM-$SI_DATED";
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
	$SI_PROPPERC	= 100;
	$SI_PROPVAL		= 0;
	$SI_REALVAL		= 0;
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
		$SI_DATEY 		= date('Y');
		$SI_DATEM 		= date('m');
		$SI_DATED 		= date('d');
		$SI_DATE 		= "$SI_DATEM/$SI_DATED/$SI_DATEY";
	}
	else
	{
		$SI_DATE 		= date('m/d/Y', strtotime($SI_DATE));
	}
	
	$SI_ENDDATE 	= $default['SI_ENDDATE']; 
	//$SI_APPDATE	= $default['SI_APPDATE'];
	//$SI_CREATED 	= $default['SI_CREATED'];
	$SI_DESC 		= $default['SI_DESC'];
	$SI_DPPER 		= $default['SI_DPPER'];
	$SI_DPVAL 		= $default['SI_DPVAL'];
	$SI_VALUE 		= $default['SI_VALUE'];
	$SI_APPVAL 		= $default['SI_APPVAL'];
	$SI_PROPPERC	= $default['SI_PROPPERC'];
	$SI_PROPVAL		= $default['SI_PROPVAL'];
	$SI_REALVAL		= $default['SI_REALVAL'];
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
		$ISDWONL 	= $this->session->userdata['ISDWONL'];$LangID 	= $this->session->userdata['LangID'];

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
			if($TranslCode == 'IncludetoContract')$IncludetoContract = $LangTransl;
			if($TranslCode == 'SINumber')$SINumber = $LangTransl;
			if($TranslCode == 'ManualNumber')$ManualNumber = $LangTransl;
			if($TranslCode == 'SIStep')$SIStep = $LangTransl;
			if($TranslCode == 'Project')$Project = $LangTransl;
			if($TranslCode == 'ProjectValue')$ProjectValue = $LangTransl;
			if($TranslCode == 'PPNValue')$PPNValue = $LangTransl;
			if($TranslCode == 'AmandementthisSI')$AmandementthisSI = $LangTransl;
			if($TranslCode == 'AmandementNumber')$AmandementNumber = $LangTransl;
			if($TranslCode == 'JobDescription')$JobDescription = $LangTransl;
			if($TranslCode == 'Notes')$Notes = $LangTransl;
			if($TranslCode == 'DateofFiling')$DateofFiling = $LangTransl;
			if($TranslCode == 'SIDate')$SIDate = $LangTransl;
			if($TranslCode == 'SIEndDate')$SIEndDate = $LangTransl;
			if($TranslCode == 'ChargeFiled')$ChargeFiled = $LangTransl;
			if($TranslCode == 'ChargeApproved')$ChargeApproved = $LangTransl;
			if($TranslCode == 'Approved')$Approved = $LangTransl;
			if($TranslCode == 'RealCost')$RealCost = $LangTransl;
			if($TranslCode == 'AmandementValue')$AmandementValue = $LangTransl;
			if($TranslCode == 'AmandementStat')$AmandementStat = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'Yes')$Yes = $LangTransl;
			if($TranslCode == 'No')$No = $LangTransl;
			if($TranslCode == 'AddToInvoice')$AddToInvoice = $LangTransl;
			if($TranslCode == 'New')$New = $LangTransl;
			if($TranslCode == 'Approve')$Approve = $LangTransl;
			if($TranslCode == 'Close')$Close = $LangTransl;
			if($TranslCode == 'AddSI')$AddSI = $LangTransl;
		endforeach;
		
		//if($FlagUSER == 'APPSI' || $FlagUSER == 'APPUSR')
		if($ISAPPROVE == 1)
		{
			$isOpen	= 1;
		}
		else
		{
			$isOpen	= 0;
		}
		
		$sql = "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
		$resultProj = $this->db->query($sql)->result();
		foreach($resultProj as $row) :
			$PRJNAMEH = $row->PRJNAME;
		endforeach;
		
		// START : APPROVE PROCEDURE
			$IS_LAST	= 0;
			$APP_LEVEL	= 0;
			$APPROVER_1	= '';
			$APPROVER_2	= '';
			$APPROVER_3	= '';
			$APPROVER_4	= '';
			$APPROVER_5	= '';	
			$disableAll	= 0;
			$DOCAPP_TYPE= 1;
			$sqlCAPP	= "tbl_docstepapp WHERE MENU_CODE = '$MenuCode' AND PRJCODE = '$PRJCODE'";
			$resCAPP	= $this->db->count_all($sqlCAPP);
			if($resCAPP > 0)
			{
				$sqlAPP	= "SELECT * FROM tbl_docstepapp WHERE MENU_CODE = '$MenuCode'
							AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";
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
				$sqlC_App	= "tbl_approve_hist WHERE AH_CODE = '$SI_CODE'";
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
				$APPROVE_AMOUNT = $SI_APPVAL;
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
		    <?php echo $AddSI; ?>
		    <small><?php echo $PRJNAMEH; ?></small>  </h1>
		  <?php /*?><ol class="breadcrumb">
		    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		    <li><a href="#">Tables</a></li>
		    <li class="active">Data tables</li>
		  </ol><?php */?>
		</section>

		<section class="content">
		    <div class="row">
               	<form class="form-horizontal" name="frm" method="post" action="<?php echo $form_action; ?>" enctype="multipart/form-data">
					<div class="col-md-6">
						<div class="box box-primary">
							<div class="box-header with-border" style="display: none;">
								<i class="fa fa-cloud-upload"></i>
								<h3 class="box-title">&nbsp;</h3>
							</div>
							<div class="box-body">
					            <input type="hidden" name="IS_LAST" id="IS_LAST" value="<?php echo $IS_LAST; ?>">
					            <input type="hidden" name="APP_LEVEL" id="APP_LEVEL" value="<?php echo $APP_LEVEL; ?>">
					            <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
					            <input type="Hidden" name="rowCount" id="rowCount" value="0">
					            <input type="hidden" name="FlagUSER" id="FlagUSER" value="<?php echo $FlagUSER; ?>">
					            <input type="hidden" name="ISAPPROVE" id="ISAPPROVE" value="<?php echo $ISAPPROVE; ?>">
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $IncludetoContract; ?></label>
				                    <div class="col-sm-9">
				                    	<input type="radio" class="flat-red" name="SI_INCCON" id="isInclude1" value="1" <?php if($SI_INCCON == 1) { ?> checked <?php } ?>> 
				                        <?php echo $Yes ?> &nbsp;&nbsp;
				                        <input type="radio" class="flat-red" name="SI_INCCON" id="isInclude2" value="0" <?php if($SI_INCCON == 0) { ?> checked <?php } ?>> 
				                        <?php echo $No ?> &nbsp;&nbsp;
				                        <input type="radio" name="SI_INCCON" id="isInclude2" value="0" <?php if($SI_INCCON == 2) { ?> checked <?php } ?> style="display:none"> <?php //echo $AddToInvoice ?>
				                    </div>
				                </div>
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $SINumber; ?></label>
				                    <div class="col-sm-9">
				                    	<input type="text" class="form-control" name="SI_CODE1" id="SI_CODE1" value="<?php print $DocNumber; ?>" disabled>
				                    	<input type="hidden" class="textbox" name="SI_CODE" id="SI_CODE" size="30" value="<?php echo $SI_CODE; ?>" />
				               	  		<input type="hidden" class="textbox" name="PATT_NUMBER" id="PATT_NUMBER" size="30" value="<?php echo $PATT_NUMBER; ?>" />
				                    </div>
				                </div>
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $ManualNumber; ?></label>
				                    <div class="col-sm-9">
				                    	<input type="text" name="SI_MANNO" id="SI_MANNO" value="<?php echo $SI_MANNO; ?>" class="form-control" placeholder="<?php echo $ManualNumber; ?>">
				                    </div>
				                </div>
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $SIDate; ?></label>
				                    <div class="col-sm-9">
				                    	<div class="input-group date">
				                            <div class="input-group-addon">
				                            <i class="fa fa-calendar"></i>&nbsp;</div><input type="text" name="SI_DATE" class="form-control pull-left" id="datepicker" value="<?php echo $SI_DATE; ?>" style="width:100px">
				                        </div>
				                    </div>
				                </div>
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $SIStep; ?></label>
				                    <div class="col-sm-9">
				                    	<input type="text" name="SI_STEP" id="SI_STEP" value="<?php echo $MAXSTEP; ?>" class="form-control" readonly>
				                    </div>
				                </div>
				            	<div class="form-group" style="display: none;">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Project; ?></label>
				                    <div class="col-sm-9">
				                    	<select name="PRJCODE1" id="PRJCODE1" onChange="chooseProject()" class="form-control" disabled>
					                        <option value="none"> --- </option>
					                        <?php 
					                        if($countPRJ > 0)
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
					                    <input type="hidden" name="PRJCODE" id="PRJCODE" value="<?php echo $PRJCODE; ?>" >
				                    </div>
				                </div>
				                <script>
				                    function getPROPPERValue(thisVal)
				                    {
				                        var decFormat		= document.getElementById('decFormat').value;
										document.getElementById('SI_PROPPERC1').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(thisVal)),decFormat));
										if(thisVal < 0)
										{
				                        	document.getElementById('SI_PROPPERC1').value 	= RoundNDecimal(parseFloat(thisVal),decFormat);
										}
										
				                        document.getElementById('SI_PROPPERC').value 	= thisVal;
										
										SI_VALUE			= document.getElementById('SI_VALUE').value;
				                        SI_APPVAL			= thisVal * SI_VALUE / 100;
										document.getElementById('SI_APPVAL').value 		= SI_APPVAL;
										document.getElementById('SI_APPVAL1').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(SI_APPVAL)),decFormat));
										if(SI_APPVAL < 0)
										{
				                        	document.getElementById('SI_APPVAL1').value = RoundNDecimal(parseFloat(SI_APPVAL),decFormat);
										}
				                    }
									
				                    function getSIAPPVal(thisVal)
				                    {
				                        var decFormat		= document.getElementById('decFormat').value;
				                        SI_APPVAL1			= eval(thisVal).value.split(",").join("");
										
										document.getElementById('SI_APPVAL1').value= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.ceil(SI_APPVAL1)),2));
										if(SI_APPVAL1 < 0)
										{
											document.getElementById('SI_APPVAL1').value = RoundNDecimal(parseFloat(SI_APPVAL1),2);
										}
										
										document.getElementById('SI_APPVAL').value 	= SI_APPVAL1;
										 
				                        var SI_VALUE		= document.getElementById('SI_VALUE').value;
				                        var percenT			= SI_APPVAL1 / SI_VALUE * 100;
										
				                        document.getElementById('SI_PROPPERC1').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(percenT)), 4));
				                        document.getElementById('SI_PROPPERC').value 	= percenT;
				                    }
									
				                    function getDPValue(thisVal)
				                    {
				                        var decFormat		= document.getElementById('decFormat').value;
				                        document.getElementById('SI_DPPER1').value 		= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(thisVal)),decFormat));
										if(thisVal < 0)
										{
											document.getElementById('SI_DPPER1').value = RoundNDecimal(parseFloat(thisVal), decFormat);
										}
				                        document.getElementById('SI_DPPER').value 		= thisVal;
				                        proj_amountIDR		= document.getElementById('proj_amountTotIDR').value;
				                        SI_DPVALx			= thisVal * proj_amountIDR / 100;
				                        document.getElementById('SI_DPVAL').value 		= SI_DPVALx;
				                        document.getElementById('SI_DPVAL1').value 		= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(SI_DPVALx)),decFormat));
										if(SI_DPVALx < 0)
										{
											document.getElementById('SI_DPVAL1').value = RoundNDecimal(parseFloat(SI_DPVALx), decFormat);
										}
				                    }

									function getREAL(thisVal)
									{
				                        var decFormat		= document.getElementById('decFormat').value;
				                        SI_REALVAL			= eval(thisVal).value.split(",").join("");
										
				                        document.getElementById('SI_REALVAL').value 	= SI_REALVAL;
				                        document.getElementById('SI_REALVAL1').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(SI_REALVAL)),decFormat));
									}
									
				                    function showAmandemen(thisVal)
				                    {
				                        if(thisVal == 2)
				                        {
				                            document.getElementById('isamandement').style.display 		= 'none';
				                            document.getElementById('isamandementstat').style.display 	= 'none';
				                            document.getElementById('sistatus1').style.display 			= '';
				                            //document.getElementById('sistatus2').style.display 		= '';
				                        }
				                        else
				                        {
				                            document.getElementById('isamandement').style.display		= '';
				                            document.getElementById('isamandementstat').style.display 	= '';
				                            document.getElementById('sistatus1').style.display 			= 'none';
				                            //document.getElementById('sistatus2').style.display 		= 'none';
				                        }
				                        document.getElementById('SI_IS_AMAND').value			= thisVal;
				                    }

				                    function getAMANDValue(thisVal)
				                    {
				                        var decFormat		= document.getElementById('decFormat').value;
										document.getElementById('SI_AMANDVAL1').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(thisVal)),decFormat));
										if(thisVal < 0)
										{
											document.getElementById('SI_AMANDVAL1').value 	= RoundNDecimal(parseFloat(thisVal), decFormat);
										}
				                        
				                        document.getElementById('SI_AMANDVAL').value 	= thisVal;
				                    }
				                    
				                    function changeStatAmand(thisVal)
				                    {
				                        document.getElementById('SI_AMANDSTATVAL').value 	= thisVal;
				                    }
									
				                    function getSIValue(thisVal)
				                    {
				                        var decFormat		= document.getElementById('decFormat').value;
				                        SI_VALUE1			= eval(thisVal).value.split(",").join("");
										document.getElementById('SI_VALUE1').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(SI_VALUE1)),decFormat));
										if(SI_VALUE1 < 0)
										{
											document.getElementById('SI_VALUE1').value 		= RoundNDecimal(parseFloat(SI_VALUE1),decFormat);
										}
										document.getElementById('SI_VALUE').value 	= SI_VALUE1;
										
										var perValue	= 100;
										getPROPPERValue(perValue);
				                    }
				                </script>
				            	<div class="form-group" style="display: none;">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $AmandementthisSI; ?></label>
				                    <div class="col-sm-9">
				                    	<input type="radio" name="SI_AMAND" id="SI_AMAND1" value="1" <?php if($SI_AMAND == 1) { ?> checked <?php } ?> onClick="showAmandemen(1);"> 
				                        <?php echo $Yes ?> &nbsp;&nbsp;
				                        <input type="radio" name="SI_AMAND" id="SI_AMAND2" value="2" <?php if($SI_AMAND == 2) { ?> checked <?php } ?> onClick="showAmandemen(2);"> 
				                        <?php echo $No ?> 
				                        <input type="hidden" name="SI_IS_AMAND" id="SI_IS_AMAND" value="<?php echo $SI_AMAND; ?>">
				                    </div>
				                </div>
				            	<div class="form-group" style="display: none;">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $AmandementNumber; ?></label>
				                    <div class="col-sm-9">
				                    	<input type="text" class="form-control" style="max-width:150px" name="SI_AMANDNO" id="SI_AMANDNO" value="<?php echo $SI_AMANDNO; ?>">
				                    </div>
				                </div>
				            	<div class="form-group" style="display: none;">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $AmandementValue; ?></label>
				                    <div class="col-sm-9">
				                    	<input type="text" class="form-control" style="text-align:right; max-width:150px" name="SI_AMANDVAL1" id="SI_AMANDVAL1" value="<?php print number_format($SI_AMANDVAL, 2); ?>" onKeyPress="return isIntOnlyNew(event);" onBlur="getAMANDValue(this.value)">
		                        		<input type="hidden" size="15" class="textbox" style="text-align:right;" name="SI_AMANDVAL" id="SI_AMANDVAL" value="<?php echo $SI_AMANDVAL; ?>">
				                    </div>
				                </div>
				            	<div class="form-group" style="display: none;">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $AmandementStat; ?></label>
				                    <div class="col-sm-9">
				                    	<input type="radio" name="SI_AMANDSTAT" id="SI_AMANDSTAT1" value="1" <?php if($SI_AMANDSTAT == 1) { ?> checked <?php } ?> onClick="changeStatAmand(1)">
				                        <?php echo $New ?> &nbsp;&nbsp;
				                        <input type="radio" name="SI_AMANDSTAT" id="SI_AMANDSTAT2" value="3" <?php if($SI_AMANDSTAT == 3) { ?> checked <?php } ?> onClick="changeStatAmand(3)">
				                        <?php echo $Approve ?> 
				                        <input type="hidden" name="SI_AMANDSTATVAL" id="SI_AMANDSTATVAL" value="<?php echo $SI_AMANDSTAT; ?>" >
				                    </div>
				                </div>
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $JobDescription; ?></label>
				                    <div class="col-sm-9">
				                    	<textarea class="form-control" name="SI_DESC"id="SI_DESC" style="min-height:70px; max-width:500px" placeholder="<?php echo $JobDescription; ?>"><?php echo $SI_DESC; ?></textarea>
				                    </div>
				                </div>
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Notes; ?></label>
				                    <div class="col-sm-9">
				                    	<textarea name="SI_NOTES" class="form-control" id="SI_NOTES" style="min-height:70px; max-width:500px" placeholder="<?php echo $Notes; ?>"><?php echo $SI_NOTES; ?></textarea>
				                    </div>
				                </div>
				            </div>
				        </div>
				    </div>
					<div class="col-md-6">
						<div class="box box-warning">
							<div class="box-header with-border">
								<i class="fa fa-dollar"></i>
								<h3 class="box-title"><?php echo $ProjectValue; ?></h3>
							</div>
							<div class="box-body">
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $ProjectValue; ?></label>
				                    <div class="col-sm-4">
				                    	<input type="text" class="form-control" style="max-width:200px; text-align:right" name="proj_amountIDR1" id="proj_amountIDR1" value="<?php print number_format($proj_amountIDR, $decFormat); ?>" disabled>
		                        		<input type="hidden" size="15" class="textbox" style="text-align:right;" name="proj_amountIDR" id="proj_amountIDR" value="<?php echo $proj_amountIDR; ?>">
				                    </div>
				                    <div class="col-sm-1">
				                    	<label for="inputName" class="col-sm-3 control-label">PPn</label>
				                    </div>
				                    <div class="col-sm-4">
				                    	<?php
					                        $proj_amountPPnIDR 	= $proj_amountIDR * 0.1;
					                        $proj_amountnPPnIDR = $proj_amountIDR + $proj_amountPPnIDR;
					                    ?>
					                    <input type="text" class="form-control" style="max-width:200px; text-align:right" name="proj_amountPPnIDR1" id="proj_amountPPnIDR1" value="<?php print number_format($proj_amountPPnIDR, $decFormat); ?>" disabled>
					                    <input type="hidden" size="15" class="textbox" style="text-align:right;" name="proj_amountPPnIDR" id="proj_amountPPnIDR" value="<?php echo $proj_amountPPnIDR; ?>">
				                    </div>
				                </div>
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label">Tot. <?php echo $ProjectValue ?></label>
				                    <div class="col-sm-4">
				                    	<input type="text" class="form-control" style="max-width:200px; text-align:right" name="proj_amountTotIDR1" id="proj_amountTotIDR1" value="<?php print number_format($proj_amountnPPnIDR, $decFormat); ?>" disabled>
		                        		<input type="hidden" class="form-control" style="text-align:right;max-width:200px;" name="proj_amountTotIDR" id="proj_amountTotIDR" value="<?php echo $proj_amountnPPnIDR; ?>">
				                    </div>
				                </div>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="box box-success">
							<div class="box-header with-border">
								<i class="fa fa-dollar"></i>
								<h3 class="box-title"><?php echo $ChargeFiled; ?></h3>
							</div>
							<div class="box-body">
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $ChargeFiled; ?></label>
				                    <div class="col-sm-4">
				                    	<input type="text" class="form-control" style="max-width:200px; text-align:right" name="SI_VALUE1" id="SI_VALUE1" value="<?php print number_format($SI_VALUE, 2); ?>" onBlur="getSIValue(this)" onKeyPress="return isIntOnlyNew(event);" <?php if($SI_STAT != 1) { ?> readonly <?php } ?>>
										<input type="hidden" size="15" class="textbox" style="text-align:right;" name="SI_VALUE" id="SI_VALUE" value="<?php echo $SI_VALUE; ?>">
										<input type="hidden" name="SI_STATVAL" id="SI_STATVAL" value="<?php echo $SI_STAT; ?>">
										<input type="hidden" size="17" class="textbox" style="text-align:right;" name="SI_DPVAL" id="SI_DPVAL" value="<?php echo $SI_DPVAL; ?>">
										<input type="hidden" size="2" class="textbox" style="text-align:right;" name="SI_DPPER" id="SI_DPPER" value="<?php echo $SI_DPPER; ?>">

		                        		<input type="text" class="form-control" style="max-width:200px; text-align:right; display:none" name="SI_APPVAL1" id="SI_APPVAL1" value="<?php print number_format($SI_APPVAL, 2); ?>" onBlur="getSIAPPVal(this)" onKeyPress="return isIntOnlyNew(event);">
		                   	 			<input type="hidden" class="form-control" style="text-align:right;max-width:200px;" name="SI_APPVAL" id="SI_APPVAL" value="<?php echo $SI_APPVAL; ?>">
				                    </div>
				                    <div class="col-sm-1">
				                    	<label for="inputName" class="col-sm-3 control-label">RAP</label>
				                    </div>
				                    <div class="col-sm-4">
				                    	<input type="text" class="form-control" style="max-width:80px; text-align:right; display:none" name="SI_PROPPERC1" id="SI_PROPPERC1" value="<?php print number_format($SI_PROPPERC, 4); ?>" onBlur="getPROPPERValue(this.value)">
				                      	<input type="hidden" size="2" class="textbox" style="text-align:right;" name="SI_PROPPERC" id="SI_PROPPERC" value="<?php echo $SI_PROPPERC; ?>">
				                      	<?php /*?> BIAYA ASLI <?php */?>
				                   		<input type="hidden"  class="form-control" style="max-width:200px; text-align:right" name="SI_PROPVAL1" id="SI_PROPVAL1" value="<?php print number_format($SI_PROPVAL, 2); ?>" onKeyPress="return isIntOnlyNew(event);" >
				                        <input type="hidden" size="15" class="textbox" style="text-align:right;" name="SI_PROPVAL" id="SI_PROPVAL" value="<?php echo $SI_PROPVAL; ?>">
				                   		<input type="text"  class="form-control" style="max-width:200px; text-align:right" name="SI_REALVAL1" id="SI_REALVAL1" value="<?php print number_format($SI_REALVAL, 2); ?>" onKeyPress="return isIntOnlyNew(event);" onBlur="getREAL(this)" >
				                        <input type="hidden" size="15" class="textbox" style="text-align:right;" name="SI_REALVAL" id="SI_REALVAL" value="<?php echo $SI_REALVAL; ?>">
				                    </div>
				                </div>
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Status; ?></label>
				                    <div class="col-sm-9">
				                    	<input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $SI_STAT; ?>">
					                    <?php
					                    	if($SI_STAT == 1 || $SI_STAT == 4)
					                    	{
					                    		?>
													<select name="SI_STAT" id="SI_STAT" class="form-control select2">
														<option value="0"> -- </option>
														<option value="1"<?php if($SI_STAT == 1) { ?> selected <?php } ?>>New</option>
														<option value="2"<?php if($SI_STAT == 2) { ?> selected <?php } ?>>Confirm</option>
													</select>
												<?php
					                    	}
					                    	else
					                    	{
												if($ISAPPROVE == 1)
												{
													// START : FOR ALL APPROVAL FUNCTION								
														if($disableAll == 0)
														{
															if($canApprove == 1)
															{
																$disButton	= 0;
																$sqlCAPPHE	= "tbl_approve_hist WHERE AH_CODE = '$SI_CODE' AND AH_APPROVER = '$DefEmp_ID'";
																$resCAPPHE	= $this->db->count_all($sqlCAPPHE);
																if($resCAPPHE > 0)
																	$disButton	= 1;
																?>
																	<select name="SI_STAT" id="SI_STAT" class="form-control select2" <?php if($disButton == 1) { ?> disabled <?php } ?> >
																		<option value="0"> -- </option>
																		<option value="1"<?php if($SI_STAT == 1) { ?> selected <?php } ?>>New</option>
																		<option value="2"<?php if($SI_STAT == 2) { ?> selected <?php } ?>>Confirm</option>
																		<option value="3"<?php if($SI_STAT == 3) { ?> selected <?php } ?>>Approved</option>
																		<option value="4"<?php if($SI_STAT == 4) { ?> selected <?php } ?>>Revised</option>
																		<option value="5"<?php if($SI_STAT == 5) { ?> selected <?php } ?>>Rejected</option>
																		<option value="6"<?php if($SI_STAT == 6) { ?> selected <?php } ?> disabled>Closed</option>
																		<option value="7"<?php if($SI_STAT == 7) { ?> selected <?php } ?> disabled>Awaiting</option>
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
											}
					                    ?>
				                    </div>
				                </div>
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label">&nbsp;</label>
				                    <div class="col-sm-9">
				                    	<?php
											if($disableAll == 0)
											{
												if(($SI_STAT == 2 || $SI_STAT == 7) && $canApprove == 1)
												{
													?>
														<button class="btn btn-primary" >
														<i class="fa fa-save"></i></button>&nbsp;
													<?php
												}
												elseif($SI_STAT == 1 || $SI_STAT == 4)
												{
													?>
														<button type="button" class="btn btn-primary" onClick="submitForm(1);">
														<i class="fa fa-save"></i></button>&nbsp;
													<?php
												}
											}
				                           	echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>');
				                        ?>
				                    </div>
				                </div>
							</div>
							<br>
							<br>
						</div>
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
		var ISAPPROVE	= document.getElementById('ISAPPROVE').value;
		var SI_IS_AMAND	= document.getElementById('SI_IS_AMAND').value;
		
		if(SI_STATVAL == 3)
		{
			if(SI_IS_AMAND == 2)
			{
				swal('Document has Approved/Closed. You can not update this document.');
				return false;
			}			
		}
		
		if(SI_MANNO == '')
		{
			swal('Please input SI Manual Number.');
			document.getElementById('SI_MANNO').focus();
			return false;
		}
		
		if(SI_STEP == 0)
		{
			swal('Please select step of Site Instruction.');
			document.getElementById('SI_STEP').focus();
			return false;
		}
		
		if(SI_VALUE == 0)
		{
			swal('Please input Charges Value.');
			document.getElementById('SI_VALUE1').value = '';
			document.getElementById('SI_VALUE1').focus();
			return false;
		}
		
		//if(FlagUSER == 'APPSI')
		if(ISAPPROVE == 1)
		{
			var SI_APPVAL	= document.getElementById('SI_APPVAL').value;
			var SI_PROPPERC	= document.getElementById('SI_PROPPERC').value;
			
			if(SI_APPVAL == 0)
			{
				swal('Please input Charges Approved Value.');
				document.getElementById('SI_APPVAL1').value = '';
				document.getElementById('SI_APPVAL1').focus();
				return false;
			}
			if(SI_PROPPERC == 0)
			{
				swal('Please input Proposed Percentation.');
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
				swal('Please input Amandement Number.');
				document.getElementById('SI_AMANDNO').focus();
				return false;
			}
			
			if(SI_AMANDVAL == 0)
			{
				swal('Please input Amandement Value.');
				document.getElementById('SI_AMANDVAL1').value = '';
				document.getElementById('SI_AMANDVAL1').focus();
				return false;
			}
			
			if(SI_AMANDSTATVAL == 0)
			{
				swal('Please select Amandement Status.');
				document.getElementById('SI_AMANDSTATVAL').value 	= 1;
				document.getElementById('SI_AMANDSTAT1').checked	= true;
				return false;
			}
		}
		//swal('a')
		//return false;
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
	
	/*function doDecimalFormat(angka)
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
	}*/
	
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
		//else return (c);  // untuk menghilangkan 2 angka di belakang koma
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
		//swal(date)
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