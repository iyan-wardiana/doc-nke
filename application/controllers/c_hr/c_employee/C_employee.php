<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 31 Oktober 2017
 * File Name	= C_employee.php
 * Location		= -
*/
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_employee extends CI_Controller
{
  	function __construct() // GOOD
	{
		parent::__construct();
		
		$this->load->model('m_hr/m_employee/m_employee', '', TRUE);
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
	
 	function index()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_hr/c_employee/c_employee/iN4x_3Mp/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function iN4x_3Mp()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$EmpID 				= $this->session->userdata('Emp_ID');			
			$data['title'] 		= $appName;
			$data["MenuCode"] 	= 'MN324';
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN324';
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
			
			$this->load->view('v_hr/v_employee/employee', $data);
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

		$LangID     = $this->session->userdata['LangID'];
        
        $sqlTransl      = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
        $resTransl      = $this->db->query($sqlTransl)->result();
        foreach($resTransl as $rowTransl) :
            $TranslCode = $rowTransl->MLANG_CODE;
            $LangTransl = $rowTransl->LangTransl;

    		if($TranslCode == 'Director')$Director = $LangTransl;
            if($TranslCode == 'Department')$Department = $LangTransl;
            if($TranslCode == 'JoinDate')$JoinDate = $LangTransl;
            if($TranslCode == 'Location')$Location = $LangTransl;
            if($TranslCode == 'Address')$Address = $LangTransl;
    		if($TranslCode == 'Active')$Active = $LangTransl;
    		if($TranslCode == 'Inactive')$Inactive = $LangTransl;
    		if($TranslCode == 'Contact')$Contact = $LangTransl;
    		if($TranslCode == 'CompanyName')$CompanyName = $LangTransl;
    		if($TranslCode == 'Budget')$Budget = $LangTransl;
    		if($TranslCode == 'Phone')$Phone = $LangTransl;
    		if($TranslCode == 'Notes')$Notes = $LangTransl;
        endforeach;

		$PRJCODE		= "";
		
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
									"Emp_ID",
									"First_Name", 
									"Birth_Place",
									"Pos_Code",
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
			$num_rows 		= $this->m_employee->get_AllDataC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_employee->get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$Emp_ID			= $dataI['Emp_ID'];            
				$First_Name		= $dataI['First_Name'];
				$Middle_Name	= $dataI['Middle_Name'];
				$Last_Name		= $dataI['Last_Name'];
				$CompleteNm		= "$First_Name&nbsp;$Last_Name";
				$Birth_Place	= $dataI['Birth_Place'];
				$Date_Of_Birth	= strftime('%d %B %Y', strtotime($dataI['Date_Of_Birth']));
				$Gol_Code		= $dataI['Gol_Code'];
				$Pos_Code		= $dataI['Pos_Code'];
				$Emp_DeptCode	= $dataI['Emp_DeptCode'];
				$Mobile_Phone	= $dataI['Mobile_Phone'];
				$Email			= $dataI['Email'];
				$Address1		= $dataI['Address1'];
				$city1			= $dataI['city1'];
				$country1		= $dataI['country1'];
				$State1			= $dataI['State1'];
				$zipcode1		= $dataI['zipcode1'];
				$Emp_Status		= $dataI['Emp_Status'];
				$Joint_Date		= strftime('%d %B %Y', strtotime($dataI['Joint_Date']));
				$EMPG_RANK		= '';

				// GET ADDRESS
					$EMPADD1	= "";
					if($city1 != '')
						$EMPADD1	= $city1." - ";

					$EMPADD2	= "";
					if($country1 != '')
						$EMPADD2	= $EMPADD1.$country1."<br>";

					$EMPADD3 	= "-";
					if($Address1 != '')
						$EMPADD3	= $EMPADD2.$Address1;

					$EMPTLPD	= "-";
					if($Mobile_Phone != '')
						$EMPTLPD	= $Mobile_Phone;

					$EMPMAILD	= "-";
					if($Email != '')
						$EMPMAILD	= $Email;

				// DETAIL GOLONGAN
					/*$sqlGol			= "SELECT EMPG_CODE, EMPG_RANK FROM tbl_employee_gol WHERE EMPG_CHILD = '$Gol_Code' LIMIT 1";
					$resGol			= $this->db->query($sqlGol)->result();
					foreach($resGol as $rowGol) :
						$EMPG_CODE 	= $rowGol->EMPG_CODE;
						$EMPG_RANK 	= $rowGol->EMPG_RANK;
					endforeach;*/
				
				// DETAIL POSISI
					/*$POSF_CODE		= $dataI['Pos_Code'];
					$POSF_PARENT	= '';
					$POSF_NAME 		= '';
					$sqlPos			= "SELECT POSF_PARENT, POSF_CODE, POSF_NAME
										FROM tbl_position_func WHERE POSF_CODE = '$POSF_CODE' LIMIT 1";
					$resPos			= $this->db->query($sqlPos)->result();
					foreach($resPos as $rowPos) :
						$POSF_PARENT= $rowPos->POSF_PARENT;
						$POSF_CODE 	= $rowPos->POSF_CODE;
						$POSF_NAME 	= $rowPos->POSF_NAME;
					endforeach;*/
				
					/*$POSF_PARENT	= $POSF_PARENT;
					$POSS_NAME 		= '';
					$sqlPos			= "SELECT POSS_CODE, POSS_NAME
										FROM tbl_position_str WHERE POSS_CODE = '$POSF_PARENT' LIMIT 1";
					$resPos			= $this->db->query($sqlPos)->result();
					foreach($resPos as $rowPos) :
						$POSS_CODE 	= $rowPos->POSS_CODE;
						$POSS_NAME 	= $rowPos->POSS_NAME;
					endforeach;*/

					$POSS_NAME		= "-";
					$sqlPoss		= "SELECT POSS_NAME FROM tbl_position_str WHERE POSS_CODE = '$Emp_DeptCode' LIMIT 1";
					$resPoss		= $this->db->query($sqlPoss)->result();
					foreach($resPoss as $rowPoss) :
						$POSS_NAME 	= $rowPoss->POSS_NAME;
					endforeach;
				
				// IMG EMP
					$imgemp_filenameX = "username.jpg";
					$sqlGetIMG		= "SELECT imgemp_filename, imgemp_filenameX FROM tbl_employee_img WHERE imgemp_empid = '$Emp_ID'";
					$resGetIMG 		= $this->db->query($sqlGetIMG)->result();
					foreach($resGetIMG as $rowGIMG) :
						$imgemp_filename 	= $rowGIMG ->imgemp_filename;
						$imgemp_filenameX 	= $rowGIMG ->imgemp_filenameX;
					endforeach;
					
					$imgLoc				= base_url('assets/AdminLTE-2.0.5/emp_image/'.$Emp_ID.'/'.$imgemp_filenameX);
					if (!file_exists('assets/AdminLTE-2.0.5/emp_image/'.$Emp_ID))
					{
						$imgLoc			= base_url('assets/AdminLTE-2.0.5/emp_image/username.jpg');
					}
				
				// URL SETTING
					$empSetting		= site_url('c_hr/c_employee/c_employee/i4x3mp_4p4/?id='.$this->url_encryption_helper->encode_url($Emp_ID));
					$empUpdate		= site_url('c_hr/c_employee/c_employee/i4x3mp_4p4/?id='.$this->url_encryption_helper->encode_url($Emp_ID));
					$empProject		= site_url('c_hr/c_employee/c_employee/employee_project/?id='.$this->url_encryption_helper->encode_url($Emp_ID));
					$empAuth_items	= site_url('c_hr/c_employee/c_employee/employee_auth_items/?id='.$this->url_encryption_helper->encode_url($Emp_ID));
					$empAuthorize	= site_url('c_hr/c_employee/c_employee/employee_authorization/?id='.$this->url_encryption_helper->encode_url($Emp_ID));
					$secDashURL		= site_url('c_hr/c_employee/c_employee/employee_dashboard/?id='.$this->url_encryption_helper->encode_url($Emp_ID));
					$secDocURL		= site_url('c_hr/c_employee/c_employee/employee_auth_doc/?id='.$this->url_encryption_helper->encode_url($Emp_ID));
									
					$secSetDash		= "<a href='".$secDashURL."' data-skin='skin-green' class='btn btn-info btn-xs' title='Dashboard Setting'>
											<i class='fa fa-bar-chart-o'></i>
									   </a>";
					$secSetDoc		= "<a href='".$secDocURL."' data-skin='skin-green' class='btn btn-warning btn-xs' title='Doc. Auth. Setting'>
											<i class='fa fa-book'></i>
									   </a>";
					$secSetPrj		= "<a href='".$empProject."' data-skin='skin-green' class='btn btn-danger btn-xs' title='Project Auth.'>
											<i class='fa fa-building-o'></i>
									   </a>";
					$secSetItem		= "<a href='".$empAuth_items."' data-skin='skin-green' class='btn bg-orange btn-xs' title='Items Auth.'>
											<i class='fa fa-cubes'></i>
									   </a>";
					$secSetAuth		= "<a href='".$empAuthorize."' data-skin='skin-green' class='btn btn-success btn-xs' title='Menu Auth.'>
											<i class='fa fa-check'></i>
									   </a>";
					$secempUpd		= "&nbsp;&nbsp;<a href='".$empUpdate."'><i class='glyphicon glyphicon-pencil'></i></a>";

					$secImg			= "<img class='direct-chat-img' src='".$imgLoc."' style='border:groove; border-color:#0C3' >";
						
					$secPrint1		= site_url('c_finance/c_cpa70d18/printdocument/?id='.$this->url_encryption_helper->encode_url($Emp_ID));   
					$secPrint		= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint1."'>
									   <label style='white-space:nowrap'>
									   <a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
											<i class='glyphicon glyphicon-print'></i>
										</a>
										</label>";
				
				// EMP STATUS
					if($Emp_Status == 1)
					{
						$isActDesc 	= $Active;
						$STATCOL	= 'success';
					}
					else
					{
						$isActDesc 	= $Inactive;
						$STATCOL	= 'danger';
					}
								
				$output['data'][] 	= array($noU.".",
										  	"<div style='white-space:nowrap' style='background-color: transparent'>
										  		<p><strong style='font-size:13px'>".$CompleteNm."</strong></p>
										  		<div class='box-comments' style='background-color: transparent'>
											  		<div class='box-comment'>
										                <!-- User image -->
										                <img class='img-circle img-sm' src='".$imgLoc."' alt='User Image'>
										                <div class='comment-text'>
										                   	<span class='username'>
										                        ".ucwords($Emp_ID).$secempUpd."
										                    </span>
									                  		".$Birth_Place.", ".$Date_Of_Birth."
										                </div>
										            </div>
									            </div>
									  	  	</div>",
										  	"<strong><i class='fa fa-sitemap margin-r-5'></i>".$Department."</strong>
									  		<div>
										  		<p class='text-muted' style='margin-left: 18px'>".$POSS_NAME."</p>
										  	</div>
										  	<strong><i class='fa fa-calendar margin-r-5'></i>".$JoinDate."</strong>
									  		<div>
										  		<p class='text-muted' style='margin-left: 16px'>".$Joint_Date."</p>
										  	</div>",
										  	"<strong><i class='fa fa-map-marker margin-r-5'></i>".$Address."</strong>
									  		<div style='margin-left: 12px'>
										  		<p class='text-muted'>".$EMPADD3."</p>
										  	</div>
										  	<strong><i class='glyphicon glyphicon-phone-alt margin-r-5'></i>".$Phone." </strong>
									  		<div>
										  		<p class='text-muted' style='margin-left: 18px'>".$EMPTLPD."</p>
										  	</div>
										  	<strong><i class='fa fa-envelope margin-r-5'></i>E-mail</strong>
									  		<div>
										  		<p class='text-muted' style='margin-left: 18px'>".$EMPMAILD."</p>
										  	</div>",
										  	"<div style='text-align:center; white-space:nowrap'><span class='label label-".$STATCOL."' style='font-size:12px'>".$isActDesc."</span></div>",
										  	"<div style='white-space:nowrap'>$secSetDash $secSetPrj $secSetItem $secSetAuth</div>");
				
				$noU			= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function data_search()
	{
		$this->load->model('m_setting/m_employee/m_employee', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$idAppName	= $_GET['id'];
		$appName	= $this->url_encryption_helper->decode_url($idAppName);
		
		$data['title'] 		= $appName;
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
		$data["MenuCode"] 	= 'MN324';	
		
		if ($this->session->userdata('login') == TRUE)
		{
			// START : PAGINATION - SEARCH
				$key		= $this->input->post('key');
				$page		= $this->input->get('per_page');
				$search		= array('Emp_ID'=> $key, 'First_Name'=> $key, 'Last_Name'=> $key);
				$siteURL	= site_url('c_hr/c_employee/c_employee/data_search/?id='.$this->url_encryption_helper->encode_url($appName));
				$baseURL	= base_url() . 'index.php/c_hr/c_employee/c_employee/iN4x_3Mp/?id=';
				$totalrows	= $this->m_employee->count_all_emp_src($search);
				
				$this->load->library('pagination');
				
				$batas		= 10;
				if(!$page):
				   $offset = 0;
				else:
				   $offset = $page;
				endif;
				
				$data['search_action']		= $siteURL;
				$config['page_query_string']= TRUE;
				$config['base_url'] 		= $baseURL;
				
				$config['total_rows'] 		= $this->m_employee->count_all_emp_src($search);
				$config['per_page'] 		= $batas;
				$config['uri_segment'] 		= $page;
		 
				$config['full_tag_open'] 	= '<ul class="pagination">';
				$config['full_tag_close'] 	= '</ul>';
				$config['first_link'] 		= '&laquo; First';
				$config['first_tag_open'] 	= '<li class="prev page">';
				$config['first_tag_close'] 	= '</li>';
		 
				$config['last_link'] 		= 'Last &raquo;';
				$config['last_tag_open'] 	= '<li class="next page">';
				$config['last_tag_close'] 	= '</li>';
		 
				$config['next_link'] 		= 'Next &rarr;';
				$config['next_tag_open'] 	= '<li class="next page">';
				$config['next_tag_close'] 	= '</li>';
		 
				$config['prev_link']		= '&larr; Prev';
				$config['prev_tag_open'] 	= '<li class="prev page">';
				$config['prev_tag_close'] 	= '</li>';
		 
				$config['cur_tag_open'] 	= '<li class="current"><a href="">';
				$config['cur_tag_close'] 	= '</a></li>';
		 
				$config['num_tag_open'] 	= '<li class="page">';
				$config['num_tag_close'] 	= '</li>';
				$this->pagination->initialize($config);
				$data['paging']				= $this->pagination->create_links();
				$data['jlhpage']			= $page;
				$data['key']				= $key;
			// START : PAGINATION - SEARCH
			
			$data['viewdata'] 		= $this->m_employee->get_all_emp($batas,$offset,$search);
			
			$this->load->view('v_hr/v_employee/employee', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function add()
	{
		$this->load->model('m_hr/m_employee/m_employee', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			$data['showSetting']	= 1;
			$data['form_action']	= site_url('c_hr/c_employee/c_employee/add_process');
			$data['backURL'] 		= site_url('c_hr/c_employee/c_employee/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$MenuCode 				= 'MN324';
			$data["MenuCode"] 		= 'MN324';
			$data['viewDocPattern'] = $this->m_employee->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN324';
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

			// GET TOTAL
			/*$data['TotACT'] 	= $this->m_employee->getCount_ACT();
			$data['TotNACT'] 	= $this->m_employee->getCount_NACT();
			$data['TotNEW'] 	= $this->m_employee->getCount_NEW();
			$data['TotBOD'] 	= $this->m_employee->getCount_BOD();
			$data['TotGM'] 		= $this->m_employee->getCount_GM();
			$data['TotMNG'] 	= $this->m_employee->getCount_MNG();
			$data['TotKEPU'] 	= $this->m_employee->getCount_KEPU();
			$data['TotPM'] 		= $this->m_employee->getCount_PM();
			$data['TotKU'] 		= $this->m_employee->getCount_KU();
			$data['TotSM'] 		= $this->m_employee->getCount_SM();
			$data['TotSPEC'] 	= $this->m_employee->getCount_SPEC();
			$data['TotSTF'] 	= $this->m_employee->getCount_STF();
			$data['TotNSTF'] 	= $this->m_employee->getCount_NSTF();*/
			
			$data['PositionC'] 		= $this->m_employee->getCount_position();		
			$data['GetPosition'] 	= $this->m_employee->get_position()->result();
			
			$this->load->view('v_hr/v_employee/employee_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function getAge($newDate)
	{
		$today 		= new DateTime('today');
		$splitCode 	= explode("~", $newDate);
		$month		= $splitCode[0];
		$day		= $splitCode[1];
		$year		= $splitCode[2];
		$birthDate	= "$year-$month-$day";
		$birthDt 	= new DateTime($birthDate);
		$y 			= $today->diff($birthDt)->y;
		echo $y;
	}
	
	function add_process() // USE
	{
		date_default_timezone_set("Asia/Jakarta");
		$this->load->model('m_hr/m_employee/m_employee', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();
			
			$PassEncryp		= md5($this->input->post('log_password'));
			$Date_Of_Birth	= date('Y-m-d',strtotime($this->input->post('Date_Of_Birth')));
			$Emp_ID			= $this->input->post('Emp_ID');
			$First_Name		= addslashes($this->input->post('First_Name'));
			$Last_Name		= addslashes($this->input->post('Last_Name'));
			$compName		= "$First_Name $Last_Name";
			$theUsername	= $this->input->post('log_username');
			$thePassword	= $this->input->post('log_password');
			
			$EmployeeStatus	= $this->input->post('EmployeeStatus');
			$TaxStstus		= $this->input->post('TaxStstus');
			
			//echo $EmployeeStatus;
			//return false;
			
			$EMAIL 				= $this->input->post('Email');
				
			$employee 			= array('Emp_ID' 			=> $this->input->post('Emp_ID'),
										'Gol_Code'			=> $this->input->post('Gol_Code'),
										'Pos_Code'			=> $this->input->post('Pos_Code'),
										'EmpNoIdentity'		=> $this->input->post('EmpNoIdentity'),
										'First_Name'		=> addslashes($this->input->post('First_Name')),
										'Middle_Name'		=> addslashes($this->input->post('Middle_Name')),
										'Last_Name'			=> addslashes($this->input->post('Last_Name')),
										'Birth_Place'		=> addslashes($this->input->post('Birth_Place')),
										'Date_Of_Birth'		=> $Date_Of_Birth,
										'gender'			=> $this->input->post('gender'),
										'Religion'			=> $this->input->post('Religion'),
										'Marital_Status'	=> $this->input->post('Marital_Status'),
										'Email		'		=> $this->input->post('Email'),
										'Mobile_Phone'		=> $this->input->post('Mobile_Phone'),
										'Address1'			=> addslashes($this->input->post('Address1')),
										'city1'				=> addslashes($this->input->post('city1')),
										'country1'			=> addslashes($this->input->post('country1')),
										'State1'			=> addslashes($this->input->post('State1')),
										'Emp_Location'		=> $this->input->post('Emp_Location'),
										'Emp_Status'		=> $this->input->post('Employee_status'),
										'Employee_status'	=> $this->input->post('Employee_status'),
										'FlagUSER'			=> $this->input->post('FlagUSER'),
										'Emp_DeptCode'		=> $this->input->post('Pos_Code'),
										'Emp_Status'		=> $EmployeeStatus,
										'Tax_Status'		=> $TaxStstus,
										'Emp_Image'			=> 'username.jpg',
										'ACC_ID_AR'			=> $this->input->post('ACC_ID_AR'),
										'ACC_ID_AP'			=> $this->input->post('ACC_ID_AP'),
										
										'log_username'		=> $this->input->post('log_username'),
										'log_passHint'		=> $this->input->post('log_passHint'),
										'log_password'		=> $PassEncryp,
										'writeEMP'			=> 1,
										'editEMP'			=> 1,
										'readEMP'			=> 1);
						
			$employeeCp				= array('NK' 	=> $this->input->post('Emp_ID'),
										'U'			=> $this->input->post('log_username'),
										'P'			=> $this->input->post('log_password'),
										'UD'		=> date('Y-m-d H:i:s'));
						
			$employeeImg			= array('imgemp_empid' 	=> $this->input->post('Emp_ID'),
										'imgemp_filename'	=> 'username',
										'imgemp_filenameX'	=> 'username.jpg');
						
			$employeePRJ			= array('Emp_ID' 	=> $this->input->post('Emp_ID'),
										'proj_Code'		=> 'KTR');
										
			// ADD user menu E-Prosedure
			    $s_mn   = "SELECT order_id, menu_code FROM tbl_menu WHERE menu_code = 'MN268'";
			    $r_mn   = $this->db->query($s_mn);
			    if($r_mn->num_rows() > 0)
			    {
			        foreach($r_mn->result() as $rw_mn):
			            $isChkDetail    = $rw_mn->order_id;
			            $menu_code      = $rw_mn->menu_code;
			        endforeach;
			        // SAVE EMPLOYEE PROJECT
			        $usermenu   = ["isChkDetail" => $isChkDetail, "emp_id" => $this->input->post('Emp_ID'), "menu_code" => $menu_code];
			        $this->m_employee->add5($usermenu);
			    }
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN324';
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

			// SAVE EMPLOYEE
			$this->m_employee->add($employee);
			
			// SAVE PASSWORD
			$this->m_employee->add2($employeeCp);
			
			// SAVE EMPLOYEE IMG
			$this->m_employee->add3($employeeImg);
			
			// SAVE EMPLOYEE PROJECT
			$this->m_employee->add4($employeePRJ);
			
			// SAVE TO DASHBOARD
			$this->m_employee->updateDash();
			
			// END MAIL
			$this->m_employee->sendMail($Emp_ID, $compName, $theUsername, $thePassword, $EMAIL);
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			redirect('c_hr/c_employee/c_employee/');
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function i4x3mp_4p4()
	{
		$this->load->model('m_hr/m_employee/m_employee', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$Emp_ID		= $_GET['id'];
		$Emp_ID		= $this->url_encryption_helper->decode_url($Emp_ID);
		
		if ($this->session->userdata('login') == TRUE)
		{
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['showSetting']	= 1;
			$data['form_action']	= site_url('c_hr/c_employee/c_employee/update_process');
			$data['backURL'] 		= site_url('c_hr/c_employee/c_employee/?id='.$this->url_encryption_helper->encode_url($appName));			
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			$UserEmpDeptCode		= $this->session->userdata['Emp_DeptCode'];
			$data['Emp_ID']			= $Emp_ID;
			
			$MenuCode 				= 'MN324';
			$data["MenuCode"] 		= 'MN324';
			$data['viewDocPattern'] = $this->m_employee->getDataDocPat($MenuCode)->result();

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN324';
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
			// GET TOTAL
			/*$data['TotACT'] 	= $this->m_employee->getCount_ACT();
			$data['TotNACT'] 	= $this->m_employee->getCount_NACT();
			$data['TotNEW'] 	= $this->m_employee->getCount_NEW();
			$data['TotBOD'] 	= $this->m_employee->getCount_BOD();
			$data['TotGM'] 		= $this->m_employee->getCount_GM();
			$data['TotMNG'] 	= $this->m_employee->getCount_MNG();
			$data['TotKEPU'] 	= $this->m_employee->getCount_KEPU();
			$data['TotPM'] 		= $this->m_employee->getCount_PM();
			$data['TotKU'] 		= $this->m_employee->getCount_KU();
			$data['TotSM'] 		= $this->m_employee->getCount_SM();
			$data['TotSPEC'] 	= $this->m_employee->getCount_SPEC();
			$data['TotSTF'] 	= $this->m_employee->getCount_STF();
			$data['TotNSTF'] 	= $this->m_employee->getCount_NSTF();*/
			
			$data['GolC'] 			= $this->m_employee->getCount_gol();		
			$data['GetGol'] 		= $this->m_employee->get_gol()->result();
			
			$data['PositionC'] 		= $this->m_employee->getCount_position();		
			$data['GetPosition'] 	= $this->m_employee->get_position()->result();
			
			$this->load->view('v_hr/v_employee/employee_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process()
	{
		date_default_timezone_set("Asia/Jakarta");
		$this->load->model('m_hr/m_employee/m_employee', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();
			
			$username1				= $this->session->userdata['username'];
			$password1				= $this->session->userdata['password'];
			$username2				= $this->input->post('log_username');
			$password2				= $this->input->post('log_password');
			$DefEmp_ID1 			= $this->session->userdata['Emp_ID'];
			$DefEmp_ID2 			= $this->input->post('Emp_ID');
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title'] 		= 'Employee Data | Update Employee Data';
			$data['main_view'] 		= 'v_hr/v_employee/employee_form';
			$data['form_action']	= site_url('c_hr/c_employee/c_employee/update_process');
			$data['link'] 			= array('link_back' => anchor('c_hr/c_employee/c_employee/','<input type="button" name="btnCancel" id="btnCancel" value="Cancel" class="btn btn-danger" />', array('style' => 'text-decoration: none;')));
					
			$PassEncryp				= md5($this->input->post('log_password'));
			$Date_Of_Birth			= date('Y-m-d',strtotime($this->input->post('Date_Of_Birth')));
				
			$employee 				= array('Emp_ID' 			=> $this->input->post('Emp_ID'),
											'Gol_Code'			=> $this->input->post('Gol_Code'),
											'Pos_Code'			=> $this->input->post('Pos_Code'),
											'EmpNoIdentity'		=> $this->input->post('EmpNoIdentity'),
											'First_Name'		=> addslashes($this->input->post('First_Name')),
											'Middle_Name'		=> addslashes($this->input->post('Middle_Name')),
											'Last_Name'			=> addslashes($this->input->post('Last_Name')),
											'Birth_Place'		=> addslashes($this->input->post('Birth_Place')),
											'Date_Of_Birth'		=> $Date_Of_Birth,
											'gender'			=> $this->input->post('gender'),
											'Religion'			=> $this->input->post('Religion'),
											'Marital_Status'	=> $this->input->post('Marital_Status'),
											'Email'				=> $this->input->post('Email'),
											'Mobile_Phone'		=> $this->input->post('Mobile_Phone'),
											'Address1'			=> addslashes($this->input->post('Address1')),
											'city1'				=> $this->input->post('city1'),
											'country1'			=> $this->input->post('country1'),
											'State1'			=> $this->input->post('State1'),
											'Emp_Notes'			=> $this->input->post('Emp_Notes'),
											'ACC_ID_AR'			=> $this->input->post('ACC_ID_AR'),
											'ACC_ID_AP'			=> $this->input->post('ACC_ID_AP'),
											
											'Emp_Location'		=> $this->input->post('Emp_Location'),
											'Emp_Status'		=> $this->input->post('Employee_status'),
											'Employee_status'	=> $this->input->post('Employee_status'),
											'FlagUSER'			=> $this->input->post('FlagUSER'),
											'Emp_DeptCode'		=> $this->input->post('Pos_Code'),
											'Emp_Image'			=> $this->input->post('userfile'),
											
											'log_username'		=> $this->input->post('log_username'),
											'log_passHint'		=> $this->input->post('log_passHint'),
											'log_password'		=> $PassEncryp,
											'writeEMP'			=> $this->input->post('writeEMP'),
											'editEMP'			=> $this->input->post('editEMP'),
											'readEMP'			=> $this->input->post('readEMP'));
			// SAVE UPDATE EMPLOYEE	
			$this->m_employee->update($this->input->post('Emp_ID'), $employee);
						
			$employeeCp				= array('NK'	=> $this->input->post('Emp_ID'),
										'U'			=> $this->input->post('log_username'),
										'P'			=> $this->input->post('log_password'),
										'UD'		=> date('Y-m-d H:i:s'));
			// SAVE UPDATE PASSWORD	
			$this->m_employee->update2($this->input->post('Emp_ID'), $employeeCp);
										
			$employeeImg			= array('imgemp_empid' 	=> $this->input->post('Emp_ID'),
										'imgemp_filename'	=> $this->input->post('FileName'),
										'imgemp_filenameX'	=> $this->input->post('userfile'));
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= 'emp_upd';
				$MenuCode 		= 'MN324';
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

			// SAVE TO DASHBOARD
			$this->m_employee->updateDash();
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			/*if($DefEmp_ID1 == $DefEmp_ID2)
			{
				if($username1 != $username2 || $password1 != $password2)
				{
					$this->session->sess_destroy();
					//redirect('Auth', 'refresh');
					redirect('__l1y', 'refresh');
				}
			}*/
			redirect('c_hr/c_employee/c_employee/');
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function do_upload()
	{ 
		$this->load->model('m_hr/m_employee/m_employee', '', TRUE);
		$Emp_ID 					= $this->input->post('Emp_ID');
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;

		$data['task'] 				= 'add';
		$data['h2_title']			= 'Add Employee';
		$data['main_view'] 			= 'v_hr/v_employee/employee_form';
		
		// CEK FILE
        $file 						= $_FILES['userfile'];
		$nameFile					= $_FILES["userfile"]["name"];
		$ext 						= end((explode(".", $nameFile)));
       	$fileInpName 				= $this->input->post('FileName');

			
		if (!file_exists('assets/AdminLTE-2.0.5/emp_image/'.$Emp_ID))
		{
			mkdir('assets/AdminLTE-2.0.5/emp_image/'.$Emp_ID, 0777, true);
		}
		
		$file 						= $_FILES['userfile'];
		$file_name 					= $file['name'];
		$config['upload_path']   	= "assets/AdminLTE-2.0.5/emp_image/$Emp_ID/"; 
		$config['allowed_types']	= 'gif|jpg|png'; 
		$config['overwrite'] 		= TRUE;
		$config['max_size']     	= 1000000; 
		$config['max_width']    	= 10024; 
		$config['max_height']    	= 10000;  
		$config['file_name']       	= $file['name'];
		
        $this->load->library('upload', $config);
		
        if ( ! $this->upload->do_upload('userfile')) 
		{
			$data['Emp_ID']			= $Emp_ID;
			$data['task'] 			= 'edit';
         }
         else 
		 {
            $data['path']			= $file_name;
			$data['Emp_ID']			= $Emp_ID;
			$data['task'] 			= 'edit';
            $data['showSetting']	= 0;
            $this->m_employee->updateProfPict($Emp_ID, $nameFile, $fileInpName);
         }
         $data['backURL'] 			= site_url('c_hr/c_employee/c_employee/?id='.$this->url_encryption_helper->encode_url($appName));

         // START : UPDATE TO T-TRACK
			date_default_timezone_set("Asia/Jakarta");
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			$TTR_PRJCODE	= '';
			$TTR_REFDOC		= 'do_upload';
			$MenuCode 		= 'MN324';
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

         $this->load->view('v_hr/v_employee/employee_form', $data);
	}

	function employee_authorization($offset=0)
	{
		$this->load->model('m_hr/m_employee/m_employee', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$Emp_ID		= $_GET['id'];
		$Emp_ID		= $this->url_encryption_helper->decode_url($Emp_ID);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['Emp_ID'] 		= $Emp_ID;
			$data['title'] 			= $appName;
			$data['h2_title'] 		= 'Employee Authorization';
			$data["MenuCode"] 		= 'MN324';
			$data['form_action']	= site_url('c_hr/c_employee/c_employee/employee_authorization_process');
			$data['link'] 			= array('link_back' => anchor('c_hr/c_employee/c_employee/','<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));	
	 
			$data['viewallmenu'] = $this->m_employee->get_allmenu($Emp_ID, $offset)->result();
				
			$this->load->view('v_hr/v_employee/employee_auth_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function employee_authorization_process()
	{
		$this->load->model('m_hr/m_employee/m_employee', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();
			$Emp_ID1	= $this->input->post('Emp_ID1');
			$PRJSCATEG 	= $this->session->userdata['PRJSCATEG'];
			
			$this->m_employee->deleteAuthEmp($this->input->post('Emp_ID1'), $PRJSCATEG);		
			
			foreach($_POST['data'] as $d)
			{
				$menu_code = $d['menu_code']; 
				$chkDetail = $d['isChkDetail'];
				//echo "$chkDetail - $menu_code<br>";
				if($chkDetail > 0)
				{
					$d['USRMN_CAT']	= $PRJSCATEG;
					$this->db->insert('tusermenu',$d);				
				}
			}
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= 'auth_proc';
				$MenuCode 		= 'MN324';
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
			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$url			= site_url('c_hr/c_employee/c_employee/');
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function employee_project($offset=0)
	{
		$this->load->model('m_hr/m_employee/m_employee', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$Emp_ID		= $_GET['id'];
		$Emp_ID		= $this->url_encryption_helper->decode_url($Emp_ID);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['Emp_ID'] 		= $Emp_ID;
			$data['title'] 			= $appName;
			$data['h2_title'] 		= 'Employees Project';
			$data["MenuCode"] 		= 'MN324';
			
			// GET MENU DESC
				$mnCode				= 'MN324';
				$data["MenuApp"] 	= 'MN324';
				//$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;

			$data['form_action']	= site_url('c_hr/c_employee/c_employee/employee_project_process');
			//$data['main_view'] 		= 'v_hr/v_employee/employee_setproj_form';	
			$data['link'] 			= array('link_back' => anchor('c_hr/c_employee/c_employee/','<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= 'emp_prj';
				$MenuCode 		= 'MN324';
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
	 
			$data['viewallproject'] = $this->m_employee->get_allproject($Emp_ID, $offset)->result();
				
			$this->load->view('v_hr/v_employee/employee_setproj_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function employee_project_process()
	{
		$this->load->model('m_hr/m_employee/m_employee', '', TRUE);
			
		ob_start();
		$this->db->trans_begin();
		
		$this->m_employee->deleteEmpProjEmp($this->input->post('Emp_ID1'));	
		$empID	= $this->input->post('Emp_ID1');
		
		$packelementsPRJ	= $_POST['packageelements'];
		if (count($packelementsPRJ) > 0)
		{
			$mySelected = $_POST['packageelements'];
			foreach ($mySelected as $projCode)
			{
				$employeeProj = array('Emp_ID' => $empID, 'proj_Code' => $projCode);
				$this->m_employee->addEmpProj($employeeProj);
			}
		}
		
		$this->m_employee->deleteEmpAcc($this->input->post('Emp_ID1'));
			
		$packelementsACC	= $_POST['packageelementsCB'];
		if (count($packelementsACC) > 0)
		{
			$mySelectedCB = $_POST['packageelementsCB'];
			foreach ($mySelectedCB as $Acc_Numb)
			{				
				if (count($packelementsPRJ) > 0)
				{
					$mySelected = $_POST['packageelements'];
					foreach ($mySelected as $projCode)
					{
						// GET BUDGET
						/* HIDDEN BY DH on 14 August 2020. KARENA LEBIH BAIK DIRECT PER ANGGARAN. KARENA NANTI SETIAP TRX DIANGGARAN AKAN TERPOST JUGA KE HEADER COANYA
						$sqlGBUDG	= "SELECT PRJCODE FROM tbl_project WHERE PRJCODE_HO = '$projCode' AND PRJTYPE = 3";
						$resGBUDG	= $this->db->query($sqlGBUDG)->result();
						foreach($resGBUDG as $budg):
							$PRJCODE = $budg->PRJCODE;
							$insAcc  = array('Emp_ID' => $empID, 'PRJCODE' => $PRJCODE, 'Acc_Number' => $Acc_Numb);
							$this->m_employee->addEmpAcc($insAcc);
						endforeach;*/
						$Acc_Name 		= "";
						$sqlDataACC     = "SELECT DISTINCT A.Account_NameId FROM tbl_chartaccount A WHERE A.Account_Number = '$Acc_Numb'";
                        $resDataACC     = $this->db->query($sqlDataACC)->result();
                        foreach($resDataACC as $rowDACC) :
                            $Acc_Name   = $rowDACC->Account_NameId;
                        endforeach;
						$insAcc  = array('Emp_ID' => $empID, 'PRJCODE' => $projCode, 'Acc_Number' => $Acc_Numb, 'Acc_Name' => $Acc_Name);
						$this->m_employee->addEmpAcc($insAcc);
					}
				}
			}
		}

		// START : UPDATE TO T-TRACK
			date_default_timezone_set("Asia/Jakarta");
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			$TTR_PRJCODE	= '';
			$TTR_REFDOC		= 'emp_prj';
			$MenuCode 		= 'MN324';
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
		
		redirect('c_hr/c_employee/c_employee/');
	}

	function employee_auth_items()
	{
		$this->load->model('m_hr/m_employee/m_employee', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$Emp_ID		= $_GET['id'];
		$Emp_ID		= $this->url_encryption_helper->decode_url($Emp_ID);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['Emp_ID'] 		= $Emp_ID;
			$data['title'] 			= $appName;
			$data['h2_title'] 		= "Employee's Authorization Items";
			$data["MenuCode"] 		= "MN324";
			
			// GET MENU DESC
				$mnCode				= 'MN324';
				$data["MenuApp"] 	= 'MN324';
				//$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;

			$data['form_action']	= site_url('c_hr/c_employee/c_employee/employee_auth_items_process');
			//$data['main_view'] 		= 'v_hr/v_employee/employee_setproj_form';	
			$data['link'] 			= array('link_back' => anchor('c_hr/c_employee/c_employee/','<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= 'emp_Auth_items';
				$MenuCode 		= 'MN324';
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
	 
			$data['viewallEmp'] = $this->m_employee->get_allEmployee();
				
			$this->load->view('v_hr/v_employee/employee_setAuth_items_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function employee_auth_items_process()
	{
		$this->load->model('m_hr/m_employee/m_employee', '', TRUE);
			
		ob_start();
		$this->db->trans_begin();
		
		$this->m_employee->deleteEmpAuthItems($this->input->post('Emp_ID1'));	
		$empID	= $this->input->post('Emp_ID1');
		
		$packelementsPRJ	= $_POST['packageelements'];
		if (count($packelementsPRJ) > 0)
		{
			$mySelected = $_POST['packageelements'];
			foreach ($mySelected as $projCode)
			{
				$empIR_sett = array('Emp_ID' => $empID, 'PRJCODE' => $projCode);
				$this->m_employee->addEmpIR_sett($empIR_sett);
			}
		}

		// START : UPDATE TO T-TRACK
			date_default_timezone_set("Asia/Jakarta");
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			$TTR_PRJCODE	= '';
			$TTR_REFDOC		= 'emp_Auth_items';
			$MenuCode 		= 'MN324';
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
		
		redirect('c_hr/c_employee/c_employee/');
	}

	function employee_dashboard($offset=0)
	{
		$this->load->model('m_hr/m_employee/m_employee', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$Emp_ID		= $_GET['id'];
		$Emp_ID		= $this->url_encryption_helper->decode_url($Emp_ID);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['Emp_ID'] 		= $Emp_ID;
			$data['title'] 			= $appName;
			$data['h2_title'] 		= 'Employee\'s Dashboard';
			$data["MenuCode"] 		= 'MN324';
			
			// GET MENU DESC
				$mnCode				= 'MN324';
				$data["MenuApp"] 	= 'MN324';
				//$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;

			$data['form_action']	= site_url('c_hr/c_employee/c_employee/employee_dashboard_process');
			$data['link'] 			= array('link_back' => anchor('c_hr/c_employee/c_employee/','<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));	
	 
			$data['viewallproject'] = $this->m_employee->get_alldashboard($Emp_ID, $offset)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= 'emp_dash';
				$MenuCode 		= 'MN324';
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

			$this->load->view('v_hr/v_employee/employee_setdash_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function employee_dashboard_process()
	{
		$this->load->model('m_hr/m_employee/m_employee', '', TRUE);
			
		ob_start();
		$this->db->trans_begin();
		
		$this->m_employee->deleteEmpDashEmp($this->input->post('Emp_ID1'));	
		$empID	= $this->input->post('Emp_ID1');
		
		$packageelements	= $_POST['packageelements'];
		$Cpackageelements	= count($packageelements);
		echo $Cpackageelements;
		//return false;
		if (count($packageelements)>0)
		{
			$mySelected	= $_POST['packageelements'];
			foreach ($mySelected as $DS_TYPE)
			{
				echo "$empID = $DS_TYPE<br>";
				$employeeDash = array('EMP_ID' => $empID, 'DS_TYPE' => $DS_TYPE);
				$this->m_employee->addEmpDash($employeeDash);
			}
		}
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
		}
		else
		{
			$this->db->trans_commit();
		}

		// START : UPDATE TO T-TRACK
			date_default_timezone_set("Asia/Jakarta");
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			$TTR_PRJCODE	= '';
			$TTR_REFDOC		= 'emp_dash';
			$MenuCode 		= 'MN324';
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
		
		redirect('c_hr/c_employee/c_employee/');
	}

	function employee_auth_doc($offset=0)
	{
		$this->load->model('m_hr/m_employee/m_employee', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$Emp_ID		= $_GET['id'];
		$Emp_ID		= $this->url_encryption_helper->decode_url($Emp_ID);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['Emp_ID'] 		= $Emp_ID;
			$data['title'] 			= $appName;
			$data['h2_title'] 		= 'Employee\'s Document Authorization';
			$data["MenuCode"] 		= 'MN324';
			$data['form_action']	= site_url('c_hr/c_employee/c_employee/employee_authorization_process');
			$data['link'] 			= array('link_back' => anchor('c_hr/c_employee/c_employee/','<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));	
	 
			$data['viewalltype'] 	= $this->m_employee->get_all_doctype_list($Emp_ID, $offset)->result();
				
			$this->load->view('v_hr/v_employee/employee_setdoc_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function employee_docauth_process()
	{
		$this->load->model('m_hr/m_employee/m_employee', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();
			$Emp_ID1	= $this->input->post('Emp_ID1');
			
			$this->m_employee->deleteAccDocEmp($this->input->post('Emp_ID1'));		
			
			foreach($_POST['data'] as $d)
			{
				$chkDetail = $d['isChkDetail'];
				if($chkDetail > 0)
				{
					$this->db->insert('tbl_userdoctype',$d);
				}
			}
			
			$DAU_WRITE	= $this->input->post('DAU_WRITE');
				if($DAU_WRITE == '')
					$DAU_WRITE = 0;
			$DAU_READ	= $this->input->post('DAU_READ');
				if($DAU_READ == '')
					$DAU_READ = 0;
			$DAU_DL		= $this->input->post('DAU_DL');
				if($DAU_DL == '')
					$DAU_DL = 0;
			
			$this->m_employee->deleteAuthDocEmp($this->input->post('Emp_ID1'));	
						
			$INSAUTDOC	= array('DAU_EMPID' 	=> $Emp_ID1,
								'DAU_WRITE'		=> $DAU_WRITE,
								'DAU_READ'		=> $DAU_READ,
								'DAU_DL'		=> $DAU_DL);
								
			$this->m_employee->addEMPAUTH($INSAUTDOC);
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= 'emp_atuh_doc';
				$MenuCode 		= 'MN324';
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
			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$url			= site_url('c_hr/c_employee/c_employee/');
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
		
	function getTheCode($log_username) // OK
	{ 	
		$this->load->model('m_hr/m_employee/m_employee', '', TRUE);
		$countUNCode 	= $this->m_employee->count_log_username($log_username);
		echo $countUNCode;
	}
}