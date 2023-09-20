<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 8 Agustus 2019
 * File Name	= v_audit.php
 * Location		= -
*/
$this->load->view('template/head');

$appName    = $this->session->userdata('appName');
$appBody    = $this->session->userdata('appBody');
$comp_color = $this->session->userdata('comp_color');
$FlagUSER   = $this->session->userdata['FlagUSER'];
$DefEmp_ID  = $this->session->userdata['Emp_ID'];

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
$Emp_DeptCode		= $this->session->userdata['Emp_DeptCode'];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $appName; ?></title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
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
	//$this->load->view('template/topbar');
	//$this->load->view('template/sidebar');
	
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
		if($TranslCode == 'MenuNameIND')$MenuNameIND = $LangTransl;
		if($TranslCode == 'MenuNameENG')$MenuNameENG = $LangTransl;
		if($TranslCode == 'MenuParent')$MenuParent = $LangTransl;
		if($TranslCode == 'MenuLevel')$MenuLevel = $LangTransl;
		if($TranslCode == 'Sort')$Sort = $LangTransl;

	endforeach;
?>

<body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
    <section class="content-header">
        <h1>
            <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/list.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;
            <?php echo $h2_title; ?>
            <small><?php echo $h3_title; ?></small>
            <div class="pull-right">
            <?php
                $secAddURL = site_url('c_project/c_4uD1NT/add/?id='.$this->url_encryption_helper->encode_url($appName));
                echo anchor("$secAddURL",'<button class="btn btn-primary"><i class="fa fa-plus"></i></button>&nbsp;');
                //echo anchor("$backURL",'<button class="btn btn-danger"><i class="fa fa-reply"></i></button>');
            ?></div>
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
                <table id="example1" class="table table-bordered table-striped" width="100%">
                    <thead>
                        <tr>
                            <th width="5%" style="text-align:center"><?php echo $Code ?>  </th>
                            <th nowrap width="5%" style="text-align:center">Tgl Audit</th>
                            <th width="2%" style="text-align:center" nowrap>Tgl. NCR</th>
                            <th width="3%" style="text-align:center" nowrap>Auditee</th>
                            <th width="8%" style="text-align:center">Auditor</th>
                            <th width="16%" style="text-align:center">Lokasi Temuan</th>
                            <th width="54%" style="text-align:center">Desripsi</th>
                            <th width="5%" nowrap style="text-align:center">Status </th>
                            <th width="2%" style="text-align:center">&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $i = 0;
                    $j = 0;
                    foreach($vwAudit as $row) : 
                        $myNewNo 		= ++$i;
                        $AUI_NUM		= $row->AUI_NUM;			//
                        $AUI_CODE		= $row->AUI_CODE;			//
                        $PRJCODE		= $row->PRJCODE; 
                        $AUI_STEP		= $row->AUI_STEP;			//
                        $AUI_DOCNO		= $AUI_CODE."-".$AUI_STEP;	//
                        $AUI_ORDNO		= $row->AUI_ORDNO;
                        $AUI_SUBJEK		= $row->AUI_SUBJEK;
                        $AUI_LOC		= $row->AUI_LOC;			//
                        $AUI_DATE		= $row->AUI_DATE;			//
                        $AUI_DATE_NCR	= $row->AUI_DATE_NCR;		//
                        $AUI_AUDITOR	= $row->AUI_AUDITOR;		//
                        $AUI_PROBLDESC	= $row->AUI_PROBLDESC;		//
                        $AUI_STAT		= $row->AUI_STAT;			//
                        $TYPE			= $row->TYPE;			//
                        if($AUI_STAT == 0)
                        {
                            $STATCOL1	= 'warning';
                            $STATDESC1	= 'open';
                        }
                        elseif($AUI_STAT == 1)
                        {
                            $STATCOL1	= 'warning';
                            $STATDESC1	= 'open';
                        }
                        elseif($AUI_STAT == 2)
                        {
                            $STATCOL1	= 'primary';
                            $STATDESC1	= 'dikerjakan';
                        }
                        elseif($AUI_STAT == 3)
                        {
                            $STATCOL1	= 'success';
                            $STATDESC1	= 'selesai';
                        }
                        else
                        {
                            $STATCOL1	= 'danger';
                            $STATDESC1	= 'perbaikan';
                        }
                        $CollCode		= "$PRJCODE~$AUI_NUM";
                        if($TYPE == 1)
                        {
                            $secUpd		= site_url('c_project/c_4uD1NT/update/?id='.$this->url_encryption_helper->encode_url($AUI_NUM));
                            $secPrint1	= site_url('c_project/c_4uD1NT/prN7_d0c/?id='.$this->url_encryption_helper->encode_url($CollCode));
                            $viewImage	= site_url('c_project/c_4uD1NT/viewImage/?id='.$this->url_encryption_helper->encode_url($AUI_NUM));
                        }
                        else
                        {
                            $secUpd		= site_url('c_project/c_4uD1NT/update_nts/?id='.$this->url_encryption_helper->encode_url($AUI_NUM));
                            $secPrint1	= site_url('c_project/c_4uD1NT/prN7_d0cNts/?id='.$this->url_encryption_helper->encode_url($CollCode));
                            $viewImage	= site_url('c_project/c_4uD1NT/viewImage/?id='.$this->url_encryption_helper->encode_url($AUI_NUM));
                        }
    
                        $statvwImage = $this->db->get_where('tbl_audit_pic', array('AUI_NUM' => $AUI_NUM));
                        if($statvwImage->num_rows() > 0){
                            $STATCOLIMG = 'success';
                        }else{
                            $STATCOLIMG = 'danger';
                        }
                        
                        if ($j==1) {
                            echo "<tr class=zebra1>";
                            $j++;
                        } else {
                            echo "<tr class=zebra2>";
                            $j--;
                        }
                        ?> 
                            <td nowrap> <?php echo "$AUI_DOCNO : $AUI_ORDNO"; ?> </td>
                            <td nowrap> <?php echo $AUI_DATE; ?> </td>
                            <td nowrap> <?php echo $AUI_DATE_NCR; ?> </td>
                            <td nowrap> <?php echo $AUI_SUBJEK; ?></td>
                            <td nowrap> <?php echo $AUI_AUDITOR; ?> </td>
                            <td nowrap><?php echo $AUI_LOC; ?></td>
                            <td><?php echo $AUI_PROBLDESC; ?></td>
                            <td style="text-align:center">
                                <span class="label label-<?php echo $STATCOL1; ?>" style="font-size:12px"><?php echo $STATDESC1; ?></span>
                            </td>
                            <td style="text-align:center" nowrap>
                                <input type="hidden" name="urlPrint<?php echo $myNewNo; ?>" id="urlPrint<?php echo $myNewNo; ?>" value="<?php echo $secPrint1; ?>">
                                <input type="hidden" name="viewImage<?php echo $myNewNo; ?>" id="viewImage<?php echo $myNewNo; ?>" value="<?php echo $viewImage; ?>">
                                <a href="<?php echo $secUpd; ?>" class="btn btn-info btn-xs" title="Update">
                                    <i class="glyphicon glyphicon-pencil"></i>
                                </a>
                                <a href="avascript:void(null);" class="btn btn-primary btn-xs" title="Print" onClick="printD('<?php echo $myNewNo; ?>')">
                                    <i class="glyphicon glyphicon-print"></i>
                                </a>
                                <a href="avascript:void(null);" class="btn btn-<?=$STATCOLIMG?> btn-xs" title="View" onClick="viewImage('<?php echo $myNewNo; ?>')">
                                    <i class="glyphicon glyphicon-eye-open"></i>
                                </a>
                            </td>
                        </tr>
                        <?php
                    endforeach; 
                    
                    ?>
                    </tbody> 
                </table>
                </div>
            </div>
        </div>
    </section>
    <?php
        $act_lnk = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        if($DefEmp_ID == 'D15040004221')
            echo "<font size='1'><i>$act_lnk</i></font>";
    ?>
</body>

</html>
<script>
    $(document).ready(function() {
        $('#example1').DataTable({
            "processing": false,
            "serverSide": false,
            //"scrollX": false,
            "autoWidth": true,
            "filter": true,
            "type": "POST",
            //"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
            "lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
            "columnDefs": [ { targets: [0,1,3], className: 'dt-body-center' },
                            { "width": "100px", "targets": [1] }
                        ],
            "language": {
                "infoFiltered":"",
                "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
            },
            });
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

    function viewImage(row)
    {
        var url = document.getElementById('viewImage'+row).value;
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