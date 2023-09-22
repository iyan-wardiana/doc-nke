<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 27 Juli 2017
 * File Name	= update_maillist.php
 * Location		= -
*/
date_default_timezone_set("Asia/Jakarta");
$appName 	= $this->session->userdata('appName');

// $this->load->view('template/head');

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
$Emp_DeptCode	= $this->session->userdata['Emp_DeptCode'];

$this->db->select('Display_Rows,decFormat,APPLEV');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
	$APPLEV = $row->APPLEV;
endforeach;

$MDEPT_CODE1	= 'JXXX';
$sqlMDC	= "SELECT MDEPT_CODE, Pos_Code FROM tbl_employee WHERE Emp_ID = '$DefEmp_ID'";
$sqlMDC	= $this->db->query($sqlMDC)->result();
foreach($sqlMDC as $rowMDC) :
	$MDEPT_CODE1= $rowMDC->MDEPT_CODE;
	$POSS_CODE  = $rowMDC->Pos_Code;
endforeach;
if($MDEPT_CODE1 == '')
	$MDEPT_CODE1 = 'JXXX';

$MDEPT_CODE1	= 'JXXX';
$sqlMDC	= "SELECT A.DEMP_EMPID, A.DEMP_DEPCODE
            FROM tbl_mail_dept_emp A
            WHERE DEMP_EMPID = '$DefEmp_ID'";
$sqlMDC	= $this->db->query($sqlMDC)->result();
foreach($sqlMDC as $rowMDC) :
    $MDEPT_CODE1= $rowMDC->DEMP_DEPCODE;
endforeach;
if($MDEPT_CODE1 == '')
    $MDEPT_CODE1 = 'JXXX';

$MB_M		= date('m');
$MB_M1		= (int)$MB_M;
$MB_Y		= date('Y');
$sqlMBC		= "tbl_mailbox WHERE MB_M = '$MB_M1' AND MB_Y = '$MB_Y' AND MB_ISRUNNO = 'Y' AND MB_STATUS != '3'";
$resMBC		= $this->db->count_all($sqlMBC);

$resMBCN	= $resMBC + 1;
$len = strlen($resMBCN);
$nol		= '';	
$PattLength	= 4;
if($PattLength==2)
{
	if($len==1) $nol="0";
}
elseif($PattLength==3)
{if($len==1) $nol="00";else if($len==2) $nol="0";
}
elseif($PattLength==4)
{if($len==1) $nol="000";else if($len==2) $nol="00";else if($len==3) $nol="0";
}
elseif($PattLength==5)
{if($len==1) $nol="0000";else if($len==2) $nol="000";else if($len==3) $nol="00";else if($len==4) $nol="0";
}
elseif($PattLength==6)
{if($len==1) $nol="00000";else if($len==2) $nol="0000";else if($len==3) $nol="000";else if($len==4) $nol="00";else if($len==5) $nol="0";
}
elseif($PattLength==7)
{if($len==1) $nol="000000";else if($len==2) $nol="00000";else if($len==3) $nol="0000";else if($len==4) $nol="000";else if($len==5) $nol="00";else if($len==6) $nol="0";
}
$MAIL_STEP	= $nol.$resMBCN;

$MB_ID 			= $default['MB_ID'];
$MB_NO 			= $default['MB_NO'];
$MB_CODE 		= $default['MB_CODE'];
$MB_CLASS 		= $default['MB_CLASS'];
$MB_TYPE 		= $default['MB_TYPE'];
$MB_TYPE_X 		= $default['MB_TYPE_X'];
$MB_DEPT 		= $default['MB_DEPT'];
$MB_TO_ID 	    = $default['MB_TO_ID'];
$MB_TO 	        = $default['MB_TO'];
$MB_SUBJECT 	= $default['MB_SUBJECT'];
$MB_STATUS		= $default['MB_STATUS'];
$MB_MESSAGE 	= $default['MB_MESSAGE'];

if (isset($_POST['submitSrch']))
{
	$MB_CLASS	= $_POST['MB_CLASS_A'];
	$MB_TYPE	= $_POST['MB_TYPE_A'];
	$MB_DEPT	= $_POST['MB_DEPT_A'];
    $MDEPT_CODE1= $MB_DEPT1;
}

