<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 14 Februari 2017
 * File Name	= C_mc180c2c.php
 * Notes		= -
*/

class C_mc180c2c extends CI_Controller
{
  	function __construct() // GOOD
	{
		parent::__construct();

		//setlocale(LC_ALL, 'id-ID', 'id_ID');
		setlocale(LC_ALL, 'en_EN');

		$this->load->model('m_project/m_project_mc/m_project_mc', '', TRUE);
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

 	// Start : Index tiap halaman
 	public function index() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_project/c_mc180c2c/prj180c2cls/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function prj180c2cls() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$LangID 	= $this->session->userdata['LangID'];
			// GET MENU DESC
				$mnCode				= 'MN253';
				$MenuCode			= 'MN253';
				$data["MenuCode"] 	= 'MN253';
				$data["MenuApp"] 	= 'MN253';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN017';
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_project/c_mc180c2c/gall180c2cmc/?id=";
			
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

	function gall180c2cmc($offset=0) // OK
	{
		$this->load->model('m_project/m_project_mc/m_project_mc', '', TRUE);
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
			
			$LangID 	= $this->session->userdata['LangID'];
			// GET MENU DESC
				$mnCode				= 'MN253';
				$MenuCode			= 'MN253';
				$data["MenuCode"] 	= 'MN253';
				$data["MenuApp"] 	= 'MN253';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$data['main_view'] 		= 'v_project/v_project_mc/project_mc';
			$data['link'] 			= array('link_back' => anchor('c_project/c_mc180c2c/','<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Back" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 		= site_url('c_project/c_mc180c2c/');
			$data['PRJCODE'] 		= $PRJCODE;
			
			$selSearchproj_Code 	= $PRJCODE;
			$selSearchCat			= 'isMC';
			$GETFROM				= 'GEN';
			$num_rows 				= $this->m_project_mc->count_all_num_rowsProjMC($PRJCODE, $GETFROM);
			$data['CATTYPE'] 		= 'isMC';
			$data["MenuCode"] 		= 'MN253';
			$data["recordcount"] 	= $num_rows;
			$data['viewmc'] 		= $this->m_project_mc->get_last_ten_projmc($PRJCODE, $GETFROM)->result();
			
			$myProjectSess = array(
					'myProjSession' => $selSearchproj_Code);
			$this->session->set_userdata('dtProjSess', $myProjectSess);
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN253';
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
			
			$this->load->view('v_project/v_project_mc/project_mc', $data);
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
    		if($TranslCode == 'document')$document = $LangTransl;
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
			
			$columns_valid 	= array("MC_CODE",
									"MC_MANNO",
									"MC_DATE",
									"MC_ENDDATE",
									"MC_NOTES");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_project_mc->get_AllDataC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_project_mc->get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$MC_CODE		= $dataI['MC_CODE'];
                $MC_MANNO		= $dataI['MC_MANNO'];
                $MC_STEP		= $dataI['MC_STEP'];
                $PRJCODE		= $dataI['PRJCODE'];
                $MC_DATE		= $dataI['MC_DATE'];
                $MC_STARTD		= $dataI['MC_STARTD'];
                $MC_ENDDATE		= $dataI['MC_ENDDATE'];
                $MC_NOTES		= $dataI['MC_NOTES'];
                $MC_ISINV		= $dataI['MC_ISINV'];
                $MC_DOC 		= $dataI['MC_DOC'];
                $MC_STAT		= $dataI['MC_STAT'];
                $MC_PROGAPP		= $dataI['MC_PROGAPP'];
				
				$date1 			= new DateTime($MC_DATE);
				$date2 			= new DateTime($MC_ENDDATE);

				$MC_DATEV		= strftime('%d %B %Y', strtotime($MC_DATE));
				$MC_ENDDATEV	= strftime('%d %B %Y', strtotime($MC_ENDDATE));

				$isDis 			= "";
				$numCol 		= "maroon";
				if($MC_STAT == 0)
				{
					$MC_STATDes = "fake";
					$STATCOL	= 'danger';
				}
				elseif($MC_STAT == 1)
				{
					$MC_STATDes = "New";
					$STATCOL	= 'warning';
				}
				elseif($MC_STAT == 2)
				{
					$MC_STATDes = "Confirm";
					$STATCOL	= 'primary';
					$numCol 	= "orange";
				}
				elseif($MC_STAT == 3)
				{
					$MC_STATDes = "Approve";
					$STATCOL	= 'success';
					$numCol 	= "olive";
				}
				elseif($MC_STAT == 4)
				{
					$isDis		= "disabled='disabled'";
					$MC_STATDes = "Revise";
					$STATCOL	= 'warning';
				}
				elseif($MC_STAT == 5)
				{
					$isDis		= "disabled='disabled'";
					$MC_STATDes = "Rejected";
					$STATCOL	= 'danger';
				}
				elseif($MC_STAT == 6)
				{
					$MC_STATDes = "Close";
					$STATCOL	= 'primary';
				}
				elseif($MC_STAT == 7)
				{
					$MC_STATDes = "Waiting";
					$STATCOL	= 'warning';
				}
				elseif($MC_STAT == 9)
				{
					$isDis		= "disabled='disabled'";
					$MC_STATDes = "Void";
					$STATCOL	= 'danger';
				}

				$resStatD		= "<span class='label label-danger' style='font-size:12px'>Outstanding</span>";
				$sqlPayStat		= "tbl_projinv_header WHERE PINV_SOURCE = '$MC_CODE' AND PINV_STAT IN (3,6) AND PRJCODE = '$PRJCODE'";
				$resPatStat		= $this->db->count_all($sqlPayStat);

				if($resPatStat > 0)
				{
					$imgMng		= base_url('assets/AdminLTE-2.0.5/dist/img/paid-stamp.png');
					$resStatD 	= "<img class='img-circle img-sm; center' src='".$imgMng."' width='30' height='30' title='Processing'>";
					$isDis		= "disabled='disabled'";
				}

				$MC_Document 	= explode('~', $MC_DOC);
				$listMC_DOC 	= '';
				if($MC_DOC != null)
				{
					$new_row = 0;
					foreach($MC_Document as $row):
						$new_row 	 = $new_row + 1;
						$listMC_DOC .= "<ul style='list-style-type:circle; margin: 0;'>";
						$listMC_DOC .= "<li>";
						$listMC_DOC .= "<input type='hidden' name='FileUpName".$new_row."' id='FileUpName".$new_row."' value='".$row."' />";
						$listMC_DOC .= "<a href='avascript:void(null);' title='View PDF File' onClick='typeOpenNewTab(".$new_row.");'>".$row."</a>";
						$listMC_DOC .= "</li>";
						$listMC_DOC .= "</ul>";
					endforeach;
				}

				$secUpd			= site_url('c_project/c_mc180c2c/u180c2cpmc//?id='.$this->url_encryption_helper->encode_url($MC_CODE));
				$secPrint		= site_url('c_project/c_mc180c2c/printdocument/?id='.$this->url_encryption_helper->encode_url($MC_CODE));
				$CollID			= "MC~$MC_CODE~$PRJCODE";
				$secDel 		= base_url().'index.php/__l1y/trashDOC/?id=';
				$secVoid 		= base_url().'index.php/__l1y/trashMC/?id=';
				$delID 			= "$secDel~tbl_mcheader~MC_CODE~$MC_CODE~PRJCODE~$PRJCODE";
				$voidID 		= "$secVoid~tbl_mcheader~MC_CODE~$MC_CODE~PRJCODE~$PRJCODE";

				if($MC_STAT == 1 || $MC_STAT == 4)
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
				elseif($MC_STAT == 3 || $MC_STAT == 6) 
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

				/*$output['data'][]	 = array($MC_CODE,
										  	"<div style='white-space:nowrap'>".$MC_MANNOD."</div>",
										  	$MC_NOTES,
										  	$date1->format('d F Y'),
										  	$date2->format('d F Y'),
										  	"<span class='label label-".$STATCOL."' style='font-size:12px'>".$MC_STATDes."</span>",
											$resPatStatD,
										  	"<div style='white-space:nowrap'>".$secAction."</div");*/

				$output['data'][]	= array("<div style='white-space:nowrap'>
											  	<strong>".$MC_CODE."</strong>
											  	<div><strong><i class='fa fa-flag-o margin-r-5'></i> ".$ManualCode." </strong></div>
										  		<div>
											  		<p class='text-muted' style='margin-left: 20px'>
											  			".$MC_MANNO."
											  		</p>
											  	</div>
										  	</div>",
										  	"<div style='white-space:nowrap'><button type='button' class='btn bg-".$numCol." btn-flat margin'>".$MC_STEP."</button><br>".number_format($MC_PROGAPP,4)." %</div>",
										  	"<div>
											  	<strong><i class='fa fa-calendar margin-r-5'></i> ".$Date." </strong>
										  		<div>
											  		<p class='text-muted' style='margin-left: 18px'>
											  			".$MC_DATEV." - ".$MC_ENDDATEV."
											  		</p>
											  	</div>
											  	<strong><i class='fa fa-pencil margin-r-5'></i> ".$Description." </strong>
										  		<div>
											  		<p class='text-muted' style='margin-left: 18px'>
											  			".$MC_NOTES."
											  		</p>
											  	</div>
											  	<strong><i class='fa fa-book margin-r-5'></i> ".$document." </strong>
										  		<div>
											  		<p class='text-muted' style='margin-left: 18px; margin-bottom: 0'>
											  			".$listMC_DOC."
											  		</p>
											  	</div>
										  	</div>",
										  	"<span class='label label-".$STATCOL."' style='font-size:12px'>".$MC_STATDes."</span>",
											"<div style='text-align:center;'>".$resStatD."</div>",
										  	"<div style='white-space:nowrap'>".$secAction."</div>");
										  
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function a180c2cddmc() // OK
	{
		$this->load->model('m_project/m_project_mc/m_project_mc', '', TRUE);
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
			
			$data['PRJCODE'] 			= $PRJCODE;
			$data['title'] 				= $appName;
			$data['task'] 				= 'add';
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h2_title"] 	= "Tambah MC";
				$data['h3_title']	= "MC Proyek";
			}
			else
			{
				$data["h2_title"] 	= "Add MC";
				$data['h3_title']	= "MC Project";
			}
			
			$data['main_view'] 			= 'v_project/v_project_mc/project_mc_form';
			$data['form_action']		= site_url('c_project/c_mc180c2c/add_process');
			$cancel_url					= site_url('c_project/c_mc180c2c/gall180c2cmc/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			//$data['link'] 				= array('link_back' => anchor("$cancel_url",'<input type="button" name="btnCancel" id="btnCancel" value="Cancel" class="btn btn-danger" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 			= $cancel_url;
			$data['countPRJ']			= $this->m_project_mc->count_all_num_rowsProject();
			$data['viewProject'] 		= $this->m_project_mc->viewProject()->result();
			
			$MenuCode 					= 'MN253';
			$data['MenuCode']			= 'MN253';
			$data['viewDocPattern'] 	= $this->m_project_mc->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN253';
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
			
			$this->load->view('v_project/v_project_mc/project_mc_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function add_process() // OK
	{
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];	
		$this->load->model('m_project/m_project_mc/m_project_mc', '', TRUE);
		
		$sqlApp 			= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$this->db->trans_begin();
			
			date_default_timezone_set("Asia/Jakarta");
			//setting MC Date
			$MC_CHECKD		= date('Y-m-d');
			$MC_CREATED		= date('Y-m-d H:i:s');

			$MC_CODE 		= $this->input->post('MC_CODE');
			$MC_MANNO 		= $this->input->post('MC_MANNO');
			$MC_OWNER 		= $this->input->post('MC_OWNER');
			$MC_STEP 		= $this->input->post('MC_STEP');
			$PRJCODE 		= $this->input->post('PRJCODE');
			$PRJCOST 		= $this->input->post('PRJCOST');
			
			$MC_DATE		= date('Y-m-d',strtotime($this->input->post('MC_DATE')));
			$MC_STARTD		= date('Y-m-d',strtotime($this->input->post('MC_STARTD')));
			$MC_ENDDATE		= date('Y-m-d',strtotime($this->input->post('MC_ENDDATE')));
			$MC_CHECKD		= date('Y-m-d',strtotime($this->input->post('MC_CHECKD')));
			$PATT_YEAR		= date('Y',strtotime($this->input->post('MC_DATE')));
			$MC_CREATED		= date('Y-m-d H:i:s');
			$MC_DPVAL 		= $this->input->post('MC_DPVAL');
			$MC_PROGAPPVAL	= $this->input->post('MC_PROGAPPVAL');
			$MC_DPBACK 		= $this->input->post('MC_DPBACK');
			$MC_RETCUT 		= $this->input->post('MC_RETCUT');
			$MC_TOTVAL		= $this->input->post('MC_TOTVAL');
			$MC_AKUMNEXT	= $MC_DPVAL + $MC_PROGAPPVAL - $MC_DPBACK - $MC_RETCUT;
			//$MC_VALBEF		= $MC_AKUMNEXT - $MC_TOTVAL;
			$MC_VALBEF		= $this->input->post('MC_VALBEF');
			$MC_STAT		= $this->input->post('MC_STAT');

			// ============================= Start Upload File ========================================== //
			$this->load->library('upload');
		    $this->upload->initialize(["upload_path" => "assets/AdminLTE-2.0.5/doc_center/uploads/MC_Document",
									   "allowed_types" => "pdf",
									   // "max_size" => 1024,
									   "overwrite" => false
		  	]);

		  	if ($this->upload->do_multi_upload('MC_DOC'))
			{
				$data	= $this->upload->get_multi_upload_data();
				$MC_Document = [];
				foreach($data as $row):
					$MC_Document[] = $row['file_name'];
				endforeach;
				$MC_DOC = implode('~', $MC_Document);

				$dataMCH 		= array('MC_CODE' 		=> $MC_CODE,
										'MC_MANNO'		=> $MC_MANNO,
										'GETFROM'		=> 'GEN',
										'MC_STEP'		=> $this->input->post('MC_STEP'),
										'PRJCODE'		=> $PRJCODE,
										'PRJCOST'		=> $PRJCOST,
										'MC_OWNER'		=> $MC_OWNER,
										'MC_DATE'		=> $MC_DATE,
										'MC_STARTD'		=> $MC_STARTD,
										'MC_ENDDATE'	=> $MC_ENDDATE,
										'MC_CHECKD'		=> $MC_DATE,
										'MC_CREATED'	=> $MC_CREATED,
										'MC_RETVAL'		=> $this->input->post('MC_RETVAL'),
										'MC_PROGB'		=> $this->input->post('MC_PROGB'),
										'MC_PROG'		=> $this->input->post('MC_PROG'),
										'MC_PROGVAL'	=> $this->input->post('MC_PROGVAL'),
										'MC_PROGCUR'	=> $this->input->post('MC_PROGCUR'),
										'MC_PROGCURVAL'	=> $this->input->post('MC_PROGCURVAL'),
										'MC_VALADD'		=> $this->input->post('MC_VALADD'),
										'MC_MATVAL'		=> $this->input->post('MC_MATVAL'),
										'MC_DPPER'		=> $this->input->post('MC_DPPER'),
										'MC_DPVAL'		=> $this->input->post('MC_DPVAL'),
										'MC_DPBACK'		=> $this->input->post('MC_DPBACK'),
										'MC_DPBACKCUR'	=> $this->input->post('MC_DPBACKCUR'),
										'MC_RETCUTP'	=> $this->input->post('MC_RETCUTP'),
										'MC_RETCUT'		=> $this->input->post('MC_RETCUT'),
										'MC_RETCUTCUR'	=> $this->input->post('MC_RETCUTCUR'),
										'MC_PROGAPP'	=> $this->input->post('MC_PROGAPP'),
										'MC_PROGAPPVAL'	=> $this->input->post('MC_PROGAPPVAL'),
										'MC_AKUMNEXT'	=> $MC_AKUMNEXT,
										'MC_VALBEF'		=> $MC_VALBEF,
										'MC_TOTVAL'		=> $this->input->post('MC_TOTVAL'),
										'MC_TOTVAL_PPn'	=> $this->input->post('MC_TOTVAL_PPn'),
										'MC_TOTVAL_PPh'	=> $this->input->post('MC_TOTVAL_PPh'),
										'MC_NOTES'		=> addslashes($this->input->post('MC_NOTES')),
										'MC_EMPID'		=> $DefEmp_ID,
										'MC_DOC'		=> $MC_DOC,
										'MC_STAT'		=> $MC_STAT,
										'PATT_YEAR'		=> $PATT_YEAR,
										'PATT_NUMBER'	=> $this->input->post('PATT_NUMBER'));
			}
			else
			{
				$dataMCH 		= array('MC_CODE' 		=> $MC_CODE,
										'MC_MANNO'		=> $MC_MANNO,
										'GETFROM'		=> 'GEN',
										'MC_STEP'		=> $this->input->post('MC_STEP'),
										'PRJCODE'		=> $PRJCODE,
										'PRJCOST'		=> $PRJCOST,
										'MC_OWNER'		=> $MC_OWNER,
										'MC_DATE'		=> $MC_DATE,
										'MC_STARTD'		=> $MC_STARTD,
										'MC_ENDDATE'	=> $MC_ENDDATE,
										'MC_CHECKD'		=> $MC_DATE,
										'MC_CREATED'	=> $MC_CREATED,
										'MC_RETVAL'		=> $this->input->post('MC_RETVAL'),
										'MC_PROGB'		=> $this->input->post('MC_PROGB'),
										'MC_PROG'		=> $this->input->post('MC_PROG'),
										'MC_PROGVAL'	=> $this->input->post('MC_PROGVAL'),
										'MC_PROGCUR'	=> $this->input->post('MC_PROGCUR'),
										'MC_PROGCURVAL'	=> $this->input->post('MC_PROGCURVAL'),
										'MC_VALADD'		=> $this->input->post('MC_VALADD'),
										'MC_MATVAL'		=> $this->input->post('MC_MATVAL'),
										'MC_DPPER'		=> $this->input->post('MC_DPPER'),
										'MC_DPVAL'		=> $this->input->post('MC_DPVAL'),
										'MC_DPBACK'		=> $this->input->post('MC_DPBACK'),
										'MC_DPBACKCUR'	=> $this->input->post('MC_DPBACKCUR'),
										'MC_RETCUTP'	=> $this->input->post('MC_RETCUTP'),
										'MC_RETCUT'		=> $this->input->post('MC_RETCUT'),
										'MC_RETCUTCUR'	=> $this->input->post('MC_RETCUTCUR'),
										'MC_PROGAPP'	=> $this->input->post('MC_PROGAPP'),
										'MC_PROGAPPVAL'	=> $this->input->post('MC_PROGAPPVAL'),
										'MC_AKUMNEXT'	=> $MC_AKUMNEXT,
										'MC_VALBEF'		=> $MC_VALBEF,
										'MC_TOTVAL'		=> $this->input->post('MC_TOTVAL'),
										'MC_TOTVAL_PPn'	=> $this->input->post('MC_TOTVAL_PPn'),
										'MC_TOTVAL_PPh'	=> $this->input->post('MC_TOTVAL_PPh'),
										'MC_NOTES'		=> addslashes($this->input->post('MC_NOTES')),
										'MC_EMPID'		=> $DefEmp_ID,
										'MC_DOC'		=> $MC_DOC,
										// 'MC_STAT'		=> $MC_STAT,
										'PATT_YEAR'		=> $PATT_YEAR,
										'PATT_NUMBER'	=> $this->input->post('PATT_NUMBER'));
			}

			if($MC_STAT == 2)
			{
				// START : UPDATE FINANCIAL DASHBOARD
					$MC_VAL 	= $this->input->post('MC_TOTVAL');
					$finDASH 	= array('PRJCODE'	=> $PRJCODE,
										'PERIODE'	=> $MC_DATE,
										'FVAL'		=> $MC_VAL,
										'FNAME'		=> "MC_VAL");										
					$this->m_updash->updFINDASH($finDASH);
				// END : UPDATE FINANCIAL DASHBOARD
			}

			// ============================= End Upload File ========================================== //

			$this->m_project_mc->add($dataMCH, $PRJCODE);
			
			// COUNT DATA
				//$this->m_project_mc->count_all_mcnew($PRJCODE);
				//$this->m_project_mc->count_all_mccon($PRJCODE);
				//$this->m_project_mc->count_all_mcapp($PRJCODE);
			
			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('MC_STAT');			// IF "ADD" CONDITION ALWAYS = PR_STAT
				$parameters 	= array('DOC_CODE' 		=> $MC_CODE,		// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "MC",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_mcheader",	// TABLE NAME
										'KEY_NAME'		=> "MC_CODE",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "MC_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $MC_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_MC",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_MC_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_MC_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_MC_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_MC_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_MC_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_MC_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $MC_CODE;
				$MenuCode 		= 'MN253';
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
			
			$url			= site_url('c_project/c_mc180c2c/gall180c2cmc/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function u180c2cpmc() // OK
	{
		$this->load->model('m_project/m_project_mc/m_project_mc', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$MC_CODEX			= $_GET['id'];
			$MC_CODE			= $this->url_encryption_helper->decode_url($MC_CODEX);
			$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
			
			$getMCDET 					= $this->m_project_mc->get_MC_by_number($MC_CODE)->row();
			$PRJCODE					= $getMCDET->PRJCODE;
			
			$data['PRJCODE'] 			= $PRJCODE;
			$data['title'] 				= $appName;
			$data['task'] 				= 'edit';
			$data['h2_title']			= 'Add MC';
			$data['h3_title']			= 'MC Project';
			$data['MenuCode']			= 'MN253';
			$data['main_view'] 			= 'v_project/v_project_mc/project_mc_form';
			$data['form_action']		= site_url('c_project/c_mc180c2c/update_process');
			$cancel_url					= site_url('c_project/c_mc180c2c/gall180c2cmc/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			//$data['link'] 				= array('link_back' => anchor("$cancel_url",'<input type="button" name="btnCancel" id="btnCancel" value="Cancel" class="btn btn-danger" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 			= $cancel_url;
			
			$data['countPRJ']			= $this->m_project_mc->count_all_num_rowsProject();
			$data['viewProject'] 		= $this->m_project_mc->viewProject()->result();			
			
			$data['default']['MC_CODE'] 		= $getMCDET->MC_CODE;
			$data['default']['MC_MANNO'] 		= $getMCDET->MC_MANNO;
			$data['default']['GETFROM'] 		= $getMCDET->GETFROM;
			$data['default']['MC_STEP'] 		= $getMCDET->MC_STEP;
			$data['default']['PRJCODE'] 		= $getMCDET->PRJCODE;
			$data['default']['PRJCOST'] 		= $getMCDET->PRJCOST;
			$data['default']['MC_OWNER'] 		= $getMCDET->MC_OWNER;
			$data['default']['MC_DATE'] 		= $getMCDET->MC_DATE;
			$data['default']['MC_STARTD'] 		= $getMCDET->MC_STARTD;
			$data['default']['MC_ENDDATE'] 		= $getMCDET->MC_ENDDATE; 
			$data['default']['MC_CHECKD'] 		= $getMCDET->MC_CHECKD; 
			$data['default']['MC_CREATED'] 		= $getMCDET->MC_CREATED;
			$data['default']['MC_RETVAL'] 		= $getMCDET->MC_RETVAL;
			$data['default']['MC_PROGB'] 		= $getMCDET->MC_PROGB;
			$data['default']['MC_PROG'] 		= $getMCDET->MC_PROG;
			$data['default']['MC_PROGVAL'] 		= $getMCDET->MC_PROGVAL;
			$data['default']['MC_PROGCUR'] 		= $getMCDET->MC_PROGCUR;
			$data['default']['MC_PROGCURVAL'] 	= $getMCDET->MC_PROGCURVAL;
			$data['default']['MC_VALADD'] 		= $getMCDET->MC_VALADD;
			$data['default']['MC_MATVAL'] 		= $getMCDET->MC_MATVAL;
			$data['default']['MC_DPPER'] 		= $getMCDET->MC_DPPER;
			$data['default']['MC_DPVAL'] 		= $getMCDET->MC_DPVAL;
			$data['default']['MC_DPBACK'] 		= $getMCDET->MC_DPBACK;
			$data['default']['MC_DPBACKCUR'] 	= $getMCDET->MC_DPBACKCUR;
			$data['default']['MC_RETCUTP'] 		= $getMCDET->MC_RETCUTP;
			$data['default']['MC_RETCUT'] 		= $getMCDET->MC_RETCUT;
			$data['default']['MC_RETCUTCUR'] 	= $getMCDET->MC_RETCUTCUR;
			$data['default']['MC_PROGAPP'] 		= $getMCDET->MC_PROGAPP;
			$data['default']['MC_PROGAPPVAL']	= $getMCDET->MC_PROGAPPVAL;
			$data['default']['MC_AKUMNEXT'] 	= $getMCDET->MC_AKUMNEXT;
			$data['default']['MC_VALBEF']		= $getMCDET->MC_VALBEF;
			$data['default']['MC_TOTVAL'] 		= $getMCDET->MC_TOTVAL;
			$data['default']['MC_TOTVAL_PPn'] 	= $getMCDET->MC_TOTVAL_PPn;
			$data['default']['MC_TOTVAL_PPh'] 	= $getMCDET->MC_TOTVAL_PPh;
			$data['default']['MC_NOTES'] 		= $getMCDET->MC_NOTES;
			$data['default']['MC_EMPID'] 		= $getMCDET->MC_EMPID;
			$data['default']['MC_DOC'] 			= $getMCDET->MC_DOC;
			$data['default']['MC_STAT'] 		= $getMCDET->MC_STAT;
			$data['default']['PATT_YEAR'] 		= $getMCDET->PATT_YEAR;
			$data['default']['PATT_MONTH'] 		= $getMCDET->PATT_MONTH;
			$data['default']['PATT_DATE'] 		= $getMCDET->PATT_DATE;
			$data['default']['PATT_NUMBER'] 	= $getMCDET->PATT_NUMBER;
			$data['PRJCODE'] 					= $getMCDET->PRJCODE;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getMCDET->MC_CODE;
				$MenuCode 		= 'MN253';
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
			
			$this->load->view('v_project/v_project_mc/project_mc_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process() // OK
	{ 
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];	
		$this->load->model('m_project/m_project_mc/m_project_mc', '', TRUE);
		
		$sqlApp 			= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$this->db->trans_begin();
			date_default_timezone_set("Asia/Jakarta");
			
			//setting MC Date
			$MC_CHECKD		= date('Y-m-d');
			$MC_CREATED		= date('Y-m-d H:i:s');
			
			$MC_CODE 		= $this->input->post('MC_CODE');
			$MC_MANNO 		= $this->input->post('MC_MANNO');
			$MC_OWNER 		= $this->input->post('MC_OWNER');
			$MC_STEP 		= $this->input->post('MC_STEP');
			$PRJCODE 		= $this->input->post('PRJCODE');
			$PRJCOST 		= $this->input->post('PRJCOST');
			
			$MC_DATE		= date('Y-m-d',strtotime($this->input->post('MC_DATE')));
			$MC_STARTD		= date('Y-m-d',strtotime($this->input->post('MC_STARTD')));
			$MC_ENDDATE		= date('Y-m-d',strtotime($this->input->post('MC_ENDDATE')));
			$MC_CHECKD		= date('Y-m-d',strtotime($this->input->post('MC_CHECKD')));
			$PATT_YEAR		= date('Y',strtotime($this->input->post('MC_DATE')));
			$MC_CREATED		= date('Y-m-d H:i:s');
			$MC_DPVAL 		= $this->input->post('MC_DPVAL');
			$MC_PROGAPPVAL	= $this->input->post('MC_PROGAPPVAL');
			$MC_DPBACK 		= $this->input->post('MC_DPBACK');
			$MC_RETCUT 		= $this->input->post('MC_RETCUT');
			$MC_TOTVAL		= $this->input->post('MC_TOTVAL');
			$MC_AKUMNEXT	= $MC_DPVAL + $MC_PROGAPPVAL - $MC_DPBACK - $MC_RETCUT;
			//$MC_VALBEF		= $MC_AKUMNEXT - $MC_TOTVAL;
			$MC_VALBEF		= $this->input->post('MC_VALBEF');
			$MC_STAT		= $this->input->post('MC_STAT');

			// ============================= Start Upload File ========================================== //
			$this->load->library('upload');
		    $this->upload->initialize(["upload_path" => "assets/AdminLTE-2.0.5/doc_center/uploads/MC_Document",
									   "allowed_types" => "pdf",
									   // "max_size" => 1024,
									   "overwrite" => false
		  	]);

		  	if ($this->upload->do_multi_upload('MC_DOC'))
			{
				$data	= $this->upload->get_multi_upload_data();
				$MC_Document = [];
				foreach($data as $row):
					$MC_Document[] = $row['file_name'];
				endforeach;
				$MC_DOC = implode('~', $MC_Document);

				$dataMCH 		= array('MC_MANNO'		=> $MC_MANNO,
									'GETFROM'		=> 'GEN',
									'MC_STEP'		=> $this->input->post('MC_STEP'),
									'PRJCODE'		=> $PRJCODE,
									'PRJCOST'		=> $PRJCOST,
									'MC_OWNER'		=> $MC_OWNER,
									'MC_DATE'		=> $MC_DATE,
									'MC_STARTD'		=> $MC_STARTD,
									'MC_ENDDATE'	=> $MC_ENDDATE,
									'MC_CHECKD'		=> $MC_DATE,
									'MC_CREATED'	=> $MC_CREATED,
									'MC_RETVAL'		=> $this->input->post('MC_RETVAL'),
									'MC_PROGB'		=> $this->input->post('MC_PROGB'),
									'MC_PROG'		=> $this->input->post('MC_PROG'),
									'MC_PROGVAL'	=> $this->input->post('MC_PROGVAL'),
									'MC_PROGCUR'	=> $this->input->post('MC_PROGCUR'),
									'MC_PROGCURVAL'	=> $this->input->post('MC_PROGCURVAL'),
									'MC_VALADD'		=> $this->input->post('MC_VALADD'),
									'MC_MATVAL'		=> $this->input->post('MC_MATVAL'),
									'MC_DPPER'		=> $this->input->post('MC_DPPER'),
									'MC_DPVAL'		=> $this->input->post('MC_DPVAL'),
									'MC_DPBACK'		=> $this->input->post('MC_DPBACK'),
									'MC_DPBACKCUR'	=> $this->input->post('MC_DPBACKCUR'),
									'MC_RETCUTP'	=> $this->input->post('MC_RETCUTP'),
									'MC_RETCUT'		=> $this->input->post('MC_RETCUT'),
									'MC_RETCUTCUR'	=> $this->input->post('MC_RETCUTCUR'),
									'MC_PROGAPP'	=> $this->input->post('MC_PROGAPP'),
									'MC_PROGAPPVAL'	=> $this->input->post('MC_PROGAPPVAL'),
									'MC_AKUMNEXT'	=> $MC_AKUMNEXT,
									'MC_VALBEF'		=> $MC_VALBEF,
									'MC_TOTVAL'		=> $this->input->post('MC_TOTVAL'),
									'MC_TOTVAL_PPn'	=> $this->input->post('MC_TOTVAL_PPn'),
									'MC_TOTVAL_PPh'	=> $this->input->post('MC_TOTVAL_PPh'),
									'MC_NOTES'		=> addslashes($this->input->post('MC_NOTES')),
									'MC_EMPID'		=> $DefEmp_ID,
									'MC_DOC' 		=> $MC_DOC,
									'MC_STAT'		=> $MC_STAT,
									'PATT_YEAR'		=> $PATT_YEAR,
									'PATT_NUMBER'	=> $this->input->post('PATT_NUMBER'));
			}
			else
			{
				$dataMCH 		= array('MC_MANNO'		=> $MC_MANNO,
									'GETFROM'		=> 'GEN',
									'MC_STEP'		=> $this->input->post('MC_STEP'),
									'PRJCODE'		=> $PRJCODE,
									'PRJCOST'		=> $PRJCOST,
									'MC_OWNER'		=> $MC_OWNER,
									'MC_DATE'		=> $MC_DATE,
									'MC_STARTD'		=> $MC_STARTD,
									'MC_ENDDATE'	=> $MC_ENDDATE,
									'MC_CHECKD'		=> $MC_DATE,
									'MC_CREATED'	=> $MC_CREATED,
									'MC_RETVAL'		=> $this->input->post('MC_RETVAL'),
									'MC_PROGB'		=> $this->input->post('MC_PROGB'),
									'MC_PROG'		=> $this->input->post('MC_PROG'),
									'MC_PROGVAL'	=> $this->input->post('MC_PROGVAL'),
									'MC_PROGCUR'	=> $this->input->post('MC_PROGCUR'),
									'MC_PROGCURVAL'	=> $this->input->post('MC_PROGCURVAL'),
									'MC_VALADD'		=> $this->input->post('MC_VALADD'),
									'MC_MATVAL'		=> $this->input->post('MC_MATVAL'),
									'MC_DPPER'		=> $this->input->post('MC_DPPER'),
									'MC_DPVAL'		=> $this->input->post('MC_DPVAL'),
									'MC_DPBACK'		=> $this->input->post('MC_DPBACK'),
									'MC_DPBACKCUR'	=> $this->input->post('MC_DPBACKCUR'),
									'MC_RETCUTP'	=> $this->input->post('MC_RETCUTP'),
									'MC_RETCUT'		=> $this->input->post('MC_RETCUT'),
									'MC_RETCUTCUR'	=> $this->input->post('MC_RETCUTCUR'),
									'MC_PROGAPP'	=> $this->input->post('MC_PROGAPP'),
									'MC_PROGAPPVAL'	=> $this->input->post('MC_PROGAPPVAL'),
									'MC_AKUMNEXT'	=> $MC_AKUMNEXT,
									'MC_VALBEF'		=> $MC_VALBEF,
									'MC_TOTVAL'		=> $this->input->post('MC_TOTVAL'),
									'MC_TOTVAL_PPn'	=> $this->input->post('MC_TOTVAL_PPn'),
									'MC_TOTVAL_PPh'	=> $this->input->post('MC_TOTVAL_PPh'),
									'MC_NOTES'		=> addslashes($this->input->post('MC_NOTES')),
									'MC_EMPID'		=> $DefEmp_ID,
									'MC_DOC' 		=> $MC_DOC,
									// 'MC_STAT'		=> $MC_STAT,
									'PATT_YEAR'		=> $PATT_YEAR,
									'PATT_NUMBER'	=> $this->input->post('PATT_NUMBER'));
			}

			if($MC_STAT == 2)
			{
				// START : UPDATE FINANCIAL DASHBOARD
					$MC_VAL 	= $this->input->post('MC_TOTVAL');
					$finDASH 	= array('PRJCODE'	=> $PRJCODE,
										'PERIODE'	=> $MC_DATE,
										'FVAL'		=> $MC_VAL,
										'FNAME'		=> "MC_VAL");										
					$this->m_updash->updFINDASH($finDASH);
				// END : UPDATE FINANCIAL DASHBOARD
			}

			// ============================= End Upload File ========================================== //
			
			$this->m_project_mc->update($MC_CODE, $dataMCH, $PRJCODE);
			
			if($MC_STAT == 3)
			{
				$dataMCH = array('MC_STAT'		=> 7);						
				$this->m_project_mc->update($MC_CODE, $dataMCH, $PRJCODE);
			
				// START : SAVE APPROVE HISTORY
					$this->load->model('m_updash/m_updash', '', TRUE);
					
					$AH_CODE		= $MC_CODE;
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
			
			// SAVE TO PROFITLOSS
			$MC_STAT	= $this->input->post('MC_STAT');
			
			if($MC_STAT == 9)
			{				
				// START : UPDATE L/R
					$this->load->model('m_updash/m_updash', '', TRUE);
					$PERIODED	= $MC_DATE;
					$PERIODM	= date('m', strtotime($PERIODED));
					$PERIODY	= date('Y', strtotime($PERIODED));
					$MC_PROG		= $this->input->post('MC_PROG');
					$MC_PROGAPP		= $this->input->post('MC_PROGAPP');
					$MC_AKUMNEXT	= $this->input->post('MC_AKUMNEXT');
					$updLR			= "UPDATE tbl_profitloss SET PROGMC = PROGMC - $MC_PROG, PROGMC_PLAN = PROGMC_PLAN - $MC_PROG,
											 PROGMC_REAL = PROGMC_REAL - $MC_PROGAPP, PROGMC_REALA = PROGMC_REALA - $MC_AKUMNEXT
										WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
											AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				// END : UPDATE L/R
				
				// START : ADD CONCLUSION
					$MC_PAYMENT1	= $this->input->post('MC_PAYMENT');
					$MC_TOT_PPn		= $this->input->post('MC_TOTVAL_PPn');
					$MC_TOT_PPh		= $this->input->post('MC_TOTVAL_PPh');
					$MC_GTOT		= $MC_PAYMENT1 + $MC_TOT_PPn - $MC_TOT_PPh;
					
					$MC_PROG		= $this->input->post('MC_PROG');
					$MC_PROGVAL		= $this->input->post('MC_PROGVAL');									
					$MC_PROGAPP		= $this->input->post('MC_PROGAPP');
					$MC_PROGAPPVAL	= $this->input->post('MC_PROGAPPVAL');
					$MC_VALADD		= $this->input->post('MC_VALADD');
					$MC_RETVAL		= $this->input->post('MC_RETCUT');
					$MC_DPAMOUNT	= $this->input->post('MC_DPVAL');
					$MC_DPCUTAM		= $this->input->post('MC_DPBACK');
					$MC_TOTAMOUNT	= $MC_AKUMNEXT;
					$MC_TOTBEF		= $MC_VALBEF;
					$MC_TOT_NET		= $this->input->post('MC_PAYMENT');
					$MCP_BEFVAL		= $MC_VALBEF;
					$MC_TOT_PPn		= $MC_TOT_PPn;
					$MC_TOT_PPh		= $MC_TOT_PPh;
					$MC_GTOT		= $MC_GTOT;
					
					$updMCCON		= "UPDATE tbl_mc_conc SET MC_PROG = MC_PROG - $MC_PROG, MC_PROGVAL = MC_PROGVAL - $MC_PROGVAL,
											 MC_PROGAPPVAL = MC_PROGAPPVAL - $MC_PROGAPPVAL, MC_VALADD = MC_VALADD - $MC_VALADD,
											 MC_RETVAL = MC_RETVAL - $MC_RETVAL, MC_DPAMOUNT = MC_DPAMOUNT - $MC_DPAMOUNT,
											 MC_DPCUTAM = MC_DPCUTAM - $MC_DPCUTAM, MC_TOTAMOUNT = MC_TOTAMOUNT - $MC_TOTAMOUNT,
											 MC_TOTBEF = MC_TOTBEF - $MC_TOTBEF, MC_TOT_NET = MC_TOT_NET - $MC_TOT_NET,
											 MC_TOT_PPn = MC_TOT_PPn - $MC_TOT_PPn, MC_TOT_PPh = MC_TOT_PPh - $MC_TOT_PPh,
											 MC_GTOT = MC_GTOT - $MC_GTOT
										WHERE PRJCODE = '$PRJCODE' AND MCC_CODE = '$MC_CODE'";
					$this->db->query($updMCCON);
				// END : ADD CONCLUSION
			
				// Check untuk bulan yang sama
					$MC_DATEY	= date('Y',strtotime($MC_DATE));
					$MC_DATEM	= (int)date('m',strtotime($MC_DATE));
				// BUAT TANGGAL AKHIR BULAN PER SI
					$LASTDATE	= date('Y-m-t', strtotime($MC_DATE));
				
				$dataMCH = array('MC_STAT'	=> $this->input->post('MC_STAT'));						
				$this->m_project_mc->update($MC_CODE, $dataMCH, $PRJCODE);
				
				$updMCP		= "UPDATE tbl_mc_plan SET MCP_PROG = MCP_PROG - $MC_PROG, MCP_PROGVAL = MCP_PROGVAL - $MC_PROGVAL,
										 MCP_RETCUT = MCP_RETCUT - $MC_RETVAL, MCP_NEXTVAL = MCP_NEXTVAL - $MC_AKUMNEXT,
										 MCP_BEFVAL = MCP_BEFVAL - $MCP_BEFVAL, MCP_PROGAPP = MCP_PROGAPP - $MC_PROGAPP,
										 MCP_PROGAPPVAL = MCP_PROGAPPVAL - $MC_PROGAPPVAL, MCP_STATUS = $MC_STAT
									WHERE MCP_PRJCODE = '$PRJCODE' AND MCP_CODE = '$MC_CODE'";
				$this->db->query($updMCP);
			}
			else
			{
				if($AH_ISLAST == 1)
				{
					if($MC_STAT == 3)
					{
						// START : SETTING L/R
							$this->load->model('m_updash/m_updash', '', TRUE);
							$PERIODED	= $MC_DATE;
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
							$MC_PROG		= $this->input->post('MC_PROG');
							$MC_PROGAPP		= $this->input->post('MC_PROGAPP');
							$MC_PROGCUR		= $this->input->post('MC_PROGCUR');
							$MC_PROGCURVAL 	= $this->input->post('MC_PROGCURVAL');
							$updLR			= "UPDATE tbl_profitloss SET PROGMC = PROGMC + $MC_PROGCUR, PROGMC_PLAN = PROGMC_PLAN + $MC_PROGCUR,
													 PROGMC_REAL = PROGMC_REAL + $MC_PROGCURVAL, PROGMC_REALA = PROGMC_REALA + $MC_PROGCURVAL
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
													AND YEAR(PERIODE) = '$PERIODY'";
							$this->db->query($updLR);
						// END : UPDATE L/R
						
						// START : ADD CONCLUSION
							$MC_PAYMENT1	= $this->input->post('MC_PAYMENT');
							$MC_TOT_PPn		= $this->input->post('MC_TOTVAL_PPn');
							$MC_TOT_PPh		= $this->input->post('MC_TOTVAL_PPh');
							$MC_GTOT		= $MC_PAYMENT1 + $MC_TOT_PPn - $MC_TOT_PPh;
							$dataMCH 		= array('MCC_CODE' 	=> $MC_CODE,
												'MCC_STEP'		=> $this->input->post('MC_STEP'),
												'PRJCODE'		=> $PRJCODE,
												'MC_DATE'		=> $MC_DATE,
												'MC_PROG'		=> $this->input->post('MC_PROG'),
												'MC_PROGVAL'	=> $this->input->post('MC_PROGVAL'),									
												'MC_PROGAPP'	=> $this->input->post('MC_PROGAPP'),
												'MC_PROGAPPVAL'	=> $this->input->post('MC_PROGAPPVAL'),
												'MC_VALADD'		=> $this->input->post('MC_VALADD'),
												'MC_RETVAL'		=> $this->input->post('MC_RETCUT'),
												'MC_DPAMOUNT'	=> $this->input->post('MC_DPVAL'),
												'MC_DPCUTAM'	=> $this->input->post('MC_DPBACK'),
												'MC_TOTAMOUNT'	=> $MC_AKUMNEXT,
												'MC_TOTBEF'		=> $MC_VALBEF,
												'MC_TOT_NET'	=> $this->input->post('MC_PAYMENT'),
												'MC_TOT_PPn'	=> $MC_TOT_PPn,
												'MC_TOT_PPh'	=> $MC_TOT_PPh,
												'MC_GTOT'		=> $MC_GTOT);
							$this->m_project_mc->updateConc($MC_CODE, $dataMCH, $PRJCODE);
						// END : ADD CONCLUSION
					
						// Check untuk bulan yang sama
							$MC_DATEY	= date('Y',strtotime($MC_DATE));
							$MC_DATEM	= (int)date('m',strtotime($MC_DATE));
						// BUAT TANGGAL AKHIR BULAN PER SI
							$LASTDATE	= date('Y-m-t', strtotime($MC_DATE));
						
						$dataMCH = array('MC_STAT'	=> $this->input->post('MC_STAT'));						
						$this->m_project_mc->update($MC_CODE, $dataMCH, $PRJCODE);
				
						// CREATE MC MONITORING				
						$dataMCM 	= array('MCP_CODE' 		=> $MC_CODE,
											'MCP_PRJCODE'	=> $PRJCODE,
											'MCP_DATE'		=> $MC_DATE,
											'MCP_PROG'		=> $this->input->post('MC_PROG'),
											'MCP_PROGVAL'	=> $this->input->post('MC_PROGVAL'),
											'MCP_RETCUT'	=> $this->input->post('MC_RETCUT'),
											'MCP_NEXTVAL'	=> $MC_AKUMNEXT,
											'MCP_BEFVAL'	=> $MC_VALBEF,
											'MCP_PROGAPP'	=> $this->input->post('MC_PROGAPP'),
											'MCP_PROGAPPVAL'=> $this->input->post('MC_PROGAPPVAL'),
											'MCP_STATUS'	=> $MC_STAT);
						$this->m_project_mc->addMCM($dataMCM, $PRJCODE);
					}
				}
			}
			
			// START : CLEAR HISTORY
				$this->load->model('m_updash/m_updash', '', TRUE);
				if($MC_STAT == 4)
				{
					$cllPar = array('AH_CODE' 		=> $MC_CODE,
									'AH_APPROVER'	=> $DefEmp_ID);
					$this->m_updash->clearHist($cllPar);
				}
			// END : CLEAR HISTORY
			
			// COUNT DATA
				//$this->m_project_mc->count_all_mcnew($PRJCODE);
				//$this->m_project_mc->count_all_mccon($PRJCODE);
				//$this->m_project_mc->count_all_mcapp($PRJCODE);
			
			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('STAT_BEFORE');		// IF "ADD" CONDITION ALWAYS = PR_STAT
				$parameters 	= array('DOC_CODE' 		=> $MC_CODE,		// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "MC",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_mcheader",	// TABLE NAME
										'KEY_NAME'		=> "MC_CODE",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "MC_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $MC_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_MC",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_MC_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_MC_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_MC_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_MC_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_MC_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_MC_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $MC_CODE;
				$MenuCode 		= 'MN253';
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
			
			$url			= site_url('c_project/c_mc180c2c/gall180c2cmc/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
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
			$secIndex 		= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/c_mc180c2c'),'inbox');
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
		
		$url			= site_url('c_project/c_mc180c2c/prjls180c2cSI/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function prjls180c2cSI() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
			
		$LangID 	= $this->session->userdata['LangID'];
		// GET MENU DESC
			$mnCode				= 'MN259';
			$MenuCode			= 'MN259';
			$data["MenuCode"] 	= 'MN259';
			$data["MenuApp"] 	= 'MN259';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data["h1_title"] 	= "Site Instruction";
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN259';	 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_project/c_mc180c2c/gAlL180c2cSI/?id=";
			
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
		$this->load->model('m_project/m_project_mc/m_project_mc', '', TRUE);		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		$LangID 	= $this->session->userdata['LangID'];
		// GET MENU DESC
			$mnCode				= 'MN259';
			$MenuCode			= 'MN259';
			$data["MenuCode"] 	= 'MN259';
			$data["MenuApp"] 	= 'MN259';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODEX			= $_GET['id'];
			$PRJCODE			= $this->url_encryption_helper->decode_url($PRJCODEX);
			$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
			
			$data['title'] 			= $appName;
			$data['h2_title'] 		= 'SI Project List';
			$data['h3_title'] 		= 'SI Project';
			$cancel_url				= site_url('c_project/c_mc180c2c/prj180c2clsSI/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['backURL'] 		= $cancel_url;
			$data['PRJCODE'] 		= $PRJCODE;
			$data["selSearchCat"] 	= 'isSI';			
			$num_rows 				= $this->m_project_mc->count_all_num_rowsProjSI($PRJCODE);
			
			$data["recordcount"] 	= $num_rows;			
			$data['CATTYPE'] 		= 'isSI';
			$data['MenuCode']		= 'MN259';
			
			$data['viewprojinvoice'] = $this->m_project_mc->get_last_ten_projsi($PRJCODE)->result();
			
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
			
			$this->load->view('v_project/v_project_mc/project_si', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

  	function get_AllDataSI() // GOOD
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

			$columns_valid 	= array("SI_CODE",
									"SI_MANNO",
									"SI_DATE",
									"SI_ENDDATE",
									"SI_DESC",
									"SI_VALUE");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_project_mc->get_AllDataSIC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_project_mc->get_AllDataSIL($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$SI_CODE		= $dataI['SI_CODE'];
                $SI_MANNO		= $dataI['SI_MANNO'];
                $SI_MANNOD		= $SI_MANNO;
				if($SI_MANNO == '')
				{
					$SI_MANNOD	= 'Not Set';
				}
                $SI_DATE		= $dataI['SI_DATE'];
                $SI_DATEV		= strftime('%d %B %Y', strtotime($SI_DATE));
                $SI_ENDDATE		= $dataI['SI_ENDDATE'];
                $SI_APPDATE		= $dataI['SI_APPDATE'];
                $PRJCODE		= $dataI['PRJCODE'];
                $SI_DESC		= $dataI['SI_DESC'];
                $SI_VALUE		= $dataI['SI_VALUE'];
                $SI_APPVAL		= $dataI['SI_APPVAL'];
                $SI_NOTES		= $dataI['SI_NOTES'];
                $SI_STAT		= $dataI['SI_STAT'];

				if($SI_STAT == 0)
				{
					$SI_STATDes = "fake";
					$STATCOL	= 'danger';
				}
				elseif($SI_STAT == 1)
				{
					$SI_STATDes = "New";
					$STATCOL	= 'warning';
				}
				elseif($SI_STAT == 2)
				{
					$SI_STATDes = "Confirm";
					$STATCOL	= 'primary';
				}
				elseif($SI_STAT == 3)
				{
					$SI_STATDes = "Approve";
					$STATCOL	= 'success';
				}
				elseif($SI_STAT == 6)
				{
					$SI_STATDes = "Close";
					$STATCOL	= 'primary';
				}
				elseif($SI_STAT == 7)
				{
					$SI_STATDes = "Waiting";
					$STATCOL	= 'warning';
				}
				else
				{
					$SI_STATDes = "Fake";
					$STATCOL	= 'danger';
				}

				$secUpd			= site_url('c_project/c_mc180c2c/u180c2cpsi//?id='.$this->url_encryption_helper->encode_url($SI_CODE));
				$secDelURL 		= site_url('c_project/c_mc180c2c/deleteMC/?id='.$this->url_encryption_helper->encode_url($SI_CODE));
				$secPrint		= site_url('c_project/c_mc180c2c/printdocument/?id='.$this->url_encryption_helper->encode_url($SI_CODE));
				$CollID			= "SI~$SI_CODE~$PRJCODE";
				$secDel 		= base_url().'index.php/__l1y/trashDOC/?id=';
				$secVoid 		= base_url().'index.php/__l1y/trashMC/?id=';
				$delID 			= "$secDel~tbl_siheader~SI_CODE~$SI_CODE~PRJCODE~$PRJCODE";
				$voidID 		= "$secVoid~tbl_siheader~SI_CODE~$SI_CODE~PRJCODE~$PRJCODE";

				if($SI_STAT == 1 || $SI_STAT == 4)
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
				elseif($SI_STAT == 3 || $SI_STAT == 6) 
				{
					$secAction	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
									<input type='hidden' name='urlVoid".$noU."' id='urlVoid".$noU."' value='".$voidID."'>
								   <label style='white-space:nowrap'>
								   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   </a>
								   <a href='avascript:void(null);' class='btn btn-primary btn-xs' title='Print' onClick='printD(".$noU.")' disabled='disabled'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-warning btn-xs' onClick='voidDOC(".$noU.")' title='Void'>
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
								   <a href='avascript:void(null);' class='btn btn-primary btn-xs' title='Print' onClick='printD(".$noU.")' disabled='disabled'>
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

				$output['data'][]	= array("<div style='white-space:nowrap'>".$SI_CODE."</div>",
										  	"<strong>".$SI_MANNOD."</strong>",
										  	"<div style='white-space:nowrap'>
											  	<strong><i class='fa fa-calendar margin-r-5'></i> ".$Date." Pengajuan </strong>
										  		<div>
											  		<p class='text-muted' style='margin-left: 18px'>
											  			".$SI_DATEV."
											  		</p>
											  	</div>
										  	</div>",
										  	"<div>
											  	<strong><i class='fa fa-pencil margin-r-5'></i> ".$Description." </strong>
										  		<div>
											  		<p class='text-muted' style='margin-left: 18px'>
											  			".$SI_DESC."
											  		</p>
											  	</div>
										  	</div>",
										  	number_format($SI_VALUE,2),
										  	"<span class='label label-".$STATCOL."' style='font-size:12px'>".$SI_STATDes."</span>",
										  	"<div style='white-space:nowrap'>".$secAction."</div");
										  
				$noU		= $noU + 1;
			}
			
			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function a180c2cddsi() // OK
	{
		$this->load->model('m_project/m_project_mc/m_project_mc', '', TRUE);		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		$LangID 	= $this->session->userdata['LangID'];
		// GET MENU DESC
			$mnCode				= 'MN259';
			$MenuCode			= 'MN259';
			$data["MenuCode"] 	= 'MN259';
			$data["MenuApp"] 	= 'MN259';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE			= $_GET['id'];
			$PRJCODE			= $this->url_encryption_helper->decode_url($PRJCODE);
			$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
			
			$cancel_url					= site_url('c_project/c_mc180c2c/gAlL180c2cSI/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			$data['PRJCODE'] 			= $PRJCODE;
			$data['title'] 				= $appName;
			$data['task'] 				= 'add';
			$data['h2_title']			= 'Add Site Instruction';
			$data['h3_title']			= 'SI Project';
			$data['form_action']		= site_url('c_project/c_mc180c2c/addSI_process');
			$data['backURL'] 			= $cancel_url;
			
			$data['countPRJ']			= $this->m_project_mc->count_all_num_rowsProject();
			$data['viewProject'] 		= $this->m_project_mc->viewProject()->result();
			
			$MenuCode 					= 'MN259';
			$data['MenuCode']			= 'MN259';
			$data['viewDocPattern'] 	= $this->m_project_mc->getDataDocPat($MenuCode)->result();
			
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
			
			$this->load->view('v_project/v_project_mc/project_si_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function addSI_process() // OK
	{
		$this->load->model('m_project/m_project_mc/m_project_mc', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		
		$this->db->trans_begin();
		
		date_default_timezone_set("Asia/Jakarta");
		
		$SI_INCCON		= $this->input->post('SI_INCCON');
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
			$SI_APPDATE		= '0000-00-00 00:00:00';
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
		$this->m_project_mc->addSI($dataSIH, $PRJCODE);
		
		/*if($SI_AMAND == 1)
		{
			$this->m_project_mc->updateSIH($SI_CODE);
		}*/	
			
		// COUNT DATA
			//$this->m_project_mc->count_all_sinew($PRJCODE);
			//$this->m_project_mc->count_all_sicon($PRJCODE);
			//$this->m_project_mc->count_all_siapp($PRJCODE);
			
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
			
		$url			= site_url('c_project/c_mc180c2c/gAlL180c2cSI/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
		redirect($url);
	}
	
	function u180c2cpsi() // OK
	{
		$this->load->model('m_project/m_project_mc/m_project_mc', '', TRUE);		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$SI_CODE			= $_GET['id'];
			$SI_CODE			= $this->url_encryption_helper->decode_url($SI_CODE);
			
			$getSIDET 			= $this->m_project_mc->get_SI_by_number($SI_CODE)->row();
			$PRJCODE			= $getSIDET->PRJCODE;
			
			$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
					
			$data['PRJCODE'] 			= $PRJCODE;
			$data['title'] 				= $appName;
			$data['task'] 				= 'edit';
			$data['h2_title']			= 'Edit Site Instruction';
			$data['h3_title']			= 'SI Project';
			$data['MenuCode']			= 'MN259';
			$data['form_action']		= site_url('c_project/c_mc180c2c/updateSI_process');
			$cancel_url					= site_url('c_project/c_mc180c2c/gAlL180c2cSI/?id='.$this->url_encryption_helper->encode_url($PRJCODE));	
			//$data['link'] 				= array('link_back' => anchor("$cancel_url",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 			= $cancel_url;
			
			$data['countPRJ']			= $this->m_project_mc->count_all_num_rowsProject();
			$data['viewProject'] 		= $this->m_project_mc->viewProject()->result();
			
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
			
			$this->load->view('v_project/v_project_mc/project_si_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function updateSI_process() // OK
	{
		$this->load->model('m_project/m_project_mc/m_project_mc', '', TRUE);
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
			$SI_APPDATE	= '0000-00-00 00:00:00';
		}
		
		$SI_DATE		= date('Y-m-d',strtotime($this->input->post('SI_DATE')));
		$SI_ENDDATE		= date('Y-m-d',strtotime($this->input->post('SI_ENDDATE')));
		//$SI_APPDATE	= date('Y-m-d',strtotime($this->input->post('SI_APPDATE')));
		$SI_APPDATE		= date('Y-m-d H:i:s');
		$SI_APPDATE2	= date('Y-m-d H:i:s');
		//$SI_CREATED	= date('Y-m-d H:i:s');
		$PATT_YEAR		= date('Y',strtotime($this->input->post('SI_DATE')));
		
		$SI_INCCON		= $this->input->post('SI_INCCON');
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
		$this->m_project_mc->updateSI($SI_CODE, $dataSIH, $PRJCODE);
		
		/*if($SI_AMAND == 1)
		{
			$this->m_project_mc->updateSIH($SI_CODE);
		}*/
		
		// SAVE TO PROFITLOSS
		if($SI_STAT == 3)
		{
			$dataSIH 	= array('SI_STAT'	=> 7);							
			$this->m_project_mc->updateSI($SI_CODE, $dataSIH, $PRJCODE);
			// START : SAVE APPROVE HISTORY
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$AH_CODE		= $SI_CODE;
				$AH_APPLEV		= $this->input->post('APP_LEVEL');
				$AH_APPROVER	= $DefEmp_ID;
				$AH_APPROVED	= date('Y-m-d H:i:s');
				$AH_NOTES		= addslashes($this->input->post('MC_NOTES'));
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
			$this->m_project_mc->updateSI($SI_CODE, $dataSIH, $PRJCODE);
			
			// ADD TO CONTRACT
			if($SI_INCCON == 1)
			{
				$SI_VALUE	= $this->input->post('SI_VALUE');
			
				$dataPRJ 	= array('PRJNOTE'	=> $SI_CODE,
									'PRJCOST2'	=> $SI_VALUE,
									'REFCHGNO'	=> $SI_MANNO);							
				$this->m_project_mc->updatePRJ($dataPRJ, $PRJCODE);
				
			}
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
			
		$url			= site_url('c_project/c_mc180c2c/gAlL180c2cSI/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
		redirect($url);
	}
	
	function deleteMC() // OK
	{ 
		$this->load->model('m_project/m_project_mc/m_project_mc', '', TRUE);		
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
			
			$getSIDET 			= $this->m_project_mc->get_SI_by_number($SI_CODE)->row();
			$PRJCODE			= $getSIDET->PRJCODE;
		
			$this->db->trans_begin();
						
			$this->m_project_mc->deleteMC($SI_CODE);
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_project/c_mc180c2c/gAlL180c2cSI/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
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
		
		$this->m_project_mc->syncTable($PRJCODE);
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
		}
		else
		{
			$this->db->trans_commit();
		}
		//return false;
		redirect('c_project/c_mc180c2c/gall180c2cmc/'.$PRJCODE);
	}
	
	function popSIAppList() // OK
	{
		$this->load->model('m_project/m_project_mc/m_project_mc', '', TRUE);		
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
					
			$this->load->view('v_project/v_project_mc/project_si_view_app_List', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function popSIApp() // OK
	{
		$this->load->model('m_project/m_project_mc/m_project_mc', '', TRUE);		
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
				$this->load->view('v_project/v_project_mc/project_si_view_app_admin', $data);
			}
			else
			{
				$this->load->view('v_project/v_project_mc/project_si_view_app', $data);
			}
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function printdocument() // OK
	{
		$this->load->model('m_project/m_project_mc/m_project_mc', '', TRUE);

		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		if ($this->session->userdata('login') == TRUE)
		{
			$ID			    = $_GET['id'];
			$MC_CODE		= $this->url_encryption_helper->decode_url($ID);
			$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title'] 		= 'Document Print';
			$data['h3_title'] 		= 'Document Print';

     		$getMCDET 				= $this->m_project_mc->get_MC_by_number($MC_CODE)->row();
			$PRJCODE			 	= $getMCDET->PRJCODE;

			$data['PRJCODE'] 		= $PRJCODE;

			$data['countPRJ']		= $this->m_project_mc->count_all_num_rowsProject_byID($PRJCODE);
			$data['viewProject'] 	= $this->m_project_mc->viewProject_byID($PRJCODE)->result();

			$data['MC_CODE'] 		= $getMCDET->MC_CODE;
			$data['MC_MANNO'] 		= $getMCDET->MC_MANNO;
			$data['GETFROM'] 		= $getMCDET->GETFROM;
			$data['MC_STEP'] 		= $getMCDET->MC_STEP;
			$data['PRJCODE'] 		= $getMCDET->PRJCODE;
			$data['MC_OWNER'] 		= $getMCDET->MC_OWNER;
			$data['MC_DATE'] 		= date('Y-m-d', strtotime($getMCDET->MC_DATE));
			$data['MC_STARTD'] 		= date('Y-m-d', strtotime($getMCDET->MC_STARTD));
			$data['MC_ENDDATE'] 	= $getMCDET->MC_ENDDATE;
			$data['MC_CHECKD'] 		= $getMCDET->MC_CHECKD;
			$data['MC_CREATED'] 	= $getMCDET->MC_CREATED;
			$data['MC_RETVAL'] 		= $getMCDET->MC_RETVAL;
			$data['MC_RETCUT'] 		= $getMCDET->MC_RETCUT;
			$data['MC_DPPER'] 		= $getMCDET->MC_DPPER;
			$data['MC_DPVAL'] 		= $getMCDET->MC_DPVAL;
			$data['MC_DPBACK'] 		= $getMCDET->MC_DPBACK;
			$data['MC_PROG'] 		= $getMCDET->MC_PROG;
			$data['MC_PROGVAL'] 	= $getMCDET->MC_PROGVAL;
			$data['MC_PROGAPP'] 	= $getMCDET->MC_PROGAPP;
			$data['MC_PROGAPPVAL']	= $getMCDET->MC_PROGAPPVAL;
			$data['MC_VALADD'] 		= $getMCDET->MC_VALADD;
			$data['MC_MATVAL'] 		= $getMCDET->MC_MATVAL;
			$data['MC_VALBEF']		= $getMCDET->MC_VALBEF;
			$data['MC_AKUMNEXT'] 	= $getMCDET->MC_AKUMNEXT;
			$data['MC_TOTVAL'] 		= $getMCDET->MC_TOTVAL;
			$data['MC_TOTVAL_PPn'] 	= $getMCDET->MC_TOTVAL_PPn;
			$data['MC_TOTVAL_PPh'] 	= $getMCDET->MC_TOTVAL_PPh;
			$data['MC_NOTES'] 		= $getMCDET->MC_NOTES;
			$data['MC_EMPID'] 		= $getMCDET->MC_EMPID;
			$data['MC_STAT'] 		= $getMCDET->MC_STAT;
			$data['PATT_YEAR'] 		= $getMCDET->PATT_YEAR;
			$data['PATT_MONTH'] 	= $getMCDET->PATT_MONTH;
			$data['PATT_DATE'] 		= $getMCDET->PATT_DATE;
			$data['PATT_NUMBER'] 	= $getMCDET->PATT_NUMBER;
			$data['PRJCODE'] 		= $getMCDET->PRJCODE;

			// $data['link'] 				= array('link_back' => anchor('c_project/c_mc180c2c/gall180c2cmc/'.$getpurINVO->PRJCODE,'<input type="button" name="btnCancel" id="btnCancel" value="Cancel" class="btn btn-danger" />', array('style' => 'text-decoration: none;')));


			// START : UPDATE TO T-TRACK
				// date_default_timezone_set("Asia/Jakarta");
				// $DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				// $TTR_PRJCODE	= $PRJCODE;
				// $TTR_REFDOC		= $getpurINVO->PINV_CODE;
				// $MenuCode 		= 'MN232';
				// $TTR_CATEG		= 'P';
        //
				// $this->load->model('m_updash/m_updash', '', TRUE);
				// $paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
				// 						'TTR_DATE' 		=> date('Y-m-d H:i:s'),
				// 						'TTR_MNCODE'	=> $MenuCode,
				// 						'TTR_CATEG'		=> $TTR_CATEG,
				// 						'TTR_PRJCODE'	=> $TTR_PRJCODE,
				// 						'TTR_REFDOC'	=> $TTR_REFDOC);
				// $this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK

			$this->load->view('v_project/v_project_mc/project_mc_print', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function getOWNC()
	{
		$OWNCODE	= $_POST['OWNCODE'];
		$ownIns 	= 'S';
		$sqlOWN		= "SELECT own_Code, own_Title, own_Inst, own_Name FROM tbl_owner WHERE own_Code = '$OWNCODE'";
		$resOWN	= $this->db->query($sqlOWN)->result();
		foreach($resOWN as $rowOWN) :
			$ownIns 	= $rowOWN ->own_Inst;
		endforeach;
		echo $ownIns;
	}
}