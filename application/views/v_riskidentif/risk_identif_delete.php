<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 04 Mei 2017
 * File Name	= risk_identif_delete.php
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

if(isset($_POST['submit']))
{
	$packageelements	= $_POST['packageelements'];
	$TOTRISK			= count($packageelements);
	if (count($packageelements)>0)
	{
		$mySelected	= $_POST['packageelements'];
		$row		= 0;
		foreach ($mySelected as $RISKCode)
		{
			$row	= $row + 1;
			if($row == 1)
			{
				$PRJCODE1	= $RISKCode;
			}
			else
			{
				$PRJCODE1	= "$PRJCODE1','$RISKCode";
			}
		}
	}
	$delRisk 	= "DELETE FROM tbl_riskidentif WHERE RID_CODE IN ('$PRJCODE1')";
	$this->db->query($delRisk);
	
	$delRisk1 	= "DELETE FROM tbl_riskdescdet WHERE RIDD_CODE1 IN ('$PRJCODE1')";
	$this->db->query($delRisk1);
	
	$delRisk2 	= "DELETE FROM tbl_riskimpactdet WHERE RIDD_CODE2 IN ('$PRJCODE1')";
	$this->db->query($delRisk2);
	
	$delRisk3 	= "DELETE FROM tbl_riskpolicydet WHERE RIDD_CODE3 IN ('$PRJCODE1')";
	$this->db->query($delRisk3);
}
	
if($DefEmp_ID == 'D15040004221')
	$getRisk 	= "SELECT * FROM tbl_riskidentif ORDER BY RID_CODE";
else
	$getRisk 	= "SELECT * FROM tbl_riskidentif WHERE EMP_ID = '$DefEmp_ID' ORDER BY RID_CODE";

$qRisk 		= $this->db->query($getRisk)->result();
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
        <p>Please select Risk Caused below to delete.</p>
    </div>
	<div class="search-table-outter">
  	  <form method="post" name="frm" id="frm" action="" onSubmit="return target_popup(this);" >
      	<input type="hidden" name="isVPAll" id="isVPAll" value="0" />
        <table width="100%">
          <script>
                function changeVPType(thisVal)
                {
                    if(thisVal == 0)
                    {
                        document.getElementById('RISKAll01').style.display = '';
                        document.getElementById('isVPAll').value = 0;
                    }
                    else
                    {
                        document.getElementById('RISKAll01').style.display = 'none';
                        document.getElementById('isVPAll').value = 1;
                    }
                }
            </script>
          <tr id="RISKAll01">
              <td width="14%" valign="top" nowrap>Select Risk</td>
              <td width="1%" valign="top" nowrap>:</td>
              <td width="5%" id="RISKAll02" nowrap>
                  <select multiple="multiple" class="options" size="10" style="width: 300px;"name="pavailable" onChange="MoveOption(this.form.pavailable, this.form.packageelements)">
                    <?php 
                        foreach($qRisk as $rowRSK) :
                            $RID_CODE 	= $rowRSK->RID_CODE;
                            $RID_CAUSE	= $rowRSK->RID_CAUSE;
                            ?>
                              <option value="<?php echo $RID_CODE; ?>"><?php echo "$RID_CODE - $RID_CAUSE";?></option>
                          <?php
                        endforeach;
                    ?>
                  </select>                </td>
              <td width="80%" id="RISKAll03" nowrap>
                  <select multiple="multiple" name="packageelements[]" id="packageelements" size="10" style="width: 300px;" ondblclick="MoveOption(this.form.packageelements, this.form.pavailable)">
                </select>                </td>
          </tr>
          <script>
				function changeTypePeriod(thisValue) 
				{					
					document.getElementById("vPeriodx").value = thisValue;
					document.frmsrch.submitSrch.click();
				}
            </script>
          <tr>
            <td>&nbsp;</td>
            <td nowrap>&nbsp;</td>
            <td colspan="2" nowrap>&nbsp;</td>
          </tr>
          <tr>
              <td>&nbsp;</td>
              <td nowrap>&nbsp;</td>
              <td colspan="2" nowrap>
                  <input type="submit" class="btn btn-primary" name="submit" id="submit" value="Delete [ - ]" />&nbsp;
                  <input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value=" Close " onClick="window.close()" /></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td nowrap>&nbsp;</td>
            <td colspan="2" nowrap><hr /></td>
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
	function MoveOption(objSourceElement, objTargetElement) 
	{ 
		var aryTempSourceOptions = new Array(); 
		var aryTempTargetOptions = new Array(); 
		var x = 0; 
    
   		//looping through source element to find selected options 
   		for (var i = 0; i < objSourceElement.length; i++)
		{ 
    		if (objSourceElement.options[i].selected)
			{ 
				 //need to move this option to target element 
				 var intTargetLen = objTargetElement.length++; 
				 objTargetElement.options[intTargetLen].text = objSourceElement.options[i].text; 
				 objTargetElement.options[intTargetLen].value = objSourceElement.options[i].value; 
    		} 
    		else
			{ 
				 //storing options that stay to recreate select element 
				 var objTempValues = new Object(); 
				 objTempValues.text = objSourceElement.options[i].text; 
				 objTempValues.value = objSourceElement.options[i].value; 
				 aryTempSourceOptions[x] = objTempValues; 
				 x++; 
			} 
   		}
		
   		//sorting and refilling target list 
		for (var i = 0; i < objTargetElement.length; i++)
		{ 
			var objTempValues = new Object(); 
			objTempValues.text = objTargetElement.options[i].text; 
			objTempValues.value = objTargetElement.options[i].value; 
			aryTempTargetOptions[i] = objTempValues; 
		} 

		aryTempTargetOptions.sort(sortByText); 

		for (var i = 0; i < objTargetElement.length; i++)
		{ 
			objTargetElement.options[i].text = aryTempTargetOptions[i].text; 
			objTargetElement.options[i].value = aryTempTargetOptions[i].value; 
			objTargetElement.options[i].selected = false; 
		}
		
   		//resetting length of source 
   		objSourceElement.length = aryTempSourceOptions.length; 
   		//looping through temp array to recreate source select element 
   		for (var i = 0; i < aryTempSourceOptions.length; i++) 
		{ 
			objSourceElement.options[i].text = aryTempSourceOptions[i].text; 
			objSourceElement.options[i].value = aryTempSourceOptions[i].value; 
			objSourceElement.options[i].selected = false; 
		}

		function sortByText(a, b) 
		{ 
			if (a.text < b.text) {return -1} 
			if (a.text > b.text) {return 1} 
			return 0; 
		}
	}
	
	function target_popup(form)
	{
		isVPAll			= document.getElementById('isVPAll').value;
		if(isVPAll == 0)
		{
			packageelements	= document.getElementById('packageelements').value;
			if(packageelements == '')
			{
				alert('Please select one or more risk identifier.');
				return false;
			}
		}
	}
</script>
<?php
//$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
//$this->load->view('template/foot');
?>