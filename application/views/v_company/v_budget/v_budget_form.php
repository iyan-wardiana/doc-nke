<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 1 Agustus 2019
	* File Name	= v_budget_form.php
	* Location		= -
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$FlagUSER 	= $this->session->userdata['FlagUSER'];
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
$appBody    = $this->session->userdata['appBody'];

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
$PRJCODE_HO 	= $PRJCODE;
/*
$AUTH_PROJECT 	= 0;
$DAU_READ 	= 0;
$sqlDAU 	= "SELECT AUTH_PROJECT FROM tbl_employee_appauth
				WHERE AUTH_EMPID = '$DefEmp_ID'";
$resultDAU 	= $this->db->query($sqlDAU)->result();
foreach($resultDAU as $rowDAU) :
	$AUTH_PROJECT 	= $rowDAU->AUTH_PROJECT;
endforeach;*/

if($task == 'add')
{
	$proj_Type = 1;
	$proj_Category = 2;
	$proj_PM_EmpID = '';
	//$proj_CustCode = '';
	$PRJCURR = 'IDR';
	$default['PRJNAME'] = '';
	foreach($viewDocPattern as $row) :
		$Pattern_Code = $row->Pattern_Code;
		$Pattern_Position = $row->Pattern_Position;
		$Pattern_YearAktive = $row->Pattern_YearAktive;
		$Pattern_MonthAktive = $row->Pattern_MonthAktive;
		$Pattern_DateAktive = $row->Pattern_DateAktive;
		//$Pattern_Length = $row->Pattern_Length;
		$Pattern_Length = 4;
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

	$this->db->where('Patt_Year', $year);
	$this->db->where('Patt_Month', $month);
	$this->db->where('Patt_Date', $date);
	$myCount = $this->db->count_all('tbl_project_budg');
	
	/*$sql = "SELECT MAX(Patt_Number) as maxNumber FROM tbl_project_budg";
	$result = $this->db->query($sql)->result();
	if($myCount>0)
	{
		foreach($result as $row) :
			$myMax = $row->maxNumber;
			$myMax = $myMax+1;
		endforeach;
	}	else	{		$myMax = 1;	}*/
	$myMax = $myCount+1;
	
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
	$DocNumber = "$Pattern_Code$groupPattern-$lastPatternNumb";
	
	$PRJDATEA 		= date('Y');
	$PRJDATEB 		= date('m');
	$PRJDATEC 		= date('d');
	$PRJDATED 		= date('d/m/Y');
	$PRJEDAT 		= "$PRJDATED";
	$PRJDATE_MNTD	= date('d/m/Y');
	$proj_addDate 	= date('d/m/Y');
	$proj_amountUSD	= 0;
	$PRJBOQ			= 0;
	$PRJRAP			= 0;
	$BASEAMOUNT		= 0;
	
	$PRJCODE 		= '';
	$PRJCODENM		= '';
	$PRJCNUM		= '';
	$PRJNAME 		= '';
	$PRJLOCT 		= '';
	$PRJOWN			= '';
	$PRJDATE 		= date('d/m/Y');
	$PRJEDAT 		= date('d/m/Y');
	$ENDDATE		= date('d/m/Y');
	$PRJDATE_CO		= date('d/m/Y');
	$PRJEDATD 		= date('d/m/Y');
	
	$PRJCOST 		= 0;
	$PRJCOST_PPNP 	= 0;
	$PRJLKOT 		= 0;
	$PRJLK_NUM 		= '';
	$PRJCBNG		= '';
	$PRJCURR		= 'IDR';
	$CURRRATE		= 1;
	$CURRRATEUSD	= 13000;
	$PRJSTAT 		= 0;
	$PRJNOTE		= '';
	$PRJLEV 		= 3;
	$Patt_Number	= $lastPatternNumb1;
	
	$ISCHANGE		= 0; 	// 0. No, 1. Yes
	$REFCHGNO		= '';	// References No.
	$PRJCOST2		= 0;	// Nilai Kontrak Baru
	$CHGUSER		= $DefEmp_ID;
	$CHGSTAT		= 0;
	$PRJ_MNG		= '';
	$QTY_SPYR		= 0;
	$PRC_STRK		= '';
	$PRC_ARST		= '';
	$PRC_MKNK		= '';
	$PRC_ELCT		= '';

	$PRJ_LROVH		= 0;
	$PRJ_LRPPH		= 0;
	$PRJ_LRBNK		= 0;
	
	if($pgFrom == 'HO')
		$PRJCATEG	= 'HO';
	else
		$PRJCATEG	= 'SPL';

	$PRJ_IMGNAME	= "building.jpg";
	$isHO			= 0;				// 0 = Project 1 = Head Office
	$PRJPERIOD		= '';
	$PRJPERIOD_P	= '';
	$PRJ_FDESC 		= "";
	$PRJ_ACCUM 		= '';
}
else
{
	$DocNumber 			= $default['proj_Number'];	
	$proj_Number 		= $default['proj_Number'];
	$PRJCODE 			= $default['PRJCODE'];
	$PRJCODE_HO			= $default['PRJCODE_HO'];
	$PRJPERIOD 			= $default['PRJPERIOD'];
	$PRJPERIOD_P		= $default['PRJPERIOD_P'];
	$PRJCNUM			= $default['PRJCNUM'];
	$PRJNAME 			= $default['PRJNAME'];
	$PRJLOCT 			= $default['PRJLOCT'];
	$PRJCATEG 			= $default['PRJCATEG'];
	$PRJOWN 			= $default['PRJOWN'];
	$PRJDATE 			= $default['PRJDATE'];
	$PRJDATE_CO 		= $default['PRJDATE_CO'];
	$PRJDATE_MNT 		= $default['PRJDATE_MNT'];
	if($PRJDATE_MNT == '0000-00-00' || $PRJDATE_MNT == '')
		$PRJDATE_MNTD 	= date('d/m/Y');
	else
		$PRJDATE_MNTD 	= date('d/m/Y', strtotime($default['PRJDATE_MNT']));
		
	$PRJEDAT 			= $default['PRJEDAT'];
	$ENDDATE			= $default['PRJEDAT'];
	$PRJLEV				= $default['PRJLEV'];
	$isHO				= $default['isHO'];
	
	$sqlPrj				= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
	$resPrj 			= $this->db->query($sqlPrj)->result();
	foreach($resPrj as $rowPrj) :
		$PRJCODENM 		= $rowPrj->PRJNAME;		
	endforeach;
	
	$PRJ_FDESC 			= "";
	$PRJ_FNAME 			= "";
	$sqlPrjD			= "SELECT PRJ_FDESC, PRJ_FNAME FROM tbl_project_doc WHERE PRJCODE = '$PRJCODE'";
	$resPrjD 			= $this->db->query($sqlPrjD)->result();
	foreach($resPrjD as $rowPrjD) :
		$PRJ_FDESC 		= $rowPrjD->PRJ_FDESC;
		$PRJ_FNAME 		= $rowPrjD->PRJ_FNAME;
	endforeach;
	
	$PRJDATED 		= date('d/m/Y', strtotime($PRJDATE));
	$PRJEDATD 		= date('d/m/Y', strtotime($PRJEDAT));
	$PRJDATE_CO 	= date('d/m/Y', strtotime($PRJDATE_CO));
	
	$sqlsp0		= "SELECT ENDDATE FROM tbl_projhistory WHERE PRJCODE = '$PRJCODE'";
	$sqlsp0R	= $this->db->query($sqlsp0)->result();
	foreach($sqlsp0R as $rowsp0) :
		$ENDDATE		= $rowsp0->ENDDATE;
		$ENDDATE 		= date("d/m/Y", strtotime($ENDDATE));
	endforeach;

	$PRJCOST 			= $default['PRJCOST'];	// NILAI KONTRAK
	$PRJCOST_PPNP 		= $default['PRJCOST_PPNP'];
	$proj_amountUSD		= $default['PRJCOST'];
	$PRJBOQ				= $default['PRJBOQ'];	// NILAI KONTRAK	
	$PRJRAP				= $default['PRJRAP'];
	$PRJLKOT 			= $default['PRJLKOT'];
	$PRJLK_NUM 			= $default['PRJLK_NUM'];
	$PRJCBNG			= $default['PRJCBNG'];
	$PRJCURR			= $default['PRJCURR'];
	$CURRRATE			= $default['CURRRATE'];
	$CURRRATEUSD		= $default['CURRRATE'];
	$PRJSTAT 			= $default['PRJSTAT'];
	$PRJNOTE			= $default['PRJNOTE'];
	$PRJ_MNG			= $default['PRJ_MNG'];
	$QTY_SPYR			= $default['QTY_SPYR'];
	$PRC_STRK			= $default['PRC_STRK'];
	$PRC_ARST			= $default['PRC_ARST'];
	$PRC_MKNK			= $default['PRC_MKNK'];
	$PRC_ELCT			= $default['PRC_ELCT'];
	$PRJ_ACCUM			= $default['PRJ_ACCUM'];
	$PRJ_IMGNAME		= $default['PRJ_IMGNAME'];
	$Patt_Year 			= $default['Patt_Year'];
	$Patt_Number 		= $default['Patt_Number'];
	$lastPatternNumb1	= $Patt_Number;
	
	if($PRJCURR == 'USD')
	{
		$BASEAMOUNT	= $proj_amountUSD * $CURRRATEUSD;
	}
	else
	{
		$BASEAMOUNT	= $PRJCOST;
	}
	
	$ISCHANGE		= $default['ISCHANGE']; // 0. No, 1. Yes
	$REFCHGNO		= $default['REFCHGNO'];	// References No.
	$PRJCOST2		= $default['PRJCOST2'];	// Nilai Kontrak Baru
	$CHGUSER		= $default['CHGUSER'];
	$CHGSTAT		= $default['CHGSTAT'];

	$PRJ_LROVH		= $default['PRJ_LROVH'];
	$PRJ_LRPPH		= $default['PRJ_LRPPH'];
	$PRJ_LRBNK		= $default['PRJ_LRBNK'];
}

