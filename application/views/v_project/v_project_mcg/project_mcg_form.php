<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 06 September 2018
 * File Name	= project_mcg_form.php
 * Location		= -
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$FlagUSER 	= $this->session->userdata('FlagUSER');
$appBody    = $this->session->userdata('appBody');

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

// _global function
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
$decFormat		= 2;


$currentRow = 0;
if($task == 'add')
{
	$MCH_DateY	= date('Y');
	$MCH_DateM 	= date('m');
	$MCH_DateD 	= date('d');
	$MCH_DATE	= "$MCH_DateM/$MCH_DateD/$MCH_DateY";
	$MCH_DATEx	= mktime(0,0,0,$MCH_DateM,$MCH_DateD,$MCH_DateY);
	$MCH_TTOTerm	= 14;
	$MCH_ENDDATE = date("m/d/Y",strtotime("+$MCH_TTOTerm days",$MCH_DATEx));
}
else
{
	$MCH_DATE 		=  $default['MCH_DATE'];
	$MCH_ENDDATE 	=  $default['MCH_DATE'];
}

$proj_amountIDR1	= 0;
$sqlPRJ 			= "SELECT ISCHANGE, PRJCOST, PRJCOST2 FROM tbl_project
						WHERE PRJCODE = '$PRJCODE'";
$resultPRJ 			= $this->db->query($sqlPRJ)->result();
foreach($resultPRJ  as $rowPRJ) :
	$ISCHANGE		= $rowPRJ->ISCHANGE;
	$proj_amountIDR1= $rowPRJ->PRJCOST;
	$proj_amountIDR2= $rowPRJ->PRJCOST2;
	if($ISCHANGE == 1)
	{
		$proj_amountIDR1= $rowPRJ->PRJCOST2;
	}
	$PRJCOSTnPPn	= round(($proj_amountIDR1 * 0.1) + $proj_amountIDR1);
endforeach;

// MENCACRI APAKAH ADA SI INCLUDE TO PROJECT /  CONTRACT
$TOTMCH_APPVAL		= 0;

$proj_amountIDR		= round($proj_amountIDR1 + $TOTMCH_APPVAL);
$proj_amountIDRX	= round($proj_amountIDR1 + $TOTMCH_APPVAL);
$proj_amountIDRXnPPn= round(($proj_amountIDRX * 0.1) + $proj_amountIDRX);

$currentRow = 0;
if($task == 'add')
{
	$DefEmp_ID		= $this->session->userdata['Emp_ID'];
	
	$dataSessSrc = array(
			'selSearchproj_Code' => $PRJCODE,
			'selSearchType' => $this->input->post('selSearchType'),
			'txtSearch' => $this->input->post('txtSearch'));
	$this->session->set_userdata('dtSessSrc1', $dataSessSrc);
	$this->session->set_userdata('dtSessSrc2', $dataSessSrc);
	
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
	//$this->db->where('Patt_Month', $month);
	//$this->db->where('Patt_Date', $date);
	$myCount = $this->db->count_all('tbl_mcg_header');
	
	$sql = "SELECT MAX(PATT_NUMBER) as maxNumber FROM tbl_mcg_header WHERE PRJCODE = '$PRJCODE'";
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
	$PATT_NUMBER	= $lastPatternNumb1;
	
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
	
	$PRJOWN	= "";
	$sql 	= "SELECT proj_Number, PRJCODE, PRJNAME, PRJOWN FROM tbl_project
				WHERE PRJCODE = '$PRJCODE'";
	$resultProj = $this->db->query($sql)->result();
	foreach($resultProj as $row) :
		$proj_Number = $row->proj_Number;
		$PRJCODE	= $row->PRJCODE;
		$PRJNAME 	= $row->PRJNAME;
		$PRJOWN		= $row->PRJOWN;
	endforeach;
	$DocNumber 		= "$Pattern_Code$PRJCODE$groupPattern-$lastPatternNumb";
	$MCH_CODE 		= "$Pattern_Code$PRJCODE$groupPattern-$lastPatternNumb";
	$MCH_MANNO		= '';
	$PRJCODE		= $PRJCODE;
	
	$MCGCODE		= substr($lastPatternNumb, -4);
	$MCGYEAR		= date('y');
	$MCGMONTH		= date('m');
	$MCH_CODE		= "$Pattern_Code.$MCGCODE.$MCGYEAR.$MCGMONTH"; // MANUAL CODE
	
	$MCH_DateY 		= date('Y');
	$MCH_DateM 		= date('m');
	$MCH_DateD 		= date('d');
	$MCH_DATE		= "$MCH_DateM/$MCH_DateD/$MCH_DateY";
	//$MCH_ENDDATE 	= $MCH_Date;
	$MCH_CHECKD		= "$MCH_DateM/$MCH_DateD/$MCH_DateY";
	$MCH_CREATED 	= "$MCH_DateM/$MCH_DateD/$MCH_DateY";
	
		$MCH_DPPER1	= 0;
		$MCH_DPVAL1	= 0;
		$MCH_DPVAL2	= 0;
		$DPPPNVAL 	= 0;
		$sqlDP 		= "SELECT SUM(PINV_DPPER) AS DPPERCENT, SUM(PINV_DPVAL) AS DPVALUE, SUM(PINV_DPVALPPn) AS DPPPNVAL
						FROM tbl_projinv_header WHERE PRJCODE = '$PRJCODE' AND PINV_CAT = 1 AND PINV_STAT IN (3,6)";
		$resDP 		= $this->db->query($sqlDP)->result();
		foreach($resDP as $rowDP) :
			$MCH_DPPER 	= $rowDP ->DPPERCENT;
			$MCH_DPVALA 	= $rowDP ->DPVALUE;
			$DPPPNVALA 	= $rowDP ->DPPPNVAL;
			$MCH_DPPER1	= round($MCH_DPPER1 + $MCH_DPPER);
			$MCH_DPVAL1	= round($MCH_DPVAL1 + $MCH_DPVALA);
			$MCH_DPVAL2	= round($MCH_DPVAL2 + $MCH_DPVALA + $DPPPNVALA);
		endforeach;
	$MCH_DPPER		= $MCH_DPPER1;
	$MCH_DPVAL		= $MCH_DPVAL1;
	$MCH_DPVALnPPn	= $MCH_DPVAL2;
	$MCH_RETVAL		= round(0.05 * $proj_amountIDRXnPPn);
	$MCH_DPBACK		= 0;
	$MCH_DPBACKCUR	= 0;
	$MCH_RETCUTP	= 0;
	$MCH_RETCUT		= 0;
	$MCH_RETCUTCUR	= 0;
	$MCH_PROG		= 0;
	$MCH_PROGVAL	= 0;
	$MCH_PROGCUR	= 0;
	$MCH_PROGCURVAL	= 0;
	$MCH_PROGVALXY	= 0;
	$MCH_PROGAPP 	= 0;
	$MCH_PROGAPPVAL = 0;
	$MCH_VALADD		= 0;
	$MCH_MATVAL		= 0;
	$MCH_VALBEF		= 0;
	$MCH_AKUMNEXT	= 0;
	$MCH_TOTVAL		= 0;
	$MCH_NOTES		= '';
	$MCH_EMPID		= $DefEmp_ID;
	$MCH_STAT		= 1;
	
	$PATT_YEAR 		= date('Y');
	$PATT_MONTH		= date('m');
	$PATT_DATE 		= date('d');
	
	$DPExist 		= 0;
	$sqlDP 			= "tbl_projinv_header WHERE PRJCODE = '$PRJCODE' AND PINV_CAT = 1 AND PINV_STAT = 2";
	$resultDP 		= $this->db->count_all($sqlDP);
	if($resultDP > 0)
	{
		$DPExist 	= 1;
	}
	
	$MCH_ENDDATE0	= mktime(0,0,0,$MCH_DateM,$MCH_DateD,$MCH_DateY);
	$MCH_ENDDATE1	= 14;
	$MCH_ENDDATE 	= date("m/d/Y",strtotime("+$MCH_ENDDATE1 days",$MCH_ENDDATE0));
	$MCH_OWNER		= $PRJOWN;
	
	// MENCARI NILAI PEMBAYARAN SEBELUMNYA
	$MCH_AKUMNEXT	= 0;
	$PATT_NUMBEF	= $lastPatternNumb1 - 1; // PATTERN NUMBER SEBELUMNYA
	$sqlMC 			= "tbl_mcg_header WHERE PRJCODE = '$PRJCODE' AND PATT_NUMBER = $PATT_NUMBEF AND MCH_STAT = 3";
	$resMC 			= $this->db->count_all($sqlMC);
	if($resMC > 0)
	{
		$sqlAKN	= "SELECT MCH_AKUMNEXT FROM tbl_mcg_header 
					WHERE PRJCODE = '$PRJCODE' AND PATT_NUMBER = $PATT_NUMBEF AND MCH_STAT = 3";
		$resAKN	= $this->db->query($sqlAKN)->result();
		foreach($resAKN as $rowAKN) :
			$MCH_VALBEF = $rowAKN ->MCH_AKUMNEXT;
		endforeach;
	}
	// APABILA TIDAK ADA, BERARTI DARI AKUMULASI DP
	else
	{
		$MCH_VALBEF 	= $MCH_DPVAL1;			
	}
	
	// CEK APAKAH PERNAH DIBUATKAN MC ATAU TIDA. APABILA SUDAH MAKA AMBIL NILAI MAKSIMUM DARI MC. APABILA TIDAK ADA, AMBIL NILAI MAX DARI DP
	$sqlMCEXIST		= "tbl_mcg_header WHERE PRJCODE = '$PRJCODE'";
	$resMCEXIST		= $this->db->count_all($sqlMCEXIST);
	if($resMCEXIST > 0)
	{
		$sqlMAXMC 	= "SELECT MAX(MCH_STEP) AS MAXSTEP FROM tbl_mcg_header
						WHERE PRJCODE = '$PRJCODE'";
		$resMAXMC 	= $this->db->query($sqlMAXMC)->result();
		foreach($resMAXMC as $rowMAXMC) :
			$MAXSTEP = $rowMAXMC->MAXSTEP;
		endforeach;
		$MAXSTEP	= $MAXSTEP + 1;
	}
	else
	{
		$sqlDPEXIST	= "tbl_projinv_header WHERE PRJCODE = '$PRJCODE' AND PINV_CAT = 1 AND PINV_STAT = 2";
		$resDPEXIST	= $this->db->count_all($sqlDPEXIST);
		$MAXSTEP	= $resDPEXIST + 1;
	}
	
	// CEK APAKAH ADA DP
	$PATT_NUMBEF	= $lastPatternNumb1 - 1; // PATTERN NUMBER SEBELUMNYA
	
	$MCH_TOTPROGRESS= $MCH_PROGVAL + $MCH_VALADD + $MCH_MATVAL;
	$MCH_PAYBEFRET	= $MCH_TOTPROGRESS + $MCH_DPVAL - $MCH_DPBACK - $MCH_RETCUT;
	$MCH_PAYAKUM	= $MCH_PAYBEFRET;
	$MCH_TOTVAL	= 0;
	$MCH_PAYDUE		= 0;
	$MCH_PAYDUEPPh	= 0;	
	$MCH_TOTVAL_PPn	= 0;
	$MCH_TOTVAL_PPh	= 0;
	$TOTPAYMENT		= 0;
	$MCH_STEP		= $MAXSTEP;
}
else
{
	$isSetDocNo 	= 1;
	$DocNumber		= $default['MCH_CODE'];	
	$MCH_CODE 		= $default['MCH_CODE'];
	$MCH_MANNO 		= $default['MCH_MANNO'];
	$MCH_STEP 		= $default['MCH_STEP'];
	$PRJCODE 		= $default['PRJCODE'];
	$MCH_OWNER 		= $default['MCH_OWNER'];
	$MCH_DATE 		= $default['MCH_DATE'];
	$MCH_DATE		= date('m/d/Y',strtotime($MCH_DATE));
	$MCH_ENDDATE 	= $default['MCH_ENDDATE'];
	$MCH_ENDDATE	= date('m/d/Y',strtotime($MCH_ENDDATE));
	$MCH_RETVAL 	= $default['MCH_RETVAL'];
	$MCH_PROG 		= $default['MCH_PROG'];
	$MCH_PROGVAL 	= $default['MCH_PROGVAL'];
	$MCH_PROGCUR 	= $default['MCH_PROGCUR'];
	$MCH_PROGCURVAL = $default['MCH_PROGCURVAL'];
	$MCH_VALADD 	= $default['MCH_VALADD'];
	$MCH_MATVAL 	= $default['MCH_MATVAL'];
	$MCH_DPPER 		= $default['MCH_DPPER'];
	$MCH_DPVAL 		= $default['MCH_DPVAL'];
	$MCH_DPBACK 	= $default['MCH_DPBACK'];
	$MCH_DPBACKCUR 	= $default['MCH_DPBACKCUR'];
	$MCH_RETCUTP 	= $default['MCH_RETCUTP'];
	$MCH_RETCUT 	= $default['MCH_RETCUT'];
	$MCH_RETCUTCUR 	= $default['MCH_RETCUTCUR'];
	$MCH_PROGAPP 	= $default['MCH_PROGAPP'];
	$MCH_PROGAPPVAL	= $default['MCH_PROGAPPVAL'];
	$MCH_VALBEF		= $default['MCH_VALBEF'];
	$MCH_AKUMNEXT 	= $default['MCH_AKUMNEXT'];
	$MCH_TOTVAL 	= $default['MCH_TOTVAL'];
	$MCH_TOTVAL_PPn = $default['MCH_TOTVAL_PPn'];
	$MCH_TOTVAL_PPh = $default['MCH_TOTVAL_PPh'];
	$MCH_NOTES 		= $default['MCH_NOTES'];
	$MCH_CREATED 	= $default['MCH_CREATED'];
	$MCH_CREATER 	= $default['MCH_CREATER'];
	$MCH_EMPID 		= $default['MCH_EMPID'];
	$MCH_STAT 		= $default['MCH_STAT'];
	$PATT_YEAR 		= $default['PATT_YEAR'];
	$PATT_MONTH 	= $default['PATT_MONTH'];
	$PATT_DATE 		= $default['PATT_DATE'];
	$PATT_NUMBER 	= $default['PATT_NUMBER'];
}
	
