<?php
/*  
 * Author		= Dian Hermanto
 * Create Date	= 9 Februari 2018
 * File Name	= v_calendar.php
 * Location		= -
*/ 
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');

$this->load->view('template/topbar');
$this->load->view('template/sidebar');

$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
if($decFormat == 0)
	$decFormat		= 2;

$DefEmp_ID 		= $this->session->userdata['Emp_ID'];

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Testing | Data Tables</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'> 
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/bootstrap/css/bootstrap.min.css'; ?>">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- fullCalendar 2.2.5-->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/fullcalendar/fullcalendar.min.css'; ?>">
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/fullcalendar/fullcalendar.print.css'; ?>" media="print">
    <!-- Theme style -->
   	<link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/AdminLTE.min.css'; ?>">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
    folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/skins/_all-skins.min.css'; ?>">
    
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/select2/select2.min.css';?>">
    
   
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
		if($TranslCode == 'Title')$Title = $LangTransl;
		if($TranslCode == 'Procedure')$Procedure = $LangTransl;
		if($TranslCode == 'Requested')$Requested = $LangTransl;
		if($TranslCode == 'Approved')$Approved = $LangTransl;
		if($TranslCode == 'Awaiting')$Awaiting = $LangTransl;
		if($TranslCode == 'YourBook')$YourBook = $LangTransl;
		if($TranslCode == 'StartDate')$StartDate = $LangTransl;
		if($TranslCode == 'EndDate')$EndDate = $LangTransl;
		if($TranslCode == 'Description')$Description = $LangTransl;
		if($TranslCode == 'Status')$Status = $LangTransl;
		if($TranslCode == 'Vehicle')$Vehicle = $LangTransl;
		if($TranslCode == 'NOPOL')$NOPOL = $LangTransl;
		if($TranslCode == 'Driver')$Driver = $LangTransl;
		if($TranslCode == 'Time')$Time = $LangTransl;
		if($TranslCode == 'Topic')$Topic = $LangTransl;
		if($TranslCode == 'Participants')$Participants = $LangTransl;
		if($TranslCode == 'Qty')$Qty = $LangTransl;
		if($TranslCode == 'None')$None = $LangTransl;
		if($TranslCode == 'Notes')$Notes = $LangTransl;
		if($TranslCode == 'Email')$Email = $LangTransl;
		if($TranslCode == 'Close')$Close = $LangTransl;
	endforeach;
	
	if($LangID == 'IND')
	{
		//$h1_title		= 'Pemesanan';
		//$h2_title		= 'daftar jadwal';
		$MeetingRoom	= 'Pemesanan Ruang Rapat';
		$RestRoom		= 'Pemesanan Kamar';
		$Vehicle		= 'Pemesanan Kendaraan';
		$BookingNow		= 'Pesan Sekarang';
		$Available		= 'Tersedia';
		$EventList		= 'Daftar Kegiatan';
		$RoomList		= 'Daftar Kendaraan';
		$StatusVH		= 'Status Kendaraan';
		$RemoveList		= 'hapus setelah didrag';
		$CreateEvent	= 'Buat Jadwal Baru';
		$UpdateeEvent	= 'Ubah Status';
		$ChooseColor	= 'Pilih Warna';
			
		$alert1			= 'Tanggal/waktu selesai harus lebih besar dari tanggal/waktu mulai.';
		$alert2			= 'Tentukan waktu mulai penggunaan ruang rapat dengan benar.';
		$alert3			= 'Tentukan waktu selesai penggunaan ruang rapat dengan benar.';
		$alert4			= 'Pilih salah satu kendaraan.';
		$alert5			= 'Tuliskan catatan peminjaman kendaraan.';
		$alert6			= 'Format waktu salah.';
		$alert7			= 'Tuliskan topik / tujuan.';
		$alert8			= 'Masukan jumlah penumpang.';
		$alert9			= 'Tuliskan pengaju pemesanan.';
		$alert9			= 'Tuliskan pengaju pemesanan.';
		$alert10		= 'Masukan alamat email.';
		$alert11		= 'Silahkan pilih salah satu kendaraan.';
		$alert12		= 'Tanggal tidak boleh kurang dari tanggal sekarang.';
		$alert13		= 'Pilih salah satu supir.';
		$Submitter		= 'Pengaju';
	}
	else
	{
		//$h1_title		= 'Reservation';	
		//$h2_title		= 'schedules list';	
		$MeetingRoom	= 'Meeting Room Reservation';
		$RestRoom		= 'Pemesanan Kamar';
		$Vehicle		= 'Pemesanan Kendaraan';
		$BookingNow		= 'Book Now';
		$Available		= 'Available';
		$EventList		= 'Events List';
		$RoomList		= 'Vehicle List';
		$StatusVH		= 'Vehicle Status';
		$RemoveList		= 'remove after drop';
		$CreateEvent	= 'Create New Event';
		$UpdateeEvent	= 'Update Status';
		$ChooseColor	= 'Select Color';
		
		$alert1			= 'End date/time must be greater than Start date/time.';
		$alert2			= 'Specify the start time of using the meeting room correctly.';
		$alert3			= 'Specify the end time of using the meeting room correctly.';
		$alert4			= 'Select a vehicle.';
		$alert5			= 'Write the reservation note of the vehicle.';
		$alert6			= 'Error time format.';
		$alert7			= 'Write the topic / destination.';
		$alert8			= 'Input qty of passenger.';
		$alert9			= 'Write submitter.';
		$alert10		= 'Please input email address.';
		$alert11		= 'Please select a vehicle.';
		$alert12		= 'The date can not be less than current date.';
		$alert13		= 'Select a driver.';
		$Submitter		= 'Submitter';
	}
	
	$RSV_STARTDX	= date('m/d/Y');
	$RSV_ENDDX		= date('m/d/Y');
	

