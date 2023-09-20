<?php
/* 
 	* Author		= Dian Hermanto
 	* Create Date	= 08 Oktober 2019
 	* File Name	= coa_journal_vd.php
 	* Location		= -
*/
$Periode1 = date('YmdHis');
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
$comp_name 	= $this->session->userdata['comp_name'];
$PRJSCATEG 	= $this->session->userdata['PRJSCATEG'];

$sqlPRJ			= "SELECT PRJCODE, PRJNAME, PRJCOST, isHO FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$resPRJ			= $this->db->query($sqlPRJ)->result();
foreach($resPRJ as $rowPRJ) :
	$PRJCODE 	= $rowPRJ->PRJCODE;
	$PRJNAME 	= $rowPRJ->PRJNAME;
  	$PRJCOST  	= $rowPRJ->PRJCOST;
  	$isHO     	= $rowPRJ->isHO;
endforeach;

$ACC_NAME		= '';
$sqlACCD		= "SELECT Account_NameEn, Account_NameId, Account_Class
                FROM tbl_chartaccount WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_ID'";
$resACCD		= $this->db->query($sqlACCD)->result();
foreach($resACCD as $rowACCD) :
  	$Account_NameEn   = $rowACCD->Account_NameEn;
  	$Account_NameId   = $rowACCD->Account_NameId;
  	$Account_Class    = $rowACCD->Account_Class;
endforeach;
$LangID         = $this->session->userdata['LangID'];
if($LangID == 'IND')
{
  	$ACC_NAME    = $Account_NameId;;
}
else
{
  	$ACC_NAME    = $Account_NameEn;
}

if(($Account_Class == 3 || $Account_Class == 4) && $isHO == 1)
{
    $ADDQRY   = "";
    $ADDQRY1  = "";
}
else
{
    $ADDQRY   = "A.proj_Code = '$PRJCODE' AND";
    $ADDQRY1  = "PRJCODE = '$PRJCODE' AND";
}
$PrintDate 		= date('Y-m-d H:i:s');

$syncPRJ		= '';
$rowSyn 		= 0;
$ColsyncPRJ 	= '';
$sqlISHO 		= "SELECT syncPRJ FROM tbl_chartaccount
					WHERE $ADDQRY1 Account_Number = '$ACC_ID' LIMIT 1";
$resISHO		= $this->db->query($sqlISHO)->result();
foreach($resISHO as $rowISHO):
	$syncPRJ	= $rowISHO->syncPRJ;
endforeach;

$JIDExplode = explode('~', $syncPRJ);
foreach ($JIDExplode as $rowSync)
{
	$rowSyn		= $rowSyn + 1;
	if($rowSyn == 1)
	{
		$syncPRJO	= "$rowSync";
		$ColsyncPRJ	= "$syncPRJO";
	}
	else
	{
		$ColsyncPRJ	= "$ColsyncPRJ','$rowSync";
	}
}
/*if($PRJSCATEG == 1)
	$ADDQRY 	= "A.proj_Code IN ('$ColsyncPRJ') AND";*/

