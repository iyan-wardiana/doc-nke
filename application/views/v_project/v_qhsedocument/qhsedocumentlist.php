<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 15 Maret 2017
 * File Name	= qhsedocumentlist.php
 * Location		= -
*/
$sqlA 			= "SELECT doc_parent FROM tbl_document WHERE doc_code = '$doc_code' AND isHRD = 1";
$resultA 		= $this->db->query($sqlA)->result();
foreach($resultA as $rowA) :
	$doc_parent = $rowA->doc_parent;
endforeach;

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody    = $this->session->userdata('appBody');
$comp_color = $this->session->userdata('comp_color');
$FlagUSER 	= $this->session->userdata['FlagUSER'];
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

$appName 	= $this->session->userdata('appName');

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

$FlagUSER 		= $this->session->userdata['FlagUSER'];
$DefEmp_ID 		= $this->session->userdata['Emp_ID'];

$DAU_WRITE 	= 0;
$DAU_READ 	= 0;
$DAU_DL 	= 0;
$sqlDAU 	= "SELECT DAU_WRITE, DAU_READ, DAU_DL
				FROM tbl_employee_docauth
				WHERE DAU_EMPID = '$DefEmp_ID'";
$resultDAU 	= $this->db->query($sqlDAU)->result();
foreach($resultDAU as $rowDAU) :
	$DAU_WRITE 	= $rowDAU->DAU_WRITE;
	$DAU_READ 	= $rowDAU->DAU_READ;
	$DAU_DL 	= $rowDAU->DAU_DL;
endforeach;
if($DefEmp_ID == '230100005538' || $DefEmp_ID == '220100005433' || $DefEmp_ID == 'B98060000159' || $DefEmp_ID == 'D02060000245' || $DefEmp_ID == 'D15040004221')
{
	$DAU_WRITE 	= 1;
}
else
{
	$DAU_WRITE 	= 0;
}
?>
<script>
	function chooseProject(thisVal)
	{
		proj_Code	= thisVal.value;
		document.frmsrch.submitSrch.click();
	}
</script>
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
	$ISDWONL 	= $this->session->userdata['ISDWONL'];$LangID 	= $this->session->userdata['LangID'];

	$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
	$resTransl		= $this->db->query($sqlTransl)->result();
	foreach($resTransl as $rowTransl) :
		$TranslCode	= $rowTransl->MLANG_CODE;
		$LangTransl	= $rowTransl->LangTransl;
		
		if($TranslCode == 'Add')$Add = $LangTransl;
		if($TranslCode == 'Edit')$Edit = $LangTransl;
		if($TranslCode == 'DocNumber')$DocNumber = $LangTransl;
		if($TranslCode == 'DocCode')$DocCode = $LangTransl;
		if($TranslCode == 'Date')$Date = $LangTransl;
		if($TranslCode == 'Description')$Description = $LangTransl;
		if($TranslCode == 'DocLocation')$DocLocation = $LangTransl;
		if($TranslCode == 'Type')$Type = $LangTransl;
		if($TranslCode == 'Template')$Template = $LangTransl;
		if($TranslCode == 'DocFile')$DocFile = $LangTransl;

	endforeach;
?>

