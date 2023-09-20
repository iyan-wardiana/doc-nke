<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 12 Maret 2017
 * File Name	= project_invoice_form.php
 * Location		= -
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody 	= $this->session->userdata['appBody'];
$FlagUSER 	= $this->session->userdata['FlagUSER'];
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
$DEPCODE 	= $this->session->userdata['DEPCODE'];

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

$currentRow = 0;
if($task == 'add')
{
	$PINV_DateY 	= date('Y');
	$PINV_DateM 	= date('m');
	$PINV_DateD 	= date('d');
	$PINV_TTODate	= "$PINV_DateY-$PINV_DateM-$PINV_DateD";
	$PINV_TTODatex	= mktime(0,0,0,$PINV_DateM,$PINV_DateD,$PINV_DateY);
	$PINV_TTOTerm	= 30;
	$PINV_ENDDATE 	= date("Y-m-d",strtotime("+$PINV_TTOTerm days",$PINV_TTODatex));
}
else
{
	$PINV_ENDDATE 	=  $default['PINV_ENDDATE'];
	//$PINV_TTODDate 	=  $default['PINV_TTODDate'];
}

// CEK APAKAH PERNAH ADA PERUBAHAN NILAI KONTRAK
	$PRJCOST1		= 0;
	$PINV_OWNER			= '';
	$sqlPRJ 			= "SELECT ISCHANGE, PRJCOST, PRJCOST2, PRJOWN FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
	$resultPRJ 			= $this->db->query($sqlPRJ)->result();
	foreach($resultPRJ  as $rowPRJ) :
		$ISCHANGE		= $rowPRJ->ISCHANGE;
		$PRJCOST1= $rowPRJ->PRJCOST;
		$PRJCOST2		= $rowPRJ->PRJCOST2;
		if($ISCHANGE == 1)
		{
			$PRJCOST1= $PRJCOST2;
		}
		$PINV_OWNER		= $rowPRJ->PRJOWN;	
	endforeach;

// CEK APAKAH PERNAH ADA SI INCLUDE TO KONTRAK
	$PRJCOST2		= 0;
	$sqlPRJ2 			= "SELECT SI_APPVAL FROM tbl_siheader WHERE PRJCODE = '$PRJCODE' AND SI_INCCON = 2";
	$resultPRJ2			= $this->db->query($sqlPRJ2)->result();
	foreach($resultPRJ2  as $rowPRJ2) :
		$PRJCOST2	= $rowPRJ2->SI_APPVAL;
	endforeach;
	$PRJCOST		= $PRJCOST1 + $PRJCOST2;
	
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
	
	// START : PROCEDURE NUMBERING DOKUMEN
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
		$myCount = $this->db->count_all('tbl_projinv_header');

		$sqlRUNN	= "tbl_projinv_header WHERE PRJCODE = '$PRJCODE'";
		$myRUNN 	= $this->db->count_all($sqlRUNN);

		$myMax 		= $myRUNN+1;
		$myMaxRUN 	= $myRUNN+1;
		$PINV_CAT	= 1;
		if (isset($_POST['submitSrch']))
		{
			$PINV_CAT		= $_POST['PINV_CATSRCH'];
		}

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
	
		$lastPatternNumb 	= $myMax;
		$lastPatternNumbX 	= $myMaxRUN;
		$lastPatternNumb1 	= $myMax;
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
	
		$lastPatternNumb 	= $nol.$lastPatternNumb;
		$lastPatternNumbX 	= $nol.$lastPatternNumbX;
	
	
		$sql = "SELECT proj_Number, PRJCODE, PRJNAME FROM tbl_project
				WHERE PRJCODE = '$PRJCODE'";
		$resultProj = $this->db->query($sql)->result();
		foreach($resultProj as $row) :
			$proj_Number 	= $row->proj_Number;
			$PRJCODE 		= $row->PRJCODE;
			$PRJNAME 		= $row->PRJNAME;
		endforeach;
	
		$DocNumber 			= "$Pattern_Code$PRJCODE$groupPattern-$lastPatternNumbX";
		$PINV_CODE 			= "$Pattern_Code$PRJCODE$groupPattern-$lastPatternNumbX";
	// END : PROCEDURE NUMBERING DOKUMEN

	// START : DEFAULT VALUE
		$PINV_MANNO			= '';
		$PINV_STEP			= 0;
		$PINV_CAT 			= $PINV_CAT;
		$PINV_MMC			= 1;
		$PINV_SOURCE		= '';
		$PRJCODE			= $PRJCODE;
		
		$PINV_DATEY 		= date('Y');
		$PINV_DATEM 		= date('m');
		$PINV_DATED 		= date('d');
		$PINV_DATE 			= date('Y-m-d');
		$PINV_DATE 			= "$PINV_DATEM/$PINV_DATED/$PINV_DATEY";
		//$PINV_ENDDATE 	= $PINV_DATE;
		//$PINV_CHECKD		= "$PINV_DATEY-$PINV_DATEM-$PINV_DATED";
		//$PINV_CREATED 	= "$PINV_DATEY-$PINV_DATEM-$PINV_DATED";
		$PINV_ENDDATE0		= mktime(0,0,0,$PINV_DATEM,$PINV_DATED,$PINV_DATEY);
		$PINV_ENDDATE1		= 14;
		$PINV_ENDDATE 		= date("m/d/Y",strtotime("+$PINV_ENDDATE1 days",$PINV_ENDDATE0));
	
		$PINV_DPPER1	= 0;
		$PINV_DPVAL1	= 0;
		$PINV_DPPPNVAL1 = 0;
		$sqlDP 			= "SELECT SUM(PINV_DPPER) AS DPPERCENT, SUM(PINV_DPVAL) AS DPVALUE, SUM(PINV_DPVALPPn) AS PINV_DPPPNVAL
							FROM tbl_projinv_header WHERE PRJCODE = '$PRJCODE' AND PINV_CAT = 1";
		$resDP 			= $this->db->query($sqlDP)->result();
		foreach($resDP as $rowDP) :
			$PINV_DPPER 	= $rowDP->DPPERCENT;
			$PINV_DPVALA 	= $rowDP->DPVALUE;
			$PINV_DPPPNVAL 	= $rowDP->PINV_DPPPNVAL;
			$PINV_DPPER1	= $PINV_DPPER1 + $PINV_DPPER;
			$PINV_DPVAL1	= $PINV_DPVAL1 + $PINV_DPVALA;
			$PINV_DPPPNVAL1 = $PINV_DPPPNVAL1 + $PINV_DPPPNVAL;
		endforeach;
		$PINV_DPVALnPPn		= 
			
		$PINV_DPPER			= $PINV_DPPER1;						// Persentase DP
		$PINV_DPVAL			= $PINV_DPVAL1;						// Nilai DP tanpa PPN
		$PINV_DPVALPPn		= round($PINV_DPPPNVAL1);			// PPN
		$PINV_DPVALnPPn		= $PINV_DPVAL + $PINV_DPVALPPn;		// Nilai DP dengan PPN

		$PINV_RETVAL		= 0;
		$PINV_DPBACK		= 0;
		$PINV_DPBACKCUR		= 0;
		$PINV_DPBACKPPn 	= 0;
		$PINV_RETCUTP		= 0;
		$PINV_RETCUT		= 0;
		$PINV_RETCUTCUR		= 0;
		$PINV_RETCUTPPn		= 0;
		$PINV_PROG			= 0;
		$PINV_PROGCUR		= 0;
		$PINV_PROGCURVAL	= 0;
		$PINV_PROGVAL		= 0;
		$PINV_PROGVALPPn	= 0;
		$PINV_PROGAPP 		= 0;
		$PINV_PROGAPPVAL 	= 0;
		$PINV_VALADD		= 0;
		$PINV_VALADDPPn		= 0;
		$PINV_MATVAL		= 0;
		$PINV_AKUMNEXT		= 0;
		$PINV_TOTVAL		= 0;
		$PINV_TOTVALPPn		= 0;
		$PINV_NOTES			= '';
		$PINV_EMPID			= $DefEmp_ID;
		$PINV_STAT			= 1;
		
		$PATT_YEAR 			= date('Y');
		$PATT_YEAR1			= date('y');
		$PATT_MONTH			= date('m');
		$PATT_DATE 			= date('d');
		
		$DPExist 			= 0;
		$sqlDP 				= "tbl_projinv_header WHERE PRJCODE = '$PRJCODE' AND PINV_CAT = 1";
		$resultDP 			= $this->db->count_all($sqlDP);
		if($resultDP > 0)
		{
			$DPExist 	= 1;
		}
	// END : DEFAULT VALUE
	
	// MENCARI NILAI PEMBAYARAN SEBELUMNYA, DIAMBIL SECARA JESELURUHAN DARI MC
		$PINV_VALBEF		= 0;
		$PINV_VALBEFPPn		= 0;
		$PINV_AKUMNEXT		= 0;
		$PATT_NUMBEF		= $lastPatternNumb1 - 1; // PATTERN NUMBER SEBELUMNYA
		$sqlMC 				= "tbl_projinv_header WHERE PRJCODE = '$PRJCODE' AND PATT_NUMBER = $PATT_NUMBEF";
		$resMC 				= $this->db->count_all($sqlMC);
		if($resMC > 0)
		{
			$sqlAKN	= "SELECT PINV_AKUMNEXT FROM tbl_projinv_header WHERE PRJCODE = '$PRJCODE' AND PATT_NUMBER = $PATT_NUMBEF";
			$resAKN	= $this->db->query($sqlAKN)->result();
			foreach($resAKN as $rowAKN) :
				$PINV_VALBEF 		= $rowAKN ->PINV_AKUMNEXT;
				$PINV_VALBEFNonPPn	= round($PINV_VALBEF * 100 / 110);				// Nilai Pembayaran Sebelumnya, tanpa PPn
				$PINV_VALBEFPPn		= round($PINV_VALBEF - $PINV_VALBEFNonPPn);		// PPn Pembayaran Sebelumnya
			endforeach;
		}
	
	// CEK APAKAH PERNAH DIBUATKAN MC ATAU TIDAK. APABILA SUDAH MAKA AMBIL NILAI MAKSIMUM DARI MC. APABILA TIDAK ADA, AMBIL NILAI MAX DARI DP
		$sqlMCEXIST		= "tbl_projinv_header WHERE PRJCODE = '$PRJCODE'";
		$resMCEXIST		= $this->db->count_all($sqlMCEXIST);
		if($resMCEXIST > 0)
		{
			$sqlMAXMC 	= "SELECT MAX(PINV_STEP) AS MAXSTEP FROM tbl_projinv_header
							WHERE PRJCODE = '$PRJCODE' AND PINV_STAT IN (3,6)";
			$resMAXMC 	= $this->db->query($sqlMAXMC)->result();
			foreach($resMAXMC as $rowMAXMC) :
				$MAXSTEP = $rowMAXMC->MAXSTEP;
			endforeach;
			$MAXSTEP	= $MAXSTEP + 1;
		}
		else
		{
			$sqlDPEXIST	= "tbl_projinv_header WHERE PRJCODE = '$PRJCODE' AND PINV_CAT = 1 AND PINV_STAT IN (3,6)";
			$resDPEXIST	= $this->db->count_all($sqlDPEXIST);
			$MAXSTEP	= $resDPEXIST + 1;
		}
	
	if (isset($_POST['submitSrch']))
	{
		$PINV_CAT		= $_POST['PINV_CATSRCH'];
		if($PINV_CAT == 2)
		{				
			$sqlMAXMC1 	= "SELECT MAX(PINV_STEP) AS MAXSTEP FROM tbl_projinv_header WHERE PRJCODE = '$PRJCODE' AND PINV_CAT = 2 AND PINV_STAT IN (3,6)";
			$resMAXMC1 	= $this->db->query($sqlMAXMC1)->result();
			foreach($resMAXMC1 as $rowMAXMC1) :
				$MAXSTEP = $rowMAXMC1->MAXSTEP;
			endforeach;
			$MAXSTEP	= $MAXSTEP + 1;
		}
		elseif($PINV_CAT == 3)
		{				
			$sqlMAXMC1 	= "SELECT MAX(PINV_STEP) AS MAXSTEP FROM tbl_projinv_header WHERE PRJCODE = '$PRJCODE' AND PINV_CAT = 3 AND PINV_STAT IN (3,6)";
			$resMAXMC1 	= $this->db->query($sqlMAXMC1)->result();
			foreach($resMAXMC1 as $rowMAXMC1) :
				$MAXSTEP = $rowMAXMC1->MAXSTEP;
			endforeach;
			$MAXSTEP	= $MAXSTEP + 1;
		}
	}
	
	$PATT_NUMBEF			= $lastPatternNumb1 - 1; // PATTERN NUMBER SEBELUMNYA		
	
	$PINV_TOTPROGRESS		= 0;
	$PINV_TOTPROGRESSNONPPn	= 0;
	$PINV_PAYBEFRET			= 0;
	$PINV_PAYAKUM			= 0;		
	$PINV_PAYAKUMX			= 0;
	$PINV_PAYAKUMPPn		= 0;
	$PINV_TOTVALPPhP		= 0;
	$PINV_TOTVALPPh			= 0;
	$PINV_RECEIVETOT		= 0;
	$GPINV_TOTVAL			= 0;		
	$PINV_WITHPPN			= 0;
	
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
	
	$lenNo 			= strlen($myMax);
	if($lenNo==1) $nolNo ="00"; else if($lenNo==2) $nolNo="0";			
	$StepInv 		= $nolNo.$myMax;
	
	$PINV_MANNO		= "$StepInv-INV/SP.$myMax-$PRJCODE/$ROM_MONTH/$PATT_YEAR1";
	
	if (isset($_POST['submitSrch']))
	{
		$PINV_CAT		= $_POST['PINV_CATSRCH'];
		if($PINV_CAT == 3)
		{
			$PINV_MANNO		= "$StepInv-INV/VO.$myMax-$PRJCODE/$ROM_MONTH/$PATT_YEAR1";
		}
	}
	
	$MC_REF			= '';
	$PINV_SOURCE_MN	= '';
}
else
{
	$isSetDocNo = 1;
	$DocNumber			= $default['PINV_CODE'];
	$PINV_CODE 			= $default['PINV_CODE'];
	$PINV_MANNO 		= $default['PINV_MANNO'];
	$PINV_STEP 			= $default['PINV_STEP'];
	$MAXSTEP 			= $default['PINV_STEP'];
	$PINV_CAT 			= $default['PINV_CAT'];
	$PINV_MMC 			= $default['PINV_MMC'];
	$PINV_SOURCE		= $default['PINV_SOURCE'];
	$PINV_SOURCE_MN		= $default['PINV_SOURCE_MN'];
	$PRJCODE 			= $default['PRJCODE'];
	$PINV_OWNER 		= $default['PINV_OWNER'];
	$PINV_DATEX			= $default['PINV_DATE'];
	$PINV_DATE 			= date("m/d/Y",strtotime($PINV_DATEX));
	$PINV_ENDDATEX		= $default['PINV_ENDDATE']; 
	$PINV_ENDDATE 		= date("m/d/Y",strtotime($PINV_ENDDATEX));
	$PINV_CHECKD 		= $default['PINV_CHECKD']; 
	$PINV_CREATED		= $default['PINV_CREATED'];
	$PINV_RETVAL 		= $default['PINV_RETVAL'];
	$PINV_RETCUTP 		= $default['PINV_RETCUTP'];
	$PINV_RETCUT 		= $default['PINV_RETCUT'];
	$PINV_RETCUTCUR		= $default['PINV_RETCUTCUR'];
	$PINV_RETCUTPPn		= $default['PINV_RETCUTPPn'];
	$PINV_DPPER 		= $default['PINV_DPPER'];
	$PINV_DPVAL 		= $default['PINV_DPVAL'];
	$PINV_DPVALPPn		= $default['PINV_DPVALPPn'];
	$PINV_DPBACK 		= $default['PINV_DPBACK'];
	$PINV_DPBACKCUR		= $default['PINV_DPBACKCUR'];
	$PINV_DPBACKPPn 	= $default['PINV_DPBACKPPn'];
	$PINV_PROG 			= $default['PINV_PROG'];
	$PINV_PROGVAL		= $default['PINV_PROGVAL'];
	$PINV_PROGVALPPn	= $default['PINV_PROGVALPPn'];
	$PINV_PROGCUR		= $default['PINV_PROGCUR'];
	$PINV_PROGCURVAL	= $default['PINV_PROGCURVAL'];
	$PINV_PROGAPP		= $default['PINV_PROGAPP'];
	$PINV_PROGAPPVAL 	= $default['PINV_PROGAPPVAL'];
	$PINV_VALADD 		= $default['PINV_VALADD'];
	$PINV_VALADDPPn 	= $default['PINV_VALADDPPn'];
	$PINV_MATVAL 		= $default['PINV_MATVAL'];
	$PINV_VALBEF		= $default['PINV_VALBEF'];
	$PINV_VALBEFPPn		= $default['PINV_VALBEFPPn'];
	$PINV_AKUMNEXT		= $default['PINV_AKUMNEXT'];
	$PINV_TOTVAL 		= $default['PINV_TOTVAL'];
	$PINV_TOTVALPPn 	= $default['PINV_TOTVALPPn'];
	$PINV_TOTVALPPhP 	= $default['PINV_TOTVALPPhP'];
	$PINV_TOTVALPPh 	= $default['PINV_TOTVALPPh'];
	$GPINV_TOTVAL 		= $default['GPINV_TOTVAL'];
	$PINV_NOTES 		= $default['PINV_NOTES'];
	$PINV_EMPID 		= $default['PINV_EMPID'];
	$PINV_STAT 			= $default['PINV_STAT'];
	$PATT_YEAR 			= $default['PATT_YEAR'];
	$PATT_MONTH 		= $default['PATT_MONTH'];
	$PATT_DATE 			= $default['PATT_DATE'];
	$PATT_NUMBER 		= $default['PATT_NUMBER'];
	$PRJCODE 			= $default['PRJCODE'];
	$MC_REF 			= $default['MC_REF'];
	
	$PINV_TOTPROGRESS		= $PINV_PROGVAL + $PINV_VALADD + $PINV_MATVAL;	
	$PINV_TOTPROGRESSPPn	= 0.11 * $PINV_TOTPROGRESS;
	$PINV_TOTPROGRESS		= $PINV_TOTPROGRESS + $PINV_TOTPROGRESSPPn;	
	$PINV_TOTPROGRESSNONPPn	= $PINV_TOTPROGRESS * 100 / 110;
	$PINV_PAYBEFRET			= $PINV_TOTPROGRESS - $PINV_VALBEF - $PINV_DPBACK - $PINV_RETCUT;
	//$PINV_PAYAKUM			= $PINV_PAYBEFRET - $PINV_VALBEF;
	$PINV_PAYAKUM			= $PINV_TOTVAL;
	$PINV_PAYAKUMPPn		= round(0.11 * $PINV_PAYAKUM);
	$PINV_PAYAKUMX			= $PINV_TOTPROGRESS;
	$PINV_TOTVALPPh			= $PINV_TOTVALPPh;
	$PINV_RECEIVETOT		= $PINV_TOTVAL - $PINV_TOTVALPPh;
	
	$PINV_WITHPPN			= $PINV_TOTVAL + $PINV_TOTVALPPn;
}

