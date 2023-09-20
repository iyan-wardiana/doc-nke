<?php
/* 
 	* Author   		= Dian Hermanto
 	* Create Date  	= 14 Nopember 2020
 	* File Name  	= v_ledger_report_gl.php
 	* Location   	= -
*/

setlocale(LC_ALL, 'id-ID', 'id_ID');
date_default_timezone_set("Asia/Jakarta");

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$LangID 	= $this->session->userdata['LangID'];

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

// _global function
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;

if($viewType == 1)
{
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=GL_".date('YmdHis').".xls");
	header("Pragma: no-cache");
	header("Expires: 0");
}

$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
$comp_name 	= $this->session->userdata['comp_name'];

if($CFType == 1)
	$CFTyped	= "Detail";
else
	$CFTyped	= "Summary";

if($PRJCODECOL[0] != 1)
{
	$ArrPRJCODE 	= join("','", $PRJCODECOL);
	$addQPRJ 		= "PRJCODE IN ('$ArrPRJCODE')";
	$addQPRJJur 	= "A.proj_Code IN ('$ArrPRJCODE')";
}
else
{
	// get All Project
	$ArrPRJCODE = '';
	$qProject 	= $this->db->select("PRJCODE")->get("tbl_project");
	if($qProject->num_rows() > 0)
	{
		$arrPRJ = [];
		foreach($qProject->result() as $rPRJ):
			$arrPRJ[] = $rPRJ->PRJCODE;
		endforeach;
		$ArrPRJCODE = join("','", $arrPRJ);
	}
	$addQPRJ 	= "PRJCODE IN ('$ArrPRJCODE')";
	$addQPRJJur = "A.proj_Code IN ('$ArrPRJCODE')";
}
	
$sql = "SELECT A.proj_Number, A.PRJCODE, A.PRJCNUM, A.PRJNAME, A.PRJLOCT, A.PRJOWN, A.PRJDATE, A.PRJDATE_CO, A.PRJEDAT,
			A.PRJCOST, A.PRJCATEG,
			A.PRJLKOT, A.PRJCBNG, A.PRJCURR, A.CURRRATE, A.PRJSTAT, A.PRJNOTE, A.ISCHANGE, A.REFCHGNO, A.PRJCOST2, 
			A.PRJ_MNG, A.PRJBOQ,
			A.CHGUSER, A.CHGSTAT, A.PRJPROG, A.QTY_SPYR, A.PRC_STRK, A.PRC_ARST, A.PRC_MKNK, A.PRC_ELCT, A.PRJ_IMGNAME,
			A.isHO
		FROM tbl_project A
		WHERE $addQPRJ";

$resPROJ	= $this->db->query($sql)->result();
foreach($resPROJ as $rowPROJ){
	$PRJCODE	= $rowPROJ->PRJCODE;
	$PRJNAME	= $rowPROJ->PRJNAME;
	$PRJCNUM	= $rowPROJ->PRJCNUM;
	$PRJDATE	= $rowPROJ->PRJDATE;
	$PRJEDAT	= $rowPROJ->PRJEDAT;
	$PRJOWN		= $rowPROJ->PRJOWN;
	$PRJLOCT	= $rowPROJ->PRJLOCT;
	$PRJ_MNG	= $rowPROJ->PRJ_MNG;
	$isHO		= $rowPROJ->isHO;
}
$MNGP_NAME	= '';
if($PRJ_MNG != '')
{
	$sqlMNGP 	= "SELECT First_Name, Last_Name FROM tbl_employee WHERE Emp_ID = '$PRJ_MNG'";
	$resMNGP 	= $this->db->query($sqlMNGP)->result();
	foreach($resMNGP as $rowMNGP) :
		$First_Name = $rowMNGP->First_Name;
		$Last_Name 	= $rowMNGP->Last_Name;
	endforeach;
	$MNGP_NAME	= $First_Name.$Last_Name;
}

