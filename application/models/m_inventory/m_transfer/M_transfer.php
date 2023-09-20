<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 28 Januari 2019
 * File Name	= M_transfer.php
 * Location		= -
*/

class M_transfer extends CI_Model
{
	public function __construct() // GOOD
	{
		parent::__construct();
		$this->load->database();
	}
	
	function get_AllDataC($PRJCODE, $search) // GOOD
	{
		$sql 	= "tbl_item_tsfh A
						LEFT JOIN tbl_mr_header B ON B.MR_NUM = A.ITMTSF_REFNO
					WHERE A.PRJCODE = '$PRJCODE'
						AND (A.ITMTSF_CODE LIKE '%$search%' ESCAPE '!' OR A.ITMTSF_DATE LIKE '%$search%' ESCAPE '!' 
						OR B.JO_CODE LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}

	function get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.ITMTSF_NUM, A.ITMTSF_CODE, A.ITMTSF_DATE, A.PRJCODE, A.ITMTSF_ORIGIN, A.ITMTSF_DEST, A.ITMTSF_REFNO,
							A.ITMTSF_NOTE, A.ITMTSF_STAT, A.STATDESC, A.STATCOL, A.CREATERNM, B.JO_CODE
						FROM tbl_item_tsfh A
							LEFT JOIN tbl_mr_header B ON B.MR_NUM = A.ITMTSF_REFNO
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.ITMTSF_CODE LIKE '%$search%' ESCAPE '!' OR A.ITMTSF_DATE LIKE '%$search%' ESCAPE '!' 
							OR B.JO_CODE LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.ITMTSF_NUM, A.ITMTSF_CODE, A.ITMTSF_DATE, A.PRJCODE, A.ITMTSF_ORIGIN, A.ITMTSF_DEST, A.ITMTSF_REFNO,
							A.ITMTSF_NOTE, A.ITMTSF_STAT, A.STATDESC, A.STATCOL, A.CREATERNM, B.JO_CODE
						FROM tbl_item_tsfh A
							LEFT JOIN tbl_mr_header B ON B.MR_NUM = A.ITMTSF_REFNO
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.ITMTSF_CODE LIKE '%$search%' ESCAPE '!' OR A.ITMTSF_DATE LIKE '%$search%' ESCAPE '!' 
							OR B.JO_CODE LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.ITMTSF_NUM, A.ITMTSF_CODE, A.ITMTSF_DATE, A.PRJCODE, A.ITMTSF_ORIGIN, A.ITMTSF_DEST, A.ITMTSF_REFNO,
							A.ITMTSF_NOTE, A.ITMTSF_STAT, A.STATDESC, A.STATCOL, A.CREATERNM, B.JO_CODE
						FROM tbl_item_tsfh A
							LEFT JOIN tbl_mr_header B ON B.MR_NUM = A.ITMTSF_REFNO
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.ITMTSF_CODE LIKE '%$search%' ESCAPE '!' OR A.ITMTSF_DATE LIKE '%$search%' ESCAPE '!' 
							OR B.JO_CODE LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.ITMTSF_NUM, A.ITMTSF_CODE, A.ITMTSF_DATE, A.PRJCODE, A.ITMTSF_ORIGIN, A.ITMTSF_DEST, A.ITMTSF_REFNO,
							A.ITMTSF_NOTE, A.ITMTSF_STAT, A.STATDESC, A.STATCOL, A.CREATERNM, B.JO_CODE
						FROM tbl_item_tsfh A
							LEFT JOIN tbl_mr_header B ON B.MR_NUM = A.ITMTSF_REFNO
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.ITMTSF_CODE LIKE '%$search%' ESCAPE '!' OR A.ITMTSF_DATE LIKE '%$search%' ESCAPE '!' 
							OR B.JO_CODE LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}

	function count_all_tsf($PRJCODE, $key) // GOOD
	{
		if($key == '')
		{
			$sql = "tbl_item_tsfh A
					INNER JOIN tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'";
		}
		else
		{
			$sql = "tbl_item_tsfh A
					INNER JOIN tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND (ITMTSF_CODE LIKE '%$key%' ESCAPE '!' OR ITMTSF_DATE LIKE '%$key%' ESCAPE '!' 
						OR ITMTSF_NOTE LIKE '%$key%' ESCAPE '!')";
		}
		return $this->db->count_all($sql);
	}
	
	function get_all_tsf($PRJCODE, $start, $end, $key) // GOOD
	{
		if($key == '')
		{
			$sql = "SELECT A.*,
						B.proj_Number, B.PRJCODE, B.PRJNAME
					FROM tbl_item_tsfh A
						INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE' LIMIT $start, $end";
		}
		else
		{
			$sql = "SELECT A.*,
						B.proj_Number, B.PRJCODE, B.PRJNAME
					FROM tbl_item_tsfh A
						INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND (ITMTSF_CODE LIKE '%$key%' ESCAPE '!' OR ITMTSF_DATE LIKE '%$key%' ESCAPE '!' 
						OR ITMTSF_NOTE LIKE '%$key%' ESCAPE '!') LIMIT $start, $end";
		}
		return $this->db->query($sql);
	}
	
	function getDataDocPat($MenuCode) // GOOD
	{
		$this->db->select('Pattern_Code,Pattern_Position,Pattern_YearAktive,Pattern_MonthAktive,Pattern_DateAktive,Pattern_Length,useYear,useMonth,useDate');
		$this->db->from('tbl_docpattern');
		$this->db->where('menu_code', $MenuCode);
		return $this->db->get();
	}
	
	function count_all_MTR($PRJCODE) // GOOD
	{
		$sql	= "tbl_mr_header WHERE PRJCODE = '$PRJCODE' AND MR_STAT = 3";
		return $this->db->count_all($sql);
	}
	
	function view_all_MTR($PRJCODE) // GOOD
	{
		$sql	= "SELECT A.MR_NUM, A.MR_CODE, A.MR_DATE, A.JO_NUM, A.JO_CODE, A.SO_NUM, A.SO_CODE, A.CCAL_NUM, 
						A.MR_AMOUNT, A.MR_NOTE, B.CUST_DESC
					FROM tbl_mr_header A
						INNER JOIN tbl_jo_header B ON A.JO_NUM = B.JO_NUM
							AND B.PRJCODE = '$PRJCODE'
					WHERE A.PRJCODE = '$PRJCODE' AND A.MR_STAT = 3";
		return $this->db->query($sql);
	}
	
