<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 28 Februari 2017
 * File Name	= gej_entry_selacc.php
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

function cut_text($var, $len = 200, $txt_titik = "...") 
{
	$var1	= explode("</p>",$var);
	$var	= $var1[0];
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
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() . 'assets/css/spritecss.css'; ?>">
</head>

<?php
	$LangID 		= $this->session->userdata['LangID'];

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
		if($TranslCode == 'MCCode')$MCCode = $LangTransl;
		if($TranslCode == 'Code')$Code = $LangTransl;
		if($TranslCode == 'Date')$Date = $LangTransl;
		if($TranslCode == 'MCCode')$MCCode = $LangTransl;
		if($TranslCode == 'MCNumber')$MCNumber = $LangTransl;
		if($TranslCode == 'InvoiceNumber')$InvoiceNumber = $LangTransl;
		if($TranslCode == 'MCDate')$MCDate = $LangTransl;
		if($TranslCode == 'PrestationVal')$PrestationVal = $LangTransl;
		if($TranslCode == 'ReceivedAmount')$ReceivedAmount = $LangTransl;
		if($TranslCode == 'Notes')$Notes = $LangTransl;
		if($TranslCode == 'Select')$Select = $LangTransl;
		if($TranslCode == 'Close')$Close = $LangTransl;
		if($TranslCode == 'MCList')$MCList = $LangTransl;
		if($TranslCode == 'MCListGroup')$MCListGroup = $LangTransl;
	endforeach;
	
	if($LangID == 'IND')
	{
		$selectMC	= "Silahkan pilih salah satu MC di bawah ini.";
	}
	else
	{
		$selectMC	= "Please select one of MC Number below.";
	}
		
	$ISCREATE	= 1;
	
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
		$Active1		= "";
		$Active2		= "active";
		$Active1Cls		= "";
		$Active2Cls		= "class='active'";
	}
?>

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">
</section>
<style>
	.search-table, td, th {
		border-collapse: collapse;
	}
	.search-table-outter { overflow-x: scroll; }
</style>
<!-- Main content -->

<form class="form-horizontal" name="frm_01" method="post" action="" style="display:none">
	<input type="text" name="List_Type" id="List_Type" value="<?php echo $List_Type; ?>" />
    <input type="submit" class="button_css" name="submit1" id="submit1" value="Submit" align="left" />
