<?php
/* 
 * Author       = Dian Hermanto
 * Create Date  = 22 Mei 2018
 * File Name    = joblistdet_form.php
 * Location     = -
*/

$this->load->view('template/head');

$appName    = $this->session->userdata('appName');
$appBody    = $this->session->userdata('appBody');

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

// _global function
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
    $Display_Rows = $row->Display_Rows;
    $decFormat = $row->decFormat;
endforeach;
$decFormat      = 2;

$FlagUSER       = $this->session->userdata['FlagUSER'];
$DefEmp_ID      = $this->session->userdata['Emp_ID'];

$sql = "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$result = $this->db->query($sql)->result();
foreach($result as $row) :
    $PRJNAME = $row ->PRJNAME;
endforeach;

$sqlJobC        = "tbl_joblist WHERE JOBLEV = 1 AND PRJCODE = '$PRJCODE' AND ISHEADER = '1'";
$resJobC        = $this->db->count_all($sqlJobC);
$RUNNO          = $resJobC+1;
$NEWCODE        = "$PRJCODE.$RUNNO";

$currentRow = 0;
if($task == 'add')
{
    $ORD_ID     = 0;
    $JOBCODEID  = $NEWCODE;
    $JOBCODEIDV = '';
    $JOBPARENT  = '';
    $PRJCODE    = $PRJCODE;
    $JOBCOD1    = '';
    $JOBDESC    = '';
    $JOBCLASS   = '';
    $JOBGRP     = '';
    $JOBTYPE    = '';
    $JOBUNIT    = '';
    $JOBLEV     = 1;
    $JOBVOLM    = 0;
    $PRICE      = 0;
    $JOBCOST    = 0;
    $ISLAST     = 0;
    $ITM_NEED   = 0;
    $ITM_GROUP  = 'S';
    $ISHEADER   = 1;
    $ITM_CODE   = '';
    $JOBLEV     = 0;
}
else
{
    $ORD_ID     = $default['ORD_ID'];
    $JOBCODEID  = $default['JOBCODEID'];
    $JOBCODEIDV = $default['JOBCODEIDV'];
    $JOBPARENT  = $default['JOBPARENT'];
    $PRJCODE    = $default['PRJCODE'];
    $PRJCODE    = $default['PRJCODE'];
    $JOBCOD1    = $default['JOBCOD1'];
    $JOBDESC    = $default['JOBDESC'];
    $JOBCLASS   = $default['JOBCLASS'];
    $JOBGRP     = $default['JOBGRP'];
    $JOBTYPE    = $default['JOBTYPE'];
    $JOBUNIT    = $default['JOBUNIT'];
    $JOBLEV     = $default['JOBLEV'];
    $JOBVOLM    = $default['JOBVOLM'];
    $PRICE      = $default['PRICE'];
    $JOBCOST    = $default['JOBCOST'];
    $ISLAST     = $default['ISLAST'];
    $ITM_NEED   = $default['ITM_NEED'];
    $ITM_GROUP  = $default['ITM_GROUP'];
    $ISHEADER   = $default['ISHEADER'];
    $ITM_CODE   = $default['ITM_CODE'];
}

