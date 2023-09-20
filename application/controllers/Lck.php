<?php
/*  
	* Author		= Dian Hermanto
	* Create Date	= 20 Februari 2022
	* File Name		= Lck.php
	* Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Lck extends CI_Controller  
{
  	function __construct() // GOOD
	{
		parent::__construct();
		
		$this->load->model('m_updash/m_updash', '', TRUE);
	
		$this->data['UserID'] 		= $this->session->userdata['Emp_ID'];
		$this->data['appName'] 		= $this->session->userdata['appName'];
		$this->data['ISCREATE'] 	= $this->session->userdata['ISCREATE'];
		$this->data['ISAPPROVE'] 	= $this->session->userdata['ISAPPROVE'];
		$this->data['LangID']		= $this->session->userdata['LangID'];
	}

 	public function index()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('lck/lckTrx/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function lckTrx($offset=0)
	{
		$this->load->model('m_howtouse', '', TRUE);
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			
		// GET MENU DESC
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = "Kunci Transaksi";
			else
				$data["mnName"] = "Lock Journal Transactions";
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
					
			$data['title'] 				= $appName;
			$data['h2_title']			= 'Pencarian';
			$data['h3_title']			= 'Help';

			$this->load->view('v_lock/v_lock', $data); 
		}
		else
		{
			redirect('__I1y');
		}
	}

	function lTrx()
	{
		$statApp 	= $_POST['statApp'];
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

		$delSQL		= "UPDATE tappname SET app_stat = $statApp";
		$this->db->query($delSQL);

		$data 		= array('app_stat' => $statApp);
		$this->session->set_userdata($data);

		$LangID 	= $this->session->userdata['LangID'];
		if($LangID == 'IND')
		{
			if($statApp == 1)
				$alert1	= "Transaksi penjurnalan sudah kami kunci.";
			else
				$alert1	= "Transaksi penjurnalan sudah kami buka kembali.";
		}
		else
		{
			if($statApp == 1)
				$alert1	= "Journal transaction has been locked.";
			else
				$alert1	= "Journal transaction has been opened.";
		}

		echo "$alert1";
	}

	function appStat()
	{
		$s_00		= "SELECT app_stat FROM tappname";
		$r_00 		= $this->db->query($s_00)->result();
		foreach($r_00 as $rw_00):
			$app_stat 	= $rw_00->app_stat;
		endforeach;

		$LangID 	= $this->session->userdata['LangID'];
		if($LangID == 'IND')
		{
			if($app_stat == 1)
				$alert1	= "Mohon maaf, saat ini transaksi penjurnalan sedang terkunci.";
			else
				$alert1	= "Saat ini transaksi penjurnalan sedang terbuka.";
		}
		else
		{
			if($app_stat == 1)
				$alert1	= "Sorry, the journalizing transaction is currently locked.";
			else
				$alert1	= "The journalizing transaction is currently unlocked.";
		}

    	$ACC_ID_PERSL	= "";
    	$ACC_ID_EMPAP	= "";
    	$s_01 			= "SELECT A.ACC_ID_PERSL, A.ACC_ID_EMPAP FROM tglobalsetting A";
    	$r_01 			= $this->db->query($s_01)->result();
    	foreach($r_01 as $rw_01):
    		$ACC_ID_PERSL 	= $rw_01->ACC_ID_PERSL;
    		$ACC_ID_EMPAP 	= $rw_01->ACC_ID_EMPAP;
    	endforeach;

		echo "$app_stat~$alert1~$ACC_ID_PERSL~$ACC_ID_EMPAP";
	}

	function lJRN()
	{
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		$LOCKJMONTH	= $this->input->post('LOCKJMONTH');
		$LOCKJYEAR	= $this->input->post('LOCKJYEAR');

		$s_0a		= "UPDATE tbl_journalheader SET isLock = 1 WHERE MONTH(JournalH_Date) = '$LOCKJMONTH' AND YEAR(JournalH_Date) = '$LOCKJYEAR' AND GEJ_STAT = 3";
		$this->db->query($s_0a);

		$s_0b		= "UPDATE tbl_journaldetail SET isLock = 1 WHERE MONTH(JournalH_Date) = '$LOCKJMONTH' AND YEAR(JournalH_Date) = '$LOCKJYEAR' AND GEJ_STAT = 3";
		$this->db->query($s_0b);

		$s_1a		= "UPDATE tbl_journalheader_vcash SET isLock = 1 WHERE MONTH(JournalH_Date) = '$LOCKJMONTH' AND YEAR(JournalH_Date) = '$LOCKJYEAR' AND GEJ_STAT = 3";
		$this->db->query($s_1a);

		$s_1b		= "UPDATE tbl_journaldetail_vcash SET isLock = 1 WHERE MONTH(JournalH_Date) = '$LOCKJMONTH' AND YEAR(JournalH_Date) = '$LOCKJYEAR' AND GEJ_STAT = 3";
		$this->db->query($s_1b);

		$LangID 	= $this->session->userdata['LangID'];
		if($LangID == 'IND')
		{
			$alert1	= "Semua jurnal sudah kami kunci.";
		}
		else
		{
			$alert1	= "All Journal has been locked.";
		}

		echo "$alert1";
	}
}