<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 9 Februari 2017
 * File Name	= hrdocument.php
 * Location		= -
*/

class Hrdocument_perizinan  extends CI_Controller
{
	function __construct() // GOOD
	{
		parent::__construct();

		$this->load->model('m_project/m_hrdocument_perizinan/m_hrdocument', '', TRUE);
		$this->load->model('m_updash/m_updash', '', TRUE);

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
	
 	public function index() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_project/hrdocument_perizinan/get1nd3x/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}

	function get1nd3x()
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);

		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);

			$LangID 	= $this->session->userdata['LangID'];
			// GET MENU DESC
				$mnCode				= 'MN330';
				$data["MenuCode"] 	= 'MN330';
				$data["MenuApp"] 	= 'MN330';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_project/hrdocument_perizinan/get_last_ten_GroupType/?id=";

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

	function get_last_ten_GroupType()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		if ($this->session->userdata('login') == TRUE)
		{
			$EmpID 		= $this->session->userdata('Emp_ID');

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
					$mxLS		= $EXP_COLLD[2];
					$end		= $EXP_COLLD[3];
					$start		= 0;
				}
				else
				{
					$key		= '';
					$PRJCODE	= $EXP_COLLD1;
					$start		= 0;
					$end		= 50;
				}
				$data["url_search"] = site_url('c_project/hrdocument_perizinan/f4n7_5rcH/?id='.$this->url_encryption_helper->encode_url($PRJCODE));

				//$num_rows 			= $this->m_project_amd->count_all_amd($PRJCODE, $key);
				//$data["cData"] 		= $num_rows;
				//$data['vData']		= $this->m_project_amd->get_all_amd($PRJCODE, $start, $end, $key)->result();
			// -------------------- END : SEARCHING METHOD --------------------
			
			$LangID 	= $this->session->userdata['LangID'];
			// GET MENU DESC
				$mnCode				= 'MN330';
				$data["MenuCode"] 	= 'MN330';
				$data["MenuApp"] 	= 'MN330';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$data['title'] 		= $appName;

			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h2_title"] 	= "HR Document";
				$data['h3_title']	= "Document Group List";
			}
			else
			{
				$data["h2_title"] 	= "HR Document";
				$data['h3_title']	= "Document Group List";
			}
			$data['PRJCODE']	= $PRJCODE;
			$data["MenuCode"] 	= 'MN330';
			$data['backURL'] 	= site_url('c_project/hrdocument_perizinan/?id='.$this->url_encryption_helper->encode_url($PRJCODE));

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN330';
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

			$this->load->view('v_project/v_hrdocument_perizinan/hrdocument_type', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function get_last_ten_projList($offset=0) // HOLD
	{
		$this->load->model('m_project/m_hrdocument_perizinan/m_hrdocument', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName			= $_GET['id'];
			$appName			= $this->url_encryption_helper->decode_url($idAppName);
			$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
			
			$data['title'] 				= $appName;
			$data['h2_title'] 			= 'Project List';
			$data['h3_title'] 			= 'HR Document';
			$data['main_view'] 			= 'v_project/v_project_mc/project_planning';
			$data['secAddURL'] 			= site_url('c_project/hrdocument_perizinan/add/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['showIndex'] 			= site_url('c_project/hrdocument_perizinan/index/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['srch_url'] 			= site_url('c_project/hrdocument_perizinan/get_last_ten_docpattern_src/?id='.$this->url_encryption_helper->encode_url($appName));			
			$data['moffset'] 			= $offset;
			$data['perpage'] 			= 20;
			$data['theoffset']			= 0;
			
			$num_rows					= $this->m_hrdocument->count_all_num_rows($DefEmp_ID);
			$data["recordcount"] 		= $num_rows;
			$config 					= array();
			
			$config["total_rows"] 		= $num_rows;
			$config["per_page"] 		= 20;
			$config["uri_segment"] 		= 3;
			$config['cur_page'] 		= $offset;
			$config['base_url'] 		= site_url('c_project/hrdocument_perizinan/get_last_ten_projList');				
			$config['full_tag_open'] 	= '<ul class="tsc_pagination tsc_paginationA tsc_paginationA01">';
			$config['full_tag_close'] 	= '</ul>';
			$config['prev_link'] 		= '&lt;';
			$config['prev_tag_open'] 	= '<li>';
			$config['prev_tag_close'] 	= '</li>';
			$config['next_link'] 		= '&gt;';
			$config['next_tag_open'] 	= '<li>';
			$config['next_tag_close'] 	= '</li>';
			$config['cur_tag_open'] 	= '<li class="current"><a href="#">';
			$config['cur_tag_close'] 	= '</a></li>';
			$config['num_tag_open'] 	= '<li>';
			$config['num_tag_close'] 	= '</li>';			
			$config['first_tag_open'] 	= '<li>';
			$config['first_tag_close'] 	= '</li>';
			$config['last_tag_open'] 	= '<li>';
			$config['last_tag_close'] 	= '</li>';			
			$config['first_link'] 		= '&lt;&lt;';
			$config['last_link'] 		= '&gt;&gt;';
	 
			$this->pagination->initialize($config);
	 
			$data['viewproject'] = $this->m_hrdocument->get_last_ten_project($config["per_page"], $offset, $DefEmp_ID)->result();
			$data["pagination"] = $this->pagination->create_links();
			
			$this->load->view('v_project/v_hrdocument_perizinan/project_planning', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function hr_documentlistx($offset=0) // HOLD
	{
		$this->load->model('m_project/m_hrdocument_perizinan/m_hrdocument', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 					= $this->session->userdata['Emp_ID'];
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$sqlPRJNM		= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
			$resPRJNM 		= $this->db->query($sqlPRJNM)->result();
			foreach($resPRJNM as $rowPRJNM) :
				$PRJNAME 	= $rowPRJNM->PRJNAME;
			endforeach;
			$data["PRJNAME"]= $PRJNAME;
			
			$offset			= 0;
			$selGROUPDOC	= 'D0240';
			
			$sqlAC 			= "tbl_document WHERE doc_parent = '$selGROUPDOC' AND isHRD = 1";
			$ressqlAC		= $this->db->count_all($sqlAC);
			$sqlA 			= "SELECT doc_code, doc_name FROM tbl_document WHERE doc_parent = '$selGROUPDOC' AND isHRD = 1";
			$resultA 		= $this->db->query($sqlA)->result();
			foreach($resultA as $rowA) :
				$doc_codeA 	= $rowA->doc_code;
			endforeach;
			$selCLASSDOC 	= $doc_codeA;
			
			$sqlBC 			= "tbl_document WHERE doc_parent = '$selCLASSDOC' AND isHRD = 1";
			$ressqlBC		= $this->db->count_all($sqlBC);
			$sqlB 			= "SELECT doc_code, doc_name FROM tbl_document WHERE doc_parent = '$selCLASSDOC' AND isHRD = 1";
			$resultB 		= $this->db->query($sqlB)->result();
			foreach($resultB as $rowB) :
				$doc_codeB	= $rowB->doc_code;
			endforeach;
			$selTYPEDOC 	= $doc_codeB;
			$data['txtSearch']			= '';
				
			$data["selGROUPDOC1"] 		= $selGROUPDOC;
			$data["selCLASSDOC1"] 		= $selCLASSDOC;
			$data["selTYPEDOC1"] 		= $selTYPEDOC;
			
			// Secure URL
			$data['srch_url'] 			= site_url('c_project/project_owner/get_last_ten_projHRDOC_src/?id='.$this->url_encryption_helper->encode_url($appName));		
			$data['title'] 				= $appName;
			$data['h2_title'] 			= 'Document List';
			$data['h3_title'] 			= 'HR Document';
			$data['main_view'] 			= 'v_project/v_hrdocument_perizinan/hrdocumentlist';	
					
			$data['link'] 				= array('link_back' => anchor('c_project/hrdocument_perizinan/','<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Back" />', array('style' => 'text-decoration: none;')));
			$data['PRJCODE'] 			= $PRJCODE;
			//$data['moffset'] 			= $offset;
			
			$selSearchproj_Code 		= $PRJCODE;
			$selSearchCat				= '';
			$selSearchType      		= '';
			$txtSearch        			= '';
			
			$dataSessSrc = array(
				'selSearchproj_Code' 	=> $selSearchproj_Code,
				'selSearchCat' 			=> $selSearchCat,
				'selSearchType' 		=> $selSearchType,
				'txtSearch' 			=> $txtSearch);
			$this->session->set_userdata('dtSessSrc1', $dataSessSrc);
			$this->session->set_userdata('dtSessSrc2', $dataSessSrc);
			
			$num_rows 					= $this->m_hrdocument->count_all_num_rowsProjDOC($PRJCODE, $selTYPEDOC, $txtSearch);
			
			$data["recordcount"] 		= $num_rows;
			
			$data['CATTYPE'] 			= '';
			
			$data["recordcount"] 		= $num_rows;			
			$config 					= array();
			$config['base_url'] 		= site_url('c_project/hrdocument_perizinan/get_last_ten_projHRDOC1');
			$config["total_rows"] 		= $num_rows;			
			$config["per_page"] 		= 20;
			$config["uri_segment"] 		= 4;				
			$config['full_tag_open'] 	= '<ul class="tsc_pagination tsc_paginationA tsc_paginationA01">';
			$config['full_tag_close'] 	= '</ul>';
			$config['prev_link'] 		= '&lt;';
			$config['prev_tag_open'] 	= '<li>';
			$config['prev_tag_close'] 	= '</li>';
			$config['next_link'] 		= '&gt;';
			$config['next_tag_open'] 	= '<li>';
			$config['next_tag_close']	= '</li>';
			$config['cur_tag_open'] 	= '<li class="current"><a href="#">';
			$config['cur_tag_close'] 	= '</a></li>';
			$config['num_tag_open'] 	= '<li>';
			$config['num_tag_close'] 	= '</li>';			
			$config['first_tag_open'] 	= '<li>';
			$config['first_tag_close'] 	= '</li>';
			$config['last_tag_open'] 	= '<li>';
			$config['last_tag_close'] 	= '</li>';			
			$config['first_link'] 		= '&lt;&lt;';
			$config['last_link']		= '&gt;&gt;';
			
			$this->pagination->initialize($config);
	 
			$data['viewdocument'] = $this->m_hrdocument->get_last_ten_projHRDOC($PRJCODE, $config["per_page"], $offset, $selTYPEDOC, $txtSearch)->result();
			
			$data["pagination"] = $this->pagination->create_links();
			
			$myProjectSess = array(
					'myProjSession' => $selSearchproj_Code);
			$this->session->set_userdata('dtProjSess', $myProjectSess);
			
			$this->load->view('v_project/v_hrdocument_perizinan/hrdocumentlist', $data);
		}
		else
		{
			redirect('Auth');
		}
	}

	function get_last_ten_GroupType_220706($offset=0) // OK
	{
		$this->load->model('m_project/m_hrdocument_perizinan/m_hrdocument', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName			= $_GET['id'];
			$appName			= $this->url_encryption_helper->decode_url($idAppName);
			$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
			
			$data['title'] 				= $appName;
			$data['h2_title'] 			= 'Document Group List';
			$data['h3_title'] 			= 'HR Document';
			$data['DefEmp_ID'] 			= $this->session->userdata['Emp_ID'];
			
			$this->load->view('v_project/v_hrdocument_perizinan/hrdocument_type', $data);
		}
		else
		{
			redirect('Auth');
		}
	}

	function get_last_ten_Type($offset=0) // OK
	{
		$this->load->model('m_project/m_hrdocument_perizinan/m_hrdocument', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$doc_code			= $_GET['id'];
			$doc_code			= $this->url_encryption_helper->decode_url($doc_code);
			$sqlA 				= "SELECT doc_code, doc_parent, doc_name FROM tbl_document WHERE doc_code = '$doc_code' AND isHRD = 1";
			$resultA 			= $this->db->query($sqlA)->result();
			foreach($resultA as $rowA) :
				$doc_codeA 		= $rowA->doc_code;
				$doc_parentA	= $rowA->doc_parent;
				$doc_nameA 		= $rowA->doc_name;
			endforeach;
			$data["doc_parent"]	= $doc_parentA;	
			$data["doc_code"]	= $doc_code;			
			$data["doc_name"]	= $doc_nameA;
			
			$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
			
			$data['title'] 			= $appName;
			$data['h2_title'] 		= 'Document Type List';
			$data['h3_title'] 		= 'HR Document';
			$data['DefEmp_ID'] 		= $this->session->userdata['Emp_ID'];
			$data['parentCode'] 	= $doc_code;
			$cancel_url				= site_url('c_project/hrdocument_perizinan/get_last_ten_GroupType/?id='.$this->url_encryption_helper->encode_url($appName));
			//$data['link'] 			= array('link_back' => anchor("$cancel_url",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Back" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 		= $cancel_url;
			
			$this->load->view('v_project/v_hrdocument_perizinan/hrdocument', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function hr_documentlist() // OK
	{
		$this->load->model('m_project/m_hrdocument_perizinan/m_hrdocument', '', TRUE);
		
		$doc_code	= $_GET['id'];
		$doc_code	= $this->url_encryption_helper->decode_url($doc_code);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 					= $this->session->userdata['Emp_ID'];
		
		if ($this->session->userdata('login') == TRUE)
		{
			$sqlA 			= "SELECT doc_code, doc_parent, doc_name FROM tbl_document WHERE doc_code = '$doc_code' AND isHRD = 1";
			$resultA 		= $this->db->query($sqlA)->result();
			foreach($resultA as $rowA) :
				$doc_codeA 		= $rowA->doc_code;
				$doc_parentA	= $rowA->doc_parent;
				$doc_nameA 		= $rowA->doc_name;
			endforeach;
			$data["doc_parent"]	= $doc_parentA;	
			$data["doc_code"]	= $doc_code;			
			$data["doc_name"]	= $doc_nameA;
			
			$cancel_url			= site_url('c_project/hrdocument_perizinan/get_last_ten_Type/?id='.$this->url_encryption_helper->encode_url($doc_parentA));
					
			$data['title'] 		= $appName;
			$data['h2_title'] 	= 'Document List';
			$data['h3_title'] 	= $doc_nameA;
			$data['main_view'] 	= 'v_project/v_hrdocument_perizinan/hrdocumentlist';
			$data['link'] 			= array('link_back' => anchor("$cancel_url",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Back" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 		= $cancel_url;
			$num_rows 				= $this->m_hrdocument->count_all_num_DokHR($doc_code);			
			$data["countDoc"] 		= $num_rows;	 
			$data['viewdocument'] 	= $this->m_hrdocument->get_last_ten_DokHR($doc_code)->result();
			
			$this->load->view('v_project/v_hrdocument_perizinan/hrdocumentlist', $data);
		}
		else
		{
			redirect('Auth');
		}
	}

  	function get_AllData() // GOOD
	{
		$doc_code	= $_GET['id'];

		$LangID 	= $this->session->userdata['LangID'];
		$sqlTransl	= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
		$resTransl	= $this->db->query($sqlTransl)->result();
		foreach($resTransl as $rowTransl) :
			$TranslCode	= $rowTransl->MLANG_CODE;
			$LangTransl	= $rowTransl->LangTransl;

			if($TranslCode == 'Month')$Month = $LangTransl;
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

			$columns_valid 	= array("HRDOCNO",
									"HRDOCCODE",
									"START_DATE",
									"END_DATE",
									"HRDOC_NOTE",
									"OWNER_DESC",
									"HRDOCLOK",
									"",
									"");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_hrdocument->get_AllDataC($doc_code, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_hrdocument->get_AllDataL($doc_code, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$HRDID			= $dataI['ID'];
				$HRDOCNO		= $dataI['HRDOCNO'];
				$HRDOCCODE		= $dataI['HRDOCCODE'];
				$HRDOCTYPE		= $dataI['HRDOCTYPE'];
				if($HRDOCTYPE == 1)
					$HRDOCTYPED	= "Asli";
				else
					$HRDOCTYPED	= "Copy";
				
				$TRXDATE		= $dataI['TRXDATE'];
				$END_DATE		= $dataI['END_DATE'];
				//$REMINDER		= $dataI['REMINDER'];
				$REM_DATE		= $dataI['REM_DATE'];
				
				$tglTRXD		= date('Y-m-d',strtotime($END_DATE));
				if($END_DATE == '')
					$END_DATE	= "-";
				
				$PRJCODE		= $dataI['PRJCODE'];
				$PRJNAME		= 'Not Found';
				if($PRJCODE != '')
				{
					$sql 		= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
					$result 	= $this->db->query($sql)->result();
					foreach($result as $rowPRJ) :
						$PRJNAME = $rowPRJ ->PRJNAME;
					endforeach;
				}
				$OWNER_CODE		= $dataI['OWNER_CODE'];
				$OWNER_DESC		= $dataI['OWNER_DESC'];
				$HRDOCCOST		= 0;
				$HRDOCJNS		= $dataI['HRDOCJNS'];
				if($HRDOCJNS == 1)
				{
					$HRDOCJNS	= "LEMBAR";
				}
				elseif($HRDOCJNS == 2)
				{
					$HRDOCJNS	= "BUKU";
				}
				elseif($HRDOCJNS == 3)
				{
					$HRDOCJNS	= "BUKU TIPIS";
				}
				else
				{
					$HRDOCJNS	= $HRDOCJNS;
				}
				$HRDOCJML		= $dataI['HRDOCJML'];
				$HRDOCLOK		= $dataI['HRDOCLOK'];
				$HRDOC_NAME		= $dataI['HRDOC_NAME'];
				$PM_EMPCODE		= $dataI['PM_EMPCODE'];
				$PM_NAME		= $dataI['PM_NAME'];
				$PM_STATUS		= $dataI['PM_STATUS'];					
				if($PM_EMPCODE != '')
				{
					$sqlEMPD	= "SELECT First_Name, Last_Name FROM tbl_employee WHERE Emp_ID = '$PM_EMPCODE'";
					$resEMPD	= $this->db->query($sqlEMPD)->result();
					foreach($resEMPD as $rowEMPD) :
						$First_Name = $rowEMPD ->First_Name;
						$Last_Name 	= $rowEMPD ->Last_Name;
					endforeach;
					if($PM_STATUS != "")
					{
						$PM_NAME	= ": $First_Name $Last_Name ($PM_STATUS)";
					}
					else
					{
						$PM_NAME	= ": $First_Name $Last_Name";
					}
				}
				
				$HRD_EMPID		= $dataI['HRD_EMPID'];
				$HRD_NAME		= '';
				if($HRD_EMPID != '')
				{
					$sqlEMPD	= "SELECT First_Name, Last_Name FROM tbl_employee WHERE Emp_ID = '$HRD_EMPID'";
					$resEMPD	= $this->db->query($sqlEMPD)->result();
					foreach($resEMPD as $rowEMPD) :
						$First_Name = $rowEMPD ->First_Name;
						$Last_Name 	= $rowEMPD ->Last_Name;
					endforeach;
					if($PM_STATUS != "")
					{
						$HRD_NAME	= ": $First_Name $Last_Name ($PM_STATUS)";
					}
					else
					{
						$HRD_NAME	= ": $First_Name $Last_Name";
					}
				}
				
				$DIR_EMPCODE	= $dataI['DIR_EMPCODE'];
				$DIR_NAME		= $dataI['DIR_NAME'];
				if($DIR_EMPCODE != '')
				{
					$sqlEMPD	= "SELECT First_Name, Last_Name FROM tbl_employee WHERE Emp_ID = '$DIR_EMPCODE'";
					$resEMPD	= $this->db->query($sqlEMPD)->result();
					foreach($resEMPD as $rowEMPD) :
						$First_NameD 	= $rowEMPD ->First_Name;
						$Last_NameD		= $rowEMPD ->Last_Name;
					endforeach;
					$DIR_NAME	= ": $First_NameD $Last_NameD";
				}
				$STATUS_DOK		= $dataI['STATUS_DOK'];
				$BORROW_EMP		= $dataI['BORROW_EMP'];
				$BORROW_NM		= "";
				if($BORROW_EMP != '')
				{
					$sqlEMPD	= "SELECT First_Name, Last_Name FROM tbl_employee WHERE Emp_ID = '$BORROW_EMP'";
					$resEMPD	= $this->db->query($sqlEMPD)->result();
					foreach($resEMPD as $rowEMPD) :
						$First_NameB 	= $rowEMPD ->First_Name;
						$Last_NameB		= $rowEMPD ->Last_Name;
					endforeach;
					$BORROW_NM	= ": $First_NameB $Last_NameB";
				}
				$HRDOC_NOTE		= $dataI['HRDOC_NOTE'];
				if($HRDOC_NOTE == "")
				{
					if($PRJCODE != "KTR")
					{
						$HRDOC_NOTE	= "$PRJCODE ($PRJNAME) : $HRDOCJML $HRDOCJNS $PM_NAME $DIR_NAME $BORROW_NM";
					}
					else
					{
						$HRDOC_NOTE	= "$HRDOCJML $HRDOCJNS $PM_NAME $DIR_NAME $BORROW_NM";
					}
				}
				else
				{
					$HRDOC_NOTE	= "$PRJCODE : $HRDOC_NOTE : $HRDOCJML $HRDOCJNS $PM_NAME $DIR_NAME $BORROW_NM";
				}

				$due_date 	= date('Y-m-d', strtotime($END_DATE));
				$awal  		= date_create($REM_DATE);
				$akhir 		= date_create($due_date); // waktu sekarang
				$diff  		= date_diff($awal,$akhir);
				
				if($REM_DATE != 0 || $REM_DATE != "")
				{
					$ALERT_DT 	= $diff->m . '&nbsp;'.$Month;
				}
				else
				{
					$ALERT_DT 	= "-";
				}

				$CollID			= "$PRJCODE~$HRDID";
				$secUpd			= site_url('c_project/hrdocument_perizinan/update/?id='.$this->url_encryption_helper->encode_url($HRDID));
				$secUplURL		= site_url('c_project/hrdocument_perizinan/hrdocproject_upload/?id='.$this->url_encryption_helper->encode_url($HRDID));

				//$secDelIcut 	= base_url().'index.php/__l1y/trashDOC/?id=';
				$secDelIcut 	= base_url().'index.php/__l1y/delDOC/?id=';
				$delID 			= "$secDelIcut~$HRDID";

				$FileUpName 	= $HRDOC_NAME;
				if($HRDOC_NAME == '')
				{
					$secAction	= 	"<input type='hidden' name='secUplURL_".$noU."' id='secUplURL_".$noU."' value='".$secUplURL."'>
									<input type='hidden' name='FileUpName".$noU."' id='FileUpName".$noU."' value='".$FileUpName."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='selectPICT(".$noU.")' title='Upload Document'>
										<i class='fa fa-upload'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				else
				{
					$secAction	= 	"<input type='hidden' name='secUplURL_".$noU."' id='secUplURL_".$noU."' value='".$secUplURL."'>
									<input type='hidden' name='FileUpName".$noU."' id='FileUpName".$noU."' value='".$FileUpName."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
									<a href='javascript:void(null);' class='btn btn-success btn-xs' onClick='typeOpenNewTab(".$noU.")' title='View Document'>
										<i class='fa fa-eye'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
								
				$output['data'][] 	= array("<div style='white-space:nowrap'>$HRDOCNO</div>",
										  	$HRDOCCODE,
										  	$TRXDATE,
										  	$tglTRXD,
										  	$ALERT_DT,
										  	$HRDOC_NOTE,
										  	$HRDOCLOK,
										  	$HRDOCTYPED,
										  	$secAction);
				$noU		= $noU + 1;
			}
								
			/*$output['data'][] 	= array("A",
										"A",
										"A",
										"A",
										"A",
										"A",
										"A",
										"A",
										"A");*/

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function add() // OK
	{
		$this->load->model('m_project/m_hrdocument_perizinan/m_hrdocument', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 					= $this->session->userdata['Emp_ID'];
		
		if ($this->session->userdata('login') == TRUE)
		{
			$doc_code	= $_GET['id'];
			$DOCCODE	= $this->url_encryption_helper->decode_url($doc_code);
			
			$getDOCType 				= $this->m_hrdocument->get_DOC_Type($DOCCODE)->row();
			$doc_name					= $getDOCType->doc_name;
			
			$data['title'] 				= $appName;
			$data['DOCCODE'] 			= $DOCCODE;
			$data['doc_name'] 			= $doc_name;
			$cancel_url					= site_url('c_project/hrdocument_perizinan/hr_documentlist/?id='.$this->url_encryption_helper->encode_url($DOCCODE));
			$data['backURL'] 			= $cancel_url;
			
			$docPatternPosition 		= 'Especially';
			$data['title'] 				= $appName;
			$data['task'] 				= 'add';
			$data['h2_title']			= 'Add HR Document';
			$data['main_view'] 			= 'v_project/v_hrdocument_perizinan/hrdocument_form';
			$data['form_action']		= site_url('c_project/hrdocument_perizinan/do_upload');
			$data['link'] 				= array('link_back' => anchor("$cancel_url",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Back" />', array('style' => 'text-decoration: none;')));
			
			$MenuCode 					= 'MN234';
			$data['viewDocPattern'] 	= $this->m_hrdocument->getDataDocPat($MenuCode)->result();
			
			$this->load->view('v_project/v_hrdocument_perizinan/hrdocument_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function hrdocproject_upload() // OK
	{
		$this->load->model('m_project/m_hrdocument_perizinan/m_hrdocument', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 					= $this->session->userdata['Emp_ID'];
		
		if ($this->session->userdata('login') == TRUE)
		{
			$HRDOCNO	= $_GET['id'];
			$HRDOCNO	= $this->url_encryption_helper->decode_url($HRDOCNO);
		
			$getprojectSPK 				= $this->m_hrdocument->get_DOC_by_number($HRDOCNO)->row();
			$PRJCODE					= $getprojectSPK->PRJCODE;
			
			$cancel_url 				= site_url('c_project/sd_hrdocument/get_last_ten_projHRDOC/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			$data['PRJCODE'] 			= $PRJCODE;	
			$data['proj_Code'] 			= $PRJCODE;
			$data['title'] 				= $appName;
			$data['task'] 				= 'edit';
			$data['h2_title']			= 'Document Upload';
			$data['h9_title']			= $HRDOCNO;
			$data['main_view'] 			= 'v_project/v_hrdocument_perizinan/hrdocument_form';
			$data['form_action']		= site_url('c_project/sd_hrdocument/do_upload_update');
			$data['link'] 				= array('link_back' => anchor("$cancel_url",'<input type="button" name="btnCancel" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			$data['default']['HRDOCNO']			= $getprojectSPK->HRDOCNO;
			$data['default']['HRDOCCODE']		= $getprojectSPK->HRDOCCODE;
			$data['default']['HRDOCTYPE']		= $getprojectSPK->HRDOCTYPE;
			$data['default']['TRXDATE']			= $getprojectSPK->TRXDATE;
			$data['default']['PRJCODE']			= $getprojectSPK->PRJCODE;
			//$data['default']['HRDOCCOST']		= $getprojectSPK->HRDOCCOST;
			$data['default']['HRDOCCOST']		= 0;
			$data['default']['HRDOCJNS']		= $getprojectSPK->HRDOCTYPE;
			$data['default']['HRDOCJML']		= $getprojectSPK->HRDOCJML;
			$data['default']['HRDOCLBR']		= $getprojectSPK->HRDOCLBR;
			$data['default']['HRDOCLOK']		= $getprojectSPK->HRDOCLOK;
			$data['default']['Patt_Date'] 		= $getprojectSPK->Patt_Date;
			$data['default']['Patt_Month'] 		= $getprojectSPK->Patt_Month;
			$data['default']['Patt_Year'] 		= $getprojectSPK->Patt_Year;
			$data['default']['Patt_Number'] 	= $getprojectSPK->Patt_Number;
			
			$this->load->view('v_project/v_hrdocument_perizinan/hrdocument_upload', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function do_upload() // OK
	{
		$this->load->model('m_project/m_hrdocument_perizinan/m_hrdocument', '', TRUE);
		
		
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$this->db->trans_begin();
		
		// Pembuatan ID Penyimpanan Per Tanggal	
		date_default_timezone_set("Asia/Jakarta");
		$HRDOC_CREATED		= date('Y-m-d H:i:s');
		
		$istask				= $this->input->post('istask');
		$HRDOCNO			= $this->input->post('HRDOCNO');
		$DOCCODE			= $this->input->post('DOCCODE');
		$Patt_Number		= $this->input->post('Patt_Number');			
		$HRDOCCODE			= $this->input->post('HRDOCCODE');
		$TRXDATE			= date('Y-m-d',strtotime($this->input->post('TRXDATE')));
		$SHOW_SE_DATE		= $this->input->post('SHOW_SE_DATE');
		$START_DATE			= $this->input->post('START_DATE');
		$END_DATE			= $this->input->post('END_DATE');
		$HRDOCTYPE			= $this->input->post('HRDOCTYPE');	// ASLI / COPY
		if($HRDOCTYPE == 1)
		{
			$HRDOCJNS		= "ASLI";
		}
		else
		{
			$HRDOCJNS		= "COPY";
		}
		$HRDOCJML			= $this->input->post('HRDOCJML');	// JML HAL
		$HRDOCLBR			= $this->input->post('HRDOCLBR');	// LEMBAR / BUKU / BUKU TIPIS
		$HRDOCLOK			= $this->input->post('HRDOCLOK');
		$PRJCODE			= $this->input->post('PRJCODE');
		$OWNER_CODE			= $this->input->post('OWNER_CODE');
		$OWNER_DESC			= $this->input->post('OWNER_DESC');
		$OWNER_ADD			= $this->input->post('OWNER_ADD');
		$HRDOCCOST			= $this->input->post('HRDOCCOST');
		$PM_EMPCODE			= $this->input->post('PM_EMPCODE');
		$PM_NAME			= $this->input->post('PM_NAME');
		$PM_STATUS			= $this->input->post('PM_STATUS');
		$DIR_EMPCODE		= $this->input->post('DIR_EMPCODE');
		$DIR_NAME			= $this->input->post('DIR_NAME');
		$HRD_EMPID 			= $this->input->post('HRD_EMPID');
		$HRDOCSTAT			= $this->input->post('HRDOCSTAT');
		if($HRDOCSTAT == 1)
		{
			$BORROW_EMP		= '';
		}
		else
		{
			$BORROW_EMP		= $this->input->post('BORROW_EMP');
		}
		$HRDOC_NOTE			= $this->input->post('HRDOC_NOTE');
		$TRXUSER			= $DefEmp_ID;
		$Patt_Date			= date('d',strtotime($HRDOC_CREATED));
		$Patt_Month			= date('m',strtotime($HRDOC_CREATED));
		$Patt_Year			= date('Y',strtotime($HRDOC_CREATED));
		
		$file_name 			= "";
		if(isset($_FILES['userfile']))
		{
			$file 			= $_FILES['userfile'];
			$file_name 		= $file['name'];
			
			$filename 		= $_FILES["userfile"]["name"];
			$source 		= $_FILES["userfile"]["tmp_name"];
			$type 			= $_FILES["userfile"]["type"];
			
			$name 			= explode(".", $filename);
			$fileExt		= $name[1];
			
			$target_path 	= "assets/AdminLTE-2.0.5/doc_center/uploads/".$filename;  // change this to the correct site path
				
			$myPath 		= "assets/AdminLTE-2.0.5/doc_center/uploads/$filename";
			
			if (file_exists($myPath) == true)
			{
				unlink($myPath);
			}
			
			move_uploaded_file($source, $target_path);
		}
		
		if($istask == 'add')
		{
			$dataINSDOC = array('HRDOCNO' => $HRDOCNO,		// OK
						'HRDOCCODE'		=> $HRDOCCODE,		// OK
						'DOCCODE'		=> $DOCCODE,		// OK
						'HRDOCTYPE'		=> $HRDOCTYPE,		// OK
						'TRXDATE'		=> $TRXDATE,		// OK
						'PRJCODE'		=> $PRJCODE,		// OK
						'OWNER_CODE'	=> $OWNER_CODE,		// OK
						'OWNER_DESC'	=> $OWNER_DESC,		// OK
						'OWNER_ADD'		=> $OWNER_ADD,		// OK
						'HRDOCCOST'		=> $HRDOCCOST,		// OK
						'HRDOCJNS'		=> $HRDOCJNS,		// OK
						'HRDOCJML'		=> $HRDOCJML,		// OK
						'HRDOCLBR'		=> $HRDOCLBR,		// OK
						'HRDOCLOK'		=> $HRDOCLOK,		// OK
						'TRXUSER'		=> $TRXUSER,		// OK
						'START_DATE'	=> $START_DATE,		// OK
						'END_DATE'		=> $END_DATE,		// OK
						'HRDOCSTAT'		=> $HRDOCSTAT,		// OK
						'HRDOC_CREATED'	=> $HRDOC_CREATED,	// OK
						'HRDOC_NAME'	=> $file_name,		// OK
						'PM_EMPCODE'	=> $PM_EMPCODE,		// OK
						'PM_NAME'		=> $PM_NAME,		// OK
						'PM_STATUS'		=> $PM_STATUS,		// OK
						'DIR_EMPCODE'	=> $DIR_EMPCODE,	// OK
						'DIR_NAME'		=> $DIR_NAME,		// OK
						'PEMILIK_MODAL'	=> $OWNER_DESC,		// OK
						'BORROW_EMP'	=> $BORROW_EMP,		// OK
						'HRDOC_NOTE'	=> $HRDOC_NOTE,		// OK
						'Patt_Date'		=> $Patt_Date,		// OK
						'Patt_Month'	=> $Patt_Month,		// OK
						'Patt_Year'		=> $Patt_Year,		// OK
						'HRD_EMPID'		=> $HRD_EMPID,
						'Patt_Number'	=> $this->input->post('Patt_Number'));
			$this->m_hrdocument->add($dataINSDOC);
		}
		else
		{
			if($file_name != '')
			{
				$dataUPDDOC = array('HRDOCNO' => $HRDOCNO,		// OK
							'HRDOCCODE'		=> $HRDOCCODE,		// OK
							'DOCCODE'		=> $DOCCODE,		// OK
							'HRDOCTYPE'		=> $HRDOCTYPE,		// OK
							'TRXDATE'		=> $TRXDATE,		// OK
							'PRJCODE'		=> $PRJCODE,		// OK
							'OWNER_CODE'	=> $OWNER_CODE,		// OK
							'OWNER_DESC'	=> $OWNER_DESC,		// OK
							'OWNER_ADD'		=> $OWNER_ADD,		// OK
							'HRDOCCOST'		=> $HRDOCCOST,		// OK
							'HRDOCJNS'		=> $HRDOCJNS,		// OK
							'HRDOCJML'		=> $HRDOCJML,		// OK
							'HRDOCLBR'		=> $HRDOCLBR,		// OK
							'HRDOCLOK'		=> $HRDOCLOK,		// OK
							'TRXUSER'		=> $TRXUSER,		// OK
							'START_DATE'	=> $START_DATE,		// OK
							'END_DATE'		=> $END_DATE,		// OK
							'HRDOCSTAT'		=> $HRDOCSTAT,		// OK
							'HRDOC_CREATED'	=> $HRDOC_CREATED,	// OK
							'HRDOC_NAME'	=> $file_name,		// OK
							'PM_EMPCODE'	=> $PM_EMPCODE,		// OK
							'PM_NAME'		=> $PM_NAME,		// OK
							'PM_STATUS'		=> $PM_STATUS,		// OK
							'DIR_EMPCODE'	=> $DIR_EMPCODE,	// OK
							'DIR_NAME'		=> $DIR_NAME,		// OK
							'PEMILIK_MODAL'	=> $OWNER_DESC,		// OK
							'BORROW_EMP'	=> $BORROW_EMP,		// OK
							'HRD_EMPID'		=> $HRD_EMPID,
							'HRDOC_NOTE'	=> $HRDOC_NOTE);
			}
			else
			{
				$dataUPDDOC = array('HRDOCNO' => $HRDOCNO,		// OK
							'HRDOCCODE'		=> $HRDOCCODE,		// OK
							'DOCCODE'		=> $DOCCODE,		// OK
							'HRDOCTYPE'		=> $HRDOCTYPE,		// OK
							'TRXDATE'		=> $TRXDATE,		// OK
							'PRJCODE'		=> $PRJCODE,		// OK
							'OWNER_CODE'	=> $OWNER_CODE,		// OK
							'OWNER_DESC'	=> $OWNER_DESC,		// OK
							'OWNER_ADD'		=> $OWNER_ADD,		// OK
							'HRDOCCOST'		=> $HRDOCCOST,		// OK
							'HRDOCJNS'		=> $HRDOCJNS,		// OK
							'HRDOCJML'		=> $HRDOCJML,		// OK
							'HRDOCLBR'		=> $HRDOCLBR,		// OK
							'HRDOCLOK'		=> $HRDOCLOK,		// OK
							'TRXUSER'		=> $TRXUSER,		// OK
							'START_DATE'	=> $START_DATE,		// OK
							'END_DATE'		=> $END_DATE,		// OK
							'HRDOCSTAT'		=> $HRDOCSTAT,		// OK
							'HRDOC_CREATED'	=> $HRDOC_CREATED,	// OK
							//'HRDOC_NAME'	=> $file_name,		// OK
							'PM_EMPCODE'	=> $PM_EMPCODE,		// OK
							'PM_NAME'		=> $PM_NAME,		// OK
							'PM_STATUS'		=> $PM_STATUS,		// OK
							'DIR_EMPCODE'	=> $DIR_EMPCODE,	// OK
							'DIR_NAME'		=> $DIR_NAME,		// OK
							'PEMILIK_MODAL'	=> $OWNER_DESC,		// OK
							'BORROW_EMP'	=> $BORROW_EMP,		// OK
							'HRD_EMPID'		=> $HRD_EMPID,
							'HRDOC_NOTE'	=> $HRDOC_NOTE);
			}				
			$this->m_hrdocument->update($HRDOCNO, $dataUPDDOC);
		}
		
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
		
		$url			= site_url('c_project/hrdocument_perizinan/hr_documentlist/?id='.$this->url_encryption_helper->encode_url($DOCCODE));
		redirect($url);
	}
	
	function update() // OK
	{
		$this->load->model('m_project/m_hrdocument_perizinan/m_hrdocument', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 					= $this->session->userdata['Emp_ID'];
		
		if ($this->session->userdata('login') == TRUE)
		{
			$HRDOCNO	= $_GET['id'];
			$HRDOCNO	= $this->url_encryption_helper->decode_url($HRDOCNO);
			
			$getprojectDOC 				= $this->m_hrdocument->get_DOC_by_number($HRDOCNO)->row();
			$PRJCODE					= $getprojectDOC->PRJCODE;
			$DOCCODE					= $getprojectDOC->DOCCODE;
			
			$getDOCType 				= $this->m_hrdocument->get_DOC_Type($DOCCODE)->row();
			$doc_name					= $getDOCType->doc_name;
			
			$cancel_url 				= site_url('c_project/hrdocument_perizinan/hr_documentlist/?id='.$this->url_encryption_helper->encode_url($DOCCODE));
			
			$data['doc_name'] 			= $doc_name;
			$docPatternPosition 		= 'Especially';	
			$data['title'] 				= $appName;
			$data['task'] 				= 'edit';
			$data['h2_title']			= 'Document Update';
			$data['main_view'] 			= 'v_project/v_hrdocument_perizinan/hrdocument_form';
			$data['form_action']		= site_url('c_project/hrdocument_perizinan/do_upload');
			$data['link'] 				= array('link_back' => anchor("$cancel_url",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Back" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 			= $cancel_url;
			
			$data['recordcountProject']	= $this->m_hrdocument->count_all_num_rowsProject();
			$data['viewProject'] 		= $this->m_hrdocument->viewProject()->result();
			
			$MenuCode 					= 'MN238';
			$data['viewDocPattern'] 	= $this->m_hrdocument->getDataDocPat($MenuCode)->result();
			
			$getprojectSPK 				= $this->m_hrdocument->get_DOC_by_number($HRDOCNO)->row();
			
			$data['default']['HRDOCNO']			= $getprojectSPK->HRDOCNO;
			$data['default']['HRDOCCODE']		= $getprojectSPK->HRDOCCODE;
			$data['default']['DOCCODE']			= $getprojectSPK->DOCCODE;
			$data['default']['HRDOCTYPE']		= $getprojectSPK->HRDOCTYPE;
			$data['default']['TRXDATE']			= $getprojectSPK->TRXDATE;
			$data['default']['PRJCODE']			= $getprojectSPK->PRJCODE;
			$data['default']['OWNER_CODE']		= $getprojectSPK->OWNER_CODE;
			$data['default']['OWNER_DESC']		= $getprojectSPK->OWNER_DESC;
			$data['default']['OWNER_ADD']		= $getprojectSPK->OWNER_ADD;
			$data['default']['HRDOCCOST']		= $getprojectSPK->HRDOCCOST;
			$data['default']['HRDOCJNS']		= $getprojectSPK->HRDOCJNS;
			$data['default']['HRDOCJML']		= $getprojectSPK->HRDOCJML;
			$data['default']['HRDOCLBR']		= $getprojectSPK->HRDOCLBR;
			$data['default']['HRDOCLOK']		= $getprojectSPK->HRDOCLOK;
			$data['default']['TRXUSER']			= $getprojectSPK->TRXUSER;
			$data['default']['START_DATE']		= $getprojectSPK->START_DATE;
			$data['default']['END_DATE']		= $getprojectSPK->END_DATE;
			$data['default']['HRDOCSTAT']		= $getprojectSPK->HRDOCSTAT;
			$data['default']['HRDOC_NAME']		= $getprojectSPK->HRDOC_NAME;
			$data['default']['PM_EMPCODE']		= $getprojectSPK->PM_EMPCODE;
			$data['default']['PM_NAME']			= $getprojectSPK->PM_NAME;
			$data['default']['PM_STATUS']		= $getprojectSPK->PM_STATUS;
			$data['default']['DIR_EMPCODE']		= $getprojectSPK->DIR_EMPCODE;
			$data['default']['DIR_NAME']		= $getprojectSPK->DIR_NAME;
			$data['default']['STATUS_DOK']		= $getprojectSPK->STATUS_DOK;
			$data['default']['BORROW_EMP']		= $getprojectSPK->BORROW_EMP;
			$data['default']['PEMILIK_MODAL']	= $getprojectSPK->PEMILIK_MODAL;
			$data['default']['HRDOC_NOTE']		= $getprojectSPK->HRDOC_NOTE;			
			$data['default']['HRD_EMPID'] 		= $getprojectSPK->HRD_EMPID;
			$data['default']['HRD_CUALIF'] 		= $getprojectSPK->HRD_CUALIF;
			$data['default']['HRD_PUBLISHER'] 	= $getprojectSPK->HRD_PUBLISHER;	
			$data['default']['Patt_Date'] 		= $getprojectSPK->Patt_Date;
			$data['default']['Patt_Month'] 		= $getprojectSPK->Patt_Month;
			$data['default']['Patt_Year'] 		= $getprojectSPK->Patt_Year;
			$data['default']['Patt_Number'] 	= $getprojectSPK->Patt_Number;
			
			$this->load->view('v_project/v_hrdocument_perizinan/hrdocument_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function getallemp()
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_project/m_hrdocument_perizinan/m_hrdocument', '', TRUE);
			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName 	= $therow->app_name;		
			endforeach;
			$DOCCODE		= $_GET['id'];
			$DOCCODE		= $this->url_encryption_helper->decode_url($DOCCODE);
			
			$data['title'] 		= $appName;
			$data['h2_title']	= 'Employee List';
			$data['h3_title']	= 'doc. center';
			$data['DOCCODE']	= $DOCCODE;
			
			$data['countEmp'] 	= $this->m_hrdocument->count_all_emp();
			$data['vwAllEmp'] 	= $this->m_hrdocument->viewAllEmp()->result();
					
			$this->load->view('v_project/v_hrdocument_perizinan/hrdocument_emp', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
}