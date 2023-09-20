<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 15 Maret 2017
 * File Name	= Qhsedocument.php
 * Location		= -
*/

class Qhsedocument extends CI_Controller
{
	function __construct() // GOOD
	{
		parent::__construct();

		$this->load->model('m_project/m_qhsedocument/m_qhsedocument', '', TRUE);
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
		
		$url			= site_url('c_project/qhsedocument/get1nd3x/?id='.$this->url_encryption_helper->encode_url($appName));
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
				$mnCode				= 'MN268';
				$data["MenuCode"] 	= 'MN268';
				$data["MenuApp"] 	= 'MN268';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = "Daftar Proyek";
				else
					$data["mnName"] = "Project List";
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_project/qhsedocument/get_last_ten_GroupType/?id=";

			/*$data["secVIEW"]	= 'v_projectlist/project_list';
			if($this->session->userdata['nSELP'] == 0)
			{*/
				$PRJCODE	= $this->session->userdata['proj_Code'];
				$url		= site_url($data["secURL"].$this->url_encryption_helper->encode_url($PRJCODE));
				redirect($url);
			/*}
			else
			{
				$this->load->view($data["secVIEW"], $data);
			}*/
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

				/*$collDATA		= $_GET['id'];
				$EXP_COLLD1		= $this->url_encryption_helper->decode_url($collDATA);
				$EXP_COLLD		= explode('~', $EXP_COLLD1);
				$C_COLLD1		= count($EXP_COLLD);*/
				$C_COLLD1		= 0;

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
					//$PRJCODE	= $EXP_COLLD1;
					$PRJCODE	= "KTR-22";
					$start		= 0;
					$end		= 50;
				}

				$this->session->set_userdata('dtSessPRJ', $PRJCODE);
				
				$data["url_search"] = site_url('c_project/qhsedocument/f4n7_5rcH/?id='.$this->url_encryption_helper->encode_url($PRJCODE));

				//$num_rows 			= $this->m_project_amd->count_all_amd($PRJCODE, $key);
				//$data["cData"] 		= $num_rows;
				//$data['vData']		= $this->m_project_amd->get_all_amd($PRJCODE, $start, $end, $key)->result();
			// -------------------- END : SEARCHING METHOD --------------------
			
