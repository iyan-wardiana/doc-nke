<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 12 Maret 2017
 * File Name	= project_selectmc.php
 * Location		= -
*/
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');

$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
if($decFormat == 0)
	$decFormat		= 2;


$PINV_AKUMNEXT		= 0;
/*$sqlSrc 	= "tbl_mcheader A
				WHERE A.PRJCODE = '$PRJCODE' AND MC_STAT IN (2,3) AND MC_APPSTAT IN (0,1)
				ORDER BY A.MC_MANNO ASC";*/
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/style.css'; ?>");</style>
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/styleZebra.css'; ?>");</style>
    <title><?php echo $appName; ?> | Data Tables</title>
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

	<?php
	
		$LangID 	= $this->session->userdata['LangID'];

		$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
		$resTransl		= $this->db->query($sqlTransl)->result();
		foreach($resTransl as $rowTransl) :
			$TranslCode	= $rowTransl->MLANG_CODE;
			$LangTransl	= $rowTransl->LangTransl;
				
			if($TranslCode == 'Add')$Add = $LangTransl;
			if($TranslCode == 'Edit')$Edit = $LangTransl;
			if($TranslCode == 'Save')$Save = $LangTransl;
			if($TranslCode == 'Update')$Update = $LangTransl;
			if($TranslCode == 'Back')$Back = $LangTransl;
			if($TranslCode == 'MCList')$MCList = $LangTransl;
			if($TranslCode == 'MCListGroup')$MCListGroup = $LangTransl;
			if($TranslCode == 'MCCode')$MCCode = $LangTransl;
			if($TranslCode == 'MCNumber')$MCNumber = $LangTransl;
			if($TranslCode == 'InvoiceNumber')$InvoiceNumber = $LangTransl;
			if($TranslCode == 'MCDate')$MCDate = $LangTransl;
			if($TranslCode == 'PrestationVal')$PrestationVal = $LangTransl;
			if($TranslCode == 'ReceivedAmount')$ReceivedAmount = $LangTransl;
			if($TranslCode == 'Notes')$Notes = $LangTransl;
			if($TranslCode == 'Select')$Select = $LangTransl;
			if($TranslCode == 'Close')$Close = $LangTransl;
		endforeach;
		if($LangID == 'IND')
		{
			$selectMC	= "Silahkan pilih salah satu MC di bawah ini.";
		}
		else
		{
			$selectMC	= "Please select one of MC Number below.";
		}
	
		if(isset($_POST['submit1']))
		{
			$List_Type 		= $this->input->post('List_Type');
			if($List_Type == 1)
			{
				$Active1		= "active";
				$Active2		= "";
				$Active1Cls		= "class='active'";
				$Active2Cls		= "";
			}
			else
			{
				$Active1		= "";
				$Active2		= "active";
				$Active1Cls		= "";
				$Active2Cls		= "class='active'";
			}
		}
		else
		{
			$List_Type		= 2;
			$Active1		= "active";
			$Active2		= "";
			$Active1Cls		= "class='active'";
			$Active2Cls		= "";
		}
	?>

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">

</section>
<form class="form-horizontal" name="frm_01" method="post" action="" style="display:none">
	<input type="text" name="List_Type" id="List_Type" value="<?php echo $List_Type; ?>" />
    <input type="submit" class="button_css" name="submit1" id="submit1" value="Submit" align="left" />
