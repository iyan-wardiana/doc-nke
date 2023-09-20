<?php
/*  
	* Author		= Hendar Permana 
	* Create Date	= 1 Maret 2017 
	* File Name	= v_office_inventory.php 
	* Location		= -
*/ 

// _global function
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody    = $this->session->userdata['appBody'];

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
?>

<style>
	.search-table, td, th {
		border-collapse: collapse;
	}
	.search-table-outter { overflow-x: scroll; }
	
    a[disabled="disabled"] {
        pointer-events: none;
    }
</style>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $appName; ?></title>
        <!-- Tell the browser to be responsive to screen width -->
        <?php
            $vers 	= $this->session->userdata['vers'];

            $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'css' AND isAct = 1 AND cssjs_vers IN ('$vers', 'All')";
            $rescss = $this->db->query($sqlcss)->result();
            foreach($rescss as $rowcss) :
                $cssjs_lnk  = $rowcss->cssjs_lnk;
                ?>
                    <link rel="stylesheet" href="<?php echo base_url($cssjs_lnk) ?>">
                <?php
            endforeach;

            $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'jss' AND isAct = 1 AND cssjs_vers IN ('$vers', 'All')";
            $rescss = $this->db->query($sqlcss)->result();
            foreach($rescss as $rowcss) :
                $cssjs_lnk1  = $rowcss->cssjs_lnk;
                ?>
                    <script src="<?php echo base_url($cssjs_lnk1) ?>"></script>
                <?php
            endforeach;
        ?>

        <!-- Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    </head>

	<script src="<?php echo base_url('assets/js/jquery-1.10.1.min.js') ?>" type="text/javascript"></script>
	<?php
		$this->load->view('template/mna');
		//______$this->load->view('template/topbar');
		//______$this->load->view('template/sidebar');
		
		$ISREAD 	= $this->session->userdata['ISREAD'];
		$ISCREATE 	= $this->session->userdata['ISCREATE'];
		$ISAPPROVE 	= $this->session->userdata['ISAPPROVE'];
		$ISDWONL 	= $this->session->userdata['ISDWONL'];
		$LangID 	= $this->session->userdata['LangID'];

		$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
		$resTransl		= $this->db->query($sqlTransl)->result();
		foreach($resTransl as $rowTransl) :
			$TranslCode	= $rowTransl->MLANG_CODE;
			$LangTransl	= $rowTransl->LangTransl;
			
			if($TranslCode == 'Add')$Add = $LangTransl;
			if($TranslCode == 'Edit')$Edit = $LangTransl;
			if($TranslCode == 'Delete')$Delete = $LangTransl;
			if($TranslCode == 'Code')$Code = $LangTransl;
			if($TranslCode == 'Name')$Name = $LangTransl;
			if($TranslCode == 'Contact')$Contact = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'Action')$Action = $LangTransl;

		endforeach;

        $comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
        <section class="content-header">
            <h1>
	            <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/list.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $mnTitl; ?>
	            <small><?php echo $mnName; ?></small>
            </h1>
        </section>

		<style>
			.search-table, td, th {
				border-collapse: collapse;
			}
			.search-table-outter { overflow-x: scroll; }
		</style>

        <section class="content">
            <div class="box">
        		<div class="box-body">
                    <div class="search-table-outter">
				      	<table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
					        <thead>
					            <tr>
					                <th width="3%" style="text-align:center;">No.</th>
					                <th width="15%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Code ?> </th>
					           	  	<th width="53%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Name ?> </th>
					                <th width="53%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Contact ?> </th>
					  	            <th width="29%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Status ?> </th>
									<th width="8%" style="text-align:center; vertical-align:middle"><?=$Action?></th>
					            </tr>
					        </thead>
					        <tbody>
								<?php
									$i = 0;
									$j = 0;

									if($recordcount >0)
									{ 
										foreach($vAssetGroup as $row) :
											$myNewNo 	= ++$i;
											$DRIV_CODE 	= $row->DRIV_CODE;
											$DRIV_NAME 	= $row->DRIV_NAME;
											$DRIV_ID 	= $row->DRIV_ID;
											$DRIV_CONT 	= $row->DRIV_CONTACT;
											$DRIV_STAT	= $row->DRIV_STAT;
											
											$secUpd			= site_url('c_setting/c_driver/update/?id='.$this->url_encryption_helper->encode_url($DRIV_CODE));
				                            if ($j==1) {
				                                echo "<tr class=zebra1>";
				                                $j++;
				                            } else {
				                                echo "<tr class=zebra2>";
				                                $j--;
				                            }
											?>
					                            <td style="text-align:center"><?php echo $myNewNo; ?></td>
					                            <td nowrap style="text-align:center"> <?php echo $DRIV_CODE;?></td>
					                            <td nowrap style="text-align:left"><?php echo $DRIV_NAME; ?> </td>
					                            <td nowrap style="text-align:left"><?php echo $DRIV_CONT; ?> </td>
					                            <td nowrap style="text-align:center">
												<?php
				                                
				                                    if ($DRIV_STAT==0)
				                                        $DRIV_STATx='Ready';
				                                    elseif ($DRIV_STAT==1)
				                                        $DRIV_STATx='Used';
				                                        
				                                    echo $DRIV_STATx; 
				                                 
				                                ?>
					                            </td>
					                            <td width="8%" style="text-align:center; vertical-align:middle">
												 	<?php
														//$VH_CODE	= $rowMenu["VH_CODE"];
														$url_update 	= site_url('c_setting/c_driver/update/?id='.$this->url_encryption_helper->encode_url($DRIV_CODE));
														$url_delete 	= site_url('c_setting/c_driver/delete/?id='.$this->url_encryption_helper->encode_url($DRIV_CODE));
						                         	?>
							                        <a href="<?=$url_update?>" class="btn btn-success btn-xs" title="<?=$Edit?>"><i class="fa fa-edit"></i></a>
							                        &nbsp;&nbsp;&nbsp;
							                        <a href="<?=$url_delete?>" class="btn btn-danger btn-xs" title="<?=$Delete?>" onclick="javascript: return confirm('Anda yakin hapus: <?php echo $DRIV_NAME;?> ?')"><i class="fa fa-trash-o"></i></a>
				                        		</td>
				                            
							           		</tr>
											<?php 
										endforeach; 
									}
								?>
			        		</tbody>
			        		<tfoot>
								<tr>
								<td colspan="4" style="text-align:left">
									<?php
								        if($ISCREATE == 1 || $DefEmp_ID = 'D15040004221')
								        {
								            echo anchor("$secAddURL",'<button class="btn btn-primary"><i class="fa fa-plus"></i></button>');
								        }
								    ?></td>
								</tr>
							</tfoot>
		   				</table>
    				</div>
                    <?php
                        $DefID      = $this->session->userdata['Emp_ID'];
                        $act_lnk    = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                        if($DefID == 'D15040004221')
                            echo "<font size='1'><i>$act_lnk</i></font>";
                    ?>
    			</div>
			</div>
		</section>
	</body>
</html>
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
<?php
    $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'js' AND isAct = 1 AND cssjs_vers IN ('$vers', 'All')";
    $rescss = $this->db->query($sqlcss)->result();
    foreach($rescss as $rowcss) :
        $cssjs_lnk  = $rowcss->cssjs_lnk;
        ?>
            <script src="<?php echo base_url($cssjs_lnk) ?>"></script>
        <?php
    endforeach;

    // Right side column. contains the Control Panel
    //______$this->load->view('template/aside');

    //______$this->load->view('template/js_data');

    //______$this->load->view('template/foot');
?>