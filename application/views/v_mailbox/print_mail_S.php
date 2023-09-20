<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 03 Agustus 2017
 * File Name	= print_mail_S.php
 * Location		= -
*/
$MB_SUBJECTD = str_replace(' ', '_', $MB_SUBJECT);
if($viewType == 1)
{
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=".$MB_SUBJECTD.".doc");
	header("Pragma: no-cache");
	header("Expires: 0");
}

$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

$MB_ID 		= $default['MB_ID'];
$MB_NO 		= $default['MB_NO'];
$MB_CLASS 	= $default['MB_CLASS'];
$MB_TYPE 	= $default['MB_TYPE'];
$MB_TYPE_X 	= $default['MB_TYPE_X'];
	$MT_CODE= '';
	$MT_DESC= '';
	$sqlMT	= "SELECT MT_CODE, MT_DESC FROM tbl_mail_type WHERE MT_CODE = '$MB_TYPE'";
	$sqlMT	= $this->db->query($sqlMT)->result();
	foreach($sqlMT as $rowMT) :
		$MT_CODE	= $rowMT->MT_CODE;
		$MT_DESC	= $rowMT->MT_DESC;
	endforeach;
	if($MB_TYPE == 'OTHER')
	{
		$MT_DESC	= $MB_TYPE_X;
	}

$MB_DEPT 	= $default['MB_DEPT'];
$MB_CODE 	= $default['MB_CODE'];
$MB_PARENTC = $default['MB_PARENTC'];
$MB_SUBJECT = $default['MB_SUBJECT'];
$MB_DATE 	= $default['MB_DATE'];
$MB_DATE1 	= $default['MB_DATE1'];
$MB_READD 	= $default['MB_READD'];
$MB_FROM_ID = $default['MB_FROM_ID'];
$MB_FROM	= $default['MB_FROM'];
$MB_TO_ID	= $default['MB_TO_ID'];
$MB_TO		= $default['MB_TO'];
$MB_TO_IDG	= $default['MB_TO_IDG'];
$MB_TOG		= $default['MB_TOG'];
$MB_MESSAGE = $default['MB_MESSAGE'];
$MB_STATUS 	= $default['MB_STATUS'];	
$MB_FN1		= $default['MB_FN1'];
$MB_FN2		= $default['MB_FN2'];
$MB_FN3		= $default['MB_FN3'];
$MB_FN4		= $default['MB_FN4'];
$MB_FN5		= $default['MB_FN5'];		
$MB_ISRUNNO	= $default['MB_ISRUNNO'];
$MB_D		= $default['MB_D'];
$MB_M		= $default['MB_M'];
$MB_Y		= $default['MB_Y'];
$MB_PATTNO	= $default['MB_PATTNO'];
?>
<!DOCTYPE html>
<html>
<head>
</head>
<body>
<section class="content">
    <table width="100%" border="0" style="size:auto">
    <?php
	if($viewType != 1)
	{
	?>
    <tr style="display:none">
        <td width="19%">
            <div id="Layer1">
                <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();">
                <img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/print.gif';?>" border="0" alt="" align="absmiddle">Print</a>
                <a href="#" onClick="window.close();" class="button"> close </a>                </div>            </td>
        <td width="42%" class="style2" style="text-align:center; font-weight:bold">&nbsp;</td>
        <td width="39%" class="style2" style="text-align:center; font-weight:bold">&nbsp;</td>
    </tr>
    <tr>
        <td class="style2" style="text-align:left; font-weight:bold;">&nbsp;</td>
        <td class="style2">&nbsp;</td>
        <td class="style2" style="text-align:left; font-weight:bold">&nbsp;</td>
    </tr>
    <?php
	}
	?>
    <tr>
      <td colspan="3" valign="top" class="style2" style="text-align:left; font-weight:bold;"><img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/Logo1.jpg'; ?>" width="181" height="44"></td>
    </tr>
    <tr>
        <td colspan="3" class="style2" style="text-align:center; text-transform:uppercase; font-weight:bold; font-size:18px">
			<?php echo $MT_DESC; ?>
        </td>
    </tr>
    <tr>
        <td colspan="3" class="style2" style="text-align:center; text-transform:uppercase;">
			Nomor : <?php echo $MB_CODE; ?>
        </td>
    </tr>
    <tr>
        <td colspan="3" class="style2" style="text-align:center">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="3" class="style2">
        	<?php
				echo $MB_MESSAGE;
			?>
        </td>
    </tr>
    <tr>
      <td colspan="3" class="style2">&nbsp;</td>
    </tr>
  </table>
</section>
</body>
</html>