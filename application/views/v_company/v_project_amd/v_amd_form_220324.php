<?php
/*
	* Author		= Dian Hermanto
	* Create Date	= 22 April 2018
	* File Name	= v_amd_form.php
	* Location		= -
*/

$this->load->view('template/head');

$appName 		= $this->session->userdata('appName');
$appBody    	= $this->session->userdata('appBody');
$PRJSCATEG    	= $this->session->userdata('PRJSCATEG');

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

$currentRow = 0;
if($task == 'add')
{
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

	$year 	= date('Y');
	$sql 	= "tbl_amd_header WHERE YEAR(AMD_DATE) = $year AND PRJCODE = '$PRJCODE'";
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
	$yearG = date('y');
	if(($useYear == 1) && ($useMonth == 1) && ($useDate == 1))
		$groupPattern = "$yearG$pattMonth$pattDate";
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
	$lastPattNumb = $myMax;
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

	$DocNumber 	= "$PRJCODE.$Pattern_Code$groupPattern-$lastPatternNumb";
	$AMD_NUM	= $DocNumber;
	$AMD_CODE	= "$Pattern_Code-$PRJCODE-$lastPatternNumb";
	$PRJCODE	= $PRJCODE;

	// ADA PERUBAHAN PROSEDUR. BY REQUEST PAK WAWAN
	// 1. PILIH ITEM KOMPNEN (SINGLE)
	// 2. PILIH PEKERJAN (MULTIPLE)

	$AMD_JOBPAR	= '';				// ITEM KOMPONEN (ITM_CODE)
	$JOBCODEID	= '';				// ITEM KOMPONEN (ITM_CODE)
	$AMD_TYPE	= 0;
	$AMD_CATEG	= '';
	$AMD_REFNO	= '';
	$AMD_REFNOAM= 0;
	$AMD_DATE 	= date('d/m/Y');
	$AMD_DESC	= '';
	$AMD_UNIT	= '';
	$JOBDESC	= '';
	$AMD_NOTES	= '';
	$AMD_MEMO	= '';
	$AMD_AMOUNT	= 0;
	$AMD_STAT	= 1;
	$Patt_Year 	= date('Y');
	$AMD_FUNC	= '';
	$AMD_JOBDESC= "";
}
else
{
	$isSetDocNo = 1;
	$AMD_NUM 		= $default['AMD_NUM'];
	$DocNumber 		= $default['AMD_NUM'];
	$AMD_CODE 		= $default['AMD_CODE'];
	$PRJCODE 		= $default['PRJCODE'];
	$AMD_TYPE 		= $default['AMD_TYPE'];
	$AMD_CATEG 		= $default['AMD_CATEG'];
	$AMD_FUNC 		= $default['AMD_FUNC'];
	$AMD_REFNO 		= $default['AMD_REFNO'];
	$AMD_REFNOAM 	= $default['AMD_REFNOAM'];
	$AMD_JOBPAR		= $default['AMD_JOBPAR'];
	$AMD_JOBID		= $default['AMD_JOBID'];
	$AMD_JOBDESC	= $default['AMD_JOBDESC'];
	$JOBCODEID		= $default['AMD_JOBID'];
	$AMD_DATE 		= date('d/m/Y', strtotime($default['AMD_DATE']));
	$AMD_DESC 		= $default['AMD_DESC'];
	$JOBDESC		= $AMD_DESC;
	$AMD_UNIT 		= $default['AMD_UNIT'];
	$AMD_NOTES 		= $default['AMD_NOTES'];
	$AMD_MEMO 		= $default['AMD_MEMO'];
	$AMD_AMOUNT		= $default['AMD_AMOUNT'];
	$PRJCODE 		= $default['PRJCODE'];
	$AMD_STAT 		= $default['AMD_STAT'];
	$Patt_Number 	= $default['Patt_Number'];
	$lastPattNumb	= $Patt_Number;

	$NEW_JOBCODEID	= $JOBCODEID;
	if($AMD_CATEG == 'SINJ')
	{
		$JOBCODEID		= $default['AMD_JOBPAR'];
		$NEW_JOBCODEID	= $AMD_JOBID;
	}
	// COUNT CHILD FOR THE PARENT
	$sqlCHLDC 	= "tbl_joblist WHERE JOBPARENT = '$AMD_JOBID'";
	$resCHLDC 	= $this->db->count_all($sqlCHLDC);
	$resCHLDC	= $resCHLDC + 1;

	$PattLength	= 2;
	$lgth 		= strlen($resCHLDC);
	$nolJN		= "";
	if($PattLength==2)
	{
		if($lgth==1) $nolJN="0";
	}
	elseif($PattLength==3)
	{
		if($lgth==1) $nolJN="00";else if($lgth==2) $nolJN="0";
	}
	$lastJobNum 	= $nolJN.$resCHLDC;
	//$NEW_JOBCODEID	= "$AMD_JOBID.$lastJobNum";
	$NEW_JOBCODEID	= $NEW_JOBCODEID;
	$NEW_JOBCODEIDV	= $NEW_JOBCODEID;
	$NEW_JOBPARENT	= $AMD_JOBPAR;
}

if(isset($_POST['JOBCODEIDX']))
{
	$AMD_CATEG 	= $_POST['AMD_CATEGX'];
	$JOBCODEID 	= $_POST['JOBCODEIDX'];
}

