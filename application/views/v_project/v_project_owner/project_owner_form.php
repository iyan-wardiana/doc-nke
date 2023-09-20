<?php
/* 
    * Author        = Dian Hermanto
    * Create Date   = 9 Februari 2017
    * File Name     = project_owner_form.php
    * Location      = -
*/

$this->load->view('template/head');

$appName    = $this->session->userdata('appName');
$FlagUSER   = $this->session->userdata['FlagUSER'];
$DefEmp_ID  = $this->session->userdata['Emp_ID'];
$appBody    = $this->session->userdata('appBody');

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

$ISREAD     = $this->session->userdata['ISREAD'];
$ISCREATE   = $this->session->userdata['ISCREATE'];
$ISAPPROVE  = $this->session->userdata['ISAPPROVE'];

// _global function
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
    $Display_Rows = $row->Display_Rows;
    $decFormat = $row->decFormat;
endforeach;
$decFormat      = 2;

/*
$AUTH_PROJECT   = 0;
$DAU_READ   = 0;
$sqlDAU     = "SELECT AUTH_PROJECT FROM tbl_employee_appauth
                WHERE AUTH_EMPID = '$DefEmp_ID'";
$resultDAU  = $this->db->query($sqlDAU)->result();
foreach($resultDAU as $rowDAU) :
    $AUTH_PROJECT   = $rowDAU->AUTH_PROJECT;
endforeach;*/

