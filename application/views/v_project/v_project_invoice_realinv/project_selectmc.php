<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 12 Maret 2017
 * File Name	= project_selectmc.php
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


$PINV_AKUMNEXT		= 0;
$sqlSrc 			= "tbl_mcheader A
						WHERE A.PRJCODE = '$PRJCODE' AND MC_STAT IN (2,3) AND MC_APPSTAT IN (0,1)
						ORDER BY A.MC_MANNO ASC";				
$recordcountAllMC 	= $this->db->count_all($sqlSrc);

$sql 				= "SELECT A.*
						FROM tbl_mcheader A
						WHERE A.PRJCODE = '$PRJCODE' AND MC_STAT IN (2,3) AND MC_APPSTAT IN (0,1)
						ORDER BY A.MC_MANNO ASC";
$viewAllMC 			= $this->db->query($sql)->result();
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
			if($TranslCode == 'MCCode')$MCCode = $LangTransl;
			if($TranslCode == 'MCNumber')$MCNumber = $LangTransl;
			if($TranslCode == 'InvoiceNumber')$InvoiceNumber = $LangTransl;
			if($TranslCode == 'MCDate')$MCDate = $LangTransl;
			if($TranslCode == 'MCValue')$MCValue = $LangTransl;
			if($TranslCode == 'ReceivedAmount')$ReceivedAmount = $LangTransl;
			if($TranslCode == 'Notes')$Notes = $LangTransl;
		
		endforeach;
	
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
        	<h4><?php echo $h2_title; ?></h4> <p>Please select one of MC numbeNumber below.</p>
      	</div>
        <form method="post" name="frmSearch" action="">
            <input type="hidden" name="MC_REF2" id="MC_REF2" value="" size="50" />
            <input type="hidden" name="PINV_MMC" id="PINV_MMC" value="0" size="1" />
            <input type="hidden" name="MC_TOTVAL1" id="MC_TOTVAL1" value="0" size="10" />
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th width="2%">&nbsp;</th>
                        <th width="11%"><?php echo $MCCode ?></th>
                        <th width="11%"><?php echo $MCNumber ?></th>
                        <th width="11%"><?php echo $InvoiceNumber ?></th>
                        <th width="18%"><?php echo $MCDate ?></th>
                        <th width="15%"><?php echo $MCValue ?></th>
                        <th width="15%"><?php echo $ReceivedAmount ?></th>
                        <th width="43%"><?php echo $Notes ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $currow = 0;			
                        $idx 	= -1;
                        if($recordcountAllMC>0)
                        {
                            foreach($viewAllMC as $row) :
                                $pageFrom 		= "MC";					// 0
                                $MC_CODE 		= $row->MC_CODE;		// 1
                                $MC_MANNO 		= $row->MC_MANNO;		// 2
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
                                $MC_TOTVAL 		= $row->MC_TOTVAL;		// 18
                                $MC_NOTES 		= $row->MC_NOTES;		// 19
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
                                        <td style="text-align:center" nowrap><input type="checkbox" name="chk" id="chk" value="<?php echo $pageFrom;?>|<?php echo $MC_CODE;?>|<?php echo $MC_MANNO;?>|<?php echo $MC_DATE;?>|<?php echo $MC_ENDDATE;?>|<?php echo $MC_RETVAL;?>|<?php echo $MC_PROG;?>|<?php echo $MC_PROGVAL;?>|<?php echo $MC_VALADD;?>|<?php echo $MC_MATVAL;?>|<?php echo $MC_DPPER;?>|<?php echo $MC_DPVAL;?>|<?php echo $MC_DPBACK;?>|<?php echo $MC_RETCUT;?>|<?php echo $MC_PROGAPP;?>|<?php echo $MC_PROGAPPVAL;?>|<?php echo $MC_AKUMNEXT;?>|<?php echo $MC_VALBEF;?>|<?php echo $MC_TOTVAL;?>|<?php echo $MC_NOTES;?>" onClick="pickThis(this, '<?php echo $currow; ?>');" <?php if($MC_APPSTAT == 1) { ?> disabled <?php } ?> /></td>
                                        <td nowrap><?php echo "$MC_CODE"; ?></td>
                                        <td nowrap>
                                            <?php echo "$MC_MANNO"; ?>
                                            <input type="hidden" name="MC_CODE<?php echo $idx; ?>" id="MC_CODE<?php echo $idx; ?>" value="<?php echo "$MC_CODE"; ?>" />
                                            <input type="hidden" name="MC_TOTVAL<?php echo $idx; ?>" id="MC_TOTVAL<?php echo $idx; ?>" value="<?php echo "$MC_TOTVAL"; ?>" />                                    </td>
                                        <td nowrap><?php echo "$PINV_MANNO"; ?></td>
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
                <input type="hidden" name="PINV_AKUMNEXT" id="PINV_AKUMNEXT" value="<?php echo "$PINV_AKUMNEXT"; ?>" />
                <tr>
                    <td colspan="8" nowrap>
                        <input type="button" name="btnSubmit" id="btnSubmit" class="btn btn-primary" value=" Select " onclick="get_item()" />&nbsp;
                        <input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value=" Cancel " onclick="window.close()" /></td>
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
	function pickThis(thisobj, theRow) 
	{
		//var NumOfRows 	= document.getElementsByName('chk').length; // minus 1 because it's the header
		NumOfRows		= document.getElementsByName('chk').length;
		if (thisobj!= '') 
		{
			if (thisobj.checked) selectedRows++;
			else selectedRows--;
		}
		/*if (selectedRows==NumOfRows) 
		{
			document.getElementsByName('chk')AllItem.checked = true;
		}
		else
		{
			document.getElementsByName('chk')AllItem.checked = false;
		}*/
		MC_REFB		= '';
		isMMC		= 0;
		MC_TOTVAL1	= 0;
		/*for(i=0;i<=NumOfRows;i++) 
		{
			ischeck = document.getElementsByName('chk')[i].checked;
			if(document.getElementsByName('chk')[i].checked) 
			{
				MC_CODE		= document.getElementById('MC_CODE'+i).value;
				MC_TOTVAL	= document.getElementById('MC_TOTVAL'+i).value;
				if(i == 0)
				{
					alert('i = '+i)
					MC_REFB 	= MC_CODE;
					MC_TOTVAL1	= parseFloat(MC_TOTVAL1) + parseFloat(MC_TOTVAL);
				}
				else
				{
					alert('ib = '+i)
					if(MC_REFB == '')
						MC_REFB = MC_CODE;
					else
						MC_REFB 	= ''+MC_REFB+'|'+MC_CODE;
					MC_TOTVAL1	= parseFloat(MC_TOTVAL1) + parseFloat(MC_TOTVAL);
				}
			}			
		}*/
		
		for(i=0; i <=NumOfRows; i++)
		{
			ischeck = document.getElementsByName('chk')[i].checked;
			if(ischeck == true)
			{
				MC_CODE		= document.getElementById('MC_CODE'+i).value;
				MC_TOTVAL	= document.getElementById('MC_TOTVAL'+i).value;
				if(i == 0)
				{
					MC_REFB 	= MC_CODE;
					MC_TOTVAL1	= parseFloat(MC_TOTVAL1) + parseFloat(MC_TOTVAL);
				}
				else if(i > 1)
				{
					if(MC_REFB == '')
						MC_REFB = MC_CODE;
					else
						MC_REFB 	= ''+MC_REFB+'|'+MC_CODE;
					MC_TOTVAL1	= parseFloat(MC_TOTVAL1) + parseFloat(MC_TOTVAL);
				}
			}
			document.getElementById('MC_TOTVAL1').value	= MC_TOTVAL1;
			document.getElementById('PINV_MMC').value	= isMMC;
			document.getElementById('MC_REF2').value 	= MC_REFB;
		}
	}
	

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
					PINV_MMC	= document.getElementById('PINV_MMC').value;
					MC_TOTVAL1	= document.getElementById('MC_TOTVAL1').value;
					MC_REF		= document.getElementById('MC_REF2').value;
					PINV_PAYBEF	= document.getElementById('PINV_AKUMNEXT').value;
					window.opener.getDetail(document.getElementsByName('chk')[i].value, PINV_MMC, MC_TOTVAL1, MC_REF, PINV_PAYBEF);				
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