$ADDQRY_ACC1 = "";
$ADDQRY_ACC2 = "";
if($sellAccount2 != 0)
{
	$rowACC 	= 0;
	$sqlACC 	= "SELECT Account_Number, Account_NameId, syncPRJ FROM tbl_chartaccount WHERE $addQPRJ AND (ID BETWEEN $sellAccount AND $sellAccount2) AND isLast = 1";
	$resACC 	= $this->db->query($sqlACC)->result();
	foreach($resACC as $rwACC) :
		$rowACC		= $rowACC + 1;
		$Acc_Num 	= $rwACC->Account_Number;
		$ACCSELCOL1 = $Acc_Num;
		if($rowACC == 1)
			$ACCSELCOL = "'$ACCSELCOL1'";
		else
			$ACCSELCOL = "'$ACCSELCOL1', $ACCSELCOL";
	endforeach;
	$ADDQRY_ACC1 	= "AND Account_Number IN ($ACCSELCOL)";
	$ADDQRY_ACC2 	= "AND A.Acc_Id IN ($ACCSELCOL)";
}
elseif($sellAccount != 0)
{
	$rowACC 	= 0;
	$sqlACC 	= "SELECT Account_Number, Account_NameId, syncPRJ FROM tbl_chartaccount WHERE $addQPRJ AND ID = $sellAccount AND isLast = 1";
	$resACC 	= $this->db->query($sqlACC)->result();
	foreach($resACC as $rwACC) :
		$rowACC		= $rowACC + 1;
		$Acc_Num 	= $rwACC->Account_Number;
		$ACCSELCOL1 = $Acc_Num;
		if($rowACC == 1)
			$ACCSELCOL = "'$ACCSELCOL1'";
		else
			$ACCSELCOL = "'$ACCSELCOL1', $ACCSELCOL";
	endforeach;
	$ADDQRY_ACC1 	= "AND Account_Number IN ($ACCSELCOL)";
	$ADDQRY_ACC2 	= "AND A.Acc_Id IN ($ACCSELCOL)";
}

$ACCSELCOL 		= $ADDQRY_ACC1;
$Account_Number = '';
$Account_NameId	= '';
$syncPRJ		= $ACCSELCOL;
$sqlACC 	= "SELECT Account_Number, Account_NameId, syncPRJ FROM tbl_chartaccount WHERE $addQPRJ $ADDQRY_ACC1";
$resACC 	= $this->db->query($sqlACC)->result();
foreach($resACC as $rowACC) :
	$Account_Number = $rowACC->Account_Number;
	$Account_NameId	= $rowACC->Account_NameId;
	$syncPRJ		= $rowACC->syncPRJ;
endforeach;
$tags 		= explode('~' , $syncPRJ);
$TOTPRJ 	= count($tags);

$StartDate	= date('Y-m-d', strtotime($Start_Date));
$EndDate	= date('Y-m-d', strtotime($End_Date . '+ 1 day'));
$StartDateV	= date('d M Y', strtotime($Start_Date));
$EndDateV	= date('d M Y', strtotime($End_Date));

