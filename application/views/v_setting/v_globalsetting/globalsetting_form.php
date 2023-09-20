<?php
/* 
 * Author       = Dian Hermanto
 * Create Date  = 7 Februari 2017
 * File Name    = globalsetting_form.php
 * Location     = -
*/
?>
<?php
// _global function
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
    $Display_Rows = $row->Display_Rows;
    $decFormat = $row->decFormat;
endforeach;
$decFormat      = 2;
 
$this->load->view('template/head');

$appName    = $this->session->userdata('appName');
$appBody    = $this->session->userdata['appBody'];
$PRJSCATEG  = $this->session->userdata['PRJSCATEG'];

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

foreach($viewglobalsetting as $row) :
    $Display_Rows   = $row->Display_Rows;
    $decFormat      = $row->decFormat;
    $currency_ID    = $row->currency_ID;
    $purchasePrice  = $row->purchasePrice;
    $salesPrice     = $row->salesPrice; 
    $RateType_SO    = $row->RateType_SO;
    $RateType_PO    = $row->RateType_PO;
    $RateType_SN    = $row->RateType_SN;
    $RateType_RR    = $row->RateType_RR;
    $RateType_SI    = $row->RateType_SI;
    $RateType_VI    = $row->RateType_VI;
    $RateType_GL    = $row->RateType_GL; 
    $recountType    = $row->recountType;
    $isUpdOutApp    = $row->isUpdOutApp;
    $isUpdProfLoss  = $row->isUpdProfLoss;
    $ACC_ID_IR      = $row->ACC_ID_IR;
    $ACC_ID_UMSUB   = $row->ACC_ID_UMSUB;
    $ACC_ID_PROD    = $row->ACC_ID_PROD;
    $ACC_ID_SN      = $row->ACC_ID_SN;
    $ACC_ID_SPOT    = $row->ACC_ID_SPOT;    // SALES DISC
    $ACC_ID_SPPN    = $row->ACC_ID_SPPN;    // SALES PPN
    $ACC_ID_SPPH    = $row->ACC_ID_SPPH;    // SALES PPH
    $ACC_ID_RDP     = $row->ACC_ID_RDP;
    $ACC_ID_RET     = $row->ACC_ID_RET;
    $ACC_ID_OEXP    = $row->ACC_ID_OEXP;
    $ACC_ID_POT     = $row->ACC_ID_POT;
    $ACC_ID_MC      = $row->ACC_ID_MC;
    $ACC_ID_MCR     = $row->ACC_ID_MCR;
    $ACC_ID_MCP     = $row->ACC_ID_MCP;
    $ACC_ID_MCT     = $row->ACC_ID_MCT;
    $ACC_ID_MCRET   = $row->ACC_ID_MCRET;
    $ACC_ID_MCI     = $row->ACC_ID_MCI;
    $ACC_ID_MCPPn   = $row->ACC_ID_MCPPn;
    $ACC_ID_SALRET  = $row->ACC_ID_SALRET;
    $ACC_ID_IRRET   = $row->ACC_ID_IRRET;
    $ACC_ID_IRPPN   = $row->ACC_ID_IRPPN;
    $ACC_ID_WIPP    = $row->ACC_ID_WIPP;
    $ACC_ID_PERSL   = $row->ACC_ID_PERSL;
    $ACC_ID_EMPAP   = $row->ACC_ID_EMPAP;
    $RESET_JOURN    = $row->RESET_JOURN;
    $SET_DEPTPURCH  = $row->SET_DEPTPURCH;
    $APPLEV         = $row->APPLEV;
