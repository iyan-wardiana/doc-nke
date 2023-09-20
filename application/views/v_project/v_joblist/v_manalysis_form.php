<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 3 Nopember 2021
	* File Name		= v_manalysis_form.php
	* Location		= -
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody 	= $this->session->userdata['appBody'];
$FlagUSER 	= $this->session->userdata['FlagUSER'];
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
$DEPCODE 	= $this->session->userdata['DEPCODE'];

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

// _global function
$this->db->select('Display_Rows,decFormat,APPLEV');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
	$APPLEV = $row->APPLEV;
endforeach;
$decFormat		= 2;

$currentRow = 0;
if($task == 'add')
{
	$MAN_NUM 		= date('YmdHis');
	$MAN_CODE 		= "";
	$MAN_NAME 		= "";
	$MAN_DESC 		= "";
	$MAN_STAT 		= 1;
}
?>
<!DOCTYPE html>
<html>
  	<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $appName; ?></title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <link rel="icon" type="image/png" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/favicon/lock-02.png'; ?>" sizes="32x32">
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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

        <!-- Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    </head>

	<style type="text/css">
		.search-table, td, th {
			border-collapse: collapse;
		}
		.search-table-outter { overflow-x: scroll; }
		
	    a[disabled="disabled"] {
	        pointer-events: none;
	    }
	</style>

	<?php
		$this->load->view('template/mna');
		//______$this->load->view('template/topbar');
		//______$this->load->view('template/sidebar');
		
		$ISREAD 	= $this->session->userdata['ISREAD'];
		$ISCREATE 	= $this->session->userdata['ISCREATE'];
		$ISAPPROVE 	= $this->session->userdata['ISAPPROVE'];
		$ISDWONL 	= $this->session->userdata['ISDWONL'];
		$ISDELETE	= $this->session->userdata['ISDELETE'];
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
			if($TranslCode == 'UniqCode')$UniqCode = $LangTransl;
			if($TranslCode == 'Code')$Code = $LangTransl;
			if($TranslCode == 'anlNm')$anlNm = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
			if($TranslCode == 'ItemName')$ItemName = $LangTransl;
			if($TranslCode == 'ItemQty')$ItemQty = $LangTransl;
			if($TranslCode == 'Price')$Price = $LangTransl;
			if($TranslCode == 'SelectItem')$SelectItem = $LangTransl;
			if($TranslCode == 'Remarks')$Remarks = $LangTransl;
			if($TranslCode == 'ItemList')$ItemList = $LangTransl;
			if($TranslCode == 'Select')$Select = $LangTransl;
			if($TranslCode == 'Close')$Close = $LangTransl;
		endforeach;
		
		if($LangID == 'IND')
		{
			$alert1		= "Kode analisa tidak boleh kosong";
			$alert2		= "Nama analisa tidak boleh kosong";
			$alert3		= "Silahkan pilih salah satu item";
		}
		else
		{
			$alert1		= "Analysis code can not be empty";
			$alert2		= "Analysis name can not be empty";
			$alert3		= "Please select an item";
		}

		$comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
			<h1>
			    <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/purchase_req.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $mnName; ?>
			    <small><?php //echo "$mnName"; ?></small>
			</h1>
		</section>

		<section class="content">
		    <div class="row">
	            <form class="form-horizontal" name="frm" method="post" action="<?php echo $form_action; ?>" enctype="multipart/form-data" onSubmit="return checkForm()">
					<div class="col-md-6">
						<div class="box box-primary">
							<div class="box-header with-border" style="display: none;">
								<i class="fa fa-cloud-upload"></i>
								<h3 class="box-title">&nbsp;</h3>
							</div>
							<div class="box-body">
				                <input type="Hidden" name="rowCount" id="rowCount" value="0">
				                <div class="form-group" style="display: none;">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $UniqCode; ?></label>
				                    <div class="col-sm-9">
				                    	<input type="text" class="form-control" style="text-align:left" id="MAN_NUM" name="MAN_NUM" size="5" value="<?php echo $MAN_NUM; ?>" readonly/>
				                    </div>
				                </div>
				                <div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Code; ?></label>
				                    <div class="col-sm-9">
				                    	<input type="text" class="form-control" style="text-align:left" id="MAN_CODE" name="MAN_CODE" size="5" value="<?php echo $MAN_CODE; ?>" />
				                    </div>
				                </div>
				                <div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $anlNm; ?></label>
				                    <div class="col-sm-9">
				                    	<input type="text" class="form-control" style="text-align:left" id="MAN_NAME" name="MAN_NAME" size="5" value="<?php echo $MAN_NAME; ?>" />                       
				                    </div>
				                </div>
				                <div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Description; ?></label>
				                    <div class="col-sm-6">
				                    	<textarea name="MAN_DESC" class="form-control" id="MAN_DESC" cols="30" style="height:60px"><?php echo $MAN_DESC; ?></textarea>                      
				                    </div>
				                    <div class="col-sm-3">
				                    	<a class="btn btn-app" data-toggle="modal" data-target="#mdl_addItm">
							                <!-- <span class="badge bg-green">300</span> -->
							                <i class="fa fa-barcode"></i> <?=$SelectItem?>
							            </a>                   
				                    </div>
				                </div>
							</div>
						</div>
					</div>

	                <div class="col-md-12">
	                    <div class="box box-primary">
	                        <div class="search-table-outter">
	                            <table id="tbl" class="table table-bordered table-striped" width="100%">
	                                <tr style="background:#CCCCCC">
	                                  	<th width="5%" style="text-align:left">&nbsp;</th>
	                                  	<th width="10%" style="text-align:center; vertical-align: middle;"><?php echo $ItemCode ?> </th>
	                                  	<th width="30%" style="text-align:center; vertical-align: middle;"><?php echo $ItemName ?> </th>
	                                  	<th width="8%" style="text-align:center"><?php echo $ItemQty; ?> </th>
	                                  	<th width="5%" style="text-align:center; vertical-align: middle;">Unit </th>
	                                  	<th width="15%" style="text-align:center; vertical-align: middle;"><?php echo $Price ?> </th>
	                                  	<th width="10%" style="text-align:center; vertical-align: middle;">Koef </th>
	                                  	<th width="17%" style="text-align:center; vertical-align: middle;">Total</th>
	                                </tr>
	                                <?php
	                                if($task == 'edit')
	                                {
	                                    $sqlDET	= "SELECT * FROM tbl_manalysis_detail WHERE MAN_NUM = '$MAN_NUM'";
	                                    $result = $this->db->query($sqlDET)->result();
	                                    $i		= 0;
	                                    $j		= 0;
	                                    
	                                    foreach($result as $row) :
	                                        $currentRow  	= ++$i;
	                                        $MAN_NUM 		= $row->MAN_NUM;
	                                        $MAN_CODE 		= $row->MAN_CODE;
	                                        $ITM_CODE 		= $row->ITM_CODE;
	                                        $ITM_NAME 		= $row->ITM_NAME;
	                                        $ITM_UNIT 		= $row->ITM_UNIT;
	                                        $ITM_GROUP 		= $row->ITM_GROUP;
	                                        $ITM_QTY 		= $row->ITM_QTY;
	                                        $ITM_PRICE 		= $row->ITM_PRICE;
	                                        $ITM_TOTAL 		= $row->ITM_TOTAL;
	                                        $ITM_KOEF 		= $row->ITM_KOEF;

	                                        /*$ITM_NAME 		= "";
		                                    $s_01			= "SELECT ITM_NAME FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' LIMIT 1";
		                                    $r_01 			= $this->db->query($s_01)->result();
		                                    foreach($r_01 as $rw_01) :
		                                        $ITM_NAME 	= $rw_01->ITM_NAME;
		                                    endforeach;*/
	                            
	                                    	/*	if ($j==1) {
	                                            echo "<tr class=zebra1>";
	                                            $j++;
	                                        } else {
	                                            echo "<tr class=zebra2>";
	                                            $j--;
	                                        }*/
	                                        ?> 
	                                        <tr id="tr_<?php echo $currentRow; ?>" style="vertical-align: middle;">
		                                        <td style="text-align:center; vertical-align: middle;">
		                                            <a href="#" onClick="deleteRow(<?php echo $currentRow; ?>)" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a>
	                                        	</td>
		                                        <td style="text-align:left; vertical-align: middle;" nowrap> <!-- IITM_CODE -->
		                                          	<?php echo $ITM_CODE; ?>
		                                          	<input type="hidden" id="data<?php echo $currentRow; ?>MAN_NUM" name="data[<?php echo $currentRow; ?>][MAN_NUM]" value="<?php echo $MAN_NUM; ?>" class="form-control" style="max-width:300px;">
		                                          	<input type="hidden" id="data<?php echo $currentRow; ?>MAN_CODE" name="data[<?php echo $currentRow; ?>][MAN_CODE]" value="<?php echo $MAN_CODE; ?>" class="form-control" style="max-width:300px;">
		                                          	<input type="hidden" id="data<?php echo $currentRow; ?>ITM_CODE" name="data[<?php echo $currentRow; ?>][ITM_CODE]" value="<?php echo $ITM_CODE; ?>" class="form-control" style="max-width:300px;">
		                                          	<input type="hidden" id="data<?php echo $currentRow; ?>ITM_GROUP" name="data[<?php echo $currentRow; ?>][ITM_GROUP]" value="<?php echo $ITM_GROUP; ?>" class="form-control" style="max-width:300px;">
		                                      	</td>
		                                      	<td style="text-align:left; vertical-align: middle;"> <!-- ITM_NAME -->
		                                        	<?php echo $ITM_NAME; ?>
		                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][ITM_NAME]" id="data<?php echo $currentRow; ?>ITM_NAME" value="<?php echo $ITM_NAME; ?>" class="form-control" style="max-width:300px;" >
		                                        	<!-- <div style="font-style: italic;">
												  		<i class='text-muted fa fa-chevron-circle-right'></i>&nbsp;&nbsp;<?=$JOBPARDESC?>
												  	</div> -->
		                                        </td>
		                                     	<td style="text-align:right; vertical-align: middle;"> <!-- ITM_QTY -->
		                                          	<input type="text" name="ITM_QTY<?php echo $currentRow; ?>" id="ITM_QTY<?php echo $currentRow; ?>" value="<?php print number_format($ITM_QTY, $decFormat); ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgQty(this,<?php echo $currentRow; ?>);" readonly >
													<input type="hidden" name="data[<?php echo $currentRow; ?>][ITM_QTY]" id="data<?php echo $currentRow; ?>ITM_QTY" value="<?php print $ITM_QTY; ?>" class="form-control">
		                                        </td>
		                                        <td style="text-align:center; vertical-align: middle;" nowrap> <!-- ITM_UNIT -->
		                                          <?php echo $ITM_UNIT; ?>
		                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][ITM_UNIT]" id="data<?php echo $currentRow; ?>ITM_UNIT" value="<?php echo $ITM_UNIT; ?>" class="form-control" style="max-width:300px;" >
		                                        </td>
		                                        <td style="text-align:center; vertical-align: middle;" nowrap> <!-- ITM_PRICE -->
		                                          	<input type="text" name="ITM_PRICE<?php echo $currentRow; ?>" id="ITM_PRICE<?php echo $currentRow; ?>" value="<?php print number_format($ITM_PRICE, $decFormat); ?>" class="form-control" style="text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgPrc(this,<?php echo $currentRow; ?>);" >
													<input type="hidden" name="data[<?php echo $currentRow; ?>][ITM_PRICE]" id="data<?php echo $currentRow; ?>ITM_PRICE" value="<?php print $ITM_PRICE; ?>" class="form-control">
		                                        </td>
		                                        <td style="text-align:left; vertical-align: middle;"> <!-- ITM_KOEF -->
		                                          	<input type="text" name="ITM_KOEF<?php echo $currentRow; ?>" id="ITM_KOEF<?php echo $currentRow; ?>" value="<?php print number_format($ITM_KOEF, $decFormat); ?>" class="form-control" style="text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgKoef(this,<?php echo $currentRow; ?>);" >
													<input type="hidden" name="data[<?php echo $currentRow; ?>][ITM_KOEF]" id="data<?php echo $currentRow; ?>ITM_KOEF" value="<?php print $ITM_KOEF; ?>" class="form-control">
												</td>
		                                        <td style="text-align:left; vertical-align: middle;"> <!-- ITM_TOTAL -->
		                                          	<input type="text" name="ITM_TOTAL<?php echo $currentRow; ?>" id="ITM_TOTAL<?php echo $currentRow; ?>" value="<?php print number_format($ITM_TOTAL, $decFormat); ?>" class="form-control" style="text-align:right" onKeyPress="return isIntOnlyNew(event);" readonly >
													<input type="hidden" name="data[<?php echo $currentRow; ?>][ITM_TOTAL]" id="data<?php echo $currentRow; ?>ITM_TOTAL" value="<?php print $ITM_TOTAL; ?>" class="form-control">
												</td>
	                                  		</tr>
	                                    <?php
	                                    endforeach;
	                                }
	                                ?>
	                                <input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">
	                            </table>
	                        </div>
	                  	</div>
		            </div>
	                <br>
	                <div class="col-md-6">
		                <div class="form-group">
		                    <label for="inputName" class="col-sm-3 control-label">&nbsp;</label>
		                    <div class="col-sm-9">
                                <button class="btn btn-primary" id="btnSave">
                                <i class="fa fa-save"></i></button>&nbsp;
                                <?php
		                            echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>');
		                        ?>
		                    </div>
		                </div>
		            </div>
	            </form>
	        </div>

	    	<!-- ============ START MODAL =============== -->
		    	<style type="text/css">
		    		.modal-dialog{
					    position: relative;
					    display: table; /* This is important */ 
					    overflow-y: auto;    
					    overflow-x: auto;
					    width: auto;
					    min-width: 300px;   
					}
		    	</style>
		    	<?php
					$Active1		= "active";
					$Active2		= "";
					$Active3		= "";
					$Active4		= "";
					$Active5		= "";
					$Active1Cls		= "class='active'";
					$Active2Cls		= "";
					$Active3Cls		= "";
					$Active4Cls		= "";
					$Active5Cls		= "";
		    	?>
		        <div class="modal fade" id="mdl_addItm" name='mdl_addItm' tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		            <div class="modal-dialog">
			            <div class="modal-content">
			                <div class="modal-body">
								<div class="row">
							    	<div class="col-md-12">
						              	<ul class="nav nav-tabs">
						                    <li id="li1" <?php echo $Active1Cls; ?>>
						                    	<a href="#itm1" data-toggle="tab" onClick="setType(1)">Material</a>
						                    </li>
						                    <li id="li2" <?php echo $Active2Cls; ?>>
						                    	<a href="#itm2" data-toggle="tab" onClick="setType(2)">Upah</a>
						                    </li>
						                    <li id="li3" <?php echo $Active3Cls; ?>>
						                    	<a href="#itm3" data-toggle="tab" onClick="setType(3)">Subkon</a>
						                    </li>
						                    <li id="li4" <?php echo $Active4Cls; ?>>
						                    	<a href="#itm4" data-toggle="tab" onClick="setType(4)">Alat</a>
						                    </li>
						                    <li id="li5" <?php echo $Active5Cls; ?>>
						                    	<a href="#itm5" data-toggle="tab" onClick="setType(5)">Lainnya</a>
						                    </li>
						                </ul>
							            <div class="box-body">
							            	<div class="<?php echo $Active1; ?> tab-pane" id="itm1">
		                                        <div class="form-group">
		                                        	<form method="post" name="frmSearch1" id="frmSearch1" action="">
			                                            <table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
			                                                <thead>
			                                                    <tr>
			                                                        <th width="5%">&nbsp;</th>
			                                                        <th width="20%" nowrap><?php echo $ItemCode; ?></th>
			                                                        <th width="45%" nowrap><?php echo $ItemName; ?></th>
			                                                        <th width="5%" nowrap>Unit</th>
			                                                        <th width="5%" nowrap><?php echo "Group"; ?>  </th>
			                                                        <th width="10%" nowrap><?php echo $Price; ?></th>
			                                                        <th width="10%" nowrap><?php echo $Remarks; ?></th>
			                                                  	</tr>
			                                                </thead>
			                                                <tbody>
			                                                </tbody>
			                                            </table>
                                                    	<button class="btn btn-primary" type="button" id="btnDetail1" name="btnDetail1">
                                                    		<i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?>
                                                    	</button>
                                      					<button type="button" id="idClose1" class="btn btn-danger" data-dismiss="modal">
                                                    		<i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>
                                                    	</button>
														<button class="btn btn-warning" type="button" id="idRefresh1" title="Refresh" >
															<i class="glyphicon glyphicon-refresh"></i>
														</button>
		                                            </form>
		                                      	</div>
		                                    </div>
                                      	</div>
                                      	<input type="hidden" name="rowCheck" id="rowCheck" value="0">
                                      	<button type="button" id="idClose" class="btn btn-default" data-dismiss="modal" style="display: none";>Close</button>
	                                </div>
	                            </div>
			                </div>
				        </div>
				    </div>
				</div>

				<script type="text/javascript">
					function setType(tabType)
					{
						if(tabType == 1)
							var ITMCAT 	= 'M';
						else if(tabType == 2)
							var ITMCAT 	= 'U';
						else if(tabType == 3)
							var ITMCAT 	= 'S';
						else if(tabType == 4)
							var ITMCAT 	= 'T';
						else
							var ITMCAT 	= 'O';

						// if(tabType == 1)
						// {
						// 	document.getElementById('itm1').style.display	= '';
						// 	document.getElementById('itm2').style.display	= 'none';
						// }
						// else
						// {
						// 	document.getElementById('itm1').style.display	= 'none';
						// 	document.getElementById('itm2').style.display	= '';
						// }

						$('#example1').DataTable(
				    	{
				    		"destroy": true,
					        "processing": true,
					        "serverSide": true,
							//"scrollX": false,
							"autoWidth": true,
							"filter": true,
					        "ajax": "<?php echo site_url('c_project/c_joblist/get_AllDataITM/?id=')?>"+ITMCAT,
					        "type": "POST",
							//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
							"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
							"columnDefs": [	{ targets: [0,1,3,4], className: 'dt-body-center' },
											{ targets: [5], className: 'dt-body-right' }
										  ],
							"language": {
					            "infoFiltered":"",
					            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
					        },
						});

					}

					$(document).ready(function()
					{
				    	$('#example1').DataTable(
				    	{
					        "processing": true,
					        "serverSide": true,
							//"scrollX": false,
							"autoWidth": true,
							"filter": true,
					        "ajax": "<?php echo site_url('c_project/c_joblist/get_AllDataITM/?id=M')?>",
					        "type": "POST",
							//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
							"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
							"columnDefs": [	{ targets: [0,1,3,4], className: 'dt-body-center' },
											{ targets: [5], className: 'dt-body-right' }
										  ],
							"language": {
					            "infoFiltered":"",
					            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
					        },
						});
					});

					$("#idRefresh1").click(function()
					{
						$('#example1').DataTable().ajax.reload();
					});

					var selectedRows = 0;
					function pickThis1(thisobj) 
					{
						var favorite = [];
						$.each($("input[name='chk1']:checked"), function() {
					      	favorite.push($(this).val());
					    });
					    $("#rowCheck").val(favorite.length);
					}

					$(document).ready(function()
					{
					   	$("#btnDetail1").click(function()
					    {
							var totChck 	= $("#rowCheck").val();
							if(totChck == 0)
							{
								swal('<?php echo $alert3; ?>',
								{
									icon: "warning",
								})
								.then(function()
					            {
					                swal.close();
					            });
								return false;
							}
							console.log('1 = '+totChck);
						    $.each($("input[name='chk1']:checked"), function()
						    {
								console.log('2 = '+$(this).val());
						      	add_item($(this).val());
						    });

						    $('#mdl_addItm').on('hidden.bs.modal', function () {
							    $(this)
								    .find("input,textarea,select")
									    // .val('')
									    .end()
								    .find("input[type=checkbox], input[type=radio]")
								       .prop("checked", "")
								       .end();
							});
                        	document.getElementById("idClose").click()
					    });
					});
				</script>
	    	<!-- ============ END MODAL =============== -->

        	<?php
				$act_lnk = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        		if($DefEmp_ID == 'D15040004221')
                	echo "<font size='1'><i>$act_lnk</i></font>";
            ?>
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
		$.fn.datepicker.defaults.format = "dd/mm/yyyy";
	    $('#datepicker').datepicker({
	      autoclose: true,
		  endDate: '+1d'
	    });
		
		//Date picker
		$('#datepicker1').datepicker({
		  autoclose: true,
		  startDate: '+0d'
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

	function add_item(strItem) 
	{
		arrItem = strItem.split('|');
		var objTable, objTR, objTD, intIndex, arrItem;
		var MAN_NUMX 	= "<?php echo $MAN_NUM; ?>";
		ilvl = arrItem[1];
		
		/*validateDouble(arrItem[0],arrItem[1])
		if(validateDouble(arrItem[0],arrItem[1]))
		{
			swal("Double Item for " + arrItem[0]);
			return;
		}*/

		ITM_CODE 		= arrItem[0];
		ITM_NAME 		= arrItem[1];
		ITM_UNIT 		= arrItem[2];
		ITM_GROUP 		= arrItem[3];
		ITM_VOLM 		= arrItem[4];
		ITM_PRICE 		= arrItem[5];

		objTable 		= document.getElementById('tbl');
		intTable 		= objTable.rows.length;
		//swal('intTable = '+intTable)
		//intIndex = parseInt(document.frm.rowCount.value) + 1;
		intIndex = parseInt(objTable.rows.length);
		//intIndex = intTable;
		document.frm.rowCount.value = intIndex;
		
		objTR = objTable.insertRow(intTable);
		objTR.id = 'tr_' + intIndex;
		
		// Checkbox
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = "center";
			objTD.noWrap = true;
			objTD.innerHTML = '<a href="#" onClick="deleteRow('+intIndex+')" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a>';
		
		// ITM_CODE
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = 'right';
			objTD.innerHTML = ''+ITM_CODE+'<input type="hidden" id="data'+intIndex+'ITM_CODE" name="data['+intIndex+'][ITM_CODE]" value="'+ITM_CODE+'" class="form-control" style="max-width:300px;"><input type="hidden" style="text-align:right" name="data['+intIndex+'][ITM_GROUP]" id="ITM_GROUP'+intIndex+'" value="'+ITM_GROUP+'" >';
		
		// ITM_NAME
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = 'left';
			objTD.noWrap = true;
			objTD.innerHTML = ''+ITM_NAME+'<input type="hidden" style="text-align:right" name="data['+intIndex+'][ITM_NAME]" id="ITM_NAME'+intIndex+'" value="'+ITM_NAME+'" >';
		
		// ITM_QTY
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = '<input type="text" name="ITM_QTY'+intIndex+'" id="ITM_QTY'+intIndex+'" value="1.00" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgQty(this,'+intIndex+');" readonly><input type="hidden" name="data['+intIndex+'][ITM_QTY]" id="data'+intIndex+'ITM_QTY" value="1.00" class="form-control" style="max-width:300px;" >';
		
		// ITM_UNIT
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = ''+ITM_UNIT+'<input type="hidden" class="form-control" style="min-width:100px; max-width:300px; text-align:right" name="ITM_UNIT'+intIndex+'" id="ITM_UNIT'+intIndex+'" value="'+ITM_UNIT+'" disabled ><input type="hidden" style="text-align:right" name="data['+intIndex+'][ITM_UNIT]" id="ITM_UNIT'+intIndex+'" value="'+ITM_UNIT+'" >';
		
		// ITM_PRICE
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = '<input type="text" name="ITM_PRICE'+intIndex+'" id="ITM_PRICE'+intIndex+'" value="'+ITM_PRICE+'" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgPrc(this,'+intIndex+');" ><input type="hidden" name="data['+intIndex+'][ITM_PRICE]" id="data'+intIndex+'ITM_PRICE" value="'+ITM_PRICE+'" class="form-control" style="max-width:300px;" >';
		
		// ITM_KOEF
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = '<input type="text" name="ITM_KOEF'+intIndex+'" id="ITM_KOEF'+intIndex+'" value="0.00" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgKoef(this,'+intIndex+');" ><input type="hidden" name="data['+intIndex+'][ITM_KOEF]" id="data'+intIndex+'ITM_KOEF" value="0.00" class="form-control" style="max-width:300px;" >';
		
		// ITM_TOTAL
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'center';
			objTD.innerHTML = '<input type="text" name="ITM_TOTAL'+intIndex+'" id="ITM_TOTAL'+intIndex+'" value="0.00" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,'+intIndex+');" readonly><input type="hidden" name="data['+intIndex+'][ITM_TOTAL]" id="data'+intIndex+'ITM_TOTAL" value="0.00" class="form-control" style="max-width:300px;" >';
		
		console.log('c')
		document.getElementById('ITM_PRICE'+intIndex).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_PRICE)), 2));
		document.getElementById('totalrow').value = intIndex;
		console.log('d')
	}
	
	function deleteRow(btn)
	{
		var row = document.getElementById("tr_" + btn);
		row.remove();
	}
	
	function chgQty(thisVal1, row)
	{
		thisVal 			= eval(thisVal1).value.split(",").join("");
		document.getElementById('data'+row+'ITM_QTY').value 	= thisVal;
		document.getElementById('ITM_QTY'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(thisVal)), 2));

		ITM_PRICE 	= document.getElementById('data'+row+'ITM_PRICE').value
		ITM_TOTAL 	= parseFloat(thisVal) * parseFloat(ITM_PRICE);
		document.getElementById('data'+row+'ITM_TOTAL').value 	= ITM_TOTAL;
		document.getElementById('ITM_TOTAL'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_TOTAL)), 2));
	}
	
	function chgPrc(thisVal1, row)
	{
		thisVal 			= eval(thisVal1).value.split(",").join("");
		document.getElementById('data'+row+'ITM_PRICE').value 	= thisVal;
		document.getElementById('ITM_PRICE'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(thisVal)), 2));

		ITM_QTY 	= document.getElementById('data'+row+'ITM_QTY').value
		ITM_TOTAL 	= parseFloat(thisVal) * parseFloat(ITM_QTY);
		document.getElementById('data'+row+'ITM_TOTAL').value 	= ITM_TOTAL;
		document.getElementById('ITM_TOTAL'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_TOTAL)), 2));
	}
	
	function chgKoef(thisVal1, row)
	{
		thisVal 			= eval(thisVal1).value.split(",").join("");
		document.getElementById('data'+row+'ITM_KOEF').value 	= thisVal;
		document.getElementById('ITM_KOEF'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(thisVal)), 4));

		// VOLUME INDUK DIANGGA= = 1
		ITMVOLM 	= parseFloat(thisVal) * parseFloat(1);
		document.getElementById('data'+row+'ITM_QTY').value 	= ITMVOLM;
		document.getElementById('ITM_QTY'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITMVOLM)), 2));

		ITM_PRICE 	= document.getElementById('data'+row+'ITM_PRICE').value
		ITM_TOTAL 	= parseFloat(ITMVOLM) * parseFloat(ITM_PRICE);
		document.getElementById('data'+row+'ITM_TOTAL').value 	= ITM_TOTAL;
		document.getElementById('ITM_TOTAL'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_TOTAL)), 2));
	}
	
	function checkForm(value)
	{
		var totrow 		= document.getElementById('totalrow').value;
		var MAN_CODE 	= document.getElementById('MAN_CODE').value;
		var MAN_NAME 	= document.getElementById('MAN_NAME').value;

		if(MAN_CODE == "")
		{
			swal('<?php echo $alert1; ?>',
			{
				icon: "warning",
			})
			.then(function()
            {
                swal.close();
                $('#MAN_CODE').focus();
            });
			return false;
		}

		if(MAN_NAME == "")
		{
			swal('<?php echo $alert2; ?>',
			{
				icon: "warning",
			})
			.then(function()
            {
                swal.close();
                $('#MAN_NAME').focus();
            });
			return false;
		}

		if(totrow == 0)
		{
			swal('<?php echo $alert3; ?>',
			{
				icon: "warning",
			});
			return false;		
		}
		else
		{
			/*for(i=1;i<=totrow;i++)
			{
				ITM_QTY = parseFloat(document.getElementById('data'+row+'ITM_QTY').value);
				if(ITM_QTY == 0)
				{
					swal('<?php echo $inpMRQTY; ?>',
					{
						icon: "warning",
					})
					.then(function()
		            {
		                swal.close();
						document.getElementById('PR_VOLM'+i).value = '0';
		                $('#PR_VOLM'+i).focus();
		            });
					return false;
				}
			}*/
		}
	}
	
	function isIntOnlyNew(evt)
	{
		if (evt.which){ var charCode = evt.which; }
		else if(document.all && event.keyCode){ var charCode = event.keyCode; }
		else { return true; }
		return ((charCode == 45) || (charCode == 46) || (charCode == 8) || (charCode >= 48) && (charCode <= 57));
	}
  
	function doDecimalFormat(angka) {
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

	function decimalin(ini)
	{
		var i, j;
		var bil2 = deletecommaperiod(ini.value,'both')
		var bil3 = ""
		j = 0
		for (i=bil2.length-1;i>=0;i--)
		{
			j = j + 1;
			if (j == 3)
			{
				bil3 = "." + bil3
			}
			else if ((j >= 6) && ((j % 3) == 0))
			{
				bil3 = "," + bil3
			}
			bil3 = bil2.charAt(i) + "" + bil3
		}
		ini.value = bil3
	}
	
	function RoundNDecimal(X, N)
	{
		var T, S=new String(Math.round(X*Number("1e"+N)))
		while (S.length<=N) S='0'+S
		return S.substr(0, T=(S.length-N)) + '.' + S.substr(T, N)
	}
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
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