	function count_all_prim($PRJCODE, $MRNM) // GOOD
	{
		$sql		= "tbl_mr_detail A
						INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
							AND B.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_CATEG != 'OTH' AND A.MR_NUM = '$MRNM'";	
		return $this->db->count_all($sql);
	}
	
	function view_all_prim($PRJCODE, $MRNM) // GOOD
	{
		$sql		= "SELECT DISTINCT '' AS JOBCODEDET, '' AS JOBCODEID, '' AS JOBPARENT, '' AS JOBCODE,
							A.PRJCODE, A.ITM_CODE, A.ITM_UNIT, A.MR_VOLM, A.MR_PRICE, 
							A.MR_TOTAL, A.MR_DESC AS ITM_DESC,
							A.IRM_VOLM, A.IRM_AMOUNT,
							B.ITM_TYPE, B.ITM_NAME, B.ITM_GROUP, B.ITM_CODE_H, B.ITM_CATEG
						FROM tbl_mr_detail A
							INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
								AND B.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_CATEG != 'OTH' AND A.MR_NUM = '$MRNM'";
		return $this->db->query($sql);
	}
	
	function count_allItemOth($PRJCODE, $MRNM) // GOOD
	{
		$sql		= "tbl_item WHERE PRJCODE = '$PRJCODE' AND STATUS = 1
							AND (ISPART = 1 OR ISFUEL = 1 OR ISLUBRIC = 1 OR ISFASTM = 1 OR ISRM = 1 OR ISWIP = 1 OR ISFG = 1)
							AND ITM_CODE NOT IN ( SELECT A.ITM_CODE FROM tbl_mr_detail A 
								WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_CATEG != 'OTH' AND A.MR_NUM = '$MRNM')";
		return $this->db->count_all($sql);
	}
	
	function viewAllItemOth($PRJCODE, $MRNM) // GOOD
	{
		$sql		= "SELECT DISTINCT '' AS JOBCODEDET, '' AS JOBCODEID, '' AS JOBPARENT, '' AS JOBCODE,
							PRJCODE, ITM_CODE, ITM_UNIT, 0 AS MR_VOLM, ITM_PRICE AS MR_PRICE, 0 AS MR_TOTAL, '' AS ITM_DESC,
							0 AS IRM_VOLM, 0 AS IRM_AMOUNT, ITM_TYPE, ITM_NAME,  ITM_GROUP, ITM_CODE_H,  ITM_CATEG
						FROM tbl_item WHERE PRJCODE = '$PRJCODE' AND STATUS = 1
							AND (ISPART = 1 OR ISFUEL = 1 OR ISLUBRIC = 1 OR ISFASTM = 1 OR ISRM = 1 OR ISWIP = 1 OR ISFG = 1)
							AND ITM_CODE NOT IN ( SELECT A.ITM_CODE FROM tbl_mr_detail A 
								WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_CATEG != 'OTH' AND A.MR_NUM = '$MRNM')";
		return $this->db->query($sql);
	}
	
	function add($insASEXPH) // GOOD
	{
		$this->db->insert('tbl_item_tsfh', $insASEXPH);
	}
	
	function update($ITMTSF_NUM, $updASTSFH) // GOOD
	{
		$this->db->where('ITMTSF_NUM', $ITMTSF_NUM);
		$this->db->update('tbl_item_tsfh', $updASTSFH);
	}
	
	function get_ITMTSF_by_number($ITMTSF_NUM) // GOOD
	{
		$sql = "SELECT *
				FROM tbl_item_tsfh
				WHERE ITMTSF_NUM = '$ITMTSF_NUM' LIMIT 1";
		return $this->db->query($sql);
	}
	
	function deleteDetail($ITMTSF_NUM) // GOOD
	{
		$this->db->where('ITMTSF_NUM', $ITMTSF_NUM);
		$this->db->delete('tbl_item_tsfd');
	}
	
	function count_all_item($PRJCODE, $JOBCODE) // G
	{
		$sql	= "tbl_item WHERE ITM_GROUP IN ('T','M') AND PRJCODE = '$PRJCODE' AND STATUS = 1 AND ITM_VOLM > 0";
		return $this->db->count_all($sql);
	}
	
	function get_all_item($PRJCODE, $JOBCODE) // G
	{
		$sql	= "SELECT PRJCODE, ITM_CODE, ITM_CODE_H, ITM_GROUP, ITM_CATEG, JOBCODEID, ITM_NAME, ITM_DESC, ITM_UNIT, ITM_VOLM, ITM_PRICE
					FROM tbl_item WHERE ITM_GROUP IN ('T','M') 
						AND PRJCODE = '$PRJCODE' AND STATUS = 1 AND ITM_VOLM > 0";
		return $this->db->query($sql);
	}
	
	function count_all_tsfInb($PRJCODE, $key, $DefEmp_ID) // G
	{
		if($key == '')
		{
			$sql = "tbl_item_tsfh A
						INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND ITMTSF_STAT IN (2,7)";
		}
		else
		{
			$sql = "tbl_item_tsfh A
						INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND ITMTSF_STAT IN (2,7)
						AND (ITMTSF_CODE LIKE '%$key%' ESCAPE '!' OR ITMTSF_DATE LIKE '%$key%' ESCAPE '!' 
						OR ITMTSF_NOTE LIKE '%$key%' ESCAPE '!')";
		}
		return $this->db->count_all($sql);
	}
	
	function get_all_tsfInb($PRJCODE, $start, $end, $key, $DefEmp_ID) // G
	{
		if($key == '')
		{
			$sql = "SELECT A.*,
						B.proj_Number, B.PRJCODE, B.PRJNAME
					FROM tbl_item_tsfh A
						INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND ITMTSF_STAT IN (2,7) LIMIT $start, $end";
		}
		else
		{
			$sql = "SELECT A.*,
						B.proj_Number, B.PRJCODE, B.PRJNAME
					FROM tbl_item_tsfh A
						INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND ITMTSF_STAT IN (2,7)
						AND (ITMTSF_CODE LIKE '%$key%' ESCAPE '!' OR ITMTSF_DATE LIKE '%$key%' ESCAPE '!' 
						OR ITMTSF_NOTE LIKE '%$key%' ESCAPE '!') LIMIT $start, $end";
		}
		return $this->db->query($sql);
	}
	
