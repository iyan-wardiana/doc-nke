<?php
	$FileUpName = 'document_01.pdf';
	$myPath = 'system/application/views/v_document_log/document_list/'.$FileUpName;
	$file = base_url() . "$myPath"; # read file into array
	
	header('Content-type: application/pdf');
	header('Content-Disposition: inline; filename="$file "');
	header('Content-Length: ' . filesize($file));
	//ob_clean();
	//flush();
	readfile($file);
	//exit;
	
?>
<embed src="<?php echo $file; ?>" na