</form>
<!-- Main content -->
  <div class="box">
    <!-- /.box-header -->
	<div class="box-body">
		<div class="callout callout-success">
        	<p><?php echo $selectMC; ?></p>
      	</div>
        <div class="row">
             <div class="col-md-9">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li <?php echo $Active2Cls; ?>><a href="#profPicture" data-toggle="tab" onClick="setType(2)"><?php echo $MCList; ?></a></li>		<!-- Tab 2 -->
                        <li <?php echo $Active1Cls; ?> style="display: none;"><a href="#settings" data-toggle="tab" onClick="setType(1)"><?php echo $MCListGroup; ?></a></li> 		<!-- Tab 1 -->
                    </ul>
                    <!-- Biodata -->
                    <div class="tab-content">
                        <form method="post" name="frmSearch" action="">
                            <input type="hidden" name="MC_REF2" id="MC_REF2" value="" size="50" />
                            <input type="hidden" name="PINV_MMC" id="PINV_MMC" value="0" size="1" />
                            <input type="hidden" name="MC_TOTVAL1" id="MC_TOTVAL1" value="0" size="10" />
                            <table id="example1" class="table table-bordered table-striped" width="100%">
                                <thead>
                                    <tr>
                                        <th width="2%" style="text-align:center; vertical-align:middle;">&nbsp;</th>
                                        <th width="8%" style="text-align:center; vertical-align:middle; display:none"><?php echo $MCCode ?></th>
                                        <th width="8%" style="text-align:center; vertical-align:middle;"><?php echo $MCNumber ?></th>
                                        <th width="8%" style="text-align:center; display:none"><?php echo $InvoiceNumber ?></th>
                                        <th width="5%" style="text-align:center; vertical-align:middle;"><?php echo $MCDate ?></th>
                                        <th width="11%" style="text-align:center; vertical-align:middle;"><?php echo $PrestationVal ?></th>
                                        <th width="11%" style="text-align:center; vertical-align:middle;" nowrap>Total<br>
                                        (inc. PPn)</th>
                                        <th width="9%" style="text-align:center; vertical-align:middle;" nowrap>PPh</th>
                                        <th width="38%" style="text-align:center; vertical-align:middle;"><?php echo $Notes ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        if($List_Type == 2)
                                        {
											$sqlSrc 	= "tbl_mcheader A
															WHERE A.PRJCODE = '$PRJCODE' AND MC_STAT = 3 AND MC_ISINV = 0
																AND MC_ISGROUP = 0
															ORDER BY A.MC_MANNO ASC";				
											$countMC 	= $this->db->count_all($sqlSrc);
											
											$sql 		= "SELECT A.*
															FROM tbl_mcheader A
															WHERE A.PRJCODE = '$PRJCODE' AND MC_STAT = 3 AND MC_ISINV = 0
																AND MC_ISGROUP = 0
															ORDER BY A.MC_MANNO ASC";
											$viewAllMC 	= $this->db->query($sql)->result();
                                            $currow = 0;			
                                            $idx 	= -1;
                                            if($countMC>0)
                                            {
                                                foreach($viewAllMC as $row) :
                                                    $pageFrom 		= "MC";					// 0
                                                    $MC_CODE 		= $row->MC_CODE;		// 1
                                                    $MC_MANNO 		= $row->MC_MANNO;		// 2
                                                    if($MC_MANNO == '')
                                                        $MC_MANNO	= $MC_CODE;
                                                    $MC_DATE 		= $row->MC_DATE;		// 3
                                                    $MC_ENDDATE 	= $row->MC_ENDDATE;		// 4
                                                    $MC_RETVAL 		= $row->MC_RETVAL;		// 5
                                                    $MC_PROGB       = $row->MC_PROGB;       // 6
                                                    $MC_PROG 		= $row->MC_PROG;		// 6
                                                    $MC_PROGVAL     = $row->MC_PROGVAL;     // 7
                                                    $MC_PROGCUR     = $row->MC_PROGCUR;     // 7
                                                    $MC_PROGCURVAL  = $row->MC_PROGCURVAL;  // 7
                                                    $MC_PROGAPP     = $row->MC_PROGAPP;     // 14
                                                    $MC_PROGAPPVAL  = $row->MC_PROGAPPVAL;  // 15
                                                    $MC_VALADD 		= $row->MC_VALADD;		// 8
                                                    $MC_MATVAL 		= $row->MC_MATVAL;		// 9
                                                    $MC_DPPER 		= $row->MC_DPPER;		// 10
                                                    $MC_DPVAL		= $row->MC_DPVAL;		// 11
                                                    $MC_DPBACK 		= $row->MC_DPBACK;		// 12
                                                    $MC_DPBACKCUR   = $row->MC_DPBACKCUR;   // 16
                                                    $MC_RETCUTP     = $row->MC_RETCUTP;      // 13
                                                    $MC_RETCUT      = $row->MC_RETCUT;      // 13
                                                    $MC_RETCUTCUR   = $row->MC_RETCUTCUR;   // 16
                                                    $MC_VALBEF 		= $row->MC_VALBEF;		// 17
                                                    $MC_TOTVAL 		= $row->MC_TOTVAL;
                                                    $MC_TOTVAL_PPn 	= $row->MC_TOTVAL_PPn;
                                                    $MC_TOTVAL_PPh	= $row->MC_TOTVAL_PPh;
                                                    //$GMC_TOTVAL     = $MC_TOTVAL+$MC_TOTVAL_PPn-$MC_TOTVAL_PPh; // 18
                                                    $GMC_TOTVAL     = $MC_TOTVAL;           // 18
                                                    $GMC_TOTVALnPPn = $MC_TOTVAL + $MC_TOTVAL_PPn;
                                                    $MC_NOTES 		= $row->MC_NOTES;		// 19
                                                    $MC_OWNER		= $row->MC_OWNER;
                                                    $MC_APPSTAT     = $row->MC_APPSTAT;
                                                    $MC_AKUMNEXT    = $row->MC_AKUMNEXT;
                                                    $currow			= $currow + 1;
                                                    $idx			= $idx + 1;

                                                        /*$MC_TOTPROGRESS	= $MC_PROGVAL + $MC_VALADD + $MC_MATVAL;
                                                        $MC_PAYBEFRET	= $MC_TOTPROGRESS + $MC_DPVAL - $MC_DPBACK - $MC_RETCUT;
                                                        $MC_PAYAKUM		= $MC_PAYBEFRET;
                                                        $MC_PAYMENT		= $MC_PAYAKUM - $MC_VALBEF;
                                                        $MC_PAYDUE		= $MC_PAYMENT + round(0.1 * $MC_PAYMENT);
                                                        $MC_PAYDUEPPh	= round(0.03 * $MC_PAYMENT);
                                                        $TOTPAYMENT		= $MC_PAYDUE - $MC_PAYDUEPPh;*/

                                                    $ownIns = 'S';
                                                    $sqlOWN = "SELECT own_Inst FROM tbl_owner WHERE own_Code = '$MC_OWNER'";
                                                    $resOWN = $this->db->query($sqlOWN)->result();
                                                    foreach($resOWN as $rowOWN) :
                                                        $ownIns     = $rowOWN ->own_Inst;
                                                    endforeach;
                                                    
                                                    // GET INVOICE NUMBER BY MC_CODE
                                                        $PINV_MANNO 	= "-";
                                                        if($MC_APPSTAT == 1)
                                                        {
                                                            $sqlGetINV 			= "SELECT PINV_CODE, PINV_MANNO FROM tbl_projinv_header 
                                                                                    WHERE PINV_SOURCE = '$MC_CODE'";
                                                            $resGetINV 			= $this->db->query($sqlGetINV)->result();
                                                            foreach($resGetINV as $rowGetINV) :
                                                                $PINV_CODE 		= $rowGetINV->PINV_CODE;
                                                                $PINV_MANNO 	= $rowGetINV->PINV_MANNO;
                                                            endforeach;
                                                        }	
                                                        else
                                                        {
                                                            $PINV_CODE 		= "-";
                                                            $PINV_MANNO 	= "-";
                                                        }	
                                                    
                                                    // GET LAST PAYMENT BEFOR
                                                        $sqlGetLPB 			= "SELECT PINV_AKUMNEXT FROM tbl_projinv_header 
                                                                                WHERE PRJCODE = '$PRJCODE'";
                                                        $resGetLPB 			= $this->db->query($sqlGetLPB)->result();
                                                        foreach($resGetLPB as $rowGetLPB) :
                                                            $PINV_AKUMNEXT 	= $rowGetLPB->PINV_AKUMNEXT;
                                                        endforeach;	
                                                        ?>
                                                        <tr>
                                                            <td style="text-align:center" nowrap><input type="radio" name="chk" id="chk" value="<?php echo $pageFrom;?>|<?php echo $MC_CODE;?>|<?php echo $MC_MANNO;?>|<?php echo $MC_DATE;?>|<?php echo $MC_ENDDATE;?>|<?php echo $MC_PROGB;?>|<?php echo $MC_PROG;?>|<?php echo $MC_PROGVAL;?>|<?php echo $MC_PROGCUR;?>|<?php echo $MC_PROGCURVAL;?>|<?php echo $MC_PROGAPP;?>|<?php echo $MC_PROGAPPVAL;?>|<?php echo $MC_VALADD;?>|<?php echo $MC_MATVAL;?>|<?php echo $MC_DPPER;?>|<?php echo $MC_DPVAL;?>|<?php echo $MC_DPBACK;?>|<?php echo $MC_DPBACKCUR;?>|<?php echo $MC_RETCUTP;?>|<?php echo $MC_RETCUT;?>|<?php echo $MC_RETCUTCUR;?>|<?php echo $MC_VALBEF;?>|<?php echo $MC_TOTVAL;?>|<?php echo $MC_TOTVAL_PPn;?>|<?php echo $MC_TOTVAL_PPh;?>|<?php echo $MC_NOTES;?>|<?php echo $MC_OWNER;?>|<?php echo $ownIns;?>|<?php echo $MC_AKUMNEXT;?>" onClick="pickThis(this, '<?php echo $currow; ?>');" <?php if($MC_APPSTAT == 1) { ?> disabled <?php } ?> /></td>
                                                            <td style="display:none" nowrap><?php echo "$MC_MANNO"; ?></td>
                                                            <td nowrap>
                                                                <?php echo "$MC_MANNO"; ?>
                                                                <input type="hidden" name="MC_CODE<?php echo $idx; ?>" id="MC_CODE<?php echo $idx; ?>" value="<?php echo "$MC_CODE"; ?>" />
                                                                <input type="hidden" name="MC_TOTVAL<?php echo $idx; ?>" id="MC_TOTVAL<?php echo $idx; ?>" value="<?php echo "$MC_TOTVAL"; ?>" />                                    </td>
                                                            <td style="display:none" nowrap><?php echo "$PINV_MANNO"; ?></td>
                                                            <td><?php echo $MC_DATE; ?></td>
                                                            <td style="text-align:right"><?php print number_format($MC_TOTVAL, $decFormat); ?></td>
                                                            <td style="text-align:right"><?php print number_format($GMC_TOTVALnPPn, $decFormat); ?></td>
                                                            <td style="text-align:right"><?php print number_format($MC_TOTVAL_PPh, $decFormat); ?></td>
                                                            <td><?php echo $MC_NOTES; ?></td>
                                                        </tr>
                                                    <?php
                                                endforeach;
                                            }
                                        }
                                        else
                                        {
											$sqlSrc 	= "tbl_mcg_header A
															WHERE A.PRJCODE = '$PRJCODE' AND MCH_STAT = 3 AND MCH_ISINV = 0
															ORDER BY A.MCH_MANNO ASC";				
											$countMC 	= $this->db->count_all($sqlSrc);
											
											$sql 		= "SELECT A.*
															FROM tbl_mcg_header A
															WHERE A.PRJCODE = '$PRJCODE' AND MCH_STAT = 3 AND MCH_ISINV = 0
															ORDER BY A.MCH_MANNO ASC";
											$viewAllMC 	= $this->db->query($sql)->result();
                                            $currow = 0;			
                                            $idx 	= -1;
                                            if($countMC>0)
                                            {
                                                foreach($viewAllMC as $row) :													
                                                    $pageFrom 		= "MCG";				// 0
                                                    $MCH_CODE 		= $row->MCH_CODE;		// 1
                                                    $MCH_MANNO 		= $row->MCH_MANNO;		// 2
                                                    if($MCH_MANNO == '')
                                                        $MCH_MANNO	= $MCH_CODE;
                                                    $MCH_DATE 		= $row->MCH_DATE;		// 3
                                                    $MCH_ENDDATE 	= $row->MCH_ENDDATE;	// 4
                                                    $MCH_RETVAL 	= $row->MCH_RETVAL;		// 5
                                                    $MCH_PROG 		= $row->MCH_PROG;		// 6
                                                    $MCH_PROGVAL	= $row->MCH_PROGVAL;	// 7
                                                    $MCH_PROGCUR	= $row->MCH_PROGCUR;	// 
                                                    $MCH_PROGCURVAL	= $row->MCH_PROGCURVAL;	// 
                                                    $MCH_VALADD 	= $row->MCH_VALADD;		// 8
                                                    $MCH_MATVAL 	= $row->MCH_MATVAL;		// 9
                                                    $MCH_DPPER 		= $row->MCH_DPPER;		// 10
                                                    $MCH_DPVAL		= $row->MCH_DPVAL;		// 11
                                                    $MCH_DPBACK 	= $row->MCH_DPBACK;		// 12
                                                    $MCH_DPBACKCUR 	= $row->MCH_DPBACKCUR;	// 
                                                    $MCH_RETCUTP 	= $row->MCH_RETCUTP;	// 
                                                    $MCH_RETCUT 	= $row->MCH_RETCUT;		// 13
                                                    $MCH_RETCUTCUR 	= $row->MCH_RETCUTCUR;	// 
                                                    $MCH_PROGAPP	= $row->MCH_PROGAPP;	// 14
                                                    $MCH_PROGAPPVAL	= $row->MCH_PROGAPPVAL;	// 15
                                                    $MCH_AKUMNEXT 	= $row->MCH_AKUMNEXT;	// 16
                                                    $MCH_VALBEF 	= $row->MCH_VALBEF;		// 17
                                                    $MCH_TOTVAL 	= $row->MCH_TOTVAL;		// Nilai Inc. PPn sebelum dikurangi PPh
                                                    $MCH_TOTVAL_PPn = $row->MCH_TOTVAL_PPn;
                                                    $MCH_TOTVAL_PPh	= $row->MCH_TOTVAL_PPh;
                                                   //$GMCH_TOTVAL	= $MCH_TOTVAL-$MCH_TOTVAL_PPn-$MCH_TOTVAL_PPh;	// 18
                                                    $GMCH_TOTVALnPPn= $MCH_TOTVAL + $MCH_TOTVAL_PPn;
                                                    $GMCH_TOTVAL	= $MCH_TOTVAL - $MCH_TOTVAL_PPh;	// 18
													
                                                    $MCH_NOTES 		= $row->MCH_NOTES;		// 19
                                                    $MCH_OWNER		= $row->MCH_OWNER;
                                                    $MCH_APPSTAT	= $row->MCH_APPSTAT;
                                                    $currow			= $currow + 1;
                                                    $idx			= $idx + 1;

                                                    $ownIns = 'S';
                                                    $sqlOWN = "SELECT own_Inst FROM tbl_owner WHERE own_Code = '$MCH_OWNER'";
                                                    $resOWN = $this->db->query($sqlOWN)->result();
                                                    foreach($resOWN as $rowOWN) :
                                                        $ownIns     = $rowOWN ->own_Inst;
                                                    endforeach;
                                                    
                                                    // GET INVOICE NUMBER BY MCH_CODE
                                                        $PINV_MANNO 	= "-";
                                                        if($MCH_APPSTAT == 1)
                                                        {
                                                            $sqlGetINV 			= "SELECT PINV_CODE, PINV_MANNO FROM tbl_projinv_header 
                                                                                    WHERE PINV_SOURCE = '$MCH_CODE'";
                                                            $resGetINV 			= $this->db->query($sqlGetINV)->result();
                                                            foreach($resGetINV as $rowGetINV) :
                                                                $PINV_CODE 		= $rowGetINV->PINV_CODE;
                                                                $PINV_MANNO 	= $rowGetINV->PINV_MANNO;
                                                            endforeach;
                                                        }	
                                                        else
                                                        {
                                                            $PINV_CODE 		= "-";
                                                            $PINV_MANNO 	= "-";
                                                        }
                                                        ?>
                                                        <tr>
                                                            <td style="text-align:center" nowrap><input type="radio" name="chk" id="chk" value="<?php echo $pageFrom;?>|<?php echo $MCH_CODE;?>|<?php echo $MCH_MANNO;?>|<?php echo $MCH_DATE;?>|<?php echo $MCH_ENDDATE;?>|<?php echo $MCH_RETVAL;?>|<?php echo $MCH_PROG;?>|<?php echo $MCH_PROGVAL;?>|<?php echo $MCH_VALADD;?>|<?php echo $MCH_MATVAL;?>|<?php echo $MCH_DPPER;?>|<?php echo $MCH_DPVAL;?>|<?php echo $MCH_DPBACK;?>|<?php echo $MCH_RETCUT;?>|<?php echo $MCH_PROGAPP;?>|<?php echo $MCH_PROGAPPVAL;?>|<?php echo $MCH_AKUMNEXT;?>|<?php echo $MCH_VALBEF;?>|<?php echo $GMCH_TOTVAL;?>|<?php echo $MCH_NOTES;?>|<?php echo $MCH_OWNER;?>|<?php echo $MCH_TOTVAL;?>|<?php echo $MCH_TOTVAL_PPn;?>|<?php echo $MCH_TOTVAL_PPh;?>|<?php echo $MCH_PROGCUR;?>|<?php echo $MCH_PROGCURVAL;?>|<?php echo $MCH_DPBACKCUR;?>|<?php echo $MCH_RETCUTP;?>|<?php echo $MCH_RETCUTCUR;?>|<?php echo $ownIns;?>" onClick="pickThis(this, '<?php echo $currow; ?>');" <?php if($MCH_APPSTAT == 1) { ?> disabled <?php } ?> /></td>
                                                            <td style="display:none" nowrap><?php echo "$MCH_MANNO"; ?></td>
                                                            <td nowrap>
                                                                <?php echo "$MCH_MANNO"; ?>
                                                                <input type="hidden" name="MC_CODE<?php echo $idx; ?>" id="MC_CODE<?php echo $idx; ?>" value="<?php echo "$MCH_CODE"; ?>" />
                                                                <input type="hidden" name="MC_TOTVAL<?php echo $idx; ?>" id="MC_TOTVAL<?php echo $idx; ?>" value="<?php echo "$MCH_TOTVAL"; ?>" />                                    </td>
                                                            <td style="display:none" nowrap><?php echo "$PINV_MANNO"; ?></td>
                                                            <td><?php echo $MCH_DATE; ?></td>
                                                            <td style="text-align:right"><?php print number_format($MCH_TOTVAL, $decFormat); ?></td>
                                                            <td style="text-align:right"><?php print number_format($GMCH_TOTVALnPPn, $decFormat); ?></td>
                                                            <td style="text-align:right"><?php print number_format($MCH_TOTVAL_PPh, $decFormat); ?></td>
                                                            <td><?php echo $MCH_NOTES; ?></td>
                                                        </tr>
                                                    <?php
                                                endforeach;
                                            }
                                        }
                                    ?>
                                </tbody>
                                <input type="hidden" name="PINV_AKUMNEXT" id="PINV_AKUMNEXT" value="<?php echo "$PINV_AKUMNEXT"; ?>" />
                                <tr>
                                    <td colspan="9" nowrap>
                                    <button class="btn btn-primary" type="button" onClick="get_item();">
                                    <i class="cus-check-green-16x16"></i>&nbsp;&nbsp;<?php echo $Select; ?>                    </button> 
                                    <button class="btn btn-danger" type="button" onClick="window.close()">
                                    <i class="cus-delete-16x16"></i>&nbsp;&nbsp;<?php echo $Close; ?>                    </button></td>
                                </tr>
                            </table>
                        </form>
                    </div>
            	</div>
		 	</div>
		</div>
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
</script>  
<script>	
	var selectedRows = 0
	function pickThis(thisobj, theRow) 
	{
		//var NumOfRows 	= document.getElementsByName('chk').length; // minus 1 because it's the header
		NumOfRows		= document.getElementsByName('chk').length;
		if (thisobj!= '') 
		{
			if (thisobj.checked) selectedRows++;
			else selectedRows--;
		}
		/*if (selectedRows==NumOfRows) 
		{
			document.getElementsByName('chk')AllItem.checked = true;
		}
		else
		{
			document.getElementsByName('chk')AllItem.checked = false;
		}*/
		MC_REFB		= '';
		isMMC		= 1;
		MC_TOTVAL1	= 0;
		/*for(i=0;i<=NumOfRows;i++) 
		{
			ischeck = document.getElementsByName('chk')[i].checked;
			if(document.getElementsByName('chk')[i].checked) 
			{
				MC_CODE		= document.getElementById('MC_CODE'+i).value;
				MC_TOTVAL	= document.getElementById('MC_TOTVAL'+i).value;
				if(i == 0)
				{
					swal('i = '+i)
					MC_REFB 	= MC_CODE;
					MC_TOTVAL1	= parseFloat(MC_TOTVAL1) + parseFloat(MC_TOTVAL);
				}
				else
				{
					swal('ib = '+i)
					if(MC_REFB == '')
						MC_REFB = MC_CODE;
					else
						MC_REFB 	= ''+MC_REFB+'|'+MC_CODE;
					MC_TOTVAL1	= parseFloat(MC_TOTVAL1) + parseFloat(MC_TOTVAL);
				}
			}			
		}*/
		
		for(i=0; i <=NumOfRows; i++)
		{
			ischeck = document.getElementsByName('chk')[i].checked;
			if(ischeck == true)
			{
				MC_CODE		= document.getElementById('MC_CODE'+i).value;
				MC_TOTVAL	= document.getElementById('MC_TOTVAL'+i).value;
				if(i == 0)
				{
					MC_REFB 	= MC_CODE;
					MC_TOTVAL1	= parseFloat(MC_TOTVAL1) + parseFloat(MC_TOTVAL);
				}
				else if(i > 1)
				{
					if(MC_REFB == '')
						MC_REFB = MC_CODE;
					else
						MC_REFB 	= ''+MC_REFB+'|'+MC_CODE;
					MC_TOTVAL1	= parseFloat(MC_TOTVAL1) + parseFloat(MC_TOTVAL);
				}
			}
			document.getElementById('MC_TOTVAL1').value	= MC_TOTVAL1;
			document.getElementById('PINV_MMC').value	= isMMC;
			document.getElementById('MC_REF2').value 	= MC_REFB;
		}
	}
	

	function get_item() 
	{ 
		// swal(document.getElementsByName('chk').length) 
		if(typeof(document.getElementsByName('chk')[0]) == 'object') 
		{
			for(i=0;i<document.getElementsByName('chk').length;i++) 
			{
				if(document.getElementsByName('chk')[i].checked) 
				{
					A 			= document.getElementsByName('chk')[i].value
					arrItem 	= A.split('|');
					arrparent 	= document.getElementsByName('chk')[i].value.split('|');
					PINV_MMC	= document.getElementById('PINV_MMC').value;
					//swal('a = '+PINV_MMC)
					MC_TOTVAL1	= document.getElementById('MC_TOTVAL1').value;
					MC_REF		= document.getElementById('MC_REF2').value;
					PINV_PAYBEF	= document.getElementById('PINV_AKUMNEXT').value;
					window.opener.getDetail(document.getElementsByName('chk')[i].value, PINV_MMC, MC_TOTVAL1, MC_REF, PINV_PAYBEF);				
				}
			}
		} 
		else 
		{
			if(document.getElementsByName('chk').checked)
			{
				PINV_MMC	= document.getElementById('PINV_MMC').value;
				MC_TOTVAL1	= document.getElementById('MC_TOTVAL1').value;
				MC_REF		= document.getElementById('MC_REF2').value;
				PINV_PAYBEF	= document.getElementById('PINV_AKUMNEXT').value;
				window.opener.getDetail(document.getElementsByName('chk').value, PINV_MMC, MC_TOTVAL1, MC_REF, PINV_PAYBEF);
				//swal('2' + '\n' + document.getElementsByName('chk').value)
				/*A = document.getElementsByName('chk').value
				arrItem = A.split('|');
				//swal(arrItem)
				for(z=1;z<=5;z++)
				{
					swal('1')
					B=eval("document.getElementsByName('chk')_"+arrItem[0]+"_"+z).value;
					//window.opener.add_item(B,'child');
					swal(B)
				}*/
			}
		}
		window.close();
	}
	
	function setType(thisValue)
	{
		if(thisValue == 1)
		{
			document.getElementById('List_Type').value = thisValue;
		}
		else
		{
			document.getElementById('List_Type').value = thisValue;
		}
		document.frm_01.submit1.click();
	}
</script>
<?php 
//$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
//$this->load->view('template/foot');
?>