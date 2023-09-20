<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 1 Agustus 2019
 * File Name	= C_bUd93tL15t.php
 * Location		= -
*/

class C_bUd93tL15t extends CI_Controller  
{
  	function __construct() // GOOD
	{
		parent::__construct();
		
		$this->load->model('m_company/m_budget/m_budget', '', TRUE);
		$this->load->model('m_company/m_budget/m_budget_mst', '', TRUE);
		$this->load->model('m_company/m_joblistdet/m_joblistdet', '', TRUE);
		$this->load->model('m_inventory/m_itemlist/m_itemlist', '', TRUE);
		$this->load->model('m_gl/m_coa/m_coa', '', TRUE);
		$this->load->model('m_updash/m_updash', '', TRUE);
		$this->load->model('m_company/m_boq/m_boq', '', TRUE);
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_project/m_listproject/m_listproject', '', TRUE);
	
		$this->data['UserID'] 		= $this->session->userdata['Emp_ID'];
		$this->data['appName'] 		= $this->session->userdata['appName'];
		$this->data['ISCREATE'] 	= $this->session->userdata['ISCREATE'];
		$this->data['ISAPPROVE'] 	= $this->session->userdata['ISAPPROVE'];
		$this->data['LangID']		= $this->session->userdata['LangID'];
		
		$LangID 	= $this->session->userdata['LangID'];
		$sqlTransl	= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
		$resTransl	= $this->db->query($sqlTransl)->result();
		foreach($resTransl as $rowTransl) :
			$TranslCode	= $rowTransl->MLANG_CODE;
			$LangTransl	= $rowTransl->LangTransl;
			if($TranslCode == 'Active')$Active = $LangTransl;
			if($TranslCode == 'Inactive')$Inactive = $LangTransl;
			if($TranslCode == 'Finish')$Finish = $LangTransl;
		endforeach;
		
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
			$collDATA		= isset($_GET['id']);
			$EXP_COLLD1		= $this->url_encryption_helper->decode_url($collDATA);	
			$EXP_COLLD		= explode('~', $EXP_COLLD1);	
			$C_COLLD1		= count($EXP_COLLD);
			if($C_COLLD1 > 1)
			{
				$EXP_COLLD1	= str_replace('~', '', $EXP_COLLD1);
				$PRJCODE_HO	= $EXP_COLLD[1];
			}
			else
			{
				$PRJCODE_HO	= $EXP_COLLD1;
			}
		
			$sqlISHO 		= "SELECT PRJCODE, PRJCODE_HO, PRJPERIOD FROM tbl_project_budg WHERE PRJCODE_HO = '$PRJCODE_HO' AND PRJSTAT = 1";
			$resISHO		= $this->db->query($sqlISHO)->result();
			foreach($resISHO as $rowISHO):
				$this->data['PRJCODE']		= $rowISHO->PRJCODE;
				$this->data['PRJCODE_HO']	= $rowISHO->PRJCODE_HO;
				$this->data['PRJPERIOD']	= $rowISHO->PRJPERIOD;
			endforeach;
	}
	
 	function index()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_comprof/c_bUd93tL15t/prjl0b28t18/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function prjl0b28t18() // OK - project list
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$LangID 	= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN408';
				$data["MenuCode"] 	= 'MN408';
				$data["MenuApp"] 	= 'MN408';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN408';	 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();		
			$data["secURL"] 	= "c_comprof/c_bUd93tL15t/i180c2gdx/?id=";
			
			$data["secVIEW"]	= 'v_projectlist/project_list_set';
			$data["frmBUDG"]	= 1;

			// TIDKA BERLAKU PEMILIHAN PERIOD
			/*if($this->session->userdata['nSELP'] == 99)
			{
				$PRJCODE	= $this->session->userdata['proj_Code'];
				$url		= site_url($data["secURL"].$this->url_encryption_helper->encode_url($PRJCODE));
				redirect($url);
			}
			else
			{
				$PRJCODE	= $this->session->userdata['proj_Code'];
				$url		= site_url($data["secURL"].$this->url_encryption_helper->encode_url($PRJCODE));
				//redirect($url);
				
				$this->load->view($data["secVIEW"], $data);
			}*/

			// UNTUK MELIHAT DAFTAR ANGGARAN PROYEK, TIDAK PERLU MEMILIH PROJECT HEADER
				$sqlISHO 		= "SELECT PRJCODE FROM tbl_project WHERE isHO = 1 AND PRJSTAT = 1";
				$resISHO		= $this->db->query($sqlISHO)->result();
				foreach($resISHO as $rowISHO):
					$PRJCODE	= $rowISHO->PRJCODE;
				endforeach;
				$url			= site_url($data["secURL"].$this->url_encryption_helper->encode_url($PRJCODE));
				redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function i180c2gdx()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			date_default_timezone_set("Asia/Jakarta");
			
			// -------------------- START : SEARCHING METHOD --------------------				
				$collDATA		= $_GET['id'];
				$EXP_COLLD1		= $this->url_encryption_helper->decode_url($collDATA);
				$EXP_COLLD		= explode('~', $EXP_COLLD1);	
				$C_COLLD1		= count($EXP_COLLD);
				
				if($C_COLLD1 > 1)
				{
					$EXP_COLLD1	= str_replace('~', '', $EXP_COLLD1);
					$key		= $EXP_COLLD[0];
					$PRJCODE	= $EXP_COLLD[1];
					$start		= 0;
					$end		= 30;
				}
				else
				{
					/*$sqlPRJHO	= "SELECT PRJCODE AS PRJCODEHO FROM tbl_project WHERE isHO = 1";
					$resPRJHO 	= $this->db->query($sqlPRJHO)->result();
					foreach($resPRJHO as $rowPRJHO) :
						$PRJCODEHO 	= $rowPRJHO->PRJCODEHO;
					endforeach;
					$key		= '';
					$PRJCODE	= $PRJCODEHO;
					$start		= 0;
					$end		= 50;*/
					
					$key		= '';
					$PRJCODE	= $EXP_COLLD1;
					$start		= 0;
					$end		= 50;
				}
			// -------------------- END : SEARCHING METHOD --------------------
			
