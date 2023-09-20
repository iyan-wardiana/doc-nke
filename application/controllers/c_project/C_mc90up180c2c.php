<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 06 September 2018
 * File Name	= C_mc90up180c2c.php
 * Notes		= -
*/

class C_mc90up180c2c extends CI_Controller
{
  	function __construct() // GOOD
	{
		parent::__construct();

		setlocale(LC_ALL, 'id-ID', 'id_ID');

		$this->load->model('m_project/m_project_mc/m_project_mcg', '', TRUE);
		$this->load->model('m_updash/m_updash', '', TRUE);
		$this->load->model('m_journal/m_journal', '', TRUE);
	
		$this->data['UserID'] 		= $this->session->userdata['Emp_ID'];
		$this->data['appName'] 		= $this->session->userdata['appName'];
		$this->data['ISCREATE'] 	= $this->session->userdata['ISCREATE'];
		$this->data['ISAPPROVE'] 	= $this->session->userdata['ISAPPROVE'];
		$this->data['LangID']		= $this->session->userdata['LangID'];

		function cut_text2($var, $len = 200, $txt_titik = "-")
		{
			$var1	= explode("</p>",$var);
			$var	= $var1[0];
			if (strlen ($var) < $len) 
			{ 
				return $var; 
			}
			if (preg_match ("/(.{1,$len})\s/", $var, $match)) 
			{
				return $match [1] . $txt_titik;
			}
			else
			{
				return substr ($var, 0, $len) . $txt_titik;
			}
		}
		
		// DEFAULT PROJECT
			$sqlISHO 		= "SELECT PRJCODE FROM tbl_project WHERE isHO = 1 AND PRJSTAT = 1";
			$resISHO		= $this->db->query($sqlISHO)->result();
			foreach($resISHO as $rowISHO):
				$PRJCODE	= $rowISHO->PRJCODE;
			endforeach;
			$this->data['PRJCODE']		= $PRJCODE;
			$this->data['PRJCODE_HO']	= $PRJCODE;
		
		// GET PROJECT SELECT
			if(isset($_GET['id']))
			{
				$collDATA		= $_GET['id'];
				$EXP_COLLD1		= $this->url_encryption_helper->decode_url($collDATA);
			}
			else
			{
				$EXP_COLLD1		= '';
			}

			$EXP_COLLD		= explode('~', $EXP_COLLD1);	
			$C_COLLD1		= count($EXP_COLLD);
			if($C_COLLD1 > 1)
			{
				$EXP_COLLD1	= str_replace('~', '', $EXP_COLLD1);
				$PRJCODE	= $EXP_COLLD[0];
			}
			else
			{
				$PRJCODE	= $EXP_COLLD1;
			}
		
			$sqlISHO 		= "SELECT PRJCODE, PRJCODE_HO FROM tbl_project_budg WHERE PRJCODE = '$PRJCODE' AND PRJSTAT = 1";
			$resISHO		= $this->db->query($sqlISHO)->result();
			foreach($resISHO as $rowISHO):
				$this->data['PRJCODE']		= $rowISHO->PRJCODE;
				$this->data['PRJCODE_HO']	= $rowISHO->PRJCODE_HO;
			endforeach;
	}

