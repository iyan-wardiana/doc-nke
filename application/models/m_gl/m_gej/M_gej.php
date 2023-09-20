<?php
	/* 
		* Author		= Dian Hermanto
		* Create Date	= 10 Februari 2020
		* File Name		= M_gej.php
		* Location		= -
	*/

	class M_gej extends CI_Model
	{
		public function __construct() // GOOD
		{
			parent::__construct();
			$this->load->database();
		}
		
		function get_AllDataC($PRJCODE, $search, $jrnCat) // GOOD
		{
			if($jrnCat == 'GEJ')					// General Journal
				$jrnCATCODE	 = "'GEJ'";
			elseif($jrnCat == 'PRJ')				// Project
				$jrnCATCODE	 = "'PRJINV'";
			elseif($jrnCat == 'SAL')				// Sales
				$jrnCATCODE	 = "'SINV'";
			elseif($jrnCat == 'INVT')				// Acceptance / Use of Materials
				$jrnCATCODE	 = "'UM','IR'";
			elseif($jrnCat == 'PROD')				// Production
				$jrnCATCODE	 = "'STF'";
			elseif($jrnCat == 'FIN')				// Acceptance / Use of MaterialsFinance and Accounting
				$jrnCATCODE  = "'PINV', 'BP', 'BR', 'CHO', 'VCASH', 'TTK-D'";
			elseif($jrnCat == 'OPN')				// Opname
				$jrnCATCODE	 = "'OPN'";
			elseif($jrnCat == 'LPM')				// LPM
				$jrnCATCODE	 = "'IR'";

			$sql = "tbl_journaldetail A
						INNER JOIN tbl_journalheader B ON B.JournalH_Code = A.JournalH_Code
					WHERE A.proj_Code = '$PRJCODE' AND B.GEJ_STAT = '3' AND A.JournalType IN ($jrnCATCODE)
						AND (A.JournalH_Code LIKE '%$search%' ESCAPE '!' OR B.Manual_No LIKE '%$search%' ESCAPE '!'
						OR A.Acc_Id LIKE '%$search%' ESCAPE '!' OR A.Acc_Name LIKE '%$search%' ESCAPE '!'
						OR A.Base_Debet LIKE '%$search%' ESCAPE '!' OR A.Base_Kredit LIKE '%$search%' ESCAPE '!'
						OR B.JournalH_Desc LIKE '%$search%' ESCAPE '!' OR B.CREATERNM LIKE '%$search%' ESCAPE '!'
						OR A.Other_Desc LIKE '%$search%' ESCAPE '!')";
			return $this->db->count_all($sql);
		}
		
		function get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir, $jrnCat) // GOOD
		{
			if($jrnCat == 'GEJ')					// General Journal
				$jrnCATCODE	 = "'GEJ'";
			elseif($jrnCat == 'PRJ')				// Project
				$jrnCATCODE	 = "'PRJINV'";
			elseif($jrnCat == 'SAL')				// Sales
				$jrnCATCODE	 = "'SINV'";
			elseif($jrnCat == 'INVT')				// Acceptance / Use of Materials
				$jrnCATCODE	 = "'UM','IR'";
			elseif($jrnCat == 'PROD')				// Production
				$jrnCATCODE	 = "'STF'";
			elseif($jrnCat == 'FIN')				// Acceptance / Use of MaterialsFinance and Accounting
				$jrnCATCODE  = "'PINV', 'BP', 'BR', 'CHO', 'VCASH', 'TTK-D'";
			elseif($jrnCat == 'OPN')				// Opname
				$jrnCATCODE	 = "'OPN'";
			elseif($jrnCat == 'LPM')				// LPM
				$jrnCATCODE	 = "'IR'";

			if($length == -1)
			{
				if($order !=null)
				{
					$sql = "SELECT DISTINCT A.JournalH_Code
							FROM tbl_journaldetail A
								INNER JOIN tbl_journalheader B ON B.JournalH_Code = A.JournalH_Code
							WHERE A.proj_Code = '$PRJCODE' AND B.GEJ_STAT = 3 AND A.JournalType IN ($jrnCATCODE)
								AND (A.JournalH_Code LIKE '%$search%' ESCAPE '!' OR B.Manual_No LIKE '%$search%' ESCAPE '!'
								OR A.Acc_Id LIKE '%$search%' ESCAPE '!' OR A.Acc_Name LIKE '%$search%' ESCAPE '!'
								OR A.Base_Debet LIKE '%$search%' ESCAPE '!' OR A.Base_Kredit LIKE '%$search%' ESCAPE '!'
								OR B.JournalH_Desc LIKE '%$search%' ESCAPE '!' OR B.CREATERNM LIKE '%$search%' ESCAPE '!'
								OR A.Other_Desc LIKE '%$search%' ESCAPE '!')
							ORDER BY B.LastUpdate DESC, $order $dir";
				}
				else
				{
					$sql = "SELECT DISTINCT A.JournalH_Code
							FROM tbl_journaldetail A
								INNER JOIN tbl_journalheader B ON B.JournalH_Code = A.JournalH_Code
							WHERE A.proj_Code = '$PRJCODE' AND B.GEJ_STAT = 3 AND A.JournalType IN ($jrnCATCODE)
								AND (A.JournalH_Code LIKE '%$search%' ESCAPE '!' OR B.Manual_No LIKE '%$search%' ESCAPE '!'
								OR A.Acc_Id LIKE '%$search%' ESCAPE '!' OR A.Acc_Name LIKE '%$search%' ESCAPE '!'
								OR A.Base_Debet LIKE '%$search%' ESCAPE '!' OR A.Base_Kredit LIKE '%$search%' ESCAPE '!'
								OR B.JournalH_Desc LIKE '%$search%' ESCAPE '!' OR B.CREATERNM LIKE '%$search%' ESCAPE '!'
								OR A.Other_Desc LIKE '%$search%' ESCAPE '!') ORDER BY B.LastUpdate DESC";
				}
				return $this->db->query($sql);
			}
			else
			{
				if($order !=null)
				{
					$sql = "SELECT DISTINCT A.JournalH_Code
							FROM tbl_journaldetail A
								INNER JOIN tbl_journalheader B ON B.JournalH_Code = A.JournalH_Code
							WHERE A.proj_Code = '$PRJCODE' AND B.GEJ_STAT = 3 AND A.JournalType IN ($jrnCATCODE)
								AND (A.JournalH_Code LIKE '%$search%' ESCAPE '!' OR B.Manual_No LIKE '%$search%' ESCAPE '!'
								OR A.Acc_Id LIKE '%$search%' ESCAPE '!' OR A.Acc_Name LIKE '%$search%' ESCAPE '!'
								OR A.Base_Debet LIKE '%$search%' ESCAPE '!' OR A.Base_Kredit LIKE '%$search%' ESCAPE '!'
								OR B.JournalH_Desc LIKE '%$search%' ESCAPE '!' OR B.CREATERNM LIKE '%$search%' ESCAPE '!'
								OR A.Other_Desc LIKE '%$search%' ESCAPE '!')
							ORDER BY B.LastUpdate DESC, $order $dir LIMIT $start, $length";
				}
				else
				{
					$sql = "SELECT DISTINCT A.JournalH_Code
							FROM tbl_journaldetail A
								INNER JOIN tbl_journalheader B ON B.JournalH_Code = A.JournalH_Code
							WHERE A.proj_Code = '$PRJCODE' AND B.GEJ_STAT = 3 AND A.JournalType IN ($jrnCATCODE)
								AND (A.JournalH_Code LIKE '%$search%' ESCAPE '!' OR B.Manual_No LIKE '%$search%' ESCAPE '!'
								OR A.Acc_Id LIKE '%$search%' ESCAPE '!' OR A.Acc_Name LIKE '%$search%' ESCAPE '!'
								OR A.Base_Debet LIKE '%$search%' ESCAPE '!' OR A.Base_Kredit LIKE '%$search%' ESCAPE '!'
								OR B.JournalH_Desc LIKE '%$search%' ESCAPE '!' OR B.CREATERNM LIKE '%$search%' ESCAPE '!'
								OR A.Other_Desc LIKE '%$search%' ESCAPE '!') ORDER BY B.LastUpdate DESC
							LIMIT $start, $length";
				}
				return $this->db->query($sql);
			}
		}
		
		function get_AllDataAC($PRJCODE, $search, $STARTD, $ENDD, $GEJSTAT) // GOOD
		{
			$QRYPRJ 	= "AND A.proj_Code = '$PRJCODE'";
			if($PRJCODE == 'All')
				$QRYPRJ	 = "";

			$QRYSTAT = "";
			if($GEJSTAT == 3)
				$QRYSTAT = "A.GEJ_STAT = 3 AND";
			elseif($GEJSTAT == 9)
				$QRYSTAT = "A.GEJ_STAT = 9 AND";


			$sql = "tbl_journaldetail A
						INNER JOIN tbl_journalheader B ON B.JournalH_Code = A.JournalH_Code
					WHERE $QRYSTAT (A.JournalH_Date BETWEEN '$STARTD' AND '$ENDD') $QRYPRJ 
						AND (A.JournalH_Code LIKE '%$search%' ESCAPE '!' OR B.Manual_No LIKE '%$search%' ESCAPE '!'
						OR A.Acc_Id LIKE '%$search%' ESCAPE '!' OR A.Acc_Name LIKE '%$search%' ESCAPE '!'
						OR A.Base_Debet LIKE '%$search%' ESCAPE '!' OR A.Base_Kredit LIKE '%$search%' ESCAPE '!'
						OR B.JournalH_Desc LIKE '%$search%' ESCAPE '!' OR B.CREATERNM LIKE '%$search%' ESCAPE '!'
						OR A.Other_Desc LIKE '%$search%' ESCAPE '!')";
			return $this->db->count_all($sql);
		}
		
		function get_AllDataAL($PRJCODE, $search, $length, $start, $order, $dir, $STARTD, $ENDD, $GEJSTAT) // GOOD
		{
			$QRYPRJ 	= "AND A.proj_Code = '$PRJCODE'";
			if($PRJCODE == 'All')
				$QRYPRJ	 = "";

			$QRYSTAT = "";
			if($GEJSTAT == 3)
				$QRYSTAT = "A.GEJ_STAT = 3 AND";
			elseif($GEJSTAT == 9)
				$QRYSTAT = "A.GEJ_STAT = 9 AND";

			if($length == -1)
			{
				if($order !=null)
				{
					$sql = "SELECT DISTINCT A.JournalH_Code, A.proj_Code, A.JournalH_Date, A.isLock, A.JournalType
							FROM tbl_journaldetail A
								INNER JOIN tbl_journalheader B ON B.JournalH_Code = A.JournalH_Code
							WHERE $QRYSTAT (A.JournalH_Date BETWEEN '$STARTD' AND '$ENDD') $QRYPRJ 
								AND (A.JournalH_Code LIKE '%$search%' ESCAPE '!' OR B.Manual_No LIKE '%$search%' ESCAPE '!'
								OR A.Acc_Id LIKE '%$search%' ESCAPE '!' OR A.Acc_Name LIKE '%$search%' ESCAPE '!'
								OR A.Base_Debet LIKE '%$search%' ESCAPE '!' OR A.Base_Kredit LIKE '%$search%' ESCAPE '!'
								OR B.JournalH_Desc LIKE '%$search%' ESCAPE '!' OR B.CREATERNM LIKE '%$search%' ESCAPE '!'
								OR A.Other_Desc LIKE '%$search%' ESCAPE '!' OR A.JournalType LIKE '%$search%' ESCAPE '!')
							ORDER BY B.LastUpdate DESC, $order $dir";
				}
				else
				{
					$sql = "SELECT DISTINCT A.JournalH_Code, A.proj_Code, A.JournalH_Date, A.isLock, A.JournalType
							FROM tbl_journaldetail A
								INNER JOIN tbl_journalheader B ON B.JournalH_Code = A.JournalH_Code
							WHERE $QRYSTAT (A.JournalH_Date BETWEEN '$STARTD' AND '$ENDD') $QRYPRJ 
								AND (A.JournalH_Code LIKE '%$search%' ESCAPE '!' OR B.Manual_No LIKE '%$search%' ESCAPE '!'
								OR A.Acc_Id LIKE '%$search%' ESCAPE '!' OR A.Acc_Name LIKE '%$search%' ESCAPE '!'
								OR A.Base_Debet LIKE '%$search%' ESCAPE '!' OR A.Base_Kredit LIKE '%$search%' ESCAPE '!'
								OR B.JournalH_Desc LIKE '%$search%' ESCAPE '!' OR B.CREATERNM LIKE '%$search%' ESCAPE '!'
								OR A.Other_Desc LIKE '%$search%' ESCAPE '!' OR A.JournalType LIKE '%$search%' ESCAPE '!') ORDER BY B.LastUpdate DESC";
				}
				return $this->db->query($sql);
			}
			else
			{
				if($order !=null)
				{
					$sql = "SELECT DISTINCT A.JournalH_Code, A.proj_Code, A.JournalH_Date, A.isLock, A.JournalType
							FROM tbl_journaldetail A
								INNER JOIN tbl_journalheader B ON B.JournalH_Code = A.JournalH_Code
							WHERE $QRYSTAT (A.JournalH_Date BETWEEN '$STARTD' AND '$ENDD') $QRYPRJ 
								AND (A.JournalH_Code LIKE '%$search%' ESCAPE '!' OR B.Manual_No LIKE '%$search%' ESCAPE '!'
								OR A.Acc_Id LIKE '%$search%' ESCAPE '!' OR A.Acc_Name LIKE '%$search%' ESCAPE '!'
								OR A.Base_Debet LIKE '%$search%' ESCAPE '!' OR A.Base_Kredit LIKE '%$search%' ESCAPE '!'
								OR B.JournalH_Desc LIKE '%$search%' ESCAPE '!' OR B.CREATERNM LIKE '%$search%' ESCAPE '!'
								OR A.Other_Desc LIKE '%$search%' ESCAPE '!' OR A.JournalType LIKE '%$search%' ESCAPE '!')
							ORDER BY B.LastUpdate DESC, $order $dir LIMIT $start, $length";
				}
				else
				{
					$sql = "SELECT DISTINCT A.JournalH_Code, A.proj_Code, A.JournalH_Date, A.isLock, A.JournalType
							FROM tbl_journaldetail A
								INNER JOIN tbl_journalheader B ON B.JournalH_Code = A.JournalH_Code
							WHERE $QRYSTAT (A.JournalH_Date BETWEEN '$STARTD' AND '$ENDD') $QRYPRJ 
								AND (A.JournalH_Code LIKE '%$search%' ESCAPE '!' OR B.Manual_No LIKE '%$search%' ESCAPE '!'
								OR A.Acc_Id LIKE '%$search%' ESCAPE '!' OR A.Acc_Name LIKE '%$search%' ESCAPE '!'
								OR A.Base_Debet LIKE '%$search%' ESCAPE '!' OR A.Base_Kredit LIKE '%$search%' ESCAPE '!'
								OR B.JournalH_Desc LIKE '%$search%' ESCAPE '!' OR B.CREATERNM LIKE '%$search%' ESCAPE '!'
								OR A.Other_Desc LIKE '%$search%' ESCAPE '!' OR A.JournalType LIKE '%$search%' ESCAPE '!') ORDER BY B.LastUpdate DESC
							LIMIT $start, $length";
				}
				return $this->db->query($sql);
			}
		}
	}
?>