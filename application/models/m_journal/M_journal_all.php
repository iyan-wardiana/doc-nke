<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 13 April 2017
 * File Name	= m_journal.php
 * Location		= -
*/

class M_journal extends CI_Model
{
	// ---------------- START : Pembuatan Journal Header ----------------
	function createJournalH($JournalH_Code, $parameters) // OK
	{
		$JournalH_Code 		= $parameters['JournalH_Code'];		
		$JournalType 		= $parameters['JournalType'];
		$JournalH_Date 		= $parameters['JournalH_Date'];
		$Company_ID			= $parameters['Company_ID'];
		$Source				= $parameters['Source'];
		$Emp_ID 			= $parameters['Emp_ID'];
		$LastUpdate 		= $parameters['LastUpdate'];
		$KursAm_tobase		= $parameters['KursAmount_tobase'];
		$Wh_id				= $parameters['WHCODE'];
		$REFNumb 			= $parameters['Reference_Number'];
		$RefType 			= $parameters['RefType'];
		$proj_Code 			= $parameters['PRJCODE'];
		
		$GEJ_STAT	= 2;
		if($JournalType == 'IR')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'PINV')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'DPP')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'BP')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'BR')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'UM')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'PRJINV')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'RET')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'OPN')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'VOPN')
			$GEJ_STAT	= 5;
			
		// Save Journal Header
		$sqlGEJH 			= "INSERT INTO tbl_journalheader (JournalH_Code, JournalType, JournalH_Date, Company_ID, Source,
									Emp_ID, LastUpdate, KursAmount_tobase, 
									Wh_id, Reference_Number, Reference_Type, proj_Code, GEJ_STAT)
								VALUES ('$JournalH_Code', '$JournalType', '$JournalH_Date', '$Company_ID', '$Source', 
									'$Emp_ID', '$LastUpdate', $KursAm_tobase, 
									'$Wh_id', '$REFNumb', '$RefType', '$proj_Code', '$GEJ_STAT')";
		$this->db->query($sqlGEJH);
	}
	// ---------------- END : Pembuatan Journal Header ----------------
	
	// ---------------- START : Pembuatan Journal Header - KHUSUS INVOICE ----------------
	function createJournalH_INV($JournalH_Code, $parameters) // OK
	{
		$JournalH_Code 		= $parameters['JournalH_Code'];		
		$JournalType 		= $parameters['JournalType'];
		$JournalH_Date 		= $parameters['JournalH_Date'];
		$Company_ID			= $parameters['Company_ID'];
		$Source				= $parameters['Source'];
		$Emp_ID 			= $parameters['Emp_ID'];
		$LastUpdate 		= $parameters['LastUpdate'];
		$KursAm_tobase		= $parameters['KursAmount_tobase'];
		$Wh_id				= $parameters['WHCODE'];
		$REFNumb 			= $parameters['Reference_Number'];
		$RefType 			= $parameters['RefType'];
		$proj_Code 			= $parameters['PRJCODE'];
		$Journal_Amount		= $parameters['Journal_Amount'];
		
		$GEJ_STAT	= 2;
		if($JournalType == 'IR')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'PINV')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'DPP')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'BP')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'BR')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'UM')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'PRJINV')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'OPN')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'RET')
			$GEJ_STAT	= 3;
			
		// Save Journal Header
		$sqlGEJH 			= "INSERT INTO tbl_journalheader (JournalH_Code, JournalType, JournalH_Date, Company_ID, Source,
									Emp_ID, LastUpdate, KursAmount_tobase, 
									Wh_id, Reference_Number, Reference_Type, proj_Code, GEJ_STAT, Journal_Amount)
								VALUES ('$JournalH_Code', '$JournalType', '$JournalH_Date', '$Company_ID', '$Source', 
									'$Emp_ID', '$LastUpdate', $KursAm_tobase, 
									'$Wh_id', '$REFNumb', '$RefType', '$proj_Code', '$GEJ_STAT', $Journal_Amount)";
		$this->db->query($sqlGEJH);
	}
	// ---------------- END : Pembuatan Journal Header ----------------
	
	function createJournalD($JournalH_Code, $parameters) // OK
	{
		$JournalH_Code 		= $parameters['JournalH_Code'];
		$JournalType 		= $parameters['JournalType'];
		$JournalH_Date 		= $parameters['JournalH_Date'];
		$Company_ID			= $parameters['Company_ID'];
		$Currency_ID		= $parameters['Currency_ID'];
		$Source				= $parameters['Source'];
		$Emp_ID 			= $parameters['Emp_ID'];
		$LastUpdate 		= $parameters['LastUpdate'];
		$KursAm_tobase		= $parameters['KursAmount_tobase'];
		$Wh_id				= $parameters['WHCODE'];
		$REFNumb 			= $parameters['Reference_Number'];
		$RefType 			= $parameters['RefType'];
		$proj_Code 			= $parameters['PRJCODE'];
		$PRJCODE 			= $parameters['PRJCODE'];
		$JSource			= $parameters['JSource'];
		$TRANS_CATEG1		= $parameters['TRANS_CATEG'];
    	$Transaction_Date	= $parameters['JournalH_Date'];
    	$Item_Code 			= $parameters['ITM_CODE'];
    	$ACC_ID 			= $parameters['ACC_ID'];
    	$Qty_Plus 			= $parameters['ITM_QTY'];
    	$Item_Price 		= $parameters['ITM_PRICE'];
    	$Item_Disc 			= $parameters['ITM_DISC'];
    	$TAXCODE1 			= $parameters['TAXCODE1'];
    	$TAXPRICE1 			= $parameters['TAXPRICE1'];
		
		$PRJ_isHO			= 0;
		$sqlPRJHO 			= "SELECT isHO FROM tbl_project WHERE PRJCODE = '$PRJCODE' LIMIT 1";
		$resPRJHO			= $this->db->query($sqlPRJHO)->result();
		foreach($resPRJHO as $rowPRJHO):
			$PRJ_isHO		= $rowPRJHO->isHO;
		endforeach;
		
		$ITM_AMOUNT			= ($Qty_Plus * $Item_Price) - $Item_Disc;
		$AMOUNT_PPN			= 0;
		$AMOUNT_PPh			= 0;
		if($TAXCODE1 == 'TAX01')
			$AMOUNT_PPN		= $TAXPRICE1;
		elseif($TAXCODE1 == 'TAX02')
			$AMOUNT_PPh		= $TAXPRICE1;
			
		$Unit_Price 		= $Item_Price;
		
		$transacValue 		= ($Qty_Plus * $Item_Price) - $Item_Disc;
		//echo "transacValue = $transacValue<br>";
		//echo "ACC_ID = $ACC_ID<br>";
		//return false;
		// Create on 12 Mei 2015. by. : Dian Hermanto
		
		/* ------------- PEMBENTUKAN JURNAL ---------------------
			Jurnal yang terbentuk saat IR (REC)
			10029 PERSEDIAAN											xxxx
				21008 - HUTANG USAHA YANG BELUM DIFAKTURKAN						xxxx
		
			Jurnal yang terbentuk saat PURCHASE INVOICE (PINV)
			21008 - HUTANG BELUM DIFAKTURKAN							xxxx
			20012 - PPN - Masukan										xxxx					// jika ada ppn
				20005 HUTANG SUPPLIER											xxxx
		
			Jurnal yang terbentuk saat PEMBAYARAN PINV
			20005 HUTANG SUPPLIER										xxxx
				10013 BANK ACCOUNT												xxxx
		*/
		
		// UNTUK SETTING LINK ACCOUNT
		// 1. BUAT RELASI ANTARA ITEM DENGAN COA
		// 2. BUAT RELASI ANTARA COA DENGAN TRANSAKSI. MISALNYA TRANSAKSI PENERIMAAN, PEMFAKTURAN, PEMBAYARAN DAN LAIN-LAIN
		//echo "TRANS_CATEG = $TRANS_CATEG";
		//return false;
		
		$data 			= explode("~" , $TRANS_CATEG1);
		$TRANS_CATEG	= $data[0];
		
		if($TRANS_CATEG == 'REC')			// REC = Item Receipt
		{
			// PEREKAMAN JEJAK KE tbl_itemhistory				
				$sqlHist 		= "INSERT INTO tbl_itemhistory (JournalH_Code, proj_Code, Transaction_Date, Item_Code, Qty_Plus,
										Qty_Min, Transaction_Type, Transaction_Value, Company_ID, Currency_ID)
									VALUES ('$JournalH_Code', '$proj_Code', '$Transaction_Date', '$Item_Code', $Qty_Plus, 0,
										'$JSource', $transacValue, '$Company_ID', '$Currency_ID')";
				$this->db->query($sqlHist);
				
			// START : UPDATE L/R
				$PRJCODE	= $proj_Code;
				$PERIODM	= date('m', strtotime($JournalH_Date));
				$PERIODY	= date('Y', strtotime($JournalH_Date));
				$ITM_GROUP 	= $parameters['ITM_GROUP'];
				$ITM_TYPE 	= $parameters['ITM_TYPE'];
				
				if($ITM_GROUP == 'M')
				{
					// 1.ISMTRL, 2.ISRENT, 3.ISPART, 4.ISFULE, 5.ISLUBTICANT, 6. ISFASTM, 7.ISWAGE
					if($ITM_TYPE == 1)
					{
						$updLR	= "UPDATE tbl_profitloss SET STOCK_MTR = STOCK_MTR+$transacValue 
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
					elseif($ITM_TYPE == 3 || $ITM_TYPE == 6)
					{
						$updLR	= "UPDATE tbl_profitloss SET STOCK_TOOLS = STOCK_TOOLS+$transacValue 
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
					elseif($ITM_TYPE == 4 || $ITM_TYPE == 5)
					{
						$updLR	= "UPDATE tbl_profitloss SET STOCK_BBM = STOCK_BBM+$transacValue
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
				}
				elseif($ITM_GROUP == 'T')
				{
					$updLR	= "UPDATE tbl_profitloss SET STOCK_TOOLS = STOCK_TOOLS+$transacValue 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
			// END : UPDATE L/R
			
			// ---------------- START : Pembuatan Journal Detail ----------------		
				$curr_rate = 1; // Default IDR ke IDR
				
				// ------------------------------- D E B I T -------------------------------
					// START : 04-07-2018
						// Cek Link Account untuk penerimaan di sisi Debit
						$sqlCL_D	= "tbl_item WHERE PRJCODE = '$proj_Code' AND ITM_CODE = '$Item_Code'";
						$this->db->count_all($sqlCL_D);				
						$resCL_D	= $this->db->count_all($sqlCL_D);
						if($resCL_D > 0)
						{
							$sqlL_D	= "SELECT ACC_ID FROM tbl_item WHERE PRJCODE = '$proj_Code' AND ITM_CODE = '$Item_Code'";
							$resL_D = $this->db->query($sqlL_D)->result();					
							foreach($resL_D as $rowL_D):
								$ACC_NUM	= $rowL_D->ACC_ID;
								// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
									$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' 
														AND Journal_DK = 'D' AND Journal_Type = 'NTAX'";
									$resCGEJ	= $this->db->count_all($sqlCGEJ);
									
									if($resCGEJ == 0)
									{
											$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, 
															Currency_id, JournalD_Debet, Base_Debet, 
															COA_Debet, CostCenter, curr_rate, isDirect, Journal_DK)
														VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue,
														$transacValue, $transacValue, 'Default', 1, 0, 'D')";
											$this->db->query($sqlGEJDD);
									}
									else
									{
											$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET 
																JournalD_Debet = JournalD_Debet+$transacValue,
																Base_Debet = Base_Debet+$transacValue,
																COA_Debet = COA_Debet+$transacValue
															WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D'
																AND Journal_Type = 'NTAX'";
											$this->db->query($sqlUpdCOAD);
									}
								// START : Update to COA - Debit
									/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
														Base_Debet2 = Base_Debet2+$transacValue
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
									$this->db->query($sqlUpdCOAD); // OK*/
									
									$isHO			= 0;
									$syncPRJ		= '';
									$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
									$resISHO		= $this->db->query($sqlISHO)->result();
									foreach($resISHO as $rowISHO):
										$isHO		= $rowISHO->isHO;
										$syncPRJ	= $rowISHO->syncPRJ;
									endforeach;
									$dataPecah 	= explode("~",$syncPRJ);
									$jmD 		= count($dataPecah);
								
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
																Base_Debet2 = Base_Debet2+$transacValue
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Debit
							endforeach;
						}
						else
						{
							$ACC_NUM	= "11051100"; // PERSEDIAAN BAHAN
							// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
								$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' 
													AND Journal_DK = 'D' AND Journal_Type = 'NTAX'";
								$resCGEJ	= $this->db->count_all($sqlCGEJ);
								
								if($resCGEJ == 0)
								{
										$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id,
														JournalD_Debet, Base_Debet, 
														COA_Debet, CostCenter, curr_rate, isDirect, Journal_DK)
													VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue,
													$transacValue, $transacValue, 'Default', 1, 0, 'D')";
										$this->db->query($sqlGEJDD);
								}
								else
								{
										$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Debet = JournalD_Debet+$transacValue,
															Base_Debet = Base_Debet+$transacValue,
															COA_Debet = COA_Debet+$transacValue
														WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D'
															AND Journal_Type = 'NTAX'";
										$this->db->query($sqlUpdCOAD);
								}
							// START : Update to COA - Debit
								/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
													Base_Debet2 = Base_Debet2+$transacValue
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
								$this->db->query($sqlUpdCOAD); // OK*/
								
								$isHO			= 0;
								$syncPRJ		= '';
								$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resISHO		= $this->db->query($sqlISHO)->result();
								foreach($resISHO as $rowISHO):
									$isHO		= $rowISHO->isHO;
									$syncPRJ	= $rowISHO->syncPRJ;
								endforeach;
								$dataPecah 	= explode("~",$syncPRJ);
								$jmD 		= count($dataPecah);
							
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
															Base_Debet2 = Base_Debet2+$transacValue
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
						}
					// END : 04-07-2018
					
				// ------------------------------- K R E D I T -------------------------------
					// Hasil diskusi 22/03/18 : Journal sisi kredit ditetapkan ke 2111 = Hutang Supplier/Sewa Belum Difakturkan
				
					// START : 04-07-2018
						$ACC_NUM	= "2111"; // Hutang Supplier/Sewa Belum Difakturkan
						// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
							$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
												AND Journal_Type = 'NTAX'";
							$resCGEJ	= $this->db->count_all($sqlCGEJ);							
							if($resCGEJ == 0)
							{
								// START : Save Journal Detail - Debit
									$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
													JournalD_Kredit, Base_Kredit, 
													COA_Kredit, CostCenter, curr_rate, isDirect, Journal_DK, Other_Desc)
												VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue, $transacValue,
													$transacValue, 'Default', 1, 0, 'K', 'NOT_SET_LA')";
									$this->db->query($sqlGEJDD);
								// END : Save Journal Detail - Debit
							}
							else
							{
								// START : UPDATE Journal Detail - Debit
									$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Kredit = JournalD_Kredit+$transacValue,
														Base_Kredit = Base_Kredit+$transacValue, COA_Kredit = COA_Kredit+$transacValue
													WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K' AND Journal_Type = 'NTAX'";
									$this->db->query($sqlUpdCOAD);
								// END : UPDATE Journal Detail - Debit
							}
							
						// START : Update to COA - Debit
							/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue, 
													Base_Kredit2 = Base_Kredit2+$transacValue
											WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
							$this->db->query($sqlUpdCOAD); // OK*/
							
							$isHO			= 0;
							$syncPRJ		= '';
							$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resISHO		= $this->db->query($sqlISHO)->result();
							foreach($resISHO as $rowISHO):
								$isHO		= $rowISHO->isHO;
								$syncPRJ	= $rowISHO->syncPRJ;
							endforeach;
							$dataPecah 	= explode("~",$syncPRJ);
							$jmD 		= count($dataPecah);
						
							if($jmD > 0)
							{
								$SYNC_PRJ	= '';
								for($i=0; $i < $jmD; $i++)
								{
									$SYNC_PRJ	= $dataPecah[$i];
									$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue, 
														Base_Kredit2 = Base_Kredit2+$transacValue
													WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
									$this->db->query($sqlUpdCOA);
								}
							}
						// END : Update to COA - Debit
					// END : 22-03-2018
			// ---------------- END : Pembuatan Journal Detail ----------------
		}
		elseif($TRANS_CATEG == 'PINV')		// PINV = Purchase Invoice
		{
			$SPLCATEG	= $data[1];
			// ---------------- START : Pembuatan Journal Detail ----------------		
				$curr_rate = 1; // Default IDR ke IDR
				
				// ------------------------------- D E B I T -------------------------------
					// Hasil diskusi 22/03/18 : Journal sisi debit ditetapkan ke 2111 = Hutang Supplier/Sewa Belum Difakturkan
					// sebagai kebalikan saat pembuatan journal penerimaan
				
					// START : 22-03-2018
						$ACC_NUM	= "2111"; // Hutang Supplier/Sewa Belum Difakturkan
						// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
							/* START :HOLD TEMPORARY
								$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D'
													AND Journal_Type = 'NTAX'";
								$resCGEJ	= $this->db->count_all($sqlCGEJ);
							END :HOLD TEMPORARY */
							
							/* START :HOLD TEMPORARY
								if($resCGEJ == 0)
								{
										$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id,
														JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect, 
														Journal_DK, Other_Desc)
													VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue, $transacValue,
														$transacValue, 'Default', 1, 0, 'D', 'NOT_SET_LA')";
										$this->db->query($sqlGEJDD);
								}
								else
								{
										$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Debet = JournalD_Debet+$transacValue,
															Base_Debet = Base_Debet+$transacValue, COA_Debet = COA_Debet+$transacValue
														WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D'
															AND Journal_Type = 'NTAX'";
										$this->db->query($sqlUpdCOAD);
								}
							END :HOLD TEMPORARY */
							// START : Update to COA - Debit
								/* START :HOLD TEMPORARY
									$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
														Base_Debet2 = Base_Debet2+$transacValue
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
									$this->db->query($sqlUpdCOAD); // OK*/
									
									/*$isHO			= 0;
									$syncPRJ		= '';
									$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
									$resISHO		= $this->db->query($sqlISHO)->result();
									foreach($resISHO as $rowISHO):
										$isHO		= $rowISHO->isHO;
										$syncPRJ	= $rowISHO->syncPRJ;
									endforeach;
									$dataPecah 	= explode("~",$syncPRJ);
									$jmD 		= count($dataPecah);
								
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
																Base_Debet2 = Base_Debet2+$transacValue
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
											$this->db->query($sqlUpdCOA);
										}
									}
								END :HOLD TEMPORARY */
							// END : Update to COA - Debit
					// END : 04-07-2018
					
				// ------------------------------- K R E D I T -------------------------------
					// Hasil diskusi 22/03/18 : Journal sisi kredit ditetapkan ke 2101100 Hutang Supplier/Sewa
				
					// START : 04-07-2018
						$sqlCL_K	= "tbl_link_account WHERE LA_ITM_CODE = '$SPLCATEG' AND LA_CATEG = 'PINV' AND LA_DK = 'K'";
						$this->db->count_all($sqlCL_K);				
						$resCL_K	= $this->db->count_all($sqlCL_K);
						if($resCL_K > 0)
						{
							$sqlL_K	= "SELECT LA_ACCID FROM tbl_link_account
										WHERE LA_ITM_CODE = '$SPLCATEG' AND LA_CATEG = 'PINV' AND LA_DK = 'K'";
							$resL_K = $this->db->query($sqlL_K)->result();					
							foreach($resL_K as $rowL_K):
								$ACC_NUM	= $rowL_K->LA_ACCID;
								// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
									$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
														AND Journal_Type = 'NTAX'";
									$resCGEJ	= $this->db->count_all($sqlCGEJ);
									
									if($resCGEJ == 0)
									{
											$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, 
															Currency_id, JournalD_Kredit, Base_Kredit, COA_Kredit, CostCenter, 
															curr_rate, isDirect, Journal_DK)
														VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue,
															$transacValue, $transacValue, 'Default', 1, 0, 'K')";
											$this->db->query($sqlGEJDD);
									}
									else
									{
											$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Kredit = JournalD_Kredit+$transacValue,
																Base_Kredit = Base_Kredit+$transacValue,
																COA_Kredit = COA_Kredit+$transacValue
															WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
																AND Journal_Type = 'NTAX'";
											$this->db->query($sqlUpdCOAD);
									}
								// START : Update to COA - Debit
									/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue,
															Base_Kredit2 = Base_Kredit2+$transacValue
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
									$this->db->query($sqlUpdCOAD); // OK*/
									
									$isHO			= 0;
									$syncPRJ		= '';
									$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
									$resISHO		= $this->db->query($sqlISHO)->result();
									foreach($resISHO as $rowISHO):
										$isHO		= $rowISHO->isHO;
										$syncPRJ	= $rowISHO->syncPRJ;
									endforeach;
									$dataPecah 	= explode("~",$syncPRJ);
									$jmD 		= count($dataPecah);
								
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue,
																Base_Kredit2 = Base_Kredit2+$transacValue
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Debit
							endforeach;
						}
						else
						{
							$ACC_NUM	= "2101100"; // Hutang Supplier/Sewa
							// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
								$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
												AND Journal_Type = 'NTAX'";
								$resCGEJ	= $this->db->count_all($sqlCGEJ);
								
								if($resCGEJ == 0)
								{
									// START : Save Journal Detail - Debit
										$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id,
														JournalD_Kredit, Base_Kredit, COA_Kredit, CostCenter, curr_rate, isDirect,
														Journal_DK, Other_Desc)
													VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue, 
														$transacValue, $transacValue, 'Default', 1, 0, 'K', 'NOT_SET_LA')";
										$this->db->query($sqlGEJDD);
									// END : Save Journal Detail - Debit
								}
								else
								{
									// START : UPDATE Journal Detail - Debit
										$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Kredit = JournalD_Kredit+$transacValue,
															Base_Kredit = Base_Kredit+$transacValue,
															COA_Kredit = COA_Kredit+$transacValue
														WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K' 
															AND Journal_Type = 'NTAX'";
										$this->db->query($sqlUpdCOAD);
									// END : UPDATE Journal Detail - Debit
								}
							// START : Update to COA - Debit
								/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue,
													Base_Kredit2 = Base_Kredit2+$transacValue
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
								$this->db->query($sqlUpdCOAD); // OK*/
								
								$isHO			= 0;
								$syncPRJ		= '';
								$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resISHO		= $this->db->query($sqlISHO)->result();
								foreach($resISHO as $rowISHO):
									$isHO		= $rowISHO->isHO;
									$syncPRJ	= $rowISHO->syncPRJ;
								endforeach;
								$dataPecah 	= explode("~",$syncPRJ);
								$jmD 		= count($dataPecah);
							
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue,
															Base_Kredit2 = Base_Kredit2+$transacValue
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
						}
					// END : 04-07-2018
			// ---------------- END : Pembuatan Journal Detail ----------------
		}
		elseif($TRANS_CATEG == 'PINV2')		// PINV = Purchase Invoice - KHUSUS PPn Masukan (DEBIT ONLY)
		{
    		$Notes 		= $parameters['Notes'];
			$SPLCATEG	= $data[1];
			// ---------------- START : Pembuatan Journal Detail ----------------		
				$curr_rate = 1; // Default IDR ke IDR
				
				// ------------------------------- D E B I T -------------------------------				
					// START : 11-10-2018 - PPn Masukan (Hard Code)
						$sqlCL_D	= "tbl_tax_ppn";	
						$resCL_D	= $this->db->count_all($sqlCL_D);
						if($resCL_D > 0)
						{
							$sqlL_D	= "SELECT TAXLA_LINKIN FROM tbl_tax_ppn";
							$resL_D = $this->db->query($sqlL_D)->result();					
							foreach($resL_D as $rowL_D):
								$ACC_NUM	= $rowL_D->TAXLA_LINKIN;
								$sqlGEJDD 	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id,
												JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect, 
												Journal_DK, Other_Desc)
											VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue, $transacValue,
												$transacValue, 'Default', 1, 0, 'D', 'NOT_SET_LA')";
								$this->db->query($sqlGEJDD);
							endforeach;
						
							// START : Update to COA - Debit
								$isHO			= 0;
								$syncPRJ		= '';
								$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resISHO		= $this->db->query($sqlISHO)->result();
								foreach($resISHO as $rowISHO):
									$isHO		= $rowISHO->isHO;
									$syncPRJ	= $rowISHO->syncPRJ;
								endforeach;
								$dataPecah 	= explode("~",$syncPRJ);
								$jmD 		= count($dataPecah);
							
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
															Base_Debet2 = Base_Debet2+$transacValue
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
						}
					// END : 04-07-2018						
			// ---------------- END : Pembuatan Journal Detail ----------------
		}
		elseif($TRANS_CATEG == 'PINV3')		// PINV = Purchase Invoice - Khusus PPh Header
		{
			$SPLCATEG	= $data[1];
			$PPhTax 	= $parameters['PPhTax'];
			$PPhAmount 	= $parameters['PPhAmount'];
			// ---------------- START : Pembuatan Journal Detail ----------------		
				$curr_rate = 1; // Default IDR ke IDR
				
				// ------------------------------- D E B I T -------------------------------				
					// START : 11-10-2018 - Ambil settingan dari Link Account Kategori Supplier
						$sqlCL_K	= "tbl_link_account WHERE LA_ITM_CODE = '$SPLCATEG' AND LA_CATEG = 'PINV' AND LA_DK = 'K'";
						$this->db->count_all($sqlCL_K);				
						$resCL_K	= $this->db->count_all($sqlCL_K);
						if($resCL_K > 0)
						{
							$sqlL_K	= "SELECT LA_ACCID FROM tbl_link_account
										WHERE LA_ITM_CODE = '$SPLCATEG' AND LA_CATEG = 'PINV' AND LA_DK = 'K'";
							$resL_K = $this->db->query($sqlL_K)->result();					
							foreach($resL_K as $rowL_K):
								$ACC_NUM	= $rowL_K->LA_ACCID;
								$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id,
												JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect, 
												Journal_DK, Other_Desc)
											VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue, $transacValue,
												$transacValue, 'Default', 1, 0, 'D', 'NOT_SET_LA')";
								$this->db->query($sqlGEJDD);
								
								// START : Update to COA - Debit
									$isHO			= 0;
									$syncPRJ		= '';
									$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
									$resISHO		= $this->db->query($sqlISHO)->result();
									foreach($resISHO as $rowISHO):
										$isHO		= $rowISHO->isHO;
										$syncPRJ	= $rowISHO->syncPRJ;
									endforeach;
									$dataPecah 	= explode("~",$syncPRJ);
									$jmD 		= count($dataPecah);
								
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
																Base_Debet2 = Base_Debet2+$transacValue
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Debit
							endforeach;
						}
					// END : 11-10-2018
					
				// ------------------------------- K R E D I T -------------------------------
					// START : 11-10-2018 - Ambil settingan dari Link Account Kategori Supplier
						// Cek Link Account untuk PPh
						// didapatkan dari setinga LA Pajak
						$sqlTLAC	= "tbl_tax_la WHERE TAXLA_NUM = '$PPhTax' AND TAXLA_LINKIN != ''";			
						$resTLAC	= $this->db->count_all($sqlTLAC);			
						if($resTLAC > 0)
						{
							$TAXLA_LINKIN	= '';
							$sqlTLA			= "SELECT TAXLA_LINKIN FROM tbl_tax_la WHERE TAXLA_NUM = '$PPhTax'";
							$resTLA 		= $this->db->query($sqlTLA)->result();					
							foreach($resTLA as $rowTLA):
								$TAXLA_LINKIN	= $rowTLA->TAXLA_LINKIN;
															
								$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, 
												Currency_id, JournalD_Kredit, Base_Kredit, COA_Kredit, CostCenter, 
												curr_rate, isDirect, Journal_DK)
											VALUES ('$JournalH_Code', '$TAXLA_LINKIN', '$proj_Code', 'IDR', $transacValue,
												$transacValue, $transacValue, 'Default', 1, 0, 'K')";
								$this->db->query($sqlGEJDD);
									
								// START : Update to COA - Debit
									$isHO			= 0;
									$syncPRJ		= '';
									$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$TAXLA_LINKIN' LIMIT 1";
									$resISHO		= $this->db->query($sqlISHO)->result();
									foreach($resISHO as $rowISHO):
										$isHO		= $rowISHO->isHO;
										$syncPRJ	= $rowISHO->syncPRJ;
									endforeach;
									$dataPecah 	= explode("~",$syncPRJ);
									$jmD 		= count($dataPecah);
								
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue,
																	Base_Kredit2 = Base_Kredit2+$transacValue
																WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$TAXLA_LINKIN'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Debit
							endforeach;
						}
					// END : 11-10-2018
			// ---------------- END : Pembuatan Journal Detail ----------------
		}
		elseif($TRANS_CATEG == 'PINVD')		// PINV = Purchase Invoice Direct
		{
			$SPLCATEG	= $data[1];
			// HANYA ADA PENAMBAHAN KE HUTANG SUPPLIER
			// ---------------- START : Pembuatan Journal Detail ----------------		
				$curr_rate = 1; // Default IDR ke IDR
				
				// ------------------------------- D E B I T -------------------------------
					// Journal sisi debit ditetapkan ke 2111 = Hutang Supplier/Sewa Belum Difakturkan sebagai kebalikan
					// DITIADAKAN					
				// ------------------------------- K R E D I T -------------------------------
					// Journal sisi kredit ditetapkan ke 2101100 Hutang Supplier/Sewa
				
					// START : 04-07-2018
						$sqlCL_K	= "tbl_link_account WHERE LA_ITM_CODE = '$SPLCATEG' AND LA_CATEG = 'PINV' AND LA_DK = 'K'";
						$this->db->count_all($sqlCL_K);				
						$resCL_K	= $this->db->count_all($sqlCL_K);
						if($resCL_K > 0)
						{
							$sqlL_K	= "SELECT LA_ACCID FROM tbl_link_account
										WHERE LA_ITM_CODE = '$SPLCATEG' AND LA_CATEG = 'PINV' AND LA_DK = 'K'";
							$resL_K = $this->db->query($sqlL_K)->result();					
							foreach($resL_K as $rowL_K):
								$ACC_NUM	= $rowL_K->LA_ACCID;
								// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
									$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
														AND Journal_Type = 'NTAX'";
									$resCGEJ	= $this->db->count_all($sqlCGEJ);
									
									if($resCGEJ == 0)
									{
											$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, 
															Currency_id, JournalD_Kredit, Base_Kredit, COA_Kredit, CostCenter, 
															curr_rate, isDirect, Journal_DK)
														VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue,
															$transacValue, $transacValue, 'Default', 1, 0, 'K')";
											$this->db->query($sqlGEJDD);
									}
									else
									{
											$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Kredit = JournalD_Kredit+$transacValue,
																Base_Kredit = Base_Kredit+$transacValue,
																COA_Kredit = COA_Kredit+$transacValue
															WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
																AND Journal_Type = 'NTAX'";
											$this->db->query($sqlUpdCOAD);
									}
								// START : Update to COA - Debit
									/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue,
															Base_Kredit2 = Base_Kredit2+$transacValue
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
									$this->db->query($sqlUpdCOAD); // OK*/
									
									$isHO			= 0;
									$syncPRJ		= '';
									$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
									$resISHO		= $this->db->query($sqlISHO)->result();
									foreach($resISHO as $rowISHO):
										$isHO		= $rowISHO->isHO;
										$syncPRJ	= $rowISHO->syncPRJ;
									endforeach;
									$dataPecah 	= explode("~",$syncPRJ);
									$jmD 		= count($dataPecah);
								
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue,
																Base_Kredit2 = Base_Kredit2+$transacValue
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Debit
							endforeach;
						}
						else
						{
							$ACC_NUM	= "2101100"; // Hutang Supplier/Sewa
							// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
								$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
												AND Journal_Type = 'NTAX'";
								$resCGEJ	= $this->db->count_all($sqlCGEJ);
								
								if($resCGEJ == 0)
								{
									// START : Save Journal Detail - Debit
										$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id,
														JournalD_Kredit, Base_Kredit, COA_Kredit, CostCenter, curr_rate, isDirect,
														Journal_DK, Other_Desc)
													VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue, 
														$transacValue, $transacValue, 'Default', 1, 0, 'K', 'NOT_SET_LA')";
										$this->db->query($sqlGEJDD);
									// END : Save Journal Detail - Debit
								}
								else
								{
									// START : UPDATE Journal Detail - Debit
										$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Kredit = JournalD_Kredit+$transacValue,
															Base_Kredit = Base_Kredit+$transacValue,
															COA_Kredit = COA_Kredit+$transacValue
														WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K' 
															AND Journal_Type = 'NTAX'";
										$this->db->query($sqlUpdCOAD);
									// END : UPDATE Journal Detail - Debit
								}
							// START : Update to COA - Debit
								/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue,
													Base_Kredit2 = Base_Kredit2+$transacValue
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
								$this->db->query($sqlUpdCOAD); // OK*/
								
								$isHO			= 0;
								$syncPRJ		= '';
								$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resISHO		= $this->db->query($sqlISHO)->result();
								foreach($resISHO as $rowISHO):
									$isHO		= $rowISHO->isHO;
									$syncPRJ	= $rowISHO->syncPRJ;
								endforeach;
								$dataPecah 	= explode("~",$syncPRJ);
								$jmD 		= count($dataPecah);
							
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue,
															Base_Kredit2 = Base_Kredit2+$transacValue
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
						}
					// END : 04-07-2018
			// ---------------- END : Pembuatan Journal Detail ----------------
		}
		elseif($TRANS_CATEG == 'BP')		// BP = Bank Payment
		{
			$SPLCATEG	= $data[1];
			$PPhTax 	= $parameters['PPhTax'];
			$PPhAmount 	= $parameters['PPhAmount'];
			$DiscAmount	= $parameters['DiscAmount'];
			// ---------------- START : Pembuatan Journal Detail ----------------
				$curr_rate 		= 1; // Default IDR ke IDR
				$Unit_Price 	= $Item_Price;				
				$transacValuex 	= $Qty_Plus * $Item_Price;
				// $ACC_ID_D		= '2101100';		// HUTANG SUPPLIER - DEFAULT
				// $transacValue	= $transacValuex + $AMOUNT_PPN - $AMOUNT_PPh;
				// PPhAmount dan DiscAmount memiliki sifat memotong nominal Invoice
				$transacValue	= $transacValuex + $AMOUNT_PPN - $PPhAmount - $DiscAmount;
				
				// ------------------------------- D E B I T -------------------------------
				
					// Cek Link Account untuk pembayaran di sisi Debit
					// didapatkan dari setinga kategori supplier
					$sqlCL_D	= "tbl_link_account WHERE LA_ITM_CODE = '$SPLCATEG' AND LA_CATEG = 'BP' AND LA_DK = 'D'";			
					$resCL_D	= $this->db->count_all($sqlCL_D);			
					if($resCL_D > 0)
					{
						$sqlL_D	= "SELECT LA_ACCID FROM tbl_link_account
									WHERE LA_ITM_CODE = '$SPLCATEG' AND LA_CATEG = 'BP' AND LA_DK = 'D'";
						$resL_D = $this->db->query($sqlL_D)->result();					
						foreach($resL_D as $rowL_D):
							$ACC_NUM	= $rowL_D->LA_ACCID;
							// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
								$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
													AND Journal_Type = 'NTAX'";
								$resCGEJ	= $this->db->count_all($sqlCGEJ);
							
								if($resCGEJ == 0)
								{
										$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
														JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect, 
														Journal_DK, Other_Desc)
													VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue, 
														$transacValue, $transacValue, 'Default', 1, 0, 'D', 'NOT_SET_LA')";
										$this->db->query($sqlGEJDD);
								}
								else
								{
										$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Debet = JournalD_Debet+$transacValue,
															Base_Debet = Base_Debet+$transacValue, COA_Debet = COA_Debet+$transacValue
														WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
															AND Journal_Type = 'NTAX'";
										$this->db->query($sqlUpdCOAD);
								}
								
								// START : Update to COA - Debit
									/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
														Base_Debet2 = Base_Debet2+$transacValue
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
									$this->db->query($sqlUpdCOAD); // OK*/
									
									$isHO			= 0;
									$syncPRJ		= '';
									$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
									$resISHO		= $this->db->query($sqlISHO)->result();
									foreach($resISHO as $rowISHO):
										$isHO		= $rowISHO->isHO;
										$syncPRJ	= $rowISHO->syncPRJ;
									endforeach;
									$dataPecah 	= explode("~",$syncPRJ);
									$jmD 		= count($dataPecah);
								
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
																Base_Debet2 = Base_Debet2+$transacValue
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Debit
						endforeach;
					}
					else // if not setting in Link Account set to 2111 = Hutang Supplier/Sewa Belum Difakturkan
					{
						$ACC_NUM	= "2101100"; // Hutang Supplier/Sewa
						// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
							$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
												AND Journal_Type = 'NTAX'";
							$resCGEJ	= $this->db->count_all($sqlCGEJ);
						
							if($resCGEJ == 0)
							{
									$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
													JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect, 
													Journal_DK, Other_Desc)
												VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue, 
													$transacValue, $transacValue, 'Default', 1, 0, 'D', 'NOT_SET_LA')";
									$this->db->query($sqlGEJDD);
							}
							else
							{
									$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Debet = JournalD_Debet+$transacValue,
														Base_Debet = Base_Debet+$transacValue, COA_Debet = COA_Debet+$transacValue
													WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
														AND Journal_Type = 'NTAX'";
									$this->db->query($sqlUpdCOAD);
							}
							
						// START : Update to COA - Debit
							/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
												Base_Debet2 = Base_Debet2+$transacValue
											WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
							$this->db->query($sqlUpdCOAD); // OK*/
							
							$isHO			= 0;
							$syncPRJ		= '';
							$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resISHO		= $this->db->query($sqlISHO)->result();
							foreach($resISHO as $rowISHO):
								$isHO		= $rowISHO->isHO;
								$syncPRJ	= $rowISHO->syncPRJ;
							endforeach;
							$dataPecah 	= explode("~",$syncPRJ);
							$jmD 		= count($dataPecah);
						
							if($jmD > 0)
							{
								$SYNC_PRJ	= '';
								for($i=0; $i < $jmD; $i++)
								{
									$SYNC_PRJ	= $dataPecah[$i];
									$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
														Base_Debet2 = Base_Debet2+$transacValue
													WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
									$this->db->query($sqlUpdCOA);
								}
							}
						// END : Update to COA - Debit
					}
				
					// Cek Link Account untuk PPh
					// didapatkan dari setinga LA Pajak
					$sqlTLAC	= "tbl_tax_la WHERE TAXLA_NUM = '$PPhTax' AND TAXLA_LINKIN != ''";			
					$resTLAC	= $this->db->count_all($sqlTLAC);			
					if($resTLAC > 0)
					{
						$TAXLA_LINKIN	= '';
						$sqlTLA			= "SELECT TAXLA_LINKIN FROM tbl_tax_la WHERE TAXLA_NUM = '$PPhTax'";
						$resTLA 		= $this->db->query($sqlTLA)->result();					
						foreach($resTLA as $rowTLA):
							$TAXLA_LINKIN	= $rowTLA->TAXLA_LINKIN;
							// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
								$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
													AND Acc_Id = '$TAXLA_LINKIN'";
								$resCGEJ	= $this->db->count_all($sqlCGEJ);
							
								if($resCGEJ == 0)
								{
										$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
														JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect, 
														Journal_DK, Other_Desc)
													VALUES ('$JournalH_Code', '$TAXLA_LINKIN', '$proj_Code', 'IDR', $PPhAmount, 
														$PPhAmount, $PPhAmount, 'Default', 1, 0, 'D', 'PPh')";
										$this->db->query($sqlGEJDD);
								}
								else
								{
										$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Debet = JournalD_Debet+$PPhAmount,
															Base_Debet = Base_Debet+$PPhAmount, COA_Debet = COA_Debet+$PPhAmount
														WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
															AND Acc_Id = '$TAXLA_LINKIN'";
										$this->db->query($sqlUpdCOAD);
								}
								
								
							// START : Update to COA - Debit
								/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$PPhAmount,
													Base_Debet2 = Base_Debet2+$PPhAmount
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$TAXLA_LINKIN'";
								$this->db->query($sqlUpdCOAD); // OK*/
								
								$isHO			= 0;
								$syncPRJ		= '';
								$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$TAXLA_LINKIN' LIMIT 1";
								$resISHO		= $this->db->query($sqlISHO)->result();
								foreach($resISHO as $rowISHO):
									$isHO		= $rowISHO->isHO;
									$syncPRJ	= $rowISHO->syncPRJ;
								endforeach;
								$dataPecah 	= explode("~",$syncPRJ);
								$jmD 		= count($dataPecah);
							
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$PPhAmount,
															Base_Debet2 = Base_Debet2+$PPhAmount
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$TAXLA_LINKIN'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
						endforeach;
					}
					
				// ------------------------------- K R E D I T -------------------------------
				
				$ACC_ID_K		= $ACC_ID;				// BANK ACCOUNT SELECTED
				// START : Save Journal Detail - Kredit
					$sqlGEJDK = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, JournalD_Kredit,
									Base_Kredit, COA_Kredit, CostCenter, curr_rate, isDirect, Journal_DK)
								VALUES ('$JournalH_Code', '$ACC_ID_K', '$proj_Code', 'IDR', $transacValue, $transacValue,
									$transacValue, 'Default', 1, 0, 'K')";
					$this->db->query($sqlGEJDK);
				// END : Save Journal Detail - Kredit
										
				// START : Update to COA - Kredit
					/*$sqlUpdCOAD		= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue, 
											Base_Kredit2 = Base_Kredit2+$transacValue
										WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_ID_K'";
					$this->db->query($sqlUpdCOAD); // OK*/
							
					$isHO			= 0;
					$syncPRJ		= '';
					$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
										WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_ID_K' LIMIT 1";
					$resISHO		= $this->db->query($sqlISHO)->result();
					foreach($resISHO as $rowISHO):
						$isHO		= $rowISHO->isHO;
						$syncPRJ	= $rowISHO->syncPRJ;
					endforeach;
					$dataPecah 	= explode("~",$syncPRJ);
					$jmD 		= count($dataPecah);
				
					if($jmD > 0)
					{
						$SYNC_PRJ	= '';
						for($i=0; $i < $jmD; $i++)
						{
							$SYNC_PRJ	= $dataPecah[$i];
							$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue, 
												Base_Kredit2 = Base_Kredit2+$transacValue
											WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_ID_K'";
							$this->db->query($sqlUpdCOA);
						}
					}
				// END : Update to COA - Kredit
				
				// Cek Link Account untuk PPh
				// didapatkan dari setinga LA Pajak
				$sqlTLAC	= "tbl_tax_la WHERE TAXLA_NUM = '$PPhTax' AND TAXLA_LINKOUT != ''";			
				$resTLAC	= $this->db->count_all($sqlTLAC);			
				if($resTLAC > 0)
				{
					$TAXLA_LINKOUT	= '';
					$sqlTLA			= "SELECT TAXLA_LINKOUT FROM tbl_tax_la WHERE TAXLA_NUM = '$PPhTax'";
					$resTLA 		= $this->db->query($sqlTLA)->result();					
					foreach($resTLA as $rowTLA):
						$TAXLA_LINKOUT	= $rowTLA->TAXLA_LINKOUT;
						// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
							$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K' 
												AND Acc_Id = '$TAXLA_LINKOUT'";
							$resCGEJ	= $this->db->count_all($sqlCGEJ);
						
							if($resCGEJ == 0)
							{
									$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
													JournalD_Kredit, Base_Kredit, COA_Kredit, CostCenter, curr_rate, isDirect, 
													Journal_DK, Other_Desc)
												VALUES ('$JournalH_Code', '$TAXLA_LINKOUT', '$proj_Code', 'IDR', $PPhAmount, 
													$PPhAmount, $PPhAmount, 'Default', 1, 0, 'K', 'PPh')";
									$this->db->query($sqlGEJDD);
							}
							else
							{
									$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Kredit = JournalD_Kredit+$PPhAmount,
														Base_Kredit = Base_Kredit+$PPhAmount, COA_Kredit = COA_Kredit+$PPhAmount
													WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K' 
														AND Acc_Id = '$TAXLA_LINKOUT'";
									$this->db->query($sqlUpdCOAD);
							}
							
						// START : Update to COA - Debit
							/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$PPhAmount,
												Base_Kredit2 = Base_Kredit2+$PPhAmount
											WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$TAXLA_LINKOUT'";
							$this->db->query($sqlUpdCOAD); // OK*/
						
							$isHO			= 0;
							$syncPRJ		= '';
							$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$TAXLA_LINKOUT' LIMIT 1";
							$resISHO		= $this->db->query($sqlISHO)->result();
							foreach($resISHO as $rowISHO):
								$isHO		= $rowISHO->isHO;
								$syncPRJ	= $rowISHO->syncPRJ;
							endforeach;
							$dataPecah 	= explode("~",$syncPRJ);
							$jmD 		= count($dataPecah);
						
							if($jmD > 0)
							{
								$SYNC_PRJ	= '';
								for($i=0; $i < $jmD; $i++)
								{
									$SYNC_PRJ	= $dataPecah[$i];
									$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$PPhAmount,
														Base_Kredit2 = Base_Kredit2+$PPhAmount
													WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$TAXLA_LINKOUT'";
									$this->db->query($sqlUpdCOA);
								}
							}
						// END : Update to COA - Debit
					endforeach;
				}
			// ---------------- END : Pembuatan Journal Detail ----------------
		}
		elseif($TRANS_CATEG == 'DP')		// DP = Down Payment
		{
			$SPLCATEG	= $data[1];
			//return false;
			// ---------------- START : Pembuatan Journal Detail ----------------
				$curr_rate 		= 1; // Default IDR ke IDR
				$Unit_Price 	= $Item_Price;				
				$transacValuex 	= $Qty_Plus * $Item_Price;
				$ACC_ID_D		= '110601300';		// UANG MUKA SUBKONTRAKTOR & PEMASOK
				$transacValue	= $transacValuex + $AMOUNT_PPN - $AMOUNT_PPh;
				
				// ------------------------------- D E B I T -------------------------------
				
					// Cek Link Account untuk penerimaan di sisi Debit
					$sqlCL_D	= "tbl_link_account WHERE LA_ITM_CODE = '$SPLCATEG' AND LA_CATEG = 'DP' AND LA_DK = 'D'";
					$this->db->count_all($sqlCL_D);				
					$resCL_D	= $this->db->count_all($sqlCL_D);			
					if($resCL_D > 0)
					{
						$sqlL_D	= "SELECT LA_ACCID FROM tbl_link_account
									WHERE LA_ITM_CODE = '$SPLCATEG' AND LA_CATEG = 'DP' AND LA_DK = 'D'";
						$resL_D = $this->db->query($sqlL_D)->result();					
						foreach($resL_D as $rowL_D):
							$ACC_NUM	= $rowL_D->LA_ACCID;
							
							// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
								$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
													AND Journal_Type = 'NTAX'";
								$resCGEJ	= $this->db->count_all($sqlCGEJ);
							
								if($resCGEJ == 0)
								{
										$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
														JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect, 
														Journal_DK, Other_Desc)
													VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue, 
														$transacValue, $transacValue, 'Default', 1, 0, 'D', 'NOT_SET_LA')";
										$this->db->query($sqlGEJDD);
								}
								else
								{
										$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Debet = JournalD_Debet+$transacValue,
															Base_Debet = Base_Debet+$transacValue, COA_Debet = COA_Debet+$transacValue
														WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
															AND Journal_Type = 'NTAX'";
										$this->db->query($sqlUpdCOAD);
								}
								
							// START : Update to COA - Debit
								/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
													Base_Debet2 = Base_Debet2+$transacValue
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
								$this->db->query($sqlUpdCOAD); // OK*/
					
								$isHO			= 0;
								$syncPRJ		= '';
								$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resISHO		= $this->db->query($sqlISHO)->result();
								foreach($resISHO as $rowISHO):
									$isHO		= $rowISHO->isHO;
									$syncPRJ	= $rowISHO->syncPRJ;
								endforeach;
								$dataPecah 	= explode("~",$syncPRJ);
								$jmD 		= count($dataPecah);
							
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
															Base_Debet2 = Base_Debet2+$transacValue
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
						endforeach;
					}
					else
					{
						$ACC_NUM	= "110601300"; // UANG MUKA SUBKONTRAKTOR & PEMASOK
						// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
							$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
												AND Journal_Type = 'NTAX'";
							$resCGEJ	= $this->db->count_all($sqlCGEJ);
						
							if($resCGEJ == 0)
							{
									$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
													JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect, 
													Journal_DK, Other_Desc)
												VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue, 
													$transacValue, $transacValue, 'Default', 1, 0, 'D', 'NOT_SET_LA')";
									$this->db->query($sqlGEJDD);
							}
							else
							{
									$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Debet = JournalD_Debet+$transacValue,
														Base_Debet = Base_Debet+$transacValue, COA_Debet = COA_Debet+$transacValue
													WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
														AND Journal_Type = 'NTAX'";
									$this->db->query($sqlUpdCOAD);
							}
							
						// START : Update to COA - Debit
							/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
												Base_Debet2 = Base_Debet2+$transacValue
											WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
							$this->db->query($sqlUpdCOAD); // OK*/
			
							$isHO			= 0;
							$syncPRJ		= '';
							$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resISHO		= $this->db->query($sqlISHO)->result();
							foreach($resISHO as $rowISHO):
								$isHO		= $rowISHO->isHO;
								$syncPRJ	= $rowISHO->syncPRJ;
							endforeach;
							$dataPecah 	= explode("~",$syncPRJ);
							$jmD 		= count($dataPecah);
						
							if($jmD > 0)
							{
								$SYNC_PRJ	= '';
								for($i=0; $i < $jmD; $i++)
								{
									$SYNC_PRJ	= $dataPecah[$i];
									$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
														Base_Debet2 = Base_Debet2+$transacValue
													WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
									$this->db->query($sqlUpdCOA);
								}
							}
						// END : Update to COA - Debit
					}
				// ------------------------------- K R E D I T -------------------------------
				
				$ACC_ID_K		= $ACC_ID;				// BANK ACCOUNT SELECTED
				// START : Save Journal Detail - Kredit
					$sqlGEJDK = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, JournalD_Kredit,
									Base_Kredit, COA_Kredit, CostCenter, curr_rate, isDirect, Journal_DK)
								VALUES ('$JournalH_Code', '$ACC_ID_K', '$proj_Code', 'IDR', $transacValue, $transacValue,
									$transacValue, 'Default', 1, 0, 'K')";
					$this->db->query($sqlGEJDK);
				// END : Save Journal Detail - Kredit
										
				// START : Update to COA - Kredit
					/*$sqlUpdCOAD		= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue, 
											Base_Kredit2 = Base_Kredit2+$transacValue
										WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_ID_K'";
					$this->db->query($sqlUpdCOAD); // OK*/
				
					$isHO			= 0;
					$syncPRJ		= '';
					$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
										WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_ID_K' LIMIT 1";
					$resISHO		= $this->db->query($sqlISHO)->result();
					foreach($resISHO as $rowISHO):
						$isHO		= $rowISHO->isHO;
						$syncPRJ	= $rowISHO->syncPRJ;
					endforeach;
					$dataPecah 	= explode("~",$syncPRJ);
					$jmD 		= count($dataPecah);
				
					if($jmD > 0)
					{
						$SYNC_PRJ	= '';
						for($i=0; $i < $jmD; $i++)
						{
							$SYNC_PRJ	= $dataPecah[$i];
							$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue, 
												Base_Kredit2 = Base_Kredit2+$transacValue
											WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_ID_K'";
							$this->db->query($sqlUpdCOA);
						}
					}
				// END : Update to COA - Kredit
			// ---------------- END : Pembuatan Journal Detail ----------------
		}
		elseif($TRANS_CATEG == 'DPP')		// DP = DP Payment dor INV
		{
			$SPLCATEG	= $data[1];
			// ---------------- START : Pembuatan Journal Detail ----------------
				$curr_rate 		= 1; // Default IDR ke IDR
				$Unit_Price 	= $Item_Price;				
				$transacValuex 	= $Qty_Plus * $Item_Price;
				//$ACC_ID_D		= '110601300';		// UANG MUKA SUBKONTRAKTOR & PEMASOK
				$transacValue	= $transacValuex + $AMOUNT_PPN - $AMOUNT_PPh;
				
				// ------------------------------- D E B I T -------------------------------
				
					// Cek Link Account untuk penerimaan di sisi Debit
					// DISAMAKAN KONDISINYA DENGAN SAAT PEMBAYARAN. HANYA SUMBER PEMBAYARANYA (KREDIT) YANG BEDA.
					// YAITU BERASAL DARI DP, BUKAN DARI AKUN BANK
					$sqlCL_D	= "tbl_link_account WHERE LA_ITM_CODE = '$SPLCATEG' AND LA_CATEG = 'BP' AND LA_DK = 'D'";
					$this->db->count_all($sqlCL_D);				
					$resCL_D	= $this->db->count_all($sqlCL_D);			
					if($resCL_D > 0)
					{
						$sqlL_D	= "SELECT LA_ACCID FROM tbl_link_account
									WHERE LA_ITM_CODE = '$SPLCATEG' AND LA_CATEG = 'BP' AND LA_DK = 'D'";
						$resL_D = $this->db->query($sqlL_D)->result();					
						foreach($resL_D as $rowL_D):
							$ACC_NUM	= $rowL_D->LA_ACCID;
							// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
								$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
													AND Journal_Type = 'NTAX'";
								$resCGEJ	= $this->db->count_all($sqlCGEJ);
							
								if($resCGEJ == 0)
								{
										$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
														JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect, 
														Journal_DK, Other_Desc)
													VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue, 
														$transacValue, $transacValue, 'Default', 1, 0, 'D', 'NOT_SET_LA')";
										$this->db->query($sqlGEJDD);
								}
								else
								{
										$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Debet = JournalD_Debet+$transacValue,
															Base_Debet = Base_Debet+$transacValue, COA_Debet = COA_Debet+$transacValue
														WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
															AND Journal_Type = 'NTAX'";
										$this->db->query($sqlUpdCOAD);
								}
								
							// START : Update to COA - Debit
								/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
													Base_Debet2 = Base_Debet2+$transacValue
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
								$this->db->query($sqlUpdCOAD); // OK*/
				
								$isHO			= 0;
								$syncPRJ		= '';
								$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resISHO		= $this->db->query($sqlISHO)->result();
								foreach($resISHO as $rowISHO):
									$isHO		= $rowISHO->isHO;
									$syncPRJ	= $rowISHO->syncPRJ;
								endforeach;
								$dataPecah 	= explode("~",$syncPRJ);
								$jmD 		= count($dataPecah);
							
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
															Base_Debet2 = Base_Debet2+$transacValue
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
						endforeach;
					}
					else // if not setting in Link Account set to 2111 = Hutang Supplier/Sewa Belum Difakturkan
					{
						$ACC_NUM	= "2101100"; // Hutang Supplier/Sewa
						// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
							$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
												AND Journal_Type = 'NTAX'";
							$resCGEJ	= $this->db->count_all($sqlCGEJ);
						
							if($resCGEJ == 0)
							{
									$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
													JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect, 
													Journal_DK, Other_Desc)
												VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue, 
													$transacValue, $transacValue, 'Default', 1, 0, 'D', 'NOT_SET_LA')";
									$this->db->query($sqlGEJDD);
							}
							else
							{
									$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Debet = JournalD_Debet+$transacValue,
														Base_Debet = Base_Debet+$transacValue, COA_Debet = COA_Debet+$transacValue
													WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
														AND Journal_Type = 'NTAX'";
									$this->db->query($sqlUpdCOAD);
							}
							
						// START : Update to COA - Debit
							/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
												Base_Debet2 = Base_Debet2+$transacValue
											WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
							$this->db->query($sqlUpdCOAD); // OK*/
							
							$isHO			= 0;
							$syncPRJ		= '';
							$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resISHO		= $this->db->query($sqlISHO)->result();
							foreach($resISHO as $rowISHO):
								$isHO		= $rowISHO->isHO;
								$syncPRJ	= $rowISHO->syncPRJ;
							endforeach;
							$dataPecah 	= explode("~",$syncPRJ);
							$jmD 		= count($dataPecah);
						
							if($jmD > 0)
							{
								$SYNC_PRJ	= '';
								for($i=0; $i < $jmD; $i++)
								{
									$SYNC_PRJ	= $dataPecah[$i];
									$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
														Base_Debet2 = Base_Debet2+$transacValue
													WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
									$this->db->query($sqlUpdCOA);
								}
							}
						// END : Update to COA - Debit
					}
					
				// ------------------------------- K R E D I T -------------------------------
				
					// Cek Link Account untuk penerimaan di sisi Debit
					// KEBALIKAN DARI SAAT PEMBUATAN JURNAL DP. UANG MUKA DI SISI DEBIT. SEMENTARA UNTUK SAAT INI
					// UANG MUKA DI SISI KREDIT
					$sqlCL_D	= "tbl_link_account WHERE LA_ITM_CODE = '$SPLCATEG' AND LA_CATEG = 'DP' AND LA_DK = 'D'";
					$this->db->count_all($sqlCL_D);				
					$resCL_D	= $this->db->count_all($sqlCL_D);			
					if($resCL_D > 0)
					{
						$sqlL_D	= "SELECT LA_ACCID FROM tbl_link_account
									WHERE LA_ITM_CODE = '$SPLCATEG' AND LA_CATEG = 'DP' AND LA_DK = 'D'"; // OK DI DEBIT SESUI SETTING
						$resL_D = $this->db->query($sqlL_D)->result();					
						foreach($resL_D as $rowL_D):
							$ACC_NUM	= $rowL_D->LA_ACCID;
							
							// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
								$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K' 
													AND Journal_Type = 'NTAX'";
								$resCGEJ	= $this->db->count_all($sqlCGEJ);
								
								if($resCGEJ == 0)
								{
										$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id,
														JournalD_Kredit, Base_Kredit, 
														COA_Kredit, CostCenter, curr_rate, isDirect, Journal_DK)
													VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue, 
														$transacValue,$transacValue, 'Default', 1, 0, 'K')";
										$this->db->query($sqlGEJDD);
								}
								else
								{
										$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Kredit = JournalD_Kredit+$transacValue,
															Base_Kredit = Base_Kredit+$transacValue, 
															COA_Kredit = COA_Kredit+$transacValue
														WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K' 
															AND Journal_Type = 'NTAX'";
										$this->db->query($sqlUpdCOAD);
								}
							// START : Update to COA - Debit
								/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue, 
													Base_Kredit2 = Base_Kredit2+$transacValue
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
								$this->db->query($sqlUpdCOAD); // OK*/
					
								$isHO			= 0;
								$syncPRJ		= '';
								$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resISHO		= $this->db->query($sqlISHO)->result();
								foreach($resISHO as $rowISHO):
									$isHO		= $rowISHO->isHO;
									$syncPRJ	= $rowISHO->syncPRJ;
								endforeach;
								$dataPecah 	= explode("~",$syncPRJ);
								$jmD 		= count($dataPecah);
							
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue, 
															Base_Kredit2 = Base_Kredit2+$transacValue
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
						endforeach;
					}
					else
					{
						$ACC_NUM	= "110601300"; // UANG MUKA SUBKONTRAKTOR & PEMASOK
						
						// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
							$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K' 
												AND Journal_Type = 'NTAX'";
							$resCGEJ	= $this->db->count_all($sqlCGEJ);
							
							if($resCGEJ == 0)
							{
									$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id,
													JournalD_Kredit, Base_Kredit, 
													COA_Kredit, CostCenter, curr_rate, isDirect, Journal_DK)
												VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue, 
													$transacValue,$transacValue, 'Default', 1, 0, 'K')";
									$this->db->query($sqlGEJDD);
							}
							else
							{
									$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Kredit = JournalD_Kredit+$transacValue,
														Base_Kredit = Base_Kredit+$transacValue, 
														COA_Kredit = COA_Kredit+$transacValue
													WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K' 
														AND Journal_Type = 'NTAX'";
									$this->db->query($sqlUpdCOAD);
							}
						// START : Update to COA - Debit
							/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue, 
												Base_Kredit2 = Base_Kredit2+$transacValue
											WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";*/
							$this->db->query($sqlUpdCOAD); // OK
				
							$isHO			= 0;
							$syncPRJ		= '';
							$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resISHO		= $this->db->query($sqlISHO)->result();
							foreach($resISHO as $rowISHO):
								$isHO		= $rowISHO->isHO;
								$syncPRJ	= $rowISHO->syncPRJ;
							endforeach;
							$dataPecah 	= explode("~",$syncPRJ);
							$jmD 		= count($dataPecah);
						
							if($jmD > 0)
							{
								$SYNC_PRJ	= '';
								for($i=0; $i < $jmD; $i++)
								{
									$SYNC_PRJ	= $dataPecah[$i];
									$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue, 
														Base_Kredit2 = Base_Kredit2+$transacValue
													WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
									$this->db->query($sqlUpdCOA);
								}
							}
						// END : Update to COA - Debit
					}
			// ---------------- END : Pembuatan Journal Detail ----------------
		}
		elseif($TRANS_CATEG == 'UM')		// UM = Use Material
		{				
			$curr_rate = 1; // Default IDR ke IDR
			// START : UPDATE L/R
				$PRJCODE	= $proj_Code;
				$PERIODM	= date('m', strtotime($JournalH_Date));
				$PERIODY	= date('Y', strtotime($JournalH_Date));
				$ITM_GROUP 	= $parameters['ITM_GROUP'];
				$ITM_TYPE 	= $parameters['ITM_TYPE'];
				
				// ADD EXPENSE
				if($ITM_GROUP == 'M')
				{
					// 1.ISMTRL, 2.ISRENT, 3.ISPART, 4.ISFULE, 5.ISLUBTICANT, 6. ISFASTM, 7.ISWAGE
					if($ITM_TYPE == 1)
					{
						$updLR	= "UPDATE tbl_profitloss SET BPP_MTR_PLAN = BPP_MTR_PLAN+$transacValue 
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
					elseif($ITM_TYPE == 3 || $ITM_TYPE == 6)
					{
						$updLR	= "UPDATE tbl_profitloss SET BPP_ALAT_PLAN = BPP_ALAT_PLAN+$transacValue 
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
					elseif($ITM_TYPE == 4 || $ITM_TYPE == 5)
					{
						$updLR	= "UPDATE tbl_profitloss SET BPP_MTR_PLAN = BPP_MTR_PLAN+$transacValue 
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
				}
				elseif($ITM_GROUP == 'T')
				{
					$updLR	= "UPDATE tbl_profitloss SET BPP_ALAT_PLAN = BPP_ALAT_PLAN+$transacValue 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
				
				// MIN STOCK ON PROFIT LOSS
				if($ITM_GROUP == 'M')
				{
					// 1.ISMTRL, 2.ISRENT, 3.ISPART, 4.ISFUEL, 5.ISLUBTICANT, 6. ISFASTM, 7.ISWAGE
					if($ITM_TYPE == 1)
					{
						$updLR	= "UPDATE tbl_profitloss SET STOCK_MTR = STOCK_MTR-$transacValue 
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
					elseif($ITM_TYPE == 3 || $ITM_TYPE == 6)
					{
						$updLR	= "UPDATE tbl_profitloss SET STOCK_TOOLS = STOCK_TOOLS-$transacValue 
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
					elseif($ITM_TYPE == 4 || $ITM_TYPE == 5)
					{
						$updLR	= "UPDATE tbl_profitloss SET STOCK_BBM = STOCK_BBM-$transacValue
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
				}
				elseif($ITM_GROUP == 'T')
				{
					$updLR	= "UPDATE tbl_profitloss SET STOCK_TOOLS = STOCK_TOOLS-$transacValue 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
			// END : UPDATE L/R
			
			// ---------------- START : Pembuatan Journal Detail ----------------
				
				// ------------------------------- D E B I T -------------------------------
					// Hasil diskusi 22/03/18 : Journal sisi debit ditetapkan ke 510110 BAHAN LAIN-LAIN jika belum disetting
				
					// START : 04-07-2018
						// Cek Link Account pada tbl_item
						$sqlCL_D	= "tbl_item WHERE PRJCODE = '$proj_Code' AND ITM_CODE = '$Item_Code'";
						$resCL_D	= $this->db->count_all($sqlCL_D);
						if($resCL_D > 0)
						{
							$sqlL_D	= "SELECT ACC_ID_UM AS ACC_ID FROM tbl_item 
										WHERE PRJCODE = '$proj_Code' AND ITM_CODE = '$Item_Code'";
							$resL_D = $this->db->query($sqlL_D)->result();					
							foreach($resL_D as $rowL_D):
								$ACC_NUM	= $rowL_D->ACC_ID;
								if($ACC_NUM == '')
									$ACC_NUM	= "510110"; // BAHAN LAIN-LAIN
									
								// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
									$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
													AND Journal_Type = 'NTAX' ";
									$resCGEJ	= $this->db->count_all($sqlCGEJ);
									if($resCGEJ == 0)
									{
											$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
															JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect,
															Journal_DK)
														VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue, 
															$transacValue, $transacValue, 'Default', 1, 0, 'D')";
											$this->db->query($sqlGEJDD);
									}
									else
									{
											$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Debet = JournalD_Debet+$transacValue,
																Base_Debet = Base_Debet+$transacValue,
																COA_Debet = COA_Debet+$transacValue
															WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D'
																AND Journal_Type = 'NTAX'";
											$this->db->query($sqlUpdCOAD);
									}
									
								// START : Update to COA - Debit
									/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
														Base_Debet2 = Base_Debet2+$transacValue
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
									$this->db->query($sqlUpdCOAD); // OK*/
					
									$isHO			= 0;
									$syncPRJ		= '';
									$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
									$resISHO		= $this->db->query($sqlISHO)->result();
									foreach($resISHO as $rowISHO):
										$isHO		= $rowISHO->isHO;
										$syncPRJ	= $rowISHO->syncPRJ;
									endforeach;
									$dataPecah 	= explode("~",$syncPRJ);
									$jmD 		= count($dataPecah);
								
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
																Base_Debet2 = Base_Debet2+$transacValue
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Debit
							endforeach;
						}
						else
						{
							$ACC_NUM	= "510110"; // BAHAN LAIN-LAIN
							// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
								$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
												AND Journal_Type = 'NTAX'";
								$resCGEJ	= $this->db->count_all($sqlCGEJ);
								if($resCGEJ == 0)
								{
										$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
														JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect,
														Journal_DK)
													VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue, 
														$transacValue, $transacValue, 'Default', 1, 0, 'D')";
										$this->db->query($sqlGEJDD);
								}
								else
								{
										$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Debet = JournalD_Debet+$transacValue,
															Base_Debet = Base_Debet+$transacValue,
															COA_Debet = COA_Debet+$transacValue
														WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D'
															AND Journal_Type = 'NTAX'";
										$this->db->query($sqlUpdCOAD);
								}
							// START : Update to COA - Debit
								/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
													Base_Debet2 = Base_Debet2+$transacValue
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
								$this->db->query($sqlUpdCOAD); // OK*/
				
								$isHO			= 0;
								$syncPRJ		= '';
								$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resISHO		= $this->db->query($sqlISHO)->result();
								foreach($resISHO as $rowISHO):
									$isHO		= $rowISHO->isHO;
									$syncPRJ	= $rowISHO->syncPRJ;
								endforeach;
								$dataPecah 	= explode("~",$syncPRJ);
								$jmD 		= count($dataPecah);
							
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
															Base_Debet2 = Base_Debet2+$transacValue
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
						}
					// END : 04-07-2018
					
				// ------------------------------- K R E D I T -------------------------------
					// Hasil diskusi 22/03/18 : Journal sisi kredit ditetapkan ke 11051100 = Persediaan Bahan/Material sebagai kebalikan
					// saat pembuatan journal penerimaan
				
					// START : 04-07-2018
						// Cek Link Account untuk penerimaan di sisi Kredit
						$sqlCL_K	= "tbl_item WHERE PRJCODE = '$proj_Code' AND ITM_CODE = '$Item_Code'";
						$this->db->count_all($sqlCL_K);				
						$resCL_K	= $this->db->count_all($sqlCL_K);
						if($resCL_K > 0)
						{
							$sqlL_K	= "SELECT ACC_ID FROM tbl_item WHERE PRJCODE = '$proj_Code' AND ITM_CODE = '$Item_Code'";
							$resL_K = $this->db->query($sqlL_K)->result();					
							foreach($resL_K as $rowL_K):
								$ACC_NUM	= $rowL_K->ACC_ID;
								// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
									$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K' 
													AND Journal_Type = 'NTAX'";
									$resCGEJ	= $this->db->count_all($sqlCGEJ);
									
									if($resCGEJ == 0)
									{
											$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id,
															JournalD_Kredit, Base_Kredit, 
															COA_Kredit, CostCenter, curr_rate, isDirect, Journal_DK)
														VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue, 
															$transacValue,$transacValue, 'Default', 1, 0, 'K')";
											$this->db->query($sqlGEJDD);
									}
									else
									{
											$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Kredit = JournalD_Kredit+$transacValue,
																Base_Kredit = Base_Kredit+$transacValue, 
																COA_Kredit = COA_Kredit+$transacValue
															WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K' 
																AND Journal_Type = 'NTAX'";
											$this->db->query($sqlUpdCOAD);
									}
									
								// START : Update to COA - Debit
									/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue, 
														Base_Kredit2 = Base_Kredit2+$transacValue
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
									$this->db->query($sqlUpdCOAD); // OK*/
					
									$isHO			= 0;
									$syncPRJ		= '';
									$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
									$resISHO		= $this->db->query($sqlISHO)->result();
									foreach($resISHO as $rowISHO):
										$isHO		= $rowISHO->isHO;
										$syncPRJ	= $rowISHO->syncPRJ;
									endforeach;
									$dataPecah 	= explode("~",$syncPRJ);
									$jmD 		= count($dataPecah);
								
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue, 
																Base_Kredit2 = Base_Kredit2+$transacValue
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Debit
							endforeach;
						}
						else
						{
							$ACC_NUM	= "11051100"; // PERSEDIAAN BAHAN
							// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
								$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K' 
												AND Journal_Type = 'NTAX'";
								$resCGEJ	= $this->db->count_all($sqlCGEJ);
								
								if($resCGEJ == 0)
								{
										$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id,
														JournalD_Kredit, Base_Kredit, 
														COA_Kredit, CostCenter, curr_rate, isDirect, Journal_DK)
													VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue, 
														$transacValue,$transacValue, 'Default', 1, 0, 'K')";
										$this->db->query($sqlGEJDD);
								}
								else
								{
										$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Kredit = JournalD_Kredit+$transacValue,
															Base_Kredit = Base_Kredit+$transacValue, 
															COA_Kredit = COA_Kredit+$transacValue
														WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K' 
															AND Journal_Type = 'NTAX'";
										$this->db->query($sqlUpdCOAD);
								}
							// START : Update to COA - Debit
								/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue, 
													Base_Kredit2 = Base_Kredit2+$transacValue
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
								$this->db->query($sqlUpdCOAD); // OK*/
				
								$isHO			= 0;
								$syncPRJ		= '';
								$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resISHO		= $this->db->query($sqlISHO)->result();
								foreach($resISHO as $rowISHO):
									$isHO		= $rowISHO->isHO;
									$syncPRJ	= $rowISHO->syncPRJ;
								endforeach;
								$dataPecah 	= explode("~",$syncPRJ);
								$jmD 		= count($dataPecah);
							
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue, 
															Base_Kredit2 = Base_Kredit2+$transacValue
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
						}
					// END : 22-03-2018
			// ---------------- END : Pembuatan Journal Detail ----------------
		}
		elseif($TRANS_CATEG == 'PRJINV')	// PRINV = Project Invoice
		{
			// ---------------- START : Pembuatan Journal Detail ---------------- from PINV	
				$curr_rate = 1; // Default IDR ke IDR
				
				// ------------------------------- D E B I T -------------------------------
					// Journal sisi debit ditetapkan ke 1102010 Piutang Termyn Proyek (Owner)
				
					// START : 04-07-2018					
						$ACC_NUM	= $ACC_ID;
							
						// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
							$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
											AND Journal_Type = 'NTAX'";
							$resCGEJ	= $this->db->count_all($sqlCGEJ);
							if($resCGEJ == 0)
							{
									$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
													JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect,
													Journal_DK)
												VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue, 
													$transacValue, $transacValue, 'Default', 1, 0, 'D')";
									$this->db->query($sqlGEJDD);
							}
							else
							{
									$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Debet = JournalD_Debet+$transacValue,
														Base_Debet = Base_Debet+$transacValue,
														COA_Debet = COA_Debet+$transacValue
													WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D'
														AND Journal_Type = 'NTAX'";
									$this->db->query($sqlUpdCOAD);
							}
							
						// START : Update to COA - Debit
							/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
												Base_Debet2 = Base_Debet2+$transacValue
											WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
							$this->db->query($sqlUpdCOAD); // OK*/
				
							$isHO			= 0;
							$syncPRJ		= '';
							$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resISHO		= $this->db->query($sqlISHO)->result();
							foreach($resISHO as $rowISHO):
								$isHO		= $rowISHO->isHO;
								$syncPRJ	= $rowISHO->syncPRJ;
							endforeach;
							$dataPecah 	= explode("~",$syncPRJ);
							$jmD 		= count($dataPecah);
						
							if($jmD > 0)
							{
								$SYNC_PRJ	= '';
								for($i=0; $i < $jmD; $i++)
								{
									$SYNC_PRJ	= $dataPecah[$i];
									$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
														Base_Debet2 = Base_Debet2+$transacValue
													WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
									$this->db->query($sqlUpdCOA);
								}
							}
						// END : Update to COA - Debit
					// END : 04-07-2018
					
				// ------------------------------- K R E D I T -------------------------------
					// untuk sementara, jurnal ini hanya bertambah di sisi debit
			// ---------------- END : Pembuatan Journal Detail ----------------
		}
		elseif($TRANS_CATEG == 'PRJINV2')		// PINV = Purchase Invoice - KHUSUS PPn Masukan (DEBIT ONLY)
		{
    		$Notes 		= $parameters['Notes'];
			$SPLCATEG	= $data[1];
			// ---------------- START : Pembuatan Journal Detail ----------------		
				$curr_rate = 1; // Default IDR ke IDR
				
				// ------------------------------- K R E D I T -------------------------------				
					// START : 11-10-2018 - PPn Kelaran (Hard Code)
						$sqlCL_D	= "tbl_tax_ppn";	
						$resCL_D	= $this->db->count_all($sqlCL_D);
						if($resCL_D > 0)
						{
							$sqlL_D	= "SELECT TAXLA_LINKOUT FROM tbl_tax_ppn";
							$resL_D = $this->db->query($sqlL_D)->result();					
							foreach($resL_D as $rowL_D):
								$ACC_NUM	= $rowL_D->TAXLA_LINKOUT;
								$sqlGEJDD 	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id,
												JournalD_Kredit, Base_Kredit, COA_Kredit, CostCenter, curr_rate, isDirect, 
												Journal_DK, Other_Desc)
											VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue, $transacValue,
												$transacValue, 'Default', 1, 0, 'K', 'NOT_SET_LA')";
								$this->db->query($sqlGEJDD);
							endforeach;
						
							// START : Update to COA - Debit
								$isHO			= 0;
								$syncPRJ		= '';
								$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resISHO		= $this->db->query($sqlISHO)->result();
								foreach($resISHO as $rowISHO):
									$isHO		= $rowISHO->isHO;
									$syncPRJ	= $rowISHO->syncPRJ;
								endforeach;
								$dataPecah 	= explode("~",$syncPRJ);
								$jmD 		= count($dataPecah);
							
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue,
															Base_Kredit2 = Base_Kredit2+$transacValue
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
						}
					// END : 04-07-2018						
			// ---------------- END : Pembuatan Journal Detail ----------------
		}
		elseif($TRANS_CATEG == 'BR')		// BR = Bank Receipt
		{
			$PIUTANG_ACC	= $data[1];
			// ---------------- START : Pembuatan Journal Detail ---------------- from PINV	
				$curr_rate 		= 1; // Default IDR ke IDR
				$transacValueA	= $transacValue + $AMOUNT_PPN - $AMOUNT_PPh;
				
				// ------------------------------- D E B I T -------------------------------
					// Journal sisi debit di tentukan oleh Akun Bank yang dipilih
				
					// START : 09-07-2018
						$ACC_NUM	= $ACC_ID;	// Bank Account
						
						// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
						$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
										AND Journal_Type = 'NTAX'";
						$resCGEJ	= $this->db->count_all($sqlCGEJ);						
						if($resCGEJ == 0)
						{
								$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
												JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect,
												Journal_DK, Other_Desc)
											VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValueA,
												$transacValueA, $transacValueA, 'Default', 1, 0, 'D', 'NOT_SET_LA')";
								$this->db->query($sqlGEJDD);
						}
						else

						{
								$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Debet = JournalD_Debet+$transacValueA,
													Base_Debet = Base_Debet+$transacValueA, COA_Debet = COA_Debet+$transacValueA
												WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D'
													AND Journal_Type = 'NTAX'";
								$this->db->query($sqlUpdCOAD);
						}
						// START : Update to COA - Debit
							/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValueA,
												Base_Debet2 = Base_Debet2+$transacValueA
											WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
							$this->db->query($sqlUpdCOAD); // OK*/
				
							$isHO			= 0;
							$syncPRJ		= '';
							$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resISHO		= $this->db->query($sqlISHO)->result();
							foreach($resISHO as $rowISHO):
								$isHO		= $rowISHO->isHO;
								$syncPRJ	= $rowISHO->syncPRJ;
							endforeach;
							$dataPecah 	= explode("~",$syncPRJ);
							$jmD 		= count($dataPecah);
						
							if($jmD > 0)
							{
								$SYNC_PRJ	= '';
								for($i=0; $i < $jmD; $i++)
								{
									$SYNC_PRJ	= $dataPecah[$i];
									$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValueA,
														Base_Debet2 = Base_Debet2+$transacValueA
													WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
									$this->db->query($sqlUpdCOA);
								}
							}
						// END : Update to COA - Debit
					// END : 09-07-2018
					
				// ------------------------------- K R E D I T -------------------------------
					// Journal sisi kredit ditetapkan ke 1102010 Piutang Termyn Proyek (Owner)
					// sebagai journal pembalik saat Pembuatan Piutang
					
					$ACC_NUMK	= $PIUTANG_ACC; // Piutang Account
					$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
									AND Journal_Type = 'NTAX'";
					$resCGEJ	= $this->db->count_all($sqlCGEJ);					
					if($resCGEJ == 0)
					{
						// START : Save Journal Detail - Debit
							$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
											JournalD_Kredit, Base_Kredit, COA_Kredit, CostCenter, curr_rate, isDirect, Journal_DK, 
											Other_Desc)
										VALUES ('$JournalH_Code', '$ACC_NUMK', '$proj_Code', 'IDR', $transacValueA, $transacValueA, 
											$transacValueA, 'Default', 1, 0, 'K', 'NOT_SET_LA')";
							$this->db->query($sqlGEJDD);
						// END : Save Journal Detail - Debit
					}
					else
					{
						// START : UPDATE Journal Detail - Debit
							$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Kredit = JournalD_Kredit+$transacValueA,
												Base_Kredit = Base_Kredit+$transacValueA, COA_Kredit = COA_Kredit+$transacValueA
											WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K' AND Journal_Type = 'NTAX'";
							$this->db->query($sqlUpdCOAD);
						// END : UPDATE Journal Detail - Debit
					}
					
				// START : Update to COA - Debit
					/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValueA, 
										Base_Kredit2 = Base_Kredit2-$transacValueA
									WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUMK'";
					$this->db->query($sqlUpdCOAD); // OK*/
				
					$isHO			= 0;
					$syncPRJ		= '';
					$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
										WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUMK' LIMIT 1";
					$resISHO		= $this->db->query($sqlISHO)->result();
					foreach($resISHO as $rowISHO):
						$isHO		= $rowISHO->isHO;
						$syncPRJ	= $rowISHO->syncPRJ;
					endforeach;
					$dataPecah 	= explode("~",$syncPRJ);
					$jmD 		= count($dataPecah);
				
					if($jmD > 0)
					{
						$SYNC_PRJ	= '';
						for($i=0; $i < $jmD; $i++)
						{
							$SYNC_PRJ	= $dataPecah[$i];
							$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValueA, 
												Base_Kredit2 = Base_Kredit2-$transacValueA
											WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUMK'";
							$this->db->query($sqlUpdCOA);
						}
					}
				// END : Update to COA - Debit
			// ---------------- END : Pembuatan Journal Detail ----------------
		}
		elseif($TRANS_CATEG == 'OPN')		// OPN = Opname
		{
			$curr_rate = 1; // Default IDR ke IDR
			// START : UPDATE L/R
				$PRJCODE	= $proj_Code;
				$PERIODM	= date('m', strtotime($JournalH_Date));
				$PERIODY	= date('Y', strtotime($JournalH_Date));
				$ITM_GROUP 	= $parameters['ITM_GROUP'];
				$WO_CATEG 	= $parameters['WO_CATEG'];
				//$ITM_TYPE 	= $parameters['ITM_TYPE'];
				if($WO_CATEG == 'SUB')
				{
					$updLR	= "UPDATE tbl_profitloss SET BPP_SUBK_PLAN = BPP_SUBK_PLAN+$transacValue
								WHERE PRJCODE = '$PRJCODE'
									AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
				else
				{
					if($ITM_GROUP == 'M')
					{
						$updLR	= "UPDATE tbl_profitloss SET BPP_MTR_PLAN = BPP_MTR_PLAN+$transacValue 
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
					elseif($ITM_GROUP == 'U')
					{
						$updLR	= "UPDATE tbl_profitloss SET BPP_UPH_PLAN = BPP_UPH_PLAN+$transacValue 
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
					elseif($ITM_GROUP == 'SC')
					{
						$updLR	= "UPDATE tbl_profitloss SET BPP_SUBK_PLAN = BPP_SUBK_PLAN+$transacValue 
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
					elseif($ITM_GROUP == 'T')
					{
						$updLR	= "UPDATE tbl_profitloss SET BPP_ALAT_PLAN = BPP_ALAT_PLAN+$transacValue 
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
					elseif($ITM_GROUP == 'I')
					{
						$updLR	= "UPDATE tbl_profitloss SET BPP_OTH_PLAN = BPP_OTH_PLAN+$transacValue 
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
					elseif($ITM_GROUP == 'O')
					{
						$updLR	= "UPDATE tbl_profitloss SET BPP_OTH_PLAN = BPP_OTH_PLAN+$transacValue 
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
					elseif($ITM_GROUP == 'GE')
					{
						$updLR	= "UPDATE tbl_profitloss SET BPP_BAU_PLAN = BPP_BAU_PLAN+$transacValue 
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
				}
			// END : UPDATE L/R
				
				// ------------------------------- D E B I T -------------------------------
					// START : 04-07-2018
						// Cek Link Account pada tbl_item
						$sqlCL_D	= "tbl_item WHERE PRJCODE = '$proj_Code' AND ITM_CODE = '$Item_Code'";
						$resCL_D	= $this->db->count_all($sqlCL_D);
						if($resCL_D > 0)
						{
							$sqlL_D	= "SELECT ACC_ID_UM AS ACC_ID FROM tbl_item 
										WHERE PRJCODE = '$proj_Code' AND ITM_CODE = '$Item_Code'";
							$resL_D = $this->db->query($sqlL_D)->result();					
							foreach($resL_D as $rowL_D):
								$ACC_NUM	= $rowL_D->ACC_ID;
								if($ACC_NUM == '')
									$ACC_NUM	= "510110"; // BAHAN LAIN-LAIN
									
								// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
									$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
													AND Journal_Type = 'NTAX'";
									$resCGEJ	= $this->db->count_all($sqlCGEJ);
									if($resCGEJ == 0)
									{
											$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
															JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect,
															Journal_DK)
														VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue, 
															$transacValue, $transacValue, 'Default', 1, 0, 'D')";
											$this->db->query($sqlGEJDD);
									}
									else
									{
											$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Debet = JournalD_Debet+$transacValue,
																Base_Debet = Base_Debet+$transacValue,
																COA_Debet = COA_Debet+$transacValue
															WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D'
																AND Journal_Type = 'NTAX'";
											$this->db->query($sqlUpdCOAD);
									}
								// START : Update to COA - Debit
									/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
														Base_Debet2 = Base_Debet2+$transacValue
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
									$this->db->query($sqlUpdCOAD); // OK*/
					
									$isHO			= 0;
									$syncPRJ		= '';
									$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
									$resISHO		= $this->db->query($sqlISHO)->result();
									foreach($resISHO as $rowISHO):
										$isHO		= $rowISHO->isHO;
										$syncPRJ	= $rowISHO->syncPRJ;
									endforeach;
									$dataPecah 	= explode("~",$syncPRJ);
									$jmD 		= count($dataPecah);
								
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
																Base_Debet2 = Base_Debet2+$transacValue
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Debit
							endforeach;
						}
						else
						{
							$ACC_NUM	= "5203"; // UPAH M/E
							// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
								$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
												AND Journal_Type = 'NTAX'";
								$resCGEJ	= $this->db->count_all($sqlCGEJ);
								if($resCGEJ == 0)
								{
										$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
														JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect,
														Journal_DK)
													VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue, 
														$transacValue, $transacValue, 'Default', 1, 0, 'D')";
										$this->db->query($sqlGEJDD);
								}
								else
								{
										$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Debet = JournalD_Debet+$transacValue,
															Base_Debet = Base_Debet+$transacValue,
															COA_Debet = COA_Debet+$transacValue
														WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D'
															AND Journal_Type = 'NTAX'";
										$this->db->query($sqlUpdCOAD);
								}
								
							// START : Update to COA - Debit
								/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
													Base_Debet2 = Base_Debet2+$transacValue
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
								$this->db->query($sqlUpdCOAD); // OK*/
					
								$isHO			= 0;
								$syncPRJ		= '';
								$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resISHO		= $this->db->query($sqlISHO)->result();
								foreach($resISHO as $rowISHO):
									$isHO		= $rowISHO->isHO;
									$syncPRJ	= $rowISHO->syncPRJ;
								endforeach;
								$dataPecah 	= explode("~",$syncPRJ);
								$jmD 		= count($dataPecah);
							
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
															Base_Debet2 = Base_Debet2+$transacValue
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
						}
					// END : 04-07-2018
					
				// ------------------------------- K R E D I T -------------------------------
					// SISI KREDIT TIDAK ADA.
			// ---------------- END : Pembuatan Journal Detail ----------------
		}
		if($TRANS_CATEG == 'RET')			// RET = Return
		{
			$curr_rate = 1; // Default IDR ke IDR
			// START : UPDATE L/R
				$PRJCODE	= $proj_Code;
				$PERIODM	= date('m', strtotime($JournalH_Date));
				$PERIODY	= date('Y', strtotime($JournalH_Date));
				$ITM_GROUP 	= $parameters['ITM_GROUP'];
				$ITM_TYPE 	= $parameters['ITM_TYPE'];
				
				// MIN STOCK ON PROFIT LOSS
				if($ITM_GROUP == 'M')
				{
					// 1.ISMTRL, 2.ISRENT, 3.ISPART, 4.ISFUEL, 5.ISLUBTICANT, 6. ISFASTM, 7.ISWAGE
					if($ITM_TYPE == 1)
					{
						$updLR	= "UPDATE tbl_profitloss SET STOCK_MTR = STOCK_MTR-$transacValue 
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
					elseif($ITM_TYPE == 3 || $ITM_TYPE == 6)
					{
						$updLR	= "UPDATE tbl_profitloss SET STOCK_TOOLS = STOCK_TOOLS-$transacValue 
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
					elseif($ITM_TYPE == 4 || $ITM_TYPE == 5)
					{
						$updLR	= "UPDATE tbl_profitloss SET STOCK_BBM = STOCK_BBM-$transacValue
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
				}
				elseif($ITM_GROUP == 'T')
				{
					$updLR	= "UPDATE tbl_profitloss SET STOCK_TOOLS = STOCK_TOOLS-$transacValue 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
			// END : UPDATE L/R
			
			// ---------------- START : Pembuatan Journal Detail ----------------
				
				// ------------------------------- D E B I T -------------------------------
					
					// SEHARUSNYA PENGURSANGAN HUTANG
					
				// ------------------------------- K R E D I T -------------------------------
					// Hasil diskusi 22/03/18 : Journal sisi kredit ditetapkan ke 11051100 = Persediaan Bahan/Material sebagai kebalikan
					// saat pembuatan journal penerimaan
				
					// START : 04-07-2018
						// Cek Link Account untuk penerimaan di sisi Kredit
						$sqlCL_K	= "tbl_item WHERE PRJCODE = '$proj_Code' AND ITM_CODE = '$Item_Code'";
						$this->db->count_all($sqlCL_K);				
						$resCL_K	= $this->db->count_all($sqlCL_K);
						if($resCL_K > 0)
						{
							$sqlL_K	= "SELECT ACC_ID FROM tbl_item WHERE PRJCODE = '$proj_Code' AND ITM_CODE = '$Item_Code'";
							$resL_K = $this->db->query($sqlL_K)->result();					
							foreach($resL_K as $rowL_K):
								$ACC_NUM	= $rowL_K->ACC_ID;
								// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
									$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K' 
													AND Journal_Type = 'NTAX'";
									$resCGEJ	= $this->db->count_all($sqlCGEJ);
									
									if($resCGEJ == 0)
									{
										$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, 
														Currency_id,
														JournalD_Kredit, Base_Kredit, 
														COA_Kredit, CostCenter, curr_rate, isDirect, Journal_DK)
													VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue, 
														$transacValue,$transacValue, 'Default', 1, 0, 'K')";
										$this->db->query($sqlGEJDD);
									}
									else
									{
										$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET 
															JournalD_Kredit = JournalD_Kredit+$transacValue,
															Base_Kredit = Base_Kredit+$transacValue, 
															COA_Kredit = COA_Kredit+$transacValue
														WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K' 
															AND Journal_Type = 'NTAX'";
										$this->db->query($sqlUpdCOAD);
									}
								// START : Update to COA - Debit
									/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue, 
														Base_Kredit2 = Base_Kredit2+$transacValue
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
									$this->db->query($sqlUpdCOAD); // OK*/
					
									$isHO			= 0;
									$syncPRJ		= '';
									$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
									$resISHO		= $this->db->query($sqlISHO)->result();
									foreach($resISHO as $rowISHO):
										$isHO		= $rowISHO->isHO;
										$syncPRJ	= $rowISHO->syncPRJ;
									endforeach;
									$dataPecah 	= explode("~",$syncPRJ);
									$jmD 		= count($dataPecah);
								
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue, 
																Base_Kredit2 = Base_Kredit2+$transacValue
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Debit
							endforeach;
						}
						else
						{
							$ACC_NUM	= "11051100"; // PERSEDIAAN BAHAN
							// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
								$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K' 
												AND Journal_Type = 'NTAX'";
								$resCGEJ	= $this->db->count_all($sqlCGEJ);
								
								if($resCGEJ == 0)
								{
										$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id,
														JournalD_Kredit, Base_Kredit, 
														COA_Kredit, CostCenter, curr_rate, isDirect, Journal_DK)
													VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue, 
														$transacValue,$transacValue, 'Default', 1, 0, 'K')";
										$this->db->query($sqlGEJDD);
								}
								else
								{
										$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Kredit = JournalD_Kredit+$transacValue,
															Base_Kredit = Base_Kredit+$transacValue, 
															COA_Kredit = COA_Kredit+$transacValue
														WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K' 
															AND Journal_Type = 'NTAX'";
										$this->db->query($sqlUpdCOAD);
								}
							// START : Update to COA - Debit
								/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue, 
													Base_Kredit2 = Base_Kredit2+$transacValue
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
								$this->db->query($sqlUpdCOAD); // OK*/
				
								$isHO			= 0;
								$syncPRJ		= '';
								$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resISHO		= $this->db->query($sqlISHO)->result();
								foreach($resISHO as $rowISHO):
									$isHO		= $rowISHO->isHO;
									$syncPRJ	= $rowISHO->syncPRJ;
								endforeach;
								$dataPecah 	= explode("~",$syncPRJ);
								$jmD 		= count($dataPecah);
							
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue, 
															Base_Kredit2 = Base_Kredit2+$transacValue
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
						}
					// END : 22-03-2018
			// ---------------- END : Pembuatan Journal Detail ----------------
		}
		elseif($TRANS_CATEG == 'VOPN')		// VOPN = Reject Opname
		{
			$curr_rate = 1; // Default IDR ke IDR
			// START : UPDATE L/R
				$PRJCODE	= $proj_Code;
				$PERIODM	= date('m', strtotime($JournalH_Date));
				$PERIODY	= date('Y', strtotime($JournalH_Date));
				$ITM_GROUP 	= $parameters['ITM_GROUP'];
				$WO_CATEG 	= $parameters['WO_CATEG'];
				//$ITM_TYPE 	= $parameters['ITM_TYPE'];
				if($WO_CATEG == 'SUB')
				{
					$updLR	= "UPDATE tbl_profitloss SET BPP_SUBK_PLAN = BPP_SUBK_PLAN-$transacValue
								WHERE PRJCODE = '$PRJCODE'
									AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
				else
				{
					if($ITM_GROUP == 'M')
					{
						$updLR	= "UPDATE tbl_profitloss SET BPP_MTR_PLAN = BPP_MTR_PLAN-$transacValue 
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
					elseif($ITM_GROUP == 'U')
					{
						$updLR	= "UPDATE tbl_profitloss SET BPP_UPH_PLAN = BPP_UPH_PLAN-$transacValue 
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
					elseif($ITM_GROUP == 'SC')
					{
						$updLR	= "UPDATE tbl_profitloss SET BPP_SUBK_PLAN = BPP_SUBK_PLAN-$transacValue 
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
					elseif($ITM_GROUP == 'T')
					{
						$updLR	= "UPDATE tbl_profitloss SET BPP_ALAT_PLAN = BPP_ALAT_PLAN-$transacValue 
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
					elseif($ITM_GROUP == 'I')
					{
						$updLR	= "UPDATE tbl_profitloss SET BPP_OTH_PLAN = BPP_OTH_PLAN-$transacValue 
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
					elseif($ITM_GROUP == 'O')
					{
						$updLR	= "UPDATE tbl_profitloss SET BPP_OTH_PLAN = BPP_OTH_PLAN-$transacValue 
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
					elseif($ITM_GROUP == 'GE')
					{
						$updLR	= "UPDATE tbl_profitloss SET BPP_BAU_PLAN = BPP_BAU_PLAN-$transacValue 
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
				}
			// END : UPDATE L/R
				
				// ------------------------------- D E B I T -------------------------------
					// START : 04-07-2018
						// Cek Link Account pada tbl_item
						$sqlCL_D	= "tbl_item WHERE PRJCODE = '$proj_Code' AND ITM_CODE = '$Item_Code'";
						$resCL_D	= $this->db->count_all($sqlCL_D);
						if($resCL_D > 0)
						{
							$sqlL_D	= "SELECT ACC_ID_UM AS ACC_ID FROM tbl_item 
										WHERE PRJCODE = '$proj_Code' AND ITM_CODE = '$Item_Code'";
							$resL_D = $this->db->query($sqlL_D)->result();					
							foreach($resL_D as $rowL_D):
								$ACC_NUM	= $rowL_D->ACC_ID;
								if($ACC_NUM == '')
									$ACC_NUM	= "510110"; // BAHAN LAIN-LAIN
									
								// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
									$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K' 
													AND Journal_Type = 'NTAX'";
									$resCGEJ	= $this->db->count_all($sqlCGEJ);
									if($resCGEJ == 0)
									{
										$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, 
														Currency_id, JournalD_Kredit, Base_Kredit, COA_Kredit, CostCenter, 
														curr_rate, isDirect, Journal_DK)
													VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue, 
														$transacValue, $transacValue, 'Default', 1, 0, 'K')";
										$this->db->query($sqlGEJDD);
									}
									else
									{
										$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET 
															JournalD_Kredit = JournalD_Kredit+$transacValue,
															Base_Kredit = Base_Kredit+$transacValue,
															COA_Kredit = COA_Kredit+$transacValue
														WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
															AND Journal_Type = 'NTAX'";
										$this->db->query($sqlUpdCOAD);
									}
								// START : Update to COA - Debit
									/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
														Base_Debet2 = Base_Debet2+$transacValue
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
									$this->db->query($sqlUpdCOAD); // OK*/
					
									$isHO			= 0;
									$syncPRJ		= '';
									$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
									$resISHO		= $this->db->query($sqlISHO)->result();
									foreach($resISHO as $rowISHO):
										$isHO		= $rowISHO->isHO;
										$syncPRJ	= $rowISHO->syncPRJ;
									endforeach;
									$dataPecah 	= explode("~",$syncPRJ);
									$jmD 		= count($dataPecah);
								
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue,
																Base_Kredit2 = Base_Kredit2+$transacValue
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Debit
							endforeach;
						}
						else
						{
							$ACC_NUM	= "5203"; // UPAH M/E
							// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
								$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K' 
												AND Journal_Type = 'NTAX'";
								$resCGEJ	= $this->db->count_all($sqlCGEJ);
								if($resCGEJ == 0)
								{
										$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
														JournalD_Kredit, Base_Kredit, COA_Kredit, CostCenter, curr_rate, isDirect,
														Journal_DK)
													VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue, 
														$transacValue, $transacValue, 'Default', 1, 0, 'K')";
										$this->db->query($sqlGEJDD);
								}
								else
								{
										$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Kredit = JournalD_Kredit+$transacValue,
															Base_Kredit = Base_Kredit+$transacValue,
															COA_Kredit = COA_Kredit+$transacValue
														WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
															AND Journal_Type = 'NTAX'";
										$this->db->query($sqlUpdCOAD);
								}
								
							// START : Update to COA - Debit
								/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue,
													Base_Kredit2 = Base_Kredit2+$transacValue
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
								$this->db->query($sqlUpdCOAD); // OK*/
					
								$isHO			= 0;
								$syncPRJ		= '';
								$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resISHO		= $this->db->query($sqlISHO)->result();
								foreach($resISHO as $rowISHO):
									$isHO		= $rowISHO->isHO;
									$syncPRJ	= $rowISHO->syncPRJ;
								endforeach;
								$dataPecah 	= explode("~",$syncPRJ);
								$jmD 		= count($dataPecah);
							
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue,
															Base_Kredit2 = Base_Kredit2+$transacValue
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
						}
					// END : 04-07-2018
					
				// ------------------------------- K R E D I T -------------------------------
					// SISI KREDIT TIDAK ADA.
			// ---------------- END : Pembuatan Journal Detail ----------------
		}
	}
	
	function createITMHistMin($JournalH_Code, $parameters)
	{
		$JournalH_Code 		= $parameters['JournalH_Code'];
		$JournalType 		= $parameters['JournalType'];
		$JournalH_Date 		= $parameters['JournalH_Date'];
		$Company_ID			= $parameters['Company_ID'];
		$Currency_ID		= $parameters['Currency_ID'];
		$Source				= $parameters['Source'];
		$Emp_ID 			= $parameters['Emp_ID'];
		$LastUpdate 		= $parameters['LastUpdate'];
		$KursAm_tobase		= $parameters['KursAmount_tobase'];
		$Wh_id				= $parameters['WHCODE'];
		$REFNumb 			= $parameters['Reference_Number'];
		$RefType 			= $parameters['RefType'];
		$proj_Code 			= $parameters['PRJCODE'];
		$PRJCODE 			= $parameters['PRJCODE'];
		$JSource			= $parameters['JSource'];
		$TRANS_CATEG1		= $parameters['TRANS_CATEG'];
    	$Transaction_Date	= $parameters['JournalH_Date'];
    	$Item_Code 			= $parameters['ITM_CODE'];
    	$ACC_ID 			= $parameters['ACC_ID'];
    	$Qty_Min 			= $parameters['ITM_QTY'];
    	$Item_Price 		= $parameters['ITM_PRICE'];
    	$Item_Disc 			= $parameters['ITM_DISC'];
    	$TAXCODE1 			= $parameters['TAXCODE1'];
    	$TAXPRICE1 			= $parameters['TAXPRICE1'];
					
		$Unit_Price 		= $Item_Price;		
		$transacValue 		= ($Qty_Min * $Item_Price) - $Item_Disc;
		
		$data 				= explode("~" , $TRANS_CATEG1);
		$TRANS_CATEG		= $data[0];
		
		// PEREKAMAN JEJAK KE tbl_itemhistory				
			$sqlHist 		= "INSERT INTO tbl_itemhistory (JournalH_Code, proj_Code, Transaction_Date, Item_Code, Qty_Plus,
									Qty_Min, Transaction_Type, Transaction_Value, Company_ID, Currency_ID)
								VALUES ('$JournalH_Code', '$proj_Code', '$Transaction_Date', '$Item_Code', 0, $Qty_Min,
									'$JSource', $transacValue, '$Company_ID', '$Currency_ID')";
			$this->db->query($sqlHist);
		
		if($TRANS_CATEG == 'RET')
		{
			$sqlUPITM 	= "UPDATE tbl_item SET RET_VOLM = RET_VOLM + $Qty_Min, RET_AMOUNT = RET_AMOUNT + $transacValue
							WHERE PRJCODE = '$proj_Code' AND ITM_CODE = '$Item_Code'";
			$this->db->query($sqlUPITM);
		}
	}
}
?>