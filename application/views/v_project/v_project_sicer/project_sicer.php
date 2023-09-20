<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 13 Maret 2017
 * File Name	= project_sicer.php
 * Location		= -
*/
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$FlagUSER 	= $this->session->userdata['FlagUSER'];
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
$appBody    = $this->session->userdata('appBody');

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

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

$sql = "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$result = $this->db->query($sql)->result();
foreach($result as $row) :
	$PRJNAME = $row ->PRJNAME;
endforeach;
		
$selDocNumb			= '';
if(isset($_POST['submit']))
{
	$selDocNumb = $_POST['selDocNumb'];
}

$showIdxAll		= site_url('c_project/c_si180c2ecer/get_last_ten_projsicer/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
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
    		if($TranslCode == 'Back')$Back = $LangTransl;
    		if($TranslCode == 'Print')$Print = $LangTransl;
    		if($TranslCode == 'CertificateCode ')$CertificateCode = $LangTransl;
    		if($TranslCode == 'ManualNumber')$ManualNumber = $LangTransl;
    		if($TranslCode == 'Description')$Description = $LangTransl;
    		if($TranslCode == 'Date')$Date = $LangTransl;
    		if($TranslCode == 'EndDate')$EndDate = $LangTransl;
    		if($TranslCode == 'Status')$Status = $LangTransl;
    	endforeach;
    	if($LangID == 'IND')
    	{
    		$h1_title	= "Sertifikat SI";
    		$h2_title	= $PRJNAME;
    		$sureDelete	= "Anda yakin akan menghapus data ini?";
    	}
    	else
    	{
    		$h1_title	= "SO Certificate";
    		$h2_title	= $PRJNAME;
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

        <style>
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
                            <input type="submit" class="button_css" name="submit" id="submit" value=" search " style="display:none" />
                        </form>
                        <form action="" onSubmit="confirmDelete();" method=POST>
                            <table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
                                <thead>
                                    <tr>
                                        <th width="2%"><input name="chkAll" id="chkAll" type="checkbox" value="" style="display:none" /></th>
                                      <th width="12%" nowrap><?php echo $CertificateCode ?>  </th>
                                      <th width="15%" nowrap><?php echo $ManualNumber ?>  </th>
                                      <th width="59%" nowrap><?php echo $Description ?></th>
                                      <th width="3%" nowrap><?php echo $Date ?>  </th>
                                      <th width="5%" nowrap><?php echo $EndDate ?> </th>
                                      <th width="2%" nowrap><?php echo $Status ?> </th>
                                      <th width="2%" nowrap>&nbsp;</th>
                                  </tr>
                                </thead>
                                <tbody>
                                <?php
                                $i = 0;
                                $j = 0;
                                $PRJCODE1 	= "$PRJCODE";        
                                if($recordcount >0)
                                {
                                    foreach($viewprojSIC as $row) : 
                                        $SIC_CODE		= $row->SIC_CODE;
                                        $SIC_MANNO		= $row->SIC_MANNO;
                                        $SIC_DATE		= $row->SIC_DATE;
                                        $SIC_APPDATE	= $row->SIC_APPDATE;
                                        $PRJCODE		= $row->PRJCODE;
                                        $PRJNAME		= $row->PRJNAME;
                                        $SIC_NOTES		= $row->SIC_NOTES;
                                        $SIC_STAT		= $row->SIC_STAT;
                									
                						if($SIC_STAT == 0)
                						{
                							$SIC_STATDes = "fake";
                							$STATCOL	= 'danger';
                						}
                						elseif($SIC_STAT == 1)
                						{
                							$SIC_STATDes = "New";
                							$STATCOL	= 'warning';
                						}
                						elseif($SIC_STAT == 2)
                						{
                							$SIC_STATDes = "Confirm";
                							$STATCOL	= 'primary';
                						}
                						elseif($SIC_STAT == 3)
                						{
                							$SIC_STATDes = "Approve";
                							$STATCOL	= 'success';
                						}
                						elseif($SIC_STAT == 6)
                						{
                							$SIC_STATDes = "Close";
                							$STATCOL	= 'primary';
                						}
                						elseif($SIC_STAT == 7)
                						{
                							$SIC_STATDes = "Waiting";
                							$STATCOL	= 'warning';
                						}
                						else
                						{
                							$SIC_STATDes = "Fake";
                							$STATCOL	= 'danger';
                						}
                                        
                                        $myNewNo = ++$i;
                                                                
                                        if ($j==1) {
                                            echo "<tr class=zebra1>";
                                            $j++;
                                        } else {
                                            echo "<tr class=zebra2>";
                                            $j--;
                                        }
                                        ?>
                                            <td style="text-align:center"><?php echo $myNewNo; ?>.
                                            <input type="radio" name="chkDetail" id="chkDetail" value="<?php echo $SIC_CODE;?>" onClick="getValueNo(this);" <?php if($SIC_CODE == $selDocNumb) { ?> checked <?php } ?> style="display:none"/></td>
                                            <td nowrap> <?php print $SIC_CODE; ?> </td>
                                            <td> <?php print $SIC_MANNO; ?></td>
                                            <td><span style="text-align:left">
                                              <?php
                                                    echo $SIC_NOTES;
                                                ?>
                                            </span></td>
                                            <td style="text-align:center" nowrap>
                                                <?php
                                                    $date = new DateTime($SIC_DATE);
                                                    echo $date->format('d F Y');
                                                ?>        </td>
                                            <td style="text-align:center" nowrap>
                                                <?php
                                                    $date = new DateTime($SIC_DATE);
                                                    echo $date->format('d F Y');
                                                ?> </td>
                                            <td nowrap style="text-align:center">
                                                <span class="label label-<?php echo $STATCOL; ?>" style="font-size:11px">
                                                    <?php 
                                                        echo $SIC_STATDes;
                                                     ?>
                                                 </span>
                                            </td>
                                            <?php
                                        		$secUpd	= site_url('c_project/c_si180c2ecer/update/?id='.$this->url_encryption_helper->encode_url($SIC_CODE));
                							?>
                                            <td nowrap style="text-align:center">
                                            <a href="<?php echo $secUpd; ?>" class="btn btn-info btn-xs" title="Update">
                                                <i class="glyphicon glyphicon-pencil"></i>
                                            </a>
                                            <a href="avascript:void(null);" class="btn btn-primary btn-xs" title="Print" onClick="printDocument('<?php echo $myNewNo; ?>')" disabled="disabled">
                                                <i class="glyphicon glyphicon-print"></i>
                                            </a>
                                            <a href="" class="btn btn-danger btn-xs" title="Delete" onclick="return confirm('<?php echo $sureDelete; ?>')" <?php if($SIC_STAT > 1) { ?>disabled="disabled" <?php } ?>>
                                                <i class="glyphicon glyphicon-trash"></i>
                                            </a>
                                            </td>
                                        </tr>
                                        <?php 
                                    endforeach; 
                                }
                                ?>
                                </tbody>
                                <tr>
                                	<td colspan="8">
                						<?php
                                            $secURLPDoc		= site_url('c_project/c_si180c2ecer/printdocument/?id='.$this->url_encryption_helper->encode_url($selDocNumb));
                                            $secAddCERSI	= site_url('c_project/c_si180c2ecer/a180c2edd/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
                                            
                                            $valPrint		= "Print Certificate";
                							if($ISCREATE == 1)
                							{
                								echo anchor("$secAddCERSI",'<button type="button" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i></button>&nbsp;');
                							}
                                        ?>
                                        <input type="hidden" name="myPINV_Number" id="myPINV_Number" value="<?php echo $selDocNumb; ?>" />
                                        <button type="button" class="btn btn-primary" onClick="printDocument();" style="display:none"><i class="cus-print-16x16"></i>&nbsp;&nbsp;<?php echo $Print; ?></button>
                						<?php                
                                                 echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>');
                                        ?>
                                    </td>
                                </tr>                                
                            </table>
                        </form>
                    </div>
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
<script>
	function getValueNo(thisVal)
	{
		myValue = thisVal.value;
		document.getElementById('myPINV_Number').value = myValue;
		document.getElementById('selDocNumb').value = myValue;
		chooseDocNumb(thisVal);
	}
	
	function chooseDocNumb(thisVal)
	{
		document.frmselect.submit.click();
	}
	
	function printDocument()
	{
		myVal = document.getElementById('myPINV_Number').value;
		
		if(myVal == '')
		{
			swal('Please select one of Invoice Number.')
			return false;
		}
		var url = '<?php echo $secURLPDoc; ?>';
		title = 'Select Item';
		w = 800;
		h = 700;
		//window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/3)-(h/3);
		return window.open(url, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
	}
	
	function EditDocument()
	{
		myVal = document.getElementById('myPINV_Number').value;
		
		if(myVal == '')
		{
			swal('Please select one of Invoice Number.')
			return false;
		}
		var url = '<?php echo $secURLEDoc; ?>';
		title = 'Select Item';
		w = 700;
		h = 700;
		//window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
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