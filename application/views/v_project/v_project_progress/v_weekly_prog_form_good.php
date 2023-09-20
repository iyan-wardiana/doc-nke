<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 6 Mei 2018
 * File Name	= v_weekly_prog_form.php
 * Location		= -
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');

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
	if($Pattern_Position == 'Especially')
	{
		$Pattern_YearAktive = date('Y');
		$Pattern_MonthAktive = date('m');
		$Pattern_DateAktive = date('d');
	}
	$yearC = (int)$Pattern_YearAktive;
	$year = substr($Pattern_YearAktive,2,2);
	$month = (int)$Pattern_MonthAktive;
	$date = (int)$Pattern_DateAktive;

	$this->db->where('Patt_Year', $year);
	//$this->db->where('Patt_Month', $month);
	//$this->db->where('Patt_Date', $date);
	$myCount = $this->db->count_all('tbl_project_progress');
	
	$sql 	= "tbl_project_progress WHERE PRJCODE = '$PRJCODE' AND Patt_Year = $yearC";
	$result = $this->db->count_all($sql);
	$myMax 	= $result+1;
	
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
	$DocNumber 	= "$Pattern_Code$PRJCODE$groupPattern-$lastPatternNumb";
	$PRJP_NUM	= "$DocNumber";
	$PRJP_DATE	= date('m/d/Y');
	$PRJP_DESC	= '';
	$PRJP_STAT	= 1;
	$PRJP_TOT	= 0;
	$Patt_Number= $lastPatternNumb1;
}
else
{	
	$PRJP_NUM	= $default['PRJP_NUM'];
	$DocNumber	= $default['PRJP_NUM'];
	$PRJP_DATE	= $default['PRJP_DATE'];
	$PRJP_DATE	= date('m/d/Y',strtotime($PRJP_DATE));
	$PRJP_DESC	= $default['PRJP_DESC'];
	$PRJCODE	= $PRJCODE;
	$PRJP_STEP	= $default['PRJP_STEP'];
	$PRJP_STEPC	= $default['PRJP_STEP'];	// CURRENT STEP
	$PRJP_TOT	= $default['PRJP_TOT'];
	$PRJP_STAT	= $default['PRJP_STAT'];
	$Patt_Number= $default['Patt_Number'];
}

$getCountPSA		= "tbl_projprogres WHERE proj_Code = '$PRJCODE' AND progress_Type = 3 AND lastStepPS = 1";
$resGetCountPSA		= $this->db->count_all($getCountPSA);
$totPS				= $resGetCountPSA;

