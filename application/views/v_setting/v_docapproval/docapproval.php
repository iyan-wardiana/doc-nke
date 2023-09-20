<?php
/* 
    * Author        = Dian Hermanto
    * Create Date   = 25 Januari 2018
    * File Name = docapproval.php
    * Location      = -
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
            
            if($TranslCode == 'Add')$Add = $LangTransl;
            if($TranslCode == 'Edit')$Edit = $LangTransl;
            if($TranslCode == 'ApprovalName')$ApprovalName = $LangTransl;
            if($TranslCode == 'Project')$Project = $LangTransl;
            if($TranslCode == 'Department')$Department = $LangTransl;
            if($TranslCode == 'Approver')$Approver = $LangTransl;

        endforeach;

        if($LangID == 'IND')
        {
            $sureDelete     = "Anda yakin akan menghapus pengaturan persetujuan ini?";
        }
        else
        {
            $sureDelete     = "Are you sure want to delete this approval setting?";
        }

        $comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
        <section class="content-header">
            <h1>
                <?php echo $h2_title; ?>
                <small>setting</small>
                <div class="pull-right">
                    <?php
                        $secAddURL = site_url('c_setting/c_docapproval/add/?id='.$this->url_encryption_helper->encode_url($appName));
                        
                        echo anchor("$secAddURL",'<button class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i></button>&nbsp;');
                    ?>
                    <input type="hidden" name="myMR_Number" id="myMR_Number" value="" />
                </div>
            </h1>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <i class="glyphicon glyphicon-list"></i>
                            <h3 class="box-title">Pengelompokan Persetujuan</h3>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <select name="MENU_CODE" id="MENU_CODE" class="form-control select2" style="width: 100%" onChange="grpData(this.value)">
                                                <option value=""> --- </option>
                                                <?php
                                                    $s_00   = "SELECT DISTINCT MENU_CODE, DOCAPP_NAME FROM tbl_docstepapp ORDER BY DOCAPP_NAME";
                                                    $r_00   = $this->db->query($s_00)->result();
                                                    foreach($r_00 as $rw_00) :
                                                        $MENU_CODE      = $rw_00->MENU_CODE;
                                                        $DOCAPP_NAME    = $rw_00->DOCAPP_NAME;
                                                        ?>
                                                        <option value="<?php echo $MENU_CODE; ?>"><?php echo "$MENU_CODE - $DOCAPP_NAME"; ?></option>
                                                        <?php
                                                    endforeach;
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <select name="PRJCODE" id="PRJCODE" class="form-control select2" style="width: 100%" onChange="grpData(this.value)">
                                                <option value=""> --- </option>
                                                <?php
                                                    $s_00   = "SELECT DISTINCT A.PRJCODE, B.PRJNAME FROM tbl_docstepapp A
                                                                INNER JOIN tbl_project B ON A.PRJCODE = B.PRJCODE
                                                                ORDER BY B.PRJLEV, A.PRJCODE ASC";
                                                    $r_00   = $this->db->query($s_00)->result();
                                                    foreach($r_00 as $rw_00) :
                                                        $PRJCODE    = $rw_00->PRJCODE;
                                                        $PRJNAME    = $rw_00->PRJNAME;
                                                        ?>
                                                        <option value="<?php echo $PRJCODE; ?>"><?php echo "$PRJCODE - $PRJNAME"; ?></option>
                                                        <?php
                                                    endforeach;
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <select name="TOPRJCODE" id="TOPRJCODE" class="form-control select2">
                                                <option value=""></option>
                                                <?php
                                                    $s_00   = "SELECT PRJCODE, PRJNAME FROM tbl_project
                                                                WHERE PRJSTAT = 1
                                                                ORDER BY PRJLEV, PRJCODE ASC";
                                                    $r_00   = $this->db->query($s_00)->result();
                                                    foreach($r_00 as $rw_00) :
                                                        $PRJCODE    = $rw_00->PRJCODE;
                                                        $PRJNAME    = $rw_00->PRJNAME;
                                                        ?>
                                                        <option value="<?php echo $PRJCODE; ?>"><?php echo "$PRJCODE - $PRJNAME"; ?></option>
                                                        <?php
                                                    endforeach;
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-1">
                                        <div class="form-group">
                                            <button class="btn btn-warning" id="copyToPRJ" data-loading-text="Loading...">
                                                <i class="fa fa-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <script type="text/javascript">
                function grpData()
                {
                    var MNCODE      = document.getElementById('MENU_CODE').value;
                    var PRJCODE     = document.getElementById('PRJCODE').value;

                    $('#example').DataTable( {
                        "destroy": true,
                        "processing": true,
                        "serverSide": true,
                        //"scrollX": false,
                        "autoWidth": true,
                        "filter": true,
                        "ajax": "<?php echo site_url('c_setting/c_docapproval/get_AllDataGRP/?id=')?>"+MNCODE+'&PRJCODE='+PRJCODE,
                        "type": "POST",
                        //"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
                        "lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
                        "columnDefs": [ { targets: [0,2], className: 'dt-body-center' },
                                        { "width": "100px", "targets": [1] }
                                      ],
                        "order": [[ 1, "desc" ]],
                        "language": {
                            "infoFiltered":"",
                            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
                        },
                    } );
                }
            </script>

            <div class="box box-success">
                <div class="box-body">
                    <div class="search-table-outter">
                        <table id="example" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
                            <thead>
                            <tr>
                                <th width="5%" nowrap style="text-align:center">ID</th>
                                <th width="10%" nowrap><?php echo $ApprovalName ?> </th>
                                <th width="5%"><?php echo $Project ?></th>
                                <th width="10%"><?php echo $Department ?></th>
                                <th width="10%"><?php echo "Level Jabatan" ?></th>
                                <th width="65%"><?php echo $Approver ?> </th>
                                <th width="5%">&nbsp;</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            </tfoot>
                        </table>
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
    $(function ()
    {
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
        $.fn.datepicker.defaults.format = "dd/mm/yyyy";
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

        $('#TOPRJCODE').select2({
            placeholder: "Please copy to other project"
        });

        $('#copyToPRJ').on('click', function(){
            let MNCODE      = $('#MENU_CODE').val();
            let PRJCODE     = $('#PRJCODE').val();
            let TOPRJCODE   = $('#TOPRJCODE').val();

            swal({
                text: "Proses copy ini akan menghapus setting approval sebelumnya, yakin?",
                icon: "warning",
                buttons: ["Tidak","Yakin"],
            }).then((confirm) => {
                if(confirm) {
                    $.ajax({
                        url: "<?php echo site_url('c_setting/c_docapproval/procCopyTOPRJ') ?>",
                        data: {MENU_CODE:MNCODE, PRJCODE:PRJCODE, TOPRJCODE:TOPRJCODE},
                        type: "POST",
                        dataType: "JSON",
                        error: function(xhr, status, err) {
                            console.log("status "+err);
                        },
                        success: function(result) {
                            console.log(result);
                            let DOCAPP_NAME     = result[0].DOCAPP_NAME;
                            let DOCAPP_PRJ      = result[0].PRJCODE;

                            swal(DOCAPP_NAME+" berhasil dicopy ke kode proyek "+DOCAPP_PRJ, {
                                icon: "success",
                            }).then(function(e){
                                swal.close();
                                $('#example').DataTable().ajax.reload();
                            });
                        }
                    })
                }
            });
        });
    });

    $(document).ready(function() {
    $('#example').DataTable( {
        "processing": true,
        "serverSide": true,
        //"scrollX": false,
        "autoWidth": true,
        "filter": true,
        "ajax": "<?php echo site_url('c_setting/c_docapproval/get_AllData/')?>",
        "type": "POST",
        //"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
        "lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
        "columnDefs": [ { targets: [0,2,4], className: 'dt-body-center' },
                        { "width": "100px", "targets": [1] }
                      ],
        "language": {
            "infoFiltered":"",
            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
        },
        } );
    } );
    
    function deleteSett(row)
    {
        swal({
            text: "<?php echo $sureDelete; ?>",
            icon: "warning",
            buttons: ["No", "Yes"],
        })
        .then((willDelete) => 
        {
            if (willDelete) 
            {
                var collID  = document.getElementById('urlDel'+row).value;
                var myarr   = collID.split("~");

                var url     = myarr[0];

                $.ajax({
                    type: 'POST',
                    url: url,
                    data: {collID: collID},
                    success: function(response)
                    {
                        swal(response, 
                        {
                            icon: "success",
                        });
                        $('#example').DataTable().ajax.reload();
                    }
                });
            } 
            else 
            {
                /*swal("<?php echo $cancDel; ?>", 
                {
                    icon: "error",
                });*/
            }
        });
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