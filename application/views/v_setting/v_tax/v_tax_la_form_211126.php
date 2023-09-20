<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 11 Oktober 2018
	* File Name	= v_tax_la_ppn_form.php
	* Location		= -
*/
?>
<?php 
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody    = $this->session->userdata['appBody'];

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

// _global function
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
$decFormat		= 2;

$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
	
if($task == 'add')
{
	$TAXLA_NUM		= "";
	$TAXLA_CODE		= "";
	$TAXLA_DESC		= "";
	$TAXLA_PERC		= 0;
	$TAXLA_LINKIN	= "";
	$ACC_ID			= "";
	$TAXLA_LINKOUT	= "";
	$ACC_ID_UM		= "";
}
else
{
	$TAXLA_NUM 		= $default['TAXLA_NUM'];
	$TAXLA_CODE 	= $default['TAXLA_CODE'];
	$TAXLA_DESC 	= $default['TAXLA_DESC'];
	$TAXLA_PERC 	= $default['TAXLA_PERC'];
	$TAXLA_LINKIN 	= $default['TAXLA_LINKIN'];
	$ACC_ID			= $TAXLA_LINKIN;
	$TAXLA_LINKOUT	= $default['TAXLA_LINKOUT'];
	$ACC_ID_UM		= $TAXLA_LINKOUT;
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

	<script src="<?php echo base_url('assets/js/jquery-1.10.1.min.js') ?>" type="text/javascript"></script>
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
			if($TranslCode == 'Translate')$Translate = $LangTransl;
			if($TranslCode == 'Setting')$Setting = $LangTransl;
			if($TranslCode == 'Code')$Code = $LangTransl;
			if($TranslCode == 'Zone')$Zone = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'ZonePerc')$ZonePerc = $LangTransl;
			if($TranslCode == 'Criteria')$Criteria = $LangTransl;
			if($TranslCode == 'Percentation')$Percentation = $LangTransl;
			if($TranslCode == 'taxName')$taxName = $LangTransl;
			if($TranslCode == 'taxNmEmpty')$taxNmEmpty = $LangTransl;
			if($TranslCode == 'taxLAEmpty')$taxLAEmpty = $LangTransl;
			if($TranslCode == 'taxPercEmpty')$taxPercEmpty = $LangTransl;
		endforeach;

		$comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
			<h1>
			    <?php echo $mnName; ?>
			    <small><?php echo $Setting; ?></small>
		  	</h1>
		</section>

		<script>
			function chekData()
			{
				TAXLA_CODE		= document.getElementById('TAXLA_CODE').value;
				TAXLA_DESC		= document.getElementById('TAXLA_DESC').value;
				TAXLA_PERC		= document.getElementById('TAXLA_PERC').value;
				TAXLA_LINKIN	= document.getElementById('TAXLA_LINKIN').value;
				
				if(TAXLA_CODE == "")
				{
					swal('<?php echo $taxNmEmpty; ?>',
					{
						icon: "warning",
					});
					document.getElementById('TAXLA_CODE').focus();
					return false;
				}
				if(TAXLA_PERC == 0)
				{
					swal('<?php echo $taxPercEmpty; ?>',
					{
						icon: "warning",
					});
					document.getElementById('TAXLA_PERC1').focus();
					return false;
				}
				if(TAXLA_LINKIN == "")
				{
					alert('<?php echo $taxLAEmpty; ?>',
					{
						icon: "warning",
					});
					document.getElementById('TAXLA_LINKIN').focus();
					return false;
				}
			}
		</script>

		<section class="content">	
		    <div class="row">
		        <div class="col-md-12">
		            <div class="box box-primary">
		                <div class="box-header with-border" style="display:none">               
		              		<div class="box-tools pull-right">
		                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
		                        </button>
		                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
		                    </div>
		                </div>
		                <div class="box-body chart-responsive">
		                	<form class="form-horizontal" name="absen_form" method="post" action="<?php echo $form_action; ?>" onSubmit="return chekData()">
		                    	<input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $taxName; ?></label>
		                            <input type="hidden" class="form-control" name="TAXLA_NUM" id="TAXLA_NUM" value="<?php echo $TAXLA_NUM; ?>">
		                          	<div class="col-sm-10">
		                                <input type="text" class="form-control" name="TAXLA_CODE" id="TAXLA_CODE" value="<?php echo $TAXLA_CODE; ?>">
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-2 control-label"><?php echo $Description; ?></label>
		                            <div class="col-sm-10">
		                                <input type="text" class="form-control" name="TAXLA_DESC" id="TAXLA_DESC" value="<?php echo $TAXLA_DESC; ?>" />
		                            </div>
		                        </div>
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-2 control-label"><?php echo $Percentation; ?></label>
		                            <div class="col-sm-10">
		                                <input type="hidden" class="form-control" name="TAXLA_PERC" id="TAXLA_PERC" value="<?php echo $TAXLA_PERC; ?>" />
		                                <input type="text" class="form-control" style="text-align:right" name="TAXLA_PERC1" id="TAXLA_PERC1" value="<?php echo number_format($TAXLA_PERC, 2); ?>" onBlur="getTaxPerc(this)" onKeyPress="return isIntOnlyNew(event);" >
		                            </div>
		                        </div>
		                        <script>
									function getTaxPerc(thisVal)
									{
										
										TAXLA_PERC	= parseFloat(eval(thisVal).value.split(",").join(""));
										document.getElementById('TAXLA_PERC').value 	= TAXLA_PERC;
										document.getElementById('TAXLA_PERC1').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TAXLA_PERC)), 2));
									}
								</script>
								<?php
									$PRJCODE		= 'KTR';                            
		                            $sqlC0x			= "SELECT PRJCODE FROM tbl_project WHERE isHO = 1";
		                            $resC0x 		= $this->db->query($sqlC0x)->result();
									foreach($resC0x as $rowC0x) :
										$PRJCODE	= $rowC0x->PRJCODE;
									endforeach;
		                            $sqlC0a		= "tbl_chartaccount WHERE Account_Level = '0' AND Account_Category = '2' AND PRJCODE = '$PRJCODE'";
		                            $resC0a 	= $this->db->count_all($sqlC0a);
		                            
		                            $sqlC0b		= "SELECT DISTINCT Acc_ID, Account_Number, Account_NameEn, Account_NameId, Acc_ParentList,
														Acc_DirParent, isLast
		                                            FROM tbl_chartaccount WHERE Account_Level = '0' AND Account_Category = '2' AND PRJCODE = '$PRJCODE'";
		                            $resC0b 	= $this->db->query($sqlC0b)->result();
		                        ?>
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-2 control-label">Link In</label>
		                            <div class="col-sm-10">
		                                <select name="TAXLA_LINKIN" id="TAXLA_LINKIN" class="form-control select2">
		                        			<option value="" > --- </option>
		                                    <?php
											if($resC0a>0)
											{
												foreach($resC0b as $rowC0b) :
													$Acc_ID0		= $rowC0b->Acc_ID;
													$Account_Number0= $rowC0b->Account_Number;
													$Acc_DirParent0	= $rowC0b->Acc_DirParent;
													if($LangID == 'IND')
													{
														$Account_Name0	= $rowC0b->Account_NameId;
													}
													else
													{
														$Account_Name0	= $rowC0b->Account_NameEn;
													}
													
													$Acc_ParentList0	= $rowC0b->Acc_ParentList;
													$isLast_0			= $rowC0b->isLast;
													$disbaled_0			= 0;
													if($isLast_0 == 0)
														$disbaled_0		= 1;
											
													$sqlC1a		= "tbl_chartaccount WHERE Acc_DirParent = '$Account_Number0' 
																	AND Account_Level = '1' AND Account_Category = '2' AND PRJCODE = '$PRJCODE'";
													$resC1a 	= $this->db->count_all($sqlC1a);
													
													$collData0	= "$Account_Number0";
												?>
		                                    	<option value="<?php echo $Account_Number0; ?>" <?php if($Account_Number0 == $ACC_ID) { ?> selected <?php } if($disbaled_0 == 1) { ?> disabled style="font-weight:bold" <?php } ?>><?php echo "$Account_Name0 - $collData0"; ?></option>
		                                        <?php
												if($resC1a>0)
												{
													$sqlC1b		= "SELECT DISTINCT Acc_ID, Account_Number, Account_NameEn, Account_NameId, 
																		Acc_ParentList, isLast
																	FROM tbl_chartaccount 
																	WHERE Acc_DirParent = '$Account_Number0'
																		AND Account_Level = '1' AND Account_Category = '2' AND PRJCODE = '$PRJCODE'";
													$resC1b 	= $this->db->query($sqlC1b)->result();
													foreach($resC1b as $rowC1b) :
														$Acc_ID1		= $rowC1b->Acc_ID;
														$Account_Number1= $rowC1b->Account_Number;
														$Acc_DirParent1	= $rowC0b->Acc_DirParent;
														if($LangID == 'IND')
														{
															$Account_Name1	= $rowC1b->Account_NameId;
														}
														else
														{
															$Account_Name1	= $rowC1b->Account_NameEn;
														}
														
														$Acc_ParentList1	= $rowC1b->Acc_ParentList;
														$isLast_1			= $rowC1b->isLast;
														$disbaled_1			= 0;
														if($isLast_1 == 0)
															$disbaled_1		= 1;
													
														$level_coa1			= "&nbsp;&nbsp;&nbsp;";
												
														$sqlC2a		= "tbl_chartaccount WHERE Acc_DirParent = '$Account_Number1'
																		AND Account_Level = '2' AND Account_Category = '2' AND PRJCODE = '$PRJCODE'";
														$resC2a 	= $this->db->count_all($sqlC2a);
														
														$collData1	= "$Account_Number1";
														?>
														<option value="<?php echo $Account_Number1; ?>" <?php if($Account_Number1 == $ACC_ID) { ?> selected <?php } if($disbaled_1 == 1) { ?> disabled style="font-weight:bold" <?php } ?>><?php echo "$level_coa1$Account_Name1 - $collData1"; ?></option>
														<?php
		                                                if($resC2a>0)
		                                                {
		                                                    $sqlC2b		= "SELECT DISTINCT Acc_ID, Account_Number, Account_NameEn, Account_NameId,
																				Acc_ParentList, isLast
		                                                                    FROM tbl_chartaccount WHERE Acc_DirParent = '$Account_Number1'
																				AND Account_Level = '2' AND Account_Category = '2' AND PRJCODE = '$PRJCODE'";                                                    $resC2b 	= $this->db->query($sqlC2b)->result();
		                                                    foreach($resC2b as $rowC2b) :
		                                                        $Acc_ID2		= $rowC2b->Acc_ID;
		                                                        $Account_Number2= $rowC2b->Account_Number;
																$Acc_DirParent2	= $rowC0b->Acc_DirParent;
		                                                        if($LangID == 'IND')
		                                                        {
		                                                            $Account_Name2	= $rowC2b->Account_NameId;
		                                                        }
		                                                        else
		                                                        {
		                                                            $Account_Name2	= $rowC2b->Account_NameEn;
		                                                        }
		                                                        
		                                                        $Acc_ParentList2	= $rowC2b->Acc_ParentList;
																$isLast_2			= $rowC2b->isLast;
																$disbaled_2			= 0;
																if($isLast_2 == 0)
																	$disbaled_2		= 1;
																
		                                                        $level_coa2			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		                                                
		                                                        $sqlC3a		= "tbl_chartaccount WHERE Acc_DirParent = '$Account_Number2'
																				AND Account_Level = '3' AND Account_Category = '2' AND PRJCODE = '$PRJCODE'";
		                                                        $resC3a 	= $this->db->count_all($sqlC3a);
																
																$collData2	= "$Account_Number2";
		                                                        ?>
		                                                        <option value="<?php echo $Account_Number2; ?>" <?php if($Account_Number2 == $ACC_ID) { ?> selected <?php } if($disbaled_2 == 1) { ?> disabled style="font-weight:bold" <?php } ?>><?php echo "$level_coa2$Account_Name2 - $collData2"; ?></option>
																<?php
		                                                        if($resC3a>0)
		                                                        {
		                                                            $sqlC3b		= "SELECT DISTINCT Acc_ID, Account_Number, Account_NameEn, 
																						Account_NameId, Acc_ParentList, isLast
		                                                                            FROM tbl_chartaccount 
																					WHERE Acc_DirParent = '$Account_Number2'
																						AND Account_Level = '3' AND Account_Category = '2' AND PRJCODE = '$PRJCODE'";
		                                                            $resC3b 	= $this->db->query($sqlC3b)->result();
		                                                            foreach($resC3b as $rowC3b) :
		                                                                $Acc_ID3		= $rowC3b->Acc_ID;
		                                                                $Account_Number3= $rowC3b->Account_Number;
																		$Acc_DirParent3	= $rowC0b->Acc_DirParent;
		                                                                if($LangID == 'IND')
		                                                                {
		                                                                    $Account_Name3	= $rowC3b->Account_NameId;
		                                                                }
		                                                                else
		                                                                {
		                                                                    $Account_Name3	= $rowC3b->Account_NameEn;
		                                                                }
		                                                                
		                                                                $Acc_ParentList3	= $rowC3b->Acc_ParentList;
																		$isLast_3			= $rowC3b->isLast;
																		$disbaled_3			= 0;
																		if($isLast_3 == 0)
																			$disbaled_3		= 1;
																		
		                                                                $level_coa3			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		                                                        
		                                                                $sqlC4a		= "tbl_chartaccount WHERE Acc_DirParent = '$Account_Number3'
																						AND Account_Level = '4' AND Account_Category = '2' AND PRJCODE = '$PRJCODE'";
		                                                                $resC4a 	= $this->db->count_all($sqlC4a);
																		
																		$collData3	= "$Account_Number3";
		                                                                ?>
		                                                                <option value="<?php echo $Account_Number3; ?>" <?php if($Account_Number3 == $ACC_ID) { ?> selected <?php } if($disbaled_3 == 1) { ?> disabled style="font-weight:bold" <?php } ?>><?php echo "$level_coa3$Account_Name3 - $collData3"; ?></option>
																		<?php
		                                                                if($resC4a>0)
		                                                                {
		                                                                    $sqlC4b		= "SELECT DISTINCT Acc_ID, Account_Number, Account_NameEn, 
																								Account_NameId, Acc_ParentList, isLast
		                                                                                    FROM tbl_chartaccount 
																							WHERE Acc_DirParent = '$Account_Number3'
																								AND Account_Level = '4'
																								AND Account_Category = '2' AND PRJCODE = '$PRJCODE'";
		                                                                    $resC4b 	= $this->db->query($sqlC4b)->result();
		                                                                    foreach($resC4b as $rowC4b) :
		                                                                        $Acc_ID4		= $rowC4b->Acc_ID;
		                                                                        $Account_Number4= $rowC4b->Account_Number;
																				$Acc_DirParent4	= $rowC0b->Acc_DirParent;
		                                                                        if($LangID == 'IND')
		                                                                        {
		                                                                            $Account_Name4	= $rowC4b->Account_NameId;
		                                                                        }
		                                                                        else
		                                                                        {
		                                                                            $Account_Name4	= $rowC4b->Account_NameEn;
		                                                                        }
		                                                                        
		                                                                        $Acc_ParentList4	= $rowC4b->Acc_ParentList;
																				$isLast_4			= $rowC4b->isLast;
																				$disbaled_4			= 0;
																				if($isLast_4 == 0)
																					$disbaled_4		= 1;
																				
		                                                                        $level_coa4			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		                                                                
		                                                                        $sqlC5a		= "tbl_chartaccount WHERE
																								Acc_DirParent = '$Account_Number4'
																								AND Account_Category = '2' AND PRJCODE = '$PRJCODE'";
		                                                                        $resC5a 	= $this->db->count_all($sqlC5a);
																				
																				$collData4	= "$Account_Number4";
		                                                                        ?>
		                                                                        <option value="<?php echo $Account_Number4; ?>" <?php if($Account_Number4 == $ACC_ID) { ?> selected <?php } if($disbaled_4 == 1) { ?> disabled style="font-weight:bold" <?php } ?>><?php echo "$level_coa4$Account_Name4 - $collData4"; ?></option>
		                                                                        <?php
																				if($resC5a>0)
																				{
																					$sqlC5b		= "SELECT DISTINCT Acc_ID, Account_Number, 
																										Account_NameEn, 
																										Account_NameId, Acc_ParentList, isLast
																									FROM tbl_chartaccount 
																									WHERE Acc_DirParent = '$Account_Number4'
																										AND Account_Level = '5'
																										AND Account_Category = '2' AND PRJCODE = '$PRJCODE'";
																					$resC5b 	= $this->db->query($sqlC5b)->result();
																					foreach($resC5b as $rowC5b) :
																						$Acc_ID5		= $rowC5b->Acc_ID;
																						$Account_Number5= $rowC5b->Account_Number;
																						$Acc_DirParent5	= $rowC5b->Acc_DirParent;
																						if($LangID == 'IND')
																						{
																							$Account_Name5	= $rowC5b->Account_NameId;
																						}
																						else
																						{
																							$Account_Name5	= $rowC5b->Account_NameEn;
																						}
																						
																						$Acc_ParentList5	= $rowC5b->Acc_ParentList;
																						$isLast_5			= $rowC5b->isLast;
																						$disbaled_5			= 0;
																						if($isLast_5 == 0)
																							$disbaled_5		= 1;
																						
																						$level_coa5			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
																				
																						$sqlC6a		= "tbl_chartaccount WHERE
																										Acc_DirParent = '$Account_Number5'";
																						$resC6a 	= $this->db->count_all($sqlC5a);
																						
																						$collData5	= "$Account_Number5";
																						?>
																						<option value="<?php echo $Account_Number5; ?>" <?php if($Account_Number5 == $ACC_ID) { ?> selected <?php } if($disbaled_5 == 1) { ?> disabled style="font-weight:bold" <?php } ?>><?php echo "$level_coa5$Account_Name5 - $collData5"; ?></option>
																						<?php
																					endforeach;
																				}
		                                                                    endforeach;
		                                                                }
		                                                            endforeach;
		                                                        }
		                                                    endforeach;
		                                                }
													endforeach;
												}
												endforeach;
											}
											?>
		                                </select>
		                            </div>
		                        </div>
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-2 control-label">Link Out</label>
		                            <div class="col-sm-10">
		                                <select name="TAXLA_LINKOUT" id="TAXLA_LINKOUT" class="form-control select2">
		                        			<option value="" > --- </option>
		                                    <?php
											if($resC0a>0)
											{
												foreach($resC0b as $rowC0b) :
													$Acc_ID0		= $rowC0b->Acc_ID;
													$Account_Number0= $rowC0b->Account_Number;
													$Acc_DirParent0	= $rowC0b->Acc_DirParent;
													if($LangID == 'IND')
													{
														$Account_Name0	= $rowC0b->Account_NameId;
													}
													else
													{
														$Account_Name0	= $rowC0b->Account_NameEn;
													}
													
													$Acc_ParentList0	= $rowC0b->Acc_ParentList;
													$isLast_0			= $rowC0b->isLast;
													$disbaled_0			= 0;
													if($isLast_0 == 0)
														$disbaled_0		= 1;
											
													$sqlC1a		= "tbl_chartaccount WHERE Acc_DirParent = '$Account_Number0' 
																	AND Account_Level = '1' AND Account_Category = '2' AND PRJCODE = '$PRJCODE'";
													$resC1a 	= $this->db->count_all($sqlC1a);
													
													$collData0	= "$Account_Number0";
												?>
		                                    	<option value="<?php echo $Account_Number0; ?>" <?php if($Account_Number0 == $ACC_ID_UM) { ?> selected <?php } if($disbaled_0 == 1) { ?> disabled style="font-weight:bold" <?php } ?>><?php echo "$Account_Name0 - $collData0"; ?></option>
		                                        <?php
												if($resC1a>0)
												{
													$sqlC1b		= "SELECT DISTINCT Acc_ID, Account_Number, Account_NameEn, Account_NameId, 
																		Acc_ParentList, isLast
																	FROM tbl_chartaccount 
																	WHERE Acc_DirParent = '$Account_Number0'
																		AND Account_Level = '1' AND Account_Category = '2' AND PRJCODE = '$PRJCODE'";
													$resC1b 	= $this->db->query($sqlC1b)->result();
													foreach($resC1b as $rowC1b) :
														$Acc_ID1		= $rowC1b->Acc_ID;
														$Account_Number1= $rowC1b->Account_Number;
														$Acc_DirParent1	= $rowC0b->Acc_DirParent;
														if($LangID == 'IND')
														{
															$Account_Name1	= $rowC1b->Account_NameId;
														}
														else
														{
															$Account_Name1	= $rowC1b->Account_NameEn;
														}
														
														$Acc_ParentList1	= $rowC1b->Acc_ParentList;
														$isLast_1			= $rowC1b->isLast;
														$disbaled_1			= 0;
														if($isLast_1 == 0)
															$disbaled_1		= 1;
													
														$level_coa1			= "&nbsp;&nbsp;&nbsp;";
												
														$sqlC2a		= "tbl_chartaccount WHERE Acc_DirParent = '$Account_Number1'
																		AND Account_Level = '2' AND Account_Category = '2' AND PRJCODE = '$PRJCODE'";
														$resC2a 	= $this->db->count_all($sqlC2a);
														
														$collData1	= "$Account_Number1";
														?>
														<option value="<?php echo $Account_Number1; ?>" <?php if($Account_Number1 == $ACC_ID_UM) { ?> selected <?php } if($disbaled_1 == 1) { ?> disabled style="font-weight:bold" <?php } ?>><?php echo "$level_coa1$Account_Name1 - $collData1"; ?></option>
														<?php
		                                                if($resC2a>0)
		                                                {
		                                                    $sqlC2b		= "SELECT DISTINCT Acc_ID, Account_Number, Account_NameEn, Account_NameId,
																				Acc_ParentList, isLast
		                                                                    FROM tbl_chartaccount WHERE Acc_DirParent = '$Account_Number1'
																				AND Account_Level = '2' AND Account_Category = '2' AND PRJCODE = '$PRJCODE'";                                                    $resC2b 	= $this->db->query($sqlC2b)->result();
		                                                    foreach($resC2b as $rowC2b) :
		                                                        $Acc_ID2		= $rowC2b->Acc_ID;
		                                                        $Account_Number2= $rowC2b->Account_Number;
																$Acc_DirParent2	= $rowC0b->Acc_DirParent;
		                                                        if($LangID == 'IND')
		                                                        {
		                                                            $Account_Name2	= $rowC2b->Account_NameId;
		                                                        }
		                                                        else
		                                                        {
		                                                            $Account_Name2	= $rowC2b->Account_NameEn;
		                                                        }
		                                                        
		                                                        $Acc_ParentList2	= $rowC2b->Acc_ParentList;
																$isLast_2			= $rowC2b->isLast;
																$disbaled_2			= 0;
																if($isLast_2 == 0)
																	$disbaled_2		= 1;
																
		                                                        $level_coa2			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		                                                
		                                                        $sqlC3a		= "tbl_chartaccount WHERE Acc_DirParent = '$Account_Number2'
																				AND Account_Level = '3' AND Account_Category = '2' AND PRJCODE = '$PRJCODE'";
		                                                        $resC3a 	= $this->db->count_all($sqlC3a);
																
																$collData2	= "$Account_Number2";
		                                                        ?>
		                                                        <option value="<?php echo $Account_Number2; ?>" <?php if($Account_Number2 == $ACC_ID_UM) { ?> selected <?php } if($disbaled_2 == 1) { ?> disabled style="font-weight:bold" <?php } ?>><?php echo "$level_coa2$Account_Name2 - $collData2"; ?></option>
																<?php
		                                                        if($resC3a>0)
		                                                        {
		                                                            $sqlC3b		= "SELECT DISTINCT Acc_ID, Account_Number, Account_NameEn, 
																						Account_NameId, Acc_ParentList, isLast
		                                                                            FROM tbl_chartaccount 
																					WHERE Acc_DirParent = '$Account_Number2'
																						AND Account_Level = '3' AND Account_Category = '2' AND PRJCODE = '$PRJCODE'";
		                                                            $resC3b 	= $this->db->query($sqlC3b)->result();
		                                                            foreach($resC3b as $rowC3b) :
		                                                                $Acc_ID3		= $rowC3b->Acc_ID;
		                                                                $Account_Number3= $rowC3b->Account_Number;
																		$Acc_DirParent3	= $rowC0b->Acc_DirParent;
		                                                                if($LangID == 'IND')
		                                                                {
		                                                                    $Account_Name3	= $rowC3b->Account_NameId;
		                                                                }
		                                                                else
		                                                                {
		                                                                    $Account_Name3	= $rowC3b->Account_NameEn;
		                                                                }
		                                                                
		                                                                $Acc_ParentList3	= $rowC3b->Acc_ParentList;
																		$isLast_3			= $rowC3b->isLast;
																		$disbaled_3			= 0;
																		if($isLast_3 == 0)
																			$disbaled_3		= 1;
																		
		                                                                $level_coa3			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		                                                        
		                                                                $sqlC4a		= "tbl_chartaccount WHERE Acc_DirParent = '$Account_Number3'
																						AND Account_Level = '4' AND Account_Category = '2' AND PRJCODE = '$PRJCODE'";
		                                                                $resC4a 	= $this->db->count_all($sqlC4a);
																		
																		$collData3	= "$Account_Number3";
		                                                                ?>
		                                                                <option value="<?php echo $Account_Number3; ?>" <?php if($Account_Number3 == $ACC_ID_UM) { ?> selected <?php } if($disbaled_3 == 1) { ?> disabled style="font-weight:bold" <?php } ?>><?php echo "$level_coa3$Account_Name3 - $collData3"; ?></option>
																		<?php
		                                                                if($resC4a>0)
		                                                                {
		                                                                    $sqlC4b		= "SELECT DISTINCT Acc_ID, Account_Number, Account_NameEn, 
																								Account_NameId, Acc_ParentList, isLast
		                                                                                    FROM tbl_chartaccount 
																							WHERE Acc_DirParent = '$Account_Number3'
																								AND Account_Level = '4'
																								AND Account_Category = '2' AND PRJCODE = '$PRJCODE'";
		                                                                    $resC4b 	= $this->db->query($sqlC4b)->result();
		                                                                    foreach($resC4b as $rowC4b) :
		                                                                        $Acc_ID4		= $rowC4b->Acc_ID;
		                                                                        $Account_Number4= $rowC4b->Account_Number;
																				$Acc_DirParent4	= $rowC0b->Acc_DirParent;
		                                                                        if($LangID == 'IND')
		                                                                        {
		                                                                            $Account_Name4	= $rowC4b->Account_NameId;
		                                                                        }
		                                                                        else
		                                                                        {
		                                                                            $Account_Name4	= $rowC4b->Account_NameEn;
		                                                                        }
		                                                                        
		                                                                        $Acc_ParentList4	= $rowC4b->Acc_ParentList;
																				$isLast_4			= $rowC4b->isLast;
																				$disbaled_4			= 0;
																				if($isLast_4 == 0)
																					$disbaled_4		= 1;
																				
		                                                                        $level_coa4			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		                                                                
		                                                                        $sqlC5a		= "tbl_chartaccount WHERE
																								Acc_DirParent = '$Account_Number4'
																								AND Account_Category = '2' AND PRJCODE = '$PRJCODE'";
		                                                                        $resC5a 	= $this->db->count_all($sqlC5a);
																				
																				$collData4	= "$Account_Number4";
		                                                                        ?>
		                                                                        <option value="<?php echo $Account_Number4; ?>" <?php if($Account_Number4 == $ACC_ID_UM) { ?> selected <?php } if($disbaled_4 == 1) { ?> disabled style="font-weight:bold" <?php } ?>><?php echo "$level_coa4$Account_Name4 - $collData4"; ?></option>
		                                                                        <?php
																				if($resC5a>0)
																				{
																					$sqlC5b		= "SELECT DISTINCT Acc_ID, Account_Number, 
																										Account_NameEn, 
																										Account_NameId, Acc_ParentList, isLast
																									FROM tbl_chartaccount 
																									WHERE Acc_DirParent = '$Account_Number4'
																										AND Account_Level = '5'
																										AND Account_Category = '2' AND PRJCODE = '$PRJCODE'";
																					$resC5b 	= $this->db->query($sqlC5b)->result();
																					foreach($resC5b as $rowC5b) :
																						$Acc_ID5		= $rowC5b->Acc_ID;
																						$Account_Number5= $rowC5b->Account_Number;
																						$Acc_DirParent5	= $rowC5b->Acc_DirParent;
																						if($LangID == 'IND')
																						{
																							$Account_Name5	= $rowC5b->Account_NameId;
																						}
																						else
																						{
																							$Account_Name5	= $rowC5b->Account_NameEn;
																						}
																						
																						$Acc_ParentList5	= $rowC5b->Acc_ParentList;
																						$isLast_5			= $rowC5b->isLast;
																						$disbaled_5			= 0;
																						if($isLast_5 == 0)
																							$disbaled_5		= 1;
																						
																						$level_coa5			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
																				
																						$sqlC6a		= "tbl_chartaccount WHERE
																										Acc_DirParent = '$Account_Number5'";
																						$resC6a 	= $this->db->count_all($sqlC5a);
																						
																						$collData5	= "$Account_Number5";
																						?>
																						<option value="<?php echo $Account_Number5; ?>" <?php if($Account_Number5 == $ACC_ID_UM) { ?> selected <?php } if($disbaled_5 == 1) { ?> disabled style="font-weight:bold" <?php } ?>><?php echo "$level_coa5$Account_Name5 - $collData5"; ?></option>
																						<?php
																					endforeach;
																				}
		                                                                    endforeach;
		                                                                }
		                                                            endforeach;
		                                                        }
		                                                    endforeach;
		                                                }
													endforeach;
												}
												endforeach;
											}
											?>
		                                </select>
		                            </div>
		                        </div>
		                        <br>
		                        <div class="form-group">
		                            <div class="col-sm-offset-2 col-sm-10">
		                            <?php
										if($ISCREATE == 1)
										{
											if($task=='add')
											{
												?>
													<button class="btn btn-primary">
													<i class="fa fa-save"></i></button>&nbsp;
												<?php
											}
											else
											{
												?>
													<button class="btn btn-primary" >
													<i class="fa fa-save"></i></button>&nbsp;
												<?php
											}
										}
									
										echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>');
									?>
		                            </div>
		                        </div>
		                    </form>
                            <?php
                                $DefID      = $this->session->userdata['Emp_ID'];
                                $act_lnk    = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                                if($DefID == 'D15040004221')
                                    echo "<font size='1'><i>$act_lnk</i></font>";
                            ?>
		                </div>
		            </div>
		        </div>
		    </div>
		</section>
	</body>
