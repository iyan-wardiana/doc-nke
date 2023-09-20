<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 5 April 2017
 * File Name	= item_list.php
 * Location		= -
*/

/* 
 * Author		= Hendar Permana
 * Edit Date	= 05 September 2017
 * File Name	= entry_wip.php
 * Location		= -
*/

// _global function
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$FlagUSER 	= $this->session->userdata['FlagUSER'];
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

$PRJCODE		= $PRJCODE;
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/style.css'; ?>");</style>
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/styleZebra.css'; ?>");</style>
    <title><?php echo $appName; ?></title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
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
</head>

<script src="<?php echo base_url('assets/js/jquery-1.10.1.min.js') ?>" type="text/javascript"></script>
<?php
	$this->load->view('template/topbar');
	$this->load->view('template/sidebar');

	$ISREAD 	= $this->session->userdata['ISREAD'];
	$ISCREATE 	= $this->session->userdata['ISCREATE'];
	$ISAPPROVE 	= $this->session->userdata['ISAPPROVE'];
	$ISDWONL 	= $this->session->userdata['ISDWONL'];$LangID 	= $this->session->userdata['LangID'];

	$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
	$resTransl		= $this->db->query($sqlTransl)->result();
	foreach($resTransl as $rowTransl) :
		$TranslCode	= $rowTransl->MLANG_CODE;
		$LangTransl	= $rowTransl->LangTransl;
		
		if($TranslCode == 'Add')$Add = $LangTransl;
		if($TranslCode == 'Edit')$Edit = $LangTransl;
		if($TranslCode == 'Back')$Back = $LangTransl;
		if($TranslCode == 'Code')$Code = $LangTransl;
		if($TranslCode == 'Periode')$Periode = $LangTransl;
		if($TranslCode == 'Project')$Project = $LangTransl;
		if($TranslCode == 'Type')$Type = $LangTransl;
		if($TranslCode == 'Supplier')$Supplier = $LangTransl;
		if($TranslCode == 'Value')$Value = $LangTransl;
		if($TranslCode == 'Status')$Status = $LangTransl;
		if($TranslCode == 'Notes')$Notes = $LangTransl;

	endforeach;
?>

