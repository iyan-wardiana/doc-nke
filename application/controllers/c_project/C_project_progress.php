<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 16 Maret 2017
	* File Name		= c_project_progress.php
	* Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_project_progress  extends CI_Controller  
{
 	public function index()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_project/c_project_progress/index1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function index1()
	{
		$this->load->model('m_project/m_listproject/m_listproject', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			$EmpID 		= $this->session->userdata('Emp_ID');
					
			$data['title'] 			= $appName;
			$data['h2_title']		= 'Project Progress';
			$data['h3_title']		= 'Project';
			$data['form_action1']	= site_url('c_project/c_project_progress/add_process/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['form_action2'] 	= site_url('c_project/c_project_progress/showImages/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['form_action3'] 	= site_url('c_project/c_project_progress/do_upload/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$projCode			= '';
			$getCount			= "tbl_project WHERE PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$EmpID')";
			$resGetCount		= $this->db->count_all($getCount);		
			if($resGetCount > 0)
			{
				$getData		= "SELECT PRJCODE FROM tbl_project WHERE PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$EmpID')";
				$resGetData 	= $this->db->query($getData)->result();
				foreach($resGetData as $rowData) :
					$projCode 	= $rowData->PRJCODE;
				endforeach;
			}
			
			$MenuCode 				= 'MN226';
			$data['MenuCode'] 		= 'MN226';
			
			$data['projCode'] 		= $projCode;	// PRJCODE FEAULL
			$data['progressType'] 	= 3;
			$data['progress_Step'] 	= 1;
			$data['Emp_ID'] 		= $EmpID;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $projCode;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN226';
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
			
			$this->load->view('v_project/v_project_progress/v_project_progress', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function index2()
	{
		$this->load->model('m_project/m_listproject/m_listproject', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$collData		= $_GET['id'];
			$collData		= $this->url_encryption_helper->decode_url($collData);
			$dataArray		= explode("~", $collData);
			$projCode		= $dataArray[0];
			$progressType	= $dataArray[1];
			$progress_Step	= $dataArray[2];
			
			$EmpID 			= $this->session->userdata('Emp_ID');
			
			$data['title'] 			= $appName;
			$data['h2_title']		= 'Project Progress';
			$data['h3_title']		= 'Project';
			$data['form_action1']	= site_url('c_project/c_project_progress/add_process/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['form_action2'] 	= site_url('c_project/c_project_progress/showImages/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['form_action3'] 	= site_url('c_project/c_project_progress/do_upload/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$MenuCode 				= 'MN226';
			$data['MenuCode'] 		= 'MN226';
			
			$data['projCode'] 		= $projCode;	// PRJCODE FEAULL
			$data['progressType'] 	= $progressType;
			$data['progress_Step'] 	= $progress_Step;
			$data['Emp_ID'] 		= $EmpID;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $projCode;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN226';
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
			
			$this->load->view('v_project/v_project_progress/v_project_progress', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function add_process() // sekarang sifatnya hanya update karena Planning progress sudah terinput
	{
		$this->load->model('m_project/m_listproject/m_listproject', '', TRUE);
		$this->load->model('m_project/m_project_progress/m_project_progress', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$this->db->trans_begin();
		
		date_default_timezone_set("Asia/Jakarta");
		
		$LastUpdate		= date('Y-m-d H:i:s');
		
		$proj_Code 		= $this->input->post('proj_Code');
		$progress_Type	= $this->input->post('progress_Type');	// 1. Cash Flow, 2. Profit and Loss, 3. Project Progress
		$lastStepPS		= $this->input->post('Prg_Step_L');		// Last Step
		$progress_Step	= $this->input->post('progress_Step');	// Current Progress
		$Prg_Real_Now	= $this->input->post('Prg_Real_Now');	// Current Progress Percentation
		$Prg_RealAkum_L	= $this->input->post('Prg_RealAkum_L');	// Total Last Real Akumulation
		$Prg_Real_Now	= $this->input->post('Prg_Real_Now');	// Current Week Real Proggress
		$Prg_PlanAkum_N	= $this->input->post('Prg_PlanAkum_N');	// Current Week Real Proggress
		$Prg_ProjNotes	= '';
		$Prg_PstNotes	= '';
		
		// SETTING Prg_ProjNotes
			$input1	= $this->input->post('Prg_ProjNotes');
			$text1	= '';
			if($input1 != '')
			{
				$pecah1 = explode("\n", $input1);
				$text1 	= "";
				$vgv1	= count($pecah1);
				for ($i=0; $i<=count($pecah1)-1; $i++)
				{
					$part1 = str_replace($pecah1[$i], "".$pecah1[$i]."<br>", $pecah1[$i]);
					$text1 .= $part1;
				}
			}
		
		// SETTING Prg_PstNotes
			$input2	= $this->input->post('Prg_PstNotes');
			$text2	= '';
			if($input1 != '')
			{
				$pecah2 = explode("\n", $input2);
				$text2 	= "";
				$vgv2	= count($pecah2);
				for ($i=0; $i<=count($pecah2)-1; $i++)
				{
					$part2 = str_replace($pecah2[$i], "".$pecah2[$i]."<br>", $pecah2[$i]);
					$text2 .= $part2;
				}
			}
			
		$Prg_ProjNotes	= $text1;
		$Prg_PstNotes	= $text2;
		
		// Akumulasi Total
		$Prg_RealAkum		= $Prg_RealAkum_L + $Prg_Real_Now;
		$Prg_Dev			= $Prg_RealAkum - $Prg_PlanAkum_N;
		
		$parameters = array(
			'Prg_Real' 			=> $Prg_Real_Now,
			'Prg_RealAkum' 		=> $Prg_RealAkum,
			'Prg_Dev' 			=> $Prg_Dev,
			'Prg_ProjNotes' 	=> $Prg_ProjNotes,
			'Prg_PstNotes' 		=> $Prg_PstNotes,
			'Prg_LastUpdate' 	=> $LastUpdate
		);
		$this->m_project_progress->updatePP($proj_Code, $progress_Type, $progress_Step, $parameters);
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
		}
		else
		{
			$this->db->trans_commit();
		}
		
		$MenuCode 			= 'MN226';
		$data['MenuCode'] 	= 'MN226';
		
		$projCode			= $proj_Code;
		$progressType		= $progress_Type;
		$progress_Step 		= $progress_Step+1;
		$collData			= "$projCode~$progressType~$progress_Step";
			
		// START : UPDATE TO T-TRACK
			date_default_timezone_set("Asia/Jakarta");
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			$TTR_PRJCODE	= $projCode;
			$TTR_REFDOC		= $collData;
			$MenuCode 		= 'MN226';
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
		
		$url			= site_url('c_project/c_project_progress/index2/?id='.$this->url_encryption_helper->encode_url($collData));
		redirect($url);
	}
	
    function showImages() 
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$getAppName = $this->Menu_model->getAppName()->result();
			foreach($getAppName as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$data['title'] 		= $appName;
			$data['h2_title']	= 'Show Images';
			$data['main_view'] 	= 'v_project/v_project_progress/v_project_progress_sd';
			$data['form_action1']= site_url('c_project/c_project_progress/add_process');
			$data['form_action2'] 	= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/c_project_progress'),'showImages');
			$data['form_action3'] 	= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/c_project_progress'),'do_upload');
			
			$data['theProjCode'] = $this->input->post('proj_Code');
			$data['Emp_ID'] 	= $this->session->userdata('Emp_ID');
			
			$this->load->view('template', $data);
		}
		else
		{
			redirect('Auth');
		}
    }
		
	function view_list($FileUpName)
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$dataFile['FileUpName']	= $FileUpName;
			$data['main_view'] 		= 'v_project/v_project_progress/v_project_progress_sd_frame';
			$this->load->view('v_project/v_project_progress/v_project_progress_sd_frame', $dataFile);
		}
		else
		{
			redirect('Auth');
		}
	}
		
	/*function add($projCode)
	{
		$getAppName = $this->Menu_model->getAppName()->result();
		foreach($getAppName as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['projCode'] 		= $projCode;
			$data['isClose'] 		= 0;
			$data['title'] 			= $appName;
			$data['h2_title']		= 'Add Project Progress';
			$data['form_action']	= site_url('c_project/c_project_progress/add_process');
			$data['link'] 			= array('link_back' => anchor('c_project/c_project_progress/project_progress','<input type="button" name="btnCancel" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			//$data['main_view'] 	= 'v_project/v_project_progress/v_project_progress_sd_input';
			$data['Emp_ID'] 	= $this->session->userdata('Emp_ID');
			
			//$this->load->view('template', $data);
			$this->load->view('v_project/v_project_progress/v_project_progress_sd_input', $data);
		}
		else
		{
			redirect('Auth');
		}
	}*/
		
	function viewdocument($FileUpName)
	{
		$data['FileUpName'] = $FileUpName;		
		$this->session->set_userdata('dtSessFileUpName', $FileUpName);
		$FileUpName   = $this->session->userdata('dtSessFileUpName');
		
		$data['myPath'] = 'system/application/views/v_project_progress/document_list/'.$FileUpName;
		$this->load->view('v_project_progress/view_document', $data);
	}
	
	function ShowImage($thisVal)
	{	
		$getAppName = $this->Menu_model->getAppName()->result();
		foreach($getAppName as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$data['title'] 			= $appName;
		$data['username'] 		= $this->session->userdata('username');
		$data['password'] 		= $this->session->userdata('password');
		$data['h2_title']		= 'Show Image';
		
		$data['Emp_ID'] 		= $this->session->userdata('Emp_ID');
		$data['First_Name'] 	= $this->session->userdata('First_Name');
		$data['Middle_Name'] 	= $this->session->userdata('Middle_Name');
		$data['Last_Name'] 		= $this->session->userdata('Last_Name');
		$data['galeri_Kode'] 	= $thisVal;
		
		$this->load->view('v_project/v_project_progress/galeri_detil_sd', $data);
	}
	
	function do_upload()
	{
		$getAppName = $this->Menu_model->getAppName()->result();
		foreach($getAppName as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$this->db->trans_begin();
		
		// Pembuatan ID Penyimpanan Per Tanggal	
		date_default_timezone_set("Asia/Jakarta");
		$myDate = date('Y-m-d',strtotime($this->input->post('Prg_Date2'))); //Returns IST 
		$myDateX = date('Y-m-d H:i:s'); //Returns IST 
		$date = strtotime($myDate);
		$Th1 = date('Y', $date); 
		$Thn = substr($Th1, -2); 
		$Bln = date('m', $date);
		$Tgl = date('d', $date);
		$Jam = date('H', $date);
		$Min = date('i', $date);
		$SP = "_";
		
		$proj_Code = $this->input->post('proj_Code2');
		$galeriKode = "$Th1$Bln$Tgl";
		
		$progressDate= date('Y-m-d',strtotime($this->input->post('Prg_Date2')));
		
		// Cek kode per tanggal ini. Apabila ada, maka update, not Adding File to Header
		$getCountGK		= "tbl_galeri_header WHERE galeri_Kode = '$galeriKode'";
		$resGetCountGK	= $this->db->count_all($getCountGK);
		
		if($resGetCountGK == 0)
		{		
			$insPicHeader = array('galeri_Kode' 		=> $galeriKode,
						'proj_Code'			=> $this->input->post('proj_Code2'),
						'ktgr_Name'	=> $proj_Code
						//'progress_Date'	=> $progressDate
						);
						
			$this->M_project_progress_sd->addHeaderPic($insPicHeader);	
			
		}		
		
		// Save to Detail
			
		$config['upload_path'] = 'images/foto/';
		$config['allowed_types'] = 'jpeg|jpg|png|pdf';
		$config['max_size']	= '1000';		
		
		$this->load->library('upload', $config);
		
		if ( ! $this->upload->do_upload())
		{
			$data['fileName'] = '';				
			$data['error'] 		= 'Error';
		}
		else
		{
			$data = array('upload_data' => $this->upload->data());			
            $data['fileName'] = $this->upload->file_name;			
            $fileName = $this->upload->file_name;
		}
		
		$insPicDetail = array('galeri_Kode' 	=> $galeriKode,
					'file_Name'		=> $fileName,
					'kategori'		=> 0,
					'keterangan'	=> $progressDate,
					'oleh'			=> 0,
					'tgl'			=> $myDateX);
					
		$this->M_project_progress_sd->addDetailPic($insPicDetail);
			
		// START : UPDATE TO T-TRACK
			date_default_timezone_set("Asia/Jakarta");
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			$TTR_PRJCODE	= $this->input->post('proj_Code2');
			$TTR_REFDOC		= $galeri_Kode;
			$MenuCode 		= 'Galeri Upload';
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
		
		$data['title'] 			= $appName;
		$data['h2_title']		= 'Project Progress';
		$data['main_view'] 		= 'v_project/v_project_progress/v_project_progress_sd';		
		$data['form_action1']	= site_url('c_project/c_project_progress/add_process');
		$data['form_action2'] 	= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/c_project_progress'),'showImages');
		$data['form_action3'] 	= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/c_project_progress'),'do_upload');
		/*$data['form_action2']	= site_url('c_project/c_project_progress/showImages');
		$data['form_action3']	= site_url('c_project/c_project_progress/do_upload');*/
		$data['theProjCode'] 	= $this->input->post('proj_Code2');
		$data['Emp_ID'] 		= $this->session->userdata('Emp_ID');
		
		$this->load->view('template', $data);
	}
	
	function ShowImageFull($file_Name)
	{	
		$getAppName = $this->Menu_model->getAppName()->result();
		foreach($getAppName as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$data['title'] 			= $appName;
		$data['file_Name'] 		= $file_Name;
		
		$this->load->view('v_project/v_project_progress/view_photo_full', $data);
	}
	
}