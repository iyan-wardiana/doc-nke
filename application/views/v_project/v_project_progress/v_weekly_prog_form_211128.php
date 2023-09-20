<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 6 Mei 2018
 * File Name	= v_weekly_prog_form.php
 * Location		= -
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody    = $this->session->userdata('appBody');

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
	$PRJP_GTOT	= 0;
	$Patt_Number= $lastPatternNumb1;
}
else
{
	$isSetDocNo = 1;	
	$PRJP_NUM	= $default['PRJP_NUM'];
	$DocNumber	= $default['PRJP_NUM'];
	$PRJP_DATE	= $default['PRJP_DATE'];
	$PRJP_DATE	= date('m/d/Y',strtotime($PRJP_DATE));
	$PRJP_DATE_S= $default['PRJP_DATE_S'];
	$PRJP_DATE_S= date('m/d/Y',strtotime($PRJP_DATE_S));
	$PRJP_DATE_E= $default['PRJP_DATE_E'];
	$PRJP_DATE_E= date('m/d/Y',strtotime($PRJP_DATE_E));
	$PRJP_DESC	= $default['PRJP_DESC'];
	$PRJCODE	= $PRJCODE;
	$PRJP_STEP	= $default['PRJP_STEP'];
	$PRJP_STEPC	= $default['PRJP_STEP'];	// CURRENT STEP
	$PRJP_TOT	= $default['PRJP_TOT'];
	$PRJP_GTOT	= $default['PRJP_GTOT'];
	$PRJP_STAT	= $default['PRJP_STAT'];
	$Patt_Number= $default['Patt_Number'];
}

$sqlCountAll		= "tbl_projprogres WHERE proj_Code = '$PRJCODE' AND progress_Type = 3";
$resCountAll		= $this->db->count_all($sqlCountAll);

$getCountPSA		= "tbl_projprogres WHERE proj_Code = '$PRJCODE' AND progress_Type = 3 AND lastStepPS = 1";
$resGetCountPSA		= $this->db->count_all($getCountPSA);
$totPS				= $resGetCountPSA;