if($task == 'add')
{
    foreach($viewDocPattern as $row) :
        $Pattern_Code = $row->Pattern_Code;
        $Pattern_Position = $row->Pattern_Position;
        $Pattern_YearAktive = $row->Pattern_YearAktive;
        $Pattern_MonthAktive = $row->Pattern_MonthAktive;
        $Pattern_DateAktive = $row->Pattern_DateAktive;
        $Pattern_Length = $row->Pattern_Length;
        $useYear = $row->useYear;
        $useMonth = $row->useMonth;
        $useDate = $row->useDate;
    endforeach;
    if($Pattern_Position == 'Especially')
    {
        $Pattern_YearAktive = date('Y');
        $Pattern_MonthAktive = date('m');
        $Pattern_DateAktive = date('d');
    }
    
    $sql = "SELECT MAX(patt_No) as maxNumber FROM tbl_owner";
    $result = $this->db->query($sql)->result();
    
    foreach($result as $row) :
        $myMax = $row->maxNumber;
        $myMax = $myMax+1;
    endforeach; 
        
    $lastPatternNumb = $myMax;
    $lastPatternNumb1 = $myMax;
    $len = strlen($lastPatternNumb);
    $nol = '';
    if($Pattern_Length==2)
    {
        if($len==1) $nol="0";
    }
    elseif($Pattern_Length==3)
    {if($len==1) $nol="00";else if($len==2) $nol="0";
    }
    elseif($Pattern_Length==4)
    {if($len==1) $nol="000";else if($len==2) $nol="00";else if($len==3) $nol="0";
    }
    elseif($Pattern_Length==5)
    {if($len==1) $nol="0000";else if($len==2) $nol="000";else if($len==3) $nol="00";else if($len==4) $nol="0";
    }
    elseif($Pattern_Length==6)
    {if($len==1) $nol="00000";else if($len==2) $nol="0000";else if($len==3) $nol="000";else if($len==4) $nol="00";else if($len==5) $nol="0";
    }
    elseif($Pattern_Length==7)
    {if($len==1) $nol="000000";else if($len==2) $nol="00000";else if($len==3) $nol="0000";else if($len==4) $nol="000";else if($len==5) $nol="00";else if($len==6) $nol="0";
    }
    $lastPatternNumb = $nol.$lastPatternNumb;
    $DocNumber = "OWN$lastPatternNumb";
    
    $own_Code           = $DocNumber;
    $own_Title          = 'PT';
    $own_Inst           = 'S'; // S = Swasta, P = Pemerintah
    $own_Name           = '';
    $own_Add1           = '';
    $own_Add2           = '';
    $own_Telp           = '';
    $own_CP             = '';
    $own_CP_Name        = '';
    $own_Email          = '';
    $own_Description    = '';
    $own_ACC_ID         = '';
    $own_ACC_ID2        = '';
    $own_ACC_ID3        = '';
    $own_ACC_ID4        = '';
    $own_ACC_ID5        = '';
    $own_Status         = 1;
    $patt_No            = $myMax;
    
    $imgLoc             = base_url('assets/AdminLTE-2.0.5/own_img/username.jpg');
}
else
{
    $own_Code   = $default['own_Code'];
    $own_Title  = $default['own_Title'];
    $own_Inst   = $default['own_Inst'];
    $own_Name   = $default['own_Name'];     
    $own_Add1   = $default['own_Add1']; 
    $own_Add2   = '';
    $own_Telp   = $default['own_Telp'];
    $own_CP     = $default['own_CP'];
    $own_CP_Name= $default['own_CP_Name'];
    $own_Email  = $default['own_Email'];
    $own_ACC_ID = $default['own_ACC_ID'];
    $own_ACC_ID2= $default['own_ACC_ID2'];
    $own_ACC_ID3= $default['own_ACC_ID3'];
    $own_ACC_ID4= $default['own_ACC_ID4'];
    $own_ACC_ID5= $default['own_ACC_ID5'];
    $own_Status = $default['own_Status'];
    $patt_No    = $default['patt_No'];
    
    $IMGFILNMX  = "";
    $sqlGetIMG  = "SELECT IMGO_FILENAME, IMGO_FILENAMEX FROM tbl_owner_img WHERE IMGO_CUSTCODE = '$own_Code'";
    $resGetIMG  = $this->db->query($sqlGetIMG)->result();
    foreach($resGetIMG as $rowGIMG) :
        $IMGFILNM  = $rowGIMG->IMGO_FILENAME;
        $IMGFILNMX = $rowGIMG->IMGO_FILENAMEX;
    endforeach;
    $IMGFILNM   = $IMGFILNMX ?: "";
    
    $imgLoc     = base_url('assets/AdminLTE-2.0.5/own_img/'.$own_Code.'/'.$IMGFILNM);
    if (!file_exists('assets/AdminLTE-2.0.5/own_img/'.$own_Code))
    {
        $imgLoc = base_url('assets/AdminLTE-2.0.5/own_img/username.jpg');
    }
}
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

            if($TranslCode == 'Add')$Add = $LangTransl;
            if($TranslCode == 'Edit')$Edit = $LangTransl;
            if($TranslCode == 'Code')$Code = $LangTransl;
            if($TranslCode == 'Save')$Save = $LangTransl;
            if($TranslCode == 'Update')$Update = $LangTransl;
            if($TranslCode == 'Back')$Back = $LangTransl;
            if($TranslCode == 'Date')$Date = $LangTransl;
            if($TranslCode == 'Name')$Name = $LangTransl;
            if($TranslCode == 'Address')$Address = $LangTransl;
            if($TranslCode == 'Phone')$Phone = $LangTransl;
            if($TranslCode == 'ContactPerson')$ContactPerson = $LangTransl;
            if($TranslCode == 'ContactPersonName')$ContactPersonName = $LangTransl;
            if($TranslCode == 'Status')$Status = $LangTransl;
            if($TranslCode == 'Owner')$Owner = $LangTransl;
            if($TranslCode == 'Account')$Account = $LangTransl;
            if($TranslCode == 'remAr')$remAr = $LangTransl;
            if($TranslCode == 'ReceiptAmount')$ReceiptAmount = $LangTransl;
            if($TranslCode == 'receivables')$receivables = $LangTransl;
            if($TranslCode == 'lastBill')$lastBill = $LangTransl;
            if($TranslCode == 'AmountReceipt')$AmountReceipt = $LangTransl;
            if($TranslCode == 'Receipt')$Receipt = $LangTransl;
            if($TranslCode == 'BankReceipt')$BankReceipt = $LangTransl;
            if($TranslCode == 'lastRec')$lastRec = $LangTransl;
            if($TranslCode == 'before')$before = $LangTransl;
            if($TranslCode == 'billList')$billList = $LangTransl;
            if($TranslCode == 'Paid')$Paid = $LangTransl;
            if($TranslCode == 'Payable')$Payable = $LangTransl;
            if($TranslCode == 'AboutOwner')$AboutOwner = $LangTransl;
            if($TranslCode == 'ownNmEmpt')$ownNmEmpt = $LangTransl;
            if($TranslCode == 'CPNmEmpty')$CPNmEmpty = $LangTransl;
            if($TranslCode == 'CPEmpty')$CPEmpty = $LangTransl;
            if($TranslCode == 'accEmpty')$accEmpty = $LangTransl;
            if($TranslCode == 'Description')$Description = $LangTransl;
            if($TranslCode == 'Location')$Location = $LangTransl;
            if($TranslCode == 'None')$None = $LangTransl;
            if($TranslCode == 'Information')$Information = $LangTransl;
            if($TranslCode == 'Notes')$Notes = $LangTransl;
            if($TranslCode == 'Picture')$Picture = $LangTransl;
            if($TranslCode == 'ownInfo')$ownInfo = $LangTransl;
            if($TranslCode == 'UploadProfPict')$UploadProfPict = $LangTransl;
            if($TranslCode == 'ChooseFile')$ChooseFile = $LangTransl;
            if($TranslCode == 'Active')$Active = $LangTransl;
            if($TranslCode == 'Inactive')$Inactive = $LangTransl;
            if($TranslCode == 'AccDP')$AccDP = $LangTransl;
            if($TranslCode == 'othACC')$othACC = $LangTransl;
        endforeach;
        $urlUpdDoc      = site_url('c_project/o180c2gner/do_upload/?id='.$this->url_encryption_helper->encode_url($appName));
        
        if($LangID == 'IND')
        {
            $Yes    = "Ya";
            $No     = "Bukan";
            $AcSett = "Pengaturan Akun";
        }
        else
        {
            $Yes    = "Yes";
            $No     = "No";
            $AcSett = "Account Setting";
        }

        $comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
        <section class="content-header">
            <h1>
                <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/project.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $Add; ?>
                <small><?php echo $mnName; ?></small>
              </h1>
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
        </style>

        <section class="content">
            <div class="row">
                <div class="col-md-3">
                    <!-- Profile Image -->
                    <div class="box box-warning">
                        <div class="box-body box-profile">
                            <img class="profile-user-img img-responsive img-circle" src="<?php echo $imgLoc; ?>" style="height:150px; width:150px" alt="User profile picture">
                            <h3 class="profile-username text-center"><?php echo $own_Name; ?></h3>                    
                            <p class="text-muted text-center"><?php //echo $PRJLOCT; ?></p>
                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item">
                                    <?php echo $own_Add1; ?>
                                </li>
                            </ul>
                            <a href="#" class="btn btn-primary btn-block" style="display:none"><b>Follow</b></a>
                        </div>
                    </div>
                    
                    <div class="panel box box-primary">
                        <div class="box-header with-border">
                            <h4 class="box-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                                    <?php echo $receivables; ?>
                                </a>
                            </h4>
                        </div>
                        <?php
                            // AR
                                $AR_T   = 0;
                                $sART   = "SELECT SUM(GPINV_TOTVAL) AS AR_T FROM tbl_projinv_header
                                            WHERE PINV_OWNER = '$own_Code' AND PINV_STAT IN (3,6)";
                                $rART   = $this->db->query($sART)->result();
                                foreach($rART as $rwART) :
                                    $AR_T   = $rwART->AR_T;
                                endforeach;
                                if($AR_T == '') $AR_T = 0;

                            // BR
                                $BR_T   = 0;
                                $sBRT   = "SELECT SUM(A.GAmount) AS BR_T FROM tbl_br_detail A
                                            INNER JOIN tbl_br_header B ON A.BR_NUM = B.BR_NUM
                                            WHERE B.BR_RECTYPE in ('DP', 'PRJ') AND B.BR_PAYFROM = '$own_Code' AND BR_STAT IN (3,6)";
                                $rBRT   = $this->db->query($sBRT)->result();
                                foreach($rBRT as $rwBRT) :
                                    $BR_T   = $rwBRT->BR_T;
                                endforeach;
                                if($BR_T == '') $BR_T = 0;

                            // REM
                                $AR_R   = $AR_T - $BR_T;

                            // LAST AR
                                $PINV_MANNO     = "-";
                                $PINV_DATE      = "-";
                                $PINV_ENDDATE   = "-";
                                $GPINV_TOTVAL   = 0;
                                $sLD            = "SELECT PINV_MANNO, PINV_DATE, PINV_ENDDATE, GPINV_TOTVAL FROM tbl_projinv_header
                                                    WHERE PINV_OWNER = '$own_Code' AND PINV_STAT IN (3,6) ORDER BY PINV_STEP DESC LIMIT 1";
                                $rLD            = $this->db->query($sLD)->result();
                                foreach($rLD as $rwLD) :
                                    $PINV_MANNO     = $rwLD->PINV_MANNO;
                                    $PINV_DATE      = date('d-m-Y', strtotime($rwLD->PINV_DATE));
                                    $PINV_ENDDATE   = date('d-m-Y', strtotime($rwLD->PINV_ENDDATE));
                                    $GPINV_TOTVAL   = $rwLD->GPINV_TOTVAL;
                                endforeach;

                            // LIST BR
                                $BR_CODE    = "-";
                                $BR_DATE    = "-";
                                $BR_AMOUNT  = 0;
                                $sBRLC      = "tbl_br_detail A
                                                INNER JOIN tbl_br_header B ON B.BR_NUM = A.BR_NUM
                                                WHERE B.BR_RECTYPE in ('DP', 'PRJ') AND B.BR_DOCTYPE = 'PINV' AND B.BR_STAT IN (3,6)
                                                     AND B.BR_PAYFROM = '$own_Code'";
                                $rBRLC      = $this->db->count_all($sBRLC);

                                $sBRL       = "SELECT B.BR_CODE, B.BR_DATE, A.GAmount AS BR_AMOUNT, B.Acc_ID FROM tbl_br_detail A
                                                INNER JOIN tbl_br_header B ON B.BR_NUM = A.BR_NUM
                                                WHERE B.BR_RECTYPE in ('DP', 'PRJ') AND B.BR_DOCTYPE = 'PINV' AND B.BR_STAT IN (3,6)
                                                     AND B.BR_PAYFROM = '$own_Code'";
                                $rBRL       = $this->db->query($sBRL)->result();

                            // LIST AR
                                $sARLC      = "tbl_projinv_header WHERE PINV_OWNER = '$own_Code' AND PINV_STAT IN (3,6)";
                                $rARLC      = $this->db->count_all($sARLC);

                                $sARL           = "SELECT PINV_MANNO, PINV_DATE, PINV_ENDDATE, GPINV_TOTVAL, PINV_PAIDAM FROM tbl_projinv_header
                                                    WHERE PINV_OWNER = '$own_Code' AND PINV_STAT IN (3,6) ORDER BY PINV_STEP";
                                $rARL            = $this->db->query($sARL)->result();
                        ?>
                        <div id="collapseOne" class="panel-collapse collapse">
                            <div class="box-body">
                                <div class="form-group">
                                    <strong><i class='glyphicon glyphicon-ok margin-r-5'></i><?php echo $remAr; ?> </strong>
                                    <div style='margin-left: 20px'>
                                        <p>
                                            <?php echo number_format($AR_R, 2); ?>
                                        </p>
                                    </div>
                                    <strong><i class='glyphicon glyphicon-usd margin-r-5'></i><?php echo $ReceiptAmount; ?> </strong>
                                    <div style='margin-left: 20px'>
                                        <p>
                                            <?php echo number_format($BR_T, 2); ?>
                                        </p>
                                    </div>
                                    <strong><i class='glyphicon glyphicon-briefcase margin-r-5'></i>Total <?php echo $receivables; ?> </strong>
                                    <div style='margin-left: 20px'>
                                        <p>
                                            <?php echo number_format($AR_T, 2); ?>
                                        </p>
                                    </div>
                                    <strong><i class='glyphicon glyphicon-duplicate margin-r-5'></i><?php echo $lastBill; ?> </strong>
                                    <div style='margin-left: 20px'>
                                        <p>
                                            <?php echo $PINV_MANNO; ?><br>
                                            <b><?php echo $Date; ?> </b><br>
                                            <?php echo $PINV_DATE; ?><br>
                                            <b><?php echo $AmountReceipt; ?> </b><br>
                                            <?php echo number_format($GPINV_TOTVAL,2); ?><br>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="panel box box-success">
                        <div class="box-header with-border">
                            <h4 class="box-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                                    <?php echo $BankReceipt; ?>
                                </a>
                            </h4>
                        </div>
                        <div id="collapseTwo" class="panel-collapse collapse">
                            <div class="box-body">
                                <div class="form-group">
                                    <?php
                                        if($rBRLC > 0)
                                        {
                                            foreach($rBRL as $rwBRL) :
                                                $BR_CODE    = $rwBRL->BR_CODE;
                                                $BR_DATE    = date('d-m-Y', strtotime($rwBRL->BR_DATE));
                                                $BR_AMOUNT  = number_format($rwBRL->BR_AMOUNT,2);
                                                $AccID      = $rwBRL->Acc_ID;
                                                $AccNm      = "-";
                                                $sAccNm     = "SELECT Account_NameId AS AccNm FROM tbl_chartaccount WHERE Account_Number = '$AccID'";
                                                $rAccNm     = $this->db->query($sAccNm)->result();
                                                foreach($rAccNm as $rwAccNm) :
                                                    $AccNm  = $rwAccNm->AccNm;
                                                endforeach;

                                                echo    "<strong><i class='fa fa-file margin-r-5'></i>$BR_CODE</strong> <spin class='text-muted' style='font-style: italic; font-size: 12px'>($BR_DATE)</spin>
                                                        <div style='margin-left: 20px'>
                                                            <p class='text-muted'>
                                                                $BR_AMOUNT<br>
                                                                $AccNm
                                                            </p>
                                                        </div>";
                                            endforeach;
                                        }
                                        else
                                        {
                                            echo "-";
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="panel box box-info">
                        <div class="box-header with-border">
                            <h4 class="box-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
                                    <?php echo $billList; ?>
                                </a>
                            </h4>
                        </div>
                        <div id="collapseThree" class="panel-collapse collapse">
                            <div class="box-body">
                                <div class="form-group">
                                    <?php
                                        $PINVMANNO      = "-";
                                        $PINVDATE       = "-";
                                        $PINVENDDATE    = "-";
                                        $GPINVTOTVAL    = 0;
                                        $PINVPAIDAM     = 0;
                                        if($rARLC > 0)
                                        { 
                                            foreach($rARL as $rwARL) :
                                                $PINVMANNO      = $rwARL->PINV_MANNO;
                                                $PINVDATE       = date('d-m-Y', strtotime($rwARL->PINV_DATE));
                                                $PINVENDDATE    = date('d-m-Y', strtotime($rwARL->PINV_ENDDATE));
                                                $GPINVTOTVAL    = $rwARL->GPINV_TOTVAL;
                                                $PINVPAIDAM     = $rwARL->PINV_PAIDAM;
                                                $GPINVTOTVALV   = number_format($rwARL->GPINV_TOTVAL,2);
                                                $PINVPAIDAMV    = number_format($rwARL->PINV_PAIDAM,2);
                                                $DEVAMOUN       = $GPINVTOTVAL - $PINVPAIDAM;
                                                
                                                $STATCOL        = 'red';
                                                $STATICON       = 'remove';
                                                $PINSTATD       = 'NR';
                                                $DEVAMOUNV      = number_format($DEVAMOUN,2)." of<br>";

                                                $TEXT1          = "<p><code>$DEVAMOUNV</code> $GPINVTOTVALV</p>";

                                                if($DEVAMOUN <= 1000 AND $DEVAMOUN > -1000)
                                                {
                                                    $DEVAMOUNV  = "";
                                                    $PINSTATD   = 'FR';
                                                }

                                                if($PINSTATD == 'FR')
                                                {
                                                    $STATCOL    = 'green';
                                                    $STATICON   = 'ok';

                                                    $TEXT1      = "<p class='text-green'>$GPINVTOTVALV</p>";
                                                }
                                                
                                                echo "<strong><i class='fa fa-file margin-r-5'></i>$PINVMANNO</strong><span class='pull-right badge bg-$STATCOL'><i class='glyphicon glyphicon-$STATICON'></i></span>
                                                    <div style='margin-left: 20px'>
                                                        <spin class='text-muted' style='font-style: italic; font-size: 12px'>($PINVDATE)</spin>
                                                        $TEXT1
                                                    </div>";
                                            endforeach;
                                        }
                                        else
                                        {
                                            echo "-";
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#settings" data-toggle="tab"><?php echo $Information; ?></a></li>       <!-- Tab 1 -->
                            <li><a href="#profPicture" data-toggle="tab" onclick="showPict()"><?php echo $Picture; ?></a></li>                      <!-- Tab 2 -->
                        </ul>
                        <script type="text/javascript">
                            function showPict()
                            {
                                document.getElementById('profPicture').style.display = '';
                            }
                        </script>
                        <!-- Biodata -->
                        <div class="tab-content">
                            <div class="active tab-pane" id="settings">
                                <form class="form-horizontal" name="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return chkInp()">
                                    <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
                                    <input type="Hidden" name="rowCount" id="rowCount" value="0">
                                    <div class="box box-success">
                                        <div class="box-header with-border">
                                            <h3 class="box-title"><?php echo $ownInfo; ?></h3>
                                        </div>
                                        <br>
                                        <div class="form-group">
                                            <label for="inputName" class="col-sm-2 control-label"><?php echo $Code?> / Instansi</label>
                                            <div class="col-sm-4">
                                                <?php 
                                                    if($task=='edit')
                                                    {
                                                        $own_Title = $default['own_Title'];
                                                        ?>
                                                            <input type="text" class="form-control" name="own_Code1" id="own_Code1" value="<?php echo $own_Code; ?>"disabled />
                                                            <input type="hidden" class="form-control" name="own_Code" id="own_Code" value="<?php echo $own_Code; ?>" />
                                                        <?php 
                                                    }
                                                    else
                                                    {
                                                        $own_Title = 'PT';
                                                        ?>
                                                            <input type="text" class="form-control" name="own_Code" id="own_Code" value="<?php echo $DocNumber; ?>" style="display:none"/>
                                                            <input type="text" class="form-control" name="own_Code1" id="own_Code1" value="<?php echo $DocNumber; ?>" disabled />
                                                        <?php
                                                    }
                                                ?>
                                                <input type="hidden" class="form-control" name="patt_No" id="patt_No" size="15" value="<?php echo $patt_No; ?>" />
                                            </div>
                                            <div class="col-sm-6">
                                                <select name="own_Inst" id="own_Inst" class="form-control select2" >
                                                    <option value="S" <?php if($own_Inst == 'S') { ?> selected <?php } ?>>Swasta</option>
                                                    <option value="P" <?php if($own_Inst == 'P') { ?> selected <?php } ?>>Pemerintah</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputName" class="col-sm-2 control-label">Title / <?php echo $Name?></label>
                                            <div class="col-sm-4">
                                                <select name="own_Title" id="own_Title" class="form-control select2">
                                                    <option value="" > --- </option>
                                                    <option value="CV" <?php if($own_Title == 'CV') { ?> selected <?php } ?>>CV</option>
                                                    <option value="PT" <?php if($own_Title == 'PT') { ?> selected <?php } ?>>PT</option>
                                                    <option value="UD" <?php if($own_Title == 'UD') { ?> selected <?php } ?>>UD</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control" name="own_Name" id="own_Name" value="<?php echo $own_Name; ?>" placeholder="<?php echo $Name; ?>" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="inputName" class="col-sm-4 control-label"><?php echo $Phone; ?> </label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control" name="own_Telp" id="own_Telp" value="<?php echo $own_Telp; ?>" placeholder="<?php echo $Phone; ?>" />
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="inputName" class="col-sm-4 control-label"><?php echo $ContactPersonName; ?> </label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control" name="own_CP_Name" id="own_CP_Name" value="<?php echo $own_CP_Name; ?>" placeholder="<?php echo $ContactPersonName; ?>" />
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="inputName" class="col-sm-4 control-label"><?php echo $ContactPerson; ?> </label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control" name="own_CP" id="own_CP" value="<?php echo $own_CP; ?>" placeholder="<?php echo $ContactPerson; ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <div class="col-sm-12">
                                                        <textarea name="own_Add1" class="form-control" id="own_Add1" style="height:80px" placeholder="<?php echo $Address; ?>" ><?php echo str_replace('<br>', "\n", $own_Add1); ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-12">
                                                        <input type="email" class="form-control" name="own_Email" id="own_Email" placeholder="Email" value="<?php echo "$own_Email"; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                            $PRJCODE    = 'KTR';
                                            $sqlCPRJ    = "SELECT PRJCODE FROM tbl_project WHERE isHO = 1";
                                            $resCPRJ    = $this->db->query($sqlCPRJ)->result();
                                            foreach($resCPRJ as $rowPRJ) :
                                                $PRJCODE= $rowPRJ->PRJCODE;
                                            endforeach;
                                            
                                            $sqlA0a     = "tbl_chartaccount WHERE Account_Category = '1' AND PRJCODE = '$PRJCODE'";
                                            $resA0a     = $this->db->count_all($sqlA0a);
                                            
                                            $sqlA0b     = "SELECT Acc_ID, Account_Number, Account_Level, Account_NameEn, Account_NameId, Acc_ParentList,
                                                                Acc_DirParent, isLast
                                                            FROM tbl_chartaccount WHERE Account_Category = '1' AND PRJCODE = '$PRJCODE' ORDER BY ORD_ID ASC";
                                            $resA0b     = $this->db->query($sqlA0b)->result();
                                        ?>
                                        <div class="panel box box-warning">
                                            <div class="box-header with-border">
                                                <h4 class="box-title">
                                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseAcc">
                                                    <?php echo $AcSett; ?>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="collapseAcc" class="panel-collapse">
                                                <div class="box-body">
                                                    <div class="form-group">
                                                        <label for="inputName" class="col-sm-2 control-label" title="Piutang Saat Proj. Invoice"><?php echo "$Account $receivables"; ?></label>
                                                        <div class="col-sm-10">
                                                            <select name="own_ACC_ID" id="own_ACC_ID" class="form-control select2" title="Piutang Saat Proj. Invoice">
                                                                <option value="" > --- </option>
                                                                <?php
                                                                if($resA0a>0)
                                                                {
                                                                    foreach($resA0b as $rowA0b) :
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
                                                                            <option value="<?php echo $Account_Number0; ?>" <?php if($Account_Number0 == $own_ACC_ID) { ?> selected <?php } if($disbaled_0 == 1) { ?> disabled <?php } ?>><?php echo "$level_coa1$Account_Name0 - $collData0"; ?></option>
                                                                        <?php
                                                                    endforeach;
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <?php
                                                        $sqlB0a     = "tbl_chartaccount WHERE PRJCODE = '$PRJCODE'";
                                                        $resB0a     = $this->db->count_all($sqlB0a);
                                                        
                                                        $sqlB0b     = "SELECT Acc_ID, Account_Number, Account_Level, Account_NameEn, Account_NameId, Acc_ParentList,
                                                                            Acc_DirParent, isLast
                                                                        FROM tbl_chartaccount WHERE PRJCODE = '$PRJCODE' ORDER BY ORD_ID ASC";
                                                        $resB0b     = $this->db->query($sqlB0b)->result();
                                                    ?>
                                                    <div class="form-group">
                                                        <label for="inputName" class="col-sm-2 control-label" title="Uang Muka Saat Proj. Invoice"><?=$AccDP?></label>
                                                        <div class="col-sm-10">
                                                            <select name="own_ACC_ID2" id="own_ACC_ID2" class="form-control select2" title="Uang Muka Saat Proj. Invoice">
                                                                <option value="" > --- </option>
                                                                <?php
                                                                if($resB0a>0)
                                                                {
                                                                    foreach($resB0b as $rowB0b) :
                                                                        $Acc_ID0        = $rowB0b->Acc_ID;
                                                                        $Account_Number0= $rowB0b->Account_Number;
                                                                        $Acc_DirParent0 = $rowB0b->Acc_DirParent;
                                                                        $Account_Level0 = $rowB0b->Account_Level;
                                                                        if($LangID == 'IND')
                                                                        {
                                                                            $Account_Name0  = $rowB0b->Account_NameId;
                                                                        }
                                                                        else
                                                                        {
                                                                            $Account_Name0  = $rowB0b->Account_NameEn;
                                                                        }
                                                                        
                                                                        $Acc_ParentList0    = $rowB0b->Acc_ParentList;
                                                                        $isLast_0           = $rowB0b->isLast;
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
                                                                            <option value="<?php echo $Account_Number0; ?>" <?php if($Account_Number0 == $own_ACC_ID2) { ?> selected <?php } if($disbaled_0 == 1) { ?> disabled <?php } ?>><?php echo "$level_coa1$Account_Name0 - $collData0"; ?></option>
                                                                        <?php
                                                                    endforeach;
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="inputName" class="col-sm-2 control-label"><?php echo $Account; ?> (PPh Inv)</label>
                                                        <div class="col-sm-10">
                                                            <select name="own_ACC_ID3" id="own_ACC_ID3" class="form-control select2">
                                                                <option value="" > --- </option>
                                                                <?php
                                                                if($resA0a>0)
                                                                {
                                                                    foreach($resA0b as $rowA0b) :
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
                                                                            <option value="<?php echo $Account_Number0; ?>" <?php if($Account_Number0 == $own_ACC_ID3) { ?> selected <?php } if($disbaled_0 == 1) { ?> disabled <?php } ?>><?php echo "$level_coa1$Account_Name0 - $collData0"; ?></option>
                                                                        <?php
                                                                    endforeach;
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="inputName" class="col-sm-2 control-label"><?php echo $Account; ?> (PPn Inv)</label>
                                                        <div class="col-sm-10">
                                                            <select name="own_ACC_ID4" id="own_ACC_ID4" class="form-control select2">
                                                                <option value="" > --- </option>
                                                                <?php
                                                                if($resB0a>0)
                                                                {
                                                                    foreach($resB0b as $rowB0b) :
                                                                        $Acc_ID0        = $rowB0b->Acc_ID;
                                                                        $Account_Number0= $rowB0b->Account_Number;
                                                                        $Acc_DirParent0 = $rowB0b->Acc_DirParent;
                                                                        $Account_Level0 = $rowB0b->Account_Level;
                                                                        if($LangID == 'IND')
                                                                        {
                                                                            $Account_Name0  = $rowB0b->Account_NameId;
                                                                        }
                                                                        else
                                                                        {
                                                                            $Account_Name0  = $rowB0b->Account_NameEn;
                                                                        }
                                                                        
                                                                        $Acc_ParentList0    = $rowB0b->Acc_ParentList;
                                                                        $isLast_0           = $rowB0b->isLast;
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
                                                                            <option value="<?php echo $Account_Number0; ?>" <?php if($Account_Number0 == $own_ACC_ID4) { ?> selected <?php } if($disbaled_0 == 1) { ?> disabled <?php } ?>><?php echo "$level_coa1$Account_Name0 - $collData0"; ?></option>
                                                                        <?php
                                                                    endforeach;
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="inputName" class="col-sm-2 control-label"><?=$othACC?></label>
                                                        <div class="col-sm-10">
                                                            <select name="own_ACC_ID5" id="own_ACC_ID5" class="form-control select2">
                                                                <option value="" > --- </option>
                                                                <?php
                                                                if($resB0a>0)
                                                                {
                                                                    foreach($resB0b as $rowB0b) :
                                                                        $Acc_ID0        = $rowB0b->Acc_ID;
                                                                        $Account_Number0= $rowB0b->Account_Number;
                                                                        $Acc_DirParent0 = $rowB0b->Acc_DirParent;
                                                                        $Account_Level0 = $rowB0b->Account_Level;
                                                                        if($LangID == 'IND')
                                                                        {
                                                                            $Account_Name0  = $rowB0b->Account_NameId;
                                                                        }
                                                                        else
                                                                        {
                                                                            $Account_Name0  = $rowB0b->Account_NameEn;
                                                                        }
                                                                        
                                                                        $Acc_ParentList0    = $rowB0b->Acc_ParentList;
                                                                        $isLast_0           = $rowB0b->isLast;
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
                                                                            <option value="<?php echo $Account_Number0; ?>" <?php if($Account_Number0 == $own_ACC_ID5) { ?> selected <?php } if($disbaled_0 == 1) { ?> disabled <?php } ?>><?php echo "$level_coa1$Account_Name0 - $collData0"; ?></option>
                                                                        <?php
                                                                    endforeach;
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="inputName" class="col-sm-2 control-label"><?php echo $Status ?></label>
                                                        <div class="col-sm-10">
                                                            <select name="own_Status" id="own_Status" class="form-control select2">
                                                                <option value="1" <?php if($own_Status == 1) { ?> selected <?php } ?>><?php echo $Active; ?></option>
                                                                <option value="2" <?php if($own_Status == 2) { ?> selected <?php } ?>><?php echo $Inactive; ?></option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputName" class="col-sm-2 control-label">&nbsp;</label>
                                        <div class="col-sm-10">
                                            <?php
                                                if($ISCREATE == 1 )
                                                {
                                                    if($task=='add')
                                                    {
                                                        ?>
                                            
                                                            <button class="btn btn-primary">
                                                            <i class="fa fa-save"></i></button>&nbsp;
                                                        <?php
                                                    }
                                                    else
                                                    {
                                                        ?>
                                                            <button class="btn btn-primary" >
                                                            <i class="fa fa-save"></i></button>&nbsp;
                                                        <?php
                                                    }
                                                }
                                                echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>');
                                            ?>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="active tab-pane" id="profPicture" style="display: none;">
                                <form class="form-horizontal" name="frm" method="post" action="<?php echo $urlUpdDoc; ?>" enctype="multipart/form-data" onSubmit="return checkData()">
                                    <div class="box box-success">
                                        <div class="box-header with-border">
                                            <h3 class="box-title"><?php echo $UploadProfPict; ?></h3>
                                        </div>
                                        <br>
                                        <div class="form-group">
                                            <label for="inputName" class="col-sm-2 control-label"><?php echo $Code; ?> </label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="own_Code" id="own_Code" value="<?php echo $own_Code; ?>" readonly />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputName" class="col-sm-2 control-label"><?php echo $ChooseFile ?> </label>
                                            <div class="col-sm-10">
                                              <input type="file" name="userfile" class="filestyle" data-buttonName="btn-primary"/>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="form-group">
                                            <label for="inputName" class="col-sm-2 control-label">&nbsp;</label>
                                            <div class="col-sm-10">
                                                <?php
                                                    if($ISCREATE == 1)
                                                    {
                                                        if($task=='add')
                                                        {
                                                            ?>
                                                                <button class="btn btn-primary">
                                                                <i class="fa fa-save"></i></button>&nbsp;
                                                            <?php
                                                        }
                                                        else
                                                        {
                                                            ?>
                                                                <button class="btn btn-primary" >
                                                                <i class="fa fa-save"></i></button>&nbsp;
                                                            <?php
                                                        }
                                                    }
                                                    echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>');
                                                ?>
                                            </div>
                                        </div><br>
                                    </div>
                                </form>
                            </div>
                            <script>
                                function checkData()
                                {
                                    filename    = document.getElementById('FileName').value;
                                    if(filename == '')
                                    {
                                        alert('Please input file name.');
                                        document.getElementById('FileName').focus();
                                        return false;
                                    }
                                }
                            </script>
                        </div>
                    </div>
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

    function chkInp()
    {
        own_Name    = document.getElementById("own_Name").value;
        own_CP_Nm   = document.getElementById('own_CP_Name').value;
        own_CP      = document.getElementById('own_CP').value;
        own_ACC_ID  = document.getElementById('own_ACC_ID').value;
        if(own_Name == '')
        {
            swal('<?php echo $ownNmEmpt; ?>',
            {
                icon: "warning",
            })
            .then(function()
            {
                swal.close();
                $('#own_Name').focus();
            });
            return false;
        }
        if(own_CP_Nm == '')
        {
            swal('<?php echo $CPNmEmpty; ?>',
            {
                icon: "warning",
            })
            .then(function()
            {
                swal.close();
                $('#own_CP_Name').focus();
            });
            return false;
        }
        if(own_CP == '')
        {
            swal('<?php echo $CPEmpty; ?>',
            {
                icon: "warning",
            })
            .then(function()
            {
                swal.close();
                $('#own_CP').focus();
            });
            return false;
        }
        if(own_ACC_ID == '')
        {
            swal('<?php echo $accEmpty; ?>');
            document.getElementById('own_CP').focus();
            return false;
        }
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