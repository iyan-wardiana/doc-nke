<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 11 Maret 2017
 * File Name	= project_invoice.php
 * Location		= -
*/
$this->load->view('template/head');

$appName    = $this->session->userdata('appName');
$appBody    = $this->session->userdata['appBody'];
$FlagUSER   = $this->session->userdata['FlagUSER'];
$DefEmp_ID  = $this->session->userdata['Emp_ID'];
$DEPCODE    = $this->session->userdata['DEPCODE'];

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
	
$sql = "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$result = $this->db->query($sql)->result();
foreach($result as $row) :
	$PRJNAME = $row ->PRJNAME;
endforeach;

$selDocNumb			= '';
$PINV_MMC1			= '';

if(isset($_POST['submit']))
{
	$selDocNumb 	= $_POST['selDocNumb'];
	$PINV_MMC1	 	= $_POST['PINV_MMC1'];
}

$showIdxAll		= site_url('c_project/project_invoice/get_last_ten_projmc/?id='.$this->url_encryption_helper->encode_url($PRJCODE));

$username 		= $this->session->userdata['username'];
$writeEMP 		= $this->session->userdata['writeEMP'];
$editEMP 		= $this->session->userdata['editEMP'];
$readEMP 		= $this->session->userdata['readEMP'];
if($writeEMP == 1 || $editEMP == 1)
{
	$isOpen = 1;
}
else
{
	$isOpen = 0;
}
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
            if($TranslCode == 'Code')$Code = $LangTransl;
            if($TranslCode == 'Number')$Number = $LangTransl;
            if($TranslCode == 'PaidStatus')$PaidStatus = $LangTransl;
			if($TranslCode == 'Edit')$Edit = $LangTransl;
			if($TranslCode == 'InvoiceNumber')$InvoiceNumber = $LangTransl;
			if($TranslCode == 'ManualNumber')$ManualNumber = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'Date')$Date = $LangTransl;
			if($TranslCode == 'EndDate')$EndDate = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'Print')$Print = $LangTransl;
			if($TranslCode == 'sureVoid')$sureVoid = $LangTransl;
            if($TranslCode == 'Section')$Section = $LangTransl;
            if($TranslCode == 'InvoiceAmount')$InvoiceAmount = $LangTransl;
		endforeach;
		if($LangID == 'IND')
		{
			$h1_title	= "Daftar Faktur";
			$sureDelete	= "Anda yakin akan menghapus data ini?";
		}
		else
		{
			$h1_title	= "Invoice List";
			$sureDelete	= "Are your sure want to delete?";
		}

        $comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
            <h1>
			    <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/list.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $h1_title; ?>
			    <small><?php echo $PRJNAME; ?></small>
            </h1>
		</section>

        <style type="text/css">
            .search-table, td, th {
                border-collapse: collapse;
            }
            .search-table-outter { overflow-x: scroll; }

            a[disabled="disabled"] {
                pointer-events: none;
            }
        </style>

        <section class="content">
			<div class="box">
				<div class="box-body">
				    <div class="search-table-outter">
				      	<form name="frmselect" id="frmselect" action="" method=POST>
				            <input type="hidden" name="selDocNumb" id="selDocNumb" value="<?php echo $selDocNumb; ?>" />
				            <input type="hidden" name="PINV_MMC1" id="PINV_MMC1" value="<?php echo $PINV_MMC1; ?>" />
				            <input type="submit" class="button_css" name="submit" id="submit" value=" search " style="display:none" />
				      	</form>
				        <table id="example" class="table table-bordered table-striped" width="100%">
				            <thead>
					            <tr>
                                    <th width="10%" style="text-align:center; vertical-align: middle;" nowrap><?php echo $Code ?>  / <?php echo $Number ?> </th>
                                    <th width="5%" style="text-align:center; vertical-align: middle;" nowrap> <?php echo $Section ?></th>
                                    <th width="10%" style="text-align:center; vertical-align: middle;" nowrap><?php echo $Date ?>  </th>
                                    <th width="30%" style="text-align:center; vertical-align: middle;" nowrap><?php echo $Description ?> </th>
                                    <th width="10%" style="text-align:center; vertical-align: middle;" nowrap><?php echo $PaidStatus ?> </th>
                                    <th width="10%" style="text-align:center; vertical-align: middle;" nowrap><?php echo $InvoiceAmount ?> </th>
                                    <th width="5%" style="text-align:center; vertical-align: middle;" nowrap><?php echo $Status ?> </th>
                                    <th width="5%" style="text-align:center; vertical-align: middle;" nowrap>&nbsp;</th>
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
                            echo anchor("$addURL",'<button class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i></button>&nbsp;&nbsp;');

                            echo anchor("$backURL",'<button class="btn btn-danger"><i class="fa fa-reply"></i></button>');
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
        "ajax": "<?php echo site_url('c_project/c_prj180c2dinv/get_AllData/?id='.$PRJCODE)?>",
        "type": "POST",
        //"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
        "lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
        "columnDefs": [ { targets: [1,4,6], className: 'dt-body-center' },
                        { "width": "100px", "targets": [1] },
                        { "width": "150px", "targets": [2] }
                      ],
        "order": [[ 3, "desc" ]],
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