$sqlCOACM 	= "tbl_chartaccount WHERE PRJCODE = '$PRJCODE_HO'";
$resCOACM 	= $this->db->count_all($sqlCOACM);

$sqlJLMCM 	= "tbl_joblist_detail WHERE PRJCODE = '$PRJCODE_HO'";
$resJLMCM 	= $this->db->count_all($sqlJLMCM);

$PRJCODEHO 	= $PRJCODE_HO;
if($resCOACM == 0)
	$PRJCODEHO = 0;

$PRJSCATEG	= 0;
$sqlPrjHO	= "SELECT PRJSCATEG FROM tbl_project WHERE PRJCODE = '$PRJCODE_HO'";
$resPrjHO 	= $this->db->query($sqlPrjHO)->result();
foreach($resPrjHO as $rowPrjHO) :
	$PRJSCATEG 	= $rowPrjHO->PRJSCATEG;		
endforeach;
$PRJSCATEG 	= $PRJSCATEG ?: 0;

if($PRJCURR == '')
{
	$PRJCURR = 'IDR';
}

$imgLoc		= base_url('assets/AdminLTE-2.0.5/project_image/'.$PRJCODE.'/'.$PRJ_IMGNAME);
if (!file_exists('assets/AdminLTE-2.0.5/project_image/'.$PRJCODE))
{
	$imgLoc	= base_url('assets/AdminLTE-2.0.5/project_image/building.jpg');
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
            $vers 	= $this->session->userdata['vers'];

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
	  	<link rel="stylesheet" href="<?php echo base_url() . 'assets/css/pbar/css/cssprogress.css'; ?>">
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
			
			if($TranslCode == 'Budgeting')$Budgeting = $LangTransl;
			if($TranslCode == 'BudNo')$BudNo = $LangTransl;
			if($TranslCode == 'BudCode')$BudCode = $LangTransl;
			if($TranslCode == 'ProjectName')$ProjectName = $LangTransl;
			if($TranslCode == 'StartDate')$StartDate = $LangTransl;
			if($TranslCode == 'CutOffDate')$CutOffDate = $LangTransl;
			if($TranslCode == 'EndDate')$EndDate = $LangTransl;
			if($TranslCode == 'CompanyName')$CompanyName = $LangTransl;
			if($TranslCode == 'ContractNo')$ContractNo = $LangTransl;
			if($TranslCode == 'Category')$Category = $LangTransl;
			if($TranslCode == 'ProjectOwner')$ProjectOwner = $LangTransl;
			if($TranslCode == 'Amount')$Amount = $LangTransl;
			if($TranslCode == 'Location')$Location = $LangTransl;
			if($TranslCode == 'OutofTownProject')$OutofTownProject = $LangTransl;
			if($TranslCode == 'Compailer')$Compailer = $LangTransl;
			if($TranslCode == 'SurveyorQty')$SurveyorQty = $LangTransl;
			if($TranslCode == 'Notes')$Notes = $LangTransl;
			if($TranslCode == 'PlannedBy')$PlannedBy = $LangTransl;
			if($TranslCode == 'Structure')$Structure = $LangTransl;
			if($TranslCode == 'Architect')$Architect = $LangTransl;
			if($TranslCode == 'Mechanical')$Mechanical = $LangTransl;
			if($TranslCode == 'Electrical')$Electrical = $LangTransl;
			if($TranslCode == 'ProjectStatus')$ProjectStatus = $LangTransl;
			if($TranslCode == 'New')$New = $LangTransl;
			if($TranslCode == 'Approve')$Approve = $LangTransl;
			if($TranslCode == 'Save')$Save = $LangTransl;
			if($TranslCode == 'Update')$Update = $LangTransl;
			if($TranslCode == 'Cancel')$Cancel = $LangTransl;
			if($TranslCode == 'Active')$Active = $LangTransl;
			if($TranslCode == 'Use')$Use = $LangTransl;
			if($TranslCode == 'Freeze')$Freeze = $LangTransl;
			if($TranslCode == 'Inactive')$Inactive = $LangTransl;
			if($TranslCode == 'Project')$Project = $LangTransl;
			if($TranslCode == 'BudgTotal')$BudgTotal = $LangTransl;
			if($TranslCode == 'Target')$Target = $LangTransl;
			if($TranslCode == 'Margin')$Margin = $LangTransl;
			if($TranslCode == 'AboutProject')$AboutProject = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'budgDesc')$budgDesc = $LangTransl;
			if($TranslCode == 'Information')$Information = $LangTransl;
			if($TranslCode == 'Picture')$Picture = $LangTransl;
			if($TranslCode == 'Add')$Add = $LangTransl;
			if($TranslCode == 'None')$None = $LangTransl;
			if($TranslCode == 'Code')$Code = $LangTransl;
			if($TranslCode == 'ProjectName')$ProjectName = $LangTransl;
			if($TranslCode == 'ChooseFile')$ChooseFile = $LangTransl;
			if($TranslCode == 'UploadDoc')$UploadDoc = $LangTransl;
			if($TranslCode == 'DetInfo')$DetInfo = $LangTransl;
			if($TranslCode == 'important')$important = $LangTransl;
			if($TranslCode == 'ParentGroup')$ParentGroup = $LangTransl;
			if($TranslCode == 'ParentGroupName')$ParentGroupName = $LangTransl;
			if($TranslCode == 'PeriodeCode')$PeriodeCode = $LangTransl;
			if($TranslCode == 'codeExist')$codeExist = $LangTransl;
			if($TranslCode == 'budEmpt')$budEmpt = $LangTransl;
			if($TranslCode == 'budCEmpt')$budCEmpt = $LangTransl;
			if($TranslCode == 'selPG')$selPG = $LangTransl;
			if($TranslCode == 'ProjectManager')$ProjectManager = $LangTransl;
			if($TranslCode == 'Back')$Back = $LangTransl;
			if($TranslCode == 'eDMTsD')$eDMTsD = $LangTransl;
			if($TranslCode == 'compStatEmpy')$compStatEmpy = $LangTransl;
			if($TranslCode == 'DocFile')$DocFile = $LangTransl;
			if($TranslCode == 'Budget')$Budget = $LangTransl;
			if($TranslCode == 'Date')$Date = $LangTransl;
			if($TranslCode == 'View')$View = $LangTransl;
			if($TranslCode == 'projDoc')$projDoc = $LangTransl;
			if($TranslCode == 'Others')$Others = $LangTransl;
			if($TranslCode == 'umTP')$umTP = $LangTransl;
			if($TranslCode == 'Yes')$Yes = $LangTransl;
			if($TranslCode == 'No')$No = $LangTransl;
			if($TranslCode == 'ImpDate')$ImpDate = $LangTransl;
			if($TranslCode == 'MaintDate')$MaintDate = $LangTransl;
		endforeach;
		$urlUpdPic		= site_url('c_comprof/c_bUd93tL15t/do_uploadPRJ/?id='.$this->url_encryption_helper->encode_url($appName));
		$urlUpdDoc		= site_url('c_comprof/c_bUd93tL15t/do_uploadPRJDOC/?id='.$this->url_encryption_helper->encode_url($appName));
		$urlUpOthSet	= site_url('c_comprof/c_bUd93tL15t/do_uploadPRJOTHSet/?id='.$this->url_encryption_helper->encode_url($appName));
		$urlprjAcc		= site_url('c_comprof/c_bUd93tL15t/upProjAcc/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
		
		if($LangID == 'IND')
		{
			$Yes		= "Ya";
			$No			= "Bukan";
			$InfoDet	= "Halaman informasi detail anggaran hanya bisa dibuka pada saat update anggaran.";
			$DescNull	= "Deskripsi file / dokumen yang diupload tidak boleh kosong.";
			$FileNull	= "Tidak ada file yang akan diuplaod.";
			$alertLKNUM	= "Kode proyek luar kota tidak boleh kosong";
			$alertCB    = "Anda belum / tidak menentukan akun kas & bank proyek.";
		}
		else
		{
			$Yes		= "Yes";
			$No			= "No";
			$InfoDet	= "Budget information detail page only opened when you budget update position.";
			$DescNull	= "File / Document description can not be empty.";
			$FileNull	= "File can not be empty.";
			$alertLKNUM	= "Project code LK can not empty";
			$alertCB     = "You do not select an Account(s) project cash & bank";
		}
		$isLoadDone_1	= 1;
	?>
			
	<style>
		.search-table, td, th {
			border-collapse: collapse;
		}
		.search-table-outter { overflow-x: scroll; }
	</style>
	<?php
		$comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
			<h1>
			    <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/project.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $Add; ?>
			    <small><?php echo $mnName; ?></small>
			  </h1>
		</section>

	    <section class="content">
			<div class="row">
				<div class="col-md-3">
				  	<!-- Profile Image -->
				  	<div class="box box-primary">
				        <div class="box-body box-profile">
				            <img class="profile-user-img img-responsive img-circle" src="<?php echo $imgLoc; ?>" style="height:150px; width:150px" alt="User profile picture">
				            <h3 class="profile-username text-center"><?php echo "$PRJNAME"; ?></h3>                    
				            <p class="text-muted text-center"><?php echo $PRJLOCT; ?></p>
				            <ul class="list-group list-group-unbordered">
				            	<li class="list-group-item">
				            		<b><?php echo $BudgTotal; ?></b> <a class="pull-right"><?php print number_format($PRJBOQ, 0); ?></a>
				            	</li>
				            	<li class="list-group-item">
				            		<b><?php echo "RAB"; ?></b> <a class="pull-right"><?php print number_format($PRJRAP, 0); ?></a>
				            	</li>
				                <?php
									if($PRJBOQ == '')
										$PRJBOQ = $PRJCOST;
										
				                	$TargetFee	= $PRJBOQ - $PRJRAP;
								?>
				            	<li class="list-group-item">
				            		<b><?php echo "$Target $Margin"; ?></b> <a class="pull-right"><?php print number_format($TargetFee, 0); ?></a>
				            	</li>
				            </ul>
				            <a href="#" class="btn btn-primary btn-block" style="display:none"><b>Follow</b></a>
				        </div>
				  	</div>

		          <!-- About Me Box -->
		          	<div class="box box-primary" style="display: none;">
		                <div class="box-header with-border">
		                	<h3 class="box-title"><?php echo $AboutProject; ?></h3>
		                </div>
		                <!-- /.box-header -->
		                <div class="box-body">
		                	<strong><i class="fa fa-book margin-r-5"></i> <?php echo $Description; ?></strong>
		                	<p class="text-muted">
		                		<em><?php echo $PRJNOTE; ?></em>
		                	</p>
		               		<hr>
		                    <strong><i class="fa fa-map-marker margin-r-5"></i> <?php echo $Location; ?></strong>
		                    <p class="text-muted"><?php echo $PRJLOCT; ?></p>
		                	<hr>
		                	<strong style="display:none"><i class="fa fa-file-text-o margin-r-5"></i> <?php echo $Notes; ?></strong>
		                    <p style="display:none"><em><?php echo $None; ?></em></p>
		                </div>
		            </div>
				</div>
				<div class="col-md-9">
					<div class="nav-tabs-custom">
				    	<ul class="nav nav-tabs">
				        	<li class="active"><a href="#budgInfo" data-toggle="tab" onclick="showTab(1);"><?php echo $Information; ?></a></li>
				           	<li><a href="#budDoc" data-toggle="tab" onclick="showTab(2);"><?php echo $projDoc; ?></a></li>
				            <li><a href="#profPicture" data-toggle="tab" onclick="showTab(3);"><?php echo $Picture; ?></a></li>
							<li><a href="#prjAcc" data-toggle="tab" onclick="showTab(4);"><?php echo "Project Account"; ?></a></li>
				            <li><a href="#othSett" data-toggle="tab" onclick="showTab(5);"><?php echo $Others; ?></a></li>
				        </ul>
				        <!-- Biodata -->
				        <div class="tab-content">
				            <div class="active tab-pane" id="budgInfo">
				                <form class="form-horizontal" name="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return checkInp()">
				                    <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
				                    <input type="hidden" name="rowCount" id="rowCount" value="0">
				                    <input type="hidden" name="PRJLEV" id="PRJLEV" value="<?=$PRJLEV?>">
				                    <input type="hidden" name="PRJCNUM" id="PRJCNUM" value="">
				                    <input type="hidden" name="PRJCATEG" id="PRJCATEG" value="SPL">
				                    <input type="hidden" name="isHO" id="isHO" value="0">
				                    <input type="hidden" name="PRJOWN" id="PRJOWN" value="<?php echo $PRJOWN; ?>">
				                    <input type="hidden" name="pgFrom" id="pgFrom" value="<?php echo $pgFrom; ?>">
				                    <div class="box box-primary">
		                                <div class="box-header with-border" style="display: none;">
		                                    <h3 class="box-title"><?php echo $Information; ?></h3>
		                                </div>
				                        <br>
				                        <div class="form-group"> <!-- proj_Number : GENERATE CODE BUDGET -->
				                            <label for="inputName" class="col-sm-2 control-label"><!-- <?php echo $BudNo?> --><?php echo $ParentGroup; ?></label>
				                            <div class="col-sm-4" style="display: none;">
				                                <input type="text" class="form-control" name="PRJNUM" id="PRJNUM" value="<?php echo $DocNumber; ?>" disabled>
				                                <input type="hidden" name="proj_Number" id="proj_Number" value="<?php echo $DocNumber; ?>" >
				                            </div>
				                            <div class="col-sm-6">
				                                <select name="PRJCODE_HO" id="PRJCODE_HO" class="form-control select2" onChange="chkPrd(this.value)">
				                                    <option value="0" > --- </option>
				                                    <?php
				                                        $PRJ_Code 	= '';
				                                        $sqlPRJ		= "tbl_project WHERE PRJTYPE IN (1,2)";
														$resPRJ 	= $this->db->count_all($sqlPRJ);

				                                        $sqlPRJ 	= "SELECT PRJCODE, PRJCODE_HO, PRJPERIOD, PRJPERIOD_P, PRJNAME
																		FROM tbl_project WHERE PRJTYPE IN (1,2)";
				                                        $resultPRJ 	= $this->db->query($sqlPRJ)->result();
				                                        if($resPRJ > 0)
				                                        {
				                                            foreach($resultPRJ as $rowPRJ) :
				                                                $PRJCODE1 		= $rowPRJ->PRJCODE;
				                                                $PRJCODE_HO1	= $rowPRJ->PRJCODE_HO;
				                                                $PRJPERIOD1		= $rowPRJ->PRJPERIOD;
				                                                $PRJPERIOD_P1	= $rowPRJ->PRJPERIOD_P;
				                                                $PRJNAME1		= $rowPRJ->PRJNAME;
																
																$sqlCOAC 		= "tbl_chartaccount WHERE PRJCODE = '$PRJCODE1'";
																$resCOAC 		= $this->db->count_all($sqlCOAC);
																
																$sqlJLMC 		= "tbl_joblist_detail WHERE PRJCODE = '$PRJCODE1'";
																$resJLMC 		= $this->db->count_all($sqlJLMC);
																
																$isDis			= 0;
																$DescNote1		= '';
																$DescNote2		= '';
																//if($resCOAC == 0 || $resJLMC == 0)
																if($resCOAC == 0)
																{
																	$isDis		= 1;
																	$DescNote1	= "-- No Master COA";
																}

																if($PRJCODE1 != $PRJCODE_HO)
																{
																	$isDis		= 1;
																	$DescNote1	= "";
																}
																/*if($resJLMC == 0)
																{
																	$isDis		= 1;
																	$DescNote2	= "-- No Master Budget";
																}*/
				                                                ?>
				                                                <option value="<?php echo $PRJCODE1; ?>"
				                                                	<?php 
					                                                	if($isDis == 0) 
				                                                		{ 
				                                                			if($PRJCODE1 == trim($PRJCODEHO)) 
				                                                			{
				                                                				?> selected <?php 
				                                                			}
				                                                		} 
				                                                		else 
			                                                			{ 
			                                                				?> disabled <?php 
			                                                			} ?>>
			                                                			<?php echo "$PRJCODE1 - $PRJNAME1 $DescNote1$DescNote2"; ?>
			                                                	</option>
					                                            <?php
															endforeach;
				                                         }
				                                    ?>
				                                </select>
				                            </div>
				                            <div class="col-sm-4">
				                                <input type="text" class="form-control" name="PRJPERIOD" id="PRJPERIOD" value="<?php echo $PRJPERIOD; ?>" placeholder="<?php echo $BudCode; ?>" maxlength="20" onChange="functioncheck(this.value)" <?php if($task == 'edit') { ?>readonly <?php } ?>>
				                                <label id="isHidden" style="display: none;"></label>
				                                <input type="hidden" name="CheckThe_Code" id="CheckThe_Code" value="" size="20" maxlength="25" >
				                            </div>
				                        </div>
				                        <script>
											function chkPrd(prdSel)
											{
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
															xSplit		= xmlhttpTask.responseText;
															var ySplit 	= xSplit.split("~");
															//document.getElementById('PRJCODE_HO').value  	= ySplit[0];
															document.getElementById('PRJCODENM').value  	= ySplit[1];
														}
														else
														{
															//document.getElementById('PRJCODE_HO').value  	= 'none';
															document.getElementById('PRJCODENM').value  	= 'none';
														}
													}
												}
												xmlhttpTask.open("GET","<?php echo base_url().'index.php/c_comprof/c_bUd93tL15t/getComp/';?>"+prdSel,true);
												xmlhttpTask.send();
											}
										</script>
				                        <div class="form-group" style="display: none;">  <!--- PRJCODENM --->
				                            <label for="inputName" class="col-sm-2 control-label"><?php echo $ParentGroupName; ?></label>
				                            <div class="col-sm-10">
				                                <input type="text" class="form-control" name="PRJCODENM" id="PRJCODENM" value="<?php echo $PRJCODENM; ?>" readonly>
				                            </div>
				                        </div>
				                        <script>
				                            function functioncheck(myValue)
				                            {
				                                var ajaxRequest;
				                                try
				                                {
				                                    ajaxRequest = new XMLHttpRequest();
				                                }
				                                catch (e)
				                                {
				                                    alert("Something is wrong");
				                                    return false;
				                                }
				                                ajaxRequest.onreadystatechange = function()
				                                {
				                                    if(ajaxRequest.readyState == 4)
				                                    {
				                                        recordcount = ajaxRequest.responseText;

				                                        if(recordcount > 0)
				                                        {
				                                            document.getElementById('CheckThe_Code').value= recordcount;
				                                            /*document.getElementById("isHidden").innerHTML = ' Project Code already exist ... !';
				                                            document.getElementById("isHidden").style.color = "#ff0000";*/
				                                            document.getElementById("btnSave").style.display = "none";
															swal({
											                    icon: "error",
											                    text: "<?php echo $codeExist; ?>",
											                    closeOnConfirm: false
											                })
											                .then(function()
											                {
										                        swal.close();
										                        $('#PRJPERIOD').focus();

											                });
															document.getElementById('PRJPERIOD').value = '';
				                                        }
				                                        else
				                                        {
				                                            document.getElementById('CheckThe_Code').value= recordcount;
				                                            /*document.getElementById("isHidden").innerHTML = ' Project Code : OK .. !';
				                                            document.getElementById("isHidden").style.color = "green";*/
				                                            document.getElementById("btnSave").style.display = "";
				                                        }
				                                    }
				                                }
				                                var PRJPERIOD = document.getElementById('PRJPERIOD').value;
												
				                                ajaxRequest.open("GET", "<?php echo base_url().'index.php/c_comprof/c_bUd93tL15t/getTheCode/';?>" + PRJPERIOD, true);
				                                ajaxRequest.send(null);
				                            }
				                        </script>
				                        <div class="form-group"> <!-- PRJNAME : NAMA ANGGARAN -->
				                            <label for="inputName" class="col-sm-2 control-label"><?php echo $Description?></label>
				                            <div class="col-sm-6">
				                                <input type="text" class="form-control" name="PRJNAME" id="PRJNAME" value="<?php echo $default['PRJNAME']; ?>" placeholder="<?php echo $Description; ?>" >
				                            </div>
				                            <div class="col-sm-4">
				                            	<input type="text" class="form-control" name="PRJLOCT" id="PRJLOCT" value="<?php echo $PRJLOCT; ?>" maxlength="50" placeholder="<?php echo $Location; ?>">
				                            </div>
				                        </div>
				                        <div class="form-group"> <!-- PRJDATE -->
				                            <label for="inputName" class="col-sm-2 control-label"><?php echo $StartDate; ?></label>
				                            <div class="col-sm-3">
				                                <div class="input-group date">
					                            	<div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
			                                    	<input type="text" name="PRJDATE" class="form-control pull-left" id="datepicker1" value="<?php echo $PRJDATED; ?>">
			                                    </div>
				                            </div>
				                            <label for="inputName" class="col-sm-3 control-label"><?php echo "Proyek Luar Kota ?"; ?></label>
				                            <div class="col-sm-4">
				                            	<label>
				                                    <input type="radio" name="PRJLKOT" id="PRJLKOT0" class="flat-red" value="0" <?php echo $PRJLKOT == 0 ? 'checked':''; ?> /> 
				                                    <?php echo $No; ?> &nbsp;&nbsp;&nbsp;&nbsp;
				                                    <input type="radio" name="PRJLKOT" id="PRJLKOT1" class="flat-red" value="1" <?php echo $PRJLKOT == 1 ? 'checked':''; ?> /> 
				                                    <?php echo $Yes; ?>
				                                </label>
				                            </div>
				                        </div>
                                        <div class="form-group">
				                            <label for="inputName" class="col-sm-2 control-label"><?php echo $EndDate; ?></label>
				                            <div class="col-sm-3">
				                                <div class="input-group date">
				                                    <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
				                                	<input type="text" name="PRJEDAT" class="form-control pull-left" id="datepicker2" value="<?php echo $PRJEDATD; ?>">
				                                </div>
				                            </div>
				                            <label for="inputName" class="col-sm-3 control-label"><?php echo "Kode Proyek Luar Kota"; ?></label>
				                            <div class="col-sm-4">
				                            	<input type="text" class="form-control" name="PRJLK_NUM" id="PRJLK_NUM" value="<?php echo $PRJLK_NUM; ?>" placeholder="<?php echo "Kode Proyek Luar Kota"; ?>" <?php echo $PRJLKOT == 0 ? 'disabled':''; ?>>
				                            </div>
				                        </div>
										<div class="form-group">
											<label for="inputName" class="col-sm-2 control-label"><?php echo $MaintDate; ?></label>
		                                    <div class="col-sm-3">
		                                        <div class="input-group date">
		                                            <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
		                                            <input type="text" name="PRJDATE_MNT" class="form-control pull-left" id="datepicker3" value="<?php echo $PRJDATE_MNTD; ?>">
		                                        </div>
		                                    </div>
										</div>
				                        <div class="form-group" style="display: none;"> <!-- PRJDATE_CO -->
				                            <label for="inputName" class="col-sm-2 control-label"><?php echo $CutOffDate; ?></label>
				                            <div class="col-sm-10">
				                                <div class="input-group date">
				                                    <div class="input-group-addon">
				                                <i class="fa fa-calendar"></i>&nbsp;</div><input type="text" name="PRJDATE_CO" class="form-control pull-left" id="datepicker3" value="<?php echo $PRJDATE_CO; ?>" style="width:150px"></div>
				                            </div>
				                        </div>
				                        <div class="form-group"> <!-- PRJCOST -->
                                            <div class="col-sm-6">
						                        <?php if($PRJSCATEG == 1) { ?>
	                                                <div class="form-group">
		                                            	<label for="inputName" class="col-sm-4 control-label"><?php echo $Category ?></label>
		                                            	<div class="col-sm-8">
							                                <select name="PRJCATEG" id="PRJCATEG" class="form-control select2">
					                                            <option value=""> --- </option>
					                                            <option value="HO" <?php if($PRJCATEG == 'HO') { ?> selected <?php } if($pgFrom == 'PRY') { ?> disabled <?php } ?>>Kantor Pusat</option>
					                                            <option value="SPL" <?php if($PRJCATEG == 'SPL') { ?> selected <?php } if($pgFrom == 'HO') { ?> disabled <?php } ?>>Sipil</option>
					                                            <option value="GDG" <?php if($PRJCATEG == 'GDG') { ?> selected <?php } if($pgFrom == 'HO') { ?> disabled <?php } ?>>Gedung</option>
					                                        </select>
							                            </div>
							                        </div>
	                                                <div class="form-group">
							                            <label for="inputName" class="col-sm-4 control-label"><?php echo $ContractNo?></label>
					                                    <div class="col-sm-8">
					                                        <input type="text" class="form-control" name="PRJCNUM" id="PRJCNUM" value="<?php echo $PRJCNUM; ?>"  placeholder="<?php echo $ContractNo ?>">
					                                    </div>
							                        </div>
						                        <?php } ?>
                                                <div class="form-group">
						                            <label for="inputName" class="col-sm-4 control-label"><?php echo $Amount; ?></label>
						                            <div class="col-sm-5">
					                                    <input type="hidden" name="PRJCOST" id="PRJCOST" value="<?php echo $PRJCOST; ?>"  size="10" style="text-align:right" onKeyPress="return isIntOnlyNew(event);">
					                                    <input type="text" class="form-control" name="PRJCOST1" id="PRJCOST1" value="<?php print number_format($PRJCOST, $decFormat); ?>" style="text-align:right" onKeyPress="return isIntOnlyNew(event);" onChange="checkdecimal();" >
						                            </div>
						                            <div class="col-sm-3">
					                                    <input type="hidden" name="PRJCOST_PPNP" id="PRJCOST_PPNP" value="<?php echo $PRJCOST_PPNP; ?>"  size="10" style="text-align:right" onKeyPress="return isIntOnlyNew(event);">
					                                    <input type="text" class="form-control" name="PRJCOST_PPNPX" id="PRJCOST_PPNPX" value="<?php print number_format($PRJCOST_PPNP, $decFormat); ?>" style="text-align:right" onKeyPress="return isIntOnlyNew(event);" onChange="chgPRJDP(this);" >
						                            </div>
						                        </div>
                                                <div class="form-group">
						                            <label for="inputName" class="col-sm-4 control-label">&nbsp;</label>
						                            <div class="col-sm-4">
	                                    				<label for="exampleInputEmail1">Overhead (%)</label>
					                                    <input type="hidden" name="PRJ_LROVH" id="PRJ_LROVH" value="<?php echo $PRJ_LROVH; ?>"  size="10" style="text-align:right" onKeyPress="return isIntOnlyNew(event);">
					                                    <input type="text" class="form-control" name="PRJ_LROVHX" id="PRJ_LROVHX" value="<?php print number_format($PRJ_LROVH, 3); ?>" style="text-align:right" onKeyPress="return isIntOnlyNew(event);" onChange="chgOVH();" >
						                            </div>
						                            <div class="col-sm-4">
	                                    				<label for="exampleInputEmail1">PPh (%)</label>
					                                    <input type="hidden" name="PRJ_LRPPH" id="PRJ_LRPPH" value="<?php echo $PRJ_LRPPH; ?>"  size="10" style="text-align:right" onKeyPress="return isIntOnlyNew(event);">
					                                    <input type="text" class="form-control" name="PRJ_LRPPHX" id="PRJ_LRPPHX" value="<?php print number_format($PRJ_LRPPH, 3); ?>" style="text-align:right" onKeyPress="return isIntOnlyNew(event);" onChange="chgPPH(this);" >
						                            </div>
						                        </div>
                                                <div class="form-group">
	                                            	<label for="inputName" class="col-sm-4 control-label"><?php if($PRJSCATEG == 1) echo $ProjectManager; else echo $Compailer; ?></label>
	                                            	<div class="col-sm-8">
						                                <select name="PRJ_MNG[]" id="PRJ_MNG" class="form-control select2" multiple="multiple" data-placeholder="&nbsp;&nbsp;&nbsp;<?php if($PRJSCATEG == 1) echo $ProjectManager; else echo $Compailer; ?>">
						                                    <?php
						                                        $sqlEmp	= "SELECT Emp_ID, First_Name, Last_Name, Email
						                                                    FROM tbl_employee ORDER BY First_Name";
						                                        $sqlEmp	= $this->db->query($sqlEmp)->result();
						                                        foreach($sqlEmp as $row) :
						                                            $Emp_ID		= $row->Emp_ID;
						                                            $First_Name	= $row->First_Name;
						                                            $Last_Name	= $row->Last_Name;
						                                            $Email		= $row->Email;
						                                            ?>
						                                                <option value="<?php echo "$Emp_ID"; ?>" <?php if($PRJ_MNG == $Emp_ID) { ?> selected <?php } ?>>
						                                                    <?php echo "$First_Name $Last_Name"; ?>
						                                                </option>
						                                            <?php
						                                        endforeach;
						                                    ?>
						                                </select>
						                            </div>
						                        </div>
						                    </div>
                                            <div class="col-sm-6">
						                        <?php if($PRJSCATEG == 1) { ?>
					                                <div class="form-group">
					                                    <div class="col-sm-12">
					                                        <select name="PRJOWN" id="PRJOWN" class="form-control select2">
					                                            <option value="none"> --- </option>
					                                            <?php
					                                                $own_Code 	= '';
					                                                $CountOwn 	= $this->db->count_all('tbl_owner');
					                                                $sqlOwn 	= "SELECT own_Code, own_Title, own_Name 
																					FROM tbl_owner WHERE own_Status = 1
																					UNION ALL
																					SELECT CUST_CODE AS own_Code, '' AS own_Title,
																						CUST_DESC AS own_Name 
																					FROM tbl_customer WHERE CUST_STAT = 1";
					                                                $resultOwn = $this->db->query($sqlOwn)->result();
					                                                if($CountOwn > 0)
					                                                {
					                                                    foreach($resultOwn as $rowOwn) :
					                                                        $own_Title = $rowOwn->own_Title;
					                                                        $own_Code = $rowOwn->own_Code;
					                                                        $own_Name = $rowOwn->own_Name;
					                                                        ?>
					                                                        <option value="<?php echo $own_Code; ?>" <?php if($own_Code == $PRJOWN) { ?>selected <?php } ?>> <?php echo $own_Name; if($own_Title != '') { echo", $own_Title"; } ?> </option>
					                                                        <?php
					                                                     endforeach;
					                                                }
					                                            ?>
					                                        </select>
					                                    </div>
					                                </div>
						                        <?php } ?>
				                                <div class="form-group">
				                                    <div class="col-sm-12">
		                                            	<textarea class="form-control" name=" PRJNOTE"  id="PRJNOTE" style="height:85px" placeholder="<?php echo $Notes; ?>"><?php echo set_value('PRJNOTE', isset($default['PRJNOTE']) ? $default['PRJNOTE'] : ''); ?></textarea>
		                                            </div>
		                                        </div>
                                                <div class="form-group">
						                            <div class="col-sm-4">
	                                    				<label for="exampleInputEmail1">Bunga Bank (%)</label>
					                                    <input type="hidden" name="PRJ_LRBNK" id="PRJ_LRBNK" value="<?php echo $PRJ_LRBNK; ?>"  size="10" style="text-align:right" onKeyPress="return isIntOnlyNew(event);">
					                                    <input type="text" class="form-control" name="PRJ_LRBNKX" id="PRJ_LRBNKX" value="<?php print number_format($PRJ_LRBNK, 3); ?>" style="text-align:right" onKeyPress="return isIntOnlyNew(event);" onChange="chgBNK();" >
						                            </div>
						                        </div>
				                                <div class="form-group">
				                                    <div class="col-sm-12">
		                                            	<select name="PRJSTAT" id="PRJSTAT" class="form-control select2">
						                                    <option value="0" > --- </option>
						                                    <option value="1" <?php if($PRJSTAT == 1) { ?> selected <?php } ?>><?php echo $Active; ?></option>
						                                    <!-- <option value="3" <?php if($PRJSTAT == 3) { ?> selected <?php } ?>><?php echo $Freeze; ?></option> -->
						                                    <option value="2" <?php if($PRJSTAT == 2) { ?> selected <?php } ?>><?php echo $Inactive; ?></option>
						                                </select>
		                                            </div>
		                                        </div>
                                            </div>
				                        </div>
		                                <div class="form-group" style="display: none;">
		                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $OutofTownProject ?></label>
		                                    <div class="col-sm-10">
		                                        <select name="PRJLKOT" id="PRJLKOT" class="form-control select2" style="max-width:70px">
		                                            <option value="0" <?php if($PRJLKOT == 0) { ?> selected <?php } ?>>No</option>
		                                            <option value="1" <?php if($PRJLKOT == 1) { ?> selected <?php } ?>>Yes</option>
		                                        </select>
		                                    </div>
		                                </div>
				                        <script>
				                            function checkdecimal()
				                            {
				                                var decFormat	= document.getElementById('decFormat').value;
				                                PRJCOST = eval(document.getElementById('PRJCOST1')).value.split(",").join("");
				                                document.getElementById('PRJCOST').value = PRJCOST;
				                                document.getElementById('PRJCOST1').value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PRJCOST)),decFormat));
				                            }

				                            function chgPRJDP(thisVal)
				                            {
				                                var decFormat	= document.getElementById('decFormat').value;
				                                var PRJ_PPNP	= parseFloat(eval(thisVal).value.split(",").join(""));
				                                document.getElementById('PRJCOST_PPNP').value 	= PRJ_PPNP;
				                                document.getElementById('PRJCOST_PPNPX').value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PRJ_PPNP)), 2));
				                            }
				                            
				                            function chgOVH()
				                            {
				                                var decFormat	= document.getElementById('decFormat').value;
				                                PRJ_LROVH = eval(document.getElementById('PRJ_LROVHX')).value.split(",").join("");
				                                document.getElementById('PRJ_LROVH').value = PRJ_LROVH;
				                                document.getElementById('PRJ_LROVHX').value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PRJ_LROVH)),3));
				                            }
				                            
				                            function chgPPH()
				                            {
				                                var decFormat	= document.getElementById('decFormat').value;
				                                PRJ_LRPPH = eval(document.getElementById('PRJ_LRPPHX')).value.split(",").join("");
				                                document.getElementById('PRJ_LRPPH').value = PRJ_LRPPH;
				                                document.getElementById('PRJ_LRPPHX').value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PRJ_LRPPH)),3));
				                            }
				                            
				                            function chgBNK()
				                            {
				                                var decFormat	= document.getElementById('decFormat').value;
				                                PRJ_LRBNK = eval(document.getElementById('PRJ_LRBNKX')).value.split(",").join("");
				                                document.getElementById('PRJ_LRBNK').value = PRJ_LRBNK;
				                                document.getElementById('PRJ_LRBNKX').value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PRJ_LRBNK)),3));
				                            }
				                        </script>
				                        <?php if($PRJSCATEG != 1) { ?>
					                        <div class="form-group">
					                            <label for="inputName" class="col-sm-2 control-label">&nbsp;</label>
					                            <div class="col-sm-10">
					                                <div class="alert alert-danger alert-dismissible">
					                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					                                    <h4><i class="icon fa fa-info"></i> <?php echo $important; ?>!</h4>
					                                    Jika Anda mengaktifkan salah satu Anggaran, maka secara otomatis sistem akan menonaktifkan anggaran yang lainnya. Sehingga, semua transaksi akan menggunakan anggaran yang Anda aktifkan.
					                                </div>
					                            </div>
					                        </div>
					                    <?php } ?>
				                        <div class="form-group">
				                            <label for="inputName" class="col-sm-2 control-label">&nbsp;</label>
				                            <div class="col-sm-10">
				                                <?php
													if($ISCREATE == 1)
													{
														if($task=='add')
														{
															?>
																<button class="btn btn-primary" id="btnSave">
																<i class="fa fa-save"></i></button>&nbsp;
															<?php
														}
														else
														{
															?>
																<button class="btn btn-primary" id="btnSave">
																<i class="fa fa-save"></i></button>&nbsp;
															<?php
														}
													}
													echo anchor("$backURL",'<button class="btn btn-danger" type="button" id="btnBack"><i class="fa fa-reply"></i></button>');
												?>
				                            </div>
				                        </div><br>
				                    </div>
				                </form>
					            <script>
									function checkInp()
									{
										PRJCODE_HO = document.getElementById('PRJCODE_HO').value;
										if(PRJCODE_HO == 0)
										{
											swal('<?php echo $selPG; ?>',
											{
												icon: "warning",
											})
											.then(function()
											{
												swal.close();
												$('#PRJCODE_HO').focus();
											});
											return false;
										}
										
										PRJPERIOD = document.getElementById('PRJPERIOD').value;
										if(PRJPERIOD == '')
										{
											swal('<?php echo $budCEmpt; ?>',
											{
												icon: "warning",
											})
											.then(function()
											{
												swal.close();
												$('#PRJPERIOD').focus();
											});
											return false;
										}
										
										PRJNAME = document.getElementById('PRJNAME').value;
										if(PRJNAME == '')
										{
											swal('<?php echo $budEmpt; ?>',
											{
												icon: "warning",
											})
											.then(function()
											{
												swal.close();
												$('#PRJNAME').focus();
											});
											return false;
										}

                                		var PRJDATE 	= $('#datepicker1').val().split("/");
                                		var PRJD 		= PRJDATE[0];
                                		var PRJM 		= PRJDATE[1];
                                		var PRJY 		= PRJDATE[2];
                                		var dateS 		= new Date(PRJY+'-'+PRJM+'-'+PRJD);

                                		var PRJEDAT 	= $('#datepicker2').val().split("/");
                                		var PRJED 		= PRJEDAT[0];
                                		var PRJEM 		= PRJEDAT[1];
                                		var PRJEY 		= PRJEDAT[2];
                                		var dateE 		= new Date(PRJEY+'-'+PRJEM+'-'+PRJED);

										if(dateE < dateS)
										{
											swal('<?php echo $eDMTsD; ?>',
											{
												icon: "error",
											})
											.then(function()
											{
												document.getElementById('datepicker2').focus();
											});
											return false;
										}

										let PRJLK_NUM = $('#PRJLK_NUM');
										if(PRJLK_NUM.prop('disabled') == false)
										{
											if(PRJLK_NUM.val() == '')
											{
												swal('<?php echo $alertLKNUM; ?>',
												{
													icon: "warning",
												})
												.then(function()
												{
													swal.close();
													$('#PRJLK_NUM').focus();
												});
												return false;
											}
										}
										
										PRJSTAT = document.getElementById('PRJSTAT').value;
										if(PRJSTAT == 0)
										{
											swal('<?php echo $compStatEmpy; ?>',
											{
												icon: "warning",
											})
											.then(function()
											{
												swal.close();
												$('#PRJSTAT').focus();
											});
											return false;
										}
										document.getElementById('btnSave').style.display = 'none';
										document.getElementById('btnBack').style.display = 'none';
									}
								</script>
				            </div>
				            <div class="active tab-pane" id="budDoc" style="display: none;">
				            	<?php 
					            	if($task == 'add')
					            	{
					            		?>
					            			<div class="alert alert-warning alert-dismissible">
								                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								                <h4><i class="icon fa fa-warning"></i> <?php echo $Information; ?>!</h4>
								                <?php echo $InfoDet; ?>
							              	</div>
					            		<?php
					            	}
					            	else
					            	{
					            		?>
					            		<div class="row">
											<div class="col-md-5">
												<div class="box box-primary">
													<div class="box-body">
														<form name="myformupload" id="myformupload" enctype="multipart/form-data" method="post" >
															<div class="box-body">
												                <div class="form-group">
										                          	<label for="inputName">Pilih File</label>
										                            <input type="text" style="display:none" name="isUploaded" id="isUploaded" value="<?php echo $isUploaded; ?>" />
										                       		<input type="text" style="display:none" name="PRJCODE" id="PRJCODE" value="<?php echo $PRJCODE; ?>" />
										                            <input type="file" name="docfile" id="docfile" class="filestyle" data-buttonName="btn-primary"/>
										                        </div>
										                        <div class="form-group">
							                                        <label for="inputName">Description</label>
							                                        <input type="text" class="form-control" name="PRJ_FDESC" id="PRJ_FDESC" placeholder="<?php echo $Description; ?>" value="">
							                                    </div>
									                        	<div class="pull-right">
									                        		<button type='submit' class='btn btn-primary'><i class='glyphicon glyphicon-upload'></i></button>
									                                <?php
									                                	echo "&nbsp&nbsp;";
									                                    echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>');
									                                ?>
								                                </div>
												            </div>
									                    </form>
													</div>
													<div id="loading_1" class="overlay" <?php if($isLoadDone_1 == 1) { ?> style="display:none" <?php } ?>>
											            <i class="fa fa-refresh fa-spin"></i>
											        </div>
											    </div>
											</div>
											<div class="col-md-7">
												<div class="box box-warning">
													<div class="box-header with-border">
														<i class="fa fa-list"></i>
														<h3 class="box-title">Daftar File</h3>
													</div>
													<div class="box-body">
										                <table id="example" class="table table-bordered table-striped" width="100%">
										                    <thead>
										                        <tr>
										                            <th width="8%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Date ?> </th>
										                            <th width="61%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Description ?></th>
										                            <th width="4%" style="text-align:center; vertical-align:middle" nowrap>&nbsp;</th>
										                      </tr>
										                    </thead>
										                    <tbody>
										                    </tbody>
										                </table>
													</div>
													<div id="loading_2" class="overlay" <?php if($isLoadDone_1 == 1) { ?> style="display:none" <?php } ?>>
											            <i class="fa fa-refresh fa-spin"></i>
											        </div>
												</div>
											</div>
										</div>
			                        	<?php
				                    }
			                    ?>
				            </div>
		                    <div class="active tab-pane" id="profPicture" style="display: none;">
				            	<?php 
					            	if($task == 'add')
					            	{
					            		?>
					            			<div class="alert alert-warning alert-dismissible">
								                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								                <h4><i class="icon fa fa-warning"></i> <?php echo $Information; ?>!</h4>
								                <?php echo $InfoDet; ?>
							              	</div>
					            		<?php
					            	}
					            	else
					            	{
					            		?>
				                        <form class="form-horizontal" name="frm" method="post" action="<?php echo $urlUpdPic; ?>" enctype="multipart/form-data" onSubmit="return checkData()">
				                            <div class="box box-primary">
				                                <div class="box-header with-border" style="display: none;">
				                                    <h3 class="box-title"><?php echo $UploadDoc; ?></h3>
				                                </div>
				                                <br>
						                        <div class="form-group" style="display: none;"> <!-- proj_Number : GENERATE CODE BUDGET -->
						                            <label for="inputName" class="col-sm-2 control-label"><?php echo $BudNo?></label>
						                            <div class="col-sm-10">
						                                <input type="text" class="form-control" name="PRJNUM" id="PRJNUM" value="<?php echo $DocNumber; ?>" disabled>
						                                <input type="hidden" name="proj_Number" id="proj_Number" value="<?php echo $DocNumber; ?>" >
						                            </div>
						                        </div>
				                                <div class="form-group">
				                                    <label for="inputName" class="col-sm-2 control-label"><?php echo "$Code $Project"; ?> </label>
				                                    <div class="col-sm-10">
				                                      <input type="text" class="form-control" name="PRJCODE" id="PRJCODE" placeholder="Project Code" value="<?php echo $PRJCODE; ?>" readonly>
				                                    </div>
				                                </div>
				                                <div class="form-group">
				                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $ProjectName ?> </label>
				                                    <div class="col-sm-10">
				                                      <input type="text" class="form-control" name="PRJNAME" id="PRJNAME" placeholder="File Name" value="<?php echo $PRJNAME; ?>" readonly>
				                                    </div>
				                                </div>
				                                <div class="form-group">
				                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $ChooseFile ?> </label>
				                                    <div class="col-sm-10">
				                                      	<input type="file" name="userfile" class="filestyle" data-buttonName="btn-primary"/>
				                                    </div>
				                                </div>
				                                <br>
					                            <div class="form-group">
					                                <div class="col-sm-offset-2 col-sm-10">
					                                    <?php
															if($ISCREATE == 1)
															{
																if($task=='add')
																{
																	?>
																		<button class="btn btn-primary">
																		<i class="fa fa-save"></i></button>&nbsp;
																	<?php
																}
																else
																{
																	?>
																		<button class="btn btn-primary" >
																		<i class="fa fa-save"></i></button>&nbsp;
																	<?php
																}
															}
															echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>');
					                                    ?>
					                                </div>
					                            </div><br>
				                            </div>
				                        </form>
		                            <?php
		                        	}
		                        ?>
		               		</div>
							<div class="active tab-pane" id="prjAcc" style="display: none;">
								<?php
									if($task == 'add')
					            	{
					            		?>
					            			<div class="alert alert-warning alert-dismissible">
								                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								                <h4><i class="icon fa fa-warning"></i> <?php echo $Information; ?>!</h4>
								                <?php echo $InfoDet; ?>
							              	</div>
					            		<?php
					            	}
									else
									{
										?>
											<form class="form-horizontal" name="frm" method="post" action="<?php echo $urlprjAcc; ?>" enctype="multipart/form-data" onSubmit="return checkDataprjAcc()">
												<div class="box box-primary">
													<div class="box-header with-border">
														<i class="fa fa-child"></i>
														<h3 class="box-title">Cash-Bank Project Account</h3>
													</div>
													<div class="box-body">
														<div class="form-group">
															<div class="col-sm-6">
																<select multiple class="form-control" name="pavailableCB" onclick="MoveOption(this.form.pavailableCB, this.form.packageelementsCB)" style="height: 150px">
																	<?php
																		$sqlDataACC     = "SELECT DISTINCT
																								A.Account_Number, 
																								A.Account_Nameen as Account_Name,
																								A.isLast, A.Account_NameId
																							FROM tbl_chartaccount A
																								INNER JOIN tbl_chartcategory B ON A.Account_Category = B.ChartCategory_ID
																							WHERE
																								A.Account_Class IN (3,4) AND
																								A.Currency_id = 'IDR'
																								-- AND A.Account_Category IN (1,2,3)
																								AND A.Account_Number NOT IN (SELECT E.Acc_Number FROM tbl_project_acc E WHERE E.PRJCODE = '$PRJCODE')
																								AND A.isLast = 1
																								Order by A.Account_NameId, A.Account_Number";
																			$resDataACC     = $this->db->query($sqlDataACC)->result();
																			foreach($resDataACC as $rowDACC) :
																				$Acc_ID1        = $rowDACC->Acc_ID;
																				$Account_Number = $rowDACC->Account_Number;
																				$Account_Name   = $rowDACC->Account_Name;
																				$isLast1        = $rowDACC->isLast;
																			?>
																				<option value="<?php echo $Account_Number; ?>" <?php if($isLast1 == 0) { ?> disabled <?php } ?>><?php echo "$Account_Name - $Account_Number";?></option>
																			<?php
																		endforeach;
																	?>
																</select>
															</div>
															<div class="col-sm-6">
																<?php                   
																	$getCount       = "tbl_project_acc WHERE PRJCODE = '$PRJCODE'";
																	$resGetCount    = $this->db->count_all($getCount);
																?>
																<select multiple class="form-control" name="packageelementsCB[]" id="packageelementsCB" ondblclick="MoveOption(this.form.packageelementsCB, this.form.pavailableCB)" style="height: 150px">
																	<?php
																		if($resGetCount > 0)
																		{
																			$sqlDataACC     = "SELECT DISTINCT A.Acc_Number,
																									B.Account_Nameen as Account_Name, B.isLast, B.Account_NameId, B.Account_Number
																								FROM tbl_project_acc A
																									INNER JOIN tbl_chartaccount B ON B.Account_Number = A.Acc_Number
																								WHERE A.PRJCODE = '$PRJCODE'
																								Order by B.Account_NameId, B.Account_Number";
																			$resDataACC     = $this->db->query($sqlDataACC)->result();
																			foreach($resDataACC as $rowDACC) :
																				$Account_Number = $rowDACC->Acc_Number;
																				$Account_Name   = $rowDACC->Account_Name;
																				$isLast2        = $rowDACC->isLast;
																				?>
																					<option value="<?php echo $Account_Number; ?>" <?php if($isLast2 == 0) { ?> disabled <?php } ?>><?php echo "$Account_Name - $Account_Number";?></option>
																				<?php
																			endforeach; 
																		}
																	?>
																</select>
															</div>
														</div>
														<br>
														<div class="form-group">
															<div class="col-sm-9">
																<?php
																	if($ISCREATE == 1)
																	{
																		if($task=='add')
																		{
																			?>
																				<button class="btn btn-primary">
																				<i class="fa fa-save"></i></button>&nbsp;
																			<?php
																		}
																		else
																		{
																			?>
																				<button class="btn btn-primary" >
																				<i class="fa fa-save"></i></button>&nbsp;
																			<?php
																		}
																	}
																	echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>');
																?>
															</div>
														</div><br>
													</div>
												</div>
											</form>
										<?php
									}
								?>
								
							</div>
		                    <div class="active tab-pane" id="othSett" style="display: none;">
				            	<?php 
					            	if($task == 'add')
					            	{
					            		?>
					            			<div class="alert alert-warning alert-dismissible">
								                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								                <h4><i class="icon fa fa-warning"></i> <?php echo $Information; ?>!</h4>
								                <?php echo $InfoDet; ?>
							              	</div>
					            		<?php
					            	}
					            	else
					            	{
                                        $s_01     	= "tbl_chartaccount WHERE Account_Category IN (1,2,4) AND PRJCODE = '$PRJCODE'";
                                        $r_01     	= $this->db->count_all($s_01);
                                        
                                        $s_02     	= "SELECT DISTINCT Acc_ID, Account_Number, Account_Level, Account_NameEn, Account_NameId, Acc_ParentList,
                                                            Acc_DirParent, isLast
                                                        FROM tbl_chartaccount WHERE Account_Category IN (1,2,4)
                                                            AND PRJCODE = '$PRJCODE' ORDER BY ORD_ID ASC";
                                        $r_02     	= $this->db->query($s_02)->result();
					            		?>
				                        <form class="form-horizontal" name="frm" method="post" action="<?php echo $urlUpOthSet; ?>" enctype="multipart/form-data" onSubmit="return checkDataOthSett()">
				                            <div class="box box-primary">
				                                <div class="box-header with-border" style="display: none;">
				                                    <h3 class="box-title"><?php echo $UploadDoc; ?></h3>
				                                </div>
				                                <br>
						                        <div class="form-group" style="display: none;"> <!-- proj_Number : GENERATE CODE BUDGET -->
						                            <label for="inputName" class="col-sm-2 control-label"><?php echo $BudNo?></label>
						                            <div class="col-sm-10">
						                                <input type="text" class="form-control" name="PRJNUM" id="PRJNUM" value="<?php echo $DocNumber; ?>" disabled>
						                                <input type="hidden" name="proj_Number" id="proj_Number" value="<?php echo $DocNumber; ?>" >
						                                <input type="hidden" name="PRJCODE_HO" id="PRJCODE_HO" value="<?php echo $PRJCODE_HO; ?>" >
						                            </div>
						                        </div>
				                                <div class="form-group" style="display: none;">
				                                    <label for="inputName" class="col-sm-2 control-label"><?php echo "$Code $Project"; ?> </label>
				                                    <div class="col-sm-10">
				                                      <input type="text" class="form-control" name="PRJCODE" id="PRJCODE" placeholder="Project Code" value="<?php echo $PRJCODE; ?>" readonly>
				                                    </div>
				                                </div>
	                                            <div class="form-group">
	                                                <label for="inputName" class="col-sm-3 control-label" title="Mengatur Akun penjurnalan Penggunaan Material oleh pihak ke-3."><?php echo $umTP; ?></label>
	                                                <div class="col-sm-9">
	                                                    <select name="PRJ_ACCUM" id="PRJ_ACCUM" class="form-control select2" style="width: 100%">
	                                                        <option value="" > --- </option>
	                                                        <?php
	                                                        if($r_01>0)
	                                                        {
	                                                            foreach($r_02 as $rw_02) :
	                                                                $Acc_ID0        = $rw_02->Acc_ID;
	                                                                $Account_Number0= $rw_02->Account_Number;
	                                                                $Acc_DirParent0 = $rw_02->Acc_DirParent;
	                                                                $Account_Level0 = $rw_02->Account_Level;
	                                                                if($LangID == 'IND')
	                                                                {
	                                                                    $Account_Name0  = $rw_02->Account_NameId;
	                                                                }
	                                                                else
	                                                                {
	                                                                    $Account_Name0  = $rw_02->Account_NameEn;
	                                                                }
	                                                                
	                                                                $Acc_ParentList0    = $rw_02->Acc_ParentList;
	                                                                $isLast_0           = $rw_02->isLast;
	                                                                $disbaled_0         = 0;
	                                                                if($isLast_0 == 0)
	                                                                    $disbaled_0     = 1;
	                                                                    
	                                                                if($Account_Level0 == 0)
	                                                                    $level_coa1         = "";
	                                                                elseif($Account_Level0 == 1)
	                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;";
	                                                                elseif($Account_Level0 == 2)
	                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	                                                                elseif($Account_Level0 == 3)
	                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	                                                                elseif($Account_Level0 == 4)
	                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	                                                                elseif($Account_Level0 == 5)
	                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	                                                                elseif($Account_Level0 == 6)
	                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	                                                                elseif($Account_Level0 == 7)
	                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	                                                                
	                                                                $collData0  = "$Account_Number0";
	                                                                ?>
	                                                                    <option value="<?php echo $Account_Number0; ?>" <?php if($Account_Number0 == $PRJ_ACCUM) { ?> selected <?php } if($disbaled_0 == 1) { ?> disabled style="font-weight:bold" <?php } ?>><?php echo "$level_coa1$Account_Name0 - $collData0"; ?></option>
	                                                                <?php
	                                                            endforeach;
	                                                        }
	                                                        ?>
	                                                    </select>
	                                                </div>
	                                            </div>
				                                <br>
					                            <div class="form-group">
					                                <div class="col-sm-offset-3 col-sm-9">
					                                    <?php
															if($ISCREATE == 1)
															{
																if($task=='add')
																{
																	?>
																		<button class="btn btn-primary">
																		<i class="fa fa-save"></i></button>&nbsp;
																	<?php
																}
																else
																{
																	?>
																		<button class="btn btn-primary" >
																		<i class="fa fa-save"></i></button>&nbsp;
																	<?php
																}
															}
															echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>');
					                                    ?>
					                                </div>
					                            </div><br>
				                            </div>
				                        </form>
		                            <?php
		                        	}
		                        ?>
		               		</div>
		                    <script>
								function checkData()
								{
									filename	= document.getElementById('FileName').value;
									if(filename == '')
									{
										alert('Please input file name.');
										document.getElementById('FileName').focus();
										return false;
									}
								}

								function showTab(valTab)
								{
									if(valTab == 1)
									{
										document.getElementById('budgInfo').style.display 		= '';
										document.getElementById('budDoc').style.display 		= 'none';
										document.getElementById('profPicture').style.display 	= 'none';
										document.getElementById('prjAcc').style.display 		= 'none';
										document.getElementById('othSett').style.display 		= 'none';
									}
									else if(valTab == 2)
									{
										document.getElementById('budgInfo').style.display 		= 'none';
										document.getElementById('budDoc').style.display 		= '';
										document.getElementById('profPicture').style.display 	= 'none';
										document.getElementById('prjAcc').style.display 		= 'none';
										document.getElementById('othSett').style.display 		= 'none';
									}
									else if(valTab == 3)
									{
										document.getElementById('budgInfo').style.display 		= 'none';
										document.getElementById('budDoc').style.display 		= 'none';
										document.getElementById('profPicture').style.display 	= '';
										document.getElementById('prjAcc').style.display 		= 'none';
										document.getElementById('othSett').style.display 		= 'none';
									}
									else if(valTab == 4)
									{
										document.getElementById('budgInfo').style.display 		= 'none';
										document.getElementById('budDoc').style.display 		= 'none';
										document.getElementById('profPicture').style.display 	= 'none';
										document.getElementById('prjAcc').style.display 		= '';
										document.getElementById('othSett').style.display 		= 'none';
									}
									else if(valTab == 5)
									{
										document.getElementById('budgInfo').style.display 		= 'none';
										document.getElementById('budDoc').style.display 		= 'none';
										document.getElementById('profPicture').style.display 	= 'none';
										document.getElementById('prjAcc').style.display 		= 'none';
										document.getElementById('othSett').style.display 		= '';
									}
								}
							</script>
						</div>
					</div>
				</div>
			</div>
            <?php
                $DefID      = $this->session->userdata['Emp_ID'];
                $act_lnk    = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                if($DefID == 'D15040004221')
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
		$.fn.datepicker.defaults.format = "dd/mm/yyyy";
	    $('#datepicker1').datepicker({
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

	    $('input:radio[name="PRJLKOT"]').on('ifChecked', function(e) {
	    	if(e.target.checked == true)
        	{
        		let PRJLK_NUM = '<?=$PRJLK_NUM?>';
            	if(e.target.value == 0)
            	{
            		$('#PRJLK_NUM').prop('disabled', true);
            		$('#PRJLK_NUM').val('');
            	}
            	else
            	{
            		$('#PRJLK_NUM').prop('disabled', false);
            		$('#PRJLK_NUM').val(PRJLK_NUM);
            	}
            }
	    });
  	});
