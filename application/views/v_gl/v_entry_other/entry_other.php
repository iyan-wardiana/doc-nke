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
 * File Name	= entry_other.php
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

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
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
    <link rel="icon" type="image/png" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/favicon/32x32.png'; ?>" sizes="32x32">
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
	$ISDWONL 	= $this->session->userdata['ISDWONL'];
	$LangID 	= $this->session->userdata['LangID'];

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
		if($TranslCode == 'ProgresPlan')$ProgresPlan = $LangTransl;
		if($TranslCode == 'PredictionValue')$PredictionValue = $LangTransl;
		if($TranslCode == 'MCKatrolan')$MCKatrolan = $LangTransl;
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
                    <th width="45" style="vertical-align:middle; text-align:center"><input name="chkAll" id="chkAll" type="checkbox" value="" /></th>
                    <th width="114" style="vertical-align:middle; text-align:center"><?php echo $Code ?> </th>
                    <th width="246" style="vertical-align:middle; text-align:center"><?php echo $Periode ?> </th>
                    <th width="260" style="vertical-align:middle; text-align:center"><?php echo $Project ?> </th>
                    <th width="238" style="vertical-align:middle; text-align:center"><?php echo $ProgresPlan ?>  (%)</th>
                    <th width="281" style="vertical-align:middle; text-align:center" nowrap><?php echo $PredictionValue ?> </th>
                    <th width="281" style="vertical-align:middle; text-align:center" nowrap><?php echo $MCKatrolan ?> </th>
                    <th width="131" style="vertical-align:middle; text-align:center" nowrap><?php echo $Status ?> </th>
                    <th width="193" style="vertical-align:middle; text-align:center" nowrap><?php echo $Notes ?> </th>
                </tr>

                </thead>
                <tbody> 
                    <?php 
                    $i = 0;
					$j = 0;
                    if($recordcount > 0)
                    {
                        $Unit_Type_Name2	= '';
						foreach($viewentry_other as $row) :
							$myNewNo 		= ++$i;
							$FM_CODE 		= $row->FM_CODE;
							$FM_PERIODE		= $row->FM_PERIODE;
							$FM_PRJCODE		= $row->FM_PRJCODE;
							$FM_PROGRES		= $row->FM_PROGRES;
							$FM_PREDICTION	= $row->FM_PREDICTION;
							$FM_MCKATROLAN	= $row->FM_MCKATROLAN;
							$FM_STATUS		= $row->FM_STATUS;
							$FM_NOTE 		= $row->FM_NOTE;	
							
							if($FM_STATUS == 3)
							{
								// Add by DH for insert into profitloss on 2017-10-02
								// Check untuk bulan yang sama
								$FM_PERIODEY	= date('Y',strtotime($FM_PERIODE));
								$FM_PERIODEM	= (int)date('m',strtotime($FM_PERIODE));
								$sqlPL			= "tbl_profitloss
													WHERE PRJCODE = '$FM_PRJCODE' AND YEAR(PERIODE) = $FM_PERIODEY AND MONTH(PERIODE) = $FM_PERIODEM";
								$resPL	= $this->db->count_all($sqlPL);
								if($resPL == 0)
								{
									// GET PRJECT DETAIL			
										$sqlPRJ	= "SELECT PRJNAME, PRJCOST FROM tbl_project WHERE PRJCODE = '$FM_PRJCODE'";
										$resPRJ	= $this->db->query($sqlPRJ)->result();
										foreach($resPRJ as $rowPRJ) :
											$PRJNAME 	= $rowPRJ->PRJNAME;
											$PRJCOST 	= $rowPRJ->PRJCOST;
										endforeach;
										
									// GET FM
										$FM_PROGRES		= 0;
										$FM_MCKATROLAN	= 0;
										$FM_PREDICTION	= 0;
										$sqlOTH			= "SELECT FM_PROGRES, FM_PREDICTION, FM_MCKATROLAN, FM_NOTE
															FROM tbl_profloss_man
															WHERE FM_PRJCODE = '$FM_PRJCODE' AND FM_PERIODE = '$FM_PERIODE'";
										$resOTH			= $this->db->query($sqlOTH)->result();
										foreach($resOTH as $rowOTH) :
											$FM_PROGRES		= $rowOTH ->FM_PROGRES;
											$FM_MCKATROL	= $rowOTH ->FM_MCKATROLAN;
											$FM_PREDIC		= $rowOTH ->FM_PREDICTION;
										endforeach;
									
									// SAVE TO PROFITLOSS
										$insPL	= "INSERT INTO tbl_profitloss (PERIODE, PRJCODE, PRJNAME, PRJCOST, PLANPROG, MCCATROL, SIPREDIC)
													VALUES ('$FM_PERIODE', '$PRJCODE', '$PRJNAME', '$PRJCOST', '$FM_PROGRES', '$FM_MCKATROL', 
													'$FM_PREDIC')";
										$this->db->query($insPL);
								}
								else
								{
									// GET FM
										$FM_PROGRES		= 0;
										$FM_MCKATROLAN	= 0;
										$FM_PREDICTION	= 0;
										$sqlOTH			= "SELECT FM_PROGRES, FM_PREDICTION, FM_MCKATROLAN, FM_NOTE
															FROM tbl_profloss_man
															WHERE FM_PRJCODE = '$FM_PRJCODE' AND FM_PERIODE = '$FM_PERIODE'";
										$resOTH			= $this->db->query($sqlOTH)->result();
										foreach($resOTH as $rowOTH) :
											$FM_PROGRES		= $rowOTH ->FM_PROGRES;
											$FM_MCKATROL	= $rowOTH ->FM_MCKATROLAN;
											$FM_PREDIC		= $rowOTH ->FM_PREDICTION;
										endforeach;
									
									// SAVE TO PROFITLOSS
										$updPL	= "UPDATE tbl_profitloss SET PLANPROG = '$FM_PROGRES', MCCATROL = '$FM_MCKATROL',
													SIPREDIC = '$FM_PREDIC' 
													WHERE PRJCODE = '$FM_PRJCODE' AND YEAR(PERIODE) = $FM_PERIODEY AND MONTH(PERIODE) = $FM_PERIODEM";
										$this->db->query($updPL);
								}
							}
							
							$secUpd		= site_url('c_gl/c_entry_other/update/?id='.$this->url_encryption_helper->encode_url($FM_CODE));
							
							if ($j==1) {
								echo "<tr class=zebra1>";
								$j++;
							} else {
								echo "<tr class=zebra2>";
								$j--;
							}
							?>
                                    <td style="text-align:center"> <?php print '<input name="chkDetail" id="chkDetail" type="checkbox" value="'.$FM_CODE.'" />'; ?> </td>
                                    <td nowrap> <?php print anchor("$secUpd",$FM_CODE,array('class' => 'update')).' '; ?> </td>
                                    <td style="text-align:center"> <?php print $FM_PERIODE; ?> </td>
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
                                    <td style="text-align:center"><?php print $FM_PRJCODE; ?>&nbsp;</td>
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
									
									/*if ($FM_TYPE==0)
										$FM_TYPE1="FM Versi Proyek";
									elseif ($FM_TYPE==1)
										$FM_TYPE1="Material";
									elseif ($FM_TYPE==2)
										$FM_TYPE1="Upah";
									elseif ($FM_TYPE==3)
										$FM_TYPE1="Subkon";
									elseif ($FM_TYPE==4)
										$FM_TYPE1="Alat";
									else
										$FM_TYPE1="-";*/
										
										
                                    ?>
                                    
                                    <td style="text-align:right"><?php print "$FM_PROGRES"; ?>&nbsp;</td>
                                    <td style="text-align:right"><?php print number_format($FM_PREDICTION, $decFormat); ?>&nbsp;</td>
                                    <td style="text-align:right"><?php print number_format($FM_MCKATROLAN, $decFormat); ?>&nbsp;</td>
                                    <?php 
												if($FM_STATUS == 1) $FM_STATDesc = "New";
											elseif($FM_STATUS == 2) $FM_STATDesc = "Confirm";
											elseif($FM_STATUS == 3) $FM_STATDesc = "Approved";
											elseif($FM_STATUS == 6) $FM_STATDesc = "Close";
											
											if($FM_STATUS == 1)
											{
												$FM_STATD = 'New';
												$STATCOL	= 'warning';
											}
											elseif($FM_STATUS == 2)
											{
												$FM_STATD = 'Confirm';
												$STATCOL	= 'primary';
											}
											elseif($FM_STATUS == 3)
											{
												$FM_STATD 	= 'Approve';
												$STATCOL	= 'success';
											}
											elseif($FM_STATUS == 6)
											{
												$FM_STATD 	= 'Close';
												$STATCOL	= 'danger';
											}
											else
											{
												$FM_STATD = 'Not Detected';
												$STATCOL	= 'danger';
											}
											
									?>
                                    <td style="text-align:center">
                                    <span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
										<?php 
											echo "&nbsp; $FM_STATDesc &nbsp;";
								 		?>
									</span>
                            		</td>
                                    <td style="text-align:left"><?php print $FM_NOTE; ?>&nbsp;</td>
                                    <!--<td style="text-align:center"><?php //print "$ITM_UNIT"; ?></td>
                                    <td style="text-align:center"> <?php //print $STATUSDESC; ?> </td>-->
                                </tr>
                                <?php 
                        endforeach;
                    }
					$secAddURL = site_url('c_gl/c_entry_other/add/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
                    ?> 
                </tbody>
                <tr>
                    <td colspan="8">
                    	<?php
							//if($ISCREATE == 1)
							//{
								echo anchor("$secAddURL",'<button class="btn btn-primary"><i class="cus-add-16x16"></i>&nbsp;&nbsp;'.$Add.'</button>&nbsp;');
							//}
						?>
						&nbsp;
						<?php 
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