if(($Account_Class == 3 || $Account_Class == 4) && $isHO == 1)
{
    $ADDQRY   = "";
}
else
{
    $ADDQRY 	= "A.proj_Code IN ('$ColsyncPRJ') AND";

    if($PRJSCATEG == 2 || $PRJSCATEG == 3)
    {
	    $ADDQRY   = "";
    }
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/style.css'; ?>");</style>
		<title><?php echo $h1_title; ?></title>
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
    <style>
		body {
			margin: 0;
			padding: 0;
			background-color: #FAFAFA;
			font: 10px; Arial, Helvetica, sans-serif;
		}
		* {
			box-sizing: border-box;
			-moz-box-sizing: border-box;
		}
		.page {
			width: 21cm;
			min-height: 29.7cm;
			padding: 0.5cm;
			margin: 0.5cm auto;
			border: 1px #D3D3D3 solid;
			border-radius: 5px;
			background: white;
			box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
		}
		.subpage {
			padding: 1cm;
			height: 256mm;
		}
    
	    @page {
	        size: A4;
	        margin: 0;
	    }
	    @media print {
	        .page {
	            margin: 0;
	            border: initial;
	            border-radius: initial;
	            width: initial;
	            min-height: initial;
	            box-shadow: initial;
	            background: initial;
	            page-break-after: always;
	        }
	    }
	    
	    .inplabel {border:none;background-color:white;}
	    .inplabelOK {border:none;background-color:white; color:#009933; font-weight:bold}
	    .inplabelBad {border:none;background-color:white; color:#FF0000; font-weight:bold}
	    .inplabelTOT {border:none;background-color:white; color:#06F; font-weight:bold}
	    .inplabelTOTPPn {border:none;background-color:white; color:#933; font-weight:bold}
	    .inplabelGT {border:none;background-color:white; color:#00F; font-weight:bold}
	    .inpdim {border:none;background-color:white;}
  	</style>
	<body style="overflow:auto">
	    <div class="page">
	        <section class="content">
	            <table width="100%" border="0" style="size:auto">
	                <tr>
	                    <td width="100%" class="style2" style="text-align:left;">&nbsp;</td>
	                </tr>
	              	<tr>
	                    <td class="style2" style="text-align:left;">
	                        <table width="100%" style="size:auto; font-size:12px;">
	                            <tr style="text-align:left;">
	                                <td width="23%" nowrap>Perusahaan</td>
	                              	<td width="1%">:</td>
	                                <td style="text-align:left; font-weight:bold"><?php echo strtoupper($PRJNAME); ?></td>
	                          	</tr>
	                            <tr style="text-align:left;">
	                             	<td nowrap>Kode Akun</td>
	                              	<td>:</td>
	                              	<td style="text-align:left;"><?php echo $ACC_ID;?></td>
	                           	</tr>
	                            <tr style="text-align:left;">
	                             	<td nowrap>Nama Akun</td>
	                              	<td>:</td>
	                              	<td><?php echo $ACC_NAME;?></td>
	                           	</tr>
	                            <tr style="text-align:left;">
	                              <td nowrap valign="top">&nbsp;</td>
	                              <td>&nbsp;</td>
	                              <td>&nbsp;</td>
	                            </tr>
	                        </table>
	                    </td>
	                </tr>
	                <tr>
					   	<td style="text-align:left; font-size:12px">
	                        <table width="100%" border="1" style="size:auto; font-size:12px;" rules="all">
	                            <tr style="font-weight:bold; text-align:center; background:#CCC; font-size:12px;">
	                                <td width="3%" style="text-align:center">&nbsp;NO.&nbsp;</td>
	                                <td width="12%" style="text-align:center">KODE</td>
	                                <td width="9%" style="text-align:center">TANGGAL</td>
	                                <td width="7%" style="text-align:center">TIPE</td>
	                                <td width="45%" style="text-align:center">DESKRIPSI</td>
	                                <td width="12%" style="text-align:center" nowrap>DEBIT</td>
	                                <td width="12%" style="text-align:center" nowrap>KREDIT</td>
	                                <td width="12%" style="text-align:center" nowrap>TOTAL</td>
	                            </tr>
	                            <?php
	                            	$no 		= 0;
	                                $sqlOpBal	= "SELECT IF(Base_OpeningBalance = '', 0, Base_OpeningBalance) AS OpBal
	                                				FROM tbl_chartaccount WHERE Account_Number = '$ACC_ID' AND PRJCODE = '$PRJCODE'";
	                                $resOpBal 	= $this->db->query($sqlOpBal)->result();
	                                foreach ($resOpBal as $rowOpBal):
	                                	$Manual_No		= "-";
	                                	$JournalH_Date	= "-";
	                                	$JournalType	= "OPBAL";
	                                	$JournalDesc	= "Opening Balance";
	                                	$BaseDebetOBal	= $rowOpBal->OpBal;
	                                	$BaseKreditOBal	= 0;
	                                	$totAm			= $BaseDebetOBal;
	                                endforeach;
	                            ?>
                                <tr style="font-size:12px">
                                    <td style="text-align:center;" colspan="5"><?php echo $JournalDesc; ?></td>
                                    <td style="text-align:right"><?php echo number_format($BaseDebetOBal,2); ?></td>
                                    <td style="text-align:right"><?php echo number_format($BaseKreditOBal,2); ?></td>
                                    <td style="text-align:right"><?php echo number_format($totAm,2); ?></td>
                                </tr>
	                            <?php
	                                $sqlJD	= "SELECT
	                                                A.JournalH_Code,
	                                                B.JournalType,
	                                                B.Manual_No,
	                                                A.JournalH_Date,
	                                                A.Base_Debet,
	                                                A.Base_Kredit,
	                                                B.JournalH_Desc,
	                                                A.Other_Desc,
	                                                A.Ref_Number
	                                            FROM tbl_journaldetail A
	                                                INNER JOIN tbl_journalheader B ON A.JournalH_Code = B.JournalH_Code
	                                            WHERE
	                                                $ADDQRY
	                                                A.Acc_Id = '$ACC_ID'
	                                                AND B.GEJ_STAT = 3
	                                                ORDER BY B.LastUpdate ASC";
	                                $resJD	= $this->db->query($sqlJD)->result();
	                                foreach($resJD as $rowJD) :
	                                    $no				= $no+1;
	                                    $JournalType 	= $rowJD->JournalType;
	                                    $JournalH_Code 	= $rowJD->JournalH_Code;
	                                    $Manual_No 		= $rowJD->Manual_No;
	                                    $Ref_Number 	= $rowJD->Ref_Number;
	                                    if($Manual_No == '')
	                                        $Manual_No	= $JournalH_Code;

	                                    if($JournalType == 'IR' || $JournalType == 'PINV')
	                                        $Manual_No	= $Ref_Number;


	                                    $JournalH_Date 	= date('d-m-Y', strtotime($rowJD->JournalH_Date));
	                                    $Base_Debet   	= $rowJD->Base_Debet;
	                                    $Base_Kredit  	= $rowJD->Base_Kredit;
	                                    $totAm        	= $totAm + $Base_Debet - $Base_Kredit - $BaseKreditOBal;
	                                    $JournalDesc 	= $rowJD->JournalH_Desc;
	                                    $Other_Desc 	= $rowJD->Other_Desc;
	                                    $JournalDesc	= $Other_Desc;
	                                    if($JournalDesc == '')
	                                    {
	                                        if($JournalType == 'UM')
	                                            $JournalDesc	= "Penggunaan material";
	                                        elseif($JournalType == 'OPN')
	                                            $JournalDesc	= "Opname";
	                                    }
	                                    ?>
	                                    <tr style="font-size:12px">
	                                        <td style="text-align:center"><?php echo $no; ?>.</td>
	                                        <td nowrap style="text-align:center"><?php echo $Manual_No; ?></td>
	                                        <td nowrap style="text-align:center"><?php echo $JournalH_Date; ?></td>
	                                        <td style="text-align:center" nowrap><?php echo $JournalType; ?></td>
	                                        <td style="text-align:left"><?php echo $JournalDesc; ?></td>
	                                        <td style="text-align:right"><?php echo number_format($Base_Debet,2); ?></td>
	                                        <td style="text-align:right"><?php echo number_format($Base_Kredit,2); ?></td>
	                                        <td style="text-align:right"><?php echo number_format($totAm,2); ?></td>
	                                    </tr>
	                                    <?php
	                                endforeach;
	                            ?>
	                        </table>
	           	        </td>
	                </tr>
	            </table>
	        </section>
	    </div>
	</body>
</html>