 	public function index() // G
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_project/c_mc90up180c2c/prj180c2cls/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function prj180c2cls() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_project/m_spk/m_spk', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "MC Grouping";
			}
			else
			{
				$data["h1_title"] 	= "MC Grouping";
			}
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN017';	 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_project/c_mc90up180c2c/gall180c2cmg/?id=";
			
			$data["secVIEW"]	= 'v_projectlist/project_list';
			if($this->session->userdata['nSELP'] == 0)
			{
				$PRJCODE	= $this->session->userdata['proj_Code'];
				$url		= site_url($data["secURL"].$this->url_encryption_helper->encode_url($PRJCODE));
				redirect($url);
			}
			else
			{
				$this->load->view($data["secVIEW"], $data);
			}
		}
		else
		{
			redirect('__l1y');
		}
	}

	function gall180c2cmg() // G
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODEX			= $_GET['id'];
			$PRJCODE			= $this->url_encryption_helper->decode_url($PRJCODEX);
			
			$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
			
			$LangID 			= $this->session->userdata['LangID'];
			// GET MENU DESC
				$mnCode				= 'MN361';
				$MenuCode			= 'MN361';
				$data["MenuCode"] 	= 'MN361';
				$data["MenuApp"] 	= 'MN361';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;

			$data['link'] 			= array('link_back' => anchor('c_project/c_mc90up180c2c/','<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Back" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 		= site_url('c_project/c_mc90up180c2c/');
			$data['PRJCODE'] 		= $PRJCODE;
			//$num_rows 				= $this->m_project_mcg->count_all_num_rowsProjMC($PRJCODE);
			$data["MenuCode"] 		= 'MN361';
			//$data["recordcount"] 	= $num_rows;
			//$data['viewmc'] 		= $this->m_project_mcg->get_last_ten_projmc($PRJCODE)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN361';
				$TTR_CATEG		= 'L';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$this->load->view('v_project/v_project_mcg/project_mcg', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function a180c2cddmc() // G
	{
		$this->load->model('m_project/m_project_mc/m_project_mcg', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODEX			= $_GET['id'];
			$PRJCODE			= $this->url_encryption_helper->decode_url($PRJCODEX);
			$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
			
			$data['PRJCODE'] 		= $PRJCODE;
			$data['title'] 			= $appName;
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Tambah MC Group";
			}
			else
			{
				$data["h1_title"] 	= "Add MC Grouping";
			}
			$data['task'] 				= 'add';
			$data['main_view'] 			= 'v_project/v_project_mcg/project_mc_form';
			$data['form_action']		= site_url('c_project/c_mc90up180c2c/add_process');
			$cancel_url					= site_url('c_project/c_mc90up180c2c/gall180c2cmg/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			//$data['link'] 				= array('link_back' => anchor("$cancel_url",'<input type="button" name="btnCancel" id="btnCancel" value="Cancel" class="btn btn-danger" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 			= $cancel_url;
			$data['countPRJ']			= $this->m_project_mcg->count_all_num_rowsProject();
			$data['viewProject'] 		= $this->m_project_mcg->viewProject()->result();
			
			$MenuCode 					= 'MN361';
			$data['MenuCode']			= 'MN361';
			$data['viewDocPattern'] 	= $this->m_project_mcg->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN361';
				$TTR_CATEG		= 'A';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$this->load->view('v_project/v_project_mcg/project_mcg_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

  	function get_AllData() // GOOD
	{
		$PRJCODE		= $_GET['id'];

		$LangID     = $this->session->userdata['LangID'];
        
        $sqlTransl      = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
        $resTransl      = $this->db->query($sqlTransl)->result();
        foreach($resTransl as $rowTransl) :
            $TranslCode = $rowTransl->MLANG_CODE;
            $LangTransl = $rowTransl->LangTransl;

    		if($TranslCode == 'ManualCode')$ManualCode = $LangTransl;
    		if($TranslCode == 'Date')$Date = $LangTransl;
    		if($TranslCode == 'Description')$Description = $LangTransl;
    		if($TranslCode == 'SupplierName')$SupplierName = $LangTransl;
        endforeach;
		
		// START : FOR SERVER-SIDE
			$order 	= $this->input->get("order");

			$col 	= 0;
			$dir 	= "";
			if(!empty($order)) {
				foreach($order as $o) {
					$col 	= $o['column'];
					$dir	= $o['dir'];
				}
			}
			
			if($dir != "asc" && $dir != "desc") 
			{
            	$dir = "asc";
        	}

			$columns_valid 	= array("MCH_CODE",
									"MCH_MANNO",
									"MCH_NOTES");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_project_mcg->get_AllDataC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_project_mcg->get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$MCH_CODE		= $dataI['MCH_CODE'];
                $MCH_MANNO		= $dataI['MCH_MANNO'];
                $MCH_STEP		= $dataI['MCH_STEP'];
                $PRJCODE		= $dataI['PRJCODE'];
                $MCH_DATE		= $dataI['MCH_DATE'];
                $MCH_STARTD		= $dataI['MCH_DATE'];
                $MCH_ENDDATE	= $dataI['MCH_ENDDATE'];
                $MCH_PROG		= $dataI['MCH_PROG'];
                $MCH_PROGVAL	= $dataI['MCH_PROGVAL'];
                $MCH_NOTES		= $dataI['MCH_NOTES'];
                $MCH_ISINV		= $dataI['MCH_ISINV'];
                $MCH_STAT		= $dataI['MCH_STAT'];
                $MCH_CREATER	= $dataI['MCH_CREATER'];
				
				$date1 			= new DateTime($MCH_DATE);
				$date2 			= new DateTime($MCH_ENDDATE);

				$MCH_DATEV		= strftime('%d %B %Y', strtotime($MCH_DATE));
				$MCH_ENDDATEV	= strftime('%d %B %Y', strtotime($MCH_ENDDATE));

				$isDis 			= "";
				$numCol 		= "maroon";
				// START : STATUS
					if($MCH_STAT == 0)
					{
						$MCH_STATDes 	= "fake";
						$STATCOL		= 'danger';
					}
					elseif($MCH_STAT == 1)
					{
						$MCH_STATDes 	= "New";
						$STATCOL		= 'warning';
					}
					elseif($MCH_STAT == 2)
					{
						$MCH_STATDes 	= "Confirm";
						$STATCOL		= 'primary';
						$numCol 		= "orange";
					}
					elseif($MCH_STAT == 3)
					{
						$MCH_STATDes 	= "Approve";
						$STATCOL		= 'success';
						$numCol 		= "olive";
					}
					elseif($MCH_STAT == 4)
					{
						$isDis			= "disabled='disabled'";
						$MCH_STATDes 	= "Revise";
						$STATCOL		= 'warning';
					}
					elseif($MCH_STAT == 5)
					{
						$isDis			= "disabled='disabled'";
						$MCH_STATDes 	= "Rejected";
						$STATCOL		= 'danger';
					}
					elseif($MCH_STAT == 6)
					{
						$MCH_STATDes 	= "Close";
						$STATCOL		= 'primary';
					}
					elseif($MCH_STAT == 7)
					{
						$MCH_STATDes 	= "Waiting";
						$STATCOL		= 'warning';
					}
					elseif($MCH_STAT == 9)
					{
						$isDis			= "disabled='disabled'";
						$MCH_STATDes 	= "Void";
						$STATCOL		= 'danger';
					}
				// END : STATUS

				$resStatD		= "<span class='label label-danger' style='font-size:12px'>Outstanding</span>";
				$sqlPayStat		= "tbl_projinv_header WHERE PINV_SOURCE = '$MCH_CODE' AND PINV_STAT IN (3,6) AND PRJCODE = '$PRJCODE'";
				$resPatStat		= $this->db->count_all($sqlPayStat);

				if($resPatStat > 0)
				{
					$imgMng		= base_url('assets/AdminLTE-2.0.5/dist/img/paid-stamp.png');
					$resStatD 	= "<img class='img-circle img-sm; center' src='".$imgMng."' width='30' height='30' title='Processing'>";
					$isDis		= "disabled='disabled'";
				}

				$secUpd			= site_url('c_project/c_mc180c2c/u180c2cpmc//?id='.$this->url_encryption_helper->encode_url($MCH_CODE));
				$secPrint		= site_url('c_project/c_mc180c2c/printdocument/?id='.$this->url_encryption_helper->encode_url($MCH_CODE));
				$CollID			= "MCH~$MCH_CODE~$PRJCODE";
				$secDel 		= base_url().'index.php/__l1y/trashDOC/?id=';
				$secVoid 		= base_url().'index.php/__l1y/trashMC/?id=';
				$delID 			= "$secDel~tbl_mcg_header~MCH_CODE~$MCH_CODE~PRJCODE~$PRJCODE";
				$voidID 		= "$secVoid~tbl_mcg_header~MCH_CODE~$MCH_CODE~PRJCODE~$PRJCODE";

				if($MCH_STAT == 1 || $MCH_STAT == 4)
				{
					$secAction	= 	"<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
								   	<a href='avascript:void(null);' class='btn btn-primary btn-xs' title='Print' onClick='printD(".$noU.")' disabled='disabled'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-warning btn-xs' onClick='voidDOC(".$noU.")' title='Void' disabled='disabled'>
										<i class='glyphicon glyphicon-off'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete' style='display: none'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				elseif($MCH_STAT == 3 || $MCH_STAT == 6) 
				{
					$secAction	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
									<input type='hidden' name='urlVoid".$noU."' id='urlVoid".$noU."' value='".$voidID."'>
								   <label style='white-space:nowrap'>
								   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   </a>
								   <a href='avascript:void(null);' class='btn btn-primary btn-xs' title='Print' onClick='printD(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-warning btn-xs' onClick='voidDOC(".$noU.")' title='Void' ".$isDis.">
										<i class='glyphicon glyphicon-off'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				else
				{
					$secAction	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								   <label style='white-space:nowrap'>
								   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   </a>
								   <a href='avascript:void(null);' class='btn btn-primary btn-xs' title='Print' onClick='printD(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-warning btn-xs' onClick='voidDOC(".$noU.")' title='Void' disabled='disabled'>
										<i class='glyphicon glyphicon-off'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}

				$output['data'][]	= array("<div style='white-space:nowrap'>
											  	<strong>".$MCH_CODE."</strong>
											  	<div><strong><i class='fa fa-flag-o margin-r-5'></i> ".$ManualCode." </strong></div>
										  		<div>
											  		<p class='text-muted' style='margin-left: 20px'>
											  			".$MCH_MANNO."
											  		</p>
											  	</div>
										  	</div>",
										  	"<div>
											  	<strong><i class='fa fa-calendar margin-r-5'></i> ".$Date." </strong>
										  		<div>
											  		<p class='text-muted' style='margin-left: 18px'>
											  			".$MCH_DATEV." - ".$MC_ENDDATEV."
											  		</p>
											  	</div>
											  	<strong><i class='fa fa-pencil margin-r-5'></i> ".$Description." </strong>
										  		<div>
											  		<p class='text-muted' style='margin-left: 18px'>
											  			".$MCH_NOTES."
											  		</p>
											  	</div>
										  	</div>",
										  	"<span class='label label-".$STATCOL."' style='font-size:12px'>".$MCH_STATDes."</span>",
											"<div style='text-align:center;'>".$resStatD."</div>",
										  	"<div style='white-space:nowrap'>".$secAction."</div");
										  
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function pall180c2emc() // G
	{
		$this->load->model('m_project/m_project_mc/m_project_mcg', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE		= $_GET['id'];
			$PRJCODE		= $this->url_encryption_helper->decode_url($PRJCODE);
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		
			$data['title'] 			= $appName;
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Pilih MC";
			}
			else
			{
				$data["h1_title"] 	= "Select MC";
			}
			
			$data['PRJCODE'] 	= $PRJCODE;
			$data['countMC'] 	= $this->m_project_mcg->count_all_MC($PRJCODE);
			$data['vwMC'] 		= $this->m_project_mcg->view_all_MC($PRJCODE)->result();
			
			$this->load->view('v_project/v_project_mcg/project_selectmc', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function add_process() // G
	{
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];	
		$this->load->model('m_project/m_project_mc/m_project_mcg', '', TRUE);
		
		$sqlApp 			= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$this->db->trans_begin();
			
			date_default_timezone_set("Asia/Jakarta");
			$MCH_CREATED	= date('Y-m-d H:i:s');
			
			$MCH_CODE 		= $this->input->post('MCH_CODE');
			$MCH_MANNO 		= $this->input->post('MCH_MANNO');
			$PRJCODE 		= $this->input->post('PRJCODE');
			$MCH_OWNER 		= $this->input->post('MCH_OWNER');
			$MCH_DATE		= date('Y-m-d',strtotime($this->input->post('MCH_DATE')));
			$MCH_ENDDATE	= date('Y-m-d',strtotime($this->input->post('MCH_ENDDATE')));
			$PATT_YEAR		= date('Y',strtotime($this->input->post('MCH_DATE')));
			
			// START - PEMBENTUKAN GENERATE CODE
				$this->load->model('m_projectlist/m_projectlist', '', TRUE);
				$Pattern_Code	= "XX";
				$MenuCode 		= 'MN361';
				$vwDocPatt		= $this->m_projectlist->getDataDocPat($MenuCode)->result();
				foreach($vwDocPatt as $row) :
					$Pattern_Code = $row->Pattern_Code;
				endforeach;
				
				$PRJCODE 		= $this->input->post('PRJCODE');
				$TRXTIME1		= date('ymdHis');
				$MCH_CODE		= "$Pattern_Code$PRJCODE-$TRXTIME1";
			// END - PEMBENTUKAN GENERATE CODE
			
			$MCH_STAT		= $this->input->post('MCH_STAT');
			$dataMCH 		= array('MCH_CODE' 		=> $MCH_CODE,
									'MCH_MANNO'		=> $MCH_MANNO,
									'MCH_STEP'		=> $this->input->post('MCH_STEP'),
									'PRJCODE'		=> $PRJCODE,
									'PRJCOST'		=> $this->input->post('proj_amountIDR'),
									'MCH_OWNER'		=> $MCH_OWNER,
									'MCH_DATE'		=> $MCH_DATE,
									'MCH_ENDDATE'	=> $MCH_ENDDATE,
									'MCH_RETVAL'	=> $this->input->post('MCH_RETVAL'),
									'MCH_PROG'		=> $this->input->post('MCH_PROG'),
									'MCH_PROGVAL'	=> $this->input->post('MCH_PROGVAL'),
									'MCH_PROGCUR'	=> $this->input->post('MCH_PROGCUR'),
									'MCH_PROGCURVAL'=> $this->input->post('MCH_PROGCURVAL'),
									'MCH_VALADD'	=> $this->input->post('MCH_VALADD'),
									'MCH_DPPER'		=> $this->input->post('MCH_DPPER'),
									'MCH_DPVAL'		=> $this->input->post('MCH_DPVAL'),
									'MCH_DPBACK'	=> $this->input->post('MCH_DPBACK'),
									'MCH_DPBACKCUR'	=> $this->input->post('MCH_DPBACKCUR'),
									'MCH_RETCUTP'	=> $this->input->post('MCH_RETCUTP'),
									'MCH_RETCUT'	=> $this->input->post('MCH_RETCUT'),
									'MCH_RETCUTCUR'	=> $this->input->post('MCH_RETCUTCUR'),
									'MCH_PROGAPP'	=> $this->input->post('MCH_PROGAPP'),
									'MCH_PROGAPPVAL'=> $this->input->post('MCH_PROGAPPVAL'),
									'MCH_VALBEF'	=> $this->input->post('MCH_VALBEF'),
									'MCH_AKUMNEXT'	=> $this->input->post('MCH_AKUMNEXT'),
									'MCH_TOTVAL'	=> $this->input->post('MCH_TOTVAL'),
									'MCH_TOTVAL_PPn'=> $this->input->post('MCH_TOTVAL_PPn'),
									'MCH_TOTVAL_PPh'=> $this->input->post('MCH_TOTVAL_PPh'),
									'MCH_NOTES'		=> $this->input->post('MCH_NOTES'),
									'MCH_CREATED'	=> $MCH_CREATED,
									'MCH_CREATER'	=> $DefEmp_ID,
									'MCH_STAT'		=> $MCH_STAT,
									'PATT_YEAR'		=> $PATT_YEAR,
									'PATT_NUMBER'	=> $this->input->post('PATT_NUMBER'));
			$this->m_project_mcg->add($dataMCH, $PRJCODE);
			
			foreach($_POST['data'] as $d)
			{
				$d['MCH_CODE']	= $MCH_CODE;
				$d['MCH_MANNO']	= $MCH_MANNO;
				$this->db->insert('tbl_mcg_detail',$d);
				
				// UPDATE MC ISGROUP
				if($MCH_STAT == 3)
				{
					$MC_CODE	= $d['MC_CODE'];
					$dataMCX 	= array('MC_ISGROUP'	=> 1);
					$this->m_project_mcg->updateMC($MC_CODE, $dataMCX, $PRJCODE);
				}
			}
			
			if($MCH_STAT == 3)
			{
				// START : SAVE APPROVE HISTORY
					$this->load->model('m_updash/m_updash', '', TRUE);
					
					$AH_CODE		= $MCH_CODE;
					$AH_APPLEV		= $this->input->post('APP_LEVEL');
					$AH_APPROVER	= $DefEmp_ID;
					$AH_APPROVED	= date('Y-m-d H:i:s');
					$AH_NOTES		= $this->input->post('MCH_NOTES');
					$AH_ISLAST		= $this->input->post('IS_LAST');
					
					$insAppHist 	= array('AH_CODE'		=> $AH_CODE,
											'AH_APPLEV'		=> $AH_APPLEV,
											'AH_APPROVER'	=> $AH_APPROVER,
											'AH_APPROVED'	=> $AH_APPROVED,
											'AH_NOTES'		=> $AH_NOTES,
											'AH_ISLAST'		=> $AH_ISLAST);										
					$this->m_updash->insAppHist($insAppHist);
				// END : SAVE APPROVE HISTORY
			}
			
			// START : CLEAR HISTORY
				$this->load->model('m_updash/m_updash', '', TRUE);
				if($MCH_STAT == 4)
				{
					$cllPar = array('AH_CODE' 		=> $MCH_CODE,
									'AH_APPROVER'	=> $DefEmp_ID);
					$this->m_updash->clearHist($cllPar);
				}
			// END : CLEAR HISTORY
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $MC_CODE;
				$MenuCode 		= 'MN361';
				$TTR_CATEG		= 'UP';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_project/c_mc90up180c2c/gall180c2cmg/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function u180c2cpmc() // G
	{
		$this->load->model('m_project/m_project_mc/m_project_mcg', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$MCH_CODEX			= $_GET['id'];
			$MCH_CODE			= $this->url_encryption_helper->decode_url($MCH_CODEX);
			$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
			
			$getMCDET 				= $this->m_project_mcg->get_MC_by_number($MCH_CODE)->row();
			$PRJCODE				= $getMCDET->PRJCODE;
			
			$data['PRJCODE'] 		= $PRJCODE;
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Edit MC Group";
			}
			else
			{
				$data["h1_title"] 	= "Edit MC Grouping";
			}
			$data['MenuCode']		= 'MN361';
			$data['form_action']	= site_url('c_project/c_mc90up180c2c/update_process');
			$cancel_url				= site_url('c_project/c_mc90up180c2c/gall180c2cmg/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			//$data['link'] 				= array('link_back' => anchor("$cancel_url",'<input type="button" name="btnCancel" id="btnCancel" value="Cancel" class="btn btn-danger" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 			= $cancel_url;
			
			$data['countPRJ']			= $this->m_project_mcg->count_all_num_rowsProject();
			$data['viewProject'] 		= $this->m_project_mcg->viewProject()->result();
			
			$data['default']['MCH_CODE'] 		= $getMCDET->MCH_CODE;
			$data['default']['MCH_MANNO'] 		= $getMCDET->MCH_MANNO;
			$data['default']['MCH_STEP'] 		= $getMCDET->MCH_STEP;
			$data['default']['PRJCODE'] 		= $getMCDET->PRJCODE;
			$data['default']['MCH_OWNER'] 		= $getMCDET->MCH_OWNER;
			$data['default']['MCH_DATE'] 		= $getMCDET->MCH_DATE;
			$data['default']['MCH_ENDDATE'] 	= $getMCDET->MCH_ENDDATE;
			$data['default']['MCH_RETVAL'] 		= $getMCDET->MCH_RETVAL;
			$data['default']['MCH_PROG'] 		= $getMCDET->MCH_PROG;
			$data['default']['MCH_PROGVAL'] 	= $getMCDET->MCH_PROGVAL;
			$data['default']['MCH_PROGCUR'] 	= $getMCDET->MCH_PROGCUR;
			$data['default']['MCH_PROGCURVAL'] 	= $getMCDET->MCH_PROGCURVAL;
			$data['default']['MCH_VALADD'] 		= $getMCDET->MCH_VALADD;
			$data['default']['MCH_MATVAL'] 		= $getMCDET->MCH_MATVAL;
			$data['default']['MCH_DPPER'] 		= $getMCDET->MCH_DPPER;
			$data['default']['MCH_DPVAL'] 		= $getMCDET->MCH_DPVAL;
			$data['default']['MCH_DPBACK'] 		= $getMCDET->MCH_DPBACK;
			$data['default']['MCH_DPBACKCUR'] 	= $getMCDET->MCH_DPBACKCUR;
			$data['default']['MCH_RETCUTP'] 	= $getMCDET->MCH_RETCUTP;
			$data['default']['MCH_RETCUT'] 		= $getMCDET->MCH_RETCUT;
			$data['default']['MCH_RETCUTCUR'] 	= $getMCDET->MCH_RETCUTCUR;
			$data['default']['MCH_PROGAPP'] 	= $getMCDET->MCH_PROGAPP;
			$data['default']['MCH_PROGAPPVAL']	= $getMCDET->MCH_PROGAPPVAL;
			$data['default']['MCH_VALBEF']		= $getMCDET->MCH_VALBEF;
			$data['default']['MCH_AKUMNEXT'] 	= $getMCDET->MCH_AKUMNEXT;
			$data['default']['MCH_TOTVAL'] 		= $getMCDET->MCH_TOTVAL;
			$data['default']['MCH_TOTVAL_PPn'] 	= $getMCDET->MCH_TOTVAL_PPn;
			$data['default']['MCH_TOTVAL_PPh'] 	= $getMCDET->MCH_TOTVAL_PPh;
			$data['default']['MCH_NOTES'] 		= $getMCDET->MCH_NOTES;
			$data['default']['MCH_CREATED'] 	= $getMCDET->MCH_CREATED;
			$data['default']['MCH_CREATER'] 	= $getMCDET->MCH_CREATER;
			$data['default']['MCH_EMPID'] 		= $getMCDET->MCH_EMPID;
			$data['default']['MCH_STAT'] 		= $getMCDET->MCH_STAT;
			$data['default']['PATT_YEAR'] 		= $getMCDET->PATT_YEAR;
			$data['default']['PATT_MONTH'] 		= $getMCDET->PATT_MONTH;
			$data['default']['PATT_DATE'] 		= $getMCDET->PATT_DATE;
			$data['default']['PATT_NUMBER'] 	= $getMCDET->PATT_NUMBER;
			$data['PRJCODE'] 					= $getMCDET->PRJCODE;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getMCDET->MCH_CODE;
				$MenuCode 		= 'MN361';
				$TTR_CATEG		= 'U';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$this->load->view('v_project/v_project_mcg/project_mcg_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process() // G
	{ 
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];	
		$this->load->model('m_project/m_project_mc/m_project_mcg', '', TRUE);
		
		$sqlApp 			= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$this->db->trans_begin();
			
			date_default_timezone_set("Asia/Jakarta");
			$MCH_CREATED	= date('Y-m-d H:i:s');
			$MCH_DATE		= date('Y-m-d',strtotime($this->input->post('MCH_DATE')));
			$MCH_ENDDATE	= date('Y-m-d',strtotime($this->input->post('MCH_ENDDATE')));
			$PATT_YEAR		= date('Y',strtotime($this->input->post('MCH_DATE')));
			
			$MCH_CODE 		= $this->input->post('MCH_CODE');
			$MCH_MANNO 		= $this->input->post('MCH_MANNO');
			$PRJCODE 		= $this->input->post('PRJCODE');
			$MCH_OWNER 		= $this->input->post('MCH_OWNER');
			
			$AH_CODE		= $MCH_CODE;
			$AH_APPLEV		= $this->input->post('APP_LEVEL');
			$AH_APPROVER	= $DefEmp_ID;
			$AH_APPROVED	= date('Y-m-d H:i:s');
			$AH_NOTES		= $this->input->post('MCH_NOTES');
			$AH_ISLAST		= $this->input->post('IS_LAST');
			
			$MCH_STAT		= $this->input->post('MCH_STAT');
			$dataMCH 		= array('MCH_MANNO'		=> $MCH_MANNO,
									'MCH_MANNO'		=> $MCH_MANNO,
									'MCH_STEP'		=> $this->input->post('MCH_STEP'),
									'PRJCODE'		=> $PRJCODE,
									'PRJCOST'		=> $this->input->post('proj_amountIDR'),
									'MCH_OWNER'		=> $MCH_OWNER,
									'MCH_DATE'		=> $MCH_DATE,
									'MCH_ENDDATE'	=> $MCH_ENDDATE,
									'MCH_RETVAL'	=> $this->input->post('MCH_RETVAL'),
									'MCH_PROG'		=> $this->input->post('MCH_PROG'),
									'MCH_PROGVAL'	=> $this->input->post('MCH_PROGVAL'),
									'MCH_PROGCUR'	=> $this->input->post('MCH_PROGCUR'),
									'MCH_PROGCURVAL'=> $this->input->post('MCH_PROGCURVAL'),
									'MCH_VALADD'	=> $this->input->post('MCH_VALADD'),
									'MCH_DPPER'		=> $this->input->post('MCH_DPPER'),
									'MCH_DPVAL'		=> $this->input->post('MCH_DPVAL'),
									'MCH_DPBACK'	=> $this->input->post('MCH_DPBACK'),
									'MCH_DPBACKCUR'	=> $this->input->post('MCH_DPBACKCUR'),
									'MCH_RETCUTP'	=> $this->input->post('MCH_RETCUTP'),
									'MCH_RETCUT'	=> $this->input->post('MCH_RETCUT'),
									'MCH_RETCUTCUR'	=> $this->input->post('MCH_RETCUTCUR'),
									'MCH_PROGAPP'	=> $this->input->post('MCH_PROGAPP'),
									'MCH_PROGAPPVAL'=> $this->input->post('MCH_PROGAPPVAL'),
									'MCH_VALBEF'	=> $this->input->post('MCH_VALBEF'),
									'MCH_AKUMNEXT'	=> $this->input->post('MCH_AKUMNEXT'),
									'MCH_TOTVAL'	=> $this->input->post('MCH_TOTVAL'),
									'MCH_TOTVAL_PPn'=> $this->input->post('MCH_TOTVAL_PPn'),
									'MCH_TOTVAL_PPh'=> $this->input->post('MCH_TOTVAL_PPh'),
									'MCH_NOTES'		=> $this->input->post('MCH_NOTES'),
									'MCH_CREATER'	=> $DefEmp_ID,
									'MCH_STAT'		=> $MCH_STAT,
									'PATT_YEAR'		=> $PATT_YEAR);
			$this->m_project_mcg->update($MCH_CODE, $dataMCH, $PRJCODE);
			
			$this->m_project_mcg->deleteMCGDet($MCH_CODE);
			
			foreach($_POST['data'] as $d)
			{
				$d['MCH_CODE']	= $MCH_CODE;
				$d['MCH_MANNO']	= $MCH_MANNO;
				$this->db->insert('tbl_mcg_detail',$d);
				
				// UPDATE MC ISGROUP
				if($MCH_STAT == 3)
				{
					$MC_CODE	= $d['MC_CODE'];
					$dataMCX 	= array('MC_ISGROUP'	=> 1);
					$this->m_project_mcg->updateMC($MC_CODE, $dataMCX, $PRJCODE);
				}
			}
			
			if($MCH_STAT == 3)
			{
				// START : SAVE APPROVE HISTORY
					$this->load->model('m_updash/m_updash', '', TRUE);
					
					$AH_CODE		= $MCH_CODE;
					$AH_APPLEV		= $this->input->post('APP_LEVEL');
					$AH_APPROVER	= $DefEmp_ID;
					$AH_APPROVED	= date('Y-m-d H:i:s');
					$AH_NOTES		= $this->input->post('MCH_NOTES');
					$PR_MEMO		= "";
					$AH_ISLAST		= $this->input->post('IS_LAST');
					
					$insAppHist 	= array('AH_CODE'		=> $AH_CODE,
											'AH_APPLEV'		=> $AH_APPLEV,
											'AH_APPROVER'	=> $AH_APPROVER,
											'AH_APPROVED'	=> $AH_APPROVED,
											'AH_NOTES'		=> $AH_NOTES,
											'AH_ISLAST'		=> $AH_ISLAST);										
					$this->m_updash->insAppHist($insAppHist);
				// END : SAVE APPROVE HISTORY
			}
			
			// START : CLEAR HISTORY
				$this->load->model('m_updash/m_updash', '', TRUE);
				if($MCH_STAT == 4)
				{
					$cllPar = array('AH_CODE' 		=> $MCH_CODE,
									'AH_APPROVER'	=> $DefEmp_ID);
					$this->m_updash->clearHist($cllPar);
				}
			// END : CLEAR HISTORY
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $MC_CODE;
				$MenuCode 		= 'MN361';
				$TTR_CATEG		= 'UP';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_project/c_mc90up180c2c/gall180c2cmg/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
    function indexInbox()  // U
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$secIndex 		= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/c_mc90up180c2c'),'inbox');
			redirect($secIndex);
		}
		else
		{
			redirect('__l1y');
		}
    }	
	
	// --------------------- SI ---------------------  //
	
 	public function prj180c2clsSI() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_project/c_mc90up180c2c/prjls180c2cSI/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function prjls180c2cSI() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data["h1_title"] 	= "Site Instruction";
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN017';	 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_project/c_mc90up180c2c/gAlL180c2cSI/?id=";
			
			$data["secVIEW"]	= 'v_projectlist/project_list';
			if($this->session->userdata['nSELP'] == 0)
			{
				$PRJCODE	= $this->session->userdata['proj_Code'];
				$url		= site_url($data["secURL"].$this->url_encryption_helper->encode_url($PRJCODE));
				redirect($url);
			}
			else
			{
				$this->load->view($data["secVIEW"], $data);
			}
		}
		else
		{
			redirect('__l1y');
		}
	}

	function gAlL180c2cSI() // OK
	{
		$this->load->model('m_project/m_project_mc/m_project_mcg', '', TRUE);		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODEX			= $_GET['id'];
			$PRJCODE			= $this->url_encryption_helper->decode_url($PRJCODEX);
			$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
			
			$data['title'] 			= $appName;
			$data['h2_title'] 		= 'SI Project List';
			$data['h3_title'] 		= 'SI Project';
			$cancel_url				= site_url('c_project/c_mc90up180c2c/prj180c2clsSI/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['backURL'] 		= $cancel_url;
			$data['PRJCODE'] 		= $PRJCODE;
			$data["selSearchCat"] 	= 'isSI';			
			$num_rows 				= $this->m_project_mcg->count_all_num_rowsProjSI($PRJCODE);
			
			$data["recordcount"] 	= $num_rows;			
			$data['CATTYPE'] 		= 'isSI';
			$data['MenuCode']		= 'MN259';
			
			$data['viewprojinvoice'] = $this->m_project_mcg->get_last_ten_projsi($PRJCODE)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN259';
				$TTR_CATEG		= 'L';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$this->load->view('v_project/v_project_mcg/project_si', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function a180c2cddsi() // OK
	{
		$this->load->model('m_project/m_project_mc/m_project_mcg', '', TRUE);		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE			= $_GET['id'];
			$PRJCODE			= $this->url_encryption_helper->decode_url($PRJCODE);
			$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
			
			$cancel_url					= site_url('c_project/c_mc90up180c2c/gAlL180c2cSI/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			$data['PRJCODE'] 			= $PRJCODE;
			$data['title'] 				= $appName;
			$data['task'] 				= 'add';
			$data['h2_title']			= 'Add Site Instruction';
			$data['h3_title']			= 'SI Project';
			$data['form_action']		= site_url('c_project/c_mc90up180c2c/addSI_process');
			$data['backURL'] 			= $cancel_url;
			
			$data['countPRJ']			= $this->m_project_mcg->count_all_num_rowsProject();
			$data['viewProject'] 		= $this->m_project_mcg->viewProject()->result();
			
			$MenuCode 					= 'MN259';
			$data['MenuCode']			= 'MN259';
			$data['viewDocPattern'] 	= $this->m_project_mcg->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN259';
				$TTR_CATEG		= 'A';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$this->load->view('v_project/v_project_mcg/project_si_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function addSI_process() // OK
	{
		$this->load->model('m_project/m_project_mc/m_project_mcg', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		
		$this->db->trans_begin();
		
		date_default_timezone_set("Asia/Jakarta");
		
		$SI_CODE 		= $this->input->post('SI_CODE');
		$SI_MANNO 		= $this->input->post('SI_MANNO');
		$PRJCODE 		= $this->input->post('PRJCODE');
		$SI_AMAND		= $this->input->post('SI_AMAND');		
		$SI_DATE		= date('Y-m-d',strtotime($this->input->post('SI_DATE')));
		$SI_ENDDATE		= date('Y-m-d',strtotime($this->input->post('SI_ENDDATE')));
		$SI_STAT 		= $this->input->post('SI_STAT');
		
		if($SI_STAT == 3)
		{
			$SI_APPDATE		= date('Y-m-d H:i:s');
			$SI_EMPIDAPP	= $DefEmp_ID;
		}
		else
		{
			$SI_APPDATE		= '';
			$SI_EMPIDAPP	= '';
		}
		
		$SI_CREATED		= date('Y-m-d H:i:s');
		$PATT_YEAR		= date('Y',strtotime($this->input->post('SI_DATE')));
		
		$dataSIH 		= array('SI_CODE' 		=> $SI_CODE,
								'SI_MANNO'		=> $SI_MANNO,
								'SI_INCCON'		=> $this->input->post('SI_INCCON'),
								'SI_STEP'		=> $this->input->post('SI_STEP'),
								'PRJCODE'		=> $PRJCODE,
								'SI_DATE'		=> $SI_DATE,
								'SI_ENDDATE'	=> $SI_ENDDATE,
								'SI_CREATED'	=> $SI_CREATED,
								'SI_DESC'		=> $this->input->post('SI_DESC'),
								'SI_DPPER'		=> $this->input->post('SI_DPPER'),
								'SI_DPVAL'		=> $this->input->post('SI_DPVAL'),
								'SI_VALUE'		=> $this->input->post('SI_VALUE'),		// SI PENGAJUAN
								'SI_APPVAL'		=> $this->input->post('SI_APPVAL'),		// SI DISETUJUI
								'SI_PROPPERC'	=> $this->input->post('SI_PROPPERC'),	// SI DISETUJUI (%)
								'SI_PROPVAL'	=> $this->input->post('SI_PROPVAL'),	// REAL COST PEK. +/-
								'SI_REALVAL'	=> $this->input->post('SI_REALVAL'),	// REAL COST PEK. +/-
								'SI_AMAND'		=> $this->input->post('SI_AMAND'),
								'SI_AMANDNO'	=> $this->input->post('SI_AMANDNO'),
								'SI_AMANDVAL'	=> $this->input->post('SI_AMANDVAL'),
								'SI_NOTES'		=> $this->input->post('SI_NOTES'),
								'SI_EMPID'		=> $DefEmp_ID,
								'SI_STAT'		=> $SI_STAT,
								'SI_APPDATE'	=> $SI_APPDATE,
								'SI_EMPIDAPP'	=> $SI_EMPIDAPP,
								'PATT_YEAR'		=> $PATT_YEAR,
								'PATT_NUMBER'	=> $this->input->post('PATT_NUMBER'));						
		$this->m_project_mcg->addSI($dataSIH, $PRJCODE);
		
		/*if($SI_AMAND == 1)
		{
			$this->m_project_mcg->updateSIH($SI_CODE);
		}*/	
			
		// COUNT DATA
			//$this->m_project_mcg->count_all_sinew($PRJCODE);
			//$this->m_project_mcg->count_all_sicon($PRJCODE);
			//$this->m_project_mcg->count_all_siapp($PRJCODE);
			
		// START : UPDATE TO TRANS-COUNT
			$this->load->model('m_updash/m_updash', '', TRUE);
			
			$STAT_BEFORE	= $this->input->post('SI_STAT');			// IF "ADD" CONDITION ALWAYS = PR_STAT
			$parameters 	= array('DOC_CODE' 		=> $SI_CODE,		// TRANSACTION CODE
									'PRJCODE' 		=> $PRJCODE,		// PROJECT
									'TR_TYPE'		=> "SI",			// TRANSACTION TYPE
									'TBL_NAME' 		=> "tbl_siheader",	// TABLE NAME
									'KEY_NAME'		=> "SI_CODE",		// KEY OF THE TABLE
									'STAT_NAME' 	=> "SI_STAT",		// NAMA FIELD STATUS
									'STATDOC' 		=> $SI_STAT,		// TRANSACTION STATUS
									'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
									'FIELD_NM_ALL'	=> "TOT_SI",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
									'FIELD_NM_N'	=> "TOT_SI_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
									'FIELD_NM_C'	=> "TOT_SI_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
									'FIELD_NM_A'	=> "TOT_SI_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
									'FIELD_NM_R'	=> "TOT_SI_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
									'FIELD_NM_RJ'	=> "TOT_SI_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_data
									'FIELD_NM_CL'	=> "TOT_SI_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
			$this->m_updash->updateDashData($parameters);
		// END : UPDATE TO TRANS-COUNT
			
		// START : UPDATE TO T-TRACK
			date_default_timezone_set("Asia/Jakarta");
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			$TTR_PRJCODE	= $PRJCODE;
			$TTR_REFDOC		= $SI_CODE;
			$MenuCode 		= 'MN259';
			$TTR_CATEG		= 'C';
			
			$this->load->model('m_updash/m_updash', '', TRUE);				
			$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
									'TTR_DATE' 		=> date('Y-m-d H:i:s'),
									'TTR_MNCODE'	=> $MenuCode,
									'TTR_CATEG'		=> $TTR_CATEG,
									'TTR_PRJCODE'	=> $TTR_PRJCODE,
									'TTR_REFDOC'	=> $TTR_REFDOC);
			$this->m_updash->updateTrack($paramTrack);
		// END : UPDATE TO T-TRACK
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
		}
		else
		{
			$this->db->trans_commit();
		}
			
		$url			= site_url('c_project/c_mc90up180c2c/gAlL180c2cSI/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
		redirect($url);
	}
	
	function u180c2cpsi() // OK
	{
		$this->load->model('m_project/m_project_mc/m_project_mcg', '', TRUE);		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$SI_CODE			= $_GET['id'];
			$SI_CODE			= $this->url_encryption_helper->decode_url($SI_CODE);
			
			$getSIDET 			= $this->m_project_mcg->get_SI_by_number($SI_CODE)->row();
			$PRJCODE			= $getSIDET->PRJCODE;
			
			$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
					
			$data['PRJCODE'] 			= $PRJCODE;
			$data['title'] 				= $appName;
			$data['task'] 				= 'edit';
			$data['h2_title']			= 'Edit Site Instruction';
			$data['h3_title']			= 'SI Project';
			$data['MenuCode']			= 'MN259';
			$data['form_action']		= site_url('c_project/c_mc90up180c2c/updateSI_process');
			$cancel_url					= site_url('c_project/c_mc90up180c2c/gAlL180c2cSI/?id='.$this->url_encryption_helper->encode_url($PRJCODE));	
			//$data['link'] 				= array('link_back' => anchor("$cancel_url",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 			= $cancel_url;
			
			$data['countPRJ']			= $this->m_project_mcg->count_all_num_rowsProject();
			$data['viewProject'] 		= $this->m_project_mcg->viewProject()->result();
			
			$data['default']['SI_CODE'] 	= $getSIDET->SI_CODE;
			$data['default']['SI_MANNO'] 	= $getSIDET->SI_MANNO;
			$data['default']['SI_INCCON'] 	= $getSIDET->SI_INCCON;
			$data['default']['SI_STEP'] 	= $getSIDET->SI_STEP;
			$data['default']['PRJCODE'] 	= $getSIDET->PRJCODE;
			$data['default']['SI_OWNER'] 	= $getSIDET->SI_OWNER;
			$data['default']['SI_DATE'] 	= $getSIDET->SI_DATE;
			$data['default']['SI_ENDDATE'] 	= $getSIDET->SI_ENDDATE;
			$data['default']['SI_APPDATE'] 	= $getSIDET->SI_APPDATE;
			$data['default']['SI_APPDATE2']	= $getSIDET->SI_APPDATE2;
			$data['default']['SI_CREATED'] 	= $getSIDET->SI_CREATED;
			$data['default']['SI_DESC'] 	= $getSIDET->SI_DESC;
			$data['default']['SI_DPPER'] 	= $getSIDET->SI_DPPER;
			$data['default']['SI_DPVAL'] 	= $getSIDET->SI_DPVAL;
			$data['default']['SI_APPVAL'] 	= $getSIDET->SI_APPVAL;
			$data['default']['SI_VALUE'] 	= $getSIDET->SI_VALUE;
			$data['default']['SI_PROPPERC']	= $getSIDET->SI_PROPPERC;
			$data['default']['SI_PROPVAL'] 	= $getSIDET->SI_PROPVAL;
			$data['default']['SI_REALVAL'] 	= $getSIDET->SI_REALVAL;
			$data['default']['SI_AMAND'] 	= $getSIDET->SI_AMAND;
			$data['default']['SI_AMANDNO'] 	= $getSIDET->SI_AMANDNO;
			$data['default']['SI_AMANDVAL'] = $getSIDET->SI_AMANDVAL;
			$data['default']['SI_AMANDSTAT']= $getSIDET->SI_AMANDSTAT;
			$data['default']['SI_NOTES'] 	= $getSIDET->SI_NOTES;
			$data['default']['SI_EMPID'] 	= $getSIDET->SI_EMPID;
			$data['default']['SI_STAT'] 	= $getSIDET->SI_STAT;
			$data['default']['PATT_YEAR'] 	= $getSIDET->PATT_YEAR;
			$data['default']['PATT_MONTH'] 	= $getSIDET->PATT_MONTH;
			$data['default']['PATT_DATE'] 	= $getSIDET->PATT_DATE;
			$data['default']['PATT_NUMBER'] = $getSIDET->PATT_NUMBER;
			$data['PRJCODE'] 				= $getSIDET->PRJCODE;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getSIDET->SI_CODE;
				$MenuCode 		= 'MN259';
				$TTR_CATEG		= 'U';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$this->load->view('v_project/v_project_mcg/project_si_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function updateSI_process() // OK
	{
		$this->load->model('m_project/m_project_mc/m_project_mcg', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		
		$this->db->trans_begin();
		
		date_default_timezone_set("Asia/Jakarta");
		
		$SI_STAT		= $this->input->post('SI_STAT');
		if($SI_STAT == 2)
		{
			$SI_APPDATE	= date('Y-m-d H:i:s');
		}
		else
		{
			$SI_APPDATE	= '';
		}
		
		$SI_DATE		= date('Y-m-d',strtotime($this->input->post('SI_DATE')));
		$SI_ENDDATE		= date('Y-m-d',strtotime($this->input->post('SI_ENDDATE')));
		//$SI_APPDATE	= date('Y-m-d',strtotime($this->input->post('SI_APPDATE')));
		$SI_APPDATE		= date('Y-m-d H:i:s');
		$SI_APPDATE2	= date('Y-m-d H:i:s');
		//$SI_CREATED	= date('Y-m-d H:i:s');
		$PATT_YEAR		= date('Y',strtotime($this->input->post('SI_DATE')));
		
		$SI_CODE 		= $this->input->post('SI_CODE');
		$SI_MANNO 		= $this->input->post('SI_MANNO');
		$PRJCODE 		= $this->input->post('PRJCODE');
		$SI_AMAND		= $this->input->post('SI_AMAND');
		
		$AH_CODE		= $SI_CODE;
		$AH_APPLEV		= $this->input->post('APP_LEVEL');
		$AH_APPROVER	= $DefEmp_ID;
		$AH_APPROVED	= date('Y-m-d H:i:s');
		$AH_NOTES		= $this->input->post('SI_NOTES');
		$AH_ISLAST		= $this->input->post('IS_LAST');
		
		$dataSIH 	= array('SI_CODE' 		=> $SI_CODE,
							'SI_MANNO'		=> $SI_MANNO,
							'SI_INCCON'		=> $this->input->post('SI_INCCON'),
							'SI_STEP'		=> $this->input->post('SI_STEP'),
							'PRJCODE'		=> $PRJCODE,
							'SI_DATE'		=> $SI_DATE,
							'SI_ENDDATE'	=> $SI_ENDDATE,
							'SI_APPDATE'	=> $SI_APPDATE,
							'SI_APPDATE2'	=> $SI_APPDATE2,
							'SI_DESC'		=> $this->input->post('SI_DESC'),
							'SI_DPPER'		=> $this->input->post('SI_DPPER'),
							'SI_DPVAL'		=> $this->input->post('SI_DPVAL'),
							'SI_VALUE'		=> $this->input->post('SI_VALUE'),
							'SI_APPVAL'		=> $this->input->post('SI_APPVAL'),
							'SI_PROPPERC'	=> $this->input->post('SI_PROPPERC'),
							'SI_PROPVAL'	=> $this->input->post('SI_PROPVAL'),
							'SI_REALVAL'	=> $this->input->post('SI_REALVAL'),
							'SI_AMAND'		=> $this->input->post('SI_AMAND'),
							'SI_AMANDNO'	=> $this->input->post('SI_AMANDNO'),
							'SI_AMANDVAL'	=> $this->input->post('SI_AMANDVAL'),
							'SI_AMANDSTAT'	=> $this->input->post('SI_AMANDSTAT'),
							'SI_NOTES'		=> $this->input->post('SI_NOTES'),
							//'SI_EMPID'	=> $DefEmp_ID,
							'SI_EMPIDAPP'	=> $DefEmp_ID,
							'SI_STAT'		=> $this->input->post('SI_STAT'),
							'PATT_YEAR'		=> $PATT_YEAR,
							'PATT_NUMBER'	=> $this->input->post('PATT_NUMBER'));							
		$this->m_project_mcg->updateSI($SI_CODE, $dataSIH, $PRJCODE);
		
		/*if($SI_AMAND == 1)
		{
			$this->m_project_mcg->updateSIH($SI_CODE);
		}*/
		
		// SAVE TO PROFITLOSS
		if($SI_STAT == 3)
		{
			$dataSIH 	= array('SI_STAT'	=> 7);							
			$this->m_project_mcg->updateSI($SI_CODE, $dataSIH, $PRJCODE);
			// START : SAVE APPROVE HISTORY
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$AH_CODE		= $SI_CODE;
				$AH_APPLEV		= $this->input->post('APP_LEVEL');
				$AH_APPROVER	= $DefEmp_ID;
				$AH_APPROVED	= date('Y-m-d H:i:s');
				$AH_NOTES		= $this->input->post('MC_NOTES');
				$PR_MEMO		= "";
				$AH_ISLAST		= $this->input->post('IS_LAST');
				
				$insAppHist 	= array('AH_CODE'		=> $AH_CODE,
										'AH_APPLEV'		=> $AH_APPLEV,
										'AH_APPROVER'	=> $AH_APPROVER,
										'AH_APPROVED'	=> $AH_APPROVED,
										'AH_NOTES'		=> $AH_NOTES,
										'AH_ISLAST'		=> $AH_ISLAST);										
				$this->m_updash->insAppHist($insAppHist);
			// END : SAVE APPROVE HISTORY
		}
		
		if($SI_STAT == 3 && $AH_ISLAST == 1)
		{
			// START : SETTING L/R
				$this->load->model('m_updash/m_updash', '', TRUE);
				$PERIODED	= $SI_DATE;
				$PERIODM	= date('m', strtotime($PERIODED));
				$PERIODY	= date('Y', strtotime($PERIODED));
				$getLR		= "tbl_profitloss WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
								AND YEAR(PERIODE) = '$PERIODY'";
				$resLR		= $this->db->count_all($getLR);
				if($resLR	== 0)
				{
					$this->m_updash->createLR($PRJCODE, $PERIODED);
				}
			// END : SETTING L/R
							
			// START : UPDATE L/R
				$SI_VALUE		= $this->input->post('SI_VALUE');
				$SI_APPVAL		= $this->input->post('SI_APPVAL');
				$updLR			= "UPDATE tbl_profitloss SET SI_PLAN = SI_PLAN+$SI_VALUE, SI_REAL = SI_REAL+$SI_APPVAL
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
										AND YEAR(PERIODE) = '$PERIODY'";
				$this->db->query($updLR);
			// END : UPDATE L/R
			
			$dataSIH 	= array('SI_MANNO'		=> $SI_MANNO,
								'SI_INCCON'		=> $this->input->post('SI_INCCON'),
								'SI_STEP'		=> $this->input->post('SI_STEP'),
								'PRJCODE'		=> $PRJCODE,
								'SI_DATE'		=> $SI_DATE,
								'SI_ENDDATE'	=> $SI_ENDDATE,
								'SI_APPDATE'	=> $SI_APPDATE,
								'SI_APPDATE2'	=> $SI_APPDATE2,
								'SI_DESC'		=> $this->input->post('SI_DESC'),
								'SI_DPPER'		=> $this->input->post('SI_DPPER'),
								'SI_DPVAL'		=> $this->input->post('SI_DPVAL'),
								'SI_VALUE'		=> $this->input->post('SI_VALUE'),
								'SI_APPVAL'		=> $this->input->post('SI_APPVAL'),
								'SI_PROPPERC'	=> $this->input->post('SI_PROPPERC'),
								'SI_PROPVAL'	=> $this->input->post('SI_PROPVAL'),
								'SI_REALVAL'	=> $this->input->post('SI_REALVAL'),
								'SI_AMAND'		=> $this->input->post('SI_AMAND'),
								'SI_AMANDNO'	=> $this->input->post('SI_AMANDNO'),
								'SI_AMANDVAL'	=> $this->input->post('SI_AMANDVAL'),
								'SI_AMANDSTAT'	=> $this->input->post('SI_AMANDSTAT'),
								'SI_NOTES'		=> $this->input->post('SI_NOTES'),
								'SI_EMPIDAPP'	=> $DefEmp_ID,
								'SI_STAT'		=> $this->input->post('SI_STAT'),
								'PATT_YEAR'		=> $PATT_YEAR,
								'PATT_NUMBER'	=> $this->input->post('PATT_NUMBER'));							
			$this->m_project_mcg->updateSI($SI_CODE, $dataSIH, $PRJCODE);
		}
			
		// START : UPDATE TO TRANS-COUNT
			$this->load->model('m_updash/m_updash', '', TRUE);
			
			$STAT_BEFORE	= $this->input->post('STAT_BEFORE');		// IF "ADD" CONDITION ALWAYS = PR_STAT
			$parameters 	= array('DOC_CODE' 		=> $SI_CODE,		// TRANSACTION CODE
									'PRJCODE' 		=> $PRJCODE,		// PROJECT
									'TR_TYPE'		=> "SI",			// TRANSACTION TYPE
									'TBL_NAME' 		=> "tbl_siheader",	// TABLE NAME
									'KEY_NAME'		=> "SI_CODE",		// KEY OF THE TABLE
									'STAT_NAME' 	=> "SI_STAT",		// NAMA FIELD STATUS
									'STATDOC' 		=> $SI_STAT,		// TRANSACTION STATUS
									'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
									'FIELD_NM_ALL'	=> "TOT_SI",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
									'FIELD_NM_N'	=> "TOT_SI_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
									'FIELD_NM_C'	=> "TOT_SI_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
									'FIELD_NM_A'	=> "TOT_SI_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
									'FIELD_NM_R'	=> "TOT_SI_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
									'FIELD_NM_RJ'	=> "TOT_SI_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_data
									'FIELD_NM_CL'	=> "TOT_SI_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
			$this->m_updash->updateDashData($parameters);
		// END : UPDATE TO TRANS-COUNT
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $SI_CODE;
				$MenuCode 		= 'MN259';
				$TTR_CATEG		= 'UP';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
		}
		else
		{
			$this->db->trans_commit();
		}
			
		$url			= site_url('c_project/c_mc90up180c2c/gAlL180c2cSI/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
		redirect($url);
	}
	
	function deleteMC() // OK
	{ 
		$this->load->model('m_project/m_project_mc/m_project_mcg', '', TRUE);		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		
		if ($this->session->userdata('login') == TRUE)
		{
			$SI_CODE			= $_GET['id'];
			$SI_CODE			= $this->url_encryption_helper->decode_url($SI_CODE);
			
			$getSIDET 			= $this->m_project_mcg->get_SI_by_number($SI_CODE)->row();
			$PRJCODE			= $getSIDET->PRJCODE;
		
			$this->db->trans_begin();
						
			$this->m_project_mcg->deleteMC($SI_CODE);
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_project/c_mc90up180c2c/gAlL180c2cSI/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function syncTable($PRJCODE) // OK
	{ 		
		$this->db->trans_begin();
		
		$this->m_project_mcg->syncTable($PRJCODE);
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
		}
		else
		{
			$this->db->trans_commit();
		}
		//return false;
		redirect('c_project/c_mc90up180c2c/gall180c2cmg/'.$PRJCODE);
	}
	
	function popSIAppList() // OK
	{
		$this->load->model('m_project/m_project_mc/m_project_mcg', '', TRUE);		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE		= $_GET['id'];
			$PRJCODE		= $this->url_encryption_helper->decode_url($PRJCODE);
			$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
			
			$data['title'] 				= $appName;
			$data['h2_title'] 			= 'Select Item';
			//$data['form_action']		= site_url('c_project/material_request_sd/update_process');
			//$data['selDocNumbColl'] 	= $selDocNumbColl;
			$dataSessSrc = array(
				'selSearchproj_Code' 	=> $PRJCODE,
				'selSearchCat' 			=> '',
				'selSearchType' 		=> '',
				'txtSearch' 			=> '');
			$this->session->set_userdata('dtSessSrc1', $dataSessSrc);
			$this->session->set_userdata('dtSessSrc2', $dataSessSrc);
			$PRJCODE 					= $PRJCODE;
			$data['PRJCODE'] 			= $PRJCODE;
					
			$this->load->view('v_project/v_project_mcg/project_si_view_app_List', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function popSIApp() // OK
	{
		$this->load->model('m_project/m_project_mc/m_project_mcg', '', TRUE);		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE		= $_GET['id'];
			$PRJCODE		= $this->url_encryption_helper->decode_url($PRJCODE);
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		
			$data['title'] 				= $appName;
			$data['h2_title'] 			= 'Select Item';
			$data['form_action']		= site_url('c_project/material_request_sd/update_process');
			$data['selDocNumbColl'] 	= $this->input->post('selDocNumbColl');
			$selDocNumbColl				= $this->input->post('selDocNumbColl');
			$data['PRJCODE'] 			= $PRJCODE;
			
			$DefEmp_ID 					= $this->session->userdata['Emp_ID'];
			if($DefEmp_ID == 'D15040004221')
			{
				$this->load->view('v_project/v_project_mcg/project_si_view_app_admin', $data);
			}
			else
			{
				$this->load->view('v_project/v_project_mcg/project_si_view_app', $data);
			}
		}
		else
		{
			redirect('__l1y');
		}
	}
}