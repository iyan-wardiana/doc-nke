<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 15 Agustus 2017
 * File Name	= Profit_loss.php
 * Location		= -
*/

/* 
 * Author		= Hendar Permana
 * Create Date	= 18 Oktober 2017
 * File Name	= c_jo.php
 * Location		= -
*/

class c_jo  extends CI_Controller  
{
 	public function index() // U
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;
		
		$url			= site_url('c_project/c_jo/index1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	public function index1() // U
	{
		$this->load->model('m_project/m_jo/m_jo', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['h2_title'] 	= 'Project List';
			$data['h3_title'] 	= 'J O';
			
			$num_rows 			= $this->m_jo->count_all_project($DefEmp_ID);
			$data["recCount"] 	= $num_rows;
	 		$data["MenuCode"] 	= 'MN302';
			$data['vwProject']	= $this->m_jo->get_all_project($DefEmp_ID)->result();
			
			$this->load->view('v_project/v_jo/v_jo_project_list', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function get_all_jo() // U
	{
		$this->load->model('m_project/m_jo/m_jo', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
						
			$data['title'] 			= $appName;
			$data['h2_title'] 		= 'J O List';
			$data['h3_title'] 		= 'J O';		
			//$data['link'] 			= array('link_back' => anchor('c_project/c_jo/','<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Back" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 		= site_url('c_project/c_jo/');
			$data['PRJCODE'] 		= $PRJCODE;
			
			$num_rows 				= $this->m_jo->count_all_jo($PRJCODE);
			$data["recCount"] 		= $num_rows;
			$data['MenuCode'] 		= 'MN302';
	 
			$data['vwJo']		= $this->m_jo->get_all_jo($PRJCODE)->result();
			
			$this->load->view('v_project/v_jo/v_jo_list', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function add() // U
	{
		$this->load->model('m_project/m_jo/m_jo', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName 	= $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
						
			$data['PRJCODE'] 	= $PRJCODE;	
			$docPatternPosition = 'Especially';	
			$data['title'] 		= $appName;
			$data['task'] 		= 'add';
			$data['h2_title']	= 'Add';
			$data['h3_title']	= 'J O';
			$data['form_action']= site_url('c_project/c_jo/add_process');
			
			$backURL			= site_url('c_project/c_jo/get_all_jo/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['link'] 		= array('link_back' => anchor("$backURL",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 	= $backURL;
			$data['recCountPrj']= $this->m_jo->count_all_project($DefEmp_ID);
			$data['vwProject'] 	= $this->m_jo->get_all_project($DefEmp_ID)->result();
			
			$MenuCode 			= 'MN302';
			$data["MenuCode"] 	= 'MN302';
			$data['vwDocPatt'] 	= $this->m_jo->getDataDocPat($MenuCode)->result();
			
			$this->load->view('v_project/v_jo/v_jo_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	function add_process() // U
	{
		$this->load->model('m_project/m_jo/m_jo', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();
			
			date_default_timezone_set("Asia/Jakarta");
			
			/*$JO_CODE 	= $this->input->post('JO_CODE');
			$SOPNH_MCODE 	= $this->input->post('SOPNH_MCODE');
			$SOPNH_PRJCODE 	= $this->input->post('SOPNH_PRJCODE');
			$PRJCODE 		= $this->input->post('SOPNH_PRJCODE');
			$SOPNH_PERIODE 	= date('Y-m-d',strtotime($this->input->post('SOPNH_PERIODE')));
			$SOPNH_WH 		= $this->input->post('SOPNH_WH');
			$SOPNH_NOTES 	= $this->input->post('SOPNH_NOTES');
			$SOPND_CREATED 	= date('Y-m-d H:i:s');;
			$SOPND_EMPID 	= $DefEmp_ID;
			$SOPNH_STAT		= $this->input->post('SOPNH_STAT'); // 1 = New, 2 = Awaiting, 3 = Approve, 4 = Revise, 5 = Reject
			
			$SOPNH_DATE		= date('Y-m-d',strtotime($this->input->post('SOPNH_DATE')));
			$Patt_Year		= date('Y',strtotime($this->input->post('SOPNH_DATE')));
			$Patt_Month		= date('m',strtotime($this->input->post('SOPNH_DATE')));
			$Patt_Date		= date('d',strtotime($this->input->post('SOPNH_DATE')));
			$Patt_Number 	= $this->input->post('Patt_Number');*/
			
			
			/*$InpJo 	= array('JO_CODE' => $JO_CODE,
								'SOPNH_MCODE' 	=> $SOPNH_MCODE,
								'SOPNH_DATE'	=> $SOPNH_DATE,
								'SOPNH_PRJCODE'	=> $SOPNH_PRJCODE,
								'SOPNH_PERIODE'	=> $SOPNH_PERIODE,
								'SOPNH_WH'		=> $SOPNH_WH,
								'SOPNH_NOTES'	=> $SOPNH_NOTES,
								'SOPND_CREATED'	=> $SOPND_CREATED, 
								'SOPND_EMPID'	=> $SOPND_EMPID, 
								'SOPNH_STAT'	=> $SOPNH_STAT, 
								'Patt_Year'		=> $Patt_Year, 
								'Patt_Month'	=> $Patt_Month,
								'Patt_Date'		=> $Patt_Date,
								'Patt_Number'	=> $Patt_Number);*/
								
			
			$JO_CODE 		= $this->input->post('JO_CODE');
			$JO_DATE 		= date('Y-m-d',strtotime($this->input->post('JO_DATE')));
			$JO_PRJCODE 	= $this->input->post('JO_PRJCODE');
			$JO_PERIODE 	= date('Y-m-d',strtotime($this->input->post('JO_PERIODE')));
			$JO_REF_NO	 	= $this->input->post('JO_REF_NO');
			$JO_DOC_NO 		= $this->input->post('JO_DOC_NO');
			$JO_NOTES 		= $this->input->post('JO_NOTES');
			$JO_CREATED 	= date('Y-m-d H:i:s');;
			$JO_CREATER 	= $DefEmp_ID;
			
			$InpJo 	= array('JO_CODE' 		=> $JO_CODE,
							'JO_DATE' 		=> $JO_DATE,
							'JO_PRJCODE' 	=> $JO_PRJCODE,
							'JO_PERIODE' 	=> $JO_PERIODE,
							'JO_REF_NO' 	=> $JO_REF_NO,
							'JO_DOC_NO' 	=> $JO_DOC_NO,
							'JO_NOTES'	 	=> $JO_NOTES,
							'JO_CREATER' 	=> $JO_CREATER,
							'JO_CREATED'	=> $JO_CREATED);
							
			$this->m_jo->add($InpJo);
			
			foreach($_POST['data'] as $d)
			{
				$this->db->insert('tbl_jo_detail',$d);
			}
			
			// UPDATE GRAND TOTAL PRICE -- INGAT !!! APABILA ADA REVISE HARUS ADA PENGURANGAN			
			/*if($SOPNH_STAT == 3)
			{
				$RSOPN_YEAR1	= date('Y',strtotime($this->input->post('SOPNH_PERIODE')));
				$RSOPN_YEAR		= (int)$RSOPN_YEAR1;
				$RSOPN_MONTH1	= date('m',strtotime($this->input->post('SOPNH_PERIODE')));
				$RSOPN_MONTH	= (int)$RSOPN_MONTH1;
				
				// UPDATE MATERIAL
					// CEK APAKAH ADA DATA MATERIAL DI DETAIL
					$sqlCM		= "tbl_sopn_detail WHERE SOPND_PRJCODE = '$SOPNH_PRJCODE' AND ITEM_TYPE = 'MTRL'
										AND YEAR(SOPND_DATE) = '$RSOPN_YEAR' AND MONTH(SOPND_DATE) = $RSOPN_MONTH AND SOPND_STAT = 3";
					$resCM		= $this->db->count_all($sqlCM);
					
					if($resCM > 0)
					{
						$GTQty_IN	= 0;
						$GTQty_OUT	= 0;
						$GTPrice	= 0;
						$sqlGT 		= "SELECT SUM(ITEM_IN_NOW) AS GTQty_IN, SUM(ITEM_OUT_NOW) AS GTQty_OUT,  SUM(ITEM_REM_PRICE) AS GTPrice 
										FROM tbl_sopn_detail
										WHERE SOPND_PRJCODE = '$PRJCODE' AND ITEM_TYPE = 'MTRL'
											AND YEAR(SOPND_DATE) = $RSOPN_YEAR AND MONTH(SOPND_DATE) = $RSOPN_MONTH AND SOPND_STAT = 3";
						$resGT 		= $this->db->query($sqlGT)->result();
						foreach($resGT as $row) :
							$GTQty_IN 	= $row->GTQty_IN;	
							$GTQty_OUT	= $row->GTQty_OUT;	
							$GTPrice 	= $row->GTPrice;		
						endforeach;
						$RSOPN_REMQTY	= $GTQty_IN - $GTQty_OUT;
						
						$sqlCPerM	= "tbl_sopn_concl WHERE RSOPN_PRJCODE = '$SOPNH_PRJCODE' AND RSOPN_MTRTYPE = 'MTRL'
											AND RSOPN_YEAR = '$RSOPN_YEAR' AND RSOPN_MONTH = $RSOPN_MONTH";
						$resCCPer	= $this->db->count_all($sqlCPerM);
						
						if($resCCPer > 0)
						{					
							$sqlUpd		= "UPDATE tbl_sopn_concl 
												SET RSOPN_QTYIN = '$GTQty_IN', RSOPN_QTYOUT = '$GTQty_OUT',
												RSOPN_REMQTY = '$RSOPN_REMQTY', RSOPN_REMP = '$GTPrice'
											WHERE RSOPN_PRJCODE = '$SOPNH_PRJCODE' AND RSOPN_MTRTYPE = 'MTRL'
												AND RSOPN_YEAR = $RSOPN_YEAR AND RSOPN_MONTH = $RSOPN_MONTH";
							$this->db->query($sqlUpd);
						}
						else
						{
							$IConcl	= array('RSOPN_PRJCODE'	=> $SOPNH_PRJCODE,
											'RSOPN_MTRTYPE'	=> 'MTRL',
											'RSOPN_DATE'	=> $SOPNH_PERIODE,
											'RSOPN_YEAR'	=> date('Y',strtotime($SOPNH_PERIODE)),
											'RSOPN_MONTH'	=> date('m',strtotime($SOPNH_PERIODE)),
											'RSOPN_DAY'		=> date('d',strtotime($SOPNH_PERIODE)), 
											'RSOPN_QTYIN'	=> $GTQty_IN, 
											'RSOPN_QTYOUT'	=> $GTQty_OUT, 
											'RSOPN_PRICE'	=> 0, 
											'RSOPN_INP'		=> 0,
											'RSOPN_OUTP'	=> 0,
											'RSOPN_REMQTY'	=> $RSOPN_REMQTY,
											'RSOPN_REMP'	=> $GTPrice);
											
							$this->m_jo->addCon($IConcl);
						}
					}*/
				
				// UPDATE TOOLS
					// CEK APAKAH ADA DATA TOOLS DI DETAIL
					/*$sqlCT		= "tbl_sopn_detail WHERE SOPND_PRJCODE = '$SOPNH_PRJCODE' AND ITEM_TYPE = 'TOOLS'
										AND YEAR(SOPND_DATE) = '$RSOPN_YEAR' AND MONTH(SOPND_DATE) = $RSOPN_MONTH";
					$resCT		= $this->db->count_all($sqlCT);
					
					if($resCT > 0)
					{
						$GTQty_IN	= 0;
						$GTQty_OUT	= 0;
						$GTPrice	= 0;
						$sqlGT 		= "SELECT SUM(ITEM_IN_NOW) AS GTQty_IN, SUM(ITEM_OUT_NOW) AS GTQty_OUT,  SUM(ITEM_REM_PRICE) AS GTPrice 
										FROM tbl_sopn_detail
										WHERE SOPND_PRJCODE = '$PRJCODE' AND ITEM_TYPE = 'TOOLS'
											AND YEAR(SOPND_DATE) = $RSOPN_YEAR AND MONTH(SOPND_DATE) = $RSOPN_MONTH AND SOPND_STAT = 3";
						$resGT 		= $this->db->query($sqlGT)->result();
						foreach($resGT as $row) :
							$GTQty_IN 	= $row->GTQty_IN;	
							$GTQty_OUT	= $row->GTQty_OUT;	
							$GTPrice 	= $row->GTPrice;		
						endforeach;
						$RSOPN_REMQTY	= $GTQty_IN - $GTQty_OUT;
						
						$sqlCPerT	= "tbl_sopn_concl WHERE RSOPN_PRJCODE = '$SOPNH_PRJCODE' AND RSOPN_MTRTYPE = 'TOOLS'
											AND RSOPN_YEAR = '$RSOPN_YEAR' AND RSOPN_MONTH = $RSOPN_MONTH";
						$resCCPerT	= $this->db->count_all($sqlCPerT);
						
						if($resCCPerT > 0)
						{					
							$sqlUpdT	= "UPDATE tbl_sopn_concl 
												SET RSOPN_QTYIN = '$GTQty_IN', RSOPN_QTYOUT = '$GTQty_OUT',
												RSOPN_REMQTY = '$RSOPN_REMQTY', RSOPN_REMP = '$GTPrice'
											WHERE RSOPN_PRJCODE = '$SOPNH_PRJCODE' AND RSOPN_MTRTYPE = 'TOOLS'
												AND RSOPN_YEAR = $RSOPN_YEAR AND RSOPN_MONTH = $RSOPN_MONTH";
							$this->db->query($sqlUpdT);
						}
						else
						{
							$IConclT= array('RSOPN_PRJCODE'	=> $SOPNH_PRJCODE,
											'RSOPN_MTRTYPE'	=> 'TOOLS',
											'RSOPN_DATE'	=> $SOPNH_PERIODE,
											'RSOPN_YEAR'	=> date('Y',strtotime($SOPNH_PERIODE)),
											'RSOPN_MONTH'	=> date('m',strtotime($SOPNH_PERIODE)),
											'RSOPN_DAY'		=> date('d',strtotime($SOPNH_PERIODE)), 
											'RSOPN_QTYIN'	=> $GTQty_IN, 
											'RSOPN_QTYOUT'	=> $GTQty_OUT, 
											'RSOPN_PRICE'	=> 0, 
											'RSOPN_INP'		=> 0,
											'RSOPN_OUTP'	=> 0,
											'RSOPN_REMQTY'	=> $RSOPN_REMQTY,
											'RSOPN_REMP'	=> $GTPrice);
											
							$this->m_jo->addCon($IConclT);
						}
					}
				
				// ------------------------------- SAVE TO PROFLOSS -------------------------------
				// Check untuk bulan yang sama
					$YEARP	= date('Y',strtotime($SOPNH_PERIODE));
					$MONTHP	= (int)date('m',strtotime($SOPNH_PERIODE));
				// BUAT TANGGAL AKHIR BULAN PER SI
					$LASTDATE	= date('Y-m-t', strtotime($SOPNH_PERIODE));
					
				$sqlPL	= "tbl_profitloss 
							WHERE PRJCODE = '$PRJCODE' AND PERIODE = '$LASTDATE'";
				$resPL	= $this->db->count_all($sqlPL);
				if($resPL == 0)
				{
					// GET PRJECT DETAIL			
						$sqlPRJ	= "SELECT PRJNAME, PRJCOST FROM tbl_project WHERE PRJCODE = '$SOPNH_PERIODE'";
						$resPRJ	= $this->db->query($sqlPRJ)->result();
						foreach($resPRJ as $rowPRJ) :
							$PRJNAME 	= $rowPRJ->PRJNAME;
							$PRJCOST 	= $rowPRJ->PRJCOST;
						endforeach;
						
					// GET WIP TOTAL PER MONTH
						$RSOPN_REMP_M = 0;	// Material
						$sql_15		= "SELECT RSOPN_REMP
										FROM tbl_sopn_concl
										WHERE RSOPN_PRJCODE = '$PRJCODE' AND RSOPN_MTRTYPE = 'MTRL'
											AND RSOPN_YEAR = $YEARP AND RSOPN_MONTH = $MONTHP";
						$res_15 	= $this->db->query($sql_15)->result();
						foreach($res_15 as $row_15) :
							$RSOPN_REMP_M = $row_15->RSOPN_REMP;
						endforeach;
					
					// SAVE TO PROFITLOSS
						$insPL	= "INSERT INTO tbl_profitloss (PERIODE, PRJCODE, PRJNAME, PRJCOST, STOCK_OPN)
									VALUES ('$LASTDATE', '$PRJCODE', '$PRJNAME', '$PRJCOST', '$RSOPN_REMP_M')";
						$this->db->query($insPL);
				}
				else
				{
					// GET WIP TOTAL PER MONTH
						$RSOPN_REMP_M = 0;	// Material
						$sql_15		= "SELECT RSOPN_REMP
										FROM tbl_sopn_concl
										WHERE RSOPN_PRJCODE = '$PRJCODE' AND RSOPN_MTRTYPE = 'MTRL'
											AND RSOPN_YEAR = $YEARP AND RSOPN_MONTH = $MONTHP";
						$res_15 	= $this->db->query($sql_15)->result();
						foreach($res_15 as $row_15) :
							$RSOPN_REMP_M = $row_15->RSOPN_REMP;
						endforeach;
						
					// SAVE TO PROFITLOSS
						$updPL	= "UPDATE tbl_profitloss SET STOCK_OPN = '$RSOPN_REMP_M'
									WHERE PRJCODE = '$PRJCODE' AND PERIODE = '$LASTDATE'";
						$this->db->query($updPL);
				}
				
				// ------------------------------- UPDATE STOK DI TBL_ITEM -------------------------------			
				foreach($_POST['data'] as $d)
				{
					$PRJCODED	= $d['SOPND_PRJCODE'];
					$ITEM_CODE	= $d['ITEM_CODE'];
					$ITEM_IN	= $d['ITEM_IN_NOW'];
					$ITEM_PRICE	= $d['ITEM_PRICE'];
					$ITEM_INP	= $ITEM_IN * $ITEM_PRICE;
					$ITEM_OUT	= $d['ITEM_OUT_NOW'];
					$ITEM_OUTP	= $ITEM_OUT * $ITEM_PRICE;
					$ITEM_REM	= $d['ITEM_REM_QTY'];
					$ITM_TOTALP	= $ITEM_REM * $ITEM_PRICE;
					$sqlUpStok	= "UPDATE tbl_item SET ITM_IN = $ITEM_IN, ITM_INP = $ITEM_INP, ITM_OUT = $ITEM_OUT, ITM_OUTP = $ITEM_OUTP,
									ITM_PRICE = $ITEM_PRICE, ITM_TOTALP = $ITM_TOTALP, ITM_REMQTY = $ITEM_REM, LAST_TRXNO = '$JO_CODE'									
									WHERE PRJCODE = '$PRJCODED' AND ITM_CODE = '$ITEM_CODE'";
					$this->db->query($sqlUpStok);
				}
			}*/
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_project/c_jo/get_all_jo/?id='.$this->url_encryption_helper->encode_url($JO_PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function popupallitem() // U
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_project/m_jo/m_jo', '', TRUE);
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			
			$data['title'] 			= $appName;
			$data['h2_title'] 		= 'J O Item';
			$data['h3_title'] 		= 'J O';
			$data['form_action']	= site_url('c_project/c_jo/update_process');
			$data['PRJCODE'] 		= $PRJCODE;
			$data['secShowAll']		= site_url('c_project/c_jo/popupallitem/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			$data['recCountItem'] 	= $this->m_jo->count_Proj_Item($PRJCODE);
			$data['vwItem'] 		= $this->m_jo->viewProjItem($PRJCODE)->result();
					
			$this->load->view('v_project/v_jo/v_jo_select_item', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update() // U
	{
		$this->load->model('m_project/m_jo/m_jo', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$JO_CODE		= $_GET['id'];
		$JO_CODE		= $this->url_encryption_helper->decode_url($JO_CODE);
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{			
			$data['title'] 					= $appName;
			$data['task'] 					= 'edit';
			$data['h2_title']				= 'Update';
			$data['h3_title']				= 'J O';
			$data['form_action']			= site_url('c_project/c_jo/update_process');
			$data["MenuCode"] 				= 'MN302';
			
			$data['recCountPrj']			= $this->m_jo->count_all_project($DefEmp_ID);
			$data['vwProject'] 				= $this->m_jo->get_all_project($DefEmp_ID)->result();
			
			$getJo							= $this->m_jo->get_jo_det($JO_CODE)->row();
			$data['default']['JO_CODE'] 	= $getJo->JO_CODE;
			$data['default']['JO_DATE']		= $getJo->JO_DATE;
			$data['default']['JO_PERIODE']	= $getJo->JO_PERIODE;
			$data['default']['JO_PRJCODE']	= $getJo->JO_PRJCODE;
			$data['PRJCODE']				= $getJo->JO_PRJCODE;
			$PRJCODE 						= $getJo->JO_PRJCODE;
			$data['default']['JO_REF_NO'] 	= $getJo->JO_REF_NO;
			$data['default']['JO_DOC_NO'] 	= $getJo->JO_DOC_NO;
			$data['default']['JO_NOTES'] 	= $getJo->JO_NOTES;

			$backURL			= site_url('c_project/c_jo/get_all_jo/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['link'] 		= array('link_back' => anchor("$backURL",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 	= $backURL;
			
			$this->load->view('v_project/v_jo/v_jo_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update_process() // U
	{
		$this->load->model('m_project/m_jo/m_jo', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();
			
			date_default_timezone_set("Asia/Jakarta");
			
			$JO_CODE 		= $this->input->post('JO_CODE');
			$JO_DATE 		= date('Y-m-d',strtotime($this->input->post('JO_DATE')));
			$JO_PRJCODE 	= $this->input->post('JO_PRJCODE');
			$PRJCODE 		= $this->input->post('JO_PRJCODE');			
			$JO_PERIODE 	= date('Y-m-d',strtotime($this->input->post('JO_PERIODE')));
			$JO_REF_NO		= $this->input->post('JO_REF_NO');
			$JO_DOC_NO	 	= $this->input->post('JO_DOC_NO');
			$JO_NOTES	 	= $this->input->post('JO_NOTES');
			$JO_CREATED 	= date('Y-m-d H:i:s');;
			$JO_CREATER 	= $DefEmp_ID;
			
			$UpdJO 	= array('JO_CODE' 			=> $JO_CODE,
								'JO_DATE' 		=> $JO_DATE,
								'JO_PRJCODE'	=> $JO_PRJCODE,
								'JO_PERIODE'	=> $JO_PERIODE,
								'JO_REF_NO'		=> $JO_REF_NO,
								'JO_DOC_NO'		=> $JO_DOC_NO,
								'JO_NOTES'		=> $JO_NOTES,
								'JO_CREATED'	=> $JO_CREATED, 
								'JO_CREATER'	=> $JO_CREATER);
			
			$this->m_jo->update($JO_CODE, $UpdJO);
			$this->m_jo->deleteDetail($JO_CODE);
			
			foreach($_POST['data'] as $d)
			{
				$this->db->insert('tbl_jo_detail',$d);
			}
			
			// UPDATE GRAND TOTAL PRICE -- INGAT !!! APABILA ADA REVISE HARUS ADA PENGURANGAN			
			/*if($SOPNH_STAT == 3)
			{
				$RSOPN_YEAR1	= date('Y',strtotime($this->input->post('SOPNH_PERIODE')));
				$RSOPN_YEAR		= (int)$RSOPN_YEAR1;
				$RSOPN_MONTH1	= date('m',strtotime($this->input->post('SOPNH_PERIODE')));
				$RSOPN_MONTH	= (int)$RSOPN_MONTH1;
				
				// UPDATE MATERIAL
					// CEK APAKAH ADA DATA MATERIAL DI DETAIL
					$sqlCM		= "tbl_sopn_detail WHERE SOPND_PRJCODE = '$SOPNH_PRJCODE' AND ITEM_TYPE = 'MTRL'
										AND YEAR(SOPND_DATE) = '$RSOPN_YEAR' AND MONTH(SOPND_DATE) = $RSOPN_MONTH AND SOPND_STAT = 3";
					$resCM		= $this->db->count_all($sqlCM);
					
					if($resCM > 0)
					{
						$GTQty_IN	= 0;
						$GTQty_OUT	= 0;
						$GTPrice	= 0;
						$sqlGT 		= "SELECT SUM(ITEM_IN_NOW) AS GTQty_IN, SUM(ITEM_OUT_NOW) AS GTQty_OUT,  SUM(ITEM_REM_PRICE) AS GTPrice 
										FROM tbl_sopn_detail
										WHERE SOPND_PRJCODE = '$PRJCODE' AND ITEM_TYPE = 'MTRL'
											AND YEAR(SOPND_DATE) = $RSOPN_YEAR AND MONTH(SOPND_DATE) = $RSOPN_MONTH AND SOPND_STAT = 3";
						$resGT 		= $this->db->query($sqlGT)->result();
						foreach($resGT as $row) :
							$GTQty_IN 	= $row->GTQty_IN;	
							$GTQty_OUT	= $row->GTQty_OUT;	
							$GTPrice 	= $row->GTPrice;		
						endforeach;
						$RSOPN_REMQTY	= $GTQty_IN - $GTQty_OUT;
						
						$sqlCPerM	= "tbl_sopn_concl WHERE RSOPN_PRJCODE = '$SOPNH_PRJCODE' AND RSOPN_MTRTYPE = 'MTRL'
											AND RSOPN_YEAR = '$RSOPN_YEAR' AND RSOPN_MONTH = $RSOPN_MONTH";
						$resCCPer	= $this->db->count_all($sqlCPerM);
						
						if($resCCPer > 0)
						{					
							$sqlUpd		= "UPDATE tbl_sopn_concl 
												SET RSOPN_QTYIN = '$GTQty_IN', RSOPN_QTYOUT = '$GTQty_OUT',
												RSOPN_REMQTY = '$RSOPN_REMQTY', RSOPN_REMP = '$GTPrice'
											WHERE RSOPN_PRJCODE = '$SOPNH_PRJCODE' AND RSOPN_MTRTYPE = 'MTRL'
												AND RSOPN_YEAR = $RSOPN_YEAR AND RSOPN_MONTH = $RSOPN_MONTH";
							$this->db->query($sqlUpd);
						}
						else
						{
							$IConcl	= array('RSOPN_PRJCODE'	=> $SOPNH_PRJCODE,
											'RSOPN_MTRTYPE'	=> 'MTRL',
											'RSOPN_DATE'	=> $SOPNH_PERIODE,
											'RSOPN_YEAR'	=> date('Y',strtotime($SOPNH_PERIODE)),
											'RSOPN_MONTH'	=> date('m',strtotime($SOPNH_PERIODE)),
											'RSOPN_DAY'		=> date('d',strtotime($SOPNH_PERIODE)), 
											'RSOPN_QTYIN'	=> $GTQty_IN, 
											'RSOPN_QTYOUT'	=> $GTQty_OUT, 
											'RSOPN_PRICE'	=> 0, 
											'RSOPN_INP'		=> 0,
											'RSOPN_OUTP'	=> 0,
											'RSOPN_REMQTY'	=> $RSOPN_REMQTY,
											'RSOPN_REMP'	=> $GTPrice);
											
							$this->m_jo->addCon($IConcl);
						}
					}*/
				
				// UPDATE TOOLS
					// CEK APAKAH ADA DATA TOOLS DI DETAIL
					/*$sqlCT		= "tbl_sopn_detail WHERE SOPND_PRJCODE = '$SOPNH_PRJCODE' AND ITEM_TYPE = 'TOOLS'
										AND YEAR(SOPND_DATE) = '$RSOPN_YEAR' AND MONTH(SOPND_DATE) = $RSOPN_MONTH";
					$resCT		= $this->db->count_all($sqlCT);
					
					if($resCT > 0)
					{
						$GTQty_IN	= 0;
						$GTQty_OUT	= 0;
						$GTPrice	= 0;
						$sqlGT 		= "SELECT SUM(ITEM_IN_NOW) AS GTQty_IN, SUM(ITEM_OUT_NOW) AS GTQty_OUT,  SUM(ITEM_REM_PRICE) AS GTPrice 
										FROM tbl_sopn_detail
										WHERE SOPND_PRJCODE = '$PRJCODE' AND ITEM_TYPE = 'TOOLS'
											AND YEAR(SOPND_DATE) = $RSOPN_YEAR AND MONTH(SOPND_DATE) = $RSOPN_MONTH AND SOPND_STAT = 3";
						$resGT 		= $this->db->query($sqlGT)->result();
						foreach($resGT as $row) :
							$GTQty_IN 	= $row->GTQty_IN;	
							$GTQty_OUT	= $row->GTQty_OUT;	
							$GTPrice 	= $row->GTPrice;		
						endforeach;
						$RSOPN_REMQTY	= $GTQty_IN - $GTQty_OUT;
						
						$sqlCPerT	= "tbl_sopn_concl WHERE RSOPN_PRJCODE = '$SOPNH_PRJCODE' AND RSOPN_MTRTYPE = 'TOOLS'
											AND RSOPN_YEAR = '$RSOPN_YEAR' AND RSOPN_MONTH = $RSOPN_MONTH";
						$resCCPerT	= $this->db->count_all($sqlCPerT);
						
						if($resCCPerT > 0)
						{					
							$sqlUpdT	= "UPDATE tbl_sopn_concl 
												SET RSOPN_QTYIN = '$GTQty_IN', RSOPN_QTYOUT = '$GTQty_OUT',
												RSOPN_REMQTY = '$RSOPN_REMQTY', RSOPN_REMP = '$GTPrice'
											WHERE RSOPN_PRJCODE = '$SOPNH_PRJCODE' AND RSOPN_MTRTYPE = 'TOOLS'
												AND RSOPN_YEAR = $RSOPN_YEAR AND RSOPN_MONTH = $RSOPN_MONTH";
							$this->db->query($sqlUpdT);
						}
						else
						{
							$IConclT= array('RSOPN_PRJCODE'	=> $SOPNH_PRJCODE,
											'RSOPN_MTRTYPE'	=> 'TOOLS',
											'RSOPN_DATE'	=> $SOPNH_PERIODE,
											'RSOPN_YEAR'	=> date('Y',strtotime($SOPNH_PERIODE)),
											'RSOPN_MONTH'	=> date('m',strtotime($SOPNH_PERIODE)),
											'RSOPN_DAY'		=> date('d',strtotime($SOPNH_PERIODE)), 
											'RSOPN_QTYIN'	=> $GTQty_IN, 
											'RSOPN_QTYOUT'	=> $GTQty_OUT, 
											'RSOPN_PRICE'	=> 0, 
											'RSOPN_INP'		=> 0,
											'RSOPN_OUTP'	=> 0,
											'RSOPN_REMQTY'	=> $RSOPN_REMQTY,
											'RSOPN_REMP'	=> $GTPrice);
											
							$this->m_jo->addCon($IConclT);
						}
					}*/
				
				// ------------------------------- SAVE TO PROFLOSS -------------------------------
				// Check untuk bulan yang sama
					/*$YEARP	= date('Y',strtotime($SOPNH_PERIODE));
					$MONTHP	= (int)date('m',strtotime($SOPNH_PERIODE));
				// BUAT TANGGAL AKHIR BULAN PER SI
					$LASTDATE	= date('Y-m-t', strtotime($SOPNH_PERIODE));
					
				$sqlPL	= "tbl_profitloss 
							WHERE PRJCODE = '$PRJCODE' AND PERIODE = '$LASTDATE'";
				$resPL	= $this->db->count_all($sqlPL);
				if($resPL == 0)
				{
					// GET PRJECT DETAIL			
						$sqlPRJ	= "SELECT PRJNAME, PRJCOST FROM tbl_project WHERE PRJCODE = '$SOPNH_PERIODE'";
						$resPRJ	= $this->db->query($sqlPRJ)->result();
						foreach($resPRJ as $rowPRJ) :
							$PRJNAME 	= $rowPRJ->PRJNAME;
							$PRJCOST 	= $rowPRJ->PRJCOST;
						endforeach;
						
					// GET WIP TOTAL PER MONTH
						$RSOPN_REMP_M = 0;	// Material
						$sql_15		= "SELECT RSOPN_REMP
										FROM tbl_sopn_concl
										WHERE RSOPN_PRJCODE = '$PRJCODE' AND RSOPN_MTRTYPE = 'MTRL'
											AND RSOPN_YEAR = $YEARP AND RSOPN_MONTH = $MONTHP";
						$res_15 	= $this->db->query($sql_15)->result();
						foreach($res_15 as $row_15) :
							$RSOPN_REMP_M = $row_15->RSOPN_REMP;
						endforeach;
					
					// SAVE TO PROFITLOSS
						$insPL	= "INSERT INTO tbl_profitloss (PERIODE, PRJCODE, PRJNAME, PRJCOST, STOCK_OPN)
									VALUES ('$LASTDATE', '$PRJCODE', '$PRJNAME', '$PRJCOST', '$RSOPN_REMP_M')";
						$this->db->query($insPL);
				}
				else
				{
					// GET WIP TOTAL PER MONTH
						$RSOPN_REMP_M = 0;	// Material
						$sql_15		= "SELECT RSOPN_REMP
										FROM tbl_sopn_concl
										WHERE RSOPN_PRJCODE = '$PRJCODE' AND RSOPN_MTRTYPE = 'MTRL'
											AND RSOPN_YEAR = $YEARP AND RSOPN_MONTH = $MONTHP";
						$res_15 	= $this->db->query($sql_15)->result();
						foreach($res_15 as $row_15) :
							$RSOPN_REMP_M = $row_15->RSOPN_REMP;
						endforeach;
						
					// SAVE TO PROFITLOSS
						$updPL	= "UPDATE tbl_profitloss SET STOCK_OPN = '$RSOPN_REMP_M'
									WHERE PRJCODE = '$PRJCODE' AND PERIODE = '$LASTDATE'";
						$this->db->query($updPL);
				}
				
				// ------------------------------- UPDATE STOK DI TBL_ITEM -------------------------------			
				foreach($_POST['data'] as $d)
				{
					$PRJCODED	= $d['SOPND_PRJCODE'];
					$ITEM_CODE	= $d['ITEM_CODE'];
					$ITEM_IN	= $d['ITEM_IN_NOW'];
					$ITEM_PRICE	= $d['ITEM_PRICE'];
					$ITEM_INP	= $ITEM_IN * $ITEM_PRICE;
					$ITEM_OUT	= $d['ITEM_OUT_NOW'];
					$ITEM_OUTP	= $ITEM_OUT * $ITEM_PRICE;
					$ITEM_REM	= $d['ITEM_REM_QTY'];
					$ITM_TOTALP	= $ITEM_REM * $ITEM_PRICE;
					$sqlUpStok	= "UPDATE tbl_item SET ITM_IN = $ITEM_IN, ITM_INP = $ITEM_INP, ITM_OUT = $ITEM_OUT, ITM_OUTP = $ITEM_OUTP,
									ITM_PRICE = $ITEM_PRICE, ITM_TOTALP = $ITM_TOTALP, ITM_REMQTY = $ITEM_REM, LAST_TRXNO = '$JO_CODE'									
									WHERE PRJCODE = '$PRJCODED' AND ITM_CODE = '$ITEM_CODE'";
					$this->db->query($sqlUpStok);
				}
			}*/
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
		
			$url			= site_url('c_project/c_jo/get_all_jo/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
	/*
	function asset_exp_idx() // U
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_project/c_jo/asset_exp_prjlist/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}*/
	
	/*function asset_exp_prjlist() // U
	{
		$this->load->model('m_project/m_jo/m_jo', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['h2_title'] 	= 'Project List';
			$data['h3_title'] 	= 'asset cost';
			
			$num_rows 			= $this->m_jo->count_all_project($DefEmp_ID);
			$data["recCount"] 	= $num_rows;
	 
			$data['vwProject']	= $this->m_jo->get_all_project($DefEmp_ID)->result();
			
			$this->load->view('v_gl/v_asset_expense/project_list', $data);
		}
		else
		{
			redirect('Auth');
		}
	}*/
	
	/*function get_all_asset_exp() // U
	{
		$this->load->model('m_project/m_jo/m_jo', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
						
			$data['title'] 			= $appName;
			$data['h2_title'] 		= 'Asset Cost';
			$data['h3_title'] 		= '$PRJCODE';		
			$data['link'] 			= array('link_back' => anchor('c_project/c_jo/asset_exp_idx','<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Back" />', array('style' => 'text-decoration: none;')));
			$data['PRJCODE'] 		= $PRJCODE;
			$data['MenuCode'] 		= 'MN303';
			
			$num_rows 				= $this->m_jo->count_all_asset_exp($PRJCODE);
			$data["recCount"] 		= $num_rows;
	 
			$data['vwAssetExp']		= $this->m_jo->get_all_asset_exp($PRJCODE)->result();
			

			$this->load->view('v_gl/v_asset_expense/asset_exp_list', $data);
		}
		else
		{
			redirect('Auth');
		}
	}*/
	
	/*function addAssExp() // U
	{
		$this->load->model('m_project/m_jo/m_jo', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName 	= $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			
			$backURL			= site_url('c_project/c_jo/get_all_asset_exp/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			$data['PRJCODE'] 	= $PRJCODE;	
			$docPatternPosition = 'Especially';	
			$data['title'] 		= $appName;
			$data['task'] 		= 'add';
			$data['h2_title']	= 'Add';
			$data['h3_title']	= 'asset cost';
			$data['form_action']= site_url('c_project/c_jo/addAssExp_process');
			$data['link'] 		= array('link_back' => anchor("$backURL",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			$data['recCountPrj']= $this->m_jo->count_all_project($DefEmp_ID);
			$data['vwProject'] 	= $this->m_jo->get_all_project($DefEmp_ID)->result();
			
			$MenuCode 			= 'MN303';
			$data['MenuCode'] 	= 'MN303';
			$data['vwDocPatt'] 	= $this->m_jo->getDataDocPat($MenuCode)->result();
			
			$this->load->view('v_gl/v_asset_expense/asset_exp_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}*/
	
	/*function popupallAsset() // U
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_project/m_jo/m_jo', '', TRUE);
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			
			$data['title'] 			= $appName;
			$data['h2_title'] 		= 'List Asset';
			$data['h3_title'] 		= 'asset cost';
			$data['PRJCODE'] 		= $PRJCODE;
			
			$data['recCountAsset'] 	= $this->m_jo->count_Asset($PRJCODE);
			$data['vwAsset'] 		= $this->m_jo->viewAsset($PRJCODE)->result();
					
			$this->load->view('v_gl/v_asset_expense/select_asset', $data);
		}
		else
		{
			redirect('Auth');
		}
	}*/
	
	/*function addAssExp_process() // U
	{
		$this->load->model('m_project/m_jo/m_jo', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{	
			$this->db->trans_begin();
			
			date_default_timezone_set("Asia/Jakarta");
			
			$RASSET_CODE 	= $this->input->post('RASSET_CODE');
			$RASSET_MCODE 	= $this->input->post('RASSET_MCODE');
			
			$RASSET_DATE 	= date('Y-m-d',strtotime($this->input->post('RASSET_DATE')));
			$RASSET_PERIOD 	= date('Y-m-d',strtotime($this->input->post('RASSET_PERIOD')));
			$RASSET_PROJECT = $this->input->post('RASSET_PROJECT');
			$PRJCODE 		= $this->input->post('RASSET_PROJECT');
			
			$RASSET_POSIT 	= $this->input->post('RASSET_POSIT');
			$RASSET_NOTES 	= $this->input->post('RASSET_NOTES');
			$RASSET_CREATED	= date('Y-m-d H:i:s');
			$RASSET_STAT	= $this->input->post('RASSET_STAT'); // 1 = New, 2 = Awaiting, 3 = Approve, 4 = Revise, 5 = Reject
			$RASSET_EMPID 	= $DefEmp_ID;
			
			$Patt_Year		= date('Y',strtotime($RASSET_DATE));
			$Patt_Month		= date('m',strtotime($RASSET_DATE));
			$Patt_Date		= date('d',strtotime($RASSET_DATE));
			$Patt_Number 	= $this->input->post('Patt_Number');
			
			$InpAssetExp 	= array('RASSET_CODE' 	=> $RASSET_CODE,
								'RASSET_MCODE' 		=> $RASSET_MCODE,
								'RASSET_DATE'		=> $RASSET_DATE,
								'RASSET_PERIOD'		=> $RASSET_PERIOD,
								'RASSET_PROJECT'	=> $RASSET_PROJECT,
								'RASSET_POSIT'		=> $RASSET_POSIT,
								'RASSET_NOTES'		=> $RASSET_NOTES,
								'RASSET_CREATED'	=> $RASSET_CREATED, 
								'RASSET_STAT'		=> $RASSET_STAT, 
								'RASSET_EMPID'		=> $RASSET_EMPID, 
								'Patt_Year'			=> $Patt_Year, 
								'Patt_Month'		=> $Patt_Month,
								'Patt_Date'			=> $Patt_Date,
								'Patt_Number'		=> $Patt_Number);
							
			$this->m_jo->addAstExp($InpAssetExp);
			
			foreach($_POST['data'] as $d)
			{
				$this->db->insert('tbl_assetexp_detail',$d);
			}
			
			// UPDATE GRAND TOTAL PRICE -- INGAT !!! APABILA ADA REVISE HARUS ADA PENGURANGAN			
			if($RASSET_STAT == 3)
			{
				$RASTXP_YEAR1	= date('Y',strtotime($this->input->post('RASSET_PERIOD')));
				$RASTXP_YEAR	= (int)$RASTXP_YEAR1;
				$RASTXP_MONTH1	= date('m',strtotime($this->input->post('RASSET_PERIOD')));
				$RASTXP_MONTH	= (int)$RASTXP_MONTH1;
				
				// BUAT TANGGAL AKHIR BULAN PER EXP
					$LASTDATE	= date('Y-m-t', strtotime($this->input->post('RASSET_PERIOD')));
				
				$GTQty_IN	= 0;
				$GTQty_OUT	= 0;
				$GTPrice	= 0;
				$sqlGT 		= "SELECT SUM(RASSETD_EXP_BEF) AS GTExp_IN, SUM(RASSETD_EXP_NOW) AS GTExp_OUT, SUM(RASSETD_TOTEXP) AS GTPrice 
								FROM tbl_assetexp_detail
								WHERE RASSETD_PROJECT = '$PRJCODE' 
									AND YEAR(RASSETD_DATE) = '$RASTXP_YEAR' AND MONTH(RASSETD_DATE) = $RASTXP_MONTH AND RASSETD_STAT = 3";
				$resGT 		= $this->db->query($sqlGT)->result();
				foreach($resGT as $row) :
					$GTQty_IN 	= $row->GTExp_IN;		// DALAM ASSET EXP NDAK PERLU ADA IN HARUS DR RAB
					$GTQty_OUT	= $row->GTExp_OUT;
					$GTPrice 	= $row->GTPrice;
				endforeach;
				//$RASTXP_REMQTY	= $GTQty_IN - $GTQty_OUT;
				$RASTXP_REMQTY	= $GTQty_OUT;
				
				$sqlCPer	= "tbl_assetexp_concl WHERE RASTXP_PRJCODE = '$RASSET_PRJCODE'
									AND RASTXP_YEAR = '$RASTXP_YEAR' AND RASTXP_MONTH = $RASTXP_MONTH";
				$resCCPer	= $this->db->count_all($sqlCPer);
				
				if($resCCPer > 0)
				{					
					$sqlUpd		= "UPDATE tbl_assetexp_concl 
										SET RASTXP_QTYIN = '$GTQty_IN', RASTXP_QTYOUT = '$GTQty_OUT',
										RASTXP_REMP = '$GTPrice'
									WHERE RASTXP_PRJCODE = '$RASSET_PRJCODE'
										AND RASTXP_YEAR = '$RASTXP_YEAR' AND MONTH(RASTXP_MONTH) = $RASTXP_MONTH";
					$this->db->query($sqlUpd);
				}
				else
				{
					$IConcl	= array('RASTXP_PRJCODE'=> $RASSET_PROJECT,
									'RASTXP_DATE'	=> $LASTDATE,
									'RASTXP_YEAR'	=> date('Y',strtotime($RASSET_PERIOD)),
									'RASTXP_MONTH'	=> date('m',strtotime($RASSET_PERIOD)),
									'RASTXP_DAY'	=> date('d',strtotime($RASSET_PERIOD)), 
									'RASTXP_QTYIN'	=> $GTQty_IN, 
									'RASTXP_QTYOUT'	=> $GTQty_OUT, 
									'RASTXP_PRICE'	=> 0, 
									'RASTXP_INP'	=> 0,
									'RASTXP_OUTP'	=> 0,
									'RASTXP_REMQTY'	=> $RASTXP_REMQTY,
									'RASTXP_REMP'	=> $GTPrice);
					$this->m_jo->addConAst($IConcl);
				}
				
				// SAVE TO PROFLOSS
				// Check untuk bulan yang sama
					$YEARP	= $RASTXP_YEAR;
					$MONTHP	= $RASTXP_MONTH;
				// BUAT TANGGAL AKHIR BULAN PER SI
					$LASTDATE	= date('Y-m-t', strtotime($this->input->post('RASSET_PERIOD')));
					
				$sqlPL	= "tbl_profitloss 
							WHERE PRJCODE = '$PRJCODE' AND PERIODE = '$LASTDATE'";
				$resPL	= $this->db->count_all($sqlPL);
				if($resPL == 0)
				{
					// GET PRJECT DETAIL			
						$sqlPRJ	= "SELECT PRJNAME, PRJCOST FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
						$resPRJ	= $this->db->query($sqlPRJ)->result();
						foreach($resPRJ as $rowPRJ) :
							$PRJNAME 	= $rowPRJ->PRJNAME;
							$PRJCOST 	= $rowPRJ->PRJCOST;
						endforeach;
						
					// GET ASSET COST TOTAL PER MONTH
						$RASTXP_REMPB	= 0;
						$sql_16			= "SELECT RASTXP_REMP
											FROM tbl_assetexp_concl
											WHERE RASTXP_PRJCODE = '$PRJCODE'
												AND RASTXP_YEAR = $YEARP AND RASTXP_MONTH = $MONTHP";
						$res_16 		= $this->db->query($sql_16)->result();
						foreach($res_16 as $row_16) :
							$RASTXP_REMPB = $row_16->RASTXP_REMP;
						endforeach;
					
					// SAVE TO PROFITLOSS
						$insPL	= "INSERT INTO tbl_profitloss (PERIODE, PRJCODE, PRJNAME, PRJCOST, TOOLS_COST)
									VALUES ('$LASTDATE', '$PRJCODE', '$PRJNAME', '$PRJCOST', '$RASTXP_REMPB')";
						$this->db->query($insPL);
				}
				else
				{
					// GET ASSET COST TOTAL PER MONTH
						$RASTXP_REMPB	= 0;
						$sql_16			= "SELECT RASTXP_REMP
											FROM tbl_assetexp_concl
											WHERE RASTXP_PRJCODE = '$PRJCODE'
												AND RASTXP_YEAR = $YEARP AND RASTXP_MONTH = $MONTHP";
						$res_16 		= $this->db->query($sql_16)->result();
						foreach($res_16 as $row_16) :
							$RASTXP_REMPB = $row_16->RASTXP_REMP;
						endforeach;
						
					// SAVE TO PROFITLOSS
						$updPL	= "UPDATE tbl_profitloss SET TOOLS_COST = '$RASTXP_REMPB'
									WHERE PRJCODE = '$PRJCODE' AND PERIODE = '$LASTDATE'";
						$this->db->query($updPL);
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
			
			$url			= site_url('c_project/c_jo/get_all_asset_exp/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}*/
	
	/*function updateAstExp() // U
	{
		$this->load->model('m_project/m_jo/m_jo', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$RASSET_CODE	= $_GET['id'];
		$RASSET_CODE	= $this->url_encryption_helper->decode_url($RASSET_CODE);
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 					= $appName;
			$data['task'] 					= 'edit';
			$data['h2_title']				= 'Update';
			$data['h3_title']				= 'stock opname';
			$data['form_action']			= site_url('c_project/c_jo/updateAstExp_process');
			$data['MenuCode'] 				= 'MN303';
			
			$data['recCountPrj']			= $this->m_jo->count_all_project($DefEmp_ID);
			$data['vwProject'] 				= $this->m_jo->get_all_project($DefEmp_ID)->result();
			
			$getAssetExp						= $this->m_jo->get_stock_astExp_det($RASSET_CODE)->row();
			$data['default']['RASSET_CODE'] 	= $getAssetExp->RASSET_CODE;
			$data['default']['RASSET_MCODE']	= $getAssetExp->RASSET_MCODE;
			$data['default']['RASSET_DATE']		= $getAssetExp->RASSET_DATE;
			$data['default']['RASSET_PERIOD']	= $getAssetExp->RASSET_PERIOD;
			$data['default']['RASSET_PROJECT']	= $getAssetExp->RASSET_PROJECT;
			$data['PRJCODE']					= $getAssetExp->RASSET_PROJECT;
			$PRJCODE 							= $getAssetExp->RASSET_PROJECT;
			$data['default']['RASSET_POSIT'] 	= $getAssetExp->RASSET_POSIT;
			$data['default']['RASSET_NOTES'] 	= $getAssetExp->RASSET_NOTES;
			$data['default']['RASSET_REVMEMO'] 	= $getAssetExp->RASSET_REVMEMO;
			$data['default']['RASSET_STAT'] 	= $getAssetExp->RASSET_STAT;
			$data['default']['Patt_Year'] 		= $getAssetExp->Patt_Year;
			$data['default']['Patt_Month'] 		= $getAssetExp->Patt_Month;
			$data['default']['Patt_Date'] 		= $getAssetExp->Patt_Date;
			$data['default']['Patt_Number'] 	= $getAssetExp->Patt_Number;
			
			$backURL			= site_url('c_project/c_jo/get_all_asset_exp/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['link'] 		= array('link_back' => anchor("$backURL",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			$this->load->view('v_gl/v_asset_expense/asset_exp_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}*/
	
	/*function updateAstExp_process() // U
	{
		$this->load->model('m_project/m_jo/m_jo', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{	
			$this->db->trans_begin();
			
			date_default_timezone_set("Asia/Jakarta");
			
			$RASSET_CODE 	= $this->input->post('RASSET_CODE');
			$RASSET_MCODE 	= $this->input->post('RASSET_MCODE');
			
			$RASSET_DATE 	= date('Y-m-d',strtotime($this->input->post('RASSET_DATE')));
			$RASSET_PERIOD 	= date('Y-m-d',strtotime($this->input->post('RASSET_PERIOD')));
			$RASSET_PROJECT = $this->input->post('RASSET_PROJECT');
			$PRJCODE 		= $this->input->post('RASSET_PROJECT');
			
			$RASSET_POSIT 	= $this->input->post('RASSET_POSIT');
			$RASSET_NOTES 	= $this->input->post('RASSET_NOTES');
			$RASSET_CREATED	= date('Y-m-d H:i:s');
			$RASSET_STAT	= $this->input->post('RASSET_STAT'); // 1 = New, 2 = Awaiting, 3 = Approve, 4 = Revise, 5 = Reject
			$RASSET_EMPID 	= $DefEmp_ID;
			
			$UpdAssetExp 	= array('RASSET_CODE' 	=> $RASSET_CODE,
								'RASSET_MCODE' 		=> $RASSET_MCODE,
								'RASSET_DATE'		=> $RASSET_DATE,
								'RASSET_PERIOD'		=> $RASSET_PERIOD,
								'RASSET_PROJECT'	=> $RASSET_PROJECT,
								'RASSET_POSIT'		=> $RASSET_POSIT,
								'RASSET_NOTES'		=> $RASSET_NOTES,
								'RASSET_CREATED'	=> $RASSET_CREATED, 
								'RASSET_STAT'		=> $RASSET_STAT, 
								'RASSET_EMPID'		=> $RASSET_EMPID);
			
			$this->m_jo->updAstExp($RASSET_CODE, $UpdAssetExp);
			$this->m_jo->deleteAstDetail($RASSET_CODE);
			
			foreach($_POST['data'] as $d)
			{
				$this->db->insert('tbl_assetexp_detail',$d);
			}
			
			// UPDATE GRAND TOTAL PRICE -- INGAT !!! APABILA ADA REVISE HARUS ADA PENGURANGAN			
			if($RASSET_STAT == 3)
			{
				$RASTXP_YEAR1	= date('Y',strtotime($this->input->post('RASSET_PERIOD')));
				$RASTXP_YEAR	= (int)$RASTXP_YEAR1;
				$RASTXP_MONTH1	= date('m',strtotime($this->input->post('RASSET_PERIOD')));
				$RASTXP_MONTH	= (int)$RASTXP_MONTH1;
				
				// BUAT TANGGAL AKHIR BULAN PER EXP
					$LASTDATE	= date('Y-m-t', strtotime($this->input->post('RASSET_PERIOD')));
				
				$GTQty_IN	= 0;
				$GTQty_OUT	= 0;
				$GTPrice	= 0;
				$sqlGT 		= "SELECT SUM(RASSETD_EXP_BEF) AS GTExp_IN, SUM(RASSETD_EXP_NOW) AS GTExp_OUT, SUM(RASSETD_TOTEXP) AS GTPrice 
								FROM tbl_assetexp_detail
								WHERE RASSETD_PROJECT = '$PRJCODE' 
									AND YEAR(RASSETD_DATE) = '$RASTXP_YEAR' AND MONTH(RASSETD_DATE) = $RASTXP_MONTH AND RASSETD_STAT = 3";
				$resGT 		= $this->db->query($sqlGT)->result();
				foreach($resGT as $row) :
					$GTQty_IN 	= $row->GTExp_IN;			// DALAM ASSET EXP NDAK PERLU ADA IN HARUS DR RAB
					$GTQty_OUT	= $row->GTExp_OUT;	
					$GTPrice 	= $row->GTPrice;		
				endforeach;
				//$RASTXP_REMQTY	= $GTQty_IN - $GTQty_OUT;
				$RASTXP_REMQTY	= $GTQty_OUT;
				
				$sqlCPer	= "tbl_assetexp_concl WHERE RASTXP_PRJCODE = '$RASSET_PRJCODE'
									AND RASTXP_YEAR = '$RASTXP_YEAR' AND RASTXP_MONTH = $RASTXP_MONTH";
				$resCCPer	= $this->db->count_all($sqlCPer);
				
				if($resCCPer > 0)
				{					
					$sqlUpd		= "UPDATE tbl_assetexp_concl 
										SET RASTXP_QTYIN = '$GTQty_IN', RASTXP_QTYOUT = '$GTQty_OUT',
										RASTXP_REMP = '$GTPrice'
									WHERE RASTXP_PRJCODE = '$RASSET_PRJCODE'
										AND RASTXP_YEAR = '$RASTXP_YEAR' AND MONTH(RASTXP_MONTH) = $RASTXP_MONTH";
					$this->db->query($sqlUpd);
				}
				else
				{
					$IConcl	= array('RASTXP_PRJCODE'=> $RASSET_PROJECT,
									'RASTXP_DATE'	=> $LASTDATE,
									'RASTXP_YEAR'	=> date('Y',strtotime($RASSET_PERIOD)),
									'RASTXP_MONTH'	=> date('m',strtotime($RASSET_PERIOD)),
									'RASTXP_DAY'	=> date('d',strtotime($RASSET_PERIOD)), 
									'RASTXP_QTYIN'	=> $GTQty_IN, 
									'RASTXP_QTYOUT'	=> $GTQty_OUT, 
									'RASTXP_PRICE'	=> 0, 
									'RASTXP_INP'	=> 0,
									'RASTXP_OUTP'	=> 0,
									'RASTXP_REMQTY'	=> $RASTXP_REMQTY,
									'RASTXP_REMP'	=> $GTPrice);
					$this->m_jo->addConAst($IConcl);
				}
				
				// SAVE TO PROFLOSS
				// Check untuk bulan yang sama
					$YEARP	= $RASTXP_YEAR;
					$MONTHP	= $RASTXP_MONTH;
				// BUAT TANGGAL AKHIR BULAN PER SI
					$LASTDATE	= date('Y-m-t', strtotime($this->input->post('RASSET_PERIOD')));
					
				$sqlPL	= "tbl_profitloss 
							WHERE PRJCODE = '$PRJCODE' AND PERIODE = '$LASTDATE'";
				$resPL	= $this->db->count_all($sqlPL);
				if($resPL == 0)
				{
					// GET PRJECT DETAIL			
						$sqlPRJ	= "SELECT PRJNAME, PRJCOST FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
						$resPRJ	= $this->db->query($sqlPRJ)->result();
						foreach($resPRJ as $rowPRJ) :
							$PRJNAME 	= $rowPRJ->PRJNAME;
							$PRJCOST 	= $rowPRJ->PRJCOST;
						endforeach;
						
					// GET ASSET COST TOTAL PER MONTH
						$RASTXP_REMPB	= 0;
						$sql_16			= "SELECT RASTXP_REMP
											FROM tbl_assetexp_concl
											WHERE RASTXP_PRJCODE = '$PRJCODE'
												AND RASTXP_YEAR = $YEARP AND RASTXP_MONTH = $MONTHP";
						$res_16 		= $this->db->query($sql_16)->result();
						foreach($res_16 as $row_16) :
							$RASTXP_REMPB = $row_16->RASTXP_REMP;
						endforeach;
					
					// SAVE TO PROFITLOSS
						$insPL	= "INSERT INTO tbl_profitloss (PERIODE, PRJCODE, PRJNAME, PRJCOST, TOOLS_COST)
									VALUES ('$LASTDATE', '$PRJCODE', '$PRJNAME', '$PRJCOST', '$RASTXP_REMPB')";
						$this->db->query($insPL);
				}
				else
				{
					// GET ASSET COST TOTAL PER MONTH
						$RASTXP_REMPB	= 0;
						$sql_16			= "SELECT RASTXP_REMP
											FROM tbl_assetexp_concl
											WHERE RASTXP_PRJCODE = '$PRJCODE'
												AND RASTXP_YEAR = $YEARP AND RASTXP_MONTH = $MONTHP";
						$res_16 		= $this->db->query($sql_16)->result();
						foreach($res_16 as $row_16) :
							$RASTXP_REMPB = $row_16->RASTXP_REMP;
						endforeach;
						
					// SAVE TO PROFITLOSS
						$updPL	= "UPDATE tbl_profitloss SET TOOLS_COST = '$RASTXP_REMPB'
									WHERE PRJCODE = '$PRJCODE' AND PERIODE = '$LASTDATE'";
						$this->db->query($updPL);
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
			
			$url			= site_url('c_project/c_jo/get_all_asset_exp/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}*/
	/*
	function overhead_idx() // U
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_project/c_jo/overhead_prjlist/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}*/
	
	/*function overhead_prjlist() // U
	{
		$this->load->model('m_project/m_jo/m_jo', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['h2_title'] 	= 'Project List';
			$data['h3_title'] 	= 'overhead';
			
			$num_rows 			= $this->m_jo->count_all_project($DefEmp_ID);
			$data["recCount"] 	= $num_rows;
	 
			$data['vwProject']	= $this->m_jo->get_all_project($DefEmp_ID)->result();
			
			$this->load->view('v_gl/v_overhead/project_list', $data);
		}
		else
		{
			redirect('Auth');
		}
	}*/
	
	/*function get_all_overhead() // U
	{
		$this->load->model('m_project/m_jo/m_jo', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
						
			$data['title'] 		= $appName;
			$data['h2_title'] 	= 'Overhead';
			$data['h3_title'] 	= 'overhead';		
			$data['link'] 		= array('link_back' => anchor('c_project/c_jo/overhead_idx','<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Back" />', array('style' => 'text-decoration: none;')));
			$data['PRJCODE'] 	= $PRJCODE;
			$data['MenuCode']	= 'MN311';
			
			$num_rows 			= $this->m_jo->count_all_overhead($PRJCODE);
			$data["recCount"] 	= $num_rows;
	 
			$data['vwOverhead']	= $this->m_jo->get_all_overhead($PRJCODE)->result();
			
			$this->load->view('v_gl/v_overhead/overhead_list', $data);
		}
		else
		{
			redirect('Auth');
		}
	}*/
	
	/*function addOVH() // U
	{
		$this->load->model('m_project/m_jo/m_jo', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName 	= $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			
			$backURL			= site_url('c_project/c_jo/get_all_overhead/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			$data['PRJCODE'] 	= $PRJCODE;	
			$docPatternPosition = 'Especially';	
			$data['title'] 		= $appName;
			$data['task'] 		= 'add';
			$data['h2_title']	= 'Add';
			$data['h3_title']	= 'overhead';
			$data['form_action']= site_url('c_project/c_jo/addOVH_process');
			$data['link'] 		= array('link_back' => anchor("$backURL",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			$data['recCountPrj']= $this->m_jo->count_all_project($DefEmp_ID);
			$data['vwProject'] 	= $this->m_jo->get_all_project($DefEmp_ID)->result();
			
			$MenuCode 			= 'MN311';
			$data['MenuCode']	= 'MN311';
			$data['vwDocPatt'] 	= $this->m_jo->getDataDocPat($MenuCode)->result();
			
			$this->load->view('v_gl/v_overhead/overhead_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}*/
	
	/*function addOVH_process() // U
	{
		$this->load->model('m_project/m_jo/m_jo', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{	
			$this->db->trans_begin();
			
			date_default_timezone_set("Asia/Jakarta");
			
			$OVH_CODE 		= $this->input->post('OVH_CODE');
			
			$OVH_PERIODE 	= date('Y-m-d',strtotime($this->input->post('OVH_PERIODE')));
			$OVH_PRJCODE	= $this->input->post('OVH_PRJCODE');
			$PRJCODE 		= $this->input->post('OVH_PRJCODE');
			
			$OVH_TYPE 		= $this->input->post('OVH_TYPE');
			$OVH_PERCENT 	= $this->input->post('OVH_PERCENT');
			$OVH_NOTES 		= $this->input->post('OVH_NOTES');
			$OVH_CREATER	= $DefEmp_ID;
			$OVH_CREATED	= date('Y-m-d H:i:s');
			$OVH_STAT		= $this->input->post('OVH_STAT'); // 1 = New, 2 = Awaiting, 3 = Approve, 4 = Revise, 5 = Reject
			
			$Patt_Year		= date('Y',strtotime($OVH_CREATED));
			$Patt_Month		= date('m',strtotime($OVH_CREATED));
			$Patt_Date		= date('d',strtotime($OVH_CREATED));
			$Patt_Number 	= $this->input->post('Patt_Number');
			
			if($OVH_STAT == 3)
			{
				// CEK OVH TYPE
				$sqlOVHT		= "tbl_overhead WHERE OVH_PRJCODE = '$PRJCODE' AND OVH_TYPE = '$OVH_TYPE'
									AND YEAR(OVH_PERIODE) = $Patt_Year AND MONTH(OVH_PERIODE) = $Patt_Month AND OVH_STAT = 3";
				$resOVHT		= $this->db->count_all($sqlOVHT);
				if($resOVHT > 0)
				{
					// UPDATE ALL TO BE CLOSED
					$sqlUpdOVHT	= "UPDATE tbl_overhead SET OVH_STAT = 6
									WHERE OVH_PRJCODE = '$PRJCODE' AND OVH_TYPE = '$OVH_TYPE'
										AND YEAR(OVH_PERIODE) = $Patt_Year AND MONTH(OVH_PERIODE) = $Patt_Month AND OVH_STAT = 3";
					$resUpdOVHT	= $this->db->query($sqlUpdOVHT);
				}
			}
			
			$InpOVH 		= array('OVH_CODE' 		=> $OVH_CODE,
									'OVH_PERIODE' 	=> $OVH_PERIODE,
									'OVH_PRJCODE'	=> $OVH_PRJCODE,
									'OVH_TYPE'		=> $OVH_TYPE,
									'OVH_PERCENT'	=> $OVH_PERCENT,
									'OVH_NOTES'		=> $OVH_NOTES,
									'OVH_CREATER'	=> $OVH_CREATER,
									'OVH_CREATED'	=> $OVH_CREATED, 
									'OVH_STAT'		=> $OVH_STAT, 
									'Patt_Year'		=> $Patt_Year, 
									'Patt_Month'	=> $Patt_Month,
									'Patt_Date'		=> $Patt_Date,
									'Patt_Number'	=> $Patt_Number);							
			$this->m_jo->addOVH($InpOVH);
			
			if($OVH_STAT == 3)
			{
				// SAVE TO PROFLOSS
				// Check untuk bulan yang sama
					$YEARP		= date('Y', strtotime($OVH_PERIODE));
					$MONTHP		= (int)date('Y', strtotime($OVH_PERIODE));
				// BUAT TANGGAL AKHIR BULAN
					$LASTDATE	= date('Y-m-t', strtotime($OVH_PERIODE));
				
				// GET PRJECT DETAIL			
					$sqlPRJ	= "SELECT PRJNAME, PRJCOST FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
					$resPRJ	= $this->db->query($sqlPRJ)->result();
					foreach($resPRJ as $rowPRJ) :
						$PRJNAME 	= $rowPRJ->PRJNAME;
						$PRJCOST 	= $rowPRJ->PRJCOST;
					endforeach;
					
				$sqlPL	= "tbl_profitloss 
							WHERE PRJCODE = '$PRJCODE' AND PERIODE = '$LASTDATE'";
				$resPL	= $this->db->count_all($sqlPL);
				if($resPL == 0)
				{						
					// GET ASSET COST TOTAL PER MONTH
						$OVH_PERCENT	= $OVH_PERCENT;
						$sqlPERC		= "SELECT OVH_PERCENT
											FROM tbl_overhead
											WHERE OVH_PRJCODE = '$PRJCODE' AND OVH_TYPE = '$OVH_TYPE'
												AND OVH_PERIODE = $YEARP AND OVH_PERIODE = $MONTHP AND OVH_STAT = '3'";
						$resPERC 		= $this->db->query($sqlPERC)->result();
						foreach($resPERC as $rowPERC) :
							$OVH_PERCENT = $rowPERC->OVH_PERCENT;
						endforeach;
						
						if($OVH_TYPE == 'KTR')
						{
							$OHC_PUSAT	= $OVH_PERCENT * $PRJCOST;
							// SAVE TO PROFITLOSS
								$insPL	= "INSERT INTO tbl_profitloss (PERIODE, PRJCODE, PRJNAME, PRJCOST, OHC_PUSAT)
											VALUES ('$LASTDATE', '$PRJCODE', '$PRJNAME', '$PRJCOST', '$OHC_PUSAT')";
								$this->db->query($insPL);
						}
						elseif($OVH_TYPE == 'CBG')
						{
							$OHC_CBG	= $OVH_PERCENT * $PRJCOST;
							// SAVE TO PROFITLOSS
								$insPL	= "INSERT INTO tbl_profitloss (PERIODE, PRJCODE, PRJNAME, PRJCOST, OHC_CBG)
											VALUES ('$LASTDATE', '$PRJCODE', '$PRJNAME', '$PRJCOST', '$OHC_CBG')";
								$this->db->query($insPL);
						}
						elseif($OVH_TYPE == 'BNG')
						{
							$OHC_BNG	= $OVH_PERCENT * $PRJCOST;
							// SAVE TO PROFITLOSS
								$insPL	= "INSERT INTO tbl_profitloss (PERIODE, PRJCODE, PRJNAME, PRJCOST, OHC_BNG)
											VALUES ('$LASTDATE', '$PRJCODE', '$PRJNAME', '$PRJCOST', '$OHC_BNG')";
								$this->db->query($insPL);
						}
						else
						{
							$OHC_PPH	= $OVH_PERCENT * $PRJCOST;
							// SAVE TO PROFITLOSS
								$insPL	= "INSERT INTO tbl_profitloss (PERIODE, PRJCODE, PRJNAME, PRJCOST, OHC_PPH)
											VALUES ('$LASTDATE', '$PRJCODE', '$PRJNAME', '$PRJCOST', '$OHC_PPH')";
								$this->db->query($insPL);
						}
				}
				else
				{
					// GET ASSET COST TOTAL PER MONTH
						$OVH_PERCENT	= $OVH_PERCENT;
						$sqlPERC		= "SELECT OVH_PERCENT
											FROM tbl_overhead
											WHERE OVH_PRJCODE = '$PRJCODE' AND OVH_TYPE = '$OVH_TYPE'
												AND OVH_PERIODE = $YEARP AND OVH_PERIODE = $MONTHP AND OVH_STAT = '3'";
						$resPERC 		= $this->db->query($sqlPERC)->result();
						foreach($resPERC as $rowPERC) :
							$OVH_PERCENT = $rowPERC->OVH_PERCENT;
						endforeach;
						
						if($OVH_TYPE == 'KTR')
						{
							$OHC_PUSAT	= $OVH_PERCENT * $PRJCOST;
							// SAVE TO PROFITLOSS
								$insPL	= "INSERT INTO tbl_profitloss (PERIODE, PRJCODE, PRJNAME, PRJCOST, OHC_PUSAT)
											VALUES ('$LASTDATE', '$PRJCODE', '$PRJNAME', '$PRJCOST', '$OHC_PUSAT')";
								$this->db->query($insPL);
						}
						elseif($OVH_TYPE == 'CBG')
						{
							$OHC_CBG	= $OVH_PERCENT * $PRJCOST;
							// SAVE TO PROFITLOSS
								$insPL	= "INSERT INTO tbl_profitloss (PERIODE, PRJCODE, PRJNAME, PRJCOST, OHC_CBG)
											VALUES ('$LASTDATE', '$PRJCODE', '$PRJNAME', '$PRJCOST', '$OHC_CBG')";
								$this->db->query($insPL);
						}
						elseif($OVH_TYPE == 'BNG')
						{
							$OHC_BNG	= $OVH_PERCENT * $PRJCOST;
							// SAVE TO PROFITLOSS
								$insPL	= "INSERT INTO tbl_profitloss (PERIODE, PRJCODE, PRJNAME, PRJCOST, OHC_BUNGA)
											VALUES ('$LASTDATE', '$PRJCODE', '$PRJNAME', '$PRJCOST', '$OHC_BNG')";
								$this->db->query($insPL);
						}
						else
						{
							$OHC_PPH	= $OVH_PERCENT * $PRJCOST;
							// SAVE TO PROFITLOSS
								$insPL	= "INSERT INTO tbl_profitloss (PERIODE, PRJCODE, PRJNAME, PRJCOST, OHC_PPH)
											VALUES ('$LASTDATE', '$PRJCODE', '$PRJNAME', '$PRJCOST', '$OHC_PPH')";
								$this->db->query($insPL);
						}
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
			
			$url			= site_url('c_project/c_jo/get_all_overhead/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}*/
	
	/*function updateOVH() // U
	{
		$this->load->model('m_project/m_jo/m_jo', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$OVH_CODE	= $_GET['id'];
		$OVH_CODE	= $this->url_encryption_helper->decode_url($OVH_CODE);
		
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 					= $appName;
			$data['task'] 					= 'edit';
			$data['h2_title']				= 'Update';
			$data['h3_title']				= 'overhead';
			$data['form_action']			= site_url('c_project/c_jo/updateOVH_process');
			$data['MenuCode']				= 'MN311';
			
			$data['recCountPrj']			= $this->m_jo->count_all_project($DefEmp_ID);
			$data['vwProject'] 				= $this->m_jo->get_all_project($DefEmp_ID)->result();
			
			$getOverhead					= $this->m_jo->get_ovh_det($OVH_CODE)->row();
			$data['default']['OVH_CODE'] 	= $getOverhead->OVH_CODE;
			$data['default']['OVH_PERIODE']	= $getOverhead->OVH_PERIODE;
			$data['default']['OVH_PRJCODE']	= $getOverhead->OVH_PRJCODE;
			$data['PRJCODE']				= $getOverhead->OVH_PRJCODE;
			$PRJCODE 						= $getOverhead->OVH_PRJCODE;
			$data['default']['OVH_TYPE']	= $getOverhead->OVH_TYPE;
			$data['default']['OVH_PERCENT']	= $getOverhead->OVH_PERCENT;
			$data['default']['OVH_NOTES'] 	= $getOverhead->OVH_NOTES;
			$data['default']['OVH_STAT'] 	= $getOverhead->OVH_STAT;
			$data['default']['Patt_Year'] 	= $getOverhead->Patt_Year;
			$data['default']['Patt_Month'] 	= $getOverhead->Patt_Month;
			$data['default']['Patt_Date'] 	= $getOverhead->Patt_Date;
			$data['default']['Patt_Number'] = $getOverhead->Patt_Number;
			
			$backURL			= site_url('c_project/c_jo/get_all_overhead/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['link'] 		= array('link_back' => anchor("$backURL",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			$this->load->view('v_gl/v_overhead/overhead_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}*/
	
	/*function updateOVH_process() // U
	{
		$this->load->model('m_project/m_jo/m_jo', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{	
			$this->db->trans_begin();
			
			date_default_timezone_set("Asia/Jakarta");
			
			$OVH_CODE 		= $this->input->post('OVH_CODE');
			
			$OVH_PERIODE 	= date('Y-m-d',strtotime($this->input->post('OVH_PERIODE')));
			$OVH_PRJCODE	= $this->input->post('OVH_PRJCODE');
			$PRJCODE 		= $this->input->post('OVH_PRJCODE');
			
			$OVH_TYPE 		= $this->input->post('OVH_TYPE');
			$OVH_PERCENT 	= $this->input->post('OVH_PERCENT');
			$OVH_NOTES 		= $this->input->post('OVH_NOTES');
			$OVH_CREATER	= $DefEmp_ID;
			$OVH_CREATED	= date('Y-m-d H:i:s');
			$OVH_STAT		= $this->input->post('OVH_STAT'); // 1 = New, 2 = Awaiting, 3 = Approve, 4 = Revise, 5 = Reject
			
			$Patt_Year		= date('Y',strtotime($OVH_CREATED));
			$Patt_Month		= date('m',strtotime($OVH_CREATED));
			$Patt_Date		= date('d',strtotime($OVH_CREATED));
			$Patt_Number 	= $this->input->post('Patt_Number');
			
			if($OVH_STAT == 3)
			{
				// CEK OVH TYPE
				$sqlOVHT		= "tbl_overhead WHERE OVH_PRJCODE = '$PRJCODE' AND OVH_TYPE = '$OVH_TYPE'
									AND YEAR(OVH_PERIODE) = $Patt_Year AND MONTH(OVH_PERIODE) = $Patt_Month AND OVH_STAT = 3";
				$resOVHT		= $this->db->count_all($sqlOVHT);
				if($resOVHT > 0)
				{
					// UPDATE ALL TO BE CLOSED
					$sqlUpdOVHT	= "UPDATE tbl_overhead SET OVH_STAT = 6
									WHERE OVH_PRJCODE = '$PRJCODE' AND OVH_TYPE = '$OVH_TYPE'
										AND YEAR(OVH_PERIODE) = $Patt_Year AND MONTH(OVH_PERIODE) = $Patt_Month AND OVH_STAT = 3";
					$resUpdOVHT	= $this->db->query($sqlUpdOVHT);
				}
			}
			
			$UpdOVH 		= array('OVH_CODE' 		=> $OVH_CODE,
									'OVH_PERIODE' 	=> $OVH_PERIODE,
									'OVH_PRJCODE'	=> $OVH_PRJCODE,
									'OVH_TYPE'		=> $OVH_TYPE,
									'OVH_PERCENT'	=> $OVH_PERCENT,
									'OVH_NOTES'		=> $OVH_NOTES,
									'OVH_CREATER'	=> $OVH_CREATER,
									'OVH_CREATED'	=> $OVH_CREATED, 
									'OVH_STAT'		=> $OVH_STAT, 
									'Patt_Year'		=> $Patt_Year, 
									'Patt_Month'	=> $Patt_Month,
									'Patt_Date'		=> $Patt_Date,
									'Patt_Number'	=> $Patt_Number);
			$this->m_jo->updOVH($OVH_CODE, $UpdOVH);
			
			if($OVH_STAT == 3)
			{
				// SAVE TO PROFLOSS
				// Check untuk bulan yang sama
					$YEARP		= date('Y', strtotime($OVH_PERIODE));
					$MONTHP		= (int)date('Y', strtotime($OVH_PERIODE));
				// BUAT TANGGAL AKHIR BULAN
					$LASTDATE	= date('Y-m-t', strtotime($OVH_PERIODE));
				
				// GET PRJECT DETAIL			
					$sqlPRJ	= "SELECT PRJNAME, PRJCOST FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
					$resPRJ	= $this->db->query($sqlPRJ)->result();
					foreach($resPRJ as $rowPRJ) :
						$PRJNAME 	= $rowPRJ->PRJNAME;
						$PRJCOST 	= $rowPRJ->PRJCOST;
					endforeach;
					
				$sqlPL	= "tbl_profitloss 
							WHERE PRJCODE = '$PRJCODE' AND PERIODE = '$LASTDATE'";
				$resPL	= $this->db->count_all($sqlPL);
				if($resPL == 0)
				{						
					// GET ASSET COST TOTAL PER MONTH
						$OVH_PERCENT	= $OVH_PERCENT;
						$sqlPERC		= "SELECT OVH_PERCENT
											FROM tbl_overhead
											WHERE OVH_PRJCODE = '$PRJCODE' AND OVH_TYPE = '$OVH_TYPE'
												AND OVH_PERIODE = $YEARP AND OVH_PERIODE = $MONTHP AND OVH_STAT = '3'";
						$resPERC 		= $this->db->query($sqlPERC)->result();
						foreach($resPERC as $rowPERC) :
							$OVH_PERCENT = $rowPERC->OVH_PERCENT;
						endforeach;
						
						if($OVH_TYPE == 'KTR')
						{
							$OHC_PUSAT	= $OVH_PERCENT * $PRJCOST;
							// SAVE TO PROFITLOSS
								$insPL	= "INSERT INTO tbl_profitloss (PERIODE, PRJCODE, PRJNAME, PRJCOST, OHC_PUSAT)
											VALUES ('$LASTDATE', '$PRJCODE', '$PRJNAME', '$PRJCOST', '$OHC_PUSAT')";
								$this->db->query($insPL);
						}
						elseif($OVH_TYPE == 'CBG')
						{
							$OHC_CBG	= $OVH_PERCENT * $PRJCOST;
							// SAVE TO PROFITLOSS
								$insPL	= "INSERT INTO tbl_profitloss (PERIODE, PRJCODE, PRJNAME, PRJCOST, OHC_CBG)
											VALUES ('$LASTDATE', '$PRJCODE', '$PRJNAME', '$PRJCOST', '$OHC_CBG')";
								$this->db->query($insPL);
						}
						elseif($OVH_TYPE == 'BNG')
						{
							$OHC_BNG	= $OVH_PERCENT * $PRJCOST;
							// SAVE TO PROFITLOSS
								$insPL	= "INSERT INTO tbl_profitloss (PERIODE, PRJCODE, PRJNAME, PRJCOST, OHC_BNG)
											VALUES ('$LASTDATE', '$PRJCODE', '$PRJNAME', '$PRJCOST', '$OHC_BNG')";
								$this->db->query($insPL);
						}
						else
						{
							$OHC_PPH	= $OVH_PERCENT * $PRJCOST;
							// SAVE TO PROFITLOSS
								$insPL	= "INSERT INTO tbl_profitloss (PERIODE, PRJCODE, PRJNAME, PRJCOST, OHC_PPH)
											VALUES ('$LASTDATE', '$PRJCODE', '$PRJNAME', '$PRJCOST', '$OHC_PPH')";
								$this->db->query($insPL);
						}
				}
				else
				{
					// GET ASSET COST TOTAL PER MONTH
						$OVH_PERCENT	= $OVH_PERCENT;
						$sqlPERC		= "SELECT OVH_PERCENT
											FROM tbl_overhead
											WHERE OVH_PRJCODE = '$PRJCODE' AND OVH_TYPE = '$OVH_TYPE'
												AND OVH_PERIODE = $YEARP AND OVH_PERIODE = $MONTHP AND OVH_STAT = '3'";
						$resPERC 		= $this->db->query($sqlPERC)->result();
						foreach($resPERC as $rowPERC) :
							$OVH_PERCENT = $rowPERC->OVH_PERCENT;
						endforeach;
						
						if($OVH_TYPE == 'KTR')
						{
							$OHC_PUSAT	= $OVH_PERCENT * $PRJCOST;
							// SAVE TO PROFITLOSS
								$insPL	= "INSERT INTO tbl_profitloss (PERIODE, PRJCODE, PRJNAME, PRJCOST, OHC_PUSAT)
											VALUES ('$LASTDATE', '$PRJCODE', '$PRJNAME', '$PRJCOST', '$OHC_PUSAT')";
								$this->db->query($insPL);
						}
						elseif($OVH_TYPE == 'CBG')
						{
							$OHC_CBG	= $OVH_PERCENT * $PRJCOST;
							// SAVE TO PROFITLOSS
								$insPL	= "INSERT INTO tbl_profitloss (PERIODE, PRJCODE, PRJNAME, PRJCOST, OHC_CBG)
											VALUES ('$LASTDATE', '$PRJCODE', '$PRJNAME', '$PRJCOST', '$OHC_CBG')";
								$this->db->query($insPL);
						}
						elseif($OVH_TYPE == 'BNG')
						{
							$OHC_BNG	= $OVH_PERCENT * $PRJCOST;
							// SAVE TO PROFITLOSS
								$insPL	= "INSERT INTO tbl_profitloss (PERIODE, PRJCODE, PRJNAME, PRJCOST, OHC_BUNGA)
											VALUES ('$LASTDATE', '$PRJCODE', '$PRJNAME', '$PRJCOST', '$OHC_BNG')";
								$this->db->query($insPL);
						}
						else
						{
							$OHC_PPH	= $OVH_PERCENT * $PRJCOST;
							// SAVE TO PROFITLOSS
								$insPL	= "INSERT INTO tbl_profitloss (PERIODE, PRJCODE, PRJNAME, PRJCOST, OHC_PPH)
											VALUES ('$LASTDATE', '$PRJCODE', '$PRJNAME', '$PRJCOST', '$OHC_PPH')";
								$this->db->query($insPL);
						}
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
			
			$url			= site_url('c_project/c_jo/get_all_overhead/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}*/
}