			$LangID 	= $this->session->userdata['LangID'];
			// GET MENU DESC
				$mnCode				= 'MN268';
				$data["MenuCode"] 	= 'MN268';
				$data["MenuApp"] 	= 'MN268';
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
				$data["h2_title"] 	= "Document Group List";
				$data['h3_title']	= "QHSE Document";
			}
			else
			{
				$data["h2_title"] 	= "Document Group List";
				$data['h3_title']	= "QHSE Document";
			}
			$data['PRJCODE']	= $PRJCODE;
			$data['THEPRJCODE'] = $PRJCODE;
			$data["MenuCode"] 	= 'MN268';
			$data['backURL'] 	= site_url('c_project/qhsedocument/?id='.$this->url_encryption_helper->encode_url($PRJCODE));

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN268';
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

			$this->load->view('v_project/v_qhsedocument/qhsedocument_type', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function get_last_ten_projList($offset=0) // OK
	{
		$this->load->model('m_project/m_qhsedocument/m_qhsedocument', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName			= $_GET['id'];
			$appName			= $this->url_encryption_helper->decode_url($idAppName);
			$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
			
			$data['title'] 				= $appName;
			$data['h2_title'] 			= 'Project List';
			$data['h3_title'] 			= 'QHSE Document';
			$data['main_view'] 			= 'v_project/v_project_mc/project_planning';
			$data['secAddURL'] 			= site_url('c_project/qhsedocument/add/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['showIndex'] 			= site_url('c_project/qhsedocument/index/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['srch_url'] 			= site_url('c_project/qhsedocument/get_last_ten_docpattern_src/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['perpage'] 			= 20;
			
			$num_rows					= $this->m_qhsedocument->count_all_num_rows($DefEmp_ID);
			$data["recordcount"] 		= $num_rows;
	 
			$data['viewproject'] = $this->m_qhsedocument->get_last_ten_project($DefEmp_ID)->result();
			
			$this->load->view('v_project/v_qhsedocument/project_planning', $data);
		}
		else
		{
			redirect('Auth');
		}
	}

	function get_last_ten_GroupType_220707($offset=0) // OK
	{
		$this->load->model('m_project/m_qhsedocument/m_qhsedocument', '', TRUE);
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
			
			$data['title'] 				= $appName;
			$data['h2_title'] 			= 'Document Group List';
			$data['h3_title'] 			= 'QHSE Document';
			$data['DefEmp_ID'] 			= $this->session->userdata['Emp_ID'];
			$data['THEPRJCODE'] 		= $PRJCODE;
			
			$this->load->view('v_project/v_qhsedocument/qhsedocument_type', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function qhse_documentlist($offset=0) // OK
	{
		$this->load->model('m_project/m_qhsedocument/m_qhsedocument', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 					= $this->session->userdata['Emp_ID'];
		
		if ($this->session->userdata('login') == TRUE)
		{
			$doc_code	= $_GET['id'];
			$doc_code	= $this->url_encryption_helper->decode_url($doc_code);
			
			$sqlA 			= "SELECT doc_code, doc_parent, doc_name FROM tbl_document WHERE doc_code = '$doc_code'";
			$resultA 		= $this->db->query($sqlA)->result();
			foreach($resultA as $rowA) :
				$doc_codeA 		= $rowA->doc_code;
				$doc_parentA	= $rowA->doc_parent;
				$doc_nameA 		= $rowA->doc_name;
			endforeach;
			$data["doc_parent"]	= $doc_parentA;
			$data["doc_code"]	= $doc_code;			
			$data["doc_name"]	= $doc_nameA;
			
			$THEPRJCODE   		= $this->session->userdata['dtSessPRJ'];
			$cancel_url			= site_url('c_project/qhsedocument/get_last_ten_GroupType/?id='.$this->url_encryption_helper->encode_url($THEPRJCODE));
					
			$data['title'] 		= $appName;
			$data['h2_title'] 	= 'Document List';
			$data['h3_title'] 	= $doc_nameA;
			$data['main_view'] 	= 'v_project/v_qhsedocument/qhsedocumentlist';
			$data['link'] 			= array('link_back' => anchor("$cancel_url",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Back" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 	= site_url('c_project/qhsedocument/get_last_ten_GroupType/?id='.$this->url_encryption_helper->encode_url($doc_code));
			$data["MenuCode"] 		= 'MN268';
			$num_rows 				= $this->m_qhsedocument->count_all_num_DokQHSE($doc_code);			
			$data["countDoc"] 		= $num_rows;	 
			$data['viewdocument'] 	= $this->m_qhsedocument->get_last_ten_DokQHSE($doc_code)->result();
			
			$this->load->view('v_project/v_qhsedocument/qhsedocumentlist', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function add() // OK
	{
		$this->load->model('m_project/m_qhsedocument/m_qhsedocument', '', TRUE);
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
			
			$getDOCType 				= $this->m_qhsedocument->get_DOC_Type($DOCCODE)->row();
			$doc_name					= $getDOCType->doc_name;
			
			$data['title'] 				= $appName;
			$data['DOCCODE'] 			= $DOCCODE;
			$data['doc_name'] 			= $doc_name;
			$cancel_url					= site_url('c_project/qhsedocument/qhse_documentlist/?id='.$this->url_encryption_helper->encode_url($DOCCODE));
			
			$docPatternPosition 		= 'Especially';
			$data['title'] 				= $appName;
			$data['task'] 				= 'add';
			$data['h2_title']			= 'Add Document';
			$data['h3_title']			= 'QHSE Document';
			$data['main_view'] 			= 'v_project/v_qhsedocument/qhsedocument_form';
			$data['form_action']		= site_url('c_project/qhsedocument/do_upload');
			$data['link'] 				= array('link_back' => anchor("$cancel_url",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Back" />', array('style' => 'text-decoration: none;')));
			
			$MenuCode 					= 'MN268';
			$data["MenuCode"] 			= 'MN268';
			$data['viewDocPattern'] 	= $this->m_qhsedocument->getDataDocPat($MenuCode)->result();
			
			$this->load->view('v_project/v_qhsedocument/qhsedocument_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function do_upload() // OK
	{
		$this->load->model('m_project/m_qhsedocument/m_qhsedocument', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 					= $this->session->userdata['Emp_ID'];
		
		if ($this->session->userdata('login') == TRUE)
		{
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
			$SHOW_SE_DATE		= $this->input->post('SHOW_SE_DATE');
			if($SHOW_SE_DATE == '')
				$SHOW_SE_DATE	= 0;
				
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
			
			$file 				= $_FILES['userfile'];
			$file_name 			= $file['name'];
			
			$file1 				= $_FILES['userfile1'];
			$file_name1			= $file1['name'];
			
			if($file_name != '') // template uploaded
			{				
				/*$filename 	= $_FILES["userfile"]["name"];
				$source 	= $_FILES["userfile"]["tmp_name"];
				$type 		= $_FILES["userfile"]["type"];
				echo "Your upload file name : $filename<br>";
				
				$target_path = "assets/AdminLTE-2.0.5/doc_center/uploads/".$filename;  // change this to the correct site path
				if(move_uploaded_file($source, $target_path))
				{
					$message = "Your .pdf file was uploaded.";
					echo $message;
				} 
				else 
				{	
					$message = "There was a problem with the upload. Please try again.";
					echo $message;
					return false;
				}*/
			}
			if($file_name1 != '') // template uploaded
			{				
				$filename1 	= $_FILES["userfile1"]["name"];
				$source1 	= $_FILES["userfile1"]["tmp_name"];
				$type1 		= $_FILES["userfile1"]["type"];
				
				$name 		= explode(".", $filename1);
				$accepted_types1 = array('application/zip', 'application/x-zip-compressed', 'multipart/x-zip', 'application/x-compressed');
				foreach($accepted_types1 as $mime_type) 
				{
					if($mime_type == $type1) 
					{
						$okay = true;
						break;
					} 
				}
				
				$continue = strtolower($name[1]) == 'zip' ? true : false;
				if(!$continue)
				{
					$message = "The file you are trying to upload is not a .zip file. Please try again.";
				}
				
				$target_path1 = "assets/AdminLTE-2.0.5/doc_center/uploads/".$filename1;  // change this to the correct site path
				if(move_uploaded_file($source1, $target_path1))
				{
					$zip = new ZipArchive();
					$x = $zip->open($target_path1);
					if ($x === true) 
					{
						$zip->extractTo("assets/AdminLTE-2.0.5/doc_center/uploads/"); // change this to the correct site path
						$zip->close();
				
						unlink($target_path1);
					}
					$message = "Your .zip file was uploaded and unpacked.";
				} 
				else 
				{	
					$message = "There was a problem with the upload. Please try again.";
				}
			}
			
			/*echo "HRDOCNO = $HRDOCNO<br>";
			echo "DOCCODE = $DOCCODE<br>";
			echo "HRDOCCODE = $HRDOCCODE<br>";
			echo "SHOW_SE_DATE = $SHOW_SE_DATE<br>";
			echo "START_DATE = $START_DATE<br>";
			echo "END_DATE = $END_DATE<br>";
			echo "HRDOCTYPE = $HRDOCTYPE<br>";
			echo "HRDOCJNS = $HRDOCJNS<br>";
			echo "HRDOCJML = $HRDOCJML<br>";
			echo "HRDOCLBR = $HRDOCLBR<br>";
			echo "HRDOCLOK = $HRDOCLOK<br>";
			echo "PRJCODE = $PRJCODE<br>";
			echo "OWNER_DESC = $OWNER_DESC<br>";
			echo "OWNER_ADD = $OWNER_ADD<br>";
			echo "HRDOCCOST = $HRDOCCOST<br>";
			echo "PM_EMPCODE = $PM_EMPCODE<br>";
			echo "PM_NAME = $PM_NAME<br>";
			echo "PM_STATUS = $PM_STATUS<br>";
			echo "DIR_EMPCODE = $DIR_EMPCODE<br>";
			echo "DIR_NAME = $DIR_NAME<br>";
			echo "HRDOCSTAT = $HRDOCSTAT<br>";
			echo "file_name = $file_name<br>";*/
			
			if($file_name != '')
			{
				/*
				$config['upload_path']   	= "assets/AdminLTE-2.0.5/doc_center/uploads/$file_name/"; 
				$config['allowed_types']	= ''; 
				$config['overwrite'] 		= TRUE;
				$config['max_size']     	= 100000000000; 
				$config['max_width']    	= 100000000000; 
				$config['max_height']    	= 100000000000;  
				$config['file_name']       	= $file['name'];
				echo "Testing";
				$this->load->library('upload', $config);
				
				$this->load->library('upload', $config);*/
				$file 						= $_FILES['userfile'];
				$file_name 					= $file['name'];
				$config['upload_path']   	= "assets/AdminLTE-2.0.5/doc_center/uploads/"; 
				$config['allowed_types']	= 'pdf|gif|jpg|jpeg|png'; 
				$config['overwrite'] 		= TRUE;
				//$config['max_size']     	= 1000000000; 
				//$config['max_width']    	= 10024; 
				//$config['max_height']    	= 10000;  
				$config['file_name']       	= $file['name'];
		
				$this->load->library('upload', $config);
				
				if ( ! $this->upload->do_upload('userfile')) 
				{
					//return false;
					$DOCCODE				= $DOCCODE;			
					$getDOCType 			= $this->m_qhsedocument->get_DOC_Type($DOCCODE)->row();
					$doc_name				= $getDOCType->doc_name;
					
					$data['title'] 			= $appName;
					$data['DOCCODE'] 		= $DOCCODE;
					$data['doc_name'] 		= $doc_name;
					$cancel_url				= site_url('c_project/qhsedocument/qhse_documentlist/?id='.$this->url_encryption_helper->encode_url($DOCCODE));
					
					$docPatternPosition 	= 'Especially';
					$data['title'] 			= $appName;
					$data['task'] 			= 'add';
					$data['h2_title']		= 'Add Document';
					$data['h3_title']		= 'QHSE Document';
					$data['main_view'] 		= 'v_project/v_qhsedocument/qhsedocument_form';
					$data['form_action']	= site_url('c_project/qhsedocument/do_upload');
					$data['link'] 			= array('link_back' => anchor("$cancel_url",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Back" />', array('style' => 'text-decoration: none;')));
					
					$MenuCode 				= 'MN268';
					$data['viewDocPattern'] = $this->m_qhsedocument->getDataDocPat($MenuCode)->result();
					
					$this->load->view('v_project/v_qhsedocument/qhsedocument_form', $data);
				}
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
							'SHOW_SE_DATE'	=> $SHOW_SE_DATE,	// OK
							'HRDOCSTAT'		=> $HRDOCSTAT,		// OK
							'HRDOC_CREATED'	=> $HRDOC_CREATED,	// OK
							'HRDOC_NAME'	=> $file_name,		// OK
							'HRDOC_TEMPL'	=> $file_name1,		// OK
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
							'Patt_Number'	=> $this->input->post('Patt_Number'));
				
				$this->m_qhsedocument->add($dataINSDOC);
			}
			else
			{
				if($file_name != '' && $file_name1 != '')
				{
					$dataUPDDOC = array('HRDOCTYPE'		=> $HRDOCTYPE,		// OK
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
								'SHOW_SE_DATE'	=> $SHOW_SE_DATE,	// OK
								'HRDOCSTAT'		=> $HRDOCSTAT,		// OK
								'HRDOC_CREATED'	=> $HRDOC_CREATED,	// OK
								'HRDOC_NAME'	=> $file_name,		// OK
								'HRDOC_TEMPL'	=> $file_name1,		// OK
								'PM_EMPCODE'	=> $PM_EMPCODE,		// OK
								'PM_NAME'		=> $PM_NAME,		// OK
								'PM_STATUS'		=> $PM_STATUS,		// OK
								'DIR_EMPCODE'	=> $DIR_EMPCODE,	// OK
								'DIR_NAME'		=> $DIR_NAME,		// OK
								'PEMILIK_MODAL'	=> $OWNER_DESC,		// OK
								'BORROW_EMP'	=> $BORROW_EMP,		// OK
								'HRDOC_NOTE'	=> $HRDOC_NOTE);
				}
				elseif($file_name != '' && $file_name1 == '')
				{
					$dataUPDDOC = array('HRDOCTYPE'		=> $HRDOCTYPE,		// OK
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
								'SHOW_SE_DATE'	=> $SHOW_SE_DATE,	// OK
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
								'HRDOC_NOTE'	=> $HRDOC_NOTE);
				}
				elseif($file_name == '' && $file_name1 != '')
				{
					$dataUPDDOC = array('HRDOCTYPE'		=> $HRDOCTYPE,		// OK
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
								'SHOW_SE_DATE'	=> $SHOW_SE_DATE,	// OK
								'HRDOCSTAT'		=> $HRDOCSTAT,		// OK
								'HRDOC_CREATED'	=> $HRDOC_CREATED,	// OK
								'HRDOC_TEMPL'	=> $file_name1,		// OK
								'PM_EMPCODE'	=> $PM_EMPCODE,		// OK
								'PM_NAME'		=> $PM_NAME,		// OK
								'PM_STATUS'		=> $PM_STATUS,		// OK
								'DIR_EMPCODE'	=> $DIR_EMPCODE,	// OK
								'DIR_NAME'		=> $DIR_NAME,		// OK
								'PEMILIK_MODAL'	=> $OWNER_DESC,		// OK
								'BORROW_EMP'	=> $BORROW_EMP,		// OK
								'HRDOC_NOTE'	=> $HRDOC_NOTE);
				}
				else
				{
					$dataUPDDOC = array('HRDOCTYPE'		=> $HRDOCTYPE,		// OK
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
								'SHOW_SE_DATE'	=> $SHOW_SE_DATE,	// OK
								'HRDOCSTAT'		=> $HRDOCSTAT,		// OK
								'HRDOC_CREATED'	=> $HRDOC_CREATED,	// OK
								'PM_EMPCODE'	=> $PM_EMPCODE,		// OK
								'PM_NAME'		=> $PM_NAME,		// OK
								'PM_STATUS'		=> $PM_STATUS,		// OK
								'DIR_EMPCODE'	=> $DIR_EMPCODE,	// OK
								'DIR_NAME'		=> $DIR_NAME,		// OK
								'PEMILIK_MODAL'	=> $OWNER_DESC,		// OK
								'BORROW_EMP'	=> $BORROW_EMP,		// OK
								'HRDOC_NOTE'	=> $HRDOC_NOTE);
				}				
				$this->m_qhsedocument->update($HRDOCNO, $dataUPDDOC);
			}
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			$url			= site_url('c_project/qhsedocument/qhse_documentlist/?id='.$this->url_encryption_helper->encode_url($DOCCODE));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update() // OK
	{
		$this->load->model('m_project/m_qhsedocument/m_qhsedocument', '', TRUE);
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
			
			$getprojectDOC 				= $this->m_qhsedocument->get_DOC_by_number($HRDOCNO)->row();
			$PRJCODE					= $getprojectDOC->PRJCODE;
			$DOCCODE					= $getprojectDOC->DOCCODE;
			
			$getDOCType 				= $this->m_qhsedocument->get_DOC_Type($DOCCODE)->row();
			$doc_name					= $getDOCType->doc_name;
			
			$cancel_url 				= site_url('c_project/qhsedocument/qhse_documentlist/?id='.$this->url_encryption_helper->encode_url($DOCCODE));
			
			$data['doc_name'] 			= $doc_name;
			$docPatternPosition 		= 'Especially';	
			$data['title'] 				= $appName;
			$data['task'] 				= 'edit';
			$data['h2_title']			= 'Update Document';
			$data['h3_title']			= 'QHSE Document';
			$data['main_view'] 			= 'v_project/v_qhsedocument/qhsedocument_form';
			$data['form_action']		= site_url('c_project/qhsedocument/do_upload');
			$data['link'] 				= array('link_back' => anchor("$cancel_url",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Back" />', array('style' => 'text-decoration: none;')));
			
			$MenuCode 					= 'MN268';
			$data["MenuCode"] 			= 'MN268';
			$data['viewDocPattern'] 	= $this->m_qhsedocument->getDataDocPat($MenuCode)->result();
			
			$getprojectSPK 				= $this->m_qhsedocument->get_DOC_by_number($HRDOCNO)->row();
			
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
			$data['default']['SHOW_SE_DATE']	= $getprojectSPK->SHOW_SE_DATE;
			$data['default']['HRDOCSTAT']		= $getprojectSPK->HRDOCSTAT;
			$data['default']['HRDOC_NAME']		= $getprojectSPK->HRDOC_NAME;
			$data['default']['HRDOC_TEMPL']		= $getprojectSPK->HRDOC_TEMPL;
			$data['default']['PM_EMPCODE']		= $getprojectSPK->PM_EMPCODE;
			$data['default']['PM_NAME']			= $getprojectSPK->PM_NAME;
			$data['default']['PM_STATUS']		= $getprojectSPK->PM_STATUS;
			$data['default']['DIR_EMPCODE']		= $getprojectSPK->DIR_EMPCODE;
			$data['default']['DIR_NAME']		= $getprojectSPK->DIR_NAME;
			$data['default']['STATUS_DOK']		= $getprojectSPK->STATUS_DOK;
			$data['default']['BORROW_EMP']		= $getprojectSPK->BORROW_EMP;
			$data['default']['PEMILIK_MODAL']	= $getprojectSPK->PEMILIK_MODAL;
			$data['default']['HRDOC_NOTE']		= $getprojectSPK->HRDOC_NOTE;
			$data['default']['Patt_Date'] 		= $getprojectSPK->Patt_Date;
			$data['default']['Patt_Month'] 		= $getprojectSPK->Patt_Month;
			$data['default']['Patt_Year'] 		= $getprojectSPK->Patt_Year;
			$data['default']['Patt_Number'] 	= $getprojectSPK->Patt_Number;
			
			$this->load->view('v_project/v_qhsedocument/qhsedocument_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function delDocument() // OK
	{
		$HRDOCID	= $_GET['id'];
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$this->load->model('m_project/m_qhsedocument/m_qhsedocument', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$this->db->trans_begin();
			
			date_default_timezone_set("Asia/Jakarta");
			
			$this->m_qhsedocument->UpdateOriginal($HRDOCID);
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			//return $this->m_qhsedocument->get_all_mail_inbox($DefEmp_ID)->result();
		}
		else
		{
			redirect('Auth');
		}
	}

	function get_last_ten_Type($offset=0) // HOLD
	{
		$this->load->model('m_project/m_qhsedocument/m_qhsedocument', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$doc_code			= $_GET['id'];
			$doc_code			= $this->url_encryption_helper->decode_url($doc_code);
			
			$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
			
			$data['title'] 			= $appName;
			$data['h2_title'] 		= 'Document Type List';
			$data['h3_title'] 		= 'HR Document';
			$data['DefEmp_ID'] 		= $this->session->userdata['Emp_ID'];
			$data['parentCode'] 	= $doc_code;
			$cancel_url				= site_url('c_project/qhsedocument/get_last_ten_GroupType/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['link'] 			= array('link_back' => anchor("$cancel_url",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Back" />', array('style' => 'text-decoration: none;')));
			
			$this->load->view('v_project/v_qhsedocument/qhsedocument', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function qhse_documentlistx($offset=0) // HOLD
	{
		$this->load->model('m_project/m_qhsedocument/m_qhsedocument', '', TRUE);
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
			$data['main_view'] 			= 'v_project/v_qhsedocument/qhsedocumentlist';	
					
			$data['link'] 				= array('link_back' => anchor('c_project/qhsedocument/','<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Back" />', array('style' => 'text-decoration: none;')));
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
			
			$num_rows 					= $this->m_qhsedocument->count_all_num_rowsProjDOC($PRJCODE, $selTYPEDOC, $txtSearch);
			
			$data["recordcount"] 		= $num_rows;
			
			$data['CATTYPE'] 			= '';
			
			$data["recordcount"] 		= $num_rows;			
			$config 					= array();
			$config['base_url'] 		= site_url('c_project/qhsedocument/get_last_ten_projHRDOC1');
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
	 
			$data['viewdocument'] = $this->m_qhsedocument->get_last_ten_projHRDOC($PRJCODE, $config["per_page"], $offset, $selTYPEDOC, $txtSearch)->result();
			
			$data["pagination"] = $this->pagination->create_links();
			
			$myProjectSess = array(
					'myProjSession' => $selSearchproj_Code);
			$this->session->set_userdata('dtProjSess', $myProjectSess);
			
			$this->load->view('v_project/v_qhsedocument/qhsedocumentlist', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function view_pdf()
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_project/m_qhsedocument/m_qhsedocument', '', TRUE);
			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			$FileUpName	= $_GET['id'];
			$FileUpName	= $this->url_encryption_helper->decode_url($FileUpName);
			
			$data['title'] 		= $appName;
			$data['h2_title'] 	= 'View Document';
			$data['h3_title'] 	= 'PDF';
			$data['FileUpName'] = $FileUpName;
					
			$this->load->view('v_project/v_qhsedocument/qhsedocument_viewpdf', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function ins_dl_hist()
	{
		date_default_timezone_set("Asia/Jakarta");
		
		$DWLEMPID	= $_GET['id'];
		$DWLEMPID	= $this->url_encryption_helper->decode_url($DWLEMPID);		
		$DWLDATE	= date('Y-m-d H:i:s');
		$DWLTYPE	= 'e-procedure';
		$HRDOCID	= $this->input->post('HRDOCID');
		$DWLGRP		= $this->input->post('DWLGRP');
		$DWLCLASS	= $this->input->post('DWLCLASS');
		$DWLREF1	= $this->input->post('DWLREF1');
		
		$sqlDOC		= "SELECT HRDOCNO, HRDOCCODE, HRDOC_NAME, HRDOC_TEMPL FROM tbl_qhsedoc_header WHERE HRDOCID = '$HRDOCID'";
		$resDOC		= $this->db->query($sqlDOC)->result();
			foreach($resDOC as $rowDOC) :
				$HRDOCNO 	= $rowDOC->HRDOCNO;
				$HRDOCCODE 	= $rowDOC->HRDOCCODE;
				$HRDOC_NAME	= $rowDOC->HRDOC_NAME;
				$HRDOC_TEMPL= $rowDOC->HRDOC_TEMPL;
			endforeach;
		
		if($DWLGRP == 'DOC')
		{
			$sqlInsHist	= "INSERT INTO tbl_dwlhist (DWLEMPID, DWLDATE, DWLGRP, DWLCLASS, DWLTYPE, DWLFN, DWLREF1, DWLREF2, DWLREF3)
			VALUES ('$DWLEMPID', '$DWLDATE', '$DWLGRP', '$DWLCLASS', '$DWLTYPE', '$HRDOC_NAME', '$DWLREF1', '$HRDOCNO', '$HRDOCCODE')";
		}
		else
		{
			$sqlInsHist	= "INSERT INTO tbl_dwlhist (DWLEMPID, DWLDATE, DWLGRP, DWLCLASS, DWLTYPE, DWLFN, DWLREF1, DWLREF2, DWLREF3)
			VALUES ('$DWLEMPID', '$DWLDATE', '$DWLGRP', '$DWLCLASS', '$DWLTYPE', '$HRDOC_TEMPL', '$DWLREF1', '$HRDOCNO', '$HRDOCCODE')";
		}
		$this->db->query($sqlInsHist);
	}
}