<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 14 Februari 2017
 * File Name	= project_mc.php
 * Location		= -
*/
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$FlagUSER 	= $this->session->userdata('FlagUSER');
$appBody    = $this->session->userdata('appBody');

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
$ISREAD 	= $this->session->userdata['ISREAD'];
$ISCREATE 	= $this->session->userdata['ISCREATE'];
$ISAPPROVE 	= $this->session->userdata['ISAPPROVE'];

$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
if($decFormat == 0)
	$decFormat		= 2;

if(isset($_POST['submitSrch']))
{
	$MC_CODE	= $_POST['selDocNumb'];
	$selDocNumb	= $_POST['selDocNumb'];
}
else
{
	$MC_CODE	= '';
	$selDocNumb	= '';
}
	
// Project List
$sqlPLC	= "tbl_project";
$resPLC	= $this->db->count_all($sqlPLC);

$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
$sql = "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$result = $this->db->query($sql)->result();
foreach($result as $row) :
	$PRJNAME = $row ->PRJNAME;
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
			if($TranslCode == 'Back')$Back = $LangTransl;
			if($TranslCode == 'Code')$Code = $LangTransl;
			if($TranslCode == 'Number')$Number = $LangTransl;
			if($TranslCode == 'MCStep')$MCStep = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'ChargeFiled')$ChargeFiled = $LangTransl;
			if($TranslCode == 'ChargeApproved')$ChargeApproved = $LangTransl;
			if($TranslCode == 'Date')$Date = $LangTransl;
			if($TranslCode == 'EndDate')$EndDate = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'PaidStatus')$PaidStatus = $LangTransl;
			if($TranslCode == 'CheckTheList')$CheckTheList = $LangTransl;
			if($TranslCode == 'MCList')$MCList = $LangTransl;
			if($TranslCode == 'sureDelete')$sureDelete = $LangTransl;
			if($TranslCode == 'sureVoid')$sureVoid = $LangTransl;
		endforeach;

        $comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
			<h1>
		    <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/mc.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $mnName; ?>
		    <small><?php echo $PRJNAME; ?></small>
		  </h1>
		</section>

		<style>
			.search-table, td, th {
				border-collapse: collapse;
			}
			.search-table-outter { overflow-x: scroll; }
			
		    a[disabled="disabled"] {
		        pointer-events: none;
		    }
		</style>

		<script>
			function getValueNo(thisVal)
			{
				myValue = thisVal.value;
				document.getElementById('selDocNumb').value = myValue;
				chooseDocNumb(thisVal);
			}
			
			function chooseDocNumb(thisVal)
			{
				document.frmsrch.submitSrch.click();
			}
		</script>

        <section class="content">
			<div class="box">
				<div class="box-body">
					<form name="frmsrch" id="frmsrch" action="" method=POST style="display:none">
						<input type="text" name="PRJCODE" id="PRJCODE" class="form-control" style="max-width:150px; text-align:left" value="<?php echo $PRJCODE; ?>" />
						<input type="text" name="selDocNumb" id="selDocNumb" class="form-control" style="max-width:150px; text-align:left" value="<?php echo $selDocNumb; ?>" />
					    <input type="submit" class="button_css" name="submitSrch" id="submitSrch" value=" search " />
					</form>
					<div class="search-table-outter">
					   	<table id="example" class="table table-bordered table-striped" width="100%">
					  		<thead>
					            <tr>
					                <th width="10%" style="text-align:center; vertical-align: middle;" nowrap><?php echo $Code ?>  / <?php echo $Number ?> </th>
					                <th width="15%" style="text-align:center; vertical-align: middle;" nowrap> <?php echo $MCStep ?> </th>
					                <th width="45%" style="text-align:center; vertical-align: middle;" nowrap><?php echo $Description ?> </th>
					                <th width="5%" style="text-align:center; vertical-align: middle;" nowrap><?php echo $Status ?> </th>
					                <th width="5%" style="text-align:center; vertical-align: middle;" nowrap><?php echo $PaidStatus ?> </th>
					                <th width="10%" style="text-align:center; vertical-align: middle;" nowrap>&nbsp;</th>
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
		                $secURLPDoc		= site_url('c_project/c_mc180c2c/printdocument/?id='.$this->url_encryption_helper->encode_url($MC_CODE));
		                $secAddURLMC= site_url('c_project/c_mc90up180c2c/a180c2cddmc/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
		                $secAddURLSI	= site_url('c_project/c_mc90up180c2c/addSI/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
		                $secAddCERSI	= site_url('c_project/c_mc90up180c2c/addCERSI/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
						if($ISAPPROVE == 1)
							$ISCREATE	= 1;
							
						if($ISCREATE == 1)
						{					
							echo anchor("$secAddURLMC",'<button type="button" class="btn btn-primary "><i class="glyphicon glyphicon-plus"></i></button>&nbsp;');
						}
		 				echo anchor("$backURL",'&nbsp;<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>');
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
        "ajax": "<?php echo site_url('c_project/C_mc90up180c2c/get_AllData/?id='.$PRJCODE)?>",
        "type": "POST",
        //"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
        "lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
        "columnDefs": [ { targets: [1,3,4], className: 'dt-body-center' },
                        { "width": "100px", "targets": [1] }
                      ],
        "order": [[ 2, "desc" ]],
        "language": {
            "infoFiltered":"",
            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
        },
        } );
    } );
    
    function printD(row)
    {
        var url = document.getElementById('urlPrint'+row).value;
        w = 900;
        h = 550;
        var left = (screen.width/2)-(w/2);
        var top = (screen.height/2)-(h/2);
        window.open(url, 'formpopup', 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
        form.target = 'formpopup';
    }
    
    function voidDOC(row)
    {
        swal({
            text: "<?php echo $sureVoid; ?>",
            icon: "warning",
            buttons: ["No", "Yes"],
        })
        .then((willDelete) => 
        {
            if (willDelete) 
            {
                var collID  = document.getElementById('urlVoid'+row).value;
                var myarr   = collID.split("~");

                var url     = myarr[0];

                $.ajax({
                    type: 'POST',
                    url: url,
                    data: {collID: collID},
                    success: function(response)
                    {
                        swal(response, 
                        {
                            icon: "success",
                        });
                        $('#example').DataTable().ajax.reload();
                    }
                });
            } 
            else 
            {
                //...
            }
        });
    }
    
    function deleteDOC(row)
    {
        var r = confirm("<?php echo $sureDelete; ?>");
        if (r == true) 
        {
            var collID  = document.getElementById('urlDel'+row).value;
            var myarr   = collID.split("~");

            var url     = myarr[0];

            $.ajax({
                type: 'POST',
                url: url,
                data: {collID: collID},
                success: function(response)
                {
                    swal(response);
                    $('#example').DataTable().ajax.reload();
                }
            });
        }
    }

	function printDa()
	{
		var url = '<?php echo $secURLPDoc; ?>';
		title = 'Select Item';
		w = 1024;
		h = 768;
		//window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/3)-(h/3);
		return window.open(url, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
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