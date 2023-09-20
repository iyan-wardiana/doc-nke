<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 6 Mei 2018
 * File Name	= v_weekly_prog_seljob.php
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

	<?php
		$LangID 		= $this->session->userdata['LangID'];

		$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
		$resTransl		= $this->db->query($sqlTransl)->result();
		foreach($resTransl as $rowTransl) :
			$TranslCode	= $rowTransl->MLANG_CODE;
			$LangTransl	= $rowTransl->LangTransl;
			
			if($TranslCode == 'Add')$Add = $LangTransl;
			if($TranslCode == 'Edit')$Edit = $LangTransl;
			if($TranslCode == 'JobCode')$JobCode = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'Volume')$Volume = $LangTransl;
			if($TranslCode == 'Progress')$Progress = $LangTransl;
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
			if($TranslCode == 'Percentation')$Percentation = $LangTransl;
		endforeach;
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
		                    <li <?php echo $Active2Cls; ?>><a href="#profPicture" data-toggle="tab" onClick="setType(2)"><?php echo $ItemList; ?></a></li>		<!-- Tab 2 -->
		                    <li <?php echo $Active1Cls; ?> style="display:none"><a href="#settings" data-toggle="tab" onClick="setType(1)"><?php echo $AccountList; ?></a></li> 		<!-- Tab 1 -->
		                </ul>
		                <!-- Biodata -->
		                <div class="tab-content">
		                	<?php
								$List_Type = 2;
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
		                                                        <th width="13%" style="text-align:center" nowrap><?php echo $JobCode; ?></th>
		                                                        <th width="58%" style="text-align:center" nowrap><?php echo $Description; ?></th>
		                                                        <th width="12%" style="text-align:center" nowrap><?php echo $Volume; ?></th>
		                                                        <th width="7%" style="text-align:center" nowrap><?php echo $Progress; ?> </th>
		                                                        <th width="8%" style="text-align:center" nowrap><?php echo $Percentation; ?></th>
		                                                  </tr>
		                                                </thead>
		                                                <tbody>
		                                                <?php
		                                                    $i = 0;
		                                                    $j = 0;
		                                                    if($countAllJob>0)
		                                                    {
		                                                        $totRow	= 0;
		                                                        foreach($vwAllJob as $row) :
		                                                            $JOBCODEID 		= $row->JOBCODEID;
		                                                            $JOBCODEIDV 	= $row->JOBCODEIDV;
		                                                            $PRJCODE 		= $row->PRJCODE;
		                                                            $JOBDESC 		= $row->JOBDESC;
																	$JOBVOLM		= $row->JOBVOLM;	// Budget Volume
																	$PRICE			= $row->PRICE;
		                                                            $JOBCOST 		= $row->JOBCOST;	// Budget Cost
																	if($JOBCOST == 0)
																		$JOBCOST	= 1;
		                                                            $JOBUNIT 		= $row->JOBUNIT;															
																	$BOQ_VOLM		= $row->BOQ_VOLM;	// Budget Volume
																		
																	$BOQ_PRICE		= $row->BOQ_PRICE;
		                                                            $BOQ_JOBCOST	= $row->BOQ_JOBCOST;// Budget Cost
		                                                            $BOQ_BOBOT		= $row->BOQ_BOBOT;	// Bobot
																	
																	if($JOBUNIT == 'LS')
																	{
																		$BOQ_VOLM	= $BOQ_JOBCOST;
																	}
																	if($BOQ_VOLM == 0 || $BOQ_VOLM == '')
																		$BOQ_VOLM	= 1;
																	
																	// GET PROGRESS BEFORE
																	$TOTBEF_VAL		= 0;
																	$sqlJOBBEF		= "SELECT SUM(A.PROG_VAL) AS TOTPROGBEF_VAL
																						FROM tbl_project_progress_det A
																							INNER JOIN 	tbl_project_progress B
																								ON A.PRJP_NUM = B.PRJP_NUM
																						WHERE A.JOBCODEID = '$JOBCODEID' AND A.PRJCODE = '$PRJCODE'
																							AND B.PRJP_STAT = 3";
		                                                            $resJOBBEF		= $this->db->query($sqlJOBBEF)->result();
		                                                            foreach($resJOBBEF as $rowTOTBEF) :
		                                                                $TOTBEF_VAL	= $rowTOTBEF->TOTPROGBEF_VAL;
		                                                            endforeach;
																	if($TOTBEF_VAL == '')
																		$TOTBEF_VAL	= 0;
																	
																	$JOBCOST_PROG	= $TOTBEF_VAL * $PRICE;
																	$JOBCOST_PROG_P	= $JOBCOST_PROG / $JOBCOST; // Percentation Progress
																	
																	$TOTBEF_VAL_P	= ($TOTBEF_VAL / $BOQ_VOLM) * 100;
		                                                            $totRow		= $totRow + 1;
																	
																	if($TOTBEF_VAL_P == 100)
																	{
																		$STATCOL		= 'success';
																		$TOTBEF_VAL_PV	= number_format($TOTBEF_VAL_P, 0);
																		$disable		= 1;
																	}
																	elseif($TOTBEF_VAL_P >= 80)
																	{
																		$STATCOL		= 'info';
																		$TOTBEF_VAL_PV	= number_format($TOTBEF_VAL_P, 3);
																		$disable		= 0;
																	}
																	elseif($TOTBEF_VAL_P >= 50)
																	{
																		$STATCOL		= 'primary';
																		$TOTBEF_VAL_PV	= number_format($TOTBEF_VAL_P, 3);
																		$disable		= 0;
																	}
																	elseif($TOTBEF_VAL_P >= 25)
																	{
																		$STATCOL		= 'warning';
																		$TOTBEF_VAL_PV	= number_format($TOTBEF_VAL_P, 3);
																		$disable		= 0;
																	}
																	else
																	{
																		$STATCOL		= 'danger';
																		$TOTBEF_VAL_PV	= number_format($TOTBEF_VAL_P, 3);
																		$disable		= 0;
																	}
		                                                        
		                                                            if ($j==1) {
		                                                                echo "<tr class=zebra1>";
		                                                                $j++;
		                                                            } else {
		                                                                echo "<tr class=zebra2>";
		                                                                $j--;
		                                                            }
		                                                            ?>
		                                                            <td style="text-align:center">
		                                                            	<input type="radio" name="chk" id="chk_<?php echo $totRow; ?>" value="<?php echo $JOBCODEID;?>|<?php echo $JOBCODEIDV;?>|<?php echo $JOBDESC;?>|<?php echo $BOQ_VOLM;?>|<?php echo $BOQ_PRICE;?>|<?php echo $BOQ_JOBCOST;?>|<?php echo $JOBCOST_PROG;?>|<?php echo $JOBCOST_PROG_P;?>|<?php echo $BOQ_BOBOT;?>|<?php echo $TOTBEF_VAL;?>|<?php echo $JOBUNIT;?>|<?php echo $THEROW;?>" onClick="getRow('<?php echo $totRow; ?>')" <?php if($disable == 1) { ?> disabled <?php } ?>  />
		                                                            </td>
		                                                            <td><?php echo $JOBCODEIDV; ?></td>
		                                                            <td><?php echo $JOBDESC; ?></td>
		                                                            <td style="text-align:right" nowrap><?php echo number_format($BOQ_VOLM, 2); ?></td>
		                                                            <td nowrap style="text-align:right"><?php echo number_format($TOTBEF_VAL, 2); ?></td>
		                                                            <td nowrap style="text-align:right">
		                                                            	<span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
																			<?php echo $TOTBEF_VAL_PV; ?> %
		                                                                </span>
		                                                            </td>
		                                                            </tr>
		                                                        <?php
		                                                        endforeach;
		                                                    }
		                                                ?>
		                                                </tbody>
		                                                <tr>
		                                                  <td colspan="6" nowrap>
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
		                                                        <th width="14%" nowrap><?php echo $JobCode; ?></th>
		                                                        <th width="70%" nowrap><?php echo $Progress; ?></th>
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
		                                                            	<input type="radio" name="chk" id="chk_<?php echo $totRow; ?>" value="<?php echo $Account_Number;?>|<?php echo $Account_NameId;?>|<?php echo $ITM_CODE;?>|<?php echo $THEROW;?>" onClick="getRow('<?php echo $totRow; ?>')" <?php if($isDisabled == 1) { ?> disabled <?php } ?>/>
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
		                </div>
		            </div>
				 </div>
			</div>
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