if(isset($_POST['submitChgType']))
{
	$isTypeDoc 		= 1;
	$progressType 	= 3;	// 1. Cash Flow, 2. Profit and Loss, 3. Project Progress
	$projCode 		= $_POST['projCode'];
	$PRJP_STEP_APP	= $totPS + 1;	// Last Step Approve
	$PRJP_STEP		= $_POST['PRJP_STEP1'];
	$PRJP_STEPC		= $_POST['PRJP_STEP1'];
	
	// Mencari Last Step Progress Report. Mendeteksinya dari isShow from table tbl_projprogres
	//$getCountPS	= "tbl_projprogres WHERE proj_Code = '$projCode' AND progress_Type = $progressType AND lastStepPS = 1";
	$getCountPS		= "tbl_projprogres WHERE proj_Code = '$projCode' AND progress_Type = $progressType AND Prg_Step = $PRJP_STEP";
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
	$PRJP_STEP_APP	= $lastStepPS;
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

if($task == 'add')
{
	$Prg_Date1 	= date('m/d/Y', strtotime($Prg_Date1));
	$Prg_Date2 	= date('m/d/Y', strtotime($Prg_Date2));
}
else
{
	$Prg_Date1 	= date('m/d/Y', strtotime($PRJP_DATE_S));
	$Prg_Date2 	= date('m/d/Y', strtotime($PRJP_DATE_E));
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
			if($TranslCode == 'RequestNo')$RequestNo = $LangTransl;
			if($TranslCode == 'SelectJob')$SelectJob = $LangTransl;
			if($TranslCode == 'PR_CODE')$PR_CODE = $LangTransl;
			if($TranslCode == 'Notes')$Notes = $LangTransl;
			if($TranslCode == 'Date')$Date = $LangTransl;
			if($TranslCode == 'Project')$Project = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'SelectAccount')$SelectAccount = $LangTransl;
			if($TranslCode == 'New')$New = $LangTransl;
			if($TranslCode == 'Confirm')$Confirm = $LangTransl;
			if($TranslCode == 'Close')$Close = $LangTransl;		
			if($TranslCode == 'JobCode')$JobCode = $LangTransl;
			if($TranslCode == 'Volume')$Volume = $LangTransl;
			if($TranslCode == 'ProgressNow')$ProgressNow = $LangTransl;
			if($TranslCode == 'Percentation')$Percentation = $LangTransl;
			if($TranslCode == 'Amount')$Amount = $LangTransl;
			if($TranslCode == 'Remarks')$Remarks = $LangTransl;
			if($TranslCode == 'ProjectProgress')$ProjectProgress = $LangTransl;
			if($TranslCode == 'ProgressCode')$ProgressCode = $LangTransl;
			if($TranslCode == 'ProjectProgress')$ProjectProgress = $LangTransl;
			if($TranslCode == 'JournalType')$JournalType = $LangTransl;
			if($TranslCode == 'Date')$Date = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'Tax')$Tax = $LangTransl;
			if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
			if($TranslCode == 'Section')$Section = $LangTransl;
			if($TranslCode == 'StartDate')$StartDate = $LangTransl;
			if($TranslCode == 'Finish')$Finish = $LangTransl;
			if($TranslCode == 'before')$before = $LangTransl;
		endforeach;
		
		if($LangID == 'IND')
		{
			$subTitleH	= "Tambah";
			$subTitleD	= "permintaan pembelian";
			$alert0		= "Masukan deskripsi jurnal transaksi.";
			$alert1		= "Silahkan pilih Nomor Akun.";
			$alert2		= "Silahkan tuliskan deskripsi jurnal.";
			$alert3		= "Tentukan posisi akun; di Debit atau Kredit.";
			$alert4		= "Tentukan posisi jenis transaksi, pajak atau bukan.";
			$alert5		= "Masukan nomor referensi jurnal transaksi.";
			$alert6		= "Transaksi tidak boleh 0.";
			$alert7		= "Belum ada akun untuk sisi Debit.";
			$alert8		= "Belum ada akun untuk sisi Kredit.";
			$alert9		= "Nilai Debit dan Kredit tidak sama.";
			$alert10	= "Nilai progress yang Anda masukan melebihi batas toleransi.";
			$ProgressBefore	= "Prog.<br>Sebelumnya";
			$ProgressNw	= "Saat ini";
			$ProgonPerc	= "Progres (%)";
		}
		else
		{
			$subTitleH	= "Add";
			$subTitleD	= "purchase request";
			$alert0		= "Please input description of journal transaction.";
			$alert1		= "Please select account number.";
			$alert2		= "Please write of journal description";
			$alert3		= "Set the Account Position; Debit or Credit.";
			$alert4		= "Set the type transaction, Tax or Non Tax.";
			$alert5		= "Insert Reference Number of transaction";
			$alert6		= "The amount cen not be zero.";
			$alert7		= "Debit Side transaction can not be empty.";
			$alert8		= "Credit Side transaction can not be empty";
			$alert9		= "Debit dan Credit Amount must be same.";
			$alert10	= "The progress value that you input exceeds the tolerance limit.";
			$ProgressBefore	= "Prev.<br>Progress";
			$ProgressNw	= "Prog. Now :";
			$ProgonPerc	= "Progress (%)";
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
				$sqlC_App	= "tbl_approve_hist WHERE AH_CODE = '$PRJP_NUM'";
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
				//$APPROVE_AMOUNT = $PR_VALUEAPP;
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
				/*$sqlMJREMP	= "SELECT * FROM tbl_major_app";
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
								WHERE PR_NUM = '$PRJP_NUM' 
									AND B.PRJCODE = '$PRJCODE'
									AND B.ISMAJOR = 1";
				$resCMJR = $this->db->count_all($sqlCMJR);*/
			// END : SPECIAL FOR SASMITO
			
			$APP_LEVEL	= $APP_STEP;
		// END : APPROVE PROCEDURE

        $comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
			<h1>
		    <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/progress.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $subTitleH; ?>
		    <small><?php echo $ProjectProgress; ?></small>  </h1>
		  <?php /*?><ol class="breadcrumb">
		    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		    <li><a href="#">Tables</a></li>
		    <li class="active">Data tables</li>
		  </ol><?php */?>
		</section>

		<section class="content">
		    <div class="row">				
		        <form name="frmChangeType" id="frmChangeType" method="post">
		            <input type="hidden" name="projCode" id="projCode" value="<?php echo $PRJCODE; ?>" />
		            <input type="hidden" name="PRJP_STEP1" id="PRJP_STEP1" value="<?php echo $PRJP_STEP; ?>" />
		            <input type="submit" name="submitChgType" id="submitChgType" style="display:none" />
		        </form>
               	<form class="form-horizontal" name="frm" method="post" action="<?php echo $form_action; ?>" enctype="multipart/form-data" onSubmit="return validateInData()">
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
					            <input type="hidden" name="Patt_Number" id="Patt_Number" value="<?php echo $Patt_Number; ?>" />
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
				                <div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $ProgressCode; ?></label>
				                    <div class="col-sm-9">
				                    	<input type="text" class="form-control" style="text-align:left" name="PRJP_NUM1" id="PRJP_NUM1" value="<?php echo $DocNumber; ?>" disabled />
				                    	<input type="hidden" class="form-control" style="max-width:400px;text-align:right" name="PRJP_NUM" id="PRJP_NUM" size="30" value="<?php echo $DocNumber; ?>" />
				                        <input type="hidden" class="form-control" style="max-width:400px;text-align:right" name="PRJCODE" id="PRJCODE" size="30" value="<?php echo $PRJCODE; ?>" />
				                    </div>
				                </div>
				            	<div class="form-group" style="display:none">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Date; ?></label>
				                    <div class="col-sm-9">
				                    	<div class="input-group date">
				                        <div class="input-group-addon">
				                        <i class="fa fa-calendar"></i>&nbsp;</div><input type="text" name="PRJP_DATE" class="form-control pull-left" id="datepicker" value="<?php echo $PRJP_DATE; ?>" style="width:100px"></div>
				                    </div>
				                </div>
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Section; ?></label>
				                    <div class="col-sm-9">
				                        <select name="PRJP_STEP" id="PRJP_STEP" class="form-control select2" style="max-width:70px" onChange="selProgStep(this.value)" >
				                            <?php
				                                if($resCountAll > 0)
				                                {
				                                    for($i=1;$i<=$resCountAll;$i++)
				                                    {
				                                    ?>
				                                        <option value="<?php echo $i; ?>" <?php if($i < $PRJP_STEP_APP) { ?> disabled <?php } if($i == $PRJP_STEPC) { ?> selected <?php } ?>><?php echo $i; ?></option>
				                                    <?php
				                                    }
				                                }
												else
												{
				                                    ?>
				                                        <option value="1">1</option>
				                                    <?php
												}
				                            ?>
				                        </select>                     
				                    </div>
				                </div>
								<script>
				                    function selProgStep(stepVal)
				                    {
				                        document.getElementById('PRJP_STEP1').value = stepVal;
				                        
				                        document.getElementById('submitChgType').click();
				                    }
				                </script>
				            	<div class="form-group" style="display: none;">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Date; ?></label>
				                    <div class="col-sm-9">
				                    	<input type="hidden" name="PRJP_DATE" class="form-control pull-left" id="datepicker" value="<?php echo $PRJP_DATE; ?>" style="width:106px">
				                        <input type="text" class="form-control" style="max-width:100px;text-align:left" name="PRJP_NUM1" id="PRJP_NUM1" value="<?php  $date1 = new DateTime($Prg_Date1);
											$date1 = new DateTime($Prg_Date1);
				                            echo $date1->format('d M Y');
				                            //echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; to &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
				                            echo "&nbsp;&nbsp; - &nbsp;&nbsp;";
				                            $date2 = new DateTime($Prg_Date2);
				                            echo $date2->format('d M Y'); ?>" disabled />
				                    </div>
				                </div>
		                      	<div class="form-group">
									<label for="inputName" class="col-sm-3 control-label"><?php echo $StartDate ?></label>
									<div class="col-sm-3">
										<div class="input-group date">
											<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
											<input type="text" name="Start_Date" class="form-control pull-left" id="datepicker2" value="<?php echo $Prg_Date1; ?>" style="width:100px" >
										</div>
									</div>
		                            <div class="col-sm-2">
		                            	<label for="inputName" class="col-sm-12 control-label"><?php echo $Finish; ?></label>
		                            </div>
		                            <div class="col-sm-3">
		                              	<div class="input-group date">
		                                  	<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
		                                  	<input type="text" name="End_Date" class="form-control pull-left" id="datepicker3" value="<?php echo $Prg_Date2; ?>" style="width:100px" >
		                                </div>
		                            </div>
		                        </div>
				            	<div class="form-group" style="display: none;">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Project; ?></label>
				                    <div class="col-sm-9">
				                    	<select name="PRJCODE" id="PRJCODE" class="form-control" disabled>
				                          <option value="none">--- None ---</option>
				                          <?php
										  	$STEP_CURR		= $PRJP_STEPC;
										  	$theProjCode 	= $PRJCODE;
				                        	$url_AddItem	= site_url('c_project/c_uPpR09r355/puSA0b28t18/?id=');
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
				                    </div>
				                </div>
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Description; ?></label>
				                    <div class="col-sm-9">
				                    	<textarea name="PRJP_DESC" class="form-control" id="PRJP_DESC" cols="30" placeholder="<?php echo $Description; ?>" style="height: 75px"><?php echo $PRJP_DESC; ?></textarea>
				                    </div>
				                </div>
					        </div>
					    </div>
					</div>
					<div class="col-md-6">
						<div class="box box-warning">
							<div class="box-header with-border">
								<i class="fa fa-bar-chart"></i>
								<h3 class="box-title"><?php echo $mnName; ?></h3>
							</div>
							<div class="box-body">
				                <?php
									$PRJP_TOTBEF	= 0;
									/*$sqlTOTBEF	= "SELECT PRJP_TOT FROM tbl_project_progress 
														WHERE PRJCODE = '$PRJCODE' AND PRJP_STEP = $Prg_Step_L";*/
									$sqlTOTBEF		= "SELECT SUM(PRJP_TOT) AS PRJP_TOT FROM tbl_project_progress
														WHERE PRJCODE = '$PRJCODE' AND PRJP_STEP < $STEP_CURR";
									$resTOTBEF		= $this->db->query($sqlTOTBEF)->result();		
									foreach($resTOTBEF as $rowTOTBEF) :
										$PRJP_TOTBEF= $rowTOTBEF->PRJP_TOT;
									endforeach;
									
									$PRJP_GTOT	= $PRJP_TOTBEF + $PRJP_TOT;
								?>
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $before; ?></label>
				                    <div class="col-sm-3">
				                    	<input type="text" class="form-control" style="text-align:right" name="PRJP_TOTBEFX" id="PRJP_TOTBEFX" value="<?php echo number_format($PRJP_TOTBEF, 4); ?>" disabled />
				                    </div>
				                    <div class="col-sm-3">
				                    	<label for="inputName" class="control-label pull-right"><?php echo $ProgressNw; ?></label>
				                    </div>
				                    <div class="col-sm-3">
				                    	<input type="text" class="form-control" style="text-align:right" name="PRJP_TOTX" id="PRJP_TOTX" value="<?php echo number_format($PRJP_TOT, 4); ?>" disabled />
				                        <input type="hidden" class="form-control" style="max-width:180px;text-align:left" name="PRJP_TOT" id="PRJP_TOT" size="30" value="<?php echo $PRJP_TOT; ?>" />
				                    </div>
				                </div>
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $ProjectProgress; ?></label>
				                    <div class="col-sm-3">
				                    	<input type="text" class="form-control" style="text-align:right" name="PRJP_GTOTX" id="PRJP_GTOTX" value="<?php echo number_format($PRJP_GTOT, 4); ?>" disabled />
				                        <input type="hidden" class="form-control" style="max-width:180px;text-align:left" name="PRJP_GTOT" id="PRJP_GTOT" size="30" value="<?php echo $PRJP_GTOT; ?>" />
				                    </div>
				                    <?php
				                    	//$PRJPRGS 	= $PRJP_GTOT * 100;
				                    	$PRJPRGS 	= $PRJP_GTOT;
				                    ?>
				                    <div class="col-sm-3">
				                    	<label for="inputName" class="control-label pull-right"><?php echo $ProgonPerc; ?></label>
				                    </div>
				                    <div class="col-sm-3">
				                    	<input type="text" class="form-control" style="text-align:right" name="PRJPRGS" id="PRJPRGS" value="<?php echo number_format($PRJPRGS, 4); ?>" disabled />
				                    </div>
				                </div>
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Status; ?></label>
				                    <div class="col-sm-9">
				                    	<input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $PRJP_STAT; ?>">
										<?php
											// START : FOR ALL APPROVAL FUNCTION
												$disButton	= 0;
												$disButtonE	= 0;
												if($task == 'add')
												{
													if($ISCREATE == 1 || $ISAPPROVE == 1)
													{
														?>
															<select name="PRJP_STAT" id="PRJP_STAT" class="form-control select2" >
																<option value="1">New</option>
																<option value="2">Confirm</option>
															</select>
														<?php
													}
												}
												else
												{
													$disButton	= 1;
													if(($ISCREATE == 1 && $ISAPPROVE == 1))
													{
														if($PRJP_STAT == 1 || $PRJP_STAT == 4)
														{
															$disButton	= 0;
															?>
																<select name="PRJP_STAT" id="PRJP_STAT" class="form-control select2" >
																	<option value="1">New</option>
																	<option value="2">Confirm</option>
																</select>
															<?php
														}
														elseif($PRJP_STAT == 2 || $PRJP_STAT == 3 || $PRJP_STAT == 7)
														{
															$disButton	= 0;
															if($canApprove == 0)
																$disButton	= 1;
															
															$sqlCAPPHE	= "tbl_approve_hist WHERE AH_CODE = '$PRJP_NUM' AND AH_APPROVER = '$DefEmp_ID'";
															$resCAPPHE	= $this->db->count_all($sqlCAPPHE);
															if($resCAPPHE > 0)
																$disButton	= 1;
														
															?>
																<select name="PRJP_STAT" id="PRJP_STAT" class="form-control select2" <?php if($disButton == 1) { ?> disabled <?php } ?>>
																	<option value="1"<?php if($PRJP_STAT == 1) { ?> selected <?php } ?> disabled >New</option>
																	<option value="2"<?php if($PRJP_STAT == 2) { ?> selected <?php } ?> >Confirm</option>
																	<option value="3"<?php if($PRJP_STAT == 3) { ?> selected <?php } ?> >Approved</option>
																	<option value="4"<?php if($PRJP_STAT == 4) { ?> selected <?php } ?> >Revising</option>
																	<option value="5"<?php if($PRJP_STAT == 5) { ?> selected <?php } ?> >Rejected</option>
																	<!-- <option value="6"<?php if($PRJP_STAT == 6) { ?> selected <?php } ?>>Closed</option> -->
																	<!-- <option value="7"<?php if($PRJP_STAT == 7) { ?> selected <?php } ?> >Waiting</option>
																	<?php if($PRJP_STAT == 3 || $PRJP_STAT == 9) { ?> -->
																	<!-- <option value="9"<?php if($PRJP_STAT == 9) { ?> selected <?php } ?>>Void</option>
																	<?php } ?> -->
																</select>
															<?php
														}
													}
													elseif($ISAPPROVE == 1)
													{
														if($PRJP_STAT == 1 || $PRJP_STAT == 4)
														{
															$disButton	= 1;
															?>
																<select name="PRJP_STAT" id="PRJP_STAT" class="form-control select2" <?php if($disButton == 1) { ?> disabled <?php } ?>>
																	<option value="1">New</option>
																	<option value="2">Confirm</option>
																</select>
															<?php
														}
														else if($PRJP_STAT == 2 || $PRJP_STAT == 3 || $PRJP_STAT == 7)
														{
															$disButton	= 0;
															if($canApprove == 0)
																$disButton	= 1;
																
															$sqlCAPPHE	= "tbl_approve_hist WHERE AH_CODE = '$PRJP_NUM' AND AH_APPROVER = '$DefEmp_ID'";
															$resCAPPHE	= $this->db->count_all($sqlCAPPHE);
															if($resCAPPHE > 0)
																$disButton	= 1;
														
															?>
																<select name="PRJP_STAT" id="PRJP_STAT" class="form-control select2" <?php if($disButton == 1) { ?> disabled <?php } ?>>
																	<option value="1"<?php if($PRJP_STAT == 1) { ?> selected <?php } ?> disabled >New</option>
																	<option value="2"<?php if($PRJP_STAT == 2) { ?> selected <?php } ?> >Confirm</option>
																	<option value="3"<?php if($PRJP_STAT == 3) { ?> selected <?php } ?> >Approved</option>
																	<option value="4"<?php if($PRJP_STAT == 4) { ?> selected <?php } ?> >Revising</option>
																	<option value="5"<?php if($PRJP_STAT == 5) { ?> selected <?php } ?> >Rejected</option>
																	<option value="6"<?php if($PRJP_STAT == 6) { ?> selected <?php } ?> style="display:none" >Closed</option>
																	<option value="7"<?php if($PRJP_STAT == 7) { ?> selected <?php } ?> >Waiting</option>
																	<?php if($PRJP_STAT == 3 || $PRJP_STAT == 9) { ?>
																	<option value="9"<?php if($PRJP_STAT == 9) { ?> selected <?php } ?>>Void</option>
																	<?php } ?>
																</select>
															<?php
														}
													}
													elseif($ISCREATE == 1)
													{
														if($PRJP_STAT == 1 || $PRJP_STAT == 4)
														{
															$disButton	= 0;
															?>
																<select name="PRJP_STAT" id="PRJP_STAT" class="form-control select2" <?php if($disButton == 1) { ?> disabled <?php } ?>>
																	<option value="1"<?php if($PRJP_STAT == 1) { ?> selected <?php } ?> >New</option>
																	<option value="2"<?php if($PRJP_STAT == 2) { ?> selected <?php } ?> >Confirm</option>
																	<option value="3"<?php if($PRJP_STAT == 3) { ?> selected <?php } ?> disabled >Approved</option>
																	<option value="4"<?php if($PRJP_STAT == 4) { ?> selected <?php } ?> disabled >Revising</option>
																	<option value="5"<?php if($PRJP_STAT == 5) { ?> selected <?php } ?> disabled >Rejected</option>
																	<option value="6"<?php if($PRJP_STAT == 6) { ?> selected <?php } ?> style="display:none" >Closed</option>
																	<option value="7"<?php if($PRJP_STAT == 7) { ?> selected <?php } ?> disabled >Waiting</option>
																	<?php if($PRJP_STAT == 3 || $PRJP_STAT == 9) { ?>
																	<option value="9"<?php if($PRJP_STAT == 9) { ?> selected <?php } ?> disabled>Void</option>
																	<?php } ?>
																</select>
															<?php
														}
														else
														{
															$disButton	= 1;
															?>
																<select name="PRJP_STAT" id="PRJP_STAT" class="form-control select2" <?php if($disButton == 1) { ?> disabled <?php } ?>>
																	<option value="1"<?php if($PRJP_STAT == 1) { ?> selected <?php } ?> disabled >New</option>
																	<option value="2"<?php if($PRJP_STAT == 2) { ?> selected <?php } ?> disabled >Confirm</option>
																	<option value="3"<?php if($PRJP_STAT == 3) { ?> selected <?php } ?> disabled >Approved</option>
																	<option value="4"<?php if($PRJP_STAT == 4) { ?> selected <?php } ?> disabled >Revising</option>
																	<option value="5"<?php if($PRJP_STAT == 5) { ?> selected <?php } ?> disabled >Rejected</option>
																	<option value="6"<?php if($PRJP_STAT == 6) { ?> selected <?php } ?> style="display:none" >Closed</option>
																	<option value="7"<?php if($PRJP_STAT == 7) { ?> selected <?php } ?> disabled >Waiting</option>
																	<?php if($PRJP_STAT == 3 || $PRJP_STAT == 9) { ?>
																	<option value="9"<?php if($PRJP_STAT == 9) { ?> selected <?php } ?> disabled>Void</option>
																	<?php } ?>
																</select>
															<?php
														}
													}
												}
											// END : FOR ALL APPROVAL FUNCTION
											$theProjCode 	= "$PRJCODE";
				                        	$url_AddItem	= site_url('c_project/c_uPpR09r355/puSA0b28t18/?id='.$this->url_encryption_helper->encode_url($theProjCode));
				                        ?>
				                    </div>
				                </div>
								<?php
				                    if($PRJP_STAT == 1 || $PRJP_STAT == 4)
				                    {
				                        ?>
				                        <div class="form-group" style="display:none">
				                            <label for="inputName" class="col-sm-3 control-label">&nbsp;</label>
				                            <div class="col-sm-9">
				                                <script>
				                                    var url = "<?php echo $url_AddItem;?>";
				                                    function selectAccount(theRow)
				                                    {
				                                        PRJCODE	= '<?php echo $PRJCODE;?>';
				                                        title = 'Select Item';
				                                        w = 800;
				                                        h = 550;
				                                        //window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
				                                        var left = (screen.width/2)-(w/2);
				                                        var top = (screen.height/2)-(h/2);
				                                        return window.open(url+'&PRJCODE='+PRJCODE+'&theRow='+theRow, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
				                                    }
				                                </script>
				                                <button class="btn btn-success" type="button" onClick="add_listAcc();">
				                                <?php echo $SelectJob; ?>&nbsp;&nbsp;[ <i class="glyphicon glyphicon-plus"></i> ]
				                                </button>
				                            </div>
				                        </div>
										<?php
				                    }
				                ?>
				                <div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label">&nbsp;</label>
				                    <div class="col-sm-9">
				                        <?php
											$$disableAll	= 0;
											if($disableAll == 0)
											{
												if(($PRJP_STAT == 2 || $PRJP_STAT == 7) && $canApprove == 1)
												{
													?>
														<button class="btn btn-primary" >
														<i class="fa fa-save"></i></button>&nbsp;
													<?php
												}
												elseif($PRJP_STAT == 1 || $PRJP_STAT == 4)
												{
														?>
															<button class="btn btn-primary">
															<i class="fa fa-save"></i></button>&nbsp;
														<?php
												}
											}							
											$backURL	= site_url('c_project/c_uPpR09r355/pR09r355Lst/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
											echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>');
				                        ?>
				                    </div>
				                </div>
							</div>
						</div>
					</div>
                    <div class="col-md-12">
                        <div class="box box-primary">
	                        <div class="search-table-outter">
	                            <table id="tbl" class="table table-bordered table-striped" width="100%">
		                        	<tr style="background:#CCCCCC">
		                              	<th width="3%" style="text-align:center; vertical-align: middle;">&nbsp;</th>
		                              	<th width="10%" style="text-align:center; vertical-align: middle;"><?php echo $JobCode; ?> </th>
		                              	<th width="30%" style="text-align:center; vertical-align: middle;"><?php echo $Description; ?> </th>
		                              	<th width="10%" style="text-align:center; vertical-align: middle;"><?php echo $Volume; ?> </th>
		                              	<th width="5%" style="text-align:center; vertical-align: middle;">Unit</th>
		                              	<th width="10%" style="text-align:center; vertical-align: middle;"><?php echo $ProgressBefore; ?></th>
		                              	<th width="10%" style="text-align:center; vertical-align: middle;"><?php echo $ProgressNow; ?> </th>
		                              	<th width="7%" style="text-align:center; vertical-align: middle;">%</th>
		                              	<th width="15%" style="text-align:center; vertical-align: middle;"><?php echo $Remarks; ?></th>
		                          	</tr>
		                            <?php					
										if($task == 'edit')
										{
											/*$sqlDET		= "SELECT A.JOBCODEID, A.JOBDESC, A.JOBVOLM AS PROG_BUDG, A.BOQ_JOBCOST,
																B.PROG_VAL, B.PROG_PERC, A.BOQ_BOBOT, A.JOBUNIT, B.PROG_DESC
															FROM tbl_joblist A
																LEFT JOIN tbl_project_progress_det B ON A.JOBCODEID = B.JOBCODEID
																	AND B.PRJP_NUM = '$PRJP_NUM' 
															WHERE 
																A.ISBOBOT = 1
																AND A.PRJCODE = '$PRJCODE' ORDER BY A.JOBCODEID ASC";*/
											$sqlDET		= "SELECT A.JOBCODEID, A.JOBDESC, A.JOBVOLM AS PROG_BUDG, A.BOQ_JOBCOST,
																B.PROG_VAL, B.PROG_PERC, A.BOQ_BOBOT, A.JOBUNIT, B.PROG_DESC
															FROM tbl_joblist A
																LEFT JOIN tbl_project_progress_det B ON A.JOBCODEID = B.JOBCODEID
																	AND B.PRJP_NUM = '$PRJP_NUM' 
															WHERE 
																A.PRJCODE = '$PRJCODE' AND A.JOBLEV = 1 AND A.ISBOBOT = 1 AND A.BOQ_BOBOT > 0
															ORDER BY A.JOBCODEID ASC";
										}
										else
										{
											/*$sqlDET		= "SELECT DISTINCT JOBCODEID, JOBDESC, JOBVOLM AS PROG_BUDG, BOQ_JOBCOST,
																0 AS PROG_VAL, 0 AS PROG_PERC, BOQ_BOBOT, JOBUNIT, '' AS PROG_DESC
															FROM tbl_joblist
															WHERE PRJCODE = '$PRJCODE' AND ISBOBOT = 1";*/
											$sqlDET		= "SELECT DISTINCT JOBCODEID, JOBDESC, JOBVOLM AS PROG_BUDG, BOQ_JOBCOST,
																0 AS PROG_VAL, 0 AS PROG_PERC, BOQ_BOBOT, JOBUNIT, '' AS PROG_DESC
															FROM tbl_joblist
															WHERE PRJCODE = '$PRJCODE' AND JOBLEV = 1 AND ISBOBOT = 1 AND BOQ_BOBOT > 0";
										}
										$result = $this->db->query($sqlDET)->result();
		                                $i		= 0;
		                                $j		= 0;
										
										foreach($result as $row) :
											$currentRow  	= ++$i;
											$PRJP_NUM 		= $PRJP_NUM;
											$JOBCODEID 		= $row->JOBCODEID;
											$JOBDESC 		= $row->JOBDESC;
											$PROG_BUDG 		= $row->PROG_BUDG;
											$BOQ_JOBCOST	= $row->BOQ_JOBCOST;
											$PROG_VAL 		= $row->PROG_VAL;
											$PROG_PERC 		= $row->PROG_PERC;
											$BOQ_BOBOT 		= $row->BOQ_BOBOT;
											$JOBUNIT 		= $row->JOBUNIT;
											$PROG_DESC 		= $row->PROG_DESC;
											
											if(strtoupper($JOBUNIT) == 'LS')
											{
												$PROG_BUDG	= $BOQ_JOBCOST;
											}
											
											$TOTBEF_VAL		= 0;
											$sqlTOTBEF		= "SELECT SUM(PROG_VAL) AS TOTBEF_VAL FROM tbl_project_progress_det A 
																INNER JOIN tbl_project_progress B ON A.PRJP_NUM = B.PRJP_NUM
																WHERE JOBCODEID = '$JOBCODEID' AND A.PRJCODE = '$PRJCODE' AND B.PRJP_STAT = 3
																AND A.PRJP_NUM != '$PRJP_NUM'";
											$resTOTBEF 		= $this->db->query($sqlTOTBEF)->result();
											foreach($resTOTBEF as $rowTOTBEF) :
												$TOTBEF_VAL = $rowTOTBEF->TOTBEF_VAL;
											endforeach;
											if($TOTBEF_VAL == '')
												$TOTBEF_VAL	= 0;
												
											$PROG_TOT		= $TOTBEF_VAL + $PROG_VAL;
											
											if($PROG_BUDG == 0 || $PROG_BUDG == '')
												$PROG_BUDGD	= 1;
											else
												$PROG_BUDGD	= $PROG_BUDG;

											// CURRENT PROGRESS
												$PROG_PERC	= $PROG_VAL / $PROG_BUDGD * $BOQ_BOBOT;
											
											// AKUM. PROGRESS
												$PROG_PERCT	= $PROG_TOT / $PROG_BUDGD * $BOQ_BOBOT;																		
											?> 
		                                    <tr id="tr_<?php echo $currentRow; ?>">
											<td width="2%" height="25" style="text-align:center" nowrap>
												<?php
													/*if($PRJP_STAT == 1)
													{
														?>
														<a href="#" onClick="deleteRow(<?php echo $currentRow; ?>)" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a>
														<?php
													}
													else
													{
														echo "$currentRow.";
													}*/
													echo "$currentRow.";
		                                        ?>
		                                    </td>
										 	<td width="10%" style="text-align:left">
		                                    	<input type="hidden" name="data[<?php echo $currentRow; ?>][PRJP_NUM]" id="data<?php echo $currentRow; ?>PRJP_NUM" value="<?php echo $PRJP_NUM; ?>" class="form-control" style="max-width:150px;" >
		                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][PRJCODE]" id="data<?php echo $currentRow; ?>PRJCODE" value="<?php echo $PRJCODE; ?>" class="form-control" style="max-width:150px;" >
		                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][JOBCODEID]" id="data<?php echo $currentRow; ?>JOBCODEID" value="<?php echo $JOBCODEID; ?>" class="form-control" style="max-width:150px;" onClick="selectAccount(<?php echo $currentRow; ?>);">
		                                        <?php echo $JOBCODEID; ?>
		                                    </td>
										  	<td width="30%" style="text-align:left">
		                                        <input type="hidden" name="data<?php echo $currentRow; ?>JOBDESC" id="data<?php echo $currentRow; ?>JOBDESC" value="<?php echo $JOBDESC; ?>" class="form-control" style="max-width:500px;" placeholder="<?php echo $Description; ?>">
		                                        <?php echo $JOBDESC; ?>
		                                    </td>
											<td width="7%" nowrap style="text-align:right">
		                                    	<input type="hidden" name="data<?php echo $currentRow; ?>PROG_BUDGX" id="data<?php echo $currentRow; ?>PROG_BUDGX" value="<?php echo number_format($PROG_BUDG, 2); ?>" class="form-control" style="min-width:100px; max-width:150px; text-align:right" disabled >
		                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][PROG_BUDG]" id="data<?php echo $currentRow; ?>PROG_BUDG" value="<?php echo $PROG_BUDG; ?>" class="form-control" style="max-width:300px;" >
		                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][BOQ_BOBOT]" id="data<?php echo $currentRow; ?>BOQ_BOBOT" value="<?php echo $BOQ_BOBOT; ?>" class="form-control" style="max-width:300px;" >
		                                    	<?php echo number_format($PROG_BUDG, 2); ?>
		                                    </td>
											<td width="4%" nowrap style="text-align:center">
		                                    	<input type="hidden" name="JOBUNIT<?php echo $currentRow; ?>" id="JOBUNIT<?php echo $currentRow; ?>" value="<?php echo $JOBUNIT; ?>" class="form-control" style="min-width:50px; max-width:100px; text-align:center" disabled >
		                                    	<?php echo $JOBUNIT; ?>
		                                    </td>
											<td width="6%" nowrap style="text-align:right">
		                                    	<input type="hidden" name="<?php echo $currentRow; ?>PROG_BEF" id="PROG_BEF<?php echo $currentRow; ?>" value="<?php echo number_format($TOTBEF_VAL, 2); ?>" class="form-control" style="max-width:150px; text-align:right" disabled >
		                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][PROG_BEF]" id="data<?php echo $currentRow; ?>PROG_BEF" value="<?php echo $TOTBEF_VAL; ?>" class="form-control" style="max-width:300px;" >
		                                    	<?php echo number_format($TOTBEF_VAL, 2); ?>
		                                    </td>
											<td width="13%" nowrap style="text-align:center">
		                                    	<input type="text" name="PROG_VAL<?php echo $currentRow; ?>" id="PROG_VAL<?php echo $currentRow; ?>" value="<?php echo number_format($PROG_VAL, 2); ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,<?php echo $currentRow; ?>);" >
		                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][PROG_VAL]" id="data<?php echo $currentRow; ?>PROG_VAL" value="<?php echo $PROG_VAL; ?>" class="form-control" style="max-width:300px;" >
		                                    </td>
											<td width="7%" nowrap style="text-align:center">
		                                    	<input type="text" name="PROG_PERC<?php echo $currentRow; ?>" id="PROG_PERC<?php echo $currentRow; ?>" value="<?php echo number_format($PROG_PERC, 4); ?>" class="form-control" style="min-width:80px; max-width:80px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,<?php echo $currentRow; ?>);" size="10" readonly >
		                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][PROG_PERC]" id="data<?php echo $currentRow; ?>PROG_PERC" value="<?php echo $PROG_PERC; ?>" class="form-control" style="max-width:300px;" >
		                                    </td>
										  	<td width="21%" style="text-align:left">
		                                    	<input type="text" name="data[<?php echo $currentRow; ?>][PROG_DESC]" id="data<?php echo $currentRow; ?>PROG_DESC" size="20" value="<?php echo $PROG_DESC; ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:left" placeholder="<?php echo $Remarks; ?>">
		                                    </td>
							          	</tr>
		                              	<?php
		                             	endforeach;
										?>
		                                <input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">
		                              	<?php
		                            ?>
			                    </table>
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
  });
  
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
	
	function add_listAcc(strItem) 
	{		
		var objTable, objTR, objTD, intIndex;
		
		PRJP_NUM		= '';
		PRJCODE 		= '';
		JOBCODEID		= '';
		JOBDESC			= '';
		JOBUNIT			= '';
		PROG_BUDG		= 0;
		BOQ_BOBOT		= 0;
		PROG_BEF		= 0;
		PROG_PERC		= 0;
		PROG_VAL		= 0;
		
		objTable 		= document.getElementById('tbl');
		intTable 		= objTable.rows.length;
		//swal('intTable = '+intTable)
		//intIndex = parseInt(document.frm.rowCount.value) + 1;
		intIndex = parseInt(objTable.rows.length);
		//intIndex = intTable;
		document.frm.rowCount.value = intIndex;
		
		objTR = objTable.insertRow(intTable);
		objTR.id = 'tr_' + intIndex;
		
		// Account Icon
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = "center";
		objTD.noWrap = true;
		objTD.innerHTML = '<a href="#" onClick="deleteRow('+intIndex+')" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a><input type="hidden" id="chk" name="chk" value="'+intIndex+'" width="10" size="15" readonly class="form-control" style="max-width:300px;">';
		
		// Jobcode ID
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'right';
		objTD.innerHTML = '<input type="hidden" name="data['+intIndex+'][PRJP_NUM]" id="data'+intIndex+'PRJP_NUM" value="<?php echo $PRJP_NUM; ?>" class="form-control" style="max-width:150px;" ><input type="hidden" name="data['+intIndex+'][PRJCODE]" id="data'+intIndex+'PRJCODE" value="<?php echo $PRJCODE; ?>" class="form-control" style="max-width:150px;" ><input type="text" name="data['+intIndex+'][JOBCODEID]" id="data'+intIndex+'JOBCODEID" value="'+JOBCODEID+'" class="form-control" style="max-width:150px;" onClick="selectAccount('+intIndex+');">';
		
		// Job Description
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.noWrap = true;
		objTD.innerHTML = '<input type="text" name="data'+intIndex+'JOBDESC" id="data'+intIndex+'JOBDESC" value="'+JOBDESC+'" class="form-control" style="max-width:500px;" placeholder="<?php echo $Description; ?>">';
		
		// Progress Budget
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" name="data'+intIndex+'PROG_BUDGX" id="data'+intIndex+'PROG_BUDGX" value="'+PROG_BUDG+'" class="form-control" style="min-width:110px; max-width:150px; text-align:right" disabled ><input type="hidden" name="data['+intIndex+'][PROG_BUDG]" id="data'+intIndex+'PROG_BUDG" value="'+PROG_BUDG+'" class="form-control" style="max-width:300px;" ><input type="hidden" name="data['+intIndex+'][BOQ_BOBOT]" id="data'+intIndex+'BOQ_BOBOT" value="'+BOQ_BOBOT+'" class="form-control" style="max-width:300px;" >';
		
		// Satuan
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = '<input type="text" name="JOBUNIT'+intIndex+'" id="JOBUNIT'+intIndex+'" value="'+JOBUNIT+'" class="form-control" style="min-width:50px; max-width:100px; text-align:center" disabled >';
		
		// Progress Before
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" name="data'+intIndex+'PROG_BEF" id="PROG_BEF'+intIndex+'" value="'+PROG_BEF+'" class="form-control" style="max-width:150px; text-align:right" disabled ><input type="hidden" name="data['+intIndex+'][PROG_BEF]" id="data'+intIndex+'PROG_BEF" value="'+PROG_BEF+'" class="form-control" style="max-width:300px;" >';
		
		// Progress Current - Value
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" name="PROG_VAL'+intIndex+'" id="PROG_VAL'+intIndex+'" value="0.00" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,'+intIndex+');" ><input type="hidden" name="data['+intIndex+'][PROG_VAL]" id="data'+intIndex+'PROG_VAL" value="0.00" class="form-control" style="max-width:300px;" >';
		
		// Progress Current - Percentation
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" name="PROG_PERC'+intIndex+'" id="PROG_PERC'+intIndex+'" value="0.0000" class="form-control" style="min-width:80px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,'+intIndex+');" ><input type="hidden" name="data['+intIndex+'][PROG_PERC]" id="data'+intIndex+'PROG_PERC" value="0.00" class="form-control" style="max-width:300px;" >';
		
		// Remarks
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.innerHTML = '<input type="text" name="data['+intIndex+'][PROG_DESC]" id="data'+intIndex+'PROG_DESC" size="20" value="" class="form-control" style="min-width:110px; max-width:300px; text-align:left" placeholder="<?php echo $Remarks; ?>">';
		
		var decFormat												= document.getElementById('decFormat').value;
		document.getElementById('data'+intIndex+'PROG_BUDGX').value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PROG_BUDG)),decFormat));
		document.getElementById('totalrow').value = intIndex;
	}
	
	function add_item(strItem) 
	{
		arrItem = strItem.split('|');
		
		/*validateDouble(arrItem[0],arrItem[1])
		if(validateDouble(arrItem[0],arrItem[1]))
		{
			swal("Double Item for " + arrItem[0]);
			return;
		}*/
		
		JOBCODEID 		= arrItem[0];
		JOBCODEIDV 		= arrItem[1];
		JOBDESC 		= arrItem[2];
		JOBVOLM 		= arrItem[3];	// Budget Volume
		PRICE 			= arrItem[4];	// Item/Job Price
		JOBCOST 		= arrItem[5];	// Budget Cost
		JOBCOST_PROG 	= arrItem[6];	// Progress Approve Before
		JOBCOST_PROG_P	= arrItem[7];	// Progress Percentaton Approve Before
		BOQ_BOBOT 		= arrItem[8];
		TOTBEF_VAL		= arrItem[9];
		JOBUNIT			= arrItem[10];
		THEROW 			= arrItem[11];
		if(TOTBEF_VAL == '')
			TOTBEF_VAL	= 0;
			
		var decFormat												= document.getElementById('decFormat').value;
		
		document.getElementById('data'+THEROW+'JOBCODEID').value	= JOBCODEID;
		document.getElementById('data'+THEROW+'BOQ_BOBOT').value	= BOQ_BOBOT;
		document.getElementById('data'+THEROW+'JOBDESC').value		= JOBDESC;
		document.getElementById('data'+THEROW+'PROG_BUDGX').value	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(JOBVOLM)),decFormat));
		document.getElementById('JOBUNIT'+THEROW).value				= JOBUNIT;
		document.getElementById('data'+THEROW+'PROG_BUDG').value	= JOBVOLM;
		document.getElementById('data'+THEROW+'PROG_BEF').value		= TOTBEF_VAL;
		document.getElementById('PROG_BEF'+THEROW).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TOTBEF_VAL)),decFormat));
		document.getElementById('data'+THEROW+'PROG_BEF').value		= TOTBEF_VAL;
		document.getElementById('data'+THEROW+'PROG_VAL').value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(0)),decFormat));
		document.getElementById('data'+THEROW+'PROG_DESC').value	= '';
	}
	
	function getConvertion(thisVal1, row)
	{
		var decFormat	= document.getElementById('decFormat').value;
		totrow			= document.getElementById('totalrow').value;
		
		// CHECK PROGRESS BEFORE
		BOQ_BOBOT		= document.getElementById('data'+row+'BOQ_BOBOT').value;
		
		PROG_VAL 		= parseFloat(eval(thisVal1).value.split(",").join(""));

		// Percentation
		PROG_BUDG		= parseFloat(document.getElementById('data'+row+'PROG_BUDG').value);
		PROG_BEF		= document.getElementById('data'+row+'PROG_BEF').value;
		TOLERANCE		= 0.1 * parseFloat(PROG_BUDG);
		PROG_REM		= parseFloat(PROG_BUDG) - parseFloat(PROG_BEF);
		//PROG_REM		= parseFloat(PROG_BUDG) - parseFloat(PROG_BEF) + parseFloat(TOLERANCE);
		
		if(PROG_VAL > PROG_REM)
		{
			swal('<?php echo $alert10; ?>',
			{
				icon:"warning",
			});
			PROG_VAL	= parseFloat(PROG_REM);
		}
		
		document.getElementById('data'+row+'PROG_VAL').value= PROG_VAL;
		document.getElementById('PROG_VAL'+row).value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PROG_VAL)), 4));
		
		PROG_TOT		= parseFloat(PROG_BEF) + parseFloat(PROG_VAL);
		
		// CURRENT PROGRESS
			PROG_PERC	= parseFloat(PROG_VAL) / parseFloat(PROG_BUDG) * parseFloat(BOQ_BOBOT);
		
		// AKUM. PROGRESS
			PROG_PERCT	= parseFloat(PROG_TOT) / parseFloat(PROG_BUDG) * parseFloat(BOQ_BOBOT);
		
		document.getElementById('data'+row+'PROG_PERC').value = PROG_PERC;
		document.getElementById('PROG_PERC'+row).value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PROG_PERC)), 4));
		
		PRJP_TOT	= 0;
		PRJP_GTOT	= 0;
		for(i=1;i<=totrow;i++)
		{
			var PROG_PERC	= document.getElementById('data'+i+'PROG_PERC').value;
			var PROG_VAL	= document.getElementById('data'+i+'PROG_VAL').value;				
			PRJP_TOT		= parseFloat(PRJP_TOT) + parseFloat(PROG_PERC);
		}
		
		var PRJP_TOTBEF	= parseFloat(eval(PRJP_TOTBEFX).value.split(",").join(""));
		var PRJP_GTOT	= parseFloat(PRJP_TOTBEF) + parseFloat(PRJP_TOT);
		document.getElementById('PRJP_TOT').value		= PRJP_TOT;
		document.getElementById('PRJP_TOTX').value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PRJP_TOT)), 4));
		document.getElementById('PRJPRGS').value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PRJP_TOT * 100)), 4));
		document.getElementById('PRJP_GTOT').value		= PRJP_GTOT;
		document.getElementById('PRJP_GTOTX').value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PRJP_GTOT)), 4));
		
		
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
	
	function RoundNDecimal(X, N)
	{
		var T, S=new String(Math.round(X*Number("1e"+N)))
		while (S.length<=N) S='0'+S
		return S.substr(0, T=(S.length-N)) + '.' + S.substr(T, N)
	}
	
	function validateInData(value)
	{
		var totrow 			= document.getElementById('totalrow').value;
		var PRJP_DESC 	= document.getElementById('PRJP_DESC').value;
		var totAmountD		= parseFloat(document.getElementById('Journal_AmountD').value);
		var totAmountK		= parseFloat(document.getElementById('Journal_AmountK').value);
		
		if(PRJP_DESC == '')
		{
			swal('<?php echo $alert0; ?>');
			document.getElementById('PRJP_DESC').focus();
			return false;
		}
		
		var rowD		= 0;
		var rowK		= 0;
		for(i=1;i<=totrow;i++)
		{
			var Acc_Id 			= document.getElementById('data'+i+'Acc_Id').value;
			var JournalD_Desc 	= document.getElementById('data'+i+'JournalD_Desc').value;
			var JournalD_Pos 	= document.getElementById('data'+i+'JournalD_Pos').value;
			var isTax			= document.getElementById('data'+i+'isTax').value;
			var Ref_Number		= document.getElementById('data'+i+'Ref_Number').value;
			var JournalD_Amount	= parseFloat(document.getElementById('data'+i+'JournalD_Amount').value);
			var Other_Desc		= document.getElementById('data'+i+'Other_Desc').value;
			
			if(Acc_Id == '')
			{
				swal('<?php echo $alert1; ?>');
				document.getElementById('data'+i+'Acc_Id').focus();
				return false;
			}
			
			if(JournalD_Desc == '')
			{
				swal('<?php echo $alert2; ?>');
				document.getElementById('data'+i+'JournalD_Desc').focus();
				return false;
			}
			
			if(JournalD_Pos == '')
			{
				swal('<?php echo $alert3; ?>');
				document.getElementById('data'+i+'JournalD_Pos').focus();
				return false;
			}
			
			if(isTax == '')
			{
				swal('<?php echo $alert4; ?>');
				document.getElementById('data'+i+'isTax').focus();
				return false;
			}
			
			if(Ref_Number == '')
			{
				swal('<?php echo $alert5; ?>');
				document.getElementById('data'+i+'Ref_Number').focus();
				return false;
			}
			
			if(JournalD_Amount == 0)
			{
				swal('<?php echo $alert6; ?>');
				document.getElementById('JournalD_Amount'+i).focus();
				return false;
			}
			
			if(JournalD_Pos == 'D')
				var rowD	= parseFloat(rowD) + 1;
			else
				var rowK	= parseFloat(rowK) + 1;
		}
		if(rowD == 0)
		{
			swal('<?php echo $alert7; ?>')
			return false;
		}
		
		if(rowK == 0)
		{
			swal('<?php echo $alert8; ?>')
			return false;
		}
			
		if(totAmountD != totAmountK)
		{
			swal('<?php echo $alert9; ?>');
			return false;
		}
		
		if(totrow == 0)
		{
			swal('Please input detail Material Request.');
			return false;		
		}
		else
		{
			document.frm.submit();
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