<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 19 April 2017
 * File Name	= asset_mainten_selectasset.php
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

$PRJNAME		= '';
$sqlPRJ 		= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE  = '$PRJCODE'";
$resultPRJ 		= $this->db->query($sqlPRJ)->result();
foreach($resultPRJ as $rowPRJ) :
	$PRJNAME 	= $rowPRJ->PRJNAME;
endforeach;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/style.css'; ?>");</style>
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/styleZebra.css'; ?>");</style>
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
			if($TranslCode == 'Select')$Select = $LangTransl;
			if($TranslCode == 'Close')$Close = $LangTransl;
			if($TranslCode == 'AssetCode')$AssetCode = $LangTransl;
			if($TranslCode == 'AssetName')$AssetName = $LangTransl;
			if($TranslCode == 'AssetDescription')$AssetDescription = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'Scheduled')$Scheduled = $LangTransl;
	
		endforeach;
	
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

<div class="box">
    <!-- /.box-header -->
<div class="box-body">
    <div class="callout callout-success">
        <h4><?php echo "$h2_title :: $PRJCODE - $PRJNAME"; ?></h4>
    </div>
	<div class="search-table-outter">
        <form method="post" name="frmSearch" action="">
              <table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
                <thead>
                    <tr>
                        <th width="2%"><input type="checkbox" name="ChkAllItem" id="ChkAllItem" onClick="check_all(this)" /></th>
                        <th width="7%" style="vertical-align:middle; text-align:center" nowrap><?php echo $AssetCode ?> </th>
               	  	  <th width="23%" style="vertical-align:middle; text-align:center"><?php echo $AssetName ?> </th>
               	  	  <th width="48%" nowrap style="text-align:center"><?php echo $AssetDescription ?> </th>
                   	  <th width="4%" nowrap style="text-align:center"><?php echo $Status ?> </th>
               	  	  <th width="8%" nowrap style="text-align:center"><?php echo $Scheduled ?> </th>
                  </tr>
                </thead>
                <tbody>
                <?php
                    $i = 0;
                    $j = 0;
                    if($reCountAllAsset>0)
                    {
                        $totRow	= 0;
                        foreach($viewAllAsset as $row) :
                            $AS_CODE 		= $row->AS_CODE;			// 0
                            $AS_NAME 		= $row->AS_NAME;			// 1
                            $AS_DESC 		= $row->AS_DESC;			// 2
                            $AS_STAT 		= $row->AS_STAT;			// 3
							if($AS_STAT == 1)
							{
								$AS_STATD	= 'Good';
							}
							elseif($AS_STAT == 2)
							{
								$AS_STATD	= 'In Active';
							}
							elseif($AS_STAT == 3)
							{
								$AS_STATD	= 'Used';
							}
							elseif($AS_STAT == 4)
							{
								$AS_STATD	= 'Maintenanced';
							}
							
							$sql0			= "tbl_asset_usage WHERE AU_AS_CODE = '$AS_CODE' AND AU_PROCS = 1";
							$sqlCount0		= $this->db->count_all($sql0);
							
							$sql1			= "tbl_asset_mainten WHERE AM_AS_CODE = '$AS_CODE' AND AM_PROCS = 1";
							$sqlCount1		= $this->db->count_all($sql1);
							
							if($sqlCount0 > 0)
							{
								$AU_PRJCODE		= "";
								/*$sqlPRJ 		= "SELECT AU_PRJCODE FROM tbl_asset_usage WHERE AU_AS_CODE = '$AS_CODE'
												AND STR_TO_DATE(AU_STARTD, '%Y-%m-%d') >= '$StartDate' AND STR_TO_DATE(AU_ENDD, '%Y-%m-%d') <= '$EndDateN'";*/
								$sqlPRJ 		= "SELECT AU_PRJCODE, AU_STARTD, AU_ENDD FROM tbl_asset_usage WHERE AU_AS_CODE = '$AS_CODE' AND AU_PROCS = 1";
								$resultPRJ 		= $this->db->query($sqlPRJ)->result();
								foreach($resultPRJ as $rowPRJ) :
									$AU_PRJCODE 	= $rowPRJ->AU_PRJCODE;
									$AU_STARTD 		= $rowPRJ->AU_STARTD;
									$AU_ENDD 		= $rowPRJ->AU_ENDD;
								endforeach;
								$SD				= date('d/m/Y', strtotime($AU_STARTD));
								$ED				= date('d/m/Y', strtotime($AU_ENDD));
								$SCHEDULED		= "Scheduled - $AU_PRJCODE : $SD to $ED";
								
								$bgColor		= 1;
							}
							if($sqlCount1 > 0)
							{
								$AM_PRJCODE		= "";
								/*$sqlPRJ 		= "SELECT AM_PRJCODE FROM tbl_asset_usage WHERE AM_AS_CODE = '$AS_CODE'
												AND STR_TO_DATE(AM_STARTD, '%Y-%m-%d') >= '$StartDate' AND STR_TO_DATE(AM_ENDD, '%Y-%m-%d') <= '$EndDateN'";*/
								$sqlPRJ 		= "SELECT AM_PRJCODE, AM_STARTD, AM_ENDD FROM tbl_asset_mainten WHERE AM_AS_CODE = '$AS_CODE' AND AM_PROCS = 1";
								$resultPRJ 		= $this->db->query($sqlPRJ)->result();
								foreach($resultPRJ as $rowPRJ) :
									$AM_PRJCODE 	= $rowPRJ->AM_PRJCODE;
									$AM_STARTD 		= $rowPRJ->AM_STARTD;
									$AM_ENDD 		= $rowPRJ->AM_ENDD;
								endforeach;
								$SD				= date('d/m/Y', strtotime($AM_STARTD));
								$ED				= date('d/m/Y', strtotime($AM_ENDD));
								$SCHEDULED		= "Maintenanced - $SD to $ED";
								
								$bgColor		= 2;
							}
							
							if($sqlCount0 == 0 && $sqlCount1 ==0)
							{
								$SCHEDULED		= "-";
								$bgColor		= 0;
							}
							
							if($AS_STAT == 2)
							{
								$bgColor		= 5;
							}
							elseif($AS_STAT == 3)
							{
								$bgColor		= 5;
							}
							elseif($AS_STAT == 4)
							{
								$bgColor		= 5;
							}
							
                            $totRow			= $totRow + 1;
						
							if ($j==1) 
							{
								/*if ($bgColor == 1)
									echo "<tr class=zebra1 style='background:#9CD68B'>";
								elseif ($bgColor == 2)
									echo "<tr class=zebra1 style='background:#E2C5E1'>";
								else*/
									echo "<tr class=zebra1>";
								$j++;
							} 
							else 
							{
								/*if ($bgColor == 1)
									echo "<tr class=zebra1 style='background:#9CD68B'>";
								elseif ($bgColor == 2)
									echo "<tr class=zebra1 style='background:#E2C5E1'>";
								else*/
									echo "<tr class=zebra2>";
								$j--;
							}
							?> 
                            <td style="text-align:center"><input type="checkbox" name="chk" value="<?php echo $AS_CODE;?>|<?php echo $AS_NAME;?>|<?php echo $AS_DESC;?>|<?php echo $AS_DESC;?>|<?php echo $AS_DESC;?>|<?php echo $AS_DESC;?>|<?php echo $AS_DESC;?>" onClick="pickThis(this);" <?php if($bgColor > 0) { ?> disabled <?php } ?>/></td>
                            <td nowrap><?php echo $AS_CODE; ?></td>
                            <td nowrap><?php echo $AS_NAME; ?></td>
                            <td nowrap style="text-align:left"><?php echo $AS_DESC; ?></td>
                            <td nowrap style="text-align:left">
                            <?php
								if($AS_STAT == 1)
								{
									?>
                            			<img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/ready_icon2.png'; ?>" width="25" height="25" title="Active">
                                	<?php
								}
								elseif($AS_STAT == 2)
								{
									?>
                            			<img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/inactive_red.png'; ?>" width="25" height="25" title="In Active">
                                	<?php
								}
								elseif($AS_STAT == 3)
								{
									?>
                            			<img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/lock_icon1.png'; ?>" width="25" height="25" title="Used">
                                	<?php
								}
								elseif($AS_STAT == 4)
								{
									?>
                            			<img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/repair_yellow1.png'; ?>" width="25" height="25" title="Repair">
                                	<?php
								}
							?>
							</td>
                            <td nowrap style="text-align:left"><?php echo $SCHEDULED; ?></td>
                        </tr>
                        <?php
                        endforeach;
                    }
                ?>
                </tbody>
                <tr>
                  <td colspan="7" nowrap>
                    <!--<input type="button" name="btnSubmit" id="btnSubmit" class="btn btn-primary" value=" Select" onClick="get_item()" />&nbsp;
                    <input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value=" Close " onClick="window.close()" />-->                  
                  
                  	<button class="btn btn-primary" type="button" onClick="get_item();">
                        <i class="cus-check-green-16x16"></i>&nbsp;&nbsp;<?php echo $Select; ?>
                    </button>&nbsp;
                    
                    <button class="btn btn-danger" type="button" onClick="window.close()">
                        <i class="cus-delete-16x16"></i>&nbsp;&nbsp;<?php echo $Close; ?>
                    </button> 
                  </td>
                </tr>
            </table>
      </form>
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

					window.opener.add_header(document.frmSearch.chk[i].value);				
				}
			}
		} 
		else 
		{
			if(document.frmSearch.chk.checked)
			{
				window.opener.add_header(document.frmSearch.chk.value);
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
</script>
<?php 
//$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
//$this->load->view('template/foot');
?>