// REJECT FUNCTION
	// CEK ACCESS OTORIZATION
		$resAPP	= 0;
		$sqlAPP	= "tbl_docstepapp_det WHERE MENU_CODE = 'MN340'
					AND PRJCODE = '$PRJCODE' AND APPROVER_1 = '$DefEmp_ID'";
		$resAPP	= $this->db->count_all($sqlAPP);
	// CEK IR
		/*$DOC_NO	= '';
		$sqlIRC	= "tbl_ir_header WHERE PO_NUM = '$PO_NUM' AND IR_STAT != 5";
		$isUSED	= $this->db->count_all($sqlIRC);
		if($isUSED > 0)
		{
			$sqlIR 	= "SELECT IR_CODE FROM tbl_ir_header WHERE PO_NUM = '$PO_NUM' AND IR_STAT != 5 LIMIT 1";
			$resIR	= $this->db->query($sqlIR)->result();
			foreach($resIR as $rowIR):
				$DOC_NO	= $rowIR->IR_CODE;
			endforeach;
		}*/

		
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
				$APPROVE_AMOUNT 	= $AMD_AMOUNT;
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
			if($TranslCode == 'Invoice')$Invoice = $LangTransl;
			if($TranslCode == 'AMDNumber')$AMDNumber = $LangTransl;
			if($TranslCode == 'reviseNotes')$reviseNotes = $LangTransl;
			if($TranslCode == 'Code')$Code = $LangTransl;
			if($TranslCode == 'Date')$Date = $LangTransl;
			if($TranslCode == 'DueDate')$DueDate = $LangTransl;
			if($TranslCode == 'SourceDocument')$SourceDocument = $LangTransl;
			if($TranslCode == 'Category')$Category = $LangTransl;
			if($TranslCode == 'FunctionalPosition')$FunctionalPosition = $LangTransl;
			if($TranslCode == 'RefNumber')$RefNumber = $LangTransl;
			if($TranslCode == 'Search')$Search = $LangTransl;
			if($TranslCode == 'Project')$Project = $LangTransl;
			if($TranslCode == 'Notes')$Notes = $LangTransl;
			if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
			if($TranslCode == 'ItemName')$ItemName = $LangTransl;
			if($TranslCode == 'ItemQty')$ItemQty = $LangTransl;
			if($TranslCode == 'BeginPlan')$BeginPlan = $LangTransl;
			if($TranslCode == 'Used')$Used = $LangTransl;
			if($TranslCode == 'Increase')$Increase = $LangTransl;
			if($TranslCode == 'Volume')$Volume = $LangTransl;
			if($TranslCode == 'Unit')$Unit = $LangTransl;
			if($TranslCode == 'Remarks')$Remarks = $LangTransl;
			if($TranslCode == 'tsfAmount')$tsfAmount = $LangTransl;
			if($TranslCode == 'Price')$Price = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'Total')$Total = $LangTransl;
			if($TranslCode == 'Tax')$Tax = $LangTransl;
			if($TranslCode == 'Type')$Type = $LangTransl;
			if($TranslCode == 'Add')$Add = $LangTransl;
			if($TranslCode == 'Save')$Save = $LangTransl;
			if($TranslCode == 'Update')$Update = $LangTransl;
			if($TranslCode == 'Back')$Back = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'AmandTotal')$AmandTotal = $LangTransl;
			if($TranslCode == 'InvList')$InvList = $LangTransl;
			if($TranslCode == 'JobParent')$JobParent = $LangTransl;
			if($TranslCode == 'itmJob')$itmJob = $LangTransl;
			if($TranslCode == 'JobName')$JobName = $LangTransl;
			if($TranslCode == 'TotAmount')$TotAmount = $LangTransl;
			if($TranslCode == 'AddItem')$AddItem = $LangTransl;
			if($TranslCode == 'Others')$Others = $LangTransl;
			if($TranslCode == 'SINumber')$SINumber = $LangTransl;
			if($TranslCode == 'reviseNotes')$reviseNotes = $LangTransl;
			if($TranslCode == 'Approval')$Approval = $LangTransl;
			if($TranslCode == 'Approved')$Approved = $LangTransl;
			if($TranslCode == 'NotYetApproved')$NotYetApproved = $LangTransl;
			if($TranslCode == 'JobList')$JobList = $LangTransl;
			if($TranslCode == 'Select')$Select = $LangTransl;
			if($TranslCode == 'Close')$Close = $LangTransl;
			if($TranslCode == 'rejected')$rejected = $LangTransl;
			if($TranslCode == 'SelectItem')$SelectItem = $LangTransl;
			if($TranslCode == 'SelectJob')$SelectJob = $LangTransl;
			if($TranslCode == 'ItemList')$ItemList = $LangTransl;
			if($TranslCode == 'BudgetQty')$BudgetQty = $LangTransl;
			if($TranslCode == 'Requested')$Requested = $LangTransl;
			if($TranslCode == 'StockQuantity')$StockQuantity = $LangTransl;
			if($TranslCode == 'SIStep')$SIStep = $LangTransl;
			if($TranslCode == 'ContractAmount')$ContractAmount = $LangTransl;
			if($TranslCode == 'SIList')$SIList = $LangTransl;
			if($TranslCode == 'othInfo')$othInfo = $LangTransl;
			if($TranslCode == 'Remain')$Remain = $LangTransl;
			if($TranslCode == 'jobCd')$jobCd = $LangTransl;
			if($TranslCode == 'RemBudget')$RemBudget = $LangTransl;
			if($TranslCode == 'JobNm')$JobNm = $LangTransl;
		endforeach;

		if($LangID == 'IND')
		{
			$h1_title 	= "Tambah";
			$h2_title 	= "Tambah Amandemen";
			$h3_title	= "Penerimaan Barang";
			$alert1		= "Silahan pilih kategori amandemen.";
			$alert2		= "Jumlah total Amandemen melebihi Total SI.";
			$alert3		= "Jumlah total Amandemen bernilai Nol.";
			$alert4		= "Silahkan pilih Nomo SI.";
			$alert5		= "Silahkan tentukan kategori SI (Tambah / Kurang).";
			$alert6		= "Silahkan tulis alasan revisi/tolak/membatalkan dokumen.";
			$alert7		= "Silahan pilih induk anggaran.";
			$alert8		= "Anda belum memilih detail item yang akan diamandemen.";
			$alert9		= "Anda belum memasukan kode detail pekerjaan. Kode header = ";
			$alert10	= "Kode detail pekerjaan tidak boleh sama dengan kode induk pekerjaan.";
			$alert11	= "Kategori Over Budget atau Not Budgeting tidak bisa memilih No SI.";
			$alert12	= "Volume item tidak boleh kosong";
			$alert13	= "Harga item tidak boleh kosong";
			$alert14	= "Jumlah yang akan dipindahkan melebihi sisa nilai yang ada.";
			$isManual	= "Centang untuk kode manual.";
			$alertSubmit= "data sudah berhasil disimpan";
		}
		else
		{
			$h1_title 	= "Add";
			$h2_title 	= "Add Amendment ";
			$h3_title	= "Receiving Goods";
			$alert1		= "Please select an Amendment  Category.";
			$alert2		= "Amendment Total Amount is greater then SI Amount.";
			$alert3		= "Amendment Total Amount is Zero.";
			$alert4		= "Please select SI Number.";
			$alert5		= "Please select SI category (Plus / Min).";
			$alert6		= "Plese input the reason why you revise/reject/void the document.";
			$alert7		= "Please select a budget parent.";
			$alert8		= "You have not selected the item details to be amended.";
			$alert9		= "You have not input the job detail code. Header code = ";
			$alert10	= "Job detail code can not same with job header code.";
			$alert11	= "Over Budget or Not Budgeting category cannot choose SI No.";
			$alert12	= "Item Volume can not be empty";
			$alert13	= "Item Price can not be empty";
			$alert14	= "Total transfer is greater then maximum remain.";
			$isManual	= "Check to manual code.";
			$alertSubmit= "data has been successfully saved";
		}

		$sqlPRJ1 	= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE' LIMIT 1";
		$restPRJ1 	= $this->db->query($sqlPRJ1)->result();
		foreach($restPRJ1 as $rowPRJ1) :
			$PRJNAME1 	= $rowPRJ1->PRJNAME;
		endforeach;

		$comp_color = $this->session->userdata('comp_color');
    ?>

	<style>
		.search-table, td, th {
			border-collapse: collapse;
		}
		.search-table-outter { overflow-x: scroll; }
	</style>

	<style>
		.inplabel {border:none;background-color:white;}
		.inplabelOK {border:none;background-color:white; color:#009933; font-weight:bold}
		.inplabelBad {border:none;background-color:white; color:#FF0000; font-weight:bold}
		.inplabelTOT {border:none;background-color:white; color:#06F; font-weight:bold}
		.inplabelTOTPPn {border:none;background-color:white; color:#933; font-weight:bold}
		.inplabelGT {border:none;background-color:white; color:#00F; font-weight:bold}
		.inpdim {border:none;background-color:white;}
	</style>

    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
			<h1>
			    <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/amendment.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $h2_title; ?>
			    <small><?php echo $PRJNAME1; ?></small>  </h1>
		</section>

		<section class="content">
			<div>
                <!-- after get JobcodeID code -->
                <form name="frmsrch1" id="frmsrch1" action="" method=POST style="display: none;">
                    <input type="text" name="PRJCODEX" id="PRJCODEX" value="<?php echo $PRJCODE; ?>" />
                    <input type="text" name="AMD_CATEGX" id="AMD_CATEGX" value="<?php echo $AMD_CATEG; ?>" />
                    <input type="text" name="JOBCODEIDX" id="JOBCODEIDX" value="<?php echo $JOBCODEID; ?>" />
                    <input type="submit" class="button_css" name="submitSrch1" id="submitSrch1" value=" search " />
                </form>
                <!-- End -->

                <form class="form-horizontal" name="frm" id="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return checkInp()">
			    	<div class="row">
						<div class="col-md-6">
							<div class="box box-primary">
								<div class="box-header with-border" style="display: none;">
									<i class="fa fa-cloud-upload"></i>
									<h3 class="box-title">&nbsp;</h3>
								</div>
								<div class="box-body">
			                    	<input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
			                        <input type="hidden" name="rowCount" id="rowCount" value="0">
			                        <input type="hidden" name="Patt_Number" id="Patt_Number" value="<?php echo $lastPattNumb; ?>">
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
			                        <div class="form-group" style="display: none;">
			                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $AMDNumber; ?></label>
			                          	<div class="col-sm-9">
			                                <input type="hidden" class="form-control" name="AMD_NUM" id="AMD_NUM" value="<?php echo $AMD_NUM; ?>" >
											<input type="text" class="form-control" name="AMD_NUM1" id="AMD_NUM1" value="<?php echo $AMD_NUM; ?>" disabled>
			                          	</div>
			                        </div>
			                        <div class="form-group">
			                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Code; ?></label>
			                          	<div class="col-sm-5">
											<input type="text" class="form-control" name="AMD_CODE" id="AMD_CODE" value="<?php echo $AMD_CODE; ?>" readonly>
			                          	</div>
			                          	<div class="col-sm-4">
			                                <div class="input-group date">
			                                    <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
			                                    <input type="text" name="AMD_DATE" class="form-control pull-left" id="datepicker1" value="<?php echo $AMD_DATE; ?>">
			                                </div>
			                          	</div>
			                        </div>
			                        <div class="form-group">
			                          	<label for="inputName" class="col-sm-3 control-label"><?php echo "$Category"; ?></label>
			                          	<div class="col-sm-6">
			                            	<select name="AMD_CATEG1" id="AMD_CATEG1" class="form-control select2" onChange="selCAT(this.value)" <?php if($AMD_STAT != 1 && $AMD_STAT != 4) { ?> disabled <?php } ?>>
			                                    <option value="0"> --- </option>
			                                    <option value="OB" <?php if($AMD_CATEG == 'OB') { ?> selected <?php } ?>>Over Budget - OB</option>
			                                    <option value="NB" <?php if($AMD_CATEG == 'NB') { ?> selected <?php } ?>>Not Budgeting - NB</option>
			                                    <option value="SI" <?php if($AMD_CATEG == 'SI') { ?> selected <?php } ?>>Site Instruction - SI</option>
			                                    <option value="SINJ" <?php if($AMD_CATEG == 'SINJ') { ?> selected <?php } ?>>Site Instruction - SI New Job</option>
			                                    <option value="OTH" <?php if($AMD_CATEG == 'OTH') { ?> selected <?php } ?>>Lainnya</option>
			                                </select>
			                                <input type="hidden" name="AMD_CATEG" class="form-control" id="AMD_CATEG" value="<?php echo $AMD_CATEG; ?>">
			                          	</div>
			                          	<div class="col-sm-3">
	                                        <select name="AMD_FUNC1" id="AMD_FUNC1" class="form-control select2" onChange="selFUNC(this.value)" <?php if($AMD_CATEG == 'OB' || $AMD_CATEG == 'NB' || $AMD_CATEG == 'OTH') { ?> disabled <?php } ?>>
	                                            <option value=""> --- </option>
	                                            <option value="PLUS" <?php if($AMD_FUNC == 'PLUS') { ?> selected <?php } ?>>Plus</option>
	                                            <option value="MIN" <?php if($AMD_FUNC == 'MIN') { ?> selected <?php } ?>>Minus</option>
	                                        </select>
			                                <input type="hidden" name="AMD_FUNC" class="form-control" id="AMD_FUNC" value="<?php echo $AMD_FUNC; ?>">
			                          	</div>
			                        </div>
			                        <script>
			                            function selCAT(AMD_CATEG)
			                            {
			                            	AMDCATEG_B 		= document.getElementById('AMD_CATEG').value;

			                                if(AMD_CATEG == 'SI')
			                                {
			                                	document.getElementById('itmName').innerHTML 		= '<?php echo $ItemCode ?>';
			                                	$('#btnselSI').removeAttr('disabled');
			                                	$('#AMD_REFNOX').removeAttr('disabled');
			                                	$('#AMD_FUNC1').removeAttr('disabled');
			                                	$('#AMD_FUNC1').val('').trigger('change');
		                                		$('#AMD_FUNC').val('');
	        									document.getElementById('btnModal').style.display 	= 'none';
	        									
	        									if(AMDCATEG_B == 'OTH')
	        									{
		        									$('#detItm1').removeClass('col-md-6').addClass('col-md-12');
		        									$('#OBColl').removeClass('box box-danger').addClass('box box-primary');
	        									}

	        									document.getElementById('OBItm').style.display 		= 'none';
	        									document.getElementById('SUBSItm').style.display 	= 'none';
		        								document.getElementById('detItm2').style.display 	= 'none';
	        									document.getElementById('colRemark').style.display 	= '';
	        									document.getElementById('itmUM').style.display 		= '';
			                                }
			                                else if(AMD_CATEG == 'SINJ')
			                                {
			                                	document.getElementById('itmName').innerHTML 		= '<?php echo $ItemCode ?>';
			                                	$('#btnselSI').removeAttr('disabled');
			                                	$('#AMD_REFNOX').removeAttr('disabled');
			                                	$('#AMD_FUNC1').removeAttr('disabled');
			                                	$('#AMD_FUNC1').val('PLUS').trigger('change');
			                                	$('#AMD_FUNC1').attr('disabled', 'disabled');
			                                	$('#AMD_FUNC').val('PLUS');
	        									document.getElementById('btnModal').style.display 	= 'none';
	        									
	        									if(AMDCATEG_B == 'OTH')
	        									{
		        									$('#detItm1').removeClass('col-md-6').addClass('col-md-12');
		        									$('#OBColl').removeClass('box box-danger').addClass('box box-primary');
	        									}

	        									document.getElementById('OBItm').style.display 		= 'none';
	        									document.getElementById('SUBSItm').style.display 	= 'none';
	        									document.getElementById('detItm2').style.display 	= 'none';
	        									document.getElementById('colRemark').style.display 	= '';
	        									document.getElementById('itmUM').style.display 		= '';
			                                }
			                                else if(AMD_CATEG == 'OTH')
			                                {
			                                	document.getElementById('itmName').innerHTML 		= '<?=$jobCd?>';
			                                	$('#btnselSI').attr('disabled', 'disabled');
			                                	$('#AMD_REFNOX').attr('disabled', 'disabled');
			                                	$('#AMD_FUNC1').attr('disabled', 'disabled');
			                                	$('#AMD_FUNC1').val('').trigger('change');
		                                		$('#AMD_FUNC').val('');
	        									document.getElementById('btnModal').style.display 	= 'none';

	        									$('#detItm1').removeClass('col-md-12').addClass('col-md-6');
	        									$('#OBColl').removeClass('box box-primary').addClass('box box-danger');
	        									document.getElementById('OBItm').style.display 		= '';
	        									document.getElementById('SUBSItm').style.display 	= '';
	        									document.getElementById('detItm2').style.display 	= '';
	        									document.getElementById('colRemark').style.display 	= 'none';
	        									document.getElementById('itmUM').style.display 		= 'none';
			                                }
			                                else
			                                {
			                                	document.getElementById('itmName').innerHTML 	= '<?=$jobCd?>';
			                                	$('#btnselSI').attr('disabled', 'disabled');
			                                	$('#AMD_REFNOX').attr('disabled', 'disabled');
			                                	$('#AMD_FUNC1').attr('disabled', 'disabled');
			                                	$('#AMD_FUNC1').val('').trigger('change');
		                                		$('#AMD_FUNC').val('');
	        									document.getElementById('btnModal').style.display 	= 'none';
	        									
	        									if(AMDCATEG_B == 'OTH')
	        									{
		        									$('#detItm1').removeClass('col-md-6').addClass('col-md-12');
		        									$('#OBColl').removeClass('box box-danger').addClass('box box-primary');
	        									}

	        									document.getElementById('OBItm').style.display 		= 'none';
	        									document.getElementById('SUBSItm').style.display 	= 'none';
	        									document.getElementById('detItm2').style.display 	= 'none';
	        									document.getElementById('colRemark').style.display 	= '';
	        									document.getElementById('itmUM').style.display 		= '';
			                                }
			                                $('#AMD_CATEG').val(AMD_CATEG);
		                                	$('#JOBCODEID').val('');
		                                	$('#JOBCODEID1').val('');
		                                	$('#JOBCODEID2').val('');

		                                	var tableHeaderRowCount = 2;
											var table = document.getElementById('tbl');
		                                	var rowCount = table.rows.length;
		                                	for (var i = tableHeaderRowCount; i < rowCount; i++) {
											    table.deleteRow(tableHeaderRowCount);
											}
			                            }

										function selFUNC(AMD_FUNC)
										{
											$('#AMD_FUNC').val(AMD_FUNC);
										}
									</script>
									<?php
										//if($AMD_CATEG == 'OB' || $AMD_CATEG == 'NB' || $AMD_CATEG == 'NB')
									?>
	                                <div class="form-group">
	                                    <label for="inputName" class="col-sm-3 control-label">No. SI</label>
	                                    <div class="col-sm-6">
	                                        <div class="input-group">
	                                            <div class="input-group-btn">
	                                                <button type="button" class="btn btn-primary" id="btnselSI" <?php if($AMD_STAT != 1 && $AMD_STAT != 4) { ?> disabled <?php } else { ?> onClick="selSI();" <?php } ?>><i class="fa fa-search" ></i></button>
	                                            </div>
	                                            <input type="hidden" class="form-control" name="AMD_REFNO" id="AMD_REFNO" value="<?php echo $AMD_REFNO; ?>" >
	                                            <input type="text" class="form-control" name="AMD_REFNOX" id="AMD_REFNOX" value="<?php echo $AMD_REFNO; ?>" <?php if($AMD_CATEG == 'OB' || $AMD_CATEG == 'NB' || $AMD_CATEG == 'OTH') { ?> disabled <?php } ?>>
					                        	<a class="btn btn-sm btn-warning" data-toggle="modal" data-target="#mdl_addSI" id="btnModalSI" style="display: none;">
					                        		<i class="glyphicon glyphicon-search"></i>&nbsp;&nbsp;
					                        	</a>
	                                        </div>
	                                    </div>
		                                <div class="col-sm-3">
		                                    <input type="text" class="form-control" style="text-align:right" name="AMD_REFNOAMX" id="AMD_REFNOAMX" value="<?php echo number_format($AMD_REFNOAM, 2); ?>" title="Total SI" readonly>
		                                    <input type="hidden" class="form-control" style="max-width:150px; text-align:right" name="AMD_REFNOAM" id="AMD_REFNOAM" value="<?php echo $AMD_REFNOAM; ?>" >
		                                </div>
	                                </div>
			                        <?php
			                        	/*$JOBDESC 	= "";
			                        	$sqlJDESCX 	= "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JOBCODEID' LIMIT 1";
										$resJDESCX 	= $this->db->query($sqlJDESCX)->result();
										foreach($resJDESCX as $rowJDESCX) :
											$JOBDESC 	= $rowJDESCX->JOBDESC;
										endforeach;*/
			                        	$JOBDESC 	= "";
			                        	$sqlJDESCX 	= "SELECT ITM_NAME AS JOBDESC FROM tbl_item WHERE ITM_CODE = '$JOBCODEID' LIMIT 1";
										$resJDESCX 	= $this->db->query($sqlJDESCX)->result();
										foreach($resJDESCX as $rowJDESCX) :
											$JOBDESC 	= $rowJDESCX->JOBDESC;
										endforeach;
			                        ?>
			                        <div class="form-group">
			                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $itmJob ?> </label>
			                          	<div class="col-sm-6">
			                                <div class="input-group">
			                                    <div class="input-group-btn">
													<button type="button" class="btn btn-primary" name="btnSelJID" id="btnSelJID" <?php if($AMD_STAT != 1 && $AMD_STAT != 4) { ?> disabled <?php } else { ?> onClick="selItem()" <?php } ?>><i class="fa fa-search"></i></button>
			                                    </div>
			                                    <input type="hidden" class="form-control" name="JOBCODEID" id="JOBCODEID" value="<?php echo $JOBCODEID; ?>" >
			                                    <input type="hidden" class="form-control" name="ITM_CODEH" id="ITM_CODEH" value="<?php echo $JOBCODEID; ?>" >
			                                    <input type="hidden" class="form-control" name="AMD_JOBDESC" id="AMD_JOBDESC" value="<?php echo $AMD_JOBDESC; ?>" >
			                                    <input type="hidden" class="form-control" name="AMD_UNIT" id="AMD_UNIT" value="<?php echo $AMD_UNIT; ?>" >
			                                    <input type="text" class="form-control" name="JOBCODEID1" id="JOBCODEID1" value="<?php echo "$JOBCODEID $JOBDESC"; ?>" onClick="selItem()">
			                                    <input type="hidden" class="form-control" name="JOBCODEID2" id="JOBCODEID2" value="<?php echo $JOBCODEID; ?>" data-toggle="modal" data-target="#mdl_addItm">
			                                </div>
			                            </div>
	                                    <div class="col-sm-3">
			                            	<input type="text" class="form-control" style="text-align:right" name="AMD_AMOUNTX" id="AMD_AMOUNTX" value="<?php echo number_format($AMD_AMOUNT, 2); ?>" Title="Total Amandemen" disabled >
			                            	<input type="hidden" class="form-control" style="max-width:150px; text-align:right" name="AMD_AMOUNT" id="AMD_AMOUNT" value="<?php echo $AMD_AMOUNT; ?>" >
	                                    </div>
			                        </div>
			                        <?php
										$url_selPR_CODE		= site_url('c_comprof/c_am1h0db2/pop1h0f0gSI/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			                        ?>
			                        <script>
										var url1 = "<?php echo $url_selPR_CODE;?>";
										function selSI()
										{
											PRJCODE 	= $("#PRJCODE").val();

			                                AMD_CATEG 	= document.getElementById('AMD_CATEG').value;

			                                if(AMD_CATEG == 0)
			                                {
			                                	swal("<?php echo $alert1; ?>",
			                                	{
			                                		icon:"warning"
			                                	});
			                                	return false;
			                                }
			                                else if(AMD_CATEG == 'OB' || AMD_CATEG == 'NB')
			                                {
			                                	swal("<?php echo $alert11; ?>",
			                                	{
			                                		icon:"warning"
			                                	});
			                                	return false;
			                                }
			                                else
			                                {
												var AMD_FUNC 	= document.getElementById('AMD_FUNC1').value;

												if(AMD_FUNC == '')
												{
													swal("<?php echo $alert5; ?>",
													{
														icon: "warning"
													})
													then(function()
													{
														swal.close();
													});
													return false;
												}

												document.getElementById('btnModalSI').click();
										    	$('#example2').DataTable(
										    	{
										    		"destroy": true,
										    		//"paging": false,
											        "processing": true,
											        "serverSide": true,
													//"scrollX": false,
													"autoWidth": true,
													"filter": true,
											        "ajax": "<?php echo site_url('c_comprof/c_am1h0db2/get_AllDataSI/?id=')?>"+PRJCODE,
											        "type": "POST",
													//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
													"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
													"columnDefs": [	{ targets: [0,2,3], className: 'dt-body-center' },
																	{ targets: [5], className: 'dt-body-right' },
																	{ sortable: false, targets: [4] }
																  ],
													"language": {
											            "infoFiltered":"",
											            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
											        },
												});
						    
											   	$("#idRefresh2").click(function()
											    {
													$('#example2').DataTable().ajax.reload();
											    });
										    }
										}
									</script>
			                        <div class="form-group" style="display:none">
			                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Project; ?></label>
			                          	<div class="col-sm-9">
			                            	<input type="hidden" class="form-control" style="max-width:195px" name="PRJCODE" id="PRJCODE" value="<?php echo $PRJCODE; ?>" >
			                            	<select name="selPRJCODE" id="selPRJCODE" class="form-control" <?php if($INV_STATUS != 1) { ?> disabled <?php } ?>>
				                                <?php
				                                    $sqlPRJ 	= "SELECT PRJCODE, PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
				                                    $resultPRJ 	= $this->db->query($sqlPRJ)->result();

													foreach($resultPRJ as $rowPRJ) :
														$PRJCODE1 	= $rowPRJ->PRJCODE;
														$PRJNAME1 	= $rowPRJ->PRJNAME;
														?>
															<option value="<?php echo $PRJCODE1; ?>" <?php if($PRJCODE1 == $PRJCODE) { ?>selected <?php } ?>>
																<?php echo $PRJNAME1; ?>
															</option>
														<?php
													 endforeach;

				                                ?>
				                            </select>
			                          	</div>
			                        </div>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="box box-warning">
								<div class="box-header with-border">
									<i class="fa fa-info-circle"></i>
									<h3 class="box-title"><?=$othInfo?></h3>
								</div>
								<div class="box-body">
			                        <div class="form-group">
			                       	  	<label for="inputName" class="col-sm-3 control-label"><?php echo $Notes; ?></label>
			                          	<div class="col-sm-9">
			                                <textarea class="form-control" name="AMD_NOTES"  id="AMD_NOTES" style="height:90px"><?php echo $AMD_NOTES; ?></textarea>
			                          	</div>
			                        </div>
			                        <div id="revMemo" class="form-group" <?php if($AMD_MEMO == '') { ?> style="display:none" <?php } ?>>
			                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $reviseNotes; ?> </label>
			                          	<div class="col-sm-9">
			                                <textarea class="form-control" name="AMD_MEMO"  id="AMD_MEMO" style="height:70px" disabled><?php echo $AMD_MEMO; ?></textarea>
			                          	</div>
			                        </div>
			                        <div class="form-group">
			                            <label for="inputName" class="col-sm-3 control-label"><?php echo $Status; ?></label>
			                            <div class="col-sm-6">
			                                <input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $AMD_STAT; ?>">
			                                <select name="AMD_STAT" id="AMD_STAT" class="form-control select2" onChange="chkSTAT(this.value)">
			                                    <?php
												$disableBtn	= 0;
												if($AMD_STAT == 5 || $AMD_STAT == 6 || $AMD_STAT == 9)
												{
													$disableBtn	= 1;
												}

			                                    if($AMD_STAT == 1 || $AMD_STAT == 4)
			                                    {
			                                        ?>
			                                            <option value="1"<?php if($AMD_STAT == 1) { ?> selected <?php } ?>>New</option>
			                                            <option value="2"<?php if($AMD_STAT == 2) { ?> selected <?php } ?>>Confirm</option>
			                                            <option value="4"<?php if($AMD_STAT == 4) { ?> selected <?php } else { ?> disabled <?php } ?>>Revising</option>
			                                        <?php
			                                    }
			                                    else
			                                    {
			                                        ?>
			                                            <option value="1"<?php if($AMD_STAT == 1) { ?> selected <?php } else { ?> disabled <?php } ?>>New</option>
			                                            <option value="2"<?php if($AMD_STAT == 2) { ?> selected <?php } else { ?> disabled <?php } ?>>Confirm</option>
			                                            <option value="3"<?php if($AMD_STAT == 3) { ?> selected <?php } else { ?> disabled <?php } ?>>Approve</option>
			                                            <option value="4"<?php if($AMD_STAT == 4) { ?> selected <?php } else { ?> disabled <?php } ?>>Revising</option>
			                                            <option value="5"<?php if($AMD_STAT == 5) { ?> selected <?php } else { ?> disabled <?php } ?>>Rejected</option>
			                                            <option value="6"<?php if($AMD_STAT == 6) { ?> selected <?php } else { ?> disabled <?php } ?>>Closed</option>
			                                            <option value="7"<?php if($AMD_STAT == 7) { ?> selected <?php } else { ?> disabled <?php } ?>>Waiting</option>
			                                            <option value="9"<?php if($AMD_STAT == 9) { ?> selected <?php } else { ?> disabled <?php } ?>>Void</option>
			                                        <?php
			                                    }
			                                    ?>
			                                </select>
			                            </div>
					                    <div class="col-sm-3">
					                        <div class="pull-right">
					                        	<a class="btn btn-sm btn-warning" id="btnModal" onClick="mdlJlist()">
					                        		<i class="glyphicon glyphicon-search"></i>&nbsp;&nbsp;<?php echo $SelectJob; ?>
					                        	</a>
					                        	<a class="btn btn-sm btn-warning" data-toggle="modal" data-target="#mdl_addJList" id="btnModalA" style="display: none;">
					                        		<i class="glyphicon glyphicon-search"></i>&nbsp;&nbsp;<?php echo $SelectJob; ?>
					                        	</a>
					                        	<a class="btn btn-sm btn-warning" data-toggle="modal" data-target="#mdl_addItm" id="btnModal1" style="display: none;">
					                        		<i class="glyphicon glyphicon-search"></i>&nbsp;&nbsp;<?php echo $SelectJob; ?>
					                        	</a>
					                        </div>
					                   	</div>
			                        </div>
			                        <script>
										function chkSTAT(selSTAT)
										{
											STAT_BEFORE = document.getElementById("STAT_BEFORE").value;
											if(selSTAT == 9)
											{
												document.getElementById('revMemo').style.display = '';
												document.getElementById("AMD_MEMO").disabled = false;
												document.getElementById('btnREJECT').style.display = '';
											}
											else
											{
												if(STAT_BEFORE == 4)
													document.getElementById('revMemo').style.display = '';
												else
													document.getElementById('revMemo').style.display = 'none';

												if(STAT_BEFORE == 9)
													document.getElementById('btnREJECT').style.display = 'none';
											}
										}

										function selITM()
										{
											/*document.getElementById('btnModal1').click();
											PRJCODE 	= $("#PRJCODE").val();
											AMD_CATEG 	= $("#AMD_CATEG").val();
											JOBCODEID 	= $("#JOBCODEID").val();
											collID 		= PRJCODE+'~'+AMD_CATEG+'~'+JOBCODEID;
											//collID 		= PRJCODE+'~OB~A.01.03';

									    	$('#example1').DataTable(
									    	{
									    		"destroy": true,
									    		//"paging": false,
										        "processing": true,
										        "serverSide": true,
												//"scrollX": false,
												"autoWidth": true,
												"filter": true,
										        "ajax": "<?php echo site_url('c_comprof/c_am1h0db2/get_AllDataITM/?id=')?>"+collID,
										        "type": "POST",
												//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
												"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
												"columnDefs": [	{ targets: [0], className: 'dt-body-center' },
																{ targets: [2,3,4,5], className: 'dt-body-right' },
																{ sortable: false, targets: [2,3,4,5] }
															  ],
												"language": {
										            "infoFiltered":"",
										            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
										        },
											});
						    
										   	$("#idRefresh1").click(function()
										    {
												$('#example1').DataTable().ajax.reload();
										    });*/
										}
									</script>
			                        <?php
										$theProjCode 	= "$PRJCODE~$AMD_CATEG";
			                        	$url_AddItem	= site_url('c_comprof/c_am1h0db2/g374llItem_7im/?id='.$this->url_encryption_helper->encode_url($theProjCode));
									?>
								</div>
							</div>
						</div>
					</div>
			    	<div class="row">
	                    <div <?php if($AMD_CATEG == 'OTH') { ?> class="col-md-6" <?php } else { ?> class="col-md-12" <?php } ?> id="detItm1">
	                        <div class="box box-primary" id="OBColl">
								<div class="box-header with-border" id="OBItm" <?php if($AMD_CATEG != 'OTH') { ?> style="display: none;" <?php } ?>>
									<i class="glyphicon glyphicon-circle-arrow-down"></i>
									<h3 class="box-title">Daftar Item Over Budget</h3>
								</div>
								<div class="box-body">
			                        <div class="search-table-outter">
			                            <table id="tbl" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
		                                    <tr style="background:#CCCCCC">
												<th width="2%" style="text-align:center; vertical-align: middle;">No.</th>
												<th width="15%" style="text-align:center; vertical-align: middle; <?php if($AMD_CATEG == 'OTH') { ?> display: none; <?php } ?>" id="colItmNm"><div id="itmName"><?php echo $ItemCode ?></div></th>
												<th width="30%" style="text-align:center; vertical-align: middle;"><?php echo $JobNm ?> </th>
												<th width="10%" style="text-align:center; vertical-align: middle;" nowrap><?=$RemBudget?></th>
												<th width="10%" style="text-align:center; vertical-align: middle;" colspan="2">Vol. Amd.</th>
												<th width="13%" style="text-align:center; vertical-align: middle;" colspan="2"><?php echo $Price ?></th>
												<th width="5%" style="text-align:center; vertical-align: middle; <?php if($AMD_CATEG == 'OTH') { ?> display: none; <?php } ?>" id="itmUM"><?php echo $Unit ?></th>
												<th width="25%" style="text-align:center; vertical-align: middle; <?php if($AMD_CATEG == 'OTH') { ?> display: none; <?php } ?>" id="colRemark"><?php echo $Remarks ?> </th>
		                                    </tr>
		                                    <?php
		                                    if($task == 'edit')
		                                    {
		                                        $sqlDET	= "SELECT DISTINCT A.AMD_NUM, A.JOBCODEID, A.ITM_CODE, A.ITM_UNIT,
																A.AMD_VOLM, A.REM_BUDG, A.AMD_PRICE, A.AMD_TOTAL, A.AMD_DESC, A.AMD_CLASS, A.JOBPARENT,
																B.ITM_NAME, B.ITM_GROUP,
																C.REQ_VOLM, C.REQ_AMOUNT,
																C.ITM_PRICE, C.ITM_VOLM, C.ITM_USED, C.ITM_USED_AM,
																C.ITM_STOCK, C.ITM_STOCK_AM
		                                                    FROM tbl_amd_detail A
		                                                        INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
		                                                            AND B.PRJCODE = '$PRJCODE'
																LEFT JOIN tbl_joblist_detail C ON C.PRJCODE = '$PRJCODE'
																	AND C.JOBCODEID = A.JOBCODEID
		                                                    WHERE AMD_NUM = '$AMD_NUM'
		                                                        AND B.PRJCODE = '$PRJCODE'";
		                                        $result = $this->db->query($sqlDET)->result();
		                                        $i		= 0;
		                                        $j		= 0;

		                                        foreach($result as $row) :
		                                            $currentRow  	= ++$i;
		                                            $AMD_NUM 		= $row->AMD_NUM;
		                                            $JOBCODEID 		= $row->JOBCODEID;
		                                            $ITM_CODE 		= $row->ITM_CODE;
		                                            $ITM_GROUP 		= $row->ITM_GROUP;
		                                            $ITM_UNIT 		= strtoupper($row->ITM_UNIT);
		                                            $AMD_VOLM 		= $row->AMD_VOLM;
		                                            $REM_BUDG 		= $row->REM_BUDG;
		                                            $AMD_PRICE 		= $row->AMD_PRICE;
		                                            $AMD_TOTAL 		= $row->AMD_TOTAL;
		                                            $AMD_DESC 		= $row->AMD_DESC;
													$AMD_CLASS		= $row->AMD_CLASS;
		                                            $ITM_NAME 		= $row->ITM_NAME;
		                                            $JOBPARENT 		= $row->JOBPARENT;

													$ITM_REMV 		= 0;
		                                            $ITM_REMAMN 	= 0;
		                                            $s_JDSC 		= "SELECT (ITM_VOLM - REQ_VOLM) AS ITM_REMV, (ITM_BUDG - REQ_AMOUNT) AS ITM_REMAMN
		                                            					FROM tbl_joblist_detail
		                                            					WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' LIMIT 1";
													$r_JDSC			= $this->db->query($s_JDSC)->result();
													foreach($r_JDSC as $rw_JDSC):
														$ITM_REMV	= $rw_JDSC->ITM_REMV;
														$ITM_REMAMN	= $rw_JDSC->ITM_REMAMN;
													endforeach;

		                                            $JOBPARDESC		= "";
		                                            $s_JDSC 		= "SELECT JOBDESC FROM tbl_joblist_detail
		                                            					WHERE JOBCODEID = '$JOBPARENT' AND PRJCODE = '$PRJCODE' LIMIT 1";
													$r_JDSC			= $this->db->query($s_JDSC)->result();
													foreach($r_JDSC as $rw_JDSC):
														$JOBPARDESC	= wordwrap($rw_JDSC->JOBDESC, 30, "<br>", true);
													endforeach;

		                                            $REQ_VOLM 		= $row->REQ_VOLM;
		                                            $REQ_AMOUNT 	= $row->REQ_AMOUNT;
		                                            $ITM_PRICE 		= $row->ITM_PRICE;
		                                            $ITM_VOLM 		= $row->ITM_VOLM;			// VOLM BUDGET
		                                            //$ITM_REMV 	= $ITM_VOLM - $REQ_VOLM;
		                                            $ITM_USED 		= $row->ITM_USED;
		                                            $ITM_USED_AM	= $row->ITM_USED_AM;
		                                            $ITM_STOCK 		= $row->ITM_STOCK;
		                                            $ITM_STOCK_AM	= $row->ITM_STOCK_AM;

													if($AMD_CATEG == 'OTH')
													{
														//$ITM_REMV 	= $AMD_TOTAL - $REQ_AMOUNT;
														$ITM_VOLM1	= 0;
														$ADD_VOLM1	= 0;
														$REQ_VOLM	= 0;
														$sql1	= "SELECT DISTINCT ITM_PRICE, ITM_VOLM, ADDVOLM AS ADD_VOLM,
																		PR_VOLM AS REQ_VOLM
																	FROM tbl_item
																		WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
														$res1	= $this->db->query($sql1)->result();
														foreach($res1 as $row1) :
															$ITM_VOLM1	= $row1->ITM_VOLM;
															$ADD_VOLM1	= $row1->ADD_VOLM;
															$ITM_USED	= $row1->REQ_VOLM;
														endforeach;
														$ITM_VOLM		= $ITM_VOLM1 + $ADD_VOLM1;
														if($ITM_VOLM == '')
															$ITM_VOLM	= 0;
														if($ITM_USED == '')
															$ITM_USED	= 0;
													}

		                                        	/*if ($j==1) {
		                                                echo "<tr class=zebra1>";
		                                                $j++;
		                                            } else {
		                                                echo "<tr class=zebra2>";
		                                                $j--;
		                                            }*/
		                                            ?>
		                                            <tr id="tr_<?php echo $currentRow; ?>">
			                                             <!-- NO URUT -->
			                                            <td height="25" style="text-align:center;">
			                                              	<?php
				                                                if($AMD_STAT == 1)
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

			                                            <!-- ITM_CODE : ITM_NAME -->
			                                            <td <?php if($AMD_CATEG == 'OTH') { ?> style="display: none;" <?php } else { ?> style="text-align: center;" <?php } ?>>
															<?php echo "$ITM_CODE"; ?>
			                                                <input type="hidden" id="data<?php echo $currentRow; ?>ITM_GROUP" name="data[<?php echo $currentRow; ?>][ITM_GROUP]" value="<?php echo $ITM_GROUP; ?>" class="form-control" style="max-width:300px;">
			                                                <input type="hidden" id="data<?php echo $currentRow; ?>ITM_CODE" name="data[<?php echo $currentRow; ?>][ITM_CODE]" value="<?php echo $ITM_CODE; ?>" class="form-control" style="max-width:300px;">
			                                                <input type="hidden" id="data<?php echo $currentRow; ?>AMD_NUM" name="data[<?php echo $currentRow; ?>][AMD_NUM]" value="<?php echo $AMD_NUM; ?>" class="form-control" style="max-width:300px;">
			                                                <input type="hidden" name="data[<?php echo $currentRow; ?>][JOBCODEID]" id="data<?php echo $currentRow; ?>JOBCODEID" value="<?php echo $JOBCODEID; ?>" class="form-control" >
			                                                <input type="hidden" name="data[<?php echo $currentRow; ?>][JOBPARENT]" id="data<?php echo $currentRow; ?>JOBPARENT" value="<?php echo $JOBPARENT; ?>" class="form-control" >
			                                          	</td>

			                                            <!-- ITM_NAME -->
			                                          	<td style="text-align:left">
															<label style='white-space:nowrap'>
																<?php echo "$JOBCODEID : $ITM_NAME"; ?>
															</label>
			                                                <input type="hidden" id="data<?php echo $currentRow; ?>JOBDESC" name="data[<?php echo $currentRow; ?>][JOBDESC]" value="<?php echo $ITM_NAME; ?>" class="form-control" style="max-width:300px;">
														  	<div style='margin-left: 10px; font-style: italic; white-space:nowrap'>
														  		<?php echo "$JOBPARENT : $JOBPARDESC"?>
														  	</div>
			                                          	</td>

			                                            <!-- REMAIN VOLUME -->
			                                            <?php
			                                            	$COLLDATA 		= "'$JOBCODEID~$JOBPARENT'";
			                                            ?>
			                                         	<td style="text-align:right;" nowrap>
			                                                <span class='label label-danger' style='font-size:12px'>
			                                               		<?php echo number_format($ITM_REMAMN, 2); ?>
			                                                </span>
			                                                <input type="hidden" id="ITM_REMV<?php echo $currentRow; ?>" value="<?php echo $ITM_REMV; ?>" class="form-control" style="max-width:300px;" >
			                                                <br>
			                                                <a href="#" onClick="mdlJlistSubs(<?=$COLLDATA?>)" title="Tambah Budget" class="btn btn-warning btn-xs" <?php if($AMD_CATEG != 'OTH') { ?> style="display: none;" <?php } ?>><i class="glyphicon glyphicon-transfer"></i></a><br>
			                                                <span class='label label-success' style='font-size:12px; <?php if($AMD_CATEG != 'OTH') { ?> display: none; <?php } ?>' id='idAMDTOTAL<?php echo $currentRow; ?>' title="Nilai transfer dari pekerjaan lain">
			                                               		<?php echo number_format($AMD_TOTAL, 2); ?>
			                                                </span>
			                                                <a class="btn btn-sm btn-warning" data-toggle="modal" data-target="#mdl_addJListSubs" id="btnModalB" style="display: none;"><i class="glyphicon glyphicon-search"></i></a>
			                                            </td>

			                                            <!-- AMD_CLASS -->
			                                         	<td style="text-align:center" nowrap>
			                                                <input type="hidden" name="data[<?php echo $currentRow; ?>][AMD_CLASS]" id="data<?php echo $currentRow; ?>AMD_CLASS" value="<?php echo $AMD_CLASS; ?>">
			                                         		<?php if($AMD_STAT == 1 || $AMD_STAT == 4) { ?>
			                                                	<input type="checkbox" name="data<?php echo $currentRow; ?>AMD_CLASS" id="AMD_CLASS1<?php echo $currentRow; ?>" value="2" onClick="chgRad1(this,<?php echo $currentRow; ?>);" <?php if($AMD_CLASS == 2) { ?> checked <?php } ?>>
			                                                <?php } else { ?>
			                                                	<input type="checkbox" name="data<?php echo $currentRow; ?>AMD_CLASS" id="AMD_CLASS1<?php echo $currentRow; ?>" value="2" onClick="chgRad1(this,<?php echo $currentRow; ?>);" <?php if($AMD_CLASS == 2) { ?> checked <?php } ?> disabled>
			                                                <?php } ?>
			                                            </td>

			                                            <!-- AMD_VOLM -->
			                                            <?php
			                                            	if($AMD_CATEG == 'OTH')
			                                            	{
			                                            		$chgVoLF1 	= "chgVolOTH";
			                                            		$chgVoLF2 	= "chgPriceOTH";
			                                            	}
			                                            	else
			                                            	{
			                                            		$chgVoLF1 	= "chgPrice";
			                                            		$chgVoLF2 	= "chgPrice";
			                                            	}
			                                            ?>
			                                         	<td style="text-align:center" nowrap>
			                                         		<?php if($AMD_STAT == 1 || $AMD_STAT == 4) { ?>
			                                                	<input type="text" name="AMD_VOLM<?php echo $currentRow; ?>" id="AMD_VOLM<?php echo $currentRow; ?>" value="<?php echo number_format($AMD_VOLM, 2); ?>" class="form-control inplabel" style="min-width:60px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="<?=$chgVoLF1?>(this,<?php echo $currentRow; ?>);" <?php if($AMD_CLASS == 1) { ?> disabled <?php } ?> >
			                                                <?php } else { ?>
			                                                	<?php echo number_format($AMD_VOLM, 2); ?>
			                                                	<input type="hidden" name="AMD_VOLM<?php echo $currentRow; ?>" id="AMD_VOLM<?php echo $currentRow; ?>" value="<?php echo number_format($AMD_VOLM, 2); ?>" class="form-control inplabel" style="min-width:60px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="<?=$chgVoLF1?>(this,<?php echo $currentRow; ?>);" <?php if($AMD_CLASS == 1) { ?> disabled <?php } ?> >
			                                                <?php } ?>
			                                                <input type="hidden" name="data[<?php echo $currentRow; ?>][AMD_VOLM]" id="data<?php echo $currentRow; ?>AMD_VOLM" value="<?php echo $AMD_VOLM; ?>" class="form-control" style="max-width:300px;" >
			                                            </td>

			                                            <!-- AMD_CLASS -->
			                                         	<td style="text-align:center">
			                                         		<?php
				                                         		if($ITM_UNIT == 'LS' || $ITM_UNIT == 'LUMP')
				                                         		{
				                                         			?>
				                                         				<input type="checkbox" name="data<?php echo $currentRow; ?>AMD_CLASS" id="AMD_CLASS2<?php echo $currentRow; ?>" value="1" onClick="chgRad2(this,<?php echo $currentRow; ?>);" style="display: none;" <?php if($AMD_CLASS == 1) { ?> checked <?php } ?>>
				                                         			<?php
				                                         		}
				                                         		else
				                                         		{
					                                         		if($AMD_STAT == 1 || $AMD_STAT == 4) 
					                                         		{
					                                         			?>
					                                                		<input type="checkbox" name="data<?php echo $currentRow; ?>AMD_CLASS" id="AMD_CLASS2<?php echo $currentRow; ?>" value="1" onClick="chgRad2(this,<?php echo $currentRow; ?>);" <?php if($AMD_CLASS == 1) { ?> checked <?php } ?>>
					                                                	<?php
					                                                }
					                                                else
					                                                {
					                                                	?>
					                                                		<input type="checkbox" name="data<?php echo $currentRow; ?>AMD_CLASS" id="AMD_CLASS2<?php echo $currentRow; ?>" value="1" onClick="chgRad2(this,<?php echo $currentRow; ?>);" <?php if($AMD_CLASS == 1) { ?> checked <?php } ?> disabled>
					                                               		<?php
					                                               	}
				                                               	}
			                                               	?>
			                                            </td>

			                                            <!-- AMD_PRICE -->
			                                         	<td style="text-align:right">
			                                         		<?php
				                                         		/*if($ITM_UNIT == 'LS' || $ITM_UNIT == 'LUMP')
				                                         		{
				                                         			?>
				                                         				<input type="hidden" name="AMD_PRICE<?php echo $currentRow; ?>" id="AMD_PRICE<?php echo $currentRow; ?>" value="<?php echo number_format($AMD_PRICE, 2); ?>" class="form-control inplabel" style="min-width:80px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="<?=$chgVoLF2?>(this,<?php echo $currentRow; ?>);" <?php if($AMD_CLASS == 2) { ?> disabled <?php } ?> >
				                                         			<?php
				                                         		}
				                                         		else
				                                         		{*/
					                                         		if($AMD_STAT == 1 || $AMD_STAT == 4)
					                                         		{
					                                         			?>
					                                                		<input type="text" name="AMD_PRICE<?php echo $currentRow; ?>" id="AMD_PRICE<?php echo $currentRow; ?>" value="<?php echo number_format($AMD_PRICE, 2); ?>" class="form-control inplabel" style="min-width:80px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="<?=$chgVoLF2?>(this,<?php echo $currentRow; ?>);" <?php if($AMD_CLASS == 2) { ?> disabled <?php } ?> >
					                                                	<?php
					                                                }
					                                                else
					                                                {
					                                                	?>
						                                                	<?php
						                                                		echo number_format($AMD_PRICE, 2);
						                                                	?>
						                                                	<input type="hidden" name="AMD_PRICE<?php echo $currentRow; ?>" id="AMD_PRICE<?php echo $currentRow; ?>" value="<?php echo number_format($AMD_PRICE, 2); ?>"  class="form-control inplabel" style="min-width:80px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="<?=$chgVoLF2?>(this,<?php echo $currentRow; ?>);" <?php if($AMD_CLASS == 2) { ?> disabled <?php } ?> >
					                                                	<?php
					                                                }
					                                            //}
			                                                ?>
			                                                <input type="hidden" name="data[<?php echo $currentRow; ?>][AMD_PRICE]" id="data<?php echo $currentRow; ?>AMD_PRICE" value="<?php echo $AMD_PRICE; ?>" class="form-control" style="max-width:300px;" >
			                                                <input type="hidden" name="data[<?php echo $currentRow; ?>][AMD_TOTAL]" id="data<?php echo $currentRow; ?>AMD_TOTAL" value="<?php echo $AMD_TOTAL; ?>" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" >
			                                                <input type="hidden" id="data<?php echo $currentRow; ?>AMD_MAXVAL" value="<?php echo $AMD_TOTAL; ?>" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" >
			                                           	</td>

			                                            <!-- ITM_UNIT -->
			                                            <td <?php if($AMD_CATEG == 'OTH') { ?> style="text-align:center; display: none"; <?php } ?>>
			                                            	<?php echo $ITM_UNIT; ?>
			                                                <input type="hidden" name="data[<?php echo $currentRow; ?>][ITM_UNIT]" id="data<?php echo $currentRow; ?>ITM_UNIT" value="<?php echo $ITM_UNIT; ?>" class="form-control" style="max-width:300px;" >
														</td>

			                                            <!-- AMD_DESC -->
			                                            <td <?php if($AMD_CATEG == 'OTH') { ?> style="display: none"; <?php } ?>>
			                                         		<?php if($AMD_STAT == 1 || $AMD_STAT == 4) { ?>
			                                            		<input type="text" name="data[<?php echo $currentRow; ?>][AMD_DESC]" id="data<?php echo $currentRow; ?>AMD_DESC" size="20" value="<?php echo $AMD_DESC; ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:left">
			                                                <?php } else { ?>
			                                                	<?php echo $AMD_DESC; ?>
			                                            		<input type="hidden" name="data[<?php echo $currentRow; ?>][AMD_DESC]" id="data<?php echo $currentRow; ?>AMD_DESC" size="20" value="<?php echo $AMD_DESC; ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:left">
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
	                    </div>
	                    <div class="col-md-6" <?php if($AMD_CATEG != 'OTH') { ?> style="display: none;" <?php } ?> id="detItm2">
	                        <div class="box box-success" id="SUBSItm">
								<div class="box-header with-border">
									<i class="glyphicon glyphicon-circle-arrow-left"></i>
									<h3 class="box-title">Item Pengganti</h3>
								</div>
								<div class="box-body">
			                        <div class="search-table-outter">
			                            <table id="tbl_sub" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
		                                    <tr style="background:#CCCCCC">
												<th width="5%" style="text-align:center; vertical-align: middle;">No.</th>
												<th width="50%" style="text-align:center; vertical-align: middle;"><?php echo $JobNm ?> </th>
												<th width="10%" style="text-align:center; vertical-align: middle; display: none;">Volume</th>
												<th width="10%" style="text-align:center; vertical-align: middle;" nowrap><?php echo $RemBudget ?></th>
												<th width="5%" style="text-align:center; vertical-align: middle; display: none;"><?php echo $Unit ?> </th>
												<th width="10%" style="text-align:center; vertical-align: middle;" nowrap><?php echo $tsfAmount ?> </th>
		                                    </tr>
		                                    <?php
		                                    if($task == 'edit')
		                                    {
		                                        $sqlDET	= "SELECT A.AMD_NUM, A.JOBCODEIDH, A.JOBCODEID, A.JOBPARENT, A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT,
																A.JOBDESC, A.AMD_CLASS, A.AMD_VOLM, A.REM_BUDG, A.AMD_PRICE, A.AMD_TOTAL, A.AMD_DESC,
																A.AMD_TOTTSF
		                                                    FROM tbl_amd_detail_subs A
		                                                        INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
		                                                            AND B.PRJCODE = '$PRJCODE'
																LEFT JOIN tbl_joblist_detail C ON C.PRJCODE = '$PRJCODE'
																	AND C.JOBCODEID = A.JOBCODEID
		                                                    WHERE AMD_NUM = '$AMD_NUM'
		                                                        AND B.PRJCODE = '$PRJCODE'";
		                                        $result = $this->db->query($sqlDET)->result();
		                                        $i		= 0;
		                                        $j		= 0;

		                                        foreach($result as $row) :
		                                            $currentRow  	= ++$i;
		                                            $AMD_NUM 		= $row->AMD_NUM;
		                                            $JOBCODEIDH 	= $row->JOBCODEIDH;
		                                            $JOBCODEID 		= $row->JOBCODEID;
		                                            $JOBPARENT 		= $row->JOBPARENT;
		                                            $ITM_GROUP 		= $row->ITM_GROUP;
		                                            $ITM_CODE 		= $row->ITM_CODE;
		                                            $ITM_UNIT 		= strtoupper($row->ITM_UNIT);
		                                            $ITM_NAME 		= $row->JOBDESC;
													$AMD_CLASS		= $row->AMD_CLASS;
		                                            $AMD_VOLM 		= $row->AMD_VOLM;
		                                            $REM_BUDG 		= $row->REM_BUDG;
		                                            $AMD_PRICE 		= $row->AMD_PRICE;
		                                            $AMD_TOTAL 		= $row->AMD_TOTAL;
		                                            $AMD_DESC 		= $row->AMD_DESC;
		                                            $AMD_TOTTSF 	= $row->AMD_TOTTSF;

		                                            // START : JOBPARENT H
			                                            $JOBPARITM		= "";
			                                            $JOBPARITMD		= "";
			                                            $s_JDSCA 		= "SELECT JOBCODEID, JOBDESC FROM tbl_joblist_detail
			                                            					WHERE JOBCODEID = (SELECT JOBPARENT FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBCODEIDH' AND PRJCODE = '$PRJCODE' LIMIT 1) AND PRJCODE = '$PRJCODE' LIMIT 1";
														$r_JDSCA		= $this->db->query($s_JDSCA)->result();
														foreach($r_JDSCA as $rw_JDSCA):
															$JOBPARITM	= $rw_JDSCA->JOBCODEID;
															$JOBPARITMD	= wordwrap($rw_JDSCA->JOBDESC, 30, "<br>", true);
														endforeach;

			                                            $ITM_CODEH 		= "";
			                                            $ITM_NAMEH 		= "";
			                                            $s_JDSCA 		= "SELECT ITM_CODE FROM tbl_joblist_detail
			                                            					WHERE JOBCODEID = '$JOBCODEIDH' AND PRJCODE = '$PRJCODE' LIMIT 1";
														$r_JDSCA		= $this->db->query($s_JDSCA)->result();
														foreach($r_JDSCA as $rw_JDSCA):
															$ITM_CODEH	= $rw_JDSCA->ITM_CODE;

				                                            $s_ITMH 	= "SELECT ITM_NAME FROM tbl_item
				                                            				WHERE ITM_CODE = '$ITM_CODEH' AND PRJCODE = '$PRJCODE' LIMIT 1";
															$r_ITMH		= $this->db->query($s_ITMH)->result();
															foreach($r_ITMH as $rw_ITMH):
																$ITM_NAMEH	= $rw_ITMH->ITM_NAME;
															endforeach;
														endforeach;
		                                            // END : JOBPARENT H

		                                            // START : JOBPARENT
			                                            $JOBPARDESC		= "";
			                                            $s_JDSC 		= "SELECT JOBDESC FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBPARENT'
			                                            					AND PRJCODE = '$PRJCODE' LIMIT 1";
														$r_JDSC			= $this->db->query($s_JDSC)->result();
														foreach($r_JDSC as $rw_JDSC):
															$JOBPARDESC	= wordwrap($rw_JDSC->JOBDESC, 30, "<br>", true);
														endforeach;
		                                            // END : JOBPARENT

													// START : REMAIN
			                                            $ITM_REMV 		= 0;
			                                            $ITM_REMAMN 	= 0;
			                                            $s_JDSC 		= "SELECT (ITM_VOLM - REQ_VOLM) AS ITM_REMV, (ITM_BUDG - REQ_AMOUNT) AS ITM_REMAMN
			                                            					FROM tbl_joblist_detail
			                                            					WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' LIMIT 1";
														$r_JDSC			= $this->db->query($s_JDSC)->result();
														foreach($r_JDSC as $rw_JDSC):
															$ITM_REMV	= $rw_JDSC->ITM_REMV;
															$ITM_REMAMN	= $rw_JDSC->ITM_REMAMN;
														endforeach;
													// END : REMAIN
		                                            ?>
		                                            <tr id="tr_<?php echo $currentRow; ?>">
			                                            <td height="25" style="text-align:center;">
			                                              	<?php
				                                                if($AMD_STAT == 1)
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

			                                            <!-- ITM_NAME -->
			                                          	<td style="text-align:left">
															<label style='white-space:nowrap'>
																<?php echo "$JOBCODEID : $ITM_NAME ($ITM_UNIT)"; ?>
															</label>
														  	<div style='margin-left: 10px; font-style: italic; white-space:nowrap'>
														  		<?php echo "$JOBPARENT : $JOBPARDESC". ' <i class="fa fa-arrow-right"></i> '."$ITM_CODEH ($JOBCODEIDH) : $ITM_NAMEH"; ?>
														  	</div>
			                                                <input type="hidden" id="dataSUB<?php echo $currentRow; ?>AMD_NUM" name="dataSUB[<?php echo $currentRow; ?>][AMD_NUM]" value="<?php echo $AMD_NUM; ?>" class="form-control" style="max-width:300px;">
														  	<input type="hidden" name="dataSUB[<?php echo $currentRow; ?>][JOBCODEIDH]" id="dataSUB<?php echo $currentRow; ?>JOBCODEIDH" value="<?=$JOBCODEIDH?>" class="form-control" >
			                                                <input type="hidden" name="dataSUB[<?php echo $currentRow; ?>][JOBCODEID]" id="dataSUB<?php echo $currentRow; ?>JOBCODEID" value="<?php echo $JOBCODEID; ?>" class="form-control" >
			                                                <input type="hidden" name="dataSUB[<?php echo $currentRow; ?>][JOBPARENT]" id="dataSUB<?php echo $currentRow; ?>JOBPARENT" value="<?php echo $JOBPARENT; ?>" class="form-control" >
			                                                <input type="hidden" id="dataSUB<?php echo $currentRow; ?>ITM_GROUP" name="dataSUB[<?php echo $currentRow; ?>][ITM_GROUP]" value="<?php echo $ITM_GROUP; ?>" class="form-control" style="max-width:300px;">
			                                                <input type="hidden" id="dataSUB<?php echo $currentRow; ?>ITM_CODE" name="dataSUB[<?php echo $currentRow; ?>][ITM_CODE]" value="<?php echo $ITM_CODE; ?>" class="form-control" style="max-width:300px;">
			                                                <input type="hidden" name="dataSUB[<?php echo $currentRow; ?>][ITM_UNIT]" id="dataSUB<?php echo $currentRow; ?>ITM_UNIT" value="<?php echo $ITM_UNIT; ?>" class="form-control" style="max-width:300px;" >
			                                                <input type="hidden" id="dataSUB<?php echo $currentRow; ?>JOBDESC" name="dataSUB[<?php echo $currentRow; ?>][JOBDESC]" value="<?php echo $ITM_NAME; ?>" class="form-control" style="max-width:300px;">
			                                          	</td>

			                                            <!-- AMD_CLASS -->
			                                         	<td style="text-align:center; display: none;" nowrap>
			                                                <input type="hidden" name="dataSUB[<?php echo $currentRow; ?>][AMD_CLASS]" id="dataSUB<?php echo $currentRow; ?>AMD_CLASS" value="<?php echo $AMD_CLASS; ?>">
			                                         		<?php if($AMD_STAT == 1 || $AMD_STAT == 4) { ?>
			                                                	<input type="checkbox" name="dataSUB<?php echo $currentRow; ?>AMD_CLASS" id="AMD_CLASS1<?php echo $currentRow; ?>" value="2" onClick="chgRad1(this,<?php echo $currentRow; ?>);" <?php if($AMD_CLASS == 2) { ?> checked <?php } ?>>
			                                                <?php } else { ?>
			                                                	<input type="checkbox" name="dataSUB<?php echo $currentRow; ?>AMD_CLASS" id="AMD_CLASS1<?php echo $currentRow; ?>" value="2" onClick="chgRad1(this,<?php echo $currentRow; ?>);" <?php if($AMD_CLASS == 2) { ?> checked <?php } ?> disabled>
			                                                <?php } ?>
			                                            </td>

			                                            <!-- AMD_VOLM -->
			                                         	<td style="text-align:center; display: none;" nowrap>
			                                         		<?php if($AMD_STAT == 1 || $AMD_STAT == 4) { ?>
			                                                	<input type="text" name="AMD_VOLM<?php echo $currentRow; ?>" id="AMD_VOLM<?php echo $currentRow; ?>" value="<?php echo number_format($AMD_VOLM, 2); ?>" class="form-control" style="min-width:80px; max-width:100px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgPrice(this,<?php echo $currentRow; ?>);" <?php if($AMD_CLASS == 1) { ?> disabled <?php } ?> >
			                                                <?php } else { ?>
			                                                	<?php echo number_format($AMD_VOLM, 2); ?>
			                                                	<input type="hidden" name="AMD_VOLM<?php echo $currentRow; ?>" id="AMD_VOLM<?php echo $currentRow; ?>" value="<?php echo number_format($AMD_VOLM, 2); ?>" class="form-control" style="min-width:80px; max-width:100px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgPrice(this,<?php echo $currentRow; ?>);" <?php if($AMD_CLASS == 1) { ?> disabled <?php } ?> >
			                                                <?php } ?>
			                                                <input type="hidden" name="dataSUB[<?php echo $currentRow; ?>][AMD_VOLM]" id="dataSUB<?php echo $currentRow; ?>AMD_VOLM" value="<?php echo $AMD_VOLM; ?>" class="form-control" style="max-width:300px;" >
			                                            </td>

			                                            <!-- AMD_CLASS -->
			                                         	<td style="text-align:center; display: none;">
			                                         		<?php
				                                         		if($ITM_UNIT == 'LS' || $ITM_UNIT == 'LUMP')
				                                         		{
				                                         			?>
				                                         				<input type="checkbox" name="dataSUB<?php echo $currentRow; ?>AMD_CLASS" id="AMD_CLASS2<?php echo $currentRow; ?>" value="1" onClick="chgRad2(this,<?php echo $currentRow; ?>);" style="display: none;" <?php if($AMD_CLASS == 1) { ?> checked <?php } ?>>
				                                         			<?php
				                                         		}
				                                         		else
				                                         		{
					                                         		if($AMD_STAT == 1 || $AMD_STAT == 4) 
					                                         		{
					                                         			?>
					                                                		<input type="checkbox" name="dataSUB<?php echo $currentRow; ?>AMD_CLASS" id="AMD_CLASS2<?php echo $currentRow; ?>" value="1" onClick="chgRad2(this,<?php echo $currentRow; ?>);" <?php if($AMD_CLASS == 1) { ?> checked <?php } ?>>
					                                                	<?php
					                                                }
					                                                else
					                                                {
					                                                	?>
					                                                		<input type="checkbox" name="dataSUB<?php echo $currentRow; ?>AMD_CLASS" id="AMD_CLASS2<?php echo $currentRow; ?>" value="1" onClick="chgRad2(this,<?php echo $currentRow; ?>);" <?php if($AMD_CLASS == 1) { ?> checked <?php } ?> disabled>
					                                               		<?php
					                                               	}
				                                               	}
			                                               	?>
			                                            </td>

			                                            <!-- AMD_PRICE -->
			                                         	<td style="text-align:right; display: none;">
			                                         		<?php
				                                         		if($ITM_UNIT == 'LS' || $ITM_UNIT == 'LUMP')
				                                         		{
				                                         			?>
				                                         				hidden
				                                         				<input type="hidden" name="AMD_PRICE<?php echo $currentRow; ?>" id="AMD_PRICE<?php echo $currentRow; ?>" value="<?php echo number_format($AMD_PRICE, 2); ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgPrice(this,<?php echo $currentRow; ?>);" <?php if($AMD_CLASS == 2) { ?> disabled <?php } ?> >
				                                         			<?php
				                                         		}
				                                         		else
				                                         		{
					                                         		if($AMD_STAT == 1 || $AMD_STAT == 4)
					                                         		{
					                                         			?>
					                                                		<input type="text" name="AMD_PRICE<?php echo $currentRow; ?>" id="AMD_PRICE<?php echo $currentRow; ?>" value="<?php echo number_format($AMD_PRICE, 2); ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgPrice(this,<?php echo $currentRow; ?>);" <?php if($AMD_CLASS == 2) { ?> disabled <?php } ?> >
					                                                	<?php
					                                                }
					                                                else
					                                                {
					                                                	?>
						                                                	<?php
						                                                		echo number_format($AMD_PRICE, 2);
						                                                	?>
						                                                	<input type="hidden" name="AMD_PRICE<?php echo $currentRow; ?>" id="AMD_PRICE<?php echo $currentRow; ?>" value="<?php echo number_format($AMD_PRICE, 2); ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgPrice(this,<?php echo $currentRow; ?>);" <?php if($AMD_CLASS == 2) { ?> disabled <?php } ?> >
					                                                	<?php
					                                                }
					                                            }
			                                                ?>
			                                                <input type="hidden" name="dataSUB[<?php echo $currentRow; ?>][AMD_PRICE]" id="dataSUB<?php echo $currentRow; ?>AMD_PRICE" value="<?php echo $AMD_PRICE; ?>" class="form-control" style="max-width:300px;" >
			                                           	</td>

			                                            <!-- REMAIN -->
			                                            <td style="text-align: right;" nowrap>
			                                            	<?php
			                                            		echo number_format($ITM_REMAMN, 2);
			                                            		$ITM_REMVP 	= $ITM_REMV;
			                                            		if($ITM_REMV == 0)
			                                            			$ITM_REMVP 	= 1;
			                                            		$ITM_REMP 	= $ITM_REMAMN / $ITM_REMVP;
			                                            	?>
														  	<div style='margin-left: 10px; font-style: italic; white-space:nowrap'>
														  		<label style='white-space:nowrap'>
														  			<?php echo number_format($ITM_REMV, 2).' (V)'; ?><br>
														  			<?php echo number_format($ITM_REMP, 2).' (J)'; ?>
														  		</label>
														  	</div>
			                                            	<input type="hidden" id="dataSUB<?php echo $currentRow; ?>AMD_TOTAL" name="dataSUB[<?php echo $currentRow; ?>][AMD_TOTAL]" value="<?php echo $AMD_TOTAL; ?>">
			                                            	<input type="hidden" name="dataSUB[<?php echo $currentRow; ?>][REM_BUDG]" id="dataSUB<?php echo $currentRow; ?>AMD_MAXVAL" value="<?php echo $ITM_REMAMN; ?>">
														</td>

			                                            <!-- AMD_DESC -->
			                                            <td style="text-align: right;">
			                                         		<?php if($AMD_STAT == 1 || $AMD_STAT == 4) { ?>
			                                            		<input type="hidden" name="dataSUB[<?php echo $currentRow; ?>][AMD_DESC]" id="dataSUB<?php echo $currentRow; ?>AMD_DESC" size="20" value="<?php echo $AMD_DESC; ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:left">
			                                            		<input type="text" id="dataSUB<?php echo $currentRow; ?>AMD_TOTTSFX" value="<?php echo number_format($AMD_TOTTSF, 2); ?>" class="form-control inplabel" style="min-width:80px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgTTsf(this,<?php echo $currentRow; ?>);">
			                                            		<input type="hidden" id="dataSUB<?php echo $currentRow; ?>AMD_TOTTSF" name="dataSUB[<?php echo $currentRow; ?>][AMD_TOTTSF]" value="<?=$AMD_TOTTSF?>">
			                                                <?php } else { ?>
			                                                	<?php echo number_format($AMD_TOTTSF, 2); ?>
			                                            		<input type="hidden" name="dataSUB[<?php echo $currentRow; ?>][AMD_DESC]" id="dataSUB<?php echo $currentRow; ?>AMD_DESC" size="20" value="<?php echo $AMD_DESC; ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:left">
			                                            		<input type="hidden" id="dataSUB<?php echo $currentRow; ?>AMD_TOTTSFX" value="<?php echo number_format($AMD_TOTTSF, 2); ?>" class="form-control inplabel" style="min-width:80px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgTTsf(this,<?php echo $currentRow; ?>);">
			                                            		<input type="hidden" id="dataSUB<?php echo $currentRow; ?>AMD_TOTTSF" name="dataSUB[<?php echo $currentRow; ?>][AMD_TOTTSF]" value="<?=$AMD_TOTTSF?>">
			                                                <?php } ?>
			                                            </td>
		                                      		</tr>
		                                        <?php
		                                        endforeach;
		                                    }
		                                    ?>
		                                    <input type="hidden" name="totalrow2" id="totalrow2" value="<?php echo $currentRow; ?>">
		                                </table>
		                            </div>
		                        </div>
	                        </div>
	                    </div>
	                </div>
                    <br>
                	<div class="col-md-6">
                        <div class="form-group">
                       	  	<label for="inputName" class="col-sm-3 control-label">&nbsp;</label>
                          	<div class="col-sm-9">
	                        	<?php
									if($task=='add' && $resCAPP > 0)
									{
										if(($AMD_STAT == 1 || $AMD_STAT == 4) && $ISCREATE == 1)
										{
											?>
												<button class="btn btn-primary" id="btnSave">
												<i class="fa fa-save"></i></button>&nbsp;
											<?php
										}
									}
									else
									{
										if(($AMD_STAT == 1 || $AMD_STAT == 4) && $ISCREATE == 1 && $resCAPP > 0)
										{
											?>
												<button class="btn btn-primary" id="btnSave">
												<i class="fa fa-save"></i></button>&nbsp;
											<?php
										}
										//elseif($resAPP == 1)
										//{
											?>
												<button class="btn btn-primary" id="btnREJECT" style="display:none" >
												<i class="fa fa-save"></i></button>
											<?php
										//}
									}

									/*if($ISAPPROVE == 1 && $AMD_STAT == 2)
									{
										?>
											<button class="btn btn-primary">
												<i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Save; ?>
											</button>&nbsp;
										<?php
									}
									if($ISCREATE == 1 && ($AMD_STAT == 1 || $AMD_STAT == 4))
									{
										?>
											<button class="btn btn-primary">
												<i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Save; ?>
											</button>&nbsp;
										<?php
									}*/
									$backURL	= site_url('c_comprof/c_am1h0db2/gall1h0db2amd/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
									echo anchor("$backURL",'<button class="btn btn-danger" type="button" id="btnBack"><i class="fa fa-reply"></i></button>');
								?>
							</div>
						</div>
               		</div>
                </form>
		    	<div class="row">
			        <div class="col-md-12">
						<?php
	                        $DOC_NUM	= $AMD_NUM;
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
								
	                    	if($resCAPP == 0)
	                    	{
	                    		if($LangID == 'IND')
								{
									$zerSetApp	= "Belum ada pengaturan untuk persetujuan dokumen ini.";
								}
								else
								{
									$zerSetApp	= "There are no arrangements for the approval of this document.";
								}
	                    		?>
	                    			<div class="alert alert-warning alert-dismissible">
					                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					                <?php echo $zerSetApp; ?>
					              	</div>
	                    		<?php
	                    	}
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
		    </div>
            <style type="text/css">
            	.ow-anywhere {
				   overflow-wrap: anywhere;
				}
            </style>
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
							    		<?php
							    			$itmNameListD 	= "$JobList $JOBDESC ($AMD_JOBPAR)";
							    		?>
						              	<ul class="nav nav-tabs">
						                    <li id="li1" <?php echo $Active1Cls; ?>>
						                    	<a href="#itm1" data-toggle="tab" id="itmNameList"><?=$itmNameListD?></a>
						                    </li>
						                </ul>
							            <div class="box-body">
							            	<div class="<?php echo $Active1; ?> tab-pane" id="itm2">
		                                        <div class="form-group">
		                                        	<form method="post" name="frmSearch1" id="frmSearch1" action="">
			                                            <table id="example0" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
			                                                <thead>
			                                                    <tr>
											                        <th width="1%">&nbsp;</th>
		                        									<th width="50%" style="text-align: center;" nowrap><?php echo $JobName; ?></th>
		                        									<th width="49%" style="text-align: center;" nowrap><?php echo $JobParent; ?></th>
											                  	</tr>
			                                                </thead>
			                                                <tbody>
			                                                </tbody>
			                                            </table>
                                                    	<button class="btn btn-primary" type="button" id="btnDetail0" name="btnDetail0">
                                                    		<i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?>
                                                    	</button>
                                      					<button type="button" id="idClose0" class="btn btn-danger" data-dismiss="modal">
                                                    		<i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>
                                                    	</button>
                                      					<button class="btn btn-warning" type="button" id="idRefresh0" title="Refresh" >
                                                    		<i class="glyphicon glyphicon-refresh"></i>
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

		        <div class="modal fade" id="mdl_addJListSubs" name='mdl_addJListSubs' tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		            <div class="modal-dialog">
			            <div class="modal-content">
			                <div class="modal-body">
								<div class="row">
							    	<div class="col-md-12">
						              	<ul class="nav nav-tabs">
						                    <li id="li1" <?php echo $Active1Cls; ?>>
						                    	<a href="#itm1" data-toggle="tab" id="itmNameListSub"><?=$itmNameListD?></a>
						                    </li>
						                </ul>
							            <div class="box-body">
							            	<div class="<?php echo $Active1; ?> tab-pane" id="itm2">
		                                        <div class="form-group">
		                                        	<form method="post" name="frmSearch1" id="frmSearch1" action="">
			                                            <table id="example3" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
			                                                <thead>
			                                                    <tr>
											                        <th width="5%">&nbsp;</th>
		                        									<th width="35%" style="text-align:center;" nowrap><?php echo $ItemName; ?></th>
											                        <th width="5%" style="text-align:center;"><?php echo $Unit; ?></th>
											                        <th width="15%" style="text-align:center;" nowrap><?php echo $BudgetQty; ?>  </th>
											                        <th width="15%" style="text-align:center;" nowrap><?php echo $Requested; ?></th>
											                        <th width="15%" style="text-align:center;" nowrap><?php echo $Remain; ?>  </th>
											                  	</tr>
			                                                </thead>
			                                                <tbody>
			                                                </tbody>
			                                            </table>
                                                    	<button class="btn btn-primary" type="button" id="btnDetail3" name="btnDetail3">
                                                    		<i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?>
                                                    	</button>
                                      					<button type="button" id="idClose3" class="btn btn-danger" data-dismiss="modal">
                                                    		<i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>
                                                    	</button>
                                      					<button class="btn btn-warning" type="button" id="idRefresh3" title="Refresh" >
                                                    		<i class="glyphicon glyphicon-refresh"></i>
                                                    	</button>
		                                            </form>
		                                      	</div>
		                                    </div>
                                      	</div>
                                      	<input type="hidden" name="rowCheck3" id="rowCheck3" value="0">
	                                </div>
	                            </div>
			                </div>
				        </div>
				    </div>
				</div>

				<script type="text/javascript">
					function mdlJlist()
					{
						document.getElementById('btnModalA').click();
						var ITM_CODEH 	= document.getElementById('ITM_CODEH').value;
						var AMD_CATEG 	= document.getElementById('AMD_CATEG').value;
						var collData 	= ITM_CODEH+'~'+AMD_CATEG;

						$('#example0').DataTable(
				    	{
				    		"bDestroy": true,
					        "processing": true,
					        "serverSide": true,
							//"scrollX": false,
							"autoWidth": true,
							"filter": true,
					        "ajax": "<?php echo site_url('c_comprof/c_am1h0db2/get_AllDataJLD/?id='.$PRJCODE.'&collData=')?>"+collData,
					        "type": "POST",
							//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
							"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
							"columnDefs": [	{ targets: [0], className: 'dt-body-center' },
										  ],
							"language": {
					            "infoFiltered":"",
					            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
					        },
						});
					    
					   	$("#idRefresh0").click(function()
					    {
							$('#example0').DataTable().ajax.reload();
					    });
					}

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
								swal('<?php echo $alert7; ?>',
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
						    	var AMD_CATEG 	= document.getElementById('AMD_CATEG').value;
						    	if(AMD_CATEG == 'OTH')
						    		add_JOBOTH($(this).val());
						    	else
						      		add_JOB($(this).val());
						    });

						    $('#mdl_addJList').on('hidden.bs.modal', function () {
							    $(this)
								    .find("input,textarea,select")
									    //.val('')
									    .end()
								    .find("input[type=checkbox], input[type=radio]")
								       .prop("checked", "")
								       .end();
							});
                        	document.getElementById("idClose0").click()
					    });
					});

					function mdlJlistSubs(strItem)
					{
						arrItem 		= strItem.split('~');
						JOBCODEID 		= arrItem[0];
						JOBPAR_CODE 	= arrItem[1];

						document.getElementById('btnModalB').click();
						var ITM_CODEH 	= document.getElementById('ITM_CODEH').value;
						var AMD_CATEG 	= document.getElementById('AMD_CATEG').value;
						var collData 	= ITM_CODEH+'~'+AMD_CATEG+'~'+JOBCODEID+'~'+JOBPAR_CODE;

						$('#example3').DataTable(
				    	{
				    		"bDestroy": true,
					        "processing": true,
					        "serverSide": true,
							//"scrollX": false,
							"autoWidth": true,
							"filter": true,
					        "ajax": "<?php echo site_url('c_comprof/c_am1h0db2/get_AllDataJLDSUBS/?id='.$PRJCODE.'&collData=')?>"+collData,
					        "type": "POST",
							//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
							"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
							"columnDefs": [	{ targets: [0,2], className: 'dt-body-center' },
											{ targets: [3,4,5], className: 'dt-body-right' },
										  ],
							"language": {
					            "infoFiltered":"",
					            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
					        },
						});
					    
					   	$("#idRefresh3").click(function()
					    {
							$('#example3').DataTable().ajax.reload();
					    });
					}

					var selectedRows = 0;
					function pickThis3(thisobj) 
					{
						var favorite = [];
						$.each($("input[name='chk3']:checked"), function() {
					      	favorite.push($(this).val());
					    });
					    $("#rowCheck3").val(favorite.length);
					}

					$(document).ready(function()
					{
					   	$("#btnDetail3").click(function()
					    {
							var totChck 	= $("#rowCheck3").val();

							if(totChck == 0)
							{
								swal('<?php echo $alert7; ?>',
								{
									icon: "warning",
								})
								.then(function()
					            {
					                swal.close();
					            });
								return false;
							}

						    $.each($("input[name='chk3']:checked"), function()
						    {
						      	add_JOBSUBS($(this).val());
						    });

						    $('#mdl_addJListSubs').on('hidden.bs.modal', function () {
							    $(this)
								    .find("input,textarea,select")
									    //.val('')
									    .end()
								    .find("input[type=checkbox], input[type=radio]")
								       .prop("checked", "")
								       .end();
							});
                        	document.getElementById("idClose3").click()
					    });
					});
				</script>
	    	<!-- ============ END MODAL JOBLIST =============== -->

	    	<!-- ============ START MODAL ITEM =============== -->
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

					$urlJL	= site_url('c_comprof/c_am1h0db2/popupallJL/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
		    	?>
		        <div class="modal fade" id="mdl_addItm" name='mdl_addItm' tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		            <div class="modal-dialog">
			            <div class="modal-content">
			                <div class="modal-body">
								<div class="row">
							    	<div class="col-md-12">
						              	<ul class="nav nav-tabs">
						                    <li id="li1" <?php echo $Active1Cls; ?>>
						                    	<a href="#itm1" data-toggle="tab"><?php echo $ItemList; ?></a>
						                    </li>
						                </ul>
							            <div class="box-body">
							            	<div class="<?php echo $Active1; ?> tab-pane" id="itm1">
		                                        <div class="form-group">
		                                        	<form method="post" name="frmSearch1" id="frmSearch1" action="">
			                                            <table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
			                                                <thead>
			                                                    <tr>
											                        <th width="3%">&nbsp;</th>
											                        <th width="7%" nowrap><?php echo $ItemName; ?></th>
											                        <th width="3%" nowrap><?php echo $Unit; ?></th>
											                        <th width="6%" nowrap><?php echo $BudgetQty; ?>  </th>
											                        <th width="6%" nowrap><?php echo $Requested; ?></th>
											                        <th width="9%" nowrap><?php echo $Remain; ?>  </th>
											                  	</tr>
			                                                </thead>
			                                                <tbody>
			                                                </tbody>
			                                            </table>
                                                    	<button class="btn btn-primary" type="button" id="btnDetail1" name="btnDetail1">
                                                    		<i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?>
                                                    	</button>
                                      					<button type="button" id="idClose1" class="btn btn-danger" data-dismiss="modal">
                                                    		<i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>
                                                    	</button>
                                      					<button class="btn btn-warning" type="button" id="idRefresh1" title="Refresh" >
                                                    		<i class="glyphicon glyphicon-refresh"></i>
                                                    	</button>
		                                            </form>
		                                      	</div>
		                                    </div>
                                      	</div>
                                      	<input type="hidden" name="rowCheck1" id="rowCheck1" value="0">
                                      	<button type="button" id="idClose1" class="btn btn-default" data-dismiss="modal" style="display: none";>Close</button>
	                                </div>
	                            </div>
			                </div>
				        </div>
				    </div>
				</div>

				<script type="text/javascript">
					var urlJL 			= "<?php echo $urlJL;?>";
					var selectedRows 	= 0;

					function selItem()
					{
                        AMD_CATEG 	= document.getElementById('AMD_CATEG').value;

						if(AMD_CATEG == 'SI' || AMD_CATEG == 'SINJ')
						{
							var AMD_FUNC 	= document.getElementById('AMD_FUNC1').value;
							var AMD_SINO 	= document.getElementById('AMD_REFNOX').value;

							if(AMD_FUNC == '')
							{
								swal("<?php echo $alert5; ?>",
								{
									icon: "warning"
								})
								then(function()
								{
									swal.close();
								});
								return false;
							}

							if(AMD_SINO == '')
							{
								swal("<?php echo $alert4; ?>",
								{
									icon: "warning"
								})
								then(function()
								{
									swal.close();
									document.getElementById('AMD_REFNOX').focus();
								});
								return false;
							}
						}

                        if(AMD_CATEG == 0)
                        {
                        	swal("<?php echo $alert1; ?>",
                        	{
                        		icon:"warning"
                        	});
                        	return false;
                        }

                        document.getElementById('JOBCODEID2').click();

                        $('#example1').DataTable(
				    	{
				    		"destroy": true,
				    		//"paging": false,
					        "processing": true,
					        "serverSide": true,
							//"scrollX": false,
							"autoWidth": true,
							"filter": true,
					        "ajax": "<?php echo site_url('c_comprof/c_am1h0db2/get_AllDataITMH/?id='.$PRJCODE)?>",
					        "type": "POST",
							//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
							"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
							"columnDefs": [	{ targets: [0], className: 'dt-body-center' },
											{ targets: [2,3,4,5], className: 'dt-body-right' },
											{ sortable: false, targets: [2,3,4,5] }
										  ],
							"order": [[ 3, "desc" ]],
							"language": {
					            "infoFiltered":"",
					            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
					        },
						});
					}

					function pickThis1(thisobj) 
					{
						var favorite = [];
						$.each($("input[name='chk1']:checked"), function() {
					      	favorite.push($(this).val());
					    });
					    $("#rowCheck1").val(favorite.length);
					}

					$(document).ready(function()
					{
					   	$("#idRefresh1").click(function()
					    {
							$('#example1').DataTable().ajax.reload();
					    });

					   	$("#btnDetail1").click(function()
					    {
							var totChck 	= $("#rowCheck1").val();
							if(totChck == 0)
							{
								swal('<?php echo $alert2; ?>',
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
						      	add_itemH($(this).val());
						    });

						    $('#mdl_addItm').on('hidden.bs.modal', function () {
							    $(this)
								    .find("input,textarea,select")
									    //.val('')
									    .end()
								    .find("input[type=checkbox], input[type=radio]")
								       .prop("checked", "")
								       .end();
							});
                        	document.getElementById("idClose1").click()
					    });
					});
				</script>
	    	<!-- ============ END MODAL ITEM =============== -->

	    	<!-- ============ START MODAL SI =============== -->
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
		        <div class="modal fade" id="mdl_addSI" name='mdl_addSI' tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		            <div class="modal-dialog">
			            <div class="modal-content">
			                <div class="modal-body">
								<div class="row">
							    	<div class="col-md-12">
						              	<ul class="nav nav-tabs">
						                    <li id="li1" <?php echo $Active1Cls; ?>>
						                    	<a href="#itm1" data-toggle="tab"><?php echo $SIList; ?></a>
						                    </li>
						                </ul>
							            <div class="box-body">
							            	<div class="<?php echo $Active1; ?> tab-pane" id="itm1">
		                                        <div class="form-group">
		                                        	<form method="post" name="frmSearch1" id="frmSearch1" action="">
			                                            <table id="example2" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
			                                                <thead>
			                                                    <tr>
											                        <th width="2%" style="vertical-align: middle;">&nbsp;</th>
											                        <th width="8%" style="vertical-align: middle;" nowrap><?php echo $Code; ?></th>
											                        <th width="10%" style="vertical-align: middle;" nowrap><?php echo $Date; ?></th>
											                        <th width="5%" style="vertical-align: middle;" nowrap><?php echo $SIStep; ?>  </th>
											                        <th width="65%" style="vertical-align: middle;" nowrap><?php echo $Description; ?></th>
											                        <th width="10%" style="vertical-align: middle;" nowrap><?php echo $ContractAmount; ?>  </th>
											                  	</tr>
			                                                </thead>
			                                                <tbody>
			                                                </tbody>
			                                            </table>
                                                    	<button class="btn btn-primary" type="button" id="btnDetail2" name="btnDetail2">
                                                    		<i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?>
                                                    	</button>
                                      					<button type="button" id="idClose2" class="btn btn-danger" data-dismiss="modal">
                                                    		<i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>
                                                    	</button>
                                      					<button class="btn btn-warning" type="button" id="idRefresh2" title="Refresh" >
                                                    		<i class="glyphicon glyphicon-refresh"></i>
                                                    	</button>
		                                            </form>
		                                      	</div>
		                                    </div>
                                      	</div>
                                      	<input type="hidden" name="rowCheck2" id="rowCheck2" value="0">
                                      	<button type="button" id="idClose2" class="btn btn-default" data-dismiss="modal" style="display: none";>Close</button>
	                                </div>
	                            </div>
			                </div>
				        </div>
				    </div>
				</div>

				<script type="text/javascript">
					var selectedRows = 0;
					function pickThis2(thisobj) 
					{
						var favorite = [];
						$.each($("input[name='chk2']:checked"), function() {
					      	favorite.push($(this).val());
					    });
					    $("#rowCheck2").val(favorite.length);
					}

					$(document).ready(function()
					{
					   	$("#btnDetail2").click(function()
					    {
							var totChck 	= $("#rowCheck2").val();
							if(totChck == 0)
							{
								swal('<?php echo $alert2; ?>',
								{
									icon: "warning",
								})
								.then(function()
					            {
					                swal.close();
					            });
								return false;
							}

						    $.each($("input[name='chk2']:checked"), function()
						    {
						      	add_SI($(this).val());
						    });

						    $('#mdl_addSI').on('hidden.bs.modal', function () {
							    $(this)
								    .find("input,textarea,select")
									    //.val('')
									    .end()
								    .find("input[type=checkbox], input[type=radio]")
								       .prop("checked", "")
								       .end();
							});
                        	document.getElementById("idClose2").click()
					    });
					});
				</script>
	    	<!-- ============ END MODAL SI =============== -->
	    	<div class="col-md-12">
				<?php
	                $DefID      = $this->session->userdata['Emp_ID'];
	                $act_lnk    = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	                if($DefID == 'D15040004221')
	                    echo "<font size='1'><i>$act_lnk</i></font>";
	            ?>
	        </div>
		</section>
	</body>
</html>
<?php
	$secGJCode = base_url().'index.php/__l1y/getJobCode/?id=';
?>
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
	      	autoclose: true,
	      	format:'dd/mm/yyyy',
	  		endDate: '+0d'
	    });

	    //Date picker
	    $('#datepicker2').datepicker({
	      	autoclose: true,
	    });

	    //Date picker
	    $('#datepicker3').datepicker({
	      	autoclose: true,
	    });

	    //Date picker
	    $('#datepicker4').datepicker({
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

		$('#frm').validate({
	    	submitHandler: function(form)
	    	{
	    		var AMD_CATEG	= document.getElementById('AMD_CATEG').value;
				var AMD_STAT	= document.getElementById('AMD_STAT').value;

				if(AMD_CATEG == 'SI')
				{
					AMD_FUNC	= document.getElementById('AMD_FUNC').value;
					if(AMD_FUNC == '')
					{
						swal('<?php echo $alert5; ?>');
						document.getElementById('AMD_FUNC').focus();
						return false;
					}
				}
				if(AMD_CATEG == 'SI' || AMD_CATEG == 'SINJ')
				{
					AMD_REFNO	= document.getElementById('AMD_REFNO').value;
					if(AMD_REFNO == '')
					{
						swal('<?php echo $alert4; ?>');
						document.getElementById('AMD_REFNOX').focus();
						return false;
					}
				}

				if(AMD_STAT == 9)
				{
					AMD_MEMO	= document.getElementById('AMD_MEMO').value;
					if(AMD_MEMO == '')
					{
						swal('<?php echo $alert6; ?>');
						document.getElementById('AMD_MEMO').focus();
						return false;
					}
				}

				var AMD_AMOUNT	= document.getElementById('AMD_AMOUNT').value;
				if(AMD_AMOUNT == 0)
				{
					swal('<?php echo $alert3; ?>');
					return false;
				}
			
	    		if($(form).data('submitted')==true){
			      swal('<?php echo $alertSubmit;?>');
			      return false;
			    } else {
			      //swal('submitting');
			      $(form).data('submitted', true);
			      return true;
			    }
	    	}
	    });
  	});

	var decFormat		= 2;

	function add_header(strItem)
	{
		arrItem = strItem.split('|');
		JOBCODEID 	= arrItem[0];
		JOBDESC 	= arrItem[1];

		document.getElementById('JOBCODEID').value 			= JOBCODEID;
		document.getElementById('JOBCODEID1').value 		= JOBCODEID+' '+JOBDESC;

		//document.getElementById('btnModal').style.display = '';

		JOBCODEIDX 	= document.getElementById("JOBCODEID").value
		document.getElementById("JOBCODEIDX").value 	= JOBCODEIDX;
		AMD_CATEGX 	= document.getElementById("AMD_CATEGX").value;
		document.getElementById("AMD_CATEGX").value 	= AMD_CATEGX;
        //document.frmsrch1.submitSrch1.click();

        document.getElementById('btnModal').style.display 	= '';
	}

	function add_JOB(strItem)
	{
		var objTable, objTR, objTD, intIndex, arrItem;
		var AMD_CATEG 	= document.getElementById('AMD_CATEG').value;
		var AMD_NUM 	= "<?php echo $DocNumber; ?>";
		var ITM_DESC 	= "<?php echo $ItemCode; ?>";

		arrItem 		= strItem.split('|');
		JOBCODEID 		= arrItem[0];
		JOBCODEDET 		= arrItem[0];
		JOBPAR_CODE 	= arrItem[1];
		JOBPAR_DESC 	= arrItem[2];
		JOBCODEID_NEW 	= arrItem[4];
		ITM_REMV 		= arrItem[5];
		ITM_PRICE 		= arrItem[6];

		var JOBPAR 		= "<div style='margin-left: 10px; font-style: italic;'><label>"+JOBPAR_CODE+" : "+JOBPAR_DESC+"</label></div>";

		JOBCODE 		= "";
		PRJCODE 		= document.getElementById('PRJCODE').value;
		ITM_CODE 		= document.getElementById('ITM_CODEH').value;
		ITM_NAME 		= document.getElementById('AMD_JOBDESC').value;
		ITMUNIT 		= document.getElementById('AMD_UNIT').value;
		//ITM_PRICE 		= 0;
		ITM_VOLM 		= 0;
		AMD_CLASS		= 0;
		
		// START : SETTING ROWS INDEX
			intIndexA 		= parseFloat(document.getElementById('totalrow').value);
			intIndex 		= parseInt(intIndexA)+1;
			objTable 		= document.getElementById('tbl');
			intTable 		= objTable.rows.length;

			document.frm.rowCount.value = intIndex;
			
			objTR 			= objTable.insertRow(intTable);
			objTR.id 		= 'tr_' + intIndex;
		// START : SETTING ROWS INDEX

		// Checkbox
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = "center";
			objTD.noWrap = true;
			objTD.innerHTML = '<a href="#" onClick="deleteRow('+intIndex+')" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a><input type="hidden" id="chk" name="chk" value="'+intIndex+'" width="10" size="15" readonly class="form-control" style="max-width:300px;">';

		// ITM_CODE
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = 'right';

			if(AMD_CATEG == 'OB' || AMD_CATEG == 'SI')
			{
				document.getElementById('itmName').innerHTML 	= ITM_DESC;
				//document.getElementById('itmVol').innerHTML 	= "Volume";
				objTD.innerHTML = ''+ITM_CODE+'<input type="hidden" id="data'+intIndex+'ITM_CODE" name="data['+intIndex+'][ITM_CODE]" value="'+ITM_CODE+'" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'AMD_NUM" name="data['+intIndex+'][AMD_NUM]" value="'+AMD_NUM+'" class="form-control" style="max-width:300px;"><input type="hidden" name="data['+intIndex+'][JOBCODEID]" id="data'+intIndex+'JOBCODEID" value="'+JOBCODEID+'" class="form-control" ><input type="hidden" name="data['+intIndex+'][JOBPARENT]" id="data'+intIndex+'JOBPARENT" value="'+JOBPAR_CODE+'" class="form-control" ><input type="hidden" name="totalrow" id="totalrow" value="'+intIndex+'">';
			}
			else
			{
				objTD.innerHTML = '<input type="hidden" id="data'+intIndex+'ITM_CODE" name="data['+intIndex+'][ITM_CODE]" value="'+ITM_CODE+'" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'AMD_NUM" name="data['+intIndex+'][AMD_NUM]" value="'+AMD_NUM+'" class="form-control" style="max-width:300px;"><input type="text" name="data['+intIndex+'][JOBCODEID]" id="data'+intIndex+'JOBCODEID" value="'+JOBCODEID_NEW+'" class="form-control" ><input type="hidden" name="data['+intIndex+'][JOBPARENT]" id="data'+intIndex+'JOBPARENT" value="'+JOBPAR_CODE+'" class="form-control" ><input type="hidden" name="totalrow" id="totalrow" value="'+intIndex+'">';
			}

		// ITM_NAME
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = 'left';
			objTD.noWrap = true;
			objTD.innerHTML = ''+ITM_NAME+JOBPAR+'<input type="hidden" id="data'+intIndex+'JOBDESC" name="data['+intIndex+'][JOBDESC]" value="'+ITM_NAME+'" class="form-control" style="max-width:300px;">';

		// REMAIN_VOLUME
			ITM_REMVW	= doDecimalFormat(RoundNDecimal(parseFloat((ITM_REMV)),2));
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'center';
			objTD.innerHTML = ''+ITM_REMVW+'<input type="hidden" id="ITM_REMV'+intIndex+'" value="'+ITM_REMV+'" class="form-control" style="max-width:300px;" >';

		// AMD_CLASS
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = '<input type="hidden" name="data['+intIndex+'][AMD_CLASS]" id="data'+intIndex+'AMD_CLASS" value="'+AMD_CLASS+'"><input type="checkbox" name="data'+intIndex+'AMD_CLASS" id="AMD_CLASS1'+intIndex+'" value="1" onClick="chgRad1(this,'+intIndex+');">';

		// AMD_VOLM
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'center';
			objTD.innerHTML = '<input type="text" name="AMD_VOLM'+intIndex+'" id="AMD_VOLM'+intIndex+'" value="0.00" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgPrice(this,'+intIndex+');" ><input type="hidden" name="data['+intIndex+'][AMD_VOLM]" id="data'+intIndex+'AMD_VOLM" value="0.00" class="form-control" style="max-width:300px;" >';

		// AMD_CLASS
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = '<input type="checkbox" name="data'+intIndex+'AMD_CLASS" id="AMD_CLASS2'+intIndex+'" value="2" onClick="chgRad2(this,'+intIndex+');">';

		// Item Price
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';

			ITM_UNIT		= ITMUNIT.toUpperCase();
			ITM_PRICEV	= doDecimalFormat(RoundNDecimal(parseFloat((ITM_PRICE)),2));
			if(ITM_UNIT == 'LUMP' || ITM_UNIT == 'LS')
			{
				objTD.innerHTML = 'hidden<input type="hidden" name="AMD_PRICE'+intIndex+'" id="AMD_PRICE'+intIndex+'" value="'+ITM_PRICE+'" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgPrice(this,'+intIndex+');" ><input type="hidden" name="data['+intIndex+'][AMD_PRICE]" id="data'+intIndex+'AMD_PRICE" value="'+ITM_PRICE+'" class="form-control" style="max-width:300px;" ><input type="hidden" name="data['+intIndex+'][AMD_TOTAL]" id="data'+intIndex+'AMD_TOTAL" value="0" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" >';
			}
			else
			{
				objTD.innerHTML = '<input type="text" name="AMD_PRICE'+intIndex+'" id="AMD_PRICE'+intIndex+'" value="'+ITM_PRICEV+'" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgPrice(this,'+intIndex+');" ><input type="hidden" name="data['+intIndex+'][AMD_PRICE]" id="data'+intIndex+'AMD_PRICE" value="'+ITM_PRICE+'" class="form-control" style="max-width:300px;" ><input type="hidden" name="data['+intIndex+'][AMD_TOTAL]" id="data'+intIndex+'AMD_TOTAL" value="0" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" >';
			}

		// IITM_UNIT
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'center';
			objTD.innerHTML = ''+ITM_UNIT+'<input type="hidden" name="data['+intIndex+'][ITM_UNIT]" id="data'+intIndex+'ITM_UNIT" value="'+ITM_UNIT+'" class="form-control" style="max-width:300px;" >';

		// AMD_DESC
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = 'left';
			objTD.innerHTML = '<input type="text" name="data['+intIndex+'][AMD_DESC]" id="data'+intIndex+'AMD_DESC" size="20" value="" class="form-control" style="min-width:110px; max-width:300px; text-align:left">';

		document.getElementById('totalrow').value = intIndex;
	}

	function add_JOBOTH(strItem)
	{
		var objTable, objTR, objTD, intIndex, arrItem;
		var AMD_CATEG 	= document.getElementById('AMD_CATEG').value;
		var AMD_NUM 	= "<?php echo $DocNumber; ?>";
		var ITM_DESC 	= "<?php echo $ItemCode; ?>";

		arrItem 		= strItem.split('|');
		JOBCODEID 		= arrItem[0];
		JOBCODEDET 		= arrItem[0];
		JOBPAR_CODE 	= arrItem[1];
		JOBPAR_DESC 	= arrItem[2];
		JOBCODEID_NEW 	= arrItem[4];
		ITM_REMV 		= arrItem[5];
		ITM_PRICE 		= arrItem[6];
		COLLDATA 		= JOBCODEID+'~'+JOBPAR_CODE;

		//var JOBPAR 	= "<div style='margin-left: 10px; font-style: italic;'><label>"+JOBPAR_CODE+" : "+JOBPAR_DESC+"</label></div>";

		JOBCODE 		= "";
		PRJCODE 		= document.getElementById('PRJCODE').value;
		ITM_CODE 		= document.getElementById('ITM_CODEH').value;
		ITM_NAME 		= document.getElementById('AMD_JOBDESC').value;
		ITMUNIT 		= document.getElementById('AMD_UNIT').value;
		//ITM_PRICE 	= 0;
		ITM_VOLM 		= 0;
		AMD_CLASS		= 0;
		
		// START : SETTING ROWS INDEX
			intIndexA 		= parseFloat(document.getElementById('totalrow').value);
			intIndex 		= parseInt(intIndexA)+1;
			objTable 		= document.getElementById('tbl');
			intTable 		= objTable.rows.length;

			document.frm.rowCount.value = intIndex;
			
			objTR 			= objTable.insertRow(intTable);
			objTR.id 		= 'tr_' + intIndex;
		// START : SETTING ROWS INDEX

		// Checkbox
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = "center";
			objTD.noWrap = true;
			objTD.innerHTML = '<a href="#" onClick="deleteRow('+intIndex+')" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a><input type="hidden" id="chk" name="chk" value="'+intIndex+'" width="10" size="15" readonly class="form-control" style="max-width:300px;">';

		// ITM_CODE
			document.getElementById('colItmNm').style.display 	= 'none';
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = 'right';
			objTD.style.display = 'none';

			document.getElementById('itmName').innerHTML 	= ITM_DESC;
			//document.getElementById('itmVol').innerHTML 	= "Vol. Sisa";
			objTD.innerHTML = ''+ITM_CODE+'<input type="hidden" id="data'+intIndex+'ITM_CODE" name="data['+intIndex+'][ITM_CODE]" value="'+ITM_CODE+'" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'AMD_NUM" name="data['+intIndex+'][AMD_NUM]" value="'+AMD_NUM+'" class="form-control" style="max-width:300px;"><input type="hidden" name="data['+intIndex+'][JOBCODEID]" id="data'+intIndex+'JOBCODEID" value="'+JOBCODEID+'" class="form-control" ><input type="hidden" name="data['+intIndex+'][JOBPARENT]" id="data'+intIndex+'JOBPARENT" value="'+JOBPAR_CODE+'" class="form-control" ><input type="hidden" name="totalrow" id="totalrow" value="'+intIndex+'"><input type="text" id="data'+intIndex+'JOBDESC" name="data['+intIndex+'][JOBDESC]" value="'+ITM_NAME+'" class="form-control">';

		// ITM_NAME
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = 'left';
			objTD.noWrap = true;
			objTD.innerHTML = ''+ITM_NAME+'<br><input type="text" value="'+JOBPAR_CODE+' : '+JOBPAR_DESC+'" class="form-control inplabel" width="100%">';

		// REMAIN_VOLUME
			ITM_REMVW	= doDecimalFormat(RoundNDecimal(parseFloat((ITM_REMV)),2));
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = ''+ITM_REMVW+'<a href="#" onClick="mdlJlistSubs(\''+COLLDATA+'\')" title="Tambah Budget" class="btn btn-warning btn-xs"><i class="glyphicon glyphicon-transfer"></i></a><input type="hidden" id="ITM_REMV'+intIndex+'" value="'+ITM_REMV+'" class="form-control" style="max-width:300px;" >';

		// AMD_CLASS
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = '<input type="hidden" name="data['+intIndex+'][AMD_CLASS]" id="data'+intIndex+'AMD_CLASS" value="'+AMD_CLASS+'"><input type="checkbox" name="data'+intIndex+'AMD_CLASS" id="AMD_CLASS1'+intIndex+'" value="1" onClick="chgRad1(this,'+intIndex+');">';

		// AMD_VOLM
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'center';
			objTD.innerHTML = '<input type="text" name="AMD_VOLM'+intIndex+'" id="AMD_VOLM'+intIndex+'" value="0.00" class="form-control inplabel" style="min-width:60px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgVolOTH(this,'+intIndex+');" ><input type="hidden" name="data['+intIndex+'][AMD_VOLM]" id="data'+intIndex+'AMD_VOLM" value="0" class="form-control" style="max-width:300px;" >';

		// AMD_CLASS
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = '<input type="checkbox" name="data'+intIndex+'AMD_CLASS" id="AMD_CLASS2'+intIndex+'" value="2" onClick="chgRad2(this,'+intIndex+');">';

		// Item Price
			ITM_PRICEV	= doDecimalFormat(RoundNDecimal(parseFloat((ITM_PRICE)),2));
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';

			ITM_UNIT		= ITMUNIT.toUpperCase();
			if(ITM_UNIT == 'LUMP' || ITM_UNIT == 'LS')
			{
				objTD.innerHTML = 'hidden<input type="hidden" name="AMD_PRICE'+intIndex+'" id="AMD_PRICE'+intIndex+'" value="'+ITM_PRICE+'" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgPriceOTH(this,'+intIndex+');" ><input type="hidden" name="data['+intIndex+'][AMD_PRICE]" id="data'+intIndex+'AMD_PRICE" value="'+ITM_PRICE+'" class="form-control" style="max-width:300px;" ><input type="hidden" name="data['+intIndex+'][AMD_TOTAL]" id="data'+intIndex+'AMD_TOTAL" value="0" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" ><input type="text" id="data'+intIndex+'AMD_MAXVAL" value="" class="form-control" style="max-width:300px;" >';
			}
			else
			{
				objTD.innerHTML = '<input type="text" name="AMD_PRICE'+intIndex+'" id="AMD_PRICE'+intIndex+'" value="'+ITM_PRICEV+'" class="form-control inplabel" style="min-width:80px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgPriceOTH(this,'+intIndex+');" ><input type="hidden" name="data['+intIndex+'][AMD_PRICE]" id="data'+intIndex+'AMD_PRICE" value="'+ITM_PRICE+'" class="form-control" style="max-width:300px;" ><input type="hidden" name="data['+intIndex+'][AMD_TOTAL]" id="data'+intIndex+'AMD_TOTAL" value="0" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" ><input type="hidden" id="data'+intIndex+'AMD_MAXVAL" value="" class="form-control" style="max-width:300px;" >';
			}

		// IITM_UNIT. INFORMASI INI AKAN DIGANTI DENGAN TOMBOL PEMILIHAN ITEM PENGGANTI
			btnSelItm 	= "";
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'center';
			objTD.style.display = 'none';
			objTD.innerHTML = '<a class="btn btn-sm btn-danger" id="btnModal" onClick="mdlJlistSubs(\''+JOBCODEID+'\')"><i class="glyphicon glyphicon-transfer"></i></a><a class="btn btn-sm btn-warning" data-toggle="modal" data-target="#mdl_addJListSubs" id="btnModalB" style="display: none;"><i class="glyphicon glyphicon-search"></i></a><input type="hidden" name="data['+intIndex+'][ITM_UNIT]" id="data'+intIndex+'ITM_UNIT" value="'+ITM_UNIT+'" class="form-control" style="max-width:300px;" >';

		// AMD_DESC
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = 'left';
			objTD.style.display = 'none';
			objTD.innerHTML = '<input type="hidden" name="data['+intIndex+'][AMD_DESC]" id="data'+intIndex+'AMD_DESC" size="20" value="" class="form-control" style="min-width:110px; max-width:300px; text-align:left">';

		document.getElementById('totalrow').value = intIndex;
	}

	function add_JOBSUBS(strItem)
	{
		var objTable, objTR, objTD, intIndex, arrItem;
		var AMD_CATEG 	= document.getElementById('AMD_CATEG').value;
		var AMD_NUM 	= "<?php echo $DocNumber; ?>";
		var ITM_DESC 	= "<?php echo $ItemCode; ?>";

		arrItem 		= strItem.split('|');
		JOBID_SEL 		= arrItem[0];
		JOBCODEID 		= arrItem[1];
		JOBCODEDET 		= arrItem[1];
		JOBPAR_CODE 	= arrItem[2];
		JOBPAR_DESC 	= arrItem[3];
		ITM_CODE 		= arrItem[4];
		ITM_NAME 		= arrItem[5];
		JOBUNIT 		= arrItem[6];
		ITM_REMVOL 		= arrItem[7];
		ITM_REMVAL 		= arrItem[8];
		ITM_PRICE 		= arrItem[9];
		AMD_TOTAL 		= parseFloat(ITM_REMVOL * ITM_PRICE);
		/*console.log('ITM_REMVOL = '+ITM_REMVOL)
		console.log('ITM_PRICE = '+ITM_PRICE)
		console.log('AMD_TOTAL = '+AMD_TOTAL)*/

		//var JOBPAR 	= "<div style='margin-left: 10px; font-style: italic;'><label>"+JOBPAR_CODE+" : "+JOBPAR_DESC+"</label></div>";

		JOBCODE 		= "";
		PRJCODE 		= document.getElementById('PRJCODE').value;
		ITM_CODEH 		= document.getElementById('ITM_CODEH').value;
		ITM_NAMEH 		= document.getElementById('AMD_JOBDESC').value;
		ITMUNIT 		= document.getElementById('AMD_UNIT').value;
		//ITM_PRICE 	= 0;
		//ITM_VOLM 		= 0;
		AMD_CLASS		= 0;
		
		// START : SETTING ROWS INDEX
			intIndexA 		= parseFloat(document.getElementById('totalrow2').value);
			intIndex 		= parseInt(intIndexA)+1;
			objTable 		= document.getElementById('tbl_sub');
			intTable 		= objTable.rows.length;

			document.frm.rowCount.value = intIndex;
			
			objTR 			= objTable.insertRow(intTable);
			objTR.id 		= 'tr_' + intIndex;
		// START : SETTING ROWS INDEX

		// Checkbox
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = "center";
			objTD.noWrap = true;
			objTD.innerHTML = '<a href="#" onClick="deleteRowSUBS('+intIndex+')" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a><input type="hidden" id="chk" name="chk" value="'+intIndex+'" width="10" size="15" readonly class="form-control" style="max-width:300px;">';

		// ITM_NAME
			document.getElementById('colItmNm').style.display 	= 'none';
			document.getElementById('itmName').innerHTML 	= ITM_DESC;
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = 'left';
			objTD.noWrap = true;
			objTD.innerHTML = ''+ITM_NAME+'<input type="text" value="'+JOBPAR_CODE+' : '+JOBPAR_DESC+'" class="form-control inplabel" width="100%"><input type="hidden" id="dataSUB'+intIndex+'AMD_NUM" name="dataSUB['+intIndex+'][AMD_NUM]" value="'+AMD_NUM+'" class="form-control" style="max-width:300px;"><input type="hidden" name="dataSUB['+intIndex+'][JOBCODEIDH]" id="dataSUB'+intIndex+'JOBCODEIDH" value="'+JOBID_SEL+'" class="form-control" ><input type="text" name="dataSUB['+intIndex+'][JOBCODEID]" id="dataSUB'+intIndex+'JOBCODEID" value="'+JOBCODEID+'" class="form-control" ><input type="hidden" name="dataSUB['+intIndex+'][JOBPARENT]" id="dataSUB'+intIndex+'JOBPARENT" value="'+JOBPAR_CODE+'" class="form-control" ><input type="text" id="dataSUB'+intIndex+'ITM_CODE" name="dataSUB['+intIndex+'][ITM_CODE]" value="'+ITM_CODE+'" class="form-control" style="max-width:300px;"><input type="hidden" name="dataSUB['+intIndex+'][ITM_UNIT]" id="dataSUB'+intIndex+'ITM_UNIT" value="'+ITM_UNIT+'" class="form-control" style="max-width:300px;" ><input type="hidden" id="dataSUB'+intIndex+'JOBDESC" name="dataSUB['+intIndex+'][JOBDESC]" value="'+ITM_NAME+'" class="form-control"><input type="hidden" name="totalrow2" id="totalrow2" value="'+intIndex+'"><input type="hidden" name="dataSUB['+intIndex+'][AMD_CLASS]" id="dataSUB'+intIndex+'AMD_CLASS" value="'+AMD_CLASS+'"><input type="checkbox" name="dataSUB'+intIndex+'AMD_CLASS" id="AMD_CLASS1'+intIndex+'" value="1" onClick="chgRad1(this,'+intIndex+');" style="display: none"><input type="checkbox" name="dataSUB'+intIndex+'AMD_CLASS" id="AMD_CLASS2'+intIndex+'" value="2" onClick="chgRad2(this,'+intIndex+');" style="display: none">';

		// REMAIN VALUE
			var AMD_TOTALV 	= doDecimalFormat(RoundNDecimal(parseFloat((AMD_TOTAL)), 2));
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = ''+AMD_TOTALV+'<input type="hidden" id="dataSUB'+intIndex+'AMD_TOTAL" name="dataSUB['+intIndex+'][AMD_TOTAL]" value="'+AMD_TOTAL+'"><input type="hidden" name="dataSUB['+intIndex+'][REM_BUDG]" id="dataSUB'+intIndex+'AMD_MAXVAL" value="'+AMD_TOTAL+'">';

		// AMD_DESC
			document.getElementById('colRemark').style.display 	= 'none';
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = 'left';
			objTD.innerHTML = '<input type="text" id="dataSUB'+intIndex+'AMD_TOTTSFX" value="0" class="form-control inplabel" style="min-width:80px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgTTsf(this,'+intIndex+');"><input type="hidden" id="dataSUB'+intIndex+'AMD_TOTTSF" name="dataSUB['+intIndex+'][AMD_TOTTSF]" value="0"><input type="hidden" id="dataSUB'+intIndex+'AMD_VOLM" name="dataSUB['+intIndex+'][AMD_VOLM]" value="'+ITM_REMVOL+'"><input type="hidden" id="dataSUB'+intIndex+'AMD_PRICE" name="dataSUB['+intIndex+'][AMD_PRICE]" value="'+ITM_PRICE+'"<input type="hidden" id="dataSUB'+intIndex+'AMD_DESC" name="dataSUB['+intIndex+'][AMD_DESC]" value="">';

		document.getElementById('totalrow2').value = intIndex;
	}

	function add_SI(strItem)
	{
		var decFormat	= document.getElementById('decFormat').value;

		arrItem = strItem.split('|');
		SI_CODE 	= arrItem[0];
		SI_MANNO 	= arrItem[1];
		SI_VALUE 	= arrItem[2];
		SI_APPVAL 	= arrItem[3];

		document.getElementById('AMD_REFNO').value 		= SI_CODE;
		document.getElementById('AMD_REFNOX').value 	= SI_MANNO;
		document.getElementById('AMD_REFNOAM').value 	= SI_APPVAL;
		document.getElementById('AMD_REFNOAMX').value 	= doDecimalFormat(RoundNDecimal(parseFloat((SI_APPVAL)),decFormat));
	}

	function add_itemXX(strItem)
	{
		arrItem = strItem.split('|');
		var objTable, objTR, objTD, intIndex, arrItem;
		var AMD_NUMx 	= "<?php echo $DocNumber; ?>";

		var AMD_CODEx 	= "<?php echo $AMD_CODE; ?>";
		ilvl = arrItem[1];

		/*validateDouble(arrItem[0],arrItem[1])
		if(validateDouble(arrItem[0],arrItem[1]))
		{
			swal("Double Item for " + arrItem[0]);
			return;
		}*/

		JOBCODEDET 		= arrItem[0];
		JOBCODEID 		= arrItem[1];
	}

	function add_item(strItem)
	{
		arrItem = strItem.split('|');
		var objTable, objTR, objTD, intIndex, arrItem;
		var AMD_CATEG 	= document.getElementById('AMD_CATEG').value;
		var JOBPARENT 	= document.getElementById('JOBCODEID').value;
		var AMD_NUMx 	= "<?php echo $DocNumber; ?>";
		var ITM_DESC 	= "<?php echo $ItemCode; ?>";

		var AMD_CODEx 	= "<?php echo $AMD_CODE; ?>";
		ilvl = arrItem[1];

		/*validateDouble(arrItem[0],arrItem[1])
			if(validateDouble(arrItem[0],arrItem[1]))
			{
				swal("Double Item for " + arrItem[0]);
				return;
		}*/

		// VARIABLE
			JOBCODEDET 		= arrItem[0];
			JOBCODEID 		= arrItem[1];
			JOBCODE 		= arrItem[2];
			PRJCODE 		= arrItem[3];
			ITM_CODE 		= arrItem[4];
			ITM_NAME 		= arrItem[5];
			ITM_SN			= arrItem[6];
			ITMUNIT 		= arrItem[7];
			ITM_PRICE 		= arrItem[8];
			ITM_VOLM 		= arrItem[9];
			ITM_BUDGQTY		= parseFloat(ITM_VOLM);
			ITM_STOCK 		= arrItem[10];
			ITM_USED 		= arrItem[11];
			itemConvertion	= arrItem[12];
			TotPrice		= arrItem[13];
			REM_BUDG		= arrItem[14];
			TOT_USEBUDG		= arrItem[15];
			ITM_BUDG		= arrItem[16];
			TOT_USEDQTY		= arrItem[17];
			ITM_GROUP		= arrItem[18];
			AMD_CLASS		= 0;
		
		// START : SETTING ROWS INDEX
			intIndexA 		= parseFloat(document.getElementById('totalrow').value);
			intIndex 		= parseInt(intIndexA)+1;
			objTable 		= document.getElementById('tbl');
			intTable 		= objTable.rows.length;

			document.frm.rowCount.value = intIndex;
			
			objTR 			= objTable.insertRow(intTable);
			objTR.id 		= 'tr_' + intIndex;
		// START : SETTING ROWS INDEX

		// Checkbox
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = "center";
			objTD.noWrap = true;
			objTD.innerHTML = '<a href="#" onClick="deleteRow('+intIndex+')" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a><input type="hidden" id="chk" name="chk" value="'+intIndex+'" width="10" size="15" readonly class="form-control" style="max-width:300px;">';

		// Item Code
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = 'right';

			if(AMD_CATEG == 'OB' || AMD_CATEG == 'SI')
			{
				document.getElementById('itmName').innerHTML = ITM_DESC;
				objTD.innerHTML = ''+ITM_CODE+'<input type="hidden" id="data'+intIndex+'ITM_GROUP" name="data['+intIndex+'][ITM_GROUP]" value="'+ITM_GROUP+'" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'ITM_CODE" name="data['+intIndex+'][ITM_CODE]" value="'+ITM_CODE+'" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'AMD_NUM" name="data['+intIndex+'][AMD_NUM]" value="'+AMD_NUMx+'" class="form-control" style="max-width:300px;"><input type="hidden" name="data['+intIndex+'][JOBCODEID]" id="data'+intIndex+'JOBCODEID" value="'+JOBCODEID+'" class="form-control" ><input type="hidden" name="totalrow" id="totalrow" value="'+intIndex+'">';
			}
			else
			{
				//document.getElementById('itmName').innerHTML = 'Kode Pekerjaan';
				objTD.innerHTML = '<input type="hidden" id="data'+intIndex+'ITM_GROUP" name="data['+intIndex+'][ITM_GROUP]" value="'+ITM_GROUP+'" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'ITM_CODE" name="data['+intIndex+'][ITM_CODE]" value="'+ITM_CODE+'" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'AMD_NUM" name="data['+intIndex+'][AMD_NUM]" value="'+AMD_NUMx+'" class="form-control" style="max-width:300px;"><input type="text" name="data['+intIndex+'][JOBCODEID]" id="data'+intIndex+'JOBCODEID" value="'+JOBCODEID+'" class="form-control" onChange="chgJCODE(this,'+intIndex+');" style="min-width: 100px"><input type="hidden" name="totalrow" id="totalrow" value="'+intIndex+'">';
			}

		// Item Name
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = 'left';
			objTD.noWrap = true;
			objTD.innerHTML = ''+ITM_NAME+'<input type="hidden" id="data'+intIndex+'JOBDESC" name="data['+intIndex+'][JOBDESC]" value="'+ITM_NAME+'" class="form-control" style="max-width:300px;">';

		// Item Budget
			ITM_BUDGQTYV	= doDecimalFormat(RoundNDecimal(parseFloat((ITM_BUDGQTY)),2));
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';

			if(AMD_CATEG == 'OB' || AMD_CATEG == 'SI')
			{
				objTD.innerHTML = ''+ITM_BUDGQTYV+'<input type="hidden" class="form-control" style="min-width:100px; max-width:300px; text-align:right" name="ITM_BUDGQTYx'+intIndex+'" id="ITM_BUDGQTYx'+intIndex+'" value="'+ITM_BUDGQTY+'" disabled >';
			}
			else
			{
				var ITM_BUDGQTY = 0;
				ITM_BUDGQTYV	= doDecimalFormat(RoundNDecimal(parseFloat((0)),2));
				objTD.innerHTML = ''+ITM_BUDGQTYV+'<input type="hidden" class="form-control" style="min-width:100px; max-width:300px; text-align:right" name="ITM_BUDGQTYx'+intIndex+'" id="ITM_BUDGQTYx'+intIndex+'" value="'+ITM_BUDGQTY+'" disabled >';
			}

		// Item Used
			TOT_USEDQTYV	= doDecimalFormat(RoundNDecimal(parseFloat((TOT_USEDQTY)),2));
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = ''+TOT_USEDQTYV+'<input type="hidden" class="form-control" style="min-width:110px; max-width:300px; text-align:right" name="TOT_USEDQTY'+intIndex+'" id="TOT_USEDQTY'+intIndex+'" value="'+TOT_USEDQTY+'" disabled >';

		// Item Remain
			REM_BUDGV	= doDecimalFormat(RoundNDecimal(parseFloat((REM_BUDG)),2));
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = ''+REM_BUDGV+'<input type="hidden" name="data['+intIndex+'][REM_BUDG]" id="data'+intIndex+'REM_BUDG" value="'+REM_BUDG+'">';

		// Item Class
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = '<input type="hidden" name="data['+intIndex+'][AMD_CLASS]" id="data'+intIndex+'AMD_CLASS" value="'+AMD_CLASS+'"><input type="checkbox" name="data'+intIndex+'AMD_CLASS" id="AMD_CLASS1'+intIndex+'" value="1" onClick="chgRad1(this,'+intIndex+');">';

		// Amd Qty
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'center';
			objTD.innerHTML = '<input type="text" name="AMD_VOLM'+intIndex+'" id="AMD_VOLM'+intIndex+'" value="0.00" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgPrice(this,'+intIndex+');" ><input type="hidden" name="data['+intIndex+'][AMD_VOLM]" id="data'+intIndex+'AMD_VOLM" value="0.00" class="form-control" style="max-width:300px;" >';

		// Item Class
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = '<input type="checkbox" name="data'+intIndex+'AMD_CLASS" id="AMD_CLASS2'+intIndex+'" value="2" onClick="chgRad2(this,'+intIndex+');">';

		// Item Price
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';

			ITM_UNIT		= ITMUNIT.toUpperCase();
			if(ITM_UNIT == 'LUMP' || ITM_UNIT == 'LS')
			{
				objTD.innerHTML = 'hidden<input type="hidden" name="AMD_PRICE'+intIndex+'" id="AMD_PRICE'+intIndex+'" value="'+ITM_PRICE+'" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgPrice(this,'+intIndex+');" ><input type="hidden" name="data['+intIndex+'][AMD_PRICE]" id="data'+intIndex+'AMD_PRICE" value="'+ITM_PRICE+'" class="form-control" style="max-width:300px;" ><input type="hidden" name="data['+intIndex+'][AMD_TOTAL]" id="data'+intIndex+'AMD_TOTAL" value="0" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" >';
			}
			else
			{
				objTD.innerHTML = '<input type="text" name="AMD_PRICE'+intIndex+'" id="AMD_PRICE'+intIndex+'" value="'+ITM_PRICE+'" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgPrice(this,'+intIndex+');" ><input type="hidden" name="data['+intIndex+'][AMD_PRICE]" id="data'+intIndex+'AMD_PRICE" value="'+ITM_PRICE+'" class="form-control" style="max-width:300px;" ><input type="hidden" name="data['+intIndex+'][AMD_TOTAL]" id="data'+intIndex+'AMD_TOTAL" value="0" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" >';
			}

		// Item Unit Type -- ITM_UNIT
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'center';
			objTD.innerHTML = ''+ITM_UNIT+'<input type="hidden" name="data['+intIndex+'][ITM_UNIT]" id="data'+intIndex+'ITM_UNIT" value="'+ITM_UNIT+'" class="form-control" style="max-width:300px;" >';

		// Remarks -- AMD_DESC
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = 'left';
			objTD.innerHTML = '<input type="text" name="data['+intIndex+'][AMD_DESC]" id="data'+intIndex+'AMD_DESC" size="20" value="" class="form-control" style="min-width:110px; max-width:300px; text-align:left">';

		var decFormat											= document.getElementById('decFormat').value;
		document.getElementById('ITM_BUDGQTYx'+intIndex).value 	= doDecimalFormat(RoundNDecimal(parseFloat((ITM_BUDGQTY)),decFormat));
		document.getElementById('TOT_USEDQTY'+intIndex).value 	= doDecimalFormat(RoundNDecimal(parseFloat((TOT_USEDQTY)),decFormat));
		document.getElementById('AMD_PRICE'+intIndex).value 	= doDecimalFormat(RoundNDecimal(parseFloat((ITM_PRICE)),decFormat));

		document.getElementById('totalrow').value = intIndex;
	}

	function add_itemH(strItem)
	{
		var JOBLIST = "<?php echo $JobList; ?>";

		arrItem 	= strItem.split('|');
		var objTable, objTR, objTD, intIndex, arrItem;
		var AMD_CATEG 	= document.getElementById('AMD_CATEG').value;

		var AMD_NUM 	= "<?php echo $DocNumber; ?>";

		var AMD_CODE 	= "<?php echo $AMD_CODE; ?>";
		ilvl = arrItem[1];

		ITM_CODE 		= arrItem[0];
		ITM_NAME 		= arrItem[1];
		ITM_UNIT		= arrItem[2];

		document.getElementById('ITM_CODEH').value 			= ITM_CODE;
		document.getElementById('JOBCODEID').value 			= ITM_CODE;
		document.getElementById('AMD_JOBDESC').value 		= ITM_NAME;
		document.getElementById('AMD_UNIT').value 			= ITM_UNIT;
		document.getElementById('JOBCODEID1').value 		= ITM_CODE+' : '+ITM_NAME;
		document.getElementById('itmNameList').innerHTML 	= JOBLIST+' '+ITM_NAME+' ('+ITM_CODE+')';
		document.getElementById('itmNameListSub').innerHTML = JOBLIST+' '+ITM_NAME+' ('+ITM_CODE+')';

		JOBCODEIDX 	= document.getElementById("JOBCODEID").value
		document.getElementById("JOBCODEIDX").value 	= JOBCODEIDX;
		AMD_CATEGX 	= document.getElementById("AMD_CATEGX").value;
		document.getElementById("AMD_CATEGX").value 	= AMD_CATEGX;

        document.getElementById('btnModal').style.display 	= '';
	}

	function chgJCODE(thisVal, row)
	{
		var PRJCODE		= document.getElementById('PRJCODE').value;
		var JOBPARENT	= document.getElementById('JOBCODEID').value;
		var JOBCODEID	= thisVal.value;	// JOBPARENT

		var url 		= '<?php echo $secGJCode; ?>';
        var collID  	= JOBCODEID+'~'+JOBPARENT+'~'+PRJCODE;

        $.ajax({
            type: 'POST',
            url: url,
            data: {collID: collID},
            success: function(response)
            {
            	var myarr 	= response.split("~");
            	var chkStat = myarr[0];
            	var chkAlrt = myarr[1];
            	if(chkStat == 1)
					iconDesc = "success";
				else
					iconDesc = "warning";

            	swal(chkAlrt,
				{
					icon: ''+iconDesc,
				})
				.then(function()
				{
					document.getElementById('data'+row+'JOBCODEID').focus();
					if(chkStat == 1)
					{
						document.getElementById('btnSave').style.display 		= '';
					}
					else
					{
						document.getElementById('data'+row+'JOBCODEID').value 	= '';
						document.getElementById('btnSave').style.display 		= 'none';
					}
				});
            }
        });
		return false;
	}

	function chgPrice(thisVal, row)
	{
		var decFormat	= document.getElementById('decFormat').value;

		var AMD_CLASS	= document.getElementById('data'+row+'AMD_CLASS').value;
		var PRJCODE		= document.getElementById('PRJCODE').value;
		var JOBPARENT	= document.getElementById('JOBCODEID').value;
		var JOBCODEID	= document.getElementById('data'+row+'JOBCODEID').value;	// JOBPARENT

		if(JOBCODEID == '')
		{
			var url 	= '<?php echo $secGJCode; ?>';
	        var collID  = JOBCODEID+'~'+JOBPARENT+'~'+PRJCODE;

	        $.ajax({
	            type: 'POST',
	            url: url,
	            data: {collID: collID},
	            success: function(response)
	            {
	            	var myarr 	= response.split("~");
	            	var chkStat = myarr[0];
	            	var chkAlrt = myarr[1];
	            	if(chkStat == 1)
						iconDesc = "success";
					else
						iconDesc = "warning";
					
	            	swal(chkAlrt,
					{
						icon: ''+iconDesc,
					})
					.then(function()
					{
						document.getElementById('data'+row+'JOBCODEID').focus();
						document.getElementById('AMD_VOLM'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat((0)),decFormat));
						document.getElementById('data'+row+'AMD_VOLM').value 	= parseFloat((0));
						document.getElementById('data'+row+'JOBCODEID').value 	= '';
						document.getElementById('btnSave').style.display 		= 'none';
					});
	            }
	        });
			return false;
		}

		var AMD_VOLM	= eval(document.getElementById('AMD_VOLM'+row).value.split(",").join(""));
		var AMD_PRICE	= eval(document.getElementById('AMD_PRICE'+row).value.split(",").join(""));

		// VOLUME
		document.getElementById('AMD_VOLM'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat((AMD_VOLM)),decFormat));
		document.getElementById('data'+row+'AMD_VOLM').value 	= parseFloat((AMD_VOLM));

		// PRICE
		document.getElementById('AMD_PRICE'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat((AMD_PRICE)),decFormat));
		document.getElementById('data'+row+'AMD_PRICE').value 	= parseFloat((AMD_PRICE));

		// TOTAL PRICE
		var AMD_TOTAL	= parseFloat(AMD_VOLM) * parseFloat(AMD_PRICE);
		document.getElementById('data'+row+'AMD_TOTAL').value 	= parseFloat((AMD_TOTAL));
		if(AMD_CLASS == 1)		// PRICE ONLY
		{
			// VOLUME DIAMBIL DARI SISA ANGGARAN
			//var AMD_TOTAL	= 1 * parseFloat(AMD_PRICE);
			var REM_BUDG 	= document.getElementById('data'+row+'REM_BUDG').value;
			var AMD_TOTAL	= parseFloat(REM_BUDG) * parseFloat(AMD_PRICE);
			document.getElementById('data'+row+'AMD_TOTAL').value 	= parseFloat((AMD_TOTAL));
		}
		/*else if(AMD_CLASS == 2)
		{
			var AMD_TOTAL	=  parseFloat(AMD_VOLM) * 1;
			document.getElementById('data'+row+'AMD_TOTAL').value 	= parseFloat((AMD_TOTAL));
		}*/

		// GRAND TOTAL
		countGTotal(row);
	}

	function chgTTsf(thisVal, row)
	{
		var decFormat	= document.getElementById('decFormat').value;
		var AMD_TRANSFER	= eval(thisVal.value.split(",").join(""));
		var JOBCODEIDH		= document.getElementById('dataSUB'+row+'JOBCODEIDH').value;
		var AMD_MAXVAL		= eval(document.getElementById('dataSUB'+row+'AMD_MAXVAL').value);
		var AMD_VOLMSUB		= eval(document.getElementById('dataSUB'+row+'AMD_VOLM').value);

		if(AMD_TRANSFER > AMD_MAXVAL)
		{
			swal("<?=$alert14?>",
			{
				icon:"warning"
			})
			.then(function()
			{
				swal.close();
				document.getElementById('dataSUB'+row+'AMD_TOTTSFX').focus();
				document.getElementById('dataSUB'+row+'AMD_TOTTSFX').value 	= doDecimalFormat(RoundNDecimal(parseFloat((0)), 2));
				document.getElementById('dataSUB'+row+'AMD_TOTTSF').value 	= RoundNDecimal(parseFloat((0)), 2);
			});
			return false;
		}
		else
		{
			// SISA SETELAH DIKURANGI NILAI RANSFER
				var REM_BUDGET 	= parseFloat(AMD_MAXVAL - AMD_TRANSFER);

			var AMD_PRICESUB 	= REM_BUDGET / AMD_VOLMSUB;

			console.log('aa')
			document.getElementById('dataSUB'+row+'AMD_TOTTSFX').value 	= doDecimalFormat(RoundNDecimal(parseFloat((AMD_TRANSFER)), 2));
			document.getElementById('dataSUB'+row+'AMD_TOTTSF').value 	= RoundNDecimal(parseFloat((AMD_TRANSFER)), 2);
			document.getElementById('dataSUB'+row+'AMD_TOTAL').value 	= RoundNDecimal(parseFloat((REM_BUDGET)), 2);
			console.log('b')
			document.getElementById('dataSUB'+row+'AMD_PRICE').value 	= parseFloat((AMD_PRICESUB));
			console.log('c')

			totrow2		= document.getElementById('totalrow2').value;
			AMD_GTOTTSF	= 0;
			for(i=1;i<=totrow2;i++)
			{
				let myObj 	= document.getElementById('dataSUB'+i+'AMD_TOTTSF');
				var values 	= typeof myObj !== 'undefined' ? myObj : '';
				
				if(values != null)
				{
					var JOBCODEIDHB = document.getElementById('dataSUB'+i+'JOBCODEIDH').value;
					if(JOBCODEIDH == JOBCODEIDHB)
					{
						var AMD_TOTTSF 	= document.getElementById('dataSUB'+i+'AMD_TOTTSF').value;
						AMD_GTOTTSF		= parseFloat(AMD_GTOTTSF) + parseFloat(AMD_TOTTSF);
					}
				}
			}

			console.log('d')
			// SENT TOTAL TO OVER BUDGET
			totrow		= document.getElementById('totalrow').value;
			for(j=1;j<=totrow;j++)
			{
				let myObj 	= document.getElementById('data'+j+'JOBCODEID');
				var values 	= typeof myObj !== 'undefined' ? myObj : '';

				if(values != null)
				{
					var JOBCODEID 	= document.getElementById('data'+j+'JOBCODEID').value;
					if(JOBCODEIDH == JOBCODEID)
					{
						var AMD_PRICE 	= document.getElementById('data'+j+'AMD_PRICE').value;
						var AMD_VOLM 	= parseFloat(AMD_GTOTTSF / AMD_PRICE);

						document.getElementById('data'+j+'AMD_MAXVAL').value 	= AMD_GTOTTSF;
						document.getElementById('data'+j+'AMD_TOTAL').value 	= AMD_GTOTTSF;
						document.getElementById('data'+j+'AMD_VOLM').value 		= AMD_VOLM;
						document.getElementById('AMD_VOLM'+j).value 			= doDecimalFormat(RoundNDecimal(parseFloat((AMD_VOLM)), 2));
						document.getElementById('idAMDTOTAL'+j).innerHTML 		= doDecimalFormat(RoundNDecimal(parseFloat((AMD_GTOTTSF)), 2));
					}
				}
			}
		}
	}

	function chgVolOTH(thisVal, row)
	{
		var decFormat	= document.getElementById('decFormat').value;
		console.log('a')
		var AMD_CLASS	= document.getElementById('data'+row+'AMD_CLASS').value;
		console.log('b')
		var PRJCODE		= document.getElementById('PRJCODE').value;
		console.log('c')

		var AMD_MAXVAL 	= document.getElementById('data'+row+'AMD_MAXVAL').value;
		console.log('d')
		var AMD_VOLM	= eval(thisVal.value.split(",").join(""));
		console.log('e')
		//var AMD_PRICE	= eval(document.getElementById('AMD_PRICE'+row).value.split(",").join(""));

		// VOLUME
			document.getElementById('AMD_VOLM'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat((AMD_VOLM)),decFormat));
			document.getElementById('data'+row+'AMD_VOLM').value 	= parseFloat((AMD_VOLM));

		// PRICE
			var AMD_PRICE = parseFloat(AMD_MAXVAL / AMD_VOLM);
			document.getElementById('AMD_PRICE'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat((AMD_PRICE)),decFormat));
			document.getElementById('data'+row+'AMD_PRICE').value 	= parseFloat((AMD_PRICE));
	}

	function chgPriceOTH(thisVal, row)
	{
		var decFormat	= document.getElementById('decFormat').value;
		var AMD_CLASS	= document.getElementById('data'+row+'AMD_CLASS').value;
		var PRJCODE		= document.getElementById('PRJCODE').value;

		var AMD_MAXVAL 	= document.getElementById('data'+row+'AMD_MAXVAL').value;
		//var AMD_VOLM	= eval(document.getElementById('AMD_VOLM'+row).value.split(",").join(""));
		var AMD_PRICE	= eval(thisVal.value.split(",").join(""));

		// VOLUME
			var AMD_VOLM= parseFloat(AMD_MAXVAL / AMD_PRICE);
			document.getElementById('AMD_VOLM'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat((AMD_VOLM)),decFormat));
			document.getElementById('data'+row+'AMD_VOLM').value 	= parseFloat((AMD_VOLM));

		// PRICE
			document.getElementById('AMD_PRICE'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat((AMD_PRICE)),decFormat));
			document.getElementById('data'+row+'AMD_PRICE').value 	= parseFloat((AMD_PRICE));
	}

	function countGTotal(row)
	{
		decFormat	= document.getElementById('decFormat').value;
		totrow		= document.getElementById('totalrow').value;
		AMD_AMOUNT	= 0;
		for(i=1;i<=totrow;i++)
		{
			let myObj 	= document.getElementById('data'+i+'AMD_TOTAL');
			var values 	= typeof myObj !== 'undefined' ? myObj : '';

			//console.log(i+' = '+ values)
			
			if(values != null)
			{
				var AMD_TOTAL 	= document.getElementById('data'+i+'AMD_TOTAL').value;
				AMD_AMOUNT		= parseFloat(AMD_AMOUNT) + parseFloat(AMD_TOTAL);
			}
		}
		document.getElementById('AMD_AMOUNT').value 	= parseFloat(AMD_AMOUNT);
		document.getElementById('AMD_AMOUNTX').value 	= doDecimalFormat(RoundNDecimal(parseFloat((AMD_AMOUNT)),decFormat));

		var AMD_CATEG	= document.getElementById('AMD_CATEG').value;
		if(AMD_CATEG == 'SI' || AMD_CATEG == 'SINJ')
		{
			AMD_REFNOAM	= document.getElementById('AMD_REFNOAM').value;
			if(AMD_AMOUNT > AMD_REFNOAM)
			{
				swal('<?php echo $alert2; ?>',
				{
					icon: "warning"
				});
				document.getElementById('AMD_VOLM'+row).value = 0;
				document.getElementById('data'+row+'AMD_VOLM').value = 0;
				document.getElementById('AMD_VOLM'+row).focus();
				return false;
			}
		}
	}

	/* --------------------- Before Update ---------------------------------------------------------------------------------
		function chgRad1(thisVal, row)
		{
			decFormat	= document.getElementById('decFormat').value;
			AMD_CLASS1	= document.getElementById('AMD_CLASS1'+row).checked;

			if(AMD_CLASS1 == true)
			{
				document.getElementById('data'+row+'AMD_CLASS').value 	= 1;
				document.getElementById('AMD_VOLM'+row).disabled 		= true;
				document.getElementById('AMD_VOLM'+row).value 			= 0;
				document.getElementById('data'+row+'AMD_VOLM').value	= 0;
				document.getElementById('AMD_VOLM'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat((0)),decFormat));

				document.getElementById('AMD_CLASS2'+row).checked		= false;
				document.getElementById('AMD_PRICE'+row).disabled 		= false;
			}
			else
			{
				document.getElementById('data'+row+'AMD_CLASS').value 	= 0;
				document.getElementById('AMD_VOLM'+row).disabled 		= false;
			}
			countGTotal(row);
		}

		function chgRad2(thisVal, row)
		{
			decFormat	= document.getElementById('decFormat').value;
			AMD_CLASS2	= document.getElementById('AMD_CLASS2'+row).checked;

			if(AMD_CLASS2 == true)
			{
				document.getElementById('data'+row+'AMD_CLASS').value 	= 2;
				document.getElementById('AMD_PRICE'+row).disabled 		= true;
				//document.getElementById('AMD_PRICE'+row).value 		= 0;
				//document.getElementById('data'+row+'AMD_PRICE').value	= 0;
				//document.getElementById('AMD_PRICE'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat((0)),decFormat));

				document.getElementById('AMD_CLASS1'+row).checked		= false;
				document.getElementById('AMD_VOLM'+row).disabled 		= false;
			}
			else
			{
				document.getElementById('data'+row+'AMD_CLASS').value 	= 0;
				document.getElementById('AMD_PRICE'+row).disabled 		= false;
			}
			countGTotal(row);
		}
	--------------------- Before Update --------------------------------------------------------------------------------- */

	/* -------------------------- Update [iyan][211113] ------------------------------------------------------------------------ */
	function chgRad1(thisVal, row)
	{
		decFormat	= document.getElementById('decFormat').value;
		AMD_CLASS1	= document.getElementById('AMD_CLASS1'+row).checked; 
		AMD_VOLM 	= document.getElementById('AMD_VOLM'+row).value;
		AMD_PRICE 	= document.getElementById('AMD_PRICE'+row).value;
		AMD_TOTAL 	= parseFloat(AMD_VOLM) * parseFloat(AMD_PRICE);
		//alert(AMD_TOTAL);


		if(AMD_CLASS1 == true)
		{
			document.getElementById('data'+row+'AMD_CLASS').value 	= 2;
			document.getElementById('AMD_VOLM'+row).disabled 		= false;
			document.getElementById('AMD_VOLM'+row).value 			= 0;
			document.getElementById('data'+row+'AMD_VOLM').value	= 0;
			document.getElementById('AMD_VOLM'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat((0)),decFormat));
			
			document.getElementById('data'+row+'AMD_TOTAL').value 	= parseFloat((AMD_TOTAL));
			//alert(AMD_PRICE);

			document.getElementById('AMD_CLASS2'+row).checked		= false;
			document.getElementById('AMD_PRICE'+row).disabled 		= true;
		}
		else
		{
			document.getElementById('data'+row+'AMD_CLASS').value 	= 0;
			document.getElementById('AMD_PRICE'+row).disabled 		= false;
		}
		countGTotal(row);
	}

	function chgRad2(thisVal, row)
	{
		decFormat	= document.getElementById('decFormat').value;
		AMD_CLASS2	= document.getElementById('AMD_CLASS2'+row).checked;
		AMD_PRICE 	= document.getElementById('data'+row+'AMD_PRICE').value;

		if(AMD_CLASS2 == true)
		{
			document.getElementById('data'+row+'AMD_CLASS').value 	= 1;
			document.getElementById('AMD_PRICE'+row).disabled 		= false;
			document.getElementById('AMD_VOLM'+row).value 			= 0;
			document.getElementById('data'+row+'AMD_VOLM').value	= 0;
			document.getElementById('AMD_VOLM'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat((0)),decFormat));

			//document.getElementById('AMD_PRICE'+row).value 		= 0;
			//document.getElementById('data'+row+'AMD_PRICE').value	= 0;
			//document.getElementById('AMD_PRICE'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat((0)),decFormat));

			// VOLUME DIAMBIL DARI SISA ANGGARAN
				var REM_BUDG 	= document.getElementById('data'+row+'REM_BUDG').value;
				var AMD_TOTAL	= parseFloat(REM_BUDG) * parseFloat(AMD_PRICE);
				document.getElementById('data'+row+'AMD_TOTAL').value 	= parseFloat((AMD_TOTAL));

			document.getElementById('AMD_CLASS1'+row).checked		= false;
			document.getElementById('AMD_VOLM'+row).disabled 		= true;
		}
		else
		{
			document.getElementById('data'+row+'AMD_CLASS').value 	= 0;
			document.getElementById('AMD_VOLM'+row).disabled 		= false;
		}
		countGTotal(row);
	}
	/* -------------------------- End Update [iyan][211113] -------------------------------------------------------------------- */

	function deleteRow(btn)
	{
		var row = document.getElementById("tr_" + btn);
		row.remove();

		objTable 		= document.getElementById('tblINV');
		intTable 		= objTable.rows.length;

		document.getElementById('IR_NUM1').value = '';

		for(i=1; i<=intTable; i++)
		{
			INV_CODEH	= document.getElementById('INV_CODEH'+i).value;
			IR_NUM1 		= document.getElementById('IR_NUM1').value;
			if(IR_NUM1 == '')
				document.getElementById('IR_NUM1').value = INV_CODEH;
			else
				document.getElementById('IR_NUM1').value = IR_NUM1+'~'+INV_CODEH;
		}
	}

	function deleteRowSUBS(btn)
	{
		var row = document.getElementById("tbl_sub" + btn);
		row.remove();

		objTable 		= document.getElementById('tblINV');
		intTable 		= objTable.rows.length;

		document.getElementById('IR_NUM1').value = '';

		for(i=1; i<=intTable; i++)
		{
			INV_CODEH	= document.getElementById('INV_CODEH'+i).value;
			IR_NUM1 		= document.getElementById('IR_NUM1').value;
			if(IR_NUM1 == '')
				document.getElementById('IR_NUM1').value = INV_CODEH;
			else
				document.getElementById('IR_NUM1').value = IR_NUM1+'~'+INV_CODEH;
		}
	}

	function checkInp()
	{
		AMD_CATEG	= document.getElementById('AMD_CATEG').value;
		if(AMD_CATEG == '')
		{
			swal('<?php echo $alert1; ?>',
			{
				icon:"warning",
			});
			document.getElementById('AMD_CATEG').focus();
			return false;
		}

		JOBCODEID	= document.getElementById('JOBCODEID').value;
		if(JOBCODEID == '')
		{
			swal('<?php echo $alert7; ?>',
			{
				icon: "warning",
			})
			.then(function()
			{
				swal.close();
				$('#JOBCODEID1').focus();
			});
			return false;
		}

		totalrow	= parseFloat(document.getElementById('totalrow').value);
		console.log('totalrow = '+totalrow)
		if(totalrow == 0)
		{
			swal('<?php echo $alert8; ?>',
			{
				icon: "warning",
			});
			return false;
		}

		for(i=1; i<=totalrow; i++)
		{
			JOBPARENT 	= document.getElementById('JOBCODEID').value;
			JOBCODEID	= document.getElementById('data'+i+'JOBCODEID').value;
			AMD_VOLM	= parseFloat(document.getElementById('data'+i+'AMD_VOLM').value);
			AMD_PRICE	= parseFloat(document.getElementById('data'+i+'AMD_PRICE').value);
			if(JOBCODEID == '')
			{
				swal('<?php echo $alert9; ?>'+JOBPARENT,
				{
					icon: "warning",
				})
				.then(function()
	            {
	            	document.getElementById('data'+i+'JOBCODEID').focus();
	                swal.close();
	            });
				return false;
			}

			if(AMD_VOLM == 0)
			{
				swal('<?php echo $alert12; ?>',
				{
					icon: "warning",
				})
				.then(function()
	            {
	            	document.getElementById('AMD_VOLM'+i).focus();
	                swal.close();
	            });
				return false;
			}

			if(AMD_PRICE == 0)
			{
				swal('<?php echo $alert13; ?>',
				{
					icon: "warning",
				})
				.then(function()
	            {
	            	document.getElementById('AMD_PRICE'+i).focus();
	                swal.close();
	            });
				return false;
			}
		}
		document.getElementById('btnSave').style.display = 'none';
		document.getElementById('btnBack').style.display = 'none';
	}

	function doDecimalFormat(angka) {
		var a, b, c, dec, i, j;
		angka = String(angka);
		if(angka.indexOf('.') > -1)
		{
			a = angka.split('.')[0] ; dec = angka.split('.')[1]
		}
		else
		{
			a = angka;
			dec = -1;
		}

		b = a.replace(/[^\d]/g,"");
		c = "";
		var panjang = b.length;
		j = 0;
		for (i = panjang; i > 0; i--) {
			j = j + 1;
			if (((j % 3) == 1) && (j != 1)) c = b.substr(i-1,1) + "," + c;
			else c = b.substr(i-1,1) + c;
		}
		if(angka < 0)
		{
			return angka;
		}
		else
		{
			if(dec == -1) return angka;
			else return (c + '.' + dec);
		}
	}

	function RoundNDecimal(X, N) {
		var T, S=new String(Math.round(X*Number("1e"+N)))
		while (S.length<=N) S='0'+S
		return S.substr(0, T=(S.length-N)) + '.' + S.substr(T, N)
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