	function createITM($parameters) // G
	{
		$ITMTSF_NUM		= $parameters['ITMTSF_NUM'];
		$PRJCODE 		= $parameters['PRJCODE'];
   		$ITMTSF_ORIGIN 	= $parameters['ITMTSF_ORIGIN'];
		$PRJCODE_DEST 	= $parameters['PRJCODE_DEST'];
		$ITMTSF_DEST 	= $parameters['ITMTSF_DEST'];
		$ITMTSF_VOLM	= $parameters['ITM_QTY'];
		$ITM_CODE 		= $parameters['ITM_CODE'];
		$ITM_NAME 		= $parameters['ITM_NAME'];
		$ITM_UNIT 		= $parameters['ITM_UNIT'];
		$ITM_GROUP 		= $parameters['ITM_GROUP'];
		$ITM_QTY 		= $parameters['ITM_QTY'];
		$ITM_PRICE 		= $parameters['ITM_PRICE'];
		$ITM_TOTALP		= $ITM_QTY * $ITM_PRICE;

		$sqlITMC		= "tbl_item WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE_DEST'";
		$resITMC		= $this->db->count_all($sqlITMC);
		$LASTNO			= $resITMC + 1;

		if($resITMC == 0)
		{
			$sqlGetITM	= "SELECT ITM_CODE, ITM_CODE_H, JOBCODEID, ITM_GROUP, ITM_CATEG, ITM_CLASS, ITM_NAME, ITM_DESC, ITM_TYPE,
								ITM_UNIT, UMCODE, ITM_CURRENCY, ITM_VOLM, ITM_PRICE, ITM_TOTALP, ITM_LASTP, ACC_ID, ACC_ID_UM, ISMTRL,
								ISRENT, ISPART, ISFUEL, ISLUBRIC, ISFASTM, ISWAGE, ISRM, ISWIP, ISFG, ISCOST, ITM_KIND, ISMAJOR, ISOUTB
							FROM tbl_item
							WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
			$resGetITM	= $this->db->query($sqlGetITM)->result();
			foreach($resGetITM as $rowITM) :
				$ITM_CODE 	= $rowITM->ITM_CODE;
				$ITM_CODE_H	= $rowITM->ITM_CODE_H;
				$JOBCODEID	= $rowITM->JOBCODEID;
				$ITM_GROUP	= $rowITM->ITM_GROUP;
				$ITM_CATEG	= $rowITM->ITM_CATEG;
				$ITM_CLASS	= $rowITM->ITM_CLASS;
				$ITM_NAME	= $rowITM->ITM_NAME;
				$ITM_DESC	= $rowITM->ITM_DESC;
				$ITM_TYPE	= $rowITM->ITM_TYPE;
				$ITM_UNIT	= $rowITM->ITM_UNIT;
				$UMCODE		= $rowITM->UMCODE;
				$ITM_CURRENCY	= $rowITM->ITM_CURRENCY;
				$ITM_VOLM	= $rowITM->ITM_VOLM;
				$ITM_PRICE	= $rowITM->ITM_PRICE;
				$ITM_TOTALP	= $rowITM->ITM_TOTALP;
				$ITM_LASTP	= $rowITM->ITM_LASTP;
				$ACC_ID 	= $rowITM->ACC_ID;
				$ACC_ID_UM	= $rowITM->ACC_ID_UM;
				$ISMTRL		= $rowITM->ISMTRL;
				$ISRENT		= $rowITM->ISRENT;
				$ISPART		= $rowITM->ISPART;
				$ISFUEL		= $rowITM->ISFUEL;
				$ISLUBRIC	= $rowITM->ISLUBRIC;
				$ISFASTM	= $rowITM->ISFASTM;
				$ISWAGE		= $rowITM->ISWAGE;
				$ISRM		= $rowITM->ISRM;
				$ISWIP		= $rowITM->ISWIP;
				$ISFG		= $rowITM->ISFG;
				$ISCOST		= $rowITM->ISCOST;
				$ITM_KIND	= $rowITM->ITM_KIND;
				$ISMAJOR	= $rowITM->ISMAJOR;
				$ISOUTB		= $rowITM->ISOUTB;
				
				// INSERT INTO DESTINATION
				$sqlInsITM	= "INSERT INTO tbl_item (PRJCODE, ITM_CODE, ITM_CODE_H, JOBCODEID, ITM_GROUP, ITM_CATEG, ITM_CLASS, 
								ITM_NAME, ITM_DESC, ITM_TYPE, ITM_UNIT, UMCODE, ITM_CURRENCY, ITM_VOLM, ITM_PRICE, 
								ITM_TOTALP, ITM_LASTP, ACC_ID, ACC_ID_UM, ISMTRL, ISRENT, ISPART, ISFUEL, ISLUBRIC, 
								ISFASTM, ISWAGE, ISRM, ISWIP, ISFG, ISCOST, ITM_KIND, ISMAJOR, ISOUTB, LAST_TRXNO, 
								LASTNO)
								VALUES ('$PRJCODE_DEST', '$ITM_CODE', '$ITM_CODE_H', '$JOBCODEID', '$ITM_GROUP', '$ITM_CATEG', '$ITM_CLASS', 
								'$ITM_NAME', '$ITM_DESC', '$ITM_TYPE', '$ITM_UNIT', '$UMCODE', '$ITM_CURRENCY', 0, '$ITM_PRICE', 
								0, '$ITM_LASTP', '$ACC_ID', '$ACC_ID_UM', '$ISMTRL', '$ISRENT', '$ISPART', '$ISFUEL', '$ISLUBRIC', 
								'$ISFASTM', '$ISWAGE', '$ISRM', '$ISWIP', '$ISFG', '$ISCOST', '$ITM_KIND', '$ISMAJOR', '$ISOUTB', 
								'$ITMTSF_NUM', '$LASTNO')";
				$this->db->query($sqlInsITM);
			endforeach;
		}
		
