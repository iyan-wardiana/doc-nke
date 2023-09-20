<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 9 Februari 2017
 * File Name	= project_planning.php
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

$HRDOCNO			= $default['HRDOCNO'];
$DocNumber			= $default['HRDOCNO'];
$HRDOCCODE			= $default['HRDOCCODE'];
$HRDOCTYPE			= $default['HRDOCTYPE'];
$SPK_DATE			= $default['TRXDATE'];
$PRJCODE			= $default['PRJCODE'];
//$HRDOCCOST		= $default['HRDOCCOST'];
$HRDOCCOST			= 0;
$HRDOCJNS			= $default['HRDOCTYPE'];
$HRDOCJML			= $default['HRDOCJML'];
$HRDOCLBR			= $default['HRDOCLBR'];
$HRDOCLOK			= $default['HRDOCLOK'];
$Patt_Date 			= $default['Patt_Date'];
$Patt_Month 		= $default['Patt_Month'];
$Patt_Year 			= $default['Patt_Year'];
$Patt_Number 		= $default['Patt_Number'];
$lastPatternNumb1	= $default['Patt_Number'];

$PRJNAME			= "Project's name not found";
$sql = "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$result = $this->db->query($sql)->result();
foreach($result as $row) :
	$PRJNAME = $row->PRJNAME;
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
        <h4><?php echo $h2_title; ?></h4> 
        <p>Please choose a document to uplaod (PDF Only).</p>
    </div>
	<div class="search-table-outter">
        <form name="frm" action="" method="POST" enctype="multipart/form-data">
            <table width="100%" border="0" style="size:auto" bgcolor="#FFFFFF">
                <tr>
                    <td width="16%" align="left" class="style1" nowrap>Doc. Code</td>
                    <td width="1%" align="left" class="style1" nowrap>:</td>
                    <td align="left" nowrap class="style1">
                        <?php echo $DocNumber; ?>
                        <input type="hidden" name="HRDOCNO" id="HRDOCNO" value="<?php echo $HRDOCNO; ?>" class="textbox"></td>
                </tr>
                <tr>
                    <td width="16%" align="left" class="style1">Doc. Number</td>
                    <td width="1%" align="left" class="style1">:</td>
                    <td align="left" nowrap class="style1"><?php echo $HRDOCCODE; ?>              		</td>
                </tr>
                <tr>
                    <td align="left" class="style1">Project Name</td>
                    <td align="left" class="style1">:</td>
                    <td width="83%" align="left" class="style1">
                      <?php echo $PRJCODE; ?>
                        <input type="hidden" class="textbox" name="PRJCODE" id="PRJCODE" value="<?php echo $PRJCODE; ?>" size="3" maxlength="6" >
                        - <?php echo $PRJNAME; ?></td>
                </tr>
                <tr>
                    <td align="left" class="style1">Doc. Type</td>
                    <td align="left" class="style1">:</td>
                    <td align="left" nowrap><span class="style1">
                      <select name="HRDOCTYPE1" id="HRDOCTYPE1" class="form-control" style="max-width:150px" disabled>
                        <option value="1" <?php if($HRDOCTYPE == 1) { ?>selected <?php } ?>> ASLI </option>
                        <option value="2" <?php if($HRDOCTYPE == 2) { ?>selected <?php } ?>> COPY </option>
                      </select>
                    </span></td> 
                </tr>
                <tr>
                    <td align="left" class="style1">Doc. Qty</td>
                  <td align="left" class="style1">:</td>
                    <td align="left" nowrap>
                        <input type="text" class="form-control" style="text-align:right;max-width:150px" name="HRDOCJMLX" id="HRDOCJMLX" value="<?php echo number_format($HRDOCJML, 0); ?>" onKeyPress="return isIntOnlyNew(event);" onBlur="getHRDOCJML(this)" disabled>
                        <input type="hidden" class="textbox" name="HRDOCJML" id="HRDOCJML" style="text-align:right" value="<?php echo $HRDOCJML; ?>" size="10">                    </td> 
                </tr>
                <script>
                    function getHRDOCJML(thisVal)
                    {
                        var decFormat	= document.getElementById('decFormat').value;
                        var thisVal		= eval(thisVal).value.split(",").join("");
                        HRDOCJML			= thisVal;
                        document.getElementById('HRDOCJML').value 	= HRDOCJML;
                        document.getElementById('HRDOCJMLX').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.round(HRDOCJML)),decFormat));
                    }
                </script>
                <tr>
                    <td align="left" class="style1">Doc. Group</td>
                  <td align="left" class="style1">:</td>
                    <td align="left">
                      <select name="HRDOCLBR" id="HRDOCLBR" class="form-control" style="max-width:150px" disabled>
                            <option value="1" <?php if($HRDOCLBR == 1) { ?>selected <?php } ?>> LEMBAR </option>
                            <option value="2" <?php if($HRDOCLBR == 2) { ?>selected <?php } ?>> BUKU </option>
                            <option value="3" <?php if($HRDOCLBR == 3) { ?>selected <?php } ?>> BUKU TIPIS </option>
                      </select>            </td> 
                </tr>
                <tr>
                    <td align="left" class="style1">Location</td>
                    <td align="left" class="style1">:</td>
                    <td align="left" class="style1">
                        <input type="text" class="form-control" style="max-width:150px" name="HRDOCLOK" id="HRDOCLOK" value="<?php print $HRDOCLOK; ?>" size="10" disabled >                    </td> 
                </tr>
                <script>
                    function getHRDOCCOST(thisVal)
                    {
                        var decFormat	= document.getElementById('decFormat').value;
                        var thisVal		= eval(thisVal).value.split(",").join("");
                        HRDOCCOST			= thisVal;
                        document.getElementById('HRDOCCOST').value 	= HRDOCCOST;
                        document.getElementById('HRDOCCOSTX').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.round(HRDOCCOST)),decFormat));
                    }
                </script>
                <tr>
                    <td align="left" class="style1">Doc. File</td>
                    <td align="left" class="style1">:</td>
                    <td align="left" class="style1">
                    <input type="file" name="userfile" class="filestyle" data-buttonName="btn-primary"/>                                </td>
              </tr>
                <tr>
                    <td align="left" class="style1" style="font-style:italic">&nbsp;</td>
                    <td align="left" class="style1" style="font-style:italic">&nbsp;</td>
                    <td align="left" class="style1" style="font-style:italic">
                      <input type="hidden" name="HRDOCCOSTX" id="HRDOCCOSTX" size="15" value="<?php echo number_format($HRDOCCOST, 0); ?>" class="textbox" style="text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getHRDOCCOST(this)" />
                      <input type="hidden" name="HRDOCCOST" id="HRDOCCOST" size="15" value="<?php echo $HRDOCCOST; ?>" class="textbox" style="text-align:right" /></td>
              </tr>
                <tr>
                  <td align="left" class="style1">&nbsp;</td>
                  <td align="left" class="style1">&nbsp;</td>
                  <td align="left" class="style1">
                  <input type="submit" class="btn btn-primary" name="submit" id="submit" value=" Upload " align="left" />&nbsp;
                  <input type="button" class="btn btn-danger" name="button_close" id="button_close" value=" Cancel " align="left" onclick="closeWin();" />                            </td>
                </tr>
                <tr>
                  <td align="left" class="style1">&nbsp;</td>
                  <td align="left" class="style1">&nbsp;</td>
                  <td align="left" class="style1">&nbsp;</td>
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
<?php
	if(isset($_POST['submit']))
	{
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
		$HRDOCNO			= $_POST['HRDOCNO'];
		date_default_timezone_set("Asia/Jakarta");
		
		$fext 				= preg_replace("/.*\.([^.]+)$/","\\1", $_FILES['userfile']['name']);
		$fsize 				= $_FILES['userfile']['size'];
		$fname 				= $_FILES['userfile']['name'];
		
		$name 				= $_FILES['userfile']['name'];
		
		if (move_uploaded_file($_FILES['userfile']['tmp_name'],"./assets/AdminLTE-2.0.5/doc_center/uploads/$name"))
		{
			$fileName 			= $_FILES['userfile']['name'];
			if($fileName != '')
			{
				$updateFileName = "UPDATE tbl_hrdoc_header SET HRDOC_NAME = '$fileName' WHERE HRDOCNO = '$HRDOCNO'";
				$this->db->query($updateFileName);
			}
			?>
				<script>
					window.close()
                </script>
            <?php
		}
		else
		{
			?>
				<script>
					window.close()
                </script>
            <?php
		}
	}
?>
<script>
	function closeWin()
	{
    	window.close();   // Closes the new window
	}
</script>