</html>

<script>
  $(function () {
    //Initialize Select2 Elements
    $(".select2").select2();

    //Datemask dd/mm/yyyy
    $("#datemask").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});
    //Datemask2 mm/dd/yyyy
    $("#datemask2").inputmask("mm/dd/yyyy", {"placeholder": "mm/dd/yyyy"});
    //Money Euro
    $("[data-mask]").inputmask();

    //Date range picker
    $('#reservation').daterangepicker();
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A'});
    //Date range as a button
    $('#daterange-btn').daterangepicker(
        {
          ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
          },
          startDate: moment().subtract(29, 'days'),
          endDate: moment()
        },
        function (start, end) {
          $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }
    );

    //Date picker
    $('#datepicker1').datepicker({
      autoclose: true
    });

    //Date picker
    $('#datepicker2').datepicker({
      autoclose: true
    });

    //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass: 'iradio_minimal-blue'
    });
    //Red color scheme for iCheck
    $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
      checkboxClass: 'icheckbox_minimal-red',
      radioClass: 'iradio_minimal-red'
    });
    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass: 'iradio_flat-green'
    });

    //Colorpicker
    $(".my-colorpicker1").colorpicker();
    //color picker with addon
    $(".my-colorpicker2").colorpicker();

    //Timepicker
    $(".timepicker").timepicker({
      showInputs: false
    });
  });
