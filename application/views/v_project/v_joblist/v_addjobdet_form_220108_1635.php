<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 20 Desember 2021
	* File Name		= v_addjobdet_form.php
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
$this->db->select('Display_Rows,decFormat,APPLEV');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
	$APPLEV = $row->APPLEV;
endforeach;
$decFormat		= 2; 

$sql = "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$result = $this->db->query($sql)->result();
foreach($result as $row) :
	$PRJNAME = $row ->PRJNAME;
endforeach;

$currentRow = 0;


$sqlJlD			= "SELECT JOBDESC, JOBUNIT, JOBLEV, JOBVOLM, PRICE, JOBCOST, BOQ_VOLM, BOQ_PRICE, BOQ_JOBCOST
					FROM tbl_joblist WHERE JOBCODEID = '$JOBPARCODE' AND  PRJCODE = '$PRJCODE' LIMIT 1";
$resJlD			= $this->db->query($sqlJlD)->result();
foreach($resJlD as $rowJLD) :
    $JOBDESC 	= $rowJLD->JOBDESC;
    $JOBUNIT 	= $rowJLD->JOBUNIT;
    $JOBLEV 	= $rowJLD->JOBLEV;
    $JOBVOLM 	= $rowJLD->JOBVOLM;
    $JOBPRICE 	= $rowJLD->PRICE;
    $JOBCOST 	= $rowJLD->JOBCOST;
    $BOQ_VOLM 	= $rowJLD->BOQ_VOLM;
    $BOQ_PRICE 	= $rowJLD->BOQ_PRICE;
    $BOQ_JOBCOST= $rowJLD->BOQ_JOBCOST;
