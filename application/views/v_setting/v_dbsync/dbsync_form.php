<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 21 September 2019
 * File Name	= dbsync_form.php
 * Location		= -
*/
?>
<?php
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

// _global function
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
$decFormat		= 2;
$PRJCODE		= '';
$TRX_TYPE		= '';
if(isset($_POST['TRX_TYPE']))
{
	$this->db->trans_begin();
	
	$PRJCODE		= $_POST['PRJCODE'];
	$TRX_TYPE		= $_POST['TRX_TYPE'];
	
	$sqlRESS	= "UPDATE tbl_journaldetail A, tbl_journalheader B
						SET A.GEJ_STAT = B.GEJ_STAT, A.JournalH_Date = B.JournalH_Date
					WHERE A.JournalH_Code = B.JournalH_Code";
	$this->db->query($sqlRESS);
	
	$sqlJOURN	= "SELECT A.JournalH_Code, A.Acc_Id, B.JournalType, A.JOBCODEID, A.ITM_CODE, 
						A.ITM_VOLM, A.ITM_PRICE, A.JournalD_Debet, A.JournalD_Kredit 
					FROM tbl_journaldetail A
						INNER JOIN tbl_journalheader B ON A.JournalH_Code = B.JournalH_Code
							AND B.JournalType = '$TRX_TYPE'
					WHERE A.proj_Code = '$PRJCODE' AND A.GEJ_STAT = 3 AND A.JournalD_Debet > 0";
	//$resJOURN	= $this->db->query($sqlJOURN)->result();
	if($TRX_TYPE == 'All')
	{
		// RESET VALUE
			$updITM	= "UPDATE tbl_item SET ITM_OUT = 0, ITM_OUTP = 0, UM_VOLM = 0, UM_AMOUNT = 0
						WHERE PRJCODE = '$PRJCODE'";
			$this->db->query($updITM);
			
			$updITM	= "UPDATE tbl_joblist_detail SET OPN_QTY = 0, OPN_AMOUNT = 0, ITM_USED = 0, ITM_USED_AM = 0
						WHERE PRJCODE = '$PRJCODE'";
			$this->db->query($updITM);
			
		$sqlJOURN	= "SELECT A.JournalH_Code, A.Acc_Id, B.JournalType, A.JOBCODEID, A.ITM_CODE, 
							A.ITM_VOLM, A.ITM_PRICE, A.JournalD_Debet, A.JournalD_Kredit 
						FROM tbl_journaldetail A
							INNER JOIN tbl_journalheader B ON A.JournalH_Code = B.JournalH_Code
						WHERE A.proj_Code = '$PRJCODE' AND A.GEJ_STAT = 3 AND A.JournalD_Debet > 0 ORDER BY A.JOBCODEID ASC";
		$resJOURN	= $this->db->query($sqlJOURN)->result();
		foreach($resJOURN as $rowJ1) :
			$JHCode		= $rowJ1->JournalH_Code;
			$JourType	= $rowJ1->JournalType;
			$JOBCODEID	= $rowJ1->JOBCODEID;
			$ITM_CODE	= $rowJ1->ITM_CODE;
			$ITM_VOLM	= $rowJ1->ITM_VOLM;
			$ITM_PRICE	= $rowJ1->ITM_PRICE;
			$ITM_USEDAM	= $rowJ1->JournalD_Debet;
			
			if($JourType == 'GEJ')
			{					
				if($JOBCODEID != '' && $ITM_CODE != '')
				{
					$updITM	= "UPDATE tbl_item SET ITM_OUT = ITM_OUT + $ITM_VOLM, ITM_OUTP = ITM_OUTP + $ITM_PRICE,
									UM_VOLM = UM_VOLM + $ITM_VOLM, UM_AMOUNT = UM_AMOUNT + $ITM_USEDAM
								WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
					$this->db->query($updITM);
					
					$updJD	= "UPDATE tbl_joblist_detail SET ITM_USED = ITM_USED + $ITM_VOLM, ITM_USED_AM = ITM_USED_AM + $ITM_USEDAM
								WHERE PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE'";
					$this->db->query($updJD);
				}
				elseif($JOBCODEID != '' && $ITM_CODE == '')
				{
					$updITM	= "UPDATE tbl_joblist_detail SET ITM_USED = ITM_USED + $ITM_VOLM, ITM_USED_AM = ITM_USED_AM + $ITM_USEDAM
								WHERE PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBCODEID'";
					$this->db->query($updITM);
				}
				elseif($JOBCODEID == '' && $ITM_CODE != '')
				{
					$JOBCODEID1	= '';
					$sqlITMC	= "tbl_joblist_detail WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
					$resITMC	= $this->db->count_all($sqlITMC);
					if($resITMC == 1)
					{
						$sqlITM		= "SELECT JOBCODEID FROM tbl_joblist_detail WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
						$resITM		= $this->db->query($sqlITM)->result();
						foreach($resITM as $rowITM) :
							$JOBCODEID1	= $rowITM->JOBCODEID;
							
							$updITM		= "UPDATE tbl_item SET ITM_OUT = ITM_OUT + $ITM_VOLM, ITM_OUTP = ITM_OUTP + $ITM_PRICE,
												UM_VOLM = UM_VOLM + $ITM_VOLM, UM_AMOUNT = UM_AMOUNT + $ITM_USEDAM
											WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
							$this->db->query($updITM);
							
							$updJD		= "UPDATE tbl_joblist_detail SET ITM_USED = ITM_USED + $ITM_VOLM, ITM_USED_AM = ITM_USED_AM + $ITM_USEDAM
											WHERE PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBCODEID1' AND ITM_CODE = '$ITM_CODE'";
							$this->db->query($updJD);
							
							$updJDet	= "UPDATE tbl_journaldetail SET JOBCODEID = '$JOBCODEID1'
											WHERE JournalH_Code = '$JHCode' AND proj_Code = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
							$this->db->query($updJDet);
						endforeach;
					}
				}
			}
			elseif($JourType == 'OPN')
			{
				$JOBCODEIDX	= '';
				$ITM_VOLMX	= 0;
				$ITM_PRICEX	= 0;
				$ITM_USEDAMX= 0;
				$getOPN	= "SELECT JOBCODEID, OPND_VOLM, OPND_ITMPRICE, OPND_ITMTOTAL FROM tbl_opn_detail
							WHERE OPNH_NUM = '$JHCode' AND ITM_CODE = '$ITM_CODE'";
				$resOPN	= $this->db->query($getOPN)->result();
				foreach($resOPN as $rowOPND) :
					$JOBCODEIDX	= $rowOPND->JOBCODEID;
					$ITM_VOLMX	= $rowOPND->OPND_VOLM;
					$ITM_PRICEX	= $rowOPND->OPND_ITMPRICE;
					$ITM_USEDAMX= $rowOPND->OPND_ITMTOTAL;
				
					$updITM		= "UPDATE tbl_item SET ITM_OUT = ITM_OUT + $ITM_VOLMX, ITM_OUTP = $ITM_PRICEX,
										UM_VOLM = UM_VOLM + $ITM_VOLMX, UM_AMOUNT = UM_AMOUNT + $ITM_USEDAMX
									WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
					$this->db->query($updITM);
					
					$updJD		= "UPDATE tbl_joblist_detail SET OPN_QTY = OPN_QTY + $ITM_VOLMX, OPN_AMOUNT = OPN_AMOUNT + $ITM_USEDAMX,
										ITM_USED = ITM_USED + $ITM_VOLMX, ITM_USED_AM = ITM_USED_AM + $ITM_USEDAMX
									WHERE PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBCODEIDX' AND ITM_CODE = '$ITM_CODE'";
					$this->db->query($updJD);
					
					$updJDet	= "UPDATE tbl_journaldetail SET JOBCODEID = '$JOBCODEIDX', ITM_VOLM = $ITM_VOLMX, ITM_PRICE = $ITM_PRICEX
									WHERE JournalH_Code = '$JHCode' AND proj_Code = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
					$this->db->query($updJDet);
				endforeach;
			}
			elseif($JourType == 'CHO')
			{
				$updITM	= "UPDATE tbl_item SET ITM_OUT = ITM_OUT + $ITM_VOLM, ITM_OUTP = ITM_OUTP + $ITM_PRICE,
								UM_VOLM = UM_VOLM + $ITM_VOLM, UM_AMOUNT = UM_AMOUNT + $ITM_USEDAM
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($updITM);
				
				$updJD	= "UPDATE tbl_joblist_detail SET ITM_USED = ITM_USED + $ITM_VOLM, ITM_USED_AM = ITM_USED_AM + $ITM_USEDAM
							WHERE PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($updJD);
			}
			elseif($JourType == 'CPRJ')
			{
				$updITM	= "UPDATE tbl_item SET ITM_OUT = ITM_OUT + $ITM_VOLM, ITM_OUTP = ITM_OUTP + $ITM_PRICE,
								UM_VOLM = UM_VOLM + $ITM_VOLM, UM_AMOUNT = UM_AMOUNT + $ITM_USEDAM
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($updITM);
				
				$updJD	= "UPDATE tbl_joblist_detail SET ITM_USED = ITM_USED + $ITM_VOLM, ITM_USED_AM = ITM_USED_AM + $ITM_USEDAM
							WHERE PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($updJD);
			}
			elseif($JourType == 'O-EXP')
			{
				$updITM	= "UPDATE tbl_item SET ITM_OUT = ITM_OUT + $ITM_VOLM, ITM_OUTP = ITM_OUTP + $ITM_PRICE,
								UM_VOLM = UM_VOLM + $ITM_VOLM, UM_AMOUNT = UM_AMOUNT + $ITM_USEDAM
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($updITM);
				
				$updJD	= "UPDATE tbl_joblist_detail SET ITM_USED = ITM_USED + $ITM_VOLM, ITM_USED_AM = ITM_USED_AM + $ITM_USEDAM
							WHERE PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($updJD);
			}
			elseif($JourType == 'UM')
			{
				// CLEAR JOURNAL
				$updJDDet	= "DELETE FROM tbl_journaldetail WHERE JournalH_Code = '$JHCode' AND proj_Code = '$PRJCODE' AND ISNULL(JOBCODEID)";
				$this->db->query($updJDDet);
					
				$JOBCODEIDX	= '';
				$ITM_VOLMX	= 0;
				$ITM_PRICEX	= 0;
				$ITM_USEDAMX= 0;
				$getUM	= "SELECT UM_NUM, JOBCODEID, ITM_CODE, ITM_QTY, ITM_PRICE, ITM_TOTAL FROM tbl_um_detail
							WHERE UM_NUM = '$JHCode'";
				$resUM	= $this->db->query($getUM)->result();
				foreach($resUM as $rowUMD) :
					$JOBCODEIDX	= $rowUMD->JOBCODEID;
					$ITM_CODEX	= $rowUMD->ITM_CODE;
					$ITM_VOLMX	= $rowUMD->ITM_QTY;
					$ITM_PRICEX	= $rowUMD->ITM_PRICE;
					$ITM_USEDAMX= $rowUMD->ITM_TOTAL;
					//if($JHCode == 'UMPI.07.19.002-190910170108' || $JHCode == 'UMPI.07.19.002-190910170754')
						//echo "$JHCode - $JOBCODEIDX - $ITM_CODEX<br>";
					
					$updITM		= "UPDATE tbl_item SET ITM_OUT = ITM_OUT + $ITM_VOLMX, ITM_OUTP = ITM_OUTP + $ITM_PRICEX,
										UM_VOLM = UM_VOLM + $ITM_VOLMX, UM_AMOUNT = UM_AMOUNT + $ITM_USEDAMX
									WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
					$this->db->query($updITM);
					
					$updJD		= "UPDATE tbl_joblist_detail SET ITM_USED = ITM_USED + $ITM_VOLMX, ITM_USED_AM = ITM_USED_AM + $ITM_USEDAMX
									WHERE PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBCODEIDX' AND ITM_CODE = '$ITM_CODEX'";
					$this->db->query($updJD);
					
					// CREATE JOURNAL SISI DEBIT
						$sqlL_D	= "SELECT ACC_ID_UM AS ACC_ID, ITM_GROUP, ITM_CATEG FROM tbl_item WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODEX'";
						$resL_D = $this->db->query($sqlL_D)->result();					
						foreach($resL_D as $rowL_D):
							$ACC_NUM	= $rowL_D->ACC_ID;
							$ITM_GROUP	= $rowL_D->ITM_GROUP;
							$ITM_CATEG	= $rowL_D->ITM_CATEG;
							if($ACC_NUM == '')
								$ACC_NUM	= "510110"; // BAHAN LAIN-LAIN
								
							// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
								$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JHCode' AND Journal_DK = 'D' 
												AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM' AND ITM_CODE = '$ITM_CODEX'";
								$resCGEJ	= $this->db->count_all($sqlCGEJ);
								if($resCGEJ == 0)
								{
									$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
													JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect, 
													JOBCODEID, Journal_DK, ITM_CODE, ITM_GROUP, ITM_CATEG, ITM_VOLM, ITM_PRICE)
												VALUES ('$JHCode', '$ACC_NUM', '$PRJCODE', 'IDR', 
													$ITM_USEDAMX, $ITM_USEDAMX, $ITM_USEDAMX, 'Default', 1, 0, 
													'$JOBCODEIDX', 'D', '$ITM_CODEX', '$ITM_GROUP', '$ITM_CATEG', $ITM_VOLMX, $ITM_PRICEX)";
									$this->db->query($sqlGEJDD);
								}
								else
								{
									$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Debet = JournalD_Debet+$ITM_USEDAMX,
														Base_Debet = Base_Debet+$ITM_USEDAMX, COA_Debet = COA_Debet+$ITM_USEDAMX
													WHERE JournalH_Code = '$JHCode' AND Journal_DK = 'D'
														AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM' AND ITM_CODE = '$ITM_CODEX'";
									$this->db->query($sqlUpdCOAD);
								}
							// END : Update to COA - Debit
						endforeach;
					
					// CREATE JOURNAL SISI KREDIT
						$sqlL_K	= "SELECT ACC_ID FROM tbl_item WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODEX'";
						$resL_K = $this->db->query($sqlL_K)->result();					
						foreach($resL_K as $rowL_K):
							$ACC_NUMK	= $rowL_K->ACC_ID;
							// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
								$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JHCode' AND Journal_DK = 'K' 
												AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUMK' AND ITM_CODE = '$ITM_CODEX'";
								$resCGEJ	= $this->db->count_all($sqlCGEJ);
								
								if($resCGEJ == 0)
								{
									$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id,
													JournalD_Kredit, Base_Kredit, COA_Kredit, CostCenter, curr_rate, isDirect, 
													JOBCODEID, Journal_DK, ITM_CODE, ITM_GROUP, ITM_CATEG, ITM_VOLM, ITM_PRICE)
												VALUES ('$JHCode', '$ACC_NUMK', '$PRJCODE', 'IDR', 
													$ITM_USEDAMX, $ITM_USEDAMX, $ITM_USEDAMX, 'Default', 1, 0, 
													'$JOBCODEIDX', 'K', '$ITM_CODEX', '$ITM_GROUP', '$ITM_CATEG', $ITM_VOLMX, $ITM_PRICEX)";
									$this->db->query($sqlGEJDD);
								}
								else
								{
									$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Kredit = JournalD_Kredit+$ITM_USEDAMX,
														Base_Kredit = Base_Kredit+$ITM_USEDAMX, 
														COA_Kredit = COA_Kredit+$ITM_USEDAMX
													WHERE JournalH_Code = '$JHCode' AND Journal_DK = 'K' 
														AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUMK' AND ITM_CODE = '$ITM_CODEX'";
									$this->db->query($sqlUpdCOAD);
								}
						endforeach;
				endforeach;
			}
		endforeach;
	}
	elseif($TRX_TYPE == 'GEJ')
	{
		foreach($resJOURN as $rowJ1) :
			$JOBCODEID	= $rowJ1->JOBCODEID;
			$ITM_CODE	= $rowJ1->ITM_CODE;
			$ITM_VOLM	= $rowJ1->ITM_VOLM;
			$ITM_PRICE	= $rowJ1->ITM_PRICE;
			$ITM_USEDAM	= $rowJ1->JournalD_Debet;
			
			if($JOBCODEID != '' && $ITM_CODE != '')
			{
				$updITM	= "UPDATE tbl_item SET ITM_OUT = ITM_OUT + $ITM_VOLM, ITM_OUTP = ITM_OUTP + $ITM_PRICE,
								UM_VOLM = UM_VOLM + $ITM_VOLM, UM_AMOUNT = UM_AMOUNT + $ITM_USEDAM
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($updITM);
				
				$updITM	= "UPDATE tbl_joblist_detail SET ITM_USED = ITM_USED + $ITM_VOLM, ITM_USED_AM = ITM_USED_AM + $ITM_USEDAM
							WHERE PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($updITM);
			}
			elseif($JOBCODEID != '' && $ITM_CODE == '')
			{
				$updITM	= "UPDATE tbl_joblist_detail SET ITM_USED = ITM_USED + $ITM_VOLM, ITM_USED_AM = ITM_USED_AM + $ITM_USEDAM
							WHERE PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBCODEID'";
				$this->db->query($updITM);
			}
			elseif($JOBCODEID == '' && $ITM_CODE != '')
			{
				// HARUS ADA SYNC JOBCODEID TERLEBIH DAHULU
			}
		endforeach;
	}
	elseif($TRX_TYPE == 'OPN')
	{
		foreach($resJOURN as $rowJ1) :
			$JHCode		= $rowJ1->JournalH_Code;
			$JOBCODEID	= $rowJ1->JOBCODEID;
			$ITM_CODE	= $rowJ1->ITM_CODE;
			$ITM_VOLM	= $rowJ1->ITM_VOLM;
			$ITM_PRICE	= $rowJ1->ITM_PRICE;
			$ITM_USEDAM	= $rowJ1->JournalD_Debet;
			
			$getOPN	= "SELECT JOBCODEID FROM tbl_opn_detail WHERE OPNH_NUM = '$JHCode' AND ITM_CODE = '$ITM_CODE'";
			$resOPN	= $this->db->query($getOPN)->result();
			foreach($resOPN as $rowOPND) :
				$JOBCODEID	= $rowOPND->JOBCODEID;
			endforeach;
			
			$updITM	= "UPDATE tbl_item SET ITM_OUT = ITM_OUT + $ITM_VOLM, ITM_OUTP = ITM_OUTP + $ITM_PRICE,
							UM_VOLM = UM_VOLM + $ITM_VOLM, UM_AMOUNT = UM_AMOUNT + $ITM_USEDAM
						WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($updITM);
			
			$updITM	= "UPDATE tbl_joblist_detail SET OPN_QTY = OPN_QTY + $ITM_VOLM, OPN_AMOUNT = OPN_AMOUNT + $ITM_USEDAM,
							ITM_USED = ITM_USED + $ITM_VOLM, ITM_USED_AM = ITM_USED_AM + $ITM_USEDAM
						WHERE PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($updITM);
		endforeach;
	}
	elseif($TRX_TYPE == 'CHO')
	{
		foreach($resJOURN as $rowJ1) :
			$JOBCODEID	= $rowJ1->JOBCODEID;
			$ITM_CODE	= $rowJ1->ITM_CODE;
			$ITM_VOLM	= $rowJ1->ITM_VOLM;
			$ITM_PRICE	= $rowJ1->ITM_PRICE;
			$ITM_USEDAM	= $rowJ1->JournalD_Debet;
			
			$updITM	= "UPDATE tbl_item SET ITM_OUT = ITM_OUT + $ITM_VOLM, ITM_OUTP = ITM_OUTP + $ITM_PRICE,
							UM_VOLM = UM_VOLM + $ITM_VOLM, UM_AMOUNT = UM_AMOUNT + $ITM_USEDAM
						WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($updITM);
			
			$updITM	= "UPDATE tbl_joblist_detail SET ITM_USED = ITM_USED + $ITM_VOLM, ITM_USED_AM = ITM_USED_AM + $ITM_USEDAM
						WHERE PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($updITM);
		endforeach;
	}
	elseif($TRX_TYPE == 'CPRJ')
	{
		foreach($resJOURN as $rowJ1) :
			$JOBCODEID	= $rowJ1->JOBCODEID;
			$ITM_CODE	= $rowJ1->ITM_CODE;
			$ITM_VOLM	= $rowJ1->ITM_VOLM;
			$ITM_PRICE	= $rowJ1->ITM_PRICE;
			$ITM_USEDAM	= $rowJ1->JournalD_Debet;
			
			$updITM	= "UPDATE tbl_item SET ITM_OUT = ITM_OUT + $ITM_VOLM, ITM_OUTP = ITM_OUTP + $ITM_PRICE,
							UM_VOLM = UM_VOLM + $ITM_VOLM, UM_AMOUNT = UM_AMOUNT + $ITM_USEDAM
						WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($updITM);
			
			$updITM	= "UPDATE tbl_joblist_detail SET ITM_USED = ITM_USED + $ITM_VOLM, ITM_USED_AM = ITM_USED_AM + $ITM_USEDAM
						WHERE PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($updITM);
			echo "$JOBCODEID - $ITM_CODE<br>";
		endforeach;
	}
	elseif($TRX_TYPE == 'O-EXP')
	{
		foreach($resJOURN as $rowJ1) :
			$JOBCODEID	= $rowJ1->JOBCODEID;
			$ITM_CODE	= $rowJ1->ITM_CODE;
			$ITM_VOLM	= $rowJ1->ITM_VOLM;
			$ITM_PRICE	= $rowJ1->ITM_PRICE;
			$ITM_USEDAM	= $rowJ1->JournalD_Debet;
			
			$updITM	= "UPDATE tbl_item SET ITM_OUT = ITM_OUT + $ITM_VOLM, ITM_OUTP = ITM_OUTP + $ITM_PRICE,
							UM_VOLM = UM_VOLM + $ITM_VOLM, UM_AMOUNT = UM_AMOUNT + $ITM_USEDAM
						WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($updITM);
			
			$updITM	= "UPDATE tbl_joblist_detail SET ITM_USED = ITM_USED + $ITM_VOLM, ITM_USED_AM = ITM_USED_AM + $ITM_USEDAM
						WHERE PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($updITM);
		endforeach;
	}
	elseif($TRX_TYPE == 'UM')
	{
	}
	
	if ($this->db->trans_status() === FALSE)
	{
		$this->db->trans_rollback();
	}
	else
	{
		$this->db->trans_commit();
	}
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/style.css'; ?>");</style>
    <title><?php echo $appName; ?> | Data Tables</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/bootstrap/css/bootstrapa.min.css'; ?>">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/css/font-awesome.min.css'; ?>">
    <!-- Ionicons -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/css/ionicons.min.css'; ?>">
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE.min.css'; ?>">
    <!-- DataTables -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/dataTables.bootstrap.css'; ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/AdminLTE.minaa.css'; ?>">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/skins/_all-skins.min.css'; ?>">
        <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE.css'; ?>">
    <!-- daterange picker -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/daterangepicker/daterangepicker.css'; ?>">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datepicker/datepicker3.css'; ?>">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/iCheck/all.css'; ?>">
    <!-- Bootstrap Color Picker -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/colorpicker/bootstrap-colorpicker.min.css'; ?>">
    <!-- Bootstrap time Picker -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/timepicker/bootstrap-timepicker.min.css'; ?>">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/dataTables.bootstrap.css'; ?>">
    <!-- Select2 -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/select2/select2.min.css'; ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/AdminLTE.min.css'; ?>">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/skins/_all-skins.min.css'; ?>">
    <link href="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/iCheck/flat/blue.css') ?>" rel="stylesheet" type="text/css" />
    <!-- Morris chart -->
    <link href="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/morris/morris.css') ?>" rel="stylesheet" type="text/css" />
    <!-- jvectormap -->
    <link href="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/jvectormap/jquery-jvectormap-1.2.2.css') ?>" rel="stylesheet" type="text/css" />
    <!-- Date Picker -->
    <link href="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/datepicker/datepicker3.css') ?>" rel="stylesheet" type="text/css" />
    <!-- Daterange picker -->
    <link href="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/daterangepicker/daterangepicker-bs3.css') ?>" rel="stylesheet" type="text/css" />
    <!-- bootstrap wysihtml5 - text editor -->
    <link href="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') ?>" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="<?php echo base_url('assets/css/ilmudetil.css') ?>">
    <script src="<?php echo base_url('assets/js/jquery-1.10.1.min.js') ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('assets/js/highcharts.js') ?>" type="text/javascript"></script>

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<script type="text/javascript">
    function notifyMe(msg_title, msg_body, redirect_onclick) {
        var granted = 0;
 
        // Let's check if the browser supports notifications
        if (!("Notification" in window)) {
            alert("This browser does not support desktop notification");
        }
 
        // Let's check if the user is okay to get some notification
        else if (Notification.permission === "granted") {
            granted = 1;
        }
 
        // Otherwise, we need to ask the user for permission
        else if (Notification.permission !== 'denied') {
            Notification.requestPermission(function (permission) {
                // If the user is okay, let's create a notification
                if (permission === "granted") {
                    granted = 1;
                }
            });
        }
 
        if (granted == 1) {
            var notification = new Notification(msg_title, {
                body: msg_body,
                icon: 'notif-icon.png'
            });
 
            if (redirect_onclick) {
                notification.onclick = function() {
                    window.location.href = redirect_onclick;
                }
            }
        }
    }