<body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
    <section class="content-header">
        <h1>
            <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/list.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;
	        <?php echo $doc_parent; ?>
	        <small><?php echo "$doc_name"; ?></small>
            <div class="pull-right">
            <?php 
            	if($DAU_WRITE == 1)
				{
					$secAddURL	= site_url('c_project/qhsedocument/add/?id='.$this->url_encryption_helper->encode_url($doc_code));
					echo anchor("$secAddURL",'<button class="btn btn-primary"><i class="fa fa-plus"></i></button>&nbsp;');
				}
				$secInsDLH	= site_url('c_project/qhsedocument/ins_dl_hist/?id='.$this->url_encryption_helper->encode_url($DefEmp_ID));
                echo anchor("$backURL",'<button class="btn btn-danger"><i class="fa fa-reply"></i></button>');
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
				    <form method="post" name="sendDelete" id="sendDelete" class="form-user" action="" style="display:none">		
				        <table>
				            <tr>
				                <td></td>
				                <td><a class="tombol-delete" id="delClass">Simpan</a></td>
				            </tr>
				        </table>
				    </form>
					<table id="example1" class="table table-bordered table-striped" width="100%">
				    	<input type="hidden" name="DAU_WRITEX" id="DAU_WRITEX" value="<?php echo $DAU_WRITE; ?>">
				        <thead>
				            <tr style="background:#CCCCCC">
				                <th style="vertical-align:middle; text-align:center" width="7%" nowrap><?php echo $DocNumber ?>  </th>
				              	<th style="vertical-align:middle; text-align:center" width="6%" nowrap><?php echo $DocCode ?> </th>
				              	<th style="vertical-align:middle; text-align:center" width="3%" nowrap><?php echo $Date ?>  </th>
				              	<th style="vertical-align:middle; text-align:center" width="67%" nowrap><?php echo $Description ?> </th>
				              	<th style="vertical-align:middle; text-align:center" width="7%"><?php echo $DocLocation ?> </th>
				              	<th style="vertical-align:middle; text-align:center" width="3%" nowrap><?php echo $Type ?> </th>
				              	<th style="vertical-align:middle; text-align:center" width="3%" nowrap><?php echo $Template ?> </th>
				              	<th style="vertical-align:middle; text-align:center" width="4%" nowrap><?php echo $DocFile ?> </th>
				          </tr>
				        </thead>
				        <tbody>
						<?php
							$i = 0;
							if($countDoc >0)
							{	
								foreach($viewdocument as $row) :
									$myNewNo = ++$i;
									$empID			= '';
									$HRDOCID		= $row->HRDOCID;
									$HRDOCNO		= $row->HRDOCNO;
									$HRDOCCODE		= $row->HRDOCCODE;
									$HRDOCTYPE		= $row->HRDOCTYPE;
									$TRXDATE		= $row->TRXDATE;
									$PRJCODE		= $row->PRJCODE;
									$PRJNAME		= 'Not Found';
									if($PRJCODE != '')
									{
										$sql 		= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
										$result 	= $this->db->query($sql)->result();
										foreach($result as $rowPRJ) :
											$PRJNAME = $rowPRJ ->PRJNAME;
										endforeach;
									}
									$OWNER_CODE		= $row->OWNER_CODE;
									$OWNER_DESC		= $row->OWNER_DESC;
									$HRDOCCOST		= 0;
									$HRDOCJNS		= $row->HRDOCJNS;
									if($HRDOCJNS == 1)
									{
										$HRDOCJNS	= "LEMBAR";
									}
									elseif($HRDOCJNS == 2)
									{
										$HRDOCJNS	= "BUKU";
									}
									elseif($HRDOCJNS == 3)
									{
										$HRDOCJNS	= "BUKU TIPIS";
									}
									else
									{
										$HRDOCJNS	= $HRDOCJNS;
									}
									$HRDOCJML		= $row->HRDOCJML;
									$HRDOCLOK		= $row->HRDOCLOK;
									$HRDOC_NAME		= $row->HRDOC_NAME;
									$HRDOC_TEMPL	= $row->HRDOC_TEMPL;
									$PM_EMPCODE		= $row->PM_EMPCODE;
									$PM_NAME		= $row->PM_NAME;
									$PM_STATUS		= $row->PM_STATUS;
									if($PM_EMPCODE != '')
									{
										$sqlEMPD	= "SELECT First_Name, Last_Name FROM tbl_employee WHERE Emp_ID = '$PM_EMPCODE'";
										$resEMPD	= $this->db->query($sqlEMPD)->result();
										foreach($resEMPD as $rowEMPD) :
											$First_Name = $rowEMPD ->First_Name;
											$Last_Name 	= $rowEMPD ->Last_Name;
										endforeach;
										if($PM_STATUS != "")
										{
											$PM_NAME	= ": $First_Name $Last_Name ($PM_STATUS)";
										}
										else
										{
											$PM_NAME	= ": $First_Name $Last_Name";
										}
									}
									$DIR_EMPCODE	= $row->DIR_EMPCODE;
									$DIR_NAME		= $row->DIR_NAME;
									if($DIR_EMPCODE != '')
									{
										$sqlEMPD	= "SELECT First_Name, Last_Name FROM tbl_employee WHERE Emp_ID = '$DIR_EMPCODE'";
										$resEMPD	= $this->db->query($sqlEMPD)->result();
										foreach($resEMPD as $rowEMPD) :
											$First_NameD 	= $rowEMPD ->First_Name;
											$Last_NameD		= $rowEMPD ->Last_Name;
										endforeach;
										$DIR_NAME	= ": $First_NameD $Last_NameD";
									}
									$STATUS_DOK		= $row->STATUS_DOK;
									$BORROW_EMP		= $row->BORROW_EMP;
									$BORROW_NM		= "";
									if($BORROW_EMP != '')
									{
										$sqlEMPD	= "SELECT First_Name, Last_Name FROM tbl_employee WHERE Emp_ID = '$BORROW_EMP'";
										$resEMPD	= $this->db->query($sqlEMPD)->result();
										foreach($resEMPD as $rowEMPD) :
											$First_NameB 	= $rowEMPD ->First_Name;
											$Last_NameB		= $rowEMPD ->Last_Name;
										endforeach;
										$BORROW_NM	= ": $First_NameB $Last_NameB";
									}
									$HRDOC_NOTE		= $row->HRDOC_NOTE;
									if($HRDOC_NOTE == "")
									{
										if($PRJCODE != "KTR")
										{
											$HRDOC_NOTE	= "$PRJCODE ($PRJNAME) : $HRDOCJML $HRDOCJNS $PM_NAME $DIR_NAME $BORROW_NM";
										}
										else
										{
											$HRDOC_NOTE	= "$HRDOCJML $HRDOCJNS $PM_NAME $DIR_NAME $BORROW_NM";
										}
									}

									else
									{
										$HRDOC_NOTE	= "$PRJCODE : $HRDOC_NOTE : $HRDOCJML $HRDOCJNS $PM_NAME $DIR_NAME $BORROW_NM";
									}
									$PRJCODE		= $row->PRJCODE;
									$PRJCODE		= $row->PRJCODE;
									$PRJCODE		= $row->PRJCODE;
									$PRJCODE		= $row->PRJCODE;
									//$TRXDATEa		= $row->HRDOC_CREATED;
									//$TRXDATE		= date('Y-m-d',strtotime($TRXDATEa));
									
									$secURLPI		= site_url('c_project/qhsedocument/update/?id='.$this->url_encryption_helper->encode_url($HRDOCNO));
				                	?>   	
										<tr>
				                            <td nowrap>
												<?php
													if($DAU_WRITE == 1)
													{
				                                		echo anchor("$secURLPI",$HRDOCNO,array('class' => 'update')).' ';
													}
													else
													{
														echo $HRDOCNO;
													}
												?>
				                            </td>
				                            <td nowrap> <?php echo $HRDOCCODE; ?> </td>
				                            <td style="text-align:center" nowrap> <?php echo $TRXDATE; ?> </td>
				                            <td><?php echo "$HRDOC_NOTE"; ?></td>
				                            <td style="text-align:center; text-transform:uppercase"> <?php echo $HRDOCLOK; ?> </td>
				                            <td style="text-align:center"><?php echo $HRDOCJNS; ?></td>
				                            <td nowrap style="text-align:center">
				                                <?php
				                                    if($HRDOC_TEMPL == '')
				                                    {
														if($DAU_WRITE == 1)
														{
															$FileUpName = '';
															$secUplURL	= site_url('c_project/qhsedocument/hrdocproject_upload/?id='.$this->url_encryption_helper->encode_url($HRDOCNO));
															?>
																<input type="hidden" name="secUplURL_<?php echo $myNewNo; ?>" id="secUplURL_<?php echo $myNewNo; ?>" value="<?php echo $secUplURL; ?>"/>
																<a href="javascript:void(null);" onClick="selectPICT(<?php echo $myNewNo; ?>, 0);" data-skin="skin-green" class="btn btn-success btn-xs" title="Upload Document">
																	<i class="fa fa-upload"></i>
																</a>
															<?php
														}
														else
														{
															?>
				                                                <a data-skin="skin-green" class="btn btn-danger btn-xs" title="Upload Document" style="font-style:italic" >
				                                                    No Data
				                                                </a>
				                                            <?php
														}
				                                    }
				                                    else
				                                    {
														$FileUpName = $HRDOC_TEMPL;
														//echo $FileUpName;
														$linkDL = '<a href="'.base_url().'assets/AdminLTE-2.0.5/doc_center/uploads/'.$FileUpName.'" title="Download file" data-skin="skin-green" class="btn btn-success btn-xs" onClick="writeTDWLER('.$HRDOCID.')"><i class="fa fa-download"></i></a>';
														echo $linkDL;					
				                                    }
				                                ?>
											</td>
				                            <td nowrap style="text-align:center">
				                                <?php
				                                    if($HRDOC_NAME == '')
				                                    {
														if($DAU_WRITE == 1)
														{
															$secUplURL		= site_url('c_project/qhsedocument/hrdocproject_upload/?id='.$this->url_encryption_helper->encode_url($HRDOCNO));
															?>
																<input type="hidden" name="secUplURL_<?php echo $myNewNo; ?>" id="secUplURL_<?php echo $myNewNo; ?>" value="<?php echo $secUplURL; ?>"/>
																<a href="javascript:void(null);" onClick="selectPICT(<?php echo $myNewNo; ?>);" data-skin="skin-green" class="btn btn-success btn-xs" title="Upload Document">
																	<i class="fa fa-upload"></i>
																</a>
															<?php
														}
														else
														{
															?>
				                                                <a data-skin="skin-green" class="btn btn-danger btn-xs" title="Upload Document" style="font-style:italic">
				                                                    No Data
				                                                </a>
				                                            <?php
														}
				                                    }
				                                    else
				                                    {
														// Download File PDF
														$FileUpName = $HRDOC_NAME;
														if($DAU_WRITE == 1 || $DAU_DL == 1)
														{
															$linkDLPDF = '<a href="'.base_url().'assets/AdminLTE-2.0.5/doc_center/uploads/'.$FileUpName.'" target="_blank" title="Download file" data-skin="skin-green" class="btn btn-success btn-xs" onClick="writeDWLER('.$HRDOCID.')" id="isdl"><i class="fa fa-download"></i></a>';
															echo $linkDLPDF;
														}
														
														// View File PDF
														$secViewPDF	= site_url('c_project/qhsedocument/view_pdf/?id='.$this->url_encryption_helper->encode_url($HRDOC_NAME));
				                                        //$FileUpName = $HRDOC_NAME;
														//echo $FileUpName;
														$secUplURL	= site_url('c_project/qhsedocument/hrdocproject_upload/?id='.$this->url_encryption_helper->encode_url($HRDOCNO));
														if($DAU_WRITE == 1 || $DAU_DL == 1 || $DAU_READ == 1)
														{
				                                        ?>
				                                            <input type="hidden" name="FileUpName<?php echo $myNewNo; ?>" id="FileUpName<?php echo $myNewNo; ?>" value="<?php echo $FileUpName; ?>" />
				                                            <input type="hidden" name="File_Name<?php echo $myNewNo; ?>" id="File_Name<?php echo $myNewNo; ?>" value="<?php echo $secViewPDF; ?>" />
				                                            <a href="javascript:void(null);" onClick="writeVWER(<?php echo $HRDOCID; ?>); typeOpenNewTab(<?php echo $myNewNo; ?>);" data-skin="skin-green" class="btn btn-warning btn-xs" title="View Document">
				                                            	<i class="fa fa-eye"></i>
				                                            </a>
				                                        <?php
														}
				                                    }
													
													// for delete function, special employee
													$ISDELETE	= 0;
													if($DefEmp_ID == 'B98060000159')
														$ISDELETE	= 1;
													elseif($DefEmp_ID == 'A15110004402')
														$ISDELETE	= 1;
				                                   	// if($ISDELETE == 1)
													if($DAU_WRITE == 1)
				                                    {
														$secDel = base_url().'index.php/c_project/qhsedocument/delDocument/?id='.$HRDOCID;
				                                        ?>
				                                        <a href="#" onClick="writeDELER(<?php echo $HRDOCID; ?>); deleteDoc('<?php echo $secDel; ?>')" title="Delete Document" class="btn btn-danger btn-xs">
				                                            <i class="fa fa-trash-o"> </i>
				                                        </a>
				                                        <?php
				                                    }
				                                ?>
				                        	</td>
										</tr>
									<?php 
								endforeach; 
							}
						?>
				        </tbody>
				   	</table>
					<script>
				        function writeDWLER(DOCID)
				        {
				            document.getElementById('HRDOCID').value 	= DOCID;
				            document.getElementById('DWLGRP').value 	= 'DOC';
				            document.getElementById('DWLCLASS').value 	= 'DWL';
				            document.getElementById('insDLHist').click();
				        }
						
				        function writeVWER(DOCID)
				        {
				            document.getElementById('HRDOCID').value 	= DOCID;
				            document.getElementById('DWLGRP').value 	= 'DOC';
				            document.getElementById('DWLCLASS').value 	= 'VW';
				            document.getElementById('insDLHist').click();
				        }
						
				        function writeDELER(DOCID)
				        {
				            document.getElementById('HRDOCID').value 	= DOCID;
				            document.getElementById('DWLGRP').value 	= 'DOC';
				            document.getElementById('DWLCLASS').value 	= 'DEL';
				            document.getElementById('insDLHist').click();
				        }
						
				        function writeTDWLER(DOCID)
				        {
				            document.getElementById('HRDOCID').value 	= DOCID;
				            document.getElementById('DWLGRP').value 	= 'TMPL';
				            document.getElementById('DWLCLASS').value 	= 'DWL';
				            document.getElementById('insDLHist').click();
				        }

				        $(document).ready(function()
				        {
							$(".tombol-delete-all").click(function()
							{
								var formAction 	= $('#sendDeleteAll')[0].action;
								
								var data = $('.form-del-all').serialize();
								$.ajax({
									type: 'POST',
									url: formAction,
									data: data
								});
							});
				        });
				    </script>
				    <form method="post" name="sendDeleteAll" id="sendDeleteAll" class="form-del-all" action="<?php echo $secInsDLH; ?>" style="display:none" >		
				        <table>
				            <tr>
				                <td>
				                    <input type="hidden" name="DWLGRP" id="DWLGRP" value="">
				                    <input type="hidden" name="DWLREF1" id="DWLREF1" value="<?php echo $doc_code; ?>">
				                    <input type="hidden" name="HRDOCID" id="HRDOCID" value="">
				                    <input type="hidden" name="DWLCLASS" id="DWLCLASS" value="">
				                </td>
				                <td>
				                    <a class="tombol-delete-all" id="insDLHist">Delete All</a>
				                </td>
				            </tr>
				        </table>
				    </form>
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

