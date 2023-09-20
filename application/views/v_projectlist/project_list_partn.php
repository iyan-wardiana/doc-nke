<?php
/* 
 	* Author		= Dian Hermanto
 	* Create Date	= 19 Juni 2021
 	* File Name		= project_list_partn.php
 	* Location		= -
*/
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody    = $this->session->userdata['appBody'];
$comp_color	= $this->session->userdata['comp_color'];

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

$selProject = '';
if(isset($_POST['submit']))
{
	$selProject = $_POST['selProject'];
}
	
// Project List
$sqlPLC	= "tbl_project";
$resPLC	= $this->db->count_all($sqlPLC);

$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
$sqlPL 		= "SELECT proj_Number, PRJCODE, PRJNAME
			FROM tbl_project WHERE PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
			ORDER BY PRJNAME";
$resPL		= $this->db->query($sqlPL)->result();
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

	  	<link rel="stylesheet" href="<?php echo base_url() . 'assets/css/pbar/css/cssprogress.css'; ?>">
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
    		if($TranslCode == 'Code')$Code = $LangTransl;
    		if($TranslCode == 'Description')$Description = $LangTransl;
    		if($TranslCode == 'StartDate')$StartDate = $LangTransl;
    		if($TranslCode == 'EndDate')$EndDate = $LangTransl;
    		if($TranslCode == 'BudList')$BudList = $LangTransl;
    		if($TranslCode == 'Project')$Project = $LangTransl;
    		if($TranslCode == 'Remarks')$Remarks = $LangTransl;
        	if($TranslCode == 'CompList')$CompList = $LangTransl;
        	if($TranslCode == 'submitted')$submitted = $LangTransl;
        	if($TranslCode == 'Paid')$Paid = $LangTransl;
    	endforeach;
    	
    	if(!empty($mnName))
    		$h1_title 	= $mnName;
    	else
    		$h1_title 	= '';
    ?>

    <style>
        .search-table, td, th {
            border-collapse: collapse;
        }
        .search-table-outter { overflow-x: scroll; }
    </style>
    <?php

		$comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
      	<section class="content-header">
        	<h1>
            <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/project.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $mnName; ?>
          	</h1>
      	</section>

        <section class="content">
            <div class="box">
                <div class="box-body">
                    <div class="search-table-outter">
                        <table id="example" class="table table-bordered table-striped table-hover table-responsive search-table inner" width="100%">
                            <thead>
                                <tr>
									<th width="5%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Code ?> </th>
									<th width="26%" style="text-align:center; vertical-align:middle"><?php echo $Description ?> </th>
									<th width="40%" style="text-align:center; vertical-align:middle"><?php echo $Remarks ?> </th>
									<th width="15%" style="text-align:center; vertical-align:middle" nowrap><?php echo $submitted ?> </th>
									<th width="15%" style="text-align:center; vertical-align:middle"><?php echo $Paid ?> </th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        <?php
                        	if(isset($mnCode))
                        		$mn_Code = "$mnCode";
                        	else
                        		$mn_Code = "";

                        	if(isset($MenuApp))
                        		$mn_App = "$MenuApp";
                        	else
                        		$mn_App = "";

                        	if(isset($jrnCat))
                        		$jrn_Cat = "$jrnCat";
                        	else
                        		$jrn_Cat = "";
                        ?>
                    </div>
                </div>
            </div>
        	<?php
        		$this->db->select('APPLEV');
				$resGlobal = $this->db->get('tglobalsetting')->result();
				foreach($resGlobal as $row) :
				    $APPLEV = $row->APPLEV;
				endforeach;
				$act_lnk = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        		if($DefEmp_ID == 'D15040004221')
                	echo "<font size='1'><i>$act_lnk</i></font>";

                echo $APPLEV;
            ?>
        </section>
    </body>
</html>