</script>

<script>
	var decFormat		= 2;
	
	function doDecimalFormat(angka) 
	{
		var a, b, c, dec, i, j;
		angka = String(angka);
		if(angka.indexOf('.') > -1){ a = angka.split('.')[0] ; dec = angka.split('.')[1]
		} else { a = angka; dec = -1; }
		b = a.replace(/[^\d]/g,"");
		c = "";
		var panjang = b.length;
		j = 0;
		for (i = panjang; i > 0; i--) {
			j = j + 1;
			if (((j % 3) == 1) && (j != 1)) c = b.substr(i-1,1) + "," + c;
			else c = b.substr(i-1,1) + c;
		}
		if(dec == -1) return angka;
		else return (c + '.' + dec); 
	}
	
	function RoundNDecimal(X, N) {
		var T, S=new String(Math.round(X*Number("1e"+N)))
		while (S.length<=N) S='0'+S
		return S.substr(0, T=(S.length-N)) + '.' + S.substr(T, N)
	}
		
	function isIntOnlyNew(evt)
	{
		if (evt.which){ var charCode = evt.which; }
		else if(document.all && event.keyCode){ var charCode = event.keyCode; }
		else { return true; }
		return ((charCode == 45) || (charCode == 46) || (charCode == 8) || (charCode >= 48) && (charCode <= 57));
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