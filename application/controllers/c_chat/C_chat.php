<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 04 April 2017
 * File Name	= c_chat.php
 * Location		= -
*/


if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_chat  extends CI_Controller
{
 	function index() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_chat/c_chat/inbox_chat/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function inbox_chat($offset=0) // U
	{
		$this->load->model('m_chat/m_chat', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$DefEmp_ID	= $_GET['id'];
			$DefEmp_ID	= $this->url_encryption_helper->decode_url($DefEmp_ID);
					
			$data['title'] 			= $appName;
			$data['h2_title']		= 'Chat List';
			$data['h3_title']		= 'chat receive';
	 
			//$data['viewchat']		= $this->m_mailbox->get_all_mail_inbox()->result();
			
			$this->load->view('v_chat/chatlist', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function addchat()
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_chat/m_chat', '', TRUE);
			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			$appName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($appName);
			
			$data['title'] 				= $appName;
			$data['h2_title'] 			= 'Add Chat';
			$data['h3_title'] 			= 'chat management';
					
			$this->load->view('v_chat/chat_emplist', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
		
	function getSentDat($COLL_DATA) // OK
	{
		date_default_timezone_set("Asia/Jakarta");
		
		$CHAT_CODEY		= date('Y');
		$CHAT_CODEM		= date('m');
		$CHAT_CODED		= date('d');
		$CHAT_CODEH		= date('H');
		$CHAT_CODEI		= date('i');
		$CHAT_CODES		= date('s');
		
		$CHAT_CODE		= "$CHAT_CODEY$CHAT_CODEM$CHAT_CODED$CHAT_CODEH$CHAT_CODEI$CHAT_CODES";
		$EXPLODESTR 	= explode("~", $COLL_DATA);
		$THEROW			= $EXPLODESTR[0];
		$CHAT_EMP_FROM	= $EXPLODESTR[1];
		$CHAT_EMP_TO	= $EXPLODESTR[2];
		$CHAT_MESSAGEa	= str_replace("%20", " ", $EXPLODESTR[3]); // | -> %7C, ` -> %60, ^ -> %5E
		$CHAT_MESSAGEb	= str_replace("%60", ".", $CHAT_MESSAGEa);
		$CHAT_MESSAGEc	= str_replace("%7C", ",", $CHAT_MESSAGEb);
		$CHAT_MESSAGE	= str_replace("%5E", "?", $CHAT_MESSAGEc);
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		
		$CHAT_CODE		= "$CHAT_CODEY$CHAT_CODEM$CHAT_CODED$CHAT_CODEH$CHAT_CODEI$CHAT_CODES";
		
		$CHAT_TIME		= date('Y-m-d H:i:s');
		$CHAT_SENDDATE	= date('Y-m-d H:i:s');
		
		$sqlInpChat		= "INSERT INTO tbl_chat (CHAT_CODE, CHAT_EMP_FROM, CHAT_EMP_TO, CHAT_MESSAGE, CHAT_TIME, CHAT_EMP_SENDER)
							VALUES ('$CHAT_CODE', '$CHAT_EMP_FROM', '$CHAT_EMP_TO', '$CHAT_MESSAGE', '$CHAT_TIME', '$DefEmp_ID')";
		$this->db->query($sqlInpChat);
		echo $THEROW;
	}
		
	function getReadAll($COLL_DATA) // OK
	{
		$EXPLODESTR 	= explode("~", $COLL_DATA);
		$THEROW			= $EXPLODESTR[0];
		$CHAT_EMP_FROM	= $EXPLODESTR[1];
		$CHAT_EMP_TO	= $EXPLODESTR[2];
		
		$sqlUpdChat		= "UPDATE tbl_chat SET CHAT_READ = 1 WHERE CHAT_EMP_FROM = '$CHAT_EMP_FROM' AND CHAT_EMP_TO = '$CHAT_EMP_TO'";
		$this->db->query($sqlUpdChat);
		echo $THEROW;
	}
}