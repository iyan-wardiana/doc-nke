<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 8 Februari 2017
	* File Name		= listproject_form.php
	* Location		= -
*/
											
date_default_timezone_set("Asia/Jakarta"); 
setlocale(LC_ALL, 'id_ID');

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
	if($Pattern_Position == 'Especially')
	{
		$Pattern_YearAktive = date('Y');
		$Pattern_MonthAktive = date('m');
		$Pattern_DateAktive = date('d');
	}
	$year = (int)$Pattern_YearAktive;
	$month = (int)$Pattern_MonthAktive;
	$date = (int)$Pattern_DateAktive;

	$this->db->where('Patt_Year', $year);
	$this->db->where('Patt_Month', $month);
	$this->db->where('Patt_Date', $date);
	$myCount = $this->db->count_all('tbl_project');
	
	$sql = "SELECT MAX(Patt_Number) as maxNumber FROM tbl_project
			WHERE Patt_Year = $year";
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
	$PRJDATED 		= "$PRJDATEB/$PRJDATEC/$PRJDATEA";
	$PRJEDAT 		= "$PRJDATED";
	$PRJDATE_MNT	= date('d/m/Y');
	$proj_addDate 	= "$PRJDATEB/$PRJDATEC/$PRJDATEA";
	$proj_amountUSD	= 0;
	$PRJBOQ			= 0;
	$PRJRAP			= 0;
	$BASEAMOUNT		= 0;
	
	$PRJCODE 		= '';
	$PRJCNUM		= '';
	$PRJNAME 		= '';
	$PRJLOCT 		= '';
	$PRJOWN			= '';
	$PRJDATE 		= "$PRJDATEB/$PRJDATEC/$PRJDATEA";
	$PRJEDAT 		= "$PRJDATEB/$PRJDATEC/$PRJDATEA";
	$ENDDATE		= "$PRJDATEB/$PRJDATEC/$PRJDATEA";
	$PRJDATE_CO		= "$PRJDATEB/$PRJDATEC/$PRJDATEA";
	$PRJEDATD 		= "$PRJDATEB/$PRJDATEC/$PRJDATEA";
	
	$PRJCOST 		= 0;
	$PRJLKOT 		= '';
	$PRJCBNG		= '';
	$PRJCURR		= 'IDR';
	$CURRRATE		= 1;
	$CURRRATEUSD	= 13000;
	$PRJSTAT 		= 0;
	$PRJNOTE		= '';
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
	$PRJCATEG		= 'SPL';
	$PRJ_IMGNAME	= "building.jpg";
	$isHO			= 0;				// 0 = Project 1 = Head Office
}
else
{
	$proj_Number 		= $default['proj_Number'];
	$DocNumber 			= $proj_Number;
	$PRJCODE 			= $default['PRJCODE'];
	$PRJCNUM			= $default['PRJCNUM'];
	$PRJNAME 			= $default['PRJNAME'];
	$PRJLOCT 			= $default['PRJLOCT'];
	$PRJCATEG 			= $default['PRJCATEG'];
	$PRJOWN 			= $default['PRJOWN'];
	$PRJDATE 			= date('d/m/Y', strtotime($default['PRJDATE']));
	$PRJDATE_CO 		= date('d/m/Y', strtotime($default['PRJDATE_CO']));
	$PRJDATE_MNT 		= $default['PRJDATE_MNT'];
	if($PRJDATE_MNT == '0000-00-00' || $PRJDATE_MNT == '')
		$PRJDATE_MNTD 	= date('Y-m-d');
	else
		$PRJDATE_MNTD 	= date('Y-m-d', strtotime($default['PRJDATE_MNT']));

	$PRJEDAT 			= date('d/m/Y', strtotime($default['PRJEDAT']));
	$ENDDATE 			= date('d/m/Y', strtotime($default['PRJEDAT']));
	$isHO				= $default['isHO'];/*
	
	$PRJDATEA 		= date("Y", strtotime($PRJDATE));
	$PRJDATEB 		= date("m", strtotime($PRJDATE));
	$PRJDATEC 		= date("d", strtotime($PRJDATE));
	$PRJDATED 		= "$PRJDATEB/$PRJDATEC/$PRJDATEA";
	
	$PRJEDATA 		= date("Y", strtotime($PRJEDAT));
	$PRJEDATB 		= date("m", strtotime($PRJEDAT));
	$PRJEDATC 		= date("d", strtotime($PRJEDAT));
	$PRJEDATD 		= "$PRJEDATB/$PRJEDATC/$PRJEDATA";
	$ENDDATED		= $PRJEDAT;
	
	$PRJDATE_COA 	= date("Y", strtotime($PRJDATE_CO));
	$PRJDATE_COB 	= date("m", strtotime($PRJDATE_CO));
	$PRJDATE_COC 	= date("d", strtotime($PRJDATE_CO));
	$PRJDATE_CO 	= "$PRJDATE_COB/$PRJDATE_COC/$PRJDATE_COA";*/
	
	$sqlsp0		= "SELECT ENDDATE FROM tbl_projhistory WHERE PRJCODE = '$PRJCODE'";
	$sqlsp0R	= $this->db->query($sqlsp0)->result();
	foreach($sqlsp0R as $rowsp0) :
		$ENDDATE		= $rowsp0->ENDDATE;
		$ENDDATE 		= date("d/m/Y", strtotime($ENDDATE));
	endforeach;

	$PRJCOST 			= $default['PRJCOST'];	// NILAI KONTRAK
	$proj_amountUSD		= $default['PRJCOST'];
	$PRJBOQ				= $default['PRJBOQ'];	// NILAI KONTRAK	
	$PRJRAP				= $default['PRJRAP'];
	$PRJLKOT 			= $default['PRJLKOT'];
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
	$PRJ_IMGNAME		= $default['PRJ_IMGNAME'];
	$Patt_Year 			= $default['Patt_Year'];
	$Patt_Number 		= $default['Patt_Number'];
	$lastPatternNumb1	= $Patt_Number;
	
	if($PRJDATE == '0000-00-00')
	{
		$sqlX = "SELECT PRJDATE
				FROM project WHERE PRJCODE = '$PRJCODE'";
		$result = $this->db->query($sqlX)->result();
		foreach($result as $rowx) :
			$PRJDATE		= $rowx->PRJDATE;
		endforeach;
		if($PRJDATE == '0000-00-00')
		{
			$PRJDATEA 	= date('Y');
			$PRJDATEB 	= date('m');
			$PRJDATEC 	= date('d');
			//$PRJDATE 	= "$PRJDATEB/$PRJDATEC/$PRJDATEA";
			$PRJDATE 	= date("d/m/Y");
		}
	}
	
	if($PRJEDAT == '0000-00-00')
	{
		$PRJEDAT 		= $PRJDATE;
	}
	
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
}