<script>
	$(document).ready(function() {
    $('#example').DataTable( {
        "processing": true,
        "serverSide": true,
		//"scrollX": false,
		"autoWidth": true,
		"filter": true,
        "ajax": "<?php echo site_url('c_project/lst180c2hprj/get_AllDataPRJ_partn/?id='.$secURL.'&mnCode='.$mn_Code.'&MenuApp='.$mn_App.'&jrnCat='.$jrn_Cat)?>",
        "type": "POST",
		//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
		"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
		"columnDefs": [	{ targets: [3], className: 'dt-body-center' }
					  ],
		"order": [[ 2, "desc" ]],
		"language": {
            "infoFiltered":"",
            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
        },
		} );
	} );
	
	$(function () {
	    /* jQueryKnob */

	    $(".knob").knob({
			/*change : function (value) {
			//console.log("change : " + value);
			},
			release : function (value) {
			console.log("release : " + value);
			},
			cancel : function () {
			console.log("cancel : " + this.value);
			},*/
	      	draw: function ()
	      	{
		        // "tron" case
		        if (this.$.data('skin') == 'tron')
		        {
		          	var a = this.angle(this.cv)  // Angle
					, sa = this.startAngle          // Previous start angle
					, sat = this.startAngle         // Start angle
					, ea                            // Previous end angle
					, eat = sat + a                 // End angle
					, r = true;

		          	this.g.lineWidth = this.lineWidth;

					this.o.cursor
					&& (sat = eat - 0.3)
					&& (eat = eat + 0.3);

		          	if (this.o.displayPrevious)
		          	{
						ea = this.startAngle + this.angle(this.value);
						this.o.cursor
						&& (sa = ea - 0.3)
						&& (ea = ea + 0.3);
						this.g.beginPath();
						this.g.strokeStyle = this.previousColor;
						this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sa, ea, false);
						this.g.stroke();
					}

					this.g.beginPath();
					this.g.strokeStyle = r ? this.o.fgColor : this.fgColor;
					this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sat, eat, false);
					this.g.stroke();

					this.g.lineWidth = 2;
					this.g.beginPath();
					this.g.strokeStyle = this.o.fgColor;
					this.g.arc(this.xy, this.xy, this.radius - this.lineWidth + 1 + this.lineWidth * 2 / 3, 0, 2 * Math.PI, false);
					this.g.stroke();

					return false;
		        }
	      	}
	    });
	    /* END JQUERY KNOB */

	    //INITIALIZE SPARKLINE CHARTS
	    $(".sparkline").each(function () {
	      	var $this = $(this);
	      	$this.sparkline('html', $this.data());
	    });

	    /* SPARKLINE DOCUMENTATION EXAMPLES http://omnipotent.net/jquery.sparkline/#s-about */
	    drawDocSparklines();
	    drawMouseSpeedDemo();
	});

  	function drawDocSparklines()
  	{
	    // Bar + line composite charts
	    $('#compositebar').sparkline('html', {type: 'bar', barColor: '#aaf'});
	    $('#compositebar').sparkline([4, 1, 5, 7, 9, 9, 8, 7, 6, 6, 4, 7, 8, 4, 3, 2, 2, 5, 6, 7],
	        {composite: true, fillColor: false, lineColor: 'red'});


	    // Line charts taking their values from the tag
	    $('.sparkline-1').sparkline();

	    // Larger line charts for the docs
	    $('.largeline').sparkline('html',
	        {type: 'line', height: '2.5em', width: '4em'});

	    // Customized line chart
	    $('#linecustom').sparkline('html',
	        {
	         	 height: '1.5em', width: '8em', lineColor: '#f00', fillColor: '#ffa',
	          	minSpotColor: false, maxSpotColor: false, spotColor: '#77f', spotRadius: 3
	        });

	    // Bar charts using inline values
	    $('.sparkbar').sparkline('html', {type: 'bar'});

	    $('.barformat').sparkline([1, 3, 5, 3, 8], {
	      	type: 'bar',
	      	tooltipFormat: '{{value:levels}} - {{value}}',
	      	tooltipValueLookups: {
	        levels: $.range_map({':2': 'Low', '3:6': 'Medium', '7:': 'High'})
	      	}
    	});

	    // Tri-state charts using inline values
	    $('.sparktristate').sparkline('html', {type: 'tristate'});
	    $('.sparktristatecols').sparkline('html',
	        {type: 'tristate', colorMap: {'-2': '#fa7', '2': '#44f'}});

	    // Composite line charts, the second using values supplied via javascript
	    $('#compositeline').sparkline('html', {fillColor: false, changeRangeMin: 0, chartRangeMax: 10});
	    $('#compositeline').sparkline([4, 1, 5, 7, 9, 9, 8, 7, 6, 6, 4, 7, 8, 4, 3, 2, 2, 5, 6, 7],
	        {composite: true, fillColor: false, lineColor: 'red', changeRangeMin: 0, chartRangeMax: 10});

	    // Line charts with normal range marker
	    $('#normalline').sparkline('html',
	        {fillColor: false, normalRangeMin: -1, normalRangeMax: 8});
	    $('#normalExample').sparkline('html',
	        {fillColor: false, normalRangeMin: 80, normalRangeMax: 95, normalRangeColor: '#4f4'});

	    // Discrete charts
	    $('.discrete1').sparkline('html',
	        {type: 'discrete', lineColor: 'blue', xwidth: 18});
	    $('#discrete2').sparkline('html',
	        {type: 'discrete', lineColor: 'blue', thresholdColor: 'red', thresholdValue: 4});

	    // Bullet charts
	    $('.sparkbullet').sparkline('html', {type: 'bullet'});

	    // Pie charts
	    $('.sparkpie').sparkline('html', {type: 'pie', height: '1.0em'});

	    // Box plots
	    $('.sparkboxplot').sparkline('html', {type: 'box'});
	    $('.sparkboxplotraw').sparkline([1, 3, 5, 8, 10, 15, 18],
	        {type: 'box', raw: true, showOutliers: true, target: 6});

	    // Box plot with specific field order
	    $('.boxfieldorder').sparkline('html', {
	      type: 'box',
	      tooltipFormatFieldlist: ['med', 'lq', 'uq'],
	      tooltipFormatFieldlistKey: 'field'
	    });

	    // click event demo sparkline
	    $('.clickdemo').sparkline();
	    $('.clickdemo').bind('sparklineClick', function (ev) {
	      var sparkline = ev.sparklines[0],
	          region = sparkline.getCurrentRegionFields();
	      value = region.y;
	      alert("Clicked on x=" + region.x + " y=" + region.y);
	    });

	    // mouseover event demo sparkline
	    $('.mouseoverdemo').sparkline();
	    $('.mouseoverdemo').bind('sparklineRegionChange', function (ev) {
	      var sparkline = ev.sparklines[0],
	          region = sparkline.getCurrentRegionFields();
	      value = region.y;
	      $('.mouseoverregion').text("x=" + region.x + " y=" + region.y);
	    }).bind('mouseleave', function () {
	      $('.mouseoverregion').text('');
	    });
  	}
  	/**
		** Draw the little mouse speed animated graph
		** This just attaches a handler to the mousemove event to see
		** (roughly) how far the mouse has moved
		** and then updates the display a couple of times a second via
		** setTimeout()
  	 **/
  	function drawMouseSpeedDemo()
  	{
		var mrefreshinterval = 500; // update display every 500ms
		var lastmousex = -1;
		var lastmousey = -1;
		var lastmousetime;
		var mousetravel = 0;
		var mpoints = [];
		var mpoints_max = 30;
		$('html').mousemove(function (e) {
		var mousex = e.pageX;
		var mousey = e.pageY;
		if (lastmousex > -1) {
		mousetravel += Math.max(Math.abs(mousex - lastmousex), Math.abs(mousey - lastmousey));
		}
		lastmousex = mousex;
		lastmousey = mousey;
		});
		var mdraw = function () {
		var md = new Date();
		var timenow = md.getTime();
		if (lastmousetime && lastmousetime != timenow) {
		var pps = Math.round(mousetravel / (timenow - lastmousetime) * 1000);
		mpoints.push(pps);
		if (mpoints.length > mpoints_max)
		mpoints.splice(0, 1);
		mousetravel = 0;
		$('#mousespeed').sparkline(mpoints, {width: mpoints.length * 2, tooltipSuffix: ' pixels per second'});
		}
		lastmousetime = timenow;
		setTimeout(mdraw, mrefreshinterval);
		};
		// We could use setInterval instead, but I prefer to do it this way
		setTimeout(mdraw, mrefreshinterval);
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