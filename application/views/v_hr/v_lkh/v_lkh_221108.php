<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 25 November 2017
	* File Name	= v_vendor.php
	* Location		= -
*/
// $this->load->view('template/head');

date_default_timezone_set("Asia/Jakarta");

$appName 		= $this->session->userdata('appName');
$appBody 		= $this->session->userdata['appBody'];
$FlagUSER 		= $this->session->userdata['FlagUSER'];
$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
$PATTEMP 		= substr($DefEmp_ID,-4);
$DWR_NUM 		= "LKH".".".date('ymdHis');
$DWR_CODE 		= "LKH.".date('ymd').".".date('His');
$DWR_DATE 		= date('Y-m-d');
$DWR_DATED 		= date('d/m/Y');
$DWR_CATEG 		= 0;
$Start_Date 	= date('Y-m-d');
$End_Date 		= date('Y-m-d');
//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
    $Display_Rows = $row->Display_Rows;
    $decFormat = $row->decFormat;
endforeach;
if($decFormat == 0)
    $decFormat    = 2;

$s_app 	= "SELECT app_stat FROM tappname";
$r_app 	= $this->db->query($s_app)->result();
foreach($r_app as $rw_app) :
	$app_stat = $rw_app->app_stat;		
endforeach;

$img_filenameX 	= "";
$sqlGetIMG		= "SELECT imgemp_filename, imgemp_filenameX FROM tbl_employee_img WHERE imgemp_empid = '$DefEmp_ID'";
$resGetIMG 		= $this->db->query($sqlGetIMG)->result();
foreach($resGetIMG as $rowGIMG) :
	$imgemp_filename 	= $rowGIMG ->imgemp_filename;
	$img_filenameX 		= $rowGIMG ->imgemp_filenameX;
endforeach;
$imgLoc				= base_url('assets/AdminLTE-2.0.5/emp_image/'.$DefEmp_ID.'/'.$img_filenameX);
if (!file_exists('assets/AdminLTE-2.0.5/emp_image/'.$DefEmp_ID))
{
	$imgLoc			= base_url('assets/AdminLTE-2.0.5/emp_image/username.jpg');
}
$urlLKHVPD 			= base_url().'index.php/__l1y/vwDWRPD/?id=';		// LKH View Per Day