			$data['title'] 		= $appName;
			$SOURCEDOC			= "";
			$COLLDATA			= "$PRJCODE~$SOURCEDOC";
			$data['addURL'] 	= site_url('c_comprof/c_bUd93tL15t/add/?id='.$this->url_encryption_helper->encode_url($COLLDATA));
			$data['backURL'] 	= site_url('c_comprof/c_bUd93tL15t/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
			$data['MenuCode'] 	= 'MN408';
			$data['COLLDATA'] 	= $COLLDATA;
			
			// GET MENU DESC
				$mnCode				= 'MN408';
				$data["MenuApp"] 	= 'MN408';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN408';
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
			
			$this->load->view('v_company/v_budget/v_budget', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function prjl0b28t18_MN246() // OK - project list FROM ABOUT COMPANY MENU
	{
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$LangID 	= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN246';
				$data["MenuCode"] 	= 'MN246';
				$data["MenuApp"] 	= 'MN246';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN246';	 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();		
			$data["secURL"] 	= "c_comprof/c_bUd93tL15t/i180c2gdx_MN246/?id=";
			
			$data["secVIEW"]	= 'v_projectlist/project_list_set_ho';
			$data["frmBUDG"]	= 1;

			// TIDKA BERLAKU PEMILIHAN PERIOD
			if($this->session->userdata['nSELP'] == 99)
			{
				$PRJCODE	= $this->session->userdata['proj_Code'];
				$url		= site_url($data["secURL"].$this->url_encryption_helper->encode_url($PRJCODE));
				redirect($url);
			}
			else
			{
				$PRJCODE	= $this->session->userdata['proj_Code'];
				$url		= site_url($data["secURL"].$this->url_encryption_helper->encode_url($PRJCODE));
				//redirect($url);
				
				$this->load->view($data["secVIEW"], $data);
			}

			// UNTUK MELIHAT DAFTAR ANGGARAN HO, TIDAK PERLU MEMILIH PROJECT HEADER
				/*$sqlISHO 		= "SELECT PRJCODE FROM tbl_project WHERE isHO = 1 AND PRJSTAT = 1";
				$resISHO		= $this->db->query($sqlISHO)->result();
				foreach($resISHO as $rowISHO):
					$PRJCODE	= $rowISHO->PRJCODE;
				endforeach;

			$data["PRJCODE"]	= $PRJCODE;

			$this->load->view($data["secVIEW"], $data);*/
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function i180c2gdx_MN246()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			date_default_timezone_set("Asia/Jakarta");
			
			// -------------------- START : SEARCHING METHOD --------------------
				$collDATA		= $_GET['id'];
				$EXP_COLLD1		= $this->url_encryption_helper->decode_url($collDATA);
				$EXP_COLLD		= explode('~', $EXP_COLLD1);	
				$C_COLLD1		= count($EXP_COLLD);
				
				if($C_COLLD1 > 1)
				{
					$EXP_COLLD1	= str_replace('~', '', $EXP_COLLD1);
					$key		= $EXP_COLLD[0];
					$PRJCODE	= $EXP_COLLD[1];
					$start		= 0;
					$end		= 30;
				}
				else
				{
					/*$sqlPRJHO	= "SELECT PRJCODE AS PRJCODEHO FROM tbl_project WHERE isHO = 1";
					$resPRJHO 	= $this->db->query($sqlPRJHO)->result();
					foreach($resPRJHO as $rowPRJHO) :
						$PRJCODEHO 	= $rowPRJHO->PRJCODEHO;
					endforeach;
					$key		= '';
					$PRJCODE	= $PRJCODEHO;
					$start		= 0;
					$end		= 50;*/
					
					$key		= '';
					$PRJCODE	= $EXP_COLLD1;
					$start		= 0;
					$end		= 50;
				}
			// -------------------- END : SEARCHING METHOD --------------------
			
			$data['title'] 		= $appName;
			$SOURCEDOC			= "";
			$COLLDATA			= "$PRJCODE~$SOURCEDOC";
			$data['addURL'] 	= site_url('c_comprof/c_bUd93tL15t/add/?id='.$this->url_encryption_helper->encode_url($COLLDATA));
			$data['backURL'] 	= site_url('c_comprof/c_bUd93tL15t/prjl0b28t18_MN246?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
			$data['MenuCode'] 	= 'MN246';
			$data['COLLDATA'] 	= $COLLDATA;
			
			// GET MENU DESC
				$mnCode				= 'MN246';
				$data["MenuApp"] 	= 'MN246';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN246';
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
			
			$this->load->view('v_projectlist/project_list_set_ho', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

  	function get_AllData() // GOOD
	{
		setlocale(LC_ALL, 'id-ID', 'id_ID');

		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		$PRJCODE	= $_GET['id'];

		$LangID     = $this->session->userdata['LangID'];
        
        $sqlTransl      = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
        $resTransl      = $this->db->query($sqlTransl)->result();
        foreach($resTransl as $rowTransl) :
            $TranslCode = $rowTransl->MLANG_CODE;
            $LangTransl = $rowTransl->LangTransl;

    		if($TranslCode == 'Director')$Director = $LangTransl;
            if($TranslCode == 'Compailer')$Compailer = $LangTransl;
            if($TranslCode == 'Location')$Location = $LangTransl;
    		if($TranslCode == 'Active')$Active = $LangTransl;
    		if($TranslCode == 'Inactive')$Inactive = $LangTransl;
    		if($TranslCode == 'Contact')$Contact = $LangTransl;
    		if($TranslCode == 'CompanyName')$CompanyName = $LangTransl;
    		if($TranslCode == 'Budget')$Budget = $LangTransl;
    		if($TranslCode == 'Phone')$Phone = $LangTransl;
    		if($TranslCode == 'Notes')$Notes = $LangTransl;
    		if($TranslCode == 'Periode')$Periode = $LangTransl;
    		if($TranslCode == 'ProjectManager')$ProjectManager = $LangTransl;
    		if($TranslCode == 'Remarks')$Remarks = $LangTransl;
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
			
			$columns_valid 	= array("PRJPERIOD", 
									"PRJCODE_HO", 
									"PRJNAME",
									"PRJCOST",
									"PRJDATE",
									"PRJEDAT");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_budget->get_AllDataC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			
			$query 			= $this->m_budget->get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$proj_Number 	= $dataI['proj_Number'];
				$PRJCODE 		= $dataI['PRJCODE'];
				$PRJCODE_HO		= $dataI['PRJCODE_HO'];
				$PRJPERIOD		= $dataI['PRJPERIOD'];
				$PRJCNUM		= $dataI['PRJCNUM'];
				$PRJNAME		= $dataI['PRJNAME'];
				$PRJCOST		= $dataI['PRJCOST'];
				$PRJDATE		= $dataI['PRJDATE'];
				$myDateProj 	= $dataI['PRJDATE'];
				$PRJEDAT		= $dataI['PRJEDAT'];
				$PRJEDATV		= strftime('%d %b %Y', strtotime($PRJEDAT));
				$PRJSTAT		= $dataI['PRJSTAT'];
				$isActif 		= $dataI['PRJSTAT'];
				$PRJADD			= $dataI['PRJADD'];
				$PRJLOCT		= $dataI['PRJLOCT'];
				$PRJTELP		= $dataI['PRJTELP'];
				$PRJ_MNG		= $dataI['PRJ_MNG'];
				$PRJFAX			= $dataI['PRJFAX'];
				$PRJMAIL		= $dataI['PRJMAIL'];
				$PRJNOTE		= $dataI['PRJNOTE'];
				$PRJLEV			= $dataI['PRJLEV'];

				// GET ADDRESS
					$PRJADD2 		= $PRJLOCT;
					if($PRJADD != '')
						$PRJADD2	= $PRJADD." - ".$PRJLOCT;

					if($PRJTELP != '')
						$PRJADD2	= $PRJADD2."<br>Telp. : ".$PRJTELP;

					if($PRJMAIL != '')
						$PRJADD2	= $PRJADD2.". E-mail: ".$PRJMAIL;
				
				// GET PROJECT DETAIL
					//$sqlX 	= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE_HO'";
					$sqlX 	= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
					$result = $this->db->query($sqlX)->result();
					foreach($result as $rowx) :
						$PRJNAME1	= $rowx->PRJNAME;
					endforeach;
				
					$PRJNAME2	= "-";
					$sqlX 	= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE_HO'";
					$result = $this->db->query($sqlX)->result();
					foreach($result as $rowx) :
						$PRJNAME2	= $rowx->PRJNAME;
					endforeach;
				
					if($myDateProj == '0000-00-00')
					{
						$sqlX = "SELECT PRJDATE FROM tbl_project WHERE PRJCODE = '$prjcode'";
						$result = $this->db->query($sqlX)->result();
						foreach($result as $rowx) :
							$PRJDATE	= $rowx->PRJDATE;
						endforeach;
					}
					$PRJDATEV		= strftime('%d %b %Y', strtotime($PRJDATE));
				
				// PRJ BUDGET DETAIL
					$TOTBP		= 0;	// Budget Plan
					$TOTADD		= 0;
					$TOTUSEDM	= 0;
					$sqlBudg 	= "SELECT SUM(ITM_BUDG) AS TOTBP, SUM(ADD_JOBCOST) AS TOTADD, SUM(ITM_USED_AM) AS TOTUSEDM FROM tbl_joblist_detail 
									WHERE ISLAST = 1 AND PRJCODE = '$PRJCODE'";
					$resBudg 	= $this->db->query($sqlBudg)->result();
					foreach($resBudg as $rowBT) :
						$TOTBP		= $rowBT->TOTBP;
						$TOTADD		= $rowBT->TOTADD;
						$TOTUSEDM	= $rowBT->TOTUSEDM;
					endforeach;
					$TOTBUDG	= $TOTBP + $TOTADD;
					if($TOTBUDG == 0)
						$TOTBUDG	= 1;
					
					$PERCUSED	= round($TOTUSEDM / $TOTBUDG * 100, 0);
					if($PERCUSED <= 25)
						$GRFCOL	= 'danger';
					elseif($PERCUSED <= 50)
						$GRFCOL	= 'warning';
					elseif($PERCUSED <= 75)
						$GRFCOL	= 'primary';
					elseif($PERCUSED <= 100)
						$GRFCOL	= 'success';
				
				// STATUS DESC
					if($isActif == 1)
					{
						$isActDesc 	= $Active;
						$STATCOL	= 'success';
					}
					else
					{
						$isActDesc 	= $Inactive;
						$STATCOL	= 'danger';
					}

				// PM AREA
					if($PRJ_MNG == '')
					{
						$imgMng			= base_url('assets/AdminLTE-2.0.5/emp_image/username.jpg');
						$PRJ_MNG 		= "[ no selected ID ]";
						$EMPNAMEMng		= "[ no selected ]";
						$BirthPlace		= "-";
						$BirthDate		= "0000-00-00";
					}
					else
					{
						$imgempfNmX 	= "username.jpg";
						$sqlIMGCrt		= "SELECT imgemp_filenameX FROM tbl_employee_img WHERE imgemp_empid = '$PRJ_MNG'";
						$resIMGCrt 		= $this->db->query($sqlIMGCrt)->result();
						foreach($resIMGCrt as $rowGIMGCrt) :
							$imgempfNmX = $rowGIMGCrt->imgemp_filenameX;
						endforeach;
						
						$imgMng			= base_url('assets/AdminLTE-2.0.5/emp_image/'.$PRJ_MNG.'/'.$imgempfNmX);
						if (!file_exists('assets/AdminLTE-2.0.5/emp_image/'.$PRJ_MNG))
						{
							$imgMng		= base_url('assets/AdminLTE-2.0.5/emp_image/username.jpg');
						}

						$EMPNAMEMng		= "";
						$BirthPlace		= "-";
						$BirthDate		= "0000-00-00";
						$sqlEmpCrt		= "SELECT CONCAT(First_Name, ' ', Last_Name) AS EMP_NAME, Birth_Place, Date_Of_Birth FROM tbl_employee WHERE Emp_ID = '$PRJ_MNG'";
						$resEmpCrt 		= $this->db->query($sqlEmpCrt)->result();
						foreach($resEmpCrt as $rowEmpCrt) :
							$EMPNAMEMng	= strtolower($rowEmpCrt->EMP_NAME);
							$BirthPlace	= strtolower($rowEmpCrt->Birth_Place);
							$BirthDate	= strftime('%d %b %Y', strtotime($rowEmpCrt->Date_Of_Birth));
						endforeach;
					}
				
				$collData	= "$PRJCODE~$PRJPERIOD";
				$secUpd		= site_url('c_comprof/c_bUd93tL15t/u180c2gdt/?id='.$this->url_encryption_helper->encode_url($proj_Number));
				$secDetCOA	= site_url('c_comprof/c_bUd93tL15t/iNc04/?id='.$this->url_encryption_helper->encode_url($collData));
				$secDetail	= site_url('c_comprof/c_bUd93tL15t/gl180c21JL/?id='.$this->url_encryption_helper->encode_url($collData));
				$secDetITM	= site_url('c_comprof/c_bUd93tL15t/iNI7m/?id='.$this->url_encryption_helper->encode_url($collData));
				$secvwPRJ	= site_url('c_comprof/c_bUd93tL15t/c_project_progress/?id='.$this->url_encryption_helper->encode_url($proj_Number));
				
				// CEK BUDGET DETAIL
				$sqlBUDGC	= "tbl_joblist_detail WHERE PRJCODE = '$PRJCODE'";
				$resBUDGC 	= $this->db->count_all($sqlBUDGC);
				
				// CEK COA
				$sqlCOAC	= "tbl_chartaccount WHERE PRJCODE = '$PRJCODE'";
				$resCOAC 	= $this->db->count_all($sqlCOAC);
                
				$secPrint		= "<input type='hidden' name='COA".$noU."' id='COA".$noU."' value='".$resCOAC."'>
									<input type='hidden' name='BUD".$noU."' id='BUD".$noU."' value='".$resBUDGC."'>
									<label style='white-space:nowrap'>
									   	<a href='".$secUpd."' class='btn btn-warning btn-xs' title='Update'>
											<i class='glyphicon glyphicon-pencil'></i>
									   	</a>
										<a href='".$secDetCOA."' class='btn btn-primary btn-xs' title='Detail COA'>
											<i class='fa fa-book'></i>
										</a>
										<a href='".$secDetail."' class='btn btn-info btn-xs' title='Detail Budget'>
                                            <i class='fa fa-cogs'></i>
                                        </a>
										<a href='".$secDetITM."' class='btn bg-purple btn-xs' title='Detail Item'>
                                            <i class='glyphicon glyphicon-equalizer'></i>
                                        </a>
                                        <a href='".$secvwPRJ."' class='btn btn-success btn-xs' title='Progress' style='display: none'>
                                            <i class='glyphicon glyphicon-stats'></i>
                                        </a>
									</label>";

				$output['data'][] 	= array("<strong style='font-size:13px'>".$PRJCODE."</strong>",
									  		"<div style='white-space:nowrap'>
									  			<strong><i class='fa fa-calendar margin-r-5'></i>".$Periode."</strong>
										  		<div style='margin-left: 20px'>
											  		".$PRJDATEV." - ".$PRJEDATV."
											  	</div>
											  	<strong><i class='fa fa-user margin-r-5'></i>".$ProjectManager." </strong><br>
												<div class='box-comments' style='background-color: transparent; margin-left: 15px'>
											  		<div class='box-comment'>
										                <img class='img-circle img-sm' src='".$imgMng."' alt='User Image'>
										                <div class='comment-text'>
										                   	<span class='username'>
										                        ".ucwords($EMPNAMEMng)."
										                    </span>
									                  		".$PRJ_MNG."<br>
									                  		".ucwords($BirthPlace).", ".$BirthDate."<strong>
										                </div>
										            </div>
									            </div>
											</div>",
											"<strong><i class='fa fa-building margin-r-5'></i>".strtoupper($PRJNAME1)." </strong><br>
								            <strong><i class='fa fa-map-marker margin-r-5'></i>&nbsp;&nbsp;".$Location." - ".$Contact." </strong>
									  		<div style='margin-left: 17px'>
										  		<div>".$PRJADD2."</div>
										  	</div>
										  	<strong><i class='fa fa-money margin-r-5'></i>".$Budget."</strong>
									  		<div style='margin-left: 18px'>
										  		IDR ".number_format($PRJCOST,2)."
										  	</div>
										  	<strong><i class='fa fa-tags margin-r-5'></i>".$Remarks." </strong><br>
									  		<div style='margin-left: 17px'>
										  		<p class='text-muted'>
										  			".$PRJNOTE."
										  		</p>
										  	</div>",
											"<div style='text-align:center; white-space:nowrap'><span class='label label-".$STATCOL."' style='font-size:12px'>".$isActDesc."</span></div>",
											"<div style='text-align:center;'>".$secPrint."</div>");

				$noU			= $noU + 1;
			}
			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function add() // OK
	{
		$PRJCODE		= $_GET['id'];
		$PRJCODE		= $this->url_encryption_helper->decode_url($PRJCODE);
		$pgFrom			= $_GET['pgFrom'];

		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName 	= $therow->app_name;		
		endforeach;

		if($pgFrom == 'HO')
			$mnCode 	= 'MN246';
		else
			$mnCode 	= 'MN408';
		
		// GET MENU DESC
			$data["MenuApp"] 	= $mnCode;
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$EmpID 			= $this->session->userdata('Emp_ID');

			$data['pgFrom'] 	= $pgFrom;
			$data['title'] 		= $appName;
			$data['task'] 		= 'add';
			$data['h2_title']	= 'Add Project';
			$data['main_view'] 	= 'v_company/v_budget/v_budget_form';
			$data['main_view2'] = 'v_company/v_budget/getaddress_sd';
			$data['form_action']= site_url('c_comprof/c_bUd93tL15t/add_process');
			$data['backURL'] 	= site_url('c_comprof/c_bUd93tL15t/i180c2gdx/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			$MenuCode 				= $mnCode;
			$data['MenuCode'] 		= $mnCode;
			$data['PRJCODE'] 		= $PRJCODE;
			$data['viewDocPattern'] = $this->m_budget->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= $mnCode;
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
			
			$this->load->view('v_company/v_budget/v_budget_form', $data);
		}
		else
		{
			redirect('__I1y');
		}
	}
		
	function getTheCode($PRJPERIOD) // OK
	{
		$this->load->model('m_project/m_listproject/m_listproject', '', TRUE);
		$recordcountProj 	= $this->m_budget->count_all_num_rowsProj($PRJPERIOD);

		$sqlPRJ		= "tbl_project_budg WHERE PRJCODE = '$PRJPERIOD'";
		$resPRJ 	= $this->db->count_all($sqlPRJ);

		$sqlPRJ1	= "tbl_project WHERE PRJCODE = '$PRJPERIOD'";
		$resPRJ1 	= $this->db->count_all($sqlPRJ1);

		$isExist	= $resPRJ + $resPRJ1;
		echo $isExist;
	}
	
	function add_process() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		date_default_timezone_set("Asia/Jakarta");
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();
			
			$pgFrom 		= $this->input->post('pgFrom');
			$proj_Number	= $this->input->post('proj_Number');
			$PRJCODE_HO		= $this->input->post('PRJCODE_HO');
			$PRJCODE 		= $this->input->post('PRJPERIOD');
			$PRJPERIOD 		= $this->input->post('PRJPERIOD');
			$PRJCATEG 		= $this->input->post('PRJCATEG');
			if($PRJCATEG == 'HO')
				$PRJLEV 	= 2;		// Budget Periode of Head Office
			else
				$PRJLEV 	= 0;		// Budget Periode of Project

			$PRJPER_P 		= '';
			$PRJCATEG 		= '';
			$PRJSCATEG		= 0;
			$PRJLOCT 		= '';
			$sqlPERIOD_P 	= "SELECT PRJPERIOD, PRJCATEG, PRJSCATEG, PRJLOCT FROM tbl_project WHERE PRJCODE = '$PRJCODE_HO'";
			$resPERIOD_P	= $this->db->query($sqlPERIOD_P)->result();
			foreach($resPERIOD_P as $rowPP) :
				$PRJPER_P 	= $rowPP->PRJPERIOD;
				//$PRJCATEG 	= $rowPP->PRJCATEG;
				$PRJSCATEG 	= $rowPP->PRJSCATEG;
				$PRJLOCT 	= $rowPP->PRJLOCT;		
			endforeach;
			$PRJPERIOD_P	= $PRJPER_P;

			$PRJNAME 		= $this->input->post('PRJNAME');
			//$PRJCODE		= "$PRJCODE_HO.$PRJPERIOD";
			
			$PRJDATE		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('PRJDATE'))));
			$PRJEDAT		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('PRJEDAT'))));
			$PRJDATE_CO		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('PRJDATE_CO'))));
			$PRJCOST 		= $this->input->post('PRJCOST');
			$PRJ_MNG1		= $this->input->post('PRJ_MNG');
			$PRJNOTE		= addslashes($this->input->post('PRJNOTE'));
			$PRJSTAT 		= $this->input->post('PRJSTAT');
			$PRJCATEG 		= $PRJCATEG;
			$PRJLOCT 		= $PRJLOCT;
			$PRJCNUM		= '';
			$PRJOWN 		= '';
			$PRJCURR		= 'IDR';
			$PRJCBNG		= '';
			$PRJLKOT		= '';
			$PRJTYPE		= 3; // 1 HOLDING COMPANY, 2 COMPANY, 3 BUDGET PERIODE
			$Patt_Year		= date('Y',strtotime($PRJDATE));
			
			// CEK PATTERN FOR STEP PERIODE
			$sqlSPrdC		= "tbl_project_budg WHERE PRJCODE_HO = '$PRJCODE_HO'";
			$sresSPrdC 		= $this->db->count_all($sqlSPrdC);
			$PattNumb		= $sresSPrdC + 1;
			$Patt_Number	= $PattNumb;
			/*if($Patt_Number > 4)
			{
				echo "Can not create periode anymore.<br>Please back.";
				return false;
			}*/
			
			$CURRRATE		= 1;
			
			$selStep		= 0;
			$PRJ_MNG		= '';
			if($PRJ_MNG1 != '')
			{
				foreach ($PRJ_MNG1 as $sel_pm)
				{
					$selStep	= $selStep + 1;
					if($selStep == 1)
					{
						$user_to	= explode ("|",$sel_pm);
						$user_ID	= $user_to[0];
						$PRJ_MNG	= $user_ID;
					}
					else
					{					
						$user_to	= explode ("|",$sel_pm);
						$user_ID	= $user_to[0];			
						$PRJ_MNG	= "$TASKD_EMPID2;$user_ID";
					}
				}
			}

			if($PRJSCATEG == 1)	// JIKA KONTRAKTOR
				$PRJTYPE	= 2;
			else
				$PRJTYPE	= 3;
			
			// DISABLE OTHER BUDGET
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			$DATE_CREATED	= date('Y-m-d H:i:s');
			if($PRJSTAT == 1)
			{
				if($PRJSCATEG != 1)
				{
					$upBOQ	= "UPDATE tbl_boqlist SET BOQ_STAT = 0, oth_reason = 'Disabled by $DefEmp_ID on $DATE_CREATED' 
								WHERE PRJCODE != '$PRJCODE' AND PRJCODE_HO = '$PRJCODE_HO'";
					$this->db->query($upBOQ);
					$upJL	= "UPDATE tbl_joblist SET WBS_STAT = 0, oth_reason = 'Disabled by $DefEmp_ID on $DATE_CREATED'
								WHERE PRJCODE != '$PRJCODE' AND PRJCODE_HO = '$PRJCODE_HO'";
					$this->db->query($upJL);
					$upJLD	= "UPDATE tbl_joblist_detail SET WBSD_STAT = 0, oth_reason = 'Disabled by $DefEmp_ID on $DATE_CREATED'
								WHERE PRJCODE != '$PRJCODE' AND PRJCODE_HO = '$PRJCODE_HO'";
					$this->db->query($upJLD);
					$upITM	= "UPDATE tbl_item SET STATUS = 0, oth_reason = 'Disabled by $DefEmp_ID on $DATE_CREATED'
								WHERE PRJCODE != '$PRJCODE' AND PRJCODE_HO = '$PRJCODE_HO'";
					$this->db->query($upITM);
					$upBUDG	= "UPDATE tbl_project SET PRJSTAT = 2, oth_reason = 'Disabled by $DefEmp_ID on $DATE_CREATED'
								WHERE PRJCODE != '$PRJCODE' AND PRJCODE_HO = '$PRJCODE_HO' AND PRJTYPE = 3";
					$this->db->query($upBUDG);
					$upBUDG	= "UPDATE tbl_project_budgm SET PRJSTAT = 2, oth_reason = 'Disabled by $DefEmp_ID on $DATE_CREATED'
								WHERE PRJCODE != '$PRJCODE' AND PRJCODE_HO = '$PRJCODE_HO' AND PRJTYPE = 3";
					$this->db->query($upBUDG);
					$upBUDG	= "UPDATE tbl_project_budg SET PRJSTAT = 2, oth_reason = 'Disabled by $DefEmp_ID on $DATE_CREATED'
								WHERE PRJCODE != '$PRJCODE' AND PRJCODE_HO = '$PRJCODE_HO' AND PRJTYPE = 3";
					$this->db->query($upBUDG);
				}
			}

			// CREATE JOBLIST VIEW
				$PRJCODEVW 		= preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE);
				
				$s_cv 			= "CREATE VIEW vw_joblist_detail_$PRJCODEVW AS
									SELECT ORD_ID,
										JOBCODEDET, JOBCODEID, JOBPARENT, JOBCODE, PRJCODE, PRJCODE_HO, PRJPERIOD, JOBDESC, ITM_GROUP, GROUP_CATEG,
										ITM_CODE, ITM_UNIT, ITM_VOLM, ITM_PRICE, ITM_LASTP, ITM_AVGP, ITM_BUDG, BOQ_VOLM, BOQ_PRICE, BOQ_JOBCOST,
										BOQ_BOBOT, ISBOBOT, BOQ_AMDVOLM, BOQ_AMDPRICE, BOQ_AMDTOTAL, ADD_VOLM, ADD_PRICE, ADD_JOBCOST, ADDM_VOLM,
										ADDM_JOBCOST, REQ_VOLM, REQ_AMOUNT, PO_VOLM, PO_AMOUNT, IR_VOLM, IR_AMOUNT, WO_QTY, WO_AMOUNT,
										OPN_QTY, OPN_AMOUNT, ITM_USED, ITM_USED_AM, ITM_STOCK, ITM_STOCK_AM, IS_LEVEL, ISLAST
									FROM tbl_joblist_detail 
									WHERE PRJCODE = '$PRJCODE'";
				$this->db->query($s_cv);

			$projectheader = array('proj_Number' 	=> $proj_Number,
									'PRJCODE'		=> $PRJCODE,
									'PRJCODE_HO'	=> $PRJCODE_HO,
									'PRJPERIOD'		=> $PRJPERIOD,
									'PRJPERIOD_P'	=> $PRJPERIOD_P,
									'PRJCNUM'		=> $PRJCNUM,
									'PRJNAME'		=> $PRJNAME,
									'PRJLOCT'		=> $PRJLOCT,
									'PRJCATEG'		=> $PRJCATEG,
									'PRJLEV'		=> $PRJLEV,
									'PRJOWN'		=> $PRJOWN,
									'PRJDATE'		=> $PRJDATE,
									'PRJDATE_CO'	=> $PRJDATE_CO,
									'PRJEDAT'		=> $PRJEDAT, 
									'PRJBOQ'		=> $PRJCOST,
									'PRJCOST'		=> $PRJCOST,
									'PRJLKOT'		=> $PRJLKOT,
									'PRJCBNG'		=> $PRJCBNG,
									'PRJCURR'		=> $PRJCURR,
									'CURRRATE'		=> $CURRRATE,
									'PRJSTAT'		=> $PRJSTAT,
									'PRJNOTE'		=> $PRJNOTE,
									'PRJ_MNG'		=> $PRJ_MNG,
									'BUDG_LEVEL'	=> 2,
									'PRJTYPE'		=> $PRJTYPE,
									'PRJCODEVW'		=> $PRJCODEVW,
									'Patt_Year'		=> $Patt_Year,
									'Patt_Number'	=> $Patt_Number);
			$this->m_budget->add($projectheader);
			
			// START : CREATE LABA RUGI
				$LR_CODE	= date('YmdHis');
				$PERIODE	= date('Y-m-d');
				$LR_CREATER	= $this->session->userdata['Emp_ID'];
				$LR_CREATED	= date('Y-m-d H:i:s');
				
				$projectLR 	= array('LR_CODE'			=> $LR_CODE,
								'PERIODE'				=> $PERIODE,
								'PRJCODE'				=> $PRJCODE,
								'PRJPERIOD'				=> $PRJPERIOD,
								'PRJNAME'				=> $PRJNAME,
								'PRJCOST'				=> $PRJCOST,
								'LR_CREATER'			=> $LR_CREATER,
								'LR_CREATED'			=> $LR_CREATED);
				$this->m_budget->addLR($projectLR);
			// END : UPDATE LABA RUGI
			
			// isHO untuk di budget selalu bernilai 2, karena
			// 0 isPRJ
			// 1 isHO
			// 2 isBUDGET
			// START : COPY COA
			$sqlCOA	= "tbl_chartaccount WHERE PRJCODE = '$PRJCODE'";
			$resCOA = $this->db->count_all($sqlCOA);

			// START : COPY COA
				$ORD_ID		= $resCOA;
				$sqlAcc		= "SELECT Acc_ID, Account_Class, Account_Number, Account_NameEn, Account_NameId, Account_Category,
									Account_Level, Acc_DirParent, Acc_ParentList, Acc_StatusLinked, Company_ID, Default_Acc,
									Currency_id, Base_OpeningBalance, Base_Debet, Base_Kredit, Base_Debet_tax, Base_Kredit_tax,
									IsInterCompany, isCostComponent, isOnDuty, isFOHCost, Base_Debet2, Base_Kredit2,
									Base_Debet_tax2, Base_Kredit_tax2, COGSReportID, isHO, syncPRJ, isLast
								FROM tbl_chartaccount
								WHERE 
									PRJCODE = '$PRJCODE_HO'";
				$resAcc 	= $this->db->query($sqlAcc)->result();
				foreach($resAcc as $rowAcc) :
					$ORD_ID				= $ORD_ID + 1;
					$PRJCODE			= $PRJCODE;
					$Acc_ID 			= $rowAcc->Acc_ID;
					$Account_Class 		= $rowAcc->Account_Class;
					$Account_Number 	= $rowAcc->Account_Number;
					$Account_NameEn 	= $rowAcc->Account_NameEn;
					$Account_NameId 	= $rowAcc->Account_NameId;
					$Account_Category 	= $rowAcc->Account_Category;
					$Account_Level 		= $rowAcc->Account_Level;
					$Acc_DirParent 		= $rowAcc->Acc_DirParent;
					$Acc_ParentList 	= $rowAcc->Acc_ParentList;
					$Acc_StatusLinked 	= $rowAcc->Acc_StatusLinked;
					$Company_ID 		= $rowAcc->Company_ID;
					$Default_Acc 		= $rowAcc->Default_Acc;
					$Currency_id 		= $rowAcc->Currency_id;
					$isHO 				= 2;	// isBudget

					if($Account_Class == 3 || $Account_Class == 4)
					{
						$Base_OpeningBalance= $rowAcc->Base_OpeningBalance;
						$Base_Debet 		= $rowAcc->Base_Debet;
						$Base_Kredit 		= $rowAcc->Base_Kredit;
						$Base_Debet_tax 	= $rowAcc->Base_Debet_tax;
						$Base_Kredit_tax 	= $rowAcc->Base_Kredit_tax;						
					}
					else
					{
						// SEMENTAR SEMUA AKUN DI NOL KAN BAIK KAS/BANK
							$Base_OpeningBalance= 0;
							$Base_Debet 		= 0;
							$Base_Kredit 		= 0;
							$Base_Debet_tax 	= 0;
							$Base_Kredit_tax 	= 0;						
					}
						
					$IsInterCompany 	= $rowAcc->IsInterCompany;
					$isCostComponent 	= $rowAcc->isCostComponent;
					$isOnDuty 			= $rowAcc->isOnDuty;
					$isFOHCost 			= $rowAcc->isFOHCost;

					//if($isHO == 1)
					if($Account_Class == 3 || $Account_Class == 4)
					{
						$Base_Debet2 		= $rowAcc->Base_Debet2;
						$Base_Kredit2 		= $rowAcc->Base_Kredit2;
						$Base_Debet_tax2 	= $rowAcc->Base_Debet_tax2;
						$Base_Kredit_tax2 	= $rowAcc->Base_Kredit_tax2;					
					}
					else
					{
						// SEMENTAR SEMUA AKUN DI NOL KAN BAIK KAS/BANK
							$Base_Debet2		= 0;
							$Base_Kredit2 		= 0;
							$Base_Debet_tax2 	= 0;
							$Base_Kredit_tax2 	= 0;						
					}
						
					$COGSReportID 		= $rowAcc->COGSReportID;
					$syncPRJ1 			= $rowAcc->syncPRJ;
					$syncPRJ			= "$syncPRJ1~$PRJCODE";
					//$syncPRJ			= $syncPRJHO;
					$isLast 			= $rowAcc->isLast;
					$sqlInsrAcc			= "INSERT INTO tbl_chartaccount 
												(ORD_ID, PRJCODE, PRJCODE_HO, Acc_ID, Account_Class, Account_Number, Account_NameEn, Account_NameId, 
												Account_Category, Account_Level, Acc_DirParent, Acc_ParentList, Acc_StatusLinked,
												Company_ID, Default_Acc, Currency_id, Base_OpeningBalance, Base_Debet, Base_Kredit, 
												Base_Debet_tax, Base_Kredit_tax, IsInterCompany, isCostComponent, isOnDuty, isFOHCost,
												Base_Debet2, Base_Kredit2, Base_Debet_tax2, Base_Kredit_tax2, COGSReportID, isHO,
												syncPRJ, isLast) 
											VALUES 
												($ORD_ID, '$PRJCODE', '$PRJCODE_HO', '$Acc_ID', '$Account_Class', '$Account_Number', '$Account_NameEn', 
												'$Account_NameId', '$Account_Category', '$Account_Level', '$Acc_DirParent', 
												'$Acc_ParentList', '$Acc_StatusLinked', '$Company_ID', '$Default_Acc','$Currency_id', 
												'$Base_OpeningBalance', '$Base_Debet', '$Base_Kredit', '$Base_Debet_tax', 
												'$Base_Kredit_tax', '$IsInterCompany', '$isCostComponent', '$isOnDuty', '$isFOHCost',
												'$Base_Debet2', '$Base_Kredit2', '$Base_Debet_tax2', '$Base_Kredit_tax2', 
												'$COGSReportID', '$isHO', '$syncPRJ', '$isLast')";
					$this->db->query($sqlInsrAcc);
				endforeach;
			// END : COPY COA

			// tbl_boqlist, tbl_joblist, tbl_joblist_detail, tbl_item, tbl_project, tbl_project_budg, tbl_project_budgm, tbl_profitloss, tbl_chartaccount
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			if($pgFrom == 'HO')
				$url	= site_url('c_comprof/c_bUd93tL15t/prjl0b28t18_MN246/?id='.$this->url_encryption_helper->encode_url($PRJCODE_HO));
			else
				$url	= site_url('c_comprof/c_bUd93tL15t/i180c2gdx/?id='.$this->url_encryption_helper->encode_url($PRJCODE_HO));

			redirect($url);
		}
		else
		{
			redirect('__I1y');
		}
	}
	
	function do_upload1()
	{
		$this->load->model('m_project/m_listproject/m_listproject', '', TRUE);
		
		$PRJCODE			= $this->input->post('PRJCODE');
		
		// CEK FILE
        $file 				= $_FILES['userfile'];
		$nameFile			= $_FILES["userfile"]["name"];
		$ext 				= end((explode(".", $nameFile)));
			
		if (!file_exists('assets/AdminLTE-2.0.5/project_image/'.$PRJCODE))
		{
			mkdir('assets/AdminLTE-2.0.5/project_image/'.$PRJCODE, 0777, true);
		}
		
		$file 						= $_FILES['userfile'];
		$file_name 					= $file['name'];
		$config['upload_path']   	= "assets/AdminLTE-2.0.5/project_image/$PRJCODE/"; 
		$config['allowed_types']	= 'gif|jpg|png'; 
		$config['overwrite'] 		= TRUE;
		$config['max_size']     	= 1000000; 
		$config['max_width']    	= 10024; 
		$config['max_height']    	= 10000;  
		$config['file_name']       	= $file['name'];
		
        $this->load->library('upload', $config);
		
        if ( ! $this->upload->do_upload('userfile')) 
		{
			//$data['Emp_ID']		= $Emp_ID;
			//$data['task'] 		= 'edit';
         }
         else 
		 {
            //$data['path']			= $file_name;
			//$data['Emp_ID']			= $Emp_ID;
			//$data['task'] 			= 'edit';
            //$data['showSetting']	= 0;
            $this->m_budget->updatePict($PRJCODE, $nameFile);
         }
		 
         $sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName 	= $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_comprof/c_bUd93tL15t/i180c2gdx/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function u180c2gdt()
	{
		$this->load->model('m_project/m_listproject/m_listproject', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN408';
			$data["MenuApp"] 	= 'MN408';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$proj_Number	= $_GET['id'];
			$proj_Number	= $this->url_encryption_helper->decode_url($proj_Number);

			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title'] 		= 'Edit Project';
			$data['main_view'] 		= 'v_company/v_budget/v_budget_form';
			$data['form_action']	= site_url('c_comprof/c_bUd93tL15t/update_process');
			
			$data['recordcountCust'] 	= $this->m_budget->count_all_num_rowsCust();
			$data['viewcustomer'] 		= $this->m_budget->viewcustomer()->result();
			
			$MenuCode 					= 'MN408';
			$data['MenuCode'] 			= 'MN408';
			$data['viewDocPattern'] 	= $this->m_budget->getDataDocPat($MenuCode)->result();

			$getproject = $this->m_budget->get_PROJ_by_number($proj_Number)->row();
					
			$data['default']['proj_Number'] = $getproject->proj_Number;
			$PRJCODE						= $getproject->PRJCODE;
			$PRJCODE_HO						= $getproject->PRJCODE_HO;
			$data['PRJCODE'] 				= $getproject->PRJCODE;
			$data['default']['PRJCODE'] 	= $getproject->PRJCODE;
			$data['default']['PRJCODE_HO'] 	= $getproject->PRJCODE_HO;
			$data['default']['PRJPERIOD'] 	= $getproject->PRJPERIOD;
			$data['default']['PRJPERIOD_P'] = $getproject->PRJPERIOD_P;
			$data['default']['PRJCNUM'] 	= $getproject->PRJCNUM;
			$data['default']['PRJNAME'] 	= $getproject->PRJNAME;
			$data['default']['PRJLOCT'] 	= $getproject->PRJLOCT;
			$data['default']['PRJCATEG'] 	= $getproject->PRJCATEG;
			$data['default']['PRJOWN'] 		= $getproject->PRJOWN;
			$data['default']['PRJDATE'] 	= $getproject->PRJDATE;
			$data['default']['PRJDATE_CO'] 	= $getproject->PRJDATE_CO;
			$data['default']['PRJEDAT'] 	= $getproject->PRJEDAT;
			$PRJEDAT						= $getproject->PRJEDAT;
			//echo "c_hehe $PRJEDAT";
			$data['default']['PRJCOST'] 	= $getproject->PRJCOST;
			$data['default']['PRJBOQ'] 		= $getproject->PRJBOQ;
			$data['default']['PRJRAP'] 		= $getproject->PRJRAP;
			$data['default']['PRJLKOT'] 	= $getproject->PRJLKOT;
			$data['default']['PRJCBNG']		= $getproject->PRJCBNG;
			$data['default']['PRJCURR']		= $getproject->PRJCURR;
			$data['default']['CURRRATE']	= $getproject->CURRRATE;
			$data['default']['PRJSTAT'] 	= $getproject->PRJSTAT;
			$data['default']['PRJNOTE'] 	= $getproject->PRJNOTE;
			$data['default']['isHO']		= $getproject->isHO;
			
			$data['default']['ISCHANGE']	= $getproject->ISCHANGE;
			$data['default']['REFCHGNO']	= $getproject->REFCHGNO;
			$data['default']['PRJCOST2'] 	= $getproject->PRJCOST2;
			$data['default']['CHGUSER'] 	= $getproject->CHGUSER;
			$data['default']['CHGSTAT'] 	= $getproject->CHGSTAT;
			$data['default']['PRJ_MNG'] 	= $getproject->PRJ_MNG;
			$data['default']['QTY_SPYR'] 	= $getproject->QTY_SPYR;
			$data['default']['PRC_STRK'] 	= $getproject->PRC_STRK;
			$data['default']['PRC_ARST'] 	= $getproject->PRC_ARST;
			$data['default']['PRC_MKNK'] 	= $getproject->PRC_MKNK;
			$data['default']['PRC_ELCT'] 	= $getproject->PRC_ELCT;
			$data['default']['PRJ_IMGNAME'] = $getproject->PRJ_IMGNAME;
			$data['default']['PRJ_ACCUM'] 	= $getproject->PRJ_ACCUM;
			$data['default']['Patt_Year'] 	= $getproject->Patt_Year;
			$data['default']['Patt_Number'] = $getproject->Patt_Number;

			$data['isUploaded']				= 0;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getproject->proj_Number;
				$MenuCode 		= 'MN408';
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
			
			$data['backURL']	= site_url('c_comprof/c_bUd93tL15t/i180c2gdx/?id='.$this->url_encryption_helper->encode_url($PRJCODE_HO));
			$this->load->view('v_company/v_budget/v_budget_form', $data);
		}
		else
		{
			redirect('__I1y');
		}
	}
	
	function update_process()
	{
		$this->load->model('m_project/m_listproject/m_listproject', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();
			
			date_default_timezone_set("Asia/Jakarta");
			$DATE_CREATED	= date('Y-m-d H:i:s');
			
			$proj_Number	= $this->input->post('proj_Number');
			$PRJCODE_HO		= $this->input->post('PRJCODE_HO');
			$PRJPERIOD 		= $this->input->post('PRJPERIOD');
			$PRJCATEG 		= $this->input->post('PRJCATEG');
			if($PRJCATEG == 'HO')
				$PRJLEV 	= 2;		// Budget Periode of Head Office
			else
				$PRJLEV 	= 0;		// Budget Periode of Project

			$PRJPER_P 		= '';
			$PRJCATEG 		= '';
			$PRJSCATEG		= 0;
			$PRJLOCT 		= '';
			$sqlPERIOD_P 	= "SELECT PRJPERIOD, PRJCATEG, PRJSCATEG, PRJLOCT FROM tbl_project WHERE PRJCODE = '$PRJCODE_HO'";
			$resPERIOD_P	= $this->db->query($sqlPERIOD_P)->result();
			foreach($resPERIOD_P as $rowPP) :
				$PRJPER_P 	= $rowPP->PRJPERIOD;
				//$PRJCATEG 	= $rowPP->PRJCATEG;
				$PRJSCATEG 	= $rowPP->PRJSCATEG;
				$PRJLOCT 	= $rowPP->PRJLOCT;		
			endforeach;
			$PRJPERIOD_P	= $PRJPER_P;

			$PRJNAME 		= $this->input->post('PRJNAME');
			//$PRJCODE		= "$PRJCODE_HO.$PRJPERIOD";
			$PRJCODE		= "$PRJPERIOD";
			
			$PRJDATE		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('PRJDATE'))));
			$PRJDATE_CO		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('PRJDATE_CO'))));
			$PRJEDAT		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('PRJEDAT'))));
			$PRJCNUM 		= $this->input->post('PRJCNUM');
			$PRJCATEG 		= $this->input->post('PRJCATEG');
			$PRJOWN 		= $this->input->post('PRJOWN');
			$PRJCURR		= $this->input->post('PRJCURR');
			$PRJCOST 		= $this->input->post('PRJCOST');
			$PRJLOCT 		= addslashes($this->input->post('PRJLOCT'));
			$PRJLKOT 		= addslashes($this->input->post('PRJLKOT'));
			$PRJ_MNG1		= $this->input->post('PRJ_MNG');
			$PRJNOTE		=addslashes( $this->input->post('PRJNOTE'));
			$PRJSTAT 		= $this->input->post('PRJSTAT');			
			$PRJCBNG		= '';			
			$Patt_Year		= date('Y',strtotime($this->input->post('PRJDATE')));
			
			$CURRRATE		= 1;
			
			$selStep	= 0;
			$PRJ_MNG	= '';
			if($PRJ_MNG1 != '')
			{
				foreach ($PRJ_MNG1 as $sel_pm)
				{
					$selStep	= $selStep + 1;
					if($selStep == 1)
					{
						$user_to	= explode ("|",$sel_pm);
						$user_ID	= $user_to[0];
						$PRJ_MNG	= $user_ID;
						//$coll_MADD	= $user_ADD;
					}
					else
					{					
						$user_to	= explode ("|",$sel_pm);
						$user_ID	= $user_to[0];			
						$PRJ_MNG	= "$TASKD_EMPID2;$user_ID";
						//$coll_MADD	= "$coll_MADD;$user_ADD";
					}
				}
			}

			if($PRJSCATEG == 1)	// JIKA KONTRAKTOR
			{
				$PRJTYPE	= 2;
			}
			else
			{
				$PRJTYPE	= 3;
			}

			// DISABLE OTHER BUDGET
			if($PRJSTAT == 1)
			{
				if($PRJSCATEG != 1)
				{
					$upBOQ	= "UPDATE tbl_boqlist SET BOQ_STAT = 0, oth_reason = 'Disabled by $DefEmp_ID on $DATE_CREATED' 
								WHERE PRJCODE != '$PRJCODE' AND PRJCODE_HO = '$PRJCODE_HO'";
					$this->db->query($upBOQ);
					$upJL	= "UPDATE tbl_joblist SET WBS_STAT = 0, oth_reason = 'Disabled by $DefEmp_ID on $DATE_CREATED'
								WHERE PRJCODE != '$PRJCODE' AND PRJCODE_HO = '$PRJCODE_HO'";
					$this->db->query($upJL);
					$upJLD	= "UPDATE tbl_joblist_detail SET WBSD_STAT = 0, oth_reason = 'Disabled by $DefEmp_ID on $DATE_CREATED'
								WHERE PRJCODE != '$PRJCODE' AND PRJCODE_HO = '$PRJCODE_HO'";
					$this->db->query($upJLD);
					$upITM	= "UPDATE tbl_item SET STATUS = 0, oth_reason = 'Disabled by $DefEmp_ID on $DATE_CREATED'
								WHERE PRJCODE != '$PRJCODE' AND PRJCODE_HO = '$PRJCODE_HO'";
					$this->db->query($upITM);
					$upBUDG	= "UPDATE tbl_project SET PRJSTAT = 2, oth_reason = 'Disabled by $DefEmp_ID on $DATE_CREATED'
								WHERE PRJCODE != '$PRJCODE' AND PRJCODE_HO = '$PRJCODE_HO' AND PRJTYPE = 3";
					$this->db->query($upBUDG);
					$upBUDG	= "UPDATE tbl_project_budgm SET PRJSTAT = 2, oth_reason = 'Disabled by $DefEmp_ID on $DATE_CREATED'
								WHERE PRJCODE != '$PRJCODE' AND PRJCODE_HO = '$PRJCODE_HO' AND PRJTYPE = 3";
					$this->db->query($upBUDG);
					$upBUDG	= "UPDATE tbl_project_budg SET PRJSTAT = 2, oth_reason = 'Disabled by $DefEmp_ID on $DATE_CREATED'
								WHERE PRJCODE != '$PRJCODE' AND PRJCODE_HO = '$PRJCODE_HO' AND PRJTYPE = 3";
					$this->db->query($upBUDG);
				}
			}
			
			$projectheader = array('PRJCODE'		=> $PRJCODE,
									'PRJCODE_HO'	=> $PRJCODE_HO,
									'PRJPERIOD'		=> $PRJPERIOD,
									'PRJCNUM'		=> $PRJCNUM,
									'PRJNAME'		=> $PRJNAME,
									'PRJLOCT'		=> $PRJLOCT,
									'PRJCATEG'		=> $PRJCATEG,
									'PRJLEV' 		=> $PRJLEV,
									'PRJOWN'		=> $PRJOWN,
									'PRJDATE'		=> $PRJDATE,
									'PRJDATE_CO'	=> $PRJDATE_CO,
									'PRJEDAT'		=> $PRJEDAT,
									'PRJBOQ'		=> $PRJCOST,
									'PRJCOST'		=> $PRJCOST,
									'PRJLKOT'		=> $PRJLKOT,
									'PRJCBNG'		=> $PRJCBNG,
									'PRJCURR'		=> $PRJCURR,
									'CURRRATE'		=> $CURRRATE,
									'PRJSTAT'		=> $PRJSTAT,
									'PRJNOTE'		=> $PRJNOTE,
									'PRJ_MNG'		=> $PRJ_MNG,
									'BUDG_LEVEL'	=> 2,
									'PRJTYPE'		=> $PRJTYPE,
									'Patt_Year'		=> $Patt_Year);
			$this->m_budget->update($proj_Number, $projectheader);
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $proj_Number;
				$MenuCode 		= 'MN408';
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
			
			$url			= site_url('c_comprof/c_bUd93tL15t/i180c2gdx/?id='.$this->url_encryption_helper->encode_url($PRJCODE_HO));
			redirect($url);
		}
		else
		{
			redirect('__I1y');
		}
	}
	
	function vInpProjDet($PRJCODE) // OK
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_project/m_listproject/m_listproject', '', TRUE);
			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;	
			
			$data['proj_Code'] 		= $PRJCODE;
			$data['title'] 			= $appName;
			$data['form_action']	= site_url('c_comprof/c_bUd93tL15t/vInpProjDet_process');
			$data['h2_title'] 		= 'Input Project Progress';
			
			$this->load->view('v_company/v_budget/project_sd_detInput', $data);
		}
		else
		{
			redirect('__I1y');
		}
	}
	
	function vInpProjDet_process() // HOLD - LANGSUNG DI HALAMAN POP UP
	{
		$this->load->model('m_project/m_listproject/m_listproject', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		$this->db->trans_begin();
		
		//$this->m_budget->deleteProjDet($this->input->post('proj_Code'));
		
		// untuk penyimpanan ke tabel tproject_header
		$projectDet = array('proj_Code' 	=> $this->input->post('proj_Code'),
						'OrigProj_Value'	=> $this->input->post('OrigProj_Value'),
						'Remeasure_Value'	=> $this->input->post('Remeasure_Value'),
						'SIntruc_Value'		=> $this->input->post('SIntruc_Value'),
						
						'RemPropos_Value'	=> $this->input->post('RemPropos_Value'),
						'RemApprov_Value'	=> $this->input->post('RemApprov_Value'),
						'RemDenied_Value'	=> $this->input->post('RemDenied_Value'),
						
						'SInPropos_Value'	=> $this->input->post('SInPropos_Value'),
						'SInApprov_Value'	=> $this->input->post('SInApprov_Value'),
						'SInDenied_Value'	=> $this->input->post('SInDenied_Value'),
						
						'CIDP_Value'		=> $this->input->post('CIDP_Value'),
						'CIProgress_Value'	=> $this->input->post('CIProgress_Value'),
						'CIOthers_Value'	=> $this->input->post('CIOthers_Value'),
						
						'COSDBP_Value'		=> $this->input->post('COSDBP_Value'),
						'COOStanding_Value'	=> $this->input->post('COOStanding_Value'),
						
						'LastUpdate'		=> $this->input->post('LastUpdate'),
						'UpdatedBy'			=> $this->input->post('UpdatedBy'));	
											
		$this->m_budget->addInpProjDet($projectDet);
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
		}
		else
		{
			$this->db->trans_commit();
		}
		
		$this->session->set_flashdata('message', 'Data succesfull to insert.!');
		redirect('c_comprof/c_bUd93tL15t/');
	}
	
	function vProjPerform($PRJCODE) // OK
	{
		$this->load->model('m_project/m_listproject/m_listproject', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;		
		if ($this->session->userdata('login') == TRUE)
		{			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title'] 		= 'View Project Performance';
			
			$data['proj_Code'] 		= $PRJCODE;
			
			$this->load->view('v_company/v_budget/project_sd_perform', $data);
		}
		else
		{
			redirect('__I1y');
		}
	}
	
    function inbox($offset=0)
	{
		$this->load->model('m_project/m_listproject/m_listproject', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 		= $appName;
			$data['h2_title']	= 'Project List Inbox';
			$data['main_view'] 	= 'v_company/v_budget/v_budget_inbox';

			/*$num_rows = $this->m_budget->count_all_num_rows_inbox();
			$data["recordcount"] = $num_rows;
			$config = array();
			$config['base_url'] = site_url('c_comprof/c_bUd93tL15t/get_last_ten_project');
			$config["total_rows"] = $num_rows;
			$config["per_page"] = 2;
			$config["uri_segment"] = 3;
				
			$config['full_tag_open'] = '<ul class="tsc_pagination tsc_paginationA tsc_paginationA01">';
			$config['full_tag_close'] = '</ul>';
			$config['prev_link'] = '&lt;';
			$config['prev_tag_open'] = '<li>';
			$config['prev_tag_close'] = '</li>';
			$config['next_link'] = '&gt;';
			$config['next_tag_open'] = '<li>';
			$config['next_tag_close'] = '</li>';
			$config['cur_tag_open'] = '<li class="current"><a href="#">';
			$config['cur_tag_close'] = '</a></li>';
			$config['num_tag_open'] = '<li>';
			$config['num_tag_close'] = '</li>';
			
			$config['first_tag_open'] = '<li>';
			$config['first_tag_close'] = '</li>';
			$config['last_tag_open'] = '<li>';
			$config['last_tag_close'] = '</li>';
			
			$config['first_link'] = '&lt;&lt;';
			$config['last_link'] = '&gt;&gt;';
	 		
			$this->pagination->initialize($config);
	 		
			$data['viewpurord'] = $this->m_budget->get_last_ten_PR_inbox($config["per_page"], $offset)->result();
			$data["pagination"] = $this->pagination->create_links();*/	
			
			$this->load->view('template', $data);
		}
		else
		{
			redirect('__I1y');
		}
    }
	
	function getVendAddress($vendCode)
	{
		$this->load->model('m_project/m_listproject/m_listproject', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$data['myVendCode']		= "$vendCode";
		$sql = "SELECT Vend_Code, Vend_Name, Vend_Address FROM tvendor
					WHERE Vend_Code = '$vendCode'";
		$result1 = $this->db->query($sql)->result();
		foreach($result1 as $row) :
			$Vend_Name = $row->Vend_Address;
		endforeach;
		echo $Vend_Name;
	}

	function gl180c21JL() // OK
	{
		if ($this->session->userdata('login') == TRUE)
		{	
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;

			// START : GET MENU NAME
				$MenuCode 			= 'MN408';
				$data['MenuCode'] 	= 'MN408';
				$data['MenuApp'] 	= 'MN408';
				$MenuCode			= 'MN408';
				$getMN 				= $this->m_updash->get_menunm($MenuCode)->row();
				if($this->data['LangID'] == 'IND')
					$data['mnName'] = $getMN->menu_name_IND;
				else
					$data['mnName'] = $getMN->menu_name_ENG;
			// END : GET MENU NAME
			
			$collData			= $_GET['id'];
			$collData			= $this->url_encryption_helper->decode_url($collData);
			$dataArray			= explode("~", $collData);
			$PRJCODE			= $dataArray[0];
			$PRJPERIOD			= $dataArray[0];

			$PRJCODE_HO			= '';
			$sqlPRJ 			= "SELECT PRJCODE_HO FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
			$resPRJ 			= $this->db->query($sqlPRJ)->result();
			foreach($resPRJ as $rowPRJ) :
				$PRJCODE_HO = $rowPRJ->PRJCODE_HO;		
			endforeach;
			
			$data['title'] 		= $appName;
			$data['secAddURL'] 	= site_url('c_comprof/c_bUd93tL15t/addjl/?id='.$this->url_encryption_helper->encode_url($PRJCODE)); 
			$data['secUpl'] 	= site_url('c_comprof/c_bUd93tL15t/jl180e2elst/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['backURL']	= site_url('c_comprof/c_bUd93tL15t/i180c2gdx/?id='.$this->url_encryption_helper->encode_url($PRJCODE_HO));
			
			$num_rows 			= $this->m_budget->count_all_schedule($PRJCODE, $PRJPERIOD);
			$data['countjobl'] 	= $num_rows;
			$data['vwjoblist'] 	= $this->m_budget->get_all_joblist($PRJCODE, $PRJPERIOD)->result();
			
			$getprojname 		= $this->m_budget->get_project_name($PRJCODE, $PRJPERIOD)->row();
			$data['PRJNAME'] 	= $getprojname->PRJNAME;
			$data['BUDGNAME'] 	= $getprojname->BUDGNAME;
			$data['PRJCODE'] 	= $PRJCODE;
			
			$MenuCode 			= 'MN408';
			$data["MenuCode"] 	= 'MN408';
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $PRJPERIOD;
				$MenuCode 		= 'MN408';
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
			
			$this->load->view('v_company/v_budget/v_joblistdet', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function jl180e2elst($offset=0) // OK
	{
		if ($this->session->userdata('login') == TRUE)
		{	
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			// GET MENU DESC
				$mnCode				= 'MN404';
				$data["MenuApp"] 	= 'MN404';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$PRJCODE				= $_GET['id'];
			$PRJCODE				= $this->url_encryption_helper->decode_url($PRJCODE);
			$data['PRJCODE'] 		= $PRJCODE;
			
			$collData				= "$PRJCODE~$PRJCODE";
			$backURL				= site_url('c_comprof/c_bUd93tL15t/gl180c21JL/?id='.$this->url_encryption_helper->encode_url($collData));
			$data['isProcess'] 		= 0;
			$data['message'] 		= '';
			$data['PRJCODE']		= $PRJCODE;
			$data['BOQH_DESC']		= '';
			$data['isUploaded']		= 0;
			$data['title'] 			= $appName;
			$data['title'] 			= $appName;
			$data['h2_title'] 		= 'History';
			$data['h3_title'] 		= 'bill of quantity';
			$data['secAdd'] 		= site_url('c_comprof/c_bUd93tL15t/addUpJL/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['link'] 			= array('link_back' => anchor("$backURL",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 		= $backURL;
			
			$num_rows 				= $this->m_boq->count_all_boq_hist($PRJCODE);
			$data['countBoQH'] 		= $num_rows;
	 		$data['viewBoQH'] 		= $this->m_boq->get_all_boq_hist($PRJCODE)->result();
			
			$this->load->view('v_company/v_budget/v_boq', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function addjl()
	{
		$this->load->model('m_project/m_joblistdet/m_joblistdet', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			
			$LangID 	= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$MenuCode 			= 'MN408';
				$mnCode				= 'MN408';
				$data['MenuCode'] 	= 'MN408';
				$data['MenuApp'] 	= 'MN408';
				$MenuCode			= 'MN408';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data['mnName'] = "Input";
				else
					$data['mnName'] = "Add";
			
			$data['form_action']	= site_url('c_comprof/c_bUd93tL15t/addJL_process');
			$cancel					= site_url('c_comprof/c_bUd93tL15t/gl180c21JL/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			$data['countParent']	= $this->m_joblistdet->count_all_job1();		
			$data['vwParent'] 		= $this->m_joblistdet->get_all_job1()->result();
			
			$data['backURL'] 		= $cancel;
			$getprojname 			= $this->m_joblistdet->get_project_name($PRJCODE)->row();			
			$data['PRJNAME'] 		= $getprojname->PRJNAME;
			$data['PRJCODE'] 		= $PRJCODE;
			$data["MenuCode"] 	    = 'MN272';
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN408';
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
			
			$this->load->view('v_company/v_budget/V_joblistdet_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function addjl_process() // OK
	{
		$this->load->model('m_project/m_joblistdet/m_joblistdet', '', TRUE);
			
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{	
			$this->db->trans_begin();
			
			$PRJCODE 	= $this->input->post('PRJCODE');
			$JOBLEV 	= $this->input->post('JOBLEV');
			$JOBPARENT 	= $this->input->post('JOBPARENT');
			$JOBCODEID 	= $this->input->post('JOBCODEID');
			$JOBDESC 	= $this->input->post('JOBDESC');
			$JOBUNIT 	= $this->input->post('JOBUNIT');
			$JOBVOLM 	= $this->input->post('JOBVOLM');
			$PRICE 		= $this->input->post('PRICE');
			$JOBCOST	= $JOBVOLM * $PRICE;
			
			// BOQ
				$sqlJobC	= "tbl_boqlist WHERE JOBPARENT = '$JOBPARENT' AND PRJCODE = '$PRJCODE'";
				$resJobC	= $this->db->count_all($sqlJobC);
				$RUNNO		= $resJobC+1;

				$ORDID1 	= 0;
				$PRJHO 		= "";
				$PRJPER 	= $PRJCODE;
				$sqlORD1 	= "SELECT ORD_ID, PRJCODE_HO, PRJPERIOD FROM tbl_joblist_detail WHERE JOBPARENT = '$JOBPARENT' AND PRJCODE = '$PRJCODE'
								ORDER BY ORD_ID DESC LIMIT 1";
				$resORD1 	= $this->db->query($sqlORD1)->result();
				foreach($resORD1 as $rowORD) :
					$ORDID1 = $rowORD->ORD_ID;
					$PRJHO 	= $rowORD->PRJCODE_HO;
					$PRJPER = $rowORD->PRJPERIOD;
				endforeach;
				$ORDID2 	= $ORDID1+1;
				
				$boqlist 	= array('ORD_ID'		=> $ORDID2,
									'JOBCODEID' 	=> $this->input->post('JOBCODEID'),
									'JOBCODEIDV'	=> $this->input->post('JOBCODEID'),
									'JOBPARENT'		=> $this->input->post('JOBPARENT'),
									'PRJCODE'		=> $this->input->post('PRJCODE'),
									'PRJCODE_HO'	=> $PRJHO,
									'PRJPERIOD'		=> $PRJPER,
									'JOBDESC'		=> $this->input->post('JOBDESC'),
									'JOBGRP'		=> $this->input->post('ITM_GROUP'),
									'JOBUNIT'		=> $this->input->post('JOBUNIT'),
									'JOBLEV'		=> $this->input->post('JOBLEV'),
									'JOBVOLM'		=> $this->input->post('JOBVOLM'),
									'PRICE'			=> $this->input->post('PRICE'),
									'JOBCOST'		=> $JOBCOST,
									'BOQ_VOLM'		=> $this->input->post('JOBVOLM'),
									'BOQ_PRICE'		=> $this->input->post('PRICE'),
									'BOQ_JOBCOST'	=> $JOBCOST,
									'ISHEADER'		=> 1,
									'ITM_NEED'		=> 0,
									'ISLAST'		=> 0,
									'BOQ_STAT'		=> 1,
									'Patt_Number'	=> $RUNNO);
				$this->m_joblistdet->addBOQ($boqlist);
			
			// JOBLIST
				$sqlJobC	= "tbl_joblist WHERE JOBPARENT = '$JOBPARENT' AND PRJCODE = '$PRJCODE'";
				$resJobC	= $this->db->count_all($sqlJobC);
				$RUNNO		= $resJobC+1;

				$joblist 	= array('ORD_ID'		=> $ORDID2,
									'JOBCODEID' 	=> $this->input->post('JOBCODEID'),
									'JOBCODEIDV'	=> $this->input->post('JOBCODEID'),
									'JOBPARENT'		=> $this->input->post('JOBPARENT'),
									'PRJCODE'		=> $this->input->post('PRJCODE'),
									'PRJCODE_HO'	=> $PRJHO,
									'PRJPERIOD'		=> $PRJPER,
									'JOBDESC'		=> $this->input->post('JOBDESC'),
									'JOBGRP'		=> $this->input->post('ITM_GROUP'),
									'JOBUNIT'		=> $this->input->post('JOBUNIT'),
									'JOBLEV'		=> $this->input->post('JOBLEV'),
									'JOBVOLM'		=> $this->input->post('JOBVOLM'),
									'PRICE'			=> $this->input->post('PRICE'),
									'JOBCOST'		=> $JOBCOST,
									'BOQ_VOLM'		=> $this->input->post('JOBVOLM'),
									'BOQ_PRICE'		=> $this->input->post('PRICE'),
									'BOQ_JOBCOST'	=> $JOBCOST,
									'ISHEADER'		=> 1,
									'ITM_NEED'		=> 0,
									'ISLAST'		=> 0,
									'WBS_STAT'		=> 1,
									'Patt_Number'	=> $RUNNO);
				$this->m_joblistdet->addJOB($joblist);
			
			// JOBLISTDETAIL
				//$sqlJobC	= "tbl_joblist_detail WHERE JOBPARENT = '$JOBPARENT' AND PRJCODE = '$PRJCODE' AND ISLAST = '0'";
				$sqlJobC	= "tbl_joblist_detail WHERE JOBPARENT = '$JOBPARENT' AND PRJCODE = '$PRJCODE'";
				$resJobC	= $this->db->count_all($sqlJobC);
				$RUNNO		= $resJobC+1;

				$joblistD 	= array('ORD_ID'		=> $ORDID2,
									'JOBCODEDET' 	=> $this->input->post('JOBCODEID'),
									'JOBCODEID' 	=> $this->input->post('JOBCODEID'),
									'JOBPARENT'		=> $this->input->post('JOBPARENT'),
									'PRJCODE'		=> $this->input->post('PRJCODE'),
									'PRJCODE_HO'	=> $PRJHO,
									'PRJPERIOD'		=> $PRJPER,
									'JOBDESC'		=> $this->input->post('JOBDESC'),
									'ITM_GROUP'		=> $this->input->post('ITM_GROUP'),
									'GROUP_CATEG'	=> $this->input->post('ITM_GROUP'),
									'ITM_UNIT'		=> 'LS',
									'ITM_VOLM'		=> 1,
									'ITM_PRICE'		=> $PRICE,
									'ITM_LASTP'		=> $PRICE,
									'ITM_BUDG'		=> $PRICE,
									'BOQ_VOLM'		=> 1,
									'BOQ_PRICE'		=> $PRICE,
									'BOQ_JOBCOST'	=> $PRICE,
									'IS_LEVEL'		=> $this->input->post('JOBLEV'),
									'ISCLOSE'		=> 0,
									'ISLAST'		=> 0,
									'WBSD_STAT'		=> 1,
									'Patt_Number'	=> $RUNNO);
				$this->m_joblistdet->addJOBDET($joblistD);
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $this->input->post('JOBCODEID');
				$MenuCode 		= 'MN272';
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
			$collData	= "$PRJCODE~$PRJCODE";
			$url		= site_url('c_comprof/c_bUd93tL15t/gl180c21JL/?id='.$this->url_encryption_helper->encode_url($collData));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function addUpJL() // OK
	{
		$this->load->model('m_project/m_boq/m_boq', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);

			$PRJNAME 	= '';
			$sqlApp 	= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$PRJNAME = $therow->PRJNAME;		
			endforeach;

			$data['PRJNAME'] 		= $PRJNAME;
			
			$backURL	= site_url('c_comprof/c_bUd93tL15t/jl180e2elst/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			$data['isProcess'] 		= 0;
			$data['message'] 		= '';
			$data['PRJCODE']		= $PRJCODE;
			$data['BOQH_DESC']		= '';
			$data['isUploaded']		= 0;
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';

			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Upload";
				$data["h2_title"] 	= "Anggaran";
			}
			else
			{
				$data["h1_title"] 	= "Upload";
				$data["h2_title"] 	= "Budget";
			}

			$data['form_action']	= site_url('c_comprof/c_bUd93tL15t/do_uploadJL');
			//$data['link'] 			= array('link_back' => anchor("$backURL",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 		= $backURL;
			
			$this->load->view('v_company/v_budget/v_jobl_upload_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function do_uploadJL() // OK
	{
		$this->load->model('m_project/m_boq/m_boq', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
		
		if ($this->session->userdata('login') == TRUE)
		{
			date_default_timezone_set("Asia/Jakarta");
			$BOQH_DATE		= date('Y-m-d H:i:s');
			$BOQH_DATEY		= date('Y');
			$BOQH_DATEM		= date('m');
			$BOQH_DATED		= date('d');
			$BOQH_DATEH		= date('H');
			$BOQH_DATEm		= date('i');
			$BOQH_DATES		= date('s');
			
			$BOQH_CODE		= "BOQ$BOQH_DATEY$BOQH_DATEM$BOQH_DATED-$BOQH_DATEH$BOQH_DATEm$BOQH_DATES";
			$BOQH_DATE		= date('Y-m-d H:i:s');
			$BOQH_PRJCODE	= $this->input->post('PRJCODE');
			$BOQH_DESC		= $this->input->post('BOQH_DESC');
			$BOQH_TYPE		= $this->input->post('BOQH_TYPE');
			$BOQH_USER		= $DefEmp_ID;
			$BOQH_STAT		= 1;
			
			$file 			= $_FILES['userfile'];
			$file_name 		= $file['name'];
					
			$filename 	= $_FILES["userfile"]["name"];
			$source 	= $_FILES["userfile"]["tmp_name"];
			$type 		= $_FILES["userfile"]["type"];
			
			$name 		= explode(".", $filename);
			$fileExt	= $name[1];
			
			if($BOQH_TYPE == 1)
			{
				$target_path 	= "application/xlsxfile/import_boq/master/".$filename;  // change this to the correct site path					
				$myPath 		= "application/xlsxfile/import_boq/master/$filename";
			}
			else
			{
				$target_path 	= "application/xlsxfile/import_boq/period/".$filename;  // change this to the correct site path					
				$myPath 		= "application/xlsxfile/import_boq/period/$filename";
			}
			
			if (file_exists($myPath) == true)
			{
				unlink($myPath);
			}
				
			if(move_uploaded_file($source, $target_path))
			{
				$message = "Your file was uploaded";
				$data['message'] 	= $message;
				$data['isUploaded']	= 1;
				$data['BOQH_DESC']	= $BOQH_DESC;
				
				//$this->m_boq->updateStat();
				
				$BoQHist = array('BOQH_CODE' 	=> $BOQH_CODE,
								'BOQH_DATE'		=> $BOQH_DATE,
								'BOQH_PRJCODE'	=> $BOQH_PRJCODE,
								'BOQH_DESC'		=> $BOQH_DESC,
								'BOQH_FN'		=> $filename,
								'BOQH_USER'		=> $BOQH_USER,
								'BOQH_STAT'		=> $BOQH_STAT,
								'BOQH_TYPE'		=> $BOQH_TYPE);
				$this->m_boq->add($BoQHist);
			} 
			else 
			{	
				$message = "There was a problem with the upload. Please try again.";
				$data['message'] 	= $message;
				$data['isUploaded']	= 0;
				$data['BOQH_DESC']	= $BOQH_DESC;
			}
			
			$backURL				= site_url('c_comprof/c_bUd93tL15t/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['isProcess'] 		= 1;
			$data['title'] 			= $appName;
			$data['PRJCODE']		= $BOQH_PRJCODE;
			$data['task'] 			= 'add';
			$data['h2_title']		= 'Upload';
			$data['h3_title'] 		= 'bill of quantity';
			$data['form_action']	= site_url('c_comprof/c_bUd93tL15t/do_upload');
			$data['link'] 			= array('link_back' => anchor('c_comprof/c_boq/','<input type="button" class="btn btn-danger" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 		= $backURL;
		
			/*$url	= site_url('c_comprof/c_bUd93tL15t/jl180e2elst/?id='.$this->url_encryption_helper->encode_url($BOQH_PRJCODE));
			redirect($url);*/
		}
	}
	
	function upd1d0ebb() // OK
	{
		/*$JOBCODEID		= $_GET['id'];
		$JOBCODEID		= $this->url_encryption_helper->decode_url($JOBCODEID);
		
		$COUNTALL		= strlen($JOBCODEID);
		$COUNTALLA		= $COUNTALL - 4;
		$PRJCODE		= substr($JOBCODEID, 0, $COUNTALLA);*/
		
		$collData		= $_GET['id'];
		$collData		= $this->url_encryption_helper->decode_url($collData);
		$data1			= explode("~", $collData);
		$PRJCODE		= $data1[0];
		$JOBCODEID		= $data1[1];
		
		if ($this->session->userdata('login') == TRUE)
		{
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title']		= 'Edit Job';
			$data['h3_title'] 		= 'project';
			$data['form_action']	= site_url('c_comprof/c_bUd93tL15t/upd1d0ebb_process');			
			
			$getjoblist 			= $this->m_budget->get_joblist_by_code($PRJCODE, $JOBCODEID)->row();
			
			$data['default']['JOBCODEID'] 	= $getjoblist->JOBCODEID;
			$data['default']['JOBCODEIDV'] 	= $getjoblist->JOBCODEID;
			$data['default']['JOBPARENT'] 	= $getjoblist->JOBPARENT;
			$PRJCODE						= $getjoblist->PRJCODE;
			$PRJPERIOD						= $getjoblist->PRJPERIOD;
			$data['default']['PRJCODE'] 	= $getjoblist->PRJCODE;
			$data['default']['JOBCOD1'] 	= $getjoblist->JOBCOD1;
			$data['default']['JOBDESC'] 	= $getjoblist->JOBDESC;
			$data['default']['JOBCLASS'] 	= $getjoblist->JOBCLASS;
			$data['default']['JOBGRP'] 		= $getjoblist->JOBGRP;
			$data['default']['JOBTYPE'] 	= $getjoblist->JOBTYPE;
			$data['default']['JOBUNIT'] 	= $getjoblist->JOBUNIT;
			$data['default']['JOBLEV'] 		= $getjoblist->JOBLEV;
			$data['default']['JOBVOLM'] 	= $getjoblist->JOBVOLM;
			$data['default']['PRICE'] 		= $getjoblist->PRICE;
			$data['default']['JOBCOST'] 	= $getjoblist->JOBCOST;
			$data['default']['ISLAST'] 		= $getjoblist->ISLAST;
			$data['default']['ITM_NEED'] 	= $getjoblist->ITM_NEED;
			$data['default']['ITM_GROUP'] 	= $getjoblist->JOBGRP;

			$sqlCAT 	= "SELECT GROUP_CATEG FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBCODEID'";
			$resCAT 	= $this->db->query($sqlCAT)->result();
			foreach($resCAT as $rowCAT) :
				$GROUP_CATEG = $rowCAT->GROUP_CATEG;		
			endforeach;

			$data['default']['GROUP_CATEG'] = $GROUP_CATEG;
			$data['default']['ISHEADER'] 	= $getjoblist->ISHEADER;
			$data['default']['ITM_CODE'] 	= $getjoblist->ITM_CODE;
			$data['default']['Patt_Number']	= $getjoblist->Patt_Number;
			
			$data['countParent']	= $this->m_budget->count_all_job1();		
			$data['vwParent'] 		= $this->m_budget->get_all_job1()->result();
			
			$getprojname 			= $this->m_budget->get_project_name($PRJCODE, $PRJPERIOD)->row();			
			$data['PRJNAME'] 		= $getprojname->PRJNAME;
			$data['PRJCODE'] 		= $PRJCODE;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getjoblist->JOBCODEID;
				$MenuCode 		= 'MN408';
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
			
			$this->load->view('v_company/v_budget/v_joblistdet_form_upt', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function upd1d0ebb_process() // 
	{
		$this->load->model('m_project/m_joblistdet/m_joblistdet', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{	
			$this->db->trans_begin();
			
			$JOBCODEID 	= $this->input->post('JOBCODEID');
			$JOBPARENT 	= $this->input->post('JOBPARENT');
			$COUNTALL	= strlen($JOBCODEID);
			$COUNTALLA	= $COUNTALL - 4;
			$PRJCODE	= substr($JOBCODEID, 0, $COUNTALLA);
			$PRJCODE 	= $this->input->post('PRJCODE');
			$GROUPCATEG = $this->input->post('GROUP_CATEG');
			
			$sqlCAT 	= "SELECT IG_Code FROM tbl_itemcategory WHERE IC_Num = '$GROUPCATEG'";
			$resCAT 	= $this->db->query($sqlCAT)->result();
			foreach($resCAT as $rowCAT) :
				$IG_Code = $rowCAT->IG_Code;
			endforeach;
			
			$joblist = array('JOBCODEID' 	=> $this->input->post('JOBCODEID'),
							'JOBCODEIDV'	=> $this->input->post('JOBCODEIDV'),
							'JOBPARENT'		=> $this->input->post('JOBPARENT'),
							'PRJCODE'		=> $this->input->post('PRJCODE'),
							'JOBCOD1'		=> $this->input->post('JOBCOD1'),
							'JOBDESC'		=> $this->input->post('JOBDESC'),
							'JOBCLASS'		=> $this->input->post('JOBCLASS'),
							'JOBGRP'		=> $IG_Code,
							'JOBTYPE'		=> $this->input->post('JOBTYPE'),
							'JOBUNIT'		=> $this->input->post('JOBUNIT'),
							'JOBLEV'		=> $this->input->post('JOBLEV'),
							'JOBVOLM'		=> $this->input->post('JOBVOLM'),
							'PRICE'			=> $this->input->post('ITM_PRICE'),
							'JOBCOST'		=> $this->input->post('JOBCOST'),
							'ITM_NEED'		=> $this->input->post('ITM_NEED'),
							'ISLAST'		=> $this->input->post('ISLAST'));
			$this->m_joblistdet->update($JOBCODEID, $joblist);
			
			// UPDATE TOTAL PARENT
			$this->m_joblistdet->updateParent($JOBPARENT, $JOBCODEID);
			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $this->input->post('JOBCODEID');
				$MenuCode 		= 'MN408';
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
			
			$url		= site_url('c_comprof/c_bUd93tL15t/gl180c21JL/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
		
    function iNc04() // G
	{
		$DefEmp_ID	= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{	
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			// START : GET MENU NAME
				$data['MenuCode'] 	= 'MN105';
				$MenuCode			= 'MN105';
				$getMNNm 		= $this->m_updash->get_menunm($MenuCode)->row();
				if($this->data['LangID'] == 'IND')
					$data['h1_title'] = $getMNNm->menu_name_IND;
				else
					$data['h1_title'] = $getMNNm->menu_name_ENG;
			// END : GET MENU NAME
			
			$collData			= $_GET['id'];
			$collData			= $this->url_encryption_helper->decode_url($collData);
			$dataArray			= explode("~", $collData);
			$PRJCODE			= $dataArray[0];
			$PRJPERIOD			= $dataArray[1];
			$LinkAcc			= 1;
			
			$data['viewCOA'] = $this->m_budget->get_all_ofCOADef($PRJCODE, $PRJPERIOD, $LinkAcc)->result();
			$MenuCode 			= 'MN105';
			$data["MenuCode"] 	= 'MN105';
			$data["selPRJCODE"] = $PRJCODE;
			$data["AccCateg"] 	= 1;
			$data["PRJPERIOD"] 	= $PRJPERIOD;
			$data["myPRJCODE"]	= $PRJCODE;
			
			$data['secAdd'] 	= site_url('c_gl/c_ch1h0fbeart/ch1h0fbeart_upl/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $PRJPERIOD;
				$MenuCode 		= 'MN408';
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
			
			$this->load->view('v_company/v_budget/v_coa', $data);
		}
		else
		{
			redirect('__l1y');
		}
    }
    
	function up1h0fbt() // G
	{
		$this->load->model('m_gl/m_coa/m_coa', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$collData	= $_GET['id'];
			$collData1	= $this->url_encryption_helper->decode_url($collData);
			$splitCode 	= explode("~", $collData1);
			$Acc_ID		= $splitCode[0];
			$PRJCODE	= $splitCode[1];
			$PRJPERIOD	= $splitCode[2];
		
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';

			$collData				= "$PRJCODE~$PRJPERIOD";
			$data['form_action']	= site_url('c_comprof/c_bUd93tL15t/up1h0fbt_process');
			//$data['backURL'] 		= site_url('c_comprof/c_bUd93tL15t/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['backURL'] 		= site_url('c_comprof/c_bUd93tL15t/iNc04/?id='.$this->url_encryption_helper->encode_url($collData));
			$MenuCode 				= 'MN105';
			$data["MenuCode"] 		= 'MN105';
			$data["PRJPERIOD"] 		= $PRJPERIOD;
			
			$data["PRJCODE"] 		= $PRJCODE;
			$getdepartment 			= $this->m_budget->get_coa_by_code($Acc_ID, $PRJCODE)->row();
			
			$data['default']['Acc_ID'] 				= $getdepartment->Acc_ID;
			$data['default']['ORD_ID'] 				= $getdepartment->ORD_ID;
			$data['default']['PRJCODE'] 			= $getdepartment->PRJCODE;
			$data['default']['Account_Class'] 		= $getdepartment->Account_Class;
			$data['default']['Account_Number'] 		= $getdepartment->Account_Number;
			$data['default']['Account_NameEn'] 		= $getdepartment->Account_NameEn;
			$data['default']['Account_NameId'] 		= $getdepartment->Account_NameId;
			$data['default']['Account_Category'] 	= $getdepartment->Account_Category;
			$data['default']['Account_Level'] 		= $getdepartment->Account_Level;
			$data['default']['Acc_DirParent'] 		= $getdepartment->Acc_DirParent;
			$data['default']['Acc_ParentList'] 		= $getdepartment->Acc_ParentList;
			$data['default']['Acc_StatusLinked'] 	= $getdepartment->Acc_StatusLinked;
			$data['default']['Default_Acc'] 		= $getdepartment->Default_Acc;
			$data['default']['COGSReportID'] 		= $getdepartment->COGSReportID;
			$data['default']['Acc_Group'] 			= $getdepartment->Acc_Group;
			$data['default']['Currency_id'] 		= $getdepartment->Currency_id;
			$data['default']['Base_Debet'] 			= $getdepartment->Base_Debet;
			$data['default']['Base_Kredit'] 		= $getdepartment->Base_Kredit;
			$data['default']['Base_Debet2'] 		= $getdepartment->Base_Debet2;
			$data['default']['Base_Kredit2'] 		= $getdepartment->Base_Kredit2;
			$data['default']['Base_OpeningBalance'] = $getdepartment->Base_OpeningBalance;
			$data['default']['isHO'] 				= $getdepartment->isHO;
			$data['default']['isSync'] 				= $getdepartment->isSync;
			$data['default']['syncPRJ'] 			= $getdepartment->syncPRJ;
			$data['default']['showCF'] 				= $getdepartment->showCF;
				
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN105';
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
			
			$this->load->view('v_company/v_budget/coa_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function up1h0fbt_process() // G
	{
		$this->load->model('m_gl/m_coa/m_coa', '', TRUE);	
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
				
		$this->db->trans_begin();
		
		$comp_init 	= $this->session->userdata('comp_init');
		
		$collPRJ	= "AllPRJ";
		$LinkAcc	= 1;
		
		$Acc_ID			= $this->input->post('Acc_ID');
		$Account_Number	= $this->input->post('Account_Number');
		$collData		= $this->input->post('Acc_DirParent');
		$Account_Class	= $this->input->post('Account_Class');
		$PRJCODE		= $this->input->post('PRJCODE');
		$PRJPERIOD		= $this->input->post('PRJPERIOD');
		
		$splitCode 		= explode("~", $collData);
		$Acc_DirParent	= $splitCode[0];
		$Acc_ParentList1= $splitCode[1];
		$Acc_ParentList	= "$Acc_ParentList1;$Acc_DirParent";
		$isHO			= $this->input->post('isHO');
		$isSync			= $this->input->post('isSync');

		$collData		= "$PRJCODE~$PRJPERIOD";
		
		// UPDATE TO BE HEADER
			$sqlUPDH 	= "UPDATE tbl_chartaccount SET Account_Class = 1, isLast = 0 WHERE Account_Number = '$Acc_DirParent'";
			$this->db->query($sqlUPDH);
		
		// CARI LEVEL PARENT
			$sqlACLEV 	= "SELECT Account_Level FROM tbl_chartaccount 
							WHERE Account_Number = '$Acc_DirParent' AND PRJCODE = '$PRJCODE'";
			$resACLEV 	= $this->db->query($sqlACLEV)->result();
			foreach($resACLEV as $rowLEV) :
				$Account_Level1 = $rowLEV->Account_Level;		
			endforeach;
			$Account_Level	= $Account_Level1 + 1;
		
			if($Account_Class == 1)
			{
				$isLast	= 0;

			}
			else
			{
				$isLast	= 1;
			}
		
		// COUNT ALL PROJECT RELATION
			$PRJCODE1	= '';
			if($isSync == 1)
			{
				$packageelements	= $_POST['packageelements'];
				$TOTPROJ			= count($packageelements);
				if (count($packageelements)>0)
				{
					$mySelected	= $_POST['packageelements'];
					$row		= 0;
					foreach ($mySelected as $projCode)
					{
						$row	= $row + 1;
						if($row == 1)
						{
							$PRJCODE1	= $projCode;
						}
						else
						{
							$PRJCODE1	= "$PRJCODE1~$projCode";
						}
					}
				}
			}
		
		// SAVE TO ALL PROJECT
			$sqlPRJ		= "SELECT PRJCODE, isHO, PRJTYPE FROM tbl_project";
			$resPRJ 	= $this->db->query($sqlPRJ)->result();
			foreach($resPRJ as $rowPRJ) :
				$PRJCODE 	= $rowPRJ->PRJCODE;
				$isHO 		= $rowPRJ->isHO;
				$PRJTYPE 	= $rowPRJ->PRJTYPE;
				if($PRJTYPE == 3)
					$isHO 	= 2;	// 0=Proyek,1=HO,2=Period
				$coaUpd 	= array('Acc_ID'				=> $Acc_ID,
									'ORD_ID'				=> $this->input->post('ORD_ID'),
									'Account_Class'			=> $Account_Class,
									'PRJCODE'				=> $PRJCODE,
									'Account_Number'		=> $Account_Number,
									'Account_NameEn'		=> $this->input->post('Account_NameEn'),
									'Account_NameId'		=> $this->input->post('Account_NameId'),
									'Acc_DirParent'			=> $Acc_DirParent,
									'Company_ID'			=> $comp_init,
									'Account_Level'			=> $Account_Level,
									'Account_Category'		=> $this->input->post('Account_Category'),
									'Account_Class'			=> $this->input->post('Account_Class'),
									'Default_Acc'			=> $this->input->post('Default_Acc'),
									'Base_OpeningBalance'	=> $this->input->post('Base_OpeningBalance'),
									'COGSReportID'			=> $this->input->post('COGSReportID'),
									'Acc_Group'				=> $this->input->post('Acc_Group'),
									'showCF'				=> $this->input->post('showCF'),
									'isHO'					=> $isHO,
									//'syncPRJ'				=> $PRJCODE1, // khusus untuk project yang dipilih
									'isLast'				=> $isLast);
				$this->m_coa->update($Acc_ID, $coaUpd, $PRJCODE);
			endforeach;
			
		// SAVE TO ALL SELECTED PROJECT
			$dataPecah 	= explode("~",$PRJCODE1);
			$jmD 		= count($dataPecah);
			
			for($i=0; $i < $jmD; $i++)
			{
				$SYNC_PRJ	= $dataPecah[$i];
				
				$coaUpd 	= array('Base_OpeningBalance'	=> $this->input->post('Base_OpeningBalance'),
									'syncPRJ'				=> $PRJCODE1);								
				$this->m_coa->update($Acc_ID, $coaUpd, $SYNC_PRJ);
			}		
			
		$data['viewCOA'] 	= $this->m_budget->get_all_ofCOADef($collPRJ, $LinkAcc)->result();
		$MenuCode 			= 'MN105';
		$data["MenuCode"] 	= 'MN105';
		$data["selPRJCODE"] = 'AllPRJ';
		$data["myPRJCODE"]	= $PRJCODE;
			
		$data['secAdd'] 	= site_url('c_gl/c_ch1h0fbeart/ch1h0fbeart_upl/?id='.$this->url_encryption_helper->encode_url($collPRJ));
		
		// START : UPDATE TO T-TRACK
			date_default_timezone_set("Asia/Jakarta");
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			$TTR_PRJCODE	= '';
			$TTR_REFDOC		= '';
			$MenuCode 		= 'MN105';
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
		
		$url			= site_url('c_comprof/c_bUd93tL15t/iNc04/?id='.$this->url_encryption_helper->encode_url($collData));
		redirect($url);
	}

	function get_all_ofCOA() // G
	{
		// START : GET MENU NAME
			$data['MenuCode'] 	= 'MN105';
			$MenuCode			= 'MN105';
			$getMNNm 		= $this->m_updash->get_menunm($MenuCode)->row();
			if($this->data['LangID'] == 'IND')
				$data['h1_title'] = $getMNNm->menu_name_IND;
			else
				$data['h1_title'] = $getMNNm->menu_name_ENG;
		// END : GET MENU NAME
		
		$collDATA	= $_GET['id'];
		$theCateg	= $_GET['tC4t'];
		$LinkAcc1	= $this->url_encryption_helper->decode_url($collDATA);	
		$LinkAcc1	= explode("~", $LinkAcc1);
		$collPRJ	= $LinkAcc1[0];
		//$LinkAcc	= $LinkAcc1[1];
		$LinkAcc	= $theCateg;
		$PRJPERIOD	= $LinkAcc1[2];
		$data["PRJPERIOD"] = $PRJPERIOD;
		$getAppName = $this->m_coa->getAppName()->result();
		foreach($getAppName as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['viewCOA'] 	= $this->m_budget->get_all_ofCOACAT($collPRJ, $PRJPERIOD, $LinkAcc)->result();
			$data["selPRJCODE"] = $collPRJ;
			$data["AccCateg"] 	= $LinkAcc;
			
			$this->load->view('v_company/v_budget/v_coa', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

  	function get_AllDataCOA() // GOOD
	{
		setlocale(LC_ALL, 'id-ID', 'id_ID');

		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		$PRJCODE	= $_GET['id'];
		$theCateg	= $_GET['tC4t'];

		$sqlPRJL 	= "SELECT PRJLEV FROM tbl_project_budg WHERE PRJCODE = '$PRJCODE'";
		$resPRJL 	= $this->db->query($sqlPRJL)->result();
		foreach($resPRJL as $rowPRJL) :
				$PRJLEV = $rowPRJL->PRJLEV;
		endforeach;

		$LangID     = $this->session->userdata['LangID'];
        
        $sqlTransl      = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
        $resTransl      = $this->db->query($sqlTransl)->result();
        foreach($resTransl as $rowTransl) :
            $TranslCode = $rowTransl->MLANG_CODE;
            $LangTransl = $rowTransl->LangTransl;

    		if($TranslCode == 'Director')$Director = $LangTransl;
            if($TranslCode == 'Compailer')$Compailer = $LangTransl;
            if($TranslCode == 'Location')$Location = $LangTransl;
    		if($TranslCode == 'Active')$Active = $LangTransl;
    		if($TranslCode == 'Inactive')$Inactive = $LangTransl;
    		if($TranslCode == 'Contact')$Contact = $LangTransl;
    		if($TranslCode == 'CompanyName')$CompanyName = $LangTransl;
    		if($TranslCode == 'Budget')$Budget = $LangTransl;
    		if($TranslCode == 'Phone')$Phone = $LangTransl;
    		if($TranslCode == 'Notes')$Notes = $LangTransl;
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
			
			$columns_valid 	= array("ORD_ID", 
									"Account_Number", 
									"Account_NameId",
									"Account_NameEn");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_budget->get_AllDataCOAC($PRJCODE, $theCateg, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			
			$query 			= $this->m_budget->get_AllDataCOAL($PRJCODE, $theCateg, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$Acc_ID					= $dataI['Acc_ID'];
                $Account_Category		= $dataI['Account_Category'];
                $Account_Number			= $dataI['Account_Number'];
                $Account_NameEn			= $dataI['Account_NameEn'];
                $Base_OpeningBalance	= $dataI['Base_OpeningBalance'];

                $AccView				= "$Account_Number $Account_NameEn";
                
                if($dataI['Default_Acc'] == "D") $DAName = "Debit"; else $DAName = "Credit";
				
				$Account_Class			= $dataI['Account_Class'];

				$CELLCOL 				= "";
                if($Account_Class == 1)
                {
                	$ACName = "Header";
                	$JDDesc	= "Header";
                	$isDisB	= 'disabled="disabled"';
                	$CELLCOL= "font-weight:bold; white-space:nowrap";
                }
                elseif($Account_Class == 2)
                { $ACName = "Detail"; $JDDesc = ""; $isDisB = ""; }
                elseif($Account_Class == 3)
                { $ACName = "Detail Bank"; $JDDesc = ""; $isDisB = ""; }
                elseif($Account_Class == 4)
                { $ACName = "Detail Cash"; $JDDesc = ""; $isDisB = ""; }
                elseif($Account_Class == 5)
                { $ACName = "Detail Cheque"; $JDDesc = ""; $isDisB = ""; }

                $Account_Level	= $dataI['Account_Level'];
				
                if($dataI['Account_Level'] == 0)
                { $LongSpace = ""; }
                elseif($dataI['Account_Level'] == 1)
                { $LongSpace = "&nbsp;&nbsp;&nbsp;&nbsp;"; }
                elseif($dataI['Account_Level'] == 2)
                { $LongSpace = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; }
                elseif($dataI['Account_Level'] == 3)
                { $LongSpace = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; }
                elseif($dataI['Account_Level'] == 4)
                { $LongSpace = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; }
                elseif($dataI['Account_Level'] == 5)
                { $LongSpace = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; }
                elseif($dataI['Account_Level'] == 6)
                { $LongSpace = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; }

            	$collID		= "$Acc_ID~$PRJCODE~$PRJCODE";
                $secUpd		= site_url('c_comprof/c_bUd93tL15t/up1h0fbt/?id='.$this->url_encryption_helper->encode_url($collID));

                // CHECK IN JOURNAL
                	$sqlJDC1	= "tbl_journaldetail WHERE Acc_Id = '$Account_Number'";
            		$resJDC1	= intval($this->db->count_all($sqlJDC1));
            		if($resJDC1 > 0)
            			$JDDesc	= $JDDesc."Journal";

                	$sqlJDC2	= "tbl_item WHERE ACC_ID = '$Account_Number' OR ACC_ID_UM = '$Account_Number'";
            		$resJDC2	= intval($this->db->count_all($sqlJDC2));
            		if($resJDC2 > 0)
            			$JDDesc	= $JDDesc." - Master Material";

                	$sqlJDC3	= "tbl_vendcat WHERE VC_LA_PAYDP = '$Account_Number' OR VC_LA_PAYINV = '$Account_Number'
                						OR VC_LA_RET = '$Account_Number'";
            		$resJDC3	= intval($this->db->count_all($sqlJDC3));
            		if($resJDC3 > 0)
            			$JDDesc	= $JDDesc." - Supl. Cat";

                	$sqlJDC4	= "tbl_custcat WHERE CC_LA_CINV = '$Account_Number' OR CC_LA_RECDP = '$Account_Number'";
            		$resJDC4	= intval($this->db->count_all($sqlJDC4));
            		if($resJDC4 > 0)
            			$JDDesc	= $JDDesc." - Cust. Cat";

            		$AccisUsed	= $resJDC1 + $resJDC2 + $resJDC3 + $resJDC4;
            		if($AccisUsed > 0)
            		{
            			$isDisB = 'disabled="disabled"';
            		}

					if($PRJCODE == "AllPRJ")
					{
						$ADDQUERY	= "";
					}
					else
					{
						$ADDQUERY	= "AND PRJCODE = '$PRJCODE'";
					}
					
					if($PRJLEV == 1)
					{
						$TBL 		= $dataI['tot'];
					}
					else
					{
						$Base_Debet = $dataI['Base_Debet'];
						$Base_Kredit= $dataI['Base_Kredit'];
						$TBL 		= $Base_OpeningBalance + $Base_Debet - $Base_Kredit;
					}
					$isLast			= $dataI['isLast'];
													// JIKA HEADER, CARI TURUNANNYA - 1
					if($isLast == 0)
					{
						$sql2A	= "SELECT Account_Number, isLast, Base_OpeningBalance, Base_Debet, Base_Kredit
									FROM tbl_chartaccount
									WHERE Acc_DirParent = '$Account_Number' 
										$ADDQUERY
										AND Account_Category = '$Account_Category'";											
						$res2A	= $this->db->query($sql2A)->result();
						$TLEV2A	= 0;
						foreach($res2A as $row2A):
							$Acc2A	= $row2A->Account_Number;
							$OpB2A	= $row2A->Base_OpeningBalance;
							$BD2A	= $row2A->Base_Debet;
							$BK2A	= $row2A->Base_Kredit;
							$Last2A	= $row2A->isLast;
							// JIKA HEADER, CARI TURUNANNYA - 2
							$TLEV3A	= 0;
							if($Last2A == 0)
							{
								$sql3A	= "SELECT Account_Number, isLast, Base_OpeningBalance, Base_Debet, 
												Base_Kredit
											FROM tbl_chartaccount
											WHERE Acc_DirParent = '$Acc2A' 
												$ADDQUERY
												AND Account_Category = '$Account_Category'";											
								$res3A	= $this->db->query($sql3A)->result();
								foreach($res3A as $row3A):
									$Acc3A	= $row3A->Account_Number;
									$OpB3A	= $row3A->Base_OpeningBalance;
									$BD3A	= $row3A->Base_Debet;
									$BK3A	= $row3A->Base_Kredit;
									$Last3A	= $row3A->isLast;
									// JIKA HEADER, CARI TURUNANNYA - 3
									$TLEV4A	= 0;
									if($Last3A == 0)
									{
										$sql4A	= "SELECT Account_Number, isLast, Base_OpeningBalance,
													Base_Debet, Base_Kredit
													FROM tbl_chartaccount
													WHERE Acc_DirParent = '$Acc3A' 
														$ADDQUERY
														AND Account_Category = '$Account_Category'";											
										$res4A	= $this->db->query($sql4A)->result();
										foreach($res4A as $row4A):
											$Acc4A	= $row4A->Account_Number;
											$OpB4A	= $row4A->Base_OpeningBalance;
											$BD4A	= $row4A->Base_Debet;
											$BK4A	= $row4A->Base_Kredit;
											$Last4A	= $row4A->isLast;	
											// JIKA HEADER, CARI TURUNANNYA - 4
											$TLEV5A	= 0;
											if($Last4A == 0)
											{
												$sql5A	= "SELECT Account_Number, isLast, Base_OpeningBalance,
															Base_Debet, Base_Kredit
															FROM tbl_chartaccount
															WHERE Acc_DirParent = '$Acc4A' 
																$ADDQUERY
																AND Account_Category = '$Account_Category'";											
												$res5A	= $this->db->query($sql5A)->result();
												foreach($res5A as $row5A):
													$Acc5A	= $row5A->Account_Number;
													$OpB5A	= $row5A->Base_OpeningBalance;
													$BD5A	= $row5A->Base_Debet;
													$BK5A	= $row5A->Base_Kredit;
													$Last5A	= $row5A->isLast;
													$TLEV6A	= 0;
													if($Last5A == 0)
													{
														$sql6A	= "SELECT Account_Number, isLast, Base_OpeningBalance,
																	Base_Debet, Base_Kredit
																	FROM tbl_chartaccount
																	WHERE Acc_DirParent = '$Acc5A' 
																		$ADDQUERY
																		AND Account_Category = '$Account_Category'";											
														$res6A	= $this->db->query($sql6A)->result();
														foreach($res6A as $row6A):
															$Acc6A	= $row6A->Account_Number;
															$OpB6A	= $row6A->Base_OpeningBalance;
															$BD6A	= $row6A->Base_Debet;
															$BK6A	= $row6A->Base_Kredit;
															$Last6A	= $row6A->isLast;
															$TLEV7A	= 0;
															if($Last6A == 0)
															{
																$sql7A	= "SELECT Account_Number, isLast, 
																			Base_OpeningBalance,
																			Base_Debet, Base_Kredit
																			FROM tbl_chartaccount
																			WHERE Acc_DirParent = '$Acc6A' 
																				$ADDQUERY
																				AND Account_Category = '$Account_Category'";											
																$res7A	= $this->db->query($sql7A)->result();
																foreach($res7A as $row7A):
																	$Acc7A	= $row6A->Account_Number;
																	$OpB7A	= $row7A->Base_OpeningBalance;
																	$BD7A	= $row7A->Base_Debet;
																	$BK7A	= $row7A->Base_Kredit;
																	$Last7A	= $row7A->isLast;
																	$TLEV8A	= 0;
																	$TLEV7A	= $TLEV7A + $OpB7A + $BD7A - $BK7A + $TLEV8A;
																endforeach;
															}
															$TLEV6A	= $TLEV6A + $OpB6A + $BD6A - $BK6A + $TLEV7A;
														endforeach;
													}
													$TLEV5A	= $TLEV5A + $OpB5A + $BD5A - $BK5A + $TLEV6A;
												endforeach;
											}
											$TLEV4A	= $TLEV4A + $OpB4A + $BD4A - $BK4A + $TLEV5A;
										endforeach;
									}
									$TLEV3A	= $TLEV3A + $OpB3A + $BD3A - $BK3A + $TLEV4A;
								endforeach;
							}
							$TLEV2A	= $TLEV2A + $OpB2A + $BD2A - $BK2A + $TLEV3A;
						endforeach;
						$TBL	= $TBL + $TLEV2A;
					}
					$balanceValvA	= $TBL;
					$balanceValv 	= number_format(abs($TBL), 2);

					$secAction		= 	"<label style='white-space:nowrap'>
										   	<a href='".$secUpd."' class='btn btn-warning btn-xs' title='Update'>
												<i class='glyphicon glyphicon-pencil'></i>
										   	</a>
											<a href='javascript:void(null);' class='btn btn-danger btn-xs' title='".$JDDesc."' onClick='delCOA(".$noU.")' ".$isDisB.">
		                                        <i class='glyphicon glyphicon-trash'></i>
		                                    </a>
										</label>";

					$output['data'][] 	= array("<div style='text-align:left; ".$CELLCOL."''>".$LongSpace.$AccView."</div>",
										  		"<div style='text-align:center; ".$CELLCOL."''>".$ACName."</div>",
												"<div style='text-align:center; ".$CELLCOL."''>".$DAName."</div>",
												"<div style='text-align:right; ".$CELLCOL."''>".$balanceValv."</div>",
												"<div style='text-align:center; ".$CELLCOL."''>".$secAction."</div>");
				$noU			= $noU + 1;
			}


		echo json_encode($output);
	}

	function get_all_ofCOAIDX() // G
	{
		$LinkAcc	= 1;
		$collPRJ	= $this->input->post('selPRJCODE');
		$this->load->model('m_gl/m_coa/m_coa', '', TRUE);
		
		$getAppName = $this->m_coa->getAppName()->result();
		foreach($getAppName as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['viewCOA'] 	= $this->m_coa->get_all_ofCOADef($collPRJ, $LinkAcc)->result();
			$data["selPRJCODE"] = $collPRJ;
			$MenuCode 			= 'MN105';
			$data["MenuCode"] 	= 'MN105';
			$data["selPRJCODE"] = 'AllPRJ';
			$data["AccCateg"] 	= $LinkAcc;
			
			$data['secAdd'] 	= site_url('c_gl/c_ch1h0fbeart/ch1h0fbeart_upl/?id='.$this->url_encryption_helper->encode_url($collPRJ));
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN105';
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
			
			$this->load->view('v_company/v_budget/v_coa', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function iNI7m() // GOOD
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$collData			= $_GET['id'];
			$collData			= $this->url_encryption_helper->decode_url($collData);
			$dataArray			= explode("~", $collData);
			$PRJCODE			= $dataArray[0];
			$PRJPERIOD			= $dataArray[1];
			$collData			= "$PRJCODE~$PRJPERIOD";
			
			$EmpID 				= $this->session->userdata('Emp_ID');

			$PRJCODE_HO			= '';
			$sqlPRJ 			= "SELECT PRJCODE_HO FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
			$resPRJ 			= $this->db->query($sqlPRJ)->result();
			foreach($resPRJ as $rowPRJ) :
				$PRJCODE_HO = $rowPRJ->PRJCODE_HO;		
			endforeach;
				
			$data['title'] 		= $this->data['appName'];
			$data['h2_title'] 	= 'Item List';
			$data['h3_title']	= 'inventory';
			$data['secUpl'] 	= site_url('c_comprof/c_bUd93tL15t/it180e2elst_upl/?id='.$this->url_encryption_helper->encode_url($collData));	
			//$data['backURL']	= site_url('c_comprof/c_bUd93tL15t/iNI7m/?id='.$this->url_encryption_helper->encode_url($this->data['appName']));	
			//$data['backURL']	= site_url('c_comprof/c_bUd93tL15t/');
			$data['backURL']	= site_url('c_comprof/c_bUd93tL15t/i180c2gdx/?id='.$this->url_encryption_helper->encode_url($PRJCODE_HO));
						
			$data['PRJCODE'] 	= $PRJCODE;
			$data["MenuCode"] 	= 'MN188';
			
			$getprojname 		= $this->m_budget->get_project_name($PRJCODE, $PRJPERIOD)->row();
			$data['PRJNAME'] 	= $getprojname->PRJNAME;
			$data['BUDGNAME'] 	= $getprojname->BUDGNAME;
			$data['PRJCODE'] 	= $PRJCODE;
			
			$data['PRJCODE'] 	= $PRJCODE;
			$data["MenuCode"] 	= 'MN188';
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $PRJPERIOD;
				$MenuCode 		= 'MN188';
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
			
			$this->load->view('v_company/v_budget/v_item_list', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function it180e2elst_upl() // GOOD
	{
		$this->load->model('m_inventory/m_itemlist/m_itemlist', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$collData		= $_GET['id'];
			$collData		= $this->url_encryption_helper->decode_url($collData);
			$dataArray		= explode("~", $collData);
			$PRJCODE		= $dataArray[0];
			$PRJPERIOD		= $dataArray[1];
			
			$collData		= "$PRJCODE~$PRJPERIOD";
			$backURL		= site_url('c_comprof/c_bUd93tL15t/iNI7m/?id='.$this->url_encryption_helper->encode_url($collData));
			
			$data['isProcess'] 		= 0;
			$data['message'] 		= '';
			$data['PRJCODE']		= $PRJCODE;
			$data['PRJPERIOD']		= $PRJPERIOD;
			$data['ITMH_DESC']		= '';
			$data['isUploaded']		= 0;
			$data['title'] 			= $this->data['appName'];
			$data['task'] 			= 'add';
			$data['h2_title']		= 'Upload';
			$data['h3_title'] 		= 'master item';
			$data['form_action']	= site_url('c_comprof/c_bUd93tL15t/do_upload');
			$data['backURL'] 		= $backURL;
			
			$this->load->view('v_company/v_budget/v_item_upload_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function do_upload() // GOOD
	{
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
		
		if ($this->session->userdata('login') == TRUE)
		{
			date_default_timezone_set("Asia/Jakarta");
			$ITMH_DATE		= date('Y-m-d H:i:s');
			$ITMH_DATEY		= date('Y');
			$ITMH_DATEM		= date('m');
			$ITMH_DATED		= date('d');
			$ITMH_DATEH		= date('H');
			$ITMH_DATEm		= date('i');
			$ITMH_DATES		= date('s');
			
			$ITMH_CODE		= "ITM$ITMH_DATEY$ITMH_DATEM$ITMH_DATED-$ITMH_DATEH$ITMH_DATEm$ITMH_DATES";
			$ITMH_DATE		= date('Y-m-d H:i:s');
			$ITMH_PRJCODE	= $this->input->post('PRJCODE');
			$PRJPERIOD		= $this->input->post('PRJPERIOD');
			$ITMH_DESC		= $this->input->post('ITMH_DESC');
			$ITMH_USER		= $DefEmp_ID;
			$ITMH_STAT		= 1;
			$collData		= "$ITMH_PRJCODE~$PRJPERIOD";
			
			$file 			= $_FILES['userfile'];
			$file_name 		= $file['name'];
					
			$filename 	= $_FILES["userfile"]["name"];
			$source 	= $_FILES["userfile"]["tmp_name"];
			$type 		= $_FILES["userfile"]["type"];
			
			$name 		= explode(".", $filename);
			$fileExt	= $name[1];
			
			$target_path= "application/xlsxfile/import_item/period/".$filename;  // change this to the correct site path
				
			$myPath 	= "application/xlsxfile/import_item/period/$filename";
			
			if (file_exists($myPath) == true)
			{
				unlink($myPath);
			}
			
			$data['isUploaded']	= 1;	
			if(move_uploaded_file($source, $target_path))
			{
				/*$targetpath = "import_excel/import_item/period/".$filename;  // change this to the correct site path
				$myPath2 	= "import_excel/import_item/period/$filename";
				
				if (file_exists($myPath2) == true)
				{
					unlink($myPath2);
				}
					
				move_uploaded_file($source, $targetpath);*/

				$message = "Your file was uploaded";
				$data['message'] 	= $message;
				$data['isSuccess']	= 1;
				$data['ITMH_DESC']	= $ITMH_DESC;
				
				//$this->m_itemlist->updateStat();
				
				$ItemHist = array('ITMH_CODE' 	=> $ITMH_CODE,
								'ITMH_DATE'		=> $ITMH_DATE,
								'ITMH_PRJCODE'	=> $ITMH_PRJCODE,
								'ITMH_DESC'		=> $ITMH_DESC,
								'ITMH_FN'		=> $filename,
								'ITMH_USER'		=> $ITMH_USER,
								'ITMH_STAT'		=> $ITMH_STAT);

				$this->m_itemlist->add_importitem($ItemHist);
			} 
			else 
			{	
				$message = "There was a problem with the upload. Please try again.";
				$data['message'] 	= $message;
				$data['isSuccess']	= 0;
				$data['ITMH_DESC']	= $ITMH_DESC;
			}
			$collData				= "$ITMH_PRJCODE~$PRJPERIOD";
			$backURL				= site_url('c_comprof/c_bUd93tL15t/iNI7m/?id='.$this->url_encryption_helper->encode_url($collData));
			$data['isProcess'] 		= 1;
			$data['PRJCODE']		= $ITMH_PRJCODE;
			$data['title'] 			= $this->data['appName'];
			$data['task'] 			= 'add';
			$data['h2_title']		= 'Upload';
			$data['h3_title'] 		= 'master item';
			$data['form_action']	= site_url('c_comprof/c_bUd93tL15t/do_upload');
			$data['backURL'] 		= $backURL;

			$PRJCODEHO	= '';
			$sqlPrj		= "SELECT PRJCODE_HO FROM tbl_project_budgm WHERE PRJCODE = '$ITMH_PRJCODE'";
			$resPrj 	= $this->db->query($sqlPrj)->result();
			foreach($resPrj as $row) :
				$PRJCODEHO = $row->PRJCODE_HO;		
			endforeach;
			

			/*$url			= site_url('c_comprof/c_bUd93tL15t/it180e2elst_upl/?id='.$this->url_encryption_helper->encode_url($collData));
			redirect($url);*/
		}
	}
	
	function do_uploadPRJ() // GOOD
	{
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
		
		if ($this->session->userdata('login') == TRUE)
		{
			date_default_timezone_set("Asia/Jakarta");
			
			$this->load->model('m_project/m_listproject/m_listproject', '', TRUE);
		
			$PRJCODE			= $this->input->post('PRJCODE');
			
			// CEK FILE
	        $file 				= $_FILES['userfile'];
			$nameFile			= $_FILES["userfile"]["name"];
			$ext 				= end((explode(".", $nameFile)));
				
			if (!file_exists('assets/AdminLTE-2.0.5/project_image/'.$PRJCODE))
			{
				mkdir('assets/AdminLTE-2.0.5/project_image/'.$PRJCODE, 0777, true);
			}
			
			$file 						= $_FILES['userfile'];
			$file_name 					= $file['name'];
			$config['upload_path']   	= "assets/AdminLTE-2.0.5/project_image/$PRJCODE/"; 
			$config['allowed_types']	= 'gif|jpg|png'; 
			$config['overwrite'] 		= TRUE;
			$config['max_size']     	= 1000000; 
			$config['max_width']    	= 10024; 
			$config['max_height']    	= 10000;  
			$config['file_name']       	= $file['name'];
			
	        $this->load->library('upload', $config);
			
	        if ( ! $this->upload->do_upload('userfile')) 
			{
				//$data['Emp_ID']		= $Emp_ID;
				//$data['task'] 		= 'edit';
	         }
	         else 
			 {
	            //$data['path']			= $file_name;
				//$data['Emp_ID']			= $Emp_ID;
				//$data['task'] 			= 'edit';
	            //$data['showSetting']	= 0;
	            $this->m_listproject->updatePict($PRJCODE, $nameFile);
	         }
			 
	         $sqlApp 		= "SELECT * FROM tappname";
			$resultaApp 	= $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName 	= $therow->app_name;		
			endforeach;

			$PRJCODEHO	= '';
			$sqlPrj		= "SELECT PRJCODE_HO FROM tbl_project_budg WHERE PRJCODE = '$PRJCODE'";
			$resPrj 	= $this->db->query($sqlPrj)->result();
			foreach($resPrj as $row) :
				$PRJCODEHO = $row->PRJCODE_HO;		
			endforeach;
						
			$url			= site_url('c_comprof/c_bUd93tL15t/i180c2gdx/?id='.$this->url_encryption_helper->encode_url($PRJCODEHO));
			redirect($url);
		}
	}
	
	function do_uploadPRJDOC() // GOOD
	{
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
		
		if ($this->session->userdata('login') == TRUE)
		{
			date_default_timezone_set("Asia/Jakarta");
			
			$this->load->model('m_project/m_listproject/m_listproject', '', TRUE);
		
			$PRJCODE	= $this->input->post('PRJCODE');
			$PRJCODE	= $this->input->post('PRJCODE');
			$PRJ_FDESC	= $this->input->post('PRJ_FDESC');

			// CEK FILE
	        $file 		= $_FILES['docfile'];
			$file_name 	= $file['name'];
					
			$filename 	= $_FILES["docfile"]["name"];
			$source 	= $_FILES["docfile"]["tmp_name"];
			$type 		= $_FILES["docfile"]["type"];
			
			$name 		= explode(".", $filename);
			$fileExt	= $name[1];

			$target_path 	= "application/xlsxfile/import_buddoc/".$filename;  // change this to the correct site path					
			$myPath 		= "application/xlsxfile/import_buddoc/$filename";
			
			if (file_exists($myPath) == true)
			{
				unlink($myPath);
			}
				
			if(move_uploaded_file($source, $target_path))
			{
				$message = "Your file was uploaded";
				$data['message'] 	= $message;
				$data['isUploaded']	= 1;
				
				$prjDoc = array('PRJCODE' 		=> $PRJCODE,
								'PRJ_FDESC'		=> $PRJ_FDESC,
								'PRJ_FNAME'		=> $file_name);
				$this->m_budget->addPRJDoc($prjDoc);
			} 
			else 
			{	
				$message = "There was a problem with the upload. Please try again.";
				$data['message'] 	= $message;
				$data['isUploaded']	= 0;
			}
			
			$sqlPrd			= "SELECT PRJCODE_HO FROM tbl_project_budgm WHERE PRJCODE = '$PRJCODE'";
			$resPrd 		= $this->db->query($sqlPrd)->result();
			foreach($resPrd as $row) :
				$PRJCODE_HO = $row->PRJCODE_HO;		
			endforeach;
			$url		= site_url('c_comprof/c_bUd93tL15t/i180c2gdx/?id='.$this->url_encryption_helper->encode_url($PRJCODE_HO));
			redirect($url);
		}
	}
	
	function do_uploadPRJOTHSet() // GOOD
	{
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
		
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();
			
			date_default_timezone_set("Asia/Jakarta");
			$DATE_CREATED	= date('Y-m-d H:i:s');
		
			$proj_Number	= $this->input->post('proj_Number');
			$PRJCODE		= $this->input->post('PRJCODE');
			$PRJCODE_HO		= $this->input->post('PRJCODE_HO');
			$PRJ_ACCUM		= $this->input->post('PRJ_ACCUM');

			$projectheader = array('PRJ_ACCUM'	=> $PRJ_ACCUM);				
			$this->m_budget->update($proj_Number, $projectheader);
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $proj_Number;
				$MenuCode 		= 'MN408';
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
			
			$url			= site_url('c_comprof/c_bUd93tL15t/i180c2gdx/?id='.$this->url_encryption_helper->encode_url($PRJCODE_HO));
			redirect($url);
		}
	}
	
	function view_itemup() // OK
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$ITMH_CODE	= $_GET['id'];
			$ITMH_CODE	= $this->url_encryption_helper->decode_url($ITMH_CODE);
			
			$sqlPRJ		= "SELECT ITMH_PRJCODE FROM tbl_item_uphist WHERE ITMH_CODE = '$ITMH_CODE'";
			$sqlPRJR	= $this->db->query($sqlPRJ)->result();
			foreach($sqlPRJR as $rowPRJ) :
				$ITMH_PRJ		= $rowPRJ->ITMH_PRJCODE;
			endforeach;
	
			$data['PRJCODE']		= $ITMH_PRJ;
			$data['ITMH_CODE']		= $ITMH_CODE;
			$data['title'] 			= $this->data['appName'];
			$data['h2_title']		= 'View';
			$data['h3_title'] 		= 'master item';
			
			$this->load->view('v_company/v_budget/v_item_view_xl', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function downloadFile()
	{
		$this->load->helper('download');

		$collLink	= $_GET['id'];
		$collLink	= $this->url_encryption_helper->decode_url($collLink);
		$collLink1	= explode('~', $collLink);
		$theLink	= $collLink1[0];
		$FileUpName	= $collLink1[1];
		header("Content-Type: text/plain; charset=utf-8");
		header("Content-Type: application/force-download");
		header("Content-Disposition: attachment; filename=".$FileUpName);
	}
	
 	function M4573R_bUd9()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_comprof/c_bUd93tL15t/prjl0b28t18c/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function prjl0b28t18c() // OK - project list
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Master Anggaran";
			}
			else
			{
				$data["h1_title"] 	= "Budget Master";
			}
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN408';	 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_comprof/c_bUd93tL15t/i180c2gd_xMtbUd9/?id=";
			
			$data["secVIEW"]	= 'v_projectlist/project_list';
			if($this->session->userdata['nSELP'] == 0)
			{
				$PRJCODE	= $this->session->userdata['proj_Code'];
				$url		= site_url($data["secURL"].$this->url_encryption_helper->encode_url($PRJCODE));
				redirect($url);
			}
			else
			{
				$PRJCODE	= $this->session->userdata['proj_Code'];
				$url		= site_url($data["secURL"].$this->url_encryption_helper->encode_url($PRJCODE));
				redirect($url);
				
				//$this->load->view($data["secVIEW"], $data);
			}
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function i180c2gd_xMtbUd9()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			date_default_timezone_set("Asia/Jakarta");
			
			// -------------------- START : SEARCHING METHOD --------------------				
				$collDATA		= $_GET['id'];
				$EXP_COLLD1		= $this->url_encryption_helper->decode_url($collDATA);
				$EXP_COLLD		= explode('~', $EXP_COLLD1);	
				$C_COLLD1		= count($EXP_COLLD);
				
				if($C_COLLD1 > 1)
				{
					$EXP_COLLD1	= str_replace('~', '', $EXP_COLLD1);
					$key		= $EXP_COLLD[0];
					$PRJCODE	= $EXP_COLLD[1];
					$start		= 0;
					$end		= 30;
				}
				else
				{
					/*$sqlPRJHO	= "SELECT PRJCODE AS PRJCODEHO FROM tbl_project WHERE isHO = 1";
					$resPRJHO 	= $this->db->query($sqlPRJHO)->result();
					foreach($resPRJHO as $rowPRJHO) :
						$PRJCODEHO 	= $rowPRJHO->PRJCODEHO;
					endforeach;
					$key		= '';
					$PRJCODE	= $PRJCODEHO;
					$start		= 0;
					$end		= 50;*/
					
					$key		= '';
					$PRJCODE	= $EXP_COLLD[0];
					$start		= 0;
					$end		= 50;
				}
			// -------------------- END : SEARCHING METHOD --------------------
			
			$data['title'] 		= $appName;
			$SOURCEDOC			= "";
			$COLLDATA			= "$PRJCODE~$SOURCEDOC";
			$data['addURL'] 	= site_url('c_comprof/c_bUd93tL15t/addM5Tr/?id='.$this->url_encryption_helper->encode_url($COLLDATA));
			$data['backURL'] 	= site_url('c_comprof/c_bUd93tL15t/M4573R_bUd9/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
			$data['MenuCode'] 	= 'MN409';
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN409';
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
			
			$this->load->view('v_company/v_budget_mst/v_budget', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

  	function get_AllDataM5Tr() // GOOD
	{
		$PRJCODEHO	= $_GET['id'];
		
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
			
			$columns_valid 	= array("PRJPERIOD", 
									"PRJCODE_HO", 
									"PRJNAME",
									"PRJCOST",
									"PRJDATE",
									"PRJEDAT");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_budget_mst->get_AllDataC($PRJCODEHO, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			
			$query 			= $this->m_budget_mst->get_AllDataL($PRJCODEHO, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$proj_Number	= $dataI['proj_Number'];
				$PRJCODE		= $dataI['PRJCODE'];		
				$PRJCODE_HO		= $dataI['PRJCODE_HO'];
				$PRJPERIOD		= $dataI['PRJPERIOD'];
				$PRJNAME		= $dataI['PRJNAME'];
				$PRJDATE		= $dataI['PRJDATE'];
				$PRJEDAT		= $dataI['PRJEDAT'];
				$PRJDATEV		= date('d M Y', strtotime($PRJDATE));
				$PRJEDATV		= date('d M Y', strtotime($PRJEDAT));
				$PRJCOST		= $dataI['PRJCOST'];
				$PRJSTAT		= $dataI['PRJSTAT'];
				
				$PRJCODENM		= '';
				$sqlPrj			= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE_HO'";
				$resPrj 		= $this->db->query($sqlPrj)->result();
				foreach($resPrj as $rowPrj) :
					$PRJCODENM 	= $rowPrj->PRJNAME;		
				endforeach;
				
				$PRJPROG		= $dataI['PRJPROG'];
				if($PRJPROG == 0)
					$PRJPROGD	= 1;
				else
					$PRJPROGD	= $PRJPROG;
					
				if($PRJPROG <= 55)
				{
					$isActif 	= 1;
					$PROGCOL	= 'red';
				}
				elseif($PRJPROG <= 75)
				{
					$isActif 	= 1;
					$PROGCOL	= 'yellow';
				}
				elseif($PRJPROG <= 99)
				{
					$isActif 	= 1;
					$PROGCOL	= 'green';
				}
				elseif($PRJPROG == 100)
				{
					$isActif 	= 2;
					$PROGCOL	= 'blue';
				}
				
				if($isActif == 1)
				{
					$isActDesc	= 'Aktif';
					$STATCOL	= 'success';
				}
				elseif($isActif == 2)
				{
					$isActDesc	= 'Finish';
					$STATCOL	= 'primary';
				}
				else
				{
					$isActDesc	= 'Tidak Aktif';
					$STATCOL	= 'danger';
				}
				
				if($PRJSTAT == 1)
				{
					$isActDesc	= 'Aktif';
					$STATCOL	= 'success';
				}
				else
				{
					$isActDesc	= 'Tidak Aktif';
					$STATCOL	= 'danger';
				}
				
				$collData	= "$PRJCODE~$PRJPERIOD";
				$secUpd		= site_url('c_comprof/c_bUd93tL15t/u180c2gdtM5tr/?id='.$this->url_encryption_helper->encode_url($proj_Number));
				$secvwPRJ	= site_url('c_comprof/c_bUd93tL15t/c_project_progress/?id='.$this->url_encryption_helper->encode_url($proj_Number));
				$secDetail	= site_url('c_comprof/c_bUd93tL15t/gl180c21JLM5t/?id='.$this->url_encryption_helper->encode_url($collData));
				$secDetCOA	= site_url('c_comprof/c_bUd93tL15t/iNc04/?id='.$this->url_encryption_helper->encode_url($collData));
				$secDetITM	= site_url('c_comprof/c_bUd93tL15t/iNI7m/?id='.$this->url_encryption_helper->encode_url($collData));
				
				// CEK BUDGET DETAIL
				$sqlBUDGC	= "tbl_joblist_detail WHERE PRJCODE = '$PRJCODE'";
				$resBUDGC 	= $this->db->count_all($sqlBUDGC);
				
				// CEK COA
				$sqlCOAC	= "tbl_chartaccount WHERE PRJCODE = '$PRJCODE'";
				$resCOAC 	= $this->db->count_all($sqlCOAC);
                
				$secPrint		= "<input type='hidden' name='COA".$noU."' id='COA".$noU."' value='".$resCOAC."'>
									<input type='hidden' name='BUD".$noU."' id='BUD".$noU."' value='".$resBUDGC."'>
									<label style='white-space:nowrap'>
									   	<a href='".$secUpd."' class='btn btn-warning btn-xs' title='Update'>
											<i class='glyphicon glyphicon-pencil'></i>
									   	</a>
										<a href='".$secDetCOA."' target='_blank' class='btn btn-primary btn-xs' title='Detail COA' style='display:none'>
											<i class='fa fa-book'></i>
										</a>
										<a href='".$secDetail."' target='_blank' class='btn btn-info btn-xs' title='Detail Master Budget'>
                                            <i class='fa fa-cogs'></i>
                                        </a>
										<a href='".$secDetITM."' target='_blank' class='btn bg-purple btn-xs' title='Detail Item' style='display:none'>
                                            <i class='glyphicon glyphicon-equalizer'></i>
                                        </a>
                                        <a href='".$secvwPRJ."' class='btn btn-success btn-xs' title='Progress'>
                                            <i class='glyphicon glyphicon-stats'></i>
                                        </a>
									</label>";
								
				$output['data'][] = array("<div style='white-space:nowrap'>$PRJCODE</div>",
										  "<div style='white-space:nowrap'>$PRJCODE_HO - $PRJCODENM</div>",
										  $PRJNAME,
										  "<div style='text-align:right;'>".number_format($PRJCOST, 2)."</div>",
										  "<div style='text-align:center;'>".$PRJDATEV."</div>",
										  "<div style='text-align:center;'>".$PRJEDATV."</div>",
										  "<div style='text-align:center;'><span class='label label-".$STATCOL."' style='font-size:12px;'>".$isActDesc."</span></div>",
										  "<div style='text-align:center;'>".$secPrint."</div>");
				$noU			= $noU + 1;
			}
			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function addM5Tr() // OK
	{
		$this->load->model('m_project/m_listproject/m_listproject', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$collDATA		= $_GET['id'];
			$EXP_COLLD1		= $this->url_encryption_helper->decode_url($collDATA);
			$EXP_COLLD		= explode('~', $EXP_COLLD1);	
			$PRJCODE		= $EXP_COLLD[0];
			
			$data['title'] 		= $this->data['appName'];
			$data['task'] 		= 'add';
			$data['h2_title']	= 'Add Project';
			$data['main_view'] 	= 'v_company/v_budget_mst/v_budget_form';
			$data['main_view2'] = 'v_company/v_budget_mst/getaddress_sd';
			$data['form_action']= site_url('c_comprof/c_bUd93tL15t/add_processM5Tr');
			$data['backURL'] 	= site_url('c_comprof/c_bUd93tL15t/');
			
			$MenuCode 				= 'MN409';
			$data['MenuCode'] 		= 'MN409';
			//$data['viewDocPattern'] = $this->m_budget_mst->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN409';
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
			
			$this->load->view('v_company/v_budget_mst/v_budget_form', $data);
		}
		else
		{
			redirect('__I1y');
		}
	}
	
	function add_processM5Tr() // OK
	{
		$this->load->model('m_project/m_listproject/m_listproject', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		date_default_timezone_set("Asia/Jakarta");
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();
			
			$proj_Number	= $this->input->post('proj_Number');
			$PRJCODE_HO		= $this->input->post('PRJCODE_HO');
			$PRJPERIOD 		= $this->input->post('PRJPERIOD');
			$PRJCODE		= "$PRJCODE_HO.$PRJPERIOD";
			//$PRJCODE		= $PRJCODE_HO;
			$PRJNAME 		= $this->input->post('PRJNAME');
			$PRJDATE		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('PRJDATE'))));
			$PRJDATE_CO		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('PRJDATE_CO'))));
			$PRJEDAT		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('PRJEDAT'))));
			$PRJCNUM 		= $this->input->post('PRJCNUM');
			$PRJCATEG 		= $this->input->post('PRJCATEG');
			$PRJOWN 		= $this->input->post('PRJOWN');
			$PRJCURR		= $this->input->post('PRJCURR');
			$PRJCOST 		= $this->input->post('PRJCOST');
			$PRJLOCT 		= addslashes($this->input->post('PRJLOCT'));
			$PRJLKOT 		= addslashes($this->input->post('PRJLKOT'));
			$PRJ_MNG1		= $this->input->post('PRJ_MNG');
			$PRJNOTE		= addslashes($this->input->post('PRJNOTE'));
			$PRJSTAT 		= $this->input->post('PRJSTAT');			
			$PRJCBNG		= '';			
			$Patt_Year		= date('Y',strtotime($this->input->post('PRJDATE')));
			$Patt_Number	= $this->input->post('Patt_Number');
			
			$CURRRATE		= 1;
			
			$selStep	= 0;
			$PRJ_MNG	= '';
			if($PRJ_MNG1 != '')
			{
				foreach ($PRJ_MNG1 as $sel_pm)
				{
					$selStep	= $selStep + 1;
					if($selStep == 1)
					{
						$user_to	= explode ("|",$sel_pm);
						$user_ID	= $user_to[0];
						$PRJ_MNG	= $user_ID;
						//$coll_MADD	= $user_ADD;
					}
					else
					{					
						$user_to	= explode ("|",$sel_pm);
						$user_ID	= $user_to[0];			
						$PRJ_MNG	= "$TASKD_EMPID2;$user_ID";
						//$coll_MADD	= "$coll_MADD;$user_ADD";
					}
				}
			}
			
			// DISABLE OTHER BUDGET
			$DATE_CREATED	= date('Y-m-d H:i:s');
			
			$projectheader = array('proj_Number' 	=> $proj_Number,
									'PRJCODE'		=> $PRJCODE,
									'PRJCODE_HO'	=> $PRJCODE_HO,
									'PRJPERIOD'		=> $PRJPERIOD,
									'PRJCNUM'		=> $PRJCNUM,
									'PRJNAME'		=> $PRJNAME,
									'PRJLOCT'		=> $PRJLOCT,
									'PRJCATEG'		=> $PRJCATEG,
									'PRJOWN'		=> $PRJOWN,
									'PRJDATE'		=> $PRJDATE,
									'PRJDATE_CO'	=> $PRJDATE_CO,
									'PRJEDAT'		=> $PRJEDAT,
									'PRJBOQ'		=> $PRJCOST,
									'PRJCOST'		=> $PRJCOST,
									'PRJLKOT'		=> $PRJLKOT,
									'PRJCBNG'		=> $PRJCBNG,
									'PRJCURR'		=> $PRJCURR,
									'CURRRATE'		=> $CURRRATE,
									'PRJSTAT'		=> $PRJSTAT,
									'PRJNOTE'		=> $PRJNOTE,
									'PRJ_MNG'		=> $PRJ_MNG,
									'BUDG_LEVEL'	=> 1,
									'Patt_Year'		=> $Patt_Year,
									'Patt_Number'	=> $Patt_Number);
			$this->m_budget_mst->add($projectheader);
				
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$url			= site_url('c_comprof/c_bUd93tL15t/i180c2gd_xMtbUd9/?id='.$this->url_encryption_helper->encode_url($PRJCODE_HO));
			redirect($url);
		}
		else
		{
			redirect('__I1y');
		}
	}
	
	function gl180c21JLM5t() // OK
	{
		if ($this->session->userdata('login') == TRUE)
		{	
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			// START : GET MENU NAME
				$data['MenuCode'] 	= 'MN409';
				$MenuCode			= 'MN409';
				$getMNNm 		= $this->m_updash->get_menunm($MenuCode)->row();
				if($this->data['LangID'] == 'IND')
					$data['h1_title'] = $getMNNm->menu_name_IND;
				else
					$data['h1_title'] = $getMNNm->menu_name_ENG;
			// END : GET MENU NAME
			
			$collData			= $_GET['id'];
			$collData			= $this->url_encryption_helper->decode_url($collData);
			$dataArray			= explode("~", $collData);
			$PRJCODE			= $dataArray[0];
			$PRJPERIOD			= $dataArray[1];
			
			$data['title'] 		= $appName;
			$data['secAddURL'] 	= site_url('c_comprof/c_bUd93tL15t/add/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['backURL']	= site_url('c_comprof/c_bUd93tL15t/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$num_rows 			= $this->m_budget_mst->count_all_schedule($PRJCODE, $PRJPERIOD);
			$data['countjobl'] 	= $num_rows;
			$data['vwjoblist'] 	= $this->m_budget_mst->get_all_joblist($PRJCODE, $PRJPERIOD)->result();
			
			$getprojname 		= $this->m_budget_mst->get_project_name($PRJCODE, $PRJPERIOD)->row();
			$data['PRJNAME'] 	= $getprojname->PRJNAME;
			$data['BUDGNAME'] 	= $getprojname->BUDGNAME;
			$data['PRJCODE'] 	= $PRJCODE;
			$data['PRJPERIOD'] 	= $PRJPERIOD;
			
			$MenuCode 			= 'MN409';
			$data["MenuCode"] 	= 'MN409';
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $PRJPERIOD;
				$MenuCode 		= 'MN409';
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
			
			$this->load->view('v_company/v_budget_mst/v_joblistdet', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function u180c2gdtM5tr()
	{
		$this->load->model('m_project/m_listproject/m_listproject', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$proj_Number	= $_GET['id'];
			$proj_Number	= $this->url_encryption_helper->decode_url($proj_Number);
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title'] 		= 'Edit Project';
			$data['main_view'] 		= 'v_company/v_budget/v_budget_form';
			$data['form_action']	= site_url('c_comprof/c_bUd93tL15t/update_processM5tr');			
			
			$data['recordcountCust'] 	= $this->m_budget->count_all_num_rowsCust();
			$data['viewcustomer'] 		= $this->m_budget->viewcustomer()->result();
			
			$MenuCode 					= 'MN409';
			$data['MenuCode'] 			= 'MN409';
			$data['viewDocPattern'] 	= $this->m_budget->getDataDocPat($MenuCode)->result();
			
			$getproject = $this->m_budget_mst->get_PROJ_by_number($proj_Number)->row();
					
			$data['default']['proj_Number'] = $getproject->proj_Number;
			$PRJCODE						= $getproject->PRJCODE;
			$data['default']['PRJCODE'] 	= $getproject->PRJCODE;
			$data['default']['PRJCODE_HO'] 	= $getproject->PRJCODE_HO;
			$PRJCODE_HO						= $getproject->PRJCODE_HO;
			$data['default']['PRJPERIOD'] 	= $getproject->PRJPERIOD;
			$data['default']['PRJCNUM'] 	= $getproject->PRJCNUM;
			$data['default']['PRJNAME'] 	= $getproject->PRJNAME;
			$data['default']['PRJLOCT'] 	= $getproject->PRJLOCT;
			$data['default']['PRJCATEG'] 	= $getproject->PRJCATEG;
			$data['default']['PRJOWN'] 		= $getproject->PRJOWN;
			$data['default']['PRJDATE'] 	= $getproject->PRJDATE;
			$data['default']['PRJDATE_CO'] 	= $getproject->PRJDATE_CO;
			$data['default']['PRJEDAT'] 	= $getproject->PRJEDAT;
			$PRJEDAT						= $getproject->PRJEDAT;
			//echo "c_hehe $PRJEDAT";
			$data['default']['PRJCOST'] 	= $getproject->PRJCOST;
			$data['default']['PRJBOQ'] 		= $getproject->PRJBOQ;
			$data['default']['PRJRAP'] 		= $getproject->PRJRAP;
			$data['default']['PRJLKOT'] 	= $getproject->PRJLKOT;
			$data['default']['PRJCBNG']		= $getproject->PRJCBNG;
			$data['default']['PRJCURR']		= $getproject->PRJCURR;
			$data['default']['CURRRATE']	= $getproject->CURRRATE;
			$data['default']['PRJSTAT'] 	= $getproject->PRJSTAT;
			$data['default']['PRJNOTE'] 	= $getproject->PRJNOTE;
			$data['default']['isHO']		= $getproject->isHO;
			
			$data['default']['ISCHANGE']	= $getproject->ISCHANGE;
			$data['default']['REFCHGNO']	= $getproject->REFCHGNO;
			$data['default']['PRJCOST2'] 	= $getproject->PRJCOST2;
			$data['default']['CHGUSER'] 	= $getproject->CHGUSER;
			$data['default']['CHGSTAT'] 	= $getproject->CHGSTAT;
			$data['default']['PRJ_MNG'] 	= $getproject->PRJ_MNG;
			$data['default']['QTY_SPYR'] 	= $getproject->QTY_SPYR;
			$data['default']['PRC_STRK'] 	= $getproject->PRC_STRK;
			$data['default']['PRC_ARST'] 	= $getproject->PRC_ARST;
			$data['default']['PRC_MKNK'] 	= $getproject->PRC_MKNK;
			$data['default']['PRC_ELCT'] 	= $getproject->PRC_ELCT;
			$data['default']['PRJ_IMGNAME'] = $getproject->PRJ_IMGNAME;
			$data['default']['Patt_Year'] 	= $getproject->Patt_Year;
			$data['default']['Patt_Number'] = $getproject->Patt_Number;
			
			$data['backURL'] 		= site_url('c_comprof/c_bUd93tL15t/i180c2gd_xMtbUd9/?id='.$this->url_encryption_helper->encode_url($PRJCODE_HO));
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getproject->proj_Number;
				$MenuCode 		= 'MN409';
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
			
			$this->load->view('v_company/v_budget_mst/v_budget_form', $data);
		}
		else
		{
			redirect('__I1y');
		}
	}
	
	function update_processM5tr()
	{
		$this->load->model('m_project/m_listproject/m_listproject', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$this->db->trans_begin();
			
			date_default_timezone_set("Asia/Jakarta");
			$DATE_CREATED	= date('Y-m-d H:i:s');
			
			$proj_Number	= $this->input->post('proj_Number');			
			$PRJCODE_HO		= $this->input->post('PRJCODE_HO');
			$PRJPERIOD 		= $this->input->post('PRJPERIOD');
			$PRJCODE		= "$PRJCODE_HO.$PRJPERIOD";
			$PRJNAME 		= $this->input->post('PRJNAME');
			
			$PRJDATE		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('PRJDATE'))));
			$PRJDATE_CO		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('PRJDATE_CO'))));
			$PRJEDAT		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('PRJEDAT'))));
			$PRJCNUM 		= $this->input->post('PRJCNUM');
			$PRJCATEG 		= $this->input->post('PRJCATEG');
			$PRJOWN 		= $this->input->post('PRJOWN');
			$PRJCURR		= $this->input->post('PRJCURR');
			$PRJCOST 		= $this->input->post('PRJCOST');
			$PRJLOCT 		= addslashes($this->input->post('PRJLOCT'));
			$PRJLKOT 		= addslashes($this->input->post('PRJLKOT'));
			$PRJ_MNG1		= $this->input->post('PRJ_MNG');
			$PRJNOTE		= addslashes($this->input->post('PRJNOTE'));
			$PRJSTAT 		= $this->input->post('PRJSTAT');			
			$PRJCBNG		= '';			
			$Patt_Year		= date('Y',strtotime($this->input->post('PRJDATE')));
			
			$CURRRATE		= 1;
			
			$selStep	= 0;
			$PRJ_MNG	= '';
			if($PRJ_MNG1 != '')
			{
				foreach ($PRJ_MNG1 as $sel_pm)
				{
					$selStep	= $selStep + 1;
					if($selStep == 1)
					{
						$user_to	= explode ("|",$sel_pm);
						$user_ID	= $user_to[0];
						$PRJ_MNG	= $user_ID;
						//$coll_MADD	= $user_ADD;
					}
					else
					{					
						$user_to	= explode ("|",$sel_pm);
						$user_ID	= $user_to[0];			
						$PRJ_MNG	= "$TASKD_EMPID2;$user_ID";
						//$coll_MADD	= "$coll_MADD;$user_ADD";
					}
				}
			}
			
			$projectheader = array('PRJCODE'		=> $PRJCODE,
									'PRJCODE_HO'	=> $PRJCODE_HO,
									'PRJPERIOD'		=> $PRJPERIOD,
									'PRJCNUM'		=> $PRJCNUM,
									'PRJNAME'		=> $PRJNAME,
									'PRJLOCT'		=> $PRJLOCT,
									'PRJCATEG'		=> $PRJCATEG,
									'PRJOWN'		=> $PRJOWN,
									'PRJDATE'		=> $PRJDATE,
									'PRJDATE_CO'	=> $PRJDATE_CO,
									'PRJEDAT'		=> $PRJEDAT,
									'PRJBOQ'		=> $PRJCOST,
									'PRJCOST'		=> $PRJCOST,
									'PRJLKOT'		=> $PRJLKOT,
									'PRJCBNG'		=> $PRJCBNG,
									'PRJCURR'		=> $PRJCURR,
									'CURRRATE'		=> $CURRRATE,
									'PRJSTAT'		=> $PRJSTAT,
									'PRJNOTE'		=> $PRJNOTE,
									'PRJ_MNG'		=> $PRJ_MNG,
									'BUDG_LEVEL'	=> 1,
									'Patt_Year'		=> $Patt_Year);				
			$this->m_budget_mst->update($proj_Number, $projectheader);
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $proj_Number;
				$MenuCode 		= 'MN408';
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
			
			$url	= site_url('c_comprof/c_bUd93tL15t/i180c2gd_xMtbUd9/?id='.$this->url_encryption_helper->encode_url($PRJCODE_HO));
			redirect($url);
		}
		else
		{
			redirect('__I1y');
		}
	}
	
	function upd1d0ebbM5tr() // OK
	{
		/*$JOBCODEID		= $_GET['id'];
		$JOBCODEID		= $this->url_encryption_helper->decode_url($JOBCODEID);
		
		$COUNTALL		= strlen($JOBCODEID);
		$COUNTALLA		= $COUNTALL - 4;
		$PRJCODE		= substr($JOBCODEID, 0, $COUNTALLA);*/
			
		$collData		= $_GET['id'];
		$collData		= $this->url_encryption_helper->decode_url($collData);
		$data1			= explode("~", $collData);
		$PRJCODE		= $data1[0];
		$JOBCODEID		= $data1[1];
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title']		= 'Edit Job';
			$data['h3_title'] 		= 'project';
			$data['form_action']	= site_url('c_comprof/c_bUd93tL15t/update_process');			
			
			$getjoblist 			= $this->m_budget_mst->get_joblist_by_code($JOBCODEID)->row();
			
			$data['default']['JOBCODEID'] 	= $getjoblist->JOBCODEID;
			$data['default']['JOBCODEIDV'] 	= $getjoblist->JOBCODEIDV;
			$data['default']['JOBPARENT'] 	= $getjoblist->JOBPARENT;
			$PRJCODE						= $getjoblist->PRJCODE;
			$PRJPERIOD						= $getjoblist->PRJPERIOD;
			$data['default']['PRJCODE'] 	= $getjoblist->PRJCODE;
			$data['default']['JOBCOD1'] 	= $getjoblist->JOBCOD1;
			$data['default']['JOBDESC'] 	= $getjoblist->JOBDESC;
			$data['default']['JOBCLASS'] 	= $getjoblist->JOBCLASS;
			$data['default']['JOBGRP'] 		= $getjoblist->JOBGRP;
			$data['default']['JOBTYPE'] 	= $getjoblist->JOBTYPE;
			$data['default']['JOBUNIT'] 	= $getjoblist->JOBUNIT;
			$data['default']['JOBLEV'] 		= $getjoblist->JOBLEV;
			$data['default']['JOBVOLM'] 	= $getjoblist->JOBVOLM;
			$data['default']['PRICE'] 		= $getjoblist->PRICE;
			$data['default']['JOBCOST'] 	= $getjoblist->JOBCOST;
			$data['default']['ISLAST'] 		= $getjoblist->ISLAST;
			$data['default']['ITM_NEED'] 	= $getjoblist->ITM_NEED;
			$data['default']['ITM_GROUP'] 	= $getjoblist->JOBGRP;
			$data['default']['ISHEADER'] 	= $getjoblist->ISHEADER;
			$data['default']['ITM_CODE'] 	= $getjoblist->ITM_CODE;
			$data['default']['Patt_Number']	= $getjoblist->Patt_Number;
			
			$cancel					= site_url('c_comprof/c_bUd93tL15t/gl180c21JL/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			$data['countParent']	= $this->m_budget_mst->count_all_job1();		
			$data['vwParent'] 		= $this->m_budget_mst->get_all_job1()->result();
			
			$data['backURL'] 		= $cancel;
			$getprojname 			= $this->m_budget_mst->get_project_name($PRJCODE, $PRJPERIOD)->row();			
			$data['PRJNAME'] 		= $getprojname->PRJNAME;
			$data['PRJCODE'] 		= $PRJCODE;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getjoblist->JOBCODEID;
				$MenuCode 		= 'MN409';
				$TTR_CATEG		= 'U-JL';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$this->load->view('v_company/v_budget_mst/v_joblistdet_form_upt', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
    function getPrd($prdSel)
	{
		$PRJCODE	= '';
		$sqlPrd		= "SELECT PRJCODE_HO FROM tbl_project_budgm WHERE PRJPERIOD = '$prdSel'";
		$resPrd 	= $this->db->query($sqlPrd)->result();
		foreach($resPrd as $row) :
			$PRJCODE = $row->PRJCODE_HO;		
		endforeach;
		$sqlPrj		= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
		$resPrj 	= $this->db->query($sqlPrj)->result();
		foreach($resPrj as $rowPrj) :
			$PRJNAME = $rowPrj->PRJNAME;		
		endforeach;
		echo "$PRJCODE~$PRJNAME";
    }
	
    function getComp($prjSel)
	{
		$sqlPrj		= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$prjSel'";
		$resPrj 	= $this->db->query($sqlPrj)->result();
		foreach($resPrj as $rowPrj) :
			$PRJNAME = $rowPrj->PRJNAME;
		endforeach;
		echo "$prjSel~$PRJNAME";
    }
	
	function getJOBLIST()
	{
		$levJOB 		= $_POST['depart'];
		$splitlevJOB	= explode("~", $levJOB);
		$JOBLEV			= $splitlevJOB[0];
		$JOBLEVN		= $JOBLEV - 1;
		$PRJCODE		= $splitlevJOB[1];
		
		$jl_arr = array();
		
		$jl_arr[]	= array("JOBCODEID" => "0", "JOBDESC" => " --- ", "ISDISABLED" => "");
		$Disabled_1	= 0;
		$sqlJob_1	= "SELECT JOBCODEID, JOBDESC FROM tbl_joblist WHERE JOBLEV = 1 AND PRJCODE = '$PRJCODE'";
		$resJob_1	= $this->db->query($sqlJob_1)->result();
		foreach($resJob_1 as $row_1) :
			$JOBCODEID_1	= $row_1->JOBCODEID;
			$JOBDESC_1		= $row_1->JOBDESC;			
			$space_lev_1	= ""; 
			$JOBDESCV_1		= "$space_lev_1 $JOBCODEID_1 : $JOBDESC_1";
			
			$sqlC_2			= "tbl_joblist WHERE JOBPARENT = '$JOBCODEID_1' AND ISHEADER = '1' AND PRJCODE = '$PRJCODE'";
			$resC_2 		= $this->db->count_all($sqlC_2);
			
			$ISDISABLED		= "disabled";
			if($JOBLEVN == 1)
				$ISDISABLED	= "";
				
			$jl_arr[]		= array("JOBCODEID" => $JOBCODEID_1, "JOBDESC" => $JOBDESCV_1, "ISDISABLED" => $ISDISABLED);
			
			if($resC_2 > 0)
			{
				$Disabled_2	= 0;
				$sqlJob_2	= "SELECT JOBCODEID, JOBDESC FROM tbl_joblist WHERE JOBPARENT = '$JOBCODEID_1' AND ISHEADER = '1' AND PRJCODE = '$PRJCODE'";
				$resJob_2	= $this->db->query($sqlJob_2)->result();
				foreach($resJob_2 as $row_2) :
					$JOBCODEID_2	= $row_2->JOBCODEID;
					$JOBDESC_2		= $row_2->JOBDESC;
					$space_lev_2	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; 
					$JOBDESCV_2		= "$space_lev_2 $JOBCODEID_2 : $JOBDESC_2";
					
					$sqlC_3		= "tbl_joblist WHERE JOBPARENT = '$JOBCODEID_2' AND ISHEADER = '1'";
					$resC_3 	= $this->db->count_all($sqlC_3);
					
					$ISDISABLED	= "disabled";
					if($JOBLEVN == 2)
						$ISDISABLED	= "";
						
					$jl_arr[]	= array("JOBCODEID" => $JOBCODEID_2, "JOBDESC" => $JOBDESCV_2, "ISDISABLED" => $ISDISABLED);
					
					if($resC_3 > 0)
					{
						$sqlJob_3	= "SELECT JOBCODEID, JOBDESC FROM tbl_joblist WHERE JOBPARENT = '$JOBCODEID_2' AND ISHEADER = '1'
										AND PRJCODE = '$PRJCODE'";
						$resJob_3	= $this->db->query($sqlJob_3)->result();
						foreach($resJob_3 as $row_3) :
							$JOBCODEID_3	= $row_3->JOBCODEID;
							$JOBDESC_3		= $row_3->JOBDESC;
							$space_lev_3	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; 
							$JOBDESCV_3		= "$space_lev_3 $JOBCODEID_3 : $JOBDESC_3";
							
							$sqlC_4		= "tbl_joblist WHERE JOBPARENT = '$JOBCODEID_3' AND ISHEADER = '1' AND PRJCODE = '$PRJCODE'";
							$resC_4 	= $this->db->count_all($sqlC_4);
							
							$ISDISABLED	= "disabled";
							if($JOBLEVN == 3)
								$ISDISABLED	= "";
								
							$jl_arr[]	= array("JOBCODEID" => $JOBCODEID_3, "JOBDESC" => $JOBDESCV_3, "ISDISABLED" => $ISDISABLED);
							
							if($resC_4 > 0)
							{
								$Disabled_4	= 0;
								$sqlJob_4	= "SELECT JOBCODEID, JOBDESC FROM tbl_joblist WHERE JOBPARENT = '$JOBCODEID_3' AND ISHEADER = '1' AND PRJCODE = '$PRJCODE'";
								$resJob_4	= $this->db->query($sqlJob_4)->result();
								foreach($resJob_4 as $row_4) :
									$JOBCODEID_4	= $row_4->JOBCODEID;
									$JOBDESC_4		= $row_4->JOBDESC;
									$space_lev_4	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; 
									$JOBDESCV_4		= "$space_lev_4 $JOBCODEID_4 : $JOBDESC_4";
									
									$sqlC_5		= "tbl_joblist WHERE JOBPARENT = '$JOBCODEID_4' AND ISHEADER = '1' AND PRJCODE = '$PRJCODE'";
									$resC_5 	= $this->db->count_all($sqlC_5);
									
									$ISDISABLED	= "disabled";
									if($JOBLEVN == 4)
										$ISDISABLED	= "";
										
									$jl_arr[]	= array("JOBCODEID" => $JOBCODEID_4, "JOBDESC" => $JOBDESCV_4, "ISDISABLED" => $ISDISABLED);
									
									if($resC_5 > 0)
									{
										$Disabled_5	= 0;
										$sqlJob_5	= "SELECT JOBCODEID, JOBDESC FROM tbl_joblist WHERE JOBPARENT = '$JOBCODEID_4' AND ISHEADER = '1' AND PRJCODE = '$PRJCODE'";
										$resJob_5	= $this->db->query($sqlJob_5)->result();
										foreach($resJob_5 as $row_5) :
											$JOBCODEID_5	= $row_5->JOBCODEID;
											$JOBDESC_5		= $row_5->JOBDESC;
											$space_lev_5	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; 
											$JOBDESCV_5		= "$space_lev_5 $JOBCODEID_5 : $JOBDESC_5";
											
											$sqlC_6		= "tbl_joblist WHERE JOBPARENT = '$JOBCODEID_5' AND ISHEADER = '1' AND PRJCODE = '$PRJCODE'";
											$resC_6 	= $this->db->count_all($sqlC_6);
											
											$ISDISABLED	= "disabled";
											if($JOBLEVN == 5)
												$ISDISABLED	= "";
												
											$jl_arr[]	= array("JOBCODEID" => $JOBCODEID_5, "JOBDESC" => $JOBDESCV_5, "ISDISABLED" => $ISDISABLED);
											
											if($resC_6 > 0)
											{
												$Disabled_6	= 0;
												$sqlJob_6	= "SELECT JOBCODEID, JOBDESC FROM tbl_joblist WHERE JOBPARENT = '$JOBCODEID_5' AND ISHEADER = '1' AND PRJCODE = '$PRJCODE'";
												$resJob_6	= $this->db->query($sqlJob_6)->result();
												foreach($resJob_6 as $row_6) :
													$JOBCODEID_6	= $row_6->JOBCODEID;
													$JOBDESC_6		= $row_6->JOBDESC;
													$space_lev_6	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; 
													$JOBDESCV_6		= "$space_lev_6 $JOBCODEID_6 : $JOBDESC_6";
													
													$sqlC_7			= "tbl_joblist WHERE JOBPARENT = '$JOBCODEID_6' AND ISHEADER = '1' 
																		AND PRJCODE = '$PRJCODE'";
													$resC_7 		= $this->db->count_all($sqlC_7);
																								
													$ISDISABLED		= "disabled";
													if($JOBLEVN == 6)
														$ISDISABLED	= "";
														
													$jl_arr[]	= array("JOBCODEID" => $JOBCODEID_5, "JOBDESC" => $JOBDESCV_5, "ISDISABLED" => $ISDISABLED);
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
		echo json_encode($jl_arr);
	}

  	function get_AllDataJL() // GOOD
	{
		$PRJCODE		= $_GET['id'];

		setlocale(LC_ALL, 'id-ID', 'id_ID');

		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		$PRJCODE	= $_GET['id'];

		$LangID     = $this->session->userdata['LangID'];
        
        $sqlTransl      = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
        $resTransl      = $this->db->query($sqlTransl)->result();
        foreach($resTransl as $rowTransl) :
            $TranslCode = $rowTransl->MLANG_CODE;
            $LangTransl = $rowTransl->LangTransl;

    		if($TranslCode == 'Director')$Director = $LangTransl;
            if($TranslCode == 'Compailer')$Compailer = $LangTransl;
            if($TranslCode == 'Location')$Location = $LangTransl;
    		if($TranslCode == 'Active')$Active = $LangTransl;
    		if($TranslCode == 'Inactive')$Inactive = $LangTransl;
    		if($TranslCode == 'Contact')$Contact = $LangTransl;
    		if($TranslCode == 'CompanyName')$CompanyName = $LangTransl;
    		if($TranslCode == 'Budget')$Budget = $LangTransl;
    		if($TranslCode == 'Phone')$Phone = $LangTransl;
    		if($TranslCode == 'Notes')$Notes = $LangTransl;
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
			
			$columns_valid 	= array("JOBCODEID", 
									"ITM_UNIT", 
									"JOBDESC");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			/*$num_rows 		= $this->m_budget->get_AllDataJLC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			
			$query 			= $this->m_budget->get_AllDataJLL($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			$TotBOQ			= 0;
			$TotBUD			= 0;
			$TotADD			= 0;
			$TotADD2		= 0;
			$TotADD3		= 0;
			$TotADD4		= 0;
			$TotADD5		= 0;
			$TotALL			= 0;
			$TotREM			= 0;
			$REMAIN2		= 0;
			$TotUSE			= 0;
			$TotPC			= 0;	// Total Project Complete
			foreach ($query->result_array() as $dataI) 
			{
				$ORD_ID 		= $dataI['ORD_ID'];
				$JOBCODEID 		= $dataI['JOBCODEID'];
				$JOBDESC		= $dataI['JOBDESC'];
				$JOBLEV			= $dataI['IS_LEVEL'];
				$ITM_UNIT		= $dataI['ITM_UNIT'];
				$JOBUNIT 		= strtoupper($dataI['ITM_UNIT']);
				if($JOBUNIT == '')
					$JOBUNIT= 'LS';

				$ITM_GROUP		= $dataI['ITM_GROUP'];
				$JOBVOLM		= $dataI['ITM_VOLM'];
				$JOBPRICE		= $dataI['ITM_PRICE'];
				$ITM_LASTP		= $dataI['ITM_LASTP'];
				$JOBCOST		= $dataI['ITM_BUDG'];
				$ADD_VOLM 		= $dataI['ADD_VOLM'];
				$ADD_PRICE		= $dataI['ADD_PRICE'];
				$ADD_JOBCOST	= $dataI['ADD_JOBCOST'];
				$ADDM_VOLM 		= $dataI['ADDM_VOLM'];
				$ADDM_JOBCOST	= $dataI['ADDM_JOBCOST'];
				$ITM_USED		= $dataI['ITM_USED'];
				$ITM_USED_AM	= $dataI['ITM_USED_AM'];
				$ISLAST			= $dataI['ISLAST'];

				$CollID			= "$PRJCODE~$JOBCODEID";
				$secUpd			= site_url('c_comprof/c_bUd93tL15t/upd1d0ebb/?id='.$this->url_encryption_helper->encode_url($CollID));
                
				$secPrint		= 	"<input type='hidden' name='urlUpdate".$noU."' id='urlUpdate".$noU."' value='".$secUpd."'>
									<label style='white-space:nowrap'>
									   	<a href='javascript:void(null);' class='btn btn-warning btn-xs' onClick='updJob(".$noU.")'>
											<i class='glyphicon glyphicon-pencil'></i>
										</a>
									</label>";

				if($ISLAST == 0)	// HEADER
				{
					$JOBDESC1 	= $JOBDESC;
					$STATCOL	= 'primary';
					$CELL_COL	= "style='font-weight:bold; white-space:nowrap'";

					// PENJUMLAHAN ADDENDUM
						$TOT_BUDGAM	= 0;
						$TOT_ADDAM	= 0;
						$TOT_USEDAM	= 0;
						$sqlTBUDG	= "SELECT SUM(ITM_BUDG) AS TOT_BUDGAM, SUM(ADD_JOBCOST) AS TOT_ADDAM, SUM(ITM_USED_AM) AS TOT_USEDAM
										FROM tbl_joblist_detail
										WHERE JOBPARENT LIKE '$JOBCODEID%' AND PRJCODE = '$PRJCODE'
											AND IS_LEVEL > $JOBLEV AND ISLAST = 1";
						$resTBUDG 	= $this->db->query($sqlTBUDG)->result();
						foreach($resTBUDG as $rowTBUDG) :
							$TOT_BUDGAM	= $rowTBUDG->TOT_BUDGAM;
							$TOT_ADDAM 	= $rowTBUDG->TOT_ADDAM;
							$TOT_USEDAM = $rowTBUDG->TOT_USEDAM;
						endforeach;
						$JOBCOST		= $TOT_BUDGAM;

						$JOBVOLM 		= 1;
						$JOBPRICE 		= $TOT_BUDGAM;
						$ADD_JOBCOST 	= $TOT_ADDAM;
						$ITM_USED_AM 	= $TOT_USEDAM;
						$REMAIN			= $JOBPRICE + $ADD_JOBCOST - $ITM_USED_AM;
						
						$TOTPRJC_01		= 0;
						$TotPC			= $TotPC + $TOTPRJC_01;
				}
				else
				{
					$strLEN 	= strlen($JOBDESC);
					$JOBDESCA	= substr("$JOBDESC", 0, 60);
					$JOBDESC1 	= $JOBDESCA;
					if($strLEN > 60)
						$JOBDESC1 	= $JOBDESCA."...";
					$STATCOL	= 'success';
					$CELL_COL	= "style='white-space:nowrap'";

					$TotBUD		= $TotBUD + $JOBCOST;
					
					$TotBOQ		= $TotBOQ + $JOBCOST + $ADD_JOBCOST;
					$TotADD		= $TotADD + $ADD_JOBCOST;
					$TotALL		= $TotBOQ + $TotADD;
					$TotUSE		= $TotUSE + $ITM_USED_AM;
					
					$REMAIN		= $JOBCOST + $ADD_JOBCOST - $ITM_USED_AM;
					$TotREM		= $TotREM + $REMAIN2;

					// COUNT PROJECTION COMPLETED
						$TOTUSED_01	= $ITM_USED_AM;
						if($JOBUNIT == 'LS')
							$TOTREMA_01	= ($JOBCOST + $ADD_JOBCOST - $TOTUSED_01);
						else
							$TOTREMA_01	= ($JOBVOLM + $ADD_VOLM - $ITM_USED) * $ITM_LASTP;
						
						$TOTPRJC_01	= $TOTUSED_01 + $TOTREMA_01;	// Total Projection Complete
						$TotPC		= $TotPC + $TOTPRJC_01;
				}

				// SPACE
					if($JOBLEV == 1)
						$spaceLev 	= "";
					elseif($JOBLEV == 2)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 3)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 4)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 5)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 6)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 7)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 8)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 9)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 10)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 11)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 12)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

				$JobView		= "$spaceLev $JOBCODEID - $JOBDESC1";

				$TOT_JOBCOST	= $JOBCOST + $ADD_JOBCOST - $ITM_USED_AM;
				$TOT_JOBCOSTP 	= $TOT_JOBCOST ?: 1;
				$percBDG		= $ITM_USED_AM / $TOT_JOBCOSTP * 100;		// Used Percentation
				$STATCOL1		= 'success';
				if($percBDG > 85)
					$STATCOL	= 'danger';
				elseif($percBDG > 60)
					$STATCOL	= 'warning';
				elseif($percBDG > 0)
					$STATCOL	= 'success';

				$ADDVOL_VW 		= "";
				$ADDPRC_VW 		= "";
				if($ISLAST == 1)
				{
					if($ADD_VOLM > 0)
					{
						$ADDVOL_VW 	= 	"<div>
											<p class='text-yellow'><i class='glyphicon glyphicon-triangle-top'></i>
									  		".number_format($ADD_VOLM, 2)."</p>
									  	</div>";
						$ADDPRC_VW 	= 	"<div>
											<p class='text-yellow'><i class='glyphicon glyphicon-triangle-top'></i>
									  		".number_format($ADD_PRICE, 2)."</p>
									  	</div>";
					}
					if($ADDM_VOLM > 0)
					{
						$ADDVOL_VW 	= 	"<div>
											<p class='text-yellow'><i class='glyphicon glyphicon-triangle-top'></i>
									  		".number_format($ADDM_VOLM, 2)."</p>
									  	</div>";
					}
				}
				
				$JOBVOLMV 	= $JOBVOLM;
				$JOBPRICEV 	= $JOBPRICE;
				if($JOBUNIT == 'LUMP')
				{
					$JOBVOLMV 	= 1;
					$JOBPRICEV 	= $JOBVOLM;
				}

				$output['data'][] 	= array("<span ".$CELL_COL.">".$JobView."</span>",
											"<div style='text-align:center;'><span ".$CELL_COL.">".$JOBUNIT."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".number_format($JOBVOLMV, 2).$ADDVOL_VW."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".number_format($JOBPRICEV, 2).$ADDPRC_VW."</span></div>",
											"<div style='text-align:right;'><span class='label label-".$STATCOL."' style='font-size:12px'>".number_format($JOBCOST,2)."</span></div>",
											"<div style='text-align:right;'><span class='label label-".$STATCOL."' style='font-size:12px'>".number_format($ADD_JOBCOST,2)."</span></div>",
											"<div style='text-align:right;'><span class='label label-".$STATCOL."' style='font-size:12px'>".number_format($ITM_USED_AM,2)."</span></div>",
											"<div style='text-align:right;'><span class='label label-".$STATCOL."' style='font-size:12px'>".number_format($REMAIN,2)."</span></div>",
											"<div style='text-align:right;'><span class='label label-".$STATCOL."' style='font-size:12px'>".number_format($TOTPRJC_01,2)."</span></div>",
											"<div style='text-align:center;'>".$secPrint."</div>");

				$noU			= $noU + 1;
			}*/

			$output['data'][] 	= array("A",
										"B",
										"C",
										"D",
										"E",
										"F",
										"F",
										"G",
										"H",
										"I");

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

  	function get_AllDataHIST() // GOOD
	{
		$PRJCODE		= $_GET['id'];
		
		// START : FOR SERVER-SIDE
			$order 	= $this->input->get("order");

			$col 	= 0;
			$dir 	= "desc";
			if(!empty($order)) {
				foreach($order as $o) {
					$col 	= $o['column'];
					$dir	= $o['dir'];
				}
			}
			
			$dir 	= 'desc';
			if($dir != "asc" && $dir != "desc") 
			{
            	$dir = "asc";
        	}
			
			$columns_valid 	= array("BOQH_DATE");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_budget->get_AllDataHISTC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_budget->get_AllDataHISTL($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
                $BOQH_CODE 		= $dataI['BOQH_CODE'];
                $BOQH_PRJCODE 	= $dataI['BOQH_PRJCODE'];
                $BOQH_DATE 		= $dataI['BOQH_DATE'];
                $BOQH_DESC	 	= $dataI['BOQH_DESC'];
                $BOQH_FN	 	= $dataI['BOQH_FN'];
                $BOQH_STAT	 	= $dataI['BOQH_STAT'];
                $BOQH_USER	 	= $dataI['BOQH_USER'];
                $BOQH_TYPE	 	= $dataI['BOQH_TYPE'];
                
                if($BOQH_STAT == 0)
                {
                    $BOQH_STATD = 'fake';
                    $STATCOL	= 'danger';
                    $disabled1	= 1;	// Icon Process
                    $disabled2	= 1;	// Icon Flag
                }
                elseif($BOQH_STAT == 1)
                {
                    $BOQH_STATD = 'New';
                    $STATCOL	= 'warning';
                    $disabled1	= 0;	// Icon Process
                    $disabled2	= 1;	// Icon Flag
                }
                elseif($BOQH_STAT == 2)
                {
                    $BOQH_STATD = 'success';
                    $STATCOL	= 'success';
                    $disabled1	= 1;	// Icon Process
                    $disabled2	= 0;	// Icon Flag
                }
                elseif($BOQH_STAT == 3)
                {
                    $BOQH_STATD = 'changed';
                    $STATCOL	= 'primary';
                    $disabled1	= 1;	// Icon Process
                    $disabled2	= 1;	// Icon Flag
                }

                $disabled1d		= "";
                $disabled2d		= "";
                if($disabled1 == 1)
                	$disabled1d	= "disabled='disabled'";
                if($disabled2 == 1)
                	$disabled2d	= "disabled='disabled'";

                $FileName 	= $BOQH_FN;
                $secVWlURL	= site_url('c_gl/c_ch1h0fbeart/view_coaup/?id='.$this->url_encryption_helper->encode_url($BOQH_CODE));
                $urlDwl		= base_url('index.php/c_comprof/c_bUd93tL15t/dwlFileBoQ/?id='.urldecode($FileName));

				$secAction	= 	"<input type='hidden' name='secVWlURL_".$noU."' id='secVWlURL_".$noU."' value='".$secVWlURL."'>
						   		<input type='hidden' name='BOQH_CODE".$noU."' id='BOQH_CODE".$noU."' value='".$BOQH_CODE."'>
							   	<label style='white-space:nowrap'>
							   	<a href='javascript:void(null);' onClick='procIMP(".$noU.");' data-skin='skin-green' class='btn btn-warning btn-xs' title='Process' ".$disabled1d.">
                                    <i class='glyphicon glyphicon-refresh'></i>
                                </a>
							   	<a href='javascript:void(null);' data-skin='skin-green' class='btn btn-success btn-xs' title='Processed'".$disabled1d.">
                                    <i class='glyphicon glyphicon-flag'></i>
                                </a>
                                <a href='".$urlDwl."' target='_self' class='btn btn-primary btn-xs' title='Process'>
                                    <i class='glyphicon glyphicon-download-alt'></i>
                                </a>
                                <a href='javascript:void(null);' onClick='viewboq(".$noU.");' data-skin='skin-green' class='btn btn-info btn-xs' title='View Document' style='display:none'>
                                    <i class='fa fa-eye'></i>
                                </a>
								</label>";

				$output['data'][] 	= array("<label style='white-space:nowrap'>".$BOQH_DATE."</label>",
										  	$BOQH_DESC,
										  	"<span class='label label-".$STATCOL."' style='font-size:12px'>".$BOQH_STATD."</span>",
										  	$secAction);
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

  	function get_AllDataITM() // GOOD
	{
		$PRJCODE		= $_GET['id'];
		
		// START : FOR SERVER-SIDE
			$order 	= $this->input->get("order");

			$col 	= 0;
			$dir 	= "desc";
			if(!empty($order)) {
				foreach($order as $o) {
					$col 	= $o['column'];
					$dir	= $o['dir'];
				}
			}
			
			$dir 	= 'desc';
			if($dir != "asc" && $dir != "desc") 
			{
            	$dir = "asc";
        	}
			
			$columns_valid 	= array("ITMH_DATE");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_budget->get_AllDataITMC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_budget->get_AllDataITML($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
                $ITMH_CODE 		= $dataI['ITMH_CODE'];
                $ITMH_PRJCODE 	= $dataI['ITMH_PRJCODE'];
                $ITMH_DATE 		= $dataI['ITMH_DATE'];
                $ITMH_DESC	 	= $dataI['ITMH_DESC'];
                $ITMH_FN	 	= $dataI['ITMH_FN'];
                $ITMH_STAT	 	= $dataI['ITMH_STAT'];
                $ITMH_USER	 	= $dataI['ITMH_USER'];
                
                if($ITMH_STAT == 0)
                {
                    $ITMH_STATD = 'fake';
                    $STATCOL	= 'danger';
                    $disabled1	= 1;	// Icon Process
                    $disabled2	= 1;	// Icon Flag
                }
                elseif($ITMH_STAT == 1)
                {
                    $ITMH_STATD = 'New';
                    $STATCOL	= 'warning';
                    $disabled1	= 0;	// Icon Process
                    $disabled2	= 1;	// Icon Flag
                }
                elseif($ITMH_STAT == 2)
                {
                    $ITMH_STATD = 'success';
                    $STATCOL	= 'success';
                    $disabled1	= 1;	// Icon Process
                    $disabled2	= 0;	// Icon Flag
                }
                elseif($ITMH_STAT == 3)
                {
                    $ITMH_STATD = 'changed';
                    $STATCOL	= 'primary';
                    $disabled1	= 1;	// Icon Process
                    $disabled2	= 1;	// Icon Flag
                }

                $disabled1d		= "";
                $disabled2d		= "";
                if($disabled1 == 1)
                	$disabled1d	= "disabled='disabled'";
                if($disabled2 == 1)
                	$disabled2d	= "disabled='disabled'";

                $FileName 	= $ITMH_FN;
                $secVWlURL	= site_url('c_gl/c_ch1h0fbeart/view_coaup/?id='.urldecode($FileName));
                $urlDwl		= base_url('index.php/c_comprof/c_bUd93tL15t/dwlFileItm/?id='.urldecode($FileName));

				$secAction	= 	"<input type='hidden' name='secVWlURL_".$noU."' id='secVWlURL_".$noU."' value='".$secVWlURL."'>
							   		<input type='hidden' name='ITMH_CODE".$noU."' id='ITMH_CODE".$noU."' value='".$ITMH_CODE."'>
								   	<label style='white-space:nowrap'>
								   	<a href='javascript:void(null);' onClick='procIMP(".$noU.");' data-skin='skin-green' class='btn btn-warning btn-xs' title='Process' ".$disabled1d.">
                                        <i class='glyphicon glyphicon-refresh'></i>
                                    </a>
								   	<a href='javascript:void(null);' data-skin='skin-green' class='btn btn-success btn-xs' title='Processed'".$disabled1d.">
                                        <i class='glyphicon glyphicon-flag'></i>
                                    </a>
                                    <a href='".$urlDwl."' target='_self' class='btn btn-primary btn-xs' title='Process'>
                                        <i class='glyphicon glyphicon-download-alt'></i>
                                    </a>
                                    <a href='javascript:void(null);' onClick='viewItm(".$noU.");' data-skin='skin-green' class='btn btn-info btn-xs' title='View Document' style='display:none'>
                                        <i class='fa fa-eye'></i>
                                    </a>
									</label>";

				$output['data'][] 	= array("<label style='white-space:nowrap'>".$ITMH_DATE."</label>",
										  	$ITMH_DESC,
										  	"<span class='label label-".$STATCOL."' style='font-size:12px'>".$ITMH_STATD."</span>",
										  	$secAction);
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

	function dwlFileItm()
	{
		$this->load->helper(array('url','download'));
		$fileName	= $_GET['id'];
		force_download('application/xlsxfile/import_item/period/'.$fileName,NULL);
	}

	function dwlFilePRJDOC()
	{
		$this->load->helper(array('url','download'));
		$fileName	= $_GET['id'];
		force_download('application/xlsxfile/import_buddoc/'.$fileName,NULL);
	}

	function dwlFileBoQ()
	{
		$this->load->helper(array('url','download'));
		$fileName	= $_GET['id'];
		force_download('application/xlsxfile/import_boq/period/'.$fileName,NULL);
	}

  	function get_AllDataPRJDOC() // GOOD
	{
		$PRJCODE		= $_GET['id'];
		
		// START : FOR SERVER-SIDE
			$order 	= $this->input->get("order");

			$col 	= 0;
			$dir 	= "desc";
			if(!empty($order)) {
				foreach($order as $o) {
					$col 	= $o['column'];
					$dir	= $o['dir'];
				}
			}
			
			$dir 	= 'desc';
			if($dir != "asc" && $dir != "desc") 
			{
            	$dir = "asc";
        	}
			
			$columns_valid 	= array("PRJ_FDATE", "PRJ_FDESC");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_budget->get_AllDataPRJDOC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_budget->get_AllDataPRJDOCL($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
                $PRJCODE 		= $dataI['PRJCODE'];
                $PRJ_FDESC 		= $dataI['PRJ_FDESC'];
                $PRJ_FNAME 		= $dataI['PRJ_FNAME'];
                $PRJ_FDATE	 	= $dataI['PRJ_FDATE'];

                $FileName 		= $PRJ_FNAME;
                $urlDwl			= base_url('index.php/c_comprof/c_bUd93tL15t/dwlFilePRJDOC/?id='.urldecode($FileName));

				$secAction		= 	"<label style='white-space:nowrap'>
									<a href='".$urlDwl."' target='_self' class='btn btn-primary btn-xs' title='Process'>
                                        <i class='glyphicon glyphicon-download-alt'></i>
                                    </a>
									</label>";

				$output['data'][] 	= array($PRJ_FDATE,
										  	"<div style='white-space:nowrap'>$PRJ_FDESC</div>",
										  	$secAction);
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
}