<body class="hold-transition skin-blue sidebar-mini">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
        <?php echo $h2_title; ?>
        <small><?php echo $h3_title; ?></small>
        </h1><br>
        <?php /*?><ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Tables</a></li>
        <li class="active">Data tables</li>
        </ol><?php */?>
    </section>

    <!-- Main content -->
    
    <div class="box">
        <!-- /.box-header -->
        <div class="box-body">
            <table id="example1" class="table table-bordered table-striped" width="100%">
            	<thead>
                <tr>
                    <th width="33" style="vertical-align:middle; text-align:center"><input name="chkAll" id="chkAll" type="checkbox" value="" /></th>
                    <th width="92" style="vertical-align:middle; text-align:center"><?php echo $Code ?></th>
                    <th width="174" style="vertical-align:middle; text-align:center"><?php echo $Periode ?></th>
                    <th width="47" style="vertical-align:middle; text-align:center; display:none"><?php echo $Project ?></th>
                    <th width="165" style="vertical-align:middle; text-align:center"><?php echo $Type ?></th>
                    <th width="277" style="vertical-align:middle; text-align:center"><?php echo $Supplier ?></th>
                    <th width="155" style="vertical-align:middle; text-align:center" nowrap><?php echo $Value ?> (Rp)</th>
                    <th width="92" style="vertical-align:middle; text-align:center" nowrap><?php echo $Status ?></th>
                    <th width="133" style="vertical-align:middle; text-align:center" nowrap><?php echo $Notes ?></th>
                </tr>

                </thead>
                <tbody> 
                    <?php 
                    $i = 0;
					$j = 0;
                    if($recordcount > 0)
                    {
                        $Unit_Type_Name2	= '';
						foreach($viewentry_wip as $row) :
							$myNewNo 		= ++$i;
							$WIP_CODE 		= $row->WIP_CODE;
							$WIP_PERIODE	= $row->WIP_PERIODE;
							$WIP_PRJCODE	= $row->WIP_PRJCODE;
							$WIP_TYPE 		= $row->WIP_TYPE;
							$WIP_SUPL 		= $row->WIP_SUPL;
							$WIP_VALUE 		= $row->WIP_VALUE;
							$WIP_STAT 		= $row->WIP_STAT;
							$WIP_NOTE 		= $row->WIP_NOTE;
							
							if($WIP_STAT == 3)
							{
								// Check untuk bulan yang sama
									$YEARP	= date('Y',strtotime($WIP_PERIODE));
									$MONTHP	= (int)date('m',strtotime($WIP_PERIODE));
								// BUAT TANGGAL AKHIR BULAN PER SI
									$LASTDATE	= date('Y-m-t', strtotime($WIP_PERIODE));
									
								$sqlPL	= "tbl_profitloss 
											WHERE PRJCODE = '$PRJCODE' AND PERIODE = '$LASTDATE'";
								$resPL	= $this->db->count_all($sqlPL);
								if($resPL == 0)
								{
									// GET PRJECT DETAIL			
										$sqlPRJ	= "SELECT PRJNAME, PRJCOST FROM tbl_project WHERE PRJCODE = '$WIP_PRJCODE'";
										$resPRJ	= $this->db->query($sqlPRJ)->result();
										foreach($resPRJ as $rowPRJ) :
											$PRJNAME 	= $rowPRJ->PRJNAME;
											$PRJCOST 	= $rowPRJ->PRJCOST;
										endforeach;
										
									// GET WIP TOTAL PER MONTH
										$WIP_VALUE0	= 0;
										$WIP_VALUE1	= 0;
										$WIP_VALUE2	= 0;
										$WIP_VALUE3	= 0;
										$WIP_VALUE4	= 0;
										/*$sql_WIP0	= "SELECT SUM(WIP_VALUE) AS WIP_VALUE
														FROM tbl_wip
														WHERE WIP_PRJCODE = $WIP_PRJCODE
														AND YEAR(WIP_PERIODE) = $YEARP AND MONTH(WIP_PERIODE) = $MONTHP AND WIP_STAT = 3";								
										$res_WIP0	= $this->db->query($sql_WIP0)->result();
										foreach($res_WIP0 as $row_WIP0) :
											$WIP_VALUE0 = $row_WIP0->WIP_VALUE;
										endforeach;
										
										$sql_WIP1	= "SELECT SUM(WIP_VALUE) AS WIP_VALUE
														FROM tbl_wip
														WHERE WIP_PRJCODE = $WIP_PRJCODE AND WIP_TYPE = 1
														AND YEAR(WIP_PERIODE) = $YEARP AND MONTH(WIP_PERIODE) = $MONTHP AND WIP_STAT = 3";
										$res_WIP1 	= $this->db->query($sql_WIP1)->result();
										foreach($res_WIP1 as $row_WIP1) :
											$WIP_VALUE1 = $row_WIP1->WIP_VALUE;
										endforeach;
										
										$sql_WIP2	= "SELECT SUM(WIP_VALUE) AS WIP_VALUE
														FROM tbl_wip
														WHERE WIP_PRJCODE = $WIP_PRJCODE AND WIP_TYPE = 2
														AND YEAR(WIP_PERIODE) = $YEARP AND MONTH(WIP_PERIODE) = $MONTHP AND WIP_STAT = 3";
										$res_WIP2 	= $this->db->query($sql_WIP2)->result();
										foreach($res_WIP2 as $row_WIP2) :
											$WIP_VALUE2	= $row_WIP2->WIP_VALUE;
										endforeach;*/
										
										$sql_WIP3	= "SELECT SUM(WIP_VALUE) AS WIP_VALUE
														FROM tbl_wip
														WHERE WIP_PRJCODE = '$WIP_PRJCODE' AND WIP_TYPE = 3 
														AND YEAR(WIP_PERIODE) = $YEARP AND MONTH(WIP_PERIODE) = $MONTHP AND WIP_STAT = 3";
										$res_WIP3 	= $this->db->query($sql_WIP3)->result();
										foreach($res_WIP3 as $row_WIP3) :
											$WIP_VALUE3	= $row_WIP3->WIP_VALUE;
										endforeach;
										
										$sql_WIP4	= "SELECT SUM(WIP_VALUE) AS WIP_VALUE
														FROM tbl_wip
														WHERE WIP_PRJCODE = '$WIP_PRJCODE' AND WIP_TYPE = 4
														AND YEAR(WIP_PERIODE) = $YEARP AND MONTH(WIP_PERIODE) = $MONTHP AND WIP_STAT = 3";
										$res_WIP4 	= $this->db->query($sql_WIP4)->result();
										foreach($res_WIP4 as $row_WIP4) :
											$WIP_VALUE4	= $row_WIP4->WIP_VALUE;
										endforeach;
									
									// SAVE TO PROFITLOSS
										$insPL	= "INSERT INTO tbl_profitloss (PERIODE, PRJCODE, PRJNAME, PRJCOST, WIP_SUBKON, WIP_ALAT)
													VALUES ('$LASTDATE', '$PRJCODE', '$PRJNAME', '$PRJCOST', '$WIP_VALUE3', '$WIP_VALUE4')";
										$this->db->query($insPL);
								}
								else
								{
									// GET WIP TOTAL PER MONTH
										$sql_WIP3	= "SELECT SUM(WIP_VALUE) AS WIP_VALUE
														FROM tbl_wip
														WHERE WIP_PRJCODE = '$WIP_PRJCODE' AND WIP_TYPE = 3 
														AND YEAR(WIP_PERIODE) = $YEARP AND MONTH(WIP_PERIODE) = $MONTHP AND WIP_STAT = 3";
										$res_WIP3 	= $this->db->query($sql_WIP3)->result();
										foreach($res_WIP3 as $row_WIP3) :
											$WIP_VALUE3	= $row_WIP3->WIP_VALUE;
										endforeach;
										
										$sql_WIP4	= "SELECT SUM(WIP_VALUE) AS WIP_VALUE
														FROM tbl_wip
														WHERE WIP_PRJCODE = '$WIP_PRJCODE' AND WIP_TYPE = 4
														AND YEAR(WIP_PERIODE) = $YEARP AND MONTH(WIP_PERIODE) = $MONTHP AND WIP_STAT = 3";
										$res_WIP4 	= $this->db->query($sql_WIP4)->result();
										foreach($res_WIP4 as $row_WIP4) :
											$WIP_VALUE4	= $row_WIP4->WIP_VALUE;
										endforeach;
										
									// SAVE TO PROFITLOSS
										$updPL	= "UPDATE tbl_profitloss SET WIP_SUBKON = '$WIP_VALUE3', WIP_ALAT = '$WIP_VALUE4'
													WHERE PRJCODE = '$PRJCODE' AND PERIODE = '$LASTDATE'";
										$this->db->query($updPL);
								}
							}
							
							$secUpd		= site_url('c_gl/c_entry_wip/update/?id='.$this->url_encryption_helper->encode_url($WIP_CODE));
							
							if ($j==1) {
								echo "<tr class=zebra1>";
								$j++;
							} else {
								echo "<tr class=zebra2>";
								$j--;
							}
							?>
                                    <td style="text-align:center"> <?php print '<input name="chkDetail" id="chkDetail" type="checkbox" value="'.$WIP_CODE.'" />'; ?> </td>
                                    <td nowrap> <?php print anchor("$secUpd",$WIP_CODE,array('class' => 'update')).' '; ?> </td>
                                    <td style="text-align:center"> <?php print $WIP_PERIODE; ?> </td>
                                    <?php
										// Mencari nilai penambahan item dari tabel LPM berstatus 3 (Approve)
										//$AkumQty_IP		= 0;
										/*$sqlTotIn		= "SELECT SUM(A.ITM_QTY) AS AkumQty_IN, SUM(A.ITM_QTY * A.ITM_PRICE) AS AkumQty_IP
															FROM tbl_lpm_detail A
																INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
																	AND B.PRJCODE = '$PRJCODE'
																INNER JOIN tbl_lpm_header C ON C.RR_Number = A.RR_Number
															WHERE
																A.ITM_CODE = '$ITM_CODE'
																AND A.PRJCODE = '$PRJCODE'
																AND C.LPMSTAT = 3";
										$ressqlTotIn	= $this->db->query($sqlTotIn)->result();
										foreach($ressqlTotIn as $rowIN) :
											$AkumQty_IN = $rowIN->AkumQty_IN;
											$AkumQty_IP = $rowIN->AkumQty_IP;
										endforeach;
										$ITM_IN			= $AkumQty_IN + $ITM_VOLM;
										$ITM_INP		= $AkumQty_IP + ($ITM_VOLM * $ITM_PRICE);*/
                                    ?>
                                    <td style="text-align:center; display:none"><?php print $WIP_PRJCODE; ?>&nbsp;</td>
                                    <?php							
										// Mencari nilai pengeluaran dari tabel Asset Usage Detail berstatus 3 (Approve)
										/*$AkumQty_UP			= 0;
										$sqlTotReserve		= "SELECT SUM(A.ITM_QTY) AS AkumQty_UM, SUM(A.ITM_QTY * A.ITM_PRICE) AS AkumQty_UP
																FROM tbl_asset_usagedet A
																	INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
																		AND B.PRJCODE = '$PRJCODE'
																	INNER JOIN tbl_asset_usage C ON C.AU_CODE = A.AU_CODE
																WHERE
																	A.ITM_CODE = '$ITM_CODE'
																	AND A.AU_PRJCODE = '$PRJCODE'
																	AND C.AU_STAT = 3";
										$ressqlTotReserve	= $this->db->query($sqlTotReserve)->result();
										foreach($ressqlTotReserve as $rowTR) :
											$AkumQty_UM 	= $rowTR->AkumQty_UM;
											$AkumQty_UP 	= $rowTR->AkumQty_UP;
										endforeach;							
										$ITM_OUT			= $AkumQty_UM;
										
										//$ITM_ONHAND		= $ITM_IN - $ITM_OUT;
										$ITM_ONHAND			= $ITM_VOLM - $ITM_OUT;*/
										
										//$ITM_ONHAND			= $TotQTYIN - $ITM_OUT;
										//$ITM_ONHANDP		= $ITM_TOTALP - $AkumQty_UP;
										//$AVGPRICE			= $ITM_ONHANDP / $ITM_ONHAND;
										// REMAIN QTY
										//$REM_QTY			= $ITM_VOLM - $TOT_USEDQTY;
										//$REM_QTY			= $ITM_ONHAND;
									
									if ($WIP_TYPE==0)
										$WIP_TYPE1="WIP Versi Proyek";
									elseif ($WIP_TYPE==1)
										$WIP_TYPE1="Material";
									elseif ($WIP_TYPE==2)
										$WIP_TYPE1="Upah";
									elseif ($WIP_TYPE==3)
										$WIP_TYPE1="Subkon";
									elseif ($WIP_TYPE==4)
										$WIP_TYPE1="Alat";
									else
										$WIP_TYPE1="-";
										
										
                                    ?>
                                    
                                    <td style="text-align:left" nowrap><?php print $WIP_TYPE1; ?>&nbsp;</td>
                                    <td style="text-align:left" nowrap><?php print $WIP_SUPL; ?>&nbsp;</td>
                                    <td style="text-align:right"><?php print number_format($WIP_VALUE, $decFormat); ?>&nbsp;</td>
                                    <?php
												if($WIP_STAT == 1)$WIP_STATDesc = "New";
											elseif($WIP_STAT == 2) $WIP_STATDesc = "Confirm";
											elseif($WIP_STAT == 3) $WIP_STATDesc = "Approved";
											elseif($WIP_STAT == 6) $WIP_STATDesc = "Close";
											
											if($WIP_STAT == 1)
											{
												$WIP_STATD = 'New';
												$STATCOL	= 'warning';
											}
											elseif($WIP_STAT == 2)
											{
												$WIP_STATD = 'Confirm';
												$STATCOL	= 'primary';
											}
											elseif($WIP_STAT == 3)
											{
												$WIP_STATD 	= 'Approve';
												$STATCOL	= 'success';
											}
											elseif($WIP_STAT == 6)
											{
												$WIP_STATD 	= 'Close';
												$STATCOL	= 'danger';
											}
											else
											{
												$WIP_STATD = 'Not Detected';
												$STATCOL	= 'danger';
											}
											
									?>
                                    <td style="text-align:center">
                                    <span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
										<?php 
											echo "&nbsp;$WIP_STATDesc&nbsp;";
								 		?>
									</span>
                            		</td>
                                    <td style="text-align:left"><?php print $WIP_NOTE; ?>&nbsp;</td>
                                    <!--<td style="text-align:center"><?php //print "$ITM_UNIT"; ?></td>
                                    <td style="text-align:center"> <?php //print $STATUSDESC; ?> </td>-->
                                </tr>
                                <?php 
                        endforeach;
                    }
					$secAddURL = site_url('c_gl/c_entry_wip/add/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
                    ?> 
                </tbody>
                <tr>
                    <td colspan="9">
                    <?php
						if($ISCREATE == 1)
						{
							//echo anchor($secAddURL,'<input type="button" name="btnSubmit" id="btnSubmit" class="btn btn-primary" value="Add [+]" />&nbsp;');
                        	echo anchor("$secAddURL",'<button class="btn btn-primary"><i class="cus-add-16x16"></i>&nbsp;&nbsp;'.$Add.'</button>&nbsp;');
						}
						
						echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="cus-back-16x16"></i>&nbsp;&nbsp;'.$Back.'</button>');
					?>
                	</td>
			    </tr>                           
            </table>
      </div>
      	<!-- /.box -->
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
$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>