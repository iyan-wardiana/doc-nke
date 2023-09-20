<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 5 Juli 2019
 * File Name	= joblistdet.php
 * Location		= -
*/
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');

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
?>

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">

<h1>
    <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/wbs.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $h1_title; ?> 
    <small><?php echo "$BUDGNAME - $PRJNAME"; ?> </small>
  </h1><br>
  <?php /*?><ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#">Tables</a></li>
    <li class="active">Data tables</li>
  </ol><?php */?>
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
<!-- Main content -->

  <div class="box">
    <!-- /.box-header -->
<div class="box-body">
	<div class="search-table-outter">
      <table id="" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
        <thead>
            <tr>
                <th rowspan="2" nowrap style="text-align:center; vertical-align:middle"><?php echo $Description; ?></th>
                <th width="5%" rowspan="2" nowrap style="text-align:center; vertical-align:middle"><?php echo $Unit; ?></th>
                <th width="6%" rowspan="2" nowrap style="text-align:center; vertical-align:middle"><?php echo $Volume; ?></th>
                <th width="6%" rowspan="2" nowrap style="text-align:center; vertical-align:middle"><?php echo $Price; ?></th>
                <th width="6%" rowspan="2" nowrap style="text-align:center; vertical-align:middle"><?php echo $Amount; ?></th>
                <?php
					$PrdDesc1	= 1;
					$PrdDesc2	= 2;
					$PrdDesc3	= 3;
					$PrdDesc4	= 4;
					$PRJCODE1	= '';
					$PRJCODE2	= '';
					$PRJCODE3	= '';
					$PRJCODE4	= '';
					$sqlSPrdC	= "tbl_project_budg WHERE PRJPERIOD_P = '$PRJPERIOD'";
					$resSPrdC	= $this->db->count_all($sqlSPrdC);
					for($i=1;$i<=$resSPrdC;$i++)
					{
						$sqlSPrd	= "SELECT PRJNAME, PRJCODE FROM tbl_project_budg WHERE PRJPERIOD_P = '$PRJPERIOD' ";
						$resSPrd	= $this->db->query($sqlSPrd)->result();
						foreach($resSPrd as $rowPrd):
							$PRJNAME	= $rowPrd->PRJNAME;
							$PRJCODEPER	= $rowPrd->PRJCODE;
						endforeach;
						if($i==1)
						{
							$PrdDesc1	= $PRJNAME;
							$PRJCODE1	= $PRJCODEPER;
						}
						elseif($i==2)
						{
							$PrdDesc2	= $PRJNAME;
							$PRJCODE2	= $PRJCODEPER;
						}
						elseif($i==3)
						{
							$PrdDesc3	= $PRJNAME;
							$PRJCODE3	= $PRJCODEPER;
						}
						elseif($i==4)
						{
							$PrdDesc4	= $PRJNAME;
							$PRJCODE4	= $PRJCODEPER;
						}
					}
				?>
                <th width="5%" colspan="3" nowrap style="text-align:center; vertical-align:middle; background-color:#9C6">Periode <?php echo $PrdDesc1; ?></th>
                <th width="7%" colspan="3" nowrap style="text-align:center; vertical-align:middle; background-color:#996">Periode <?php echo $PrdDesc2; ?></th>
                <th width="6%" colspan="3" nowrap style="text-align:center; vertical-align:middle; background-color:#C99">Periode <?php echo $PrdDesc3; ?></th>
                <th width="3%" colspan="3" nowrap style="text-align:center; vertical-align:middle; background-color:#C9C">Periode <?php echo $PrdDesc4; ?></th>
                <th width="3%" rowspan="2" nowrap style="text-align:center; vertical-align:middle">Projection<br>
                Complete</th>
                <th width="3%" rowspan="2" nowrap style="text-align:center; vertical-align:middle">&nbsp;</th>
          </tr>
            <tr>
              <th style="text-align:center; vertical-align:middle; background-color:#9C6" nowrap>Budget</th>
              <th style="text-align:center; vertical-align:middle; background-color:#9C6" nowrap>Add</th>
              <th style="text-align:center; vertical-align:middle; background-color:#9C6" nowrap><?php echo $Used; ?></th>
              <th nowrap style="text-align:center; vertical-align:middle; background-color:#996">Budget</th>
              <th nowrap style="text-align:center; vertical-align:middle; background-color:#996">Add</th>
              <th nowrap style="text-align:center; vertical-align:middle; background-color:#996"><?php echo $Used; ?></th>
              <th nowrap style="text-align:center; vertical-align:middle; background-color:#C99">Budget</th>
              <th nowrap style="text-align:center; vertical-align:middle; background-color:#C99">Add</th>
              <th nowrap style="text-align:center; vertical-align:middle; background-color:#C99"><?php echo $Used; ?></th>
              <th nowrap style="text-align:center; vertical-align:middle; background-color:#C9C">Budget</th>
              <th nowrap style="text-align:center; vertical-align:middle; background-color:#C9C">Add</th>
              <th nowrap style="text-align:center; vertical-align:middle; background-color:#C9C"><?php echo $Used; ?></th>
            </tr>
        </thead>
        <tbody>
		<?php
			$myNewNo=0;
			$i 		= 0;
			$j 		= 0;
			$TotBOQ	= 0;
			$TotBUD	= 0;
			$TotBUD1= 0;
			$TotBUD2= 0;
			$TotBUD3= 0;
			$TotBUD4= 0;
			$TotADD	= 0;
			$TotADD1= 0;
			$TotADD2= 0;
			$TotADD3= 0;
			$TotADD4= 0;
			$TotADD5= 0;
			$TotALL	= 0;
			$TotREM	= 0;
			$TotUSE	= 0;
			$TotUSE1= 0;
			$TotUSE2= 0;
			$TotUSE3= 0;
			$TotUSE4= 0;
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
					$JOBVOLM 	= $row->ITM_VOLM;
					$JOBPRICE 	= $row->ITM_PRICE;
					$JOBCOST 	= $row->ITM_BUDG;
					$ADD_VOLM 	= $row->ADD_VOLM;
					$ADD_PRICE 	= $row->ADD_PRICE;
					$ADD_JOBCOST= $row->ADD_JOBCOST;
					$ADDM_VOLM 	= $row->ADDM_VOLM;
					$ADDM_JOBCOST= $row->ADDM_JOBCOST;
					$ITM_USED_AM= $row->ITM_USED_AM;
						
					/*$TotBOQ		= $TotBOQ + $JOBCOST + $ADD_JOBCOST;
					$TotADD		= $TotADD + $ADD_JOBCOST;
					$TotALL		= $TotBOQ + $TotADD;*/
					
					$JOBDESC1		= cut_text ("$JOBDESC", 40);
					$secUpd			= site_url('c_comprof/c_bUd93tL15t/update/?id='.$this->url_encryption_helper->encode_url($JOBCODEID));
					$space_level1 	= "&nbsp;&nbsp;&nbsp;&nbsp;";
					$JobView		= "$JOBCODEIDV - $JOBDESC1";
					
					$sqlC2			= "tbl_boqlistm WHERE JOBPARENT = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";
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
							$TOT_USEDAM	= 0;
							$sqlTBUDG	= "SELECT SUM(ITM_BUDG) AS TOT_BUDGAM, SUM(ADD_JOBCOST) AS TOT_ADDAM, SUM(ITM_USED_AM) AS TOT_USEDAM
											FROM tbl_joblist_detailm
											WHERE JOBPARENT LIKE '$JOBCODEID%' AND PRJCODE = '$PRJCODE'
												AND IS_LEVEL > $JOBLEV AND ISLAST = 1";
							$resTBUDG 	= $this->db->query($sqlTBUDG)->result();
							foreach($resTBUDG as $rowTBUDG) :
								$TOT_BUDGAM	= $rowTBUDG->TOT_BUDGAM;
								$TOT_ADDAM 	= $rowTBUDG->TOT_ADDAM;
								$TOT_USEDAM = $rowTBUDG->TOT_USEDAM;
							endforeach;
							$JOBCOST		= $TOT_BUDGAM;
							$ADD_JOBCOST	= $TOT_ADDAM;
							
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
						$TotREM		= $TotREM + $REMAIN2;
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
						
					$secupdJob	= site_url('c_comprof/c_bUd93tL15t/upd1d0ebbM5tr/?id='.$this->url_encryption_helper->encode_url($JOBCODEID));
					?>
						<td width="50%" nowrap><?php print "$JobView"; ?> </td>
						<td nowrap style="text-align:center"><?php echo $JOBUNIT; ?></td>
						<td nowrap style="text-align:right"><?php print number_format($JOBVOLM, $decFormat); ?> </td>
						<td nowrap style="text-align:right"><?php print number_format($JOBPRICE, $decFormat); ?> </td>
						<td nowrap style="text-align:right">
                        	<?php //if($ISHEADER != 1) { ?>
                                <span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
                                    <?php print number_format($JOBCOST, $decFormat); ?>
                                </span>
                            <?php //} ?>
                        </td>
                        <?php
							$ITM_BUDGA1	= 0;
							$ADD_COSTA1	= 0;
							$ITM_USEMA1	= 0;
							$sqlSPrdA1	= "SELECT ITM_VOLM, ITM_LASTP, ITM_BUDG, ADD_VOLM, ADD_PRICE, ADD_JOBCOST, ITM_USED, ITM_USED_AM 
											FROM tbl_joblist_detail WHERE JOBCODEID_P = '$JOBCODEID' AND PRJCODE = '$PRJCODE1'";
							$resSPrdA1	= $this->db->query($sqlSPrdA1)->result();
							foreach($resSPrdA1 as $rowPrdA1):
								$ITM_BUDGA1	= $rowPrdA1->ITM_BUDG;
								$ADD_COSTA1	= $rowPrdA1->ADD_JOBCOST;
								$ITM_USEMA1	= $rowPrdA1->ITM_USED_AM;
							endforeach;
							if($ISHEADER != 1)
							{
								$TotBUD1	= $TotBUD1 + $ITM_BUDGA1;
								$TotADD1	= $TotADD1 + $ADD_COSTA1;
								$TotUSE1	= $TotUSE1 + $ITM_USEMA1;
							}
						?>
						<td nowrap style="text-align:right; background-color:#9C6">
                            <span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
                                <?php print number_format($ITM_BUDGA1, $decFormat); ?>
                            </span>
                        </td>
						<td nowrap style="text-align:right; background-color:#9C6">
                            <span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
                                <?php print number_format($ADD_COSTA1, $decFormat); ?>
                            </span>
                        </td>
						<td nowrap style="text-align:right; background-color:#9C6">
                            <span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
                                <?php print number_format($ITM_USEMA1, $decFormat); ?>
                            </span>
                        </td>
                        <?php
							$ITM_BUDGB1	= 0;
							$ADD_COSTB1	= 0;
							$ITM_USEMB1	= 0;
							$sqlSPrdB1	= "SELECT ITM_VOLM, ITM_LASTP, ITM_BUDG, ADD_VOLM, ADD_PRICE, ADD_JOBCOST, ITM_USED, ITM_USED_AM 
											FROM tbl_joblist_detail WHERE JOBCODEID_P = '$JOBCODEID' AND PRJCODE = '$PRJCODE2'";
							$resSPrdB1	= $this->db->query($sqlSPrdB1)->result();
							foreach($resSPrdB1 as $rowPrdB1):
								$ITM_BUDGB1	= $rowPrdB1->ITM_BUDG;
								$ADD_COSTB1	= $rowPrdB1->ADD_JOBCOST;
								$ITM_USEMB1	= $rowPrdB1->ITM_USED_AM;
							endforeach;
							if($ISHEADER != 1)
							{
								$TotBUD2	= $TotBUD2 + $ITM_BUDGB2;
								$TotADD2	= $TotADD2 + $ADD_COSTB2;
								$TotUSE2	= $TotUSE2 + $ITM_USEMB2;
							}
						?>
						<td nowrap style="text-align:right; background-color:#996">
                            <span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
                                <?php print number_format($ITM_BUDGB1, $decFormat); ?>
                            </span>
                        </td>
						<td nowrap style="text-align:right; background-color:#996">
                            <span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
                                <?php print number_format($ADD_COSTB1, $decFormat); ?>
                            </span>
                        </td>
						<td nowrap style="text-align:right; background-color:#996">
                            <span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
                                <?php print number_format($ITM_USEMB1, $decFormat); ?>
                            </span>
                        </td>
                        <?php
							$ITM_BUDGC1	= 0;
							$ADD_COSTC1	= 0;
							$ITM_USEMC1	= 0;
							$sqlSPrdC1	= "SELECT ITM_VOLM, ITM_LASTP, ITM_BUDG, ADD_VOLM, ADD_PRICE, ADD_JOBCOST, ITM_USED, ITM_USED_AM 
											FROM tbl_joblist_detail WHERE JOBCODEID_P = '$JOBCODEID' AND PRJCODE = '$PRJCODE3'";
							$resSPrdC1	= $this->db->query($sqlSPrdC1)->result();
							foreach($resSPrdC1 as $rowPrdC1):
								$ITM_BUDGC1	= $rowPrdC1->ITM_BUDG;
								$ADD_COSTC1	= $rowPrdC1->ADD_JOBCOST;
								$ITM_USEMC1	= $rowPrdC1->ITM_USED_AM;
							endforeach;
							if($ISHEADER != 1)
							{
								$TotBUD3	= $TotBUD3 + $ITM_BUDGC3;
								$TotADD3	= $TotADD3 + $ADD_COSTC3;
								$TotUSE3	= $TotUSE3 + $ITM_USEMC3;
							}
						?>
						<td nowrap style="text-align:right; background-color:#C99">
                            <span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
                                <?php print number_format($ITM_BUDGC1, $decFormat); ?>
                            </span>
                        </td>
						<td nowrap style="text-align:right; background-color:#C99">
                            <span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
                                <?php print number_format($ADD_COSTC1, $decFormat); ?>
                            </span>
                        </td>
						<td nowrap style="text-align:right; background-color:#C99">
                            <span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
                                <?php print number_format($ITM_USEMC1, $decFormat); ?>
                            </span>
                        </td>
                        <?php
							$ITM_BUDGD1	= 0;
							$ADD_COSTD1	= 0;
							$ITM_USEMD1	= 0;
							$sqlSPrdD1	= "SELECT ITM_VOLM, ITM_LASTP, ITM_BUDG, ADD_VOLM, ADD_PRICE, ADD_JOBCOST, ITM_USED, ITM_USED_AM 
											FROM tbl_joblist_detail WHERE JOBCODEID_P = '$JOBCODEID' AND PRJCODE = '$PRJCODE4'";
							$resSPrdD1	= $this->db->query($sqlSPrdD1)->result();
							foreach($resSPrdD1 as $rowPrdD1):
								$ITM_BUDGD1	= $rowPrdD1->ITM_BUDG;
								$ADD_COSTD1	= $rowPrdD1->ADD_JOBCOST;
								$ITM_USEMD1	= $rowPrdD1->ITM_USED_AM;
							endforeach;
							if($ISHEADER != 1)
							{
								$TotBUD4	= $TotBUD4 + $ITM_BUDGD4;
								$TotADD4	= $TotADD4 + $ADD_COSTD4;
								$TotUSE4	= $TotUSE4 + $ITM_USEMD4;
							}
						?>
						<td nowrap style="text-align:right; background-color:#C9C">
                            <span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
                                <?php print number_format($ITM_BUDGD1, $decFormat); ?>
                            </span>
                        </td>
						<td nowrap style="text-align:right; background-color:#C9C">
                            <span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
                                <?php print number_format($ADD_COSTD1, $decFormat); ?>
                            </span>
                        </td>
						<td nowrap style="text-align:right; background-color:#C9C">
                            <span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
                                <?php print number_format($ITM_USEMD1, $decFormat); ?>
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
										FROM tbl_joblist_detailm
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
							$JOBVOLM2A 		= $rowJOB2->JOBVOLM;
							$JOBPRICE2 		= $rowJOB2->PRICE;
							$JOBCOST2A 		= $rowJOB2->JOBCOST;
							$JOBLEV2		= $rowJOB2->IS_LEVEL;
					
							$ADD_VOLM2 		= $rowJOB2->ADD_VOLM;
							$ADD_PRICE2 	= $rowJOB2->ADD_PRICE;
							$ADD_JOBCOST2	= $rowJOB2->ADD_JOBCOST;
							$ADDM_JOBCOST2	= $rowJOB2->ADDM_JOBCOST;
							//$JOBCOST2 		= $JOBCOST2A + $ADD_JOBCOST2;
							$JOBCOST2 		= $JOBCOST2A;
							//$ISHEADER2 		= $rowJOB2->ISHEADER;
							$ITM_USED_AM2	= $rowJOB2->ITM_USED_AM;
							
							$JOBVOLM2 		= $JOBVOLM2A + $ADD_VOLM2;
							
							/*$TotADD			= $TotADD + $ADD_JOBCOST2;
							$TotALL			= $TotBOQ + $TotADD;*/
							
							$JobView2		= "$JOBCODEID2V - $JOBDESC2";
							$space_level2	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
							$secUpd2		= site_url('c_comprof/c_bUd93tL15t/update/?id='.$this->url_encryption_helper->encode_url($JOBCODEID2));
					
							$ISHEADER2		= 0;
							$sqlC3			= "tbl_boqlistm WHERE JOBPARENT = '$JOBCODEID2' AND PRJCODE = '$PRJCODE'";
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
													FROM tbl_joblist_detailm
													WHERE JOBPARENT LIKE '$JOBCODEID2%' AND PRJCODE = '$PRJCODE'
														AND IS_LEVEL > $JOBLEV2 AND ISLAST = 1";
									$resTBUDG 	= $this->db->query($sqlTBUDG)->result();
									foreach($resTBUDG as $rowTBUDG) :
										$TOT_BUDGAM	= $rowTBUDG->TOT_BUDGAM;
										$TOT_ADDAM 	= $rowTBUDG->TOT_ADDAM;
									endforeach;
									$JOBCOST2		= $TOT_BUDGAM;
									$ADD_JOBCOST2	= $TOT_ADDAM;
								
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
										$TOTREMA_02	= ($TOTREMV_02 * $ITM_LASTP_02) + $ADD_JOBCOST2;
									}
										
									$TOTPRJC_02		= $TOTUSED_02 + $TOTREMA_02;	// Total Projection Complete
									$TotPC			= $TotPC + $TOTPRJC_02;
							}
							
							$STATCOLPC_02		= 'success';							// Status Color Projection Complete
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
							$secupdJob2	= site_url('c_comprof/c_bUd93tL15t/upd1d0ebbM5tr/?id='.$this->url_encryption_helper->encode_url($JOBCODEID2));
							?>
                                          <td nowrap>
									<?php
										print $space_level2;
										print $JobView2;
									?>								</td>
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
									<?php
										$ITM_BUDGA2	= 0;
										$ADD_COSTA2	= 0;
										$ITM_USEMA2	= 0;
										$sqlSPrdA2	= "SELECT ITM_VOLM, ITM_LASTP, ITM_BUDG, ADD_VOLM, ADD_PRICE, ADD_JOBCOST, ITM_USED, ITM_USED_AM 
														FROM tbl_joblist_detail WHERE JOBCODEID_P = '$JOBCODEID2' AND PRJCODE = '$PRJCODE1'";
										$resSPrdA2	= $this->db->query($sqlSPrdA2)->result();
										foreach($resSPrdA2 as $rowPrdA2):
											$ITM_BUDGA2	= $rowPrdA2->ITM_BUDG;
											$ADD_COSTA2	= $rowPrdA2->ADD_JOBCOST;
											$ITM_USEMA2	= $rowPrdA2->ITM_USED_AM;
										endforeach;
										if($ISHEADER2 != 1)
										{
											$TotBUD1	= $TotBUD1 + $ITM_BUDGA2;
											$TotADD1	= $TotADD1 + $ADD_COSTA2;
											$TotUSE1	= $TotUSE1 + $ITM_USEMA2;
										}
									?>
									<td nowrap style="text-align:right; background-color:#9C6">
										<span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
											<?php print number_format($ITM_BUDGA2, $decFormat); ?>
										</span>
									</td>
									<td nowrap style="text-align:right; background-color:#9C6">
										<span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
											<?php print number_format($ADD_COSTA2, $decFormat); ?>
										</span>
									</td>
									<td nowrap style="text-align:right; background-color:#9C6">
										<span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
											<?php print number_format($ITM_USEMA2, $decFormat); ?>
										</span>
									</td>
									<?php
										$ITM_BUDGB2	= 0;
										$ADD_COSTB2	= 0;
										$ITM_USEMB2	= 0;
										$sqlSPrdB2	= "SELECT ITM_VOLM, ITM_LASTP, ITM_BUDG, ADD_VOLM, ADD_PRICE, ADD_JOBCOST, ITM_USED, ITM_USED_AM 
														FROM tbl_joblist_detail WHERE JOBCODEID_P = '$JOBCODEID2' AND PRJCODE = '$PRJCODE2'";
										$resSPrdB2	= $this->db->query($sqlSPrdB2)->result();
										foreach($resSPrdB2 as $rowPrdB2):
											$ITM_BUDGB2	= $rowPrdB2->ITM_BUDG;
											$ADD_COSTB2	= $rowPrdB2->ADD_JOBCOST;
											$ITM_USEMB2	= $rowPrdB2->ITM_USED_AM;
										endforeach;
										if($ISHEADER2 != 1)
										{
											$TotBUD2	= $TotBUD2 + $ITM_BUDGB2;
											$TotADD2	= $TotADD2 + $ADD_COSTB2;
											$TotUSE2	= $TotUSE2 + $ITM_USEMB2;
										}
									?>
									<td nowrap style="text-align:right; background-color:#996">
										<span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
											<?php print number_format($ITM_BUDGB2, $decFormat); ?>
										</span>
									</td>
									<td nowrap style="text-align:right; background-color:#996">
										<span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
											<?php print number_format($ADD_COSTB2, $decFormat); ?>
										</span>
									</td>
									<td nowrap style="text-align:right; background-color:#996">
										<span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
											<?php print number_format($ITM_USEMB2, $decFormat); ?>
										</span>
									</td>
									<?php
										$ITM_BUDGC2	= 0;
										$ADD_COSTC2	= 0;
										$ITM_USEMC2	= 0;
										$sqlSPrdC2	= "SELECT ITM_VOLM, ITM_LASTP, ITM_BUDG, ADD_VOLM, ADD_PRICE, ADD_JOBCOST, ITM_USED, ITM_USED_AM 
														FROM tbl_joblist_detail WHERE JOBCODEID_P = '$JOBCODEID2' AND PRJCODE = '$PRJCODE3'";
										$resSPrdC2	= $this->db->query($sqlSPrdC2)->result();
										foreach($resSPrdC2 as $rowPrdC2):
											$ITM_BUDGC2	= $rowPrdC2->ITM_BUDG;
											$ADD_COSTC2	= $rowPrdC2->ADD_JOBCOST;
											$ITM_USEMC2	= $rowPrdC2->ITM_USED_AM;
										endforeach;
										if($ISHEADER2 != 1)
										{
											$TotBUD3	= $TotBUD3 + $ITM_BUDGC2;
											$TotADD3	= $TotADD3 + $ADD_COSTC2;
											$TotUSE3	= $TotUSE3 + $ITM_USEMC2;
										}
									?>
									<td nowrap style="text-align:right; background-color:#C99">
										<span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
											<?php print number_format($ITM_BUDGC2, $decFormat); ?>
										</span>
									</td>
									<td nowrap style="text-align:right; background-color:#C99">
										<span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
											<?php print number_format($ADD_COSTC2, $decFormat); ?>
										</span>
									</td>
									<td nowrap style="text-align:right; background-color:#C99">
										<span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
											<?php print number_format($ITM_USEMC2, $decFormat); ?>
										</span>
									</td>
									<?php
										$ITM_BUDGD2	= 0;
										$ADD_COSTD2	= 0;
										$ITM_USEMD2	= 0;
										$sqlSPrdD2	= "SELECT ITM_VOLM, ITM_LASTP, ITM_BUDG, ADD_VOLM, ADD_PRICE, ADD_JOBCOST, ITM_USED, ITM_USED_AM 
														FROM tbl_joblist_detail WHERE JOBCODEID_P = '$JOBCODEID2' AND PRJCODE = '$PRJCODE4'";
										$resSPrdD2	= $this->db->query($sqlSPrdD2)->result();
										foreach($resSPrdD2 as $rowPrdD2):
											$ITM_BUDGD2	= $rowPrdD2->ITM_BUDG;
											$ADD_COSTD2	= $rowPrdD2->ADD_JOBCOST;
											$ITM_USEMD2	= $rowPrdD2->ITM_USED_AM;
										endforeach;
										if($ISHEADER2 != 1)
										{
											$TotBUD2	= $TotBUD2 + $ITM_BUDGD2;
											$TotADD2	= $TotADD2 + $ADD_COSTD2;
											$TotUSE2	= $TotUSE2 + $ITM_USEMD2;
										}
									?>
									<td nowrap style="text-align:right; background-color:#C9C">
										<span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
											<?php print number_format($ITM_BUDGD2, $decFormat); ?>
										</span>
									</td>
									<td nowrap style="text-align:right; background-color:#C9C">
										<span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
											<?php print number_format($ADD_COSTD2, $decFormat); ?>
										</span>
									</td>
									<td nowrap style="text-align:right; background-color:#C9C">
										<span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
											<?php print number_format($ITM_USEMD2, $decFormat); ?>
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
												FROM tbl_joblist_detailm
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
									$JOBVOLM3A 		= $rowJOB3->JOBVOLM;
									$JOBPRICE3 		= $rowJOB3->PRICE;
									$JOBCOST3 		= $rowJOB3->JOBCOST;
									$JOBCOST3A 		= $rowJOB3->JOBCOST;
									$JOBLEV3		= $rowJOB3->IS_LEVEL;
							
									$ADD_VOLM3 		= $rowJOB3->ADD_VOLM;
									$ADD_PRICE3 	= $rowJOB3->ADD_PRICE;
									$ADD_JOBCOST3	= $rowJOB3->ADD_JOBCOST;
									$ADDM_JOBCOST3	= $rowJOB3->ADDM_JOBCOST;
									//$JOBCOST3 		= $JOBCOST3A + $ADD_JOBCOST3;
									$JOBCOST3 		= $JOBCOST3A;
									//$ISHEADER3 		= $rowJOB3->ISHEADER;
									$ITM_USED_AM3	= $rowJOB3->ITM_USED_AM;
							
									$JOBVOLM3 		= $JOBVOLM3A + $ADD_VOLM3;
									
									/*$TotADD			= $TotADD + $ADD_JOBCOST3;
									$TotALL			= $TotBOQ + $TotADD;*/
									
									$JobView3		= "$JOBCODEID3V - $JOBDESC3";
									$space_level3	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
									$secUpd3		= site_url('c_comprof/c_bUd93tL15t/update/?id='.$this->url_encryption_helper->encode_url($JOBCODEID3));
					
									$ISHEADER3		= 0;
									$sqlC4			= "tbl_boqlistm WHERE JOBPARENT = '$JOBCODEID3' AND PRJCODE = '$PRJCODE'";
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
															FROM tbl_joblist_detailm
															WHERE JOBPARENT LIKE '$JOBCODEID3%' AND PRJCODE = '$PRJCODE'
																AND IS_LEVEL > $JOBLEV3 AND ISLAST = 1";
											$resTBUDG 	= $this->db->query($sqlTBUDG)->result();
											foreach($resTBUDG as $rowTBUDG) :
												$TOT_BUDGAM	= $rowTBUDG->TOT_BUDGAM;
												$TOT_ADDAM 	= $rowTBUDG->TOT_ADDAM;
											endforeach;
											$JOBCOST3		= $TOT_BUDGAM;
											$ADD_JOBCOST3	= $TOT_ADDAM;
											
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
												$TOTREMA_03	= ($TOTREMV_03 * $ITM_LASTP_03) + $ADD_JOBCOST3;
												/*if($JOBCODEID3 == 'DPI0319001.01.A.07')
													echo "$TOTREMA_03	= ($TOTREMV_03 * $ITM_LASTP_03) + $ADD_JOBCOST3<br>";*/
											}
												
											$TOTPRJC_03		= $TOTUSED_03 + $TOTREMA_03;	// Total Projection Complete
											$TotPC			= $TotPC + $TOTPRJC_03;
											/*if($JOBCODEID3 == 'DPI0319001.01.A.07')
												echo "$TOTPRJC_03		= $TOTUSED_03 + $TOTREMA_03";*/
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
										
									$secupdJob3	= site_url('c_comprof/c_bUd93tL15t/upd1d0ebbM5tr/?id='.$this->url_encryption_helper->encode_url($JOBCODEID3));
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
											<?php
												$ITM_BUDGA3	= 0;
												$ADD_COSTA3	= 0;
												$ITM_USEMA3	= 0;
												$sqlSPrdA3	= "SELECT ITM_VOLM, ITM_LASTP, ITM_BUDG, ADD_VOLM, ADD_PRICE, ADD_JOBCOST, 
																	ITM_USED, ITM_USED_AM 
																FROM tbl_joblist_detail WHERE JOBCODEID_P = '$JOBCODEID3' 
																	AND PRJCODE = '$PRJCODE1'";
												$resSPrdA3	= $this->db->query($sqlSPrdA3)->result();
												foreach($resSPrdA3 as $rowPrdA3):
													$ITM_BUDGA3	= $rowPrdA3->ITM_BUDG;
													$ADD_COSTA3	= $rowPrdA3->ADD_JOBCOST;
													$ITM_USEMA3	= $rowPrdA3->ITM_USED_AM;
												endforeach;
												if($ISHEADER3 != 1)
												{
													$TotBUD1	= $TotBUD1 + $ITM_BUDGA3;
													$TotADD1	= $TotADD1 + $ADD_COSTA3;
													$TotUSE1	= $TotUSE1 + $ITM_USEMA3;
												}
											?>
											<td nowrap style="text-align:right; background-color:#9C6">
												<span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
													<?php print number_format($ITM_BUDGA3, $decFormat); ?>
												</span>
											</td>
											<td nowrap style="text-align:right; background-color:#9C6">
												<span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
													<?php print number_format($ADD_COSTA3, $decFormat); ?>
												</span>
											</td>
											<td nowrap style="text-align:right; background-color:#9C6">
												<span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
													<?php print number_format($ITM_USEMA3, $decFormat); ?>
												</span>
											</td>
											<?php
												$ITM_BUDGB3	= 0;
												$ADD_COSTB3	= 0;
												$ITM_USEMB3	= 0;
												$sqlSPrdB3	= "SELECT ITM_VOLM, ITM_LASTP, ITM_BUDG, ADD_VOLM, ADD_PRICE, ADD_JOBCOST, 
																	ITM_USED, ITM_USED_AM 
																FROM tbl_joblist_detail WHERE JOBCODEID_P = '$JOBCODEID3' 
																	AND PRJCODE = '$PRJCODE2'";
												$resSPrdB3	= $this->db->query($sqlSPrdB3)->result();
												foreach($resSPrdB3 as $rowPrdB3):
													$ITM_BUDGB3	= $rowPrdB3->ITM_BUDG;
													$ADD_COSTB3	= $rowPrdB3->ADD_JOBCOST;
													$ITM_USEMB3	= $rowPrdB3->ITM_USED_AM;
												endforeach;
												if($ISHEADER3 != 1)
												{
													$TotBUD2	= $TotBUD2 + $ITM_BUDGB3;
													$TotADD2	= $TotADD2 + $ADD_COSTB3;
													$TotUSE2	= $TotUSE2 + $ITM_USEMB3;
												}
											?>
											<td nowrap style="text-align:right; background-color:#996">
												<span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
													<?php print number_format($ITM_BUDGB3, $decFormat); ?>
												</span>
											</td>
											<td nowrap style="text-align:right; background-color:#996">
												<span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
													<?php print number_format($ADD_COSTB3, $decFormat); ?>
												</span>
											</td>
											<td nowrap style="text-align:right; background-color:#996">
												<span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
													<?php print number_format($ITM_USEMB3, $decFormat); ?>
												</span>
											</td>
											<?php
												$ITM_BUDGC3	= 0;
												$ADD_COSTC3	= 0;
												$ITM_USEMC3	= 0;
												$sqlSPrdC3	= "SELECT ITM_VOLM, ITM_LASTP, ITM_BUDG, ADD_VOLM, ADD_PRICE, ADD_JOBCOST, 
																	ITM_USED, ITM_USED_AM 
																FROM tbl_joblist_detail WHERE JOBCODEID_P = '$JOBCODEID3' 
																	AND PRJCODE = '$PRJCODE3'";
												$resSPrdC3	= $this->db->query($sqlSPrdC3)->result();
												foreach($resSPrdC3 as $rowPrdC3):
													$ITM_BUDGC3	= $rowPrdC3->ITM_BUDG;
													$ADD_COSTC3	= $rowPrdC3->ADD_JOBCOST;
													$ITM_USEMC3	= $rowPrdC3->ITM_USED_AM;
												endforeach;
												if($ISHEADER3 != 1)
												{
													$TotBUD3	= $TotBUD3 + $ITM_BUDGC3;
													$TotADD3	= $TotADD3 + $ADD_COSTC3;
													$TotUSE3	= $TotUSE3 + $ITM_USEMC3;
												}
											?>
											<td nowrap style="text-align:right; background-color:#C99">
												<span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
													<?php print number_format($ITM_BUDGC3, $decFormat); ?>
												</span>
											</td>
											<td nowrap style="text-align:right; background-color:#C99">
												<span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
													<?php print number_format($ADD_COSTC3, $decFormat); ?>
												</span>
											</td>
											<td nowrap style="text-align:right; background-color:#C99">
												<span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
													<?php print number_format($ITM_USEMC3, $decFormat); ?>
												</span>
											</td>
											<?php
												$ITM_BUDGD3	= 0;
												$ADD_COSTD3	= 0;
												$ITM_USEMD3	= 0;
												$sqlSPrdD3	= "SELECT ITM_VOLM, ITM_LASTP, ITM_BUDG, ADD_VOLM, ADD_PRICE, ADD_JOBCOST, 
																	ITM_USED, ITM_USED_AM 
																FROM tbl_joblist_detail WHERE JOBCODEID_P = '$JOBCODEID3' 
																	AND PRJCODE = '$PRJCODE4'";
												$resSPrdD3	= $this->db->query($sqlSPrdD3)->result();
												foreach($resSPrdD3 as $rowPrdD3):
													$ITM_BUDGD3	= $rowPrdD3->ITM_BUDG;
													$ADD_COSTD3	= $rowPrdD3->ADD_JOBCOST;
													$ITM_USEMD3	= $rowPrdD3->ITM_USED_AM;
												endforeach;
												if($ISHEADER3 != 1)
												{
													$TotBUD4	= $TotBUD4 + $ITM_BUDGD3;
													$TotADD4	= $TotADD4 + $ADD_COSTD3;
													$TotUSE4	= $TotUSE4 + $ITM_USEMD3;
												}
											?>
											<td nowrap style="text-align:right; background-color:#C9C">
												<span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
													<?php print number_format($ITM_BUDGD3, $decFormat); ?>
												</span>
											</td>
											<td nowrap style="text-align:right; background-color:#C9C">
												<span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
													<?php print number_format($ADD_COSTD3, $decFormat); ?>
												</span>
											</td>
											<td nowrap style="text-align:right; background-color:#C9C">
												<span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
													<?php print number_format($ITM_USEMD3, $decFormat); ?>
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
														FROM tbl_joblist_detailm
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
											$JOBVOLM4A 		= $rowJOB4->JOBVOLM;
											$JOBPRICE4 		= $rowJOB4->PRICE;
											$JOBCOST4 		= $rowJOB4->JOBCOST;
											$JOBCOST4A 		= $rowJOB4->JOBCOST;
											$JOBLEV4		= $rowJOB4->IS_LEVEL;
									
											$ADD_VOLM4 		= $rowJOB4->ADD_VOLM;
											$ADD_PRICE4 	= $rowJOB4->ADD_PRICE;
											$ADD_JOBCOST4	= $rowJOB4->ADD_JOBCOST;
											$ADDM_JOBCOST4	= $rowJOB4->ADDM_JOBCOST;				
											//$JOBCOST4 		= $JOBCOST4A + $ADD_JOBCOST4;					
											$JOBCOST4 		= $JOBCOST4A;
											//$ISHEADER4 		= $rowJOB4->ISHEADER;
											$ITM_USED_AM4	= $rowJOB4->ITM_USED_AM;
											
											$JOBVOLM4 		= $JOBVOLM4A + $ADD_VOLM4;
											
											/*$TotADD			= $TotADD + $ADD_JOBCOST4;
											$TotALL			= $TotBOQ + $TotADD;*/
											
											$JobView4		= "$JOBCODEID4V - $JOBDESC4";
											$space_level4	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
											$secUpd4		= site_url('c_comprof/c_bUd93tL15t/update/?id='.$this->url_encryption_helper->encode_url($JOBCODEID4));
					
											$ISHEADER4		= 0;
											$sqlC5			= "tbl_boqlistm WHERE JOBPARENT = '$JOBCODEID4' AND PRJCODE = '$PRJCODE'";
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
																	FROM tbl_joblist_detailm
																	WHERE JOBPARENT LIKE '$JOBCODEID4%' AND PRJCODE = '$PRJCODE'
																		AND IS_LEVEL > $JOBLEV4 AND ISLAST = 1";
													$resTBUDG 	= $this->db->query($sqlTBUDG)->result();
													foreach($resTBUDG as $rowTBUDG) :
														$TOT_BUDGAM	= $rowTBUDG->TOT_BUDGAM;
														$TOT_ADDAM 	= $rowTBUDG->TOT_ADDAM;
													endforeach;
													$JOBCOST4		= $TOT_BUDGAM;
													$ADD_JOBCOST4	= $TOT_ADDAM;
													
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
														$TOTREMA_04	= ($TOTREMV_04 * $ITM_LASTP_04) + $ADD_JOBCOST4;
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
												
											$secupdJob4	= site_url('c_comprof/c_bUd93tL15t/upd1d0ebbM5tr/?id='.$this->url_encryption_helper->encode_url($JOBCODEID4));
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
													<?php
														$ITM_BUDGA4	= 0;
														$ADD_COSTA4	= 0;
														$ITM_USEMA4	= 0;
														$sqlSPrdA4	= "SELECT ITM_VOLM, ITM_LASTP, ITM_BUDG, ADD_VOLM, ADD_PRICE, ADD_JOBCOST, 
																			ITM_USED, ITM_USED_AM 
																		FROM tbl_joblist_detail WHERE JOBCODEID_P = '$JOBCODEID4' 
																			AND PRJCODE = '$PRJCODE1'";
														$resSPrdA4	= $this->db->query($sqlSPrdA4)->result();
														foreach($resSPrdA4 as $rowPrdA4):
															$ITM_BUDGA4	= $rowPrdA4->ITM_BUDG;
															$ADD_COSTA4	= $rowPrdA4->ADD_JOBCOST;
															$ITM_USEMA4	= $rowPrdA4->ITM_USED_AM;
														endforeach;
														if($ISHEADER4 != 1)
														{
															$TotBUD1	= $TotBUD1 + $ITM_BUDGA4;
															$TotADD1	= $TotADD1 + $ADD_COSTA4;
															$TotUSE1	= $TotUSE1 + $ITM_USEMA4;
														}
													?>
													<td nowrap style="text-align:right; background-color:#9C6">
														<span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
															<?php print number_format($ITM_BUDGA4, $decFormat); ?>
														</span>
													</td>
													<td nowrap style="text-align:right; background-color:#9C6">
														<span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
															<?php print number_format($ADD_COSTA4, $decFormat); ?>
														</span>
													</td>
													<td nowrap style="text-align:right; background-color:#9C6">
														<span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
															<?php print number_format($ITM_USEMA4, $decFormat); ?>
														</span>
													</td>
													<?php
														$ITM_BUDGB4	= 0;
														$ADD_COSTB4	= 0;
														$ITM_USEMB4	= 0;
														$sqlSPrdB4	= "SELECT ITM_VOLM, ITM_LASTP, ITM_BUDG, ADD_VOLM, ADD_PRICE, ADD_JOBCOST, 
																			ITM_USED, ITM_USED_AM 
																		FROM tbl_joblist_detail WHERE JOBCODEID_P = '$JOBCODEID4' 
																			AND PRJCODE = '$PRJCODE2'";
														$resSPrdB4	= $this->db->query($sqlSPrdB4)->result();
														foreach($resSPrdB4 as $rowPrdB4):
															$ITM_BUDGB4	= $rowPrdB4->ITM_BUDG;
															$ADD_COSTB4	= $rowPrdB4->ADD_JOBCOST;
															$ITM_USEMB4	= $rowPrdB4->ITM_USED_AM;
														endforeach;
														if($ISHEADER4 != 1)
														{
															$TotBUD2	= $TotBUD2 + $ITM_BUDGB4;
															$TotADD2	= $TotADD2 + $ADD_COSTB4;
															$TotUSE2	= $TotUSE2 + $ITM_USEMB4;
														}
													?>
													<td nowrap style="text-align:right; background-color:#996">
														<span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
															<?php print number_format($ITM_BUDGB4, $decFormat); ?>
														</span>
													</td>
													<td nowrap style="text-align:right; background-color:#996">
														<span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
															<?php print number_format($ADD_COSTB4, $decFormat); ?>
														</span>
													</td>
													<td nowrap style="text-align:right; background-color:#996">
														<span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
															<?php print number_format($ITM_USEMB4, $decFormat); ?>
														</span>
													</td>
													<?php
														$ITM_BUDGC4	= 0;
														$ADD_COSTC4	= 0;
														$ITM_USEMC4	= 0;
														$sqlSPrdC4	= "SELECT ITM_VOLM, ITM_LASTP, ITM_BUDG, ADD_VOLM, ADD_PRICE, ADD_JOBCOST, 
																			ITM_USED, ITM_USED_AM 
																		FROM tbl_joblist_detail WHERE JOBCODEID_P = '$JOBCODEID4' 
																			AND PRJCODE = '$PRJCODE3'";
														$resSPrdC4	= $this->db->query($sqlSPrdC4)->result();
														foreach($resSPrdC4 as $rowPrdC4):
															$ITM_BUDGC4	= $rowPrdC4->ITM_BUDG;
															$ADD_COSTC4	= $rowPrdC4->ADD_JOBCOST;
															$ITM_USEMC4	= $rowPrdC4->ITM_USED_AM;
														endforeach;
														if($ISHEADER4 != 1)
														{
															$TotBUD3	= $TotBUD3 + $ITM_BUDGC4;
															$TotADD3	= $TotADD3 + $ADD_COSTC4;
															$TotUSE3	= $TotUSE3 + $ITM_USEMC4;
														}
													?>
													<td nowrap style="text-align:right; background-color:#C99">
														<span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
															<?php print number_format($ITM_BUDGC4, $decFormat); ?>
														</span>
													</td>
													<td nowrap style="text-align:right; background-color:#C99">
														<span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
															<?php print number_format($ADD_COSTC4, $decFormat); ?>
														</span>
													</td>
													<td nowrap style="text-align:right; background-color:#C99">
														<span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
															<?php print number_format($ITM_USEMC4, $decFormat); ?>
														</span>
													</td>
													<?php
														$ITM_BUDGD4	= 0;
														$ADD_COSTD4	= 0;
														$ITM_USEMD4	= 0;
														$sqlSPrdD4	= "SELECT ITM_VOLM, ITM_LASTP, ITM_BUDG, ADD_VOLM, ADD_PRICE, ADD_JOBCOST, 
																			ITM_USED, ITM_USED_AM 
																		FROM tbl_joblist_detail WHERE JOBCODEID_P = '$JOBCODEID4' 
																			AND PRJCODE = '$PRJCODE4'";
														$resSPrdD4	= $this->db->query($sqlSPrdD4)->result();
														foreach($resSPrdD4 as $rowPrdD4):
															$ITM_BUDGD4	= $rowPrdD4->ITM_BUDG;
															$ADD_COSTD4	= $rowPrdD4->ADD_JOBCOST;
															$ITM_USEMD4	= $rowPrdD4->ITM_USED_AM;
														endforeach;
														if($ISHEADER4 != 1)
														{
															$TotBUD4	= $TotBUD4 + $ITM_BUDGD4;
															$TotADD4	= $TotADD4 + $ADD_COSTD4;
															$TotUSE4	= $TotUSE4 + $ITM_USEMD4;
														}
													?>
													<td nowrap style="text-align:right; background-color:#C9C">
														<span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
															<?php print number_format($ITM_BUDGD4, $decFormat); ?>
														</span>
													</td>
													<td nowrap style="text-align:right; background-color:#C9C">
														<span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
															<?php print number_format($ADD_COSTD4, $decFormat); ?>
														</span>
													</td>
													<td nowrap style="text-align:right; background-color:#C9C">
														<span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
															<?php print number_format($ITM_USEMD4, $decFormat); ?>
														</span>
													</td>
                                                    <td nowrap style="text-align:right">
													<?php if($ISHEADER4 != 1) { ?>
                                                        <span class="label label-<?php echo $STATCOLPC_04; ?>" style="font-size:12px">
                                                            <?php print number_format($ITM_BUDGA4, $decFormat); ?>
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
											$sqlC5		= "tbl_boqlistm WHERE JOBPARENT = '$JOBCODEID4' AND PRJCODE = '$PRJCODE'";
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
																	IS_LEVEL
																FROM tbl_joblist_detailm
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
													$JOBVOLM5A 		= $rowJOB5->JOBVOLM;
													$JOBPRICE5 		= $rowJOB5->PRICE;
													$JOBCOST5 		= $rowJOB5->JOBCOST;
													$JOBCOST5A 		= $rowJOB5->JOBCOST;
													$JOBLEV5		= $rowJOB5->IS_LEVEL;
											
													$ADD_VOLM5 		= $rowJOB5->ADD_VOLM;
													$ADD_PRICE5 	= $rowJOB5->ADD_PRICE;
													$ADD_JOBCOST5	= $rowJOB5->ADD_JOBCOST;
													$ADDM_JOBCOST5	= $rowJOB5->ADDM_JOBCOST;				
													//$JOBCOST5 		= $JOBCOST5A + $ADD_JOBCOST5;					
													$JOBCOST5 		= $JOBCOST5A;
													//$ISHEADER5 		= $rowJOB5->ISHEADER;
													$ITM_USED_AM5	= $rowJOB5->ITM_USED_AM;
													
													$JOBVOLM5		= $JOBVOLM5A + $ADD_VOLM5;
						
													$REMAIN5		= $JOBCOST5 + $ADD_JOBCOST5 - $ITM_USED_AM5;
													$TotREM			= $TotREM + $REMAIN5;
													
													/*$TotADD			= $TotADD + $ADD_JOBCOST5;
													$TotALL			= $TotBOQ + $TotADD;*/
							
													$JobView5		= "$JOBCODEID5V - $JOBDESC5";
													$space_level5	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
													$space_level5	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
													$secUpd5		= site_url('c_comprof/c_bUd93tL15t/update/?id='.$this->url_encryption_helper->encode_url($JOBCODEID5));
					
													$ISHEADER5		= 0;
													$sqlC6			= "tbl_boqlistm WHERE JOBPARENT = '$JOBCODEID5' AND PRJCODE = '$PRJCODE'";
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
																			FROM tbl_joblist_detailm
																			WHERE JOBPARENT LIKE '$JOBCODEID5%' 
																				AND PRJCODE = '$PRJCODE' AND IS_LEVEL > $JOBLEV5 
																				AND ISLAST = 1";
															$resTBUDG 	= $this->db->query($sqlTBUDG)->result();
															foreach($resTBUDG as $rowTBUDG) :
																$TOT_BUDGAM	= $rowTBUDG->TOT_BUDGAM;
																$TOT_ADDAM 	= $rowTBUDG->TOT_ADDAM;
															endforeach;
															$JOBCOST5		= $TOT_BUDGAM;
															$ADD_JOBCOST5	= $TOT_ADDAM;
															
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
																$TOTREMA_05	= ($TOTREMV_05 * $ITM_LASTP_05) + $ADD_JOBCOST5;
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
														
													$secupdJob5	= site_url('c_comprof/c_bUd93tL15t/upd1d0ebbM5tr/?id='.$this->url_encryption_helper->encode_url($JOBCODEID5));
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
															<?php
																$ITM_BUDGA5	= 0;
																$ADD_COSTA5	= 0;
																$ITM_USEMA5	= 0;
																$sqlSPrdA5	= "SELECT ITM_VOLM, ITM_LASTP, ITM_BUDG, ADD_VOLM, ADD_PRICE, ADD_JOBCOST, 
																					ITM_USED, ITM_USED_AM 
																				FROM tbl_joblist_detail WHERE JOBCODEID_P = '$JOBCODEID5' 
																					AND PRJCODE = '$PRJCODE1'";
																$resSPrdA5	= $this->db->query($sqlSPrdA5)->result();
																foreach($resSPrdA5 as $rowPrdA5):
																	$ITM_BUDGA5	= $rowPrdA5->ITM_BUDG;
																	$ADD_COSTA5	= $rowPrdA5->ADD_JOBCOST;
																	$ITM_USEMA5	= $rowPrdA5->ITM_USED_AM;
																endforeach;
																if($ISHEADER5 != 1)
																{
																	$TotBUD1	= $TotBUD1 + $ITM_BUDGA5;
																	$TotADD1	= $TotADD1 + $ADD_COSTA5;
																	$TotUSE1	= $TotUSE1 + $ITM_USEMA5;
																}
															?>
															<td nowrap style="text-align:right; background-color:#9C6">
																<span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
																	<?php print number_format($ITM_BUDGA5, $decFormat); ?>
																</span>
															</td>
															<td nowrap style="text-align:right; background-color:#9C6">
																<span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
																	<?php print number_format($ADD_COSTA5, $decFormat); ?>
																</span>
															</td>
															<td nowrap style="text-align:right; background-color:#9C6">
																<span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
																	<?php print number_format($ITM_USEMA5, $decFormat); ?>
																</span>
															</td>
															<?php
																$ITM_BUDGB5	= 0;
																$ADD_COSTB5	= 0;
																$ITM_USEMB5	= 0;
																$sqlSPrdB5	= "SELECT ITM_VOLM, ITM_LASTP, ITM_BUDG, ADD_VOLM, ADD_PRICE, ADD_JOBCOST, 
																					ITM_USED, ITM_USED_AM 
																				FROM tbl_joblist_detail WHERE JOBCODEID_P = '$JOBCODEID5' 
																					AND PRJCODE = '$PRJCODE2'";
																$resSPrdB5	= $this->db->query($sqlSPrdB5)->result();
																foreach($resSPrdB5 as $rowPrdB5):
																	$ITM_BUDGB5	= $rowPrdB5->ITM_BUDG;
																	$ADD_COSTB5	= $rowPrdB5->ADD_JOBCOST;
																	$ITM_USEMB5	= $rowPrdB5->ITM_USED_AM;
																endforeach;
																if($ISHEADER5 != 1)
																{
																	$TotBUD2	= $TotBUD2 + $ITM_BUDGB5;
																	$TotADD2	= $TotADD2 + $ADD_COSTB5;
																	$TotUSE2	= $TotUSE2 + $ITM_USEMB5;
																}
															?>
															<td nowrap style="text-align:right; background-color:#996">
																<span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
																	<?php print number_format($ITM_BUDGB5, $decFormat); ?>
																</span>
															</td>
															<td nowrap style="text-align:right; background-color:#996">
																<span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
																	<?php print number_format($ADD_COSTB5, $decFormat); ?>
																</span>
															</td>
															<td nowrap style="text-align:right; background-color:#996">
																<span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
																	<?php print number_format($ITM_USEMB5, $decFormat); ?>
																</span>
															</td>
															<?php
																$ITM_BUDGC5	= 0;
																$ADD_COSTC5	= 0;
																$ITM_USEMC5	= 0;
																$sqlSPrdC5	= "SELECT ITM_VOLM, ITM_LASTP, ITM_BUDG, ADD_VOLM, ADD_PRICE, ADD_JOBCOST, 
																					ITM_USED, ITM_USED_AM 
																				FROM tbl_joblist_detail WHERE JOBCODEID_P = '$JOBCODEID5' 
																					AND PRJCODE = '$PRJCODE3'";
																$resSPrdC5	= $this->db->query($sqlSPrdC5)->result();
																foreach($resSPrdC5 as $rowPrdC5):
																	$ITM_BUDGC5	= $rowPrdC5->ITM_BUDG;
																	$ADD_COSTC5	= $rowPrdC5->ADD_JOBCOST;
																	$ITM_USEMC5	= $rowPrdC5->ITM_USED_AM;
																endforeach;
																if($ISHEADER5 != 1)
																{
																	$TotBUD3	= $TotBUD3 + $ITM_BUDGC5;
																	$TotADD3	= $TotADD3 + $ADD_COSTC5;
																	$TotUSE3	= $TotUSE3 + $ITM_USEMC5;
																}
															?>
															<td nowrap style="text-align:right; background-color:#C99">
																<span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
																	<?php print number_format($ITM_BUDGC5, $decFormat); ?>
																</span>
															</td>
															<td nowrap style="text-align:right; background-color:#C99">
																<span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
																	<?php print number_format($ADD_COSTC5, $decFormat); ?>
																</span>
															</td>
															<td nowrap style="text-align:right; background-color:#C99">
																<span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
																	<?php print number_format($ITM_USEMC5, $decFormat); ?>
																</span>
															</td>
															<?php
																$ITM_BUDGD5	= 0;
																$ADD_COSTD5	= 0;
																$ITM_USEMD5	= 0;
																$sqlSPrdD5	= "SELECT ITM_VOLM, ITM_LASTP, ITM_BUDG, ADD_VOLM, ADD_PRICE, ADD_JOBCOST, 
																					ITM_USED, ITM_USED_AM 
																				FROM tbl_joblist_detail WHERE JOBCODEID_P = '$JOBCODEID5' 
																					AND PRJCODE = '$PRJCODE1'";
																$resSPrdD5	= $this->db->query($sqlSPrdD5)->result();
																foreach($resSPrdD5 as $rowPrdD5):
																	$ITM_BUDGD5	= $rowPrdD5->ITM_BUDG;
																	$ADD_COSTD5	= $rowPrdD5->ADD_JOBCOST;
																	$ITM_USEMD5	= $rowPrdD5->ITM_USED_AM;
																endforeach;
																if($ISHEADER5 != 1)
																{
																	$TotBUD4	= $TotBUD4 + $ITM_BUDGD5;
																	$TotADD4	= $TotADD4 + $ADD_COSTD5;
																	$TotUSE4	= $TotUSE4 + $ITM_USEMD5;
																}
															?>
															<td nowrap style="text-align:right; background-color:#C9C">
																<span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
																	<?php print number_format($ITM_BUDGD5, $decFormat); ?>
																</span>
															</td>
															<td nowrap style="text-align:right; background-color:#C9C">
																<span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
																	<?php print number_format($ADD_COSTD5, $decFormat); ?>
																</span>
															</td>
															<td nowrap style="text-align:right; background-color:#C9C">
																<span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
																	<?php print number_format($ITM_USEMD5, $decFormat); ?>
																</span>
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
                <th width="2%" nowrap style="text-align:right; vertical-align:middle; background-color:#9C6"><?php echo number_format($TotBUD1, $decFormat); ?></th>
                <th width="2%" nowrap style="text-align:right; vertical-align:middle; background-color:#9C6"><?php echo number_format($TotADD1, $decFormat); ?></th>
                <th width="3%" nowrap style="text-align:right; vertical-align:middle; background-color:#9C6"><?php echo number_format($TotUSE1, $decFormat); ?></th>
                <th width="2%" nowrap style="text-align:right; vertical-align:middle; background-color:#996"><?php echo number_format($TotBUD2, $decFormat); ?></th>
                <th width="1%" nowrap style="text-align:right; vertical-align:middle; background-color:#996"><?php echo number_format($TotADD2, $decFormat); ?></th>
                <th width="3%" nowrap style="text-align:right; vertical-align:middle; background-color:#996"><?php echo number_format($TotUSE2, $decFormat); ?></th>
                <th width="2%" nowrap style="text-align:right; vertical-align:middle; background-color:#C99"><?php echo number_format($TotBUD3, $decFormat); ?></th>
                <th width="1%" nowrap style="text-align:right; vertical-align:middle; background-color:#C99"><?php echo number_format($TotADD3, $decFormat); ?></th>
                <th width="3%" nowrap style="text-align:right; vertical-align:middle; background-color:#C99"><?php echo number_format($TotUSE3, $decFormat); ?></th>
                <th width="0%" nowrap style="text-align:right; vertical-align:middle; background-color:#C9C"><?php echo number_format($TotBUD4, $decFormat); ?></th>
                <th width="0%" nowrap style="text-align:right; vertical-align:middle; background-color:#C9C"><?php echo number_format($TotADD4, $decFormat); ?></th>
                <th width="0%" nowrap style="text-align:right; vertical-align:middle; background-color:#C9C"><?php echo number_format($TotUSE4, $decFormat); ?></th>
                <th width="3%" nowrap style="text-align:right; vertical-align:middle">&nbsp;</th>
                <th width="3%" nowrap style="text-align:right; vertical-align:middle">&nbsp;</th>
            </tr>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="19" style="text-align:left">
				<?php
					if($ISCREATE == 1)
					{
						// TO ADD MUST BE FROM AMANDEMEN
						//echo anchor("$secAddURL",'<button class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i></button>&nbsp;&nbsp;');
					} 
					echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>');

                ?>          	</td>
          </tr>
        </tfoot>
   	</table>
    </div>
    <!-- /.box-body -->
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
</script>
<?php 
$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>