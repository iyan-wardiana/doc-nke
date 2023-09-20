<?php
/* 
    * Author		= Dian Hermanto
    * Create Date	= 07 Maret 2019
    * File Name	= v_wh.php
    * Location		= -
*/
?>
<?php 
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody    = $this->session->userdata['appBody'];

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

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
        	if($TranslCode == 'Save')$Save = $LangTransl;
        	if($TranslCode == 'Update')$Update = $LangTransl;	
        	if($TranslCode == 'Back')$Back = $LangTransl;
        	if($TranslCode == 'Code')$Code = $LangTransl;
        	if($TranslCode == 'Description')$Description = $LangTransl;
        	if($TranslCode == 'whName')$whName = $LangTransl;
        	if($TranslCode == 'ProjectName')$ProjectName = $LangTransl;
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
            <?php echo $h1_title; ?>
            <small><?php echo $h2_title; ?></small>
            </h1>
        </section>

        <section class="content">
            <div class="box">
                <div class="box-body">
                    <div class="search-table-outter">
                        <table id="example1" class="table table-bordered table-striped" width="100%">
                            <thead>
                            <tr>
                                <th width="3%" nowrap>No</th>
                                <th width="12%"><?php echo $Code; ?></th>
                                <th width="31%"><?php echo $whName; ?></th>
                                <th width="38%" nowrap><?php echo $Description; ?></th>
                                <th width="3%" nowrap><?php echo $ProjectName; ?></th>
                                <th width="2%">&nbsp;</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php 
                            $noUrut = 0;
                            $j = 0;
                            if($cData >0)
                            {
                                foreach($vData as $row) : 
                                    $noUrut		= $noUrut + 1;
                                    $WH_NUM		= $row->WH_NUM;
                                    $WH_CODE	= $row->WH_CODE;
                                    $WH_NAME 	= $row->WH_NAME;
                                    $WH_LOC 	= $row->WH_LOC;
                                    $PRJCODE 	= $row->PRJCODE;
                                    $ISWHPROD	= $row->ISWHPROD;
            												
                                    $secUpd	= site_url('c_setting/c_w4r3h/update/?id='.$this->url_encryption_helper->encode_url($WH_NUM));
                                        
                                    if ($j==1) {
                                        echo "<tr class=zebra1>";
                                        $j++;
                                    } else {
                                        echo "<tr class=zebra2>";
                                        $j--;
                                    }
                                    ?>
                                        <td style="text-align:center" nowrap> <?php echo $noUrut; ?>.</td>
                                        <td><?php echo $WH_CODE;?></td>
                                        <td><?php echo $WH_NAME; ?></td>
                                        <td><?php echo $WH_LOC; ?></td>
                                        <td><?php echo $PRJCODE; ?></td>
                                        <td width="2%" nowrap>
                                            <a href="<?php echo $secUpd; ?>" class="btn btn-warning btn-xs" title="Update">
                                                <i class="glyphicon glyphicon-pencil"></i>
                                            </a>
                                            <a href="" class="btn btn-danger btn-xs" title="In Active Project" onclick="return confirm('<?php echo $sureDelete; ?>')" disabled="disabled">
                                                <i class="glyphicon glyphicon-trash"></i>
                                            </a>
                                    	</td>
                                    </tr>
                                    <?php
                                endforeach; 
                            }
                            $secAddURL = site_url('c_setting/c_w4r3h/w4r3h_l44d/?id='.$this->url_encryption_helper->encode_url($appName));
                            ?>
                            </tbody>
                            <tfoot>
                            </tfoot>
                        </table>
                    </div>
                    <?php 
                        //echo anchor($secAddURL,'<input type="button" name="btnSubmit" id="btnSubmit" class="btn btn-primary" value="Add [ + ]" />');
                        
                        echo anchor("$secAddURL",'<button class="btn btn-primary"><i class="fa fa-plus"></i></button>&nbsp;');
                    ?>
                </div>
            </div>
            <?php
                $DefID      = $this->session->userdata['Emp_ID'];
                $act_lnk    = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                if($DefID == 'D15040004221')
                    echo "<font size='1'><i>$act_lnk</i></font>";
            ?>
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