$sqlOWN	= "SELECT own_Code, own_Title, own_Name FROM tbl_owner WHERE own_Status = '1'";
$resOWN	= $this->db->query($sqlOWN)->result();
?>
<!DOCTYPE html>
<html>
  	<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $appName; ?></title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <link rel="icon" type="image/png" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/favicon/lock-02.png'; ?>" sizes="32x32">
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

	<script src="<?php echo base_url('assets/js/jquery-1.10.1.min.js') ?>" type="text/javascript"></script>

	<?php
		$this->load->view('template/mna');
		//______$this->load->view('template/topbar');
		//______$this->load->view('template/sidebar');

		$ISREAD 	= $this->session->userdata['ISREAD'];
		$ISCREATE 	= $this->session->userdata['ISCREATE'];
		$ISAPPROVE 	= $this->session->userdata['ISAPPROVE'];
		$ISDWONL 	= $this->session->userdata['ISDWONL'];
		$LangID 	= $this->session->userdata['LangID'];
		
		if($ISAPPROVE == 1)
			$ISCREATE	= 1;

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
			if($TranslCode == 'MCNumber')$MCNumber = $LangTransl;
			if($TranslCode == 'ManualNumber')$ManualNumber = $LangTransl;
			if($TranslCode == 'MCStep')$MCStep = $LangTransl;
			if($TranslCode == 'Project')$Project = $LangTransl;
			if($TranslCode == 'ProjectValue')$ProjectValue = $LangTransl;
			if($TranslCode == 'PPNValue')$PPNValue = $LangTransl;
			if($TranslCode == 'TotalProjectValue')$TotalProjectValue = $LangTransl;
			if($TranslCode == 'Progress')$Progress = $LangTransl;
			if($TranslCode == 'ProgressApproved')$ProgressApproved = $LangTransl;
			if($TranslCode == 'PercentationValue')$PercentationValue = $LangTransl;
			if($TranslCode == 'AdvPaymentInstallment')$AdvPaymentInstallment = $LangTransl;
			if($TranslCode == 'Notes')$Notes = $LangTransl;
			if($TranslCode == 'DateofFilling')$EdDateofFillingit = $LangTransl;
			if($TranslCode == 'MCDate')$MCDate = $LangTransl;
			if($TranslCode == 'ApproveDateTarget')$ApproveDateTarget = $LangTransl;
			if($TranslCode == 'DPPercentation')$DPPercentation = $LangTransl;
			if($TranslCode == 'DPValue')$DPValue = $LangTransl;
			if($TranslCode == 'PaymentBefore')$PaymentBefore = $LangTransl;
			if($TranslCode == 'PaymentNext')$PaymentNext = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'AmountDue')$AmountDue = $LangTransl;
			if($TranslCode == 'Amount')$Amount = $LangTransl;
			if($TranslCode == 'ReceiptAmount')$ReceiptAmount = $LangTransl;
			if($TranslCode == 'DateofFiling')$DateofFiling = $LangTransl;
			if($TranslCode == 'ProgressAmmount')$ProgressAmmount = $LangTransl;
			if($TranslCode == 'Retention')$Retention = $LangTransl;
			if($TranslCode == 'AddMC')$AddMC = $LangTransl;
			if($TranslCode == 'Owner')$Owner = $LangTransl;
			if($TranslCode == 'MCPayment')$MCPayment = $LangTransl;
			if($TranslCode == 'MCAmount')$MCAmount = $LangTransl;
			if($TranslCode == 'MCCode')$MCCode = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'Code')$Code = $LangTransl;
			if($TranslCode == 'Date')$Date = $LangTransl;
			if($TranslCode == 'Progress')$Progress = $LangTransl;
			if($TranslCode == 'ProgressAmmount')$ProgressAmmount = $LangTransl;
			if($TranslCode == 'InvAmount')$InvAmount = $LangTransl;
		endforeach;
		$isEdit	= 'N';
		if($MCH_STAT == 1)
		{
			$isEdit	= 'Y';
		}
		if($MCH_STAT == 4)
		{
			$isEdit	= 'Y';
		}
		elseif($MCH_STAT == 2 && $ISAPPROVE != 1)
		{
			$isEdit	= 'N';
		}
		elseif($MCH_STAT == 2 && $ISAPPROVE == 1)
		{
			$isEdit	= 'Y';
		}
		elseif($MCH_STAT == 3)
		{
			$isEdit	= 'N';
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
				$sqlC_App	= "tbl_approve_hist WHERE AH_CODE = '$MCH_CODE'";
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
				$APPROVE_AMOUNT = $MCH_TOTVAL;
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
		<!-- Content Header (Page header) -->
		<section class="content-header">
			<h1>
			    <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/mcgroup.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $h1_title; ?>
			    <small><?php echo $PRJNAMEH; ?></small>
			</h1>
		</section>

		<section class="content">
		    <div class="box box-primary">
		    	<div class="box-body chart-responsive">
		            <form class="form-horizontal" name="frm" method="post" action="<?php echo $form_action; ?>" enctype="multipart/form-data" onSubmit="return checkForm()">
	                    <input type="hidden" name="IS_LAST" id="IS_LAST" value="<?php echo $IS_LAST; ?>">
	                    <input type="hidden" name="APP_LEVEL" id="APP_LEVEL" value="<?php echo $APP_LEVEL; ?>">
	                    <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
	                    <input type="Hidden" name="rowCount" id="rowCount" value="0">
	                    <input type="hidden" name="FlagUSER" id="FlagUSER" value="<?php echo $FlagUSER; ?>">
	                    <input type="hidden" name="ISAPPROVE" id="ISAPPROVE" value="<?php echo $ISAPPROVE; ?>">
	                    <input type="hidden" name="MCH_STATX" id="MCH_STATX" value="<?php echo $MCH_STAT; ?>">
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
	                          <td width="16%" align="left" class="style1"> &nbsp;&nbsp; <?php echo $MCNumber ?></td>
	                          <td width="1%" align="left" class="style1">:</td>
	                          <td width="36%" align="left" class="style1">
	                          	<input type="text" name="DocNumber" id="DocNumber" value="<?php echo $DocNumber; ?>" class="form-control" style="max-width:200px" disabled>
	                            <input type="hidden" class="textbox" name="MCH_CODE" id="MCH_CODE" size="30" value="<?php echo $MCH_CODE; ?>" />
	                            <input type="hidden" class="textbox" name="PATT_NUMBER" id="PATT_NUMBER" size="30" value="<?php echo $PATT_NUMBER; ?>" />
	                          </td>
	                          <td width="12%" align="left" class="style1"><?php if($ISAPPROVE == 0) { ?> <?php echo $DateofFiling ?>  <?php } else { ?><?php echo $MCDate ?>  <?php } ?></td>
	                          <td width="35%" align="left" class="style1">
	                                  <div class="input-group date">
	                                    <div class="input-group-addon">
	                                    <i class="fa fa-calendar"></i>&nbsp;</div>
	                                    <input type="text" name="MCH_DATE" class="form-control pull-left" id="datepicker" value="<?php echo $MCH_DATE; ?>" style="width:150px">
	                                </div>
	                            </td>
	                        <!-- MCH_CODE, PATT_NUMBER, MCH_MANNO -->
	                      </tr>
	                      <tr>
	                          <td align="left" class="style1">&nbsp;&nbsp;<?php echo $ManualNumber ?> </td>
	                          <td align="left" class="style1">:</td>
	                          <td align="left" class="style1">
	                          <input type="text" name="MCH_MANNO" id="MCH_MANNO" value="<?php echo $MCH_MANNO; ?>" class="form-control" style="max-width:200px">
	                          </td>
	                          <td align="left" class="style1"><?php echo $ApproveDateTarget ?> </td>
	                          <td align="left" class="style1">
	                              <input type="hidden" name="MCH_ENDDATE" id="MCH_ENDDATE" value="<?php echo $MCH_ENDDATE; ?>" class="form-control" style="max-width:350px" >
	                                <div class="input-group date">
	                                    <div class="input-group-addon">
	                                    <i class="fa fa-calendar"></i>&nbsp;</div>
	                                    <input type="text" name="MCH_ENDDATE" class="form-control pull-left" id="datepicker2" value="<?php echo $MCH_ENDDATE; ?>" style="width:150px">
	                                </div>
	                            </td>
	                          <!-- MCH_CODE, PATT_NUMBER, MCH_MANNO -->
	                      </tr>
	                      <tr>
	                          <td align="left" class="style1">&nbsp;&nbsp;<?php echo $MCStep ?></td>
	                          <td align="left" class="style1">:</td>
	                          <td align="left" class="style1">
	                          	<input type="text" name="MCH_STEP" id="MCH_STEP" value="<?php echo $MCH_STEP; ?>" class="form-control" style="max-width:100px">
	                          </td>
	                          <td align="left" class="style1" nowrap>&nbsp;</td>
	                          <td align="left" class="style1">&nbsp;</td>
	                          <!-- MCH_STEP, MCH_ENDDATE -->
	                      </tr>
	                      <script>
	                            /*function changeStep(thisVal)
	                            {
	                                document.getElementById('MCH_STEP').value = thisVal;
	                            }
	                    
	                            function changeFDate(thisVal)
	                            {
	                                var date 			= new Date(thisVal);
	                                var datey 			= new Date(date.getFullYear(), date.getMonth(), date.getDate(), 0, 0, 0);
	                                var theM			= datey.getMonth();
	                                var dateDesc		= datey.getFullYear()+ "-" + theM + "-" + datey.getDate();
	                                document.getElementById('MCDATEx').value 	= formatDate(datey);
	                                var FDate			= document.getElementById('MCDATEx').value
	                                changeDueDate(FDate)
	                            }
	                    
	                            function changeFDateTarget()
	                            {
	                                //MCH_DATE				= document.getElementById('MCH_DATE').value;
	                                //swal(MCH_DATE)
	                            }*/
	                        </script>
	                      <tr>
	                          <td align="left" class="style1">&nbsp;&nbsp;<?php echo $Project ?></td>
	                          <td align="left" class="style1">:</td>
	                          <td align="left" class="style1">
	                            <select name="PRJCODE1" id="PRJCODE1" class="form-control" style="max-width:350px" onChange="chooseProject()" disabled>
	                                <option value="none">--- None ---</option>
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
	                          <input type="hidden" name="PRJCODE" id="PRJCODE" value="<?php echo $PRJCODE; ?>" ></td>
	                          <td align="left" class="style1" nowrap><?php echo $Owner; ?></td>
	                          <td align="left" class="style1">
	                              <script type="text/javascript">SunFishERP_DateTimePicker('MCH_CHECKD','<?php echo $MCH_CHECKD;?>','onMouseOver="mybirdthdates();"');</script>
	                              <select name="MCH_OWNER" id="MCH_OWNER" class="form-control" style="max-width:350px">
	                                <option value="none">--- None ---</option>
	                                <?php
	                                    foreach($resOWN as $rowOWN) :
											$own_Code 	= $rowOWN ->own_Code;
											$own_Title 	= $rowOWN ->own_Title;
											$own_Name 	= $rowOWN ->own_Name;
											if($own_Title != '')
												$ownCompN	= "$own_Name, $own_Title";
											else
												$ownCompN	= "$own_Name";
	                                        ?>
	                                        <option value="<?php echo $own_Code; ?>" <?php if($own_Code == $MCH_OWNER) { ?> selected <?php } ?>><?php echo $ownCompN; ?></option>
	                                        <?php
	                                    endforeach;
	                                ?>
	                            </select>
							</td>
	                      </tr>
	                      <tr>
	                          <td align="left" class="style1">&nbsp;&nbsp;<?php echo $ProjectValue ?> </td>
	                          <td align="left" class="style1">:</td>
	                          <td align="left" class="style1">
	                              <input type="hidden" size="10" class="textbox" style="text-align:right;" name="MCH_RETVAL" id="MCH_RETVAL" value="<?php echo $MCH_RETVAL; ?>">
	                              <input type="text" class="form-control" style="max-width:200px; text-align:right;" name="proj_amountIDR1" id="proj_amountIDR1" value="<?php print number_format($proj_amountIDR, $decFormat); ?>" disabled>
	                              <input type="hidden" class="form-control" style="max-width:200px; text-align:right;" name="proj_amountIDR" id="proj_amountIDR" value="<?php echo $proj_amountIDR; ?>">                  </td> 
	                          <td align="left" class="style1" nowrap><?php echo $DPPercentation ?>  (%)</td>
	                          <td align="left" class="style1">
	                              <input type="text" class="form-control" style="max-width:90px; text-align:right;" name="MCH_DPPER1" id="MCH_DPPER1" value="<?php print number_format($MCH_DPPER, 4); ?>" onBlur="getDPValue(this.value)" disabled>
	                              <input type="hidden" size="2" class="textbox" style="text-align:right;" name="MCH_DPPER" id="MCH_DPPER" value="<?php echo $MCH_DPPER; ?>">                  </td>
	                          <!-- MCH_RETVAL, MCH_DPPER, MCH_DPPER1 -->
	                      </tr>
	                      <tr>
	                          <td align="left" class="style1">&nbsp;&nbsp;<?php echo $PPNValue ?> (10%)</td>
	                          <td align="left" class="style1">:</td>
	                          <td align="left" class="style1">
	                            <?php
	                                $proj_amountPPnIDR 	= round($proj_amountIDR * 0.1);
	                                $proj_amountnPPnIDR = round($proj_amountIDR + $proj_amountPPnIDR);
	                            ?>
	                            <input type="text" class="form-control" style="max-width:200px; text-align:right;" name="proj_amountPPnIDR1" id="proj_amountPPnIDR1" value="<?php print number_format($proj_amountPPnIDR, $decFormat); ?>" disabled>
	                            <input type="hidden" class="form-control" style="max-width:200px; text-align:right;" name="proj_amountPPnIDR" id="proj_amountPPnIDR" value="<?php echo $proj_amountPPnIDR; ?>">              </td> 
	                          <td align="left" class="style1"><?php echo $DPValue ?> </td>
	                          <td align="left" class="style1">
	                              <input type="text" size="17"  class="form-control" style="max-width:200px; text-align:right;" name="MCH_DPVAL1" id="MCH_DPVAL1" value="<?php print number_format($MCH_DPVAL, $decFormat); ?>" disabled>
	                              <input type="hidden" size="17"  class="form-control" style="max-width:150px; text-align:right;" name="MCH_DPVAL" id="MCH_DPVAL" value="<?php echo $MCH_DPVAL; ?>">                  </td>
	                          <!-- MCH_DPVAL, MCH_DPVAL1 -->
	                      </tr>
	                      <script>
	                            function getDPValue(thisVal)
	                            {
	                                /*var decFormat		= document.getElementById('decFormat').value;
	                                document.getElementById('MCH_DPPER1').value 		= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(thisVal)),decFormat));
	                                document.getElementById('MCH_DPPER').value 		= thisVal;
	                                proj_amountIDR		= document.getElementById('proj_amountTotIDR').value;
	                                MCH_DPVALx			= parseFloat(thisVal) * parseFloat(proj_amountIDR) / 100;
	                                document.getElementById('MCH_DPVAL').value 		= MCH_DPVALx;
	                                document.getElementById('MCH_DPVAL1').value 		= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MCH_DPVALx)),decFormat));*/
	                            }
	                        </script>
	                      <tr>
	                          <td align="left" class="style1" nowrap>&nbsp;&nbsp;<?php echo $TotalProjectValue ?>  (+ PPn 10%)</td>
	                          <td align="left" class="style1">:</td>
	                          <td align="left" class="style1">
	                            <input type="text" class="form-control" style="max-width:200px; text-align:right;" name="proj_amountTotIDR1" id="proj_amountTotIDR1" value="<?php print number_format($proj_amountnPPnIDR, $decFormat); ?>" disabled>
	                            <input type="hidden" class="form-control" style="max-width:200px; text-align:right;" name="proj_amountTotIDR" id="proj_amountTotIDR" value="<?php echo $proj_amountnPPnIDR; ?>">
	                            <input type="hidden" class="form-control" style="max-width:200px; text-align:right;" name="proj_amountTotIDRNon" id="proj_amountTotIDRNon" value="<?php echo $proj_amountIDR; ?>">                  </td> 
	                          <td align="left" class="style1" ><?php echo $DPValue ?>  + PPn 10%</td>
	                          <td align="left" class="style1" >
	                              <input type="text" class="form-control" style="max-width:200px; text-align:right;" name="MCH_DPVAL1" id="MCH_DPVAL1" value="<?php print number_format($MCH_DPVAL, $decFormat); ?>" disabled></td>
	                      </tr>
	                      <tr>
	                          <td align="left" class="style1">&nbsp;&nbsp;<?php echo $Progress ?>  (%)</td>
	                          <td align="left" class="style1">:</td>
	                          <td align="left" class="style1">
	                              <input type="text" class="form-control" style="max-width:90px; text-align:right;" name="MCH_PROG1" id="MCH_PROG1" value="<?php print number_format($MCH_PROG, 4); ?>" onBlur="getPROGValueX(); getPROGValue1(1)" onKeyPress="return isIntOnlyNew(event);" disabled>
	                              <input type="hidden" class="form-control" style="max-width:90px; text-align:right;" name="MCH_PROG" id="MCH_PROG" value="<?php print $MCH_PROG; ?>" >
	                              <input type="hidden" class="form-control" style="max-width:90px; text-align:right;" name="MCH_PROGX" id="MCH_PROGX" value="<?php print $MCH_PROG; ?>" >
	                              <input type="hidden" class="form-control" style="max-width:90px; text-align:right;" name="MCH_PROGCUR" id="MCH_PROGCUR" value="<?php print $MCH_PROGCUR; ?>" >
	                              <input type="hidden" class="form-control" style="max-width:90px; text-align:right;" name="MCH_PROGCURVAL" id="MCH_PROGCURVAL" value="<?php print $MCH_PROGCURVAL; ?>" >                </td>
	                          <td align="left" class="style1" ><?php echo $AdvPaymentInstallment ?></td>
	                          <td align="left" class="style1" ><input type="text" class="form-control" style="max-width:200px; text-align:right;" name="MCH_DPBACK1" id="MCH_DPBACK1" value="<?php print number_format($MCH_DPBACK, $decFormat); ?>" onBlur="getTOTDUE(this)" title="Progress Approved * 0.01" disabled>
	                          <input type="hidden" class="form-control" style="max-width:200px; text-align:right;" name="MCH_DPBACK" id="MCH_DPBACK" value="<?php echo $MCH_DPBACK; ?>">
	                          <input type="hidden" class="form-control" style="max-width:200px; text-align:right;" name="MCH_DPBACKCUR" id="MCH_DPBACKCUR" value="<?php echo $MCH_DPBACKCUR; ?>">
	                          </td>
	                          <!-- MCH_PROG, MCH_PROG1 -->
	                      </tr>
	                        <script>			
	                        function getPROGValueX()
	                        {
	                            /*var decFormat		= document.getElementById('decFormat').value;
	                            var thisValA		= document.getElementById('MCH_PROG1');
	                            var proj_amountIDR	= document.getElementById('proj_amountTotIDRNon').value;
	                            var thisVal			= eval(thisValA).value.split(",").join("");
	                            MCH_PROGAPPVAL		= parseFloat(thisVal) * parseFloat(proj_amountIDR) / 100;
	                            
	                            document.getElementById('MCH_PROG1').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(thisVal), 4));
	                            document.getElementById('MCH_PROG').value 	= thisVal;
	                            document.getElementById('MCH_PROGVAL1').value = doDecimalFormatxx(RoundNDecimal(parseFloat(MCH_PROGAPPVAL), 2));
	                            document.getElementById('MCH_PROGVAL').value = RoundNDecimal(parseFloat(MCH_PROGAPPVAL), 2);
	                                                        
	                            document.getElementById('MCH_PROGAPPx').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(thisVal), 4));
	                            document.getElementById('MCH_PROGAPP').value 	= thisVal;
	                            document.getElementById('MCH_PROGAPPVALx').value	= doDecimalFormatxx(RoundNDecimal(parseFloat(MCH_PROGAPPVAL), 2));
	                            document.getElementById('MCH_PROGAPPVAL').value 	= RoundNDecimal(parseFloat(MCH_PROGAPPVAL), 2);
	                            
	                            getPROGValue(MCH_PROGAPPVAL)*/
	                        }
	                        </script>
	                      <tr>
	                          <td align="left" class="style1">&nbsp;&nbsp;<?php echo $ProgressAmmount; ?> (Rp)</td>
	                          <td align="left" class="style1">:</td>
	                          <td align="left" class="style1">
	                              <input type="text" class="form-control" style="max-width:200px; text-align:right;" name="MCH_PROGVAL1" id="MCH_PROGVAL1" value="<?php print number_format($MCH_PROGVAL, $decFormat); ?>" onBlur="getPROGPER(this)" disabled>
	                              <input type="hidden" class="form-control" style="max-width:200px; text-align:right;" name="MCH_PROGVAL" id="MCH_PROGVAL" value="<?php print $MCH_PROGVAL; ?>" >
	                          </td>
	                          <td align="left" class="style1" >&nbsp;</td>
	                          <td align="left" class="style1" >&nbsp;</td>
	                          <!-- MCH_PROG_AMMOUNT -->
	                      </tr>
	                      <script>				
	                        function getPROGPER(thisVal)
	                        {
	                            /*var ISAPPROVE		= document.getElementById('ISAPPROVE').value;
	                            var thisValA		= thisVal;
	                            var decFormat		= document.getElementById('decFormat').value;
	                            var proj_amountIDR	= document.getElementById('proj_amountTotIDRNon').value;
	                            var thisVal			= eval(thisValA).value.split(",").join("");
	                            MCH_PROG1			= parseFloat(thisVal) / parseFloat(proj_amountIDR) * 100;
	                            
	                            document.getElementById('MCH_PROG1').value 		= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MCH_PROG1)), 4));
	                            document.getElementById('MCH_PROG').value 		= RoundNDecimal(parseFloat(MCH_PROG1), 4);
	                            document.getElementById('MCH_PROGVAL1').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(thisVal)), 2));
	                            document.getElementById('MCH_PROGVAL').value 	= RoundNDecimal(parseFloat(thisVal), 2);
	                            
	                            document.getElementById('MCH_PROGAPPx').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MCH_PROG1)), 4));
	                            document.getElementById('MCH_PROGAPP').value 	= RoundNDecimal(parseFloat(MCH_PROG1), 4);
	                            document.getElementById('MCH_PROGAPPVALx').value = doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(thisVal)), 2));
	                            document.getElementById('MCH_PROGAPPVAL').value 	= RoundNDecimal(parseFloat(thisVal), 2);
	                            
	                            getPROGValue(thisVal);*/
	                        }
	                      </script>
	                    <tr>
	                        <td align="left" class="style1">&nbsp;&nbsp;<?php echo $ProgressApproved ?>  (%)</td>
	                        <td align="left" class="style1">:</td>
	                        <td align="left" class="style1">
	                            <label><input type="text" class="form-control" style="max-width:100px; text-align:right;" name="MCH_PROGAPPx" id="MCH_PROGAPPx" value="<?php print number_format($MCH_PROGAPP, 4); ?>" onBlur="getPROGAPPValue(this.value)" onKeyPress="return isIntOnlyNew(event);" disabled>
	                            <input type="hidden" class="form-control" style="max-width:90px; text-align:right;" name="MCH_PROGAPP" id="MCH_PROGAPP" value="<?php print $MCH_PROGAPP; ?>" ></label>
	                            <label>
	                            <input type="text" class="form-control" style="max-width:190px; text-align:right;" name="MCH_PROGAPPVALx" id="MCH_PROGAPPVALx" value="<?php print number_format($MCH_PROGAPPVAL, $decFormat); ?>" onBlur="getPROGPERAPP(this.value)" title="Progress Approved Amount" disabled>
	                            <input type="hidden" class="form-control" style="max-width:200px; text-align:right;" name="MCH_PROGAPPVAL" id="MCH_PROGAPPVAL" value="<?php print $MCH_PROGAPPVAL; ?>" title="Approve Amount" >
	                            </label>
	                        </td>
	                        <td align="left" class="style1"><?php //echo $PaymentBefore ?></td>
	                        <td align="left" class="style1">
	                        	<input type="hidden" class="form-control" style="max-width:200px; text-align:right;" name="MCH_VALBEFx" id="MCH_VALBEFx" value="<?php echo number_format($MCH_VALBEF, $decFormat); ?>" title="Amount Before" disabled>
	                        	<input type="hidden" class="form-control" style="max-width:200px; text-align:right;" name="MCH_VALBEF" id="MCH_VALBEF" value="<?php echo $MCH_VALBEF; ?>" title="Amount Before">
	                        </td>
	                        <!-- MCH_PROG, MCH_PROG1 -->
	                    </tr>
	                    <script>
	                        function getPROGAPPValue(MCH_PROGAPPx)
	                        {
	                            /*var decFormat		= document.getElementById('decFormat').value;
	                            var proj_amountIDR	= document.getElementById('proj_amountTotIDRNon').value;
	                            var MCH_PROGAPP		= MCH_PROGAPPx.split(",").join("");
	                            MCH_PROGAPPVAL		= parseFloat(MCH_PROGAPP) * parseFloat(proj_amountIDR) / 100;
	                            
	                            //document.getElementById('MCH_PROG1').value = doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(thisVal)), 4));
	                            //document.getElementById('MCH_PROG').value 	= thisVal;
	                            document.getElementById('MCH_PROGAPPx').value= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MCH_PROGAPP)), 4));
	                            document.getElementById('MCH_PROGAPP').value = MCH_PROGAPP;
	                            
	                            getPROGValue(MCH_PROGAPPVAL)*/
	                        }
	                    </script>
	                    <tr>
	                        <td align="left" class="style1" nowrap>&nbsp;&nbsp;<?php echo $Retention; ?> (Rp)</td>
	                        <td align="left" class="style1">:</td>
	                        <td align="left" class="style1">
	                            <input type="hidden" class="form-control" style="max-width:200px; text-align:right;" name="MCH_VALADD" id="MCH_VALADD" value="<?php echo $MCH_VALADD; ?>" title="SI">
	                            <input type="hidden" class="form-control" style="max-width:200px; text-align:right;" name="MCH_MATVAL" id="MCH_MATVAL" value="<?php echo $MCH_MATVAL; ?>" title="Material Amount">
	                            <label>
	                            <input type="text" class="form-control" style="max-width:150px; text-align:right;" name="MCH_RETCUTx" id="MCH_RETCUTx" value="<?php echo number_format($MCH_RETCUT, $decFormat); ?>" onBlur="getTOTAL(this)" title="RetCut = 0.05*Progress Approved" disabled>
	                            <input type="hidden" class="form-control" style="max-width:130px; text-align:right;" name="MCH_RETCUTP" id="MCH_RETCUTP" value="<?php echo $MCH_RETCUTP; ?>">
	                            <input type="hidden" class="form-control" style="max-width:130px; text-align:right;" name="MCH_RETCUT" id="MCH_RETCUT" value="<?php echo $MCH_RETCUT; ?>" >
	                            <input type="hidden" class="form-control" style="max-width:130px; text-align:right;" name="MCH_RETCUTCUR" id="MCH_RETCUTCUR" value="<?php echo $MCH_RETCUTCUR; ?>"></label>
	                            <?php /*?><input type="hidden" class="form-control" style="max-width:200px; text-align:right;" name="MCH_DPBACK" id="MCH_DPBACK" value="<?php echo $MCH_DPBACK; ?>">
	                           // MCH_PAYBEFRET		= MCH_TOTPROGRESS +  MCH_DPVAL -  MCH_DPBACK - MCH_RETCUT;<?php */?>
	                           <label>
	                            <input type="hidden" class="form-control" style="max-width:140px; text-align:right;" name="MCH_PAYBEFRETx" id="MCH_PAYBEFRETx" value="<?php echo number_format($MCH_VALBEF, $decFormat); ?>" title="Pay.bef.Ret = Tot.Progres + DP - DP.Back - Ret.Cut" disabled>
	                            <input type="hidden" class="form-control" style="max-width:155px; text-align:right;" name="MCH_PAYBEFRET" id="MCH_PAYBEFRET" value="<?php echo $MCH_VALBEF; ?>" title="Payment Before Retention">
	                          </label>
	                        </td> 
	                        <td align="left" class="style1"><?php //echo $PaymentNext ?></td>
	                        <td align="left" class="style1">
	                        	<input type="hidden" class="form-control" style="max-width:200px; text-align:right;" name="MCH_AKUMNEXTx" id="MCH_AKUMNEXTx" value="<?php echo number_format($MCH_AKUMNEXT, $decFormat); ?>" title="Next Amount" disabled>
	                        	<input type="hidden" class="form-control" style="max-width:200px; text-align:right;" name="MCH_AKUMNEXT" id="MCH_AKUMNEXT" value="<?php echo $MCH_AKUMNEXT; ?>" title="Next Amount">
	                        </tr>
	                      <tr>
	                          <td align="left" class="style1">&nbsp;&nbsp;<?php echo $MCAmount; ?> - PPn</td>
	                          <td align="left" class="style1">:</td>
	                          <td align="left" class="style1">
	                          	<label>
	                            <input type="text" class="form-control" style="max-width:150px; text-align:right;" name="MCH_TOTVALx" id="MCH_TOTVALx" value="<?php echo number_format($MCH_TOTVAL, $decFormat); ?>" title="MCPay. = Next Amount - Amount Before" disabled>
	                            <input type="hidden" class="form-control" style="max-width:200px; text-align:right;" name="MCH_TOTVAL" id="MCH_TOTVAL" value="<?php echo $MCH_TOTVAL; ?>" title="MCPay. = Next Amount - Amount Before">
	                            </label>
	                            <label>
	                            <input type="text" class="form-control" style="max-width:140px; text-align:right;" name="MCH_TOTVAL_PPnx" id="MCH_TOTVAL_PPnx" value="<?php echo number_format($MCH_TOTVAL_PPn, $decFormat); ?>" onBlur="getTOTPlusPPN(this.value)" onKeyPress="return isIntOnlyNew(event);" disabled>
	                            <input type="hidden" class="form-control" style="max-width:155px; text-align:right;" name="MCH_TOTVAL_PPn" id="MCH_TOTVAL_PPn" value="<?php echo $MCH_TOTVAL_PPn; ?>">
	                          </label>
	                          <?php
							  	$MCH_PAYDUE	= $MCH_TOTVAL + $MCH_TOTVAL_PPn;
							  ?>
	                            </td> 
	                          <td align="left" class="style1"><?php echo $Amount; ?>&nbsp;(incl. PPn) </td>
	                          <td align="left" class="style1"  valign="top"><input type="text" class="form-control" style="max-width:200px; text-align:right;" name="MCH_PAYDUE" id="MCH_PAYDUE" value="<?php print number_format($MCH_PAYDUE, $decFormat); ?>" disabled></td>
	                      </tr>
	                      <tr>
	                          <td align="left" class="style1" style="font-weight:bold; color:#00F">&nbsp;</td>
	                          <td align="left" class="style1">&nbsp;</td>
	                          <td align="left" class="style1" style="font-weight:bold; color:#FFF"><hr></td> 
	                          <td align="left" class="style1">PPh</td>
	                          <td align="left" class="style1">
	                              <input type="text" class="form-control" style="max-width:200px; text-align:right;" name="MCH_TOTVAL_PPhx" id="MCH_TOTVAL_PPhx" value="<?php echo number_format($MCH_TOTVAL_PPh, $decFormat); ?>" onBlur="getTOTPlusPPH(this.value)" onKeyPress="return isIntOnlyNew(event);">
	                              <input type="hidden" class="form-control" style="max-width:155px; text-align:right;" name="MCH_TOTVAL_PPh" id="MCH_TOTVAL_PPh" value="<?php echo $MCH_TOTVAL_PPh; ?>" title="Payment Before Retention">
	                          </td>
	                          <!-- MCH_PROG, MCH_PROG1 -->
	                      </tr>
	                      <?php
						  	$TOTPAYMENT	= $MCH_TOTVAL - $MCH_TOTVAL_PPh;
						  ?>
	                      <tr>
	                          <td align="left" class="style1" style="font-weight:bold; color:#00F">&nbsp;&nbsp;<?php echo $ReceiptAmount; ?> (Aft. PPh)</td>
	                          <td align="left" class="style1">:</td>
	                          <td align="left" class="style1" style="font-weight:bold; color:#FFF">
	                          	<input type="text" class="form-control" style="max-width:200px; text-align:right; background-color:#0C6;" name="TOTPAYMENT" id="TOTPAYMENT" value="<?php print number_format($TOTPAYMENT, $decFormat); ?>" disabled></td> 
	                          <td align="left" class="style1">&nbsp;</td>
	                          <td align="left" class="style1">&nbsp;</td>
	                          <!-- MCH_PROG, MCH_PROG1 -->
	                      </tr>
	                      <script>
								function getTOTPlusPPH(PPHValue)
								{
									var decFormat		= document.getElementById('decFormat').value;
									var MCH_PAYDUE		= document.getElementById('MCH_PAYDUE').value.split(",").join("");
									var MCH_TOTVAL_PPh	= PPHValue.split(",").join("");
									
									MCH_PAYDUEPPh		= parseFloat(MCH_TOTVAL_PPh);	
									
									document.getElementById('MCH_TOTVAL_PPh').value	= MCH_PAYDUEPPh;
									document.getElementById('MCH_TOTVAL_PPhx').value	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MCH_PAYDUEPPh)),decFormat));
									
									TOTPAYMENT		= parseFloat(MCH_PAYDUE) - parseFloat(MCH_PAYDUEPPh);
									document.getElementById('TOTPAYMENT').value = doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(TOTPAYMENT)),decFormat));
								}
								
	                            function getTOTDUE(thisVal) // HIDDEN BY DH ON 13 JULI 2019
	                            {
	                                /*var decFormat	= document.getElementById('decFormat').value;
	                                var thisVal		= eval(thisVal).value.split(",").join("");
	                                MCH_DPBACK		= thisVal;
	                                document.getElementById('MCH_DPBACK').value 	= MCH_DPBACK;
	                                document.getElementById('MCH_DPBACK1').value = doDecimalFormatxx(RoundNDecimal(parseFloat(Math.round(MCH_DPBACK)),decFormat));
	                                MCH_TOTPROGRESS	= document.getElementById('MCH_TOTPROGRESS').value;
	                                MCH_RETCUT		= document.getElementById('MCH_RETCUT').value;
	                                MCH_DPVAL		= document.getElementById('MCH_DPVAL').value;
	                                
	                                MCH_PAYBEFRET		= Math.round(parseFloat(MCH_TOTPROGRESS) +  parseFloat(MCH_DPVAL) -  parseFloat(MCH_DPBACK) - parseFloat(MCH_RETCUT));
	                                document.getElementById('MCH_PAYBEFRET').value 	= MCH_PAYBEFRET;	// JUMLAH PEMBAYARAN SEBELUM RETENSI
	                            	document.getElementById('MCH_PAYBEFRETx').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MCH_PAYBEFRET)),decFormat));
									
									MCH_PAYAKUM			= Math.round(parseFloat(MCH_PAYBEFRET));
									document.getElementById('MCH_PAYAKUM').value 	= MCH_PAYAKUM;	// JUMLAH AKUMULASI PEMBAYARAN SAMPAI SAAT INI
									document.getElementById('MCH_AKUMNEXT').value 	= MCH_PAYAKUM;	// DIGUNAKAN UNTUK PEMBUATAN MC SELANJUTNYA
									document.getElementById('MCH_AKUMNEXTx').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.round(MCH_PAYAKUM)),decFormat));
									MCH_VALBEF			= document.getElementById('MCH_VALBEF').value;
									
									MCH_PAYMENT			= Math.round(MCH_PAYAKUM - MCH_VALBEF);
									document.getElementById('MCH_PAYMENT').value 	= MCH_PAYMENT;	// YANG HARUS DIBAYAR
									document.getElementById('MCH_PAYMENTx').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MCH_PAYMENT)),decFormat));
									
									MCH_TOTVAL_PPn		= Math.round(0.1 * MCH_PAYMENT);							
									document.getElementById('MCH_TOTVAL_PPn').value	= MCH_TOTVAL_PPn;
									document.getElementById('MCH_TOTVAL_PPnx').value	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MCH_TOTVAL_PPn)),decFormat));
									
									MCH_PAYDUE			= parseFloat(MCH_PAYMENT) + parseFloat(MCH_TOTVAL_PPn);							
									
									document.getElementById('MCH_PAYDUE').value = doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MCH_PAYDUE)),decFormat));
									MCH_PAYDUEPPh		= Math.round(0.03 * MCH_PAYMENT);													
									document.getElementById('MCH_TOTVAL_PPh').value	= MCH_PAYDUEPPh;
									document.getElementById('MCH_TOTVAL_PPhx').value	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MCH_PAYDUEPPh)),decFormat));
									
									TOTPAYMENT		= parseFloat(MCH_PAYDUE - MCH_PAYDUEPPh);
									document.getElementById('TOTPAYMENT').value = doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(TOTPAYMENT)),decFormat));*/
	                            }
	                        </script>
	                      <tr>
	                          <td align="left" class="style1" valign="top">&nbsp;&nbsp;<?php echo $Notes ?> </td>
	                          <td align="left" class="style1" valign="top">:</td>
	                          <td align="left" class="style1">
	                            <textarea name="MCH_NOTES" class="form-control" id="MCH_NOTES" cols="30" style="height:50px"><?php echo $MCH_NOTES; ?></textarea></td> 
	                          <td align="left" class="style1"  valign="top">&nbsp;</td>
	                          <td align="left" class="style1"  valign="top">&nbsp;</td>
	                      </tr>
	                      <tr>
	                          <td align="left" valign="middle" class="style1">&nbsp;&nbsp;<?php echo $Status; ?></td>
	                          <td align="left" valign="middle" class="style1">:</td>
	                          <td colspan="3" align="left" valign="middle" class="style1">
	                            <input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $MCH_STAT; ?>">
	                            <?php
	                                if($ISAPPROVE == 1)
	                                {
										// START : FOR ALL APPROVAL FUNCTION								
											if($disableAll == 0)
											{
												if($canApprove == 1)
												{
													$disButton	= 0;
													$sqlCAPPHE	= "tbl_approve_hist WHERE AH_CODE = '$MCH_CODE' AND AH_APPROVER = '$DefEmp_ID'";
													$resCAPPHE	= $this->db->count_all($sqlCAPPHE);
													if($resCAPPHE > 0)
														$disButton	= 1;
													?>
														<select name="MCH_STAT" id="MCH_STAT" class="form-control" style="max-width:100px" onChange="selStat(this.value)" <?php if($disButton == 1) { ?> disabled <?php } ?> >
															<option value="0"> -- </option>
	                                                        <option value="1"<?php if($MCH_STAT == 1) { ?> selected <?php } ?>>New</option>
	                                                        <option value="2"<?php if($MCH_STAT == 2) { ?> selected <?php } ?> style="display:none">Confirm</option>
															<option value="3"<?php if($MCH_STAT == 3) { ?> selected <?php } ?>>Approved</option>
															<option value="4"<?php if($MCH_STAT == 4) { ?> selected <?php } ?>>Revised</option>
															<option value="5"<?php if($MCH_STAT == 5) { ?> selected <?php } ?>>Rejected</option>
															<option value="6"<?php if($MCH_STAT == 6) { ?> selected <?php } ?>>Closed</option>
															<option value="7"<?php if($MCH_STAT == 7) { ?> selected <?php } ?> style="display:none">Awaiting</option>
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
	                                    <select name="MCH_STAT" id="MCH_STAT" class="form-control" style="max-width:110px" onChange="selStat(this.value)">
	                                    <option value="1"<?php if($MCH_STAT == 1) { ?> selected <?php } ?>>New</option>
	                                    <option value="2"<?php if($MCH_STAT == 2) { ?> selected <?php } ?> style="display:none">Confirm</option>
	                                    <option value="3"<?php if($MCH_STAT == 3) { ?> selected <?php } ?>>Approved</option>
	                                    <option value="4"<?php if($MCH_STAT == 4) { ?> selected <?php } ?>>Revised</option>
	                                    <option value="5"<?php if($MCH_STAT == 5) { ?> selected <?php } ?>>Rejected</option>
	                                    <option value="6"<?php if($MCH_STAT == 6) { ?> selected <?php } ?>>Closed</option>
	                                    <option value="7"<?php if($MCH_STAT == 7) { ?> selected <?php } ?> disabled>Awaiting</option>
	                                    </select>
	                                <?php
	                                }
	                            ?>
	                          </td>
	                        </tr> 
	                      <tr>
	                          <td align="left" class="style1">&nbsp;</td>
	                          <td align="left" class="style1">&nbsp;</td>
	                          <td align="left" class="style1">&nbsp;</td>
	                          <td align="left" class="style1">&nbsp;</td>
	                          <td align="left" class="style1">&nbsp;</td>
	                      </tr> 
						<?php
	                        $theProjCode 	= $PRJCODE;
	                        $url_AddMC		= site_url('c_project/c_mc90up180c2c/pall180c2emc/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
						?>
	                      <tr>
	                          <td align="left" class="style1">&nbsp;</td>
	                          <td align="left" class="style1">&nbsp;</td>
	                          <td align="left" class="style1">    
	                            <script>
	                                var url = "<?php echo $url_AddMC;?>";
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
	                            <i class="glyphicon glyphicon-th-list"></i>&nbsp;&nbsp;<?php echo $AddMC; ?>
	                            </button>
	                          </td>
	                          <td align="left" class="style1">&nbsp;</td>
	                          <td align="left" class="style1">&nbsp;</td>
	                      </tr>  
	                      <tr>
	                          <td colspan="5" align="left" class="style1">
	                            <div class="row">
	                                <div class="col-md-12">
	                                  <div class="box box-primary">
	                                    <br>
	                                    <table width="100%" border="1" id="tbl" >
	                                    	<tr style="background:#CCCCCC">
	                                            <th width="2%" style="text-align:center" nowrap>&nbsp;</th>
	                                            <th width="10%" style="text-align:center" nowrap><?php echo $Code; ?> </th>
	                                            <th width="7%" style="text-align:center" nowrap><?php echo $Date; ?> </th>
	                                            <th width="2%" style="text-align:center" nowrap><?php echo $MCStep; ?> </th>
	                                            <th width="32%" style="text-align:center" nowrap><?php echo $Description; ?></th>
	                                            <th width="8%" style="text-align:center" nowrap><?php echo $Progress; ?><br>%</th>
	                                            <th width="9%" style="text-align:center" nowrap><?php echo $ProgressAmmount; ?></th>
	                                            <th width="3%" style="text-align:center" nowrap><?php echo $Amount; ?>&nbsp;</th>
	                                            <th style="text-align:center" nowrap>PPn </th>
	                                            <th style="text-align:center" nowrap>PPh</th>
	                                            <th width="12%" nowrap style="text-align:center"><?php echo $InvAmount; ?></th>
	                                        </tr>
											<?php					
	                                            if($task == 'edit')
	                                            {
	                                                $sqlDET		= "SELECT A.*
	                                                                FROM tbl_mcg_detail A
	                                                                INNER JOIN tbl_mcg_header B ON A.MCH_CODE = B.MCH_CODE
	                                                                WHERE A.MCH_CODE = '$MCH_CODE' 
	                                                                    AND B.PRJCODE = '$PRJCODE'
	                                                                ORDER BY A.MCH_CODE ASC";
	                                                $result 	= $this->db->query($sqlDET)->result();
	                                                $i 	= 0;
	                                                $j	= 0;
	                                                
													$totRow	= 0;
													foreach($result as $row) :
														$currentRow  	= ++$i;
														$MCH_CODE		= $row->MCH_CODE;
														$MCH_MANNO		= $row->MCH_MANNO;
														$MC_CODE		= $row->MC_CODE;
														$MC_MANNO		= $row->MC_MANNO;	
														if($MC_MANNO == '' || $MC_MANNO == 0)
															$MC_MANNO	= $MC_CODE;
															
														$MC_STEP		= $row->MC_STEP;
														$PRJCODE		= $row->PRJCODE;
														$MC_OWNER		= $row->MC_OWNER;
														$MC_DATE		= $row->MC_DATE;
														$MC_ENDDATE		= $row->MC_ENDDATE;
														$MC_PROG		= $row->MC_PROG;
														$MC_PROGVAL		= $row->MC_PROGVAL;
														$MC_PROGCUR		= $row->MC_PROGCUR;
														$MC_PROGCURVAL	= $row->MC_PROGCURVAL;
														$MC_DPPER		= $row->MC_DPPER;
														$MC_DPVAL		= $row->MC_DPVAL;
														$MC_DPBACK		= $row->MC_DPBACK;
														$MC_DPBACKCUR	= $row->MC_DPBACKCUR;
														$MC_RETCUTP		= $row->MC_RETCUTP;
														$MC_RETCUT		= $row->MC_RETCUT;
														$MC_RETCUTCUR	= $row->MC_RETCUTCUR;
														$MC_PROGAPP		= $row->MC_PROGAPP;
														$MC_PROGAPPVAL	= $row->MC_PROGAPPVAL;
														$MC_TOTVAL		= $row->MC_TOTVAL;
														$MC_TOTVAL_PPn	= $row->MC_TOTVAL_PPn;
														$MC_TOTVAL_PPh	= $row->MC_TOTVAL_PPh;
														$MC_GTOTVAL		= $row->MC_GTOTVAL;
														$MC_NOTES		= $row->MC_NOTES;
														$MC_STAT		= $row->MC_STAT;
														$MC_VALBEF		= $row->MC_VALBEF;
														//$MC_GTOTVAL		= $MC_TOTVAL + $MC_TOTVAL_PPn - $MC_TOTVAL_PPh;
														$MC_GTOTVAL		= $MC_TOTVAL - $MC_TOTVAL_PPh;
														
														if($MC_STAT == 0)
														{
															$MC_STATDes = "fake";
															$STATCOL	= 'danger';
														}
														elseif($MC_STAT == 1)
														{
															$MC_STATDes = "New";
															$STATCOL	= 'warning';
														}
														elseif($MC_STAT == 2)
														{
															$MC_STATDes = "Confirm";
															$STATCOL	= 'primary';
														}
														elseif($MC_STAT == 3)
														{
															$MC_STATDes = "Approve";
															$STATCOL	= 'success';
														}
														elseif($MC_STAT == 4)
														{
															$MC_STATDes = "Revise";
															$STATCOL	= 'warning';
														}
														elseif($MC_STAT == 5)
														{
															$MC_STATDes = "Rejected";
															$STATCOL	= 'danger';
														}
														elseif($MC_STAT == 6)
														{
															$MC_STATDes = "Close";
															$STATCOL	= 'primary';
														}
														elseif($MC_STAT == 7)
														{
															$MC_STATDes = "Waiting";
															$STATCOL	= 'warning';
														}
														$totRow			= $totRow + 1;
											
														?>
														<tr>
															<td style="text-align:center">
															<?php
																if($MCH_STAT == 1)
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
															</td>
															<td nowrap>
																<?php echo $MC_MANNO; ?>
	                                                        	<input type="hidden" id="data<?php echo $currentRow; ?>MC_CODE" name="data[<?php echo $currentRow; ?>][MC_CODE]" value="<?php echo $MC_CODE; ?>" width="10" size="15" readonly class="textbox"><input type="hidden" id="data<?php echo $currentRow; ?>MC_MANNO" name="data[<?php echo $currentRow; ?>][MC_MANNO]" value="<?php echo $MC_MANNO; ?>" width="10" size="15" readonly class="textbox">
	                                                        </td>
															<td style="text-align:center" nowrap>
																<?php echo $MC_DATE; ?>
	                                                            <input type="hidden" id="data<?php echo $currentRow; ?>MC_DATE" name="data[<?php echo $currentRow; ?>][MC_DATE]" value="<?php echo $MC_DATE; ?>" width="10" size="15" readonly class="textbox">
	                                                        </td>
															<td nowrap style="text-align:center">
	                                                        	<?php echo $MC_STEP; ?>
	                                                        	<input type="hidden" id="data<?php echo $currentRow; ?>MC_STEP" name="data[<?php echo $currentRow; ?>][MC_STEP]" value="<?php echo $MC_STEP; ?>" width="10" size="15" readonly class="textbox">
	                                                        </td>
															<td nowrap>
																<?php echo $MC_NOTES; ?>
	                                                            <input type="hidden" id="data<?php echo $currentRow; ?>MC_NOTES" name="data[<?php echo $currentRow; ?>][MC_NOTES]" value="<?php echo $MC_NOTES; ?>" width="10" size="15" readonly class="textbox">
	                                                        </td>
															<td style="text-align:right" nowrap>
	                                                        	<?php echo number_format($MC_PROG, 5); ?>&nbsp;
	                                                        	<input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][MC_PROG]" id="data<?php echo $currentRow; ?>MC_PROG" size="10" value="<?php echo $MC_PROG; ?>" class="textbox" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,<?php echo $currentRow; ?>);" >
	                                                            <input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][MC_PROG]" id="data<?php echo $currentRow; ?>MC_PROG" size="10" value="<?php echo $MC_PROG; ?>" class="textbox" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,<?php echo $currentRow; ?>);" >
	                                                            <input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][MC_PROGVAL]" id="data<?php echo $currentRow; ?>MC_PROGVAL" size="10" value="<?php echo $MC_PROGVAL; ?>" class="textbox" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,<?php echo $currentRow; ?>);" >
	                                                            <input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][MC_PROGCUR]" id="data<?php echo $currentRow; ?>MC_PROGCUR" size="10" value="<?php echo $MC_PROGCUR; ?>" class="textbox" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,<?php echo $currentRow; ?>);" >
	                                                            <input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][MC_PROGCURVAL]" id="data<?php echo $currentRow; ?>MC_PROGCURVAL" size="10" value="<?php echo $MC_PROGCURVAL; ?>" class="textbox" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,<?php echo $currentRow; ?>);" >
	                                                            <input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][MC_DPPER]" id="data<?php echo $currentRow; ?>MC_DPPER" size="10" value="<?php echo $MC_DPPER; ?>" class="textbox" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,<?php echo $currentRow; ?>);" >
	                                                            <input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][MC_DPVAL]" id="data<?php echo $currentRow; ?>MC_DPVAL" size="10" value="<?php echo $MC_DPVAL; ?>" class="textbox" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,<?php echo $currentRow; ?>);" >
	                                                            <input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][MC_DPBACK]" id="data<?php echo $currentRow; ?>MC_DPBACK" size="10" value="<?php echo $MC_DPBACK; ?>" class="textbox" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,<?php echo $currentRow; ?>);" >
	                                                            <input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][MC_DPBACKCUR]" id="data<?php echo $currentRow; ?>MC_DPBACKCUR" size="10" value="<?php echo $MC_DPBACKCUR; ?>" class="textbox" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,<?php echo $currentRow; ?>);" >
	                                                            <input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][MC_RETCUTP]" id="data<?php echo $currentRow; ?>MC_RETCUTP" size="10" value="<?php echo $MC_RETCUTP; ?>" class="textbox" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,<?php echo $currentRow; ?>);" >
	                                                            <input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][MC_RETCUT]" id="data<?php echo $currentRow; ?>MC_RETCUT" size="10" value="<?php echo $MC_RETCUT; ?>" class="textbox" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,<?php echo $currentRow; ?>);" >
	                                                            <input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][MC_RETCUTCUR]" id="data<?php echo $currentRow; ?>MC_RETCUTCUR" size="10" value="<?php echo $MC_RETCUTCUR; ?>" class="textbox" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,<?php echo $currentRow; ?>);" >
	                                                        </td>
															<td style="text-align:right" nowrap>
	                                                        	<?php echo number_format($MC_PROGAPPVAL, $decFormat); ?>&nbsp;
	                                                            <input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][MC_PROGAPP]" id="data<?php echo $currentRow; ?>MC_PROGAPP" size="10" value="<?php echo $MC_PROGAPP; ?>" class="textbox" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,<?php echo $currentRow; ?>);" >
	                                                            <input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][MC_PROGAPPVAL]" id="data<?php echo $currentRow; ?>MC_PROGAPPVAL" size="10" value="<?php echo $MC_PROGAPPVAL; ?>" class="textbox" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,<?php echo $currentRow; ?>);" >
	                                                        </td>
															<td nowrap style="text-align:right">
																<?php echo number_format($MC_TOTVAL, $decFormat); ?>&nbsp;
	                                                            <input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][MC_TOTVAL]" id="data<?php echo $currentRow; ?>MC_TOTVAL" size="10" value="<?php echo $MC_TOTVAL; ?>" class="textbox" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,<?php echo $currentRow; ?>);" >
	                                                        </td>
															<td width="7%" nowrap style="text-align:right">
																<?php echo number_format($MC_TOTVAL_PPn, $decFormat); ?>&nbsp;
	                                                        	<input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][MC_TOTVAL_PPn]" id="data<?php echo $currentRow; ?>MC_TOTVAL_PPn" size="10" value="<?php echo $MC_TOTVAL_PPn; ?>" class="textbox" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,<?php echo $currentRow; ?>);" >
	                                                        </td>
															<td width="8%" nowrap style="text-align:right">
																<?php echo number_format($MC_TOTVAL_PPh, $decFormat); ?>&nbsp;
	                                                            <input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][MC_TOTVAL_PPh]" id="data<?php echo $currentRow; ?>MC_TOTVAL_PPh" size="10" value="<?php echo $MC_TOTVAL_PPh; ?>" class="textbox" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,<?php echo $currentRow; ?>);" >
	                                                        </td>
															<td nowrap style="text-align:right">
																<?php echo number_format($MC_GTOTVAL, $decFormat); ?>&nbsp;
	                                                            <input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][MC_GTOTVAL]" id="data<?php echo $currentRow; ?>MC_GTOTVAL" size="10" value="<?php echo $MC_GTOTVAL; ?>" class="textbox" onKeyPress="return isIntOnlyNew(event);" >
	                                                        </td>
														</tr>
														<?php
													endforeach;
	                                            }
	                                            ?>
	                                            <input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">
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
										if(($MCH_STAT == 2 || $MCH_STAT == 7) && $canApprove == 1)
										{
											?>
												<button class="btn btn-primary" >
												<i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Update; ?>
												</button>&nbsp;
											<?php
										}
										elseif($MCH_STAT == 1 || $MCH_STAT == 4)
										{
	                                            ?>
	                                                <button type="button" class="btn btn-primary" onClick="submitForm(1);">
	                                                <i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Save; ?>
	                                                </button>&nbsp;
	                                            <?php
										}
									}
								   echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i>&nbsp;&nbsp;'.$Back.'</button>');
								?>        	</td>
	                      </tr>
	                      <tr>
	                        <td colspan="5" align="left" class="style1">&nbsp;</td>
	                      </tr>
	                  </table>
	              	</form>
              	</div>
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
  	
	var MC_TOTNPPNA	= 0;
	function add_item(strItem) 
	{
		var decFormat	= document.getElementById('decFormat').value;
		arrItem = strItem.split('|');
		
		var objTable, objTR, objTD, intIndex, arrItem;
		var MCH_CODE 	= "<?php echo $MCH_CODE; ?>";
		var MCH_MANNO 	= "<?php echo $MCH_MANNO; ?>";
		
		ilvl = arrItem[1];
		
		MC_CODE 		= arrItem[0];
		MC_MANNO 		= arrItem[1];			
		MC_DATE 		= arrItem[2];
		MC_STEP			= arrItem[3];
		MC_PROG 		= arrItem[4];
		MC_PROGVAL 		= arrItem[5];
		MC_PROGAPP 		= arrItem[6];
		MC_PROGAPPVAL 	= arrItem[7];
		MC_VALADD 		= arrItem[8];
		MC_MATVAL 		= arrItem[9];
		MC_DPPER 		= arrItem[10];
		MC_DPBACK 		= arrItem[11];
		MC_RETCUT 		= arrItem[12];
		MC_AKUMNEXT 	= arrItem[13];
		MC_VALBEF 		= arrItem[14];
		MC_TOTVAL 		= arrItem[15];
		MC_TOTVAL_PPn 	= arrItem[16];
		MC_TOTVAL_PPh 	= arrItem[17];
		MC_NOTES 		= arrItem[18];
		MC_GTOTVAL 		= arrItem[19];
		MC_TOTNPPN 		= arrItem[20];
		MC_TOTNPPNA		= parseFloat(MC_TOTNPPNA) + parseFloat(MC_TOTNPPN);
		MC_PROGCUR 		= arrItem[21];
		MC_PROGCURVAL 	= arrItem[22];
		MC_DPBACKCUR 	= arrItem[23];
		MC_RETCUTP		= arrItem[24];
		MC_RETCUTCUR	= arrItem[25];
		MC_DPVAL		= arrItem[26];
		
		/*validateDouble(SI_CODE)
		if(validateDouble(SI_CODE))
		{
			swal("Double Item for " + SI_CODE);
			return;
		}*/
		
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
		objTD.innerHTML = '<a href="#" onClick="deleteRow('+intIndex+')" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a><input type="hidden" id="chk" name="chk" value="'+intIndex+'" width="10" size="15" readonly class="textbox">';
		
		// MCH_MANNO 
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'left';
		objTD.noWrap = true;
		objTD.innerHTML = ''+MC_CODE+'<input type="hidden" id="data'+intIndex+'MC_CODE" name="data['+intIndex+'][MC_CODE]" value="'+MC_CODE+'" width="10" size="15" readonly class="textbox"><input type="hidden" id="data'+intIndex+'MC_MANNO" name="data['+intIndex+'][MC_MANNO]" value="'+MC_MANNO+'" width="10" size="15" readonly class="textbox">';
		
		// MC_DATE
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'left';
		objTD.innerHTML = ''+MC_DATE+'<input type="hidden" id="data'+intIndex+'MC_DATE" name="data['+intIndex+'][MC_DATE]" value="'+MC_DATE+'" width="10" size="15" readonly class="textbox">';
		
		// MC_STEP
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = ''+MC_STEP+'<input type="hidden" id="data'+intIndex+'MC_STEP" name="data['+intIndex+'][MC_STEP]" value="'+MC_STEP+'" width="10" size="15" readonly class="textbox">';
		
		// MC_NOTES
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = ''+MC_NOTES+'<input type="hidden" id="data'+intIndex+'MC_NOTES" name="data['+intIndex+'][MC_NOTES]" value="'+MC_NOTES+'" width="10" size="15" readonly class="textbox">';
		
		// MC_PROG
		var MC_PROG1	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_PROG)),5));
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = ''+MC_PROG1+'<input type="hidden" style="text-align:right" name="data['+intIndex+'][MC_PROG]" id="data'+intIndex+'MC_PROG" size="10" value="'+MC_PROG+'" class="textbox" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,'+intIndex+');" ><input type="hidden" style="text-align:right" name="data['+intIndex+'][MC_PROGVAL]" id="data'+intIndex+'MC_PROGVAL" size="10" value="'+MC_PROGVAL+'" class="textbox" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,'+intIndex+');" ><input type="hidden" style="text-align:right" name="data['+intIndex+'][MC_PROGCUR]" id="data'+intIndex+'MC_PROGCUR" size="10" value="'+MC_PROGCUR+'" class="textbox" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,'+intIndex+');" ><input type="hidden" style="text-align:right" name="data['+intIndex+'][MC_PROGCURVAL]" id="data'+intIndex+'MC_PROGCURVAL" size="10" value="'+MC_PROGCURVAL+'" class="textbox" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,'+intIndex+');" ><input type="hidden" style="text-align:right" name="data['+intIndex+'][MC_DPPER]" id="data'+intIndex+'MC_DPPER" size="10" value="'+MC_DPPER+'" class="textbox" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,'+intIndex+');" ><input type="hidden" style="text-align:right" name="data['+intIndex+'][MC_DPVAL]" id="data'+intIndex+'MC_DPVAL" size="10" value="'+MC_DPVAL+'" class="textbox" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,'+intIndex+');" ><input type="hidden" style="text-align:right" name="data['+intIndex+'][MC_DPBACK]" id="data'+intIndex+'MC_DPBACK" size="10" value="'+MC_DPBACK+'" class="textbox" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,'+intIndex+');" ><input type="hidden" style="text-align:right" name="data['+intIndex+'][MC_DPBACKCUR]" id="data'+intIndex+'MC_DPBACKCUR" size="10" value="'+MC_DPBACKCUR+'" class="textbox" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,'+intIndex+');" ><input type="hidden" style="text-align:right" name="data['+intIndex+'][MC_RETCUTP]" id="data'+intIndex+'MC_RETCUTP" size="10" value="'+MC_RETCUTP+'" class="textbox" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,'+intIndex+');" ><input type="hidden" style="text-align:right" name="data['+intIndex+'][MC_RETCUT]" id="data'+intIndex+'MC_RETCUT" size="10" value="'+MC_RETCUT+'" class="textbox" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,'+intIndex+');" ><input type="hidden" style="text-align:right" name="data['+intIndex+'][MC_RETCUTCUR]" id="data'+intIndex+'MC_RETCUTCUR" size="10" value="'+MC_RETCUTCUR+'" class="textbox" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,'+intIndex+');" >';
		
		
		
		/*objTD.innerHTML = ''+MC_PROG1+'<input type="hidden" style="text-align:right" name="data['+intIndex+'][MC_PROG]" id="data'+intIndex+'MC_PROG" size="10" value="'+MC_PROG+'" class="textbox" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,'+intIndex+');" ><input type="hidden" style="text-align:right" name="data['+intIndex+'][MC_PROGVAL]" id="data'+intIndex+'MC_PROGVAL" size="10" value="'+MC_PROGVAL+'" class="textbox" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,'+intIndex+');" ><input type="hidden" style="text-align:right" name="data['+intIndex+'][MC_PROGCUR]" id="data'+intIndex+'MC_PROGCUR" size="10" value="'+MC_PROGCUR+'" class="textbox" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,'+intIndex+');" ><input type="hidden" style="text-align:right" name="data['+intIndex+'][MC_PROGCURVAL]" id="data'+intIndex+'MC_PROGCURVAL" size="10" value="'+MC_PROGCURVAL+'" class="textbox" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,'+intIndex+');" ><input type="hidden" style="text-align:right" name="data['+intIndex+'][MC_DPPER]" id="data'+intIndex+'MC_DPPER" size="10" value="'+MC_DPPER+'" class="textbox" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,'+intIndex+');" ><input type="hidden" style="text-align:right" name="data['+intIndex+'][MC_DPVAL]" id="data'+intIndex+'MC_DPVAL" size="10" value="'+MC_DPVAL+'" class="textbox" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,'+intIndex+');" ><input type="hidden" style="text-align:right" name="data['+intIndex+'][MC_DPBACK]" id="data'+intIndex+'MC_DPBACK" size="10" value="'+MC_DPBACK+'" class="textbox" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,'+intIndex+');" ><input type="hidden" style="text-align:right" name="data['+intIndex+'][MC_DPBACKCUR]" id="data'+intIndex+'MC_DPBACKCUR" size="10" value="'+MC_DPBACKCUR+'" class="textbox" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,'+intIndex+');" ><input type="hidden" style="text-align:right" name="data['+intIndex+'][MC_RETCUTP]" id="data'+intIndex+'MC_RETCUTP" size="10" value="'+MC_RETCUTP+'" class="textbox" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,'+intIndex+');" ><input type="hidden" style="text-align:right" name="data['+intIndex+'][MC_RETCUT]" id="data'+intIndex+'MC_RETCUT" size="10" value="'+MC_RETCUT+'" class="textbox" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,'+intIndex+');" ><input type="hidden" style="text-align:right" name="data['+intIndex+'][MC_RETCUTCUR]" id="data'+intIndex+'MC_RETCUTCUR" size="10" value="'+MC_RETCUTCUR+'" class="textbox" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,'+intIndex+');" >';*/
		
		// MC_PROGAPPVAL
		var MC_PROGAPPVAL1	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_PROGAPPVAL)),2));
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = ''+MC_PROGAPPVAL1+'<input type="hidden" style="text-align:right" name="data['+intIndex+'][MC_PROGAPP]" id="data'+intIndex+'MC_PROGAPP" size="10" value="'+MC_PROGAPP+'" class="textbox" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,'+intIndex+');" ><input type="hidden" style="text-align:right" name="data['+intIndex+'][MC_PROGAPPVAL]" id="data'+intIndex+'MC_PROGAPPVAL" size="10" value="'+MC_PROGAPPVAL+'" class="textbox" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,'+intIndex+');" >';
		
		// MC_TOTVAL
		var MC_TOTVAL1	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_TOTVAL)),2));
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = ''+MC_TOTVAL1+'<input type="hidden" style="text-align:right" name="data['+intIndex+'][MC_TOTVAL]" id="data'+intIndex+'MC_TOTVAL" size="10" value="'+MC_TOTVAL+'" class="textbox" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,'+intIndex+');" >';
		
		// MC_TOTVAL_PPn
		var MC_TOTVAL_PPn1	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_TOTVAL_PPn)),2));
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = ''+MC_TOTVAL_PPn1+'<input type="hidden" style="text-align:right" name="data['+intIndex+'][MC_TOTVAL_PPn]" id="data'+intIndex+'MC_TOTVAL_PPn" size="10" value="'+MC_TOTVAL_PPn+'" class="textbox" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,'+intIndex+');" >';
		
		// MC_TOTVAL_PPh
		var MC_TOTVAL_PPh1	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_TOTVAL_PPh)),2));
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = ''+MC_TOTVAL_PPh1+'<input type="hidden" style="text-align:right" name="data['+intIndex+'][MC_TOTVAL_PPh]" id="data'+intIndex+'MC_TOTVAL_PPh" size="10" value="'+MC_TOTVAL_PPh+'" class="textbox" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,'+intIndex+');" >';
		
		// MC_GTOTVAL
		var MC_GTOTVAL1		= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_GTOTVAL)), 2))
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = ''+MC_GTOTVAL1+'<input type="hidden" style="text-align:right" name="data'+intIndex+'MC_AKUMNEXT1" id="data'+intIndex+'MC_AKUMNEXT1" size="10" value="'+MC_AKUMNEXT+'" class="textbox" onKeyPress="return isIntOnlyNew(event);" ><input type="hidden" style="text-align:right" name="data['+intIndex+'][MC_GTOTVAL]" id="data'+intIndex+'MC_GTOTVAL" size="10" value="'+MC_GTOTVAL+'" class="textbox" onKeyPress="return isIntOnlyNew(event);" >';
		
		document.getElementById('totalrow').value = intIndex;
		//swal(intIndex)
		if(intIndex > 0)
		{
			MC_PROGBEF	= parseFloat(document.getElementById('MCH_PROGX').value);
			MC_PROGNEXT	= parseFloat(MC_PROG);
			if(MC_PROGNEXT > MC_PROGBEF)
			{
				document.getElementById('MCH_DPBACK').value		= parseFloat(MC_DPBACK);
				document.getElementById('MCH_DPBACKCUR').value	= parseFloat(MC_DPBACKCUR);
				document.getElementById('MCH_DPBACK1').value	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_DPBACK)), 2));
				document.getElementById('MCH_PROG1').value		= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_PROG)),4));
				document.getElementById('MCH_PROGX').value		= parseFloat(MC_PROG);
				document.getElementById('MCH_PROG').value		= parseFloat(MC_PROG);
				document.getElementById('MCH_PROGVAL').value	= parseFloat(MC_PROGVAL);
				document.getElementById('MCH_PROGVAL1').value	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_PROGAPPVAL)), 2));
				document.getElementById('MCH_PROGCUR').value	= parseFloat(MC_PROGCUR);
				document.getElementById('MCH_PROGCURVAL').value	= parseFloat(MC_PROGCURVAL);
				document.getElementById('MCH_PROGAPP').value	= parseFloat(MC_PROGAPP);
				document.getElementById('MCH_PROGAPPx').value	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_PROGAPP)), 4));
				document.getElementById('MCH_PROGAPPVAL').value	= parseFloat(MC_PROGAPPVAL);
				document.getElementById('MCH_PROGAPPVALx').value= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_PROGAPPVAL)), 2));
				document.getElementById('MCH_RETCUTP').value	= parseFloat(MC_RETCUTP);
				document.getElementById('MCH_RETCUT').value		= parseFloat(MC_RETCUT);
				document.getElementById('MCH_RETCUTx').value	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_RETCUT)), 2));
				document.getElementById('MCH_RETCUTCUR').value	= parseFloat(MC_RETCUTCUR);
				document.getElementById('MCH_PAYBEFRET').value	= parseFloat(MC_GTOTVAL);
				document.getElementById('MCH_PAYBEFRETx').value	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_GTOTVAL)), 2));
				document.getElementById('MCH_VALBEF').value		= parseFloat(MC_VALBEF);
				document.getElementById('MCH_VALBEFx').value	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_VALBEF)), 2));
				document.getElementById('MCH_AKUMNEXT').value	= parseFloat(MC_AKUMNEXT);
				document.getElementById('MCH_AKUMNEXTx').value	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_AKUMNEXT)), 2));
				document.getElementById('MCH_PAYDUE').value		= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_TOTNPPNA)), 2));
				document.getElementById('MCH_TOTVAL').value		= parseFloat(MC_TOTVAL);
				document.getElementById('MCH_TOTVALx').value	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_TOTVAL)), 2));
				document.getElementById('MCH_TOTVAL_PPn').value	= parseFloat(MC_TOTVAL_PPn);
				document.getElementById('MCH_TOTVAL_PPnx').value= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_TOTVAL_PPn)), 2));
				document.getElementById('MCH_TOTVAL_PPh').value	= parseFloat(MC_TOTVAL_PPh);
				document.getElementById('MCH_TOTVAL_PPhx').value= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_TOTVAL_PPh)), 2));
				document.getElementById('TOTPAYMENT').value		= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_GTOTVAL)), 2));
			}
			else
			{
				//document.getElementById('MCH_PROG1').value	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_PROGBEF)),4));
				//document.getElementById('MCH_PROG').value	= parseFloat(MC_PROGBEF);
				//document.getElementById('MCH_PROGX').value	= parseFloat(MC_PROGBEF);
			}
		}
		else
		{
			document.getElementById('MCH_DPBACK').value		= parseFloat(MC_DPBACK);
			document.getElementById('MCH_DPBACKCUR').value	= parseFloat(MC_DPBACKCUR);
			document.getElementById('MCH_DPBACK1').value	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_DPBACK)), 2));
			document.getElementById('MCH_PROG1').value		= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_PROG)),4));
			document.getElementById('MCH_PROGX').value		= parseFloat(MC_PROG);
			document.getElementById('MCH_PROG').value		= parseFloat(MC_PROG);
			document.getElementById('MCH_PROGVAL').value	= parseFloat(MC_PROGVAL);
			document.getElementById('MCH_PROGVAL1').value	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_PROGAPPVAL)), 2));
			document.getElementById('MCH_PROGCUR').value	= parseFloat(MC_PROGCUR);
			document.getElementById('MCH_PROGCURVAL').value	= parseFloat(MC_PROGCURVAL);
			document.getElementById('MCH_PROGAPP').value	= parseFloat(MC_PROGAPP);
			document.getElementById('MCH_PROGAPPx').value	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_PROGAPP)), 4));
			document.getElementById('MCH_PROGAPPVAL').value	= parseFloat(MC_PROGAPPVAL);
			document.getElementById('MCH_PROGAPPVALx').value= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_PROGAPPVAL)), 2));
			document.getElementById('MCH_RETCUTP').value	= parseFloat(MC_RETCUTP);
			document.getElementById('MCH_RETCUT').value		= parseFloat(MC_RETCUT);
			document.getElementById('MCH_RETCUTx').value	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_RETCUT)), 2));
			document.getElementById('MCH_RETCUTCUR').value	= parseFloat(MC_RETCUTCUR);
			document.getElementById('MCH_PAYBEFRET').value	= parseFloat(MC_GTOTVAL);
			document.getElementById('MCH_PAYBEFRETx').value	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_GTOTVAL)), 2));
			document.getElementById('MCH_VALBEF').value		= parseFloat(MC_VALBEF);
			document.getElementById('MCH_VALBEFx').value	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_VALBEF)), 2));
			document.getElementById('MCH_AKUMNEXT').value	= parseFloat(MC_AKUMNEXT);
			document.getElementById('MCH_AKUMNEXTx').value	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_AKUMNEXT)), 2));
			document.getElementById('MCH_PAYDUE').value		= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_TOTNPPNA)), 2));
			document.getElementById('MCH_TOTVAL').value		= parseFloat(MC_TOTVAL);
			document.getElementById('MCH_TOTVALx').value	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_TOTVAL)), 2));
			document.getElementById('MCH_TOTVAL_PPn').value	= parseFloat(MC_TOTVAL_PPn);
			document.getElementById('MCH_TOTVAL_PPnx').value= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_TOTVAL_PPn)), 2));
			document.getElementById('MCH_TOTVAL_PPh').value	= parseFloat(MC_TOTVAL_PPh);
			document.getElementById('MCH_TOTVAL_PPhx').value= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_TOTVAL_PPh)), 2));
			document.getElementById('TOTPAYMENT').value		= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_GTOTVAL)), 2));
		}
		getTotalComp()
	}
	
	function getTotalComp()
	{
		var decFormat	= document.getElementById('decFormat').value;
		totRow 			= document.getElementById('totalrow').value;
		
		// MC_PROGCUR
			var MC_PROGCUR	= 0;
			for(i=0;i<=totRow;i++)
			{
				MC_PROGCUR1 	= parseFloat(document.getElementById('data'+i+'MC_PROGCUR').value);
				MC_PROGCUR		= parseFloat(MC_PROGCUR) + parseFloat(MC_PROGCUR1);
			}		
			document.getElementById('MCH_PROGCUR').value 	= MC_PROGCUR;
			
		// MC_RETCUTP
			var MC_RETCUTP	= 0;
			for(i=0;i<=totRow;i++)
			{
				MC_RETCUTP1 	= parseFloat(document.getElementById('data'+i+'MC_RETCUTP').value);
				MC_RETCUTP		= parseFloat(MC_RETCUTP) + parseFloat(MC_RETCUTP1);
			}		
			document.getElementById('MCH_RETCUTP').value 	= MC_RETCUTP;
			
		// MCH_RETCUTCUR
			var MC_RETCUTCUR	= 0;
			for(i=0;i<=totRow;i++)
			{
				MC_RETCUTCUR1 	= parseFloat(document.getElementById('data'+i+'MC_RETCUTCUR').value);
				MC_RETCUTCUR	= parseFloat(MC_RETCUTCUR) + parseFloat(MC_RETCUTCUR1);
			}		
			document.getElementById('MCH_RETCUTCUR').value 	= MC_RETCUTCUR;
			 
		// MC_PROGCURVAL
			var MC_PROGCURVAL	= 0;
			for(i=0;i<=totRow;i++)
			{
				MC_PROGCURVAL1 	= parseFloat(document.getElementById('data'+i+'MC_PROGCURVAL').value);
				MC_PROGCURVAL	= parseFloat(MC_PROGCURVAL) + parseFloat(MC_PROGCURVAL1);
			}		
			document.getElementById('MCH_PROGCURVAL').value 	= MC_PROGCURVAL;
			
		// MC DP BACK
			var MC_DPBACKCUR	= 0;
			for(i=0;i<=totRow;i++)
			{
				MC_DPBACKCUR1 	= parseFloat(document.getElementById('data'+i+'MC_DPBACKCUR').value);
				MC_DPBACKCUR	= parseFloat(MC_DPBACKCUR) + parseFloat(MC_DPBACKCUR1);
			}		
			document.getElementById('MCH_DPBACKCUR').value 	= MC_DPBACKCUR;
		
		// MC TOTAL
			var MC_TOTVALA	= 0;
			for(i=0;i<=totRow;i++)
			{
				MC_TOTVAL1 	= parseFloat(document.getElementById('data'+i+'MC_TOTVAL').value);
				MC_TOTVALA	= parseFloat(MC_TOTVALA) + parseFloat(MC_TOTVAL1);
			}		
			document.getElementById('MCH_TOTVAL').value 	= MC_TOTVALA;	// YANG HARUS DIBAYAR
			document.getElementById('MCH_TOTVALx').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_TOTVALA)),decFormat));
		
		// MC TOTAL PPN
			var MCH_TOTVAL_PPnA	= 0;
			for(i=0;i<=totRow;i++)
			{
				MCH_TOTVAL_PPn1 	= parseFloat(document.getElementById('data'+i+'MC_TOTVAL_PPn').value);
				MCH_TOTVAL_PPnA		= parseFloat(MCH_TOTVAL_PPnA) + parseFloat(MCH_TOTVAL_PPn1);
			}		
			document.getElementById('MCH_TOTVAL_PPn').value 	= MCH_TOTVAL_PPnA;	// YANG HARUS DIBAYAR
			document.getElementById('MCH_TOTVAL_PPnx').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MCH_TOTVAL_PPnA)),decFormat));
			
		// MC TOTAL PPh
			var MCH_TOTVAL_PPhA	= 0;
			for(i=0;i<=totRow;i++)
			{
				MCH_TOTVAL_PPh1 	= parseFloat(document.getElementById('data'+i+'MC_TOTVAL_PPh').value);
				MCH_TOTVAL_PPhA		= parseFloat(MCH_TOTVAL_PPhA) + parseFloat(MCH_TOTVAL_PPh1);
			}		
			document.getElementById('MCH_TOTVAL_PPh').value 	= MCH_TOTVAL_PPhA;	// YANG HARUS DIBAYAR
			document.getElementById('MCH_TOTVAL_PPhx').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MCH_TOTVAL_PPhA)),decFormat));
		
		// MC TOTAL AFTER PPh
			var TOTPAYMENTA	= 0;
			for(i=0;i<=totRow;i++)
			{
				TOTPAYMENT1 	= parseFloat(document.getElementById('data'+i+'MC_GTOTVAL').value);
				TOTPAYMENTA		= parseFloat(TOTPAYMENTA) + parseFloat(TOTPAYMENT1);
			}		
			document.getElementById('TOTPAYMENT').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(TOTPAYMENTA)),decFormat));
		
		
	}
		
	function submitForm(value)
	{
		var FlagUSER	= document.getElementById('FlagUSER').value;
		var ISAPPROVE	= document.getElementById('ISAPPROVE').value;
		var MCH_STEP		= document.getElementById('MCH_STEP').value;
		var MCH_PROG		= document.getElementById('MCH_PROG').value;
		var MCH_STATX	= document.getElementById('MCH_STATX').value;
		
		//if(FlagUSER == 'APPSI')
		if(ISAPPROVE == 1)
		{
			var MCH_MANNO	= document.getElementById('MCH_MANNO').value;
			if(MCH_MANNO == '')
			{
				swal('Please input MC Manual Number.');
				document.getElementById('MCH_MANNO').focus();
				return false;
			}
		}
		
		if(MCH_STEP == 0)
		{
			swal('Please select step of MC.');
			document.getElementById('MCH_STEP').focus();
			return false;
		}
		
		if(MCH_PROG == 0)
		{
			swal('Please input Progress Percentation.');
			document.getElementById('MCH_PROG1').value = '';
			document.getElementById('MCH_PROG1').focus();
			return false;
		}
		
		//if(FlagUSER == 'APPSI')
		if(ISAPPROVE == 1)
		{
			var MCH_PROGAPP	= parseFloat(document.getElementById('MCH_PROGAPP').value);
			if(MCH_PROGAPP == 0)
			{
				swal('Please input Progress Approve Presentation');
				document.getElementById('MCH_PROGAPPx').focus();
				document.getElementById('MCH_PROGAPPx').value = '';
				return false;
			}
		}
		
		if(MCH_STATX == 3)
		{
			swal('The Document has been Approved/Closed. You can not update.')
			return false;
		}
		else
		{
			document.frm.submit();
		}
		//return false;
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
	
	function changeDueDate(thisVal)
	{
		var FDate			= document.getElementById('MCDATEx').value
		var date 			= new Date(FDate);
		//swal(date)
		PINV_TTOTerm		= 14;
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
		
		document.getElementById('MCH_ENDDATE').value 	= formatDate(datey);
		document.getElementById('MCH_ENDDATEx').value	= dateDesc;
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