if($PRJCURR == '')
{
	$PRJCURR = 'IDR';
}
$imgLoc		= base_url('assets/AdminLTE-2.0.5/project_image/building.jpg');
$imgLoc		= base_url('assets/AdminLTE-2.0.5/project_image/'.$PRJCODE.'/'.$PRJ_IMGNAME);
if (!file_exists('assets/AdminLTE-2.0.5/project_image/'.$PRJCODE))
{
	$imgLoc			= base_url('assets/AdminLTE-2.0.5/project_image/building.jpg');
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

    <script type="text/javascript" src="https://www.amcharts.com/lib/3/amcharts.js"></script>
    <script type="text/javascript" src="https://cdn.amcharts.com/lib/3/pie.js"></script>
    <script type="text/javascript" src="https://www.amcharts.com/lib/3/themes/light.js"></script>
	<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/js/highcharts/highcharts.js'; ?>"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="http://www.chartjs.org/dist/2.7.2/Chart.bundle.js"></script>
    <script src="http://www.chartjs.org/samples/latest/utils.js"></script>
    <script src="http://code.jquery.com/jquery-2.2.1.min.js"></script>

	<script type="text/javascript">
		Highcharts.theme = {
		    colors: ['#058DC7', '#50B432', '#ED561B', '#DDDF00', '#24CBE5', '#64E572', 
		             '#FF9655', '#FFF263', '#6AF9C4'],
		    chart: {
		        backgroundColor: {
		            linearGradient: [0, 0, 500, 500],
		            stops: [
		                [0, 'rgb(255, 255, 255)'],
		                [1, 'rgb(240, 240, 255)']
		            ]
		        },
		    },
		    title: {
		        style: {
		            color: '#000',
		            font: 'bold 16px "Trebuchet MS", Verdana, sans-serif'
		        }
		    },
		    subtitle: {
		        style: {
		            color: '#666666',
		            font: 'bold 12px "Trebuchet MS", Verdana, sans-serif'
		        }
		    },

		    legend: {
		        itemStyle: {
		            font: '9pt Trebuchet MS, Verdana, sans-serif',
		            color: 'black'
		        },
		        itemHoverStyle:{
		            color: 'gray'
		        }   
		    }
		};

		// Apply the theme
		Highcharts.setOptions(Highcharts.theme);
	</script>

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
			
			if($TranslCode == 'ProjectNumber')$ProjectNumber = $LangTransl;
			if($TranslCode == 'Code')$Code = $LangTransl;
			if($TranslCode == 'ProjectName')$ProjectName = $LangTransl;
			if($TranslCode == 'Date')$Date = $LangTransl;
			if($TranslCode == 'CutOffDate')$CutOffDate = $LangTransl;
			if($TranslCode == 'EndDate')$EndDate = $LangTransl;
			if($TranslCode == 'ContractNo')$ContractNo = $LangTransl;
			if($TranslCode == 'Category')$Category = $LangTransl;
			if($TranslCode == 'Owner')$Owner = $LangTransl;
			if($TranslCode == 'Amount')$Amount = $LangTransl;
			if($TranslCode == 'Location')$Location = $LangTransl;
			if($TranslCode == 'OutofTownProject')$OutofTownProject = $LangTransl;
			if($TranslCode == 'ProjectManager')$ProjectManager = $LangTransl;
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
			if($TranslCode == 'Back')$Back = $LangTransl;
			if($TranslCode == 'Active')$Active = $LangTransl;
			if($TranslCode == 'Inactive')$Inactive = $LangTransl;
			if($TranslCode == 'Project')$Project = $LangTransl;
			if($TranslCode == 'ProjectCost')$ProjectCost = $LangTransl;
			if($TranslCode == 'Target')$Target = $LangTransl;
			if($TranslCode == 'Margin')$Margin = $LangTransl;
			if($TranslCode == 'AboutProject')$AboutProject = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'Information')$Information = $LangTransl;
			if($TranslCode == 'Picture')$Picture = $LangTransl;
			if($TranslCode == 'Progress')$Progress = $LangTransl;
			if($TranslCode == 'Add')$Add = $LangTransl;
			if($TranslCode == 'None')$None = $LangTransl;
			if($TranslCode == 'Code')$Code = $LangTransl;
			if($TranslCode == 'ProjectName')$ProjectName = $LangTransl;
			if($TranslCode == 'ChooseFile')$ChooseFile = $LangTransl;
			if($TranslCode == 'UploadDoc')$UploadDoc = $LangTransl;
			if($TranslCode == 'ProjectInformation')$ProjectInformation = $LangTransl;
			if($TranslCode == 'ownNmEmpt')$ownNmEmpt = $LangTransl;
			if($TranslCode == 'prjNmEmpt')$prjNmEmpt = $LangTransl;
			if($TranslCode == 'eDMTsD')$eDMTsD = $LangTransl;
			if($TranslCode == 'ownNmEmpt')$ownNmEmpt = $LangTransl;
			if($TranslCode == 'projDoc')$projDoc = $LangTransl;
			if($TranslCode == 'ProfilePicture')$ProfilePicture = $LangTransl;
			if($TranslCode == 'activity')$activity = $LangTransl;
			if($TranslCode == 'ImpDate')$ImpDate = $LangTransl;
			if($TranslCode == 'MaintDate')$MaintDate = $LangTransl;
		endforeach;
		$urlUpdDoc		= site_url('c_project/lst180c2hprj/do_upload/?id='.$this->url_encryption_helper->encode_url($appName));
		
		if($LangID == 'IND')
		{
			$Yes	= "Ya";
			$No		= "Bukan";
		}
		else
		{
			$Yes	= "Yes";
			$No		= "No";
		}

        $comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
			<h1>
			    <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/project.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $Add; ?>
			    <small><?php echo $Project; ?></small>
			  </h1>
			  <?php /*?><ol class="breadcrumb">
			    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			    <li><a href="#">Tables</a></li>
			    <li class="active">Data tables</li>
			  </ol><?php */?>
		</section>
		<style>
			.search-table, td, th {
				border-collapse: collapse;
			}
			.search-table-outter { overflow-x: scroll; }
		</style>
		
	    <section class="content">
	      	<div class="row">
		        <div class="col-md-3">
		          	<!-- Profile Image -->
		          	<div class="box box-warning">
		                <div class="box-body box-profile">
		                    <img class="profile-user-img img-responsive img-circle" src="<?php echo $imgLoc; ?>" style="height:150px; width:150px" alt="User profile picture">
		                    <h3 class="profile-username text-center"><?php echo "$PRJNAME"; ?></h3>                    
		                    <p class="text-muted text-center"><?php echo $PRJLOCT; ?></p>
		                    <ul class="list-group list-group-unbordered">
		                    	<?php
		                    		$jlH	= site_url('c_project/c_joblist/get_all_joblist/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
		                    		$jlD	= site_url('c_project/c_joblistdet/gl180c21JL/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
		                    	?>
		                    	<li class="list-group-item">
		                    		<b>RAB</b> <a href="<?=$jlH?>" class="pull-right"><?php print number_format($PRJBOQ, 0); ?></a>
		                    	</li>
		                    	<li class="list-group-item">
		                    		<b>RAP</b> <a href="<?=$jlD?>" class="pull-right"><?php print number_format($PRJRAP, 0); ?></a>
		                    	</li>
		                        <?php
									if($PRJBOQ == '')
										$PRJBOQ = $PRJCOST;
										
		                        	$TargetFee	= $PRJBOQ - $PRJRAP;
								?>
		                    	<li class="list-group-item">
		                    		<b><?php echo "$Margin"; ?></b> <a class="pull-right"><?php print number_format($TargetFee, 0); ?></a>
		                    	</li>
		                    </ul>
		                    <a href="#" class="btn btn-primary btn-block" style="display:none"><b>Follow</b></a>
		                </div>
		          	</div>

		          <!-- About Me Box -->
		          	<div class="box box-danger">
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
		                	<li class="active"><a href="#settings" data-toggle="tab" onclick="showTab(1)"><?php echo $Information; ?></a></li>
		                    <li><a href="#projProg" data-toggle="tab" onclick="showTab(2)"><?php echo $Progress; ?></a></li>
		                    <li style="display: none;"><a href="#projDoc" data-toggle="tab" onclick="showTab(3)"><?php echo $projDoc; ?></a></li>
		                    <li><a href="#projAct" data-toggle="tab" onclick="showTab(4)"><?php echo $activity; ?></a></li>
		                    <li><a href="#projProfPic" data-toggle="tab" onclick="showTab(5)" style="display: none;"><?php echo $ProfilePicture; ?></a></li>
		                </ul>
		                <script type="text/javascript">
		                	function showTab(valTab)
		                	{
		                		if(valTab == 1)
		                			document.getElementById('settings').style.display = '';
		                		else if(valTab == 2)
		                			document.getElementById('projProg').style.display = '';
		                		else if(valTab == 3)
		                			document.getElementById('projDoc').style.display = '';
		                		else if(valTab == 4)
		                			document.getElementById('projAct').style.display = '';
		                		else if(valTab == 5)
		                			document.getElementById('projProfPic').style.display = '';
		                	}
		                </script>
		                <div class="tab-content">
		                    <div class="active tab-pane" id="settings">
		                        <form class="form-horizontal" name="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return validateInData()">
		                            <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
		                            <input type="Hidden" name="rowCount" id="rowCount" value="0">
		                            <div class="box box-success">
		                                <div class="box-header with-border">
		                                    <h3 class="box-title"><?php echo $ProjectInformation; ?></h3>
		                                </div>
		                                <br>
		                                <div class="form-group">
		                                    <label for="inputName" class="col-sm-3 control-label"><?php echo $ProjectNumber?></label>
		                                    <div class="col-sm-9">
		                                        <input type="text" class="form-control" name="PRJNUM1" id="PRJNUM1" value="<?php echo $DocNumber; ?>" disabled>
		                                        <input type="hidden" name="Patt_Number" id="Patt_Number" value="<?php echo $Patt_Number; ?>">
		                                        <input type="hidden" name="proj_Number" id="proj_Number" value="<?php echo $DocNumber; ?>" >
		                                        <input type="hidden"  id="lastPatternNumb" name="lastPatternNumb" size="20" value="<?php echo $lastPatternNumb1; ?>" />
		                                    </div>
		                                </div>
		                                <div class="form-group">
		                                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Code?> / <?php echo $ProjectName?></label>
		                                    <div class="col-sm-3">
		                                        <input type="text" class="form-control" name="PRJCODE" id="PRJCODE" value="<?php echo $PRJCODE; ?>" maxlength="20" onChange="functioncheck(this.value)" readonly>
		                                    </div>
		                                    <div class="col-sm-6">
		                                        <input type="hidden" class="form-control" name="PRJNAME" id="PRJNAME" size="50" value="<?php echo $default['PRJNAME']; ?>">
		                                        <input type="text" class="form-control" name="PRJNAME1" id="PRJNAME1" size="50" value="<?php echo $default['PRJNAME']; ?>" readonly>
		                                    </div>
		                                </div>
                                        <?php
                                        	$PRJDATEV		= strftime('%d %B %Y', strtotime($default['PRJDATE']));
                                        	$PRJEDATV		= strftime('%d %B %Y', strtotime($default['PRJEDAT']));
                                        	$PRJDATE_COV	= strftime('%d %B %Y', strtotime($default['PRJDATE_CO']));
											$PRJDATE_MNTV	= strftime('%d %B %Y', strtotime($PRJDATE_MNTD));
                                        ?>
		                                <div class="form-group">
		                                    <label for="inputName" class="col-sm-3 control-label"><?php echo $ImpDate; ?></label>
		                                    <div class="col-sm-3">
		                                        <div class="input-group date">
		                                            <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
		                                            <input type="hidden" name="PRJDATE" class="form-control pull-left" id="datepicker1" value="<?php echo $PRJDATE; ?>">
		                                            <input type="text" name="PRJDATEV" class="form-control pull-left" id="PRJDATEV" value="<?php echo $PRJDATEV; ?>" readonly>
		                                        </div>
		                                    </div>
		                                    <div class="col-sm-3">
		                                        <div class="input-group date">
		                                            <div class="input-group-addon">
		                                        	<i class="fa fa-calendar"></i>&nbsp;</div>
		                                        	<input type="hidden" name="PRJEDAT" class="form-control pull-left" id="datepicker2" value="<?php echo $PRJEDAT; ?>" style="width:120px">
		                                            <input type="text" name="PRJEDATV" class="form-control pull-left" id="PRJEDATV" value="<?php echo $PRJEDATV; ?>" readonly>
		                                        </div>
		                                    </div>
		                                </div>
										<div class="form-group">
											<label for="inputName" class="col-sm-3 control-label"><?php echo $MaintDate; ?></label>
		                                    <div class="col-sm-3">
		                                        <div class="input-group date">
		                                            <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
		                                            <input type="hidden" name="PRJDATE_MNT" class="form-control pull-left" id="datepicker3" value="<?php echo $PRJDATE_MNT; ?>">
		                                            <input type="text" name="PRJDATE_MNTV" class="form-control pull-left" id="PRJDATE_MNTV" value="<?php echo $PRJDATE_MNTV; ?>" readonly>
		                                        </div>
		                                    </div>
										</div>
		                                <div class="form-group" style="display: none;">
		                                    <label for="inputName" class="col-sm-3 control-label"><?php echo $CutOffDate; ?></label>
		                                    <div class="col-sm-9">
		                                        <div class="input-group date">
		                                            <div class="input-group-addon">
		                                        <i class="fa fa-calendar"></i>&nbsp;</div><input type="text" name="PRJDATE_CO" class="form-control pull-left" id="datepicker3" value="<?php echo $PRJDATE_CO; ?>" style="width:120px"></div>
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
		                                                    document.getElementById("isHidden").innerHTML = ' Project Code already exist ... !';
		                                                    document.getElementById("isHidden").style.color = "#ff0000";
		                                                }
		                                                else
		                                                {
		                                                    document.getElementById('CheckThe_Code').value= recordcount;
		                                                    document.getElementById("isHidden").innerHTML = ' Project Code : OK .. !';
		                                                    document.getElementById("isHidden").style.color = "green";
		                                                }
		                                            }
		                                        }
		                                        var PRJCODE = document.getElementById('PRJCODE').value;
		                                        
		                                        ajaxRequest.open("GET", "<?php echo base_url().'index.php/c_project/lst180c2hprj/getTheCode/';?>" + PRJCODE, true);
		                                        ajaxRequest.send(null);
		                                    }
		                                </script>
		                                <div class="form-group">
		                                    <label for="inputName" class="col-sm-3 control-label"><?php echo $ContractNo?> / <?php echo $Amount; ?></label>
		                                    <div class="col-sm-6">
		                                        <input type="text" class="form-control" name="PRJCNUM" id="PRJCNUM" value="<?php echo $PRJCNUM; ?>" readonly>
		                                    </div>
		                                    <div class="col-sm-3">
		                                        <input type="hidden" name="PRJCOST" id="PRJCOST" value="<?php echo $PRJCOST; ?>"  size="10" style="text-align:right" onKeyPress="return isIntOnlyNew(event);">
	                                            <input type="text" class="form-control" name="PRJCOST1" id="PRJCOST1" value="<?php print number_format($PRJCOST, $decFormat); ?>" style="text-align:right" onKeyPress="return isIntOnlyNew(event);" onChange="checkdecimal();" readonly>
		                                    </div>
		                                </div>
		                                <div class="form-group">
		                                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Category; ?> / <?php echo $Owner; ?></label>
		                                    <div class="col-sm-3">
		                                        <select name="PRJCATEG1" id="PRJCATEG1" class="form-control select2" disabled>
		                                            <option value=""> --- </option>
		                                            <option value="SPL" <?php if($PRJCATEG == 'SPL') { ?> selected <?php } ?>>Sipil</option>
		                                            <option value="GDG" <?php if($PRJCATEG == 'GDG') { ?> selected <?php } ?>>Gedung</option>
		                                        </select>
		                                        <input type="hidden" name="PRJCATEG" id="PRJCATEG" value="<?php echo $PRJCATEG; ?>" >
		                                    </div>
		                                    <div class="col-sm-6">
		                                        <select name="PRJOWN1" id="PRJOWN1" class="form-control select2" disabled>
		                                            <option value="0"> --- </option>
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
		                                        <input type="hidden" name="PRJOWN" id="PRJOWN" value="<?php echo $PRJOWN; ?>" >
		                                    </div>
		                                </div>
		                                <div class="form-group" <?php if($task == 'add') { ?> style="display:none" <?php } ?> style="display: none;">
		                                    <label for="inputName" class="col-sm-3 control-label">is Project ... ?</label>
		                                    <div class="col-sm-9">
		                                        <select name="isHO" id="isHO" class="form-control" style="max-width:350px; max-width:100px">
		                                            <option value="0" <?php if($isHO == 0) { ?> selected <?php } ?>><?php echo $Yes; ?></option>
		                                            <option value="1" <?php if($isHO == 1) { ?> selected <?php } ?>><?php echo $No; ?></option>
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
		                                </script>
		                                <div class="form-group">
		                                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Location ?></label>
		                                    <div class="col-sm-9">
		                                        <input type="text" class="form-control" name="PRJLOCT" id="PRJLOCT" value="<?php echo $PRJLOCT; ?>" maxlength="50" readonly>
		                                    </div>
		                                </div>
		                                <div class="form-group" style="display: none;">
		                                    <label for="inputName" class="col-sm-3 control-label"><?php echo $OutofTownProject ?></label>
		                                    <div class="col-sm-9">
		                                        <select name="PRJLKOT" id="PRJLKOT" class="form-control select2">
		                                            <option value="0" <?php if($PRJLKOT == 0) { ?> selected <?php } ?>>No</option>
		                                            <option value="1" <?php if($PRJLKOT == 1) { ?> selected <?php } ?>>Yes</option>
		                                        </select>
		                                    </div>
		                                </div>
		                                <div class="form-group">
		                                    <label for="inputName" class="col-sm-3 control-label"><?php echo $ProjectManager ?></label>
		                                    <div class="col-sm-9">
		                                        <select name="PRJ_MNG1[]" id="PRJ_MNG1" class="form-control select2" multiple="multiple" data-placeholder="&nbsp;&nbsp;&nbsp;Project Manager" disabled>
		                                            <?php
		                                                /*$sqlEmp	= "SELECT Emp_ID, First_Name, Last_Name, Email
		                                                            FROM tbl_employee WHERE Pos_Code LIKE 'PM%' ORDER BY First_Name";*/
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
		                                        <input type="hidden" name="PRJ_MNG[]" id="PRJ_MNG" value="<?php echo $PRJ_MNG; ?>" >
		                                    </div>
		                                </div>
		                                <div class="form-group" style="display: none;">
		                                    <label for="inputName" class="col-sm-3 control-label"><?php echo $SurveyorQty ?></label>
		                                    <div class="col-sm-9">
		                                        <input type="text" class="form-control" style="max-width:250px" name="QTY_SPYR" id="QTY_SPYR" value="<?php echo number_format($QTY_SPYR, $decFormat); ?>" size="30" >
		                                    </div>
		                                </div>
		                                <div class="form-group">
		                                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Notes ?></label>
		                                    <div class="col-sm-9">
		                                        <textarea class="form-control" name="PRJNOTE"  id="PRJNOTE" style="height: 90px; display: none;"><?php echo set_value('PRJNOTE', isset($default['PRJNOTE']) ? $default['PRJNOTE'] : ''); ?></textarea>
		                                        <div class="alert alert-warning alert-dismissible">
									                <?php echo $default['PRJNOTE']; ?>
								              	</div>
		                                    </div>
		                                </div>
		                            	<br>
		                            </div>
		                            <div class="box box-warning" style="display: none;">
		                                <div class="box-header with-border">
		                                    <h3 class="box-title"><?php echo $PlannedBy; ?></h3>
		                                </div>
		                                <br>
		                                <div class="form-group">
		                                    <label for="inputName" class="col-sm-3 control-label">1. <?php echo $Structure ?></label>
		                                    <div class="col-sm-9">
		                                        <input type="text" class="form-control" name="PRC_STRK" id="PRC_STRK" value="<?php echo $PRC_STRK; ?>" size="30" >
		                                    </div>
		                                </div>
		                                <div class="form-group">
		                                    <label for="inputName" class="col-sm-3 control-label">2. <?php echo $Architect ?></label>
		                                    <div class="col-sm-9">
		                                        <input type="text" class="form-control" name="PRC_ARST" id="PRC_ARST" value="<?php echo $PRC_ARST; ?>" size="30" >
		                                    </div>
		                                </div>
		                                <div class="form-group">
		                                    <label for="inputName" class="col-sm-3 control-label">3. <?php echo $Mechanical ?></label>
		                                    <div class="col-sm-9">
		                                        <input type="text" class="form-control" name="PRC_MKNK" id="PRC_MKNK" value="<?php echo $PRC_MKNK; ?>" size="30" >
		                                    </div>
		                                </div>
		                                <div class="form-group">
		                                    <label for="inputName" class="col-sm-3 control-label">4. <?php echo $Electrical ?></label>
		                                    <div class="col-sm-9">
		                                        <input type="text" class="form-control" name="PRC_ELCT" id="PRC_ELCT" value="<?php echo $PRC_ELCT; ?>" size="30" >
		                                    </div>
		                                </div>
		                            	<br>
		                            </div>
	                                <div class="form-group" style="display: none;">
	                                    <label for="inputName" class="col-sm-3 control-label"><?php echo $ProjectStatus ?></label>
	                                    <div class="col-sm-9">
	                                        <select name="PRJSTAT" id="PRJSTAT" class="form-control select2" style="max-width:120px">
	                                            <option value="0" <?php if($PRJSTAT == 0) { ?> selected <?php } ?>><?php echo $Inactive; ?></option>
	                                            <option value="1" <?php if($PRJSTAT == 1) { ?> selected <?php } ?>><?php echo $Active; ?></option>
	                                        </select>
	                                    </div>
	                                </div>
	                                <!-- <br> -->
	                                <div class="form-group" style="display: none;">
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
	                                </div>
	                                <!-- <br> -->
		                        </form>
		                    </div>
		                    <script>
								function validateInData()
								{
									/*nextornot = document.getElementById('CheckThe_Code').value;
									if(nextornot > 0)
									{
										alert('Project Code Already Exist. Please Change.');
										document.getElementById('PRJCODE').value = '';
										document.getElementById('PRJCODE').focus();
										return false;
									}*/

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
									
									PRJNAME = document.getElementById('PRJNAME').value;
									if(PRJNAME == '')
									{
										swal('<?php echo $prjNmEmpt; ?>',
										{
											icon:"warning"
										})
										.then(function()
										{
											document.getElementById('PRJNAME').focus();
										});
										return false;
									}
									
									PRJOWN = document.getElementById('PRJOWN').value;
									if(PRJOWN == '0')
									{
										swal('<?php echo $ownNmEmpt; ?>',
										{
											icon:"warning"
										})
										.then(function()
										{
											document.getElementById('PRJOWN').focus();
										});
										return false;
									}
									
									var ISCHANGE	= document.getElementById('ISCHANGEX').value;
									if(ISCHANGE == 1)
									{
										var REFCHGNO	= document.getElementById('REFCHGNO').value;
										if(REFCHGNO == '')
										{
											alert('Please input reference number.');
											document.getElementById('REFCHGNO').focus();
											return false;
										}
										
										var PRJCOST2 = eval(document.getElementById('PRJCOST22a')).value.split(",").join("");
										if(PRJCOST2 == 0)
										{
											alert('Please input new of Contract Value.');
											document.getElementById('PRJCOST22a').focus();
											return false;
										}
									}
									else
									{
										document.getElementById('REFCHGNO').value 	= '';
										document.getElementById('PRJCOST22a').value = '0.00';
										document.getElementById('PRJCOST22').value 	= '0';
									}
								}
							</script>
		                    <div class="active tab-pane" id="projProg" style="display: none;">
		                        <div class="box box-success">
					                <div class="box-body">
					                    <div id="line-chartx" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
					                </div>
					            </div>
		               		</div>
		                    <div class="active tab-pane" id="projDoc" style="display: none;">
								<div class="box box-success">
									<div class="box-header with-border">
										<i class="fa fa-list"></i>
										<h3 class="box-title">Daftar File</h3>
									</div>
									<div class="box-body">
						                <table id="example1" class="table table-bordered table-striped" width="100%">
						                    <thead>
						                        <tr>
						                            <th style="text-align:center; vertical-align:middle; width: 2%"><?php echo $Date ?> </th>
						                            <th style="text-align:center; vertical-align:middle; width: 96%"><?php echo $Description ?></th>
						                            <th style="text-align:center; vertical-align:middle; width: 2%">&nbsp;</th>
						                      	</tr>
						                    </thead>
						                    <tbody>
						                    </tbody>
						                </table>
			                            <div class="form-group">
			                                <?php
			                                    echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>');
			                                ?>
			                            </div>
									</div>
								</div>
		                    </div>
		                    <div class="active tab-pane" id="projAct" style="display: none;">
								<div class="box box-success">
								</div>
								<?php
									$s_pinfo		= "SELECT A.*, B.imgemp_filenameX AS file_nm FROM tbl_project_liveinfo A
															LEFT JOIN tbl_employee_img B ON A.emp_id = B.imgemp_empid
														WHERE A.prjcode = '$PRJCODE' ORDER BY A.created DESC";
									$r_pinfo		= $this->db->query($s_pinfo)->result();
									foreach($r_pinfo as $rw_pinfo) :
										$info_code	= $rw_pinfo->info_code;
										$emp_id		= $rw_pinfo->emp_id;
										$emp_name	= $rw_pinfo->emp_name;
										$emp_msg	= $rw_pinfo->emp_msg;
										$islast		= $rw_pinfo->islast;
										$created	= $rw_pinfo->created;
										$file_nm	= $rw_pinfo->file_nm;

										$dateIND1 	= new DateTime($created);
										$dateIND 	= strftime('%A', $dateIND1->getTimestamp());

										$crtDV		= strftime('%d %B %y', strtotime($created));
										$crtTV		= date('H:i', strtotime($created));

										$s_img		= "SELECT Pos_Code FROM tbl_employee WHERE Emp_ID = '$emp_id'";
										$r_img		= $this->db->query($s_img)->result();
										foreach($r_img as $rw_img) :
											$Pos_Code 	= $rw_img->Pos_Code;
										endforeach;

										$POSNM  	= "-";
										$sqlDEP 	= "SELECT POSS_NAME FROM tbl_position_str WHERE POSS_CODE = '$Pos_Code'";
										$resDEP 	= $this->db->query($sqlDEP)->result();
										foreach($resDEP as $rowDEP) :
											$POSNM 	= $rowDEP->POSS_NAME;
										endforeach;

										$imgLoc		= base_url('assets/AdminLTE-2.0.5/emp_image/'.$emp_id.'/'.$file_nm);
										if (!file_exists('assets/AdminLTE-2.0.5/emp_image/'.$emp_id))
										{
											$imgLoc	= base_url('assets/AdminLTE-2.0.5/emp_image/username.jpg');
										}
										?>
										<div class="post">
											<div class="user-block">
												<img class="img-circle img-bordered-sm" src="<?=$imgLoc?>" alt="user image">
												<span class="username">
													<a href="#"><?=$emp_name?></a>
													<!-- <a href="#" class="pull-right btn-box-tool"><i class="fa fa-clock-o"></i></a> -->
												</span>
												<span class="description"><?=$POSNM?> - <?=$crtTV?><div class="pull-right"><?=$dateIND.", ".$crtDV?></div></span>
											</div>
											<p>
												<?=$emp_msg?>
											</p>
											<div class="row margin-bottom">
												<?php
													$s_imgPC	= "tbl_project_liveinfopic WHERE info_code = '$info_code' AND prjcode = '$PRJCODE'";
													$r_imgPC	= $this->db->count_all($s_imgPC);

													if($r_imgPC > 0)
													{
														$i 			= 0;
														$s_imgP		= "SELECT picture_name FROM tbl_project_liveinfopic
																		WHERE info_code = '$info_code' AND prjcode = '$PRJCODE'";
														$r_imgP		= $this->db->query($s_imgP)->result();
														foreach($r_imgP as $rw_imgP) :
															$i 		= $i+1;

															$picInf = $rw_imgP->picture_name;
															$imgP	= base_url('assets/AdminLTE-2.0.5/project_image/'.$PRJCODE.'/prjlivinfo/'.$picInf);
															if($i==1) { ?>
																<div class="col-sm-6">
											                      	<img class="img-responsive" src="<?=$imgP?>" alt="Photo">
											                    </div>
															<?php } ?>
															<?php if($i == 2) { ?>
											                    <div class="col-sm-6">
																	<div class="row">
																		<div class="col-sm-6">
																			<img class="img-responsive" src="<?=$imgP?>" alt="Photo">
																			<?php } if($i == 3) { ?>
																			<br>
																			<img class="img-responsive" src="<?=$imgP?>" alt="Photo">
																		</div>
																		<?php } if($i == 4) { ?>
																			<div class="col-sm-6">
																				<img class="img-responsive" src="<?=$imgP?>" alt="Photo">
																				<?php } if($i == 5) { ?>
																					<br>
																					<img class="img-responsive" src="<?=$imgP?>" alt="Photo">
																			</div>
																	</div>
																</div>
															<?php }
														endforeach;
													}
												?>
											</div>
										</div>
										<?php
									endforeach;
								?>
								<script type="text/javascript">
									function chkForm()
									{
										emp_msg	= document.getElementById('emp_msg').value;
										if(emp_msg == '')
										{
											swal('Anda belum mengisi pesan.',
											{
												icon: "warning",
											})
									        .then(function()
									        {
									            swal.close();
									            $("#emp_msg").focus();
									        });
											return false;
										}

								        var url 	= "<?=$secAdd?>";

										var formData 	= {
															prjcode	: $("#prjcode").val(),
															empid	: $("#empid").val(),
															emp_msg	: $("#emp_msg").val()
															userfile: $("#userfile").val()
														};
										$.ajax({
								            type: 'POST',
								            url: "<?php echo site_url('c_project/lst180c2hprj/svForm')?>",
								            data: formData,
								            success: function(response)
								            {
								            	$('#sy_code').val(0).trigger('change');
								            	$('#ag_smt').val(0).trigger('change');
								            	$('#ag_name').val();
								            	$('#frm')[0].reset();

								            	/*swal(response, 
												{
													icon: "success",
												});*/
								                $('#example').DataTable().ajax.reload();
								            }
								        });
									}
								</script>
								<div class="post" style="display: none;">
									<div class="user-block">
										<img class="img-circle img-bordered-sm" src="<?=$imgLoc?>" alt="User Image">
										<span class="username">
											<a href="#">Adam Jones</a>
											<a href="#" class="pull-right btn-box-tool"><i class="fa fa-times"></i></a>
										</span>
										<span class="description">Posted 5 photos - 5 days ago</span>
									</div>
									<div class="row margin-bottom">
										<div class="col-sm-6">
											<img class="img-responsive" src="<?=$imgLoc?>" alt="Photo">
										</div>
										<div class="col-sm-6">
											<div class="row">
												<div class="col-sm-6">
													<img class="img-responsive" src="<?=$imgLoc?>" alt="Photo">
													<br>
													<img class="img-responsive" src="<?=$imgLoc?>" alt="Photo">
												</div>
												<div class="col-sm-6">
													<img class="img-responsive" src="<?=$imgLoc?>" alt="Photo">
													<br>
													<img class="img-responsive" src="<?=$imgLoc?>" alt="Photo">
												</div>
											</div>
										</div>
									</div>

									<ul class="list-inline">
										<li><a href="#" class="link-black text-sm"><i class="fa fa-share margin-r-5"></i> Share</a></li>
										<li><a href="#" class="link-black text-sm"><i class="fa fa-thumbs-o-up margin-r-5"></i> Like</a></li>
										<li class="pull-right">
										<a href="#" class="link-black text-sm"><i class="fa fa-comments-o margin-r-5"></i> Comments (5)</a></li>
									</ul>

									<input class="form-control input-sm" type="text" placeholder="Type a comment">
								</div>
		                    </div>
		                    <div class="active tab-pane" id="projProfPic" style="display: none;">
		                        <form class="form-horizontal" name="frm" method="post" action="<?php echo $urlUpdDoc; ?>" enctype="multipart/form-data" onSubmit="return checkData()">
		                            <div class="box box-success">
		                                <div class="box-header with-border">
		                                    <h3 class="box-title">Upload <?php echo $ProfilePicture; ?></h3>
		                                </div>
		                                <br>
		                                <div class="form-group">
		                                    <label for="inputName" class="col-sm-3 control-label"><?php echo "$Code $Project"; ?> </label>
		                                    <div class="col-sm-9">
		                                      <input type="text" class="form-control" name="PRJCODE" id="PRJCODE" placeholder="Project Code" value="<?php echo $PRJCODE; ?>" readonly>
		                                    </div>
		                                </div>
		                                <div class="form-group">
		                                    <label for="inputName" class="col-sm-3 control-label"><?php echo $ProjectName ?> </label>
		                                    <div class="col-sm-9">
		                                      <input type="text" class="form-control" name="PRJNAME" id="PRJNAME" placeholder="File Name" value="<?php echo $PRJNAME; ?>" readonly>
		                                    </div>
		                                </div>
		                                <div class="form-group">
		                                    <label for="inputName" class="col-sm-3 control-label"><?php echo $ChooseFile ?> </label>
		                                    <div class="col-sm-9">
		                                      <input type="file" name="userfile" class="filestyle" data-buttonName="btn-primary"/>
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
							</script>
						</div>
					</div>
	        	</div>
	      	</div>
        	<?php
				$act_lnk = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        		if($DefEmp_ID == 'D15040004221')
                	echo "<font size='1'><i>$act_lnk</i></font>";
            ?>
	    </section>
	</body>
