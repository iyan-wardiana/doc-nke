<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 16 September 2018
 * File Name	= project_mcbu_form.php
 * Location		= -
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$FlagUSER 	= $this->session->userdata['FlagUSER'];

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

$proj_amountIDR1	= 0;
$sqlPRJ 			= "SELECT ISCHANGE, PRJCOST, PRJCOST2 FROM tbl_project
						WHERE PRJCODE = '$PRJCODE'";
$resultPRJ 			= $this->db->query($sqlPRJ)->result();
foreach($resultPRJ  as $rowPRJ) :
	$ISCHANGE		= $rowPRJ->ISCHANGE;
	$proj_amountIDR0= $rowPRJ->PRJCOST;
	$proj_amountIDR2= $rowPRJ->PRJCOST2;	// Added by SI
	if($ISCHANGE == 1)
	{
		$proj_amountIDR1= $rowPRJ->PRJCOST2;
	}
	$proj_amountIDR1= $proj_amountIDR0 + $proj_amountIDR2;	
	$PRJCOSTnPPn	= round(($proj_amountIDR1 * 0.1) + $proj_amountIDR1);
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
$proj_amountIDR		= round($proj_amountIDR1 + $TOTSI_APPVAL);
$proj_amountIDRX	= round($proj_amountIDR1 + $TOTSI_APPVAL);
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
	$MC_STEP		= 0;
	$PRJCODE		= $PRJCODE;
	
	$MCCODE			= substr($lastPatternNumb, -4);
	$MCYEAR			= date('y');
	$MCMONTH		= date('m');
	$MCDATED		= date('m');
	$MC_MANNO		= "$Pattern_Code.$MCYEAR.$MCMONTH.$MCDATED.$MCCODE"; // MANUAL CODE
	
	$MC_DateY 		= date('Y');
	$MC_DateM 		= date('m');
	$MC_DateD 		= date('d');
	$MC_DATE		= "$MC_DateM/$MC_DateD/$MC_DateY";
	//$MC_ENDDATE 	= $MC_Date;
	$MC_CHECKD		= "$MC_DateM/$MC_DateD/$MC_DateY";
	$MC_CREATED 	= "$MC_DateM/$MC_DateD/$MC_DateY";
	
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
	$GETFROM		= 0;
	$MC_DPPER		= $MC_DPPER1;
	$MC_DPVAL		= $MC_DPVAL1;
	$MC_DPVALnPPn	= $MC_DPVAL2;
	$MC_RETVAL		= round(0.05 * $proj_amountIDRXnPPn);
	$MC_DPBACK		= 0;
	$MC_RETCUT		= 0;
	$MC_PROG		= 0;
	$MC_PROGVAL		= 0;
	$MC_PROGVALXY	= 0;
	$MC_PROGAPP 	= 0;
	$MC_PROGAPPVAL 	= 0;
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
	$MC_VALBEF		= 0;
	$MC_AKUMNEXT	= 0;
	$MC_TOTVAL		= 0;
	$MC_NOTES		= '';
	$MC_EMPID		= $DefEmp_ID;
	$MC_STAT		= 1;
	
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
	
	$MC_ENDDATE0	= mktime(0,0,0,$MC_DateM,$MC_DateD,$MC_DateY);
	$MC_ENDDATE1	= 14;
	$MC_ENDDATE 	= date("m/d/Y",strtotime("+$MC_ENDDATE1 days",$MC_ENDDATE0));
	$MC_OWNER		= $PRJOWN;
	
	// MENCARI NILAI PEMBAYARAN SEBELUMNYA
	$MC_AKUMNEXT	= 0;
	$PATT_NUMBEF	= $lastPatternNumb1 - 1; // PATTERN NUMBER SEBELUMNYA
	$sqlMC 			= "tbl_mcheader WHERE PRJCODE = '$PRJCODE' AND PATT_NUMBER = $PATT_NUMBEF AND MC_STAT = 3";
	$resMC 			= $this->db->count_all($sqlMC);
	if($resMC > 0)
	{
		/*$sqlAKN	= "SELECT SUM(MC_AKUMNEXT) AS MC_AKUMNEXT FROM tbl_mcheader 
					WHERE PRJCODE = '$PRJCODE' AND PATT_NUMBER = $PATT_NUMBEF AND MC_STAT = 3";*/
		$sqlAKN	= "SELECT SUM(MC_AKUMNEXT) AS MC_AKUMNEXT FROM tbl_mcheader 
					WHERE PRJCODE = '$PRJCODE' AND MC_STAT = 3";
		$resAKN	= $this->db->query($sqlAKN)->result();
		foreach($resAKN as $rowAKN) :
			$MC_VALBEF = $rowAKN->MC_AKUMNEXT;
		endforeach;
	}
	// APABILA TIDAK ADA, BERARTI DARI AKUMULASI DP
	else
	{
		//$MC_VALBEF 	= $MC_DPVAL1;			
	}
	
	// CEK APAKAH PERNAH DIBUATKAN MC ATAU TIDA. APABILA SUDAH MAKA AMBIL NILAI MAKSIMUM DARI MC. APABILA TIDAK ADA, AMBIL NILAI MAX DARI DP
	$sqlMCEXIST		= "tbl_mcheader WHERE PRJCODE = '$PRJCODE'";
	$resMCEXIST		= $this->db->count_all($sqlMCEXIST);
	if($resMCEXIST > 0)
	{
		$sqlMAXMC 	= "SELECT MAX(MC_STEP) AS MAXSTEP FROM tbl_mcheader
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
	
	$MC_TOTPROGRESS	= $MC_PROGVAL + $MC_VALADD + $MC_MATVAL;
	$MC_PAYBEFRET	= $MC_TOTPROGRESS + $MC_DPVAL - $MC_DPBACK - $MC_RETCUT;
	$MC_PAYAKUM		= $MC_PAYBEFRET;
	$MC_PAYMENT		= $MC_PAYAKUM - $MC_VALBEF;
	$MC_PAYDUE		= 0;
	$MC_PAYDUEPPh	= 0;	
	$MC_TOTVAL_PPn	= 0;
	$MC_TOTVAL_PPh	= 0;
	$TOTPAYMENT		= 0;
}
else
{
	$isSetDocNo = 1;
	$DocNumber		= $default['MC_CODE'];
	$MC_CODE 		= $default['MC_CODE'];
	$MC_MANNO 		= $default['MC_MANNO'];
	$MC_STEP 		= $default['MC_STEP'];
	$MAXSTEP		= $MC_STEP;
	$GETFROM 		= $default['GETFROM'];
	$PRJCODE 		= $default['PRJCODE'];
	$MC_OWNER 		= $default['MC_OWNER'];
	$MC_DATE1 		= $default['MC_DATE'];
	$MC_DATE		= date('m/d/Y',strtotime($MC_DATE1));
	$MC_ENDDATE1 	= $default['MC_ENDDATE']; 
	$MC_ENDDATE		= date('m/d/Y',strtotime($MC_ENDDATE1));
	$MC_CHECKD1		= $default['MC_CHECKD']; 
	$MC_CHECKD		= date('m/d/Y',strtotime($MC_CHECKD1));
	$MC_CREATED1 	= $default['MC_CREATED'];
	$MC_CREATED		= date('m/d/Y',strtotime($MC_CREATED1));
	$MC_RETVAL 		= $default['MC_RETVAL'];
	$MC_RETCUT 		= $default['MC_RETCUT'];
	$MC_DPPER 		= $default['MC_DPPER'];
	$MC_DPVAL 		= $default['MC_DPVAL'];
	$MC_DPVALnPPn	= $MC_DPVAL + round(0.1 * $MC_DPVAL);
	$MC_DPBACK 		= $default['MC_DPBACK'];
	$MC_PROG 		= $default['MC_PROG'];
	$MC_PROGVAL 	= $default['MC_PROGVAL'];
	$MC_PROGVALXY	= $MC_PROGVAL + round(0.1 * $MC_PROGVAL);;
	$MC_PROGAPP 	= $default['MC_PROGAPP'];
	$MC_PROGAPPVAL 	= $default['MC_PROGAPPVAL'];
	$MC_VALADD 		= $default['MC_VALADD'];
	$MC_MATVAL 		= $default['MC_MATVAL'];
	$MC_VALBEF		= $default['MC_VALBEF'];
	$MC_AKUMNEXT 	= $default['MC_AKUMNEXT'];
	$MC_TOTVAL 		= $default['MC_TOTVAL'];
	$MC_NOTES 		= $default['MC_NOTES'];
	$MC_EMPID 		= $default['MC_EMPID'];
	$MC_STAT 		= $default['MC_STAT'];
	$PATT_YEAR 		= $default['PATT_YEAR'];
	$PATT_MONTH 	= $default['PATT_MONTH'];
	$PATT_DATE 		= $default['PATT_DATE'];
	$PATT_NUMBER 	= $default['PATT_NUMBER'];
	
	$MC_TOTPROGRESS	= $MC_PROGVAL + $MC_VALADD + $MC_MATVAL;
	$MC_PAYBEFRET	= $MC_AKUMNEXT;
	$MC_PAYAKUM		= $MC_PAYBEFRET;
	//$MC_PAYMENT		= $MC_PAYAKUM - $MC_VALBEF;
	$MC_TOTVAL_PPn	= $default['MC_TOTVAL_PPn'];
	$MC_TOTVAL_PPh	= $default['MC_TOTVAL_PPh'];
	$MC_PAYMENT		= $MC_TOTPROGRESS + $MC_TOTVAL_PPn;
	//$MC_PAYDUE	= $MC_PAYMENT + $MC_TOTVAL_PPn;
	$MC_PAYDUE		= $MC_PAYMENT;
	//$MC_PAYDUEPPh	= round(0.03 * $MC_PAYMENT);
	//$TOTPAYMENT	= $MC_PAYMENT - $MC_TOTVAL_PPh;
	$TOTPAYMENT		= $MC_AKUMNEXT;
}