</script>
<script src="<?php echo base_url('assets/js/jquery-1.10.1.min.js') ?>" type="text/javascript"></script>
<?php
	$this->load->view('template/topbar');
	$this->load->view('template/sidebar');
	$LangID 	= $this->session->userdata['LangID'];

	$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
	$resTransl		= $this->db->query($sqlTransl)->result();
	foreach($resTransl as $rowTransl) :
		$TranslCode	= $rowTransl->MLANG_CODE;
		$LangTransl	= $rowTransl->LangTransl;
		
		if($TranslCode == 'Add')$Add = $LangTransl;
		if($TranslCode == 'Edit')$Edit = $LangTransl;
		if($TranslCode == 'Save')$Save = $LangTransl;
		if($TranslCode == 'Update')$Update = $LangTransl;
		if($TranslCode == 'Back')$Back = $LangTransl;
		if($TranslCode == 'Code')$Code = $LangTransl;
		if($TranslCode == 'Name')$Name = $LangTransl;
		if($TranslCode == 'Description')$Description = $LangTransl;
		if($TranslCode == 'Category')$Category = $LangTransl;
		if($TranslCode == 'AccountPosition')$AccountPosition = $LangTransl;
		if($TranslCode == 'AddInvoice')$AddInvoice = $LangTransl;
		if($TranslCode == 'DownPayment')$DownPayment = $LangTransl;
		if($TranslCode == 'ProjectName')$ProjectName = $LangTransl;
	endforeach;
