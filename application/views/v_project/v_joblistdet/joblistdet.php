<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 24 Maret 2017
 * File Name	= joblistdet.php
 * Location		= -
*/
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody    = $this->session->userdata('appBody');

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
if($decFormat == 0)
	$decFormat		= 2;
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $appName; ?></title>
        <!-- Tell the browser to be responsive to screen width -->
        <?php
          $vers     = $this->session->userdata('vers');

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
			
			if($TranslCode == 'Add')$Add = $LangTransl;
			if($TranslCode == 'Edit')$Edit = $LangTransl;
			if($TranslCode == 'Back')$Back = $LangTransl;
			if($TranslCode == 'JobCode')$JobCode = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'Class')$Class = $LangTransl;
			if($TranslCode == 'Group')$Group = $LangTransl;
			if($TranslCode == 'Type')$Type = $LangTransl;
			if($TranslCode == 'Volume')$Volume = $LangTransl;
			if($TranslCode == 'Amount')$Amount = $LangTransl;
			if($TranslCode == 'Unit')$Unit = $LangTransl;
			if($TranslCode == 'Qty')$Qty = $LangTransl;
			if($TranslCode == 'Price')$Price = $LangTransl;
			if($TranslCode == 'Used')$Used = $LangTransl;
			if($TranslCode == 'Remain')$Remain = $LangTransl;
		endforeach;
		if($LangID == 'IND')
		{
			$sureDelete	= "Anda yakin akan menghapus data ini?";
			$h_title	= "WBS Detail";
		}
		else
		{
			$sureDelete	= "Are your sure want to delete?";
			$h_title	= "WBS Detail";
		}

		$comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
			<h1>
			    <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/wbs.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $h_title; ?> 
			    <small><?php echo $PRJNAME; ?> </small>
			  </h1>
		</section>

		<style>
			.search-table, td, th {
				border-collapse: collapse;
			}
			.search-table-outter { overflow-x: scroll; }
			
		    a[disabled="disabled"] {
		        pointer-events: none;
		    }
		</style>

        <section class="content">
			<div class="box">
				<div class="box-body">
					<div class="search-table-outter">
				      <table id="" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
				        <thead>
				            <tr>
				                <th><?php echo $Description; ?></th>
				                <th width="5%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Unit; ?></th>
				                <th width="6%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Volume; ?></th>
				                <th width="6%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Price; ?></th>
				                <th width="6%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Amount; ?></th>
				                <th width="5%" style="text-align:center; vertical-align:middle" nowrap>Add. (+)</th>
				                <th width="7%" style="text-align:center; vertical-align:middle; display:none" nowrap>Add. (-)</th>
				                <th width="6%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Used; ?></th>
				                <th width="3%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Remain; ?></th>
				                <th width="3%" style="text-align:center; vertical-align:middle" nowrap>Projection<br>
				                Complete</th>
				                <th width="3%" style="text-align:center; vertical-align:middle" nowrap>&nbsp;</th>
				          </tr>
				        </thead>
				        <tbody>
						<?php
							$myNewNo=0;
							$i 		= 0;
							$j 		= 0;
							$TotBOQ	= 0;
							$TotBUD	= 0;
							$TotADD	= 0;
							$TotADD2= 0;
							$TotADD3= 0;
							$TotADD4= 0;
							$TotADD5= 0;
							$TotALL	= 0;
							$TotREM	= 0;
							$TotUSE	= 0;
							$TotPC	= 0;	// Total Project Complete
							if($countjobl > 0)
							{
								foreach($vwjoblist as $row) :
									$myNewNo 	= $myNewNo + 1;;
									$JOBCODEID 	= $row->JOBCODEID;
									$JOBCODEIDV	= $row->JOBCODEID;
									$JOBDESC 	= $row->JOBDESC;
									//$JOBDESC	= strtolower($JOBDESC);
									$JOBGRP 	= $row->ITM_GROUP;
									$JOBUNIT 	= strtoupper($row->ITM_UNIT);
									$JOBLEV 	= $row->IS_LEVEL;
									$JOBVOLM 	= round($row->ITM_VOLM,2);
									$JOBPRICE 	= round($row->ITM_PRICE,2);
									$JOBCOST 	= round($row->ITM_BUDG,2);
									$ADD_VOLM 	= round($row->ADD_VOLM,2);
									$ADD_PRICE 	= round($row->ADD_PRICE,2);
									$ADD_JOBCOST= round($row->ADD_JOBCOST,2);
									$ADDM_VOLM 	= round($row->ADDM_VOLM,2);
									$ADDM_JOBCOST= round($row->ADDM_JOBCOST,2);
									$ITM_USED_AM= round($row->ITM_USED_AM,2);
										
									/*$TotBOQ		= $TotBOQ + $JOBCOST + $ADD_JOBCOST;
									$TotADD		= $TotADD + $ADD_JOBCOST;
									$TotALL		= $TotBOQ + $TotADD;*/
									
									$JOBDESC1		= cut_text ("$JOBDESC", 40);
									$secUpd			= site_url('c_project/c_joblistdet/update/?id='.$this->url_encryption_helper->encode_url($JOBCODEID));
									$space_level1 	= "&nbsp;&nbsp;&nbsp;&nbsp;";
									$JobView		= "$JOBCODEIDV - $JOBDESC1";
									
									$ISHEADER 		= 0;
									$sqlC2			= "tbl_boqlist WHERE JOBPARENT = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";
									$ressqlC2 		= $this->db->count_all($sqlC2);
									if($ressqlC2 > 0)
										$ISHEADER	= 1;
									
									if($ISHEADER == 1)
									{
										$STATCOL	= 'primary';
										if ($j==1) {
											echo "<tr class=zebra1 style=font-weight:bold>";
											$j++;
										} else {
											echo "<tr class=zebra2 style=font-weight:bold>";
											$j--;
										}
										// PENJUMLAHAN ADDENDUM
											$TOT_BUDGAM	= 0;
											$TOT_ADDAM	= 0;
											$sqlTBUDG	= "SELECT SUM(ITM_BUDG) AS TOT_BUDGAM, SUM(ADD_JOBCOST) AS TOT_ADDAM, SUM(ITM_USED_AM) AS TOT_USEDAM
															FROM tbl_joblist_detail
															WHERE JOBPARENT LIKE '$JOBCODEID%' AND PRJCODE = '$PRJCODE'
																AND IS_LEVEL > $JOBLEV AND ISLAST = 1";
											$resTBUDG 	= $this->db->query($sqlTBUDG)->result();
											foreach($resTBUDG as $rowTBUDG) :
												$TOT_BUDGAM	= $rowTBUDG->TOT_BUDGAM;
												$TOT_ADDAM 	= $rowTBUDG->TOT_ADDAM;
											endforeach;
											$JOBCOST		= round($TOT_BUDGAM,2);
											$ADD_JOBCOST	= round($TOT_ADDAM,2);
											
										$TotBOQ		= $TotBOQ;
										$TotADD		= $TotADD;
										$TotALL		= $TotBOQ;
										
										$REMAIN		= 0;
										
										$ITM_USED_AM= 0;
									}
									else
									{
										$STATCOL	= 'success';
										if ($j==1) {
											echo "<tr class=zebra1>";
											$j++;
										} else {
											echo "<tr class=zebra2>";
											$j--;
										}
										$TotBUD		= $TotBUD + $JOBCOST;
										
										$TotBOQ		= $TotBOQ + $JOBCOST + $ADD_JOBCOST;
										$TotADD		= $TotADD + $ADD_JOBCOST;
										$TotALL		= $TotBOQ + $TotADD;
										$TotUSE		= $TotUSE + $ITM_USED_AM;
										
										$REMAIN		= $JOBCOST + $ADD_JOBCOST - $ITM_USED_AM;
										$TotREM		= $TotREM + $REMAIN;
									}
									
									$TOT_JOBCOST	= $JOBCOST + $ADD_JOBCOST - $ITM_USED_AM;
									
									if($ISHEADER != 1)
									{
										// COUNT PROJECTION COMPLETED
											$ITM_LASTP	= $row->ITM_LASTP;
											$ITM_USED	= $row->ITM_USED;
											$TOTUSED_01	= $ITM_USED_AM;
											if($JOBUNIT == 'LS')
												$TOTREMA_01	= ($JOBCOST + $ADD_JOBCOST - $TOTUSED_01);
											else
												$TOTREMA_01	= ($JOBVOLM + $ADD_VOLM - $ITM_USED) * $ITM_LASTP;
											
											$TOTPRJC_01	= $TOTUSED_01 + $TOTREMA_01;	// Total Projection Complete
											$TotPC		= $TotPC + $TOTPRJC_01;
									}
									
									if($TOT_JOBCOST == 0)
										$TOT_JOBCOST = 1;
									
									$percBDG		= $ITM_USED_AM / $TOT_JOBCOST * 100;		// Used Percentation
									if($percBDG > 85)
										$STATCOL	= 'danger';
									elseif($percBDG > 60)
										$STATCOL	= 'warning';
									elseif($percBDG > 0)
										$STATCOL	= 'success';
										
									$secupdJob	= site_url('c_project/c_joblistdet/upd1d0ebb/?id='.$this->url_encryption_helper->encode_url($JOBCODEID));
									
									$LinkJD1		= "$PRJCODE~$JOBCODEID";
									$secShowJD1		= site_url('c_project/c_joblistdet/sH0wJD/?id='.$this->url_encryption_helper->encode_url($LinkJD1));
									?>
										<td width="50%" nowrap><?php print "$JobView"; ?> </td>
										<td nowrap style="text-align:center"><?php echo $JOBUNIT; ?></td>
										<td nowrap style="text-align:right"><?php print number_format($JOBVOLM, $decFormat); ?> </td>
										<td nowrap style="text-align:right"><?php print number_format($JOBPRICE, $decFormat); ?> </td>
										<td nowrap style="text-align:right">
				                        	<?php if($ISHEADER != 1) { ?>
				                                <span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
				                                    <?php print number_format($JOBCOST, $decFormat); ?>
				                                </span>
				                            <?php } ?>
				                        </td>
										<td nowrap style="text-align:right">
				                            <span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
				                                <?php print number_format($ADD_JOBCOST, $decFormat); ?>
				                            </span>
				                        </td>
										<td nowrap style="text-align:right; display:none">
				                            <span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
				                                <?php print number_format($ADDM_JOBCOST, $decFormat); ?>
				                            </span>
				                        </td>
										<td nowrap style="text-align:right">
				                        	<?php if($ISHEADER != 1) { ?>
				                                <span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
				                                    <a onclick="showDetC('<?php echo $secShowJD1; ?>')" style="cursor: pointer; color:#FFF">
				                                        <?php echo number_format($ITM_USED_AM, $decFormat); ?>
				                                    </a>
				                                </span>
				                            <?php } else { ?>
				                                <span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
				                                    <?php print number_format($ITM_USED_AM, $decFormat); ?>
				                                </span>
				                        	<?php } ?>
				                        </td>
										<td nowrap style="text-align:right">
				                            <span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
				                                <?php print number_format($REMAIN, $decFormat); ?>
				                            </span>
				                        </td>
										<td nowrap style="text-align:right">
				                        	<?php if($ISHEADER != 1) { ?>
				                                <span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
				                                    <?php print number_format($TOTPRJC_01, $decFormat); ?>
				                                </span>
				                            <?php } ?>
				                        </td>
				                        <input type="hidden" name="urlupdateJob<?php echo $myNewNo; ?>" id="urlupdateJob<?php echo $myNewNo; ?>" value="<?php echo $secupdJob; ?>">
				                        <td nowrap style="text-align:right">
				                            <a href="javascript:void(null);" class="btn btn-info btn-xs" title="Update" onClick="updateJob('<?php echo $myNewNo; ?>')">
				                                <i class="glyphicon glyphicon-pencil"></i>
				                            </a>
				                            <a href="" class="btn btn-danger btn-xs" title="In Active Project" onclick="return confirm('<?php echo $sureDelete; ?>')" disabled="disabled" style="display:none">
				                                <i class="glyphicon glyphicon-trash"></i>
				                            </a>
				                        </td>
								    </tr>
									<?php
									if($ressqlC2 > 0)
									{
										/*$sqlJOB2	= "SELECT JOBCODEID, JOBCODEIDV, JOBPARENT, JOBDESC, JOBGRP, JOBCLASS, JOBUNIT, JOBVOLM, 
															JOBCOST, ISHEADER, PRICE, ADD_VOLM, ADD_PRICE, ADD_JOBCOST, ADDM_JOBCOST
														FROM tbl_joblist
														WHERE JOBPARENT = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";*/
										$sqlJOB2	= "SELECT JOBCODEID, JOBCODEID AS JOBCODEIDV, JOBPARENT, JOBDESC, ITM_GROUP AS JOBGRP,
															ITM_UNIT AS JOBUNIT, ITM_VOLM AS JOBVOLM, ITM_BUDG AS JOBCOST, ITM_PRICE AS PRICE,
															ADD_VOLM, ADD_PRICE, ADD_JOBCOST, ADDM_JOBCOST, ITM_USED_AM, IS_LEVEL,
															ITM_LASTP, ITM_USED
														FROM tbl_joblist_detail
														WHERE JOBPARENT = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";
										$resJOB2 	= $this->db->query($sqlJOB2)->result();
										foreach($resJOB2 as $rowJOB2) :
											$myNewNo		= $myNewNo + 1;
											$JOBCODEID2 	= $rowJOB2->JOBCODEID;
											$JOBCODEID2V	= $rowJOB2->JOBCODEIDV;
											$JOBPARENT2		= $rowJOB2->JOBPARENT;
											$JOBDESC2 		= $rowJOB2->JOBDESC;
											//$JOBDESC2		= strtolower($JOBDESC2);
											$JOBDESC2		= cut_text ("$JOBDESC2", 40);
											$JOBGRP2 		= $rowJOB2->JOBGRP;
											//$JOBCLASS2 	= $rowJOB2->JOBCLASS;
											$JOBUNIT2 		= strtoupper($rowJOB2->JOBUNIT);
											$JOBVOLM2A 		= round($rowJOB2->JOBVOLM,2);
											$JOBPRICE2 		= round($rowJOB2->PRICE,2);
											$JOBCOST2A 		= round($rowJOB2->JOBCOST,2);
											$JOBLEV2		= $rowJOB2->IS_LEVEL;
									
											$ADD_VOLM2 		= round($rowJOB2->ADD_VOLM,2);
											$ADD_PRICE2 	= round($rowJOB2->ADD_PRICE,2);
											$ADD_JOBCOST2	= round($rowJOB2->ADD_JOBCOST,2);
											$ADDM_JOBCOST2	= round($rowJOB2->ADDM_JOBCOST,2);
											//$JOBCOST2 		= $JOBCOST2A + $ADD_JOBCOST2;
											$JOBCOST2 		= round($JOBCOST2A,2);
											//$ISHEADER2 		= $rowJOB2->ISHEADER;
											$ITM_USED_AM2	= round($rowJOB2->ITM_USED_AM,2);
											
											$JOBVOLM2 		= round($JOBVOLM2A + $ADD_VOLM2,2);
											
											/*$TotADD			= $TotADD + $ADD_JOBCOST2;
											$TotALL			= $TotBOQ + $TotADD;*/
											
											$JobView2		= "$JOBCODEID2V - $JOBDESC2";
											$space_level2	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
											$secUpd2		= site_url('c_project/c_joblistdet/update/?id='.$this->url_encryption_helper->encode_url($JOBCODEID2));
									
											$ISHEADER2		= 0;
											$sqlC3			= "tbl_boqlist WHERE JOBPARENT = '$JOBCODEID2' AND PRJCODE = '$PRJCODE'";
											$ressqlC3 		= $this->db->count_all($sqlC3);
											if($ressqlC3 > 0)
												$ISHEADER2	= 1;
											
											if($ISHEADER2 == 1)
											{
												$STATCOL	= 'primary';
												if ($j==1) {
													echo "<tr class=zebra1 style=font-weight:bold>";
													$j++;
												} else {
													echo "<tr class=zebra2 style=font-weight:bold>";
													$j--;
												}
												// PENJUMLAHAN ADDENDUM
													$TOT_BUDGAM	= 0;
													$TOT_ADDAM	= 0;
													$sqlTBUDG	= "SELECT SUM(ITM_BUDG) AS TOT_BUDGAM, SUM(ADD_JOBCOST) AS TOT_ADDAM, SUM(ITM_USED_AM) AS TOT_USEDAM
																	FROM tbl_joblist_detail
																	WHERE JOBPARENT LIKE '$JOBCODEID2%' AND PRJCODE = '$PRJCODE'
																		AND IS_LEVEL > $JOBLEV2 AND ISLAST = 1";
													$resTBUDG 	= $this->db->query($sqlTBUDG)->result();
													foreach($resTBUDG as $rowTBUDG) :
														$TOT_BUDGAM	= $rowTBUDG->TOT_BUDGAM;
														$TOT_ADDAM 	= $rowTBUDG->TOT_ADDAM;
													endforeach;
													$JOBCOST2		= round($TOT_BUDGAM,2);
													$ADD_JOBCOST2	= round($TOT_ADDAM,2);
												
												$TotADD		= $TotADD;
												$TotALL		= $TotBOQ;
												
												$REMAIN2	= 0;
											}
											else
											{
												$STATCOL	= 'success';
												if ($j==1) {
													echo "<tr class=zebra1>";
													$j++;
												} else {
													echo "<tr class=zebra2>";
													$j--;
												}
												
												$TotBUD		= $TotBUD + $JOBCOST2;
												
												$TotADD		= $TotADD + $ADD_JOBCOST2;
												$TotALL		= $TotBOQ + $TotADD;
												$TotUSE		= $TotUSE + $ITM_USED_AM2;
										
												$REMAIN2	= $JOBCOST2 + $ADD_JOBCOST2 - $ITM_USED_AM2;
												$TotREM		= $TotREM + $REMAIN2;
											}
											
											//$TOT_JOBCOST2	= $JOBCOST2 + $ADD_JOBCOST2 - $ITM_USED_AM2;
											$TOT_JOBCOST2	= $JOBCOST2 + $ADD_JOBCOST2;
											if($TOT_JOBCOST2 == 0)
												$TOT_JOBCOST2 = 1;
														
											$TOT_JOBCOST2A	= $JOBCOST2 + $ADD_JOBCOST2;
											if($TOT_JOBCOST2A == 0)
												$TOT_JOBCOST2A = 1;
											
											$TOTPRJC_02	= 0;
											if($ISHEADER2 != 1)
											{
												// COUNT PROJECTION COMPLETED
												// FORMULA	= USED_AMOUNT + (REM_VOLM * LAST_PRICE)
													$ITM_LASTP_02	= $rowJOB2->ITM_LASTP;
													$ITM_USED_02	= $rowJOB2->ITM_USED;
													$TOTUSED_02		= $ITM_USED_AM2;
													$TOTREMV_02		= $JOBVOLM2A + $ADD_VOLM2 - $ITM_USED_02;	// SISAL VOLUME
													if($TOTREMV_02 < 0)
														$TOTREMV_02 = 1;
														
													if($JOBUNIT2 == 'LS')
													{
														$TOTREMA_02	= ($JOBCOST2 + $ADD_JOBCOST2 - $TOTUSED_02);
													}
													else
													{
														//$TOTREMA_02	= ($TOTREMV_02 * $ITM_LASTP_02) + $ADD_JOBCOST2;
														$TOTREMA_02	= round(($TOTREMV_02 * $ITM_LASTP_02),2);
													}
														
													$TOTPRJC_02		= $TOTUSED_02 + $TOTREMA_02;	// Total Projection Complete
													$TotPC			= $TotPC + $TOTPRJC_02;
											}
											
											//$STATCOLPC_02		= 'success';							// Status Color Projection Complete
											$STATCOLPC_02		= 'info';								// Status Color Projection Complete
											$PRPERCENT_02		= $TOTPRJC_02 / $TOT_JOBCOST2A;
											if($PRPERCENT_02 > 1.5)
											{
												$STATCOLPC_02		= 'danger';
											}
											elseif($PRPERCENT_02 > 1)
											{
												$STATCOLPC_02		= 'warning';
											}
											elseif($PRPERCENT_02 == 1)
											{
												$STATCOLPC_02		= 'info';
											}
											
											$percBDG2	= $ITM_USED_AM2 / $TOT_JOBCOST2 * 100;		// Used Percentation
											if($percBDG2 > 85)
												$STATCOL	= 'danger';
											elseif($percBDG2 > 60)
												$STATCOL	= 'warning';
											elseif($percBDG2 > 0)
												$STATCOL	= 'success';
											$secupdJob2	= site_url('c_project/c_joblistdet/upd1d0ebb/?id='.$this->url_encryption_helper->encode_url($JOBCODEID2));
									
											$LinkJD2		= "$PRJCODE~$JOBCODEID2";
											$secShowJD2		= site_url('c_project/c_joblistdet/sH0wJD/?id='.$this->url_encryption_helper->encode_url($LinkJD2));
											?>
				                                	<td nowrap>
													<?php
														print $space_level2;
														print $JobView2;
													?>
													</td>
				                                    <td nowrap style="text-align:center"><?php echo $JOBUNIT2; ?></td>
				                                    <td nowrap style="text-align:right"><?php print number_format($JOBVOLM2, $decFormat); ?></td>
				                                    <td nowrap style="text-align:right"><?php print number_format($JOBPRICE2, $decFormat); ?></td>
				                                    <td nowrap style="text-align:right">
				                                    	<?php if($ISHEADER2 != 1) { ?>
				                                            <span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
				                                                <?php 
																	print number_format($JOBCOST2, $decFormat);
																?>
				                                            </span>
				                                        <?php } ?>
				                                    </td>
				                                    <td nowrap style="text-align:right">
				                                        <span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
				                                            <?php print number_format($ADD_JOBCOST2, $decFormat); ?>
				                                        </span>
				                                    </td>
				                                    <td nowrap style="text-align:right; display:none">
				                                        <span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
				                                            <?php
				                                            	print number_format($ADDM_JOBCOST2, $decFormat);
				                                            ?>
				                                        </span>
				                                    </td>
				                                    <td nowrap style="text-align:right">                                        
														<?php if($ISHEADER2 != 1) { ?>
				                                            <span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
				                                                <a onclick="showDetC('<?php echo $secShowJD2; ?>')" style="cursor: pointer; color:#FFF">
				                                                    <?php echo number_format($ITM_USED_AM2, $decFormat); ?>
				                                                </a>
				                                            </span>
				                                        <?php } else { ?>
				                                            <span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
				                                                <?php print number_format($ITM_USED_AM2, $decFormat); ?>
				                                            </span>
				                                        <?php } ?>
				                                    </td>
				                                    <td nowrap style="text-align:right">
				                                        <span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
				                                            <?php 
																//print number_format($TOT_JOBCOST2, $decFormat); 
																print number_format($REMAIN2, $decFormat);
															?>
				                                        </span>
				                                    </td>
				                                    <td nowrap style="text-align:right">
													<?php if($ISHEADER2 != 1) { ?>
				                                        <span class="label label-<?php echo $STATCOLPC_02; ?>" style="font-size:12px">
				                                            <?php 
				                                            	print number_format($TOTPRJC_02, $decFormat);
				                                            ?>
				                                        </span>
				                                    <?php } ?>
				                                    </td>
				                        			<input type="hidden" name="urlupdateJob<?php echo $myNewNo; ?>" id="urlupdateJob<?php echo $myNewNo; ?>" value="<?php echo $secupdJob2; ?>">
				                                    <td nowrap style="text-align:right">
				                                    <a href="javascript:void(null);" class="btn btn-info btn-xs" title="Update" onClick="updateJob('<?php echo $myNewNo; ?>')">
				                                        <i class="glyphicon glyphicon-pencil"></i>
				                                    </a>
				                                    <a href="" class="btn btn-danger btn-xs" title="In Active Project" onclick="return confirm('<?php echo $sureDelete; ?>')" disabled="disabled" style="display:none">
				                                        <i class="glyphicon glyphicon-trash"></i>
				                                    </a>
				                                </td>
										    </tr>
											<?php
											$i	= 0;
											if($ressqlC3 > 0)
											{
												/*$sqlJOB3	= "SELECT JOBCODEID, JOBCODEIDV, JOBPARENT, JOBDESC, JOBGRP, JOBCLASS, JOBUNIT, 
																	JOBVOLM, JOBCOST, ISHEADER, PRICE, ADD_VOLM, ADD_PRICE, 
																	ADD_JOBCOST, ADDM_JOBCOST
																FROM tbl_joblist
																WHERE JOBPARENT = '$JOBCODEID2' AND PRJCODE = '$PRJCODE'";*/
												$sqlJOB3	= "SELECT JOBCODEID, JOBCODEID AS JOBCODEIDV, JOBPARENT, JOBDESC, ITM_GROUP AS JOBGRP,
																	ITM_UNIT AS JOBUNIT, ITM_VOLM AS JOBVOLM, ITM_BUDG AS JOBCOST, 
																	ITM_PRICE AS PRICE, 
																	ADD_VOLM, ADD_PRICE, ADD_JOBCOST, ADDM_JOBCOST, ITM_USED_AM, IS_LEVEL,
																	ITM_LASTP, ITM_USED
																FROM tbl_joblist_detail
																WHERE JOBPARENT = '$JOBCODEID2' AND PRJCODE = '$PRJCODE'";
												$resJOB3 	= $this->db->query($sqlJOB3)->result();
												foreach($resJOB3 as $rowJOB3) :
													$myNewNo 		= $myNewNo + 1;
													$JOBCODEID3 	= $rowJOB3->JOBCODEID;
													$JOBCODEID3V	= $rowJOB3->JOBCODEIDV;
													$JOBPARENT3		= $rowJOB3->JOBPARENT;
													$JOBDESC3 		= $rowJOB3->JOBDESC;
													//$JOBDESC3		= strtolower($JOBDESC3);
													$JOBDESC3		= cut_text ("$JOBDESC3", 40);
													$JOBGRP3 		= $rowJOB3->JOBGRP;
													//$JOBCLASS3 	= $rowJOB3->JOBCLASS;
													$JOBUNIT3 		= strtoupper($rowJOB3->JOBUNIT);
													$JOBVOLM3A 		= round($rowJOB3->JOBVOLM,2);
													$JOBPRICE3 		= round($rowJOB3->PRICE,2);
													$JOBCOST3 		= round($rowJOB3->JOBCOST,2);
													$JOBCOST3A 		= round($rowJOB3->JOBCOST,2);
													$JOBLEV3		= $rowJOB3->IS_LEVEL;
											
													$ADD_VOLM3 		= round($rowJOB3->ADD_VOLM,2);
													$ADD_PRICE3 	= round($rowJOB3->ADD_PRICE,2);
													$ADD_JOBCOST3	= round($rowJOB3->ADD_JOBCOST,2);
													$ADDM_JOBCOST3	= round($rowJOB3->ADDM_JOBCOST,2);
													//$JOBCOST3 		= $JOBCOST3A + $ADD_JOBCOST3;
													$JOBCOST3 		= $JOBCOST3A;
													//$ISHEADER3 		= $rowJOB3->ISHEADER;
													$ITM_USED_AM3	= round($rowJOB3->ITM_USED_AM,2);
											
													$JOBVOLM3 		= round(($JOBVOLM3A + $ADD_VOLM3),2);
													
													/*$TotADD			= $TotADD + $ADD_JOBCOST3;
													$TotALL			= $TotBOQ + $TotADD;*/
													
													$JobView3		= "$JOBCODEID3V - $JOBDESC3";
													$space_level3	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
													$secUpd3		= site_url('c_project/c_joblistdet/update/?id='.$this->url_encryption_helper->encode_url($JOBCODEID3));
									
													$ISHEADER3		= 0;
													$sqlC4			= "tbl_boqlist WHERE JOBPARENT = '$JOBCODEID3' AND PRJCODE = '$PRJCODE'";
													$ressqlC4 		= $this->db->count_all($sqlC4);
													if($ressqlC4 > 0)
														$ISHEADER3	= 1;
													
													if($ISHEADER3 == 1)
													{
														$STATCOL	= 'primary';
														if ($j==1) {
															echo "<tr class=zebra1 style=font-weight:bold>";
															$j++;
														} else {
															echo "<tr class=zebra2 style=font-weight:bold>";
															$j--;
														}
														// PENJUMLAHAN ADDENDUM
															$TOT_BUDGAM	= 0;
															$TOT_ADDAM	= 0;
															$sqlTBUDG	= "SELECT SUM(ITM_BUDG) AS TOT_BUDGAM, SUM(ADD_JOBCOST) AS TOT_ADDAM, SUM(ITM_USED_AM) AS TOT_USEDAM
																			FROM tbl_joblist_detail
																			WHERE JOBPARENT LIKE '$JOBCODEID3%' AND PRJCODE = '$PRJCODE'
																				AND IS_LEVEL > $JOBLEV3 AND ISLAST = 1";
															$resTBUDG 	= $this->db->query($sqlTBUDG)->result();
															foreach($resTBUDG as $rowTBUDG) :
																$TOT_BUDGAM	= $rowTBUDG->TOT_BUDGAM;
																$TOT_ADDAM 	= $rowTBUDG->TOT_ADDAM;
															endforeach;
															$JOBCOST3		= round($TOT_BUDGAM,2);
															$ADD_JOBCOST3	= round($TOT_ADDAM,2);
															
														$TotADD			= $TotADD;
														$TotALL			= $TotBOQ;
													}
													else
													{
														$STATCOL	= 'success';
														if ($j==1) {
															echo "<tr class=zebra1>";
															$j++;
														} else {
															echo "<tr class=zebra2>";
															$j--;
														}
												
														$TotBUD	= $TotBUD + $JOBCOST3;
														
														$TotADD		= $TotADD + $ADD_JOBCOST3;
														$TotALL		= $TotBOQ + $TotADD;
														$TotUSE		= $TotUSE + $ITM_USED_AM3;
										
														$REMAIN3	= $JOBCOST3 + $ADD_JOBCOST3 - $ITM_USED_AM3;
														$TotREM		= $TotREM + $REMAIN3;
													}
													
													$TOT_JOBCOST3	= $JOBCOST3 + $ADD_JOBCOST3 - $ITM_USED_AM3;
														
													if($TOT_JOBCOST3 == 0)
														$TOT_JOBCOST3 = 1;
														
													$TOT_JOBCOST3A	= $JOBCOST3 + $ADD_JOBCOST3;
													if($TOT_JOBCOST3A == 0)
														$TOT_JOBCOST3A = 1;
													
													$TOTPRJC_03	= 0;
													if($ISHEADER3 != 1)
													{
														// COUNT PROJECTION COMPLETED
														// FORMULA	= USED_AMOUNT + (REM_VOLM * LAST_PRICE)
															$ITM_LASTP_03	= $rowJOB3->ITM_LASTP;
															$ITM_USED_03	= $rowJOB3->ITM_USED;
															$TOTUSED_03		= $ITM_USED_AM3;
															$TOTREMV_03		= $JOBVOLM3A + $ADD_VOLM3 - $ITM_USED_03;
															if($TOTREMV_03 < 0)
																$TOTREMV_03 = 1;
																
															if($JOBUNIT3 == 'LS')
															{
																$TOTREMA_03	= ($JOBCOST3 + $ADD_JOBCOST3 - $TOTUSED_03);
															}
															else
															{
																//$TOTREMA_03	= ($TOTREMV_03 * $ITM_LASTP_03) + $ADD_JOBCOST3;
																$TOTREMA_03	= ($TOTREMV_03 * $ITM_LASTP_03);
															}
																
															$TOTPRJC_03		= $TOTUSED_03 + $TOTREMA_03;	// Total Projection Complete
															$TotPC			= $TotPC + $TOTPRJC_03;
													}
													
													$STATCOLPC_03		= 'success';							// Status Color Projection Complete
													$PRPERCENT_03		= $TOTPRJC_03 / $TOT_JOBCOST3A;
													if($PRPERCENT_03 > 1.5)
													{
														$STATCOLPC_03		= 'danger';
													}
													elseif($PRPERCENT_03 > 1)
													{
														$STATCOLPC_03		= 'warning';
													}
													elseif($PRPERCENT_03 == 1)
													{
														$STATCOLPC_03		= 'info';
													}
													
													$percBDG3		= $ITM_USED_AM3 / $TOT_JOBCOST3 * 100;		// Used Percentation
													if($percBDG3 > 85)
														$STATCOL	= 'danger';
													elseif($percBDG3 > 60)
														$STATCOL	= 'warning';
													elseif($percBDG3 > 0)
														$STATCOL	= 'success';
														
													$secupdJob3	= site_url('c_project/c_joblistdet/upd1d0ebb/?id='.$this->url_encryption_helper->encode_url($JOBCODEID3));
									
													$LinkJD3		= "$PRJCODE~$JOBCODEID3";
													$secShowJD3		= site_url('c_project/c_joblistdet/sH0wJD/?id='.$this->url_encryption_helper->encode_url($LinkJD3));
														?>
				                                              <td nowrap>
															<?php 
																print $space_level3;
																print $JobView3;
															?>										</td>
				                                            <td nowrap style="text-align:center"><?php echo $JOBUNIT3; ?></td>
				                                            <td nowrap style="text-align:right"><?php print number_format($JOBVOLM3, $decFormat); ?></td>
				                                            <td nowrap style="text-align:right"><?php print number_format($JOBPRICE3, $decFormat); ?></td>
				                                            <td nowrap style="text-align:right">
																<?php if($ISHEADER3 != 1) { ?>
				                                                    <span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
				                                                        <?php print number_format($JOBCOST3, $decFormat); ?>
				                                                    </span>
				                                                <?php } ?>
				                                            </td>
				                                            <td nowrap style="text-align:right">
				                                                <span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
				                                                    <?php print number_format($ADD_JOBCOST3, $decFormat); ?>
				                                                </span>
				                                            </td>
				                                            <td nowrap style="text-align:right; display:none">
				                                                <span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
				                                                    <?php print number_format($ADDM_JOBCOST3, $decFormat); ?>
				                                                </span>
				                                            </td>
				                                            <td nowrap style="text-align:right">
																<?php if($ISHEADER3 != 1) { ?>
				                                                    <span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
				                                                        <a onclick="showDetC('<?php echo $secShowJD3; ?>')" style="cursor: pointer; color:#FFF">
				                                                            <?php echo number_format($ITM_USED_AM3, $decFormat); ?>
				                                                        </a>
				                                                    </span>
				                                                <?php } else { ?>
				                                                    <span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
				                                                        <?php print number_format($ITM_USED_AM3, $decFormat); ?>
				                                                    </span>
				                                                <?php } ?>
				                                            </td>
				                                            <td nowrap style="text-align:right">
				                                                <span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
				                                                    <?php 
																		//print number_format($TOT_JOBCOST3, $decFormat); 
																		print number_format($REMAIN3, $decFormat); 
																	?>
				                                                </span>
				                                            </td>
				                                            <td nowrap style="text-align:right">
															<?php if($ISHEADER3 != 1) { ?>
				                                                <span class="label label-<?php echo $STATCOLPC_03; ?>" style="font-size:12px">
				                                                    <?php 
																		print number_format($TOTPRJC_03, $decFormat);
																	?>
				                                                </span>
				                                            <?php } ?>
				                                            </td>
				                               				<input type="hidden" name="urlupdateJob<?php echo $myNewNo; ?>" id="urlupdateJob<?php echo $myNewNo; ?>" value="<?php echo $secupdJob3; ?>">
				                                            <td nowrap style="text-align:right">
				                                            <a href="javascript:void(null);" class="btn btn-info btn-xs" title="Update" onClick="updateJob('<?php echo $myNewNo; ?>')">
				                                                <i class="glyphicon glyphicon-pencil"></i>
				                                            </a>
				                                            <a href="" class="btn btn-danger btn-xs" title="In Active Project" onclick="return confirm('<?php echo $sureDelete; ?>')" disabled="disabled" style="display:none">
				                                                <i class="glyphicon glyphicon-trash"></i>
				                                            </a>
				                                        </td>
												    </tr>
													<?php
													if($ressqlC4 > 0)
													{
														/*$sqlJOB4	= "SELECT JOBCODEID, JOBCODEIDV, JOBPARENT, JOBDESC, JOBGRP, JOBCLASS, 
																			JOBUNIT, JOBVOLM, JOBCOST, ISHEADER, PRICE, ADD_VOLM, ADD_PRICE, 
																			ADD_JOBCOST, ADDM_JOBCOST
																		FROM tbl_joblist
																		WHERE JOBPARENT = '$JOBCODEID3' AND PRJCODE = '$PRJCODE'";*/
														$sqlJOB4	= "SELECT JOBCODEID, JOBCODEID AS JOBCODEIDV, JOBPARENT, JOBDESC,
																			ITM_GROUP AS JOBGRP, ITM_UNIT AS JOBUNIT, ITM_VOLM AS JOBVOLM, 
																			ITM_BUDG AS JOBCOST, ITM_PRICE AS PRICE,
																			ADD_VOLM, ADD_PRICE, ADD_JOBCOST, ADDM_JOBCOST, ITM_USED_AM, IS_LEVEL,
																			ITM_LASTP, ITM_USED
																		FROM tbl_joblist_detail
																		WHERE JOBPARENT = '$JOBCODEID3' AND PRJCODE = '$PRJCODE'";
														$resJOB4 	= $this->db->query($sqlJOB4)->result();
														foreach($resJOB4 as $rowJOB4) :
															$myNewNo		= $myNewNo + 1;
															$JOBCODEID4 	= $rowJOB4->JOBCODEID;
															$JOBCODEID4V	= $rowJOB4->JOBCODEIDV;
															$JOBPARENT4		= $rowJOB4->JOBPARENT;
															$JOBDESC4 		= $rowJOB4->JOBDESC;
															//$JOBDESC4		= strtolower($JOBDESC4);
															$JOBDESC4		= cut_text ("$JOBDESC4", 40);
															$JOBGRP4 		= $rowJOB4->JOBGRP;
															//$JOBCLASS4 		= $rowJOB4->JOBCLASS;
															$JOBUNIT4 		= strtoupper($rowJOB4->JOBUNIT);
															$JOBVOLM4A 		= round($rowJOB4->JOBVOLM,2);
															$JOBPRICE4 		= round($rowJOB4->PRICE,2);
															$JOBCOST4 		= round($rowJOB4->JOBCOST,2);
															$JOBCOST4A 		= round($rowJOB4->JOBCOST,2);
															$JOBLEV4		= $rowJOB4->IS_LEVEL;
													
															$ADD_VOLM4 		= round($rowJOB4->ADD_VOLM,2);
															$ADD_PRICE4 	= round($rowJOB4->ADD_PRICE,2);
															$ADD_JOBCOST4	= round($rowJOB4->ADD_JOBCOST,2);
															$ADDM_JOBCOST4	= round($rowJOB4->ADDM_JOBCOST,2);				
															//$JOBCOST4 		= $JOBCOST4A + $ADD_JOBCOST4;					
															$JOBCOST4 		= $JOBCOST4A;
															//$ISHEADER4 		= $rowJOB4->ISHEADER;
															$ITM_USED_AM4	= round($rowJOB4->ITM_USED_AM,2);
															
															$JOBVOLM4 		= round(($JOBVOLM4A + $ADD_VOLM4),2);
															
															/*$TotADD			= $TotADD + $ADD_JOBCOST4;
															$TotALL			= $TotBOQ + $TotADD;*/
															
															$JobView4		= "$JOBCODEID4V - $JOBDESC4";
															$space_level4	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
															$secUpd4		= site_url('c_project/c_joblistdet/update/?id='.$this->url_encryption_helper->encode_url($JOBCODEID4));
									
															$ISHEADER4		= 0;
															$sqlC5			= "tbl_boqlist WHERE JOBPARENT = '$JOBCODEID4' AND PRJCODE = '$PRJCODE'";
															$ressqlC5 		= $this->db->count_all($sqlC5);	
															if($ressqlC5 > 0)
																$ISHEADER4	= 1;
															
															$REMAIN4		= 0;
															if($ISHEADER4 == 1)
															{
																$STATCOL	= 'primary';
																if ($j==1) {
																	echo "<tr class=zebra1 style=font-weight:bold>";
																	$j++;
																} else {
																	echo "<tr class=zebra2 style=font-weight:bold>";
																	$j--;
																}
																// PENJUMLAHAN ADDENDUM
																	$TOT_BUDGAM	= 0;
																	$TOT_ADDAM	= 0;
																	$sqlTBUDG	= "SELECT SUM(ITM_BUDG) AS TOT_BUDGAM, SUM(ADD_JOBCOST) AS TOT_ADDAM, SUM(ITM_USED_AM) AS TOT_USEDAM
																					FROM tbl_joblist_detail
																					WHERE JOBPARENT LIKE '$JOBCODEID4%' AND PRJCODE = '$PRJCODE'
																						AND IS_LEVEL > $JOBLEV4 AND ISLAST = 1";
																	$resTBUDG 	= $this->db->query($sqlTBUDG)->result();
																	foreach($resTBUDG as $rowTBUDG) :
																		$TOT_BUDGAM	= $rowTBUDG->TOT_BUDGAM;
																		$TOT_ADDAM 	= $rowTBUDG->TOT_ADDAM;
																	endforeach;
																	$JOBCOST4		= round($TOT_BUDGAM,2);
																	$ADD_JOBCOST4	= round($TOT_ADDAM,2);
																	
																$TotADD			= $TotADD;
																$TotALL			= $TotBOQ;
															}
															else
															{
																$STATCOL	= 'success';
																if ($j==1) {
																	echo "<tr class=zebra1>";
																	$j++;
																} else {
																	echo "<tr class=zebra2>";
																	$j--;
																}
												
																$TotBUD	= $TotBUD + $JOBCOST4;
																
																$TotADD		= $TotADD + $ADD_JOBCOST4;
																$TotALL		= $TotBOQ + $TotADD;
																$TotUSE		= $TotUSE + $ITM_USED_AM4;
										
																$REMAIN4	= $JOBCOST4 + $ADD_JOBCOST4 - $ITM_USED_AM4;
																$TotREM		= $TotREM + $REMAIN4;
															}
															
															$TOT_JOBCOST4	= $JOBCOST4 + $ADD_JOBCOST4 - $ITM_USED_AM4;
															if($TOT_JOBCOST4 == 0)
																$TOT_JOBCOST4 = 1;
														
															$TOT_JOBCOST4A	= $JOBCOST4 + $ADD_JOBCOST4;
															if($TOT_JOBCOST4A == 0)
																$TOT_JOBCOST4A = 1;
															
															$TOTPRJC_04	= 0;
															if($ISHEADER4 != 1)
															{
																// COUNT PROJECTION COMPLETED
																// FORMULA	= USED_AMOUNT + (REM_VOLM * LAST_PRICE)
																	$ITM_LASTP_04	= $rowJOB4->ITM_LASTP;
																	$ITM_USED_04	= $rowJOB4->ITM_USED;
																	$TOTUSED_04		= $ITM_USED_AM4;
																	$TOTREMV_04		= $JOBVOLM4A + $ADD_VOLM4 - $ITM_USED_04;
																	if($TOTREMV_04 < 0)
																		$TOTREMV_04 = 1;
														
																	if($JOBUNIT4 == 'LS')
																	{
																		$TOTREMA_04	= ($JOBCOST4 + $ADD_JOBCOST4 - $TOTUSED_04);
																	}
																	else
																	{
																		//$TOTREMA_04	= ($TOTREMV_04 * $ITM_LASTP_04) + $ADD_JOBCOST4;
																		$TOTREMA_04	= ($TOTREMV_04 * $ITM_LASTP_04);
																	}
																		
																	$TOTPRJC_04		= $TOTUSED_04 + $TOTREMA_04;	// Total Projection Complete
																	$TotPC			= $TotPC + $TOTPRJC_04;
															}
															
															$STATCOLPC_04		= 'success';							// Status Color Projection Complete
															$PRPERCENT_04		= $TOTPRJC_04 / $TOT_JOBCOST4A;
															if($PRPERCENT_04 > 1.5)
															{
																$STATCOLPC_04		= 'danger';
															}
															elseif($PRPERCENT_04 > 1)
															{
																$STATCOLPC_04		= 'warning';
															}
															elseif($PRPERCENT_04 == 1)
															{
																$STATCOLPC_04		= 'info';
															}
															
															$percBDG4		= $ITM_USED_AM4 / $TOT_JOBCOST4 * 100;		// Used Percentation
															if($percBDG4 > 85)
																$STATCOL	= 'danger';
															elseif($percBDG4 > 60)
																$STATCOL	= 'warning';
															elseif($percBDG4 > 0)
																$STATCOL	= 'success';
																
															$secupdJob4	= site_url('c_project/c_joblistdet/upd1d0ebb/?id='.$this->url_encryption_helper->encode_url($JOBCODEID4));
															$LinkJD4		= "$PRJCODE~$JOBCODEID2";
															$secShowJD4		= site_url('c_project/c_joblistdet/sH0wJD/?id='.$this->url_encryption_helper->encode_url($LinkJD4));
															?>
				                                                      <td nowrap>
																	<?php 
																		print $space_level4;
																		print $JobView4;
																	?></td>
				                                                    <td nowrap style="text-align:center"><?php echo $JOBUNIT4; ?></td>
				                                                    <td nowrap style="text-align:right"><?php print number_format($JOBVOLM4, $decFormat); ?></td>
				                                                    <td nowrap style="text-align:right"><?php print number_format($JOBPRICE4, $decFormat); ?></td>
				                                                    <td nowrap style="text-align:right">
																		<?php if($ISHEADER4 != 1) { ?>
				                                                            <span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
				                                                                <?php print number_format($JOBCOST4, $decFormat); ?>
				                                                            </span>
				                                                        <?php } ?>
				                                                    </td>
				                                                    <td nowrap style="text-align:right">
				                                                        <span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
				                                                            <?php print number_format($ADD_JOBCOST4, $decFormat); ?>
				                                                        </span>
				                                                    </td>
				                                                    <td nowrap style="text-align:right; display:none">
				                                                        <span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
				                                                            <?php 
																			print "AS number_format($ADDM_JOBCOST4, $decFormat)"; ?>
				                                                        </span>
				                                                    </td>
				                                                    <td nowrap style="text-align:right">
																		<?php if($ISHEADER4 != 1) { ?>
				                                                            <span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
				                                                                <a onclick="showDetC('<?php echo $secShowJD4; ?>')" style="cursor: pointer; color:#FFF">
				                                                                    <?php echo number_format($ITM_USED_AM4, $decFormat); ?>
				                                                                </a>
				                                                            </span>
				                                                        <?php } else { ?>
				                                                            <span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
				                                                                <?php print number_format($ITM_USED_AM4, $decFormat); ?>
				                                                            </span>
				                                                        <?php } ?>
				                                                    </td>
				                                                    <td nowrap style="text-align:right">
				                                                        <span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
				                                                            <?php 
																				//print number_format($TOT_JOBCOST4, $decFormat); 
																				print number_format($REMAIN4, $decFormat); 
																			?>
				                                                        </span>
				                                                    </td>
				                                                    <td nowrap style="text-align:right">
																	<?php if($ISHEADER4 != 1) { ?>
				                                                        <span class="label label-<?php echo $STATCOLPC_04; ?>" style="font-size:12px">
				                                                            <?php print number_format($TOTPRJC_04, $decFormat); ?>
				                                                        </span>
				                                                    <?php } ?>
				                                                    </td>
				                                                    <input type="hidden" name="urlupdateJob<?php echo $myNewNo; ?>" id="urlupdateJob<?php echo $myNewNo; ?>" value="<?php echo $secupdJob4; ?>">
				                                                    <td nowrap style="text-align:right">
				                                                   <a href="javascript:void(null);" class="btn btn-info btn-xs" title="Update" onClick="updateJob('<?php echo $myNewNo; ?>')">
				                                                        <i class="glyphicon glyphicon-pencil"></i>
				                                                    </a>
				                                                    <a href="" class="btn btn-danger btn-xs" title="In Active Project" onclick="return confirm('<?php echo $sureDelete; ?>')" disabled="disabled" style="display:none">
				                                                        <i class="glyphicon glyphicon-trash"></i>
				                                                    </a>
				                                                </td>
														    </tr>
															<?php
															$sqlC5		= "tbl_boqlist WHERE JOBPARENT = '$JOBCODEID4' AND PRJCODE = '$PRJCODE'";
															$ressqlC5 	= $this->db->count_all($sqlC5);	
															if($ressqlC5 > 0)
															{
																/*$sqlJOB5	= "SELECT JOBCODEID, JOBCODEIDV, JOBPARENT, JOBDESC, JOBGRP, JOBCLASS,
																				JOBUNIT, JOBVOLM, JOBCOST, ISHEADER, PRICE, ADD_VOLM, ADD_PRICE,
																				ADD_JOBCOST, ADDM_JOBCOST
																				FROM tbl_joblist
																				WHERE JOBPARENT = '$JOBCODEID4' AND PRJCODE = '$PRJCODE'";*/
																$sqlJOB5	= "SELECT JOBCODEID, JOBCODEID AS JOBCODEIDV, JOBPARENT, JOBDESC,
																					ITM_GROUP AS JOBGRP, ITM_UNIT AS JOBUNIT, ITM_VOLM AS JOBVOLM, 
																					ITM_BUDG AS JOBCOST, ITM_PRICE AS PRICE,
																					ADD_VOLM, ADD_PRICE, ADD_JOBCOST, ADDM_JOBCOST, ITM_USED_AM,
																					IS_LEVEL, ITM_LASTP, ITM_USED
																				FROM tbl_joblist_detail
																				WHERE JOBPARENT = '$JOBCODEID4' AND PRJCODE = '$PRJCODE'";
																$resJOB5 	= $this->db->query($sqlJOB5)->result();
																foreach($resJOB5 as $rowJOB5) :
																	$myNewNo		= $myNewNo + 1;
																	$JOBCODEID5 	= $rowJOB5->JOBCODEID;
																	$JOBCODEID5V	= $rowJOB5->JOBCODEIDV;
																	$JOBPARENT5		= $rowJOB5->JOBPARENT;
																	$JOBDESC5 		= $rowJOB5->JOBDESC;
																	//$JOBDESC5		= strtolower($JOBDESC5);
																	$JOBDESC5		= cut_text ("$JOBDESC5", 40);
																	$JOBGRP5 		= $rowJOB5->JOBGRP;
																	//$JOBCLASS5 		= $rowJOB5->JOBCLASS;
																	$JOBUNIT5 		= strtoupper($rowJOB5->JOBUNIT);
																	$JOBVOLM5A 		= round($rowJOB5->JOBVOLM,2);
																	$JOBPRICE5 		= round($rowJOB5->PRICE,2);
																	$JOBCOST5 		= round($rowJOB5->JOBCOST,2);
																	$JOBCOST5A 		= round($rowJOB5->JOBCOST,2);
																	$JOBLEV5		= $rowJOB5->IS_LEVEL;
															
																	$ADD_VOLM5 		= round($rowJOB5->ADD_VOLM,2);
																	$ADD_PRICE5 	= round($rowJOB5->ADD_PRICE,2);
																	$ADD_JOBCOST5	= round($rowJOB5->ADD_JOBCOST,2);
																	$ADDM_JOBCOST5	= round($rowJOB5->ADDM_JOBCOST,2);				
																	//$JOBCOST5 		= $JOBCOST5A + $ADD_JOBCOST5;					
																	$JOBCOST5 		= $JOBCOST5A;
																	//$ISHEADER5 		= $rowJOB5->ISHEADER;
																	$ITM_USED_AM5	= round($rowJOB5->ITM_USED_AM,2);
																	
																	$JOBVOLM5		= round(($JOBVOLM5A + $ADD_VOLM5),2);
										
																	$REMAIN5		= round(($JOBCOST5 + $ADD_JOBCOST5 - $ITM_USED_AM5),2);
																	$TotREM			= round(($TotREM + $REMAIN5),2);
																	
																	/*$TotADD			= $TotADD + $ADD_JOBCOST5;
																	$TotALL			= $TotBOQ + $TotADD;*/
											
																	$JobView5		= "$JOBCODEID5V - $JOBDESC5";
																	$space_level5	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
																	$space_level5	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
																	$secUpd5		= site_url('c_project/c_joblistdet/update/?id='.$this->url_encryption_helper->encode_url($JOBCODEID5));
									
																	$ISHEADER5		= 0;
																	$sqlC6			= "tbl_boqlist WHERE JOBPARENT = '$JOBCODEID5' AND PRJCODE = '$PRJCODE'";
																	$ressqlC6 		= $this->db->count_all($sqlC6);	
																	if($ressqlC6 > 0)
																		$ISHEADER5	= 1;
																	
																	if($ISHEADER5 == 1)
																	{
																		if ($j==1) {
																			echo "<tr class=zebra1 style=font-weight:bold>";
																			$j++;
																		} else {
																			echo "<tr class=zebra2 style=font-weight:bold>";
																			$j--;
																		}
																		
																		// PENJUMLAHAN ADDENDUM
																			$TOT_BUDGAM	= 0;
																			$TOT_ADDAM	= 0;
																			$sqlTBUDG	= "SELECT SUM(ITM_BUDG) AS TOT_BUDGAM, 
																								SUM(ADD_JOBCOST) AS TOT_ADDAM
																							FROM tbl_joblist_detail
																							WHERE JOBPARENT LIKE '$JOBCODEID5%' 
																								AND PRJCODE = '$PRJCODE' AND IS_LEVEL > $JOBLEV5 
																								AND ISLAST = 1";
																			$resTBUDG 	= $this->db->query($sqlTBUDG)->result();
																			foreach($resTBUDG as $rowTBUDG) :
																				$TOT_BUDGAM	= $rowTBUDG->TOT_BUDGAM;
																				$TOT_ADDAM 	= $rowTBUDG->TOT_ADDAM;
																			endforeach;
																			$JOBCOST5		= round($TOT_BUDGAM,2);
																			$ADD_JOBCOST5	= round($TOT_ADDAM,2);
																			
																		$TotADD			= $TotADD;
																		$TotALL			= $TotBOQ;
																	}
																	else
																	{
																		if ($j==1) {
																			echo "<tr class=zebra1>";
																			$j++;
																		} else {
																			echo "<tr class=zebra2>";
																			$j--;
																		}
												
																		$TotBUD	= $TotBUD + $JOBCOST5;
																		
																		$TotADD			= $TotADD + $ADD_JOBCOST5;
																		$TotALL			= $TotBOQ + $TotADD;
																		$TotUSE			= $TotUSE + $ITM_USED_AM5;
																	}
																	
																	$TOT_JOBCOST5	= $JOBCOST5 + $ADD_JOBCOST5 - $ITM_USED_AM5;
																	if($TOT_JOBCOST5 == 0)
																		$TOT_JOBCOST5 = 1;
														
																	$TOT_JOBCOST5A	= $JOBCOST5 + $ADD_JOBCOST5;
																	if($TOT_JOBCOST5A == 0)
																		$TOT_JOBCOST5A = 1;
																	
																	$TOTPRJC_05	= 0;
																	if($ISHEADER5 != 1)
																	{
																		// COUNT PROJECTION COMPLETED
																		// FORMULA	= USED_AMOUNT + (REM_VOLM * LAST_PRICE)
																			$ITM_LASTP_05	= $rowJOB5->ITM_LASTP;
																			$ITM_USED_05	= $rowJOB5->ITM_USED;
																			$TOTUSED_05		= $ITM_USED_AM5;
																			$TOTREMV_05		= $JOBVOLM5A + $ADD_VOLM5 - $ITM_USED_05;
																			if($TOTREMV_05 < 0)
																				$TOTREMV_05 = 1;
														
																			if($JOBUNIT5 == 'LS')
																			{
																				$TOTREMA_05	= ($JOBCOST5 + $ADD_JOBCOST5 - $TOTUSED_05);
																			}
																			else
																			{
																				//$TOTREMA_05	= ($TOTREMV_05 * $ITM_LASTP_05) + $ADD_JOBCOST5;
																				$TOTREMA_05	= ($TOTREMV_05 * $ITM_LASTP_05);
																			}
																				
																			$TOTPRJC_05		= $TOTUSED_05 + $TOTREMA_05;// Total Projection Complete
																			$TotPC			= $TotPC + $TOTPRJC_05;
																	}
																	
																	$STATCOLPC_05		= 'success';					// Status Color Projection Complete
																	$PRPERCENT_05		= $TOTPRJC_05 / $TOT_JOBCOST5A;
																	if($PRPERCENT_05 > 1.5)
																	{
																		$STATCOLPC_05		= 'danger';
																	}
																	elseif($PRPERCENT_05 > 1)
																	{
																		$STATCOLPC_05		= 'warning';
																	}
																	elseif($PRPERCENT_05 == 1)
																	{
																		$STATCOLPC_05		= 'info';
																	}
																	
																	$percBDG5		= $ITM_USED_AM5 / $TOT_JOBCOST5 * 100;		// Used Percentation
																	if($percBDG5 > 85)
																		$STATCOL	= 'danger';
																	elseif($percBDG5 > 60)
																		$STATCOL	= 'warning';
																	elseif($percBDG5 > 0)
																		$STATCOL	= 'success';
																		
																	$secupdJob5	= site_url('c_project/c_joblistdet/upd1d0ebb/?id='.$this->url_encryption_helper->encode_url($JOBCODEID5));
																	$LinkJD5		= "$PRJCODE~$JOBCODEID2";
																	$secShowJD5		= site_url('c_project/c_joblistdet/sH0wJD/?id='.$this->url_encryption_helper->encode_url($LinkJD5));
																	?>
				                                                              <td nowrap>
																			<?php 
																				print $space_level5;
																				print $JobView5;
																			?></td>
				                                                            <td nowrap style="text-align:center"><?php echo $JOBUNIT5; ?></td>
				                                                            <td nowrap style="text-align:right"><?php print number_format($JOBVOLM5, $decFormat); ?></td>
				                                                            <td nowrap style="text-align:right"><?php print number_format($JOBPRICE5, $decFormat); ?></td>
				                                                            <td nowrap style="text-align:right"><?php print number_format($JOBCOST5, $decFormat); ?></td>
				                                                            <td nowrap style="text-align:right"><?php print number_format($ADD_JOBCOST5, $decFormat); ?></td>
				                                                            <td nowrap style="text-align:right; display:none"><?php print number_format($ADDM_JOBCOST5, $decFormat); ?></td>
				                                                            <td nowrap style="text-align:right">
				                                                                <?php if($ISHEADER5 != 1) { ?>
				                                                                    <span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
				                                                                        <a onclick="showDetC('<?php echo $secShowJD5; ?>')" style="cursor: pointer; color:#FFF">
				                                                                            <?php echo number_format($ITM_USED_AM5, $decFormat); ?>
				                                                                        </a>
				                                                                    </span>
				                                                                <?php } else { ?>
				                                                                    <span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
				                                                                        <?php print number_format($ITM_USED_AM5, $decFormat); ?>
				                                                                    </span>
				                                                                <?php } ?>
				                                                            </td>
				                                                            <td nowrap style="text-align:right">
				                                                                <?php 
																					//print number_format($TOT_JOBCOST5, $decFormat); 
																					print number_format($REMAIN5, $decFormat); 
																				?>
				                                                            </td>
				                                                            <td nowrap style="text-align:right">
																			<?php if($ISHEADER5 != 1) { ?>
				                                                                <span class="label label-<?php echo $PRPERCENT_05; ?>" style="font-size:12px">
				                                                                    <?php print number_format($TOTPRJC_05, $decFormat); ?>
				                                                                </span>
				                                                            <?php } ?>
				                                                            </td>
				                                                            <input type="hidden" name="urlupdateJob<?php echo $myNewNo; ?>" id="urlupdateJob<?php echo $myNewNo; ?>" value="<?php echo $secupdJob5; ?>">
				                                                            <td nowrap style="text-align:right">
				                                                            <a href="javascript:void(null);" class="btn btn-info btn-xs" title="Update" onClick="updateJob('<?php echo $myNewNo; ?>')">
				                                                                <i class="glyphicon glyphicon-pencil"></i>
				                                                            </a>
				                                                            <a href="" class="btn btn-danger btn-xs" title="In Active Project" onclick="return confirm('<?php echo $sureDelete; ?>')" disabled="disabled" style="display:none">
				                                                                <i class="glyphicon glyphicon-trash"></i>
				                                                            </a>
				                                                        </td>
																    </tr>
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
							$TotALL	= $TotBUD + $TotADD - $TotUSE;
						?>
				            <tr>
				                <th colspan="4">T O T A L</th>
				                <th width="5%" style="text-align:right; vertical-align:middle" nowrap><?php echo number_format($TotBUD, $decFormat); ?></th>
				                <th width="7%" style="text-align:right; vertical-align:middle" nowrap><?php echo number_format($TotADD, $decFormat); ?></th>
				                <th width="6%" style="text-align:right; vertical-align:middle" nowrap><?php echo number_format($TotUSE, $decFormat); ?></th>
				                <th width="6%" style="text-align:right; vertical-align:middle" nowrap><?php echo number_format($TotREM, $decFormat); ?></th>
				                <th width="0%" nowrap style="text-align:right; vertical-align:middle"><?php echo number_format($TotPC, $decFormat); ?></th>
				                <th width="3%" nowrap style="text-align:right; vertical-align:middle">&nbsp;</th>
				            </tr>
				        </tbody>
				        <tfoot>
				          <tr>
				            <td colspan="11" style="text-align:left">
								<?php
									if($ISCREATE == 1)
									{
										echo anchor("$secAddURL",'<button class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i></button>&nbsp;&nbsp;');
									} 
									echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>');

				                ?>          	</td>
				          </tr>
				        </tfoot>
				   	</table>
				    </div>
				    <!-- /.box-body -->
				</div>
			</div>
		</section>
	</body>
</html>

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
	
	function updateJob(row)
	{
		var url	= document.getElementById('urlupdateJob'+row).value;
		w = 900;
		h = 550;
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		window.open(url, 'formpopup', 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
		form.target = 'formpopup';
	}
	
	function showDetC(LinkD)
	{
		w = 700;
		h = 550;
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		window.open(LinkD,'popUpWindow','height='+h+',width='+w+',left='+left+',top='+top+',resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');
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