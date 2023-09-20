<?php
/* 
    * Author       = Dian Hermanto
    * Create Date  = 27 Maret 2021
    * File Name    = shwLstDoc.php
    * Location     = -
*/
$this->load->view('template/head');

$appName    = $this->session->userdata('appName');
$appBody    = $this->session->userdata['appBody'];

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
    $Display_Rows = $row->Display_Rows;
    $decFormat = $row->decFormat;
endforeach;
if($decFormat == 0)
    $decFormat      = 2;
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $appName; ?></title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <link rel="icon" type="image/png" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/favicon/lock-02.png'; ?>" sizes="32x32">
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

        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">   
    </head>

    <?php
        $LangID         = $this->session->userdata['LangID'];

        if($prjC == 'AllPRJ')
            $qry1       = "";
        else
            $qry1       = "PRJCODE = '$prjC' AND ";

        $sqlDETC        = "$theTbl WHERE $qry1 $theStt = '$theTyp'";
        $resDETC        = $this->db->count_all($sqlDETC);
        $docQty         = number_format($resDETC,0);
        $docStat        = "Baru";
        if($theTyp == 1)
        {
            if($LangID == 'IND')
                $docStat= "Baru";
            else
                $docStat= "New";
        }
        elseif($theTyp == 2)
        {
            if($LangID == 'IND')
                $docStat= "telah Dikonfirmasi";
            else
                $docStat= "confirmed";
        }
        elseif($theTyp == 3)
        {
            if($LangID == 'IND')
                $docStat= "telah Disetujui";
            else
                $docStat= "Approved";
        }
        elseif($theTyp == 6)
        {
            if($LangID == 'IND')
                $docStat= "yang telah Selesai";
            else
                $docStat= "Closed";
        }
        else
        {
            if($LangID == 'IND')
                $docStat= "Tidak Terdefinisi";
            else
                $docStat= "Undefined";
        }


        $sqlTransl      = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
        $resTransl      = $this->db->query($sqlTransl)->result();
        foreach($resTransl as $rowTransl) :
            $TranslCode = $rowTransl->MLANG_CODE;
            $LangTransl = $rowTransl->LangTransl;
            
            if($TranslCode == 'docList')$docList = $LangTransl;
            if($TranslCode == 'weFound')$weFound = $LangTransl;
            if($TranslCode == 'document')$document = $LangTransl;
        endforeach;
        if($LangID == 'IND')
            $desc   = $weFound.$docQty." ".$document." ".$docStat;
        else
            $desc   = $weFound.$docQty." ".$docStat." ".$document;
    ?>
    
    <body class="<?php echo $appBody; ?>">
        <section class="content-header">
        </section>
        <style>
            .search-table, td, th {
                border-collapse: collapse;
            }
            .search-table-outter { overflow-x: scroll; }
        </style>

        <form class="form-horizontal" name="frm_01" method="post" action="" style="display:none">
            <input type="text" name="List_Type" id="List_Type" value="<?php echo $List_Type; ?>" />
            <input type="submit" class="button_css" name="submit1" id="submit1" value="Submit" align="left" />
        </form>
        
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-warning alert-dismissible">
                        <!-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> -->
                        <h4><i class="icon fa fa-warning"></i> <?php echo $docList; ?>!</h4>
                        <?php echo $desc; ?>
                    </div>
                    <div class="box box-primary">
                        <div class="search-table-outter">
                            <table id="tbl" class="table table-bordered table-striped" width="100%">
                                <tr style="background:#CCCCCC">
                                    <th width="2%" style="text-align: center;">No.</th>
                                    <th width="18%" style="text-align: center;">Kode Dokumen</th>
                                    <th width="15%" style="text-align: center;">Tanggal</th>
                                    <!-- <th width="15%" style="text-align: center;">Jatuh Tempo</th> -->
                                    <th width="65%" style="text-align: center;">Deksripsi</th>
                                </tr>
                                <?php
                                    $i          = 0;
                                    $sqlDET     = "SELECT $fldCd AS dCODE, $fldDt AS dDATE, $fldNt AS dNOTE FROM $theTbl WHERE $qry1 $theStt = '$theTyp'";
                                    $result     = $this->db->query($sqlDET)->result();
                                    foreach($result as $row) :
                                        $cRow       = ++$i;
                                        $dCODE      = $row->dCODE;
                                        $dDATE      = date('d-m-Y', strtotime($row->dDATE));
                                        $dNOTE      = $row->dNOTE;
                                        ?>
                                            <tr>
                                                <td><?php echo $i; ?></td>
                                                <td style="text-align: center;"><?php echo $dCODE; ?></td>
                                                <td style="text-align: center;"><?php echo $dDATE; ?></td>
                                                <!-- <td><?php echo $PR_DATE; ?></td> -->
                                                <td><?php echo $dNOTE; ?></td>
                                            </tr>
                                        <?php
                                    endforeach;
                                ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </body>
</html>
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
    //$this->load->view('template/aside');

    //$this->load->view('template/js_data');

    //$this->load->view('template/foot');
?>