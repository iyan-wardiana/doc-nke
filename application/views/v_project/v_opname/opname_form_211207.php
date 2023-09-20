<?php
/*
 * Author		= Dian Hermanto
 * Create Date	= 1 Februari 2018
 * File Name	= opname_form.php
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
$decFormat	= 2;

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
	}

	$yearC = (int)$Pattern_YearAktive;
	$year = substr($Pattern_YearAktive,2,2);
	$month = (int)$Pattern_MonthAktive;
	$date = (int)$Pattern_DateAktive;

	$this->db->where('Patt_Year', $year);
	//$this->db->where('Patt_Month', $month);
	//$this->db->where('Patt_Date', $date);
	$myCount = $this->db->count_all('tbl_opn_header');

	$sql = "SELECT MAX(Patt_Number) as maxNumber FROM tbl_opn_header
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
	$DocNumber 		= "$PRJCODE.$Pattern_Code$groupPattern-$lastPatternNumb";
	$OPNH_NUM		= "$DocNumber";
	$OPNH_CODE		= "$lastPatternNumb";
	$OPNH_DATE		= date('m/d/Y');
	$WO_ENDD		= date('m/d/Y');
	$OPNH_DATESP	= date('m/d/Y', strtotime('-6 days', strtotime($OPNH_DATE)));
	$PRJCODE		= $PRJCODE;
	$SPLCODE		= '';
	$WO_NUM			= '';
	$JOBCODEID		= '';
	$OPNH_NOTE		= '';
	$OPNH_STAT 		= 1;
	$Patt_Year 		= date('Y');
	$Patt_Number	= $lastPatternNumb1;
	$WO_NUMX		= '';
	$OPNH_NOTE		= '';
	$OPNH_NOTE2		= '';
	$OPNH_MEMO		= '';
	$OPNH_AMOUNT	= 0;
	$OPNH_AMOUNTPPNP= 0;
	$OPNH_AMOUNTPPN	= 0;
	$OPNH_AMOUNTPPHP= 0;
	$OPNH_AMOUNTPPH = 0;
	$OPNH_RETPERC	= 0;
	$OPNH_RETAMN	= 0;
	$OPNH_DPPER		= 0;
	$OPNH_DPVAL		= 0;
	$OPNH_POT		= 0;
	$OPNH_TOTAMOUNT	= 0;
	$OPNH_POTREF	= '';
	$OPNH_POTREF1	= '';
	$OPNH_POTACCID	= '';

	$dataColl 		= "$PRJCODE~$Pattern_Code~tbl_opn_header~$Pattern_Length";
	$dataTarget		= "OPNH_CODE";

	$OPNH_AMOUNTB	 = 0;
	$OPNH_PPNVALB 	 = 0;
	$OPNH_AMOUNTPPHBF= 0;
	$OPNH_DPVALB	 = 0;
	$OPNH_RETAMNB	 = 0;
	$PROG_BEF		 = 0;
	$OPNH_AMOUNTPPHBF= 0;

	if(isset($_POST['WO_NUMX']))
	{
		$WO_NUMX		= $_POST['WO_NUMX'];
	}
	else
	{
		$WO_NUMX		= $WO_NUMX;
	}

		
		if($WO_NUMX != '')
		{
			$WO_VALUE	= 0;
			$WO_VALPPN	= 0;
			$sqlWOH		= "SELECT WO_CODE, WO_DATE, WO_STARTD, WO_ENDD, JOBCODEID, SPLCODE, WO_NOTE, WO_VALUE, WO_VALPPN
							FROM tbl_wo_header
							WHERE WO_NUM = '$WO_NUMX' AND PRJCODE = '$PRJCODE' AND WO_STAT = 3";
			$resWOH 	= $this->db->query($sqlWOH)->result();
			foreach($resWOH as $rosWOH):
				$WO_CODE	= $rosWOH->WO_CODE;
				$WO_DATE	= $rosWOH->WO_DATE;
				$WO_STARTD	= $rosWOH->WO_STARTD;
				$WO_ENDD	= $rosWOH->WO_ENDD;
				$JOBCODEID	= $rosWOH->JOBCODEID;
				$SPLCODE	= $rosWOH->SPLCODE;
				$WO_NOTE	= $rosWOH->WO_NOTE;
				$WO_VALUE	= $rosWOH->WO_VALUE;
				$WO_VALPPN	= $rosWOH->WO_VALPPN;
			endforeach;

			$WO_TOTAL	= 0;
			$WO_DESC	= 0;
			$TAXPRICE1	= 0;
			$TOT_PPN	= 0;
			$TOT_PPH	= 0;
			$sqlWOD		= "SELECT SUM(A.WO_TOTAL) AS WO_TOTAL, SUM(A.WO_DESC) AS WO_DESC, SUM(A.TAXPRICE1) AS TOT_PPN,
								SUM(A.TAXPRICE2) AS TOT_PPH
							FROM tbl_wo_detail A
							INNER JOIN tbl_wo_header B ON B.WO_NUM = A.WO_NUM
							WHERE B.WO_NUM = '$WO_NUMX' AND B.PRJCODE = '$PRJCODE' AND B.WO_STAT = 3";
			$resWOD 	= $this->db->query($sqlWOD)->result();
			foreach($resWOD as $rosWOD):
				$WO_TOTAL	= $rosWOD->WO_TOTAL;
				$WO_DESC	= $rosWOD->WO_DESC;
				$TOT_PPN	= $rosWOD->TOT_PPN;
				$TOT_PPH	= $rosWOD->TOT_PPH;
			endforeach;
			$TOT_PPHP 		= $TOT_PPH / $WO_TOTAL * 100;

			// TOTAL OPNAME
				$TOT_WOAMOUNT	= 0;
				$TOT_OPNAMOUNT	= 0;
				$sqlWOH		= "SELECT SUM(A.WO_TOTAL) AS TOT_WOAMOUNT, SUM(A.OPN_AMOUNT) AS TOT_OPNAMOUNT
								FROM tbl_wo_detail A
								INNER JOIN tbl_wo_header B ON A.WO_NUM = B.WO_NUM
								WHERE A.WO_NUM = '$WO_NUMX' AND A.PRJCODE = '$PRJCODE' AND B.WO_STAT = 3";
				$resWOH 	= $this->db->query($sqlWOH)->result();
				foreach($resWOH as $rosWOH):
					$TOT_WOAMOUNT	= $rosWOH->TOT_WOAMOUNT;
					$TOT_OPNAMOUNT	= $rosWOH->TOT_OPNAMOUNT;
				endforeach;



			// $OPNH_AMOUNT1	= $WO_VALUE - $WO_VALPPN;
			// $OPNH_AMOUNTPPN	= $WO_VALPPN;
			// $OPNH_AMOUNTPPNP= $WO_VALPPN / $OPNH_AMOUNT1 * 100;

			/*$OPNH_AMOUNT1	= $WO_TOTAL;
			$OPNH_AMOUNTPPN	= $TOT_PPN;
			$OPNH_AMOUNTPPH	= $TOT_PPH;
			$OPNH_AMOUNTPPNP= $TOT_PPN / $WO_TOTAL * 100;
			$OPNH_AMOUNTPPHP= $TOT_PPH / $WO_TOTAL * 100;*/

			$OPNH_AMOUNT1	= $WO_TOTAL;
			$OPNH_AMOUNTPPNP= $TOT_PPN / $WO_TOTAL * 100;
			$OPNH_AMOUNTPPHP= $TOT_PPH / $WO_TOTAL * 100;

			$OPNH_AMOUNT	= $TOT_WOAMOUNT - $TOT_OPNAMOUNT;
			$OPNH_AMOUNTPPN	= $OPNH_AMOUNTPPNP * $OPNH_AMOUNT / 100;	// PPN NOW
			$OPNH_AMOUNTPPH = $OPNH_AMOUNTPPHP * $OPNH_AMOUNT / 100;	// PPH NOW



			// PROGRESS PRESTASI SEBELUMNYA
			$OPNHDATE	= date('Y-m-d');
			$PROG_BEF	= 0;
			$sqlGBEF	= "SELECT
								SUM(A.OPND_ITMTOTAL) AS PROG_BEF
							FROM
								tbl_opn_detail A
							INNER JOIN tbl_opn_header B ON A.OPNH_NUM = B.OPNH_NUM
								AND B.OPNH_STAT = 3
							WHERE A.OPNH_NUM != '$OPNH_NUM'
							AND B.OPNH_DATE < '$OPNHDATE' AND A.PRJCODE = '$PRJCODE' AND B.WO_NUM = '$WO_NUMX'";
			$resGBEF	= $this->db->query($sqlGBEF)->result();
			foreach($resGBEF as $rowGBEF) :
				$PROG_BEF	= $rowGBEF->PROG_BEF;
			endforeach;
			$OPNH_AMOUNTB	= $PROG_BEF + $OPNH_AMOUNT;

			$OPNH_AMOUNTB		= $OPNH_AMOUNTB;
			$OPNH_PPNVALB1		= 0;
			$OPNH_DPVALB1		= 0;
			$OPNH_RETAMNB1		= 0;
			$OPNH_POTB1			= 0;
			$sqlGBEFH			= "SELECT SUM(OPNH_AMOUNTPPN) AS OPNH_PPN, SUM(OPNH_DPVAL) AS TOT_DP, SUM(OPNH_RETAMN) AS TOT_RET,
										SUM(OPNH_AMOUNTPPHP) AS OPNH_PPH
									FROM tbl_opn_header
									WHERE OPNH_NUM != '$OPNH_NUM'
										AND OPNH_DATE < '$OPNHDATE' AND PRJCODE = '$PRJCODE' AND OPNH_STAT = 3 AND WO_NUM = '$WO_NUMX'";
			$resGBEFH			= $this->db->query($sqlGBEFH)->result();
			foreach($resGBEFH as $rowGBEFH) :
				$OPNH_PPNVALB1	= $rowGBEFH->OPNH_PPN;
				$OPNH_DPVALB1	= $rowGBEFH->TOT_DP;
				$OPNH_RETAMNB1	= $rowGBEFH->TOT_RET;
				$TOT_PPHBEF		= $rowGBEFH->OPNH_PPH;
			endforeach;
			$OPNH_PPNVALB		= $OPNH_PPNVALB1;
			$OPNH_DPVALB		= $OPNH_DPVALB1;
			$OPNH_RETAMNB		= $OPNH_RETAMNB1;
			$OPNH_PPHVALB		= $TOT_PPHBEF;

			$OPNH_AMOUNTPPHBF 	= $OPNH_AMOUNTPPH + $OPNH_PPHVALB;

			// $OPNH_AMOUNTB		= $OPNH_AMOUNTB;
			// $OPNH_DPVALB1		= 0;
			// $OPNH_RETAMNB1		= 0;
			// $OPNH_POTB1			= 0;
			// $sqlGBEFH			= "SELECT SUM(OPNH_DPVAL) AS TOT_DP, SUM(OPNH_RETAMN) AS TOT_RET
			// 						FROM tbl_opn_header
			// 						WHERE OPNH_NUM != '$OPNH_NUM'
			// 							AND OPNH_DATE < '$OPNHDATE' AND PRJCODE = '$PRJCODE' AND OPNH_STAT = 3 AND WO_NUM = '$WO_NUM'";
			// $resGBEFH			= $this->db->query($sqlGBEFH)->result();
			// foreach($resGBEFH as $rowGBEFH) :
			// 	$OPNH_DPVALB1	= $rowGBEFH->TOT_DP;
			// 	$OPNH_RETAMNB1	= $rowGBEFH->TOT_RET;
			// endforeach;
			// $OPNH_DPVALB		= $OPNH_DPVALB1;
			// $OPNH_RETAMNB		= $OPNH_RETAMNB1;

			//GET PPH SEBELUMNYA
				/*$OPNH_PPhBEF 		= 0;
				$Tot_OPNH_PPhBEF	= 0;
				$sqlPPHBF 		= "SELECT A.TAXCODE1, A.TAXPRICE1 AS OPNH_PPhBEF FROM tbl_opn_detail A 
									INNER JOIN tbl_opn_header B ON B.OPNH_NUM = A.OPNH_NUM
									WHERE B.OPNH_NUM != '$OPNH_NUM' AND B.OPNH_DATE < '$OPNHDATE' AND B.PRJCODE = '$PRJCODE'
									AND B.OPNH_STAT = 3 AND B.WO_NUM = '$WO_NUM'";
				$resPPHBF		= $this->db->query($sqlPPHBF)->result();
				foreach ($resPPHBF as $rPPhBF):
					$TAXCODE1 		= $rPPhBF->TAXCODE1;
					$OPNH_PPhBEF 	= $rPPhBF->OPNH_PPhBEF;
					if($TAXCODE1 != 'TAX01')
					{
						$Tot_OPNH_PPhBEF 	= $Tot_OPNH_PPhBEF + $OPNH_PPhBEF;
						$OPNH_AMOUNTPPHBF 	= $Tot_OPNH_PPhBEF;
					}
				endforeach;*/
		}
		else
		{
			$WO_CODE	= '';
			$WO_DATE	= '';
			$WO_STARTD	= '';
			$WO_ENDD	= '';
			$JOBCODEID	= '';
			$SPLCODE	= '';

			$WO_NOTE	= '';
		}
}
else
{
	$isSetDocNo 	= 1;
	$OPNH_NUM 		= $default['OPNH_NUM'];
	$DocNumber		= $default['OPNH_NUM'];
	$OPNH_CODE 		= $default['OPNH_CODE'];
	$OPNH_DATE 		= date('m/d/Y', strtotime($default['OPNH_DATE']));
	$OPNH_DATE 		= date('m/d/Y', strtotime($default['OPNH_DATE']));
	$OPNH_DATESP	= date('m/d/Y', strtotime($default['OPNH_DATESP']));
	$PRJCODE		= $default['PRJCODE'];
	$SPLCODE 		= $default['SPLCODE'];
	$WO_NUM 		= $default['WO_NUM'];
	$WO_NUMX		= $WO_NUM;
	$JOBCODEID 		= $default['JOBCODEID'];
	$OPNH_NOTE 		= $default['OPNH_NOTE'];
	$OPNH_NOTE2 	= $default['OPNH_NOTE2'];
	$OPNH_STAT 		= $default['OPNH_STAT'];
	$OPNH_MEMO 		= $default['OPNH_MEMO'];
	$PRJNAME 		= $default['PRJNAME'];
	$OPNH_AMOUNT 	= $default['OPNH_AMOUNT'];
	$OPNH_AMOUNTPPNP= $default['OPNH_AMOUNTPPNP'];
	$OPNH_AMOUNTPPN	= $default['OPNH_AMOUNTPPN'];
	$OPNH_AMOUNTPPHP= $default['OPNH_AMOUNTPPHP'];
	$OPNH_AMOUNTPPH	= $default['OPNH_AMOUNTPPH'];
	$OPNH_RETPERC 	= $default['OPNH_RETPERC'];
	$OPNH_RETAMN 	= $default['OPNH_RETAMN'];
	$OPNH_DPPER 	= $default['OPNH_DPPER'];
	$OPNH_DPVAL 	= $default['OPNH_DPVAL'];
	$OPNH_POT		= $default['OPNH_POT'];
	$OPNH_POTREF	= $default['OPNH_POTREF'];
	$OPNH_POTREF1	= $default['OPNH_POTREF1'];
	$OPNH_POTACCID	= $default['OPNH_POTACCID'];
	$Patt_Year 		= $default['Patt_Year'];
	$Patt_Month 	= $default['Patt_Month'];
	$Patt_Date 		= $default['Patt_Date'];
	$Patt_Number	= $default['Patt_Number'];

	//echo "OPNH_AMOUNTPPH : $OPNH_AMOUNTPPH";

	if($OPNH_AMOUNTPPHP > 0 || $OPNH_AMOUNTPPNP > 0)
	{
		/*$WO_TOTAL	= 0;
		$WO_DESC	= 0;
		$TAXPRICE1	= 0;
		$TOT_PPN	= 0;
		$TOT_PPH	= 0;
		$sqlWOD		= "SELECT SUM(A.WO_TOTAL) AS WO_TOTAL, SUM(A.WO_DESC) AS WO_DESC, SUM(A.TAXPRICE1) AS TOT_PPN,
							SUM(A.TAXPRICE2) AS TOT_PPH
						FROM tbl_wo_detail A
						INNER JOIN tbl_wo_header B ON B.WO_NUM = A.WO_NUM
						WHERE B.WO_NUM = '$WO_NUMX' AND B.PRJCODE = '$PRJCODE' AND B.WO_STAT IN (3,6)";
		$resWOD 	= $this->db->query($sqlWOD)->result();
		foreach($resWOD as $rosWOD):
			$WO_TOTAL	= $rosWOD->WO_TOTAL;
			$WO_DESC	= $rosWOD->WO_DESC;
			$TOT_PPN	= $rosWOD->TOT_PPN;
			$TOT_PPH	= $rosWOD->TOT_PPH;
		endforeach;
		$TOT_PPHP 		= $TOT_PPH / $WO_TOTAL * 100;

		// TOTAL OPNAME
			$TOT_WOAMOUNT	= 0;
			$TOT_OPNAMOUNT	= 0;
			$sqlWOH		= "SELECT SUM(A.WO_TOTAL) AS TOT_WOAMOUNT, SUM(A.OPN_AMOUNT) AS TOT_OPNAMOUNT
							FROM tbl_wo_detail A
							INNER JOIN tbl_wo_header B ON A.WO_NUM = B.WO_NUM
							WHERE A.WO_NUM = '$WO_NUMX' AND A.PRJCODE = '$PRJCODE' AND B.WO_STAT IN (3,6)";
			$resWOH 	= $this->db->query($sqlWOH)->result();
			foreach($resWOH as $rosWOH):
				$TOT_WOAMOUNT	= $rosWOH->TOT_WOAMOUNT;
				$TOT_OPNAMOUNT	= $rosWOH->TOT_OPNAMOUNT;
			endforeach;

		$OPNH_AMOUNT1	= $WO_TOTAL;
		$OPNH_AMOUNTPPNP= $TOT_PPN / $WO_TOTAL * 100;
		$OPNH_AMOUNTPPHP= $TOT_PPH / $WO_TOTAL * 100;

		$OPNH_AMOUNT	= $TOT_WOAMOUNT - $TOT_OPNAMOUNT;
		$OPNH_AMOUNTPPN	= $OPNH_AMOUNTPPNP * $OPNH_AMOUNT / 100;
		$OPNH_AMOUNTPPH = $OPNH_AMOUNTPPHP * $OPNH_AMOUNT / 100;*/
	}

	//GET INFO PPH
	/*$OPNH_PPh		= 0;
	$Tot_OPNH_PPh	= 0;
	$OPNH_AMOUNTPPH	= 0;
	$sqlPPH 		= "SELECT A.TAXCODE1, A.TAXPRICE1 AS OPNH_PPh FROM tbl_opn_detail A 
						INNER JOIN tbl_opn_header B ON B.OPNH_NUM = A.OPNH_NUM
						WHERE B.OPNH_NUM = '$OPNH_NUM' AND B.PRJCODE = '$PRJCODE'
						AND B.WO_NUM = '$WO_NUM'";
	$resPPH			= $this->db->query($sqlPPH)->result();
	foreach ($resPPH as $rPPh):
		$TAXCODE1 	= $rPPh->TAXCODE1;

		$OPNH_PPh = $rPPh->OPNH_PPh;
		if($TAXCODE1 != 'TAX01')
		{
			$Tot_OPNH_PPh	= $Tot_OPNH_PPh + $OPNH_PPh;
			$OPNH_AMOUNTPPH	= $Tot_OPNH_PPh;
		}
	endforeach;*/

	// PROGRESS PRESTASI SEBELUMNYA
	$OPNHDATE	= '0000-00-00';
	$sqlOPNH	= "SELECT OPNH_DATE FROM tbl_opn_header WHERE OPNH_NUM = '$OPNH_NUM'";
	$resOPNH	= $this->db->query($sqlOPNH)->result();
	foreach($resOPNH as $rowOPNH) :
		$OPNHDATE	= $rowOPNH->OPNH_DATE;
	endforeach;
	$PROG_BEF	= 0;
	$sqlGBEF	= "SELECT
						SUM(A.OPND_ITMTOTAL) AS PROG_BEF
					FROM
						tbl_opn_detail A
					INNER JOIN tbl_opn_header B ON A.OPNH_NUM = B.OPNH_NUM
						AND B.OPNH_STAT = 3
					WHERE A.OPNH_NUM != '$OPNH_NUM'
						AND B.OPNH_DATE < '$OPNHDATE' AND A.PRJCODE = '$PRJCODE' AND B.WO_NUM = '$WO_NUM'";
	$resGBEF	= $this->db->query($sqlGBEF)->result();
	foreach($resGBEF as $rowGBEF) :
		$PROG_BEF	= $rowGBEF->PROG_BEF;
	endforeach;
	$OPNH_AMOUNTB	= $PROG_BEF + $OPNH_AMOUNT;

	$OPNH_AMOUNTB		= $OPNH_AMOUNTB;
	$OPNH_PPNVALB1		= 0;
	$OPNH_DPVALB1		= 0;
	$OPNH_RETAMNB1		= 0;
	$OPNH_POTB1			= 0;
	$sqlGBEFH			= "SELECT SUM(OPNH_AMOUNTPPN) AS OPNH_PPN, SUM(OPNH_DPVAL) AS TOT_DP, SUM(OPNH_RETAMN) AS TOT_RET
							FROM tbl_opn_header
							WHERE OPNH_NUM != '$OPNH_NUM'
								AND OPNH_DATE < '$OPNHDATE' AND PRJCODE = '$PRJCODE' AND OPNH_STAT = 3 AND WO_NUM = '$WO_NUM'";
	$resGBEFH			= $this->db->query($sqlGBEFH)->result();
	foreach($resGBEFH as $rowGBEFH) :
		$OPNH_PPNVALB1	= $rowGBEFH->OPNH_PPN;
		$OPNH_DPVALB1	= $rowGBEFH->TOT_DP;
		$OPNH_RETAMNB1	= $rowGBEFH->TOT_RET;
	endforeach;
	$OPNH_PPNVALB		= $OPNH_PPNVALB1;
	$OPNH_DPVALB		= $OPNH_DPVALB1;
	$OPNH_RETAMNB		= $OPNH_RETAMNB1;

	//GET PPH SEBELUMNYA
	$OPNH_PPhBEF		= 0;
	$Tot_OPNH_PPhBEF	= 0;
	$OPNH_AMOUNTPPHBF 	= 0;
	/*$sqlPPHBF 		= "SELECT A.TAXCODE1, A.TAXPRICE1 AS OPNH_PPhBEF FROM tbl_opn_detail A 
						INNER JOIN tbl_opn_header B ON B.OPNH_NUM = A.OPNH_NUM
						WHERE B.OPNH_NUM != '$OPNH_NUM' AND B.OPNH_DATE < '$OPNHDATE' AND B.PRJCODE = '$PRJCODE'
						AND B.OPNH_STAT = 3 AND B.WO_NUM = '$WO_NUM'";
	$resPPHBF		= $this->db->query($sqlPPHBF)->result();
	foreach ($resPPHBF as $rPPhBF):
		$TAXCODE1 	= $rPPhBF->TAXCODE1;

		$OPNH_PPhBEF = $rPPhBF->OPNH_PPhBEF;
		if($TAXCODE1 != 'TAX01')
		{
			$Tot_OPNH_PPhBEF	= $Tot_OPNH_PPhBEF + $OPNH_PPhBEF;
			$OPNH_AMOUNTPPHBF 	= $Tot_OPNH_PPhBEF;
		}
	endforeach;*/
}

