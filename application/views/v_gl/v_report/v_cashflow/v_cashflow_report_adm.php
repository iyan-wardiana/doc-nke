<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 15 Maret 2017
 * File Name	= v_cashflow_report_adm.php
 * Location		= -
*/
if($viewType == 1)
{
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=exceldata.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
}
//echo ".<br>..<br>...<br><br>Sorry this page is under construction.<br>
//By. DIAN HERMANTO - IT Department.<br><br><br>";
//return false;
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
if($TOTPROJ == 1)
{
	$sql 		= "SELECT A.PRJCODE, A.PRJNAME FROM tbl_project A 
					WHERE A.PRJCODE IN ($PRJCODECOL)
					ORDER BY A.PRJCODE";
	$result 	= $this->db->query($sql)->result();
	foreach($result as $row) :
		$PRJCODED 	= $row ->PRJCODE;
		$PRJNAMED 	= $row ->PRJNAME;
	endforeach;
	$PRJCODECOLL	= "$PRJCODED";
	$PRJNAMECOLL	= "$PRJNAMED";
}
else
{
	$PRJCODED	= 'Multi Project Code';
	$PRJNAMED 	= 'Multi Project Name';
	$myrow		= 0;
	$sql 		= "SELECT A.PRJCODE, A.PRJNAME FROM tbl_project A 
					WHERE A.PRJCODE IN ($PRJCODECOL)
					ORDER BY A.PRJCODE";
	$result 	= $this->db->query($sql)->result();
	foreach($result as $row) :
		$myrow		= $myrow + 1;
		$PRJCODED 	= $row ->PRJCODE;
		$PRJNAMED 	= $row ->PRJNAME;
		if($myrow == 1)
		{
			$PRJCODECOLL	= "$PRJCODED";
			$PRJCODECOL1	= "$PRJCODED";
			$PRJNAMECOLL	= "$PRJNAMED";
			$PRJNAMECOL1	= "$PRJNAMED";
		}
		if($myrow > 1)
		{
			$PRJCODECOL1	= "$PRJCODECOL1, $PRJCODED";
			$PRJCODECOLL	= "$PRJCODECOL1";
			$PRJNAMECOL1	= "$PRJNAMECOL1, $PRJNAMED";
			$PRJNAMECOLL	= "$PRJNAMECOL1";
		}		
	endforeach;
	//return false;
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/style.css'; ?>");</style>
  <title><?php echo $h2_title; ?></title>
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
<body>
<section class="content">
    <table width="100%" border="0" style="size:auto">
    <tr>
        <td width="19%">
            <div id="Layer1">
                <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();">
                <img src="<?php echo base_url('assets/AdminLTE-2.0.5/dist/img/print.gif');?>" border="0" alt="" align="absmiddle">Print</a>
                <a href="#" onClick="window.close();" class="button"> close </a>                </div>            </td>
        <td width="42%" class="style2" style="text-align:center; font-weight:bold">&nbsp;</td>
        <td width="39%" class="style2" style="text-align:center; font-weight:bold">&nbsp;</td>
    </tr>
    <tr>
        <td class="style2" style="text-align:left; font-weight:bold;">&nbsp;</td>
        <td class="style2">&nbsp;</td>
        <td class="style2" style="text-align:left; font-weight:bold">&nbsp;</td>
    </tr>
    <tr>
        <td rowspan="2" valign="top" class="style2" style="text-align:left; font-weight:bold;"><img src="<?php echo base_url('assets/AdminLTE-2.0.5/dist/img/compLog/compLog.jpg'); ?>" width="181" height="44"></td>
        <td colspan="2" class="style2" style="text-align:center; font-weight:bold; text-transform:uppercase; font-size:12px">PROJECT CASH FLOW</td>
  </tr>
    <tr>
        <td colspan="2" valign="top" class="style2" style="text-align:center; font-weight:bold; font-size:12px"><span class="style2" style="text-align:center; font-weight:bold; font-size:12px">PT SASMITO INFRA</span></td>
    </tr>
        <?php
            $StartDate1 = date('Y/m/d',strtotime($Start_Date));
            $EndDate1 = date('Y/m/d',strtotime($End_Date));
            $End_Date = date('Y-m-d',strtotime($End_Date));
        ?>
    <tr>
        <td colspan="3" class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="3" class="style2" style="text-align:left; font-style:italic">Report Until Date : <?php /*?><?php echo $StartDate1; ?> to <?php */?><?php echo $EndDate1; ?></td>
    </tr>
    <tr>
        <td colspan="3" class="style2" style="text-align:left; font-style:italic">
            <table width="100%">
                <tr style="text-align:left; font-style:italic">
                    <td width="8%" nowrap valign="top">PROJ. CODE</td>
                    <td width="1%">:</td>
                    <td width="91%"><span class="style2" style="text-align:left; font-style:italic"><?php echo "$PRJCODECOLL"; ?></span></td>
                </tr>
                <tr style="text-align:left; font-style:italic">
                  <td nowrap valign="top">PROJ. NAME</td>
                  <td>:</td>
                  <td><span class="style2" style="text-align:left; font-style:italic"><?php echo $PRJNAMECOLL;?></span></td>
              </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="3" class="style2" style="text-align:center"><hr /></td>
    </tr>
    <tr>
        <td colspan="3" class="style2">
            <table width="100%" border="1" rules="all">
                <tr style="background:#CCCCCC">
                    <td width="23%" rowspan="2" nowrap style="text-align:center; font-weight:bold">Project Name</td>
                    <td width="7%" rowspan="2" nowrap style="text-align:center; font-weight:bold">
                    <?php
                        if($CFType == 2) // DETAIL
                        {
                            echo "Description";
                        }
                        else
                        {
                            echo "Cash Flow /<br>Acc. Code";
                        }
                    ?>                        </td>
                    <td width="48%" rowspan="2" nowrap style="text-align:center; font-weight:bold">COA Description</td>
                    <td colspan="2" nowrap style="text-align:center; font-weight:bold">Nominal (IDR)</td>
                </tr>
                <tr style="background:#CCCCCC">
                    <td width="11%" nowrap style="text-align:center; font-weight:bold">Debit</td>
                    <td width="11%" nowrap style="text-align:center; font-weight:bold">Kredit</td>
                </tr>
                <?php
                // ------------------------------- CASH IN -------------------------------  //
                if($CFType == 2)	// DETAIL
                {
                    // JIKA DETAIL
                    $CASHINA	= 0;
                    $CASHINB	= 0;
                    
                    // GET CASH IN FROM votbdt UNTUK DP
                    $CASHINDP	= 0;
                    $sqlqa 				= "tbl_cf_report_in A
                                                LEFT JOIN cf_votbhd B ON A.VOCCODE = B.VOCCODE
                                                AND B.PRJCODE IN ($PRJCODECOL)
                                            WHERE ACCCODE = '11410'
                                                AND A.PJTCODE IN ($PRJCODECOL)
                                                AND A.CFLCODE = 18
                                                AND B.TRXDATE <= '$End_Date'";
                    $ressqlqa			= $this->db->count_all($sqlqa);
                    if($ressqlqa > 0)
                    {
                        $sqlqb 		= "SELECT A.VOCCODE, A.TRXDATE, A.ACCCODE, A.ACCDESC, A.PJTCODE, A.PRJNAME, A.LPODESC, A.CSTCOST
                                            FROM tbl_cf_report_in A
                                        WHERE ACCCODE = '11410'
                                            AND A.PJTCODE IN ($PRJCODECOL)
                                            AND A.CFLCODE = 18
                                            ORDER BY A.PJTCODE";
                        $resqb 		= $this->db->query($sqlqb)->result();
                        foreach($resqb as $rowqb) :
                            $VOCCODE 	= $rowqb->VOCCODE;
                            $TRXDATE 	= $rowqb->TRXDATE;
                            $ACCCODE 	= $rowqb->ACCCODE;
							$ACCDESC	= $rowqb->ACCDESC;
                            $PJTCODEc 	= $rowqb->PJTCODE;
                            $PRJNAMEc 	= $rowqb->PRJNAME;
                            $LPODESC 	= $rowqb->LPODESC;
                            $CSTCOST 	= $rowqb->CSTCOST;
                            $CASHINDP	= $CASHINDP + $CSTCOST;
                            ?>
                                <tr>
                                    <td nowrap style="text-align:left;"><?php echo "$PJTCODEc - $PRJNAMEc"; ?></td>
                                    <td nowrap style="text-align:left;">
                                    <?php
                                        echo "$LPODESC";
                                    ?>                                    </td>
                                    <td nowrap style="text-align:left;"><?php echo "$ACCCODE - $ACCDESC"; ?></td>
                                    <td nowrap style="text-align:right;"><?php echo number_format($CSTCOST, $decFormat); ?></td>
                                    <td nowrap style="text-align:right;">&nbsp;</td>
                                </tr>
                            <?php
                        endforeach;
                        ?>
                            <tr style="background-color:#D4D4D4; font-style:italic; font-weight:bold;">
                                <td colspan="3" nowrap style="text-align:left;">SUB TOTAL - DP</td>
                                <td nowrap style="text-align:right;"><?php echo number_format($CASHINDP, $decFormat); ?></td>
                                <td nowrap style="text-align:right;">&nbsp;</td>
                            </tr>
                        <?php
                    }
                    
                    // GET CASH IN FROM votbdt UNTUK NON-DP
                    $TRXDATE 	= '';
                    $sqlqe 		= "SELECT A.VOCCODE, A.TRXDATE, A.ACCCODE, A.ACCDESC, A.PJTCODE, A.PRJNAME, A.LPODESC, A.CSTCOST
                                        FROM tbl_cf_report_in A
                                    WHERE ACCCODE = '11410'
                                        AND A.PJTCODE IN ($PRJCODECOL)
                                        AND A.CFLCODE != 18
                                        ORDER BY A.PJTCODE";
                    $resqe 		= $this->db->query($sqlqe)->result();
                    foreach($resqe as $rowqe) :
                        $VOCCODE 	= $rowqe->VOCCODE;
                        $TRXDATE 	= $rowqe->TRXDATE;
                        $ACCCODE 	= $rowqe->ACCCODE;
                        $ACCDESC 	= $rowqe->ACCDESC;
                        $PJTCODEf 	= $rowqe->PJTCODE;
                        $PRJNAMEf 	= $rowqe->PRJNAME;
                        $LPODESC 	= $rowqe->LPODESC;
                        $CSTCOST 	= $rowqe->CSTCOST;
                        $CASHINA	= $CASHINA + $CSTCOST;
                        ?>
                            <tr>
                                <td nowrap style="text-align:left;"><?php echo "$PJTCODEf - $PRJNAMEf"; ?></td>
                                <td nowrap style="text-align:left;">
                                <?php
                                    echo "$LPODESC";
                                ?>                                    </td>
                                <td nowrap style="text-align:left;"><?php echo "$ACCCODE - $ACCDESC"; ?></td>
                                <td nowrap style="text-align:right;"><?php echo number_format($CSTCOST, $decFormat); ?></td>
                                <td nowrap style="text-align:right;">&nbsp;</td>
                            </tr>
                        <?php
                    endforeach;
                    
                    // GET CASH IN FROM TRXBANK
                    $sqlqh 		= "SELECT JRNCODE, TRBDATE, CFLCODE, CFLDESC, PRJCODE, PRJNAME, TRBCOST 
									FROM tbl_cf_report_in 
                                    WHERE CFLCODE IN ('12','13','14','15','16','17','18','19') 
                                    AND PRJCODE IN ($PRJCODECOL)
                                    AND TRBDATE <= '$End_Date'
                                        GROUP BY CFLCODE";
                    $resqh 		= $this->db->query($sqlqh)->result();
                    foreach($resqh as $rowqh) :
                        $JRNCODE 	= $rowqh->JRNCODE;
                        $TRBDATE 	= $rowqh->TRBDATE;
                        $CFLCODE 	= $rowqh->CFLCODE;
                        $CFLDESC 	= $rowqh->CFLDESC;
                        $PRJCODEB 	= $rowqh->PRJCODE;
                        $PRJCODEj 	= $rowqh->PRJCODE;
                        $PRJNAMEj 	= $rowqh->PRJNAME;
                        $TRBCOST 	= $rowqh->TRBCOST;
                        $CASHINB	= $CASHINB + $TRBCOST;
                        ?>
                            <tr>
                                <td nowrap style="text-align:left;"><?php echo "$PRJCODEj - $PRJNAMEj"; ?></td>
                                <td nowrap style="text-align:left;"><?php echo $JRNCODE; ?></td>
                                <td nowrap style="text-align:left;"><?php echo "$CFLCODE - $CFLDESC"; ?></td>
                                <td nowrap style="text-align:right;"><?php echo number_format($TRBCOST, $decFormat); ?></td>
                                <td nowrap style="text-align:right;">&nbsp;</td>
                            </tr>
                        <?php
                    endforeach;
                    $CASHINAB	= $CASHINA + $CASHINB;
                    $CASHINTOTA	= $CASHINDP + $CASHINA + $CASHINB;
                    ?>
                        <tr style="background-color:#D4D4D4; font-style:italic; font-weight:bold;">
                            <td colspan="3" nowrap style="text-align:left;">SUB TOTAL - TERMIN</td>
                            <td nowrap style="text-align:right;"><?php echo number_format($CASHINAB, $decFormat); ?></td>
                            <td nowrap style="text-align:right;">&nbsp;</td>
                        </tr>
                        <tr style="background-color:#C4C4C4">
                            <td colspan="3" nowrap style="text-align:left; font-weight:bold">CASH IN TOTAL</td>
                            <td nowrap style="text-align:right; font-weight:bold"><?php echo number_format($CASHINTOTA, $decFormat); ?></td>
                            <td nowrap style="text-align:right;">&nbsp;</td>
                        </tr>
                    <?php
                    $CASHINTOTX	= $CASHINTOTA;
                }
                else 				// SUMMARY
                {
                    // JIKA DETAIL
                    $CASHINA	= 0;
                    $CASHINB	= 0;
                    
                    // GET CASH IN FROM votbdt UNTUK DP
                    $CASHINDP	= 0;
                    $sqlqa 				= "tbl_cf_report_in A
                                                -- LEFT JOIN cf_votbhd B ON A.VOCCODE = B.VOCCODE
                                                -- B.PRJCODE IN ($PRJCODECOL)
                                            WHERE ACCCODE = '11410'
                                                AND A.PJTCODE IN ($PRJCODECOL)
                                                AND A.CFLCODE = 18";
                   $ressqlqa			= $this->db->count_all($sqlqa);
				   $ressqlqa			= 0;
                    if($ressqlqa > 0)
                    {
                        $sqlqb 		= "SELECT A.VOCCODE, B.TRXDATE, A.ACCCODE, A.PJTCODE, A.LPODESC, A.CSTCOST 
                                            FROM tbl_cf_report_in A
                                            LEFT JOIN cf_votbhd B ON A.VOCCODE = B.VOCCODE
                                            AND B.PRJCODE IN ($PRJCODECOL)
                                        WHERE ACCCODE = '11410'
                                            AND A.PJTCODE IN ($PRJCODECOL)
                                            AND A.CFLCODE = 18
                                            ORDER BY A.PJTCODE";
                        $resqb 		= $this->db->query($sqlqb)->result();
                        foreach($resqb as $rowqb) :
                            $VOCCODE 	= $rowqb->VOCCODE;
                            $TRXDATE 	= $rowqb->TRXDATE;
                            $ACCCODE 	= $rowqb->ACCCODE;
                            $PJTCODE 	= $rowqb->PJTCODE;
                            $LPODESC 	= $rowqb->LPODESC;
                            $CSTCOST 	= $rowqb->CSTCOST;
                            $CASHINDP	= $CASHINDP + $CSTCOST;
                            
                            $sqlqc 		= "SELECT PRJCODE, PRJNAME FROM tbl_project WHERE PRJCODE = '$PJTCODE'";
                            $resqc 		= $this->db->query($sqlqc)->result();
                            foreach($resqc as $rowqc) :
                                $PRJCODEc 	= $rowqc->PRJCODE;
                                $PRJNAMEc 	= $rowqc->PRJNAME;
                            endforeach;
                            
                            $sqlqd 		= "SELECT ACCDESC FROM chart WHERE ACCCODE = '$ACCCODE'";
                            $resqd 		= $this->db->query($sqlqd)->result();
                            foreach($resqd as $rowqd) :
                                $ACCDESC 	= $rowqd->ACCDESC;
                            endforeach;
                        endforeach;
                        ?>
                            <tr>
                                <td nowrap style="text-align:left;">&nbsp;</td>
                                <td nowrap style="text-align:left;">18</td>
                                <td nowrap style="text-align:left;">UM TERMIN</td>
                                <td nowrap style="text-align:right;"><?php echo number_format($CASHINDP, $decFormat); ?></td>
                                <td nowrap style="text-align:right;">&nbsp;</td>
                            </tr>
                        <?php
                    }
                    
                    
                    /*$ACCCODE 		= "11";
                    $sqlq6a 		= "SELECT CFLDESC FROM cashflow WHERE CFLCODE = '11'";
                    $resq6a 		= $this->db->query($sqlq6a)->result();
                    foreach($resq6a as $rowq6a) :
                        $CFLDESC6a 	= $rowq6a->CFLDESC;
                    endforeach;*/
					$CFLDESC6a	= '';
                    
                    // GET CASH IN FROM votbdt UNTUK NON-DP
                    $sqlqe 		= "SELECT A.VOCCODE, A.ACCCODE, A.PJTCODE, A.LPODESC, A.CSTCOST 
                                        FROM tbl_cf_report_in A
                                    WHERE ACCCODE = '11410'
                                        AND A.PJTCODE IN ($PRJCODECOL)
                                        AND A.CFLCODE != 18
                                        ORDER BY A.PJTCODE";
                    $resqe 		= $this->db->query($sqlqe)->result();
                    foreach($resqe as $rowqe) :
                        $VOCCODE 	= $rowqe->VOCCODE;
                        $ACCCODE 	= $rowqe->ACCCODE;
                        $PJTCODE 	= $rowqe->PJTCODE;
                        $LPODESC 	= $rowqe->LPODESC;
                        $CSTCOST 	= $rowqe->CSTCOST;
                        $CASHINA	= $CASHINA + $CSTCOST;
                        
                        $sqlqf 		= "SELECT PRJCODE, PRJNAME FROM tbl_project WHERE PRJCODE = '$PJTCODE'";
                        $resqf 		= $this->db->query($sqlqf)->result();
                        foreach($resqf as $rowqf) :
                            $PRJCODEf 	= $rowqf->PRJCODE;
                            $PRJNAMEf 	= $rowqf->PRJNAME;
                         endforeach;
                        
                        $sqlqg 		= "SELECT ACCDESC FROM chart WHERE ACCCODE = '$ACCCODE'";
                        $resqg 		= $this->db->query($sqlqg)->result();
                        foreach($resqg as $rowqg) :
                            $ACCDESC 	= $rowqg->ACCDESC;
                        endforeach;
                        ?>
                            <tr>
                                <td nowrap style="text-align:left;"><?php echo "$PRJCODEf - $PRJNAMEf"; ?></td>
                                <td nowrap style="text-align:left;">
                                <?php
                                    echo "$VOCCODE";
                                ?>                                    </td>
                                <td nowrap style="text-align:left;"><?php echo "$ACCCODE - $ACCDESC"; ?></td>
                                <td nowrap style="text-align:right;"><?php echo number_format($CSTCOST, $decFormat); ?></td>
                                <td nowrap style="text-align:right;">&nbsp;</td>
                            </tr>
                        <?php
                    endforeach;
                    
                    // GET CASH IN FROM TRXBANK
                    $sqlqh 		= "SELECT JRNCODE, TRBDATE, CFLCODE, PRJCODE, TRBCOST 
									FROM tbl_cf_report_in
                                    WHERE CFLCODE IN ('12','13','14','15','16','17','18','19')
                                    AND PRJCODE IN ($PRJCODECOL)
                                    AND TRBDATE <= '$End_Date'
                                        GROUP BY CFLCODE";
                    $resqh 		= $this->db->query($sqlqh)->result();
                    foreach($resqh as $rowqh) :
                        $JRNCODE 	= $rowqh->JRNCODE;
                        $TRBDATE 	= $rowqh->TRBDATE;
                        $CFLCODE 	= $rowqh->CFLCODE;
                        $PRJCODEB 	= $rowqh->PRJCODE;
                        $PJTCODE 	= $rowqh->PRJCODE;
                        $TRBCOST 	= $rowqh->TRBCOST;
                        $CASHINB	= $CASHINB + $TRBCOST;

                        
                        $sqlqi 		= "SELECT CFLDESC FROM cashflow WHERE CFLCODE = '$CFLCODE'";
                        $resqi 		= $this->db->query($sqlqi)->result();
                        foreach($resqi as $rowqi) :
                            $CFLDESC 	= $rowqi->CFLDESC;
                         endforeach;
                        $sqlqj 		= "SELECT PRJCODE, PRJNAME FROM tbl_project WHERE PRJCODE = '$PJTCODE'";
                        $resqj 		= $this->db->query($sqlqj)->result();
                        foreach($resqj as $rowqj) :
                            $PRJCODEj 	= $rowqj->PRJCODE;
                            $PRJNAMEj 	= $rowqj->PRJNAME;
                         endforeach;
                        ?>
                            <tr>
                                <td nowrap style="text-align:left;"><?php echo "$PRJCODEj - $PRJNAMEj"; ?></td>
                                <td nowrap style="text-align:left;"><?php echo $CFLCODE; ?></td>
                                <td nowrap style="text-align:left;"><?php echo "$CFLCODE - $CFLDESC"; ?></td>
                                <td nowrap style="text-align:right;"><?php echo number_format($TRBCOST, $decFormat); ?></td>
                                <td nowrap style="text-align:right;">&nbsp;</td>
                            </tr>
                        <?php
                    endforeach;
                    $CASHINAB		= $CASHINA + $CASHINB;
                    $CASHINTOTB		= $CASHINDP + $CASHINA + $CASHINB;
                    $CASHINTOTBNPPN	= $CASHINTOTB / 1.1; // TOTAL ASLI TANPA PPN
                    $CASHINTOTBPPN	= 0.1 * $CASHINTOTBNPPN; // TOTAL PPN
                    $CASHINTOTPPH	= $CASHINTOTBNPPN * 0.03;
                    $CASHINTOTNET	= $CASHINTOTBNPPN - $CASHINTOTPPH;
                    ?>
                    <tr style="background-color:#C4C4C4">
                        <td colspan="3" nowrap style="text-align:left; font-weight:bold">CASH IN TOTAL ( PPn, PPH)</td>
                        <td nowrap style="text-align:right; font-weight:bold"><?php echo number_format($CASHINTOTB, $decFormat); ?>
                        </td>
                        <td nowrap style="text-align:right;">&nbsp;</td>
                    </tr>
                    <tr style="background-color:#DDDDDD">
                        <td colspan="3" nowrap style="text-align:left; font-weight:bold">PPn (10%)</td>
                        <td nowrap style="text-align:right; font-weight:bold"><?php echo number_format($CASHINTOTBPPN, $decFormat); ?>
                        </td>
                        <td nowrap style="text-align:right;">&nbsp;</td>
                    </tr>
                    <tr style="background-color:#DDDDDD">
                        <td colspan="3" nowrap style="text-align:left; font-weight:bold">PPH ( 3% )</td>
                        <td nowrap style="text-align:right; font-weight:bold"><?php echo number_format($CASHINTOTPPH, $decFormat); ?></td>
                      <td nowrap style="text-align:right;">&nbsp;</td>
                    </tr>
                    <tr style="background-color:#C4C4C4">
                        <td colspan="3" nowrap style="text-align:left; font-weight:bold">CASH IN NET</td>
                        <td nowrap style="text-align:right; font-weight:bold"><?php echo number_format($CASHINTOTNET, $decFormat); ?>
                        </td>
                        <td nowrap style="text-align:right;">&nbsp;</td>
                    </tr>
                    <?php						
                    $CASHINTOTX	= $CASHINTOTB;
                }
                
                // ------------------------------- CASH OUT -------------------------------  //
                if($CFType == 2) 	// DETAIL
                {
                    // JIKA DETAIL
                    $CASHOUTA	= 0;
                    $CASHOUTSAL	= 0;
                    $CASHOUTAZ	= 0;
                    // 1. HITUNG PENGELUARAN GAJI
                        $sqlq8 	= "SELECT A.CSTCOST 
                                    FROM cf_vocdt A
                                        LEFT JOIN cf_vochd B ON A.VOCCODE = B.VOCCODE
                                    WHERE A.ACCCODE IN ('42411','42413')
                                        AND A.PJTCODE IN ($PRJCODECOL)
                                        AND B.TRXDATE <= '$End_Date'";
                        $resq8 	= $this->db->query($sqlq8)->result();
                        foreach($resq8 as $rowq8) :
                            $CSTCOST8	= $rowq8->CSTCOST;
                            $CASHOUTSAL	= $CASHOUTSAL + $CSTCOST8;
                        endforeach;
                    // 2. GET CASH OUT FROM TRXBANK
                    $sqlq8a 	= "SELECT JRNCODE, TRBDATE, CFLCODE, PRJCODE, TRBCOST
									FROM cf_trxbank_out
                                    WHERE CFLCODE IN ('20','21','22','23','24','25','26','27','28','29','30','31','32','33','34','35','36','37','38','39')
                                        AND PRJCODE IN ($PRJCODECOL)
                                        AND TRBDATE <= '$End_Date'
                                    ORDER BY TRBDATE";
                    $resq8a 	= $this->db->query($sqlq8a)->result();
                    foreach($resq8a as $rowq8a) :
                        $JRNCODE8a 	= $rowq8a->JRNCODE;
                        $TRBDATE8a 	= $rowq8a->TRBDATE;
                        $CFLCODE8a 	= $rowq8a->CFLCODE;
                        $PRJCODE8a 	= $rowq8a->PRJCODE;
                        $TRBCOST8a 	= $rowq8a->TRBCOST;
                        
                        $sqlq9 		= "SELECT CFLDESC FROM cashflow WHERE CFLCODE = '$CFLCODE8a'";
                        $resq9 		= $this->db->query($sqlq9)->result();
                        foreach($resq9 as $rowq9) :
                            $CFLDESC9 	= $rowq9->CFLDESC;
                         endforeach;
                        
                        $sqlq8b 		= "SELECT PRJCODE, PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE8a'";
                        $resq8b 		= $this->db->query($sqlq8b)->result();
                        foreach($resq8b as $rowq8b) :
                            $PRJCODE8b 	= $rowq8b->PRJCODE;
                            $PRJNAME8b 	= $rowq8b->PRJNAME;
                         endforeach;
                        
                        $CASHOUTA	= $CASHOUTA + $TRBCOST8a;
                        ?>
                            <tr>
                                <td nowrap style="text-align:left;"><?php echo "$PRJCODE8a - $PRJNAME8b"; ?></td>
                                <td nowrap style="text-align:left;"><?php echo $JRNCODE8a; ?></td>
                                <td nowrap style="text-align:left;"><?php echo "$CFLCODE8a - $CFLDESC9"; ?></td>
                                <td nowrap style="text-align:right;">&nbsp;</td>
                                <td nowrap style="text-align:right; border-bottom-style:solid;" bo><?php echo number_format($TRBCOST8a, $decFormat); ?></td>
                            </tr>
                        <?php
                    endforeach;
                    $CASHOUTAZ	= $CASHOUTSAL + $CASHOUTA;
                    ?>
                        <tr style="background-color:#D4D4D4; font-style:italic; font-weight:bold;">
                            <td colspan="3" nowrap style="text-align:left;">SUB TOTAL - CASH OUT</td>
                            <td nowrap style="text-align:right; font-weight:bold">&nbsp;</td>
                            <td nowrap style="text-align:right; border-top-style:solid;" width="12%"><?php echo number_format($CASHOUTA, $decFormat); ?></td>
                        </tr>
                        <tr style="background-color:#D4D4D4; font-style:italic; font-weight:bold;">
                            <td nowrap style="text-align:left;">SUB TOTAL - SALARY / GAJI</td>
                            <td nowrap style="text-align:left;">&nbsp;</td>
                            <td nowrap style="text-align:left;">&nbsp;</td>
                            <td nowrap style="text-align:right;">&nbsp;</td>
                            <td nowrap style="text-align:right;"><?php echo number_format($CASHOUTSAL, $decFormat); ?></td>
                        </tr>
                    <?php
                    $CASHOUTBZ = $CASHOUTAZ;
                }
                else				// SUMMARY
                {
                    // JIKA SUMMARY
                        $CASHOUTB	= 0;
                        $CASHOUTSAL	= 0;
                        $CASHOUTBZ	= 0;
                    // 1. HITUNG PENGELUARAN GAJI
                        $sqlq10 	= "SELECT A.CSTCOST 
                                        FROM cf_vocdt A
                                            LEFT JOIN cf_vochd B ON A.VOCCODE = B.VOCCODE
                                        WHERE A.ACCCODE IN ('42411','42413')
                                            AND A.PJTCODE IN ($PRJCODECOL)
                                            AND B.TRXDATE <= '$End_Date'";
                        /*$sqlq10 	= "SELECT CSTCOST FROM VS_VOCDT WHERE PJTCODE IN ($PRJCODECOL)";*/
                       // $resq10 	= $this->db->query($sqlq10)->result();
					   $resq10	= 0;
                        /*foreach($resq10 as $rowq10) :
                            $CSTCOST10	= $rowq10->CSTCOST;
                            $CASHOUTSAL	= $CASHOUTSAL + $CSTCOST10;
                        endforeach;*/
                    // 2. HITUNG PENGELUARAN SELAIN GAJI
                        $sqlq11 	= "SELECT JRNCODE, TRBDATE, CFLCODE, TRBCOST 
										FROM cf_trxbank_out 
                                        WHERE CFLCODE IN ('20','21','22','23','24','25','26','27','28','29','30','31','32','33','34','35','36','37','38','39')
                                            AND PRJCODE IN ($PRJCODECOL)
                                            AND TRBDATE <= '$End_Date'
                                        ORDER BY TRBDATE";
                        /*$sqlq11 	= "SELECT PRJCODE, JRNCODE, TRBDATE, CFLCODE, TRBCOST FROM VSUMMARY_TRXBANK02 
                                        WHERE PRJCODE IN ($PRJCODECOL) ORDER BY TRBDATE";*/
                        //$resq11 	= $this->db->query($sqlq11)->result();
						$resq11	= 0;
                        /*foreach($resq11 as $rowq11) :
                            $JRNCODE11	= $rowq11->JRNCODE;
                            $TRBDATE11	= $rowq11->TRBDATE;
                            $CFLCODE11	= $rowq11->CFLCODE;
                            $TRBCOST11	= $rowq11->TRBCOST;
                            $CASHOUTB	= $CASHOUTB + $TRBCOST11;
                        endforeach;*/
                        $CASHOUTBZ		= $CASHOUTSAL + $CASHOUTB;
                    ?>
                    <tr>
                        <td colspan="3" nowrap style="text-align:left;">SALARY / GAJI</td>
                        <td nowrap style="text-align:right; font-weight:bold">&nbsp;</td>
                        <td nowrap style="text-align:right;"><?php echo number_format($CASHOUTSAL, $decFormat); ?></td>
                    </tr>
                    <tr>
                        <td colspan="3" nowrap style="text-align:left;">CASH OUT</td>
                        <td nowrap style="text-align:right; font-weight:bold">&nbsp;</td>
                        <td nowrap style="text-align:right;"><?php echo number_format($CASHOUTB, $decFormat); ?></td>
                    </tr>
                    <?php
                }
                ?>
                <tr style="background-color:#C4C4C4">
                    <td colspan="3" nowrap style="text-align:left; font-weight:bold">  CASH OUT TOTAL</td>
                    <td nowrap style="text-align:right; font-weight:bold">&nbsp;</td>
                    <td nowrap style="text-align:right; font-weight:bold"><?php echo number_format($CASHOUTBZ, $decFormat); ?></td>
                </tr>
                <tr style="background-color:#C4C4C4">
                    <td colspan="3" nowrap style="text-align:left; font-weight:bold">GRAND TOTAL CASH IN</td>
                    <td nowrap style="text-align:right; font-weight:bold"><?php echo number_format($CASHINTOTX, $decFormat); ?></td>
                    <td nowrap style="text-align:right; font-weight:bold">&nbsp;</td>
                </tr>
                <?php
                    if($CFType == 2)
                    {
                        $REMNOM		= $CASHINTOTA - $CASHOUTAZ;
                    }
                    else
                    {
                        $REMNOM		= $CASHINTOTB - $CASHOUTBZ;
                    }
                ?>
                <tr style="background-color:#C4C4C4">
                    <td colspan="3" nowrap style="text-align:left; font-weight:bold;">REMAIN ( MARGIN )</td>
                    <td nowrap style="text-align:right; font-weight:bold"><div <?php if($REMNOM > 0) { ?>style="display:none" <?php } ?>><?php echo number_format($REMNOM, $decFormat); ?></div></td>
                    <td nowrap style="text-align:right; font-weight:bold"><div <?php if($REMNOM < 0) { ?>style="display:none" <?php } ?>><?php echo number_format($REMNOM, $decFormat); ?></div></td>
                </tr>
            </table>
      </td>
    </tr>
</table>
</section>
</body>
</html>