?>

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>
    <?php echo $h1_title; ?>
    <small><?php echo $h2_title; ?></small>
  </h1>
  <?php /*?><ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#">Tables</a></li>
    <li class="active">Data tables</li>
  </ol><?php */?>
</section>
<!-- Main content -->
<section class="content">
<button onClick="notifyMe('Title Notif', 'Body Notif')" style="display:none">Notify me!</button>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border" style="display:none">               
              		<div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body chart-responsive">
                	<form class="form-horizontal" name="frm" method="post" action="" onSubmit="return saveCategory()">
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $ProjectName; ?></label>
                          	<div class="col-sm-10">
                                <select name="PRJCODE" id="PRJCODE" class="form-control select2" data-placeholder="&nbsp;&nbsp;&nbsp;<?php echo $ProjectName; ?>">
                                    <option value="">--- None ---</option>
                                    <?php
                                        $sqlPRJ = "SELECT PRJCODE, PRJNAME FROM tbl_project ORDER BY PRJCODE";
                                        $resPRJ	= $this->db->query($sqlPRJ)->result();
                                        foreach($resPRJ as $row_1) :
                                            $PRJCODE1	= $row_1->PRJCODE;
                                            $PRJNAME	= $row_1->PRJNAME;
                                            ?>
                                            <option value="<?php echo $PRJCODE1; ?>" <?php if($PRJCODE1 == $PRJCODE) { ?> selected <?php } ?>>
                                                <?php echo "$PRJCODE1 - $PRJNAME"; ?>
                                            </option>
                                            <?php
                                        endforeach;
                                    ?>
                                </select>
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Tipe Transaksi</label>
                          	<div class="col-sm-10">
                                <select name="TRX_TYPE" id="TRX_TYPE" class="form-control select2" style="max-width:250px">
                                    <option value="All"> --- All --- </option>
                                    <option value="GEJ" <?php if($TRX_TYPE == 'GEJ') { ?> selected <?php } ?>>Journal Umum</option>
                                    <option value="OPN" <?php if($TRX_TYPE == 'OPN') { ?> selected <?php } ?>>Opname</option>
                                    <option value="CHO" <?php if($TRX_TYPE == 'CHO') { ?> selected <?php } ?>>Penggunaan Kas Kantor</option>
                                    <option value="CPRJ" <?php if($TRX_TYPE == 'CPRJ') { ?> selected <?php } ?>>Penggunaan Kas Proyek</option>
                                    <option value="O-EXP" <?php if($TRX_TYPE == 'O-EXP') { ?> selected <?php } ?>>Penggunaan Lainnya</option>
                                    <option value="UM" <?php if($TRX_TYPE == 'UM') { ?> selected <?php } ?>>Penggunaan Material</option>
                                </select>
                          	</div>
                        </div>
                        <br>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button class="btn btn-primary" >
                                <i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Update; ?>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
