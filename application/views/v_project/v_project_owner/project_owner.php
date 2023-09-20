<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 9 Februari 2017
 * File Name	= M_project_owner.php
 * Location		= -
*/
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$FlagUSER 	= $this->session->userdata['FlagUSER'];
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
$appBody    = $this->session->userdata('appBody');

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
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $appName; ?></title>
        <!-- Tell the browser to be responsive to screen width -->
        <?php
          	$vers   = $this->session->userdata['vers'];

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
			if($TranslCode == 'Code')$Code = $LangTransl;
			if($TranslCode == 'Name')$Name = $LangTransl;
			if($TranslCode == 'Address')$Address = $LangTransl;
			if($TranslCode == 'Phone')$Phone = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'OwnerList')$OwnerList = $LangTransl;
			if($TranslCode == 'Project')$Project = $LangTransl;
			if($TranslCode == 'sureNonAct')$sureNonAct = $LangTransl;
			if($TranslCode == 'dataDisabl')$dataDisabl = $LangTransl;
			if($TranslCode == 'sureDAct')$sureDAct = $LangTransl;
			if($TranslCode == 'dataAct')$dataAct = $LangTransl;
		endforeach;
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

	<?php

        $comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
			<h1>
			    <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/owner.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $OwnerList; ?>
			    <small><?php echo $Project; ?></small>
			  </h1>
			  <?php /*?><ol class="breadcrumb">
			    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			    <li><a href="#">Tables</a></li>
			    <li class="active">Data tables</li>
			  </ol><?php */?>
		</section>

        <section class="content">
			<div class="box">
				<div class="box-body">
					<div class="search-table-outter">
				      	<table id="example" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
					        <thead>
					            <tr>
					                <th style="vertical-align:middle; text-align:center" width="2%">No</th>
					              	<th style="vertical-align:middle; text-align:center" width="15%"><?php echo $Code ?></th>
					                <th style="vertical-align:middle; text-align:center" width="25%"><?php echo $Name ?></th>
					                <th style="vertical-align:middle; text-align:center" width="50%"><?php echo $Address ?></th>
					                <th style="vertical-align:middle; text-align:center" width="3%">&nbsp;</th>
					                <th style="vertical-align:middle; text-align:center" width="5%">&nbsp;</th>
					            </tr>
					        </thead>
					        <tbody>
					        </tbody>
					        <tfoot>
					        </tfoot>
				   		</table>
				    </div>
				    <br>
					<?php
						if($ISCREATE == 1)
						{
							echo anchor("$secAddURL",'<button class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i></button>');
						}
					?>
				</div>
			</div>
        	<?php
				$act_lnk = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        		if($DefEmp_ID == 'D15040004221')
                	echo "<font size='1'><i>$act_lnk</i></font>";
            ?>
		</section>
	</body>
</html>

<script>
	$(document).ready(function() {
    $('#example').DataTable( {
        "processing": true,
        "serverSide": true,
		//"scrollX": false,
		"autoWidth": true,
		"filter": true,
        "ajax": "<?php echo site_url('c_project/o180c2gner/get_AllData/?id=')?>",
        "type": "POST",
		//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
		"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
		"columnDefs": [	{ targets: [0,4,5], className: 'dt-body-center' },
						//{ targets: [3], className: 'dt-body-right' },
						{ "width": "100px", "targets": [1] }
					  ],
		"language": {
            "infoFiltered":"",
            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
        },
		} );
	} );
	
	function deleteDOC(row)
	{
		swal({
			text: "<?php echo $sureNonAct; ?>",
			icon: "warning",
			buttons: ["Yes", "No"],
		})
		.then((willDelete) => 
		{
			if (willDelete) 
			{
				// ...
			} 
			else 
			{
				var collID	= document.getElementById('urlDel'+row).value;
	        	var myarr 	= collID.split("~");
	        	var urlC 	= myarr[0];
	        	var ownC 	= myarr[1];
				$.ajax({
                    type: 'POST',
                    url: urlC,
                    data: "ownC="+ownC,
                    success: function(msg)
                    {
                        //swal(msg)
                    }
                });
				swal("<?php echo $dataDisabl; ?>", 
				{
					icon: "success",
				});
				$('#example').DataTable().ajax.reload();
			}
		});
	}
	
	function reActivated(row)
	{
		swal({
			text: "<?php echo $sureDAct; ?>",
			icon: "warning",
			buttons: ["No", "Yes"],
		})
		.then((willDelete) => 
		{
			if (willDelete) 
			{
				var collID	= document.getElementById('urlAct'+row).value;
	        	var myarr 	= collID.split("~");
	        	var urlC 	= myarr[0];
	        	var ownC 	= myarr[1];
				$.ajax({
                    type: 'POST',
                    url: urlC,
                    data: "ownC="+ownC,
                    success: function(msg)
                    {
                        //swal(msg)
                    }
                });
				swal("<?php echo $dataAct; ?>", 
				{
					icon: "success",
				});
				$('#example').DataTable().ajax.reload();
			} 
			else 
			{
				//..
			}
		});
	}
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