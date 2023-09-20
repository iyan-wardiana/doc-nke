<?php
/* 
    * Author		= Dian Hermanto
    * Create Date	= 25 Januari 2018
    * File Name	= docapproval.php
    * Location		= -
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody    = $this->session->userdata['appBody'];

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
    		if($TranslCode == 'ApprovalName')$ApprovalName = $LangTransl;
    		if($TranslCode == 'Project')$Project = $LangTransl;
            if($TranslCode == 'Department')$Department = $LangTransl;
    		if($TranslCode == 'Approver')$Approver = $LangTransl;

    	endforeach;

        $comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
        <section class="content-header">
            <h1>
            <?php echo $h2_title; ?>
            <small>setting</small>
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
                        <table id="example1" class="table table-bordered table-striped" width="100%">
                            <thead>
                            <tr>
                                <th width="5%" nowrap style="text-align:center">ID</th>
                                <th width="11%" nowrap><?php echo $ApprovalName ?> </th>
                                <th width="6%"><?php echo $Project ?></th>
                                <th width="6%"><?php echo $Department ?></th>
                                <th width="78%"><?php echo $Approver ?> </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php 
                            $i = 0;
                    		$j = 0;
                            if($recordcount >0)
                            {
                                foreach($viewdocapproval as $row) : 
                                $DOCAPP_ID 			= $row->DOCAPP_ID;
                                $DOCAPP_NAME 		= $row->DOCAPP_NAME;
                                $PRJCODE	 		= $row->PRJCODE;
                                $MENU_CODE 			= $row->MENU_CODE;
                                $POSCODE            = $row->POSCODE;
                                $POSS_NAME          = "-";
                                if($POSCODE != '')
                                {
                                    $sqlDEPT        = "SELECT POSS_NAME FROM tbl_position_str WHERE POSS_CODE = '$POSCODE'";
                                    $resDEPT        = $this->db->query($sqlDEPT)->result();
                                    foreach($resDEPT as $rowDEPT):
                                        $POSS_NAME  = $rowDEPT->POSS_NAME;
                                    endforeach;
                                }

                                $APPROVER_1 		= $row->APPROVER_1;
                                $APPROVER_2 		= $row->APPROVER_2;
                                $APPROVER_3 		= $row->APPROVER_3;
                                $APPROVER_4 		= $row->APPROVER_4;
                                $APPROVER_5 		= $row->APPROVER_5;
                                $APPLIMIT_1 		= $row->APPLIMIT_1;
                                $APPLIMIT_2 		= $row->APPLIMIT_2;
                                $APPLIMIT_3 		= $row->APPLIMIT_3;
                                $APPLIMIT_4 		= $row->APPLIMIT_4;
                                $APPLIMIT_5 		= $row->APPLIMIT_5;
                                
                                $APP1_EMPNAME1	= "";
                                if($APPROVER_1 != '')
                                {
                    				$First_Name1	= '';
                    				$Last_Name1		= '';
                    				$sqlGetEMPNC1	= "tbl_employee WHERE EMP_ID = '$APPROVER_1'";
                                    $resGetEMPNC1	= $this->db->count_all($sqlGetEMPNC1);
                    				if($resGetEMPNC1 > 0)
                    				{
                    					$sqlGetEMPN1	= "SELECT First_Name, Last_Name FROM tbl_employee WHERE EMP_ID = '$APPROVER_1'";
                    					$resGetEMPN1	= $this->db->query($sqlGetEMPN1)->result();
                    					foreach($resGetEMPN1 as $rowEMPN1) :
                    						$First_Name1	= $rowEMPN1->First_Name;
                    						$Last_Name1		= $rowEMPN1->Last_Name;
                    					endforeach;
                    				}
                    				else
                    				{
                    					$First_Name1	= "---- Employee";
                    					$Last_Name1		= "Lose ----";
                    				}
                                    $APP1_EMPNAME1		= "STEP 1 : $First_Name1 $Last_Name1";
                                }
                                
                                $APP2_EMPNAME2	= "";
                                if($APPROVER_2 != '')
                                {
                    				$First_Name2	= '';
                    				$Last_Name2		= '';
                                    $sqlGetEMPNC2	= "tbl_employee WHERE EMP_ID = '$APPROVER_2'";
                                    $resGetEMPNC2	= $this->db->count_all($sqlGetEMPNC2);
                    				if($resGetEMPNC2 > 0)
                    				{
                    					$sqlGetEMPN2	= "SELECT First_Name, Last_Name FROM tbl_employee WHERE EMP_ID = '$APPROVER_2'";
                    					$resGetEMPN2	= $this->db->query($sqlGetEMPN2)->result();
                    					foreach($resGetEMPN2 as $rowEMPN2) :
                    						$First_Name2	= $rowEMPN2->First_Name;
                    						$Last_Name2		= $rowEMPN2->Last_Name;
                    					endforeach;
                    				}
                    				else
                    				{
                    					$First_Name2	= "---- Employee";
                    					$Last_Name2		= "Lose ----";
                    				}
                                    $APP2_EMPNAME2		= ",&nbsp;&nbsp;STEP 2 : $First_Name2 $Last_Name2";
                                }
                                
                                $APP3_EMPNAME3	= "";
                                if($APPROVER_3 != '')
                                {
                    				$First_Name3	= '';
                    				$Last_Name3		= '';
                                    $sqlGetEMPNC3	= "tbl_employee WHERE EMP_ID = '$APPROVER_3'";
                                    $resGetEMPNC3	= $this->db->count_all($sqlGetEMPNC3);
                    				if($resGetEMPNC3 > 0)
                    				{
                    					$sqlGetEMPN3	= "SELECT First_Name, Last_Name FROM tbl_employee WHERE EMP_ID = '$APPROVER_3'";
                    					$resGetEMPN3	= $this->db->query($sqlGetEMPN3)->result();
                    					foreach($resGetEMPN3 as $rowEMPN3) :
                    						$First_Name3	= $rowEMPN3->First_Name;
                    						$Last_Name3		= $rowEMPN3->Last_Name;
                    					endforeach;
                    				}
                    				else
                    				{
                    					$First_Name3	= "---- Employee";
                    					$Last_Name3		= "Lose ----";
                    				}
                                    $APP3_EMPNAME3		= ",&nbsp;&nbsp;STEP 3 : $First_Name3 $Last_Name3";
                                }
                                
                                $APP4_EMPNAME4	= "";
                                if($APPROVER_4 != '')
                                {
                                   	$First_Name4	= '';
                    				$Last_Name4		= '';
                                    $sqlGetEMPNC4	= "tbl_employee WHERE EMP_ID = '$APPROVER_4'";
                                    $resGetEMPNC4	= $this->db->count_all($sqlGetEMPNC4);
                    				if($resGetEMPNC4 > 0)
                    				{
                    					$sqlGetEMPN4	= "SELECT First_Name, Last_Name FROM tbl_employee WHERE EMP_ID = '$APPROVER_4'";
                    					$resGetEMPN4	= $this->db->query($sqlGetEMPN4)->result();
                    					foreach($resGetEMPN4 as $rowEMPN4) :
                    						$First_Name4	= $rowEMPN4->First_Name;
                    						$Last_Name4		= $rowEMPN4->Last_Name;
                    					endforeach;
                    				}
                    				else
                    				{
                    					$First_Name4	= "---- Employee";
                    					$Last_Name4		= "Lose ----";
                    				}
                                    $APP4_EMPNAME4		= ",&nbsp;&nbsp;STEP 4 : $First_Name4 $Last_Name4";
                                }
                                
                                $APP5_EMPNAME5	= "";
                                if($APPROVER_5 != '')
                                {
                                    $First_Name5	= '';
                    				$Last_Name5		= '';
                                    $sqlGetEMPNC5	= "tbl_employee WHERE EMP_ID = '$APPROVER_5'";
                                    $resGetEMPNC5	= $this->db->count_all($sqlGetEMPNC5);
                    				if($resGetEMPNC5 > 0)
                    				{
                    					$sqlGetEMPN5	= "SELECT First_Name, Last_Name FROM tbl_employee WHERE EMP_ID = '$APPROVER_5'";
                    					$resGetEMPN5	= $this->db->query($sqlGetEMPN5)->result();
                    					foreach($resGetEMPN5 as $rowEMPN5) :
                    						$First_Name5	= $rowEMPN5->First_Name;
                    						$Last_Name5		= $rowEMPN5->Last_Name;
                    					endforeach;
                    				}
                    				else
                    				{
                    					$First_Name5	= "---- Employee";
                    					$Last_Name5		= "Lose ----";
                    				}
                                    $APP5_EMPNAME5		= ",&nbsp;&nbsp;STEP 5 : $First_Name5 $Last_Name5";
                                }
                    			$secUpd		= site_url('c_setting/c_docapproval/update/?id='.$this->url_encryption_helper->encode_url($DOCAPP_ID));
                    					
                    			if ($j==1) {
                    				echo "<tr class=zebra1>";
                    				$j++;
                    			} else {
                    				echo "<tr class=zebra2>";
                    				$j--;
                    			}
                    			?>
                               	<td style="text-align:center"> <?php print $DOCAPP_ID; ?> </td>
                               	<td nowrap><?php echo anchor($secUpd,$DOCAPP_NAME);?></td>
                                <td nowrap><?php echo $PRJCODE;?></td>
                                <td nowrap><?php echo $POSS_NAME;?></td>
                                <td><?php echo "$APP1_EMPNAME1$APP2_EMPNAME2$APP3_EMPNAME3$APP4_EMPNAME4$APP5_EMPNAME5"; ?></td>
                            </tr>
                            <?php endforeach; 
                            }
                            ?>
                            </tbody>
                            <tfoot>
                            </tfoot>
                        </table>
                    </div>
                    <?php
                        $secAddURL = site_url('c_setting/c_docapproval/add/?id='.$this->url_encryption_helper->encode_url($appName));
                        
                        echo anchor("$secAddURL",'<button class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i></button>&nbsp;');
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