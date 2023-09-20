<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 24 Februari 2017
 * File Name	= getrpffile.php
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
$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
$Emp_DeptCode	= $this->session->userdata['Emp_DeptCode'];
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

<script src="<?php echo base_url('assets/js/jquery-1.10.1.min.js') ?>" type="text/javascript"></script>
<?php
	$this->load->view('template/topbar');
	$this->load->view('template/sidebar');
	
	$ISREAD 	= $this->session->userdata['ISREAD'];
	$ISCREATE 	= $this->session->userdata['ISCREATE'];
	$ISAPPROVE 	= $this->session->userdata['ISAPPROVE'];
	$ISDWONL 	= $this->session->userdata['ISDWONL'];
?>

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>
    <?php echo $h2_title; ?>
    <small>RPF File</small>
  </h1>
  <br>
  <?php /*?><ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#">Tables</a></li>
    <li class="active">Data tables</li>
  </ol><?php */?>
</section>
<style>
	.search-table, td, th {
		border-collapse: collapse;
	}
	.search-table-outter { overflow-x: scroll; }
</style>
<!-- Main content -->

<div class="box">
    <div class="box-body">
        <div class="search-table-outter">
        	<form action="" onSubmit="confirmDelete();" method=POST>
                    <table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
                    	<thead>
                            <tr>
                                <th width="4%" style="text-align:center">No.</th>
                                <th width="89%">Nama File</th>
                                <th width="3%" nowrap>File Type</th>
                              <th width="4%">Action</th>
                          </tr>
                        </thead>
                    	<?php
							$FlagUSER	= $this->session->userdata['FlagUSER'];
							$Dir1		= "Temp";
							$Dir2		= "Temp$FlagUSER";
							$dir 		= "C:/$Dir2/";
							//echo "$FlagUSER<br>";
							//echo "$Dir1";
							//echo "$Dir2";
							//echo "$dir";
							return false;
							// Open a known directory, and proceed to read its contents
							if (is_dir($dir)) 
							{
								$type 	= array('folder'=>'folder.png','doc'=>'doc.png','docx'=>'doc.png','xls'=>'xlsx.png','pdf'=>'pdf.png','txt'=>'txt.png','php'=>'php.png','TMP'=>'tmp.png','CDX'=>'cdx.png','RPF'=>'RPF.png','DBF'=>'DBF.png','FPT'=>'FPT.png','EXE'=>'EXE.png','ERH'=>'ERH.png','tmp'=>'tmp.png');
								//$type 	= array('folder'=>'folder.png','RPF'=>'RPF.png');
								$nomor	= 1;
								$dh = opendir($dir);
								if ($dh = opendir($dir)) 
								{
									$nomor = 1;
									$file = readdir($dh);
									return false;
									while (($file = readdir($dh)) !== false) 
									{
										$exp 	= explode('.',$file);
										$icon	= count($exp)==1?'folder':$exp[count($exp)-1];
										// GET FILE NAME
										// echo "$icon<br>";
										
										if($icon == "RPF")
										{
											$proj_CodeX	= $this->session->userdata['SessTempProject']['sessTempProj'];
											$myPath 	= "$dir/$file";
											$RTF_MOD	= date("Y-m-d-H:i:s", filemtime($myPath));
											
											$sqlRTFNm 	= "tbl_rtflistA WHERE RTF_NAME = '$file' AND PRJCODE = '$proj_CodeX'";
											$resRTFNm 	= $this->db->count_all($sqlRTFNm);
																
											//$sqlRTFUpNm	= "UPDATE tbl_rtflistA SET PRJCODE = '$proj_CodeX', RTF_MOD = '$RTF_MOD' WHERE RTF_NAME = '$file'";
											//$resRTFUpNm	= $this->db->query($sqlRTFUpNm);
											echo "heheh $resRTFNm";
											return false;
											if($resRTFNm == 0) // Jika nama file tersebut belum ada, maka tambahkan
											{
												$sqlINSFile 	= "INSERT INTO tbl_rtflistA (PRJCODE, RTF_NAME, RTF_SHOW, RTF_MOD) VALUES ('$proj_CodeX', '$file', 1, '$RTF_MOD')";
												$this->db->query($sqlINSFile);
											}
											else // Apabila sduah ada, maka cek apakah tanggal updtae nya sama
											{
												$sqlGetRTFNmC 	= "SELECT RTF_SHOW, RTF_MOD FROM tbl_rtflistA WHERE RTF_NAME = '$file'";
												$resGetRTFNmC	= $this->db->query($sqlGetRTFNmC)->result();
												foreach($resGetRTFNmC as $rowGetRTFNmC) :
													$RTF_SHOW 	= $rowGetRTFNmC->RTF_SHOW;
													$RTF_MODA 	= $rowGetRTFNmC->RTF_MOD;
												endforeach;
												
												if($RTF_MOD != $RTF_MODA && $RTF_SHOW = 0)
												{
													$sqlRTFUpNm	= "UPDATE tbl_rtflistA SET RTF_SHOW = 1 WHERE RTF_NAME = '$file' a";
													$resRTFUpNm	= $this->db->query($sqlRTFUpNm);
												}
												else
												{
													$sqlRTFUpNm	= "UPDATE tbl_rtflistA SET RTF_SHOW = 0 WHERE RTF_NAME = '$file' b";
													$resRTFUpNm	= $this->db->query($sqlRTFUpNm);
												}
											}
										}
										$RTF_SHOW		= 1;
										$sqlGetRTFNm 	= "SELECT RTF_SHOW FROM tbl_rtflistA WHERE RTF_NAME = '$file'";
										$resGetRTFNm	= $this->db->query($sqlGetRTFNm)->result();
										foreach($resGetRTFNm as $rowGetRTFNm) :
											$RTF_SHOW 	= $rowGetRTFNm->RTF_SHOW;
										endforeach;
										if($RTF_SHOW == 1)
										{
											if($icon == "RPF")
											{
												if($isSearch == 'No')
												{
													if($file=='.' || $file=='..')
													{
														
													}
													else
													{
														if($icon=='folder')
														{
															$link = '<a href="#open" title="Open folder content"><img src="images/open.png" border="0"/></a>';
														}
														else
														{
															$delFileURL	= site_url('c_help/c_getrpffile/delFile/?id='.$this->url_encryption_helper->encode_url($file));
															$link = '<a href="'.base_url().'uploads/'.$Dir2.'/'.$file.'" title="Download file" data-skin="skin-green" class="btn btn-success btn-xs"><i class="fa fa-download"></i> dfd</a> 
																	 <a href="'.$delFileURL.'" title="Delete file" data-skin="skin-green" class="btn btn-success btn-xs"><i class="fa fa-remove" onclick="return confirm(\'Are you sure want to delete this file?\');"></i></a>';
														}
														echo '<tr>
																	<td>&nbsp;'.$nomor.'.</td>
																	<td>'.$file.'</td>
																	<td style="text-align:center"><i class="fa fa-file-text"></i></td>
																	<td>'.$link.'</td>
																 </tr>';
														$nomor++;
													}
												}
												else
												{
													if($file == "$txtSearch2")
													{
														if($file=='.' || $file=='..')
														{
															
														}
														else
														{
															if($icon=='folder')
															{
																$link = '<a href="#open" title="Open folder content"><img src="images/open.png" border="0"/></a>';
															}
															else
															{
																$delFileURL	= site_url('c_help/c_getrpffile/delFile/?id='.$this->url_encryption_helper->encode_url($file));
																$link = '<a href="'.base_url().'uploads/'.$Dir2.'/'.$file2.'" title="Download file" data-skin="skin-green" class="btn btn-success btn-xs"><i class="fa fa-download"></i></a> 
																	 <a href="'.$delFileURL.'" title="Delete file" data-skin="skin-green" class="btn btn-success btn-xs"><i class="fa fa-remove" onclick="return confirm(\'Are you sure want to delete this file?\');"></a>';
															}
															echo '<tr>
																		<td>&nbsp;'.$nomor.'.</td>
																		<td>'.$file.'</td>
																		<td style="text-align:center"><i class="fa fa-file-text"></i></td>
																		<td>'.$link.'</td>
																	 </tr>';
															$nomor++;
														}
													}
												}
											}
										}
									  // echo "filename: $file : filetype: " . filetype($dir . $file) . "<br>";
									}
									closedir($dh);
								}
                    		}
                    	?>
                    </table>
            </form>
        </div>
    </div>
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

<?php 
$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>