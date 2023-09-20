<?php
/*
 * Author		= Dian Hermanto
 * Create Date	= 31 Maret 2019
 * File Name	= y_5c4nQR.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Y_5c4nQR extends CI_Controller  
{
  	function __construct() // GOOD
	{
		parent::__construct();
		
		$this->load->model('m_production/m_prodprocess', '', TRUE);
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
	
 	function index() // GOOD
	{
		$this->load->model('m_production/m_prodprocess', '', TRUE);
		
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url		= site_url('Y_5c4nQR/y_Y_5c4_nQR/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function y_Y_5c4_nQR() // GOOD
	{
		$this->load->model('m_production/m_prodprocess', '', TRUE);
				
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$CollID				= $_GET['id'];
			$appName			= $this->url_encryption_helper->decode_url($CollID);
			$EmpID 				= $this->session->userdata('Emp_ID');						
			$data['title'] 		= $appName;	
			$LangID 			= $this->session->userdata['LangID'];			
			$data["h1_title"] 	= "Scan QR Code";			
			$data['form_action']= site_url('c_production/c_pR04uctpr0535/add_process');
	
			$this->load->view('y_Y_5c4_nQR', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function Get_Data() // GOOD
	{
		// INFORMATION NEEDED
		/* EXAMP. IR.000002.19.03~NO JO~SO.000001.19.03~PT Dian Abadi Jaya
			0. Pemilik
			1. Item Code
			2. Customer Name
			3. Receipt Code
			4. SO Number
			5. BOM Code
			6. Gray Code
			5. DLL
		*/
		$QRCode		= $this->input->post('scanned-QRText');
		$collDC		= explode('~' , $QRCode);
		$totCollD 	= count($collDC);

		if($totCollD == 1)
		{
			$QRSplit	= explode("~", $QRCode);
			$IRQRC		= $QRSplit[0];	// IR QR Code
			
			// GET SO DETAIL
			$SO_CODE	= '';
			$SO_DATE	= '';
			$BOM_CODE	= '';
			$CUST_DESC	= '';
			$sqlSO		= "SELECT A.PRJCODE, A.JO_NUM, A.JO_CODE, A.IR_CODE, A.ITM_CODE, A.ITM_NAME, A.QRC_PATT,
								B.SO_CODE, B.BOM_CODE, B.CUST_DESC
							FROM tbl_jo_stfdetail_qrc A
								INNER JOIN tbl_jo_header B ON A.JO_NUM = B.JO_NUM
							WHERE A.QRC_NUM = '$IRQRC' LIMIT 1";
			$resSO		= $this->db->query($sqlSO)->result();
			foreach($resSO as $rowSO) :
				$PRJCODE	= $rowSO->PRJCODE;
				$JO_NUM		= $rowSO->JO_NUM;
				$JO_CODE	= $rowSO->JO_CODE;
				$IR_CODE	= $rowSO->IR_CODE;
				$ITM_CODE	= $rowSO->ITM_CODE;
				$ITM_NAME	= $rowSO->ITM_NAME;
				$QRC_PATT	= $rowSO->QRC_PATT;
				$SO_CODE	= $rowSO->SO_CODE;
				$BOM_CODE	= $rowSO->BOM_CODE;
				$CUST_DESC	= $rowSO->CUST_DESC;
			endforeach;
			echo "$CUST_DESC~$IRCode~$SO_CODE~$SO_DATEV~$BOM_CODE";
		}
		else if($totCollD == 2)
		{
			$QRSplit	= explode("~", $QRCode);
			$IRCode		= $QRSplit[0];	// JO_NUM
			$SOCode		= $QRSplit[1];	// 
			
			// GET SO DETAIL
			$SO_CODE	= '';
			$SO_DATE	= '';
			$BOM_CODE	= '';
			$CUST_DESC	= '';
			$sqlSO		= "SELECT A.SO_CODE, A.SO_DATE, A.BOM_CODE, B.CUST_DESC
							FROM tbl_so_header A
								INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
							WHERE A.SO_NUM = '$SOCode' LIMIT 1";
			$resSO		= $this->db->query($sqlSO)->result();
			foreach($resSO as $rowSO) :
				$SO_CODE	= $rowSO->SO_CODE;
				$SO_DATE	= $rowSO->SO_DATE;
				$SO_DATEV	= date('d M Y', strtotime($SO_DATE));
				$BOM_CODE	= $rowSO->BOM_CODE;
				$CUST_DESC	= $rowSO->CUST_DESC;
			endforeach;
			echo "$CUST_DESC~$IRCode~$SO_CODE~$SO_DATEV~$BOM_CODE";
		}
	}
	
	function GetData() // GOOD
	{

		$QRCode		= $this->input->post('scanned-QRText');
		// GET INFORMATION TYPE
		// 1. CHECK IN QRC List
			$sqlC	= "tbl_qrc_detail WHERE QRC_NUM = '$QRCode'";
			$resC 	= $this->db->count_all($sqlC);
			if($resC > 0)
			{
				$ISEXIST	= 1;
				$PRJCODE	= '';
				//$ITM_CODE	= '';
				$IR_CODE	= '';
				$QRC_CODEV	= '';
				$QRC_DATE	= '';
				$CUST_DESC	= '';
				$IR_CODE	= '';
				$ITM_CODE 	= '';
				$ITM_NAME	= '';
				$GRP_CODE	= '';
				$JO_NUM		= '';
				$ITM_UNIT	= '';
				$sqlJO		= "SELECT PRJCODE, QRC_CODEV, QRC_DATE, REC_DESC AS CUST_DESC, IR_CODE, ITM_CODE, ITM_NAME, GRP_CODE, JO_NUM, ITM_UNIT
									FROM tbl_qrc_detail WHERE QRC_NUM = '$QRCode' LIMIT 1";
				$resJO		= $this->db->query($sqlJO)->result();
				foreach($resJO as $rowJO) :
					$PRJCODE	= $rowJO->PRJCODE;
					$QRC_CODEV	= $rowJO->QRC_CODEV;
					$QRC_DATE	= $rowJO->QRC_DATE;
					$CUST_DESC	= $rowJO->CUST_DESC;
					$IR_CODE	= $rowJO->IR_CODE;
					$ITM_CODE	= $rowJO->ITM_CODE;
					$ITM_NAME	= $rowJO->ITM_NAME;
					$GRP_CODE	= $rowJO->GRP_CODE;
					$JO_NUM		= $rowJO->JO_NUM;
					$ITM_UNIT	= $rowJO->ITM_UNIT;
				endforeach;

				$JO_CODE	= '';
				$SO_CODE	= '';
				$SO_DATEV 	= '';
				$BOM_CODE	= '';
				$BOM_NAME	= '';
				if($JO_NUM != '')
				{
					$sqlSO		= "SELECT A.JO_CODE, A.JO_DATE, A.SO_CODE, B.SO_DATE, A.BOM_CODE
									FROM tbl_jo_header A
										INNER JOIN tbl_so_header B ON A.SO_NUM = B.SO_NUM
									WHERE A.JO_NUM = '$JO_NUM' LIMIT 1";
					$resSO		= $this->db->query($sqlSO)->result();
					foreach($resSO as $rowSO) :
						$JO_CODE	= $rowSO->JO_CODE;
						$JO_DATEV	= date('d-m-Y', strtotime($rowSO->JO_DATE));
						$SO_CODE	= $rowSO->SO_CODE;
						$SO_DATEV	= date('d-m-Y', strtotime($rowSO->SO_DATE));
						$BOM_CODE	= $rowSO->BOM_CODE;
					endforeach;

					if($BOM_CODE != '')
					{
						$sqlBOM		= "SELECT BOM_NAME FROM tbl_bom_header WHERE BOM_CODE = '$BOM_CODE' LIMIT 1";
						$sqlBOM		= $this->db->query($sqlBOM)->result();
						foreach($sqlBOM as $rowBOM) :
							$BOM_NAME	= $rowBOM->BOM_NAME;
						endforeach;						
					}
				}
				echo "$ISEXIST~QRCL~$PRJCODE~$QRCode~$QRC_CODEV~$QRC_DATE~$CUST_DESC~$IR_CODE~$ITM_NAME~$GRP_CODE~$JO_NUM~$JO_CODE~$JO_DATEV~$SO_CODE~$SO_DATEV~$BOM_CODE~$BOM_NAME~$ITM_CODE~$ITM_UNIT";
			}
			else
			{
				$ISEXIST	= 0;
			}

		// 2. CHECK IN GROUP LIST
			$sqlC	= "tbl_item_collh WHERE ICOLL_CODE = '$QRCode'";
			$resC 	= $this->db->count_all($sqlC);
			if($resC > 0)
			{
				$ISEXIST	= 1;
				$PRJCODE	= '';
				//$ITM_CODE	= '';
				$IR_CODE	= '';
				$QRC_CODEV	= '';
				$QRC_DATE	= '';
				$CUST_DESC	= '';
				$IR_CODE	= '';
				$ITM_CODE 	= '';
				$ITM_NAME	= '';
				$GRP_CODE	= '';
				$JO_NUM		= '';
				$ITM_UNIT	= '';
				$sqlJO		= "SELECT PRJCODE, ICOLL_CODE AS QRC_CODEV, ICOLL_CREATED AS QRC_DATE, CUST_DESC, ICOLL_REFNUM AS IR_CODE,
									ICOLL_NOTES AS ITM_NAME, ICOLL_CODE AS GRP_CODE, JO_NUM
								FROM tbl_item_collh WHERE ICOLL_CODE = '$QRCode' LIMIT 1";
				$resJO		= $this->db->query($sqlJO)->result();
				foreach($resJO as $rowJO) :
					$PRJCODE	= $rowJO->PRJCODE;
					$QRC_CODEV	= $rowJO->QRC_CODEV;
					$QRC_DATE	= $rowJO->QRC_DATE;
					$CUST_DESC	= $rowJO->CUST_DESC;
					$IR_CODE	= $rowJO->IR_CODE;
					$ITM_NAME	= $rowJO->ITM_NAME;
					$GRP_CODE	= $rowJO->GRP_CODE;
					$JO_NUM		= $rowJO->JO_NUM;
				endforeach;

				$JO_CODE	= '';
				$SO_CODE	= '';
				$SO_DATEV 	= '';
				$BOM_CODE	= '';
				$BOM_NAME	= '';
				if($JO_NUM != '')
				{
					$sqlSO		= "SELECT A.JO_CODE, A.JO_DATE, A.SO_CODE, B.SO_DATE, A.BOM_CODE
									FROM tbl_jo_header A
										INNER JOIN tbl_so_header B ON A.SO_NUM = B.SO_NUM
									WHERE A.JO_NUM = '$JO_NUM' LIMIT 1";
					$resSO		= $this->db->query($sqlSO)->result();
					foreach($resSO as $rowSO) :
						$JO_CODE	= $rowSO->JO_CODE;
						$JO_DATEV	= date('d-m-Y', strtotime($rowSO->JO_DATE));
						$SO_CODE	= $rowSO->SO_CODE;
						$SO_DATEV	= date('d-m-Y', strtotime($rowSO->SO_DATE));
						$BOM_CODE	= $rowSO->BOM_CODE;
					endforeach;

					if($BOM_CODE != '')
					{
						$sqlBOM		= "SELECT BOM_NAME FROM tbl_bom_header WHERE BOM_CODE = '$BOM_CODE' LIMIT 1";
						$sqlBOM		= $this->db->query($sqlBOM)->result();
						foreach($sqlBOM as $rowBOM) :
							$BOM_NAME	= $rowBOM->BOM_NAME;
						endforeach;						
					}
				}
				echo "$ISEXIST~GRPL~$PRJCODE~$QRCode~$QRC_CODEV~$QRC_DATE~$CUST_DESC~$IR_CODE~$ITM_NAME~$GRP_CODE~$JO_NUM~$JO_CODE~$JO_DATEV~$SO_CODE~$SO_DATEV~$BOM_CODE~$BOM_NAME~$ITM_CODE~$ITM_UNIT";
			}
			else
			{
				$ISEXIST	= 0;
			}

		// 3. CHECK IN JO LIST
			$sqlC	= "tbl_jo_header WHERE JO_UC = '$QRCode'";
			$resC 	= $this->db->count_all($sqlC);
			if($resC > 0)
			{
				$ISEXIST	= 1;
				$PRJCODE	= '';
				//$ITM_CODE	= '';
				$IR_CODE	= '';
				$QRC_CODEV	= '';
				$QRC_DATE	= '';
				$CUST_DESC	= '';
				$IR_CODE	= '';
				$ITM_CODE 	= '';
				$ITM_NAME	= '';
				$GRP_CODE	= '';
				$JO_NUM		= '';
				$JO_CODE	= '';
				$JO_DATEV	= '';
				$SO_CODE	= '';
				$SO_DATEV 	= '';
				$BOM_CODE	= '';
				$BOM_NAME	= '';
				$ITM_UNIT	= '';

				$sqlJO		= "SELECT A.PRJCODE, A.JO_CODE AS QRC_CODEV, A.JO_DATE AS QRC_DATE, A.CUST_DESC, '' AS IR_CODE,
									A.JO_NOTES AS ITM_NAME, '' AS GRP_CODE, A.JO_NUM, A.JO_CODE, A.JO_DATE, A.SO_CODE,
									B.SO_DATE, A.BOM_CODE
								FROM tbl_jo_header A
									INNER JOIN tbl_so_header B ON A.SO_NUM = B.SO_NUM
								WHERE A.JO_UC = '$QRCode' LIMIT 1";
				$resJO		= $this->db->query($sqlJO)->result();
				foreach($resJO as $rowJO) :
					$PRJCODE	= $rowJO->PRJCODE;
					$QRC_CODEV	= $rowJO->QRC_CODEV;
					$QRC_DATE	= $rowJO->QRC_DATE;
					$CUST_DESC	= $rowJO->CUST_DESC;
					$IR_CODE	= $rowJO->IR_CODE;
					$ITM_NAME	= $rowJO->ITM_NAME;
					$GRP_CODE	= $rowJO->GRP_CODE;
					$JO_NUM		= $rowJO->JO_NUM;
					$JO_CODE	= $rowJO->JO_CODE;
					$JO_DATEV	= date('d-m-Y', strtotime($rowJO->JO_DATE));
					$SO_CODE	= $rowJO->SO_CODE;
					$SO_DATEV	= date('d-m-Y', strtotime($rowJO->SO_DATE));
					$BOM_CODE	= $rowJO->BOM_CODE;
				endforeach;

				if($BOM_CODE != '')
				{
					$sqlBOM		= "SELECT BOM_NAME FROM tbl_bom_header WHERE BOM_CODE = '$BOM_CODE' LIMIT 1";
					$sqlBOM		= $this->db->query($sqlBOM)->result();
					foreach($sqlBOM as $rowBOM) :
						$BOM_NAME	= $rowBOM->BOM_NAME;
					endforeach;						
				}

				echo "$ISEXIST~JOL~$PRJCODE~$QRCode~$QRC_CODEV~$QRC_DATE~$CUST_DESC~$IR_CODE~$ITM_NAME~$GRP_CODE~$JO_NUM~$JO_CODE~$JO_DATEV~$SO_CODE~$SO_DATEV~$BOM_CODE~$BOM_NAME~$ITM_CODE~$ITM_UNIT";
			}
			else
			{
				$ISEXIST	= 0;
				echo "no";
			}

	}
	
	function GetDataQRC() // GOOD
	{

		$QRCode		= $this->input->post('scanned-QRText');
		// GET INFORMATION TYPE
		// 1. CHECK IN QRC List
			$sqlC	= "tbl_qrc_detail WHERE QRC_NUM = '$QRCode'";
			$resC 	= $this->db->count_all($sqlC);
			if($resC > 0)
			{
				$ISEXIST	= 1;
				$PRJCODE	= '';
				//$ITM_CODE	= '';
				$IR_CODE	= '';
				$QRC_CODEV	= '';
				$QRC_DATE	= '';
				$CUST_DESC	= '';
				$IR_CODE	= '';
				$ITM_CODE 	= '';
				$ITM_NAME	= '';
				$GRP_CODE	= '';
				$JO_NUM		= '';
				$ITM_UNIT	= '';
				$ITM_QTY	= 0;
				$sqlJO		= "SELECT PRJCODE, QRC_CODEV, QRC_DATE, REC_DESC AS CUST_DESC, IR_CODE, ITM_CODE, ITM_NAME, GRP_CODE, JO_NUM, ITM_UNIT, ITM_QTY, QRC_STAT
									FROM tbl_qrc_detail WHERE QRC_NUM = '$QRCode' LIMIT 1";
				$resJO		= $this->db->query($sqlJO)->result();
				foreach($resJO as $rowJO) :
					$PRJCODE	= $rowJO->PRJCODE;
					$QRC_CODEV	= $rowJO->QRC_CODEV;
					$QRC_DATE	= $rowJO->QRC_DATE;
					$CUST_DESC	= $rowJO->CUST_DESC;
					$IR_CODE	= $rowJO->IR_CODE;
					$ITM_CODE	= $rowJO->ITM_CODE;
					$ITM_NAME	= $rowJO->ITM_NAME;
					$GRP_CODE	= $rowJO->GRP_CODE;
					$JO_NUM		= $rowJO->JO_NUM;
					$ITM_UNIT	= $rowJO->ITM_UNIT;
					$ITM_QTY	= $rowJO->ITM_QTY;
					$QRC_STAT	= $rowJO->QRC_STAT;
				endforeach;

				$JO_CODE	= '';
				$SO_CODE	= '';
				$SO_DATEV 	= '';
				$BOM_CODE	= '';
				$BOM_NAME	= '';
				$JO_DATEV	= '';
				if($JO_NUM != '')
				{
					$sqlSO		= "SELECT A.JO_CODE, A.JO_DATE, A.SO_CODE, B.SO_DATE, A.BOM_CODE
									FROM tbl_jo_header A
										INNER JOIN tbl_so_header B ON A.SO_NUM = B.SO_NUM
									WHERE A.JO_NUM = '$JO_NUM' LIMIT 1";
					$resSO		= $this->db->query($sqlSO)->result();
					foreach($resSO as $rowSO) :
						$JO_CODE	= $rowSO->JO_CODE;
						$JO_DATEV	= date('d-m-Y', strtotime($rowSO->JO_DATE));
						$SO_CODE	= $rowSO->SO_CODE;
						$SO_DATEV	= date('d-m-Y', strtotime($rowSO->SO_DATE));
						$BOM_CODE	= $rowSO->BOM_CODE;
					endforeach;

					if($BOM_CODE != '')
					{
						$sqlBOM		= "SELECT BOM_NAME FROM tbl_bom_header WHERE BOM_CODE = '$BOM_CODE' LIMIT 1";
						$sqlBOM		= $this->db->query($sqlBOM)->result();
						foreach($sqlBOM as $rowBOM) :
							$BOM_NAME	= $rowBOM->BOM_NAME;
						endforeach;						
					}
				}

				if($GRP_CODE == '')
				{
					echo "1~QRCL~$PRJCODE~$QRCode~$QRC_CODEV~$QRC_DATE~$CUST_DESC~$IR_CODE~$ITM_NAME~$GRP_CODE~$JO_NUM~$JO_CODE~$JO_DATEV~$SO_CODE~$SO_DATEV~$BOM_CODE~$BOM_NAME~$ITM_CODE~$ITM_UNIT~$ITM_QTY";
				}
				else
				{

					echo "2~QRCL~$PRJCODE~$QRCode~$QRC_CODEV~$QRC_DATE~$CUST_DESC~$IR_CODE~$ITM_NAME~$GRP_CODE~$JO_NUM~$JO_CODE~$JO_DATEV~$SO_CODE~$SO_DATEV~$BOM_CODE~$BOM_NAME~$ITM_CODE~$ITM_UNIT~$ITM_QTY";
				}
			}
			else
			{
				$ISEXIST	= 1;
			}

	}
	
	function GetDataQRCSN() // GOOD
	{
		//$QRC_NUM	= $this->input->post('scanned-QRText');
		$QRC_NUM	= $this->input->post('QRCode');
		// GET INFORMATION TYPE
		// 1. CHECK IN QRC List
			$sqlC	= "tbl_qrc_detail WHERE QRC_NUM = '$QRC_NUM'";
			$resC 	= $this->db->count_all($sqlC);
			if($resC > 0)
			{
				$sqlSNC	= "tbl_sn_detail_qrc WHERE QRC_NUM = '$QRC_NUM' AND QRC_ISVOID = 0";
				$resSNC = $this->db->count_all($sqlSNC);
				if($resSNC == 0)
				{
					 $PRJCODE	= '';
					$CUST_CODE	= '';
					$CUST_DESC	= '';
					$ITM_CODE	= '';
					$ITM_NAME	= '';
					$ITM_UNIT 	= '';
					$QRC_CODEV	= '';
					$QRC_VOLM	= 0;
					$JO_NUM		= '';

					$sqlQRC		= "SELECT PRJCODE, REC_FROM, REC_DESC, ITM_CODE, ITM_NAME, ITM_UNIT, QRC_CODEV, ITM_QTY, JO_NUM
									FROM tbl_qrc_detail WHERE QRC_NUM = '$QRC_NUM' LIMIT 1";
					$resQRC		= $this->db->query($sqlQRC)->result();
					foreach($resQRC as $rowQRC) :
						$PRJCODE	= $rowQRC->PRJCODE;
						$CUST_CODE	= $rowQRC->REC_FROM;
						$CUST_DESC	= $rowQRC->REC_DESC;
						$ITM_CODE	= $rowQRC->ITM_CODE;
						$ITM_NAME	= $rowQRC->ITM_NAME;
						$ITM_UNIT	= $rowQRC->ITM_UNIT;
						$QRC_CODEV	= $rowQRC->QRC_CODEV;
						$QRC_VOLM	= $rowQRC->ITM_QTY;
						$JO_NUM		= $rowQRC->JO_NUM;
					endforeach;

					$JO_CODE	= '';
					$SO_NUM		= '';
					$SO_CODE	= '';
					$SO_DATEV 	= '';
					$BOM_CODE	= '';
					$BOM_NAME	= '';
					$JO_DATEV	= '';
					$JO_NOTES	= '';
					if($JO_NUM != '')
					{
						$sqlSO		= "SELECT A.JO_CODE, A.JO_DATE, A.SO_NUM, A.SO_CODE, B.SO_DATE, A.BOM_CODE, A.JO_NOTES
										FROM tbl_jo_header A
											INNER JOIN tbl_so_header B ON A.SO_NUM = B.SO_NUM
										WHERE A.JO_NUM = '$JO_NUM' LIMIT 1";
						$resSO		= $this->db->query($sqlSO)->result();
						foreach($resSO as $rowSO) :
							$SO_NUM		= $rowSO->SO_NUM;
							$JO_CODE	= $rowSO->JO_CODE;
							$JO_DATEV	= date('d-m-Y', strtotime($rowSO->JO_DATE));
							$SO_CODE	= $rowSO->SO_CODE;
							$SO_DATEV	= date('d-m-Y', strtotime($rowSO->SO_DATE));
							$BOM_CODE	= $rowSO->BOM_CODE;
							$JO_NOTES	= $rowSO->JO_NOTES;
						endforeach;

						if($BOM_CODE != '')
						{
							$sqlBOM		= "SELECT BOM_NAME FROM tbl_bom_header WHERE BOM_CODE = '$BOM_CODE' LIMIT 1";
							$sqlBOM		= $this->db->query($sqlBOM)->result();
							foreach($sqlBOM as $rowBOM) :
								$BOM_NAME	= $rowBOM->BOM_NAME;
							endforeach;						
						}
					}

					echo "1~QRCL~$PRJCODE~$CUST_CODE~$CUST_DESC~$SO_NUM~$SO_CODE~$SO_DATEV~$JO_NUM~$JO_CODE~$JO_DATEV~$ITM_CODE~$ITM_NAME~$ITM_UNIT~$QRC_NUM~$QRC_CODEV~$QRC_VOLM~$BOM_CODE~$JO_NOTES";
				}
				else
				{
					$PRJCODE	= '';
					$CUST_CODE	= '';
					$CUST_DESC	= '';
					$ITM_CODE	= '';
					$ITM_NAME	= '';
					$ITM_UNIT 	= '';
					$QRC_CODEV	= '';
					$QRC_VOLM	= 0;
					$JO_NUM		= '';

					$sqlQRC		= "SELECT PRJCODE, REC_FROM, REC_DESC, ITM_CODE, ITM_NAME, ITM_UNIT, QRC_CODEV, ITM_QTY, JO_NUM
									FROM tbl_qrc_detail WHERE QRC_NUM = '$QRC_NUM' LIMIT 1";
					$resQRC		= $this->db->query($sqlQRC)->result();
					foreach($resQRC as $rowQRC) :
						$PRJCODE	= $rowQRC->PRJCODE;
						$CUST_CODE	= $rowQRC->REC_FROM;
						$CUST_DESC	= $rowQRC->REC_DESC;
						$ITM_CODE	= $rowQRC->ITM_CODE;
						$ITM_NAME	= $rowQRC->ITM_NAME;
						$ITM_UNIT	= $rowQRC->ITM_UNIT;
						$QRC_CODEV	= $rowQRC->QRC_CODEV;
						$QRC_VOLM	= $rowQRC->ITM_QTY;
						$JO_NUM		= $rowQRC->JO_NUM;
					endforeach;

					$JO_CODE	= '';
					$SO_NUM		= '';
					$SO_CODE	= '';
					$SO_DATEV 	= '';
					$BOM_CODE	= '';
					$BOM_NAME	= '';
					$JO_DATEV	= '';
					if($JO_NUM != '')
					{
						$sqlSO		= "SELECT A.JO_CODE, A.JO_DATE, A.SO_CODE, B.SO_DATE, A.BOM_CODE
										FROM tbl_jo_header A
											INNER JOIN tbl_so_header B ON A.SO_NUM = B.SO_NUM
										WHERE A.JO_NUM = '$JO_NUM' LIMIT 1";
						$resSO		= $this->db->query($sqlSO)->result();
						foreach($resSO as $rowSO) :
							$JO_CODE	= $rowSO->JO_CODE;
							$JO_DATEV	= date('d-m-Y', strtotime($rowSO->JO_DATE));
							$SO_CODE	= $rowSO->SO_CODE;
							$SO_DATEV	= date('d-m-Y', strtotime($rowSO->SO_DATE));
							$BOM_CODE	= $rowSO->BOM_CODE;
						endforeach;

						if($BOM_CODE != '')
						{
							$sqlBOM		= "SELECT BOM_NAME FROM tbl_bom_header WHERE BOM_CODE = '$BOM_CODE' LIMIT 1";
							$sqlBOM		= $this->db->query($sqlBOM)->result();
							foreach($sqlBOM as $rowBOM) :
								$BOM_NAME	= $rowBOM->BOM_NAME;
							endforeach;						
						}
					}

					$SN_CODE	= '';
					$SN_DATEV 	= '';
					$sqlQRCSN 	= "SELECT SN_CODE, SN_DATE FROM tbl_sn_detail_qrc WHERE QRC_NUM = '$QRC_NUM'";
					$resQRCSN	= $this->db->query($sqlQRCSN)->result();
					foreach($resQRCSN as $rowQRCSN) :
						$SN_CODE 	= $rowQRCSN->SN_CODE;
						$SN_DATEV	= date('d-m-Y', strtotime($rowQRCSN->SN_DATE));
					endforeach;

					echo "2~QRCL~$SN_CODE~$SN_DATEV~$CUST_DESC~$SO_NUM~$SO_CODE~$JO_NUM~$JO_CODE~$ITM_CODE~$ITM_NAME~$ITM_UNIT~$QRC_NUM~$QRC_CODEV~$QRC_VOLM";
				}
			}
			else
			{
				$ISEXIST	= 0;
			}
	}

	
	function GetDataQRCSNXX() // GOOD
	{
		$QRC_NUM	= $this->input->post('QRCode');
		echo "1~QRCL~$QRC_NUM";
	}
	
	function GetDataQRCSR() // GOOD SALES RETURN
	{

		$QRC_NUM	= $this->input->post('scanned-QRText');
		// GET INFORMATION TYPE
			$sqlC	= "tbl_qrc_detail WHERE QRC_NUM = '$QRC_NUM'";
			$resC 	= $this->db->count_all($sqlC);
			if($resC > 0)
			{
				$sqlSNC	= "tbl_sn_detail_qrc WHERE QRC_NUM = '$QRC_NUM' AND QRC_ISRET = '0'";
				$resSNC = $this->db->count_all($sqlSNC);
				if($resSNC > 0)
				{
					$PRJCODE	= '';
					$SN_NUM		= '';
					$SN_CODE	= '';
					$SN_DATEV	= '';
					$CUST_CODE	= '';
					$ITM_CODE	= '';
					$ITM_NAME	= '';
					$ITM_UNIT 	= '';
					$QRC_CODEV	= '';
					$QRC_VOLM	= 0;
					$JO_NUM		= '';
					$QRC_PRICE	= 0;
					$CUST_ADD 	= '';

					$SR_CODE	= '';
					$SR_DATE	= '';

					$sqlQRC		= "SELECT A.PRJCODE, A.SN_NUM, A.SN_CODE, A.SN_DATE, A.CUST_CODE, A.ITM_CODE, ITM_NAME, A.ITM_UNIT, QRC_CODEV, QRC_VOLM,
										QRC_PRICE, A.JO_NUM, CUST_ADDRESS
									FROM tbl_sn_detail_qrc A INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
									INNER JOIN tbl_sn_header C ON A.SN_NUM = C.SN_NUM
									WHERE QRC_NUM = '$QRC_NUM' LIMIT 1";
					$resQRC		= $this->db->query($sqlQRC)->result();
					foreach($resQRC as $rowQRC) :
						$PRJCODE	= $rowQRC->PRJCODE;
						$SN_NUM		= $rowQRC->SN_NUM;
						$SN_CODE	= $rowQRC->SN_CODE;
						$SN_DATEV	= date('d-m-Y', strtotime($rowQRC->SN_DATE));
						$CUST_CODE	= $rowQRC->CUST_CODE;
						$ITM_CODE	= $rowQRC->ITM_CODE;
						$ITM_NAME	= $rowQRC->ITM_NAME;
						$ITM_UNIT	= $rowQRC->ITM_UNIT;
						$QRC_CODEV	= $rowQRC->QRC_CODEV;
						$QRC_VOLM	= $rowQRC->QRC_VOLM;
						$QRC_PRICE	= $rowQRC->QRC_PRICE;
						$JO_NUM		= $rowQRC->JO_NUM;
						$CUST_ADD 	= $rowQRC->CUST_ADDRESS;
					endforeach;

					$JO_CODE	= '';
					$SO_NUM		= '';
					$SO_CODE	= '';
					$SO_DATEV 	= '';
					$BOM_CODE	= '';
					$BOM_NAME	= '';
					$JO_DATEV	= '';
					$CUST_DESC	= '';

					$sqlSO		= "SELECT A.JO_CODE, A.JO_DATE, A.SO_CODE, B.SO_DATE, A.BOM_CODE, A.CUST_DESC, A.JO_NOTES
									FROM tbl_jo_header A
										INNER JOIN tbl_so_header B ON A.SO_NUM = B.SO_NUM
									WHERE A.JO_NUM = '$JO_NUM' LIMIT 1";
					$resSO		= $this->db->query($sqlSO)->result();
					foreach($resSO as $rowSO) :
						$JO_CODE	= $rowSO->JO_CODE;
						$JO_DATEV	= date('d-m-Y', strtotime($rowSO->JO_DATE));
						$SO_CODE	= $rowSO->SO_CODE;
						$SO_DATEV	= date('d-m-Y', strtotime($rowSO->SO_DATE));
						$BOM_CODE	= $rowSO->BOM_CODE;
						$CUST_DESC	= $rowSO->CUST_DESC;
						$JO_NOTES	= $rowSO->JO_NOTES;
					endforeach;

					if($BOM_CODE != '')
					{
						$sqlBOM		= "SELECT BOM_NAME FROM tbl_bom_header WHERE BOM_CODE = '$BOM_CODE' LIMIT 1";
						$sqlBOM		= $this->db->query($sqlBOM)->result();
						foreach($sqlBOM as $rowBOM) :
							$BOM_NAME	= $rowBOM->BOM_NAME;
						endforeach;
					}

					// CEK STATUS FAKTUR
						$sqlSN		= "tbl_sinv_detail A INNER JOIN tbl_sinv_header B ON A.SINV_NUM = B.SINV_NUM WHERE A.SN_NUM = '$SN_NUM' AND B.SINV_STAT IN (3,6)";
						$sqlSNC		= $this->db->count_all($sqlSN);
						$SINVC 		= $sqlSNC ?: 0;

						$SINVDET 	= "";
						if($SINVC > 0)
						{
							$SINVDET 	= "";
							$sqlsINV	= "SELECT A.SINV_CODE, B.SINV_DATE FROM tbl_sinv_detail A INNER JOIN tbl_sinv_header B ON A.SINV_NUM = B.SINV_NUM
											WHERE A.SN_NUM = '$SN_NUM' AND B.SINV_STAT IN (3,6)";
							$ressINV	= $this->db->query($sqlsINV)->result();
							foreach($ressINV as $rowsinv) :
								$SINV_CODE	= $rowsinv->SINV_CODE;
								$SINV_DATE	= $rowsinv->SINV_DATE;
								$SINVDET 	= "$SINV_CODE ($SINV_DATE)";
							endforeach;
						}

					echo "1~QRCL~$PRJCODE~$CUST_CODE~$CUST_DESC~$SO_NUM~$SO_CODE~$SO_DATEV~$JO_NUM~$JO_CODE~$JO_DATEV~$ITM_CODE~$ITM_NAME~$ITM_UNIT~$QRC_NUM~$QRC_CODEV~$QRC_VOLM~$QRC_PRICE~$BOM_CODE~$BOM_NAME~$JO_NOTES~$SN_NUM~$SN_CODE~$SN_DATEV~$CUST_ADD~$SR_CODE~$SR_DATE~$SINVC~$SINVDET";
				}
				else
				{
					$PRJCODE	= '';
					$SN_NUM		= '';
					$SN_CODE	= '';
					$SN_DATEV	= '';
					$CUST_CODE	= '';
					$ITM_CODE	= '';
					$ITM_NAME	= '';
					$ITM_UNIT 	= '';
					$QRC_CODEV	= '';
					$QRC_VOLM	= 0;
					$JO_NUM		= '';
					$QRC_PRICE	= 0;
					$CUST_ADD 	= '';

					$JO_CODE	= '';
					$JO_NOTES	= '';
					$SO_NUM		= '';
					$SO_CODE	= '';
					$SO_DATEV 	= '';
					$BOM_CODE	= '';
					$BOM_NAME	= '';
					$JO_DATEV	= '';
					$CUST_DESC	= '';

					$SR_CODE	= '';
					$SR_DATE	= '';

					$sqlSNC	= "tbl_sn_detail_qrc WHERE QRC_NUM = '$QRC_NUM' AND QRC_ISRET = '1'";
					$resSNC = $this->db->count_all($sqlSNC);
					if($resSNC > 0)
					{
						$CATGV	= 2;
						
						$sqlSR	= "SELECT SR_CODE, SR_DATE FROM tbl_sr_detail_qrc WHERE QRC_NUM = '$QRC_NUM'";
						$resSR	= $this->db->query($sqlSR)->result();
						foreach($resSR as $rowSR) :
							$SR_CODE	= $rowSR->SR_CODE;
							$SR_DATE	= date('d-m-Y', strtotime($rowSR->SR_DATE));
						endforeach;


						$sqlQRC		= "SELECT A.PRJCODE, A.SN_NUM, A.SN_CODE, A.SN_DATE, A.CUST_CODE, A.ITM_CODE, ITM_NAME, A.ITM_UNIT, QRC_CODEV, QRC_VOLM,
											QRC_PRICE, A.JO_NUM, CUST_ADDRESS
										FROM tbl_sn_detail_qrc A INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
										INNER JOIN tbl_sn_header C ON A.SN_NUM = C.SN_NUM
										WHERE QRC_NUM = '$QRC_NUM' AND QRC_ISRET = '1' LIMIT 1";
						$resQRC		= $this->db->query($sqlQRC)->result();
						foreach($resQRC as $rowQRC) :
							$PRJCODE	= $rowQRC->PRJCODE;
							$SN_NUM		= $rowQRC->SN_NUM;
							$SN_CODE	= $rowQRC->SN_CODE;
							$SN_DATEV	= date('d-m-Y', strtotime($rowQRC->SN_DATE));
							$CUST_CODE	= $rowQRC->CUST_CODE;
							$ITM_CODE	= $rowQRC->ITM_CODE;
							$ITM_NAME	= $rowQRC->ITM_NAME;
							$ITM_UNIT	= $rowQRC->ITM_UNIT;
							$QRC_CODEV	= $rowQRC->QRC_CODEV;
							$QRC_VOLM	= $rowQRC->QRC_VOLM;
							$QRC_PRICE	= $rowQRC->QRC_PRICE;
							$JO_NUM		= $rowQRC->JO_NUM;
							$CUST_ADD 	= $rowQRC->CUST_ADDRESS;
						endforeach;

						$sqlSO			= "SELECT A.JO_CODE, A.JO_DATE, A.SO_CODE, B.SO_DATE, A.BOM_CODE, A.CUST_DESC, A.JO_NOTES
											FROM tbl_jo_header A
												INNER JOIN tbl_so_header B ON A.SO_NUM = B.SO_NUM
											WHERE A.JO_NUM = '$JO_NUM' LIMIT 1";
						$resSO			= $this->db->query($sqlSO)->result();
						foreach($resSO as $rowSO) :
							$JO_CODE	= $rowSO->JO_CODE;
							$JO_DATEV	= date('d-m-Y', strtotime($rowSO->JO_DATE));
							$SO_CODE	= $rowSO->SO_CODE;
							$SO_DATEV	= date('d-m-Y', strtotime($rowSO->SO_DATE));
							$BOM_CODE	= $rowSO->BOM_CODE;
							$CUST_DESC	= $rowSO->CUST_DESC;
							$JO_NOTES	= $rowSO->JO_NOTES;
						endforeach;

						if($BOM_CODE != '')
						{
							$sqlBOM		= "SELECT BOM_NAME FROM tbl_bom_header WHERE BOM_CODE = '$BOM_CODE' LIMIT 1";
							$sqlBOM		= $this->db->query($sqlBOM)->result();
							foreach($sqlBOM as $rowBOM) :
								$BOM_NAME	= $rowBOM->BOM_NAME;
							endforeach;
						}
					}
					else
					{
						$CATGV	= 3;
					}

					$SINVDET 	= "";
					$QRC_PRICE	= 0;

					echo "$CATGV~QRCL~$PRJCODE~$CUST_CODE~$CUST_DESC~$SO_NUM~$SO_CODE~$SO_DATEV~$JO_NUM~$JO_CODE~$JO_DATEV~$ITM_CODE~$ITM_NAME~$ITM_UNIT~$QRC_NUM~$QRC_CODEV~$QRC_VOLM~$QRC_PRICE~$BOM_CODE~$BOM_NAME~$JO_NOTES~$SN_NUM~$SN_CODE~$SN_DATEV~$CUST_ADD~$SR_CODE~$SR_DATE~0~$SINVDET";
				}
			}
			else
			{
				$ISEXIST	= 0;		// NOT EXIST QRC
			}

	}
	
 	function g3NQrc0d3() // GOOD
	{
		$this->load->model('m_production/m_prodprocess', '', TRUE);
		
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url		= site_url('Y_5c4nQR/g3NQrc0d3_u5r/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function g3NQrc0d3_u5r() // GOOD
	{
		$this->load->model('m_production/m_prodprocess', '', TRUE);
					
		$data['title'] 		= "1stWeb Scanner";	
		$LangID 			= "IND";			
		$data["h1_title"] 	= "Scan QR Code";			
		$data['form_action']= site_url('c_production/c_pR04uctpr0535/add_process');

		$this->load->view('y_Y_5c4_nQR_u5r', $data);
	}
}