$DrafTTD1   = "white";
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $h1_title; ?></title>
    <link rel="icon" type="image/png" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/favicon/contract.png'; ?>" sizes="32x32">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.0/css/font-awesome.min.css" integrity="sha512-FEQLazq9ecqLN5T6wWq26hCZf7kPqUbFC9vsHNbXMJtSZZWAcbJspT+/NEAQkBfFReZ8r9QlA9JHaAuo28MTJA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <style type="text/css">
    	@page { margin: 0 }
        body { margin: 0 }
        .sheet {
          margin: 0;
          overflow: hidden;
          position: relative;
          box-sizing: border-box;
          page-break-after: always;
        }

        /** Paper sizes **/
        body.A3               .sheet { width: 297mm;}
        body.A3.landscape     .sheet { width: 420mm;}
        body.A4               .sheet { width: 210mm; height: 296mm }
        body.A4.landscape     .sheet { width: 297mm; height: 209mm }
        body.A5               .sheet { width: 148mm; height: 209mm }
        body.A5.landscape     .sheet { width: 210mm; height: 147mm }
        body.letter           .sheet { width: 216mm; height: 279mm }
        body.letter.landscape .sheet { width: 280mm; height: 215mm }
        body.legal            .sheet { width: 216mm; height: 356mm }
        body.legal.landscape  .sheet { width: 357mm; height: 215mm }

        /** Padding area **/
        .sheet.padding-10mm { padding: 10mm }
        .sheet.padding-15mm { padding: 15mm }
        .sheet.padding-20mm { padding: 20mm }
        .sheet.padding-25mm { padding: 25mm }
        .sheet.custom { padding: 1cm 1cm 0.97cm 1cm }

        /** For screen preview **/
        @media screen {
          body { background: #e0e0e0;}
          .sheet {
            background: white;
            box-shadow: 0 .5mm 2mm rgba(0,0,0,.3);
            margin: 5mm auto;
            border-radius: 5px 5px 5px 5px;
          }
        }

        /** Fix for Chrome issue #273306 **/
        @media print {
          @page { size: landscape;}
          body.A3.landscape { width: 420mm }
          body.A3, body.A4.landscape { width: 297mm }
          body.A4, body.A5.landscape { width: 210mm }
          body.A5                    { width: 148mm }
          body.letter, body.legal    { width: 216mm }
          body.letter.landscape      { width: 280mm }
          body.legal.landscape       { width: 357mm }
        }
    </style>
    
</head>
<script src="<?php echo base_url('assets/js/jquery-1.10.1.min.js') ?>" type="text/javascript"></script>

<?php
    if($LangID == 'IND')
    {
        $header     = "BUKU BESAR RINCI";
    }
    else
    {
        $header     = "LEDGER";
    }
?>

<body class="page landscape">
	<style type="text/css">
		.search-table, td, th {
			border-collapse: collapse;
		}
		.search-table-outter { overflow-x: scroll; }
		
	    a[disabled="disabled"] {
	        pointer-events: none;
	    }
	</style>
    <section class="page sheet custom search-table-outter">
    	<table width="100%" border="0" style="size:auto">
		    <tr style="border-top: hidden; border-left: hidden; border-right: hidden; ">
		        <td colspan="3" class="style2" style="text-align:center; font-weight:bold;">
		        	<span class="style2" style="text-align:center; font-weight:bold; font-size:12px"><?php echo $comp_name; ?></span><br>
					<div style="font-weight: bold; font-size: 18px; color: #2E86C1"><?php echo $h1_title; ?></div>
		            <?php echo "$StartDateV - $EndDateV"; ?>
				</td>
		  	</tr>
        	<tr style="display: none;">
            	<td width="10%" nowrap>Nomor &amp; Nama Proyek </td>
                <td width="1%">:</td>
                <td width="84%" nowrap><?php echo "$PRJCODE - $PRJNAME";?></td>
            </tr>
		    <tr style="display: none;">
		        <td colspan="3" class="style2" style="line-height: 2px; border-left: hidden; border-right: hidden; border-bottom: hidden;">&nbsp;</td>
		    </tr>
        	<tr style="display: none;">
        	  	<td width="10%" style="border-left: hidden; border-right: hidden; border-bottom: hidden;">Nama Akun</td>
        	  	<td width="1%" style="border-left: hidden; border-right: hidden; border-bottom: hidden;">:</td>
        	  	<td width="84%" style="border-left: hidden; border-right: hidden; border-bottom: hidden;"><?php echo "$Account_Number - $Account_NameId"; ?></td>
      	  	</tr>
        	<tr>
        	  	<td width="10%" style="border-left: hidden; border-right: hidden; border-bottom: hidden;" nowrap>&nbsp;<!-- Tanggal Cetak --></td>
        	  	<td width="1%" style="border-left: hidden; border-right: hidden; border-bottom: hidden;">&nbsp;</td>
        	  	<td width="84%" style="border-left: hidden; border-right: hidden; border-bottom: hidden; text-align: right; font-style: italic;" nowrap><?php echo date("d M Y H:i:s");?></td>
       	  	</tr>
		    <tr>
		        <td colspan="3" class="style2" style="border-left: hidden; border-right: hidden; border-bottom: hidden;">
		            <table width="100%" border="1" rules="all">
		                <tr style="line-height: 25px;">
							<td width="5%" nowrap style="text-align:center; font-weight:bold; border-top: double; border-bottom: double; border-left: hidden; border-right: hidden;">Tgl. Post.</td>
							<td width="5%" nowrap style="text-align:center; font-weight:bold; border-top: double; border-bottom: double; border-left: hidden; border-right: hidden;">Tgl. Dok.</td>
							<td width="5%" nowrap style="text-align:center; font-weight:bold; border-top: double; border-bottom: double; border-left: hidden; border-right: hidden;">No. Jurnal</td>
							<td width="5%" nowrap style="text-align:left; font-weight:bold; border-top: double; border-bottom: double; border-left: hidden; border-right: hidden;">Akun</td>
							<td width="40%" nowrap style="text-align:left; font-weight:bold; border-top: double; border-bottom: double; border-left: hidden; border-right: hidden;">Keterangan</td>
							<td width="5%" nowrap style="text-align:left; font-weight:bold; border-top: double; border-bottom: double; border-left: hidden; border-right: hidden;">Offset Acct</td>
							<td width="10%" nowrap style="text-align:left; font-weight:bold; border-top: double; border-bottom: double; border-left: hidden; border-right: hidden;">Offset Acct Name</td>
							<td width="10%" nowrap style="text-align:center; font-weight:bold; border-top: double; border-bottom: double; border-left: hidden; border-right: hidden;">Debit</td>
							<td width="10%" nowrap style="text-align:center; font-weight:bold; border-top: double; border-bottom: double; border-left: hidden; border-right: hidden;">Kredit</td>
							<td width="10%" nowrap style="text-align:center; font-weight:bold; border-top: double; border-bottom: double; border-left: hidden; border-right: hidden;">Cumulative Bal.</td>
		                </tr>
					    <tr style="line-height: 4px">
		                  	<td colspan="10" nowrap style="text-align:center; border-left: hidden; border-right: hidden; border-bottom: hidden;">&nbsp;</td>
					    </tr>
		                <?php
							$CASH_OUTTOT	= 0;
							$sqlACC		= "SELECT Account_Class FROM tbl_chartaccount 
												WHERE PRJCODE = '$PRJCODE' $ADDQRY_ACC1";
							$resACC	= $this->db->query($sqlACC)->result();
							foreach($resACC as $rowACC):
								$Account_Class	= $rowACC->Account_Class;
							endforeach;
								
							// CASH IN COLLECT
							$therow			= 0;
							$therow1		= 0;
							$therow2		= 0;
							$CASH_SALDO		= 0;
							$CASH_INTOT		= 0;
							$CASH_OUTTOT	= 0;
							$CASH_TOTD		= 0;
							$CASH_TOTK		= 0;
							
							if($Account_Class == 3 || $Account_Class == 4)
							{
								$ADDQUERY	= "";
							}
							else
							{
								if($TOTPRJ > 1)
								{
									$COLLPRJ1	= $tags[0];
									$COLLPRJ	= '';
									foreach($tags as $i =>$key)
									{
										+$i;
										if($i==0)
											$COLLPRJ = "'$COLLPRJ1'";
										else
											$COLLPRJ = "$COLLPRJ,'$key'";
									}
								}
								//$ADDQUERY	= "A.proj_Code IN ($COLLPRJ) AND";
								$ADDQUERY	= "A.proj_Code = '$PRJCODE' AND";
							}
							if($isHO == 1)
							{
								$ADDQUERY	= "";		// TAMPILKAN SEMUA PROYEK
							}
							
							$sqlJRNDC		= "tbl_journaldetail A
													INNER JOIN tbl_journalheader B ON B.JournalH_Code = A.JournalH_Code
												WHERE 
													$ADDQUERY
													B.JournalH_Date >= '$StartDate'
													AND B.JournalH_Date < '$EndDate'
													AND B.GEJ_STAT = 3
													$ADDQRY_ACC2
													-- AND (A.Base_Debet > 0 OR A.Base_Kredit > 0)
													-- AND A.JournalH_Code IN (SELECT C.JournalH_Code FROM tbl_journaldetail C WHERE C.Acc_Id IN ($ACCSELCOL))
												ORDER BY B.JournalH_Date, B.JournalH_Code ASC";
							$resJRNDC		= $this->db->count_all($sqlJRNDC);
							
							$JCODE2			= "";
							$ACC_NAME 		= "";
							$sqlACC			= "SELECT Account_Number AS ACC_NUMB, Account_NameId AS ACC_NAME FROM tbl_chartaccount 
												WHERE PRJCODE = '$PRJCODE' $ADDQRY_ACC1";
							$resACC			= $this->db->query($sqlACC)->result();
							foreach($resACC as $rowACC):
								$ACC_NUMB	= $rowACC->ACC_NUMB;
								$ACC_NAME	= $rowACC->ACC_NAME;
								?>
	                        		<tr style="font-weight: bold; color: #2E86C1">
										<td nowrap style="text-align:left; border-left: hidden; border-right: hidden; border-bottom: hidden;">Akun</td>
										<td nowrap style="text-align:left; border-left: hidden; border-right: hidden; border-bottom: hidden;" colspan="2">
											<?php echo $ACC_NUMB; ?></td>
										<td nowrap style="text-align:left; border-left: hidden; border-right: hidden; border-bottom: hidden;" colspan="3">
											<?php echo $ACC_NAME; ?></td>
										<td nowrap style="text-align:right; border-left: hidden; border-right: hidden; border-bottom: hidden;">&nbsp;</td>
										<td nowrap style="text-align:right; border-left: hidden; border-right: hidden; border-bottom: hidden;">&nbsp;</td>
										<td nowrap style="text-align:right; border-left: hidden; border-right: hidden; border-bottom: hidden;">&nbsp;</td>
										<td nowrap style="text-align:right; border-left: hidden; border-right: hidden; border-bottom: hidden;">&nbsp;</td>
					                </tr>
	                        	<?php
								$sqlCPRJ	= "SELECT
													A.JournalD_Id,
													B.JournalH_Code,
													B.JournalH_Date,
													B.LastUpdate,
													B.JournalType,
													B.JournalH_Desc,
													B.Reference_Number,
													B.Manual_No,
													A.Acc_Id,
													A.Acc_Id_Cross,
													A.proj_Code,
													A.Base_Debet,
													A.Base_Debet_tax,
													A.Base_Kredit,
													A.Base_Kredit_tax,
													A.ITM_CODE,
													A.ITM_CATEG,
													A.Ref_Number,
													A.Other_Desc
												FROM
													tbl_journaldetail A
												INNER JOIN tbl_journalheader B ON B.JournalH_Code = A.JournalH_Code
												WHERE 
													$ADDQUERY
													B.JournalH_Date >= '$StartDate'
													AND B.JournalH_Date < '$EndDate'
													AND A.Acc_Id = '$ACC_NUMB'
													AND B.GEJ_STAT = 3
												ORDER BY B.JournalH_Date, B.JournalH_Code, A.Base_Kredit ASC";
								$resCPRJ	= $this->db->query($sqlCPRJ)->result();
								foreach($resCPRJ as $rowCPRJ):
									$therow			= $therow + 1;
									$JournalH_Code	= $rowCPRJ->JournalH_Code;
									$JournalH_Date	= $rowCPRJ->JournalH_Date;
									$JournalH_Date1	= date('d.m.y', strtotime($JournalH_Date));
									$LastUpdate		= $rowCPRJ->LastUpdate;
									$LastUpdate1	= date('d.m.y', strtotime($LastUpdate));
									$JournalType	= $rowCPRJ->JournalType;
									$JournalH_Desc	= $rowCPRJ->JournalH_Desc;
									//$Refer_Number	= $rowCPRJ->Reference_Number;
									$proj_Code		= $rowCPRJ->proj_Code;
									$Manual_No		= $rowCPRJ->Manual_No;
									$JCODE1			= str_replace($proj_Code, '', $Manual_No);
									$JCODE 			= str_replace("--", '.', $JCODE1);
									$Acc_Id			= $rowCPRJ->Acc_Id;
									$Acc_Id_Crs		= $rowCPRJ->Acc_Id_Cross;
									$Base_Debet		= $rowCPRJ->Base_Debet;
									$Base_Debet_tax	= $rowCPRJ->Base_Debet_tax;
									$Base_Kredit	= $rowCPRJ->Base_Kredit;
									$Base_Kredit_tax= $rowCPRJ->Base_Kredit_tax;
									$ITM_CODE		= $rowCPRJ->ITM_CODE;
									$ITM_CATEG		= $rowCPRJ->ITM_CATEG;
									$Ref_Number		= $rowCPRJ->Ref_Number;
									$Other_Desc		= $rowCPRJ->Other_Desc;

									if($JournalH_Desc == '')
										$JournalH_Desc	= $Other_Desc;
									
									$CASH_INTOT		= $Base_Debet + $Base_Debet_tax;
									$CASH_OUTTOT	= $Base_Kredit + $Base_Kredit_tax;
									$CASH_SALDO		= $CASH_SALDO + $Base_Debet + $Base_Debet_tax - $Base_Kredit - $Base_Kredit_tax;
									$CASH_TOTD		= $CASH_TOTD + $CASH_INTOT;
									$CASH_TOTK		= $CASH_TOTK + $CASH_OUTTOT;

									$AccID 			= "";
									$sqlACC 		= "SELECT Acc_Id as AccID FROM tbl_journaldetail
														WHERE proj_Code = '$proj_Code' AND JournalH_Code = '$JournalH_Code'
															AND Acc_Id_Cross = '$Acc_Id' AND Acc_Id != '$Acc_Id'";
									$resACC			= $this->db->query($sqlACC)->result();
									foreach($resACC as $rowAcc):
										$AccID		= $rowAcc->AccID;
									endforeach;

									$AccCrNm 		= "";
									$sqlACC_Cr 		= "SELECT Account_NameEn FROM tbl_chartaccount
														WHERE PRJCODE = '$proj_Code' AND Account_Number = '$AccID' LIMIT 1";
									$resACC_Cr		= $this->db->query($sqlACC_Cr)->result();
									foreach($resACC_Cr as $row_Cr):
										$AccCrNm	= $row_Cr->Account_NameEn;
									endforeach;

									if($JCODE == $JCODE2)
										$JCODE 	= "";
									?>
									<tr>
										<td nowrap style="text-align:left; border-left: hidden; border-right: hidden; border-bottom: hidden;"><?php echo $LastUpdate1; ?></td>
										<td nowrap style="text-align:left; border-left: hidden; border-right: hidden; border-bottom: hidden;"><?php echo $JournalH_Date1; ?></td>
										<td nowrap style="text-align:left; border-left: hidden; border-right: hidden; border-bottom: hidden;"><?php echo $Manual_No; ?></td>
										<td nowrap style="text-align:left; border-left: hidden; border-right: hidden; border-bottom: hidden;"><?php echo $Acc_Id; ?></td>
										<td style="text-align:left; border-left: hidden; border-right: hidden; border-bottom: hidden;"><?php echo $JournalH_Desc; ?></td>
										<td nowrap style="text-align:left; border-left: hidden; border-right: hidden; border-bottom: hidden;"><?php echo $AccID; ?></td>
										<td nowrap style="text-align:left; border-left: hidden; border-right: hidden; border-bottom: hidden;"><?php echo $AccCrNm; ?></td>
										<td nowrap style="text-align:right; border-left: hidden; border-right: hidden; border-bottom: hidden;"><?php echo number_format($CASH_INTOT, 2); ?></td>
										<td nowrap style="text-align:right; border-left: hidden; border-right: hidden; border-bottom: hidden;"><?php echo number_format($CASH_OUTTOT, 2); ?></td>
										<td nowrap style="text-align:right; border-left: hidden; border-right: hidden; border-bottom: hidden;"><?php echo number_format($CASH_SALDO, 2); ?></td>
									</tr>
			                        <?php
			                        $JCODE2	= $JCODE;
								endforeach;
							endforeach;
						?>
					    <tr style="line-height: 4px">
		                  	<td colspan="6" nowrap style="text-align:center; border-left: hidden; border-right: hidden;">&nbsp;</td>
			                <td nowrap style="text-align:right; font-weight:bold; border-left: hidden; border-right: hidden;">&nbsp;</td>
			                <td nowrap style="text-align:right; font-weight:bold; border-left: hidden; border-right: hidden;">&nbsp;</td>
							<td nowrap style="text-align:right; font-weight:bold; border-left: hidden; border-right: hidden;">&nbsp;</td>
		                  	<td nowrap style="text-align:right; font-weight:bold; border-left: hidden; border-right: hidden;">&nbsp;</td>
					    </tr>
		              	<tr style="border-top: double; border-bottom: double; display: none;">
		                  	<td colspan="6" nowrap style="text-align:center; border-left: hidden; border-right: hidden;">&nbsp;</td>
			                <td nowrap style="text-align:right; font-weight:bold; border-right: hidden;"><?php echo number_format($CASH_TOTD, 2); ?></td>
			                <td nowrap style="text-align:right; font-weight:bold; border-right: hidden;"><?php echo number_format($CASH_TOTK, 2); ?></td>
							<td nowrap style="text-align:right; font-weight:bold; border-right: hidden;"><?php echo number_format($CASH_TOTK, 2); ?></td>
		                  	<td nowrap style="text-align:right; font-weight:bold; border-right: hidden;"><?php echo number_format($CASH_SALDO, 2); ?></td>
					  	</tr>
		                <tr style="line-height:2px;">
		                    <td colspan="10" nowrap style="text-align:center; font-style:italic; border-left:hidden; border-right:hidden; border-bottom:hidden">&nbsp;</td>
		                </tr>
		            </table>
		            <table width="100%" border="1" rules="all" style="display:none">
		                <tr>
		                    <td width="21%">
		                    	Dibuat oleh: Adm. Um. &amp; Keu. Proyek<br><br>
		                        Paraf<br><br>
		                        Tanggal
		                    </td>
		                    <td width="21%">
		                    	Diperiksa Oleh : Kepala Proyek<br><br>
		                        Paraf<br><br>
		                        Tanggal
		                    </td>
		                    <td width="21%">
		                    	Diverifikasi Oleh : Keuangan Pusat<br><br>
		                        Paraf<br><br>
		                        Tanggal
		                    </td>
		                    <td width="21%">
		                    	Disetujui Oleh :  Direktur Operasional<br><br>
		                        Paraf<br><br>
		                        Tanggal
		                    </td>
		                    <td width="17%">
		                    	No. Form : 16.R0/PRO/keu/17<br><br>
		                        Revisi ke : 0<br><br>
		                        Tanggal  : <?php echo date("d M Y", strtotime("now"));?>
		                    </td>
		              </tr>
		          	</table>
			  	</td>
		    </tr>
		</table>
    </div>
</body>

</html>

<!-- jQuery 2.2.3 -->
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/jQuery/jquery-2.2.3.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/bootstrap/js/bootstrap.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/jquery.dataTables.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/dataTables.bootstrap.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/slimScroll/jquery.slimscroll.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/fastclick/fastclick.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE/dist/js/demo.js'; ?>"></script>
<script>
  $(function () 
  {
    $("#example1").DataTable();
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false
    });
  });
</script>

<?php
//$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>