$TOT_PPH	= 0;
$sqlWOD		= "SELECT SUM(A.TAXPRICE2) AS TOT_PPH
				FROM tbl_wo_detail A
				INNER JOIN tbl_wo_header B ON B.WO_NUM = A.WO_NUM
				WHERE B.WO_NUM = '$WO_NUMX' AND B.PRJCODE = '$PRJCODE' AND B.WO_STAT = 3";
$resWOD 	= $this->db->query($sqlWOD)->result();
foreach($resWOD as $rosWOD):
	$TOT_PPH	= $rosWOD->TOT_PPH;
endforeach;

//$OPNH_AMOUNTPPH = $TOT_PPH;

$sqlWOH1	= "SELECT WO_CODE FROM tbl_wo_header
				WHERE WO_NUM = '$WO_NUMX'";
$resWOH1 	= $this->db->query($sqlWOH1)->result();
foreach($resWOH1 as $rosWOH1):
	$WO_CODE	= $rosWOH1->WO_CODE;
endforeach;

$OPNH_TOTAMOUNT		= $OPNH_AMOUNT + $OPNH_AMOUNTPPN - $OPNH_DPVAL - $OPNH_RETAMN - $OPNH_POT;
$OPNH_TOTAMOUNTX	= $OPNH_AMOUNT + $OPNH_AMOUNTPPN - $OPNH_AMOUNTPPH - $OPNH_DPVAL - $OPNH_RETAMN - $OPNH_POT;

