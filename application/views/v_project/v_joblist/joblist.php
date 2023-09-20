<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 24 Maret 2017
 * File Name	= joblist.php
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
	
$PRJCODEX	= $PRJCODE;
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $appName; ?></title>
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
			if($TranslCode == 'Deviation')$Deviation = $LangTransl;
		endforeach;
		if($LangID == 'IND')
		{
			$sureDelete	= "Anda yakin akan menghapus data ini?";
		}
		else
		{
			$sureDelete	= "Are your sure want to delete?";
		}

		$comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
			<h1>
			    <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/wbs.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $h2_title; ?> 
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
					                <th style="text-align:center; vertical-align:middle"><?php echo $Description; ?></th>
					                <th width="7%" style="text-align:center; vertical-align:middle; display:none" nowrap><?php echo $Group; ?></th>
					                <th width="8%" style="text-align:center; vertical-align:middle; display:none" nowrap><?php echo $Class; ?></th>
					                <th width="7%" style="text-align:center; vertical-align:middle; display:none" nowrap><?php echo $Type; ?></th>
					                <th width="5%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Volume; ?></th>
					                <th width="5%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Unit; ?></th>
					                <th width="3%" style="text-align:center; vertical-align:middle" nowrap><?php echo "$Amount<br>(BOQ)"; ?></th>
					                <th width="1%" style="text-align:center; vertical-align:middle" nowrap><?php echo "$Amount<br>(RAP)"; ?></th>
					                <th width="1%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Deviation; ?></th>
					                <th width="3%" style="text-align:center; vertical-align:middle" nowrap>&nbsp;</th>
					          </tr>
					        </thead>
					        <tbody>
							<?php
								$i 		= 0;
								$j 		= 0;
								$TotBOQ	= 0;
								$BOQ_TotBOQ	= 0;
								$DEV_TotBOQ	= 0;
								if($countjobl > 0)
								{
									foreach($vwjoblist as $row) :
										$myNewNo 	= ++$i;
										$JOBCODEID 	= $row->JOBCODEID;
										$JOBCODEIDV	= $row->JOBCODEIDV;
										$JOBCOD1 	= $row->JOBCOD1;
										$JOBCOD2 	= $row->JOBCOD2;
										$JOBDESC 	= $row->JOBDESC;
										$JOBCLASS 	= $row->JOBCLASS;
										$JOBGRP 	= $row->JOBGRP;
										$JOBTYPE 	= $row->JOBTYPE;
										$JOBUNIT 	= $row->JOBUNIT;
										$JOBLEV 	= $row->JOBLEV;
										$JOBVOLM 	= $row->JOBVOLM;
										$JOBCOST 	= $row->JOBCOST;
										$BOQ_JOBCOST= $row->BOQ_JOBCOST;
										$TotBOQ		= $TotBOQ + $JOBCOST;			// ITEM BUDGET
										//echo number_format($BOQ_TotBOQ,0); echo "+"; echo number_format($BOQ_JOBCOST,0); echo " = ";
										$BOQ_TotBOQ	= $BOQ_TotBOQ + $BOQ_JOBCOST;	// BoQ BUDGET
										//echo number_format($BOQ_TotBOQ, 0); echo "<br>";
										$ISHEADER 	= $row->ISHEADER;
										
										if($JOBGRP == 'M')
										{
											$JOBGRPD = "Material";
										}
										elseif($JOBGRP == 'U')
										{
											$JOBGRPD = "Upah";
										}
										elseif($JOBGRP == 'S')
										{
											$JOBGRPD = "Service";
										}
										elseif($JOBGRP == 'T')
										{
											$JOBGRPD = "Tools";
										}
										elseif($JOBGRP == 'I')
										{
											$JOBGRPD = "Indirect";
										}
										elseif($JOBGRP == 'R')
										{
											$JOBGRPD = "Reimburstment";
										}
										else
										{
											$JOBGRPD = "-";
										}
										
										if($JOBCLASS == 'CLS01')
										{
											$JOBCLASSD = "An. STR";
										}
										elseif($JOBCLASS == 'CLS02')
										{
											$JOBCLASSD = "Dinding";
										}
										elseif($JOBCLASS == 'CLS03')
										{
											$JOBCLASSD = "Lantai";
										}
										elseif($JOBCLASS == 'CLS04')
										{
											$JOBCLASSD = "Sanitair";
										}
										elseif($JOBCLASS == 'CLS05')
										{
											$JOBCLASSD = "Sundries";
										}
										elseif($JOBCLASS == 'CLS06')
										{
											$JOBCLASSD = "Others";
										}
										else
										{
											$JOBCLASSD = "-";
										}
										
										if($JOBTYPE == 'S')
										{
											$JOBTYPED	= "Selfdone";
										}
										elseif($JOBTYPE == 'C')
										{
											$JOBTYPED	= "Subcontracted";
										}
										elseif($JOBTYPE == 'O')
										{
											$JOBTYPED	= "Others";
										}
										else
										{
											$JOBTYPED	= "-";
										}
										
										$JOBDESC1		= cut_text ("$JOBDESC", 70);
										$secUpd			= site_url('c_project/c_joblist/update/?id='.$this->url_encryption_helper->encode_url($JOBCODEID));
										$space_level1 	= "&nbsp;&nbsp;&nbsp;&nbsp;";
										$JobView		= "$JOBCODEIDV - $JOBDESC1";
										
										//$ISHEADER		= 0;
										$sqlC2			= "tbl_joblist WHERE JOBPARENT = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ISHEADER = '1'";
										$ressqlC2 		= $this->db->count_all($sqlC2);
										if($ressqlC2 > 0)
											$ISHEADER	= 1;
											
										if($ISHEADER == 1)
										{
											$DEV_AMOUNT	= $BOQ_JOBCOST - $JOBCOST;
											if($DEV_AMOUNT > 0)
											{
												$STATCOL	= 'primary';
											}
											elseif($DEV_AMOUNT == 0)
											{
												$STATCOL	= 'warning';
											}
											else
											{
												$STATCOL	= 'danger';
											}
											if ($j==1) {
												echo "<tr class=zebra1 style=font-weight:bold>";
												$j++;
											} else {
												echo "<tr class=zebra2 style=font-weight:bold>";
												$j--;
											}
										}
										else
										{
											$DEV_AMOUNT	= 0;
											$STATCOL	= 'warning';
											if ($j==1) {
												echo "<tr class=zebra1>";
												$j++;
											} else {
												echo "<tr class=zebra2>";
												$j--;
											}
										}
										$DEV_TotBOQ	= $DEV_TotBOQ + $DEV_AMOUNT;
										?>
											<td width="51%" nowrap><?php print $JobView; ?> </td>
											<td style="display:none" nowrap><?php print $JOBCLASSD; ?> </td>
											<td style="display:none" nowrap><?php print $JOBCLASSD; ?></td>
											<td style="display:none" nowrap><?php print $JOBTYPED; ?></td>
											<td nowrap style="text-align:right"><?php print number_format($JOBVOLM, $decFormat); ?> </td>
											<td nowrap style="text-align:center"><?php echo $JOBUNIT; ?></td>
											<td nowrap style="text-align:right"><?php print number_format($BOQ_JOBCOST, $decFormat); ?></td>
											<td nowrap style="text-align:right"><?php print number_format($JOBCOST, $decFormat); ?></td>
											<td nowrap style="text-align:right;">
					                            <span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
					                                <?php print number_format($DEV_AMOUNT, $decFormat); ?>
					                            </span>
					                        </td>
											<td nowrap style="text-align:right">
					                            <a href="<?php echo $secUpd; ?>" class="btn btn-info btn-xs" title="Update Project" disabled="disabled">
					                                <i class="glyphicon glyphicon-pencil"></i>
					                            </a>
					                            <a href="" class="btn btn-danger btn-xs" title="In Active Project" onclick="return confirm('<?php echo $sureDelete; ?>')" disabled="disabled">
					                                <i class="glyphicon glyphicon-trash"></i>
					                            </a>
					                        </td>
									    </tr>
										<?php
										if($ressqlC2 > 0)
										{
											$sqlJOB2	= "SELECT JOBCODEID, JOBCODEIDV, JOBPARENT, JOBDESC, JOBGRP, JOBCLASS, JOBUNIT, JOBVOLM, JOBCOST, BOQ_JOBCOST, ISHEADER
															FROM tbl_joblist
															WHERE JOBPARENT = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ISHEADER = '1'";
											$resJOB2 	= $this->db->query($sqlJOB2)->result();
											foreach($resJOB2 as $rowJOB2) :
												$JOBCODEID2 	= $rowJOB2->JOBCODEID;
												$JOBCODEID2V	= $rowJOB2->JOBCODEIDV;
												$JOBPARENT2		= $rowJOB2->JOBPARENT;
												$JOBDESC2 		= $rowJOB2->JOBDESC;
												$JOBDESC2		= cut_text ("$JOBDESC2", 70);
												$JOBGRP2 		= $rowJOB2->JOBGRP;
												$JOBCLASS2 		= $rowJOB2->JOBCLASS;
												$JOBUNIT2 		= $rowJOB2->JOBUNIT;
												$JOBVOLM2 		= $rowJOB2->JOBVOLM;
												$JOBCOST2 		= $rowJOB2->JOBCOST;
												$BOQ_JOBCOST2 	= $rowJOB2->BOQ_JOBCOST;
												$ISHEADER2 		= $rowJOB2->ISHEADER;
												
												$JobView2		= "$JOBCODEID2V - $JOBDESC2";
												$space_level2	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
												$secUpd2		= site_url('c_project/c_joblist/update/?id='.$this->url_encryption_helper->encode_url($JOBCODEID2));
										
												//$ISHEADER2		= 0;
												$sqlC3			= "tbl_joblist WHERE JOBPARENT = '$JOBCODEID2' AND PRJCODE = '$PRJCODE' AND ISHEADER = '1'";
												$ressqlC3 		= $this->db->count_all($sqlC3);
												if($ressqlC3 > 0)
													$ISHEADER2	= 1;
												
												if($ISHEADER2 == 1)
												{
													$DEV_AMOUNT2	= $BOQ_JOBCOST2 - $JOBCOST2;
													if($DEV_AMOUNT2 > 0)
													{
														$STATCOL2	= 'success';
													}
													elseif($DEV_AMOUNT2 == 0)
													{
														$STATCOL2	= '';
													}
													else
													{
														$STATCOL2	= 'danger';
													}
													if ($j==1) {
														echo "<tr class=zebra1 style=font-weight:bold>";
														$j++;
													} else {
														echo "<tr class=zebra2 style=font-weight:bold>";
														$j--;
													}
												}
												else
												{
													$DEV_AMOUNT2	= 0;
													$STATCOL2		= '';
													if ($j==1) {
														echo "<tr class=zebra1>";
														$j++;
													} else {
														echo "<tr class=zebra2>";
														$j--;
													}
												}
												?>
													 	<td nowrap>
														<?php 
															print $space_level2;
															print $JobView2;
														?>								</td>
					                                    <td style="display:none" nowrap><?php print $JOBCLASSD; ?></td>
					                                    <td style="display:none" nowrap><?php print $JOBCLASSD; ?></td>
					                                    <td style="display:none" nowrap><?php print $JOBTYPED; ?></td>
					                                    <td nowrap style="text-align:right"><?php print number_format($JOBVOLM2, $decFormat); ?></td>
					                                    <td nowrap style="text-align:center"><?php echo $JOBUNIT2; ?></td>
					                                    <td nowrap style="text-align:right"><?php print number_format($BOQ_JOBCOST2, $decFormat); ?></td>
					                                    <td nowrap style="text-align:right"><?php print number_format($JOBCOST2, $decFormat); ?></td>
					                                    <td nowrap style="text-align:right;">
					                                        <span class="label label-<?php echo $STATCOL2; ?>" style="font-size:12px">
					                                            <?php print number_format($DEV_AMOUNT2, $decFormat); ?>
					                                        </span>
					                                    </td>
					                                    <td nowrap style="text-align:right">
					                                    <a href="<?php echo $secUpd2; ?>" class="btn btn-info btn-xs" title="Update Project" disabled="disabled">
					                                        <i class="glyphicon glyphicon-pencil"></i>
					                                    </a>
					                                    <a href="" class="btn btn-danger btn-xs" title="In Active Project" onclick="return confirm('<?php echo $sureDelete; ?>')" disabled="disabled">
					                                        <i class="glyphicon glyphicon-trash"></i>
					                                    </a>
					                                </td>
											    </tr>
												<?php
												if($ressqlC3 > 0)
												{
													$sqlJOB3	= "SELECT JOBCODEID, JOBCODEIDV, JOBPARENT, JOBDESC, JOBGRP, JOBCLASS, JOBUNIT, JOBVOLM, JOBCOST, BOQ_JOBCOST, ISHEADER
																	FROM tbl_joblist
																	WHERE JOBPARENT = '$JOBCODEID2' AND PRJCODE = '$PRJCODE' AND ISHEADER = '1'";
													$resJOB3 	= $this->db->query($sqlJOB3)->result();
													foreach($resJOB3 as $rowJOB3) :
														$JOBCODEID3 	= $rowJOB3->JOBCODEID;
														$JOBCODEID3V	= $rowJOB3->JOBCODEIDV;
														$JOBPARENT3		= $rowJOB3->JOBPARENT;
														$JOBDESC3 		= $rowJOB3->JOBDESC;
														$JOBDESC3		= cut_text ("$JOBDESC3", 70);
														$JOBGRP3 		= $rowJOB3->JOBGRP;
														$JOBCLASS3 		= $rowJOB3->JOBCLASS;
														$JOBUNIT3 		= $rowJOB3->JOBUNIT;
														$JOBVOLM3 		= $rowJOB3->JOBVOLM;
														$JOBCOST3 		= $rowJOB3->JOBCOST;
														$BOQ_JOBCOST3 	= $rowJOB3->BOQ_JOBCOST;
														$ISHEADER3 		= $rowJOB3->ISHEADER;
														
														$JobView3		= "$JOBCODEID3V - $JOBDESC3";
														$space_level3	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
														$secUpd3		= site_url('c_project/c_joblist/update/?id='.$this->url_encryption_helper->encode_url($JOBCODEID3));
										
														//$ISHEADER3		= 0;
														$sqlC4			= "tbl_joblist WHERE JOBPARENT = '$JOBCODEID3' AND PRJCODE = '$PRJCODE' AND ISHEADER = '1'";
														$ressqlC4 		= $this->db->count_all($sqlC4);
														if($ressqlC4 > 0)
															$ISHEADER3	= 1;
														
														if($ISHEADER3 == 1)
														{
															$DEV_AMOUNT3	= $BOQ_JOBCOST3 - $JOBCOST3;
															if($DEV_AMOUNT3 > 0)
															{
																$STATCOL3	= 'info';
															}
															elseif($DEV_AMOUNT3 == 0)
															{
																$STATCOL3	= '';
															}
															else
															{
																$STATCOL3	= 'danger';
															}
															if ($j==1) {
																echo "<tr class=zebra1 style=font-weight:bold>";
																$j++;
															} else {
																echo "<tr class=zebra2 style=font-weight:bold>";
																$j--;
															}
														}
														else
														{
															$DEV_AMOUNT3	= 0;
															$STATCOL3		= '';
															if ($j==1) {
																echo "<tr class=zebra1>";
																$j++;
															} else {
																echo "<tr class=zebra2>";
																$j--;
															}
														}
															?>
															  	<td nowrap>
																<?php 
																	print $space_level3;
																	print $JobView3;
																?>										</td>
					                                            <td style="display:none" nowrap><?php print $JOBCLASSD; ?></td>
					                                            <td style="display:none" nowrap><?php print $JOBCLASSD; ?></td>
					                                            <td style="display:none" nowrap><?php print $JOBTYPED; ?></td>
					                                            <td nowrap style="text-align:right"><?php print number_format($JOBVOLM3, $decFormat); ?></td>
					                                            <td nowrap style="text-align:center"><?php echo $JOBUNIT3; ?></td>
					                                            <td nowrap style="text-align:right"><?php print number_format($BOQ_JOBCOST3, $decFormat); ?></td>
					                                            <td nowrap style="text-align:right"><?php print number_format($JOBCOST3, $decFormat); ?></td>
					                                            <td nowrap style="text-align:right;">
					                                            <span class="label label-<?php echo $STATCOL3; ?>" style="font-size:12px">
					                                                <?php print number_format($DEV_AMOUNT3, $decFormat); ?>
					                                            </span>
					                                            <td nowrap style="text-align:right">
					                                            <a href="<?php echo $secUpd3; ?>" class="btn btn-info btn-xs" title="Update Project" disabled="disabled">
					                                                <i class="glyphicon glyphicon-pencil"></i>
					                                            </a>
					                                            <a href="" class="btn btn-danger btn-xs" title="In Active Project" onclick="return confirm('<?php echo $sureDelete; ?>')" disabled="disabled">
					                                                <i class="glyphicon glyphicon-trash"></i>
					                                            </a>
					                                        </td>
													    </tr>
														<?php
														if($ressqlC4 > 0)
														{
															$sqlJOB4	= "SELECT JOBCODEID, JOBCODEIDV, JOBPARENT, JOBDESC, JOBGRP, JOBCLASS, JOBUNIT, JOBVOLM, JOBCOST, BOQ_JOBCOST,
																			ISHEADER
																			FROM tbl_boqlist
																			WHERE JOBPARENT = '$JOBCODEID3' AND PRJCODE = '$PRJCODE' AND ISHEADER = '1'";
															$resJOB4 	= $this->db->query($sqlJOB4)->result();
															foreach($resJOB4 as $rowJOB4) :
																$JOBCODEID4 	= $rowJOB4->JOBCODEID;
																$JOBCODEID4V	= $rowJOB4->JOBCODEIDV;
																$JOBPARENT4		= $rowJOB4->JOBPARENT;
																$JOBDESC4 		= $rowJOB4->JOBDESC;
																$JOBDESC4		= cut_text ("$JOBDESC4", 70);
																$JOBGRP4 		= $rowJOB4->JOBGRP;
																$JOBCLASS4 		= $rowJOB4->JOBCLASS;
																$JOBUNIT4 		= $rowJOB4->JOBUNIT;
																$JOBVOLM4 		= $rowJOB4->JOBVOLM;
																$JOBCOST4 		= $rowJOB4->JOBCOST;
																$BOQ_JOBCOST4 	= $rowJOB4->BOQ_JOBCOST;
																$ISHEADER4 		= $rowJOB4->ISHEADER;
																
																$JobView4		= "$JOBCODEID4V - $JOBDESC4";
																$space_level4	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
																$secUpd4		= site_url('c_project/c_joblist/update/?id='.$this->url_encryption_helper->encode_url($JOBCODEID4));
										
																//$ISHEADER4		= 0;
																$sqlC5			= "tbl_joblist WHERE JOBPARENT = '$JOBCODEID4' AND PRJCODE = '$PRJCODE' AND ISHEADER = '1'";
																$ressqlC5 		= $this->db->count_all($sqlC5);	
																if($ressqlC5 > 0)
																	$ISHEADER4	= 1;
																
																if($ISHEADER4 == 1)
																{
																	$DEV_AMOUNT4	= $BOQ_JOBCOST4 - $JOBCOST4;
																	if($DEV_AMOUNT4 > 0)
																	{
																		$STATCOL4	= 'warning';
																	}
																	elseif($DEV_AMOUNT4 == 0)
																	{
																		$STATCOL4	= '';
																	}
																	else
																	{
																		$STATCOL4	= 'danger';
																	}
																	if ($j==1) {
																		echo "<tr class=zebra1 style=font-weight:bold>";
																		$j++;
																	} else {
																		echo "<tr class=zebra2 style=font-weight:bold>";
																		$j--;
																	}
																}
																else
																{
																	$DEV_AMOUNT4	= 0;
																	$STATCOL4		= '';
																	if ($j==1) {
																		echo "<tr class=zebra1>";
																		$j++;
																	} else {
																		echo "<tr class=zebra2>";
																		$j--;
																	}
																}
																?>
																	  	<td nowrap>
																		<?php 
																			print $space_level4;
																			print $JobView4;
																		?>												</td>
					                                                    <td style="display:none" nowrap><?php print $JOBCLASSD; ?></td>
					                                                    <td style="display:none" nowrap><?php print $JOBCLASSD; ?></td>
					                                                    <td style="display:none" nowrap><?php print $JOBTYPED; ?></td>
					                                                    <td nowrap style="text-align:right"><?php print number_format($JOBVOLM4, $decFormat); ?></td>
					                                                    <td nowrap style="text-align:center"><?php echo $JOBUNIT4; ?></td>
					                                                    <td nowrap style="text-align:right"><?php print number_format($BOQ_JOBCOST4, $decFormat); ?></td>
					                                                    <td nowrap style="text-align:right"><?php print number_format($JOBCOST4, $decFormat); ?></td>
					                                                    <td nowrap style="text-align:right;">
					                                                    <span class="label label-<?php echo $STATCOL4; ?>" style="font-size:12px">
					                                                        <?php print number_format($DEV_AMOUNT4, $decFormat); ?>
					                                                    </span>
					                                                    </td>
					                                                    <td nowrap style="text-align:right">
					                                                    <a href="<?php echo $secUpd4; ?>" class="btn btn-info btn-xs" title="Update Project" disabled="disabled">
					                                                        <i class="glyphicon glyphicon-pencil"></i>
					                                                    </a>
					                                                    <a href="" class="btn btn-danger btn-xs" title="In Active Project" onclick="return confirm('<?php echo $sureDelete; ?>')" disabled="disabled">
					                                                        <i class="glyphicon glyphicon-trash"></i>
					                                                    </a>
					                                                </td>
															    </tr>
																<?php
																$sqlC5		= "tbl_joblist WHERE JOBPARENT = '$JOBCODEID4' AND PRJCODE = '$PRJCODE' AND ISHEADER = '1'";
																$ressqlC5 	= $this->db->count_all($sqlC5);	
																if($ressqlC5 > 0)
																{
																	$sqlJOB5	= "SELECT JOBCODEID, JOBCODEIDV, JOBPARENT, JOBDESC, JOBGRP, JOBCLASS, JOBUNIT, JOBVOLM, JOBCOST, 
																					BOQ_JOBCOST, ISHEADER
																					FROM tbl_joblist
																					WHERE JOBPARENT = '$JOBCODEID4' AND PRJCODE = '$PRJCODE' AND ISHEADER = '1'";
																	$resJOB5 	= $this->db->query($sqlJOB5)->result();
																	foreach($resJOB5 as $rowJOB5) :
																		$JOBCODEID5 	= $rowJOB5->JOBCODEID;
																		$JOBCODEID5V	= $rowJOB5->JOBCODEIDV;
																		$JOBPARENT5		= $rowJOB5->JOBPARENT;
																		$JOBDESC5 		= $rowJOB5->JOBDESC;
																		$JOBDESC5		= cut_text ("$JOBDESC5", 70);
																		$JOBGRP5 		= $rowJOB5->JOBGRP;
																		$JOBCLASS5 		= $rowJOB5->JOBCLASS;
																		$JOBUNIT5 		= $rowJOB5->JOBUNIT;
																		$JOBVOLM5 		= $rowJOB5->JOBVOLM;
																		$JOBCOST5 		= $rowJOB5->JOBCOST;
																		$BOQ_JOBCOST5 	= $rowJOB5->BOQ_JOBCOST;
																		$ISHEADER5 		= $rowJOB5->ISHEADER;
																		
																		$JobView5		= "$JOBCODEID5V - $JOBDESC5";
																		$space_level5	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
																		$secUpd5		= site_url('c_project/c_joblist/update/?id='.$this->url_encryption_helper->encode_url($JOBCODEID5));
										
																		//$ISHEADER5		= 0;
																		$sqlC6			= "tbl_joblist WHERE JOBPARENT = '$JOBCODEID5' AND PRJCODE = '$PRJCODE' AND ISHEADER = '1'";
																		$ressqlC6 		= $this->db->count_all($sqlC6);	
																		if($ressqlC6 > 0)
																			$ISHEADER5	= 1;
																		
																		if($ISHEADER5 == 1)
																		{
																			$DEV_AMOUNT5	= $BOQ_JOBCOST5 - $JOBCOST5;
																			if($DEV_AMOUNT5 > 0)
																			{
																				$STATCOL5	= 'success';
																			}
																			elseif($DEV_AMOUNT5 == 0)
																			{
																				$STATCOL5	= '';
																			}
																			else
																			{
																				$STATCOL5	= 'danger';
																			}
																			if ($j==1) {
																				echo "<tr class=zebra1 style=font-weight:bold>";
																				$j++;
																			} else {
																				echo "<tr class=zebra2 style=font-weight:bold>";
																				$j--;
																			}
																		}
																		else
																		{
																			$DEV_AMOUNT5	= 0;
																			$STATCOL5		= '';
																			if ($j==1) {
																				echo "<tr class=zebra1>";
																				$j++;
																			} else {
																				echo "<tr class=zebra2>";
																				$j--;
																			}
																		}
																		?>
																			  	<td nowrap>
																				<?php 
																					print $space_level5;
																					print $JobView5;
																				?>														</td>
					                                                            <td style="display:none" nowrap><?php print $JOBCLASSD; ?></td>
					                                                            <td style="display:none" nowrap><?php print $JOBCLASSD; ?></td>
					                                                            <td style="display:none" nowrap><?php print $JOBTYPED; ?></td>
					                                                            <td nowrap style="text-align:right"><?php print number_format($JOBVOLM5, $decFormat); ?></td>
					                                                            <td nowrap style="text-align:center"><?php echo $JOBUNIT5; ?></td>
					                                                            <td nowrap style="text-align:right"><?php print number_format($BOQ_JOBCOST5, $decFormat); ?></td>
					                                                            <td nowrap style="text-align:right"><?php print number_format($JOBCOST5, $decFormat); ?></td>
					                                                            <td nowrap style="text-align:right;">
					                                                            <span class="label label-<?php echo $STATCOL5; ?>" style="font-size:12px">
					                                                                <?php print number_format($DEV_AMOUNT5, $decFormat); ?>
					                                                            </span>
					                                                            </td>
					                                                            <td nowrap style="text-align:right">
					                                                            <a href="<?php echo $secUpd5; ?>" class="btn btn-info btn-xs" title="Update Project" disabled="disabled">
					                                                                <i class="glyphicon glyphicon-pencil"></i>
					                                                            </a>
					                                                            <a href="" class="btn btn-danger btn-xs" title="In Active Project" onclick="return confirm('<?php echo $sureDelete; ?>')" disabled="disabled">
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
								// UPDATE RAB IN PROJECT
								$sqlUPDPRJ	= "UPDATE tbl_project SET PRJBOQ = $BOQ_TotBOQ, PRJCOST = $BOQ_TotBOQ, PRJRAP = $TotBOQ
												WHERE PRJCODE = '$PRJCODEX'";
								$this->db->query($sqlUPDPRJ);
							?>
					            <tr>
					                <th colspan="3">T O T A L</th>
					              <th width="7%" nowrap style="text-align:right; vertical-align:middle"><?php echo number_format($BOQ_TotBOQ, $decFormat); ?></th>
					                <th width="5%" nowrap style="text-align:right; vertical-align:middle"><?php echo number_format($TotBOQ, $decFormat); ?></th>
					                <th width="5%" nowrap style="text-align:right; vertical-align:middle"><?php echo number_format($DEV_TotBOQ, $decFormat); ?></th>
					                <th width="3%" style="text-align:right; vertical-align:middle" nowrap>&nbsp;</th>
					            </tr>
					        </tbody>
					        <tfoot>
					        </tfoot>
				   		</table>
				    </div>
				    <br>
					<?php
						/*if($ISCREATE == 1)
						{
							echo anchor("$secAddURL",'<button class="btn btn-primary"><i class="cus-add-16x16"></i>&nbsp;&nbsp;'.$Add.'</button>&nbsp;');
						}*/
						echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>');

	                ?>
				</div>
			</div>
        	<?php
				$act_lnk = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        		if($DefEmp_ID == 'D15040004221')
                	echo "<font size='1'><i>$act_lnk</i></font>";
            ?>
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
</script>
<script>
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