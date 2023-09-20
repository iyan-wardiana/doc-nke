<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 27 Mei 2016
 * File Name	= project_selectinv_sd.php
 * Notes		= Sync With SDBP on 27 Mei 2016
*/
$proj_Code		 		= $this->session->userdata['dtProjSess']['myProjSession'];
?>
<?php
// _global function
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;

$sqlPINvMax		= "SELECT MAX(adend_step) AS MaxStep FROM sd_tprojinv_detail WHERE proj_Code = '$proj_Code'";
$resPINvMax		= $this->db->query($sqlPINvMax)->result();
foreach($resPINvMax as $rowPINVMax) :
	$MaxStep 	= $rowPINVMax ->MaxStep;
endforeach;
	
$sqlPINC	= "sd_tprojinv_detail WHERE proj_Code = '$proj_Code'";
$ressqlPINC	= $this->db->count_all($sqlPINC);
if($ressqlPINC > 0)
{
	$sqlPINvMax		= "SELECT MAX(adend_step) AS MaxStep FROM sd_tprojinv_detail WHERE proj_Code = '$proj_Code'";
	$resPINvMax		= $this->db->query($sqlPINvMax)->result();
	foreach($resPINvMax as $rowPINVMax) :
		$MaxStep 	= $rowPINVMax ->MaxStep;
	endforeach;
	$lastStepPINV	= $MaxStep + 1;
}
else
{
	$lastStepPINV	= 1;
}
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

<title><?php echo isset($title) ? $title : ''; ?></title>
</head>

<body id="<?php echo isset($title) ? $title : ''; ?>">

<div id="mainPopUp">
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
</table>
<form method="post" name="frmSearch" action="">
	<input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
    <table width="750" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td>
            	<table width="100%" border="0">
                    <tr>
                        <td width="27%" nowrap>Invoice Number</td>
                        <td width="1%">:</td>
                        <td width="72%"><?php echo $DocNumber; ?></td>
                    </tr>
                    <tr>
                        <td width="27%" nowrap>Addendum Step</td>
                        <td width="1%">:</td>
                        <td width="72%">
                            <select name="adend_step" id="adend_step" class="listmenu">
                                <?php
                                    for($i=$lastStepPINV;$i<=$lastStepPINV+9;$i++)
                                    {
                                ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                <?php
                                    }
                                ?>
                            </select>                            </td>
                    </tr>
                    <tr>
                        <td>Addendum No</td>
                        <td>:</td>
                        <td><input type="text" style="text-align:left" name="adend_Code" id="adend_Code" size="50" value="" class="textbox" > </td>
                    </tr>
                    <tr>
                        <td>Addendum Value</td>
                        <td>:</td>
                        <td><input type="text" style="text-align:right" name="adend_Value1A1" id="adend_Value1A1" size="15" value="0" class="textbox" onKeyPress="return isIntOnlyNew(event);" onchange="getAddendAm(this.value);" >
                        <input type="hidden" style="text-align:right" name="adend_Value1A" id="adend_Value1A" size="10" value="0" class="textbox" ></td>
                    </tr>
                    <script>
						function getAddendAm(thisValx)
						{
							var decFormat		= document.getElementById('decFormat').value;
							
							thisVal				= thisValx.split(",").join("");					
							document.getElementById('adend_Value1A1').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(thisVal)),decFormat));
							document.getElementById('adend_Value1A').value 		= thisVal;
						}
					</script>
                    <tr>
                      <td>Percentation Progress</td>
                      <td>&nbsp;</td>
                      <td>
                      <input type="text" style="text-align:right" name="adend_Percent1" id="adend_Percent1" size="2" value="0" class="textbox" onkeypress="return isIntOnlyNew(event);" onchange="getAddendPercente(this.value);"  /> 
                      <input type="hidden" style="text-align:right" name="adend_Percent" id="adend_Percent" size="2" value="0" class="textbox" onkeypress="return isIntOnlyNew(event);"  /> 
                      %</td>
                    </tr>
                    <script>
						function getAddendPercente(thisValx)
						{
							var decFormat		= document.getElementById('decFormat').value;
							
							thisVal				= thisValx.split(",").join("");					
							document.getElementById('adend_Percent1').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(thisVal)),decFormat));
							document.getElementById('adend_Percent').value 		= thisVal;
						}
					</script>
                    <tr>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td><hr /></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td><input type="button" name="btnSubmit" id="btnSubmit" class="button_css" value=" Insert" onclick="get_item()" />
                        &nbsp;
                        <input type="button" name="btnCancel" id="btnCancel" class="button_css" value=" Cancel " onclick="window.close()" /></td>
                    </tr>
          		</table>
          	</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
    </table>
</form>

<script>
var selectedRows = 0;
function check_all(chk){
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
		adend_step		= document.getElementById('adend_step').value;
		adend_Code		= document.getElementById('adend_Code').value;
		if(adend_Code == '')
		{
			alert('Please input Addendum Number.');
			document.getElementById('adend_Code').focus();
			return false;
		}
		adend_Value1A	= document.getElementById('adend_Value1A').value;
		if(adend_Value1A == 0)
		{
			alert('Please input Addendum Value.');
			document.getElementById('adend_Value1A').value = '';
			document.getElementById('adend_Value1A').focus();
			return false;
		}
		adend_Percent	= document.getElementById('adend_Percent').value; 
		if(adend_Percent == 0)
		{
			alert('Please input Addendum Percentation Progress.');
			document.getElementById('adend_Percent').value = '';
			document.getElementById('adend_Percent').focus();
			return false;
		}
		window.opener.add_item(adend_step, adend_Code, adend_Value1A, adend_Percent);
		window.close();
	}
</script>

 </div>
</body>
</html>