// AKUMULASI
	$AKUM_PROG		= $OPNH_AMOUNTB;
	$AKUM_DPVAL		= $OPNH_DPVAL + $OPNH_DPVALB;
	if($OPNH_RETPERC == 0)
		$AKUM_RETAMN	= $OPNH_RETAMNB;
	else
		$AKUM_RETAMN	= $OPNH_RETAMN + $OPNH_RETAMNB;

	$AKUM_PPNVAL 	= $OPNH_AMOUNTPPN + $OPNH_PPNVALB;

	$AKUM_PPHVAL 	= $OPNH_AMOUNTPPH + $OPNH_AMOUNTPPHBF;

	//$OPNH_TOTAMOUNTB	= $AKUM_PROG - $AKUM_DPVAL - $AKUM_RETAMN - $PROG_BEF;
	//$OPNH_TOTAMOUNTB	= ($AKUM_PROG - $AKUM_DPVAL - $OPNH_RETAMN);
	$OPNH_TOTAMOUNTB	= ($AKUM_PROG + $AKUM_PPNVAL - $AKUM_PPHVAL - $AKUM_DPVAL - $OPNH_RETAMN);
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
			if($TranslCode == 'OpnNo')$OpnNo = $LangTransl;
			if($TranslCode == 'NoSPK')$NoSPK = $LangTransl;
			if($TranslCode == 'RequestCode')$RequestCode = $LangTransl;
			if($TranslCode == 'Code')$Code = $LangTransl;
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
			if($TranslCode == 'SPKQty')$SPKQty = $LangTransl;
			if($TranslCode == 'QtyOpnamed')$QtyOpnamed = $LangTransl;
			if($TranslCode == 'RequestNow')$RequestNow = $LangTransl;
			if($TranslCode == 'Quantity')$Quantity = $LangTransl;
			if($TranslCode == 'Unit')$Unit = $LangTransl;
			if($TranslCode == 'Primary')$Primary = $LangTransl;
			if($TranslCode == 'Secondary')$Secondary = $LangTransl;
			if($TranslCode == 'Remarks')$Remarks = $LangTransl;
			if($TranslCode == 'AddItem')$AddItem = $LangTransl;
			if($TranslCode == 'JobName')$JobName = $LangTransl;
			if($TranslCode == 'ItemQty')$ItemQty = $LangTransl;
			if($TranslCode == 'Search')$Search = $LangTransl;
			if($TranslCode == 'ApproverNotes')$ApproverNotes = $LangTransl;
			if($TranslCode == 'Supplier')$Supplier = $LangTransl;
			if($TranslCode == 'reviseNotes')$reviseNotes = $LangTransl;
			if($TranslCode == 'reviseNotes')$reviseNotes = $LangTransl;
			if($TranslCode == 'Approval')$Approval = $LangTransl;
			if($TranslCode == 'Approved')$Approved = $LangTransl;
			if($TranslCode == 'NotYetApproved')$NotYetApproved = $LangTransl;
			if($TranslCode == 'rejected')$rejected = $LangTransl;
			if($TranslCode == 'InOthSett')$InOthSett = $LangTransl;

			if($TranslCode == 'Select')$Select = $LangTransl;
    		if($TranslCode == 'Close')$Close = $LangTransl;
    		if($TranslCode == 'NoSPK')$NoSPK = $LangTransl;
    		if($TranslCode == 'Date')$Date = $LangTransl;
    		if($TranslCode == 'StartDate')$StartDate = $LangTransl;
    		if($TranslCode == 'EndDate')$EndDate = $LangTransl;
    		if($TranslCode == 'Description')$Description = $LangTransl;
    		if($TranslCode == 'BudgetQty')$BudgetQty = $LangTransl;
    		if($TranslCode == 'QtyOpnamed')$QtyOpnamed = $LangTransl;
    		if($TranslCode == 'Remain')$Remain = $LangTransl;
    		if($TranslCode == 'WOList')$WOList = $LangTransl;
    		if($TranslCode == 'SPKCost')$SPKCost = $LangTransl;
		endforeach;

		if($LangID == 'IND')
		{
			$subTitleH	= "Tambah Opname";
			$subTitleD	= "opname proyek";
			$isManual	= "Centang untuk kode manual.";
			$alert1		= "Masukan alasan mengapa dokumen ini di-batalkan/close.";
			$alert2		= "Silahkan pilih nama supplier.";
			$alert3		= "Nilai yang Anda inputkan lebih besar dari sisa.";
			$alert4		= "Nilai yang Anda inputkan lebih besar dari total opname.";
			$alert5		= "Silahkan pilih nomor SPK.";
			$SPeriode	= "Periode Mulai";
			$docalert1	= 'Peringatan';
			$docalert2	= 'Anda belum men-setting penomoran untuk dokumen ini. Sehingga, akan diberikan penomoran secara default dari sistem. Silahkan atur dari menu pengaturan. Silahkan atur  penomoran dokumen pada menu pengaturan.';
			$alertVOID	= "Tidak dapat dibatalkan. Sudah digunakan oleh Dokumen No.: ";
		}
		else
		{
			$subTitleH	= "Add Opname";
			$subTitleD	= "project opname";
			$isManual	= "Check to manual code.";
			$alert1		= "Input the reason why you close/void this document.";
			$alert2		= "Please select a supplier.";
			$alert3		= "Amount you inputed is greater than remaining.";
			$alert4		= "Amount you inputed is greater than total opname.";
			$alert5		= "Please select SPK Number.";
			$SPeriode	= "Start Periode";
			$docalert1	= 'Warning';
			$docalert2	= 'You have not set the numbering for this document. So, numbering will be given by default from the system. Please set document numbering in the Setting menu.';
			$alertVOID	= "Can not be void. Used by document No.: ";
		}

		// VOID CHECKING FUNCTION
			$DOC_NO	= '';
			$sqlTTKC= "tbl_ttk_header A 
						INNER JOIN tbl_ttk_detail B ON B.TTK_NUM = A.TTK_NUM
						WHERE B.TTK_REF1_NUM = '$OPNH_NUM' AND A.TTK_CATEG = 'OPN' AND TTK_STAT NOT IN (5,9)";
			$isUSED	= $this->db->count_all($sqlTTKC);
			if($isUSED > 0)
			{
				$noU	= 0;
				$sqlTTK	= "SELECT TTK_CODE FROM tbl_ttk_header A 
							INNER JOIN tbl_ttk_detail B ON B.TTK_NUM = A.TTK_NUM
							WHERE B.TTK_REF1_NUM = '$OPNH_NUM' AND A.TTK_CATEG = 'OPN' AND TTK_STAT NOT IN (5,9)";
				$resTTK	= $this->db->query($sqlTTK)->result();
				foreach($resTTK as $rowTTK):
					$noU	= $noU + 1;
					$DOCNO	= $rowTTK->TTK_CODE;
					if($noU == 1)
						$DOC_NO = $DOCNO;
					else
						$DOC_NO	= $DOC_NO.", ".$DOCNO;
				endforeach;
			}

		$secAddURL	= site_url('c_project/c_spk/add/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
		$secGenCode	= base_url().'index.php/c_project/c_spk/genCode/'; // Generate Code
		
		// START : APPROVE PROCEDURE
			if($APPLEV == 'HO')
				$PRJCODE_LEV	= $this->data['PRJCODE_HO'];
			else
				$PRJCODE_LEV	= $this->data['PRJCODE'];
			
			// DocNumber - PO_TOTCOST
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
				$APPROVE_AMOUNT 	= $OPNH_AMOUNT;
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
			    <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/opname.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $subTitleH; ?>
			    <small><?php echo $PRJNAME; ?></small>
			</h1>
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
		        <!-- Mencari Kode Purchase Request Number -->
		        <form name="frmsrch" id="frmsrch" action="" method=POST style="display:none">
		            <input type="text" name="WO_NUMX" id="WO_NUMX" class="textbox" value="<?php echo $WO_NUMX; ?>" />
		            <input type="text" name="task" id="task" class="textbox" value="<?php echo $task; ?>" />
		            <input type="submit" class="button_css" name="submitSrch" id="submitSrch" value=" search " />
		        </form>
		        <!-- End -->
		        
		        <form class="form-horizontal" name="frm" method="post" action="<?php echo $form_action; ?>" enctype="multipart/form-data" onSubmit="return validateInData()">
					<div class="col-md-6">
						<div class="box box-primary">
							<div class="box-header with-border" style="display: none;">
								<i class="fa fa-cloud-upload"></i>
								<h3 class="box-title">&nbsp;</h3>
							</div>
							<div class="box-body">
		            			<input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
					            <input type="hidden" name="Patt_Number" id="Patt_Number" value="<?php echo $Patt_Number; ?>" />
					            <input type="hidden" name="isUSED" id="isUSED" value="<?php echo $isUSED; ?>">
					            <input type="hidden" name="SPLCODE" id="SPLCODE" value="<?php echo $SPLCODE; ?>" />
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
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $OpnNo; ?></label>
				                    <div class="col-sm-9">
				                    	<input type="text" class="form-control" style="text-align:left" name="OPNH_NUM1" id="OPNH_NUM1" size="30" value="<?php echo $DocNumber; ?>" disabled />
				                    	<input type="hidden" class="form-control" style="max-width:350px;text-align:right" name="OPNH_NUM" id="OPNH_NUM" size="30" value="<?php echo $DocNumber; ?>" />
				                    </div>
				                </div>
				                <div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Code; ?></label>
				                    <div class="col-sm-9">
				                        <input type="text" class="form-control" style="text-align:left" id="OPNH_CODE" name="OPNH_CODE" size="5" value="<?php echo $OPNH_CODE; ?>" readonly />
				                        <label style="display:none;">
				                            &nbsp;&nbsp;<input type="checkbox" name="isManual" id="isManual">
				                        </label>
				                        <label style="font-style:italic;display:none;">
				                            <?php echo $isManual; ?>
				                        </label>
				                    </div>
				                </div>
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Date; ?></label>
				                    <div class="col-sm-9">
				                    	<div class="input-group date">
				                        <div class="input-group-addon">
				                        <i class="fa fa-calendar"></i>&nbsp;</div><input type="text" name="OPNH_DATE" class="form-control pull-left" id="datepicker" value="<?php echo $OPNH_DATE; ?>" style="width:106px" onChange="getOPNH_NUM(this.value)"></div>
				                    </div>
				                </div>
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $SPeriode; ?></label>
				                    <div class="col-sm-9">
				                    	<div class="input-group date">
				                        <div class="input-group-addon">
				                        <i class="fa fa-calendar"></i>&nbsp;</div><input type="text" name="OPNH_DATESP" class="form-control pull-left" id="datepicker1" value="<?php echo $OPNH_DATESP; ?>" style="width:106px"></div>
				                    </div>
				                </div>
				                <div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $NoSPK; ?> </label>
				                    <div class="col-sm-9">
				                        <div class="input-group">
				                            <div class="input-group-btn">
				                                <button type="button" class="btn btn-primary"><?php echo $Search ?> </button>
				                            </div>
				                            <input type="hidden" class="form-control" name="WO_NUM" id="WO_NUM" style="max-width:160px" value="<?php echo $WO_NUMX; ?>" >
				                            <input type="hidden" class="form-control" name="WO_CODE" id="WO_CODE" style="max-width:160px" value="<?php echo $WO_CODE; ?>" >
				                            <input type="text" class="form-control" name="WO_NUM1" id="WO_NUM1" value="<?php echo $WO_CODE; ?>" onClick="pleaseCheck();" <?php if($task != 'add') { ?> disabled <?php } ?> style="display: none;">

		                                    <input type="text" class="form-control" name="WO_NUM1" id="WO_NUM1" value="<?php echo "$WO_CODE"; ?>" onClick="pleaseCheck();">
		                                    <input type="hidden" class="form-control" name="WO_NUM2" id="WO_NUM2" value="<?php echo $WO_CODE; ?>" data-toggle="modal" data-target="#mdl_addJList">
				                        </div>
				                    </div>
				                </div>
								<?php
				                    //$url_selAURCODE	= site_url('c_asset/c_asset_usage/popupallaur/?id='.$this->url_encryption_helper->encode_url($AU_PRJCODE));
				                    $selSPKNo	= site_url('c_project/c_o180d0bpnm/popupallOPNH/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
				                ?>
				                <script>
				                    var url1 = "<?php echo $selSPKNo;?>";
				                    function pleaseCheck()
				                    {
				                        /*title = 'Select Item';
				                        w = 1000;
				                        h = 550;
				                        //window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
				                        var left = (screen.width/2)-(w/2);
				                        var top = (screen.height/2)-(h/2);
				                        return window.open(url1, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);*/

				                        document.getElementById('WO_NUM2').click();
				                    }

									function getOPNH_NUM(selDate)
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
													document.getElementById('OPNH_NUM1').value = myarr[0];
													document.getElementById('OPNH_CODE').value = myarr[1];
												}
											});
										});
									});
								</script>
				            	<div class="form-group" style="display: none;">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Project; ?></label>
				                    <div class="col-sm-9">
				                        <input type="hidden" class="form-control" style="max-width:350px;text-align:right" name="PRJCODE" id="PRJCODE" size="30" value="<?php echo $PRJCODE; ?>" />
				                    	<select name="PRJCODE" id="PRJCODE" class="form-control" onChange="chooseProject()" disabled>
				                          <option value="none">---</option>
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
				                    	<input type="hidden" name="JOBCODEID" id="JOBCODEID" value="<?php echo $JOBCODEID; ?>">
				                        <select name="JOBCODEID1" id="JOBCODEID1" class="form-control select2" multiple="multiple" data-placeholder="&nbsp;&nbsp;&nbsp;<?php echo $JobName; ?>" onBlur="selJOB(this.value)" disabled>
											<?php
				                                /*$Disabled_1	= 0;
				                                $sqlJob_1	= "SELECT JOBCODEID, JOBPARENT, JOBLEV, JOBDESC FROM tbl_joblist WHERE ISHEADER = '1'";
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

													$JIDExplode = explode('~', $JOBCODEID);
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

				                                    $sqlC_2			= "tbl_joblist WHERE JOBPARENT = '$JOBCODEID_1' AND ISHEADER = '1'";
				                                    $resC_2 		= $this->db->count_all($sqlC_2);
				                                    if($resC_2 > 0)
				                                        $Disabled_1 = 1;
													else
														$Disabled_1 = 0;

													if($JOBCODEID_1 != '')
													{
				                                    ?>
				                                        <option value="<?php echo "$JOBCODEID_1"; ?>" <?php if($SELECTED == 1) { ?> selected <?php } if($Disabled_1 == 1) {?> disabled <?php } ?> title="<?php echo $JOBDESC_1; ?>">
				                                            <?php
				                                                echo "$space_level_1 $JOBCODEID_1 : $JOBDESC_1";
				                                            ?>
				                                        </option>
				                                    <?php
													}
				                                endforeach;*/
				                            ?>
				                        </select>
				                    </div>
				                </div>
				                <div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Supplier; ?></label>
				                    <div class="col-sm-9">
				                    	<select name="SPLCODE" id="SPLCODE" class="form-control select2">
				                          <option value="none">---</option>
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
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Notes; ?></label>
				                    <div class="col-sm-9">
				                    	<textarea name="OPNH_NOTE" id="OPNH_NOTE" class="form-control" style="height: 70px" cols="30"><?php echo $OPNH_NOTE; ?></textarea>
				                    </div>
				                </div>
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $ApproverNotes; ?></label>
				                    <div class="col-sm-9">
				                    	<textarea name="OPNH_NOTE2" class="form-control" id="OPNH_NOTE2" cols="30" disabled><?php echo $OPNH_NOTE2; ?></textarea>
				                    </div>
				                </div>
				            </div>
				        </div>
				    </div>
					<div class="col-md-6">
						<div class="box box-warning">
							<div class="box-header with-border">
								<i class="fa fa-dollar"></i>
								<h3 class="box-title">Informasi Keuangan</h3>
							</div>
							<div class="box-body">
				                <!-- SEMUA YANG BEHUBUNGAN DENGAN ANGKA DI HEADER, DI HIDE, INFO PAK DIKKA DAN TASK REQUEST MS.201961900016 -->
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?=$SPKCost?></label>
				                    <div class="col-sm-9">
				                        <input type="hidden" class="form-control" style="max-width:200px; text-align:right" name="OPNH_AMOUNT" id="OPNH_AMOUNT" value="<?php echo $OPNH_AMOUNT; ?>" >
				                        <input type="text" class="form-control" style="text-align:right" name="OPNH_AMOUNTX" id="OPNH_AMOUNTX" value="<?php echo number_format($OPNH_AMOUNT, 2); ?>" onBlur="AmountTotal(this)" onKeyPress="return isIntOnlyNew(event);" >
				                        <!-- <label>
				                            <input type="hidden" class="form-control" style="max-width:200px; text-align:right;" name="PROG_BEF" id="PROG_BEF" value="<?php //echo $PROG_BEF; ?>" disabled>
				                            <input type="hidden" class="form-control" style="max-width:200px; text-align:right;" id="AKUM_PROG" value="<?php //echo number_format($AKUM_PROG, $decFormat); ?>" disabled>
				                            <input type="text" class="form-control" style="max-width:200px; text-align:right;" id="PROG_BEF1" value="<?php //echo number_format($PROG_BEF, $decFormat); ?>" disabled>
				                    	</label> -->
				                    </div>
				                </div>
				                <div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label">PPn - Pot. PPh</label>
				                    <div class="col-sm-6">
				                    	<label>
				                            <input type="hidden" class="form-control" style="max-width:200px; text-align:right;" name="OPNH_AMOUNTPPNP" id="OPNH_AMOUNTPPNP" value="<?php echo $OPNH_AMOUNTPPNP; ?>">
				                            <input type="text" class="form-control" style="max-width:70px; text-align:right;" name="OPNH_AMOUNTPPNPX" id="OPNH_AMOUNTPPNPX" value="<?php echo number_format($OPNH_AMOUNTPPNP, $decFormat); ?>" onBlur="getPPnPer()">
				                        </label>
				                        <label>
				                            <input type="hidden" class="form-control" style="max-width:150px; text-align:right" name="OPNH_AMOUNTPPN" id="OPNH_AMOUNTPPN" value="<?php echo $OPNH_AMOUNTPPN; ?>" >
				                        	<input type="text" class="form-control" style="max-width:160px; text-align:right" name="OPNH_AMOUNTPPNX" id="OPNH_AMOUNTPPNX" value="<?php echo number_format($OPNH_AMOUNTPPN, 2); ?>" onBlur="getPPnVal(this)" onKeyPress="return isIntOnlyNew(event);" disabled >
				                        </label>
				                    </div>
				                    <div class="col-sm-3">
			                            <input type="text" class="form-control" style="text-align:right;" name="OPNH_AMOUNTPPHX" id="OPNH_AMOUNTPPHX" value="<?php echo number_format($OPNH_AMOUNTPPH, 2); ?>" disabled >
			                            <input type="hidden" class="form-control" style="max-width:200px; text-align:right" name="OPNH_AMOUNTPPHP" id="OPNH_AMOUNTPPHP" value="<?php echo $OPNH_AMOUNTPPHP; ?>">
			                            <input type="hidden" class="form-control" style="max-width:200px; text-align:right" name="OPNH_AMOUNTPPH" id="OPNH_AMOUNTPPH" value="<?php echo $OPNH_AMOUNTPPH; ?>">
				                        <!-- <label>
				                            <input type="text" class="form-control" style="max-width:200px; text-align:right" name="OPNH_AMOUNTPPHBFX" id="OPNH_AMOUNTPPHBFX" value="<?php //echo number_format($OPNH_AMOUNTPPHBF, 2); ?>" disabled >
				                            <input type="hidden" class="form-control" style="max-width:200px; text-align:right" name="OPNH_AMOUNTPPHBF" id="OPNH_AMOUNTPPHBF" value="<?php //echo $OPNH_AMOUNTPPHBF; ?>">
				                        </label> -->
				                    </div>
				                </div>
				                <?php
									if($task == 'add')
									{
										// TOTAL HARGA SPK
											$WO_DPPER	= 0;
											$WO_DPVAL	= 0;
											$WO_VALUE	= 0;
											$WO_VALPPN	= 0;
											$WO_VALUET	= 0;
											$sqlGTWO	= "SELECT WO_DPPER, WO_DPVAL, WO_VALUE, WO_VALPPN
															FROM tbl_wo_header WHERE WO_NUM = '$WO_NUMX' AND PRJCODE = '$PRJCODE'";
											$resGTWO	= $this->db->query($sqlGTWO)->result();
											foreach($resGTWO as $rowGTWO) :
												$WO_DPPER	= $rowGTWO->WO_DPPER;
												$WO_DPVAL	= $rowGTWO->WO_DPVAL;
												$WO_VALUE	= $rowGTWO->WO_VALUE;
												$WO_VALPPN	= $rowGTWO->WO_VALPPN;
												$WO_VALUET	= $WO_VALUE + $WO_VALPPN;
											endforeach;

										// CARI SISA OPNAME UNTUK MENGHITUNG PENGEMBALIAN DP
											$TOTWO_AMN	= 0;
											$TOTWO_QTY	= 0;
											$sqlTOT_WO	= "SELECT SUM(A.WO_VOLM * A.ITM_PRICE) AS TOTWOAMOUNT, SUM(A.WO_VOLM) AS TOTWOQTY
															FROM tbl_wo_detail A
															INNER JOIN tbl_wo_header B ON A.WO_NUM = B.WO_NUM
																AND B.PRJCODE = '$PRJCODE'
															WHERE A.WO_NUM = '$WO_NUMX' AND A.PRJCODE = '$PRJCODE'";
											$resTOT_WO		= $this->db->query($sqlTOT_WO)->result();
											foreach($resTOT_WO as $rowTOT_WO) :
												$TOTWO_AMN	= $rowTOT_WO->TOTWOAMOUNT;
												$TOTWO_QTY	= $rowTOT_WO->TOTWOQTY;
											endforeach;

											$TOTOPN_AMN	= 0;
											$TOTOPN_QTY	= 0;
											$sqlTOT_OPN	= "SELECT SUM(A.OPND_VOLM * A.OPND_ITMPRICE) AS TOTOPNAMOUNT,
																SUM(A.OPND_VOLM) AS TOTOPNQTY
															FROM tbl_opn_detail A
															INNER JOIN tbl_opn_header B ON A.OPNH_NUM = B.OPNH_NUM
																AND B.PRJCODE = '$PRJCODE'
															WHERE B.WO_NUM = '$WO_NUMX'
																AND A.PRJCODE = '$PRJCODE' AND B.OPNH_STAT = '3' AND A.OPNH_NUM != '$OPNH_NUM'";
											$resTOT_OPN		= $this->db->query($sqlTOT_OPN)->result();
											foreach($resTOT_OPN as $rowTOT_OPN) :
												$TOTOPN_AMN	= $rowTOT_OPN->TOTOPNAMOUNT;
												$TOTOPN_QTY	= $rowTOT_OPN->TOTOPNQTY;
												if($TOTOPN_AMN == '')
													$TOTOPN_AMN	= 0;
												if($TOTOPN_QTY == '')
													$TOTOPN_QTY	= 0;
											endforeach;
											$REMOPN_AMN	= $TOTWO_AMN - $TOTOPN_AMN;

										// CARI DOKUMEN PEMBAYARAN DP UNTUK SPK INI JIKA ADA
											$WO_DPVAL	= 0;		// NILAI DP
											$WO_DPVALUS	= 0;		// DIGUNAKAN
											$sqlDPV		= "SELECT DP_AMOUNT, DP_AMOUNT_USED
															FROM tbl_dp_header WHERE DP_REFNUM = '$WO_NUMX' AND DP_PAID = 2";
											$resDPV	= $this->db->query($sqlDPV)->result();
											foreach($resDPV as $rowDPV) :
												$WO_DPVAL	= $rowDPV->DP_AMOUNT;
												$WO_DPVALUS	= $rowDPV->DP_AMOUNT_USED;
											endforeach;

											$OPNH_DPPER	= $WO_DPPER;
											//$OPNH_DPVAL	= $WO_DPPER * $WO_VALUET / 100;
											$OPNH_DPVAL	= $WO_DPPER * $REMOPN_AMN / 100;

										// CARI TOTAL PENGEMBALIAN DP
											$OPNH_TDPVAL	= 0;
											$sqlGTOPN		= "SELECT SUM(OPNH_DPVAL) AS TOT_DPVAL
																FROM tbl_opn_header WHERE WO_NUM = '$WO_NUMX'
																AND PRJCODE = '$PRJCODE' AND OPNH_STAT IN (3,6)";
											$resGTOPN		= $this->db->query($sqlGTOPN)->result();
											foreach($resGTOPN as $rowGTOPN) :
												$OPNH_TDPVAL	= $rowGTOPN->TOT_DPVAL;
											endforeach;

										// CARI SISA PENGEMBALIAN DP
											$OPNH_REMP	= $WO_DPVAL - $OPNH_TDPVAL;
											if($OPNH_REMP > $OPNH_DPVAL)
											{
												$OPNH_DPVAL	= $OPNH_DPVAL;
											}
											else
											{
												$OPNH_DPVAL	= $OPNH_REMP;
											}
											if($WO_DPPER == 0)
												$OPNH_DPVAL	= 0;
									}
									else
									{
										$OPNH_DPPER	= $OPNH_DPPER;
										$OPNH_DPVAL	= $OPNH_DPVAL;
									}
								?>
								<div class="form-group">
									<label for="inputName" class="col-sm-3 control-label">DP Pengemb.</label>
									<div class="col-sm-9">
				                        <label>
				                            <input type="hidden" class="form-control" style="max-width:200px; text-align:right;" name="OPNH_DPPER" id="OPNH_DPPER" value="<?php echo $OPNH_DPPER; ?>">
				                            <input type="text" class="form-control" style="max-width:70px; text-align:right;" name="OPNH_DPPERX" id="OPNH_DPPERX" value="<?php echo number_format($OPNH_DPPER, $decFormat); ?>" onBlur="getDPer()">
				                        </label>
				                        <label>
				                            <input type="hidden" class="form-control" style="max-width:200px; text-align:right;" name="OPNH_DPVAL" id="OPNH_DPVAL" value="<?php echo $OPNH_DPVAL; ?>">
				                            <input type="text" class="form-control" style="max-width:160px; text-align:right;" name="OPNH_DPVALX" id="OPNH_DPVALX" value="<?php echo number_format($OPNH_DPVAL, $decFormat); ?>" onBlur="getDPVal(this)" disabled>
				                        </label>
				                        <!-- <label>
				                            <input type="hidden" class="form-control" style="max-width:150px; text-align:right;" name="OPNH_DPVALB" id="OPNH_DPVALB" value="<?php //echo $OPNH_DPVALB; ?>" disabled>
				                            <input type="text" class="form-control" style="max-width:150px; text-align:right;" id="AKUM_DPVAL" value="<?php //echo number_format($AKUM_DPVAL, $decFormat); ?>" disabled>
				                        </label> -->
									</div>
								</div>

				                <script>
									function getPPnPer()
									{
										var PPNPERC		= document.getElementById('OPNH_AMOUNTPPNPX');
										OPNH_PPNPERC	= parseFloat(eval(PPNPERC).value.split(",").join(""));

										var totOPN1	= 0;
										var totRow = document.getElementById('totalrow').value;
										for(i=1;i<=totRow;i++)
										{
											var OPND_ITMTOTAL 	= parseFloat(document.getElementById('data'+i+'OPND_ITMTOTAL').value);
											var OPND_TOTTAX 	= parseFloat(document.getElementById('data'+i+'TAXPRICE1').value);
											totOPN1				= parseFloat(totOPN1 + OPND_ITMTOTAL);
										};

										OPNH_AMOUNT1	= parseFloat(totOPN1);
										OPNH_AMOUNTPPN	= parseFloat(OPNH_PPNPERC * OPNH_AMOUNT1 / 100);

										document.getElementById('OPNH_AMOUNT').value 	= OPNH_AMOUNT1;
										document.getElementById('OPNH_AMOUNTX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPNH_AMOUNT1)), 2));

										document.getElementById('OPNH_AMOUNTPPNP').value 	= OPNH_PPNPERC;
										document.getElementById('OPNH_AMOUNTPPNPX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPNH_PPNPERC)), 2));

										document.getElementById('OPNH_AMOUNTPPN').value 	= OPNH_AMOUNTPPN;
										document.getElementById('OPNH_AMOUNTPPNX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPNH_AMOUNTPPN)), 2));

										countGTotal();
									}

									function getDPer()
									{
										OPNH_DPPER1	= document.getElementById('OPNH_DPPERX');
										OPNH_DPPER	= parseFloat(eval(OPNH_DPPER1).value.split(",").join(""));

										var totOPN1	= 0;
										var totRow = document.getElementById('totalrow').value;
										for(i=1;i<=totRow;i++)
										{
											var OPND_ITMTOTAL 	= parseFloat(document.getElementById('data'+i+'OPND_ITMTOTAL').value);
											var OPND_TOTTAX 	= parseFloat(document.getElementById('data'+i+'TAXPRICE1').value);
											totOPN1				= parseFloat(totOPN1 + OPND_ITMTOTAL);
										};
										OPNH_AMOUNT1	= parseFloat(totOPN1);
										OPNH_DPVAL		= parseFloat(OPNH_DPPER * OPNH_AMOUNT1 / 100);

										document.getElementById('OPNH_AMOUNT').value 	= OPNH_AMOUNT1;
										document.getElementById('OPNH_AMOUNTX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPNH_AMOUNT1)), 2));

										document.getElementById('OPNH_DPPER').value 	= OPNH_DPPER;
										document.getElementById('OPNH_DPPERX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPNH_DPPER)), 2));

										document.getElementById('OPNH_DPVAL').value 	= OPNH_DPVAL;
										document.getElementById('OPNH_DPVALX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPNH_DPVAL)), 2));

										countGTotal();
									}

									function getDPVal(thisVal)
									{
										OPNH_DPVAL	= parseFloat(eval(thisVal).value.split(",").join(""));

										var totOPN1	= 0;
										var totRow = document.getElementById('totalrow').value;
										for(i=1;i<=totRow;i++)
										{
											var OPND_ITMTOTAL 	= parseFloat(document.getElementById('data'+i+'OPND_ITMTOTAL').value);
											var OPND_TOTTAX 	= parseFloat(document.getElementById('data'+i+'TAXPRICE1').value);
											totOPN1				= parseFloat(totOPN1 + OPND_ITMTOTAL);
										};
										OPNH_DPPER		= parseFloat(OPNH_DPVAL / totOPN1 * 100);

										OPNH_AMOUNT1	= parseFloat(totOPN1);

										document.getElementById('OPNH_AMOUNT').value 	= OPNH_AMOUNT1;
										document.getElementById('OPNH_AMOUNTX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPNH_AMOUNT1)), 2));

										document.getElementById('OPNH_DPPER').value 	= OPNH_DPPER;
										document.getElementById('OPNH_DPPERX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPNH_DPPER)), 2));

										document.getElementById('OPNH_DPVAL').value 	= OPNH_DPVAL;
										document.getElementById('OPNH_DPVALX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPNH_DPVAL)), 2));

										countGTotal();
									}

									function AmountTotal(thisVal)
									{
										OPNH_AMOUNT	= parseFloat(eval(thisVal).value.split(",").join(""));
										document.getElementById('OPNH_AMOUNT').value 	= OPNH_AMOUNT;
										document.getElementById('OPNH_AMOUNTX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPNH_AMOUNT)), 2));

										// AKUMULASI TOTAL AWAL
										/*PROG_BEF 	= parseFloat(document.getElementById('PROG_BEF').value);
										AKUM_PROG	= parseFloat(OPNH_AMOUNT) + parseFloat(PROG_BEF);
										document.getElementById('AKUM_PROG').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(AKUM_PROG)), 2));*/
									}

									function getPPnVal(thisVal)
									{
										OPNH_AMOUNTPPN	= parseFloat(eval(thisVal).value.split(",").join(""));

										var totOPN1	= 0;
										var totRow = document.getElementById('totalrow').value;
										for(i=1;i<=totRow;i++)
										{
											var OPND_ITMTOTAL 	= parseFloat(document.getElementById('data'+i+'OPND_ITMTOTAL').value);
											var OPND_TOTTAX 	= parseFloat(document.getElementById('data'+i+'TAXPRICE1').value);
											totOPN1				= parseFloat(totOPN1 + OPND_ITMTOTAL);
										}
										OPNH_POT 		= parseFloat(document.getElementById('OPNH_POT').value);
										OPNH_DPVAL 		= parseFloat(document.getElementById('OPNH_DPVAL').value);
										OPNH_RETAMN 	= parseFloat(document.getElementById('OPNH_RETAMN').value);
										//OPNH_AMOUNT1	= parseFloat(totOPN1) - parseFloat(OPNH_DPVAL) - parseFloat(OPNH_RETAMN) - parseFloat(OPNH_POT) + parseFloat(OPNH_AMOUNTPPN);
										// OPNH_AMOUNT1	= parseFloat(totOPN1) - parseFloat(OPNH_POT);
										// INFO BU AN @07 JAN 2019 TIDAK MEMOTONG HANYA INFO SAJA
										OPNH_AMOUNT1	= parseFloat(totOPN1);

										document.getElementById('OPNH_AMOUNT').value 	= OPNH_AMOUNT1;
										document.getElementById('OPNH_AMOUNTX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPNH_AMOUNT1)), 2));

										document.getElementById('OPNH_AMOUNTPPN').value 	= OPNH_AMOUNTPPN;
										document.getElementById('OPNH_AMOUNTPPNX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPNH_AMOUNTPPN)), 2));

										// AKUMULASI TOTAL AWAL
										/*PROG_BEF 	= parseFloat(document.getElementById('PROG_BEF').value);
										AKUM_PROG	= parseFloat(OPNH_AMOUNT1) + parseFloat(PROG_BEF);
										document.getElementById('AKUM_PROG').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(AKUM_PROG)), 2));*/

										countGTotal();
									}

									function countGTotal()
									{
										AM 		= parseFloat(document.getElementById('OPNH_AMOUNT').value);
										AMPPN 	= parseFloat(document.getElementById('OPNH_AMOUNTPPN').value);
										POTPPH 	= parseFloat(document.getElementById('OPNH_AMOUNTPPH').value);
										DPVAL 	= parseFloat(document.getElementById('OPNH_DPVAL').value);
										RETAMN 	= parseFloat(document.getElementById('OPNH_RETAMN').value);
										POT 	= parseFloat(document.getElementById('OPNH_POT').value);
										console.log('AM ='+AM)
										console.log('AMPPN ='+AMPPN)
										console.log('POTPPH ='+POTPPH)
										console.log('DPVAL ='+DPVAL)
										console.log('RETAMN ='+RETAMN)
										console.log('POT ='+POT)

										OPNH_TOTAMOUNT	= parseFloat(AM) + parseFloat(AMPPN) - parseFloat(DPVAL) - parseFloat(RETAMN) - parseFloat(POT) - parseFloat(POTPPH);

										document.getElementById('OPNH_TOTAMOUNT').value 	= OPNH_TOTAMOUNT;
										document.getElementById('OPNH_TOTAMOUNTX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPNH_TOTAMOUNT)), 2));
									}
								</script>
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label">Retensi</label>
				                    <div class="col-sm-9">
				                    <label>
				                        <input type="hidden" class="form-control" style="max-width:120px; text-align:right" name="OPNH_RETPERC" id="OPNH_RETPERC" value="<?php echo $OPNH_RETPERC; ?>" >
				                        <input type="text" class="form-control" style="max-width:70px; text-align:right" name="OPNH_RETPERCX" id="OPNH_RETPERCX" value="<?php echo number_format($OPNH_RETPERC, 2); ?>" onBlur="getAmountRet1()" onKeyPress="return isIntOnlyNew(event);" >
				                    </label>
				                    <label>
				                        <input type="hidden" class="form-control" style="max-width:120px; text-align:right" name="OPNH_RETAMN" id="OPNH_RETAMN" value="<?php echo $OPNH_RETAMN; ?>" >
				                        <input type="text" class="form-control" style="max-width:160px; text-align:right" name="OPNH_RETAMNX" id="OPNH_RETAMNX" value="<?php echo number_format($OPNH_RETAMN, 2); ?>" onKeyPress="return isIntOnlyNew(event);" disabled >
				                    </label>
				                    <!-- <label>
				                        <input type="hidden" class="form-control" style="max-width:150px; text-align:right" name="OPNH_RETAMNB" id="OPNH_RETAMNB" value="<?php //echo $OPNH_RETAMNB; ?>" onKeyPress="return isIntOnlyNew(event);" disabled >
				                        <input type="text" class="form-control" style="max-width:150px; text-align:right" id="AKUM_RETAMN" value="<?php //echo number_format($AKUM_RETAMN, 2); ?>" title="Akum. Ret." disabled >
				                    </label> -->
				                    </div>
				                </div>

								<?php
									$sqlLO	= "SELECT A.Base_Debet FROM tbl_journaldetail A
												WHERE A.Journal_DK = 'D' AND A.JournalH_Code = '$OPNH_POTREF' LIMIT 1";
									$resLO	= $this->db->query($sqlLO)->result();
									$totRow	= 0;
									$REM_AMOUNT	= 0;
									foreach($resLO as $row) :
										$Base_Debet 	= $row->Base_Debet;

										// CARI SUDAH TEROPNAME
											$TOT_PAID	= 0;
											$sqlTOT	= "SELECT SUM(OPNH_POT) AS TOT_PAID FROM tbl_opn_header
														WHERE OPNH_POTREF = '$OPNH_POTREF'
															AND OPNH_STAT IN (3,6)";
											$resTOT	= $this->db->query($sqlTOT)->result();
											foreach($resTOT as $row) :
												$TOT_PAID 	= $row->TOT_PAID;	// 0
											endforeach;
										$REM_AMOUNT	= $Base_Debet - $TOT_PAID;
									endforeach;
				                ?>
				                <div class="form-group">
									<label for="inputName" class="col-sm-3 control-label">Pot. Lainnya</label>
									<div class="col-sm-5">
			                            <input type="hidden" class="form-control" style="max-width:140px; text-align:right;" name="OPNH_POTREF" id="OPNH_POTREF" value="<?php echo $OPNH_POTREF; ?>">
			                            <input type="text" class="form-control" style="max-width:250px; text-align:right;" name="OPNH_POTREF1" id="OPNH_POTREF1" value="<?php echo $OPNH_POTREF1; ?>" data-placeholder="Kode DP" onClick="getPOTREF();" readonly>
			                            <input type="hidden" class="form-control" style="max-width:140px; text-align:right;" name="OPNH_POTACCID" id="OPNH_POTACCID" value="<?php echo $OPNH_POTACCID; ?>">
									</div>
									<div class="col-sm-4">
			                            <input type="hidden" class="form-control" style="max-width:200px; text-align:right;" name="OPNH_POT" id="OPNH_POT" value="<?php echo $OPNH_POT; ?>">
			                            <input type="text" class="form-control" style="text-align:right;" name="OPNH_POTX" id="OPNH_POTX" value="<?php echo number_format($OPNH_POT, $decFormat); ?>" onBlur="getPOT()">
			                            <input type="hidden" class="form-control" style="max-width:200px; text-align:right;" name="OPNH_REMAIN" id="OPNH_REMAIN" value="<?php echo $REM_AMOUNT; ?>">
									</div>
								</div>
				                <div class="form-group">
									<label for="inputName" class="col-sm-3 control-label">Grand Total</label>
									<div class="col-sm-9">
			                            <input type="hidden" class="form-control" style="max-width:200px; text-align:right;" name="OPNH_TOTAMOUNT" id="OPNH_TOTAMOUNT" value="<?php echo $OPNH_TOTAMOUNT; ?>">
			                            <input type="text" class="form-control" style="text-align:right;" name="OPNH_TOTAMOUNTX" id="OPNH_TOTAMOUNTX" value="<?php echo number_format($OPNH_TOTAMOUNTX, $decFormat); ?>" disabled>
				                        <!-- <label>
				                            <input type="text" class="form-control" style="max-width:200px; text-align:right;" name="OPNH_TOTAMOUNTB" id="OPNH_TOTAMOUNTB" value="<?php //echo number_format($OPNH_TOTAMOUNTB, $decFormat); ?>" disabled>
				                    	</label> -->
									</div>
								</div>
				                <script>
									function getAmountRet1()
									{
										OPNH_RETPERC1	= document.getElementById('OPNH_RETPERCX');
										OPNH_RETPERC	= parseFloat(eval(OPNH_RETPERC1).value.split(",").join(""));

										var totOPN1	= 0;
										var totRow = document.getElementById('totalrow').value;
										for(i=1;i<=totRow;i++)
										{
											var OPND_ITMTOTAL 	= parseFloat(document.getElementById('data'+i+'OPND_ITMTOTAL').value);
											var OPND_TOTTAX 	= parseFloat(document.getElementById('data'+i+'TAXPRICE1').value);
											totOPN1				= parseFloat(totOPN1 + OPND_ITMTOTAL);
										};

										OPNH_AMOUNTPPN 	= parseFloat(document.getElementById('OPNH_AMOUNTPPN').value);
										OPNH_DPVAL 		= parseFloat(document.getElementById('OPNH_DPVAL').value);
										OPNH_POT 		= parseFloat(document.getElementById('OPNH_POT').value);


										//OPNH_AMOUNT1	= parseFloat(totOPN1) - parseFloat(OPNH_DPVAL) - parseFloat(OPNH_RETAMN) - parseFloat(OPNH_POT) + parseFloat(OPNH_AMOUNTPPN);
										// OPNH_AMOUNT1	= parseFloat(totOPN1) - parseFloat(OPNH_POT);
										// INFO BU AN @07 JAN 2019 TIDAK MEMOTONG HANYA INFO SAJA
										OPNH_AMOUNT1	= parseFloat(totOPN1);

										// RETENSI dikalikan terhadap nilai setelah dikurangi potongan
										//OPNH_RETAMN		= parseFloat(OPNH_RETPERC * totOPN1 / 100);
										OPNH_RETAMN		= parseFloat(OPNH_RETPERC * OPNH_AMOUNT1 / 100);

										document.getElementById('OPNH_AMOUNT').value 	= OPNH_AMOUNT1;
										document.getElementById('OPNH_AMOUNTX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPNH_AMOUNT1)), 2));

										document.getElementById('OPNH_RETPERC').value 	= OPNH_RETPERC;
										document.getElementById('OPNH_RETPERCX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPNH_RETPERC)), 2));

										document.getElementById('OPNH_RETAMN').value 	= OPNH_RETAMN;
										document.getElementById('OPNH_RETAMNX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPNH_RETAMN)), 2));

										// AKUMULASI RETENSI
										/*OPNH_RETAMNB	= parseFloat(document.getElementById('OPNH_RETAMNB').value);
										AKUM_RETAMN		= parseFloat(OPNH_RETAMN) + parseFloat(OPNH_RETAMNB);*/
										/*if(OPNH_RETAMN > 0)
											AKUM_RETAMN	= 0.05 * parseFloat(OPNH_AMOUNTB);	// DIAMBIL 5% TOTAL AKUMULASI PEMBAYARAN
										else
											AKUM_RETAMN	= 0;	// DIAMBIL 5% TOTAL AKUMULASI PEMBAYARAN*/

										//document.getElementById('AKUM_RETAMN').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(AKUM_RETAMN)), 2));

										// AKUMULASI TOTAL AWAL
										/*PROG_BEF 	= parseFloat(document.getElementById('PROG_BEF').value);
										AKUM_PROG	= parseFloat(OPNH_AMOUNT1) + parseFloat(PROG_BEF);
										document.getElementById('AKUM_PROG').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(AKUM_PROG)), 2));*/

										countGTotal();
									}

									//function getPOT(thisVal)
									function getPOT()
									{
										OPNH_POT1	= document.getElementById('OPNH_POTX');
										OPNH_POT	= parseFloat(eval(OPNH_POT1).value.split(",").join(""));
										OPNH_REMAIN	= parseFloat(document.getElementById('OPNH_REMAIN').value);
										if(OPNH_POT > OPNH_REMAIN)
										{
											swal('<?php echo $alert3; ?>');
											document.getElementById("OPNH_POT").focus();

											document.getElementById('OPNH_POT').value 	= OPNH_REMAIN;
											document.getElementById('OPNH_POTX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPNH_REMAIN)), 2));
											OPNH_POT	= parseFloat(document.getElementById('OPNH_POT').value);
										}

										var totOPN1	= 0;
										var totRow = document.getElementById('totalrow').value;
										for(i=1;i<=totRow;i++)
										{
											var OPND_ITMTOTAL 	= parseFloat(document.getElementById('data'+i+'OPND_ITMTOTAL').value);
											var OPND_TOTTAX 	= parseFloat(document.getElementById('data'+i+'TAXPRICE1').value);
											totOPN1				= parseFloat(totOPN1 + OPND_ITMTOTAL);
										}

										//OPNH_AMOUNTX	= parseFloat(document.getElementById('OPNH_AMOUNT').value);
										OPNH_AMOUNTX	= parseFloat(totOPN1);
										if(OPNH_POT > OPNH_AMOUNTX)
										{
											swal('<?php echo $alert4; ?>');
											document.getElementById("OPNH_POT").focus();

											document.getElementById('OPNH_POT').value 	= OPNH_AMOUNTX;
											document.getElementById('OPNH_POTX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPNH_AMOUNTX)), 2));
											OPNH_POT	= parseFloat(document.getElementById('OPNH_POT').value);
										}

										OPNH_RETAMN		= parseFloat(document.getElementById('OPNH_RETAMN').value);
										OPNH_DPVAL 		= parseFloat(document.getElementById('OPNH_DPVAL').value);
										OPNH_AMOUNTPPN 	= parseFloat(document.getElementById('OPNH_AMOUNTPPN').value);

										//OPNH_AMOUNT1	= parseFloat(totOPN1) - parseFloat(OPNH_DPVAL) - parseFloat(OPNH_RETAMN) - parseFloat(OPNH_POT) + parseFloat(OPNH_AMOUNTPPN);
										// OPNH_AMOUNT1	= parseFloat(totOPN1) - parseFloat(OPNH_POT);
										// INFO BU AN @07 JAN 2019 TIDAK MEMOTONG HANYA INFO SAJA
										OPNH_AMOUNT1	= parseFloat(totOPN1);

										document.getElementById('OPNH_AMOUNT').value 	= OPNH_AMOUNT1;
										document.getElementById('OPNH_AMOUNTX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPNH_AMOUNT1)), 2));

										document.getElementById('OPNH_POT').value 	= OPNH_POT;
										document.getElementById('OPNH_POTX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPNH_POT)), 2));

										// AKUMULASI TOTAL AWAL
										/*PROG_BEF 	= parseFloat(document.getElementById('PROG_BEF').value);
										AKUM_PROG	= parseFloat(OPNH_AMOUNT1) + parseFloat(PROG_BEF);
										document.getElementById('AKUM_PROG').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(AKUM_PROG)), 2));*/

										getPPnPer();
										getDPer();
										getAmountRet1();
									}
								</script>

								<?php
				                    $url_popdp	= site_url('c_project/c_o180d0bpnm/ll_4p/?id=');
				                ?>
				                <script>
				                    var urlDP = "<?php echo "$url_popdp";?>";
				                    function getPOTREF()
				                    {
										PRJCODE	= document.getElementById("PRJCODE").value;
										SPLCODE	= document.getElementById("SPLCODE").value;
										if(SPLCODE == '')
										{
											swal('<?php echo $alert2; ?>');
											document.getElementById("SPLCODE").focus();
											return false;
										}
				                        title = 'Select Item';
				                        w = 850;
				                        h = 550;

				                        var left = (screen.width/2)-(w/2);
				                        var top = (screen.height/2)-(h/2);
										return window.open(urlDP+PRJCODE+'&SPLCODE='+SPLCODE, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
				                    }

									function add_POT(strItem)
									{
										arrItem = strItem.split('|');
										POT_NUM		= arrItem[0];
										POT_CODE 	= arrItem[1];
										POT_REMAM 	= arrItem[2];
										Acc_Id 		= arrItem[3];
										document.getElementById('OPNH_POTREF').value	= POT_NUM;
										document.getElementById('OPNH_POTREF1').value	= POT_CODE;
										document.getElementById('OPNH_REMAIN').value	= POT_REMAM;
										document.getElementById('OPNH_POTACCID').value	= Acc_Id;

										document.getElementById('OPNH_POT').value 		= POT_REMAM;
										document.getElementById('OPNH_POTX').value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(POT_REMAM)), 2));

										getPOT();
									}
				                </script>
				                <div id="revMemo" class="form-group" <?php if($OPNH_MEMO == '') { ?> style="display:none" <?php } ?>>
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $reviseNotes; ?></label>
				                    <div class="col-sm-9">
				                        <textarea name="OPNH_MEMO" class="form-control" style="max-width:350px;" id="OPNH_MEMO" cols="30" disabled><?php echo $OPNH_MEMO; ?></textarea>
				                    </div>
				                </div>

				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Status; ?></label>
				                    <div class="col-sm-9">
				                    	<input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $OPNH_STAT; ?>">
				                        <select name="OPNH_STAT" id="OPNH_STAT" class="form-control select2" onChange="selStat(this.value)">
											<?php
				                                $disableBtn	= 0;
				                                if($OPNH_STAT == 5 || $OPNH_STAT == 6 || $OPNH_STAT == 9)
				                                {
				                                    $disableBtn	= 1;
				                                }
				                                if($OPNH_STAT != 1 AND $OPNH_STAT != 4)
				                                {
				                                    ?>
				                                        <option value="1"<?php if($OPNH_STAT == 1) { ?> selected <?php } ?> disabled>New</option>
				                                        <option value="2"<?php if($OPNH_STAT == 2) { ?> selected <?php } ?> disabled>Confirm</option>
				                                        <option value="3"<?php if($OPNH_STAT == 3) { ?> selected <?php } ?> disabled>Approve</option>
				                                        <option value="4"<?php if($OPNH_STAT == 4) { ?> selected <?php } ?> disabled>Revising</option>
				                                        <option value="5"<?php if($OPNH_STAT == 5) { ?> selected <?php } ?> disabled>Rejected</option>
				                                        <option value="6"<?php if($OPNH_STAT == 6) { ?> selected <?php } if($disableBtn == 1) {?> disabled <?php } ?>>Closed</option>
				                                        <option value="7"<?php if($OPNH_STAT == 7) { ?> selected <?php } ?> disabled>Waiting</option>
				                                        <option value="9"<?php if($OPNH_STAT == 9) { ?> selected <?php } if($disableBtn == 1) {?> disabled <?php } ?>>Void</option>
				                                    <?php
				                                }
				                                else
				                                {
				                                    ?>
				                                        <option value="1"<?php if($OPNH_STAT == 1) { ?> selected <?php } ?>>New</option>
				                                        <option value="2"<?php if($OPNH_STAT == 2) { ?> selected <?php } ?>>Confirm</option>
				                                    <?php
				                                }
				                                $theProjCode 	= $PRJCODE;
				                                $url_AddItem	= site_url('c_project/c_spk/popupallitem/?id='.$this->url_encryption_helper->encode_url($theProjCode));
				                            ?>
				                        </select>
				                    </div>
				                </div>
				                <script>
									function selStat(thisValue)
									{
										if(thisValue == 9)
										{
											var isUSED	= document.getElementById('isUSED').value;
											if(isUSED > 0)
											{
												swal('<?php echo $alertVOID; ?>'+' <?php echo $DOC_NO; ?>');
												return false;
											}
											else
											{
												document.getElementById('tblClose').style.display 	= '';
												document.getElementById('revMemo').style.display 	= '';
												document.getElementById('OPNH_MEMO').disabled 		= false;
											}
										}
										else if(thisValue == 3)
										{
											document.getElementById('tblClose').style.display 	= 'none';
											document.getElementById('revMemo').style.display 	= 'none';
											document.getElementById('OPNH_MEMO').disabled 		= true;
										}
									}
								</script>
							</div>
						</div>
					</div>
                    <div class="col-md-12">
                        <div class="box box-primary">
	                        <div class="search-table-outter">
	                            <table id="tbl" class="table table-bordered table-striped" width="100%">
		                        	<tr style="background:#CCCCCC">
										<th width="3%" height="25" rowspan="2" style="text-align:left">&nbsp;</th>
										<th width="33%" rowspan="2" style="text-align:center; vertical-align: middle;"><?php echo $ItemName ?> </th>
										<th colspan="4" style="text-align:center"><?php echo $ItemQty; ?> </th>
										<th rowspan="2" style="text-align:center; vertical-align: middle;"><?php echo $Unit ?> </th>
										<th width="24%" rowspan="2" style="text-align:center; vertical-align: middle;"><?php echo $Remarks ?> </th>
		                          	</tr>
		                            <tr style="background:#CCCCCC">
		                              	<th style="text-align:center;">SPK </th>
		                              	<th style="text-align:center;"><?php echo $QtyOpnamed ?> </th>
		                              	<th style="text-align:center">Opname</th>
		                              	<th style="text-align:center">Total</th>
		                            </tr>
		                            <?php
										if($task == 'add' && $WO_NUMX == '')
										{
											$sqlDETC	= "tbl_wo_detail A
																INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
																	AND B.PRJCODE = '$PRJCODE'
															WHERE WO_NUM = '$WO_NUMX'
																AND B.PRJCODE = '$PRJCODE'";
											$resultC 	= $this->db->count_all($sqlDETC);
										}
										elseif($task == 'add' && $WO_NUMX != '')
										{
											$sqlDETWO	= "SELECT A.WO_ID, A.WO_NUM, A.PRJCODE, A.JOBCODEDET, A.JOBCODEID, A.ITM_CODE, A.ITM_UNIT,
																A.WO_VOLM AS OPND_VOLM,
																A.ITM_PRICE AS OPND_ITMPRICE, A.WO_TOTAL AS OPND_ITMTOTAL,
																A.WO_DESC AS OPND_DESC, A.TAXCODE1, A.TAXCODE2, A.TAXPRICE1, A.TAXPRICE2
															FROM tbl_wo_detail A
															WHERE WO_NUM = '$WO_NUMX'
																AND A.PRJCODE = '$PRJCODE'";
											$resDETWO 	= $this->db->query($sqlDETWO)->result();

											$sqlDETC	= "tbl_wo_detail A
															WHERE WO_NUM = '$WO_NUMX'
																AND A.PRJCODE = '$PRJCODE'";
											$resultC 	= $this->db->count_all($sqlDETC);
										}
										else
										{
											if($task == 'edit')
											{
												//*from data
												$sqlDET		= "SELECT A.JOBCODEDET, A.JOBCODEID, A.WO_ID, A.ITM_CODE, A.ITM_UNIT, A.OPND_VOLM,
																	A.OPND_ITMPRICE,
																	A.OPND_ITMTOTAL, A.OPND_DESC, A.TAXCODE1, A.TAXCODE2, A.TAXPRICE1, A.TAXPRICE2,
																	B.WO_NUM, B.PRJCODE
																FROM tbl_opn_detail A
																	INNER JOIN tbl_opn_header B ON A.OPNH_NUM = B.OPNH_NUM
																WHERE
																	A.OPNH_NUM = '$OPNH_NUM'
																	AND B.PRJCODE = '$PRJCODE'";
												$resDETWO = $this->db->query($sqlDET)->result();
												// count data
												$sqlDETC	= "tbl_opn_detail A
																	INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
																WHERE
																	A.OPNH_NUM = '$OPNH_NUM'
																	AND B.PRJCODE = '$PRJCODE'";
												$resultC 	= $this->db->count_all($sqlDETC);
											}
										}

										if($resultC > 0)
										{
											$i		= 0;
											$j		= 0;

											foreach($resDETWO as $row) :
												$currentRow  	= ++$i;
												$WO_ID 			= $row->WO_ID;
												$WO_NUM 		= $row->WO_NUM;
												$PRJCODE 		= $row->PRJCODE;
												$JOBCODEDET		= $row->JOBCODEDET;
												$JOBCODEID 		= $row->JOBCODEID;
												$ITM_CODE 		= $row->ITM_CODE;

												$ITM_NAME 		= '';
												$ACC_ID_UM 		= '';
												$ITM_GROUP 		= '';

												$sqlDETITM		= "SELECT A.ITM_NAME, A.ACC_ID_UM, A.ITM_GROUP
																	FROM tbl_item A
																	WHERE A.ITM_CODE = '$ITM_CODE'
																		AND A.PRJCODE = '$PRJCODE'";
												$resDETITM 		= $this->db->query($sqlDETITM)->result();
												foreach($resDETITM as $detITM) :
													$ITM_NAME 		= $detITM->ITM_NAME;
													$ACC_ID_UM 		= $detITM->ACC_ID_UM;
													$ITM_GROUP 		= $detITM->ITM_GROUP;
												endforeach;

												//$ITM_NAME 		= $row->ITM_NAME;
												//$ACC_ID_UM		= $row->ACC_ID_UM;
												//$ITM_GROUP		= $row->ITM_GROUP;
												$ITM_UNIT 		= $row->ITM_UNIT;
												$OPND_VOLM 		= $row->OPND_VOLM;
												//$OPND_VOLM2 	= $row->OPND_VOLM2;
												$ITM_PRICE 		= $row->OPND_ITMPRICE;
												$OPND_ITMTOTAL	= $row->OPND_ITMTOTAL;
												$TAXCODE1		= $row->TAXCODE1;
												$TAXPRICE1		= $row->TAXPRICE1;
												$TAXCODE2		= $row->TAXCODE2;
												$TAXPRICE2		= $row->TAXPRICE2;
												$OPND_DESC 		= $row->OPND_DESC;
												$itemConvertion	= 1;

												// TOTAL SPK YANG DIPIIH
													$TOTWOAMOUNT	= 0;
													$TOTWOQTY		= 0;
													$sqlTOTWO		= "SELECT SUM(A.WO_VOLM * A.ITM_PRICE) AS TOTWOAMOUNT, 
																		SUM(A.WO_VOLM) AS TOTWOQTY
																		FROM tbl_wo_detail A
																		INNER JOIN tbl_wo_header B ON A.WO_NUM = B.WO_NUM
																			AND B.PRJCODE = '$PRJCODE'
																		WHERE A.WO_NUM = '$WO_NUMX' AND A.ITM_CODE = '$ITM_CODE' AND A.PRJCODE = '$PRJCODE'
																			AND A.JOBCODEID = '$JOBCODEID' AND A.WO_ID = $WO_ID";
													$resTOTWO		= $this->db->query($sqlTOTWO)->result();
													foreach($resTOTWO as $rowTOTWO) :
														$TOTWOAMOUNT	= $rowTOTWO->TOTWOAMOUNT;
														$TOTWOQTY		= $rowTOTWO->TOTWOQTY;
													endforeach;

												// TOTAL OPN APPROVED

													$TOTOPNAMOUNT	= 0;
													$TOTOPNQTY		= 0;
													$sqlTOTOPN		= "SELECT SUM(A.OPND_VOLM * A.OPND_ITMPRICE) AS TOTOPNAMOUNT,
																			SUM(A.OPND_VOLM) AS TOTOPNQTY
																		FROM tbl_opn_detail A
																		INNER JOIN tbl_opn_header B ON A.OPNH_NUM = B.OPNH_NUM
																			AND B.PRJCODE = '$PRJCODE'
																		WHERE B.WO_NUM = '$WO_NUMX' AND A.ITM_CODE = '$ITM_CODE'
																			AND A.PRJCODE = '$PRJCODE'
																			AND A.JOBCODEID = '$JOBCODEID' AND B.OPNH_STAT IN (2,3,6)
																			AND A.OPNH_NUM != '$OPNH_NUM' AND A.WO_ID = $WO_ID";
													$resTOTOPN		= $this->db->query($sqlTOTOPN)->result();
													foreach($resTOTOPN as $rowTOTOPN) :
														$TOTOPNAMOUNT	= $rowTOTOPN->TOTOPNAMOUNT;
														$TOTOPNQTY		= $rowTOTOPN->TOTOPNQTY;
														if($TOTOPNAMOUNT == '')
															$TOTOPNAMOUNT	= 0;
														if($TOTOPNQTY == '')
															$TOTOPNQTY	= 0;
													endforeach;

													// SISA QTY PR
													if($task == 'add')
														$REMOPNQTY		= $TOTWOQTY - $TOTOPNQTY;
													else
														$REMOPNQTY		= $OPND_VOLM;


													$OPND_ITMTOTAL		= $REMOPNQTY * $ITM_PRICE;

													if($TAXCODE1 == 'TAX01')
														$TAXPRICE1 = 0.1 * $OPND_ITMTOTAL;

													if($TAXCODE2 == 'TAX02')
														$TAXPRICE2 = 0.02 * $OPND_ITMTOTAL;
													elseif($TAXCODE2 == 'TAX03')
														$TAXPRICE2 = 0.03 * $OPND_ITMTOTAL;
													elseif($TAXCODE2 == 'TAX04')
														$TAXPRICE2 = 0.04 * $OPND_ITMTOTAL;

													$disableInp 	= 0;
													if($TOTOPNQTY >= $TOTWOQTY)
													{
														$disableInp = 1;
														$REMOPNQTY	= 0;
													}

												// GET HEADER JOB
													$JOBHDESC		= "";
													$sqlHDESC		= "SELECT A.JOBPARENT, B.JOBDESC FROM tbl_joblist_detail A
																		INNER JOIN tbl_joblist B ON A.JOBPARENT = B.JOBCODEID
																			AND B.PRJCODE = '$PRJCODE'
																		WHERE A.JOBCODEID = '$JOBCODEID' AND A.PRJCODE = '$PRJCODE'";
													$resHDESC		= $this->db->query($sqlHDESC)->result();
													foreach($resHDESC as $rowHDESC) :
														$JOBHDESC	= $rowHDESC->JOBDESC;
													endforeach;

												$disRow 			= 1;
												if($OPNH_STAT == 1 || $OPNH_STAT == 4)
												{
													$disRow 		= 0;
												}

												?>
												<tr id="tr_<?php echo $currentRow; ?>">
												<td width="3%" height="25" style="text-align:left">
													<?php
														if($OPNH_STAT == 1)
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
												<td width="33%" style="text-align:left">
													<div style="white-space:nowrap">
													  	<strong><i class='fa fa-cube margin-r-5'></i> <?=$ITM_CODE?> </strong>
												  		<div>
													  		<p class='text-muted' style='margin-left: 20px'>
													  			<?=$ITM_NAME."<br>"?>
													  			<?=$JobName." : ".$JOBHDESC?>
													  		</p>
													  	</div>
												  	</div>
													<input type="hidden" name="data[<?php echo $currentRow; ?>][WO_ID]" id="data<?php echo $currentRow; ?>WO_ID" value="<?php echo $WO_ID; ?>" class="form-control" style="max-width:300px;">
													<input type="hidden" name="data[<?php echo $currentRow; ?>][OPNH_NUM]" id="data<?php echo $currentRow; ?>OPNH_NUM" value="<?php echo $OPNH_NUM; ?>" class="form-control" style="max-width:300px;">
													<input type="hidden" name="data[<?php echo $currentRow; ?>][OPNH_CODE]" id="data<?php echo $currentRow; ?>OPNH_CODE" value="<?php echo $OPNH_CODE; ?>" class="form-control" style="max-width:300px;">
													<input type="hidden" name="data[<?php echo $currentRow; ?>][PRJCODE]" id="data<?php echo $currentRow; ?>PRJCODE" value="<?php echo $PRJCODE; ?>" class="form-control" >
													<input type="hidden" name="data[<?php echo $currentRow; ?>][JOBCODEDET]" id="data<?php echo $currentRow; ?>JOBCODEDET" value="<?php echo $JOBCODEDET; ?>" class="form-control" >
													<input type="hidden" name="data[<?php echo $currentRow; ?>][JOBCODEID]" id="data<?php echo $currentRow; ?>JOBCODEID" value="<?php echo $JOBCODEID; ?>" class="form-control" >
													<input type="hidden" id="data<?php echo $currentRow; ?>ITM_CODE" name="data[<?php echo $currentRow; ?>][ITM_CODE]" value="<?php echo $ITM_CODE; ?>" class="form-control" style="max-width:300px;">
													<input type="hidden" id="data<?php echo $currentRow; ?>ACC_ID_UM" name="data[<?php echo $currentRow; ?>][ACC_ID_UM]" value="<?php echo $ACC_ID_UM; ?>" class="form-control" style="max-width:300px;">
													<input type="hidden" id="data<?php echo $currentRow; ?>ITM_UNIT" name="data[<?php echo $currentRow; ?>][ITM_UNIT]" value="<?php echo $ITM_UNIT; ?>" class="form-control" style="max-width:300px;" >
													<input type="hidden" id="data<?php echo $currentRow; ?>ITM_GROUP" name="data[<?php echo $currentRow; ?>][ITM_GROUP]" value="<?php echo $ITM_GROUP; ?>" class="form-control" style="max-width:300px;" >
													<!-- Item Name -->
												</td>
												
												<td width="11%" style="text-align:right"> <!-- SPK Qty -->
													<?php echo number_format($TOTWOQTY, $decFormat); ?>
													<input type="hidden" class="form-control" style="min-width:100px; max-width:300px; text-align:right" name="TOTWOQTYx<?php echo $currentRow; ?>" id="TOTWOQTYx<?php echo $currentRow; ?>" value="<?php echo number_format($TOTWOQTY, $decFormat); ?>" disabled >
												  	<input type="hidden" style="text-align:right" name="TOTWOQTY<?php echo $currentRow; ?>" id="TOTWOQTY<?php echo $currentRow; ?>" value="<?php echo $TOTWOQTY; ?>" >
												  	<input type="hidden" style="text-align:right" name="TOTWOAMOUNT<?php echo $currentRow; ?>" id="TOTWOAMOUNT<?php echo $currentRow; ?>" value="<?php echo $TOTWOAMOUNT; ?>" >
											  </td>
											  <td width="11%" style="text-align:right"> <!-- Opname Approved Qty-->
													<?php echo number_format($TOTOPNQTY, $decFormat); ?>
													<input type="hidden" class="form-control" style="text-align:right" name="TOTOPNQTY<?php echo $currentRow; ?>" id="TOTOPNQTY<?php echo $currentRow; ?>" value="<?php print $TOTOPNQTY; ?>" >
													<input type="hidden" class="form-control" style="text-align:right" name="TOTOPNAMOUNT<?php echo $currentRow; ?>" id="TOTOPNAMOUNT<?php echo $currentRow; ?>" value="<?php print $TOTOPNAMOUNT; ?>" >
													<input type="hidden" class="form-control" style="min-width:110px; max-width:300px; text-align:right" name="TOTOPNQTYx<?php echo $currentRow; ?>" id="TOTOPNQTYx<?php echo $currentRow; ?>" value="<?php print number_format($TOTOPNQTY, $decFormat); ?>" disabled >
											 </td>
											 <td width="11%" style="text-align:right"> <!-- Opname Now -->
												<?php if($disRow == 0) { ?>
												  	<input type="text" name="OPND_VOLM<?php echo $currentRow; ?>" id="OPND_VOLM<?php echo $currentRow; ?>" value="<?php echo number_format($REMOPNQTY, 2); ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getOPN_AMN(this,<?php echo $currentRow; ?>);">
												<?php } else { ?>
													<?php echo number_format($REMOPNQTY, 2); ?>
												  	<input type="hidden" name="OPND_VOLM<?php echo $currentRow; ?>" id="OPND_VOLM<?php echo $currentRow; ?>" value="<?php echo number_format($REMOPNQTY, 2); ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getOPN_AMN(this,<?php echo $currentRow; ?>);">
												<?php } ?>

												<input type="hidden" name="data[<?php echo $currentRow; ?>][OPND_VOLM]" id="data<?php echo $currentRow; ?>OPND_VOLM" value="<?php echo $REMOPNQTY; ?>" class="form-control" style="max-width:300px;" >
												<input type="hidden" name="data[<?php echo $currentRow; ?>][OPND_ITMPRICE]" id="data<?php echo $currentRow; ?>OPND_ITMPRICE" value="<?php echo $ITM_PRICE; ?>" class="form-control" style="max-width:300px;" >
												<input type="hidden" name="data[<?php echo $currentRow; ?>][TAXCODE1]" id="data<?php echo $currentRow; ?>TAXCODE1" value="<?php echo $TAXCODE1; ?>" class="form-control" style="max-width:300px;">
												<input type="hidden" name="data[<?php echo $currentRow; ?>][TAXPRICE1]" id="data<?php echo $currentRow; ?>TAXPRICE1" value="<?php echo $TAXPRICE1; ?>" class="form-control" style="max-width:300px;">
												<input type="hidden" name="data[<?php echo $currentRow; ?>][TAXCODE2]" id="data<?php echo $currentRow; ?>TAXCODE2" value="<?php echo $TAXCODE2; ?>" class="form-control" style="max-width:300px;">
												<input type="hidden" name="data[<?php echo $currentRow; ?>][TAXPRICE2]" id="data<?php echo $currentRow; ?>TAXPRICE2" value="<?php echo $TAXPRICE2; ?>" class="form-control" style="max-width:300px;">
												<input type="hidden" style="text-align:right" name="itemConvertion<?php echo $currentRow; ?>" id="itemConvertion<?php echo $currentRow; ?>" value="<?php echo $itemConvertion; ?>" >
											</td>
											<td width="4%" style="text-align:center" nowrap>
												<?php if($disRow == 0) { ?>
													<input type="text" name="OPND_ITMTOTAL<?php echo $currentRow; ?>" id="OPND_ITMTOTAL<?php echo $currentRow; ?>" value="<?php echo number_format($OPND_ITMTOTAL, $decFormat); ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getOPN_VOLM(this.value,<?php echo $currentRow; ?>);" <?php if($disableInp == 1) { ?> disabled <?php } ?>>
												<?php } else { ?>
													<?php echo number_format($OPND_ITMTOTAL, 2); ?>
													<input type="hidden" name="OPND_ITMTOTAL<?php echo $currentRow; ?>" id="OPND_ITMTOTAL<?php echo $currentRow; ?>" value="<?php echo number_format($OPND_ITMTOTAL, $decFormat); ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getOPN_VOLM(this.value,<?php echo $currentRow; ?>);" <?php if($disableInp == 1) { ?> disabled <?php } ?>>
												<?php } ?>
												
												<input type="hidden" name="data[<?php echo $currentRow; ?>][OPND_ITMTOTAL]" id="data<?php echo $currentRow; ?>OPND_ITMTOTAL" value="<?php echo $OPND_ITMTOTAL; ?>" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" >
		                                    </td>
											<td width="4%" style="text-align:center" nowrap>
												  	<?php echo $ITM_UNIT; ?>
													<!-- Item Unit Type -- ITM_UNIT -->
		                                    </td>
											<td width="24%" style="text-align:left;">
												<?php if($disRow == 0) { ?>
													<input type="text" name="data[<?php echo $currentRow; ?>][OPND_DESC]" id="data<?php echo $currentRow; ?>OPND_DESC" size="20" value="<?php print $OPND_DESC; ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:left">
												<?php } else { ?>
													<?php echo $OPND_DESC; ?>
													<input type="hidden" name="data[<?php echo $currentRow; ?>][OPND_DESC]" id="data<?php echo $currentRow; ?>OPND_DESC" size="20" value="<?php print $OPND_DESC; ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:left">
												<?php } ?>
												
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
					<div class="col-md-6">
		                <div class="form-group">
		                	<label for="inputName" class="col-sm-3 control-label">&nbsp;</label>
		                    <div class="col-sm-9">
		                    	<?php
									if($task=='add')
									{
										if($ISCREATE == 1)
										{
											?>
												<button class="btn btn-primary" id="btnSave">
												<i class="fa fa-save"></i></button>&nbsp;
											<?php
										}
									}
									elseif($task=='edit')
									{
										if($OPNH_STAT == 3)
										{
											?>
		                                        <button class="btn btn-primary" style="display:none" id="tblClose">
		                                        <i class="fa fa-save"></i></button>&nbsp;
											<?php
										}
										elseif($OPNH_STAT == 1 || $OPNH_STAT == 4)
										{
											?>
												<button class="btn btn-primary" id="btnSave">
												<i class="fa fa-save"></i></button>&nbsp;
											<?php
										}
									}
									else
									{
										if($OPNH_STAT == 1 && $ISCREATE == 1)
										{
											?>
												<button class="btn btn-primary" id="btnSave">
												<i class="fa fa-save"></i></button>&nbsp;
											<?php
										}
									}
									$backURL	= site_url('c_project/c_o180d0bpnm/gal180d0bopn/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
									echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>');
		                        ?>
		                    </div>
		                </div>
		          	</div>
				</form>
		        <div class="col-md-12">
					<?php
                        $DOC_NUM	= $OPNH_NUM;
                        $sqlCAPPH	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM'";
                        $resCAPPH	= $this->db->count_all($sqlCAPPH);
						$sqlAPP	= "SELECT * FROM tbl_docstepapp WHERE MENU_CODE = '$MenuApp'
									AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE proj_Code = '$PRJCODE_LEV')";
						$resAPP	= $this->db->query($sqlAPP)->result();
						foreach($resAPP as $rowAPP) :
							$MAX_STEP		= $rowAPP->MAX_STEP;
							$APPROVER_1		= $rowAPP->APPROVER_1;
							$APPROVER_2		= $rowAPP->APPROVER_2;
							$APPROVER_3		= $rowAPP->APPROVER_3;
							$APPROVER_4		= $rowAPP->APPROVER_4;
							$APPROVER_5		= $rowAPP->APPROVER_5;
						endforeach;
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
		        </div>
		    </div>

	    	<!-- ============ START MODAL JOBLIST =============== -->
		    	<style type="text/css">
		    		.modal-dialog{
					    position: relative;
					    display: table; /* This is important */ 
					    overflow-y: auto;    
					    overflow-x: auto;
					    width: auto;
					    min-width: 300px;   
					}
		    	</style>
		    	<?php
					$Active1		= "active";
					$Active2		= "";
					$Active1Cls		= "class='active'";
					$Active2Cls		= "";
		    	?>
		        <div class="modal fade" id="mdl_addJList" name='mdl_addJList' tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		            <div class="modal-dialog">
			            <div class="modal-content">
			                <div class="modal-body">
								<div class="row">
							    	<div class="col-md-12">
						              	<ul class="nav nav-tabs">
						                    <li id="li1" <?php echo $Active1Cls; ?>>
						                    	<a href="#itm1" data-toggle="tab"><?php echo $WOList; ?></a>
						                    </li>
						                </ul>
							            <div class="box-body">
							            	<div class="<?php echo $Active1; ?> tab-pane" id="itm2">
		                                        <div class="form-group">
		                                        	<form method="post" name="frmSearch1" id="frmSearch1" action="">
			                                            <table id="examplex" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
			                                                <thead>
			                                                    <tr>
											                        <th width="2%">&nbsp;</th>
								                                    <th width="9%" style="vertical-align:middle; text-align:center" nowrap><?php echo $NoSPK; ?></th>
								                                    <th width="10%" style="vertical-align:middle; text-align:center"><?php echo $Date; ?></th>
								                                    <th width="5%" nowrap style="text-align:center"><?php echo $StartDate; ?></th>
								                                    <th width="7%" nowrap style="text-align:center"><?php echo $EndDate; ?></th>
								                                    <th width="34%" nowrap style="text-align:center"><?php echo $Description; ?></th>
								                                    <th width="11%" nowrap style="text-align:center"><?php echo $BudgetQty; ?></th>
								                                    <th width="12%" nowrap style="text-align:center"><?php echo $QtyOpnamed; ?></th>
								                                    <th width="10%" nowrap style="text-align:center"><?php echo $Remain; ?></th>
											                  	</tr>
			                                                </thead>
			                                                <tbody>
								                            <?php
								                                $i = 0;
								                                $j = 0;
								                                $sqlReqC	= "tbl_wo_header A
								                                                    INNER JOIN  tbl_employee B ON A.WO_CREATER = B.Emp_ID
								                                                    INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
								                                                WHERE A.WO_STAT = 3 AND A.PRJCODE = '$PRJCODE'
								                                                ORDER BY A.WO_NUM ASC";				
								                                $resReqC 	= $this->db->count_all($sqlReqC);
								                            
								                                $sql 		= "SELECT A.WO_NUM, A.WO_CODE, A.WO_DATE, A.WO_STARTD, A.WO_ENDD, A.WO_CREATER, A.WO_APPROVER, 
								                                                    A.JOBCODEID, A.WO_NOTE, A.WO_STAT, A.WO_MEMO,
								                                                    B.First_Name, B.Middle_Name, B.Last_Name,
								                                                    C.PRJCODE, C.PRJNAME
								                                                FROM tbl_wo_header A
								                                                    INNER JOIN  tbl_employee B ON A.WO_CREATER = B.Emp_ID
								                                                    INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
								                                                WHERE A.WO_STAT = 3 AND A.PRJCODE = '$PRJCODE'
								                                                ORDER BY A.WO_NUM ASC";
								                                $viewAllMR 	= $this->db->query($sql)->result();
								                                if($resReqC > 0)
								                                {
								                                    $totRow	= 0;
								                                    foreach($viewAllMR as $row) :
								                                        $WO_NUM 		= $row->WO_NUM;
								                                        $WO_CODE 		= $row->WO_CODE;
								                                        $WO_DATE 		= $row->WO_DATE;
								                                        $WO_STARTD 		= $row->WO_STARTD;
								                                        $WO_ENDD 		= $row->WO_ENDD;
								                                        $WO_CREATER 	= $row->WO_CREATER;
								                                        $WO_STAT 		= $row->WO_STAT;
								                                        $WO_APPROVER 	= $row->WO_APPROVER;
								                                        $WO_NOTE 		= $row->WO_NOTE;
								                                        $PRJCODE		= $row->PRJCODE;
								                                        $PRJNAME		= $row->PRJNAME;
								                                        $First_Name		= $row->First_Name;
								                                        $Middle_Name	= $row->Middle_Name;
								                                        $Last_Name		= $row->Last_Name;
								                                        $compName 		= "$First_Name $Middle_Name $Last_Name";
								                						
								                						// GET TOTAL SPK QTY
								                						$TOTWO			= 0;
								                						$TOTOPN			= 0;
								                						$TOTREM			= 0;
								                						$sqlQtyWO 		= "SELECT SUM(WO_VOLM) AS TOTWO, SUM(OPN_VOLM) AS TOTOPN
								                											FROM tbl_wo_detail
								                											WHERE WO_NUM = '$WO_NUM' AND PRJCODE = '$PRJCODE'";
								                						$resQtyWO 		= $this->db->query($sqlQtyWO)->result();
								                						foreach($resQtyWO as $rowWOQty):
								                							$TOTWO		= $rowWOQty->TOTWO;
								                							$TOTOPN		= $rowWOQty->TOTOPN;
								                							$TOTREM		= $TOTWO - $TOTOPN;
								                						endforeach;
								                                        
								                                        $totRow			= $totRow + 1;
								                                    
								                                        if ($j==1) {
								                                            echo "<tr class=zebra1>";
								                                            $j++;
								                                        } else {
								                                            echo "<tr class=zebra2>";
								                                            $j--;
								                                        }
								                                        ?>
								                                        <td style="text-align:center">
								                                        	<input type="radio" name="chk0" value="<?php echo $WO_NUM;?>|<?php echo $WO_CODE;?>" onClick="pickThis0(this);" />
								                                        </td>
								                                        <td nowrap>
								                                            <a href="javascript:void(null);" onClick="showItem(<?php echo $totRow; ?>)" class="link">
								                                            <?php echo $WO_CODE; ?></a> 
								                                            <input type="hidden" name="WO_NUM<?php echo $totRow; ?>" id="WO_NUM<?php echo $totRow; ?>" value="<?php echo $WO_NUM; ?>" />
								                                        </td>
								                                        <td nowrap><?php echo date('d M Y', strtotime($WO_DATE)); ?></td>
								                                        <td nowrap><?php echo date('d M Y', strtotime($WO_STARTD)); ?></td>
								                                        <td style="text-align:center" nowrap><?php echo date('d M Y', strtotime($WO_ENDD)); ?></td>
								                                        <td><?php echo $WO_NOTE; ?></td>
								                                        <td style="text-align:right"><?php echo number_format($TOTWO, 2); ?></td>
								                                        <td style="text-align:right"><?php echo number_format($TOTOPN, 2); ?></td>
								                                        <td style="text-align:right"><?php echo number_format($TOTREM, 2); ?></td>
								                                        </tr>
								                                    <?php
								                                    endforeach;
								                                }
								                            ?>
			                                                </tbody>
			                                            </table>
                                                    	<button class="btn btn-primary" type="button" id="btnDetail0" name="btnDetail0">
                                                    		<i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?>
                                                    	</button>&nbsp;
                                      					<button type="button" id="idClose0" class="btn btn-danger" data-dismiss="modal">
                                                    		<i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>
                                                    	</button>
		                                            </form>
		                                      	</div>
		                                    </div>
                                      	</div>
                                      	<input type="hidden" name="rowCheck0" id="rowCheck0" value="0">
	                                </div>
	                            </div>
			                </div>
				        </div>
				    </div>
				</div>

				<script type="text/javascript">
					/*$(document).ready(function()
					{
				    	$('#example0').DataTable(
				    	{
					        "processing": true,
					        "serverSide": true,
							//"scrollX": false,
							"autoWidth": true,
							"filter": true,
					        "ajax": "<?php echo site_url('c_comprof/c_am1h0db2/get_AllDataJL/?id='.$PRJCODE)?>",
					        "type": "POST",
							//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
							"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
							"columnDefs": [	{ targets: [0], className: 'dt-body-center' },
											{ "width": "2px", "targets": [0] },
											{ "width": "98px", "targets": [1] }
										  ],
							"language": {
					            "infoFiltered":"",
					            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
					        },
						});
					});*/

					var selectedRows = 0;
					function pickThis0(thisobj) 
					{
						var favorite = [];
						$.each($("input[name='chk0']:checked"), function() {
					      	favorite.push($(this).val());
					    });
					    $("#rowCheck0").val(favorite.length);
					}

					$(document).ready(function()
					{
					   	$("#btnDetail0").click(function()
					    {
							var totChck 	= $("#rowCheck0").val();

							if(totChck == 0)
							{
								swal('<?php echo $alert5; ?>',
								{
									icon: "warning",
								})
								.then(function()
					            {
					                swal.close();
					            });
								return false;
							}

						    $.each($("input[name='chk0']:checked"), function()
						    {
						      	add_header($(this).val());
						    });

						    $('#mdl_addJList').on('hidden.bs.modal', function () {
							    $(this)
								    .find("input,textarea,select")
									    .val('')
									    .end()
								    .find("input[type=checkbox], input[type=radio]")
								       .prop("checked", "")
								       .end();
							});
                        	document.getElementById("idClose0").click()
					    });
					});
				</script>
	    	<!-- ============ END MODAL JOBLIST =============== -->
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
				var dataTarget			= "OPNH_CODE";
				var myProj_code			= "PRJCODE";
				var PRJCODE					= "<?=$PRJCODE?>";
				var Pattern_Code		= "<?=$Pattern_Code?>";
				var PattTable 			= "tbl_opn_header";
				var Pattern_Length	= "<?=$Pattern_Length?>";
				var TRXDATE					= "OPNH_DATE";
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
			$OPNH_STAT 		= $default['OPNH_STAT'];
			$OPNH_CODE 		= $default['OPNH_CODE'];
			if($OPNH_STAT == 1)
			{
				?>
				$(document).ready(function()
				{
					setInterval(function(){getNewCode()}, 1000);
				});

				function getNewCode()
				{
						var task						= "<?=$task?>";
						var dataTarget			= "OPNH_CODE";
						var Manual_Code			= "<?=$OPNH_CODE?>";
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

	function add_header(strItem)
	{
		arrItem 	= strItem.split('|');
		ilvl 		= arrItem[1];

		WO_NUM		= arrItem[0];

		document.getElementById("WO_NUMX").value = WO_NUM;
		document.frmsrch.submitSrch.click();
	}

	function getOPN_AMN(thisVal1, row)
	{
		var decFormat		= document.getElementById('decFormat').value;

		OPND_VOLM			= parseFloat(eval(thisVal1).value.split(",").join(""));
		//itemConvertion		= document.getElementById('itemConvertion'+row).value;
		ITM_PRICE			= parseFloat(document.getElementById('data'+row+'OPND_ITMPRICE').value);// Item Price
		TOTWOQTY			= document.getElementById('TOTWOQTY'+row).value;
		TOTWOAMOUNT			= document.getElementById('TOTWOAMOUNT'+row).value;						// Total SPK Amount
		TOTOPNQTY			= parseFloat(document.getElementById('TOTOPNQTY'+row).value);			// Total Opname Approved Qty
		TOTOPNAMOUNT		= parseFloat(document.getElementById('TOTOPNAMOUNT'+row).value);		// Total Opname Approved Amount

		// OPNAME NOW
			//OPND_VOLM		= eval(thisVal1).value.split(",").join("");								// Opname Now
		OPND_ITMTOTAL	= parseFloat(OPND_VOLM) * parseFloat(ITM_PRICE);						// Total Opname Now

			

		REM_OPN_QTY			= parseFloat(TOTWOQTY) - parseFloat(TOTOPNQTY);
		REM_OPN_AMOUNT		= parseFloat(TOTWOAMOUNT) - parseFloat(TOTOPNAMOUNT);

		if(OPND_VOLM > REM_OPN_QTY)
		{
			REM_OPN_QTYV	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(REM_OPN_QTY)),decFormat));
			swal('Opname Qty is Greater than SPK Qty. Maximum Qty is '+REM_OPN_QTYV);

			// document.getElementById('data'+row+'OPND_VOLM').value 		= REM_OPN_QTY;
			// document.getElementById('OPND_VOLM'+row).value				= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(REM_OPN_QTY)), 2));

			REM_OPN_AMOUNT	= parseFloat(REM_OPN_QTY) * parseFloat(ITM_PRICE);
			// document.getElementById('data'+row+'OPND_ITMTOTAL').value 	= REM_OPN_AMOUNT;
			// document.getElementById('data'+row+'TAXPRICE1').value		= TAXPRICE1;
			// document.getElementById('data'+row+'TAXPRICE2').value		= TAXPRICE2;
			//return false;

			OPND_VOLM 		= REM_OPN_QTY;
			OPND_ITMTOTAL	= REM_OPN_AMOUNT;
		}

		document.getElementById('data'+row+'OPND_VOLM').value 		= OPND_VOLM;
		document.getElementById('OPND_VOLM'+row).value				= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPND_VOLM)), 2));

		document.getElementById('data'+row+'OPND_ITMTOTAL').value 	= OPND_ITMTOTAL;
		document.getElementById('OPND_ITMTOTAL'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPND_ITMTOTAL)),decFormat));
			
		// TAX
		TAXCODE1			= document.getElementById('data'+row+'TAXCODE1').value;					// Tax Code
		TAXPRICE1			= parseFloat(document.getElementById('data'+row+'TAXPRICE1').value);
		TAXCODE2			= document.getElementById('data'+row+'TAXCODE2').value;					// Tax Code
		TAXPRICE2			= parseFloat(document.getElementById('data'+row+'TAXPRICE2').value);
		if(TAXCODE1 != '')
		{
			TAXPRICE1	= parseFloat(OPND_ITMTOTAL) * 0.1;						// Total Opname Tax Now
		}

		if(TAXCODE2 != '')
		{
			if(TAXCODE2 == 'TAX02')
				TAXPRICE2	= parseFloat(OPND_ITMTOTAL) * 0.02;						// Total Opname Tax Now
			else if(TAXCODE2 == 'TAX03')
				TAXPRICE2	= parseFloat(OPND_ITMTOTAL) * 0.03;						// Total Opname Tax Now
			else if(TAXCODE2 == 'TAX04')
				TAXPRICE2	= parseFloat(OPND_ITMTOTAL) * 0.04;						// Total Opname Tax Now
		}

		document.getElementById('data'+row+'TAXPRICE1').value 		= TAXPRICE1;
		document.getElementById('data'+row+'TAXPRICE2').value 		= TAXPRICE2;

		var totOPN1	= 0;
		var totRow = document.getElementById('totalrow').value;
		for(i=1;i<=totRow;i++)
		{
			var OPND_ITMTOTAL 	= parseFloat(document.getElementById('data'+i+'OPND_ITMTOTAL').value);
			var OPND_TOTTAXPPN 	= parseFloat(document.getElementById('data'+i+'TAXPRICE1').value);
			var OPND_TOTTAXPPH 	= parseFloat(document.getElementById('data'+i+'TAXPRICE2').value);
			totOPN1				= parseFloat(totOPN1 + OPND_ITMTOTAL);
		}

		OPNH_DPVAL	= parseFloat(document.getElementById('OPNH_DPVAL').value);
		totOPN		= parseFloat(totOPN1) - parseFloat(OPNH_DPVAL);

		// HASIL MEETING 27 DES 18 DI MS, DP HANYA INFORMASI KECUALI POTONGAN
		OPNH_POTX	= document.getElementById('OPNH_POT').value;
		totOPN		= parseFloat(totOPN1) - parseFloat(OPNH_POTX);

		//swal(OPND_TOTTAXPPH);
		
		document.getElementById('OPNH_AMOUNT').value	= totOPN;
		document.getElementById('OPNH_AMOUNTX').value	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(totOPN)), decFormat));
		document.getElementById('OPNH_AMOUNTPPN').value	= OPND_TOTTAXPPN;
		document.getElementById('OPNH_AMOUNTPPNX').value= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPND_TOTTAXPPN)), decFormat));
		document.getElementById('OPNH_AMOUNTPPH').value	= OPND_TOTTAXPPH;
		document.getElementById('OPNH_AMOUNTPPHX').value= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPND_TOTTAXPPH)), decFormat));

		/*document.getElementById('OPNH_POT').value 		= 0;
		document.getElementById('OPNH_RETPERC').value 	= 0;
		document.getElementById('OPNH_RETAMN').value 	= 0;
		document.getElementById('OPNH_DPPER').value 	= 0;
		document.getElementById('OPNH_DPVAL').value 	= 0;
		document.getElementById('OPNH_AMOUNTPPN').value = 0;
		document.getElementById('OPNH_POTX').value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(0)), decFormat));
		document.getElementById('OPNH_RETPERCX').value	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(0)), decFormat));
		document.getElementById('OPNH_RETAMNX').value	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(0)), decFormat));
		document.getElementById('OPNH_DPPERX').value	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(0)), decFormat));
		document.getElementById('OPNH_DPVALX').value	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(0)), decFormat));
		document.getElementById('OPNH_AMOUNTPPNX').value= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(0)), decFormat));

		var OPNH_RETPERC	= parseFloat(document.getElementById('OPNH_RETPERC').value);
		if(OPNH_RETPERC > 0)
		{
			TOTRETAMN		= parseFloat(OPNH_RETPERC * totOPN) / 100;
		}
		document.getElementById('OPNH_RETAMN').value	= TOTRETAMN;
		document.getElementById('OPNH_RETAMNX').value	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TOTRETAMN)), decFormat));*/

		// AKUMULASI TOTAL AWAL
		// PROG_BEF 	= parseFloat(document.getElementById('PROG_BEF').value);

		// if(isNaN(PROG_BEF) == true)
		// 	PROG_BEF= 0;

		// AKUM_PROG	= parseFloat(totOPN) + parseFloat(PROG_BEF);
		// document.getElementById('AKUM_PROG').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(AKUM_PROG)), 2));

		countGTotal();
	}

	function getOPN_VOLM(thisVal1, row)
	{
		var decFormat		= document.getElementById('decFormat').value;

		OPND_AMN			= parseFloat(eval(thisVal1).value.split(",").join(""));
		//OPND_AMN			= parseFloat(eval(thisVal1));
		//itemConvertion		= document.getElementById('itemConvertion'+row).value;
		ITM_PRICE			= parseFloat(document.getElementById('data'+row+'OPND_ITMPRICE').value);// Item Price
		TOTWOQTY			= document.getElementById('TOTWOQTY'+row).value;
		TOTWOAMOUNT			= document.getElementById('TOTWOAMOUNT'+row).value;						// Total SPK Amount
		TOTOPNQTY			= parseFloat(document.getElementById('TOTOPNQTY'+row).value);			// Total Opname Approved Qty
		TOTOPNAMOUNT		= parseFloat(document.getElementById('TOTOPNAMOUNT'+row).value);		// Total Opname Approved Amount

		// OPNAME NOW
			//OPND_VOLM		= eval(thisVal1).value.split(",").join("");								// Opname Now
			OPND_VOLM		= parseFloat(OPND_AMN) / parseFloat(ITM_PRICE);							// Opname Now
			OPND_ITMTOTAL	= parseFloat(OPND_AMN);													// Total Opname Now
			
		

		REM_OPN_QTY			= parseFloat(TOTWOQTY) - parseFloat(TOTOPNQTY);
		//swal(REM_OPN_QTY);
		REM_OPN_AMOUNT		= parseFloat(TOTWOAMOUNT) - parseFloat(TOTOPNAMOUNT);
		//swal(REM_OPN_AMOUNT);

		if(OPND_VOLM > REM_OPN_QTY)
		{
			REM_OPN_QTYV	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(REM_OPN_QTY)),decFormat));
			swal('Opname Qty is Greater than SPK Qty. Maximum Qty is '+REM_OPN_QTYV);

			// document.getElementById('data'+row+'OPND_VOLM').value 		= REM_OPN_QTY;
			// document.getElementById('OPND_VOLM'+row).value				= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(REM_OPN_QTY)), 2));

			REM_OPN_AMOUNT	= parseFloat(REM_OPN_QTY) * parseFloat(ITM_PRICE);
			//swal(REM_OPN_AMOUNT);
			// document.getElementById('data'+row+'OPND_ITMTOTAL').value 	= REM_OPN_AMOUNT;
			// document.getElementById('data'+row+'TAXPRICE1').value		= TAXPRICE1;
			// document.getElementById('data'+row+'TAXPRICE2').value		= TAXPRICE2;
			//return false;

			OPND_VOLM 		= REM_OPN_QTY;
			OPND_ITMTOTAL 	= REM_OPN_AMOUNT;
		}

		document.getElementById('data'+row+'OPND_VOLM').value 		= OPND_VOLM;
		document.getElementById('OPND_VOLM'+row).value				= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPND_VOLM)), 2));

		document.getElementById('data'+row+'OPND_ITMTOTAL').value 	= OPND_ITMTOTAL;
		document.getElementById('OPND_ITMTOTAL'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPND_ITMTOTAL)),decFormat));


		// TAX
		TAXCODE1			= document.getElementById('data'+row+'TAXCODE1').value;					// Tax Code
		TAXPRICE1			= parseFloat(document.getElementById('data'+row+'TAXPRICE1').value);
		TAXCODE2			= document.getElementById('data'+row+'TAXCODE2').value;					// Tax Code
		TAXPRICE2			= parseFloat(document.getElementById('data'+row+'TAXPRICE2').value);
		if(TAXCODE1 != '')
		{
			TAXPRICE1	= parseFloat(OPND_ITMTOTAL) * 0.1;						// Total Opname Tax Now
		}

		if(TAXCODE2 != '')
		{
			if(TAXCODE2 == 'TAX02')
				TAXPRICE2	= parseFloat(OPND_ITMTOTAL) * 0.02;						// Total Opname Tax Now
			else if(TAXCODE2 == 'TAX03')
				TAXPRICE2	= parseFloat(OPND_ITMTOTAL) * 0.03;						// Total Opname Tax Now
			else if(TAXCODE2 == 'TAX04')
				TAXPRICE2	= parseFloat(OPND_ITMTOTAL) * 0.04;						// Total Opname Tax Now
		}

		document.getElementById('data'+row+'TAXPRICE1').value 		= TAXPRICE1;
		document.getElementById('data'+row+'TAXPRICE2').value 		= TAXPRICE2;

		var totOPN1	= 0;
		var totRow = document.getElementById('totalrow').value;
		for(i=1;i<=totRow;i++)
		{
			var OPND_ITMTOTAL 	= parseFloat(document.getElementById('data'+i+'OPND_ITMTOTAL').value);
			var OPND_TOTTAXPPN 	= parseFloat(document.getElementById('data'+i+'TAXPRICE1').value);
			var OPND_TOTTAXPPH 	= parseFloat(document.getElementById('data'+i+'TAXPRICE2').value);
			totOPN1				= parseFloat(totOPN1 + OPND_ITMTOTAL);
		}

		OPNH_DPVAL	= parseFloat(document.getElementById('OPNH_DPVAL').value);
		totOPN		= parseFloat(totOPN1) - parseFloat(OPNH_DPVAL);

		// HASIL MEETING 27 DES 18 DI MS, DP HANYA INFORMASI KECUALI POTONGAN
		OPNH_POTX	= document.getElementById('OPNH_POT').value;
		totOPN		= parseFloat(totOPN1) - parseFloat(OPNH_POTX);
		
		document.getElementById('OPNH_AMOUNT').value	= totOPN;
		document.getElementById('OPNH_AMOUNTX').value	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(totOPN)), decFormat));
		document.getElementById('OPNH_AMOUNTPPN').value	= OPND_TOTTAXPPN;
		document.getElementById('OPNH_AMOUNTPPNX').value= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPND_TOTTAXPPN)), decFormat));
		document.getElementById('OPNH_AMOUNTPPH').value	= OPND_TOTTAXPPH;
		document.getElementById('OPNH_AMOUNTPPHX').value= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPND_TOTTAXPPH)), decFormat));


		/*document.getElementById('OPNH_POT').value 		= 0;
		document.getElementById('OPNH_RETPERC').value 	= 0;
		document.getElementById('OPNH_RETAMN').value 	= 0;
		document.getElementById('OPNH_DPPER').value 	= 0;
		document.getElementById('OPNH_DPVAL').value 	= 0;
		document.getElementById('OPNH_AMOUNTPPN').value = 0;
		document.getElementById('OPNH_POTX').value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(0)), decFormat));
		document.getElementById('OPNH_RETPERCX').value	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(0)), decFormat));
		document.getElementById('OPNH_RETAMNX').value	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(0)), decFormat));
		document.getElementById('OPNH_DPPERX').value	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(0)), decFormat));
		document.getElementById('OPNH_DPVALX').value	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(0)), decFormat));
		document.getElementById('OPNH_AMOUNTPPNX').value= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(0)), decFormat));

		var OPNH_RETPERC	= parseFloat(document.getElementById('OPNH_RETPERC').value);
		if(OPNH_RETPERC > 0)
		{
			TOTRETAMN		= parseFloat(OPNH_RETPERC * totOPN) / 100;
		}
		document.getElementById('OPNH_RETAMN').value	= TOTRETAMN;
		document.getElementById('OPNH_RETAMNX').value	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TOTRETAMN)), decFormat));*/

		// AKUMULASI TOTAL AWAL
		// PROG_BEF 	= parseFloat(document.getElementById('PROG_BEF').value);

		// if(isNaN(PROG_BEF) == true)
		// 	PROG_BEF= 0;

		// AKUM_PROG	= parseFloat(totOPN) + parseFloat(PROG_BEF);
		// document.getElementById('AKUM_PROG').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(AKUM_PROG)), 2));

		countGTotal();
	}

	function validateInData()
	{
		var totrow 	= document.getElementById('totalrow').value;

		OPNH_STAT	= document.getElementById("OPNH_STAT").value;
		OPNH_STAT	= document.getElementById("OPNH_STAT").value;

		if(OPNH_STAT == 9)
		{
			OPNH_MEMO		= document.getElementById('OPNH_MEMO').value;
			if(OPNH_MEMO == '')
			{
				swal('<?php echo $alert1; ?>');
				document.getElementById('OPNH_MEMO').focus();
				return false;
			}
		}
		document.getElementById('btnSave').style.display 		= 'none';
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

	function RoundNDecimal(X, N)
	{
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