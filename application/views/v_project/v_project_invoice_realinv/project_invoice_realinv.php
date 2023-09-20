<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 17 Desember 2017
 * File Name	= project_invoice_realinv.php
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
	
$sql = "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$result = $this->db->query($sql)->result();
foreach($result as $row) :
	$PRJNAME = $row ->PRJNAME;
endforeach;

$selDocNumb			= '';
$PINV_MMC1			= '';

if(isset($_POST['submit']))
{
	$selDocNumb 	= $_POST['selDocNumb'];
	$PINV_MMC1	 	= $_POST['PINV_MMC1'];
}

$showIdxAll		= site_url('c_project/project_invoice_realINV/get_last_ten_projmc/?id='.$this->url_encryption_helper->encode_url($PRJCODE));

$username 		= $this->session->userdata['username'];
$writeEMP 		= $this->session->userdata['writeEMP'];
$editEMP 		= $this->session->userdata['editEMP'];
$readEMP 		= $this->session->userdata['readEMP'];
if($writeEMP == 1 || $editEMP == 1)
{
	$isOpen = 1;
}
else
{
	$isOpen = 0;
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

<script src="<?php echo base_url('assets/js/jquery-1.10.1.min.js') ?>" type="text/javascript"></script>
<?php
	$this->load->view('template/topbar');
	$this->load->view('template/sidebar');
	
	$ISREAD 	= $this->session->userdata['ISREAD'];
	$ISCREATE 	= $this->session->userdata['ISCREATE'];
	$ISAPPROVE 	= $this->session->userdata['ISAPPROVE'];
	$ISDWONL 	= $this->session->userdata['ISDWONL'];
	$LangID 	= $this->session->userdata['LangID'];

	$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
	$resTransl		= $this->db->query($sqlTransl)->result();
	foreach($resTransl as $rowTransl) :
		$TranslCode	= $rowTransl->MLANG_CODE;
		$LangTransl	= $rowTransl->LangTransl;
		
		if($TranslCode == 'Add')$Add = $LangTransl;
		if($TranslCode == 'Edit')$Edit = $LangTransl;
		if($TranslCode == 'RealisasiNumber')$RealisasiNumber = $LangTransl;
		if($TranslCode == 'InvoiceNumber')$InvoiceNumber = $LangTransl;
		if($TranslCode == 'ManualNumber')$ManualNumber = $LangTransl;
		if($TranslCode == 'Description')$Description = $LangTransl;
		if($TranslCode == 'Date')$Date = $LangTransl;
		if($TranslCode == 'DueDate')$DueDate = $LangTransl;
		if($TranslCode == 'Status')$Status = $LangTransl;
		if($TranslCode == 'Print')$Print = $LangTransl;
		if($TranslCode == 'Back')$Back = $LangTransl;
		if($TranslCode == 'InvoiceRealization')$InvoiceRealization = $LangTransl;
	endforeach;
	if($LangID == 'IND')
	{
		$h1_title	= "Daftar Faktur";
		$sureDelete	= "Anda yakin akan menghapus data ini?";
	}
	else
	{
		$h1_title	= "Invoice List";
		$sureDelete	= "Are your sure want to delete?";
	}
?>

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>
    <?php echo $InvoiceRealization; ?>
    <small><?php echo $PRJNAME; ?></small>
  </h1>
  <br>
  <?php /*?><ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#">Tables</a></li>
    <li class="active">Data tables</li>
  </ol><?php */?>
</section>
<style>
	.search-table, td, th {
		border-collapse: collapse;
	}
	.search-table-outter { overflow-x: scroll; }
	
    a[disabled="disabled"] {
        pointer-events: none;
    }
</style>
<!-- Main content -->

<div class="box">
<!-- /.box-header -->
<div class="box-body">
    <div class="search-table-outter">
      <form name="frmselect" id="frmselect" action="" method=POST>
            <input type="hidden" name="selDocNumb" id="selDocNumb" value="<?php echo $selDocNumb; ?>" />
            <input type="hidden" name="PINV_MMC1" id="PINV_MMC1" value="<?php echo $PINV_MMC1; ?>" />
            <input type="submit" class="button_css" name="submit" id="submit" value=" search " style="display:none" />
      </form>
        <table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
            <thead>
            <tr>
                <th width="1%"><input name="chkAll" id="chkAll" type="checkbox" value="" style="display:none" /></td>
                <th width="15%" style="text-align:center" nowrap><?php echo $RealisasiNumber ?> </td>
                <th width="7%" style="text-align:center" nowrap><?php echo $Date ?>  </td>
                <th width="12%" style="text-align:center" nowrap><?php echo $InvoiceNumber ?> </td>
                <th width="11%" style="text-align:center" nowrap><?php echo $ManualNumber ?>  </td>
                <th width="35%" style="text-align:center"><?php echo $Description ?> </td>
                <th width="8%" style="text-align:center" nowrap><?php echo $DueDate ?> </td>
                <th width="8%" style="text-align:center" nowrap><?php echo $Status ?> </td>
                <th width="3%" style="text-align:center" nowrap>                
            </tr>
            </thead>
            <tbody>
            <?php 
                $i = 0;
                $j = 0;
                $PRJCODE1 	= "$PRJCODE";        
                if($countINVReal >0)
                {
                    foreach($vwINVReal as $row) : 
                        $PRINV_Number		= $row->PRINV_Number;
                        $PRINV_Date			= $row->PRINV_Date;
                        $PINV_Number		= $row->PINV_Number;
                        $PINV_Date			= $row->PINV_Date;
                        $PRINV_Deviation	= $row->PRINV_Deviation;
                        $PINV_Amount		= $row->PINV_Amount;
                        $PINV_AmountPPn		= $row->PINV_AmountPPn;
                        $PINV_AmountPPh		= $row->PINV_AmountPPh;
                        $PRINV_Amount		= $row->PRINV_Amount;
                        $PRINV_AmountPPn	= $row->PRINV_AmountPPn;
                        $PRINV_AmountPPh	= $row->PRINV_AmountPPh;
                        $PRINV_AmountOTH	= $row->PRINV_AmountOTH;
                        $PRINV_Notes		= $row->PRINV_Notes;
                        $PRINV_STAT			= $row->PRINV_STAT;
						
						if($PRINV_STAT == 0)
						{
							$PRINV_STATDes	= "fake";
							$STATCOL		= 'danger';
						}
						elseif($PRINV_STAT == 1)
						{
							$PRINV_STATDes 	= "New";
							$STATCOL		= 'warning';
						}
						elseif($PRINV_STAT == 2)
						{
							$PRINV_STATDes 	= "Confirm";
							$STATCOL		= 'primary';
						}
						elseif($PRINV_STAT == 3)
						{
							$PRINV_STATDes 	= "Approve";
							$STATCOL		= 'success';
						}
                        
                        $myNewNo = ++$i;
                    
                        if ($j==1) {
                            echo "<tr class=zebra1>";
                            $j++;
                        } else {
                            echo "<tr class=zebra2>";
                            $j--;
                        }
                        ?>
                            <td style="text-align:center"><?php echo $myNewNo; ?>.</td>
                            <td nowrap> <?php print $PRINV_Number; ?> </td>
                            <td style="text-align:center" nowrap>
                                <?php
                                    $date = new DateTime($PRINV_Date);
                                    echo $date->format('d F Y');
                                ?>        </td>
                            <td nowrap> <?php print $PINV_Number; ?></td>
                            <td nowrap>&nbsp;</td>
                            <td><span style="text-align:left">
                              <?php
                                    echo $PRINV_Notes;
                                ?>
                            </span></td>
                            <td style="text-align:center" nowrap>
                                <?php
                                    $date = new DateTime($PINV_Date);
                                    echo $date->format('d F Y');
                                ?>        </td>
                            <td style="text-align:center">
                                <span class="label label-<?php echo $STATCOL; ?>" style="font-size:11px">
                                    <?php 
                                        echo $PRINV_STATDes;
                                     ?>
                                 </span>
                            </td>
                            <?php
								$secUpd	= site_url('c_project/c_prj180c2einvr/up180c2ddt/?id='.$this->url_encryption_helper->encode_url($PRINV_Number));
							?>
                            <td width="0%" nowrap style="text-align:center">
                            <a href="<?php echo $secUpd; ?>" class="btn btn-info btn-xs" title="Update">
                                <i class="glyphicon glyphicon-pencil"></i>
                            </a>
                            <a href="avascript:void(null);" class="btn btn-primary btn-xs" title="Print" onClick="printDocument('<?php echo $myNewNo; ?>')" disabled="disabled" >
                                <i class="glyphicon glyphicon-print"></i>
                            </a>
                            <a href="" class="btn btn-danger btn-xs" title="Delete" onclick="return confirm('<?php echo $sureDelete; ?>')" <?php if($PRINV_STAT > 1) { ?>disabled="disabled" <?php } ?>>
                                <i class="glyphicon glyphicon-trash"></i>
                            </a>
                        </td>
                        </tr>
                        <?php 
                    endforeach; 
                }
            ?>
            </tbody>
            <tr>
                <td style="text-align:left" colspan="9">
					<?php
                        $secURLPDoc		= site_url('c_project/project_invoice_realINV/printdocument/?id='.$this->url_encryption_helper->encode_url($selDocNumb));
                        $secURLPDoc1	= site_url('c_project/project_invoice_realINV/printdocument1/?id='.$this->url_encryption_helper->encode_url($selDocNumb));
                        $secURLEDoc		= site_url('c_project/project_invoice_realINV/editdocument/?id='.$this->url_encryption_helper->encode_url($selDocNumb));
						if($ISCREATE == 1)
                        {
							echo anchor("$addURL",'<button class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i>&nbsp;&nbsp;'.$Add.'</button>&nbsp;');
                        }
                    ?>
                    <input type="hidden" name="myPINV_Number" id="myPINV_Number" value="<?php echo $selDocNumb; ?>" />
                    <input type="hidden" name="PINV_MMC" id="PINV_MMC" value="<?php echo $PINV_MMC1; ?>" />
                    <button name="btnPrintDoc" id="btnPrintDoc" class="btn btn-warning" onClick="printDocument();" style="display:none"><i class="cus-print-16x16"></i>&nbsp;&nbsp;<?php echo $Print; ?></button>
                    <input type="button" name="btnEditDoc" id="btnEditDoc" class="button_css" value="Edit Invoice" onClick="EditDocument();" style="display:none" />
                    <?php
					echo anchor("$backURL",'<button class="btn btn-danger"><i class="fa fa-reply"></i>&nbsp;&nbsp;'.$Back.'</button>');
					?>
                </td>
            </tr>
        </table>
    	<script>
			function getValueNo(thisVal, PINV_MMC)
			{
				myValue = thisVal.value;
				document.getElementById('myPINV_Number').value 	= myValue;
				document.getElementById('PINV_MMC1').value 		= PINV_MMC;
				document.getElementById('selDocNumb').value 	= myValue;
				chooseDocNumb(thisVal);
			}
			
			function chooseDocNumb(thisVal)
			{
				document.frmselect.submit.click();
			}
			
			function printDocument()
			{
				myVal 		= document.getElementById('myPINV_Number').value;
				PINV_MMC 	= document.getElementById('PINV_MMC').value;
				
				if(myVal == '')
				{
					alert('Please select one of Invoice Number.')
					return false;
				}
				if(PINV_MMC == 1)
					var url = '<?php echo $secURLPDoc; ?>';
				else
					var url = '<?php echo $secURLPDoc1; ?>';
					
				title = 'Select Item';
				w = 800;
				h = 700;
				//window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
				var left = (screen.width/2)-(w/2);
				var top = (screen.height/3)-(h/3);
				return window.open(url, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
			}
			
			function EditDocument()
			{
				myVal = document.getElementById('myPINV_Number').value;
				
				if(myVal == '')
				{
					alert('Please select one of Invoice Number.')
					return false;
				}
				var url = '<?php echo $secURLEDoc; ?>';
				title = 'Select Item';
				w = 700;
				h = 700;
				//window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
				var left = (screen.width/2)-(w/2);
				var top = (screen.height/2)-(h/2);
				return window.open(url, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
			}
		</script>
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
</script>
<script>
	function getValueNo(thisVal)
	{
		myValue = thisVal;
		document.getElementById('myProjCode').value = myValue;
		document.getElementById('selProject').value = myValue;
		chooseProject(thisVal);
	}
	
	function chooseProject(thisVal)
	{
		document.frmselect.submit.click();
	}
		
	function vProjPerform()
	{
		myVal = document.getElementById('myProjCode').value;
		
		if(myVal == '')
		{
			alert('Please select one of Project Code.')
			return false;
		}
		var url = '<?php echo $urlProjPerF; ?>';
		title = 'Select Item';		
		
		return window.open(url, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+screen.width+', height='+screen.height);
	}
		
	function vInpProjDet()
	{
		myVal = document.getElementById('myProjCode').value;
		
		if(myVal == '')
		{
			alert('Please select one of Project Code.')
			return false;
		}
		var url = '<?php echo $urlProjInDet; ?>';
		title = 'Select Item';		
		w = 900;
		h = 550;
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		return window.open(url, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
	}
</script>

<?php 
$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>