<?php
	$secIndex		= site_url('c_project/qhsedocument/qhse_documentlist/?id='.$this->url_encryption_helper->encode_url($doc_code));
	$secOpen 		= base_url() . 'assets/AdminLTE-2.0.5/doc_center/webpdf/web/viewer.php?FileUpName=';
	$secDLURL		= site_url('c_project/qhsedocument/download_file/?id='.$this->url_encryption_helper->encode_url($doc_code));
?>
<script>	
	function deleteDoc(thisVal)
	{
		document.sendDelete.action = thisVal;
		document.getElementById('delClass').click();
	}
	
	$(document).ready(function()
	{
		$(".tombol-delete").click(function()
		{
			var index_qhsdoc= "<?php echo $secIndex; ?>";
			var formAction 	= $('#sendDelete')[0].action;
			var data = $('.form-user').serialize();
			$.ajax({
				type: 'POST',
				url: formAction,
				data: data,
				success: function(response)
				{
					$( "#example1" ).load( ""+index_qhsdoc+" #example1" );
				}
			});
			
		});
	});
	
	function chgGROUPDOC()
	{
		selGROUPDOC 	= document.getElementById('selGROUPDOCX').value;
		document.getElementById('selGROUPDOC').value = selGROUPDOC;
		selCLASSDOC 	= document.getElementById('selCLASSDOCX').value;
		document.getElementById('selCLASSDOC').value = '';
		selTYPEDOC 	= document.getElementById('selTYPEDOCX').value;
		document.getElementById('selTYPEDOC').value = '';
		
		document.getElementById('subChgGROUPDOC').click();
	}
	
	function chgCLASSDOC()
	{
		selGROUPDOC 	= document.getElementById('selGROUPDOCX').value;
		document.getElementById('selGROUPDOCA').value = selGROUPDOC;
		selCLASSDOC 	= document.getElementById('selCLASSDOCX').value;
		document.getElementById('selCLASSDOCA').value = selCLASSDOC;
		selTYPEDOC 	= document.getElementById('selTYPEDOCX').value;
		document.getElementById('selTYPEDOCA').value = '';
		
		document.getElementById('subChgCLASSDOC').click();
	}
	
	function chgTYPEDOC()
	{
		selGROUPDOC 	= document.getElementById('selGROUPDOCX').value;
		document.getElementById('selGROUPDOCB').value = selGROUPDOC;
		selCLASSDOC 	= document.getElementById('selCLASSDOCX').value;
		document.getElementById('selCLASSDOCB').value = selCLASSDOC;
		selTYPEDOC 	= document.getElementById('selTYPEDOCX').value;
		document.getElementById('selTYPEDOCB').value = selTYPEDOC;
		
		document.getElementById('subChgTYPEDOC').click();
	}
	
	function selectPICT(thisVal, isDoc)
	{
		if(isDoc == 0)
		{
			alert('Can not upload. Please open document by clicking the Document No.');
		}
		/*var DAU_WRITEX = document.getElementById('DAU_WRITEX').value;
		if(DAU_WRITEX == 0)
		{
			alert('You can not access to upload document.');
			return false;
		}
		var urlUplPICT = document.getElementById('secUplURL_'+thisVal).value;
		title = 'Select Item';
		w = 780;
		h = 550;
		//window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		return window.open(urlUplPICT, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);*/
	}
	
	var urlOpen = "<?php echo $secOpen;?>";
	var urlDL = "<?php echo $secDLURL;?>";
	var urlOpenx = "<?php echo base_url();?>";
	
	function typeOpenNewTab(thisVal)
	{
		var myFileName	= document.getElementById('FileUpName'+thisVal).value;
		var FileUpName	= ''+myFileName+'&base_url='+urlOpenx;
		title = 'Select Item';
		w = 850;
		h = 550;
		//window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		return window.open(urlOpen+FileUpName, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
	}
	
	function typeOpenNewTabXX(theRow)
	{
		var url	= document.getElementById('File_Name'+theRow).value;
		//alert(url)
		title = 'Select Item';
		w = 1000;
		h = 550;
		//window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		return window.open(url, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
	}
	
	function typeOpenNewTab1(thisVal)
	{
		var myFileName	= document.getElementById('FileUpName'+thisVal).value;
		var FileUpName	= ''+myFileName+'&base_url='+urlOpenx;
		title = 'Select Item';
		w = 850;
		h = 550;
		//window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		return window.open(urlDL+FileUpName, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
	}
	/*function typeOpenNewTab(thisVal)
	{
		var myFileName	= document.getElementById('FileUpName'+thisVal).value;
		var FileUpName	= ''+myFileName;
		alert(FileUpName)
		title = 'Select Item';
		w = 850;
		h = 550;
		//window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		return window.open(urlOpen+FileUpName, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
	}*/
</script>

<script>
	$(document).ready(function() {
		$('#example1').DataTable({
			"processing": true,
			"serverSide": false,
			//"scrollX": false,
			"autoWidth": true,
			"filter": true,
			"type": "POST",
			//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
			"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
			"columnDefs": [	{ targets: [1,2,5,6], className: 'dt-body-center' },
							{ "width": "100px", "targets": [1] }
						],
			"language": {
				"infoFiltered":"",
				"processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
			},
		});

		$("#example1").DataTable();
	});
</script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/jQuery/jquery-2.2.3.min.js'; ?>"></script>
