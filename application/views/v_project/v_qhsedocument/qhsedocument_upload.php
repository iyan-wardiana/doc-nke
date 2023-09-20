<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 14 April 2015
 * File Name	= purchase_selectpr.php
 * Location		= system\application\views\v_purchase\v_purchase_order\purchase_selectpr.php
*/
?>
<?php
// _global function
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;

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

$sql = "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$result = $this->db->query($sql)->result();
foreach($result as $row) :
	$PRJNAME = $row ->PRJNAME;
endforeach;
?>
 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="<?php echo base_url().'imagess/fav_icon.png';?>" />
<style type="text/css">@import url("<?php echo base_url() . 'css/reset.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'css/style.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'css/style_menu.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'css/style_table.css'; ?>");</style>
<script language="javascript" src="<?php echo base_url() . 'assets/js/allscript.js'; ?>"></script>
<style type="text/css">
.link{
	color:##003;
	cursor:pointer;
}
</style>

<title><?php echo isset($title) ? $title : ''; ?></title>
</head>

<body id="<?php echo isset($title) ? $title : ''; ?>">

<div id="mainPopUp">
<form name="frm" action="" method="POST" enctype="multipart/form-data">
<table width="750" border="0" cellpadding="0" cellspacing="0">
	<tr>
    	<td>
        	<div class="HCSSTableGenerator">
            <table width="90%" border="0" style="size:auto">
              <tr>
                <td colspan="3" class="style2"><?php echo $h2_title; ?></td>
              </tr>
            </table>
            </div>
        </td>
    </tr>
	<tr>
    	<td>
        	<table width="100%" border="0" style="size:auto" bgcolor="#FFFFFF">
                <tr>
                    <th width="14%" align="left" class="style1">Doc. Code</th>
                    <th width="1%" align="left" class="style1">:</th>
                    <th colspan="3" align="left" nowrap class="style1">
                        <?php echo $DocNumber; ?>
                        <input type="hidden" name="HRDOCNO" id="HRDOCNO" value="<?php echo $HRDOCNO; ?>" class="textbox"></th>
             	</tr>
                <tr>
                    <th width="14%" align="left" class="style1">Doc. Number</th>
                    <th width="1%" align="left" class="style1">:</th>
                    <th colspan="3" align="left" nowrap class="style1"><?php echo $HRDOCCODE; ?>              		</th>
             	</tr>
                <tr>
                    <th align="left" class="style1">Project Name</th>
                    <th align="left" class="style1">:</th>
                    <th width="37%" align="left" class="style1">
					  <?php echo $PRJCODE; ?>
                        <input type="hidden" class="textbox" name="PRJCODE" id="PRJCODE" value="<?php echo $PRJCODE; ?>" size="3" maxlength="6" >
					    - <?php echo $PRJNAME; ?></th>
                    <th width="6%" align="left" class="style1">&nbsp;</th>
                    <th width="42%" align="left" class="style1">&nbsp;</th>
                </tr>
                <tr>
                    <th align="left" class="style1">Doc. Type</th>
                    <th align="left" class="style1">:</th>
                    <th align="left" nowrap><span class="style1">
                      <select name="HRDOCTYPE1" id="HRDOCTYPE1" class="listmenu" disabled>
                        <option value="1" <?php if($HRDOCTYPE == 1) { ?>selected <?php } ?>> ASLI </option>
                        <option value="2" <?php if($HRDOCTYPE == 2) { ?>selected <?php } ?>> COPY </option>
                      </select>
                    </span></th> 
                    <th align="left" class="style1" id="labelProject1">&nbsp;</th>
                    <th align="left" class="style1" id="labelProject2">&nbsp;</th>
                </tr>
                <tr>
                    <th align="left" class="style1">Doc. Qty</th>
                  <th align="left" class="style1">:</th>
                    <th align="left" nowrap>
                        <input type="text" class="textbox" name="HRDOCJMLX" id="HRDOCJMLX" style="text-align:right" value="<?php echo number_format($HRDOCJML, 0); ?>" size="10" onKeyPress="return isIntOnlyNew(event);" onBlur="getHRDOCJML(this)" disabled>
                        <input type="hidden" class="textbox" name="HRDOCJML" id="HRDOCJML" style="text-align:right" value="<?php echo $HRDOCJML; ?>" size="10">                    </th> 
                    <th align="left" class="style1" id="labelProject1">&nbsp;</th>
                    <th align="left" class="style1" id="labelProject2">&nbsp;</th>
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
                    <th align="left" class="style1">Doc. Group</th>
                  <th align="left" class="style1">:</th>
                    <th align="left">
                      <select name="HRDOCLBR" id="HRDOCLBR" class="listmenu" disabled>
                            <option value="1" <?php if($HRDOCLBR == 1) { ?>selected <?php } ?>> LEMBAR </option>
                            <option value="2" <?php if($HRDOCLBR == 2) { ?>selected <?php } ?>> BUKU </option>
                            <option value="3" <?php if($HRDOCLBR == 3) { ?>selected <?php } ?>> BUKU TIPIS </option>
                      </select>            </th> 
                    <th align="left">&nbsp;</th>
                    <th align="left">&nbsp;
                      <input type="hidden" name="HRDOCCOSTX" id="HRDOCCOSTX" size="15" value="<?php echo number_format($HRDOCCOST, 0); ?>" class="textbox" style="text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getHRDOCCOST(this)" />
                      <input type="hidden" name="HRDOCCOST" id="HRDOCCOST" size="15" value="<?php echo $HRDOCCOST; ?>" class="textbox" style="text-align:right" />                    </th>
                </tr>
                <tr>
                    <th align="left" class="style1">Location</th>
                    <th align="left" class="style1">:</th>
                    <th align="left" class="style1">
                        <input type="text" class="textbox" name="HRDOCLOK" id="HRDOCLOK" value="<?php print $HRDOCLOK; ?>" size="10" disabled >                    </th> 
                    <th align="left" nowrap>Doc. File</th>
                <th align="left">:&nbsp;
                      <input type="file" name="userfile" id="userfile" size="20" />                    </th>
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
                    <th colspan="5" align="left" class="style1" style="font-style:italic">&nbsp;</th>
              </tr>
                <tr>
                  <th align="left" class="style1">&nbsp;</th>
                  <th align="left" class="style1">&nbsp;</th>
                  <th colspan="3" align="left" class="style1"><hr></th>
                </tr>
                <tr>
                  <th align="left" class="style1">&nbsp;</th>
                  <th align="left" class="style1">&nbsp;</th>
                  <th colspan="3" align="left" class="style1">
                  <input type="submit" class="button_css" name="submit" id="submit" value=" Upload " align="left" />
                  <input type="button" class="button_css" name="button_close" id="button_close" value=" Cancel " align="left" onclick="closeWin();" />
                </th>
                </tr>
                <tr>
                  <th align="left" class="style1">&nbsp;</th>
                  <th align="left" class="style1">&nbsp;</th>
                  <th colspan="3" align="left" class="style1"><hr></th>
                </tr>
            </table>
        </td>
    </tr>
	<tr>
    	<td>
        	
        </td>
    </tr>
</table>
</form>
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
		
		if (move_uploaded_file($_FILES['userfile']['tmp_name'],"./system/application/views/v_project/v_hrdocument/uploads/$name"))
		{
			$fileName 			= $_FILES['userfile']['name'];
			if($fileName != '')
			{
				$updateFileName = "UPDATE tbl_hrdoc_header SET HRDOC_NAME = '$fileName' WHERE HRDOCNO = '$HRDOCNO'";
				mysql_query($updateFileName) or die(mysql_error());
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
</div>
<script>
	function closeWin()
	{
    	window.close();   // Closes the new window
	}
</script>
</body>
</html>