$sqlOWN	= "SELECT own_Code, own_Title, own_Name FROM tbl_owner WHERE own_Status = '1'";
$resOWN	= $this->db->query($sqlOWN)->result();
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
?>

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>
    <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/mc.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $h2_title; ?>
    <small><?php echo $PRJNAMEH; ?></small>  </h1>
  <?php /*?><ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#">Tables</a></li>
    <li class="active">Data tables</li>
  </ol><?php */?>
</section>
<!-- Main content -->
<section class="content">	
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border" style="display:none">               
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body chart-responsive">
                <form name="frm" id="frm" method="post" action="<?php echo $form_action; ?>">
                    <input type="hidden" name="IS_LAST" id="IS_LAST" value="<?php echo $IS_LAST; ?>">
                    <input type="hidden" name="APP_LEVEL" id="APP_LEVEL" value="<?php echo $APP_LEVEL; ?>">
                    <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
                    <input type="Hidden" name="rowCount" id="rowCount" value="0">
                    <input type="hidden" name="FlagUSER" id="FlagUSER" value="<?php echo $FlagUSER; ?>">
                    <input type="hidden" name="ISAPPROVE" id="ISAPPROVE" value="<?php echo $ISAPPROVE; ?>">
                    <input type="hidden" name="MC_STATX" id="MC_STATX" value="<?php echo $MC_STAT; ?>">
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
                            <input type="hidden" class="textbox" name="MC_CODE" id="MC_CODE" size="30" value="<?php echo $MC_CODE; ?>" />
                            <input type="hidden" class="textbox" name="PATT_NUMBER" id="PATT_NUMBER" size="30" value="<?php echo $PATT_NUMBER; ?>" />
                          </td>
                          <td width="12%" align="left" class="style1"><?php if($ISAPPROVE == 0) { ?> <?php echo $DateofFiling ?>  <?php } else { ?><?php echo $MCDate ?>  <?php } ?></td>
                          <td width="35%" align="left" class="style1">
                                  <div class="input-group date">
                                    <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>&nbsp;</div>
                                    <input type="text" name="MC_DATE" class="form-control pull-left" id="datepicker" value="<?php echo $MC_DATE; ?>" style="width:150px">
                                </div>
                            </td>
                        <!-- MC_CODE, PATT_NUMBER, MC_MANNO -->
                      </tr>
                      <tr>
                          <td align="left" class="style1">&nbsp;&nbsp;<?php echo $ManualNumber ?> </td>
                          <td align="left" class="style1">:</td>
                          <td align="left" class="style1">
                            <?php 
                            //if($FlagUSER == 'APPSI')
                            if($ISAPPROVE == 1)
                            { 
                                ?>
                                    <input type="text" name="MC_MANNO" id="MC_MANNO" value="<?php echo $MC_MANNO; ?>" class="form-control" style="max-width:350px">
                                <?php
                            }
                            //if($FlagUSER != 'APPSI')
                            if($ISAPPROVE == 0)
                            {
                                if($MC_MANNO != "")
                                {
                                    echo "/ &nbsp;&nbsp; $MC_MANNO";
                                }
                                else
                                {
                                    echo "/ &nbsp;&nbsp; -";
                                }
                            }
                            ?>
                          <input type="hidden" class="form-control" style="max-width:140px; text-align:right;" name="MC_TOTVAL_PPnx" id="MC_TOTVAL_PPnx" value="<?php echo number_format($MC_TOTVAL_PPn, $decFormat); ?>" onBlur="getTOTPlusPPN(this.value)" onKeyPress="return isIntOnlyNew(event);" disabled></td>
                          <td align="left" class="style1"><?php echo $ApproveDateTarget ?> </td>
                          <td align="left" class="style1">
                              <input type="hidden" name="MC_ENDDATE" id="MC_ENDDATE" value="<?php echo $MC_ENDDATE; ?>" class="form-control" style="max-width:350px" >
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>&nbsp;</div>
                                    <input type="text" name="MC_ENDDATE" class="form-control pull-left" id="datepicker2" value="<?php echo $MC_ENDDATE; ?>" style="width:150px">
                                </div>
                            </td>
                          <!-- MC_CODE, PATT_NUMBER, MC_MANNO -->
                      </tr>
                      <tr>
                          <td align="left" class="style1">&nbsp;&nbsp;<?php echo $MCStep ?></td>
                          <td align="left" class="style1">:</td>
                          <td align="left" class="style1">
                            <select name="MC_STEP1" id="MC_STEP1" class="form-control" style="max-width:70px" onChange="changeStep(this.value)">
                                <?php
                                    for($STEP=0;$STEP<=50;$STEP++)
                                    {
                                    ?>
                                        <option value="<?php echo $STEP; ?>" <?php if($STEP == $MAXSTEP) { ?> selected <?php } ?>><?php echo $STEP; ?></option>
                                        <?php
                                    }
                                ?>
                            </select>
                            <input type="hidden" name="MC_STEP" id="MC_STEP" value="<?php echo $MAXSTEP; ?>">                  </td>
                          <td align="left" class="style1" nowrap>&nbsp;</td>
                          <td align="left" class="style1">&nbsp;</td>
                          <!-- MC_STEP, MC_ENDDATE -->
                      </tr>
                      <script>
                            function changeStep(thisVal)
                            {
                                document.getElementById('MC_STEP').value = thisVal;
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
                                //MC_DATE				= document.getElementById('MC_DATE').value;
                                //swal(MC_DATE)
                            }
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
                              <script type="text/javascript">SunFishERP_DateTimePicker('MC_CHECKD','<?php echo $MC_CHECKD;?>','onMouseOver="mybirdthdates();"');</script>
                              <select name="MC_OWNER" id="MC_OWNER" class="form-control" style="max-width:350px">
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
                                        <option value="<?php echo $own_Code; ?>" <?php if($own_Code == $MC_OWNER) { ?> selected <?php } ?>><?php echo "$ownCompN"; ?></option>
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
                              <input type="hidden" size="10" class="textbox" style="text-align:right;" name="MC_RETVAL" id="MC_RETVAL" value="<?php echo $MC_RETVAL; ?>">
                              <input type="text" class="form-control" style="max-width:200px; text-align:right;" name="proj_amountIDR1" id="proj_amountIDR1" value="<?php print number_format($proj_amountIDR, $decFormat); ?>" disabled>
                              <input type="hidden" class="form-control" style="max-width:200px; text-align:right;" name="proj_amountIDR" id="proj_amountIDR" value="<?php echo $proj_amountIDR; ?>">                  </td> 
                          <td align="left" class="style1" nowrap><?php echo $DPPercentation ?>  (%)</td>
                          <td align="left" class="style1">
                              <input type="text" class="form-control" style="max-width:90px; text-align:right;" name="MC_DPPER1" id="MC_DPPER1" value="<?php print number_format($MC_DPPER, 4); ?>" onBlur="getDPValue(this.value)" disabled>
                              <input type="hidden" size="2" class="textbox" style="text-align:right;" name="MC_DPPER" id="MC_DPPER" value="<?php echo $MC_DPPER; ?>">                  </td>
                          <!-- MC_RETVAL, MC_DPPER, MC_DPPER1 -->
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
                              <input type="text" size="17"  class="form-control" style="max-width:200px; text-align:right;" name="MC_DPVAL1" id="MC_DPVAL1" value="<?php print number_format($MC_DPVAL, $decFormat); ?>" disabled>
                              <input type="hidden" size="17"  class="form-control" style="max-width:150px; text-align:right;" name="MC_DPVAL" id="MC_DPVAL" value="<?php echo $MC_DPVAL; ?>">                  </td>
                          <!-- MC_DPVAL, MC_DPVAL1 -->
                      </tr>
                      <script>
                            function getDPValue(thisVal)
                            {
                                var decFormat		= document.getElementById('decFormat').value;
                                document.getElementById('MC_DPPER1').value 		= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(thisVal)),decFormat));
                                document.getElementById('MC_DPPER').value 		= thisVal;
                                proj_amountIDR		= document.getElementById('proj_amountTotIDR').value;
                                MC_DPVALx			= parseFloat(thisVal) * parseFloat(proj_amountIDR) / 100;
                                document.getElementById('MC_DPVAL').value 		= MC_DPVALx;
                                document.getElementById('MC_DPVAL1').value 		= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_DPVALx)),decFormat));
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
                              <input type="text" class="form-control" style="max-width:200px; text-align:right;" name="MC_DPVAL1" id="MC_DPVAL1" value="<?php print number_format($MC_DPVALnPPn, $decFormat); ?>" disabled></td>
                      </tr>
                      <tr>
                          <td align="left" class="style1">&nbsp;&nbsp;<?php echo $Progress ?>  (%)</td>
                          <td align="left" class="style1">:</td>
                          <td align="left" class="style1">
                              <input type="text" class="form-control" style="max-width:90px; text-align:right;" name="MC_PROG1" id="MC_PROG1" value="<?php print number_format($MC_PROG, 4); ?>" onBlur="getPROGValueX(this); getPROGValue1(1)" onKeyPress="return isIntOnlyNew(event);" <?php if($ISCREATE == 0 || $GETFROM == 2) { ?> disabled <?php } ?>>
                              <input type="hidden" class="form-control" style="max-width:90px; text-align:right;" name="MC_PROG" id="MC_PROG" value="<?php print $MC_PROG; ?>" >
                              <input type="hidden" name="GETFROM" id="GETFROM" value="<?php echo $GETFROM; ?>">                  </td>
                          <td align="left" class="style1" ><?php echo $AdvPaymentInstallment ?></td>
                          <td align="left" class="style1" ><input type="text" class="form-control" style="max-width:200px; text-align:right;" name="MC_DPBACK1" id="MC_DPBACK1" value="<?php print number_format($MC_DPBACK, $decFormat); ?>" onBlur="getTOTDUE(this)" title="Progress Approved * 0.01">
                          <input type="hidden" class="form-control" style="max-width:200px; text-align:right;" name="MC_DPBACK" id="MC_DPBACK" value="<?php echo $MC_DPBACK; ?>"></td>
                          <!-- MC_PROG, MC_PROG1 -->
                      </tr>
                        <script>			
                        function getPROGValueX(thisVal)
                        {
                            var decFormat		= document.getElementById('decFormat').value;
                            var thisValA		= thisVal;
                            var proj_amountIDR	= document.getElementById('proj_amountTotIDRNon').value;
                            var thisVal			= eval(thisValA).value.split(",").join("");
                            MC_PROGAPPVAL		= parseFloat(thisVal) * parseFloat(proj_amountIDR) / 100;
                            
                            document.getElementById('MC_PROG1').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(thisVal), 4));
                            document.getElementById('MC_PROG').value 	= thisVal;
                            document.getElementById('MC_PROGVAL1').value = doDecimalFormatxx(RoundNDecimal(parseFloat(MC_PROGAPPVAL), 2));
                            document.getElementById('MC_PROGVAL').value = RoundNDecimal(parseFloat(MC_PROGAPPVAL), 2);
                            
                            document.getElementById('GETFROM').value	= 2;
                            
                            document.getElementById('MC_PROGAPPx').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(thisVal), 4));
                            document.getElementById('MC_PROGAPP').value 	= thisVal;
                            document.getElementById('MC_PROGAPPVALx').value	= doDecimalFormatxx(RoundNDecimal(parseFloat(MC_PROGAPPVAL), 2));
                            document.getElementById('MC_PROGAPPVAL').value 	= RoundNDecimal(parseFloat(MC_PROGAPPVAL), 2);
                            
                            getPROGValue(MC_PROGAPPVAL)
                        }
                        </script>
                      <tr>
                          <td align="left" class="style1">&nbsp;&nbsp;<?php echo $ProgressAmmount; ?> (Rp)</td>
                          <td align="left" class="style1">:</td>
                          <td align="left" class="style1">
                              <input type="text" class="form-control" style="max-width:200px; text-align:right;" name="MC_PROGVAL1" id="MC_PROGVAL1" value="<?php print number_format($MC_PROGVAL, $decFormat); ?>" onBlur="getPROGPER(this)" <?php if($GETFROM == 1) { ?> disabled <?php } ?>>
                              <input type="hidden" class="form-control" style="max-width:200px; text-align:right;" name="MC_PROGVAL" id="MC_PROGVAL" value="<?php print $MC_PROGVAL; ?>" >
                          </td>
                          <td align="left" class="style1" >&nbsp;</td>
                          <td align="left" class="style1" >&nbsp;</td>
                          <!-- MC_PROG_AMMOUNT -->
                      </tr>
                      <script>				
                        function getPROGPER(thisVal)	// OK
                        {
                            var ISAPPROVE		= document.getElementById('ISAPPROVE').value;
                            var thisValA		= thisVal;
                            var decFormat		= document.getElementById('decFormat').value;
                            var proj_amountIDR	= document.getElementById('proj_amountTotIDRNon').value;
                            var thisVal			= eval(thisValA).value.split(",").join("");
                            MC_PROG1			= parseFloat(thisVal) / parseFloat(proj_amountIDR) * 100;
                            
                            document.getElementById('MC_PROG1').value 		= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_PROG1)), 4));
                            document.getElementById('MC_PROG').value 		= RoundNDecimal(parseFloat(MC_PROG1), 4);
                            document.getElementById('MC_PROGVAL1').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(thisVal)), 2));
                            document.getElementById('MC_PROGVAL').value 	= RoundNDecimal(parseFloat(thisVal), 2);
                            
                            document.getElementById('GETFROM').value		= 2;
                            
                            document.getElementById('MC_PROGAPPx').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_PROG1)), 4));
                            document.getElementById('MC_PROGAPP').value 	= RoundNDecimal(parseFloat(MC_PROG1), 4);
                            document.getElementById('MC_PROGAPPVALx').value = doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(thisVal)), 2));
                            document.getElementById('MC_PROGAPPVAL').value 	= RoundNDecimal(parseFloat(thisVal), 2);
                            
                            getPROGValue(thisVal);
                        }
                      </script>
                    <tr>
                        <td align="left" class="style1">&nbsp;&nbsp;<?php echo $ProgressApproved ?>  (%)</td>
                        <td align="left" class="style1">:</td>
                        <td align="left" class="style1">
                            <?php /*?><label><input type="text" class="form-control" style="max-width:100px; text-align:right;" name="MC_PROGAPPx" id="MC_PROGAPPx" value="<?php print number_format($MC_PROGAPP, 4); ?>" onBlur="getPROGAPPValue(this.value)" onKeyPress="return isIntOnlyNew(event);" <?php if($GETFROM == 2) { ?> disabled <?php } ?>><?php */?>
                            <label><input type="text" class="form-control" style="max-width:100px; text-align:right;" name="MC_PROGAPPx" id="MC_PROGAPPx" value="<?php print number_format($MC_PROGAPP, 4); ?>" onBlur="getPROGAPPValue(this.value)" onKeyPress="return isIntOnlyNew(event);" disabled>
                            <input type="hidden" class="form-control" style="max-width:90px; text-align:right;" name="MC_PROGAPP" id="MC_PROGAPP" value="<?php print $MC_PROGAPP; ?>" ></label>
                            <label>
                            <?php /*?><input type="text" class="form-control" style="max-width:185px; text-align:right;" name="MC_PROGAPPVALx" id="MC_PROGAPPVALx" value="<?php print number_format($MC_PROGAPPVAL, $decFormat); ?>" onBlur="getPROGPERAPP(this.value)" <?php if($GETFROM == 1) { ?> disabled <?php } ?> title="Progress Approved Amount"><?php */?>
                            <input type="text" class="form-control" style="max-width:185px; text-align:right;" name="MC_PROGAPPVALx" id="MC_PROGAPPVALx" value="<?php print number_format($MC_PROGAPPVAL, $decFormat); ?>" onBlur="getPROGPERAPP(this.value)" title="Progress Approved Amount" disabled>
                            <input type="hidden" class="form-control" style="max-width:200px; text-align:right;" name="MC_PROGAPPVAL" id="MC_PROGAPPVAL" value="<?php print $MC_PROGAPPVAL; ?>" title="Approve Amount" >
                            </label>
                        </td>
                        <td align="left" class="style1"><?php echo $PaymentBefore ?></td>
                        <td align="left" class="style1">
                        	<input type="text" class="form-control" style="max-width:200px; text-align:right;" name="MC_VALBEFx" id="MC_VALBEFx" value="<?php echo number_format($MC_VALBEF, $decFormat); ?>" title="Amount Before" disabled>
                        	<input type="hidden" class="form-control" style="max-width:200px; text-align:right;" name="MC_VALBEF" id="MC_VALBEF" value="<?php echo $MC_VALBEF; ?>" title="Amount Before">
                        </td>
                        <!-- MC_PROG, MC_PROG1 -->
                    </tr>
                    <script>
                        function getPROGAPPValue(MC_PROGAPPx)
                        {
                            var decFormat		= document.getElementById('decFormat').value;
                            var proj_amountIDR	= document.getElementById('proj_amountTotIDRNon').value;
                            var MC_PROGAPP		= MC_PROGAPPx.split(",").join("");
                            MC_PROGAPPVAL		= parseFloat(MC_PROGAPP) * parseFloat(proj_amountIDR) / 100;
                            
                            //document.getElementById('MC_PROG1').value = doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(thisVal)), 4));
                            //document.getElementById('MC_PROG').value 	= thisVal;
                            document.getElementById('MC_PROGAPPx').value= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_PROGAPP)), 4));
                            document.getElementById('MC_PROGAPP').value = MC_PROGAPP;
                            
                            getPROGValue(MC_PROGAPPVAL)
                        }
                    </script>
                    <tr>
                        <td align="left" class="style1" nowrap>&nbsp;&nbsp;<?php echo $MCAmount; ?> - PPn</td>
                        <td align="left" class="style1">:</td>
                        <td align="left" class="style1">                          
                          	<label>
                            <input type="hidden" class="form-control" style="max-width:200px; text-align:right;" name="MC_PAYAKUM" id="MC_PAYAKUM" value="<?php echo $MC_PAYAKUM; ?>" title="Pay Acummulation">
                            <input type="text" class="form-control" style="max-width:150px; text-align:right;" name="MC_PAYMENTx" id="MC_PAYMENTx" value="<?php echo number_format($MC_PAYMENT, $decFormat); ?>" title="MCPay. = Next Amount - Amount Before" disabled>
                            <input type="hidden" class="form-control" style="max-width:200px; text-align:right;" name="MC_PAYMENT" id="MC_PAYMENT" value="<?php echo $MC_PAYMENT; ?>" title="MCPay. = Next Amount - Amount Before">
                            </label>
                          <label>
                            <input type="hidden" class="form-control" style="max-width:155px; text-align:right;" name="MC_TOTVAL_PPn" id="MC_TOTVAL_PPn" value="<?php echo $MC_TOTVAL_PPn; ?>">
                          </label>
                        </td> 
                        <td align="left" class="style1"><?php //echo $PaymentNext ?></td>
                        <td align="left" class="style1"><input type="hidden" class="form-control" style="max-width:200px; text-align:right;" name="MC_AKUMNEXT" id="MC_AKUMNEXT" value="<?php echo $MC_AKUMNEXT; ?>" title="Next Amount">
                        </tr>
                      <tr>
                          <td align="left" class="style1">&nbsp;&nbsp;<?php echo $Retention; ?> (Rp)</td>
                          <td align="left" class="style1">:</td>
                          <td align="left" class="style1">
                            <input type="hidden" class="form-control" style="max-width:200px; text-align:right;" name="MC_VALADD" id="MC_VALADD" value="<?php echo $MC_VALADD; ?>" title="SI">
                            <input type="hidden" class="form-control" style="max-width:200px; text-align:right;" name="MC_MATVAL" id="MC_MATVAL" value="<?php echo $MC_MATVAL; ?>" title="Material Amount">
                            <input type="hidden" class="form-control" style="max-width:200px; text-align:right;" name="MC_TOTPROGRESS" id="MC_TOTPROGRESS" value="<?php echo $MC_TOTPROGRESS; ?>" title="Total Progress">
                            <label>
                            <input type="text" class="form-control" style="max-width:150px; text-align:right;" name="MC_RETCUTx" id="MC_RETCUTx" value="<?php echo number_format($MC_RETCUT, $decFormat); ?>" onBlur="getTOTAL(this)" title="RetCut = 0.05*Progress Approved" disabled>
                            <input type="hidden" class="form-control" style="max-width:130px; text-align:right;" name="MC_RETCUT" id="MC_RETCUT" value="<?php echo $MC_RETCUT; ?>" title="RetCut = 0.05*Progress Approved"></label>
                            <?php /*?><input type="hidden" class="form-control" style="max-width:200px; text-align:right;" name="MC_DPBACK" id="MC_DPBACK" value="<?php echo $MC_DPBACK; ?>">
                           // MC_PAYBEFRET		= MC_TOTPROGRESS +  MC_DPVAL -  MC_DPBACK - MC_RETCUT;<?php */?>
                           <label>
                            <input type="text" class="form-control" style="max-width:140px; text-align:right;" name="MC_PAYBEFRETx" id="MC_PAYBEFRETx" value="<?php echo number_format($MC_PAYBEFRET, $decFormat); ?>" title="MC Net = Tot.Progres (Inc.PPn) - Pemb. Sebelumnya - Pengemb. DP - Retensi" disabled>
                            <input type="hidden" class="form-control" style="max-width:155px; text-align:right;" name="MC_PAYBEFRET" id="MC_PAYBEFRET" value="<?php echo $MC_PAYBEFRET; ?>" title="Payment Before Retention">
                          </label>
                            </td> 
                          <?php /*?><td align="left" class="style1"><?php echo $Amount; ?>&nbsp;(incl. PPn) </td><?php */?>
                          <td align="left" class="style1"><?php echo $PaymentNext; ?> </td>
                          <td align="left" class="style1"  valign="top">
                          	<input type="text" class="form-control" style="max-width:200px; text-align:right;" name="MC_AKUMNEXTx" id="MC_AKUMNEXTx" value="<?php echo number_format($MC_AKUMNEXT, $decFormat); ?>" title="Next Amount" disabled>
                          	<input type="hidden" class="form-control" style="max-width:200px; text-align:right;" name="MC_PAYDUE" id="MC_PAYDUE" value="<?php print number_format($MC_PAYDUE, $decFormat); ?>" disabled></td>
                      </tr>
                    <script>
						function getTOTPlusPPN(PPNValue)
						{
                            var decFormat		= document.getElementById('decFormat').value;
							var MC_PAYMENT		= document.getElementById('MC_PAYMENT').value;
                            var MC_TOTVAL_PPn	= PPNValue.split(",").join("");
							
							document.getElementById('MC_TOTVAL_PPn').value	= MC_TOTVAL_PPn;
							document.getElementById('MC_TOTVAL_PPnx').value	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_TOTVAL_PPn)),decFormat));
							
							MC_PAYDUE		= parseFloat(MC_PAYMENT) + parseFloat(MC_TOTVAL_PPn);
							
                            document.getElementById('MC_PAYDUE').value = doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_PAYDUE)),decFormat));
                            MC_PAYDUEPPh	= Math.round(0.03 * MC_PAYMENT);	
							
							document.getElementById('MC_TOTVAL_PPh').value	= MC_PAYDUEPPh;
							document.getElementById('MC_TOTVAL_PPhx').value	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_PAYDUEPPh)),decFormat));
							
                            TOTPAYMENT		= parseFloat(MC_PAYDUE - MC_PAYDUEPPh);
                            document.getElementById('TOTPAYMENT').value = doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(TOTPAYMENT)),decFormat));
						}
						
                        function getPROGPERAPP(MC_PROGAPPVALx)
                        {
                            var ISAPPROVE		= document.getElementById('ISAPPROVE').value;
                            var decFormat		= document.getElementById('decFormat').value;
                            var proj_amountIDR	= document.getElementById('proj_amountTotIDRNon').value;
                            var MC_PROGAPPVAL	= MC_PROGAPPVALx.split(",").join("");
                            MC_PROGAPP			= parseFloat(MC_PROGAPPVAL) / parseFloat(proj_amountIDR) * 100;
                            
                            document.getElementById('GETFROM').value		= 2;
                            document.getElementById('MC_PROGAPPx').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_PROGAPP)), 4));
                            document.getElementById('MC_PROGAPP').value 	= MC_PROGAPP;
                            
                            getPROGValue(MC_PROGAPPVAL)
                        }
                            
                        function getTOTAL(thisVal)
                        {
                            var decFormat		= document.getElementById('decFormat').value;
                            var proj_amountIDR	= document.getElementById('proj_amountTotIDRNon').value;
							
							var MC_RETCUT		= eval(thisVal).value.split(",").join("");
							
                            document.getElementById('MC_RETCUT').value 		= MC_RETCUT;	// PEMOTONGAN (RETENSI)
                            document.getElementById('MC_RETCUTx').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_RETCUT)),decFormat));
							var MC_PROGAPPx		= document.getElementById('MC_PROGAPPx').value; // GET NILAI ASLI SAAT PROGRESS APP DI ISI
                            document.getElementById('MC_PROGAPPx').value = doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_PROGAPPx)), 4));
                            document.getElementById('MC_PROGAPP').value = MC_PROGAPPx;
                            							
                            var decFormat		= document.getElementById('decFormat').value;
                            var proj_amountIDR	= document.getElementById('proj_amountTotIDRNon').value;
                            MC_VALADD			= document.getElementById('MC_VALADD').value;
                            MC_MATVAL			= document.getElementById('MC_MATVAL').value;
							MC_PROGAPPVAL		= document.getElementById('MC_PROGAPPVAL').value;
							
							MC_TOTVAL_PPn		= parseFloat(0.1 * MC_PROGAPPVAL);
													
                            document.getElementById('MC_TOTVAL_PPn').value	= MC_TOTVAL_PPn;
							document.getElementById('MC_TOTVAL_PPnx').value	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_TOTVAL_PPn)),decFormat));
                            
                            MC_PAYMENT			= parseFloat(MC_PROGAPPVAL) + parseFloat(MC_TOTVAL_PPn);	// TOTAL KOTOR MC
							
                            document.getElementById('MC_PAYMENT').value 	= MC_PAYMENT;
                            document.getElementById('MC_PAYMENTx').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_PAYMENT)),decFormat));
													
                            MC_PROGVALAPPx		= parseFloat(MC_PROGAPPVAL);
                            MC_PROGVALXY		= parseFloat(MC_PROGVALAPPx + (0.1 * MC_PROGVALAPPx));
							
                            document.getElementById('MC_PROGAPPVAL').value 	= MC_PROGVALAPPx;
                            document.getElementById('MC_PROGAPPVALx').value = doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_PROGVALAPPx)),decFormat));					
                            document.getElementById('MC_PROGVALXY').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_PROGVALXY)),decFormat));
                            MC_DPVAL			= document.getElementById('MC_DPVAL').value;
                            MC_TOTPROGRESS		= parseFloat(MC_PAYMENT) +  parseFloat(MC_VALADD) +  parseFloat(MC_MATVAL);
							
                            document.getElementById('MC_TOTPROGRESS').value = MC_TOTPROGRESS;	// KEMAJUAN PEKERJAAN
							
                            MC_DPPER			= document.getElementById('MC_DPPER').value;
                            MC_DPBACK			= parseFloat(MC_DPPER * MC_TOTPROGRESS / 100);
							
                            document.getElementById('MC_DPBACK').value 		= MC_DPBACK;	// ANG. PENGEMBALIAN UANG MUKA (PROPORSIONAL)
                            document.getElementById('MC_DPBACK1').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(MC_DPBACK),decFormat));
							
                            MC_VALBEF			= document.getElementById('MC_VALBEF').value;
                            
                            MC_PAYBEFRET		= parseFloat(MC_TOTPROGRESS) - parseFloat(MC_VALBEF) -  parseFloat(MC_DPBACK) - parseFloat(MC_RETCUT);
							
                            document.getElementById('MC_PAYBEFRET').value 	= MC_PAYBEFRET;	// JUMLAH PEMBAYARAN SEBELUM RETENSI
                            document.getElementById('MC_PAYBEFRETx').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_PAYBEFRET)),decFormat));
                            
                            MC_PAYAKUM			= parseFloat(MC_PAYBEFRET);
                            document.getElementById('MC_PAYAKUM').value 	= MC_PAYAKUM;	// JUMLAH AKUMULASI PEMBAYARAN SAMPAI SAAT INI
                            document.getElementById('MC_AKUMNEXT').value 	= MC_PAYAKUM;	// DIGUNAKAN UNTUK PEMBUATAN MC SELANJUTNYA
                            document.getElementById('MC_AKUMNEXTx').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.round(MC_PAYAKUM)),decFormat));						
                            
                            MC_PAYDUE			= parseFloat(MC_PROGVALXY);
                            document.getElementById('MC_PAYDUE').value = doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_PAYDUE)),decFormat));
                            //MC_PAYDUEPPh		= parseFloat(0.03 * MC_PAYMENT);
                            MC_PAYDUEPPh		= 0;													
                            document.getElementById('MC_TOTVAL_PPh').value	= MC_PAYDUEPPh;
							document.getElementById('MC_TOTVAL_PPhx').value	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_PAYDUEPPh)),decFormat));
							
                            //TOTPAYMENT		= parseFloat(MC_PAYDUE - MC_PAYDUEPPh);
                            TOTPAYMENT			= parseFloat(MC_TOTPROGRESS) - parseFloat(MC_VALBEF) -  parseFloat(MC_DPBACK) - parseFloat(MC_RETCUT);
                            document.getElementById('TOTPAYMENT').value = doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(TOTPAYMENT)),decFormat));
                        }
                            
                        function getPROGValue(MC_PROGAPPVAL)
                        {
                            var MC_PROGAPPx		= document.getElementById('MC_PROGAPPx').value; // GET NILAI ASLI SAAT PROGRESS APP DI ISI
                            document.getElementById('MC_PROGAPPx').value = doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_PROGAPPx)), 4));
                            document.getElementById('MC_PROGAPP').value = MC_PROGAPPx;
                            							
                            var decFormat		= document.getElementById('decFormat').value;
                            var proj_amountIDR	= document.getElementById('proj_amountTotIDRNon').value;
                            MC_VALADD			= document.getElementById('MC_VALADD').value;
                            MC_MATVAL			= document.getElementById('MC_MATVAL').value;
							
							MC_TOTVAL_PPn		= parseFloat(0.1 * MC_PROGAPPVAL);
													
                            document.getElementById('MC_TOTVAL_PPn').value	= MC_TOTVAL_PPn;
							document.getElementById('MC_TOTVAL_PPnx').value	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_TOTVAL_PPn)),decFormat));
                            
                            MC_PAYMENT			= parseFloat(MC_PROGAPPVAL) + parseFloat(MC_TOTVAL_PPn);	// TOTAL KOTOR MC
							
                            document.getElementById('MC_PAYMENT').value 	= MC_PAYMENT;
                            document.getElementById('MC_PAYMENTx').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_PAYMENT)),decFormat));
													
                            MC_PROGVALAPPx		= parseFloat(MC_PROGAPPVAL);
                            MC_PROGVALXY		= parseFloat(MC_PROGVALAPPx + (0.1 * MC_PROGVALAPPx));
							
                            document.getElementById('MC_PROGAPPVAL').value 	= MC_PROGVALAPPx;
                            document.getElementById('MC_PROGAPPVALx').value = doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_PROGVALAPPx)),decFormat));					
                            document.getElementById('MC_PROGVALXY').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_PROGVALXY)),decFormat));
                            MC_DPVAL			= document.getElementById('MC_DPVAL').value;
                            MC_TOTPROGRESS		= parseFloat(MC_PAYMENT) +  parseFloat(MC_VALADD) +  parseFloat(MC_MATVAL);
							
                            document.getElementById('MC_TOTPROGRESS').value = MC_TOTPROGRESS;	// KEMAJUAN PEKERJAAN
                            
                            //MC_RETCUT			= Math.round((5 / 100 * MC_PROGVALx));
                            MC_RETCUT			= parseFloat((5 / 100 * MC_TOTPROGRESS));
                            document.getElementById('MC_RETCUT').value 		= MC_RETCUT;	// PEMOTONGAN (RETENSI)
                            document.getElementById('MC_RETCUTx').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_RETCUT)),decFormat));
							
                            MC_DPPER			= document.getElementById('MC_DPPER').value;
                            MC_DPBACK			= parseFloat(MC_DPPER * MC_TOTPROGRESS / 100);
							
                            document.getElementById('MC_DPBACK').value 		= MC_DPBACK;	// ANG. PENGEMBALIAN UANG MUKA (PROPORSIONAL)
                            document.getElementById('MC_DPBACK1').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(MC_DPBACK),decFormat));
							
                            MC_VALBEF			= document.getElementById('MC_VALBEF').value;
                            
                            MC_PAYBEFRET		= parseFloat(MC_TOTPROGRESS) - parseFloat(MC_VALBEF) -  parseFloat(MC_DPBACK) - parseFloat(MC_RETCUT);
							
                            document.getElementById('MC_PAYBEFRET').value 	= MC_PAYBEFRET;	// JUMLAH PEMBAYARAN SEBELUM RETENSI
                            document.getElementById('MC_PAYBEFRETx').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_PAYBEFRET)),decFormat));
                            
                            MC_PAYAKUM			= parseFloat(MC_PAYBEFRET);
                            document.getElementById('MC_PAYAKUM').value 	= MC_PAYAKUM;	// JUMLAH AKUMULASI PEMBAYARAN SAMPAI SAAT INI
                            document.getElementById('MC_AKUMNEXT').value 	= MC_PAYAKUM;	// DIGUNAKAN UNTUK PEMBUATAN MC SELANJUTNYA
                            document.getElementById('MC_AKUMNEXTx').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.round(MC_PAYAKUM)),decFormat));						
                            
                            MC_PAYDUE			= parseFloat(MC_PROGVALXY);
                            document.getElementById('MC_PAYDUE').value = doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_PAYDUE)),decFormat));
                            //MC_PAYDUEPPh		= parseFloat(0.03 * MC_PAYMENT);
                            MC_PAYDUEPPh		= 0;													
                            document.getElementById('MC_TOTVAL_PPh').value	= MC_PAYDUEPPh;
							document.getElementById('MC_TOTVAL_PPhx').value	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_PAYDUEPPh)),decFormat));
							
                            //TOTPAYMENT		= parseFloat(MC_PAYDUE - MC_PAYDUEPPh);
                            TOTPAYMENT			= parseFloat(MC_TOTPROGRESS) - parseFloat(MC_VALBEF) -  parseFloat(MC_DPBACK) - parseFloat(MC_RETCUT);
                            document.getElementById('TOTPAYMENT').value = doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(TOTPAYMENT)),decFormat));
                        }
                        
                        function getPROGValue1(thisVal)
                        {
                            document.getElementById('GETFROM').value		= 1;
                        }
                    </script>
                      <tr>
                          <td align="left" class="style1" style="font-weight:bold; color:#00F">&nbsp;</td>
                          <td align="left" class="style1">&nbsp;</td>
                          <td align="left" class="style1" style="font-weight:bold; color:#FFF"><hr></td> 
                          <td align="left" class="style1">PPh</td>
                          <td align="left" class="style1">
                              <input type="text" class="form-control" style="max-width:200px; text-align:right;" name="MC_TOTVAL_PPhx" id="MC_TOTVAL_PPhx" value="<?php echo number_format($MC_TOTVAL_PPh, $decFormat); ?>" onBlur="getTOTPlusPPH(this.value)" onKeyPress="return isIntOnlyNew(event);">
                              <input type="hidden" class="form-control" style="max-width:155px; text-align:right;" name="MC_TOTVAL_PPh" id="MC_TOTVAL_PPh" value="<?php echo $MC_TOTVAL_PPh; ?>" title="Payment Before Retention">
                          </td>
                          <!-- MC_PROG, MC_PROG1 -->
                      </tr>
                      <tr>
                          <td align="left" class="style1" style="font-weight:bold; color:#00F">&nbsp;&nbsp;<?php echo $ReceiptAmount; ?> (Aft. PPh)</td>
                          <td align="left" class="style1">:</td>
                          <td align="left" class="style1" style="font-weight:bold; color:#FFF"><input type="hidden" class="form-control" style="max-width:200px; text-align:right;" name="MC_PROGVALXY" id="MC_PROGVALXY" value="<?php print number_format($MC_PROGVALXY, $decFormat); ?>" disabled>
                          <input type="text" class="form-control" style="max-width:200px; text-align:right; background-color:#0C6;" name="TOTPAYMENT" id="TOTPAYMENT" value="<?php print number_format($TOTPAYMENT, $decFormat); ?>" disabled></td> 
                          <td align="left" class="style1">&nbsp;</td>
                          <td align="left" class="style1">&nbsp;</td>
                          <!-- MC_PROG, MC_PROG1 -->
                      </tr>
                      <script>
							function getTOTPlusPPH(PPHValue)
							{
								var decFormat		= document.getElementById('decFormat').value;
								var MC_PAYDUE		= document.getElementById('MC_AKUMNEXTx').value.split(",").join("");
								var MC_TOTVAL_PPh	= PPHValue.split(",").join("");
								MC_PAYDUEPPh		= parseFloat(MC_TOTVAL_PPh);	
								
								document.getElementById('MC_TOTVAL_PPh').value	= MC_PAYDUEPPh;
								document.getElementById('MC_TOTVAL_PPhx').value	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_PAYDUEPPh)),decFormat));
								
								TOTPAYMENT		= parseFloat(MC_PAYDUE) - parseFloat(MC_PAYDUEPPh);
								document.getElementById('TOTPAYMENT').value = doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(TOTPAYMENT)),decFormat));
							}
							
                            function getTOTDUE(thisVal)
                            {
                                var decFormat	= document.getElementById('decFormat').value;
                                var thisVal		= eval(thisVal).value.split(",").join("");
                                MC_DPBACK		= thisVal;
                                document.getElementById('MC_DPBACK').value 	= MC_DPBACK;
                                document.getElementById('MC_DPBACK1').value = doDecimalFormatxx(RoundNDecimal(parseFloat(Math.round(MC_DPBACK)),decFormat));
								MC_PROGAPPVAL		= document.getElementById('MC_PROGAPPVAL').value;
								
                                var MC_PROGAPPx		= document.getElementById('MC_PROGAPPx').value; // GET NILAI ASLI SAAT PROGRESS APP DI ISI
								document.getElementById('MC_PROGAPPx').value = doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_PROGAPPx)), 4));
								document.getElementById('MC_PROGAPP').value = MC_PROGAPPx;
															
								var decFormat		= document.getElementById('decFormat').value;
								var proj_amountIDR	= document.getElementById('proj_amountTotIDRNon').value;
								MC_VALADD			= document.getElementById('MC_VALADD').value;
								MC_MATVAL			= document.getElementById('MC_MATVAL').value;
								
								MC_TOTVAL_PPn		= parseFloat(0.1 * MC_PROGAPPVAL);
														
								document.getElementById('MC_TOTVAL_PPn').value	= MC_TOTVAL_PPn;
								document.getElementById('MC_TOTVAL_PPnx').value	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_TOTVAL_PPn)),decFormat));
								
								MC_PAYMENT			= parseFloat(MC_PROGAPPVAL) + parseFloat(MC_TOTVAL_PPn);	// TOTAL KOTOR MC
								
								document.getElementById('MC_PAYMENT').value 	= MC_PAYMENT;
								document.getElementById('MC_PAYMENTx').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_PAYMENT)),decFormat));
														
								MC_PROGVALAPPx		= parseFloat(MC_PROGAPPVAL);
								MC_PROGVALXY		= parseFloat(MC_PROGVALAPPx + (0.1 * MC_PROGVALAPPx));
								
								document.getElementById('MC_PROGAPPVAL').value 	= MC_PROGVALAPPx;
								document.getElementById('MC_PROGAPPVALx').value = doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_PROGVALAPPx)),decFormat));					
								document.getElementById('MC_PROGVALXY').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_PROGVALXY)),decFormat));
								MC_DPVAL			= document.getElementById('MC_DPVAL').value;
								MC_TOTPROGRESS		= parseFloat(MC_PAYMENT) +  parseFloat(MC_VALADD) +  parseFloat(MC_MATVAL);
								
								document.getElementById('MC_TOTPROGRESS').value = MC_TOTPROGRESS;	// KEMAJUAN PEKERJAAN
								
								//MC_RETCUT			= Math.round((5 / 100 * MC_PROGVALx));
								MC_RETCUT			= parseFloat((5 / 100 * MC_TOTPROGRESS));
								document.getElementById('MC_RETCUT').value 		= MC_RETCUT;	// PEMOTONGAN (RETENSI)
								document.getElementById('MC_RETCUTx').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_RETCUT)),decFormat));
								
								MC_DPPER			= document.getElementById('MC_DPPER').value;
								
								document.getElementById('MC_DPBACK').value 		= MC_DPBACK;	// ANG. PENGEMBALIAN UANG MUKA (PROPORSIONAL)
								document.getElementById('MC_DPBACK1').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(MC_DPBACK),decFormat));
								
								MC_VALBEF			= document.getElementById('MC_VALBEF').value;
								
								MC_PAYBEFRET		= parseFloat(MC_TOTPROGRESS) - parseFloat(MC_VALBEF) -  parseFloat(MC_DPBACK) - parseFloat(MC_RETCUT);
								
								document.getElementById('MC_PAYBEFRET').value 	= MC_PAYBEFRET;	// JUMLAH PEMBAYARAN SEBELUM RETENSI
								document.getElementById('MC_PAYBEFRETx').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_PAYBEFRET)),decFormat));
								
								MC_PAYAKUM			= parseFloat(MC_PAYBEFRET);
								document.getElementById('MC_PAYAKUM').value 	= MC_PAYAKUM;	// JUMLAH AKUMULASI PEMBAYARAN SAMPAI SAAT INI
								document.getElementById('MC_AKUMNEXT').value 	= MC_PAYAKUM;	// DIGUNAKAN UNTUK PEMBUATAN MC SELANJUTNYA
								document.getElementById('MC_AKUMNEXTx').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.round(MC_PAYAKUM)),decFormat));						
								
								MC_PAYDUE			= parseFloat(MC_PROGVALXY);
								document.getElementById('MC_PAYDUE').value = doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_PAYDUE)),decFormat));
								//MC_PAYDUEPPh		= parseFloat(0.03 * MC_PAYMENT);
								MC_PAYDUEPPh		= 0;													
								document.getElementById('MC_TOTVAL_PPh').value	= MC_PAYDUEPPh;
								document.getElementById('MC_TOTVAL_PPhx').value	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_PAYDUEPPh)),decFormat));
								
								//TOTPAYMENT		= parseFloat(MC_PAYDUE - MC_PAYDUEPPh);
								TOTPAYMENT			= parseFloat(MC_TOTPROGRESS) - parseFloat(MC_VALBEF) -  parseFloat(MC_DPBACK) - parseFloat(MC_RETCUT);
								document.getElementById('TOTPAYMENT').value = doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(TOTPAYMENT)),decFormat));
                            }
                        </script>
                      <tr>
                          <td align="left" class="style1" valign="top">&nbsp;&nbsp;<?php echo $Notes ?> </td>
                          <td align="left" class="style1" valign="top">:</td>
                          <td align="left" class="style1">
                            <textarea name="MC_NOTES" class="form-control" id="MC_NOTES" cols="30" style="height:50px"><?php echo $MC_NOTES; ?></textarea></td> 
                          <td align="left" class="style1"  valign="top">&nbsp;</td>
                          <td align="left" class="style1"  valign="top">&nbsp;</td>
                      </tr>
                      <tr>
                          <td align="left" valign="middle" class="style1">&nbsp;&nbsp;<?php echo $Status; ?></td>
                          <td align="left" valign="middle" class="style1">:</td>
                          <td colspan="3" align="left" valign="middle" class="style1">
                            <input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $MC_STAT; ?>">
                            <?php
                                if($ISAPPROVE == 1)
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
												
												if($task == 'add')
												{
												?>
													<select name="MC_STAT" id="MC_STAT" class="form-control" style="max-width:100px" onChange="selStat(this.value)" <?php if($disButton == 1) { ?> disabled <?php } ?> >
														<option value="0"> -- </option>
                                                        <option value="1"<?php if($MC_STAT == 1) { ?> selected <?php } ?>>New</option>
                                                        <option value="2"<?php if($MC_STAT == 2) { ?> selected <?php } ?>>Confirm</option>
													</select>
												<?php
												}
												else
												{
												?>
													<select name="MC_STAT" id="MC_STAT" class="form-control" style="max-width:100px" onChange="selStat(this.value)" <?php if($disButton == 1) { ?> disabled <?php } ?> >
														<option value="0"> -- </option>
                                                        <option value="1"<?php if($MC_STAT == 1) { ?> selected <?php } ?>>New</option>
                                                        <option value="2"<?php if($MC_STAT == 2) { ?> selected <?php } ?>>Confirm</option>
														<option value="3"<?php if($MC_STAT == 3) { ?> selected <?php } ?>>Approved</option>
														<option value="4"<?php if($MC_STAT == 4) { ?> selected <?php } ?>>Revised</option>
														<option value="5"<?php if($MC_STAT == 5) { ?> selected <?php } ?>>Rejected</option>
														<option value="6"<?php if($MC_STAT == 6) { ?> selected <?php } ?>>Closed</option>
														<option value="7"<?php if($MC_STAT == 7) { ?> selected <?php } ?> style="display:none">Awaiting</option>
														<option value="9"<?php if($MC_STAT == 9) { ?> selected <?php } if($MC_STAT == 1) {?> disabled <?php } ?>>Void</option>
													</select>
												<?php
												}
											}
											else
											{
												?>
													<?php /*?><a href="" class="btn btn-<?php echo $statcoloer; ?> btn-xs" title="ssss">
														<?php echo $descApp; ?>
													</a><?php */?>
                                                    <select name="MC_STAT" id="MC_STAT" class="form-control" style="max-width:100px" onChange="selStat(this.value)" <?php if($disButton == 1) { ?> disabled <?php } ?> >
                                                        <option value="1"<?php if($MC_STAT == 1) { ?> selected <?php } ?> disabled>New</option>
                                                        <option value="2"<?php if($MC_STAT == 2) { ?> selected <?php } ?> disabled>Confirm</option>
														<option value="3"<?php if($MC_STAT == 3) { ?> selected <?php } ?> disabled>Approved</option>
														<option value="4"<?php if($MC_STAT == 4) { ?> selected <?php } ?> disabled>Revised</option>
														<option value="5"<?php if($MC_STAT == 5) { ?> selected <?php } ?> disabled>Rejected</option>
														<option value="6"<?php if($MC_STAT == 6) { ?> selected <?php } ?> disabled>Closed</option>
														<option value="9"<?php if($MC_STAT == 9) { ?> selected <?php } if($MC_STAT == 9) {?> disabled <?php } ?>>Void</option>
													</select>
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
                                    <select name="MC_STAT" id="MC_STAT" class="form-control" style="max-width:110px" onChange="selStat(this.value)">
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
                            ?>
                          </td>
                        </tr> 
                      	<tr>
                          	<td colspan="5" align="left" class="style1">&nbsp;</td>
                      	</tr> 
                        <script>
							function selStat(selSTAT)
							{
								if(selSTAT == 9)
								{
									document.getElementById('btnVoid').style.display = '';
								}
								else
								{
									document.getElementById('btnVoid').style.display = 'none';
								}
							}
						</script>
                      <tr>
                          <td align="left" class="style1">&nbsp;</td>
                          <td align="left" class="style1">&nbsp;</td>
                          <td colspan="3" align="left" class="style1">
                              <?php
							  	if($disableAll == 0)
								{
									if(($MC_STAT == 2 || $MC_STAT == 7) && $canApprove == 1)
									{
										?>
											<button class="btn btn-primary" >
											<i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Update; ?>
											</button>&nbsp;
										<?php
									}
									elseif($MC_STAT == 1 || $MC_STAT == 4)
									{
                                            ?>
                                                <button type="button" class="btn btn-primary" onClick="submitForm(1);">
                                                <i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Save; ?>
                                                </button>&nbsp;
                                            <?php
									}
									else
									{
                                            ?>
                                                <button type="button" class="btn btn-primary" onClick="submitForm(1);" style="display:none" id="btnVoid">
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
		var FlagUSER	= document.getElementById('FlagUSER').value;
		var ISAPPROVE	= document.getElementById('ISAPPROVE').value;
		var MC_STEP		= document.getElementById('MC_STEP').value;
		var MC_PROG		= document.getElementById('MC_PROG').value;
		var MC_STATX	= document.getElementById('MC_STATX').value;
		var MC_STAT		= document.getElementById('MC_STAT').value;
		
		//if(FlagUSER == 'APPSI')
		if(ISAPPROVE == 1)
		{
			var MC_MANNO	= document.getElementById('MC_MANNO').value;
			if(MC_MANNO == '')
			{
				swal('Please input MC Manual Number.');
				document.getElementById('MC_MANNO').focus();
				return false;
			}
		}
		
		if(MC_STEP == 0)
		{
			swal('Please select step of MC.');
			document.getElementById('MC_STEP').focus();
			return false;
		}
		
		if(MC_PROG == 0)
		{
			swal('Please input Progress Percentation.');
			document.getElementById('MC_PROG1').value = '';
			document.getElementById('MC_PROG1').focus();
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
			if(MC_STAT == 9)
			{
				var MC_NOTES		= document.getElementById('MC_NOTES').value;
				if(MC_NOTES == '')
				{
					swal('Masukan alasan mengapa Anda membatalkan dokumen ini.');
					document.getElementById('MC_NOTES').focus();
					return false;
				}
			}
			else
			{
				swal('The Document has been Approved/Closed. You can not update.');
				return false;
			}
		}
		
		document.frm.submit();
			
		//return false;
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
</script>
<?php 
$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>