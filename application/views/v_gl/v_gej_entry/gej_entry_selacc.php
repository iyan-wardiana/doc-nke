<?php
/* 
 * Author       = Dian Hermanto
 * Create Date  = 28 Februari 2017
 * File Name    = gej_entry_selacc.php
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

function cut_text($var, $len = 200, $txt_titik = "...") 
{
    $var1   = explode("</p>",$var);
    $var    = $var1[0];
    if (strlen ($var) < $len) 
    { 
        return $var; 
    }
    if (preg_match ("/(.{1,$len})\s/", $var, $match)) 
    {
        return $match [1] . $txt_titik;
    }
    else
    {
        return substr ($var, 0, $len) . $txt_titik;
    }
}
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

        $sqlTransl      = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
        $resTransl      = $this->db->query($sqlTransl)->result();
        foreach($resTransl as $rowTransl) :
            $TranslCode = $rowTransl->MLANG_CODE;
            $LangTransl = $rowTransl->LangTransl;
            
            if($TranslCode == 'Add')$Add = $LangTransl;
            if($TranslCode == 'Edit')$Edit = $LangTransl;
            if($TranslCode == 'AccountCode')$AccountCode = $LangTransl;
            if($TranslCode == 'Description')$Description = $LangTransl;
            if($TranslCode == 'BudgetQty')$BudgetQty = $LangTransl;
            if($TranslCode == 'StockQuantity')$StockQuantity = $LangTransl;
            if($TranslCode == 'Unit')$Unit = $LangTransl;
            if($TranslCode == 'Select')$Select = $LangTransl;
            if($TranslCode == 'Close')$Close = $LangTransl;
            if($TranslCode == 'PleaseSelectItem')$PleaseSelectItem = $LangTransl;
            if($TranslCode == 'Requested')$Requested = $LangTransl;
            if($TranslCode == 'AccountList')$AccountList = $LangTransl;
            if($TranslCode == 'ItemList')$ItemList = $LangTransl;
            if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
            if($TranslCode == 'Amount')$Amount = $LangTransl;
        endforeach;
        $ISCREATE   = 1;
        
        if(isset($_POST['submit1']))
        {
            $List_Type      = $this->input->post('List_Type');
            if($List_Type == 1)
            {
                $Active1        = "active";
                $Active2        = "";
                $Active1Cls     = "class='active'";
                $Active2Cls     = "";
            }
            else
            {
                $Active1        = "";
                $Active2        = "active";
                $Active1Cls     = "";
                $Active2Cls     = "class='active'";
            }
        }
        else
        {
            $List_Type      = 2;
            $Active1        = "";
            $Active2        = "active";
            $Active1Cls     = "";
            $Active2Cls     = "class='active'";
        }
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
                 <div class="col-md-9">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li <?php echo $Active2Cls; ?>><a href="#profPicture" data-toggle="tab" onClick="setType(2)"><?php echo $ItemList; ?></a></li>      <!-- Tab 2 -->
                            <li <?php echo $Active1Cls; ?>><a href="#settings" data-toggle="tab" onClick="setType(1)"><?php echo $AccountList; ?></a></li>      <!-- Tab 1 -->
                        </ul>
                        <!-- Biodata -->
                        <div class="tab-content">
                            <?php
                                if($List_Type == 2)
                                {
                                    ?>
                                        <div class="<?php echo $Active2; ?> tab-pane" id="profPicture">
                                          <div class="box box-success">
                                                <div>
                                                    &nbsp;
                                                </div>
                                                <div class="form-group">
                                                    <table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th width="2%">&nbsp;</th>
                                                                <th width="13%" nowrap><?php echo $AccountCode; ?></th>
                                                                <th width="13%" nowrap><?php echo $ItemCode; ?></th>
                                                                <th width="72%" nowrap><?php echo $Description; ?> </th>
                                                          </tr>
                                                        </thead>
                                                        <tbody>
                                                        </tbody>
                                                        <tfoot>
                                                        <tr>
                                                            <td colspan="4" nowrap>
                                                            <input type="hidden" name="rowCheck" id="rowCheck" value="">
                                                            <button class="btn btn-primary" type="button" onClick="get_item();">
                                                            <i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?></button> 
                                                            <button class="btn btn-danger" type="button" onClick="window.close()">
                                                            <i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?></button></td>
                                                        </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                }
                                elseif($List_Type == 1)
                                {
                                    ?>
                                        <div class="<?php echo $Active1; ?> tab-pane" id="settings">
                                            <div class="box box-success">
                                                <div>
                                                    &nbsp;
                                                </div>
                                                <div class="form-group">
                                                    <table id="example2" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th width="2%">&nbsp;</th>
                                                                <th width="20%" nowrap><?php echo $AccountCode; ?></th>
                                                                <th width="58%" nowrap><?php echo $Description; ?></th>
                                                                <th width="20%" nowrap><?php echo $Amount; ?></th>
                                                          </tr>
                                                        </thead>
                                                        <tbody>
                                                        </tbody>
                                                        <tfoot>
                                                        <tr>
                                                            <td colspan="4" nowrap>
                                                            <input type="hidden" name="rowCheck" id="rowCheck" value="">
                                                            <button class="btn btn-primary" type="button" onClick="get_item();">
                                                            <i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?></button> 
                                                            <button class="btn btn-danger" type="button" onClick="window.close()">
                                                            <i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?></button></td>
                                                        </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                }
                            ?>
                            <?php
                                $DefID      = $this->session->userdata['Emp_ID'];
                                $act_lnk    = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                                if($DefID == 'D15040004221')
                                    echo "<font size='1'><i>$act_lnk</i></font>";
                            ?>
                        </div>
                    </div>
                 </div>
            </div>
        </section>
    </body>
</html>
<?php
    $collData = "$PRJCODE~$THEROW";
?>
<script>
    $(function () 
    {
        $('#example1').DataTable(
        {
            "processing": true,
            "serverSide": true,
            //"scrollX": false,
            "autoWidth": true,
            "filter": true,
            "ajax": "<?php echo site_url('c_gl/cgeje0b28t18/get_AllDataITMS/?id='.$collData)?>",
            "type": "POST",
            //"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
            "lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
            "columnDefs": [ { targets: [0], className: 'dt-body-center' },
                            { "width": "100px", "targets": [1] }
                          ],
            "language": {
                "infoFiltered":"",
                "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
            },
        });
        $('#example2').DataTable(
        {
            "processing": true,
            "serverSide": true,
            //"scrollX": false,
            "autoWidth": true,
            "filter": true,
            "ajax": "<?php echo site_url('c_gl/cgeje0b28t18/get_AllDataCOA/?id='.$collData)?>",
            "type": "POST",
            //"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
            "lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
            "columnDefs": [ { targets: [0], className: 'dt-body-center' },
                            { targets: [3], className: 'dt-body-right' },
                            { "width": "100px", "targets": [1] }
                          ],
            "language": {
                "infoFiltered":"",
                "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
            },
        });
    });
      
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
  
    var selectedRows = 0;
    
    function pickThis(thisobj) 
    {
        var NumOfRows = document.frmSearch.chk.length; // minus 1 because it's the header
        if (thisobj!= '') 
        {
            if (thisobj.checked) selectedRows++;
            else selectedRows--;
        }
        if (selectedRows==NumOfRows) 
        {
            document.frmSearch.ChkAllItem.checked = true;
        }
        else
        {
            document.frmSearch.ChkAllItem.checked = false;
        }
    }
    
    function getRow(thisobj) 
    {
        document.getElementById('rowCheck').value = thisobj;
    }
    
    function get_item() 
    {
        rowChecked  = document.getElementById('rowCheck').value;
        window.opener.add_item(document.getElementById('chk_'+rowChecked).value);
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