</html>

<!-- START FLOT CHARTS -->
	<script src="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/flot/jquery.flot.min.js') ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/flot/jquery.flot.resize.min.js') ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/flot/jquery.flot.pie.min.js') ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/flot/jquery.flot.categories.min.js') ?>" type="text/javascript"></script>
<!-- END FLOT CHARTS -->

<!-- START MORRIS CHARTS -->
	<script src="<?php echo base_url('assets/js/raphael-min.js') ?>"></script>
    <script src="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/morris/morris.min.js') ?>" type="text/javascript"></script>
<!-- END MORRIS CHARTS -->
<script src="//code.highcharts.com/themes/sand-signika.js"></script>
<?php
	$sqlProgC	= "tbl_projprogres WHERE proj_Code = '$PRJCODE' AND progress_Type = 3 AND isShow = 1";
	$resProgC	= $this->db->count_all($sqlProgC);
	if($resProgC <= 20)
	{
		$pmbg	= 1;
	}
	else
	{
		$pmbg	= $resProgC / 20;
	}
	$ADDR	= 0;
	for($PC = 1; $PC <= $resProgC; $PC++)
	{
		$ADDRX	= $ADDR + $PC;
	}
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
	} );
</script>

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
	});
  
	Highcharts.chart('line-chartx', {
		chart: {
			type: 'line'
		},
		title: {
			text: 'Progres/Prosentasi Kemajuan Pekerjaan'
		},
		subtitle: {
			//text: 'Source: WorldClimate.com'
		},
		xAxis: {
			title: {
				text: 'Minggu ke'
			},
			categories: [
			<?php
				$sqlProgC	= "tbl_projprogres WHERE proj_Code = '$PRJCODE' AND progress_Type = 3 AND isShow = 1";
				$resProgC	= $this->db->count_all($sqlProgC);
				if($resProgC <= 20)
				{
					$pmbg	= 1;
				}
				else
				{
					$pmbg	= $resProgC / 20;
				}
				$ADDR	= 0;
				for($PC = 1; $PC <= $resProgC; $PC++)
				{
					$ADDRX	= $ADDR + $PC;
					echo "'$ADDRX',";
				}
			?>
			]
		},
		yAxis: {
			title: {
				text: 'Progress/Prosentasi'
			}
		},
		plotOptions: {
			line: {
				dataLabels: {
					enabled: true
				},
				enableMouseTracking: false
			}
		},
		series: [{
			name: 'Plan',
			color: "#00F",
			data: [<?php
						$jumTotPlanAkuma 	= '';
						$GraphicTitleText	= 3;
						$sqla   	= "SELECT A.proj_Code, B.PRJNAME
										FROM tbl_projprogres A
										INNER JOIN tbl_project B ON B.PRJCODE = A.proj_Code
										WHERE A.proj_Code = '$PRJCODE' AND progress_Type = 3
										GROUP BY proj_Code";
						$ressqla 	= $this->db->query($sqla)->result();
						
						foreach($ressqla as $rowa) :
							$proj_Code 	= $rowa->proj_Code;
							$projName 	= $rowa->PRJNAME;
							$proj_Name	= "Project : $proj_Code";
							
							$NoU 		= 0;
							$sql0   	= "SELECT MAX(day(Prg_Date1)) AS myDay, MAX(MONTH(Prg_Date1)) AS myMonth, 
												MAX(YEAR(Prg_Date1)) AS myYear, MAX(Prg_Date1) AS myDate,
												MAX(Prg_PlanAkum) AS Prg_PlanAkum, MAX(Prg_RealAkum) AS Prg_RealAkum, isShow, 
												isShowRA, isShowDev
											FROM tbl_projprogres WHERE proj_Code = '$PRJCODE' 
												AND progress_Type = $GraphicTitleText AND isShow = 1 
											GROUP BY day(Prg_Date1), month(Prg_Date1), year(Prg_Date1) ORDER BY Prg_Date1";
							$ressql0 	= $this->db->query($sql0)->result();
							foreach($ressql0 as $row0) :
								$NoU 			= $NoU + 1;
								$DayAx 			= $row0->myDay;
								$myDate			= $row0->myDate;
								$Prg_PlanAkum	= $row0->Prg_PlanAkum;
								$Prg_RealAkum	= $row0->Prg_RealAkum;
								$Prg_Dev2 		= $Prg_RealAkum - $Prg_PlanAkum;
								$isShow			= $row0->isShow;
								$isShowRA		= $row0->isShowRA;
								$isShowDev		= $row0->isShowDev;
								if($isShow == 1)
								{
									echo "$Prg_PlanAkum,";
								}
							endforeach;
						endforeach;
					?>]
		}, {
			name: 'Real',
			color: "#390",
			data: [<?php
						$jumTotPlanAkuma 	= '';
						$GraphicTitleText	= 3;
						$sqla   	= "SELECT A.proj_Code, B.PRJNAME
										FROM tbl_projprogres A
										INNER JOIN tbl_project B ON B.PRJCODE = A.proj_Code
										WHERE A.proj_Code = '$PRJCODE' AND progress_Type = $GraphicTitleText
										GROUP BY proj_Code";
						$ressqla 	= $this->db->query($sqla)->result();
						
						foreach($ressqla as $rowa) :
							$proj_Code 	= $rowa->proj_Code;
							$projName 	= $rowa->PRJNAME;
							$proj_Name	= "Project : $proj_Code";
							
							$getCountx		= "tbl_projprogres WHERE proj_Code = '$PRJCODE' AND progress_Type = $GraphicTitleText AND isShow = 1";
							$resGetCountx	= $this->db->count_all($getCountx);
							
							$NoU 		= 0;
							$sql0   	= "SELECT MAX(day(Prg_Date1)) AS myDay, MAX(MONTH(Prg_Date1)) AS myMonth, 
												MAX(YEAR(Prg_Date1)) AS myYear, MAX(Prg_Date1) AS myDate,
												MAX(Prg_PlanAkum) AS Prg_PlanAkum, MAX(Prg_RealAkum) AS Prg_RealAkum, isShow, 
												isShowRA, isShowDev
											FROM tbl_projprogres WHERE proj_Code = '$PRJCODE' 
												AND progress_Type = $GraphicTitleText AND isShow = 1 
											GROUP BY day(Prg_Date1), month(Prg_Date1), year(Prg_Date1) ORDER BY Prg_Date1";
							$ressql0 	= $this->db->query($sql0)->result();
							foreach($ressql0 as $row0) :
								$NoU 			= $NoU + 1;
								$DayAx 			= $row0->myDay;
								$myDate			= $row0->myDate;
								$Prg_PlanAkum	= $row0->Prg_PlanAkum;
								$Prg_RealAkum	= $row0->Prg_RealAkum;
								$Prg_Dev2 		= $Prg_RealAkum - $Prg_PlanAkum;
								$isShow			= $row0->isShow;
								$isShowRA		= $row0->isShowRA;
								$isShowDev		= $row0->isShowDev;
								if($isShowRA == 1)
								{
									echo "$Prg_RealAkum,";
								}
							endforeach;
						endforeach;
					?>]
		}]
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