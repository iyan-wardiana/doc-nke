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
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/select2/select2.min.css';?>">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
    folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/skins/_all-skins.min.css'; ?>">
    
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
		if($TranslCode == 'Room')$Room = $LangTransl;
		if($TranslCode == 'Time')$Time = $LangTransl;
		if($TranslCode == 'Topic')$Topic = $LangTransl;
		if($TranslCode == 'Participants')$Participants = $LangTransl;
		if($TranslCode == 'Qty')$Qty = $LangTransl;
		if($TranslCode == 'None')$None = $LangTransl;
		if($TranslCode == 'Notes')$Notes = $LangTransl;
		if($TranslCode == 'Email')$Emaill = $LangTransl;
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
		$RoomList		= 'Daftar Ruang Rapat';
		$StatusMR		= 'Status Pemesanan Ruang Meeting';
		$RemoveList		= 'hapus setelah didrag';
		$CreateEvent	= 'Buat Jadwal Baru';
		$UpdateeEvent	= 'Ubah Agenda';
		$ChooseColor	= 'Pilih Warna';
		$MyReservation	= 'Lihat Pemesanan Saya';
		$ViewDetail		= 'Lihat Rincian';
			
		$alert1			= 'Tanggal/waktu selesai harus lebih besar dari tanggal/waktu mulai.';
		$alert2			= 'Tentukan waktu mulai penggunaan ruang rapat dengan benar.';
		$alert3			= 'Tentukan waktu selesai penggunaan ruang rapat dengan benar.';
		$alert4			= 'Pilih salah satu ruang rapat.';
		$alert5			= 'Tuliskan catatan peminjaman ruang rapat.';
		$alert6			= 'Format waktu salah.';
		$alert7			= 'Tuliskan topik rapat.';
		$alert8			= 'Masukan jumlah peserta.';
		$alert9			= 'Tuliskan pengaju pemesanan.';
		$alert9			= 'Tuliskan pengaju pemesanan.';
		$alert10		= 'Masukan alamat email.';
		$alert11		= 'Silahkan pilih salah satu ruang rapat.';
		$alert12		= 'Tanggal tidak boleh kurang dari tanggal sekarang.';
		$Submitter		= 'Pengaju';
		$InviteTo		= 'Undang kepada';
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
		$RoomList		= 'Meeting Room List';
		$StatusMR		= 'Reservation Meeting Room Status';
		$RemoveList		= 'remove after drop';
		$CreateEvent	= 'Create New Event';
		$UpdateeEvent	= 'Update Agenda';
		$ChooseColor	= 'Select Color';
		$MyReservation	= 'View My Reservation';
		$ViewDetail		= 'View Detail';
		
		$alert1			= 'End date/time must be greater than Start date/time.';
		$alert2			= 'Specify the start time of using the meeting room correctly.';
		$alert3			= 'Specify the end time of using the meeting room correctly.';
		$alert4			= 'Select a meeting room.';
		$alert5			= 'Write the reservation note of the meeting room.';
		$alert6			= 'Error time format.';
		$alert7			= 'Write the topic of meeting.';
		$alert8			= 'Input qty of participants.';
		$alert9			= 'Write submitter.';
		$alert10		= 'Please input email address.';
		$alert11		= 'Please select a meeting room.';
		$alert12		= 'The date can not be less than current date.';
		$Submitter		= 'Submitter';
		$InviteTo		= 'Invite To';
	}
	
	$RSV_STARTDX	= date('m/d/Y');
	$RSV_ENDDX		= date('m/d/Y');
	
	// GET PHOTO
	if($MR_CODE_DEF == 'MR-180001')
		$MR0V			= base_url('assets/AdminLTE-2.0.5/dist/img/meetingroom/ruangrapat1.jpg');
	elseif($MR_CODE_DEF == 'MR-180002')
		$MR0V			= base_url('assets/AdminLTE-2.0.5/dist/img/meetingroom/ruangrapat2.jpg');
	elseif($MR_CODE_DEF == 'MR-180003')
		$MR0V			= base_url('assets/AdminLTE-2.0.5/dist/img/meetingroom/ruangrapat3.jpg');
	elseif($MR_CODE_DEF == 'MR-180004')
		$MR0V			= base_url('assets/AdminLTE-2.0.5/dist/img/meetingroom/ruangrapat4.jpg');
	elseif($MR_CODE_DEF == 'MR-180005')
		$MR0V			= base_url('assets/AdminLTE-2.0.5/dist/img/meetingroom/ruangrapat5.jpg');
	elseif($MR_CODE_DEF == 'MR-180006')
		$MR0V			= base_url('assets/AdminLTE-2.0.5/dist/img/meetingroom/ruangrapat6.jpg');
	elseif($MR_CODE_DEF == 'MR-180007')
		$MR0V			= base_url('assets/AdminLTE-2.0.5/dist/img/meetingroom/ruangrapat7.jpg');
	elseif($MR_CODE_DEF == 'MR-180008')
		$MR0V			= base_url('assets/AdminLTE-2.0.5/dist/img/meetingroom/ruangrapat12.jpg');
	elseif($MR_CODE_DEF == 'MR-180009')
		$MR0V			= base_url('assets/AdminLTE-2.0.5/dist/img/meetingroom/ruangrapat23.jpg');
	elseif($MR_CODE_DEF == 'MR-180010')
		$MR0V			= base_url('assets/AdminLTE-2.0.5/dist/img/meetingroom/ruangrapat123.jpg');
	else
		$MR0V			= base_url('assets/AdminLTE-2.0.5/dist/img/meetingroom/homerapat.jpg');
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
            		<div class="box-header with-border">
            			<h4 class="box-title"><?php echo $RoomList; ?></h4>
            		</div>
                    <?php
						$RSV_STARTD2	= date('Y-m-d H:i:s');
						$RSV_ENDD2		= date('Y-m-d H:i:s');
							
						if($isReadyAdd == 0)
						{
							$MR_CODE_DEFA	= '';
						}
						else
						{
							$MR_CODE_DEFA	= $MR_CODE_DEF;
						}
					?>
                    <div class="box-body">
                        <div id="external-events">
                        <?php
						$selMRoomHist	= site_url('reservation/selectMR/?id='.$this->url_encryption_helper->encode_url("All"));
						if($MR_CODE_DEFA == 'All')
						{
							$btn_color	= "btn-foursquare";
						}
						else
						{
							$btn_color	= "btn-bitbucket";	
						}
						?>
                        <a class="btn btn-block btn-social <?=$btn_color?>" href="<?=$selMRoomHist?>">
                            <i class="fa fa-registered"></i><?php echo "$MyReservation"; ?>
                        </a>
                        	<?php
								$sqltSchC	= "tbl_meeting_room";
								$restSchC	= $this->db->count_all($sqltSchC);	
								$SchRow		= 0;							
								if($restSchC > 0)
								{							
									$sqltSch	= "SELECT MR_ID, MR_CODE, MR_NAME FROM tbl_meeting_room";
									$restSch	= $this->db->query($sqltSch)->result();
									foreach($restSch as $rowSch):
										$SchRow		= $SchRow + 1;
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
											$btn_color	= "btn-twitter";*/
										
										$MR_ID		= $rowSch->MR_ID;
										$MR_CODE	= $rowSch->MR_CODE;
										$MR_NAME	= $rowSch->MR_NAME;
										if($MR_CODE == $MR_CODE_DEFA)
										  	$btn_color	= "btn-foursquare";
										else	
											$btn_color	= "btn-bitbucket";									  	
										
										$selMRoom	= site_url('reservation/selectMR/?id='.$this->url_encryption_helper->encode_url($MR_CODE));
									?>
                                        <a class="btn btn-block btn-social <?php echo $btn_color; ?>" href="<?php echo $selMRoom; ?>">
                                            <i class="fa fa-registered"></i><?php echo "$MR_NAME"; ?>
                                        </a>
									<?php
									endforeach;
								}
								else
								{
									?>
									<h6 style="font-style:italic"><?php echo $None; ?></h6>
                                    <?php
								}
							?>
                        </div>
                    </div>
                    
                    <div class="box box-solid">
                    <div class="box-header with-border">
                      <h3 class="box-title"><?=$StatusMR?></h3>
                    </div>
                    <div class="box-body">
                      <div class="btn-group" style="width: 100%; margin-bottom: 10px;">
                        <!--<button type="button" id="color-chooser-btn" class="btn btn-info btn-block dropdown-toggle" data-toggle="dropdown">Color <span class="caret"></span></button>-->
                        <a class="text-orange" href="#" style="text-align:justify; cursor:default;" title="Waiting"><i class="fa fa-square"></i></a>&nbsp;Waiting<br>
                        <a class="text-green" href="#" style="text-align:justify; cursor:default;" title="Approved"><i class="fa fa-square"></i></a>&nbsp;Approved<br>
                        <a class="text-aqua" href="#" style="text-align:justify; cursor:default;" title="Reschedule"><i class="fa fa-square"></i></a>&nbsp;Reschedule<br>
                        <a class="text-red" href="#" style="text-align:justify; cursor:default;" title="Reject"><i class="fa fa-square"></i></a>&nbsp;Reject<br>
                        <a class="text-gray" href="#" style="text-align:justify; cursor:default;" title="Close"><i class="fa fa-square"></i></a>&nbsp;Close<br>
                        <a class="text-purple" href="#" style="text-align:justify; cursor:default;" title="Revise"><i class="fa fa-square"></i></a>&nbsp;Revise<br>
                        <a class="text-primary" href="#" style="text-align:justify; cursor:default;" title="in used"><i class="fa fa-square"></i></a>&nbsp;in used<br>
                      </div>
                      <!-- /btn-group -->
                    </div>
                  </div>
                    <?php
						if($MR_CODE_DEFA != 'All')
						{
					?>
                    <div class="box-body">
                        <div id="external-events">
							<h6 style="font-style:italic">
                            	<img src="<?php echo $MR0V; ?>" style="max-height:430px; max-width:300px; width:100%" />
                            </h6>
                        </div>
                    </div>
                    <?php
						}
					?>
                  
            	</div>
                <?php					
					$MR_CODE_DEF		= "$MR_CODE_DEFA~$RSV_STARTD2~$RSV_ENDD2";
					$get_events			= site_url('reservation/get_events/?id='.$this->url_encryption_helper->encode_url($MR_CODE_DEF));
					$get_events_b		= site_url('reservation/get_events_b/?id='.$this->url_encryption_helper->encode_url($MR_CODE_DEFA));
				?>
                <input type="hidden" name="isReadyAdd" class="form-control pull-left" id="isReadyAdd" style="width:130px" value="<?php echo $isReadyAdd; ?>">
                <input type="hidden" name="MR_CODE_DEF" class="form-control pull-left" id="MR_CODE_DEF" style="width:130px" value="<?php echo $MR_CODE_DEF; ?>">
                <input type="hidden" name="MR_CODE_DEFA" class="form-control pull-left" id="MR_CODE_DEFA" style="width:130px" value="<?php echo $MR_CODE_DEFA; ?>">
                <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel"><?php echo $CreateEvent; ?></h4>
                            </div>
                            <form class="form-horizontal" name="frm" id="frm" method="post" action="<?php echo $add_event; ?>" onSubmit="return chkForm()">
                            	<div class="modal-body">
                                    <div class="form-group">
                                        <label for="p-in" class="col-md-4 label-heading"><?php echo $StartDate; ?></label>
                                        <div class="col-md-8">
                                            <div class="input-group date">
                                                <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
                                                <input type="text" name="RSV_STARTD" class="form-control pull-left" id="RSV_STARTD" style="width:130px" onKeyUp="toTimeString1(this.value);" on onChange="chgStartT(this.value);">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="p-in" class="col-md-4 label-heading"><?php echo $EndDate; ?></label>
                                        <div class="col-md-8">
                                            <div class="input-group date">
                                                <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
                                                <input type="text" name="RSV_ENDD" class="form-control pull-left" id="RSV_ENDD" style="width:130px" onKeyUp="toTimeString2(this.value);">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="p-in" class="col-md-4 label-heading"><?php echo $Room; ?></label>
                                        <div class="col-md-8">
                                            <select name="CATEG_CODE" id="CATEG_CODE" class="form-control">
                                                <?php
                                                    $CountCTG 	= $this->db->count_all('tbl_meeting_room');
                                                    $sqlCTG 	= "SELECT MR_CODE, MR_NAME, MR_STAT, MR_STATD FROM tbl_meeting_room";
                                                    $resCTG		= $this->db->query($sqlCTG)->result();
                                                    if($CountCTG > 0)
                                                    {
                                                        foreach($resCTG as $rowCTG) :
                                                            $MR_CODE 	= $rowCTG->MR_CODE;
                                                            $MR_NAME 	= $rowCTG->MR_NAME;
                                                            $MR_STAT 	= $rowCTG->MR_STAT;
                                                            $MR_STATD 	= $rowCTG->MR_STATD;
                                                            $isDisabled	= 0;
                                                            $isDisDesc	= "";
                                                            
                                                            if($MR_STAT == 1)
                                                            {
                                                                $isDisabled	= 1;
                                                                $isDisDesc	= " - $MR_STATD";
                                                            }
                                                            ?>
                                                            <option value="<?php echo $MR_CODE; ?>" <?php if($MR_CODE == $MR_CODE_DEFA) { ?> selected <?php }?>>
                                                                <?php echo "$MR_NAME"; ?>
                                                            </option>
                                                            <?php
                                                        endforeach;
                                                    }
                                                    
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="p-in" class="col-md-4 label-heading"><?php echo $Topic; ?></label>
                                        <div class="col-md-8 ui-front">
                                            <input type="text" class="form-control" name="RSV_TITLE" id="RSV_TITLE" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="p-in" class="col-md-4 label-heading"><?php echo $Description; ?></label>
                                        <div class="col-md-8 ui-front">
                                            <textarea name="RSV_DESC" id="RSV_DESC" class="form-control" cols="30"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="p-in" class="col-md-4 label-heading"><?php echo "$Participants ($Qty)"; ?></label>
                                        <div class="col-md-8 ui-front">
                                            <input type="text" class="form-control" style="max-width:50px; text-align:right" name="RSV_QTY" id="RSV_QTY" onKeyPress="return isIntOnlyNew(event);" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="p-in" class="col-md-4 label-heading"><?php echo $Submitter; ?></label>
                                        <div class="col-md-8 ui-front">
                                            <input type="text" class="form-control" name="RSV_SUBMITTER" id="RSV_SUBMITTER" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="p-in" class="col-md-4 label-heading"><?php echo $Emaill; ?></label>
                                        <div class="col-md-8 ui-front">
                                            <input type="email" class="form-control" name="RSV_MAIL" id="RSV_MAIL" placeholder="Email" />
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-md-4 label-heading"><?php echo $InviteTo; ?></label>
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
                                              <option value="<?php echo "$Email";?>"><?php echo "$Full_Name - $Email";?></option>
                                              <?php
												 }
													}
											  ?>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="p-in" class="col-md-4 label-heading"><?php echo $Status; ?></label>
                                        <div class="col-md-8 ui-front">
                                            <select name="RSV_STAT" id="RSV_STAT" class="form-control" style="max-width:100px" onChange="selStat(this.value)">
                                                <!--<option value="1">New</option>-->
                                                <option value="2">Confirm</option>
                                                <option value="6">Close</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-primary">
                                        <i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Save; ?>
                                    </button>&nbsp;
                                    <button class="btn btn-danger" type="button" data-dismiss="modal">
                                    	<i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">
									<?php 
										if($MR_CODE_DEFA == 'All')
										{
											echo $ViewDetail;	
										}
										else
										{
											echo $UpdateeEvent; 
										}
									?>
                                </h4>
                            </div>
                            <form class="form-horizontal" name="frm" id="frm" method="post" action="<?php echo $edit_event; ?>" onSubmit="return chkForm_E()">
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="p-in" class="col-md-4 label-heading"><?php echo $StartDate; ?></label>
                                        <div class="col-md-8">
                                            <div class="input-group date">
                                                <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
                                                <input type="text" name="RSV_STARTD" class="form-control pull-left" id="RSV_STARTD_E" style="width:130px" onKeyUp="toTimeString1_E(this.value);" onChange="chgStartTime_E(this.value)" <?php if($MR_CODE_DEFA == 'All') { ?> disabled <?php } ?>>
                                                <input type="hidden" name="RSV_CODE" class="form-control pull-left" id="RSV_CODE_E">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="p-in" class="col-md-4 label-heading"><?php echo $EndDate; ?></label>
                                        <div class="col-md-8">
                                            <div class="input-group date">
                                                <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
                                                <input type="text" name="RSV_ENDD" class="form-control pull-left" id="RSV_ENDD_E" style="width:130px" onKeyUp="toTimeString2_E(this.value);" <?php if($MR_CODE_DEFA == 'All') { ?> disabled <?php } ?>>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="p-in" class="col-md-4 label-heading"><?php echo $Room; ?></label>
                                        <div class="col-md-8">
                                        <?php /*?><?php
													$CountCTG 	= $this->db->count_all('tbl_meeting_room');
                                                    $sqlCTG 	= "SELECT MR_CODE, MR_NAME, MR_STAT, MR_STATD FROM tbl_meeting_room";
                                                    $resCTG		= $this->db->query($sqlCTG)->result();
													//echo $CountCTG;
										?>
                                            <select name="CATEG_CODE" id="CATEG_CODE_E" class="form-control">
                                                <option value="">--- None ---</option>
                                                <?php
                                                    
                                                    if($CountCTG > 0)
                                                    {
                                                        foreach($resCTG as $rowCTG) :
                                                            $MR_CODEX1	= $rowCTG->MR_CODE;
                                                            $MR_NAME 	= $rowCTG->MR_NAME;
                                                            $MR_STAT 	= $rowCTG->MR_STAT;
                                                            $MR_STATD 	= $rowCTG->MR_STATD;
                                                            $isDisabled	= 0;
                                                            $isDisDesc	= "";
                                                            
                                                            if($MR_STAT == 1)
                                                            {
                                                                $isDisabled	= 1;
                                                                $isDisDesc	= " - $MR_STATD";
                                                            }
                                                            ?>
                                                            <option value="<?php echo $MR_CODEX1; ?>">
                                                                <?php echo "$MR_NAME$isDisDesc"; ?>
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
                                            
                                             <?php
													$CountCTG 	= $this->db->count_all('tbl_meeting_room');
                                                    $sqlCTG 	= "SELECT MR_CODE, MR_NAME, MR_STAT, MR_STATD FROM tbl_meeting_room";
                                                    $resCTG		= $this->db->query($sqlCTG)->result();
													//echo $CountCTG;
										?>
                                            <select name="CATEG_CODE" id="CATEG_CODE_EX" class="form-control" <?php if($MR_CODE_DEFA == 'All') { ?> disabled <?php } ?>>
                                                <option value="">--- None ---</option>
                                                <?php
                                                    
                                                    if($CountCTG > 0)
                                                    {
                                                        foreach($resCTG as $rowCTG) :
                                                            $MR_CODEX1	= $rowCTG->MR_CODE;
                                                            $MR_NAME 	= $rowCTG->MR_NAME;
                                                            $MR_STAT 	= $rowCTG->MR_STAT;
                                                            $MR_STATD 	= $rowCTG->MR_STATD;
                                                            $isDisabled	= 0;
                                                            $isDisDesc	= "";
                                                            
                                                            if($MR_STAT == 1)
                                                            {
                                                                $isDisabled	= 1;
                                                                $isDisDesc	= " - $MR_STATD";
                                                            }
                                                            ?>
                                                            <option value="<?php echo $MR_CODEX1; ?>">
                                                                <?php echo "$MR_NAME$isDisDesc"; ?>
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
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="p-in" class="col-md-4 label-heading"><?php echo $Topic; ?></label>
                                        <div class="col-md-8 ui-front">
                                            <input type="text" class="form-control" name="RSV_TITLE" id="RSV_TITLE_E" <?php if($MR_CODE_DEFA == 'All') { ?> disabled <?php } ?> />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="p-in" class="col-md-4 label-heading"><?php echo $Description; ?></label>
                                        <div class="col-md-8 ui-front">
                                            <textarea name="RSV_DESC" id="RSV_DESC_E" class="form-control" cols="30" <?php if($MR_CODE_DEFA == 'All') { ?> disabled <?php } ?>></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="p-in" class="col-md-4 label-heading"><?php echo "$Participants ($Qty)"; ?></label>
                                        <div class="col-md-8 ui-front">
                                            <input type="text" class="form-control" style="max-width:50px; text-align:right" name="RSV_QTY" id="RSV_QTY_E" onKeyPress="return isIntOnlyNew(event);" <?php if($MR_CODE_DEFA == 'All') { ?> disabled <?php } ?> />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="p-in" class="col-md-4 label-heading"><?php echo $Submitter; ?></label>
                                        <div class="col-md-8 ui-front">
                                            <input type="text" class="form-control" name="RSV_SUBMITTER" id="RSV_SUBMITTER_E" <?php if($MR_CODE_DEFA == 'All') { ?> disabled <?php } ?> />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="p-in" class="col-md-4 label-heading"><?php echo $Emaill; ?></label>
                                        <div class="col-md-8 ui-front">
                                            <input type="email" class="form-control" name="RSV_MAIL" id="RSV_MAIL_E" placeholder="Email" <?php if($MR_CODE_DEFA == 'All') { ?> disabled <?php } ?> />
                                        </div>
                                    </div>
                                    <?php /*?><select name="x[]" id="x" multiple="multiple" style="width: 100%;" class="form-control select2" style="display:none" hidden="">
                                    	<option value="0">A</option>
                                        <option value="1">B</option>
                                        <option value="2">C</option>
                                        <option value="3">D</option>
                                        <option value="4">E</option>
                                    </select><?php */?>
                                    <div class="form-group">
                                        <label class="col-md-4 label-heading"><?php echo $InviteTo; ?></label>
                                        <div class="col-md-8 ui-front">
                                            <select name="Inv_Emp[]" id="Inv_Emp_E" class="form-control select2" multiple="multiple" data-placeholder="Select Karyawan" style="width: 100%;" <?php if($MR_CODE_DEFA == 'All') { ?> disabled <?php } ?>>
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
                                              <option value="<?=$Email?>"><?php echo "$Full_Name - $Email";?></option>
                                              <?php
												 }
													}
											  ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="p-in" class="col-md-4 label-heading"><?php echo $Notes; ?>&nbsp;Admin</label>
                                        <div class="col-md-8 ui-front">
                                            <textarea name="RSV_MEMO" id="RSV_MEMO_E" class="form-control" style="max-width:350px;" cols="30" <?php if($MR_CODE_DEFA == 'All') { ?> disabled <?php } ?>></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="p-in" class="col-md-4 label-heading" id="p-in"><?php echo $Status; ?></label>
                                        <div class="col-md-8 ui-front">
                                            <!-- 
                                            <select name="RSV_STAT" id="RSV_STAT_E" class="form-control" style="max-width:100px" onChange="selStat(this.value)">
                                                <option value="1">New</option>
                                                <option value="2">Confirm</option>
                                                <option value="3">Approve</option>
                                                <option value="4">Reschedule</option>
                                                <option value="5">Reject</option>
                                                <option value="6">Close</option>
                                                <option value="8">In used</option>
                                            </select>
                                            -->
                                            
                                            <!--<input type="text" name="RSV_STATX" id="RSV_STAT_EX" class="form-control" style="max-width:150px" readonly>-->
                                            <select name="RSV_STAT" id="RSV_STAT_E" class="form-control" style="max-width:150px" onChange="selStat(this.value)" <?php if($MR_CODE_DEFA == 'All') { ?> disabled <?php } ?>>
                                            
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
                                    <button class="btn btn-primary" id="btn_update_e" <?php if($MR_CODE_DEFA == 'All') { ?>style="display:none" <?php } ?>>
                                        <i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Update; ?>
                                    </button>&nbsp;
                                    <button class="btn btn-danger" type="button" data-dismiss="modal">
                                    	<i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>
                                    </button>
                                </div>
                            </form>
                            <?php /*?><form method="post" name="invEmail" id="invEmail">
        	<input type="text" name="strEmail" id="strEmail" value="">
            <input type="submit" name="invSubmit" id="invSubmit">
        </form><?php */?>
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
        <form method="post" name="sendDate" id="sendDate" class="form-user" action="<?php echo $get_events_b; ?>" style="display:none">		
            <table>
                <tr>
                    <td>
                        <input type="text" name="date_start" id="date_start" value="">
                        <input type="text" name="date_end" id="date_end" value="">
                    </td>
                    <td><a class="tombol-date" id="dateClass">Simpan</a></td>
                </tr>
            </table>
        </form>
        
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
	
		$(function ()
		{
			/* initialize the external events
			-----------------------------------------------------------------*/
			function ini_events(ele) 
			{
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
		
			ini_events($('#external-events div.external-event'));
		
			/* initialize the calendar
			 -----------------------------------------------------------------*/
			//Date for the calendar events (dummy data)
			var date_last_clicked = null;
			var date 	= new Date();
			var d 		= date.getDate(), m = date.getMonth(), y = date.getFullYear();
			var MR_CODE_DEFA = document.getElementById('MR_CODE_DEFA').value;
			var defaultCal = 'agendaWeek';
			//alert(MR_CODE_DEFA);
			if(MR_CODE_DEFA == 'All')
			{
				defaultCal = 'listWeek';
			}
				
			$('#calendar').fullCalendar(
			{
				header: 
				{
					left: 'prev,next today',
					center: 'title',
					right: 'agendaWeek,agendaDay,month,listWeek'
				},
				buttonText: 
				{
					today: 'Today',
					month: 'Month',
					week: 'Week',
					day: 'Day',
					list: 'List Week'
				},
				defaultView: defaultCal,
				allDaySlot: false,
				timeFormat: 'HH:mm',
				slotLabelFormat: 'HH:mm',
				
				eventSources: [
					 {
						 events: function(start, end, timezone, callback) {
							 $.ajax({
							 url: '<?php echo $get_events; ?>',
							 dataType: 'json',
							 data: {
							 // our hypothetical feed requires UNIX timestamps
							 start: start.unix(),
							 end: end.unix()
							 },
							 success: function(msg) {
								 var events = msg.events;
								 callback(events);
							 }
							 });
						 }
					 },
				 ],
				 
				dayClick: function(date, jsEvent, view) 
				{
					var dateNow 	= new Date();
					var isReadyAdd	= document.getElementById('isReadyAdd').value;
					var myDate		= date.format('YYYY/MM/DD HH:mm');
					var dateSelect 	= new Date(myDate);
					
					var myEndDate1 	= moment(dateSelect).add(60, 'm').toDate();
					var myEndDate2 	= new Date(myEndDate1);
					var myEndDate	= moment(myEndDate2).format('YYYY/MM/DD HH:mm');
					
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
					
					document.getElementById('date_start').value = myDate;
					document.getElementById('date_end').value = myEndDate;
					
					//document.getElementById('dateClass').click();
					
					document.getElementById('RSV_STARTD').value = myDate;
					document.getElementById('RSV_ENDD').value = myEndDate;
					date_last_clicked = $(this);
					$(this).css('background-color', '#bed7f3');
					$('#addModal').modal();
				},
				
				eventClick: function(event, jsEvent, view) 
				{
					//$('#name').val(event.title);
					//$('#description').val(event.description);
					var isReadyAdd	= document.getElementById('isReadyAdd').value;
					
					var RSV_ID			= event.RSV_ID;
					var RSV_CODE 		= event.RSV_CODE;
					var RSV_CATEG		= event.RSV_CATEG;
					var CATEG_CODE 		= event.CATEG_CODE;
					var RSV_STARTD 		= event.RSV_STARTD;
					var RSV_ENDD 		= event.RSV_ENDD;
					var CATEG_CODE2 	= event.CATEG_CODE2;
					var RSV_STARTD2 	= event.RSV_STARTD2;
					var RSV_ENDD2 		= event.RSV_ENDD2;
					var RSV_TITLE 		= event.RSV_TITLE;
					var RSV_DESC 		= event.RSV_DESC;
					var RSV_QTY 		= event.RSV_QTY;
					var RSV_SUBMITTER 	= event.RSV_SUBMITTER;
					var RSV_MEMO		= event.RSV_MEMO;
					var RSV_MAIL 		= event.RSV_MAIL;
					//var Inv_Emp_ID		= event.Inv_Emp_ID;
					//alert('Inv_Emp_ID = '+Inv_Emp_ID)
					var Inv_Email		= event.Inv_Email;
					var RSV_STAT 		= event.RSV_STAT;
					var arrEmail		= Inv_Email.split(';');
					//var lenEmail		= arrEmail.length;
					//alert(arrEmail);
					
					$('#Inv_Emp_E').select2();
					
					if(Inv_Email != "")
					{
						$('#Inv_Emp_E').select2().select2('val', [arrEmail]);
						//$('#Inv_Emp_E').select2().val([arrEmail]).trigger('click');
						//$("#Inv_Emp_E > option").prop("selected","selected");
					}
					
					//$('.select2').select2().select2('val', [arrEmail]);
					
					//$('.select2').select2().val([arrEmail]).trigger('change');
					
					
					var myDateS = moment(event.RSV_STARTD2).format('YYYY/MM/DD HH:mm');
					var myDateE = moment(event.RSV_ENDD2).format('YYYY/MM/DD HH:mm');
					
					
					document.getElementById('date_start').value 	= myDateS;
					document.getElementById('date_end').value 		= myDateE;
					
					document.getElementById('RSV_CODE_E').value 	= RSV_CODE;
					document.getElementById('RSV_STARTD_E').value 	= myDateS;
					document.getElementById('RSV_ENDD_E').value 	= myDateE;
					
					//document.getElementById('CATEG_CODE_E').value 	= CATEG_CODE;
					var CATEG	= document.getElementById('CATEG_CODE_EX');
					var lenCATEG	= CATEG.length;
					//alert(lenCATEG);
					for(var i=0; i<lenCATEG; i++)
					{
						var optCATEG = CATEG.options[i];
						//alert(optCATEG+' = '+CATEG_CODE);
						if(optCATEG.value == CATEG_CODE)
						{
							//alert(optCATEG+' = '+CATEG_CODE);
							optCATEG.selected = true;
							//alert(optCATEG.selected);
						}
					}
					document.getElementById('RSV_TITLE_E').value 	= RSV_TITLE;
					document.getElementById('RSV_DESC_E').value 	= RSV_DESC;
					document.getElementById('RSV_QTY_E').value 		= RSV_QTY;
					document.getElementById('RSV_SUBMITTER_E').value = RSV_SUBMITTER;
					document.getElementById('RSV_MEMO_E').value 	= RSV_MEMO;
					document.getElementById('RSV_MAIL_E').value 	= RSV_MAIL;
					document.getElementById('Inv_Emp_E').value		= Inv_Email;
					//document.getElementById('strEmail').value		= Inv_Emp_Email;
					document.getElementById('RSV_STAT_E').value 	= RSV_STAT;
					//document.getElementById('listDataV').innerHTML	= listData;
					document.getElementById('dateClass').click();
					
					
				//$("#invEmail").submit();
					
					if(RSV_STAT == 1)
					{
						//document.getElementById('RSV_STAT_EX').value 	= 'New';
						document.getElementById('btn_update_e').disabled = true;
						//document.getElementById('RSV_STAT_E').style.display = 'none';
					}
					else if(RSV_STAT == 2)
					{
						document.getElementById('RSV_STAT_E').options[0].hidden = false; //Confirm
						document.getElementById('RSV_STAT_E').options[1].hidden = true; // Approve
						document.getElementById('RSV_STAT_E').options[2].hidden = true; // Reschedule
						document.getElementById('RSV_STAT_E').options[3].hidden = true; // Reject
						document.getElementById('RSV_STAT_E').options[4].hidden = false; // Close
						document.getElementById('RSV_STAT_E').options[5].hidden = true; // Revise
						document.getElementById('RSV_STAT_E').options[6].hidden = true; // In Used
						document.getElementById('btn_update_e').disabled = true;
					}
					else if(RSV_STAT == 3)
					{
						document.getElementById('RSV_STAT_E').options[0].hidden = true; //Confirm
						document.getElementById('RSV_STAT_E').options[1].hidden = false; // Approve
						document.getElementById('RSV_STAT_E').options[2].hidden = true; // Reschedule
						document.getElementById('RSV_STAT_E').options[3].hidden = true; // Reject
						document.getElementById('RSV_STAT_E').options[4].hidden = true; // Close
						document.getElementById('RSV_STAT_E').options[5].hidden = true; // Revise
						document.getElementById('RSV_STAT_E').options[6].hidden = true; // In Used
						document.getElementById('btn_update_e').disabled = true;
					}
					else if(RSV_STAT == 4)
					{
						document.getElementById('RSV_STAT_E').options[0].hidden = false; //Confirm
						document.getElementById('RSV_STAT_E').options[1].hidden = true; // Approve
						document.getElementById('RSV_STAT_E').options[2].hidden = false; // Reschedule
						document.getElementById('RSV_STAT_E').options[3].hidden = true; // Reject
						document.getElementById('RSV_STAT_E').options[4].hidden = true; // Close
						document.getElementById('RSV_STAT_E').options[5].hidden = true; // Revise
						document.getElementById('RSV_STAT_E').options[6].hidden = true; // In Used
						document.getElementById('btn_update_e').disabled = false;
					}
					else if(RSV_STAT == 5)
					{
						document.getElementById('RSV_STAT_E').options[0].hidden = true; //Confirm
						document.getElementById('RSV_STAT_E').options[1].hidden = true; // Approve
						document.getElementById('RSV_STAT_E').options[2].hidden = true; // Reschedule
						document.getElementById('RSV_STAT_E').options[3].hidden = false; // Reject
						document.getElementById('RSV_STAT_E').options[4].hidden = true; // Close
						document.getElementById('RSV_STAT_E').options[5].hidden = true; // Revise
						document.getElementById('RSV_STAT_E').options[6].hidden = true; // In Used
						document.getElementById('btn_update_e').disabled = true;
					}
					else if(RSV_STAT == 6)
					{
						document.getElementById('RSV_STAT_E').options[0].hidden = true; //Confirm
						document.getElementById('RSV_STAT_E').options[1].hidden = true; // Approve
						document.getElementById('RSV_STAT_E').options[2].hidden = true; // Reschedule
						document.getElementById('RSV_STAT_E').options[3].hidden = true; // Reject
						document.getElementById('RSV_STAT_E').options[4].hidden = false; // Close
						document.getElementById('RSV_STAT_E').options[5].hidden = true; // Revise
						document.getElementById('RSV_STAT_E').options[6].hidden = true; // In Used
						document.getElementById('btn_update_e').disabled = true;
					}
					else if(RSV_STAT == 7)
					{
						document.getElementById('RSV_STAT_E').options[0].hidden = false; //Confirm
						document.getElementById('RSV_STAT_E').options[1].hidden = true; // Approve
						document.getElementById('RSV_STAT_E').options[2].hidden = true; // Reschedule
						document.getElementById('RSV_STAT_E').options[3].hidden = true; // Reject
						document.getElementById('RSV_STAT_E').options[4].hidden = true; // Close
						document.getElementById('RSV_STAT_E').options[5].hidden = false; // Revise
						document.getElementById('RSV_STAT_E').options[6].hidden = true; // In Used
						document.getElementById('btn_update_e').disabled = false;
					}
					else if(RSV_STAT == 8)
					{
						document.getElementById('RSV_STAT_E').options[0].hidden = true; //Confirm
						document.getElementById('RSV_STAT_E').options[1].hidden = true; // Approve
						document.getElementById('RSV_STAT_E').options[2].hidden = true; // Reschedule
						document.getElementById('RSV_STAT_E').options[3].hidden = true; // Reject
						document.getElementById('RSV_STAT_E').options[4].hidden = true; // Close
						document.getElementById('RSV_STAT_E').options[5].hidden = true; // Revise
						document.getElementById('RSV_STAT_E').options[6].hidden = false; // In Used
						document.getElementById('btn_update_e').disabled = true;
					}
					
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
				eventRender: function(event, element) {
					element.attr('title', event.tooltip);
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
		
		$('#Inv_Emp').select2();
		
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