</form>
<section class="content">
	<div class="row">
    	 <div class="col-md-9">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li <?php echo $Active2Cls; ?>><a href="#profPicture" data-toggle="tab" onClick="setType(2)"><?php echo $MCList; ?></a></li>		<!-- Tab 2 -->
                    <li <?php echo $Active1Cls; ?>><a href="#settings" data-toggle="tab" onClick="setType(1)"><?php echo $MCListGroup; ?></a></li> 		<!-- Tab 1 -->
                </ul>
                <!-- Biodata -->
                <div class="tab-content">
                    <form method="post" name="frmSearch" action="">
                        <input type="TEXT" name="MC_REF2" id="MC_REF2" value="" size="50" />
                        <input type="TEXT" name="PINV_MMC" id="PINV_MMC" value="0" size="1" />
                        <input type="TEXT" name="MC_TOTVAL1" id="MC_TOTVAL1" value="0" size="10" />
                        <?php
                            if($List_Type == 2)
                            {
                                $sqlSrc 		= "tbl_mcheader A
                                                    WHERE A.PRJCODE = '$PRJCODE' AND MC_STAT = 3 AND MC_ISINV = 0
                                                    ORDER BY A.MC_MANNO ASC";				
                                $countMC 		= $this->db->count_all($sqlSrc);
                                $sql 			= "SELECT A.*
                                                    FROM tbl_mcheader A
                                                    WHERE A.PRJCODE = '$PRJCODE' AND MC_STAT = 3 AND MC_ISINV = 0
                                                    ORDER BY A.MC_MANNO ASC";
                                $viewAllMC 	= $this->db->query($sql)->result();
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
                                                            <th width="2%" style="text-align:center">&nbsp;</th>
                                                            <th width="11%" style="text-align:center; display:none"><?php echo $MCCode ?></th>
                                                            <th width="11%" style="text-align:center"><?php echo $MCNumber ?></th>
                                                            <th width="11%" style="text-align:center; display:none"><?php echo $InvoiceNumber ?></th>
                                                            <th width="18%" style="text-align:center"><?php echo $MCDate ?></th>
                                                            <th width="15%" style="text-align:center"><?php echo $PrestationVal ?></th>
                                                            <th width="15%" style="text-align:center" nowrap>Total<br>
                                                            (inc. PPn - PPh)</th>
                                                            <th width="43%" style="text-align:center"><?php echo $Notes ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
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
                                                                $MC_PROG 		= $row->MC_PROG;		// 6
                                                                $MC_PROGVAL		= $row->MC_PROGVAL;		// 7
                                                                $MC_VALADD 		= $row->MC_VALADD;		// 8
                                                                $MC_MATVAL 		= $row->MC_MATVAL;		// 9
                                                                $MC_DPPER 		= $row->MC_DPPER;		// 10
                                                                $MC_DPVAL		= $row->MC_DPVAL;		// 11
                                                                $MC_DPBACK 		= $row->MC_DPBACK;		// 12
                                                                $MC_RETCUT 		= $row->MC_RETCUT;		// 13
                                                                $MC_PROGAPP		= $row->MC_PROGAPP;		// 14
                                                                $MC_PROGAPPVAL	= $row->MC_PROGAPPVAL;	// 15
                                                                $MC_AKUMNEXT 	= $row->MC_AKUMNEXT;	// 16
                                                                $MC_VALBEF 		= $row->MC_VALBEF;		// 17
                                                                $MC_TOTVAL 		= $row->MC_TOTVAL;
                                                                $MC_TOTVAL_PPn 	= $row->MC_TOTVAL_PPn;
                                                                $MC_TOTVAL_PPh	= $row->MC_TOTVAL_PPh;
                                                                $GMC_TOTVAL		= $MC_TOTVAL+$MC_TOTVAL_PPn-$MC_TOTVAL_PPh;	// 18
                                                                $MC_NOTES 		= $row->MC_NOTES;		// 19
                                                                $MC_OWNER		= $row->MC_OWNER;
                                                                $MC_APPSTAT		= $row->MC_APPSTAT;
                                                                $currow			= $currow + 1;
                                                                $idx			= $idx + 1;
                                                                
                                                                    $MC_TOTPROGRESS	= $MC_PROGVAL + $MC_VALADD + $MC_MATVAL;
                                                                    $MC_PAYBEFRET	= $MC_TOTPROGRESS + $MC_DPVAL - $MC_DPBACK - $MC_RETCUT;
                                                                    $MC_PAYAKUM		= $MC_PAYBEFRET;
                                                                    $MC_PAYMENT		= $MC_PAYAKUM - $MC_VALBEF;
                                                                    $MC_PAYDUE		= $MC_PAYMENT + round(0.1 * $MC_PAYMENT);
                                                                    $MC_PAYDUEPPh	= round(0.03 * $MC_PAYMENT);
                                                                    $TOTPAYMENT		= $MC_PAYDUE - $MC_PAYDUEPPh;
                                                                
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
                                                                        <td style="text-align:center" nowrap><input type="radio" name="chk" id="chk" value="<?php echo $pageFrom;?>|<?php echo $MC_CODE;?>|<?php echo $MC_MANNO;?>|<?php echo $MC_DATE;?>|<?php echo $MC_ENDDATE;?>|<?php echo $MC_RETVAL;?>|<?php echo $MC_PROG;?>|<?php echo $MC_PROGVAL;?>|<?php echo $MC_VALADD;?>|<?php echo $MC_MATVAL;?>|<?php echo $MC_DPPER;?>|<?php echo $MC_DPVAL;?>|<?php echo $MC_DPBACK;?>|<?php echo $MC_RETCUT;?>|<?php echo $MC_PROGAPP;?>|<?php echo $MC_PROGAPPVAL;?>|<?php echo $MC_AKUMNEXT;?>|<?php echo $MC_VALBEF;?>|<?php echo $MC_TOTVAL;?>|<?php echo $MC_NOTES;?>|<?php echo $MC_OWNER;?>" onClick="pickThis(this, '<?php echo $currow; ?>');" <?php if($MC_APPSTAT == 1) { ?> disabled <?php } ?> /></td>
                                                                        <td style="display:none" nowrap><?php echo "$MC_MANNO"; ?></td>
                                                                        <td nowrap>
                                                                            <?php echo "$MC_MANNO"; ?>
                                                                            <input type="hidden" name="MC_CODE<?php echo $idx; ?>" id="MC_CODE<?php echo $idx; ?>" value="<?php echo "$MC_CODE"; ?>" />
                                                                            <input type="hidden" name="MC_TOTVAL<?php echo $idx; ?>" id="MC_TOTVAL<?php echo $idx; ?>" value="<?php echo "$MC_TOTVAL"; ?>" />                                    </td>
                                                                        <td style="display:none" nowrap><?php echo "$PINV_MANNO"; ?></td>
                                                                        <td><?php echo $MC_DATE; ?></td>
                                                                        <td style="text-align:right"><?php print number_format($MC_TOTVAL, $decFormat); ?></td>
                                                                        <td style="text-align:right"><?php print number_format($TOTPAYMENT, $decFormat); ?></td>
                                                                        <td><?php echo $MC_NOTES; ?></td>
                                                                </tr>
                                                            <?php
                                                            endforeach;
                                                        }
                                                    ?>
                                                    </tbody>
                                                    <tr>
                                                      <td colspan="4" nowrap>
                                                        <input type="hidden" name="rowCheck" id="rowCheck" value="">
                                                        <button class="btn btn-primary" type="button" onClick="get_item();">
                                                        <i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?>                    </button> 
                                                        <button class="btn btn-danger" type="button" onClick="window.close()">
                                                        <i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>                    </button>                  </td>
                                                    </tr>
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
                                                <table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th width="2%">&nbsp;</th>
                                                            <th width="14%" nowrap><?php echo $AccountCode; ?></th>
                                                            <th width="70%" nowrap><?php echo $Description; ?></th>
                                                            <th width="14%" nowrap><?php echo $Amount; ?></th>
                                                      </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                        $i = 0;
                                                        $j = 0;
                                                        if($countAllCOA>0)
                                                        {
                                                            $totRow		= 0;
                                                            foreach($vwAllCOA as $row) :
                                                                $isDisabled			= 0;
                                                                $Account_Number 	= $row->Account_Number;		// 0
                                                                $Account_NameEn		= $row->Account_NameEn;
                                                                $Account_NameId		= $row->Account_NameId;		// 1
                                                                $PRJCODE 			= $row->PRJCODE;
                                                                $Account_Class		= $row->Account_Class;
                                                                $ITM_CATEG			= '';
                                                                
                                                                $Base_OpeningBalance= $row->Base_OpeningBalance;
                                                                $Base_Debet 		= $row->Base_Debet;
                                                                $Base_Kredit 		= $row->Base_Kredit;
                                                                $balanceVal 		= $Base_OpeningBalance + $Base_Debet - $Base_Kredit;
                                                                
                                                                //if($Account_Class == 4 && $balanceVal == 0)
                                                                    //$isDisabled		= 1;
                                                                
                                                                $ITM_CODE			= '';
                                                                
                                                                $totRow		= $totRow + 1;
                                                            
                                                                if ($j==1) {
                                                                    echo "<tr class=zebra1>";
                                                                    $j++;
                                                                } else {
                                                                    echo "<tr class=zebra2>";
                                                                    $j--;
                                                                }
                                                                ?>
                                                                <td style="text-align:center">
                                                                    <input type="radio" name="chk" id="chk_<?php echo $totRow; ?>" value="<?php echo $Account_Number;?>|<?php echo $Account_NameId;?>|<?php echo $ITM_CODE;?>|<?php echo $ITM_CATEG;?>|<?php echo $THEROW;?>" onClick="getRow('<?php echo $totRow; ?>')" <?php if($isDisabled == 1) { ?> disabled <?php } ?>/>
                                                                </td>
                                                                <td><?php echo $Account_Number; ?></td>
                                                                <td nowrap><?php echo cut_text($Account_NameId, 50); ?></td>
                                                                <td style="text-align:right" nowrap><?php echo number_format($balanceVal, 2); ?></td>
                                                                </tr>
                                                            <?php
                                                            endforeach;
                                                        }
                                                    ?>
                                                    </tbody>
                                                    <tr>
                                                        <tfoot>
                                                          <td colspan="4" nowrap>
                                                            <input type="hidden" name="rowCheck" id="rowCheck" value="">
                                                            <button class="btn btn-primary" type="button" onClick="get_item();">
                                                            <i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?>                    </button> 
                                                            <button class="btn btn-danger" type="button" onClick="window.close()">
                                                            <i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>                    </button>                  									</td>
                                                        </tfoot>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                            }
                        ?>
                    </form>
                </div>
            </div>
		 </div>
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
		rowChecked	= document.getElementById('rowCheck').value;
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
//$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
//$this->load->view('template/foot');
?>