if($PRICE == 0)
{
    if($JOBVOLM == 0)
        $PRICE  = 0;
    else
        $PRICE  = $JOBCOST / $JOBVOLM;
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

    <style type="text/css">
        .search-table, td, th {
            border-collapse: collapse;
        }
        .search-table-outter { overflow-x: scroll; }
        
        a[disabled="disabled"] {
            pointer-events: none;
        }
    </style>

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
            if($TranslCode == 'Save')$Save = $LangTransl;
            if($TranslCode == 'Back')$Back = $LangTransl;
            if($TranslCode == 'Update')$Update = $LangTransl;
            if($TranslCode == 'Select')$Select = $LangTransl;
            if($TranslCode == 'Close')$Close = $LangTransl;
            if($TranslCode == 'Code')$Code = $LangTransl;
            if($TranslCode == 'Parent')$Parent = $LangTransl;
            if($TranslCode == 'Unit')$Unit = $LangTransl;
            if($TranslCode == 'Description')$Description = $LangTransl;
            if($TranslCode == 'JobDescription')$JobDescription = $LangTransl;
            if($TranslCode == 'Category')$Category = $LangTransl;
            if($TranslCode == 'Price')$Price = $LangTransl;
            if($TranslCode == 'Type')$Type = $LangTransl;
            if($TranslCode == 'ItemName')$ItemName = $LangTransl;
            if($TranslCode == 'JobName')$JobName = $LangTransl;
            if($TranslCode == 'JobLevel')$JobLevel = $LangTransl;
            if($TranslCode == 'TopLevel')$TopLevel = $LangTransl;
            if($TranslCode == 'JobCode')$JobCode = $LangTransl;
            if($TranslCode == 'JobList')$JobList = $LangTransl;
        endforeach;
        
        if($LangID == 'IND')
        {
            $subTitleH  = "Tambah Pekerjaan";
            $subTitleD  = "detail pekerjaan";
            $Invoiced   = " sudah dibuatkan faktur";
            $alert1     = "Deskripsi pekerjaan sudah diedit.";
            $alert2     = "Deskripsi pekerjaan tidak boleh kosong.";
            $alert3     = "Anda harus memilih relasi material.";
            $alert4     = "Silahan pilih Level Pekerjaan.";
            $alert5     = "Silahan pilih induk anggaran.";
            $alert6     = "Kode pekerjaan tidak boleh kosong.";
            $alert7     = "Nama pekerjaan tidak boleh kosong.";
            $alert8     = "Unit pekerjaan tidak boleh kosong.";
            $miscell    = "Rupa-Rupa";
        }
        else
        {
            $subTitleH  = "Add Job";
            $subTitleD  = "job detail";
            $Invoiced   = " has already been created an invoice.";
            $alert1     = "Job description updated.";
            $alert2     = "Job description can not be empty.";
            $alert3     = "You must select an item relation.";
            $alert4     = "Please select a Jov Level.";
            $alert5     = "Please select a budget parent.";
            $alert6     = "Job Code can not be empty.";
            $alert7     = "Job Name can not be empty.";
            $alert8     = "Job Unit can not be empty.";
            $miscell    = "Miscellaneous";
        }

        $JOBDESCD   = '';
        $sqlJD      = "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JOBCODEID'";
        $resJD      = $this->db->query($sqlJD)->result();
        foreach($resJD as $rowJD) :
            $JOBDESCD = $rowJD->JOBDESC;
        endforeach;

        $JOBDESCP   = '';
        $JOBDESC1   = '';
        $sqlJDP     = "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JOBPARENT'";
        $resJDP     = $this->db->query($sqlJDP)->result();
        foreach($resJDP as $rowJDP) :
            $JOBDESCP = $rowJDP->JOBDESC;
        endforeach;
        if($JOBPARENT != '')
            $JOBDESC1   = "$JOBPARENT : $JOBDESCP";

        $comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
        <section class="content-header">
            <h1><img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/purchase_req.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $subTitleH; ?>
                <small><?php echo $subTitleD; ?></small>  </h1>
        </section>

        <section class="content">
            <div class="row">
                <form class="form-horizontal" name="frm" method="post" action="<?php echo $form_action; ?>" enctype="multipart/form-data" onSubmit="return submitForm()">
                    <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
                    <input type="hidden" name="PRJCODE" id="PRJCODE" value="<?php echo $PRJCODE; ?>" />
                    <input type="Hidden" name="rowCount" id="rowCount" value="0">
                    <div class="col-md-8">
                        <div class="box box-primary">
                            <div class="box-header with-border" style="display: none;">
                                <i class="fa fa-cloud-upload"></i>
                                <h3 class="box-title">&nbsp;</h3>
                            </div>
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-3 control-label"><?php echo $JobLevel; ?></label>
                                    <div class="col-sm-9">
                                        <select name="IS_LAST" id="IS_LAST" class="form-control select2" onChange="chgLevel(this.value);">
                                            <option value="" <?php if($ISLAST == '') { ?> selected <?php } ?>>---</option>
                                            <option value="1" <?php if($ISLAST == 1 || $ISLAST == 0) { ?> selected <?php } ?>><?php echo $TopLevel; ?></option>
                                            <option value="2" <?php if($ISLAST == 2 || $ISLAST == 0) { ?> selected <?php } ?>>Header</option>
                                            <option value="3" <?php if($ISLAST == 1) { ?> selected <?php } ?>>Detail</option>
                                        </select>
                                        <input type="hidden" class="form-control" name="ORD_ID" id="ORD_ID" value="<?php echo $ORD_ID; ?>" />
                                        <input type="hidden" class="form-control" name="JOBLEV" id="JOBLEV" value="<?php echo $JOBLEV; ?>" />
                                    </div>
                                </div>
                                <div class="form-group" style="display:none">
                                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Type; ?></label>
                                    <div class="col-sm-9">
                                        <select name="ISHEADER" id="ISHEADER" class="form-control select2" style="max-width:150px" onChange="chgType(this.value);">
                                            <option value="1" <?php if($ISHEADER == 1) { ?> selected <?php } ?>>Header</option>
                                            <option value="0" <?php if($ISHEADER == 0) { ?> selected <?php } ?> disabled>Detail</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group" id="showJobParent">
                                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Parent; ?></label>
                                    <div class="col-sm-9">
                                        <div class="input-group">
                                            <div class="input-group-btn">
                                                <button type="button" class="btn btn-primary" onClick="selJOB()"><i class="fa fa-search"></i></button>
                                            </div>
                                            <input type="hidden" class="form-control" name="JOBPARENT" id="JOBPARENT" value="<?php echo $JOBPARENT; ?>" >
                                            <input type="text" class="form-control" name="JOBPARENT1" id="JOBPARENT1" value="<?php echo "$JOBPARENT $JOBDESC"; ?>" readonly>
                                            <input type="hidden" class="form-control" name="JOBPARENT2" id="JOBPARENT2" value="<?php echo $JOBPARENT; ?>" data-toggle="modal" data-target="#mdl_addJList">
                                        </div>
                                    </div>
                                </div>
                                <script type="text/javascript">
                                    function selJOB()
                                    {
                                        JOBLEV   = document.getElementById('JOBLEV').value;

                                        if(JOBLEV == '')
                                        {
                                            swal("<?php echo $alert4; ?>",
                                            {
                                                icon:"warning"
                                            });
                                            return false;
                                        }

                                        document.getElementById('JOBPARENT2').click();
                                    }
                                </script>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-3 control-label"><?php echo $JobCode; ?></label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" style="text-align:left" name="JOBCODEID" id="JOBCODEID" value="<?php echo $JOBCODEID; ?>" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-3 control-label" style="vertical-align:top"><?php echo $JobName; ?></label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" style="text-align:left" name="JOBDESC" id="JOBDESC" value="<?php echo $JOBDESC; ?>" />
                                    </div>
                                </div>
                                <div class="form-group" style="display:none">
                                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Category; ?></label>
                                    <div class="col-sm-9">
                                        <select name="ITM_GROUP" id="ITM_GROUP" class="form-control">
                                                <option value="S" <?php if($ITM_GROUP == 'S') { ?> selected <?php } ?>><?php echo $JobName; ?></option>
                                                <option value="M" <?php if($ITM_GROUP == 'M') { ?> selected <?php } ?>>Material</option>
                                                <option value="U" <?php if($ITM_GROUP == 'U') { ?> selected <?php } ?>>Upah</option>
                                                <option value="SC" <?php if($ITM_GROUP == 'SC') { ?> selected <?php } ?>>Subkon</option>
                                                <option value="T" <?php if($ITM_GROUP == 'T') { ?> selected <?php } ?>>Alat</option>
                                                <option value="O" <?php if($ITM_GROUP == 'O') { ?> selected <?php } ?>>Overhead</option>
                                                <option value="I" <?php if($ITM_GROUP == 'I') { ?> selected <?php } ?>><?php echo $miscell; ?></option>
                                                <option value="GE" <?php if($ITM_GROUP == 'GE') { ?> selected <?php } ?>><?php echo $miscell; ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Unit; ?></label>
                                    <div class="col-sm-9">
                                        <select name="JOBUNIT" id="JOBUNIT" class="form-control select2">
                                            <option value="">---</option>
                                            <?php
                                                $sqlUnit    = "SELECT * FROM tbl_unittype";
                                                $resUnit    = $this->db->query($sqlUnit)->result();
                                                foreach($resUnit as $rowUM) :
                                                    $Unit_Type_Code = $rowUM->Unit_Type_Code;
                                                    $UMCODE         = $rowUM->UMCODE;
                                                    $Unit_Type_Name = $rowUM->Unit_Type_Name;
                                                    ?>
                                                    <option value="<?php echo $Unit_Type_Code; ?>" <?php if($UMCODE == $JOBUNIT) { ?> selected <?php } ?>><?php echo $Unit_Type_Name; ?></option>
                                                    <?php
                                                endforeach;
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-3 control-label">Qty</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" style="text-align:right" name="JOBVOLM1" id="JOBVOLM1" value="<?php echo number_format($JOBVOLM, 2); ?>" onKeyPress="return isIntOnlyNew(event);" onBlur="chgVolm(this);" disabled />
                                        <input type="hidden" class="form-control" style="max-width:150px;text-align:right" name="JOBVOLM" id="JOBVOLM" value="<?php echo $JOBVOLM; ?>" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Price; ?></label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" style="text-align:right" name="PRICE1" id="PRICE1" value="<?php echo number_format($PRICE, 2); ?>" onKeyPress="return isIntOnlyNew(event);" onBlur="chgPrice(this);" disabled />
                                        <input type="hidden" class="form-control" style="max-width:150px;text-align:right" name="PRICE" id="PRICE" value="<?php echo $PRICE; ?>" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-3 col-sm-9">
                                        <button class="btn btn-primary" id="btnSave" >
                                            <i class="fa fa-save"></i>
                                        </button>&nbsp;
                                        <?php
                                            $backURL    = site_url('c_project/c_joblistdet/gl180c21JL/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
                                                echo anchor("$backURL",'<button class="btn btn-danger" type="button" id="btnBack"><i class="fa fa-reply"></i></button>');
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="alert alert-warning alert-dismissible">
                            <h4><i class="icon fa fa-warning"></i> Perhatian!</h4>
                            <ol>
                                <li>Silahkan tentukan Kategori Pekerjaan. <b>Level Atas</b> dijadikan sebagai tingkatan pekerjaan yang tidak memiliki Level Pekerjaan. <b>Header</b> dijadikan sebagai tingkatan pekerjaan yang memiliki turunan header. <b>Detail</b> dijadikan sebagai tingkatan pekerjaan yang memiliki detil RAP.</li>
                                <li><b>Kode Pekerjaan</b> akan terbentuk secara otomatis, namun masih dapat dirubah.</li>
                                <li><b>Nama Pekerjaan</b> diinput secara manual.</li>
                            </ol>
                        </div>
                    </div>
                </form>
            </div>

            <!-- ============ START MODAL JOBLIST =============== -->
                <style type="text/css">
                    .modal-dialog{
                        position: relative;
                        display: table; /* This is important */ 
                        overflow-y: auto;    
                        overflow-x: auto;
                        width: auto;
                        min-width: 300px;   
                    }
                </style>
                <?php
                    $Active1        = "active";
                    $Active2        = "";
                    $Active1Cls     = "class='active'";
                    $Active2Cls     = "";
                ?>
                <div class="modal fade" id="mdl_addJList" name='mdl_addJList' tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <ul class="nav nav-tabs">
                                            <li id="li1" <?php echo $Active1Cls; ?>>
                                                <a href="#itm1" data-toggle="tab"><?php echo $JobList; ?></a>
                                            </li>
                                        </ul>
                                        <div class="box-body">
                                            <div class="<?php echo $Active1; ?> tab-pane" id="itm2">
                                                <div class="form-group">
                                                    <form method="post" name="frmSearch1" id="frmSearch1" action="">
                                                        <table id="example0" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
                                                            <thead>
                                                                <tr>
                                                                    <th width="1%">&nbsp;</th>
                                                                    <th width="99%" nowrap><?php echo $Description; ?></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            </tbody>
                                                        </table>
                                                        <button class="btn btn-primary" type="button" id="btnDetail0" name="btnDetail0">
                                                            <i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?>
                                                        </button>&nbsp;
                                                        <button type="button" id="idClose0" class="btn btn-danger" data-dismiss="modal">
                                                            <i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="rowCheck0" id="rowCheck0" value="0">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <script type="text/javascript">
                    $(document).ready(function()
                    {
                        $('#example0').DataTable(
                        {
                            "processing": true,
                            "serverSide": true,
                            //"scrollX": false,
                            "autoWidth": true,
                            "filter": true,
                            "ajax": "<?php echo site_url('c_comprof/c_am1h0db2/get_AllDataJLH/?id='.$PRJCODE)?>",
                            "type": "POST",
                            //"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
                            "lengthMenu": [[25, 50, 100, 200], [25, 50, 100, 200]],
                            "columnDefs": [ { targets: [0], className: 'dt-body-center' },
                                            { "width": "2px", "targets": [0] },
                                            { "width": "98px", "targets": [1] }
                                          ],
                            "language": {
                                "infoFiltered":"",
                                "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
                            },
                        });
                    });

                    var selectedRows = 0;
                    function pickThis0(thisobj) 
                    {
                        var favorite = [];
                        $.each($("input[name='chk0']:checked"), function() {
                            favorite.push($(this).val());
                        });
                        $("#rowCheck0").val(favorite.length);
                    }

                    $(document).ready(function()
                    {
                        $("#btnDetail0").click(function()
                        {
                            var totChck     = $("#rowCheck0").val();

                            if(totChck == 0)
                            {
                                swal('<?php echo $alert5; ?>',
                                {
                                    icon: "warning",
                                })
                                .then(function()
                                {
                                    swal.close();
                                });
                                return false;
                            }

                            $.each($("input[name='chk0']:checked"), function()
                            {
                                add_header($(this).val());
                            });

                            $('#mdl_addJList').on('hidden.bs.modal', function () {
                                $(this)
                                    .find("input,textarea,select")
                                        //.val('')
                                        .end()
                                    .find("input[type=checkbox], input[type=radio]")
                                       .prop("checked", "")
                                       .end();
                            });
                            document.getElementById("idClose0").click()
                        });
                    });
                </script>
            <!-- ============ END MODAL JOBLIST =============== -->
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
        $('#datepicker').datepicker({
          autoclose: true
        });
        
        //Date picker
        $('#datepicker1').datepicker({
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
  
    function doDecimalFormat(angka) {
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
    
    function chgLevel(thisValue)
    {
        if(thisValue == 1)
        {
            //document.getElementById('showITEM').style.display     = 'none';
            document.getElementById('ISHEADER').style.display       = 'none';
            document.getElementById('showJobParent').style.display  = 'none';
            $("#JOBPARENT").val('');
            $("#JOBPARENT1").val('');
        }
        else
        {
            //document.getElementById('showITEM').style.display     = '';
            document.getElementById('ISHEADER').style.display       = '';
            document.getElementById('showJobParent').style.display  = '';
            $("#JOBPARENT").val('');
            $("#JOBPARENT1").val('');
        }
    }

    function add_header(strItem)
    {
        arrItem     = strItem.split('|');
        ORD_ID      = parseFloat(arrItem[0]) + 1;
        JOBPARENT   = arrItem[1];
        JOBDESC     = arrItem[2];
        JOBLEV      = parseFloat(arrItem[3]) + 1;

        document.getElementById('JOBLEV').value         = JOBLEV;
        document.getElementById('JOBPARENT').value      = JOBPARENT;
        document.getElementById('JOBPARENT1').value     = JOBPARENT+' '+JOBDESC;

        var PRJCODE     = document.getElementById("PRJCODE").value;
        var depart      = JOBPARENT+'~'+PRJCODE;

        $.ajax({
            url: '<?php echo site_url('c_project/c_joblistdet/getCODEJOBLISTRN/?id=')?>',
            type: 'post',
            data: {depart:depart},
            success:function(response)
            {
                arrItem         = response.split('~');
                var NEWJIDA     = arrItem[0];
                var NEWORID     = arrItem[1];
                var NEWJID      = JOBPARENT+'.'+NEWJIDA;
                $("#JOBCODEID").val(NEWJID);
                $("#ORD_ID").val(NEWORID);
            }
        });
    }
    
    function chgVolm(thisVal)
    {
        var decFormat   = document.getElementById('decFormat').value;
        var JOBVOLM     = eval(document.getElementById('JOBVOLM1').value.split(",").join(""));
        
        document.getElementById('JOBVOLM').value = parseFloat(Math.abs(JOBVOLM));
        document.getElementById('JOBVOLM1').value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(JOBVOLM)),decFormat));
    }
    
    function chgPrice(thisVal)
    {
        var decFormat   = document.getElementById('decFormat').value;
        var PRICE1  = eval(document.getElementById('PRICE1').value.split(",").join(""));
        
        document.getElementById('PRICE').value = parseFloat(Math.abs(PRICE1));
        document.getElementById('PRICE1').value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PRICE1)),decFormat));
    }
    
    function chgType(isHeader)
    {
        if(isHeader == 1)
        {
            //document.getElementById('showITEM').style.display         = 'none';
            document.getElementById('showJobParent').style.display  = '';
        }
        else
        {
            //document.getElementById('showITEM').style.display         = '';
            document.getElementById('showJobParent').style.display  = '';
        }
    }
    
    $(document).ready(function()
    {
        $("#JOBLEV_XX").change(function()
        {
            var PRJCODE     = document.getElementById("PRJCODE").value;
            var deptid1     = $(this).val();
            alert(deptid1)
            var deptid      = deptid1+'~'+PRJCODE;
            if(deptid1 == 1)
            {
                document.getElementById("JOBCODEID").value  = '<?php echo $NEWCODE; ?>';
            }
            else
            {
                $.ajax({
                    url: '<?php echo site_url('c_project/c_joblistdet/getJOBLIST/?id=')?>',
                    type: 'post',
                    data: {depart:deptid},
                    dataType: 'json',
                    success:function(response)
                    {
                        var len = response.length;
                        
                        $("#JOBPARENT").empty();
                        for( var i = 0; i<len; i++){
                            var JOBCODEID   = response[i]['JOBCODEID'];
                            var JOBDESC     = response[i]['JOBDESC'];
                            var ISDISABLED  = response[i]['ISDISABLED'];
                            
                            $("#JOBPARENT").append("<option value='"+JOBCODEID+"' "+ISDISABLED+">"+JOBDESC+"</option>");
                        }
                    }
                });
            }
        });
    
    });
    
    $(document).ready(function()
    {
        $("#JOBPARENT_XX").change(function()
        {
            var PRJCODE     = document.getElementById("PRJCODE").value;
            var deptid1     = $(this).val();
            var deptid      = deptid1+'~'+PRJCODE;
            
            $.ajax({
                url: '<?php echo site_url('c_project/c_joblistdet/getCODEJOBLIST/?id=')?>',
                type: 'post',
                data: {depart:deptid},
                dataType: 'json',
                success:function(response)
                {
                    document.getElementById("JOBCODEID").value  = response;
                }
            });
        });
    
    });

    function submitForm()
    {
        JOBLEV      = $("#JOBLEV").val();
        JOBPARENT   = $("#JOBPARENT").val();
        JOBCODEID   = $("#JOBCODEID").val();
        JOBDESC     = $("#JOBDESC").val();
        JOBUNIT     = $("#JOBUNIT").val();
        
        if(JOBLEV == '')
        {
            swal('<?php echo $alert4; ?>',
            {
                icon: "warning",
            })
            .then(function()
            {
                $("#JOBLEV").focus();
                swal.close();
            });
            return false;
        }
        
        if(JOBLEV != '' && JOBLEV != 1)
        {
            if(JOBPARENT == '')
            {
                swal('<?php echo $alert5; ?>',
                {
                    icon: "warning",
                })
                .then(function()
                {
                    swal.close();
                });
                return false;
            }
        }

        if(JOBCODEID == '')
        {
            swal('<?php echo $alert6; ?>',
            {
                icon: "warning",
            })
            .then(function()
            {
                $("#JOBCODEID").focus();
                swal.close();
            });
            return false;
        }

        if(JOBDESC == '')
        {
            swal('<?php echo $alert7; ?>',
            {
                icon: "warning",
            })
            .then(function()
            {
                $("#JOBDESC").focus();
                swal.close();
            });
            return false;
        }

        if(JOBUNIT == '')
        {
            swal('<?php echo $alert8; ?>',
            {
                icon: "warning",
            })
            .then(function()
            {
                swal.close();
            });
            return false;
        }

        document.getElementById('btnSave').style.display    = 'none';
        document.getElementById('btnBack').style.display    = 'none';
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