$empENC 			= $this->url_encryption_helper->encode_url($DefEmp_ID);
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

	        $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'css' AND isAct IN (1,3,4) AND cssjs_vers IN ('$vers', 'All')";
	        $rescss = $this->db->query($sqlcss)->result();
	        foreach($rescss as $rowcss) :
	            $cssjs_lnk  = $rowcss->cssjs_lnk;
	            ?>
	                <link rel="stylesheet" href="<?php echo base_url($cssjs_lnk) ?>">
	            <?php
	        endforeach;

	        $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'jss' AND isAct IN (1,3,4) AND cssjs_vers IN ('$vers', 'All')";
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
    
	<style>
        .search-table, td, th {
            border-collapse: collapse;
        }
        .search-table-outter { overflow-x: scroll; }

		[aria-current="disabled"] {
			pointer-events: none;
			cursor: no-drop;
			text-decoration: none;
		}
    </style>

	<?php
		// Top Bar
  			$this->load->view('template/mna');
			//______$this->load->view('template/topbar');

		// Left side column. contains the logo and sidebar
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
			if($TranslCode == 'Name')$Name = $LangTransl;
			if($TranslCode == 'Address')$Address = $LangTransl;
			if($TranslCode == 'City')$City = $LangTransl;
			if($TranslCode == 'Phone')$Phone = $LangTransl;
			if($TranslCode == 'SupplierList')$SupplierList = $LangTransl;
			if($TranslCode == 'Project')$Project = $LangTransl;
			if($TranslCode == 'Scope')$Scope = $LangTransl;
			if($TranslCode == 'sureNonAct')$sureNonAct = $LangTransl;
			if($TranslCode == 'sureDAct')$sureDAct = $LangTransl;
			if($TranslCode == 'dataDisabl')$dataDisabl = $LangTransl;
			if($TranslCode == 'dataAct')$dataAct = $LangTransl;
			if($TranslCode == 'yesDel')$yesDel = $LangTransl;
			if($TranslCode == 'cancDel')$cancDel = $LangTransl;
			if($TranslCode == 'sureDelete')$sureDelete = $LangTransl;
		endforeach;

        $comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
			<h1><img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/joborder.png'; ?>" style="max-width:40px; max-height:40px" > <?php echo $mnName; ?> </h1>
		</section>
		<style>
			.search-table, td, th {
				border-collapse: collapse;
			}
			.search-table-outter { overflow-x: scroll; }
		</style>

	    <section class="content">
	    	<form class="form-horizontal" name="frm" method="post" action="" enctype="multipart/form-data" onSubmit="return validateInData()">
	    		<div class="row">
                    <div class="col-md-4">
                        <div class="box box-success">
                            <div class="box-header with-border">
                                <h3 class="box-title"><i class="fa fa-user"></i> Biodata Karyawan</h3>
                            </div>
                            <div class="box-body">
				                <div class="row">
				                	<?php
			                    		$compName 	= "-";
										$POSS_NAME 	= "-";
										$POSS_NAMEP	= "-";
										$EMP_NAMEP	= "-";
										$Emp_IDP 	= "-";
										$EMP_NAMEP 	= "-";
										$EMP_BPlcP	= "-";
										$EMP_BDatP	= "-";
										$PosCode 	= "";
			                    		$s_Empl		= "SELECT CONCAT(First_Name,' ', Last_Name) AS compName, Birth_Place, Date_Of_Birth, Pos_Code
			                    						FROM tbl_employee
			                    						WHERE Emp_ID = '$DefEmp_ID' LIMIT 1";
										$r_Empl 	= $this->db->query($s_Empl)->result();
										foreach($r_Empl as $rw_Empl) :
											$compName 	= $rw_Empl->compName;
											$bPlace 	= $rw_Empl->Birth_Place;
											$bDate 		= strftime('%d %b %Y', strtotime($rw_Empl->Date_Of_Birth));
											$PosCode 	= $rw_Empl->Pos_Code;

											$s_possC	= "SELECT POSS_NAME, POSS_DESC, POSS_PARENT FROM tbl_position_str WHERE POSS_CODE = '$PosCode' LIMIT 1";
											$r_possC 	= $this->db->query($s_possC)->result();
											foreach($r_possC as $rw_possC) :
												$POSS_NAME 	= $rw_possC->POSS_NAME;
												$POSS_DESC 	= $rw_possC->POSS_DESC;
												$POSS_CODEP	= $rw_possC->POSS_PARENT;
		
												$s_possPC	= "SELECT POSS_NAME, POSS_DESC FROM tbl_position_str WHERE POSS_CODE = '$POSS_CODEP' LIMIT 1";
												$r_possPC 	= $this->db->query($s_possPC)->result();
												foreach($r_possPC as $rw_possPC) :
													$POSS_NAMEP	= $rw_possPC->POSS_NAME;
													$POSS_DESCP	= $rw_possPC->POSS_DESC;
												endforeach;

												$s_EmplP	= "SELECT Emp_ID, CONCAT(First_Name,' ', Last_Name) AS compName, Birth_Place, Date_Of_Birth, Pos_Code
					                    						FROM tbl_employee
					                    						WHERE Pos_Code = '$POSS_CODEP' LIMIT 1";
												$r_EmplP 	= $this->db->query($s_EmplP)->result();
												foreach($r_EmplP as $rw_EmplP) :
													$Emp_IDP 	= $rw_EmplP->Emp_ID;
													$EMP_NAMEP 	= $rw_EmplP->compName;
													$EMP_BPlcP	= $rw_EmplP->Birth_Place;
													$EMP_BDatP	= strftime('%d %b %Y', strtotime($rw_EmplP->Date_Of_Birth));
												endforeach;
											endforeach;
										endforeach;
				                	?>
					                <div class="col-md-5" style="text-align: center;">
					                  	<img class="profile-user-img img-responsive img-circle" src="<?php echo $imgLoc; ?>" alt="User profile picture">
					                </div>

					                <div class="col-md-7">
					                  	<div class="row">
					                    	<div class="col-md-12">
					                    		<?php echo "<strong>Nama Lengkap</strong>"; ?>
					                    	</div>
					                    	<div class="col-md-12" style="font-style: italic;">
					                    		<?php echo "$compName"; ?>
					                    	</div>
					                    	<div class="col-md-12">
					                    		<?php echo "<strong>TTL</strong>"; ?>
					                    	</div>
					                    	<div class="col-md-12" style="font-style: italic;">
					                    		<?php echo "$bPlace, $bDate"; ?>
					                    	</div>
					                    	<div class="col-md-5">
					                    		<?php echo "<strong>Posisi</strong>"; ?>
					                    	</div>
					                    	<div class="col-md-12" style="font-style: italic;">
					                    		<?php echo "$POSS_NAME"; ?>
					                    	</div>
				                    	</div>
				                    </div>
					            </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title"><i class="fa fa-area-chart"></i> Performance</h3>
                            </div>
                            <div class="box-body">
				            	<div class="row">
			                    	<div class="col-md-6">
			                    		<?php echo "<strong>Hari Kerja</strong>"; ?><font style="font-style: italic;"> (Rencana)</font>
			                    	</div>
			                    	<div class="col-md-6">
			                    		<?php echo "<strong>Masuk Kerja</strong>"; ?><font style="font-style: italic;"> (Terpenuhi)</font>
			                    	</div>
			                    	<div class="col-md-6" style="font-style: italic;">
			                    		<?php echo "20 Hari"; ?>
			                    	</div>
			                    	<div class="col-md-6" style="font-style: italic;">
			                    		<?php echo "20 Hari"; ?>
			                    	</div>
			                    	<div class="col-md-6">
			                    		<?php echo "<strong>Jam Kerja</strong>"; ?><font style="font-style: italic;"> (Rencana)</font>
			                    	</div>
			                    	<div class="col-md-6">
			                    		<?php echo "<strong>Jam Kerja</strong>"; ?><font style="font-style: italic;"> (Terpenuhi)</font>
			                    	</div>
			                    	<div class="col-md-6" style="font-style: italic;">
			                    		<?php echo "160 Jam"; ?>
			                    	</div>
			                    	<div class="col-md-6" style="font-style: italic;">
			                    		<?php echo "160 Jam"; ?>
			                    	</div>
			                    	<div class="col-md-1">
			                    		<?php echo "<strong>Pesan</strong>"; ?>
			                    	</div>
			                    	<div class="col-md-12" style="font-style: italic;">
			                    		<?php echo "-"; ?>
			                    	</div>
		                    	</div>
				            </div>
				        </div>
				    </div>
                    <div class="col-md-3">
                        <div class="box box-warning">
                            <div class="box-header with-border">
                                <h3 class="box-title"><i class="fa fa-users"></i> Atasan Langsung</h3>
                            </div>
                            <div class="box-body">
			                  	<div class="row">
			                    	<div class="col-md-12">
			                    		<?php echo "<strong>Nama Lengkap</strong>"; ?>
			                    	</div>
			                    	<div class="col-md-12" style="font-style: italic;">
			                    		<?php echo "$EMP_NAMEP"; ?>
			                    	</div>
			                    	<div class="col-md-12" style="font-style: italic;">
			                    		<?php echo "$Emp_IDP"; ?>
			                    	</div>
			                    	<div class="col-md-12" style="font-style: italic;">
			                    		<?php echo "$EMP_BPlcP, $EMP_BDatP"; ?>
			                    	</div>
			                    	<div class="col-md-12">
			                    		<?php echo "<strong>Jabatan Struktural</strong>"; ?>
			                    	</div>
			                    	<div class="col-md-12" style="font-style: italic;">
			                    		<?php echo "$POSS_NAMEP"; ?>
			                    	</div>
		                    	</div>
				            </div>
				        </div>
				    </div>
                </div>
				<div class="box">
					<div class="box-body">
						<div class="search-table-outter">
					      	<table id="list_lkh" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
						        <thead>
						            <tr>
						                <th width="2%">&nbsp;</th>
						              	<th width="10%" style="text-align:center; vertical-align:middle" nowrap>No. Register</th>
						       	  	  	<th width="10%" style="text-align:center; vertical-align:middle" nowrap>Waktu</th>
						           	  	<th width="5%" style="text-align:center; vertical-align:middle" nowrap>Kategori</th>
						           	  	<th width="63%" style="text-align:center; vertical-align:middle" nowrap>Deskripsi</th>
						           	  	<th width="5%" style="text-align:center; vertical-align:middle" nowrap>&nbsp;</th>
						           	  	<th width="5%" style="text-align:center; vertical-align:middle" nowrap>Aksi</th>
						   	      	</tr>
						        </thead>
						        <tbody>
						        </tbody>
								<input type="hidden" name="totalrow" id="totalrow" value="0">
						        <tfoot>
						        </tfoot>
					   		</table>
					    </div>
					    <br>
						<?php
							if($ISCREATE == 1)
							{
								echo anchor("$secAddURL",'<button class="btn btn-primary">&nbsp;<i class="glyphicon glyphicon-plus"></i></button>');
							}
						?>
					</div>
				</div>
	        	<?php
	        		$DefID 		= $this->session->userdata['Emp_ID'];
					$act_lnk 	= "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	        		if($DefID == 'D15040004221')
	                	echo "<font size='1'><i>$act_lnk</i></font>";
	            ?>
	        </form>

	    	<!-- ============ START MODAL ADD LKH =============== -->
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
					$Active1Cls		= "class='active'";
					$Active2Cls		= "";
					$Active3Cls		= "";
					$Active4Cls		= "";

					$DWR_DATE 		= date('Y-m-d');
					$DWR_DATEV		= strftime('%d %B %Y', strtotime($DWR_DATE));
		    	?>
	    		<div class="modal fade" id="mdl_delItm" name='mdl_delItm' role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		            <div class="modal-dialog">
			            <div class="modal-content">
			                <div class="modal-body">
								<div class="row">
							    	<div class="col-md-12">
						              	<ul class="nav nav-tabs">
						                    <li id="li1" <?php echo $Active1Cls; ?>>
						                    	<a href="#itm1" data-toggle="tab" >Tambah Time Sheet</a>
						                    </li>	
						                </ul>
							            <div class="box-body">
							            	<div class="<?php echo $Active1; ?> tab-pane" id="itm1">
		                                        <div class="form-group">
			                                    	<form method="post" name="frmTS" id="frmTS" action="">
								                    	<div class="row">
									                    	<div class="col-md-12">
									                    		<?php echo "<strong>No. Registrasi</strong>"; ?>
									                    	</div>
								                    	</div>
								                    	<div class="row">
									                    	<div class="col-md-8" style="white-space: nowrap;">
									                    		<input type="hidden" name="DWR_TASK" id="DWR_TASK" value="add" class="form-control">
									                    		<input type="hidden" name="DWR_ID" id="DWR_ID" value="" class="form-control">
																<input type="hidden" name="DWR_NUM" id="DWR_NUM" value="<?=$DWR_NUM?>" class="form-control">
									                    		<input type="text" name="DWR_CODE" id="DWR_CODE" value="<?=$DWR_CODE?>" class="form-control" readonly>
									                    	</div>
									                    	<div class="col-md-4" style="white-space: nowrap;">
									                    		<select name="DWR_CATEG" id="DWR_CATEG" class="form-control select2" style="width: 100%">
						                                            <option value="0"<?php if($DWR_CATEG == 0) { ?> selected <?php } ?>>--</option>
						                                            <option value="1"<?php if($DWR_CATEG == 1) { ?> selected <?php } ?>>Break Time</option>
								                                </select>
									                    	</div>
								                    	</div>
								                    	<br>
								                    	<div class="row">
									                    	<div class="col-md-4">
									                    		<?php echo "<strong>Tanggal</strong>"; ?>
									                    	</div>
									                    	<div class="col-md-4">
									                    		<?php echo "<strong>Mulai</strong>"; ?>
									                    	</div>
									                    	<div class="col-md-4">
									                    		<?php echo "<strong>Selesai</strong>"; ?>
									                    	</div>
								                    	</div>
								                    	<div class="row">
									                    	<div class="col-md-4" style="white-space: nowrap;">
									                    		<?php //echo $DWR_DATEV; ?>
									                    		<input type="text" name="DWR_DATE" class="form-control pull-left" id="datepicker1" value="<?php echo $DWR_DATED; ?>" readonly>
									                    	</div>
									                    	<div class="col-md-4" style="white-space: nowrap;">
									                    		<div class="input-group">
												                    <input type="text" name="DWR_DATES" id="DWR_DATES" class="form-control timepicker">
												                    <div class="input-group-addon">
												                      <i class="fa fa-clock-o"></i>
												                    </div>
												                </div>
									                    	</div>
									                    	<div class="col-md-4" style="white-space: nowrap;">
									                    		<div class="input-group">
												                    <input type="text" name="DWR_DATEE" id="DWR_DATEE" class="form-control timepicker">
												                    <div class="input-group-addon">
												                      <i class="fa fa-clock-o"></i>
												                    </div>
												                </div>
									                    	</div>
								                    	</div>
								                    	<br>
								                    	<div class="row">
									                    	<div class="col-md-12">
									                    		<?php echo "<strong>Deskripsi Pekerjaan</strong>"; ?>
									                    	</div>
								                    	</div>
								                    	<div class="row">
									                    	<div class="col-md-12" style="white-space: nowrap;">
									                    		<textarea name="DWR_NOTES" class="form-control" id="DWR_NOTES" cols="30" placeholder="&nbsp;Catatan pekerjaan yang dilakukan"></textarea>  
									                    	</div>
								                    	</div>
								                    	<br>
									                  	<div class="row">
									                    	<div class="col-md-6">
																<button type="button" id="btn-input" class="btn btn-warning" onClick="proc_inp()"><i class="fa fa-save"></i></button>
																<button type="button" id="idCloseDRow" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-remove"></i></button>
									                    	</div>
								                    	</div>
			                                        </form>
			                                    </div>
			                                </div>
                                      	</div>
	                                </div>
	                            </div>
			                </div>
				        </div>
				    </div>
				</div>
	    	<!-- ============ END MODAL CANCEL ITEM =============== -->
    	</section>
	</body>