endforeach;
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $appName; ?></title>
        <!-- Tell the browser to be responsive to screen width -->
        <?php
          $vers     = $this->session->userdata['vers'];

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
        
        $ISREAD     = $this->session->userdata['ISREAD'];
        $ISCREATE   = $this->session->userdata['ISCREATE'];
        $ISAPPROVE  = $this->session->userdata['ISAPPROVE'];
        $ISDWONL    = $this->session->userdata['ISDWONL'];
        $LangID     = $this->session->userdata['LangID'];

        $sqlTransl      = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
        $resTransl      = $this->db->query($sqlTransl)->result();
        foreach($resTransl as $rowTransl) :
            $TranslCode = $rowTransl->MLANG_CODE;
            $LangTransl = $rowTransl->LangTransl;
            
            if($TranslCode == 'Description')$Description = $LangTransl;
            if($TranslCode == 'receivables')$receivables = $LangTransl;
            if($TranslCode == 'Payable')$Payable = $LangTransl;
            if($TranslCode == 'Tax')$Tax = $LangTransl;
            if($TranslCode == 'Income')$Income = $LangTransl;
            if($TranslCode == 'salRet')$salRet = $LangTransl;
            if($TranslCode == 'OthExp')$OthExp = $LangTransl;
            if($TranslCode == 'AppLev')$AppLev = $LangTransl;
            if($TranslCode == 'HeadOffice')$HeadOffice = $LangTransl;
            if($TranslCode == 'Project')$Project = $LangTransl;
            if($TranslCode == 'SentItem')$SentItem = $LangTransl;
            if($TranslCode == 'Production')$Production = $LangTransl;
            if($TranslCode == 'Sales')$Sales = $LangTransl;
            if($TranslCode == 'umTP')$umTP = $LangTransl;
            if($TranslCode == 'setPurchGrp')$setPurchGrp = $LangTransl;
            if($TranslCode == 'No')$No = $LangTransl;
            if($TranslCode == 'Yes')$Yes = $LangTransl;
            if($TranslCode == 'Purchase')$Purchase = $LangTransl;
        endforeach;
        
        if($LangID == 'IND')
        {
            $RecDP      = "Penerimaan DP";
            $RecItem    = "Penerimaan Item";
            $Disc       = "Potongan";
            $Retensi    = "Retensi";
            $Invoice    = "Faktur";
            $InvoiceDP  = "Faktur Proyek : DP";
            $InvoiceMC  = "Faktur Proyek : MC";
        }
        else
        {
            $RecDP      = "Receipt of Advances";
            $RecItem    = "Item Receipt";
            $Disc       = "Discount";
            $Retensi    = "Retention";
            $Invoice    = "Invoice";
            $InvoiceDP  = "Project Invoice : DP";
            $InvoiceMC  = "Project Invoice : MC";
        }

        $comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
        <section class="content-header">
            <h1>
                <?php echo $h1_title; ?>
                <small><?php echo $h2_title; ?></small>
              </h1>
              <?php /*?><ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="#">Tables</a></li>
                <li class="active">Data tables</li>
              </ol><?php */?>
        </section>

        <section class="content">
            <form class="form-horizontal" name="absen_form" method="post" action="<?php echo $form_action; ?>" onSubmit="return chekData()">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-success">
                            <div class="box-header with-border" style="display: none;">
                                <h3 class="box-title"><?php echo $h1_title; ?></h3>
                                <div class="box-tools pull-right">
                                    <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                                    <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                                </div>
                            </div>
                            <div class="box-body">
                                <div class="row">
                                    <div class="box-body chart-responsive">
                                        <?php // Start First ?>
                                            <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
                                            <input type="hidden" name="genSett" id="genSett" value="1" />
                                            <input type="hidden" name="invDP" id="invDP" value="0" />
                                            <input type="hidden" name="invMC" id="invMC" value="0" />
                                            <div class="form-group" style="display: none;">
                                                <label for="inputName" class="col-sm-2 control-label"> Display of Row</label>
                                                <div class="col-sm-10">
                                                    <label>
                                                        <input type="hidden" maxlength="5" name="Display_Rows" id="Display_Rows" class="form-control" style="max-width:60px" value="<?php echo $Display_Rows; ?>">  
                                                      <input type="text" maxlength="5" name="Display_Rows1" id="Display_Rows1" class="form-control" value="<?php echo $Display_Rows; ?>" disabled>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group" style="display: none;">
                                                <label for="inputName" class="col-sm-2 control-label">Decimal Range</label>
                                                <div class="col-sm-10">
                                                    <select name="decFormat" id="decFormat" class="form-control select2">
                                                        <?php 
                                                            for($idx=0;$idx<=4;$idx++)
                                                            {
                                                                ?>
                                                                    <option value="<?php echo $idx; ?>" <?php if($idx == $decFormat) { ?>selected<?php } ?>><?php echo $idx; ?></option>
                                                                <?php
                                                            }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group" style="display: none;">
                                                <label for="inputName" class="col-sm-2 control-label">Currency</label>
                                                <div class="col-sm-10">
                                                    <select name="currency_ID" id="currency_ID" class="form-control select2">
                                                        <?php 
                                                            foreach($viewCurrency as $row) : ?>
                                                                <option value="<?php echo $row->CURR_ID; ?>" <?php if($row->CURR_ID == $currency_ID) { ?>selected<?php } ?>><?php echo $row->CURR_ID;?></option>
                                                            <?php endforeach; ?>
                                                     </select>
                                                </div>
                                            </div>
                                            <?php
                                                $PRJCODE    = 'KTR';
                                                $sqlPL      = "SELECT PRJCODE FROM tbl_project WHERE isHO = 1 LIMIT 1";
                                                $resPL      = $this->db->query($sqlPL)->result();
                                                foreach($resPL as $rowPL1):
                                                    $PRJCODE    = $rowPL1->PRJCODE;
                                                endforeach;
                                                
                                                $sqlC0a     = "tbl_chartaccount WHERE Account_Category IN (1,2,4) AND PRJCODE = '$PRJCODE'";
                                                $resC0a     = $this->db->count_all($sqlC0a);
                                                
                                                $sqlC0b     = "SELECT DISTINCT Acc_ID, Account_Number, Account_Level, Account_NameEn, Account_NameId,
                                                                    Acc_ParentList, Acc_DirParent, isLast
                                                                FROM tbl_chartaccount WHERE Account_Category IN (1,2,4)
                                                                    AND PRJCODE = '$PRJCODE' ORDER BY ORD_ID ASC";
                                                $resC0b     = $this->db->query($sqlC0b)->result();
                                                
                                                $sqlC0c     = "tbl_chartaccount WHERE Account_Category IN (2,4,5,7,8) AND PRJCODE = '$PRJCODE'";
                                                $resC0c     = $this->db->count_all($sqlC0c);
                                                
                                                $sqlC0d     = "SELECT DISTINCT Acc_ID, Account_Number, Account_Level, Account_NameEn, Account_NameId,
                                                                    Acc_ParentList, Acc_DirParent, isLast
                                                                FROM tbl_chartaccount WHERE Account_Category IN (2,4,5,7,8)
                                                                    AND PRJCODE = '$PRJCODE' ORDER BY ORD_ID ASC";
                                                $resC0d     = $this->db->query($sqlC0d)->result(); 
                                            ?>
                                            <!-- START : PURCHASING -->
                                            <div class="form-group">
                                                <label for="inputName" class="col-sm-2 control-label" title="Mengatur penjurnalan penerimaan material (hutang belum terfaktur)."><?php echo $RecItem; ?></label>
                                                <div class="col-sm-10">
                                                    <select name="ACC_ID_IR" id="ACC_ID_IR" class="form-control select2">
                                                        <option value="" > --- </option>
                                                        <?php
                                                        if($resC0a>0)
                                                        {
                                                            foreach($resC0b as $rowC0b) :
                                                                $Acc_ID0        = $rowC0b->Acc_ID;
                                                                $Account_Number0= $rowC0b->Account_Number;
                                                                $Acc_DirParent0 = $rowC0b->Acc_DirParent;
                                                                $Account_Level0 = $rowC0b->Account_Level;
                                                                if($LangID == 'IND')
                                                                {
                                                                    $Account_Name0  = $rowC0b->Account_NameId;
                                                                }
                                                                else
                                                                {
                                                                    $Account_Name0  = $rowC0b->Account_NameEn;
                                                                }
                                                                
                                                                $Acc_ParentList0    = $rowC0b->Acc_ParentList;
                                                                $isLast_0           = $rowC0b->isLast;
                                                                $disbaled_0         = 0;
                                                                if($isLast_0 == 0)
                                                                    $disbaled_0     = 1;
                                                                    
                                                                if($Account_Level0 == 0)
                                                                    $level_coa1         = "";
                                                                elseif($Account_Level0 == 1)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;";
                                                                elseif($Account_Level0 == 2)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                elseif($Account_Level0 == 3)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                elseif($Account_Level0 == 4)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                elseif($Account_Level0 == 5)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                elseif($Account_Level0 == 6)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                elseif($Account_Level0 == 7)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                
                                                                $collData0  = "$Account_Number0";
                                                                ?>
                                                                    <option value="<?php echo $Account_Number0; ?>" <?php if($Account_Number0 == $ACC_ID_IR) { ?> selected <?php } if($disbaled_0 == 1) { ?> disabled style="font-weight:bold" <?php } ?>><?php echo "$level_coa1$Account_Name0 - $collData0"; ?></option>
                                                                <?php
                                                            endforeach;
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputName" class="col-sm-2 control-label" title="Mengatur Akun jika ada potongan Pembelian saat Penerimaan Material."><?php echo $Disc; ?> (IR)</label>
                                                <div class="col-sm-10">
                                                    <select name="ACC_ID_POT" id="ACC_ID_POT" class="form-control select2">
                                                        <option value="" > --- </option>
                                                        <?php
                                                        if($resC0c>0)
                                                        {
                                                            foreach($resC0d as $rowC0b) :
                                                                $Acc_ID0        = $rowC0b->Acc_ID;
                                                                $Account_Number0= $rowC0b->Account_Number;
                                                                $Acc_DirParent0 = $rowC0b->Acc_DirParent;
                                                                $Account_Level0 = $rowC0b->Account_Level;
                                                                if($LangID == 'IND')
                                                                {
                                                                    $Account_Name0  = $rowC0b->Account_NameId;
                                                                }
                                                                else
                                                                {
                                                                    $Account_Name0  = $rowC0b->Account_NameEn;
                                                                }
                                                                
                                                                $Acc_ParentList0    = $rowC0b->Acc_ParentList;
                                                                $isLast_0           = $rowC0b->isLast;
                                                                $disbaled_0         = 0;
                                                                if($isLast_0 == 0)
                                                                    $disbaled_0     = 1;
                                                                    
                                                                if($Account_Level0 == 0)
                                                                    $level_coa1         = "";
                                                                elseif($Account_Level0 == 1)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;";
                                                                elseif($Account_Level0 == 2)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                elseif($Account_Level0 == 3)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                elseif($Account_Level0 == 4)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                elseif($Account_Level0 == 5)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                elseif($Account_Level0 == 6)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                elseif($Account_Level0 == 7)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                
                                                                $collData0  = "$Account_Number0";
                                                                ?>
                                                                    <option value="<?php echo $Account_Number0; ?>" <?php if($Account_Number0 == $ACC_ID_POT) { ?> selected <?php } if($disbaled_0 == 1) { ?> disabled style="font-weight:bold" <?php } ?>><?php echo "$level_coa1$Account_Name0 - $collData0"; ?></option>
                                                                <?php
                                                            endforeach;
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="inputName" class="col-sm-2 control-label" title="Mengatur Akun jika ada penerimaan material dari return penjualan.">PPn (<?=$Purchase?>)</label>
                                                <div class="col-sm-10">
                                                    <select name="ACC_ID_IRPPN" id="ACC_ID_IRPPN" class="form-control select2">
                                                        <option value="" > --- </option>
                                                        <?php
                                                            if($resC0a>0)
                                                            {
                                                                foreach($resC0b as $rowA0b) :
                                                                    $Acc_ID0        = $rowA0b->Acc_ID;
                                                                    $Account_Number0= $rowA0b->Account_Number;
                                                                    $Acc_DirParent0 = $rowA0b->Acc_DirParent;
                                                                    $Account_Level0 = $rowA0b->Account_Level;
                                                                    if($LangID == 'IND')
                                                                    {
                                                                        $Account_Name0  = $rowA0b->Account_NameId;
                                                                    }
                                                                    else
                                                                    {
                                                                        $Account_Name0  = $rowA0b->Account_NameEn;
                                                                    }
                                                                    
                                                                    $Acc_ParentList0    = $rowA0b->Acc_ParentList;
                                                                    $isLast_0           = $rowA0b->isLast;
                                                                    $disbaled_0         = 0;
                                                                    if($isLast_0 == 0)
                                                                        $disbaled_0     = 1;
                                                                        
                                                                    if($Account_Level0 == 0)
                                                                        $level_coa1         = "";
                                                                    elseif($Account_Level0 == 1)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 2)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 3)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 4)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 5)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 6)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 7)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    
                                                                    //$collData0= "$Account_Number0~$Acc_ParentList0";
                                                                    $collData0  = "$Account_Number0";
                                                                    ?>
                                                                        <option value="<?php echo $Account_Number0; ?>" <?php if($Account_Number0 == $ACC_ID_IRPPN) { ?> selected <?php } if($disbaled_0 == 1) { ?> disabled <?php } ?>><?php echo "$level_coa1$Account_Name0 - $collData0"; ?></option>
                                                                    <?php
                                                                endforeach;
                                                            }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="inputName" class="col-sm-2 control-label" title="Mengatur Akun jika ada penerimaan material dari return penjualan.">IR Retur</label>
                                                <div class="col-sm-10">
                                                    <select name="ACC_ID_IRRET" id="ACC_ID_IRRET" class="form-control select2">
                                                        <option value="" > --- </option>
                                                        <?php
                                                        if($resC0a>0)
                                                        {
                                                            foreach($resC0b as $rowA0b) :
                                                                $Acc_ID0        = $rowA0b->Acc_ID;
                                                                $Account_Number0= $rowA0b->Account_Number;
                                                                $Acc_DirParent0 = $rowA0b->Acc_DirParent;
                                                                $Account_Level0 = $rowA0b->Account_Level;
                                                                if($LangID == 'IND')
                                                                {
                                                                    $Account_Name0  = $rowA0b->Account_NameId;
                                                                }
                                                                else
                                                                {
                                                                    $Account_Name0  = $rowA0b->Account_NameEn;
                                                                }
                                                                
                                                                $Acc_ParentList0    = $rowA0b->Acc_ParentList;
                                                                $isLast_0           = $rowA0b->isLast;
                                                                $disbaled_0         = 0;
                                                                if($isLast_0 == 0)
                                                                    $disbaled_0     = 1;
                                                                    
                                                                if($Account_Level0 == 0)
                                                                    $level_coa1         = "";
                                                                elseif($Account_Level0 == 1)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;";
                                                                elseif($Account_Level0 == 2)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                elseif($Account_Level0 == 3)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                elseif($Account_Level0 == 4)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                elseif($Account_Level0 == 5)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                elseif($Account_Level0 == 6)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                elseif($Account_Level0 == 7)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                
                                                                //$collData0= "$Account_Number0~$Acc_ParentList0";
                                                                $collData0  = "$Account_Number0";
                                                                ?>
                                                                    <option value="<?php echo $Account_Number0; ?>" <?php if($Account_Number0 == $ACC_ID_IRRET) { ?> selected <?php } if($disbaled_0 == 1) { ?> disabled <?php } ?>><?php echo "$level_coa1$Account_Name0 - $collData0"; ?></option>
                                                                <?php
                                                            endforeach;
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- START : SALES -->
                                            <?php if($PRJSCATEG == 2 || $PRJSCATEG == 3) { ?>
                                                <div class="form-group">
                                                    <label for="inputName" class="col-sm-2 control-label" title="Mengatur akun jika ada potongan penjualan."><?php echo "$Disc ($Sales)"; ?></label>
                                                    <div class="col-sm-10">
                                                        <select name="ACC_ID_SPOT" id="ACC_ID_SPOT" class="form-control select2">
                                                            <option value="" > --- </option>
                                                            <?php
                                                            if($resC0a>0)
                                                            {
                                                                foreach($resC0b as $rowC0b) :
                                                                    $Acc_ID0        = $rowC0b->Acc_ID;
                                                                    $Account_Number0= $rowC0b->Account_Number;
                                                                    $Acc_DirParent0 = $rowC0b->Acc_DirParent;
                                                                    $Account_Level0 = $rowC0b->Account_Level;
                                                                    if($LangID == 'IND')
                                                                    {
                                                                        $Account_Name0  = $rowC0b->Account_NameId;
                                                                    }
                                                                    else
                                                                    {
                                                                        $Account_Name0  = $rowC0b->Account_NameEn;
                                                                    }
                                                                    
                                                                    $Acc_ParentList0    = $rowC0b->Acc_ParentList;
                                                                    $isLast_0           = $rowC0b->isLast;
                                                                    $disbaled_0         = 0;
                                                                    if($isLast_0 == 0)
                                                                        $disbaled_0     = 1;
                                                                        
                                                                    if($Account_Level0 == 0)
                                                                        $level_coa1         = "";
                                                                    elseif($Account_Level0 == 1)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 2)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 3)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 4)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 5)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 6)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 7)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    
                                                                    $collData0  = "$Account_Number0";
                                                                    ?>
                                                                        <option value="<?php echo $Account_Number0; ?>" <?php if($Account_Number0 == $ACC_ID_SPOT) { ?> selected <?php } if($disbaled_0 == 1) { ?> disabled style="font-weight:bold" <?php } ?>><?php echo "$level_coa1$Account_Name0 - $collData0"; ?></option>
                                                                    <?php
                                                                endforeach;
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="inputName" class="col-sm-2 control-label"><?php echo "PPn ($Sales)"; ?></label>
                                                    <div class="col-sm-10">
                                                        <select name="ACC_ID_SPPN" id="ACC_ID_SPPN" class="form-control select2">
                                                            <option value="" > --- </option>
                                                            <?php
                                                            if($resC0a>0)
                                                            {
                                                                foreach($resC0b as $rowC0b) :
                                                                    $Acc_ID0        = $rowC0b->Acc_ID;
                                                                    $Account_Number0= $rowC0b->Account_Number;
                                                                    $Acc_DirParent0 = $rowC0b->Acc_DirParent;
                                                                    $Account_Level0 = $rowC0b->Account_Level;
                                                                    if($LangID == 'IND')
                                                                    {
                                                                        $Account_Name0  = $rowC0b->Account_NameId;
                                                                    }
                                                                    else
                                                                    {
                                                                        $Account_Name0  = $rowC0b->Account_NameEn;
                                                                    }
                                                                    
                                                                    $Acc_ParentList0    = $rowC0b->Acc_ParentList;
                                                                    $isLast_0           = $rowC0b->isLast;
                                                                    $disbaled_0         = 0;
                                                                    if($isLast_0 == 0)
                                                                        $disbaled_0     = 1;
                                                                        
                                                                    if($Account_Level0 == 0)
                                                                        $level_coa1         = "";
                                                                    elseif($Account_Level0 == 1)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 2)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 3)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 4)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 5)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 6)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 7)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    
                                                                    $collData0  = "$Account_Number0";
                                                                    ?>
                                                                        <option value="<?php echo $Account_Number0; ?>" <?php if($Account_Number0 == $ACC_ID_SPPN) { ?> selected <?php } if($disbaled_0 == 1) { ?> disabled style="font-weight:bold" <?php } ?>><?php echo "$level_coa1$Account_Name0 - $collData0"; ?></option>
                                                                    <?php
                                                                endforeach;
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="inputName" class="col-sm-2 control-label" title="Mengatur Akun jika ada PPH Penjualan."><?php echo "PPh ($Sales)"; ?></label>
                                                    <div class="col-sm-10">
                                                        <select name="ACC_ID_SPPH" id="ACC_ID_SPPH" class="form-control select2">
                                                            <option value="" > --- </option>
                                                            <?php
                                                            if($resC0a>0)
                                                            {
                                                                foreach($resC0b as $rowC0b) :
                                                                    $Acc_ID0        = $rowC0b->Acc_ID;
                                                                    $Account_Number0= $rowC0b->Account_Number;
                                                                    $Acc_DirParent0 = $rowC0b->Acc_DirParent;
                                                                    $Account_Level0 = $rowC0b->Account_Level;
                                                                    if($LangID == 'IND')
                                                                    {
                                                                        $Account_Name0  = $rowC0b->Account_NameId;
                                                                    }
                                                                    else
                                                                    {
                                                                        $Account_Name0  = $rowC0b->Account_NameEn;
                                                                    }
                                                                    
                                                                    $Acc_ParentList0    = $rowC0b->Acc_ParentList;
                                                                    $isLast_0           = $rowC0b->isLast;
                                                                    $disbaled_0         = 0;
                                                                    if($isLast_0 == 0)
                                                                        $disbaled_0     = 1;
                                                                        
                                                                    if($Account_Level0 == 0)
                                                                        $level_coa1         = "";
                                                                    elseif($Account_Level0 == 1)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 2)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 3)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 4)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 5)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 6)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 7)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    
                                                                    $collData0  = "$Account_Number0";
                                                                    ?>
                                                                        <option value="<?php echo $Account_Number0; ?>" <?php if($Account_Number0 == $ACC_ID_SPPH) { ?> selected <?php } if($disbaled_0 == 1) { ?> disabled style="font-weight:bold" <?php } ?>><?php echo "$level_coa1$Account_Name0 - $collData0"; ?></option>
                                                                    <?php
                                                                endforeach;
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="inputName" class="col-sm-2 control-label" title="Mengatur akun piutang belum terfaktur."><?php echo $SentItem; ?></label>
                                                    <div class="col-sm-10">
                                                        <select name="ACC_ID_SN" id="ACC_ID_SN" class="form-control select2">
                                                            <option value="" > --- </option>
                                                            <?php
                                                            if($resC0a>0)
                                                            {
                                                                foreach($resC0b as $rowC0b) :
                                                                    $Acc_ID0        = $rowC0b->Acc_ID;
                                                                    $Account_Number0= $rowC0b->Account_Number;
                                                                    $Acc_DirParent0 = $rowC0b->Acc_DirParent;
                                                                    $Account_Level0 = $rowC0b->Account_Level;
                                                                    if($LangID == 'IND')
                                                                    {
                                                                        $Account_Name0  = $rowC0b->Account_NameId;
                                                                    }
                                                                    else
                                                                    {
                                                                        $Account_Name0  = $rowC0b->Account_NameEn;
                                                                    }
                                                                    
                                                                    $Acc_ParentList0    = $rowC0b->Acc_ParentList;
                                                                    $isLast_0           = $rowC0b->isLast;
                                                                    $disbaled_0         = 0;
                                                                    if($isLast_0 == 0)
                                                                        $disbaled_0     = 1;
                                                                        
                                                                    if($Account_Level0 == 0)
                                                                        $level_coa1         = "";
                                                                    elseif($Account_Level0 == 1)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 2)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 3)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 4)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 5)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 6)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 7)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    
                                                                    $collData0  = "$Account_Number0";
                                                                    ?>
                                                                        <option value="<?php echo $Account_Number0; ?>" <?php if($Account_Number0 == $ACC_ID_SN) { ?> selected <?php } if($disbaled_0 == 1) { ?> disabled style="font-weight:bold" <?php } ?>><?php echo "$level_coa1$Account_Name0 - $collData0"; ?></option>
                                                                    <?php
                                                                endforeach;
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="inputName" class="col-sm-2 control-label" title="Mengatur Akun retur penjualan, lawan dari Penerimaan Persediaan."><?php echo $salRet; ?></label>
                                                    <div class="col-sm-10">
                                                        <select name="ACC_ID_SALRET" id="ACC_ID_SALRET" class="form-control select2">
                                                            <option value="" > --- </option>
                                                            <?php
                                                            if($resC0a>0)
                                                            {
                                                                foreach($resC0b as $rowA0b) :
                                                                    $Acc_ID0        = $rowA0b->Acc_ID;
                                                                    $Account_Number0= $rowA0b->Account_Number;
                                                                    $Acc_DirParent0 = $rowA0b->Acc_DirParent;
                                                                    $Account_Level0 = $rowA0b->Account_Level;
                                                                    if($LangID == 'IND')
                                                                    {
                                                                        $Account_Name0  = $rowA0b->Account_NameId;
                                                                    }
                                                                    else
                                                                    {
                                                                        $Account_Name0  = $rowA0b->Account_NameEn;
                                                                    }
                                                                    
                                                                    $Acc_ParentList0    = $rowA0b->Acc_ParentList;
                                                                    $isLast_0           = $rowA0b->isLast;
                                                                    $disbaled_0         = 0;
                                                                    if($isLast_0 == 0)
                                                                        $disbaled_0     = 1;
                                                                        
                                                                    if($Account_Level0 == 0)
                                                                        $level_coa1         = "";
                                                                    elseif($Account_Level0 == 1)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 2)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 3)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 4)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 5)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 6)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 7)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    
                                                                    //$collData0= "$Account_Number0~$Acc_ParentList0";
                                                                    $collData0  = "$Account_Number0";
                                                                    ?>
                                                                        <option value="<?php echo $Account_Number0; ?>" <?php if($Account_Number0 == $ACC_ID_SALRET) { ?> selected <?php } if($disbaled_0 == 1) { ?> disabled <?php } ?>><?php echo "$level_coa1$Account_Name0 - $collData0"; ?></option>
                                                                    <?php
                                                                endforeach;
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="inputName" class="col-sm-2 control-label" title="Mengatur Akun penjurnalan proses masukan dan keluaran produksi WIP."><?php echo $Production; ?> WIP</label>
                                                    <div class="col-sm-10">
                                                        <select name="ACC_ID_WIPP" id="ACC_ID_WIPP" class="form-control select2">
                                                            <option value="" > --- </option>
                                                            <?php
                                                            if($resC0a>0)
                                                            {
                                                                foreach($resC0b as $rowC0b) :
                                                                    $Acc_ID0        = $rowC0b->Acc_ID;
                                                                    $Account_Number0= $rowC0b->Account_Number;
                                                                    $Acc_DirParent0 = $rowC0b->Acc_DirParent;
                                                                    $Account_Level0 = $rowC0b->Account_Level;
                                                                    if($LangID == 'IND')
                                                                    {
                                                                        $Account_Name0  = $rowC0b->Account_NameId;
                                                                    }
                                                                    else
                                                                    {
                                                                        $Account_Name0  = $rowC0b->Account_NameEn;
                                                                    }
                                                                    
                                                                    $Acc_ParentList0    = $rowC0b->Acc_ParentList;
                                                                    $isLast_0           = $rowC0b->isLast;
                                                                    $disbaled_0         = 0;
                                                                    if($isLast_0 == 0)
                                                                        $disbaled_0     = 1;
                                                                        
                                                                    if($Account_Level0 == 0)
                                                                        $level_coa1         = "";
                                                                    elseif($Account_Level0 == 1)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 2)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 3)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 4)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 5)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 6)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 7)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    
                                                                    $collData0  = "$Account_Number0";
                                                                    ?>
                                                                        <option value="<?php echo $Account_Number0; ?>" <?php if($Account_Number0 == $ACC_ID_WIPP) { ?> selected <?php } if($disbaled_0 == 1) { ?> disabled style="font-weight:bold" <?php } ?>><?php echo "$level_coa1$Account_Name0 - $collData0"; ?></option>
                                                                    <?php
                                                                endforeach;
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="inputName" class="col-sm-2 control-label" title="Mengatur Akun penjurnalan proses terakhir produksi (menambahkan nilai FG hasil produksi)."><?php echo $Production; ?></label>
                                                    <div class="col-sm-10">
                                                        <select name="ACC_ID_PROD" id="ACC_ID_PROD" class="form-control select2">
                                                            <option value="" > --- </option>
                                                            <?php
                                                            if($resC0a>0)
                                                            {
                                                                foreach($resC0b as $rowC0b) :
                                                                    $Acc_ID0        = $rowC0b->Acc_ID;
                                                                    $Account_Number0= $rowC0b->Account_Number;
                                                                    $Acc_DirParent0 = $rowC0b->Acc_DirParent;
                                                                    $Account_Level0 = $rowC0b->Account_Level;
                                                                    if($LangID == 'IND')
                                                                    {
                                                                        $Account_Name0  = $rowC0b->Account_NameId;
                                                                    }
                                                                    else
                                                                    {
                                                                        $Account_Name0  = $rowC0b->Account_NameEn;
                                                                    }
                                                                    
                                                                    $Acc_ParentList0    = $rowC0b->Acc_ParentList;
                                                                    $isLast_0           = $rowC0b->isLast;
                                                                    $disbaled_0         = 0;
                                                                    if($isLast_0 == 0)
                                                                        $disbaled_0     = 1;
                                                                        
                                                                    if($Account_Level0 == 0)
                                                                        $level_coa1         = "";
                                                                    elseif($Account_Level0 == 1)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 2)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 3)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 4)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 5)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 6)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 7)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    
                                                                    $collData0  = "$Account_Number0";
                                                                    ?>
                                                                        <option value="<?php echo $Account_Number0; ?>" <?php if($Account_Number0 == $ACC_ID_PROD) { ?> selected <?php } if($disbaled_0 == 1) { ?> disabled style="font-weight:bold" <?php } ?>><?php echo "$level_coa1$Account_Name0 - $collData0"; ?></option>
                                                                    <?php
                                                                endforeach;
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            <?php } ?>

                                            <div class="form-group">
                                                <label for="inputName" class="col-sm-2 control-label" title="Mengatur Akun penjurnalan proses terakhir produksi (menambahkan nilai FG hasil produksi)."><?php echo $umTP; ?></label>
                                                <div class="col-sm-10">
                                                    <select name="ACC_ID_UMSUB" id="ACC_ID_UMSUB" class="form-control select2">
                                                        <option value="" > --- </option>
                                                        <?php
                                                        if($resC0a>0)
                                                        {
                                                            foreach($resC0b as $rowC0b) :
                                                                $Acc_ID0        = $rowC0b->Acc_ID;
                                                                $Account_Number0= $rowC0b->Account_Number;
                                                                $Acc_DirParent0 = $rowC0b->Acc_DirParent;
                                                                $Account_Level0 = $rowC0b->Account_Level;
                                                                if($LangID == 'IND')
                                                                {
                                                                    $Account_Name0  = $rowC0b->Account_NameId;
                                                                }
                                                                else
                                                                {
                                                                    $Account_Name0  = $rowC0b->Account_NameEn;
                                                                }
                                                                
                                                                $Acc_ParentList0    = $rowC0b->Acc_ParentList;
                                                                $isLast_0           = $rowC0b->isLast;
                                                                $disbaled_0         = 0;
                                                                if($isLast_0 == 0)
                                                                    $disbaled_0     = 1;
                                                                    
                                                                if($Account_Level0 == 0)
                                                                    $level_coa1         = "";
                                                                elseif($Account_Level0 == 1)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;";
                                                                elseif($Account_Level0 == 2)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                elseif($Account_Level0 == 3)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                elseif($Account_Level0 == 4)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                elseif($Account_Level0 == 5)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                elseif($Account_Level0 == 6)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                elseif($Account_Level0 == 7)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                
                                                                $collData0  = "$Account_Number0";
                                                                ?>
                                                                    <option value="<?php echo $Account_Number0; ?>" <?php if($Account_Number0 == $ACC_ID_UMSUB) { ?> selected <?php } if($disbaled_0 == 1) { ?> disabled style="font-weight:bold" <?php } ?>><?php echo "$level_coa1$Account_Name0 - $collData0"; ?></option>
                                                                <?php
                                                            endforeach;
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputName" class="col-sm-2 control-label"><?php echo $OthExp; ?></label>
                                                <div class="col-sm-10">
                                                    <select name="ACC_ID_OEXP" id="ACC_ID_OEXP" class="form-control select2">
                                                        <option value="" > --- </option>
                                                        <?php
                                                        if($resC0c>0)
                                                        {
                                                            foreach($resC0d as $rowC0d) :
                                                                $Acc_ID0        = $rowC0d->Acc_ID;
                                                                $Account_Number0= $rowC0d->Account_Number;
                                                                $Acc_DirParent0 = $rowC0d->Acc_DirParent;
                                                                $Account_Level0 = $rowC0d->Account_Level;
                                                                if($LangID == 'IND')
                                                                {
                                                                    $Account_Name0  = $rowC0d->Account_NameId;
                                                                }
                                                                else
                                                                {
                                                                    $Account_Name0  = $rowC0d->Account_NameEn;
                                                                }
                                                                
                                                                $Acc_ParentList0    = $rowC0d->Acc_ParentList;
                                                                $isLast_0           = $rowC0d->isLast;
                                                                $disbaled_0         = 0;
                                                                if($isLast_0 == 0)
                                                                    $disbaled_0     = 1;
                                                                    
                                                                if($Account_Level0 == 0)
                                                                    $level_coa1         = "";
                                                                elseif($Account_Level0 == 1)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;";
                                                                elseif($Account_Level0 == 2)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                elseif($Account_Level0 == 3)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                elseif($Account_Level0 == 4)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                elseif($Account_Level0 == 5)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                elseif($Account_Level0 == 6)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                elseif($Account_Level0 == 7)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                
                                                                $collData0  = "$Account_Number0";
                                                                ?>
                                                                    <option value="<?php echo $Account_Number0; ?>" <?php if($Account_Number0 == $ACC_ID_OEXP) { ?> selected <?php } if($disbaled_0 == 1) { ?> disabled style="font-weight:bold" <?php } ?>><?php echo "$level_coa1$Account_Name0 - $collData0"; ?></option>
                                                                <?php
                                                            endforeach;
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <?php
                                            if($PRJSCATEG == 1) {
                                            ?>
                                                <div class="form-group">
                                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Retensi; ?></label>
                                                    <div class="col-sm-10">
                                                        <select name="ACC_ID_RET" id="ACC_ID_RET" class="form-control select2">
                                                            <option value="" > --- </option>
                                                            <?php
                                                            if($resC0a>0)
                                                            {
                                                                foreach($resC0b as $rowC0b) :
                                                                    $Acc_ID0        = $rowC0b->Acc_ID;
                                                                    $Account_Number0= $rowC0b->Account_Number;
                                                                    $Acc_DirParent0 = $rowC0b->Acc_DirParent;
                                                                    $Account_Level0 = $rowC0b->Account_Level;
                                                                    if($LangID == 'IND')
                                                                    {
                                                                        $Account_Name0  = $rowC0b->Account_NameId;
                                                                    }
                                                                    else
                                                                    {
                                                                        $Account_Name0  = $rowC0b->Account_NameEn;
                                                                    }
                                                                    
                                                                    $Acc_ParentList0    = $rowC0b->Acc_ParentList;
                                                                    $isLast_0           = $rowC0b->isLast;
                                                                    $disbaled_0         = 0;
                                                                    if($isLast_0 == 0)
                                                                        $disbaled_0     = 1;
                                                                        
                                                                    if($Account_Level0 == 0)
                                                                        $level_coa1         = "";
                                                                    elseif($Account_Level0 == 1)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 2)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 3)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 4)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 5)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 6)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 7)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    
                                                                    $collData0  = "$Account_Number0";
                                                                    ?>
                                                                        <option value="<?php echo $Account_Number0; ?>" <?php if($Account_Number0 == $ACC_ID_RET) { ?> selected <?php } if($disbaled_0 == 1) { ?> disabled style="font-weight:bold" <?php } ?>><?php echo "$level_coa1$Account_Name0 - $collData0"; ?></option>
                                                                    <?php
                                                                endforeach;
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="inputName" class="col-sm-2 control-label">Pengemb. UM</label>
                                                    <div class="col-sm-10">
                                                        <select name="ACC_ID_RDP" id="ACC_ID_RDP" class="form-control select2">
                                                            <option value="" > --- </option>
                                                            <?php
                                                            if($resC0a>0)
                                                            {
                                                                foreach($resC0b as $rowC0b) :
                                                                    $Acc_ID0        = $rowC0b->Acc_ID;
                                                                    $Account_Number0= $rowC0b->Account_Number;
                                                                    $Acc_DirParent0 = $rowC0b->Acc_DirParent;
                                                                    $Account_Level0 = $rowC0b->Account_Level;
                                                                    if($LangID == 'IND')
                                                                    {
                                                                        $Account_Name0  = $rowC0b->Account_NameId;
                                                                    }
                                                                    else
                                                                    {
                                                                        $Account_Name0  = $rowC0b->Account_NameEn;
                                                                    }
                                                                    
                                                                    $Acc_ParentList0    = $rowC0b->Acc_ParentList;
                                                                    $isLast_0           = $rowC0b->isLast;
                                                                    $disbaled_0         = 0;
                                                                    if($isLast_0 == 0)
                                                                        $disbaled_0     = 1;
                                                                        
                                                                    if($Account_Level0 == 0)
                                                                        $level_coa1         = "";
                                                                    elseif($Account_Level0 == 1)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 2)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 3)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 4)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 5)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 6)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 7)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    
                                                                    $collData0  = "$Account_Number0";
                                                                    ?>
                                                                        <option value="<?php echo $Account_Number0; ?>" <?php if($Account_Number0 == $ACC_ID_RDP) { ?> selected <?php } if($disbaled_0 == 1) { ?> disabled style="font-weight:bold" <?php } ?>><?php echo "$level_coa1$Account_Name0 - $collData0"; ?></option>
                                                                    <?php
                                                                endforeach;
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="inputName" class="col-sm-2 control-label">Pinjaman Dinas (PD)</label>
                                                    <div class="col-sm-10">
                                                        <select name="ACC_ID_PERSL" id="ACC_ID_PERSL" class="form-control select2">
                                                            <option value="" > --- </option>
                                                            <?php
                                                            if($resC0a>0)
                                                            {
                                                                foreach($resC0b as $rowC0b) :
                                                                    $Acc_ID0        = $rowC0b->Acc_ID;
                                                                    $Account_Number0= $rowC0b->Account_Number;
                                                                    $Acc_DirParent0 = $rowC0b->Acc_DirParent;
                                                                    $Account_Level0 = $rowC0b->Account_Level;
                                                                    if($LangID == 'IND')
                                                                    {
                                                                        $Account_Name0  = $rowC0b->Account_NameId;
                                                                    }
                                                                    else
                                                                    {
                                                                        $Account_Name0  = $rowC0b->Account_NameEn;
                                                                    }
                                                                    
                                                                    $Acc_ParentList0    = $rowC0b->Acc_ParentList;
                                                                    $isLast_0           = $rowC0b->isLast;
                                                                    $disbaled_0         = 0;
                                                                    if($isLast_0 == 0)
                                                                        $disbaled_0     = 1;
                                                                        
                                                                    if($Account_Level0 == 0)
                                                                        $level_coa1         = "";
                                                                    elseif($Account_Level0 == 1)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 2)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 3)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 4)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 5)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 6)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 7)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    
                                                                    $collData0  = "$Account_Number0";
                                                                    ?>
                                                                        <option value="<?php echo $Account_Number0; ?>" <?php if($Account_Number0 == $ACC_ID_PERSL) { ?> selected <?php } if($disbaled_0 == 1) { ?> disabled style="font-weight:bold" <?php } ?>><?php echo "$level_coa1$Account_Name0 - $collData0"; ?></option>
                                                                    <?php
                                                                endforeach;
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="inputName" class="col-sm-2 control-label">Hutang Karyawan</label>
                                                    <div class="col-sm-10">
                                                        <select name="ACC_ID_EMPAP" id="ACC_ID_EMPAP" class="form-control select2">
                                                            <option value="" > --- </option>
                                                            <?php
                                                            if($resC0a>0)
                                                            {
                                                                foreach($resC0b as $rowC0b) :
                                                                    $Acc_ID0        = $rowC0b->Acc_ID;
                                                                    $Account_Number0= $rowC0b->Account_Number;
                                                                    $Acc_DirParent0 = $rowC0b->Acc_DirParent;
                                                                    $Account_Level0 = $rowC0b->Account_Level;
                                                                    if($LangID == 'IND')
                                                                    {
                                                                        $Account_Name0  = $rowC0b->Account_NameId;
                                                                    }
                                                                    else
                                                                    {
                                                                        $Account_Name0  = $rowC0b->Account_NameEn;
                                                                    }
                                                                    
                                                                    $Acc_ParentList0    = $rowC0b->Acc_ParentList;
                                                                    $isLast_0           = $rowC0b->isLast;
                                                                    $disbaled_0         = 0;
                                                                    if($isLast_0 == 0)
                                                                        $disbaled_0     = 1;
                                                                        
                                                                    if($Account_Level0 == 0)
                                                                        $level_coa1         = "";
                                                                    elseif($Account_Level0 == 1)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 2)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 3)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 4)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 5)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 6)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    elseif($Account_Level0 == 7)
                                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                    
                                                                    $collData0  = "$Account_Number0";
                                                                    ?>
                                                                        <option value="<?php echo $Account_Number0; ?>" <?php if($Account_Number0 == $ACC_ID_EMPAP) { ?> selected <?php } if($disbaled_0 == 1) { ?> disabled style="font-weight:bold" <?php } ?>><?php echo "$level_coa1$Account_Name0 - $collData0"; ?></option>
                                                                    <?php
                                                                endforeach;
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            <?php
                                            }
                                            ?>
                                        <?php // End First ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if($PRJSCATEG == 1) { ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box box-warning">
                                <div class="box-header with-border">
                                    <h3 class="box-title"><?php echo $InvoiceMC; ?></h3>
                                    <div class="box-tools pull-right">
                                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                                        <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                                <div class="box-body">
                                    <div class="row">
                                        <div class="box-body chart-responsive">
                                            <!-- <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
                                            <input type="hidden" name="genSett" id="genSett" value="0" />
                                            <input type="hidden" name="invDP" id="invDP" value="0" /> -->
                                            <input type="hidden" name="invMC" id="invMC" value="1" />
                                            <div class="form-group">
                                                <label for="inputName" class="col-sm-2 control-label"><?php echo $receivables; ?></label>
                                                <div class="col-sm-10">
                                                    <select name="ACC_ID_MCR" id="ACC_ID_MCR" class="form-control select2">
                                                        <option value="" > --- </option>
                                                        <?php
                                                        if($resC0a>0)
                                                        {
                                                            foreach($resC0b as $rowC0b) :
                                                                $Acc_ID0        = $rowC0b->Acc_ID;
                                                                $Account_Number0= $rowC0b->Account_Number;
                                                                $Acc_DirParent0 = $rowC0b->Acc_DirParent;
                                                                $Account_Level0 = $rowC0b->Account_Level;
                                                                if($LangID == 'IND')
                                                                {
                                                                    $Account_Name0  = $rowC0b->Account_NameId;
                                                                }
                                                                else
                                                                {
                                                                    $Account_Name0  = $rowC0b->Account_NameEn;
                                                                }
                                                                
                                                                $Acc_ParentList0    = $rowC0b->Acc_ParentList;
                                                                $isLast_0           = $rowC0b->isLast;
                                                                $disbaled_0         = 0;
                                                                if($isLast_0 == 0)
                                                                    $disbaled_0     = 1;
                                                                    
                                                                if($Account_Level0 == 0)
                                                                    $level_coa1         = "";
                                                                elseif($Account_Level0 == 1)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;";
                                                                elseif($Account_Level0 == 2)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                elseif($Account_Level0 == 3)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                elseif($Account_Level0 == 4)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                elseif($Account_Level0 == 5)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                elseif($Account_Level0 == 6)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                elseif($Account_Level0 == 7)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                
                                                                $collData0  = "$Account_Number0";
                                                                ?>
                                                                    <option value="<?php echo $Account_Number0; ?>" <?php if($Account_Number0 == $ACC_ID_MCR) { ?> selected <?php } if($disbaled_0 == 1) { ?> disabled style="font-weight:bold" <?php } ?>><?php echo "$level_coa1$Account_Name0 - $collData0"; ?></option>
                                                                <?php
                                                            endforeach;
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputName" class="col-sm-2 control-label"><?php echo $Payable; ?></label>
                                                <div class="col-sm-10">
                                                    <select name="ACC_ID_MCP" id="ACC_ID_MCP" class="form-control select2">
                                                        <option value="" > --- </option>
                                                        <?php
                                                        if($resC0a>0)
                                                        {
                                                            foreach($resC0b as $rowA0b) :
                                                                $Acc_ID0        = $rowA0b->Acc_ID;
                                                                $Account_Number0= $rowA0b->Account_Number;
                                                                $Acc_DirParent0 = $rowA0b->Acc_DirParent;
                                                                $Account_Level0 = $rowA0b->Account_Level;
                                                                if($LangID == 'IND')
                                                                {
                                                                    $Account_Name0  = $rowA0b->Account_NameId;
                                                                }
                                                                else
                                                                {
                                                                    $Account_Name0  = $rowA0b->Account_NameEn;
                                                                }
                                                                
                                                                $Acc_ParentList0    = $rowA0b->Acc_ParentList;
                                                                $isLast_0           = $rowA0b->isLast;
                                                                $disbaled_0         = 0;
                                                                if($isLast_0 == 0)
                                                                    $disbaled_0     = 1;
                                                                    
                                                                if($Account_Level0 == 0)
                                                                    $level_coa1         = "";
                                                                elseif($Account_Level0 == 1)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;";
                                                                elseif($Account_Level0 == 2)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                elseif($Account_Level0 == 3)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                elseif($Account_Level0 == 4)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                elseif($Account_Level0 == 5)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                elseif($Account_Level0 == 6)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                elseif($Account_Level0 == 7)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                
                                                                //$collData0= "$Account_Number0~$Acc_ParentList0";
                                                                $collData0  = "$Account_Number0";
                                                                ?>
                                                                    <option value="<?php echo $Account_Number0; ?>" <?php if($Account_Number0 == $ACC_ID_MCP) { ?> selected <?php } if($disbaled_0 == 1) { ?> disabled <?php } ?>><?php echo "$level_coa1$Account_Name0 - $collData0"; ?></option>
                                                                <?php
                                                            endforeach;
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputName" class="col-sm-2 control-label"><?php echo $Tax; ?></label>
                                                <div class="col-sm-10">
                                                    <select name="ACC_ID_MCPPn" id="ACC_ID_MCPPn" class="form-control select2">
                                                        <option value="" > --- </option>
                                                        <?php
                                                        if($resC0a>0)
                                                        {
                                                            foreach($resC0b as $rowA0b) :
                                                                $Acc_ID0        = $rowA0b->Acc_ID;
                                                                $Account_Number0= $rowA0b->Account_Number;
                                                                $Acc_DirParent0 = $rowA0b->Acc_DirParent;
                                                                $Account_Level0 = $rowA0b->Account_Level;
                                                                if($LangID == 'IND')
                                                                {
                                                                    $Account_Name0  = $rowA0b->Account_NameId;
                                                                }
                                                                else
                                                                {
                                                                    $Account_Name0  = $rowA0b->Account_NameEn;
                                                                }
                                                                
                                                                $Acc_ParentList0    = $rowA0b->Acc_ParentList;
                                                                $isLast_0           = $rowA0b->isLast;
                                                                $disbaled_0         = 0;
                                                                if($isLast_0 == 0)
                                                                    $disbaled_0     = 1;
                                                                    
                                                                if($Account_Level0 == 0)
                                                                    $level_coa1         = "";
                                                                elseif($Account_Level0 == 1)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;";
                                                                elseif($Account_Level0 == 2)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                elseif($Account_Level0 == 3)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                elseif($Account_Level0 == 4)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                elseif($Account_Level0 == 5)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                elseif($Account_Level0 == 6)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                elseif($Account_Level0 == 7)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                
                                                                //$collData0= "$Account_Number0~$Acc_ParentList0";
                                                                $collData0  = "$Account_Number0";
                                                                ?>
                                                                    <option value="<?php echo $Account_Number0; ?>" <?php if($Account_Number0 == $ACC_ID_MCPPn) { ?> selected <?php } if($disbaled_0 == 1) { ?> disabled <?php } ?>><?php echo "$level_coa1$Account_Name0 - $collData0"; ?></option>
                                                                <?php
                                                            endforeach;
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputName" class="col-sm-2 control-label">PPh</label>
                                                <div class="col-sm-10">
                                                    <select name="ACC_ID_MCT" id="ACC_ID_MCT" class="form-control select2">
                                                        <option value="" > --- </option>
                                                        <?php
                                                        if($resC0a>0)
                                                        {
                                                            foreach($resC0b as $rowA0b) :
                                                                $Acc_ID0        = $rowA0b->Acc_ID;
                                                                $Account_Number0= $rowA0b->Account_Number;
                                                                $Acc_DirParent0 = $rowA0b->Acc_DirParent;
                                                                $Account_Level0 = $rowA0b->Account_Level;
                                                                if($LangID == 'IND')
                                                                {
                                                                    $Account_Name0  = $rowA0b->Account_NameId;
                                                                }
                                                                else
                                                                {
                                                                    $Account_Name0  = $rowA0b->Account_NameEn;
                                                                }
                                                                
                                                                $Acc_ParentList0    = $rowA0b->Acc_ParentList;
                                                                $isLast_0           = $rowA0b->isLast;
                                                                $disbaled_0         = 0;
                                                                if($isLast_0 == 0)
                                                                    $disbaled_0     = 1;
                                                                    
                                                                if($Account_Level0 == 0)
                                                                    $level_coa1         = "";
                                                                elseif($Account_Level0 == 1)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;";
                                                                elseif($Account_Level0 == 2)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                elseif($Account_Level0 == 3)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                elseif($Account_Level0 == 4)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                elseif($Account_Level0 == 5)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                elseif($Account_Level0 == 6)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                elseif($Account_Level0 == 7)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                
                                                                //$collData0= "$Account_Number0~$Acc_ParentList0";
                                                                $collData0  = "$Account_Number0";
                                                                ?>
                                                                    <option value="<?php echo $Account_Number0; ?>" <?php if($Account_Number0 == $ACC_ID_MCT) { ?> selected <?php } if($disbaled_0 == 1) { ?> disabled <?php } ?>><?php echo "$level_coa1$Account_Name0 - $collData0"; ?></option>
                                                                <?php
                                                            endforeach;
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputName" class="col-sm-2 control-label">Retensi</label>
                                                <div class="col-sm-10">
                                                    <select name="ACC_ID_MCRET" id="ACC_ID_MCRET" class="form-control select2">
                                                        <option value="" > --- </option>
                                                        <?php
                                                        if($resC0a>0)
                                                        {
                                                            foreach($resC0b as $rowA0b) :
                                                                $Acc_ID0        = $rowA0b->Acc_ID;
                                                                $Account_Number0= $rowA0b->Account_Number;
                                                                $Acc_DirParent0 = $rowA0b->Acc_DirParent;
                                                                $Account_Level0 = $rowA0b->Account_Level;
                                                                if($LangID == 'IND')
                                                                {
                                                                    $Account_Name0  = $rowA0b->Account_NameId;
                                                                }
                                                                else
                                                                {
                                                                    $Account_Name0  = $rowA0b->Account_NameEn;
                                                                }
                                                                
                                                                $Acc_ParentList0    = $rowA0b->Acc_ParentList;
                                                                $isLast_0           = $rowA0b->isLast;
                                                                $disbaled_0         = 0;
                                                                if($isLast_0 == 0)
                                                                    $disbaled_0     = 1;
                                                                    
                                                                if($Account_Level0 == 0)
                                                                    $level_coa1         = "";
                                                                elseif($Account_Level0 == 1)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;";
                                                                elseif($Account_Level0 == 2)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                elseif($Account_Level0 == 3)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                elseif($Account_Level0 == 4)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                elseif($Account_Level0 == 5)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                elseif($Account_Level0 == 6)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                elseif($Account_Level0 == 7)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                
                                                                //$collData0= "$Account_Number0~$Acc_ParentList0";
                                                                $collData0  = "$Account_Number0";
                                                                ?>
                                                                    <option value="<?php echo $Account_Number0; ?>" <?php if($Account_Number0 == $ACC_ID_MCRET) { ?> selected <?php } if($disbaled_0 == 1) { ?> disabled <?php } ?>><?php echo "$level_coa1$Account_Name0 - $collData0"; ?></option>
                                                                <?php
                                                            endforeach;
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputName" class="col-sm-2 control-label"><?php echo $Income; ?></label>
                                                <div class="col-sm-10">
                                                    <select name="ACC_ID_MCI" id="ACC_ID_MCI" class="form-control select2">
                                                        <option value="" > --- </option>
                                                        <?php
                                                        if($resC0a>0)
                                                        {
                                                            foreach($resC0b as $rowA0b) :
                                                                $Acc_ID0        = $rowA0b->Acc_ID;
                                                                $Account_Number0= $rowA0b->Account_Number;
                                                                $Acc_DirParent0 = $rowA0b->Acc_DirParent;
                                                                $Account_Level0 = $rowA0b->Account_Level;
                                                                if($LangID == 'IND')
                                                                {
                                                                    $Account_Name0  = $rowA0b->Account_NameId;
                                                                }
                                                                else
                                                                {
                                                                    $Account_Name0  = $rowA0b->Account_NameEn;
                                                                }
                                                                
                                                                $Acc_ParentList0    = $rowA0b->Acc_ParentList;
                                                                $isLast_0           = $rowA0b->isLast;
                                                                $disbaled_0         = 0;
                                                                if($isLast_0 == 0)
                                                                    $disbaled_0     = 1;
                                                                    
                                                                if($Account_Level0 == 0)
                                                                    $level_coa1         = "";
                                                                elseif($Account_Level0 == 1)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;";
                                                                elseif($Account_Level0 == 2)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                elseif($Account_Level0 == 3)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                elseif($Account_Level0 == 4)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                elseif($Account_Level0 == 5)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                elseif($Account_Level0 == 6)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                elseif($Account_Level0 == 7)
                                                                    $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                
                                                                //$collData0= "$Account_Number0~$Acc_ParentList0";
                                                                $collData0  = "$Account_Number0";
                                                                ?>
                                                                    <option value="<?php echo $Account_Number0; ?>" <?php if($Account_Number0 == $ACC_ID_MCI) { ?> selected <?php } if($disbaled_0 == 1) { ?> disabled <?php } ?>><?php echo "$level_coa1$Account_Name0 - $collData0"; ?></option>
                                                                <?php
                                                            endforeach;
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>

                <!-- START : GENERAL -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box box-success">
                                <div class="box-header with-border" style="display: none;">
                                    <h3 class="box-title"><?php echo $h1_title; ?></h3>
                                    <div class="box-tools pull-right">
                                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                                        <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                                <div class="box-body">
                                    <div class="row">
                                        <div class="box-body chart-responsive">
                                            <?php
                                                $Emp_ID1    = '';
                                                $Emp_ID2    = '';
                                                $sqlMJREMP  = "SELECT * FROM tbl_major_app";
                                                $resMJREMP  = $this->db->query($sqlMJREMP)->result();
                                                foreach($resMJREMP as $rowMJR) :
                                                    $Emp_ID1    = $rowMJR->Emp_ID1;
                                                    $Emp_ID2    = $rowMJR->Emp_ID2;
                                                endforeach;
                                            ?>
                                            <div class="form-group">
                                                <label for="inputName" class="col-sm-2 control-label"><?php echo $AppLev; ?></label>
                                                <div class="col-sm-10">
                                                    <select name="APPLEV" id="APPLEV" class="form-control select2">
                                                        <option value="HO" <?php if($APPLEV == 'HO') { ?> selected <?php } ?>> <?php echo $HeadOffice; ?> </option>
                                                        <option value="PRJ" <?php if($APPLEV == 'PRJ') { ?> selected <?php } ?> > <?php echo $Project; ?> </option>
                                                        <!-- <option value="PRD" <?php if($APPLEV == 'PRD') { ?> selected <?php } ?> > Periode </option> -->
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputName" class="col-sm-2 control-label"><?php echo $setPurchGrp; ?></label>
                                                <div class="col-sm-10">
                                                    <select name="SET_DEPTPURCH" id="SET_DEPTPURCH" class="form-control select2">
                                                        <option value="0" <?php if($SET_DEPTPURCH == 0) { ?> selected <?php } ?>> <?php echo $No; ?> </option>
                                                        <option value="1" <?php if($SET_DEPTPURCH == 1) { ?> selected <?php } ?> > <?php echo $Yes; ?> </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputName" class="col-sm-2 control-label">Reset Journal</label>
                                                <div class="col-sm-10">
                                                    <select name="RESET_JOURN" id="RESET_JOURN" class="form-control select2">
                                                        <option value="0" <?php if($RESET_JOURN == 0) { ?> selected <?php } ?>> No </option>
                                                        <option value="1" <?php if($RESET_JOURN == 1) { ?> selected <?php } ?> > Yes </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group" style="display: none;">
                                                <label for="inputName" class="col-sm-2 control-label">Penyetuju Khusus</label>
                                                <div class="col-sm-10">
                                                    <select name="MJR_APP[]" id="MJR_APP" class="form-control select2" multiple="multiple" data-placeholder="&nbsp;&nbsp;&nbsp;Project Manager">
                                                        <?php
                                                            /*$sqlEmp   = "SELECT Emp_ID, First_Name, Last_Name, Email
                                                                        FROM tbl_employee WHERE Pos_Code LIKE 'PM%' ORDER BY First_Name";*/
                                                            $sqlEmp = "SELECT Emp_ID, First_Name, Last_Name, Email
                                                                        FROM tbl_employee ORDER BY First_Name";
                                                            $sqlEmp = $this->db->query($sqlEmp)->result();
                                                            foreach($sqlEmp as $row) :
                                                                $Emp_ID     = $row->Emp_ID;
                                                                $First_Name = $row->First_Name;
                                                                $Last_Name  = $row->Last_Name;
                                                                $Email      = $row->Email;
                                                                ?>
                                                                    <option value="<?php echo "$Emp_ID"; ?>" <?php if($Emp_ID1 == $Emp_ID) { ?> selected <?php } ?>>
                                                                        <?php echo "$First_Name $Last_Name"; ?>
                                                                    </option>
                                                                <?php
                                                            endforeach;
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group" style="display:none">
                                                <label for="inputName" class="col-sm-2 control-label">Purchase Price</label>
                                                <div class="col-sm-10">
                                                    <input name="purchasePrice" type="radio" value="0" <?php if($purchasePrice == 0) { ?> checked <?php } ?>> Fixed
                                                    <input name="purchasePrice" type="radio" value="1" <?php if($purchasePrice == 1) { ?> checked <?php } ?>> Editable 
                                                </div>
                                            </div>
                                            <div class="form-group" style="display:none">
                                                <label for="inputName" class="col-sm-2 control-label">Sales Price</label>
                                                <div class="col-sm-10">
                                                    <input name="salesPrice" type="radio" value="0" <?php if($salesPrice == 0) { ?> checked <?php } ?>> Fixed
                                                    <input name="salesPrice" type="radio" value="1" <?php if($salesPrice == 1) { ?> checked <?php } ?>> Editable
                                                </div>
                                            </div>
                                            <div class="form-group" style="display:none">
                                                <label for="inputName" class="col-sm-2 control-label">Rate Sales</label>
                                                <div class="col-sm-10">
                                                    <input name="RateType_SO" type="radio" value="0" <?php if($RateType_SO == 0) { ?> checked <?php } ?>> Fixed
                                                    <input name="RateType_SO" type="radio" value="1" <?php if($RateType_SO == 1) { ?> checked <?php } ?>> Editable
                                                </div>
                                            </div>
                                            <div class="form-group" style="display:none">
                                                <label for="inputName" class="col-sm-2 control-label">Rate Purchase Invoice</label>
                                                <div class="col-sm-10">
                                                    <input name="RateType_VI" type="radio" value="0" <?php if($RateType_VI == 0) { ?> checked <?php } ?>> Fixed 
                                                    <input name="RateType_VI" type="radio" value="1" <?php if($RateType_VI == 1) { ?> checked <?php } ?>> Editable
                                                </div>
                                            </div>
                                            <div class="form-group" style="display:none">
                                                <label for="inputName" class="col-sm-2 control-label">Rate Sales Invoice</label>
                                                <div class="col-sm-10">
                                                    <input name="RateType_SI" type="radio" value="0" <?php if($RateType_SI == 0) { ?> checked <?php } ?>> Fixed
                                                    <input name="RateType_SI" type="radio" value="1" <?php if($RateType_SI == 1) { ?> checked <?php } ?>> Editable
                                                </div>
                                            </div>
                                            <div class="form-group" style="display:none">
                                                <label for="inputName" class="col-sm-2 control-label">Rate Journal</label>
                                                <div class="col-sm-10">
                                                    <input name="RateType_GL" type="radio" value="0" <?php if($RateType_GL == 0) { ?> checked <?php } ?>> Fixed
                                                    <input name="RateType_GL" type="radio" value="1" <?php if($RateType_GL == 1) { ?> checked <?php } ?>> Editable
                                                </div>
                                            </div>
                                            <div class="form-group" style="display:none">
                                                <label for="inputName" class="col-sm-2 control-label">Recount Type</label>
                                                <div class="col-sm-10">
                                                    <select name="recountType" id="recountType" class="form-control" style="max-width:80px">
                                                        <option value="AVG" <?php if($recountType == "AVG") { ?> selected <?php } ?>>AVG</option>
                                                        <option value="FIFO" <?php if($recountType == "FIFO") { ?> selected <?php } ?> >FIFO</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group" style="display:none">
                                                <label for="inputName" class="col-sm-2 control-label">Upd. Outst. Approv</label>
                                                <div class="col-sm-10">
                                                    <input name="isUpdOutApp" type="radio" value="1" <?php if($isUpdOutApp == 1) { ?> checked <?php } ?>>Yes
                                                    <input name="isUpdOutApp" type="radio" value="0" <?php if($isUpdOutApp == 0) { ?> checked <?php } ?>>No
                                                </div>
                                            </div>
                                            <div class="form-group" style="display:none">
                                                <label for="inputName" class="col-sm-2 control-label">Upd. L/R Report</label>
                                                <div class="col-sm-10">
                                                    <input name="isUpdProfLoss" type="radio" value="1" <?php if($isUpdProfLoss == 1) { ?> checked <?php } ?>>Yes
                                                    <input name="isUpdProfLoss" type="radio" value="0" <?php if($isUpdProfLoss == 0) { ?> checked <?php } ?>>No
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-offset-2 col-sm-10">
                                                    <button class="btn btn-primary" ><i class="fa fa-save"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <!-- END : GENERAL -->
            </form>
            <div class="row" style="display: none;">
                <div class="col-md-12">
                    <div class="box box-warning">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $InvoiceMC; ?></h3>
                            <div class="box-tools pull-right">
                                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="box-body chart-responsive">
                                    <form class="form-horizontal" name="absen_form" method="post" action="<?php echo $form_action; ?>" onSubmit="return chekData()">
                                        <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
                                        <!-- <input type="hidden" name="genSett" id="genSett" value="0" />
                                        <input type="hidden" name="invDP" id="invDP" value="0" />
                                        <input type="hidden" name="invMC" id="invMC" value="1" /> -->
                                        <div class="form-group">
                                            <label for="inputName" class="col-sm-2 control-label"><?php echo $receivables; ?></label>
                                            <div class="col-sm-10">
                                                <select name="ACC_ID_MCR" id="ACC_ID_MCR" class="form-control select2">
                                                    <option value="" > --- </option>
                                                    <?php
                                                    if($resC0a>0)
                                                    {
                                                        foreach($resC0b as $rowC0b) :
                                                            $Acc_ID0        = $rowC0b->Acc_ID;
                                                            $Account_Number0= $rowC0b->Account_Number;
                                                            $Acc_DirParent0 = $rowC0b->Acc_DirParent;
                                                            $Account_Level0 = $rowC0b->Account_Level;
                                                            if($LangID == 'IND')
                                                            {
                                                                $Account_Name0  = $rowC0b->Account_NameId;
                                                            }
                                                            else
                                                            {
                                                                $Account_Name0  = $rowC0b->Account_NameEn;
                                                            }
                                                            
                                                            $Acc_ParentList0    = $rowC0b->Acc_ParentList;
                                                            $isLast_0           = $rowC0b->isLast;
                                                            $disbaled_0         = 0;
                                                            if($isLast_0 == 0)
                                                                $disbaled_0     = 1;
                                                                
                                                            if($Account_Level0 == 0)
                                                                $level_coa1         = "";
                                                            elseif($Account_Level0 == 1)
                                                                $level_coa1         = "&nbsp;&nbsp;&nbsp;";
                                                            elseif($Account_Level0 == 2)
                                                                $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                            elseif($Account_Level0 == 3)
                                                                $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                            elseif($Account_Level0 == 4)
                                                                $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                            elseif($Account_Level0 == 5)
                                                                $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                            elseif($Account_Level0 == 6)
                                                                $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                            elseif($Account_Level0 == 7)
                                                                $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                            
                                                            $collData0  = "$Account_Number0";
                                                            ?>
                                                                <option value="<?php echo $Account_Number0; ?>" <?php if($Account_Number0 == $ACC_ID_MCR) { ?> selected <?php } if($disbaled_0 == 1) { ?> disabled style="font-weight:bold" <?php } ?>><?php echo "$level_coa1$Account_Name0 - $collData0"; ?></option>
                                                            <?php
                                                        endforeach;
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputName" class="col-sm-2 control-label"><?php echo $Payable; ?></label>
                                            <div class="col-sm-10">
                                                <select name="ACC_ID_MCP" id="ACC_ID_MCP" class="form-control select2">
                                                    <option value="" > --- </option>
                                                    <?php
                                                    if($resC0a>0)
                                                    {
                                                        foreach($resC0b as $rowA0b) :
                                                            $Acc_ID0        = $rowA0b->Acc_ID;
                                                            $Account_Number0= $rowA0b->Account_Number;
                                                            $Acc_DirParent0 = $rowA0b->Acc_DirParent;
                                                            $Account_Level0 = $rowA0b->Account_Level;
                                                            if($LangID == 'IND')
                                                            {
                                                                $Account_Name0  = $rowA0b->Account_NameId;
                                                            }
                                                            else
                                                            {
                                                                $Account_Name0  = $rowA0b->Account_NameEn;
                                                            }
                                                            
                                                            $Acc_ParentList0    = $rowA0b->Acc_ParentList;
                                                            $isLast_0           = $rowA0b->isLast;
                                                            $disbaled_0         = 0;
                                                            if($isLast_0 == 0)
                                                                $disbaled_0     = 1;
                                                                
                                                            if($Account_Level0 == 0)
                                                                $level_coa1         = "";
                                                            elseif($Account_Level0 == 1)
                                                                $level_coa1         = "&nbsp;&nbsp;&nbsp;";
                                                            elseif($Account_Level0 == 2)
                                                                $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                            elseif($Account_Level0 == 3)
                                                                $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                            elseif($Account_Level0 == 4)
                                                                $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                            elseif($Account_Level0 == 5)
                                                                $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                            elseif($Account_Level0 == 6)
                                                                $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                            elseif($Account_Level0 == 7)
                                                                $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                            
                                                            //$collData0= "$Account_Number0~$Acc_ParentList0";
                                                            $collData0  = "$Account_Number0";
                                                            ?>
                                                                <option value="<?php echo $Account_Number0; ?>" <?php if($Account_Number0 == $ACC_ID_MCP) { ?> selected <?php } if($disbaled_0 == 1) { ?> disabled <?php } ?>><?php echo "$level_coa1$Account_Name0 - $collData0"; ?></option>
                                                            <?php
                                                        endforeach;
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputName" class="col-sm-2 control-label"><?php echo $Tax; ?></label>
                                            <div class="col-sm-10">
                                                <select name="ACC_ID_MCPPn" id="ACC_ID_MCPPn" class="form-control select2">
                                                    <option value="" > --- </option>
                                                    <?php
                                                    if($resC0a>0)
                                                    {
                                                        foreach($resC0b as $rowA0b) :
                                                            $Acc_ID0        = $rowA0b->Acc_ID;
                                                            $Account_Number0= $rowA0b->Account_Number;
                                                            $Acc_DirParent0 = $rowA0b->Acc_DirParent;
                                                            $Account_Level0 = $rowA0b->Account_Level;
                                                            if($LangID == 'IND')
                                                            {
                                                                $Account_Name0  = $rowA0b->Account_NameId;
                                                            }
                                                            else
                                                            {
                                                                $Account_Name0  = $rowA0b->Account_NameEn;
                                                            }
                                                            
                                                            $Acc_ParentList0    = $rowA0b->Acc_ParentList;
                                                            $isLast_0           = $rowA0b->isLast;
                                                            $disbaled_0         = 0;
                                                            if($isLast_0 == 0)
                                                                $disbaled_0     = 1;
                                                                
                                                            if($Account_Level0 == 0)
                                                                $level_coa1         = "";
                                                            elseif($Account_Level0 == 1)
                                                                $level_coa1         = "&nbsp;&nbsp;&nbsp;";
                                                            elseif($Account_Level0 == 2)
                                                                $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                            elseif($Account_Level0 == 3)
                                                                $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                            elseif($Account_Level0 == 4)
                                                                $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                            elseif($Account_Level0 == 5)
                                                                $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                            elseif($Account_Level0 == 6)
                                                                $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                            elseif($Account_Level0 == 7)
                                                                $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                            
                                                            //$collData0= "$Account_Number0~$Acc_ParentList0";
                                                            $collData0  = "$Account_Number0";
                                                            ?>
                                                                <option value="<?php echo $Account_Number0; ?>" <?php if($Account_Number0 == $ACC_ID_MCPPn) { ?> selected <?php } if($disbaled_0 == 1) { ?> disabled <?php } ?>><?php echo "$level_coa1$Account_Name0 - $collData0"; ?></option>
                                                            <?php
                                                        endforeach;
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputName" class="col-sm-2 control-label">PPh</label>
                                            <div class="col-sm-10">
                                                <select name="ACC_ID_MCT" id="ACC_ID_MCT" class="form-control select2">
                                                    <option value="" > --- </option>
                                                    <?php
                                                    if($resC0a>0)
                                                    {
                                                        foreach($resC0b as $rowA0b) :
                                                            $Acc_ID0        = $rowA0b->Acc_ID;
                                                            $Account_Number0= $rowA0b->Account_Number;
                                                            $Acc_DirParent0 = $rowA0b->Acc_DirParent;
                                                            $Account_Level0 = $rowA0b->Account_Level;
                                                            if($LangID == 'IND')
                                                            {
                                                                $Account_Name0  = $rowA0b->Account_NameId;
                                                            }
                                                            else
                                                            {
                                                                $Account_Name0  = $rowA0b->Account_NameEn;
                                                            }
                                                            
                                                            $Acc_ParentList0    = $rowA0b->Acc_ParentList;
                                                            $isLast_0           = $rowA0b->isLast;
                                                            $disbaled_0         = 0;
                                                            if($isLast_0 == 0)
                                                                $disbaled_0     = 1;
                                                                
                                                            if($Account_Level0 == 0)
                                                                $level_coa1         = "";
                                                            elseif($Account_Level0 == 1)
                                                                $level_coa1         = "&nbsp;&nbsp;&nbsp;";
                                                            elseif($Account_Level0 == 2)
                                                                $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                            elseif($Account_Level0 == 3)
                                                                $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                            elseif($Account_Level0 == 4)
                                                                $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                            elseif($Account_Level0 == 5)
                                                                $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                            elseif($Account_Level0 == 6)
                                                                $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                            elseif($Account_Level0 == 7)
                                                                $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                            
                                                            //$collData0= "$Account_Number0~$Acc_ParentList0";
                                                            $collData0  = "$Account_Number0";
                                                            ?>
                                                                <option value="<?php echo $Account_Number0; ?>" <?php if($Account_Number0 == $ACC_ID_MCT) { ?> selected <?php } if($disbaled_0 == 1) { ?> disabled <?php } ?>><?php echo "$level_coa1$Account_Name0 - $collData0"; ?></option>
                                                            <?php
                                                        endforeach;
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputName" class="col-sm-2 control-label">Retensi</label>
                                            <div class="col-sm-10">
                                                <select name="ACC_ID_MCRET" id="ACC_ID_MCRET" class="form-control select2">
                                                    <option value="" > --- </option>
                                                    <?php
                                                    if($resC0a>0)
                                                    {
                                                        foreach($resC0b as $rowA0b) :
                                                            $Acc_ID0        = $rowA0b->Acc_ID;
                                                            $Account_Number0= $rowA0b->Account_Number;
                                                            $Acc_DirParent0 = $rowA0b->Acc_DirParent;
                                                            $Account_Level0 = $rowA0b->Account_Level;
                                                            if($LangID == 'IND')
                                                            {
                                                                $Account_Name0  = $rowA0b->Account_NameId;
                                                            }
                                                            else
                                                            {
                                                                $Account_Name0  = $rowA0b->Account_NameEn;
                                                            }
                                                            
                                                            $Acc_ParentList0    = $rowA0b->Acc_ParentList;
                                                            $isLast_0           = $rowA0b->isLast;
                                                            $disbaled_0         = 0;
                                                            if($isLast_0 == 0)
                                                                $disbaled_0     = 1;
                                                                
                                                            if($Account_Level0 == 0)
                                                                $level_coa1         = "";
                                                            elseif($Account_Level0 == 1)
                                                                $level_coa1         = "&nbsp;&nbsp;&nbsp;";
                                                            elseif($Account_Level0 == 2)
                                                                $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                            elseif($Account_Level0 == 3)
                                                                $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                            elseif($Account_Level0 == 4)
                                                                $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                            elseif($Account_Level0 == 5)
                                                                $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                            elseif($Account_Level0 == 6)
                                                                $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                            elseif($Account_Level0 == 7)
                                                                $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                            
                                                            //$collData0= "$Account_Number0~$Acc_ParentList0";
                                                            $collData0  = "$Account_Number0";
                                                            ?>
                                                                <option value="<?php echo $Account_Number0; ?>" <?php if($Account_Number0 == $ACC_ID_MCRET) { ?> selected <?php } if($disbaled_0 == 1) { ?> disabled <?php } ?>><?php echo "$level_coa1$Account_Name0 - $collData0"; ?></option>
                                                            <?php
                                                        endforeach;
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputName" class="col-sm-2 control-label"><?php echo $Income; ?></label>
                                            <div class="col-sm-10">
                                                <select name="ACC_ID_MCI" id="ACC_ID_MCI" class="form-control select2">
                                                    <option value="" > --- </option>
                                                    <?php
                                                    if($resC0a>0)
                                                    {
                                                        foreach($resC0b as $rowA0b) :
                                                            $Acc_ID0        = $rowA0b->Acc_ID;
                                                            $Account_Number0= $rowA0b->Account_Number;
                                                            $Acc_DirParent0 = $rowA0b->Acc_DirParent;
                                                            $Account_Level0 = $rowA0b->Account_Level;
                                                            if($LangID == 'IND')
                                                            {
                                                                $Account_Name0  = $rowA0b->Account_NameId;
                                                            }
                                                            else
                                                            {
                                                                $Account_Name0  = $rowA0b->Account_NameEn;
                                                            }
                                                            
                                                            $Acc_ParentList0    = $rowA0b->Acc_ParentList;
                                                            $isLast_0           = $rowA0b->isLast;
                                                            $disbaled_0         = 0;
                                                            if($isLast_0 == 0)
                                                                $disbaled_0     = 1;
                                                                
                                                            if($Account_Level0 == 0)
                                                                $level_coa1         = "";
                                                            elseif($Account_Level0 == 1)
                                                                $level_coa1         = "&nbsp;&nbsp;&nbsp;";
                                                            elseif($Account_Level0 == 2)
                                                                $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                            elseif($Account_Level0 == 3)
                                                                $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                            elseif($Account_Level0 == 4)
                                                                $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                            elseif($Account_Level0 == 5)
                                                                $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                            elseif($Account_Level0 == 6)
                                                                $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                            elseif($Account_Level0 == 7)
                                                                $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                            
                                                            //$collData0= "$Account_Number0~$Acc_ParentList0";
                                                            $collData0  = "$Account_Number0";
                                                            ?>
                                                                <option value="<?php echo $Account_Number0; ?>" <?php if($Account_Number0 == $ACC_ID_MCI) { ?> selected <?php } if($disbaled_0 == 1) { ?> disabled <?php } ?>><?php echo "$level_coa1$Account_Name0 - $collData0"; ?></option>
                                                            <?php
                                                        endforeach;
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <button class="btn btn-primary" ><i class="fa fa-save"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
                $DefID      = $this->session->userdata['Emp_ID'];
                $act_lnk    = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                if($DefID == 'D15040004221')
                    echo "<font size='1'><i>$act_lnk</i></font>";
            ?>
        </section>
    </body>
</html>

<script>
  $(function () {
    //Initialize Select2 Elements
    $(".select2").select2();

    //Datemask dd/mm/yyyy
    $("#datemask").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});
    //Datemask2 mm/dd/yyyy
    $("#datemask2").inputmask("mm/dd/yyyy", {"placeholder": "mm/dd/yyyy"});
    //Money Euro
    $("[data-mask]").inputmask();

    //Date range picker
    $('#reservation').daterangepicker();
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A'});
    //Date range as a button
    $('#daterange-btn').daterangepicker(
        {
          ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
          },
          startDate: moment().subtract(29, 'days'),
          endDate: moment()
        },
        function (start, end) {
          $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }
    );

    //Date picker
    $('#datepicker1').datepicker({
      autoclose: true
    });

    //Date picker
    $('#datepicker2').datepicker({
      autoclose: true
    });

    //Date picker
    $('#datepicker3').datepicker({
      autoclose: true
    });

    //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass: 'iradio_minimal-blue'
    });
    //Red color scheme for iCheck
    $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
      checkboxClass: 'icheckbox_minimal-red',
      radioClass: 'iradio_minimal-red'
    });
    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass: 'iradio_flat-green'
    });

    //Colorpicker
    $(".my-colorpicker1").colorpicker();
    //color picker with addon
    $(".my-colorpicker2").colorpicker();

    //Timepicker
    $(".timepicker").timepicker({
      showInputs: false
    });
  });
</script>

<script>
    var decFormat       = 2;
    
    function doDecimalFormat(angka) 
    {
        var a, b, c, dec, i, j;
        angka = String(angka);
        if(angka.indexOf('.') > -1){ a = angka.split('.')[0] ; dec = angka.split('.')[1]
        } else { a = angka; dec = -1; }
        b = a.replace(/[^\d]/g,"");
        c = "";
        var panjang = b.length;
        j = 0;
        for (i = panjang; i > 0; i--) {
            j = j + 1;
            if (((j % 3) == 1) && (j != 1)) c = b.substr(i-1,1) + "," + c;
            else c = b.substr(i-1,1) + c;
        }
        if(dec == -1) return angka;
        else return (c + '.' + dec); 
    }
    
    function RoundNDecimal(X, N) {
        var T, S=new String(Math.round(X*Number("1e"+N)))
        while (S.length<=N) S='0'+S
        return S.substr(0, T=(S.length-N)) + '.' + S.substr(T, N)
    }
        
    function isIntOnlyNew(evt)
    {
        if (evt.which){ var charCode = evt.which; }
        else if(document.all && event.keyCode){ var charCode = event.keyCode; }
        else { return true; }
        return ((charCode == 45) || (charCode == 46) || (charCode == 8) || (charCode >= 48) && (charCode <= 57));
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