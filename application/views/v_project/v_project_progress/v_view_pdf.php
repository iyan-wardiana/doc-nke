<?php
	$this->load->library('pdf'); 
	$FileUpName = 'document_01.pdf';
	$myPath = 'system/application/views/v_document_log/document_list/'.$FileUpName;
	$nama_file = base_url() . "$myPath"; # read file into array
	
	//header('Content-type: application/pdf');
	//header('Content-Disposition: inline; filename="$file "');
	//header('Content-Length: ' . filesize($file));
	//ob_clean();
	//flush();
	//readfile($file);
	//exit;
	//$dpdf="http://mysrver.com/file/".$pdf['data']."";
	/*$dpdf = $file;
	echo"<iframe src='http://docs.google.com/gview?url=$dpdf&embedded=true' style='width:600px; 
	height:500px;' frameborder='0'>
	</iframe>";*/	
	//echo "<iframe src=\"$nama_file\" width=\"100%\" style=\"height:100%\"></iframe>";
?>
<object data="<?php echo $nama_file; ?>" type="application/pdf" width="500" height="500">
</object> 