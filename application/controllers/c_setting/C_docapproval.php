<?php
/*  
 * Author		= Dian Hermanto
 * Create Date	= 25 Januari 2018
 * File Name	= C_docapproval.php
 * Location		= -
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_docapproval  extends CI_Controller
{
  	function __construct() // GOOD
	{
		parent::__construct();
		
		$this->load->model('m_setting/m_docapproval/m_docapproval', '', TRUE);
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
	}
	
 	// Start : Index tiap halaman
 	public function index()
	{		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_setting/C_docapproval/index1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	public function index1($offset=0)
	{
		$this->load->model('m_setting/m_docapproval/m_docapproval', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			$data['title'] 				= $appName;
			$data['h2_title'] 			= 'Document Approval';
			$data["MenuCode"] 			= 'MN076';
			
			$num_rows 					= $this->m_docapproval->count_all_num_rows();
			$data["recordcount"] 		= $num_rows;
			
			// Start of Pagination
			$config 					= array();
			$config['base_url'] 		= site_url('c_setting/c_docapproval/get_last_ten_docapproval');	
			$config["total_rows"] 		= $num_rows;
			$config["per_page"] 		= 20;
			$config["uri_segment"]		= 4;
			$config['cur_page'] 		= $offset;			
			$config['full_tag_open'] 	= '<ul class="tsc_pagination tsc_paginationA tsc_paginationA01">';
			$config['full_tag_close'] 	= '</ul>';
			$config['prev_link'] 		= '&lt;';
			$config['prev_tag_open']	= '<li>';
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
			// End of Pagination
	 
			$data['viewdocapproval'] 	= $this->m_docapproval->get_last_ten_docapproval($config["per_page"], $offset)->result();
			$data["pagination"] 		= $this->pagination->create_links();
			
			$this->load->view('v_setting/v_docapproval/docapproval', $data);
		}
		else
		{
			redirect('login');
		}
	}
	// End

  	function get_AllData() // GOOD
	{
		$LangID 		= $this->session->userdata['LangID'];
		$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
		$resTransl		= $this->db->query($sqlTransl)->result();
		foreach($resTransl as $rowTransl) :
			$TranslCode	= $rowTransl->MLANG_CODE;
			$LangTransl	= $rowTransl->LangTransl;
			if($TranslCode == 'payableList')$payableList = $LangTransl;
			if($TranslCode == 'Contact')$Contact = $LangTransl;
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
			
			$columns_valid 	= array("",
									"DOCAPP_NAME",
									"PRJCODE",
									"POSCODE",
									"APPROVER_1");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_docapproval->get_AllDataC($search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_docapproval->get_AllDataL($search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
                $DOCAPP_ID 			= $dataI['DOCAPP_ID'];
                $DOCAPP_NAME 		= $dataI['DOCAPP_NAME'];
                $PRJCODE	 		= $dataI['PRJCODE'];
                $MENU_CODE 			= $dataI['MENU_CODE'];
                $POSCODE            = $dataI['POSCODE'];
                $POSS_LEVEL         = $dataI['POSLEVEL'];
                $POSS_NAME          = "-";
                if($POSCODE != '')
                {
                    $sqlDEPT        = "SELECT POSS_NAME FROM tbl_position_str WHERE POSS_CODE = '$POSCODE'";
                    $resDEPT        = $this->db->query($sqlDEPT)->result();
                    foreach($resDEPT as $rowDEPT):
                        $POSS_NAME  = $rowDEPT->POSS_NAME;
                    endforeach;
                }

                $APPROVER_1 		= $dataI['APPROVER_1'];
                $APPROVER_2 		= $dataI['APPROVER_2'];
                $APPROVER_3 		= $dataI['APPROVER_3'];
                $APPROVER_4 		= $dataI['APPROVER_4'];
                $APPROVER_5 		= $dataI['APPROVER_5'];
                $APPLIMIT_1 		= $dataI['APPLIMIT_1'];
                $APPLIMIT_2 		= $dataI['APPLIMIT_2'];
                $APPLIMIT_3 		= $dataI['APPLIMIT_3'];
                $APPLIMIT_4 		= $dataI['APPLIMIT_4'];
                $APPLIMIT_5 		= $dataI['APPLIMIT_5'];
                
                $APP1_EMPNAME1	= "";
                if($APPROVER_1 != '')
                {
    				$First_Name1	= '';
    				$Last_Name1		= '';
    				$sqlGetEMPNC1	= "tbl_employee WHERE EMP_ID = '$APPROVER_1'";
                    $resGetEMPNC1	= $this->db->count_all($sqlGetEMPNC1);
    				if($resGetEMPNC1 > 0)
    				{
    					$sqlGetEMPN1	= "SELECT First_Name, Last_Name FROM tbl_employee WHERE EMP_ID = '$APPROVER_1'";
    					$resGetEMPN1	= $this->db->query($sqlGetEMPN1)->result();
    					foreach($resGetEMPN1 as $rowEMPN1) :
    						$First_Name1	= $rowEMPN1->First_Name;
    						$Last_Name1		= $rowEMPN1->Last_Name;
    					endforeach;
    				}
    				else
    				{
    					$First_Name1	= "---- Employee";
    					$Last_Name1		= "Lose ----";
    				}
                    $APP1_EMPNAME1		= "STEP 1 : $First_Name1 $Last_Name1";
                }
                
                $APP2_EMPNAME2	= "";
                if($APPROVER_2 != '')
                {
    				$First_Name2	= '';
    				$Last_Name2		= '';
                    $sqlGetEMPNC2	= "tbl_employee WHERE EMP_ID = '$APPROVER_2'";
                    $resGetEMPNC2	= $this->db->count_all($sqlGetEMPNC2);
    				if($resGetEMPNC2 > 0)
    				{
    					$sqlGetEMPN2	= "SELECT First_Name, Last_Name FROM tbl_employee WHERE EMP_ID = '$APPROVER_2'";
    					$resGetEMPN2	= $this->db->query($sqlGetEMPN2)->result();
    					foreach($resGetEMPN2 as $rowEMPN2) :
    						$First_Name2	= $rowEMPN2->First_Name;
    						$Last_Name2		= $rowEMPN2->Last_Name;
    					endforeach;
    				}
    				else
    				{
    					$First_Name2	= "---- Employee";
    					$Last_Name2		= "Lose ----";
    				}
                    $APP2_EMPNAME2		= ",&nbsp;&nbsp;STEP 2 : $First_Name2 $Last_Name2";
                }
                
                $APP3_EMPNAME3	= "";
                if($APPROVER_3 != '')
                {
    				$First_Name3	= '';
    				$Last_Name3		= '';
                    $sqlGetEMPNC3	= "tbl_employee WHERE EMP_ID = '$APPROVER_3'";
                    $resGetEMPNC3	= $this->db->count_all($sqlGetEMPNC3);
    				if($resGetEMPNC3 > 0)
    				{
    					$sqlGetEMPN3	= "SELECT First_Name, Last_Name FROM tbl_employee WHERE EMP_ID = '$APPROVER_3'";
    					$resGetEMPN3	= $this->db->query($sqlGetEMPN3)->result();
    					foreach($resGetEMPN3 as $rowEMPN3) :
    						$First_Name3	= $rowEMPN3->First_Name;
    						$Last_Name3		= $rowEMPN3->Last_Name;
    					endforeach;
    				}
    				else
    				{
    					$First_Name3	= "---- Employee";
    					$Last_Name3		= "Lose ----";
    				}
                    $APP3_EMPNAME3		= ",&nbsp;&nbsp;STEP 3 : $First_Name3 $Last_Name3";
                }
                
                $APP4_EMPNAME4	= "";
                if($APPROVER_4 != '')
                {
                   	$First_Name4	= '';
    				$Last_Name4		= '';
                    $sqlGetEMPNC4	= "tbl_employee WHERE EMP_ID = '$APPROVER_4'";
                    $resGetEMPNC4	= $this->db->count_all($sqlGetEMPNC4);
    				if($resGetEMPNC4 > 0)
    				{
    					$sqlGetEMPN4	= "SELECT First_Name, Last_Name FROM tbl_employee WHERE EMP_ID = '$APPROVER_4'";
    					$resGetEMPN4	= $this->db->query($sqlGetEMPN4)->result();
    					foreach($resGetEMPN4 as $rowEMPN4) :
    						$First_Name4	= $rowEMPN4->First_Name;
    						$Last_Name4		= $rowEMPN4->Last_Name;
    					endforeach;
    				}
    				else
    				{
    					$First_Name4	= "---- Employee";
    					$Last_Name4		= "Lose ----";
    				}
                    $APP4_EMPNAME4		= ",&nbsp;&nbsp;STEP 4 : $First_Name4 $Last_Name4";
                }
                
                $APP5_EMPNAME5	= "";
                if($APPROVER_5 != '')
                {
                    $First_Name5	= '';
    				$Last_Name5		= '';
                    $sqlGetEMPNC5	= "tbl_employee WHERE EMP_ID = '$APPROVER_5'";
                    $resGetEMPNC5	= $this->db->count_all($sqlGetEMPNC5);
    				if($resGetEMPNC5 > 0)
    				{
    					$sqlGetEMPN5	= "SELECT First_Name, Last_Name FROM tbl_employee WHERE EMP_ID = '$APPROVER_5'";
    					$resGetEMPN5	= $this->db->query($sqlGetEMPN5)->result();
    					foreach($resGetEMPN5 as $rowEMPN5) :
    						$First_Name5	= $rowEMPN5->First_Name;
    						$Last_Name5		= $rowEMPN5->Last_Name;
    					endforeach;
    				}
    				else
    				{
    					$First_Name5	= "---- Employee";
    					$Last_Name5		= "Lose ----";
    				}
                    $APP5_EMPNAME5		= ",&nbsp;&nbsp;STEP 5 : $First_Name5 $Last_Name5";
                }
				
				$secUpd		= site_url('c_setting/c_docapproval/update/?id='.$this->url_encryption_helper->encode_url($DOCAPP_ID));

				$secDelSpl 	= base_url().'index.php/c_setting/c_docapproval/delSettApp/?id=';
				$delID 		= "$secDelSpl~$DOCAPP_ID";

				$secAction	= 	"<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteSett(".$noU.")' title='Delete'>
										<i class='fa fa-trash-o'></i>
									</a>
								   	</label>";

				$output['data'][] 	= array("$noU.",
										  	$DOCAPP_NAME,
										  	$PRJCODE,
										  	$POSS_NAME,
										  	$POSS_LEVEL,
										  	"$APP1_EMPNAME1$APP2_EMPNAME2$APP3_EMPNAME3$APP4_EMPNAME4$APP5_EMPNAME5",
										  	$secAction);
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

  	function get_AllDataGRP() // GOOD
	{
		$MENU_CODE		= $_GET['id'];
		$PRJCODE		= $_GET['PRJCODE'];

		$LangID 		= $this->session->userdata['LangID'];
		$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
		$resTransl		= $this->db->query($sqlTransl)->result();
		foreach($resTransl as $rowTransl) :
			$TranslCode	= $rowTransl->MLANG_CODE;
			$LangTransl	= $rowTransl->LangTransl;
			if($TranslCode == 'payableList')$payableList = $LangTransl;
			if($TranslCode == 'Contact')$Contact = $LangTransl;
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
			
			$columns_valid 	= array("",
									"DOCAPP_NAME",
									"PRJCODE",
									"POSCODE",
									"APPROVER_1");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_docapproval->get_AllDataGRPC($search, $MENU_CODE, $PRJCODE);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_docapproval->get_AllDataGRPL($search, $MENU_CODE, $PRJCODE, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
                $DOCAPP_ID 			= $dataI['DOCAPP_ID'];
                $DOCAPP_NAME 		= $dataI['DOCAPP_NAME'];
                $PRJCODE	 		= $dataI['PRJCODE'];
                $MENU_CODE 			= $dataI['MENU_CODE'];
                $POSCODE            = $dataI['POSCODE'];
                $POSS_NAME          = "-";
                if($POSCODE != '')
                {
                    $sqlDEPT        = "SELECT POSS_NAME FROM tbl_position_str WHERE POSS_CODE = '$POSCODE'";
                    $resDEPT        = $this->db->query($sqlDEPT)->result();
                    foreach($resDEPT as $rowDEPT):
                        $POSS_NAME  = $rowDEPT->POSS_NAME;
                    endforeach;
                }

                $APPROVER_1 		= $dataI['APPROVER_1'];
                $APPROVER_2 		= $dataI['APPROVER_2'];
                $APPROVER_3 		= $dataI['APPROVER_3'];
                $APPROVER_4 		= $dataI['APPROVER_4'];
                $APPROVER_5 		= $dataI['APPROVER_5'];
                $APPLIMIT_1 		= $dataI['APPLIMIT_1'];
                $APPLIMIT_2 		= $dataI['APPLIMIT_2'];
                $APPLIMIT_3 		= $dataI['APPLIMIT_3'];
                $APPLIMIT_4 		= $dataI['APPLIMIT_4'];
                $APPLIMIT_5 		= $dataI['APPLIMIT_5'];
                
                $APP1_EMPNAME1	= "";
                if($APPROVER_1 != '')
                {
    				$First_Name1	= '';
    				$Last_Name1		= '';
    				$sqlGetEMPNC1	= "tbl_employee WHERE EMP_ID = '$APPROVER_1'";
                    $resGetEMPNC1	= $this->db->count_all($sqlGetEMPNC1);
    				if($resGetEMPNC1 > 0)
    				{
    					$sqlGetEMPN1	= "SELECT First_Name, Last_Name FROM tbl_employee WHERE EMP_ID = '$APPROVER_1'";
    					$resGetEMPN1	= $this->db->query($sqlGetEMPN1)->result();
    					foreach($resGetEMPN1 as $rowEMPN1) :
    						$First_Name1	= $rowEMPN1->First_Name;
    						$Last_Name1		= $rowEMPN1->Last_Name;
    					endforeach;
    				}
    				else
    				{
    					$First_Name1	= "---- Employee";
    					$Last_Name1		= "Lose ----";
    				}
                    $APP1_EMPNAME1		= "STEP 1 : $First_Name1 $Last_Name1";
                }
                
                $APP2_EMPNAME2	= "";
                if($APPROVER_2 != '')
                {
    				$First_Name2	= '';
    				$Last_Name2		= '';
                    $sqlGetEMPNC2	= "tbl_employee WHERE EMP_ID = '$APPROVER_2'";
                    $resGetEMPNC2	= $this->db->count_all($sqlGetEMPNC2);
    				if($resGetEMPNC2 > 0)
    				{
    					$sqlGetEMPN2	= "SELECT First_Name, Last_Name FROM tbl_employee WHERE EMP_ID = '$APPROVER_2'";
    					$resGetEMPN2	= $this->db->query($sqlGetEMPN2)->result();
    					foreach($resGetEMPN2 as $rowEMPN2) :
    						$First_Name2	= $rowEMPN2->First_Name;
    						$Last_Name2		= $rowEMPN2->Last_Name;
    					endforeach;
    				}
    				else
    				{
    					$First_Name2	= "---- Employee";
    					$Last_Name2		= "Lose ----";
    				}
                    $APP2_EMPNAME2		= ",&nbsp;&nbsp;STEP 2 : $First_Name2 $Last_Name2";
                }
                
                $APP3_EMPNAME3	= "";
                if($APPROVER_3 != '')
                {
    				$First_Name3	= '';
    				$Last_Name3		= '';
                    $sqlGetEMPNC3	= "tbl_employee WHERE EMP_ID = '$APPROVER_3'";
                    $resGetEMPNC3	= $this->db->count_all($sqlGetEMPNC3);
    				if($resGetEMPNC3 > 0)
    				{
    					$sqlGetEMPN3	= "SELECT First_Name, Last_Name FROM tbl_employee WHERE EMP_ID = '$APPROVER_3'";
    					$resGetEMPN3	= $this->db->query($sqlGetEMPN3)->result();
    					foreach($resGetEMPN3 as $rowEMPN3) :
    						$First_Name3	= $rowEMPN3->First_Name;
    						$Last_Name3		= $rowEMPN3->Last_Name;
    					endforeach;
    				}
    				else
    				{
    					$First_Name3	= "---- Employee";
    					$Last_Name3		= "Lose ----";
    				}
                    $APP3_EMPNAME3		= ",&nbsp;&nbsp;STEP 3 : $First_Name3 $Last_Name3";
                }
                
                $APP4_EMPNAME4	= "";
                if($APPROVER_4 != '')
                {
                   	$First_Name4	= '';
    				$Last_Name4		= '';
                    $sqlGetEMPNC4	= "tbl_employee WHERE EMP_ID = '$APPROVER_4'";
                    $resGetEMPNC4	= $this->db->count_all($sqlGetEMPNC4);
    				if($resGetEMPNC4 > 0)
    				{
    					$sqlGetEMPN4	= "SELECT First_Name, Last_Name FROM tbl_employee WHERE EMP_ID = '$APPROVER_4'";
    					$resGetEMPN4	= $this->db->query($sqlGetEMPN4)->result();
    					foreach($resGetEMPN4 as $rowEMPN4) :
    						$First_Name4	= $rowEMPN4->First_Name;
    						$Last_Name4		= $rowEMPN4->Last_Name;
    					endforeach;
    				}
    				else
    				{
    					$First_Name4	= "---- Employee";
    					$Last_Name4		= "Lose ----";
    				}
                    $APP4_EMPNAME4		= ",&nbsp;&nbsp;STEP 4 : $First_Name4 $Last_Name4";
                }
                
                $APP5_EMPNAME5	= "";
                if($APPROVER_5 != '')
                {
                    $First_Name5	= '';
    				$Last_Name5		= '';
                    $sqlGetEMPNC5	= "tbl_employee WHERE EMP_ID = '$APPROVER_5'";
                    $resGetEMPNC5	= $this->db->count_all($sqlGetEMPNC5);
    				if($resGetEMPNC5 > 0)
    				{
    					$sqlGetEMPN5	= "SELECT First_Name, Last_Name FROM tbl_employee WHERE EMP_ID = '$APPROVER_5'";
    					$resGetEMPN5	= $this->db->query($sqlGetEMPN5)->result();
    					foreach($resGetEMPN5 as $rowEMPN5) :
    						$First_Name5	= $rowEMPN5->First_Name;
    						$Last_Name5		= $rowEMPN5->Last_Name;
    					endforeach;
    				}
    				else
    				{
    					$First_Name5	= "---- Employee";
    					$Last_Name5		= "Lose ----";
    				}
                    $APP5_EMPNAME5		= ",&nbsp;&nbsp;STEP 5 : $First_Name5 $Last_Name5";
                }
				
				$secUpd		= site_url('c_setting/c_docapproval/update/?id='.$this->url_encryption_helper->encode_url($DOCAPP_ID));

				$secDelSpl 	= base_url().'index.php/c_setting/c_docapproval/delSettApp/?id=';
				$delID 		= "$secDelSpl~$DOCAPP_ID";

				$secAction	= 	"<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteSett(".$noU.")' title='Delete'>
										<i class='fa fa-trash-o'></i>
									</a>
								   	</label>";

				$output['data'][] 	= array("$noU.",
										  	$DOCAPP_NAME,
										  	$PRJCODE,
										  	$POSS_NAME,
										  	"$APP1_EMPNAME1$APP2_EMPNAME2$APP3_EMPNAME3$APP4_EMPNAME4$APP5_EMPNAME5",
										  	$secAction);
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function add()
	{
		$this->load->model('m_setting/m_docapproval/m_docapproval', '', TRUE);
		$this->load->model('m_hr/m_organiz/m_position_str', '', TRUE);

		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName 	= $therow->app_name;		
		endforeach;
				
		if ($this->session->userdata('login') == TRUE)
		{			
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			$data['form_action']	= site_url('c_setting/c_docapproval/add_process');
			$data['link'] 			= array('link_back' => anchor('c_setting/c_docapproval/','<input type="button" name="btnCancel" id="btnCancel" class="btn btn-primary" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data["MenuCode"] 		= 'MN076';

			$data['countParent'] 	= $this->m_position_str->count_all();		
			$data['vwParent'] 		= $this->m_position_str->get_position_str_prn()->result();
			
			$this->load->view('v_setting/v_docapproval/docapproval_form', $data);
		}
		else
		{
			redirect('login');
		}
	}
	
	function add_process()
	{
		$this->load->model('m_setting/m_docapproval/m_docapproval', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName 	= $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();
			
			$LangID 		= $this->session->userdata['LangID'];
			$MENU_NAME		= '';
			$MENU_CODE		= $this->input->post('MENU_CODE');
			$POSCODE		= $this->input->post('POSCODE');
			$sqlGetMENU		= "SELECT menu_name_$LangID AS menu_name FROM tbl_menu WHERE MENU_CODE = '$MENU_CODE'";
			$resGetMENU		= $this->db->query($sqlGetMENU)->result();
			foreach($resGetMENU as $rowMENU) :		
				$MENU_NAME	= $rowMENU->menu_name;
			endforeach;
			
			$APPROVER_1		= $this->input->post('APPROVER_1');
			$APPROVER_2		= $this->input->post('APPROVER_2');
			$APPROVER_3		= $this->input->post('APPROVER_3');
			$APPROVER_4		= $this->input->post('APPROVER_4');
			$APPROVER_5		= $this->input->post('APPROVER_5');
			$PRJCODE		= $this->input->post('PRJCODE');
			
			if($APPROVER_5 != '')
				$MAX_STEP = 5;
			elseif($APPROVER_4 != '')
				$MAX_STEP = 4;
			elseif($APPROVER_3 != '')
				$MAX_STEP = 3;
			elseif($APPROVER_2 != '')
				$MAX_STEP = 2;
			elseif($APPROVER_1 != '')
				$MAX_STEP = 1;
			
			//$num_rows 	= $this->m_docapproval->count_all_num_rows();
			$MAXNO 		= 0;
			$s_MAX		= "SELECT MAX(DOCCODE) AS MAXNO FROM tbl_docstepapp";
			$r_MAX		= $this->db->query($s_MAX)->result();
			foreach($r_MAX as $rw_MAX) :		
				$MAXNO	= (int)$rw_MAX->MAXNO;
			endforeach;

			//$DOCCODE	= $MAXNO + 1;
			$DOCCODE	= date('ymdHis');
			$InsDocApp 	= array('DOCCODE'		=> $DOCCODE,
								'PRJCODE' 		=> $PRJCODE,
								'DOCAPP_NAME' 	=> $MENU_NAME,
								'DOCAPP_TYPE' 	=> $this->input->post('DOCAPP_TYPE'),
								'MENU_CODE' 	=> $this->input->post('MENU_CODE'),
								'MDEPT_CODE' 	=> $this->input->post('MDEPT_CODE'),
								'POSCODE' 		=> $this->input->post('POSCODE'),
								'APPROVER_1'	=> $this->input->post('APPROVER_1'),
								'APPLIMIT_1'	=> $this->input->post('APPLIMIT_1'),
								'APPROVER_2'	=> $this->input->post('APPROVER_2'),
								'APPLIMIT_2'	=> $this->input->post('APPLIMIT_2'),
								'APPROVER_3'	=> $this->input->post('APPROVER_3'),
								'APPLIMIT_3'	=> $this->input->post('APPLIMIT_3'),
								'APPROVER_4'	=> $this->input->post('APPROVER_4'),
								'APPLIMIT_4'	=> $this->input->post('APPLIMIT_4'),
								'APPROVER_5'	=> $this->input->post('APPROVER_5'),
								'APPLIMIT_5'	=> $this->input->post('APPLIMIT_5'),
								'MAX_STEP'		=> $MAX_STEP,
								'CREATED_BY'	=> $this->input->post('CREATED_BY'));	
			$this->m_docapproval->add($InsDocApp);
			
			// START : Create to detail
				if($APPROVER_1 != '')
				{
					$MAX_STEP		= 1;
					$InsDocAppDet 	= array('DOCCODE' 		=> $DOCCODE,
											'PRJCODE' 		=> $PRJCODE,
											'DOCAPP_NAME' 	=> $POSCODE,
											'DOCAPP_TYPE' 	=> $this->input->post('DOCAPP_TYPE'),
											'MENU_CODE' 	=> $this->input->post('MENU_CODE'),
											'MDEPT_CODE' 	=> $this->input->post('MDEPT_CODE'),
											'POSCODE' 		=> $this->input->post('POSCODE'),
											'APPROVER_1'	=> $this->input->post('APPROVER_1'),
											'APPLIMIT_1'	=> $this->input->post('APPLIMIT_1'),
											'APP_STEP'		=> 1);	
					$this->m_docapproval->add1($InsDocAppDet);
					
					$UpdDocAppDet 	= array('MAX_STEP' 		=> 1);	
					$this->m_docapproval->updateDet($UpdDocAppDet, $DOCCODE);
				}
				if($APPROVER_2 != '')
				{
					$MAX_STEP		= 2;
					$InsDocAppDet 	= array('DOCCODE' 		=> $DOCCODE,
											'PRJCODE' 		=> $PRJCODE,
											'DOCAPP_NAME' 	=> $MENU_NAME,
											'DOCAPP_TYPE' 	=> $this->input->post('DOCAPP_TYPE'),
											'MENU_CODE' 	=> $this->input->post('MENU_CODE'),
											'MDEPT_CODE' 	=> $this->input->post('MDEPT_CODE'),
											'POSCODE' 		=> $this->input->post('POSCODE'),
											'APPROVER_1'	=> $this->input->post('APPROVER_2'),
											'APPLIMIT_1'	=> $this->input->post('APPLIMIT_2'),
											'APP_STEP'		=> 2);	
					$this->m_docapproval->add1($InsDocAppDet);
					
					$UpdDocAppDet 	= array('MAX_STEP' 		=> 2);	
					$this->m_docapproval->updateDet($UpdDocAppDet, $DOCCODE);
				}
				if($APPROVER_3 != '')
				{
					$MAX_STEP		= 3;
					$InsDocAppDet 	= array('DOCCODE' 		=> $DOCCODE,
											'PRJCODE' 		=> $PRJCODE,
											'DOCAPP_NAME' 	=> $MENU_NAME,
											'DOCAPP_TYPE' 	=> $this->input->post('DOCAPP_TYPE'),
											'MENU_CODE' 	=> $this->input->post('MENU_CODE'),
											'MDEPT_CODE' 	=> $this->input->post('MDEPT_CODE'),
											'POSCODE' 		=> $this->input->post('POSCODE'),
											'APPROVER_1'	=> $this->input->post('APPROVER_3'),
											'APPLIMIT_1'	=> $this->input->post('APPLIMIT_3'),
											'APP_STEP'		=> 3);	
					$this->m_docapproval->add1($InsDocAppDet);
					
					$UpdDocAppDet 	= array('MAX_STEP' 		=> 3);	
					$this->m_docapproval->updateDet($UpdDocAppDet, $DOCCODE);
				}
				if($APPROVER_4 != '')
				{
					$MAX_STEP		= 4;
					$InsDocAppDet 	= array('DOCCODE' 		=> $DOCCODE,
											'PRJCODE' 		=> $PRJCODE,
											'DOCAPP_NAME' 	=> $MENU_NAME,
											'DOCAPP_TYPE' 	=> $this->input->post('DOCAPP_TYPE'),
											'MENU_CODE' 	=> $this->input->post('MENU_CODE'),
											'MDEPT_CODE' 	=> $this->input->post('MDEPT_CODE'),
											'POSCODE' 		=> $this->input->post('POSCODE'),
											'APPROVER_1'	=> $this->input->post('APPROVER_4'),
											'APPLIMIT_1'	=> $this->input->post('APPLIMIT_4'),
											'APP_STEP'		=> 4);	
					$this->m_docapproval->add1($InsDocAppDet);
					
					$UpdDocAppDet 	= array('MAX_STEP' 		=> 4);	
					$this->m_docapproval->updateDet($UpdDocAppDet, $DOCCODE);
				}
				if($APPROVER_5 != '')
				{
					$MAX_STEP		= 5;
					$InsDocAppDet 	= array('DOCCODE' 		=> $DOCCODE,
											'PRJCODE' 		=> $PRJCODE,
											'DOCAPP_NAME' 	=> $MENU_NAME,
											'DOCAPP_TYPE' 	=> $this->input->post('DOCAPP_TYPE'),
											'MENU_CODE' 	=> $this->input->post('MENU_CODE'),
											'MDEPT_CODE' 	=> $this->input->post('MDEPT_CODE'),
											'POSCODE' 		=> $this->input->post('POSCODE'),
											'APPROVER_1'	=> $this->input->post('APPROVER_5'),
											'APPLIMIT_1'	=> $this->input->post('APPLIMIT_5'),
											'APP_STEP'		=> 5);	
					$this->m_docapproval->add1($InsDocAppDet);
					
					$UpdDocAppDet 	= array('MAX_STEP' 		=> 5);	
					$this->m_docapproval->updateDet($UpdDocAppDet, $DOCCODE);
				}

			// CEK LAST STEP
				$s_MAX		= "SELECT MAX(APP_STEP) AS MAXSTEP FROM tbl_docstepapp_det WHERE PRJCODE = '$PRJCODE' AND MENU_CODE = '$MENU_CODE'";
				$r_MAX		= $this->db->query($s_MAX)->result();
				foreach($r_MAX as $rw_MAX) :		
					$MAXSTEP	= $rw_MAX->MAXSTEP;

					$s_UPD 		= "UPDATE tbl_docstepapp_det SET MAX_STEP = $MAXSTEP
									WHERE PRJCODE = '$PRJCODE' AND MENU_CODE = '$MENU_CODE'";
					$this->db->query($s_UPD);
				endforeach;

			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			redirect('c_setting/c_docapproval/');
		}
		else
		{
			redirect('login');
		}
	}
	
	function update()
	{
		$this->load->model('m_setting/m_docapproval/m_docapproval', '', TRUE);
		$this->load->model('m_hr/m_organiz/m_position_str', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName 	= $therow->app_name;		
		endforeach;
		
		$DOCAPP_ID	= $_GET['id'];
		$DOCAPP_ID	= $this->url_encryption_helper->decode_url($DOCAPP_ID);
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$data['title'] 				= $appName;
			$data['task'] 				= 'edit';
			$data['h2_title'] 			= 'Document Approval | Edit Document Approval';
			$data['form_action']		= site_url('c_setting/c_docapproval/update_process');
			$data['link'] 				= array('link_back' => anchor('c_setting/c_docapproval/','<input type="button" name="btnCancel" id="btnCancel" value="Cancel" class="btn btn-primary" />', array('style' => 'text-decoration: none;')));
			$data["MenuCode"] 			= 'MN076';

			$data['countParent'] 	= $this->m_position_str->count_all();		
			$data['vwParent'] 		= $this->m_position_str->get_position_str_prn()->result();
			
			$getdocapproval = $this->m_docapproval->get_docstep_by_code($DOCAPP_ID)->row();
	
			$data['default']['DOCAPP_ID'] 	= $getdocapproval->DOCAPP_ID;
			$data['default']['DOCCODE'] 	= $getdocapproval->DOCCODE;
			$data['default']['PRJCODE'] 	= $getdocapproval->PRJCODE;
			$data['default']['DOCAPP_TYPE'] = $getdocapproval->DOCAPP_TYPE;
			$data['default']['DOCAPP_NAME'] = $getdocapproval->DOCAPP_NAME;
			$data['default']['MENU_CODE'] 	= $getdocapproval->MENU_CODE;
			$data['default']['MDEPT_CODE'] 	= $getdocapproval->MDEPT_CODE;
			$data['default']['POSCODE'] 	= $getdocapproval->POSCODE;
			$data['default']['APPROVER_1'] 	= $getdocapproval->APPROVER_1;
			$data['default']['APPROVER_2'] 	= $getdocapproval->APPROVER_2;
			$data['default']['APPROVER_3'] 	= $getdocapproval->APPROVER_3;
			$data['default']['APPROVER_4'] 	= $getdocapproval->APPROVER_4;
			$data['default']['APPROVER_5'] 	= $getdocapproval->APPROVER_5; 
			$data['default']['APPLIMIT_1'] 	= $getdocapproval->APPLIMIT_1;
			$data['default']['APPLIMIT_2'] 	= $getdocapproval->APPLIMIT_2;
			$data['default']['APPLIMIT_3'] 	= $getdocapproval->APPLIMIT_3;
			$data['default']['APPLIMIT_4'] 	= $getdocapproval->APPLIMIT_4;
			$data['default']['APPLIMIT_5'] 	= $getdocapproval->APPLIMIT_5;
			$data['default']['CREATED_BY'] 	= $getdocapproval->CREATED_BY;
			
			$this->load->view('v_setting/v_docapproval/docapproval_form', $data);
		}
		else
		{
			redirect('login');
		}
	}
	
	function update_process()
	{
		$this->load->model('m_setting/m_docapproval/m_docapproval', '', TRUE);
		
		$LangID 	= $this->session->userdata['LangID'];
		$DOCAPP_ID	= $this->input->post('DOCAPP_ID');
		
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();
			
			$MENU_NAME		= '';
			$MENU_CODE		= $this->input->post('MENU_CODE');
			$MDEPT_CODE		= $this->input->post('MDEPT_CODE');
			$POSCODE		= $this->input->post('POSCODE');
			$sqlGetMENU		= "SELECT menu_name_$LangID AS menu_name FROM tbl_menu WHERE MENU_CODE = '$MENU_CODE'";
			$resGetMENU		= $this->db->query($sqlGetMENU)->result();
			foreach($resGetMENU as $rowMENU) :		
				$MENU_NAME	= $rowMENU->menu_name;
			endforeach;
			
			$APPROVER_1		= $this->input->post('APPROVER_1');
			$APPROVER_2		= $this->input->post('APPROVER_2');
			$APPROVER_3		= $this->input->post('APPROVER_3');
			$APPROVER_4		= $this->input->post('APPROVER_4');
			$APPROVER_5		= $this->input->post('APPROVER_5');
			$DOCCODE		= $this->input->post('DOCCODE');
			$PRJCODE		= $this->input->post('PRJCODE');
			$statFORM		= $this->input->post('statFORM');

			if($statFORM == 1)
			{
				if($APPROVER_5 != '')
					$MAX_STEP = 5;
				elseif($APPROVER_4 != '')
					$MAX_STEP = 4;
				elseif($APPROVER_3 != '')
					$MAX_STEP = 3;
				elseif($APPROVER_2 != '')
					$MAX_STEP = 2;
				elseif($APPROVER_1 != '')
					$MAX_STEP = 1;
					
				$UpdDocApp 	= array('DOCCODE'		=> $DOCCODE,
									'PRJCODE' 		=> $PRJCODE,
									'DOCAPP_NAME' 	=> $MENU_NAME,
									'DOCAPP_TYPE' 	=> $this->input->post('DOCAPP_TYPE'),
									'MENU_CODE' 	=> $this->input->post('MENU_CODE'),
									'MDEPT_CODE' 	=> $this->input->post('MDEPT_CODE'),
									'POSCODE' 		=> $this->input->post('POSCODE'),
									'APPROVER_1'	=> $this->input->post('APPROVER_1'),
									'APPLIMIT_1'	=> $this->input->post('APPLIMIT_1'),
									'APPROVER_2'	=> $this->input->post('APPROVER_2'),
									'APPLIMIT_2'	=> $this->input->post('APPLIMIT_2'),
									'APPROVER_3'	=> $this->input->post('APPROVER_3'),
									'APPLIMIT_3'	=> $this->input->post('APPLIMIT_3'),
									'APPROVER_4'	=> $this->input->post('APPROVER_4'),
									'APPLIMIT_4'	=> $this->input->post('APPLIMIT_4'),
									'APPROVER_5'	=> $this->input->post('APPROVER_5'),
									'APPLIMIT_5'	=> $this->input->post('APPLIMIT_5'),
									'MAX_STEP'		=> $MAX_STEP,
									'CREATED_BY'	=> $this->input->post('CREATED_BY'));
				$this->m_docapproval->update($DOCAPP_ID, $UpdDocApp);
				
				// DELETE DETAIL BY CODE
				$delSett		= "DELETE FROM tbl_docstepapp_det WHERE DOCCODE = '$DOCCODE' AND PRJCODE = '$PRJCODE'";
				$this->db->query($delSett);
				
				// START : Create to detail
					if($APPROVER_1 != '')
					{
						$MAX_STEP		= 1;
						$InsDocAppDet 	= array('DOCCODE' 		=> $DOCCODE,
												'PRJCODE' 		=> $PRJCODE,
												'DOCAPP_NAME' 	=> $MENU_NAME,
												'DOCAPP_TYPE' 	=> $this->input->post('DOCAPP_TYPE'),
												'MENU_CODE' 	=> $this->input->post('MENU_CODE'),
												'MDEPT_CODE' 	=> $this->input->post('MDEPT_CODE'),
												'POSCODE' 		=> $this->input->post('POSCODE'),
												'APPROVER_1'	=> $this->input->post('APPROVER_1'),
												'APPLIMIT_1'	=> $this->input->post('APPLIMIT_1'),
												'APP_STEP'		=> 1);	
						$this->m_docapproval->add1($InsDocAppDet);
						
						$UpdDocAppDet 	= array('MAX_STEP' 		=> 1);	
						$this->m_docapproval->updateDet($UpdDocAppDet, $DOCCODE);
					}
					if($APPROVER_2 != '')
					{
						$MAX_STEP		= 2;
						$InsDocAppDet 	= array('DOCCODE' 		=> $DOCCODE,
												'PRJCODE' 		=> $PRJCODE,
												'DOCAPP_NAME' 	=> $MENU_NAME,
												'DOCAPP_TYPE' 	=> $this->input->post('DOCAPP_TYPE'),
												'MENU_CODE' 	=> $this->input->post('MENU_CODE'),
												'MDEPT_CODE' 	=> $this->input->post('MDEPT_CODE'),
												'POSCODE' 		=> $this->input->post('POSCODE'),
												'APPROVER_1'	=> $this->input->post('APPROVER_2'),
												'APPLIMIT_1'	=> $this->input->post('APPLIMIT_2'),
												'APP_STEP'		=> 2);	
						$this->m_docapproval->add1($InsDocAppDet);
						
						$UpdDocAppDet 	= array('MAX_STEP' 		=> 2);	
						$this->m_docapproval->updateDet($UpdDocAppDet, $DOCCODE);
					}
					if($APPROVER_3 != '')
					{
						$MAX_STEP		= 3;
						$InsDocAppDet 	= array('DOCCODE' 		=> $DOCCODE,
												'PRJCODE' 		=> $PRJCODE,
												'DOCAPP_NAME' 	=> $MENU_NAME,
												'DOCAPP_TYPE' 	=> $this->input->post('DOCAPP_TYPE'),
												'MENU_CODE' 	=> $this->input->post('MENU_CODE'),
												'MDEPT_CODE' 	=> $this->input->post('MDEPT_CODE'),
												'POSCODE' 		=> $this->input->post('POSCODE'),
												'APPROVER_1'	=> $this->input->post('APPROVER_3'),
												'APPLIMIT_1'	=> $this->input->post('APPLIMIT_3'),
												'APP_STEP'		=> 3);	
						$this->m_docapproval->add1($InsDocAppDet);
						
						$UpdDocAppDet 	= array('MAX_STEP' 		=> 3);	
						$this->m_docapproval->updateDet($UpdDocAppDet, $DOCCODE);
					}
					if($APPROVER_4 != '')
					{
						$MAX_STEP		= 4;
						$InsDocAppDet 	= array('DOCCODE' 		=> $DOCCODE,
												'PRJCODE' 		=> $PRJCODE,
												'DOCAPP_NAME' 	=> $MENU_NAME,
												'DOCAPP_TYPE' 	=> $this->input->post('DOCAPP_TYPE'),
												'MENU_CODE' 	=> $this->input->post('MENU_CODE'),
												'MDEPT_CODE' 	=> $this->input->post('MDEPT_CODE'),
												'POSCODE' 		=> $this->input->post('POSCODE'),
												'APPROVER_1'	=> $this->input->post('APPROVER_4'),
												'APPLIMIT_1'	=> $this->input->post('APPLIMIT_4'),
												'APP_STEP'		=> 4);	
						$this->m_docapproval->add1($InsDocAppDet);
						
						$UpdDocAppDet 	= array('MAX_STEP' 		=> 4);	
						$this->m_docapproval->updateDet($UpdDocAppDet, $DOCCODE);
					}
					if($APPROVER_5 != '')
					{
						$MAX_STEP		= 5;
						$InsDocAppDet 	= array('DOCCODE' 		=> $DOCCODE,
												'PRJCODE' 		=> $PRJCODE,
												'DOCAPP_NAME' 	=> $MENU_NAME,
												'DOCAPP_TYPE' 	=> $this->input->post('DOCAPP_TYPE'),
												'MENU_CODE' 	=> $this->input->post('MENU_CODE'),
												'MDEPT_CODE' 	=> $this->input->post('MDEPT_CODE'),
												'POSCODE' 		=> $this->input->post('POSCODE'),
												'APPROVER_1'	=> $this->input->post('APPROVER_5'),
												'APPLIMIT_1'	=> $this->input->post('APPLIMIT_5'),
												'APP_STEP'		=> 5);	
						$this->m_docapproval->add1($InsDocAppDet);
						
						$UpdDocAppDet 	= array('MAX_STEP' 		=> 5);	
						$this->m_docapproval->updateDet($UpdDocAppDet, $DOCCODE);
					}
			}
			elseif($statFORM == 2)
			{
				$delSSTEP1	= "DELETE FROM tbl_docstepapp WHERE DOCAPP_ID = $DOCAPP_ID";
				$this->db->query($delSSTEP1);
				
				$delSSTEP2	= "DELETE FROM tbl_docstepapp_det WHERE DOCCODE = $DOCAPP_ID";
				$this->db->query($delSSTEP2);
			}

			// CEK LAST STEP
				$s_MAX		= "SELECT MAX(APP_STEP) AS MAXSTEP FROM tbl_docstepapp_det WHERE PRJCODE = '$PRJCODE' AND MENU_CODE = '$MENU_CODE'";
				$r_MAX		= $this->db->query($s_MAX)->result();
				foreach($r_MAX as $rw_MAX) :		
					$MAXSTEP	= $rw_MAX->MAXSTEP;

					$s_UPD 		= "UPDATE tbl_docstepapp_det SET MAX_STEP = $MAXSTEP
									WHERE PRJCODE = '$PRJCODE' AND MENU_CODE = '$MENU_CODE'";
					$this->db->query($s_UPD);
				endforeach;

			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			redirect('c_setting/c_docapproval/');
		}
		else
		{
			redirect('login');
		}
	}

	function delSettApp()
	{
		date_default_timezone_set("Asia/Jakarta");
		
		$collID 	= $_POST['collID'];
		$colExpl	= explode("~", $collID);
		$url 		= $colExpl[0];
        $DOCAPP_ID 	= $colExpl[1];

		$s_APP		= "SELECT DOCCODE, PRJCODE, MENU_CODE FROM tbl_docstepapp WHERE DOCAPP_ID = '$DOCAPP_ID'";
		$r_APP		= $this->db->query($s_APP)->result();
		foreach($r_APP as $rw_APP) :		
			$DOCCODE	= $rw_APP->DOCCODE;
			$PRJCODE	= $rw_APP->PRJCODE;
			$MENU_CODE	= $rw_APP->MENU_CODE;

	        $s_D1		= "DELETE FROM tbl_docstepapp WHERE DOCCODE = '$DOCCODE'";
	        $this->db->query($s_D1);

	        $s_D2		= "DELETE FROM tbl_docstepapp_det WHERE DOCCODE = '$DOCCODE'";
	        $this->db->query($s_D2);

			// CEK LAST STEP
				$s_MAX		= "SELECT MAX(APP_STEP) AS MAXSTEP FROM tbl_docstepapp_det WHERE PRJCODE = '$PRJCODE' AND MENU_CODE = '$MENU_CODE'";
				$r_MAX		= $this->db->query($s_MAX)->result();
				foreach($r_MAX as $rw_MAX) :		
					$MAXSTEP	= $rw_MAX->MAXSTEP;

					$s_UPD 		= "UPDATE tbl_docstepapp_det SET MAX_STEP = $MAXSTEP
									WHERE PRJCODE = '$PRJCODE' AND MENU_CODE = '$MENU_CODE'";
					$this->db->query($s_UPD);
				endforeach;
		endforeach;

        $LangID 	= $this->session->userdata['LangID'];
		if($LangID == 'IND')
		{
			$alert1	= "Pengaturan persetujuan telah dihapus.";
		}
		else
		{
			$alert1	= "Approval setting has been deleted.";
		}
		echo "$alert1";
	}

	function procCopyTOPRJ()
	{
		$SRCPRJCODE	= $this->input->post('PRJCODE');	
		$MENU_CODE	= $this->input->post('MENU_CODE');
		$DSTPRJCODE	= $this->input->post('TOPRJCODE');

		date_default_timezone_set("Asia/Jakarta");

		// DELETE STEP APP BEFORE HEADER & DETAIL
			$delSTEPHBF = "DELETE FROM tbl_docstepapp WHERE MENU_CODE = '$MENU_CODE' AND PRJCODE = '$DSTPRJCODE'";
			$this->db->query($delSTEPHBF);

			$delSTEPDBF = "DELETE FROM tbl_docstepapp_det WHERE MENU_CODE = '$MENU_CODE' AND PRJCODE = '$DSTPRJCODE'";
			$this->db->query($delSTEPDBF);
		// END DELETE STEP APP BEFORE HEADER & DETAIL

		// GET docstepapp HEADER SOURCE
			$getDocStepAPPH 	= "SELECT DOCCODE, PRJCODE, DOCAPP_TYPE, DOCAPP_NAME, MENU_CODE, MDEPT_CODE,
									APPROVER_1, APPROVER_2, APPROVER_3, APPROVER_4, APPROVER_5, MAX_STEP 
									FROM tbl_docstepapp WHERE MENU_CODE = '$MENU_CODE' AND PRJCODE = '$SRCPRJCODE'";
			$resDocStepAPPH		= $this->db->query($getDocStepAPPH);
			if($resDocStepAPPH->num_rows() > 0)
			{
				$rowSTEP = 0;
				foreach($resDocStepAPPH->result() as $rAPP):
					$rowSTEP 		= $rowSTEP + 1;
					$DOCCODE 		= $rAPP->DOCCODE;
					$DOCAPP_TYPE	= $rAPP->DOCAPP_TYPE;
					$DOCAPP_NAME	= $rAPP->DOCAPP_NAME;
					$MENU_CODE		= $rAPP->MENU_CODE;
					$MDEPT_CODE		= $rAPP->MDEPT_CODE;
					$APPROVER_1		= $rAPP->APPROVER_1;
					$APPROVER_2		= $rAPP->APPROVER_2;
					$APPROVER_3		= $rAPP->APPROVER_3;
					$APPROVER_4		= $rAPP->APPROVER_4;
					$APPROVER_5		= $rAPP->APPROVER_5;
					$MAX_STEP		= $rAPP->MAX_STEP;

					if($rowSTEP == 1)
					{
						$NEWDOCCODE		= date('YmdHis');
						// NEW COPY STEP APP HEADER
							$newSTEPAPPH = "INSERT INTO tbl_docstepapp (DOCCODE, PRJCODE, DOCAPP_TYPE, DOCAPP_NAME, MENU_CODE, MDEPT_CODE,
											APPROVER_1, APPROVER_2, APPROVER_3, APPROVER_4, APPROVER_5, MAX_STEP) 
											VALUES ('$NEWDOCCODE', '$DSTPRJCODE', '$DOCAPP_TYPE', '$DOCAPP_NAME', '$MENU_CODE', '$MDEPT_CODE',
											'$APPROVER_1', '$APPROVER_2', '$APPROVER_3', '$APPROVER_4', '$APPROVER_5', '$MAX_STEP')";
							$this->db->query($newSTEPAPPH);
						// END NEW COPY STEP APP HEADER
					}
					else
					{
						$date		= date_create(date('YmdHis'));
						date_add($date, date_interval_create_from_date_string("1 seconds")); // ADD 1 second
						$NEWDOCCODE	= date_format($date,"YmdHis");
						// NEW COPY STEP APP HEADER
							$newSTEPAPPH = "INSERT INTO tbl_docstepapp (DOCCODE, PRJCODE, DOCAPP_TYPE, DOCAPP_NAME, MENU_CODE, MDEPT_CODE,
											APPROVER_1, APPROVER_2, APPROVER_3, APPROVER_4, APPROVER_5, MAX_STEP) 
											VALUES ('$NEWDOCCODE', '$DSTPRJCODE', '$DOCAPP_TYPE', '$DOCAPP_NAME', '$MENU_CODE', '$MDEPT_CODE'
											'$APPROVER_1', '$APPROVER_2', '$APPROVER_3', '$APPROVER_4', '$APPROVER_5', '$MAX_STEP')";
							$this->db->query($newSTEPAPPH);
						// END NEW COPY STEP APP HEADER
					}

				endforeach;
			}
		// END GET docstepapp HEADER SOURCE

		// GET docstepapp HEADER DEST
			$getDocStepAPPDST 	= "SELECT DOCCODE, PRJCODE, DOCAPP_TYPE, DOCAPP_NAME, MENU_CODE, MDEPT_CODE,
									APPROVER_1, APPROVER_2, APPROVER_3, APPROVER_4, APPROVER_5, MAX_STEP 
									FROM tbl_docstepapp WHERE MENU_CODE = '$MENU_CODE' AND PRJCODE = '$DSTPRJCODE'";
			$resDocStepAPPDST		= $this->db->query($getDocStepAPPDST);
			if($resDocStepAPPDST->num_rows() > 0)
			{
				foreach($resDocStepAPPDST->result() as $rAPPDST):
					$DOCCODEDST		= $rAPPDST->DOCCODE;
					$PRJCODEDST		= $rAPPDST->PRJCODE;
					$DOCAPP_TYPE	= $rAPPDST->DOCAPP_TYPE;
					$DOCAPP_NAME	= $rAPPDST->DOCAPP_NAME;
					$MENU_CODE		= $rAPPDST->MENU_CODE;
					$MDEPT_CODE		= $rAPPDST->MDEPT_CODE;
					$APPROVER_1		= $rAPPDST->APPROVER_1;
					$APPROVER_2		= $rAPPDST->APPROVER_2;
					$APPROVER_3		= $rAPPDST->APPROVER_3;
					$APPROVER_4		= $rAPPDST->APPROVER_4;
					$APPROVER_5		= $rAPPDST->APPROVER_5;
					$MAX_STEP		= $rAPPDST->MAX_STEP;

					if($APPROVER_1 != '')
					{
						$newSTEPAPPD = "INSERT INTO tbl_docstepapp_det (DOCCODE, PRJCODE, DOCAPP_TYPE, DOCAPP_NAME, MENU_CODE, MDEPT_CODE, APPROVER_1, APP_STEP, MAX_STEP) 
										SELECT '$DOCCODEDST', '$PRJCODEDST', DOCAPP_TYPE, DOCAPP_NAME, MENU_CODE, MDEPT_CODE, APPROVER_1, APP_STEP, MAX_STEP
										FROM tbl_docstepapp_det
										WHERE MENU_CODE = '$MENU_CODE' AND PRJCODE = '$SRCPRJCODE' AND APPROVER_1 = '$APPROVER_1'";
						$this->db->query($newSTEPAPPD);
					}

					if($APPROVER_2 != '')
					{
						$newSTEPAPPD = "INSERT INTO tbl_docstepapp_det (DOCCODE, PRJCODE, DOCAPP_TYPE, DOCAPP_NAME, MENU_CODE, MDEPT_CODE, APPROVER_1, APP_STEP, MAX_STEP) 
										SELECT '$DOCCODEDST', '$PRJCODEDST', DOCAPP_TYPE, DOCAPP_NAME, MENU_CODE, MDEPT_CODE, APPROVER_1, APP_STEP, MAX_STEP
										FROM tbl_docstepapp_det
										WHERE MENU_CODE = '$MENU_CODE' AND PRJCODE = '$SRCPRJCODE' AND APPROVER_1 = '$APPROVER_2'";
						$this->db->query($newSTEPAPPD);
					}

					if($APPROVER_3 != '')
					{
						$newSTEPAPPD = "INSERT INTO tbl_docstepapp_det (DOCCODE, PRJCODE, DOCAPP_TYPE, DOCAPP_NAME, MENU_CODE, MDEPT_CODE, APPROVER_1, APP_STEP, MAX_STEP) 
										SELECT '$DOCCODEDST', '$PRJCODEDST', DOCAPP_TYPE, DOCAPP_NAME, MENU_CODE, MDEPT_CODE, APPROVER_1, APP_STEP, MAX_STEP
										FROM tbl_docstepapp_det
										WHERE MENU_CODE = '$MENU_CODE' AND PRJCODE = '$SRCPRJCODE' AND APPROVER_1 = '$APPROVER_3'";
						$this->db->query($newSTEPAPPD);
					}

					if($APPROVER_4 != '')
					{
						$newSTEPAPPD = "INSERT INTO tbl_docstepapp_det (DOCCODE, PRJCODE, DOCAPP_TYPE, DOCAPP_NAME, MENU_CODE, MDEPT_CODE, APPROVER_1, APP_STEP, MAX_STEP) 
										SELECT '$DOCCODEDST', '$PRJCODEDST', DOCAPP_TYPE, DOCAPP_NAME, MENU_CODE, MDEPT_CODE, APPROVER_1, APP_STEP, MAX_STEP
										FROM tbl_docstepapp_det
										WHERE MENU_CODE = '$MENU_CODE' AND PRJCODE = '$SRCPRJCODE' AND APPROVER_1 = '$APPROVER_4'";
						$this->db->query($newSTEPAPPD);
					}

					if($APPROVER_5 != '')
					{
						$newSTEPAPPD = "INSERT INTO tbl_docstepapp_det (DOCCODE, PRJCODE, DOCAPP_TYPE, DOCAPP_NAME, MENU_CODE, MDEPT_CODE, APPROVER_1, APP_STEP, MAX_STEP) 
										SELECT '$DOCCODEDST', '$PRJCODEDST', DOCAPP_TYPE, DOCAPP_NAME, MENU_CODE, MDEPT_CODE, APPROVER_1, APP_STEP, MAX_STEP
										FROM tbl_docstepapp_det
										WHERE MENU_CODE = '$MENU_CODE' AND PRJCODE = '$SRCPRJCODE' AND APPROVER_1 = '$APPROVER_5'";
						$this->db->query($newSTEPAPPD);
					}

				endforeach;

				echo json_encode($resDocStepAPPDST->result());
			}
		// END GET docstepapp HEADER DEST
	}
}