</script>

<?php
	$frmAct 	= base_url().'index.php/c_comprof/c_bUd93tL15t/do_uploadPRJDOC/?id=';
?>
<script>
	$(document).ready(function() {
    	$('#example').DataTable( {
        "processing": true,
        "serverSide": true,
		//"scrollX": false,
		"autoWidth": true,
		"filter": true,
        "ajax": "<?php echo site_url('c_comprof/c_bUd93tL15t/get_AllDataPRJDOC/?id='.$PRJCODE)?>",
        "type": "POST",
		//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
		"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
		"columnDefs": [	{ targets: [2], className: 'dt-body-center' },
						{ "width": "100px", "targets": [1] }
					  ],
		"language": {
            "infoFiltered":"",
            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
        },
		} );
	});

	$(document).ready(function() {
		$('#myformupload').submit(function(e){
   			e.preventDefault();
			PRJ_FDESC 		= document.getElementById('PRJ_FDESC').value;
			DOC_FILE 		= document.getElementById('docfile').value;
			if(PRJ_FDESC == "")
			{
				swal('<?php echo $DescNull; ?>',
				{
					icon: "warning",
				})
				.then(function()
				{
					swal.close();
					$('#PRJ_FDESC').focus();
				});
				return false;
			}
			if(DOC_FILE == "")
			{
				swal('<?php echo $FileNull; ?>',
				{
					icon: "warning",
				})
				.then(function()
				{
					swal.close();
					$('#docfile').focus();
				});
				return false;
			}

		   	var form = $(this);
		    var url = '<?php echo $frmAct; ?>';
		    var data = $('form').serialize();
		    var formData = new FormData($('#myformupload')[0]);
		    formData.append('docfile', $('input[type=file]')[0].files[0]);

			$.ajax({
				type: "POST",
				enctype: 'multipart/form-data',
				url: url,
				data: formData,
				contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
    			processData: false, // NEEDED, DON'T OMIT THIS
				success: function(data)
				{
					//alert(data)
					//$('#example2').data.reload();
					document.getElementById('docfile').value 	= '';
					document.getElementById('PRJ_FDESC').value 	= '';
					$('#example').DataTable().ajax.reload();
				}
			});
		});	
	});	
