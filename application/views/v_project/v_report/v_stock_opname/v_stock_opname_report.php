<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 17 April 2017
 * File Name	= r_usagereq_report.php
 * Location		= -
*/

/* 
 * Author		= Hendar Permana
 * Edit Date	= 16 Agustius 2017
 * File Name	= v_stock_opname_report.php
 * Location		= -
*/
if($viewType == 1)
{
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=exceldata.xls");
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
if($viewProj == 0) // SELECTED PROJECT
{
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
	}	
	$PRJCODECOLLD	= $PRJCODECOLL;
	$PRJNAMECOLLD	= $PRJNAMECOLL;
}
else
{
	$myrow			= 0;
	$sql 			= "SELECT DISTINCT PRJCODE FROM tbl_project WHERE PRJCOST > 1000000";
	$result 		= $this->db->query($sql)->result();	
	foreach($result as $row) :
		$myrow		= $myrow + 1;
		$PRJCODE	= $row->PRJCODE;
		if($myrow == 1)
		{
			$NPRJCODE = $PRJCODE;
		}
		else if($myrow == 2)
		{
			$NPRJCODE = "'$NPRJCODE', '$PRJCODE'";
		}
		else if($myrow > 2)
		{
			$NPRJCODE = "$NPRJCODE, '$PRJCODE'";
		}
	endforeach;
	$PRJCODECOL		= $NPRJCODE;
	//echo "$NPRJCODE";
	//return false;
	$PRJCODECOLLD	= "All";
	$PRJNAMECOLLD	= "All";
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/style.css'; ?>");</style>
  <title><?php echo $title; ?></title>
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
        <td width="16%">
            <div id="Layer1">
                <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();">
                <img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/print.gif';?>" border="0" alt="" align="absmiddle">Print</a>
                <a href="#" onClick="window.close();" class="button"> close </a>                </div>            </td>
        <td width="45%" class="style2" style="text-align:center; font-weight:bold">&nbsp;</td>
        <td width="39%" class="style2" style="text-align:center; font-weight:bold">&nbsp;</td>
    </tr>
    <tr>
        <td class="style2" style="text-align:left; font-weight:bold;">&nbsp;</td>
        <td class="style2">&nbsp;</td>
        <td class="style2" style="text-align:left; font-weight:bold">&nbsp;</td>
    </tr>
    <tr>
        <td rowspan="2" valign="top" class="style2" style="text-align:left; font-weight:bold;"><img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/Logo1.jpg'; ?>" width="181" height="44"></td>
        <td colspan="2" class="style2" style="text-align:center; font-weight:bold; text-transform:uppercase; font-size:20px">LAPORAN PERSEDIAAN</td>
  </tr>
    <tr>
        <td colspan="2" valign="top" class="style2" style="text-align:center; font-weight:bold; font-size:12px"><span class="style2" style="text-align:center; font-weight:bold; font-size:12px"><?php echo $comp_name; ?></span></td>
    </tr>
        <?php
            //n$StartDate1 = date('Y/m/d',strtotime($Start_Date));
            //n$EndDate1 = date('Y/m/d',strtotime($End_Date));
			//n$DOCTYPE1 = $TYPE;
			//if($TYPE==1)
			//	$DOCTYPE1 = "Asli";
			//elseif($TYPE==2)
			//	$DOCTYPE1 = "Copy";
			//else
			//	$DOCTYPE1 = "All";			
        ?>
    <tr>
        <td colspan="3" class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="3" class="style2" style="text-align:left; font-style:italic">
            <table width="100%">
            	<!--<tr style="text-align:left; font-style:italic">
        			<td width="8%" nowrap valign="top">Type Dokumen</td>
        			<td width="1%">:</td>
        			<td width="91%"><?php// echo $DOCTYPE1; ?></td>
    			</tr>-->
                <tr style="text-align:left; font-style:italic">
                    <td width="8%" nowrap valign="top">KODE PROYEK</td>
                    <td width="1%">:</td>
                    <td width="91%"><?php echo "$PRJCODECOLLD"; ?></span></td>
                </tr>
                <tr style="text-align:left; font-style:italic">
                  <td nowrap valign="top">NAMA PROYEK</td>
                  <td>:</td>
                  <td><span class="style2" style="text-align:left; font-style:italic"><?php echo $PRJNAMECOLLD;?></span></td>
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
                  <td rowspan="3" width="2%" nowrap style="text-align:center; font-weight:bold">NO</td>
                  <td rowspan="3" width="6%" nowrap style="text-align:center; font-weight:bold">KODE ITEM</td>
                  <td rowspan="3" width="6%" nowrap style="text-align:center; font-weight:bold">URAIAN</td>
                  <td rowspan="3" width="5%" nowrap style="text-align:center; font-weight:bold">SATUAN</td>
                  <td rowspan="3" width="8%" nowrap style="text-align:center; font-weight:bold">HARGA<br>SATUAN</td>
                  <td colspan="4" nowrap style="text-align:center; font-weight:bold">PENERIMAAN</td>
                  <td colspan="4" nowrap style="text-align:center; font-weight:bold">PENGELUARAN</td>
                  <td colspan="2" nowrap style="text-align:center; font-weight:bold">SISA</td>
              </tr>
              <tr style="background:#CCCCCC">              
                  <td rowspan="2" width="9%" nowrap style="text-align:center; font-weight:bold">S/D BLN LALU<br>VOL</td>
                  <td rowspan="2" width="6%" nowrap style="text-align:center; font-weight:bold">BULAN INI<br>VOL</td>
                  <td colspan="2" nowrap style="text-align:center; font-weight:bold">S/D BLN INI</td>              
                  <td rowspan="2" width="8%" nowrap style="text-align:center; font-weight:bold">S/D BLN LALU<br>VOL</td>
                  <td rowspan="2" width="6%" nowrap style="text-align:center; font-weight:bold">BULAN INI<br>VOL</td>
                  <td colspan="2" nowrap style="text-align:center; font-weight:bold">S/D BLN INI</td>              
                  <td rowspan="2" width="7%" nowrap style="text-align:center; font-weight:bold">VOL</td>
                  <td rowspan="2" width="8%" nowrap style="text-align:center; font-weight:bold">JML HARGA</td>
              </tr>
              <tr style="background:#CCCCCC">
              	  <td width="6%" nowrap style="text-align:center; font-weight:bold">VOL</td>
              	  <td width="7%" nowrap style="text-align:center; font-weight:bold">JML HARGA</td>
              	  <td width="8%" nowrap style="text-align:center; font-weight:bold">VOL</td>
              	  <td width="8%" nowrap style="text-align:center; font-weight:bold">JML HARGA</td>
              </tr>
                <?php					
                    /*$therow			= 0;
                    $GTOTAL			= 0;
                    $GTOTALA		= 0;
                    $GTOTALB		= 0;
                    $noU			= 0;
                    $noUa			= 0;
                    $noUb			= 0;
					
                    if($viewProj == 0)	// PER PROJECT
                    {
						$sqlq1 			= "SELECT * FROM tbl_asset_usagereq
											WHERE AUR_PRJCODE IN ($PRJCODECOL) ORDER BY AUR_DATE";
													
                        $resq1 			= $this->db->query($sqlq1)->result();
                    }
                    else
                    {
						$sqlq1 			= "SELECT * FROM tbl_asset_usagereq ORDER BY AUR_DATE";
													
                        $resq1 			= $this->db->query($sqlq1)->result();
                    }
                    
                    foreach($resq1 as $rowsqlq1) :
                        $therow			= $therow + 1;
                        $AUR_CODE 		= $rowsqlq1->AUR_CODE;
                        $AUR_JOBCODE 	= $rowsqlq1->AUR_JOBCODE;
                        
							$JL_NAME	= '';
							$JL_DESC	= '';
							$sqlq2a 	= "SELECT JL_NAME, JL_DESC FROM tbl_asset_joblist WHERE JL_CODE = '$AUR_JOBCODE' LIMIT 1";
							$resq2a 	= $this->db->query($sqlq2a)->result();
							foreach($resq2a as $rowq2a) :
								$JL_NAME	= $rowq2a->JL_NAME;
								$JL_DESC	= $rowq2a->JL_DESC;
							endforeach;
						
                        $AUR_AS_CODE	= $rowsqlq1->AUR_AS_CODE;
                        
							$AS_NAME	= '';
							$AS_DESC	= '';
							$sqlq3a 	= "SELECT AS_NAME, AS_DESC FROM tbl_asset_list WHERE AS_CODE = '$AUR_AS_CODE' LIMIT 1";
							$resq3a 	= $this->db->query($sqlq3a)->result();
							foreach($resq3a as $rowq3a) :
								$AS_NAME	= $rowq3a->AS_NAME;
								$AS_DESC	= $rowq3a->AS_DESC;
							endforeach;
							
                        $AUR_DATE 		= $rowsqlq1->AUR_DATE;
                        $AUR_PRJCODE	= $rowsqlq1->AUR_PRJCODE;
                        $AUR_DESC		= $rowsqlq1->AUR_DESC;
                        $AUR_STARTD		= $rowsqlq1->AUR_STARTD;
                        $AUR_ENDD		= $rowsqlq1->AUR_ENDD;
                        $AUR_STARTT		= $rowsqlq1->AUR_STARTT;
                        $AUR_ENDT		= $rowsqlq1->AUR_ENDT;
                        $AUR_STAT		= $rowsqlq1->AUR_STAT;
                        $AUR_CONFD		= $rowsqlq1->AUR_CONFD;
                        $AUR_APPD		= $rowsqlq1->AUR_APPD;*/
						
					$therow			= 0;
                    $GTOTAL			= 0;
                    $GTOTALA		= 0;
                    $GTOTALB		= 0;
                    $noU			= 0;
                    $noUa			= 0;
                    $noUb			= 0;
					
					if ($TYPE==1)
						$TYPE1= "HRDOCJNS = 1";
					elseif ($TYPE==2)
						$TYPE1= "HRDOCJNS = 2";
					else
						$TYPE1= "HRDOCJNS IN (1,2)";
					
					
                    if($viewProj == 0)	// PER PROJECT
                    {
						$sqlq1 			= 	"SELECT * 
											FROM tbl_sopn_header
											INNER JOIN tbl_sopn_detail ON tbl_sopn_header.SOPNH_CODE = tbl_sopn_detail.SOPNH_CODE
											WHERE SOPNH_PRJCODE IN ($PRJCODECOL) ORDER BY tbl_sopn_header.SOPNH_CODE";
											
				/* Key
				
				SELECT A.Item_Code, A.serialNumber, A.Item_Name, A.Item_Qty, A.Unit_Type_ID1, B.Unit_Type_Name
				FROM titem A
				INNER JOIN tunittype B ON A.Unit_Type_ID1 = B.Unit_Type_ID
				ORDER BY A.Item_Name*/
													
                        $resq1 			= $this->db->query($sqlq1)->result();
                    }
                    else
                    {
						$sqlq1 			= "SELECT * 
											FROM tbl_sopn_header 
											INNER JOIN tbl_sopn_detail ON tbl_sopn_header.SOPNH_CODE = tbl_sopn_detail.SOPNH_CODE
											ORDER BY tbl_sopn_header.SOPNH_CODE";
													
                        $resq1 			= $this->db->query($sqlq1)->result();
                    }
                    
                    foreach($resq1 as $rowsqlq1) :
                        $therow			= $therow + 1;
                        $PRJCODE 		= $rowsqlq1->SOPNH_PRJCODE;
							
							$PRJNAME1	= "";
							$sqlq2a 	= "SELECT PRJCODE,PRJNAME FROM tbl_project WHERE PRJCODE ='$PRJCODE'";
							$resq2a 	= $this->db->query($sqlq2a)->result();
							foreach($resq2a as $rowq2a) :
								//$PRJCODE	= $rowq2a->PRJCODE;
								$PRJNAME1	= $rowq2a->PRJNAME;		
							endforeach;
							
							
							/*$PRJNAME1	= "";
							$sqlq2a 	= "SELECT PRJCODE,PRJNAME FROM tbl_project WHERE PRJCODE ='$PRJCODE'";
							$resq2a 	= $this->db->query($sqlq2a)->result();
							foreach($resq2a as $rowq2a) :
								//$PRJCODE	= $rowq2a->PRJCODE;
								$PRJNAME1	= $rowq2a->PRJNAME;		
							endforeach;*/
						
                        //$PRJNAME	 	= $rowq2a->PRJNAME;
						
						//HEADER
						$SOPNH_WH		= $rowsqlq1->SOPNH_WH;
						
						//DETAIL
                        $ITEM_CODE	 	= $rowsqlq1->ITEM_CODE;
                        $ITEM_NOTE	 	= $rowsqlq1->ITEM_NOTE;
                        $ITEM_UNIT	 	= $rowsqlq1->ITEM_UNIT;
                        $ITEM_PRICE		= $rowsqlq1->ITEM_PRICE;
                        $ITEM_IN_NOW	= $rowsqlq1->ITEM_IN_NOW;
                        $ITEM_IN_BEF0	= $rowsqlq1->ITEM_IN_NOW;
                        $ITEM_IN_BEF1	= $rowsqlq1->ITEM_IN_BEF1;
                        $ITEM_OUT_NOW	= $rowsqlq1->ITEM_OUT_NOW;
                        $ITEM_OUT_BEF0	= $rowsqlq1->ITEM_OUT_BEF0;
                        $ITEM_OUT_BEF1	= $rowsqlq1->ITEM_OUT_BEF1;
                        $ITEM_REM_QTY	= $rowsqlq1->ITEM_REM_QTY;
                        $ITEM_REM_PRICE	= $rowsqlq1->ITEM_REM_PRICE;
                        
                        ?>
                            <tr>
                            	<?php // echo number_format($HRDOCCOST, $decFormat); ?>
                                <td nowrap style="text-align:center;"><?php echo "$therow"; ?>.</td>
                                <td nowrap style="text-align:center;"><?php echo $ITEM_CODE; ?></td>
                                
                                <td width="6%" nowrap style="text-align:left;"><?php echo $ITEM_NOTE; ?></td>
                              	<td width="5%" nowrap style="text-align:center;"><?php echo $ITEM_UNIT; ?></td>
                                <td width="8%" nowrap style="text-align:center;"><?php echo number_format($ITEM_PRICE, $decFormat); ?></td>
                                <td width="9%" nowrap style="text-align:right;"><?php echo number_format($ITEM_IN_BEF1, $decFormat); ?></td>
                                <td width="6%" nowrap style="text-align:center;"><?php echo number_format($ITEM_IN_NOW, $decFormat); ?></td>
                                <td width="6%" nowrap style="text-align:center;"><?php echo number_format($ITEM_IN_BEF1+$ITEM_IN_NOW, $decFormat); ?></td>
                                <td width="7%" nowrap style="text-align:center;"><?php echo number_format($ITEM_PRICE*$ITEM_IN_NOW, $decFormat); ?></td>
                                <td width="8%" nowrap style="text-align:center;"><?php echo number_format($ITEM_OUT_BEF0, $decFormat); ?></td>
                                <td width="6%" nowrap style="text-align:center;"><?php echo number_format($ITEM_OUT_NOW, $decFormat); ?></td>
                                <td width="8%" nowrap style="text-align:center;"><?php echo number_format($ITEM_OUT_BEF1+$ITEM_OUT_NOW, $decFormat); ?></td>
                                <td width="8%" nowrap style="text-align:center;"><?php echo number_format($ITEM_PRICE*$ITEM_OUT_NOW, $decFormat); ?></td>
                                <td width="7%" nowrap style="text-align:center;"><?php echo number_format($ITEM_REM_QTY, $decFormat); ?></td>
                                <td width="8%" nowrap style="text-align:center;"><?php echo number_format($ITEM_REM_PRICE, $decFormat); ?></td>
                            </tr>
                        <?php
                    endforeach;
                ?>
            </table>
      </td>
    </tr>
</table>
</section>
</body>
</html>