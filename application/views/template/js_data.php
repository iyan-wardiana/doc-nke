</div><!-- /.content-wrapper -->
<?php
	$sqlApp 		= "SELECT * FROM tappname";
	$resultaApp = $this->db->query($sqlApp)->result();
	foreach($resultaApp as $therow) :
		$vend_app	= $therow->vend_app;
		$vend_link	= $therow->vend_link;
		$app_link	= $therow->app_link;
		$foot_name	= $therow->foot_name;
		$version 	= $therow->version;
		$year 		= $therow->year;
	endforeach;
?>

<!-- <footer class="main-footer" style="display: none;">
    <div class="pull-right hidden-xs">
        <strong><a href="<?php echo $vend_link; ?>" target="_blank"><?php echo $vend_app; ?></a></strong> <b>v<?php echo $version; ?></b>
    </div>
    <strong>Copyright &copy; <?php echo $year; ?> <a href="<?php echo $app_link; ?>" target="_blank"><?php echo $foot_name; ?></a>.</strong> All rights reserved.
</footer> -->
</div><!-- ./wrapper -->

<!-- jQuery 2.1.3 -->
<?php /*?><script src="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/jQuery/jquery-2.2.3.min.js') ?>"></script><?php */?>
<!-- Bootstrap 3.3.2 JS -->
<script src="<?php echo base_url('assets/AdminLTE-2.0.5/bootstrap/js/bootstrap.min.js') ?>" type="text/javascript"></script>
<!-- SlimScroll -->
<script src="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/slimScroll/jquery.slimScroll.min.js') ?>" type="text/javascript"></script>
<!-- FastClick -->
<script src='<?php echo base_url('assets/AdminLTE-2.0.5/plugins/fastclick/fastclick.min.js') ?>'></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url('assets/AdminLTE-2.0.5/dist/js/app.min.js') ?>" type="text/javascript"></script>