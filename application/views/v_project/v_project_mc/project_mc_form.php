<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 14 Februari 2017
 * File Name	= project_mc_form.php
 * Location		= -
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$FlagUSER 	= $this->session->userdata['FlagUSER'];
$appBody    = $this->session->userdata('appBody');

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

// _global function
$this->db->select('Display_Rows, decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
$decFormat		= 2;


$currentRow = 0;
if($task == 'add')
{
	$MC_DateY	= date('Y');
	$MC_DateM 	= date('m');
	$MC_DateD 	= date('d');
	$MC_DATE	= "$MC_DateM/$MC_DateD/$MC_DateY";
	$MC_DATEx	= mktime(0,0,0,$MC_DateM,$MC_DateD,$MC_DateY);
	$MC_TTOTerm	= 14;
	$MC_ENDDATE = date("m/d/Y",strtotime("+$MC_TTOTerm days",$MC_DATEx));
}
else
{
	$MC_DATE 		=  $default['MC_DATE'];
	$MC_ENDDATE 	=  $default['MC_DATE'];
}

$PRJCOST1			= 0;
$PRJCOST_PPNP 		= 0;
$sqlPRJ 			= "SELECT ISCHANGE, PRJCOST, PRJCOST_PPNP, PRJCOST2 FROM tbl_project
						WHERE PRJCODE = '$PRJCODE'";
$resultPRJ 			= $this->db->query($sqlPRJ)->result();
foreach($resultPRJ  as $rowPRJ) :
	$ISCHANGE		= $rowPRJ->ISCHANGE;
	$PRJCOST0		= $rowPRJ->PRJCOST;
	$PRJCOST_PPNP	= $rowPRJ->PRJCOST_PPNP;
	$PRJCOST_PPNP2	= $PRJCOST_PPNP/100;
	$PRJCOST2		= $rowPRJ->PRJCOST2;	// Added by SI
	if($ISCHANGE == 1)
	{
		$PRJCOST1= $rowPRJ->PRJCOST2;
	}
	$PRJCOST1 		= $PRJCOST0 + $PRJCOST2;	
	$PRJCOSTnPPn	= round(($PRJCOST1 * $PRJCOST_PPNP2) + $PRJCOST1);
endforeach;

// MENCACRI APAKAH ADA SI INCLUDE TO PROJECT /  CONTRACT
$TOTSI_APPVAL		= 0;
$sqlGetSIC 			= "tbl_siheader WHERE PRJCODE = '$PRJCODE' AND SI_INCCON = 1 AND SI_STAT = 2";
$resGetSIC			= $this->db->count_all($sqlGetSIC);
if($resGetSIC > 0)
{
	$sqlGetSI 			= "SELECT SUM(SI_APPVAL) AS TOTSI_APPVAL FROM tbl_siheader WHERE PRJCODE = '$PRJCODE' AND SI_INCCON = 1 AND SI_STAT = 2";
	$resGetSI			= $this->db->query($sqlGetSI)->result();
	foreach($resGetSI  as $rowSI) :
		$TOTSI_APPVAL	= $rowSI->TOTSI_APPVAL;
	endforeach;
}
$PRJCOST_PPNP2 	= $PRJCOST_PPNP/100;
$PRJCOST		= round($PRJCOST1 + $TOTSI_APPVAL);
$PRJCOSTX		= round($PRJCOST1 + $TOTSI_APPVAL);
$PRJCOSTXnPPn	= round(($PRJCOSTX * $PRJCOST_PPNP2) + $PRJCOSTX);

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
	$myCount = $this->db->count_all('tbl_mcheader');
	
	$sql = "SELECT MAX(PATT_NUMBER) as maxNumber FROM tbl_mcheader WHERE PRJCODE = '$PRJCODE'";
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
	$MC_CODE 		= "$Pattern_Code$PRJCODE$groupPattern-$lastPatternNumb";
	$MC_MANNO		= '';
	$PRJCODE		= $PRJCODE;
	
	$MCCODE			= substr($lastPatternNumb, -4);
	$MCYEAR			= date('y');
	$MCMONTH		= date('m');
	$MCDATED		= date('m');
	$MC_MANNO		= "$Pattern_Code.$MCYEAR.$MCMONTH.$MCDATED.$MCCODE"; // MANUAL CODE
	$GETFROM		= 0;
	$MC_STEP		= 0;
	$BEFSTEP		= 0;
	$sqlMCEXIST		= "tbl_mcheader WHERE PRJCODE = '$PRJCODE' AND MC_STAT = 3";
	$resMCEXIST		= $this->db->count_all($sqlMCEXIST);
	if($resMCEXIST > 0)
	{
		$sqlMAXMC 	= "SELECT MAX(MC_STEP) AS MAXSTEP FROM tbl_mcheader WHERE PRJCODE = '$PRJCODE' AND MC_STAT = 3";
		$resMAXMC 	= $this->db->query($sqlMAXMC)->result();
		foreach($resMAXMC as $rowMAXMC) :
			$MAXSTEP = $rowMAXMC->MAXSTEP;
		endforeach;
		$BEFSTEP	= $MAXSTEP;
		$MAXSTEP	= $MAXSTEP + 1;
		$MC_STEP	= $MAXSTEP;
	}
	$PRJCODE		= $PRJCODE;
	$PRJCOST		= $PRJCOST;
	$MC_OWNER		= $PRJOWN;
	$MC_DATE		= date('m/d/Y');
	$MC_STARTD		= date('m/d/Y');
	$MC_ENDDATE		= date('m/d/Y');
	// plus 14 day
		$MC_ENDDATE0	= mktime(0,0,0,$MC_DateM,$MC_DateD,$MC_DateY);
		$MC_ENDDATE1	= 14;
		$MC_ENDDATE 	= date("m/d/Y",strtotime("+$MC_ENDDATE1 days",$MC_ENDDATE0));
	$MC_CHECKD		= date('m/d/Y');
	
	$MC_PROG		= 0;
	$MC_PROGVAL		= 0;
	$MC_PROGCUR		= 0;
	$MC_PROGCURVAL	= 0;
	$MC_VALADD		= 0;
	// MENCACRI APAKAH ADA SI INCLUDE TO INVOICE
		$sqlGetVARADD		= "tbl_siheader WHERE PRJCODE = '$PRJCODE' AND SI_INCCON = 2 AND SI_STAT = 2";
		$resGetVARADD		= $this->db->count_all($sqlGetVARADD);
		if($resGetVARADD > 0)
		{
			$sqlGetVARVAL	= "SELECT SUM(SI_APPVAL) AS TOTSI_APPVAL FROM tbl_siheader 
								WHERE PRJCODE = '$PRJCODE' AND SI_INCCON = 2 AND SI_STAT = 2";
			$resGetVARVAL	= $this->db->query($sqlGetVARVAL)->result();
			foreach($resGetVARVAL  as $rowVARVAL) :
				$MC_VALADD	= $rowVARVAL->TOTSI_APPVAL; // TOTAL VARIATION ORDER
			endforeach;
		}
		
	$MC_MATVAL		= 0;
	$MC_DPPER		= 0;
	// GET DP VALUE
		$MC_DPPER1	= 0;
		$MC_DPVAL1	= 0;
		$MC_DPVAL2	= 0;
		$DPPPNVAL 	= 0;
		$sqlDP 		= "SELECT SUM(PINV_DPPER) AS DPPERCENT, SUM(PINV_DPVAL) AS DPVALUE, SUM(PINV_DPVALPPn) AS DPPPNVAL
						FROM tbl_projinv_header WHERE PRJCODE = '$PRJCODE' AND PINV_CAT = 1 AND PINV_STAT IN (3,6)";
		$resDP 		= $this->db->query($sqlDP)->result();
		foreach($resDP as $rowDP) :
			$MC_DPPER 	= $rowDP ->DPPERCENT;
			$MC_DPVALA 	= $rowDP ->DPVALUE;
			$DPPPNVALA 	= $rowDP ->DPPPNVAL;
			$MC_DPPER1	= round($MC_DPPER1 + $MC_DPPER);
			$MC_DPVAL1	= round($MC_DPVAL1 + $MC_DPVALA);
			$MC_DPVAL2	= round($MC_DPVAL2 + $MC_DPVALA + $DPPPNVALA);
		endforeach;
	$MC_DPPER		= $MC_DPPER1;
	$MC_DPVAL		= $MC_DPVAL1;
	$MC_DPVALnPPn	= $MC_DPVAL2;
	$MC_DPBACK		= 0;
	$MC_DPBACKCUR	= 0;
	$MC_RETCUTP		= 5;
	$MC_RETCUT		= 0;
	$MC_RETCUTCUR	= 0;
	$MC_PROGAPP		= 0;
	$MC_PROGAPPVAL	= 0;
	$MC_AKUMNEXT	= 0;
	$MC_VALBEF		= 0;
	$MC_TOTVAL		= 0;
	$MC_TOTVAL_PPn	= 0;
	$MC_TOTVAL_PPh	= 0;
	$MC_NOTES		= '';
	$MC_EMPID		= $DefEmp_ID;
	$MC_EMPIDAPP	= '';
	$MC_STAT		= 1;
	$MC_APPSTAT		= 0;
	$MC_ISINV		= 0;
	$MC_ISGROUP		= 0;
	$MC_PAIDSTAT	= 0;
	$PATT_YEAR 		= date('Y');
	$PATT_MONTH		= date('m');
	$PATT_DATE 		= date('d');
	$PATT_NUMBER	= $myMax;
	$ownIns 		= 'S';
	
	$DPExist 		= 0;
	$sqlDP 			= "tbl_projinv_header WHERE PRJCODE = '$PRJCODE' AND PINV_CAT = 1 AND PINV_STAT = 2";
	$resultDP 		= $this->db->count_all($sqlDP);
	if($resultDP > 0)
	{
		$DPExist 	= 1;
	}
	
	// MENCARI NILAI MC_PROG SEBELUMNYA
		$MC_PROGB	= 0;	
		$MC_VALBEF	= 0;
		$sqlMCBC 	= "tbl_mcheader WHERE PRJCODE = '$PRJCODE' AND MC_STAT = 3";
		$resMCBC	= $this->db->count_all($sqlMCBC);
		if($resMCBC > 0)
		{
			$sqlMCB	= "SELECT MC_PROG AS MC_PROGB, MC_PROGAPPVAL AS MC_VALBEF
						FROM tbl_mcheader WHERE PRJCODE = '$PRJCODE' AND MC_STEP = $BEFSTEP AND MC_STAT = 3";
			$resMCB	= $this->db->query($sqlMCB)->result();
			foreach($resMCB as $rowMCB) :
				$MC_PROGB 	= $rowMCB->MC_PROGB;
				$MC_VALBEF 	= $rowMCB->MC_VALBEF;
			endforeach;
		}
		else
		{
			$MC_PROGB 	= 0;
			//$MC_VALBEF 	= $MC_DPVAL1;
			$MC_VALBEF 	= 0;
			
			$sqlDPEXIST	= "tbl_projinv_header WHERE PRJCODE = '$PRJCODE' AND PINV_CAT = 1 AND PINV_STAT = 2";
			$resDPEXIST	= $this->db->count_all($sqlDPEXIST);
			$MAXSTEP	= $resDPEXIST + 1;
		}
}
else
{
	$isSetDocNo = 1;
	$MC_CODE 		= $default['MC_CODE'];
	$DocNumber		= $default['MC_CODE'];
	$MC_MANNO 		= $default['MC_MANNO'];
	$GETFROM 		= $default['GETFROM'];
	$MC_STEP 		= $default['MC_STEP'];
	$MAXSTEP		= $MC_STEP;
	$PRJCODE 		= $default['PRJCODE'];
	$PRJCOST 		= $default['PRJCOST'];
	$MC_OWNER 		= $default['MC_OWNER'];
	$MC_DATE 		= $default['MC_DATE'];
	$MC_DATE		= date('m/d/Y',strtotime($MC_DATE));
	$MC_STARTD 		= $default['MC_STARTD'];
	$MC_STARTD		= date('m/d/Y',strtotime($MC_STARTD));
	$MC_ENDDATE 	= $default['MC_ENDDATE'];
	$MC_ENDDATE		= date('m/d/Y',strtotime($MC_ENDDATE));
	$MC_CHECKD 		= $default['MC_CHECKD']; 
	$MC_CHECKD		= date('m/d/Y',strtotime($MC_CHECKD));
	$MC_RETVAL 		= $default['MC_RETVAL'];
	$MC_PROGB 		= $default['MC_PROGB'];
	$MC_PROG 		= $default['MC_PROG'];
	$MC_PROGVAL 	= $default['MC_PROGVAL'];
	$MC_PROGCUR 	= $default['MC_PROGCUR'];
	$MC_PROGCURVAL 	= $default['MC_PROGCURVAL'];
	$MC_VALADD 		= $default['MC_VALADD'];
	$MC_MATVAL 		= $default['MC_MATVAL'];
	$MC_DPPER 		= $default['MC_DPPER'];
	$MC_DPVAL 		= $default['MC_DPVAL'];
	$MC_DPBACK 		= $default['MC_DPBACK'];
	$MC_DPBACKCUR 	= $default['MC_DPBACKCUR'];
	$MC_RETCUTP 	= $default['MC_RETCUTP'];
	$MC_RETCUT 		= $default['MC_RETCUT'];
	$MC_RETCUTCUR 	= $default['MC_RETCUTCUR'];
	$MC_PROGAPP 	= $default['MC_PROGAPP'];
	$MC_PROGAPPVAL	= $default['MC_PROGAPPVAL'];
	$MC_AKUMNEXT 	= $default['MC_AKUMNEXT'];
	$MC_VALBEF		= $default['MC_VALBEF'];
	$MC_TOTVAL 		= $default['MC_TOTVAL'];
	$MC_TOTVAL_PPn 	= $default['MC_TOTVAL_PPn'];
	$MC_TOTVAL_PPh 	= $default['MC_TOTVAL_PPh'];
	$MC_NOTES 		= $default['MC_NOTES'];
	$MC_EMPID 		= $default['MC_EMPID'];
	$MC_DOC 		= $default['MC_DOC'];
	$MC_STAT 		= $default['MC_STAT'];
	$PATT_YEAR 		= $default['PATT_YEAR'];
	$PATT_MONTH 	= $default['PATT_MONTH'];
	$PATT_DATE 		= $default['PATT_DATE'];
	$PATT_NUMBER 	= $default['PATT_NUMBER'];

	$ownIns = 'S';
	$sqlOWN	= "SELECT own_Code, own_Title, own_Inst, own_Name FROM tbl_owner WHERE own_Code = '$MC_OWNER'";
	$resOWN	= $this->db->query($sqlOWN)->result();
	foreach($resOWN as $rowOWN) :
		$ownIns 	= $rowOWN ->own_Inst;
	endforeach;
}