endforeach;
if($task == 'add')
{
	$JOB_NUM		= $PRJCODE.".".date('YmdHis');
	$JOB_DATE 		= date('YmdHis');
	$JOB_PARCODE	= $JOBPARCODE;
	$JOB_PARDESC	= $JOBDESC;
	$JOB_UNIT		= $JOBUNIT;
	$JOB_BOQV		= $BOQ_VOLM;
	$JOB_BOQP		= $BOQ_PRICE;
	$JOB_BOQT		= $BOQ_JOBCOST;
	$JOB_RAPV		= $JOBVOLM;
	$JOB_RAPP		= $JOBPRICE;
	$JOB_RAPT		= $JOBCOST;
	$JOB_NOTE		= "";
	$JOB_NOTE		= '';
	$JOB_STAT 		= 1;
}
$JOB_DEV 	= $JOB_BOQT - $JOB_RAPT;
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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

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
		$ISDELETE	= $this->session->userdata['ISDELETE'];
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
			if($TranslCode == 'Code')$Code = $LangTransl;
			if($TranslCode == 'Date')$Date = $LangTransl;
			if($TranslCode == 'JobName')$JobName = $LangTransl;
			if($TranslCode == 'JobParent')$JobParent = $LangTransl;
			if($TranslCode == 'Notes')$Notes = $LangTransl;
			if($TranslCode == 'docNotes')$docNotes = $LangTransl;
			if($TranslCode == 'Awaiting')$Awaiting = $LangTransl;
			if($TranslCode == 'RefNumber')$RefNumber = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'reviseNotes')$reviseNotes = $LangTransl;
			if($TranslCode == 'JobCode')$JobCode = $LangTransl;
			if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
			if($TranslCode == 'ItemName')$ItemName = $LangTransl;
			if($TranslCode == 'Price')$Price = $LangTransl;
			if($TranslCode == 'Unit')$Unit = $LangTransl;
			if($TranslCode == 'Remarks')$Remarks = $LangTransl;
			if($TranslCode == 'BudName')$BudName = $LangTransl;
			if($TranslCode == 'SelectItem')$SelectItem = $LangTransl;
			if($TranslCode == 'ItemList')$ItemList = $LangTransl;
			if($TranslCode == 'BudgetQty')$BudgetQty = $LangTransl;
			if($TranslCode == 'Requested')$Requested = $LangTransl;
			if($TranslCode == 'Ordered')$Ordered = $LangTransl;
			if($TranslCode == 'Remain')$Remain = $LangTransl;
			if($TranslCode == 'Select')$Select = $LangTransl;
			if($TranslCode == 'Close')$Close = $LangTransl;

			if($TranslCode == 'Approve')$Approve = $LangTransl;
			if($TranslCode == 'Approver')$Approver = $LangTransl;
			if($TranslCode == 'Approved')$Approved = $LangTransl;
			if($TranslCode == 'Approval')$Approval = $LangTransl;
			if($TranslCode == 'NotYetApproved')$NotYetApproved = $LangTransl;
			if($TranslCode == 'InOthSett')$InOthSett = $LangTransl;
		endforeach;
		
		if($LangID == 'IND')
		{
			$alert1		= "Silahkan pilih ".$BudName."";
			$alert2		= "Silahkan pilih salah satu item yang akan diminta";
			$alert3 	= "Masukan total Qty pemesanan untuk item yang sudah dipilih.";
			$alert4 	= "Volume item kosong / Volume item salah";
			$alert5 	= "Nilai koefisien kosong / Nilai koefisien salah.";
			$alert6 	= "Harga item kosong / Harga item salah.";
			$sureLock 	= "Anda yakin akan mengunci komponen ini?";
		}
		else
		{
			$alert1		= "Please select ".$BudName."";
			$alert2		= "Please select an item will be requested";
			$alert3 	= "Enter the total order qty for the selected item.";
			$alert4 	= "Item volume is empty / Item volume not correct";
			$alert5 	= "Koefisien value is empty / Koefisien value not correct";
			$alert6 	= "Item Price is empty / Item price not correct";
			$sureLock 	= "Are you sure want to lock this component?";
		}
		
		// START : APPROVE PROCEDURE
			if($APPLEV == 'HO')
				$PRJCODE_LEV	= $this->data['PRJCODE_HO'];
			else
				$PRJCODE_LEV	= $this->data['PRJCODE'];
			
			// JOB_NUM - PR_VALUE
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
				$sqlC_App	= "tbl_approve_hist WHERE AH_CODE = '$JOB_NUM'";
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
				$APPROVE_AMOUNT 	= 10000000000;
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
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
			<h1>
			    <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/purchase_req.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $Add; ?>
			    <small><?php echo "$mnName - $PRJNAME"; ?></small>
			</h1>
		</section>

		<section class="content">
		    <div class="row">
	            <form class="form-horizontal" name="frm" method="post" action="<?php echo $form_action; ?>" enctype="multipart/form-data" onSubmit="return checkForm()">
					<div class="col-md-6">
						<div class="box box-primary">
							<div class="box-header with-border" style="display: none;">
								<i class="fa fa-cloud-upload"></i>
								<h3 class="box-title">&nbsp;</h3>
							</div>
							<div class="box-body">
				                <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
				                <input type="Hidden" name="rowCount" id="rowCount" value="0">
				                <input type="hidden" name="JOB_NUM" id="JOB_NUM" value="<?php echo $JOB_NUM; ?>" />
				                <input type="hidden" name="PRJCODE" id="PRJCODE" value="<?php echo $PRJCODE; ?>" />
				                <input type="hidden" name="JOB_PARCODE" id="JOB_PARCODE" value="<?php echo $JOB_PARCODE; ?>" />
				                <input type="hidden" name="JOB_PARDESC" id="JOB_PARDESC" value="<?php echo $JOB_PARDESC; ?>" />
			                  	<div class="row">
			                    	<div class="col-md-4" style="text-align: center;">
			                    		<?php echo "<strong>$JobName</strong>"; ?>
			                    	</div>
			                    	<div class="col-md-8">
			                    		<div class="row">
				                    		<div class="col-md-12">
					                    		<?php echo "$JOBPARCODE<br>$JOBDESC"; ?>
					                    	</div>
				                    	</div>
			                    		<div class="row">
				                    		<div class="col-md-3">
					                    		<?php
					                    			echo 	"<i>Volume</i>";
					                    		?>
					                    	</div>
					                    	<div class="col-md-4">
					                    		<?php
					                    			echo 	"<i>Harga</i>";
					                    		?>
					                    	</div>
					                    	<div class="col-md-5">
					                    		<?php
					                    			echo 	"<i>Total</i>";
					                    		?>
					                    	</div>
				                    	</div>
			                    	</div>
		                    	</div>
			                  	<div class="row">
			                    	<div class="col-md-4" style="text-align: right;">
			                    		<?php echo "<strong>BoQ</strong>"; ?>
			                    	</div>
			                    	<div class="col-md-8">
			                    		<div class="row">
					                    	<div class="col-md-3">
					                    		<?php
					                    			echo 	"<i class='text-primary' style='font-size: 16px;' id='boqVOL'><strong>"
					                    					.number_format($JOB_BOQV, 2).
					                    					"</strong></i>";
					                    		?>
					                    		<input type="hidden" name="JOB_UNIT" id="JOB_UNIT" value="<?php echo $JOB_UNIT; ?>" />
					                    		<input type="hidden" name="JOB_BOQV" id="JOB_BOQV" value="<?php echo $BOQ_VOLM; ?>" />
					                    	</div>
					                    	<div class="col-md-4">
					                    		<?php
					                    			echo 	"<i class='text-primary' style='font-size: 16px;' id='boqPRC'><strong>"
					                    					.number_format($JOB_BOQP, 2).
					                    					"</strong></i>";
					                    		?>
					                    		<input type="hidden" name="JOB_BOQP" id="JOB_BOQP" value="<?php echo $JOB_BOQP; ?>" />
					                    	</div>
					                    	<div class="col-md-5">
					                    		<?php
					                    			echo 	"<i class='text-primary' style='font-size: 16px;' id='boqTOT'><strong>"
					                    					.number_format($JOB_BOQT, 2).
					                    					"</strong></i>";
					                    		?>
					                    		<input type="hidden" name="JOB_BOQT" id="JOB_BOQT" value="<?php echo $JOB_BOQT; ?>" />
					                    	</div>
					                    </div>
			                    	</div>
		                    	</div>
			                  	<div class="row">
			                    	<div class="col-md-4" style="text-align: right;">
			                    		<?php echo "<strong>RAP</strong>"; ?>
			                    	</div>
			                    	<div class="col-md-8">
			                    		<div class="row">
					                    	<div class="col-md-3">
					                    		<?php
					                    			echo 	"<i class='text-yellow' style='font-size: 16px;' id='rapVOL'><strong>"
					                    					.number_format($JOB_RAPV, 2).
					                    					"</strong></i>";
					                    		?>
					                    		<input type="hidden" name="JOB_RAPV" id="JOB_RAPV" value="<?php echo $JOB_RAPV; ?>" />
					                    	</div>
					                    	<div class="col-md-4">
					                    		<?php
					                    			echo 	"<i class='text-yellow' style='font-size: 16px;' id='rapPRC'><strong>"
					                    					.number_format($JOB_RAPP, 2).
					                    					"</strong></i>";
					                    		?>
					                    		<input type="hidden" name="JOB_RAPP" id="JOB_RAPP" value="<?php echo $JOB_RAPP; ?>" />
					                    	</div>
					                    	<div class="col-md-5">
					                    		<?php
					                    			echo 	"<i class='text-yellow' style='font-size: 16px;' id='rapTOT'><strong>"
					                    					.number_format($JOB_RAPT, 2).
					                    					"</strong></i>";
					                    		?>
					                    		<input type="hidden" name="JOB_RAPT" id="JOB_RAPT" value="<?php echo $JOB_RAPT; ?>" />
					                    	</div>
					                    </div>
			                    	</div>
		                    	</div>
			                  	<div class="row">
			                    	<div class="col-md-4" style="text-align: right;">
			                    		<?php echo "<strong>Deviasi</strong>"; ?>
			                    	</div>
			                    	<div class="col-md-8">
			                    		<div class="row">
					                    	<div class="col-md-3">
					                    		<?php
					                    			echo 	"";
					                    		?>
					                    	</div>
					                    	<div class="col-md-4">
					                    		<?php
					                    			echo 	"";
					                    		?>
					                    	</div>
					                    	<div class="col-md-5">
					                    		<?php
					                    			if($JOB_DEV >=0)
					                    				echo 	"<i class='text-success' style='font-size: 16px;' id='devAMN'><strong>".number_format($JOB_DEV, 2)."</strong></i>";
					                    			else
					                    				echo 	"<i class='text-danger' style='font-size: 16px;' id='devAMN'><strong>".number_format($JOB_DEV, 2)."</strong></i>";
					                    		?>
					                    	</div>
					                    </div>
			                    	</div>
		                    	</div>
							</div>
						</div>
					</div>

					<div class="col-md-6">
						<div class="box box-warning">
							<div class="box-header with-border" style="display: none;">
								<i class="fa fa-cloud-upload"></i>
								<h3 class="box-title">&nbsp;</h3>
							</div>
							<div class="box-body">
				                <div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Notes ?> </label>
				                    <div class="col-sm-9">
				                        <textarea name="JOB_NOTE" class="form-control" id="JOB_NOTE" style="height: 65px"><?php echo $JOB_NOTE; ?></textarea>
				                    </div>
				                </div>
				                <div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label">Status</label>
				                    <div class="col-sm-6">
				                        <input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $JOB_STAT; ?>">
				                        <?php
				                            $isDisabled = 1;
				                            if($JOB_STAT == 1 || $JOB_STAT == 4)
				                            {
				                                $isDisabled = 0;
				                            }
				                        ?>
		                                <select name="JOB_STAT" id="JOB_STAT" class="form-control select2">
		                                    <?php
			                                    $disableBtn	= 0;
			                                    if($JOB_STAT == 5 || $JOB_STAT == 6 || $JOB_STAT == 9)
			                                    {
			                                        $disableBtn	= 1;
			                                    }
			                                    if($JOB_STAT != 1 AND $JOB_STAT != 4)
			                                    {
			                                        ?>
			                                            <option value="1"<?php if($JOB_STAT == 1) { ?> selected <?php } ?> disabled>New</option>
			                                            <option value="2"<?php if($JOB_STAT == 2) { ?> selected <?php } ?> disabled>Confirm</option>
			                                            <option value="3"<?php if($JOB_STAT == 3) { ?> selected <?php } ?> disabled>Approve</option>
			                                            <option value="4"<?php if($JOB_STAT == 4) { ?> selected <?php } ?> disabled>Revising</option>
			                                            <option value="5"<?php if($JOB_STAT == 5) { ?> selected <?php } ?> disabled>Rejected</option>
			                                            <option value="6"<?php if($JOB_STAT == 6) { ?> selected <?php } if($disableBtn == 1) {?> disabled <?php } ?>>Closed</option>
			                                            <option value="7"<?php if($JOB_STAT == 7) { ?> selected <?php } ?> disabled>Waiting</option>
			                                            <option value="9"<?php if($JOB_STAT == 9) { ?> selected <?php } if($disableBtn == 1) {?> disabled <?php } ?>>Void</option>
			                                        <?php
			                                    }
			                                    else
			                                    {
			                                        ?>
			                                            <option value="1"<?php if($JOB_STAT == 1) { ?> selected <?php } ?>>New</option>
			                                            <option value="2"<?php if($JOB_STAT == 2) { ?> selected <?php } ?>>Confirm</option>
			                                        <?php
			                                    }
			                                ?>
		                                </select>
				                    </div>
					                <?php if($JOB_STAT == 1 || $JOB_STAT == 4) { ?>
					                    <div class="col-sm-3">
					                        <div class="pull-right">
					                        	<a class="btn btn-sm btn-warning" data-toggle="modal" data-target="#mdl_addItm">
					                        		<i class="glyphicon glyphicon-search"></i>&nbsp;&nbsp;<?php echo $SelectItem; ?>
					                        	</a>
					                        </div>
					                    </div>
				            		<?php } ?>
				                </div>
							</div>
						</div>
					</div>

	                <div class="col-md-12">
	                    <div class="box box-primary">
	                        <div class="search-table-outter">
	                            <table id="tbl" class="table table-bordered table-striped" width="100%">
	                                <tr style="background:#CCCCCC">
	                                  	<th width="2%" height="25" style="text-align:left">&nbsp;</th>
	                                  	<th width="10%" style="text-align:center; vertical-align: middle;"><?php echo $ItemCode ?> </th>
	                                  	<th width="30%" style="text-align:center; vertical-align: middle;"><?php echo $ItemName ?> </th>
	                                  	<th width="10%" style="text-align:center;">Volume</th>
	                                  	<th width="5%" style="text-align:center;">Koef</th>
	                                  	<th width="10%" style="text-align:center;"><?php echo $Price ?> </th>
	                                  	<th width="15%" style="text-align:center;">Total </th>
	                                  	<th width="3%" style="text-align:center; vertical-align: middle;"><?php echo $Unit ?> </th>
	                                  	<th width="15%" style="text-align:center; vertical-align: middle;"><?php echo $Remarks ?> </th>
	                                </tr>
	                                <?php
	                                if($task == 'edit')
	                                {
	                                    $sqlDET	= "SELECT A.* FROM tbl_jobcreate_detail A
	                                                WHERE A.JOBPARENT = '$JOB_PARCODE' 
	                                                    AND A.PRJCODE = '$PRJCODE'";
	                                    $result = $this->db->query($sqlDET)->result();
	                                    $i		= 0;
	                                    $j		= 0;
	                                    
	                                    foreach($result as $row) :
	                                        $currentRow  	= ++$i;
	                                        $PRJCODE 		= $row->PRJCODE;
	                                        $JOB_NUM 		= $row->JOB_NUM;
	                                        $JOBCODEID 		= $row->JOBCODEID;
	                                        $JOBPARENT 		= $row->JOBPARENT;
	                                        $ITM_CODE 		= $row->ITM_CODE;
	                                        $ITM_NAME 		= $row->ITM_NAME;
	                                        $ITM_UNIT 		= $row->ITM_UNIT;
	                                        $ITM_GROUP 		= $row->ITM_GROUP;
	                                        $ITM_RAPV 		= $row->ITM_RAPV;
	                                        $ITM_KOEF 		= $row->ITM_KOEF;
	                                        $ITM_RAPP 		= $row->ITM_RAPP;
	                                        $ITM_TOTAL 		= $row->ITM_TOTAL;
	                                        $ITM_NOTES 		= $row->ITM_NOTES;
	                                        $ISLOCK 		= $row->ISLOCK;

	                                   		/*if ($j==1) {
	                                            echo "<tr class=zebra1>";
	                                            $j++;
	                                        } else {
	                                            echo "<tr class=zebra2>";
	                                            $j--;
	                                        }*/
	                                        $secLock 		= base_url().'index.php/c_project/c_joblist/lockCom/?id=';
	                                        $lockID 		= "$secLock~tbl_jobcreate_detail~JOB_NUM~$JOB_NUM~PRJCODE~$PRJCODE~JOBPARENT~$JOBPARENT";
	                                        ?> 
	                                        <tr id="tr_<?php echo $currentRow; ?>" style="vertical-align: middle;">
	                                        	<input type="hidden" name="urlLock<?php echo $currentRow; ?>" id="urlLock<?php echo $currentRow; ?>" value="<?=$lockID?>">
	                                        	<td style="text-align:center; vertical-align: middle;">
		                                          	<?php
		                                          		$JOB_STAT = 3;
			                                            if($JOB_STAT == 1 || $JOB_STAT == 4)
			                                            {
			                                                ?>
			                                                <a href="#" onClick="deleteRow(<?php echo $currentRow; ?>)" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a>
			                                                <?php
			                                            }
			                                            else
			                                            {
				                                            if($ISLOCK == 0)
				                                            {
				                                                ?>
				                                                	<a href="#" onClick="lockRow(<?php echo $currentRow; ?>)" title="Kunci Komponen" class="btn btn-warning btn-xs"><i class="fa fa-unlock-alt"></i></a>
				                                                <?php
				                                            }
				                                            else
				                                            {
				                                                ?>
				                                                	<a href="#" onClick="lockRow(<?php echo $currentRow; ?>)" title="Kunci Komponen" class="btn btn-success btn-xs"><i class="fa fa-lock"></i></a>
				                                                <?php
				                                            }
			                                            }
		                                          	?>
		                                        	<input type="hidden" id="chk" name="chk" value="<?php echo $currentRow; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">
		                                        </td>

		                                        <!-- ITM_CODE, PRJCODE, ITM_GROUP -->
		                                        <td style="text-align:left; vertical-align: middle;" nowrap>
		                                        	<?php echo $ITM_CODE; ?>
		                                          	<input type="hidden" id="data<?php echo $currentRow; ?>ITM_CODE" name="data[<?php echo $currentRow; ?>][ITM_CODE]" value="<?=$ITM_CODE?>" class="form-control" style="max-width:300px;">
		                                          	<input type="hidden" id="data<?php echo $currentRow; ?>PRJCODE" name="data[<?php echo $currentRow; ?>][PRJCODE]" value="<?=$PRJCODE?>" class="form-control" style="max-width:300px;">
		                                          	<input type="hidden" id="data<?php echo $currentRow; ?>JOBPARENT" name="data[<?php echo $currentRow; ?>][JOBPARENT]" value="<?=$JOBPARENT?>" class="form-control" style="max-width:300px;">
		                                          	<input type="hidden" id="data<?php echo $currentRow; ?>ITM_GROUP" name="data[<?php echo $currentRow; ?>][ITM_GROUP]" value="<?=$ITM_GROUP?>" class="form-control" style="max-width:300px;">
		                                      	</td>

		                                        <!-- ITM_NAME -->
		                                        <td style="text-align:left; vertical-align: middle;" nowrap>
		                                        	<?php echo $ITM_NAME; ?>
		                                          	<input type="hidden" id="data<?php echo $currentRow; ?>ITM_NAME" name="data[<?php echo $currentRow; ?>][ITM_NAME]" value="<?=$ITM_NAME?>" class="form-control" style="max-width:300px;">
		                                      	</td>

		                                        <!-- ITM_RAPV -->
		                                        <td style="text-align:right; vertical-align: middle;">
		                                        	<input type="text" name="ITM_RAPVX<?php echo $currentRow; ?>" id="ITM_RAPVX<?php echo $currentRow; ?>" value="<?php echo number_format($ITM_RAPV, 2); ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgVOL(this,<?php echo $currentRow; ?>);" >
		                                        	<input type="hidden" name="data[<?php echo $currentRow; ?>][ITM_RAPV]" id="data<?php echo $currentRow; ?>ITM_RAPV" value="<?php echo $ITM_RAPV; ?>" class="form-control" style="max-width:300px;" >
		                                      	</td>

		                                        <!-- ITM_KOEF -->
		                                        <td style="text-align:right; vertical-align: middle;">
		                                        	<input type="text" name="ITM_KOEFX<?php echo $currentRow; ?>" id="ITM_KOEFX<?php echo $currentRow; ?>" value="<?php echo number_format($ITM_KOEF, 2); ?>" class="form-control" style="min-width:60px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgKOEF(this,<?php echo $currentRow; ?>);" >
		                                        	<input type="hidden" name="data[<?php echo $currentRow; ?>][ITM_KOEF]" id="data<?php echo $currentRow; ?>ITM_KOEF" value="<?php echo $ITM_KOEF; ?>" class="form-control" style="max-width:300px;" >
		                                      	</td>

		                                        <!-- ITM_RAPP -->
		                                        <td style="text-align:right; vertical-align: middle;">
		                                        	<input type="text" name="ITM_RAPPX<?php echo $currentRow; ?>" id="ITM_RAPPX<?php echo $currentRow; ?>" value="<?php echo number_format($ITM_RAPP, 2); ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgPRICE(this,<?php echo $currentRow; ?>);" >
		                                        	<input type="hidden" name="data[<?php echo $currentRow; ?>][ITM_RAPP]" id="data<?php echo $currentRow; ?>ITM_RAPP" value="<?php echo $ITM_RAPP; ?>" class="form-control" style="max-width:300px;" >
		                                      	</td>

		                                        <!-- ITM_TOTAL -->
		                                        <td style="text-align:right; vertical-align: middle;"> <!-- Item Bdget -->
		                                        	<input type="text" name="ITM_TOTALX<?php echo $currentRow; ?>" id="ITM_TOTALX<?php echo $currentRow; ?>" value="<?php echo number_format($ITM_TOTAL, 2); ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgVOL(this,<?php echo $currentRow; ?>);" >
		                                        	<input type="hidden" name="data[<?php echo $currentRow; ?>][ITM_TOTAL]" id="data<?php echo $currentRow; ?>ITM_TOTAL" value="<?php echo $ITM_TOTAL; ?>" class="form-control" style="max-width:300px;" >
		                                      	</td>

		                                        <!-- ITM_UNIT -->
		                                        <td style="text-align:left; vertical-align: middle;" nowrap>
		                                          	<?php
	                                            		echo "$ITM_UNIT";
	                                            	?>
	                                                <input type="hidden" id="data<?php echo $currentRow; ?>ITM_UNIT" name="data[<?php echo $currentRow; ?>][ITM_UNIT]" value="<?php echo $ITM_UNIT; ?>" class="form-control">
		                                      	</td>

		                                        <!-- ITM_NOTES -->
		                                        <td style="text-align:left; vertical-align: middle;" nowrap>
		                                          	<?php
			                                            if($JOB_STAT == 1 || $JOB_STAT == 4)
			                                            {
			                                                ?>
			                                                <input type="text" id="data<?php echo $currentRow; ?>ITM_NOTES" name="data[<?php echo $currentRow; ?>][ITM_NOTES]" value="<?php echo $ITM_NOTES; ?>" class="form-control" style="max-width:300px;">
			                                                <?php
			                                            }
			                                            else
			                                            {
			                                            	echo "$ITM_NOTES";
			                                            	?>
			                                                	<input type="hidden" id="data<?php echo $currentRow; ?>ITM_NOTES" name="data[<?php echo $currentRow; ?>][ITM_NOTES]" value="<?php echo $ITM_NOTES; ?>" class="form-control" style="max-width:300px;">
			                                                <?php
			                                            }
		                                          	?>
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
	                <br>
	                <div class="col-md-6">
		                <div class="form-group">
		                    <label for="inputName" class="col-sm-3 control-label">&nbsp;</label>
		                    <div class="col-sm-9">
		                        <button class="btn btn-primary" id="btnSave">
		                        <i class="fa fa-save"></i></button>&nbsp;
		                        <?php
		                            echo anchor("$backURL",'<button class="btn btn-danger" type="button" id="btnBack"><i class="fa fa-reply"></i></button>');
		                        ?>
		                    </div>
		                </div>
		            </div>
	            </form>
		        <div class="col-md-12">
					<?php
	                    $DOC_NUM	= $JOB_NUM;
	                    $sqlCAPPH	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM'";
	                    $resCAPPH	= $this->db->count_all($sqlCAPPH);
						$sqlAPP		= "SELECT * FROM tbl_docstepapp WHERE MENU_CODE = '$MenuApp'
										AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE proj_Code = '$PRJCODE_LEV')";
						$resAPP		= $this->db->query($sqlAPP)->result();
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

	    	<!-- ============ START MODAL =============== -->
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
					$Active3		= "";
					$Active1Cls		= "class='active'";
					$Active2Cls		= "";
					$Active3Cls		= "";
		    	?>
		        <div class="modal fade" id="mdl_addItm" name='mdl_addItm' tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		            <div class="modal-dialog">
			            <div class="modal-content">
			                <div class="modal-body">
								<div class="row">
							    	<div class="col-md-12">
						              	<ul class="nav nav-tabs">
						                    <li id="li1" <?php echo $Active1Cls; ?>>
						                    	<a href="#itm1" data-toggle="tab" onClick="setType(1)"><?php echo $ItemList; ?></a>
						                    </li>
						                </ul>
							            <div class="box-body">
							            	<div class="<?php echo $Active1; ?> tab-pane" id="itm1">
		                                        <div class="form-group">
		                                        	<form method="post" name="frmSearch1" id="frmSearch1" action="">
			                                            <table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
			                                                <thead>
			                                                    <tr>
			                                                        <th width="2%" style="text-align: center; vertical-align: middle;">&nbsp;</th>
			                                                        <th width="60%" style="text-align: center; vertical-align: middle;"  nowrap><?php echo $ItemName; ?></th>
			                                                        <th width="3%" style="text-align: center; vertical-align: middle;" nowrap>Sat.</th>
			                                                        <th width="5%" style="text-align: center; vertical-align: middle;" nowrap>Group</th>
			                                                        <th width="10%" style="text-align: center; vertical-align: middle;" nowrap><?php echo $BudgetQty; ?></th>
			                                                        <th width="10%" style="text-align: center; vertical-align: middle;" nowrap><?php echo $Requested; ?></th>
			                                                        <th width="10%" style="text-align: center; vertical-align: middle;" nowrap><?php echo $Remain; ?></th>Total</th>
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

							            	<div class="<?php echo $Active2; ?> tab-pane" id="itm2" style="display: none;">
		                                        <div class="form-group">
		                                        	<form method="post" name="frmSearch2" id="frmSearch2" action="">
			                                            <table id="example2" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
			                                                <thead>
		                                                        <tr>
			                                                        <th width="2%" style="text-align: center; vertical-align: middle;">&nbsp;</th>
			                                                        <th width="40%" style="text-align: center; vertical-align: middle;"  nowrap><?php echo $ItemName; ?></th>
			                                                        <th width="10%" style="text-align: center; vertical-align: middle;" nowrap><?php echo $Unit; ?></th>
			                                                        <th width="10%" style="text-align: center; vertical-align: middle;" nowrap><?php echo $BudgetQty; ?><br>Vol. </th>
			                                                        <th width="10%" style="text-align: center; vertical-align: middle;" nowrap><?php echo $Requested; ?><br>Vol. </th>
			                                                        <th width="10%" style="text-align: center; vertical-align: middle;" nowrap><?php echo $Ordered; ?><br>Vol.  </th>
			                                                        <th width="10%" style="text-align: center; vertical-align: middle;" nowrap><?php echo $Remain; ?></th>
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
                                      	<input type="hidden" name="rowCheck" id="rowCheck" value="0">
                                      	<button type="button" id="idClose" class="btn btn-default" data-dismiss="modal" style="display: none";>Close</button>
	                                </div>
	                            </div>
			                </div>
				        </div>
				    </div>
				</div>

				<script type="text/javascript">
					function setType(tabType)
					{
						if(tabType == 1)
						{
							document.getElementById('itm1').style.display	= '';
							document.getElementById('itm2').style.display	= 'none';
						}
						else if(tabType == 2)
						{
							document.getElementById('itm1').style.display	= 'none';
							document.getElementById('itm2').style.display	= '';
						}
						else
						{
							document.getElementById('itm1').style.display	= 'none';
							document.getElementById('itm2').style.display	= 'none';
						}
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
					        "ajax": "<?php echo site_url('c_project/c_joblist/get_AllDataITM2/?id='.$PRJCODE.'&JID='.$JOB_PARCODE)?>",
					        "type": "POST",
							//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
							"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
							"columnDefs": [	{ targets: [0,2,3], className: 'dt-body-center' },
											{ targets: [4,5,6], className: 'dt-body-right' },
											{ "width": "100px", "targets": [1] }
										  ],
							"language": {
					            "infoFiltered":"",
					            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
					        },
						});

						$("#idRefresh1").click(function()
						{
							$('#example1').DataTable().ajax.reload();
						});

				    	$('#example2').DataTable(
				    	{
					        "processing": true,
					        "serverSide": true,
							//"scrollX": false,
							"autoWidth": true,
							"filter": true,
					        "ajax": "<?php echo site_url('c_purchase/c_pr180d0c/get_AllDataITMS/?id='.$PRJCODE)?>",
					        "type": "POST",
							//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
							"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
							"columnDefs": [	{ targets: [2,4,5], className: 'dt-body-center' },
											{ "width": "100px", "targets": [1] }
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
					});

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
					   	$("#btnDetail1").click(function()
					    {
							var totChck 	= $("#rowCheck").val();
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
						      	add_item($(this).val());
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
                        	document.getElementById("idClose").click()
					    });

					   	$("#btnDetail2").click(function()
					    {
							var totChck 	= $("#rowCheck").val();
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
						      	add_item($(this).val());
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
                        	document.getElementById("idClose").click()
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
	    $('#datepicker').datepicker({
	      autoclose: true,
		  endDate: '+1d'
	    });
		
		//Date picker
		$('#datepicker1').datepicker({
		  autoclose: true,
		  startDate: '+0d'
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
	
	function add_item(strItem) 
	{
		arrItem = strItem.split('|');
		var objTable, objTR, objTD, intIndex, arrItem;

		var JOB_NUMx 	= "<?php echo $JOB_NUM; ?>";
		ilvl = arrItem[1];
		
		validateDouble(arrItem[3])
		if(validateDouble(arrItem[3]))
		{
			swal("Double Item for " + arrItem[3],
			{
				icon: "warning",
			});
			return;
		}

		PRJCODE 		= arrItem[0];
		JOBPARENT 		= arrItem[1];
		JOBPARDESC 		= arrItem[2];
		ITM_CODE 		= arrItem[3];
		ITM_NAME 		= arrItem[4];
		ITM_UNIT 		= arrItem[5];
		ITM_GROUP 		= arrItem[6];

		ITM_BOQV 		= 0;
		ITM_BOQP 		= 0;

		ITM_RAPV 		= 0;
		ITM_RAPP 		= 0;

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
		
		// ITM_CODE, PRJCODE, ITM_GROUP
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = 'right';
			objTD.innerHTML = ''+ITM_CODE+'<input type="hidden" id="data'+intIndex+'ITM_CODE" name="data['+intIndex+'][ITM_CODE]" value="'+ITM_CODE+'" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'PRJCODE" name="data['+intIndex+'][PRJCODE]" value="'+PRJCODE+'" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'JOBPARENT" name="data['+intIndex+'][JOBPARENT]" value="'+JOBPARENT+'" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'ITM_GROUP" name="data['+intIndex+'][ITM_GROUP]" value="'+ITM_GROUP+'" class="form-control" style="max-width:300px;">';
		
		// ITM_NAME
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = 'right';
			objTD.innerHTML = ''+ITM_NAME+'<input type="hidden" id="data'+intIndex+'ITM_NAME" name="data['+intIndex+'][ITM_NAME]" value="'+ITM_NAME+'" class="form-control" style="max-width:300px;">';
		
		// ITM_RAPV
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = '<input type="text" name="ITM_RAPVX'+intIndex+'" id="ITM_RAPVX'+intIndex+'" value="0.00" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgVOL(this,'+intIndex+');" ><input type="hidden" name="data['+intIndex+'][ITM_RAPV]" id="data'+intIndex+'ITM_RAPV" value="0" class="form-control" style="max-width:300px;" >';
		
		// ITM_KOEF
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = '<input type="text" name="ITM_KOEFX'+intIndex+'" id="ITM_KOEFX'+intIndex+'" value="0.00" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgKOEF(this,'+intIndex+');" ><input type="hidden" name="data['+intIndex+'][ITM_KOEF]" id="data'+intIndex+'ITM_KOEF" value="0" class="form-control" style="max-width:300px;" >';
		
		// ITM_RAPP
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = '<input type="text" name="ITM_RAPPX'+intIndex+'" id="ITM_RAPPX'+intIndex+'" value="0.00" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgPRICE(this,'+intIndex+');" ><input type="hidden" name="data['+intIndex+'][ITM_RAPP]" id="data'+intIndex+'ITM_RAPP" value="0" class="form-control" style="max-width:300px;" >';
		
		// ITM_TOTAL
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = '<input type="text" name="ITM_TOTALX'+intIndex+'" id="ITM_TOTALX'+intIndex+'" value="0.00" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgVOL(this,'+intIndex+');" ><input type="hidden" name="data['+intIndex+'][ITM_TOTAL]" id="data'+intIndex+'ITM_TOTAL" value="0" class="form-control" style="max-width:300px;" >';
		
		// ITM_UNIT
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'center';
			objTD.innerHTML = ''+ITM_UNIT+'<input type="hidden" name="data['+intIndex+'][ITM_UNIT]" id="data'+intIndex+'ITM_UNIT" value="'+ITM_UNIT+'" class="form-control" style="max-width:300px;" >';
		
		// ITM_NOTES
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = 'left';
			objTD.innerHTML = '<input type="text" name="data['+intIndex+'][ITM_NOTES]" id="data'+intIndex+'ITM_NOTES" size="20" value="" class="form-control" style="min-width:110px; max-width:300px; text-align:left">';

		document.getElementById('totalrow').value = intIndex;
	}
	
	function chgVOL(thisval, row)
	{
		var decFormat	= document.getElementById('decFormat').value;
		var ITM_RAPV	= parseFloat(eval(thisval).value.split(",").join(""));
		document.getElementById('data'+row+'ITM_RAPV').value 	= RoundNDecimal(parseFloat(Math.abs(ITM_RAPV)), 2);
		document.getElementById('ITM_RAPVX'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_RAPV)), 2));

		JOBH_VOLM 		= parseFloat(document.getElementById('JOB_BOQV').value);
		ITM_KOEF		= parseFloat(ITM_RAPV / JOBH_VOLM);
		document.getElementById('data'+row+'ITM_KOEF').value 	= RoundNDecimal(parseFloat(Math.abs(ITM_KOEF)), 4);
		document.getElementById('ITM_KOEFX'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_KOEF)), 4));

		ITM_RAPP 		= parseFloat(document.getElementById('data'+row+'ITM_RAPP').value);
		ITM_TOTAL		= parseFloat(ITM_RAPV * ITM_RAPP);
		document.getElementById('data'+row+'ITM_TOTAL').value 	= RoundNDecimal(parseFloat(Math.abs(ITM_TOTAL)), 2);
		document.getElementById('ITM_TOTALX'+row).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_TOTAL)), 2));

		var totRow 		= document.getElementById('totalrow').value;
		
		var GTVolITM	= 0;
		var GTAmnITM	= 0;
		for(i=1;i<=totRow;i++)
		{
			let myObj 	= document.getElementById('data'+i+'ITM_TOTAL');
			var values 	= typeof myObj !== 'undefined' ? myObj : '';
			
			if(values != null)
			{
				totVITM 	= parseFloat(document.getElementById('data'+i+'ITM_RAPV').value);
				GTVolITM 	= parseFloat(GTVolITM) + parseFloat(totVITM);

				totITM 		= parseFloat(document.getElementById('data'+i+'ITM_TOTAL').value);
				GTAmnITM 	= parseFloat(GTAmnITM) + parseFloat(totITM);
			}
		}
		GTVolITMP 		= parseFloat(GTVolITM);
		if(GTVolITM == 0)
			GTVolITMP 	= 1;

		ITM_AVGP 		= parseFloat(GTAmnITM) / parseFloat(GTVolITMP);

		document.getElementById('JOB_RAPV').value 		= RoundNDecimal(parseFloat(Math.abs(GTVolITM)), 2);
		document.getElementById('JOB_RAPP').value 		= RoundNDecimal(parseFloat(Math.abs(ITM_AVGP)), 2);
		document.getElementById('JOB_RAPT').value 		= RoundNDecimal(parseFloat(Math.abs(GTAmnITM)), 2);

		document.getElementById('rapVOL').innerHTML 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(GTVolITM)), 2));
		document.getElementById('rapPRC').innerHTML 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_AVGP)), 2));
		document.getElementById('rapTOT').innerHTML 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(GTAmnITM)), 2));

		// DEVIASI
			var JOB_BOQT	= document.getElementById('JOB_BOQT').value;
			var DevAmn 		= parseFloat(GTAmnITM - JOB_BOQT);
			document.getElementById('devAMN').innerHTML 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(DevAmn)), 2));
			if(DevAmn >= 0)
				$("#devAMN").removeClass('text-success').addClass('text-danger');
			else
				$("#devAMN").removeClass('text-danger').addClass('text-success');
	}
	
	function chgKOEF(thisval, row)
	{
		var decFormat	= document.getElementById('decFormat').value;
		var ITM_KOEF	= parseFloat(eval(thisval).value.split(",").join(""));
		document.getElementById('data'+row+'ITM_KOEF').value 	= RoundNDecimal(parseFloat(Math.abs(ITM_KOEF)), 4);
		document.getElementById('ITM_KOEFX'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_KOEF)), 4));

		JOBH_VOLM 		= parseFloat(document.getElementById('JOB_BOQV').value);
		ITM_RAPV		= parseFloat(ITM_KOEF * JOBH_VOLM);
		document.getElementById('data'+row+'ITM_RAPV').value 	= RoundNDecimal(parseFloat(Math.abs(ITM_RAPV)), 2);
		document.getElementById('ITM_RAPVX'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_RAPV)), 2));

		ITM_RAPP 		= parseFloat(document.getElementById('data'+row+'ITM_RAPP').value);
		ITM_TOTAL		= parseFloat(ITM_RAPV * ITM_RAPP);
		document.getElementById('data'+row+'ITM_TOTAL').value 	= RoundNDecimal(parseFloat(Math.abs(ITM_TOTAL)), 2);
		document.getElementById('ITM_TOTALX'+row).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_TOTAL)), 2));

		var totRow 		= document.getElementById('totalrow').value;
		
		var GTVolITM	= 0;
		var GTAmnITM	= 0;
		for(i=1;i<=totRow;i++)
		{
			let myObj 	= document.getElementById('data'+i+'ITM_TOTAL');
			var values 	= typeof myObj !== 'undefined' ? myObj : '';
			
			if(values != null)
			{
				totVITM 	= parseFloat(document.getElementById('data'+i+'ITM_RAPV').value);
				GTVolITM 	= parseFloat(GTVolITM) + parseFloat(totVITM);

				totITM 		= parseFloat(document.getElementById('data'+i+'ITM_TOTAL').value);
				GTAmnITM 	= parseFloat(GTAmnITM) + parseFloat(totITM);
			}
		}
		GTVolITMP 		= parseFloat(GTVolITM);
		if(GTVolITM == 0)
			GTVolITMP 	= 1;

		ITM_AVGP 		= parseFloat(GTAmnITM) / parseFloat(GTVolITMP);
		
		document.getElementById('JOB_RAPV').value 		= RoundNDecimal(parseFloat(Math.abs(GTVolITM)), 2);
		document.getElementById('JOB_RAPP').value 		= RoundNDecimal(parseFloat(Math.abs(ITM_AVGP)), 2);
		document.getElementById('JOB_RAPT').value 		= RoundNDecimal(parseFloat(Math.abs(GTAmnITM)), 2);

		document.getElementById('rapVOL').innerHTML 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(GTVolITM)), 2));
		document.getElementById('rapPRC').innerHTML 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_AVGP)), 2));
		document.getElementById('rapTOT').innerHTML 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(GTAmnITM)), 2));

		// DEVIASI
			var JOB_BOQT	= document.getElementById('JOB_BOQT').value;
			var DevAmn 		= parseFloat(GTAmnITM - JOB_BOQT);
			document.getElementById('devAMN').innerHTML 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(DevAmn)), 2));
			if(DevAmn >= 0)
				$("#devAMN").removeClass('text-success').addClass('text-danger');
			else
				$("#devAMN").removeClass('text-danger').addClass('text-success');
	}
	
	function chgPRICE(thisval, row)
	{
		var decFormat	= document.getElementById('decFormat').value;
		var ITM_RAPP	= parseFloat(eval(thisval).value.split(",").join(""));
		document.getElementById('data'+row+'ITM_RAPP').value 	= RoundNDecimal(parseFloat(Math.abs(ITM_RAPP)), 4);
		document.getElementById('ITM_RAPPX'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_RAPP)), 4));

		JOBH_VOLM 		= parseFloat(document.getElementById('JOB_BOQV').value);
		ITM_RAPV 		= parseFloat(document.getElementById('data'+row+'ITM_RAPV').value);
		ITM_TOTAL		= parseFloat(ITM_RAPV * ITM_RAPP);

		document.getElementById('data'+row+'ITM_TOTAL').value 	= RoundNDecimal(parseFloat(Math.abs(ITM_TOTAL)), 2);
		document.getElementById('ITM_TOTALX'+row).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_TOTAL)), 2));

		var totRow 		= document.getElementById('totalrow').value;
		
		var GTVolITM	= 0;
		var GTAmnITM	= 0;
		for(i=1;i<=totRow;i++)
		{
			let myObj 	= document.getElementById('data'+i+'ITM_TOTAL');
			var values 	= typeof myObj !== 'undefined' ? myObj : '';
			
			if(values != null)
			{
				totVITM 	= parseFloat(document.getElementById('data'+i+'ITM_RAPV').value);
				GTVolITM 	= parseFloat(GTVolITM) + parseFloat(totVITM);

				totITM 		= parseFloat(document.getElementById('data'+i+'ITM_TOTAL').value);
				GTAmnITM 	= parseFloat(GTAmnITM) + parseFloat(totITM);
			}
		}

		GTVolITMP 		= parseFloat(GTVolITM);
		if(GTVolITM == 0)
			GTVolITMP 	= 1;

		ITM_AVGP 		= parseFloat(GTAmnITM) / parseFloat(GTVolITMP);
		
		document.getElementById('JOB_RAPV').value 		= RoundNDecimal(parseFloat(Math.abs(GTVolITM)), 2);
		document.getElementById('JOB_RAPP').value 		= RoundNDecimal(parseFloat(Math.abs(ITM_AVGP)), 2);
		document.getElementById('JOB_RAPT').value 		= RoundNDecimal(parseFloat(Math.abs(GTAmnITM)), 2);

		document.getElementById('rapVOL').innerHTML 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(GTVolITM)), 2));
		document.getElementById('rapPRC').innerHTML 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_AVGP)), 2));
		document.getElementById('rapTOT').innerHTML 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(GTAmnITM)), 2));

		// DEVIASI
			var JOB_BOQT	= document.getElementById('JOB_BOQT').value;
			var DevAmn 		= parseFloat(GTAmnITM - JOB_BOQT);
			document.getElementById('devAMN').innerHTML 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(DevAmn)), 2));
			if(DevAmn >= 0)
				$("#devAMN").removeClass('text-success').addClass('text-danger');
			else
				$("#devAMN").removeClass('text-danger').addClass('text-success');
	}
	
	function validateDouble(vcode) 
	{
		var totRow 		= document.getElementById('totalrow').value;
		var duplicate 	= false;

		for(i=1;i<=totRow;i++)
		{
			let myObj 	= document.getElementById('data'+i+'ITM_CODE');
			var values 	= typeof myObj !== 'undefined' ? myObj : '';
			
			if(values != null)
			{
				var elitem1	= document.getElementById('data'+i+'ITM_CODE').value;
				if (elitem1 == vcode)
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

	function lockRow(row)
	{
	    swal({
            text: "<?php echo $sureLock; ?>",
            icon: "warning",
            buttons: ["No", "Yes"],
        })
        .then((willDelete) => 
        {
            if (willDelete) 
            {
                var collID	= document.getElementById('urlLock'+row).value;
		        var myarr 	= collID.split("~");

		        var url 	= myarr[0];

		        $.ajax({
		            type: 'POST',
		            url: url,
		            data: {collID: collID},
		            success: function(response)
		            {
		            	swal(response, 
						{
							icon: "success",
						});
		                $('#example').DataTable().ajax.reload();
		            }
		        });
            } 
            else 
            {
                /*swal("<?php echo $cancDel; ?>", 
				{
					icon: "error",
				});*/
            }
        });
	}
	
	function checkForm(value)
	{
		var totrow 		= document.getElementById('totalrow').value;
		var PR_REFNO 	= document.getElementById('PR_REFNO').value;
		var PR_NOTE 	= document.getElementById('PR_NOTE').value;

		var STAT_BEFORE	= document.getElementById('STAT_BEFORE').value;
		var JOB_STAT 	= document.getElementById('JOB_STAT').value;
		
		if(PR_NOTE == "")
		{
			swal('<?php echo $docNotes; ?>',
			{
				icon: "warning",
			})
			.then(function()
            {
                swal.close();
                $('#PR_NOTE').focus();
            });
			return false;
		}
	
		function getJob(event)
		{
			var chCode = ('charCode' in event) ? event.charCode : event.keyCode;
            alert ("The Unicode character code is: " + chCode);
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
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