<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 16 Desember 2019
 * File Name	= __l1y4pp.php
 * Location		= -
*/
class __l1y4pp extends CI_Controller  
{
	function update_inbbyWA()
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_purchase/m_purchase_req/m_purchase_req', '', TRUE);
		$this->load->model('m_updash/m_updash', '', TRUE);
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;

		$COLLDATA	= $_GET['id'];
		$COLLDATA	= $this->url_encryption_helper->decode_url($COLLDATA);
		$EXTRACTCOL	= explode("~", $COLLDATA);
		$PRJCODE	= $EXTRACTCOL[0];
		$PR_NUM		= $EXTRACTCOL[1];
		$AppEMP		= $EXTRACTCOL[2];

		// START : AUTO LOGIN PROCEDURE
			$autoLog 	= array('AppEMP'		=> $AppEMP);										
			$this->m_updash->autoLog($autoLog);
		// END : AUTO LOGIN PROCEDURE

		$this->data['UserID'] 		= $this->session->userdata['Emp_ID'];
		$this->data['appName'] 		= $this->session->userdata['appName'];
		$this->data['ISCREATE'] 	= $this->session->userdata['ISCREATE'];
		$this->data['ISAPPROVE'] 	= $this->session->userdata['ISAPPROVE'];
		$this->data['LangID']		= $this->session->userdata['LangID'];
		$this->data['nSELP']		= $this->session->userdata['nSELP'];
		
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
		
		// DEFAULT PROJECT
			$sqlISHO 		= "SELECT PRJCODE FROM tbl_project WHERE isHO = 1 AND PRJSTAT = 1";
			$resISHO		= $this->db->query($sqlISHO)->result();
			foreach($resISHO as $rowISHO):
				$PRJCODE	= $rowISHO->PRJCODE;
			endforeach;
			$this->data['PRJCODE']		= $PRJCODE;
			$this->data['PRJCODE_HO']	= $PRJCODE;
		
		// GET PROJECT SELECT
			if(isset($_GET['id']))
			{
				$collDATA		= $_GET['id'];
				$EXP_COLLD1		= $this->url_encryption_helper->decode_url($collDATA);
			}
			else
			{
				$EXP_COLLD1		= '';
			}

			$EXP_COLLD		= explode('~', $EXP_COLLD1);	
			$C_COLLD1		= count($EXP_COLLD);
			if($C_COLLD1 > 1)
			{
				$EXP_COLLD1	= str_replace('~', '', $EXP_COLLD1);
				$PRJCODE	= $EXP_COLLD[0];
			}
			else
			{
				$PRJCODE	= $EXP_COLLD1;
			}
		
			$sqlISHO 		= "SELECT PRJCODE, PRJCODE_HO FROM tbl_project_budg WHERE PRJCODE = '$PRJCODE' AND PRJSTAT = 1";
			$resISHO		= $this->db->query($sqlISHO)->result();
			foreach($resISHO as $rowISHO):
				$this->data['PRJCODE']		= $rowISHO->PRJCODE;
				$this->data['PRJCODE_HO']	= $rowISHO->PRJCODE_HO;
			endforeach;

		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
			
		// GET MENU DESC
			$mnCode				= 'MN018';
			$data["MenuApp"] 	= 'MN018';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$docPatternPosition = 'Especially';	
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_purchase/c_pr180d0c/update_process_inb');

			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			
			$getpurreq 						= $this->m_purchase_req->get_MR_by_number($PR_NUM)->row();
			$data['default']['PR_NUM'] 		= $getpurreq->PR_NUM;
			$data['default']['PR_CODE'] 	= $getpurreq->PR_CODE;
			$data['default']['PR_DATE'] 	= $getpurreq->PR_DATE;
			$data['default']['PR_RECEIPTD'] = $getpurreq->PR_RECEIPTD;
			$data['default']['PRJCODE']		= $getpurreq->PRJCODE;
			$data['PRJCODE']				= $getpurreq->PRJCODE;
			$data['DEPCODE']				= $getpurreq->DEPCODE;
			$data['default']['DEPCODE']		= $getpurreq->DEPCODE;
			$data['PRJCODE_HO']				= $this->data['PRJCODE_HO'];
			$PRJCODE 						= $getpurreq->PRJCODE;
			$data['default']['SPLCODE'] 	= $getpurreq->SPLCODE;
			$data['default']['PR_DEPT'] 	= $getpurreq->PR_DEPT;
			$data['default']['PR_NOTE'] 	= $getpurreq->PR_NOTE;
			$data['default']['PR_NOTE2'] 	= $getpurreq->PR_NOTE2;
			$data['default']['PR_STAT'] 	= $getpurreq->PR_STAT;
			$data['default']['PR_MEMO'] 	= $getpurreq->PR_MEMO;
			//$data['default']['PRJNAME'] 	= $getpurreq->PRJNAME;
			$data['default']['PR_VALUE']	= $getpurreq->PR_VALUE;
			$data['default']['PR_VALUEAPP']	= $getpurreq->PR_VALUEAPP;
			$data['default']['PR_REFNO']	= $getpurreq->PR_REFNO;
			$data['default']['Patt_Year'] 	= $getpurreq->Patt_Year;
			$data['default']['Patt_Month'] 	= $getpurreq->Patt_Month;
			$data['default']['Patt_Date'] 	= $getpurreq->Patt_Date;
			$data['default']['Patt_Number']	= $getpurreq->Patt_Number;
			
