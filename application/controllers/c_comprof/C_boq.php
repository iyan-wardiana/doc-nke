<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 5 Juli 2019
 * File Name	= C_boq.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_boq extends CI_Controller  
{
  	function __construct() // GOOD
	{
		parent::__construct();
		
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_company/m_boq/m_boq', '', TRUE);
		$this->load->model('m_updash/m_updash', '', TRUE);
		$this->load->model('m_project/m_joblistdet/m_joblistdet', '', TRUE);
	
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
	}
	
 	public function index() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_comprof/c_boq/prjl0b28t18/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function prjl0b28t18() // OK
	{		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			// START : GET MENU NAME
				$data['MenuCode'] 	= 'MN404';
				$MenuCode			= 'MN404';
				$getMNNm 		= $this->m_updash->get_menunm($MenuCode)->row();
				if($this->data['LangID'] == 'IND')
					$data['h1_title'] = $getMNNm->menu_name_IND;
				else
					$data['h1_title'] = $getMNNm->menu_name_ENG;
			// END : GET MENU NAME
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN106';	 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();		
			$data["secURL"] 	= "c_comprof/c_boq/G3t_bQL/?id=";
			
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
				//redirect($url);
				
				$this->load->view($data["secVIEW"], $data);
			}
		}
		else
		{
			redirect('__l1y');
		}
	}

	function G3t_bQL($offset=0) // OK
	{
		if ($this->session->userdata('login') == TRUE)
		{	
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			// START : GET MENU NAME
				$data['MenuCode'] 	= 'MN404';
				$MenuCode			= 'MN404';
				$getMNNm 		= $this->m_updash->get_menunm($MenuCode)->row();
				if($this->data['LangID'] == 'IND')
					$data['h1_title'] = $getMNNm->menu_name_IND;
				else
					$data['h1_title'] = $getMNNm->menu_name_ENG;
			// END : GET MENU NAME
			
			$PRJCODE				= $_GET['id'];
			$PRJCODE				= $this->url_encryption_helper->decode_url($PRJCODE);
			$data['PRJCODE'] 		= $PRJCODE;
			
			$backURL				= site_url('c_comprof/c_boq/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$data['title'] 			= $appName;
			$data['h2_title'] 		= 'History';
			$data['h3_title'] 		= 'bill of quantity';
			$data['secAdd'] 		= site_url('c_comprof/c_boq/add/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['link'] 			= array('link_back' => anchor("$backURL",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 		= $backURL;
			
			$getprojname 			= $this->m_joblistdet->get_project_name($PRJCODE)->row();			
			$data['PRJNAME'] 		= $getprojname->PRJNAME;
			
			$num_rows 				= $this->m_boq->count_all_boq_hist($PRJCODE);
			$data['countBoQH'] 		= $num_rows;
	 		$data['viewBoQH'] 		= $this->m_boq->get_all_boq_hist($PRJCODE)->result();
			
			$this->load->view('v_company/v_boq/v_boq', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function add() // OK
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
			
			$getprojname 			= $this->m_joblistdet->get_project_name($PRJCODE)->row();			
			$data['PRJNAME'] 		= $getprojname->PRJNAME;
			
			$backURL	= site_url('c_comprof/c_boq/G3t_bQL/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			$data['isProcess'] 		= 0;
			$data['message'] 		= '';
			$data['PRJCODE']		= $PRJCODE;
			$data['BOQH_DESC']		= '';
			$data['isUploaded']		= 0;
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			$data['h2_title']		= 'Upload';
			$data['h3_title'] 		= 'bill of quantity';
			$data['form_action']	= site_url('c_comprof/c_boq/do_upload');
			//$data['link'] 			= array('link_back' => anchor("$backURL",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 		= $backURL;
			
			$this->load->view('v_company/v_boq/v_boq_upload_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function do_upload() // OK
	{
		$this->load->model('m_project/m_boq/m_boq', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 					= $this->session->userdata['Emp_ID'];
		
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
				$target_path 	= "import_excel/import_boq/master/".$filename;  // change this to the correct site path					
				$myPath 		= "import_excel/import_boq/master/$filename";
			}
			else
			{
				$target_path 	= "import_excel/import_boq/period/".$filename;  // change this to the correct site path					
				$myPath 		= "import_excel/import_boq/period/$filename";
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
			
			$backURL				= site_url('c_comprof/c_boq/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['isProcess'] 		= 1;
			$data['title'] 			= $appName;
			$data['PRJCODE']		= $BOQH_PRJCODE;
			$data['task'] 			= 'add';
			$data['h2_title']		= 'Upload';
			$data['h3_title'] 		= 'bill of quantity';
			$data['form_action']	= site_url('c_comprof/c_boq/do_upload');
			$data['link'] 			= array('link_back' => anchor('c_comprof/c_boq/','<input type="button" class="btn btn-danger" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 		= $backURL;
		
			$url	= site_url('c_comprof/c_boq/G3t_bQL/?id='.$this->url_encryption_helper->encode_url($BOQH_PRJCODE));
			redirect($url);
		}
	}
	
	function view_boq() // OK
	{
		if ($this->session->userdata('login') == TRUE)
		{		
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$BOQH_CODE	= $_GET['id'];
			$BOQH_CODE	= $this->url_encryption_helper->decode_url($BOQH_CODE);
			
			$sqlPRJ		= "SELECT BOQH_PRJCODE FROM tbl_boq_hist WHERE BOQH_CODE = '$BOQH_CODE'";
			$sqlPRJR	= $this->db->query($sqlPRJ)->result();
			foreach($sqlPRJR as $rowPRJ) :
				$BOQH_PRJ		= $rowPRJ->BOQH_PRJCODE;
			endforeach;
	
			$data['PRJCODE']		= $BOQH_PRJ;
			$data['BOQH_CODE']		= $BOQH_CODE;
			$data['title'] 			= $appName;
			$data['h2_title']		= 'View';
			$data['h3_title'] 		= 'bill of quantity';
			
			$this->load->view('v_company/v_boq/v_boq_view_xl', $data);
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
}