</body>
</html>

<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/jQuery/jquery-2.2.3.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/bootstrap/js/bootstrap.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/jquery.dataTables.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/dataTables.bootstrap.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/slimScroll/jquery.slimscroll.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/fastclick/fastclick.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE/dist/js/demo.js'; ?>"></script>

<!-- Select2 -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/select2/select2.full.min.js'; ?>"></script>
<!-- InputMask -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/input-mask/jquery.inputmask.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/input-mask/jquery.inputmask.date.extensions.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/input-mask/jquery.inputmask.extensions.js'; ?>"></script>
<!-- date-range-picker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/daterangepicker/daterangepicker.js'; ?>"></script>
<!-- bootstrap datepicker -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datepicker/bootstrap-datepicker.js'; ?>"></script>
<!-- bootstrap color picker -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/colorpicker/bootstrap-colorpicker.min.js'; ?>"></script>
<!-- bootstrap time picker -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/timepicker/bootstrap-timepicker.min.js'; ?>"></script>
<!-- SlimScroll 1.3.0 -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/slimScroll/jquery.slimscroll.min.js'; ?>"></script>
<!-- iCheck 1.0.1 -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/iCheck/icheck.min.js'; ?>"></script>
<!-- Page script -->

<script>
  $(function () {
    //Initialize Select2 Elements
    $(".select2").select2();

    //Datemask dd/mm/yyyy
    $("#datemask").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});
    //Datemask2 mm/dd/yyyy
    $("#datemask2").inputmask("mm/dd/yyyy", {"placeholder": "mm/dd/yyyy"});
    //Money Euro
    $("[data-mask]").inputmask();

    //Date range picker
    $('#reservation').daterangepicker();
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A'});
    //Date range as a button
    $('#daterange-btn').daterangepicker(
        {
          ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
          },
          startDate: moment().subtract(29, 'days'),
          endDate: moment()
        },
        function (start, end) {
          $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }
    );

    //Date picker
    $('#datepicker1').datepicker({
      autoclose: true
    });

    //Date picker
    $('#datepicker2').datepicker({
      autoclose: true
    });

    //Date picker
    $('#datepicker3').datepicker({
      autoclose: true
    });

    //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass: 'iradio_minimal-blue'
    });
    //Red color scheme for iCheck
    $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
      checkboxClass: 'icheckbox_minimal-red',
      radioClass: 'iradio_minimal-red'
    });
    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass: 'iradio_flat-green'
    });

    //Colorpicker
    $(".my-colorpicker1").colorpicker();
    //color picker with addon
    $(".my-colorpicker2").colorpicker();

    //Timepicker
    $(".timepicker").timepicker({
      showInputs: false
    });
  });
</script>

<script>
	function saveCategory()
	{
		CheckThe_Code = document.getElementById('CheckThe_Code').value;
		if(CheckThe_Code > 0)
		{
			alert('Vendor Category Code is already exist.');
			document.getElementById('VendCat_Code').value = '';
			document.getElementById('VendCat_Code').focus();
			VendCat_Code = document.getElementById('VendCat_Code').value;
			functioncheck()
			return false;
		}
		
		VendCat_Code = document.getElementById('VendCat_Code').value;
		if(VendCat_Code == '')
		{
			alert('Vendor Category Code can not empty.');
			document.getElementById('VendCat_Code1').focus();
			return false;			
		}
	}
</script>
<?php 
$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>