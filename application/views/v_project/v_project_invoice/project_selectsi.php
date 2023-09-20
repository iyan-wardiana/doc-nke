<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 25 Maret 2018
 * File Name	= project_selectsi.php
 * Location		= -
*/
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody    = $this->session->userdata('appBody');

$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
if($decFormat == 0)
	$decFormat		= 2;


$PINV_AKUMNEXT	= 0;
$sqlSrc 		= "tbl_sicertificate A
					WHERE A.PRJCODE = '$PRJCODE' AND SIC_STAT IN (3)
					ORDER BY A.SIC_MANNO ASC";				
$countSIC 		= $this->db->count_all($sqlSrc);

$sql 			= "SELECT A.*
					FROM tbl_sicertificate A
					WHERE A.PRJCODE = '$PRJCODE' AND SIC_STAT IN (3)
					ORDER BY A.SIC_MANNO ASC";
$viewAllSIC 	= $this->db->query($sql)->result();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $appName; ?></title>
        <!-- Tell the browser to be responsive to screen width -->
        <?php
          $vers     = $this->session->userdata('vers');

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
			if($TranslCode == 'CertificateNo')$CertificateNo = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'Date')$Date = $LangTransl;
			if($TranslCode == 'Amount')$Amount = $LangTransl;
			if($TranslCode == 'Received')$Received = $LangTransl;
			if($TranslCode == 'Notes')$Notes = $LangTransl;
			if($TranslCode == 'Select')$Select = $LangTransl;
			if($TranslCode == 'Close')$Close = $LangTransl;
		endforeach;
		if($LangID == 'IND')
		{
			$selectMC	= "Silahkan pilih salah satu Sertifikat SI di bawah ini.";
		}
		else
		{
			$selectMC	= "Please select one of SI Certificate Number below.";
		}
	?>
    
    <body class="<?php echo $appBody; ?>">
		<section class="content-header">
		</section>

        <section class="content">
			<div class="box">
				<div class="box-body">
					<div class="callout callout-success">
			        	<p><?php echo $selectMC; ?></p>
			      	</div>
			        <form method="post" name="frmSearch" action="">
			            <input type="hidden" name="SIC_REF2" id="SIC_REF2" value="" size="50" />
			            <input type="hidden" name="PINV_MMC" id="PINV_MMC" value="0" size="1" />
			            <input type="hidden" name="SIC_TOTVAL1" id="SIC_TOTVAL1" value="0" size="10" />
			            <table id="example1" class="table table-bordered table-striped" width="100%">
			                <thead>
			                    <tr>
			                        <th width="2%" style="text-align:center">&nbsp;</th>
			                        <th width="9%" style="text-align:center" nowrap><?php echo $Code ?></th>
			                        <th width="11%" style="text-align:center" nowrap><?php echo $CertificateNo ?></th>
			                        <th width="3%" style="text-align:center" nowrap><?php echo $Date ?></th>
			                        <th width="53%" style="text-align:center" nowrap><?php echo $Notes ?></th>
			                        <th width="10%" style="text-align:center" nowrap><?php echo $Amount ?></th>
			                        <th width="12%" style="text-align:center" nowrap><?php echo $Received ?></th>
			                    </tr>
			                </thead>
			                <tbody>
			                    <?php
			                        $currow = 0;			
			                        $idx 	= -1;
			                        if($countSIC>0)
			                        {
			                            foreach($viewAllSIC as $row) :
			                                $pageFrom 		= "SIC";				// 0
			                                $SIC_CODE 		= $row->SIC_CODE;		// 1
			                                $SIC_MANNO 		= $row->SIC_MANNO;		// 2
			                                $SIC_STEP 		= $row->SIC_STEP;
			                                $PRJCODE 		= $row->PRJCODE;
			                                $SIC_OWNER		= $row->SIC_OWNER;
			                                $SIC_DATE 		= $row->SIC_DATE;		// 3
			                                $SIC_APPDATE	= $row->SIC_APPDATE;	// 4
			                                $SIC_PROG 		= $row->SIC_PROG;
			                                $SIC_PROGVAL	= $row->SIC_PROGVAL;
			                                $SIC_APPPROG	= $row->SIC_APPPROG;	// 5
			                                $SIC_APPPROGVAL	= $row->SIC_APPPROGVAL;	// 6 n 7
			                                $SIC_TOTVAL		= $row->SIC_TOTVAL;
			                                $SIC_NOTES 		= $row->SIC_NOTES;		// 8
			                                $SIC_STAT 		= $row->SIC_STAT;		// 9
			                                $SIC_APPSTAT	= $row->SIC_APPSTAT;	// 10
			                                $currow			= $currow + 1;			
			                                    ?>
			                                    <tr>
			                                        <td style="text-align:center" nowrap><input type="radio" name="chk" id="chk" value="<?php echo $pageFrom;?>|<?php echo $SIC_CODE;?>|<?php echo $SIC_MANNO;?>|<?php echo $SIC_DATE;?>|<?php echo $SIC_APPDATE;?>|<?php echo $SIC_APPPROG;?>|<?php echo $SIC_APPPROGVAL;?>|<?php echo $SIC_APPPROGVAL;?>|<?php echo $SIC_NOTES;?>|<?php echo $SIC_STAT;?>|<?php echo $SIC_APPSTAT;?>" onClick="pickThis(this, '<?php echo $currow; ?>');" <?php if($SIC_STAT == 1) { ?> disabled <?php } ?> /></td>
			                                        <td nowrap>
														<?php echo "$SIC_CODE"; ?>
			                                            <input type="hidden" name="SIC_CODE<?php echo $idx; ?>" id="SIC_CODE<?php echo $idx; ?>" value="<?php echo "$SIC_CODE"; ?>" />
			                                            <input type="hidden" name="SIC_TOTVAL<?php echo $idx; ?>" id="SIC_TOTVAL<?php echo $idx; ?>" value="<?php echo "$SIC_APPPROGVAL"; ?>" />
			                                        </td>
			                                        <td nowrap>
			                                            <?php echo "$SIC_MANNO"; ?>
			                                        </td>
			                                        <td nowrap><?php echo $SIC_DATE; ?></td>
			                                        <td><?php print $SIC_NOTES; ?></td>
			                                        <td style="text-align:right"><?php print number_format($SIC_TOTVAL, $decFormat); ?></td>
			                                        <td style="text-align:right"><?php print number_format($SIC_TOTVAL, $decFormat); ?></td>
			                                    </tr>
			                                <?php
			                            endforeach;
			                        }
			                    ?>
			                </tbody>
			                <tr>
			                    <td colspan="7" nowrap>
			                    <button class="btn btn-primary" type="button" onClick="get_item();">
			                    <i class="cus-check-green-16x16"></i>&nbsp;&nbsp;<?php echo $Select; ?>                    </button> 
			                    <button class="btn btn-danger" type="button" onClick="window.close()">
			                    <i class="cus-delete-16x16"></i>&nbsp;&nbsp;<?php echo $Close; ?>                    </button></td>
			                </tr>
			            </table>
			        </form>
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
	var selectedRows = 0
	function pickThis(thisobj, theRow) 
	{
		//var NumOfRows 	= document.getElementsByName('chk').length; // minus 1 because it's the header
		NumOfRows		= document.getElementsByName('chk').length;
		if (thisobj!= '') 
		{
			if (thisobj.checked) selectedRows++;
			else selectedRows--;
		}
		SIC_REFB	= '';
		isMMC		= 0;
		SIC_TOTVAL1	= 0;
		
		for(i=0; i <=NumOfRows; i++)
		{
			ischeck = document.getElementsByName('chk')[i].checked;
			if(ischeck == true)
			{
				SIC_CODE	= document.getElementById('SIC_CODE'+i).value;
				SIC_TOTVAL	= document.getElementById('SIC_TOTVAL'+i).value;
				if(i == 0)
				{
					SIC_REFB 	= SIC_CODE;
					SIC_TOTVAL1	= parseFloat(SIC_TOTVAL1) + parseFloat(SIC_TOTVAL);
				}
				else if(i > 1)
				{
					if(SIC_REFB == '')
						SIC_REFB = SIC_CODE;
					else
						SIC_REFB 	= ''+SIC_REFB+'|'+SIC_CODE;
						
					SIC_TOTVAL1	= parseFloat(SIC_TOTVAL1) + parseFloat(SIC_TOTVAL);
				}
			}
			document.getElementById('SIC_TOTVAL1').value	= SIC_TOTVAL1;
			document.getElementById('PINV_MMC').value		= isMMC;
			document.getElementById('SIC_REF2').value 		= SIC_REFB;
		}
	}
	

	function get_item() 
	{ 
		// swal(document.getElementsByName('chk').length) 
		if(typeof(document.getElementsByName('chk')[0]) == 'object') 
		{
			for(i=0;i<document.getElementsByName('chk').length;i++) 
			{
				if(document.getElementsByName('chk')[i].checked) 
				{
					A 			= document.getElementsByName('chk')[i].value
					arrItem 	= A.split('|');
					arrparent 	= document.getElementsByName('chk')[i].value.split('|');
					PINV_MMC	= document.getElementById('PINV_MMC').value;
					SIC_TOTVAL1	= document.getElementById('SIC_TOTVAL1').value;
					SIC_REF		= document.getElementById('SIC_REF2').value;
					//PINV_PAYBEF	= document.getElementById('PINV_AKUMNEXT').value;
					PINV_PAYBEF	= 0;
					window.opener.getDetail(document.getElementsByName('chk')[i].value, PINV_MMC, SIC_TOTVAL1, SIC_REF, PINV_PAYBEF);				
				}
			}
		} 
		else 
		{
			if(document.getElementsByName('chk').checked)
			{
				PINV_MMC	= document.getElementById('PINV_MMC').value;
				SIC_TOTVAL1	= document.getElementById('SIC_TOTVAL1').value;
				SIC_REF		= document.getElementById('SIC_REF2').value;
				//PINV_PAYBEF	= document.getElementById('PINV_AKUMNEXT').value;
				PINV_PAYBEF	= 0;
				window.opener.getDetail(document.getElementsByName('chk').value, PINV_MMC, SIC_TOTVAL1, SIC_REF, PINV_PAYBEF);
			}
		}
		window.close();
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
    //$this->load->view('template/aside');

    //$this->load->view('template/js_data');

    //$this->load->view('template/foot');
?>