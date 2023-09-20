<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 22 April 2018
 * File Name	= v_amd_form.php
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

	$this->db->where('Patt_Year', $year);
	//$this->db->where('Patt_Month', $month);
	//$this->db->where('Patt_Date', $date);
	$myCount = $this->db->count_all('tbl_amd_header');
	
	$sql = "SELECT MAX(Patt_Number) as maxNumber FROM tbl_amd_header
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
	$AMD_CODE	= $lastPatternNumb;
	$PRJCODE	= $PRJCODE;
	$JOBCODEID	= '';
	$AMD_TYPE	= 0;
	$AMD_CATEG	= '';
	$AMD_REFNO	= '';
	$AMD_REFNOAM= 0;
	$AMD_DATE 	= date('m/d/Y');
	$AMD_DESC	= '';
	$AMD_UNIT	= '';
	$JOBDESC	= '';
	$AMD_NOTES	= '';
	$AMD_MEMO	= '';
	$AMD_AMOUNT	= 0;
	$AMD_STAT	= 1;
	$Patt_Year 	= date('Y');
	$AMD_FUNC	= '';
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
	$JOBCODEID		= $default['AMD_JOBID'];
	$AMD_DATE 		= date('m/d/Y', strtotime($default['AMD_DATE']));
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
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $appName; ?></title>
        <!-- Tell the browser to be responsive to screen width -->
        <?php
			$vers     = $this->session->userdata['vers'];

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
			if($TranslCode == 'Unit')$Unit = $LangTransl;
			if($TranslCode == 'Remarks')$Remarks = $LangTransl;
			if($TranslCode == 'Price')$Price = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'Total')$Total = $LangTransl;
			if($TranslCode == 'Tax')$Tax = $LangTransl;
			if($TranslCode == 'Type')$Type = $LangTransl;				
			if($TranslCode == 'Add')$Add = $LangTransl;
			if($TranslCode == 'Save')$Save = $LangTransl;
			if($TranslCode == 'Update')$Update = $LangTransl;
			if($TranslCode == 'Cancel')$Cancel = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'AmandTotal')$AmandTotal = $LangTransl;
			if($TranslCode == 'InvList')$InvList = $LangTransl;
			if($TranslCode == 'JobParent')$JobParent = $LangTransl;
			if($TranslCode == 'JobName')$JobName = $LangTransl;
			if($TranslCode == 'TotAmount')$TotAmount = $LangTransl;
			if($TranslCode == 'AddItem')$AddItem = $LangTransl;
			if($TranslCode == 'Others')$Others = $LangTransl;
			if($TranslCode == 'SINumber')$SINumber = $LangTransl;
			if($TranslCode == 'Approve')$Approve = $LangTransl;
			if($TranslCode == 'Approver')$Approver = $LangTransl;
			if($TranslCode == 'Approved')$Approved = $LangTransl;
			if($TranslCode == 'Approval')$Approval = $LangTransl;
			if($TranslCode == 'NotYetApproved')$NotYetApproved = $LangTransl;
			if($TranslCode == 'Awaiting')$Awaiting = $LangTransl;
		endforeach;
		
		if($LangID == 'IND')
		{
			$h1_title 	= "Tambah";
			$h2_title 	= "Amandemen Anggaran";
			$h3_title	= "Penerimaan Barang";
			$alert1		= "Silahan pilih kategori amandemen.";
			$alert2		= "Jumlah total Amandemen melebihi Total SI.";
			$alert3		= "Jumlah total Amandemen bernilai Nol.";
			$alert4		= "Silahkan pilih Nomo SI.";
			$alert5		= "Silahkan tentukan fungsi SI.";
			$alert6		= "Silahkan tulis alasan revisi/tolak/membatalkan dokumen.";
			$alert7		= "Silahan pilih kategori amandemen.";
			$alert8		= "Silahan pilih status amandemen.";
			$alert9		= "Silahkan tulis alasan revisi dokumen.";
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
			$alert5		= "Please select SI Function.";
			$alert6		= "Plese input the reason why you revise/reject/void the document.";
			$alert7		= "Please select an Amendment Category.";
			$alert8		= "Please select Amendment Status.";
			$alert9		= "Plese input the reason why you revise the document.";
			$alertSubmit= "data has been successfully saved";
		}
		$AMD_TYPE1	= $AMD_TYPE;
		$AMD_CATEG1	= $AMD_CATEG;
		
		$AMD_JOBID1		= $JOBCODEID;
		if(isset($_POST['AMD_JOBID1']))
		{
			$PRJCODE1	= $_POST['PRJCODE1'];
			$AMD_TYPE1	= $_POST['AMD_TYPE1'];
			$AMD_CATEG1	= $_POST['AMD_CATEG1'];
			$AMD_JOBID1	= $_POST['AMD_JOBID1'];
			$sqlJDESC 	= "SELECT JOBPARENT, JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$AMD_JOBID1' LIMIT 1";
			$resJDESC 	= $this->db->query($sqlJDESC)->result();
			foreach($resJDESC as $rowJDESC) :
				$JOBPARENT 	= $rowJDESC->JOBPARENT;
				//$JOBDESC 	= $rowJDESC->JOBDESC;
				$JOBDESC 	= '';
			endforeach;
			
			// FOR NOT BUDGETING. SEARCH THE LAST NUMBER
			if($AMD_CATEG1 == 'SINJ')
			{
				// COUNT CHILD FOR THE PARENT
				$sqlCHLDC 	= "tbl_joblist WHERE JOBPARENT = '$AMD_JOBID1'";
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
				
				// new rules
				if($resCHLDC > 0)
				{
					$sqlCHLDC 	= "SELECT JOBCODEID FROM tbl_joblist WHERE JOBPARENT = '$AMD_JOBID1' 
									ORDER BY JOBCODEID DESC limit 1";
					$resCHLDC 	= $this->db->query($sqlCHLDC)->result();
					foreach($resCHLDC as $row01):
						$JOBCODEID	= $row01->JOBCODEID;
					endforeach;
					
					$pecah			= explode(".",$JOBCODEID);
					$num_tags 		= count($pecah) - 1;
					$lastPatNum		= $pecah[$num_tags];
					$resCHLDC		= (int)$lastPatNum + 1;
					
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
					$NEW_JOBCODEID	= "$AMD_JOBID1.$lastJobNum";
				}
				else
				{
					$lastJobNum 	= $nolJN.$resCHLDC;
					$NEW_JOBCODEID	= "$AMD_JOBID1.$lastJobNum";
				}
				
				$NEW_JOBCODEIDV	= $NEW_JOBCODEID;
				$NEW_JOBPARENT	= $AMD_JOBID1;
				
				/*$JOBCODEDET 	= "D$NEW_JOBCODEID";	// B
				$JOBCODEID 		= $NEW_JOBCODEID;		// C
				$JOBPARENT 		= $NEW_JOBPARENT;		// D
				$JOBCODE		= $NEW_JOBCODEID;		// E
				$PRJCODE		= $PRJCODE;				// F
				$JOBDESC		= $data->val($i, 9);		// I
				$ITM_GROUP		= $data->val($i, 10);		// J
				$GROUP_CATEG	= $data->val($i, 11);		// K
				$ITM_CODE		= $data->val($i, 12);		// L
				$ITM_UNIT		= $data->val($i, 13);		// M
				$ITM_VOLM		= $data->val($i, 14);		// N
				$ITM_PRICE		= $data->val($i, 15);		// O
				$ITM_LASTP		= $data->val($i, 16);		// P
				$ITM_BUDG		= $data->val($i, 17);		// Q
				$BOQ_VOLM		= $data->val($i, 18);		// R
				$BOQ_PRICE		= $data->val($i, 19);		// S
				$BOQ_BUDG		= $data->val($i, 20);		// T
				$BOQ_BOBOT		= $data->val($i, 21);		// U
				$ISBOBOT		= $data->val($i, 22);		// V
				$IS_LEVEL		= $data->val($i, 37);		// AK
				$ISLAST			= $data->val($i, 39);		// AM
				$Patt_Number	= $data->val($i, 40);		// AN*/
			}
		}
		
		$sqlPRJ1 	= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE' LIMIT 1";
		$restPRJ1 	= $this->db->query($sqlPRJ1)->result();	
		foreach($restPRJ1 as $rowPRJ1) :
			$PRJNAME1 	= $rowPRJ1->PRJNAME;
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
							AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE')";
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
								AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE')";
				$resAPPT	= $this->db->query($sqlAPP)->result();
				foreach($resAPPT as $rowAPPT) :
					$DOCAPP_TYPE	= $rowAPPT->DOCAPP_TYPE;
				endforeach;
			}
			
			$DOC_NUM	= $AMD_NUM;
			$sqlSTEPAPP	= "tbl_docstepapp_det WHERE MENU_CODE = '$MenuCode' AND APPROVER_1 = '$DefEmp_ID'
							AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE')";
			$resSTEPAPP	= $this->db->count_all($sqlSTEPAPP);
			if($resSTEPAPP > 0)
			{
				$canApprove	= 1;
				$APPLIMIT_1	= 0;
				
				$sqlAPP	= "SELECT APPLIMIT_1, APP_STEP, MAX_STEP FROM tbl_docstepapp_det WHERE MENU_CODE = '$MenuCode'
							AND APPROVER_1 = '$DefEmp_ID' AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE')";
				$resAPP	= $this->db->query($sqlAPP)->result();
				foreach($resAPP as $rowAPP) :
					$APPLIMIT_1	= $rowAPP->APPLIMIT_1;	// MAXIMUM AMOUNT
					$APP_STEP	= $rowAPP->APP_STEP;	// CURRENT STEP
					$MAX_STEP	= $rowAPP->MAX_STEP;
				endforeach;
				
				$sqlC_App	= "tbl_approve_hist WHERE AH_CODE = '$AMD_NUM'";
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
				$APPROVE_AMOUNT = $AMD_AMOUNT;
				//$APPROVE_AMOUNT	= 10000000000;
				//$DOCAPP_TYPE	= 1;
				
				if($DOCAPP_TYPE == 1)
				{
					// CEK CURRENT APPROVER
					$BEF_STEP_APP	= $BefStepApp;
					$CURR_STEP_APP	= $APP_STEP;
					$MAX_STEP_APP	= $MAX_STEP;
					
					if($BEF_STEP_APP == 0)		// CURRENT USER ADALAH PENYETUJU PERTAMA
					{
						$canApprove		= 1;
						$APPROVER_BEF	= '';
						$APPLIMIT_BEF	= 0;
						
						// CEK APAKAH STEP KE-2 HARUS APPROVE. JIKA MASIH HARUS APPROVE, IS_LAST = 0
						$APPROVER_BEF1	= '';
						$APPLIMIT_BEF1	= 0;
						$sqlBEFAPP1		= "SELECT APPROVER_1, APPLIMIT_1 FROM tbl_docstepapp_det 
											WHERE 
												MENU_CODE = '$MenuCode'
												AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE')
												AND APP_STEP = 1";
						$resBEFAPP1		= $this->db->query($sqlBEFAPP1)->result();
						foreach($resBEFAPP1 as $rowBEFAPP1) :
							$APPROVER_BEF1	= $rowBEFAPP1->APPROVER_1;
							$APPLIMIT_BEF1	= $rowBEFAPP1->APPLIMIT_1;
						endforeach;
						
						if($APPROVE_AMOUNT <= $APPLIMIT_BEF1)
						{
							$IS_LAST	= 1;
						}
						else
						{
							$IS_LAST	= 0;
						}
					}
					elseif($BEF_STEP_APP > 0)	// BUKAN PENYETUJU PERTAMA
					{
						// CEK SIAPA APPROVER SEBELUMNYA DAN BERAPA MAXIMAL APPROVE NYA
						$APPROVER_BEF	= '';
						$APPLIMIT_BEF	= 0;
						$sqlBEFAPP		= "SELECT APPROVER_1, APPLIMIT_1 FROM tbl_docstepapp_det 
											WHERE 
												MENU_CODE = '$MenuCode'
												AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE')
												AND APP_STEP = $BEF_STEP_APP";
						$resBEFAPP		= $this->db->query($sqlBEFAPP)->result();
						foreach($resBEFAPP as $rowBEFAPP) :
							$APPROVER_BEF	= $rowBEFAPP->APPROVER_1;
							$APPLIMIT_BEF	= $rowBEFAPP->APPLIMIT_1;	// MAXIMAL AMOUNT APPROVE BY BEFORE APPROVER
						endforeach;
						
						if($APPROVE_AMOUNT <= $APPLIMIT_BEF)
						{
							$canApprove	= 0;
							if($LangID 	== 'IND')
								$descApp	= "Dokumen ini tidak memerlukan persetujuan Anda.";
							else
								$descApp	= "This document does not require your approval";
								
							$statcoloer	= "danger";
						}
						else
						{
							$canApprove	= 1;
						}
						
						$sqlC_AppBEF	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_BEF'";
						$resC_AppBEF 	= $this->db->count_all($sqlC_AppBEF);
						
						if($resC_AppBEF == 0)
							$canApprove	= 0;
						else
							$canApprove	= 1;
						
						if($canApprove == 1)
						{
							$NEXT_STEP_APP	= $CURR_STEP_APP + 1;
							// CEK APAKAH STEP KE-2 HARUS APPROVE. JIKA MASIH HARUS APPROVE, IS_LAST = 0
							$APPROVER_BEFN	= '';
							$APPLIMIT_BEFN	= 0;
							$sqlBEFAPP2		= "SELECT APPROVER_1, APPLIMIT_1 FROM tbl_docstepapp_det 
												WHERE 
													MENU_CODE = '$MenuCode'
													AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE')
													AND APP_STEP = $NEXT_STEP_APP";
							$resBEFAPP2		= $this->db->query($sqlBEFAPP2)->result();
							foreach($resBEFAPP2 as $rowBEFAPP2) :
								$APPROVER_BEF2	= $rowBEFAPP2->APPROVER_1;
								$APPLIMIT_BEF2	= $rowBEFAPP2->APPLIMIT_1;
							endforeach;
							
							if($APPROVE_AMOUNT <= $APPLIMIT_BEF2)
							{
								$IS_LAST	= 1;
							}
							else
							{
								$IS_LAST	= 0;
							}
						}
					}
					
					/*$APPLIMIT_1V 	= number_format($APPLIMIT_1);
					$APPROVE_AMOUNTV = number_format($APPROVE_AMOUNT);
					echo "BEF_STEP_APP = $BEF_STEP_APP";*/
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
				$sqlCMJR	= "tbl_po_detail A
									INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
										AND B.PRJCODE
								WHERE PR_NUM = '$DOC_NUM' 
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
		    <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/amendment.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $h2_title; ?>
		    <small><?php echo $PRJNAME1; ?></small>  </h1>
		</section>

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
		                    <!-- after get JobcodeID code -->
		                    <form name="frmsrch1" id="frmsrch1" action="" method=POST style="display:none">
		                        <input type="text" name="PRJCODE1" id="PRJCODE1" value="<?php echo $PRJCODE; ?>" />
		                        <input type="text" name="AMD_JOBID1" id="AMD_JOBID1" value="<?php echo $JOBCODEID; ?>" />
		                        <input type="text" name="AMD_TYPE1" id="AMD_TYPE1" value="<?php echo $AMD_TYPE; ?>" />
		                        <input type="text" name="AMD_CATEG1" id="AMD_CATEG1" value="<?php echo $AMD_CATEG; ?>" />
		                        <input type="submit" class="button_css" name="submitSrch1" id="submitSrch1" value=" search " />
		                    </form>
		                    <!-- End -->
		                    
		                    <form class="form-horizontal" name="frm" id="frm" method="post" action="<?php echo $form_action; ?>">
		                        <input type="hidden" name="IS_LAST" id="IS_LAST" value="<?php echo $IS_LAST; ?>">
		                        <input type="hidden" name="APP_LEVEL" id="APP_LEVEL" value="<?php echo $APP_LEVEL; ?>">
		                    	<input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
		                        <input type="hidden" name="rowCount" id="rowCount" value="0">
		                        <input type="hidden" name="Patt_Number" id="Patt_Number" value="<?php echo $lastPattNumb; ?>">
								<?php if($isSetDocNo == 0) { ?>
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-2 control-label">&nbsp;</label>
		                            <div class="col-sm-10">
		                                <div class="alert alert-danger alert-dismissible">
		                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		                                    <h4><i class="icon fa fa-ban"></i> <?php echo $docalert1; ?>!</h4>
		                                    <?php echo $docalert2; ?>
		                                </div>
		                            </div>
		                        </div>
		                        <?php } ?>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $AMDNumber; ?></label>
		                          	<div class="col-sm-10">
		                                <input type="text" class="form-control" name="AMD_NUM1" id="AMD_NUM1" value="<?php echo $AMD_NUM; ?>" readonly >
		                                <input type="hidden" class="form-control" style="max-width:400px" name="AMD_NUM" id="AMD_NUM" value="<?php echo $AMD_NUM; ?>" >
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Code; ?></label>
		                          	<div class="col-sm-10">
		                                <input type="text" class="form-control" name="AMD_CODE1" id="AMD_CODE1" value="<?php echo $AMD_CODE; ?>" readonly >
		                                <input type="hidden" class="form-control" style="max-width:400px" name="AMD_CODE" id="AMD_CODE" value="<?php echo $AMD_CODE; ?>" >
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Date; ?></label>
		                          	<div class="col-sm-10">
		                                <div class="input-group date">
		                                    <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
		                                    <input type="text" name="AMD_DATE1" class="form-control pull-left" id="datepicker1" value="<?php echo $AMD_DATE; ?>" style="width:120px" readonly>
		                                    <input type="hidden" name="AMD_DATE" class="form-control pull-left" id="datepicker2" value="<?php echo $AMD_DATE; ?>" style="width:120px">
		                                </div>
		                          	</div>
		                        </div>
		                        <div class="form-group" style="display:none">
		                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Type; ?></label>
		                          	<div class="col-sm-10">
		                            	<select name="AMD_TYPE1" id="AMD_TYPE1" class="form-control" style="max-width:250px" onChange="selJOB(this.value)" disabled>
		                                    <option value=""> --- </option>
		                                    <option value="LS" <?php if($AMD_TYPE1 == 'LS') { ?> selected <?php } ?>>Lumpsum - LS</option>
		                                    <option value="RS" <?php if($AMD_TYPE1 == 'RS') { ?> selected <?php } ?>>Remeasure - RS</option>
		                                    <option value="PS" <?php if($AMD_TYPE1 == 'PS') { ?> selected <?php } ?>>Provisional Sum - PS</option>
		                                </select>
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Category; ?></label>
		                          	<div class="col-sm-10">
		                            	<select name="AMD_CATEG1" id="AMD_CATEG1" class="form-control select2" onChange="selJOB(this.value)" disabled>
		                                    <option value=""> --- </option>
		                                    <option value="OB" <?php if($AMD_CATEG1 == 'OB') { ?> selected <?php } ?>>Over Budget - OB</option>
		                                    <option value="SI" <?php if($AMD_CATEG1 == 'SI') { ?> selected <?php } ?>>Site Instruction - SI</option>
		                                    <option value="SINJ" <?php if($AMD_CATEG1 == 'SINJ') { ?> selected <?php } ?>>Site Instruction - SI New Job</option>
		                                    <option value="NB" <?php if($AMD_CATEG1 == 'NB') { ?> selected <?php } ?>>Not Budgeting - NB</option>
		                                    <option value="OTH" <?php if($AMD_CATEG1 == 'OTH') { ?> selected <?php } ?>><?php echo $Others; ?> - OTH</option>
		                                </select>
		                                <input type="hidden" class="form-control" style="max-width:400px" name="AMD_CATEG" id="AMD_CATEG" value="<?php echo $AMD_CATEG1; ?>" >
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-2 control-label"><?php echo $JobParent; ?></label>
		                            <div class="col-sm-10">
		                            	<input type="hidden" class="form-control" style="max-width:400px" name="JOBCODEID" id="JOBCODEID" value="<?php echo $AMD_JOBID1; ?>" >
		                                <select name="JOBCODEIDX" id="JOBCODEIDX" class="form-control select2" data-placeholder="&nbsp;&nbsp;&nbsp;<?php echo $JobName; ?>" onChange="selJOB(this.value)" disabled>
		                                	<option value="" title=""> --- </option>
		                                    <?php
		                                        $sqlJob_1	= "SELECT JOBCODEID, JOBDESC FROM tbl_joblist WHERE JOBLEV = 1 
																AND PRJCODE = '$PRJCODE'";
		                                        $resJob_1	= $this->db->query($sqlJob_1)->result();
		                                        foreach($resJob_1 as $row_1) :
													$Disabled_1		= 0;
		                                            $JOBCODEID_1	= $row_1->JOBCODEID;
		                                            $JOBDESC_1		= $row_1->JOBDESC;
		                                            $space_level_1	= "";
		                                            
		                                            $sqlC_2		= "tbl_joblist WHERE JOBPARENT = '$JOBCODEID_1' AND ISHEADER = '1'
																	AND PRJCODE = '$PRJCODE'";
		                                            $resC_2 	= $this->db->count_all($sqlC_2);
													
		                                            if($resC_2 > 0 && $AMD_CATEG1 == 'OB')
		                                                $Disabled_1 = 1;
		                                            ?>
		                                            <option value="<?php echo "$JOBCODEID_1"; ?>" <?php if($JOBCODEID_1 == $AMD_JOBID1) { ?> selected <?php } if($Disabled_1 == 1) {?> disabled <?php } ?> title="<?php echo $JOBDESC_1; ?>">
		                                                <?php echo "$space_level_1 $JOBCODEID_1 : $JOBDESC_1"; ?>
		                                            </option>
		                                            <?php
		                                            if($resC_2 > 0)
		                                            {
		                                                $Disabled_2	= 0;
		                                                $sqlJob_2	= "SELECT JOBCODEID, JOBDESC FROM tbl_joblist WHERE JOBPARENT = '$JOBCODEID_1' AND ISHEADER = '1' AND PRJCODE = '$PRJCODE'";
		                                                $resJob_2	= $this->db->query($sqlJob_2)->result();
		                                                foreach($resJob_2 as $row_2) :
		                                                    $JOBCODEID_2	= $row_2->JOBCODEID;
		                                                    $JOBDESC_2		= $row_2->JOBDESC;
		                                                    $space_level_2	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		                                                    
		                                                    $sqlC_3		= "tbl_joblist WHERE JOBPARENT = '$JOBCODEID_2' AND ISHEADER = '1'
																			AND PRJCODE = '$PRJCODE'";
		                                                    $resC_3 	= $this->db->count_all($sqlC_3);
		                                                    if($resC_3 > 0 && $AMD_CATEG1 == 'OB')
		                                                        $Disabled_2 = 1;
		                                                    else
		                                                        $Disabled_2 = 0;
		                                                    ?>
		                                                    <option value="<?php echo "$JOBCODEID_2"; ?>" <?php if($JOBCODEID_2 == $AMD_JOBID1) { ?> selected <?php } if($Disabled_2 == 1) {?> disabled <?php } ?> title="<?php echo $JOBDESC_2; ?>">
		                                                        <?php echo "$space_level_2 $JOBCODEID_2 : $JOBDESC_2"; ?>
		                                                    </option>
		                                                    <?php
		                                                    if($resC_3 > 0)
		                                                    {
		                                                        $sqlJob_3	= "SELECT JOBCODEID, JOBDESC FROM tbl_joblist WHERE JOBPARENT = '$JOBCODEID_2' AND ISHEADER = '1' AND PRJCODE = '$PRJCODE'";
		                                                        $resJob_3	= $this->db->query($sqlJob_3)->result();
		                                                        foreach($resJob_3 as $row_3) :
		                                                            $JOBCODEID_3	= $row_3->JOBCODEID;
		                                                            $JOBDESC_3		= $row_3->JOBDESC;
		                                                            $space_level_3	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		                                                            
		                                                            $sqlC_4		= "tbl_joblist WHERE JOBPARENT = '$JOBCODEID_3' AND ISHEADER = '1'";
		                                                            $resC_4 	= $this->db->count_all($sqlC_4);
		                                                            if($resC_4 > 0 && $AMD_CATEG1 == 'OB')
		                                                                $Disabled_3 = 1;
		                                                            else
		                                                                $Disabled_3 = 0;
		                                                            ?>
		                                                            <option value="<?php echo "$JOBCODEID_3"; ?>" <?php if($JOBCODEID_3 == $AMD_JOBID1) { ?> selected <?php } if($Disabled_3 == 1) {?> disabled <?php } ?> title="<?php echo $JOBDESC_3; ?>">
		                                                                <?php echo "$space_level_3 $JOBCODEID_3 : $JOBDESC_3"; ?>
		                                                            </option>
		                                                            <?php
		                                                            if($resC_4 > 0)
		                                                            {
		                                                                $Disabled_4	= 0;
		                                                                $sqlJob_4	= "SELECT JOBCODEID, JOBDESC FROM tbl_joblist WHERE JOBPARENT = '$JOBCODEID_3' AND ISHEADER = '1' AND PRJCODE = '$PRJCODE'";
		                                                                $resJob_4	= $this->db->query($sqlJob_4)->result();
		                                                                foreach($resJob_4 as $row_4) :
		                                                                    $JOBCODEID_4	= $row_4->JOBCODEID;
		                                                                    $JOBDESC_4		= $row_4->JOBDESC;
		                                                                    $space_level_4	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		                                                                    
		                                                                    $sqlC_5		= "tbl_joblist WHERE JOBPARENT = '$JOBCODEID_4' AND ISHEADER = '1' AND PRJCODE = '$PRJCODE'";
		                                                                    $resC_5 	= $this->db->count_all($sqlC_5);
		                                                                    if($resC_5 > 0 && $AMD_CATEG1 == 'OB')
		                                                                        $Disabled_4 = 1;
		                                                                    else
		                                                                        $Disabled_4 = 0;
		                                                                    ?>
		                                                                    <option value="<?php echo "$JOBCODEID_4"; ?>" <?php if($JOBCODEID_4 == $AMD_JOBID1) { ?> selected <?php } if($Disabled_4 == 1) {?> disabled <?php } ?> title="<?php echo $JOBDESC_4; ?>">
		                                                                        <?php echo "$space_level_4 $JOBCODEID_4 : $JOBDESC_4"; ?>
		                                                                    </option>
		                                                                    <?php
		                                                                    if($resC_5 > 0)
		                                                                    {
		                                                                        $Disabled_5	= 0;
		                                                                        $sqlJob_5	= "SELECT JOBCODEID, JOBDESC FROM tbl_joblist WHERE JOBPARENT = '$JOBCODEID_4' AND ISHEADER = '1' AND PRJCODE = '$PRJCODE'";
		                                                                        $resJob_5	= $this->db->query($sqlJob_5)->result();
		                                                                        foreach($resJob_5 as $row_5) :
		                                                                            $JOBCODEID_5	= $row_5->JOBCODEID;
		                                                                            $JOBDESC_5		= $row_5->JOBDESC;
		                                                                            $space_level_5	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		                                                                            
		                                                                            $sqlC_6		= "tbl_joblist WHERE JOBPARENT = '$JOBCODEID_5' AND ISHEADER = '1' AND PRJCODE = '$PRJCODE'";
		                                                                            $resC_6 	= $this->db->count_all($sqlC_6);
		                                                                            if($resC_6 > 0)
		                                                                                $Disabled_5 = 1;
		                                                                            else
		                                                                                $Disabled_5 = 0;
		                                                                            ?>
		                                                                            <option value="<?php echo "$JOBCODEID_5"; ?>" <?php if($JOBCODEID_5 == $AMD_JOBID1) { ?> selected <?php } if($Disabled_5 == 1) {?> disabled <?php } ?> title="<?php echo $JOBDESC_5; ?>">
		                                                                                <?php echo "$space_level_5 $JOBCODEID_5 : $JOBDESC_5"; ?>
		                                                                            </option>
		                                                                            <?php
		                                                                            if($resC_6 > 0)
		                                                                            {
		                                                                                $Disabled_6	= 0;
		                                                                                $sqlJob_6	= "SELECT JOBCODEID, JOBDESC FROM tbl_joblist WHERE JOBPARENT = '$JOBCODEID_5' AND ISHEADER = '1' AND PRJCODE = '$PRJCODE'";
		                                                                                $resJob_6	= $this->db->query($sqlJob_6)->result();
		                                                                                foreach($resJob_6 as $row_6) :
		                                                                                    $JOBCODEID_6	= $row_6->JOBCODEID;
		                                                                                    $JOBDESC_6		= $row_6->JOBDESC;
		                                                                                    $space_level_6	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		                                                                                    
		                                                                                    $sqlC_7		= "tbl_joblist WHERE JOBPARENT = '$JOBCODEID_6' AND ISHEADER = '1' AND PRJCODE = '$PRJCODE'";
		                                                                                    $resC_7 	= $this->db->count_all($sqlC_7);
		                                                                                    if($resC_7 > 0)
		                                                                                        $Disabled_6 = 1;
		                                                                                    else
		                                                                                        $Disabled_6 = 0;
		                                                                                    ?>
		                                                                                    <option value="<?php echo "$JOBCODEID_6"; ?>" <?php if($JOBCODEID_6 == $AMD_JOBID1) { ?> selected <?php } if($Disabled_6 == 1) {?> disabled <?php } ?> title="<?php echo $JOBDESC_6; ?>">
		                                                                                        <?php echo "$space_level_6 $JOBCODEID_6 : $JOBDESC_6"; ?>
		                                                                                    </option>
		                                                                                    <?php
		                                                                                endforeach;
		                                                                            }
		                                                                        endforeach;
		                                                                    }
		                                                                endforeach;
		                                                            }
		                                                        endforeach;
		                                                    }
		                                                endforeach;
		                                            }
		                                        endforeach;
		                                    ?>
		                                </select>
		                            </div>
		                        </div>
		                        <script>
		                            function selJOB(JOBCODEID) 
		                            {
		                                JOBCODEID	= document.getElementById("JOBCODEID").value;
		                                document.getElementById("AMD_JOBID1").value = JOBCODEID;
		                                PRJCODE		= document.getElementById("PRJCODE").value;
		                                document.getElementById("PRJCODE1").value = PRJCODE;
		                                AMD_CATEG	= document.getElementById("AMD_CATEG").value;
		                                document.getElementById("AMD_CATEG1").value = AMD_CATEG;
		                                AMD_TYPE	= document.getElementById("AMD_TYPE").value;
		                                document.getElementById("AMD_TYPE1").value = AMD_TYPE;
		                                document.frmsrch1.submitSrch1.click();
		                            }
		                        </script>
		                        <?php if($AMD_CATEG1 == 'SI')
								{
									?>
		                                <div class="form-group">
		                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $FunctionalPosition; ?></label>
		                                    <div class="col-sm-10">
		                                        <select name="AMD_FUNC1" id="AMD_FUNC1" class="form-control" style="max-width:80px" disabled>
		                                            <option value=""> --- </option>
		                                            <option value="PLUS" <?php if($AMD_FUNC == 'PLUS') { ?> selected <?php } ?>>Plus</option>
		                                            <option value="MIN" <?php if($AMD_FUNC == 'MIN') { ?> selected <?php } ?>>Minus</option>
		                                        </select>
		                                        <input type="hidden" class="form-control" style="max-width:400px" name="AMD_FUNC" id="AMD_FUNC" value="<?php echo $AMD_FUNC; ?>" >
		                                    </div>
		                                </div>
		                        	<?php
								}
								if($AMD_CATEG1 == 'SI' || $AMD_CATEG1 == 'SINJ') 
								{
									?>
		                                <div class="form-group">
		                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $SINumber; ?> </label>
		                                    <div class="col-sm-10">
		                                        <div class="input-group">
		                                            <div class="input-group-btn">
		                                                <button type="button" class="btn btn-primary"><?php echo $Search ?> </button>
		                                            </div>
		                                            <input type="hidden" class="form-control" name="AMD_REFNO" id="AMD_REFNO" style="max-width:160px" value="<?php echo $AMD_REFNO; ?>" >
		                                            <input type="text" class="form-control" name="AMD_REFNOX" id="AMD_REFNOX" value="<?php echo $AMD_REFNO; ?>" onClick="pleaseCheck();" disabled>
		                                        </div>
		                                    </div>
		                                </div>
		                        	<?php
								}
								$url_selPR_CODE		= site_url('c_project/c_am1h0db2/pop1h0f0gSI/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
		                        ?>
		                        <script>
									var url1 = "<?php echo $url_selPR_CODE;?>";
									function pleaseCheck()
									{
										title = 'Select Item';
										w = 1000;
										h = 550;
										//window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
										var left = (screen.width/2)-(w/2);
										var top = (screen.height/2)-(h/2);
										return window.open(url1, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
									}
								</script>
		                        <div class="form-group" style="display:none">
		                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Project; ?></label>
		                          	<div class="col-sm-10">
		                            	<input type="hidden" class="form-control" style="max-width:195px" name="PRJCODE" id="PRJCODE" value="<?php echo $PRJCODE; ?>" >
		                            	<select name="selPRJCODE" id="selPRJCODE" class="form-control" style="max-width:250px" <?php if($INV_STATUS != 1) { ?> disabled <?php } ?>>
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
		                        <?php if($AMD_CATEG1 == "SI") { ?>
		                            <div class="form-group">
		                              <label for="inputName" class="col-sm-2 control-label"><?php echo $JobName; ?></label>
		                                <div class="col-sm-10">
		                                	<input type="text" class="form-control" name="AMD_DESC1" id="AMD_DESC1" value="<?php echo $JOBDESC; ?>" >
		                                    <input type="hidden" class="form-control" style="max-width:400px" name="AMD_DESC" id="AMD_DESC" value="<?php echo $JOBDESC; ?>" >
		                                </div>
		                            </div>
		                        <?php } ?>
		                        <?php if($AMD_CATEG1 == "SINJ") { // dihidden karena sudah ada fasilitas untuk membuat header ?>
		                            <div class="form-group" style="display:none">
		                              <label for="inputName" class="col-sm-2 control-label"><?php echo $JobName; ?></label>
		                                <div class="col-sm-10">
		                                    <label>
		                                        <input type="text" class="form-control" name="NEW_JOBCODEID" id="NEW_JOBCODEID" value="<?php echo $NEW_JOBCODEID; ?>" >
		                                    </label>
		                                    <label>
		                                        <input type="text" class="form-control" style="max-width:450px;" name="AMD_DESC" id="AMD_DESC" value="<?php echo $JOBDESC; ?>" >
		                                    </label>
		                                </div>
		                            </div>
		                        <?php
		                        } 
								if($AMD_CATEG1 != 'OB') { ?>
		                        <div class="form-group" style="display:none">
		                          	<label for="inputName" class="col-sm-2 control-label">Unit</label>
		                          	<div class="col-sm-10">
		                            	<select name="AMD_UNIT1" id="AMD_UNIT1" class="form-control" style="max-width:100px" disabled>
		                                    <option value="0">None</option>
		                                    <?php
												$sqlUNIT	= "SELECT * FROM tbl_unittype";
												$viewUnit	= $this->db->query($sqlUNIT)->result();
												foreach($viewUnit as $row) :
													$Unit_Type_Code = $row->Unit_Type_Code;
													$UMCODE 		= $row->UMCODE;
													$Unit_Type_Name	= $row->Unit_Type_Name;
													?>
													<option value="<?php echo $Unit_Type_Code; ?>" <?php if($UMCODE == $AMD_UNIT) { ?> selected <?php } ?>><?php echo $Unit_Type_Name; ?></option>
													<?php
												endforeach;
											?>
		                                </select>
		                                <input type="text" class="form-control" style="max-width:400px" name="AMD_UNIT" id="AMD_UNIT" value="<?php echo $AMD_UNIT; ?>" >
		                          	</div>
		                        </div>
		                        <?php } ?>
		                        <div class="form-group">
		                       	  <label for="inputName" class="col-sm-2 control-label"><?php echo $Notes; ?></label>
		                          	<div class="col-sm-10">
		                                <textarea class="form-control" name="AMD_NOTES1"  id="AMD_NOTES1" style="height:70px" disabled=""><?php echo $AMD_NOTES; ?></textarea>
		                                <textarea class="form-control" name="AMD_NOTES"  id="AMD_NOTES" style="max-width:400px;height:70px; display: none;"><?php echo $AMD_NOTES; ?></textarea>
		                          	</div>
		                        </div>
		                        <div id="revMemo" class="form-group" <?php if($AMD_MEMO == '') { ?> style="display:none" <?php } ?>>
		                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $reviseNotes; ?> </label>
		                          	<div class="col-sm-10">
		                                <textarea class="form-control" name="AMD_MEMO"  id="AMD_MEMO" style="height:70px" ><?php echo $AMD_MEMO; ?></textarea>
		                          	</div>
		                        </div>
								<?php
								if($AMD_CATEG1 == 'SI' || $AMD_CATEG1 == 'SINJ') 
								{
									?>
		                            <div class="form-group">
		                              <label for="inputName" class="col-sm-2 control-label">SI Total</label>
		                                <div class="col-sm-10">
		                                    <input type="text" class="form-control" style="text-align:right" name="AMD_REFNOAMX" id="AMD_REFNOAMX" value="<?php echo number_format($AMD_REFNOAM, 2); ?>" >
		                                    <input type="hidden" class="form-control" style="max-width:150px; text-align:right" name="AMD_REFNOAM" id="AMD_REFNOAM" value="<?php echo $AMD_REFNOAM; ?>" >
		                                </div>
		                            </div>
		                        	<?php
								}
								?>
		                        <div class="form-group">
		                       	  <label for="inputName" class="col-sm-2 control-label"><?php echo $AmandTotal; ?></label>
		                          	<div class="col-sm-10">
		                            	<input type="text" class="form-control" style="text-align:right" name="AMD_AMOUNTX" id="AMD_AMOUNTX" value="<?php echo number_format($AMD_AMOUNT, 2); ?>" disabled >
		                            	<input type="hidden" class="form-control" style="max-width:150px; text-align:right" name="AMD_AMOUNT" id="AMD_AMOUNT" value="<?php echo $AMD_AMOUNT; ?>" >
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-2 control-label"><?php echo $Status; ?></label>
		                            <div class="col-sm-10">
		                                <input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $AMD_STAT; ?>">
		                                <?php
		                                    // START : FOR ALL APPROVAL FUNCTION
												if($disableAll == 0)
												{
													if($canApprove == 1)
													{
														$disButton	= 0;
														$sqlCAPPHE	= "tbl_approve_hist WHERE AH_CODE = '$AMD_NUM' AND AH_APPROVER = '$DefEmp_ID'";
														$resCAPPHE	= $this->db->count_all($sqlCAPPHE);
														if($resCAPPHE > 0)
															$disButton	= 1;
														?>
															<select name="AMD_STAT" id="AMD_STAT" class="form-control select2" style="max-width:150px" onChange="selStat(this.value)" <?php if($disButton == 1) { ?> disabled <?php } ?> >
																<option value="0"> -- </option>
																<option value="3"<?php if($AMD_STAT == 3) { ?> selected <?php } ?>>Approved</option>
																<option value="4"<?php if($AMD_STAT == 4) { ?> selected <?php } ?>>Revised</option>
																<option value="5"<?php if($AMD_STAT == 5) { ?> selected <?php } ?>>Rejected</option>
																<option value="6"<?php if($AMD_STAT == 6) { ?> selected <?php } ?>>Closed</option>
																<option value="7"<?php if($AMD_STAT == 7) { ?> selected <?php } ?> style="display:none">Awaiting</option>
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
															Step approval not set;
														</a>
													<?php
												}
											// END : FOR ALL APPROVAL FUNCTION
		                                    $theProjCode 	= $PRJCODE;
		                                    $url_AddItem	= site_url('c_purchase/c_pr180d0c/popupallitem/?id='.$this->url_encryption_helper->encode_url($theProjCode));
		                                ?>
		                            </div>
		                        </div>
		                        <script>
									function selStat(AMDSTAT)
		                            {
		                                if(AMDSTAT == 4)
		                                {
		                                    document.getElementById('revMemo').style.display = '';
		                                }
		                                else
		                                {
		                                    document.getElementById('revMemo').style.display = 'none';
		                                }
		                            }
								</script>
		                        <?php
									$theProjCode 	= "$PRJCODE~$AMD_CATEG1~$AMD_JOBID1";
		                        	$url_AddItem	= site_url('c_project/c_am1h0db2/g374llItem_7im/?id='.$this->url_encryption_helper->encode_url($theProjCode));
									if($AMD_STAT == 1 || $AMD_STAT == 4)
									{
									?>
		                                <div class="form-group">
		                                    <label for="inputName" class="col-sm-2 control-label">&nbsp;</label>
		                                    <div class="col-sm-10">
		                                        <script>
		                                            var url = "<?php echo $url_AddItem;?>";
		                                            function selectitem()
		                                            {
														AMD_CATEG	= document.getElementById('AMD_CATEG').value;
														if(AMD_CATEG == '')
														{
															swal('<?php echo $alert1; ?>');
															document.getElementById('AMD_CATEG').focus();
															return false;
														}
		                                                title = 'Select Item';
		                                                w = 1000;
		                                                h = 550;
		                                                //window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
		                                                var left = (screen.width/2)-(w/2);
		                                                var top = (screen.height/2)-(h/2);
		                                                return window.open(url, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
		                                            }
		                                        </script>
		                                        <button class="btn btn-success" type="button" onClick="selectitem();">
		                                        <i class="glyphicon glyphicon-th-list"></i>&nbsp;&nbsp;<?php echo $AddItem; ?>
		                                        </button>
		                                    </div>
		                                </div>
		                        	<?php
									}
								?>
		                        <div class="row">
		                            <div class="col-md-12">
		                                <div class="box box-primary">
		                                <br>
		                                	<table width="100%" border="1" id="tbl">
		                                        <tr style="background:#CCCCCC">
		                                          <th width="3%" height="25" rowspan="2" style="text-align:left">&nbsp;</th>
		                                          <th width="3%" rowspan="2" style="text-align:center"><?php echo $ItemCode ?> </th>
		                                          <th width="33%" rowspan="2" style="text-align:center"><?php echo $ItemName ?> </th>
		                                          <th colspan="4" style="text-align:center"><?php echo $ItemQty; ?> </th>
		                                          <th colspan="2" rowspan="2" style="text-align:center"><?php echo $Price ?></th>
		                                          <th rowspan="2" style="text-align:center"><?php echo $Unit ?> </th>
		                                          <th width="24%" rowspan="2" style="text-align:center"><?php echo $Remarks ?> </th>
		                                        </tr>
		                                        <tr style="background:#CCCCCC">
		                                          <th style="text-align:center;"><?php echo $BeginPlan ?> </th>
		                                          <th style="text-align:center;"><?php echo $Used ?> </th>
		                                          <th colspan="2" style="text-align:center"><?php echo $Increase ?></th>
		                                        </tr>
		                                        <?php					
		                                        if($task == 'edit')
		                                        {
		                                            $sqlDET	= "SELECT DISTINCT A.AMD_NUM, A.JOBCODEID, A.ITM_CODE, A.ITM_UNIT,
																	A.AMD_VOLM, A.AMD_PRICE, A.AMD_TOTAL, A.AMD_DESC, A.AMD_CLASS,
																	B.ITM_NAME, B.ITM_GROUP,
																	C.ITM_PRICE, C.ITM_VOLM, C.ITM_USED, C.ITM_USED_AM,
																	C.ITM_STOCK, C.ITM_STOCK_AM
		                                                        FROM tbl_amd_detail A
		                                                            INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
		                                                                AND B.PRJCODE = '$PRJCODE'
																	LEFT JOIN tbl_joblist_detail C ON C.PRJCODE = '$PRJCODE' 
																		AND C.JOBCODEID = A.JOBCODEID
																	LEFT JOIN tbl_amd_header D ON D.AMD_NUM = A.AMD_NUM
															            AND D.AMD_JOBID = C.JOBPARENT
		                                                        WHERE A.AMD_NUM = '$AMD_NUM' 
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
		                                                $ITM_UNIT 		= $row->ITM_UNIT;
		                                                $AMD_VOLM 		= $row->AMD_VOLM;
		                                                $AMD_PRICE 		= $row->AMD_PRICE;
		                                                $AMD_TOTAL 		= $row->AMD_TOTAL;
		                                                $AMD_DESC 		= $row->AMD_DESC;
														$AMD_CLASS		= $row->AMD_CLASS;
		                                                $ITM_NAME 		= $row->ITM_NAME;
														
		                                                $ITM_PRICE 		= $row->ITM_PRICE;
		                                                $ITM_VOLM 		= $row->ITM_VOLM;
		                                                $ITM_USED 		= $row->ITM_USED;
		                                                $ITM_USED_AM	= $row->ITM_USED_AM;
		                                                $ITM_STOCK 		= $row->ITM_STOCK;
		                                                $ITM_STOCK_AM	= $row->ITM_STOCK_AM;
														
														if($AMD_CATEG == 'OTH')
														{
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
		                                    
		                                            /*	if ($j==1) {
		                                                    echo "<tr class=zebra1>";
		                                                    $j++;
		                                                } else {
		                                                    echo "<tr class=zebra2>";
		                                                    $j--;
		                                                }*/
		                                                ?> 
		                                                <tr id="tr_<?php echo $currentRow; ?>">
		                                                <td width="3%" height="25" style="text-align:left">
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
		                                                <td width="3%" style="text-align:left"> <!-- Item Code -->
															<?php echo $ITM_CODE; ?>
		                                                    <input type="hidden" id="data<?php echo $currentRow; ?>ITM_GROUP" name="data[<?php echo $currentRow; ?>][ITM_GROUP]" value="<?php echo $ITM_GROUP; ?>" class="form-control" style="max-width:300px;">
		                                                    <input type="hidden" id="data<?php echo $currentRow; ?>ITM_CODE" name="data[<?php echo $currentRow; ?>][ITM_CODE]" value="<?php echo $ITM_CODE; ?>" class="form-control" style="max-width:300px;">
		                                                    <input type="hidden" id="data<?php echo $currentRow; ?>AMD_NUM" name="data[<?php echo $currentRow; ?>][AMD_NUM]" value="<?php echo $AMD_NUM; ?>" class="form-control" style="max-width:300px;">
		                                                    <input type="hidden" name="data[<?php echo $currentRow; ?>][JOBCODEID]" id="data<?php echo $currentRow; ?>JOBCODEID" value="<?php echo $JOBCODEID; ?>" class="form-control" >
		                                              	</td>
		                                              	<td width="33%" style="text-align:left">
															<?php echo $ITM_NAME; ?>
		                                                    <input type="hidden" id="data<?php echo $currentRow; ?>JOBDESC" name="data[<?php echo $currentRow; ?>][JOBDESC]" value="<?php echo $ITM_NAME; ?>" class="form-control" style="max-width:300px;">
		                                                    <!-- Item Name -->
		                                              	</td>
		                                              	<td width="11%" style="text-align:right"><!-- Item Bdget -->
		                                                	<?php echo number_format($ITM_VOLM, 2); ?>
		                                                  	<input type="hidden" class="form-control" style="min-width:100px; max-width:300px; text-align:right" name="ITM_BUDGQTYx<?php echo $currentRow; ?>" id="ITM_BUDGQTYx<?php echo $currentRow; ?>" value="<?php echo number_format($ITM_VOLM, 2); ?>" disabled >
		                                              	</td>
		                                              	<td width="11%" style="text-align:right">
		                                                	<?php echo number_format($ITM_USED, 2); ?>
		                                                	<input type="hidden" class="form-control" style="min-width:110px; max-width:300px; text-align:right" name="TOT_USEDQTY<?php echo $currentRow; ?>" id="TOT_USEDQTY<?php echo $currentRow; ?>" value="<?php echo number_format($ITM_USED, 2); ?>" disabled >
														</td>
		                                             	<td width="3%" style="text-align:center" nowrap> <!-- Item Increase -->
		                                                    <input type="hidden" name="data[<?php echo $currentRow; ?>][AMD_CLASS]" id="data<?php echo $currentRow; ?>AMD_CLASS" value="<?php echo $AMD_CLASS; ?>">
		                                                    <input type="checkbox" name="data<?php echo $currentRow; ?>AMD_CLASS" id="AMD_CLASS1<?php echo $currentRow; ?>" value="1" onClick="chgRad1(this,<?php echo $currentRow; ?>);" <?php if($AMD_CLASS == 1) { ?> checked <?php } ?>>
		                                                </td>
		                                             	<td width="3%" style="text-align:center" nowrap>
		                                                    <input type="text" name="AMD_VOLM<?php echo $currentRow; ?>" id="AMD_VOLM<?php echo $currentRow; ?>" value="<?php echo number_format($AMD_VOLM, 2); ?>" class="form-control" style="min-width:80px; max-width:100px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgPrice(this,<?php echo $currentRow; ?>);" <?php if($AMD_CLASS == 1) { ?> disabled <?php } ?> >
		                                                    <input type="hidden" name="data[<?php echo $currentRow; ?>][AMD_VOLM]" id="data<?php echo $currentRow; ?>AMD_VOLM" value="<?php echo $AMD_VOLM; ?>" class="form-control" style="max-width:300px;" >
		                                                </td>
		                                             	<td width="2%" style="text-align:center">
		                                                    <input type="checkbox" name="data<?php echo $currentRow; ?>AMD_CLASS" id="AMD_CLASS2<?php echo $currentRow; ?>" value="2" onClick="chgRad2(this,<?php echo $currentRow; ?>);" <?php if($AMD_CLASS == 2) { ?> checked <?php } ?>>
		                                                </td>
		                                             	<td width="3%" style="text-align:right">
		                                                	<input type="text" name="AMD_PRICE<?php echo $currentRow; ?>" id="AMD_PRICE<?php echo $currentRow; ?>" value="<?php echo number_format($AMD_PRICE, 2); ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgPrice(this,<?php echo $currentRow; ?>);" <?php if($AMD_CLASS == 2) { ?> disabled <?php } ?> >
		                                                    <input type="hidden" name="data[<?php echo $currentRow; ?>][AMD_PRICE]" id="data<?php echo $currentRow; ?>AMD_PRICE" value="<?php echo $AMD_PRICE; ?>" class="form-control" style="max-width:300px;" >
		                                                    <input type="hidden" name="data[<?php echo $currentRow; ?>][AMD_TOTAL]" id="data<?php echo $currentRow; ?>AMD_TOTAL" value="<?php echo $AMD_TOTAL; ?>" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" >
		                                               	</td>
		                                                <td width="4%" style="text-align:center" nowrap>
		                                                	<?php echo $ITM_UNIT; ?>
		                                                    <input type="hidden" name="data[<?php echo $currentRow; ?>][ITM_UNIT]" id="data<?php echo $currentRow; ?>ITM_UNIT" value="<?php echo $ITM_UNIT; ?>" class="form-control" style="max-width:300px;" >
														</td>
		                                                <td width="24%" style="text-align:center">
		                                                	<input type="text" name="data[<?php echo $currentRow; ?>][AMD_DESC]" id="data<?php echo $currentRow; ?>AMD_DESC" size="20" value="<?php echo $AMD_DESC; ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:left">
		                                                </td>
		                                          </tr>
		                                            <?php
		                                            endforeach;
		                                        }
		                                        if($task == 'add')
		                                        {
		                                            ?>
		                                              <input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">
		                                          <?php
		                                        }
		                                        else
		                                        {
		                                            ?>
		                                              <input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">
		                                          <?php
		                                        }
		                                        ?>
		                                    </table>
		                                </div>
		                            </div>
		                        </div>
		                        <br>
		                        <div class="form-group">
		                            <div class="col-sm-offset-2 col-sm-10">
		                            	<?php
											if($disableAll == 0)
											{
												if(($AMD_STAT == 2 || $AMD_STAT == 7) && $canApprove == 1)
												{
													?>
														<button class="btn btn-primary" >
														<i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Update; ?>
														</button>&nbsp;
													<?php
												}
											}
											$backURL	= site_url('c_project/c_am1h0db2/i1dah80Idx/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
											echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;'.$Cancel.'</button>');
										?>
		                            </div>
		                        </div>
								<?php
		                            $DOC_NUM	= $AMD_NUM;
		                            $sqlCAPPH	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM'";
		                            $resCAPPH	= $this->db->count_all($sqlCAPPH);
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
		                                        if($APPROVER_1 != '')
		                                        {
		                                            $boxCol_1	= "red";
		                                            $sqlCAPPH_1	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_1'";
		                                            $resCAPPH_1	= $this->db->count_all($sqlCAPPH_1);
		                                            if($resCAPPH_1 > 0)
		                                            {
		                                                $boxCol_1	= "green";
		                                                $class		= "glyphicon glyphicon-ok-sign";
		                                                
		                                                $sqlAPPH_1	= "SELECT AH_APPROVED FROM tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_1'";
		                                                $resAPPH_1	= $this->db->query($sqlAPPH_1)->result();
		                                                foreach($resAPPH_1 as $rowAPPH_1):
		                                                    $APPROVED_1	= $rowAPPH_1->AH_APPROVED;
		                                                endforeach;
		                                            }
		                                            elseif($resCAPPH_1 == 0)
		                                            {
		                                                $Approver	= $NotYetApproved;
		                                                $class		= "glyphicon glyphicon-remove-sign";
		                                                $APPROVED_1	= "Not Set";
		                                            }
		                                        ?>
		                                            <div class="col-md-3">
		                                                <div class="info-box bg-<?php echo $boxCol_1; ?>">
		                                                    <span class="info-box-icon"><i class="<?php echo $class; ?>"></i></span>
		                                                    <div class="info-box-content">
		                                                        <span class="info-box-text"><?php echo $Approver; ?></span>
		                                                        <span class="info-box-number"><?php echo $EMPN_1; ?></span>
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
		                                        if($APPROVER_2 != '')
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
		                                            }
		                                            
		                                            if($resCAPPH_1 < 2)
		                                            {
		                                                $Approver	= $Awaiting;
		                                                $boxCol_2	= "yellow";
		                                                $class		= "glyphicon glyphicon-info-sign";
		                                            }
		                                        ?>
		                                            <div class="col-md-3">
		                                                <div class="info-box bg-<?php echo $boxCol_2; ?>">
		                                                    <span class="info-box-icon"><i class="<?php echo $class; ?>"></i></span>
		                                                    <div class="info-box-content">
		                                                        <span class="info-box-text"><?php echo $Approver; ?></span>
		                                                        <span class="info-box-number"><?php echo $EMPN_2; ?></span>
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
		                                        if($APPROVER_3 != '')
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
		                                            }
		                                            
		                                            if($resCAPPH_2 < 3)
		                                            {
		                                                $Approver	= $Awaiting;
		                                                $boxCol_3	= "yellow";
		                                                $class		= "glyphicon glyphicon-info-sign";
		                                            }
		                                        ?>
		                                            <div class="col-md-3">
		                                                <div class="info-box bg-<?php echo $boxCol_3; ?>">
		                                                    <span class="info-box-icon"><i class="<?php echo $class; ?>"></i></span>
		                                                    <div class="info-box-content">
		                                                        <span class="info-box-text"><?php echo $Approver; ?></span>
		                                                        <span class="info-box-number"><?php echo $EMPN_3; ?></span>
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
		                                        if($APPROVER_4 != '')
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
		                                            }
		                                            
		                                            if($resCAPPH_3 < 4)
		                                            {
		                                                $Approver	= $Awaiting;
		                                                $boxCol_4	= "yellow";
		                                                $class		= "glyphicon glyphicon-info-sign";
		                                            }
		                                        ?>
		                                            <div class="col-md-3">
		                                                <div class="info-box bg-<?php echo $boxCol_4; ?>">
		                                                    <span class="info-box-icon"><i class="<?php echo $class; ?>"></i></span>
		                                                    <div class="info-box-content">
		                                                        <span class="info-box-text"><?php echo $Approver; ?></span>
		                                                        <span class="info-box-number"><?php echo $EMPN_4; ?></span>
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
		                                        if($APPROVER_5 != '')
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
		                                            }
		                                            
		                                            if($resCAPPH_4 < 5)
		                                            {
		                                                $Approver	= $Awaiting;
		                                                $boxCol_5	= "yellow";
		                                                $class		= "glyphicon glyphicon-info-sign";
		                                            }
		                                        ?>
		                                            <div class="col-md-3">
		                                                <div class="info-box bg-<?php echo $boxCol_5; ?>">
		                                                    <span class="info-box-icon"><i class="<?php echo $class; ?>"></i></span>
		                                                    <div class="info-box-content">
		                                                        <span class="info-box-text"><?php echo $Approver; ?></span>
		                                                        <span class="info-box-number"><?php echo $EMPN_5; ?></span>
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
		                    </form>
		                </div>
		            </div>
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

    $(document).bind('keydown keyup', function(e) {
	    if(e.which === 116) {
	       console.log('blocked');
	       return false;
	    }
	    if(e.which === 82 && e.ctrlKey) {
	       console.log('blocked');
	       return false;
	    }
	});

	$('#frm').validate({
    	submitHandler: function(form)
    	{
    		AMD_STAT	= document.getElementById('AMD_STAT').value;
			if(AMD_STAT == 0)
			{
				swal('<?php echo $alert8; ?>');
				document.getElementById('AMD_STAT').focus();
				return false;
			}
			
			if(AMD_STAT == 4)
			{
				AMD_MEMO		= document.getElementById('AMD_MEMO').value;
				if(AMD_MEMO == '')
				{
					swal('<?php echo $alert9; ?>');
					document.getElementById('AMD_MEMO').focus();
					return false;
				}
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
</script>

<script>
	var decFormat		= 2;

	function add_header(strItem) 
	{
		arrItem = strItem.split('|');
		SI_CODE 	= arrItem[0];
		SI_VALUE 	= arrItem[1];
		SI_APPVAL 	= arrItem[2];
		
		var decFormat	= document.getElementById('decFormat').value;
		
		document.getElementById('AMD_REFNO').value 		= SI_CODE;
		document.getElementById('AMD_REFNOX').value 	= SI_CODE;
		document.getElementById('AMD_REFNOAM').value 	= SI_APPVAL;
		document.getElementById('AMD_REFNOAMX').value 	= doDecimalFormat(RoundNDecimal(parseFloat((SI_APPVAL)),decFormat));
		
	}
	
	function add_item(strItem) 
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
		JOBCODE 		= arrItem[2];
		PRJCODE 		= arrItem[3];
		ITM_CODE 		= arrItem[4];
		ITM_NAME 		= arrItem[5];
		ITM_SN			= arrItem[6];
		ITM_UNIT 		= arrItem[7];
		ITM_PRICE 		= arrItem[8];
		ITM_VOLM 		= arrItem[9];
		ITM_BUDGQTY		= parseFloat(ITM_VOLM);
		ITM_STOCK 		= arrItem[10];
		ITM_USED 		= arrItem[11];
		itemConvertion	= arrItem[12];
		TotPrice		= arrItem[13];
		tempTotMax		= arrItem[14];
		TOT_USEBUDG		= arrItem[15];
		ITM_BUDG		= arrItem[16];
		TOT_USEDQTY		= arrItem[17];
		ITM_GROUP		= arrItem[18];
		AMD_CLASS		= 0;
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
		objTD.align = "center";
		objTD.noWrap = true;
		objTD.innerHTML = '<a href="#" onClick="deleteRow('+intIndex+')" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a><input type="hidden" id="chk" name="chk" value="'+intIndex+'" width="10" size="15" readonly class="form-control" style="max-width:300px;">';
		
		// Item Code
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'right';
		objTD.innerHTML = ''+ITM_CODE+'<input type="hidden" id="data'+intIndex+'ITM_GROUP" name="data['+intIndex+'][ITM_GROUP]" value="'+ITM_GROUP+'" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'ITM_CODE" name="data['+intIndex+'][ITM_CODE]" value="'+ITM_CODE+'" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'AMD_NUM" name="data['+intIndex+'][AMD_NUM]" value="'+AMD_NUMx+'" class="form-control" style="max-width:300px;"><input type="hidden" name="data['+intIndex+'][JOBCODEID]" id="data'+intIndex+'JOBCODEID" value="'+JOBCODEID+'" class="form-control" ><input type="hidden" name="totalrow" id="totalrow" value="'+intIndex+'">';
		
		// Item Name
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.noWrap = true;
		objTD.innerHTML = ''+ITM_NAME+'<input type="hidden" id="data'+intIndex+'JOBDESC" name="data['+intIndex+'][JOBDESC]" value="'+ITM_NAME+'" class="form-control" style="max-width:300px;">';
		
		// Item Budget
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" class="form-control" style="min-width:100px; max-width:300px; text-align:right" name="ITM_BUDGQTYx'+intIndex+'" id="ITM_BUDGQTYx'+intIndex+'" value="'+ITM_BUDGQTY+'" disabled >';
		
		// Item Used
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" class="form-control" style="min-width:110px; max-width:300px; text-align:right" name="TOT_USEDQTY'+intIndex+'" id="TOT_USEDQTY'+intIndex+'" value="'+TOT_USEDQTY+'" disabled >';
		
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
		objTD.innerHTML = '<input type="text" name="AMD_PRICE'+intIndex+'" id="AMD_PRICE'+intIndex+'" value="'+ITM_PRICE+'" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgPrice(this,'+intIndex+');" ><input type="hidden" name="data['+intIndex+'][AMD_PRICE]" id="data'+intIndex+'AMD_PRICE" value="'+ITM_PRICE+'" class="form-control" style="max-width:300px;" ><input type="hidden" name="data['+intIndex+'][AMD_TOTAL]" id="data'+intIndex+'AMD_TOTAL" value="0" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" >';
		
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
	
	function chgPrice(thisVal, row)
	{
		var decFormat	= document.getElementById('decFormat').value;
		
		var AMD_CLASS	= document.getElementById('data'+row+'AMD_CLASS').value;
		
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
		if(AMD_CLASS == 1)
		{
			var AMD_TOTAL	= 1 * parseFloat(AMD_PRICE);
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
	
	function countGTotal(row)
	{
		decFormat	= document.getElementById('decFormat').value;
		totalrow	= document.getElementById('totalrow').value;
		AMD_AMOUNT	= 0;
		for(i=1; i<=totalrow; i++)
		{
			var AMD_TOTAL 	= document.getElementById('data'+i+'AMD_TOTAL').value;
			AMD_AMOUNT		= parseFloat(AMD_AMOUNT) + parseFloat(AMD_TOTAL);
		}
		document.getElementById('AMD_AMOUNT').value 	= parseFloat(AMD_AMOUNT);
		document.getElementById('AMD_AMOUNTX').value 	= doDecimalFormat(RoundNDecimal(parseFloat((AMD_AMOUNT)),decFormat));
		
		var AMD_CATEG	= document.getElementById('AMD_CATEG').value;
		if(AMD_CATEG == 'SI' || AMD_CATEG == 'SINJ')
		{
			AMD_REFNOAM	= document.getElementById('AMD_REFNOAM').value;
			if(AMD_AMOUNT > AMD_REFNOAM)
			{
				swal('<?php echo $alert2; ?>');
				document.getElementById('AMD_VOLM'+row).value = 0;
				document.getElementById('data'+row+'AMD_VOLM').value = 0;
				document.getElementById('AMD_VOLM'+row).focus();
				return false;
			}
		}
	}
	
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