$sqlOWN	= "SELECT own_Code, own_Title, own_Inst, own_Name FROM tbl_owner WHERE own_Status = '1'";
$resOWN	= $this->db->query($sqlOWN)->result();
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
			if($TranslCode == 'canSelOwn')$canSelOwn = $LangTransl;
			if($TranslCode == 'docNoEmpt')$docNoEmpt = $LangTransl;
			if($TranslCode == 'inpMCStep')$inpMCStep = $LangTransl;
			if($TranslCode == 'inpProgVal')$inpProgVal = $LangTransl;
			if($TranslCode == 'inpMCStep')$inpMCStep = $LangTransl;
			if($TranslCode == 'DownPayment')$DownPayment = $LangTransl;
			if($TranslCode == 'UploadDoc')$UploadDoc = $LangTransl;
		endforeach;
		$isEdit	= 'N';
		if($MC_STAT == 1)
		{
			$isEdit	= 'Y';
		}
		if($MC_STAT == 4)
		{
			$isEdit	= 'Y';
		}
		elseif($MC_STAT == 2 && $ISAPPROVE != 1)
		{
			$isEdit	= 'N';
		}
		elseif($MC_STAT == 2 && $ISAPPROVE == 1)
		{
			$isEdit	= 'Y';
		}
		elseif($MC_STAT == 3)
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
				$sqlC_App	= "tbl_approve_hist WHERE AH_CODE = '$MC_CODE'";
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
				$APPROVE_AMOUNT = $MC_TOTVAL;
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
		    <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/mc.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $AddMC; ?>
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
			                    <input type="hidden" name="MC_STATX" id="MC_STATX" value="<?php echo $MC_STAT; ?>">
			                    <input type="hidden" name="OWN_INST" id="OWN_INST" value="<?php echo $ownIns; ?>">
			                    <input type="hidden" class="form-control" style="max-width:200px; text-align:right;" name="MC_MATVAL" id="MC_MATVAL" value="<?php echo $MC_MATVAL; ?>" title="Material Amount">
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
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $MCNumber; ?></label>
				                    <div class="col-sm-5">
				                    	<input type="text" name="DocNumber" id="DocNumber" value="<?php echo $DocNumber; ?>" class="form-control" disabled>
										<input type="hidden" class="textbox" name="MC_CODE" id="MC_CODE" size="30" value="<?php echo $MC_CODE; ?>" />
										<input type="hidden" class="textbox" name="PATT_NUMBER" id="PATT_NUMBER" size="30" value="<?php echo $PATT_NUMBER; ?>" />
				                    </div>
				                    <div class="col-sm-4">
				                    	<label for="inputName" class="control-label"><?php echo $MCDate; ?></label>
				                    </div>
				                </div>
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $ManualNumber; ?></label>
				                    <div class="col-sm-5">
				                    	<input type="text" name="MC_MANNO" id="MC_MANNO" value="<?php echo $MC_MANNO; ?>" class="form-control">
				                    </div>
				                    <div class="col-sm-4">
				                    	<div class="input-group date">
											<div class="input-group-addon">
											<i class="fa fa-calendar"></i>&nbsp;</div>
											<input type="text" name="MC_DATE" class="form-control pull-left" id="datepicker" value="<?php echo $MC_DATE; ?>" style="width:100%">
										</div>
				                    </div>
				                </div>
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $MCStep; ?></label>
				                    <div class="col-sm-9">
				                    	<select name="MC_STEP1" id="MC_STEP1" class="form-control select2" style="max-width:70px" onChange="changeStep(this.value)">
			                                <?php
			                                    for($STEP=0;$STEP<=50;$STEP++)
			                                    {
			                                    ?>
			                                        <option value="<?php echo $STEP; ?>" <?php if($STEP == $MAXSTEP) { ?> selected <?php } ?>><?php echo $STEP; ?></option>
			                                        <?php
			                                    }
			                                ?>
			                            </select>
			                            <input type="hidden" name="MC_STEP" id="MC_STEP" value="<?php echo $MAXSTEP; ?>">
				                      	<script>
				                            function changeStep(thisVal)	// JS_GOOD
				                            {
				                                document.getElementById('MC_STEP').value = thisVal;
				                            }
				                        </script>
				                    </div>
				                </div>
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label">Periode</label>
				                    <div class="col-sm-4">
				                    	<div class="input-group date">
											<div class="input-group-addon">
											<i class="fa fa-calendar"></i>&nbsp;</div>
											<input type="text" name="MC_STARTD" class="form-control pull-left" id="datepicker2" value="<?php echo $MC_STARTD; ?>" style="width:100px">
											<input type="hidden" name="MC_STARTD" id="MC_STARTD" value="<?php echo $MC_STARTD; ?>" class="form-control" style="max-width:350px" >
										</div>
				                    </div>
				                    <div class="col-sm-1">
				                    	<label for="inputName" class="control-label">s.d.</label>
				                    </div>
				                    <div class="col-sm-4">
				                    	<div class="input-group date">
											<div class="input-group-addon">
											<i class="fa fa-calendar"></i>&nbsp;</div>
											<input type="text" name="MC_ENDDATE" class="form-control pull-left" id="datepicker3" value="<?php echo $MC_ENDDATE; ?>" style="width:100%">
											<input type="hidden" name="MC_ENDDATE" id="MC_ENDDATE" value="<?php echo $MC_ENDDATE; ?>" class="form-control" style="max-width:350px" >
										</div>
				                    </div>
				                </div>
				            	<div class="form-group" style="display: none;">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Project; ?></label>
				                    <div class="col-sm-9">
				                    	<select name="PRJCODE1" id="PRJCODE1" class="form-control" onChange="chooseProject()" disabled>
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
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Owner; ?></label>
				                    <div class="col-sm-5">
				                    	<script type="text/javascript">SunFishERP_DateTimePicker('MC_CHECKD','<?php echo $MC_CHECKD;?>','onMouseOver="mybirdthdates();"');</script>
		                              	<select name="MC_OWNER" id="MC_OWNER" class="form-control select2" onchange="getOWNCatg(this.value)">
			                                <option value="none"> --- </option>
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
			                                        <option value="<?php echo $own_Code; ?>" <?php if($own_Code == $MC_OWNER) { ?> selected <?php } ?>><?php echo $ownCompN; ?></option>
			                                        <?php
			                                    endforeach;
			                                ?>
			                            </select>
				                    </div>
				                    <div class="col-sm-4">
				                    	<label for="inputName" class="control-label">Pek. Tambah/Kurang</label>
				                    </div>
				                </div>

				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $ProjectValue; ?></label>
				                    <div class="col-sm-5">
				                    	<input type="text" class="form-control" style="text-align:right;" name="PRJCOSTX" id="PRJCOSTX" value="<?php print number_format($PRJCOST, $decFormat); ?>" disabled>
		                              	<input type="hidden" class="form-control" style="max-width:200px; text-align:right;" name="PRJCOST" id="PRJCOST" value="<?php echo $PRJCOST; ?>">
				                    </div>
				                    <!-- <div class="col-sm-1">
				                    	<label for="inputName" class="col-sm-3 control-label">PPn</label>
				                    </div> -->
				                    <div class="col-sm-4">
				                    	<input type="text" class="form-control" style="text-align:right;" name="MC_VALADDX" id="MC_VALADDX" value="<?php print number_format($MC_VALADD, $decFormat); ?>" placeholder="Pekerjaan tambah kurang" title="Pekerjaan tambah kurang" onBlur="chgVALDD(this)">
		                            	<input type="hidden" class="form-control" style="max-width:200px; text-align:right;" name="MC_VALADD" id="MC_VALADD" value="<?php echo $MC_VALADD; ?>">
				                    </div>
				                </div>
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label">PPn / Total</label>
				                    <div class="col-sm-5">
				                    	<?php
				                    		$s_00 	= "SELECT PINV_DPPER FROM tbl_projinv_header
				                    					WHERE PINV_CAT = 1 AND PRJCODE = '53108' AND PINV_STAT IN (3,6)";
			                                $PRJCOSTPPN 	= round(($PRJCOST + $MC_VALADD)  * $PRJCOST_PPNP2);
			                                $PRJCOSTTOT 	= round($PRJCOST + $MC_VALADD + $PRJCOSTPPN);
			                            ?>
			                            <input type="text" class="form-control" style="text-align:right;" name="PRJCOSTPPNX" id="PRJCOSTPPNX" value="<?php print number_format($PRJCOSTPPN, $decFormat); ?>" disabled>
			                            <input type="hidden" class="form-control" style="max-width:200px; text-align:right;" name="PRJCOSTPPN" id="PRJCOSTPPN" value="<?php echo $PRJCOSTPPN; ?>">
			                            <input type="hidden" class="form-control" style="max-width:200px; text-align:right;" name="PRJCOST_PPNP2" id="PRJCOST_PPNP2" value="<?php echo $PRJCOST_PPNP2; ?>">
				                    </div>
				                    <div class="col-sm-4">
				                    	<input type="text" class="form-control" style="text-align:right;" name="PRJCOSTnPPNX" id="PRJCOSTnPPNX" value="<?php print number_format($PRJCOSTTOT, $decFormat); ?>" disabled>
		                            	<input type="hidden" class="form-control" style="max-width:200px; text-align:right;" name="PRJCOSTnPPN" id="PRJCOSTnPPN" value="<?php echo $PRJCOSTTOT; ?>"> 
				                    </div>
				                </div>
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $DownPayment; ?></label>
				                    <div class="col-sm-3">
				                    	<input type="text" class="form-control" style="text-align:right;" name="MC_DPPER1" id="MC_DPPER1" value="<?php print number_format($MC_DPPER, 4); ?>" disabled>
		                              	<input type="hidden" size="2" class="textbox" style="text-align:right;" name="MC_DPPER" id="MC_DPPER" value="<?php echo $MC_DPPER; ?>">
				                    </div>
				                    <!-- <div class="col-sm-1">
				                    	<label for="inputName" class="col-sm-3 control-label">DP</label>
				                    </div> -->
				                    <div class="col-sm-6">
				                    	<input type="text" class="form-control" style="text-align:right;" name="MC_DPVAL1" id="MC_DPVAL1" value="<?php print number_format($MC_DPVAL, $decFormat); ?>" disabled>
		                              	<input type="hidden" size="17"  class="form-control" style="max-width:150px; text-align:right;" name="MC_DPVAL" id="MC_DPVAL" value="<?php echo $MC_DPVAL; ?>">
				                    </div>
				                </div>
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label">Tot. DP - Pot. DP</label>
				                    <div class="col-sm-5">
				                    	<input type="text" class="form-control" style="text-align:right;" name="MC_DPVAL1" id="MC_DPVAL1" value="<?php print number_format($MC_DPVAL, $decFormat); ?>" disabled></td>
				                    </div>
				                    <!-- <div class="col-sm-1">
				                    	<label for="inputName" class="control-label">Pot. DP</label>
				                    </div> -->
				                    <div class="col-sm-4">
				                    	<input type="hidden" class="form-control" style="max-width:200px; text-align:right;" name="MC_DPBACK1" id="MC_DPBACK1" value="<?php print number_format($MC_DPBACK, $decFormat); ?>" onBlur="getTOTDUE(this)">
		                          		<input type="hidden" class="form-control" style="max-width:200px; text-align:right;" name="MC_DPBACK" id="MC_DPBACK" value="<?php echo $MC_DPBACK; ?>">
		                          		<input type="text" class="form-control" style="text-align:right;" name="MC_DPBACKCUR1" id="MC_DPBACKCUR1" value="<?php print number_format($MC_DPBACKCUR, $decFormat); ?>" onBlur="chgDPBack(this)"></td>
		                          		<input type="hidden" class="form-control" style="max-width:200px; text-align:right;" name="MC_DPBACKCUR" id="MC_DPBACKCUR" value="<?php echo $MC_DPBACKCUR; ?>">
				                    </div>
				                </div>
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Notes; ?></label>
				                    <div class="col-sm-9">
				                    	<textarea name="MC_NOTES" class="form-control" id="MC_NOTES" cols="30" style="height:50px"><?php echo $MC_NOTES; ?></textarea>
				                    </div>
				                </div>
							</div>
						</div>
					</div>

					<div class="col-md-6">
						<div class="box box-warning">
							<div class="box-header with-border">
								<i class="fa fa-bar-chart"></i>
								<h3 class="box-title">Progress</h3>
							</div>
							<div class="box-body">
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label">Kumulatif (%)</label>
				                    <div class="col-sm-3">
				                    	<input type="text" class="form-control" style="max-width:100px; text-align:right;" name="MC_PROG1" id="MC_PROG1" value="<?php print number_format($MC_PROG, 4); ?>" onBlur="getPROGValueX(this)" onKeyPress="return isIntOnlyNew(event);" <?php if($ISCREATE == 0 || $GETFROM == 2) { ?> disabled <?php } ?> title="Progress Akumulasi">
		                              	<input type="hidden" class="form-control" style="max-width:90px; text-align:right;" name="MC_PROGB" id="MC_PROGB" value="<?php print $MC_PROGB; ?>" >
		                              	<input type="hidden" class="form-control" style="max-width:90px; text-align:right;" name="MC_PROG" id="MC_PROG" value="<?php print $MC_PROG; ?>" >
				                    </div>
				                    <div class="col-sm-6">
				                    	<input type="text" class="form-control" style="text-align:right;" name="MC_PROGVAL1" id="MC_PROGVAL1" value="<?php print number_format($MC_PROGVAL, $decFormat); ?>" onBlur="getPROGPER(this)" <?php if($GETFROM == 1) { ?> disabled <?php } ?> title="Nilai Progress Akumulasi">
										<input type="hidden" class="form-control" style="max-width:200px; text-align:right;" name="MC_PROGVAL" id="MC_PROGVAL" value="<?php print $MC_PROGVAL; ?>" >
		                              	<input type="hidden" name="GETFROM" id="GETFROM" value="<?php echo $GETFROM; ?>">
				                    </div>
				                </div>
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label">Disetujui  (%)</label>
				                    <div class="col-sm-3">
				                    	<input type="text" class="form-control" style="max-width:100px; text-align:right;" name="MC_PROGAPPx" id="MC_PROGAPPx" value="<?php print number_format($MC_PROGAPP, 4); ?>" onBlur="getPROGAPPValue(this)" onKeyPress="return isIntOnlyNew(event);" <?php if($GETFROM == 2) { ?> disabled <?php } ?> title="Progress Akumulasi Disetujui">
										<input type="hidden" class="form-control" style="max-width:90px; text-align:right;" name="MC_PROGAPP" id="MC_PROGAPP" value="<?php print $MC_PROGAPP; ?>" >
				                    </div>
				                    <div class="col-sm-6">
				                    	<input type="text" class="form-control" style="text-align:right;" name="MC_PROGAPPVALx" id="MC_PROGAPPVALx" value="<?php print number_format($MC_PROGAPPVAL, $decFormat); ?>" onBlur="getPROGPERAPP(this)" <?php if($GETFROM == 1) { ?> disabled <?php } ?> title="Nilai Progres Disetujui">
										<input type="hidden" class="form-control" style="max-width:200px; text-align:right;" name="MC_PROGAPPVAL" id="MC_PROGAPPVAL" value="<?php print $MC_PROGAPPVAL; ?>" title="Approve Amount" >
				                    </div>
				                </div>
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label">Sebelumnya</label>
				                    <div class="col-sm-4">
				                    	<input type="text" class="form-control" style="text-align:right;" name="MC_VALBEFx" id="MC_VALBEFx" value="<?php echo number_format($MC_VALBEF, $decFormat); ?>" title="Progres Sebelumnya" disabled>
										<input type="hidden" class="form-control" style="max-width:200px; text-align:right;" name="MC_VALBEF" id="MC_VALBEF" value="<?php echo $MC_VALBEF; ?>">
				                    </div>
				                    <div class="col-sm-5">
				                    	<input type="text" class="form-control" style="text-align:right;" name="MC_PROGCURVALx" id="MC_PROGCURVALx" value="<?php print number_format($MC_PROGCURVAL,2); ?>" title="Progres Saat ini" >
		                              	<input type="hidden" class="form-control" style="max-width:135px; text-align:right;" name="MC_PROGCURVAL" id="MC_PROGCURVAL" value="<?php print $MC_PROGCURVAL; ?>" title="Nilai Progress Saat ini" >
		                              	<input type="hidden" class="form-control" style="max-width:90px; text-align:right;" name="MC_PROGCUR" id="MC_PROGCUR" value="<?php print $MC_PROGCUR; ?>" title="Persentase Progress Saat ini" >
				                    </div>
				                </div>
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Retention; ?></label>
				                    <div class="col-sm-3">
				                    	<input type="text" class="form-control" style="text-align:right;" name="MC_RETCUTPx" id="MC_RETCUTPx" value="<?php print number_format($MC_RETCUTP, 4); ?>" onBlur="getRETValue(this.value)" onKeyPress="return isIntOnlyNew(event);" <?php if($GETFROM == 2) { ?> disabled <?php } ?>>
			                            <input type="hidden" class="form-control" style="max-width:90px; text-align:right;" name="MC_RETCUTP" id="MC_RETCUTP" value="<?php print $MC_RETCUTP; ?>" >
				                    </div>
				                    <!-- <div class="col-sm-2">
				                    	<label for="inputName" class="col-sm-3 control-label">&nbsp;</label>
				                    </div> -->
				                    <div class="col-sm-6">
				                    	<input type="text" class="form-control" style="text-align:right;" name="MC_RETCUTx" id="MC_RETCUTx" value="<?php print number_format($MC_RETCUT, $decFormat); ?>" onBlur="getRETPerc(this)" <?php if($GETFROM == 1) { ?> disabled <?php } ?>>
		                            	<input type="hidden" class="form-control" style="max-width:200px; text-align:right;" name="MC_RETCUT" id="MC_RETCUT" value="<?php print $MC_RETCUT; ?>" >
		                            	<input type="hidden" class="form-control" style="max-width:200px; text-align:right;" name="MC_RETCUTCUR" id="MC_RETCUTCUR" value="<?php print $MC_RETCUTCUR; ?>" >
				                    </div>
				                </div>
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $MCAmount; ?> - PPn</label>
				                    <div class="col-sm-4">
				                    	<input type="text" class="form-control" style="text-align:right;" name="MC_TOTVALx" id="MC_TOTVALx" value="<?php echo number_format($MC_TOTVAL, $decFormat); ?>" title="MCPay. = Next Amount - Amount Before" onBlur="getMCTOT(this)">
				                        <input type="hidden" class="form-control" style="text-align:right;" name="MC_TOTVAL" id="MC_TOTVAL" value="<?php echo $MC_TOTVAL; ?>">
				                    </div>
				                    <div class="col-sm-5">
				                    	<input type="text" class="form-control" style="text-align:right;" name="MC_TOTVAL_PPnx" id="MC_TOTVAL_PPnx" value="<?php echo number_format($MC_TOTVAL_PPn, $decFormat); ?>" onKeyPress="return isIntOnlyNew(event);" onBlur="getMCTOTPPn(this)">
				                        <input type="hidden" class="form-control" style="text-align:right;" name="MC_TOTVAL_PPn" id="MC_TOTVAL_PPn" value="<?php echo $MC_TOTVAL_PPn; ?>">
				                    </div>
				                    <?php
								  		$MC_TOTVALnPPn	= $MC_TOTVAL + $MC_TOTVAL_PPn;
								  	?>
				                </div>
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label">Total - PPh</label>
				                    <div class="col-sm-4">
				                    	<input type="text" class="form-control" style="text-align:right;" name="MC_TOTVALnPn" id="MC_TOTVALnPn" value="<?php print number_format($MC_TOTVALnPPn, $decFormat); ?>" disabled>
				                    </div>
				                    <!-- <div class="col-sm-1">
				                    	<label for="inputName" class="col-sm-3 control-label">PPh</label>
				                    </div> -->
				                    <div class="col-sm-5">
				                    	<input type="text" class="form-control" style="text-align:right;" name="MC_TOTVAL_PPhx" id="MC_TOTVAL_PPhx" value="<?php echo number_format($MC_TOTVAL_PPh, $decFormat); ?>" onBlur="getTOTPlusPPH(this)" onKeyPress="return isIntOnlyNew(event);">
		                              	<input type="hidden" class="form-control" style="max-width:155px; text-align:right;" name="MC_TOTVAL_PPh" id="MC_TOTVAL_PPh" value="<?php echo $MC_TOTVAL_PPh; ?>" title="Payment Before Retention">
				                    </div>
				                </div>
		                      	<?php
							  		$TOTPAYMENT	= $MC_TOTVAL + $MC_TOTVAL_PPn - $MC_TOTVAL_PPh;
							  	?>
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $ReceiptAmount; ?></label>
				                    <div class="col-sm-4">
				                    	<input type="text" class="form-control" style="text-align:right; background-color:#0C6;" name="MC_TOTPAYMENTX" id="MC_TOTPAYMENTX" value="<?php print number_format($TOTPAYMENT, $decFormat); ?>" disabled>
										<input type="hidden" class="form-control" style="max-width:200px; text-align:right; background-color:#0C6;" name="MC_TOTPAYMENT" id="MC_TOTPAYMENT" value="<?php print $TOTPAYMENT; ?>" disabled>
				                    </div>
				                    <div class="col-sm-2">
				                    	<!-- -->
				                    </div>
				                    <div class="col-sm-4">
				                    	<!-- -->
				                    </div>
				                </div>
				                <div class="form-group">
				                	<label for="inputName" class="col-sm-3 control-label"><?php echo $UploadDoc; ?></label>
				                	<div class="col-sm-9">
				                		<input type="file" class="form-control" name="MC_DOC[]" id="MC_DOC" accept="application/pdf" multiple>
				                		<span class="text-muted" style="font-size: 9pt; font-style: italic;">Format File: PDF</span>
				                	</div>
				                </div>
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Status; ?></label>
				                    <div class="col-sm-9">
				                    	<input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $MC_STAT; ?>">
			                            <?php
			                                if($ISCREATE == 1 && $ISAPPROVE == 1)
			                                {
												// START : FOR ALL APPROVAL FUNCTION
													if($disableAll == 0)
													{
														if($canApprove == 1)
														{
															$disButton	= 0;
															$sqlCAPPHE	= "tbl_approve_hist WHERE AH_CODE = '$MC_CODE' AND AH_APPROVER = '$DefEmp_ID'";
															$resCAPPHE	= $this->db->count_all($sqlCAPPHE);
															if($resCAPPHE > 0)
																$disButton	= 1;
															?>
																<select name="MC_STAT" id="MC_STAT" class="form-control select2" <?php if($disButton == 1) { ?> disabled <?php } ?> >
																	<option value="0"> -- </option>
			                                                        <option value="1"<?php if($MC_STAT == 1) { ?> selected <?php } ?>>New</option>
			                                                        <option value="2"<?php if($MC_STAT == 2) { ?> selected <?php } ?>>Confirm</option>
																	<option value="3"<?php if($MC_STAT == 3) { ?> selected <?php } ?>>Approved</option>
																	<option value="4"<?php if($MC_STAT == 4) { ?> selected <?php } ?>>Revised</option>
																	<option value="5"<?php if($MC_STAT == 5) { ?> selected <?php } ?>>Rejected</option>
																	<option value="6"<?php if($MC_STAT == 6) { ?> selected <?php } ?> disabled>Closed</option>
																	<option value="7"<?php if($MC_STAT == 7) { ?> selected <?php } ?> disabled>Awaiting</option>
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
			                                elseif($ISCREATE == 1)
			                                {
			                                	$disableAll = 0;
			                                ?>
			                                    <select name="MC_STAT" id="MC_STAT" class="form-control select2">
			                                    <option value="1"<?php if($MC_STAT == 1) { ?> selected <?php } ?>>New</option>
			                                    <option value="2"<?php if($MC_STAT == 2) { ?> selected <?php } ?>>Confirm</option>
			                                    <option value="3"<?php if($MC_STAT == 3) { ?> selected <?php } ?> disabled>Approved</option>
			                                    <option value="4"<?php if($MC_STAT == 4) { ?> selected <?php } ?> disabled>Revised</option>
			                                    <option value="5"<?php if($MC_STAT == 5) { ?> selected <?php } ?> disabled>Rejected</option>
			                                    <option value="6"<?php if($MC_STAT == 6) { ?> selected <?php } ?> disabled>Closed</option>
			                                    <option value="7"<?php if($MC_STAT == 7) { ?> selected <?php } ?> disabled>Awaiting</option>
			                                    </select>
			                                <?php
			                                }
			                                elseif($ISAPPROVE == 1)
			                                {
												// START : FOR ALL APPROVAL FUNCTION
													if($disableAll == 0)
													{
														if($canApprove == 1)
														{
															$disButton	= 0;
															$sqlCAPPHE	= "tbl_approve_hist WHERE AH_CODE = '$MC_CODE' AND AH_APPROVER = '$DefEmp_ID'";
															$resCAPPHE	= $this->db->count_all($sqlCAPPHE);
															if($resCAPPHE > 0)
																$disButton	= 1;
															?>
																<select name="MC_STAT" id="MC_STAT" class="form-control select2" <?php if($disButton == 1) { ?> disabled <?php } ?> >
																	<option value="0"> -- </option>
			                                                        <option value="1"<?php if($MC_STAT == 1) { ?> selected <?php } ?> disabled>New</option>
			                                                        <option value="2"<?php if($MC_STAT == 2) { ?> selected <?php } ?> disabled>Confirm</option>
																	<option value="3"<?php if($MC_STAT == 3) { ?> selected <?php } ?>>Approved</option>
																	<option value="4"<?php if($MC_STAT == 4) { ?> selected <?php } ?>>Revised</option>
																	<option value="5"<?php if($MC_STAT == 5) { ?> selected <?php } ?>>Rejected</option>
																	<option value="6"<?php if($MC_STAT == 6) { ?> selected <?php } ?> disabled>Closed</option>
																	<option value="7"<?php if($MC_STAT == 7) { ?> selected <?php } ?> disabled>Awaiting</option>
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
			                            ?>
				                    </div>
				                </div>
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label">&nbsp;</label>
				                    <div class="col-sm-9">
				                    	<?php
				                    	if($task == 'add')
				                    	{
				                    		if($ISCREATE == 1)
				                    		{
					                    		if(($MC_STAT == 1 || $MC_STAT == 4) && $disableAll == 0)
												{
													?>
														<button type="button" class="btn btn-primary" onClick="submitForm(1);">
														<i class="fa fa-save"></i></button>&nbsp;
													<?php
												}
											}
				                    	}
									  	else if($disableAll == 0)
										{
											if($ISCREATE == 1 || $ISAPPROVE == 1)
											{
												if(($MC_STAT == 2 || $MC_STAT == 7) && $canApprove == 1)
												{
													?>
														<button class="btn btn-primary" >
														<i class="fa fa-save"></i></button>&nbsp;
													<?php
												}
												elseif($MC_STAT == 1 || $MC_STAT == 4)
												{
													?>
														<button type="button" class="btn btn-primary" onClick="submitForm(1);">
														<i class="fa fa-save"></i></button>&nbsp;
													<?php
												}
											}
										}
										echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>');
										?>
				                    </div>
				                </div>
				                <br>
							</div>
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

	    //Date picker
	    $('#datepicker3').datepicker({
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

	    $('#MC_DOC').bind('change', function (event) {
			var files 		= event.target.files;
			var totalsize 	= 0;

			console.log(files);

			for(let i = 0; i < files.length; i++) 
			{
				if(files[i].type != "application/pdf")
				{
					swal("Hanya file pdf yang boleh diupload");
					$(this).val('');
				}
			}
	    });

  	});
</script>
<?php
	$secURL	= site_url('c_project/c_mc180c2c/getOWNC/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
?>
<script>
	function getOWNCatg(theVal)
	{
		var OWNCODE 	= theVal;

        $.ajax({
            type: 'POST',
            url: "<?php echo $secURL; ?>",
            data: {OWNCODE: OWNCODE},
            success: function(response)
            {
            	document.getElementById('OWN_INST').value = response;
            }
        });
	}

	function getPROGValueX(thisVal)
	{
	    var decFormat	= document.getElementById('decFormat').value;
	    var PRJCOSTX	= parseFloat(document.getElementById('PRJCOST').value);
	    var MC_VALADD	= parseFloat(document.getElementById('MC_VALADD').value);
	    var PRJCOST 	= parseFloat(PRJCOSTX + MC_VALADD);
	    var MC_PROGPER	= parseFloat(eval(thisVal).value.split(",").join(""));
	    var MC_PROGVAL1	= parseFloat(MC_PROGPER) * parseFloat(PRJCOST) / 100;
	    var MC_PROGVAL 	= parseFloat(MC_PROGVAL1);

	    var MC_OWNER	= document.getElementById('MC_OWNER').value;
	    if(MC_OWNER == 'none')
	    {
	    	swal('<?php echo $canSelOwn; ?>',
	    	{
	    		icon:"warning",
	    	});
	    	document.getElementById('MC_PROG').value 		= 0;
	    	document.getElementById('MC_PROG1').value 		= doDecimalFormatxx(RoundNDecimal(parseFloat(0), 4));
	    	document.getElementById('MC_PROGVAL').value 	= 0;
	    	document.getElementById('MC_PROGVAL1').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(0), 2));
	    	return false;
	    }
	    
	    document.getElementById('MC_PROG1').value 		= doDecimalFormatxx(RoundNDecimal(parseFloat(MC_PROGPER), 4));
	    document.getElementById('MC_PROG').value 		= MC_PROGPER.toFixed(4);
	    document.getElementById('MC_PROGVAL1').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(MC_PROGVAL), 2));
	    document.getElementById('MC_PROGVAL').value 	= RoundNDecimal(parseFloat(MC_PROGVAL), 2);
		
	    document.getElementById('MC_PROGAPPx').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(MC_PROGPER), 4));
	    document.getElementById('MC_PROGAPP').value 	= MC_PROGPER.toFixed(4);
	    document.getElementById('MC_PROGAPPVALx').value	= doDecimalFormatxx(RoundNDecimal(parseFloat(MC_PROGVAL), 2));
	    document.getElementById('MC_PROGAPPVAL').value 	= RoundNDecimal(parseFloat(MC_PROGVAL), 2);
		
		// PROGRESS SAAT INI
			MC_PROGB	= parseFloat(document.getElementById('MC_PROGB').value);
			MC_PROGCUR	= parseFloat(MC_PROGPER) - parseFloat(MC_PROGB);

			MC_VALB		= parseFloat(document.getElementById('MC_VALBEF').value);
			MC_VALCUR	= parseFloat(MC_PROGVAL) - parseFloat(MC_VALB);

	    document.getElementById('MC_PROGCUR').value 		= MC_PROGCUR.toFixed(4);
	    document.getElementById('MC_PROGCURVAL').value 		= MC_VALCUR.toFixed(2);
	    document.getElementById('MC_PROGCURVALx').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(MC_VALCUR), 2));
	    
	    document.getElementById('GETFROM').value			= 2;
		
	    getPROGValue(MC_PROGVAL);
	}

	function getPROGPER(thisVal)
	{
	    var decFormat		= document.getElementById('decFormat').value;
	    var PRJCOSTX		= parseFloat(document.getElementById('PRJCOST').value);
	    var MC_VALADD		= parseFloat(document.getElementById('MC_VALADD').value);
	    var PRJCOST 		= parseFloat(PRJCOSTX + MC_VALADD);
	    var MC_PROGVAL		= eval(thisVal).value.split(",").join("");
	    // AGAR PERSENTASE TIDAK SELALU BERUBAH
	    var MC_PROGPER		= document.getElementById('MC_PROG').value;
	    if(MC_PROGPER == 0)
	   		var MC_PROGPER	= parseFloat(MC_PROGVAL) / parseFloat(PRJCOST) * 100;

	    var MC_OWNER	= document.getElementById('MC_OWNER').value;
	    if(MC_OWNER == 'none')
	    {
	    	swal('<?php echo $canSelOwn; ?>',
	    	{
	    		icon:"warning",
	    	});
	    	document.getElementById('MC_PROG').value 		= 0;
	    	document.getElementById('MC_PROG1').value 		= doDecimalFormatxx(RoundNDecimal(parseFloat(0), 4));
	    	document.getElementById('MC_PROGVAL').value 	= 0;
	    	document.getElementById('MC_PROGVAL1').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(0), 2));
	    	return false;
	    }
	    
	    document.getElementById('MC_PROG1').value 		= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_PROGPER)), 4));
	    document.getElementById('MC_PROG').value 		= RoundNDecimal(parseFloat(MC_PROGPER), 4);
	    document.getElementById('MC_PROGVAL1').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_PROGVAL)), 2));
	    document.getElementById('MC_PROGVAL').value 	= RoundNDecimal(parseFloat(MC_PROGVAL), 2);
	    
	    document.getElementById('GETFROM').value		= 2;
	    
	    document.getElementById('MC_PROGAPPx').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_PROGPER)), 4));
	    document.getElementById('MC_PROGAPP').value 	= RoundNDecimal(parseFloat(MC_PROGPER), 4);
	    document.getElementById('MC_PROGAPPVALx').value = doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_PROGVAL)), 2));
	    document.getElementById('MC_PROGAPPVAL').value 	= RoundNDecimal(parseFloat(MC_PROGVAL), 2);
		
		// PROGRESS SAAT INI
			MC_PROGB	= parseFloat(document.getElementById('MC_PROGB').value);
			MC_PROGCUR	= parseFloat(MC_PROGPER - MC_PROGB);

			MC_VALB		= parseFloat(document.getElementById('MC_VALBEF').value);
			MC_VALCUR	= parseFloat(MC_PROGVAL - MC_VALB);

	    document.getElementById('MC_PROGCUR').value 		= MC_PROGCUR.toFixed(4);
	    document.getElementById('MC_PROGCURVAL').value 		= MC_VALCUR.toFixed(2);
	    document.getElementById('MC_PROGCURVALx').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(MC_VALCUR), 2));
	    
	    getPROGValue(MC_PROGVAL);
	}

    function getPROGAPPValue(thisVal)
    {
        var decFormat		= document.getElementById('decFormat').value;
	    var PRJCOSTX		= parseFloat(document.getElementById('PRJCOST').value);
	    var MC_VALADD		= parseFloat(document.getElementById('MC_VALADD').value);
	    var PRJCOST 		= parseFloat(PRJCOSTX + MC_VALADD);
        var MC_PROGAPPPER	= parseFloat(eval(thisVal).value.split(",").join(""));
        var MC_PROGAPPVAL	= parseFloat(MC_PROGAPPPER) * parseFloat(PRJCOST) / 100;

	    var MC_OWNER	= document.getElementById('MC_OWNER').value;
	    if(MC_OWNER == 'none')
	    {
	    	swal('<?php echo $canSelOwn; ?>',
	    	{
	    		icon:"warning",
	    	});
	    	document.getElementById('MC_PROG').value 		= 0;
	    	document.getElementById('MC_PROG1').value 		= doDecimalFormatxx(RoundNDecimal(parseFloat(0), 4));
	    	document.getElementById('MC_PROGVAL').value 	= 0;
	    	document.getElementById('MC_PROGVAL1').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(0), 2));
	    	return false;
	    }
		
	    document.getElementById('MC_PROGAPPx').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(MC_PROGAPPPER), 4));
	    document.getElementById('MC_PROGAPP').value 	= MC_PROGAPPPER.toFixed(4);
	    document.getElementById('MC_PROGAPPVALx').value	= doDecimalFormatxx(RoundNDecimal(parseFloat(MC_PROGAPPVAL), 2));
	    document.getElementById('MC_PROGAPPVAL').value 	= RoundNDecimal(parseFloat(MC_PROGAPPVAL), 2);
		
		// PROGRESS SAAT INI
			MC_PROGB	= parseFloat(document.getElementById('MC_PROGB').value);
			MC_PROGCUR	= parseFloat(MC_PROGAPPPER) - parseFloat(MC_PROGB);

			MC_VALB		= parseFloat(document.getElementById('MC_VALBEF').value);
			MC_VALCUR	= parseFloat(MC_PROGAPPVAL) - parseFloat(MC_VALB);

	    document.getElementById('MC_PROGCUR').value 		= MC_PROGCUR.toFixed(4);
	    document.getElementById('MC_PROGCURVAL').value 		= MC_VALCUR.toFixed(2);
	    document.getElementById('MC_PROGCURVALx').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(MC_VALCUR), 2));
		
	    getPROGValue(MC_PROGVAL);
    }

    function getPROGPERAPP(thisVal)
    {
        var decFormat		= document.getElementById('decFormat').value;
	    var PRJCOSTX		= parseFloat(document.getElementById('PRJCOST').value);
	    var MC_VALADD		= parseFloat(document.getElementById('MC_VALADD').value);
	    var PRJCOST 		= parseFloat(PRJCOSTX + MC_VALADD);
        var MC_PROGAPPVAL	= parseFloat(eval(thisVal).value.split(",").join(""));
	    // AGAR PERSENTASE TIDAK SELALU BERUBAH
	    var MC_PROGAPPPER	=  parseFloat(document.getElementById('MC_PROGAPP').value);
	    if(MC_PROGAPPPER == 0)
	   		var MC_PROGAPPPER	= parseFloat(MC_PROGAPPVAL) / parseFloat(PRJCOST) * 100;

	    var MC_OWNER	= document.getElementById('MC_OWNER').value;
	    if(MC_OWNER == 'none')
	    {
	    	swal('<?php echo $canSelOwn; ?>',
	    	{
	    		icon:"warning",
	    	});
	    	document.getElementById('MC_PROG').value 		= 0;
	    	document.getElementById('MC_PROG1').value 		= doDecimalFormatxx(RoundNDecimal(parseFloat(0), 4));
	    	document.getElementById('MC_PROGVAL').value 	= 0;
	    	document.getElementById('MC_PROGVAL1').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(0), 2));
	    	return false;
	    }
		
	    document.getElementById('MC_PROGAPPx').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(MC_PROGAPPPER), 4));
	    document.getElementById('MC_PROGAPP').value 	= MC_PROGAPPPER.toFixed(4);
	    document.getElementById('MC_PROGAPPVALx').value	= doDecimalFormatxx(RoundNDecimal(parseFloat(MC_PROGAPPVAL), 2));
	    document.getElementById('MC_PROGAPPVAL').value 	= RoundNDecimal(parseFloat(MC_PROGAPPVAL), 2);
		
		// PROGRESS SAAT INI
			MC_PROGB	= parseFloat(document.getElementById('MC_PROGB').value);
			MC_PROGCUR	= parseFloat(MC_PROGAPPPER) - parseFloat(MC_PROGB);

			MC_VALB		= parseFloat(document.getElementById('MC_VALBEF').value);
			MC_VALCUR	= parseFloat(MC_PROGAPPVAL) - parseFloat(MC_VALB);

	    document.getElementById('MC_PROGCUR').value 		= MC_PROGCUR.toFixed(4);
	    document.getElementById('MC_PROGCURVAL').value 		= MC_VALCUR.toFixed(2);
	    document.getElementById('MC_PROGCURVALx').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(MC_VALCUR), 2));
		
	    getPROGValue(MC_PROGVAL);
    }
	
    function getRETPerc(thisVal)
    {
        var decFormat		= document.getElementById('decFormat').value;
		var ownIns			= document.getElementById('OWN_INST').value;
        var MC_RETCUT		= parseFloat(eval(thisVal).value.split(",").join(""));
		var MC_PROGAPPVAL	= document.getElementById('MC_PROGAPPVAL').value;
		var MC_PROGCURVAL	= document.getElementById('MC_PROGCURVAL').value;
		var MC_DPBACKCUR	= document.getElementById('MC_DPBACKCUR').value;
		var PRJCOST_PPNP2	= document.getElementById('PRJCOST_PPNP2').value;

		// RETENSI AKUMULATIF -- PERMINTAAN SASMITO AGAR TIDAK MENGHITUNG OTOMATIS
			document.getElementById('MC_RETCUTx').value	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_RETCUT)), 2));
			document.getElementById('MC_RETCUT').value	= MC_RETCUT.toFixed(2);

		// CUR_RETVAL	--- RETENSI SAAT INI
			MC_RETCUTP		= parseFloat(MC_RETCUT) / parseFloat(MC_PROGAPPVAL) * 100;
        	MC_RETCUTCURA	= parseFloat(MC_RETCUTP) * parseFloat(MC_PROGCURVAL) / 100;
        	MC_RETCUTCUR 	= parseFloat(MC_RETCUTCURA);
			document.getElementById('MC_RETCUTCUR').value	 	= MC_RETCUTCUR;

			CUR_RETVAL		= parseFloat(MC_RETCUTCUR);
	    
	   	// MC TOTAL
			MC_TOTVAL 		= parseFloat(MC_PROGCURVAL - MC_DPBACKCUR - CUR_RETVAL);

	   	// MC TOTAL PPn
			MC_TOTVAL_PPn	= parseFloat(PRJCOST_PPNP2 * MC_TOTVAL);

			document.getElementById('MC_TOTVAL').value 		= MC_TOTVAL.toFixed(2);
			document.getElementById('MC_TOTVALx').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_TOTVAL)), 2));
			document.getElementById('MC_TOTVAL_PPn').value 	= MC_TOTVAL_PPn.toFixed(2);
			document.getElementById('MC_TOTVAL_PPnx').value = doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_TOTVAL_PPn)), 2));
			MC_TOTVALnPn	= parseFloat(MC_TOTVAL + MC_TOTVAL_PPn);
			document.getElementById('MC_TOTVALnPn').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_TOTVALnPn)), 2));
			
	   	// MC TOTAL PPh
	   		if(ownIns == 'S')
				MC_TOTVAL_PPh	= parseFloat(0.03 * MC_TOTVAL); 
			else
			{
				MC_TOTVAL_PPh1	= parseFloat(MC_TOTVAL / 1.03);
				MC_TOTVAL_PPh	= parseFloat(MC_TOTVAL - MC_TOTVAL_PPh1);
			}
			
			document.getElementById('MC_TOTVAL_PPh').value 	= MC_TOTVAL_PPh.toFixed(2);
			document.getElementById('MC_TOTVAL_PPhx').value = doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_TOTVAL_PPh)), 2));
			
		// TOTAL RECEIVE AFTER PPh - Net Receipt
			if(ownIns == 'S')
				MC_TOTVALaftPPh	= parseFloat(MC_TOTVAL + MC_TOTVAL_PPn - MC_TOTVAL_PPh);
			else
				MC_TOTVALaftPPh	= parseFloat(MC_TOTVAL + MC_TOTVAL_PPn - MC_TOTVAL_PPh);
			
			document.getElementById('MC_TOTPAYMENT').value 	= MC_TOTVALaftPPh.toFixed(2);
			document.getElementById('MC_TOTPAYMENTX').value = doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_TOTVALaftPPh)), 2));
	}

	function getPROGValue(thisVal)
	{
	    var decFormat		= document.getElementById('decFormat').value;
		var ownIns 			= document.getElementById('OWN_INST').value;
	    var PRJCOST			= parseFloat(document.getElementById('PRJCOST').value);
	    var MC_VALADD		= parseFloat(document.getElementById('MC_VALADD').value);
	    var MC_MATVAL		= parseFloat(document.getElementById('MC_MATVAL').value);
	    var MC_PROGAPPVAL	= parseFloat(document.getElementById('MC_PROGAPPVAL').value);
	    var MC_PROGCURVAL	= parseFloat(document.getElementById('MC_PROGCURVAL').value);
		var PRJCOST_PPNP2	= parseFloat(document.getElementById('PRJCOST_PPNP2').value);

		// AKUMULASI PENGEMBALIAN DP PROPORSIONAL THD UANG MUKA - OK
			MC_DPPER		= parseFloat(document.getElementById('MC_DPPER').value);
			MC_DPVAL		= parseFloat(document.getElementById('MC_DPVAL').value);
			MC_DPBACK		= parseFloat(MC_PROGAPPVAL) * parseFloat(MC_DPPER) / 100;
			document.getElementById('MC_DPBACK').value 		= MC_DPBACK.toFixed(4);
			document.getElementById('MC_DPBACK1').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.round(MC_DPBACK)), 2));
		
		// CUR_DPBACK	--- PENGEMBALIAN DP SAAT INI PROPOSIONAL THD PROGRESS SAAT INI - OK
			MC_DPBACKCUR	= parseFloat(MC_PROGCURVAL * MC_DPPER / 100);
			document.getElementById('MC_DPBACKCUR').value 	= MC_DPBACKCUR.toFixed(2);
			document.getElementById('MC_DPBACKCUR1').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.round(MC_DPBACKCUR)), 2));

			CUR_DPBACK		= parseFloat(MC_DPBACKCUR);
			
		// RETENSI
			MC_RETCUTP		= parseFloat(document.getElementById('MC_RETCUTP').value);
	    	MC_RETCUTA		= parseFloat(MC_RETCUTP) * parseFloat(MC_PROGAPPVAL) / 100;
			MC_RETCUT 		= parseFloat(MC_RETCUTA);

			document.getElementById('MC_RETCUTPx').value	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_RETCUTP)), 4));
			document.getElementById('MC_RETCUTP').value	 	= MC_RETCUTP.toFixed(4);
			document.getElementById('MC_RETCUTx').value		= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_RETCUT)), 2));
			document.getElementById('MC_RETCUT').value 		= MC_RETCUT.toFixed(2);
		
		// CUR_RETVAL	--- RETENSI SAAT INI
	    	MC_RETCUTCURA	= parseFloat(MC_RETCUTP) * parseFloat(MC_PROGCURVAL) / 100;
	    	MC_RETCUTCUR 	= parseFloat(MC_RETCUTCURA);
			document.getElementById('MC_RETCUTCUR').value	 = MC_RETCUTCUR.toFixed(2);

			CUR_RETVAL		= parseFloat(MC_RETCUTCUR);
	    
	   	// MC TOTAL
			MC_TOTVAL 		= parseFloat(MC_PROGCURVAL - CUR_DPBACK - CUR_RETVAL);

	   	// MC TOTAL PPn
			MC_TOTVAL_PPn	= parseFloat(PRJCOST_PPNP2 * MC_TOTVAL);

			document.getElementById('MC_TOTVAL').value 		= MC_TOTVAL.toFixed(2);
			document.getElementById('MC_TOTVALx').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_TOTVAL)), 2));
			document.getElementById('MC_TOTVAL_PPn').value 	= MC_TOTVAL_PPn.toFixed(2);
			document.getElementById('MC_TOTVAL_PPnx').value = doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_TOTVAL_PPn)), 2));
			MC_TOTVALnPn	= parseFloat(MC_TOTVAL + MC_TOTVAL_PPn);
			document.getElementById('MC_TOTVALnPn').value 		= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_TOTVALnPn)), 2));
			
	   	// MC TOTAL PPh
	   		if(ownIns == 'S')
				MC_TOTVAL_PPh	= parseFloat(0.03 * MC_TOTVAL); 
			else
			{
				MC_TOTVAL_PPh1	= parseFloat(MC_TOTVAL / 1.03);
				MC_TOTVAL_PPh	= parseFloat(MC_TOTVAL - MC_TOTVAL_PPh1);
			}
			
			document.getElementById('MC_TOTVAL_PPh').value 	= MC_TOTVAL_PPh.toFixed(2);
			document.getElementById('MC_TOTVAL_PPhx').value = doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_TOTVAL_PPh)), 2));
			
		// TOTAL RECEIVE AFTER PPh - Net Receipt
			if(ownIns == 'S')
				MC_TOTVALaftPPh	= parseFloat(MC_TOTVAL + MC_TOTVAL_PPn - MC_TOTVAL_PPh);	// YANG MEMBEDAKAN HY BESARAN PPH
			else
				MC_TOTVALaftPPh	= parseFloat(MC_TOTVAL + MC_TOTVAL_PPn - MC_TOTVAL_PPh);	// YANG MEMBEDAKAN HY BESARAN PPH
			
			document.getElementById('MC_TOTPAYMENT').value 	= MC_TOTVALaftPPh.toFixed(2);
			document.getElementById('MC_TOTPAYMENTX').value = doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_TOTVALaftPPh)), 2));
	}

    function getMCTOT(thisVal)
    {
		var ownIns			= document.getElementById('OWN_INST').value;
        var MC_TOTVAL		= parseFloat(eval(thisVal).value.split(",").join(""));
		var MC_PROGAPPVAL	= parseFloat(document.getElementById('MC_PROGAPPVAL').value);
		var MC_PROGCURVAL	= parseFloat(document.getElementById('MC_PROGCURVAL').value);
		var MC_RETCUTCUR 	= parseFloat(document.getElementById('MC_RETCUTCUR').value);
		var MC_DPBACKCUR 	= parseFloat(document.getElementById('MC_DPBACKCUR').value);
		var PRJCOST_PPNP2	= parseFloat(document.getElementById('PRJCOST_PPNP2').value);

       	// MC TOTAL PPn
			MC_TOTVAL_PPn	= parseFloat(PRJCOST_PPNP2 * MC_TOTVAL);

			document.getElementById('MC_TOTVAL').value 		= MC_TOTVAL.toFixed(2);
			document.getElementById('MC_TOTVALx').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_TOTVAL)), 2));
			document.getElementById('MC_TOTVAL_PPn').value 	= MC_TOTVAL_PPn.toFixed(2);
			document.getElementById('MC_TOTVAL_PPnx').value = doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_TOTVAL_PPn)), 2));
			MC_TOTVALnPn	= parseFloat(MC_TOTVAL + MC_TOTVAL_PPn);
			document.getElementById('MC_TOTVALnPn').value 		= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_TOTVALnPn)), 2));
			
	   	// MC TOTAL PPh
	   		if(ownIns == 'S')
				MC_TOTVAL_PPh	= parseFloat(0.03 * MC_TOTVAL); 
			else
			{
				MC_TOTVAL_PPh1	= parseFloat(MC_TOTVAL / 1.03);
				MC_TOTVAL_PPh	= parseFloat(MC_TOTVAL - MC_TOTVAL_PPh1);
			}
			
			document.getElementById('MC_TOTVAL_PPh').value 	= MC_TOTVAL_PPh.toFixed(2);
			document.getElementById('MC_TOTVAL_PPhx').value = doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_TOTVAL_PPh)), 2));
			
		// TOTAL RECEIVE AFTER PPh - Net Receipt
	   		if(ownIns == 'S')
				MC_TOTVALaftPPh	= parseFloat(MC_TOTVAL + MC_TOTVAL_PPn - MC_TOTVAL_PPh);
			else
				MC_TOTVALaftPPh	= parseFloat(MC_TOTVAL + MC_TOTVAL_PPn - MC_TOTVAL_PPh);

			document.getElementById('MC_TOTPAYMENT').value 	= MC_TOTVALaftPPh.toFixed(2);
			document.getElementById('MC_TOTPAYMENTX').value = doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_TOTVALaftPPh)), 2));
    }

    function getMCTOTPPn(thisVal)
    {
		var ownIns			= document.getElementById('OWN_INST').value;
        var MC_TOTPPn		= parseFloat(eval(thisVal).value.split(",").join(""));
		var MC_PROGAPPVAL	= parseFloat(document.getElementById('MC_PROGAPPVAL').value);
		var MC_PROGCURVAL	= parseFloat(document.getElementById('MC_PROGCURVAL').value);
		var MC_RETCUTCUR 	= parseFloat(document.getElementById('MC_RETCUTCUR').value);
		var MC_DPBACKCUR 	= parseFloat(document.getElementById('MC_DPBACKCUR').value);
		var MC_TOTVAL 		= parseFloat(document.getElementById('MC_TOTVAL').value);

        document.getElementById('MC_TOTVAL_PPnx').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_TOTPPn)), 2));
        document.getElementById('MC_TOTVAL_PPn').value 		= MC_TOTPPn;

       	// MC TOTAL PPn
			MC_TOTVAL_PPn	= parseFloat(MC_TOTPPn);

			document.getElementById('MC_TOTVAL').value 		= MC_TOTVAL.toFixed(2);
			document.getElementById('MC_TOTVALx').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_TOTVAL)), 2));
			document.getElementById('MC_TOTVAL_PPn').value 	= MC_TOTVAL_PPn.toFixed(2);
			document.getElementById('MC_TOTVAL_PPnx').value = doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_TOTVAL_PPn)), 2));
			MC_TOTVALnPn	= parseFloat(MC_TOTVAL + MC_TOTVAL_PPn);
			document.getElementById('MC_TOTVALnPn').value 		= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_TOTVALnPn)), 2));
			
	   	// MC TOTAL PPh
	   		if(ownIns == 'S')
				MC_TOTVAL_PPh	= parseFloat(0.03 * MC_TOTVAL); 
			else
			{
				MC_TOTVAL_PPh1	= parseFloat(MC_TOTVAL / 1.03);
				MC_TOTVAL_PPh	= parseFloat(MC_TOTVAL - MC_TOTVAL_PPh1);
			}
			
			document.getElementById('MC_TOTVAL_PPh').value 	= MC_TOTVAL_PPh.toFixed(2);
			document.getElementById('MC_TOTVAL_PPhx').value = doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_TOTVAL_PPh)), 2));
			
		// TOTAL RECEIVE AFTER PPh - Net Receipt
			if(ownIns == 'S')
				MC_TOTVALaftPPh	= parseFloat(MC_TOTVAL + MC_TOTVAL_PPn - MC_TOTVAL_PPh);
			else
				MC_TOTVALaftPPh	= parseFloat(MC_TOTVAL + MC_TOTVAL_PPn - MC_TOTVAL_PPh);

			document.getElementById('MC_TOTPAYMENT').value 	= MC_TOTVALaftPPh.toFixed(2);
			document.getElementById('MC_TOTPAYMENTX').value = doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_TOTVALaftPPh)), 2));
    }

	function getTOTPlusPPH(thisVal)
	{
		var decFormat		= document.getElementById('decFormat').value;
        var MC_TOTVAL_PPh	= parseFloat(eval(thisVal).value.split(",").join(""));
        var MC_TOTVAL_PPn	= parseFloat(document.getElementById('MC_TOTVAL_PPn').value);
			
		document.getElementById('MC_TOTVAL_PPh').value 	= MC_TOTVAL_PPh.toFixed(2);
		document.getElementById('MC_TOTVAL_PPhx').value = doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_TOTVAL_PPh)), 2));
		
		// TOTAL RECEIVE AFTER PPh - Net Receipt
			MC_TOTVAL 		= parseFloat(document.getElementById('MC_TOTVAL').value);
			if(ownIns == 'S')
				MC_TOTVALaftPPh	= parseFloat(MC_TOTVAL + MC_TOTVAL_PPn - MC_TOTVAL_PPh);
			else
				MC_TOTVALaftPPh	= parseFloat(MC_TOTVAL + MC_TOTVAL_PPn - MC_TOTVAL_PPh);
			
			document.getElementById('MC_TOTPAYMENT').value 	= MC_TOTVALaftPPh.toFixed(2);
			document.getElementById('MC_TOTPAYMENTX').value = doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_TOTVALaftPPh)), 2));
	}
		
	function chgVALDD(thisVal)
	{
	    var decFormat		= document.getElementById('decFormat').value;
        var MC_VALADD		= parseFloat(eval(thisVal).value.split(",").join(""));
        var PRJCOSTX		= parseFloat(document.getElementById('PRJCOST').value);
		var PRJCOST_PPNP2	= parseFloat(document.getElementById('PRJCOST_PPNP2').value);
		var PRJCOST			= parseFloat(PRJCOSTX + MC_VALADD);
		var PRJCOSTPPN 		= parseFloat(PRJCOST_PPNP2 * PRJCOST);
		var PRJCOSTnPPN 	= parseFloat(PRJCOST + PRJCOSTPPN);

		document.getElementById('MC_VALADD').value 		= MC_VALADD.toFixed(2);
		document.getElementById('MC_VALADDX').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_VALADD)), 2));

		document.getElementById('PRJCOSTPPN').value 	= PRJCOSTPPN.toFixed(2);
		document.getElementById('PRJCOSTPPNX').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(PRJCOSTPPN)), 2));

		document.getElementById('PRJCOSTnPPN').value 	= PRJCOSTnPPN.toFixed(2);
		document.getElementById('PRJCOSTnPPNX').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(PRJCOSTnPPN)), 2));
	}
		
	function chgDPBack(thisVal)
	{
	    var decFormat		= document.getElementById('decFormat').value;
		var ownIns			= document.getElementById('OWN_INST').value;
        var MC_DPBACKCUR	= parseFloat(eval(thisVal).value.split(",").join(""));
        var MC_TOTVAL		= parseFloat(document.getElementById('MC_TOTVAL').value);
		var MC_PROGAPPVAL	= parseFloat(document.getElementById('MC_PROGAPPVAL').value);
		var MC_PROGCURVAL	= parseFloat(document.getElementById('MC_PROGCURVAL').value);
		var MC_RETCUTCUR 	= parseFloat(document.getElementById('MC_RETCUTCUR').value);

		document.getElementById('MC_DPBACKCUR').value 	= MC_DPBACKCUR.toFixed(2);
		document.getElementById('MC_DPBACKCUR1').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.round(MC_DPBACKCUR)), 2));

	   	// MC TOTAL
			MC_TOTVAL 		= parseFloat(MC_PROGCURVAL - MC_DPBACKCUR - MC_RETCUTCUR);

	   	// MC TOTAL PPn
			MC_TOTVAL_PPn	= parseFloat(0.1 * MC_TOTVAL);

			document.getElementById('MC_TOTVAL').value 		= MC_TOTVAL.toFixed(2);
			document.getElementById('MC_TOTVALx').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_TOTVAL)), 2));
			document.getElementById('MC_TOTVAL_PPn').value 	= MC_TOTVAL_PPn.toFixed(2);
			document.getElementById('MC_TOTVAL_PPnx').value = doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_TOTVAL_PPn)), 2));
			MC_TOTVALnPn	= parseFloat(MC_TOTVAL + MC_TOTVAL_PPn);
			document.getElementById('MC_TOTVALnPn').value 		= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_TOTVALnPn)), 2));
			
	   	// MC TOTAL PPh
	   		if(ownIns == 'S')
				MC_TOTVAL_PPh	= parseFloat(0.03 * MC_TOTVAL); 
			else
			{
				MC_TOTVAL_PPh1	= parseFloat(MC_TOTVAL / 1.03);
				MC_TOTVAL_PPh	= parseFloat(MC_TOTVAL - MC_TOTVAL_PPh1);
			}
			
			document.getElementById('MC_TOTVAL_PPh').value 	= MC_TOTVAL_PPh.toFixed(2);
			document.getElementById('MC_TOTVAL_PPhx').value = doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_TOTVAL_PPh)), 2));
			
		// TOTAL RECEIVE AFTER PPh - Net Receipt
			if(ownIns == 'S')
				MC_TOTVALaftPPh	= parseFloat(MC_TOTVAL + MC_TOTVAL_PPn - MC_TOTVAL_PPh);
			else
				MC_TOTVALaftPPh	= parseFloat(MC_TOTVAL + MC_TOTVAL_PPn - MC_TOTVAL_PPh);
			
			document.getElementById('MC_TOTPAYMENT').value 	= MC_TOTVALaftPPh.toFixed(2);
			document.getElementById('MC_TOTPAYMENTX').value = doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_TOTVALaftPPh)), 2));
	}
	
	function submitForm(value)
	{
		var FlagUSER	= document.getElementById('FlagUSER').value;
		var ISAPPROVE	= document.getElementById('ISAPPROVE').value;
		var MC_STEP		= document.getElementById('MC_STEP').value;
		var MC_PROG		= document.getElementById('MC_PROG').value;
		var MC_STATX	= document.getElementById('MC_STATX').value;
		
		//if(FlagUSER == 'APPSI')
		if(ISAPPROVE == 1)
		{
			var MC_MANNO	= document.getElementById('MC_MANNO').value;
			if(MC_MANNO == '')
			{
				swal('<?php echo $docNoEmpt; ?>',
				{
					icon:"warning",
				})
				.then(function()
				{
					document.getElementById('MC_MANNO').focus();
				});
				return false;
			}
		}
		
		if(MC_STEP == 0)
		{
			swal('<?php echo $inpMCStep; ?>',
			{
				icon:"warning",
			})
			.then(function()
			{
				document.getElementById('MC_STEP').focus();
			});
			return false;
		}
		
		if(MC_PROG == 0)
		{
			swal('<?php echo $inpProgVal; ?>',
			{
				icon:"warning",
			})
			.then(function()
			{
				document.getElementById('MC_PROG1').value = '';
				document.getElementById('MC_PROG1').focus();
			});
			return false;
		}
		
		//if(FlagUSER == 'APPSI')
		if(ISAPPROVE == 1)
		{
			var MC_PROGAPP	= parseFloat(document.getElementById('MC_PROGAPP').value);
			if(MC_PROGAPP == 0)
			{
				swal('Please input Progress Approve Presentation');
				document.getElementById('MC_PROGAPPx').focus();
				document.getElementById('MC_PROGAPPx').value = '';
				return false;
			}
		}
		
		if(MC_STATX == 3)
		{
			swal('The Document has been Approved/Closed. You can not update.',
			{
				icon:"error",
			});
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
		
		document.getElementById('MC_ENDDATE').value 	= formatDate(datey);
		document.getElementById('MC_ENDDATEx').value	= dateDesc;
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

	// NOT USED
	function getTOTDUE_XXX(thisVal)
	{
	    var decFormat	= document.getElementById('decFormat').value;
	    var thisVal		= eval(thisVal).value.split(",").join("");
	    MC_DPBACK		= thisVal;
	    document.getElementById('MC_DPBACK').value 	= MC_DPBACK;
	    document.getElementById('MC_DPBACK1').value = doDecimalFormatxx(RoundNDecimal(parseFloat(Math.round(MC_DPBACK)), 2));
	    MC_TOTPROGRESS	= document.getElementById('MC_TOTPROGRESS').value;
	    MC_RETCUT		= document.getElementById('MC_RETCUT').value;
	    MC_DPVAL		= document.getElementById('MC_DPVAL').value;
	    
	    MC_PAYBEFRET		= Math.round(parseFloat(MC_TOTPROGRESS) +  parseFloat(MC_DPVAL) -  parseFloat(MC_DPBACK) - parseFloat(MC_RETCUT));
	    document.getElementById('MC_PAYBEFRET').value 	= MC_PAYBEFRET;	// JUMLAH PEMBAYARAN SEBELUM RETENSI
		document.getElementById('MC_PAYBEFRETx').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_PAYBEFRET)), 2));
		
		MC_PAYAKUM			= Math.round(parseFloat(MC_PAYBEFRET));
		document.getElementById('MC_PAYAKUM').value 	= MC_PAYAKUM;	// JUMLAH AKUMULASI PEMBAYARAN SAMPAI SAAT INI
		document.getElementById('MC_AKUMNEXT').value 	= MC_PAYAKUM;	// DIGUNAKAN UNTUK PEMBUATAN MC SELANJUTNYA
		document.getElementById('MC_AKUMNEXTx').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.round(MC_PAYAKUM)), 2));
		MC_VALBEF			= document.getElementById('MC_VALBEF').value;
		
		MC_PAYMENT			= Math.round(MC_PAYAKUM - MC_VALBEF);
		document.getElementById('MC_PAYMENT').value 	= MC_PAYMENT;	// YANG HARUS DIBAYAR
		document.getElementById('MC_PAYMENTx').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_PAYMENT)), 2));
		
		MC_TOTVAL_PPn		= Math.round(0.1 * MC_PAYMENT);							
		document.getElementById('MC_TOTVAL_PPn').value	= MC_TOTVAL_PPn;
		document.getElementById('MC_TOTVAL_PPnx').value	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_TOTVAL_PPn)), 2));
		
		MC_PAYDUE			= parseFloat(MC_PAYMENT) + parseFloat(MC_TOTVAL_PPn);							
		
		document.getElementById('MC_PAYDUE').value = doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_PAYDUE)), 2));
		MC_PAYDUEPPh		= Math.round(0.03 * MC_PAYMENT);													
		document.getElementById('MC_TOTVAL_PPh').value	= MC_PAYDUEPPh;
		document.getElementById('MC_TOTVAL_PPhx').value	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_PAYDUEPPh)), 2));
		
		TOTPAYMENT		= parseFloat(MC_PAYDUE - MC_PAYDUEPPh);
		document.getElementById('TOTPAYMENT').value = doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(TOTPAYMENT)), 2));
	}
	
    function getRETValue_XXX(MC_RETCUTPX)	// JS_GOOD
    {
        var decFormat		= document.getElementById('decFormat').value;
        var PRJCOST			= document.getElementById('PRJCOST').value;
		var MC_PROGVAL		= document.getElementById('MC_PROGVAL').value;
		var MC_PROGCURVAL	= document.getElementById('MC_PROGCURVAL').value;
        var MC_RETCUTP		= MC_RETCUTPX.split(",").join("");

		// RETENSI AKUMULATIF -- PERMINTAAN SASMITO AGAR TIDAK MENGHITUNG OTOMATIS
        	MC_RETCUT		= parseFloat(MC_RETCUTP) * parseFloat(MC_PROGVAL) / 100;
			document.getElementById('MC_RETCUTPx').value	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_RETCUTP)), 4));
			document.getElementById('MC_RETCUTP').value	 	= MC_RETCUTP;
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