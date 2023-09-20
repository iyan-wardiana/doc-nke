<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 8 Agustus 2015
 * File Name	= v_document_log_input.php
 * Location		= -
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

$projCode = $projCode;
$getData		= "SELECT B.proj_Name
					FROM thrmemployee_proj A
					INNER JOIN tproject_header B ON B.proj_Code = A.proj_Code
					WHERE A.Emp_ID = '$Emp_ID' AND A.proj_Code = '$projCode'";
$resGetData 	= $this->db->query($getData)->result();
foreach($resGetData as $rowData) :
	$proj_Name 	= $rowData->proj_Name;
endforeach;

$progress_DateY = date('Y');
$progress_DateM = date('m');
$progress_DateD = date('d');
$progress_Date = "$progress_DateY-$progress_DateM-$progress_DateD";
$ETD = $progress_Date;
$Patt_Year = date('Y');
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
<table width="100%" border="0" cellpadding="0" cellspacing="0">
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
            <form name="employee_form" id="employee_form" method="post" action="<?php echo $form_action; ?>" enctype="multipart/form-data" onsubmit="return isSubmit()">
            	<input type="hidden" name="proj_CodeX" id="proj_CodeX" value="<?php echo $projCode; ?>" />
                <table width="100%" border="0" style="size:auto" bgcolor="#FFFFFF">
                    <tr>
                        <th class="style1" style="font-style:italic; text-align:right">&nbsp;</th>
                        <th colspan="3" align="left" class="style1">&nbsp;</th>
                        <th width="36%" align="left" class="style1">&nbsp;</th>
                    </tr>
                    <tr>
                        <th class="style1" style="font-style:italic; text-align:right">Project Progress &nbsp;</th>
                      <th colspan="3" align="left" class="style1"><hr></th>
                        <th align="left" class="style1">&nbsp;</th>
                    </tr>
                    <tr>
                        <th class="style1" style="font-style:italic; text-align:right">&nbsp;</th>
                        <th align="left" class="style1">&nbsp;</th>
                        <th align="left" class="style1">&nbsp;</th>
                        <th colspan="2" align="left" class="style1">&nbsp;</th>
                    </tr>
                    <tr>
                        <th width="14%" align="left" class="style1">Project Name</th>
                      <th width="1%" align="left" class="style1">:</th>
                      <th width="37%" align="left" class="style1"><?php echo $proj_Name; ?></th>
                      <th colspan="2" align="left" class="style1">&nbsp;</th>
                    </tr>
                    <tr id="isDocument">
                        <th align="left" class="style1">Progress Type</th>
                      	<th align="left" class="style1">:</th>
                        <th align="left" style="font-style:italic">
                            <select name="progress_Type" id="progress_Type" class="listmenu" >
                              <option value="1">Penyerapan Dana</option>
                              <option value="2">Penyelesaian Pekerjaan</option>
                            </select>
            			</th>
                      <th colspan="2" align="left" class="style1">&nbsp;</th>
                    </tr>
                    <tr>
                        <th align="left" class="style1">Progress Date</th>
                        <th align="left" class="style1">:</th>
                        <th align="left" style="font-style:italic">
                        	<script type="text/javascript">SunFishERP_DateTimePicker('progress_Date','<?php echo $progress_Date;?>','onMouseOver="mybirdthdates();"');</script>
                        </th>
                      <th colspan="2" align="left" class="style1">&nbsp;</th>
                    </tr>
                    <tr>
                        <th align="left" class="style1">is File</th>
                        <th align="left" class="style1">:</th>
                        <th align="left" style="font-style:italic"><input type="checkbox" name="isFile" id="isFile" value="1" onClick="checkDocument();"></th>
                        <th colspan="2" align="left" class="style1">&nbsp;</th>
                    </tr>
                    <tr id="isFileType" style="display:none">
                        <th align="left" class="style1">File Type</th>
                        <th align="left" class="style1">:</th>
                        <th align="left" >
                        	<input type="radio" name="isFileType" id="isFileType" value="1" /> File&nbsp;&nbsp;&nbsp;
                        	<input type="radio" name="isFileType" id="isFileType" value="2" /> Picture
                        </th>
                        <th colspan="2" align="left" class="style1">&nbsp;</th>
                    </tr>
                    <tr id="uploadFile" style="display:none">
                        <th align="left" class="style1">Upload File</th>
                        <th align="left" class="style1">:</th>
                        <th colspan="2" align="left" style="font-style:italic"><input type="file" name="TransCapt" id="TransCapt" size="20"> </th>
                        <th align="left">&nbsp;</th>
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
                        	<input type="submit" class="button_css" name="submit" id="submit" value=" save " align="left"/>
                            <input type="button" name="btnCancel" id="btnCancel" class="button_css" value=" Cancel " onclick="window.close()" />
                        </th>
                    </tr>
                    <tr>
                      <th align="left" class="style1">&nbsp;</th>
                      <th align="left" class="style1">&nbsp;</th>
                      <th colspan="3" align="left" class="style1"><hr></th>
                    </tr> 
                </table>
            </form>        	
        </td>
    </tr>
</table>
</div>
</body>
<script>
	function checkDocument()
	{
		isFile = document.getElementById('isFile');
		if(isFile.checked == true)
		{
			document.getElementById('folderCode').innerHTML = 'File Code';
			document.getElementById('isDocument').style.display = 'none';
			document.getElementById('uploadFile').style.display = '';
			document.getElementById('isFileType').style.display = '';
		}
		else
		{
			document.getElementById('folderCode').innerHTML = 'Folder Code';
			document.getElementById('isDocument').style.display = '';
			document.getElementById('uploadFile').style.display = 'none';
			document.getElementById('isFileType').style.display = 'none';
		}
	}

	function isSubmit()
	{
		document.getElementById("employee_form").submit();
		window.close();	
	}
</script>
</html>