		// START : UPDATE WH_QTY IN ORIGIN
			// 1. JIKA BEDA PROYEK, MAKA KURANGI DI PROYEK ASAL ('$PRJCODE')
				/*if($PRJCODE_DEST != $PRJCODE) // HOLD DULU KARENA INI AKAN TERJADI JIKA BEDA COMPANY
				{
					$sqlUpdORIG	= "UPDATE tbl_item SET ITM_VOLM = ITM_VOLM - $ITMTSF_VOLM, ITM_OUT = ITM_OUT + $ITMTSF_VOLM,
										ITM_OUTP = ITM_OUTP + $ITM_TOTALP
									WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
					$this->db->query($sqlUpdORIG);
				}*/
			
			// 2. KURANGI DI WAREHOUSE PROYEK ASAL ('$PRJCODE')
				// CATATA: TERNYATA KODE GUDANG YG DIPAKAI SAAT IR, ADALAH KODE UNIK
				$sqlGetWH	= "SELECT WH_NUM FROM tbl_warehouse WHERE WH_CODE = '$ITMTSF_ORIGIN'";
				$resetWH	= $this->db->query($sqlGetWH)->result();
				foreach($resetWH as $rowWH) :
					$WH_NUM 	= $rowWH->WH_NUM;
				endforeach;

				$sqlUpWHORI	= "UPDATE tbl_item_whqty SET ITM_VOLM = ITM_VOLM - $ITM_QTY, ITM_PRICE = $ITM_PRICE,
									ITM_OUT = ITM_OUT + $ITM_QTY, ITM_OUTP = ITM_OUTP + $ITM_TOTALP
								WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' AND WH_CODE = '$WH_NUM'";
				$this->db->query($sqlUpWHORI);
		// END : UPDATE WH_QTY IN ORIGIN
		
			
		// START : UPDATE WH_QTY IN DESTINATION	
			// 1. JIKA BEDA PROYEK, MAKA TAMBAHKAN DI PROYEK TUJUAN ('$PRJCODE_DEST')
				/*if($PRJCODE_DEST != $PRJCODE)  // HOLD DULU KARENA INI AKAN TERJADI JIKA BEDA COMPANY
				{
					$sqlUpdDEST	= "UPDATE tbl_item SET ITM_VOLM = ITM_VOLM + $ITMTSF_VOLM, ITM_IN = ITM_IN + $ITMTSF_VOLM,
										ITM_INP = ITM_INP + $ITM_TOTALP
									WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE_DEST'";
					$this->db->query($sqlUpdDEST);
				}*/
			
			// 2. TAMBAHKAN DI PROYEK TUJUAN ('$PRJCODE_DEST')
				// CATATA: TERNYATA KODE GUDANG YG DIPAKAI SAAT IR, ADALAH KODE UNIK
				$WHDEST 	= "";
				$sqlGetWH	= "SELECT WH_NUM FROM tbl_warehouse WHERE WH_CODE = '$ITMTSF_DEST'";
				$resetWH	= $this->db->query($sqlGetWH)->result();
				foreach($resetWH as $rowWH) :
					$WHDEST = $rowWH->WH_NUM;
				endforeach;

				$sqlWHC	= "tbl_item_whqty WHERE ITM_CODE = '$ITM_CODE' AND WH_CODE = '$WHDEST'";
				$resWHC	= $this->db->count_all($sqlWHC);
			
