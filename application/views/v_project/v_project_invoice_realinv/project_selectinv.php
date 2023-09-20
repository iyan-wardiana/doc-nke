<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 17 Desember 2017
 * File Name	= project_selectinv.php
 * Location		= -
*/
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');

$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
if($decFormat == 0)
	$decFormat		= 2;

// Searching Function	
$DefEmp_ID 			= $this->session->userdata['Emp_ID'];

$sqlPrj	= "SELECT * FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$PrjRes	= $this->db->query($sqlPrj)->result();
foreach($PrjRes as $rowPrj):
	$PRJCODE 	= $rowPrj->PRJCODE;
	$PRJNAME 	= $rowPrj->PRJNAME;
endforeach;
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
			if($TranslCode == 'Save')$Save = $LangTransl;
			if($TranslCode == 'Update')$Update = $LangTransl;
			if($TranslCode == 'Back')$Back = $LangTransl;
			if($TranslCode == 'INVCode')$INVCode = $LangTransl;
			if($TranslCode == 'Date')$Date = $LangTransl;
			if($TranslCode == 'DueDate')$DueDate = $LangTransl;
			if($TranslCode == 'ReferenceNumber')$ReferenceNumber = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'MCValue')$MCValue = $LangTransl;
			if($TranslCode == 'InvAmount')$InvAmount = $LangTransl;
			if($TranslCode == 'Notes')$Notes = $LangTransl;
			if($TranslCode == 'Select')$Select = $LangTransl;
			if($TranslCode == 'Close')$Close = $LangTransl;
		endforeach;
		if($LangID == 'IND')
		{
			$h1_title	= "Silahkan pilih salah satu Faktur di bawah ini.";
		}
		else
		{
			$h1_title	= "Please select one of Invoice Number below.";
		}
	?>

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">

</section>
<!-- Main content -->

  <div class="box">
    <!-- /.box-header -->
	<div class="box-body">
    
		<div class="callout callout-success">
        	<p><?php echo $h1_title; ?></p>
      	</div>
        <form method="post" name="frmSearch" action="">
            <input type="hidden" name="MC_REF2" id="MC_REF2" value="" size="50" />
            <input type="hidden" name="PINV_MMC" id="PINV_MMC" value="0" size="1" />
            <input type="hidden" name="MC_TOTVAL1" id="MC_TOTVAL1" value="0" size="10" />
            <table id="example1" class="table table-bordered table-striped" width="100%">
                <thead>
                    <tr>
                        <th width="2%">&nbsp;</th>
                        <th width="8%" nowrap><?php echo $INVCode; ?></th>
                        <th width="8%" nowrap><?php echo $Date; ?></th>
                        <th width="8%" nowrap><?php echo $DueDate; ?></th>
                        <th width="43%" nowrap><?php echo $Description; ?></th>
                        <th width="10%" nowrap><?php echo $InvAmount; ?></th>
                        <th width="7%" nowrap>PPn</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $currow = 0;			
                        $idx 	= -1;
                        if($countAllINV>0)
                        {
                            foreach($viewAllPINV as $row) :
                                $PINV_CAT 		= $row->PINV_CAT;
                                $PINV_CODE 		= $row->PINV_CODE;		// 0
                                $PINV_MANNO		= $row->PINV_MANNO;		// 1
                                $PINV_STEP 		= $row->PINV_STEP;		// 2
                                $PINV_MMC 		= $row->PINV_MMC;		// 3
                                $PINV_SOURCE 	= $row->PINV_SOURCE;	// 4
                                $PINV_DATE 		= $row->PINV_DATE;		// 5
                                $PINV_ENDDATE	= $row->PINV_ENDDATE;	// 6
                                $PINV_TOTVAL	= $row->PINV_TOTVAL;	// 7
                                $PINV_TOTVALPPn	= $row->PINV_TOTVALPPn;	// 8
                                $PINV_NOTES		= $row->PINV_NOTES;
								$PINV_NOTESD	= " - $PINV_NOTES";
                                $currow			= $currow + 1;
								
								if($PINV_CAT == 1)
								{
									$PINV_TOTVAL	= $row->PINV_DPVAL;
									$PINV_TOTVALPPn	= $row->PINV_DPVALPPn;
								}
                                ?>
                                    <tr>
                                        <td style="text-align:center" nowrap><input type="radio" name="chk" id="chk" value="<?php echo $PINV_CODE;?>|<?php echo $PINV_MANNO;?>|<?php echo $PINV_STEP;?>|<?php echo $PINV_MMC;?>|<?php echo $PINV_SOURCE;?>|<?php echo $PINV_DATE;?>|<?php echo $PINV_ENDDATE;?>|<?php echo $PINV_TOTVAL;?>|<?php echo $PINV_TOTVALPPn;?>" onClick="pickThis(this, '<?php echo $currow; ?>');" /></td>
                                        <td nowrap><?php echo "$PINV_MANNO"; ?></td>
                                        <td nowrap><?php echo "$PINV_DATE"; ?></td>
                                        <td style="text-align:center" nowrap><?php echo "$PINV_ENDDATE"; ?></td>
                                        <td><?php echo "$PINV_SOURCE$PINV_NOTESD"; ?></td>
                                        <td style="text-align:right"><?php print number_format($PINV_TOTVAL, $decFormat); ?></td>
                                        <td style="text-align:right"><?php print number_format($PINV_TOTVALPPn, $decFormat); ?></td>
                                    </tr>
                                <?php
                            endforeach;
                        }
                    ?>
                </tbody>
                <tr>
                    <td colspan="7" nowrap>
                        <button class="btn btn-primary" type="button" onClick="get_item();">
                        <i class="cus-check-green-16x16"></i>&nbsp;&nbsp;<?php echo $Select; ?>                    </button> 
                        <button class="btn btn-danger" type="button" onClick="window.close()">
                        <i class="cus-delete-16x16"></i>&nbsp;&nbsp;<?php echo $Close; ?>                    </button>
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
	var selectedRows = 0;	

	function get_item() 
	{ 
		// alert(document.getElementsByName('chk').length) 
		if(typeof(document.getElementsByName('chk')[0]) == 'object') 
		{
			for(i=0;i<document.getElementsByName('chk').length;i++) 
			{
				if(document.getElementsByName('chk')[i].checked) 
				{
					A 			= document.getElementsByName('chk')[i].value
					arrItem 	= A.split('|');
					arrparent 	= document.getElementsByName('chk')[i].value.split('|');
					window.opener.getDetail(document.getElementsByName('chk')[i].value);				
				}
			}
		} 
		else 
		{
			if(document.getElementsByName('chk').checked)
			{
				PINV_MMC	= document.getElementById('PINV_MMC').value;
				MC_TOTVAL1	= document.getElementById('MC_TOTVAL1').value;
				MC_REF		= document.getElementById('MC_REF2').value;
					PINV_PAYBEF	= document.getElementById('PINV_AKUMNEXT').value;
				window.opener.getDetail(document.getElementsByName('chk').value, PINV_MMC, MC_TOTVAL1, MC_REF, PINV_PAYBEF);
				//alert('2' + '\n' + document.getElementsByName('chk').value)
				/*A = document.getElementsByName('chk').value
				arrItem = A.split('|');
				//alert(arrItem)
				for(z=1;z<=5;z++)
				{
					alert('1')
					B=eval("document.getElementsByName('chk')_"+arrItem[0]+"_"+z).value;
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