?>
<body class="hold-transition skin-blue sidebar-mini">
    <section class="content-header">    
    <h1>
        <?php echo $h1_title; ?>
        <small><?php echo $h2_title; ?></small>
      </h1><br>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-3">
            	<div class="box box-solid">
                	<div class="box-body">
                		<div id="calendar2"></div>
            		</div>
                    <div class="box-header with-border"></div>
            		<div class="box-header with-border">
            			<h4 class="box-title"><?php echo $StatusVH; ?></h4>
            		</div>
                    
                    <div class="box-body">
                        <div id="external-events">
                        	<?php
								
								/*if($SchRow == 1)
									$btn_color	= "btn-bitbucket";
								elseif($SchRow == 2)
									$btn_color	= "btn-dropbox";
								elseif($SchRow == 3)
									$btn_color	= "btn-facebook";
								elseif($SchRow == 4)
									$btn_color	= "btn-flickr";
								elseif($SchRow == 5)
									$btn_color	= "btn-foursquare";
								elseif($SchRow == 6)
									$btn_color	= "btn-github";
								elseif($SchRow == 7)
									$btn_color	= "btn-google";
								elseif($SchRow == 8)
									$btn_color	= "btn-instagram";
								elseif($SchRow == 9)
									$btn_color	= "btn-linkedin";
								elseif($SchRow == 10)
									$btn_color	= "btn-twitter";
								
								$selVehicle	= site_url('reservation/addVH/?id='.$this->url_encryption_helper->encode_url($VH_CODE));
								if($VH_STAT == 1 && $VH_CODE != $VH_CODEX)
								{
									$cursor		= 'not-allowed';
									$urlVH		= '#';
									$btn_color	= "btn-default";
									$titleVH	= 'Using';
								}
								if($VH_STAT == 0 && $VH_CODE != $VH_CODEX)
								{
									$btn_color	= "btn-bitbucket";	
									$cursor		= 'pointer';
									$urlVH		= $selVehicle;	
									$titleVH	= 'Ready';
								}
								if($VH_STAT == 0 && $VH_CODE == $VH_CODEX)
								{
									$btn_color	= "btn-twitter";	
									$cursor		= 'pointer';
									$urlVH		= $selVehicle;
									$titleVH	= 'Ready';
								}
										
										//echo "$VH_CODEX = $VH_CODE";
										
								.......................................................................................................................*/		
							//echo "RSV_STAT: $RSV_STAT";
							$RSV_STAT0	= ""; // All Status
							$RSV_STAT2	= 2; // Confirm
							$RSV_STAT3	= 3; // Approve
							$RSV_STAT4	= 4; // Reschadule
							$RSV_STAT5	= 5; // Reject
							$RSV_STAT6	= 6; // Close
							$RSV_STAT7	= 7; // Revise
							$RSV_STAT8	= 8; // In Used
							
							$selVehicle0	= site_url('reservation/bklst1a2xeVH/?id='.$this->url_encryption_helper->encode_url($RSV_STAT0));
							$selVehicle2	= site_url('reservation/bklst1a2xeVH/?id='.$this->url_encryption_helper->encode_url($RSV_STAT2));
							$selVehicle3	= site_url('reservation/bklst1a2xeVH/?id='.$this->url_encryption_helper->encode_url($RSV_STAT3));
							$selVehicle4	= site_url('reservation/bklst1a2xeVH/?id='.$this->url_encryption_helper->encode_url($RSV_STAT4));
							$selVehicle5	= site_url('reservation/bklst1a2xeVH/?id='.$this->url_encryption_helper->encode_url($RSV_STAT5));
							$selVehicle6	= site_url('reservation/bklst1a2xeVH/?id='.$this->url_encryption_helper->encode_url($RSV_STAT6));
							$selVehicle7	= site_url('reservation/bklst1a2xeVH/?id='.$this->url_encryption_helper->encode_url($RSV_STAT7));
							$selVehicle8	= site_url('reservation/bklst1a2xeVH/?id='.$this->url_encryption_helper->encode_url($RSV_STAT8));
										
										
									?>
                            <input type="hidden" name="RSV_STATX" id="RSV_STATX" value="<?=$RSV_STAT?>">        
                            <!-- All Status -->
                            <a class="btn btn-block btn-default" href="<?=$selVehicle0?>" style="text-align:justify" title="All Status">
                                All Status
                            </a>
                            
                            <!-- Status Waitting -->
                            <a class="btn btn-block btn-warning" href="<?=$selVehicle2?>" style="text-align:justify" title="Waitting">
                                Waitting
                            </a>
                            
                            <!-- Status Approved -->
                             <a class="btn btn-block btn-success" href="<?=$selVehicle3?>" style="text-align:justify" title="Approved">
                                Approved
                            </a>
                            
                            <!-- Status Reschedule -->
                             <a class="btn btn-block btn-info" href="<?=$selVehicle4?>" style="text-align:justify" title="Reschedule">
                                Reschedule
                            </a>
                            
                            <!-- Status Reject -->
                             <a class="btn btn-block btn bg-red" href="<?=$selVehicle5?>" style="text-align:justify" title="Reject">
                                Reject
                            </a>
                            
                            <!-- Status Close -->
                             <a class="btn btn-block btn bg-maroon" href="<?=$selVehicle6?>" style="text-align:justify" title="Close">
                                Close
                            </a>
                            
                            <!-- Status Close -->
                             <a class="btn btn-block btn bg-purple" href="<?=$selVehicle7?>" style="text-align:justify" title="Revise">
                                Revise
                            </a>
                            
                            <!-- Status in used -->
                             <a class="btn btn-block btn-primary" href="<?=$selVehicle8?>" style="text-align:justify" title="in used">
                                in used
                            </a>
                            
                        </div>
                    </div>
                    <div class="box-body">
                        <div id="external-events">
							<h6 style="font-style:italic">
                            	<?php /*?><img src="<?php echo $MR0V; ?>" style="max-height:430px; max-width:300px; width:100%" /><?php */?>
                            </h6>
                        </div>
                    </div>
            	</div>
            </div>
            <div class="col-md-9">
                <div class="box box-primary">
                	<div class="box-body no-padding">
                		<div id="calendar"></div>
            		</div>
            	</div>
            </div>
        </div>
        
		<?php /*?><input type="text" name="isReadyAdd" class="form-control pull-left" id="isReadyAdd" style="width:130px" value="<?php echo $isReadyAdd; ?>">
		<input type="text" name="VH_CODE_DEF" class="form-control pull-left" id="VH_CODE_DEF" style="width:130px" value="<?php echo $VH_CODE_DEF; ?>"><?php */?>
        
        
		
		<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="myModalLabel"><?php echo $UpdateeEvent; ?></h4>
					</div>
					<form class="form-horizontal" name="frm" id="frm" method="post" action="<?php echo $edit_eventVH; ?>" onSubmit="return chkForm_E()">
						<div class="modal-body">
							<div class="form-group">
								<label for="p-in" class="col-md-4 label-heading"><?php echo $StartDate; ?></label>
								<div class="col-md-8">
									<div class="input-group date">
										<div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
										<input type="text" name="RSV_STARTD" class="form-control pull-left" id="RSV_STARTD_E" style="width:130px" readonly onKeyUp="toTimeString1_E(this.value);" onChange="chgStartTime_E(this.value)">
										<input type="hidden" name="RSV_CODE" class="form-control pull-left" id="RSV_CODE_E" readonly>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="p-in" class="col-md-4 label-heading"><?php echo $EndDate; ?></label>
								<div class="col-md-8">
									<div class="input-group date">
										<div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
										<input type="text" name="RSV_ENDD" class="form-control pull-left" id="RSV_ENDD_E" style="width:130px" readonly onKeyUp="toTimeString2_E(this.value);">
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="p-in" class="col-md-4 label-heading"><?php echo $Vehicle; ?></label>
								<div class="col-md-8">
									<?php /*?><select name="CATEG_CODE" id="CATEG_CODE" class="form-control">
										<option value="<?php echo $VH_CODE; ?>" selected>
											<?php echo "$VH_MEREK"; ?>
                                        </option>
									</select><?php */?>
                                    <input type="hidden" id="CATEG_CODE_E" name="CATEG_CODE" class="form-control" readonly>
                                    <input type="text" id="MEREK_E" name="MEREK_E" class="form-control" readonly>
								</div>
							</div>
                            <div class="form-group">
                                <label for="p-in" class="col-md-4 label-heading"><?php echo $NOPOL; ?></label>
                                <div class="col-md-8 ui-front">
                                    <input type="text" id="NOPOL_E" name="NOPOL" class="form-control" value="" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                            <label for="p-in" class="col-md-4 label-heading"><?php echo $Driver; ?></label>
                            <div class="col-md-8 ui-front">
                                <?php /*?><select name="DRIVER_CODE" id="DRIVER_CODE_E" class="form-control" style="max-width:250px">
                                    <option value="">--- None ---</option>
                                    <?php
                                        $CountDR 	= $this->db->count_all('tbl_driver');
                                        $sqlDR 	= "SELECT * FROM tbl_driver";
                                        $resDR		= $this->db->query($sqlDR)->result();
                                        if($CountDR > 0)
                                        {
                                            foreach($resDR as $rowDR) :
                                                $DRIVER_CODE 	= $rowDR->DRIVER_CODE;
                                                $DRIVER 		= $rowDR->DRIVER;
												$DRIVER_STAT 	= $rowDR->DRIVER_STAT;
												$isDisabled	= 0;
												$isDisDesc	= "";
												
												if($DRIVER_STAT == 1)
												{
													$isDisabled		= 1;
													$DRIVER_STAT	= "Used";
													$isDisDesc	= " - $DRIVER_STAT";
												}
                                                ?>
                                                <option <?php if($isDisabled == 1) {?> style="font-style:italic;color:red" <?php }?> value="<?php echo $DRIVER_CODE; ?>" <?php if($DRIVER_CODEX == $DRIVER_CODE) { ?>selected <?php } if($isDisabled == 1) { ?> disabled <?php } ?>>
													<?php echo "$DRIVER$isDisDesc"; ?>
                                                </option>
                                                <?php
											endforeach;
										}
										else
										{
											?>
												<option value="">--- None ---</option>
											<?php
										}
                                    ?>
                                </select><?php */?>
                                <input type="hidden" id="DRIVER_CODE_E" name="DRIVER_CODE" class="form-control" readonly>
                                <input type="text" id="DRIVER_E" name="DRIVER" class="form-control" readonly>
                            </div>
                        </div>
							<div class="form-group">
								<label for="p-in" class="col-md-4 label-heading"><?php echo $Topic; ?></label>
								<div class="col-md-8 ui-front">
									<input type="text" class="form-control" name="RSV_TITLE" id="RSV_TITLE_E" readonly />
								</div>
							</div>
							<div class="form-group">
								<label for="p-in" class="col-md-4 label-heading"><?php echo $Description; ?></label>
								<div class="col-md-8 ui-front">
									<textarea name="RSV_DESC" id="RSV_DESC_E" class="form-control" cols="30" readonly></textarea>
								</div>
							</div>
							<div class="form-group">
								<label for="p-in" class="col-md-4 label-heading"><?php echo "$Participants ($Qty)"; ?></label>
								<div class="col-md-8 ui-front">
									<input type="text" class="form-control" style="max-width:50px; text-align:right" name="RSV_QTY" id="RSV_QTY_E" readonly onKeyPress="return isIntOnlyNew(event);" />
								</div>
							</div>
							<div class="form-group">
								<label for="p-in" class="col-md-4 label-heading"><?php echo $Submitter; ?></label>
								<div class="col-md-8 ui-front">
									<input type="text" class="form-control" name="RSV_SUBMITTER" id="RSV_SUBMITTER_E" readonly />
								</div>
							</div>
							<div class="form-group">
								<label for="p-in" class="col-md-4 label-heading"><?php echo $Email; ?></label>
								<div class="col-md-8 ui-front">
									<input type="email" class="form-control" name="RSV_MAIL" id="RSV_MAIL_E" placeholder="Email" readonly />
								</div>
							</div>
							 
							<?php /*?><div class="form-group">
								<label class="col-md-4 label-heading">Undangan</label>
								<div class="col-md-8 ui-front">
									<select name="Inv_Emp[]" id="Inv_Emp" class="form-control select2" multiple="multiple" data-placeholder="Select Karyawan" style="width: 100%;">
									<?php
										if($CountUND > 0)
										{
											foreach($vwUND as $rowUND)
											{
												$Emp_ID		= $rowUND['Emp_ID'];
												$First_Name	= $rowUND['First_Name'];
												$Last_Name	= $rowUND['Last_Name'];
												$Full_Name	= "$First_Name $Last_Name";
												$Email		= $rowUND['Email'];
											
									?>
									  <option value="<?php echo "$Emp_ID|$Email";?>"><?php echo "$Full_Name - $Email";?></option>
									  <?php
										 }
											}
									  ?>
									</select>
								</div>
							</div>
							<?php */?>
                            <div class="form-group">
								<label for="p-in" class="col-md-4 label-heading"><?php echo $Notes; ?></label>
								<div class="col-md-8 ui-front">
									<textarea name="RSV_MEMO" id="RSV_MEMO_E" class="form-control" cols="30" ></textarea>
								</div>
							</div>
							<div class="form-group">
								<label for="p-in" class="col-md-4 label-heading" id="p-in"><?php echo $Status; ?></label>
								<div class="col-md-8 ui-front">
									<select name="RSV_STAT" id="RSV_STAT_E" class="form-control" style="max-width:100px" onChange="selStat(this.value)">
										<!--<option value="1">New</option>-->
										<option value="2">Confirm</option>
										<option value="3">Approve</option>
										<option value="4">Reschedule</option>
										<option value="5">Reject</option>
										<option value="6">Close</option>
                                        <option value="7">Revise</option>
										<option value="8">In used</option>
									</select>
								</div>
							</div>
							<input type="hidden" name="eventid" id="event_id" value="0" />
						</div>
						<div class="modal-footer">
							<button class="btn btn-primary" id="btn_update_e">
								<i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Update; ?>
							</button>&nbsp;
							<button class="btn btn-danger" type="button" data-dismiss="modal">
								<i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
        <?php /*?><form method="post" name="sendDate" id="sendDate" class="form-user" action="<?php echo $get_eventsVH_b; ?>" style="display:none">		
            <table>
                <tr>
                    <td>
                        <input type="text" name="date_start" id="date_start" value="">
                        <input type="text" name="date_end" id="date_end" value="">
                    </td>
                    <td><a class="tombol-date" id="dateClass">Simpan</a></td>
                </tr>
            </table>
        </form><?php */?>
    </section>
    
    <!-- jQuery 2.2.3 -->
    <script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/jQuery/jquery-2.2.3.min.js'; ?>"></script>
    <!-- Bootstrap 3.3.6 -->
    <script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/bootstrap/js/bootstrap.minx.js'; ?>"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
    <!-- Slimscroll -->
    <script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/slimScroll/jquery.slimscroll.min.js'; ?>"></script>
    <!-- FastClick -->
    <script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/fastclick/fastclick.js'; ?>"></script>
    <!-- AdminLTE App -->
    <script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/js/app.minx.js'; ?>"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/js/demo.js'; ?>"></script>
    <!-- fullCalendar 2.2.5 -->
    <script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/fullcalendar/moment.min.js'; ?>"></script>
    <script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/fullcalendar/fullcalendar.min.js'; ?>"></script>
    <!-- Page specific script -->
    <!-- Select2 -->
	<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/select2/select2.full.min.js';?>"></script>
    
    <script>
	
	$('#calendar2').fullCalendar(
	{
		header: 
		{
			left:   'title',
			center: '',
			right:  'prev,next'
		},
		contentHeight: 200
	});
	
	$(".fc table ").css("font-size", "12px");
	
		function chgStartT() 
		{
			RSV_STARTD	= document.getElementById('RSV_STARTD').value;
			RSV_ENDD	= document.getElementById('RSV_ENDD').value;
			RSV_STARTT	= RSV_STARTD.substr(-5,5);
			RSV_ENDT	= RSV_ENDD.substr(-5,5);
			
			RSV_STARTDD	= RSV_STARTD.substr(0,11);
			RSV_ENDDD	= RSV_ENDD.substr(0,11);
			fullTimeS	= new Date(RSV_STARTDD+' '+RSV_STARTT);
			fullTimeE	= new Date(RSV_ENDDD+' '+RSV_ENDT);
		}
		
		function toTimeString1(RSV_STARTT)
		{
			var totTxt 	= RSV_STARTT.length;
			var noHour	= /^[0-2]+$/;
			var noMinut	= /^[0-5]+$/;
			
			if(totTxt == 13)
			{
				isHourx	= document.getElementById('RSV_STARTD').value;
				isHourA	= isHourx.substr(0,11);
				isHourB	= isHourx.substr(0,15);
				isHour	= isHourx.substr(-2,2);
				
				if(isHour > 23)
				{
					
					alert('Hour must be less then 23');
					document.getElementById('RSV_STARTD').value = isHourA;
					document.getElementById('RSV_STARTD').focus();
					return false;
				}
				else
				{
					document.getElementById('RSV_STARTD').value = isHourB+':';
					document.getElementById('RSV_STARTD').focus();
				}
			}
			
			if(totTxt == 16)
			{
				isHourx		= document.getElementById('RSV_STARTD').value;
				isHourA		= isHourx.substr(-5,2);
				isMinutes	= isHourx.substr(-2,2);
				isDates		= isHourx.substr(0,11);
				if(parseFloat(isHourA) > 23)
				{
					alert('Range no [00 - 24]');
					document.getElementById('RSV_STARTD').value = isDates;
					document.getElementById('RSV_STARTD').focus();
					return false;
				}
				if(parseFloat(isMinutes) > 59)
				{
					alert('Range no [00 - 59]');
					document.getElementById('RSV_STARTD').value = isDates;
					document.getElementById('RSV_STARTD').focus();
					return false;
				}								
			}
		}
		
		function toTimeString2(RSV_ENDT)
		{
			var totTxt 	= RSV_ENDT.length;
			var noHour	= /^[0-2]+$/;
			var noMinut	= /^[0-5]+$/;
			
			if(totTxt == 13)
			{
				isHourx	= document.getElementById('RSV_ENDD').value;
				isHourA	= isHourx.substr(0,11);
				isHourB	= isHourx.substr(0,15);
				isHour	= isHourx.substr(-2,2);
				
				if(isHour > 23)
				{
					
					alert('Hour must be less then 23');
					document.getElementById('RSV_ENDD').value = isHourA;
					document.getElementById('RSV_ENDD').focus();
					return false;
				}
				else
				{
					document.getElementById('RSV_ENDD').value = isHourB+':';
					document.getElementById('RSV_ENDD').focus();
				}
			}
			
			if(totTxt == 16)
			{
				isHourx		= document.getElementById('RSV_ENDD').value;
				isHourA		= isHourx.substr(-5,2);
				isMinutes	= isHourx.substr(-2,2);
				isDates		= isHourx.substr(0,11);
				
				if(isHourA > 23)
				{
					alert('Range no [00 - 23]');
					document.getElementById('RSV_ENDD').value = isDates;
					document.getElementById('RSV_ENDD').focus();
					return false;
				}
				
				if(isMinutes > 59)
				{
					alert('Range no [00 - 59]');
					document.getElementById('RSV_ENDD').value = isDates;
					document.getElementById('RSV_ENDD').focus();
					return false;
				}
			}
		}
		
		function toTimeString1_E(RSV_STARTT)
		{
			var totTxt 	= RSV_STARTT.length;
			var noHour	= /^[0-2]+$/;
			var noMinut	= /^[0-5]+$/;
			
			if(totTxt == 13)
			{
				isHourx	= document.getElementById('RSV_STARTD_E').value;
				isHourA	= isHourx.substr(0,11);
				isHourB	= isHourx.substr(0,15);
				isHour	= isHourx.substr(-2,2);
				
				if(isHour > 23)
				{
					
					alert('Hour must be less then 23');
					document.getElementById('RSV_STARTD_E').value = isHourA;
					document.getElementById('RSV_STARTD_E').focus();
					return false;
				}
				else
				{
					document.getElementById('RSV_STARTD_E').value = isHourB+':';
					document.getElementById('RSV_STARTD_E').focus();
				}
			}
			
			if(totTxt == 16)
			{
				isHourx		= document.getElementById('RSV_STARTD_E').value;
				isHourA		= isHourx.substr(-5,2);
				isMinutes	= isHourx.substr(-2,2);
				isDates		= isHourx.substr(0,11);
				
				if(isHourA > 23)
				{
					alert('Range no [00 - 23]');
					document.getElementById('RSV_STARTD_E').value = isDates;
					document.getElementById('RSV_STARTD_E').focus();
					return false;
				}
				if(isMinutes > 59)
				{
					alert('Range no [00 - 59]');
					document.getElementById('RSV_STARTD_E').value = isDates;
					document.getElementById('RSV_STARTD_E').focus();
					return false;
				}								
			}
		}
		
		function toTimeString2_E(RSV_ENDT)
		{
			var totTxt 	= RSV_ENDT.length;
			var noHour	= /^[0-2]+$/;
			var noMinut	= /^[0-5]+$/;
			
			if(totTxt == 13)
			{
				isHourx	= document.getElementById('RSV_ENDD_E').value;
				isHourA	= isHourx.substr(0,11);
				isHourB	= isHourx.substr(0,15);
				isHour	= isHourx.substr(-2,2);
				
				if(isHour > 23)
				{
					
					alert('Hour must be less then 23');
					document.getElementById('RSV_ENDD_E').value = isHourA;
					document.getElementById('RSV_ENDD_E').focus();
					return false;
				}
				else
				{
					document.getElementById('RSV_ENDD_E').value = isHourB+':';
					document.getElementById('RSV_ENDD_E').focus();
				}
			}
			
			if(totTxt == 16)
			{
				isHourx		= document.getElementById('RSV_ENDD_E').value;
				isHourA		= isHourx.substr(-5,2);
				isMinutes	= isHourx.substr(-2,2);
				isDates		= isHourx.substr(0,11);
				
				if(isHourA > 23)
				{
					alert('Range no [00 - 23]');
					document.getElementById('RSV_ENDD_E').value = isDates;
					document.getElementById('RSV_ENDD_E').focus();
					return false;
				}
				
				if(isMinutes > 59)
				{
					alert('Range no [00 - 59]');
					document.getElementById('RSV_ENDD_E').value = isDates;
					document.getElementById('RSV_ENDD_E').focus();
					return false;
				}
			}
		}
		
		function chgStartTime(RSV_STARTD_N)
		{
			RSV_STARTD_N1	= RSV_STARTD_N.length;
			//alert(RSV_STARTD_N)
			//alert(RSV_STARTD_N1)
			/*RSV_ENDD	= document.getElementById('RSV_ENDD_E').value;
			RSV_ENDDL	= RSV_ENDD.length;
			RSV_STARTT	= RSV_STARTD.substr(-5,5);
			RSV_ENDT	= RSV_ENDD.substr(-5,5);*/
		}
		
		function chkForm()
		{
			RSV_STARTD	= document.getElementById('RSV_STARTD').value;
			RSV_STARTDL	= RSV_STARTD.length;
			RSV_ENDD	= document.getElementById('RSV_ENDD').value;
			RSV_ENDDL	= RSV_ENDD.length;
			RSV_STARTT	= RSV_STARTD.substr(-5,5);
			RSV_ENDT	= RSV_ENDD.substr(-5,5);
			//alert('RSV_STARTD = '+RSV_STARTD)
			//alert('RSV_STARTDL = '+RSV_STARTDL)
			//alert('RSV_STARTT = '+RSV_STARTT)
			if(RSV_STARTT == '00:00' || RSV_STARTT == '' || RSV_STARTDL != 16)
			{
				alert('<?php echo $alert2; ?>');
				isHourx	= document.getElementById('RSV_STARTD').value;
				isHourA	= isHourx.substr(0,11);
				document.getElementById('RSV_STARTD').value = isHourA;
				document.getElementById('RSV_STARTD').focus();
				return false;
			}
			
			if(RSV_ENDT == '00:00' || RSV_ENDT == '' || RSV_ENDDL != 16)
			{
				alert('<?php echo $alert3; ?>');
				isHourx	= document.getElementById('RSV_ENDD').value;
				isHourA	= isHourx.substr(0,11);	
				document.getElementById('RSV_ENDD').value = isHourA;
				document.getElementById('RSV_ENDD').focus();
				return false;
			}
		
			// CHECK DATE TIME
			RSV_STARTDD	= RSV_STARTD.substr(0,11);
			RSV_ENDDD	= RSV_ENDD.substr(0,11);
			fullTimeS	= new Date(RSV_STARTDD+' '+RSV_STARTT);
			fullTimeE	= new Date(RSV_ENDDD+' '+RSV_ENDT);
		
			if(fullTimeS > fullTimeE)
			{
				alert('<?php echo $alert1; ?>');
				document.getElementById('RSV_ENDD').focus();
				return false;
			}
		
			CATEG_CODE	= document.getElementById('CATEG_CODE').value;
			if(CATEG_CODE == '')
			{
				alert('<?php echo $alert4; ?>');
				document.getElementById('CATEG_CODE').focus();
				return false;
			}
			
			DRIVER_CODE	= document.getElementById('DRIVER_CODE').value;
			//alert(DRIVER_CODE);
			if(DRIVER_CODE == '')
			{
				alert('<?php echo $alert13; ?>');
				document.getElementById('DRIVER_CODE').focus();
				return false;
			}
			
			RSV_TITLE	= document.getElementById('RSV_TITLE').value;
			if(RSV_TITLE == '')
			{
				alert('<?php echo $alert7; ?>');
				document.getElementById('RSV_TITLE').focus();
				return false;
			}
		
			RSV_DESC	= document.getElementById('RSV_DESC').value;
			if(RSV_DESC == '')
			{
				alert('<?php echo $alert5; ?>');
				document.getElementById('RSV_DESC').focus();
				return false;
			}
			
			RSV_QTY	= document.getElementById('RSV_QTY').value;
			if(RSV_QTY == 0)
			{
				alert('<?php echo $alert8; ?>');
				document.getElementById('RSV_QTY').value = '';
				document.getElementById('RSV_QTY').focus();
				return false;
			}
			
			RSV_SUBMITTER	= document.getElementById('RSV_SUBMITTER').value;
			if(RSV_SUBMITTER == '')
			{
				alert('<?php echo $alert9; ?>');
				document.getElementById('RSV_SUBMITTER').value = '';
				document.getElementById('RSV_SUBMITTER').focus();
				return false;
			}
			
			RSV_MAIL	= document.getElementById('RSV_MAIL').value;
			if(RSV_MAIL == '')
			{
				alert('<?php echo $alert10; ?>');
				document.getElementById('RSV_MAIL').value = '';
				document.getElementById('RSV_MAIL').focus();
				return false;
			}
		}
		
		function chkForm_E()
		{
			RSV_STARTD	= document.getElementById('RSV_STARTD_E').value;
			RSV_STARTDL	= RSV_STARTD.length;
			RSV_ENDD	= document.getElementById('RSV_ENDD_E').value;
			RSV_ENDDL	= RSV_ENDD.length;
			RSV_STARTT	= RSV_STARTD.substr(-5,5);
			RSV_ENDT	= RSV_ENDD.substr(-5,5);
			
			if(RSV_STARTT == '00:00' || RSV_STARTT == '' || RSV_STARTDL != 16)
			{
				alert('<?php echo $alert2; ?>');
				isHourx	= document.getElementById('RSV_STARTD_E').value;
				isHourA	= isHourx.substr(0,11);
				document.getElementById('RSV_STARTD_E').value = isHourA;
				document.getElementById('RSV_STARTD_E').focus();
				return false;
			}
			
			if(RSV_ENDT == '00:00' || RSV_ENDT == '' || RSV_ENDDL != 16)
			{
				alert('<?php echo $alert3; ?>');
				isHourx	= document.getElementById('RSV_ENDD_E').value;
				isHourA	= isHourx.substr(0,11);	
				document.getElementById('RSV_ENDD_E').value = isHourA;
				document.getElementById('RSV_ENDD_E').focus();
				return false;
			}
		
			// CHECK DATE TIME
			RSV_STARTDD	= RSV_STARTD.substr(0,11);
			RSV_ENDDD	= RSV_ENDD.substr(0,11);
			fullTimeS	= new Date(RSV_STARTDD+' '+RSV_STARTT);
			fullTimeE	= new Date(RSV_ENDDD+' '+RSV_ENDT);
		
			if(fullTimeS > fullTimeE)
			{
				alert('<?php echo $alert1; ?>');
				document.getElementById('RSV_ENDD_E').focus();
				return false;
			}
		
			CATEG_CODE	= document.getElementById('CATEG_CODE_E').value;
			if(CATEG_CODE == '')
			{
				alert('<?php echo $alert4; ?>');
				document.getElementById('CATEG_CODE_E').focus();
				return false;
			}
			
			RSV_TITLE	= document.getElementById('RSV_TITLE_E').value;
			if(RSV_TITLE == '')
			{
				alert('<?php echo $alert7; ?>');
				document.getElementById('RSV_TITLE_E').focus();
				return false;
			}
		
			RSV_DESC	= document.getElementById('RSV_DESC_E').value;
			if(RSV_DESC == '')
			{
				alert('<?php echo $alert5; ?>');
				document.getElementById('RSV_DESC_E').focus();
				return false;
			}
			
			RSV_QTY	= document.getElementById('RSV_QTY_E').value;
			if(RSV_QTY == 0)
			{
				alert('<?php echo $alert8; ?>');
				document.getElementById('RSV_QTY_E').value = '';
				document.getElementById('RSV_QTY_E').focus();
				return false;
			}
			
			RSV_SUBMITTER	= document.getElementById('RSV_SUBMITTER_E').value;
			if(RSV_SUBMITTER == '')
			{
				alert('<?php echo $alert9; ?>');
				document.getElementById('RSV_SUBMITTER_E').value = '';
				document.getElementById('RSV_SUBMITTER_E').focus();
				return false;
			}
			
			RSV_MAIL	= document.getElementById('RSV_MAIL_E').value;
			if(RSV_MAIL == '')
			{
				alert('<?php echo $alert10; ?>');
				document.getElementById('RSV_MAIL_E').value = '';
				document.getElementById('RSV_MAIL_E').focus();
				return false;
			}
		}
		
		/* di sembunyikan karena setiap add, pasti kondisi room dalam keadaan kosong */
		
		/*
		$(document).ready(function()
		{
			$(".tombol-date").click(function()
			{
				var add_PR	= "";
				var formAction 	= $('#sendDate')[0].action;
				var data = $('.form-user').serialize();
				$.ajax({
					type: 'POST',
					url: formAction,
					data: data,
					success: function(data)
					{
						//var myarr = response;
						//document.getElementById('CATEG_CODE').value = myarr;
						$("#CATEG_CODE_E").html(data);
					}
				});
			});
		});
		.................................................*/
	
		$(function ()
		{
			/* initialize the external events
			-----------------------------------------------------------------*/
			function ini_events(ele) 
			{
				//alert("1");
				ele.each(function ()
				{
					// create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
					// it doesn't need to have a start or end
					var eventObject = 
					{
						title: $.trim($(this).text()) // use the element's text as the event title
					};
			
					// store the Event Object in the DOM element so we can get to it later
					$(this).data('eventObject', eventObject);
					
					// make the event draggable using jQuery UI
					$(this).draggable({
						zIndex: 1070,
						revert: true, // will cause the event to go back to its
						revertDuration: 0  //  original position after the drag
					});
			
				});
			}
		
			//alert("1");
			ini_events($('#external-events div.external-event'));
		
			/* initialize the calendar
			 -----------------------------------------------------------------*/
			//Date for the calendar events (dummy data)
			var date_last_clicked = null;
			var date 		= new Date();
			var d 			= date.getDate(), m = date.getMonth(), y = date.getFullYear();
						
			//alert("1");
			$('#calendar').fullCalendar({
				header: 
				{
					left: 'prev,next today',
					center: 'title',
					right: 'agendaWeek,agendaDay,month',
				},
				buttonText: 
				{
					today: 'today',
					month: 'month',
					week: 'week',
					day: 'day'
				},
				defaultView: 'agendaWeek',
				allDaySlot: false,
				
			  events: [
			  <?php
			  	if($BLVH_C > 0)
				{
					foreach($BLVH_V as $rowBLVH)
					{
						$RSV_ID 		= $rowBLVH['RSV_ID'];
						$RSV_CODE		= $rowBLVH['RSV_CODE'];
						$RSV_STARTD		= $rowBLVH['RSV_STARTD'];
						$RSV_ENDD		= $rowBLVH['RSV_ENDD'];
						$RSV_CATEG		= $rowBLVH['RSV_CATEG'];
						$CATEG_CODE		= $rowBLVH['CATEG_CODE'];
						$VH_MEREK		= $rowBLVH['VH_MEREK'];
						$VH_NOPOL		= $rowBLVH['VH_NOPOL'];
						$DRIVER_CODE	= $rowBLVH['DRIVER_CODE'];
						$DRIVER			= $rowBLVH['DRIVER'];
						$RSV_STARTT		= $rowBLVH['RSV_STARTT'];
						$RSV_ENDT		= $rowBLVH['RSV_ENDT'];
						$CATEG_CODE2	= $rowBLVH['CATEG_CODE2'];
						$RSV_STARTD2	= $rowBLVH['RSV_STARTD2'];
						$RSV_ENDD2		= $rowBLVH['RSV_ENDD2'];
						$RSV_STARTT2	= $rowBLVH['RSV_STARTT2'];
						$RSV_ENDT2		= $rowBLVH['RSV_ENDT2'];
						$RSV_TITLE		= $rowBLVH['RSV_TITLE'];
						$RSV_DESC		= $rowBLVH['RSV_DESC'];
						$RSV_QTY		= $rowBLVH['RSV_QTY'];
						$RSV_MEMO		= $rowBLVH['RSV_MEMO'];
						$RSV_SUBMITTER	= $rowBLVH['RSV_SUBMITTER'];
						$RSV_MAIL		= $rowBLVH['RSV_MAIL'];
						$RSV_STAT		= $rowBLVH['RSV_STAT'];	
						
						$arrVH			= "$RSV_CODE|$RSV_CATEG|$CATEG_CODE|$CATEG_CODE2|$VH_MEREK|$VH_NOPOL|$DRIVER_CODE|$DRIVER|";
						$arrVH			.="$RSV_STARTD2|$RSV_ENDD2|$RSV_DESC|$RSV_QTY|$RSV_SUBMITTER|$RSV_MAIL|$RSV_STAT";
										  
						if($RSV_STAT == 2)
						{
							$color	= '#B8860B'; // Confirm
						}
						elseif($RSV_STAT == 3)
						{
							$color	= '#006400'; // Approve
						}
						elseif($RSV_STAT == 4)
						{
							$color	= '#00FFFF'; // Reschedule
						}
						elseif($RSV_STAT == 5)
						{
							$color	= 'red'; // Rejected
						}
						elseif($RSV_STAT == 6)
						{
							$color	= 'maroon'; // Close
						}
						elseif($RSV_STAT == 7)
						{
							$color	= 'purple'; // Revise
						}
						elseif($RSV_STAT == 8)
						{
							$color	= '#1E90FF '; // In Used
						}
					
			  ?>
				{
				  id: '<?=$arrVH?>',
				  title: '<?=$RSV_TITLE?>',
				  start: '<?=$RSV_STARTD?>',
				  end: '<?=$RSV_ENDD?>',
				  color: '<?=$color?>'
				},
				<?php
					}
				}
				?>
			  ],
			
				dayClick: function(date, jsEvent, view) 
				{
					//alert("test");
					var dateNow 	= new Date();
					var isReadyAdd	= document.getElementById('isReadyAdd').value;
					var myDate		= date.format('YYYY/MM/DD HH:mm');
					var dateSelect 	= new Date(myDate);
					
					var myEndDate1 	= moment(dateSelect).add(60, 'm').toDate();
					var myEndDate2 	= new Date(myEndDate1);
					var DateY		= myEndDate2.getFullYear();
					var DateM		= myEndDate2.getMonth()+1;
					if(DateM < 10)
					{
						DateM	= "0"+DateM;
						//alert(DateM);
					}
					var DateD		= myEndDate2.getDate();
					var MinuteH		= myEndDate2.getHours();
					if(MinuteH < 10)
					{
						MinuteH	= "0"+MinuteH;
						//alert(MinuteH);
					}
					MinuteM			= myEndDate2.getMinutes();
					if(MinuteM < 10)
					{
						MinuteM	= "0"+MinuteM;
						//alert(MinuteH);
					}
					var myEndDate	= DateY+'/'+DateM+'/'+DateD+' '+MinuteH+':'+MinuteM;
					//var myEndDate 	= new Date(myEndDate);
					//alert('date = '+date)
					//alert('myEndDate2 = '+myEndDate)
					if(dateSelect < dateNow)
					{
						alert('<?php echo $alert12; ?>');
						$('#calendar').fullCalendar('unselect');
                		// or display some sort of alert
                		return false;
					}
					
					if(isReadyAdd == 0)
					{
						alert('<?php echo $alert11; ?>');
						return false;
					}
					
					//document.getElementById('date_start').value = myDate;
					//document.getElementById('date_end').value = myEndDate;
					
					//document.getElementById('dateClass').click();
					
					document.getElementById('RSV_STARTD').value = myDate;
					document.getElementById('RSV_ENDD').value = myEndDate;
					date_last_clicked = $(this);
					$(this).css('background-color', '#bed7f3');
					$('#addModal').modal();
				},
				
				eventClick: function(event, jsEvent, view) 
				{
					//alert("1");
					//$('#name').val(event.title);
					//$('#description').val(event.description);
					//var isReadyAdd	= document.getElementById('isReadyAdd').value;
					
					//var RSV_ID		= event.RSV_ID;
						
					var arrVH 			= event.id;
					var strVH			= arrVH.split("|");
					//alert(strVH[0]);
					var RSV_CODE		= strVH[0];
					var RSV_CATEG		= strVH[1];
					var CATEG_CODE 		= strVH[2];
					var RSV_STARTD 		= event.start;
					//alert(RSV_STARTD);
					var RSV_ENDD 		= event.end;
					//alert(RSV_ENDD);
					var CATEG_CODE2 	= strVH[3];
					var VH_MEREK		= strVH[4];
					var VH_NOPOL		= strVH[5];
					var DRIVER_CODE 	= strVH[6];
					var DRIVER			= strVH[7];
					var RSV_STARTD2 	= strVH[8];
					var RSV_ENDD2 		= strVH[9];
					var RSV_TITLE 		= event.title;
					//alert(RSV_TITLE);
					var RSV_DESC 		= strVH[10];
					var RSV_QTY 		= strVH[11];
					var RSV_SUBMITTER 	= strVH[12];
					var RSV_MAIL 		= strVH[13];
					var RSV_STAT 		= strVH[14];
					
					//alert(VH_NOPOL);
					
					//inv_to_id		= Inv_Emp_ID.split(';');
					//inv_to_email	= Inv_Emp_Email.split(';');
					//EmpID_Inv	= inv_to_id[0];
					//Email_Inv	= inv_to_email[0];
					//alert(EmpID_Inv+'|'+Email_Inv);
					
					var myDateS = moment(RSV_STARTD).format('YYYY/MM/DD HH:mm');
					var myDateE = moment(RSV_ENDD).format('YYYY/MM/DD HH:mm');
					//alert(myDateE);
					
					
					//document.getElementById('date_start').value 	= myDateS;
					//document.getElementById('date_end').value 		= myDateE;
					
					document.getElementById('RSV_CODE_E').value 	= RSV_CODE;
					document.getElementById('RSV_STARTD_E').value 	= myDateS;
					document.getElementById('RSV_ENDD_E').value 	= myDateE;
					
					document.getElementById('CATEG_CODE_E').value 	= CATEG_CODE;
					document.getElementById('NOPOL_E').value 		= VH_NOPOL;
					document.getElementById('MEREK_E').value 		= VH_MEREK;
					document.getElementById('DRIVER_CODE_E').value 	= DRIVER_CODE;
					document.getElementById('DRIVER_E').value 		= DRIVER;
					document.getElementById('RSV_TITLE_E').value 	= RSV_TITLE;
					document.getElementById('RSV_DESC_E').value 	= RSV_DESC;
					document.getElementById('RSV_QTY_E').value 		= RSV_QTY;
					document.getElementById('RSV_SUBMITTER_E').value = RSV_SUBMITTER;
					document.getElementById('RSV_MAIL_E').value 	= RSV_MAIL;
					//document.getElementById('Inv_Emp').value		= Inv_Emp;
					document.getElementById('RSV_STAT_E').value 	= RSV_STAT;
					
					//document.getElementById('dateClass').click();
					
					//$('#event_id').val(event.id);
					$('#editModal').modal();
					
					
					/*$('#name').val(event.title);
					$('#description').val(event.description);
					$('#start_date').val(moment(event.start).format('YYYY/MM/DD HH:mm'));
					if(event.end)
					{
						$('#end_date').val(moment(event.end).format('YYYY/MM/DD HH:mm'));
					} 
					else 
					{
						$('#end_date').val(moment(event.start).format('YYYY/MM/DD HH:mm'));
					}
					$('#event_id').val(event.id);
					$('#editModal').modal();*/
				},
				
				editable: true,
				droppable: true, // this allows things to be dropped onto the calendar !!!
				drop: function (date, allDay)
				{
					// this function is called when something is dropped
					// retrieve the dropped element's stored Event Object
					var originalEventObject = $(this).data('eventObject');
				
					// we need to copy it, so that multiple events don't have a reference to the same object
					var copiedEventObject = $.extend({}, originalEventObject);
				
					// assign it the date that was reported
					copiedEventObject.start = date;
					copiedEventObject.allDay = allDay;
					copiedEventObject.backgroundColor = $(this).css("background-color");
					copiedEventObject.borderColor = $(this).css("border-color");
				
					// render the event on the calendar
					// the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
					$('#calendar').fullCalendar('renderEvent', copiedEventObject, true);
			
					// is the "remove after drop" checkbox checked?
					if ($('#drop-remove').is(':checked'))
					{
						  // if so, remove the element from the "Draggable Events" list
						  $(this).remove();
					}
				}
			});
		
			/* ADDING EVENTS */
			var currColor = "#3c8dbc"; //Red by default
			//Color chooser button
			var colorChooser = $("#color-chooser-btn");
			$("#color-chooser > li > a").click(function (e) 
			{
				e.preventDefault();
				//Save color
				currColor = $(this).css("color");
				//Add color effect to button
				$('#add-new-event').css({"background-color": currColor, "border-color": currColor});
			});
			$("#add-new-event").click(function (e) 
			{
				e.preventDefault();
				//Get value and make sure it is not null
				var val1 = $("#new-event1").val();
				var val2 = $("#new-event2").val();
				
				if (val1.length == 0) 
				{
					return;
				}
				else
				{
					//Create events
					var event = $("<div />");
					event.css({"background-color": currColor, "border-color": currColor, "color": "#fff"}).addClass("external-event");
					event.html(val1);
					// val = value new event
					$('#external-events').prepend(event);
					
					//Add draggable funtionality
					ini_events(event);
					
					//Remove event from text input
					
					$("#new-event1").val("");
				}
				
				if (val2.length == 0) 
				{
					return;
				}
				else
				{
					//Create events
					var event = $("<div />");
					event.css({"background-color": currColor, "border-color": currColor, "color": "#fff"}).addClass("external-event");
					event.html(val2);
					//alert(val1)
					// val = value new event
					$('#external-events').prepend(event);
					
					//Add draggable funtionality
					ini_events(event);
					
					//Remove event from text input
					$("#new-event2").val("");
				}
			});
		});
	
		function isIntOnlyNew(evt)
		{
			if (evt.which){ var charCode = evt.which; }
			else if(document.all && event.keyCode){ var charCode = event.keyCode; }
			else { return true; }
			return ((charCode == 45) || (charCode == 46) || (charCode == 8) || (charCode >= 48) && (charCode <= 57));
		}
		
    </script>
</body>

<?php 
$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>
</html>