$NO_01		= "J";			// HOLD
$NO_02		= $MB_DEPT;		// DEPARTMENT
$NO_03		= $MB_CLASS;
$NO_04		= $MAIL_STEP;
$NO_05		= "NKE";
$NO_06		= date('m');
$NO_07		= date('y');
$MAIL_NO	= "$NO_02-$NO_03$NO_04/$NO_05/$NO_06-$NO_07";
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

        $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'css' AND isAct IN (1,3,4) AND cssjs_vers IN ('$vers', 'All')";
        $rescss = $this->db->query($sqlcss)->result();
        foreach($rescss as $rowcss) :
            $cssjs_lnk  = $rowcss->cssjs_lnk;
            ?>
                <link rel="stylesheet" href="<?php echo base_url($cssjs_lnk) ?>">
            <?php
        endforeach;

        $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'jss' AND isAct IN (1,3,4) AND cssjs_vers IN ('$vers', 'All')";
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

<script src="<?php echo base_url('assets/js/jquery-1.10.1.min.js') ?>" type="text/javascript"></script>
<?php
	// $this->load->view('template/topbar');
	// $this->load->view('template/sidebar');
	
	$ISREAD 	= $this->session->userdata['ISREAD'];
    $ISCREATE 	= $this->session->userdata['ISCREATE'];
    $ISAPPROVE 	= $this->session->userdata['ISAPPROVE'];
    $ISDWONL 	= $this->session->userdata['ISDWONL'];
    $ISDELETE = $this->session->userdata['ISDELETE'];
    $LangID 	= $this->session->userdata['LangID'];
	$PRJCODE 	= $this->session->userdata['SessTempProject'];

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
			if($TranslCode == 'WONo')$WONo = $LangTransl;
			if($TranslCode == 'Category')$Category = $LangTransl;
			if($TranslCode == 'Type')$Type = $LangTransl;
			if($TranslCode == 'RequestCode')$RequestCode = $LangTransl;
			if($TranslCode == 'WOCode')$WOCode = $LangTransl;
			if($TranslCode == 'Code')$Code = $LangTransl;
			if($TranslCode == 'Notes')$Notes = $LangTransl;
			if($TranslCode == 'Date')$Date = $LangTransl;
			if($TranslCode == 'Project')$Project = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'AlertRevisionorReject')$AlertRevisionorReject = $LangTransl;
			if($TranslCode == 'PaymentType')$PaymentType = $LangTransl;
			if($TranslCode == 'PaymentTerm')$PaymentTerm = $LangTransl;
			if($TranslCode == 'New')$New = $LangTransl;
			if($TranslCode == 'Confirm')$Confirm = $LangTransl;
			if($TranslCode == 'Close')$Close = $LangTransl;
			if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
			if($TranslCode == 'ItemName')$ItemName = $LangTransl;
			if($TranslCode == 'BudgetQty')$BudgetQty = $LangTransl;
			if($TranslCode == 'Planning')$Planning = $LangTransl;
			if($TranslCode == 'Requested')$Requested = $LangTransl;
			if($TranslCode == 'RequestNow')$RequestNow = $LangTransl;
			if($TranslCode == 'Used')$Used = $LangTransl;
			if($TranslCode == 'Quantity')$Quantity = $LangTransl;
			if($TranslCode == 'Unit')$Unit = $LangTransl;
			if($TranslCode == 'Primary')$Primary = $LangTransl;
			if($TranslCode == 'Secondary')$Secondary = $LangTransl;
			if($TranslCode == 'Amount')$Amount = $LangTransl;
			if($TranslCode == 'SelectItem')$SelectItem = $LangTransl;
			if($TranslCode == 'JobName')$JobName = $LangTransl;
			if($TranslCode == 'ItemQty')$ItemQty = $LangTransl;
			if($TranslCode == 'SPKDate')$SPKDate = $LangTransl;
			if($TranslCode == 'StartDate')$StartDate = $LangTransl;
			if($TranslCode == 'EndDate')$EndDate = $LangTransl;
			if($TranslCode == 'Approval')$Approval = $LangTransl;
			if($TranslCode == 'Approved')$Approved = $LangTransl;
			if($TranslCode == 'NotYetApproved')$NotYetApproved = $LangTransl;
			if($TranslCode == 'Awaiting')$Awaiting = $LangTransl;
			if($TranslCode == 'Price')$Price = $LangTransl;
			if($TranslCode == 'Supplier')$Supplier = $LangTransl;
			if($TranslCode == 'Tax')$Tax = $LangTransl;
			if($TranslCode == 'Search')$Search = $LangTransl;
			if($TranslCode == 'QuotNo')$QuotNo = $LangTransl;
			if($TranslCode == 'NegotNo')$NegotNo = $LangTransl;
			if($TranslCode == 'Reason')$Reason = $LangTransl;
			if($TranslCode == 'SupplierName')$SupplierName = $LangTransl;
			if($TranslCode == 'ItemList')$ItemList = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'StockQuantity')$StockQuantity = $LangTransl;
			if($TranslCode == 'Select')$Select = $LangTransl;
			if($TranslCode == 'itmSub')$itmSub = $LangTransl;
			if($TranslCode == 'Wage')$Wage = $LangTransl;
			if($TranslCode == 'Account')$Account = $LangTransl;

			if($TranslCode == 'ApproverNotes')$ApproverNotes = $LangTransl;
			if($TranslCode == 'reviseNotes')$reviseNotes = $LangTransl;
			if($TranslCode == 'Approve')$Approve = $LangTransl;
			if($TranslCode == 'Approver')$Approver = $LangTransl;
			if($TranslCode == 'Approved')$Approved = $LangTransl;
			if($TranslCode == 'Approval')$Approval = $LangTransl;
			if($TranslCode == 'NotYetApproved')$NotYetApproved = $LangTransl;
			if($TranslCode == 'InOthSett')$InOthSett = $LangTransl;
			if($TranslCode == 'JobList')$JobList = $LangTransl;
			if($TranslCode == 'JobNm')$JobNm = $LangTransl;
			if($TranslCode == 'Remain')$Remain = $LangTransl;
			if($TranslCode == 'Cancel')$Cancel = $LangTransl;
			if($TranslCode == 'sureVoid')$sureVoid = $LangTransl;
			if($TranslCode == 'dokLam')$dokLam = $LangTransl;
			if($TranslCode == 'usedItm')$usedItm = $LangTransl;
		endforeach;

    // START : APPROVE PROCEDURE
			if($APPLEV == 'HO')
            $PRJCODE_LEV	= $this->data['PRJCODE_HO'];
        else
            $PRJCODE_LEV	= $this->data['PRJCODE'];

        if(count($PRJCODE) == 1) $PRJCODE_LEV = $PRJCODE['sessTempProj'];

        $PRJCODE    = $PRJCODE_LEV;

        // START
            // step approval mailbox berdasarkan atasan masing-masing, jika bag. departemen atau direksi maka salah satu direksi harus bisa approve
            // update tgl. 05-Sept-2023
            $s_pos01    = "SELECT POSS_CODE, POSS_NAME, POSS_LEVEL, POSS_PARENT FROM tbl_position_str WHERE POSS_CODE = '$POSS_CODE'";
            $r_pos01    = $this->db->query($s_pos01);
            if($r_pos01->num_rows() > 0)
            {
                foreach($r_pos01->result() as $rw_pos01):
                    $POSS_CODE      = $rw_pos01->POSS_CODE;
                    $POSS_NAME      = $rw_pos01->POSS_NAME;
                    $POSS_LEVEL     = $rw_pos01->POSS_LEVEL;
                    $POSS_PARENT    = $rw_pos01->POSS_PARENT;

                    if($POSS_LEVEL == 'DEPT' || $POSS_LEVEL == 'BOD')
                    {
                        $POSS_LEVEL     = 'BOD';
                    }
                endforeach;
            }
        // END
        
        // DocNumber - PR_VALUE
        $EMPN_1 	= "";
        $EMPN_2 	= "";
        $EMPN_3 	= "";
        $EMPN_4		= "";
        $EMPN_5 	= "";

        $IS_LAST	= 0;
        $APP_LEVEL	= 0;
        $APPROVER_1	= '';
        $APPROVER_2	= '';
        $APPROVER_3	= '';
        $APPROVER_4	= '';
        $APPROVER_5	= '';	
        $disableAll	= 1;
        $DOCAPP_TYPE= 1;
        $sqlCAPP	= "tbl_docstepapp WHERE MENU_CODE = '$MenuApp' AND PRJCODE = '$PRJCODE_LEV' AND POSLEVEL = '$POSS_LEVEL'";
        $resCAPP	= $this->db->count_all($sqlCAPP);
        if($resCAPP > 0)
        {
            $sqlAPP	= "SELECT * FROM tbl_docstepapp WHERE MENU_CODE = '$MenuApp'
                        AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE_LEV') AND POSLEVEL = '$POSS_LEVEL'";
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
                            AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE_LEV') AND POSLEVEL = '$POSS_LEVEL'";
            $resAPPT	= $this->db->query($sqlAPP)->result();
            foreach($resAPPT as $rowAPPT) :
                $DOCAPP_TYPE	= $rowAPPT->DOCAPP_TYPE;
            endforeach;
        }
        
        $sqlSTEPAPP	= "tbl_docstepapp_det WHERE MENU_CODE = '$MenuApp' AND APPROVER_1 = '$DefEmp_ID'
                        AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE_LEV') AND POSLEVEL = '$POSS_LEVEL'";
        $resSTEPAPP	= $this->db->count_all($sqlSTEPAPP);
        
        if($resSTEPAPP > 0)
        {
            $canApprove	= 1;
            $APPLIMIT_1	= 0;
            
            $sqlAPP	= "SELECT APPLIMIT_1, APP_STEP, MAX_STEP FROM tbl_docstepapp_det WHERE MENU_CODE = '$MenuApp'
                        AND APPROVER_1 = '$DefEmp_ID' AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE_LEV') AND POSLEVEL = '$POSS_LEVEL'";
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
            $APPROVE_AMOUNT 	= $WO_VALUE;
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
        Mailbox
        <small><?php echo $countInbox; ?> messages</small>
    </h1>
    <?php /*?><ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Mailbox</li>
    </ol><?php */?>
</section>
<?php
	$secInbox_Mail	= site_url('c_mailbox/c_mailbox/inbox_mail/?id='.$this->url_encryption_helper->encode_url($DefEmp_ID));
	$secSend_Mail	= site_url('c_mailbox/c_mailbox/sent_mail/?id='.$this->url_encryption_helper->encode_url($DefEmp_ID));
	$secDraft_Mail	= site_url('c_mailbox/c_mailbox/draft_mail/?id='.$this->url_encryption_helper->encode_url($DefEmp_ID));
	$secTrash_Mail	= site_url('c_mailbox/c_mailbox/trash_mail/?id='.$this->url_encryption_helper->encode_url($DefEmp_ID));
	$secWrite_Mail	= site_url('c_mailbox/c_mailbox/write_mail/?id='.$this->url_encryption_helper->encode_url($DefEmp_ID));
?>
<!-- Main content -->
<section class="content">
    <div class="row">
		<div class="col-md-3">
            <a href="<?php echo $secInbox_Mail; ?>" class="btn btn-primary btn-block margin-bottom">Back to Inbox</a>
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">Folders</h3>
                    <div class="box-tools">
            			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            			</button>
            		</div>
            	</div>
            	<div class="box-body no-padding">
                    <ul class="nav nav-pills nav-stacked">
                        <li>
                        	<a href="<?php echo $secInbox_Mail; ?>"><i class="fa fa-inbox"></i> Inbox
                        	<span class="label label-primary pull-right"><?php echo $countInbox; ?></span></a>
						</li>
                        <li>
                        	<a href="<?php echo $secSend_Mail; ?>"><i class="fa fa-envelope-o"></i> Sent
                        	<span class="label label-warning pull-right"><?php echo $countSent; ?></span></a>
                        </li>
                        <li class="active">
                        	<a href="<?php echo $secDraft_Mail; ?>"><i class="fa fa-file-text-o"></i> Drafts
                            <span class="label label-warning pull-right"><?php echo $countDraft; ?></span></a>
						</li>
                        <li style="display:none">
                        	<a href="#"><i class="fa fa-filter"></i> Junk
                            <span class="label label-warning pull-right"><?php echo $countJunk; ?></span></a>
                        </li>
                        <li>
                        	<a href="<?php echo $secTrash_Mail; ?>"><i class="fa fa-trash-o"></i> Trash
                            <span class="label label-warning pull-right"><?php echo $countTrash; ?></span></a>
                        </li>
                    </ul>
            	</div>
			</div>
          	<div class="box box-solid" style="display:none">
                <div class="box-header with-border">
                	<h3 class="box-title">Chat Messanger</h3>
                	<div class="box-tools">
                		<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                		</button>
                	</div>
                </div>
            	<div class="box-body no-padding">
                    <ul class="nav nav-pills nav-stacked">
                        <li><a href="#"><i class="fa fa-circle-o text-red"></i> Important</a></li>
                        <li><a href="#"><i class="fa fa-circle-o text-yellow"></i> Promotions</a></li>
                        <li><a href="#"><i class="fa fa-circle-o text-light-blue"></i> Social</a></li>
                    </ul>
            	</div>
          	</div>
		</div>
		<div class="col-md-9">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Write  New Message : <?php echo $MAIL_NO; ?></h3>
                </div>
                <form name="frm" method="post" action="">
                    <input type="hidden" name="MB_CLASS_A" id="MB_CLASS_A" class="textbox" value="<?php echo $MB_CLASS; ?>" />
                    <input type="hidden" name="MB_TYPE_A" id="MB_TYPE_A" class="textbox" value="<?php echo $MB_TYPE; ?>" />
                    <input type="hidden" name="MB_DEPT_A" id="MB_DEPT_A" class="textbox" value="<?php echo $MDEPT_CODE1; ?>" />
                    <input type="submit" class="button_css" name="submitSrch" id="submitSrch" value=" search " style="display:none" />
                </form>
                <form name="frm1" method="post" action="<?php echo $form_action; ?>" enctype="multipart/form-data" onSubmit="return checkData();">
                    <input type="hidden" name="Emp_ID" id="Emp_ID" class="textbox" value="<?php echo $DefEmp_ID; ?>" />
                    <input type="hidden" name="MB_DEPT" id="MB_DEPT" class="textbox" value="<?php echo $MDEPT_CODE1; ?>" />
                    <input type="text" name="MB_STATUS" id="MB_STATUS" class="textbox" value="<?php echo $MB_STATUS; ?>" />
                    <input type="hidden" name="MB_PATTNO" id="MB_PATTNO" class="textbox" value="<?php echo $resMBCN; ?>" />
                    <input type="text" name="MB_NO" id="MB_NO" class="textbox" value="<?php echo $MB_NO; ?>" />
                    <input type="text" name="MB_CODE" id="MB_CODE" class="textbox" value="<?php echo $MAIL_NO; ?>" />
                    <div class="box-body">
                        <div class="form-group">
                            <select name="MB_CLASS" id="MB_CLASS" class="form-control" onChange="ShowDocSelect(1);">
                                <option value="M" <?php if($MB_CLASS == 'M') { ?> selected <?php } ?>> Memo </option>
                                <option value="S" <?php if($MB_CLASS == 'S') { ?> selected <?php } ?>> Surat </option>
                            </select>
                        </div>
                        <div class="form-group" style="display: none;">
                            <select name="MB_TYPE" id="MB_TYPE" class="form-control" placeholder="&nbsp;&nbsp;&nbsp;Mail Type" style="width: 100%;" onChange="ShowDocSelect_xx(this.value);">
                                <?php
                                    $sqlMTyp	= "SELECT MT_CODE, MT_DESC FROM tbl_mail_type ORDER BY MT_DESC";
                                    $sqlMTyp	= $this->db->query($sqlMTyp)->result();
                                    foreach($sqlMTyp as $row) :
                                        $MT_CODE1	= $row->MT_CODE;
                                        $MT_DESC1	= $row->MT_DESC;
                                        ?>
                                            <option value="<?php echo $MT_CODE1; ?>" <?php if($MT_CODE1 == $MB_TYPE) { ?> selected <?php } ?>>
                                                <?php echo "$MT_DESC1"; ?>
                                            </option>
                                        <?php
                                    endforeach;
                                ?>
                            </select>
                        </div>
                        <div class="form-group" id="div_mail_type_x" <?php if($MB_TYPE != "OTHER") { ?> style="display:none" <?php } ?>>
                            <input type="text" class="form-control" name="MB_TYPE_X" id="MB_TYPE_X" value="<?php echo $MB_TYPE_X; ?>">
                        </div>
                        <div class="form-group">
                            <select name="MB_DEPT1" id="MB_DEPT1" class="form-control" disabled>
                                <?php
									if($MDEPT_CODE1 != 'JXXX')
									{
										$sqlDept	= "SELECT A.DEMP_DEPCODE,
															B.MDEPT_CODE, B.MDEPT_DESC, B.MDEPT_POSIT, B.MDEPT_NAME, B.MDEPT_POSLEV
														FROM tbl_mail_dept_emp A
															INNER JOIN tbl_mail_dept B ON A.DEMP_DEPCODE = B.MDEPT_CODE
														WHERE A.DEMP_EMPID = '$DefEmp_ID'
										 				ORDER BY A.DEMP_DEPCODE";
										$sqlDept	= $this->db->query($sqlDept)->result();
										foreach($sqlDept as $rowDept) :
											$DEMP_DEPCODE	= $rowDept->DEMP_DEPCODE;
											$MDEPT_CODE		= $rowDept->MDEPT_CODE;
											$MDEPT_DESC		= $rowDept->MDEPT_DESC;
											$MDEPT_POSIT	= $rowDept->MDEPT_POSIT;
											$MDEPT_NAME		= $rowDept->MDEPT_NAME;
											$MDEPT_POSLEV	= $rowDept->MDEPT_POSLEV;
											?>
												<option value="<?php echo "$MDEPT_CODE"; ?>" <?php if($MDEPT_CODE == $MDEPT_CODE1) { ?> selected <?php } ?>>
													<?php //echo "$MDEPT_CODE - $MDEPT_POSIT / $MDEPT_NAME"; ?>
                                                    <?php echo "$MDEPT_CODE - $MDEPT_DESC / $MDEPT_NAME"; ?>
												</option>
											<?php
										endforeach;
									}
									else
									{
										?>
                                            <option value="JXXX" selected>
                                                NON-MANAGEMENT
                                            </option>
                                        <?php
									}
                                ?>
                            </select>                           
                        </div>
                        <div class="form-group">
                            <select name="MB_TO[]" id="MB_TO" class="form-control select2" multiple="multiple" data-placeholder="&nbsp;&nbsp;&nbsp;Mail To" style="width: 100%;">
                                <?php
                                    $sqlEmp	= "SELECT Emp_ID, First_Name, Last_Name, Email
												FROM tbl_employee WHERE Emp_ID != '$DefEmp_ID' ORDER BY First_Name";
                                    $sqlEmp	= $this->db->query($sqlEmp)->result();
                                    foreach($sqlEmp as $row) :
                                        $Emp_ID		= $row->Emp_ID;
                                        $First_Name	= $row->First_Name;
                                        $Last_Name	= $row->Last_Name;
                                        $Email		= $row->Email;

                                        $Emp_IDx    = '';
                                        for($i = 0; $i < count($MB_TO); $i++) {
                                            $MB_TOx = $MB_TO[$i];
                                            if($Emp_ID == $MB_TOx) $Emp_IDx = $MB_TOx;
                                        }
                                        ?>
                                            <option value="<?php echo "$Emp_ID|$Email"; ?>" <?php if($Emp_ID == $Emp_IDx) echo "selected"; ?>>
                                                <?php echo "$First_Name $Last_Name - $Email"; ?>
                                            </option>
                                        <?php
                                    endforeach;
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <select name="MB_TOG[]" id="MB_TOG" class="form-control select2" multiple="multiple" data-placeholder="&nbsp;&nbsp;&nbsp;Mail To Group" style="width: 100%;">
                                <?php
                                    $sqlMG	= "SELECT MG_CODE, MG_NAME
												FROM tbl_mailgroup_header ORDER BY MG_NAME ASC";
                                    $sqlMG	= $this->db->query($sqlMG)->result();
                                    foreach($sqlMG as $rowMG) :
                                        $MG_CODE	= $rowMG->MG_CODE;
                                        $MG_NAME	= $rowMG->MG_NAME;
                                        ?>
                                            <option value="<?php echo "$MG_CODE"; ?>">
                                                <?php echo "$MG_NAME"; ?>
                                            </option>
                                        <?php
                                    endforeach;
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <input name="MB_SUBJECT" id="MB_SUBJECT" class="form-control" placeholder="&nbsp;Subject:" value="<?php echo $MB_SUBJECT; ?>">
                        </div>
                        <div class="form-group">
                            <textarea name="MB_MESSAGE" id="compose-textarea" class="form-control" style="height: 300px">
                                <?php
									echo $MB_MESSAGE;
								?>
                            </textarea>
                        </div>
                        <div class="form-group">
                            <div class="btn btn-default btn-file">
                                <i class="fa fa-paperclip"></i> Attachment 1
                                <input type="file" name="attachment1" id="attachment1">
                            </div>
                            <div class="btn btn-default btn-file">
                                <i class="fa fa-paperclip"></i> Attachment 2
                                <input type="file" name="attachment2" id="attachment2">
                            </div>
                            <div class="btn btn-default btn-file">
                                <i class="fa fa-paperclip"></i> Attachment 3
                                <input type="file" name="attachment3" id="attachment3">
                            </div>
                            <div class="btn btn-default btn-file">
                                <i class="fa fa-paperclip"></i> Attachment 4
                                <input type="file" name="attachment4" id="attachment4">
                            </div>
                            <div class="btn btn-default btn-file">
                                <i class="fa fa-paperclip"></i> Attachment 5
                                <input type="file" name="attachment5" id="attachment5">
                            </div>
                            <p class="help-block">Max. 32MB</p>
                        </div>
                    </div>
                    <div class="box-footer">
                        <div class="pull-right">
                            <button type="button" class="btn btn-success" onClick="MailStatus(3)">
                                <i class="fa fa-pencil"></i> Draft
                            </button>&nbsp;
                            <button type="reset" class="btn btn-danger">
                                <i class="fa fa-times"></i> Reset
                            </button>&nbsp;
                        </div>
                        <button type="submit" class="btn btn-primary" name="submitSent" id="submitSent">
                         <i class="fa fa-envelope-o"></i> Send
                        </button>
                    </div>
                </form>
            </div>
        </div>
	</div>
    <?php
		$act_lnk = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		// if($DefEmp_ID == 'D15040004221')
			echo "<font size='1'><i>$act_lnk</i></font>";
	?>
</section>

<script>
	function ShowDocSelect_xx(thisvalue)
	{
		if(thisvalue == 'OTHER')
		{
			document.getElementById('div_mail_type_x').style.display = '';
		}
		else
		{
			document.getElementById('div_mail_type_x').style.display = 'none';
		}
	}
	
	function checkData()
	{
		var MB_TYPE	= document.getElementById('MB_TYPE').value;
		if(MB_TYPE == '')
		{
			alert('Please select mail type.');
			document.getElementById('MB_TYPE').focus();
			return false;
		}
		else if(MB_TYPE == 'OTHER')
		{
			var MB_TYPE_X	= document.getElementById('MB_TYPE_X').value;
			if(MB_TYPE_X == '')
			{
				alert('Mail Type can not be empty.');
				document.getElementById('MB_TYPE_X').focus();
				return false;
			}
		}
			
		var ZeroRec	= 0;
		var MB_TO	= document.getElementById('MB_TO').value;
			if(MB_TO != '')
				var ZeroRec	= 1;			
		var MB_TOG	= document.getElementById('MB_TOG').value;
			if(MB_TOG != '')
				var ZeroRec	= 1;
		if(ZeroRec == 0)
		{
			alert('Please input email recipient.');
			document.getElementById('MB_TO').focus();
			return false;
		}
		
		var MB_SUBJECT	= document.getElementById('MB_SUBJECT').value;
		if(MB_SUBJECT == '')
		{
			alert('The Mail Subject can not be empty.');
			document.getElementById('MB_SUBJECT').focus();
			return false;
		}
	}
	
	function ShowDocSelect(thisVal)
	{
		document.getElementById('MB_CLASS_A').value	= document.getElementById('MB_CLASS').value;
		document.getElementById('MB_TYPE_A').value	= document.getElementById('MB_TYPE').value;
		document.getElementById('MB_DEPT_A').value	= document.getElementById('MB_DEPT').value;
		document.frm.submitSrch.click();
	}
	
	function MailStatus(thisVal)
	{
		document.getElementById('MB_STATUS').value	= thisVal;
		document.frm1.submitSent.click();
	}
</script>
<script>
    $(function () {
    //Add text editor
       $("#compose-textarea").wysihtml5();
    });
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
        $('#datepicker').datepicker({
            autoclose: true,
            startDate: '-3d',
            endDate: '+0d'
        });

        //Date picker
        $('#datepicker1').datepicker({
          autoclose: true,
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
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js';?>"></script>
</body>
</html>