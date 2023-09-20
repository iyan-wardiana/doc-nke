<?php
/*  
    * Author		= Dian Hermanto
    * Create Date	= 22 Oktober 2019
    * File Name	= v_machine.php
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
    		if($TranslCode == 'Code')$Code = $LangTransl;
    		if($TranslCode == 'ManualCode')$ManualCode = $LangTransl;
    		if($TranslCode == 'machineNm')$machineNm = $LangTransl;
    		if($TranslCode == 'Description')$Description = $LangTransl;
    		if($TranslCode == 'INVStep')$INVStep = $LangTransl;
    		if($TranslCode == 'StdCost')$StdCost = $LangTransl;
    		if($TranslCode == 'Approve')$Approve = $LangTransl;
    		if($TranslCode == 'User')$User = $LangTransl;
    		if($TranslCode == 'Back')$Back = $LangTransl;
    	endforeach;
    	if($LangID == 'IND')
    	{
    		$sureDelete	= "Anda yakin akan menghapus data ini?";
    	}
    	else
    	{
    		$sureDelete	= "Are your sure want to delete?";
    	}

        $comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
        <section class="content-header">
            <h1>
            <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/list.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $mnName; ?>
            <small><?php //echo $h2_title; ?></small>
            </h1>
        </section>

        <section class="content">
            <div class="box">
                <!-- /.box-header -->
        		<div class="box-body">
                    <div class="search-table-outter">
                        <table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
                            <thead>
                            <tr>
                                <!--<th width="4%" rowspan="2" style="vertical-align:middle; text-align:center"><input name="chkAll" id="chkAll" type="checkbox" value="" /></th>-->
                                <th width="6%" style="vertical-align:middle; text-align:center">No</th>
                                <th width="2%" style="vertical-align:middle; text-align:center; display:none"><?php echo $Code ?> </th>
                                <th width="10%" style="vertical-align:middle; text-align:center" nowrap><?php echo $ManualCode ?> </th>
                                <th width="5%" style="vertical-align:middle; text-align:center; display:none" nowrap><?php echo $INVStep ?> </th>
                                <th width="25%" style="vertical-align:middle; text-align:center"><?php echo $machineNm  ?> </th>
                                <th width="38%" style="vertical-align:middle; text-align:center"><?php echo $Description ?> </th>
                                <th width="8%" style="vertical-align:middle; text-align:center" nowrap><?php echo $StdCost ?> </th>
                                <th width="6%" style="vertical-align:middle; text-align:center">&nbsp;</th>
                            </tr>
                            </thead>
                            <tbody> 
                                <?php 
                                $i = 0;
                                $j = 0;
                                if($countSTP > 0)
                                {
                                    foreach($vwSTP as $row) :
                                        $myNewNo 	= ++$i;
                                        $MCN_NUM    = $row->MCN_NUM;
                                        $MCN_CODE 	= $row->MCN_CODE;
                                        $MCN_NAME 	= $row->MCN_NAME;
                                        $MCN_DESC 	= $row->MCN_DESC;
                                        $MCN_ITMCAL = $row->MCN_ITMCAL;
                                        $MCN_STAT 	= $row->MCN_STAT;

        								if($MCN_STAT == 0)
        								{
        									$MCN_STATD 	= 'Non Aktif';
        									$STATCOL	= 'danger';
        								}
        								elseif($MCN_STAT == 1)
        								{
        									$MCN_STATD 	= 'Aktif';
        									$STATCOL	= 'success';
        								}

                                        $secUpd         = site_url('c_setting/c_m4ch1n/u775o_m4ch/?id='.$this->url_encryption_helper->encode_url($MCN_NUM));
                                        if ($j==1) {
                                            echo "<tr class=zebra1>";
                                            $j++;
                                        } else {
                                            echo "<tr class=zebra2>";
                                            $j--;
                                        }
                                		?>
                                            <td style="text-align:center"><?php print $myNewNo; ?>.</td>                   
                                            <td nowrap style="text-align:left; display:none"> <?php print $MCN_CODE; ?> </td>                   
                                            <td nowrap style="text-align:left"> <?php print $MCN_CODE; ?> </td>
                                            <td style="text-align:center; display:none" nowrap><?php print $MCN_ORDER; ?> </td>
                                            <td style="text-align:left" nowrap><?php print $MCN_NAME; ?> </td>
                                            <td style="text-align:left" nowrap><?php print $MCN_DESC; ?> </td>
                                            <td style="text-align:right" nowrap><?php print number_format($MCN_ITMCAL, $decFormat); ?> </td>
                                            <td style="text-align:center" nowrap>
                                                <a href="<?php echo $secUpd; ?>" class="btn btn-info btn-xs" title="Update">
                                                    <i class="glyphicon glyphicon-pencil"></i>
                                                </a>
                                                <a href="" class="btn btn-danger btn-xs" title="Delete" onclick="return confirm('<?php echo $sureDelete; ?>')" <?php if($MCN_STAT > 0) { ?>disabled="disabled" <?php } ?>>
                                                    <i class="glyphicon glyphicon-trash"></i>
                                                </a>
                                        	</td>
                                    	</tr>
                                    	<?php 
                           			endforeach;
                        		}
                                $url_add 	= site_url('c_setting/c_m4ch1n/a44_m4ch/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
                                //$url_addRO 	= site_url('c_setting/c_m4ch1n/a44p180c21o_r0/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
                                //$url_addDir	= site_url('c_setting/c_m4ch1n/addDir/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
                                ?> 
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="8">
                                        <?php
            								echo anchor("$url_add",'<button class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i></button>&nbsp;&nbsp;');
            								//echo anchor("$url_addDir",'<button class="btn btn-success"><i class="glyphicon glyphicon-plus"></i>&nbsp;&nbsp;POD</button>&nbsp;&nbsp;');
            								//echo anchor("$url_addRO",'<button class="btn btn-warning"><i class="glyphicon glyphicon-plus"></i>&nbsp;&nbsp;RO</button>&nbsp;&nbsp;');
            								/*echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>');
            								if ( ! empty($link))
            								{
            									foreach($link as $links)
            									{
            										echo $links;
            									}
            								}*/
            							?>
                                    </td>
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
	
	function printIRList(row)
	{
		var url	= document.getElementById('urlIRList'+row).value;
		w = 900;
		h = 550;
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		window.open(url, 'formpopup', 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
		form.target = 'formpopup';
	}
	
	function printDocument(row)
	{
		var url	= document.getElementById('urlPrint'+row).value;
		w = 900;
		h = 550;
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		window.open(url, 'formpopup', 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
		form.target = 'formpopup';
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