if(isset($_POST['submitChgType']))
{
	$isTypeDoc 		= 1;
	$progressType 	= 3;	// 1. Cash Flow, 2. Profit and Loss, 3. Project Progress
	$projCode 		= $_POST['projCode'];
	$PRJP_STEP		= $_POST['PRJP_STEP1'];
	$PRJP_STEPC		= $_POST['PRJP_STEP1'];
	
	// Mencari Last Step Progress Report. Mendeteksinya dari isShow from table tbl_projprogres
	$getCountPS		= "tbl_projprogres WHERE proj_Code = '$projCode' AND progress_Type = $progressType AND lastStepPS = 1";
	$resGetCountPS	= $this->db->count_all($getCountPS);
	
	$lastStepPS		= $resGetCountPS;
	$Prg_Step_L		= $lastStepPS;
	$nextStepPS		= $resGetCountPS + 1;
	if($resGetCountPS > 0)
	{
		$lastStepPS		= $resGetCountPS;
		
		$sqlProjStep	= "SELECT Prg_Step, Prg_Date1, Prg_Date2, Prg_PlanAkum, Prg_Real, Prg_ProjNotes, Prg_PstNotes
							FROM tbl_projprogres 
							WHERE proj_Code = '$projCode' AND progress_Type = $progressType AND Prg_Step = $PRJP_STEP";
		$resProjStep	= $this->db->query($sqlProjStep)->result();		
		foreach($resProjStep as $rowProjStep) :
			$Prg_Step 		= $rowProjStep->Prg_Step;
			$Prg_Date1 		= $rowProjStep->Prg_Date1;
			$Prg_Date2 		= $rowProjStep->Prg_Date2;
			$Prg_PlanAkum	= $rowProjStep->Prg_PlanAkum;
			$Prg_Real		= $rowProjStep->Prg_Real;
			$Prg_ProjNotes 	= $rowProjStep->Prg_ProjNotes;
			$Prg_PstNotes 	= $rowProjStep->Prg_PstNotes;
		endforeach;
	}
	else
	{
		$lastStepPS 	= 1;
		$Prg_Date1 		= $progress_Date;
		$Prg_Date2 		= date('Y-m-d', strtotime('+6 days', strtotime($progress_Date))); // Penambahan tanggal sebanyak 6 hari
		$Prg_ProjNotes 	= '';
		$Prg_PstNotes 	= '';
		$Prg_PlanAkum	= 0;
		$Prg_Real		= 0;
	}
	$lastStepPSP		= $Prg_PlanAkum;
}
else
{
	$isTypeDoc 		= 1;
	$progressType 	= 3;	// 1. Cash Flow, 2. Profit and Loss, 3. Project Progress
	$projCode 		= $PRJCODE;
	
	// Mencari Last Step Progress Report. Mendeteksinya dari isShow from table tbl_projprogres
	$getCountPS		= "tbl_projprogres WHERE proj_Code = '$projCode' AND progress_Type = $progressType AND lastStepPS = 1";
	$resGetCountPS	= $this->db->count_all($getCountPS);
	
	$lastStepPS		= $resGetCountPS;
	$nextStepPS		= $resGetCountPS + 1;
	$PRJP_STEP		= $nextStepPS;
	if($task == 'add')
		$PRJP_STEPC		= $nextStepPS;
	else
		$PRJP_STEPC		= $PRJP_STEPC;
	
	$Prg_Step_L		= $PRJP_STEP-1;
	if($resGetCountPS > 0)
	{
		$lastStepPS		= $resGetCountPS;
		
		$sqlProjStep	= "SELECT Prg_Step, Prg_Date1, Prg_Date2, Prg_PlanAkum, Prg_Real, Prg_ProjNotes, Prg_PstNotes
							FROM tbl_projprogres 
							WHERE proj_Code = '$projCode' AND progress_Type = $progressType AND Prg_Step = $PRJP_STEP";
		$resProjStep	= $this->db->query($sqlProjStep)->result();		
		foreach($resProjStep as $rowProjStep) :
			$Prg_Step 		= $rowProjStep->Prg_Step;
			$Prg_Date1 		= $rowProjStep->Prg_Date1;
			$Prg_Date2 		= $rowProjStep->Prg_Date2;
			$Prg_PlanAkum	= $rowProjStep->Prg_PlanAkum;
			$Prg_Real		= $rowProjStep->Prg_Real;
			$Prg_ProjNotes 	= $rowProjStep->Prg_ProjNotes;
			$Prg_PstNotes 	= $rowProjStep->Prg_PstNotes;
		endforeach;
	}
	else
	{
		$getCountPS		= "tbl_projprogres WHERE proj_Code = '$projCode' AND progress_Type = $progressType";
		$resGetCountPS1	= $this->db->count_all($getCountPS);
		
		// JIKA SUDAH IMPORT S-CURVE
		if($resGetCountPS1 > 0)
		{
			$resGetCountPS	= 0;
			$nextStepPS		= $resGetCountPS + 1;			
		
			$PRJP_STEP		= $nextStepPS;
			if($task == 'add')
				$PRJP_STEPC		= $nextStepPS;
			else
				$PRJP_STEPC		= $PRJP_STEPC;
			
			$Prg_Step_L		= $PRJP_STEP-1;
			
			$lastStepPS		= $resGetCountPS;
			
			$sqlProjStep	= "SELECT Prg_Step, Prg_Date1, Prg_Date2, Prg_PlanAkum, Prg_Real, Prg_ProjNotes, Prg_PstNotes
								FROM tbl_projprogres 
								WHERE proj_Code = '$projCode' AND progress_Type = $progressType AND Prg_Step = $PRJP_STEP";
			$resProjStep	= $this->db->query($sqlProjStep)->result();		
			foreach($resProjStep as $rowProjStep) :
				$Prg_Step 		= $rowProjStep->Prg_Step;
				$Prg_Date1 		= $rowProjStep->Prg_Date1;
				$Prg_Date2 		= $rowProjStep->Prg_Date2;
				$Prg_PlanAkum	= $rowProjStep->Prg_PlanAkum;
				$Prg_Real		= $rowProjStep->Prg_Real;
				$Prg_ProjNotes 	= $rowProjStep->Prg_ProjNotes;
				$Prg_PstNotes 	= $rowProjStep->Prg_PstNotes;
			endforeach;
		}
		else
		{
			$progress_Date 	= date('Y-m-d');
			$lastStepPS 	= 1;
			$Prg_Date1 		= $progress_Date;
			$Prg_Date2 		= date('Y-m-d', strtotime('+6 days', strtotime($progress_Date))); // Penambahan tanggal sebanyak 6 hari
			$Prg_ProjNotes 	= '';
			$Prg_PstNotes 	= '';
			$Prg_PlanAkum	= 0;
			$Prg_Real		= 0;
		}
	}
}
?>