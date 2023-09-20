<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 26 Juni 2018
 * File Name	= asset_exp_selitem.php
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
		if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
		if($TranslCode == 'ItemName')$ItemName = $LangTransl;
		if($TranslCode == 'AssetCapacity')$AssetCapacity = $LangTransl;
		if($TranslCode == 'StockQuantity')$StockQuantity = $LangTransl;
		if($TranslCode == 'Unit')$Unit = $LangTransl;
		if($TranslCode == 'Select')$Select = $LangTransl;
		if($TranslCode == 'Close')$Close = $LangTransl;
		if($TranslCode == 'JobName')$JobName = $LangTransl;
		if($TranslCode == 'Budget')$Budget = $LangTransl;
		if($TranslCode == 'PerMonth')$PerMonth = $LangTransl;
		if($TranslCode == 'ItemList')$ItemList = $LangTransl;
		if($TranslCode == 'SelAsset')$SelAsset = $LangTransl;
		if($TranslCode == 'Used')$Used = $LangTransl;
		if($TranslCode == 'Remain')$Remain = $LangTransl;
	endforeach;
	
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
    	 <div class="col-md-12">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li <?php echo $Active2Cls; ?>><a href="#profPicture" data-toggle="tab" onClick="setType(2)"><?php echo $ItemList; ?></a></li>
                    <li <?php echo $Active1Cls; ?>><a href="#settings" data-toggle="tab" onClick="setType(1)"><?php echo $SelAsset; ?></a></li>
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
                                        	<form method="post" name="frmSearch" action="">
                                                <table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th width="3%"><input type="checkbox" name="ChkAllItem" id="ChkAllItem" onClick="check_all(this)" style="display:none" /></th>
                                                            <th width="9%" style="text-align:center; vertical-align:middle" nowrap><?php echo $ItemCode; ?></th>
                                                            <th width="53%" style="text-align:center; vertical-align:middle" nowrap><?php echo $JobName; ?> </th>
                                                            <th width="15%" style="text-align:center; vertical-align:middle" nowrap><?php echo $ItemName; ?></th>
                                                            <th width="10%" style="text-align:center; vertical-align:middle" nowrap>Unit</th>
                                                            <th width="10%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Budget; ?></th>
                                                            <th width="10%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Used; ?></th>
                                                            <th width="10%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Remain; ?></th>
                                                      </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                        $i = 0;
                                                        $j = 0;
                                                        if($cAllItem>0)
                                                        {
                                                            $totRow	= 0;
                                                            foreach($vwAllItem as $row) :
                                                                $JOBCODEDET 	= $row->JOBCODEDET;		// 
                                                                $JOBCODEID 		= $row->JOBCODEID;		// 
                                                                $JOBDESC 		= $row->JOBDESC;		// 
                                                                $PRJCODE 		= $row->PRJCODE;		// 
                                                                $ITM_CODE 		= $row->ITM_CODE;		// 
                                                                $ITM_NAME		= $row->ITM_NAME;		// 
                                                                $ITM_UNIT 		= $row->ITM_UNIT;		// 
                                                                $ITM_VOLM 		= $row->ITM_VOLM;		// 
                                                                $ITM_PRICE 		= $row->ITM_PRICE;		// 
                                                                $ADD_VOLM 		= $row->ADD_VOLM;		// 
                                                                $ADD_PRICE 		= $row->ADD_PRICE;		// 
																$ITM_TOTAL		= $ITM_VOLM * $ITM_PRICE;
																$ITM_TOTALADD	= $ADD_VOLM * $ADD_PRICE;
                                                                $ITM_USED_AM 	= $row->ITM_USED_AM;	// 
                                                                $ITM_REMAM 		= $ITM_TOTAL + $ITM_TOTALADD - $ITM_USED_AM;	// 
                                                                
                                                                $disabledB		= 0;
                                                                if($ITM_REMAM <= 0)
                                                                {
                                                                    $disabledB	= 1;
                                                                }
                                                                $totRow		= $totRow + 1;
                                                            
                                                                if ($j==1) {
                                                                    echo "<tr class=zebra1>";
                                                                    $j++;
                                                                } else {
                                                                    echo "<tr class=zebra2>";
                                                                    $j--;
                                                                }
                                                                ?>
                                                                <td style="text-align:center"><input type="checkbox" name="chk" value="<?php echo $THEROW;?>|<?php echo $ITM_CODE;?>" onClick="pickThis(this);" <?php if($disabledB == 1) { ?> disabled <?php } ?> /></td>
                                                                <td><?php echo $ITM_CODE; ?></td>
                                                                <td nowrap><?php echo $JOBDESC; ?></td>
                                                                <td nowrap><?php echo $ITM_NAME; ?></td>
                                                                <td nowrap style="text-align:center"><?php echo $ITM_UNIT; ?></td>
                                                                <td nowrap style="text-align:right"><?php echo number_format($ITM_TOTAL, $decFormat); ?></td>
                                                                <td nowrap style="text-align:right"><?php echo number_format($ITM_USED_AM, $decFormat); ?></td>
                                                                <td nowrap style="text-align:right"><?php echo number_format($ITM_REMAM, $decFormat); ?></td>
                                                                </tr>
                                                                <?php
                                                            endforeach;
                                                        }
                                                    ?>
                                                    </tbody>
                                                    <tr>
                                                      <td colspan="6" nowrap>
                                                        <button class="btn btn-primary" type="button" onClick="get_item();">
                                                        <i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?>                    </button> 
                                                        <button class="btn btn-danger" type="button" onClick="window.close()">
                                                        <i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>                    </button>                  </td>
                                                    </tr>
                                                </table>
                                            </form>
                                      	</div>
                                    </div>
                                </div>
                            <?php
						}
						elseif($List_Type == 1)
						{
							//
						}
					?>
                </div>
            </div>
		 </div>
	</div>
</section>
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
function check_all(chk) {
	if(chk.checked) {
		if(typeof(document.frmSearch.chk[0]) == 'object') {
			for(i=0;i<document.frmSearch.chk.length;i++) {
				document.frmSearch.chk[i].checked = true;
			}
		} else {
			document.frmSearch.chk.checked = true;
		}
		selectedRows = document.frmSearch.chk.length;
	} else {
		if(typeof(document.frmSearch.chk[0]) == 'object') {
			for(i=0;i<document.frmSearch.chk.length;i++) {
				document.frmSearch.chk[i].checked = false;
			}
		} else {
			document.frmSearch.chk.checked = false;
		}
		selectedRows = 0;
	}
}

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
	
function get_item() 
{ 
	//alert(document.frmSearch.chk.length) 
	if(typeof(document.frmSearch.chk[0]) == 'object') 
	{
		for(i=0;i<document.frmSearch.chk.length;i++) 
		{
			if(document.frmSearch.chk[i].checked) 
			{
				A = document.frmSearch.chk[i].value
				arrItem = A.split('|');
				arrparent = document.frmSearch.chk[i].value.split('|');

				window.opener.add_item(document.frmSearch.chk[i].value);				
			}
		}
	} 
	else 
	{
		if(document.frmSearch.chk.checked)
		{
			window.opener.add_item(document.frmSearch.chk.value);
			//alert('2' + '\n' + document.frmSearch.chk.value)
			/*A = document.frmSearch.chk.value
			arrItem = A.split('|');
			//alert(arrItem)
			for(z=1;z<=5;z++)
			{
				alert('1')
				B=eval("document.frmSearch.chk_"+arrItem[0]+"_"+z).value;
				//window.opener.add_item(B,'child');
				alert(B)
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