<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 24 Februari 2017
 * File Name	= C_getrpffile.php
 * Location		= -
*/

class C_getrpffile extends CI_Controller
{
	var $limit = 2;
	var $title = 'NKE ITSys';
	
 	function index()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_help/c_getrpffile/rtflist/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function rtflist($offset=0)
	{
		$this->load->model('m_help/m_getrpffile', '', TRUE);
		$this->load->helper('directory');
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			
			$data['isSearch'] 		= 'No';
			$data['title'] 			= $appName;
			$data['h1_title']		= 'My RPF File';
			$data['h2_title']		= 'RPF File List';
			
			$data['form_action']	= site_url('c_help/c_getrpffile/do_upload/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['showIndex']		= site_url('c_help/c_getrpffile/index/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['srch_url']		= site_url('c_help/c_getrpffile/theIndex_src/?id='.$this->url_encryption_helper->encode_url($appName));		
					
			$this->load->view('v_help/v_getrpffile/getrpffile_adm', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function delFile()
	{
		$this->load->model('m_help/m_getrpffile', '', TRUE);
		$this->load->helper('directory');
		
		$file	= $_GET['id'];
		$file	= $this->url_encryption_helper->decode_url($file);
		
		$this->m_getrpffile->hiddenFile($file);
		/*$Dir2		= "Temp526";
		$dir 		= "C:/$Dir2/$file";
		echo "$dir";
		$handle = opendir($dir);
		closedir($handle);
		rmdir($myPath);*/
		//$proj_CodeX    	= $this->session->userdata['SessTempProject']['sessTempProj'];
		//$myPath = base_url().'uploads/Temp'.$proj_CodeX.'/'.$file;
		//filemtime($myPath);
		//$handle = opendir($myPath);
		//rmdir("$myPath/$file");
		//do whatever you need
		//closedir($handle);
		//rmdir($myPath, 1);
		//return false;
		redirect('c_help/c_getrpffile/');
	}
}
?>