				if($resWHC > 0)
				{
					/*$sqlUpWH	= "UPDATE tbl_item_whqty SET ITM_VOLM = ITM_VOLM + $ITM_QTY, ITM_PRICE = $ITM_PRICE,
										ITM_IN = ITM_IN + $ITM_QTY, ITM_INP = ITM_INP + $ITM_TOTALP
									WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE_DEST' AND WH_CODE = '$ITMTSF_DEST'";*/
					$sqlUpWH	= "UPDATE tbl_item_whqty SET ITM_VOLM = ITM_VOLM + $ITM_QTY, ITM_PRICE = $ITM_PRICE,
										ITM_IN = ITM_IN + $ITM_QTY, ITM_INP = ITM_INP + $ITM_TOTALP
									WHERE ITM_CODE = '$ITM_CODE' AND WH_CODE = '$WHDEST'";
					$this->db->query($sqlUpWH);
				}
				else
				{
					$sqlInsWH	= "INSERT INTO tbl_item_whqty (PRJCODE, WH_CODE, ITM_CODE, ITM_GROUP, ITM_NAME, ITM_UNIT,
										ITM_VOLM, ITM_PRICE, ITM_IN, ITM_INP)
										VALUES ('$PRJCODE_DEST', '$WHDEST', '$ITM_CODE', '$ITM_GROUP', '$ITM_NAME', '$ITM_UNIT',
										$ITM_QTY, $ITM_PRICE, $ITM_QTY, $ITM_TOTALP)";
					$this->db->query($sqlInsWH);
				}
		// END : UPDATE WH_QTY IN DESTINATION
	}

	function moveBackITM($parameters) // G
	{
		$ITMTSF_NUM			= $parameters['ITMTSF_NUM'];
		$PRJCODE 				= $parameters['PRJCODE'];
    $ITMTSF_ORIGIN 	= $parameters['ITMTSF_ORIGIN'];
		$PRJCODE_DEST 	= $parameters['PRJCODE_DEST'];
		$ITMTSF_DEST 	= $parameters['ITMTSF_DEST'];
		$ITMTSF_VOLM	= $parameters['ITMTSF_VOLM'];
		$ITM_CODE 		= $parameters['ITM_CODE'];
		$ITM_NAME 		= $parameters['ITM_NAME'];
		$ITM_UNIT 		= $parameters['ITM_UNIT'];
		$ITM_GROUP 		= $parameters['ITM_GROUP'];
		$ITM_QTY 		= $parameters['ITM_QTY'];
		$ITM_PRICE 		= $parameters['ITM_PRICE'];
		$ITM_TOTALP		= $ITM_QTY * $ITM_PRICE;

		$sqlITMC		= "tbl_item WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
		$resITMC		= $this->db->count_all($sqlITMC);
		$LASTNO			= $resITMC + 1;

		if($resITMC == 0)
		{
			$sqlGetITM	= "SELECT ITM_CODE, ITM_CODE_H, JOBCODEID, ITM_GROUP, ITM_CATEG, ITM_CLASS, ITM_NAME, ITM_DESC, ITM_TYPE,
								ITM_UNIT, UMCODE, ITM_CURRENCY, ITM_VOLM, ITM_PRICE, ITM_TOTALP, ITM_LASTP, ACC_ID, ACC_ID_UM, ISMTRL,
								ISRENT, ISPART, ISFUEL, ISLUBRIC, ISFASTM, ISWAGE, ISRM, ISWIP, ISFG, ISCOST, ITM_KIND, ISMAJOR, ISOUTB
							FROM tbl_item
							WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE_DEST'";
			$resGetITM	= $this->db->query($sqlGetITM)->result();
			foreach($resGetITM as $rowITM) :
				$ITM_CODE 	= $rowITM->ITM_CODE;
				$ITM_CODE_H	= $rowITM->ITM_CODE_H;
				$JOBCODEID	= $rowITM->JOBCODEID;
				$ITM_GROUP	= $rowITM->ITM_GROUP;
				$ITM_CATEG	= $rowITM->ITM_CATEG;
				$ITM_CLASS	= $rowITM->ITM_CLASS;
				$ITM_NAME	= $rowITM->ITM_NAME;
				$ITM_DESC	= $rowITM->ITM_DESC;
				$ITM_TYPE	= $rowITM->ITM_TYPE;
				$ITM_UNIT	= $rowITM->ITM_UNIT;
				$UMCODE		= $rowITM->UMCODE;
				$ITM_CURRENCY	= $rowITM->ITM_CURRENCY;
				$ITM_VOLM	= $rowITM->ITM_VOLM;
				$ITM_PRICE	= $rowITM->ITM_PRICE;
				$ITM_TOTALP	= $rowITM->ITM_TOTALP;
				$ITM_LASTP	= $rowITM->ITM_LASTP;
				$ACC_ID 	= $rowITM->ACC_ID;
				$ACC_ID_UM	= $rowITM->ACC_ID_UM;
				$ISMTRL		= $rowITM->ISMTRL;
				$ISRENT		= $rowITM->ISRENT;
				$ISPART		= $rowITM->ISPART;
				$ISFUEL		= $rowITM->ISFUEL;
				$ISLUBRIC	= $rowITM->ISLUBRIC;
				$ISFASTM	= $rowITM->ISFASTM;
				$ISWAGE		= $rowITM->ISWAGE;
				$ISRM		= $rowITM->ISRM;
				$ISWIP		= $rowITM->ISWIP;
				$ISFG		= $rowITM->ISFG;
				$ISCOST		= $rowITM->ISCOST;
				$ITM_KIND	= $rowITM->ITM_KIND;
				$ISMAJOR	= $rowITM->ISMAJOR;
				$ISOUTB		= $rowITM->ISOUTB;

				// INSERT INTO DESTINATION
				$sqlInsITM	= "INSERT INTO tbl_item (PRJCODE, ITM_CODE, ITM_CODE_H, JOBCODEID, ITM_GROUP, ITM_CATEG, ITM_CLASS,
								ITM_NAME, ITM_DESC, ITM_TYPE, ITM_UNIT, UMCODE, ITM_CURRENCY, ITM_VOLM, ITM_PRICE,
								ITM_TOTALP, ITM_LASTP, ACC_ID, ACC_ID_UM, ISMTRL, ISRENT, ISPART, ISFUEL, ISLUBRIC,
								ISFASTM, ISWAGE, ISRM, ISWIP, ISFG, ISCOST, ITM_KIND, ISMAJOR, ISOUTB, LAST_TRXNO,
								LASTNO)
								VALUES ('$PRJCODE', '$ITM_CODE', '$ITM_CODE_H', '$JOBCODEID', '$ITM_GROUP', '$ITM_CATEG', '$ITM_CLASS',
								'$ITM_NAME', '$ITM_DESC', '$ITM_TYPE', '$ITM_UNIT', '$UMCODE', '$ITM_CURRENCY', 0, '$ITM_PRICE',
								0, '$ITM_LASTP', '$ACC_ID', '$ACC_ID_UM', '$ISMTRL', '$ISRENT', '$ISPART', '$ISFUEL', '$ISLUBRIC',
								'$ISFASTM', '$ISWAGE', '$ISRM', '$ISWIP', '$ISFG', '$ISCOST', '$ITM_KIND', '$ISMAJOR', '$ISOUTB',
								'$ITMTSF_NUM', '$LASTNO')";
				$this->db->query($sqlInsITM);
			endforeach;
		}

		// START : UPDATE WH_QTY IN ORIGIN
			// 1. JIKA BEDA PROYEK, MAKA KURANGI DI PROYEK ASAL ('$PRJCODE')
				if($PRJCODE_DEST != $PRJCODE)
				{
					$sqlUpdORIG	= "UPDATE tbl_item SET ITM_VOLM = ITM_VOLM - $ITMTSF_VOLM, ITM_OUT = ITM_OUT + $ITMTSF_VOLM,
										ITM_OUTP = ITM_OUTP + $ITM_TOTALP
									WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE_DEST'";
					$this->db->query($sqlUpdORIG);
				}

			// 2. KURANGI DI WAREHOUSE PROYEK ASAL ('$PRJCODE')
				$sqlUpWHORI	= "UPDATE tbl_item_whqty SET ITM_VOLM = ITM_VOLM - $ITM_QTY, ITM_PRICE = $ITM_PRICE,
									ITM_OUT = ITM_OUT + $ITM_QTY, ITM_OUTP = ITM_OUTP + $ITM_TOTALP
								WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE_DEST' AND WH_CODE = '$ITMTSF_DEST'";
				$this->db->query($sqlUpWHORI);
		// END : UPDATE WH_QTY IN ORIGIN


		// START : UPDATE WH_QTY IN DESTINATION
			// 1. JIKA BEDA PROYEK, MAKA TAMBAHKAN DI PROYEK TUJUAN ('$PRJCODE_DEST')
				if($PRJCODE_DEST != $PRJCODE)
				{
					$sqlUpdDEST	= "UPDATE tbl_item SET ITM_VOLM = ITM_VOLM + $ITMTSF_VOLM, ITM_IN = ITM_IN + $ITMTSF_VOLM,
										ITM_INP = ITM_INP + $ITM_TOTALP
									WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
					$this->db->query($sqlUpdDEST);
				}

			// 2. TAMBAHKAN DI PROYEK TUJUAN ('$PRJCODE_DEST')
				$sqlWHC	= "tbl_item_whqty WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' AND WH_CODE = '$ITMTSF_ORIGIN'";
				$resWHC	= $this->db->count_all($sqlWHC);

				if($resWHC > 0)
				{
					$sqlUpWH	= "UPDATE tbl_item_whqty SET ITM_VOLM = ITM_VOLM + $ITM_QTY, ITM_PRICE = $ITM_PRICE,
										ITM_IN = ITM_IN + $ITM_QTY, ITM_INP = ITM_INP + $ITM_TOTALP
									WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' AND WH_CODE = '$ITMTSF_ORIGIN'";
					$this->db->query($sqlUpWH);
				}
				else
				{
					$sqlInsWH	= "INSERT INTO tbl_item_whqty (PRJCODE, WH_CODE, ITM_CODE, ITM_GROUP, ITM_NAME, ITM_UNIT,
										ITM_VOLM, ITM_PRICE, ITM_IN, ITM_INP)
										VALUES ('$PRJCODE', '$ITMTSF_ORIGIN', '$ITM_CODE', '$ITM_GROUP', '$ITM_NAME', '$ITM_UNIT',
										$ITM_QTY, $ITM_PRICE, $ITM_QTY, $ITM_TOTALP)";
					$this->db->query($sqlInsWH);
				}
		// END : UPDATE WH_QTY IN DESTINATION
	}

	function updateITM($ITMTSF_NUM, $ITM_CODE, $PRJCODE, $PRJCODE_DEST, $ITMTSF_VOLM) // G
	{
		$sqlUpdITM1	= "UPDATE tbl_item SET ITM_VOLM = ITM_VOLM + $ITMTSF_VOLM
						WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE_DEST";
		$this->db->query($sqlUpdITM1);
		
		$sqlUpdITM2	= "UPDATE tbl_item SET ITM_VOLM = ITM_VOLM - $ITMTSF_VOLM
						WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE";
		$this->db->query($sqlUpdITM2);
	}
	
	function updateLP($AS_CODE, $updLASTPOS) // OK
	{
		$this->db->where('AS_CODE', $AS_CODE);
		$this->db->update('tbl_asset_list', $updLASTPOS);
	}
	
	function updateJobDet($PR_NUM, $PRJCODE) // OK
	{				
		$sqlGetPR	= "SELECT PR_NUM, JOBCODEDET, JOBCODEID, ITM_CODE, PR_VOLM, PR_PRICE
						FROM tbl_pr_detail
						WHERE PR_NUM = '$PR_NUM' AND PRJCODE = '$PRJCODE'";
		$resGetPR	= $this->db->query($sqlGetPR)->result();
		foreach($resGetPR as $rowRP) :
			$PR_NUM 	= $rowRP->PR_NUM;
			$JOBCODEDET	= $rowRP->JOBCODEDET;
			$JOBCODEID	= $rowRP->JOBCODEID;
			$ITM_CODE	= $rowRP->ITM_CODE;
			$PR_VOLM	= $rowRP->PR_VOLM;
			$PR_PRICE	= $rowRP->PR_PRICE;
			$PR_TOTAMN	= $PR_VOLM * $PR_PRICE;
			
			// UPDATE JOBDETAIL
			$REQ_VOLM	= 0;
			$REQ_AMOUNT	= 0;
			$sqlGetJD		= "SELECT REQ_VOLM, REQ_AMOUNT FROM tbl_joblist_detail
								WHERE JOBCODEDET = '$JOBCODEDET' AND JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";
			$resGetJD		= $this->db->query($sqlGetJD)->result();
			foreach($resGetJD as $rowJD) :
				$REQ_VOLM 	= $rowJD->REQ_VOLM;
				$REQ_AMOUNT	= $rowJD->REQ_AMOUNT;
			endforeach;
			if($REQ_VOLM == '')
				$REQ_VOLM = 0;
			if($REQ_AMOUNT == '')
				$REQ_AMOUNT = 0;
				
			$totREQQty	= $REQ_VOLM + $PR_VOLM;
			$totREQAmn	= $REQ_AMOUNT + $PR_TOTAMN;
			$sqlUpd		= "UPDATE tbl_joblist_detail SET REQ_VOLM = $totREQQty, REQ_AMOUNT = $totREQAmn
							WHERE JOBCODEDET = '$JOBCODEDET' AND JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";
			$this->db->query($sqlUpd);
			
			// UPDATE TBL_ITEM
			$PR_VOLM1		= 0;
			$PR_AMOUNT1		= 0;
			$sqlGetJD1		= "SELECT PR_VOLM, PR_AMOUNT FROM tbl_item WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$resGetJD1		= $this->db->query($sqlGetJD1)->result();
			foreach($resGetJD1 as $rowJD1) :
				$PR_VOLM1 	= $rowJD1->PR_VOLM;
				$PR_AMOUNT1	= $rowJD1->PR_AMOUNT;
			endforeach;
			if($PR_VOLM1 == '')
				$PR_VOLM1 = 0;
			if($PR_AMOUNT1 == '')
				$PR_AMOUNT1 = 0;
				
			$totPRQty	= $PR_VOLM1 + $PR_VOLM;
			$totPRAmn	= $PR_AMOUNT1 + $PR_TOTAMN;
			$sqlUpd2	= "UPDATE tbl_item SET PR_VOLM = $totPRQty, PR_AMOUNT = $totPRAmn WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sqlUpd2);
		endforeach;
	}
	
	function count_all_num_rowsVend() // USED
	{
		return $this->db->count_all('tbl_supplier');
	}
	
	function viewvendor() // USED
	{
		$sql = "SELECT SPLCODE, SPLDESC, SPLADD1
				FROM tbl_supplier
				ORDER BY SPLDESC ASC";
		return $this->db->query($sql);
	}
	
	function count_all_num_rowsDept()
	{
		return $this->db->count_all('tdepartment');
	}
	
	function viewDepartment()
	{
		$this->db->select('Dept_ID, Dept_Name');
		$this->db->from('tdepartment');
		$this->db->order_by('Dept_Name', 'asc');
		return $this->db->get();
	}
	
	function count_all_num_rowsEmpDept()
	{
		return $this->db->count_all('tbl_employee');
	}
	
	function viewEmployeeDept()
	{
		$this->db->select('Emp_ID, First_name, Middle_Name, Last_Name');
		$this->db->from('tbl_employee');
		$this->db->order_by('First_name', 'asc');
		return $this->db->get();
	}
	
	function count_all_num_rowsAllPR()
	{
		return $this->db->count_all('TPReq_Header');
	}
	
	function viewAllPR()
	{				
		$sql = "SELECT A.MR_Number, A.MR_Date, A.Vend_Code, A.PR_EmpID, A.isAsset, B.First_Name, B.Middle_Name, B.Last_Name, C.Vend_Name, C.Vend_Address, D.Dept_Name
				FROM tproject_mrheader A
				INNER JOIN  tbl_employee B ON A.PR_EmpID = B.Emp_ID
				INNER JOIN 	tvendor C ON A.Vend_Code = C.Vend_Code
				INNER JOIN	tdepartment D ON A.PR_DepID = D.Dept_ID
				ORDER BY A.MR_Number";
		return $this->db->query($sql);
	}
	
	function update_inbox($SPPNUM, $projMatReqH) // USED
	{
		$this->db->where('SPPNUM', $SPPNUM);
		$this->db->update('tbl_item_tsfh', $projMatReqH);
	}
	
	function delete($MR_Number)
	{
		$this->db->where('MR_Number', $MR_Number);
		$this->db->delete($this->table);
	}
	
	// remarks by DH on March, 6 2014
	/*function viewAllItem()
	{
		$this->db->select('Item_Code, Item_Name, Item_Qty, Unit_Type_ID');
		$this->db->from('titem');
		$this->db->order_by('Item_Code', 'asc');
		return $this->db->get();
	}*/
	
	// add by DH on March, 6 2014
	function viewAllItem()
	{
		$sql = "SELECT A.Item_Code, A.serialNumber, A.Item_Name, A.Item_Qty, A.Item_Qty2, A.Unit_Type_ID1, A.Unit_Type_ID2, B.Unit_Type_Name, A.itemConvertion
				FROM titem A
				INNER JOIN tbl_unittype B ON A.Unit_Type_ID1 = B.Unit_Type_ID
				ORDER BY A.Item_Name";
		return $this->db->query($sql);
	}
	
	function getNumRowDocPat($MenuCode, $docPatternPosition)
	{
		$this->db->where('menu_code', $MenuCode);
		$this->db->where('Pattern_Position', $docPatternPosition);
		return $this->db->count_all('tbl_docpattern');
	}
	
	// Add by DH on March, 7 2014
	function count_all_num_rows_inbox()
	{
		/*$sql	= 	"SELECT count(*)
					FROM TPO_Header
					WHERE Approval_Status NOT IN (3,4,5)";
		return $this->db->count_all($sql);*/
		$this->db->where('Approval_Status', 0);
		$this->db->where('Approval_Status', 1);
		$this->db->where('Approval_Status', 2);
		return $this->db->count_all('TPO_Header');
	}
	
	function get_last_ten_PR_inbox($limit, $offset)
	{
		$sql = "SELECT A.MR_Number, A.PR_Date, A.Approval_Status, A.PR_Status, A.Vend_Code, A.PR_Notes, A.PR_EmpID, B.First_Name, B.Middle_Name, B.Last_Name
				FROM TPO_Header A
				INNER JOIN  tbl_employee B ON A.PR_EmpID = B.Emp_ID
				ORDER BY A.MR_Number";
		
		/*$this->db->select('MR_Number, PR_Date, Approval_Status, PR_Status, Vend_Code, PR_Notes, PR_EmpID');
		$this->db->from('TPO_Header');
		$this->db->order_by('PR_Date', 'asc');*/
		//$this->db->limit($limit, $offset);
		//return $this->db->get();
		return $this->db->query($sql);
	}
	
	function get_all_asexpjInbox($limit, $offset, $DefEmp_ID) // USED
	{
		// Hanya akan menampilkan project yang sudah ada MR nya
		$sql 		= "SELECT DISTINCT A.proj_Number, A.PRJCODE, A.PRJNAME, A.PRJDATE, A.PRJEDAT, A.PRJSTAT FROM tbl_project A
							INNER JOIN	tbl_item_tsfh D ON A.PRJCODE = D.PRJCODE
						WHERE A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
						ORDER BY A.PRJCODE";
		/*$sql = "SELECT A.proj_Number, A.PRJCODE, A.PRJNAME, A.PRJDATE, A.PRJEDAT, A.PRJSTAT
				FROM tbl_project A
				INNER JOIN tbl_item_tsfh D ON A.PRJCODE = D.PRJCODE
				ORDER BY A.PRJCODE";*/
		return $this->db->query($sql);
	}
	
	function get_all_asexpjInbox_PNo($limit, $offset, $txtSearch, $DefEmp_ID) // USED
	{
		// Hanya akan menampilkan project yang sudah ada MR nya
		$sql = "SELECT A.proj_Number, A.PRJCODE, A.PRJNAME, A.PRJDATE, A.PRJEDAT, A.PRJSTAT
				FROM tbl_project A
				INNER JOIN tbl_item_tsfh D ON A.PRJCODE = D.PRJCODE
				WHERE A.PRJCODE LIKE '%$txtSearch%' AND A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
				ORDER BY A.PRJCODE";
		return $this->db->query($sql);
	}
	
	function get_all_asexpjInbox_PNm($limit, $offset, $txtSearch, $DefEmp_ID) // USED
	{
		// Hanya akan menampilkan project yang sudah ada MR nya
		$sql = "SELECT A.proj_Number, A.PRJCODE, A.PRJNAME, A.PRJDATE, A.PRJEDAT, A.PRJSTAT
				FROM tbl_project A
				INNER JOIN tbl_item_tsfh D ON A.PRJCODE = D.PRJCODE
				WHERE A.PRJNAME LIKE '%$txtSearch%' AND A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
				ORDER BY A.PRJCODE";
		return $this->db->query($sql);
	}
	
	// Update Project Plan Material
	function updatePP($SPPNUM, $parameters) // USED
	{
		$PRJCODE 	= $parameters['PRJCODE'];
    	$SPPNUM 	= $parameters['SPPNUM'];
		$SPPCODE 	= $parameters['SPPCODE'];
		$CSTCODE 	= $parameters['CSTCODE'];
		$SPPVOLM 	= $parameters['SPPVOLM'];
				
		$sqlGet		= "SELECT A.request_qty, A.request_qty2
						FROM tbl_projplan_material A
						WHERE A.PRJCODE = '$PRJCODE' AND A.CSTCODE = '$CSTCODE'";
		$resREQPlan	= $this->db->query($sqlGet)->result();
		foreach($resREQPlan as $rowRP) :
			$request_qty 	= $rowRP->request_qty;
			$request_qty2 	= $rowRP->request_qty2;
		endforeach;
		$totMRQty1	= $request_qty + $SPPVOLM;
		$totMRQty2	= $request_qty2 + $SPPVOLM;
		$sqlUpd		= "UPDATE tbl_projplan_material SET request_qty = $totMRQty1, request_qty2 = $totMRQty2
						WHERE PRJCODE = '$PRJCODE' AND CSTCODE = '$CSTCODE'";
		$this->db->query($sqlUpd);
	}
	
	function count_all_PO($PR_NUM) // OK
	{
		$sql	= "tbl_po_header A
						INNER JOIN tbl_item_tsfh B ON A.PR_NUM = B.PR_NUM
							AND B.PR_STAT IN (3,6)
						INNER JOIN tbl_supplier C ON A.SPLCODE = C.SPLCODE
					WHERE A.PR_NUM = '$PR_NUM' AND A.PO_STAT IN (3,6)";
		return $this->db->count_all($sql);
	}
	
	function get_all_PO($PR_NUM) // OK
	{
		$sql 	= "SELECT A.PO_NUM, A.PO_DATE, A.PR_NUM, B.PR_DATE, A.PO_DUED, A.SPLCODE, A.PR_NUM, C.SPLDESC
					FROM tbl_po_header A
						INNER JOIN tbl_item_tsfh B ON A.PR_NUM = B.PR_NUM
							AND B.PR_STAT IN (3,6)
						INNER JOIN tbl_supplier C ON A.SPLCODE = C.SPLCODE
					WHERE A.PR_NUM = '$PR_NUM' AND A.PO_STAT IN (3,6)";
		return $this->db->query($sql);
	}

	function checkMR($PRJCODE, $ITMTSF_REFNO, $ITMTSF_NUM) // GOOD
	{
		$ISCLOSE	= 1;
		$sqlMRD		= "SELECT PRJCODE, JO_NUM, ITM_CODE, MR_VOLM FROM tbl_mr_detail WHERE MR_NUM = '$ITMTSF_REFNO'
						AND PRJCODE = '$PRJCODE'";
		$resMRD		= $this->db->query($sqlMRD)->result();
		foreach($resMRD as $rowMR) :
			$PRJCODE 	= $rowMR->PRJCODE;
			$JO_NUM 	= $rowMR->JO_NUM;
			$ITM_CODE 	= $rowMR->ITM_CODE;
			$MR_VOLM 	= $rowMR->MR_VOLM;

			// GET TOTAL TSF
				$TOTTSFB	= 0;
				$sqlTSFB	= "SELECT SUM(A.ITMTSF_VOLM) AS TOTTSF FROM tbl_item_tsfd A
								INNER JOIN tbl_item_tsfh B ON A.ITMTSF_NUM = B.ITMTSF_NUM
									AND B.PRJCODE = '$PRJCODE'
								WHERE B.ITMTSF_REFNO = '$ITMTSF_REFNO' AND B.ITMTSF_STAT IN (3,6)
									AND A.ITM_CODE = '$ITM_CODE'";
				$resTSFB	= $this->db->query($sqlTSFB)->result();
				foreach($resTSFB as $rowTSFB) :
					$TOTTSFB 	= $rowTSFB->TOTTSF;
					if($TOTTSFB == '')
						$TOTTSFB= 0;
				endforeach;
				$TOTTSFB = (float)$TOTTSFB;

			// GET TOTAL TSF
				$TOTTSFC	= 0;
				$sqlTSFC	= "SELECT SUM(A.ITMTSF_VOLM) AS TOTTSF FROM tbl_item_tsfd A
								INNER JOIN tbl_item_tsfh B ON A.ITMTSF_NUM = B.ITMTSF_NUM
									AND B.PRJCODE = '$PRJCODE'
								WHERE B.ITMTSF_NUM = '$ITMTSF_NUM' AND A.ITM_CODE = '$ITM_CODE'";
				$resTSFC	= $this->db->query($sqlTSFC)->result();
				foreach($resTSFC as $rowTSFC) :
					$TOTTSFC 	= $rowTSFC->TOTTSF;
					if($TOTTSFC == '')
						$TOTTSFC= 0;
				endforeach;
				$TOTTSFT	= (float)$TOTTSFC + (float)$TOTTSFB;

			if($TOTTSFT < $MR_VOLM) // JIKA ADA SALAH SATU QTY YANG MASIH KURANG DARI MR
			{
				echo "test<br>";
				$ISCLOSE	= 0;
			}
		endforeach;

		if($ISCLOSE == 1)
		{
			$sqlUpd	= "UPDATE tbl_mr_header SET MR_STAT = 6, STATDESC = 'Close', STATCOL = 'info'
						WHERE MR_NUM = '$ITMTSF_REFNO'";
			$this->db->query($sqlUpd);
		}
	}
}
?>