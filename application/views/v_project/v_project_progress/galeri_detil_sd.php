<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 31 Mei 2016
 * File Name	= galeri_detil_sd.php
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
$galeri_Kode = $galeri_Kode;
?>
 <?php
	// Searching Function
	$selSearchproj_Code    	= $this->session->userdata['dtSessSrc1']['selSearchproj_Code'];
	$selSearchgrstype1     	= $this->session->userdata['dtSessSrc1']['selSearchgrstype'];
	$selSearchType1     	= $this->session->userdata['dtSessSrc1']['selSearchType'];
	$selSearchbptype1     	= $this->session->userdata['dtSessSrc1']['selSearchbptype'];
	$selInsType1       		= $this->session->userdata['dtSessSrc1']['selInsType'];
	$txtSearch1        		= $this->session->userdata['dtSessSrc1']['txtSearch'];
	if($selInsType1 == '')
	{
		$selInsType1 = 1;
	}
	// Start : Searching Function --- Untuk delete session
	$dataSessSrc = array(
			'selSearchproj_Code' => $selSearchproj_Code,
			'selSearchgrstype' => $selSearchgrstype1,
			'selSearchType' => $selSearchType1,
			'selSearchbptype' => $selSearchbptype1,
			'selInsType' => $selInsType1,
			'txtSearch' => $txtSearch1);
	$this->session->set_userdata('dtSessSrc1', $dataSessSrc);
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

<style type="text/css">@import url("<?php echo base_url() . 'css/style_gal.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'assets/js/JS/fancybox/jquery.fancybox.css'; ?>");</style>
<script language="javascript" src="<?php echo base_url() . 'assets/js/JS/fancybox/jquery.fancybox.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/js/JS/fancybox/jquery.mousewheel.js'; ?>"></script>


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
            <table width="100%" border="0" style="size:auto">
              <tr>
                <td colspan="3" class="style2"><?php echo $h2_title; ?></td>
              </tr>
            </table>
            </div>
        </td>
    </tr>
	<tr>
    	<td>
        	
        </td>
    </tr>
</table>
    
<form method="post" name="frmSearch" action="">
  <table width="100%" border="0" cellpadding="0" cellspacing="0" id="detailPR">
        <tr>
            <td>&nbsp;</td>
      </tr>
        <tr>
            <td><hr /></td>
        </tr>
        <tr>
            <td>                
                <ul id='produk'>
                    <?php
						$QGaleri0	= "SELECT * FROM sd_tgaleri_detail WHERE galeri_Kode = '$galeri_Kode'";
						$QGaleri 	= $this->db->query($QGaleri0)->result();
						foreach($QGaleri as $AGaleri) :
							$id 		= $AGaleri->id;
							$file_Name 	= $AGaleri->file_Name;
							$keterangan	= $AGaleri->keterangan;		
							$MyImg 		= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/c_project_progress_sd'),'ShowImageFull', array('param' => $file_Name));		
							?>
							<li class="lis-produk">
								<h3><?php echo $keterangan;?></h3>
								<a class="fancybox" href="<?php echo $MyImg; ?>" data-fancybox-group="gallery" title="<?php echo $file_Name;?>">
									<img src="<?php echo base_url() . 'images/foto/'.$file_Name.''; ?>" alt="">
								</a>
							</li>
							<?php
						endforeach;
                    ?>
                </ul>
            </td>
        </tr>
    </table>
</form>
 </div>
</body>
</html>