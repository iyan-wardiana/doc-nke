<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 07 November 2017
 * File Name	= C_gen_upload.php
 * Function		= -
*/
class C_gen_upload extends CI_Controller
{	
 	function index()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_help/c_gen_upload/index1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function index1()
	{
		$this->load->model('m_help/m_gen_upload', '', TRUE);
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['title'] 		= $appName;
			$data['form_action']= site_url('c_help/c_gen_upload/do_upload/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$data['error'] 		= '';
			$data['MenuCode'] 	= 'MN212';
			$MenuCode 			= 'MN212';
			$data["countGUp"] 	= $this->m_gen_upload->count_all_upl($DefEmp_ID);
			$data['vwGUp'] 		= $this->m_gen_upload->get_all_genupload()->result();
			
			$this->load->view('v_help/v_gen_upload/gen_upload', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	 
	function do_upload($offset=0)
	{
		$this->load->model('m_help/m_gen_upload', '', TRUE);
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName 	= $therow->app_name;		
		endforeach;
		
		date_default_timezone_set("Asia/Jakarta");
		$CREATED		= date('Y-m-d H:i:s');
		
		$UP_Code	= $this->input->post('UP_Code');
		$UP_Type	= $this->input->post('UP_Type');
		$filename 	= $_FILES["userfile"]["name"];
		$source 	= $_FILES["userfile"]["tmp_name"];
		$type 		= $_FILES["userfile"]["type"];
		$fsize		= $_FILES['userfile']['size'];
		$fext		= preg_replace("/.*\.([^.]+)$/","\\1", $_FILES['userfile']['name']);		
		$success	= 0;

		if($UP_Type == 1)
		{
			$filename 	= $_FILES["userfile"]["name"];
			$source 	= $_FILES["userfile"]["tmp_name"];
			$type 		= $_FILES["userfile"]["type"];
			
			$name 		= explode(".", $filename);
			$accepted_types = array('application/zip', 'application/x-zip-compressed', 'multipart/x-zip', 'application/x-compressed');
			foreach($accepted_types as $mime_type) 
			{
				if($mime_type == $type) 
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
			
			$target_path = "uploads/genupload/".$filename;  // change this to the correct site path
			if(move_uploaded_file($source, $target_path))
			{
				$zip = new ZipArchive();
				$x = $zip->open($target_path);
				if ($x === true) 
				{
					$zip->extractTo("uploads/genupload/"); // change this to the correct site path
					$zip->close();
			
					unlink($target_path);
				}
				//$message 	= "Your .zip file was uploaded and unpacked.";
				$success	= 1;
			} 
			else 
			{	
				$message 	= "There was a problem with the upload. Please try again.";
				$success	= 0;
			}
		}
		else
		{
			$file 						= $_FILES['userfile'];
			$file_name 					= $file['name'];
			$config['upload_path']   	= "uploads/genupload/"; 
			$config['allowed_types']	= 'pdf|gif|jpg|jpeg|png|xls|xlsx|doc|docs|txt|csv|sql|rar|zip|ttf'; 
			$config['overwrite'] 		= TRUE;
			//$config['max_size']     	= 1000000000; 
			//$config['max_width']    	= 10024; 
			//$config['max_height']    	= 10000;  
			$config['file_name']       	= $file['name'];
	
			$this->load->library('upload', $config);
			
			if ( ! $this->upload->do_upload('userfile')) 
			{
				$success	= 0;
			}
			else
			{
				$success	= 1;
			}
		}

		if($success == 1)
		{
			$insGUp = array('UP_Code' 	=> $UP_Code,		// OK
							'Filename'	=> $filename,		// OK
							'fext'		=> $fext,			// OK
							'fsize'		=> $fsize,			// OK
							'UP_Emp'	=> $DefEmp_ID);
				
			$this->m_gen_upload->add($insGUp);
		}
		else
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['title'] 		= $appName;
			$data['form_action']= site_url('c_help/c_gen_upload/do_upload/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$data['error'] 		= '';
			$data['MenuCode'] 	= 'MN212';
			$MenuCode 			= 'MN212';
			$data["countGUp"] 	= $this->m_gen_upload->count_all_upl($DefEmp_ID);
			$data['vwGUp'] 		= $this->m_gen_upload->get_all_genupload()->result();
			
			$this->load->view('v_help/v_gen_upload/gen_upload', $data);
		}
		
		$url			= site_url('c_help/c_gen_upload/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
}
?>