$ownIns = 'S';
$sqlOWN	= "SELECT own_Code, own_Title, own_Inst, own_Name FROM tbl_owner WHERE own_Code = '$PINV_OWNER'";
$resOWN	= $this->db->query($sqlOWN)->result();
foreach($resOWN as $rowOWN) :
	$ownIns 	= $rowOWN ->own_Inst;
endforeach;

if (isset($_POST['submitSrch']))
{
	$PINV_CAT		= $_POST['PINV_CATSRCH'];
}
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

	<style type="text/css">
		.search-table, td, th {
			border-collapse: collapse;
		}
		.search-table-outter { overflow-x: scroll; }
		
	    a[disabled="disabled"] {
	        pointer-events: none;
	    }
	</style>

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
			if($TranslCode == 'Edit')$Edit = $LangTransl;
			if($TranslCode == 'Save')$Save = $LangTransl;
			if($TranslCode == 'Update')$Update = $LangTransl;
			if($TranslCode == 'Back')$Back = $LangTransl;
			if($TranslCode == 'Retention')$Retention = $LangTransl;
			if($TranslCode == 'kwitNo')$kwitNo = $LangTransl;
			if($TranslCode == 'INVCode')$INVCode = $LangTransl;
			if($TranslCode == 'Category')$Category = $LangTransl;
			if($TranslCode == 'InvoiceDate')$InvoiceDate = $LangTransl;
			if($TranslCode == 'RefNumber')$RefNumber = $LangTransl;
			if($TranslCode == 'INVStep')$INVStep = $LangTransl;
			if($TranslCode == 'Project')$Project = $LangTransl;
			if($TranslCode == 'ProjectValue')$ProjectValue = $LangTransl;
			if($TranslCode == 'DPPercentation')$DPPercentation = $LangTransl;
			if($TranslCode == 'DPValue')$DPValue = $LangTransl;
			if($TranslCode == 'PercentProgress')$PercentProgress = $LangTransl;
			if($TranslCode == 'PercentationValue')$PercentationValue = $LangTransl;
			if($TranslCode == 'Amount')$Amount = $LangTransl;
			if($TranslCode == 'Notes')$Notes = $LangTransl;
			if($TranslCode == 'OwnerName')$OwnerName = $LangTransl;
			if($TranslCode == 'DueDate')$DueDate = $LangTransl;
			if($TranslCode == 'AdvPaymentInstallment')$AdvPaymentInstallment = $LangTransl;
			if($TranslCode == 'Search')$Search = $LangTransl;
			
			if($TranslCode == 'MCCode')$MCCode = $LangTransl;
			if($TranslCode == 'MCNumber')$MCNumber = $LangTransl;
			if($TranslCode == 'MCDate')$MCDate = $LangTransl;
			if($TranslCode == 'PrestationVal')$PrestationVal = $LangTransl;
			if($TranslCode == 'ReceivedAmount')$ReceivedAmount = $LangTransl;
			if($TranslCode == 'Select')$Select = $LangTransl;
			if($TranslCode == 'Close')$Close = $LangTransl;
		endforeach;

		if($LangID == 'IND')
		{
			$alert1 	= "Tidak ada MC yang dipilih.";
		}
		else
		{
			$alert1 	= "No MC document selected.";
		}
		
		$sql = "SELECT proj_Number, PRJCODE, PRJNAME FROM tbl_project
				WHERE PRJCODE = '$PRJCODE'";
		$resultProj = $this->db->query($sql)->result();
		foreach($resultProj as $row) :
			$PRJNAME 		= $row->PRJNAME;
		endforeach;
		if($LangID == 'IND')
		{
			$h1_title	= "Tambah Faktur";
		}
		else
		{
			$h1_title	= "Add Invoice";
		}
		
		$isEdit	= 'N';
		if($PINV_STAT == 1)
		{
			$isEdit	= 'Y';
		}
		if($PINV_STAT == 4)
		{
			$isEdit	= 'Y';
		}
		elseif($PINV_STAT == 2 && $ISAPPROVE != 1)
		{
			$isEdit	= 'N';
		}
		elseif($PINV_STAT == 2 && $ISAPPROVE == 1)
		{
			$isEdit	= 'Y';
		}
		elseif($PINV_STAT == 3)
		{
			$isEdit	= 'N';
		}
		
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
				$sqlC_App	= "tbl_approve_hist WHERE AH_CODE = '$PINV_CODE'";
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
				$APPROVE_AMOUNT = $PINV_TOTVAL;
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
			
			// START : SPECIAL FOR SASMITO
				// Cek Super approve for Major Item
				$Emp_ID1	= '';
				$Emp_ID2	= '';
				$sqlMJREMP	= "SELECT * FROM tbl_major_app";
				$resMJREMP	= $this->db->query($sqlMJREMP)->result();
				foreach($resMJREMP as $rowMJR) :
					$Emp_ID1	= $rowMJR->Emp_ID1;
					$Emp_ID2	= $rowMJR->Emp_ID2;
				endforeach;
				if(($DefEmp_ID == $Emp_ID1) || ($DefEmp_ID == $Emp_ID2))
				{
					$canApprove	= 1;
				}
				$sqlCMJR	= "tbl_pr_detail A
									INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
										AND B.PRJCODE
								WHERE PR_NUM = '$PINV_CODE' 
									AND B.PRJCODE = '$PRJCODE'
									AND B.ISMAJOR = 1";
				$resCMJR = $this->db->count_all($sqlCMJR);
			// END : SPECIAL FOR SASMITO
			
			$APP_LEVEL	= $APP_STEP;
		// END : APPROVE PROCEDURE

        $comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
			<h1>
		    <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/invoice.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $h1_title; ?>
		    <small><?php echo $PRJNAME; ?></small>  </h1>
		</section>

		<section class="content">	
		    <div class="row">
		    	<form name="frmsrch" id="frmsrch" action="" method=POST>
		            <table width="100%" border="0">
		                <tr>
		                    <td>
		                        <input type="hidden" name="PINV_CATSRCH" id="PINV_CATSRCH" class="textbox" value="<?php echo $PINV_CAT; ?>" />
		                        <input type="submit" class="button_css" name="submitSrch" id="submitSrch" value=" search " style="display:none" />
		                    </td>
		                </tr>
		            </table>
		        </form>
		      	<form name="frm" method="post" action="<?php echo $form_action; ?>" enctype="multipart/form-data" onSubmit="return checkInp()">
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
					          	<input type="hidden" name="CatSelected" id="CatSelected" value="<?php echo $PINV_CAT; ?>">
					       	  	<input type="hidden" name="PINV_STATX" id="PINV_STATX" value="<?php echo $PINV_STAT; ?>">
					            <input type="hidden" name="OWN_INST" id="OWN_INST" value="<?php echo $ownIns; ?>">
								<?php if($isSetDocNo == 0) { ?>
					                <div class="col-sm-10">
					                    <div class="alert alert-danger alert-dismissible">
					                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					                        <h4><i class="icon fa fa-ban"></i> <?php echo $docalert1; ?>!</h4>
					                        <?php echo $docalert2; ?>
					                    </div>
					                </div>
					            <?php } ?>

					            <!-- PINV_CODE -->
	                        	<div class="col-sm-12">
	                                <div class="form-group">
	                                    <label for="inputName"><?php echo $kwitNo; ?></label>
		                                <input type="hidden" class="textbox" name="PINV_CODE" id="PINV_CODE" size="30" value="<?php echo $PINV_CODE; ?>" />
				                        <input type="hidden" class="textbox" name="PATT_NUMBER" id="PATT_NUMBER" size="30" value="<?php echo $PATT_NUMBER; ?>" />
				                        <input type="hidden" name="PINV_MMC" id="PINV_MMC" value="<?php echo $PINV_MMC; ?>">
				                        <input type="text" class="form-control" name="PINV_CODE1" id="PINV_CODE1" value="<?php echo $DocNumber; ?>" disabled>
	                                </div>
	                            </div>

					            <!-- PINV_MANNO -->
	                        	<div class="col-sm-12">
	                                <div class="form-group">
	                                	<label for="inputName">
	                                		<?php 
	                                			//echo $INVCode;
	                                			echo "No. Seri Faktur";
	                                		?>
	                                	</label>
	                                	<input type="text" name="PINV_MANNO" id="PINV_MANNO" value="<?php echo $PINV_MANNO; ?>" class="form-control">
	                                </div>
	                            </div>

					            <!-- PINV_DATE -->
	                        	<div class="col-sm-4">
	                                <div class="form-group">
	                                	<label for="inputName"><?php echo $InvoiceDate; ?></label>
		                                <div class="input-group date">
		                            		<div class="input-group-addon">
		                            		<i class="fa fa-calendar"></i>&nbsp;</div><input type="text" name="PINV_DATE" class="form-control pull-left" id="datepicker" value="<?php echo $PINV_DATE; ?>" style="width:100px" onChange="changeFDate(this)">
		                            	</div>
	                                </div>
	                            </div>

					            <!-- PINV_ENDDATE -->
	                        	<div class="col-sm-6">
	                                <div class="form-group">
	                                	<label for="inputName"><?php echo $DueDate; ?></label>
		                                <div class="input-group date">
		                            		<div class="input-group-addon">
		                            		<i class="fa fa-calendar"></i>&nbsp;</div><input type="text" name="PINV_ENDDATE" class="form-control pull-left" id="datepicker1" value="<?php echo $PINV_ENDDATE; ?>" style="width:100px">
		                            	</div>
	                                </div>
	                            </div>

					            <!-- PINV_CAT -->
	                        	<div class="col-sm-4">
	                                <div class="form-group">
	                                	<label for="inputName"><?php echo $Category; ?></label>
	                                	<select name="PINV_CAT" id="PINV_CAT" class="form-control select2" onChange="ShowDocSelect(this.value)"> 
											<option value="0"> -- </option>
											<option value="1"<?php if($PINV_CAT == 1) { ?> selected <?php } ?>>DP</option>
											<option value="2"<?php if($PINV_CAT == 2) { ?> selected <?php } ?>>MC</option>
											<option value="3"<?php if($PINV_CAT == 3) { ?> selected <?php } ?>>SI</option>
										</select>
				                        <input type="hidden" name="PINV_CATX" id="PINV_CATX" value="<?php echo $PINV_CAT; ?>">
	                                </div>
	                            </div>

								<script>
				                    function ShowDocSelect(thisVal)
				                    {
				                        if(thisVal == 1)
				                        {
				                            /*document.getElementById('MCSelectNo').style.display 		= 'none';
				                            document.getElementById('SISelectNo').style.display 		= 'none';
				                            document.getElementById('CatSelected').value				= thisVal;
				                            document.getElementById('PercentProgress').style.display 	= 'none';
				                            document.getElementById('ValueProgress').style.display 		= 'none';
				                            document.getElementById('PINV_DPPER1').disabled 			= false;
				                            document.getElementById('PINV_CATX').value					= thisVal;*/
				                            document.getElementById('PINV_CATSRCH').value				= thisVal;
				                            document.frmsrch.submitSrch.click();
				                        }
				                        else if(thisVal == 2)
				                        {
				                            /*document.getElementById('MCSelectNo').style.display 		= '';
				                            document.getElementById('SISelectNo').style.display 		= 'none';
				                            document.getElementById('CatSelected').value				= thisVal;
				                            document.getElementById('PercentProgress').style.display 	= '';
				                            document.getElementById('ValueProgress').style.display 		= '';
				                            document.getElementById('PINV_DPPER1').disabled 			= true;
				                            document.getElementById('PINV_CATX').value					= thisVal;*/
				                            document.getElementById('PINV_CATSRCH').value				= thisVal;
				                            document.frmsrch.submitSrch.click();
				                        }
				                        else if(thisVal == 3)
				                        {
				                            /*document.getElementById('MCSelectNo').style.display 		= 'none';
				                            document.getElementById('SISelectNo').style.display 		= '';
				                            document.getElementById('CatSelected').value				= thisVal;
				                            document.getElementById('PercentProgress').style.display 	= '';
				                            document.getElementById('ValueProgress').style.display 		= '';
				                            document.getElementById('PINV_DPPER1').disabled 			= true;
				                            document.getElementById('PINV_CATX').value					= thisVal;*/
				                            document.getElementById('PINV_CATSRCH').value				= thisVal;
				                            document.frmsrch.submitSrch.click();
				                        }
				                    }
				                </script>

	                        	<?php if($PINV_CAT == 2) { ?>
	                        		<!-- PINV_SOURCE, PINV_SOURCE_MN, MC_REF -->
	                          		<div class="col-sm-8">
		                                <div class="form-group">
		                          			<label for="inputName">&nbsp;</label>
			                                <div class="input-group">
			                                    <div class="input-group-btn">
													<button type="button" class="btn btn-primary"><?php echo $Search ?> </button>
			                                    </div>
			                          			<!-- <input type="text" class="form-control" name="PINV_SOURCE1" id="PINV_SOURCE1" value="<?php echo $PINV_SOURCE; ?>" onClick="selectSourceMC();" /> -->
			                          			<input type="text" class="form-control" name="PINV_SOURCE1" id="PINV_SOURCE1" value="<?php echo $PINV_SOURCE; ?>" data-toggle="modal" data-target="#mdl_addMC" <?php if($task != 'add') { ?> disabled <?php } ?> />
			                          		</div>
					                        <input type="hidden" name="PINV_SOURCE" id="PINV_SOURCE" value="<?php echo $PINV_SOURCE; ?>" />
					                        <input type="hidden" name="PINV_SOURCE_MN" id="PINV_SOURCE_MN" value="<?php echo $PINV_SOURCE_MN; ?>" />
					                        <input type="hidden" name="MC_REF" id="MC_REF" size="20" value="<?php echo $MC_REF; ?>" />
					                        <?php
					                            
					                            $url_popSourceMC	= site_url('c_project/c_prj180c2dinv/pall180c2dmc/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
					                            //$url_popSourceMC	= site_url('c_project/c_prj180c2dinv_sd/pall180c2dmc');
					                        ?>
					                        <script>
					                            var urlMC = "<?php echo "$url_popSourceMC";?>";
					                            function selectSourceMC()
					                            {
					                                title = 'Select Item';
					                                w = 950;
					                                h = 550;
					                                //window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
					                                var left = (screen.width/2)-(w/2);
					                                var top = (screen.height/2)-(h/2);
					                                return window.open(urlMC, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
					                            }
					                        </script>
					                        <a href="javascript:void(null);" onClick="selectSourceMC();" style="display:none">
					                        <img src="<?php echo base_url().'images/11.png';?>" width="20" height="20"></a>
					                    </div>
	                          		</div>
	                        	<?php } elseif($PINV_CAT == 3) { ?>
	                        		<!-- PINV_SOURCE2 -->
	                          		<div class="col-sm-8">
		                                <div class="form-group">
		                          			<label for="inputName">&nbsp;</label>
			                                <div class="input-group">
			                                    <div class="input-group-btn">
													<button type="button" class="btn btn-primary"><?php echo $Search ?> </button>
			                                    </div>
		                          				<input type="text" class="form-control" style="max-width:300px" name="PINV_SOURCE2" id="PINV_SOURCE2" value="<?php echo $PINV_SOURCE; ?>" onClick="selectSourceSI();" />
					                        	<input type="hidden" name="PINV_SOURCE_MN" id="PINV_SOURCE_MN" value="<?php echo $PINV_SOURCE_MN; ?>" />
		                          			</div>
					                        <?php
					                            $url_popSourceSI	= site_url('c_project/c_prj180c2dinv/pall180c2dsi/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
					                        ?>
					                        <script>
					                            var urlPR = "<?php echo "$url_popSourceSI";?>";
					                            function selectSourceSI()
					                            {
					                                title = 'Select Item';
					                                w = 850;
					                                h = 550;
					                                //window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
					                                var left = (screen.width/2)-(w/2);
					                                var top = (screen.height/2)-(h/2);
					                                return window.open(urlPR, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
					                            }
					                        </script>
					                        <a href="javascript:void(null);" onClick="selectSourceSI();" style="display:none">
					                        <img src="<?php echo base_url().'images/11.png';?>" width="20" height="20"></a>
					                    </div>
	                          		</div>
	                        	<?php } elseif($PINV_CAT == 1) { ?>
	                          		<div class="col-sm-8">
		                                <div class="form-group">
		                          			<label for="inputName">&nbsp;</label>
			                                <div class="input-group">
			                                    <div class="input-group-btn">
													<button type="button" class="btn btn-primary"><?php echo $Search ?> </button>
			                                    </div>
		                          				<input type="text" class="form-control" readonly />
					                       		<input type="hidden" name="PINV_SOURCE_MN" id="PINV_SOURCE_MN" value="<?php echo $PINV_SOURCE_MN; ?>" />
		                          			</div>
		                          		</div>
	                          		</div>
	                        	<?php } ?>

					            <!-- PINV_STEP, PINV_STEP1 -->
					            <?php
					            	if($task == 'add' && $PINV_CAT == 1 && $MAXSTEP == 1)
					            		$MAXSTEP = 0;	
					            ?>
	                        	<div class="col-sm-4">
	                                <div class="form-group">
	                                	<label for="inputName"><?php echo $INVStep; ?></label>
		                          		<select name="PINV_STEP1" id="PINV_STEP1" class="form-control select2">
					                        <?php
					                            for($STEP=0;$STEP<=30;$STEP++)
					                            {
					                                /*$lenNo 		= $STEP;
					                                if($lenNo==1) $nolNo ="00"; else if($lenNo==2) $nolNo="0";			
					                                $StepInv 	= $nolNo.$lenNo;*/
					                                ?>
					                                <option value="<?php echo $STEP; ?>" <?php if($STEP == $MAXSTEP) { ?> selected <?php } ?>><?php echo $STEP; ?></option>
					                                <?php
					                            }
					                        ?>
					                    </select>
					                    <input type="hidden" name="PINV_STEP" id="PINV_STEP" value="<?php echo $MAXSTEP; ?>">
					                </div>
	                            </div>

					            <!-- PINV_OWNER -->
	                        	<div class="col-sm-12">
	                                <div class="form-group">
	                                	<label for="inputName"><?php echo $OwnerName; ?></label>
		                          		<select name="PINV_OWNER" id="PINV_OWNER" class="form-control select2" onchange="getOWNCatg(this.value)">
						                    <option value="none"> --- </option>
						                    <?php
											$sqlOWNC 	= "tbl_owner";
											$resOWNC	= $this->db->count_all($sqlOWNC);
											
											$sqlOWNL 	= "SELECT * FROM tbl_owner ORDER BY own_Name";
											$resOWNL	= $this->db->query($sqlOWNL)->result();
											
						                    if($resOWNC > 0)
						                    {
						                        foreach($resOWNL as $row) :
						                            $own_Code 	= $row->own_Code;
													$own_Title1	= '';
						                            $own_Title 	= $row->own_Title;
													if($own_Title != '')
														$own_Title1	= ", $own_Title";
						                            $own_Name 	= $row->own_Name;
						                            ?>
						                            <option value="<?php echo $own_Code; ?>" <?php if($own_Code == $PINV_OWNER) { ?> selected <?php } ?>><?php echo "$own_Name$own_Title1"; ?></option>
						                            <?php
						                        endforeach;
						                    }
						                    ?>
						                </select>
						            </div>
	                            </div>

					            <!-- PINV_NOTES -->
	                        	<div class="col-sm-12">
	                                <div class="form-group">
	                                	<label for="inputName"><?php echo $Notes; ?></label>
		                          		<textarea name="PINV_NOTES" class="form-control" id="PINV_NOTES" style="height:110px;"><?php echo $PINV_NOTES; ?></textarea>
	                                </div>
	                            </div>
							</div>
						</div>
					</div>

					<div class="col-md-6">
						<div class="box box-warning">
							<div class="box-header with-border" style="display: none;">
								<i class="fa fa-cloud-upload" style="display: none;"></i>
								<h3 class="box-title">Progress</h3>
							</div>
							<div class="box-body">
					            <!-- PRJCODE, PRJCOST, PRJCOST1, PINV_RETVAL -->
	                        	<div class="col-sm-4">
	                                <div class="form-group">
	                                	<label for="inputName"><?php echo $ProjectValue; ?></label>
	                                	<input type="hidden" name="PRJCODE" id="PRJCODE" value="<?php echo $PRJCODE; ?>" >
		                        		<input type="text" class="form-control" style="text-align:right;" name="PRJCOST1" id="PRJCOST1" value="<?php print number_format($PRJCOST, 0); ?>" disabled>
		                        		<input type="hidden" size="15" class="textbox" style="text-align:right;" name="PRJCOST" id="PRJCOST" value="<?php echo $PRJCOST; ?>">
		                          		<input type="hidden" size="10" class="textbox" style="text-align:right;" name="PINV_RETVAL" id="PINV_RETVAL" value="<?php echo $PINV_RETVAL; ?>">
	                                </div>
	                            </div>

					            <!-- PRJCOST_PPN, PRJCOST_PPN1 -->
	                        	<div class="col-sm-4">
	                                <div class="form-group">
	                                	<label for="inputName"><!-- PPn -->&nbsp;</label>
		                          		<?php
					                        $PRJCOST_PPN 	= round($PRJCOST * 0.11);
					                        $PRJCOST_TOTAL 	= round($PRJCOST + $PRJCOST_PPN);
					                    ?>
					                      <input type="text" class="form-control" style="text-align:right;" name="PRJCOST_PPN1" id="PRJCOST_PPN1" value="<?php print number_format($PRJCOST_PPN, 0); ?>" disabled>
					                      <input type="hidden" size="15" class="textbox" style="text-align:right;" name="PRJCOST_PPN" id="PRJCOST_PPN" value="<?php echo $PRJCOST_PPN; ?>">
	                                </div>
	                            </div>

					            <!-- PRJCOST_TOTAL, PRJCOST_TOTAL1 -->
	                        	<div class="col-sm-4">
	                                <div class="form-group">
	                                	<label for="inputName"><!-- Total -->&nbsp;</label>
		                          		<input type="text" class="form-control" style="text-align:right;" name="PRJCOST_TOTAL1" id="PRJCOST_TOTAL1" value="<?php print number_format($PRJCOST_TOTAL, 0); ?>" disabled>
		                   				<input type="hidden" size="15" class="textbox" style="text-align:right;" name="PRJCOST_TOTAL" id="PRJCOST_TOTAL" value="<?php echo $PRJCOST_TOTAL; ?>">
	                                </div>
	                            </div>

					            <!-- PINV_DPPER, PINV_DPPER1, PINV_DPVAL, PINV_DPVAL1, PINV_DPVALPPn, PINV_DPVALPPn1 -->
								<?php if($PINV_CAT != 3) { ?>
		                        	<div class="col-sm-3">
		                                <div class="form-group">
		                                	<label for="inputName">DP (%)</label>
			                          		<input type="text" class="form-control" style="text-align:right;" name="PINV_DPPER1" id="PINV_DPPER1" value="<?php print number_format($PINV_DPPER, 4); ?>" onBlur="getDPValue(this)" <?php if($PINV_CAT == 2) { ?> readonly <?php } ?> >
		                        			<input type="hidden" size="2" class="textbox" style="text-align:right;" name="PINV_DPPER" id="PINV_DPPER" value="<?php echo $PINV_DPPER; ?>" >
		                                </div>
		                            </div>
		                        	<div class="col-sm-5">
		                                <div class="form-group">
		                                	<label for="inputName">Nilai DP</label>
			                          		<input type="text" class="form-control" style="text-align:right;" name="PINV_DPVAL1" id="PINV_DPVAL1" value="<?php print number_format($PINV_DPVAL, $decFormat); ?>" disabled>
		                        			<input type="hidden" size="10" class="textbox" style="text-align:right;" name="PINV_DPVAL" id="PINV_DPVAL" value="<?php echo $PINV_DPVAL; ?>">
		                                </div>
		                            </div>
		                        	<div class="col-sm-4">
		                                <div class="form-group">
		                                	<label for="inputName">PPn DP</label>
			                          		<input type="text" class="form-control" style="text-align:right;" name="PINV_DPVALPPn1" id="PINV_DPVALPPn1" value="<?php print number_format($PINV_DPVALPPn, $decFormat); ?>" readonly >
		                        			<input type="hidden" size="17" class="textbox" style="text-align:right;" name="PINV_DPVALPPn" id="PINV_DPVALPPn" value="<?php echo $PINV_DPVALPPn; ?>">
		                                </div>
		                            </div>
		                        <?php }

		                        if($PINV_CAT != 1) { ?>
						            <!-- PINV_DPPER2 -->
		                        	<div class="col-sm-4">
		                                <div class="form-group">
		                                	<label for="inputName">Pot. DP (%)</label>
			                          		<input type="text" class="form-control" style="text-align:right;" name="PINV_DPPER2" id="PINV_DPPER2" value="<?php print number_format($PINV_DPPER, 4); ?>" readonly>
		                                </div>
		                            </div>
		                            
						            <!-- PINV_DPBACK, PINV_DPBACKX -->
		                        	<div class="col-sm-4">
		                                <div class="form-group">
		                                	<label for="inputName">Nilai Potongan DP</label>
			                          		<input type="text" class="form-control" style="text-align:right;" name="PINV_DPBACKX" id="PINV_DPBACKX" value="<?php print number_format($PINV_DPBACK, 2); ?>" title="Pot. DP Akumulasi" readonly>
					                        <input type="hidden" size="10" class="textbox" style="text-align:right;" name="PINV_DPBACK" id="PINV_DPBACK" value="<?php echo $PINV_DPBACK; ?>">
		                                </div>
		                            </div>
		                            
						            <!-- PINV_DPBACKCUR, PINV_DPBACKCUR1 -->
		                        	<div class="col-sm-4">
		                                <div class="form-group">
		                                	<label for="inputName">Pot. DP (Saat Ini)</label>
			                          		<input type="text" class="form-control" style="text-align:right;" name="PINV_DPBACKCUR1" id="PINV_DPBACKCUR1" value="<?php print number_format($PINV_DPBACKCUR, 2); ?>" title="Pot. DP saat ini" onBlur="chgDPBackCur(this.value)">
					                        <input type="hidden" size="10" class="textbox" style="text-align:right;" name="PINV_DPBACKCUR" id="PINV_DPBACKCUR" value="<?php echo $PINV_DPBACKCUR; ?>">
		                                </div>
		                            </div>
		                            
						            <!-- PINV_PROG, PINV_PROG1 -->
		                        	<div class="col-sm-3">
		                                <div class="form-group">
		                                	<label for="inputName">Progress (%)</label>
			                          		<input type="text" class="form-control" style="text-align:right;" name="PINV_PROG1" id="PINV_PROG1" value="<?php print number_format($PINV_PROG, 4); ?>" onBlur="getPROGValue(this.value)" onKeyPress="return isIntOnlyNew(event);" readonly>
					                        <input type="hidden" class="textbox" style="text-align:right;" name="PINV_PROG" id="PINV_PROG" value="<?php echo $PINV_PROG; ?>">
		                                </div>
		                            </div>
		                            
						            <!-- PINV_PROGVAL, PINV_PROGVAL1, PINV_VALADD, PINV_TOTPROGRESS, PINV_PAYBEFRET, PINV_VALBEF, PINV_TOTVAL, PINV_TOTVALPPn -->
		                        	<div class="col-sm-5">
		                                <div class="form-group">
		                                	<label for="inputName">Nilai Progress</label>
			                          		<input type="text" class="form-control" style="text-align:right;" name="PINV_PROGVAL1" id="PINV_PROGVAL1" value="<?php print number_format($PINV_PROGVAL, $decFormat); ?>" title="Progres akumulasi sampai saat ini" disabled>
					                        <input type="hidden" size="10" class="textbox" style="text-align:right;" name="PINV_PROGVAL" id="PINV_PROGVAL" value="<?php echo $PINV_PROGVAL; ?>">
					                        <input type="hidden" size="10" class="textbox" style="text-align:right;" name="PINV_VALADD" id="PINV_VALADD" value="<?php echo $PINV_VALADD; ?>">
					                        <input type="hidden" size="10" class="textbox" style="text-align:right;" name="PINV_TOTPROGRESS" id="PINV_TOTPROGRESS" value="<?php echo $PINV_TOTPROGRESS; ?>">
					                        <input type="hidden" size="10" class="textbox" style="text-align:right;" name="PINV_PAYBEFRET" id="PINV_PAYBEFRET" value="<?php echo $PINV_PAYBEFRET; ?>">
					                        <input type="hidden" size="10" class="textbox" style="text-align:right;" name="PINV_VALBEF" id="PINV_VALBEF" value="<?php echo $PINV_VALBEF; ?>">
					                        <input type="hidden" size="10" style="text-align:right;" name="PINV_TOTVAL" id="PINV_TOTVAL" value="<?php echo $PINV_TOTVAL; ?>"> 
					                        <input type="hidden" size="10" style="text-align:right;" name="PINV_TOTVALPPn" id="PINV_TOTVALPPn" value="<?php echo $PINV_TOTVALPPn; ?>">
		                                </div>
		                            </div>
		                            
						            <!-- PINV_PROGCUR, PINV_PROGCURVAL, PINV_PROGCURVALX -->
		                        	<div class="col-sm-4">
		                                <div class="form-group">
		                                	<label for="inputName">Progress (Sat Ini)</label>
					                        <input type="hidden" class="textbox" style="text-align:right;" name="PINV_PROGCUR" id="PINV_PROGCUR" value="<?php echo $PINV_PROGCUR; ?>">
					                        <input type="hidden" class="textbox" style="text-align:right;" name="PINV_PROGCURVAL" id="PINV_PROGCURVAL" value="<?php echo $PINV_PROGCURVAL; ?>">
			                          		<input type="text" class="form-control" style="text-align:right;" name="PINV_PROGCURVALX" id="PINV_PROGCURVALX" value="<?php print number_format($PINV_PROGCURVAL, 2); ?>" onBlur="chgProgCurr(this.value)" title="Progres saat ini">
		                                </div>
		                            </div>
		                        <?php } ?>
	                            
					            <!-- PINV_RETCUTP, PINV_RETCUTPX -->
	                        	<div class="col-sm-3">
	                                <div class="form-group">
	                                	<label for="inputName"><?php echo $Retention; ?> (%)</label>
				                        <input type="text" class="form-control" style="text-align:right;" name="PINV_RETCUTPX" id="PINV_RETCUTPX" value="<?php echo number_format($PINV_RETCUTP, 4); ?>" onBlur="chgRetPer(this.value)" onKeyPress="return isIntOnlyNew(event);">
				                        <input type="hidden" class="form-control" style="max-width:130px; text-align:right;" name="PINV_RETCUTP" id="PINV_RETCUTP" value="<?php echo $PINV_RETCUTP; ?>" title="RetCut = 0.05*Progress Approved">
	                                </div>
	                            </div>
	                            
					            <!-- PINV_RETCUT, PINV_RETCUTX -->
	                        	<div class="col-sm-5">
	                                <div class="form-group">
	                                	<label for="inputName">Nilai Retensi</label>
				                        <input type="text" class="form-control" style="text-align:right;" name="PINV_RETCUTX" id="PINV_RETCUTX" value="<?php echo number_format($PINV_RETCUT, $decFormat); ?>" onBlur="chgRetValAkum(this.value)">
				                        <input type="hidden" class="form-control" style="max-width:155px; text-align:right;" name="PINV_RETCUT" id="PINV_RETCUT" value="<?php echo $PINV_RETCUT; ?>" title="Payment Before Retention">
	                                </div>
	                            </div>
	                            
					            <!-- PINV_RETCUTCUR, PINV_RETCUTCUR1 -->
	                        	<div class="col-sm-4">
	                                <div class="form-group">
	                                	<label for="inputName">Retensi (Saat Ini)</label>
				                        <input type="text" class="form-control" style="text-align:right;" name="PINV_RETCUTCUR1" id="PINV_RETCUTCUR1" value="<?php echo number_format($PINV_RETCUTCUR, $decFormat); ?>" onBlur="chgRetValCur(this.value)">
				                        <input type="hidden" class="form-control" style="max-width:155px; text-align:right;" name="PINV_RETCUTCUR" id="PINV_RETCUTCUR" value="<?php echo $PINV_RETCUTCUR; ?>">
	                                </div>
	                            </div>
	                            
					            <!-- PINV_PAYAKUM, PINV_PAYAKUMX -->
	                        	<div class="col-sm-7">
	                                <div class="form-group">
	                                	<label for="inputName"><?php echo $Amount; ?></label>
		                          		<input type="text" class="form-control" style="text-align:right;" name="PINV_PAYAKUMX" id="PINV_PAYAKUMX" value="<?php print number_format($PINV_PAYAKUM, $decFormat); ?>" disabled>
				                        <input type="hidden" size="10" class="textbox" style="text-align:right;" name="PINV_PAYAKUM" id="PINV_PAYAKUM" value="<?php echo $PINV_PAYAKUM; ?>">
	                                </div>
	                            </div>
	                            
					            <!-- PINV_PAYAKUMPPn, PINV_PAYAKUMPPnX, PINV_AKUMNEXT -->
	                        	<div class="col-sm-5">
	                                <div class="form-group">
	                                	<label for="inputName">PPn</label>
		                          		<input type="text" class="form-control" style="text-align:right;" name="PINV_PAYAKUMPPnX" id="PINV_PAYAKUMPPnX" value="<?php print number_format($PINV_PAYAKUMPPn, $decFormat); ?>" onBlur="chgPPn(this.value)">
				                        <input type="hidden" size="10" class="textbox" style="text-align:right;" name="PINV_PAYAKUMPPn" id="PINV_PAYAKUMPPn" value="<?php echo $PINV_PAYAKUMPPn; ?>">
				                        <input type="hidden" size="10" class="textbox" style="text-align:right;" name="PINV_AKUMNEXT" id="PINV_AKUMNEXT" value="<?php echo $PINV_AKUMNEXT; ?>">
	                                </div>
	                            </div>
	                            
					            <!-- PINV_TOTVALPPHP, PINV_TOTVALPPHP1 -->
	                        	<div class="col-sm-3">
	                                <div class="form-group">
	                                	<label for="inputName">PPh (%)</label>
				                        <input type="text" class="form-control" style="text-align:right;" name="PINV_TOTVALPPHP1" id="PINV_TOTVALPPHP1" value="<?php echo number_format($PINV_TOTVALPPhP, 4); ?>" onBlur="countPPHPer(this.value)" onKeyPress="return isIntOnlyNew(event);" >
				                        <input type="hidden" class="form-control" style="max-width:100px; text-align:right;" name="PINV_TOTVALPPHP" id="PINV_TOTVALPPHP" value="<?php echo $PINV_TOTVALPPhP; ?>" onKeyPress="return isIntOnlyNew(event);" >
	                                </div>
	                            </div>
	                            
					            <!-- PINV_TOTVALPPH, PINV_TOTVALPPH1 -->
	                        	<div class="col-sm-4">
	                                <div class="form-group">
	                                	<label for="inputName">Nilai PPh</label>
				                        <input type="text" class="form-control" style="text-align:right;" name="PINV_TOTVALPPH1" id="PINV_TOTVALPPH1" value="<?php echo number_format($PINV_TOTVALPPh, $decFormat); ?>" onBlur="countPPHVal(this.value)" onKeyPress="return isIntOnlyNew(event);">
				                        <input type="hidden" class="form-control" style="max-width:155px; text-align:right;" name="PINV_TOTVALPPH" id="PINV_TOTVALPPH" value="<?php echo $PINV_TOTVALPPh; ?>" title="Payment Before Retention">
	                                </div>
	                            </div>
	                            
					            <!-- PINV_RECEIVETOT, PINV_RECEIVETOT1 -->
	                        	<div class="col-sm-5">
	                                <div class="form-group">
	                                	<label for="inputName">Grand Total</label>
		                          		<input type="text" class="form-control" style="text-align:right;" name="PINV_RECEIVETOT1" id="PINV_RECEIVETOT1" value="<?php echo number_format($GPINV_TOTVAL, $decFormat); ?>" onBlur="chgGTOTAL(this.value)">
		                        		<input type="hidden" class="form-control" style="max-width:155px; text-align:right;" name="PINV_RECEIVETOT" id="PINV_RECEIVETOT" value="<?php echo $GPINV_TOTVAL; ?>" title="Payment Before Retention">
	                                </div>
	                            </div>
	                            
					            <!-- STATUS -->
	                        	<div class="col-sm-6">
	                                <div class="form-group">
	                                	<label for="inputName">Status</label>
		                          		<?php
											// START : FOR ALL APPROVAL FUNCTION
		                          				if($task == 'add' AND $ISCREATE == 1)
												{
													?>
														<select name="PINV_STAT" id="PINV_STAT" class="form-control select2">
															<option value="1"<?php if($PINV_STAT == 1) { ?> selected <?php } ?>>New</option>
															<option value="2"<?php if($PINV_STAT == 2) { ?> selected <?php } ?>>Confirm</option>
														</select>
													<?php
                                                }
                                                else
                                                {
					                          		if($ISCREATE == 1 && $ISAPPROVE == 1)
						                            {
													?>
														<select name="PINV_STAT" id="PINV_STAT" class="form-control select2">
															<option value="1"<?php if($PINV_STAT == 1) { ?> selected <?php } ?>>New</option>
															<option value="2"<?php if($PINV_STAT == 2) { ?> selected <?php } ?>>Confirm</option>
						                                    <option value="3"<?php if($PINV_STAT == 3) { ?> selected <?php } ?>>Approved</option>
						                                    <option value="4"<?php if($PINV_STAT == 4) { ?> selected <?php } ?>>Revised</option>
						                                    <option value="5"<?php if($PINV_STAT == 5) { ?> selected <?php } ?>>Rejected</option>
						                                    <option value="6"<?php if($PINV_STAT == 6) { ?> selected <?php } ?> disabled>Closed</option>
						                                    <option value="7"<?php if($PINV_STAT == 7) { ?> selected <?php } ?> disabled>Awaiting</option>
														</select>
													<?php
						                            }
													elseif($ISCREATE == 1)
													{
														// START : FOR ALL APPROVAL FUNCTION
															if($disableAll == 0)
															{
																$disButton	= 0;
																$sqlCAPPHE	= "tbl_approve_hist WHERE AH_CODE = '$PINV_CODE' AND AH_APPROVER = '$DefEmp_ID'";
																$resCAPPHE	= $this->db->count_all($sqlCAPPHE);
																if($resCAPPHE > 0)
																	$disButton	= 1;
																?>
																	<select name="PINV_STAT" id="PINV_STAT" class="form-control select2" <?php if($disButton == 1) { ?> disabled <?php } ?> >
																		<option value="0"> --- </option>
			                                                            <option value="1"<?php if($PINV_STAT == 1) { ?> selected <?php } ?>>New</option>
			                                                            <option value="2"<?php if($PINV_STAT == 2) { ?> selected <?php } ?>>Confirm</option>
			                                                            <option value="3"<?php if($PINV_STAT == 3) { ?> selected <?php } ?>>Approved</option>
			                                                            <option value="4"<?php if($PINV_STAT == 4) { ?> selected <?php } ?>>Revised</option>
			                                                            <option value="5"<?php if($PINV_STAT == 5) { ?> selected <?php } ?>>Rejected</option>
			                                                            <option value="6"<?php if($PINV_STAT == 6) { ?> selected <?php } ?>>Closed</option>
			                                                            <option value="7"<?php if($PINV_STAT == 7) { ?> selected <?php } ?> disabled>Awaiting</option>
																	</select>
																<?php
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
													elseif($ISAPPROVE == 1)
													{
														if($disableAll == 0)
														{
															$disButton	= 0;
															$sqlCAPPHE	= "tbl_approve_hist WHERE AH_CODE = '$PINV_CODE' AND AH_APPROVER = '$DefEmp_ID'";
															$resCAPPHE	= $this->db->count_all($sqlCAPPHE);
															if($resCAPPHE > 0)
																$disButton	= 1;
															?>
																<select name="PINV_STAT" id="PINV_STAT" class="form-control select2" <?php if($disButton == 1) { ?> disabled <?php } ?> >
																	<option value="0"> --- </option>
		                                                            <option value="1"<?php if($PINV_STAT == 1) { ?> selected <?php } ?> disabled>New</option>
		                                                            <option value="2"<?php if($PINV_STAT == 2) { ?> selected <?php } ?> disabled>Confirm</option>
		                                                            <option value="3"<?php if($PINV_STAT == 3) { ?> selected <?php } ?>>Approved</option>
		                                                            <option value="4"<?php if($PINV_STAT == 4) { ?> selected <?php } ?>>Revised</option>
		                                                            <option value="5"<?php if($PINV_STAT == 5) { ?> selected <?php } ?>>Rejected</option>
		                                                            <option value="6"<?php if($PINV_STAT == 6) { ?> selected <?php } ?>>Closed</option>
		                                                            <option value="7"<?php if($PINV_STAT == 7) { ?> selected <?php } ?> disabled>Awaiting</option>
																</select>
															<?php
														}
														else
														{
															?>
																<a href="" class="btn btn-danger btn-xs">
																	Step approval not set.
																</a>
															<?php
														}
													}
                                                }
											// END : FOR ALL APPROVAL FUNCTION
					                    ?>
					                    <input type="hidden" name="isEdit" id="isEdit" value="<?php echo $isEdit; ?>">
	                                </div>
	                            </div>

	                          	<div class="col-sm-6">
	                                <div class="form-group">
	                                	<label for="inputName">&nbsp;</label>
	                                	<div>
			                          		<?php
												if($disableAll == 0)
												{
													if(($PINV_STAT == 2 || $PINV_STAT == 7) && $canApprove == 1)
													{
														?>
															<button class="btn btn-primary" >
															<i class="fa fa-save"></i></button>&nbsp;
														<?php
													}
													elseif($PINV_STAT == 1 || $PINV_STAT == 4)
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
							</div>
						</div>
					</div>
				</form>
		    </div>

	    	<!-- ============ START MODAL =============== -->
		    	<style type="text/css">
		    		.modal-dialog{
					    position: relative;
					    display: table; /* This is important */ 
					    overflow-y: auto;    
					    overflow-x: auto;
					    width: auto;
					    min-width: 600px;   
					}

					.datatable td span{
					    width: 80%;
					    display: block;
					    overflow: hidden;
					}
		    	</style>
		    	<?php
					$Active1		= "active";
					$Active2		= "";
					$Active1Cls		= "class='active'";
					$Active2Cls		= "";
		    	?>
		        <div class="modal fade" id="mdl_addMC" name='mdl_addMC' tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		            <div class="modal-dialog">
			            <div class="modal-content">
			                <div class="modal-body">
								<div class="row">
							    	<div class="col-md-12">
							            <div class="box-header">
							              	<ul class="nav nav-tabs">
							                    <li id="li1" <?php echo $Active1Cls; ?>>
							                    	<a href="#" data-toggle="tab">Daftar MC</a>
							                    </li>
							                </ul>
							            </div>
							            <div class="box-body">
	                                        <div class="form-group">
	                                        	<form method="post" name="frmSearch1" id="frmSearch1" action="">
		                                            <table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
		                                                <thead>
		                                                    <tr>
		                                                        <th width="2%" style="text-align:center; vertical-align: middle;" nowrap>&nbsp;</th>
							                                    <th width="10%" nowrap style="text-align:center; vertical-align: middle;" nowrap><?php echo $MCNumber ?></th>
							                                    <th width="10%" nowrap style="text-align:center; vertical-align: middle;" nowrap><?php echo $MCDate ?></th>
							                                    <th width="5%" nowrap style="text-align:center; vertical-align: middle;" nowrap><?php echo $PrestationVal ?></th>
							                                    <th width="15%" nowrap style="text-align:center; vertical-align: middle;">Total<br>
							                                    (inc. PPn - PPh)</th>
							                                    <th width="58%" style="text-align:center; vertical-align: middle;"><?php echo $Notes ?></th>
		                                                  </tr>
		                                                </thead>
		                                                <tbody>
		                                                </tbody>
		                                            </table>
                                  					<input type="hidden" name="rowCheck" id="rowCheck" value="0">
                                  					<button class="btn btn-warning" type="button" id="idRefresh" title="Refresh" >
                                                		<i class="glyphicon glyphicon-refresh"></i>
                                                	</button>
                                                	<button class="btn btn-primary" type="button" id="btnDetail1" name="btnDetail1">
                                                		<i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?>
                                                	</button>
                                                	<button type="button" id="idClose" class="btn btn-danger" data-dismiss="modal">
                                                		<i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>
                                                	</button>
	                                            </form>
	                                      	</div>
		                                </div>
		                            </div>
		                        </div>
			                </div>
				        </div>
				    </div>
				</div>

				<script type="text/javascript">
					var selectedRows = 0;
					function pickThis1(thisobj) 
					{
						var favorite = [];
						$.each($("input[name='chk1']:checked"), function() {
					      	favorite.push($(this).val());
					    });
					    $("#rowCheck").val(favorite.length);
					}

					$(document).ready(function()
					{
				    	$('#example1').DataTable(
				    	{
					        "processing": true,
					        "serverSide": true,
							//"scrollX": false,
							"autoWidth": true,
							"filter": true,
					        "ajax": "<?php echo site_url('c_project/c_prj180c2dinv/get_AllDataMC/?id='.$PRJCODE)?>",
					        "type": "POST",
							//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
							"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
							"columnDefs": [	{ targets: [0,1,2], className: 'dt-body-center' },
											{ targets: [3,4], className: 'dt-body-right' }
										  ],
							"language": {
					            "infoFiltered":"",
					            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
					        },
						});

					   	$("#btnDetail1").click(function()
					    {
							var totChck 	= $("#rowCheck").val();
							if(totChck == 0)
							{
								swal('<?php echo $alert1; ?>',
								{
									icon: "warning",
								})
								.then(function()
					            {
					                swal.close();
					            });
								return false;
							}

						    $.each($("input[name='chk1']:checked"), function()
						    {
						      	getDetail($(this).val());
						      	////****console.log($(this).val());
						    });

						    $('#mdl_addMC').on('hidden.bs.modal', function () {
							    $(this)
								    .find("input,textarea,select")
									    //.val('')
									    .end()
								    .find("input[type=checkbox], input[type=radio]")
								       .prop("checked", "")
								       .end();
							});
                        	document.getElementById("idClose").click()
					    });
					    
					   	$("#idRefresh").click(function()
					    {
							$('#example1').DataTable().ajax.reload();
					    });
					});
				</script>
	    	<!-- ============ END MODAL =============== -->
        	<?php
				$act_lnk = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        		if($DefEmp_ID == 'D15040004221')
                	echo "<font size='1'><i>$act_lnk</i></font>";
            ?>
		</section>
	</body>
</html>
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
</script>

<?php
	$secURL	= site_url('c_project/c_mc180c2c/getOWNC/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
?>
<script>
	//function getDetail(strItem, PINV_MMC, MC_TOTVAL1, MC_REF, PINV_PAYBEF)
	function getDetail(strItem)
	{
		arrItem 		= strItem.split('|');		
		
		pageFrom 		= arrItem[0];

		var decFormat	= document.getElementById('decFormat').value;
		if(pageFrom == 'MC')
		{
			MC_CODE 		= arrItem[1];
			MC_MANNO 		= arrItem[2];
			MC_DATE 		= arrItem[3];
			MC_ENDDATE 		= arrItem[4];
			MC_RETVAL 		= arrItem[5];
			MC_PROG 		= arrItem[6];
			MC_PROGVAL		= arrItem[7];
			MC_PROGCUR 		= arrItem[8];
			MC_PROGCURVAL 	= arrItem[9];
			MC_VALADD 		= arrItem[10];
			MC_MATVAL 		= arrItem[11];
			MC_DPPER 		= arrItem[12];
			MC_DPVAL		= arrItem[13];
			MC_DPBACK 		= arrItem[14];
			MC_DPBACKCUR 	= arrItem[15];
			MC_RETCUTP 		= arrItem[16];
			MC_RETCUT 		= arrItem[17];
			MC_RETCUTCUR	= arrItem[18];
			MC_PROGAPP		= arrItem[19];
			MC_PROGAPPVAL	= arrItem[20];
			MC_AKUMNEXT 	= arrItem[21];
			MC_VALBEF 		= arrItem[22];
			MC_TOTVAL 		= arrItem[23];
			MC_TOTVAL_PPn 	= arrItem[24];
			MC_TOTVAL_PPh 	= arrItem[25];
			GMC_TOTVAL 		= arrItem[26];
			MC_NOTES 		= arrItem[27];
			MC_OWNER 		= arrItem[28];
			OWN_INST		= arrItem[29];

			//****console.log('a')
			document.getElementById('PINV_SOURCE1').value 			= MC_MANNO;
			document.getElementById('PINV_SOURCE').value 			= MC_CODE;
			document.getElementById('PINV_SOURCE_MN').value 		= MC_MANNO;
			//PRJCOST	= document.getElementById('PRJCOST_TOTAL').value;
			PRJCOST	= parseFloat(document.getElementById('PRJCOST').value);
			
			document.getElementById('PINV_DPPER1').disabled			= true;
				
			//****console.log('b')	
			// OK - Nilai Uang Muka
				/*document.getElementById('PINV_DPPER1').value 		= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_DPPER)), 4));
				document.getElementById('PINV_DPPER').value 		= MC_DPPER;
				document.getElementById('PINV_DPVAL1').value 		= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_DPVAL)), 2));
				document.getElementById('PINV_DPVAL').value 		= MC_DPVAL;
				MC_DPVALPPn				= parseFloat(Math.round(0.11 * MC_DPVAL));
				document.getElementById('PINV_DPVALPPn1').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_DPVALPPn)),2));
				document.getElementById('PINV_DPVALPPn').value 		= MC_DPVALPPn;*/
			
			// OK - Pekerjaan Tambahan
				MC_VALADD											= parseFloat(MC_VALADD);
				MC_VALADDPPn										= 0;
				document.getElementById('PINV_VALADD').value 		= MC_VALADD;

			//****console.log('c')
			// OK - Nilai Prestasi / Progress
				document.getElementById('PINV_PROG1').value 		= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_PROG)),4));
				document.getElementById('PINV_PROG').value 			= parseFloat(MC_PROG);

				//****console.log('d')
				MC_PROGVAL											= parseFloat(MC_PROGVAL);
				document.getElementById('PINV_PROGVAL1').value 		= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_PROGVAL)),2));
				document.getElementById('PINV_PROGVAL').value 		= parseFloat(MC_PROGVAL);
				
				//****console.log('e')
				document.getElementById('PINV_PROGCURVAL').value 	= parseFloat(MC_PROGCURVAL);
				document.getElementById('PINV_PROGCURVALX').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_PROGCURVAL)),2));
			
			// OK - Nilai Pemotongan Uang Muka (PINV_PROG * Nilai DP)
				PINV_DPBACK				= parseFloat(MC_DPBACK);
				MC_DPBACKCUR			= parseFloat(MC_DPBACKCUR);
				//****console.log('f')
				document.getElementById('PINV_DPBACK').value 		= PINV_DPBACK;
				document.getElementById('PINV_DPBACKX').value 		= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(PINV_DPBACK)),2));
				document.getElementById('PINV_DPBACKCUR').value 	= parseFloat(MC_DPBACKCUR);
				document.getElementById('PINV_DPBACKCUR1').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_DPBACKCUR)),2));
			
			//****console.log('g')
			// OK - Nilai Retensi 5% Tanpa PPn
				document.getElementById('PINV_RETCUTP').value		= MC_RETCUTP;
				document.getElementById('PINV_RETCUTPX').value		= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_RETCUTP)), 4));
				document.getElementById('PINV_RETCUT').value 		= MC_RETCUT;
				document.getElementById('PINV_RETCUTX').value 		= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_RETCUT)), 2));
				document.getElementById('PINV_RETCUTCUR').value 	= MC_RETCUTCUR;
				document.getElementById('PINV_RETCUTCUR1').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_RETCUTCUR)), 2));
			
			//****console.log('h')
			// OK - Nilai Prestasi Setelah dikurangi retensi dan uang Muka
				document.getElementById('PINV_PAYBEFRET').value 	= parseFloat(MC_AKUMNEXT);
				document.getElementById('PINV_AKUMNEXT').value 		= parseFloat(MC_AKUMNEXT);
			
			//****console.log('i')
			// OK - Nilai Pembayaran Sebelumnya
				document.getElementById('PINV_VALBEF').value 		= parseFloat(MC_VALBEF);
				
			// OK - Nilai Prestasi Saat ini
				PINV_PAYAKUM			= parseFloat(MC_TOTVAL);
				document.getElementById('PINV_PAYAKUM').value 		= parseFloat(MC_TOTVAL);
				document.getElementById('PINV_TOTVALPPn').value 	= parseFloat(MC_TOTVAL_PPn);

			//****console.log('j')
			// Nilai yang diajukan
				PINV_TOTVALTOT			= parseFloat(PINV_PAYAKUM);
				document.getElementById('PINV_PAYAKUMX').value 		= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(PINV_TOTVALTOT)),2));
				
			//****console.log('k')
			// Nilai PPn Prestasi Saat ini - not use
				PINV_PAYAKUMPPn			= Math.round(parseFloat(0.11 * PINV_PAYAKUM));
				document.getElementById('PINV_PAYAKUMPPn').value 	= PINV_PAYAKUMPPn;
				document.getElementById('PINV_PAYAKUMPPnX').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(PINV_PAYAKUMPPn)),2));
				//swal(PINV_PAYAKUMPPn)
				
			//****console.log('l')
			// PPh
				if(OWN_INST == 'S')
				{
					PPHPerc 			= parseFloat(MC_TOTVAL_PPh / MC_TOTVAL * 100);
				}
				else
				{
					MC_TOTVAL_		= parseFloat(MC_TOTVAL - MC_TOTVAL_PPh);
					PPHPerc			= parseFloat(MC_TOTVAL_PPh / MC_TOTVAL_ * 100);
				}

				//****console.log('m')
				document.getElementById('PINV_TOTVAL').value 		= PINV_TOTVALTOT;
				document.getElementById('PINV_TOTVALPPH').value 	= Math.round(MC_TOTVAL_PPh);
				document.getElementById('PINV_TOTVALPPHP').value 	= PPHPerc;
				document.getElementById('PINV_TOTVALPPHP1').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(PPHPerc)),4));
				document.getElementById('PINV_TOTVALPPH1').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_TOTVAL_PPh)),2));
			
			//****console.log('n')
			// Nilai yang diterima NKE
				if(OWN_INST == 'S')
					PINV_RECEIVETOT	= parseFloat(MC_TOTVAL)+parseFloat(MC_TOTVAL_PPn)-parseFloat(MC_TOTVAL_PPh);
				else
					PINV_RECEIVETOT	= parseFloat(MC_TOTVAL)+parseFloat(MC_TOTVAL_PPn)-parseFloat(MC_TOTVAL_PPh);
				
				document.getElementById('PINV_RECEIVETOT').value 	= PINV_RECEIVETOT;
				document.getElementById('PINV_RECEIVETOT1').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(PINV_RECEIVETOT)),2));
				
			//****console.log('o')
			// Auto select Owner
				//document.getElementById('PINV_OWNER').value 		= MC_OWNER;
				$('#PINV_OWNER').val(MC_OWNER).trigger('change');
				document.getElementById('OWN_INST').value 			= OWN_INST;
			//****console.log('p')
		}
		else if(pageFrom == 'MCG')
		{
			pageFrom 		= arrItem[0];		// 0
			MC_CODE 		= arrItem[1];		// 1
			MC_MANNO 		= arrItem[2];		// 2
			MC_DATE 		= arrItem[3];		// 3
			MC_ENDDATE 		= arrItem[4];		// 4
			MC_RETVAL 		= arrItem[5];		// 5
			MC_PROG 		= arrItem[6];		// 6
			MC_PROGVAL		= arrItem[7];		// 7
			MC_VALADD 		= arrItem[8];		// 8
			MC_MATVAL 		= arrItem[9];		// 9
			MC_DPPER 		= arrItem[10];		// 10
			MC_DPVAL		= arrItem[11];		// 11
			MC_DPBACK 		= arrItem[12];		// 12
			MC_RETCUT 		= arrItem[13];		// 13
			MC_PROGAPP		= arrItem[14];		// 14
			MC_PROGAPPVAL	= arrItem[15];		// 15
			MC_AKUMNEXT 	= arrItem[16];		// 16
			MC_VALBEF 		= arrItem[17];		// 17
			MC_TOTVAL 		= arrItem[18];		// 18
			MC_NOTES 		= arrItem[19];		// 19
			MC_OWNER 		= arrItem[20];		// 19
			MC_NONPPN 		= arrItem[21];		// 21
			MC_VALPPN 		= arrItem[22];		// 22
			MC_PPHVAL 		= arrItem[23];		// 21
			MCH_PROGCUR		= arrItem[24];		// 21
			MCH_PROGCURVAL	= arrItem[25];		// 21
			MCH_DPBACKCUR 	= arrItem[26];		// 21
			MCH_RETCUTP 	= arrItem[27];		// 21
			MCH_RETCUTCUR	= arrItem[28];		// 21
			OWN_INST		= arrItem[29];		// 21
			
			MC_WITHPPN		= parseFloat(MC_NONPPN) + parseFloat(MC_VALPPN);
			
			document.getElementById('PINV_SOURCE1').value 			= MC_CODE;
			document.getElementById('MC_REF').value 				= MC_REF;	
			//PRJCOST	= document.getElementById('PRJCOST_TOTAL').value;
			PRJCOST	= parseFloat(document.getElementById('PRJCOST').value);
			/*if(PINV_MMC > 1)
			{
				MC_PROGVAL	= parseFloat(MC_PROGVAL);
				MC_PROG		= MC_PROGVAL / PRJCOST * 100;
			}*/
			
			if(PINV_MMC > 1)
			{
				pageFrom 		= arrItem[0];		// 0
				MC_CODE 		= arrItem[1];		// 1
				MC_MANNO 		= arrItem[2];		// 2
				MC_DATE 		= arrItem[3];		// 3
				MC_ENDDATE 		= arrItem[4];		// 4
				MC_RETVAL 		= arrItem[5];		// 5
				MC_PROG 		= arrItem[6];		// 6
				MC_PROGVAL		= arrItem[7];		// 7
				MC_VALADD 		= 0;				// 8
				MC_MATVAL 		= 0;				// 9
				MC_DPPER 		= arrItem[10];		// 10
				MC_DPVAL		= arrItem[11];		// 11
				MC_DPBACK 		= arrItem[12];		// 12
				MC_RETCUT 		= arrItem[13];		// 13
				MC_PROGAPP		= arrItem[14];		// 14
				MC_PROGAPPVAL	= arrItem[15];		// 15
				MC_AKUMNEXT 	= arrItem[16];		// 16
				MC_VALBEF 		= arrItem[17];		// 17
				MC_TOTVAL 		= arrItem[18];		// 18
				MC_NOTES 		= arrItem[19];		// 19
				MC_OWNER 		= arrItem[20];		// 19
				MC_NONPPN 		= arrItem[21];		// 21
				MC_VALPPN 		= arrItem[22];		// 22
				MC_PPHVAL 		= arrItem[23];		// 21
				MCH_PROGCUR		= arrItem[24];		// 21
				MCH_PROGCURVAL	= arrItem[25];		// 21
				MCH_DPBACKCUR 	= arrItem[26];		// 21
				MCH_RETCUTP 	= arrItem[27];		// 21
				MCH_RETCUTCUR	= arrItem[28];		// 21
				MC_WITHPPN		= parseFloat(MC_NONPPN + MC_VALPPN);
			}
			
			if(MC_PPHVAL > 0)
			{
				var PPH_PERC	= parseFloat(MC_PPHVAL / MC_NONPPN * 100);
				document.getElementById('PINV_TOTVALPPH').value 	= MC_PPHVAL;
				document.getElementById('PINV_TOTVALPPHP').value 	= PPH_PERC;
				document.getElementById('PINV_TOTVALPPHP1').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(PPH_PERC)),4));
				document.getElementById('PINV_TOTVALPPH1').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_PPHVAL)),4));
			}
			else
			{
				PPH_PERC	= 0;
				document.getElementById('PINV_TOTVALPPH').value 	= MC_PPHVAL;
				document.getElementById('PINV_TOTVALPPHP').value 	= PPH_PERC;
				document.getElementById('PINV_TOTVALPPHP1').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_PPHP)),4));
				document.getElementById('PINV_TOTVALPPH1').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_PPHVAL)),4));
			}
			// OK - Nilai Prestasi
				document.getElementById('PINV_PROG1').value 		= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_PROG)),4));
				document.getElementById('PINV_PROG').value 			= MC_PROG;
				document.getElementById('PINV_PROGCUR').value 		= MCH_PROGCUR;
				MC_PROGVAL			= parseFloat(MC_PROGVAL);
				MC_PROGVALPPn		= parseFloat(Math.round(0.11 * MC_PROGVAL));			// PPn Nilai Prestasi
				document.getElementById('PINV_PROGVAL1').value 		= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_PROGVAL)),2));
				document.getElementById('PINV_PROGVAL').value 		= MC_PROGVAL;
				document.getElementById('PINV_PROGCURVAL').value 	= MCH_PROGCURVAL;
				
			// OK - Pekerjaan Tambahan
				MC_VALADD			= parseFloat(MC_VALADD);
				MC_VALADDPPn		= parseFloat(Math.round(0.11 * MC_VALADD));			// PPn Nilai Pekerjaan Tambah
				document.getElementById('PINV_VALADD').value 		= MC_VALADD;
				//swal('MC_VALADD = '+MC_VALADD)
			
			// OK - Nilai Termasuk Pekerjaan Tambah
				PINV_TOTPROGRESS		= parseFloat(MC_PROGVAL) + parseFloat(MC_PROGVALPPn) + parseFloat(MC_VALADD) + parseFloat(MC_VALADDPPn);
				PINV_TOTPROGRESSPPn		= parseFloat(Math.round(0.11 * PINV_TOTPROGRESS));	// PPn Nilai Termasuk Pekerjaan Tambah
				document.getElementById('PINV_TOTPROGRESS').value 		= PINV_TOTPROGRESS;
				//swal('PINV_TOTPROGRESS = '+PINV_TOTPROGRESS)
			
			// OK - Nilai Retensi 5% Tanpa PPn
				RETCUTP_DEF	= parseFloat(MC_RETCUT / MC_PROGVAL * 100);
				document.getElementById('PINV_RETCUTP').value		= RETCUTP_DEF;
				document.getElementById('PINV_RETCUTPX').value		= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(RETCUTP_DEF)), 4));
				//PINV_RETCUT			= parseFloat(RETCUTP_DEF * PINV_TOTPROGRESS);
				document.getElementById('PINV_RETCUT').value 		= MC_RETCUT;
				document.getElementById('PINV_RETCUTCUR').value 	= MCH_RETCUTCUR;
				document.getElementById('PINV_RETCUTX').value 		= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_RETCUT)), 2));
				//swal('MC_RETCUT = '+MC_RETCUT)
					
			// OK - Nilai Uang Muka
				document.getElementById('PINV_DPPER1').value 		= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_DPPER)), 4));
				document.getElementById('PINV_DPPER').value 		= MC_DPPER;
				document.getElementById('PINV_DPVAL1').value 		= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_DPVAL)), 2));
				document.getElementById('PINV_DPVAL').value 		= MC_DPVAL;
				document.getElementById('PINV_DPBACKCUR').value 	= MCH_DPBACKCUR;
				MC_DPVALPPn			= parseFloat(Math.round(0.11 * MC_DPVAL));
				document.getElementById('PINV_DPVALPPn1').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_DPVALPPn)),2));
				document.getElementById('PINV_DPVALPPn').value 		= MC_DPVALPPn;
			
			// OK - Nilai Pemotongan Uang Muka (PINV_PROG * Nilai DP)
				//PINV_DPBACK			= parseFloat(Math.round(MC_PROG * MC_DPVAL / 100));
				PINV_DPBACK			= parseFloat(MC_DPBACK);
				document.getElementById('PINV_DPBACK').value 		= PINV_DPBACK;
				//swal(PINV_DPBACK)
			
			// OK - Nilai Prestasi Setelah dikurangi retensi dan uang Muka
				//PINV_TOTPROGRESS2		= parseFloat(PINV_TOTPROGRESS) - parseFloat(MC_VALBEF) -  parseFloat(PINV_DPBACK) - parseFloat(PINV_RETCUT);
				PINV_TOTPROGRESS2		= parseFloat(MC_AKUMNEXT);
				document.getElementById('PINV_PAYBEFRET').value 	= parseFloat(MC_AKUMNEXT);
				document.getElementById('PINV_AKUMNEXT').value 		= parseFloat(MC_AKUMNEXT);
				//swal(MC_AKUMNEXT)
			
			// OK - Nilai Pembayaran Sebelumnya
				MC_VALBEF				= parseFloat(MC_VALBEF);
				document.getElementById('PINV_VALBEF').value 		= MC_VALBEF;
				//swal(MC_VALBEF)
				
			// OK - Nilai Prestasi Saat ini
				//PINV_PAYAKUM			= parseFloat(PINV_TOTPROGRESS2) - parseFloat(MC_VALBEF);
				PINV_PAYAKUM			= parseFloat(PINV_TOTPROGRESS2);
				document.getElementById('PINV_PAYAKUM').value 		= PINV_PAYAKUM;
				//swal(PINV_PAYAKUM)
				
			// Nilai PPn Prestasi Saat ini - not use
				PINV_PAYAKUMPPn			= Math.round(parseFloat(0.11 * MC_TOTVAL));
				document.getElementById('PINV_PAYAKUMPPn').value 	= PINV_PAYAKUMPPn;
				//swal(PINV_PAYAKUMPPn)
				
			// Nilai PPh				
				PPhPercent			= eval(document.getElementById('PINV_TOTVALPPHP1')).value.split(",").join("");
				//PINV_TOTVALPPH			= parseFloat(PPhPercent * PINV_PAYAKUM);
				PINV_TOTVALPPH			= parseFloat(MC_PPHVAL);
				document.getElementById('PINV_TOTVALPPH').value 	= Math.round(PINV_TOTVALPPH);
				document.getElementById('PINV_TOTVALPPH1').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(PINV_TOTVALPPH)),2));
				
			// Nilai yang diajukan
				PINV_TOTVALTOT			= parseFloat(MC_WITHPPN);
				document.getElementById('PINV_PAYAKUMX').value 		= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(PINV_TOTVALTOT)),2));
				document.getElementById('PINV_TOTVAL').value 		= MC_NONPPN;
				document.getElementById('PINV_TOTVALPPn').value 	= MC_VALPPN;
			
			// Nilai yang diterima
				PINV_RECEIVETOT			= parseFloat(MC_WITHPPN - MC_PPHVAL);	// Nilai yang akan diterima
				document.getElementById('PINV_RECEIVETOT').value 	= PINV_RECEIVETOT;
				document.getElementById('PINV_RECEIVETOT1').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(PINV_RECEIVETOT)),2));
				
			// Auto select Owner
				document.getElementById('PINV_OWNER').value 		= MC_OWNER;
				document.getElementById('OWN_INST').value 			= OWN_INST;
		}
		if(pageFrom == 'SIC')
		{
			SIC_CODE 		= arrItem[1];
			SIC_MANNO 		= arrItem[2];
			SIC_DATE 		= arrItem[3];
			SIC_APPDATE		= arrItem[4];
			SIC_PROG		= arrItem[5];
			SIC_PROGVAL		= arrItem[6];
			SIC_TOTVAL		= arrItem[7];
			SIC_NOTES 		= arrItem[8];
			SIC_STAT 		= arrItem[9];
			SIC_APPSTAT		= arrItem[10];
			
			document.getElementById('PINV_SOURCE2').value 		= SIC_CODE;
			
			// OK - Nilai Prestasi
				document.getElementById('PINV_PROG1').value 		= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(SIC_PROG)),4));
				document.getElementById('PINV_PROG').value 			= SIC_PROG;
				document.getElementById('PINV_PROGCUR').value 		= SIC_PROG;
				SIC_PROGVAL			= parseFloat(SIC_PROGVAL);
				SIC_PROGVALPPn		= parseFloat(Math.round(0.11 * SIC_PROGVAL));			// PPn Nilai Prestasi
				document.getElementById('PINV_PROGVAL1').value 		= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(SIC_PROGVAL)),2));
				document.getElementById('PINV_PROGVAL').value 		= SIC_PROGVAL;
				document.getElementById('PINV_PROGCURVAL').value 	= SIC_PROGVAL;
			
			// OK - Pekerjaan Tambahan
				MC_VALADD			= parseFloat(0);
				MC_VALADDPPn		= parseFloat(Math.round(0.11 * MC_VALADD));			// PPn Nilai Pekerjaan Tambah
				document.getElementById('PINV_VALADD').value 		= MC_VALADD;
				//swal('MC_VALADD = '+MC_VALADD)
				
			// OK - Nilai Termasuk Pekerjaan Tambah
				//PINV_TOTPROGRESS		= parseFloat(SIC_PROGVAL) + parseFloat(SIC_PROGVALPPn) + parseFloat(MC_VALADD) + parseFloat(MC_VALADDPPn);
				PINV_TOTPROGRESS		= parseFloat(SIC_PROGVAL);
				PINV_TOTPROGRESSPPn		= parseFloat(Math.round(0.11 * PINV_TOTPROGRESS));	// PPn Nilai Termasuk Pekerjaan Tambah
				document.getElementById('PINV_TOTPROGRESS').value 		= PINV_TOTPROGRESS;
				
			// OK - Nilai Retensi 5% Tanpa PPn
				RETCUTP_DEF	= 5;
				document.getElementById('PINV_RETCUTP').value		= RETCUTP_DEF;
				document.getElementById('PINV_RETCUTPX').value		= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(RETCUTP_DEF)), 4));
				PINV_RETCUT			= Math.round(RETCUTP_DEF * PINV_TOTPROGRESS / 100);
				document.getElementById('PINV_RETCUT').value 		= PINV_RETCUT;
				document.getElementById('PINV_RETCUTCUR').value 	= PINV_RETCUT;
				document.getElementById('PINV_RETCUTX').value 		= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(PINV_RETCUT)), 2));				
						
			// OK - Nilai Uang Muka
				MC_DPPER			= 0;		// Di nol kan dulu
				MC_DPVAL			= 0;		// Di nol kan dulu
				document.getElementById('PINV_DPPER1').value 		= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_DPPER)), 4));
				document.getElementById('PINV_DPPER').value 		= MC_DPPER;
				document.getElementById('PINV_DPVAL1').value 		= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_DPVAL)), 2));
				document.getElementById('PINV_DPVAL').value 		= MC_DPVAL;
				document.getElementById('PINV_DPBACKCUR').value 	= 0;
				MC_DPVALPPn			= parseFloat(Math.round(0.11 * MC_DPVAL));
				document.getElementById('PINV_DPVALPPn1').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_DPVALPPn)),2));
				document.getElementById('PINV_DPVALPPn').value 		= MC_DPVALPPn;
				
			// OK - Nilai Pemotongan Uang Muka (PINV_PROG * Nilai DP)
				//PINV_DPBACK			= parseFloat(Math.round(MC_PROG * MC_DPVAL / 100));
				PINV_DPBACK			= parseFloat(SIC_PROG) * parseFloat(MC_DPVAL) / 100;
				document.getElementById('PINV_DPBACK').value 		= PINV_DPBACK;
			
			// OK - Nilai Prestasi Setelah dikurangi retensi dan uang Muka
				PINV_TOTPROGRESS2		= parseFloat(SIC_TOTVAL - PINV_RETCUT + MC_DPVAL - PINV_DPBACK);
				document.getElementById('PINV_PAYBEFRET').value 	= parseFloat(PINV_TOTPROGRESS2);
				document.getElementById('PINV_AKUMNEXT').value 		= parseFloat(PINV_TOTPROGRESS2);
				
			// OK - Nilai Pembayaran Sebelumnya
				MC_VALBEF				= parseFloat(0);
				document.getElementById('PINV_VALBEF').value 		= MC_VALBEF;
				//swal(MC_VALBEF)
			
			// OK - Nilai Prestasi Saat ini
				//PINV_PAYAKUM			= parseFloat(PINV_TOTPROGRESS2) - parseFloat(MC_VALBEF);
				PINV_PAYAKUM			= parseFloat(PINV_TOTPROGRESS2);
				//swal(PINV_PAYAKUM)
				document.getElementById('PINV_PAYAKUM').value 		= PINV_PAYAKUM;
			
			// Nilai PPn Prestasi Saat ini - not use
				PINV_PAYAKUMPPn			= Math.round(parseFloat(0.11 * PINV_PAYAKUM));
				document.getElementById('PINV_PAYAKUMPPn').value 	= PINV_PAYAKUMPPn;
				//swal(PINV_PAYAKUMPPn)
			
			// Nilai PPh				
				PPhPercent			= eval(document.getElementById('PINV_TOTVALPPHP1')).value.split(",").join("");
				PINV_TOTVALPPH			= parseFloat(PPhPercent * PINV_PAYAKUM);
				document.getElementById('PINV_TOTVALPPH').value 	= Math.round(PINV_TOTVALPPH);
				document.getElementById('PINV_TOTVALPPH1').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(PINV_TOTVALPPH)),2));
			
			// Nilai yang diajukan
				PINV_TOTVALTOT			= parseFloat(PINV_PAYAKUM + PINV_PAYAKUMPPn);	// Nilai yang diajukan untuk dibayar
				document.getElementById('PINV_PAYAKUMX').value 		= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(PINV_TOTVALTOT)),2));
				document.getElementById('PINV_TOTVAL').value 		= PINV_PAYAKUM;
				document.getElementById('PINV_TOTVALPPn').value 	= PINV_PAYAKUMPPn;
			
			// Nilai yang diterima
				PINV_RECEIVETOT			= parseFloat(PINV_TOTVALTOT - PINV_TOTVALPPH);	// Nilai yang akan diterima
				document.getElementById('PINV_RECEIVETOT').value 	= PINV_RECEIVETOT;
				document.getElementById('PINV_RECEIVETOT1').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(PINV_RECEIVETOT)),2));				
		}
	}

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

    function getDPValue(thisVal)
    {
        var decFormat		= document.getElementById('decFormat').value;
	    var DPPer			= parseFloat(eval(thisVal).value.split(",").join(""));

       // var PRJCOST		= document.getElementById('PRJCOST_TOTAL').value;
        var PRJCOST			= parseFloat(document.getElementById('PRJCOST').value);
        var PINV_OWNER 		= document.getElementById('PINV_OWNER').value;
        var ownIns 			= document.getElementById('OWN_INST').value;
        if(PINV_OWNER == 'none')
        {
        	swal('Silahkan tentukan owner proyek',
        	{
        		icon:"warning"
        	});
        	document.getElementById('PINV_DPPER').value 	= 0;
        	document.getElementById('PINV_DPPER1').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(0)), 4));
        	return false;
        }

        PINV_DPVALx			= Math.round(DPPer * PRJCOST / 100);				//	Nilai DP sebelum dipotong PPn
        PINV_DPPPnVALx		= Math.round(((PINV_DPVALx * 100) / 110) * 0.11);		//	Nilai PPn dari DP
        PINV_DPVALy			= parseFloat(PINV_DPVALx) - parseFloat(PINV_DPPPnVALx);	//	Nilai DP setelah dipotong PPn

        document.getElementById('PINV_DPPER').value 	= DPPer;
        document.getElementById('PINV_DPPER1').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(DPPer)), 4));
        document.getElementById('PINV_DPVAL').value 	= PINV_DPVALy;
        document.getElementById('PINV_DPVAL1').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(PINV_DPVALy)), 2));
        document.getElementById('PINV_DPVALPPn').value 	= PINV_DPPPnVALx;
        document.getElementById('PINV_DPVALPPn1').value = doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(PINV_DPPPnVALx)),2));
		
		// Nilai Prestasi (Sebelum + PPn)
			PINV_TOTVAL		= parseFloat(PINV_DPVALy);
		
		// Nilai yang diajukan untuk dibayar plus PPn
			PINV_TOTVALnPPN	= parseFloat(PINV_DPVALx);

		// TOTAL AKUM
			PINV_RETCUT		= document.getElementById('PINV_RETCUTCUR').value;
			PINV_PAYAKUM 	= parseFloat(PINV_DPVALy)  - parseFloat(PINV_RETCUT);
			document.getElementById('PINV_PAYAKUM').value 	= Math.round(PINV_PAYAKUM);
            document.getElementById('PINV_PAYAKUMX').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(PINV_PAYAKUM)), 2));

		// TOTAL AKUM PPN
			PINV_PAYAKUMPPn = parseFloat(PINV_PAYAKUM * 0.11);
			document.getElementById('PINV_PAYAKUMPPn').value 	= Math.round(PINV_PAYAKUMPPn);
            document.getElementById('PINV_PAYAKUMPPnX').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(PINV_PAYAKUMPPn)), 2));

        // PPH
        	PPH_PERC			= document.getElementById('PINV_TOTVALPPHP').value;
       		if(ownIns == 'S')
				PINV_PPHVAL	= parseFloat(PPH_PERC * PINV_DPVALy / 100);
			else
			{
				PINV_PPHVAL1	= parseFloat(PINV_DPVALy / 1.03);
				PINV_PPHVAL		= parseFloat(PINV_DPVALy - PINV_PPHVAL1);
				if(PPH_PERC == 0)
					PINV_PPHVAL = 0;
			}
		
		document.getElementById('PINV_TOTVALPPH').value 	= Math.round(PINV_PPHVAL);
        document.getElementById('PINV_TOTVALPPH1').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(PINV_PPHVAL)),decFormat));
		
		// Nilai yang diterima
		if(ownIns == 'S')
			PINV_RECEIVETOT	= parseFloat(PINV_TOTVALnPPN - PINV_PPHVAL);	// YANG MEMBEDAKAN HY BESARAN PPH
		else
			PINV_RECEIVETOT	= parseFloat(PINV_TOTVALnPPN - PINV_PPHVAL);	// YANG MEMBEDAKAN HY BESARAN PPH

		document.getElementById('PINV_RECEIVETOT').value 	= PINV_RECEIVETOT;
		document.getElementById('PINV_RECEIVETOT1').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(PINV_RECEIVETOT)),2));
    }
    
	function chgDPBackCur(thisVal)
	{
		MC_DPBACKCUR	= thisVal.split(",").join("");
		document.getElementById('PINV_DPBACKCUR').value 	= parseFloat(MC_DPBACKCUR);
		document.getElementById('PINV_DPBACKCUR1').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_DPBACKCUR)),2));
	}
    
	function chgProgCurr(thisVal)
	{
		PINV_PROGCURVAL	= thisVal.split(",").join("");
		document.getElementById('PINV_PROGCURVAL').value 	= parseFloat(PINV_PROGCURVAL);
		document.getElementById('PINV_PROGCURVALX').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(PINV_PROGCURVAL)),2));
	}
	
	function chgRetPer(thisVal)
	{
		RETCUTP_DEF	= parseFloat(thisVal);
		document.getElementById('PINV_RETCUTPX').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(RETCUTP_DEF)), 4));
		document.getElementById('PINV_RETCUTP').value 	= RETCUTP_DEF;

		PINV_CAT	= document.getElementById('PINV_CATX').value;
		if(PINV_CAT == 1)			// DP
		{
			PINV_TOTPROGRESS	= parseFloat(document.getElementById('PINV_DPVAL').value);
			var PINV_RETCUT		= parseFloat(RETCUTP_DEF) * parseFloat(PINV_TOTPROGRESS) / 100;

			document.getElementById('PINV_RETCUT').value 	= parseFloat(PINV_RETCUT);
			document.getElementById('PINV_RETCUTX').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(PINV_RETCUT)), 2));

			document.getElementById('PINV_RETCUTCUR').value 	= parseFloat(PINV_RETCUT);
			document.getElementById('PINV_RETCUTCUR1').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(PINV_RETCUT)), 2));

			var PPH_PERC	= eval(document.getElementById('PINV_TOTVALPPHP1')).value.split(",").join("");
			var PINV_DPVAL	= parseFloat(document.getElementById('PINV_DPVAL').value);
			var PINV_PPNVAL	= parseFloat(document.getElementById('PINV_DPVALPPn').value);
			var PINV_PPHVAL	= document.getElementById('PINV_TOTVALPPH').value;

			// Nilai yang diterima
				PINV_RECEIVETOT			= parseFloat(PINV_DPVAL + PINV_PPNVAL - PINV_RETCUT - PINV_PPHVAL);
				document.getElementById('PINV_RECEIVETOT').value 	= PINV_RECEIVETOT;
				document.getElementById('PINV_RECEIVETOT1').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(PINV_RECEIVETOT)),2));
		}
	}

	function chgRetValAkum(thisVal)
	{
		RETCUTCUR	= thisVal.split(",").join("");
		document.getElementById('PINV_RETCUT').value 	= parseFloat(RETCUTCUR);
		document.getElementById('PINV_RETCUTX').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(RETCUTCUR)),2));
	}

	function chgRetValCur(thisVal)
	{
		RETCUTCUR	= thisVal.split(",").join("");
		document.getElementById('PINV_RETCUTCUR').value 	= parseFloat(RETCUTCUR);
		document.getElementById('PINV_RETCUTCUR1').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(RETCUTCUR)),2));
	}

	function chgPPn(thisVal)
	{
		MC_PPN	= thisVal.split(",").join("");
		document.getElementById('PINV_PAYAKUMPPn').value 	= parseFloat(MC_PPN);
		document.getElementById('PINV_PAYAKUMPPnX').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(MC_PPN)),2));
	}
	
	function countPPHPer(thisVal)
	{
		var valPPh		= thisVal.split(",").join("");
		var PPH_PERC	= parseFloat(valPPh);
		var ownIns 		= document.getElementById('OWN_INST').value;
		var PINV_CAT	= document.getElementById('PINV_CATX').value;

		document.getElementById('PINV_TOTVALPPHP1').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(valPPh)),4));
		document.getElementById('PINV_TOTVALPPHP').value 	= parseFloat(valPPh);

		if(PINV_CAT == 1)
		{
			var PINV_DPVAL	= parseFloat(document.getElementById('PINV_DPVAL').value);
			var PINV_PPNVAL	= parseFloat(document.getElementById('PINV_DPVALPPn').value);	
			var PINV_RETCUT	= parseFloat(document.getElementById('PINV_RETCUT').value);

       		if(ownIns == 'S')
       		{
				PINV_PPHVAL		= parseFloat(PPH_PERC * PINV_DPVAL / 100);
				PINV_PPHVALADD	= PINV_PPHVAL;
       		}
			else
			{
				PINV_PPHVAL1	= parseFloat(PINV_DPVAL / 1.03);
				PINV_PPHVAL		= parseFloat(PINV_DPVAL - PINV_PPHVAL1);
				PINV_PPHVALADD	= 0;
			}
			document.getElementById('PINV_TOTVALPPH1').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(PINV_PPHVAL)),2));
			document.getElementById('PINV_TOTVALPPH').value 	= PINV_PPHVAL;
			
			// Nilai yang diterima
				PINV_RECEIVETOT			= parseFloat(PINV_DPVAL + PINV_PPNVAL - PINV_RETCUT - PINV_PPHVALADD);
				document.getElementById('PINV_RECEIVETOT').value 	= PINV_RECEIVETOT;
				document.getElementById('PINV_RECEIVETOT1').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(PINV_RECEIVETOT)),2));
		}
	}
	
	function countPPHVal(thisVal)
	{
		var valPPh	= thisVal.split(",").join("");
		document.getElementById('PINV_TOTVALPPH1').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(valPPh)),2));
		document.getElementById('PINV_TOTVALPPH').value 	= parseFloat(valPPh);
	}

	function chgGTOTAL(thisVal)
	{
		PINV_RECEIVETOT1	= thisVal.split(",").join("");
		document.getElementById('PINV_RECEIVETOT').value	= parseFloat(PINV_RECEIVETOT1);
		document.getElementById('PINV_RECEIVETOT1').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(PINV_RECEIVETOT1)), 2));
	}
	
	function submitForm(value)
	{
		var PINV_MANNO	= document.getElementById('PINV_MANNO').value;
		var PINV_STEP	= document.getElementById('PINV_STEP').value;
		var FlagUSER	= document.getElementById('FlagUSER').value;
		var CatSelected	= document.getElementById('CatSelected').value;
		var PINV_STATX	= document.getElementById('PINV_STATX').value;
		var PINV_CATX	= document.getElementById('PINV_CATX').value;
		var isEdit		= document.getElementById('isEdit').value;

		var PINV_OWNER 		= document.getElementById('PINV_OWNER').value;
        var ownIns 			= document.getElementById('OWN_INST').value;
        if(PINV_OWNER == 'none')
        {
        	swal('Silahkan tentukan owner proyek',
        	{
        		icon:"warning"
        	});
        	document.getElementById('PINV_DPPER').value 	= 0;
        	document.getElementById('PINV_DPPER1').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(0)), 4));
        	return false;
        }
		
		if(PINV_CATX == 2)
		{
			PINV_SOURCE1	= document.getElementById('PINV_SOURCE1').value;
			if(PINV_SOURCE1 == '')
			{
				swal('Please select one of MC Number');
				return false;
			}
		}
		else if(PINV_CATX == 3)
		{
			PINV_SOURCE2	= document.getElementById('PINV_SOURCE2').value;
			if(PINV_SOURCE2 == '')
			{
				swal('Please select one of SI Number');
				return false;
			}
		}
		
		if(PINV_MANNO == '')
		{
			swal('Please input MC Manual Number.');
			document.getElementById('PINV_MANNO').focus();
			return false;
		}
		
		if(PINV_STEP == 0)
		{
			swal('Please select step of MC.');
			document.getElementById('PINV_STEP').focus();
			return false;
		}
		
		if(CatSelected != 1)
		{
			var PINV_PROG	= document.getElementById('PINV_PROG').value;
			if(PINV_PROG == 0)
			{
				swal('Please input Progress Percentation.');
				document.getElementById('PINV_PROG1').value = '';
				document.getElementById('PINV_PROG1').focus();
				return false;
			}
		
			/*if(FlagUSER == 'APPSI')
			{
				var PINV_PROGAPP	= parseFloat(document.getElementById('PINV_PROGAPP').value);
				if(PINV_PROGAPP == 0)
				{
					swal('Please input Progresa Approve Presentation');
					document.getElementById('PINV_PROGAPP1').focus();
					document.getElementById('PINV_PROGAPP1').value = '';
					return false;
				}
			}*/
		}
		
		if(isEdit == 'N')
		{
			swal('Sorry ... You can not update this document.')
			return false;
		}
		else
		{
			document.frm.submit();
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
	
	function changeFDate(thisVal)
	{
		var date 			= new Date(thisVal.value);
		var datey 			= new Date(date.getFullYear(), date.getMonth(), date.getDate(), 0, 0, 0);
		var theM			= datey.getMonth();
		var dateDesc		= datey.getFullYear()+ "-" + datey.getMonth() + "-" + datey.getDate();
		var newDate 		= formatDate(datey);
		changeDueDate(newDate)
	}
	
	function changeDueDate(thisVal)
	{
		//var FDate			= document.getElementById('MCDATEx').value
		var date 			= new Date(thisVal);
		PINV_TTOTerm		= 14;
		var datey 			= new Date(date.getFullYear(), date.getMonth(), date.getDate() + PINV_TTOTerm, 0, 0, 0);
		var theM			= datey.getMonth();
		if(theM == 0)
		{
			theMD	= '01';
		}
		else if(theM == 1)
		{
			theMD	= '02';
		}
		else if(theM == 2)
		{
			theMD	= '03';
		}
		else if(theM == 3)
		{
			theMD	= '04';
		}
		else if(theM == 4)
		{
			theMD	= '05';
		}
		else if(theM == 5)
		{
			theMD	= '06';
		}
		else if(theM == 6)
		{
			theMD	= '07';
		}
		else if(theM == 7)
		{
			theMD	= '08';
		}
		else if(theM == 8)
		{
			theMD	= '09';
		}
		else if(theM == 9)
		{
			theMD	= '10';
		}
		else if(theM == 10)
		{
			theMD	= '11';
		}
		else if(theM == 11)
		{
			theMD	= '12';
		}
		var dateDesc	=  theMD + "/" + datey.getDate() + "/" + datey.getFullYear();
		//swal(dateDesc)
		document.getElementById('datepicker1').value 	= dateDesc;
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