</script>

<script>
	var decFormat		= 2;
	
	function doDecimalFormat(angka) 
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

	function MoveOption(objSourceElement, objTargetElement) 
    { 
        var aryTempSourceOptions = new Array(); 
        var aryTempTargetOptions = new Array(); 
        var x = 0; 
    
        //looping through source element to find selected options 
        for (var i = 0; i < objSourceElement.length; i++)
        { 
            if (objSourceElement.options[i].selected)
            { 
                 //need to move this option to target element 
                 var intTargetLen = objTargetElement.length++; 
                 objTargetElement.options[intTargetLen].text = objSourceElement.options[i].text; 
                 objTargetElement.options[intTargetLen].value = objSourceElement.options[i].value; 
            } 
            else
            { 
                 //storing options that stay to recreate select element 
                 var objTempValues = new Object(); 
                 objTempValues.text = objSourceElement.options[i].text; 
                 objTempValues.value = objSourceElement.options[i].value; 
                 aryTempSourceOptions[x] = objTempValues; 
                 x++; 
            } 
        } 
    
        //sorting and refilling target list 
        for (var i = 0; i < objTargetElement.length; i++)
        { 
            var objTempValues = new Object(); 
            objTempValues.text = objTargetElement.options[i].text; 
            objTempValues.value = objTargetElement.options[i].value; 
            aryTempTargetOptions[i] = objTempValues; 
        } 
    
        aryTempTargetOptions.sort(sortByText); 
    
        for (var i = 0; i < objTargetElement.length; i++)
        { 
            objTargetElement.options[i].text = aryTempTargetOptions[i].text; 
            objTargetElement.options[i].value = aryTempTargetOptions[i].value; 
            objTargetElement.options[i].selected = false; 
        } 
    
        //resetting length of source 
        objSourceElement.length = aryTempSourceOptions.length; 
        //looping through temp array to recreate source select element 
        for (var i = 0; i < aryTempSourceOptions.length; i++) 
        { 
            objSourceElement.options[i].text = aryTempSourceOptions[i].text; 
            objSourceElement.options[i].value = aryTempSourceOptions[i].value; 
            objSourceElement.options[i].selected = false; 
        }
    }

	function sortByText(a, b) 
    { 
        if (a.text < b.text) {return -1} 
        if (a.text > b.text) {return 1} 
        return 0;
    }

	function checkDataprjAcc()
	{
		column2 = document.getElementById('packageelementsCB').value; // Optional
        if(column2 == '')
        {
			swal('<?php echo $alertCB; ?>',
			{
				icon: "warning",
			})
			.then(function()
			{
				swal.close();
				$('#packageelementsCB').focus();
			});
			return false;
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
    //______$this->load->view('template/aside');

    //______$this->load->view('template/js_data');

    //______$this->load->view('template/foot');
?>