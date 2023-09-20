<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 23 Desember 2015
 * File Name	= sync_db.php
 * Function		= -
 * Location		= -
*/
?>

<?php	
	$my_file	= base_url() . 'system/application/views/v_help/v_sync_db/HAHAAH.txt';
	$namafile = "D:\mySyncDianjk.ffs_batch";  
	/*$handle = fopen ($my_file, "r");  
	if (!$handle) 
	{  
		echo "<b>File tidak dapat dibuka atau belum ada</b>";  
	}
	else
	{  
		echo "<b>File berhasil dibuka</b>";  
	} */ 
	//fclose($handle);
	
	//$handle = exec($namafile);
?>
<div class="HCSSTableGenerator">
<form name="mySync" id="mySync" action="<?php echo $my_file; ?>">
<table width="100%" border="0">
    <tr height="20">
        <td colspan="3"><b><?php echo $h1_title; ?></b></td>
    </tr>
    <tr height="20">
        <td width="32%" valign="top"><b><?php echo $h2_title; ?></b></td>
        <td width="1%" valign="top"><b>:</b></td>
		<td width="67%"><input type="submit" value="Database Sync" class="button_css" /></td>
    </tr>
</table>
</form>
</div>
<div class="HCSSTableGenerator"></div>
<script>
	function downloadFile(fileName)
	{
		if($src ==  "xyyx")
		{
			$pth    =   file_get_contents(base_url()."path/to/the/file.pdf");
			$nme    =   "sample_file.pdf";
			force_download($nme, $pth);     
		}
	}
</script>