</html>

<script>
	$(document).ready(function()
	{
	    $('#list_lkh').DataTable(
    	{
			"dom": "<'row'<'col-sm-2'l><'col-sm-8'<'toolbar'>><'col-sm-2'f>>"+
					"<'row'<'col-sm-12'tr>>",
	        "processing": true,
	        "serverSide": true,
			//"scrollX": false,
			"autoWidth": true,
			"filter": true,
	        "ajax": {
				        "url": "<?php echo site_url('__l1y/get_AllDailyWR/?id='.$DefEmp_ID.'&Start_Date='.$Start_Date.'&End_Date='.$End_Date)?>",
				        "type": "POST",
						"data": function(data) {
							data.DWR_DATEX 	= $('#datepicker').val();
							data.DWR_EMPIDX = $('#DWR_EMPID').val();
						},
			        },
	        // "type": "POST",
			"lengthMenu": [[5, 10, 25, 50, 100, 200, -1], [5, 10, 25, 50, 100, 200, "All"]],
			"pageLength": -1,
			"columnDefs": [	{ targets: [0,3,5], className: 'dt-body-center' }
						  ],
        	"order": [[ 2, "desc" ]],
			"drawCallback": function( settings ) {
				var api 	= this.api();
				var LnRow 	= api.rows( {page:'current'} ).data().length;
				$('#totalrow').val(LnRow);
			},
			"language": {
	            "infoFiltered":"",
	            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
	        }
		});
		
		$('div.toolbar').html('<form id="form-filter" class="form-horizontal">'+
							  '<div class="input-group date">'+
							  '<div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>'+
							  '<input type="text" name="DWR_DATED" class="form-control pull-left" id="datepicker" value="" style="width:170px">'+
							  '<input type="hidden" name="DWR_EMPIDX" class="form-control" id="DWR_EMPIDX" value="">'+
							  '</div>&nbsp;'+
							  '<select class="form-control select2" name="DWR_EMPID" id="DWR_EMPID" data-placeholder="Karyawan" style="width: 190px;">'+
							  '<option value=""></option></select>&nbsp;'+
							  '<button type="button" id="btn-filter" class="btn btn-success"><i class="fa fa-filter"></i>&nbsp;Filter</button>&nbsp;'+
							  '<button type="button" id="btn-reset" class="btn btn-warning"><i class="fa fa-refresh"></i>&nbsp;Reset</button>&nbsp;'+
							  '<button type="button" id="btn-print" class="btn btn-info"><i class="fa fa-print"></i>&nbsp;Cetak</button>&nbsp;'+
							  '<button type="button" id="btn-form" class="btn btn-primary" data-toggle="modal" data-target="#mdl_delItm"><i class="glyphicon glyphicon-plus"></i></button>'+
							  '</form>');

		var POSS_CODE 	= "<?=$PosCode?>";
		var EmpID 		= "<?php echo $DefEmp_ID; ?>";
		$.ajax({
			url: "<?php echo site_url('__l1y/get_AllEMP'); ?>",
			type: "POST",
			dataType: "JSON",
			data: {POSS_CODE:POSS_CODE},
			success: function(result) {
				console.log(result.length);
				var selected 	= "";
				var DWR_EMPID = "<option value=''></option>";
				for(let i in result) {
					Emp_ID 		= result[i]['Emp_ID'];
					Emp_Nm 		= result[i]['compName'];
					DWR_EMPID 	+= '<option value="'+Emp_ID+'">'+Emp_Nm+'</option>';
				}
				$('#DWR_EMPID').html(DWR_EMPID);
				$('#DWR_EMPID').val(EmpID).trigger('change');
			}
		});

		$('#DWR_EMPID').change(function(e){
			$.post({
				url: "<?php echo site_url('__l1y/getEncryptURL');?>",
				dataType: "JSON",
				data: {id:$(this).val()}
			}).done((result) => $('#DWR_EMPIDX').val(result));
		});


		$('#btn-filter').bind('click', function(){
			$('#list_lkh').DataTable().ajax.reload();
		});

		$('#btn-reset').click(function(){
			$('#DWR_EMPID').val('<?php echo $DefEmp_ID; ?>').change()
			$('#list_lkh').DataTable().ajax.reload();
		});

		$('#btn-print').click(function(){
			DWR_DATE	= document.getElementById('datepicker').value;
			DWR_EMPID	= document.getElementById('DWR_EMPID').value;
			DWR_EMPIDX	= document.getElementById('DWR_EMPIDX').value;
			if(DWR_EMPID == '')
			{
				swal('Silahkan pilih salah satu karyawan',
	            {
	                icon:"warning"
	            });
				return false;
			}
			// url 		= "<?php // echo $urlLKHVPD.$empENC; ?>"+"&DWRDATE="+DWR_DATE;
			url 		= "<?php echo $urlLKHVPD; ?>"+DWR_EMPIDX+"&DWRDATE="+DWR_DATE;
			title = 'Select Item';
			w = 1200;
			h = 550;
			var left = (screen.width/2)-(w/2);
			var top = (screen.height/2)-(h/2);
			window.open(url, 'formpopup', 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
			form.target = 'formpopup';
		});
	} );

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
	    // $('#datepicker').datepicker({
	    //   autoclose: true,
		//   //startDate: '-0d'
	    // });

		$('#datepicker').daterangepicker(
			{
				locale: {
					format: 'DD/MM/YYYY'
				},
				ranges: {
					'Today': [moment(), moment()],
					'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
					'Last 7 Days': [moment().subtract(6, 'days'), moment()],
					'Last 30 Days': [moment().subtract(29, 'days'), moment()],
					'This Month': [moment().startOf('month'), moment().endOf('month')],
					'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
					'This Year': [moment().startOf('year'), moment().endOf('year')],
					'Last Year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')],
					// 'Last Transaction': [startTRX.startOf('month'), moment().subtract(1, 'month').endOf('month')],
					// 'All Periode': [startTRX.startOf('month'), moment()]
				},
				startDate: moment(),
				endDate: moment()
			},
			function (start, end) {
				$('#datepicker span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY')); // Today Default
			}
		);
		
		//Date picker
		// $('#datepicker1').datepicker({
		//   autoclose: true,
		//   startDate: '-0d',
		//   endDate: '+0d'
		// });
		
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
			showInputs: false,
			showMeridian: false,
			ttimeFormat: 'HH:mm:ss'
		});

		setInterval(() => expLKH(), 1000);
	});

	function expLKH()
	{
		let dt		= new Date();
		let H		= ("0"+dt.getHours()).slice(-2);
		let M		= ("0"+dt.getMinutes()).slice(-2);
		let S 		= ("0"+dt.getSeconds()).slice(-2);
		let time	= H+':'+M+':'+S;
		
		console.log(time);
		
		if(time >= '23:00:00' && time <= '00:00:00')
		{
			$('#btn-form').prop('disabled', true);
		}
		else
		{
			$('#btn-form').prop('disabled', false);
		}
	}

	function proc_inp()
	{
		var DWR_TASK	= document.getElementById('DWR_TASK').value;
		var DWR_ID		= document.getElementById('DWR_ID').value;
		var DWR_NUM		= document.getElementById('DWR_NUM').value;
		var DWR_CODE	= document.getElementById('DWR_CODE').value;
		var DWR_DATE	= document.getElementById('datepicker1').value;
		var DWR_DATES	= document.getElementById('DWR_DATES').value;
		var DWR_DATEE	= document.getElementById('DWR_DATEE').value;
		var DWR_CATEG	= document.getElementById('DWR_CATEG').value;
		var DWR_NOTES	= document.getElementById('DWR_NOTES').value;

		// if(DWR_DATE == '')
		// {
		// 	swal("Tanggal tidak boleh kosong").then(()=> $('#datepicker1').focus());
		// 	return false;
		// }

		if(DWR_NOTES == '')
		{
			swal("Catatan tidak boleh kosong").then(()=> $('#DWR_NOTES').focus());
			return false;
		}

		var formData 	= {
							DWR_TASK 		: DWR_TASK,
							DWR_ID 			: DWR_ID,
							DWR_NUM			: DWR_NUM,
							DWR_CODE 		: DWR_CODE,
							DWR_DATE		: DWR_DATE,
							DWR_DATES 		: DWR_DATES,
							DWR_DATEE 		: DWR_DATEE,
							DWR_CATEG 		: DWR_CATEG,
							DWR_NOTES 		: DWR_NOTES
						};

		$.ajax({
            type: 'POST',
            url: "<?php echo site_url('__l1y/saveDWR')?>",
            data: formData,
			beforeSend: function() {
				$('#btn-input').prop('disabled', true);
			},
            success: function(response)
            {
				$("#DWR_NOTES").val('');
            	$('#list_lkh').DataTable().ajax.reload();
				$('#btn-input').prop('disabled', false);
            }
        });

	}
	
	function updRow(row)
	{
        document.getElementById('btn-form').click();

        var collID	= document.getElementById('urlUpd'+row).value;
        var myarr 	= collID.split("~");

        var url 	= myarr[0];

        $.ajax({
            type: 'POST',
            url: url,
            data: {collID: collID},
            success: function(response)
            {
            	var myarr 	= response.split("~");

		        var DWR_ID 		= myarr[0];
		        var DWR_CODE 	= myarr[1];
		        var DWR_EMPID 	= myarr[2];
		        var DWR_EMPNM 	= myarr[3];
		        var DWR_DATE 	= myarr[4];
		        var DWR_DATES 	= myarr[5];
		        var DWR_DATEE 	= myarr[6];
		        var DWR_CATEG 	= myarr[7];
		        var DWR_NOTES 	= myarr[8];

		        var date1		= new Date(DWR_DATE);
			    var dd 			= date1.getDate();
			    var mm 			= date1.getMonth() + 1;
			    var yyyy 		= date1.getFullYear();
			    var DWR_DATE	= dd + '/' + mm + '/' + yyyy;

		        var date2		= new Date(DWR_DATES);
			    var hrs2		= date2.getHours();
			    var mnt2		= date2.getMinutes();
			    var TIMES		= hrs2 + ':' + mnt2 + ':' + '00';

		        var date3		= new Date(DWR_DATES);
			    var hrs3		= date3.getHours();
			    var mnt3		= date3.getMinutes();
			    var TIMEE		= hrs3 + ':' + mnt3 + ':' + '00';

		        $("#DWR_TASK").val('edit');
		        $("#DWR_ID").val(DWR_ID);
		        $("#DWR_CODE").val(DWR_CODE);
		        $('#DWR_CATEG').val(DWR_CATEG).trigger('change');
		        // $("#datepicker1").datepicker('setDate', DWR_DATE);
				$("#datepicker1").val(DWR_DATE);
		        $("#DWR_DATES").timepicker('setTime', TIMES);
		        $("#DWR_DATEE").timepicker('setTime', TIMEE);
		        $("#DWR_NOTES").val(DWR_NOTES);
            }
        });
	}
	
	function verRow(row)
	{
        var collID	= document.getElementById('urlVerRow'+row).value;
        var myarr 	= collID.split("~");

        var url 	= myarr[0];

        $.ajax({
            type: 'POST',
            url: url,
            data: {collID: collID},
            success: function(response)
            {
            	/*swal(response, 
				{
					icon: "success",
				});*/
                $('#list_lkh').DataTable().ajax.reload();
            }
        });
	}
	
	function undoRow(row)
	{
        var collID	= document.getElementById('urlUndoRow'+row).value;
        var myarr 	= collID.split("~");

        var url 	= myarr[0];

        $.ajax({
            type: 'POST',
            url: url,
            data: {collID: collID},
            success: function(response)
            {
            	/*swal(response, 
				{
					icon: "success",
				});*/
                $('#list_lkh').DataTable().ajax.reload();
            }
        });
	}
	
	function delRow(row)
	{
	    swal({
            text: "<?php echo $sureDelete; ?>",
            icon: "warning",
            buttons: ["No", "Yes"],
        })
        .then((willDelete) => 
        {
            if (willDelete) 
            {
                var collID	= document.getElementById('urlDel'+row).value;
		        var myarr 	= collID.split("~");

		        var url 	= myarr[0];

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
		                $('#list_lkh').DataTable().ajax.reload();
		            }
		        });
            } 
            else 
            {
                /*swal("<?php echo $cancDel; ?>", 
				{
					icon: "error",
				});*/
            }
        });
	}
</script>
<?php
	$sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'js' AND isAct IN (1,3,4) AND cssjs_vers IN ('$vers', 'All')";
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