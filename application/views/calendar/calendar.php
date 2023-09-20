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
		if($TranslCode == 'Code')$Code = $LangTransl;
		if($TranslCode == 'Title')$Title = $LangTransl;
		if($TranslCode == 'Procedure')$Procedure = $LangTransl;
		if($TranslCode == 'Requested')$Requested = $LangTransl;
		if($TranslCode == 'Approved')$Approved = $LangTransl;
		if($TranslCode == 'Awaiting')$Awaiting = $LangTransl;
		if($TranslCode == 'YourBook')$YourBook = $LangTransl;
		if($TranslCode == 'None')$None = $LangTransl;
		if($TranslCode == 'Notes')$Notes = $LangTransl;
	endforeach;
	
	if($LangID == 'IND')
	{
		$h1_title		= 'Pemesanan';
		$h2_title		= 'daftar jadwal';
		$MeetingRoom	= 'Pemesanan Ruang Rapat';
		$RestRoom		= 'Pemesanan Kamar';
		$Vehicle		= 'Pemesanan Kendaraan';
		$BookingNow		= 'Pesan Sekarang';
		$Available		= 'Tersedia';
		$EventList		= 'Daftar Kegiatan';
		$RemoveList		= 'hapus setelah didrag';
		$CreateEvent	= 'Buat Agenda Baru';
		$ChooseColor	= 'Pilih Warna';
	}
	else
	{
		$h1_title		= 'Reservation';	
		$h2_title		= 'schedules list';	
		$MeetingRoom	= 'Meeting Room Reservation';
		$RestRoom		= 'Pemesanan Kamar';
		$Vehicle		= 'Pemesanan Kendaraan';
		$BookingNow		= 'Book Now';
		$Available		= 'Available';
		$EventList		= 'Events List';
		$RemoveList		= 'remove after drop';
		$CreateEvent	= 'Create New Event';
		$ChooseColor	= 'Select Color';
	}
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
            			<h4 class="box-title"><?php echo $EventList; ?></h4>
            		</div>
                    <div class="box-body">
                        <div id="external-events">
                        	<?php
								$sqltSchC	= "tbl_reservation WHERE RSV_CATEG = 'MR' AND RSV_STAT = '2'";
								$restSchC	= $this->db->count_all($sqltSchC);
								if($restSchC > 0)
								{
									$sqltSch	= "SELECT RSV_TITLE FROM tbl_reservation WHERE RSV_CATEG = 'MR' AND RSV_STAT = '2'";
									$restSch	= $this->db->query($sqltSch)->result();
									foreach($restSch as $rowSch):
										  $RSV_TITLE	= $rowSch->RSV_TITLE;
									?>
										<div class="external-event bg-green"><?php echo $RSV_TITLE; ?></div>
										<div class="checkbox">
											<label for="drop-remove">
												<input type="checkbox" id="drop-remove">
												<?php echo $RemoveList; ?>
											</label>
										</div>
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
            	</div>
                
                <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                             <div class="modal-header">
                                 <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                 <h4 class="modal-title" id="myModalLabel">Add Calendar Event</h4>
                             </div>
                             <div class="modal-body">
                                  Form Goes Here
                             </div>
                             <div class="modal-footer">
                                 <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                 <button type="button" class="btn btn-primary">Save changes</button>
                             </div>
                        </div>
                    </div>
                </div>
                    
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $CreateEvent; ?></h3>
                        <h6 style="font-style:italic"><?php echo $ChooseColor; ?></h6>
                    </div>
                    <div class="box-body">
                        <div class="btn-group" style="width: 100%; margin-bottom: 10px;">
                            <ul class="fc-color-picker" id="color-chooser">
                                <li><a class="text-aqua" href="#"><i class="fa fa-square"></i></a></li>
                                <li><a class="text-blue" href="#"><i class="fa fa-square"></i></a></li>
                                <li><a class="text-light-blue" href="#"><i class="fa fa-square"></i></a></li>
                                <li><a class="text-teal" href="#"><i class="fa fa-square"></i></a></li>
                                <li><a class="text-yellow" href="#"><i class="fa fa-square"></i></a></li>
                                <li><a class="text-orange" href="#"><i class="fa fa-square"></i></a></li>
                                <li><a class="text-green" href="#"><i class="fa fa-square"></i></a></li>
                                <li><a class="text-lime" href="#"><i class="fa fa-square"></i></a></li>
                                <li><a class="text-red" href="#"><i class="fa fa-square"></i></a></li>
                                <li><a class="text-purple" href="#"><i class="fa fa-square"></i></a></li>
                                <li><a class="text-fuchsia" href="#"><i class="fa fa-square"></i></a></li>
                                <li><a class="text-muted" href="#"><i class="fa fa-square"></i></a></li>
                                <li><a class="text-navy" href="#"><i class="fa fa-square"></i></a></li>
                            </ul>
                        </div>
                        <div class="box-body chart-responsive">
                        	<div class="form-group">
                                <input id="new-event1" type="text" class="form-control" placeholder="<?php echo $Title; ?>">
                            </div>
                        	<div class="form-group">
                                <input id="new-event2" type="text" class="form-control" placeholder="<?php echo $Notes; ?>">
                            </div>
                            <div class="form-group">
                                <button id="add-new-event" type="button" class="btn btn-primary btn-flat"><?php echo $Add; ?></button>
                            </div>
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
    </section>
    
    <!-- jQuery 2.2.3 -->
    <script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/jQuery/jquery-2.2.3.min.js'; ?>"></script>
    <!-- Bootstrap 3.3.6 -->
    <script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/bootstrap/js/bootstrap.min.js'; ?>"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
    <!-- Slimscroll -->
    <script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/slimScroll/jquery.slimscroll.min.js'; ?>"></script>
    <!-- FastClick -->
    <script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/fastclick/fastclick.js'; ?>"></script>
    <!-- AdminLTE App -->
    <script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/js/app.min.js'; ?>"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/js/demo.js'; ?>"></script>
    <!-- fullCalendar 2.2.5 -->
    <script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/fullcalendar/moment.min.js'; ?>"></script>
    <script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/fullcalendar/fullcalendar.min.js'; ?>"></script>
    <!-- Page specific script -->
    <script>
		var date_last_clicked = null;

		$('#calendar').fullCalendar(
		{
			eventSources: [
					{
						color: '#18b9e6',   
						textColor: '#000000',
						events: []
					}
				],
				
			events: [
                    {
                        title: 'Event 1',
                        start: '2018-02-09'
                    },
                    {
                        title: 'Event 2',
                        start: '2018-02-19'
                    }
                ],
			dayClick: function(date, jsEvent, view) 
			{
				date_last_clicked = $(this);
				$(this).css('background-color', '#bed7f3');
				$('#addModal').modal();
			}
		});
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