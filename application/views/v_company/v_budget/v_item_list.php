<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 5 April 2017
	* File Name	= item_list.php
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

$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
$PRJCODE		= $PRJCODE;
$sqlPRJ 		= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE  = '$PRJCODE'";
$resultPRJ 		= $this->db->query($sqlPRJ)->result();
foreach($resultPRJ as $rowPRJ) :
	$PRJNAME 	= $rowPRJ->PRJNAME;
endforeach;
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $appName; ?></title>
        <!-- Tell the browser to be responsive to screen width -->
        <?php
          $vers     = $this->session->userdata['vers'];

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
			if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
			if($TranslCode == 'ItemName')$ItemName = $LangTransl;
			if($TranslCode == 'Quantity')$Quantity = $LangTransl;
			if($TranslCode == 'BudgetQty')$BudgetQty = $LangTransl;
			if($TranslCode == 'ReceiptQty')$ReceiptQty = $LangTransl;
			if($TranslCode == 'Remain')$Remain = $LangTransl;
			if($TranslCode == 'Used')$Used = $LangTransl;
			if($TranslCode == 'OnHand')$OnHand = $LangTransl;
			if($TranslCode == 'Price')$Price = $LangTransl;
			if($TranslCode == 'UnitName')$UnitName = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'ItemList')$ItemList = $LangTransl;
			if($TranslCode == 'Upload')$Upload = $LangTransl;
			if($TranslCode == 'Back')$Back = $LangTransl;
			if($TranslCode == 'Ordered')$Ordered = $LangTransl;
		endforeach;

		$selPRJSYNC	= '';
		if(isset($_POST['submitSYNC']))
		{
			$selPRJSYNC = $_POST['selPRJSYNC'];
			
			$sqlITMSYN	= "SELECT ITM_CODE, ITM_UNIT FROM tbl_item WHERE PRJCODE = '$selPRJSYNC' AND STATUS = 1";
			$resITMSYN 	= $this->db->query($sqlITMSYN)->result();
			foreach($resITMSYN as $rowSYNC) :
				$ITM_CODE 	= $rowSYNC->ITM_CODE;
				$ITM_UNIT 	= $rowSYNC->ITM_UNIT;
				
				// GET ALL BUDGET FROM JOBLIST DETAIL
				$TOT_BUDVOLM	= 0;
				$TOT_BUDAM 		= 0;
				$PRICE_AVG		= 0;
				if($ITM_UNIT == 'LS' || $ITM_UNIT == 'ls')
				{
					$sqlJLD	= "SELECT SUM(ITM_VOLM) AS TOT_BUDVOLM, SUM(ITM_BUDG) AS TOT_BUDAM,
										SUM(ADD_VOLM) AS TOT_ADDVOLM, SUM(ADD_JOBCOST) AS TOT_ADDCOST,
										SUM(ADDM_VOLM) AS TOT_ADDMVOLM, SUM(ADDM_JOBCOST) AS TOT_ADDMCOST
									FROM
										tbl_joblist_detail
									WHERE PRJCODE = '$selPRJSYNC' AND ITM_CODE = '$ITM_CODE' AND ITM_UNIT = '$ITM_UNIT'";
					$resJLD	= $this->db->query($sqlJLD)->result();
					foreach($resJLD as $rowJLD) :
						$TOT_BUDVOLM1 	= $rowJLD->TOT_BUDVOLM;
						$TOT_BUDAM1 	= $rowJLD->TOT_BUDAM;
						$TOT_ADDVOLM1 	= $rowJLD->TOT_ADDVOLM;
						$TOT_ADDCOST1 	= $rowJLD->TOT_ADDCOST;
						$TOT_ADDMVOLM1 	= $rowJLD->TOT_ADDMVOLM;
						$TOT_ADDMCOST1 	= $rowJLD->TOT_ADDMCOST;
						$TOT_BUDVOLM	= 1;
						$TOT_BUDAM		= $TOT_BUDAM1 + $TOT_ADDCOST1 - $TOT_ADDMCOST1;
						$PRICE_AVG		= $TOT_BUDAM / $TOT_BUDVOLM;
					endforeach;
				}
				else
				{
					$sqlJLD	= "SELECT SUM(ITM_VOLM) AS TOT_BUDVOLM, SUM(ITM_BUDG) AS TOT_BUDAM,
										SUM(ADD_VOLM) AS TOT_ADDVOLM, SUM(ADD_JOBCOST) AS TOT_ADDCOST,
										SUM(ADDM_VOLM) AS TOT_ADDMVOLM, SUM(ADDM_JOBCOST) AS TOT_ADDMCOST
									FROM
										tbl_joblist_detail
									WHERE PRJCODE = '$selPRJSYNC' AND ITM_CODE = '$ITM_CODE' AND ITM_UNIT = '$ITM_UNIT'";
					$resJLD	= $this->db->query($sqlJLD)->result();
					foreach($resJLD as $rowJLD) :
						$TOT_BUDVOLM1 	= $rowJLD->TOT_BUDVOLM;
						$TOT_BUDAM1 	= $rowJLD->TOT_BUDAM;
						$TOT_ADDVOLM1 	= $rowJLD->TOT_ADDVOLM;
						$TOT_ADDCOST1 	= $rowJLD->TOT_ADDCOST;
						$TOT_ADDMVOLM1 	= $rowJLD->TOT_ADDMVOLM;
						$TOT_ADDMCOST1 	= $rowJLD->TOT_ADDMCOST;
						$TOT_BUDVOLM	= $TOT_BUDVOLM1 + $TOT_ADDVOLM1 - $TOT_ADDMVOLM1;
						$TOT_BUDAM		= $TOT_BUDAM1 + $TOT_ADDCOST1 - $TOT_ADDMCOST1;
						$PRICE_AVG		= $TOT_BUDAM / $TOT_BUDVOLM;
					endforeach;
				}
				
				$sqlUpdCOA		= "UPDATE tbl_item SET 
										ITM_VOLMBG = $TOT_BUDVOLM
									WHERE PRJCODE = '$selPRJSYNC' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($sqlUpdCOA);
			endforeach;
		}
		$isLoadDone	= 0;
		$isSyncDone	= 0;
	?>

	<style>
        .search-table, td, th {
            border-collapse: collapse;
        }
        .search-table-outter { overflow-x: scroll; }
    </style>

	<script>
		function syncJournal()
		{
			document.getElementById('loading_1').style.display = '';
			document.frmsync.submitSYNC.click();
		}
	</script>
	<?php

		$comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
	    <section class="content-header">
	        <h1>
	        <?php echo $ItemList; ?>
	        <small><?php echo "$PRJNAME"; ?></small>
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
		                <form name="frmsync" id="frmsync" action="" method=POST>
		                    <input type="hidden" name="selPRJSYNC" id="selPRJSYNC" value="<?php echo $PRJCODE; ?>">
		                    <input type="submit" name="submitSYNC" id="submitSYNC" style="display:none">
		                </form>
		                <table id="example" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
		                    <thead>
		                    <tr>
		                        <th width="10%" rowspan="2" style="vertical-align:middle; text-align:center"><?php echo $ItemCode ?> </th>
		                        <th width="50%" rowspan="2" style="vertical-align:middle; text-align:center"><?php echo $ItemName ?> </th>
		                        <th colspan="5" style="vertical-align:middle; text-align:center"><?php echo $Quantity ?> </th>
		                        <!-- <th width="6%" rowspan="2" style="vertical-align:middle; text-align:center"><?php echo $Price ?> </th> -->
		                        <th width="3%" rowspan="2" style="vertical-align:middle; text-align:center" nowrap>Unit</th>
		                        <th width="2%" rowspan="2" style="vertical-align:middle; text-align:center"><?php echo $Status ?> </th>
		                        <th width="3%" rowspan="2" style="vertical-align:middle; text-align:center">&nbsp;</th>
		                    </tr>
		                    <tr>
		                        <th width="5%" style="vertical-align:middle; text-align:center" nowrap><?php echo $BudgetQty; ?> </th>
		                        <th width="5%" style="vertical-align:middle; text-align:center" nowrap><?php echo $Ordered; ?> </th>
		                        <th width="5%" style="vertical-align:middle; text-align:center" nowrap><?php echo $ReceiptQty; ?></th>
		                        <th width="5%" style="vertical-align:middle; text-align:center" nowrap><?php echo $Used; ?></th>
		                        <th width="6%" style="vertical-align:middle; text-align:center" nowrap><?php echo $OnHand; ?> </th>
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
                        $secAddURL = site_url('c_comprof/c_bUd93tL15t/a180e2edd/?id='.$this->url_encryption_helper->encode_url($PRJCODE));

                        if($DefEmp_ID == 'D15040004221')
                        {							
                            //echo anchor("$secAddURL",'<button class="btn btn-primary" title="'.$Add.'"><i class="glyphicon glyphicon-plus"></i></button>&nbsp;&nbsp;');
                            echo anchor("$secUpl",'<button class="btn btn-warning" title="'.$Upload.'"><i class="glyphicon glyphicon-import"></i></button>');
                            echo '&nbsp;&nbsp;<button class="btn btn-info" onClick="syncJournal()" title="Sync"><i class="glyphicon glyphicon-refresh"></i></button>&nbsp;&nbsp;';
                        }
                        else if($ISCREATE == 1)
                        {
                            echo anchor("$secUpl",'<button class="btn btn-warning" title="'.$Upload.'"><i class="glyphicon glyphicon-import"></i></button>');
                            echo '&nbsp;&nbsp;<button class="btn btn-info" onClick="syncJournal()" title="Sync"><i class="glyphicon glyphicon-refresh"></i></button>&nbsp;&nbsp;';
                        }
                        echo anchor("$backURL",'<button class="btn btn-danger" title="'.$Back.'"><i class="fa fa-reply"></i></button>');
                    ?>
				</div>
		    </div>
		    <div id="loading_1" class="overlay" style="display:none">
		        <i class="fa fa-refresh fa-spin"></i>
		    </div>
            <?php
                $DefID      = $this->session->userdata['Emp_ID'];
                $act_lnk    = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                if($DefID == 'D15040004221')
                    echo "<font size='1'><i>$act_lnk</i></font>";
            ?>
		</section>
    
	    <?php 
			$isLoadDone = 1;
			if($isLoadDone == 1)
			{
				?>
					<script>
						document.getElementById('loading_1').style.display = 'none';
	                </script>
	    		<?php
			}
		?>
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
        "ajax": "<?php echo site_url('c_inventory/c_it180e2elst/get_AllData/?id='.$PRJCODE)?>",
        "type": "POST",
		"lengthMenu": [[50, 100, 200], [50, 100, 200]],
		"columnDefs": [	{ targets: [2,3,4,5,6], className: 'dt-body-right' },
					   	{ targets: [0,7,8], className: 'dt-body-center' },
					   	{ targets: [0], className: 'dt-body-nowrap' },
						{ "width": "100px", "targets": [1] },
					  ],
		"language": {
            "infoFiltered":"",
            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
        },
    } );
} );
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