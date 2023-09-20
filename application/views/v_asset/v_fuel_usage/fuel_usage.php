<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 5 April 2017
 * File Name	= item_list.php
 * Location		= -
*/

/* 
 * Author		= Hendar Permana
 * Edit Date	= 29 September 2017
 * File Name	= fuel_usage.php
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
		if($TranslCode == 'Date')$Date = $LangTransl;
		if($TranslCode == 'Project')$Project = $LangTransl;
		if($TranslCode == 'Asset')$Asset = $LangTransl;
		if($TranslCode == 'Quantity')$Quantity = $LangTransl;
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
                    <th width="56" style="vertical-align:middle; text-align:center"><input name="chkAll" id="chkAll" type="checkbox" value="" /></th>
                    <th width="151" style="vertical-align:middle; text-align:center"><?php echo $Code ?>  </th>
                    <th width="194" style="vertical-align:middle; text-align:center"><?php echo $Date ?> </th>
                    <th width="229" style="vertical-align:middle; text-align:center; display:none"><?php echo $Project ?> </th>
                    <th width="390" style="vertical-align:middle; text-align:center"><?php echo $Asset ?> </th>
                    <th width="259" style="vertical-align:middle; text-align:center"><?php echo $Quantity ?>  (Liter)</th>
                    <!--<th width="155" style="vertical-align:middle; text-align:center" nowrap>Price (Rp)</th>-->
                    <th width="233" style="vertical-align:middle; text-align:center" nowrap><?php echo $Notes ?> </th>
                </tr>

                </thead>
                <tbody> 
                    <?php 
                    $i = 0;
					$j = 0;
                    if($recordcount > 0)
                    {
                        $Unit_Type_Name2	= '';
						foreach($viewfuel_usage as $row) :
							$myNewNo 		= ++$i;
							$FU_CODE 		= $row->FU_CODE;
							$FU_DATE		= $row->FU_DATE;
							$FU_PRJCODE		= $row->FU_PRJCODE;
							$FU_ASSET 		= $row->FU_ASSET;
							$FU_QTY 		= $row->FU_QTY;
							$FU_PRICE 		= $row->FU_PRICE;
							//$FU_STAT 		= $row->FU_STAT;
							$FU_NOTE 		= $row->FU_NOTE;			
							//$ITM_UNIT		= $row->ITM_UNIT;
							//$ITM_CURRENCY	= $row->ITM_CURRENCY;
							//$ITM_VOLM		= $row->ITM_VOLM;
							//$ITM_PRICE		= $row->ITM_PRICE;
							//$ITM_TOTALP		= $row->ITM_TOTALP;
							//$ITM_IN			= $row->ITM_IN;
							//$ITM_OUT		= $row->ITM_OUT;
							//$STATUS			= $row->STATUS;
							//if($STATUS == 1)
							//{
							//	$STATUSDESC	= "Active";
							//}
							//else
							//{
							//	$STATUSDESC	= "In Active";
							//}
							
							//$TotQTYIN			= $ITM_VOLM - $ITM_IN + $ITM_OUT + $ITM_IN;
							
							$secUpd		= site_url('c_asset/c_fuel_usage/update/?id='.$this->url_encryption_helper->encode_url($FU_CODE));
							
							if ($j==1) {
								echo "<tr class=zebra1>";
								$j++;
							} else {
								echo "<tr class=zebra2>";
								$j--;
							}
							?>
                                    <td style="text-align:center"> <?php print '<input name="chkDetail" id="chkDetail" type="checkbox" value="'.$FU_CODE.'" />'; ?> </td>
                                    <td nowrap> <?php print anchor("$secUpd",$FU_CODE,array('class' => 'update')).' '; ?> </td>
                                    <td style="text-align:center"> <?php print $FU_DATE; ?> </td>
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
                                    <td style="text-align:center; display:none"><?php print $FU_PRJCODE; ?>&nbsp;</td>
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
									
									/*if ($FU_TYPE==0)
										$FU_TYPE1="FU Versi Proyek";
									elseif ($FU_TYPE==1)
										$FU_TYPE1="Material";
									elseif ($FU_TYPE==2)
										$FU_TYPE1="Upah";
									elseif ($FU_TYPE==3)
										$FU_TYPE1="Subkon";
									elseif ($FU_TYPE==4)
										$FU_TYPE1="Alat";
									else
										$FU_TYPE1="-";*/
										
										
                                    ?>
                                     <?php
											$sqlA 	= "SELECT AS_NAME FROM tbl_asset_list WHERE AS_CODE='$FU_ASSET'";
											$resA	= $this->db->query($sqlA)->result();
												foreach($resA as $rowA) :
													$FU_ASSET1 	= $rowA->AS_NAME;
												endforeach
									?>	
                                    <td style="text-align:left" nowrap><?php print "$FU_ASSET - $FU_ASSET1"; ?>&nbsp;</td>
                                    <td style="text-align:right" nowrap><?php print number_format($FU_QTY, $decFormat); ?>&nbsp;</td>
                                    <!--<td style="text-align:right" nowrap><?php print number_format($FU_PRICE, $decFormat); ?>&nbsp;</td>-->
                                    <?php
												/*If($FU_STAT == 1)$FU_STATDesc = "New";
											elseif($FU_STAT == 2) $FU_STATDesc = "Confirm";
											elseif($FU_STAT == 3) $FU_STATDesc = "Approved";
											elseif($FU_STAT == 6) $FU_STATDesc = "Close";
											
											if($FU_STAT == 1)
											{
												$FU_STATD = 'New';
												$STATCOL	= 'warning';
											}
											elseif($FU_STAT == 2)
											{
												$FU_STATD = 'Confirm';
												$STATCOL	= 'primary';
											}
											elseif($FU_STAT == 3)
											{
												$FU_STATD 	= 'Approve';
												$STATCOL	= 'success';
											}
											elseif($FU_STAT == 6)
											{
												$FU_STATD 	= 'Close';
												$STATCOL	= 'danger';
											}
											else
											{
												$FU_STATD = 'Not Detected';
												$STATCOL	= 'danger';
											}*/
											
									?>
                                 <!--   <td style="text-align:center">
                                    <span class="label label-<?php //echo $STATCOL; ?>" style="font-size:12px">
										<?php 
											//echo "&nbsp;$FU_STATDesc&nbsp;";
								 		?>
									</span>
                            		</td>-->
                                    <td style="text-align:left"><?php print $FU_NOTE; ?>&nbsp;</td>
                                    <!--<td style="text-align:center"><?php //print "$ITM_UNIT"; ?></td>
                                    <td style="text-align:center"> <?php //print $STATUSDESC; ?> </td>-->
                                </tr>
                                <?php 
                        endforeach;
                    }
					$secAddURL = site_url('c_asset/c_fuel_usage/add/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
                    ?> 
                </tbody>
                <tr>
                    <td colspan="7">
                   <?php
						if($ISCREATE == 1)
						{
							echo anchor("$secAddURL",'<button class="btn btn-primary"><i class="cus-add-16x16"></i>&nbsp;&nbsp;'.$Add.'</button>');
						}
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