			$MenuCode 			= 'MN018';
			$data["MenuCode"] 	= 'MN018';
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getpurreq->PR_NUM;
				$MenuCode 		= 'MN018';
				$TTR_CATEG		= 'APP-U';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$this->load->view('v_purchase/v_purchase_req/purchase_req_inb_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process_inb() // OK
	{
		$this->load->model('m_purchase/m_purchase_req/m_purchase_req', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			date_default_timezone_set("Asia/Jakarta");
			
			$PR_APPROVED 	= date('Y-m-d H:i:s');
			
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			$PR_STAT 		= $this->input->post('PR_STAT'); // 1 = New, 2 = confirm, 3 = Close
			
			$PR_NUM 		= $this->input->post('PR_NUM');
			$PRJCODE 		= $this->input->post('PRJCODE');
			$PR_STAT		= $this->input->post('PR_STAT');
			
			$AH_CODE		= $PR_NUM;
			$AH_APPLEV		= $this->input->post('APP_LEVEL');
			$AH_APPROVER	= $DefEmp_ID;
			$AH_APPROVED	= $PR_APPROVED;
			$AH_NOTES		= $this->input->post('PR_NOTE2');
			$PR_MEMO		= $this->input->post('PR_MEMO');
			$AH_ISLAST		= $this->input->post('IS_LAST');
			
			// START : SPECIAL FOR SASMITO
				$TOTMAJOR	= $this->input->post('TOTMAJOR');
				// IF TOTMAJOR > 0, MAKA HARUS ADA STEP APPROVAL KHUSUS DARI MENU SETTING
				// MELALUI TABEL tbl_major_app
				$sqlMJREMP	= "SELECT * FROM tbl_major_app";
				$resMJREMP	= $this->db->query($sqlMJREMP)->result();
				foreach($resMJREMP as $rowMJR) :
					$Emp_ID1	= $rowMJR->Emp_ID1;
					$Emp_ID2	= $rowMJR->Emp_ID2;
				endforeach;
				$yesAPP		= 0;
				if($TOTMAJOR > 0)
				{
					if(($DefEmp_ID == $Emp_ID1) || ($DefEmp_ID == $Emp_ID2))
					{
						$AH_ISLAST 	= 1;
						$yesAPP		= 1;
					}
					else
					{
						$AH_ISLAST 	= 0;
						$yesAPP		= 0;
					}
				}
				else
				{
					$yesAPP		= 1;
				}
				//echo "Emp_ID1 = $Emp_ID1 AND TOTMAJOR = $TOTMAJOR AND yesAPP = $yesAPP";
				//return false;
			// END : SPECIAL FOR SASMITO
			
			$projMatReqH 	= array('PR_NOTE2'		=> $this->input->post('PR_NOTE2'));										
			$this->m_purchase_req->update($PR_NUM, $projMatReqH);
			
			$projMatReqH 	= array('PR_STAT'	=> 7);					
			$this->m_purchase_req->update($PR_NUM, $projMatReqH);
			
			// UPDATE JOBDETAIL ITEM
			if($PR_STAT == 3)
			{
				// START : SAVE APPROVE HISTORY
					$this->load->model('m_updash/m_updash', '', TRUE);
					
					$insAppHist 	= array('PRJCODE'		=> $PRJCODE,
											'AH_CODE'		=> $AH_CODE,
											'AH_APPLEV'		=> $AH_APPLEV,
											'AH_APPROVER'	=> $AH_APPROVER,
											'AH_APPROVED'	=> $AH_APPROVED,
											'AH_NOTES'		=> $AH_NOTES,
											'AH_ISLAST'		=> $AH_ISLAST);										
					$this->m_updash->insAppHist($insAppHist);
				// END : SAVE APPROVE HISTORY
				
				//$this->m_purchase_req->updateJobDet($PR_NUM, $PRJCODE); // UPDATE JOBD ETAIL DAN PR

				// START : CREATE ALERT PROCEDURE
					$crtAlert 	= array('PRJCODE'		=> $PRJCODE,
										'ALRT_MNCODE'	=> 'MN018',
										'ALRT_CATEG'	=> "PR",
										'ALRT_NUM'		=> $PR_NUM,
										'ALRT_LEV'		=> $AH_APPLEV,
										'ALRT_EMP'		=> $AH_APPROVER);										
					$this->m_updash->crtAlert($crtAlert);
				// END : CREATE ALERT PROCEDURE
			}
			
			if($AH_ISLAST == 1 && $yesAPP == 1)
			{
				if($PR_STAT == 5)	// REJECTED
				{
					// Cek IR with PO Source Code
					$this->m_purchase_req->updREJECT($PR_NUM, $PRJCODE);
				}
				elseif($PR_STAT == 6) // IF CLOSE
				{
					foreach($_POST['data'] as $d)
					{
						$PR_NUM		= $d['PR_NUM'];
						$ITM_CODE	= $d['ITM_CODE'];
						$this->m_purchase_req->updateVolBud($PR_NUM, $PRJCODE, $ITM_CODE);
					}
				}
				elseif($PR_STAT == 3)
				{
					$projMatReqH 	= array('PR_STAT'	=> $PR_STAT,
											'PR_MEMO'	=> $PR_MEMO);
					$this->m_purchase_req->update($PR_NUM, $projMatReqH);
					
					$this->m_purchase_req->updateJobDet($PR_NUM, $PRJCODE); // UPDATE JOBD ETAIL DAN PR
				}
				
				$projMatReqH 	= array('PR_APPROVER1'	=> $DefEmp_ID,
										'PR_APPROVED1'	=> $PR_APPROVED,
										'PR_NOTE2'		=> $this->input->post('PR_NOTE2'),
										'PR_MEMO'		=> $PR_MEMO,
										'PR_STAT'		=> $PR_STAT);										
				$this->m_purchase_req->update($PR_NUM, $projMatReqH);
			
				// START : UPDATE STATUS
					$this->load->model('m_updash/m_updash', '', TRUE);
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "PR_NUM",
											'DOC_CODE' 		=> $PR_NUM,
											'DOC_STAT' 		=> $PR_STAT,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> $completeName,
											'TBLNAME'		=> "tbl_pr_header");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
			
				// START : UPDATE TO TRANS-COUNT
					$this->load->model('m_updash/m_updash', '', TRUE);
					
					$STAT_BEFORE	= $this->input->post('STAT_BEFORE');		// IF "ADD" CONDITION ALWAYS = PR_STAT
					$parameters 	= array('DOC_CODE' 		=> $PR_NUM,			// TRANSACTION CODE
											'PRJCODE' 		=> $PRJCODE,		// PROJECT
											'TR_TYPE'		=> "PR",			// TRANSACTION TYPE
											'TBL_NAME' 		=> "tbl_pr_header",	// TABLE NAME
											'KEY_NAME'		=> "PR_NUM",		// KEY OF THE TABLE
											'STAT_NAME' 	=> "PR_STAT",		// NAMA FIELD STATUS
											'STATDOC' 		=> $PR_STAT,		// TRANSACTION STATUS
											'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
											'FIELD_NM_ALL'	=> "TOT_REQ",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
											'FIELD_NM_N'	=> "TOT_REQ_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
											'FIELD_NM_C'	=> "TOT_REQ_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
											'FIELD_NM_A'	=> "TOT_REQ_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
											'FIELD_NM_R'	=> "TOT_REQ_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
											'FIELD_NM_RJ'	=> "TOT_REQ_RJ",	// TOTAL REJECT TRANSACTION FOR tbl_dash_data
											'FIELD_NM_CL'	=> "TOT_REQ_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
					$this->m_updash->updateDashData($parameters);
				// END : UPDATE TO TRANS-COUNT
			}
			else
			{
				if($PR_STAT == 5)	// REJECTED
				{
					// Cek IR with PO Source Code
					$this->m_purchase_req->updREJECT($PR_NUM, $PRJCODE);
				}
				elseif($PR_STAT == 6) // IF CLOSE
				{
					foreach($_POST['data'] as $d)
					{
						$PR_NUM		= $d['PR_NUM'];
						$ITM_CODE	= $d['ITM_CODE'];
						$this->m_purchase_req->updateVolBud($PR_NUM, $PRJCODE, $ITM_CODE);
					}
				}
			
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "PR_NUM",
											'DOC_CODE' 		=> $PR_NUM,
											'DOC_STAT' 		=> 7,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> $completeName,
											'TBLNAME'		=> "tbl_pr_header");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
			}
			
			// UPDATE JOBDETAIL ITEM
			if($PR_STAT == 4 || $PR_STAT == 5 || $PR_STAT == 6)
			{
				$projMatReqH 	= array('PR_STAT'	=> $PR_STAT,
										'PR_MEMO'	=> $PR_MEMO);
				$this->m_purchase_req->update($PR_NUM, $projMatReqH);
			
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "PR_NUM",
											'DOC_CODE' 		=> $PR_NUM,
											'DOC_STAT' 		=> $PR_STAT,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> $completeName,
											'TBLNAME'		=> "tbl_pr_header");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
			}
			
			if($PR_STAT == 4)
			{
				// START : DELETE HISTORY
					$this->m_updash->delAppHist($PR_NUM);
				// END : DELETE HISTORY
			}
				
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $PR_NUM;
				$MenuCode 		= 'MN018';
				$TTR_CATEG		= 'APP-UP';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$url			= site_url('c_purchase/c_pr180d0c/iN20_x1/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
}